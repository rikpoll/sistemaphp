<?php
include_once '../config.php';
//include_once '../banco.php';
$pag_atual="Artigos Criados a Data";
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

<link rel="stylesheet" href="./artigos_criados.css" type="text/css" />

<div class="artigos">
  <h2>Artigos Criados a Data</h2>
  <hr>
  <div class="pesquisa">
    <form method="POST">
			<?php
			$dia = date('d');
			$mes = date('n');
			$ano = date('Y');
			?>
			
      <label for="ano">Ano:</label>
      <select name="ano" id="ano">
			  <option value="2022" <?php echo $ano!=2022 ?:" selected";?>>2022</option>
				<option value="2023" <?php echo $ano!=2023 ?:" selected";?>>2023</option>
				<option value="2024" <?php echo $ano!=2024 ?:" selected";?>>2024</option>
        <option value="2025" <?php echo $ano!=2025 ?:" selected";?>>2025</option>
				<option value="2026" <?php echo $ano!=2026 ?:" selected";?>>2026</option>
      </select>
			
			<label for="mes">Mês:</label>
      <select name="mes" id="mes">
			  <option value="01" <?php echo $mes!=1 ?:" selected";?>>Janeiro</option>
				<option value="02" <?php echo $mes!=2 ?:" selected";?>>Fevereiro</option>
				<option value="03" <?php echo $mes!=3 ?:" selected";?>>Março</option>
        <option value="04" <?php echo $mes!=4 ?:" selected";?>>Abril</option>
				<option value="05" <?php echo $mes!=5 ?:" selected";?>>Maio</option>
				<option value="06" <?php echo $mes!=6 ?:" selected";?>>Junho</option>
				<option value="07" <?php echo $mes!=7 ?:" selected";?>>Julho</option>
				<option value="08" <?php echo $mes!=8 ?:" selected";?>>Agosto</option>
				<option value="09" <?php echo $mes!=9 ?:" selected";?>>Setembro</option>
				<option value="10" <?php echo $mes!=10 ?:" selected";?>>Outubro</option>
				<option value="11" <?php echo $mes!=11 ?:" selected";?>>Novembro</option>
				<option value="12" <?php echo $mes!=12 ?:" selected";?>>Dezembro</option>
      </select>
			
			<label for="dia">Dia:</label>
			<?php
			$dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
			
			echo '<select name="dia" id="dia" value=' . $dia . '>';
			for ($i = 1; $i <= $dias_mes; $i++) {
				$padrao = $i == $dia ? " selected" : "";
				echo '<option value="' . $i . '"' . $padrao . '>' . $i . '</option>';
			}
			echo '</select>';
			?>			
			<label for="referencia">Referência</label>
			<input type="text" name="referencia" placeholder="Digite a referência" />
			<br />
			<p class="obs"><i>Se não for digitado uma referência vai buscar todos os artigos criados na data.</i></p>
      <input type="submit" class="input" name="buscar" value="Buscar">
    </form>
  </div> <!-- pesquisa -->
	
	<div class="artigos_data">
		<div class="artigos_resumo">
		
		<?php
		if (isset($_POST["buscar"])) : 
		  $ref=$_POST['referencia'];
		?>
			<table>
			<tr>
				<th>Dia</th>
				<th>Artigo</th>
				<th>Leirilis</th>
				<th>Varidauto</th>
				<th>Biapeças</th>
				<th>Empório</th>
				<th>WMS</th>
				<th>Portal</th>
				<th>Sincronizar</th>
				<th>Feito</th>
			</tr>
			<?php
			if ($ref!=null){
				$ll=verificarReferencia($fbConexaoLL,$ref);
				$va=verificarReferencia($fbConexaoVA,$ref);
				$bp=verificarReferencia($fbConexaoBP,$ref);
				$em=verificarReferencia($fbConexaoEM,$ref);
				$wms=verificarRefWMS($connWMS,$ref);
				$dp=verificarRefPortal($connPortal,$ref);
				$linha=1;
				foreach($ll as $i){
					echo "<tr><td>$i->DT_ABERTURA</td><td>$i->REFERENCIA</td>";
					echo '<td><img src="../img/v.jpg" alt="Cadastrada" width=30 height=30></td><td>';
					echo (!$va==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo "</td><td>";
					echo (!$bp==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo "</td><td>";
					echo (!$em==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo '</td><td>';
					echo (!$wms==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo '</td><td>';
					echo (!$dp==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo '</td><td>';
					echo '<button onclick="sincroniza('.$linha.', \''.$i->REFERENCIA.'\')" class="botao"></button>';
					echo '</td><td><div id="resultado'.$linha.'"></div></td></tr>';
					$linha++;
				}
			} else {
				$data = $_POST['ano'] . '-' . $_POST['mes'] . '-' . $_POST['dia'];
        $ll=verificarData($fbConexaoLL, $data);
				$linha=1;
				foreach($ll as $i){
					echo "<tr><td>$i->DT_ABERTURA</td><td>$i->REFERENCIA</td><td>";
					echo '<img src="../img/v.jpg" alt="Cadastrada" width=30 height=30></td><td>';
					$va=verificarReferencia($fbConexaoVA,$i->REFERENCIA);
					$bp=verificarReferencia($fbConexaoBP,$i->REFERENCIA);
					$em=verificarReferencia($fbConexaoEM,$i->REFERENCIA);
					$wms=verificarRefWMS($connWMS,$i->REFERENCIA);
					$dp=verificarRefPortal($connPortal,$i->REFERENCIA);
					echo (!$va==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo "</td><td>";
					echo (!$bp==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo "</td><td>";
					echo (!$em==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo "</td><td>";
					echo (!$wms==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo "</td><td>";
					echo (!$dp==null) ? '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>' : '<img src="../img/x.png" alt="Não cadastrada." width=30 height=30>';
					echo "</td><td>";
					echo '<button onclick="sincroniza('.$linha.', \''.$i->REFERENCIA.'\')" class="botao"></button>';
					echo '</td><td><div id="resultado'.$linha.'"></div></td></tr>';
					$linha++;
				}
			}
			?>
			</table>
		<?php	endif; /*fim do if buscar*/	?>
		
		</div> <!-- artigos_resumo -->
	</div> <!-- artigos_data -->
  
</div> <!-- artigos -->
<?php include_once '../template/footer.php'; ?>

<?php
function verificarReferencia($conn, $ref){
	$sql = "select dt_abertura, referencia
					from artigo
					where codigo_arm='2' and referencia = '" . $ref . "'";
					
	$result = $conn->query($sql);
	
	$dados = array();
	
	foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function verificarRefWMS($conn, $ref){
	$sql="select referencia
         from artigos where referencia ='" . $ref . "'";
  
  $result = sqlsrv_query($conn, $sql);

  $dados = array();

  while($dado = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $dados[]=$dado;
  }

  return $dados;
}

function verificarData($conn,$data){
	$sql="select dt_abertura, referencia
				from artigo
				where codigo_arm='2' and dt_abertura = '" . $data . "'";
	
	$result = $conn->query($sql);
	
	$dados = array();
	
	foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function verificarRefPortal($conn, $ref){
  $sql="select ProductRef referencia from NBrightBuyIdx where ProductRef ='$ref'";
	
	$result = sqlsrv_query($conn, $sql);

  $dados = array();

  while($dado = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $dados[]=$dado;
  }

  return $dados;
}
?>

<script>
	function sincroniza(id,referencia) {
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
        xhr.open('POST', 'artigos_criados_sincroniza.php', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("referencia=" + referencia);
    }
</script>