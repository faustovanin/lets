<?php
    /**
     * File: Author.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  A question author class
     * Release: September/2009
     **/
    
    require_once("Person.php");
    
    class Author extends Person {
        /**
         * @property Question[] questionList The Question object collection
        **/
        private $questionList = array();
        
        /**
         * @method: <<constructor>> __construct
         * @param String name The author's name
        **/
        public function __construct($name){
            parent::__construct($name);
        }
        
        /**
         * @method void addQuestion
         * @param Question question The question to be added
        **/
        public function addQuestion($question){
            if($question instanceof Question)
                $this->questionList[] = $question;
        }
        
        /**
         * @method int getQuestionCount
         * @return The number of questions of this author
        **/
        public function getQuestionCount(){
            return count($this->questionList);
        }
        
        /**
         * @method Question getQuestion
         * @param int pos The requested question
         * @return a Question object corresponding to the given number
        **/
        public function getQuestion($pos) {
            if($pos > 0 && $pos < $this->getQuestionCount())
                return $this->questionList[$pos];
        }
    }
?>