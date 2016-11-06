<?php
    /**
     * File: XmlUtils.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  An implementation of static methods to help XML parsing
     * Release: October/2009
     **/
    
    class XmlUtils {
        private $nodeName;
        private $innerContent;
        private $copyData;
        //private $chSearh   = array("ç", "ã", "á", "é", "ê", "ô", "ó");
	private $chSearch = array(224, 225, 227, 231, 233, 234, 237, 243, 244, 245);
        //private $chPartialReplace = array("@ccedil;", "@atilde;", "@aacute;", "@eacute;", "@ecirc;", "@ocirc;", "@oacute;");
	private $chPartialReplace = array("@agrave;", "@aacute;", "@atilde;", "@ccedil;", "@eacute;", "@ecirc;", "@iacute;", "@oacute;");
        //private $chFinalReplace = array("&ccedil;", "&atilde;", "&aacute;", "&eacute;", "&ecirc;", "&ocirc;", "&oacute;");
	private $chFinalReplace   = array("&agrave;", "&aacute;", "&atilde;", "&ccedil;", "&eacute;", "&ecirc;", "&iacute;", "&oacute;");
        private $nodeAttributes;
        private $number;
        private $actualNumber;
        
        public function __construct($xmlData, $nodeName, $number=0) {
            $this->nodeName = $nodeName;
            $this->number = $number;
            $this->actualNumber = 0;
            $parser = xml_parser_create();
            xml_set_element_handler($parser, "openTag", "closeTag");
            xml_set_character_data_handler($parser, "innerData");
            xml_set_object($parser, $this);
            /*foreach($this->chSearch as $idx => $char) {
                $xmlData = str_replace($char, $this->chPartialReplace[$idx], $xmlData);
            }*/
	    $xmlDataReplaced = "";
	    for($i=0; $i<count($xmlData); $i++) {
		foreach($this->chSearch as $idx => $char) {
			if(chr($xmlData[$i]) == $char) {
				$xmlDataReplaced .= $this->chPartialReplace[$idx];
			}
			else {
				$xmlDataReplaced .= $xmlData;
			}
		}
	    }
            xml_parse($parser, $xmlDataReplaced, true);
            xml_parser_free($parser);
        }
        
        private function openTag($parser, $tag, $attributes) {
            if($tag == strtoupper($this->nodeName)) {
                if($this->number == $this->actualNumber){
                    $this->copyData = true;
                    $this->innerContent = "";
                    $this->nodeAttributes = $attributes;
                }
                $this->actualNumber++;
            }
        }
        
        private function closeTag($parser, $tag) {
            $this->copyData = false;
        }
        
        private function innerData($parser, $data) {
            if($this->copyData){
                foreach($this->chPartialReplace as $idx => $char) {
                    $data = str_replace($char, $this->chFinalReplace[$idx], $data);
                }
                $this->innerContent .= $data;
            }
        }
        
        public function getInnerContent() {
            return $this->innerContent;
        }
        
        public function getNodeAttribute($attribute) {
            $attribute = $this->nodeAttributes[strtoupper($attribute)];
            foreach($this->chPartialReplace as $idx => $char) {
                $attribute = str_replace($char, $this->chFinalReplace[$idx], $attribute);
            }
            return $attribute;
        }
        
        /**
         * @method Node getNode
         * @param DomDocument document The given document
         * @param String nodeName The requested node
         * @return The node with the given name or throws an Exception
        **/
        public static function getNode(DOMDocument $document, $nodeName) {
            return $document->getElementsByTagName($nodeName)->item(0);
        }
        
        /**
         * @method String getNodeInnerValue
         * @param DomDocument document The given document
         * @param String nodeName The node to get the content
         * @return The node inner value from the given document
        **/
        public static function getNodeInnerValue($document, $nodeName) {
            //return self::getNode($document, $nodeName)->nodeValue;
            $xmlData = $document->saveXML();
            //$xmlUtil = new XmlUtils($xmlData, $nodeName);
            //return $xmlUtil->getInnerContent();

		$chSearch = array("à", "á", "ã", "ç", "é", "ê", "í", "ó", "ô");
		$chPartialReplace = array("@agrave;", "@aacute;", "@atilde;", "@ccedil;", "@eacute;", "@ecirc;", "@iacute;", "@oacute;", "@ocirc;");
		$chFinalReplace   = array("&agrave;", "&aacute;", "&atilde;", "&ccedil;", "&eacute;", "&ecirc;", "&iacute;", "&oacute;", "&ocirc;");
		
		$xmlDataReplace = str_replace($chSearch, $chPartialReplace, $xmlData);
		$document = new DOMDocument();
		$document->loadXML($xmlDataReplace);
		$node = $document->getElementsByTagName($nodeName)->item(0);
		return str_replace($chPartialReplace, $chFinalReplace, $node->nodeValue);
        }
        
        /**
         * @method String getAttribute
         * @param DOMDocument document The document to search
         * @param String nodeName The node to search
         * @package String attribute The requested attribute
        **/
        public static function getAttribute($document, $nodeName, $attribute, $nodeNumber=0) {
            	$xmlData = $document->saveXML();
		$chSearch = array("à", "á", "ã", "ç", "é", "ê", "í", "ó", "ô");
		$chPartialReplace = array("@agrave;", "@aacute;", "@atilde;", "@ccedil;", "@eacute;", "@ecirc;", "@iacute;", "@oacute;", "@ocirc;");
		$chFinalReplace   = array("&agrave;", "&aacute;", "&atilde;", "&ccedil;", "&eacute;", "&ecirc;", "&iacute;", "&oacute;", "&ocirc;");
		
		$xmlDataReplace = str_replace($chSearch, $chPartialReplace, $xmlData);
		$document = new DOMDocument();
		$document->loadXML($xmlDataReplace);
		$node = $document->getElementsByTagName($nodeName)->item($nodeNumber);
		return str_replace($chPartialReplace, $chFinalReplace, $node->getAttribute($attribute));
            //$xmlUtil = new XmlUtils($document->saveXML(), $nodeName, $nodeNumber);
            //return $xmlUtil->getNodeAttribute($attribute);
        }

	private static function translateAccent($rawData) {

		
	}
    }
?>
