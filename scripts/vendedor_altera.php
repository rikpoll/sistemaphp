<?php
include '../config.php';
include '../banco.php';
$pag_atual="Altera Vendedor";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, $_COOKIE['login'], "Erro", "Altera Vendedor", "Erro de acesso.");

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

log1($connect, $_COOKIE['login'], "Acesso", "Altera Vendedor", "");

?>

<link rel="stylesheet" href="./vendedor.css" type="text/css" />

<?php
	$ano=$_POST['ano'];
	$diario=$_POST['diario'];
	$num=$_POST['num'];
	$vendedor=$_POST['vendedor'];

	vendedor_altera($fbConexaoLL,$ano,$diario,$num,$vendedor);
	log1($connect, $_COOKIE['login'], "Alteração", "Altera Vendedor", "{$ano} {$diario}/{$num} -> {$vendedor}");
?>

<div class="vendedor">
  <h3>Alterado para o vendedor <b><?php echo $vendedor; ?></b> na fatura <b><?php echo "{$ano} {$diario}/{$num}"; ?>.</h3>
	<a href="vendedor.php">Voltar</a>
</div>

<?php include '../template/footer.php'; ?>