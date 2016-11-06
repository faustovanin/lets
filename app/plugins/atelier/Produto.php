<?php
    /**
     * File: Produto.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Produtos disponiveis
     * Release: Dezembro/2009
    **/
    
    class Produto {
	/**
	 * @property String Nome do produto
	**/
	protected $nome;
	
	/**
	 * @method <<constructor>> __construct Construtor da classe
	 * @param String nome Nome do produto
	**/
	public function __construct($nome) {
	    if(strlen($nome) < 4)
		throw new Exception("Nome de produto inv&aacute;lido");
	    $this->nome = $nome;
	}
	
	/**
	 * @method String getNome
	 * @return Nome do produto
	**/
	public function getNome() {
	    return $this->nome;
	}
    }
?>