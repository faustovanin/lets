<?php
    /**
     * File: Person.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Class to gather authors and visitors
     * Release: July/20009
     *
    **/
    class Person {
        /**
         * @property string name The author's name
         * @property string email The author's email
        **/
        private $name;
        private $email;
        
        /**
         * @method <<constructor>> __construct
         **/
        public function __construct($name=NULL, $email=NULL){
            $this->name = $name;
            $this->email = $email;
        }
    
        /**
         * @method void setName
         * @param string name The new name
        **/
        public function setName($name){
            $this->name = $name;
        }
        
        /**
         * @method string getName
         * @return The author's name
        **/
        public function getName(){
            return $this->name;
        }
        
        /**
         * @method void setEmail
         * @param string email The new email
        **/
        public function setEmail($email){
            $this->email = $email;
        }
        
        /**
         * @method string getEmail
         * @return The author's email
        **/
        public function getEmail(){
            return $this->email;
        }
        
    }
?>