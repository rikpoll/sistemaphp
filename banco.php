<?php
function log1($conn, $utilizador, $evento, $item, $obs) {
  
  $result = mysqli_query($conn, "SELECT id FROM utilizadores WHERE login ='{$utilizador}'");
  if ($result->num_rows > 0) {
    $row = mysqli_fetch_array($result);
    $userid = $row['id'];
  } else {
    $userid = 0;
  }

  $sql="insert into log (utilizador, evento, item, obs) values ({$userid}, '{$evento}', '{$item}', '{$obs}')";
  
  $result=$conn->query($sql);

}

function procura($conn, $ref) {
  
  $sql="select referencia, designacao1,
               cod_tipo_art, cod_familia, cod_grupo,
               cod_marca, cod_versao, versao,
               preco1, preco2, preco3, preco4, preco5, preco6, preco_custo, custo_medio,
               campo_user1, campo_user3,
               absoleto, mono, suspensa, campo_user2, portalweb, mov_stocks, controla_loc,
							 isento_descfinanc, isento_desccomer
        from artigo where codigo_arm='2' and referencia='" . $ref . "'";
  
  $resultot=$conn->prepare($sql);
  $resultot->execute();
  
  $total = $resultot->fetchAll();
  if (count($total)==0) {
    return 0;
  } else {
    $result=$conn->prepare($sql);
    $result->execute();
    $dados = array();
  
    foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
      $dados[] = $dado;
    }

    return $dados;
  }
}

function sync($conn, $ref) {
  $sql="update artigo set sync='N' where codigo_arm='2' and referencia='{$ref}'";

  $result=$conn->exec($sql);
}

function estoques($conn, $ref) {
  $sql="select s.cod_localizacao, s.stock_actual
        from gia_artigo_stocks s
        where s.referencia = '{$ref}' and
              s.stock_actual <> 0
				order by s.cod_localizacao";
  
  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function estoque_wms($conn, $ref, $loja) {
	switch ($loja) {
    case 'LL':
        $l1=2;
				$l2=9;
        break;
    case 'EM':
        $l1=10;
				$l2=19;
        break;
    case 'VA':
        $l1=20;
				$l2=29;
        break;
		 case 'BP':
        $l1=30;
				$l2=39;
        break;
}
	
	$sql="select d.Referencia, d.ArmLocalizacao, --d.Localizacao Localizacao, d.Quantidade Qtd,
							 case
									when d.armLocalizacao=2 then a.STOCK2
									when d.ArmLocalizacao=3 then a.STOCK3
									when d.ArmLocalizacao=4 then a.STOCK4
									when d.ArmLocalizacao=5 then a.STOCK5
									when d.ArmLocalizacao=6 then a.STOCK6
									when d.ArmLocalizacao=7 then a.STOCK7
									when d.ArmLocalizacao=8 then a.STOCK8
									when d.armLocalizacao=10 then a.STOCK10
									when d.armLocalizacao=11 then a.STOCK11
									when d.armLocalizacao=13 then a.STOCK13
									when d.armLocalizacao=20 then a.STOCK20
									when d.armLocalizacao=21 then a.STOCK21
									when d.armLocalizacao=30 then a.STOCK30
									when d.armLocalizacao=31 then a.STOCK31
							 end Stock,
							 string_agg(d.Localizacao + ' (' +cast(d.quantidade as varchar)+ ')', ',') Localizacao
				from Distribuicao d
				left join Artigos a on a.REFERENCIA=d.Referencia
				where d.referencia='{$ref}' and d.ArmLocalizacao between {$l1} and {$l2} and d.Estado='A'
				group by d.Referencia, d.ArmLocalizacao,
							 case
									when d.armLocalizacao=2 then a.STOCK2
									when d.ArmLocalizacao=3 then a.STOCK3
									when d.ArmLocalizacao=4 then a.STOCK4
									when d.ArmLocalizacao=5 then a.STOCK5
									when d.ArmLocalizacao=6 then a.STOCK6
									when d.ArmLocalizacao=7 then a.STOCK7
									when d.ArmLocalizacao=8 then a.STOCK8
									when d.armLocalizacao=10 then a.STOCK10
									when d.armLocalizacao=11 then a.STOCK11
									when d.armLocalizacao=13 then a.STOCK13
									when d.armLocalizacao=20 then a.STOCK20
									when d.armLocalizacao=21 then a.STOCK21
									when d.armLocalizacao=30 then a.STOCK30
									when d.armLocalizacao=31 then a.STOCK31
							 end
				order by len(ArmLocalizacao), ArmLocalizacao";
	
	$result = sqlsrv_query($conn, $sql);

  $dados = array();

  while($dado = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $dados[]=$dado;
  }

  return $dados;
}

function existe($conn, $ref) {
  $sql="select referencia, designacao1,
               cod_familia, cod_grupo, cod_marca,
               preco1, preco2, preco3, preco4, preco5,
               preco6, preco_custo, custo_medio, mov_stocks,
               LPAD(EXTRACT(DAY FROM dt_abertura), 2, '0') || '/' ||
               LPAD(EXTRACT(MONTH FROM dt_abertura), 2, '0') || '/' ||
               LPAD(EXTRACT(YEAR FROM dt_abertura), 4, '0') dt_abertura,
							 LPAD(EXTRACT(DAY FROM dt_ult_alteracao), 2, '0') || '/' ||
               LPAD(EXTRACT(MONTH FROM dt_ult_alteracao), 2, '0') || '/' ||
               LPAD(EXTRACT(YEAR FROM dt_ult_alteracao), 4, '0') dt_ult_alteracao
        from artigo where codigo_arm='2' and referencia='" . $ref . "'";
  
  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function existe_wms($conn, $ref) {
  $sql="select referencia, designacao,
               cod_familia, cod_grupo, cod_marca_leirilis,
               preco1, preco2, preco3, preco4, preco5,
               preco6, preco_custo, custo_medio
         from artigos where referencia ='" . $ref . "'";
  
  $result = sqlsrv_query($conn, $sql);

  $dados = array();

  while($dado = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $dados[]=$dado;
  }

  return $dados;
}

function associado_inserir($conn, $ref, $assoc, $desc, $qtd, $taxa, $valor) {
  $sql = "insert into artigo_associado(tipo, codigo_arm, referencia,
                                       codigo_arm_assoc, referencia_assoc,
                                       designacao,  quantidade, e_taxa, preco_taxa)
                                values('A','2','{$ref}','2','{$assoc}',
                                       '{$desc}',{$qtd},'{$taxa}',{$valor})";

  $result=$conn->exec($sql);
}

function associado_remover($conn, $ref, $assoc) {
  $sql = "delete from artigo_associado
          where codigo_arm='2' and referencia='{$ref}' and
                codigo_arm_assoc='2' and referencia_assoc='{$assoc}'";

  $result=$conn->exec($sql);
}

function associado_listar($conn, $ref) {
  $sql = "select * from artigo_associado where referencia = '{$ref}'";

  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function equivalente($conn, $ref) {
  $sql = "select referencia, referencia_equivalente, descricao_equivalente
          from gia_artigo_equivalente where referencia = '{$ref}'";

  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function equivalente_add($conn, $ref, $equiv, $desc) {
  $sql = "insert into gia_artigo_equivalente (referencia, referencia_equivalente, descricao_equivalente, qtd, cbarras)
                            values ('{$ref}','{$equiv}','{$desc}',1,'N')";

  $result=$conn->query($sql);
}

function equivalente_remove($conn, $ref, $equiv) {
  $sql = "delete from gia_artigo_equivalente where referencia = '{$ref}' and referencia_equivalente = '{$equiv}'";

  $result=$conn->query($sql);
}

function prod_equiv_listar($conn, $ref) {
  $sql = "select p.referencia_equiv, a.designacao1, a.preco1
          from gia_produto_equivalente p
          left join artigo a on a.codigo_arm=p.codigo_arm_equiv and a.referencia=p.referencia_equiv
          where p.referencia = '{$ref}'";

  $result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function prod_equiv_add($conn, $ref, $equiv) {
  $sql = "insert into gia_produto_equivalente(codigo_arm, referencia, codigo_arm_equiv, referencia_equiv)
                                       values('2','{$ref}','2','{$equiv}')";

  $result=$conn->query($sql);
}

function prod_equiv_rem($conn, $ref, $equiv) {
  $sql1 = "delete from gia_produto_equivalente where referencia='{$ref}' and referencia_equiv='{$equiv}'";
  $sql2 = "delete from gia_produto_equivalente where referencia='{$equiv}' and referencia_equiv='{$ref}'";

  $result=$conn->query($sql1);
  $result=$conn->query($sql2);
}

function palete_funchal_insere($conn, $tipo, $num) {
  $sqli="insert into Paletes (Pkid_Cliente, ContaCliente, NumeroCliente, CodMorada,
														 Codigo, DataEntrada, Activa, Volumes, Pkid_Colab, Username,
														 Tipo, AnoGuia, NumGuia, AnoTA, DiarioTA, NumTA,
														 CodMoradaOrigem, Pkid_MoradaOrigem, Pkid_Filial_Destino, EmpresaTA)
				output inserted.pkid
				values ('22111-761-1', '22111', 761, 0,
								'2025 " . $tipo . "03/" . $num . "', CURRENT_TIMESTAMP, 1, 1, 1, 'wms',
								1, 0, 0, 2025, '" . $tipo . "03', " . $num . ",
								3, '22111-761-1-3' , 2, 4)";
  
  $inserir = sqlsrv_query($conn, $sqli);

  $dados = array();

  while($dado = sqlsrv_fetch_array($inserir, SQLSRV_FETCH_ASSOC)) {
    $dados[]=$dado;
  }

  return $dados;
}

function inventarios_ni_lista($conn) {
	$sql="select i.codigo_inventario, i.data_preparacao, i.utilizador, i.descricao, count(o.num_linha)qtd
				from sia_inventario i
				left join sia_inventario_online o on o.codigo_inventario=i.codigo_inventario
				where i.integrado='N' and i.data_preparacao<current_date-1
				group by 1,2,3,4";
	
	$result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function inventarios_ni_cancel($conn) {
	$sql="update sia_inventario i set i.integrado='A',
                              i.codigo_anulacao='022',
                              i.abreviatura_anulacao='Anular Inventario',
                              i.motivo_anulacao='Anular Inventario',
                              i.data_hora_anulacao=current_timestamp,
                              i.utilizador_anulacao='WMS'
				where integrado='N' and data_preparacao<current_date-1";
	
	$result=$conn->query($sql);
}

function vendedor_busca($conn, $ano, $diario, $num) {
	$sql = "select ano, diario, num_doc numero, prod_ref referencia, cast(prod_qtd as int) qtd,
								 total_linha valor, penti_operador operador, coalesce(campo_aux20,'Sem Vendedor') nome
					from gia_fment_linhas
					where ano={$ano} and
								diario='{$diario}' and
								num_doc={$num}";
	
	$result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function vendedor_listaNomes ($conn) {
	$sql="select distinct campo_aux20 colab
				from gia_fment_linhas
				where ano=2025 and
							diario like 'VD%' and
							data_doc>current_date-5 and
							campo_aux20 not like '%@%'";
	
	$result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function vendedor_altera($conn,$ano, $diario, $num, $vendedor) {
	$sql="update gia_fment_linhas
				set campo_aux20='{$vendedor}'
				where ano={$ano} and
				diario='{$diario}' and
				num_doc={$num}";
	
	$result=$conn->query($sql);
}

function tecdoc_busca($conn, $cod) {
	if ($cod==null) {
		$sql="select a.campo_user1 Codigo, a.campo_user3 Marca, list(a.referencia) Refs
					from artigo a
					where a.codigo_arm='2' and
								a.campo_user1 is not null and
								a.campo_user1 <> '' and
								a.absoleto='N'
					group by 1,2
					having count(a.referencia) > 1";
	} else {
		$sql="select a.campo_user1 Codigo, a.campo_user3 Marca, list(a.referencia) Refs
					from artigo a
					where a.codigo_arm='2' and
								a.campo_user1='{$cod}' and
								a.absoleto='N'
					group by 1,2
					having count(a.referencia) > 1";
	}
	
	$result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}
?>