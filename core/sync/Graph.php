<?php
    /**
     * File: Graph.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: This class implements a graph and reads it from an XML file
     * Release: March/2010
    **/
    
    class Edge {
	/**
	 * @property Node node The edge end node
	 * @property int weight The edge weight
	**/
	private $node;
	private $weight;
	
	/**
	 * @method <<constructor>> __construct
	 * @param Node node The edge node
	 * @param int weight The edge weight
	**/
	public function __construct(Node $node, $weight) {
	    $this->node = $node;
	    $this->weight = $weight;
	}
	
	/**
	 * @method Node getNode
	 * @return The edge node
	**/
	public function getNode() {
	    return $this->node;
	}
	
	/**
	 * @method int getWeight
	 * @return The edge weight
	**/
	public function getWeight() {
	    return $this->weight;
	}
	
	/**
	 * @method void setWeight
	 * @param int weight
	**/
	public function setWeight($weight) {
	    $this->weight = $weight;
	}
    }
    
    class Node {
	/**
	 * @property id The node id
	 * @property adjacentList A list of Edge objects adjacent to this
	**/
	private $id;
	private $adjacentList = array();
	
	/**
	 * @method <<constrcutor>> __construct Class constructor
	 * @param $id the node id
	**/
	public function __construct($id) {
	    $this->id = $id;
	}
	
	/**
	 * @method mixed getId
	 * @return The node id
	**/
	public function getId() {
	    return $this->id;
	}
	
	/**
	 * @method addAdjacentEdge
	 * @param Edege edge Edge object to be added as adjacent
	**/
	public function addAdjacentEdge(Edge $edge) {
	    $this->adjacentList[] = $edge;
	}
	
	/**
	 * @method int getAdjacentCount
	 * @return The number of adjacent nodes
	**/
	public function getAdjacentCount() {
	    return count($this->adjacentList);
	}
	
	/**
	 * @method Edge getAdjacentById
	 * @param String id The id of the requested node
	**/
	public function getAdjacentById($id) {
	    foreach($this->adjacentList as $adjEdge) {
		if($id == $adjEdge->getNode()->getId())
		    return $adjEdge;
	    }
	    return NULL;
	}
	
	/**
	 * @method Edge getAjdacentEdge
	 * @param int i The ith edge
	 * @return The requested edge
	**/
	public function getAdjacentEdge($i) {
	    if($i > count($this->adjacentList))
		return NULL;
	    return $this->adjacentList[$i];
	}
    }
    
    class Graph {
	/**
	 * @property Node nodeList a list of graph nodes
	**/
	protected $nodeList = array();
	
	/**
	 * @method void addNode
	 * @param Node node The node to be added
	**/
	public function addNode(Node $node) {
	    $this->nodeList[] = $node;
	}
	
	/**
	 * @method int getNodeCount
	 * @return The number of graph nodes
	**/
	public function getNodeCount() {
	    return count($this->nodeList);
	}
	
	/**
	 * @method Node getNode
	 * @param int i The number of the requested node
	 * @return The node with the given id or NULL
	**/
	public function getNode($i) {
	    if($i > $this->getNodeCount())
		return NULL;
	    return $this->nodeList[$i];
	}
	
	/**
	 * @method Node getNodeById
	 * @param id The node id
	 * @return The node with the given id or NULL
	**/
	public function getNodeById($id) {
	    foreach($this->nodeList as $node) {
		if($node->getId() == $id)
		    return $node;
	    }
	    return NULL;
	}
    }
?>