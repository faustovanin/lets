<?php
    /**
     * File: InputType.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  The possible types of input
     * Release: September/2009
     **/
    
    class InputType {
        const RADIO = 0;
        const CHECKBOX = 1;
        const TEXT = 2;
        const UNKNOWN = 3;
        const UNIQUE = 4;
        const MULTIPLE = 5;
        
        /**
         * @method InputType eval Evaluates a given value
         * @param String value The value to be evaluated
         * @return An InputType object corresponding to the given value
        **/
        public static function evaluate($value) {
            $validRadio     = array("radio", "RADIO");
            $validCheckBox  = array("check", "CHECK", "checkbox", "CHECKBOX");
            $validText      = array("text", "TEXT");
            $validUnique    = array("unique", "UNIQUE");
            
            foreach($validRadio as $valid)
                if($value == $valid)
                    return self::RADIO;
            foreach($validCheckBox as $valid)
                if($value == $valid)
                    return self::CHECKBOX;
            foreach($validText as $valid)
                if($value == $valid)
                    return self::TEXT;
            foreach($validUnique as $valid)
                if($value == $valid)
                    return self::UNIQUE;
            throw new Exception("The given value is not valid. Expected radio, checkbox and text.");
        }
    }
?>