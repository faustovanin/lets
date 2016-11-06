<?php
    /**
     * File: Session.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This class manages the session
     * Release: July/2009
     *
    **/
    
    class Session {
        /**
         * @property string id The index for each observer's data
        **/
        private $id;
        
        /**
         * @method <<constructor>> __construct
         * @param string id
        **/
        public function __construct($id){
            $this->id = $id;
        }
        
        /**
         * @method void setAttribute Changes a value in the session
         * @param string attribute
         * @param string value
        **/
        public function setAttribute($attribute, $value){
            session_start();
            $_SESSION[$this->id][$attribute] = $value;
        }
        
        /**
         * @method string getAttribute
         * @param string attribute
         * @return The attribute value
        **/
        public function getAttribute($attribute){
            session_start();
            return $_SESSION[$this->id][$attribute];
        }
        
        /**
         * @method void clear Clears the element data in the session
        **/
        public function clear(){
            session_start();
            unset($_SESSION[$this->id]);
        }
        
        /**
         * @method mixed getId
         * @return The session id
        **/
        public function getId() {
            session_start();
            return session_id();
        }
        
        /**
         * @method void setId
         * @param mixed id The new id
        **/
        public function setId($id) {
            session_id($id);
        }
    }
?>