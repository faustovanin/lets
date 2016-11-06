<?php
    /**
     * File: InputFactory.php   
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  Applies the Factory Pattern to make a bridge between question and the
     *      input types
     * Release: September/2009
     **/
    require_once("Input.php");
    require_once("InputUnique.php");
    
    class InputFactory {
        /**
         * @method static Input getInput
         * @param DOMNode and object from the XML structure
         * @return A new Input descendant object
        **/
        public static function getInput($node, $number=0) {
            $parent = $node;
            while( !($parent = $parent->parentNode) instanceof DOMDocument);
            $id = $node->getAttribute("id");
            $type = InputType::evaluate($node->getAttribute("type"));
            switch($type) {
                case InputType::UNIQUE:
                    $input = new InputUnique($id);
                    $optionCount = 0;
                    for($i=0; $i<$node->childNodes->length; $i++) {
                        $optionNode = $node->childNodes->item($i);
                        if($optionNode->nodeType == 1) {
                            $optionValue = $optionNode->getAttribute("value");
                            $optionCaption = XmlUtils::getAttribute($parent, "option", "caption", $optionCount++);
                            $input->addOption(new InputOption($optionCaption, $optionValue));
                        }
                    }
                    break;
                default:
                    $caption = XmlUtils::getAttribute($parent, $node->nodeName, "caption", $number);
                    $value = $node->getAttribute("value");
                    $maxLength = $node->getAttribute("maxlength");
                    
                    $input = new Input($id, $type, $caption, $value, $maxlength);
                    
                    $implies = $node->getAttribute("implies");
                    if($implies && $implies != "none"){
                        $implied =  new Input($implies, InputType::UNKNOWN, "no caption");
                        $input->setImplies($implied);
                    }
            }
            
            return $input;
        }
    }
?>