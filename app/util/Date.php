<?php
    /**
     * File: Date.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br> and
     * Caio Cesar Ferreira <kumppler@gmail.com>
     * Description: This module was developed as part the reaserch "Seleção
     *  Automática de Amostras em Pesquisa Digital de Opinião" of the Tuiuti
     *  University of Paraná.
     *  A class representing the date. The default format used is m/d/yyyy
     * Release: October/2009
     **/
    
    class Date {
        /**
         * @property int day The day
         * @property int month The mont
         * @property int year The year
        **/
        protected $day;
        protected $month;
        protected $year;
        
        /**
         * @method <<constructor>> __construct The class constructor
         * @param String date The date in a format m/d/yyyy
         * @param String format The date format (default "%m/%d/%y")
        **/
        public function __construct($date, $format="%m/%d/%y"){
            $separator = $format;
            $separator = str_replace("%d", "", $separator);
            $separator = str_replace("%m", "", $separator);
            $separator = str_replace("%y", "", $separator);
            //TODO: Read more wisely the data format
            
            $data = explode($separator[0], $date);
            $formatArray = explode($separator[0], $format);
            $dayCol = 0;
            $monthCol = 0;
            $yearCol = 0;
            foreach($formatArray as $idx => $value) {
                if($value == "%m") $monthCol = $idx;
                if($value == "%d") $dayCol = $idx;
                if($value == "%y") $yearCol = $idx;
            }
            
            if(count($data) != 3 || count($formatArray) != 3 || ($dayCol + $monthCol + $yearCol) != 3)
                throw new Exception("Invalid data format: {$date}");
            $this->day = $data[$dayCol];
            $this->month = $data[$monthCol];
            $this->year = $data[$yearCol];
        }
        
        /**
         * @method int getDay
         * @return The day
        **/
        public function getDay() {
            return $this->day;
        }
        
        /**
         * @method int getMonth
         * @return the date month
        **/
        public function getMont() {
            return $this->month;
        }
        
        /**
         * @method int getYear
         * @return the date year
         **/
        public function getYear() {
            return $this->year;
        }
        
        /**
         * @method String getFormat
         * @param String format The requested format
         * @return a formatted version of the date. The expected format is
         *  "Day %d of month %m of year %y"
        **/
        public function getFormat($format) {
            $dateFormat = str_ireplace("%d", $this->day, $format);
            $dateFormat = str_ireplace("%m", $this->month, $dateFormat);
            $dateFormat = str_ireplace("%y", $this->year, $dateFormat);
            
            return $dateFormat;
        }
    }
?>