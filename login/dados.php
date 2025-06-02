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

<link rel="stylesheet" href="./dados.css" type="text/css" />

<div class="meusdados">
	<form method="POST">
		<div class="painel">
			<div class="titulo">
				<p>Utilizador</p>
				<p>Nome</p>
				<p>Apelido</p>
				<p>Nome Completo</p>
				<p>Email</p>
				<p>Password</p>
				<p>Permissões</p>
			</div>
			
			<div class="valores">
			<?php
				$user = mysqli_query($connect, "SELECT * FROM utilizadores WHERE login ='{$_COOKIE['login']}'");
				if ($user->num_rows > 0) {
					$row = mysqli_fetch_array($user);
					$userId = $row['id'];
					$userLogin = $row['login'];
					$userNome = $row['nome'];
					$userApelido = $row['apelido'];
					$userNC = $row['nome_completo'];
					$userEmail = $row['email'];
					$userAcesso = $row['acesso'];
					$userPass = $row['pass'];
				}
				
				  $permissao_select= mysqli_query($connect, "SELECT GROUP_CONCAT(valor)p FROM config where user={$userId} and tipo='permissao'");
					$permissao_fetch = mysqli_fetch_array($permissao_select);
					$permissao_array = $permissao_fetch['p'];
					$permissoes=explode(',',$permissao_array);
					$alterar=array_search('alterar',$permissoes);
					$consultar=array_search('consultar',$permissoes);
					$vendas=array_search('vendas',$permissoes);
					$paletes=array_search('paletes',$permissoes);
					$inventario=array_search('inventario',$permissoes);
					$vendedor=array_search('vendedor',$permissoes);
					$tecdoc=array_search('tecdoc',$permissoes);
			?>
					<input type="hidden" name="id" value="<?php print($userId); ?>">
					<p><?php print($userLogin); ?></p>
					<p><input type="text" name="nome" value="<?php print($userNome); ?>"></p>
					<p><input type="text" name="apelido" value="<?php print($userApelido); ?>"></p>
					<p><input type="text" name="nc" value="<?php print($userNC); ?>"></p>
					<p><input type="text" name="mail" value="<?php print($userEmail); ?>"></p>
					<p><input type="password" name="pass" value="<?php print('********'); ?>"></p>
					<div class="permisso">
					<?php
						if ($alterar>-1) {
							echo "<p><a href=\"../scripts/altera_produto.php\">- Alterar Produtos</a></p>";
						} 
						
						if ($consultar>-1) {
							echo "<p><a href=\"../scripts/existe.php\">- Consultar Produtos</a></p>";
						}
						if ($vendas>-1) {
							echo "<p><a href=\"../scripts/vendas_cruzadas.php\">- Vendas Cruzadas</a></p>";
						}
						
						if ($paletes>-1) {
							echo "<p><a href=\"../scripts/palete_funchal.php\">- Criar Paletes Funchal</a></p>";
						}
						
						if ($inventario>-1) {
							echo "<p><a href=\"../scripts/inventarios_nao_int.php\">- Inventários Não Integrados</a></p>";
						}
						
						if ($vendedor>-1) {
							echo "<p><a href=\"../scripts/vendedor.php\">- Altera Vendedor</a></p>";
						}
						
						if ($tecdoc>-1) {
							echo "<p><a href=\"../scripts/tecdoc.php\">- TecDoc Duplicado</a></p>";
						}	
					?>
					</div>

				
			</div>
		</div>
		
		<input type="submit" name="alterar" value="Alterar">
	</form>
	<?php
		if (isset($_POST["alterar"])) {
			$id = $_POST["id"];
			$insNome = $_POST["nome"];
			$insApelido = $_POST["apelido"];
			$insNC = $_POST["nc"];
			$insEmail = $_POST["mail"];
			if ($_POST["pass"]!=='********' && $_POST["pass"]!=='') {
			  $insPass = md5($_POST["pass"]);
				$sql="update utilizadores set nome='{$insNome}', apelido='{$insApelido}', nome_completo='{$insNC}',
			                              email='{$insEmail}', pass='{$insPass}'
			        where id={$id}";
							header("Location:./dados.php?alterado=1");
		  } else {
				$sql="update utilizadores set nome='{$insNome}', apelido='{$insApelido}',
				                              nome_completo='{$insNC}', email='{$insEmail}'
			        where id={$id}";
							header("Location:./dados.php?alterado=1");
		  }
      $result=$connect->query($sql);
		} 
	?>
<?php if (isset($_GET['alterado'])) { ?>
	<div class="alterado">
		<p>Alterado com sucesso.</p>
	</div>
<?php } ?>
</div>

<?php include '../template/footer.php'; ?>