<?php
    /**
     * File: LoginView.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Janela de login
     * Release: Dezembro/2009
    **/
    
    require_once("app/util/Form.php");
    
    class LoginView {
	/**
	 * @method String login
	 * @param String mensagemErro Algum possivel erro a ser mostrado
	 * @return A janela de login
	**/
	public function login($mensagemErro="") {
	    $html .= "<div id='login'>";
	    if($mensagemErro != "") {
		$html .= "<h2 class='erro_login'>{$mensagemErro}</h2>";
	    }
	    $html .= "<h1>Digite a senha</h1>";
	    $form = new Form("do_login", "POST");
	    $form->addItem( new InputItem("password", "senha_usuario", "12345", "Senha") );
	    $form->addItem( new InputItem("submit", "", "Login") );
	    $html .= $form->getHtml();
	    $html .= "</div>";
	    
	    return $html;
	}
    }
?>