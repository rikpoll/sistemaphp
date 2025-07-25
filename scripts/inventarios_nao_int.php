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
		<h2>Inventários Não Integrados</h2>
		<h4>Listagem dos Inventários com problemas de integração para cancelamento, até ontem.</h4>
	</div>
	
	<div class="listagem">
	  <!--Inventário Leirilis-->
		<table>
			<caption>
				<?php
					$result=inventarios_ni_lista($fbConexaoLL);
					if($result==null) {
				?>
				Leirilis - Sem Resultados
			</caption>
		</table>
		<?php	} else { ?>
				Leirilis
			</caption>
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
				foreach($result as $i) {
          print("<tr>
                 <th scope=\"row\">{$i->CODIGO_INVENTARIO}</th>
                 <td id=\"cell_center\">{$i->DATA_PREPARACAO}</td>
                 <td id=\"cell_center\">{$i->UTILIZADOR}</td>
							   <td>{$i->DESCRICAO}</td>
                 <td id=\"cell_center\">{$i->QTD}</td>");
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th scope="row" colspan="5"><a href="inventarios_ni_cancela.php?loja=LL" onclick="return confirm('Tem a certeza que deseja cancelar os inventários?')">Cancelar</a></th>
				</tr>
			</tfoot>
		</table>
		<?php	} //encerra else do sem resultados ?>
		
		<!--Inventário Emporio-->
		<table>
			<caption>
				<?php
					$result=inventarios_ni_lista($fbConexaoEM);
					if($result==null) {
				?>
				Empório - Sem Resultados
			</caption>
		</table>
		<?php	} else { ?>
				Empório
			</caption>
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
				foreach($result as $i) {
          print("<tr>
                 <th scope=\"row\">{$i->CODIGO_INVENTARIO}</th>
                 <td id=\"cell_center\">{$i->DATA_PREPARACAO}</td>
                 <td id=\"cell_center\">{$i->UTILIZADOR}</td>
							   <td>{$i->DESCRICAO}</td>
                 <td id=\"cell_center\">{$i->QTD}</td>");
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th scope="row" colspan="5"><a href="inventarios_ni_cancela.php?loja=EM" onclick="return confirm('Tem a certeza que deseja cancelar os inventários?')">Cancelar</a></th>
				</tr>
			</tfoot>
		</table>
		<?php	} //encerra else do sem resultados ?>
		
		<!--Inventário Varidauto-->
		<table>
			<caption>
			  <?php
					$result=inventarios_ni_lista($fbConexaoVA);
					if($result==null) {
				?>
				Varidauto - Sem Resultados
			</caption>
		</table>
		<?php	} else { ?>
				Varidauto
			</caption>
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
          print("<tr>
                 <th scope=\"row\">{$i->CODIGO_INVENTARIO}</th>
                 <td id=\"cell_center\">{$i->DATA_PREPARACAO}</td>
                 <td id=\"cell_center\">{$i->UTILIZADOR}</td>
							   <td>{$i->DESCRICAO}</td>
                 <td id=\"cell_center\">{$i->QTD}</td>");
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th scope="row" colspan="5"><a href="inventarios_ni_cancela.php?loja=VA" onclick="return confirm('Tem a certeza que deseja cancelar os inventários?')">Cancelar</a></th>
				</tr>
			</tfoot>
		</table>
		<?php	} //encerra else do sem resultados ?>
		
		<!--Inventário Biapeças-->
		<table>
			<caption>
			 <?php
					$result=inventarios_ni_lista($fbConexaoBP);
					if($result==null) {
				?>
				Biapeças - Sem Resultados
			</caption>
		</table>
		<?php	} else { ?>
				Biapeças
			</caption>
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
          print("<tr>
                 <th scope=\"row\">{$i->CODIGO_INVENTARIO}</th>
                 <td id=\"cell_center\">{$i->DATA_PREPARACAO}</td>
                 <td id=\"cell_center\">{$i->UTILIZADOR}</td>
							   <td>{$i->DESCRICAO}</td>
                 <td id=\"cell_center\">{$i->QTD}</td>");
				}
			?>
			</tbody>
			<tfoot>
				<tr>
					<th scope="row" colspan="5"><a href="inventarios_ni_cancela.php?loja=BP" onclick="return confirm('Tem a certeza que deseja cancelar os inventários?')">Cancelar</a></th>
				</tr>
			</tfoot>
		</table>
		<?php	} //encerra else do sem resultados ?>
		
	</div>

</div>
<?php include '../template/footer.php'; ?>