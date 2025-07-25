<?php
include '../config.php';
include '../banco.php';
$pag_atual="Altera Vendedor";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, $_COOKIE['login'], "Erro", "Altera Vendedor", "Erro de acesso.");

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

log1($connect, $_COOKIE['login'], "Acesso", "Altera Vendedor", "");

?>

<link rel="stylesheet" href="./vendedor.css" type="text/css" />

<div class="vendedor">
  <h2>Altera Vendedor</h2>
  <hr>
  <div class="pesquisa">
    <form method="POST">
      <label for="ano">Ano:</label>
      <select name="ano" id="ano">
        <option value="2025">2025</option>
				<option value="2026">2026</option>
      </select>

      <label for="diario">Diário:</label>
      <input type="text" name="diario" id="diario">
      <label for="numero">Número:</label>
      <input type="number" name="numero" id="numero">
			<br />
      <input type="submit" class="input" name="buscar" value="Buscar Fatura">
    </form>
  </div>
  
  <div class="listar">
   
  <?php
    if (isset($_POST['buscar'])) {
			$ano=$_POST['ano'];
			$diario=$_POST['diario'];
			$num=$_POST['numero'];
			log1($connect, $_COOKIE['login'], "Pesquisa", "Altera Vendedor", "{$ano} {$diario}/{$num}");
			
			if ($diario=='' or $num=='') {			
			  echo "Preencha os dados corretamente.";
			} else {
				echo "<div class=\"fatura\">";
				echo "<div class=\"cabeca\">";
				echo "<p class=\"linhaf\">Fatura: <spam>{$ano} {$diario}/{$num}</spam>.</p>";
				$result = vendedor_busca_cliente($fbConexaoLL,$ano,$diario,$num);
				foreach($result as $i) {
					print("<p class=\"linhaf\">Cliente: {$i->POCA_ENTI} - <spam>{$i->ENTI_NOME}</spam>.</p>");
				}
				echo "</div>"; //cabeca
				echo "<div class=\"linhas\">";
				echo "<table><tr><th>Referência</th><th>Quantidade</th><th>Valor</th><th>Operador</th><th>Vendedor</th></tr>";
				$result = vendedor_busca($fbConexaoLL,$ano,$diario,$num);
				foreach($result as $i) {
          print("<tr><td>{$i->REFERENCIA}</td><td>{$i->QTD}</td><td>{$i->VALOR}€</td><td>{$i->OPERADOR}</td><td>{$i->NOME}</td></tr>");
				}
				echo "</table>";
				echo "</div>"; //linhas
				echo "<div class=\"lista\">";			
		?>
			<p>
				<form method="POST" action="vendedor_altera.php">
					<input type="hidden" name="ano" value="<?php echo $ano; ?>">
					<input type="hidden" name="diario" value="<?php echo $diario; ?>">
					<input type="hidden" name="num" value="<?php echo $num; ?>">
					<label for="vendedor">Alterar Vendedor para:</label>
					<select name="vendedor" id="vendedor">
						<?php
							$result = vendedor_listaNomes($fbConexaoLL);
							foreach($result as $i) {
								print("<option value=\"{$i->COLAB}\">{$i->COLAB}</option>");
							}
						?>
					</select>
					<br />
					<input type="submit" class="input" name="alterar" value="Alterar Vendedor"> 
				</form>
			</p>
	<?php
			echo "</div>"; //lista
			echo "</div>"; //fatura  
			} //fecha else
		} // fecha if
  ?>

<?php include '../template/footer.php'; ?>