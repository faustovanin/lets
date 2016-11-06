<?php
    /**
     * File: 
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class represents all the answers of a survey
     * Release: November/2009
     **/
    
    class AnswerList {
	/**
	 * @property fileName The XML file that will contain the answers
	 * @property array content The answers for the given user
	 * @property DOMDocument document The document to read and write data
	 * @property DOMElement userNode The user node for direct access
	 * @property String defaultRepositoryURL The directory where the file will be
	 **/
	private $fileName;
	private $content = array();
	private $document;
	private $userNode;
	private static $defaultRepositoryURL;
	
	/**
	 * @method <<constructor>>__construct
	 * @param String fileName The file to be read/write
	**/
	public function __construct($fileName, $userId) {
	    $this->fileName = $fileName;
	    if(is_file($this->fileName)) {
		throw new InvalidURLException($this->fileName, "The given output file is not valid");
	    }
	    $this->document = new DOMDocument();
	    $this->document->load(self::$defaultRepositoryURL . $this->fileName);
	    /**
	     * It expects an structure like:
	     * <answers>
	     * <user id="...">
	     * 		<question id="...">
	     * 			<input id="..." value="..." />
	     * 		</question>
	     * </user>
	     * </answers>
	    **/
	    $this->userNode = NULL;
	    $answerNode = $this->document->childNodes->item(0);
	    for($i=0; $i<$answerNode->childNodes->length; $i++) {
		$userNode = $answerNode->childNodes->item($i);
		if($userNode->nodeType == XML_ELEMENT_NODE && $userNode->getAttribute("id") == $userId) {
		    $this->userNode = $userNode;
		}
	    }
	    if(!$this->userNode) {
		$this->userNode = $this->document->createElement("user");
		$this->userNode->setAttribute("id", $userId);
		
		
		$answerNode->appendChild($this->userNode);
	    }
	    $answerList = array();
	    for($i=0; $i<$this->userNode->childNodes->length; $i++) {
		//question level
		$questionNode = $this->userNode->childNodes->item($i);
		$this->content[$questionNode->getAttribute("id")] = array();
		
		for($j=0; $j<$questionNode->childNodes->length; $j++) {
		    //input level
		    $inputNode = $questionNode->childNodes->item($j);
		    $this->content[$questionNode->getAttribute("id")]
				  [$inputNode->getAttribute("id")] = $inputNode->getAttribute("value");
		}
	    }
	}
	
	/**
	 * @method String getFileName
	 * @return The answer file name
	**/
	public function getFileName() {
	    return $this->fileName;
	}
	
	/**
	 * @method array getAnswerByQuestion
	 * @param Question question
	 * @return an array containing all the answers for the question
	**/
	public function getAnswerByQuestionId($questionId) {
	    return $this->content[$questionId];
	}
	
	/**
	 * @method setDefaultRepositoryURL
	 * @param URL The new url
	**/
	public static function setDefaultRepositoryURL($url) {
	    self::$defaultRepositoryURL = $url;
	}
	
	/**
	 * @method void setAnswer
	 * @param id questionId The question id
	 * @param id inputId The input id
	 * @param mixed value The input value
	**/
	public function setAnswer($questionId, $inputId, $value) {
	    $questionNode = $this->document->createElement("question");
	    for($i=0; $i<$this->userNode->childNodes->length; $i++){
		if($this->userNode->childNodes->item($i)->getAttribute("id") == $questionId) {
		    $questionNode = $this->userNode->childNodes->item($i);
		    break;
		}
	    }
	    $inputNode = $this->document->createElement("input");
	    
	    $questionNode->setAttribute("id", $questionId);
	    $inputNode->setAttribute("id", $inputId);
	    $inputNode->setAttribute("value", $value);
	    
	    $questionNode->appendChild($inputNode);
	    $this->userNode->appendChild($questionNode);
	}
	
	/**
	 * @method void save Saves the content to the file
	 * @param String fileName (default=$this->fileName) The file name to be saved
	**/
	public function save() {
	    $this->document->save(self::$defaultRepositoryURL . $this->fileName);
	}
    }
?>
