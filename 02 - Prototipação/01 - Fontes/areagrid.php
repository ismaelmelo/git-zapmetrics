<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($area_grid)) $area_grid = new carea_grid();

// Page init
$area_grid->Page_Init();

// Page main
$area_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$area_grid->Page_Render();
?>
<?php if ($area->Export == "") { ?>
<script type="text/javascript">

// Page object
var area_grid = new ew_Page("area_grid");
area_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = area_grid.PageID; // For backward compatibility

// Form object
var fareagrid = new ew_Form("fareagrid");
fareagrid.FormKeyCountName = '<?php echo $area_grid->FormKeyCountName ?>';

// Validate form
fareagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_area");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($area->no_area->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpArea");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($area->nu_tpArea->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($area->ic_ativo->FldCaption()) ?>");

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
fareagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_area", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_tpArea", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_pessoaResp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
fareagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fareagrid.ValidateRequired = true;
<?php } else { ?>
fareagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fareagrid.Lists["x_nu_tpArea"] = {"LinkField":"x_nu_tpArea","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpArea","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fareagrid.Lists["x_nu_pessoaResp"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($area->getCurrentMasterTable() == "" && $area_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $area_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($area->CurrentAction == "gridadd") {
	if ($area->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$area_grid->TotalRecs = $area->SelectRecordCount();
			$area_grid->Recordset = $area_grid->LoadRecordset($area_grid->StartRec-1, $area_grid->DisplayRecs);
		} else {
			if ($area_grid->Recordset = $area_grid->LoadRecordset())
				$area_grid->TotalRecs = $area_grid->Recordset->RecordCount();
		}
		$area_grid->StartRec = 1;
		$area_grid->DisplayRecs = $area_grid->TotalRecs;
	} else {
		$area->CurrentFilter = "0=1";
		$area_grid->StartRec = 1;
		$area_grid->DisplayRecs = $area->GridAddRowCount;
	}
	$area_grid->TotalRecs = $area_grid->DisplayRecs;
	$area_grid->StopRec = $area_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$area_grid->TotalRecs = $area->SelectRecordCount();
	} else {
		if ($area_grid->Recordset = $area_grid->LoadRecordset())
			$area_grid->TotalRecs = $area_grid->Recordset->RecordCount();
	}
	$area_grid->StartRec = 1;
	$area_grid->DisplayRecs = $area_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$area_grid->Recordset = $area_grid->LoadRecordset($area_grid->StartRec-1, $area_grid->DisplayRecs);
}
$area_grid->RenderOtherOptions();
?>
<?php $area_grid->ShowPageHeader(); ?>
<?php
$area_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fareagrid" class="ewForm form-horizontal">
<div id="gmp_area" class="ewGridMiddlePanel">
<table id="tbl_areagrid" class="ewTable ewTableSeparate">
<?php echo $area->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$area_grid->RenderListOptions();

// Render list options (header, left)
$area_grid->ListOptions->Render("header", "left");
?>
<?php if ($area->no_area->Visible) { // no_area ?>
	<?php if ($area->SortUrl($area->no_area) == "") { ?>
		<td><div id="elh_area_no_area" class="area_no_area"><div class="ewTableHeaderCaption"><?php echo $area->no_area->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_area_no_area" class="area_no_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $area->no_area->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($area->no_area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($area->no_area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($area->nu_tpArea->Visible) { // nu_tpArea ?>
	<?php if ($area->SortUrl($area->nu_tpArea) == "") { ?>
		<td><div id="elh_area_nu_tpArea" class="area_nu_tpArea"><div class="ewTableHeaderCaption"><?php echo $area->nu_tpArea->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_area_nu_tpArea" class="area_nu_tpArea">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $area->nu_tpArea->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($area->nu_tpArea->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($area->nu_tpArea->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($area->nu_pessoaResp->Visible) { // nu_pessoaResp ?>
	<?php if ($area->SortUrl($area->nu_pessoaResp) == "") { ?>
		<td><div id="elh_area_nu_pessoaResp" class="area_nu_pessoaResp"><div class="ewTableHeaderCaption"><?php echo $area->nu_pessoaResp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_area_nu_pessoaResp" class="area_nu_pessoaResp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $area->nu_pessoaResp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($area->nu_pessoaResp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($area->nu_pessoaResp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($area->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($area->SortUrl($area->ic_ativo) == "") { ?>
		<td><div id="elh_area_ic_ativo" class="area_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $area->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_area_ic_ativo" class="area_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $area->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($area->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($area->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$area_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$area_grid->StartRec = 1;
$area_grid->StopRec = $area_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($area_grid->FormKeyCountName) && ($area->CurrentAction == "gridadd" || $area->CurrentAction == "gridedit" || $area->CurrentAction == "F")) {
		$area_grid->KeyCount = $objForm->GetValue($area_grid->FormKeyCountName);
		$area_grid->StopRec = $area_grid->StartRec + $area_grid->KeyCount - 1;
	}
}
$area_grid->RecCnt = $area_grid->StartRec - 1;
if ($area_grid->Recordset && !$area_grid->Recordset->EOF) {
	$area_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $area_grid->StartRec > 1)
		$area_grid->Recordset->Move($area_grid->StartRec - 1);
} elseif (!$area->AllowAddDeleteRow && $area_grid->StopRec == 0) {
	$area_grid->StopRec = $area->GridAddRowCount;
}

// Initialize aggregate
$area->RowType = EW_ROWTYPE_AGGREGATEINIT;
$area->ResetAttrs();
$area_grid->RenderRow();
if ($area->CurrentAction == "gridadd")
	$area_grid->RowIndex = 0;
if ($area->CurrentAction == "gridedit")
	$area_grid->RowIndex = 0;
while ($area_grid->RecCnt < $area_grid->StopRec) {
	$area_grid->RecCnt++;
	if (intval($area_grid->RecCnt) >= intval($area_grid->StartRec)) {
		$area_grid->RowCnt++;
		if ($area->CurrentAction == "gridadd" || $area->CurrentAction == "gridedit" || $area->CurrentAction == "F") {
			$area_grid->RowIndex++;
			$objForm->Index = $area_grid->RowIndex;
			if ($objForm->HasValue($area_grid->FormActionName))
				$area_grid->RowAction = strval($objForm->GetValue($area_grid->FormActionName));
			elseif ($area->CurrentAction == "gridadd")
				$area_grid->RowAction = "insert";
			else
				$area_grid->RowAction = "";
		}

		// Set up key count
		$area_grid->KeyCount = $area_grid->RowIndex;

		// Init row class and style
		$area->ResetAttrs();
		$area->CssClass = "";
		if ($area->CurrentAction == "gridadd") {
			if ($area->CurrentMode == "copy") {
				$area_grid->LoadRowValues($area_grid->Recordset); // Load row values
				$area_grid->SetRecordKey($area_grid->RowOldKey, $area_grid->Recordset); // Set old record key
			} else {
				$area_grid->LoadDefaultValues(); // Load default values
				$area_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$area_grid->LoadRowValues($area_grid->Recordset); // Load row values
		}
		$area->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($area->CurrentAction == "gridadd") // Grid add
			$area->RowType = EW_ROWTYPE_ADD; // Render add
		if ($area->CurrentAction == "gridadd" && $area->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$area_grid->RestoreCurrentRowFormValues($area_grid->RowIndex); // Restore form values
		if ($area->CurrentAction == "gridedit") { // Grid edit
			if ($area->EventCancelled) {
				$area_grid->RestoreCurrentRowFormValues($area_grid->RowIndex); // Restore form values
			}
			if ($area_grid->RowAction == "insert")
				$area->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$area->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($area->CurrentAction == "gridedit" && ($area->RowType == EW_ROWTYPE_EDIT || $area->RowType == EW_ROWTYPE_ADD) && $area->EventCancelled) // Update failed
			$area_grid->RestoreCurrentRowFormValues($area_grid->RowIndex); // Restore form values
		if ($area->RowType == EW_ROWTYPE_EDIT) // Edit row
			$area_grid->EditRowCnt++;
		if ($area->CurrentAction == "F") // Confirm row
			$area_grid->RestoreCurrentRowFormValues($area_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$area->RowAttrs = array_merge($area->RowAttrs, array('data-rowindex'=>$area_grid->RowCnt, 'id'=>'r' . $area_grid->RowCnt . '_area', 'data-rowtype'=>$area->RowType));

		// Render row
		$area_grid->RenderRow();

		// Render list options
		$area_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($area_grid->RowAction <> "delete" && $area_grid->RowAction <> "insertdelete" && !($area_grid->RowAction == "insert" && $area->CurrentAction == "F" && $area_grid->EmptyRow())) {
?>
	<tr<?php echo $area->RowAttributes() ?>>
<?php

// Render list options (body, left)
$area_grid->ListOptions->Render("body", "left", $area_grid->RowCnt);
?>
	<?php if ($area->no_area->Visible) { // no_area ?>
		<td<?php echo $area->no_area->CellAttributes() ?>>
<?php if ($area->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $area_grid->RowCnt ?>_area_no_area" class="control-group area_no_area">
<input type="text" data-field="x_no_area" name="x<?php echo $area_grid->RowIndex ?>_no_area" id="x<?php echo $area_grid->RowIndex ?>_no_area" size="30" maxlength="100" placeholder="<?php echo $area->no_area->PlaceHolder ?>" value="<?php echo $area->no_area->EditValue ?>"<?php echo $area->no_area->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_area" name="o<?php echo $area_grid->RowIndex ?>_no_area" id="o<?php echo $area_grid->RowIndex ?>_no_area" value="<?php echo ew_HtmlEncode($area->no_area->OldValue) ?>">
<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $area_grid->RowCnt ?>_area_no_area" class="control-group area_no_area">
<input type="text" data-field="x_no_area" name="x<?php echo $area_grid->RowIndex ?>_no_area" id="x<?php echo $area_grid->RowIndex ?>_no_area" size="30" maxlength="100" placeholder="<?php echo $area->no_area->PlaceHolder ?>" value="<?php echo $area->no_area->EditValue ?>"<?php echo $area->no_area->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $area->no_area->ViewAttributes() ?>>
<?php echo $area->no_area->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_area" name="x<?php echo $area_grid->RowIndex ?>_no_area" id="x<?php echo $area_grid->RowIndex ?>_no_area" value="<?php echo ew_HtmlEncode($area->no_area->FormValue) ?>">
<input type="hidden" data-field="x_no_area" name="o<?php echo $area_grid->RowIndex ?>_no_area" id="o<?php echo $area_grid->RowIndex ?>_no_area" value="<?php echo ew_HtmlEncode($area->no_area->OldValue) ?>">
<?php } ?>
<a id="<?php echo $area_grid->PageObjName . "_row_" . $area_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_area" name="x<?php echo $area_grid->RowIndex ?>_nu_area" id="x<?php echo $area_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($area->nu_area->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_area" name="o<?php echo $area_grid->RowIndex ?>_nu_area" id="o<?php echo $area_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($area->nu_area->OldValue) ?>">
<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_EDIT || $area->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_area" name="x<?php echo $area_grid->RowIndex ?>_nu_area" id="x<?php echo $area_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($area->nu_area->CurrentValue) ?>">
<?php } ?>
	<?php if ($area->nu_tpArea->Visible) { // nu_tpArea ?>
		<td<?php echo $area->nu_tpArea->CellAttributes() ?>>
<?php if ($area->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($area->nu_tpArea->getSessionValue() <> "") { ?>
<span<?php echo $area->nu_tpArea->ViewAttributes() ?>>
<?php echo $area->nu_tpArea->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" name="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" value="<?php echo ew_HtmlEncode($area->nu_tpArea->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_tpArea" id="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" name="x<?php echo $area_grid->RowIndex ?>_nu_tpArea"<?php echo $area->nu_tpArea->EditAttributes() ?>>
<?php
if (is_array($area->nu_tpArea->EditValue)) {
	$arwrk = $area->nu_tpArea->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->nu_tpArea->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $area->nu_tpArea->OldValue = "";
?>
</select>
<script type="text/javascript">
fareagrid.Lists["x_nu_tpArea"].Options = <?php echo (is_array($area->nu_tpArea->EditValue)) ? ew_ArrayToJson($area->nu_tpArea->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_nu_tpArea" name="o<?php echo $area_grid->RowIndex ?>_nu_tpArea" id="o<?php echo $area_grid->RowIndex ?>_nu_tpArea" value="<?php echo ew_HtmlEncode($area->nu_tpArea->OldValue) ?>">
<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($area->nu_tpArea->getSessionValue() <> "") { ?>
<span<?php echo $area->nu_tpArea->ViewAttributes() ?>>
<?php echo $area->nu_tpArea->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" name="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" value="<?php echo ew_HtmlEncode($area->nu_tpArea->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_tpArea" id="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" name="x<?php echo $area_grid->RowIndex ?>_nu_tpArea"<?php echo $area->nu_tpArea->EditAttributes() ?>>
<?php
if (is_array($area->nu_tpArea->EditValue)) {
	$arwrk = $area->nu_tpArea->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->nu_tpArea->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $area->nu_tpArea->OldValue = "";
?>
</select>
<script type="text/javascript">
fareagrid.Lists["x_nu_tpArea"].Options = <?php echo (is_array($area->nu_tpArea->EditValue)) ? ew_ArrayToJson($area->nu_tpArea->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $area->nu_tpArea->ViewAttributes() ?>>
<?php echo $area->nu_tpArea->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_tpArea" name="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" id="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" value="<?php echo ew_HtmlEncode($area->nu_tpArea->FormValue) ?>">
<input type="hidden" data-field="x_nu_tpArea" name="o<?php echo $area_grid->RowIndex ?>_nu_tpArea" id="o<?php echo $area_grid->RowIndex ?>_nu_tpArea" value="<?php echo ew_HtmlEncode($area->nu_tpArea->OldValue) ?>">
<?php } ?>
<a id="<?php echo $area_grid->PageObjName . "_row_" . $area_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($area->nu_pessoaResp->Visible) { // nu_pessoaResp ?>
		<td<?php echo $area->nu_pessoaResp->CellAttributes() ?>>
<?php if ($area->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $area_grid->RowCnt ?>_area_nu_pessoaResp" class="control-group area_nu_pessoaResp">
<select data-field="x_nu_pessoaResp" id="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" name="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp"<?php echo $area->nu_pessoaResp->EditAttributes() ?>>
<?php
if (is_array($area->nu_pessoaResp->EditValue)) {
	$arwrk = $area->nu_pessoaResp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->nu_pessoaResp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $area->nu_pessoaResp->OldValue = "";
?>
</select>
<script type="text/javascript">
fareagrid.Lists["x_nu_pessoaResp"].Options = <?php echo (is_array($area->nu_pessoaResp->EditValue)) ? ew_ArrayToJson($area->nu_pessoaResp->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_pessoaResp" name="o<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" id="o<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" value="<?php echo ew_HtmlEncode($area->nu_pessoaResp->OldValue) ?>">
<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $area_grid->RowCnt ?>_area_nu_pessoaResp" class="control-group area_nu_pessoaResp">
<select data-field="x_nu_pessoaResp" id="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" name="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp"<?php echo $area->nu_pessoaResp->EditAttributes() ?>>
<?php
if (is_array($area->nu_pessoaResp->EditValue)) {
	$arwrk = $area->nu_pessoaResp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->nu_pessoaResp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $area->nu_pessoaResp->OldValue = "";
?>
</select>
<script type="text/javascript">
fareagrid.Lists["x_nu_pessoaResp"].Options = <?php echo (is_array($area->nu_pessoaResp->EditValue)) ? ew_ArrayToJson($area->nu_pessoaResp->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $area->nu_pessoaResp->ViewAttributes() ?>>
<?php echo $area->nu_pessoaResp->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_pessoaResp" name="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" id="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" value="<?php echo ew_HtmlEncode($area->nu_pessoaResp->FormValue) ?>">
<input type="hidden" data-field="x_nu_pessoaResp" name="o<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" id="o<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" value="<?php echo ew_HtmlEncode($area->nu_pessoaResp->OldValue) ?>">
<?php } ?>
<a id="<?php echo $area_grid->PageObjName . "_row_" . $area_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($area->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $area->ic_ativo->CellAttributes() ?>>
<?php if ($area->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $area_grid->RowCnt ?>_area_ic_ativo" class="control-group area_ic_ativo">
<div id="tp_x<?php echo $area_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $area_grid->RowIndex ?>_ic_ativo" id="x<?php echo $area_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $area->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $area_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $area->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $area_grid->RowIndex ?>_ic_ativo" id="x<?php echo $area_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $area->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $area->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $area_grid->RowIndex ?>_ic_ativo" id="o<?php echo $area_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($area->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $area_grid->RowCnt ?>_area_ic_ativo" class="control-group area_ic_ativo">
<div id="tp_x<?php echo $area_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $area_grid->RowIndex ?>_ic_ativo" id="x<?php echo $area_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $area->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $area_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $area->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $area_grid->RowIndex ?>_ic_ativo" id="x<?php echo $area_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $area->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $area->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($area->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $area->ic_ativo->ViewAttributes() ?>>
<?php echo $area->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $area_grid->RowIndex ?>_ic_ativo" id="x<?php echo $area_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($area->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $area_grid->RowIndex ?>_ic_ativo" id="o<?php echo $area_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($area->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $area_grid->PageObjName . "_row_" . $area_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$area_grid->ListOptions->Render("body", "right", $area_grid->RowCnt);
?>
	</tr>
<?php if ($area->RowType == EW_ROWTYPE_ADD || $area->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fareagrid.UpdateOpts(<?php echo $area_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($area->CurrentAction <> "gridadd" || $area->CurrentMode == "copy")
		if (!$area_grid->Recordset->EOF) $area_grid->Recordset->MoveNext();
}
?>
<?php
	if ($area->CurrentMode == "add" || $area->CurrentMode == "copy" || $area->CurrentMode == "edit") {
		$area_grid->RowIndex = '$rowindex$';
		$area_grid->LoadDefaultValues();

		// Set row properties
		$area->ResetAttrs();
		$area->RowAttrs = array_merge($area->RowAttrs, array('data-rowindex'=>$area_grid->RowIndex, 'id'=>'r0_area', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($area->RowAttrs["class"], "ewTemplate");
		$area->RowType = EW_ROWTYPE_ADD;

		// Render row
		$area_grid->RenderRow();

		// Render list options
		$area_grid->RenderListOptions();
		$area_grid->StartRowCnt = 0;
?>
	<tr<?php echo $area->RowAttributes() ?>>
<?php

// Render list options (body, left)
$area_grid->ListOptions->Render("body", "left", $area_grid->RowIndex);
?>
	<?php if ($area->no_area->Visible) { // no_area ?>
		<td>
<?php if ($area->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_area" name="x<?php echo $area_grid->RowIndex ?>_no_area" id="x<?php echo $area_grid->RowIndex ?>_no_area" size="30" maxlength="100" placeholder="<?php echo $area->no_area->PlaceHolder ?>" value="<?php echo $area->no_area->EditValue ?>"<?php echo $area->no_area->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $area->no_area->ViewAttributes() ?>>
<?php echo $area->no_area->ViewValue ?></span>
<input type="hidden" data-field="x_no_area" name="x<?php echo $area_grid->RowIndex ?>_no_area" id="x<?php echo $area_grid->RowIndex ?>_no_area" value="<?php echo ew_HtmlEncode($area->no_area->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_area" name="o<?php echo $area_grid->RowIndex ?>_no_area" id="o<?php echo $area_grid->RowIndex ?>_no_area" value="<?php echo ew_HtmlEncode($area->no_area->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($area->nu_tpArea->Visible) { // nu_tpArea ?>
		<td>
<?php if ($area->CurrentAction <> "F") { ?>
<?php if ($area->nu_tpArea->getSessionValue() <> "") { ?>
<span<?php echo $area->nu_tpArea->ViewAttributes() ?>>
<?php echo $area->nu_tpArea->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" name="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" value="<?php echo ew_HtmlEncode($area->nu_tpArea->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_tpArea" id="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" name="x<?php echo $area_grid->RowIndex ?>_nu_tpArea"<?php echo $area->nu_tpArea->EditAttributes() ?>>
<?php
if (is_array($area->nu_tpArea->EditValue)) {
	$arwrk = $area->nu_tpArea->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->nu_tpArea->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $area->nu_tpArea->OldValue = "";
?>
</select>
<script type="text/javascript">
fareagrid.Lists["x_nu_tpArea"].Options = <?php echo (is_array($area->nu_tpArea->EditValue)) ? ew_ArrayToJson($area->nu_tpArea->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $area->nu_tpArea->ViewAttributes() ?>>
<?php echo $area->nu_tpArea->ViewValue ?></span>
<input type="hidden" data-field="x_nu_tpArea" name="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" id="x<?php echo $area_grid->RowIndex ?>_nu_tpArea" value="<?php echo ew_HtmlEncode($area->nu_tpArea->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_tpArea" name="o<?php echo $area_grid->RowIndex ?>_nu_tpArea" id="o<?php echo $area_grid->RowIndex ?>_nu_tpArea" value="<?php echo ew_HtmlEncode($area->nu_tpArea->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($area->nu_pessoaResp->Visible) { // nu_pessoaResp ?>
		<td>
<?php if ($area->CurrentAction <> "F") { ?>
<select data-field="x_nu_pessoaResp" id="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" name="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp"<?php echo $area->nu_pessoaResp->EditAttributes() ?>>
<?php
if (is_array($area->nu_pessoaResp->EditValue)) {
	$arwrk = $area->nu_pessoaResp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->nu_pessoaResp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $area->nu_pessoaResp->OldValue = "";
?>
</select>
<script type="text/javascript">
fareagrid.Lists["x_nu_pessoaResp"].Options = <?php echo (is_array($area->nu_pessoaResp->EditValue)) ? ew_ArrayToJson($area->nu_pessoaResp->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $area->nu_pessoaResp->ViewAttributes() ?>>
<?php echo $area->nu_pessoaResp->ViewValue ?></span>
<input type="hidden" data-field="x_nu_pessoaResp" name="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" id="x<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" value="<?php echo ew_HtmlEncode($area->nu_pessoaResp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_pessoaResp" name="o<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" id="o<?php echo $area_grid->RowIndex ?>_nu_pessoaResp" value="<?php echo ew_HtmlEncode($area->nu_pessoaResp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($area->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($area->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $area_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $area_grid->RowIndex ?>_ic_ativo" id="x<?php echo $area_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $area->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $area_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $area->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($area->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $area_grid->RowIndex ?>_ic_ativo" id="x<?php echo $area_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $area->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $area->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $area->ic_ativo->ViewAttributes() ?>>
<?php echo $area->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $area_grid->RowIndex ?>_ic_ativo" id="x<?php echo $area_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($area->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $area_grid->RowIndex ?>_ic_ativo" id="o<?php echo $area_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($area->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$area_grid->ListOptions->Render("body", "right", $area_grid->RowCnt);
?>
<script type="text/javascript">
fareagrid.UpdateOpts(<?php echo $area_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($area->CurrentMode == "add" || $area->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $area_grid->FormKeyCountName ?>" id="<?php echo $area_grid->FormKeyCountName ?>" value="<?php echo $area_grid->KeyCount ?>">
<?php echo $area_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($area->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $area_grid->FormKeyCountName ?>" id="<?php echo $area_grid->FormKeyCountName ?>" value="<?php echo $area_grid->KeyCount ?>">
<?php echo $area_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($area->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fareagrid">
</div>
<?php

// Close recordset
if ($area_grid->Recordset)
	$area_grid->Recordset->Close();
?>
<?php if ($area_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($area_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($area->Export == "") { ?>
<script type="text/javascript">
fareagrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$area_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$area_grid->Page_Terminate();
?>
