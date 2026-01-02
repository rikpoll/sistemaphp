<link rel="stylesheet" href="./precos_varidauto.css" type="text/css" />

<?php
include_once '../config.php';
include_once '../banco.php';
$pag_atual="Precos Varidauto-WMS";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, '', "Erro", $pag_atual, "Erro de acesso.");

?>

<div class="precos_container">
  <h2>Faça login.</h2>
  <p>Você não tem autorização para ver esse conteúdo.</p>
  <p>Faça <a href="../login/login.php">login</a> para aceder.<p>
  <p>Obrigado.</p>
</div>

<?php
die();
endif; 

log1($connect, $_COOKIE['login'], "Acesso", $pag_atual, "");

?>

<div class="precos_container">
  <div class="precos_form">
    <h2>Alterar Preços WMS</h2>
    <h3>Alterar Preços na Varidauto para o WMS, para etiquetas.</h3>
    <hr />  
    <form method="POST" onsubmit="return validarFormulario()">
      <label for="ref">Referência:</label>
      <input type="text" name="ref" id="ref" placeholder="Digite a Referencia" onkeyup="this.value=this.value.toUpperCase()" autofocus />
      <input type="submit" class="botao" value="Buscar" name="buscar_preco">
    </form>    
    <?php
    if (isset($_POST["buscar_preco"])) { 
      $artigo=$_POST["ref"];
      $VA=existe($fbConexaoVA,$artigo);
      $wms=existe_wms($connWMS,$artigo);
      
      if ($VA!=null && $wms!=null) {
        log1($connect, $_COOKIE['login'], "Altera Preços", $artigo, "Referencia pesquisada e encontrada.");
        foreach($VA as $i) {
          $descricao=$i->DESIGNACAO1;
          $VApreco1=$i->PRECO1;
          $VApreco2=$i->PRECO2;
          $VApreco3=$i->PRECO3;
          $VApreco4=$i->PRECO4;
          $VApreco5=$i->PRECO5;
          $VApreco6=$i->PRECO6;
        }

        foreach($wms as $w) {
          $WMSpreco1=$w["preco1"];
          $WMSpreco2=$w["preco2"];
          $WMSpreco3=$w["preco3"];
          $WMSpreco4=$w["preco4"];
          $WMSpreco5=$w["preco5"];
          $WMSpreco6=$w["preco6"];
        }
        
    ?>
    
        <form method="POST">
          <center>
            <h2><?php echo $artigo; ?></h2>
            <h3><?php echo $descricao; ?></h3>
            <br>
          </center>
          
          <input type="hidden" name="refart" id="refart" value="<?php echo $artigo; ?>">
          <div class="form_precos">
            <div class="form_precos_int">
              <h3>Preços SIA</h3>
              <label for="fpreco1">Preço 1:</label>
              <input type="number" step="0.001" id="fpreco1" name="fpreco1" value="<?php printf('%5.3f', $VApreco1); ?>" size="7" autofocus ><br>
              <label for="fpreco2">Preço 1:</label>
              <input type="number" step="0.001" id="fpreco2" name="fpreco2" value="<?php printf('%5.3f', $VApreco2); ?>" size="7"><br>
              <label for="fpreco3">Preço 1:</label>
              <input type="number" step="0.001" id="fpreco3" name="fpreco3" value="<?php printf('%5.3f', $VApreco3); ?>" size="7"><br>
              <label for="fpreco4">Preço 1:</label>
              <input type="number" step="0.001" id="fpreco4" name="fpreco4" value="<?php printf('%5.3f', $VApreco4); ?>" size="7"><br>
              <label for="fpreco5">Preço 1:</label>
              <input type="number" step="0.001" id="fpreco5" name="fpreco5" value="<?php printf('%5.3f', $VApreco5); ?>" size="7"><br>
              <label for="fpreco6">Preço 1:</label>
              <input type="number" step="0.001" id="fpreco6" name="fpreco6" value="<?php printf('%5.3f', $VApreco6); ?>" size="7"><br>
            </div>
            <div class="form_precos_int">
              <h3>Preços WMS</h3>
              <p><?php printf('%5.3f €', $WMSpreco1); ?></p>
              <p><?php printf('%5.3f €', $WMSpreco2); ?></p>
              <p><?php printf('%5.3f €', $WMSpreco3); ?></p>
              <p><?php printf('%5.3f €', $WMSpreco4); ?></p>
              <p><?php printf('%5.3f €', $WMSpreco5); ?></p>
              <p><?php printf('%5.3f €', $WMSpreco6); ?></p>
            </div>

          </div> <!-- form_precos -->

          <input type="submit" class="botao" value="Alterar" name="alterar_preco">
        </form> 
      
    <?php
      } else {
        echo "<h3>Nenhum artigo encontrado.</h3>";
        log1($connect, $_COOKIE['login'], "Altera Preços", $artigo, "Nada encontrado.");
      }
    } 
    
    if (isset($_POST["alterar_preco"])) {
      $referencia=$_POST["refart"];
      $p1=$_POST["fpreco1"];
      $p2=$_POST["fpreco2"];
      $p3=$_POST["fpreco3"];
      $p4=$_POST["fpreco4"];
      $p5=$_POST["fpreco5"];
      $p6=$_POST["fpreco6"];
      
      //alterar no SIA
      $sql_sia="update artigo set preco1=$p1, preco2=$p2, preco3=$p3, preco4=$p4, preco5=$p5, preco6=$p6 where codigo_arm='2' and referencia='$referencia';";
      $result=$fbConexaoVA->query($sql_sia);

      //alterar no WMS
      $sql_wms="update artigos set preco1=$p1, preco2=$p2, preco3=$p3, preco4=$p4, preco5=$p5, preco6=$p6 where referencia='$referencia';";
      $result_wms = sqlsrv_query($connWMS, $sql_wms);

      log1($connect, $_COOKIE['login'], "Altera Preços", $referencia, "p1=$p1, p2=$p2, p3=$p3, p4=$p4, p5=$p5, p6=$p6");

      echo "<h3>Preços alterados.</h3>";
    }
    ?>
  </div><!-- precos_form -->
</div><!-- precos_container -->

<?php include '../template/footer.php'; ?>

<script>
function validarFormulario() {
  // Obtenha o elemento do campo pelo ID
  const eref = document.getElementById('ref');
  const ref = eref.value;

  // Para verificar se o valor é uma string vazia (mais comum para campos de texto)
  if (ref === '') {
    alert('O campos Referência não pode estar vazio.');
    eref.focus(); // Coloca o foco de volta no campo
    return false; // Impede o envio do formulário
  }

  // Para verificar se o valor é null, undefined ou vazio de forma mais geral
  // Use !valorNome para verificar se o valor é "falso" (null, undefined, '', 0, false)
  if (!ref) {
    alert('O campo Referêmcoa não pode estar vazio.');
    eref.focus();
    return false;
  }
  
  // Continue com a validação de outros campos...
  return true; // Permite o envio do formulário se tudo estiver ok
}
</script>