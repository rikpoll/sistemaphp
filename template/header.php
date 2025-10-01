<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=100%, initial-scale=0.9">
  <title><?php echo ($pag_atual=='') ? 'Sistema' : 'Sistema - ' . $pag_atual; ?></title>
  <link rel="stylesheet" href="../template/header.css" type="text/css">
  <link rel="icon" href="../img/favicon.png" type="image/png">
</head>
<body>
<div class="container">
  <div class="fixo-direito">
    <div class="direito-interno">
      <img src="../img/logo.png" width="130px">
      <br>
      <?php if (isset($_COOKIE['login'])) : ?>
      <div class='dados_user'>
				<?php
					$sqli = mysqli_connect("localhost","root","", "dp-servicos");
					$user = mysqli_query($sqli, "SELECT * FROM utilizadores WHERE login ='{$_COOKIE['login']}'");
					if ($user->num_rows > 0) {
						$row = mysqli_fetch_array($user);
						$userId = $row['id'];
						$userLogin = $row['login'];
						$userNome = $row['nome'];
						$userApelido = $row['apelido'];
						$userNC = $row['nome_completo'];
						$userEmail = $row['email'];
						$userAcesso = $row['acesso'];
					}
					$direitos1= mysqli_query($sqli, "SELECT GROUP_CONCAT(valor)p FROM config where user={$userId} and tipo='permissao'");
					$direitos2 = mysqli_fetch_array($direitos1);
					$permisso = $direitos2['p'];
				?>
        <p>Bem vindo<br><span><?php echo $userNome; ?></span></p>
				<p><a href="../login/logoff.php">Logoff</a></p>
        <p><a href="../login/dados.php">Meus dados</a></p>
				
				<?php
					if ($userAcesso==1) {
						echo "<p><a style=\"color: red;\" href=\"../login/configurar.php\"><b>Configurações</b></a></p>";
					}
				?>
      </div>
      <hr width=90%>
      <div class="links">
			<?php
				$permissao=explode(',',$permisso);
				$alterar=array_search('alterar',$permissao);
				$consultar=array_search('consultar',$permissao);
				$vendas=array_search('vendas',$permissao);
				$paletes=array_search('paletes',$permissao);
				$inventario=array_search('inventario',$permissao);
				$vendedor=array_search('vendedor',$permissao);
				$tecdoc=array_search('tecdoc',$permissao);
				$artigos_data=array_search('artigos_data',$permissao);
				$cons_artigo=array_search('cons_artigo',$permissao);
				$pendentes=array_search('pendentes',$permissao);
				$sync_equiv=array_search('equivalentes',$permissao);
				$busca=array_search('busca',$permissao);
        $deleta=array_search('deleta',$permissao);
        $caixa=array_search('caixa',$permissao);
				
				if ($alterar>-1 or $userAcesso==1) {
			    echo "<p><a href=\"../scripts/altera_produto.php\">Alterar Produtos</a></p>";
				} 
				
				if ($consultar>-1 or $userAcesso==1) {
          echo "<p><a href=\"../scripts/existe.php\">Consultar Produtos</a></p>";
				}
				
				if ($vendas>-1 or $userAcesso==1) {
          echo "<p><a href=\"../scripts/vendas_cruzadas.php\">Vendas Cruzadas</a></p>";
				}
				
				if ($paletes>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/palete_funchal.php\">Criar Paletes Funchal</a></p>";
				}
				
				if ($inventario>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/inventarios_nao_int.php\">Inventários</a></p>";
				}
				
				if ($vendedor>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/vendedor.php\">Altera Vendedor</a></p>";
				}
				
				if ($tecdoc>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/tecdoc.php\">TecDoc Duplicado</a></p>";
				}
				if ($artigos_data>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/artigos_criados.php\">Artigos a Data</a></p>";
				}				
				if ($cons_artigo>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/consultaArtigo.php\">Consulta Art/Estoque</a></p>";
				}	
				if ($pendentes>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/pendentes.php\">Sync Pendentes</a></p>";
				}						
				if ($sync_equiv>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/prod_equiv_sync.php\">Sync Equivalentes</a></p>";
				}
				if ($busca>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/busca.php\">Procurar Artigos</a></p>";
				}	
        if ($deleta>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/deleta.php\">Apagar Artigos</a></p>";
				}		
        if ($caixa>-1 or $userAcesso==1) {
				  echo "<p><a href=\"../scripts/caixa.php\">Caixas WMS</a></p>";
				}		
			?>
      </div>
      <?php else : ?>
        <a href="../login/login.php">Login</a>
      <?php endif; ?>
    </div>
  </div>
