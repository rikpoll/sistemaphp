<?php
  include_once '../config.php';
	
	$loc=$_POST['loc'];
	$ref=$_POST['referencia']; 
	$qt=$_POST['qt'];
	
  $sql = "insert into dp_pendentes_ei(localizacao, referencia, quantidade) values ('$loc', '$ref', $qt);";
  $result=$fbConexaoUNO->exec($sql);			
  //echo $sql;
	echo '<img src="../img/v.jpg" alt="Referencia Apagada." width=30 height=30>';
?>