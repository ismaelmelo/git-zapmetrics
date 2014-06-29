<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($estimativa_grid)) $estimativa_grid = new cestimativa_grid();

// Page init
$estimativa_grid->Page_Init();

// Page main
$estimativa_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$estimativa_grid->Page_Render();
?>
<?php if ($estimativa->Export == "") { ?>
<script type="text/javascript">

// Page object
var estimativa_grid = new ew_Page("estimativa_grid");
estimativa_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = estimativa_grid.PageID; // For backward compatibility

// Form object
var festimativagrid = new ew_Form("festimativagrid");
festimativagrid.FormKeyCountName = '<?php echo $estimativa_grid->FormKeyCountName ?>';

// Validate form
festimativagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ic_solicitacaoCritica");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->ic_solicitacaoCritica->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ambienteMaisRepresentativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->nu_ambienteMaisRepresentativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ambienteMaisRepresentativo");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->nu_ambienteMaisRepresentativo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_tamBase");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->qt_tamBase->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_tamBase");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->qt_tamBase->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_metPrazo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->nu_metPrazo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_doPf");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_doPf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pz_estimadoMeses");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->pz_estimadoMeses->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pz_estimadoDias");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->pz_estimadoDias->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMaximo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_ipMaximo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMedio");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_ipMedio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMinimo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_ipMinimo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipInformado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($estimativa->vr_ipInformado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipInformado");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_ipInformado->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_esforco");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->qt_esforco->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_custoDesenv");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_custoDesenv->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_outrosCustos");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_outrosCustos->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_custoTotal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->vr_custoTotal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_tamBaseFaturamento");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->qt_tamBaseFaturamento->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_recursosEquipe");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($estimativa->qt_recursosEquipe->FldErrMsg()) ?>");

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
festimativagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "ic_solicitacaoCritica", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_ambienteMaisRepresentativo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_tamBase", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_modeloCocomo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_metPrazo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_doPf", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pz_estimadoMeses", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pz_estimadoDias", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_ipMaximo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_ipMedio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_ipMinimo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_ipInformado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_esforco", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_custoDesenv", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_outrosCustos", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_custoTotal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_tamBaseFaturamento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_recursosEquipe", false)) return false;
	return true;
}

// Form_CustomValidate event
festimativagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
festimativagrid.ValidateRequired = true;
<?php } else { ?>
festimativagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
festimativagrid.Lists["x_nu_ambienteMaisRepresentativo"] = {"LinkField":"x_nu_ambiente","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($estimativa->getCurrentMasterTable() == "" && $estimativa_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $estimativa_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($estimativa->CurrentAction == "gridadd") {
	if ($estimativa->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$estimativa_grid->TotalRecs = $estimativa->SelectRecordCount();
			$estimativa_grid->Recordset = $estimativa_grid->LoadRecordset($estimativa_grid->StartRec-1, $estimativa_grid->DisplayRecs);
		} else {
			if ($estimativa_grid->Recordset = $estimativa_grid->LoadRecordset())
				$estimativa_grid->TotalRecs = $estimativa_grid->Recordset->RecordCount();
		}
		$estimativa_grid->StartRec = 1;
		$estimativa_grid->DisplayRecs = $estimativa_grid->TotalRecs;
	} else {
		$estimativa->CurrentFilter = "0=1";
		$estimativa_grid->StartRec = 1;
		$estimativa_grid->DisplayRecs = $estimativa->GridAddRowCount;
	}
	$estimativa_grid->TotalRecs = $estimativa_grid->DisplayRecs;
	$estimativa_grid->StopRec = $estimativa_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$estimativa_grid->TotalRecs = $estimativa->SelectRecordCount();
	} else {
		if ($estimativa_grid->Recordset = $estimativa_grid->LoadRecordset())
			$estimativa_grid->TotalRecs = $estimativa_grid->Recordset->RecordCount();
	}
	$estimativa_grid->StartRec = 1;
	$estimativa_grid->DisplayRecs = $estimativa_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$estimativa_grid->Recordset = $estimativa_grid->LoadRecordset($estimativa_grid->StartRec-1, $estimativa_grid->DisplayRecs);
}
$estimativa_grid->RenderOtherOptions();
?>
<?php $estimativa_grid->ShowPageHeader(); ?>
<?php
$estimativa_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="festimativagrid" class="ewForm form-horizontal">
<div id="gmp_estimativa" class="ewGridMiddlePanel">
<table id="tbl_estimativagrid" class="ewTable ewTableSeparate">
<?php echo $estimativa->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$estimativa_grid->RenderListOptions();

// Render list options (header, left)
$estimativa_grid->ListOptions->Render("header", "left");
?>
<?php if ($estimativa->ic_solicitacaoCritica->Visible) { // ic_solicitacaoCritica ?>
	<?php if ($estimativa->SortUrl($estimativa->ic_solicitacaoCritica) == "") { ?>
		<td><div id="elh_estimativa_ic_solicitacaoCritica" class="estimativa_ic_solicitacaoCritica"><div class="ewTableHeaderCaption"><?php echo $estimativa->ic_solicitacaoCritica->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_ic_solicitacaoCritica" class="estimativa_ic_solicitacaoCritica">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->ic_solicitacaoCritica->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->ic_solicitacaoCritica->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->ic_solicitacaoCritica->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->nu_ambienteMaisRepresentativo->Visible) { // nu_ambienteMaisRepresentativo ?>
	<?php if ($estimativa->SortUrl($estimativa->nu_ambienteMaisRepresentativo) == "") { ?>
		<td><div id="elh_estimativa_nu_ambienteMaisRepresentativo" class="estimativa_nu_ambienteMaisRepresentativo"><div class="ewTableHeaderCaption"><?php echo $estimativa->nu_ambienteMaisRepresentativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_nu_ambienteMaisRepresentativo" class="estimativa_nu_ambienteMaisRepresentativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->nu_ambienteMaisRepresentativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->nu_ambienteMaisRepresentativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->nu_ambienteMaisRepresentativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->qt_tamBase->Visible) { // qt_tamBase ?>
	<?php if ($estimativa->SortUrl($estimativa->qt_tamBase) == "") { ?>
		<td><div id="elh_estimativa_qt_tamBase" class="estimativa_qt_tamBase"><div class="ewTableHeaderCaption"><?php echo $estimativa->qt_tamBase->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_qt_tamBase" class="estimativa_qt_tamBase">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->qt_tamBase->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->qt_tamBase->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->qt_tamBase->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->ic_modeloCocomo->Visible) { // ic_modeloCocomo ?>
	<?php if ($estimativa->SortUrl($estimativa->ic_modeloCocomo) == "") { ?>
		<td><div id="elh_estimativa_ic_modeloCocomo" class="estimativa_ic_modeloCocomo"><div class="ewTableHeaderCaption"><?php echo $estimativa->ic_modeloCocomo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_ic_modeloCocomo" class="estimativa_ic_modeloCocomo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->ic_modeloCocomo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->ic_modeloCocomo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->ic_modeloCocomo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->nu_metPrazo->Visible) { // nu_metPrazo ?>
	<?php if ($estimativa->SortUrl($estimativa->nu_metPrazo) == "") { ?>
		<td><div id="elh_estimativa_nu_metPrazo" class="estimativa_nu_metPrazo"><div class="ewTableHeaderCaption"><?php echo $estimativa->nu_metPrazo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_nu_metPrazo" class="estimativa_nu_metPrazo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->nu_metPrazo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->nu_metPrazo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->nu_metPrazo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_doPf->Visible) { // vr_doPf ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_doPf) == "") { ?>
		<td><div id="elh_estimativa_vr_doPf" class="estimativa_vr_doPf"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_doPf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_vr_doPf" class="estimativa_vr_doPf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_doPf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_doPf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_doPf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->pz_estimadoMeses->Visible) { // pz_estimadoMeses ?>
	<?php if ($estimativa->SortUrl($estimativa->pz_estimadoMeses) == "") { ?>
		<td><div id="elh_estimativa_pz_estimadoMeses" class="estimativa_pz_estimadoMeses"><div class="ewTableHeaderCaption"><?php echo $estimativa->pz_estimadoMeses->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_pz_estimadoMeses" class="estimativa_pz_estimadoMeses">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->pz_estimadoMeses->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->pz_estimadoMeses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->pz_estimadoMeses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->pz_estimadoDias->Visible) { // pz_estimadoDias ?>
	<?php if ($estimativa->SortUrl($estimativa->pz_estimadoDias) == "") { ?>
		<td><div id="elh_estimativa_pz_estimadoDias" class="estimativa_pz_estimadoDias"><div class="ewTableHeaderCaption"><?php echo $estimativa->pz_estimadoDias->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_pz_estimadoDias" class="estimativa_pz_estimadoDias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->pz_estimadoDias->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->pz_estimadoDias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->pz_estimadoDias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_ipMaximo->Visible) { // vr_ipMaximo ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_ipMaximo) == "") { ?>
		<td><div id="elh_estimativa_vr_ipMaximo" class="estimativa_vr_ipMaximo"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMaximo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_vr_ipMaximo" class="estimativa_vr_ipMaximo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMaximo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_ipMaximo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_ipMaximo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_ipMedio->Visible) { // vr_ipMedio ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_ipMedio) == "") { ?>
		<td><div id="elh_estimativa_vr_ipMedio" class="estimativa_vr_ipMedio"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMedio->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_vr_ipMedio" class="estimativa_vr_ipMedio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMedio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_ipMedio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_ipMedio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_ipMinimo->Visible) { // vr_ipMinimo ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_ipMinimo) == "") { ?>
		<td><div id="elh_estimativa_vr_ipMinimo" class="estimativa_vr_ipMinimo"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMinimo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_vr_ipMinimo" class="estimativa_vr_ipMinimo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipMinimo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_ipMinimo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_ipMinimo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_ipInformado->Visible) { // vr_ipInformado ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_ipInformado) == "") { ?>
		<td><div id="elh_estimativa_vr_ipInformado" class="estimativa_vr_ipInformado"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipInformado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_vr_ipInformado" class="estimativa_vr_ipInformado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_ipInformado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_ipInformado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_ipInformado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->qt_esforco->Visible) { // qt_esforco ?>
	<?php if ($estimativa->SortUrl($estimativa->qt_esforco) == "") { ?>
		<td><div id="elh_estimativa_qt_esforco" class="estimativa_qt_esforco"><div class="ewTableHeaderCaption"><?php echo $estimativa->qt_esforco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_qt_esforco" class="estimativa_qt_esforco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->qt_esforco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->qt_esforco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->qt_esforco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_custoDesenv->Visible) { // vr_custoDesenv ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_custoDesenv) == "") { ?>
		<td><div id="elh_estimativa_vr_custoDesenv" class="estimativa_vr_custoDesenv"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_custoDesenv->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_vr_custoDesenv" class="estimativa_vr_custoDesenv">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_custoDesenv->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_custoDesenv->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_custoDesenv->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_outrosCustos->Visible) { // vr_outrosCustos ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_outrosCustos) == "") { ?>
		<td><div id="elh_estimativa_vr_outrosCustos" class="estimativa_vr_outrosCustos"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_outrosCustos->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_vr_outrosCustos" class="estimativa_vr_outrosCustos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_outrosCustos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_outrosCustos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_outrosCustos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->vr_custoTotal->Visible) { // vr_custoTotal ?>
	<?php if ($estimativa->SortUrl($estimativa->vr_custoTotal) == "") { ?>
		<td><div id="elh_estimativa_vr_custoTotal" class="estimativa_vr_custoTotal"><div class="ewTableHeaderCaption"><?php echo $estimativa->vr_custoTotal->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_vr_custoTotal" class="estimativa_vr_custoTotal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->vr_custoTotal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->vr_custoTotal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->vr_custoTotal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->qt_tamBaseFaturamento->Visible) { // qt_tamBaseFaturamento ?>
	<?php if ($estimativa->SortUrl($estimativa->qt_tamBaseFaturamento) == "") { ?>
		<td><div id="elh_estimativa_qt_tamBaseFaturamento" class="estimativa_qt_tamBaseFaturamento"><div class="ewTableHeaderCaption"><?php echo $estimativa->qt_tamBaseFaturamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_qt_tamBaseFaturamento" class="estimativa_qt_tamBaseFaturamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->qt_tamBaseFaturamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->qt_tamBaseFaturamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->qt_tamBaseFaturamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($estimativa->qt_recursosEquipe->Visible) { // qt_recursosEquipe ?>
	<?php if ($estimativa->SortUrl($estimativa->qt_recursosEquipe) == "") { ?>
		<td><div id="elh_estimativa_qt_recursosEquipe" class="estimativa_qt_recursosEquipe"><div class="ewTableHeaderCaption"><?php echo $estimativa->qt_recursosEquipe->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_estimativa_qt_recursosEquipe" class="estimativa_qt_recursosEquipe">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $estimativa->qt_recursosEquipe->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($estimativa->qt_recursosEquipe->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($estimativa->qt_recursosEquipe->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$estimativa_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$estimativa_grid->StartRec = 1;
$estimativa_grid->StopRec = $estimativa_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($estimativa_grid->FormKeyCountName) && ($estimativa->CurrentAction == "gridadd" || $estimativa->CurrentAction == "gridedit" || $estimativa->CurrentAction == "F")) {
		$estimativa_grid->KeyCount = $objForm->GetValue($estimativa_grid->FormKeyCountName);
		$estimativa_grid->StopRec = $estimativa_grid->StartRec + $estimativa_grid->KeyCount - 1;
	}
}
$estimativa_grid->RecCnt = $estimativa_grid->StartRec - 1;
if ($estimativa_grid->Recordset && !$estimativa_grid->Recordset->EOF) {
	$estimativa_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $estimativa_grid->StartRec > 1)
		$estimativa_grid->Recordset->Move($estimativa_grid->StartRec - 1);
} elseif (!$estimativa->AllowAddDeleteRow && $estimativa_grid->StopRec == 0) {
	$estimativa_grid->StopRec = $estimativa->GridAddRowCount;
}

// Initialize aggregate
$estimativa->RowType = EW_ROWTYPE_AGGREGATEINIT;
$estimativa->ResetAttrs();
$estimativa_grid->RenderRow();
if ($estimativa->CurrentAction == "gridadd")
	$estimativa_grid->RowIndex = 0;
if ($estimativa->CurrentAction == "gridedit")
	$estimativa_grid->RowIndex = 0;
while ($estimativa_grid->RecCnt < $estimativa_grid->StopRec) {
	$estimativa_grid->RecCnt++;
	if (intval($estimativa_grid->RecCnt) >= intval($estimativa_grid->StartRec)) {
		$estimativa_grid->RowCnt++;
		if ($estimativa->CurrentAction == "gridadd" || $estimativa->CurrentAction == "gridedit" || $estimativa->CurrentAction == "F") {
			$estimativa_grid->RowIndex++;
			$objForm->Index = $estimativa_grid->RowIndex;
			if ($objForm->HasValue($estimativa_grid->FormActionName))
				$estimativa_grid->RowAction = strval($objForm->GetValue($estimativa_grid->FormActionName));
			elseif ($estimativa->CurrentAction == "gridadd")
				$estimativa_grid->RowAction = "insert";
			else
				$estimativa_grid->RowAction = "";
		}

		// Set up key count
		$estimativa_grid->KeyCount = $estimativa_grid->RowIndex;

		// Init row class and style
		$estimativa->ResetAttrs();
		$estimativa->CssClass = "";
		if ($estimativa->CurrentAction == "gridadd") {
			if ($estimativa->CurrentMode == "copy") {
				$estimativa_grid->LoadRowValues($estimativa_grid->Recordset); // Load row values
				$estimativa_grid->SetRecordKey($estimativa_grid->RowOldKey, $estimativa_grid->Recordset); // Set old record key
			} else {
				$estimativa_grid->LoadDefaultValues(); // Load default values
				$estimativa_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$estimativa_grid->LoadRowValues($estimativa_grid->Recordset); // Load row values
		}
		$estimativa->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($estimativa->CurrentAction == "gridadd") // Grid add
			$estimativa->RowType = EW_ROWTYPE_ADD; // Render add
		if ($estimativa->CurrentAction == "gridadd" && $estimativa->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$estimativa_grid->RestoreCurrentRowFormValues($estimativa_grid->RowIndex); // Restore form values
		if ($estimativa->CurrentAction == "gridedit") { // Grid edit
			if ($estimativa->EventCancelled) {
				$estimativa_grid->RestoreCurrentRowFormValues($estimativa_grid->RowIndex); // Restore form values
			}
			if ($estimativa_grid->RowAction == "insert")
				$estimativa->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$estimativa->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($estimativa->CurrentAction == "gridedit" && ($estimativa->RowType == EW_ROWTYPE_EDIT || $estimativa->RowType == EW_ROWTYPE_ADD) && $estimativa->EventCancelled) // Update failed
			$estimativa_grid->RestoreCurrentRowFormValues($estimativa_grid->RowIndex); // Restore form values
		if ($estimativa->RowType == EW_ROWTYPE_EDIT) // Edit row
			$estimativa_grid->EditRowCnt++;
		if ($estimativa->CurrentAction == "F") // Confirm row
			$estimativa_grid->RestoreCurrentRowFormValues($estimativa_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$estimativa->RowAttrs = array_merge($estimativa->RowAttrs, array('data-rowindex'=>$estimativa_grid->RowCnt, 'id'=>'r' . $estimativa_grid->RowCnt . '_estimativa', 'data-rowtype'=>$estimativa->RowType));

		// Render row
		$estimativa_grid->RenderRow();

		// Render list options
		$estimativa_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($estimativa_grid->RowAction <> "delete" && $estimativa_grid->RowAction <> "insertdelete" && !($estimativa_grid->RowAction == "insert" && $estimativa->CurrentAction == "F" && $estimativa_grid->EmptyRow())) {
?>
	<tr<?php echo $estimativa->RowAttributes() ?>>
<?php

// Render list options (body, left)
$estimativa_grid->ListOptions->Render("body", "left", $estimativa_grid->RowCnt);
?>
	<?php if ($estimativa->ic_solicitacaoCritica->Visible) { // ic_solicitacaoCritica ?>
		<td<?php echo $estimativa->ic_solicitacaoCritica->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_ic_solicitacaoCritica" class="control-group estimativa_ic_solicitacaoCritica">
<div id="tp_x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" value="{value}"<?php echo $estimativa->ic_solicitacaoCritica->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->ic_solicitacaoCritica->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->ic_solicitacaoCritica->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_solicitacaoCritica" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->ic_solicitacaoCritica->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $estimativa->ic_solicitacaoCritica->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_solicitacaoCritica" name="o<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="o<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" value="<?php echo ew_HtmlEncode($estimativa->ic_solicitacaoCritica->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_ic_solicitacaoCritica" class="control-group estimativa_ic_solicitacaoCritica">
<div id="tp_x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" value="{value}"<?php echo $estimativa->ic_solicitacaoCritica->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->ic_solicitacaoCritica->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->ic_solicitacaoCritica->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_solicitacaoCritica" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->ic_solicitacaoCritica->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $estimativa->ic_solicitacaoCritica->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->ic_solicitacaoCritica->ViewAttributes() ?>>
<?php echo $estimativa->ic_solicitacaoCritica->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_solicitacaoCritica" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" value="<?php echo ew_HtmlEncode($estimativa->ic_solicitacaoCritica->FormValue) ?>">
<input type="hidden" data-field="x_ic_solicitacaoCritica" name="o<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="o<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" value="<?php echo ew_HtmlEncode($estimativa->ic_solicitacaoCritica->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_estimativa" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_estimativa" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_estimativa" value="<?php echo ew_HtmlEncode($estimativa->nu_estimativa->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_estimativa" name="o<?php echo $estimativa_grid->RowIndex ?>_nu_estimativa" id="o<?php echo $estimativa_grid->RowIndex ?>_nu_estimativa" value="<?php echo ew_HtmlEncode($estimativa->nu_estimativa->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT || $estimativa->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_estimativa" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_estimativa" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_estimativa" value="<?php echo ew_HtmlEncode($estimativa->nu_estimativa->CurrentValue) ?>">
<?php } ?>
	<?php if ($estimativa->nu_ambienteMaisRepresentativo->Visible) { // nu_ambienteMaisRepresentativo ?>
		<td<?php echo $estimativa->nu_ambienteMaisRepresentativo->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_nu_ambienteMaisRepresentativo" class="control-group estimativa_nu_ambienteMaisRepresentativo">
<?php
	$wrkonchange = trim(" " . @$estimativa->nu_ambienteMaisRepresentativo->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$estimativa->nu_ambienteMaisRepresentativo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" style="white-space: nowrap; z-index: <?php echo (9000 - $estimativa_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="sv_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo $estimativa->nu_ambienteMaisRepresentativo->EditValue ?>" size="30" placeholder="<?php echo $estimativa->nu_ambienteMaisRepresentativo->PlaceHolder ?>"<?php echo $estimativa->nu_ambienteMaisRepresentativo->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" style="display: inline; z-index: <?php echo (9000 - $estimativa_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_nu_ambienteMaisRepresentativo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo $estimativa->nu_ambienteMaisRepresentativo->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT  TOP " . EW_AUTO_SUGGEST_MAX_ENTRIES . " [nu_ambiente], [no_ambiente] AS [DispFld] FROM [dbo].[ambiente]";
 $sWhereWrk = "[no_ambiente] LIKE '%{query_value}%'";
 $lookuptblfilter = "[ic_ativo]='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $estimativa->Lookup_Selecting($estimativa->nu_ambienteMaisRepresentativo, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY [no_ambiente] ASC";
?>
<input type="hidden" name="q_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="q_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo", festimativagrid, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo") + ar[i] : "";
	return dv;
}
festimativagrid.AutoSuggests["x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo"] = oas;
</script>
</span>
<input type="hidden" data-field="x_nu_ambienteMaisRepresentativo" name="o<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="o<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo ew_HtmlEncode($estimativa->nu_ambienteMaisRepresentativo->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_nu_ambienteMaisRepresentativo" class="control-group estimativa_nu_ambienteMaisRepresentativo">
<?php
	$wrkonchange = trim(" " . @$estimativa->nu_ambienteMaisRepresentativo->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$estimativa->nu_ambienteMaisRepresentativo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" style="white-space: nowrap; z-index: <?php echo (9000 - $estimativa_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="sv_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo $estimativa->nu_ambienteMaisRepresentativo->EditValue ?>" size="30" placeholder="<?php echo $estimativa->nu_ambienteMaisRepresentativo->PlaceHolder ?>"<?php echo $estimativa->nu_ambienteMaisRepresentativo->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" style="display: inline; z-index: <?php echo (9000 - $estimativa_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_nu_ambienteMaisRepresentativo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo $estimativa->nu_ambienteMaisRepresentativo->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT  TOP " . EW_AUTO_SUGGEST_MAX_ENTRIES . " [nu_ambiente], [no_ambiente] AS [DispFld] FROM [dbo].[ambiente]";
 $sWhereWrk = "[no_ambiente] LIKE '%{query_value}%'";
 $lookuptblfilter = "[ic_ativo]='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $estimativa->Lookup_Selecting($estimativa->nu_ambienteMaisRepresentativo, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY [no_ambiente] ASC";
?>
<input type="hidden" name="q_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="q_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo", festimativagrid, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo") + ar[i] : "";
	return dv;
}
festimativagrid.AutoSuggests["x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo"] = oas;
</script>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->nu_ambienteMaisRepresentativo->ViewAttributes() ?>>
<?php echo $estimativa->nu_ambienteMaisRepresentativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_ambienteMaisRepresentativo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo ew_HtmlEncode($estimativa->nu_ambienteMaisRepresentativo->FormValue) ?>">
<input type="hidden" data-field="x_nu_ambienteMaisRepresentativo" name="o<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="o<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo ew_HtmlEncode($estimativa->nu_ambienteMaisRepresentativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->qt_tamBase->Visible) { // qt_tamBase ?>
		<td<?php echo $estimativa->qt_tamBase->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_qt_tamBase" class="control-group estimativa_qt_tamBase">
<input type="text" data-field="x_qt_tamBase" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" size="30" placeholder="<?php echo $estimativa->qt_tamBase->PlaceHolder ?>" value="<?php echo $estimativa->qt_tamBase->EditValue ?>"<?php echo $estimativa->qt_tamBase->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_tamBase" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBase->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_qt_tamBase" class="control-group estimativa_qt_tamBase">
<input type="text" data-field="x_qt_tamBase" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" size="30" placeholder="<?php echo $estimativa->qt_tamBase->PlaceHolder ?>" value="<?php echo $estimativa->qt_tamBase->EditValue ?>"<?php echo $estimativa->qt_tamBase->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->qt_tamBase->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBase->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_tamBase" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBase->FormValue) ?>">
<input type="hidden" data-field="x_qt_tamBase" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBase->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->ic_modeloCocomo->Visible) { // ic_modeloCocomo ?>
		<td<?php echo $estimativa->ic_modeloCocomo->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_ic_modeloCocomo" class="control-group estimativa_ic_modeloCocomo">
<div id="tp_x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" value="{value}"<?php echo $estimativa->ic_modeloCocomo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->ic_modeloCocomo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->ic_modeloCocomo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_modeloCocomo" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->ic_modeloCocomo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $estimativa->ic_modeloCocomo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_modeloCocomo" name="o<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="o<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" value="<?php echo ew_HtmlEncode($estimativa->ic_modeloCocomo->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_ic_modeloCocomo" class="control-group estimativa_ic_modeloCocomo">
<div id="tp_x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" value="{value}"<?php echo $estimativa->ic_modeloCocomo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->ic_modeloCocomo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->ic_modeloCocomo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_modeloCocomo" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->ic_modeloCocomo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $estimativa->ic_modeloCocomo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->ic_modeloCocomo->ViewAttributes() ?>>
<?php echo $estimativa->ic_modeloCocomo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_modeloCocomo" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" value="<?php echo ew_HtmlEncode($estimativa->ic_modeloCocomo->FormValue) ?>">
<input type="hidden" data-field="x_ic_modeloCocomo" name="o<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="o<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" value="<?php echo ew_HtmlEncode($estimativa->ic_modeloCocomo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->nu_metPrazo->Visible) { // nu_metPrazo ?>
		<td<?php echo $estimativa->nu_metPrazo->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_nu_metPrazo" class="control-group estimativa_nu_metPrazo">
<div id="tp_x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" value="{value}"<?php echo $estimativa->nu_metPrazo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->nu_metPrazo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_metPrazo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_nu_metPrazo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->nu_metPrazo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $estimativa->nu_metPrazo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_nu_metPrazo" name="o<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="o<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" value="<?php echo ew_HtmlEncode($estimativa->nu_metPrazo->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_nu_metPrazo" class="control-group estimativa_nu_metPrazo">
<div id="tp_x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" value="{value}"<?php echo $estimativa->nu_metPrazo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->nu_metPrazo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_metPrazo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_nu_metPrazo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->nu_metPrazo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $estimativa->nu_metPrazo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->nu_metPrazo->ViewAttributes() ?>>
<?php echo $estimativa->nu_metPrazo->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_metPrazo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" value="<?php echo ew_HtmlEncode($estimativa->nu_metPrazo->FormValue) ?>">
<input type="hidden" data-field="x_nu_metPrazo" name="o<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="o<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" value="<?php echo ew_HtmlEncode($estimativa->nu_metPrazo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_doPf->Visible) { // vr_doPf ?>
		<td<?php echo $estimativa->vr_doPf->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_doPf" class="control-group estimativa_vr_doPf">
<input type="text" data-field="x_vr_doPf" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" size="30" placeholder="<?php echo $estimativa->vr_doPf->PlaceHolder ?>" value="<?php echo $estimativa->vr_doPf->EditValue ?>"<?php echo $estimativa->vr_doPf->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_doPf" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" value="<?php echo ew_HtmlEncode($estimativa->vr_doPf->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_doPf" class="control-group estimativa_vr_doPf">
<input type="text" data-field="x_vr_doPf" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" size="30" placeholder="<?php echo $estimativa->vr_doPf->PlaceHolder ?>" value="<?php echo $estimativa->vr_doPf->EditValue ?>"<?php echo $estimativa->vr_doPf->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->vr_doPf->ViewAttributes() ?>>
<?php echo $estimativa->vr_doPf->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_doPf" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" value="<?php echo ew_HtmlEncode($estimativa->vr_doPf->FormValue) ?>">
<input type="hidden" data-field="x_vr_doPf" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" value="<?php echo ew_HtmlEncode($estimativa->vr_doPf->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->pz_estimadoMeses->Visible) { // pz_estimadoMeses ?>
		<td<?php echo $estimativa->pz_estimadoMeses->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_pz_estimadoMeses" class="control-group estimativa_pz_estimadoMeses">
<input type="text" data-field="x_pz_estimadoMeses" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" size="30" placeholder="<?php echo $estimativa->pz_estimadoMeses->PlaceHolder ?>" value="<?php echo $estimativa->pz_estimadoMeses->EditValue ?>"<?php echo $estimativa->pz_estimadoMeses->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_pz_estimadoMeses" name="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" id="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoMeses->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_pz_estimadoMeses" class="control-group estimativa_pz_estimadoMeses">
<input type="text" data-field="x_pz_estimadoMeses" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" size="30" placeholder="<?php echo $estimativa->pz_estimadoMeses->PlaceHolder ?>" value="<?php echo $estimativa->pz_estimadoMeses->EditValue ?>"<?php echo $estimativa->pz_estimadoMeses->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->pz_estimadoMeses->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoMeses->ListViewValue() ?></span>
<input type="hidden" data-field="x_pz_estimadoMeses" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoMeses->FormValue) ?>">
<input type="hidden" data-field="x_pz_estimadoMeses" name="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" id="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoMeses->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->pz_estimadoDias->Visible) { // pz_estimadoDias ?>
		<td<?php echo $estimativa->pz_estimadoDias->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_pz_estimadoDias" class="control-group estimativa_pz_estimadoDias">
<input type="text" data-field="x_pz_estimadoDias" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" size="30" placeholder="<?php echo $estimativa->pz_estimadoDias->PlaceHolder ?>" value="<?php echo $estimativa->pz_estimadoDias->EditValue ?>"<?php echo $estimativa->pz_estimadoDias->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_pz_estimadoDias" name="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" id="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoDias->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_pz_estimadoDias" class="control-group estimativa_pz_estimadoDias">
<input type="text" data-field="x_pz_estimadoDias" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" size="30" placeholder="<?php echo $estimativa->pz_estimadoDias->PlaceHolder ?>" value="<?php echo $estimativa->pz_estimadoDias->EditValue ?>"<?php echo $estimativa->pz_estimadoDias->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->pz_estimadoDias->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoDias->ListViewValue() ?></span>
<input type="hidden" data-field="x_pz_estimadoDias" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoDias->FormValue) ?>">
<input type="hidden" data-field="x_pz_estimadoDias" name="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" id="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoDias->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_ipMaximo->Visible) { // vr_ipMaximo ?>
		<td<?php echo $estimativa->vr_ipMaximo->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_ipMaximo" class="control-group estimativa_vr_ipMaximo">
<input type="text" data-field="x_vr_ipMaximo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" size="30" placeholder="<?php echo $estimativa->vr_ipMaximo->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMaximo->EditValue ?>"<?php echo $estimativa->vr_ipMaximo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_ipMaximo" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMaximo->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_ipMaximo" class="control-group estimativa_vr_ipMaximo">
<input type="text" data-field="x_vr_ipMaximo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" size="30" placeholder="<?php echo $estimativa->vr_ipMaximo->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMaximo->EditValue ?>"<?php echo $estimativa->vr_ipMaximo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->vr_ipMaximo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMaximo->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_ipMaximo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMaximo->FormValue) ?>">
<input type="hidden" data-field="x_vr_ipMaximo" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMaximo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_ipMedio->Visible) { // vr_ipMedio ?>
		<td<?php echo $estimativa->vr_ipMedio->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_ipMedio" class="control-group estimativa_vr_ipMedio">
<input type="text" data-field="x_vr_ipMedio" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" size="30" placeholder="<?php echo $estimativa->vr_ipMedio->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMedio->EditValue ?>"<?php echo $estimativa->vr_ipMedio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_ipMedio" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMedio->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_ipMedio" class="control-group estimativa_vr_ipMedio">
<input type="text" data-field="x_vr_ipMedio" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" size="30" placeholder="<?php echo $estimativa->vr_ipMedio->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMedio->EditValue ?>"<?php echo $estimativa->vr_ipMedio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->vr_ipMedio->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMedio->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_ipMedio" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMedio->FormValue) ?>">
<input type="hidden" data-field="x_vr_ipMedio" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMedio->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_ipMinimo->Visible) { // vr_ipMinimo ?>
		<td<?php echo $estimativa->vr_ipMinimo->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_ipMinimo" class="control-group estimativa_vr_ipMinimo">
<input type="text" data-field="x_vr_ipMinimo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" size="30" placeholder="<?php echo $estimativa->vr_ipMinimo->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMinimo->EditValue ?>"<?php echo $estimativa->vr_ipMinimo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_ipMinimo" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMinimo->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_ipMinimo" class="control-group estimativa_vr_ipMinimo">
<input type="text" data-field="x_vr_ipMinimo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" size="30" placeholder="<?php echo $estimativa->vr_ipMinimo->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMinimo->EditValue ?>"<?php echo $estimativa->vr_ipMinimo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->vr_ipMinimo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMinimo->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_ipMinimo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMinimo->FormValue) ?>">
<input type="hidden" data-field="x_vr_ipMinimo" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMinimo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_ipInformado->Visible) { // vr_ipInformado ?>
		<td<?php echo $estimativa->vr_ipInformado->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_ipInformado" class="control-group estimativa_vr_ipInformado">
<input type="text" data-field="x_vr_ipInformado" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" size="30" placeholder="<?php echo $estimativa->vr_ipInformado->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipInformado->EditValue ?>"<?php echo $estimativa->vr_ipInformado->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_ipInformado" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" value="<?php echo ew_HtmlEncode($estimativa->vr_ipInformado->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_ipInformado" class="control-group estimativa_vr_ipInformado">
<input type="text" data-field="x_vr_ipInformado" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" size="30" placeholder="<?php echo $estimativa->vr_ipInformado->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipInformado->EditValue ?>"<?php echo $estimativa->vr_ipInformado->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->vr_ipInformado->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipInformado->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_ipInformado" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" value="<?php echo ew_HtmlEncode($estimativa->vr_ipInformado->FormValue) ?>">
<input type="hidden" data-field="x_vr_ipInformado" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" value="<?php echo ew_HtmlEncode($estimativa->vr_ipInformado->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->qt_esforco->Visible) { // qt_esforco ?>
		<td<?php echo $estimativa->qt_esforco->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_qt_esforco" class="control-group estimativa_qt_esforco">
<input type="text" data-field="x_qt_esforco" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" size="30" placeholder="<?php echo $estimativa->qt_esforco->PlaceHolder ?>" value="<?php echo $estimativa->qt_esforco->EditValue ?>"<?php echo $estimativa->qt_esforco->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_esforco" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" value="<?php echo ew_HtmlEncode($estimativa->qt_esforco->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_qt_esforco" class="control-group estimativa_qt_esforco">
<input type="text" data-field="x_qt_esforco" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" size="30" placeholder="<?php echo $estimativa->qt_esforco->PlaceHolder ?>" value="<?php echo $estimativa->qt_esforco->EditValue ?>"<?php echo $estimativa->qt_esforco->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->qt_esforco->ViewAttributes() ?>>
<?php echo $estimativa->qt_esforco->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_esforco" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" value="<?php echo ew_HtmlEncode($estimativa->qt_esforco->FormValue) ?>">
<input type="hidden" data-field="x_qt_esforco" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" value="<?php echo ew_HtmlEncode($estimativa->qt_esforco->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_custoDesenv->Visible) { // vr_custoDesenv ?>
		<td<?php echo $estimativa->vr_custoDesenv->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_custoDesenv" class="control-group estimativa_vr_custoDesenv">
<input type="text" data-field="x_vr_custoDesenv" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" size="30" placeholder="<?php echo $estimativa->vr_custoDesenv->PlaceHolder ?>" value="<?php echo $estimativa->vr_custoDesenv->EditValue ?>"<?php echo $estimativa->vr_custoDesenv->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_custoDesenv" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" value="<?php echo ew_HtmlEncode($estimativa->vr_custoDesenv->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_custoDesenv" class="control-group estimativa_vr_custoDesenv">
<input type="text" data-field="x_vr_custoDesenv" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" size="30" placeholder="<?php echo $estimativa->vr_custoDesenv->PlaceHolder ?>" value="<?php echo $estimativa->vr_custoDesenv->EditValue ?>"<?php echo $estimativa->vr_custoDesenv->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->vr_custoDesenv->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoDesenv->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_custoDesenv" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" value="<?php echo ew_HtmlEncode($estimativa->vr_custoDesenv->FormValue) ?>">
<input type="hidden" data-field="x_vr_custoDesenv" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" value="<?php echo ew_HtmlEncode($estimativa->vr_custoDesenv->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_outrosCustos->Visible) { // vr_outrosCustos ?>
		<td<?php echo $estimativa->vr_outrosCustos->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_outrosCustos" class="control-group estimativa_vr_outrosCustos">
<input type="text" data-field="x_vr_outrosCustos" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" size="30" placeholder="<?php echo $estimativa->vr_outrosCustos->PlaceHolder ?>" value="<?php echo $estimativa->vr_outrosCustos->EditValue ?>"<?php echo $estimativa->vr_outrosCustos->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_outrosCustos" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" value="<?php echo ew_HtmlEncode($estimativa->vr_outrosCustos->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_outrosCustos" class="control-group estimativa_vr_outrosCustos">
<input type="text" data-field="x_vr_outrosCustos" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" size="30" placeholder="<?php echo $estimativa->vr_outrosCustos->PlaceHolder ?>" value="<?php echo $estimativa->vr_outrosCustos->EditValue ?>"<?php echo $estimativa->vr_outrosCustos->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->vr_outrosCustos->ViewAttributes() ?>>
<?php echo $estimativa->vr_outrosCustos->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_outrosCustos" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" value="<?php echo ew_HtmlEncode($estimativa->vr_outrosCustos->FormValue) ?>">
<input type="hidden" data-field="x_vr_outrosCustos" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" value="<?php echo ew_HtmlEncode($estimativa->vr_outrosCustos->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->vr_custoTotal->Visible) { // vr_custoTotal ?>
		<td<?php echo $estimativa->vr_custoTotal->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_custoTotal" class="control-group estimativa_vr_custoTotal">
<input type="text" data-field="x_vr_custoTotal" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" size="30" placeholder="<?php echo $estimativa->vr_custoTotal->PlaceHolder ?>" value="<?php echo $estimativa->vr_custoTotal->EditValue ?>"<?php echo $estimativa->vr_custoTotal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_custoTotal" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" value="<?php echo ew_HtmlEncode($estimativa->vr_custoTotal->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_vr_custoTotal" class="control-group estimativa_vr_custoTotal">
<input type="text" data-field="x_vr_custoTotal" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" size="30" placeholder="<?php echo $estimativa->vr_custoTotal->PlaceHolder ?>" value="<?php echo $estimativa->vr_custoTotal->EditValue ?>"<?php echo $estimativa->vr_custoTotal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->vr_custoTotal->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoTotal->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_custoTotal" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" value="<?php echo ew_HtmlEncode($estimativa->vr_custoTotal->FormValue) ?>">
<input type="hidden" data-field="x_vr_custoTotal" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" value="<?php echo ew_HtmlEncode($estimativa->vr_custoTotal->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->qt_tamBaseFaturamento->Visible) { // qt_tamBaseFaturamento ?>
		<td<?php echo $estimativa->qt_tamBaseFaturamento->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_qt_tamBaseFaturamento" class="control-group estimativa_qt_tamBaseFaturamento">
<input type="text" data-field="x_qt_tamBaseFaturamento" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" size="30" placeholder="<?php echo $estimativa->qt_tamBaseFaturamento->PlaceHolder ?>" value="<?php echo $estimativa->qt_tamBaseFaturamento->EditValue ?>"<?php echo $estimativa->qt_tamBaseFaturamento->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_tamBaseFaturamento" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBaseFaturamento->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_qt_tamBaseFaturamento" class="control-group estimativa_qt_tamBaseFaturamento">
<input type="text" data-field="x_qt_tamBaseFaturamento" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" size="30" placeholder="<?php echo $estimativa->qt_tamBaseFaturamento->PlaceHolder ?>" value="<?php echo $estimativa->qt_tamBaseFaturamento->EditValue ?>"<?php echo $estimativa->qt_tamBaseFaturamento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->qt_tamBaseFaturamento->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBaseFaturamento->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_tamBaseFaturamento" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBaseFaturamento->FormValue) ?>">
<input type="hidden" data-field="x_qt_tamBaseFaturamento" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBaseFaturamento->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($estimativa->qt_recursosEquipe->Visible) { // qt_recursosEquipe ?>
		<td<?php echo $estimativa->qt_recursosEquipe->CellAttributes() ?>>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_qt_recursosEquipe" class="control-group estimativa_qt_recursosEquipe">
<input type="text" data-field="x_qt_recursosEquipe" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" size="30" placeholder="<?php echo $estimativa->qt_recursosEquipe->PlaceHolder ?>" value="<?php echo $estimativa->qt_recursosEquipe->EditValue ?>"<?php echo $estimativa->qt_recursosEquipe->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_recursosEquipe" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" value="<?php echo ew_HtmlEncode($estimativa->qt_recursosEquipe->OldValue) ?>">
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $estimativa_grid->RowCnt ?>_estimativa_qt_recursosEquipe" class="control-group estimativa_qt_recursosEquipe">
<input type="text" data-field="x_qt_recursosEquipe" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" size="30" placeholder="<?php echo $estimativa->qt_recursosEquipe->PlaceHolder ?>" value="<?php echo $estimativa->qt_recursosEquipe->EditValue ?>"<?php echo $estimativa->qt_recursosEquipe->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($estimativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $estimativa->qt_recursosEquipe->ViewAttributes() ?>>
<?php echo $estimativa->qt_recursosEquipe->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_recursosEquipe" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" value="<?php echo ew_HtmlEncode($estimativa->qt_recursosEquipe->FormValue) ?>">
<input type="hidden" data-field="x_qt_recursosEquipe" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" value="<?php echo ew_HtmlEncode($estimativa->qt_recursosEquipe->OldValue) ?>">
<?php } ?>
<a id="<?php echo $estimativa_grid->PageObjName . "_row_" . $estimativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$estimativa_grid->ListOptions->Render("body", "right", $estimativa_grid->RowCnt);
?>
	</tr>
<?php if ($estimativa->RowType == EW_ROWTYPE_ADD || $estimativa->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
festimativagrid.UpdateOpts(<?php echo $estimativa_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($estimativa->CurrentAction <> "gridadd" || $estimativa->CurrentMode == "copy")
		if (!$estimativa_grid->Recordset->EOF) $estimativa_grid->Recordset->MoveNext();
}
?>
<?php
	if ($estimativa->CurrentMode == "add" || $estimativa->CurrentMode == "copy" || $estimativa->CurrentMode == "edit") {
		$estimativa_grid->RowIndex = '$rowindex$';
		$estimativa_grid->LoadDefaultValues();

		// Set row properties
		$estimativa->ResetAttrs();
		$estimativa->RowAttrs = array_merge($estimativa->RowAttrs, array('data-rowindex'=>$estimativa_grid->RowIndex, 'id'=>'r0_estimativa', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($estimativa->RowAttrs["class"], "ewTemplate");
		$estimativa->RowType = EW_ROWTYPE_ADD;

		// Render row
		$estimativa_grid->RenderRow();

		// Render list options
		$estimativa_grid->RenderListOptions();
		$estimativa_grid->StartRowCnt = 0;
?>
	<tr<?php echo $estimativa->RowAttributes() ?>>
<?php

// Render list options (body, left)
$estimativa_grid->ListOptions->Render("body", "left", $estimativa_grid->RowIndex);
?>
	<?php if ($estimativa->ic_solicitacaoCritica->Visible) { // ic_solicitacaoCritica ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" value="{value}"<?php echo $estimativa->ic_solicitacaoCritica->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->ic_solicitacaoCritica->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->ic_solicitacaoCritica->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_solicitacaoCritica" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->ic_solicitacaoCritica->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $estimativa->ic_solicitacaoCritica->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $estimativa->ic_solicitacaoCritica->ViewAttributes() ?>>
<?php echo $estimativa->ic_solicitacaoCritica->ViewValue ?></span>
<input type="hidden" data-field="x_ic_solicitacaoCritica" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" value="<?php echo ew_HtmlEncode($estimativa->ic_solicitacaoCritica->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_solicitacaoCritica" name="o<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" id="o<?php echo $estimativa_grid->RowIndex ?>_ic_solicitacaoCritica" value="<?php echo ew_HtmlEncode($estimativa->ic_solicitacaoCritica->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->nu_ambienteMaisRepresentativo->Visible) { // nu_ambienteMaisRepresentativo ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<?php
	$wrkonchange = trim(" " . @$estimativa->nu_ambienteMaisRepresentativo->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$estimativa->nu_ambienteMaisRepresentativo->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" style="white-space: nowrap; z-index: <?php echo (9000 - $estimativa_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="sv_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo $estimativa->nu_ambienteMaisRepresentativo->EditValue ?>" size="30" placeholder="<?php echo $estimativa->nu_ambienteMaisRepresentativo->PlaceHolder ?>"<?php echo $estimativa->nu_ambienteMaisRepresentativo->EditAttributes() ?>>&nbsp;<span id="em_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" style="display: inline; z-index: <?php echo (9000 - $estimativa_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" data-field="x_nu_ambienteMaisRepresentativo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo $estimativa->nu_ambienteMaisRepresentativo->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
 $sSqlWrk = "SELECT  TOP " . EW_AUTO_SUGGEST_MAX_ENTRIES . " [nu_ambiente], [no_ambiente] AS [DispFld] FROM [dbo].[ambiente]";
 $sWhereWrk = "[no_ambiente] LIKE '%{query_value}%'";
 $lookuptblfilter = "[ic_ativo]='S'";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $estimativa->Lookup_Selecting($estimativa->nu_ambienteMaisRepresentativo, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY [no_ambiente] ASC";
?>
<input type="hidden" name="q_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="q_x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo", festimativagrid, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo") + ar[i] : "";
	return dv;
}
festimativagrid.AutoSuggests["x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo"] = oas;
</script>
<?php } else { ?>
<span<?php echo $estimativa->nu_ambienteMaisRepresentativo->ViewAttributes() ?>>
<?php echo $estimativa->nu_ambienteMaisRepresentativo->ViewValue ?></span>
<input type="hidden" data-field="x_nu_ambienteMaisRepresentativo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo ew_HtmlEncode($estimativa->nu_ambienteMaisRepresentativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_ambienteMaisRepresentativo" name="o<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" id="o<?php echo $estimativa_grid->RowIndex ?>_nu_ambienteMaisRepresentativo" value="<?php echo ew_HtmlEncode($estimativa->nu_ambienteMaisRepresentativo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->qt_tamBase->Visible) { // qt_tamBase ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_tamBase" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" size="30" placeholder="<?php echo $estimativa->qt_tamBase->PlaceHolder ?>" value="<?php echo $estimativa->qt_tamBase->EditValue ?>"<?php echo $estimativa->qt_tamBase->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->qt_tamBase->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBase->ViewValue ?></span>
<input type="hidden" data-field="x_qt_tamBase" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBase->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_tamBase" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBase" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBase->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->ic_modeloCocomo->Visible) { // ic_modeloCocomo ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" value="{value}"<?php echo $estimativa->ic_modeloCocomo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->ic_modeloCocomo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->ic_modeloCocomo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_modeloCocomo" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->ic_modeloCocomo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $estimativa->ic_modeloCocomo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $estimativa->ic_modeloCocomo->ViewAttributes() ?>>
<?php echo $estimativa->ic_modeloCocomo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_modeloCocomo" name="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="x<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" value="<?php echo ew_HtmlEncode($estimativa->ic_modeloCocomo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_modeloCocomo" name="o<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" id="o<?php echo $estimativa_grid->RowIndex ?>_ic_modeloCocomo" value="<?php echo ew_HtmlEncode($estimativa->ic_modeloCocomo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->nu_metPrazo->Visible) { // nu_metPrazo ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" value="{value}"<?php echo $estimativa->nu_metPrazo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $estimativa->nu_metPrazo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($estimativa->nu_metPrazo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_nu_metPrazo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $estimativa->nu_metPrazo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $estimativa->nu_metPrazo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $estimativa->nu_metPrazo->ViewAttributes() ?>>
<?php echo $estimativa->nu_metPrazo->ViewValue ?></span>
<input type="hidden" data-field="x_nu_metPrazo" name="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="x<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" value="<?php echo ew_HtmlEncode($estimativa->nu_metPrazo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_metPrazo" name="o<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" id="o<?php echo $estimativa_grid->RowIndex ?>_nu_metPrazo" value="<?php echo ew_HtmlEncode($estimativa->nu_metPrazo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->vr_doPf->Visible) { // vr_doPf ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_doPf" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" size="30" placeholder="<?php echo $estimativa->vr_doPf->PlaceHolder ?>" value="<?php echo $estimativa->vr_doPf->EditValue ?>"<?php echo $estimativa->vr_doPf->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->vr_doPf->ViewAttributes() ?>>
<?php echo $estimativa->vr_doPf->ViewValue ?></span>
<input type="hidden" data-field="x_vr_doPf" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" value="<?php echo ew_HtmlEncode($estimativa->vr_doPf->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_doPf" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_doPf" value="<?php echo ew_HtmlEncode($estimativa->vr_doPf->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->pz_estimadoMeses->Visible) { // pz_estimadoMeses ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_pz_estimadoMeses" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" size="30" placeholder="<?php echo $estimativa->pz_estimadoMeses->PlaceHolder ?>" value="<?php echo $estimativa->pz_estimadoMeses->EditValue ?>"<?php echo $estimativa->pz_estimadoMeses->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->pz_estimadoMeses->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoMeses->ViewValue ?></span>
<input type="hidden" data-field="x_pz_estimadoMeses" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoMeses->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_pz_estimadoMeses" name="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" id="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoMeses" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoMeses->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->pz_estimadoDias->Visible) { // pz_estimadoDias ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_pz_estimadoDias" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" size="30" placeholder="<?php echo $estimativa->pz_estimadoDias->PlaceHolder ?>" value="<?php echo $estimativa->pz_estimadoDias->EditValue ?>"<?php echo $estimativa->pz_estimadoDias->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->pz_estimadoDias->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoDias->ViewValue ?></span>
<input type="hidden" data-field="x_pz_estimadoDias" name="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" id="x<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoDias->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_pz_estimadoDias" name="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" id="o<?php echo $estimativa_grid->RowIndex ?>_pz_estimadoDias" value="<?php echo ew_HtmlEncode($estimativa->pz_estimadoDias->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->vr_ipMaximo->Visible) { // vr_ipMaximo ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_ipMaximo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" size="30" placeholder="<?php echo $estimativa->vr_ipMaximo->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMaximo->EditValue ?>"<?php echo $estimativa->vr_ipMaximo->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->vr_ipMaximo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMaximo->ViewValue ?></span>
<input type="hidden" data-field="x_vr_ipMaximo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMaximo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_ipMaximo" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMaximo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMaximo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->vr_ipMedio->Visible) { // vr_ipMedio ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_ipMedio" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" size="30" placeholder="<?php echo $estimativa->vr_ipMedio->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMedio->EditValue ?>"<?php echo $estimativa->vr_ipMedio->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->vr_ipMedio->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMedio->ViewValue ?></span>
<input type="hidden" data-field="x_vr_ipMedio" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMedio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_ipMedio" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMedio" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMedio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->vr_ipMinimo->Visible) { // vr_ipMinimo ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_ipMinimo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" size="30" placeholder="<?php echo $estimativa->vr_ipMinimo->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipMinimo->EditValue ?>"<?php echo $estimativa->vr_ipMinimo->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->vr_ipMinimo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMinimo->ViewValue ?></span>
<input type="hidden" data-field="x_vr_ipMinimo" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMinimo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_ipMinimo" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipMinimo" value="<?php echo ew_HtmlEncode($estimativa->vr_ipMinimo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->vr_ipInformado->Visible) { // vr_ipInformado ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_ipInformado" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" size="30" placeholder="<?php echo $estimativa->vr_ipInformado->PlaceHolder ?>" value="<?php echo $estimativa->vr_ipInformado->EditValue ?>"<?php echo $estimativa->vr_ipInformado->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->vr_ipInformado->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipInformado->ViewValue ?></span>
<input type="hidden" data-field="x_vr_ipInformado" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" value="<?php echo ew_HtmlEncode($estimativa->vr_ipInformado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_ipInformado" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_ipInformado" value="<?php echo ew_HtmlEncode($estimativa->vr_ipInformado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->qt_esforco->Visible) { // qt_esforco ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_esforco" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" size="30" placeholder="<?php echo $estimativa->qt_esforco->PlaceHolder ?>" value="<?php echo $estimativa->qt_esforco->EditValue ?>"<?php echo $estimativa->qt_esforco->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->qt_esforco->ViewAttributes() ?>>
<?php echo $estimativa->qt_esforco->ViewValue ?></span>
<input type="hidden" data-field="x_qt_esforco" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" value="<?php echo ew_HtmlEncode($estimativa->qt_esforco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_esforco" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_esforco" value="<?php echo ew_HtmlEncode($estimativa->qt_esforco->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->vr_custoDesenv->Visible) { // vr_custoDesenv ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_custoDesenv" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" size="30" placeholder="<?php echo $estimativa->vr_custoDesenv->PlaceHolder ?>" value="<?php echo $estimativa->vr_custoDesenv->EditValue ?>"<?php echo $estimativa->vr_custoDesenv->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->vr_custoDesenv->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoDesenv->ViewValue ?></span>
<input type="hidden" data-field="x_vr_custoDesenv" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" value="<?php echo ew_HtmlEncode($estimativa->vr_custoDesenv->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_custoDesenv" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoDesenv" value="<?php echo ew_HtmlEncode($estimativa->vr_custoDesenv->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->vr_outrosCustos->Visible) { // vr_outrosCustos ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_outrosCustos" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" size="30" placeholder="<?php echo $estimativa->vr_outrosCustos->PlaceHolder ?>" value="<?php echo $estimativa->vr_outrosCustos->EditValue ?>"<?php echo $estimativa->vr_outrosCustos->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->vr_outrosCustos->ViewAttributes() ?>>
<?php echo $estimativa->vr_outrosCustos->ViewValue ?></span>
<input type="hidden" data-field="x_vr_outrosCustos" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" value="<?php echo ew_HtmlEncode($estimativa->vr_outrosCustos->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_outrosCustos" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_outrosCustos" value="<?php echo ew_HtmlEncode($estimativa->vr_outrosCustos->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->vr_custoTotal->Visible) { // vr_custoTotal ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_custoTotal" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" size="30" placeholder="<?php echo $estimativa->vr_custoTotal->PlaceHolder ?>" value="<?php echo $estimativa->vr_custoTotal->EditValue ?>"<?php echo $estimativa->vr_custoTotal->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->vr_custoTotal->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoTotal->ViewValue ?></span>
<input type="hidden" data-field="x_vr_custoTotal" name="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" id="x<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" value="<?php echo ew_HtmlEncode($estimativa->vr_custoTotal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_custoTotal" name="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" id="o<?php echo $estimativa_grid->RowIndex ?>_vr_custoTotal" value="<?php echo ew_HtmlEncode($estimativa->vr_custoTotal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->qt_tamBaseFaturamento->Visible) { // qt_tamBaseFaturamento ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_tamBaseFaturamento" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" size="30" placeholder="<?php echo $estimativa->qt_tamBaseFaturamento->PlaceHolder ?>" value="<?php echo $estimativa->qt_tamBaseFaturamento->EditValue ?>"<?php echo $estimativa->qt_tamBaseFaturamento->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->qt_tamBaseFaturamento->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBaseFaturamento->ViewValue ?></span>
<input type="hidden" data-field="x_qt_tamBaseFaturamento" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBaseFaturamento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_tamBaseFaturamento" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_tamBaseFaturamento" value="<?php echo ew_HtmlEncode($estimativa->qt_tamBaseFaturamento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($estimativa->qt_recursosEquipe->Visible) { // qt_recursosEquipe ?>
		<td>
<?php if ($estimativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_recursosEquipe" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" size="30" placeholder="<?php echo $estimativa->qt_recursosEquipe->PlaceHolder ?>" value="<?php echo $estimativa->qt_recursosEquipe->EditValue ?>"<?php echo $estimativa->qt_recursosEquipe->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $estimativa->qt_recursosEquipe->ViewAttributes() ?>>
<?php echo $estimativa->qt_recursosEquipe->ViewValue ?></span>
<input type="hidden" data-field="x_qt_recursosEquipe" name="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" id="x<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" value="<?php echo ew_HtmlEncode($estimativa->qt_recursosEquipe->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_recursosEquipe" name="o<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" id="o<?php echo $estimativa_grid->RowIndex ?>_qt_recursosEquipe" value="<?php echo ew_HtmlEncode($estimativa->qt_recursosEquipe->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$estimativa_grid->ListOptions->Render("body", "right", $estimativa_grid->RowCnt);
?>
<script type="text/javascript">
festimativagrid.UpdateOpts(<?php echo $estimativa_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($estimativa->CurrentMode == "add" || $estimativa->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $estimativa_grid->FormKeyCountName ?>" id="<?php echo $estimativa_grid->FormKeyCountName ?>" value="<?php echo $estimativa_grid->KeyCount ?>">
<?php echo $estimativa_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($estimativa->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $estimativa_grid->FormKeyCountName ?>" id="<?php echo $estimativa_grid->FormKeyCountName ?>" value="<?php echo $estimativa_grid->KeyCount ?>">
<?php echo $estimativa_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($estimativa->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="festimativagrid">
</div>
<?php

// Close recordset
if ($estimativa_grid->Recordset)
	$estimativa_grid->Recordset->Close();
?>
<?php if ($estimativa_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($estimativa_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($estimativa->Export == "") { ?>
<script type="text/javascript">
festimativagrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$estimativa_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$estimativa_grid->Page_Terminate();
?>
