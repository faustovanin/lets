<?php
    /**
     * File: RequestAjax.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: A request for an asynchronous comunication. The main
     *  difference between this request and its parent class is that this
     *  request will carry the observer id that originated this request. This
     *  is this way because an asynchronous request will return a piece of
     *  code instead of a whole HTML page
     * Release: July/2009
     *
     **/
    
    
    class RequestAjax extends Request {
        /**
         * @property string observer The observer id
        **/
        protected $observer;
        
        /**
         * @method <<constructor>> __construct
         * @param misc[] request An array containing the request data
        **/
        public function __construct($request){
            $this->id = $request["form_gateway_action"];
            $this->observer = $request["form_gateway_observer"];
            foreach($request as $position => $value){
                if($position != $this->id && $position != $this->observer){
                    $this->parameters[$position] = $value;
                }
            }
        }
        
        /**
         * @method string getObserverId
         * @return The observer id
        **/
        public function getObserverId() {
            return $this->observer;
        }
        
    }
?>