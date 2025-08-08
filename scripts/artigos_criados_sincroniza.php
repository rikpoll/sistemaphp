<?php
  $referencia=$_POST['referencia']; 
	
  function dadosDoArtigo($conn,$ref) {
    $sql = "select codigo_arm, referencia, a.designacao1,
									 a.cod_marca, a.cod_versao, a.versao,
									 a.cod_familia, a.cod_grupo,
									 a.preco1, a.preco2, a.preco3, a.preco4, a.preco5, a.preco6, a.preco_custo,
									 a.tempo_entrega, a.mov_stocks, a.controla_loc,
									 a.campo_user1, a.campo_user2, a.campo_user3, a.portalweb,
									 a.absoleto, a.mono, a.isento_descfinanc, a.isento_desccomer,
									 a.cod_integracao, a.merc_cod, a.iva_taxa, a.cod_unidade, a.cod_situacao,
                   a.cod_tipo_art, a.cod_depart, a.stock_minimo, a.stock_maximo,
                   a.pnt_encomen, a.qtd_med_encom, a.des_activa, a.enc_autom, a.limite_etiq
						from artigo a
						where codigo_arm='2' and referencia = '" . $ref . "'";
					
		$result = $conn->query($sql);
		
		$dados = array();
		
		foreach($result->fetchAll(PDO::FETCH_OBJ) as $dado) {
			$dados[] = $dado;
		}

		return $dados;
  }
	
	function atualiza($ref) {
		include_once '../config.php';
		
		$artigo = dadosDoArtigo($fbConexaoLL,$ref); 
		foreach($artigo as $i){
			$referencia=$i->REFERENCIA;
			$descricao=$i->DESIGNACAO1;
			$cod_marca=$i->COD_MARCA;
			$cod_versao=$i->COD_VERSAO;
			$versao=$i->VERSAO;
			$cod_familia=$i->COD_FAMILIA;
			$cod_grupo=$i->COD_GRUPO;
			$preco1=$i->PRECO1;
			$preco2=$i->PRECO2;
			$preco3=$i->PRECO3;
			$preco4=$i->PRECO4;
			$preco5=$i->PRECO5;
			$preco6=$i->PRECO6;
			$preco_custo=$i->PRECO_CUSTO;
			$tempo_entrega=$i->TEMPO_ENTREGA;
			$mov_stocks=$i->MOV_STOCKS;
			$controla_loc=$i->CONTROLA_LOC;
			$campo_user1=$i->CAMPO_USER1;
			$campo_user2=$i->CAMPO_USER2;
			$campo_user3=$i->CAMPO_USER3;
			$portalweb=$i->PORTALWEB;
			$obsoleto=$i->ABSOLETO;
			$mono=$i->MONO;
			$isento_descfinanc=$i->ISENTO_DESCFINANC;
			$isento_desccomer=$i->ISENTO_DESCCOMER;
			//extras
			$cod_integra=$i->COD_INTEGRACAO;
			$cod_mercado=$i->MERC_COD;
			$iva_taxa=$i->IVA_TAXA;
			$cod_unidade=$i->COD_UNIDADE;
			$cod_situacao=$i->COD_SITUACAO;
      $tipo_art=$i->COD_TIPO_ART;
			$cod_depart=$i->COD_DEPART;
			$stock_min=$i->STOCK_MINIMO;
			$stock_max=$i->STOCK_MAXIMO;
      $pnt_enc=$i->PNT_ENCOMEN;
			$qtd_enc=$i->QTD_MED_ENCOM;
			$des_activa=$i->DES_ACTIVA;
			$enc_aut=$i->ENC_AUTOM;
			$etiquet=$i->LIMITE_ETIQ;
		}
		
		$sql="UPDATE OR INSERT INTO ARTIGO (CODIGO_ARM, REFERENCIA, DESIGNACAO1, COD_MARCA, COD_VERSAO, VERSAO, COD_FAMILIA, COD_GRUPO, PRECO1, PRECO2, PRECO3, PRECO4, PRECO5, PRECO6, PRECO_CUSTO, TEMPO_ENTREGA, MOV_STOCKS, CONTROLA_LOC, CAMPO_USER1, CAMPO_USER2, CAMPO_USER3, PORTALWEB, ABSOLETO, MONO, ISENTO_DESCFINANC, ISENTO_DESCCOMER,
		                                    COD_INTEGRACAO, MERC_COD, IVA_TAXA, COD_UNIDADE, COD_SITUACAO, COD_TIPO_ART, COD_DEPART, STOCK_MINIMO, STOCK_MAXIMO, PNT_ENCOMEN, QTD_MED_ENCOM, DES_ACTIVA, ENC_AUTOM, LIMITE_ETIQ)
                      VALUES ('2', '$referencia', '$descricao', '$cod_marca', '$cod_versao', '$versao', '$cod_familia', '$cod_grupo', $preco1, $preco2, $preco3, $preco4, $preco5, $preco6, $preco_custo, $tempo_entrega, '$mov_stocks', '$controla_loc', '$campo_user1', '$campo_user2', '$campo_user3', '$portalweb', '$obsoleto', '$mono', '$isento_descfinanc', '$isento_desccomer',
											        '$cod_integra', $cod_mercado, $iva_taxa, '$cod_unidade', '$cod_situacao', '$tipo_art', '$cod_depart', $stock_min, $stock_max, $pnt_enc, $qtd_enc, $des_activa, '$enc_aut', $etiquet)
                    MATCHING (CODIGO_ARM, REFERENCIA);";
		
		$resLL=$fbConexaoLL->exec($sql);
		$resVA=$fbConexaoVA->exec($sql);
		$resBP=$fbConexaoBP->exec($sql);
		$resEM=$fbConexaoEM->exec($sql);
		$resUNO=$fbConexaoUNO->exec($sql);
	}
	
	atualiza($referencia);
	
	echo '<img src="../img/v.jpg" alt="Cadastrada." width=30 height=30>';
?>