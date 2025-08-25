<?php
include_once '../config.php';
//include_once '../banco.php';
$pag_atual="Sincronizar Encomendas Pendentes";
include_once '../template/header.php';

/*if (!isset($_COOKIE['login'])) : 
  
  log1($connect, $_COOKIE['login'], "Erro", $pag_atual, "Erro de acesso.");

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

log1($connect, $_COOKIE['login'], "Acesso", $pag_atual, "");
*/
?>

<link rel="stylesheet" href="./pendentes.css" type="text/css" />

<div class="pendentes">
  <h2>Sincroniza 'dp_pendentes_ei'</h2>
  <hr>
	<div class="totais">
		<div class="total">
			<p class="empresa_nome">Leirilis</p>
			<p class="empresa_total">
				<?php
					include_once '../config.php';
				
					$LL=totais($fbConexaoLL);
					$UNO=totais($fbConexaoUNO);
					foreach($LL as $i) {
						echo $i->T;
					}
				?>
			</p>
		</div> <!-- total -->
		<div class="total">
			<p class="empresa_nome">Portal</p>
			<p class="empresa_total">
				<?php
					include_once '../config.php';
				
					foreach($UNO as $i) {
						echo $i->T;
					}
				?>
			</p>
		</div> <!-- total -->
	</div> <!-- totais -->
 
  <div class="sync">
    <form method="POST">
      <input type="submit" class="input" name="sync" value="Sincronizar Tudo">
    </form>
  </div> <!-- sync -->
 
  <div class="result_sync">
	<?php
		if (isset($_POST["sync"])) {
			echo "<h3>Sincronização em andamento.</h3>";
			echo "<p>Data e hora de início: " . date("Y-m-d H:i:s") . "</p>";
			$dados=dados($fbConexaoLL);
			deleta($fbConexaoUNO);
			//$n=1;
			foreach($dados as $i) {
			  insere($fbConexaoUNO, $i[0], $i[1], $i[2]);
				//echo "<p>" . $n . " | " . date("Y-m-d H:i:s") . " - " . $i[0] . "-" . $i[1] . " - " . $i[2] . "</p>";
				//$n++;
			}
			echo "<p>Data e hora de finalização: " . date("Y-m-d H:i:s") . "</p>";
		}
	?>
	</div> <!-- result_sync -->
 
 <div class="diferenca">
		<?php
			include_once '../config.php';
			
			//preenche dos dados	
			$dadosLL = dados( $fbConexaoLL );
			$dadosUno = dados( $fbConexaoUNO );
			
			//procura diferencas no portal
			$diff=encontrarDiferencas( $dadosUno, $dadosLL );	
		  
			//se tiver diferenca traz a tabela
			if( count( $diff ) > 0 ) {
				echo '<table class="diff">';
				echo "<caption>Referencias Divergentes no Portal</caption>";
				echo "<tr><th>Linha</th><th>Armazém</th><th>Referencia</th><th>Portal</th><th>Leirilis</th><th>SYNC</th><th>Ok</th></tr>";
				$linha=1;
				foreach( $diff as $d ) {
					echo "<tr><td>$linha</td><td>$d[0]</td><td>" . $d[1] . "</td><td>" . intval($d[2]) . "</td><td>";
					
					//define array caso não tenha valor, não retorna erro
    			$qt_ll = array();
					//percorre o array da leirilis em busca da referencia/armazém
					foreach( $dadosLL as $dados ) {
						if(stripos($dados['REFERENCIA'], $d[1]) !== false && stripos($dados['LOCALIZACAO'], $d[0]) !== false) {
							$qt_ll[] = $dados;
						}
					}
					
					//se encontrou algo, retorna a qtde
					foreach( $qt_ll as $l ) {
						echo intval( $l[2] );
					}
					
					echo "</td><td>";
					if (!count($qt_ll)>0) {
					  echo '<button onclick="apaga_ref_ei(' . $linha . ', \'' . $d[0] . '\', \'' . $d[1] . '\')" class="botao_del"></button>';
					} else {
						echo '<button onclick="sync_ref_ei('. $linha . ', \'' . $d[0] . '\', \'' . $d[1] . '\', ' . intval($l[2]) . ')" class="botao_sync"></button>';
					}
					echo '</td><td><div id="resultado' . $linha . '"></div></td><tr>';
					$linha++;
				}
				echo "</table>";
				
				//Synca só os diferentes
				//echo '<button onclick="apaga_refs_portal('. $linha .', \''. $d[1] .'\')" class="input">Sincronizar Portal</button>';
				echo '<form method="POST">';
				echo '<input type="submit" class="input" name="syncPortal" value="Sincronizar Portal">';
				echo '</form>';
				
				if (isset($_POST['syncPortal'])) {
					foreach($diff as $d) {
						$sql="delete from dp_pendentes_ei where localizacao='$d[0]' and referencia='$d[1]';";
						//echo $sql . "<br>";
						$result=$fbConexaoUNO->exec($sql);
					}
					echo "<p>Sync finalizado.</p>";
				}
			}
			
			//procura diferencas na Leirilis
			$diffLL=encontrarDiferencas( $dadosLL, $dadosUno );	
		  
			//se tiver diferenca traz a tabela
			if( count( $diffLL ) > 0 ) {
				echo '<table class="diff">';
				echo "<caption>Referencias Divergentes na Leirilis</caption>";
				echo "<tr><th>Linha</th><th>Armazém</th><th>Referencia</th><th>Portal</th><th>Leirilis</th><th>SYNC</th><th>Ok</th></tr>";
				$linha=1;
				foreach( $diffLL as $d ) {
					echo "<tr><td>$linha</td><td>$d[0]</td><td>" . $d[1] . "</td><td>" . intval($d[2]) . "</td><td>";
					
					//define array caso não tenha valor, não retorna erro
    			$qt_ll = array();
					//percorre o array da leirilis em busca da referencia/armazém
					foreach( $dadosUno as $dados ) {
						if(stripos($dados['REFERENCIA'], $d[1]) !== false && stripos($dados['LOCALIZACAO'], $d[0]) !== false) {
							$qt_ll[] = $dados;
						}
					}
					
					//se encontrou algo, retorna a qtde
					foreach( $qt_ll as $l ) {
						echo intval( $l[2] );
					}
					
					echo "</td><td>";
					if (!count($qt_ll)>0) {
					  echo '<button onclick="add_ref_ei(' . $linha . ', \'' . $d[0] . '\', \'' . $d[1] . '\', ' . $d[2] . ')" class="botao_add"></button>';
					} else {
						echo '<button onclick="sync_ref_ll('. $linha . ', \'' . $d[0] . '\', \'' . $d[1] . '\', ' . intval($l[2]) . ')" class="botao_sync"></button>';
					}
					echo '</td><td><div id="resultadoll' . $linha . '"></div></td><tr>';
					$linha++;
				}
				echo "</table>";
				
				//Synca só os diferentes
				echo '<form method="POST">';
				echo '<input type="submit" class="input" name="syncLL" value="Sincronizar da Leirilis">';
				echo '</form>';
				
				if (isset($_POST['syncLL'])) {
					foreach($diffLL as $d) {
						$sql="insert into dp_pendentes_ei(localizacao, referencia, quantidade) values ('$d[0]', '$d[1]', $d[2]);";
						//echo $sql . "<br>";
						$result=$fbConexaoUNO->exec($sql);
					}
					echo "<p>Sync finalizado.</p>";
				}
			}
		?>
	</div> <!-- diferenca -->

</div> <!-- pendentes -->
<?php include_once '../template/footer.php'; ?>

<?php
function totais($conn){
	$sql = "select count(*)t from dp_pendentes_ei";
					
	$result = $conn->query($sql);
	
	$dados = array();
	
	foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
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

function deleta($conn) {
  $sqld="DELETE FROM DP_PENDENTES_EI;";
	$result=$conn->exec($sqld);
}	

function insere($conn, $loc, $ref, $qt) {
	$sqli="INSERT INTO DP_PENDENTES_EI (LOCALIZACAO, REFERENCIA, QUANTIDADE)
                     VALUES ('$loc', '$ref', $qt);";
	$result=$conn->exec($sqli);
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

<script>
	function apaga_ref_ei(id,loc,referencia) {
		// Criar um objeto XMLHttpRequest
		var xhr = new XMLHttpRequest();

		// Definir a função a ser chamada quando a resposta chegar
		xhr.onload = function() {
				if (xhr.status >= 200 && xhr.status < 300) {
						// Resposta do servidor
						document.getElementById('resultado'+id).innerHTML = xhr.responseText;
				} else {
						// Tratar erros
						console.error('Erro:', xhr.status, xhr.statusText);
				}
		};

		// Configurar a requisição (método, URL, assíncrona)
		xhr.open('POST', 'pendentes_apaga_ref_ei.php', true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send("loc=" + loc + "&referencia=" + referencia);
	}
		
	function sync_ref_ei(id,loc,referencia,qt) {
		// Criar um objeto XMLHttpRequest
		var xhr = new XMLHttpRequest();

		// Definir a função a ser chamada quando a resposta chegar
		xhr.onload = function() {
				if (xhr.status >= 200 && xhr.status < 300) {
						// Resposta do servidor
						document.getElementById('resultado'+id).innerHTML = xhr.responseText;
				} else {
						// Tratar erros
						console.error('Erro:', xhr.status, xhr.statusText);
				}
		};

		// Configurar a requisição (método, URL, assíncrona)
		xhr.open('POST', 'pendentes_sync_ref_ei.php', true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send("loc=" + loc + "&referencia=" + referencia + "&qt=" + qt);
	}
	
	function add_ref_ei(id,loc,referencia,qt) {
		// Criar um objeto XMLHttpRequest
		var xhr = new XMLHttpRequest();

		// Definir a função a ser chamada quando a resposta chegar
		xhr.onload = function() {
				if (xhr.status >= 200 && xhr.status < 300) {
						// Resposta do servidor
						document.getElementById('resultadoll'+id).innerHTML = xhr.responseText;
				} else {
						// Tratar erros
						console.error('Erro:', xhr.status, xhr.statusText);
				}
		};

		// Configurar a requisição (método, URL, assíncrona)
		xhr.open('POST', 'pendentes_add_ref_ei.php', true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send("loc=" + loc + "&referencia=" + referencia + "&qt=" + qt);
	}
</script>