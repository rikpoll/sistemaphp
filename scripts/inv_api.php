<?php
	include '../config.php';
	include '../banco.php';
	if (isset($_GET['empresa'])) {
		switch ($_GET['empresa']) {
			case 1:
			  $conn=$fbConexaoLL;
				break;
			case 3:
			  $conn=$fbConexaoEM;
				break;
			case 5:
			  $conn=$fbConexaoVA;
				break;
			case 6:
			  $conn=$fbConexaoBP;
				break;
		}
		$result=inventarios_ni_lista($conn);
		if($result==null) {
			echo json_encode("Sem Resultados");
		} else {
			foreach($result as $i) {
				echo json_encode($i);
			} 
		}	
	}