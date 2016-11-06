<?php
    /**
     * File HTMLDocument.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Decription: A DOM implementation for HTML files
     * Release: July/2009
     *
    **/
    
    class HTMLDocument extends DOMDocument {
        /**
         * @property DOMElement head
         * @property DOMElement body
        **/
        private $head;
        private $body;
        
        /**
         * @method <<constructor>> __construct
        **/
        public function __construct(){
            parent::__construct();
            $this->formatOutput = true;
        }
        
        /**
         * @method void setBody Appends content to the body
         * @param string data The data to append
        **/
        public function setBody($body, $target=NULL){
            $tempDoc = new DOMDocument();
            if(!$body) return;
            @$tempDoc->loadHTML($body);
            $tempBody = $tempDoc->getElementsByTagName("body")->item(0);
            for($i=0; $i<$tempBody->childNodes->length; $i++){
                $node = $this->importNode( $tempBody->childNodes->item($i), true );
                if($node){
                    if($target){
                        $body = $this->getElementById($target);
                    }
                    else {
                        $body = $this->getElementsByTagName("body")->item(0);
                    }
                    $body->appendChild($node);
                }
            }
        }
        
        /**
         * @method void setHead Put the given element in the head of the page
         * @param string head The content to add
        **/
        public function setHead($head) {
            if(!$head) return;
            $contentNode = $this->createDocumentFragment();
            $contentNode->appendXML($head);
            
            $headNode = $this->getElementsByTagName("head")->item(0);
            if( !$headNode){
                $headNode = $this->createElement("head");
                $this->appendChild($headNode);
            }
            $headNode->appendChild($contentNode);
        }
        
        /**
         * @method void updateRequests Changes the requests of forms and hyperlinks
         * @param string The gateway file that will gather the requests
        **/
        public function updateRequests($gateway){
            //==============Form Section========================================
            $formNodeList = $this->getElementsByTagName("form");
            for($i=0; $i<$formNodeList->length; $i++){
                $formNode = $formNodeList->item($i);
                $action = $formNode->getAttribute("action");
                $formNode->setAttribute("action", $gateway);
                
                $hiddenActionNode = $this->createElement("input");
                $hiddenActionNode->setAttribute("type", "hidden");
                $hiddenActionNode->setAttribute("name", "form_gateway_action");
                $hiddenActionNode->setAttribute("value", $action);
                
                $formNode->appendChild($hiddenActionNode);
            }
            //================================End===============================
            //=========================Hyperlink Section========================
            $hyperlinkNodeList = $this->getElementsByTagName("a");
            for($i=0; $i<$hyperlinkNodeList->length; $i++){
                $hyperlinkNode = $hyperlinkNodeList->item($i);
                $href = $hyperlinkNode->getAttribute("href");
                $href = str_replace("?", "&", $href);
                $newHref = "{$gateway}?form_gateway_action={$href}";
                $hyperlinkNode->setAttribute("href", $newHref);
            }
            //================================End===============================
        }

    }
?>