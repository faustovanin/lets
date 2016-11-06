<?php
    /**
     * File: AHMLObserver.php*
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This is the default class of this plugin and contain the lifecicle of
     *  the survey with the Adaptive Hypermedia Survey Markup Language
     * Release: September/2009
     **
    **/
    
    class AHMLObserver implements Observer {
        /**
         * @property Mode mode The active mode in use. Could be:
         *  SurveyMode: for the cases where the user will answer a whole set os
         *      questions
         *  QuestionMode: for the cases where the questions are from different
         *      sets
        **/
        protected $mode;
        
        /**
         * @method <<constructor>> __construct Class constructor
        **/
        public function __construct(){
            $configFile = "app/config/ahmlconfig.xml";
            $configObj = new DOMDocument();
            $configObj->load($configFile);
            
            $globalConfig = $configObj->getElementsByTagName("ahml-global")->item(0);
            switch($globalConfig->getAttribute("mode")){
                case "survey":
                    $this->mode = new SuveyMode();
                    break;
                case "question":
                    $this->mode = NULL;
                    // TODO: Create the class QuestionMode
                    break;
            }
            $defaultRepositoryURL = $globalConfig->getAttribute("default-repository-url");
            Survey::setDefaultRepositoryURL($defaultRepositoryURL);
            Question::setDefaultRepositoryURL($defaultRepositoryURL);
            AnswerList::setDefaultRepositoryURL($defaultRepositoryURL);
            $surveyList = array();
            $surveyNodeList = $globalConfig->getElementsByTagName("survey");
            for($i=0; $i<$surveyNodeList->length; $i++) {
                $surveyNode = $surveyNodeList->item($i);
                $surveyList[ $surveyNode->getAttribute("file-name") ] = $surveyNode->getAttribute("answer-file-name");
            }
            $this->mode->setSurveyList($surveyList);
        }
        
        /**
         * @method Response doAction Implementation of the interface Observer mehtod.
         * The existing use cases are:
         *  . Initiate
         *  . Answer question
         *  . Finalize
         * @param Message message The given message
        **/
        public function doAction($message){
            $response = new Response();
            $request  = $message->getRequest();
            $session  = $message->getSession();
            $surveyId = $request->getParameter("survey_id");
            
            return $this->mode->processMessage($message);
        }
        
        /**
         * @method String getPreamble
         * @return The AHML header
        **/
        public function getPreamble() {
            return "";
        }
        
        /**
         * @method String initiate Starts the process and
         * @param String surveyId The id of the survey to initiate
         * @return the firs question
        **/
        public function initiate($surveyId){
            
        }
    }
?>