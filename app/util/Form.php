<?php
    /**
     * File: Form.php
     * Author: Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This class contains an implementation for HTML form
     * Release: June/2009
     *
     **/
    interface HTMLItem{
        function getHtml();
    }
    abstract class FormItem implements HTMLItem {
        protected $name;
        protected $value;
        protected $label;
        
        public function __construct($name, $value="", $label=""){
            $this->name = $name;
            $this->value = $value;
            $this->label = $label;
        }
        function getHtml(){
            return "";
        }
    }
    class InputItem extends FormItem {
        protected $type;
        protected $length;
        public function __construct($type, $name, $value="", $label="", $length=""){
            parent::__construct($name, $value, $label);
            $this->type = $type;
            $this->length = $length;
        }
        public function getHtml(){
            if($this->label)
                return "<tr><td>" . $this->label . "</td><td><input type='". $this->type . "' name='" . $this->name .
            "' value='" . $this->value . "' size='{$this->length}'/></td></tr>\n";
            return "<tr><td><input type='". $this->type . "' name='" . $this->name .
            "' value='" . $this->value . "' size='{$this->length}'/></td></tr>\n";
        }
    }
    class FormFieldSet implements HTMLItem {
        private $caption;
        private $inputList = array();
        public function __construct($caption) {
            $this->caption = $caption;
        }
        public function addInputItem(InputItem $item) {
            $this->inputList[] = $item;
        }
        public function getInputCount() {
            return count($this->inputList);
        }
        public function getInput($i) {
            return $this->inputList[$i];
        }
        public function getHtml() {
            $html .= "<tr><td>";
            $html .= "{$this->caption}<br/><fieldset title='{$this->caption}'>";
            $html .= "<table>";
            foreach($this->inputList as $input) {
                $html .= $input->getHtml();
            }
            $html .= "</table>";
            $html .= "</fieldset>";
            $html .= "</td></tr>";
            return $html;
        }
    }
    class ComboBox extends FormItem{
        /**
         * @property InputItem[] itemList
        **/
        private $itemList;
        
        /**
         * @method <<constructor>> __construct
         * @param String name
         * @param String value (default "")
         * @param String label (default "")
        **/
        public function __construct($name, $value="", $label="") {
            parent::__construct($name, $value, $label);
        }
        
        /**
         * @method void addItem Adds options to the combobox
         * @param InputItem item The item to be added
        **/
        public function addItem(InputItem $item) {
            $this->itemList[] = $item;
        }
        
        /**
         * @method String getHtml
         * @return The HTML formatted combobox
        **/
        public function getHtml() {
            $html .= "<tr><td>{$this->label}</td> <td><select name='{$this->name}'>";
            foreach($this->itemList as $item) {
                if($item->label == $this->value)
                    $html .= "<option value='{$item->value}' SELECTED>{$item->label}</option>";
                else
                    $html .= "<option value='{$item->value}'>{$item->label}</option>";
            }
            $html .= "</select></td>";
            return $html;
        }
    }
    class Form implements HTMLItem{
        protected $action;
        protected $method;
        protected $listItem = array();
        
        public function __construct($action="", $method="GET"){
            $this->action = $action;
            $this->method = $method;
        }
        public function addItem($item){
            $this->listItem[] = $item;
        }
        public function getItem($i){
            return $this->listItem[$i];
        }
        public function getItemCount(){
            return size($this->listItem);
        }
        public function getHtml(){
            $html = "<form action='" . $this->action . "' method='" . $this->method . "'>\n";
            $html .= "<table class='form'>";
            foreach($this->listItem as $item){
                $html .= $item->getHtml();
            }
            $html .= "</table>";
            $html .= "</form>";
            return $html;
        }
    }
?>