<link rel="stylesheet" href="./altera_produto.css" type="text/css" />

<?php
include '../config.php';
include '../banco.php';
$pag_atual="Alteração de Produtos";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 

log1($connect, $_COOKIE['login'], "Erro", "Alterar", "Erro de acesso.");

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
?>

<div class="geral">
  <div class="altera">
    <div class="pesquisa">
      <h2>Alteração de Dados de Produtos</h2>
      <hr>  
      <form method="POST">
        <label for="freferencia">Referência:</label>
        <input type="text" id="freferencia" name="freferencia" onkeyup="this.value=this.value.toUpperCase()" autofocus><br><br>
        <input type="submit" value="Alterar" name="pesquisar">
        <input type="submit" value="Ref. Equiv." name="equivalentes">
        <input type="submit" value="Produto Equiv." name="produto_equivalente">
        <input type="submit" value="Associados" name="associados">
        <input type="submit" value="Sincronizar" name="sincronizar">
        
				<!--Retirado, agora junto com a Consulta
				<input type="submit" value="Stocks" name="stocks"><br> -->
        
        <!-- a implantar
        <input type="submit" value="Histórico" name="historico">
        <input type="submit" value="Deletar" name="deletar"> -->
      </form>
      <hr>
    </div>

<?php
if (isset($_POST["alterar"])) {
  include 'atualizar.php';
} 

if(isset($_POST["add_assoc"])){
  $artigo=$_POST["href"];
  $ref_assoc=$_POST["ref"];
  $desig=existe($fbConexaoLL,$ref_assoc);
  foreach($desig as $i) {
    $desc=$i->DESIGNACAO1;
  }
  $qtd=($_POST["qtd"]==null) ? 1 : $_POST["qtd"];
  $taxa=(isset($_POST['taxa'])) ? 'S' : 'N';
  $valor=($_POST["valor"]==null) ? 'null' : $_POST["valor"];
  associado_inserir($fbConexaoLL, $artigo, $ref_assoc, $desc, $qtd, $taxa, $valor);
  print("Foi inserido o associado " . $ref_assoc . " ao artigo " . $artigo . ".<br><br>");
  $_POST["freferencia"]=$artigo;
  echo "<br>Associados de {$artigo}.";
  include 'associados.php';
}

if(isset($_POST["rem_assoc"])){
  $artigo=$_POST["href"];
  $ref_assoc=$_POST["rem_assoc"];
  associado_remover($fbConexaoLL, $artigo, $ref_assoc);
  print("Foi removido o associado " . $ref_assoc . " do artigo " . $artigo . ".<br><br>");
  $_POST["freferencia"]=$artigo;
  echo "<br>Associados de {$artigo}.";
  include 'associados.php';
}

if(isset($_POST["rem_equiv"])){
  $artigo=$_POST["href"];
  $ref_assoc=$_POST["rem_equiv"];
  equivalente_remove($fbConexaoLL, $artigo, $ref_assoc);
  print("Foi removido a referência equivalente " . $ref_assoc . " do artigo " . $artigo . ".<br><br>");
  $_POST["freferencia"]=$artigo;
  include 'equivalentes.php';
}

if(isset($_POST["add_equiv"])){
  $artigo=$_POST["href"];
  $ref_assoc=$_POST["equivalente"];
  $desig=existe($fbConexaoLL,$ref_assoc);
  foreach($desig as $i) {
    $desc=$i->DESIGNACAO1;
  }
  if (!isset($desc)){
    $desc='';
  } else {
    $desc=$desc;
  }
  equivalente_add($fbConexaoLL, $artigo, $ref_assoc, $desc);
  print("Foi adicionado a referência equivalente " . $ref_assoc . " ao artigo " . $artigo . ".<br><br>");
  $_POST["freferencia"]=$artigo;
  include 'equivalentes.php';
}

if(isset($_POST["rem_prod_equiv"])){
  $artigo=$_POST["href"];
  $ref_assoc=$_POST["rem_prod_equiv"];
  prod_equiv_rem($fbConexaoLL, $artigo, $ref_assoc);
  print("Foi removido o produto equivalente " . $ref_assoc . " do artigo " . $artigo . ".<br><br>");
  $_POST["freferencia"]=$artigo;
  include 'produto_equivalente.php';
}

if(isset($_POST["adic_prod_equiv"])){
  $artigo=$_POST["href"];
  $ref_assoc=$_POST["equivalente"];
  prod_equiv_add($fbConexaoLL, $artigo, $ref_assoc);
  print("Foi adicionado o Produto Equivalente " . $ref_assoc . " ao artigo " . $artigo . ".<br><br>");
  $_POST["freferencia"]=$artigo;
  include 'produto_equivalente.php';
}

if (isset($_POST['freferencia']) && $_POST['freferencia']!='' ) {
  $artigo=$_POST['freferencia'];
  $retorno=procura($fbConexaoLL, $artigo);
  if ($retorno<>0) {
    if (isset($_POST['pesquisar'])) {
    include 'altera.php';
    log1($connect, $_COOKIE['login'], "Alterar", "Alterar", $_POST['freferencia']);
    } elseif (isset($_POST["associados"])) {
      echo "Associados de {$artigo}.";
      log1($connect, $_COOKIE['login'], "Alterar", "Associados", $_POST['freferencia']);
      include 'associados.php';
    } elseif (isset($_POST["equivalentes"])) {
      include 'equivalentes.php';
      log1($connect, $_COOKIE['login'], "Alterar", "ReferenciasEquivalentes", $_POST['freferencia']);
    } elseif (isset($_POST["produto_equivalente"])) {
      log1($connect, $_COOKIE['login'], "Alterar", "Produtos Equivalentes", $_POST['freferencia']);
      include 'produto_equivalente.php';
    } elseif (isset($_POST["historico"])) {
      echo "Historico de {$artigo}.";
      log1($connect, $_COOKIE['login'], "Alterar", "Histórico", $_POST['freferencia']);
    } elseif (isset($_POST["stocks"])) {
      echo "Stocks de {$artigo}.";
      log1($connect, $_COOKIE['login'], "Alterar", "Stocks", $_POST['freferencia']);
      include 'stocks.php';
    } elseif (isset($_POST["sincronizar"])) {
      log1($connect, $_COOKIE['login'], "Sincronizar", $_POST['freferencia'], "Função Executada.");
      sync($fbConexaoLL, $artigo);
      echo "O artigo {$artigo} será sincronizado em até 10 minutos.<br>";
      echo "Você pode consultar a sincronização no menu <a href='existe.php'>Consultar Produtos</a>.<br><br>";
      echo "<h3>Digite uma referência.</h3>";
    } 
  } else {
    echo "<h3>Digite uma referência válida.</h3>";
    log1($connect, $_COOKIE['login'], "Pesquisa", $_POST['freferencia'], "Não encontrado.");
  }
} else {
  echo "<h3>Digite uma referência.</h3>";
  //log1($connect, $_COOKIE['login'], "Pesquisa", '', "Campo sem preencher.");
}
?>
      
  </div>
</div>

<?php include '../template/footer.php'; ?>