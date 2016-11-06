<?php
    /**
     * File: Author.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: The blog post author
     * Release: July/2009
     *
    **/
    require_once("Person.php");
    class Author extends Person {
        /**
         * @property Database database The database to execute de procedures
         * @property int id The database id
        **/
        private $database;
        private $id;
        
        /**
         * @method <<constructor>> __construct
         * @param Database database The database to manage author's data
        **/
        public function __construct($database){
            $this->database = $database;
        }
    
        /**
         * @method void setId
         * @param int id The new id
         * @param bool load If true the author's data will be retrieved from
         *  the database. The default value is false
        **/
        public function setId($id, $load=false){
            $this->id = $id;
            if($load){
                $this->database->connect();
                $rs = $this->database->executeQuery("SELECT * FROM author WHERE author_id = " . $this->id);
                $this->setName( $rs->getField("author_name") );
                $this->setEmail( $rs->getField("author_email") );
                $this->database->disconnect();
            }
        }
        
        /**
         * @method int getId
         * @return The author's id
        **/
        public function getId(){
            return $this->id;
        }
        
        /**
         * @method Author autenticate
         * @param Database database
         * @param string email
         * @param string password
         * @return an Author object if the author exists or NULL otherwise
        **/
        public static function validate($database, $email, $password){
            $database->connect();
            $rs = $database->executeQuery("SELECT author_id FROM author WHERE author_email = '{$email}' AND author_password = '{$password}'");
            if(!$rs->getRows()){
                return NULL;
            }
            $author = new Author($database);
            $author->setId($rs->getField("author_id"), true);
            return $author;
        }
    }
?>