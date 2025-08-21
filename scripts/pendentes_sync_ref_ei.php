<?php
  include_once '../config.php';
	
	$loc=$_POST['loc'];
	$ref=$_POST['referencia']; 
	$qt=$_POST['qt'];
	
  $sql = "update dp_pendentes_ei set quantidade = $qt where localizacao = '$loc' and referencia = '$ref'";
  $result=$fbConexaoUNO->exec($sql);			
  //echo $sql;
	echo '<img src="../img/v.jpg" alt="Referencia Apagada." width=30 height=30>';
?>