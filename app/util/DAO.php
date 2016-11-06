<?php
    /**
     * File: DAO.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Data Access Object base class
     * Release: December/2009
    **/
    
    abstract class DAO {
	/**
	 * @property Database db
	 * @property String insertSQL Statement for insertion
	 * @property String updateSQL Statement for update
	 * @property String deleteSQL Statement for removing
	 **/
	protected $db;
	protected $insertSQL;
	protected $updateSQL;
	protected $deleteSQL;
	
	/**
	 * @method <<constructor>> __construct Construtor da Classe
	 * @param Database db Banco de dados
	**/
	public function __construct(Database $db) {
	    $this->db = $db;
	}
	
	/**
	 * @method void insert Executes the insert command
	**/
	public function insert() {
	    $this->db->connect();
	    $this->db->execute($this->insertSQL);
	    $this->db->disconnect();
	}
	
	/**
	 * @method void update Executes the update command
	**/
	public function update() {
	    $this->db->connect();
	    $this->db->execute($this->updateSQL);
	    $this->db->disconnect();
	}
	
	/**
	 * @method void delete Executes the delete command
	**/
	public function delete() {
	    $this->db->connect();
	    $this->db->execute($this->deleteSQL);
	    $this->db->disconnect();
	}
	
	/**
	 * @method Database getDatabase
	 * @return The database object
	**/
	public function getDatabase() {
	    return $this->db;
	}
	
    }
?>