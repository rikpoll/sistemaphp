<?php
include '../config.php';
include '../banco.php';
$pag_atual="Vendas Cruzadas";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, $_COOKIE['login'], "Erro", "Vendas Cruzadas", "Erro de acesso.");

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

log1($connect, $_COOKIE['login'], "Acesso", "Vendas Cruzadas", "");

?>

<link rel="stylesheet" href="./vendas_cruzadas.css" type="text/css" />

<div class="vendas">
  <h2>Vendas Cruzadas</h2>
  <hr>
  <div class="dados">
    <form method="POST">
      <label for="empresa">Empresa:</label>
      <select name="empresa" id="empresa">
        <option value="LL">Leirilis</option>
        <option value="EM">Empório</option>
        <option value="VA">Varidauto</option>
        <option value="BP">Biapeças</option>
      </select>

      <label for="loja">Armazém:</label>
      <select name="loja" id="loja">
        <option value=1>Leirilis</option>
        <option value=3>Empório</option>
        <option value=5>Varidauto</option>
        <option value=6>Biapeças</option>
      </select>

      <br /><br />

      <label for="data1">Data Inicial:</label>
      <input type="date" name="data1" id="data1">
      <label for="data2">Data Final:</label>
      <input type="date" name="data2" id="data2">

      <br /><br />

      <input type="submit" name="buscar" value="Buscar">
    </form>
		<br /><br />
  </div>
  
  <div class="listar">
   
  <?php
  if (isset($_POST['buscar'])) {
    $loja = $_POST['loja'];
    $data1 = $_POST['data1'];
    $data2 = $_POST['data2'];
    $empresa = $_POST['empresa'];

    switch ($loja) {
      case 1:
        $lojas = "'2','3','4','5','6','7','8'";
        break;
      case 3:
        $lojas = "'10','11','13'";
        break;
      case 5:
        $lojas = "'20','21','22','29'";
        break;
      case 6:
        $lojas = "'30', '31'";
        break;
    }

    switch ($empresa) {
      case "LL":
        $result = vendas_cruzadas_listar($fbConexaoLL, $lojas, $data1, $data2);
        $emp_nome="Leirilis";
        break;
      case "VA":
        $result = vendas_cruzadas_listar($fbConexaoVA, $lojas, $data1, $data2);
        $emp_nome="Varidauto";
        break;
      case "EM":
        $result = vendas_cruzadas_listar($fbConexaoEM, $lojas, $data1, $data2);
        $emp_nome="Empório";
        break;
      case "BP":
        $result = vendas_cruzadas_listar($fbConexaoBP, $lojas, $data1, $data2);
        $emp_nome="Biapeças";
        break;
    }
    
    print("<table>
             <tr>
               <th>Empresa</th>
               <th>Entidade</th>
               <th>Contribuinte</th>
               <th>Encomenda</th>
							 <th>ECWT</th>
               <th>Data Enc</th>
               <th>Localização</th>
               <th>Fatura Cliente</th>
							 <th>Data Fat</th>
               <th>Fatura Fornecedor</th>
               <th>GTs</th>
               <th>GEA</th>
              </tr>");


    foreach($result as $i) {
      print("<tr>
               <td>{$emp_nome}</td>
               <td>".mb_convert_encoding($i->ENTI_NOME,"UTF-8","Windows-1252")."</td>
               <td>{$i->ENTI_CONTRIBUINTE}</td>
               <td>{$i->NUM_ENCOMENDA}</td>
							 <td>{$i->NUM_DOC}</td>
               <td>{$i->DATA_DOC}</td>
               <td>{$i->LOCALIZACAO}</td>");

			//verifica se o número da encomenda não está em branco
			if (!isset($i->NUM_ENCOMENDA)) {
				$num_enc='Sem Encomenda';
			} elseif ($i->NUM_ENCOMENDA=='') {
				$num_enc='Sem Encomenda';
			} else {
				$num_enc = $i->NUM_ENCOMENDA;
			}

      switch ($empresa) {
				case "LL":
					$fa = vendas_cruzadas_listar_fa($fbConexaoLL, $num_enc, $lojas);
					break;
				case "VA":
					$fa = vendas_cruzadas_listar_fa($fbConexaoVA, $num_enc, $lojas);					
					break;
				case "EM":
					$fa = vendas_cruzadas_listar_fa($fbConexaoEM, $num_enc, $lojas);
					break;
				case "BP":
					$fa = vendas_cruzadas_listar_fa($fbConexaoBP, $num_enc, $lojas);
					break;
      }

      //fatura
			$fa_data=$i->DATA_DOC;
			print("<td>");
			foreach($fa as $f) {
			  $fa_cli=explode(",",$f->FATURA);
				foreach ($fa_cli as $fl) {
					print ($fl . "<br/>");
				}
			  $fa_data=$f->DATA_DOC;
			}
			print ("</td>");
		  
			//data da faturação
			print("<td>{$fa_data}</td>");
			
			switch ($loja) {
        case "1":
					$faturas = vendas_cruzadas_fa($fbConexaoLL, $num_enc, $lojas);
          $gts = vendas_cruzadas_gt($fbConexaoLL, $i->ENTI_CONTRIBUINTE, $fa_data, $i->LOCALIZACAO);
          break;
        case "3":
          $faturas = vendas_cruzadas_fa($fbConexaoEM, $num_enc, $lojas);
          $gts = vendas_cruzadas_gt($fbConexaoEM, $i->ENTI_CONTRIBUINTE, $fa_data, $i->LOCALIZACAO);
          break;
        case "5":
          $faturas = vendas_cruzadas_fa($fbConexaoVA, $num_enc, $lojas);
          $gts = vendas_cruzadas_gt($fbConexaoVA, $i->ENTI_CONTRIBUINTE, $fa_data, $i->LOCALIZACAO);
          break;
        case "6":
          $faturas = vendas_cruzadas_fa($fbConexaoBP, $num_enc, $lojas);
          $gts = vendas_cruzadas_gt($fbConexaoBP, $i->ENTI_CONTRIBUINTE, $fa_data, $i->LOCALIZACAO);
          break;
      }

      //faturas fornecedor
			print("<td>");
			
			foreach($faturas as $f) {
        $faturas_todas = $f->FATURA;
        $faturas_separadas = explode(",", $faturas_todas);
        
        foreach($faturas_separadas as $fatura) {
          print($fatura."<br>");
	      }
      
      }
			print("</td>");

      foreach($gts as $g) {
				$gts = $g->GT;
				$gts_line = explode(",", $gts);
        print("<td>");
				foreach($gts_line as $gtl) {
					print($gtl. "<br>");
				}
				print("</td>");
      }
      
      //guias de entrada
			print("<td>");
			switch ($empresa) {
        case "LL":
          foreach($faturas_separadas as $f) {
            if (isset($f) && $f != "") {
              $geas=vendas_cruzadas_gea($fbConexaoLL, $f);
              foreach($geas as $ge) {
                print("{$ge->GEA}");
              }
						}
          }
          break;
        case "VA":
          foreach($faturas_separadas as $f) {
            if (isset($f) && $f != "") {
              $geas=vendas_cruzadas_gea($fbConexaoVA, $f);
              foreach($geas as $ge) {
                print("{$ge->GEA}");
              }
						}
          }
          break;
        case "EM":
          foreach($faturas_separadas as $f) {
            if (isset($f) && $f != "") {
              $geas=vendas_cruzadas_gea($fbConexaoEM, $f);
              foreach($geas as $ge) {
                print("{$ge->GEA}");
              }
						}
          }
          break;
        case "BP":
          foreach($faturas_separadas as $f) {
            if (isset($f) && $f != "") {
              $geas=vendas_cruzadas_gea($fbConexaoBP, $f);
              foreach($geas as $ge) {
                print("{$ge->GEA}");
              }
						}
          }
          break;
      }
			print("</td>");
    }
  }
  ?>
  </table> 
  </div>
</div>

<?php
function vendas_cruzadas_listar($conn, $loja, $data1, $data2) {
  $sql="select distinct d.num_doc, d.diario, d.ano, d.poca_enti, d.enti_nome,
               d.enti_contribuinte, d.num_encomenda, d.data_doc, l.localizacao
        from gia_fment d
        left join gia_fment_linhas l on l.num_doc=d.num_doc and l.diario=d.diario and l.ano=d.ano
        where d.diario starting 'ECW' and
              l.localizacao in ({$loja}) and
              d.data_doc between '{$data1}' and '{$data2}'";
  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function vendas_cruzadas_listar_fa($conn, $enc, $lojas) {
  $sql="select list (distinct (d.ano || ' ' || d.diario || '/' || d.num_doc), ', ') Fatura, d.data_doc
        from gia_fment d
        left join gia_fment_linhas l on l.num_doc=d.num_doc and l.diario=d.diario and l.ano=d.ano
        where (d.diario starting 'FA'  or
							 d.diario starting 'GT') and
				      l.localizacao in ({$lojas}) and
              d.num_encomenda = '{$enc}'
				group by 2";
  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function vendas_cruzadas_fa($conn, $enc, $lojas) {
  $sql="select list (distinct (f.ano || ' ' || f.diario || '/' || f.num_doc), ', ') Fatura
        from gia_fment f
        left join gia_fment_linhas l on l.num_doc=f.num_doc and l.diario=f.diario and l.ano=f.ano
        where f.num_encomenda = '{$enc}' and
              l.localizacao in ({$lojas}) and
              f.diario starting 'FA'";
  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function vendas_cruzadas_gt($conn, $nif, $data, $loc) {
  $sql="select list(distinct(c.ano || ' ' || c.diario || '/' || c.num_doc), ', ') GT
        from gia_fment c
				left join gia_fment_linhas l on l.num_doc=c.num_doc and l.diario=c.diario and l.ano=c.ano
        where c.enti_contribuinte = '{$nif}' and
              c.data_doc = '{$data}' and
							c.diario starting 'GTS' and
							l.localizacao='{$loc}'";
  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function vendas_cruzadas_gea($conn, $gea) {
  $sql="select list(g.ano || ' ' || g.diario || '/' || g.num_doc, ', ') GEA
        from gia_fment g
        where g.num_requisicao = '{$gea}'";
  
  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}
?>

<?php include '../template/footer.php'; ?>