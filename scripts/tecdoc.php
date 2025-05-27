<?php
include '../config.php';
include '../banco.php';
$pag_atual="TecDoc";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, $_COOKIE['login'], "Erro", "TecDoc", "Erro de acesso.");

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

log1($connect, $_COOKIE['login'], "Acesso", "TecDoc", "");

?>

<link rel="stylesheet" href="./tecdoc.css" type="text/css" />

<div class="tecdoc">
  <h2>Consulta TecDoc Duplicado</h2>
	<h4>Uma listagem com Referências para o mesmo TecDoc</h4>
  <hr>
  <div class="pesquisa">
    <form method="POST">
      <label for="ref">Código TecDoc</label>
      <input type="text" name="ref" id="ref">
      <input type="submit" name="buscar" value="Pesquisa TecDoc">
    </form>
  </div>
  
  <div class="listar">
   
  <?php
    if (isset($_POST['buscar'])) {
		  $codigo=$_POST['ref'];
			if ($codigo==null) {
			  log1($connect, $_COOKIE['login'], "Pesquisa", "TecDoc", "Lista Completa");
				echo "<h2>Lista Completa</h2>";
			} else {
			  log1($connect, $_COOKIE['login'], "Pesquisa", "TecDoc", "{$codigo}");
				echo "<h3>Trazendo dados de {$codigo}.</h3>";
			}
			$td=tecdoc_busca($fbConexaoLL,$codigo);
			foreach($td as $i) {
				$refs=explode(",",$i->REFS);
				echo "<div class=\"linha\">";
			  echo "<div class=\"codigo\"><p>{$i->CODIGO}</p></div>";
				echo "<div class=\"marca\"><p>{$i->MARCA}</p></div>";
				echo "<div class=\"ref\">";
				foreach($refs as $r) {
				  echo "<p>{$r}</p>";
				}
				echo "</div></div>";
				echo "<details>
							<summary>Comparar cadastro dos produtos...</summary>
							<div class=\"info\">
							<div class=\"colunan\"><h3>&nbsp;</h3>
							<p>Desc</p>
							<p>Fam</p>
							<p>Grupo</p>
							<p>Marca</p>
							<p>P1</p>
							<p>P2</p>
							<p>P3</p>
							<p>P4</p>
							<p>P5</p>
							<p>P6</p>
							<p>Custo</p>
							<p>CM</p>
							<p>MovStk</p>
							<p>Dt Aber</p>
							<p>Ult Alt</p>
							</div>";
				foreach($refs as $r) {
				  echo "<div class=\"coluna\"><h3>{$r}</h3>";
					$info=existe($fbConexaoLL,$r);
					foreach($info as $d) {
						echo strlen($d->DESIGNACAO1)<30 ? "<p>{$d->DESIGNACAO1}</p>" : "<p>" . substr($d->DESIGNACAO1,0,27) . "...</p>";
						echo "<p>{$d->COD_FAMILIA}</p>";
						echo "<p>{$d->COD_GRUPO}</p>";
						echo "<p>{$d->COD_MARCA}</p>";
						printf('<p>%5.3f</p>',$d->PRECO1);
						printf('<p>%5.3f</p>',$d->PRECO2);
						printf('<p>%5.3f</p>',$d->PRECO3);
						printf('<p>%5.3f</p>',$d->PRECO4);
						printf('<p>%5.3f</p>',$d->PRECO5);
						printf('<p>%5.3f</p>',$d->PRECO6);
						printf('<p>%5.3f</p>',$d->PRECO_CUSTO);
						printf('<p>%5.3f</p>',$d->CUSTO_MEDIO);
						echo "<p>{$d->MOV_STOCKS}</p>";
						echo "<p>{$d->DT_ABERTURA}</p>";
						echo "<p>{$d->DT_ULT_ALTERACAO}</p>";
					}
					echo "</div>";
				}
				echo "<div></details>";
			}
			
		}
	?>
	
	</div> <!--fim listar -->
</div> <!--fim tecdoc -->
			

<?php include '../template/footer.php'; ?>