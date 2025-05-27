<?php

if (isset($_POST["alterar"])) {
  $referencia=$_POST['href'];
  $descricao=$_POST['fdesc'];
  $tipo=$_POST['ftipo'];
  $familia=$_POST['ffamilia'];
  $grupo=$_POST['fgrupo'];
  $marca=$_POST['fmarca'];
  $gama=$_POST['fgama'];
  $versao=$_POST['fversao'];
  $preco1=$_POST['fpreco1'];
  $preco2=$_POST['fpreco2'];
  $preco3=$_POST['fpreco3'];
  $preco4=$_POST['fpreco4'];
  $preco5=$_POST['fpreco5'];
  $preco6=$_POST['fpreco6'];
  $precoCusto=$_POST['fprecocusto'];
  $tecdoc=$_POST['ftecdoc'];
  $tecdocMarca=$_POST['ftecdocmarca'];
  $obsoleto=(isset($_POST['fobsoleto'])) ? 'S' : 'N';
  $mono=(isset($_POST['fmono']))? 'S' : 'N';
  $suspenso=(isset($_POST['fsuspenso'])) ? 'S' : 'N';
  $visivel=(isset($_POST['fvisivel'])) ? 'S' : 'N';
  $movStock=(isset($_POST['fmovstock'])) ? 'S' : 'N';
  $portal=(isset($_POST['fportal'])) ? 'S' : 'N';
  $ctrLoc=(isset($_POST['fctrlmov'])) ? 'S' : 'N';
	$descFin=(isset($_POST['fDescFin'])) ? 'S' : 'N';
	$descCom=(isset($_POST['fDescCom'])) ? 'S' : 'N';
  
  $sql="update artigo set 
          designacao1='{$descricao}',
          cod_tipo_art='{$tipo}',
          cod_familia='{$familia}',
          cod_grupo='{$grupo}',
          cod_marca='{$marca}', 
          cod_versao='{$gama}', 
          versao='{$versao}',
          preco1={$preco1}, 
          preco2={$preco2}, 
          preco3={$preco3}, 
          preco4={$preco4}, 
          preco5={$preco5}, 
          preco6={$preco6}, 
          preco_custo={$precoCusto}, 
          campo_user1='{$tecdoc}', 
          campo_user3='{$tecdocMarca}',
          absoleto='{$obsoleto}', 
          mono='{$mono}', 
          suspensa='{$suspenso}', 
          campo_user2='{$visivel}', 
          portalweb='{$portal}', 
          mov_stocks='{$movStock}', 
          controla_loc='{$ctrLoc}',
					isento_descfinanc='{$descFin}',
					isento_desccomer='{$descCom}',
					sync='N'
        where codigo_arm='2' and referencia='{$referencia}'";
  
  try {
    //alterando no SIA
    $fbConexaoLL->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $fbConexaoLL->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
    $fbConexaoLL->beginTransaction();
    
    $result=$fbConexaoLL->exec($sql);

    //alterando no WMS
    $sqlwms = "update artigos set 
                 designacao='{$descricao}',
                 cod_tipo='{$tipo}',
                 cod_familia='{$familia}',
                 cod_grupo='{$grupo}',
                 cod_marca_leirilis='{$marca}', 
                 preco1={$preco1}, 
                 preco2={$preco2}, 
                 preco3={$preco3}, 
                 preco4={$preco4}, 
                 preco5={$preco5}, 
                 preco6={$preco6}, 
                 preco_custo={$precoCusto}, 
                 ref_tecdoc='{$tecdoc}', 
                 cod_marca_tecdoc='{$tecdocMarca}',
                 obsoleto='{$obsoleto}', 
                 mono='{$mono}', 
                 suspenso='{$suspenso}', 
                 portalweb='{$portal}'
               where armazem='2' and referencia='{$referencia}'";
    
    $result = sqlsrv_query($connWMS, $sqlwms);
    
    if($result === false) {
      $fbConexaoLL->rollBack();
      echo "<h5>O artigo " . $referencia . " não pode ser alterado.<br>";
      echo "Verifique os dados e tente novamente.</h5>";
			log1($connect, $_COOKIE['login'], "Erro - Artigo Não Alterado", $referencia, "Erro");
      print_r(sqlsrv_errors(), true);
    } else {
      $fbConexaoLL->commit();
      echo "<h5>Artigo " . $referencia . " alterado no SIA e no WMS.";
      log1($connect, $_COOKIE['login'], "Artigo Alterado", $referencia, "");
    }

  } catch (Exception $e) {
    echo "<h5>O artigo " . $referencia . " não pode ser alterado.<br>";
    echo "Verifique os dados e tente novamente.</h5>";
    $fbConexaoLL->rollBack();
    log1($connect, $_COOKIE['login'], "Alterar", $referencia, "Erro ao alterar o artigo.");
  }



}
?>
