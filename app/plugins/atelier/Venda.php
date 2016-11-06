<?php
    /**
     * File: Venda.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Classe de venda
     * Release: Dezembro/2009
    **/
    
    require_once("ProdutoVenda.php");
    require_once("app/util/Date.php");
    
    class Venda {
	/**
	 * @property ProdutoVenda produtos Produtos da venda
	 * @property Date data Data da venda
	 * @property Date dataEntrega
	**/
	private $produtos = array();
	private $data;
	private $dataEntrega;
	
	/**
	 * @method <<construct>> __construct Construtor da classe
	 * @param Date data Data da venda
	 * @param Date dataEntrega Data da entrega da encomenda
	**/
	public function __construct(Date $data, Date $dataEntrega) {
	    $this->data = $data;
	    $this->dataEntrega = $dataEntrega;
	}
	
	/**
	 * @method void addProduto
	 * ProdutoVenda produto
	**/
	public function addProduto(ProdutoVenda $produto) {
	    $this->produtos[] = $produto;
	}
	
	/**
	 * @method int getProdutoCount
	 * @return A quantidade de produtos da venda
	**/
	public function getProdutoCount() {
	    return count($this->produtos);
	}
	
	/**
	 * @method ProdutoVenda getProduto
	 * @param int i O numero do produto desejado
	**/
	public function getProduto($i) {
	    if($i >= count($this->produto)) {
		throw new Exception("Posi&ccedil;&atilde;o inv&aacute;lida na lista de produtos");
	    }
	    return $this->produtos[$i];
	}
	
	/**
	 * @method Date getData
	 * @return Data da venda
	**/
	public function getData() {
	    return $this->data;
	}
	
	/**
	 * @method Date getDataEntrega
	 * @return Data da entrega da encomenda
	**/
	public function getDataEntrega() {
	    return $this->dataEntrega;
	}
	
	/**
	 * @method double getValor
	 * @return Valor da venda
	**/
	public function getValor() {
	    $valor = 0;
	    foreach($this->produtos as $produto) {
		$valor += $produto->getValor() * $produto->getQuantidade();
	    }
	    return $valor;
	}
	
	/**
	 * @method void setDataEntrega Define a data de entrega
	 * @param Date dataEntrega
	**/
	public function setDataEntrega(Date $dataEntrega) {
	    $this->dataEntrega = $dataEntrega;
	}
	
	/**
	 * @method bool isEntregue
	 * @return true se a venda foi entregue ou false caso contrário
	**/
	public function isEntregue() {
	    foreach($this->produtos as $produto) {
		if(!$produto->getEntregue())
		    return false;
	    }
	    return true;
	}
	
	/**
	 * @method bool isPago
	 * @return true se a venda foi paga ou false caso contrário
	**/
	public function isPago() {
	    foreach($this->produtos as $produto) {
		if(!$produto->getPago())
		    return false;
	    }
	    return true;
	}
    }
?>