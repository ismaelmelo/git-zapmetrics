<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($ator_grid)) $ator_grid = new cator_grid();

// Page init
$ator_grid->Page_Init();

// Page main
$ator_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ator_grid->Page_Render();
?>
<?php if ($ator->Export == "") { ?>
<script type="text/javascript">

// Page object
var ator_grid = new ew_Page("ator_grid");
ator_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = ator_grid.PageID; // For backward compatibility

// Form object
var fatorgrid = new ew_Form("fatorgrid");
fatorgrid.FormKeyCountName = '<?php echo $ator_grid->FormKeyCountName ?>';

// Validate form
fatorgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_ator");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ator->no_ator->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ator->ic_ativo->FldCaption()) ?>");

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
fatorgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_ator", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
fatorgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fatorgrid.ValidateRequired = true;
<?php } else { ?>
fatorgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($ator->getCurrentMasterTable() == "" && $ator_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $ator_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($ator->CurrentAction == "gridadd") {
	if ($ator->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$ator_grid->TotalRecs = $ator->SelectRecordCount();
			$ator_grid->Recordset = $ator_grid->LoadRecordset($ator_grid->StartRec-1, $ator_grid->DisplayRecs);
		} else {
			if ($ator_grid->Recordset = $ator_grid->LoadRecordset())
				$ator_grid->TotalRecs = $ator_grid->Recordset->RecordCount();
		}
		$ator_grid->StartRec = 1;
		$ator_grid->DisplayRecs = $ator_grid->TotalRecs;
	} else {
		$ator->CurrentFilter = "0=1";
		$ator_grid->StartRec = 1;
		$ator_grid->DisplayRecs = $ator->GridAddRowCount;
	}
	$ator_grid->TotalRecs = $ator_grid->DisplayRecs;
	$ator_grid->StopRec = $ator_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$ator_grid->TotalRecs = $ator->SelectRecordCount();
	} else {
		if ($ator_grid->Recordset = $ator_grid->LoadRecordset())
			$ator_grid->TotalRecs = $ator_grid->Recordset->RecordCount();
	}
	$ator_grid->StartRec = 1;
	$ator_grid->DisplayRecs = $ator_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$ator_grid->Recordset = $ator_grid->LoadRecordset($ator_grid->StartRec-1, $ator_grid->DisplayRecs);
}
$ator_grid->RenderOtherOptions();
?>
<?php $ator_grid->ShowPageHeader(); ?>
<?php
$ator_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fatorgrid" class="ewForm form-horizontal">
<div id="gmp_ator" class="ewGridMiddlePanel">
<table id="tbl_atorgrid" class="ewTable ewTableSeparate">
<?php echo $ator->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$ator_grid->RenderListOptions();

// Render list options (header, left)
$ator_grid->ListOptions->Render("header", "left");
?>
<?php if ($ator->no_ator->Visible) { // no_ator ?>
	<?php if ($ator->SortUrl($ator->no_ator) == "") { ?>
		<td><div id="elh_ator_no_ator" class="ator_no_ator"><div class="ewTableHeaderCaption"><?php echo $ator->no_ator->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ator_no_ator" class="ator_no_ator">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ator->no_ator->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ator->no_ator->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ator->no_ator->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ator->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($ator->SortUrl($ator->ic_ativo) == "") { ?>
		<td><div id="elh_ator_ic_ativo" class="ator_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $ator->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ator_ic_ativo" class="ator_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ator->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ator->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ator->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ator_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$ator_grid->StartRec = 1;
$ator_grid->StopRec = $ator_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($ator_grid->FormKeyCountName) && ($ator->CurrentAction == "gridadd" || $ator->CurrentAction == "gridedit" || $ator->CurrentAction == "F")) {
		$ator_grid->KeyCount = $objForm->GetValue($ator_grid->FormKeyCountName);
		$ator_grid->StopRec = $ator_grid->StartRec + $ator_grid->KeyCount - 1;
	}
}
$ator_grid->RecCnt = $ator_grid->StartRec - 1;
if ($ator_grid->Recordset && !$ator_grid->Recordset->EOF) {
	$ator_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $ator_grid->StartRec > 1)
		$ator_grid->Recordset->Move($ator_grid->StartRec - 1);
} elseif (!$ator->AllowAddDeleteRow && $ator_grid->StopRec == 0) {
	$ator_grid->StopRec = $ator->GridAddRowCount;
}

// Initialize aggregate
$ator->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ator->ResetAttrs();
$ator_grid->RenderRow();
if ($ator->CurrentAction == "gridadd")
	$ator_grid->RowIndex = 0;
if ($ator->CurrentAction == "gridedit")
	$ator_grid->RowIndex = 0;
while ($ator_grid->RecCnt < $ator_grid->StopRec) {
	$ator_grid->RecCnt++;
	if (intval($ator_grid->RecCnt) >= intval($ator_grid->StartRec)) {
		$ator_grid->RowCnt++;
		if ($ator->CurrentAction == "gridadd" || $ator->CurrentAction == "gridedit" || $ator->CurrentAction == "F") {
			$ator_grid->RowIndex++;
			$objForm->Index = $ator_grid->RowIndex;
			if ($objForm->HasValue($ator_grid->FormActionName))
				$ator_grid->RowAction = strval($objForm->GetValue($ator_grid->FormActionName));
			elseif ($ator->CurrentAction == "gridadd")
				$ator_grid->RowAction = "insert";
			else
				$ator_grid->RowAction = "";
		}

		// Set up key count
		$ator_grid->KeyCount = $ator_grid->RowIndex;

		// Init row class and style
		$ator->ResetAttrs();
		$ator->CssClass = "";
		if ($ator->CurrentAction == "gridadd") {
			if ($ator->CurrentMode == "copy") {
				$ator_grid->LoadRowValues($ator_grid->Recordset); // Load row values
				$ator_grid->SetRecordKey($ator_grid->RowOldKey, $ator_grid->Recordset); // Set old record key
			} else {
				$ator_grid->LoadDefaultValues(); // Load default values
				$ator_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$ator_grid->LoadRowValues($ator_grid->Recordset); // Load row values
		}
		$ator->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($ator->CurrentAction == "gridadd") // Grid add
			$ator->RowType = EW_ROWTYPE_ADD; // Render add
		if ($ator->CurrentAction == "gridadd" && $ator->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$ator_grid->RestoreCurrentRowFormValues($ator_grid->RowIndex); // Restore form values
		if ($ator->CurrentAction == "gridedit") { // Grid edit
			if ($ator->EventCancelled) {
				$ator_grid->RestoreCurrentRowFormValues($ator_grid->RowIndex); // Restore form values
			}
			if ($ator_grid->RowAction == "insert")
				$ator->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$ator->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($ator->CurrentAction == "gridedit" && ($ator->RowType == EW_ROWTYPE_EDIT || $ator->RowType == EW_ROWTYPE_ADD) && $ator->EventCancelled) // Update failed
			$ator_grid->RestoreCurrentRowFormValues($ator_grid->RowIndex); // Restore form values
		if ($ator->RowType == EW_ROWTYPE_EDIT) // Edit row
			$ator_grid->EditRowCnt++;
		if ($ator->CurrentAction == "F") // Confirm row
			$ator_grid->RestoreCurrentRowFormValues($ator_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$ator->RowAttrs = array_merge($ator->RowAttrs, array('data-rowindex'=>$ator_grid->RowCnt, 'id'=>'r' . $ator_grid->RowCnt . '_ator', 'data-rowtype'=>$ator->RowType));

		// Render row
		$ator_grid->RenderRow();

		// Render list options
		$ator_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($ator_grid->RowAction <> "delete" && $ator_grid->RowAction <> "insertdelete" && !($ator_grid->RowAction == "insert" && $ator->CurrentAction == "F" && $ator_grid->EmptyRow())) {
?>
	<tr<?php echo $ator->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ator_grid->ListOptions->Render("body", "left", $ator_grid->RowCnt);
?>
	<?php if ($ator->no_ator->Visible) { // no_ator ?>
		<td<?php echo $ator->no_ator->CellAttributes() ?>>
<?php if ($ator->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ator_grid->RowCnt ?>_ator_no_ator" class="control-group ator_no_ator">
<input type="text" data-field="x_no_ator" name="x<?php echo $ator_grid->RowIndex ?>_no_ator" id="x<?php echo $ator_grid->RowIndex ?>_no_ator" size="30" maxlength="50" placeholder="<?php echo $ator->no_ator->PlaceHolder ?>" value="<?php echo $ator->no_ator->EditValue ?>"<?php echo $ator->no_ator->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_ator" name="o<?php echo $ator_grid->RowIndex ?>_no_ator" id="o<?php echo $ator_grid->RowIndex ?>_no_ator" value="<?php echo ew_HtmlEncode($ator->no_ator->OldValue) ?>">
<?php } ?>
<?php if ($ator->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ator_grid->RowCnt ?>_ator_no_ator" class="control-group ator_no_ator">
<input type="text" data-field="x_no_ator" name="x<?php echo $ator_grid->RowIndex ?>_no_ator" id="x<?php echo $ator_grid->RowIndex ?>_no_ator" size="30" maxlength="50" placeholder="<?php echo $ator->no_ator->PlaceHolder ?>" value="<?php echo $ator->no_ator->EditValue ?>"<?php echo $ator->no_ator->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ator->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ator->no_ator->ViewAttributes() ?>>
<?php echo $ator->no_ator->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_ator" name="x<?php echo $ator_grid->RowIndex ?>_no_ator" id="x<?php echo $ator_grid->RowIndex ?>_no_ator" value="<?php echo ew_HtmlEncode($ator->no_ator->FormValue) ?>">
<input type="hidden" data-field="x_no_ator" name="o<?php echo $ator_grid->RowIndex ?>_no_ator" id="o<?php echo $ator_grid->RowIndex ?>_no_ator" value="<?php echo ew_HtmlEncode($ator->no_ator->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ator_grid->PageObjName . "_row_" . $ator_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($ator->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_ator" name="x<?php echo $ator_grid->RowIndex ?>_nu_ator" id="x<?php echo $ator_grid->RowIndex ?>_nu_ator" value="<?php echo ew_HtmlEncode($ator->nu_ator->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_ator" name="o<?php echo $ator_grid->RowIndex ?>_nu_ator" id="o<?php echo $ator_grid->RowIndex ?>_nu_ator" value="<?php echo ew_HtmlEncode($ator->nu_ator->OldValue) ?>">
<?php } ?>
<?php if ($ator->RowType == EW_ROWTYPE_EDIT || $ator->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_ator" name="x<?php echo $ator_grid->RowIndex ?>_nu_ator" id="x<?php echo $ator_grid->RowIndex ?>_nu_ator" value="<?php echo ew_HtmlEncode($ator->nu_ator->CurrentValue) ?>">
<?php } ?>
	<?php if ($ator->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $ator->ic_ativo->CellAttributes() ?>>
<?php if ($ator->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ator_grid->RowCnt ?>_ator_ic_ativo" class="control-group ator_ic_ativo">
<div id="tp_x<?php echo $ator_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $ator->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ator_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ator->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ator->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ator_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ator->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ator->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="o<?php echo $ator_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ator->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($ator->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ator_grid->RowCnt ?>_ator_ic_ativo" class="control-group ator_ic_ativo">
<div id="tp_x<?php echo $ator_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $ator->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ator_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ator->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ator->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ator_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ator->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ator->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($ator->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ator->ic_ativo->ViewAttributes() ?>>
<?php echo $ator->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ator->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="o<?php echo $ator_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ator->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ator_grid->PageObjName . "_row_" . $ator_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$ator_grid->ListOptions->Render("body", "right", $ator_grid->RowCnt);
?>
	</tr>
<?php if ($ator->RowType == EW_ROWTYPE_ADD || $ator->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fatorgrid.UpdateOpts(<?php echo $ator_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($ator->CurrentAction <> "gridadd" || $ator->CurrentMode == "copy")
		if (!$ator_grid->Recordset->EOF) $ator_grid->Recordset->MoveNext();
}
?>
<?php
	if ($ator->CurrentMode == "add" || $ator->CurrentMode == "copy" || $ator->CurrentMode == "edit") {
		$ator_grid->RowIndex = '$rowindex$';
		$ator_grid->LoadDefaultValues();

		// Set row properties
		$ator->ResetAttrs();
		$ator->RowAttrs = array_merge($ator->RowAttrs, array('data-rowindex'=>$ator_grid->RowIndex, 'id'=>'r0_ator', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($ator->RowAttrs["class"], "ewTemplate");
		$ator->RowType = EW_ROWTYPE_ADD;

		// Render row
		$ator_grid->RenderRow();

		// Render list options
		$ator_grid->RenderListOptions();
		$ator_grid->StartRowCnt = 0;
?>
	<tr<?php echo $ator->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ator_grid->ListOptions->Render("body", "left", $ator_grid->RowIndex);
?>
	<?php if ($ator->no_ator->Visible) { // no_ator ?>
		<td>
<?php if ($ator->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_ator" name="x<?php echo $ator_grid->RowIndex ?>_no_ator" id="x<?php echo $ator_grid->RowIndex ?>_no_ator" size="30" maxlength="50" placeholder="<?php echo $ator->no_ator->PlaceHolder ?>" value="<?php echo $ator->no_ator->EditValue ?>"<?php echo $ator->no_ator->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ator->no_ator->ViewAttributes() ?>>
<?php echo $ator->no_ator->ViewValue ?></span>
<input type="hidden" data-field="x_no_ator" name="x<?php echo $ator_grid->RowIndex ?>_no_ator" id="x<?php echo $ator_grid->RowIndex ?>_no_ator" value="<?php echo ew_HtmlEncode($ator->no_ator->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_ator" name="o<?php echo $ator_grid->RowIndex ?>_no_ator" id="o<?php echo $ator_grid->RowIndex ?>_no_ator" value="<?php echo ew_HtmlEncode($ator->no_ator->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ator->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($ator->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $ator_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $ator->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ator_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ator->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ator->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ator_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ator->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ator->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $ator->ic_ativo->ViewAttributes() ?>>
<?php echo $ator->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ator_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ator->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $ator_grid->RowIndex ?>_ic_ativo" id="o<?php echo $ator_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ator->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ator_grid->ListOptions->Render("body", "right", $ator_grid->RowCnt);
?>
<script type="text/javascript">
fatorgrid.UpdateOpts(<?php echo $ator_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($ator->CurrentMode == "add" || $ator->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $ator_grid->FormKeyCountName ?>" id="<?php echo $ator_grid->FormKeyCountName ?>" value="<?php echo $ator_grid->KeyCount ?>">
<?php echo $ator_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ator->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $ator_grid->FormKeyCountName ?>" id="<?php echo $ator_grid->FormKeyCountName ?>" value="<?php echo $ator_grid->KeyCount ?>">
<?php echo $ator_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ator->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fatorgrid">
</div>
<?php

// Close recordset
if ($ator_grid->Recordset)
	$ator_grid->Recordset->Close();
?>
<?php if ($ator_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($ator_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($ator->Export == "") { ?>
<script type="text/javascript">
fatorgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$ator_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$ator_grid->Page_Terminate();
?>
