<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($ambiente_valoracao_grid)) $ambiente_valoracao_grid = new cambiente_valoracao_grid();

// Page init
$ambiente_valoracao_grid->Page_Init();

// Page main
$ambiente_valoracao_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_valoracao_grid->Page_Render();
?>
<?php if ($ambiente_valoracao->Export == "") { ?>
<script type="text/javascript">

// Page object
var ambiente_valoracao_grid = new ew_Page("ambiente_valoracao_grid");
ambiente_valoracao_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = ambiente_valoracao_grid.PageID; // For backward compatibility

// Form object
var fambiente_valoracaogrid = new ew_Form("fambiente_valoracaogrid");
fambiente_valoracaogrid.FormKeyCountName = '<?php echo $ambiente_valoracao_grid->FormKeyCountName ?>';

// Validate form
fambiente_valoracaogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_versaoValoracao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->nu_versaoValoracao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versaoValoracao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->nu_versaoValoracao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_metCalibracao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->ic_metCalibracao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_linhasCodLingPf");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->qt_linhasCodLingPf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMin");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMin->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMin");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMed");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMed->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMed");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMed->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMax");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMax->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_ipMax");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_valoracao->vr_ipMax->FldErrMsg()) ?>");

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
fambiente_valoracaogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_versaoValoracao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_metCalibracao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_linhasCodLingPf", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_ipMin", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_ipMed", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_ipMax", false)) return false;
	return true;
}

// Form_CustomValidate event
fambiente_valoracaogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_valoracaogrid.ValidateRequired = true;
<?php } else { ?>
fambiente_valoracaogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($ambiente_valoracao->getCurrentMasterTable() == "" && $ambiente_valoracao_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $ambiente_valoracao_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($ambiente_valoracao->CurrentAction == "gridadd") {
	if ($ambiente_valoracao->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$ambiente_valoracao_grid->TotalRecs = $ambiente_valoracao->SelectRecordCount();
			$ambiente_valoracao_grid->Recordset = $ambiente_valoracao_grid->LoadRecordset($ambiente_valoracao_grid->StartRec-1, $ambiente_valoracao_grid->DisplayRecs);
		} else {
			if ($ambiente_valoracao_grid->Recordset = $ambiente_valoracao_grid->LoadRecordset())
				$ambiente_valoracao_grid->TotalRecs = $ambiente_valoracao_grid->Recordset->RecordCount();
		}
		$ambiente_valoracao_grid->StartRec = 1;
		$ambiente_valoracao_grid->DisplayRecs = $ambiente_valoracao_grid->TotalRecs;
	} else {
		$ambiente_valoracao->CurrentFilter = "0=1";
		$ambiente_valoracao_grid->StartRec = 1;
		$ambiente_valoracao_grid->DisplayRecs = $ambiente_valoracao->GridAddRowCount;
	}
	$ambiente_valoracao_grid->TotalRecs = $ambiente_valoracao_grid->DisplayRecs;
	$ambiente_valoracao_grid->StopRec = $ambiente_valoracao_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$ambiente_valoracao_grid->TotalRecs = $ambiente_valoracao->SelectRecordCount();
	} else {
		if ($ambiente_valoracao_grid->Recordset = $ambiente_valoracao_grid->LoadRecordset())
			$ambiente_valoracao_grid->TotalRecs = $ambiente_valoracao_grid->Recordset->RecordCount();
	}
	$ambiente_valoracao_grid->StartRec = 1;
	$ambiente_valoracao_grid->DisplayRecs = $ambiente_valoracao_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$ambiente_valoracao_grid->Recordset = $ambiente_valoracao_grid->LoadRecordset($ambiente_valoracao_grid->StartRec-1, $ambiente_valoracao_grid->DisplayRecs);
}
$ambiente_valoracao_grid->RenderOtherOptions();
?>
<?php $ambiente_valoracao_grid->ShowPageHeader(); ?>
<?php
$ambiente_valoracao_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fambiente_valoracaogrid" class="ewForm form-horizontal">
<div id="gmp_ambiente_valoracao" class="ewGridMiddlePanel">
<table id="tbl_ambiente_valoracaogrid" class="ewTable ewTableSeparate">
<?php echo $ambiente_valoracao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$ambiente_valoracao_grid->RenderListOptions();

// Render list options (header, left)
$ambiente_valoracao_grid->ListOptions->Render("header", "left");
?>
<?php if ($ambiente_valoracao->nu_versaoValoracao->Visible) { // nu_versaoValoracao ?>
	<?php if ($ambiente_valoracao->SortUrl($ambiente_valoracao->nu_versaoValoracao) == "") { ?>
		<td><div id="elh_ambiente_valoracao_nu_versaoValoracao" class="ambiente_valoracao_nu_versaoValoracao"><div class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->nu_versaoValoracao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_valoracao_nu_versaoValoracao" class="ambiente_valoracao_nu_versaoValoracao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->nu_versaoValoracao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_valoracao->nu_versaoValoracao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_valoracao->nu_versaoValoracao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_valoracao->ic_metCalibracao->Visible) { // ic_metCalibracao ?>
	<?php if ($ambiente_valoracao->SortUrl($ambiente_valoracao->ic_metCalibracao) == "") { ?>
		<td><div id="elh_ambiente_valoracao_ic_metCalibracao" class="ambiente_valoracao_ic_metCalibracao"><div class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->ic_metCalibracao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_valoracao_ic_metCalibracao" class="ambiente_valoracao_ic_metCalibracao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->ic_metCalibracao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_valoracao->ic_metCalibracao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_valoracao->ic_metCalibracao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_valoracao->dh_inclusao->Visible) { // dh_inclusao ?>
	<?php if ($ambiente_valoracao->SortUrl($ambiente_valoracao->dh_inclusao) == "") { ?>
		<td><div id="elh_ambiente_valoracao_dh_inclusao" class="ambiente_valoracao_dh_inclusao"><div class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->dh_inclusao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_valoracao_dh_inclusao" class="ambiente_valoracao_dh_inclusao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->dh_inclusao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_valoracao->dh_inclusao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_valoracao->dh_inclusao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_valoracao->qt_linhasCodLingPf->Visible) { // qt_linhasCodLingPf ?>
	<?php if ($ambiente_valoracao->SortUrl($ambiente_valoracao->qt_linhasCodLingPf) == "") { ?>
		<td><div id="elh_ambiente_valoracao_qt_linhasCodLingPf" class="ambiente_valoracao_qt_linhasCodLingPf"><div class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->qt_linhasCodLingPf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_valoracao_qt_linhasCodLingPf" class="ambiente_valoracao_qt_linhasCodLingPf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->qt_linhasCodLingPf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_valoracao->qt_linhasCodLingPf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_valoracao->qt_linhasCodLingPf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_valoracao->vr_ipMin->Visible) { // vr_ipMin ?>
	<?php if ($ambiente_valoracao->SortUrl($ambiente_valoracao->vr_ipMin) == "") { ?>
		<td><div id="elh_ambiente_valoracao_vr_ipMin" class="ambiente_valoracao_vr_ipMin"><div class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->vr_ipMin->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_valoracao_vr_ipMin" class="ambiente_valoracao_vr_ipMin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->vr_ipMin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_valoracao->vr_ipMin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_valoracao->vr_ipMin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_valoracao->vr_ipMed->Visible) { // vr_ipMed ?>
	<?php if ($ambiente_valoracao->SortUrl($ambiente_valoracao->vr_ipMed) == "") { ?>
		<td><div id="elh_ambiente_valoracao_vr_ipMed" class="ambiente_valoracao_vr_ipMed"><div class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->vr_ipMed->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_valoracao_vr_ipMed" class="ambiente_valoracao_vr_ipMed">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->vr_ipMed->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_valoracao->vr_ipMed->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_valoracao->vr_ipMed->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_valoracao->vr_ipMax->Visible) { // vr_ipMax ?>
	<?php if ($ambiente_valoracao->SortUrl($ambiente_valoracao->vr_ipMax) == "") { ?>
		<td><div id="elh_ambiente_valoracao_vr_ipMax" class="ambiente_valoracao_vr_ipMax"><div class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->vr_ipMax->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_valoracao_vr_ipMax" class="ambiente_valoracao_vr_ipMax">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_valoracao->vr_ipMax->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_valoracao->vr_ipMax->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_valoracao->vr_ipMax->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ambiente_valoracao_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$ambiente_valoracao_grid->StartRec = 1;
$ambiente_valoracao_grid->StopRec = $ambiente_valoracao_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($ambiente_valoracao_grid->FormKeyCountName) && ($ambiente_valoracao->CurrentAction == "gridadd" || $ambiente_valoracao->CurrentAction == "gridedit" || $ambiente_valoracao->CurrentAction == "F")) {
		$ambiente_valoracao_grid->KeyCount = $objForm->GetValue($ambiente_valoracao_grid->FormKeyCountName);
		$ambiente_valoracao_grid->StopRec = $ambiente_valoracao_grid->StartRec + $ambiente_valoracao_grid->KeyCount - 1;
	}
}
$ambiente_valoracao_grid->RecCnt = $ambiente_valoracao_grid->StartRec - 1;
if ($ambiente_valoracao_grid->Recordset && !$ambiente_valoracao_grid->Recordset->EOF) {
	$ambiente_valoracao_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $ambiente_valoracao_grid->StartRec > 1)
		$ambiente_valoracao_grid->Recordset->Move($ambiente_valoracao_grid->StartRec - 1);
} elseif (!$ambiente_valoracao->AllowAddDeleteRow && $ambiente_valoracao_grid->StopRec == 0) {
	$ambiente_valoracao_grid->StopRec = $ambiente_valoracao->GridAddRowCount;
}

// Initialize aggregate
$ambiente_valoracao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ambiente_valoracao->ResetAttrs();
$ambiente_valoracao_grid->RenderRow();
if ($ambiente_valoracao->CurrentAction == "gridadd")
	$ambiente_valoracao_grid->RowIndex = 0;
if ($ambiente_valoracao->CurrentAction == "gridedit")
	$ambiente_valoracao_grid->RowIndex = 0;
while ($ambiente_valoracao_grid->RecCnt < $ambiente_valoracao_grid->StopRec) {
	$ambiente_valoracao_grid->RecCnt++;
	if (intval($ambiente_valoracao_grid->RecCnt) >= intval($ambiente_valoracao_grid->StartRec)) {
		$ambiente_valoracao_grid->RowCnt++;
		if ($ambiente_valoracao->CurrentAction == "gridadd" || $ambiente_valoracao->CurrentAction == "gridedit" || $ambiente_valoracao->CurrentAction == "F") {
			$ambiente_valoracao_grid->RowIndex++;
			$objForm->Index = $ambiente_valoracao_grid->RowIndex;
			if ($objForm->HasValue($ambiente_valoracao_grid->FormActionName))
				$ambiente_valoracao_grid->RowAction = strval($objForm->GetValue($ambiente_valoracao_grid->FormActionName));
			elseif ($ambiente_valoracao->CurrentAction == "gridadd")
				$ambiente_valoracao_grid->RowAction = "insert";
			else
				$ambiente_valoracao_grid->RowAction = "";
		}

		// Set up key count
		$ambiente_valoracao_grid->KeyCount = $ambiente_valoracao_grid->RowIndex;

		// Init row class and style
		$ambiente_valoracao->ResetAttrs();
		$ambiente_valoracao->CssClass = "";
		if ($ambiente_valoracao->CurrentAction == "gridadd") {
			if ($ambiente_valoracao->CurrentMode == "copy") {
				$ambiente_valoracao_grid->LoadRowValues($ambiente_valoracao_grid->Recordset); // Load row values
				$ambiente_valoracao_grid->SetRecordKey($ambiente_valoracao_grid->RowOldKey, $ambiente_valoracao_grid->Recordset); // Set old record key
			} else {
				$ambiente_valoracao_grid->LoadDefaultValues(); // Load default values
				$ambiente_valoracao_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$ambiente_valoracao_grid->LoadRowValues($ambiente_valoracao_grid->Recordset); // Load row values
		}
		$ambiente_valoracao->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($ambiente_valoracao->CurrentAction == "gridadd") // Grid add
			$ambiente_valoracao->RowType = EW_ROWTYPE_ADD; // Render add
		if ($ambiente_valoracao->CurrentAction == "gridadd" && $ambiente_valoracao->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$ambiente_valoracao_grid->RestoreCurrentRowFormValues($ambiente_valoracao_grid->RowIndex); // Restore form values
		if ($ambiente_valoracao->CurrentAction == "gridedit") { // Grid edit
			if ($ambiente_valoracao->EventCancelled) {
				$ambiente_valoracao_grid->RestoreCurrentRowFormValues($ambiente_valoracao_grid->RowIndex); // Restore form values
			}
			if ($ambiente_valoracao_grid->RowAction == "insert")
				$ambiente_valoracao->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$ambiente_valoracao->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($ambiente_valoracao->CurrentAction == "gridedit" && ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT || $ambiente_valoracao->RowType == EW_ROWTYPE_ADD) && $ambiente_valoracao->EventCancelled) // Update failed
			$ambiente_valoracao_grid->RestoreCurrentRowFormValues($ambiente_valoracao_grid->RowIndex); // Restore form values
		if ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT) // Edit row
			$ambiente_valoracao_grid->EditRowCnt++;
		if ($ambiente_valoracao->CurrentAction == "F") // Confirm row
			$ambiente_valoracao_grid->RestoreCurrentRowFormValues($ambiente_valoracao_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$ambiente_valoracao->RowAttrs = array_merge($ambiente_valoracao->RowAttrs, array('data-rowindex'=>$ambiente_valoracao_grid->RowCnt, 'id'=>'r' . $ambiente_valoracao_grid->RowCnt . '_ambiente_valoracao', 'data-rowtype'=>$ambiente_valoracao->RowType));

		// Render row
		$ambiente_valoracao_grid->RenderRow();

		// Render list options
		$ambiente_valoracao_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($ambiente_valoracao_grid->RowAction <> "delete" && $ambiente_valoracao_grid->RowAction <> "insertdelete" && !($ambiente_valoracao_grid->RowAction == "insert" && $ambiente_valoracao->CurrentAction == "F" && $ambiente_valoracao_grid->EmptyRow())) {
?>
	<tr<?php echo $ambiente_valoracao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ambiente_valoracao_grid->ListOptions->Render("body", "left", $ambiente_valoracao_grid->RowCnt);
?>
	<?php if ($ambiente_valoracao->nu_versaoValoracao->Visible) { // nu_versaoValoracao ?>
		<td<?php echo $ambiente_valoracao->nu_versaoValoracao->CellAttributes() ?>>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_nu_versaoValoracao" class="control-group ambiente_valoracao_nu_versaoValoracao">
<input type="text" data-field="x_nu_versaoValoracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" size="30" placeholder="<?php echo $ambiente_valoracao->nu_versaoValoracao->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->nu_versaoValoracao->EditValue ?>"<?php echo $ambiente_valoracao->nu_versaoValoracao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_versaoValoracao" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_versaoValoracao->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_nu_versaoValoracao" class="control-group ambiente_valoracao_nu_versaoValoracao">
<span<?php echo $ambiente_valoracao->nu_versaoValoracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_versaoValoracao->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_versaoValoracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_versaoValoracao->CurrentValue) ?>">
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_valoracao->nu_versaoValoracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_versaoValoracao->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_versaoValoracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_versaoValoracao->FormValue) ?>">
<input type="hidden" data-field="x_nu_versaoValoracao" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_versaoValoracao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_valoracao_grid->PageObjName . "_row_" . $ambiente_valoracao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_ambiente" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_ambiente" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_ambiente" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_ambiente->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_ambiente" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_ambiente" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_ambiente" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_ambiente->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT || $ambiente_valoracao->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_ambiente" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_ambiente" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_ambiente" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_ambiente->CurrentValue) ?>">
<?php } ?>
	<?php if ($ambiente_valoracao->ic_metCalibracao->Visible) { // ic_metCalibracao ?>
		<td<?php echo $ambiente_valoracao->ic_metCalibracao->CellAttributes() ?>>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_ic_metCalibracao" class="control-group ambiente_valoracao_ic_metCalibracao">
<div id="tp_x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" value="{value}"<?php echo $ambiente_valoracao->ic_metCalibracao->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ambiente_valoracao->ic_metCalibracao->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->ic_metCalibracao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_metCalibracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ambiente_valoracao->ic_metCalibracao->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ambiente_valoracao->ic_metCalibracao->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_metCalibracao" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->ic_metCalibracao->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_ic_metCalibracao" class="control-group ambiente_valoracao_ic_metCalibracao">
<div id="tp_x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" value="{value}"<?php echo $ambiente_valoracao->ic_metCalibracao->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ambiente_valoracao->ic_metCalibracao->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->ic_metCalibracao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_metCalibracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ambiente_valoracao->ic_metCalibracao->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ambiente_valoracao->ic_metCalibracao->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_valoracao->ic_metCalibracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->ic_metCalibracao->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_metCalibracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->ic_metCalibracao->FormValue) ?>">
<input type="hidden" data-field="x_ic_metCalibracao" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->ic_metCalibracao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_valoracao_grid->PageObjName . "_row_" . $ambiente_valoracao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_valoracao->dh_inclusao->Visible) { // dh_inclusao ?>
		<td<?php echo $ambiente_valoracao->dh_inclusao->CellAttributes() ?>>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->dh_inclusao->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_valoracao->dh_inclusao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->dh_inclusao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dh_inclusao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->dh_inclusao->FormValue) ?>">
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->dh_inclusao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_valoracao_grid->PageObjName . "_row_" . $ambiente_valoracao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_valoracao->qt_linhasCodLingPf->Visible) { // qt_linhasCodLingPf ?>
		<td<?php echo $ambiente_valoracao->qt_linhasCodLingPf->CellAttributes() ?>>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_qt_linhasCodLingPf" class="control-group ambiente_valoracao_qt_linhasCodLingPf">
<input type="text" data-field="x_qt_linhasCodLingPf" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" size="30" placeholder="<?php echo $ambiente_valoracao->qt_linhasCodLingPf->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->qt_linhasCodLingPf->EditValue ?>"<?php echo $ambiente_valoracao->qt_linhasCodLingPf->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_linhasCodLingPf" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" value="<?php echo ew_HtmlEncode($ambiente_valoracao->qt_linhasCodLingPf->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_qt_linhasCodLingPf" class="control-group ambiente_valoracao_qt_linhasCodLingPf">
<input type="text" data-field="x_qt_linhasCodLingPf" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" size="30" placeholder="<?php echo $ambiente_valoracao->qt_linhasCodLingPf->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->qt_linhasCodLingPf->EditValue ?>"<?php echo $ambiente_valoracao->qt_linhasCodLingPf->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_valoracao->qt_linhasCodLingPf->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->qt_linhasCodLingPf->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_linhasCodLingPf" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" value="<?php echo ew_HtmlEncode($ambiente_valoracao->qt_linhasCodLingPf->FormValue) ?>">
<input type="hidden" data-field="x_qt_linhasCodLingPf" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" value="<?php echo ew_HtmlEncode($ambiente_valoracao->qt_linhasCodLingPf->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_valoracao_grid->PageObjName . "_row_" . $ambiente_valoracao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_valoracao->vr_ipMin->Visible) { // vr_ipMin ?>
		<td<?php echo $ambiente_valoracao->vr_ipMin->CellAttributes() ?>>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_vr_ipMin" class="control-group ambiente_valoracao_vr_ipMin">
<input type="text" data-field="x_vr_ipMin" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMin->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMin->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMin->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_ipMin" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMin->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_vr_ipMin" class="control-group ambiente_valoracao_vr_ipMin">
<input type="text" data-field="x_vr_ipMin" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMin->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMin->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMin->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_valoracao->vr_ipMin->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMin->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_ipMin" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMin->FormValue) ?>">
<input type="hidden" data-field="x_vr_ipMin" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMin->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_valoracao_grid->PageObjName . "_row_" . $ambiente_valoracao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_valoracao->vr_ipMed->Visible) { // vr_ipMed ?>
		<td<?php echo $ambiente_valoracao->vr_ipMed->CellAttributes() ?>>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_vr_ipMed" class="control-group ambiente_valoracao_vr_ipMed">
<input type="text" data-field="x_vr_ipMed" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMed->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMed->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMed->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_ipMed" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMed->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_vr_ipMed" class="control-group ambiente_valoracao_vr_ipMed">
<input type="text" data-field="x_vr_ipMed" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMed->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMed->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMed->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_valoracao->vr_ipMed->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMed->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_ipMed" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMed->FormValue) ?>">
<input type="hidden" data-field="x_vr_ipMed" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMed->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_valoracao_grid->PageObjName . "_row_" . $ambiente_valoracao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_valoracao->vr_ipMax->Visible) { // vr_ipMax ?>
		<td<?php echo $ambiente_valoracao->vr_ipMax->CellAttributes() ?>>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_vr_ipMax" class="control-group ambiente_valoracao_vr_ipMax">
<input type="text" data-field="x_vr_ipMax" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMax->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMax->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMax->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_ipMax" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMax->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_valoracao_grid->RowCnt ?>_ambiente_valoracao_vr_ipMax" class="control-group ambiente_valoracao_vr_ipMax">
<input type="text" data-field="x_vr_ipMax" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMax->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMax->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMax->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_valoracao->vr_ipMax->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMax->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_ipMax" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMax->FormValue) ?>">
<input type="hidden" data-field="x_vr_ipMax" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMax->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_valoracao_grid->PageObjName . "_row_" . $ambiente_valoracao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$ambiente_valoracao_grid->ListOptions->Render("body", "right", $ambiente_valoracao_grid->RowCnt);
?>
	</tr>
<?php if ($ambiente_valoracao->RowType == EW_ROWTYPE_ADD || $ambiente_valoracao->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fambiente_valoracaogrid.UpdateOpts(<?php echo $ambiente_valoracao_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($ambiente_valoracao->CurrentAction <> "gridadd" || $ambiente_valoracao->CurrentMode == "copy")
		if (!$ambiente_valoracao_grid->Recordset->EOF) $ambiente_valoracao_grid->Recordset->MoveNext();
}
?>
<?php
	if ($ambiente_valoracao->CurrentMode == "add" || $ambiente_valoracao->CurrentMode == "copy" || $ambiente_valoracao->CurrentMode == "edit") {
		$ambiente_valoracao_grid->RowIndex = '$rowindex$';
		$ambiente_valoracao_grid->LoadDefaultValues();

		// Set row properties
		$ambiente_valoracao->ResetAttrs();
		$ambiente_valoracao->RowAttrs = array_merge($ambiente_valoracao->RowAttrs, array('data-rowindex'=>$ambiente_valoracao_grid->RowIndex, 'id'=>'r0_ambiente_valoracao', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($ambiente_valoracao->RowAttrs["class"], "ewTemplate");
		$ambiente_valoracao->RowType = EW_ROWTYPE_ADD;

		// Render row
		$ambiente_valoracao_grid->RenderRow();

		// Render list options
		$ambiente_valoracao_grid->RenderListOptions();
		$ambiente_valoracao_grid->StartRowCnt = 0;
?>
	<tr<?php echo $ambiente_valoracao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ambiente_valoracao_grid->ListOptions->Render("body", "left", $ambiente_valoracao_grid->RowIndex);
?>
	<?php if ($ambiente_valoracao->nu_versaoValoracao->Visible) { // nu_versaoValoracao ?>
		<td>
<?php if ($ambiente_valoracao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_versaoValoracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" size="30" placeholder="<?php echo $ambiente_valoracao->nu_versaoValoracao->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->nu_versaoValoracao->EditValue ?>"<?php echo $ambiente_valoracao->nu_versaoValoracao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_valoracao->nu_versaoValoracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_versaoValoracao->ViewValue ?></span>
<input type="hidden" data-field="x_nu_versaoValoracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_versaoValoracao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_versaoValoracao" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_nu_versaoValoracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->nu_versaoValoracao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_valoracao->ic_metCalibracao->Visible) { // ic_metCalibracao ?>
		<td>
<?php if ($ambiente_valoracao->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" value="{value}"<?php echo $ambiente_valoracao->ic_metCalibracao->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ambiente_valoracao->ic_metCalibracao->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_valoracao->ic_metCalibracao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_metCalibracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ambiente_valoracao->ic_metCalibracao->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ambiente_valoracao->ic_metCalibracao->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $ambiente_valoracao->ic_metCalibracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->ic_metCalibracao->ViewValue ?></span>
<input type="hidden" data-field="x_ic_metCalibracao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->ic_metCalibracao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_metCalibracao" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_ic_metCalibracao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->ic_metCalibracao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_valoracao->dh_inclusao->Visible) { // dh_inclusao ?>
		<td>
<?php if ($ambiente_valoracao->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $ambiente_valoracao->dh_inclusao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->dh_inclusao->ViewValue ?></span>
<input type="hidden" data-field="x_dh_inclusao" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->dh_inclusao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($ambiente_valoracao->dh_inclusao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_valoracao->qt_linhasCodLingPf->Visible) { // qt_linhasCodLingPf ?>
		<td>
<?php if ($ambiente_valoracao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_linhasCodLingPf" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" size="30" placeholder="<?php echo $ambiente_valoracao->qt_linhasCodLingPf->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->qt_linhasCodLingPf->EditValue ?>"<?php echo $ambiente_valoracao->qt_linhasCodLingPf->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_valoracao->qt_linhasCodLingPf->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->qt_linhasCodLingPf->ViewValue ?></span>
<input type="hidden" data-field="x_qt_linhasCodLingPf" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" value="<?php echo ew_HtmlEncode($ambiente_valoracao->qt_linhasCodLingPf->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_linhasCodLingPf" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_qt_linhasCodLingPf" value="<?php echo ew_HtmlEncode($ambiente_valoracao->qt_linhasCodLingPf->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_valoracao->vr_ipMin->Visible) { // vr_ipMin ?>
		<td>
<?php if ($ambiente_valoracao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_ipMin" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMin->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMin->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMin->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_valoracao->vr_ipMin->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMin->ViewValue ?></span>
<input type="hidden" data-field="x_vr_ipMin" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMin->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_ipMin" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMin" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMin->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_valoracao->vr_ipMed->Visible) { // vr_ipMed ?>
		<td>
<?php if ($ambiente_valoracao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_ipMed" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMed->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMed->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMed->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_valoracao->vr_ipMed->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMed->ViewValue ?></span>
<input type="hidden" data-field="x_vr_ipMed" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMed->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_ipMed" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMed" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMed->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_valoracao->vr_ipMax->Visible) { // vr_ipMax ?>
		<td>
<?php if ($ambiente_valoracao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_ipMax" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" size="30" placeholder="<?php echo $ambiente_valoracao->vr_ipMax->PlaceHolder ?>" value="<?php echo $ambiente_valoracao->vr_ipMax->EditValue ?>"<?php echo $ambiente_valoracao->vr_ipMax->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_valoracao->vr_ipMax->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMax->ViewValue ?></span>
<input type="hidden" data-field="x_vr_ipMax" name="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" id="x<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMax->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_ipMax" name="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" id="o<?php echo $ambiente_valoracao_grid->RowIndex ?>_vr_ipMax" value="<?php echo ew_HtmlEncode($ambiente_valoracao->vr_ipMax->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ambiente_valoracao_grid->ListOptions->Render("body", "right", $ambiente_valoracao_grid->RowCnt);
?>
<script type="text/javascript">
fambiente_valoracaogrid.UpdateOpts(<?php echo $ambiente_valoracao_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($ambiente_valoracao->CurrentMode == "add" || $ambiente_valoracao->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $ambiente_valoracao_grid->FormKeyCountName ?>" id="<?php echo $ambiente_valoracao_grid->FormKeyCountName ?>" value="<?php echo $ambiente_valoracao_grid->KeyCount ?>">
<?php echo $ambiente_valoracao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ambiente_valoracao->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $ambiente_valoracao_grid->FormKeyCountName ?>" id="<?php echo $ambiente_valoracao_grid->FormKeyCountName ?>" value="<?php echo $ambiente_valoracao_grid->KeyCount ?>">
<?php echo $ambiente_valoracao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ambiente_valoracao->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fambiente_valoracaogrid">
</div>
<?php

// Close recordset
if ($ambiente_valoracao_grid->Recordset)
	$ambiente_valoracao_grid->Recordset->Close();
?>
<?php if ($ambiente_valoracao_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($ambiente_valoracao_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($ambiente_valoracao->Export == "") { ?>
<script type="text/javascript">
fambiente_valoracaogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$ambiente_valoracao_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$ambiente_valoracao_grid->Page_Terminate();
?>
