<?php
    /**
     * File: Message.php
     * @author: Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This class represents a message to be exchanged between
     *  a main class and its observers
     * Release: July/2009
     *
    **/
    require_once("InvalidObserverException.php");
    require_once("Observer.php");
    require_once("Session.php");
    require_once("Cookie.php");
    class Message {
        /**
         * @property Observer observer The observer that will receive the message
         * @property Request request The Request object with the user data
         * @property Session session The observer session object
         * @property Cookie cokkie The observer cookie object
        **/
        protected $observer;
        protected $request;
        protected $session;
        protected $cookie;
        
        /**
         * @method <<constructor>> __construct
         * @param Observer observer The observer that will receive the message
         * @param Request request The request object
         * @param Session session The session object
         * @param Cookie cookie The cookie object
        **/
        public function __construct($observer, $request, $session, $cookie){
            if( !$receiver instanceof Observer );
            $this->observer = $observer;
            $this->request = $request;
            $this->session = $session;
            $this->cookie = $cookie;
        }
        /**
         * @method Observer getObserver Returns the message observer
        **/
        public function getObserver(){
            return $this->observer;
        }
        
        /**
         * @method Request getRequest
         * @return The message content
        **/
        public function getRequest(){
            return $this->request;
        }
        
        /**
         * @method Session getSession
         * @return The Session object associated to the message
        **/
        public function getSession(){
            return $this->session;
        }
        
        /**
         * @method Cookie getCookie
         * @return The cookie object associated to the message
        **/
        public function getCookie() {
            return $this->cookie;
        }
    }
?>