<?php
    /**
     * File: Input.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class represents the data inputs
     * Release: September/2009
     **/
    
    require_once("InputType.php");
    require_once("Answer.php");
    
    class Input {
        /**
         * @property misc id The input id
         * @property Input implies A chained object to be called
         * @property misc value The input default value
         * @property InputType type The input type
         * @property String caption The input caption
         * @property Answer answer The answer for the input
        **/
        protected $id;
        protected $implies;
        protected $value;
        protected $type;
        protected $caption;
        protected $answer;
        protected $maxLength;
        
        /**
         * @method <<constructor>> __construct The class default constructor
         * @param misc The default value
         **/
        public function __construct($id, $type, $caption="", $value=NULL, $maxLength=NULL){
            $this->id = $id;
            $this->type = $type;
            $this->value = $value;
            $this->caption = $caption;
            $this->implies = NULL;
            $this->answer = NULL;
            $this->maxLength = $maxLength;
        }
        
        /**
         * @method void setImplies Defines a chained input object
         * @param Input implied The object
         **/
        public function setImplies($implied){
            if($implied instanceof Input)
                $this->implies = $implied;
        }
        
        /**
         * @method misc getId
         * @return The input id
        **/
        public function getId(){
            return $this->id;
        }
        
        /**
         * @method Input getImplies
         * @return The chained object or NULL
         **/
        public function getImplies(){
            return $this->implies;
        }
        
        /**
         * @method misc getValue
         * @return The default value of the object
         **/
        public function getValue() {
            return $this->value;
        }
        
        /**
         * @method string getView
         * @return A HTML version of the data
        **/
        public function getView(){
            $html = "";
            switch($this->type){
                case InputType::CHECKBOX:
                    $html = "<input type='checkbox' name='{$this->id}' />{$this->caption}<br/>";
                    break;
                case InputType::RADIO:
                    $html = "<input type='radio' name='{$this->id}' /> {$this->caption}<br/>";
                    break;
                case InputType::TEXT:
                    if($this->maxLength && $this->maxLength > 50) {
                        $html = "{$this->caption}<br/>";
                        $html .= "<textarea cols='80' rows='10' name='{$this->id}' value='{$this->value}>";
                        $html .= "</textarea><br/>";
                    }
                    else {
                        $html = "{$this->caption}<input type='text' name='{$this->id}' value='{$this->value}' /><br/>";
                    }
                    break;
            }
            return $html;
        }
        
        /**
         * @method void setAnswer
         * @param Answer answer An Answer object to the input
        **/
        public function setAnswer($answer){
            if(!$answer instanceof Answer)
                throw new Exception("Invalid Answer object given for class input");
            $this->answer = $answer;
        }
        
        /**
         * @method Answer getAnswer
         * @return The input answer
        **/
        public function getAnswer() {
            return $this->answer;
        }
    }
?>