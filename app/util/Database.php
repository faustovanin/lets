<?php
    /**
    * File: Database.php
    * Author: Fausto Vanin <fnsvanin@yahoo.com.br>
    * Description: Abastract class for database operations
    **/
    abstract class Database {
        protected $host;
        protected $port;
        protected $name;
        protected $user;
        protected $password;
        protected $connected;
        
        public function __construct($name, $host, $port, $user, $pass){
            $this->host = $host;
            $this->port = $port;
            $this->name = $name;
            $this->user = $user;
            $this->password = $pass;
        }
        public function getHost() {
            return $this->host;
        }
        public function getPort() {
            return $this->port;
        }
        public function getName() {
            return $this->name;
        }
        public function getUser() {
            return $this->user;
        }
        abstract function connect();
        abstract function disconnect();
        abstract function execute($sql);
        abstract function executeQuery($sql);
        abstract function getLastId();
    }
?>