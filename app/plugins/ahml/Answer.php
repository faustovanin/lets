<?php
    /**
     * File: Answer.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class is a single answer
     * Release: September/2009
    **/
    
    require_once("QuestionType.php");
    
    class Answer {
        /**
         * @property QuestionType type The expected type. Could be SINGLE, MULTIPLE or BOOLEAN
         * @property mixed content The answer content
        **/
        protected $type;
        protected $content;
        
        /**
         * @method <<constructor>> __construct
         * @param type The type of the answer. Could be:
         *  - Answer::SINGLE for single valued answers
         *  - Answer::MULTIPLE form multi-valued answers
         *  - Answer::BOOLEAN for TRUE/FALSE answers
         * @param content The answer content
         **/
        public function __construct($type, $content){
            switch($type){
                case QuestionType::SINGLE:
                    $this->content = $content;
                    $this->type = $type;
                    break;
                case QuestionType::MULTIPLE:
                    if(!is_array($content)){
                        $this->content[] = $content;
                    }
                    else $this->content = $content;
                    $this->type = $type;
                    break;
                case QuestionType::BOOLEAN:
                    $validTrueList = array(1, TRUE, "true", "TRUE");
                    $validFalseList = array(0, FALSE, "false", "FALSE");
                    
                    $inTrueList = in_array($content, $validTrueList);
                    $inFalseList = in_array($content, $validFalseList);
                    
                    if(!$inTrueList && !$inFalseList){
                        throw new Exception("Invalid answer type");
                    }
                    
                    $this->content = $inTrueList ? true : false;
                    $this->type = $type;
            }
        }
        
        /**
         * @method int getType
         * @return The type of the answer
        **/
        public function getType() {
            return $this->type;
        }
        
        /**
         * @method misc getContent
         * @return The content of the answer
         **/
        public function getContent(){
            return $this->content;
        }
    }
    
?>