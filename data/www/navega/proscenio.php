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

atualiza_perfil("e");
?>

Prolongamento no mesmo nível do palco projetado até o público que se adapta a diversas formas e dimensões.