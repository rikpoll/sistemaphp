<?php
include '../config.php';
include '../banco.php';
$pag_atual="Meus Dados";
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

<?php
  die();
  endif; 
?>

<link rel="stylesheet" href="./configurar.css" type="text/css" />

<div class="principal">
  <h1>Configurações</h1>
  <div id="Utilizadores">
    <table id="myTable">
      <thead>
        <tr>
          <th>Id</th>
          <th>Login</th>
          <th>Nome</th>
          <th>Apelido</th>
          <th>Nome Completo</th>
          <th>Email</th>
          <th>Admin</th>
          <th>Opções</th>
        </tr>
      </thead>
      <tbody>
    <?php
      $users = mysqli_query($connect, "SELECT * FROM utilizadores where id>0");
      $rowid = -1;
      while($row = $users->fetch_array()) {
        $userId = $row['id'];
        $userLogin = $row['login'];
        $userNome = $row['nome'];
        $userApelido = $row['apelido'];
        $userNC = $row['nome_completo'];
        $userEmail = $row['email'];
        $acessSN = $row['acesso'];
        if ($acessSN==1) {
          $userAcesso = "Sim";
        } else {
          $userAcesso = "Nao";
        }
        $userPass = $row['pass'];
        
        echo "<tr><td>{$userId}</td>";
        echo "<td>{$userLogin}</td>";
        echo "<td>{$userNome}</td>";
        echo "<td>{$userApelido}</td>";
        echo "<td>{$userNC}</td>";
        echo "<td>{$userEmail}</td>";
        echo "<td>{$userAcesso}</td>";
        echo "<td>
                <button onclick=\"showOpt('pass', " . ++$rowid . ")\" title=\"Alterar Senha\"><img src=\"../img/redefinir-senha.png\"  style=\"max-widht:32px; max-height:32px;\"></button>
                <button onclick=\"showOpt('edita', " . $rowid . ")\" title=\"Alterar Dados\"><img src=\"../img/editar-dados.png\"  style=\"max-widht:32px; max-height:32px;\"></button>
                <button onclick=\"showOpt('opcoes', " . $rowid . ")\" title=\"Opções\"><img src=\"../img/opcoes.png\"  style=\"max-widht:32px; max-height:32px;\"></button>
              </td></tr>";
        echo "<tr style=\"visibility: collapse;\" class=\"pass\"><td colspan=\"8\"><div class=\"opt\">";
              include "configurar_pass.php";
        echo "</div></td></tr>"; 
        echo "<tr style=\"visibility: collapse;\" class=\"edita\"><td colspan=\"8\"><div class=\"opt\">";
              include 'configurar_dados.php';
        echo "</div></td></tr>"; 
        echo "<tr style=\"visibility: collapse;\" class=\"opcoes\"><td colspan=\"8\"><div class=\"opt\">";
              include 'configurar_opt.php';
        echo "</div></td></tr>"; 
      }
    ?>
      </tbody>
    </table>
  </div><!--Utilizadores-->
  <div id="Novo">
  </div><!--Novo-->
</div> <!--principal-->

<script>
  function showOpt(classe, id) {
    var rows = document.getElementsByClassName(classe);
    
    if (rows.length > id) {
      if (rows[id].style.visibility == "collapse") {
        fechar();
        rows[id].style.visibility = "visible";
      } else {
        rows[id].style.visibility = "collapse";
      }
    }
  }

  function fechar() {
    var rows = document.getElementsByTagName("tr");
    for (var i = 0; i < rows.length; i++) {
      if (rows[i].style.visibility == "visible") {
        rows[i].style.visibility = "collapse"
      }
    }
  }
</script>

<?php include '../template/footer.php'; ?>