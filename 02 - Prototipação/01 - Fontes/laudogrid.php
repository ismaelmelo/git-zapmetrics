<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($laudo_grid)) $laudo_grid = new claudo_grid();

// Page init
$laudo_grid->Page_Init();

// Page main
$laudo_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$laudo_grid->Page_Render();
?>
<?php if ($laudo->Export == "") { ?>
<script type="text/javascript">

// Page object
var laudo_grid = new ew_Page("laudo_grid");
laudo_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = laudo_grid.PageID; // For backward compatibility

// Form object
var flaudogrid = new ew_Form("flaudogrid");
flaudogrid.FormKeyCountName = '<?php echo $laudo_grid->FormKeyCountName ?>';

// Validate form
flaudogrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_nu_solicitacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->nu_solicitacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->nu_versao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->nu_versao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_pf");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_pf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_horas");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_horas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_prazoMeses");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_prazoMeses->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_prazoDias");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_prazoDias->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_contratacao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->vr_contratacao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioSolicitacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->dt_inicioSolicitacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioSolicitacao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->dt_inicioSolicitacao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioContagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->dt_inicioContagem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioContagem");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->dt_inicioContagem->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tamanho");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ic_tamanho->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_esforco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ic_esforco->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_prazo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ic_prazo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_custo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($laudo->ic_custo->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
flaudogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_solicitacao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_versao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_pf", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_horas", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_prazoMeses", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_prazoDias", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_contratacao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dt_inicioSolicitacao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dt_inicioContagem", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_tamanho", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_esforco", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_prazo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_custo", false)) return false;
	return true;
}

// Form_CustomValidate event
flaudogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flaudogrid.ValidateRequired = true;
<?php } else { ?>
flaudogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
flaudogrid.Lists["x_nu_solicitacao"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flaudogrid.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($laudo->getCurrentMasterTable() == "" && $laudo_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $laudo_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($laudo->CurrentAction == "gridadd") {
	if ($laudo->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$laudo_grid->TotalRecs = $laudo->SelectRecordCount();
			$laudo_grid->Recordset = $laudo_grid->LoadRecordset($laudo_grid->StartRec-1, $laudo_grid->DisplayRecs);
		} else {
			if ($laudo_grid->Recordset = $laudo_grid->LoadRecordset())
				$laudo_grid->TotalRecs = $laudo_grid->Recordset->RecordCount();
		}
		$laudo_grid->StartRec = 1;
		$laudo_grid->DisplayRecs = $laudo_grid->TotalRecs;
	} else {
		$laudo->CurrentFilter = "0=1";
		$laudo_grid->StartRec = 1;
		$laudo_grid->DisplayRecs = $laudo->GridAddRowCount;
	}
	$laudo_grid->TotalRecs = $laudo_grid->DisplayRecs;
	$laudo_grid->StopRec = $laudo_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$laudo_grid->TotalRecs = $laudo->SelectRecordCount();
	} else {
		if ($laudo_grid->Recordset = $laudo_grid->LoadRecordset())
			$laudo_grid->TotalRecs = $laudo_grid->Recordset->RecordCount();
	}
	$laudo_grid->StartRec = 1;
	$laudo_grid->DisplayRecs = $laudo_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$laudo_grid->Recordset = $laudo_grid->LoadRecordset($laudo_grid->StartRec-1, $laudo_grid->DisplayRecs);
}
$laudo_grid->RenderOtherOptions();
?>
<?php $laudo_grid->ShowPageHeader(); ?>
<?php
$laudo_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="flaudogrid" class="ewForm form-horizontal">
<div id="gmp_laudo" class="ewGridMiddlePanel">
<table id="tbl_laudogrid" class="ewTable ewTableSeparate">
<?php echo $laudo->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$laudo_grid->RenderListOptions();

// Render list options (header, left)
$laudo_grid->ListOptions->Render("header", "left");
?>
<?php if ($laudo->nu_solicitacao->Visible) { // nu_solicitacao ?>
	<?php if ($laudo->SortUrl($laudo->nu_solicitacao) == "") { ?>
		<td><div id="elh_laudo_nu_solicitacao" class="laudo_nu_solicitacao"><div class="ewTableHeaderCaption"><?php echo $laudo->nu_solicitacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_nu_solicitacao" class="laudo_nu_solicitacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->nu_solicitacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->nu_solicitacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->nu_solicitacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->nu_versao->Visible) { // nu_versao ?>
	<?php if ($laudo->SortUrl($laudo->nu_versao) == "") { ?>
		<td><div id="elh_laudo_nu_versao" class="laudo_nu_versao"><div class="ewTableHeaderCaption"><?php echo $laudo->nu_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_nu_versao" class="laudo_nu_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->nu_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->nu_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->nu_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->qt_pf->Visible) { // qt_pf ?>
	<?php if ($laudo->SortUrl($laudo->qt_pf) == "") { ?>
		<td><div id="elh_laudo_qt_pf" class="laudo_qt_pf"><div class="ewTableHeaderCaption"><?php echo $laudo->qt_pf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_qt_pf" class="laudo_qt_pf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->qt_pf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->qt_pf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->qt_pf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->qt_horas->Visible) { // qt_horas ?>
	<?php if ($laudo->SortUrl($laudo->qt_horas) == "") { ?>
		<td><div id="elh_laudo_qt_horas" class="laudo_qt_horas"><div class="ewTableHeaderCaption"><?php echo $laudo->qt_horas->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_qt_horas" class="laudo_qt_horas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->qt_horas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->qt_horas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->qt_horas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->qt_prazoMeses->Visible) { // qt_prazoMeses ?>
	<?php if ($laudo->SortUrl($laudo->qt_prazoMeses) == "") { ?>
		<td><div id="elh_laudo_qt_prazoMeses" class="laudo_qt_prazoMeses"><div class="ewTableHeaderCaption"><?php echo $laudo->qt_prazoMeses->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_qt_prazoMeses" class="laudo_qt_prazoMeses">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->qt_prazoMeses->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->qt_prazoMeses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->qt_prazoMeses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->qt_prazoDias->Visible) { // qt_prazoDias ?>
	<?php if ($laudo->SortUrl($laudo->qt_prazoDias) == "") { ?>
		<td><div id="elh_laudo_qt_prazoDias" class="laudo_qt_prazoDias"><div class="ewTableHeaderCaption"><?php echo $laudo->qt_prazoDias->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_qt_prazoDias" class="laudo_qt_prazoDias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->qt_prazoDias->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->qt_prazoDias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->qt_prazoDias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->vr_contratacao->Visible) { // vr_contratacao ?>
	<?php if ($laudo->SortUrl($laudo->vr_contratacao) == "") { ?>
		<td><div id="elh_laudo_vr_contratacao" class="laudo_vr_contratacao"><div class="ewTableHeaderCaption"><?php echo $laudo->vr_contratacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_vr_contratacao" class="laudo_vr_contratacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->vr_contratacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->vr_contratacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->vr_contratacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<?php if ($laudo->SortUrl($laudo->nu_usuarioResp) == "") { ?>
		<td><div id="elh_laudo_nu_usuarioResp" class="laudo_nu_usuarioResp"><div class="ewTableHeaderCaption"><?php echo $laudo->nu_usuarioResp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_nu_usuarioResp" class="laudo_nu_usuarioResp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->nu_usuarioResp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->nu_usuarioResp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->nu_usuarioResp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->dt_inicioSolicitacao->Visible) { // dt_inicioSolicitacao ?>
	<?php if ($laudo->SortUrl($laudo->dt_inicioSolicitacao) == "") { ?>
		<td><div id="elh_laudo_dt_inicioSolicitacao" class="laudo_dt_inicioSolicitacao"><div class="ewTableHeaderCaption"><?php echo $laudo->dt_inicioSolicitacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_dt_inicioSolicitacao" class="laudo_dt_inicioSolicitacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->dt_inicioSolicitacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->dt_inicioSolicitacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->dt_inicioSolicitacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->dt_inicioContagem->Visible) { // dt_inicioContagem ?>
	<?php if ($laudo->SortUrl($laudo->dt_inicioContagem) == "") { ?>
		<td><div id="elh_laudo_dt_inicioContagem" class="laudo_dt_inicioContagem"><div class="ewTableHeaderCaption"><?php echo $laudo->dt_inicioContagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_dt_inicioContagem" class="laudo_dt_inicioContagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->dt_inicioContagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->dt_inicioContagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->dt_inicioContagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->dt_emissao->Visible) { // dt_emissao ?>
	<?php if ($laudo->SortUrl($laudo->dt_emissao) == "") { ?>
		<td><div id="elh_laudo_dt_emissao" class="laudo_dt_emissao"><div class="ewTableHeaderCaption"><?php echo $laudo->dt_emissao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_dt_emissao" class="laudo_dt_emissao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->dt_emissao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->dt_emissao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->dt_emissao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->hh_emissao->Visible) { // hh_emissao ?>
	<?php if ($laudo->SortUrl($laudo->hh_emissao) == "") { ?>
		<td><div id="elh_laudo_hh_emissao" class="laudo_hh_emissao"><div class="ewTableHeaderCaption"><?php echo $laudo->hh_emissao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_hh_emissao" class="laudo_hh_emissao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->hh_emissao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->hh_emissao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->hh_emissao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->ic_tamanho->Visible) { // ic_tamanho ?>
	<?php if ($laudo->SortUrl($laudo->ic_tamanho) == "") { ?>
		<td><div id="elh_laudo_ic_tamanho" class="laudo_ic_tamanho"><div class="ewTableHeaderCaption"><?php echo $laudo->ic_tamanho->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_ic_tamanho" class="laudo_ic_tamanho">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->ic_tamanho->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->ic_tamanho->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->ic_tamanho->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->ic_esforco->Visible) { // ic_esforco ?>
	<?php if ($laudo->SortUrl($laudo->ic_esforco) == "") { ?>
		<td><div id="elh_laudo_ic_esforco" class="laudo_ic_esforco"><div class="ewTableHeaderCaption"><?php echo $laudo->ic_esforco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_ic_esforco" class="laudo_ic_esforco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->ic_esforco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->ic_esforco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->ic_esforco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->ic_prazo->Visible) { // ic_prazo ?>
	<?php if ($laudo->SortUrl($laudo->ic_prazo) == "") { ?>
		<td><div id="elh_laudo_ic_prazo" class="laudo_ic_prazo"><div class="ewTableHeaderCaption"><?php echo $laudo->ic_prazo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_ic_prazo" class="laudo_ic_prazo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->ic_prazo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->ic_prazo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->ic_prazo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->ic_custo->Visible) { // ic_custo ?>
	<?php if ($laudo->SortUrl($laudo->ic_custo) == "") { ?>
		<td><div id="elh_laudo_ic_custo" class="laudo_ic_custo"><div class="ewTableHeaderCaption"><?php echo $laudo->ic_custo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_laudo_ic_custo" class="laudo_ic_custo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->ic_custo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->ic_custo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->ic_custo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$laudo_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$laudo_grid->StartRec = 1;
$laudo_grid->StopRec = $laudo_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($laudo_grid->FormKeyCountName) && ($laudo->CurrentAction == "gridadd" || $laudo->CurrentAction == "gridedit" || $laudo->CurrentAction == "F")) {
		$laudo_grid->KeyCount = $objForm->GetValue($laudo_grid->FormKeyCountName);
		$laudo_grid->StopRec = $laudo_grid->StartRec + $laudo_grid->KeyCount - 1;
	}
}
$laudo_grid->RecCnt = $laudo_grid->StartRec - 1;
if ($laudo_grid->Recordset && !$laudo_grid->Recordset->EOF) {
	$laudo_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $laudo_grid->StartRec > 1)
		$laudo_grid->Recordset->Move($laudo_grid->StartRec - 1);
} elseif (!$laudo->AllowAddDeleteRow && $laudo_grid->StopRec == 0) {
	$laudo_grid->StopRec = $laudo->GridAddRowCount;
}

// Initialize aggregate
$laudo->RowType = EW_ROWTYPE_AGGREGATEINIT;
$laudo->ResetAttrs();
$laudo_grid->RenderRow();
if ($laudo->CurrentAction == "gridadd")
	$laudo_grid->RowIndex = 0;
if ($laudo->CurrentAction == "gridedit")
	$laudo_grid->RowIndex = 0;
while ($laudo_grid->RecCnt < $laudo_grid->StopRec) {
	$laudo_grid->RecCnt++;
	if (intval($laudo_grid->RecCnt) >= intval($laudo_grid->StartRec)) {
		$laudo_grid->RowCnt++;
		if ($laudo->CurrentAction == "gridadd" || $laudo->CurrentAction == "gridedit" || $laudo->CurrentAction == "F") {
			$laudo_grid->RowIndex++;
			$objForm->Index = $laudo_grid->RowIndex;
			if ($objForm->HasValue($laudo_grid->FormActionName))
				$laudo_grid->RowAction = strval($objForm->GetValue($laudo_grid->FormActionName));
			elseif ($laudo->CurrentAction == "gridadd")
				$laudo_grid->RowAction = "insert";
			else
				$laudo_grid->RowAction = "";
		}

		// Set up key count
		$laudo_grid->KeyCount = $laudo_grid->RowIndex;

		// Init row class and style
		$laudo->ResetAttrs();
		$laudo->CssClass = "";
		if ($laudo->CurrentAction == "gridadd") {
			if ($laudo->CurrentMode == "copy") {
				$laudo_grid->LoadRowValues($laudo_grid->Recordset); // Load row values
				$laudo_grid->SetRecordKey($laudo_grid->RowOldKey, $laudo_grid->Recordset); // Set old record key
			} else {
				$laudo_grid->LoadDefaultValues(); // Load default values
				$laudo_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$laudo_grid->LoadRowValues($laudo_grid->Recordset); // Load row values
		}
		$laudo->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($laudo->CurrentAction == "gridadd") // Grid add
			$laudo->RowType = EW_ROWTYPE_ADD; // Render add
		if ($laudo->CurrentAction == "gridadd" && $laudo->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$laudo_grid->RestoreCurrentRowFormValues($laudo_grid->RowIndex); // Restore form values
		if ($laudo->CurrentAction == "gridedit") { // Grid edit
			if ($laudo->EventCancelled) {
				$laudo_grid->RestoreCurrentRowFormValues($laudo_grid->RowIndex); // Restore form values
			}
			if ($laudo_grid->RowAction == "insert")
				$laudo->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$laudo->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($laudo->CurrentAction == "gridedit" && ($laudo->RowType == EW_ROWTYPE_EDIT || $laudo->RowType == EW_ROWTYPE_ADD) && $laudo->EventCancelled) // Update failed
			$laudo_grid->RestoreCurrentRowFormValues($laudo_grid->RowIndex); // Restore form values
		if ($laudo->RowType == EW_ROWTYPE_EDIT) // Edit row
			$laudo_grid->EditRowCnt++;
		if ($laudo->CurrentAction == "F") // Confirm row
			$laudo_grid->RestoreCurrentRowFormValues($laudo_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$laudo->RowAttrs = array_merge($laudo->RowAttrs, array('data-rowindex'=>$laudo_grid->RowCnt, 'id'=>'r' . $laudo_grid->RowCnt . '_laudo', 'data-rowtype'=>$laudo->RowType));

		// Render row
		$laudo_grid->RenderRow();

		// Render list options
		$laudo_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($laudo_grid->RowAction <> "delete" && $laudo_grid->RowAction <> "insertdelete" && !($laudo_grid->RowAction == "insert" && $laudo->CurrentAction == "F" && $laudo_grid->EmptyRow())) {
?>
	<tr<?php echo $laudo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$laudo_grid->ListOptions->Render("body", "left", $laudo_grid->RowCnt);
?>
	<?php if ($laudo->nu_solicitacao->Visible) { // nu_solicitacao ?>
		<td<?php echo $laudo->nu_solicitacao->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($laudo->nu_solicitacao->getSessionValue() <> "") { ?>
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_solicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao"<?php echo $laudo->nu_solicitacao->EditAttributes() ?>>
<?php
if (is_array($laudo->nu_solicitacao->EditValue)) {
	$arwrk = $laudo->nu_solicitacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->nu_solicitacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $laudo->nu_solicitacao->OldValue = "";
?>
</select>
<script type="text/javascript">
flaudogrid.Lists["x_nu_solicitacao"].Options = <?php echo (is_array($laudo->nu_solicitacao->EditValue)) ? ew_ArrayToJson($laudo->nu_solicitacao->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_nu_solicitacao" name="o<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" id="o<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->EditValue ?></span>
<input type="hidden" data-field="x_nu_solicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->CurrentValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_solicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->FormValue) ?>">
<input type="hidden" data-field="x_nu_solicitacao" name="o<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" id="o<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->nu_versao->Visible) { // nu_versao ?>
		<td<?php echo $laudo->nu_versao->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_nu_versao" class="control-group laudo_nu_versao">
<input type="text" data-field="x_nu_versao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" size="30" placeholder="<?php echo $laudo->nu_versao->PlaceHolder ?>" value="<?php echo $laudo->nu_versao->EditValue ?>"<?php echo $laudo->nu_versao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $laudo_grid->RowIndex ?>_nu_versao" id="o<?php echo $laudo_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($laudo->nu_versao->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_nu_versao" class="control-group laudo_nu_versao">
<span<?php echo $laudo->nu_versao->ViewAttributes() ?>>
<?php echo $laudo->nu_versao->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($laudo->nu_versao->CurrentValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->nu_versao->ViewAttributes() ?>>
<?php echo $laudo->nu_versao->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($laudo->nu_versao->FormValue) ?>">
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $laudo_grid->RowIndex ?>_nu_versao" id="o<?php echo $laudo_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($laudo->nu_versao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->qt_pf->Visible) { // qt_pf ?>
		<td<?php echo $laudo->qt_pf->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_qt_pf" class="control-group laudo_qt_pf">
<input type="text" data-field="x_qt_pf" name="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" id="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" size="30" placeholder="<?php echo $laudo->qt_pf->PlaceHolder ?>" value="<?php echo $laudo->qt_pf->EditValue ?>"<?php echo $laudo->qt_pf->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_pf" name="o<?php echo $laudo_grid->RowIndex ?>_qt_pf" id="o<?php echo $laudo_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($laudo->qt_pf->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_qt_pf" class="control-group laudo_qt_pf">
<input type="text" data-field="x_qt_pf" name="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" id="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" size="30" placeholder="<?php echo $laudo->qt_pf->PlaceHolder ?>" value="<?php echo $laudo->qt_pf->EditValue ?>"<?php echo $laudo->qt_pf->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->qt_pf->ViewAttributes() ?>>
<?php echo $laudo->qt_pf->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_pf" name="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" id="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($laudo->qt_pf->FormValue) ?>">
<input type="hidden" data-field="x_qt_pf" name="o<?php echo $laudo_grid->RowIndex ?>_qt_pf" id="o<?php echo $laudo_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($laudo->qt_pf->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->qt_horas->Visible) { // qt_horas ?>
		<td<?php echo $laudo->qt_horas->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_qt_horas" class="control-group laudo_qt_horas">
<input type="text" data-field="x_qt_horas" name="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" id="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" size="30" placeholder="<?php echo $laudo->qt_horas->PlaceHolder ?>" value="<?php echo $laudo->qt_horas->EditValue ?>"<?php echo $laudo->qt_horas->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_horas" name="o<?php echo $laudo_grid->RowIndex ?>_qt_horas" id="o<?php echo $laudo_grid->RowIndex ?>_qt_horas" value="<?php echo ew_HtmlEncode($laudo->qt_horas->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_qt_horas" class="control-group laudo_qt_horas">
<input type="text" data-field="x_qt_horas" name="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" id="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" size="30" placeholder="<?php echo $laudo->qt_horas->PlaceHolder ?>" value="<?php echo $laudo->qt_horas->EditValue ?>"<?php echo $laudo->qt_horas->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->qt_horas->ViewAttributes() ?>>
<?php echo $laudo->qt_horas->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_horas" name="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" id="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" value="<?php echo ew_HtmlEncode($laudo->qt_horas->FormValue) ?>">
<input type="hidden" data-field="x_qt_horas" name="o<?php echo $laudo_grid->RowIndex ?>_qt_horas" id="o<?php echo $laudo_grid->RowIndex ?>_qt_horas" value="<?php echo ew_HtmlEncode($laudo->qt_horas->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->qt_prazoMeses->Visible) { // qt_prazoMeses ?>
		<td<?php echo $laudo->qt_prazoMeses->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_qt_prazoMeses" class="control-group laudo_qt_prazoMeses">
<input type="text" data-field="x_qt_prazoMeses" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" size="30" placeholder="<?php echo $laudo->qt_prazoMeses->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoMeses->EditValue ?>"<?php echo $laudo->qt_prazoMeses->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_prazoMeses" name="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" id="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" value="<?php echo ew_HtmlEncode($laudo->qt_prazoMeses->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_qt_prazoMeses" class="control-group laudo_qt_prazoMeses">
<input type="text" data-field="x_qt_prazoMeses" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" size="30" placeholder="<?php echo $laudo->qt_prazoMeses->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoMeses->EditValue ?>"<?php echo $laudo->qt_prazoMeses->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->qt_prazoMeses->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoMeses->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_prazoMeses" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" value="<?php echo ew_HtmlEncode($laudo->qt_prazoMeses->FormValue) ?>">
<input type="hidden" data-field="x_qt_prazoMeses" name="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" id="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" value="<?php echo ew_HtmlEncode($laudo->qt_prazoMeses->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->qt_prazoDias->Visible) { // qt_prazoDias ?>
		<td<?php echo $laudo->qt_prazoDias->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_qt_prazoDias" class="control-group laudo_qt_prazoDias">
<input type="text" data-field="x_qt_prazoDias" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" size="30" placeholder="<?php echo $laudo->qt_prazoDias->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoDias->EditValue ?>"<?php echo $laudo->qt_prazoDias->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_prazoDias" name="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" id="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" value="<?php echo ew_HtmlEncode($laudo->qt_prazoDias->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_qt_prazoDias" class="control-group laudo_qt_prazoDias">
<input type="text" data-field="x_qt_prazoDias" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" size="30" placeholder="<?php echo $laudo->qt_prazoDias->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoDias->EditValue ?>"<?php echo $laudo->qt_prazoDias->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->qt_prazoDias->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoDias->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_prazoDias" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" value="<?php echo ew_HtmlEncode($laudo->qt_prazoDias->FormValue) ?>">
<input type="hidden" data-field="x_qt_prazoDias" name="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" id="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" value="<?php echo ew_HtmlEncode($laudo->qt_prazoDias->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->vr_contratacao->Visible) { // vr_contratacao ?>
		<td<?php echo $laudo->vr_contratacao->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_vr_contratacao" class="control-group laudo_vr_contratacao">
<input type="text" data-field="x_vr_contratacao" name="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" id="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" size="30" placeholder="<?php echo $laudo->vr_contratacao->PlaceHolder ?>" value="<?php echo $laudo->vr_contratacao->EditValue ?>"<?php echo $laudo->vr_contratacao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_contratacao" name="o<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" id="o<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" value="<?php echo ew_HtmlEncode($laudo->vr_contratacao->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_vr_contratacao" class="control-group laudo_vr_contratacao">
<input type="text" data-field="x_vr_contratacao" name="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" id="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" size="30" placeholder="<?php echo $laudo->vr_contratacao->PlaceHolder ?>" value="<?php echo $laudo->vr_contratacao->EditValue ?>"<?php echo $laudo->vr_contratacao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->vr_contratacao->ViewAttributes() ?>>
<?php echo $laudo->vr_contratacao->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_contratacao" name="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" id="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" value="<?php echo ew_HtmlEncode($laudo->vr_contratacao->FormValue) ?>">
<input type="hidden" data-field="x_vr_contratacao" name="o<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" id="o<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" value="<?php echo ew_HtmlEncode($laudo->vr_contratacao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
		<td<?php echo $laudo->nu_usuarioResp->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_usuarioResp" name="o<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" id="o<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($laudo->nu_usuarioResp->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $laudo->nu_usuarioResp->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_usuarioResp" name="x<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" id="x<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($laudo->nu_usuarioResp->FormValue) ?>">
<input type="hidden" data-field="x_nu_usuarioResp" name="o<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" id="o<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($laudo->nu_usuarioResp->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->dt_inicioSolicitacao->Visible) { // dt_inicioSolicitacao ?>
		<td<?php echo $laudo->dt_inicioSolicitacao->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_dt_inicioSolicitacao" class="control-group laudo_dt_inicioSolicitacao">
<input type="text" data-field="x_dt_inicioSolicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" size="30" maxlength="10" placeholder="<?php echo $laudo->dt_inicioSolicitacao->PlaceHolder ?>" value="<?php echo $laudo->dt_inicioSolicitacao->EditValue ?>"<?php echo $laudo->dt_inicioSolicitacao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_dt_inicioSolicitacao" name="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" id="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" value="<?php echo ew_HtmlEncode($laudo->dt_inicioSolicitacao->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_dt_inicioSolicitacao" class="control-group laudo_dt_inicioSolicitacao">
<input type="text" data-field="x_dt_inicioSolicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" size="30" maxlength="10" placeholder="<?php echo $laudo->dt_inicioSolicitacao->PlaceHolder ?>" value="<?php echo $laudo->dt_inicioSolicitacao->EditValue ?>"<?php echo $laudo->dt_inicioSolicitacao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->dt_inicioSolicitacao->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioSolicitacao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dt_inicioSolicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" value="<?php echo ew_HtmlEncode($laudo->dt_inicioSolicitacao->FormValue) ?>">
<input type="hidden" data-field="x_dt_inicioSolicitacao" name="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" id="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" value="<?php echo ew_HtmlEncode($laudo->dt_inicioSolicitacao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->dt_inicioContagem->Visible) { // dt_inicioContagem ?>
		<td<?php echo $laudo->dt_inicioContagem->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_dt_inicioContagem" class="control-group laudo_dt_inicioContagem">
<input type="text" data-field="x_dt_inicioContagem" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" size="30" maxlength="10" placeholder="<?php echo $laudo->dt_inicioContagem->PlaceHolder ?>" value="<?php echo $laudo->dt_inicioContagem->EditValue ?>"<?php echo $laudo->dt_inicioContagem->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_dt_inicioContagem" name="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" id="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" value="<?php echo ew_HtmlEncode($laudo->dt_inicioContagem->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_dt_inicioContagem" class="control-group laudo_dt_inicioContagem">
<input type="text" data-field="x_dt_inicioContagem" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" size="30" maxlength="10" placeholder="<?php echo $laudo->dt_inicioContagem->PlaceHolder ?>" value="<?php echo $laudo->dt_inicioContagem->EditValue ?>"<?php echo $laudo->dt_inicioContagem->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->dt_inicioContagem->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioContagem->ListViewValue() ?></span>
<input type="hidden" data-field="x_dt_inicioContagem" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" value="<?php echo ew_HtmlEncode($laudo->dt_inicioContagem->FormValue) ?>">
<input type="hidden" data-field="x_dt_inicioContagem" name="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" id="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" value="<?php echo ew_HtmlEncode($laudo->dt_inicioContagem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->dt_emissao->Visible) { // dt_emissao ?>
		<td<?php echo $laudo->dt_emissao->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_dt_emissao" name="o<?php echo $laudo_grid->RowIndex ?>_dt_emissao" id="o<?php echo $laudo_grid->RowIndex ?>_dt_emissao" value="<?php echo ew_HtmlEncode($laudo->dt_emissao->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->dt_emissao->ViewAttributes() ?>>
<?php echo $laudo->dt_emissao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dt_emissao" name="x<?php echo $laudo_grid->RowIndex ?>_dt_emissao" id="x<?php echo $laudo_grid->RowIndex ?>_dt_emissao" value="<?php echo ew_HtmlEncode($laudo->dt_emissao->FormValue) ?>">
<input type="hidden" data-field="x_dt_emissao" name="o<?php echo $laudo_grid->RowIndex ?>_dt_emissao" id="o<?php echo $laudo_grid->RowIndex ?>_dt_emissao" value="<?php echo ew_HtmlEncode($laudo->dt_emissao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->hh_emissao->Visible) { // hh_emissao ?>
		<td<?php echo $laudo->hh_emissao->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_hh_emissao" name="o<?php echo $laudo_grid->RowIndex ?>_hh_emissao" id="o<?php echo $laudo_grid->RowIndex ?>_hh_emissao" value="<?php echo ew_HtmlEncode($laudo->hh_emissao->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->hh_emissao->ViewAttributes() ?>>
<?php echo $laudo->hh_emissao->ListViewValue() ?></span>
<input type="hidden" data-field="x_hh_emissao" name="x<?php echo $laudo_grid->RowIndex ?>_hh_emissao" id="x<?php echo $laudo_grid->RowIndex ?>_hh_emissao" value="<?php echo ew_HtmlEncode($laudo->hh_emissao->FormValue) ?>">
<input type="hidden" data-field="x_hh_emissao" name="o<?php echo $laudo_grid->RowIndex ?>_hh_emissao" id="o<?php echo $laudo_grid->RowIndex ?>_hh_emissao" value="<?php echo ew_HtmlEncode($laudo->hh_emissao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->ic_tamanho->Visible) { // ic_tamanho ?>
		<td<?php echo $laudo->ic_tamanho->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_ic_tamanho" class="control-group laudo_ic_tamanho">
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" value="{value}"<?php echo $laudo->ic_tamanho->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_tamanho->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_tamanho->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tamanho" name="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_tamanho->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_tamanho->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_tamanho" name="o<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="o<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" value="<?php echo ew_HtmlEncode($laudo->ic_tamanho->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_ic_tamanho" class="control-group laudo_ic_tamanho">
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" value="{value}"<?php echo $laudo->ic_tamanho->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_tamanho->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_tamanho->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tamanho" name="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_tamanho->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_tamanho->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->ic_tamanho->ViewAttributes() ?>>
<?php echo $laudo->ic_tamanho->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_tamanho" name="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" value="<?php echo ew_HtmlEncode($laudo->ic_tamanho->FormValue) ?>">
<input type="hidden" data-field="x_ic_tamanho" name="o<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="o<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" value="<?php echo ew_HtmlEncode($laudo->ic_tamanho->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->ic_esforco->Visible) { // ic_esforco ?>
		<td<?php echo $laudo->ic_esforco->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_ic_esforco" class="control-group laudo_ic_esforco">
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" value="{value}"<?php echo $laudo->ic_esforco->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_esforco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_esforco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_esforco" name="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_esforco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_esforco->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_esforco" name="o<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="o<?php echo $laudo_grid->RowIndex ?>_ic_esforco" value="<?php echo ew_HtmlEncode($laudo->ic_esforco->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_ic_esforco" class="control-group laudo_ic_esforco">
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" value="{value}"<?php echo $laudo->ic_esforco->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_esforco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_esforco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_esforco" name="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_esforco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_esforco->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->ic_esforco->ViewAttributes() ?>>
<?php echo $laudo->ic_esforco->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_esforco" name="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" value="<?php echo ew_HtmlEncode($laudo->ic_esforco->FormValue) ?>">
<input type="hidden" data-field="x_ic_esforco" name="o<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="o<?php echo $laudo_grid->RowIndex ?>_ic_esforco" value="<?php echo ew_HtmlEncode($laudo->ic_esforco->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->ic_prazo->Visible) { // ic_prazo ?>
		<td<?php echo $laudo->ic_prazo->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_ic_prazo" class="control-group laudo_ic_prazo">
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" value="{value}"<?php echo $laudo->ic_prazo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_prazo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_prazo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_prazo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_prazo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_prazo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_prazo" name="o<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="o<?php echo $laudo_grid->RowIndex ?>_ic_prazo" value="<?php echo ew_HtmlEncode($laudo->ic_prazo->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_ic_prazo" class="control-group laudo_ic_prazo">
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" value="{value}"<?php echo $laudo->ic_prazo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_prazo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_prazo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_prazo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_prazo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_prazo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->ic_prazo->ViewAttributes() ?>>
<?php echo $laudo->ic_prazo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_prazo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" value="<?php echo ew_HtmlEncode($laudo->ic_prazo->FormValue) ?>">
<input type="hidden" data-field="x_ic_prazo" name="o<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="o<?php echo $laudo_grid->RowIndex ?>_ic_prazo" value="<?php echo ew_HtmlEncode($laudo->ic_prazo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->ic_custo->Visible) { // ic_custo ?>
		<td<?php echo $laudo->ic_custo->CellAttributes() ?>>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_ic_custo" class="control-group laudo_ic_custo">
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_custo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" value="{value}"<?php echo $laudo->ic_custo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_custo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_custo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_custo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_custo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_custo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_custo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_custo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_custo" name="o<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="o<?php echo $laudo_grid->RowIndex ?>_ic_custo" value="<?php echo ew_HtmlEncode($laudo->ic_custo->OldValue) ?>">
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $laudo_grid->RowCnt ?>_laudo_ic_custo" class="control-group laudo_ic_custo">
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_custo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" value="{value}"<?php echo $laudo->ic_custo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_custo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_custo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_custo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_custo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_custo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_custo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_custo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($laudo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $laudo->ic_custo->ViewAttributes() ?>>
<?php echo $laudo->ic_custo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_custo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" value="<?php echo ew_HtmlEncode($laudo->ic_custo->FormValue) ?>">
<input type="hidden" data-field="x_ic_custo" name="o<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="o<?php echo $laudo_grid->RowIndex ?>_ic_custo" value="<?php echo ew_HtmlEncode($laudo->ic_custo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $laudo_grid->PageObjName . "_row_" . $laudo_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$laudo_grid->ListOptions->Render("body", "right", $laudo_grid->RowCnt);
?>
	</tr>
<?php if ($laudo->RowType == EW_ROWTYPE_ADD || $laudo->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
flaudogrid.UpdateOpts(<?php echo $laudo_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($laudo->CurrentAction <> "gridadd" || $laudo->CurrentMode == "copy")
		if (!$laudo_grid->Recordset->EOF) $laudo_grid->Recordset->MoveNext();
}
?>
<?php
	if ($laudo->CurrentMode == "add" || $laudo->CurrentMode == "copy" || $laudo->CurrentMode == "edit") {
		$laudo_grid->RowIndex = '$rowindex$';
		$laudo_grid->LoadDefaultValues();

		// Set row properties
		$laudo->ResetAttrs();
		$laudo->RowAttrs = array_merge($laudo->RowAttrs, array('data-rowindex'=>$laudo_grid->RowIndex, 'id'=>'r0_laudo', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($laudo->RowAttrs["class"], "ewTemplate");
		$laudo->RowType = EW_ROWTYPE_ADD;

		// Render row
		$laudo_grid->RenderRow();

		// Render list options
		$laudo_grid->RenderListOptions();
		$laudo_grid->StartRowCnt = 0;
?>
	<tr<?php echo $laudo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$laudo_grid->ListOptions->Render("body", "left", $laudo_grid->RowIndex);
?>
	<?php if ($laudo->nu_solicitacao->Visible) { // nu_solicitacao ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<?php if ($laudo->nu_solicitacao->getSessionValue() <> "") { ?>
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_solicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao"<?php echo $laudo->nu_solicitacao->EditAttributes() ?>>
<?php
if (is_array($laudo->nu_solicitacao->EditValue)) {
	$arwrk = $laudo->nu_solicitacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->nu_solicitacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $laudo->nu_solicitacao->OldValue = "";
?>
</select>
<script type="text/javascript">
flaudogrid.Lists["x_nu_solicitacao"].Options = <?php echo (is_array($laudo->nu_solicitacao->EditValue)) ? ew_ArrayToJson($laudo->nu_solicitacao->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->ViewValue ?></span>
<input type="hidden" data-field="x_nu_solicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_solicitacao" name="o<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" id="o<?php echo $laudo_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->nu_versao->Visible) { // nu_versao ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_versao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" size="30" placeholder="<?php echo $laudo->nu_versao->PlaceHolder ?>" value="<?php echo $laudo->nu_versao->EditValue ?>"<?php echo $laudo->nu_versao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $laudo->nu_versao->ViewAttributes() ?>>
<?php echo $laudo->nu_versao->ViewValue ?></span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" id="x<?php echo $laudo_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($laudo->nu_versao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $laudo_grid->RowIndex ?>_nu_versao" id="o<?php echo $laudo_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($laudo->nu_versao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->qt_pf->Visible) { // qt_pf ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_pf" name="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" id="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" size="30" placeholder="<?php echo $laudo->qt_pf->PlaceHolder ?>" value="<?php echo $laudo->qt_pf->EditValue ?>"<?php echo $laudo->qt_pf->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $laudo->qt_pf->ViewAttributes() ?>>
<?php echo $laudo->qt_pf->ViewValue ?></span>
<input type="hidden" data-field="x_qt_pf" name="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" id="x<?php echo $laudo_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($laudo->qt_pf->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_pf" name="o<?php echo $laudo_grid->RowIndex ?>_qt_pf" id="o<?php echo $laudo_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($laudo->qt_pf->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->qt_horas->Visible) { // qt_horas ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_horas" name="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" id="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" size="30" placeholder="<?php echo $laudo->qt_horas->PlaceHolder ?>" value="<?php echo $laudo->qt_horas->EditValue ?>"<?php echo $laudo->qt_horas->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $laudo->qt_horas->ViewAttributes() ?>>
<?php echo $laudo->qt_horas->ViewValue ?></span>
<input type="hidden" data-field="x_qt_horas" name="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" id="x<?php echo $laudo_grid->RowIndex ?>_qt_horas" value="<?php echo ew_HtmlEncode($laudo->qt_horas->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_horas" name="o<?php echo $laudo_grid->RowIndex ?>_qt_horas" id="o<?php echo $laudo_grid->RowIndex ?>_qt_horas" value="<?php echo ew_HtmlEncode($laudo->qt_horas->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->qt_prazoMeses->Visible) { // qt_prazoMeses ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_prazoMeses" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" size="30" placeholder="<?php echo $laudo->qt_prazoMeses->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoMeses->EditValue ?>"<?php echo $laudo->qt_prazoMeses->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $laudo->qt_prazoMeses->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoMeses->ViewValue ?></span>
<input type="hidden" data-field="x_qt_prazoMeses" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" value="<?php echo ew_HtmlEncode($laudo->qt_prazoMeses->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_prazoMeses" name="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" id="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoMeses" value="<?php echo ew_HtmlEncode($laudo->qt_prazoMeses->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->qt_prazoDias->Visible) { // qt_prazoDias ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_prazoDias" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" size="30" placeholder="<?php echo $laudo->qt_prazoDias->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoDias->EditValue ?>"<?php echo $laudo->qt_prazoDias->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $laudo->qt_prazoDias->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoDias->ViewValue ?></span>
<input type="hidden" data-field="x_qt_prazoDias" name="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" id="x<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" value="<?php echo ew_HtmlEncode($laudo->qt_prazoDias->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_prazoDias" name="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" id="o<?php echo $laudo_grid->RowIndex ?>_qt_prazoDias" value="<?php echo ew_HtmlEncode($laudo->qt_prazoDias->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->vr_contratacao->Visible) { // vr_contratacao ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_contratacao" name="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" id="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" size="30" placeholder="<?php echo $laudo->vr_contratacao->PlaceHolder ?>" value="<?php echo $laudo->vr_contratacao->EditValue ?>"<?php echo $laudo->vr_contratacao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $laudo->vr_contratacao->ViewAttributes() ?>>
<?php echo $laudo->vr_contratacao->ViewValue ?></span>
<input type="hidden" data-field="x_vr_contratacao" name="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" id="x<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" value="<?php echo ew_HtmlEncode($laudo->vr_contratacao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_contratacao" name="o<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" id="o<?php echo $laudo_grid->RowIndex ?>_vr_contratacao" value="<?php echo ew_HtmlEncode($laudo->vr_contratacao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $laudo->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $laudo->nu_usuarioResp->ViewValue ?></span>
<input type="hidden" data-field="x_nu_usuarioResp" name="x<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" id="x<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($laudo->nu_usuarioResp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_usuarioResp" name="o<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" id="o<?php echo $laudo_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($laudo->nu_usuarioResp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->dt_inicioSolicitacao->Visible) { // dt_inicioSolicitacao ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_dt_inicioSolicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" size="30" maxlength="10" placeholder="<?php echo $laudo->dt_inicioSolicitacao->PlaceHolder ?>" value="<?php echo $laudo->dt_inicioSolicitacao->EditValue ?>"<?php echo $laudo->dt_inicioSolicitacao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $laudo->dt_inicioSolicitacao->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioSolicitacao->ViewValue ?></span>
<input type="hidden" data-field="x_dt_inicioSolicitacao" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" value="<?php echo ew_HtmlEncode($laudo->dt_inicioSolicitacao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dt_inicioSolicitacao" name="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" id="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioSolicitacao" value="<?php echo ew_HtmlEncode($laudo->dt_inicioSolicitacao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->dt_inicioContagem->Visible) { // dt_inicioContagem ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_dt_inicioContagem" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" size="30" maxlength="10" placeholder="<?php echo $laudo->dt_inicioContagem->PlaceHolder ?>" value="<?php echo $laudo->dt_inicioContagem->EditValue ?>"<?php echo $laudo->dt_inicioContagem->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $laudo->dt_inicioContagem->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioContagem->ViewValue ?></span>
<input type="hidden" data-field="x_dt_inicioContagem" name="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" id="x<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" value="<?php echo ew_HtmlEncode($laudo->dt_inicioContagem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dt_inicioContagem" name="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" id="o<?php echo $laudo_grid->RowIndex ?>_dt_inicioContagem" value="<?php echo ew_HtmlEncode($laudo->dt_inicioContagem->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->dt_emissao->Visible) { // dt_emissao ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $laudo->dt_emissao->ViewAttributes() ?>>
<?php echo $laudo->dt_emissao->ViewValue ?></span>
<input type="hidden" data-field="x_dt_emissao" name="x<?php echo $laudo_grid->RowIndex ?>_dt_emissao" id="x<?php echo $laudo_grid->RowIndex ?>_dt_emissao" value="<?php echo ew_HtmlEncode($laudo->dt_emissao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dt_emissao" name="o<?php echo $laudo_grid->RowIndex ?>_dt_emissao" id="o<?php echo $laudo_grid->RowIndex ?>_dt_emissao" value="<?php echo ew_HtmlEncode($laudo->dt_emissao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->hh_emissao->Visible) { // hh_emissao ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $laudo->hh_emissao->ViewAttributes() ?>>
<?php echo $laudo->hh_emissao->ViewValue ?></span>
<input type="hidden" data-field="x_hh_emissao" name="x<?php echo $laudo_grid->RowIndex ?>_hh_emissao" id="x<?php echo $laudo_grid->RowIndex ?>_hh_emissao" value="<?php echo ew_HtmlEncode($laudo->hh_emissao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_hh_emissao" name="o<?php echo $laudo_grid->RowIndex ?>_hh_emissao" id="o<?php echo $laudo_grid->RowIndex ?>_hh_emissao" value="<?php echo ew_HtmlEncode($laudo->hh_emissao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->ic_tamanho->Visible) { // ic_tamanho ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" value="{value}"<?php echo $laudo->ic_tamanho->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_tamanho->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_tamanho->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tamanho" name="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_tamanho->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_tamanho->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $laudo->ic_tamanho->ViewAttributes() ?>>
<?php echo $laudo->ic_tamanho->ViewValue ?></span>
<input type="hidden" data-field="x_ic_tamanho" name="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="x<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" value="<?php echo ew_HtmlEncode($laudo->ic_tamanho->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_tamanho" name="o<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" id="o<?php echo $laudo_grid->RowIndex ?>_ic_tamanho" value="<?php echo ew_HtmlEncode($laudo->ic_tamanho->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->ic_esforco->Visible) { // ic_esforco ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" value="{value}"<?php echo $laudo->ic_esforco->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_esforco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_esforco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_esforco" name="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_esforco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_esforco->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $laudo->ic_esforco->ViewAttributes() ?>>
<?php echo $laudo->ic_esforco->ViewValue ?></span>
<input type="hidden" data-field="x_ic_esforco" name="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="x<?php echo $laudo_grid->RowIndex ?>_ic_esforco" value="<?php echo ew_HtmlEncode($laudo->ic_esforco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_esforco" name="o<?php echo $laudo_grid->RowIndex ?>_ic_esforco" id="o<?php echo $laudo_grid->RowIndex ?>_ic_esforco" value="<?php echo ew_HtmlEncode($laudo->ic_esforco->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->ic_prazo->Visible) { // ic_prazo ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" value="{value}"<?php echo $laudo->ic_prazo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_prazo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_prazo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_prazo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_prazo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_prazo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $laudo->ic_prazo->ViewAttributes() ?>>
<?php echo $laudo->ic_prazo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_prazo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_prazo" value="<?php echo ew_HtmlEncode($laudo->ic_prazo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_prazo" name="o<?php echo $laudo_grid->RowIndex ?>_ic_prazo" id="o<?php echo $laudo_grid->RowIndex ?>_ic_prazo" value="<?php echo ew_HtmlEncode($laudo->ic_prazo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($laudo->ic_custo->Visible) { // ic_custo ?>
		<td>
<?php if ($laudo->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $laudo_grid->RowIndex ?>_ic_custo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" value="{value}"<?php echo $laudo->ic_custo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $laudo_grid->RowIndex ?>_ic_custo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $laudo->ic_custo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->ic_custo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_custo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_custo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $laudo->ic_custo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $laudo->ic_custo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $laudo->ic_custo->ViewAttributes() ?>>
<?php echo $laudo->ic_custo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_custo" name="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="x<?php echo $laudo_grid->RowIndex ?>_ic_custo" value="<?php echo ew_HtmlEncode($laudo->ic_custo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_custo" name="o<?php echo $laudo_grid->RowIndex ?>_ic_custo" id="o<?php echo $laudo_grid->RowIndex ?>_ic_custo" value="<?php echo ew_HtmlEncode($laudo->ic_custo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$laudo_grid->ListOptions->Render("body", "right", $laudo_grid->RowCnt);
?>
<script type="text/javascript">
flaudogrid.UpdateOpts(<?php echo $laudo_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($laudo->CurrentMode == "add" || $laudo->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $laudo_grid->FormKeyCountName ?>" id="<?php echo $laudo_grid->FormKeyCountName ?>" value="<?php echo $laudo_grid->KeyCount ?>">
<?php echo $laudo_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($laudo->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $laudo_grid->FormKeyCountName ?>" id="<?php echo $laudo_grid->FormKeyCountName ?>" value="<?php echo $laudo_grid->KeyCount ?>">
<?php echo $laudo_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($laudo->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="flaudogrid">
</div>
<?php

// Close recordset
if ($laudo_grid->Recordset)
	$laudo_grid->Recordset->Close();
?>
<?php if ($laudo_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($laudo_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($laudo->Export == "") { ?>
<script type="text/javascript">
flaudogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$laudo_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$laudo_grid->Page_Terminate();
?>
