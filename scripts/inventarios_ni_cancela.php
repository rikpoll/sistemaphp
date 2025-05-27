<?php
include '../config.php';
include '../banco.php';
$pag_atual="Inventários Não Integrados";
include '../template/header.php';

if (!isset($_COOKIE['login'])) : 
  
  log1($connect, $_COOKIE['login'], "Erro", "Paletes", "Erro de acesso.");

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

log1($connect, $_COOKIE['login'], "Acesso", "Inventários", "Não Integrados");

?>

<link rel="stylesheet" href="./inventarios_nao_int.css" type="text/css" />

<div class="inventario">

	<div class="titulo">
	
	<?php
		//if(isset($_GET) {
		$loja=$_GET['loja'];
		switch ($loja) {
    case 'LL':
		?>
      <div class="listagem">
				<!--Inventário Leirilis-->
				<table>
					<caption>Inventários cancelados na Leirilis</caption>
					<thead>
						<tr>
							<th scope="col">Inventário</th>
							<th scope="col">Data</th>
							<th scope="col">Utilizador</th>
							<th scope="col">Descrição</th>
							<th scope="col">Linhas</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$result=inventarios_ni_lista($fbConexaoLL);
							foreach($result as $i) {
								log1($connect, $_COOKIE['login'], "Cancela", "Inventários", "{$i->CODIGO_INVENTARIO}-{$i->DESCRICAO}-{$i->DATA_PREPARACAO}");
								print("<tr>
										 <th scope=\"row\">{$i->CODIGO_INVENTARIO}</th>
										 <td id=\"cell_center\">{$i->DATA_PREPARACAO}</td>
										 <td id=\"cell_center\">{$i->UTILIZADOR}</td>
										 <td>{$i->DESCRICAO}</td>
										 <td id=\"cell_center\">{$i->QTD}</td>");
							}
							inventarios_ni_cancel($fbConexaoLL);
						?>
					</tbody>
				</table>
    <?php  
			break;
    case 'VA':
      ?>
      <div class="listagem">
				<!--Inventário Varidauto-->
				<table>
					<caption>Inventários cancelados na Varidauto</caption>
					<thead>
						<tr>
							<th scope="col">Inventário</th>
							<th scope="col">Data</th>
							<th scope="col">Utilizador</th>
							<th scope="col">Descrição</th>
							<th scope="col">Linhas</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$result=inventarios_ni_lista($fbConexaoVA);
							foreach($result as $i) {
								log1($connect, $_COOKIE['login'], "Cancela", "Inventários", "{$i->CODIGO_INVENTARIO}-{$i->DESCRICAO}-{$i->DATA_PREPARACAO}");
								print("<tr>
										 <th scope=\"row\">{$i->CODIGO_INVENTARIO}</th>
										 <td>{$i->DATA_PREPARACAO}</td>
										 <td>{$i->UTILIZADOR}</td>
										 <td>{$i->DESCRICAO}</td>
										 <td>{$i->QTD}</td>");
							}
							inventarios_ni_cancel($fbConexaoVA);
						?>
					</tbody>
				</table>
    <?php  
      break;
    case 'EM':
      ?>
      <div class="listagem">
				<!--Inventário Empório-->
				<table>
					<caption>Inventários cancelados na Empório</caption>
					<thead>
						<tr>
							<th scope="col">Inventário</th>
							<th scope="col">Data</th>
							<th scope="col">Utilizador</th>
							<th scope="col">Descrição</th>
							<th scope="col">Linhas</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$result=inventarios_ni_lista($fbConexaoEM);
							foreach($result as $i) {
								log1($connect, $_COOKIE['login'], "Cancela", "Inventários", "{$i->CODIGO_INVENTARIO}-{$i->DESCRICAO}-{$i->DATA_PREPARACAO}");
								print("<tr>
										 <th scope=\"row\">{$i->CODIGO_INVENTARIO}</th>
										 <td>{$i->DATA_PREPARACAO}</td>
										 <td>{$i->UTILIZADOR}</td>
										 <td>{$i->DESCRICAO}</td>
										 <td>{$i->QTD}</td>");
							}
							inventarios_ni_cancel($fbConexaoEM);
						?>
					</tbody>
				</table>
    <?php  
      break;
	  case 'BP':
			?>
      <div class="listagem">
				<!--Inventário Biapeças-->
				<table>
					<caption>Inventários cancelados na Biapeças</caption>
					<thead>
						<tr>
							<th scope="col">Inventário</th>
							<th scope="col">Data</th>
							<th scope="col">Utilizador</th>
							<th scope="col">Descrição</th>
							<th scope="col">Linhas</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$result=inventarios_ni_lista($fbConexaoBP);
							foreach($result as $i) {
								log1($connect, $_COOKIE['login'], "Cancela", "Inventários", "{$i->CODIGO_INVENTARIO}-{$i->DESCRICAO}-{$i->DATA_PREPARACAO}");
								print("<tr>
										 <th scope=\"row\">{$i->CODIGO_INVENTARIO}</th>
										 <td>{$i->DATA_PREPARACAO}</td>
										 <td>{$i->UTILIZADOR}</td>
										 <td>{$i->DESCRICAO}</td>
										 <td>{$i->QTD}</td>");
							}
							inventarios_ni_cancel($fbConexaoBP);
						?>
					</tbody>
				</table>
    <?php  
			break;
		}
	?>
  <a href="inventarios_nao_int.php">Voltar</a>	
	</div>

</div>
<?php include '../template/footer.php'; ?>
