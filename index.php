<?php
	require_once("core/ControllerFactory.php");

	try {
		$controller = ControllerFactory::getController("sync", "config.xml");
		try{
			echo($controller->processRequest( new Request($_REQUEST) ));
		}
		catch(Exception $e){
			echo($e->getMessage());
			echo($controller->error(500));
		}
	}
	catch(InvalidURLException $e) {
		echo($e->getMessage() . ": " . $e->getURL());
	}
	
?>
