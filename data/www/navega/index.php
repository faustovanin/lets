<?php
// Leigo 0 .. 3 Avancado

$SENTIDO_LEIGO = "l";
$SENTIDO_MAIS_LEIGO = "ml";
$SENTIDO_EXPERIENTE = "e";
$SENTIDO_MAIS_EXPERIENTE = "me";

$LEIGO_MIN = 0;
$EXPERIENTE_MAX = 3;

$COOKIE_PONTUACAO = "pontuacao";
$COOKIE_SENTIDO = "s";

if (isset($_GET[$COOKIE_SENTIDO])) {
  $sentido = $_GET[$COOKIE_SENTIDO];
}
else
  $sentido = "INFERNO!!!!!";


function pontuacao_atual()
{
	if (isset($_COOKIE[$GLOBALS['COOKIE_PONTUACAO']])) {
		$pontos = intval($_COOKIE[$GLOBALS['COOKIE_PONTUACAO']]);
		return $pontos;
	}
	return 1;
}

function atualiza_perfil($indo)
{
	$atual = pontuacao_atual();

	/** FIXME condicao de corrida em setcookie? */
	/** FIXME usuario clicar varias vezes num link? */
	switch ($indo) {
	case $GLOBALS["SENTIDO_LEIGO"]: $atual -= 1; break;
	case $GLOBALS["SENTIDO_MAIS_LEIGO"]: $atual -= 2; break;	
	case $GLOBALS["SENTIDO_EXPERIENTE"]: $atual += 1; break;
	case $GLOBALS["SENTIDO_MAIS_EXPERIENTE"]: $atual += 2; break;
	default:
		/* lixo, nada deve mudar */
	}

	if ($atual < $GLOBALS["LEIGO_MIN"])
		$atual = $GLOBALS["LEIGO_MIN"];
	else if ($atual > $GLOBALS["EXPERIENTE_MAX"])
		$atual = $GLOBALS["EXPERIENTE_MAX"];

	setcookie($GLOBALS['COOKIE_PONTUACAO'], "$atual", time() + 32140800);
}

atualiza_perfil($sentido);

function capta_conteudo()
{
  if (!isset($_GET["p"]))
  {
    $GLOBALS["arquivo"] = "nada.php";
    return;
  }
	
	switch ($_GET["p"]) {
	case "hist_orq": {atualiza_perfil("l");
                      $atual = pontuacao_atual();
                      if($atual<2)
                      {$GLOBALS["arquivo"] = "hist_orq_ini.php"; }
                      else
                      {$GLOBALS["arquivo"] = "hist_orq.php"; }
                    }break;
	case "partitura": {atualiza_perfil("me");
                      $atual = pontuacao_atual();
                      $GLOBALS["arquivo"] = "partitura.php";
                    }break;
	case "orquestra": {atualiza_perfil("ml");
                      $atual = pontuacao_atual();
                      if($atual<2)
                      {$GLOBALS["arquivo"] = "orquestra_ini.php"; }
                      else
                      {$GLOBALS["arquivo"] = "orquestra.php"; }
                    }break;
	case "cordas": {atualiza_perfil("l");
                      $atual = pontuacao_atual();
                      if($atual<2)
                      {$GLOBALS["arquivo"] = "cordas.php";}
                      else
                      {$GLOBALS["arquivo"] = "cordas.php";}
                    }break;
	case "sopro": {atualiza_perfil("me");
                      $atual = pontuacao_atual();
                      if($atual<2)
                      {$GLOBALS["arquivo"] = "sopro.php"; }
                      else
                      {$GLOBALS["arquivo"] = "sopro.php"; }
               }break;
	case "percussao": {atualiza_perfil("e");
                      $atual = pontuacao_atual();
                      if($atual<2)
                      {$GLOBALS["arquivo"] = "percussao.php"; }
                      else
                      {$GLOBALS["arquivo"] = "percussao.php"; }
               } break;
	default:
    $GLOBALS["arquivo"] = "nada.php"; break;
	}
}
capta_conteudo();
?>

<html>
<head>
<style>
  #menu a{color:black; text-decoration:none;}
  #menu a:visited {color:black;}
  #corpo a{color:blue;}
</style>
<title>Bem vindo ao Musica.utp.br</title>
</head>
<body >
	<div id="menu">
	<table border="0" cellspacing="0" bordercolor="#000000" background="teste_fundo1.jpg">	
		<tr>
			<td valign="bottom" width="1200" colspan="2" height="143">
				<table  cellspacing="0" border="0" bordercolor="#000000" bgcolor="#ffffff" align="right" background="teste_fundo1.jpg">
					<td align="center" width="250"><a href="bla.php?p=orquestra"><b>ORQUESTRA</b></a></td>
					<td align="center" width="250"><a href="bla.php?p=cordas"><b>CORDAS</b></a></td>
					<td align="center" width="250"><a href="bla.php?p=sopro"><b>SOPRO</b></a></td>
					<td align="center" width="250"><a href="bla.php?p=percussao"><b>PERCUSSÃO</b></a></td>
				</table>
			</td>
		</tr>
		<tr>
			<td height="794" width="250" valign="top">
			<br><br><br>
			<br><br><br>
			<br><br><br>
			<a href="bla.php?p=hist_orq"><b>HISTÓRIA ORQUESTRA</b></a>
      <a href="bla.php?p=partitura"><b>PARTITURA</b></a>
      </td>                       
			<td border="0" colspans="2" cellspacing="0" bordercolor="#000000" align="center">
			
				<table  cellspacing="0" border="0" bordercolor="#000000" bgcolor="#ffffff" align="right"> 
				
					<td valign="top" align="left" width="725" height="780" border="0"><div id="corpo"><?php include($GLOBALS["arquivo"]); ?></div></td>
					<td valign="middle" align="right" width="200" height="750" border="0"></td>
				</table>
			
			</td>

		</tr>
	</table>
	      </div>


</body>
</html>