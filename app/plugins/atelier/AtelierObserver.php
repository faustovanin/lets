<?php
    /**
     * File: AtelierObserver.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Observador do atelier
     * Release: Dezembro/2009
    **/
    
    require_once("app/util/MySQLDB.php");
    
    class AtelierObserver implements Observer {
	public function getPreamble() {
	    return "";
	}
	
	public function doAction(Message $message) {
	    $request = $message->getRequest();
	    $session = $message->getSession();
	    
	    $dataBase = new MySQLDB("atelier");
	    
	    //===========View Session===============//
	    $loginView = new LoginView();
	    $produtoView = new ProdutoView();
	    $clienteView = new ClienteView();
	    $vendaView = new VendaView();
	    $mainView = new MainView();
	    //=============End======================//
	    
	    $response = new Response();
	    $online = $session->getAttribute("online");
	    switch( $request->getId() ) {
		case "form_inserir_produto":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else
			$response->append( $produtoView->inserir() );
		    break;
		case "inserir_produto":
		    $nome = $request->getParameter("nome_produto");
		    try {
			$produtoDAO = new ProdutoDAO($dataBase);
			$produtoDAO->setProduto( new Produto($nome) );
			$produtoDAO->insert();
			$response->append( $produtoView->inserir("Cadastro inserido com sucesso!") );
			$response->append( $produtoView->verLista(ProdutoDAO::getTodos($dataBase), 1) );
		    }
		    catch(Exception $e) {
			$response->append( $produtoView->inserir($e->getMessage()) );
		    }
		    break;
		case "consulta_produto":
		    $pagina = $request->getParameter("pagina") ? $request->getParameter("pagina") : 1;
		    $response->append( $produtoView->verLista(ProdutoDAO::getTodos($dataBase), $pagina) );
		    break;
		case "excluir_produto":
		    $codProduto = $request->getParameter("cod_produto");
		    $produtoDAO = new ProdutoDAO($dataBase);
		    $produtoDAO->setId($codProduto);
		    $produtoDAO->delete();
		    $response->append( $produtoView->inserir("Cadastro excluido com sucesso!") );
		    $response->append( $produtoView->verLista(ProdutoDAO::getTodos($dataBase), 1) );
		    break;
		case "form_editar_produto":
		    $codProduto = $request->getParameter("cod_produto");
		    $produtoDAO = new ProdutoDAO($dataBase);
		    $produtoDAO->setId($codProduto);
		    $response->append( $produtoView->editar($produtoDAO) );
		    $response->append( $produtoView->verLista(ProdutoDAO::getTodos($dataBase), 1) );
		    break;
		case "editar_produto":
		    $codProduto = $request->getParameter("cod_produto");
		    $nomeProduto = $request->getParameter("nome_produto");
		    $produtoDAO = new ProdutoDAO($dataBase);
		    $produtoDAO->setId($codProduto);
		    $produtoDAO->setProduto( new Produto($nomeProduto) );
		    $produtoDAO->update();
		    $response->append( $produtoView->inserir("Cadastro alterado com sucesso!") );
		    $response->append( $produtoView->verLista(ProdutoDAO::getTodos($dataBase), 1) );
		    break;
		case "form_inserir_cliente":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else
			$response->append( $clienteView->inserir() );
		    break;
		case "inserir_cliente":
		    try {
			$cliente = new Cliente();
			$cliente->setNome( $request->getParameter("nome_cliente") );
			$cliente->setEndereco( $request->getParameter("endereco_cliente") );
			$cliente->setTelefone( $request->getParameter("telefone_cliente") );
			$cliente->setCelular( $request->getParameter("celular_cliente") );
			$cliente->setEmail( $request->getParameter("email_cliente") );
			
			$medida = new Medida();
			$medida->setAlturaGancho( $request->getParameter("altura_gancho_cliente") );
			$medida->setBoca( $request->getParameter("boca_cliente") );
			$medida->setBocaManga( $request->getParameter("boca_manga_cliente") );
			$medida->setBusto( $request->getParameter("busto_cliente") );
			$medida->setCintura( $request->getParameter("cintura_cliente") );
			$medida->setComprimento( $request->getParameter("comprimento_cliente") );
			$medida->setComprimentoBusto( $request->getParameter("comprimento_busto") );
			$medida->setManga( $request->getParameter("manga_cliente") );
			$medida->setOmbro( $request->getParameter("ombro_cliente") );
			$medida->setQuadril( $request->getParameter("quadril_cliente") );
			
			$cliente->setMedida($medida);
			
			$clienteDAO = new ClienteDAO($dataBase);
			$clienteDAO->setCliente($cliente);
			$clienteDAO->insert();
			
			$response->append( $clienteView->detalhesCliente($clienteDAO) );
		    }
		    catch(Exception $e){
			$response->append( $clienteView->inserir($e->getMessage()), $request );
		    }
		    break;
		case "form_editar_cliente":
		    $codCliente = $request->getParameter("cod_cliente");
		    $clienteDAO = new ClienteDAO($dataBase);
		    $clienteDAO->setId($codCliente);
		    $response->append($clienteView->editar($clienteDAO));
		    break;
		case "editar_cliente":
		    try {
			$clienteDAO = new ClienteDAO($dataBase);
			$clienteDAO->setId( $request->getParameter("cod_cliente") );
			
			$cliente = new Cliente();
			$cliente->setNome( $request->getParameter("nome_cliente") );
			$cliente->setEndereco( $request->getParameter("endereco_cliente") );
			$cliente->setTelefone( $request->getParameter("telefone_cliente") );
			$cliente->setCelular( $request->getParameter("celular_cliente") );
			$cliente->setEmail( $request->getParameter("email_cliente") );
			
			$medida = new Medida();
			$medida->setAlturaGancho( $request->getParameter("altura_gancho_cliente") );
			$medida->setBoca( $request->getParameter("boca_cliente") );
			$medida->setBocaManga( $request->getParameter("boca_manga_cliente") );
			$medida->setBusto( $request->getParameter("busto_cliente") );
			$medida->setCintura( $request->getParameter("cintura_cliente") );
			$medida->setComprimento( $request->getParameter("comprimento_cliente") );
			$medida->setComprimentoBusto( $request->getParameter("comprimento_busto_cliente") );
			$medida->setManga( $request->getParameter("manga_cliente") );
			$medida->setOmbro( $request->getParameter("ombro_cliente") );
			$medida->setQuadril( $request->getParameter("quadril_cliente") );
			
			$cliente->setMedida($medida);
			
			$clienteDAO->setCliente($cliente);
			$clienteDAO->update();
			
			$response->append( $clienteView->detalhesCliente($clienteDAO) );
		    }
		    catch(Exception $e){
			$response->append( $clienteView->editar($clienteDAO, $e->getMessage()), $request );
		    }
		    break;
		case "consulta_cliente":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else {
			$pagina = $request->getParameter("pagina") ? $request->getParameter("pagina") : 1;
			if($request->getParameter("nome_cliente") != "")
			    $response->append(
				$clienteView->consultaPorNome(
				    ClienteDAO::getPeloNome($dataBase, $request->getParameter("nome_cliente")),
				    $pagina,
				    $request->getParameter("nome_cliente")
				)
			    );
			else
			    $response->append( $clienteView->consultaPorNome(ClienteDAO::getTodos($dataBase), $pagina, "") );
		    }
		    break;
		case "detalhes_cliente":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else {
			$codCliente = $request->getParameter("cod_cliente");
			$clienteDAO = new ClienteDAO($dataBase);
			$clienteDAO->setId($codCliente);
			$pagina = $request->getParameter("pagina") ? $request->getParameter("pagina") : 1;
			$response->append( $clienteView->detalhesCliente($clienteDAO, VendaDAO::getPorCliente($dataBase, $clienteDAO), $pagina) );
		    }
		    break;
		case "excluir_cliente":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else {
			$codCliente = $request->getParameter("cod_cliente");
			$clienteDAO = new ClienteDAO($dataBase);
			$clienteDAO->setId($codCliente);
			$clienteDAO->delete();
			$response->append( $clienteView->consultaPorNome(ClienteDAO::getTodos($dataBase), 1, "") );
		    }
		    break;
		case "form_inserir_venda":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else
			$response->append( $vendaView->inserir(ClienteDAO::getTodos($dataBase)) );
		    break;
		case "form_inserir_produto_venda":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else {
			$clienteDAO = new ClienteDAO($dataBase);
			$clienteDAO->setId( $request->getParameter("cod_cliente") );
			$vendaDAO = new VendaDAO($dataBase, $clienteDAO);
			if( $request->getParameter("cod_venda") ) {
			    $vendaDAO->setId( $request->getParameter("cod_venda") );
			}
			else {
			    $venda = new Venda(
				new Date($request->getParameter("data_venda"), "%d/%m/%y"),
				new Date($request->getParameter("data_entrega_venda"), "%d/%m/%y")
			    );
			    $vendaDAO->setVenda($venda);
			    $vendaDAO->insert();
			}
			
			if( $request->getParameter("cod_produto") ){
			    $produtoDAO = new ProdutoDAO($dataBase);
			    $produtoDAO->setId($request->getParameter("cod_produto"));
			    $produtoVenda = new ProdutoVenda(
				$produtoDAO->getProduto(),
				$request->getParameter("valor_produto_venda"),
				$request->getParameter("qtd_produto_venda"),
				$request->getParameter("entregue_produto_venda"),
				$request->getParameter("pago_produto_venda")
			    );
			    $produtoVendaDAO = new ProdutoVendaDAO(
				$dataBase,
				$vendaDAO,
				$produtoDAO->getId(),
				$produtoVenda
			    );
			    $produtoVendaDAO->insert();
			    $response->append("<h2>Produto inserido com sucesso!</h2>");
			}
			
			$response->append(
			    $vendaView->inserirProduto(
				$clienteDAO,
				$vendaDAO,
				ProdutoDAO::getTodos($dataBase),
				ProdutoVendaDAO::getTodosPorVenda($dataBase, $vendaDAO)
			    )
			);
			
		    }
		    break;
		case "excluir_produto_venda":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else {
			$clienteDAO = new ClienteDAO($dataBase);
			$clienteDAO->setId($request->getParameter("cod_cliente"));
			$vendaDAO = new VendaDAO($dataBase, $clienteDAO);
			$vendaDAO->setId($request->getParameter("cod_venda"));
			$produtoVendaDAO = new ProdutoVendaDAO($dataBase, $vendaDAO, $request->getParameter("cod_produto"));
			$produtoVendaDAO->delete();
			$response->append(
			    $vendaView->inserirProduto(
				$clienteDAO,
				$vendaDAO,
				ProdutoDAO::getTodos($dataBase),
				ProdutoVendaDAO::getTodosPorVenda($dataBase, $vendaDAO)
			    )
			);
		    }
		    break;
		case "consulta_venda_todas":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else {
			$pagina = $request->getParameter("pagina") ? $request->getParameter("pagina") : 1;
			$response->append( $vendaView->consultaTodos(VendaDAO::getTodas($dataBase), $pagina) );
		    }
		    break;
		case "detalhes_venda":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else {
			$vendaDAO = new VendaDAO($dataBase);
			$vendaDAO->setId( $request->getParameter("cod_venda") );
			$response->append(
			    $vendaView->detalhesVenda(
				$vendaDAO,
				ProdutoVendaDAO::getTodosPorVenda($dataBase, $vendaDAO)
			    )
			);
		    }
		    break;
		case "editar_detalhe_venda":
		    if( !$online )
			$response->append( $loginView->login("Requisi&ccedil;&atilde;o Inv&aacute;lida. Fa&ccedil;a o Login.") );
		    else {
			$vendaDAO = new VendaDAO($dataBase);
			$vendaDAO->setId( $request->getParameter("cod_venda") );
			$vendaDAO->getVenda()->setDataEntrega(
			    new Date($request->getParameter("data_entrega_venda"), "%d/%m/%y")
			);
			$vendaDAO->update();
			$listaProdutoVendaDAO = ProdutoVendaDAO::getTodosPorVenda($dataBase, $vendaDAO);
			foreach($listaProdutoVendaDAO as $produtoVendaDAO) {
			    $hashEntregue = "entregue_produto_venda_{$produtoVendaDAO->getId()}";
			    $hashPago = "pago_produto_venda_{$produtoVendaDAO->getId()}";
			    $produtoVendaDAO->getProdutoVenda()->setEntregue($request->getParameter($hashEntregue));
			    $produtoVendaDAO->getProdutoVenda()->setPago($request->getParameter($hashPago));
			    
			    $produtoVendaDAO->update();
			    
			}
			$response->append("<h2>Venda atualizada!</h2>");
			$response->append(
			    $vendaView->detalhesVenda(
				$vendaDAO,
				ProdutoVendaDAO::getTodosPorVenda($dataBase, $vendaDAO)
			    )
			);
		    }
		    break;
		case "do_login":
		    $senha = $request->getParameter("senha_usuario");
		    if($senha != "codorna")
			$response->append( $loginView->login("Senha digitada inv&aacute;lida") );
		    else {
			$session->setAttribute("online", true);
			$dataInicio = time();
			while($diaSemanaInicio = date("N", $dataInicio-=86400) != 1);
			$dataFim = $dataInicio;
			while($diaSemanaFim = date("N", $dataFim+=86400) != 7);
			$response->append(
			    $mainView->principal(
				VendaDAO::getVendaEntregaPeriodo(
				    new Date(date("d/m/Y", $dataInicio), "%d/%m/%y"),
				    new Date(date("d/m/Y", $dataFim), "%d/%m/%y"),
				    $dataBase
				)
			    )
			);
		    }
		    break;
		case "logout":
		    $session->setAttribute("online", false);
		    $response->append( $loginView->login("Sess&atilde;o Encerrada.") );
		    break;
		default:
		    if(!$online)
			$response->append( $loginView->login() );
		    else {
			$dataInicio = time();
			while($diaSemanaInicio = date("N", $dataInicio-=86400) != 1);
			$dataFim = $dataInicio;
			while($diaSemanaFim = date("N", $dataFim+=86400) != 7);
			$response->append( $mainView->principal(
			    VendaDAO::getVendaEntregaPeriodo(
				    new Date(date("d/m/Y", $dataInicio), "%d/%m/%y"),
				    new Date(date("d/m/Y", $dataFim), "%d/%m/%y"),
				    $dataBase
				)
			    )
			);
		    }
		    break;
	    }
	    return $response;
	}
    }
?>