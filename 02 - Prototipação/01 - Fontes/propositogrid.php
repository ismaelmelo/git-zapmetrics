<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($proposito_grid)) $proposito_grid = new cproposito_grid();

// Page init
$proposito_grid->Page_Init();

// Page main
$proposito_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$proposito_grid->Page_Render();
?>
<?php if ($proposito->Export == "") { ?>
<script type="text/javascript">

// Page object
var proposito_grid = new ew_Page("proposito_grid");
proposito_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = proposito_grid.PageID; // For backward compatibility

// Form object
var fpropositogrid = new ew_Form("fpropositogrid");
fpropositogrid.FormKeyCountName = '<?php echo $proposito_grid->FormKeyCountName ?>';

// Validate form
fpropositogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_proposito");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($proposito->no_proposito->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($proposito->ic_ativo->FldCaption()) ?>");

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
fpropositogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_proposito", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
fpropositogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpropositogrid.ValidateRequired = true;
<?php } else { ?>
fpropositogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($proposito->getCurrentMasterTable() == "" && $proposito_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $proposito_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($proposito->CurrentAction == "gridadd") {
	if ($proposito->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$proposito_grid->TotalRecs = $proposito->SelectRecordCount();
			$proposito_grid->Recordset = $proposito_grid->LoadRecordset($proposito_grid->StartRec-1, $proposito_grid->DisplayRecs);
		} else {
			if ($proposito_grid->Recordset = $proposito_grid->LoadRecordset())
				$proposito_grid->TotalRecs = $proposito_grid->Recordset->RecordCount();
		}
		$proposito_grid->StartRec = 1;
		$proposito_grid->DisplayRecs = $proposito_grid->TotalRecs;
	} else {
		$proposito->CurrentFilter = "0=1";
		$proposito_grid->StartRec = 1;
		$proposito_grid->DisplayRecs = $proposito->GridAddRowCount;
	}
	$proposito_grid->TotalRecs = $proposito_grid->DisplayRecs;
	$proposito_grid->StopRec = $proposito_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$proposito_grid->TotalRecs = $proposito->SelectRecordCount();
	} else {
		if ($proposito_grid->Recordset = $proposito_grid->LoadRecordset())
			$proposito_grid->TotalRecs = $proposito_grid->Recordset->RecordCount();
	}
	$proposito_grid->StartRec = 1;
	$proposito_grid->DisplayRecs = $proposito_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$proposito_grid->Recordset = $proposito_grid->LoadRecordset($proposito_grid->StartRec-1, $proposito_grid->DisplayRecs);
}
$proposito_grid->RenderOtherOptions();
?>
<?php $proposito_grid->ShowPageHeader(); ?>
<?php
$proposito_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fpropositogrid" class="ewForm form-horizontal">
<div id="gmp_proposito" class="ewGridMiddlePanel">
<table id="tbl_propositogrid" class="ewTable ewTableSeparate">
<?php echo $proposito->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$proposito_grid->RenderListOptions();

// Render list options (header, left)
$proposito_grid->ListOptions->Render("header", "left");
?>
<?php if ($proposito->no_proposito->Visible) { // no_proposito ?>
	<?php if ($proposito->SortUrl($proposito->no_proposito) == "") { ?>
		<td><div id="elh_proposito_no_proposito" class="proposito_no_proposito"><div class="ewTableHeaderCaption"><?php echo $proposito->no_proposito->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_proposito_no_proposito" class="proposito_no_proposito">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $proposito->no_proposito->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($proposito->no_proposito->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($proposito->no_proposito->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($proposito->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($proposito->SortUrl($proposito->ic_ativo) == "") { ?>
		<td><div id="elh_proposito_ic_ativo" class="proposito_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $proposito->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_proposito_ic_ativo" class="proposito_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $proposito->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($proposito->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($proposito->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$proposito_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$proposito_grid->StartRec = 1;
$proposito_grid->StopRec = $proposito_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($proposito_grid->FormKeyCountName) && ($proposito->CurrentAction == "gridadd" || $proposito->CurrentAction == "gridedit" || $proposito->CurrentAction == "F")) {
		$proposito_grid->KeyCount = $objForm->GetValue($proposito_grid->FormKeyCountName);
		$proposito_grid->StopRec = $proposito_grid->StartRec + $proposito_grid->KeyCount - 1;
	}
}
$proposito_grid->RecCnt = $proposito_grid->StartRec - 1;
if ($proposito_grid->Recordset && !$proposito_grid->Recordset->EOF) {
	$proposito_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $proposito_grid->StartRec > 1)
		$proposito_grid->Recordset->Move($proposito_grid->StartRec - 1);
} elseif (!$proposito->AllowAddDeleteRow && $proposito_grid->StopRec == 0) {
	$proposito_grid->StopRec = $proposito->GridAddRowCount;
}

// Initialize aggregate
$proposito->RowType = EW_ROWTYPE_AGGREGATEINIT;
$proposito->ResetAttrs();
$proposito_grid->RenderRow();
if ($proposito->CurrentAction == "gridadd")
	$proposito_grid->RowIndex = 0;
if ($proposito->CurrentAction == "gridedit")
	$proposito_grid->RowIndex = 0;
while ($proposito_grid->RecCnt < $proposito_grid->StopRec) {
	$proposito_grid->RecCnt++;
	if (intval($proposito_grid->RecCnt) >= intval($proposito_grid->StartRec)) {
		$proposito_grid->RowCnt++;
		if ($proposito->CurrentAction == "gridadd" || $proposito->CurrentAction == "gridedit" || $proposito->CurrentAction == "F") {
			$proposito_grid->RowIndex++;
			$objForm->Index = $proposito_grid->RowIndex;
			if ($objForm->HasValue($proposito_grid->FormActionName))
				$proposito_grid->RowAction = strval($objForm->GetValue($proposito_grid->FormActionName));
			elseif ($proposito->CurrentAction == "gridadd")
				$proposito_grid->RowAction = "insert";
			else
				$proposito_grid->RowAction = "";
		}

		// Set up key count
		$proposito_grid->KeyCount = $proposito_grid->RowIndex;

		// Init row class and style
		$proposito->ResetAttrs();
		$proposito->CssClass = "";
		if ($proposito->CurrentAction == "gridadd") {
			if ($proposito->CurrentMode == "copy") {
				$proposito_grid->LoadRowValues($proposito_grid->Recordset); // Load row values
				$proposito_grid->SetRecordKey($proposito_grid->RowOldKey, $proposito_grid->Recordset); // Set old record key
			} else {
				$proposito_grid->LoadDefaultValues(); // Load default values
				$proposito_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$proposito_grid->LoadRowValues($proposito_grid->Recordset); // Load row values
		}
		$proposito->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($proposito->CurrentAction == "gridadd") // Grid add
			$proposito->RowType = EW_ROWTYPE_ADD; // Render add
		if ($proposito->CurrentAction == "gridadd" && $proposito->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$proposito_grid->RestoreCurrentRowFormValues($proposito_grid->RowIndex); // Restore form values
		if ($proposito->CurrentAction == "gridedit") { // Grid edit
			if ($proposito->EventCancelled) {
				$proposito_grid->RestoreCurrentRowFormValues($proposito_grid->RowIndex); // Restore form values
			}
			if ($proposito_grid->RowAction == "insert")
				$proposito->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$proposito->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($proposito->CurrentAction == "gridedit" && ($proposito->RowType == EW_ROWTYPE_EDIT || $proposito->RowType == EW_ROWTYPE_ADD) && $proposito->EventCancelled) // Update failed
			$proposito_grid->RestoreCurrentRowFormValues($proposito_grid->RowIndex); // Restore form values
		if ($proposito->RowType == EW_ROWTYPE_EDIT) // Edit row
			$proposito_grid->EditRowCnt++;
		if ($proposito->CurrentAction == "F") // Confirm row
			$proposito_grid->RestoreCurrentRowFormValues($proposito_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$proposito->RowAttrs = array_merge($proposito->RowAttrs, array('data-rowindex'=>$proposito_grid->RowCnt, 'id'=>'r' . $proposito_grid->RowCnt . '_proposito', 'data-rowtype'=>$proposito->RowType));

		// Render row
		$proposito_grid->RenderRow();

		// Render list options
		$proposito_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($proposito_grid->RowAction <> "delete" && $proposito_grid->RowAction <> "insertdelete" && !($proposito_grid->RowAction == "insert" && $proposito->CurrentAction == "F" && $proposito_grid->EmptyRow())) {
?>
	<tr<?php echo $proposito->RowAttributes() ?>>
<?php

// Render list options (body, left)
$proposito_grid->ListOptions->Render("body", "left", $proposito_grid->RowCnt);
?>
	<?php if ($proposito->no_proposito->Visible) { // no_proposito ?>
		<td<?php echo $proposito->no_proposito->CellAttributes() ?>>
<?php if ($proposito->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $proposito_grid->RowCnt ?>_proposito_no_proposito" class="control-group proposito_no_proposito">
<input type="text" data-field="x_no_proposito" name="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" id="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" size="75" maxlength="100" placeholder="<?php echo $proposito->no_proposito->PlaceHolder ?>" value="<?php echo $proposito->no_proposito->EditValue ?>"<?php echo $proposito->no_proposito->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_proposito" name="o<?php echo $proposito_grid->RowIndex ?>_no_proposito" id="o<?php echo $proposito_grid->RowIndex ?>_no_proposito" value="<?php echo ew_HtmlEncode($proposito->no_proposito->OldValue) ?>">
<?php } ?>
<?php if ($proposito->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $proposito_grid->RowCnt ?>_proposito_no_proposito" class="control-group proposito_no_proposito">
<input type="text" data-field="x_no_proposito" name="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" id="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" size="75" maxlength="100" placeholder="<?php echo $proposito->no_proposito->PlaceHolder ?>" value="<?php echo $proposito->no_proposito->EditValue ?>"<?php echo $proposito->no_proposito->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($proposito->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $proposito->no_proposito->ViewAttributes() ?>>
<?php echo $proposito->no_proposito->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_proposito" name="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" id="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" value="<?php echo ew_HtmlEncode($proposito->no_proposito->FormValue) ?>">
<input type="hidden" data-field="x_no_proposito" name="o<?php echo $proposito_grid->RowIndex ?>_no_proposito" id="o<?php echo $proposito_grid->RowIndex ?>_no_proposito" value="<?php echo ew_HtmlEncode($proposito->no_proposito->OldValue) ?>">
<?php } ?>
<a id="<?php echo $proposito_grid->PageObjName . "_row_" . $proposito_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($proposito->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_proposito" name="x<?php echo $proposito_grid->RowIndex ?>_nu_proposito" id="x<?php echo $proposito_grid->RowIndex ?>_nu_proposito" value="<?php echo ew_HtmlEncode($proposito->nu_proposito->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_proposito" name="o<?php echo $proposito_grid->RowIndex ?>_nu_proposito" id="o<?php echo $proposito_grid->RowIndex ?>_nu_proposito" value="<?php echo ew_HtmlEncode($proposito->nu_proposito->OldValue) ?>">
<?php } ?>
<?php if ($proposito->RowType == EW_ROWTYPE_EDIT || $proposito->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_proposito" name="x<?php echo $proposito_grid->RowIndex ?>_nu_proposito" id="x<?php echo $proposito_grid->RowIndex ?>_nu_proposito" value="<?php echo ew_HtmlEncode($proposito->nu_proposito->CurrentValue) ?>">
<?php } ?>
	<?php if ($proposito->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $proposito->ic_ativo->CellAttributes() ?>>
<?php if ($proposito->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $proposito_grid->RowCnt ?>_proposito_ic_ativo" class="control-group proposito_ic_ativo">
<div id="tp_x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $proposito->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $proposito->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($proposito->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $proposito->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $proposito->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="o<?php echo $proposito_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($proposito->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($proposito->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $proposito_grid->RowCnt ?>_proposito_ic_ativo" class="control-group proposito_ic_ativo">
<div id="tp_x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $proposito->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $proposito->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($proposito->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $proposito->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $proposito->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($proposito->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $proposito->ic_ativo->ViewAttributes() ?>>
<?php echo $proposito->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($proposito->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="o<?php echo $proposito_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($proposito->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $proposito_grid->PageObjName . "_row_" . $proposito_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$proposito_grid->ListOptions->Render("body", "right", $proposito_grid->RowCnt);
?>
	</tr>
<?php if ($proposito->RowType == EW_ROWTYPE_ADD || $proposito->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpropositogrid.UpdateOpts(<?php echo $proposito_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($proposito->CurrentAction <> "gridadd" || $proposito->CurrentMode == "copy")
		if (!$proposito_grid->Recordset->EOF) $proposito_grid->Recordset->MoveNext();
}
?>
<?php
	if ($proposito->CurrentMode == "add" || $proposito->CurrentMode == "copy" || $proposito->CurrentMode == "edit") {
		$proposito_grid->RowIndex = '$rowindex$';
		$proposito_grid->LoadDefaultValues();

		// Set row properties
		$proposito->ResetAttrs();
		$proposito->RowAttrs = array_merge($proposito->RowAttrs, array('data-rowindex'=>$proposito_grid->RowIndex, 'id'=>'r0_proposito', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($proposito->RowAttrs["class"], "ewTemplate");
		$proposito->RowType = EW_ROWTYPE_ADD;

		// Render row
		$proposito_grid->RenderRow();

		// Render list options
		$proposito_grid->RenderListOptions();
		$proposito_grid->StartRowCnt = 0;
?>
	<tr<?php echo $proposito->RowAttributes() ?>>
<?php

// Render list options (body, left)
$proposito_grid->ListOptions->Render("body", "left", $proposito_grid->RowIndex);
?>
	<?php if ($proposito->no_proposito->Visible) { // no_proposito ?>
		<td>
<?php if ($proposito->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_proposito" name="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" id="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" size="75" maxlength="100" placeholder="<?php echo $proposito->no_proposito->PlaceHolder ?>" value="<?php echo $proposito->no_proposito->EditValue ?>"<?php echo $proposito->no_proposito->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $proposito->no_proposito->ViewAttributes() ?>>
<?php echo $proposito->no_proposito->ViewValue ?></span>
<input type="hidden" data-field="x_no_proposito" name="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" id="x<?php echo $proposito_grid->RowIndex ?>_no_proposito" value="<?php echo ew_HtmlEncode($proposito->no_proposito->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_proposito" name="o<?php echo $proposito_grid->RowIndex ?>_no_proposito" id="o<?php echo $proposito_grid->RowIndex ?>_no_proposito" value="<?php echo ew_HtmlEncode($proposito->no_proposito->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($proposito->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($proposito->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $proposito->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $proposito->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($proposito->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $proposito->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $proposito->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $proposito->ic_ativo->ViewAttributes() ?>>
<?php echo $proposito->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="x<?php echo $proposito_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($proposito->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $proposito_grid->RowIndex ?>_ic_ativo" id="o<?php echo $proposito_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($proposito->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$proposito_grid->ListOptions->Render("body", "right", $proposito_grid->RowCnt);
?>
<script type="text/javascript">
fpropositogrid.UpdateOpts(<?php echo $proposito_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($proposito->CurrentMode == "add" || $proposito->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $proposito_grid->FormKeyCountName ?>" id="<?php echo $proposito_grid->FormKeyCountName ?>" value="<?php echo $proposito_grid->KeyCount ?>">
<?php echo $proposito_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($proposito->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $proposito_grid->FormKeyCountName ?>" id="<?php echo $proposito_grid->FormKeyCountName ?>" value="<?php echo $proposito_grid->KeyCount ?>">
<?php echo $proposito_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($proposito->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpropositogrid">
</div>
<?php

// Close recordset
if ($proposito_grid->Recordset)
	$proposito_grid->Recordset->Close();
?>
<?php if ($proposito_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($proposito_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($proposito->Export == "") { ?>
<script type="text/javascript">
fpropositogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$proposito_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$proposito_grid->Page_Terminate();
?>
