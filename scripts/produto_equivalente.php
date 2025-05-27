<?php
$artigo=$_POST['freferencia'];
$LL=prod_equiv_listar($fbConexaoLL,$artigo);
?>

<link rel="stylesheet" href="./produto_equivalente.css" type="text/css" />

<div class="produto_equivalente">
<h2>Produtos Equivalentes para <span><?php echo $artigo; ?></span></h2>

<form method="post">
  <input type="hidden" name="href" value="<?php echo $artigo; ?>">
  <table>
    <tr>
      <th>Produto Equiv.</th>
      <th>Descrição</th>
      <th>Preço</th>
      <th>+/-</th>
    </tr>

    <?php foreach($LL as $i) : ?>
      <tr>
        <td id="ref"><?php echo $i->REFERENCIA_EQUIV; ?></td>
        <td id="des"><?php echo $i->DESIGNACAO1; ?></td>
        <td id="val"><?php printf('%5.2f', $i->PRECO1); ?></td> 
        <td id="op"><input type="submit" class="bRem" name="rem_prod_equiv" value="<?php echo $i->REFERENCIA_EQUIV ?>" /></td>
      </tr>
    <?php endforeach; ?>

    <tr>
      <td id="ref"><input type="text" name="equivalente" placeholder="Digite a Referência" onkeyup="this.value=this.value.toUpperCase()" /></td>
      <td id="des"></td>
      <td id="val"></td> 
      <td id="op"><input type="submit" class="bAdic" name="adic_prod_equiv" value="adic_prod_equiv" /></td>
    </tr>
  </table>
</form>
</div>