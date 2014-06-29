<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($tpcontagem_grid)) $tpcontagem_grid = new ctpcontagem_grid();

// Page init
$tpcontagem_grid->Page_Init();

// Page main
$tpcontagem_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpcontagem_grid->Page_Render();
?>
<?php if ($tpcontagem->Export == "") { ?>
<script type="text/javascript">

// Page object
var tpcontagem_grid = new ew_Page("tpcontagem_grid");
tpcontagem_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = tpcontagem_grid.PageID; // For backward compatibility

// Form object
var ftpcontagemgrid = new ew_Form("ftpcontagemgrid");
ftpcontagemgrid.FormKeyCountName = '<?php echo $tpcontagem_grid->FormKeyCountName ?>';

// Validate form
ftpcontagemgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_tpContagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpcontagem->no_tpContagem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpcontagem->ic_ativo->FldCaption()) ?>");

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
ftpcontagemgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_tpContagem", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
ftpcontagemgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpcontagemgrid.ValidateRequired = true;
<?php } else { ?>
ftpcontagemgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($tpcontagem->getCurrentMasterTable() == "" && $tpcontagem_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $tpcontagem_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($tpcontagem->CurrentAction == "gridadd") {
	if ($tpcontagem->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$tpcontagem_grid->TotalRecs = $tpcontagem->SelectRecordCount();
			$tpcontagem_grid->Recordset = $tpcontagem_grid->LoadRecordset($tpcontagem_grid->StartRec-1, $tpcontagem_grid->DisplayRecs);
		} else {
			if ($tpcontagem_grid->Recordset = $tpcontagem_grid->LoadRecordset())
				$tpcontagem_grid->TotalRecs = $tpcontagem_grid->Recordset->RecordCount();
		}
		$tpcontagem_grid->StartRec = 1;
		$tpcontagem_grid->DisplayRecs = $tpcontagem_grid->TotalRecs;
	} else {
		$tpcontagem->CurrentFilter = "0=1";
		$tpcontagem_grid->StartRec = 1;
		$tpcontagem_grid->DisplayRecs = $tpcontagem->GridAddRowCount;
	}
	$tpcontagem_grid->TotalRecs = $tpcontagem_grid->DisplayRecs;
	$tpcontagem_grid->StopRec = $tpcontagem_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tpcontagem_grid->TotalRecs = $tpcontagem->SelectRecordCount();
	} else {
		if ($tpcontagem_grid->Recordset = $tpcontagem_grid->LoadRecordset())
			$tpcontagem_grid->TotalRecs = $tpcontagem_grid->Recordset->RecordCount();
	}
	$tpcontagem_grid->StartRec = 1;
	$tpcontagem_grid->DisplayRecs = $tpcontagem_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$tpcontagem_grid->Recordset = $tpcontagem_grid->LoadRecordset($tpcontagem_grid->StartRec-1, $tpcontagem_grid->DisplayRecs);
}
$tpcontagem_grid->RenderOtherOptions();
?>
<?php $tpcontagem_grid->ShowPageHeader(); ?>
<?php
$tpcontagem_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="ftpcontagemgrid" class="ewForm form-horizontal">
<div id="gmp_tpcontagem" class="ewGridMiddlePanel">
<table id="tbl_tpcontagemgrid" class="ewTable ewTableSeparate">
<?php echo $tpcontagem->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tpcontagem_grid->RenderListOptions();

// Render list options (header, left)
$tpcontagem_grid->ListOptions->Render("header", "left");
?>
<?php if ($tpcontagem->no_tpContagem->Visible) { // no_tpContagem ?>
	<?php if ($tpcontagem->SortUrl($tpcontagem->no_tpContagem) == "") { ?>
		<td><div id="elh_tpcontagem_no_tpContagem" class="tpcontagem_no_tpContagem"><div class="ewTableHeaderCaption"><?php echo $tpcontagem->no_tpContagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpcontagem_no_tpContagem" class="tpcontagem_no_tpContagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpcontagem->no_tpContagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpcontagem->no_tpContagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpcontagem->no_tpContagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tpcontagem->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($tpcontagem->SortUrl($tpcontagem->ic_ativo) == "") { ?>
		<td><div id="elh_tpcontagem_ic_ativo" class="tpcontagem_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $tpcontagem->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpcontagem_ic_ativo" class="tpcontagem_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpcontagem->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpcontagem->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpcontagem->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tpcontagem_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$tpcontagem_grid->StartRec = 1;
$tpcontagem_grid->StopRec = $tpcontagem_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($tpcontagem_grid->FormKeyCountName) && ($tpcontagem->CurrentAction == "gridadd" || $tpcontagem->CurrentAction == "gridedit" || $tpcontagem->CurrentAction == "F")) {
		$tpcontagem_grid->KeyCount = $objForm->GetValue($tpcontagem_grid->FormKeyCountName);
		$tpcontagem_grid->StopRec = $tpcontagem_grid->StartRec + $tpcontagem_grid->KeyCount - 1;
	}
}
$tpcontagem_grid->RecCnt = $tpcontagem_grid->StartRec - 1;
if ($tpcontagem_grid->Recordset && !$tpcontagem_grid->Recordset->EOF) {
	$tpcontagem_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $tpcontagem_grid->StartRec > 1)
		$tpcontagem_grid->Recordset->Move($tpcontagem_grid->StartRec - 1);
} elseif (!$tpcontagem->AllowAddDeleteRow && $tpcontagem_grid->StopRec == 0) {
	$tpcontagem_grid->StopRec = $tpcontagem->GridAddRowCount;
}

// Initialize aggregate
$tpcontagem->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tpcontagem->ResetAttrs();
$tpcontagem_grid->RenderRow();
if ($tpcontagem->CurrentAction == "gridadd")
	$tpcontagem_grid->RowIndex = 0;
if ($tpcontagem->CurrentAction == "gridedit")
	$tpcontagem_grid->RowIndex = 0;
while ($tpcontagem_grid->RecCnt < $tpcontagem_grid->StopRec) {
	$tpcontagem_grid->RecCnt++;
	if (intval($tpcontagem_grid->RecCnt) >= intval($tpcontagem_grid->StartRec)) {
		$tpcontagem_grid->RowCnt++;
		if ($tpcontagem->CurrentAction == "gridadd" || $tpcontagem->CurrentAction == "gridedit" || $tpcontagem->CurrentAction == "F") {
			$tpcontagem_grid->RowIndex++;
			$objForm->Index = $tpcontagem_grid->RowIndex;
			if ($objForm->HasValue($tpcontagem_grid->FormActionName))
				$tpcontagem_grid->RowAction = strval($objForm->GetValue($tpcontagem_grid->FormActionName));
			elseif ($tpcontagem->CurrentAction == "gridadd")
				$tpcontagem_grid->RowAction = "insert";
			else
				$tpcontagem_grid->RowAction = "";
		}

		// Set up key count
		$tpcontagem_grid->KeyCount = $tpcontagem_grid->RowIndex;

		// Init row class and style
		$tpcontagem->ResetAttrs();
		$tpcontagem->CssClass = "";
		if ($tpcontagem->CurrentAction == "gridadd") {
			if ($tpcontagem->CurrentMode == "copy") {
				$tpcontagem_grid->LoadRowValues($tpcontagem_grid->Recordset); // Load row values
				$tpcontagem_grid->SetRecordKey($tpcontagem_grid->RowOldKey, $tpcontagem_grid->Recordset); // Set old record key
			} else {
				$tpcontagem_grid->LoadDefaultValues(); // Load default values
				$tpcontagem_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$tpcontagem_grid->LoadRowValues($tpcontagem_grid->Recordset); // Load row values
		}
		$tpcontagem->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($tpcontagem->CurrentAction == "gridadd") // Grid add
			$tpcontagem->RowType = EW_ROWTYPE_ADD; // Render add
		if ($tpcontagem->CurrentAction == "gridadd" && $tpcontagem->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$tpcontagem_grid->RestoreCurrentRowFormValues($tpcontagem_grid->RowIndex); // Restore form values
		if ($tpcontagem->CurrentAction == "gridedit") { // Grid edit
			if ($tpcontagem->EventCancelled) {
				$tpcontagem_grid->RestoreCurrentRowFormValues($tpcontagem_grid->RowIndex); // Restore form values
			}
			if ($tpcontagem_grid->RowAction == "insert")
				$tpcontagem->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$tpcontagem->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($tpcontagem->CurrentAction == "gridedit" && ($tpcontagem->RowType == EW_ROWTYPE_EDIT || $tpcontagem->RowType == EW_ROWTYPE_ADD) && $tpcontagem->EventCancelled) // Update failed
			$tpcontagem_grid->RestoreCurrentRowFormValues($tpcontagem_grid->RowIndex); // Restore form values
		if ($tpcontagem->RowType == EW_ROWTYPE_EDIT) // Edit row
			$tpcontagem_grid->EditRowCnt++;
		if ($tpcontagem->CurrentAction == "F") // Confirm row
			$tpcontagem_grid->RestoreCurrentRowFormValues($tpcontagem_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$tpcontagem->RowAttrs = array_merge($tpcontagem->RowAttrs, array('data-rowindex'=>$tpcontagem_grid->RowCnt, 'id'=>'r' . $tpcontagem_grid->RowCnt . '_tpcontagem', 'data-rowtype'=>$tpcontagem->RowType));

		// Render row
		$tpcontagem_grid->RenderRow();

		// Render list options
		$tpcontagem_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($tpcontagem_grid->RowAction <> "delete" && $tpcontagem_grid->RowAction <> "insertdelete" && !($tpcontagem_grid->RowAction == "insert" && $tpcontagem->CurrentAction == "F" && $tpcontagem_grid->EmptyRow())) {
?>
	<tr<?php echo $tpcontagem->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tpcontagem_grid->ListOptions->Render("body", "left", $tpcontagem_grid->RowCnt);
?>
	<?php if ($tpcontagem->no_tpContagem->Visible) { // no_tpContagem ?>
		<td<?php echo $tpcontagem->no_tpContagem->CellAttributes() ?>>
<?php if ($tpcontagem->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpcontagem_grid->RowCnt ?>_tpcontagem_no_tpContagem" class="control-group tpcontagem_no_tpContagem">
<input type="text" data-field="x_no_tpContagem" name="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" id="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" size="30" maxlength="50" placeholder="<?php echo $tpcontagem->no_tpContagem->PlaceHolder ?>" value="<?php echo $tpcontagem->no_tpContagem->EditValue ?>"<?php echo $tpcontagem->no_tpContagem->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_tpContagem" name="o<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" id="o<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" value="<?php echo ew_HtmlEncode($tpcontagem->no_tpContagem->OldValue) ?>">
<?php } ?>
<?php if ($tpcontagem->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpcontagem_grid->RowCnt ?>_tpcontagem_no_tpContagem" class="control-group tpcontagem_no_tpContagem">
<input type="text" data-field="x_no_tpContagem" name="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" id="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" size="30" maxlength="50" placeholder="<?php echo $tpcontagem->no_tpContagem->PlaceHolder ?>" value="<?php echo $tpcontagem->no_tpContagem->EditValue ?>"<?php echo $tpcontagem->no_tpContagem->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($tpcontagem->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpcontagem->no_tpContagem->ViewAttributes() ?>>
<?php echo $tpcontagem->no_tpContagem->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_tpContagem" name="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" id="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" value="<?php echo ew_HtmlEncode($tpcontagem->no_tpContagem->FormValue) ?>">
<input type="hidden" data-field="x_no_tpContagem" name="o<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" id="o<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" value="<?php echo ew_HtmlEncode($tpcontagem->no_tpContagem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpcontagem_grid->PageObjName . "_row_" . $tpcontagem_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($tpcontagem->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_tpContagem" name="x<?php echo $tpcontagem_grid->RowIndex ?>_nu_tpContagem" id="x<?php echo $tpcontagem_grid->RowIndex ?>_nu_tpContagem" value="<?php echo ew_HtmlEncode($tpcontagem->nu_tpContagem->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_tpContagem" name="o<?php echo $tpcontagem_grid->RowIndex ?>_nu_tpContagem" id="o<?php echo $tpcontagem_grid->RowIndex ?>_nu_tpContagem" value="<?php echo ew_HtmlEncode($tpcontagem->nu_tpContagem->OldValue) ?>">
<?php } ?>
<?php if ($tpcontagem->RowType == EW_ROWTYPE_EDIT || $tpcontagem->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_tpContagem" name="x<?php echo $tpcontagem_grid->RowIndex ?>_nu_tpContagem" id="x<?php echo $tpcontagem_grid->RowIndex ?>_nu_tpContagem" value="<?php echo ew_HtmlEncode($tpcontagem->nu_tpContagem->CurrentValue) ?>">
<?php } ?>
	<?php if ($tpcontagem->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $tpcontagem->ic_ativo->CellAttributes() ?>>
<?php if ($tpcontagem->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpcontagem_grid->RowCnt ?>_tpcontagem_ic_ativo" class="control-group tpcontagem_ic_ativo">
<div id="tp_x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $tpcontagem->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpcontagem->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpcontagem->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpcontagem->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpcontagem->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="o<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpcontagem->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($tpcontagem->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpcontagem_grid->RowCnt ?>_tpcontagem_ic_ativo" class="control-group tpcontagem_ic_ativo">
<div id="tp_x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $tpcontagem->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpcontagem->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpcontagem->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpcontagem->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpcontagem->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($tpcontagem->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpcontagem->ic_ativo->ViewAttributes() ?>>
<?php echo $tpcontagem->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpcontagem->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="o<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpcontagem->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpcontagem_grid->PageObjName . "_row_" . $tpcontagem_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tpcontagem_grid->ListOptions->Render("body", "right", $tpcontagem_grid->RowCnt);
?>
	</tr>
<?php if ($tpcontagem->RowType == EW_ROWTYPE_ADD || $tpcontagem->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftpcontagemgrid.UpdateOpts(<?php echo $tpcontagem_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($tpcontagem->CurrentAction <> "gridadd" || $tpcontagem->CurrentMode == "copy")
		if (!$tpcontagem_grid->Recordset->EOF) $tpcontagem_grid->Recordset->MoveNext();
}
?>
<?php
	if ($tpcontagem->CurrentMode == "add" || $tpcontagem->CurrentMode == "copy" || $tpcontagem->CurrentMode == "edit") {
		$tpcontagem_grid->RowIndex = '$rowindex$';
		$tpcontagem_grid->LoadDefaultValues();

		// Set row properties
		$tpcontagem->ResetAttrs();
		$tpcontagem->RowAttrs = array_merge($tpcontagem->RowAttrs, array('data-rowindex'=>$tpcontagem_grid->RowIndex, 'id'=>'r0_tpcontagem', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($tpcontagem->RowAttrs["class"], "ewTemplate");
		$tpcontagem->RowType = EW_ROWTYPE_ADD;

		// Render row
		$tpcontagem_grid->RenderRow();

		// Render list options
		$tpcontagem_grid->RenderListOptions();
		$tpcontagem_grid->StartRowCnt = 0;
?>
	<tr<?php echo $tpcontagem->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tpcontagem_grid->ListOptions->Render("body", "left", $tpcontagem_grid->RowIndex);
?>
	<?php if ($tpcontagem->no_tpContagem->Visible) { // no_tpContagem ?>
		<td>
<?php if ($tpcontagem->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_tpContagem" name="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" id="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" size="30" maxlength="50" placeholder="<?php echo $tpcontagem->no_tpContagem->PlaceHolder ?>" value="<?php echo $tpcontagem->no_tpContagem->EditValue ?>"<?php echo $tpcontagem->no_tpContagem->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $tpcontagem->no_tpContagem->ViewAttributes() ?>>
<?php echo $tpcontagem->no_tpContagem->ViewValue ?></span>
<input type="hidden" data-field="x_no_tpContagem" name="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" id="x<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" value="<?php echo ew_HtmlEncode($tpcontagem->no_tpContagem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_tpContagem" name="o<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" id="o<?php echo $tpcontagem_grid->RowIndex ?>_no_tpContagem" value="<?php echo ew_HtmlEncode($tpcontagem->no_tpContagem->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($tpcontagem->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($tpcontagem->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $tpcontagem->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpcontagem->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpcontagem->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpcontagem->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpcontagem->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $tpcontagem->ic_ativo->ViewAttributes() ?>>
<?php echo $tpcontagem->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpcontagem->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" id="o<?php echo $tpcontagem_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpcontagem->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tpcontagem_grid->ListOptions->Render("body", "right", $tpcontagem_grid->RowCnt);
?>
<script type="text/javascript">
ftpcontagemgrid.UpdateOpts(<?php echo $tpcontagem_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($tpcontagem->CurrentMode == "add" || $tpcontagem->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $tpcontagem_grid->FormKeyCountName ?>" id="<?php echo $tpcontagem_grid->FormKeyCountName ?>" value="<?php echo $tpcontagem_grid->KeyCount ?>">
<?php echo $tpcontagem_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tpcontagem->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $tpcontagem_grid->FormKeyCountName ?>" id="<?php echo $tpcontagem_grid->FormKeyCountName ?>" value="<?php echo $tpcontagem_grid->KeyCount ?>">
<?php echo $tpcontagem_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tpcontagem->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ftpcontagemgrid">
</div>
<?php

// Close recordset
if ($tpcontagem_grid->Recordset)
	$tpcontagem_grid->Recordset->Close();
?>
<?php if ($tpcontagem_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($tpcontagem_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($tpcontagem->Export == "") { ?>
<script type="text/javascript">
ftpcontagemgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$tpcontagem_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$tpcontagem_grid->Page_Terminate();
?>
