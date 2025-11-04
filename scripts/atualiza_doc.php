<link rel="stylesheet" href="./atualiza_doc.css" type="text/css" />

<?php
include_once '../config.php';
include_once '../banco.php';
$pag_atual="Atualiza Documento";
include_once '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, "", "Erro", $pag_atual, "Erro de acesso.");

?>

<div class="container_atualiza">
  <h2>Faça login.</h2>
  <p>Você não tem autorização para ver esse conteúdo.</p>
  <p>Faça <a href="../login/login.php">login</a> para aceder.<p>
  <p>Obrigado.</p>
<div>

<?php
die();
endif; 

log1($connect, $_COOKIE['login'], "Acesso", $pag_atual, "Acesso liberado.");

?>

<div class="container_atualiza">

  <div class="escolha_doc">
  
    <h2><?php echo $pag_atual; ?></h2>
    
    <form method="POST" onsubmit="return validarFormulario()">
      <div class="procura_container">
        <div class="procura_ano">
          <label for="ano">Ano:</label>
          <select name="ano" id="ano">
            <option value="2023">2023</option>
            <option value="2024">2024</option>
            <option value="2025" selected>2025</option>
            <option value="2026">2026</option>
            <option value="2027">2027</option>
          </select>
        </div> <!-- procura_ano -->

        <div class="procura_diario">
          <label for="diario">Diário</label>
          <input type="text" id="diario" name="diario" placeholder="Digite a diário">
        </div> <!-- procura_diario -->

        <div class="procura_doc">
          <label for="doc">Número do Documento</label>
          <input type="text" id="doc" name="doc" placeholder="Número do documento">
        </div> <!-- procura_doc -->
      
        <input type="submit" class="botao" value="Pesquisar" name="pesquisar">
      </div> <!-- procura_container -->
    </form>
  </div> <!-- escolha_doc -->

  <div class="dados_doc_container">
  <?php
  if (isset($_POST["pesquisar"])) {
    $ano=$_POST["ano"];
    $diario=$_POST["diario"];
    $doc=$_POST["doc"];
    
    $sql_doc="select l.PKID_DOCUMENTO, l.GRUPO_DOC, l.PKID, l.num_doc, l.diario, l.ano, l.n_linha, l.campo_aux3 AlterarLocaL, l.localizacao, l.prod_ref, l.prod_desc, l.prod_qtd,
                     l.prod_qtd_liq, l.tem_qtd_liq, l.linha_facturada, l.doc_origem, l.estado, l.mov_stock, l.linha_associado--, COD_MORADA, DATA_ENTREGA, DATA_ENTREGA2
              from DocumentosLinhas  l
              where num_doc = $doc and
                    ano = $ano and
                    diario='$diario' ";

    $result_doc=sqlsrv_query($connWMS, $sql_doc);
    
    while($result = sqlsrv_fetch_array($result_doc, SQLSRV_FETCH_ASSOC)) {
      $doc_linhas[] = $result;
    }

    if (isset($doc_linhas)) {
      echo "<div class=\"table_doc\">";
      echo "<table class=\"doc_tabela\">";
      echo "<tr class=\"doc_tb_titulo\">
              <th>Linha</th>
              <th>A.L.</th>
              <th>Local</th>
              <th>Referência</th>
              <!-- th class=\"descricao\">Descrição</th -->
              <th>Qtd</th>
              <th>Qt Liq</th>
              <th>Liq</th>
              <th>Faturada</th>
              <th>DocOrigem</th>
              <th>Estado</th>
              <th>Caixa</th>
              <th>Qt</th>
              <th>Tipo</th>
            </tr>";

      foreach($doc_linhas as $d) {
        $pkid_documento=$d["PKID_DOCUMENTO"];
        $pkid_linha=$d["PKID"];
        $grupo_doc=$d["GRUPO_DOC"];
        $num_doc=$d["num_doc"];
        $diario=$d["diario"];
        $ano=$d["ano"];
        $n_linha=$d["n_linha"];
        $AlterarLocaL=$d["AlterarLocaL"];
        $localizacao=$d["localizacao"];
        $prod_ref=$d["prod_ref"];
        $prod_desc=$d["prod_desc"];
        $prod_qtd=$d["prod_qtd"];
        $prod_qtd_liq=$d["prod_qtd_liq"];
        $tem_qtd_liq=$d["tem_qtd_liq"];
        $linha_facturada=$d["linha_facturada"];
        $doc_origem=$d["doc_origem"];
        $estado=$d["estado"];
        $mov_stock=$d["mov_stock"];
        $linha_associado=$d["linha_associado"];

        echo "<tr class=\"doc_tb_linhas\">
                <td>$n_linha</td>
                <td>$AlterarLocaL</td>
                <td>$localizacao</td>
                <td>$prod_ref</td>
                <!--td class=\"descricao\">$prod_desc</td -->
                <td>$prod_qtd</td>
                <td>$prod_qtd_liq</td>
                <td>$tem_qtd_liq</td>
                <td>$linha_facturada</td>
                <td>$doc_origem</td>
                <td>$estado</td>";
              
        $sql_dt="select Localizacao, Referencia, Quantidade from Distribuicao where Pkid_DocumentoLinha = '$pkid_linha'";

        unset($doc_caixa);

        $result_dt=sqlsrv_query($connWMS, $sql_dt);
    
        while($result_cx = sqlsrv_fetch_array($result_dt, SQLSRV_FETCH_ASSOC)) {
          $doc_caixa[] = $result_cx;
        }

        if (isset($doc_caixa)) {
          foreach($doc_caixa as $dc) {
            $loc=$dc["Localizacao"];
            $locqt=$dc["Quantidade"];
            $refer=$dc["Referencia"];
            echo "<td>$loc</td><td>$locqt</td>";
          }
        } else {
          echo "<td></td><td></td>";
        }
        
        $sql_op="select case tipo when '*' then 'Expedição' when '+' then 'Arrumação' else 'OP' end tipo from Operacao where Pkid_LinhaDocumento='$pkid_linha'";

        unset($doc_op);
        
        $result_op=sqlsrv_query($connWMS, $sql_op);

        while($result_doc_op = sqlsrv_fetch_array($result_op, SQLSRV_FETCH_ASSOC)) {
          $doc_op[] = $result_doc_op;
        }

        if (isset($doc_op)) {
          foreach($doc_op as $dop) {
            echo "<td>" . $dop["tipo"] . "</td>";
          }
        } else {
          echo "<td></td>";
        }

        echo "</tr>";
      }
      echo "</table>";
      echo "</div>";

      echo "<br><h3>Documento: $pkid_documento</h3>";

      ?>
      <br>
      <div class="atualiza_form">
        <form method="POST">
          <input type="hidden" name="grupo_doc" value="<?php echo $grupo_doc; ?>">
          <input type="hidden" name="doc" value="<?php echo $pkid_documento; ?>">
          <input type="submit" class="botao" value="Atualizar/Sincronizar Documento" name="sync_doc"><br />
          <input type="submit" class="botao" value="Apagar Linhas e Sincronizar Novamente" name="sync_del"><br />
          <input type="submit" class="botao" value="Apagar Todo o Documento" name="del_doc"><br />
        </form>
      </div> <!-- atualiza_form -->
      <?php
    } else {
      echo "<h3>Documento " . $_POST["diario"] . "/" . $_POST["doc"] . " não encontrado.</h3>";
      echo "<h4>Deseja tentar a integração do documento?</h4>";
      ?>
      <form method="POST" onsubmit="return validarFormularioIntegra()">
        
        <label for="ano">Ano:</label>
        <input type="text" name="ano" value="<?php echo $ano; ?>">
        
        <label for="diario">Diario</label>
        <input type="text" name="diario" value="<?php echo $diario; ?>">
        
        <label for="doc">Número:</label>
        <input type="text" name="doc" value="<?php echo $doc; ?>">
        
        <label for="emp">Empresa:</label>
        <input type="text" id="emp" name="emp" placeholder="Número da Empresa">
        
        <input type="submit" class="botao" value="Tentar Integração" name="integrar"><br />
      </form>
    <?php
    }

  }

  if (isset($_POST["integrar"])) {
    $doc_ano=$_POST["ano"];
    $doc_diario=$_POST["diario"];
    $doc_num=$_POST["doc"];
    $doc_empresa=$_POST["emp"];

    if ($doc_empresa!='') {
      if (str_starts_with($doc_diario, 'EC')) {
        $doc_grupo = 'ENCC';
      } elseif (str_starts_with($doc_diario, 'EIC')) {
        $doc_grupo = 'ENCC';
      } elseif (str_starts_with($doc_diario, 'EF')) {
        $doc_grupo = 'ENCF';
      } elseif (str_starts_with($doc_diario, 'REF')) {
        $doc_grupo = 'ENCF';
      } elseif (str_starts_with($doc_diario, 'TA')) {
        $doc_grupo = 'GF';
      } elseif (str_starts_with($doc_diario, 'TL')) {
        $doc_grupo = 'GF';
      }

      $doc_pkid = $doc_ano . "_" . $doc_diario . "_" . $doc_num . "_" . $doc_empresa;

      $sql_sync_doc = "EXEC fill_documents @InGrupoDoc = N'$doc_grupo', @DaysDiff = 150,	@PkidDocumento = N'$doc_pkid'";
      try {
        $sync_doc=sqlsrv_query($connWMS, $sql_sync_doc);
        echo $sync_doc;      
        log1($connect, $_COOKIE['login'], "Integrar Documento", $pag_atual, "Documento $doc_ano $doc_diario/$doc_num integrado na empresa $doc_empresa");

        echo "<h3>Documento $doc_ano $doc_diario/$doc_num integrado na empresa $doc_empresa. Pkid: $doc_pkid - GrupoDoc: $doc_grupo.</h3>";
      } catch (Exception $e) {
        echo "<h3>Documento $doc_ano $doc_diario/$doc_num não integrado na empresa $doc_empresa. Pkid: $doc_pkid - GrupoDoc: $doc_grupo.</h3>";
        $e->getMessage();
      }
    } else {
      echo "<h3>Deves digitar o código da empresa.</h3>";
    }
  }

  if (isset($_POST["sync_doc"])) {
    $grupo_doc=$_POST["grupo_doc"];
    $doc_pk=$_POST["doc"];

    $sql_sync_doc = "EXEC fill_documents @InGrupoDoc = N'$grupo_doc', @DaysDiff = 150,	@PkidDocumento = N'$doc_pk'";
    $sync_doc=sqlsrv_query($connWMS, $sql_sync_doc);
          
    log1($connect, $_COOKIE['login'], "Atualiza Documento", $pag_atual, "Documento $doc_pk, do grupo $grupo_doc atualizado");

    echo "<h3>Documento $doc_pk, do grupo $grupo_doc atualizado.</h3>";
  }

  if (isset($_POST["sync_del"])) {
    $grupo_doc=$_POST["grupo_doc"];
    $doc_pk=$_POST["doc"];

    $sql_apaga_linhas = "delete from DocumentosLinhas where pkid_documento='$doc_pk'";
    $apaga_linhas=sqlsrv_query($connWMS, $sql_apaga_linhas);

    $sql_sync_doc = "EXEC fill_documents @InGrupoDoc = N'$grupo_doc', @DaysDiff = 150,	@PkidDocumento = N'$doc_pk'";
    $sync_doc=sqlsrv_query($connWMS, $sql_sync_doc);
          
    log1($connect, $_COOKIE['login'], "Atualiza Documento", $pag_atual, "Documento $doc_pk, do grupo $grupo_doc apagado e atualizado");

    echo "<h3>Documento $doc_pk, do grupo $grupo_doc apagado e atualizado.</h3>";
  }

  if (isset($_POST["del_doc"])) {
    $doc_pk=$_POST["doc"];

    $sql_deleta_linhas = "delete from DocumentosLinhas where pkid_documento='$doc_pk'";
    $deleta_linhas=sqlsrv_query($connWMS, $sql_deleta_linhas);

    $sql_deleta_doc = "delete from Documentos where pkid='$doc_pk'";
    $deleta_linhas=sqlsrv_query($connWMS, $sql_deleta_linhas);
          
    log1($connect, $_COOKIE['login'], "Apaga Documento", $pag_atual, "Documento $doc_pk apagado");

    echo "<h3>Documento $doc_pkap apagado.</h3>";
  }

  ?>
    
  </div> <!-- dados_doc_container -->
</div> <!-- container atualiza -->

<script>
function validarFormulario() {
  // Obtenha o elemento do campo pelo ID
  const ediario = document.getElementById('diario');
  const diario = ediario.value;

  const edoc = document.getElementById('doc');
  const doc = edoc.value;

  // Para verificar se o valor é uma string vazia (mais comum para campos de texto)
  if (diario === '') {
    alert('O diário não pode estar vazio.');
    ediario.focus(); // Coloca o foco de volta no campo
    return false; // Impede o envio do formulário
  }

  // Para verificar se o valor é null, undefined ou vazio de forma mais geral
  // Use !valorNome para verificar se o valor é "falso" (null, undefined, '', 0, false)
  if (!diario) {
    alert('O diário não pode estar vazio.');
    ediario.focus();
    return false;
  }

  if (doc === '') {
    alert('O documento não pode estar vazio.');
    edoc.focus(); // Coloca o foco de volta no campo
    return false; // Impede o envio do formulário
  }

  // Para verificar se o valor é null, undefined ou vazio de forma mais geral
  // Use !valorNome para verificar se o valor é "falso" (null, undefined, '', 0, false)
  if (!doc) {
    alert('O documento não pode estar vazio.');
    edoc.focus();
    return false;
  }
  
  // Continue com a validação de outros campos...
  return true; // Permite o envio do formulário se tudo estiver ok
}

function validarFormularioIntegra() {
  const eemp = document.getElementById('emp');
  const emp = eemp.value;

  if (emp === '') {
    alert('O campo Empresa não pode estar vazio.');
    eemp.focus();
    return false;
  }

  if (!emp) {
    alert('O campo Empresa não pode estar vazio.');
    eemp.focus();
    return false;
  } 

  return true;
}
</script>