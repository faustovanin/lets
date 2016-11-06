<?php
    /**
     * File: Observer.php
     * Author: Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This observer interface must be implemented by each
     *  observer candidate class. It only defines the method doAction
     * Release: July/2009
    **/
    require_once("Message.php");
    interface Observer {
        
        /**
         * @method Response A method that tells to an observer to do something
         * @param Message message Must be a Message object
         * @return Response The class response
         **/
        public function doAction(Message $message);
        
        /**
         * @method string getPreamble It could be used to print javascript and
         *   CSS data
         * @return Content to put into the head of the page
         **/
        public function getPreamble();
    }
?>