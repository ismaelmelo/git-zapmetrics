<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($tpElemento_grid)) $tpElemento_grid = new ctpElemento_grid();

// Page init
$tpElemento_grid->Page_Init();

// Page main
$tpElemento_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpElemento_grid->Page_Render();
?>
<?php if ($tpElemento->Export == "") { ?>
<script type="text/javascript">

// Page object
var tpElemento_grid = new ew_Page("tpElemento_grid");
tpElemento_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = tpElemento_grid.PageID; // For backward compatibility

// Form object
var ftpElementogrid = new ew_Form("ftpElementogrid");
ftpElementogrid.FormKeyCountName = '<?php echo $tpElemento_grid->FormKeyCountName ?>';

// Validate form
ftpElementogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_tpElemento");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpElemento->no_tpElemento->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_funcional");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpElemento->ic_funcional->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpElemento->ic_ativo->FldCaption()) ?>");

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
ftpElementogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_tpElemento", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_funcional", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
ftpElementogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpElementogrid.ValidateRequired = true;
<?php } else { ?>
ftpElementogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($tpElemento->getCurrentMasterTable() == "" && $tpElemento_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $tpElemento_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($tpElemento->CurrentAction == "gridadd") {
	if ($tpElemento->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$tpElemento_grid->TotalRecs = $tpElemento->SelectRecordCount();
			$tpElemento_grid->Recordset = $tpElemento_grid->LoadRecordset($tpElemento_grid->StartRec-1, $tpElemento_grid->DisplayRecs);
		} else {
			if ($tpElemento_grid->Recordset = $tpElemento_grid->LoadRecordset())
				$tpElemento_grid->TotalRecs = $tpElemento_grid->Recordset->RecordCount();
		}
		$tpElemento_grid->StartRec = 1;
		$tpElemento_grid->DisplayRecs = $tpElemento_grid->TotalRecs;
	} else {
		$tpElemento->CurrentFilter = "0=1";
		$tpElemento_grid->StartRec = 1;
		$tpElemento_grid->DisplayRecs = $tpElemento->GridAddRowCount;
	}
	$tpElemento_grid->TotalRecs = $tpElemento_grid->DisplayRecs;
	$tpElemento_grid->StopRec = $tpElemento_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tpElemento_grid->TotalRecs = $tpElemento->SelectRecordCount();
	} else {
		if ($tpElemento_grid->Recordset = $tpElemento_grid->LoadRecordset())
			$tpElemento_grid->TotalRecs = $tpElemento_grid->Recordset->RecordCount();
	}
	$tpElemento_grid->StartRec = 1;
	$tpElemento_grid->DisplayRecs = $tpElemento_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$tpElemento_grid->Recordset = $tpElemento_grid->LoadRecordset($tpElemento_grid->StartRec-1, $tpElemento_grid->DisplayRecs);
}
$tpElemento_grid->RenderOtherOptions();
?>
<?php $tpElemento_grid->ShowPageHeader(); ?>
<?php
$tpElemento_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="ftpElementogrid" class="ewForm form-horizontal">
<div id="gmp_tpElemento" class="ewGridMiddlePanel">
<table id="tbl_tpElementogrid" class="ewTable ewTableSeparate">
<?php echo $tpElemento->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tpElemento_grid->RenderListOptions();

// Render list options (header, left)
$tpElemento_grid->ListOptions->Render("header", "left");
?>
<?php if ($tpElemento->no_tpElemento->Visible) { // no_tpElemento ?>
	<?php if ($tpElemento->SortUrl($tpElemento->no_tpElemento) == "") { ?>
		<td><div id="elh_tpElemento_no_tpElemento" class="tpElemento_no_tpElemento"><div class="ewTableHeaderCaption"><?php echo $tpElemento->no_tpElemento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpElemento_no_tpElemento" class="tpElemento_no_tpElemento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpElemento->no_tpElemento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpElemento->no_tpElemento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpElemento->no_tpElemento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tpElemento->ic_funcional->Visible) { // ic_funcional ?>
	<?php if ($tpElemento->SortUrl($tpElemento->ic_funcional) == "") { ?>
		<td><div id="elh_tpElemento_ic_funcional" class="tpElemento_ic_funcional"><div class="ewTableHeaderCaption"><?php echo $tpElemento->ic_funcional->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpElemento_ic_funcional" class="tpElemento_ic_funcional">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpElemento->ic_funcional->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpElemento->ic_funcional->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpElemento->ic_funcional->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tpElemento->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($tpElemento->SortUrl($tpElemento->ic_ativo) == "") { ?>
		<td><div id="elh_tpElemento_ic_ativo" class="tpElemento_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $tpElemento->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpElemento_ic_ativo" class="tpElemento_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpElemento->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpElemento->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpElemento->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tpElemento_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$tpElemento_grid->StartRec = 1;
$tpElemento_grid->StopRec = $tpElemento_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($tpElemento_grid->FormKeyCountName) && ($tpElemento->CurrentAction == "gridadd" || $tpElemento->CurrentAction == "gridedit" || $tpElemento->CurrentAction == "F")) {
		$tpElemento_grid->KeyCount = $objForm->GetValue($tpElemento_grid->FormKeyCountName);
		$tpElemento_grid->StopRec = $tpElemento_grid->StartRec + $tpElemento_grid->KeyCount - 1;
	}
}
$tpElemento_grid->RecCnt = $tpElemento_grid->StartRec - 1;
if ($tpElemento_grid->Recordset && !$tpElemento_grid->Recordset->EOF) {
	$tpElemento_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $tpElemento_grid->StartRec > 1)
		$tpElemento_grid->Recordset->Move($tpElemento_grid->StartRec - 1);
} elseif (!$tpElemento->AllowAddDeleteRow && $tpElemento_grid->StopRec == 0) {
	$tpElemento_grid->StopRec = $tpElemento->GridAddRowCount;
}

// Initialize aggregate
$tpElemento->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tpElemento->ResetAttrs();
$tpElemento_grid->RenderRow();
if ($tpElemento->CurrentAction == "gridadd")
	$tpElemento_grid->RowIndex = 0;
if ($tpElemento->CurrentAction == "gridedit")
	$tpElemento_grid->RowIndex = 0;
while ($tpElemento_grid->RecCnt < $tpElemento_grid->StopRec) {
	$tpElemento_grid->RecCnt++;
	if (intval($tpElemento_grid->RecCnt) >= intval($tpElemento_grid->StartRec)) {
		$tpElemento_grid->RowCnt++;
		if ($tpElemento->CurrentAction == "gridadd" || $tpElemento->CurrentAction == "gridedit" || $tpElemento->CurrentAction == "F") {
			$tpElemento_grid->RowIndex++;
			$objForm->Index = $tpElemento_grid->RowIndex;
			if ($objForm->HasValue($tpElemento_grid->FormActionName))
				$tpElemento_grid->RowAction = strval($objForm->GetValue($tpElemento_grid->FormActionName));
			elseif ($tpElemento->CurrentAction == "gridadd")
				$tpElemento_grid->RowAction = "insert";
			else
				$tpElemento_grid->RowAction = "";
		}

		// Set up key count
		$tpElemento_grid->KeyCount = $tpElemento_grid->RowIndex;

		// Init row class and style
		$tpElemento->ResetAttrs();
		$tpElemento->CssClass = "";
		if ($tpElemento->CurrentAction == "gridadd") {
			if ($tpElemento->CurrentMode == "copy") {
				$tpElemento_grid->LoadRowValues($tpElemento_grid->Recordset); // Load row values
				$tpElemento_grid->SetRecordKey($tpElemento_grid->RowOldKey, $tpElemento_grid->Recordset); // Set old record key
			} else {
				$tpElemento_grid->LoadDefaultValues(); // Load default values
				$tpElemento_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$tpElemento_grid->LoadRowValues($tpElemento_grid->Recordset); // Load row values
		}
		$tpElemento->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($tpElemento->CurrentAction == "gridadd") // Grid add
			$tpElemento->RowType = EW_ROWTYPE_ADD; // Render add
		if ($tpElemento->CurrentAction == "gridadd" && $tpElemento->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$tpElemento_grid->RestoreCurrentRowFormValues($tpElemento_grid->RowIndex); // Restore form values
		if ($tpElemento->CurrentAction == "gridedit") { // Grid edit
			if ($tpElemento->EventCancelled) {
				$tpElemento_grid->RestoreCurrentRowFormValues($tpElemento_grid->RowIndex); // Restore form values
			}
			if ($tpElemento_grid->RowAction == "insert")
				$tpElemento->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$tpElemento->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($tpElemento->CurrentAction == "gridedit" && ($tpElemento->RowType == EW_ROWTYPE_EDIT || $tpElemento->RowType == EW_ROWTYPE_ADD) && $tpElemento->EventCancelled) // Update failed
			$tpElemento_grid->RestoreCurrentRowFormValues($tpElemento_grid->RowIndex); // Restore form values
		if ($tpElemento->RowType == EW_ROWTYPE_EDIT) // Edit row
			$tpElemento_grid->EditRowCnt++;
		if ($tpElemento->CurrentAction == "F") // Confirm row
			$tpElemento_grid->RestoreCurrentRowFormValues($tpElemento_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$tpElemento->RowAttrs = array_merge($tpElemento->RowAttrs, array('data-rowindex'=>$tpElemento_grid->RowCnt, 'id'=>'r' . $tpElemento_grid->RowCnt . '_tpElemento', 'data-rowtype'=>$tpElemento->RowType));

		// Render row
		$tpElemento_grid->RenderRow();

		// Render list options
		$tpElemento_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($tpElemento_grid->RowAction <> "delete" && $tpElemento_grid->RowAction <> "insertdelete" && !($tpElemento_grid->RowAction == "insert" && $tpElemento->CurrentAction == "F" && $tpElemento_grid->EmptyRow())) {
?>
	<tr<?php echo $tpElemento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tpElemento_grid->ListOptions->Render("body", "left", $tpElemento_grid->RowCnt);
?>
	<?php if ($tpElemento->no_tpElemento->Visible) { // no_tpElemento ?>
		<td<?php echo $tpElemento->no_tpElemento->CellAttributes() ?>>
<?php if ($tpElemento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpElemento_grid->RowCnt ?>_tpElemento_no_tpElemento" class="control-group tpElemento_no_tpElemento">
<input type="text" data-field="x_no_tpElemento" name="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" id="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" size="30" maxlength="50" placeholder="<?php echo $tpElemento->no_tpElemento->PlaceHolder ?>" value="<?php echo $tpElemento->no_tpElemento->EditValue ?>"<?php echo $tpElemento->no_tpElemento->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_tpElemento" name="o<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" id="o<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" value="<?php echo ew_HtmlEncode($tpElemento->no_tpElemento->OldValue) ?>">
<?php } ?>
<?php if ($tpElemento->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpElemento_grid->RowCnt ?>_tpElemento_no_tpElemento" class="control-group tpElemento_no_tpElemento">
<input type="text" data-field="x_no_tpElemento" name="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" id="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" size="30" maxlength="50" placeholder="<?php echo $tpElemento->no_tpElemento->PlaceHolder ?>" value="<?php echo $tpElemento->no_tpElemento->EditValue ?>"<?php echo $tpElemento->no_tpElemento->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($tpElemento->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpElemento->no_tpElemento->ViewAttributes() ?>>
<?php echo $tpElemento->no_tpElemento->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_tpElemento" name="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" id="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" value="<?php echo ew_HtmlEncode($tpElemento->no_tpElemento->FormValue) ?>">
<input type="hidden" data-field="x_no_tpElemento" name="o<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" id="o<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" value="<?php echo ew_HtmlEncode($tpElemento->no_tpElemento->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpElemento_grid->PageObjName . "_row_" . $tpElemento_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($tpElemento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_tpElemento" name="x<?php echo $tpElemento_grid->RowIndex ?>_nu_tpElemento" id="x<?php echo $tpElemento_grid->RowIndex ?>_nu_tpElemento" value="<?php echo ew_HtmlEncode($tpElemento->nu_tpElemento->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_tpElemento" name="o<?php echo $tpElemento_grid->RowIndex ?>_nu_tpElemento" id="o<?php echo $tpElemento_grid->RowIndex ?>_nu_tpElemento" value="<?php echo ew_HtmlEncode($tpElemento->nu_tpElemento->OldValue) ?>">
<?php } ?>
<?php if ($tpElemento->RowType == EW_ROWTYPE_EDIT || $tpElemento->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_tpElemento" name="x<?php echo $tpElemento_grid->RowIndex ?>_nu_tpElemento" id="x<?php echo $tpElemento_grid->RowIndex ?>_nu_tpElemento" value="<?php echo ew_HtmlEncode($tpElemento->nu_tpElemento->CurrentValue) ?>">
<?php } ?>
	<?php if ($tpElemento->ic_funcional->Visible) { // ic_funcional ?>
		<td<?php echo $tpElemento->ic_funcional->CellAttributes() ?>>
<?php if ($tpElemento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpElemento_grid->RowCnt ?>_tpElemento_ic_funcional" class="control-group tpElemento_ic_funcional">
<div id="tp_x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" value="{value}"<?php echo $tpElemento->ic_funcional->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpElemento->ic_funcional->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpElemento->ic_funcional->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_funcional" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpElemento->ic_funcional->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpElemento->ic_funcional->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_funcional" name="o<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="o<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" value="<?php echo ew_HtmlEncode($tpElemento->ic_funcional->OldValue) ?>">
<?php } ?>
<?php if ($tpElemento->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpElemento_grid->RowCnt ?>_tpElemento_ic_funcional" class="control-group tpElemento_ic_funcional">
<div id="tp_x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" value="{value}"<?php echo $tpElemento->ic_funcional->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpElemento->ic_funcional->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpElemento->ic_funcional->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_funcional" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpElemento->ic_funcional->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpElemento->ic_funcional->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($tpElemento->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpElemento->ic_funcional->ViewAttributes() ?>>
<?php echo $tpElemento->ic_funcional->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_funcional" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" value="<?php echo ew_HtmlEncode($tpElemento->ic_funcional->FormValue) ?>">
<input type="hidden" data-field="x_ic_funcional" name="o<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="o<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" value="<?php echo ew_HtmlEncode($tpElemento->ic_funcional->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpElemento_grid->PageObjName . "_row_" . $tpElemento_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tpElemento->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $tpElemento->ic_ativo->CellAttributes() ?>>
<?php if ($tpElemento->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpElemento_grid->RowCnt ?>_tpElemento_ic_ativo" class="control-group tpElemento_ic_ativo">
<div id="tp_x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $tpElemento->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpElemento->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpElemento->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpElemento->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpElemento->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="o<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpElemento->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($tpElemento->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpElemento_grid->RowCnt ?>_tpElemento_ic_ativo" class="control-group tpElemento_ic_ativo">
<div id="tp_x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $tpElemento->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpElemento->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpElemento->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpElemento->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpElemento->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($tpElemento->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpElemento->ic_ativo->ViewAttributes() ?>>
<?php echo $tpElemento->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpElemento->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="o<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpElemento->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpElemento_grid->PageObjName . "_row_" . $tpElemento_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tpElemento_grid->ListOptions->Render("body", "right", $tpElemento_grid->RowCnt);
?>
	</tr>
<?php if ($tpElemento->RowType == EW_ROWTYPE_ADD || $tpElemento->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftpElementogrid.UpdateOpts(<?php echo $tpElemento_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($tpElemento->CurrentAction <> "gridadd" || $tpElemento->CurrentMode == "copy")
		if (!$tpElemento_grid->Recordset->EOF) $tpElemento_grid->Recordset->MoveNext();
}
?>
<?php
	if ($tpElemento->CurrentMode == "add" || $tpElemento->CurrentMode == "copy" || $tpElemento->CurrentMode == "edit") {
		$tpElemento_grid->RowIndex = '$rowindex$';
		$tpElemento_grid->LoadDefaultValues();

		// Set row properties
		$tpElemento->ResetAttrs();
		$tpElemento->RowAttrs = array_merge($tpElemento->RowAttrs, array('data-rowindex'=>$tpElemento_grid->RowIndex, 'id'=>'r0_tpElemento', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($tpElemento->RowAttrs["class"], "ewTemplate");
		$tpElemento->RowType = EW_ROWTYPE_ADD;

		// Render row
		$tpElemento_grid->RenderRow();

		// Render list options
		$tpElemento_grid->RenderListOptions();
		$tpElemento_grid->StartRowCnt = 0;
?>
	<tr<?php echo $tpElemento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tpElemento_grid->ListOptions->Render("body", "left", $tpElemento_grid->RowIndex);
?>
	<?php if ($tpElemento->no_tpElemento->Visible) { // no_tpElemento ?>
		<td>
<?php if ($tpElemento->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_tpElemento" name="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" id="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" size="30" maxlength="50" placeholder="<?php echo $tpElemento->no_tpElemento->PlaceHolder ?>" value="<?php echo $tpElemento->no_tpElemento->EditValue ?>"<?php echo $tpElemento->no_tpElemento->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $tpElemento->no_tpElemento->ViewAttributes() ?>>
<?php echo $tpElemento->no_tpElemento->ViewValue ?></span>
<input type="hidden" data-field="x_no_tpElemento" name="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" id="x<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" value="<?php echo ew_HtmlEncode($tpElemento->no_tpElemento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_tpElemento" name="o<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" id="o<?php echo $tpElemento_grid->RowIndex ?>_no_tpElemento" value="<?php echo ew_HtmlEncode($tpElemento->no_tpElemento->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($tpElemento->ic_funcional->Visible) { // ic_funcional ?>
		<td>
<?php if ($tpElemento->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" value="{value}"<?php echo $tpElemento->ic_funcional->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpElemento->ic_funcional->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpElemento->ic_funcional->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_funcional" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpElemento->ic_funcional->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpElemento->ic_funcional->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $tpElemento->ic_funcional->ViewAttributes() ?>>
<?php echo $tpElemento->ic_funcional->ViewValue ?></span>
<input type="hidden" data-field="x_ic_funcional" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" value="<?php echo ew_HtmlEncode($tpElemento->ic_funcional->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_funcional" name="o<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" id="o<?php echo $tpElemento_grid->RowIndex ?>_ic_funcional" value="<?php echo ew_HtmlEncode($tpElemento->ic_funcional->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($tpElemento->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($tpElemento->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $tpElemento->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpElemento->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpElemento->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpElemento->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpElemento->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $tpElemento->ic_ativo->ViewAttributes() ?>>
<?php echo $tpElemento->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpElemento->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" id="o<?php echo $tpElemento_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpElemento->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tpElemento_grid->ListOptions->Render("body", "right", $tpElemento_grid->RowCnt);
?>
<script type="text/javascript">
ftpElementogrid.UpdateOpts(<?php echo $tpElemento_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($tpElemento->CurrentMode == "add" || $tpElemento->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $tpElemento_grid->FormKeyCountName ?>" id="<?php echo $tpElemento_grid->FormKeyCountName ?>" value="<?php echo $tpElemento_grid->KeyCount ?>">
<?php echo $tpElemento_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tpElemento->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $tpElemento_grid->FormKeyCountName ?>" id="<?php echo $tpElemento_grid->FormKeyCountName ?>" value="<?php echo $tpElemento_grid->KeyCount ?>">
<?php echo $tpElemento_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tpElemento->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ftpElementogrid">
</div>
<?php

// Close recordset
if ($tpElemento_grid->Recordset)
	$tpElemento_grid->Recordset->Close();
?>
<?php if ($tpElemento_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($tpElemento_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($tpElemento->Export == "") { ?>
<script type="text/javascript">
ftpElementogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$tpElemento_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$tpElemento_grid->Page_Terminate();
?>
