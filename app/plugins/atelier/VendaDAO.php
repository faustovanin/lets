<?php
    /**
     * File: VendaDAO.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Classe de dados de vendas
     * Release: Dezembro/2009
    **/
    
    require_once("app/util/DAO.php");
    require_once("Venda.php");
    require_once("ProdutoVendaDAO.php");
    
    class VendaDAO extends DAO {
	/**
	 * @property int id Id da venda
	 * @property Venda venda Objeto Venda
	 * @property ClienteDAO clienteDAO Objeto de dados do cliente
	**/
	private $id;
	private $venda;
	private $clienteDAO;
	
	/**
	 * @method <<constructor>> __construct Construtor da classe
	 * @param Database db Banco de dados
	**/
	public function __construct(Database $db, ClienteDAO $clienteDAO=NULL) {
	    parent::__construct($db);
	    $this->clienteDAO = $clienteDAO;
	}
	
	/**
	 * @method void setId Define o id da venda e carrega do banco de dados
	 * @param int id Id da venda
	**/
	public function setId($id) {
	    $this->db->connect();
	    $rs = $this->db->executeQuery("SELECT * FROM Venda WHERE cod_venda = {$id}");
	    if( !$rs->getRows() )
		throw new Exception("Id inv&aacute;lido");
	    $this->db->disconnect();
	    $this->id = $id;
	    $cliente = new ClienteDAO($this->db);
	    $cliente->setId( $rs->getField("cod_cliente") );
	    $this->clienteDAO = $cliente;
	    
	    $data = new Date( $rs->getField("data_venda"), "%y-%m-%d");
	    $dataEntrega = new Date( $rs->getField("data_entrega_venda"), "%y-%m-%d");
	    
	    $this->venda = new Venda($data, $dataEntrega);
	    
	    $listaProdutoVenda = ProdutoVendaDAO::getTodosPorVenda($this->db, $this);
	    foreach($listaProdutoVenda as $produtoVendaDAO) {
		$this->venda->addProduto($produtoVendaDAO->getProdutoVenda());
	    }
	}
	
	/**
	 * @method void setVenda Define a venda e tenta carregar o id do banco de dados
	 * @param Venda venda Objeto venda
	**/
	public function setVenda(Venda $venda) {
	    $clienteDAO = new ClienteDAO($this->db);
	    $this->venda = $venda;
	    $this->db->connect();
	    $rs = $this->db->executeQuery("SELECT cod_venda FROM Venda WHERE
		cod_cliente = {$this->clienteDAO->getId()} AND
		data_venda = {$this->venda->getData()->getFormat('%y/%m/%d')}
	    ");
	    $this->db->disconnect();
	    if($rs->getRows())
		$this->id = $rs->getField("cod_venda");
	}
	
	/**
	 * @method void insert
	**/
	public function insert() {
	    if($this->id)
		return;
	    $this->insertSQL = "INSERT INTO Venda VALUES(
		0,
		{$this->clienteDAO->getId()},
		'{$this->venda->getData()->getFormat('%y-%m-%d')}',
		'{$this->venda->getDataEntrega()->getFormat('%y-%m-%d')}'
	    )";
	    parent::insert();
	    $this->id = $this->db->getLastId();
	}
	
	/**
	 * @method void update
	**/
	public function update() {
	    $this->updateSQL = "UPDATE Venda SET
		cod_cliente = {$this->clienteDAO->getId()},
		data_venda = '{$this->venda->getData()->getFormat('%y/%m/%d')}',
		data_entrega_venda = '{$this->venda->getDataEntrega()->getFormat('%y/%m/%d')}'
		WHERE cod_venda = {$this->id}
	    ";
	    parent::update();
	}
	
	public function delete() {
	    $this->deleteSQL = "DELETE FROM Venda WHERE cod_venda = {$this->id}";
	    parent::delete();
	}
	/**
	 * @method VendaDAO[] getVendaEntrega
	 * @param Date entrega Data de entrega para consulta
	 * @param Database Banco de dados para fazer a consulta
	 * @return Uma coleção de vendas por data
	**/
	public static function getVendaEntrega(Date $entrega, Database $db) {
	    $vendaEntrega = array();
	    $rs = $db->executeQuery("SELECT cod_venda FROM Venda
		WHERE data_entrega_venda = #".$entrega->getFormat("%y/%m/%d")."#
	    ");
	    $vendaEntrega = array();
	    while( $rs->next() ) {
		$venda = new VendaDAO($db);
		$venda->setId( $rs->getField("cod_entrega_venda") );
		$vendaEntrega[] = $venda;
		$rs->moveNext();
	    }
	}
	
	/**
	 * @method VendaDAO[] getVendaEntregaPeriodo 
	 * @param Date inicio Data de inicio do período
	 * @param Date fim Data final do periodo
	 * @param Database Banco de dados
	 * @return As vendas de um dado período
	**/
	public static function getVendaEntregaPeriodo(Date $inicio, Date $fim, Database $db) {
	    $db->connect();
	    $rs = $db->executeQuery("SELECT cod_venda FROM Venda WHERE
		data_entrega_venda BETWEEN '{$inicio->getFormat('%y/%m/%d')}' AND
		'{$fim->getFormat('%y/%m/%d')}' 
		ORDER BY data_entrega_venda
	    ");
	    $db->disconnect();
	    $vendaEntregaPeriodo = array();
	    while($rs->next()) {
		$venda = new VendaDAO($db);
		$venda->setId( $rs->getField("cod_venda") );
		$vendaEntregaPeriodo[] = $venda;
		$rs->moveNext();
	    }
	    return $vendaEntregaPeriodo;
	}
	
	/**
	 * @method double getMovimentoPeriodo
	 * @param Date inicio Data de inicio do período
	 * @param Date fim Data final do periodo
	 * @param Database Banco de dados
	 * @return O valor de movimento no período
	**/
	public static function getMovimentoPeriodo(Date $inicio, Date $fim, Database $db) {
	    $vendaEntregaPeriodo = self::getVendaEntregaPeriodo($inicio, $fim, $db);
	    $movimento = 0.0;
	    foreach($vendaEntregaPeriodo as $vendaDAO) {
		$movimento += $vendaDAO->getVenda()->getValor();
	    }
	    return $movimento;
	}
	
	/**
	 * @method VendaDAO getTodas
	 * @param Database banco de dados
	 * @return Todas as vendas por ordem invertida de data
	**/
	public static function getTodas(Database $db) {
	    $db->connect();
	    $rs = $db->executeQuery("SELECT Venda.cod_venda, cod_cliente FROM
		Venda INNER JOIN ProdutoVenda ON ProdutoVenda.cod_venda = Venda.cod_venda
		WHERE entregue_produto_venda = 0
		GROUP BY Venda.cod_venda
		ORDER BY data_entrega_venda
	    ");
	    $db->disconnect();
	    $todas = array();
	    while($rs->next()){
		$clienteDAO = new ClienteDAO($db);
		$clienteDAO->setId( $rs->getField("cod_cliente") );
		$vendaDAO = new VendaDAO($db, $clienteDAO);
		$vendaDAO->setId( $rs->getField("cod_venda") );
		
		$todas[] = $vendaDAO;
		$rs->moveNext();
	    }
	    return $todas;
	}
	
	/**
	 * @method VendaDAO getPorCliente
	 * @param Database db Banco de dados
	 * @param ClienteDAO clienteDAO Objeto cliente
	**/
	public static function getPorCliente(Database $db, ClienteDAO $clienteDAO) {
	    $db->connect();
	    $rs = $db->executeQuery("SELECT cod_venda FROM Venda WHERE cod_cliente = {$clienteDAO->getId()} ORDER BY data_entrega_venda");
	    $db->disconnect();
	    $porCliente = array();
	    
	    while($rs->next()) {
		$vendaDAO = new VendaDAO($db, $clienteDAO);
		$vendaDAO->setId($rs->getField("cod_venda"));
		$porCliente[] = $vendaDAO;
		$rs->moveNext();
	    }
	    return $porCliente;
	}
	
	/**
	 * @method int getId
	 * @return O id da venda
	**/
	public function getId() {
	    return $this->id;
	}
	
	/**
	 * @method Venda getVenda
	 * @return O objeto venda
	**/
	public function getVenda() {
	    return $this->venda;
	}
	
	/**
	 * @method ClienteDAO getClienteDAO
	 * @return O objeto cliente associado
	**/
	public function getClienteDAO() {
	    return $this->clienteDAO;
	}
    }
?>