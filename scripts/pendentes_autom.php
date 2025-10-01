<?php
//configurações das conexões
include_once '../config.php';

logME("***************************************************************");
logME("Sincroniza Portal->Leirilis");
	
//dados da Leirilis
logMe("Carregar dados Leirilis - Início");
$dadosLL = dados( $fbConexaoLL );
logMe("Carregar dados Leirilis - Fim (" . count($dadosLL) . ")");

//dados no Uno/Portal
logMe("Carregar dados Portal - Início");
$dadosUno = dados( $fbConexaoUNO );
logMe("Carregar dados Portal - Fim (" . count($dadosUno) . ")");

//procura as diferenças
logMe("Procurar diferenças Portal/Leirilis - Início");
$diff=encontrarDiferencas( $dadosUno, $dadosLL );	
logMe("Procurar diferenças Portal/Leirilis - Fim (" . count($diff) . ")");

if (count($diff) > 0) {
	$num=1;
	foreach($diff as $d) {
		$sql="delete from dp_pendentes_ei where localizacao='$d[0]' and referencia='$d[1]';";
		$result=$fbConexaoUNO->exec($sql);
		//echo $num . " - Deletado $d[0] - $d[1]($d[2])<br>";
		logMe("$num . Deletado $d[0] - $d[1]($d[2])");
		//echo $sql . "<br>";
		$num++;
	}
	logMe("Portal/Leirilis Atualizado.");
} else {
	logMe("Nenhuma diferença encontrada no Portal.");
}

logME("Sincroniza Leirilis->Portal");

//procura as diferenças
logMe("Procurar diferenças - Início");
$diff=encontrarDiferencas( $dadosLL, $dadosUno );	
logMe("Procurar diferenças - Fim (" . count($diff) . ")");

echo "<br>";

if (count($diff) > 0) {	
	$num=1;
	foreach($diff as $d) {
	  $sql="INSERT INTO DP_PENDENTES_EI (LOCALIZACAO, REFERENCIA, QUANTIDADE)
                     VALUES ('$d[0]', '$d[1]', $d[2]);";
	  $result=$fbConexaoUNO->exec($sql);
		//echo $num . " - Inserido $d[0] - $d[1]($d[2])<br>";
		logMe("$num . Inserido $d[0] - $d[1]($d[2])");
		//echo $sql . "<br>";
		$num++;
	}
	logMe("Leirilis/Portal Atualizado.");
} else {
	logMe("Nenhuma diferença encontrada na Leirilis.");
}

logMe("Processo finalizado.");

function logMe($msg){
	$dia = date("Ymd");

	$arquivo = fopen("../log/" . $dia . " - Pendentes.log", "a");

	$dataHora = date("Y-m-d H:i:s");

	$escreve = fwrite($arquivo, $dataHora . ' - ' . $msg . "\n");

	fclose($arquivo);
}

function dados($conn) {
	$sql = "select localizacao, referencia, quantidade from dp_pendentes_ei";
	
	$result = $conn->query($sql);
	
	$dados = array();
	
	foreach($result->fetchAll() as $dado) {
    $dados[] = $dado;
  }
	
	return $dados;
}

function encontrarDiferencas($array1, $array2) {
  $diferencas = [];

  foreach ($array1 as $linha1) {
    $encontrado = false;
    foreach ($array2 as $linha2) {
      if ($linha1[0] == $linha2[0] && $linha1[1] == $linha2[1] && $linha1[2] == $linha2[2]) {
        $encontrado = true;
        break;
      }
    }
    if (!$encontrado) {
      $diferencas[] = $linha1;
    }
  }
  return $diferencas;
}
?>