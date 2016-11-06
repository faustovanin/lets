<?php
    /**
     * File: VendaView.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Arquivo de visualizaçao das vendas
     * Release: Dezembro/2009
    **/
    
    require_once("app/util/Form.php");
    require_once("VendaDAO.php");
    
    class VendaView {
	/**
	 * @property int vendasPorPagina Número de vendas por página
	**/
	private static $vendasPorPagina = 10;
	
	/**
	 * @method String inserir
	 * @param ClienteDAO[] listaCliente Lista de clientes para montar combo
	 * @return O formulario de inserçao de encomendas
	**/
	public function inserir($listaCliente) {
	    $html .= "<h1>Cadastro de Encomendas</h1>";
	    $form = new Form("form_inserir_produto_venda", "POST");
	    $comboCliente = new ComboBox("cod_cliente", "", "Cliente");
	    foreach($listaCliente as $clienteDAO) {
		$comboCliente->addItem( new InputItem("option", "", $clienteDAO->getId(), $clienteDAO->getCliente()->getNome() ) );
	    }
	    $form->addItem($comboCliente);
	    $form->addItem( new InputItem("text", "data_venda", date("d/m/Y", time()), "Data Venda") );
	    $form->addItem( new InputItem("text", "data_entrega_venda", "", "Data Entrega") );
	    $form->addItem( new InputItem("submit", "", "Cadastrar Produtos >>"));
	    
	    $html .= $form->getHtml();
	    
	    return $html;
	}
	
	/**
	 * @method String inserirProduto
	 * @param ClienteDAO clienteDAO Objeto cliente
	 * @param VendaDAO vendaDAO Objeto venda
	 * @param ProdutoDAO listaProduto Lista de Produtos para montar a combo box
	 * @return Formulário de inserção de produtos na venda
	**/
	public function inserirProduto(ClienteDAO $clienteDAO, VendaDAO $vendaDAO, $listaProduto, $listaProdutoVenda=NULL) {
	    $data = $vendaDAO->getVenda()->getData();
	    $dataEntrega = $vendaDAO->getVenda()->getDataEntrega();
	    $html .=
	    "
	    <h1>Produtos da Venda</h1>
	    <h2>Dados da Venda</h2>
	    <table class='dados_venda'>
		<tr>
		    <td>Cliente</td>
		    <td>{$clienteDAO->getCliente()->getNome()}</td>
		</tr>
		<tr>
		    <td>Data Venda</td>
		    <td>{$data->getFormat('%d/%m/%y')}</td>
		</tr>
		<tr>
		    <td>Data Entrega</td>
		    <td>{$dataEntrega->getFormat('%d/%m/%y')}</td>
		</tr>
	    </table>
	    ";
	    $form = new Form("form_inserir_produto_venda", "POST");
	    $comboProduto = new ComboBox("cod_produto", "", "Produto");
	    foreach($listaProduto as $produtoDAO) {
		$comboProduto->addItem( new InputItem("option", "", $produtoDAO->getId(), $produtoDAO->getProduto()->getNome() ) );
	    }
	    
	    $form->addItem($comboProduto);
	    $form->addItem( new InputItem("text", "valor_produto_venda", "", "Valor por Produto") );
	    $form->addItem( new InputItem("text", "qtd_produto_venda", "", "Quantidade" ) );
	    $form->addItem( new InputItem("checkbox", "entregue_produto_venda", "1", "Entregue") );
	    $form->addItem( new InputItem("checkbox", "pago_produto_venda", "1", "Pago") );
	    $form->addItem( new InputItem("hidden", "cod_venda", $vendaDAO->getId()) );
	    $form->addItem( new InputItem("hidden", "cod_cliente", $clienteDAO->getId()) );
	    $form->addItem( new InputItem("hidden", "data_venda", $data->getFormat("%d/%m/%y") ) );
	    $form->addItem( new InputItem("hidden", "data_entrega_venda", $dataEntrega->getFormat("%d/%m/%y") ) );
	    $form->addItem( new InputItem("submit", "", "Adicionar"));
	    
	    $html .= $form->getHtml();
	    if($listaProdutoVenda) {
		$html .= "<h2>Produtos da Venda</h2>";
		$html .=
		"
		    <table>
			<tr>
			    <td>Nome</td>
			    <td>Valor por Produto</td>
			    <td>Quantidade</td>
			    <td>Total Produto</td>
			    <td>Entregue</td>
			    <td>Pago</td>
			    <td>Opera&ccedil;&otilde;es</td>
			</tr>
		";
		$totalVenda = 0;
		$totalRecebido = 0;
		foreach($listaProdutoVenda as $produtoVendaDAO) {
		    $produtoVenda = $produtoVendaDAO->getProdutoVenda();
		    $produto = $produtoVenda->getProduto();
		    $totalProduto = $produtoVenda->getValor() * $produtoVenda->getQuantidade();
		    $valorProdutoFormat = number_format($produtoVenda->getValor(), 2, ',', '.');
		    $totalProdutoFormat = number_format($totalProduto, 2, ',', '.');
		    $status = array("N&atilde;o", "Sim");
		    
		    $html .=
		    "
			<tr>
			    <td>{$produto->getNome()}</td>
			    <td>R$ {$valorProdutoFormat}</td>
			    <td>{$produtoVenda->getQuantidade()}</td>
			    <td>R$ {$totalProdutoFormat}</td>
			    <td>{$status[$produtoVenda->getEntregue()]}</td>
			    <td>{$status[$produtoVenda->getPago()]}</td>
			    <td><a href='excluir_produto_venda?cod_venda={$vendaDAO->getId()}&cod_cliente={$clienteDAO->getId()}&cod_produto={$produtoVendaDAO->getId()}'>Excluir</a></td>
			</tr>
		    ";
		    $totalVenda += $totalProduto;
		    if($produtoVenda->getPago()) {
			$totalRecebido += $totalProduto;
		    }
		}
		$html .=
		"
		    </table>
		";
		$totalVendaFormat = number_format($totalVenda, 2, ',', '.');
		$totalRecebidoFormat = number_format($totalRecebido, 2, ',', '.');
		$totalAReceberFormat = number_format($totalVenda-$totalRecebido, 2, ',', '.');
		
		$html .= "<p class='total_venda'>Total da Encomenda: R$ {$totalVendaFormat} </p>";
		$html .= "<p class='total_recebido_venda'>Total Recebido: R$ {$totalRecebidoFormat}</p>";
		$html .= "<p class='total_a_receber_venda'>Total a Receber: R$ {$totalAReceberFormat}</p>";
	    }
	    return $html;
	}
	
	/**
	 * @method String consultaTodos
	 * @param VendaDAO[] listaVenda Lista de objetos venda
	 * @param int pagina Número da página a ver (default 1)
	 * @return Tela de consulta com todas as vendas
	**/
	public function consultaTodos($listaVenda, $pagina=1) {
	    $html .= "<h1>Consulta Vendas - Todas</h1>";
	    
	    $listaVendaPagina = array_slice($listaVenda, ($pagina-1)*self::$vendasPorPagina, self::$vendasPorPagina);
	    
	    $html .=
	    "
		<table class='consulta_venda'>
		    <tr>
			<td>Data Entrega</td>
			<td>Nome Cliente</td>
			<td>Opera&ccedil;&otilde;es</td>
		    </tr>
	    ";
	    
	    foreach($listaVendaPagina as $vendaDAO) {
		$venda = $vendaDAO->getVenda();
		$statusVenda = "aberta";
		if($venda->isEntregue())
		    if($venda->isPago())
			$statusVenda = "fechada";
		    else
			$statusVenda = "entregue";
		else if($venda->isPago())
		    $statusVenda = "pago";
		$html .=
		"
		    <tr class='{$statusVenda}' title='{$statusVenda}'>
			<td>{$venda->getDataEntrega()->getFormat('%d/%m/%y')}</td>
			<td>
			    <a href='detalhes_cliente?cod_cliente={$vendaDAO->getClienteDAO()->getId()}'>
				{$vendaDAO->getClienteDAO()->getCliente()->getNome()}
			    </a>
			</td>
			<td><a href='detalhes_venda?cod_venda={$vendaDAO->getId()}'>Detalhes</a></td>
		    </tr>
		";
	    }
	    $html .=
	    "
		</table>
	    ";
	    $totalPaginas = ceil(count($listaVenda)/self::$vendasPorPagina);
	    $anterior = $pagina > 1 ? $pagina-1 : $pagina;
	    $proximo = $pagina > 1 ? $pagina+1 : $pagina;
	    
	    $html .= "<a href='consulta_venda_todas?pagina=1'>Primeira</a> | ";
	    $html .= "<a href='consulta_venda_todas?pagina={$anterior}'>Anterior</a> | ";
	    $html .= "<a href='consulta_venda_todas?pagina={$proximo}'>Pr&oacute;ximo</a> | ";
	    $html .= "<a href='consulta_venda_todas?pagina={$totalPaginas}'>&Uacute;ltimo</a>";
	    
	    return $html;
	}
	
	/**
	 * @method String detalhesVenda Detalhes da venda
	 * @param VendaDAO vendaDAO Objeto venda
	 * @reutrn Tela de consulta da venda
	**/
	public function detalhesVenda(VendaDAO $vendaDAO, $listaProdutoVenda) {
	    $clienteDAO = $vendaDAO->getClienteDAO();
	    $cliente = $clienteDAO->getCliente();
	    $venda = $vendaDAO->getVenda();
	    
	    $html .= "<form action='editar_detalhe_venda' method='POST'>";
	    $html .= "<input type='hidden' name='cod_venda' value='{$vendaDAO->getId()}'/>";
	    
	    $html .= "<h1>Detalhes da Venda</h1>";
	    $html .= "<p class='nome_cliente_consulta_venda'>Nome:
		<a href='detalhes_cliente?cod_cliente={$clienteDAO->getId()}'>
		    {$cliente->getNome()}
		</a>
	    </p>";
	    $html .= "<p class='data_consulta_venda'>Data Venda: {$venda->getData()->getFormat('%d/%m/%y')}</p>";
	    $html .= "<p class='data_entrega_consulta_venda'>
		Data Entrega:
		<input type='text' name='data_entrega_venda' value='{$venda->getDataEntrega()->getFormat('%d/%m/%y')}'/>
	    </p>";
	    
	    $html .= "<h2>Produtos da Venda</h2>";
	    $html .=
	    "
		<table>
		    <tr>
			<td>Nome</td>
			<td>Valor por Produto</td>
			<td>Quantidade</td>
			<td>Total Produto</td>
			<td>Entregue</td>
			<td>Pago</td>
		    </tr>
	    ";
	    $totalVenda = 0;
	    $totalRecebido = 0;
	    
	    foreach($listaProdutoVenda as $produtoVendaDAO) {
		$produtoVenda = $produtoVendaDAO->getProdutoVenda();
		$produto = $produtoVenda->getProduto();
		$totalProduto = $produtoVenda->getValor() * $produtoVenda->getQuantidade();
		$valorProdutoFormat = number_format($produtoVenda->getValor(), 2, ',', '.');
		$totalProdutoFormat = number_format($totalProduto, 2, ',', '.');
		$entregueChecked = $produtoVenda->getEntregue() ? "CHECKED" : "";
		$pagoChecked = $produtoVenda->getPago() ? "CHECKED" : "";
		$html .=
		"
		    <tr>
			<td>{$produto->getNome()}</td>
			<td>R$ {$valorProdutoFormat}</td>
			<td>{$produtoVenda->getQuantidade()}</td>
			<td>R$ {$totalProdutoFormat}</td>
			<td><input type='checkbox' name='entregue_produto_venda_{$produtoVendaDAO->getId()}' {$entregueChecked}/></td>
			<td><input type='checkbox' name='pago_produto_venda_{$produtoVendaDAO->getId()}' {$pagoChecked}/></td>
		    </tr>
		";
		$totalVenda += $totalProduto;
		if($produtoVenda->getPago()) {
		    $totalRecebido += $totalProduto;
		}
	    }
	    $html .=
	    "
		</table>
	    ";
	    $html .=
	    "
		    <input type='submit' value='Atualizar'/>
		</form>
	    ";
	    
	    $totalVendaFormat = number_format($totalVenda, 2, ',', '.');
	    $totalRecebidoFormat = number_format($totalRecebido, 2, ',', '.');
	    $totalAReceberFormat = number_format($totalVenda-$totalRecebido, 2, ',', '.');
	    
	    $html .= "<p class='total_venda'>Total da Encomenda: R$ {$totalVendaFormat} </p>";
	    $html .= "<p class='total_recebido_venda'>Total Recebido: R$ {$totalRecebidoFormat}</p>";
	    $html .= "<p class='total_a_receber_venda'>Total a Receber: R$ {$totalAReceberFormat}</p>";
	    
	    return $html;
	}
    }
?>