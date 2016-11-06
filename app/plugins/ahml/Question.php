<?php
    /**
     * File: Question.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class represents a single question in the structure.
     * Release: September/2009
     **/
    
    require_once("QuestionType.php");
    require_once("InputFactory.php");
    require_once("XmlUtils.php");
    
    class Question {
        /**
         * @property mixed id The question id
         * @property text title The question title
         * @property text description The question description
         * @property DOMDocument document The object representing the XML document
         * @property QuestionType type The question type
         * @property Input inputList The input object collection
         * @property QuestionChain nestedList A chained Question object wrapped list
         * @property String defaultRepositoryURL The default xml url
         * @property array answerList The answer source
        **/
        protected $id;
        protected $title;
        protected $description;
        protected $document;
        protected $type;
        protected $inputList =  array();
        protected $nestedList = array();
        protected static $defaultRepositoryURL;
        protected $answerList = array();
        
        /**
         * @method <<constructor>> __construct
         * @param QuestionType type The question type
         **/
        public function __construct($id, $xmlFile, $answerList){
            $this->id = $id;
            $this->document = new DOMDocument();
            $xmlFile = self::$defaultRepositoryURL . $xmlFile;
            $this->answerList = $answerList;
            if(!is_file($xmlFile))
                throw new Exception("Invalid xml file given: {$xmlFile}");
            $this->document->load($xmlFile);
            //================Features Reading=========================//
            $this->title = XmlUtils::getAttribute($this->document, "question", "title");
            $this->description = XmlUtils::getNodeInnerValue($this->document, "description");
            
            $inputNodeList = $this->document->getElementsByTagName("input");
            for($i=0; $i<$inputNodeList->length; $i++){
                $input = InputFactory::getInput($inputNodeList->item($i), $i);
                $input->setAnswer( new Answer($this->getType(), $this->answerList[$input->getId()]) );
                $this->inputList[] = $input;
            }
            
            $this->resolveDependecies();
        }
        
        /**
         * @method mixed getId
         * @return The question id
        **/
        public function getId() {
            return $this->id;
        }
        
        /**
         * @method string getTitle
         * @return The question title
        **/
        public function getTitle() {
            return $this->title;
        }
        
        /**
         * @method string getDescription
         * @return The question description
        **/
        public function getDescription() {
            return $this->description;
        }
        
        /**
         * @method QuestionType getType
         * @return The QuestionType object related to this question
        **/
        public function getType() {
            return $this->type;
        }
        
        /**
         * @method int getInputCount
         * @return The number of in put in the question
        **/
        public function getInputCount() {
            return count($this->inputList);
        }
        
        /**
         * @method void resolveDependencies This method is responsible to resolve
         *  the "implies" restrictions between inputs
        **/
        private function resolveDependecies(){
            foreach($this->inputList as $input){
                if($input->getImplies()){
                    /**
                     * At this point the existing implied object of the actual
                     *  input will be incomplete. The following procedures
                     *  will get a complete Input object and set it back
                     *  to the actual input.
                    **/
                    $implied = $this->getInputById($input->getImplies()->getId());
                    $input->setImplies($implied);
                }
            }
        }
        
        /**
         * @method Input getInputById
         * @method mixed id the requested id
         * @return An input of the question given its id
        **/
        public function getInputById($id){
            foreach($this->inputList as $input) {
                if($input->getId() == $id)
                    return $input;
            }
            throw new Exception("Requested input {$id} does not exist.");
        }
        
        /**
         * @method void addNestedQuestion Adds a question to the nested list
        **/
        public function addNestedQuestion($question, $inputId, $answer){
            $this->nestedList[] = new QuestionChain($question, $inputId, $answer);
        }
        
        /**
         * @method int getNestedQuestionCount
         * @return The number of nested question objects in the list
        **/
        public function getNestedQuestionCount() {
            return count($this->nestedList);
        }
        
        /**
         * @method QuestionChain getNestedQuestion
         * @param int n The nth question to return
         * @return The nth question or throws an Exception
        **/
        public function getNestedQuestion($n) {
            if($n > $this->getNestedQuestionCount())
                throw new Exception("Index out of bounds");
            return $this->nestedList[$n];
        }
        
        /**
         * @method Question getNestedQuestionById
         * @param id id The requested id
         * @return A Question object with the given id or throws an Excpetion
         **/
        public function getNestedQuestionById($id) {
            foreach($this->nestedList as $nestedQuestion) {
                if($nestedQuestion->getQuestion()->getId() == $id) {
                    return $nestedQuestion->getQuestion();
                }
            }
            throw new Exception("The requested question does not exist.");
        }
        
        /**
         * @method bool answered Test if the inputs are answered
         * @return true if any input is answered or false otherwise
        **/
        public function answered(){
            /*foreach($this->inputList as $input) {
                if($input->getAnswer()) {
                    return true;
                }
            }
            return false;*/
            return count($this->answerList);
        }
        
        /**
         * @method String getView
         * @return An HTML code of the question
        **/
        public function getView() {
            $html .= "<h1 class='question_title'>{$this->title}</h1>";
            $html .= "<h2 class='question_description'>{$this->description}</h2>";
            $html .= "<form action='answer_question' method='post'>";
            foreach($this->inputList as $input) {
                $html .= $input->getView();
            }
            $html .= "<input type='submit' value='OK'/>";
            $html .= "</form>";
            return $html;
        }
        
        
        /**
         * @method void setDefaultRepositoryURL Sets the default xml files url
         * @param String url The URL to be set
        **/
        public static function setDefaultRepositoryURL($url){
            self::$defaultRepositoryURL = $url;
        }
        
        /**
         * @method String getDefaultRepositoryURL
         * @return The question default repository for xml files
        **/
        public function getDefaultRepositoryURL() {
            return self::$defaultRepositoryURL;
        }
        
        /**
         * @method Input getInput
         * @param int n The input position
         * @return The corresponding input object
        **/
        public function getInput($n){
            if($n > count($this->inputList)) {
                throw new Exception("Index out of bounds.");
            }
            return $this->inputList[$n];
        }
        
        /**
         * @method Question getNext
         * @return a Question object if the pair input/answer match or NULL otherwise
        **/
        public function getNext() {
            foreach($this->nestedList as $nested) {
                $input = $this->getInputById($nested->getInputId());
                if(!$nested->getQuestion()->answered() && $input->getAnswer()->getContent() == $nested->getAnswer()) {
                    return $nested->getQuestion();
                }
            }
            return NULL;
        }
        
        /**
         * @method bool isNested
         * @param id id The question Id
         * @return true if a question with the given id is nested or false otherwise
         **/
        public function isNested($id) {
            foreach($this->nestedList as $nested) {
                if($nested->getQuestion()->getId() == $id) {
                    return true;
                }
            }
            return false;
        }
    }
    
    /**
     * Class QuestionNotAnsweredException
     * Description: This class represents an exception in class response.
    **/
    class QuestionNotAnsweredException extends Exception {
        /**
         * @property Question question The question not answered
        **/
        private $question;
        
        /**
         * @method <<constructor>> __construct
         * @param String message The error message
         * @param Question question The question without answer
        **/
        public function __construct($message, $question){
            parent::__construct($message);
            $this->question = $question;
        }
        
        /**
         * @method Question getQuestion
         * @return The question without answer
        **/
        public function getQuestion() {
            return $this->question;
        }
        
    }
?>