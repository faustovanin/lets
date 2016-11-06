<?php
    /**
     * File: ProdutoDAO.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Classe de dados do produto
     * Release: Dezembro/2009
    **/
    
    require_once("app/util/DAO.php");
    require_once("Produto.php");
    
    class ProdutoDAO extends DAO {
	/**
	 * @property int id Id do Banco de dados
	 * @property Produto produto Produto wrapper
	**/
	private $id;
	private $produto;
	
	/**
	 * @method <<constructor>> __construct Metodo construtor
	 * @param Database db Banco de dados
	**/
	public function __construct(Database $db) {
	    parent::__construct($db);
	}
	
	public function insert() {
	    if($this->id) return;
	    $this->insertSQL = "INSERT INTO Produto VALUES(
		0,
		'{$this->produto->getNome()}'
	    )";
	    parent::insert();
	}
	
	public function update() {
	    $this->updateSQL = "UPDATE Produto SET
		nome_produto = '{$this->produto->getNome()}'
		WHERE cod_produto = {$this->id}
	    ";
	    parent::update();
	}
	
	public function delete() {
	    $this->deleteSQL = "DELETE FROM Produto
		WHERE cod_produto = {$this->id}
	    ";
	    parent::delete();
	}
	
	/**
	 * @method void setId Define o id do produto e tenta carregar do banco de dados
	 * @param int id
	**/
	public function setId($id) {
	    $this->db->connect();
	    $rs = $this->db->executeQuery("SELECT * FROM Produto WHERE cod_produto = {$id}");
	    $this->db->disconnect();
	    
	    $this->id = $id;
	    if( !$rs->getRows() )
		throw new Exception("Id passado e invalido");
		
	    $this->produto = new Produto( $rs->getField("nome_produto") );
	}
	
	/**
	 * @method void setProduto Define o produto e tenta carregar o id do Banco de Dados
	 * @param Produto produto
	**/
	public function setProduto(Produto $produto) {
	    $this->db->connect();
	    $rs = $this->db->executeQuery("SELECT * FROM Produto WHERE
		nome_produto LIKE '{$produto->getNome()}'
	    ");
	    $this->db->disconnect();
	    if( $rs->getRows() ) {
		$this->id = $rs->getField("cod_produto");
	    }
	    $this->produto = $produto;    
	}
	
	/**
	 * @method int getId
	 * @return O id do produto
	**/
	public function getId() {
	    return $this->id;
	}
	
	/**
	 * @method Produto getProduto
	 * @return Objeto produto
	**/
	public function getProduto() {
	    return $this->produto;
	}
	
	/**
	 * @method ProdutoDAO[] getTodos
	 * @param Database db
	 * @return Todos os produtos cadastrados
	**/
	public static function getTodos(Database $db) {
	    $db->connect();
	    $rs = $db->executeQuery("SELECT cod_produto FROM Produto ORDER BY nome_produto");
	    $db->disconnect();
	    
	    $todos = array();
	    while($rs->next()){
		$produtoDAO = new ProdutoDAO($db);
		$produtoDAO->setId($rs->getField("cod_produto"));
		$todos[] = $produtoDAO;
		$rs->moveNext();
	    }
	    return $todos;
	}
    }
?>