<?php
    /**
     * File: Controller.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Decription: This class controls the application behavior and call the
     *  observers to compose the final HTML
     * Release: July/2009
     *
    **/
    require_once("Message.php");
    require_once("Observer.php");
    require_once("Response.php");
    require_once("InvalidObserverException.php");
    require_once("InvalidURLException.php");
    require_once("HTMLDocument.php");
    require_once("app/plugins/default/DefaultObserver.php");
    require_once("GraphXML.php");
    
    class Controller {
        /**
         * @property Observer[] obserList A collection of objects. Each item
         *  from the list will be called to compose the result. It will
         *  be indexed by the plugin id, defined in the configuration file
         *  to the plugin tag on the attribute name
         * @property string template The HTML page to insert the content
         * @property string body The page body to be composed
         * @property string gatewayFile The file that will receive the requests
         * @property HTMLDocument document The document object
         * @property string target The location where the content will be printed.
         *  If NULL the content will be printed directly in the body, otherwise
         *  it will be printed in a div whose id target
         * @property String statsFileName The name of the file that will contain the access statistics
        **/
        protected $observerList = array();
        protected $template;
        protected $body;
        protected $errorPageList;
        protected $gatewayFile;
        protected $document;
        protected $target;
        protected $statsFileName;
        
        /**
         * @method <<constructor>> __construct The class constructor
         * 
        **/
        public function __construct($configFile){
            if( !is_file($configFile) ) {
                throw new InvalidURLException($configFile, "Invalid configuration file");
            }
            $configDocument = new DOMDocument();
            $configDocument->load($configFile);
            $templateNode = $configDocument->getElementsByTagName("template")->item(0);
            $this->setTemplate( $templateNode->getAttribute("file") );
            $this->setTarget($templateNode->getAttribute("content-target"));
            
            $statsFileNode = $configDocument->getElementsByTagName("statistics")->item(0);
            if($statsFileNode)
                $this->statsFileName = $statsFileNode->getAttribute("file");
            
            //=======================Error pages section========================
            $errorPageNodeList = $configDocument->getElementsByTagName("error-page");
            for($i=0; $i<$errorPageNodeList->length; $i++){
                $node = $errorPageNodeList->item($i);
                $this->errorPageList[ $node->getAttribute("code") ] = $node->getAttribute("page");
            }
            //==============================End=================================
            
            //===================Plugin section=================================
            $pluginNodeList = $configDocument->getElementsByTagName("plugin");
            for($i=0; $i < $pluginNodeList->length; $i++){
                $pluginNode = $pluginNodeList->item($i);
                
                $pluginDirName = $pluginNode->getAttribute("url");
                $pluginDir = scandir($pluginDirName);
                foreach($pluginDir as $item){
                //The given directory will be read to gather all the .php files
                //  and compose a require_once call for each one;
                    if(is_file($pluginDirName."/".$item)){
                        $extension = strtolower(substr($item, -3, 3));
                        if($extension == "php"){
                            require_once($pluginDirName."/".$item);
                        }
                    }
                }
               
                $mainClass = new ReflectionClass($pluginNode->getAttribute("main-class"));
                $this->observerList[ $pluginNode->getAttribute("name") ] = $mainClass->newInstance();
            }
            $defaultPluginNodeList = $configDocument->getElementsByTagName("default");
            if($defaultPluginNodeList) {
                $defaultPluginNode = $defaultPluginNodeList->item(0);
                for($i=0; $i<$defaultPluginNode->childNodes->length; $i++) {
                    $dataNode = $defaultPluginNode->childNodes->item($i);
                    
                    if($dataNode->nodeType == XML_ELEMENT_NODE) {
                        $defaultPluginDataObject = new DefaultObserver();
                        $defaultPluginDataObject->setDataDirectory($dataNode->getAttribute("url"));
                        $this->observerList[ $dataNode->getAttribute("name") ] = $defaultPluginDataObject;
                    }
                }
            }
                
            //==============================End=================================
            
            //=======================Gateway File Section=======================
            $gatewayNode = $configDocument->getElementsByTagName("gateway-file")->item(0);
            $this->gatewayFile = $gatewayNode->getAttribute("url");
            //==============================End=================================
            $this->document = new HTMLDocument();
            $this->document->loadHTMLFile($this->template); //Load templeate data
        }
        
        /**
         * @method void subscribe It is the way to an Observer object to be part
         *  of the club :)
         * @param Observer observer
        **/
        public function subscribe($observer) {
            if( !$observer instanceof Observer )
                throw new InvalidObserverException($observer, "Could not append element to observer list.");
            $this->observerList[] = $observer;
        }
        
        /**
         * @method void setTemplate
         * @param string template The template page file URL
        **/
        public function setTemplate($templateFile){
            if(!is_file($templateFile))
                throw new InvalidURLException($templateFile, "The given template file is unreachable");
            $this->template = $templateFile;
        }
        
        /**
         * @method void setTarget Defines the local where the content will be held
         * @param string target The new target
        **/
        public function setTarget($target){
            $this->target = $target;
        }
        
        /**
         * @method string processRequest Receives a request and compose the response
         * @param Request A Request object
         * @return The HTML code for the given request
        **/
        public function processRequest($request=NULL){
            $head = "";//Initiates the head
            $body = "";//Initiates the body
            
            foreach($this->observerList as $observerId => $observer){
                $session = new Session($observerId);
            //Iterates through the the observer list calling the method doAction
                $message = new Message($observer, $request, $session, new Cookie($observerId));
                $response = $observer->doAction($message);
                if( $response->validate() ){
                    $head .= $observer->getPreamble();
                    $body .= "<div id='{$observerId}'>{$response->getInnerHTML()}</div>";
                }
                
            }
            
            //===============Request Interception Statistics==================//
            
            if(isset($this->statsFileName)) {
                $graphXML = new GraphXML($this->statsFileName);
                $statsSession = new Session("stats");
                $newNode = $request->getId() != "" ? $request->getId() : "default-page";
                if( ($lastNode = $statsSession->getAttribute("last_node")) != "") {
                    if( $nodeFrom = $graphXML->getNodeById($lastNode) ) {
                        if( $fromEdgeTo = $nodeFrom->getAdjacentById($newNode) ) {
                            $fromEdgeTo->setWeight( $fromEdgeTo->getWeight() + 1 );
                        }
                        else {
                            $nodeTo = new Node($newNode);
                            $fromEdgeTo = new Edge($nodeTo, 1);
                            $nodeFrom->addAdjacentEdge( $fromEdgeTo );
                            $graphXML->addNode($nodeTo);
                        }
                    }
                    else {
                        $nodeFrom = new Node($lastNode);
                        $nodeTo = new Node($newNode);
                        $fromEdgeTo = new Edge($nodeTo, 1);
                        $nodeFrom->addAdjacentEdge( $fromEdgeTo );
                        $graphXML->addNode($nodeFrom);
                        $graphXML->addNode($nodeTo);
                    }
                    $graphXML->save();
                }
                $statsSession->setAttribute("last_node", $newNode);
            }
            
            //============================End==================================//
            try{
                $this->document->setHead($head);
                $this->document->setBody($body, $this->target);
                $this->document->updateRequests($this->gatewayFile);
                return $this->document->saveHTML();
            }
            catch(Exception $e){
                echo($e->getMessage());
                return $this->error(500); //Internal error
            }
        }
        
        /**
         * @method string errorPage Retur the requested error page.
         * @param int error The error number
         * @return An HTML error page
        **/
        public function error($errorCode){
            $errorPageFile = $this->errorPageList[$errorCode];
            if( is_file($errorPageFile) ){
                return file_get_contents($errorPageFile);
            }
            return "Error " . $errorCode;
        }
    }
?>