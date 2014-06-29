<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($fatura_os_grid)) $fatura_os_grid = new cfatura_os_grid();

// Page init
$fatura_os_grid->Page_Init();

// Page main
$fatura_os_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$fatura_os_grid->Page_Render();
?>
<?php if ($fatura_os->Export == "") { ?>
<script type="text/javascript">

// Page object
var fatura_os_grid = new ew_Page("fatura_os_grid");
fatura_os_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = fatura_os_grid.PageID; // For backward compatibility

// Form object
var ffatura_osgrid = new ew_Form("ffatura_osgrid");
ffatura_osgrid.FormKeyCountName = '<?php echo $fatura_os_grid->FormKeyCountName ?>';

// Validate form
ffatura_osgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_os");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura_os->nu_os->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_pagIntegralOs");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura_os->ic_pagIntegralOs->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_pagoOsFatura");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($fatura_os->vr_pagoOsFatura->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_pagoOsFatura");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($fatura_os->vr_pagoOsFatura->FldErrMsg()) ?>");

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
ffatura_osgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_os", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_pagIntegralOs", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_pagoOsFatura", false)) return false;
	return true;
}

// Form_CustomValidate event
ffatura_osgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffatura_osgrid.ValidateRequired = true;
<?php } else { ?>
ffatura_osgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ffatura_osgrid.Lists["x_nu_os"] = {"LinkField":"x_nu_os","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_titulo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($fatura_os->getCurrentMasterTable() == "" && $fatura_os_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $fatura_os_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($fatura_os->CurrentAction == "gridadd") {
	if ($fatura_os->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$fatura_os_grid->TotalRecs = $fatura_os->SelectRecordCount();
			$fatura_os_grid->Recordset = $fatura_os_grid->LoadRecordset($fatura_os_grid->StartRec-1, $fatura_os_grid->DisplayRecs);
		} else {
			if ($fatura_os_grid->Recordset = $fatura_os_grid->LoadRecordset())
				$fatura_os_grid->TotalRecs = $fatura_os_grid->Recordset->RecordCount();
		}
		$fatura_os_grid->StartRec = 1;
		$fatura_os_grid->DisplayRecs = $fatura_os_grid->TotalRecs;
	} else {
		$fatura_os->CurrentFilter = "0=1";
		$fatura_os_grid->StartRec = 1;
		$fatura_os_grid->DisplayRecs = $fatura_os->GridAddRowCount;
	}
	$fatura_os_grid->TotalRecs = $fatura_os_grid->DisplayRecs;
	$fatura_os_grid->StopRec = $fatura_os_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$fatura_os_grid->TotalRecs = $fatura_os->SelectRecordCount();
	} else {
		if ($fatura_os_grid->Recordset = $fatura_os_grid->LoadRecordset())
			$fatura_os_grid->TotalRecs = $fatura_os_grid->Recordset->RecordCount();
	}
	$fatura_os_grid->StartRec = 1;
	$fatura_os_grid->DisplayRecs = $fatura_os_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$fatura_os_grid->Recordset = $fatura_os_grid->LoadRecordset($fatura_os_grid->StartRec-1, $fatura_os_grid->DisplayRecs);
}
$fatura_os_grid->RenderOtherOptions();
?>
<?php $fatura_os_grid->ShowPageHeader(); ?>
<?php
$fatura_os_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="ffatura_osgrid" class="ewForm form-horizontal">
<div id="gmp_fatura_os" class="ewGridMiddlePanel">
<table id="tbl_fatura_osgrid" class="ewTable ewTableSeparate">
<?php echo $fatura_os->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$fatura_os_grid->RenderListOptions();

// Render list options (header, left)
$fatura_os_grid->ListOptions->Render("header", "left");
?>
<?php if ($fatura_os->nu_os->Visible) { // nu_os ?>
	<?php if ($fatura_os->SortUrl($fatura_os->nu_os) == "") { ?>
		<td><div id="elh_fatura_os_nu_os" class="fatura_os_nu_os"><div class="ewTableHeaderCaption"><?php echo $fatura_os->nu_os->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_fatura_os_nu_os" class="fatura_os_nu_os">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fatura_os->nu_os->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($fatura_os->nu_os->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fatura_os->nu_os->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($fatura_os->ic_pagIntegralOs->Visible) { // ic_pagIntegralOs ?>
	<?php if ($fatura_os->SortUrl($fatura_os->ic_pagIntegralOs) == "") { ?>
		<td><div id="elh_fatura_os_ic_pagIntegralOs" class="fatura_os_ic_pagIntegralOs"><div class="ewTableHeaderCaption"><?php echo $fatura_os->ic_pagIntegralOs->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_fatura_os_ic_pagIntegralOs" class="fatura_os_ic_pagIntegralOs">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fatura_os->ic_pagIntegralOs->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($fatura_os->ic_pagIntegralOs->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fatura_os->ic_pagIntegralOs->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($fatura_os->vr_pagoOsFatura->Visible) { // vr_pagoOsFatura ?>
	<?php if ($fatura_os->SortUrl($fatura_os->vr_pagoOsFatura) == "") { ?>
		<td><div id="elh_fatura_os_vr_pagoOsFatura" class="fatura_os_vr_pagoOsFatura"><div class="ewTableHeaderCaption"><?php echo $fatura_os->vr_pagoOsFatura->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_fatura_os_vr_pagoOsFatura" class="fatura_os_vr_pagoOsFatura">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $fatura_os->vr_pagoOsFatura->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($fatura_os->vr_pagoOsFatura->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($fatura_os->vr_pagoOsFatura->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$fatura_os_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$fatura_os_grid->StartRec = 1;
$fatura_os_grid->StopRec = $fatura_os_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($fatura_os_grid->FormKeyCountName) && ($fatura_os->CurrentAction == "gridadd" || $fatura_os->CurrentAction == "gridedit" || $fatura_os->CurrentAction == "F")) {
		$fatura_os_grid->KeyCount = $objForm->GetValue($fatura_os_grid->FormKeyCountName);
		$fatura_os_grid->StopRec = $fatura_os_grid->StartRec + $fatura_os_grid->KeyCount - 1;
	}
}
$fatura_os_grid->RecCnt = $fatura_os_grid->StartRec - 1;
if ($fatura_os_grid->Recordset && !$fatura_os_grid->Recordset->EOF) {
	$fatura_os_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $fatura_os_grid->StartRec > 1)
		$fatura_os_grid->Recordset->Move($fatura_os_grid->StartRec - 1);
} elseif (!$fatura_os->AllowAddDeleteRow && $fatura_os_grid->StopRec == 0) {
	$fatura_os_grid->StopRec = $fatura_os->GridAddRowCount;
}

// Initialize aggregate
$fatura_os->RowType = EW_ROWTYPE_AGGREGATEINIT;
$fatura_os->ResetAttrs();
$fatura_os_grid->RenderRow();
if ($fatura_os->CurrentAction == "gridadd")
	$fatura_os_grid->RowIndex = 0;
if ($fatura_os->CurrentAction == "gridedit")
	$fatura_os_grid->RowIndex = 0;
while ($fatura_os_grid->RecCnt < $fatura_os_grid->StopRec) {
	$fatura_os_grid->RecCnt++;
	if (intval($fatura_os_grid->RecCnt) >= intval($fatura_os_grid->StartRec)) {
		$fatura_os_grid->RowCnt++;
		if ($fatura_os->CurrentAction == "gridadd" || $fatura_os->CurrentAction == "gridedit" || $fatura_os->CurrentAction == "F") {
			$fatura_os_grid->RowIndex++;
			$objForm->Index = $fatura_os_grid->RowIndex;
			if ($objForm->HasValue($fatura_os_grid->FormActionName))
				$fatura_os_grid->RowAction = strval($objForm->GetValue($fatura_os_grid->FormActionName));
			elseif ($fatura_os->CurrentAction == "gridadd")
				$fatura_os_grid->RowAction = "insert";
			else
				$fatura_os_grid->RowAction = "";
		}

		// Set up key count
		$fatura_os_grid->KeyCount = $fatura_os_grid->RowIndex;

		// Init row class and style
		$fatura_os->ResetAttrs();
		$fatura_os->CssClass = "";
		if ($fatura_os->CurrentAction == "gridadd") {
			if ($fatura_os->CurrentMode == "copy") {
				$fatura_os_grid->LoadRowValues($fatura_os_grid->Recordset); // Load row values
				$fatura_os_grid->SetRecordKey($fatura_os_grid->RowOldKey, $fatura_os_grid->Recordset); // Set old record key
			} else {
				$fatura_os_grid->LoadDefaultValues(); // Load default values
				$fatura_os_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$fatura_os_grid->LoadRowValues($fatura_os_grid->Recordset); // Load row values
		}
		$fatura_os->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($fatura_os->CurrentAction == "gridadd") // Grid add
			$fatura_os->RowType = EW_ROWTYPE_ADD; // Render add
		if ($fatura_os->CurrentAction == "gridadd" && $fatura_os->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$fatura_os_grid->RestoreCurrentRowFormValues($fatura_os_grid->RowIndex); // Restore form values
		if ($fatura_os->CurrentAction == "gridedit") { // Grid edit
			if ($fatura_os->EventCancelled) {
				$fatura_os_grid->RestoreCurrentRowFormValues($fatura_os_grid->RowIndex); // Restore form values
			}
			if ($fatura_os_grid->RowAction == "insert")
				$fatura_os->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$fatura_os->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($fatura_os->CurrentAction == "gridedit" && ($fatura_os->RowType == EW_ROWTYPE_EDIT || $fatura_os->RowType == EW_ROWTYPE_ADD) && $fatura_os->EventCancelled) // Update failed
			$fatura_os_grid->RestoreCurrentRowFormValues($fatura_os_grid->RowIndex); // Restore form values
		if ($fatura_os->RowType == EW_ROWTYPE_EDIT) // Edit row
			$fatura_os_grid->EditRowCnt++;
		if ($fatura_os->CurrentAction == "F") // Confirm row
			$fatura_os_grid->RestoreCurrentRowFormValues($fatura_os_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$fatura_os->RowAttrs = array_merge($fatura_os->RowAttrs, array('data-rowindex'=>$fatura_os_grid->RowCnt, 'id'=>'r' . $fatura_os_grid->RowCnt . '_fatura_os', 'data-rowtype'=>$fatura_os->RowType));

		// Render row
		$fatura_os_grid->RenderRow();

		// Render list options
		$fatura_os_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($fatura_os_grid->RowAction <> "delete" && $fatura_os_grid->RowAction <> "insertdelete" && !($fatura_os_grid->RowAction == "insert" && $fatura_os->CurrentAction == "F" && $fatura_os_grid->EmptyRow())) {
?>
	<tr<?php echo $fatura_os->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fatura_os_grid->ListOptions->Render("body", "left", $fatura_os_grid->RowCnt);
?>
	<?php if ($fatura_os->nu_os->Visible) { // nu_os ?>
		<td<?php echo $fatura_os->nu_os->CellAttributes() ?>>
<?php if ($fatura_os->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $fatura_os_grid->RowCnt ?>_fatura_os_nu_os" class="control-group fatura_os_nu_os">
<select data-field="x_nu_os" id="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os" name="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os"<?php echo $fatura_os->nu_os->EditAttributes() ?>>
<?php
if (is_array($fatura_os->nu_os->EditValue)) {
	$arwrk = $fatura_os->nu_os->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura_os->nu_os->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $fatura_os->nu_os->OldValue = "";
?>
</select>
<script type="text/javascript">
ffatura_osgrid.Lists["x_nu_os"].Options = <?php echo (is_array($fatura_os->nu_os->EditValue)) ? ew_ArrayToJson($fatura_os->nu_os->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_os" name="o<?php echo $fatura_os_grid->RowIndex ?>_nu_os" id="o<?php echo $fatura_os_grid->RowIndex ?>_nu_os" value="<?php echo ew_HtmlEncode($fatura_os->nu_os->OldValue) ?>">
<?php } ?>
<?php if ($fatura_os->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $fatura_os_grid->RowCnt ?>_fatura_os_nu_os" class="control-group fatura_os_nu_os">
<span<?php echo $fatura_os->nu_os->ViewAttributes() ?>>
<?php echo $fatura_os->nu_os->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_os" name="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os" id="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os" value="<?php echo ew_HtmlEncode($fatura_os->nu_os->CurrentValue) ?>">
<?php } ?>
<?php if ($fatura_os->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fatura_os->nu_os->ViewAttributes() ?>>
<?php echo $fatura_os->nu_os->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_os" name="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os" id="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os" value="<?php echo ew_HtmlEncode($fatura_os->nu_os->FormValue) ?>">
<input type="hidden" data-field="x_nu_os" name="o<?php echo $fatura_os_grid->RowIndex ?>_nu_os" id="o<?php echo $fatura_os_grid->RowIndex ?>_nu_os" value="<?php echo ew_HtmlEncode($fatura_os->nu_os->OldValue) ?>">
<?php } ?>
<a id="<?php echo $fatura_os_grid->PageObjName . "_row_" . $fatura_os_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($fatura_os->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_fatura" name="x<?php echo $fatura_os_grid->RowIndex ?>_nu_fatura" id="x<?php echo $fatura_os_grid->RowIndex ?>_nu_fatura" value="<?php echo ew_HtmlEncode($fatura_os->nu_fatura->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_fatura" name="o<?php echo $fatura_os_grid->RowIndex ?>_nu_fatura" id="o<?php echo $fatura_os_grid->RowIndex ?>_nu_fatura" value="<?php echo ew_HtmlEncode($fatura_os->nu_fatura->OldValue) ?>">
<?php } ?>
<?php if ($fatura_os->RowType == EW_ROWTYPE_EDIT || $fatura_os->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_fatura" name="x<?php echo $fatura_os_grid->RowIndex ?>_nu_fatura" id="x<?php echo $fatura_os_grid->RowIndex ?>_nu_fatura" value="<?php echo ew_HtmlEncode($fatura_os->nu_fatura->CurrentValue) ?>">
<?php } ?>
	<?php if ($fatura_os->ic_pagIntegralOs->Visible) { // ic_pagIntegralOs ?>
		<td<?php echo $fatura_os->ic_pagIntegralOs->CellAttributes() ?>>
<?php if ($fatura_os->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $fatura_os_grid->RowCnt ?>_fatura_os_ic_pagIntegralOs" class="control-group fatura_os_ic_pagIntegralOs">
<div id="tp_x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" value="{value}"<?php echo $fatura_os->ic_pagIntegralOs->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $fatura_os->ic_pagIntegralOs->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura_os->ic_pagIntegralOs->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_pagIntegralOs" name="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $fatura_os->ic_pagIntegralOs->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $fatura_os->ic_pagIntegralOs->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_pagIntegralOs" name="o<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="o<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" value="<?php echo ew_HtmlEncode($fatura_os->ic_pagIntegralOs->OldValue) ?>">
<?php } ?>
<?php if ($fatura_os->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $fatura_os_grid->RowCnt ?>_fatura_os_ic_pagIntegralOs" class="control-group fatura_os_ic_pagIntegralOs">
<div id="tp_x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" value="{value}"<?php echo $fatura_os->ic_pagIntegralOs->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $fatura_os->ic_pagIntegralOs->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura_os->ic_pagIntegralOs->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_pagIntegralOs" name="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $fatura_os->ic_pagIntegralOs->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $fatura_os->ic_pagIntegralOs->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($fatura_os->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fatura_os->ic_pagIntegralOs->ViewAttributes() ?>>
<?php echo $fatura_os->ic_pagIntegralOs->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_pagIntegralOs" name="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" value="<?php echo ew_HtmlEncode($fatura_os->ic_pagIntegralOs->FormValue) ?>">
<input type="hidden" data-field="x_ic_pagIntegralOs" name="o<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="o<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" value="<?php echo ew_HtmlEncode($fatura_os->ic_pagIntegralOs->OldValue) ?>">
<?php } ?>
<a id="<?php echo $fatura_os_grid->PageObjName . "_row_" . $fatura_os_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($fatura_os->vr_pagoOsFatura->Visible) { // vr_pagoOsFatura ?>
		<td<?php echo $fatura_os->vr_pagoOsFatura->CellAttributes() ?>>
<?php if ($fatura_os->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $fatura_os_grid->RowCnt ?>_fatura_os_vr_pagoOsFatura" class="control-group fatura_os_vr_pagoOsFatura">
<input type="text" data-field="x_vr_pagoOsFatura" name="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" id="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" size="30" placeholder="<?php echo $fatura_os->vr_pagoOsFatura->PlaceHolder ?>" value="<?php echo $fatura_os->vr_pagoOsFatura->EditValue ?>"<?php echo $fatura_os->vr_pagoOsFatura->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_pagoOsFatura" name="o<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" id="o<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" value="<?php echo ew_HtmlEncode($fatura_os->vr_pagoOsFatura->OldValue) ?>">
<?php } ?>
<?php if ($fatura_os->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $fatura_os_grid->RowCnt ?>_fatura_os_vr_pagoOsFatura" class="control-group fatura_os_vr_pagoOsFatura">
<input type="text" data-field="x_vr_pagoOsFatura" name="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" id="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" size="30" placeholder="<?php echo $fatura_os->vr_pagoOsFatura->PlaceHolder ?>" value="<?php echo $fatura_os->vr_pagoOsFatura->EditValue ?>"<?php echo $fatura_os->vr_pagoOsFatura->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($fatura_os->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $fatura_os->vr_pagoOsFatura->ViewAttributes() ?>>
<?php echo $fatura_os->vr_pagoOsFatura->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_pagoOsFatura" name="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" id="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" value="<?php echo ew_HtmlEncode($fatura_os->vr_pagoOsFatura->FormValue) ?>">
<input type="hidden" data-field="x_vr_pagoOsFatura" name="o<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" id="o<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" value="<?php echo ew_HtmlEncode($fatura_os->vr_pagoOsFatura->OldValue) ?>">
<?php } ?>
<a id="<?php echo $fatura_os_grid->PageObjName . "_row_" . $fatura_os_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$fatura_os_grid->ListOptions->Render("body", "right", $fatura_os_grid->RowCnt);
?>
	</tr>
<?php if ($fatura_os->RowType == EW_ROWTYPE_ADD || $fatura_os->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ffatura_osgrid.UpdateOpts(<?php echo $fatura_os_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($fatura_os->CurrentAction <> "gridadd" || $fatura_os->CurrentMode == "copy")
		if (!$fatura_os_grid->Recordset->EOF) $fatura_os_grid->Recordset->MoveNext();
}
?>
<?php
	if ($fatura_os->CurrentMode == "add" || $fatura_os->CurrentMode == "copy" || $fatura_os->CurrentMode == "edit") {
		$fatura_os_grid->RowIndex = '$rowindex$';
		$fatura_os_grid->LoadDefaultValues();

		// Set row properties
		$fatura_os->ResetAttrs();
		$fatura_os->RowAttrs = array_merge($fatura_os->RowAttrs, array('data-rowindex'=>$fatura_os_grid->RowIndex, 'id'=>'r0_fatura_os', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($fatura_os->RowAttrs["class"], "ewTemplate");
		$fatura_os->RowType = EW_ROWTYPE_ADD;

		// Render row
		$fatura_os_grid->RenderRow();

		// Render list options
		$fatura_os_grid->RenderListOptions();
		$fatura_os_grid->StartRowCnt = 0;
?>
	<tr<?php echo $fatura_os->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fatura_os_grid->ListOptions->Render("body", "left", $fatura_os_grid->RowIndex);
?>
	<?php if ($fatura_os->nu_os->Visible) { // nu_os ?>
		<td>
<?php if ($fatura_os->CurrentAction <> "F") { ?>
<select data-field="x_nu_os" id="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os" name="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os"<?php echo $fatura_os->nu_os->EditAttributes() ?>>
<?php
if (is_array($fatura_os->nu_os->EditValue)) {
	$arwrk = $fatura_os->nu_os->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura_os->nu_os->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $fatura_os->nu_os->OldValue = "";
?>
</select>
<script type="text/javascript">
ffatura_osgrid.Lists["x_nu_os"].Options = <?php echo (is_array($fatura_os->nu_os->EditValue)) ? ew_ArrayToJson($fatura_os->nu_os->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $fatura_os->nu_os->ViewAttributes() ?>>
<?php echo $fatura_os->nu_os->ViewValue ?></span>
<input type="hidden" data-field="x_nu_os" name="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os" id="x<?php echo $fatura_os_grid->RowIndex ?>_nu_os" value="<?php echo ew_HtmlEncode($fatura_os->nu_os->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_os" name="o<?php echo $fatura_os_grid->RowIndex ?>_nu_os" id="o<?php echo $fatura_os_grid->RowIndex ?>_nu_os" value="<?php echo ew_HtmlEncode($fatura_os->nu_os->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($fatura_os->ic_pagIntegralOs->Visible) { // ic_pagIntegralOs ?>
		<td>
<?php if ($fatura_os->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" value="{value}"<?php echo $fatura_os->ic_pagIntegralOs->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $fatura_os->ic_pagIntegralOs->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($fatura_os->ic_pagIntegralOs->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_pagIntegralOs" name="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $fatura_os->ic_pagIntegralOs->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $fatura_os->ic_pagIntegralOs->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $fatura_os->ic_pagIntegralOs->ViewAttributes() ?>>
<?php echo $fatura_os->ic_pagIntegralOs->ViewValue ?></span>
<input type="hidden" data-field="x_ic_pagIntegralOs" name="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="x<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" value="<?php echo ew_HtmlEncode($fatura_os->ic_pagIntegralOs->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_pagIntegralOs" name="o<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" id="o<?php echo $fatura_os_grid->RowIndex ?>_ic_pagIntegralOs" value="<?php echo ew_HtmlEncode($fatura_os->ic_pagIntegralOs->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($fatura_os->vr_pagoOsFatura->Visible) { // vr_pagoOsFatura ?>
		<td>
<?php if ($fatura_os->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_pagoOsFatura" name="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" id="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" size="30" placeholder="<?php echo $fatura_os->vr_pagoOsFatura->PlaceHolder ?>" value="<?php echo $fatura_os->vr_pagoOsFatura->EditValue ?>"<?php echo $fatura_os->vr_pagoOsFatura->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $fatura_os->vr_pagoOsFatura->ViewAttributes() ?>>
<?php echo $fatura_os->vr_pagoOsFatura->ViewValue ?></span>
<input type="hidden" data-field="x_vr_pagoOsFatura" name="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" id="x<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" value="<?php echo ew_HtmlEncode($fatura_os->vr_pagoOsFatura->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_pagoOsFatura" name="o<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" id="o<?php echo $fatura_os_grid->RowIndex ?>_vr_pagoOsFatura" value="<?php echo ew_HtmlEncode($fatura_os->vr_pagoOsFatura->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$fatura_os_grid->ListOptions->Render("body", "right", $fatura_os_grid->RowCnt);
?>
<script type="text/javascript">
ffatura_osgrid.UpdateOpts(<?php echo $fatura_os_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($fatura_os->CurrentMode == "add" || $fatura_os->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $fatura_os_grid->FormKeyCountName ?>" id="<?php echo $fatura_os_grid->FormKeyCountName ?>" value="<?php echo $fatura_os_grid->KeyCount ?>">
<?php echo $fatura_os_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($fatura_os->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $fatura_os_grid->FormKeyCountName ?>" id="<?php echo $fatura_os_grid->FormKeyCountName ?>" value="<?php echo $fatura_os_grid->KeyCount ?>">
<?php echo $fatura_os_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($fatura_os->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ffatura_osgrid">
</div>
<?php

// Close recordset
if ($fatura_os_grid->Recordset)
	$fatura_os_grid->Recordset->Close();
?>
<?php if ($fatura_os_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($fatura_os_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($fatura_os->Export == "") { ?>
<script type="text/javascript">
ffatura_osgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$fatura_os_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$fatura_os_grid->Page_Terminate();
?>
