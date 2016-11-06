<?php
    /**
     * File: ControllerFactory.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This factory chooses the controller from the given parameter
     * Release: July/2009
     *
    **/
    require_once("sync/Controller.php");
    require_once("async/ControllerAjax.php");
    require_once("sync/Request.php");
    
    class ControllerFactory {
        /**
         * @method Controller getController
         * @param string id The id of the controller to obtain
         * @param string configFile The configuration file name
         * @return A Controller object accordding to the given parameter
        **/
        public static function getController($id, $configFile){
            switch($id){
                case "sync":
                    return new Controller($configFile);
                case "async":
                    return new ControllerAjax($configFile);
            }
        }
    }
?>