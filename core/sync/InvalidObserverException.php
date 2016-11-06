<?php
    /**
     * File: InvalidReceiverException.php
     * Author: Fausto Vanin
     * Description: This is an exception class to be thrown when an invalid
     *  Receiver object were part of a transaction
     * Release: July/2009
    **/
    
    class InvalidObserverException extends Exception {
        /**
         * @property misc observer the invalid element
        **/
        private $observer;
        
        /**
         * @method <<constructor>> __construct
         * @param misc observer The cause of the exception
         * @param string message The error message
        **/
        public function __construct($observer, $message){
            parent::__construct($message);
            $this->observer = $observer;
        }
        /**
         * @method misc getObserver Returns the cause of the error
         * @return the element that caused the exception
        **/
        public function getObserver(){
            return $this->observer;
        }
    }
?>