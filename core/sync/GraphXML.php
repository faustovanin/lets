<?php
    /**
     * File: GraphXML.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Implementation of the Graph class to read from a XML file
     * Release: March/2010
    **/
    
    /*<graph>
	<node id=doLogin />
	<node id=showMain />
     
	<edge id-from=doLogin id-to=showMain weight=0 />

    </graph>*/

    require_once("Graph.php");
    
    class GraphXML extends Graph{
	private $fileName;
	
	public function __construct($fileName) {
	    $this->fileName = $fileName;
	    
	    $document = new DOMDocument("1.0", "utf-8");
	    if(is_file($fileName))
		$document->load($fileName);
	    
	    $graph = $document->getElementsByTagName("node")->item(0);
	    if(!$graph) {
		$graph = $document->createElement("graph");
		$document->appendChild($graph);
	    }
	    
	    $nodeList = $document->getElementsByTagName("node");
	    if(!$nodeList)
		return;
	    
	    for($i=0; $i<$nodeList->length; $i++) {
		$node = new Node($nodeList->item($i)->getAttribute("id"));
		$this->addNode($node);
	    }
	    
	    $edgeList = $document->getElementsByTagName("edge");
	    
	    for($i=0; $i<$edgeList->length; $i++) {
		$edge = $edgeList->item($i);
		
		$node = $this->getNodeById($edge->getAttribute("id-from"));
		$adjNode = $this->getNodeById($edge->getAttribute("id-to"));
		
		$edgeObj = new Edge($adjNode, $edge->getAttribute("weight"));
		$node->addAdjacentEdge($edgeObj);
	    }
	}
	
	public function save() {
	    $document = new DOMDocument("1.0", "utf-8");
	    $nodeList = $document->createElement("graph");
	    
	    foreach($this->nodeList as $nodeObj) {
		$node = $document->createElement("node");
		$node->setAttribute("id", $nodeObj->getId());
		$nodeList->appendChild($node);
	    }
	    
	    foreach($this->nodeList as $nodeObj) {
		for($i=0; $i<$nodeObj->getAdjacentCount(); $i++) {
		    $edge = $document->createElement("edge");
		    $edge->setAttribute("id-from", $nodeObj->getId());
		    
		    $edgeObj = $nodeObj->getAdjacentEdge($i);
		    $edge->setAttribute("id-to", $edgeObj->getNode()->getId());
		    $edge->setAttribute("weight", $edgeObj->getWeight());
		    
		    $nodeList->appendChild($edge);
		}
	    }
	    
	    $document->appendChild($nodeList);
	    $document->save($this->fileName);
	}
    }
?>