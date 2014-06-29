<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($contagempf_funcao_grid)) $contagempf_funcao_grid = new ccontagempf_funcao_grid();

// Page init
$contagempf_funcao_grid->Page_Init();

// Page main
$contagempf_funcao_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_funcao_grid->Page_Render();
?>
<?php if ($contagempf_funcao->Export == "") { ?>
<script type="text/javascript">

// Page object
var contagempf_funcao_grid = new ew_Page("contagempf_funcao_grid");
contagempf_funcao_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = contagempf_funcao_grid.PageID; // For backward compatibility

// Form object
var fcontagempf_funcaogrid = new ew_Form("fcontagempf_funcaogrid");
fcontagempf_funcaogrid.FormKeyCountName = '<?php echo $contagempf_funcao_grid->FormKeyCountName ?>';

// Validate form
fcontagempf_funcaogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_agrupador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->nu_agrupador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_funcao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->no_funcao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpManutencao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->nu_tpManutencao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpElemento");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_funcao->nu_tpElemento->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_alr");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->qt_alr->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_der");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->qt_der->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_contribuicao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->vr_contribuicao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_qtPf");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf_funcao->vr_qtPf->FldErrMsg()) ?>");

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
fcontagempf_funcaogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_agrupador", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_uc", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_funcao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_tpManutencao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_tpElemento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_alr", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_der", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_complexApf", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_contribuicao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_fatorReducao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pc_varFasesRoteiro", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_qtPf", false)) return false;
	return true;
}

// Form_CustomValidate event
fcontagempf_funcaogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempf_funcaogrid.ValidateRequired = true;
<?php } else { ?>
fcontagempf_funcaogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontagempf_funcaogrid.Lists["x_nu_agrupador"] = {"LinkField":"x_nu_agrupador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_agrupador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaogrid.Lists["x_nu_uc"] = {"LinkField":"x_nu_uc","Ajax":true,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_uc","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaogrid.Lists["x_nu_tpManutencao"] = {"LinkField":"x_nu_tpManutencao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpManutencao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaogrid.Lists["x_nu_tpElemento"] = {"LinkField":"x_nu_tpElemento","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpElemento","","",""],"ParentFields":["x_nu_tpManutencao"],"FilterFields":["x_nu_tpManutencao"],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($contagempf_funcao->getCurrentMasterTable() == "" && $contagempf_funcao_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $contagempf_funcao_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($contagempf_funcao->CurrentAction == "gridadd") {
	if ($contagempf_funcao->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$contagempf_funcao_grid->TotalRecs = $contagempf_funcao->SelectRecordCount();
			$contagempf_funcao_grid->Recordset = $contagempf_funcao_grid->LoadRecordset($contagempf_funcao_grid->StartRec-1, $contagempf_funcao_grid->DisplayRecs);
		} else {
			if ($contagempf_funcao_grid->Recordset = $contagempf_funcao_grid->LoadRecordset())
				$contagempf_funcao_grid->TotalRecs = $contagempf_funcao_grid->Recordset->RecordCount();
		}
		$contagempf_funcao_grid->StartRec = 1;
		$contagempf_funcao_grid->DisplayRecs = $contagempf_funcao_grid->TotalRecs;
	} else {
		$contagempf_funcao->CurrentFilter = "0=1";
		$contagempf_funcao_grid->StartRec = 1;
		$contagempf_funcao_grid->DisplayRecs = $contagempf_funcao->GridAddRowCount;
	}
	$contagempf_funcao_grid->TotalRecs = $contagempf_funcao_grid->DisplayRecs;
	$contagempf_funcao_grid->StopRec = $contagempf_funcao_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$contagempf_funcao_grid->TotalRecs = $contagempf_funcao->SelectRecordCount();
	} else {
		if ($contagempf_funcao_grid->Recordset = $contagempf_funcao_grid->LoadRecordset())
			$contagempf_funcao_grid->TotalRecs = $contagempf_funcao_grid->Recordset->RecordCount();
	}
	$contagempf_funcao_grid->StartRec = 1;
	$contagempf_funcao_grid->DisplayRecs = $contagempf_funcao_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$contagempf_funcao_grid->Recordset = $contagempf_funcao_grid->LoadRecordset($contagempf_funcao_grid->StartRec-1, $contagempf_funcao_grid->DisplayRecs);
}
$contagempf_funcao_grid->RenderOtherOptions();
?>
<?php $contagempf_funcao_grid->ShowPageHeader(); ?>
<?php
$contagempf_funcao_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fcontagempf_funcaogrid" class="ewForm form-horizontal">
<div id="gmp_contagempf_funcao" class="ewGridMiddlePanel">
<table id="tbl_contagempf_funcaogrid" class="ewTable ewTableSeparate">
<?php echo $contagempf_funcao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$contagempf_funcao_grid->RenderListOptions();

// Render list options (header, left)
$contagempf_funcao_grid->ListOptions->Render("header", "left");
?>
<?php if ($contagempf_funcao->nu_agrupador->Visible) { // nu_agrupador ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->nu_agrupador) == "") { ?>
		<td><div id="elh_contagempf_funcao_nu_agrupador" class="contagempf_funcao_nu_agrupador"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_agrupador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_nu_agrupador" class="contagempf_funcao_nu_agrupador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_agrupador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->nu_agrupador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->nu_agrupador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->nu_uc->Visible) { // nu_uc ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->nu_uc) == "") { ?>
		<td><div id="elh_contagempf_funcao_nu_uc" class="contagempf_funcao_nu_uc"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_uc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_nu_uc" class="contagempf_funcao_nu_uc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_uc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->nu_uc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->nu_uc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->no_funcao->Visible) { // no_funcao ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->no_funcao) == "") { ?>
		<td><div id="elh_contagempf_funcao_no_funcao" class="contagempf_funcao_no_funcao"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->no_funcao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_no_funcao" class="contagempf_funcao_no_funcao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->no_funcao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->no_funcao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->no_funcao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->nu_tpManutencao->Visible) { // nu_tpManutencao ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->nu_tpManutencao) == "") { ?>
		<td><div id="elh_contagempf_funcao_nu_tpManutencao" class="contagempf_funcao_nu_tpManutencao"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_tpManutencao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_nu_tpManutencao" class="contagempf_funcao_nu_tpManutencao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_tpManutencao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->nu_tpManutencao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->nu_tpManutencao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->nu_tpElemento->Visible) { // nu_tpElemento ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->nu_tpElemento) == "") { ?>
		<td><div id="elh_contagempf_funcao_nu_tpElemento" class="contagempf_funcao_nu_tpElemento"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_tpElemento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_nu_tpElemento" class="contagempf_funcao_nu_tpElemento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_tpElemento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->nu_tpElemento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->nu_tpElemento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->qt_alr->Visible) { // qt_alr ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->qt_alr) == "") { ?>
		<td><div id="elh_contagempf_funcao_qt_alr" class="contagempf_funcao_qt_alr"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->qt_alr->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_qt_alr" class="contagempf_funcao_qt_alr">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->qt_alr->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->qt_alr->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->qt_alr->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->qt_der->Visible) { // qt_der ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->qt_der) == "") { ?>
		<td><div id="elh_contagempf_funcao_qt_der" class="contagempf_funcao_qt_der"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->qt_der->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_qt_der" class="contagempf_funcao_qt_der">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->qt_der->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->qt_der->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->qt_der->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->ic_complexApf->Visible) { // ic_complexApf ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->ic_complexApf) == "") { ?>
		<td><div id="elh_contagempf_funcao_ic_complexApf" class="contagempf_funcao_ic_complexApf"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->ic_complexApf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_ic_complexApf" class="contagempf_funcao_ic_complexApf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->ic_complexApf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->ic_complexApf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->ic_complexApf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->vr_contribuicao->Visible) { // vr_contribuicao ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->vr_contribuicao) == "") { ?>
		<td><div id="elh_contagempf_funcao_vr_contribuicao" class="contagempf_funcao_vr_contribuicao"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_contribuicao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_vr_contribuicao" class="contagempf_funcao_vr_contribuicao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_contribuicao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->vr_contribuicao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->vr_contribuicao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->vr_fatorReducao->Visible) { // vr_fatorReducao ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->vr_fatorReducao) == "") { ?>
		<td><div id="elh_contagempf_funcao_vr_fatorReducao" class="contagempf_funcao_vr_fatorReducao"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_fatorReducao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_vr_fatorReducao" class="contagempf_funcao_vr_fatorReducao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_fatorReducao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->vr_fatorReducao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->vr_fatorReducao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->pc_varFasesRoteiro) == "") { ?>
		<td><div id="elh_contagempf_funcao_pc_varFasesRoteiro" class="contagempf_funcao_pc_varFasesRoteiro"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->pc_varFasesRoteiro->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_pc_varFasesRoteiro" class="contagempf_funcao_pc_varFasesRoteiro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->pc_varFasesRoteiro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->pc_varFasesRoteiro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->pc_varFasesRoteiro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->vr_qtPf->Visible) { // vr_qtPf ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->vr_qtPf) == "") { ?>
		<td><div id="elh_contagempf_funcao_vr_qtPf" class="contagempf_funcao_vr_qtPf"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_qtPf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_funcao_vr_qtPf" class="contagempf_funcao_vr_qtPf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_qtPf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->vr_qtPf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->vr_qtPf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$contagempf_funcao_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$contagempf_funcao_grid->StartRec = 1;
$contagempf_funcao_grid->StopRec = $contagempf_funcao_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($contagempf_funcao_grid->FormKeyCountName) && ($contagempf_funcao->CurrentAction == "gridadd" || $contagempf_funcao->CurrentAction == "gridedit" || $contagempf_funcao->CurrentAction == "F")) {
		$contagempf_funcao_grid->KeyCount = $objForm->GetValue($contagempf_funcao_grid->FormKeyCountName);
		$contagempf_funcao_grid->StopRec = $contagempf_funcao_grid->StartRec + $contagempf_funcao_grid->KeyCount - 1;
	}
}
$contagempf_funcao_grid->RecCnt = $contagempf_funcao_grid->StartRec - 1;
if ($contagempf_funcao_grid->Recordset && !$contagempf_funcao_grid->Recordset->EOF) {
	$contagempf_funcao_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $contagempf_funcao_grid->StartRec > 1)
		$contagempf_funcao_grid->Recordset->Move($contagempf_funcao_grid->StartRec - 1);
} elseif (!$contagempf_funcao->AllowAddDeleteRow && $contagempf_funcao_grid->StopRec == 0) {
	$contagempf_funcao_grid->StopRec = $contagempf_funcao->GridAddRowCount;
}

// Initialize aggregate
$contagempf_funcao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$contagempf_funcao->ResetAttrs();
$contagempf_funcao_grid->RenderRow();
if ($contagempf_funcao->CurrentAction == "gridadd")
	$contagempf_funcao_grid->RowIndex = 0;
if ($contagempf_funcao->CurrentAction == "gridedit")
	$contagempf_funcao_grid->RowIndex = 0;
while ($contagempf_funcao_grid->RecCnt < $contagempf_funcao_grid->StopRec) {
	$contagempf_funcao_grid->RecCnt++;
	if (intval($contagempf_funcao_grid->RecCnt) >= intval($contagempf_funcao_grid->StartRec)) {
		$contagempf_funcao_grid->RowCnt++;
		if ($contagempf_funcao->CurrentAction == "gridadd" || $contagempf_funcao->CurrentAction == "gridedit" || $contagempf_funcao->CurrentAction == "F") {
			$contagempf_funcao_grid->RowIndex++;
			$objForm->Index = $contagempf_funcao_grid->RowIndex;
			if ($objForm->HasValue($contagempf_funcao_grid->FormActionName))
				$contagempf_funcao_grid->RowAction = strval($objForm->GetValue($contagempf_funcao_grid->FormActionName));
			elseif ($contagempf_funcao->CurrentAction == "gridadd")
				$contagempf_funcao_grid->RowAction = "insert";
			else
				$contagempf_funcao_grid->RowAction = "";
		}

		// Set up key count
		$contagempf_funcao_grid->KeyCount = $contagempf_funcao_grid->RowIndex;

		// Init row class and style
		$contagempf_funcao->ResetAttrs();
		$contagempf_funcao->CssClass = "";
		if ($contagempf_funcao->CurrentAction == "gridadd") {
			if ($contagempf_funcao->CurrentMode == "copy") {
				$contagempf_funcao_grid->LoadRowValues($contagempf_funcao_grid->Recordset); // Load row values
				$contagempf_funcao_grid->SetRecordKey($contagempf_funcao_grid->RowOldKey, $contagempf_funcao_grid->Recordset); // Set old record key
			} else {
				$contagempf_funcao_grid->LoadDefaultValues(); // Load default values
				$contagempf_funcao_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$contagempf_funcao_grid->LoadRowValues($contagempf_funcao_grid->Recordset); // Load row values
		}
		$contagempf_funcao->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($contagempf_funcao->CurrentAction == "gridadd") // Grid add
			$contagempf_funcao->RowType = EW_ROWTYPE_ADD; // Render add
		if ($contagempf_funcao->CurrentAction == "gridadd" && $contagempf_funcao->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$contagempf_funcao_grid->RestoreCurrentRowFormValues($contagempf_funcao_grid->RowIndex); // Restore form values
		if ($contagempf_funcao->CurrentAction == "gridedit") { // Grid edit
			if ($contagempf_funcao->EventCancelled) {
				$contagempf_funcao_grid->RestoreCurrentRowFormValues($contagempf_funcao_grid->RowIndex); // Restore form values
			}
			if ($contagempf_funcao_grid->RowAction == "insert")
				$contagempf_funcao->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$contagempf_funcao->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($contagempf_funcao->CurrentAction == "gridedit" && ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT || $contagempf_funcao->RowType == EW_ROWTYPE_ADD) && $contagempf_funcao->EventCancelled) // Update failed
			$contagempf_funcao_grid->RestoreCurrentRowFormValues($contagempf_funcao_grid->RowIndex); // Restore form values
		if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) // Edit row
			$contagempf_funcao_grid->EditRowCnt++;
		if ($contagempf_funcao->CurrentAction == "F") // Confirm row
			$contagempf_funcao_grid->RestoreCurrentRowFormValues($contagempf_funcao_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$contagempf_funcao->RowAttrs = array_merge($contagempf_funcao->RowAttrs, array('data-rowindex'=>$contagempf_funcao_grid->RowCnt, 'id'=>'r' . $contagempf_funcao_grid->RowCnt . '_contagempf_funcao', 'data-rowtype'=>$contagempf_funcao->RowType));

		// Render row
		$contagempf_funcao_grid->RenderRow();

		// Render list options
		$contagempf_funcao_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($contagempf_funcao_grid->RowAction <> "delete" && $contagempf_funcao_grid->RowAction <> "insertdelete" && !($contagempf_funcao_grid->RowAction == "insert" && $contagempf_funcao->CurrentAction == "F" && $contagempf_funcao_grid->EmptyRow())) {
?>
	<tr<?php echo $contagempf_funcao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$contagempf_funcao_grid->ListOptions->Render("body", "left", $contagempf_funcao_grid->RowCnt);
?>
	<?php if ($contagempf_funcao->nu_agrupador->Visible) { // nu_agrupador ?>
		<td<?php echo $contagempf_funcao->nu_agrupador->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_nu_agrupador" class="control-group contagempf_funcao_nu_agrupador">
<select data-field="x_nu_agrupador" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador"<?php echo $contagempf_funcao->nu_agrupador->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_agrupador->EditValue)) {
	$arwrk = $contagempf_funcao->nu_agrupador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_agrupador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_agrupador->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_nu_agrupador" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_agrupador->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_nu_agrupador" class="control-group contagempf_funcao_nu_agrupador">
<span<?php echo $contagempf_funcao->nu_agrupador->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_agrupador->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_agrupador" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_agrupador->CurrentValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->nu_agrupador->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_agrupador->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_agrupador" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_agrupador->FormValue) ?>">
<input type="hidden" data-field="x_nu_agrupador" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_agrupador->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_funcao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_funcao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_funcao" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_funcao->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_funcao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_funcao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_funcao" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_funcao->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT || $contagempf_funcao->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_funcao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_funcao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_funcao" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_funcao->CurrentValue) ?>">
<?php } ?>
	<?php if ($contagempf_funcao->nu_uc->Visible) { // nu_uc ?>
		<td<?php echo $contagempf_funcao->nu_uc->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_nu_uc" class="control-group contagempf_funcao_nu_uc">
<select data-field="x_nu_uc" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc"<?php echo $contagempf_funcao->nu_uc->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_uc->EditValue)) {
	$arwrk = $contagempf_funcao->nu_uc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_uc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf_funcao->nu_uc) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_uc->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
 $sWhereWrk = "";
 $lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $contagempf_funcao->Lookup_Selecting($contagempf_funcao->nu_uc, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY [co_alternativo] ASC";
?>
<input type="hidden" name="s_x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" id="s_x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("[nu_uc] = {filter_value}"); ?>&t0=3">
</span>
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_uc->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_nu_uc" class="control-group contagempf_funcao_nu_uc">
<select data-field="x_nu_uc" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc"<?php echo $contagempf_funcao->nu_uc->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_uc->EditValue)) {
	$arwrk = $contagempf_funcao->nu_uc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_uc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf_funcao->nu_uc) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_uc->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
 $sWhereWrk = "";
 $lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $contagempf_funcao->Lookup_Selecting($contagempf_funcao->nu_uc, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY [co_alternativo] ASC";
?>
<input type="hidden" name="s_x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" id="s_x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("[nu_uc] = {filter_value}"); ?>&t0=3">
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->nu_uc->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_uc->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_uc->FormValue) ?>">
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_uc->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->no_funcao->Visible) { // no_funcao ?>
		<td<?php echo $contagempf_funcao->no_funcao->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_no_funcao" class="control-group contagempf_funcao_no_funcao">
<input type="text" data-field="x_no_funcao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" size="100" maxlength="120" placeholder="<?php echo $contagempf_funcao->no_funcao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->no_funcao->EditValue ?>"<?php echo $contagempf_funcao->no_funcao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_funcao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" value="<?php echo ew_HtmlEncode($contagempf_funcao->no_funcao->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_no_funcao" class="control-group contagempf_funcao_no_funcao">
<input type="text" data-field="x_no_funcao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" size="100" maxlength="120" placeholder="<?php echo $contagempf_funcao->no_funcao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->no_funcao->EditValue ?>"<?php echo $contagempf_funcao->no_funcao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->no_funcao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->no_funcao->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_funcao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" value="<?php echo ew_HtmlEncode($contagempf_funcao->no_funcao->FormValue) ?>">
<input type="hidden" data-field="x_no_funcao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" value="<?php echo ew_HtmlEncode($contagempf_funcao->no_funcao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->nu_tpManutencao->Visible) { // nu_tpManutencao ?>
		<td<?php echo $contagempf_funcao->nu_tpManutencao->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_nu_tpManutencao" class="control-group contagempf_funcao_nu_tpManutencao">
<?php $contagempf_funcao->nu_tpManutencao->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $contagempf_funcao_grid->RowIndex . "_nu_tpElemento']); " . @$contagempf_funcao->nu_tpManutencao->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpManutencao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao"<?php echo $contagempf_funcao->nu_tpManutencao->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_tpManutencao->EditValue)) {
	$arwrk = $contagempf_funcao->nu_tpManutencao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_tpManutencao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_tpManutencao->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempf_funcaogrid.Lists["x_nu_tpManutencao"].Options = <?php echo (is_array($contagempf_funcao->nu_tpManutencao->EditValue)) ? ew_ArrayToJson($contagempf_funcao->nu_tpManutencao->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_tpManutencao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpManutencao->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_nu_tpManutencao" class="control-group contagempf_funcao_nu_tpManutencao">
<?php $contagempf_funcao->nu_tpManutencao->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $contagempf_funcao_grid->RowIndex . "_nu_tpElemento']); " . @$contagempf_funcao->nu_tpManutencao->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpManutencao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao"<?php echo $contagempf_funcao->nu_tpManutencao->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_tpManutencao->EditValue)) {
	$arwrk = $contagempf_funcao->nu_tpManutencao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_tpManutencao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_tpManutencao->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempf_funcaogrid.Lists["x_nu_tpManutencao"].Options = <?php echo (is_array($contagempf_funcao->nu_tpManutencao->EditValue)) ? ew_ArrayToJson($contagempf_funcao->nu_tpManutencao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->nu_tpManutencao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpManutencao->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_tpManutencao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpManutencao->FormValue) ?>">
<input type="hidden" data-field="x_nu_tpManutencao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpManutencao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->nu_tpElemento->Visible) { // nu_tpElemento ?>
		<td<?php echo $contagempf_funcao->nu_tpElemento->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_nu_tpElemento" class="control-group contagempf_funcao_nu_tpElemento">
<select data-field="x_nu_tpElemento" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento"<?php echo $contagempf_funcao->nu_tpElemento->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_tpElemento->EditValue)) {
	$arwrk = $contagempf_funcao->nu_tpElemento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_tpElemento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_tpElemento->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempf_funcaogrid.Lists["x_nu_tpElemento"].Options = <?php echo (is_array($contagempf_funcao->nu_tpElemento->EditValue)) ? ew_ArrayToJson($contagempf_funcao->nu_tpElemento->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_tpElemento" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpElemento->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_nu_tpElemento" class="control-group contagempf_funcao_nu_tpElemento">
<select data-field="x_nu_tpElemento" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento"<?php echo $contagempf_funcao->nu_tpElemento->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_tpElemento->EditValue)) {
	$arwrk = $contagempf_funcao->nu_tpElemento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_tpElemento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_tpElemento->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempf_funcaogrid.Lists["x_nu_tpElemento"].Options = <?php echo (is_array($contagempf_funcao->nu_tpElemento->EditValue)) ? ew_ArrayToJson($contagempf_funcao->nu_tpElemento->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->nu_tpElemento->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpElemento->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_tpElemento" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpElemento->FormValue) ?>">
<input type="hidden" data-field="x_nu_tpElemento" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpElemento->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->qt_alr->Visible) { // qt_alr ?>
		<td<?php echo $contagempf_funcao->qt_alr->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_qt_alr" class="control-group contagempf_funcao_qt_alr">
<input type="text" data-field="x_qt_alr" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" size="4" maxlength="4" placeholder="<?php echo $contagempf_funcao->qt_alr->PlaceHolder ?>" value="<?php echo $contagempf_funcao->qt_alr->EditValue ?>"<?php echo $contagempf_funcao->qt_alr->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_alr" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_alr->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_qt_alr" class="control-group contagempf_funcao_qt_alr">
<input type="text" data-field="x_qt_alr" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" size="4" maxlength="4" placeholder="<?php echo $contagempf_funcao->qt_alr->PlaceHolder ?>" value="<?php echo $contagempf_funcao->qt_alr->EditValue ?>"<?php echo $contagempf_funcao->qt_alr->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->qt_alr->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_alr->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_alr" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_alr->FormValue) ?>">
<input type="hidden" data-field="x_qt_alr" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_alr->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->qt_der->Visible) { // qt_der ?>
		<td<?php echo $contagempf_funcao->qt_der->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_qt_der" class="control-group contagempf_funcao_qt_der">
<input type="text" data-field="x_qt_der" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" size="4" maxlength="4" placeholder="<?php echo $contagempf_funcao->qt_der->PlaceHolder ?>" value="<?php echo $contagempf_funcao->qt_der->EditValue ?>"<?php echo $contagempf_funcao->qt_der->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_der" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_der->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_qt_der" class="control-group contagempf_funcao_qt_der">
<input type="text" data-field="x_qt_der" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" size="4" maxlength="4" placeholder="<?php echo $contagempf_funcao->qt_der->PlaceHolder ?>" value="<?php echo $contagempf_funcao->qt_der->EditValue ?>"<?php echo $contagempf_funcao->qt_der->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->qt_der->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_der->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_der" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_der->FormValue) ?>">
<input type="hidden" data-field="x_qt_der" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_der->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->ic_complexApf->Visible) { // ic_complexApf ?>
		<td<?php echo $contagempf_funcao->ic_complexApf->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_ic_complexApf" class="control-group contagempf_funcao_ic_complexApf">
<input type="text" data-field="x_ic_complexApf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" size="30" placeholder="<?php echo $contagempf_funcao->ic_complexApf->PlaceHolder ?>" value="<?php echo $contagempf_funcao->ic_complexApf->EditValue ?>"<?php echo $contagempf_funcao->ic_complexApf->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_ic_complexApf" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" value="<?php echo ew_HtmlEncode($contagempf_funcao->ic_complexApf->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_ic_complexApf" class="control-group contagempf_funcao_ic_complexApf">
<input type="text" data-field="x_ic_complexApf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" size="30" placeholder="<?php echo $contagempf_funcao->ic_complexApf->PlaceHolder ?>" value="<?php echo $contagempf_funcao->ic_complexApf->EditValue ?>"<?php echo $contagempf_funcao->ic_complexApf->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->ic_complexApf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->ic_complexApf->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_complexApf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" value="<?php echo ew_HtmlEncode($contagempf_funcao->ic_complexApf->FormValue) ?>">
<input type="hidden" data-field="x_ic_complexApf" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" value="<?php echo ew_HtmlEncode($contagempf_funcao->ic_complexApf->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->vr_contribuicao->Visible) { // vr_contribuicao ?>
		<td<?php echo $contagempf_funcao->vr_contribuicao->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_vr_contribuicao" class="control-group contagempf_funcao_vr_contribuicao">
<input type="text" data-field="x_vr_contribuicao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" size="30" placeholder="<?php echo $contagempf_funcao->vr_contribuicao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_contribuicao->EditValue ?>"<?php echo $contagempf_funcao->vr_contribuicao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_contribuicao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_contribuicao->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_vr_contribuicao" class="control-group contagempf_funcao_vr_contribuicao">
<input type="text" data-field="x_vr_contribuicao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" size="30" placeholder="<?php echo $contagempf_funcao->vr_contribuicao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_contribuicao->EditValue ?>"<?php echo $contagempf_funcao->vr_contribuicao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->vr_contribuicao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_contribuicao->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_contribuicao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_contribuicao->FormValue) ?>">
<input type="hidden" data-field="x_vr_contribuicao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_contribuicao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->vr_fatorReducao->Visible) { // vr_fatorReducao ?>
		<td<?php echo $contagempf_funcao->vr_fatorReducao->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_vr_fatorReducao" class="control-group contagempf_funcao_vr_fatorReducao">
<input type="text" data-field="x_vr_fatorReducao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" size="30" placeholder="<?php echo $contagempf_funcao->vr_fatorReducao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_fatorReducao->EditValue ?>"<?php echo $contagempf_funcao->vr_fatorReducao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_fatorReducao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_fatorReducao->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_vr_fatorReducao" class="control-group contagempf_funcao_vr_fatorReducao">
<input type="text" data-field="x_vr_fatorReducao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" size="30" placeholder="<?php echo $contagempf_funcao->vr_fatorReducao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_fatorReducao->EditValue ?>"<?php echo $contagempf_funcao->vr_fatorReducao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->vr_fatorReducao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_fatorReducao->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_fatorReducao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_fatorReducao->FormValue) ?>">
<input type="hidden" data-field="x_vr_fatorReducao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_fatorReducao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
		<td<?php echo $contagempf_funcao->pc_varFasesRoteiro->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_pc_varFasesRoteiro" class="control-group contagempf_funcao_pc_varFasesRoteiro">
<input type="text" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" size="30" placeholder="<?php echo $contagempf_funcao->pc_varFasesRoteiro->PlaceHolder ?>" value="<?php echo $contagempf_funcao->pc_varFasesRoteiro->EditValue ?>"<?php echo $contagempf_funcao->pc_varFasesRoteiro->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf_funcao->pc_varFasesRoteiro->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_pc_varFasesRoteiro" class="control-group contagempf_funcao_pc_varFasesRoteiro">
<span<?php echo $contagempf_funcao->pc_varFasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf_funcao->pc_varFasesRoteiro->EditValue ?></span>
</span>
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf_funcao->pc_varFasesRoteiro->CurrentValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->pc_varFasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf_funcao->pc_varFasesRoteiro->ListViewValue() ?></span>
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf_funcao->pc_varFasesRoteiro->FormValue) ?>">
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf_funcao->pc_varFasesRoteiro->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->vr_qtPf->Visible) { // vr_qtPf ?>
		<td<?php echo $contagempf_funcao->vr_qtPf->CellAttributes() ?>>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_vr_qtPf" class="control-group contagempf_funcao_vr_qtPf">
<input type="text" data-field="x_vr_qtPf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" size="10" placeholder="<?php echo $contagempf_funcao->vr_qtPf->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_qtPf->EditValue ?>"<?php echo $contagempf_funcao->vr_qtPf->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_qtPf" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_qtPf->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_funcao_grid->RowCnt ?>_contagempf_funcao_vr_qtPf" class="control-group contagempf_funcao_vr_qtPf">
<input type="text" data-field="x_vr_qtPf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" size="10" placeholder="<?php echo $contagempf_funcao->vr_qtPf->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_qtPf->EditValue ?>"<?php echo $contagempf_funcao->vr_qtPf->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_funcao->vr_qtPf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_qtPf->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_qtPf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_qtPf->FormValue) ?>">
<input type="hidden" data-field="x_vr_qtPf" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_qtPf->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_funcao_grid->PageObjName . "_row_" . $contagempf_funcao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$contagempf_funcao_grid->ListOptions->Render("body", "right", $contagempf_funcao_grid->RowCnt);
?>
	</tr>
<?php if ($contagempf_funcao->RowType == EW_ROWTYPE_ADD || $contagempf_funcao->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcontagempf_funcaogrid.UpdateOpts(<?php echo $contagempf_funcao_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($contagempf_funcao->CurrentAction <> "gridadd" || $contagempf_funcao->CurrentMode == "copy")
		if (!$contagempf_funcao_grid->Recordset->EOF) $contagempf_funcao_grid->Recordset->MoveNext();
}
?>
<?php
	if ($contagempf_funcao->CurrentMode == "add" || $contagempf_funcao->CurrentMode == "copy" || $contagempf_funcao->CurrentMode == "edit") {
		$contagempf_funcao_grid->RowIndex = '$rowindex$';
		$contagempf_funcao_grid->LoadDefaultValues();

		// Set row properties
		$contagempf_funcao->ResetAttrs();
		$contagempf_funcao->RowAttrs = array_merge($contagempf_funcao->RowAttrs, array('data-rowindex'=>$contagempf_funcao_grid->RowIndex, 'id'=>'r0_contagempf_funcao', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($contagempf_funcao->RowAttrs["class"], "ewTemplate");
		$contagempf_funcao->RowType = EW_ROWTYPE_ADD;

		// Render row
		$contagempf_funcao_grid->RenderRow();

		// Render list options
		$contagempf_funcao_grid->RenderListOptions();
		$contagempf_funcao_grid->StartRowCnt = 0;
?>
	<tr<?php echo $contagempf_funcao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$contagempf_funcao_grid->ListOptions->Render("body", "left", $contagempf_funcao_grid->RowIndex);
?>
	<?php if ($contagempf_funcao->nu_agrupador->Visible) { // nu_agrupador ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<select data-field="x_nu_agrupador" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador"<?php echo $contagempf_funcao->nu_agrupador->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_agrupador->EditValue)) {
	$arwrk = $contagempf_funcao->nu_agrupador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_agrupador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_agrupador->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $contagempf_funcao->nu_agrupador->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_agrupador->ViewValue ?></span>
<input type="hidden" data-field="x_nu_agrupador" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_agrupador->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_agrupador" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_agrupador" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_agrupador->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->nu_uc->Visible) { // nu_uc ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<select data-field="x_nu_uc" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc"<?php echo $contagempf_funcao->nu_uc->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_uc->EditValue)) {
	$arwrk = $contagempf_funcao->nu_uc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_uc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf_funcao->nu_uc) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_uc->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
 $sWhereWrk = "";
 $lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
 if (strval($lookuptblfilter) <> "") {
 	ew_AddFilter($sWhereWrk, $lookuptblfilter);
 }

 // Call Lookup selecting
 $contagempf_funcao->Lookup_Selecting($contagempf_funcao->nu_uc, $sWhereWrk);
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " ORDER BY [co_alternativo] ASC";
?>
<input type="hidden" name="s_x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" id="s_x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("[nu_uc] = {filter_value}"); ?>&t0=3">
<?php } else { ?>
<span<?php echo $contagempf_funcao->nu_uc->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_uc->ViewValue ?></span>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_uc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_uc->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->no_funcao->Visible) { // no_funcao ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_funcao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" size="100" maxlength="120" placeholder="<?php echo $contagempf_funcao->no_funcao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->no_funcao->EditValue ?>"<?php echo $contagempf_funcao->no_funcao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf_funcao->no_funcao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->no_funcao->ViewValue ?></span>
<input type="hidden" data-field="x_no_funcao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" value="<?php echo ew_HtmlEncode($contagempf_funcao->no_funcao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_funcao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_no_funcao" value="<?php echo ew_HtmlEncode($contagempf_funcao->no_funcao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->nu_tpManutencao->Visible) { // nu_tpManutencao ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<?php $contagempf_funcao->nu_tpManutencao->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $contagempf_funcao_grid->RowIndex . "_nu_tpElemento']); " . @$contagempf_funcao->nu_tpManutencao->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpManutencao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao"<?php echo $contagempf_funcao->nu_tpManutencao->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_tpManutencao->EditValue)) {
	$arwrk = $contagempf_funcao->nu_tpManutencao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_tpManutencao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_tpManutencao->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempf_funcaogrid.Lists["x_nu_tpManutencao"].Options = <?php echo (is_array($contagempf_funcao->nu_tpManutencao->EditValue)) ? ew_ArrayToJson($contagempf_funcao->nu_tpManutencao->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $contagempf_funcao->nu_tpManutencao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpManutencao->ViewValue ?></span>
<input type="hidden" data-field="x_nu_tpManutencao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpManutencao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_tpManutencao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpManutencao" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpManutencao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->nu_tpElemento->Visible) { // nu_tpElemento ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<select data-field="x_nu_tpElemento" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento"<?php echo $contagempf_funcao->nu_tpElemento->EditAttributes() ?>>
<?php
if (is_array($contagempf_funcao->nu_tpElemento->EditValue)) {
	$arwrk = $contagempf_funcao->nu_tpElemento->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf_funcao->nu_tpElemento->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf_funcao->nu_tpElemento->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempf_funcaogrid.Lists["x_nu_tpElemento"].Options = <?php echo (is_array($contagempf_funcao->nu_tpElemento->EditValue)) ? ew_ArrayToJson($contagempf_funcao->nu_tpElemento->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $contagempf_funcao->nu_tpElemento->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpElemento->ViewValue ?></span>
<input type="hidden" data-field="x_nu_tpElemento" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpElemento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_tpElemento" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_nu_tpElemento" value="<?php echo ew_HtmlEncode($contagempf_funcao->nu_tpElemento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->qt_alr->Visible) { // qt_alr ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_alr" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" size="4" maxlength="4" placeholder="<?php echo $contagempf_funcao->qt_alr->PlaceHolder ?>" value="<?php echo $contagempf_funcao->qt_alr->EditValue ?>"<?php echo $contagempf_funcao->qt_alr->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf_funcao->qt_alr->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_alr->ViewValue ?></span>
<input type="hidden" data-field="x_qt_alr" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_alr->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_alr" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_alr" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_alr->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->qt_der->Visible) { // qt_der ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_der" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" size="4" maxlength="4" placeholder="<?php echo $contagempf_funcao->qt_der->PlaceHolder ?>" value="<?php echo $contagempf_funcao->qt_der->EditValue ?>"<?php echo $contagempf_funcao->qt_der->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf_funcao->qt_der->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_der->ViewValue ?></span>
<input type="hidden" data-field="x_qt_der" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_der->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_der" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_qt_der" value="<?php echo ew_HtmlEncode($contagempf_funcao->qt_der->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->ic_complexApf->Visible) { // ic_complexApf ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_ic_complexApf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" size="30" placeholder="<?php echo $contagempf_funcao->ic_complexApf->PlaceHolder ?>" value="<?php echo $contagempf_funcao->ic_complexApf->EditValue ?>"<?php echo $contagempf_funcao->ic_complexApf->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf_funcao->ic_complexApf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->ic_complexApf->ViewValue ?></span>
<input type="hidden" data-field="x_ic_complexApf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" value="<?php echo ew_HtmlEncode($contagempf_funcao->ic_complexApf->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_complexApf" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_ic_complexApf" value="<?php echo ew_HtmlEncode($contagempf_funcao->ic_complexApf->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->vr_contribuicao->Visible) { // vr_contribuicao ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_contribuicao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" size="30" placeholder="<?php echo $contagempf_funcao->vr_contribuicao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_contribuicao->EditValue ?>"<?php echo $contagempf_funcao->vr_contribuicao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf_funcao->vr_contribuicao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_contribuicao->ViewValue ?></span>
<input type="hidden" data-field="x_vr_contribuicao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_contribuicao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_contribuicao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_contribuicao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_contribuicao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->vr_fatorReducao->Visible) { // vr_fatorReducao ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_fatorReducao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" size="30" placeholder="<?php echo $contagempf_funcao->vr_fatorReducao->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_fatorReducao->EditValue ?>"<?php echo $contagempf_funcao->vr_fatorReducao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf_funcao->vr_fatorReducao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_fatorReducao->ViewValue ?></span>
<input type="hidden" data-field="x_vr_fatorReducao" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_fatorReducao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_fatorReducao" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_fatorReducao" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_fatorReducao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" size="30" placeholder="<?php echo $contagempf_funcao->pc_varFasesRoteiro->PlaceHolder ?>" value="<?php echo $contagempf_funcao->pc_varFasesRoteiro->EditValue ?>"<?php echo $contagempf_funcao->pc_varFasesRoteiro->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf_funcao->pc_varFasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf_funcao->pc_varFasesRoteiro->ViewValue ?></span>
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf_funcao->pc_varFasesRoteiro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf_funcao->pc_varFasesRoteiro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf_funcao->vr_qtPf->Visible) { // vr_qtPf ?>
		<td>
<?php if ($contagempf_funcao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_qtPf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" size="10" placeholder="<?php echo $contagempf_funcao->vr_qtPf->PlaceHolder ?>" value="<?php echo $contagempf_funcao->vr_qtPf->EditValue ?>"<?php echo $contagempf_funcao->vr_qtPf->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf_funcao->vr_qtPf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_qtPf->ViewValue ?></span>
<input type="hidden" data-field="x_vr_qtPf" name="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" id="x<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_qtPf->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_qtPf" name="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" id="o<?php echo $contagempf_funcao_grid->RowIndex ?>_vr_qtPf" value="<?php echo ew_HtmlEncode($contagempf_funcao->vr_qtPf->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$contagempf_funcao_grid->ListOptions->Render("body", "right", $contagempf_funcao_grid->RowCnt);
?>
<script type="text/javascript">
fcontagempf_funcaogrid.UpdateOpts(<?php echo $contagempf_funcao_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($contagempf_funcao->CurrentMode == "add" || $contagempf_funcao->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $contagempf_funcao_grid->FormKeyCountName ?>" id="<?php echo $contagempf_funcao_grid->FormKeyCountName ?>" value="<?php echo $contagempf_funcao_grid->KeyCount ?>">
<?php echo $contagempf_funcao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($contagempf_funcao->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $contagempf_funcao_grid->FormKeyCountName ?>" id="<?php echo $contagempf_funcao_grid->FormKeyCountName ?>" value="<?php echo $contagempf_funcao_grid->KeyCount ?>">
<?php echo $contagempf_funcao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($contagempf_funcao->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcontagempf_funcaogrid">
</div>
<?php

// Close recordset
if ($contagempf_funcao_grid->Recordset)
	$contagempf_funcao_grid->Recordset->Close();
?>
<?php if ($contagempf_funcao_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($contagempf_funcao_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($contagempf_funcao->Export == "") { ?>
<script type="text/javascript">
fcontagempf_funcaogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$contagempf_funcao_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$contagempf_funcao_grid->Page_Terminate();
?>
