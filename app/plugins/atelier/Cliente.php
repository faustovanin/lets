<?php
    /**
     * File: Cliente.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Cliente da loja
     * Release: Dezembro/2009
    **/
    
    require_once("Medida.php");
    require_once("Venda.php");
    
    class Cliente {
	/**
	 * @property String nome
	 * @property String endereco
	 * @property String telefone
	 * @property String email
	 * @property Medida medida
	 * @property Venda[] listaVenda Vendas do Cliente
	**/
	protected $nome;
	protected $endereco;
	protected $telefone;
	protected $celular;
	protected $email;
	protected $medida;
	protected $listaVenda;
	
	/**
	 * @method <<constructor>> __construct Class constructor
	 **/
	public function __construct(){
	    
	}
	
	/**
	 * @method void setNome
	 * @param String nome Nome do cliente
	**/
	public function setNome($nome) {
	    if($nome=="")
		throw new Exception("Nome inv&aacute;lido");
	    $this->nome = $nome;
	}
	
	/**
	 * @method void setEndereco
	 * @param String endereco Endereço do cliente
	**/
	public function setEndereco($endereco) {
	    if($endereco == "")
		throw new Exception("Endere&ccedil;o inv&aacute;lido");
	    $this->endereco = $endereco;
	}
	
	/**
	 * @method void setTelefone
	 * @param String telefone Numero do telefone
	**/
	public function setTelefone($telefone) {
	    if($telefone == "")
		throw new Exception("Telefone inv&aacute;lido");
	    $this->telefone = $telefone;
	}
	
	/**
	 * @method void setCelular
	 * @param String celular Telefone celular do cliente
	 **/
	public function setCelular($celular) {
	    $this->celular = $celular;
	}
	
	/**
	 * @method void setEmail
	 * @param String email
	**/
	public function setEmail($email) {
	    $this->email = $email;
	}
	
	/**
	 * @method void setMedida
	 * @param Medida medida Medidas do cliente
	**/
	public function setMedida(Medida $medida) {
	    $this->medida = $medida;
	}
	
	/**
	 * @method void addVenda Adiciona uma venda ao cliente
	 * @param Venda venda Venda a ser adicionada
	**/
	public function addVenda(Venda $venda) {
	    $this->listaVenda[] = $venda;
	}
	
	/**
	 * @method int getVendaCount
	 * @return A quantidade de vendas do cliente
	**/
	public function getVendaCount() {
	    return count($this->listaVenda);
	}
	
	/**
	 * @method Venda getVenda
	 * @param int i Venda desejada
	 * @return O objeto venda pedido
	**/
	public function getVenda($i) {
	    if($i >= count($this->listaVenda))
		throw new Exception("Venda solicitada n&atilde;o existe.");
	    return $this->listaVenda[$i];
	}
	
	/**
	 * @method String getNome
	 * @return O nome do cliente
	**/
	public function getNome() {
	    return $this->nome;
	}
	
	/**
	 * @method String getEndereco
	 * @return O endereco do cliente
	**/
	public function getEndereco() {
	    return $this->endereco;
	}
	
	/**
	 * @method String getTelefone
	 * @return O telefone do cliente
	**/
	public function getTelefone() {
	    return $this->telefone;
	}
	
	/**
	 * @method String getCelular
	 * @return O numero do celular do cliente
	**/
	public function getCelular() {
	    return $this->celular;
	}
	
	/**
	 * @method String getEmail
	 * @return O email do cliente
	 **/
	public function getEmail() {
	    return $this->email;
	}
	
	/**
	 * @method Medida getMedida
	 * @return Medida do cliente
	**/
	public function getMedida() {
	    return $this->medida;
	}
    }
?>