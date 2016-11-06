<?php
    /**
     * File: HTMLFragmentAjax.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This class represents a portion of HTML to be sent in an
     *  asynchronous request.
     * Release: July/2009
     *
    **/
    
    class HTMLFragmentAjax extends HTMLDocument {
        /**
         * @property string scope The scope where the fragment is
        **/
        private $scope;
        
        /**
         * @method <<constructor>> __construct
         * @param string scope The scope where the fragment is
        **/
        public function __construct($scope) {
            parent::__construct();
            $this->scope = $scope;
        }
        
        /**
         * @method void updateRequests This method, just as the class
         *  HTMLDocument change the requests sending them to a gateway. The
         *  main difference between this method and HTMLDocuments is that
         *  in this case the requests will be changed into onclick Javascript
         *  methods, calling asynchronous procedures.
        **/
        public function updateRequests($gateway){
            //==============Form Section========================================
            $formNodeList = $this->getElementsByTagName("form");
            for($i=0; $i<$formNodeList->length; $i++){
                $formNode = $formNodeList->item($i);
                $action = $formNode->getAttribute("action");
                //$formNode->setAttribute("action", $gateway);
                $formNode->setAttribute("action", "javascript:void(null)");
                $formId = "{$this->scope}_form{$i}";
                $formNode->setAttribute("id", $formId);
                
                $hiddenActionNode = $this->createElement("input");
                $hiddenActionNode->setAttribute("type", "hidden");
                $hiddenActionNode->setAttribute("name", "form_gateway_action");
                $hiddenActionNode->setAttribute("value", $action);
                
                $hiddenObserverNode = $this->createElement("input");
                $hiddenObserverNode->setAttribute("type", "hidden");
                $hiddenObserverNode->setAttribute("name", "form_gateway_observer");
                $hiddenObserverNode->setAttribute("value", $this->scope);
                
                $inputNodeList = $formNode->getElementsByTagName("input");
                for($i=0; $i<$inputNodeList->length; $i++){
                    $childNode = $inputNodeList->item($i);
                    if($childNode->getAttribute("type") == "submit") {
                        $childNode->setAttribute("onClick", "{$gateway}(xajax.getFormValues('{$formId}'));");
                    }
                }
                
                $formNode->appendChild($hiddenActionNode);
                $formNode->appendChild($hiddenObserverNode);
            }
            //================================End===============================
            //=========================Hyperlink Section========================
            $hyperlinkNodeList = $this->getElementsByTagName("a");
            for($i=0; $i<$hyperlinkNodeList->length; $i++){
                $hyperlinkNode = $hyperlinkNodeList->item($i);
                $href = $hyperlinkNode->getAttribute("href");
                
                $hrefParameterValueList = explode("?", $href);
                $newParameterList["form_gateway_action"] = $hrefParameterValueList[0]; //The href itself
                $newParameterList["form_gateway_observer"] = $this->scope;
                
                if(count($hrefParameterValueList)>1){
                    $parameterValueList = explode("&", $hrefParameterValueList[1]);
                    foreach($parameterValueList as $parameterValue){
                        $parameterValuePair = explode("=", $parameterValue);
                        $parameter = $parameterValuePair[0];
                        $value = $parameterValuePair[1];
                        $newParameterList[ $parameter ] = $value;
                    }
                }
                $onClickPreamble = "var requestParameters = new Array();";
                foreach($newParameterList as $parameter => $value){
                    $onClickPreamble .= "requestParameters['{$parameter}'] = '{$value}';";
                }
                $hyperlinkNode->setAttribute("onClick", "{$onClickPreamble}{$gateway}(requestParameters);");
                
                $hyperlinkNode->setAttribute("href", "javascript:void(null)");
            }
            //================================End===============================
        }
        
        /**
         * @method string saveHTML
         * @return A HTML version of the data
        **/
        public function saveHTML(){
            $body = $this->getElementsByTagName("body")->item(0);
            $bodyContent = "";
            for($i=0; $i<$body->childNodes->length; $i++){
                $childNode = $body->childNodes->item($i);
                $bodyContent .= parent::saveXML($childNode);
            }
            $bodyContent = str_replace("<![CDATA[", "", $bodyContent);
            $bodyContent = str_replace("]]>", "", $bodyContent);
            return $bodyContent;
        }
        
        /**
         * @method xajaxResponse getAjaxResponse
         * @return The ajax response, executing all the scripts in the page
        **/
        public function getAjaxResponse(){
            $ajaxResponse = new xajaxResponse();
            $html = $this->saveHTML();
            $ajaxScriptExecuter = new ScriptExecuter($this->scope);
            $ajaxResponse = $ajaxScriptExecuter->process($html);
            return $ajaxResponse;
        }

    }
    class ScriptExecuter {
        private $scriptContext;
        private $parser;
        private $ajaxResponse;
        private $id;
        private $partialContent;
        
        public function __construct($id){
            $this->id = $id;
            $this->scriptContext = false;
            $this->ajaxResponse = new xajaxResponse();
            $this->ajaxResponse->assign($this->id, "innerHTML", "");
            //Clear the current data
            $this->parser = xml_parser_create();
            xml_set_object($this->parser, $this);
            xml_set_character_data_handler($this->parser, "dataHandler");
            xml_set_element_handler($this->parser, "openTag", "closeTag");
        }
        public function __destruct(){
            xml_parser_free($this->parser);
        }
        private function append($content){
            $this->ajaxResponse->append($this->id, "innerHTML", $content);
        }
        private function openTag($parser, $tag, $attributes){
            if($tag == "MAXEXTERNALSCOPE")return;
            if($tag == "SCRIPT"){
                $this->scriptContext = true;
                $this->append($this->partialContent);
                $this->partialContent = "";
            }
            else{
                $this->scriptContext = false;
                $tag = strtolower($tag);
                $this->partialContent .= "<{$tag}";
                foreach($attributes as $attribute => $value){
                    $attribute = strtolower($attribute);
                    $this->partialContent .= " {$attribute}=\"{$value}\"";
                }
                $this->partialContent .= ">";
                
            }
        }
        private function dataHandler($parser, $data){
            if($this->scriptContext){
                $data = str_replace("\n", "", $data);
                $this->ajaxResponse->script($data);
            }
            else {
                $this->partialContent .= $data;
            }
        }
        private function closeTag($parser, $tag){
            if($tag == "MAXEXTERNALSCOPE")return;
            if(!$this->scriptContext){
                $tag = strtolower($tag);
                $this->partialContent .= "</{$tag}>";
            }
        }
        public function process($data){
            $data = "<MAXEXTERNALSCOPE>{$data}</MAXEXTERNALSCOPE>";
            xml_parse($this->parser, $data, true);
            $this->append($this->partialContent);
            $this->partialContent = "";
            return $this->ajaxResponse;
        }
    }
?>