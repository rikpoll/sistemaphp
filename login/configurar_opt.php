<?php
  $permissao_select= mysqli_query($connect, "SELECT GROUP_CONCAT(valor)p FROM config where user={$userId} and tipo='permissao'");
  $permissao_fetch = mysqli_fetch_array($permissao_select);
  $permissao_array = $permissao_fetch['p'];
  $permissoes=explode(',',$permissao_array);
  $alterarprod=array_search('alterar',$permissoes);
  $consultar=array_search('consultar',$permissoes);
  $vendas=array_search('vendas',$permissoes);
  $paletes=array_search('paletes',$permissoes);
  $inventario=array_search('inventario',$permissoes);
  $vendedor=array_search('vendedor',$permissoes);
  $tecdoc=array_search('tecdoc',$permissoes);
	$artigos_data=array_search('artigos_data',$permissoes);
	$cons_artigo=array_search('cons_artigo',$permissao);
?>

<div class="linha">
  <?php
    if ($userAcesso==1) {
      echo "<h2>Administrador.</h2>";
    } else {
  ?>

  <form method="POST">
    <input type="hidden" name="id" value="<?php print($userId); ?>" />
    <div class="seleciona">
      <input type="checkbox" name="alterarprod" value="alterarprod" <?php if ($alterarprod>-1) {echo "checked";} ?> /><label for="alterarprod">Alterar Produtos</label><br>
    </div>
    <div class="seleciona">
      <input type="checkbox" name="consultar" value="consultar" <?php if ($consultar>-1) {echo "checked";} ?> /><label for="consultar">Consultar Produtos</label><br>
    </div>
    <div class="seleciona">
      <input type="checkbox" name="vendas" value="vendas" <?php if ($vendas>-1) {echo "checked";} ?> /><label for="vendas">Vendas Cruzadas</label><br>
    </div>
    <div class="seleciona">
      <input type="checkbox" name="paletes" value="paletes" <?php if ($paletes>-1) {echo "checked";} ?> /><label for="paletes">Criar Paletes Funchal</label><br>
    </div>
    <div class="seleciona">
      <input type="checkbox" name="inventario" value="inventario" <?php if ($inventario>-1) {echo "checked";} ?> /><label for="inventario">Inventários Não Integrados</label><br>
    </div>
    <div class="seleciona">
          <input type="checkbox" name="vendedor" value="vendedor" <?php if ($vendedor>-1) {echo "checked";} ?> /><label for="vendedor">Alterar Vendedor</label><br>
    </div>
    <div class="seleciona">
      <input type="checkbox" name="tecdoc" value="tecdoc" <?php if ($tecdoc>-1) {echo "checked";} ?> /><label for="tecdoc">TecDoc Duplicado</label><br>
    </div>
		<div class="seleciona">
      <input type="checkbox" name="artigos_data" value="artigos_data" <?php if ($artigos_data>-1) {echo "checked";} ?> /><label for="artigos_data">Artigos Criados a Data</label><br>
    </div>
		<div class="seleciona">
      <input type="checkbox" name="cons_artigo" value="cons_artigo" <?php if ($cons_artigo>-1) {echo "checked";} ?> /><label for="cons_artigo">Consulta Artigos/Estoque</label><br>
    </div>
    <div class="seleciona">
      <input type="submit" name="alterarpermissao" class="input" value="Alterar">
    </div>
  </form>
  <?php } ?>
</div>

<?php
  if (isset($_POST['alterarpermissao'])) {
    $permissao_select= mysqli_query($connect, "SELECT GROUP_CONCAT(valor)p FROM config where user={$_POST['id']} and tipo='permissao'");
    $permissao_fetch = mysqli_fetch_array($permissao_select);
    $permissao_array = $permissao_fetch['p'];
    $permissoes=explode(',',$permissao_array);
    $alterarprod=array_search('alterar',$permissoes);
    $consultar=array_search('consultar',$permissoes);
    $vendas=array_search('vendas',$permissoes);
    $paletes=array_search('paletes',$permissoes);
    $inventario=array_search('inventario',$permissoes);
    $vendedor=array_search('vendedor',$permissoes);
    $tecdoc=array_search('tecdoc',$permissoes);
		$artigos_data=array_search('artigos_data',$permissoes);
		$cons_artigo=array_search('cons_artigo',$permissao);

    if ($alterarprod>-1) {   
      if (!isset($_POST['alterarprod'])) {
        $sql="delete from config where tipo='permissao' and user={$_POST['id']} and valor='alterar'";
        $result=$connect->query($sql);
      }
    } else {
      if (isset($_POST['alterarprod'])) {
          $sql="insert into config(tipo, user, valor) values('permissao', {$_POST['id']}, 'alterar')";
          $result=$connect->query($sql);
      } 
    }

    if ($consultar>-1) {   
      if (!isset($_POST['consultar'])) {
        $sql="delete from config where tipo='permissao' and user={$_POST['id']} and valor='consultar'";
        $result=$connect->query($sql);
      }
    } else {
      if (isset($_POST['consultar'])) {
          $sql="insert into config(tipo, user, valor) values('permissao', {$_POST['id']}, 'consultar')";
          $result=$connect->query($sql);
      } 
    }

    if ($vendas>-1) {   
      if (!isset($_POST['vendas'])) {
        $sql="delete from config where tipo='permissao' and user={$_POST['id']} and valor='vendas'";
        $result=$connect->query($sql);
      }
    } else {
      if (isset($_POST['vendas'])) {
          $sql="insert into config(tipo, user, valor) values('permissao', {$_POST['id']}, 'vendas')";
          $result=$connect->query($sql);
      } 
    }

    if ($paletes>-1) {   
      if (!isset($_POST['paletes'])) {
        $sql="delete from config where tipo='permissao' and user={$_POST['id']} and valor='paletes'";
        $result=$connect->query($sql);
      }
    } else {
      if (isset($_POST['paletes'])) {
          $sql="insert into config(tipo, user, valor) values('permissao', {$_POST['id']}, 'paletes')";
          $result=$connect->query($sql);
      } 
    }

     if ($inventario>-1) {   
      if (!isset($_POST['inventario'])) {
        $sql="delete from config where tipo='permissao' and user={$_POST['id']} and valor='inventario'";
        $result=$connect->query($sql);
      }
    } else {
      if (isset($_POST['inventario'])) {
          $sql="insert into config(tipo, user, valor) values('permissao', {$_POST['id']}, 'inventario')";
          $result=$connect->query($sql);
      } 
    }

     if ($vendedor>-1) {   
      if (!isset($_POST['vendedor'])) {
        $sql="delete from config where tipo='permissao' and user={$_POST['id']} and valor='vendedor'";
        $result=$connect->query($sql);
      }
    } else {
      if (isset($_POST['vendedor'])) {
          $sql="insert into config(tipo, user, valor) values('permissao', {$_POST['id']}, 'vendedor')";
          $result=$connect->query($sql);
      } 
    }

    if ($tecdoc>-1) {   
      if (!isset($_POST['tecdoc'])) {
        $sql="delete from config where tipo='permissao' and user={$_POST['id']} and valor='tecdoc'";
        $result=$connect->query($sql);
      }
    } else {
      if (isset($_POST['tecdoc'])) {
          $sql="insert into config(tipo, user, valor) values('permissao', {$_POST['id']}, 'tecdoc')";
          $result=$connect->query($sql);
      } 
    }
		
		if ($artigos_data>-1) {   
      if (!isset($_POST['artigos_data'])) {
        $sql="delete from config where tipo='permissao' and user={$_POST['id']} and valor='artigos_data'";
        $result=$connect->query($sql);
      }
    } else {
      if (isset($_POST['artigos_data'])) {
          $sql="insert into config(tipo, user, valor) values('permissao', {$_POST['id']}, 'artigos_data')";
          $result=$connect->query($sql);
      } 
    }
		
		if ($cons_artigo>-1) {   
      if (!isset($_POST['cons_artigo'])) {
        $sql="delete from config where tipo='permissao' and user={$_POST['id']} and valor='cons_artigo'";
        $result=$connect->query($sql);
      }
    } else {
      if (isset($_POST['cons_artigo'])) {
          $sql="insert into config(tipo, user, valor) values('permissao', {$_POST['id']}, 'cons_artigo')";
          $result=$connect->query($sql);
      } 
    }
  }
?>
