<?php
    /**
     * File: MainView.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Tela principal do sistema
     * Release: Dezembro/2009
    **/
    
    require_once("app/util/Form.php");
    
    class Menu {
	protected $caption;
	protected $href;
	protected $submenuList = array();
	
	public function __construct($caption, $href=NULL) {
	    $this->caption = $caption;
	    $this->href = $href;
	}
	
	public function addSubItem(Menu $item) {
	    $this->submenuList[] = $item;
	}
	
	public function getHtml() {
	    if($this->href) {
		$html = "<a href='{$this->href}'><li>{$this->caption}</li></a>";
	    }
	    else {
		$html = "<li>{$this->caption}</li>";
	    }
	    if(count($this->submenuList))
		$html .= "<ul>";
	    foreach($this->submenuList as $subItem) {
		$html .= $subItem->getHtml();
	    }
	    if(count($this->submenuList))
		$html .= "</ul>";
	    return $html;
	}
    }
    
    class MainView {
	/**
	 * @method String principal
	 * @param VendaDAO[] lisatVenda Entregas da semana
	 * @return A tela principal do sistema
	**/
	public function principal($listaVenda) {
	    $html = "<h1>Sistema de Controle de Atelier - Tela Principal</h1>";
	    $menuProduto = new Menu("Produto");
	    $menuProduto->addSubItem( new Menu("Inserir", "form_inserir_produto") );
	    $menuProduto->addSubItem( new Menu("Consultar", "consulta_produto") );
	    
	    $menuCliente = new Menu("Cliente");
	    $menuCliente->addSubItem( new Menu("Inserir", "form_inserir_cliente") );
	    $menuCliente->addSubItem( new Menu("Consultar", "consulta_cliente") );
	    
	    $menuVenda = new Menu("Encomenda");
	    $menuVenda->addSubItem( new Menu("Inserir", "form_inserir_venda") );
	    $menuConsultaVenda = new Menu("Consultar", "consulta_venda_todas" );
	    $menuVenda->addSubItem($menuConsultaVenda);
	    
	    $logoff = new Menu("Sair", "logout");
	    $html .=
	    "
		<div id='menu'>
		    <ul>
			{$menuCliente->getHtml()}
			{$menuVenda->getHtml()}
			{$menuProduto->getHtml()}
			{$logoff->getHtml()}
		    </ul>
		</div>
	    ";
	    $html .=
	    "
		<div id='entregas_semana'>
		    <h2>Entregas da Semana</h2>
	    ";
	    if(!count($listaVenda)) {
		$html .= "<p class='aviso'>Sem entregas esta semana</p>";
		return $html;
	    }
	    $html .=
	    "
		<table class='consulta_venda_detalhe_cliente'>
		    <tr>
			<td>Data Entrega</td>
			<td>Cliente</td>
			<td>Lista de Produtos</td>
			<td colspan='2'>Opera&ccedil;&otilde;es</td>
		     
	    ";
	    foreach($listaVenda as $vendaDAO){
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
			<td>
			    <a href='detalhes_cliente?cod_cliente={$vendaDAO->getClienteDAO()->getId()}'>
				{$vendaDAO->getClienteDAO()->getCliente()->getNome()}
			    </a>
			</td>
		";
		$listaProdutoVenda = ProdutoVendaDAO::getTodosPorVenda($vendaDAO->getDatabase(), $vendaDAO);
		$html .=
		"
			<td>
			    <table class='consulta_venda_produto_detalhe_cliente'>
				<tr>
				    <td>Produto</td>
				    <td>Quantidade</td>
				</tr>
		";
		foreach($listaProdutoVenda as $produtoVendaDAO) {
		    $produtoVenda = $produtoVendaDAO->getProdutoVenda();
		    $produto = $produtoVenda->getProduto();
		    $totalProduto = $produtoVenda->getValor() * $produtoVenda->getQuantidade();
		    
		    $html .=
		    "
				<tr>
				    <td>{$produto->getNome()}</td>
				    <td>{$produtoVenda->getQuantidade()}</td>
				</tr>
		    ";
		}
		$html .=
		"
			    </table>
			</td>
			<td>
			    <a href='detalhes_venda?cod_venda={$vendaDAO->getId()}'>
				Detalhes
			    </a>
			</td>
			<td>
			    <a href='form_inserir_produto_venda?cod_cliente={$vendaDAO->getClienteDAO()->getId()}&cod_venda={$vendaDAO->getId()}'>
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
	    return $html;
	}
    }
?>