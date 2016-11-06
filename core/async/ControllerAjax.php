<?php
    /**
     * File: ControllerAjax.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: A Asynchronous version for the solution
     * Release: July/2009
     *
    **/
    require_once("core/sync/Controller.php");
    require_once("core/sync/Request.php");
    require_once("RequestAjax.php");
    require_once("xajax/xajax_core/xajax.inc.php");
    require_once("HTMLFragmentAjax.php");
    
    class ControllerAjax extends Controller {
        /**
         * @property xajax xAjax the Ajax object
        **/
        private $xAjax;
        
        /**
         * @method <<constructor>> __construct
         * @param string configFile The configuration file
        **/
        public function __construct($configFile){
            parent::__construct($configFile);            
            $this->xAjax = new xajax();
            //$this->xAjax->setFlag("debug", true);
            $this->xAjax->register(XAJAX_CALLABLE_OBJECT, $this);
            $this->xAjax->configure("javascript URI","core/async/xajax/");
            $this->xAjax->processRequest();
        }
        
        /**
         * @method string processRequest Receives a request and compose the response.
         *  The main difference between this method and Controllers is that
         *  in this case the Javascript code must be printed in the head of the
         *  page.
         * @param Request A Request object
         * @return The HTML code for the given request
        **/
        public function processRequest($request=NULL){
            $head = "";//Initiates the head
            $body = "";//Initiates the body
            $dummy = new RequestAjax(array());
            foreach($this->observerList as $observerId => $observer){
            //Iterates through the the observer list calling the method doAction
                $message = new Message($observer, $request, new Session($observerId), new Cookie($observerId));
                $response = $observer->doAction($message);
                if( $response->validate() ){
                    $htmlFragment = new HTMLFragmentAjax($observerId);
                    $htmlFragment->loadHTML($response->getInnerHTML());
                    $htmlFragment->updateRequests("xajax_ControllerAjax.processAjaxRequest");
                    $head .= $observer->getPreamble();
                    $body .= "<div id='{$observerId}'>{$htmlFragment->saveHTML()}</div>";
                }
            }
            try{
                $this->document->setHead($head.$this->xAjax->getJavascript());
                $this->document->setBody($body, $this->target);
                return $this->document->saveHTML();
            }
            catch(DOMException $e){
                echo($e->getMessage());
                return $this->error(500); //Internal error
            }
        }
        
        /**
         * @method string processAjaxRequest Receives a request and compose the response
         * @param Request A Request object
         * @return The HTML code for the given request
        **/
        public function processAjaxRequest($requestArray=NULL){
            $request = new RequestAjax($requestArray);
            $observerId = $request->getObserverId();
            $observer = $this->observerList[$observerId];
            $message = new Message($observer, $request, new Session($observerId), new Cookie($observerId));
            $response = $observer->doAction($message);
            
            $htmlFragment = new HTMLFragmentAjax($observerId);
            $htmlFragment->loadHTML($response->getInnerHTML());
            $htmlFragment->updateRequests("xajax_ControllerAjax.processAjaxRequest");
            
            return $htmlFragment->getAjaxResponse();
            
            //$xajaxResponse = new xajaxResponse();
            
            //$xajaxResponse->assign($observerId, "innerHTML", $htmlFragment->saveHTML());
            //$xajaxResponse->script("writeRichText('rte1', '', 400, 200, true, false);");
            //$xajaxResponse->alert("{$htmlFragment->saveHTML()}");
            //return $xajaxResponse;
        }
    }
?>