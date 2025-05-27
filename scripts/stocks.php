<?php
$artigo=$_POST['freferencia'];
$LL=array();
$VA=array();
$BP=array();
$EM=array();

$LL=estoques($fbConexaoLL,$artigo);
$VA=estoques($fbConexaoVA,$artigo);
$BP=estoques($fbConexaoBP,$artigo);
$EM=estoques($fbConexaoEM,$artigo);
?>

<link rel="stylesheet" href="./stocks.css" type="text/css" />

<div class="estoques">
  <!-- Leirilis -->
  <div class="estoque">
    <div class="titulo">
      <h3>Leirilis</h3>
    </div>
    <div class="dados">
      <?php if ($LL==null) { ?>
        <img src="../img/x.png" alt="Sem estoque" width="50" height="50" />
      <?php
        } else { ?>
          <table>
          <?php
          foreach($LL as $i) {
            $loc=$i->COD_LOCALIZACAO;
            $qtd=$i->STOCK_ACTUAL;
            printf("<tr><td>Loja {$loc}</td><td>%d</td></tr>", $qtd);
          }
          ?>
          </table>
        <?php } ?>
    </div>
  </div>
  
  <!-- Varidauto -->
  <div class="estoque">
    <div class="titulo">
      <h3>Varidauto</h3>
    </div>
    <div class="dados">
      <?php if ($VA==null) { ?>
        <img src="../img/x.png" alt="Sem estoque" width="50" height="50" />
      <?php
        } else { ?>
          <table>
          <?php
          foreach($VA as $i) {
            $loc=$i->COD_LOCALIZACAO;
            $qtd=$i->STOCK_ACTUAL;
            printf("<tr><td>Loja {$loc}</td><td>%d</td></tr>", $qtd);
          }
          ?>
          </table>
        <?php } ?>
    </div>
  </div>

  <!-- Biapecas -->
  <div class="estoque">
    <div class="titulo">
      <h3>Biapeças</h3>
    </div>
    <div class="dados">
      <?php if ($BP==null) { ?>
        <img src="../img/x.png" alt="Sem estoque" width="50" height="50" />
      <?php
        } else { ?>
          <table>
          <?php
          foreach($BP as $i) {
            $loc=$i->COD_LOCALIZACAO;
            $qtd=$i->STOCK_ACTUAL;
            printf("<tr><td>Loja {$loc}</td><td>%d</td></tr>", $qtd);
          }
          ?>
          </table>
        <?php } ?>
    </div>
  </div>

  <!-- Emporio -->
  <div class="estoque">
    <div class="titulo">
      <h3>Empório</h3>
    </div>
    <div class="dados">
      <?php if ($EM==null) { ?>
        <img src="../img/x.png" alt="Sem estoque" width="50" height="50" />
      <?php
        } else { ?>
          <table>
          <?php
          foreach($EM as $i) {
            $loc=$i->COD_LOCALIZACAO;
            $qtd=$i->STOCK_ACTUAL;
            printf("<tr><td>Loja {$loc}</td><td>%d</td></tr>", $qtd);
          }
          ?>
          </table>
        <?php } ?>
    </div>
  </div>
</div>