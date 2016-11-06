<?php
    /**
     * File: InvalidURLException.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This exception class is to be thrown when a given resource
     *  is unavailable at the given URL
     * Release: July/2009
     *
    **/
    
    class InvalidURLException extends Exception {
        /**
         * @property string url The invalid URL
        **/
        protected $url;
        
        /**
         * @method <<constructor>> __constructor
         * @param string url The unreachable URL
         * @param string message The exception message
        **/
        public function __construct($url, $message){
            parent::__construct($message);
            $this->url = $url;
        }
        
        /**
         * @method string getURL Returns the unreachable URL
        **/
        public function getURL() {
            return $this->url;
        }
    }
?>