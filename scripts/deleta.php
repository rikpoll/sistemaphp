<link rel="stylesheet" href="./deleta.css" type="text/css" />
<?php
include_once '../config.php';
include_once '../banco.php';
$pag_atual="Delete Artigos";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, '', "Erro", $pag_atual, "Erro de acesso.");

?>

  <div class="deleta_container">
    <h2>Faça login.</h2>
    <p>Você não tem autorização para ver esse conteúdo.</p>
    <p>Faça <a href="../login/login.php">login</a> para aceder.<p>
    <p>Obrigado.</p>
  </div>


<?php die();
endif; 

log1($connect, $_COOKIE['login'], "Acesso", $pag_atual, "");

?>

<div class="deleta_container">
  <div class="pesquisa">
    <h2>Apagar Produtos</h2>
    <h3>Apagar produtos em todas as bases, caso seja possível.</h3>
    <hr />  
    <form method="POST" onsubmit="return validarFormulario()">
        <label for="freferencia">Referência:</label>
        <input type="text" id="referencia" name="freferencia" onkeyup="this.value=this.value.toUpperCase()" autofocus>
        <input type="submit" class="botao" value="Buscar" name="busca_ref"><br />
    </form>
  </div><!--pesquisa-->

  <?php
    if (isset($_POST["busca_ref"])) {
      $ref=$_POST["freferencia"];
      log1($connect, $_COOKIE['login'], "Pesquisa", $pag_atual, $ref);    
      $encontrado=[];
  ?>
  <h2>Apagar referencia <?php echo $ref; ?></h2>

  <div class="info_ref">

  <?php
      $refLL=procura( $fbConexaoLL, $ref );
      $refEM=procura( $fbConexaoEM, $ref );
      $refVA=procura( $fbConexaoVA, $ref );
      $refBP=procura( $fbConexaoBP, $ref );
      $refUNO=procura( $fbConexaoUNO, $ref );
      $refWMS=existe_wms( $connWMS, $ref );
      $refPortal=verificarRefPortal( $connPortal, $ref );
  ?>
    
  <?php
      //Leirilis
      echo "<div class=\"info_loja\">";
      echo "<p class=\"info_nome\">Leirilis&nbsp;";
      if ($refLL!=null) {
        echo '<img src="../img/v.jpg" alt="Possui cadastro" width=30 height=30></p>';
        foreach ( $refLL as $r ) {
          echo '<p class="info_desig">';
          if (strlen($r->DESIGNACAO1)>30) {
						  echo substr($r->DESIGNACAO1,0,30)."...";
          } else {
            echo $r->DESIGNACAO1;
          }
          echo '</p>';
          echo ($r->COD_FAMILIA==null) ? '<p>Fam</p>' : "<p>Fam: <strong>" . $r->COD_FAMILIA . "</strong></p>";
          echo ($r->COD_GRUPO==null) ? '<p>Grp</p>' : "<p>Grp: <strong>" . $r->COD_GRUPO . "</strong></p>";
          echo ($r->COD_MARCA==null) ? '<p>Mrc</p>' : "<p>Mrc: <strong>" . $r->COD_MARCA . "</strong></p>";
          printf('<p>1$ <strong>%5.3f</strong></p>',$r->PRECO1);
          printf('<p>C$ <strong>%5.3f</strong></p>',$r->PRECO_CUSTO);
          $encontrado[]='Leirilis';
        }
      } else {
        echo '<img src="../img/x.png" alt="Não possui cadastro" width=30 height=30>';
      }
      echo "</div> <!-- info_loja -->";

      //Empório
      echo "<div class=\"info_loja\">";
      echo "<p class=\"info_nome\">Empório&nbsp;";
      if ($refEM!=null) {
        echo '<img src="../img/v.jpg" alt="Possui cadastro" width=30 height=30></p>';
        foreach ( $refEM as $r ) {
          echo '<p class="info_desig">';
          if (strlen($r->DESIGNACAO1)>30) {
						  echo substr($r->DESIGNACAO1,0,30)."...";
          } else {
            echo $r->DESIGNACAO1;
          }
          echo '</p>';
          echo ($r->COD_FAMILIA==null) ? '<p>Fam</p>' : "<p>Fam: <strong>" . $r->COD_FAMILIA . "</strong></p>";
          echo ($r->COD_GRUPO==null) ? '<p>Grp</p>' : "<p>Grp: <strong>" . $r->COD_GRUPO . "</strong></p>";
          echo ($r->COD_MARCA==null) ? '<p>Mrc</p>' : "<p>Mrc: <strong>" . $r->COD_MARCA . "</strong></p>";
          printf('<p>1$ <strong>%5.3f</strong></p>',$r->PRECO1);
          printf('<p>C$ <strong>%5.3f</strong></p>',$r->PRECO_CUSTO);
          $encontrado[]='Empório';
        }
      } else {
        echo '<img src="../img/x.png" alt="Não possui cadastro" width=30 height=30>';
      }
      echo "</div> <!-- info_loja -->";

      //Varidauto
      echo "<div class=\"info_loja\">";
      echo "<p class=\"info_nome\">Varidauto&nbsp;";
      if ($refVA!=null) {
        echo '<img src="../img/v.jpg" alt="Possui cadastro" width=30 height=30></p>';
        foreach ( $refVA as $r ) {
          echo '<p class="info_desig">';
          if (strlen($r->DESIGNACAO1)>30) {
						  echo substr($r->DESIGNACAO1,0,30)."...";
          } else {
            echo $r->DESIGNACAO1;
          }
          echo '</p>';
          echo ($r->COD_FAMILIA==null) ? '<p>Fam</p>' : "<p>Fam: <strong>" . $r->COD_FAMILIA . "</strong></p>";
          echo ($r->COD_GRUPO==null) ? '<p>Grp</p>' : "<p>Grp: <strong>" . $r->COD_GRUPO . "</strong></p>";
          echo ($r->COD_MARCA==null) ? '<p>Mrc</p>' : "<p>Mrc: <strong>" . $r->COD_MARCA . "</strong></p>";
          printf('<p>1$ <strong>%5.3f</strong></p>',$r->PRECO1);
          printf('<p>C$ <strong>%5.3f</strong></p>',$r->PRECO_CUSTO);
          $encontrado[]='Varidauto';
        }
      } else {
        echo '<img src="../img/x.png" alt="Não possui cadastro" width=30 height=30>';
      }
      echo "</div> <!-- info_loja -->";

      //Biapeças
      echo "<div class=\"info_loja\">";
      echo "<p class=\"info_nome\">Biepeças&nbsp;";
      if ($refBP!=null) {
        echo '<img src="../img/v.jpg" alt="Possui cadastro" width=30 height=30></p>';
        foreach ( $refBP as $r ) {
          echo '<p class="info_desig">';
          if (strlen($r->DESIGNACAO1)>30) {
						  echo substr($r->DESIGNACAO1,0,30)."...";
          } else {
            echo $r->DESIGNACAO1;
          }
          echo '</p>';
          echo ($r->COD_FAMILIA==null) ? '<p>Fam</p>' : "<p>Fam: <strong>" . $r->COD_FAMILIA . "</strong></p>";
          echo ($r->COD_GRUPO==null) ? '<p>Grp</p>' : "<p>Grp: <strong>" . $r->COD_GRUPO . "</strong></p>";
          echo ($r->COD_MARCA==null) ? '<p>Mrc</p>' : "<p>Mrc: <strong>" . $r->COD_MARCA . "</strong></p>";
          printf('<p>1$ <strong>%5.3f</strong></p>',$r->PRECO1);
          printf('<p>C$ <strong>%5.3f</strong></p>',$r->PRECO_CUSTO);
          $encontrado[]='Biapeças';
        }
      } else {
        echo '<img src="../img/x.png" alt="Não possui cadastro" width=30 height=30>';
      }
      echo "</div> <!-- info_loja -->";

      //Uno
      echo "<div class=\"info_loja\">";
      echo "<p class=\"info_nome\">UNO&nbsp;";
      if ($refUNO!=null) {
        echo '<img src="../img/v.jpg" alt="Possui cadastro" width=30 height=30></p>';
        foreach ( $refUNO as $r ) {
          echo '<p class="info_desig">';
          if (strlen($r->DESIGNACAO1)>30) {
						  echo substr($r->DESIGNACAO1,0,30)."...";
          } else {
            echo $r->DESIGNACAO1;
          }
          echo '</p>';
          echo ($r->COD_FAMILIA==null) ? '<p>Fam</p>' : "<p>Fam: <strong>" . $r->COD_FAMILIA . "</strong></p>";
          echo ($r->COD_GRUPO==null) ? '<p>Grp</p>' : "<p>Grp: <strong>" . $r->COD_GRUPO . "</strong></p>";
          echo ($r->COD_MARCA==null) ? '<p>Mrc</p>' : "<p>Mrc: <strong>" . $r->COD_MARCA . "</strong></p>";
          printf('<p>1$ <strong>%5.3f</strong></p>',$r->PRECO1);
          printf('<p>C$ <strong>%5.3f</strong></p>',$r->PRECO_CUSTO);
          $encontrado[]='Uno';
        }
      } else {
        echo '<img src="../img/x.png" alt="Não possui cadastro" width=30 height=30>';
      }
      echo "</div> <!-- info_loja -->";

      //WMS
      echo "<div class=\"info_loja\">";
      echo "<p class=\"info_nome\">WMS&nbsp;";
      if ($refWMS!=null) {
        echo '<img src="../img/v.jpg" alt="Possui cadastro" width=30 height=30></p>';
        foreach ( $refWMS as $r ) {
          echo '<p class="info_desig">';
          if (strlen($r["designacao"])>30) {
						  echo substr($r["designacao"],0,30)."...";
          } else {
            echo $r["designacao"];
          }
          echo '</p>';
          echo ($r["cod_familia"]==null) ? '<p>Fam</p>' : "<p>Fam: <strong>" . $r["cod_familia"] . "</strong></p>";
          echo ($r["cod_grupo"]==null) ? '<p>Grp</p>' : "<p>Grp: <strong>" . $r["cod_grupo"] . "</strong></p>";
          echo ($r["cod_marca_leirilis"]==null) ? '<p>Mrc</p>' : "<p>Mrc: <strong>" . $r["cod_marca_leirilis"] . "</strong></p>";
          printf('<p>1$ <strong>%5.3f</strong></p>',$r["preco1"]);
          printf('<p>C$ <strong>%5.3f</strong></p>',$r["preco_custo"]);
          $encontrado[]='WMS';
        }
      } else {
        echo '<img src="../img/x.png" alt="Não possui cadastro" width=30 height=30>';
      }
      echo "</div> <!-- info_loja -->";

      //Portal
      echo "<div class=\"info_loja\">";
      echo "<p class=\"info_nome\">Portal&nbsp;";
      if ($refPortal!=null) {
        echo '<img src="../img/v.jpg" alt="Possui cadastro" width=30 height=30></p>';
        foreach ( $refPortal as $r ) {
          echo '<p class="info_desig">';
          if (strlen($r["ProductName"])>30) {
						  echo substr($r["ProductName"],0,30)."...";
          } else {
            echo $r["ProductName"];
          }
          echo '</p>';
          echo '<p>ItemID: <strong>' . $r["ItemId"] . '</strong></p>';
          echo '<p>Visível: <strong>' . ($r["Visible"]=='1' ? 'Sim' : 'Não') . '</strong></p>';
          echo '<p>Hidden: <strong>' . $r["Hidden"] . '</strong></p>';
          echo '<p>Disable: <strong>' . $r["Disable"] . '</strong></p>';
          $encontrado[]='Portal';
        }
      } else {
        echo '<img src="../img/x.png" alt="Não possui cadastro" width=30 height=30>';
      }
      echo "</div> <!-- info_loja -->";

    ?>
    </div> <!-- info_ref -->
    <div class="resultados">
    <?php
      $linha = count($encontrado);

      //echo "Linhas: <h1>$linha</h1>";
      if ($linha==1) {
        echo "Há <b>1</b> registro de $ref na loja <b>";
        
        foreach($encontrado as $e) {
          echo ($linha>1) ? "$e, " : $e;
          $linha--;
        }
        
        echo "</b></p>";
      } elseif ($linha>1) {
        echo "Há <b>$linha</b> registros para $ref, nas lojas <b>";
        
        foreach($encontrado as $e) {
          echo ($linha>1) ? "$e, " : $e;
          $linha--;
        }
        
        echo "</b></p>";
      } else {
        echo "Sem registros.";
      }

      

      if (count($encontrado)) {
    ?>

      <form method="POST">
        <input type="hidden" name="refr" value="<?php echo $ref; ?>">
        <input type="submit" class="botao" value="Deseja Apagar?" name="deleta">
      </form>

    <?php
      }
    }

    if (isset($_POST["deleta"])) {
      $ref=$_POST["refr"];
      
      try {
        $erro = [];
        $acerto = [];
        // Criar conexões com os bancos de dados

        //define("USER_SIA", "SYSDBA");
        //define("PASS_SIA", "2t6rXhgX");

        $LL = new PDO("firebird:dbname=192.168.6.15:D:\Alidata\Leirilis\Database\GIA.FDB", FB_USER, FB_PASS);
        $LL->setAttribute(PDO::ATTR_AUTOCOMMIT,0);
        
        $VA = new PDO("firebird:dbname=192.168.6.13:c:\Alidata\Varidauto\Database\GIA.FDB", FB_USER, FB_PASS);
        $VA->setAttribute(PDO::ATTR_AUTOCOMMIT,0);

        $EM = new PDO("firebird:dbname=192.168.6.13:c:\Alidata\Emporio\Database\GIA.FDB", FB_USER, FB_PASS);
        $EM->setAttribute(PDO::ATTR_AUTOCOMMIT,0);

        $BP = new PDO("firebird:dbname=192.168.6.13:c:\Alidata\Biapecas\Database\GIA.FDB", FB_USER, FB_PASS);
        $BP->setAttribute(PDO::ATTR_AUTOCOMMIT,0);
        
        $UNO = new PDO("firebird:dbname=192.168.6.15:d:\dp\database\uno.fdb", FB_USER, FB_PASS);
        $UNO->setAttribute(PDO::ATTR_AUTOCOMMIT,0);

        //$wms = new PDO("sqlsrv:Server=192.168.6.13,1435;Database=dp-wms", "svcportais", "RrfnjQx6yLh9ZT");
        //$portal = new PDO("sqlsrv:Server=ns3213222.ip-141-95-99.eu;Database=dp-store", "svcportais", "RrfnjQx6yLh9ZT");

        $sql = "delete from artigo where codigo_arm='2' and referencia='$ref'";

        try {
          $LL->beginTransaction();
          $result = $LL->query($sql);
          $acerto[]='Leirilis';
        } catch (PDOException $e) {
          $erro[]=['Leirilis'];
        }

        try {
          $VA->beginTransaction();
          $result = $VA->query($sql);
          $acerto[]='Varidauto';
        } catch (PDOException $e) {
          $erro[]=['Varidauto'];
        }

        try {
          $EM->beginTransaction();
          $result = $EM->query($sql);
          $acerto[]='Empório';
        } catch (PDOException $e) {
          $erro[]=['Empório'];
        }

        try {
          $BP->beginTransaction();
          $result = $BP->query($sql);
          $acerto[]='Biapeças';
        } catch (PDOException $e) {
          $erro[]=['Biapeças'];
        }

        try {
          $UNO->beginTransaction();
          $result = $UNO->query($sql);
          $acerto[]='UNO';
        } catch (PDOException $e) {
          $erro[]=['UNO'];
        }

        //WMS
        sqlsrv_begin_transaction( $connWMS );
        $sqlwms_equiv = "delete from ReferenciasEquivalentes where referencia='$ref'";
        $result_eq=sqlsrv_query($connWMS, $sqlwms_equiv);
        $sqlwms_artigo = "delete from Artigos where referencia='$ref'";
        $result=sqlsrv_query($connWMS, $sqlwms_artigo);

        if($result) {
          $acerto[]='WMS';
        } else {
          $erro[]=['WMS'];
        }

        //Portal
        sqlsrv_begin_transaction( $connPortal );
        $sqlportal_nb = "delete from NBrightBuy where itemid=(select ItemId from NBrightBuyIdx where ProductRef='$ref' and TypeCode='PRD')";
        $resultnb=sqlsrv_query($connPortal, $sqlportal_nb);
        $sqlportal_id = "delete from NBrightBuyIdx where ProductRef='$ref' and TypeCode='PRD'";
        $resultid=sqlsrv_query($connPortal, $sqlportal_id);

        if ($resultid) {
          $acerto[]='Portal';
        } else {
          $erro[]=['Portal'];
        }
        

        if (!$erro) {
          echo "<p>Foram excluidos registros de $ref nas bases <b>";
          
          $linha=count($acerto);
          foreach($acerto as $a) {
            echo ($linha > 1) ? "$a, " : $a;
            $linha--;
          }
          echo "</b></p>";
          
          log1($connect, $_COOKIE['login'], "Deleta", $pag_atual, "Referencia $ref apagada!");    
          
          $LL->commit();
          $VA->commit();
          $EM->commit();
          $BP->commit();
          $UNO->commit();
          sqlsrv_commit( $connWMS ); 
          sqlsrv_commit( $connPortal ); 
        } else {
          $LL->rollBack();
          $VA->rollBack();
          $EM->rollBack();
          $BP->rollBack();
          $UNO->rollBack();
          sqlsrv_rollback( $connWMS );
          sqlsrv_rollback( $connPortal );

          echo "<p>Os registros não puderam ser excluídos de $ref nas empresas: <b>";
          
          $linha=count($erro);
          foreach ($erro as $e) {
            echo ($linha > 1) ? "$e[0], " : "$e[0]";
            $linha--;
          }
          
          echo "</b></p>";

          log1($connect, $_COOKIE['login'], "Deleta", $pag_atual, "Referencia $ref não pode ser excluída!");    
        }
        
/*
//ib
if ($LL->inTransaction()) {
  echo "<h2>Em Transação</h2>";
} else {
  echo "<h2>Rodando sem trans</h2>";
}

//sqlserver
if ( sqlsrv_begin_transaction( $connWMS ) === false )  
{  
    echo "Could not begin transaction.\n";  
    die( print_r( sqlsrv_errors(), true ));  
}  else {
  echo "Transação Iniciada.<br>";
  $sql = "insert into artigos (pkid,referencia,ARMAZEM) values ('2-PDOTESTE','PDOTESTE','2')";
  $result=sqlsrv_query($connWMS, $sql);
  //$result->execute();
}*/
       
//        echo "<h2>Tamanho do erro:" . count($erro) . "</h2>";
  //      var_dump($erro);
      } catch (PDOException $e) {
        die("Erro de conexão com o banco de dados: " . $e->getMessage());
      }
    }
?>

  </div> <!-- resultados -->
</div><!-- deleta_container -->

<?php include '../template/footer.php'; ?>

<script>
function validarFormulario() {
  // Obtenha o elemento do campo pelo ID
  const freferencia = document.getElementById('referencia');
  const referencia = freferencia.value;

  // Para verificar se o valor é uma string vazia (mais comum para campos de texto)
  if (referencia === '') {
    alert('O campo "Referência" não pode estar vazio.');
    freferencia.focus(); // Coloca o foco de volta no campo
    return false; // Impede o envio do formulário
  }

  // Para verificar se o valor é null, undefined ou vazio de forma mais geral
  // Use !valorNome para verificar se o valor é "falso" (null, undefined, '', 0, false)
  if (!referencia) {
    alert('O campo "Referência" não pode estar vazio.');
    freferencia.focus();
    return false;
  }
  
  // Continue com a validação de outros campos...
  return true; // Permite o envio do formulário se tudo estiver ok
}
</script>