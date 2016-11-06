<?php
    /**
     * File: InputUnique.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class represents an input with many options but one to be chosen
     * Release: November/2009
     **/
    
    require_once("Input.php");
    class InputOption {
        /**
         * @property String caption The option caption
         * @property mixed value The option value
        **/
        protected $caption;
        protected $value;
        
        /**
         * @method <<constructor>> __construct Class constructor
         * @param String caption The caption
         * @param mixed value The value
        **/
        public function __construct($caption, $value) {
            $this->caption = $caption;
            $this->value = $value;
        }
        
        /**
         * @method String getCaption
         * @return The option caption
        **/
        public function getCaption() {
            return $this->caption;
        }
        
        /**
         * @method mixed getValue
         * @return The option value
        **/
        public function getValue(){
            return $this->value;
        }
    }
    class InputUnique extends Input {
        /**
         * @property Option optionList A list of options for the input
        **/
        protected $optionList = array();
        
        /**
         * @method <<constructor>> __construct The class default constructor
         * @param misc The default value
         **/
        public function __construct($id){
            $this->id = $id;
            $this->type = InputType::UNIQUE;
        }
        
        /**
         * @method void addOption
         * @param InputOption option The option to be added
        **/
        public function addOption($option) {
            $this->optionList[] = $option;
        }
        
        /**
         * @method int getOptionCount
         * @return The number of options of the input
        **/
        public function getOptionCount() {
            return count($this->optionList);
        }
        
        /**
         * @method InputOption getOption
         * @param int n The nth option to be returned
         * @return The nth option or throws an exception
        **/
        public function getOption($n){
            if($n > $this->getOptionCount())
                throw new Exception("Index out of bounds");
            return $this->optionList[$n];
        }
        
        /**
         * @method overloaded String getView
         * @return A HTML version of the input. This function is overloaded
         *  to set an unique id for many input options
        **/
        public function getView() {
            $html = "";
            foreach($this->optionList as $option) {
                $html .= "<input type='radio' name='{$this->id}' value='{$option->getValue()}'/> {$option->getCaption()}<br/>";
            }
            return $html;
        }
    }
?>