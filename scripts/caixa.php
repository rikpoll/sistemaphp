<link rel="stylesheet" href="./caixa.css" type="text/css" />

<?php
include_once '../config.php';
include_once '../banco.php';
$pag_atual="Caixas WMS";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, '', "Erro", $pag_atual, "Erro de acesso.");

?>

<div class="caixa_container">
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

<div class="caixa_container">
  <div class="pesquisa">
    <h2>Caixa WMS</h2>
    <h3>Apagar caixa e operação.</h3>
    <hr />  
    <form method="POST" onsubmit="return validarFormulario()">
      <label for="lojas">Caixa:</label>
      <select name="loja" id="lojas">
        <option value="">-- Escolha uma loja --</option>

        <optgroup label="Leirilis">
          <option value="2">Leiria (2)</option>
          <option value="3">Funchal (3)</option>
          <option value="4">Coimbra (4)</option>
          <option value="5">Lisboa (5)</option>
          <option value="6">Porto (6)</option>
          <option value="7">Braga (7)</option>
          <option value="8">Aveiro (8)</option>

        <optgroup label="Empório">
          <option value="10">Albufeira (10)</option>
          <option value="11">Faro (11)</option>
          <option value="13">Algoz (13)</option>

        <optgroup label="Varidauto">
          <option value="20">Aveiro (20)</option>
          <option value="21">Estarreja (21)</option>
        
        <optgroup label="Biapeças">
          <option value="30">Vila Flor (30)</option>
          <option value="31">Moncorvo (31)</option>

        <optgroup label="Techone">
          <option value="40">Leiria (40)</option>

      </select>
      <label for="caixa">Caixa:</label>
      <input type="number" id="caixa" name="caixa" autofocus>
      <br>
      <input type="submit" class="botao" value="Buscar Caixa" name="busca_caixa"><br />
    </form>
  </div><!--pesquisa-->

  <?php
    if (isset($_POST["loja"]) && isset($_POST["caixa"])) {
      $caixa = $_POST["loja"].".98.0.0.".$_POST["caixa"].".0";
      
      log1($connect, $_COOKIE['login'], "Pesquisa", $pag_atual, $caixa);    
  
      echo "<h2>Conteúdo da Caixa $caixa</h2>";

      $sql_dt = "select * from Distribuicao where Localizacao = '$caixa'";
      $resultdt=sqlsrv_query($connWMS, $sql_dt);

      while($result = sqlsrv_fetch_array($resultdt, SQLSRV_FETCH_ASSOC)) {
        $r[] = $result;
      }

      if (isset($r)) {
        echo "<table class=\"trtabela\"><tr class=\"trtitulo\"><th colspan=\"8\">Distribuição</th></tr><tr class=\"trcabeca\"><th>Arm</th><th>Referência</th><th>Quantidade</th><th>Estado</th><th>Doc Linha</th><th>Notas</th><th>Username</th><th>Data</th></tr>";
        foreach ($r as $i) {
          echo "<tr class=\"trdados\">";
          echo "<td>" . $i["ArmLocalizacao"] . "</td>";
          echo "<td>" . $i["Referencia"] . "</td>";
          echo "<td>" . $i["Quantidade"] . "</td>";
          echo "<td>" . $i["Estado"] . "</td>";
          echo "<td>" . $i["Pkid_DocumentoLinha"] . "</td>";
          echo "<td>" . $i["Notas"] . "</td>";
          echo "<td>" . $i["Username"] . "</td>";
          echo "<td>" . $i["Ult_Movimento"]->format("Y-m-d") . "</td>";
          echo "</tr>";
        }
        echo "</table>";
      } else {
        echo "<p>Caixa $caixa vazia na Distribuição.</p>";
      }

      $sql_op = "select * from Operacao where Pkid_Localizacao=(select distinct Pkid from Localizacoes where Identificacao='$caixa')";
      $resultop=sqlsrv_query($connWMS, $sql_op);
      
      while($result = sqlsrv_fetch_array($resultop, SQLSRV_FETCH_ASSOC)) {
        $o[] = $result;
      }

      if (isset($o)) {
        echo "<table class=\"trtabela\"><tr class=\"trtitulo\"><th colspan=\"9\">Operação</th></tr><tr class=\"trcabeca\"><th>Referência</th><th>Quantidade</th><th>Tipo</th><th>Username</th><th>Notas</th><th>Pkid_Documento</th><th>Pkid_LinhaDocumento</th><th>Data</th><th>Diário</th></tr>";
        foreach ($o as $i) {
          echo "<tr class=\"trdados\">";
          echo "<td>" . $i["Pkid_Artigo"] . "</td>";
          echo "<td>" . $i["Quantidade"] . "</td>";
          $tipo_cx = $i["Tipo"];
          switch ($tipo_cx) {
            case '*':
              $tipo_cx_des='Expedição';
              break;
            case '+':
              $tipo_cx_des='Arrumação';
              break;
            default:
              $tipo_cx_des=$tipo_cx;
          }
          echo "<td>" . $tipo_cx_des . "</td>";
          echo "<td>" . $i["Username"] . "</td>";
          echo "<td>" . $i["Notas"] . "</td>";
          echo "<td>" . $i["Pkid_Documento"] . "</td>";
          echo "<td>" . $i["Pkid_LinhaDocumento"] . "</td>";
          echo "<td>" . $i["Data"]->format("Y-m-d") . "</td>";
          echo "<td>" . $i["DiarioEntrada"] . "</td>";
          //echo "<td>" . $i["Pkid_Palete"] . "</td>";
          $palete = $i["Pkid_Palete"];
          echo "</tr>";
        }
        echo "</table>";
      } else {
        echo "<p>Caixa $caixa vazia na Operação.</p>";
      }

      $sql_palete_num="select distinct Pkid_Palete from Operacao where Pkid_Localizacao=(select distinct Pkid from Localizacoes where Identificacao='$caixa') and Pkid_Palete is not null";
      $result_plt_num=sqlsrv_query($connWMS, $sql_palete_num);

      while($result = sqlsrv_fetch_array($result_plt_num, SQLSRV_FETCH_ASSOC)) {
        $pn[] = $result;
      }

      if (isset($pn)) {
        foreach($pn as $i) {
          $palete=$i["Pkid_Palete"];
        }
        $sql_palete = "select * from Paletes where pkid=$palete";
        $result_plt=sqlsrv_query($connWMS, $sql_palete);
      
        while($result = sqlsrv_fetch_array($result_plt, SQLSRV_FETCH_ASSOC)) {
          $p[] = $result;
        }

        if (isset($p)) {
          echo "<table class=\"trtabela\"><tr class=\"trtitulo\"><th colspan=\"7\">Palete</th></tr><tr class=\"trcabeca\"><th>Palete</th><th>Cliente</th><th>Codigo</th><th>Activa</th><th>Data</th><th>TA</th><th>Guia</th></tr>";
          foreach ($p as $i) {
            echo "<tr class=\"trdados\">";
            echo "<td>" . $i["Pkid"] . "</td>";
            echo "<td>" . $i["NumeroCliente"] . "</td>";
            echo "<td>" . $i["Codigo"] . "</td>";

            $ativa = $i["Activa"];
            switch ($ativa) {
              case 0:
                $ativa_des='Fechada';
                break;
              case 1:
                $ativa_des='Aberta';
                break;
              default:
                $ativa_des=$tipo_cx;
            }

            echo "<td>" . $ativa_des . "</td>";
            echo "<td>" . $i["DataEntrada"]->format("Y-m-d") . "</td>";
            
            $AnoTA=$i["AnoTA"];
            $DiarioTA=$i["DiarioTA"];
            $NumTA=$i["NumTA"];
            $AnoGuia=$i["AnoGuia"];
            $DiarioGuia=$i["DiarioGuia"];
            $NumGuia=$i["NumGuia"];

            echo ($AnoTA<>0) ? "<td>$AnoTA $DiarioTA/$NumTA</td>" : "<td></td>";
            echo ($AnoGuia<>0) ? "<td>$AnoGuia $DiarioGuia/$NumGuia</td>" : "<td></td>";

            echo "</tr>";
          }
          echo "</table>";
        } else {
          echo "<p>Caixa $caixa vazia na Operação.</p>";
        }
      }
      
      

      $sql_lc = "select * from Localizacoes where Identificacao='$caixa'";
      $resultlc = sqlsrv_query($connWMS, $sql_lc);
      
      while($result = sqlsrv_fetch_array($resultlc, SQLSRV_FETCH_ASSOC)) {
        $l[] = $result;
      }

      if (isset($l)) {
        echo "<table class=\"trtabela\"><tr class=\"trtitulo\"><th colspan=\"9\">Localização</th></tr><tr class=\"trcabeca\"><th>Ordem</th><th>Zona</th><th>Armazém</th><th>Nível</th><th>Tipo</th><th>Arm. Dest.</th><th>Zona Destino</th><th>Piso Destino</th><th>Area</th></tr>";
        foreach ($l as $i) {
          echo "<tr class=\"trdados\">";
          echo "<td>" . $i["Ordem"] . "</td>";
          echo "<td>" . (isset($i["Pkid_Zona"]) ? $i["Pkid_Zona"] . " - " . $i["Zona"] : "") . "</td>";
          echo "<td>" . $i["Armazem"] . "</td>";
          echo "<td>" . $i["Nivel"] . "</td>";
          echo "<td>" . $i["Tipo"] . "</td>";
          echo "<td>" . $i["ArmazemDestino"] . "</td>";
          echo "<td>" . (isset($i["Pkid_ZonaDestino"]) ? $i["Pkid_ZonaDestino"] . " - " . $i["ZonaDestino"] : "") . "</td>";
          echo "<td>" . (isset($i["Pkid_PisoDestino"]) ? $i["Pkid_PisoDestino"] . " - " . $i["PisoDestino"] : "") . "</td>";
          echo "<td>" . (isset($i["Pkid_Area"]) ? $i["Pkid_Area"] . " - " . $i["Area"] : "") . "</td>";
          echo "</tr>";
        }
        echo "</table>";
      } else {
        echo "<p>Caixa $caixa não cadastrada.</p>";
      }

      if (isset($r) || isset($o)) {
      ?>
        <form method="POST">
          <input type="hidden" name="caixa" value="<?php echo $caixa; ?>">
          <input type="submit" class="botao" value="Apagar Caixa" name="apaga_caixa"><br />
          <?php if (!isset($o)) { ?>
            <input type="submit" class="botao" value="Criar Operação Expedição" name="cria_op_exp"><br />
            <input type="submit" class="botao" value="Criar Operação Arrumação" name="cria_op_arr"><br />
          <?php } else { ?>
            <input type="submit" class="botao" value="Liberar Caixa para Arrumação" name="libera_cx"><br />
          <?php } ?>
        </form>
      <?php
      
      }
    }

    if (isset($_POST["apaga_caixa"])) {
      $caixa = $_POST["caixa"];
      
      $sql_del_dt = "delete from Distribuicao where Localizacao = '$caixa'";
      $deldt=sqlsrv_query($connWMS, $sql_del_dt);

      $sql_del_op = "delete from Operacao where Pkid_Localizacao=(select distinct Pkid from Localizacoes where Identificacao='$caixa')";
      $delop=sqlsrv_query($connWMS, $sql_del_op);
            
      log1($connect, $_COOKIE['login'], "Deleta", $pag_atual, "Caixa $caixa apagada");

      echo "<h3>A caixa $caixa foi apagada.</h3>";
    }

    if (isset($_POST["libera_cx"])) {
      $caixa = $_POST["caixa"];
      
      $sql_libera_cx = "update operacao set DiarioEntrada='CGT' where DiarioEntrada is null and pkid_localizacao=(select distinct pkid_localizacao from distribuicao where localizacao='$caixa')";
      $libera_cx=sqlsrv_query($connWMS, $sql_libera_cx);
                  
      log1($connect, $_COOKIE['login'], "Libera Cx", $pag_atual, "Caixa $caixa liberada para arrumação");

      echo "<h3>A caixa $caixa foi liberada para arrumação.</h3>";
    }

    if (isset($_POST["cria_op_exp"])) {
      $caixa = $_POST["caixa"];
      
      $sql_dist = "select * from Distribuicao where Localizacao='$caixa'";
      $dados_dist_ex=sqlsrv_query($connWMS, $sql_dist);

      while($result = sqlsrv_fetch_array($dados_dist_ex, SQLSRV_FETCH_ASSOC)) {
        $dados_dist[] = $result;
      }

      if (isset($dados_dist)) {
        foreach ($dados_dist as $i) {
          $Armazem=$i["Armazem"];
          $Referencia=$i["Referencia"];
          $Pkid_Artigo=$i["Pkid_Artigo"];
          $Pkid_Localizacao=$i["Pkid_Localizacao"];
          $Quantidade=$i["Quantidade"];
          $Ult_Movimento=$i["Ult_Movimento"]->format("Y-m-d H:m:s.n");
          $Pkid_DocumentoLinha=$i["Pkid_DocumentoLinha"];
          $Username=$i["Username"];
        }

        $sql_doc = "select Pkid_Documento from DocumentosLinhas where pkid='$Pkid_DocumentoLinha'";
        $dados_doc_ex=sqlsrv_query($connWMS, $sql_doc);

        while($result = sqlsrv_fetch_array($dados_doc_ex, SQLSRV_FETCH_ASSOC)) {
          $dados_doc[] = $result;
        }

        if (isset($dados_doc)) {
          foreach ($dados_doc as $d) {
            $Pkid_Documento=$d["Pkid_Documento"];

            $sql_add_opr = "insert into Operacao (Pkid_Documento, Pkid_LinhaDocumento, Pkid_Artigo, Pkid_Localizacao, Quantidade, Tipo, Username, Data, Notas, Pkid_DocumentoLinha_Unlink) values ('$Pkid_Documento','$Pkid_DocumentoLinha','$Pkid_Artigo',$Pkid_Localizacao,$Quantidade,'*','$Username','$Ult_Movimento','WMS_add_separacao','$Pkid_DocumentoLinha')";
            $dados_add_op=sqlsrv_query($connWMS, $sql_add_opr);
          }

          
          echo "<h3>Operação criada para a caixa $caixa na expedição.</h3>";
          log1($connect, $_COOKIE['login'], "Cria operação", $pag_atual, "Operação criada para a caixa $caixa na expedição");
        } else {
          echo "<h3>Erro ao recuperar documento.</h3>";
          log1($connect, $_COOKIE['login'], "Cria operação", $pag_atual, "Erro ao criar operação para a caixa $caixa, docs");
        }

      } else {
        log1($connect, $_COOKIE['login'], "Cria operação", $pag_atual, "Erro ao criar operação para a caixa $caixa, dados");
        echo "<h3>Erro ao criar Operação para a Caixa $caixa!</h3>";
      }

      //$sql_del_op = "delete from Operacao where Pkid_Localizacao=(select distinct Pkid from Localizacoes where Identificacao='$caixa')";
      //$delop=sqlsrv_query($connWMS, $sql_del_op);
            
      //log1($connect, $_COOKIE['login'], "Deleta", $pag_atual, "Caixa $caixa apagada");

      //echo "<h3>caixa apagada: $caixa</h3>";
    }

    if (isset($_POST["cria_op_arr"])) {
      $caixa = $_POST["caixa"];
      
      $sql_dist = "select * from Distribuicao where Localizacao='$caixa'";
      $dados_dist_ex=sqlsrv_query($connWMS, $sql_dist);

      while($result = sqlsrv_fetch_array($dados_dist_ex, SQLSRV_FETCH_ASSOC)) {
        $dados_dist[] = $result;
      }

      if (isset($dados_dist)) {
        foreach ($dados_dist as $i) {
          $Armazem=$i["Armazem"];
          $Referencia=$i["Referencia"];
          $Pkid_Artigo=$i["Pkid_Artigo"];
          $Pkid_Localizacao=$i["Pkid_Localizacao"];
          $Quantidade=$i["Quantidade"];
          $Ult_Movimento=$i["Ult_Movimento"]->format("Y-m-d H:m:s.n");
          $Pkid_DocumentoLinha=$i["Pkid_DocumentoLinha"];
          $Username=$i["Username"];
          
          $sql_add_opr = "insert into Operacao (Pkid_Artigo, Pkid_Localizacao, Quantidade, Tipo, Username, Data, Notas, DiarioEntrada) values ('$Pkid_Artigo',$Pkid_Localizacao,$Quantidade,'+','$Username','$Ult_Movimento','WMS_add_entrada','CGT')";
          
          $dados_add_op=sqlsrv_query($connWMS, $sql_add_opr);
        }

        
        echo "<h3>Operação criada para a caixa $caixa na arrumação.</h3>";
        log1($connect, $_COOKIE['login'], "Cria operação", $pag_atual, "Operação criada para a caixa $caixa na arrumação");
      } else {
        log1($connect, $_COOKIE['login'], "Cria operação", $pag_atual, "Erro ao criar operação para a caixa $caixa, dados");
        echo "<h3>Erro ao criar Operação para a Caixa $caixa!</h3>";
      }
    }
  ?>

</div> <!-- caixa_container -->

<?php include '../template/footer.php'; ?>

<script>
function validarFormulario() {
  // Obtenha o elemento do campo pelo ID
  const elojas = document.getElementById('lojas');
  const loja = elojas.value;

  const ecaixa = document.getElementById('caixa');
  const caixa = ecaixa.value;

  // Para verificar se o valor é uma string vazia (mais comum para campos de texto)
  if (loja === '' || caixa === '') {
    alert('Os campos não podem estar vazios.');
    ecaixa.focus(); // Coloca o foco de volta no campo
    return false; // Impede o envio do formulário
  }

  // Para verificar se o valor é null, undefined ou vazio de forma mais geral
  // Use !valorNome para verificar se o valor é "falso" (null, undefined, '', 0, false)
  if (!loja || !caixa) {
    alert('Os campos não podem estar vazios.');
    ecaixa.focus();
    return false;
  }
  
  // Continue com a validação de outros campos...
  return true; // Permite o envio do formulário se tudo estiver ok
}
</script>