<?php
    /**
     * File: Owner.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class represents a survey owner
     * Release: September/2009
     **/
    
    require_once("Person.php");
    
    class Owner extends Person {
        /**
         * @property Survey[] surveyList The owned surveys
        **/
        private $surveyList = array();
        
        /**
         * @method <<constructor>> __construct
         * @param String name The owner name
        **/
        public function __construct($name){
            parent::__construct($name);
        }
        
        /**
         * @method void addSurvey Adds survey to the owner list
         * @param Survey survey The survey to be added
        **/
        public function addSurvey($survey){
            if($survey instanceof Survey)
                $this->surveyList[] = $survey;
        }
        
        /**
         * @method int getSurveyCount
         * @return The number of survey owned by this user
        **/
        public function getSurveyCount() {
            return count($this->surveyList);
        }
        
        /**
         * @method Survey getSurvey
         * @param int pos The requested survey position
         * @return a Survey object corresponding to the given number
        **/
        public function getSurvey($pos){
            if($pos > 0 && $pos < $this->getSurveyCount())
                return $this->surveyList[$pos];
        }
    }
?>