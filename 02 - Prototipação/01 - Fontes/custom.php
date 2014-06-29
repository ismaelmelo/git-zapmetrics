<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php ew_Header(FALSE) ?>
<?php include_once "header.php"; ?>

<script type="text/javascript">
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>

<?php
	include_once "config.php";
	$connStr = "Provider=" . $argProvider . ";Persist Security Info=False;Data Source=" . $argHostname . ";Initial Catalog=" . $argDatabasename . ";User Id=" . $argUsername . ";Password=" . $argPassword . "";
	/*----------------------------------------------------*/
	$conn = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
	$conn->open($connStr);
	//Dados da seleção 1
	$instrucaoSQL = "SELECT 
						dh_processamento
					FROM 
						processamento 
					WHERE 
						nu_processamento = (SELECT MAX(nu_processamento) FROM processamento)";
	$rs = $conn->execute($instrucaoSQL);
	If (!isset($rs) or empty($rs)) {
		$dhProc = "";
	} else {
		$dhProc = $rs->Fields(0)->Value;
	}
	
	$rs->Close();
	$conn->Close();
	$rs = null;
	$conn = null;
	/*----------------------------------------------------*/
	$connb = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
	$connb->open($connStr);
	//Dados da seleção 1
	$instrucaoSQLb = "SELECT 
						no_urlServicos
					FROM 
						parintegracoes 
					WHERE 
						nu_id = '1'";
	$rsb = $connb->execute($instrucaoSQLb);
	$num_columnsb = $rsb->Fields->Count();
	
	for ($i=0; $i < $num_columnsb; $i++) {
		$fld[$i] = $rsb->Fields($i);
	}
	
	$url = $fld[0]->value;
	
	$rsb->Close();
	$connb->Close();
	$rsb = null;
	$connb = null;
	/*----------------------------------------------------*/
echo"<div style='background:#F0F0F0'>Última atualização das informações operacionais: " . ew_FormatDateTime($dhProc, 17) . " (<a href='/" . $url . "start.php' target='_blank'>atualizar agora</a>)</div><br>";
$perfilUsuarioLogado = @$_SESSION[EW_SESSION_USER_LEVEL_ID];
if ($perfilUsuarioLogado == "") {
	$conn0 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
	$conn0->open($connStr);
	//Dados da seleção 1
	$instrucaoSQL = "SELECT 
						tx_htmlHomeNaoLogado
					FROM 
						pargerais 
					WHERE 
						nu_parametro = '1'";
	$rs0 = $conn0->execute($instrucaoSQL);
	$num_columns = $rs0->Fields->Count();
	
	for ($i=0; $i < $num_columns; $i++) {
		$fld[$i] = $rs0->Fields($i);
	}
	
	echo $fld[0]->value;
	
	$rs0->Close();
	$conn0->Close();
	$rs0 = null;
	$conn0 = null;
} else {
?>
  <div name="superior">
	<?php
		//-------------------------- Posições 1, 2 e 3 ----------------------------------------
		$conn1 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
		$conn1->open($connStr);
		//Dados da seleção 1
		$instrucaoSQL = "SELECT 
							wp.nu_widget AS nu_widget, 
							wp.nu_perfil AS nu_perfil, 
							wp.no_titulo AS no_titulo, 
							wp.no_legTexto AS no_legTexto, 
							wp.no_legValores AS no_legValores, 
							wp.nu_posicao AS nu_posicao,  
							wp.vr_larguraEmPx AS vr_larguraEmPx, 
							wp.vr_alturaEmPx AS vr_alturaEmPx, 
							w.im_arquivo AS no_arquivo 
						FROM 
							widget_perfil wp 
						LEFT JOIN widget w 
						  ON 
							wp.nu_widget = w.nu_widget 
						WHERE 
							(wp.nu_posicao = '1' OR wp.nu_posicao = '2' OR wp.nu_posicao = '3') 
						  AND
							wp.nu_perfil = " . $perfilUsuarioLogado . " 
						  AND 
							w.ic_ativo = 'S' 
						  AND
							wp.ic_ativo = 'S' 
						ORDER BY
							wp.nu_posicao ASC";
		$rs1 = $conn1->execute($instrucaoSQL);
		$num_columns = $rs1->Fields->Count();
		for ($i=0; $i < $num_columns; $i++) {
			$fld[$i] = $rs1->Fields($i);
		}
		while (!$rs1->EOF) {  //carry on looping through while there are records
			if (($fld[5]->value == 1) OR ($fld[5]->value == 2) OR ($fld[5]->value == 3)) { ?>
				<iframe src="servicos/widgets/<?php echo urlencode($fld[8]->value) . "?titulo=" . urlencode($fld[2]->value) . "&legTexto=" . urlencode($fld[3]->value) . "&legValor=" . urlencode($fld[4]->value); ?>" width="<?php echo $fld[6]->value; ?>" height="<?php echo $fld[7]->value; ?>" frameborder="no"></iframe>
			<?php }
			$rs1->MoveNext(); //move on to the next record
		}
		$w1 = $fld;
		$rs1->Close();
		$conn1->Close();
		$rs1 = null;
		$conn1 = null;
	?>
  </div>
  <div name="meio">
  	<?php
		//-------------------------- Posições 4, 5 e 6 ----------------------------------------
		$conn1 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
		$connStr = "Provider=" . $argProvider . ";Persist Security Info=False;Data Source=" . $argHostname . ";Initial Catalog=" . $argDatabasename . ";User Id=" . $argUsername . ";Password=" . $argPassword . "";
		$conn1->open($connStr);
		//Dados da seleção 1
		$instrucaoSQL = "SELECT 
							wp.nu_widget AS nu_widget, 
							wp.nu_perfil AS nu_perfil, 
							wp.no_titulo AS no_titulo, 
							wp.no_legTexto AS no_legTexto, 
							wp.no_legValores AS no_legValores, 
							wp.nu_posicao AS nu_posicao, 
							wp.vr_larguraEmPx AS vr_larguraEmPx, 
							wp.vr_alturaEmPx AS vr_alturaEmPx, 
							w.im_arquivo AS no_arquivo 
						FROM 
							widget_perfil wp 
						LEFT JOIN widget w 
						  ON 
							wp.nu_widget = w.nu_widget 
						WHERE 
							(wp.nu_posicao = '4' OR wp.nu_posicao = '5' OR wp.nu_posicao = '6') 
						  AND
							wp.nu_perfil = " . $perfilUsuarioLogado . " 
						  AND 
							w.ic_ativo = 'S' 
						  AND
							wp.ic_ativo = 'S' 
						ORDER BY
							wp.nu_posicao ASC";
		$rs1 = $conn1->execute($instrucaoSQL);
		$num_columns = $rs1->Fields->Count();
		for ($i=0; $i < $num_columns; $i++) {
			$fld[$i] = $rs1->Fields($i);
		}
		while (!$rs1->EOF) {  //carry on looping through while there are records
			if (($fld[5]->value == 4) OR ($fld[5]->value == 5) OR ($fld[5]->value == 6)) { ?>
				<iframe src="servicos/widgets/<?php echo urlencode($fld[8]->value) . "?titulo=" . urlencode($fld[2]->value) . "&legTexto=" . urlencode($fld[3]->value) . "&legValor=" . urlencode($fld[4]->value); ?>" width="<?php echo $fld[6]->value; ?>" height="<?php echo $fld[7]->value; ?>" frameborder="no"></iframe>
			<?php }
			$rs1->MoveNext(); //move on to the next record
		}
		$w1 = $fld;
		$rs1->Close();
		$conn1->Close();
		$rs1 = null;
		$conn1 = null;
	?>
  </div>
  <div name="inferior">
  	<?php
		//-------------------------- Posições 7, 8 e 9 ----------------------------------------
		$conn1 = new COM ("ADODB.Connection") or die("Não foi possível carregar o ADO");
		$connStr = "Provider=" . $argProvider . ";Persist Security Info=False;Data Source=" . $argHostname . ";Initial Catalog=" . $argDatabasename . ";User Id=" . $argUsername . ";Password=" . $argPassword . "";
		$conn1->open($connStr);
		//Dados da seleção 1
		$instrucaoSQL = "SELECT 
							wp.nu_widget AS nu_widget, 
							wp.nu_perfil AS nu_perfil, 
							wp.no_titulo AS no_titulo, 
							wp.no_legTexto AS no_legTexto, 
							wp.no_legValores AS no_legValores, 
							wp.nu_posicao AS nu_posicao, 
							wp.vr_larguraEmPx AS vr_larguraEmPx, 
							wp.vr_alturaEmPx AS vr_alturaEmPx, 
							w.im_arquivo AS no_arquivo 
						FROM 
							widget_perfil wp 
						LEFT JOIN widget w 
						  ON 
							wp.nu_widget = w.nu_widget 
						WHERE 
							(wp.nu_posicao = '7' OR wp.nu_posicao = '8' OR wp.nu_posicao = '9') 
						  AND
							wp.nu_perfil = " . $perfilUsuarioLogado . " 
						  AND 
							w.ic_ativo = 'S' 
						  AND
							wp.ic_ativo = 'S' 
						ORDER BY
							wp.nu_posicao ASC";
		$rs1 = $conn1->execute($instrucaoSQL);
		$num_columns = $rs1->Fields->Count();
		for ($i=0; $i < $num_columns; $i++) {
			$fld[$i] = $rs1->Fields($i);
		}
		while (!$rs1->EOF) {  //carry on looping through while there are records
			if (($fld[5]->value == 7) OR ($fld[5]->value == 8) OR ($fld[5]->value == 9)) { ?>
				<iframe src="servicos/widgets/<?php echo urlencode($fld[8]->value) . 
					"?titulo=" . urlencode($fld[2]->value) . 
					"&legTexto=" . urlencode($fld[3]->value) . 
					"&legValor=" . $fld[4]->value; ?>" width="<?php echo $fld[6]->value; ?>" height="<?php echo $fld[7]->value; ?>" frameborder="no"></iframe>
			<?php }
			$rs1->MoveNext(); //move on to the next record
		}
		$w1 = $fld;
		$rs1->Close();
		$conn1->Close();
		$rs1 = null;
		$conn1 = null;
	?>
  </div>

<?php 
}
include_once "footer.php";
?>
