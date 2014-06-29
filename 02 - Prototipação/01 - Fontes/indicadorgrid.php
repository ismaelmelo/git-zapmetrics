<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($indicador_grid)) $indicador_grid = new cindicador_grid();

// Page init
$indicador_grid->Page_Init();

// Page main
$indicador_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$indicador_grid->Page_Render();
?>
<?php if ($indicador->Export == "") { ?>
<script type="text/javascript">

// Page object
var indicador_grid = new ew_Page("indicador_grid");
indicador_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = indicador_grid.PageID; // For backward compatibility

// Form object
var findicadorgrid = new ew_Form("findicadorgrid");
findicadorgrid.FormKeyCountName = '<?php echo $indicador_grid->FormKeyCountName ?>';

// Validate form
findicadorgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_indicador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicador->no_indicador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ds_indicador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicador->ds_indicador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpIndicador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicador->ic_tpIndicador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicador->ic_ativo->FldCaption()) ?>");

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
findicadorgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_indicador", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ds_indicador", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_tpIndicador", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_processoCobit5", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
findicadorgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
findicadorgrid.ValidateRequired = true;
<?php } else { ?>
findicadorgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
findicadorgrid.Lists["x_nu_processoCobit5"] = {"LinkField":"x_nu_processo","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_processo","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($indicador->getCurrentMasterTable() == "" && $indicador_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $indicador_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($indicador->CurrentAction == "gridadd") {
	if ($indicador->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$indicador_grid->TotalRecs = $indicador->SelectRecordCount();
			$indicador_grid->Recordset = $indicador_grid->LoadRecordset($indicador_grid->StartRec-1, $indicador_grid->DisplayRecs);
		} else {
			if ($indicador_grid->Recordset = $indicador_grid->LoadRecordset())
				$indicador_grid->TotalRecs = $indicador_grid->Recordset->RecordCount();
		}
		$indicador_grid->StartRec = 1;
		$indicador_grid->DisplayRecs = $indicador_grid->TotalRecs;
	} else {
		$indicador->CurrentFilter = "0=1";
		$indicador_grid->StartRec = 1;
		$indicador_grid->DisplayRecs = $indicador->GridAddRowCount;
	}
	$indicador_grid->TotalRecs = $indicador_grid->DisplayRecs;
	$indicador_grid->StopRec = $indicador_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$indicador_grid->TotalRecs = $indicador->SelectRecordCount();
	} else {
		if ($indicador_grid->Recordset = $indicador_grid->LoadRecordset())
			$indicador_grid->TotalRecs = $indicador_grid->Recordset->RecordCount();
	}
	$indicador_grid->StartRec = 1;
	$indicador_grid->DisplayRecs = $indicador_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$indicador_grid->Recordset = $indicador_grid->LoadRecordset($indicador_grid->StartRec-1, $indicador_grid->DisplayRecs);
}
$indicador_grid->RenderOtherOptions();
?>
<?php $indicador_grid->ShowPageHeader(); ?>
<?php
$indicador_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="findicadorgrid" class="ewForm form-horizontal">
<div id="gmp_indicador" class="ewGridMiddlePanel">
<table id="tbl_indicadorgrid" class="ewTable ewTableSeparate">
<?php echo $indicador->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$indicador_grid->RenderListOptions();

// Render list options (header, left)
$indicador_grid->ListOptions->Render("header", "left");
?>
<?php if ($indicador->no_indicador->Visible) { // no_indicador ?>
	<?php if ($indicador->SortUrl($indicador->no_indicador) == "") { ?>
		<td><div id="elh_indicador_no_indicador" class="indicador_no_indicador"><div class="ewTableHeaderCaption"><?php echo $indicador->no_indicador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicador_no_indicador" class="indicador_no_indicador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicador->no_indicador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicador->no_indicador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicador->no_indicador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicador->ds_indicador->Visible) { // ds_indicador ?>
	<?php if ($indicador->SortUrl($indicador->ds_indicador) == "") { ?>
		<td><div id="elh_indicador_ds_indicador" class="indicador_ds_indicador"><div class="ewTableHeaderCaption"><?php echo $indicador->ds_indicador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicador_ds_indicador" class="indicador_ds_indicador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicador->ds_indicador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicador->ds_indicador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicador->ds_indicador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicador->ic_tpIndicador->Visible) { // ic_tpIndicador ?>
	<?php if ($indicador->SortUrl($indicador->ic_tpIndicador) == "") { ?>
		<td><div id="elh_indicador_ic_tpIndicador" class="indicador_ic_tpIndicador"><div class="ewTableHeaderCaption"><?php echo $indicador->ic_tpIndicador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicador_ic_tpIndicador" class="indicador_ic_tpIndicador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicador->ic_tpIndicador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicador->ic_tpIndicador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicador->ic_tpIndicador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicador->nu_processoCobit5->Visible) { // nu_processoCobit5 ?>
	<?php if ($indicador->SortUrl($indicador->nu_processoCobit5) == "") { ?>
		<td><div id="elh_indicador_nu_processoCobit5" class="indicador_nu_processoCobit5"><div class="ewTableHeaderCaption"><?php echo $indicador->nu_processoCobit5->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicador_nu_processoCobit5" class="indicador_nu_processoCobit5">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicador->nu_processoCobit5->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicador->nu_processoCobit5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicador->nu_processoCobit5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicador->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($indicador->SortUrl($indicador->ic_ativo) == "") { ?>
		<td><div id="elh_indicador_ic_ativo" class="indicador_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $indicador->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicador_ic_ativo" class="indicador_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicador->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicador->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicador->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$indicador_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$indicador_grid->StartRec = 1;
$indicador_grid->StopRec = $indicador_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($indicador_grid->FormKeyCountName) && ($indicador->CurrentAction == "gridadd" || $indicador->CurrentAction == "gridedit" || $indicador->CurrentAction == "F")) {
		$indicador_grid->KeyCount = $objForm->GetValue($indicador_grid->FormKeyCountName);
		$indicador_grid->StopRec = $indicador_grid->StartRec + $indicador_grid->KeyCount - 1;
	}
}
$indicador_grid->RecCnt = $indicador_grid->StartRec - 1;
if ($indicador_grid->Recordset && !$indicador_grid->Recordset->EOF) {
	$indicador_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $indicador_grid->StartRec > 1)
		$indicador_grid->Recordset->Move($indicador_grid->StartRec - 1);
} elseif (!$indicador->AllowAddDeleteRow && $indicador_grid->StopRec == 0) {
	$indicador_grid->StopRec = $indicador->GridAddRowCount;
}

// Initialize aggregate
$indicador->RowType = EW_ROWTYPE_AGGREGATEINIT;
$indicador->ResetAttrs();
$indicador_grid->RenderRow();
if ($indicador->CurrentAction == "gridadd")
	$indicador_grid->RowIndex = 0;
if ($indicador->CurrentAction == "gridedit")
	$indicador_grid->RowIndex = 0;
while ($indicador_grid->RecCnt < $indicador_grid->StopRec) {
	$indicador_grid->RecCnt++;
	if (intval($indicador_grid->RecCnt) >= intval($indicador_grid->StartRec)) {
		$indicador_grid->RowCnt++;
		if ($indicador->CurrentAction == "gridadd" || $indicador->CurrentAction == "gridedit" || $indicador->CurrentAction == "F") {
			$indicador_grid->RowIndex++;
			$objForm->Index = $indicador_grid->RowIndex;
			if ($objForm->HasValue($indicador_grid->FormActionName))
				$indicador_grid->RowAction = strval($objForm->GetValue($indicador_grid->FormActionName));
			elseif ($indicador->CurrentAction == "gridadd")
				$indicador_grid->RowAction = "insert";
			else
				$indicador_grid->RowAction = "";
		}

		// Set up key count
		$indicador_grid->KeyCount = $indicador_grid->RowIndex;

		// Init row class and style
		$indicador->ResetAttrs();
		$indicador->CssClass = "";
		if ($indicador->CurrentAction == "gridadd") {
			if ($indicador->CurrentMode == "copy") {
				$indicador_grid->LoadRowValues($indicador_grid->Recordset); // Load row values
				$indicador_grid->SetRecordKey($indicador_grid->RowOldKey, $indicador_grid->Recordset); // Set old record key
			} else {
				$indicador_grid->LoadDefaultValues(); // Load default values
				$indicador_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$indicador_grid->LoadRowValues($indicador_grid->Recordset); // Load row values
		}
		$indicador->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($indicador->CurrentAction == "gridadd") // Grid add
			$indicador->RowType = EW_ROWTYPE_ADD; // Render add
		if ($indicador->CurrentAction == "gridadd" && $indicador->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$indicador_grid->RestoreCurrentRowFormValues($indicador_grid->RowIndex); // Restore form values
		if ($indicador->CurrentAction == "gridedit") { // Grid edit
			if ($indicador->EventCancelled) {
				$indicador_grid->RestoreCurrentRowFormValues($indicador_grid->RowIndex); // Restore form values
			}
			if ($indicador_grid->RowAction == "insert")
				$indicador->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$indicador->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($indicador->CurrentAction == "gridedit" && ($indicador->RowType == EW_ROWTYPE_EDIT || $indicador->RowType == EW_ROWTYPE_ADD) && $indicador->EventCancelled) // Update failed
			$indicador_grid->RestoreCurrentRowFormValues($indicador_grid->RowIndex); // Restore form values
		if ($indicador->RowType == EW_ROWTYPE_EDIT) // Edit row
			$indicador_grid->EditRowCnt++;
		if ($indicador->CurrentAction == "F") // Confirm row
			$indicador_grid->RestoreCurrentRowFormValues($indicador_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$indicador->RowAttrs = array_merge($indicador->RowAttrs, array('data-rowindex'=>$indicador_grid->RowCnt, 'id'=>'r' . $indicador_grid->RowCnt . '_indicador', 'data-rowtype'=>$indicador->RowType));

		// Render row
		$indicador_grid->RenderRow();

		// Render list options
		$indicador_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($indicador_grid->RowAction <> "delete" && $indicador_grid->RowAction <> "insertdelete" && !($indicador_grid->RowAction == "insert" && $indicador->CurrentAction == "F" && $indicador_grid->EmptyRow())) {
?>
	<tr<?php echo $indicador->RowAttributes() ?>>
<?php

// Render list options (body, left)
$indicador_grid->ListOptions->Render("body", "left", $indicador_grid->RowCnt);
?>
	<?php if ($indicador->no_indicador->Visible) { // no_indicador ?>
		<td<?php echo $indicador->no_indicador->CellAttributes() ?>>
<?php if ($indicador->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_no_indicador" class="control-group indicador_no_indicador">
<input type="text" data-field="x_no_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" size="30" maxlength="100" placeholder="<?php echo $indicador->no_indicador->PlaceHolder ?>" value="<?php echo $indicador->no_indicador->EditValue ?>"<?php echo $indicador->no_indicador->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_indicador" name="o<?php echo $indicador_grid->RowIndex ?>_no_indicador" id="o<?php echo $indicador_grid->RowIndex ?>_no_indicador" value="<?php echo ew_HtmlEncode($indicador->no_indicador->OldValue) ?>">
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_no_indicador" class="control-group indicador_no_indicador">
<input type="text" data-field="x_no_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" size="30" maxlength="100" placeholder="<?php echo $indicador->no_indicador->PlaceHolder ?>" value="<?php echo $indicador->no_indicador->EditValue ?>"<?php echo $indicador->no_indicador->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicador->no_indicador->ViewAttributes() ?>>
<?php echo $indicador->no_indicador->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" value="<?php echo ew_HtmlEncode($indicador->no_indicador->FormValue) ?>">
<input type="hidden" data-field="x_no_indicador" name="o<?php echo $indicador_grid->RowIndex ?>_no_indicador" id="o<?php echo $indicador_grid->RowIndex ?>_no_indicador" value="<?php echo ew_HtmlEncode($indicador->no_indicador->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicador_grid->PageObjName . "_row_" . $indicador_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_nu_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicador->nu_indicador->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_indicador" name="o<?php echo $indicador_grid->RowIndex ?>_nu_indicador" id="o<?php echo $indicador_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicador->nu_indicador->OldValue) ?>">
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_EDIT || $indicador->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_nu_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicador->nu_indicador->CurrentValue) ?>">
<?php } ?>
	<?php if ($indicador->ds_indicador->Visible) { // ds_indicador ?>
		<td<?php echo $indicador->ds_indicador->CellAttributes() ?>>
<?php if ($indicador->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_ds_indicador" class="control-group indicador_ds_indicador">
<textarea data-field="x_ds_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" cols="35" rows="4" placeholder="<?php echo $indicador->ds_indicador->PlaceHolder ?>"<?php echo $indicador->ds_indicador->EditAttributes() ?>><?php echo $indicador->ds_indicador->EditValue ?></textarea>
</span>
<input type="hidden" data-field="x_ds_indicador" name="o<?php echo $indicador_grid->RowIndex ?>_ds_indicador" id="o<?php echo $indicador_grid->RowIndex ?>_ds_indicador" value="<?php echo ew_HtmlEncode($indicador->ds_indicador->OldValue) ?>">
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_ds_indicador" class="control-group indicador_ds_indicador">
<textarea data-field="x_ds_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" cols="35" rows="4" placeholder="<?php echo $indicador->ds_indicador->PlaceHolder ?>"<?php echo $indicador->ds_indicador->EditAttributes() ?>><?php echo $indicador->ds_indicador->EditValue ?></textarea>
</span>
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicador->ds_indicador->ViewAttributes() ?>>
<?php echo $indicador->ds_indicador->ListViewValue() ?></span>
<input type="hidden" data-field="x_ds_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" value="<?php echo ew_HtmlEncode($indicador->ds_indicador->FormValue) ?>">
<input type="hidden" data-field="x_ds_indicador" name="o<?php echo $indicador_grid->RowIndex ?>_ds_indicador" id="o<?php echo $indicador_grid->RowIndex ?>_ds_indicador" value="<?php echo ew_HtmlEncode($indicador->ds_indicador->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicador_grid->PageObjName . "_row_" . $indicador_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicador->ic_tpIndicador->Visible) { // ic_tpIndicador ?>
		<td<?php echo $indicador->ic_tpIndicador->CellAttributes() ?>>
<?php if ($indicador->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_ic_tpIndicador" class="control-group indicador_ic_tpIndicador">
<select data-field="x_ic_tpIndicador" id="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" name="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador"<?php echo $indicador->ic_tpIndicador->EditAttributes() ?>>
<?php
if (is_array($indicador->ic_tpIndicador->EditValue)) {
	$arwrk = $indicador->ic_tpIndicador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->ic_tpIndicador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicador->ic_tpIndicador->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_ic_tpIndicador" name="o<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" id="o<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" value="<?php echo ew_HtmlEncode($indicador->ic_tpIndicador->OldValue) ?>">
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_ic_tpIndicador" class="control-group indicador_ic_tpIndicador">
<select data-field="x_ic_tpIndicador" id="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" name="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador"<?php echo $indicador->ic_tpIndicador->EditAttributes() ?>>
<?php
if (is_array($indicador->ic_tpIndicador->EditValue)) {
	$arwrk = $indicador->ic_tpIndicador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->ic_tpIndicador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicador->ic_tpIndicador->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicador->ic_tpIndicador->ViewAttributes() ?>>
<?php echo $indicador->ic_tpIndicador->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_tpIndicador" name="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" id="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" value="<?php echo ew_HtmlEncode($indicador->ic_tpIndicador->FormValue) ?>">
<input type="hidden" data-field="x_ic_tpIndicador" name="o<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" id="o<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" value="<?php echo ew_HtmlEncode($indicador->ic_tpIndicador->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicador_grid->PageObjName . "_row_" . $indicador_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicador->nu_processoCobit5->Visible) { // nu_processoCobit5 ?>
		<td<?php echo $indicador->nu_processoCobit5->CellAttributes() ?>>
<?php if ($indicador->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_nu_processoCobit5" class="control-group indicador_nu_processoCobit5">
<select data-field="x_nu_processoCobit5" id="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" name="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5"<?php echo $indicador->nu_processoCobit5->EditAttributes() ?>>
<?php
if (is_array($indicador->nu_processoCobit5->EditValue)) {
	$arwrk = $indicador->nu_processoCobit5->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->nu_processoCobit5->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$indicador->nu_processoCobit5) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicador->nu_processoCobit5->OldValue = "";
?>
</select>
<script type="text/javascript">
findicadorgrid.Lists["x_nu_processoCobit5"].Options = <?php echo (is_array($indicador->nu_processoCobit5->EditValue)) ? ew_ArrayToJson($indicador->nu_processoCobit5->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_processoCobit5" name="o<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" id="o<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" value="<?php echo ew_HtmlEncode($indicador->nu_processoCobit5->OldValue) ?>">
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_nu_processoCobit5" class="control-group indicador_nu_processoCobit5">
<select data-field="x_nu_processoCobit5" id="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" name="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5"<?php echo $indicador->nu_processoCobit5->EditAttributes() ?>>
<?php
if (is_array($indicador->nu_processoCobit5->EditValue)) {
	$arwrk = $indicador->nu_processoCobit5->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->nu_processoCobit5->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$indicador->nu_processoCobit5) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicador->nu_processoCobit5->OldValue = "";
?>
</select>
<script type="text/javascript">
findicadorgrid.Lists["x_nu_processoCobit5"].Options = <?php echo (is_array($indicador->nu_processoCobit5->EditValue)) ? ew_ArrayToJson($indicador->nu_processoCobit5->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicador->nu_processoCobit5->ViewAttributes() ?>>
<?php echo $indicador->nu_processoCobit5->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_processoCobit5" name="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" id="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" value="<?php echo ew_HtmlEncode($indicador->nu_processoCobit5->FormValue) ?>">
<input type="hidden" data-field="x_nu_processoCobit5" name="o<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" id="o<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" value="<?php echo ew_HtmlEncode($indicador->nu_processoCobit5->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicador_grid->PageObjName . "_row_" . $indicador_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicador->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $indicador->ic_ativo->CellAttributes() ?>>
<?php if ($indicador->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_ic_ativo" class="control-group indicador_ic_ativo">
<div id="tp_x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $indicador->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $indicador->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $indicador->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $indicador->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="o<?php echo $indicador_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($indicador->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $indicador_grid->RowCnt ?>_indicador_ic_ativo" class="control-group indicador_ic_ativo">
<div id="tp_x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $indicador->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $indicador->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $indicador->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $indicador->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($indicador->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicador->ic_ativo->ViewAttributes() ?>>
<?php echo $indicador->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($indicador->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="o<?php echo $indicador_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($indicador->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicador_grid->PageObjName . "_row_" . $indicador_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$indicador_grid->ListOptions->Render("body", "right", $indicador_grid->RowCnt);
?>
	</tr>
<?php if ($indicador->RowType == EW_ROWTYPE_ADD || $indicador->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
findicadorgrid.UpdateOpts(<?php echo $indicador_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($indicador->CurrentAction <> "gridadd" || $indicador->CurrentMode == "copy")
		if (!$indicador_grid->Recordset->EOF) $indicador_grid->Recordset->MoveNext();
}
?>
<?php
	if ($indicador->CurrentMode == "add" || $indicador->CurrentMode == "copy" || $indicador->CurrentMode == "edit") {
		$indicador_grid->RowIndex = '$rowindex$';
		$indicador_grid->LoadDefaultValues();

		// Set row properties
		$indicador->ResetAttrs();
		$indicador->RowAttrs = array_merge($indicador->RowAttrs, array('data-rowindex'=>$indicador_grid->RowIndex, 'id'=>'r0_indicador', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($indicador->RowAttrs["class"], "ewTemplate");
		$indicador->RowType = EW_ROWTYPE_ADD;

		// Render row
		$indicador_grid->RenderRow();

		// Render list options
		$indicador_grid->RenderListOptions();
		$indicador_grid->StartRowCnt = 0;
?>
	<tr<?php echo $indicador->RowAttributes() ?>>
<?php

// Render list options (body, left)
$indicador_grid->ListOptions->Render("body", "left", $indicador_grid->RowIndex);
?>
	<?php if ($indicador->no_indicador->Visible) { // no_indicador ?>
		<td>
<?php if ($indicador->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" size="30" maxlength="100" placeholder="<?php echo $indicador->no_indicador->PlaceHolder ?>" value="<?php echo $indicador->no_indicador->EditValue ?>"<?php echo $indicador->no_indicador->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $indicador->no_indicador->ViewAttributes() ?>>
<?php echo $indicador->no_indicador->ViewValue ?></span>
<input type="hidden" data-field="x_no_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_no_indicador" value="<?php echo ew_HtmlEncode($indicador->no_indicador->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_indicador" name="o<?php echo $indicador_grid->RowIndex ?>_no_indicador" id="o<?php echo $indicador_grid->RowIndex ?>_no_indicador" value="<?php echo ew_HtmlEncode($indicador->no_indicador->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($indicador->ds_indicador->Visible) { // ds_indicador ?>
		<td>
<?php if ($indicador->CurrentAction <> "F") { ?>
<textarea data-field="x_ds_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" cols="35" rows="4" placeholder="<?php echo $indicador->ds_indicador->PlaceHolder ?>"<?php echo $indicador->ds_indicador->EditAttributes() ?>><?php echo $indicador->ds_indicador->EditValue ?></textarea>
<?php } else { ?>
<span<?php echo $indicador->ds_indicador->ViewAttributes() ?>>
<?php echo $indicador->ds_indicador->ViewValue ?></span>
<input type="hidden" data-field="x_ds_indicador" name="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" id="x<?php echo $indicador_grid->RowIndex ?>_ds_indicador" value="<?php echo ew_HtmlEncode($indicador->ds_indicador->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ds_indicador" name="o<?php echo $indicador_grid->RowIndex ?>_ds_indicador" id="o<?php echo $indicador_grid->RowIndex ?>_ds_indicador" value="<?php echo ew_HtmlEncode($indicador->ds_indicador->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($indicador->ic_tpIndicador->Visible) { // ic_tpIndicador ?>
		<td>
<?php if ($indicador->CurrentAction <> "F") { ?>
<select data-field="x_ic_tpIndicador" id="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" name="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador"<?php echo $indicador->ic_tpIndicador->EditAttributes() ?>>
<?php
if (is_array($indicador->ic_tpIndicador->EditValue)) {
	$arwrk = $indicador->ic_tpIndicador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->ic_tpIndicador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicador->ic_tpIndicador->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $indicador->ic_tpIndicador->ViewAttributes() ?>>
<?php echo $indicador->ic_tpIndicador->ViewValue ?></span>
<input type="hidden" data-field="x_ic_tpIndicador" name="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" id="x<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" value="<?php echo ew_HtmlEncode($indicador->ic_tpIndicador->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_tpIndicador" name="o<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" id="o<?php echo $indicador_grid->RowIndex ?>_ic_tpIndicador" value="<?php echo ew_HtmlEncode($indicador->ic_tpIndicador->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($indicador->nu_processoCobit5->Visible) { // nu_processoCobit5 ?>
		<td>
<?php if ($indicador->CurrentAction <> "F") { ?>
<select data-field="x_nu_processoCobit5" id="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" name="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5"<?php echo $indicador->nu_processoCobit5->EditAttributes() ?>>
<?php
if (is_array($indicador->nu_processoCobit5->EditValue)) {
	$arwrk = $indicador->nu_processoCobit5->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->nu_processoCobit5->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$indicador->nu_processoCobit5) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicador->nu_processoCobit5->OldValue = "";
?>
</select>
<script type="text/javascript">
findicadorgrid.Lists["x_nu_processoCobit5"].Options = <?php echo (is_array($indicador->nu_processoCobit5->EditValue)) ? ew_ArrayToJson($indicador->nu_processoCobit5->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $indicador->nu_processoCobit5->ViewAttributes() ?>>
<?php echo $indicador->nu_processoCobit5->ViewValue ?></span>
<input type="hidden" data-field="x_nu_processoCobit5" name="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" id="x<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" value="<?php echo ew_HtmlEncode($indicador->nu_processoCobit5->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_processoCobit5" name="o<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" id="o<?php echo $indicador_grid->RowIndex ?>_nu_processoCobit5" value="<?php echo ew_HtmlEncode($indicador->nu_processoCobit5->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($indicador->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($indicador->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $indicador->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $indicador->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicador->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $indicador->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $indicador->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $indicador->ic_ativo->ViewAttributes() ?>>
<?php echo $indicador->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="x<?php echo $indicador_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($indicador->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $indicador_grid->RowIndex ?>_ic_ativo" id="o<?php echo $indicador_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($indicador->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$indicador_grid->ListOptions->Render("body", "right", $indicador_grid->RowCnt);
?>
<script type="text/javascript">
findicadorgrid.UpdateOpts(<?php echo $indicador_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($indicador->CurrentMode == "add" || $indicador->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $indicador_grid->FormKeyCountName ?>" id="<?php echo $indicador_grid->FormKeyCountName ?>" value="<?php echo $indicador_grid->KeyCount ?>">
<?php echo $indicador_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($indicador->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $indicador_grid->FormKeyCountName ?>" id="<?php echo $indicador_grid->FormKeyCountName ?>" value="<?php echo $indicador_grid->KeyCount ?>">
<?php echo $indicador_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($indicador->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="findicadorgrid">
</div>
<?php

// Close recordset
if ($indicador_grid->Recordset)
	$indicador_grid->Recordset->Close();
?>
<?php if ($indicador_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($indicador_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($indicador->Export == "") { ?>
<script type="text/javascript">
findicadorgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$indicador_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$indicador_grid->Page_Terminate();
?>
