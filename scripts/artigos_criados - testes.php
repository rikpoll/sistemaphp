<?php
//include_once '../config.php';
//include_once '../banco.php';
$pag_atual="Artigos Criados a Data";
include_once '../template/header.php';
include_once 'artigos_criados_consultas.php';

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
			  <option value="1" <?php echo $mes!=1 ?:" selected";?>>Janeiro</option>
				<option value="2" <?php echo $mes!=2 ?:" selected";?>>Fevereiro</option>
				<option value="3" <?php echo $mes!=3 ?:" selected";?>>Março</option>
        <option value="4" <?php echo $mes!=4 ?:" selected";?>>Abril</option>
				<option value="5" <?php echo $mes!=5 ?:" selected";?>>Maio</option>
				<option value="6" <?php echo $mes!=6 ?:" selected";?>>Junho</option>
				<option value="7" <?php echo $mes!=7 ?:" selected";?>>Julho</option>
				<option value="8" <?php echo $mes!=8 ?:" selected";?>>Agosto</option>
				<option value="9" <?php echo $mes!=9 ?:" selected";?>>Setembro</option>
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
			
			<br />
      <input type="submit" class="input" name="buscar" value="Buscar">
    </form>
  </div> <!-- pesquisa -->
	
	<div class="artigos_data">
		<div class="artigos_resumo">
		
		
		<?php
		
		if (isset($_POST["buscar"])) { ?>
			<table>
			<tr>
				<th>Dia</th>
				<th>Artigos</th>
			</tr>
			<?php
			$result=artigos_criados_mes($mes);
			foreach ($result as $i) {
				echo "<tr><td>" . $i->DT_ABERTURA . "</td><td>" . $i->QT . "</td></tr>";
			}
			echo "</table><br />";
		}
		echo "<pre>";
		var_dump($result);
		echo "</pre>";
		?>
		</div> <!-- artigos_resumo -->
	</div> <!-- artigos_data -->
  
</div> <!-- artigos -->
<?php include_once '../template/footer.php'; ?>