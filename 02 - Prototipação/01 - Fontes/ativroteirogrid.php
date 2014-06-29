<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($ativroteiro_grid)) $ativroteiro_grid = new cativroteiro_grid();

// Page init
$ativroteiro_grid->Page_Init();

// Page main
$ativroteiro_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ativroteiro_grid->Page_Render();
?>
<?php if ($ativroteiro->Export == "") { ?>
<script type="text/javascript">

// Page object
var ativroteiro_grid = new ew_Page("ativroteiro_grid");
ativroteiro_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = ativroteiro_grid.PageID; // For backward compatibility

// Form object
var fativroteirogrid = new ew_Form("fativroteirogrid");
fativroteirogrid.FormKeyCountName = '<?php echo $ativroteiro_grid->FormKeyCountName ?>';

// Validate form
fativroteirogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_ativRoteiro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ativroteiro->no_ativRoteiro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_distribuicao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ativroteiro->pc_distribuicao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ativroteiro->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ordem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ativroteiro->nu_ordem->FldErrMsg()) ?>");

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
fativroteirogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_ativRoteiro", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pc_distribuicao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_ordem", false)) return false;
	return true;
}

// Form_CustomValidate event
fativroteirogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fativroteirogrid.ValidateRequired = true;
<?php } else { ?>
fativroteirogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($ativroteiro->getCurrentMasterTable() == "" && $ativroteiro_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $ativroteiro_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($ativroteiro->CurrentAction == "gridadd") {
	if ($ativroteiro->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$ativroteiro_grid->TotalRecs = $ativroteiro->SelectRecordCount();
			$ativroteiro_grid->Recordset = $ativroteiro_grid->LoadRecordset($ativroteiro_grid->StartRec-1, $ativroteiro_grid->DisplayRecs);
		} else {
			if ($ativroteiro_grid->Recordset = $ativroteiro_grid->LoadRecordset())
				$ativroteiro_grid->TotalRecs = $ativroteiro_grid->Recordset->RecordCount();
		}
		$ativroteiro_grid->StartRec = 1;
		$ativroteiro_grid->DisplayRecs = $ativroteiro_grid->TotalRecs;
	} else {
		$ativroteiro->CurrentFilter = "0=1";
		$ativroteiro_grid->StartRec = 1;
		$ativroteiro_grid->DisplayRecs = $ativroteiro->GridAddRowCount;
	}
	$ativroteiro_grid->TotalRecs = $ativroteiro_grid->DisplayRecs;
	$ativroteiro_grid->StopRec = $ativroteiro_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$ativroteiro_grid->TotalRecs = $ativroteiro->SelectRecordCount();
	} else {
		if ($ativroteiro_grid->Recordset = $ativroteiro_grid->LoadRecordset())
			$ativroteiro_grid->TotalRecs = $ativroteiro_grid->Recordset->RecordCount();
	}
	$ativroteiro_grid->StartRec = 1;
	$ativroteiro_grid->DisplayRecs = $ativroteiro_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$ativroteiro_grid->Recordset = $ativroteiro_grid->LoadRecordset($ativroteiro_grid->StartRec-1, $ativroteiro_grid->DisplayRecs);
}
$ativroteiro_grid->RenderOtherOptions();
?>
<?php $ativroteiro_grid->ShowPageHeader(); ?>
<?php
$ativroteiro_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fativroteirogrid" class="ewForm form-horizontal">
<div id="gmp_ativroteiro" class="ewGridMiddlePanel">
<table id="tbl_ativroteirogrid" class="ewTable ewTableSeparate">
<?php echo $ativroteiro->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$ativroteiro_grid->RenderListOptions();

// Render list options (header, left)
$ativroteiro_grid->ListOptions->Render("header", "left");
?>
<?php if ($ativroteiro->no_ativRoteiro->Visible) { // no_ativRoteiro ?>
	<?php if ($ativroteiro->SortUrl($ativroteiro->no_ativRoteiro) == "") { ?>
		<td><div id="elh_ativroteiro_no_ativRoteiro" class="ativroteiro_no_ativRoteiro"><div class="ewTableHeaderCaption"><?php echo $ativroteiro->no_ativRoteiro->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ativroteiro_no_ativRoteiro" class="ativroteiro_no_ativRoteiro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ativroteiro->no_ativRoteiro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ativroteiro->no_ativRoteiro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ativroteiro->no_ativRoteiro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ativroteiro->pc_distribuicao->Visible) { // pc_distribuicao ?>
	<?php if ($ativroteiro->SortUrl($ativroteiro->pc_distribuicao) == "") { ?>
		<td><div id="elh_ativroteiro_pc_distribuicao" class="ativroteiro_pc_distribuicao"><div class="ewTableHeaderCaption"><?php echo $ativroteiro->pc_distribuicao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ativroteiro_pc_distribuicao" class="ativroteiro_pc_distribuicao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ativroteiro->pc_distribuicao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ativroteiro->pc_distribuicao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ativroteiro->pc_distribuicao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ativroteiro->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($ativroteiro->SortUrl($ativroteiro->ic_ativo) == "") { ?>
		<td><div id="elh_ativroteiro_ic_ativo" class="ativroteiro_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $ativroteiro->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ativroteiro_ic_ativo" class="ativroteiro_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ativroteiro->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ativroteiro->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ativroteiro->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ativroteiro->nu_ordem->Visible) { // nu_ordem ?>
	<?php if ($ativroteiro->SortUrl($ativroteiro->nu_ordem) == "") { ?>
		<td><div id="elh_ativroteiro_nu_ordem" class="ativroteiro_nu_ordem"><div class="ewTableHeaderCaption"><?php echo $ativroteiro->nu_ordem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ativroteiro_nu_ordem" class="ativroteiro_nu_ordem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ativroteiro->nu_ordem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ativroteiro->nu_ordem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ativroteiro->nu_ordem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ativroteiro_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$ativroteiro_grid->StartRec = 1;
$ativroteiro_grid->StopRec = $ativroteiro_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($ativroteiro_grid->FormKeyCountName) && ($ativroteiro->CurrentAction == "gridadd" || $ativroteiro->CurrentAction == "gridedit" || $ativroteiro->CurrentAction == "F")) {
		$ativroteiro_grid->KeyCount = $objForm->GetValue($ativroteiro_grid->FormKeyCountName);
		$ativroteiro_grid->StopRec = $ativroteiro_grid->StartRec + $ativroteiro_grid->KeyCount - 1;
	}
}
$ativroteiro_grid->RecCnt = $ativroteiro_grid->StartRec - 1;
if ($ativroteiro_grid->Recordset && !$ativroteiro_grid->Recordset->EOF) {
	$ativroteiro_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $ativroteiro_grid->StartRec > 1)
		$ativroteiro_grid->Recordset->Move($ativroteiro_grid->StartRec - 1);
} elseif (!$ativroteiro->AllowAddDeleteRow && $ativroteiro_grid->StopRec == 0) {
	$ativroteiro_grid->StopRec = $ativroteiro->GridAddRowCount;
}

// Initialize aggregate
$ativroteiro->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ativroteiro->ResetAttrs();
$ativroteiro_grid->RenderRow();
if ($ativroteiro->CurrentAction == "gridadd")
	$ativroteiro_grid->RowIndex = 0;
if ($ativroteiro->CurrentAction == "gridedit")
	$ativroteiro_grid->RowIndex = 0;
while ($ativroteiro_grid->RecCnt < $ativroteiro_grid->StopRec) {
	$ativroteiro_grid->RecCnt++;
	if (intval($ativroteiro_grid->RecCnt) >= intval($ativroteiro_grid->StartRec)) {
		$ativroteiro_grid->RowCnt++;
		if ($ativroteiro->CurrentAction == "gridadd" || $ativroteiro->CurrentAction == "gridedit" || $ativroteiro->CurrentAction == "F") {
			$ativroteiro_grid->RowIndex++;
			$objForm->Index = $ativroteiro_grid->RowIndex;
			if ($objForm->HasValue($ativroteiro_grid->FormActionName))
				$ativroteiro_grid->RowAction = strval($objForm->GetValue($ativroteiro_grid->FormActionName));
			elseif ($ativroteiro->CurrentAction == "gridadd")
				$ativroteiro_grid->RowAction = "insert";
			else
				$ativroteiro_grid->RowAction = "";
		}

		// Set up key count
		$ativroteiro_grid->KeyCount = $ativroteiro_grid->RowIndex;

		// Init row class and style
		$ativroteiro->ResetAttrs();
		$ativroteiro->CssClass = "";
		if ($ativroteiro->CurrentAction == "gridadd") {
			if ($ativroteiro->CurrentMode == "copy") {
				$ativroteiro_grid->LoadRowValues($ativroteiro_grid->Recordset); // Load row values
				$ativroteiro_grid->SetRecordKey($ativroteiro_grid->RowOldKey, $ativroteiro_grid->Recordset); // Set old record key
			} else {
				$ativroteiro_grid->LoadDefaultValues(); // Load default values
				$ativroteiro_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$ativroteiro_grid->LoadRowValues($ativroteiro_grid->Recordset); // Load row values
		}
		$ativroteiro->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($ativroteiro->CurrentAction == "gridadd") // Grid add
			$ativroteiro->RowType = EW_ROWTYPE_ADD; // Render add
		if ($ativroteiro->CurrentAction == "gridadd" && $ativroteiro->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$ativroteiro_grid->RestoreCurrentRowFormValues($ativroteiro_grid->RowIndex); // Restore form values
		if ($ativroteiro->CurrentAction == "gridedit") { // Grid edit
			if ($ativroteiro->EventCancelled) {
				$ativroteiro_grid->RestoreCurrentRowFormValues($ativroteiro_grid->RowIndex); // Restore form values
			}
			if ($ativroteiro_grid->RowAction == "insert")
				$ativroteiro->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$ativroteiro->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($ativroteiro->CurrentAction == "gridedit" && ($ativroteiro->RowType == EW_ROWTYPE_EDIT || $ativroteiro->RowType == EW_ROWTYPE_ADD) && $ativroteiro->EventCancelled) // Update failed
			$ativroteiro_grid->RestoreCurrentRowFormValues($ativroteiro_grid->RowIndex); // Restore form values
		if ($ativroteiro->RowType == EW_ROWTYPE_EDIT) // Edit row
			$ativroteiro_grid->EditRowCnt++;
		if ($ativroteiro->CurrentAction == "F") // Confirm row
			$ativroteiro_grid->RestoreCurrentRowFormValues($ativroteiro_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$ativroteiro->RowAttrs = array_merge($ativroteiro->RowAttrs, array('data-rowindex'=>$ativroteiro_grid->RowCnt, 'id'=>'r' . $ativroteiro_grid->RowCnt . '_ativroteiro', 'data-rowtype'=>$ativroteiro->RowType));

		// Render row
		$ativroteiro_grid->RenderRow();

		// Render list options
		$ativroteiro_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($ativroteiro_grid->RowAction <> "delete" && $ativroteiro_grid->RowAction <> "insertdelete" && !($ativroteiro_grid->RowAction == "insert" && $ativroteiro->CurrentAction == "F" && $ativroteiro_grid->EmptyRow())) {
?>
	<tr<?php echo $ativroteiro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ativroteiro_grid->ListOptions->Render("body", "left", $ativroteiro_grid->RowCnt);
?>
	<?php if ($ativroteiro->no_ativRoteiro->Visible) { // no_ativRoteiro ?>
		<td<?php echo $ativroteiro->no_ativRoteiro->CellAttributes() ?>>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ativroteiro_grid->RowCnt ?>_ativroteiro_no_ativRoteiro" class="control-group ativroteiro_no_ativRoteiro">
<input type="text" data-field="x_no_ativRoteiro" name="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" id="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" size="30" maxlength="75" placeholder="<?php echo $ativroteiro->no_ativRoteiro->PlaceHolder ?>" value="<?php echo $ativroteiro->no_ativRoteiro->EditValue ?>"<?php echo $ativroteiro->no_ativRoteiro->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_ativRoteiro" name="o<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" id="o<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->no_ativRoteiro->OldValue) ?>">
<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ativroteiro_grid->RowCnt ?>_ativroteiro_no_ativRoteiro" class="control-group ativroteiro_no_ativRoteiro">
<input type="text" data-field="x_no_ativRoteiro" name="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" id="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" size="30" maxlength="75" placeholder="<?php echo $ativroteiro->no_ativRoteiro->PlaceHolder ?>" value="<?php echo $ativroteiro->no_ativRoteiro->EditValue ?>"<?php echo $ativroteiro->no_ativRoteiro->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ativroteiro->no_ativRoteiro->ViewAttributes() ?>>
<?php echo $ativroteiro->no_ativRoteiro->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_ativRoteiro" name="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" id="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->no_ativRoteiro->FormValue) ?>">
<input type="hidden" data-field="x_no_ativRoteiro" name="o<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" id="o<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->no_ativRoteiro->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ativroteiro_grid->PageObjName . "_row_" . $ativroteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_ativRoteiro" name="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ativRoteiro" id="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ativRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->nu_ativRoteiro->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_ativRoteiro" name="o<?php echo $ativroteiro_grid->RowIndex ?>_nu_ativRoteiro" id="o<?php echo $ativroteiro_grid->RowIndex ?>_nu_ativRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->nu_ativRoteiro->OldValue) ?>">
<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_EDIT || $ativroteiro->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_ativRoteiro" name="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ativRoteiro" id="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ativRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->nu_ativRoteiro->CurrentValue) ?>">
<?php } ?>
	<?php if ($ativroteiro->pc_distribuicao->Visible) { // pc_distribuicao ?>
		<td<?php echo $ativroteiro->pc_distribuicao->CellAttributes() ?>>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ativroteiro_grid->RowCnt ?>_ativroteiro_pc_distribuicao" class="control-group ativroteiro_pc_distribuicao">
<input type="text" data-field="x_pc_distribuicao" name="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" size="30" placeholder="<?php echo $ativroteiro->pc_distribuicao->PlaceHolder ?>" value="<?php echo $ativroteiro->pc_distribuicao->EditValue ?>"<?php echo $ativroteiro->pc_distribuicao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_pc_distribuicao" name="o<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" id="o<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($ativroteiro->pc_distribuicao->OldValue) ?>">
<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ativroteiro_grid->RowCnt ?>_ativroteiro_pc_distribuicao" class="control-group ativroteiro_pc_distribuicao">
<input type="text" data-field="x_pc_distribuicao" name="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" size="30" placeholder="<?php echo $ativroteiro->pc_distribuicao->PlaceHolder ?>" value="<?php echo $ativroteiro->pc_distribuicao->EditValue ?>"<?php echo $ativroteiro->pc_distribuicao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ativroteiro->pc_distribuicao->ViewAttributes() ?>>
<?php echo $ativroteiro->pc_distribuicao->ListViewValue() ?></span>
<input type="hidden" data-field="x_pc_distribuicao" name="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($ativroteiro->pc_distribuicao->FormValue) ?>">
<input type="hidden" data-field="x_pc_distribuicao" name="o<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" id="o<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($ativroteiro->pc_distribuicao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ativroteiro_grid->PageObjName . "_row_" . $ativroteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ativroteiro->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $ativroteiro->ic_ativo->CellAttributes() ?>>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ativroteiro_grid->RowCnt ?>_ativroteiro_ic_ativo" class="control-group ativroteiro_ic_ativo">
<div id="tp_x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $ativroteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ativroteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ativroteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ativroteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ativroteiro->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="o<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ativroteiro->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ativroteiro_grid->RowCnt ?>_ativroteiro_ic_ativo" class="control-group ativroteiro_ic_ativo">
<div id="tp_x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $ativroteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ativroteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ativroteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ativroteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ativroteiro->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ativroteiro->ic_ativo->ViewAttributes() ?>>
<?php echo $ativroteiro->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ativroteiro->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="o<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ativroteiro->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ativroteiro_grid->PageObjName . "_row_" . $ativroteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ativroteiro->nu_ordem->Visible) { // nu_ordem ?>
		<td<?php echo $ativroteiro->nu_ordem->CellAttributes() ?>>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ativroteiro_grid->RowCnt ?>_ativroteiro_nu_ordem" class="control-group ativroteiro_nu_ordem">
<input type="text" data-field="x_nu_ordem" name="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $ativroteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $ativroteiro->nu_ordem->EditValue ?>"<?php echo $ativroteiro->nu_ordem->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" id="o<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($ativroteiro->nu_ordem->OldValue) ?>">
<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ativroteiro_grid->RowCnt ?>_ativroteiro_nu_ordem" class="control-group ativroteiro_nu_ordem">
<input type="text" data-field="x_nu_ordem" name="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $ativroteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $ativroteiro->nu_ordem->EditValue ?>"<?php echo $ativroteiro->nu_ordem->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ativroteiro->nu_ordem->ViewAttributes() ?>>
<?php echo $ativroteiro->nu_ordem->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_ordem" name="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($ativroteiro->nu_ordem->FormValue) ?>">
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" id="o<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($ativroteiro->nu_ordem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ativroteiro_grid->PageObjName . "_row_" . $ativroteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$ativroteiro_grid->ListOptions->Render("body", "right", $ativroteiro_grid->RowCnt);
?>
	</tr>
<?php if ($ativroteiro->RowType == EW_ROWTYPE_ADD || $ativroteiro->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fativroteirogrid.UpdateOpts(<?php echo $ativroteiro_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($ativroteiro->CurrentAction <> "gridadd" || $ativroteiro->CurrentMode == "copy")
		if (!$ativroteiro_grid->Recordset->EOF) $ativroteiro_grid->Recordset->MoveNext();
}
?>
<?php
	if ($ativroteiro->CurrentMode == "add" || $ativroteiro->CurrentMode == "copy" || $ativroteiro->CurrentMode == "edit") {
		$ativroteiro_grid->RowIndex = '$rowindex$';
		$ativroteiro_grid->LoadDefaultValues();

		// Set row properties
		$ativroteiro->ResetAttrs();
		$ativroteiro->RowAttrs = array_merge($ativroteiro->RowAttrs, array('data-rowindex'=>$ativroteiro_grid->RowIndex, 'id'=>'r0_ativroteiro', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($ativroteiro->RowAttrs["class"], "ewTemplate");
		$ativroteiro->RowType = EW_ROWTYPE_ADD;

		// Render row
		$ativroteiro_grid->RenderRow();

		// Render list options
		$ativroteiro_grid->RenderListOptions();
		$ativroteiro_grid->StartRowCnt = 0;
?>
	<tr<?php echo $ativroteiro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ativroteiro_grid->ListOptions->Render("body", "left", $ativroteiro_grid->RowIndex);
?>
	<?php if ($ativroteiro->no_ativRoteiro->Visible) { // no_ativRoteiro ?>
		<td>
<?php if ($ativroteiro->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_ativRoteiro" name="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" id="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" size="30" maxlength="75" placeholder="<?php echo $ativroteiro->no_ativRoteiro->PlaceHolder ?>" value="<?php echo $ativroteiro->no_ativRoteiro->EditValue ?>"<?php echo $ativroteiro->no_ativRoteiro->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ativroteiro->no_ativRoteiro->ViewAttributes() ?>>
<?php echo $ativroteiro->no_ativRoteiro->ViewValue ?></span>
<input type="hidden" data-field="x_no_ativRoteiro" name="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" id="x<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->no_ativRoteiro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_ativRoteiro" name="o<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" id="o<?php echo $ativroteiro_grid->RowIndex ?>_no_ativRoteiro" value="<?php echo ew_HtmlEncode($ativroteiro->no_ativRoteiro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ativroteiro->pc_distribuicao->Visible) { // pc_distribuicao ?>
		<td>
<?php if ($ativroteiro->CurrentAction <> "F") { ?>
<input type="text" data-field="x_pc_distribuicao" name="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" size="30" placeholder="<?php echo $ativroteiro->pc_distribuicao->PlaceHolder ?>" value="<?php echo $ativroteiro->pc_distribuicao->EditValue ?>"<?php echo $ativroteiro->pc_distribuicao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ativroteiro->pc_distribuicao->ViewAttributes() ?>>
<?php echo $ativroteiro->pc_distribuicao->ViewValue ?></span>
<input type="hidden" data-field="x_pc_distribuicao" name="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" id="x<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($ativroteiro->pc_distribuicao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_pc_distribuicao" name="o<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" id="o<?php echo $ativroteiro_grid->RowIndex ?>_pc_distribuicao" value="<?php echo ew_HtmlEncode($ativroteiro->pc_distribuicao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ativroteiro->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($ativroteiro->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $ativroteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ativroteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ativroteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ativroteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ativroteiro->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $ativroteiro->ic_ativo->ViewAttributes() ?>>
<?php echo $ativroteiro->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ativroteiro->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" id="o<?php echo $ativroteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ativroteiro->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ativroteiro->nu_ordem->Visible) { // nu_ordem ?>
		<td>
<?php if ($ativroteiro->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_ordem" name="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $ativroteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $ativroteiro->nu_ordem->EditValue ?>"<?php echo $ativroteiro->nu_ordem->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ativroteiro->nu_ordem->ViewAttributes() ?>>
<?php echo $ativroteiro->nu_ordem->ViewValue ?></span>
<input type="hidden" data-field="x_nu_ordem" name="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($ativroteiro->nu_ordem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" id="o<?php echo $ativroteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($ativroteiro->nu_ordem->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ativroteiro_grid->ListOptions->Render("body", "right", $ativroteiro_grid->RowCnt);
?>
<script type="text/javascript">
fativroteirogrid.UpdateOpts(<?php echo $ativroteiro_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($ativroteiro->CurrentMode == "add" || $ativroteiro->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $ativroteiro_grid->FormKeyCountName ?>" id="<?php echo $ativroteiro_grid->FormKeyCountName ?>" value="<?php echo $ativroteiro_grid->KeyCount ?>">
<?php echo $ativroteiro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ativroteiro->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $ativroteiro_grid->FormKeyCountName ?>" id="<?php echo $ativroteiro_grid->FormKeyCountName ?>" value="<?php echo $ativroteiro_grid->KeyCount ?>">
<?php echo $ativroteiro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ativroteiro->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fativroteirogrid">
</div>
<?php

// Close recordset
if ($ativroteiro_grid->Recordset)
	$ativroteiro_grid->Recordset->Close();
?>
<?php if ($ativroteiro_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($ativroteiro_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($ativroteiro->Export == "") { ?>
<script type="text/javascript">
fativroteirogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$ativroteiro_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$ativroteiro_grid->Page_Terminate();
?>
