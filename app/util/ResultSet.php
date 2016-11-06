<?php
    /**
     * File: ResultSet.php
     * Author: Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Represents an amount of data
     *
     **/
    class ResultSet {
        protected $data = array();
        protected $rows;
        protected $cols;
        protected $position;
        
        public function __construct($data){
            if( is_array($data)) {
                $this->data = $data;
                $this->rows = count($data);
                $this->cols = count($data, true)/$this->rows;
                $this->position = 0;
            }
        }
        public function getRows(){
            return $this->rows;
        }
        public function getCols(){
            return $this->cols;
        }
        public function next() {
            return $this->position < $this->rows;
        }
        public function moveNext() {
            ++$this->position;
        }
        public function getField($name){
            return $this->data[$this->position][$name];
        }
    }
?>