<?php
include '../config.php';
include '../banco.php';
$pag_atual="Criar Paletes para o Funchal";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, $_COOKIE['login'], "Erro", "Paletes", "Erro de acesso.");

?>

<div class="geral">
  <div class="erro">
    <h2>Faça login.</h2>
    <p>Você não tem autorização para ver esse conteúdo.</p>
    <p>Faça <a href="../login/login.php">login</a> para aceder.<p>
    <p>Obrigado.</p>
  <div>
</div> 

<?php die();
endif; 

log1($connect, $_COOKIE['login'], "Acesso", "Palete", "");

?>

<link rel="stylesheet" href="./palete_funchal.css" type="text/css" />

<div class="paletes">

	<div class="titulo">
		<h2>Criar Palete</h2>
		<h4>Insira o Tipo de documento (TA/TL) e o número da transferência.<br>
				Será criada uma Palete na Leirilis para entrada do material do Funchal.</h4>
	</div>

	<div class="palete">
		<form method="POST">
			<label for="tipo">Tipo:</label>
			<select name="tipo" id="tipo">
				<option value="TA">TA</option>
				<option value="TL">TL</option>
			</select>
			<br />

			<label for="num">Número:</label>
			<input type="number" name="num" id="num">
			<br />
			
			<input type="submit" name="inserir" value="Criar Palete">
		</form>
	</div>

<?php

//verifica se o formulário foi enviado
if (isset($_POST["inserir"])) {
  
	//coloca na variavel o valor do form
  $num=$_POST['num'];
	
	//se o numero for maior que 0 continua
	if ($num>0) {
		
		//seta variavel com o tipo de documento TA ou TL
		$tipo=$_POST['tipo'];
		
		echo "<div class=\"result\">";
		
		$palete = palete_funchal_insere($connWMS, $tipo, $num);
		
		foreach($palete as $p) {
      echo "<h2>Palete criada com o número: " . $p["pkid"] . "</h2>";
			
			//inserir linha no log com a palete criada
		  log1($connect, $_COOKIE['login'], "Criar", "Palete", $tipo . "/" . $num . " - Palete " . $p["pkid"]);
    }
		//echo "<h2>Palete criada com o número: ". $num . " para a " . $tipo . "/" . $num . "</h2>";
		echo "</div>";	
		
	}
	//se for nulo ou < 0 mostra msg e sai
	else
	{
		echo "<h2>Precisa inserir o número da TA/TL</h2>";
		//inserir linha no log com o erro
		log1($connect, $_COOKIE['login'], "Erro", "Palete", "Sem número.");
	}

}

?>
</div>
<?php include '../template/footer.php'; ?>