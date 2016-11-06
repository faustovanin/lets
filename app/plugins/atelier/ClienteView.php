<?php
    /**
     * File: ClienteView.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Classe de visualizaçao dos clientes
     * Release: Dezembro/2009
    **/
    
    require_once("app/util/Form.php");
    
    class ClienteView {
	/**
	 * @property int clientePorPagina Número de clientes por página para consulta
	**/
	private static $clientePorPagina = 10;
	private static $vendaPorPagina = 2;
	
	/**
	 * @method void setClientePorPagina Define o número de clientes por página para consulta
	 * @param int clientePorPagina
	**/
	public static function setClientePorPagina($clientePorPagina) {
	    self::$clientePorPagina = $clientePorPagina;
	}
	
	/**
	 * @method int getClientePorPagina
	 * @return O número de clientes por página par consulta
	**/
	public static function getClientePorPagina() {
	    return self::$clientePorPagina;
	}
	
	/**
	 * @method String inserir
	 * @param String mensagem Mensagem a ser mostrada na tela
	 * @param Request request Objeto request com os dados enviados para o caso de erros de validação
	 * @return Formulario de inserçao
	 **/
	public function inserir($mensagem= NULL, Request $request=NULL) {
	    if($mensagem)
		$html .= "<h2>{$mensagem}</h2>";
	    
	    $html .= "<h1>Inser&ccedil;&atilde;o de Cliente</h1>";
	    $form = new Form("inserir_cliente", "POST");
	    $basicoFieldSet = new FormFieldSet("Dados B&aacute;sicos");
	    $basicoFieldSet->addInputItem( new InputItem("text", "nome_cliente", "", "Nome", 50) );
	    $basicoFieldSet->addInputItem( new InputItem("text", "endereco_cliente", "", "Endere&ccedil;o", 100) );
	    $basicoFieldSet->addInputItem( new InputItem("text", "telefone_cliente", "", "Telefone") );
	    $basicoFieldSet->addInputItem(new InputItem("text", "celular_cliente", "", "Celular") );
	    $basicoFieldSet->addInputItem( new InputItem("text", "email_cliente", "", "Email") );
	    $form->addItem($basicoFieldSet);
	    
	    $medidaFieldSet = new FormFieldSet("Medidas");
	    $medidaFieldSet->addInputItem( new InputItem("text", "busto_cliente", "", "Busto") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "comprimento_busto_cliente", "", "Comprimento Busto") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "ombro_cliente", "", "Ombro") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "cintura_cliente", "", "Cintura") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "quadril_cliente", "", "Quadril") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "comprimento_cliente", "", "Comprimento") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "altura_gancho_cliente", "", "Altura Gancho") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "boca_cliente", "", "Boca") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "manga_cliente", "", "Manga") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "boca_manga_cliente", "", "Boca Manga") );
	    $form->addItem($medidaFieldSet);
	    
	    $form->addItem( new InputItem("submit", "", "Enviar"));
	    $form->addItem( new InputItem("reset", "", "Limpar") );
	    
	    $html .= $form->getHtml();
	    
	    return $html;
	}
	
	/**
	 * @method String editar
	 * @param ClienteDAO clienteDAO
	 * @param String mensagem
	 * @param Request request
	 * @return Formulario de ediçao
	**/
	public function editar(ClienteDAO $clienteDAO, $mensagem=NULL, Request $request=NULL) {
	    if($mensagem)
		$html .= "<h2>{$mensagem}</h2>";
	    $html .= "<h1>Edi&ccedil;&atilde;o de Cliente</h1>";
	    $form = new Form("editar_cliente", "POST");
	    
	    $cliente = $clienteDAO->getCliente();
	    $basicoFieldSet = new FormFieldSet("Dados B&aacute;sicos");
	    $basicoFieldSet->addInputItem( new InputItem("text", "nome_cliente", $cliente->getNome(), "Nome", 50) );
	    $basicoFieldSet->addInputItem( new InputItem("text", "endereco_cliente", $cliente->getEndereco(), "Endere&ccedil;o", 100) );
	    $basicoFieldSet->addInputItem( new InputItem("text", "telefone_cliente", $cliente->getTelefone(), "Telefone") );
	    $basicoFieldSet->addInputItem(new InputItem("text", "celular_cliente", $cliente->getCelular(), "Celular") );
	    $basicoFieldSet->addInputItem( new InputItem("text", "email_cliente", $cliente->getEmail(), "Email") );
	    $form->addItem($basicoFieldSet);
	    
	    $medida = $cliente->getMedida();
	    $medidaFieldSet = new FormFieldSet("Medidas");
	    $medidaFieldSet->addInputItem( new InputItem("text", "busto_cliente", $medida->getBusto(), "Busto") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "comprimento_busto_cliente", $medida->getComprimentoBusto(), "Comprimento Busto") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "ombro_cliente", $medida->getOmbro(), "Ombro") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "cintura_cliente", $medida->getCintura(),"Cintura") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "quadril_cliente", $medida->getQuadril(), "Quadril") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "comprimento_cliente", $medida->getComprimento(), "Comprimento") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "altura_gancho_cliente", $medida->getAlturaGancho(), "Altura Gancho") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "boca_cliente", $medida->getBoca(), "Boca") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "manga_cliente", $medida->getManga(), "Manga") );
	    $medidaFieldSet->addInputItem( new InputItem("text", "boca_manga_cliente", $medida->getBocaManga(), "Boca Manga") );
	    $form->addItem($medidaFieldSet);
	    
	    $form->addItem( new InputItem("submit", "", "Enviar"));
	    $form->addItem( new InputItem("reset", "", "Limpar") );
	    $form->addItem( new InputItem("hidden", "cod_cliente", $clienteDAO->getId() ) );
	    
	    $html .= $form->getHtml();
	    
	    return $html;
	}
	
	/**
	 * @method String consultaPorNome
	 * @param ClienteDAO[] listaCliente Lista de clientes
	 * @param String textoConsulta
	 * @param int pagina Página atual
	**/
	public function consultaPorNome($listaCliente, $pagina=1, $textoConsulta="") {
	    $html = "<h1>Consulta de Clientes por Nome</h1>";
	    $form = new Form("consulta_cliente", "POST");
	    $form->addItem( new InputItem("text", "nome_cliente", $textoConsulta, "Nome do Cliente") );
	    $form->addItem( new InputItem("submit", "", "Enviar") );
	    
	    $html .= $form->getHtml();
	    
	    $html .= "<table>";
	    $html .=
	    "
		<tr>
		    <td>Nome</td>
		    <td>Endere&ccedil;o</td>
		    <td>Telefone</td>
		    <td>Celular</td>
		    <td>Email</td>
		    <td colspan='3'>Opera&ccedil;&otilde;es</td>
		</tr>
	    ";
	    $listaClientePagina = array_slice($listaCliente, ($pagina-1)*self::$clientePorPagina, self::$clientePorPagina );
	    foreach( $listaClientePagina as $clienteDAO ) {
		$cliente = $clienteDAO->getCliente();
		$html .= "<tr>";
		
		$html .=
		"
		    <td>{$cliente->getNome()}</td>
		    <td>{$cliente->getEndereco()}</td>
		    <td>{$cliente->getTelefone()}</td>
		    <td>{$cliente->getCelular()}</td>
		    <td>{$cliente->getEmail()}</td>
		    <td><a href='detalhes_cliente?cod_cliente={$clienteDAO->getId()}'>Detalhes</a></td>
		    <td><a href='form_editar_cliente?cod_cliente={$clienteDAO->getId()}'>Editar</a></td>
		    <td><a href='excluir_cliente?cod_cliente={$clienteDAO->getId()}'>Excluir</a></td>
		";
		
		$html .= "</tr>";
	    }
	    $html .= "</table>";
	    $totalPaginas = ceil(count($listaCliente)/self::$clientePorPagina);
	    $html .= "<a href='consulta_cliente?pagina=1'>Primeira</a> | ";
	    $anterior = $pagina == 1 ? 1 : $pagina - 1;
	    $html .= "<a href='consulta_cliente?pagina={$anterior}'>Anterior</a> | ";
	    $proximo = $pagina == $totalPaginas ? $pagina : $pagina + 1;
	    $html .= "<a href='consulta_cliente?pagina={$proximo}'>Pr&oacute;ximo</a> | ";
	    $html .= "<a href='consulta_cliente?pagina={$totalPaginas}'>&Uacute;ltimo</a>";
	    
	    return $html;
	}
	
	/**
	 * @method String detalhesCliente
	 * @param ClienteDAO clienteDAO Objeto de dados do cliente
	 * @param VendaDAO[] listaVenda Vendas do cliente
	 * @param int pagina Pagina para visualização das vendas (default 1)
	 * @return Tela de detalhes do cliente
	**/
	public function detalhesCliente(ClienteDAO $clienteDAO, $listaVenda, $pagina=1) {
	    $html .= "<h1>Detalhes do Cliente</h1>";
	    $html .= "<a href='form_editar_cliente?cod_cliente={$clienteDAO->getId()}'>Editar</a> | ";
	    $html .= "<a href='excluir_cliente?cod_cliente={$clienteDAO->getId()}'>Excluir</a>";
	    
	    $html .= "<h2>Dados B&aacute;sicos</h2>";
	    $cliente = $clienteDAO->getCliente();
	    $html .=
	    "<table class='consulta_cliente_dados_basicos'>
		<tr>
		    <td>Nome</td> <td>{$cliente->getNome()}</td>
		</tr>
		<tr>
		    <td>Endere&ccedil;o</td> <td>{$cliente->getEndereco()}</td>
		</tr>
		<tr>
		    <td>Telefone</td> <td>{$cliente->getTelefone()}</td>
		</tr>
		<tr>
		    <td>Celular</td> <td>{$cliente->getCelular()}</td>
		</tr>
		<tr>
		    <td>Email</td> <td>{$cliente->getEmail()}</td>
		</tr>
	    </table>";
	    $medida = $cliente->getMedida();
	    $html .= "<h2>Medidas</h2>";
	    $html .=
	    "<table class='consulta_cliente_medidas'>
		<tr>
		    <td>Busto</td> <td>{$medida->getBusto()}</td>
		    <td>Comprimento Busto</td> <td>{$medida->getComprimentoBusto()}</td>
		</tr>
		<tr>
		    <td>Ombro</td> <td>{$medida->getOmbro()}</td>
		    <td>Cintura</td> <td>{$medida->getCintura()}</td>
		</tr>
		<tr>
		    <td>Quadril</td> <td>{$medida->getQuadril()}</td>
		    <td>Comprimento</td> <td>{$medida->getComprimento()}</td>
		</tr>
		<tr>
		    <td>Altura Gancho</td> <td>{$medida->getAlturaGancho()}</td>
		    <td>Boca</td> <td>{$medida->getBoca()}</td>
		</tr>
		<tr>
		    <td>Manga</td> <td>{$medida->getManga()}</td>
		    <td>Boca Manga</td> <td>{$medida->getBocaManga()}</td>
		</tr>
	    </table>";
	    $html .= "<h2>&Uacute;ltimas Encomendas</h2>";
	    $html .=
	    "
		<table class='consulta_venda_detalhe_cliente'>
		    <tr>
			<td>Data Entrega</td>
			<td>Produtos</td>
			<td>Total Venda</td>
			<td>Recebido</td>
			<td>A Receber</td>
			<td colspan='2'>Opera&ccedil;&otilde;es</td>
		     
	    ";
	    $listaVendaPagina = array_slice($listaVenda, ($pagina-1)*self::$vendaPorPagina, self::$vendaPorPagina);
	    foreach($listaVendaPagina as $vendaDAO){
		$venda = $vendaDAO->getVenda();
		$statusVenda = "aberta";
		if($venda->isEntregue())
		    if($venda->isPago())
			$statusVenda = "fechada";
		    else
			$statusVenda = "entregue";
		else if($venda->isPago())
		    $statusVenda = "pago";
		$totalVenda = 0;
		$totalRecebido = 0;
		$html .=
		"
		    <tr class='{$statusVenda}' title='{$statusVenda}'>
			<td>{$venda->getDataEntrega()->getFormat('%d/%m/%y')}</td>
		";
		$listaProdutoVenda = ProdutoVendaDAO::getTodosPorVenda($vendaDAO->getDatabase(), $vendaDAO);
		$html .=
		"
			<td>
			    <table class='consulta_venda_produto_detalhe_cliente'>
				<tr>
				    <td>Produto</td>
				    <td>Valor Unit&aacute;rio</td>
				    <td>Qtd</td>
				    <td>Total Produto</td>
				</tr>
		";
		foreach($listaProdutoVenda as $produtoVendaDAO) {
		    $produtoVenda = $produtoVendaDAO->getProdutoVenda();
		    $produto = $produtoVenda->getProduto();
		    $totalProduto = $produtoVenda->getValor() * $produtoVenda->getQuantidade();
		    $valorProdutoFormat = number_format($produtoVenda->getValor(), 2, ',', '.');
		    $totalProdutoFormat = number_format($totalProduto, 2, ',', '.');
		    
		    $html .=
		    "
				<tr>
				    <td>{$produto->getNome()}</td>
				    <td>R$ {$valorProdutoFormat}</td>
				    <td>{$produtoVenda->getQuantidade()}</td>
				    <td>R$ {$totalProdutoFormat}</td>
				</tr>
		    ";
		    $totalVenda += $totalProduto;
		    if($produtoVenda->getPago()) {
		        $totalRecebido += $totalProduto;
		}
		}
		$totalVendaFormat = number_format($totalVenda, 2, ',', '.');
		$totalRecebidoFormat = number_format($totalRecebido, 2, ',', '.');
		$totalAReceberFormat = number_format($totalVenda-$totalRecebido, 2, ',', '.');
		$html .=
		"
			    </table>
			</td>
			<td>R$ {$totalVendaFormat}</td>
			<td>R$ {$totalRecebidoFormat}</td>
			<td>R$ {$totalAReceberFormat}</td>
			<td>
			    <a href='detalhes_venda?cod_venda={$vendaDAO->getId()}'>
				Editar
			    </a>
			</td>
			<td>
			    <a href='form_inserir_produto_venda?cod_cliente={$clienteDAO->getId()}&cod_venda={$vendaDAO->getId()}'>
				Adicionar
			    </a>
			</td>
		    </tr>
		";
	    }
	    $html .=
	    "
		</table>
	    ";
	    $totalPaginas = ceil(count($listaVenda)/self::$vendaPorPagina);
	    $anterior = $pagina > 1 ? $pagina-1 : $pagina;
	    $proxima = $totalPaginas > 1 ? $pagina+1 : $pagina;
	    $html .= "<a href='detalhes_cliente?cod_cliente={$clienteDAO->getId()}&pagina=1'>Primeira</a> | ";
	    $html .= "<a href='detalhes_cliente?cod_cliente={$clienteDAO->getId()}&pagina={$anterior}'>Anterior</a> | ";
	    $html .= "<a href='detalhes_cliente?cod_cliente={$clienteDAO->getId()}&pagina={$proxima}'>Pr&oacute;xima</a> | ";
	    $html .= "<a href='detalhes_cliente?cod_cliente={$clienteDAO->getId()}&pagina={$totalPaginas}'>&Uacute;ltima</a>";
	    return $html;
	}
    }
?>