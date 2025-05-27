<link rel="stylesheet" href="./existe.css" type="text/css" />
<link rel="stylesheet" href="./altera_produto.css" type="text/css" />

<?php
include '../config.php';
include '../banco.php';
$pag_atual="Consulta de Produtos/Estoque";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 

  log1($connect, $_COOKIE['login'], "Erro", "Consulta Produtos/Estoque", "Erro de acesso.");

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
endif; ?>

<div class="geral">
  <div class="altera">
    <div class="pesquisa">
      <h2>Consulta de Produtos</h2>
      <hr>  
      <form method="POST">
        <label for="freferencia">Referência:</label>
        <input type="text" id="freferencia" name="freferencia" autofocus>
        <input type="submit" value="Pesquisar" name="pesquisar">
      </form>
      <hr>
    </div>

<?php

log1($connect, $_COOKIE['login'], "Acesso", "Consulta Produtos/Estoque", "");

if (isset($_POST["pesquisar"])) {
  
  $artigo=$_POST['freferencia'];
  $LL=array();
  $VA=array();
  $BP=array();
  $EM=array();
  $portal=array();
  $wms=array();

  //se existe no SIA
  $LL=existe($fbConexaoLL,$artigo);
  $VA=existe($fbConexaoVA,$artigo);
  $BP=existe($fbConexaoBP,$artigo);
  $EM=existe($fbConexaoEM,$artigo);
	
	$LLs=estoques($fbConexaoLL,$artigo);
	$VAs=estoques($fbConexaoVA,$artigo);
  $BPs=estoques($fbConexaoBP,$artigo);
  $EMs=estoques($fbConexaoEM,$artigo);
	
	//estoque e localizacao WMS
	$wms_stLL=estoque_wms($connWMS,$artigo, 'LL');
	$wms_stVA=estoque_wms($connWMS,$artigo, 'VA');
	$wms_stBP=estoque_wms($connWMS,$artigo, 'BP');
	$wms_stEM=estoque_wms($connWMS,$artigo, 'EM');
	

  //se existe no wms
  $wms=existe_wms($connWMS,$artigo);
  //se existe no portal
  ?>
	<div>
		<h4>Dados do Artigo: </h4><h2><?php echo $artigo; ?><h2>
	</div>
  <div class="existe">
    <div class="existente">
      <p class="nome">&nbsp;<img src="../img/w.jpg" alt="Possui Stock" width=30 height=30></p>
      <hr>
      <div class="descricao">
        <p>Descrição</p>
      </div>
      <div class="detalhe">
        <div class="separador"></div><p>Família</p>
        <div class="separador"></div><p>Grupo</p>
        <div class="separador"></div><p>Marca</p>
        <div class="separador"></div><p>Preço 1</p>
        <div class="separador"></div><p>Preço 2</p>
        <div class="separador"></div><p>Preço 3</p>
        <div class="separador"></div><p>Preço 4</p>
        <div class="separador"></div><p>Preço 5</p>
        <div class="separador"></div><p>Preço 6</p>
        <div class="separador"></div><p>Preço de Custo</p>
        <div class="separador"></div><p>Custo Médio</p>
				<div class="separador"></div><p>Mov Stock</p>
				<div class="separador"></div><p>Data Abertura</p>
				<div class="separador"></div><p>Dt Últ Alteração</p>
      </div>
    </div>

    <div class="existente">
      <p class="nome">Leirilis
      <?php
        if ($LL==null) {
          echo '<img src="../img/x.png" alt="Possui Stock" width=30 height=30>';
        } else {
          echo '<img src="../img/v.jpg" alt="Possui Stock" width=30 height=30>';
        }
      ?>
      </p>
      <hr>
      <div class="descricao">
      <?php foreach($LL as $i) { ?>
        <p>
				  <?php
					  if (strlen($i->DESIGNACAO1)>58) {
						  echo substr($i->DESIGNACAO1,0,58)."...";
						} else {
							echo $i->DESIGNACAO1;
						}
				  ?>
				</p>
      </div>
      <div class="detalhe">
        <div class="separador"></div><p><?php echo ($i->COD_FAMILIA==null) ? '&nbsp;' : $i->COD_FAMILIA; ?></p>
        <div class="separador"></div><p><?php echo ($i->COD_GRUPO==null) ? '&nbsp;' : $i->COD_GRUPO; ?></p>
        <div class="separador"></div><p><?php echo ($i->COD_MARCA==null) ? '&nbsp;' : $i->COD_MARCA; ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO1); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO2); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO3); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO4); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO5); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO6); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO_CUSTO); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->CUSTO_MEDIO); ?></p>
				<div class="separador"></div><p><?php print($i->MOV_STOCKS); ?></p>
				<div class="separador"></div><p><?php print($i->DT_ABERTURA); ?></p>
				<div class="separador"></div><p><?php print($i->DT_ULT_ALTERACAO); ?></p>
        <?php } ?>
				<br>
      </div>
    </div>

    <div class="existente">
      <p class="nome">Varidauto
      <?php
        if ($VA==null) {
          echo '<img src="../img/x.png" alt="Possui Stock" width=30 height=30>';
        } else {
          echo '<img src="../img/v.jpg" alt="Possui Stock" width=30 height=30>';
        }
      ?>
      </p>
      <hr>
      <div class="descricao">
      <?php foreach($VA as $i) { ?>
        <p>
				  <?php
					  if (strlen($i->DESIGNACAO1)>58) {
						  echo substr($i->DESIGNACAO1,0,58)."...";
						} else {
							echo $i->DESIGNACAO1;
						}
				  ?>
				</p>
      </div>
      <div class="detalhe">
        <div class="separador"></div><p><?php echo ($i->COD_FAMILIA==null) ? '&nbsp;' : $i->COD_FAMILIA; ?></p>
        <div class="separador"></div><p><?php echo ($i->COD_GRUPO==null) ? '&nbsp;' : $i->COD_GRUPO; ?></p>
        <div class="separador"></div><p><?php echo ($i->COD_MARCA==null) ? '&nbsp;' : $i->COD_MARCA; ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO1); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO2); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO3); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO4); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO5); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO6); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO_CUSTO); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->CUSTO_MEDIO); ?></p>
				<div class="separador"></div><p><?php print($i->MOV_STOCKS); ?></p>
				<div class="separador"></div><p><?php print($i->DT_ABERTURA); ?></p>
				<div class="separador"></div><p><?php print($i->DT_ULT_ALTERACAO); ?></p>
        <?php } ?>
				<br>
      </div>
    </div>

    <div class="existente">
      <p class="nome">Biapeças
      <?php
        if ($BP==null) {
          echo '<img src="../img/x.png" alt="Possui Stock" width=30 height=30>';
        } else {
          echo '<img src="../img/v.jpg" alt="Possui Stock" width=30 height=30>';
        }
      ?>
      </p>
      <hr>
      <div class="descricao">
      <?php foreach($BP as $i) { ?>
        <p>
				  <?php
					  if (strlen($i->DESIGNACAO1)>58) {
						  echo substr($i->DESIGNACAO1,0,58)."...";
						} else {
							echo $i->DESIGNACAO1;
						}
				  ?>
				</p>
      </div>
      <div class="detalhe">
        <div class="separador"></div><p><?php echo ($i->COD_FAMILIA==null) ? '&nbsp;' : $i->COD_FAMILIA; ?></p>
        <div class="separador"></div><p><?php echo ($i->COD_GRUPO==null) ? '&nbsp;' : $i->COD_GRUPO; ?></p>
        <div class="separador"></div><p><?php echo ($i->COD_MARCA==null) ? '&nbsp;' : $i->COD_MARCA; ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO1); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO2); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO3); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO4); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO5); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO6); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO_CUSTO); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->CUSTO_MEDIO); ?></p>
				<div class="separador"></div><p><?php print($i->MOV_STOCKS); ?></p>
				<div class="separador"></div><p><?php print($i->DT_ABERTURA); ?></p>
				<div class="separador"></div><p><?php print($i->DT_ULT_ALTERACAO); ?></p>
        <?php } ?>
				<br>
      </div>
    </div>

    <div class="existente">
      <p class="nome">Empório
      <?php
        if ($EM==null) {
          echo '<img src="../img/x.png" alt="Possui Stock" width=30 height=30>';
        } else {
          echo '<img src="../img/v.jpg" alt="Possui Stock" width=30 height=30>';
        }
      ?>
      </p>
      <hr>
      <div class="descricao">
      <?php foreach($EM as $i) { ?>
        <p>
				  <?php
					  if (strlen($i->DESIGNACAO1)>58) {
						  echo substr($i->DESIGNACAO1,0,58)."...";
						} else {
							echo $i->DESIGNACAO1;
						}
				  ?>
				</p>
      </div>
      <div class="detalhe">
        <div class="separador"></div><p><?php echo ($i->COD_FAMILIA==null) ? '&nbsp;' : $i->COD_FAMILIA; ?></p>
        <div class="separador"></div><p><?php echo ($i->COD_GRUPO==null) ? '&nbsp;' : $i->COD_GRUPO; ?></p>
        <div class="separador"></div><p><?php echo ($i->COD_MARCA==null) ? '&nbsp;' : $i->COD_MARCA; ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO1); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO2); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO3); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO4); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO5); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO6); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->PRECO_CUSTO); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i->CUSTO_MEDIO); ?></p>
				<div class="separador"></div><p><?php print($i->MOV_STOCKS); ?></p>
				<div class="separador"></div><p><?php print($i->DT_ABERTURA); ?></p>
				<div class="separador"></div><p><?php print($i->DT_ULT_ALTERACAO); ?></p>
        <?php } ?>
				<br>
      </div>
    </div>

    <div class="existente">
      <p class="nome">WMS
      <?php
        if ($wms==null) {
          echo '<img src="../img/x.png" alt="Possui Stock" width=30 height=30>';
        } else {
          echo '<img src="../img/v.jpg" alt="Possui Stock" width=30 height=30>';
        }
      ?>
      </p>
      <hr>
      <div class="descricao">
      <?php foreach($wms as $i) { ?>
        <p>
				  <?php
					  if (strlen($i["designacao"])>58) {
						  echo substr($i["designacao"],0,58)."...";
						} else {
							echo $i["designacao"];
						}
				  ?>
				</p>
      </div>
      <div class="detalhe">
        <div class="separador"></div><p><?php echo ($i["cod_familia"]==null) ? '&nbsp;' : $i["cod_familia"]; ?></p>
        <div class="separador"></div><p><?php echo ($i["cod_grupo"]==null) ? '&nbsp;' : $i["cod_grupo"]; ?></p>
        <div class="separador"></div><p><?php echo ($i["cod_marca_leirilis"]==null) ? '&nbsp;' : $i["cod_marca_leirilis"]; ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i["preco1"]); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i["preco2"]); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i["preco3"]); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i["preco4"]); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i["preco5"]); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i["preco6"]); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i["preco_custo"]); ?></p>
        <div class="separador"></div><p><?php printf('%5.3f',$i["custo_medio"]); ?></p>
				<div class="separador"></div><p><?php print('&nbsp;'); ?></p>
        <?php } ?>
      </div>
    </div>
  </div>
	
	<!--Segunda DIV para estoques SIA -->
	<div class="existe">
		
		<!-- Título -->
		<div class="existente">
		  <p class="centro">Stocks<br><strong>SIA</strong></p>
		</div>
		
		<!-- Leirilis -->
		<div class="existente">
			<table class="tabsia">
				<?php 
					foreach($LLs as $i) {
						$loc=$i->COD_LOCALIZACAO;
						$qtd=$i->STOCK_ACTUAL;
						printf("<tr><td>Loja {$loc}</td><td>%d</td></tr>", $qtd);
					}
				?>
			</table>
		</div>
		
		<!-- Varidauto -->
		<div class="existente">
			<table class="tabsia">
				<?php 
					foreach($VAs as $i) {
						$loc=$i->COD_LOCALIZACAO;
						$qtd=$i->STOCK_ACTUAL;
						printf("<tr><td>Loja {$loc}</td><td>%d</td></tr>", $qtd);
					}
				?>
			</table>
		</div>
		
		<!-- Biapeças -->
		<div class="existente">
		  <table class="tabsia">
				<?php 
					foreach($BPs as $i) {
							$loc=$i->COD_LOCALIZACAO;
							$qtd=$i->STOCK_ACTUAL;
							printf("<tr><td>Loja {$loc}</td><td>%d</td></tr>", $qtd);
						}
				?>
			</table>
		</div>
		
		<!-- Empório -->
		<div class="existente">
		  <table class="tabsia">
					<?php 
						foreach($EMs as $i) {
								$loc=$i->COD_LOCALIZACAO;
								$qtd=$i->STOCK_ACTUAL;
								printf("<tr><td>Loja {$loc}</td><td>%d</td></tr>", $qtd);
							}
					?>
			</table>
		</div>
		
		<!-- Div vazia para manter layout -->
		<div class="existente_vazio">
		  <p></p>
		</div>
	</div>
	
	<!--Segunda DIV para estoques WMS -->
	<div class="existe">
		
		<!-- Título -->
		<div class="existente">
		  <p class="centro">Stocks<br><strong>WMS</strong></p>
		</div>
		
		<!-- Leirilis -->
		<div class="existente">
			<div class="detalhe_st centro">
				<?php foreach($wms_stLL as $i) { ?>
					<p><?php echo "Loja " . $i["ArmLocalizacao"] . " -> " . $i["Stock"]; ?></p>
					<p><?php
							 $prateleiras=explode(",", $i["Localizacao"]);
							 foreach($prateleiras as $p) {
								 echo $p."\n";
							 }
						 ?></p>
					<div class="separador_st"></div>
				<?php } ?>
			</div>	
		</div>
		
		<!-- Varidauto -->
		<div class="existente">
			<div class="detalhe_st centro">
				<?php foreach($wms_stVA as $i) { ?>
					<p><?php echo "Loja " . $i["ArmLocalizacao"] . " -> " . $i["Stock"]; ?></p>
					<p><?php
							 $prateleiras=explode(",", $i["Localizacao"]);
							 foreach($prateleiras as $p) {
								 echo $p."\n";
							 }
						 ?></p>
					<div class="separador_st"></div>
				<?php } ?>
			</div>	
		</div>
		
		<!-- Biepecas -->
		<div class="existente">
			<div class="detalhe_st centro">
				<?php foreach($wms_stBP as $i) { ?>
					<p><?php echo "Loja " . $i["ArmLocalizacao"] . " -> " . $i["Stock"]; ?></p>
					<p><?php
							 $prateleiras=explode(",", $i["Localizacao"]);
							 foreach($prateleiras as $p) {
								 echo $p."\n";
							 }
						 ?></p>
					<div class="separador_st"></div>
				<?php } ?>
			</div>	
		</div>
		
		<!-- Emporio -->
		<div class="existente">
			<div class="detalhe_st centro">
				<?php foreach($wms_stEM as $i) { ?>
					<p><?php echo "Loja " . $i["ArmLocalizacao"] . " -> " . $i["Stock"]; ?></p>
					<p><?php
							 $prateleiras=explode(",", $i["Localizacao"]);
							 foreach($prateleiras as $p) {
								 echo $p."\n";
							 }
						 ?></p>
					<div class="separador_st"></div>
				<?php } ?>
			</div>	
		</div>
		
		<!-- Div vazia para manter layout -->
		<div class="existente_vazio">
		  <p></p>
		</div>
	</div>
	
	

<?php
}
include '../template/footer.php';
?>