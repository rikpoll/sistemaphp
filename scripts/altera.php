<?php
  foreach($retorno as $i) {
    $referencia=$i->REFERENCIA;
    $descricao=$i->DESIGNACAO1;
    $tipo=$i->COD_TIPO_ART;
    $familia=$i->COD_FAMILIA;
    $grupo=$i->COD_GRUPO;
    $marca=$i->COD_MARCA;
    $gama=$i->COD_VERSAO;
    $versao=$i->VERSAO;
    $preco1=$i->PRECO1;
    $preco2=$i->PRECO2;
    $preco3=$i->PRECO3;
    $preco4=$i->PRECO4;
    $preco5=$i->PRECO5;
    $preco6=$i->PRECO6;
    $precoCusto=$i->PRECO_CUSTO;
    $precoMedio=$i->CUSTO_MEDIO;
    $tecdoc=$i->CAMPO_USER1;
    $tecdocMarca=$i->CAMPO_USER3;
    $obsoleto=$i->ABSOLETO;
    $mono=$i->MONO;
    $suspenso=$i->SUSPENSA;
    $visivel=$i->CAMPO_USER2;
    $portal=$i->PORTALWEB;
    $movStock=$i->MOV_STOCKS;
    $ctrLoc=$i->CONTROLA_LOC;
		$descFin=$i->ISENTO_DESCFINANC; 
		$descCom=$i->ISENTO_DESCCOMER;
  }
?>

    <div class="resultado">
      <form method="POST">
        <input type="hidden" name="href" value="<?php echo $referencia; ?>">
        
        <label for="fref">Referência</label>
        <input type="text" id="fref" name="fref" value="<?php echo $referencia; ?>" size="30" disabled><br>
        
        <label for="fdesc">Descrição</label>
        <input type="text" id="fdesc" name="fdesc" size="75" value="<?php echo $descricao; ?>"><br><br>
        
        <label for="ffamilia">Família</label>
        <input type="text" id="ffamilia" name="ffamilia" value="<?php echo $familia; ?>" size="7">
        
        <label for="fgrupo">Grupo</label>
        <input type="text" id="fgrupo" name="fgrupo" value="<?php echo $grupo; ?>" size="7">
        
        <label for="ftipo">Tipo</label>
        <input type="text" id="ftipo" name="ftipo" value="<?php echo $tipo; ?>" size="7"><br><br>

        <label for="fmarca">Marca</label>
        <input type="text" id="fmarca" name="fmarca" value="<?php echo $marca; ?>" size="7">

        <label for="fgama">Gama</label>
        <input type="text" id="fgama" name="fgama" value="<?php echo $gama; ?>" size="7">

        <label for="fversao">Versão</label>
        <input type="text" id="fversao" name="fversao" value="<?php echo $versao; ?>" size="7"><br><br>

        <label for="fpreco1">Preço 1</label>
        <input type="number" step="0.001" id="fpreco1" name="fpreco1" value="<?php echo $preco1; ?>" size="7">

        <label for="fpreco2">Preço 2</label>
        <input type="number" step="0.001" id="fpreco2" name="fpreco2" value="<?php echo $preco2; ?>" size="7">

        <label for="fpreco3">Preço 3</label>
        <input type="number" step="0.001" id="fpreco3" name="fpreco3" value="<?php echo $preco3; ?>" size="7"><br>

        <label for="fpreco4">Preço 4</label>
        <input type="number" step="0.001" id="fpreco4" name="fpreco4" value="<?php echo $preco4; ?>" size="7">

        <label for="fpreco5">Preço 5</label>
        <input type="number" step="0.001" id="fpreco5" name="fpreco5" value="<?php echo $preco5; ?>" size="7">

        <label for="fpreco6">Preço 6</label>
        <input type="number" step="0.001" id="fpreco6" name="fpreco6" value="<?php echo $preco6; ?>" size="7"><br>

        <label for="fprecocusto">Preço de Custo</label>
        <input type="number" step="0.001" id="fprecocusto" name="fprecocusto" value="<?php echo $precoCusto; ?>" size="7">

        <label for="fcustomedio">Custo Médio</label>
        <input type="number" step="0.001" id="fcustomedio" name="fcustomedio" value="<?php echo $precoMedio; ?>" size="7" disabled><br><br>

        <label for="ftecdoc">TecDoc</label>
        <input type="text" id="ftecdoc" name="ftecdoc" value="<?php echo $tecdoc; ?>" size="30">

        <label for="ftecdocmarca">Marca</label>
        <input type="text" id="ftecdocmarca" name="ftecdocmarca" value="<?php echo $tecdocMarca; ?>" size="10"><br><br>

        <center>
        <table>
          <tr>
            <td>
              <label for="fobsoleto">Obsoleto</label>
              <input type="checkbox" id="fobsoleto" name="fobsoleto" <?php echo ($obsoleto=='S') ? 'checked' : ''; ?>><br>
              <label for="fsuspenso">Suspensa</label>
              <input type="checkbox" id="fsuspenso" name="fsuspenso" <?php echo ($suspenso=='S') ? 'checked' : ''; ?>><br>
              <label for="fmono">Mono</label>
              <input type="checkbox" id="fmono" name="fmono" <?php echo ($mono=='S') ? 'checked' : ''; ?>>
            </td>

            <td>
              <label for="fvisivel">Visível</label>
              <input type="checkbox" id="fvisivel" name="fvisivel" <?php echo ($visivel=='S') ? 'checked' : ''; ?>><br>
              <label for="fportal">PortalWeb</label>
              <input type="checkbox" id="fportal" name="fportal" <?php echo ($portal=='S') ? 'checked' : ''; ?>>
            </td>
            <td>
              <label for="fmovstock">Movimenta Stock</label>
              <input type="checkbox" id="fmovstock" name="fmovstock" <?php echo ($movStock=='S') ? 'checked' : ''; ?>><br>
              <label for="fctrlmov">Controla Movimentação</label>
              <input type="checkbox" id="fctrlmov" name="fctrlmov" <?php echo ($ctrLoc=='S') ? 'checked' : ''; ?>>
            </td>
						<td>
              <label for="fDescFin">Isento Desc. Fin.</label>
              <input type="checkbox" id="fDescFin" name="fDescFin" <?php echo ($descFin=='S') ? 'checked' : ''; ?>><br>
              <label for="fDescCom">Isento Desc. Com.</label>
              <input type="checkbox" id="fDescCom" name="fDescCom" <?php echo ($descCom=='S') ? 'checked' : ''; ?>>
            </td>
          </tr>
        </table>
        </center>

        <br><br>
        <input type="submit" value="Alterar" name="alterar">
        <input type="submit" value="Cancelar" name="cancelar">
      </form>
      
    </div>