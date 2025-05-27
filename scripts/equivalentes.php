<?php
$artigo=$_POST['freferencia'];
$LL=equivalente($fbConexaoLL,$artigo);
?>

<link rel="stylesheet" href="./equivalentes.css" type="text/css" />

<div class="equivalentes">

<h2>Artigos equivalentes para <span><?php echo $artigo; ?></span></h2>

<form method="POST">
  <input type="hidden" name="href" value="<?php echo $artigo; ?>">
  <table>
    <tr>
      <th>Equivalente</th>
      <th>Descrição</th>
      <th>+/-</th>
    </tr>
    <?php foreach($LL as $i) : ?>
      <tr>
        <td id="ref"><?php echo $i->REFERENCIA_EQUIVALENTE; ?></td>
        <td id="des"><?php echo $i->DESCRICAO_EQUIVALENTE; ?></td>
        <td id="op"><input type="submit" class="bRem" name="rem_equiv" value="<?php echo $i->REFERENCIA_EQUIVALENTE ?>" /></td>
      </tr>
    <?php endforeach; ?>
    <tr>
      <td><input type="text" name="equivalente" placeholder="Digite a Referência" onkeyup="this.value=this.value.toUpperCase()"/></td>
      <td></td> 
      <td id="op"><input type="submit" class="bAdic" name="add_equiv" value="add_equiv" /></td>
    </tr>
  </table>
</form>
