<?php
    /**
     * File: QuestionChain.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class is a wrapper for Question object representing the chaining
     *  restrictions between Question objects
     * Release: October/2009
     **/
    require_once("Question.php");
    
    class QuestionChain {
        /**
         * @property Question wrapped The wrapped Question object
         * @property mixed inputId The id of the Input object to test
         * @property mixed answer The expected answer
        **/
        private $wrapped;
        private $inputId;
        private $answer;
        
        /**
         * @method <<constructor>> __construct The class constructor
         * @param Question question The question object to wrap
         * @param mixed inputId The id of the input to test
         * @param mixed answer The expected answer
        **/
        public function __construct($question, $inputId, $answer){
            $this->wrapped = $question;
            $this->inputId = $inputId;
            $this->answer = $answer;
        }
        
        /**
         * @method Question getQuestion
         * @return The wrapped Question object
        **/
        public function getQuestion(){
            return $this->wrapped;
        }
        
        /**
         * @method mixed getInputId
         * @return The id of the input
        **/
        public function getInputId() {
            return $this->inputId;
        }
        
        /**
         * @method mixed getAnswer
         * @return The expected answer
        **/
        public function getAnswer() {
            return $this->answer;
        }
    }
?>