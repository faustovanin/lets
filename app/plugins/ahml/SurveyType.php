<?php
    /**
     * File: SuveyType.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class represents the possible survey type suported
     * Release: October/2009
     **/
    
    class SurveyType {
        const QUESTIONNAIRE = 0;
        const TEST = 1;
        const INTERVIEW = 2;
        
        /**
         * @method SurveyType eval
         * @param String text A text containing the type value to be evaluated
         * @return A valid SurveyType for the given text
        **/
        public static function evaluate($text){
            $validQuestionnarie = array("questionnaire", "QUESTIONNAIRE", "quest", "QUEST");
            $validTest = array("test", "TEST");
            $validInterview = array("interview", "Interview", "INTERVIEW", "int", "INT", "inter", "Inter");
            
            foreach($validQuestionnarie as $valid)
                if($text == $valid)
                    return self::QUESTIONNAIRE;
            foreach($validTest as $valid)
                if($text == $valid)
                    return self::TEST;
            foreach($validInterview as $valid)
                if($text == $valid)
                    return self::INTERVIEW;
            throw new Exception("Invalid survey type given. Expected questionnaire, interview or test");
        }
    }
?>