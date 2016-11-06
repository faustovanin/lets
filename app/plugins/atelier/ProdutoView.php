<?php
    /**
     * File: ProdutoView.php
     * @author Fausto Vanin <fnsvanin@yahoo.com.br>
     * Description: Classe de visualizaçao dos produtos
     * Release: Dezembro/2009
    **/
    
    require_once("app/util/Form.php");
    
    class ProdutoView {
	private static $produtosPorPagina = 10;
	
	/**
	 * @method setProdutosPorPagina
	 * @param int produtosPorPagina
	 **/
	public static function setProdutosPorPagina($produtosPorPagina) {
	    self::$produtosPorPagina = $produtosPorPagina;
	}
	
	/**
	 * @method getProdutosPorPagina
	 * @return O numero de produtos a ser mostrado por página
	**/
	public static function getProdutosPorPagina() {
	    return self::$produtosPorPagina;
	}
	
	/**
	 * @method String consultaPorNome
	 * @return A janela de consulta por nome
	**/
	public function verLista($listaProduto, $pagina=1) {
	    $html .= "<h1>Produtos Cadastrados</h1>";
	    $html .= "<table>";
	    
	    $listaProdutoPagina = array_slice($listaProduto, ($pagina-1)*self::$produtosPorPagina, self::$produtosPorPagina);
	    
	    $html .=
	    "
		<tr>
		    <td>Nome</td> <td colspan='2'>Opera&ccedil;&otilde;es</td>
		</tr>
	    ";
	    
	    foreach($listaProdutoPagina as $produtoDAO) {
		$produto = $produtoDAO->getProduto();
		$html .= "<tr>";
				
		$html .=
		"<td>
		    {$produto->getNome()}
		</td>
		<td>
		    <a href='form_editar_produto?cod_produto={$produtoDAO->getId()}'>
			Editar
		    </a>
		</td>
		<td>
		    <a href='excluir_produto?cod_produto={$produtoDAO->getId()}'>
			Excluir
		    </a>
		</td>
		";
		
		$html .= "</tr>";
	    }
	    $totalPaginas = ceil(count($listaProduto)/self::$produtosPorPagina);
	    $html .= "</table>";
	    $html .= "<a href='consulta_produto?pagina=1'>Primeira</a> | ";
	    $anterior = $pagina == 1 ? 1 : $pagina - 1;
	    $html .= "<a href='consulta_produto?pagina={$anterior}'>Anterior</a> | ";
	    $proximo = $pagina == $totalPaginas ? $pagina : $pagina + 1;
	    $html .= "<a href='consulta_produto?pagina={$proximo}'>Pr&oacute;ximo</a> | ";
	    $html .= "<a href='consulta_produto?pagina={$totalPaginas}'>&Uacute;ltimo</a>";
	    return $html;
	}
	
	/**
	 * @method String inserir Formulário de inserção
	 * @return Formulário de inserção de produto
	**/
	public function inserir($mensagem=NULL) {
	    if($mensagem) {
		$html .= "<h2 class='erro_produto'>{$mensagem}</h2>";
	    }
	    $html .= "<h1>Cadastro de Produto</h1>";
	    $form = new Form("inserir_produto", "POST");
	    $form->addItem( new InputItem("text", "nome_produto", "", "Nome Produto") );
	    $form->addItem( new InputItem("submit", "", "Enviar"));
	    $html .= $form->getHtml();
	    
	    return $html;
	}
	
	/**
	 * @method String editar
	 * @param ProdutoDAO produto Produto para ser editado
	 * @return Formulário de edição
	**/
	public function editar(ProdutoDAO $produtoDAO) {
	    $html = "<h1>Edi&ccedil;&atilde;o de Produto</h1>";
	    $form = new Form("editar_produto", "POST");
	    $form->addItem( new InputItem("text", "nome_produto", $produtoDAO->getProduto()->getNome(), "Nome Produto") );
	    $form->addItem( new InputItem("hidden", "cod_produto", $produtoDAO->getId()) );
	    $form->addItem( new InputItem("submit", "", "Enviar") );
	    $html .= $form->getHtml();
	    return $html;
	}
    }
?>