<link rel="stylesheet" href="./busca.css" type="text/css" />

<?php
include_once '../config.php';
include_once '../banco.php';
$pag_atual="Procura Artigos";
include_once '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, "", "Erro", $pag_atual, "Erro de acesso.");

?>

<div class="busca">
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

<div class="busca">
  <h2><?php echo $pag_atual; ?></h2>
  <form method="POST">
    <div class="escolha">
      <input type="radio" id="contido" name="escolha" value="contido" checked>
      <label for="contido">Contido</label>
      <input type="radio" id="inicio" name="escolha" value="inicio">
      <label for="inicio">Começa com</label>
      <input type="radio" id="final" name="escolha" value="final">
      <label for="final">Termina com</label><br>
    </div> <!-- div escolha -->

    <div class="dados_container">
      <div class="dados">
        <label for="ref">Referência</label><br>
        <input type="text" id="ref" name="ref" placeholder="Digite a referência">
      </div> <!-- div dados -->

      <div class="dados">
        <label for="desc">Designação</label><br>
        <input type="text" id="desc" name="desc" placeholder="Digite a Designação">
      </div> <!-- div dados -->
    </div> <!-- div dados_container -->

    <input type="submit" class="botao" value="Pesquisar" name="pesquisar">
  </form>

  <div class="itens">
    
  <?php
  if (isset($_POST["pesquisar"])) {
    
    echo "<h1>Resultados</h1>";
    if (isset($_POST["ref"]) && $_POST["ref"]!='') {
      $ref=$_POST["ref"];
    }
    
    if (isset($_POST["desc"]) && $_POST["desc"]!='') {
      $desc=$_POST["desc"];
    }

    if (isset($ref) && isset($desc)) {
      $opt=1;
    } elseif (isset($ref) && !isset($desc) ) {
      $opt=2;
    } elseif (!isset($ref) && isset($desc) ) {
      $opt=3;
    } else {
      $opt=0;
    }

    if ($opt>0) {
      $escolha=$_POST["escolha"];
      $sql="select a.Referencia, a.Designacao, a.COD_FAMILIA Familia, a.COD_GRUPO Grupo, a.COD_MARCA_LEIRILIS Marca, a.REF_TECDOC +' | '+ a.COD_MARCA_TECDOC Tecdoc from Artigos a where ";
      switch ($escolha) {
        case 'contido':
          switch ($opt) {
            case 1:
              $sql.="DESIGNACAO like '%$desc%' and REFERENCIA like '%$ref%'";
              break;
            case 2:
              $sql.="REFERENCIA like '%$ref%'";
              break;
            case 3:
              $sql.="DESIGNACAO like '%$desc%'";
              break;
          }
          break;
        case 'inicio':
          switch ($opt) {
            case 1:
              $sql.="DESIGNACAO like '$desc%' and REFERENCIA like '$ref%'";
              break;
            case 2:
              $sql.="REFERENCIA like '$ref%'";
              break;
            case 3:
              $sql.="DESIGNACAO like '$desc%'";
              break;
            }
          break;
        case 'final':
          switch ($opt) {
            case 1:
              $sql.="DESIGNACAO like '%$desc' and REFERENCIA like '%$ref'";
              break;
            case 2:
              $sql.="REFERENCIA like '%$ref'";
              break;
            case 3:
              $sql.="DESIGNACAO like '%$desc'";
              break;
            }
          break;
        }
      $result = sqlsrv_query($connWMS, $sql);

      $dados = array();

      while($dado = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $dados[]=$dado;
      }

      echo "<h3>Total encontrado: " . count($dados) . "</h3>";

      echo "<div class=\"table-fixo\">";
      echo "<table class=\"resultados\" ";
      echo "<tr class=\"resultados_titulo\"><th>Referencia</th><th>Designacao</th><th>Família</th><th>Grupo</th><th>Marca</th><th>Tecdoc</th></tr>";
      foreach($dados as $d) {
        echo "<tr><td>";
        echo $d["Referencia"];
        echo "</td><td>";
        echo $d["Designacao"];
        echo "</td><td>";
        echo $d["Familia"];
        echo "</td><td>";
        echo $d["Grupo"];
        echo "</td><td>";
        echo $d["Marca"];
        echo "</td><td>";
        echo $d["Tecdoc"];
        echo "</td></tr>";
      }
      echo "</table>";
      echo "</div>";
    } else {
      echo "<h2>Digite um valor!</h2>";
    }
  }
  ?>
  </div> <!-- itens -->
</div> <!-- busca -->
<?php include_once '../template/footer.php'; ?>