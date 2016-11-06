<?php
    /**
     * File: Survey.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class represents the survey
     * Release: October/2009
     **/
    
    require_once("Question.php");
    require_once("SurveyType.php");
    require_once("Date.php");
    require_once("XmlUtils.php");
    
    class Survey {
        /**
         * @property Question questionList The questions of the survey
         * @property Owner owner The survey owner
         * @property String description The survey description.
         * @property String disclaimer A message to show before the user start
         *  responding
         * @property String closing The final message to be shown
         * @property SurveyType type The survey type
         * @property String title The survey title
         * @property DomDocument document The given XML document
         * @property Date dateOfCreation The date of the survey
         * @property String fileName The XML file name
         * @property String defaultRepositoryURL The directory to search for the files
         * @property AnswerList answerList The list of answers for the current user
        **/
        private $questionList = array();
        private $owner;
        private $description;
        private $disclaimer;
        private $closing;
        private $type;
        private $title;
        private $document;
        private $fileName;
        private static $defaultRepositoryURL;
        private $answerList;
        
        /**
         * @method <<constructor>> __construct Class constructor
         * @param String fileName The file that contains the survey description
        **/
        public function __construct($xmlFile, $answerList){
            $this->fileName = $xmlFile;
            $xmlFile = self::$defaultRepositoryURL . $xmlFile;
            $this->answerList = $answerList;
            if(!is_file($xmlFile)) {
                throw new Exception("Invalid configuration file or repository given: {$xmlFile}");
            }
            $this->document = DOMDocument::load($xmlFile);
            //================Features Reading=========================//
            $surveyTag = XmlUtils::getNode($this->document, "survey");
            $this->dateOfCreation = new Date($surveyTag->getAttribute("date-of-creation"));
            $this->type = SurveyType::evaluate($surveyTag->getAttribute("type"));
            $this->title = XmlUtils::getAttribute($this->document, "survey", "title");
            
            $this->owner = new Owner(XmlUtils::getNodeInnerValue($this->document, "owner"));
            $this->description = XmlUtils::getNodeInnerValue($this->document, "description");
            $this->disclaimer = XmlUtils::getNodeInnerValue($this->document, "disclaimer");
            $this->closing = XmlUtils::getNodeInnerValue($this->document, "closing");
            //============================END==========================//
            //====================Question Session=====================//
            $questionList = $this->document->getElementsByTagName("question");
            
            for($i = 0; $i < $questionList->length; $i++) {
                $this->followNested($questionList->item($i));
            }
        }
        
        /**
         * @method void followNested
         * @param DomNode question
        **/
        public function followNested($question){
            $questionId = $question->getAttribute("id");
            $questionFile = $question->getAttribute("file");
            $questionObject = new Question($questionId, $questionFile, $this->answerList->getAnswerByQuestionId($questionId));
            $nested = false;
            foreach($this->questionList as $questionInList) {
                $nested |= $questionInList->isNested($questionId);
            }
            if(!$nested){
                $this->questionList[] = $questionObject;
            }
            
            $ifQuestionList = $question->childNodes;
            for($i=0; $i<$ifQuestionList->length; $i++) {
                $ifInputNode = $ifQuestionList->item($i);
                if($ifInputNode->nodeName == "if-input") {
                    $inputId = $ifInputNode->getAttribute("id");
            
                    for($j=0; $j<$ifInputNode->childNodes->length; $j++) {
                        $ifAnswerNode = $ifInputNode->childNodes->item($j);
                        if($ifAnswerNode->nodeName == "if-answer") {
                            $answerValue = $ifAnswerNode->getAttribute("value");
            
                            for($k=0; $k<$ifAnswerNode->childNodes->length; $k++) {
                                $questionNode = $ifAnswerNode->childNodes->item($k);
                                if($questionNode->nodeName == "question") {
            
                                    $questionId = $questionNode->getAttribute("id");
                                    $questionFile = $questionNode->getAttribute("file");
                                    $questionObject->addNestedQuestion(new Question($questionId, $questionFile, $this->answerList->getAnswerByQuestionId($questionId)), $inputId, $answerValue);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        /**
         * @method void setDefaultRepositoryURL
         * @param String url The URL to point for XML files
        **/
        public static function setDefaultRepositoryURL($url){
            self::$defaultRepositoryURL = $url;
        }
        
        /**
         * @method String getDefaultRepositoryURL
        **/
        public static function getDefaultRepositoryURL() {
            return self::$defaultRepositoryURL;
        }
        
        /**
         * @method Question getQuestion
         * @param Question question The actual answered question. Default (NULL)
         * @return An object representing the next question or NULL if it has finnished
        **/
        public function getQuestion($previousQuestion = NULL) {
            if(!$previousQuestion) {
                //It means it is just starting
                return $this->questionList[0];
            }
            if(!$previousQuestion->answered()){
                throw new QuestionNotAnsweredException("The given question is not answered.", $previousQuestion);
            }
            $nextQuestion = $previousQuestion->getNext();
            if(!$nextQuestion) {
                $getNext = false; //flag for the next iteration
                foreach($this->questionList as $question) {
                    if($getNext){
                        return $question;
                    }
                    if($question->getId() == $previousQuestion->getId() ||
                       $question->isNested($previousQuestion->getId()) ) {
                        if($nextQuestion = $question->getNext()) return $nextQuestion;
                        $getNext = true; //The next question will be returned
                    }
                }
            }
            else {
                return $nextQuestion;
            }
            return NULL; //$previousQuestion was the last question
        }
        
        /**
         * @method String getTitle
         * @return The survey title
        **/
        public function getTitle(){
            return $this->title;
        }
        
        /**
         * @method Owner getAuthor
         * @return the authors of the survey
        **/
        public function getAuthor() {
            return $this->owner;
        }
        
        /**
         * @method String getDisclaimer
         * @return The survey disclaimer message
        **/
        public function getDisclaimer() {
            return $this->disclaimer;
        }
        
        /**
         * @method String getDescription
         * @return The survey description
        **/
        public function getDescription() {
            return $this->description;
        }
        
        /**
         * @method Question getQuestionById
         * @param String id The requested question id
         * @return The question with the given id
        **/
        public function getQuestionById($id) {
            foreach($this->questionList as $question) {
                if($question->getId() == $id) {
                    return $question;
                }
                try{
                    return $question->getNestedQuestionById($id);
                }
                catch(Exception $e){
                    //Nothing to do here
                }
            }
            throw new Exception("Invalid question id {$id} given");
        }
        
        /**
         * @method String getFileName
         * @return The name of the XML file
        **/
        public function getFileName() {
            return $this->fileName;
        }
        
        /**
         * @method void updateAnswerFile
        **/
        public function updateAnswerFile() {
            
        }
        
        /**
         * @method String getClosing
         * @return The closing message
        **/
        public function getClosing() {
            return $this->closing;
        }
    }
?>