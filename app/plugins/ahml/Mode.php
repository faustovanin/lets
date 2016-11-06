<?php
    /**
     * File: 
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  This class defines the behavior of the different modes supported
     * Release: November/2009
     **/
    
    class Mode  {
        /**
         * @property Session session The given session
         * @property Request request The given request
         * @property Response response The response to send
         * @property Cookie cookie The cookie object
        **/
        protected $session;
        protected $request;
        protected $response;
        protected $cookie;
        protected $surveyList = array();
        
        /**
         * @method Response processMessage
         * @param Message request The environment message
         * @return A Response object containing the result of the request
         *  processing
        **/
        public function processMessage($message){
            $this->response = new Response();
            $this->session = $message->getSession();
            $this->request = $message->getRequest();
            $this->cookie = $message->getCookie();
        }
        
        /**
         * @method void setSurveyList Sets the survey name list to the mode
         * @param String surveyList The list to be set
        **/
        public function setSurveyList($surveyList) {
            $this->surveyList = $surveyList;
        }
    }
?>