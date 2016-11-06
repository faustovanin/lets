<?php
    /**
     * File: Cookie.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This class represents the user cookies
     * Release: November/2009
    **/
    class Time {
        /**
         * @method minutes hours
         * @return The given time in hours converted to minutes
        **/
        public static function hours($hours){
            return $hours * 60;
        }
        
        /**
         * @method minutes days
         * @return The given time in days converted to minutes
        **/
        public static function days($days) {
            return self::hours(24) * $days;
        }
        
        /**
         * @method minutes months
         * @return The given time in months converted to minutes
        **/
        public static function months($months){
            return self::days(30) * $months;
        }
        
        /**
         * @method minutes years
         * @return The given time in years converted to minutes
        **/
        public static function years($years){
            return self::months(12) * $years;
        }
    }
    class Cookie {
        /**
         * @property String id The plugin id
        **/
        private $id;
        
        /**
         * @method <<constructor>>> __construct
         * @param String id
        **/
        public function __construct($id){
            $this->id = $id;
        }
        
        /**
         * @method void setAttribute
         * @param String attribute The attribute to be set
         * @param String value The value to be set
         * @param int expiration The expiration time in minutes (default 60)
        **/
        public function setAttribute($attribute, $value, $expiration=60) {
            if(is_array($value)){
                foreach($value as $idx => $subValue) {
                    $array = "{$this->id}[{$attribute}][{$idx}]";
                    
                    if( !setcookie($array, $subValue, time()+($expiration*60)) ){
                        throw new Exception("Error writing cookie");
                    }
                }
                return;
            }
            $array = "{$this->id}[{$attribute}]";
            if( !setcookie($array, $value, time()+($expiration*60)) ) {
                throw new Exception("Error writing cookie");
            }
        }
        
        /**
         * @method String getAttribute
         * @param String attribute The attribute name to retrieve
         * @return The requested attribute value
        **/
        public function getAttribute($attribute){
            return $_COOKIE[$this->id][$attribute];
        }
        
        /**
         * @method void clear Clears the cookies
        **/
        public function clear() {
            unset($_COOKIE[$this->id]);
        }
    }
?>