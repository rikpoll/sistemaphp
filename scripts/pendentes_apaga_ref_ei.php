<?php
  include_once '../config.php';
	
	$loc=$_POST['loc'];
	$ref=$_POST['referencia']; 
	
  $sql = "delete from dp_pendentes_ei where localizacao = '$loc' and referencia = '$ref'";
  $result=$fbConexaoUNO->exec($sql);			

	echo '<img src="../img/v.jpg" alt="Referencia Apagada." width=30 height=30>';
?>