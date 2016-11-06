<?php
    /**
     * File: MySQLDB.php
     * Author: Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Implementation of the asbstract class Database for MySQL
     **/
    require_once("Database.php");
    require_once("ResultSet.php");
    class MySQLDB extends Database {
        protected $connection;
        protected $connected;
        protected $lastId;
        public function __construct($name, $host="localhost", $port="3306", $user="root", $pass=""){
            parent::__construct($name, $host, $port, $user, $pass);
            $this->connected = false;
        }
        public function connect() {
            //if($this->connected) return true;
            $this->connection = mysql_connect($this->host.":".$this->port, $this->user, $this->password);
            $this->connected = mysql_select_db($this->name, $this->connection);
            return $this->connected;
        }
        public function disconnect() {
            if($this->connected)
                @mysql_close($this->connection);
        }
        public function execute($sql) {
            $result = mysql_query($sql);
            $this->lastId = mysql_insert_id($this->connection);
            return mysql_affected_rows($this->connection);
        }
        public function executeQuery($sql) {
            $result = mysql_query($sql);
            for($i=0; $i<mysql_num_rows($result); $i++){
                $line = mysql_fetch_assoc($result);
                $matrix[] = $line;
            }
            return new ResultSet($matrix);
        }
        public function getLastId() {
            return $this->lastId;
        }
    }
?>