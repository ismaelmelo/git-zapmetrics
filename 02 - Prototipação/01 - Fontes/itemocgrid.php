<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($itemoc_grid)) $itemoc_grid = new citemoc_grid();

// Page init
$itemoc_grid->Page_Init();

// Page main
$itemoc_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$itemoc_grid->Page_Render();
?>
<?php if ($itemoc->Export == "") { ?>
<script type="text/javascript">

// Page object
var itemoc_grid = new ew_Page("itemoc_grid");
itemoc_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = itemoc_grid.PageID; // For backward compatibility

// Form object
var fitemocgrid = new ew_Form("fitemocgrid");
fitemocgrid.FormKeyCountName = '<?php echo $itemoc_grid->FormKeyCountName ?>';

// Validate form
fitemocgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_tpItem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($itemoc->nu_tpItem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_itemOc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($itemoc->no_itemOc->FldCaption()) ?>");

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
fitemocgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_tpItem", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_itemOc", false)) return false;
	return true;
}

// Form_CustomValidate event
fitemocgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fitemocgrid.ValidateRequired = true;
<?php } else { ?>
fitemocgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fitemocgrid.Lists["x_nu_tpItem"] = {"LinkField":"x_nu_tpItem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpItem","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($itemoc->getCurrentMasterTable() == "" && $itemoc_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $itemoc_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($itemoc->CurrentAction == "gridadd") {
	if ($itemoc->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$itemoc_grid->TotalRecs = $itemoc->SelectRecordCount();
			$itemoc_grid->Recordset = $itemoc_grid->LoadRecordset($itemoc_grid->StartRec-1, $itemoc_grid->DisplayRecs);
		} else {
			if ($itemoc_grid->Recordset = $itemoc_grid->LoadRecordset())
				$itemoc_grid->TotalRecs = $itemoc_grid->Recordset->RecordCount();
		}
		$itemoc_grid->StartRec = 1;
		$itemoc_grid->DisplayRecs = $itemoc_grid->TotalRecs;
	} else {
		$itemoc->CurrentFilter = "0=1";
		$itemoc_grid->StartRec = 1;
		$itemoc_grid->DisplayRecs = $itemoc->GridAddRowCount;
	}
	$itemoc_grid->TotalRecs = $itemoc_grid->DisplayRecs;
	$itemoc_grid->StopRec = $itemoc_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$itemoc_grid->TotalRecs = $itemoc->SelectRecordCount();
	} else {
		if ($itemoc_grid->Recordset = $itemoc_grid->LoadRecordset())
			$itemoc_grid->TotalRecs = $itemoc_grid->Recordset->RecordCount();
	}
	$itemoc_grid->StartRec = 1;
	$itemoc_grid->DisplayRecs = $itemoc_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$itemoc_grid->Recordset = $itemoc_grid->LoadRecordset($itemoc_grid->StartRec-1, $itemoc_grid->DisplayRecs);
}
$itemoc_grid->RenderOtherOptions();
?>
<?php $itemoc_grid->ShowPageHeader(); ?>
<?php
$itemoc_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fitemocgrid" class="ewForm form-horizontal">
<div id="gmp_itemoc" class="ewGridMiddlePanel">
<table id="tbl_itemocgrid" class="ewTable ewTableSeparate">
<?php echo $itemoc->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$itemoc_grid->RenderListOptions();

// Render list options (header, left)
$itemoc_grid->ListOptions->Render("header", "left");
?>
<?php if ($itemoc->nu_tpItem->Visible) { // nu_tpItem ?>
	<?php if ($itemoc->SortUrl($itemoc->nu_tpItem) == "") { ?>
		<td><div id="elh_itemoc_nu_tpItem" class="itemoc_nu_tpItem"><div class="ewTableHeaderCaption"><?php echo $itemoc->nu_tpItem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_itemoc_nu_tpItem" class="itemoc_nu_tpItem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $itemoc->nu_tpItem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($itemoc->nu_tpItem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($itemoc->nu_tpItem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($itemoc->no_itemOc->Visible) { // no_itemOc ?>
	<?php if ($itemoc->SortUrl($itemoc->no_itemOc) == "") { ?>
		<td><div id="elh_itemoc_no_itemOc" class="itemoc_no_itemOc"><div class="ewTableHeaderCaption"><?php echo $itemoc->no_itemOc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_itemoc_no_itemOc" class="itemoc_no_itemOc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $itemoc->no_itemOc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($itemoc->no_itemOc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($itemoc->no_itemOc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$itemoc_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$itemoc_grid->StartRec = 1;
$itemoc_grid->StopRec = $itemoc_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($itemoc_grid->FormKeyCountName) && ($itemoc->CurrentAction == "gridadd" || $itemoc->CurrentAction == "gridedit" || $itemoc->CurrentAction == "F")) {
		$itemoc_grid->KeyCount = $objForm->GetValue($itemoc_grid->FormKeyCountName);
		$itemoc_grid->StopRec = $itemoc_grid->StartRec + $itemoc_grid->KeyCount - 1;
	}
}
$itemoc_grid->RecCnt = $itemoc_grid->StartRec - 1;
if ($itemoc_grid->Recordset && !$itemoc_grid->Recordset->EOF) {
	$itemoc_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $itemoc_grid->StartRec > 1)
		$itemoc_grid->Recordset->Move($itemoc_grid->StartRec - 1);
} elseif (!$itemoc->AllowAddDeleteRow && $itemoc_grid->StopRec == 0) {
	$itemoc_grid->StopRec = $itemoc->GridAddRowCount;
}

// Initialize aggregate
$itemoc->RowType = EW_ROWTYPE_AGGREGATEINIT;
$itemoc->ResetAttrs();
$itemoc_grid->RenderRow();
if ($itemoc->CurrentAction == "gridadd")
	$itemoc_grid->RowIndex = 0;
if ($itemoc->CurrentAction == "gridedit")
	$itemoc_grid->RowIndex = 0;
while ($itemoc_grid->RecCnt < $itemoc_grid->StopRec) {
	$itemoc_grid->RecCnt++;
	if (intval($itemoc_grid->RecCnt) >= intval($itemoc_grid->StartRec)) {
		$itemoc_grid->RowCnt++;
		if ($itemoc->CurrentAction == "gridadd" || $itemoc->CurrentAction == "gridedit" || $itemoc->CurrentAction == "F") {
			$itemoc_grid->RowIndex++;
			$objForm->Index = $itemoc_grid->RowIndex;
			if ($objForm->HasValue($itemoc_grid->FormActionName))
				$itemoc_grid->RowAction = strval($objForm->GetValue($itemoc_grid->FormActionName));
			elseif ($itemoc->CurrentAction == "gridadd")
				$itemoc_grid->RowAction = "insert";
			else
				$itemoc_grid->RowAction = "";
		}

		// Set up key count
		$itemoc_grid->KeyCount = $itemoc_grid->RowIndex;

		// Init row class and style
		$itemoc->ResetAttrs();
		$itemoc->CssClass = "";
		if ($itemoc->CurrentAction == "gridadd") {
			if ($itemoc->CurrentMode == "copy") {
				$itemoc_grid->LoadRowValues($itemoc_grid->Recordset); // Load row values
				$itemoc_grid->SetRecordKey($itemoc_grid->RowOldKey, $itemoc_grid->Recordset); // Set old record key
			} else {
				$itemoc_grid->LoadDefaultValues(); // Load default values
				$itemoc_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$itemoc_grid->LoadRowValues($itemoc_grid->Recordset); // Load row values
		}
		$itemoc->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($itemoc->CurrentAction == "gridadd") // Grid add
			$itemoc->RowType = EW_ROWTYPE_ADD; // Render add
		if ($itemoc->CurrentAction == "gridadd" && $itemoc->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$itemoc_grid->RestoreCurrentRowFormValues($itemoc_grid->RowIndex); // Restore form values
		if ($itemoc->CurrentAction == "gridedit") { // Grid edit
			if ($itemoc->EventCancelled) {
				$itemoc_grid->RestoreCurrentRowFormValues($itemoc_grid->RowIndex); // Restore form values
			}
			if ($itemoc_grid->RowAction == "insert")
				$itemoc->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$itemoc->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($itemoc->CurrentAction == "gridedit" && ($itemoc->RowType == EW_ROWTYPE_EDIT || $itemoc->RowType == EW_ROWTYPE_ADD) && $itemoc->EventCancelled) // Update failed
			$itemoc_grid->RestoreCurrentRowFormValues($itemoc_grid->RowIndex); // Restore form values
		if ($itemoc->RowType == EW_ROWTYPE_EDIT) // Edit row
			$itemoc_grid->EditRowCnt++;
		if ($itemoc->CurrentAction == "F") // Confirm row
			$itemoc_grid->RestoreCurrentRowFormValues($itemoc_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$itemoc->RowAttrs = array_merge($itemoc->RowAttrs, array('data-rowindex'=>$itemoc_grid->RowCnt, 'id'=>'r' . $itemoc_grid->RowCnt . '_itemoc', 'data-rowtype'=>$itemoc->RowType));

		// Render row
		$itemoc_grid->RenderRow();

		// Render list options
		$itemoc_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($itemoc_grid->RowAction <> "delete" && $itemoc_grid->RowAction <> "insertdelete" && !($itemoc_grid->RowAction == "insert" && $itemoc->CurrentAction == "F" && $itemoc_grid->EmptyRow())) {
?>
	<tr<?php echo $itemoc->RowAttributes() ?>>
<?php

// Render list options (body, left)
$itemoc_grid->ListOptions->Render("body", "left", $itemoc_grid->RowCnt);
?>
	<?php if ($itemoc->nu_tpItem->Visible) { // nu_tpItem ?>
		<td<?php echo $itemoc->nu_tpItem->CellAttributes() ?>>
<?php if ($itemoc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $itemoc_grid->RowCnt ?>_itemoc_nu_tpItem" class="control-group itemoc_nu_tpItem">
<select data-field="x_nu_tpItem" id="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" name="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem"<?php echo $itemoc->nu_tpItem->EditAttributes() ?>>
<?php
if (is_array($itemoc->nu_tpItem->EditValue)) {
	$arwrk = $itemoc->nu_tpItem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itemoc->nu_tpItem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $itemoc->nu_tpItem->OldValue = "";
?>
</select>
<script type="text/javascript">
fitemocgrid.Lists["x_nu_tpItem"].Options = <?php echo (is_array($itemoc->nu_tpItem->EditValue)) ? ew_ArrayToJson($itemoc->nu_tpItem->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_tpItem" name="o<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" id="o<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" value="<?php echo ew_HtmlEncode($itemoc->nu_tpItem->OldValue) ?>">
<?php } ?>
<?php if ($itemoc->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $itemoc_grid->RowCnt ?>_itemoc_nu_tpItem" class="control-group itemoc_nu_tpItem">
<select data-field="x_nu_tpItem" id="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" name="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem"<?php echo $itemoc->nu_tpItem->EditAttributes() ?>>
<?php
if (is_array($itemoc->nu_tpItem->EditValue)) {
	$arwrk = $itemoc->nu_tpItem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itemoc->nu_tpItem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $itemoc->nu_tpItem->OldValue = "";
?>
</select>
<script type="text/javascript">
fitemocgrid.Lists["x_nu_tpItem"].Options = <?php echo (is_array($itemoc->nu_tpItem->EditValue)) ? ew_ArrayToJson($itemoc->nu_tpItem->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($itemoc->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $itemoc->nu_tpItem->ViewAttributes() ?>>
<?php echo $itemoc->nu_tpItem->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_tpItem" name="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" id="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" value="<?php echo ew_HtmlEncode($itemoc->nu_tpItem->FormValue) ?>">
<input type="hidden" data-field="x_nu_tpItem" name="o<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" id="o<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" value="<?php echo ew_HtmlEncode($itemoc->nu_tpItem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $itemoc_grid->PageObjName . "_row_" . $itemoc_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($itemoc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_itemOc" name="x<?php echo $itemoc_grid->RowIndex ?>_nu_itemOc" id="x<?php echo $itemoc_grid->RowIndex ?>_nu_itemOc" value="<?php echo ew_HtmlEncode($itemoc->nu_itemOc->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_itemOc" name="o<?php echo $itemoc_grid->RowIndex ?>_nu_itemOc" id="o<?php echo $itemoc_grid->RowIndex ?>_nu_itemOc" value="<?php echo ew_HtmlEncode($itemoc->nu_itemOc->OldValue) ?>">
<?php } ?>
<?php if ($itemoc->RowType == EW_ROWTYPE_EDIT || $itemoc->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_itemOc" name="x<?php echo $itemoc_grid->RowIndex ?>_nu_itemOc" id="x<?php echo $itemoc_grid->RowIndex ?>_nu_itemOc" value="<?php echo ew_HtmlEncode($itemoc->nu_itemOc->CurrentValue) ?>">
<?php } ?>
	<?php if ($itemoc->no_itemOc->Visible) { // no_itemOc ?>
		<td<?php echo $itemoc->no_itemOc->CellAttributes() ?>>
<?php if ($itemoc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $itemoc_grid->RowCnt ?>_itemoc_no_itemOc" class="control-group itemoc_no_itemOc">
<input type="text" data-field="x_no_itemOc" name="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" id="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" size="30" maxlength="100" placeholder="<?php echo $itemoc->no_itemOc->PlaceHolder ?>" value="<?php echo $itemoc->no_itemOc->EditValue ?>"<?php echo $itemoc->no_itemOc->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_itemOc" name="o<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" id="o<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" value="<?php echo ew_HtmlEncode($itemoc->no_itemOc->OldValue) ?>">
<?php } ?>
<?php if ($itemoc->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $itemoc_grid->RowCnt ?>_itemoc_no_itemOc" class="control-group itemoc_no_itemOc">
<input type="text" data-field="x_no_itemOc" name="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" id="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" size="30" maxlength="100" placeholder="<?php echo $itemoc->no_itemOc->PlaceHolder ?>" value="<?php echo $itemoc->no_itemOc->EditValue ?>"<?php echo $itemoc->no_itemOc->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($itemoc->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $itemoc->no_itemOc->ViewAttributes() ?>>
<?php echo $itemoc->no_itemOc->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_itemOc" name="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" id="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" value="<?php echo ew_HtmlEncode($itemoc->no_itemOc->FormValue) ?>">
<input type="hidden" data-field="x_no_itemOc" name="o<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" id="o<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" value="<?php echo ew_HtmlEncode($itemoc->no_itemOc->OldValue) ?>">
<?php } ?>
<a id="<?php echo $itemoc_grid->PageObjName . "_row_" . $itemoc_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$itemoc_grid->ListOptions->Render("body", "right", $itemoc_grid->RowCnt);
?>
	</tr>
<?php if ($itemoc->RowType == EW_ROWTYPE_ADD || $itemoc->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fitemocgrid.UpdateOpts(<?php echo $itemoc_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($itemoc->CurrentAction <> "gridadd" || $itemoc->CurrentMode == "copy")
		if (!$itemoc_grid->Recordset->EOF) $itemoc_grid->Recordset->MoveNext();
}
?>
<?php
	if ($itemoc->CurrentMode == "add" || $itemoc->CurrentMode == "copy" || $itemoc->CurrentMode == "edit") {
		$itemoc_grid->RowIndex = '$rowindex$';
		$itemoc_grid->LoadDefaultValues();

		// Set row properties
		$itemoc->ResetAttrs();
		$itemoc->RowAttrs = array_merge($itemoc->RowAttrs, array('data-rowindex'=>$itemoc_grid->RowIndex, 'id'=>'r0_itemoc', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($itemoc->RowAttrs["class"], "ewTemplate");
		$itemoc->RowType = EW_ROWTYPE_ADD;

		// Render row
		$itemoc_grid->RenderRow();

		// Render list options
		$itemoc_grid->RenderListOptions();
		$itemoc_grid->StartRowCnt = 0;
?>
	<tr<?php echo $itemoc->RowAttributes() ?>>
<?php

// Render list options (body, left)
$itemoc_grid->ListOptions->Render("body", "left", $itemoc_grid->RowIndex);
?>
	<?php if ($itemoc->nu_tpItem->Visible) { // nu_tpItem ?>
		<td>
<?php if ($itemoc->CurrentAction <> "F") { ?>
<select data-field="x_nu_tpItem" id="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" name="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem"<?php echo $itemoc->nu_tpItem->EditAttributes() ?>>
<?php
if (is_array($itemoc->nu_tpItem->EditValue)) {
	$arwrk = $itemoc->nu_tpItem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($itemoc->nu_tpItem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $itemoc->nu_tpItem->OldValue = "";
?>
</select>
<script type="text/javascript">
fitemocgrid.Lists["x_nu_tpItem"].Options = <?php echo (is_array($itemoc->nu_tpItem->EditValue)) ? ew_ArrayToJson($itemoc->nu_tpItem->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $itemoc->nu_tpItem->ViewAttributes() ?>>
<?php echo $itemoc->nu_tpItem->ViewValue ?></span>
<input type="hidden" data-field="x_nu_tpItem" name="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" id="x<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" value="<?php echo ew_HtmlEncode($itemoc->nu_tpItem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_tpItem" name="o<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" id="o<?php echo $itemoc_grid->RowIndex ?>_nu_tpItem" value="<?php echo ew_HtmlEncode($itemoc->nu_tpItem->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($itemoc->no_itemOc->Visible) { // no_itemOc ?>
		<td>
<?php if ($itemoc->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_itemOc" name="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" id="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" size="30" maxlength="100" placeholder="<?php echo $itemoc->no_itemOc->PlaceHolder ?>" value="<?php echo $itemoc->no_itemOc->EditValue ?>"<?php echo $itemoc->no_itemOc->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $itemoc->no_itemOc->ViewAttributes() ?>>
<?php echo $itemoc->no_itemOc->ViewValue ?></span>
<input type="hidden" data-field="x_no_itemOc" name="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" id="x<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" value="<?php echo ew_HtmlEncode($itemoc->no_itemOc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_itemOc" name="o<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" id="o<?php echo $itemoc_grid->RowIndex ?>_no_itemOc" value="<?php echo ew_HtmlEncode($itemoc->no_itemOc->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$itemoc_grid->ListOptions->Render("body", "right", $itemoc_grid->RowCnt);
?>
<script type="text/javascript">
fitemocgrid.UpdateOpts(<?php echo $itemoc_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($itemoc->CurrentMode == "add" || $itemoc->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $itemoc_grid->FormKeyCountName ?>" id="<?php echo $itemoc_grid->FormKeyCountName ?>" value="<?php echo $itemoc_grid->KeyCount ?>">
<?php echo $itemoc_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($itemoc->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $itemoc_grid->FormKeyCountName ?>" id="<?php echo $itemoc_grid->FormKeyCountName ?>" value="<?php echo $itemoc_grid->KeyCount ?>">
<?php echo $itemoc_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($itemoc->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fitemocgrid">
</div>
<?php

// Close recordset
if ($itemoc_grid->Recordset)
	$itemoc_grid->Recordset->Close();
?>
<?php if ($itemoc_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($itemoc_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($itemoc->Export == "") { ?>
<script type="text/javascript">
fitemocgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$itemoc_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$itemoc_grid->Page_Terminate();
?>
