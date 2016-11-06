<?php
    /**
     * File: View.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Visualization default class
     * Release: December/2009
    **/
    
    require_once("Form.php");
    
    abstract class View {
	/**
	 * @property Form form A basic form
	**/
	protected $form;
	
	/**
	 * @method <<constructor>> __construct Metodo construtor
	**/
	public function __construct() {
	    $this->form = new Form();
	}
	
	/**
	 * @method String getHtml
	 * @param String viewCommand A parameter 
	 * @return The content in HTML format
	**/
	abstract function getHtml($viewCommand);
    }
?>