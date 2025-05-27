<?php
  $artigo=$_POST['freferencia'];
  $assoc=associado_listar($fbConexaoLL,$artigo);
?>
<link rel="stylesheet" href="./associados.css" type="text/css" />
<div class="assoc">
<form method="POST"> 
<input type="hidden" name="href" value="<?php echo $artigo; ?>">
  <table>
    <tr>
      <th>Referência</th>
      <th>Descrição</th>
      <th>Qtd</th>
      <th>Taxa</th>
      <th>Valor</th>
      <th>+ / -</th>
    </tr>
  
  
  <?php
  foreach($assoc as $i) {
    printf('<tr>');
    printf('<td id="ref">%s</td>',$i->REFERENCIA_ASSOC);
    printf('<td id="des">%s</td>',$i->DESIGNACAO);
    printf('<td id="peq">%2.0f</td>',$i->QUANTIDADE);
    printf('<td id="peq">%s</td>',$i->E_TAXA);
    printf('<td id="peq">%2.3f</td>',$i->PRECO_TAXA);
    printf('<td id="peq"><input type="submit" class="bRem" name="rem_assoc" value="'.$i->REFERENCIA_ASSOC.'" /></td>');
    printf('</tr>');
  }
  ?>
 
    <tr>
      <td id="ref"><input type="text" placeholder="Digite a referencia" size=31 name="ref" onkeyup="this.value=this.value.toUpperCase()" /></td>
      <td id="des"></td>
      <td id="peq"><input type="number" value="1" name="qtd"/></td>
      <td id="peq"><input type="checkbox" name="taxa"/></td>
      <td id="peq"><input type="number" placeholder="$" name="valor"/></td>
      <td id="peq"><input type="submit" class="bAdic" name="add_assoc" value="add_assoc" /></td>
    </tr>
  </table>
</form>
<?php

?>
</div>