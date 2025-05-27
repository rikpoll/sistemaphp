<?php
include '../config.php';
include '../banco.php';
$pag_atual="Meus Dados";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 

log1($connect, $_COOKIE['login'], "Erro", "Alterar", "Erro de acesso.");

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
?>

<link rel="stylesheet" href="./me.css" type="text/css" />

<div class="geral">
	<h1>Meus Dados</h1>
	<h3>Você está logado como <spam><?php $_COOKIE['login'] ?></spam>, bem vindo.</h3>
</div>

<?php include '../template/footer.php'; ?>