<?php
    /**
     * File: ClienteDAO.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Arquivo de dados da classe Cliente
     * Release: Dezembro/2009
    **/
    
    require_once("app/util/DAO.php");
    require_once("Cliente.php");
    
    class ClienteDAO extends DAO {
	/**
	 * @property int id Id do cliente
	 * @property Cliente cliente Cliente wrapped
	**/
	private $id;
	private $cliente;
	
	/**
	 * @method <<constructor>> __construct
	 * @param Database db
	**/
	public function __construct(Database $db) {
	    parent::__construct($db);
	}
	public function insert() {
	    if($this->id) return;
	    $this->insertSQL = "INSERT INTO Cliente VALUES (0, 
		'{$this->cliente->getNome()}',
		'{$this->cliente->getEndereco()}', 
		'{$this->cliente->getTelefone()}', 
		'{$this->cliente->getCelular()}',
		'{$this->cliente->getEmail()}',
		'{$this->cliente->getMedida()->getBusto()}',
		'{$this->cliente->getMedida()->getComprimentoBusto()}',
		'{$this->cliente->getMedida()->getOmbro()}',
		'{$this->cliente->getMedida()->getCintura()}',
		'{$this->cliente->getMedida()->getQuadril()}',
		'{$this->cliente->getMedida()->getComprimento()}',
		'{$this->cliente->getMedida()->getAlturaGancho()}',
		'{$this->cliente->getMedida()->getBoca()}',
		'{$this->cliente->getMedida()->getManga()}',
		'{$this->cliente->getMedida()->getBocaManga()}'
		)";
	    parent::insert();
	    $this->setCliente($this->cliente);
	}
	public function update() {
	    $this->updateSQL = "UPDATE Cliente SET
		nome_cliente = '{$this->cliente->getNome()}',
		endereco_cliente = '{$this->cliente->getEndereco()}',
		telefone_cliente = '{$this->cliente->getTelefone()}',
		celular_cliente = '{$this->cliente->getCelular()}',
		email_cliente = '{$this->cliente->getEmail()}',
		busto_cliente = '{$this->cliente->getMedida()->getBusto()}',
		comprimento_busto_cliente = '{$this->cliente->getMedida()->getComprimentoBusto()}',
		ombro_cliente = '{$this->cliente->getMedida()->getOmbro()}',
		cintura_cliente = '{$this->cliente->getMedida()->getCintura()}',
		quadril_cliente = '{$this->cliente->getMedida()->getQuadril()}',
		comprimento_cliente = '{$this->cliente->getMedida()->getComprimento()}',
		altura_gancho_cliente = '{$this->cliente->getMedida()->getAlturaGancho()}',
		boca_cliente = '{$this->cliente->getMedida()->getBoca()}',
		manga_cliente = '{$this->cliente->getMedida()->getManga()}',
		boca_manga_cliente = '{$this->cliente->getMedida()->getBocaManga()}'
		WHERE cod_cliente = {$this->id}";
	    parent::update();
	}
	public function delete() {
	    $this->deleteSQL = "DELETE FROM Cliente WHERE cod_cliente = {$this->id}";
	    parent::delete();
	}
	/**
	 * @method void setId Define o id e carrega os demais dados do banco de dados
	 * @param int id O id para buscar no banco de dados
	**/
	public function setId($id) {
	    $this->db->connect();
	    $rs = $this->db->executeQuery("SELECT * FROM Cliente WHERE cod_cliente = {$id}");
	    $this->db->disconnect();
	    if( !$rs->getRows() )
		throw new Exception("Id invalido");
	    $this->id = $id;
	    $this->cliente = new Cliente();
	    $this->cliente->setNome( $rs->getField("nome_cliente") );
	    $this->cliente->setEndereco( $rs->getField("endereco_cliente") );
	    $this->cliente->setTelefone( $rs->getField("telefone_cliente") );
	    $this->cliente->setCelular( $rs->getField("celular_cliente") );
	    $this->cliente->setEmail( $rs->getField("email_cliente") );
	    
	    $medida = new Medida();
	    $medida->setAlturaGancho( $rs->getField("altura_gancho_cliente") );
	    $medida->setBoca( $rs->getField("boca_cliente") );
	    $medida->setBocaManga( $rs->getField("boca_manga_cliente") );
	    $medida->setBusto( $rs->getField("busto_cliente") );
	    $medida->setCintura( $rs->getField("cintura_cliente") );
	    $medida->setComprimento( $rs->getField("comprimento_cliente") );
	    $medida->setComprimentoBusto( $rs->getField("comprimento_busto_cliente") );
	    $medida->setManga( $rs->getField("manga_cliente") );
	    $medida->setOmbro( $rs->getField("ombro_cliente") );
	    $medida->setQuadril( $rs->getField("quadril_cliente") );
	    
	    $this->cliente->setMedida($medida);
	}
	
	/**
	 * @method void setCliente Define o objeto cliente e tenta carregar o id do banco de dados
	 * @param Cliente cliente
	**/
	public function setCliente(Cliente $cliente) {
	    $this->cliente = $cliente;
	    $this->db->connect();
	    $rs = $this->db->executeQuery("SELECT cod_cliente FROM Cliente WHERE 
		nome_cliente = '{$cliente->getNome()}' AND
		endereco_cliente = '{$cliente->getEndereco()}' AND
		telefone_cliente = '{$cliente->getTelefone()}' AND
		celular_cliente = '{$cliente->getCelular()}' AND
		email_cliente = '{$cliente->getEmail()}'
	    ");
	    $this->db->disconnect();
	    if( !$rs->getRows() ) {
		return;
	    }
	    $this->id = $rs->getField("cod_cliente");
	}
	
	/**
	 * @method ClienteDAO[] getPeloNome
	 * @param String parteNome Parte do nome
	 * @param Database db Banco de dados
	 * @return Lista de clientes que possuem parte do nome dado
	**/
	public static function getPeloNome(Database $db, $parteNome) {
	    $db->connect();
	    $rs = $db->executeQuery("SELECT cod_cliente FROM Cliente 
		WHERE nome_cliente LIKE '%{$parteNome}%' 
		ORDER BY nome_cliente
	    ");
	    $db->disconnect();
	    $peloNome = array();
	    while($rs->next()) {
		$clienteDAO = new ClienteDAO($db);
		$clienteDAO->setId( $rs->getField("cod_cliente") );
		$peloNome[] = $clienteDAO;
		$rs->moveNext();
	    }
	    return $peloNome;
	}
	
	/**
	 * @method int getId
	 * @return O id do cliente
	**/
	public function getId() {
	    return $this->id;
	}
	
	/**
	 * @method Cliente getCliente
	 * @return Objeto cliente
	**/
	public function getCliente() {
	    return $this->cliente;
	}
	
	/**
	 * @method ClienteDAO[] getTodos
	 * @param Database db Banco de dados
	 * @return Todos os clientes em ordem alfabética
	**/
	public static function getTodos(Database $db) {
	    $db->connect();
	    $rs = $db->executeQuery("SELECT cod_cliente FROM Cliente ORDER BY nome_cliente");
	    $db->disconnect();
	    $todos = array();
	    
	    while($rs->next()) {
		$clienteDAO = new ClienteDAO($db);
		$clienteDAO->setId($rs->getField("cod_cliente"));
		$todos[] = $clienteDAO;
		$rs->moveNext();
	    }
	    
	    return $todos;
	}
    }
?>