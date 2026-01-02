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
               LPAD(EXTRACT(YEAR FROM dt_ult_alteracao), 4, '0') dt_ult_alteracao,
							 absoleto, mono, cod_situacao, tempo_entrega
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

function vendedor_busca_cliente($conn, $ano, $diario, $num) {
	$sql = "select poca_enti, enti_nome
					from gia_fment
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
							data_doc>current_date-30 and
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
		$sql="select a.campo_user1 Codigo, a.campo_user3 Marca,
                case campo_user3
                     when '2' then 'HELLA'
										when '3' then 'ATE'
										when '4' then 'MANN-FILTER'
										when '5' then 'PIERBURG'
										when '6' then 'Schaeffler LuK'
										when '9' then 'VICTOR REINZ'
										when '10' then 'ELRING'
										when '11' then 'BorgWarner (BERU)'
										when '15' then 'NGK'
										when '16' then 'BILSTEIN'
										when '19' then 'SWF'
										when '21' then 'VALEO'
										when '24' then 'EXIDE'
										when '26' then 'DUNLOP'
										when '27' then 'Seiken'
										when '28' then 'MOOG-AU'
										when '30' then 'BOSCH'
										when '31' then 'CONTINENTAL CTAM'
										when '32' then 'SACHS'
										when '33' then 'GATES'
										when '35' then 'LEMFÖRDER'
										when '37' then 'MONROE'
										when '38' then 'PURFLUX'
										when '39' then 'TEXTAR'
										when '41' then 'BOSAL'
										when '42' then 'DAYCO'
										when '43' then 'CHAMPION'
										when '49' then 'BENDIX'
										when '50' then 'SKF'
										when '54' then 'FTE'
										when '55' then 'Herth+Buss Jakoparts'
										when '60' then 'GOETZE'
										when '61' then 'TYC'
										when '62' then 'FERODO'
										when '65' then 'BREMBO'
										when '66' then 'DENSO'
										when '67' then 'ams-OSRAM'
										when '68' then 'ZF'
										when '75' then 'PHILIPS'
										when '79' then 'BorgWarner (Wahler)'
										when '83' then 'VDO/CONTINENTAL'
										when '85' then 'KYB'
										when '89' then 'DELPHI'
										when '95' then 'MAGNETI MARELLI'
										when '101' then 'FEBI BILSTEIN'
										when '110' then 'SNR'
										when '113' then 'PAYEN'
										when '121' then 'METELLI'
										when '128' then 'BE TURBO'
										when '137' then 'UFI'
										when '139' then 'AJUSA'
										when '140' then 'CORTECO'
										when '144' then 'MEYLE'
										when '151' then 'SWAG'
										when '152' then 'ROADHOUSE'
										when '154' then 'DOLZ'
										when '156' then 'JAPANPARTS'
										when '159' then 'FACET'
										when '161' then 'TRW'
										when '162' then 'VAICO'
										when '166' then 'AISIN'
										when '177' then 'BTS Turbo'
										when '183' then 'VEMO'
										when '190' then 'National'
										when '192' then 'Schaeffler FAG'
										when '197' then 'LPR'
										when '204' then 'Schaeffler INA'
										when '205' then 'NRF'
										when '207' then 'CASTROL'
										when '208' then 'BIRTH'
										when '215' then 'CALORSTAT by Vernet'
										when '222' then 'LIQUI MOLY'
										when '234' then 'ERA'
										when '240' then 'FAE'
										when '244' then 'MEAT & DORIA'
										when '245' then 'Saleri SIL'
										when '248' then 'NIPPARTS'
										when '252' then 'ZF Parts'
										when '256' then 'MICHELIN'
										when '268' then 'BENDIX'
										when '269' then 'GOETZE'
										when '274' then 'BorgWarner (AWD)'
										when '277' then 'CONTINENTAL/VDO'
										when '283' then 'AP'
										when '287' then 'MAHLE'
										when '292' then 'LIZARTE'
										when '296' then 'JDEUS'
										when '301' then 'TOPRAN'
										when '310' then 'GRAF'
										when '311' then 'CIFAM'
										when '315' then 'MICHELIN'
										when '326' then 'BorgWarner Schwitzer'
										when '327' then 'BorgWarner'
										when '328' then 'BorgWarner (3K)'
										when '331' then 'ORIGINAL IMPERIUM'
										when '350' then 'BLUE PRINT'
										when '351' then 'AUTOFREN SEINSA'
										when '357' then 'VENEPORTE'
										when '362' then 'ALKAR'
										when '377' then 'BOSCH DIAGNOSTICS'
										when '405' then 'CAUTEX'
										when '406' then 'BM Catalysts'
										when '421' then 'COMLINE'
										when '429' then 'WAT'
										when '432' then 'KOLBENSCHMIDT'
										when '436' then 'MOTUL'
										when '437' then 'CoopersFiaam'
										when '449' then 'ASTEMO-HITACHI'
										when '460' then 'DRI'
										when '475' then 'BORG & BECK'
										when '484' then 'CASCO'
										when '4364' then 'ACKOJA'
										when '4436' then 'DUNLOP'
										when '4451' then 'YUASA'
										when '4501' then 'LUCAS'
										when '4561' then 'WESTLAKE'
										when '4598' then '3RG'
										when '4615' then 'GARRETT'
										when '4627' then 'WAI'
										when '4664' then 'Metalcaucho'
										when '4667' then 'STC'
										when '4734' then 'HELLA GUTMANN'
										when '4758' then 'Talosa'
										when '4773' then 'MDR'
										when '4819' then 'HC-Cargo'
										when '4828' then 'RYMEC'
										when '4830' then 'TMI'
										when '4843' then 'AS-PL'
										when '4948' then 'DR!VE+'
										when '4965' then 'JUMASA'
										when '4970' then 'TRICLO'
										when '5407' then 'NEOCOM'
										when '5444' then 'Hankook'
										when '5562' then 'DTS'
										when '6303' then 'Seiken'
										when '6355' then 'NTY'
										when '6441' then 'BERU by DRiV'
										when '6444' then 'BorgWarner (AWD)'
										when '6512' then 'CONTINENTAL/VDO'
										when '6558' then 'AIC'
										when '6770' then 'MOOG-AU'
										when '6944' then 'Vitesco Technologies'
										when '6982' then 'CONTINENTAL Tires'
										when '7363' then 'Tech One'
										when '7820' then 'Schaeffler Vitesco'
										when '7952' then 'WAHLER'
                      else ''
                  end Descricao,
                list(a.referencia) Refs
          from artigo a
          where a.codigo_arm='2' and
                      a.campo_user1 is not null and
                      a.campo_user1 <> '' and
                      a.absoleto='N'
          group by 1, 2 ,3
          having count(a.referencia) > 1";
	} else {
		$sql="select a.campo_user1 Codigo, a.campo_user3 Marca,
                case campo_user3
                      when '2' then 'HELLA'
											when '3' then 'ATE'
											when '4' then 'MANN-FILTER'
											when '5' then 'PIERBURG'
											when '6' then 'Schaeffler LuK'
											when '9' then 'VICTOR REINZ'
											when '10' then 'ELRING'
											when '11' then 'BorgWarner (BERU)'
											when '15' then 'NGK'
											when '16' then 'BILSTEIN'
											when '19' then 'SWF'
											when '21' then 'VALEO'
											when '24' then 'EXIDE'
											when '26' then 'DUNLOP'
											when '27' then 'Seiken'
											when '28' then 'MOOG-AU'
											when '30' then 'BOSCH'
											when '31' then 'CONTINENTAL CTAM'
											when '32' then 'SACHS'
											when '33' then 'GATES'
											when '35' then 'LEMFÖRDER'
											when '37' then 'MONROE'
											when '38' then 'PURFLUX'
											when '39' then 'TEXTAR'
											when '41' then 'BOSAL'
											when '42' then 'DAYCO'
											when '43' then 'CHAMPION'
											when '49' then 'BENDIX'
											when '50' then 'SKF'
											when '54' then 'FTE'
											when '55' then 'Herth+Buss Jakoparts'
											when '60' then 'GOETZE'
											when '61' then 'TYC'
											when '62' then 'FERODO'
											when '65' then 'BREMBO'
											when '66' then 'DENSO'
											when '67' then 'ams-OSRAM'
											when '68' then 'ZF'
											when '75' then 'PHILIPS'
											when '79' then 'BorgWarner (Wahler)'
											when '83' then 'VDO/CONTINENTAL'
											when '85' then 'KYB'
											when '89' then 'DELPHI'
											when '95' then 'MAGNETI MARELLI'
											when '101' then 'FEBI BILSTEIN'
											when '110' then 'SNR'
											when '113' then 'PAYEN'
											when '121' then 'METELLI'
											when '128' then 'BE TURBO'
											when '137' then 'UFI'
											when '139' then 'AJUSA'
											when '140' then 'CORTECO'
											when '144' then 'MEYLE'
											when '151' then 'SWAG'
											when '152' then 'ROADHOUSE'
											when '154' then 'DOLZ'
											when '156' then 'JAPANPARTS'
											when '159' then 'FACET'
											when '161' then 'TRW'
											when '162' then 'VAICO'
											when '166' then 'AISIN'
											when '177' then 'BTS Turbo'
											when '183' then 'VEMO'
											when '190' then 'National'
											when '192' then 'Schaeffler FAG'
											when '197' then 'LPR'
											when '204' then 'Schaeffler INA'
											when '205' then 'NRF'
											when '207' then 'CASTROL'
											when '208' then 'BIRTH'
											when '215' then 'CALORSTAT by Vernet'
											when '222' then 'LIQUI MOLY'
											when '234' then 'ERA'
											when '240' then 'FAE'
											when '244' then 'MEAT & DORIA'
											when '245' then 'Saleri SIL'
											when '248' then 'NIPPARTS'
											when '252' then 'ZF Parts'
											when '256' then 'MICHELIN'
											when '268' then 'BENDIX'
											when '269' then 'GOETZE'
											when '274' then 'BorgWarner (AWD)'
											when '277' then 'CONTINENTAL/VDO'
											when '283' then 'AP'
											when '287' then 'MAHLE'
											when '292' then 'LIZARTE'
											when '296' then 'JDEUS'
											when '301' then 'TOPRAN'
											when '310' then 'GRAF'
											when '311' then 'CIFAM'
											when '315' then 'MICHELIN'
											when '326' then 'BorgWarner Schwitzer'
											when '327' then 'BorgWarner'
											when '328' then 'BorgWarner (3K)'
											when '331' then 'ORIGINAL IMPERIUM'
											when '350' then 'BLUE PRINT'
											when '351' then 'AUTOFREN SEINSA'
											when '357' then 'VENEPORTE'
											when '362' then 'ALKAR'
											when '377' then 'BOSCH DIAGNOSTICS'
											when '405' then 'CAUTEX'
											when '406' then 'BM Catalysts'
											when '421' then 'COMLINE'
											when '429' then 'WAT'
											when '432' then 'KOLBENSCHMIDT'
											when '436' then 'MOTUL'
											when '437' then 'CoopersFiaam'
											when '449' then 'ASTEMO-HITACHI'
											when '460' then 'DRI'
											when '475' then 'BORG & BECK'
											when '484' then 'CASCO'
											when '4364' then 'ACKOJA'
											when '4436' then 'DUNLOP'
											when '4451' then 'YUASA'
											when '4501' then 'LUCAS'
											when '4561' then 'WESTLAKE'
											when '4598' then '3RG'
											when '4615' then 'GARRETT'
											when '4627' then 'WAI'
											when '4664' then 'Metalcaucho'
											when '4667' then 'STC'
											when '4734' then 'HELLA GUTMANN'
											when '4758' then 'Talosa'
											when '4773' then 'MDR'
											when '4819' then 'HC-Cargo'
											when '4828' then 'RYMEC'
											when '4830' then 'TMI'
											when '4843' then 'AS-PL'
											when '4948' then 'DR!VE+'
											when '4965' then 'JUMASA'
											when '4970' then 'TRICLO'
											when '5407' then 'NEOCOM'
											when '5444' then 'Hankook'
											when '5562' then 'DTS'
											when '6303' then 'Seiken'
											when '6355' then 'NTY'
											when '6441' then 'BERU by DRiV'
											when '6444' then 'BorgWarner (AWD)'
											when '6512' then 'CONTINENTAL/VDO'
											when '6558' then 'AIC'
											when '6770' then 'MOOG-AU'
											when '6944' then 'Vitesco Technologies'
											when '6982' then 'CONTINENTAL Tires'
											when '7363' then 'Tech One'
											when '7820' then 'Schaeffler Vitesco'
											when '7952' then 'WAHLER'
                      else ''
                  end Descricao,
                list(a.referencia) Refs
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

function tecdoc_simples($conn, $cod) {
	$sql="select a.campo_user1 Codigo, a.campo_user3 Marca,
              case campo_user3
                  when '2' then 'HELLA'
									when '3' then 'ATE'
									when '4' then 'MANN-FILTER'
									when '5' then 'PIERBURG'
									when '6' then 'Schaeffler LuK'
									when '9' then 'VICTOR REINZ'
									when '10' then 'ELRING'
									when '11' then 'BorgWarner (BERU)'
									when '15' then 'NGK'
									when '16' then 'BILSTEIN'
									when '19' then 'SWF'
									when '21' then 'VALEO'
									when '24' then 'EXIDE'
									when '26' then 'DUNLOP'
									when '27' then 'Seiken'
									when '28' then 'MOOG-AU'
									when '30' then 'BOSCH'
									when '31' then 'CONTINENTAL CTAM'
									when '32' then 'SACHS'
									when '33' then 'GATES'
									when '35' then 'LEMFÖRDER'
									when '37' then 'MONROE'
									when '38' then 'PURFLUX'
									when '39' then 'TEXTAR'
									when '41' then 'BOSAL'
									when '42' then 'DAYCO'
									when '43' then 'CHAMPION'
									when '49' then 'BENDIX'
									when '50' then 'SKF'
									when '54' then 'FTE'
									when '55' then 'Herth+Buss Jakoparts'
									when '60' then 'GOETZE'
									when '61' then 'TYC'
									when '62' then 'FERODO'
									when '65' then 'BREMBO'
									when '66' then 'DENSO'
									when '67' then 'ams-OSRAM'
									when '68' then 'ZF'
									when '75' then 'PHILIPS'
									when '79' then 'BorgWarner (Wahler)'
									when '83' then 'VDO/CONTINENTAL'
									when '85' then 'KYB'
									when '89' then 'DELPHI'
									when '95' then 'MAGNETI MARELLI'
									when '101' then 'FEBI BILSTEIN'
									when '110' then 'SNR'
									when '113' then 'PAYEN'
									when '121' then 'METELLI'
									when '128' then 'BE TURBO'
									when '137' then 'UFI'
									when '139' then 'AJUSA'
									when '140' then 'CORTECO'
									when '144' then 'MEYLE'
									when '151' then 'SWAG'
									when '152' then 'ROADHOUSE'
									when '154' then 'DOLZ'
									when '156' then 'JAPANPARTS'
									when '159' then 'FACET'
									when '161' then 'TRW'
									when '162' then 'VAICO'
									when '166' then 'AISIN'
									when '177' then 'BTS Turbo'
									when '183' then 'VEMO'
									when '190' then 'National'
									when '192' then 'Schaeffler FAG'
									when '197' then 'LPR'
									when '204' then 'Schaeffler INA'
									when '205' then 'NRF'
									when '207' then 'CASTROL'
									when '208' then 'BIRTH'
									when '215' then 'CALORSTAT by Vernet'
									when '222' then 'LIQUI MOLY'
									when '234' then 'ERA'
									when '240' then 'FAE'
									when '244' then 'MEAT & DORIA'
									when '245' then 'Saleri SIL'
									when '248' then 'NIPPARTS'
									when '252' then 'ZF Parts'
									when '256' then 'MICHELIN'
									when '268' then 'BENDIX'
									when '269' then 'GOETZE'
									when '274' then 'BorgWarner (AWD)'
									when '277' then 'CONTINENTAL/VDO'
									when '283' then 'AP'
									when '287' then 'MAHLE'
									when '292' then 'LIZARTE'
									when '296' then 'JDEUS'
									when '301' then 'TOPRAN'
									when '310' then 'GRAF'
									when '311' then 'CIFAM'
									when '315' then 'MICHELIN'
									when '326' then 'BorgWarner Schwitzer'
									when '327' then 'BorgWarner'
									when '328' then 'BorgWarner (3K)'
									when '331' then 'ORIGINAL IMPERIUM'
									when '350' then 'BLUE PRINT'
									when '351' then 'AUTOFREN SEINSA'
									when '357' then 'VENEPORTE'
									when '362' then 'ALKAR'
									when '377' then 'BOSCH DIAGNOSTICS'
									when '405' then 'CAUTEX'
									when '406' then 'BM Catalysts'
									when '421' then 'COMLINE'
									when '429' then 'WAT'
									when '432' then 'KOLBENSCHMIDT'
									when '436' then 'MOTUL'
									when '437' then 'CoopersFiaam'
									when '449' then 'ASTEMO-HITACHI'
									when '460' then 'DRI'
									when '475' then 'BORG & BECK'
									when '484' then 'CASCO'
									when '4364' then 'ACKOJA'
									when '4436' then 'DUNLOP'
									when '4451' then 'YUASA'
									when '4501' then 'LUCAS'
									when '4561' then 'WESTLAKE'
									when '4598' then '3RG'
									when '4615' then 'GARRETT'
									when '4627' then 'WAI'
									when '4664' then 'Metalcaucho'
									when '4667' then 'STC'
									when '4734' then 'HELLA GUTMANN'
									when '4758' then 'Talosa'
									when '4773' then 'MDR'
									when '4819' then 'HC-Cargo'
									when '4828' then 'RYMEC'
									when '4830' then 'TMI'
									when '4843' then 'AS-PL'
									when '4948' then 'DR!VE+'
									when '4965' then 'JUMASA'
									when '4970' then 'TRICLO'
									when '5407' then 'NEOCOM'
									when '5444' then 'Hankook'
									when '5562' then 'DTS'
									when '6303' then 'Seiken'
									when '6355' then 'NTY'
									when '6441' then 'BERU by DRiV'
									when '6444' then 'BorgWarner (AWD)'
									when '6512' then 'CONTINENTAL/VDO'
									when '6558' then 'AIC'
									when '6770' then 'MOOG-AU'
									when '6944' then 'Vitesco Technologies'
									when '6982' then 'CONTINENTAL Tires'
									when '7363' then 'Tech One'
									when '7820' then 'Schaeffler Vitesco'
									when '7952' then 'WAHLER'
                  else ''
              end Descricao,
            a.referencia, a.designacao1,
            a.cod_marca || ' - ' || m.descricao cod_marca,
            a.cod_familia || ' - ' || f.descricao cod_familia,
            a.cod_grupo || ' - ' || g.descricao cod_grupo,
            a.preco1, a.preco_custo
      from artigo a
      left join goa_marca m on m.cod_marca=a.cod_marca
      left join familia f on f.cod_familia=a.cod_familia
      left join gia_grupo g on g.cod_grupo=a.cod_grupo
      where a.codigo_arm='2' and
            a.campo_user1 = '$cod'";
		
	$result=$conn->query($sql);

  $dados = array();

  foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
    $dados[] = $dado;
  }

  return $dados;
}

function verificarRefPortal($conn, $ref){
  $sql="select distinct ni.ItemId, ni.ProductRef, ni.ProductName, ni.Visible,
               nb.XMLData.value('(genxml/checkbox/chkishidden)[1]','varchar(30)') Hidden,
	             nb.XMLData.value('(genxml/checkbox/chkdisable)[1]','varchar(30)') Disable
				from NBrightBuyIdx ni
				left join NBrightBuy nb on nb.itemid=ni.ItemId
				where ProductRef ='$ref' and ni.TypeCode='PRD'";
	
	$result = sqlsrv_query($conn, $sql);

  $dados = array();

  while($dado = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $dados[]=$dado;
  }

  return $dados;
}
?>