<?php
$pag_atual="Sincronizar Produtos Equivalentes";
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

<link rel="stylesheet" href="./prod_equiv_sync.css" type="text/css" />

<div class="prod_equiv">
  <h2>Sincroniza Produtos Equivalentes</h2>
  <hr>
	<div class="totais">
		<div class="total">
			<p class="empresa_nome">Varidauto</p>
			<p class="empresa_total">
				<?php
					include_once '../config.php';
			
					//preenche dos dados	
					$dadosUno = dados( $fbConexaoUNO );
					$dadosVA = dados( $fbConexaoVA );
					
					//procura diferencas no portal
					$diffVA=array_diff(array_column($dadosVA,0,'id'), array_column($dadosUno,0,'id'));
										
					//echo 'VA DIFF: ' . count($diffVA) . ' - Uno: ' . count($dadosUno) . ' - VA: ' . count($dadosVA);
					echo count($diffVA);
					$dadosVA=[];
					//$dadosUno = [];
				?>
			</p>
			
			<div class="sync">
				<form method="POST">
					<input type="submit" class="bt_sync" name="syncva" value="Sync VA">
				</form>
			</div> <!-- sync -->
		</div> <!-- total -->
		
		<div class="total">
			<p class="empresa_nome">Empório</p>
			<p class="empresa_total">
				<?php
					//trazer do array a quantidade que não existe no UNO
					$dadosEM = dados( $fbConexaoEM );
					
					//$dadosUno = dados( $fbConexaoUNO );
					$diffEM=array_diff(array_column($dadosEM,0,'id'), array_column($dadosUno,0,'id'));
					
					//echo 'EM DIFF: ' . count($diffEM) . ' - Uno: ' . count($dadosUno) . ' - EM: ' . count($dadosEM);					
					echo count($diffEM);
					$dadosEM=[];
					//$dadosUno = [];
				?>
			</p>
			
			<div class="sync">
				<form method="POST">
					<input type="submit" class="bt_sync" name="syncem" value="Sync EM">
				</form>
			</div> <!-- sync -->
		</div> <!-- total -->
		
		<div class="total">
			<p class="empresa_nome">Biapeças</p>
			<p class="empresa_total">
				<?php
					//trazer do array a quantidade que não existe no UNO
					$dadosBI = dados( $fbConexaoBP );
					
					//$dadosUno = dados( $fbConexaoUNO );
					
					$diffBI=array_diff(array_column($dadosBI,0,'id'), array_column($dadosUno,0,'id'));
					
					//echo 'EM DIFF: ' . count($diffBI) . ' - Uno: ' . count($dadosUno) . ' - EM: ' . count($dadosBI);					
					echo count($diffBI);
					
					$dadosBI=[];
					//$dadosUno = [];
					
				?>
			</p>
			
			<div class="sync">
				<form method="POST">
					<input type="submit" class="bt_sync" name="syncbi" value="Sync BP">
				</form>
			</div> <!-- sync -->
		</div> <!-- total -->
	</div> <!-- totais -->

  <div class="result_sync">
	<?php
		if (isset($_POST["syncva"])) {
			echo "<h3>Sincronização Varidauto em andamento.</h3>";
			echo "<p>Data e hora de início: " . date("Y-m-d H:i:s") . "</p>";
			
			if (count($diffVA) != 0) {
				//$dadosUno = dados( $fbConexaoUNO );
				$dadosVA = dados( $fbConexaoVA );
				$diffVA=array_diff(array_column($dadosVA,0,'id'), array_column($dadosUno,0,'id'));
				
				foreach(array_keys($diffVA) as $i) {
					$ref=$dadosVA[$i]['REFERENCIA'];
					$equiv=$dadosVA[$i]['REFERENCIA_EQUIV'];
					$linha=$dadosVA[$i]['N_LINHA'];
				
					insere($fbConexaoUNO,$ref,$equiv);
				}
			} else {
				echo 'Sem dados a sincronizar.';
			}
			
			echo "<p>Data e hora de finalização: " . date("Y-m-d H:i:s") . "</p>";
			$dadosVA=[];
			$dadosUno = [];
		}
		
		if (isset($_POST["syncem"])) {
			echo "<h3>Sincronização Empório em andamento.</h3>";
			echo "<p>Data e hora de início: " . date("Y-m-d H:i:s") . "</p>";
			
			if (count($diffVA) != 0) {
				//$dadosUno = dados( $fbConexaoUNO );
				$dadosEM = dados( $fbConexaoEM );
				$diffEM=array_diff(array_column($dadosEM,0,'id'), array_column($dadosUno,0,'id'));
				
				foreach(array_keys($diffEM) as $i) {
					$ref=$dadosEM[$i]['REFERENCIA'];
					$equiv=$dadosEM[$i]['REFERENCIA_EQUIV'];
					$linha=$dadosEM[$i]['N_LINHA'];
					
					insere($fbConexaoUNO,$ref,$equiv);
				}
			} else {
				echo 'Sem dados a sincronizar.';
			}
			
			echo "<p>Data e hora de finalização: " . date("Y-m-d H:i:s") . "</p>";
			$dadosEM=[];
			$dadosUno = [];
		}
		
		if (isset($_POST["syncbi"])) {
			echo "<h3>Sincronização Biapeças em andamento.</h3>";
			echo "<p>Data e hora de início: " . date("Y-m-d H:i:s") . "</p>";
			
			if (count($diffBI) != 0) {
				//$dadosUno = dados( $fbConexaoUNO );
				$dadosBI = dados( $fbConexaoBP );
				$diffVA=array_diff(array_column($dadosBI,0,'id'), array_column($dadosUno,0,'id'));
				
				foreach(array_keys($diffBI) as $i) {
					$ref=$dadosBI[$i]['REFERENCIA'];
					$equiv=$dadosBI[$i]['REFERENCIA_EQUIV'];
					$linha=$dadosBI[$i]['N_LINHA'];
					
					insere($fbConexaoUNO,$ref,$equiv);
				}
			} else {
				echo 'Sem dados a sincronizar.';
			}			
			
			echo "<p>Data e hora de finalização: " . date("Y-m-d H:i:s") . "</p>";
			$dadosBI=[];
			$dadosUno = [];
			
		}
	?>
	</div> <!-- result_sync -->

</div> <!-- pendentes -->
<?php include_once '../template/footer.php'; ?>

<?php
function dados($conn) {
	$sql = "select referencia, referencia_equiv, n_linha
	        from gia_produto_equivalente
					where codigo_arm='2'";
	
	$result = $conn->query($sql);
	
	$dados = array();
	
	foreach($result->fetchAll() as $dado) {
    $dados[] = $dado;
  }
	
	return $dados;
}

function insere($conn, $ref, $equiv) {
	$sql = "insert into gia_produto_equivalente(codigo_arm, referencia, n_linha, codigo_arm_equiv, referencia_equiv)
                                       values('2','$ref',
																			        (select coalesce(max(n_linha),0)+1 as linha from gia_produto_equivalente where REFERENCIA='$ref'),
																			        '2','$equiv')";

  $result=$conn->query($sql);
	//echo $sql;
}
?>