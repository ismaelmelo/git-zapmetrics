<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($faseroteiro_grid)) $faseroteiro_grid = new cfaseroteiro_grid();

// Page init
$faseroteiro_grid->Page_Init();

// Page main
$faseroteiro_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$faseroteiro_grid->Page_Render();
?>
<?php if ($faseroteiro->Export == "") { ?>
<script type="text/javascript">

// Page object
var faseroteiro_grid = new ew_Page("faseroteiro_grid");
faseroteiro_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = faseroteiro_grid.PageID; // For backward compatibility

// Form object
var ffaseroteirogrid = new ew_Form("ffaseroteirogrid");
ffaseroteirogrid.FormKeyCountName = '<?php echo $faseroteiro_grid->FormKeyCountName ?>';

// Validate form
ffaseroteirogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_faseRoteiro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($faseroteiro->no_faseRoteiro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_distribuicao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($faseroteiro->pc_distribuicao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_distribuicao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($faseroteiro->pc_distribuicao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($faseroteiro->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ordem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($faseroteiro->nu_ordem->FldErrMsg()) ?>");

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
ffaseroteirogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_faseRoteiro", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pc_distribuicao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_ordem", false)) return false;
	return true;
}

// Form_CustomValidate event
ffaseroteirogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffaseroteirogrid.ValidateRequired = true;
<?php } else { ?>
ffaseroteirogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($faseroteiro->getCurrentMasterTable() == "" && $faseroteiro_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $faseroteiro_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($faseroteiro->CurrentAction == "gridadd") {
	if ($faseroteiro->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$faseroteiro_grid->TotalRecs = $faseroteiro->SelectRecordCount();
			$faseroteiro_grid->Recordset = $faseroteiro_grid->LoadRecordset($faseroteiro_grid->StartRec-1, $faseroteiro_grid->DisplayRecs);
		} else {
			if ($faseroteiro_grid->Recordset = $faseroteiro_grid->LoadRecordset())
				$faseroteiro_grid->TotalRecs = $faseroteiro_grid->Recordset->RecordCount();
		}
		$faseroteiro_grid->StartRec = 1;
		$faseroteiro_grid->DisplayRecs = $faseroteiro_grid->TotalRecs;
	} else {
		$faseroteiro->CurrentFilter = "0=1";
		$faseroteiro_grid->StartRec = 1;
		$faseroteiro_grid->DisplayRecs = $faseroteiro->GridAddRowCount;
	}
	$faseroteiro_grid->TotalRecs = $faseroteiro_grid->DisplayRecs;
	$faseroteiro_grid->StopRec = $faseroteiro_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$faseroteiro_grid->TotalRecs = $faseroteiro->SelectRecordCount();
	} else {
		if ($faseroteiro_grid->Recordset = $faseroteiro_grid->LoadRecordset())
			$faseroteiro_grid->TotalRecs = $faseroteiro_grid->Recordset->RecordCount();
	}
	$faseroteiro_grid->StartRec = 1;
	$faseroteiro_grid->DisplayRecs = $faseroteiro_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$faseroteiro_grid->Recordset = $faseroteiro_grid->LoadRecordset($faseroteiro_grid->StartRec-1, $faseroteiro_grid->DisplayRecs);
}
$faseroteiro_grid->RenderOtherOptions();
?>
<?php $faseroteiro_grid->ShowPageHeader(); ?>
<?php
$faseroteiro_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="ffaseroteirogrid" class="ewForm form-horizontal">
<div id="gmp_faseroteiro" class="ewGridMiddlePanel">
<table id="tbl_faseroteirogrid" class="ewTable ewTableSeparate">
<?php echo $faseroteiro->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$faseroteiro_grid->RenderListOptions();

// Render list options (header, left)
$faseroteiro_grid->ListOptions->Render("header", "left");
?>
<?php if ($faseroteiro->no_faseRoteiro->Visible) { // no_faseRoteiro ?>
	<?php if ($faseroteiro->SortUrl($faseroteiro->no_faseRoteiro) == "") { ?>
		<td><div id="elh_faseroteiro_no_faseRoteiro" class="faseroteiro_no_faseRoteiro"><div class="ewTableHeaderCaption"><?php echo $faseroteiro->no_faseRoteiro->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_faseroteiro_no_faseRoteiro" class="faseroteiro_no_faseRoteiro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $faseroteiro->no_faseRoteiro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($faseroteiro->no_faseRoteiro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($faseroteiro->no_faseRoteiro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($faseroteiro->pc_distribuicao->Visible) { // pc_distribuicao ?>
	<?php if ($faseroteiro->SortUrl($faseroteiro->pc_distribuicao) == "") { ?>
		<td><div id="elh_faseroteiro_pc_distribuicao" class="faseroteiro_pc_distribuicao"><div class="ewTableHeaderCaption"><?php echo $faseroteiro->pc_distribuicao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_faseroteiro_pc_distribuicao" class="faseroteiro_pc_distribuicao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $faseroteiro->pc_distribuicao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($faseroteiro->pc_distribuicao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($faseroteiro->pc_distribuicao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($faseroteiro->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($faseroteiro->SortUrl($faseroteiro->ic_ativo) == "") { ?>
		<td><div id="elh_faseroteiro_ic_ativo" class="faseroteiro_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $faseroteiro->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_faseroteiro_ic_ativo" class="faseroteiro_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $faseroteiro->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($faseroteiro->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($faseroteiro->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($faseroteiro->nu_ordem->Visible) { // nu_ordem ?>
	<?php if ($faseroteiro->SortUrl($faseroteiro->nu_ordem) == "") { ?>
		<td><div id="elh_faseroteiro_nu_ordem" class="faseroteiro_nu_ordem"><div class="ewTableHeaderCaption"><?php echo $faseroteiro->nu_ordem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_faseroteiro_nu_ordem" class="faseroteiro_nu_ordem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $faseroteiro->nu_ordem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($faseroteiro->nu_ordem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($faseroteiro->nu_ordem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$faseroteiro_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$faseroteiro_grid->StartRec = 1;
$faseroteiro_grid->StopRec = $faseroteiro_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($faseroteiro_grid->FormKeyCountName) && ($faseroteiro->CurrentAction == "gridadd" || $faseroteiro->CurrentAction == "gridedit" || $faseroteiro->CurrentAction == "F")) {
		$faseroteiro_grid->KeyCount = $objForm->GetValue($faseroteiro_grid->FormKeyCountName);
		$faseroteiro_grid->StopRec = $faseroteiro_grid->StartRec + $faseroteiro_grid->KeyCount - 1;
	}
}
$faseroteiro_grid->RecCnt = $faseroteiro_grid->StartRec - 1;
if ($faseroteiro_grid->Recordset && !$faseroteiro_grid->Recordset->EOF) {
	$faseroteiro_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $faseroteiro_grid->StartRec > 1)
		$faseroteiro_grid->Recordset->Move($faseroteiro_grid->StartRec - 1);
} elseif (!$faseroteiro->AllowAddDeleteRow && $faseroteiro_grid->StopRec == 0) {
	$faseroteiro_grid->StopRec = $faseroteiro->GridAddRowCount;
}

// Initialize aggregate
$faseroteiro->RowType = EW_ROWTYPE_AGGREGATEINIT;
$faseroteiro->ResetAttrs();
$faseroteiro_grid->RenderRow();
if ($faseroteiro->CurrentAction == "gridadd")
	$faseroteiro_grid->RowIndex = 0;
if ($faseroteiro->CurrentAction == "gridedit")
	$faseroteiro_grid->RowIndex = 0;
while ($faseroteiro_grid->RecCnt < $faseroteiro_grid->StopRec) {
	$faseroteiro_grid->RecCnt++;
	if (intval($faseroteiro_grid->RecCnt) >= intval($faseroteiro_grid->StartRec)) {
		$faseroteiro_grid->RowCnt++;
		if ($faseroteiro->CurrentAction == "gridadd" || $faseroteiro->CurrentAction == "gridedit" || $faseroteiro->CurrentAction == "F") {
			$faseroteiro_grid->RowIndex++;
			$objForm->Index = $faseroteiro_grid->RowIndex;
			if ($objForm->HasValue($faseroteiro_grid->FormActionName))
				$faseroteiro_grid->RowAction = strval($objForm->GetValue($faseroteiro_grid->FormActionName));
			elseif ($faseroteiro->CurrentAction == "gridadd")
				$faseroteiro_grid->RowAction = "insert";
			else
				$faseroteiro_grid->RowAction = "";
		}

		// Set up key count
		$faseroteiro_grid->KeyCount = $faseroteiro_grid->RowIndex;

		// Init row class and style
		$faseroteiro->ResetAttrs();
		$faseroteiro->CssClass = "";
		if ($faseroteiro->CurrentAction == "gridadd") {
			if ($faseroteiro->CurrentMode == "copy") {
				$faseroteiro_grid->LoadRowValues($faseroteiro_grid->Recordset); // Load row values
				$faseroteiro_grid->SetRecordKey($faseroteiro_grid->RowOldKey, $faseroteiro_grid->Recordset); // Set old record key
			} else {
				$faseroteiro_grid->LoadDefaultValues(); // Load default values
				$faseroteiro_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$faseroteiro_grid->LoadRowValues($faseroteiro_grid->Recordset); // Load row values
		}
		$faseroteiro->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($faseroteiro->CurrentAction == "gridadd") // Grid add
			$faseroteiro->RowType = EW_ROWTYPE_ADD; // Render add
		if ($faseroteiro->CurrentAction == "gridadd" && $faseroteiro->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$faseroteiro_grid->RestoreCurrentRowFormValues($faseroteiro_grid->RowIndex); // Restore form values
		if ($faseroteiro->CurrentAction == "gridedit") { // Grid edit
			if ($faseroteiro->EventCancelled) {
				$faseroteiro_grid->RestoreCurrentRowFormValues($faseroteiro_grid->RowIndex); // Restore form values
			}
			if ($faseroteiro_grid->RowAction == "insert")
				$faseroteiro->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$faseroteiro->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($faseroteiro->CurrentAction == "gridedit" && ($faseroteiro->RowType == EW_ROWTYPE_EDIT || $faseroteiro->RowType == EW_ROWTYPE_ADD) && $faseroteiro->EventCancelled) // Update failed
			$faseroteiro_grid->RestoreCurrentRowFormValues($faseroteiro_grid->RowIndex); // Restore form values
		if ($faseroteiro->RowType == EW_ROWTYPE_EDIT) // Edit row
			$faseroteiro_grid->EditRowCnt++;
		if ($faseroteiro->CurrentAction == "F") // Confirm row
			$faseroteiro_grid->RestoreCurrentRowFormValues($faseroteiro_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$faseroteiro->RowAttrs = array_merge($faseroteiro->RowAttrs, array('data-rowindex'=>$faseroteiro_grid->RowCnt, 'id'=>'r' . $faseroteiro_grid->RowCnt . '_faseroteiro', 'data-rowtype'=>$faseroteiro->RowType));

		// Render row
		$faseroteiro_grid->RenderRow();

		// Render list options
		$faseroteiro_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($faseroteiro_grid->RowAction <> "delete" && $faseroteiro_grid->RowAction <> "insertdelete" && !($faseroteiro_grid->RowAction == "insert" && $faseroteiro->CurrentAction == "F" && $faseroteiro_grid->EmptyRow())) {
?>
	<tr<?php echo $faseroteiro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$faseroteiro_grid->ListOptions->Render("body", "left", $faseroteiro_grid->RowCnt);
?>
	<?php if ($faseroteiro->no_faseRoteiro->Visible) { // no_faseRoteiro ?>
		<td<?php echo $faseroteiro->no_faseRoteiro->CellAttributes() ?>>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $faseroteiro_grid->RowCnt ?>_faseroteiro_no_faseRoteiro" class="control-group faseroteiro_no_faseRoteiro">
<input type="text" data-field="x_no_faseRoteiro" name="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" id="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" size="30" maxlength="75" placeholder="<?php echo $faseroteiro->no_faseRoteiro->PlaceHolder ?>" value="<?php echo $faseroteiro->no_faseRoteiro->EditValue ?>"<?php echo $faseroteiro->no_faseRoteiro->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_faseRoteiro" name="o<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" id="o<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" value="<?php echo ew_HtmlEncode($faseroteiro->no_faseRoteiro->OldValue) ?>">
<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $faseroteiro_grid->RowCnt ?>_faseroteiro_no_faseRoteiro" class="control-group faseroteiro_no_faseRoteiro">
<input type="text" data-field="x_no_faseRoteiro" name="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" id="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" size="30" maxlength="75" placeholder="<?php echo $faseroteiro->no_faseRoteiro->PlaceHolder ?>" value="<?php echo $faseroteiro->no_faseRoteiro->EditValue ?>"<?php echo $faseroteiro->no_faseRoteiro->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $faseroteiro->no_faseRoteiro->ViewAttributes() ?>>
<?php echo $faseroteiro->no_faseRoteiro->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_faseRoteiro" name="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" id="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" value="<?php echo ew_HtmlEncode($faseroteiro->no_faseRoteiro->FormValue) ?>">
<input type="hidden" data-field="x_no_faseRoteiro" name="o<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" id="o<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" value="<?php echo ew_HtmlEncode($faseroteiro->no_faseRoteiro->OldValue) ?>">
<?php } ?>
<a id="<?php echo $faseroteiro_grid->PageObjName . "_row_" . $faseroteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_faseRoteiro" name="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_faseRoteiro" id="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_faseRoteiro" value="<?php echo ew_HtmlEncode($faseroteiro->nu_faseRoteiro->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_faseRoteiro" name="o<?php echo $faseroteiro_grid->RowIndex ?>_nu_faseRoteiro" id="o<?php echo $faseroteiro_grid->RowIndex ?>_nu_faseRoteiro" value="<?php echo ew_HtmlEncode($faseroteiro->nu_faseRoteiro->OldValue) ?>">
<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_EDIT || $faseroteiro->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_faseRoteiro" name="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_faseRoteiro" id="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_faseRoteiro" value="<?php echo ew_HtmlEncode($faseroteiro->nu_faseRoteiro->CurrentValue) ?>">
<?php } ?>
	<?php if ($faseroteiro->pc_distribuicao->Visible) { // pc_distribuicao ?>
		<td<?php echo $faseroteiro->pc_distribuicao->CellAttributes() ?>>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $faseroteiro_grid->RowCnt ?>_faseroteiro_pc_distribuicao" class="control-group faseroteiro_pc_distribuicao">
<input type="text" data-field="x_pc_distribuicao" name="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" size="30" placeholder="<?php echo $faseroteiro->pc_distribuicao->PlaceHolder ?>" value="<?php echo $faseroteiro->pc_distribuicao->EditValue ?>"<?php echo $faseroteiro->pc_distribuicao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_pc_distribuicao" name="o<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" id="o<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($faseroteiro->pc_distribuicao->OldValue) ?>">
<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $faseroteiro_grid->RowCnt ?>_faseroteiro_pc_distribuicao" class="control-group faseroteiro_pc_distribuicao">
<input type="text" data-field="x_pc_distribuicao" name="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" size="30" placeholder="<?php echo $faseroteiro->pc_distribuicao->PlaceHolder ?>" value="<?php echo $faseroteiro->pc_distribuicao->EditValue ?>"<?php echo $faseroteiro->pc_distribuicao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $faseroteiro->pc_distribuicao->ViewAttributes() ?>>
<?php echo $faseroteiro->pc_distribuicao->ListViewValue() ?></span>
<input type="hidden" data-field="x_pc_distribuicao" name="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($faseroteiro->pc_distribuicao->FormValue) ?>">
<input type="hidden" data-field="x_pc_distribuicao" name="o<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" id="o<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($faseroteiro->pc_distribuicao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $faseroteiro_grid->PageObjName . "_row_" . $faseroteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($faseroteiro->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $faseroteiro->ic_ativo->CellAttributes() ?>>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $faseroteiro_grid->RowCnt ?>_faseroteiro_ic_ativo" class="control-group faseroteiro_ic_ativo">
<div id="tp_x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $faseroteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $faseroteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($faseroteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $faseroteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $faseroteiro->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="o<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($faseroteiro->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $faseroteiro_grid->RowCnt ?>_faseroteiro_ic_ativo" class="control-group faseroteiro_ic_ativo">
<div id="tp_x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $faseroteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $faseroteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($faseroteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $faseroteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $faseroteiro->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $faseroteiro->ic_ativo->ViewAttributes() ?>>
<?php echo $faseroteiro->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($faseroteiro->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="o<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($faseroteiro->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $faseroteiro_grid->PageObjName . "_row_" . $faseroteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($faseroteiro->nu_ordem->Visible) { // nu_ordem ?>
		<td<?php echo $faseroteiro->nu_ordem->CellAttributes() ?>>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $faseroteiro_grid->RowCnt ?>_faseroteiro_nu_ordem" class="control-group faseroteiro_nu_ordem">
<input type="text" data-field="x_nu_ordem" name="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $faseroteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $faseroteiro->nu_ordem->EditValue ?>"<?php echo $faseroteiro->nu_ordem->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" id="o<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($faseroteiro->nu_ordem->OldValue) ?>">
<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $faseroteiro_grid->RowCnt ?>_faseroteiro_nu_ordem" class="control-group faseroteiro_nu_ordem">
<input type="text" data-field="x_nu_ordem" name="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $faseroteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $faseroteiro->nu_ordem->EditValue ?>"<?php echo $faseroteiro->nu_ordem->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $faseroteiro->nu_ordem->ViewAttributes() ?>>
<?php echo $faseroteiro->nu_ordem->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_ordem" name="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($faseroteiro->nu_ordem->FormValue) ?>">
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" id="o<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($faseroteiro->nu_ordem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $faseroteiro_grid->PageObjName . "_row_" . $faseroteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$faseroteiro_grid->ListOptions->Render("body", "right", $faseroteiro_grid->RowCnt);
?>
	</tr>
<?php if ($faseroteiro->RowType == EW_ROWTYPE_ADD || $faseroteiro->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ffaseroteirogrid.UpdateOpts(<?php echo $faseroteiro_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($faseroteiro->CurrentAction <> "gridadd" || $faseroteiro->CurrentMode == "copy")
		if (!$faseroteiro_grid->Recordset->EOF) $faseroteiro_grid->Recordset->MoveNext();
}
?>
<?php
	if ($faseroteiro->CurrentMode == "add" || $faseroteiro->CurrentMode == "copy" || $faseroteiro->CurrentMode == "edit") {
		$faseroteiro_grid->RowIndex = '$rowindex$';
		$faseroteiro_grid->LoadDefaultValues();

		// Set row properties
		$faseroteiro->ResetAttrs();
		$faseroteiro->RowAttrs = array_merge($faseroteiro->RowAttrs, array('data-rowindex'=>$faseroteiro_grid->RowIndex, 'id'=>'r0_faseroteiro', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($faseroteiro->RowAttrs["class"], "ewTemplate");
		$faseroteiro->RowType = EW_ROWTYPE_ADD;

		// Render row
		$faseroteiro_grid->RenderRow();

		// Render list options
		$faseroteiro_grid->RenderListOptions();
		$faseroteiro_grid->StartRowCnt = 0;
?>
	<tr<?php echo $faseroteiro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$faseroteiro_grid->ListOptions->Render("body", "left", $faseroteiro_grid->RowIndex);
?>
	<?php if ($faseroteiro->no_faseRoteiro->Visible) { // no_faseRoteiro ?>
		<td>
<?php if ($faseroteiro->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_faseRoteiro" name="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" id="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" size="30" maxlength="75" placeholder="<?php echo $faseroteiro->no_faseRoteiro->PlaceHolder ?>" value="<?php echo $faseroteiro->no_faseRoteiro->EditValue ?>"<?php echo $faseroteiro->no_faseRoteiro->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $faseroteiro->no_faseRoteiro->ViewAttributes() ?>>
<?php echo $faseroteiro->no_faseRoteiro->ViewValue ?></span>
<input type="hidden" data-field="x_no_faseRoteiro" name="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" id="x<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" value="<?php echo ew_HtmlEncode($faseroteiro->no_faseRoteiro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_faseRoteiro" name="o<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" id="o<?php echo $faseroteiro_grid->RowIndex ?>_no_faseRoteiro" value="<?php echo ew_HtmlEncode($faseroteiro->no_faseRoteiro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($faseroteiro->pc_distribuicao->Visible) { // pc_distribuicao ?>
		<td>
<?php if ($faseroteiro->CurrentAction <> "F") { ?>
<input type="text" data-field="x_pc_distribuicao" name="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" size="30" placeholder="<?php echo $faseroteiro->pc_distribuicao->PlaceHolder ?>" value="<?php echo $faseroteiro->pc_distribuicao->EditValue ?>"<?php echo $faseroteiro->pc_distribuicao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $faseroteiro->pc_distribuicao->ViewAttributes() ?>>
<?php echo $faseroteiro->pc_distribuicao->ViewValue ?></span>
<input type="hidden" data-field="x_pc_distribuicao" name="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($faseroteiro->pc_distribuicao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_pc_distribuicao" name="o<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" id="o<?php echo $faseroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($faseroteiro->pc_distribuicao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($faseroteiro->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($faseroteiro->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $faseroteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $faseroteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($faseroteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $faseroteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $faseroteiro->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $faseroteiro->ic_ativo->ViewAttributes() ?>>
<?php echo $faseroteiro->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($faseroteiro->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" id="o<?php echo $faseroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($faseroteiro->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($faseroteiro->nu_ordem->Visible) { // nu_ordem ?>
		<td>
<?php if ($faseroteiro->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_ordem" name="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $faseroteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $faseroteiro->nu_ordem->EditValue ?>"<?php echo $faseroteiro->nu_ordem->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $faseroteiro->nu_ordem->ViewAttributes() ?>>
<?php echo $faseroteiro->nu_ordem->ViewValue ?></span>
<input type="hidden" data-field="x_nu_ordem" name="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($faseroteiro->nu_ordem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" id="o<?php echo $faseroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($faseroteiro->nu_ordem->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$faseroteiro_grid->ListOptions->Render("body", "right", $faseroteiro_grid->RowCnt);
?>
<script type="text/javascript">
ffaseroteirogrid.UpdateOpts(<?php echo $faseroteiro_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($faseroteiro->CurrentMode == "add" || $faseroteiro->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $faseroteiro_grid->FormKeyCountName ?>" id="<?php echo $faseroteiro_grid->FormKeyCountName ?>" value="<?php echo $faseroteiro_grid->KeyCount ?>">
<?php echo $faseroteiro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($faseroteiro->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $faseroteiro_grid->FormKeyCountName ?>" id="<?php echo $faseroteiro_grid->FormKeyCountName ?>" value="<?php echo $faseroteiro_grid->KeyCount ?>">
<?php echo $faseroteiro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($faseroteiro->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ffaseroteirogrid">
</div>
<?php

// Close recordset
if ($faseroteiro_grid->Recordset)
	$faseroteiro_grid->Recordset->Close();
?>
<?php if ($faseroteiro_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($faseroteiro_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($faseroteiro->Export == "") { ?>
<script type="text/javascript">
ffaseroteirogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$faseroteiro_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$faseroteiro_grid->Page_Terminate();
?>
