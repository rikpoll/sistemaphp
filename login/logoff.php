<?php
include '../config.php';
include '../banco.php';
$pag_atual="Logoff";

  log1($connect, $login, "Logoff", "Sistema", "Logoff efetuado com sucesso para {$login}.");
	setcookie('login', '', time()-1000, '/');
  header("Location:../index.php");