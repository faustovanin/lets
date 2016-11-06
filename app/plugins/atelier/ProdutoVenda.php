<?php
    /**
     * File: ProdutoVenda.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Produtos do cliente
     * Release: Dezembro/2009
    **/
    
    require_once("Cliente.php");
    require_once("Produto.php");
    
    class ProdutoVenda {
	/**
	 * @property Produto produto
	 * @property double valor
	 * @property int quantidade
	 * @property bool entregue
	 * @property bool pago
	**/
	private $produto;
	private $valor;
	private $quantidade;
	private $entregue;
	private $pago;
	
	/**
	 * @method <<constructor>> __construct Construtor da classe
	 * @param Cliente cliente Objeto cliente
	 * @param Produto produto Objeto produto
	 * @param double valor Valor do produto
	 * @param bool entregue Se o produto ja foi entregue (default=false)
	 * @param bool pago Se o produto ja foi pago pago (default=false)
	**/
	public function __construct(Produto $produto, $valor, $quantidade, $entregue=false, $pago=false) {
	    $this->produto = $produto;
	    $this->valor   = $valor;
	    $this->quantidade = $quantidade;
	    $this->entregue = $entregue;
	    $this->pago = $pago;
	}
	
	/**
	 * @method Produto getProduto
	 * @return Objeto Produto
	**/
	public function getProduto() {
	    return $this->produto;
	}
	
	/**
	 * @property double getValor
	 * @return Valor do produto
	**/
	public function getValor() {
	    return $this->valor;
	}
	
	/**
	 * @method int getQuantidade
	 * @return A quantidade de produto encomendada
	**/
	public function getQuantidade() {
	    return $this->quantidade;
	}
	
	
	/**
	 * @method bool getPago
	 * @return Se o produto está pago ou não
	**/
	public function getPago() {
	    return $this->pago ? "1" : "0";
	}
	
	/**
	 * @method bool getEntregue
	 * @return Se o produto está entregue ou não
	**/
	public function getEntregue() {
	    return $this->entregue ? "1" : "0";
	}
	
	/**
	 * @method void setEntregue Altera o status da entrega
	 * @param bool entregue novo status
	**/
	public function setEntregue($entregue) {
	    $this->entregue = $entregue;
	}
	
	/**
	 * @method voi setPago Alterar status do pagamento
	 * @param bool pago novo status
	**/
	public function setPago($pago) {
	    $this->pago = $pago;
	}
    }
?>