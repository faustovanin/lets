<?php
    /**
     * File: DefaultObserver.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This is a default implementation to manage ordinary PHP
     * 	applictations and run it under de framework
     * Release: 2010/March
    **/
    
    class DefaultObserver implements Observer {
	/**
	 * @property String dataDirectory The directory that contains the data
	**/
	private $dataDirectory;
	private $indexFileNameList = array("index.php", "index.htm", "index.html");
	
	/**
	 * @method String getDataDirectory
	 * @return The name of the directory conatining application content
	**/
	public function getDataDirectory() {
	    return $this->dataDirectory;
	}
	
	/**
	 * @method void setDataDirectory
	 * @param String dataDirectory The new directory
	**/
	public function setDataDirectory($dataDirectory) {
	    $this->dataDirectory = $dataDirectory;
	}
	
	/**
         * @method Response A method that tells to an observer to do something
         * @param Message message Must be a Message object
         * @return Response The class response
         **/
        public function doAction(Message $message) {
	    $request = $message->getRequest();
	    $response = new Response();
	    
	    $fileList = array_merge( array($request->getId()), $this->indexFileNameList);
	    $valid = false;
	    foreach($fileList as $indexFileName) {
		$file = $this->dataDirectory . "/" . $indexFileName;
		if(is_file($file)) {
		    $valid = true;
		    break;
		}
	    }
	    if(!$valid)
		throw new InvalidURLException($request->getId(), "The request address is not valid.");
	    
	    ob_start(); //Content output data interceptation start
	    
	    include($file); //Executes the PHP code
	    
	    $result = ob_get_contents(); //Retrieve the generated data
	
	    ob_end_clean(); //Content output data interceptation end
	    
	    $response->append($result); //Append to response object
	    $response->updateImage($this->dataDirectory);
	    return $response;
	}
	
	 /**
         * @method string getPreamble It could be used to print javascript and
         *   CSS data
         * @return Content to put into the head of the page
         **/
        public function getPreamble(){
	    
	}
    }
?>