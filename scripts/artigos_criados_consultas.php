<?php
	
function artigos_criados_hoje($data) {
	$sql="select a.codigo_arm, a.referencia
               a.dt_abertura, a.dt_ult_alteracao,
							 a.sync, a.dt_sync
				from artigo a
				where codigo_arm='2' and dt_abertura = '" . $data . "'";

	$pesquisa=$fbConexaoLL->query($sql);

  $dados = array();

  foreach($pesquisa->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

class artigosPorDia {
	public $DATA;
	public $LL;
	public $VA;
}


function artigos_criados_mes($mes) {
	require '../config.php';
	
	$mes_dia_1=date("Y-m-".'1');
	$ultimoDia = date("t");
  $mes_dia_ultimo = date("Y-m-".$ultimoDia);
	
	/*$sql="select dt_abertura data, count(*)LL
        from artigo a
        where codigo_arm='2' and dt_abertura between '" . $mes_dia_1 . "' and '" . $mes_dia_ultimo . "'
        group by 1";
				
	$sqlv="select dt_abertura data, count(*)VA
        from artigo a
        where codigo_arm='2' and dt_abertura between '" . $mes_dia_1 . "' and '" . $mes_dia_ultimo . "'
        group by 1";
	
	$LL=$fbConexaoLL->query($sql);
	$VA=$fbConexaoVA->query($sqlv);

	//$sql_tmp = "CREATE TEMPORARY TABLE artigos_por_dia(dia date,LL int,VA int,EM int,BP int)";
	//$connect = query($sql_tmp);
  $i=1;
	echo "<pre>";
  $resultLL = $LL->fetchAll();
	$resultVA = $VA->fetchAll();
	
	echo "<h1>LEIRILIS</h1>";
	print_r($resultLL);
	
	echo "<br><br><h1>VARIDAUTO</h1>";
	print_r($resultVA);
	
	echo "<br><br><h1>TUDO JUNTO</h1>";
	print_r(array_merge($resultLL+$resultVA));*/
	
	echo "<br><br><h1>TESTE</h1>";

	$teste = [['data'=>'2025-07-11','LL'=>5,'VA'=>0],['data'=>'2025-07-13','LL'=>2,'VA'=>1]];
  $teste[] = ['data'=>'2025-07-12','LL'=>7,'VA'=>3];
	
	echo "Valor de X: " . $teste['1']['LL'] . "<br><br>";
	$teste['1']['LL']=9;
	$novo=['EM'=>'Teste'];
	$teste[0]=array_merge($teste[0],$novo);
	
	$data = '2025-07-15';
	$chave = array_search($data,array_column($teste,'data'));
	if ($chave!='') {
	  echo "encontrado no chave: " . $chave;
	} else {
		echo "sem chave";
  }
	
	echo "<pre>";
	print_r($teste);
	echo "</pre>";
	echo "<table>";
	echo "<tr><th>Data</th><th>LL</th><th>VA</th><th>EM</th></tr>";
	
	foreach($teste as $t){
		$em = $t['EM'] ?? 0;
  	echo "<tr><td>" . $t['data'] . "</td><td>" . $t['LL'] . "</td><td>" . $t['VA'] . "</td><td>" . $em . "</td></tr>";
	}
	echo "</table>";
	
	/*foreach($dadosLL as $d) {
			echo $i . " - " . $d->DT_ABERTURA . " || " . $d->QT;
			$i++;
			echo "<br />";
	}*/
		
	
	
	//foreach(
	
	/*foreach($VA->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dadosVA[] = $dado;
  }
	
	$dados = array_merge(*/

  //return $dados;
}

artigos_criados_mes(7);

