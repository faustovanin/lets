<?php
    /**
     * File: ProdutoVendaDAO.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Classe de dados dos produtos da venda
     * Release: Dezembro/2009
    **/
    
    require_once("ProdutoVenda.php");
    require_once("VendaDAO.php");
    require_once("app/util/DAO.php");
    
    class ProdutoVendaDAO extends DAO {
	/**
	 * @property int id Id do Banco de dados
	 * @property VendaDAO vendaDAO Objeto de dados da venda
	 * @property ProdutoVenda produtoVenda Produto da venda
	 * @property Database db Banco de dados
	**/
	private $id;
	private $produtoVenda;
	private $vendaDAO;
	
	/**
	 * @method <<constructor>> __construct Metodo construtor
	 * @param Database db Banco de dados
	 * @param VendaDAO Objeto de banco de dados da venda
	 * @param int id Id do produto
	 * @param ProdutoVenda produtoVenda (default NULL)
	**/
	public function __construct(Database $db, VendaDAO $vendaDAO, $id, ProdutoVenda $produtoVenda=NULL) {
	    $this->db = $db;
	    $this->vendaDAO = $vendaDAO;
	    $this->id = $id;
	    if( !$produtoVenda ) {
		$produtoDAO = new ProdutoDAO($this->db);
		$produtoDAO->setId($this->id);
		$this->db->connect();
		$rs = $this->db->executeQuery("SELECT * FROM ProdutoVenda WHERE
		    cod_venda = {$this->vendaDAO->getId()} AND
		    cod_produto = {$this->id}
		");
		$this->db->disconnect();
		$this->produtoVenda = new ProdutoVenda(
		    $produtoDAO->getProduto(),
		    $rs->getField("valor_produto_venda"),
		    $rs->getField("qtd_produto_venda"),
		    $rs->getField("entregue_produto_venda"),
		    $rs->getField("pago_produto_venda")
		);
	    }
	    else
		$this->produtoVenda = $produtoVenda;
	}
	
	public function insert() {
	    if(!$this->vendaDAO)
		throw new Exception("Venda inv&aaculte;lida");
	    $this->insertSQL = "INSERT INTO ProdutoVenda VALUES(
		{$this->vendaDAO->getId()},
		{$this->id},
		{$this->produtoVenda->getValor()},
		{$this->produtoVenda->getQuantidade()},
		{$this->produtoVenda->getEntregue()},
		{$this->produtoVenda->getPago()}
	    )";
	    parent::insert();
	}
	
	public function update() {
	    if(!$this->vendaDAO)
		throw new Exception("Venda inv&aaculte;lida");
	    $this->updateSQL = "UPDATE ProdutoVenda SET
		valor_produto_venda = {$this->produtoVenda->getValor()},
		qtd_produto_venda = {$this->produtoVenda->getQuantidade()},
		entregue_produto_venda = {$this->produtoVenda->getEntregue()},
		pago_produto_venda = {$this->produtoVenda->getPago()}
		WHERE cod_venda = {$this->vendaDAO->getId()} AND
		cod_produto = {$this->id}
	    ";
	    parent::update();
	}
	
	public function delete() {
	    if(!$this->vendaDAO)
		throw new Exception("Venda inv&aaculte;lida");
	    $this->deleteSQL = "DELETE FROM ProdutoVenda WHERE
		cod_venda = {$this->vendaDAO->getId()} AND
		cod_produto = {$this->id}
	    ";
	    parent::delete();
	}
	
	/**
	 * @method void setProdutoVenda
	 * @param ProdutoVenda produtoVenda
	**/
	public function setProdutoVenda(ProdutoVenda $produtoVenda) {
	    $this->produtoVenda = $produtoVenda;
	}
	
	/**
	 * @method ProdutoVendaDAO getTodosPorVenda
	 * @param Database db Banco de dados
	 * @param VendaDAO vendaDAO objeto de dados da venda
	**/
	public static function getTodosPorVenda(Database $db, VendaDAO $vendaDAO) {
	    $db->connect();
	    $rs = $db->executeQuery("SELECT cod_produto FROM ProdutoVenda WHERE
		cod_venda = {$vendaDAO->getId()}
	    ");
	    $db->disconnect();
	    $todosPorVenda = array();
	    while( $rs->next() ) {
		$produtoVendaDAO = new ProdutoVendaDAO($db, $vendaDAO, $rs->getField("cod_produto"));
		$todosPorVenda[] = $produtoVendaDAO;
		$rs->moveNext();
	    }
	    return $todosPorVenda;
	}
	
	/**
	 * @method ProdutoVenda getProdutoVenda
	**/
	public function getProdutoVenda() {
	    return $this->produtoVenda;
	}
	
	/**
	 * @method int getId
	 * @return O id do produto da venda
	**/
	public function getId() {
	    return $this->id;
	}
    }
?>