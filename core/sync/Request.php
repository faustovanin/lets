<?php
    /**
     * File: Request.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Wrapper for GET and POST requests
     * Release: July/2009
     *
    **/
    
    class Request {
        /**
         * @property string id The request id
         * @property string[] parameters The list of parameters values
        **/
        protected $id;
        protected $parameters = array();
        
        /**
         * @method <<constructor>> __construct
         * @param misc request
        **/
        public function __construct($request){
            $this->id = $request["form_gateway_action"];
            foreach($request as $position => $value){
                if($position != $this->id){
                    $this->parameters[$position]  = $value;
                }
            }
        }
        
        /**
         * @method string getId
         * @return The id of the request
        **/
        public function getId(){
            return $this->id;
        }
        
        /**
         * @method string getParameter
         * @param string parameter The parameter to obtain
         * @return The value of the parameter
        **/
        public function getParameter($parameter){
            return $this->parameters[$parameter];
        }
    }
?>