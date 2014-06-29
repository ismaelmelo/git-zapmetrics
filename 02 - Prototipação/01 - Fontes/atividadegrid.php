<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($atividade_grid)) $atividade_grid = new catividade_grid();

// Page init
$atividade_grid->Page_Init();

// Page main
$atividade_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$atividade_grid->Page_Render();
?>
<?php if ($atividade->Export == "") { ?>
<script type="text/javascript">

// Page object
var atividade_grid = new ew_Page("atividade_grid");
atividade_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = atividade_grid.PageID; // For backward compatibility

// Form object
var fatividadegrid = new ew_Form("fatividadegrid");
fatividadegrid.FormKeyCountName = '<?php echo $atividade_grid->FormKeyCountName ?>';

// Validate form
fatividadegrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_atividade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade->no_atividade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_duracao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade->vr_duracao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_duracao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($atividade->vr_duracao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade->ic_ativo->FldCaption()) ?>");

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
fatividadegrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_atividade", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_duracao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
fatividadegrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fatividadegrid.ValidateRequired = true;
<?php } else { ?>
fatividadegrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($atividade_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $atividade_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($atividade->CurrentAction == "gridadd") {
	if ($atividade->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$atividade_grid->TotalRecs = $atividade->SelectRecordCount();
			$atividade_grid->Recordset = $atividade_grid->LoadRecordset($atividade_grid->StartRec-1, $atividade_grid->DisplayRecs);
		} else {
			if ($atividade_grid->Recordset = $atividade_grid->LoadRecordset())
				$atividade_grid->TotalRecs = $atividade_grid->Recordset->RecordCount();
		}
		$atividade_grid->StartRec = 1;
		$atividade_grid->DisplayRecs = $atividade_grid->TotalRecs;
	} else {
		$atividade->CurrentFilter = "0=1";
		$atividade_grid->StartRec = 1;
		$atividade_grid->DisplayRecs = $atividade->GridAddRowCount;
	}
	$atividade_grid->TotalRecs = $atividade_grid->DisplayRecs;
	$atividade_grid->StopRec = $atividade_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$atividade_grid->TotalRecs = $atividade->SelectRecordCount();
	} else {
		if ($atividade_grid->Recordset = $atividade_grid->LoadRecordset())
			$atividade_grid->TotalRecs = $atividade_grid->Recordset->RecordCount();
	}
	$atividade_grid->StartRec = 1;
	$atividade_grid->DisplayRecs = $atividade_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$atividade_grid->Recordset = $atividade_grid->LoadRecordset($atividade_grid->StartRec-1, $atividade_grid->DisplayRecs);
}
$atividade_grid->RenderOtherOptions();
?>
<?php $atividade_grid->ShowPageHeader(); ?>
<?php
$atividade_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fatividadegrid" class="ewForm form-horizontal">
<div id="gmp_atividade" class="ewGridMiddlePanel">
<table id="tbl_atividadegrid" class="ewTable ewTableSeparate">
<?php echo $atividade->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$atividade_grid->RenderListOptions();

// Render list options (header, left)
$atividade_grid->ListOptions->Render("header", "left");
?>
<?php if ($atividade->no_atividade->Visible) { // no_atividade ?>
	<?php if ($atividade->SortUrl($atividade->no_atividade) == "") { ?>
		<td><div id="elh_atividade_no_atividade" class="atividade_no_atividade"><div class="ewTableHeaderCaption"><?php echo $atividade->no_atividade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_atividade_no_atividade" class="atividade_no_atividade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atividade->no_atividade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atividade->no_atividade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atividade->no_atividade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($atividade->vr_duracao->Visible) { // vr_duracao ?>
	<?php if ($atividade->SortUrl($atividade->vr_duracao) == "") { ?>
		<td><div id="elh_atividade_vr_duracao" class="atividade_vr_duracao"><div class="ewTableHeaderCaption"><?php echo $atividade->vr_duracao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_atividade_vr_duracao" class="atividade_vr_duracao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atividade->vr_duracao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atividade->vr_duracao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atividade->vr_duracao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($atividade->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($atividade->SortUrl($atividade->ic_ativo) == "") { ?>
		<td><div id="elh_atividade_ic_ativo" class="atividade_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $atividade->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_atividade_ic_ativo" class="atividade_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atividade->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atividade->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atividade->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$atividade_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$atividade_grid->StartRec = 1;
$atividade_grid->StopRec = $atividade_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($atividade_grid->FormKeyCountName) && ($atividade->CurrentAction == "gridadd" || $atividade->CurrentAction == "gridedit" || $atividade->CurrentAction == "F")) {
		$atividade_grid->KeyCount = $objForm->GetValue($atividade_grid->FormKeyCountName);
		$atividade_grid->StopRec = $atividade_grid->StartRec + $atividade_grid->KeyCount - 1;
	}
}
$atividade_grid->RecCnt = $atividade_grid->StartRec - 1;
if ($atividade_grid->Recordset && !$atividade_grid->Recordset->EOF) {
	$atividade_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $atividade_grid->StartRec > 1)
		$atividade_grid->Recordset->Move($atividade_grid->StartRec - 1);
} elseif (!$atividade->AllowAddDeleteRow && $atividade_grid->StopRec == 0) {
	$atividade_grid->StopRec = $atividade->GridAddRowCount;
}

// Initialize aggregate
$atividade->RowType = EW_ROWTYPE_AGGREGATEINIT;
$atividade->ResetAttrs();
$atividade_grid->RenderRow();
if ($atividade->CurrentAction == "gridadd")
	$atividade_grid->RowIndex = 0;
if ($atividade->CurrentAction == "gridedit")
	$atividade_grid->RowIndex = 0;
while ($atividade_grid->RecCnt < $atividade_grid->StopRec) {
	$atividade_grid->RecCnt++;
	if (intval($atividade_grid->RecCnt) >= intval($atividade_grid->StartRec)) {
		$atividade_grid->RowCnt++;
		if ($atividade->CurrentAction == "gridadd" || $atividade->CurrentAction == "gridedit" || $atividade->CurrentAction == "F") {
			$atividade_grid->RowIndex++;
			$objForm->Index = $atividade_grid->RowIndex;
			if ($objForm->HasValue($atividade_grid->FormActionName))
				$atividade_grid->RowAction = strval($objForm->GetValue($atividade_grid->FormActionName));
			elseif ($atividade->CurrentAction == "gridadd")
				$atividade_grid->RowAction = "insert";
			else
				$atividade_grid->RowAction = "";
		}

		// Set up key count
		$atividade_grid->KeyCount = $atividade_grid->RowIndex;

		// Init row class and style
		$atividade->ResetAttrs();
		$atividade->CssClass = "";
		if ($atividade->CurrentAction == "gridadd") {
			if ($atividade->CurrentMode == "copy") {
				$atividade_grid->LoadRowValues($atividade_grid->Recordset); // Load row values
				$atividade_grid->SetRecordKey($atividade_grid->RowOldKey, $atividade_grid->Recordset); // Set old record key
			} else {
				$atividade_grid->LoadDefaultValues(); // Load default values
				$atividade_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$atividade_grid->LoadRowValues($atividade_grid->Recordset); // Load row values
		}
		$atividade->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($atividade->CurrentAction == "gridadd") // Grid add
			$atividade->RowType = EW_ROWTYPE_ADD; // Render add
		if ($atividade->CurrentAction == "gridadd" && $atividade->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$atividade_grid->RestoreCurrentRowFormValues($atividade_grid->RowIndex); // Restore form values
		if ($atividade->CurrentAction == "gridedit") { // Grid edit
			if ($atividade->EventCancelled) {
				$atividade_grid->RestoreCurrentRowFormValues($atividade_grid->RowIndex); // Restore form values
			}
			if ($atividade_grid->RowAction == "insert")
				$atividade->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$atividade->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($atividade->CurrentAction == "gridedit" && ($atividade->RowType == EW_ROWTYPE_EDIT || $atividade->RowType == EW_ROWTYPE_ADD) && $atividade->EventCancelled) // Update failed
			$atividade_grid->RestoreCurrentRowFormValues($atividade_grid->RowIndex); // Restore form values
		if ($atividade->RowType == EW_ROWTYPE_EDIT) // Edit row
			$atividade_grid->EditRowCnt++;
		if ($atividade->CurrentAction == "F") // Confirm row
			$atividade_grid->RestoreCurrentRowFormValues($atividade_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$atividade->RowAttrs = array_merge($atividade->RowAttrs, array('data-rowindex'=>$atividade_grid->RowCnt, 'id'=>'r' . $atividade_grid->RowCnt . '_atividade', 'data-rowtype'=>$atividade->RowType));

		// Render row
		$atividade_grid->RenderRow();

		// Render list options
		$atividade_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($atividade_grid->RowAction <> "delete" && $atividade_grid->RowAction <> "insertdelete" && !($atividade_grid->RowAction == "insert" && $atividade->CurrentAction == "F" && $atividade_grid->EmptyRow())) {
?>
	<tr<?php echo $atividade->RowAttributes() ?>>
<?php

// Render list options (body, left)
$atividade_grid->ListOptions->Render("body", "left", $atividade_grid->RowCnt);
?>
	<?php if ($atividade->no_atividade->Visible) { // no_atividade ?>
		<td<?php echo $atividade->no_atividade->CellAttributes() ?>>
<?php if ($atividade->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $atividade_grid->RowCnt ?>_atividade_no_atividade" class="control-group atividade_no_atividade">
<input type="text" data-field="x_no_atividade" name="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" id="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" size="30" maxlength="100" placeholder="<?php echo $atividade->no_atividade->PlaceHolder ?>" value="<?php echo $atividade->no_atividade->EditValue ?>"<?php echo $atividade->no_atividade->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_atividade" name="o<?php echo $atividade_grid->RowIndex ?>_no_atividade" id="o<?php echo $atividade_grid->RowIndex ?>_no_atividade" value="<?php echo ew_HtmlEncode($atividade->no_atividade->OldValue) ?>">
<?php } ?>
<?php if ($atividade->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $atividade_grid->RowCnt ?>_atividade_no_atividade" class="control-group atividade_no_atividade">
<input type="text" data-field="x_no_atividade" name="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" id="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" size="30" maxlength="100" placeholder="<?php echo $atividade->no_atividade->PlaceHolder ?>" value="<?php echo $atividade->no_atividade->EditValue ?>"<?php echo $atividade->no_atividade->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($atividade->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $atividade->no_atividade->ViewAttributes() ?>>
<?php echo $atividade->no_atividade->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_atividade" name="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" id="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" value="<?php echo ew_HtmlEncode($atividade->no_atividade->FormValue) ?>">
<input type="hidden" data-field="x_no_atividade" name="o<?php echo $atividade_grid->RowIndex ?>_no_atividade" id="o<?php echo $atividade_grid->RowIndex ?>_no_atividade" value="<?php echo ew_HtmlEncode($atividade->no_atividade->OldValue) ?>">
<?php } ?>
<a id="<?php echo $atividade_grid->PageObjName . "_row_" . $atividade_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($atividade->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_atividade" name="x<?php echo $atividade_grid->RowIndex ?>_nu_atividade" id="x<?php echo $atividade_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade->nu_atividade->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_atividade" name="o<?php echo $atividade_grid->RowIndex ?>_nu_atividade" id="o<?php echo $atividade_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade->nu_atividade->OldValue) ?>">
<?php } ?>
<?php if ($atividade->RowType == EW_ROWTYPE_EDIT || $atividade->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_atividade" name="x<?php echo $atividade_grid->RowIndex ?>_nu_atividade" id="x<?php echo $atividade_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade->nu_atividade->CurrentValue) ?>">
<?php } ?>
	<?php if ($atividade->vr_duracao->Visible) { // vr_duracao ?>
		<td<?php echo $atividade->vr_duracao->CellAttributes() ?>>
<?php if ($atividade->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $atividade_grid->RowCnt ?>_atividade_vr_duracao" class="control-group atividade_vr_duracao">
<input type="text" data-field="x_vr_duracao" name="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" id="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" size="30" placeholder="<?php echo $atividade->vr_duracao->PlaceHolder ?>" value="<?php echo $atividade->vr_duracao->EditValue ?>"<?php echo $atividade->vr_duracao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_duracao" name="o<?php echo $atividade_grid->RowIndex ?>_vr_duracao" id="o<?php echo $atividade_grid->RowIndex ?>_vr_duracao" value="<?php echo ew_HtmlEncode($atividade->vr_duracao->OldValue) ?>">
<?php } ?>
<?php if ($atividade->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $atividade_grid->RowCnt ?>_atividade_vr_duracao" class="control-group atividade_vr_duracao">
<input type="text" data-field="x_vr_duracao" name="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" id="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" size="30" placeholder="<?php echo $atividade->vr_duracao->PlaceHolder ?>" value="<?php echo $atividade->vr_duracao->EditValue ?>"<?php echo $atividade->vr_duracao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($atividade->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $atividade->vr_duracao->ViewAttributes() ?>>
<?php echo $atividade->vr_duracao->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_duracao" name="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" id="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" value="<?php echo ew_HtmlEncode($atividade->vr_duracao->FormValue) ?>">
<input type="hidden" data-field="x_vr_duracao" name="o<?php echo $atividade_grid->RowIndex ?>_vr_duracao" id="o<?php echo $atividade_grid->RowIndex ?>_vr_duracao" value="<?php echo ew_HtmlEncode($atividade->vr_duracao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $atividade_grid->PageObjName . "_row_" . $atividade_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($atividade->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $atividade->ic_ativo->CellAttributes() ?>>
<?php if ($atividade->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $atividade_grid->RowCnt ?>_atividade_ic_ativo" class="control-group atividade_ic_ativo">
<div id="tp_x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $atividade->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $atividade->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $atividade->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $atividade->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="o<?php echo $atividade_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($atividade->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($atividade->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $atividade_grid->RowCnt ?>_atividade_ic_ativo" class="control-group atividade_ic_ativo">
<div id="tp_x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $atividade->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $atividade->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $atividade->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $atividade->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($atividade->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $atividade->ic_ativo->ViewAttributes() ?>>
<?php echo $atividade->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($atividade->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="o<?php echo $atividade_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($atividade->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $atividade_grid->PageObjName . "_row_" . $atividade_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$atividade_grid->ListOptions->Render("body", "right", $atividade_grid->RowCnt);
?>
	</tr>
<?php if ($atividade->RowType == EW_ROWTYPE_ADD || $atividade->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fatividadegrid.UpdateOpts(<?php echo $atividade_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($atividade->CurrentAction <> "gridadd" || $atividade->CurrentMode == "copy")
		if (!$atividade_grid->Recordset->EOF) $atividade_grid->Recordset->MoveNext();
}
?>
<?php
	if ($atividade->CurrentMode == "add" || $atividade->CurrentMode == "copy" || $atividade->CurrentMode == "edit") {
		$atividade_grid->RowIndex = '$rowindex$';
		$atividade_grid->LoadDefaultValues();

		// Set row properties
		$atividade->ResetAttrs();
		$atividade->RowAttrs = array_merge($atividade->RowAttrs, array('data-rowindex'=>$atividade_grid->RowIndex, 'id'=>'r0_atividade', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($atividade->RowAttrs["class"], "ewTemplate");
		$atividade->RowType = EW_ROWTYPE_ADD;

		// Render row
		$atividade_grid->RenderRow();

		// Render list options
		$atividade_grid->RenderListOptions();
		$atividade_grid->StartRowCnt = 0;
?>
	<tr<?php echo $atividade->RowAttributes() ?>>
<?php

// Render list options (body, left)
$atividade_grid->ListOptions->Render("body", "left", $atividade_grid->RowIndex);
?>
	<?php if ($atividade->no_atividade->Visible) { // no_atividade ?>
		<td>
<?php if ($atividade->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_atividade" name="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" id="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" size="30" maxlength="100" placeholder="<?php echo $atividade->no_atividade->PlaceHolder ?>" value="<?php echo $atividade->no_atividade->EditValue ?>"<?php echo $atividade->no_atividade->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $atividade->no_atividade->ViewAttributes() ?>>
<?php echo $atividade->no_atividade->ViewValue ?></span>
<input type="hidden" data-field="x_no_atividade" name="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" id="x<?php echo $atividade_grid->RowIndex ?>_no_atividade" value="<?php echo ew_HtmlEncode($atividade->no_atividade->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_atividade" name="o<?php echo $atividade_grid->RowIndex ?>_no_atividade" id="o<?php echo $atividade_grid->RowIndex ?>_no_atividade" value="<?php echo ew_HtmlEncode($atividade->no_atividade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($atividade->vr_duracao->Visible) { // vr_duracao ?>
		<td>
<?php if ($atividade->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_duracao" name="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" id="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" size="30" placeholder="<?php echo $atividade->vr_duracao->PlaceHolder ?>" value="<?php echo $atividade->vr_duracao->EditValue ?>"<?php echo $atividade->vr_duracao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $atividade->vr_duracao->ViewAttributes() ?>>
<?php echo $atividade->vr_duracao->ViewValue ?></span>
<input type="hidden" data-field="x_vr_duracao" name="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" id="x<?php echo $atividade_grid->RowIndex ?>_vr_duracao" value="<?php echo ew_HtmlEncode($atividade->vr_duracao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_duracao" name="o<?php echo $atividade_grid->RowIndex ?>_vr_duracao" id="o<?php echo $atividade_grid->RowIndex ?>_vr_duracao" value="<?php echo ew_HtmlEncode($atividade->vr_duracao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($atividade->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($atividade->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $atividade->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $atividade->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $atividade->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $atividade->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $atividade->ic_ativo->ViewAttributes() ?>>
<?php echo $atividade->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="x<?php echo $atividade_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($atividade->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $atividade_grid->RowIndex ?>_ic_ativo" id="o<?php echo $atividade_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($atividade->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$atividade_grid->ListOptions->Render("body", "right", $atividade_grid->RowCnt);
?>
<script type="text/javascript">
fatividadegrid.UpdateOpts(<?php echo $atividade_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($atividade->CurrentMode == "add" || $atividade->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $atividade_grid->FormKeyCountName ?>" id="<?php echo $atividade_grid->FormKeyCountName ?>" value="<?php echo $atividade_grid->KeyCount ?>">
<?php echo $atividade_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($atividade->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $atividade_grid->FormKeyCountName ?>" id="<?php echo $atividade_grid->FormKeyCountName ?>" value="<?php echo $atividade_grid->KeyCount ?>">
<?php echo $atividade_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($atividade->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fatividadegrid">
</div>
<?php

// Close recordset
if ($atividade_grid->Recordset)
	$atividade_grid->Recordset->Close();
?>
<?php if ($atividade_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($atividade_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($atividade->Export == "") { ?>
<script type="text/javascript">
fatividadegrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$atividade_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$atividade_grid->Page_Terminate();
?>
