<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($divisao_grid)) $divisao_grid = new cdivisao_grid();

// Page init
$divisao_grid->Page_Init();

// Page main
$divisao_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$divisao_grid->Page_Render();
?>
<?php if ($divisao->Export == "") { ?>
<script type="text/javascript">

// Page object
var divisao_grid = new ew_Page("divisao_grid");
divisao_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = divisao_grid.PageID; // For backward compatibility

// Form object
var fdivisaogrid = new ew_Form("fdivisaogrid");
fdivisaogrid.FormKeyCountName = '<?php echo $divisao_grid->FormKeyCountName ?>';

// Validate form
fdivisaogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_divisao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($divisao->no_divisao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($divisao->ic_ativo->FldCaption()) ?>");

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
fdivisaogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_divisao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
fdivisaogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdivisaogrid.ValidateRequired = true;
<?php } else { ?>
fdivisaogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($divisao->getCurrentMasterTable() == "" && $divisao_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $divisao_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($divisao->CurrentAction == "gridadd") {
	if ($divisao->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$divisao_grid->TotalRecs = $divisao->SelectRecordCount();
			$divisao_grid->Recordset = $divisao_grid->LoadRecordset($divisao_grid->StartRec-1, $divisao_grid->DisplayRecs);
		} else {
			if ($divisao_grid->Recordset = $divisao_grid->LoadRecordset())
				$divisao_grid->TotalRecs = $divisao_grid->Recordset->RecordCount();
		}
		$divisao_grid->StartRec = 1;
		$divisao_grid->DisplayRecs = $divisao_grid->TotalRecs;
	} else {
		$divisao->CurrentFilter = "0=1";
		$divisao_grid->StartRec = 1;
		$divisao_grid->DisplayRecs = $divisao->GridAddRowCount;
	}
	$divisao_grid->TotalRecs = $divisao_grid->DisplayRecs;
	$divisao_grid->StopRec = $divisao_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$divisao_grid->TotalRecs = $divisao->SelectRecordCount();
	} else {
		if ($divisao_grid->Recordset = $divisao_grid->LoadRecordset())
			$divisao_grid->TotalRecs = $divisao_grid->Recordset->RecordCount();
	}
	$divisao_grid->StartRec = 1;
	$divisao_grid->DisplayRecs = $divisao_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$divisao_grid->Recordset = $divisao_grid->LoadRecordset($divisao_grid->StartRec-1, $divisao_grid->DisplayRecs);
}
$divisao_grid->RenderOtherOptions();
?>
<?php $divisao_grid->ShowPageHeader(); ?>
<?php
$divisao_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fdivisaogrid" class="ewForm form-horizontal">
<div id="gmp_divisao" class="ewGridMiddlePanel">
<table id="tbl_divisaogrid" class="ewTable ewTableSeparate">
<?php echo $divisao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$divisao_grid->RenderListOptions();

// Render list options (header, left)
$divisao_grid->ListOptions->Render("header", "left");
?>
<?php if ($divisao->no_divisao->Visible) { // no_divisao ?>
	<?php if ($divisao->SortUrl($divisao->no_divisao) == "") { ?>
		<td><div id="elh_divisao_no_divisao" class="divisao_no_divisao"><div class="ewTableHeaderCaption"><?php echo $divisao->no_divisao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_divisao_no_divisao" class="divisao_no_divisao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $divisao->no_divisao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($divisao->no_divisao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($divisao->no_divisao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($divisao->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($divisao->SortUrl($divisao->ic_ativo) == "") { ?>
		<td><div id="elh_divisao_ic_ativo" class="divisao_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $divisao->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_divisao_ic_ativo" class="divisao_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $divisao->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($divisao->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($divisao->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$divisao_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$divisao_grid->StartRec = 1;
$divisao_grid->StopRec = $divisao_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($divisao_grid->FormKeyCountName) && ($divisao->CurrentAction == "gridadd" || $divisao->CurrentAction == "gridedit" || $divisao->CurrentAction == "F")) {
		$divisao_grid->KeyCount = $objForm->GetValue($divisao_grid->FormKeyCountName);
		$divisao_grid->StopRec = $divisao_grid->StartRec + $divisao_grid->KeyCount - 1;
	}
}
$divisao_grid->RecCnt = $divisao_grid->StartRec - 1;
if ($divisao_grid->Recordset && !$divisao_grid->Recordset->EOF) {
	$divisao_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $divisao_grid->StartRec > 1)
		$divisao_grid->Recordset->Move($divisao_grid->StartRec - 1);
} elseif (!$divisao->AllowAddDeleteRow && $divisao_grid->StopRec == 0) {
	$divisao_grid->StopRec = $divisao->GridAddRowCount;
}

// Initialize aggregate
$divisao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$divisao->ResetAttrs();
$divisao_grid->RenderRow();
if ($divisao->CurrentAction == "gridadd")
	$divisao_grid->RowIndex = 0;
if ($divisao->CurrentAction == "gridedit")
	$divisao_grid->RowIndex = 0;
while ($divisao_grid->RecCnt < $divisao_grid->StopRec) {
	$divisao_grid->RecCnt++;
	if (intval($divisao_grid->RecCnt) >= intval($divisao_grid->StartRec)) {
		$divisao_grid->RowCnt++;
		if ($divisao->CurrentAction == "gridadd" || $divisao->CurrentAction == "gridedit" || $divisao->CurrentAction == "F") {
			$divisao_grid->RowIndex++;
			$objForm->Index = $divisao_grid->RowIndex;
			if ($objForm->HasValue($divisao_grid->FormActionName))
				$divisao_grid->RowAction = strval($objForm->GetValue($divisao_grid->FormActionName));
			elseif ($divisao->CurrentAction == "gridadd")
				$divisao_grid->RowAction = "insert";
			else
				$divisao_grid->RowAction = "";
		}

		// Set up key count
		$divisao_grid->KeyCount = $divisao_grid->RowIndex;

		// Init row class and style
		$divisao->ResetAttrs();
		$divisao->CssClass = "";
		if ($divisao->CurrentAction == "gridadd") {
			if ($divisao->CurrentMode == "copy") {
				$divisao_grid->LoadRowValues($divisao_grid->Recordset); // Load row values
				$divisao_grid->SetRecordKey($divisao_grid->RowOldKey, $divisao_grid->Recordset); // Set old record key
			} else {
				$divisao_grid->LoadDefaultValues(); // Load default values
				$divisao_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$divisao_grid->LoadRowValues($divisao_grid->Recordset); // Load row values
		}
		$divisao->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($divisao->CurrentAction == "gridadd") // Grid add
			$divisao->RowType = EW_ROWTYPE_ADD; // Render add
		if ($divisao->CurrentAction == "gridadd" && $divisao->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$divisao_grid->RestoreCurrentRowFormValues($divisao_grid->RowIndex); // Restore form values
		if ($divisao->CurrentAction == "gridedit") { // Grid edit
			if ($divisao->EventCancelled) {
				$divisao_grid->RestoreCurrentRowFormValues($divisao_grid->RowIndex); // Restore form values
			}
			if ($divisao_grid->RowAction == "insert")
				$divisao->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$divisao->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($divisao->CurrentAction == "gridedit" && ($divisao->RowType == EW_ROWTYPE_EDIT || $divisao->RowType == EW_ROWTYPE_ADD) && $divisao->EventCancelled) // Update failed
			$divisao_grid->RestoreCurrentRowFormValues($divisao_grid->RowIndex); // Restore form values
		if ($divisao->RowType == EW_ROWTYPE_EDIT) // Edit row
			$divisao_grid->EditRowCnt++;
		if ($divisao->CurrentAction == "F") // Confirm row
			$divisao_grid->RestoreCurrentRowFormValues($divisao_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$divisao->RowAttrs = array_merge($divisao->RowAttrs, array('data-rowindex'=>$divisao_grid->RowCnt, 'id'=>'r' . $divisao_grid->RowCnt . '_divisao', 'data-rowtype'=>$divisao->RowType));

		// Render row
		$divisao_grid->RenderRow();

		// Render list options
		$divisao_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($divisao_grid->RowAction <> "delete" && $divisao_grid->RowAction <> "insertdelete" && !($divisao_grid->RowAction == "insert" && $divisao->CurrentAction == "F" && $divisao_grid->EmptyRow())) {
?>
	<tr<?php echo $divisao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$divisao_grid->ListOptions->Render("body", "left", $divisao_grid->RowCnt);
?>
	<?php if ($divisao->no_divisao->Visible) { // no_divisao ?>
		<td<?php echo $divisao->no_divisao->CellAttributes() ?>>
<?php if ($divisao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $divisao_grid->RowCnt ?>_divisao_no_divisao" class="control-group divisao_no_divisao">
<input type="text" data-field="x_no_divisao" name="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" id="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" size="30" maxlength="75" placeholder="<?php echo $divisao->no_divisao->PlaceHolder ?>" value="<?php echo $divisao->no_divisao->EditValue ?>"<?php echo $divisao->no_divisao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_divisao" name="o<?php echo $divisao_grid->RowIndex ?>_no_divisao" id="o<?php echo $divisao_grid->RowIndex ?>_no_divisao" value="<?php echo ew_HtmlEncode($divisao->no_divisao->OldValue) ?>">
<?php } ?>
<?php if ($divisao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $divisao_grid->RowCnt ?>_divisao_no_divisao" class="control-group divisao_no_divisao">
<input type="text" data-field="x_no_divisao" name="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" id="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" size="30" maxlength="75" placeholder="<?php echo $divisao->no_divisao->PlaceHolder ?>" value="<?php echo $divisao->no_divisao->EditValue ?>"<?php echo $divisao->no_divisao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($divisao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $divisao->no_divisao->ViewAttributes() ?>>
<?php echo $divisao->no_divisao->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_divisao" name="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" id="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" value="<?php echo ew_HtmlEncode($divisao->no_divisao->FormValue) ?>">
<input type="hidden" data-field="x_no_divisao" name="o<?php echo $divisao_grid->RowIndex ?>_no_divisao" id="o<?php echo $divisao_grid->RowIndex ?>_no_divisao" value="<?php echo ew_HtmlEncode($divisao->no_divisao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $divisao_grid->PageObjName . "_row_" . $divisao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($divisao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_divisao" name="x<?php echo $divisao_grid->RowIndex ?>_nu_divisao" id="x<?php echo $divisao_grid->RowIndex ?>_nu_divisao" value="<?php echo ew_HtmlEncode($divisao->nu_divisao->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_divisao" name="o<?php echo $divisao_grid->RowIndex ?>_nu_divisao" id="o<?php echo $divisao_grid->RowIndex ?>_nu_divisao" value="<?php echo ew_HtmlEncode($divisao->nu_divisao->OldValue) ?>">
<?php } ?>
<?php if ($divisao->RowType == EW_ROWTYPE_EDIT || $divisao->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_divisao" name="x<?php echo $divisao_grid->RowIndex ?>_nu_divisao" id="x<?php echo $divisao_grid->RowIndex ?>_nu_divisao" value="<?php echo ew_HtmlEncode($divisao->nu_divisao->CurrentValue) ?>">
<?php } ?>
	<?php if ($divisao->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $divisao->ic_ativo->CellAttributes() ?>>
<?php if ($divisao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $divisao_grid->RowCnt ?>_divisao_ic_ativo" class="control-group divisao_ic_ativo">
<div id="tp_x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $divisao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $divisao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($divisao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $divisao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $divisao->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="o<?php echo $divisao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($divisao->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($divisao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $divisao_grid->RowCnt ?>_divisao_ic_ativo" class="control-group divisao_ic_ativo">
<div id="tp_x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $divisao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $divisao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($divisao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $divisao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $divisao->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($divisao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $divisao->ic_ativo->ViewAttributes() ?>>
<?php echo $divisao->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($divisao->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="o<?php echo $divisao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($divisao->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $divisao_grid->PageObjName . "_row_" . $divisao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$divisao_grid->ListOptions->Render("body", "right", $divisao_grid->RowCnt);
?>
	</tr>
<?php if ($divisao->RowType == EW_ROWTYPE_ADD || $divisao->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdivisaogrid.UpdateOpts(<?php echo $divisao_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($divisao->CurrentAction <> "gridadd" || $divisao->CurrentMode == "copy")
		if (!$divisao_grid->Recordset->EOF) $divisao_grid->Recordset->MoveNext();
}
?>
<?php
	if ($divisao->CurrentMode == "add" || $divisao->CurrentMode == "copy" || $divisao->CurrentMode == "edit") {
		$divisao_grid->RowIndex = '$rowindex$';
		$divisao_grid->LoadDefaultValues();

		// Set row properties
		$divisao->ResetAttrs();
		$divisao->RowAttrs = array_merge($divisao->RowAttrs, array('data-rowindex'=>$divisao_grid->RowIndex, 'id'=>'r0_divisao', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($divisao->RowAttrs["class"], "ewTemplate");
		$divisao->RowType = EW_ROWTYPE_ADD;

		// Render row
		$divisao_grid->RenderRow();

		// Render list options
		$divisao_grid->RenderListOptions();
		$divisao_grid->StartRowCnt = 0;
?>
	<tr<?php echo $divisao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$divisao_grid->ListOptions->Render("body", "left", $divisao_grid->RowIndex);
?>
	<?php if ($divisao->no_divisao->Visible) { // no_divisao ?>
		<td>
<?php if ($divisao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_divisao" name="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" id="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" size="30" maxlength="75" placeholder="<?php echo $divisao->no_divisao->PlaceHolder ?>" value="<?php echo $divisao->no_divisao->EditValue ?>"<?php echo $divisao->no_divisao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $divisao->no_divisao->ViewAttributes() ?>>
<?php echo $divisao->no_divisao->ViewValue ?></span>
<input type="hidden" data-field="x_no_divisao" name="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" id="x<?php echo $divisao_grid->RowIndex ?>_no_divisao" value="<?php echo ew_HtmlEncode($divisao->no_divisao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_divisao" name="o<?php echo $divisao_grid->RowIndex ?>_no_divisao" id="o<?php echo $divisao_grid->RowIndex ?>_no_divisao" value="<?php echo ew_HtmlEncode($divisao->no_divisao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($divisao->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($divisao->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $divisao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $divisao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($divisao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $divisao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $divisao->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $divisao->ic_ativo->ViewAttributes() ?>>
<?php echo $divisao->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $divisao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($divisao->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $divisao_grid->RowIndex ?>_ic_ativo" id="o<?php echo $divisao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($divisao->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$divisao_grid->ListOptions->Render("body", "right", $divisao_grid->RowCnt);
?>
<script type="text/javascript">
fdivisaogrid.UpdateOpts(<?php echo $divisao_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($divisao->CurrentMode == "add" || $divisao->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $divisao_grid->FormKeyCountName ?>" id="<?php echo $divisao_grid->FormKeyCountName ?>" value="<?php echo $divisao_grid->KeyCount ?>">
<?php echo $divisao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($divisao->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $divisao_grid->FormKeyCountName ?>" id="<?php echo $divisao_grid->FormKeyCountName ?>" value="<?php echo $divisao_grid->KeyCount ?>">
<?php echo $divisao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($divisao->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fdivisaogrid">
</div>
<?php

// Close recordset
if ($divisao_grid->Recordset)
	$divisao_grid->Recordset->Close();
?>
<?php if ($divisao_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($divisao_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($divisao->Export == "") { ?>
<script type="text/javascript">
fdivisaogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$divisao_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$divisao_grid->Page_Terminate();
?>
