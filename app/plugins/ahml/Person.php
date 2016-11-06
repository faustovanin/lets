<?php
    /**
     * File: Person.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This is a basic Person class
     * Release: September/2009
     **/
    
    abstract class Person {
        /**
         * @property strign name the person name
        **/
        protected $name;
        
        /**
         * @method <<constructor>> __construct
         * @param string name The person name
        **/
        public function __construct($name){
            $this->name = $name;
        }
        
        /**
         * @method string getName
         * @return The person name
        **/
        public function getName(){
            return $this->name;
        }
    }
?>