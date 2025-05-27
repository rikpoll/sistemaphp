<?php
  include '../config.php';
  include '../banco.php';
  $pag_atual='Acesso';
  include '../template/header.php';
?>
<link rel="stylesheet" href="../login/login.css" type="text/css">

<?php if (!isset($_POST['entrar'])) { ?>

<div class="form_login">
  <form method="POST">
    <label>User:</label> <input type='text' name='user' /><br>
    <label>Pass:</label> <input type='password' name='pass' /><br>
    <input type="submit" value='Entrar' name='entrar' />
  </form>
</div>

<?php
} else if (isset($_POST['entrar'])) {
    $login = $_POST['user'];
    $pass = md5($_POST['pass']);

    $verifica = mysqli_query($connect, "SELECT * FROM utilizadores WHERE login ='{$login}' and pass='{$pass}'") or die('Não funciona.');
    if (mysqli_num_rows($verifica)<=0) {
      print("Login ou Senha incorretos.");
      log1($connect, $login, "Login", "Erro", "Login ou Senha incorretos para {$login}.");
      die();
    } else {
      //tempo de 9 horas para todo o site
      setcookie('login', $login, time()+ 60*60*9, '/');
      log1($connect, $login, "Login", "Sistema", "Login efetuado com sucesso para {$login}.");
      header("Location:../index.php");
    }
  } else {
?>

<div class="form_login">
  <a href="../login/logoff.php">Logoff</a>
  <a href="../login/dados.php">Alterar meus dados</a>
</div>

<?php
}

include '../template/footer.php';
?>
