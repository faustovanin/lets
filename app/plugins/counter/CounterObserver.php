<?php
    /**
     * File: CounterObserver.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: A plugin to access counting
     * Release: July/2009
     *
    **/
    
    class CounterObserver implements Observer {
        /**
         * @property string fileCounter The file that will contain the counter
        **/
        private $fileCounter = "data/txt/counter.txt";
        
        /**
         * @method <<constructor>> __construct
        **/
        public function __construct(){
            
        }
        
        /**
         * @method string getPreamble
         * @return the counter preamble
        **/
        public function getPreamble(){
            return "";
        }
        
        /**
         * @method Response doAction
         * @param Message message The operation message
         * @return A Response object containing the counter
        **/
        public function doAction(Message $message){
            $request = $message->getRequest();
            $session = $message->getSession();
            $response = new Response();
            
            $counter = (int) @file_get_contents($this->fileCounter);
            if( !$session->getAttribute("counted") ){
                ++$counter;
                $oFile = @fopen($this->fileCounter, "w");
                @fwrite($oFile, $counter);
                @fclose($oFile);
                $session->setAttribute("counted", 1);
            }
            $response->append("<p class='counter'>Visits: {$counter}</p>");
            
            return $response;
        }
    }
?>