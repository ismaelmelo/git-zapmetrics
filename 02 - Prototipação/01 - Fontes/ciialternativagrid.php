<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($ciialternativa_grid)) $ciialternativa_grid = new cciialternativa_grid();

// Page init
$ciialternativa_grid->Page_Init();

// Page main
$ciialternativa_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ciialternativa_grid->Page_Render();
?>
<?php if ($ciialternativa->Export == "") { ?>
<script type="text/javascript">

// Page object
var ciialternativa_grid = new ew_Page("ciialternativa_grid");
ciialternativa_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = ciialternativa_grid.PageID; // For backward compatibility

// Form object
var fciialternativagrid = new ew_Form("fciialternativagrid");
fciialternativagrid.FormKeyCountName = '<?php echo $ciialternativa_grid->FormKeyCountName ?>';

// Validate form
fciialternativagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_alternativa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ciialternativa->no_alternativa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_alternativa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ciialternativa->vr_alternativa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_alternativa");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ciialternativa->vr_alternativa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_peso");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ciialternativa->nu_peso->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ciialternativa->ic_ativo->FldCaption()) ?>");

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
fciialternativagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_alternativa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_alternativa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_peso", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
fciialternativagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fciialternativagrid.ValidateRequired = true;
<?php } else { ?>
fciialternativagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($ciialternativa->getCurrentMasterTable() == "" && $ciialternativa_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $ciialternativa_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($ciialternativa->CurrentAction == "gridadd") {
	if ($ciialternativa->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$ciialternativa_grid->TotalRecs = $ciialternativa->SelectRecordCount();
			$ciialternativa_grid->Recordset = $ciialternativa_grid->LoadRecordset($ciialternativa_grid->StartRec-1, $ciialternativa_grid->DisplayRecs);
		} else {
			if ($ciialternativa_grid->Recordset = $ciialternativa_grid->LoadRecordset())
				$ciialternativa_grid->TotalRecs = $ciialternativa_grid->Recordset->RecordCount();
		}
		$ciialternativa_grid->StartRec = 1;
		$ciialternativa_grid->DisplayRecs = $ciialternativa_grid->TotalRecs;
	} else {
		$ciialternativa->CurrentFilter = "0=1";
		$ciialternativa_grid->StartRec = 1;
		$ciialternativa_grid->DisplayRecs = $ciialternativa->GridAddRowCount;
	}
	$ciialternativa_grid->TotalRecs = $ciialternativa_grid->DisplayRecs;
	$ciialternativa_grid->StopRec = $ciialternativa_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$ciialternativa_grid->TotalRecs = $ciialternativa->SelectRecordCount();
	} else {
		if ($ciialternativa_grid->Recordset = $ciialternativa_grid->LoadRecordset())
			$ciialternativa_grid->TotalRecs = $ciialternativa_grid->Recordset->RecordCount();
	}
	$ciialternativa_grid->StartRec = 1;
	$ciialternativa_grid->DisplayRecs = $ciialternativa_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$ciialternativa_grid->Recordset = $ciialternativa_grid->LoadRecordset($ciialternativa_grid->StartRec-1, $ciialternativa_grid->DisplayRecs);
}
$ciialternativa_grid->RenderOtherOptions();
?>
<?php $ciialternativa_grid->ShowPageHeader(); ?>
<?php
$ciialternativa_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fciialternativagrid" class="ewForm form-horizontal">
<div id="gmp_ciialternativa" class="ewGridMiddlePanel">
<table id="tbl_ciialternativagrid" class="ewTable ewTableSeparate">
<?php echo $ciialternativa->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$ciialternativa_grid->RenderListOptions();

// Render list options (header, left)
$ciialternativa_grid->ListOptions->Render("header", "left");
?>
<?php if ($ciialternativa->no_alternativa->Visible) { // no_alternativa ?>
	<?php if ($ciialternativa->SortUrl($ciialternativa->no_alternativa) == "") { ?>
		<td><div id="elh_ciialternativa_no_alternativa" class="ciialternativa_no_alternativa"><div class="ewTableHeaderCaption"><?php echo $ciialternativa->no_alternativa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ciialternativa_no_alternativa" class="ciialternativa_no_alternativa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ciialternativa->no_alternativa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ciialternativa->no_alternativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ciialternativa->no_alternativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ciialternativa->vr_alternativa->Visible) { // vr_alternativa ?>
	<?php if ($ciialternativa->SortUrl($ciialternativa->vr_alternativa) == "") { ?>
		<td><div id="elh_ciialternativa_vr_alternativa" class="ciialternativa_vr_alternativa"><div class="ewTableHeaderCaption"><?php echo $ciialternativa->vr_alternativa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ciialternativa_vr_alternativa" class="ciialternativa_vr_alternativa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ciialternativa->vr_alternativa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ciialternativa->vr_alternativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ciialternativa->vr_alternativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ciialternativa->nu_peso->Visible) { // nu_peso ?>
	<?php if ($ciialternativa->SortUrl($ciialternativa->nu_peso) == "") { ?>
		<td><div id="elh_ciialternativa_nu_peso" class="ciialternativa_nu_peso"><div class="ewTableHeaderCaption"><?php echo $ciialternativa->nu_peso->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ciialternativa_nu_peso" class="ciialternativa_nu_peso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ciialternativa->nu_peso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ciialternativa->nu_peso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ciialternativa->nu_peso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ciialternativa->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($ciialternativa->SortUrl($ciialternativa->ic_ativo) == "") { ?>
		<td><div id="elh_ciialternativa_ic_ativo" class="ciialternativa_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $ciialternativa->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ciialternativa_ic_ativo" class="ciialternativa_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ciialternativa->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ciialternativa->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ciialternativa->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ciialternativa_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$ciialternativa_grid->StartRec = 1;
$ciialternativa_grid->StopRec = $ciialternativa_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($ciialternativa_grid->FormKeyCountName) && ($ciialternativa->CurrentAction == "gridadd" || $ciialternativa->CurrentAction == "gridedit" || $ciialternativa->CurrentAction == "F")) {
		$ciialternativa_grid->KeyCount = $objForm->GetValue($ciialternativa_grid->FormKeyCountName);
		$ciialternativa_grid->StopRec = $ciialternativa_grid->StartRec + $ciialternativa_grid->KeyCount - 1;
	}
}
$ciialternativa_grid->RecCnt = $ciialternativa_grid->StartRec - 1;
if ($ciialternativa_grid->Recordset && !$ciialternativa_grid->Recordset->EOF) {
	$ciialternativa_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $ciialternativa_grid->StartRec > 1)
		$ciialternativa_grid->Recordset->Move($ciialternativa_grid->StartRec - 1);
} elseif (!$ciialternativa->AllowAddDeleteRow && $ciialternativa_grid->StopRec == 0) {
	$ciialternativa_grid->StopRec = $ciialternativa->GridAddRowCount;
}

// Initialize aggregate
$ciialternativa->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ciialternativa->ResetAttrs();
$ciialternativa_grid->RenderRow();
if ($ciialternativa->CurrentAction == "gridadd")
	$ciialternativa_grid->RowIndex = 0;
if ($ciialternativa->CurrentAction == "gridedit")
	$ciialternativa_grid->RowIndex = 0;
while ($ciialternativa_grid->RecCnt < $ciialternativa_grid->StopRec) {
	$ciialternativa_grid->RecCnt++;
	if (intval($ciialternativa_grid->RecCnt) >= intval($ciialternativa_grid->StartRec)) {
		$ciialternativa_grid->RowCnt++;
		if ($ciialternativa->CurrentAction == "gridadd" || $ciialternativa->CurrentAction == "gridedit" || $ciialternativa->CurrentAction == "F") {
			$ciialternativa_grid->RowIndex++;
			$objForm->Index = $ciialternativa_grid->RowIndex;
			if ($objForm->HasValue($ciialternativa_grid->FormActionName))
				$ciialternativa_grid->RowAction = strval($objForm->GetValue($ciialternativa_grid->FormActionName));
			elseif ($ciialternativa->CurrentAction == "gridadd")
				$ciialternativa_grid->RowAction = "insert";
			else
				$ciialternativa_grid->RowAction = "";
		}

		// Set up key count
		$ciialternativa_grid->KeyCount = $ciialternativa_grid->RowIndex;

		// Init row class and style
		$ciialternativa->ResetAttrs();
		$ciialternativa->CssClass = "";
		if ($ciialternativa->CurrentAction == "gridadd") {
			if ($ciialternativa->CurrentMode == "copy") {
				$ciialternativa_grid->LoadRowValues($ciialternativa_grid->Recordset); // Load row values
				$ciialternativa_grid->SetRecordKey($ciialternativa_grid->RowOldKey, $ciialternativa_grid->Recordset); // Set old record key
			} else {
				$ciialternativa_grid->LoadDefaultValues(); // Load default values
				$ciialternativa_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$ciialternativa_grid->LoadRowValues($ciialternativa_grid->Recordset); // Load row values
		}
		$ciialternativa->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($ciialternativa->CurrentAction == "gridadd") // Grid add
			$ciialternativa->RowType = EW_ROWTYPE_ADD; // Render add
		if ($ciialternativa->CurrentAction == "gridadd" && $ciialternativa->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$ciialternativa_grid->RestoreCurrentRowFormValues($ciialternativa_grid->RowIndex); // Restore form values
		if ($ciialternativa->CurrentAction == "gridedit") { // Grid edit
			if ($ciialternativa->EventCancelled) {
				$ciialternativa_grid->RestoreCurrentRowFormValues($ciialternativa_grid->RowIndex); // Restore form values
			}
			if ($ciialternativa_grid->RowAction == "insert")
				$ciialternativa->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$ciialternativa->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($ciialternativa->CurrentAction == "gridedit" && ($ciialternativa->RowType == EW_ROWTYPE_EDIT || $ciialternativa->RowType == EW_ROWTYPE_ADD) && $ciialternativa->EventCancelled) // Update failed
			$ciialternativa_grid->RestoreCurrentRowFormValues($ciialternativa_grid->RowIndex); // Restore form values
		if ($ciialternativa->RowType == EW_ROWTYPE_EDIT) // Edit row
			$ciialternativa_grid->EditRowCnt++;
		if ($ciialternativa->CurrentAction == "F") // Confirm row
			$ciialternativa_grid->RestoreCurrentRowFormValues($ciialternativa_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$ciialternativa->RowAttrs = array_merge($ciialternativa->RowAttrs, array('data-rowindex'=>$ciialternativa_grid->RowCnt, 'id'=>'r' . $ciialternativa_grid->RowCnt . '_ciialternativa', 'data-rowtype'=>$ciialternativa->RowType));

		// Render row
		$ciialternativa_grid->RenderRow();

		// Render list options
		$ciialternativa_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($ciialternativa_grid->RowAction <> "delete" && $ciialternativa_grid->RowAction <> "insertdelete" && !($ciialternativa_grid->RowAction == "insert" && $ciialternativa->CurrentAction == "F" && $ciialternativa_grid->EmptyRow())) {
?>
	<tr<?php echo $ciialternativa->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ciialternativa_grid->ListOptions->Render("body", "left", $ciialternativa_grid->RowCnt);
?>
	<?php if ($ciialternativa->no_alternativa->Visible) { // no_alternativa ?>
		<td<?php echo $ciialternativa->no_alternativa->CellAttributes() ?>>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ciialternativa_grid->RowCnt ?>_ciialternativa_no_alternativa" class="control-group ciialternativa_no_alternativa">
<input type="text" data-field="x_no_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" size="30" maxlength="100" placeholder="<?php echo $ciialternativa->no_alternativa->PlaceHolder ?>" value="<?php echo $ciialternativa->no_alternativa->EditValue ?>"<?php echo $ciialternativa->no_alternativa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_alternativa" name="o<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" id="o<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->no_alternativa->OldValue) ?>">
<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ciialternativa_grid->RowCnt ?>_ciialternativa_no_alternativa" class="control-group ciialternativa_no_alternativa">
<input type="text" data-field="x_no_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" size="30" maxlength="100" placeholder="<?php echo $ciialternativa->no_alternativa->PlaceHolder ?>" value="<?php echo $ciialternativa->no_alternativa->EditValue ?>"<?php echo $ciialternativa->no_alternativa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ciialternativa->no_alternativa->ViewAttributes() ?>>
<?php echo $ciialternativa->no_alternativa->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->no_alternativa->FormValue) ?>">
<input type="hidden" data-field="x_no_alternativa" name="o<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" id="o<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->no_alternativa->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ciialternativa_grid->PageObjName . "_row_" . $ciialternativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->nu_alternativa->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_alternativa" name="o<?php echo $ciialternativa_grid->RowIndex ?>_nu_alternativa" id="o<?php echo $ciialternativa_grid->RowIndex ?>_nu_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->nu_alternativa->OldValue) ?>">
<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_EDIT || $ciialternativa->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->nu_alternativa->CurrentValue) ?>">
<?php } ?>
	<?php if ($ciialternativa->vr_alternativa->Visible) { // vr_alternativa ?>
		<td<?php echo $ciialternativa->vr_alternativa->CellAttributes() ?>>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ciialternativa_grid->RowCnt ?>_ciialternativa_vr_alternativa" class="control-group ciialternativa_vr_alternativa">
<input type="text" data-field="x_vr_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" size="30" placeholder="<?php echo $ciialternativa->vr_alternativa->PlaceHolder ?>" value="<?php echo $ciialternativa->vr_alternativa->EditValue ?>"<?php echo $ciialternativa->vr_alternativa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_alternativa" name="o<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" id="o<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->vr_alternativa->OldValue) ?>">
<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ciialternativa_grid->RowCnt ?>_ciialternativa_vr_alternativa" class="control-group ciialternativa_vr_alternativa">
<input type="text" data-field="x_vr_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" size="30" placeholder="<?php echo $ciialternativa->vr_alternativa->PlaceHolder ?>" value="<?php echo $ciialternativa->vr_alternativa->EditValue ?>"<?php echo $ciialternativa->vr_alternativa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ciialternativa->vr_alternativa->ViewAttributes() ?>>
<?php echo $ciialternativa->vr_alternativa->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->vr_alternativa->FormValue) ?>">
<input type="hidden" data-field="x_vr_alternativa" name="o<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" id="o<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->vr_alternativa->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ciialternativa_grid->PageObjName . "_row_" . $ciialternativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ciialternativa->nu_peso->Visible) { // nu_peso ?>
		<td<?php echo $ciialternativa->nu_peso->CellAttributes() ?>>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ciialternativa_grid->RowCnt ?>_ciialternativa_nu_peso" class="control-group ciialternativa_nu_peso">
<input type="text" data-field="x_nu_peso" name="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" id="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" size="30" placeholder="<?php echo $ciialternativa->nu_peso->PlaceHolder ?>" value="<?php echo $ciialternativa->nu_peso->EditValue ?>"<?php echo $ciialternativa->nu_peso->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_peso" name="o<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" id="o<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" value="<?php echo ew_HtmlEncode($ciialternativa->nu_peso->OldValue) ?>">
<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ciialternativa_grid->RowCnt ?>_ciialternativa_nu_peso" class="control-group ciialternativa_nu_peso">
<input type="text" data-field="x_nu_peso" name="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" id="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" size="30" placeholder="<?php echo $ciialternativa->nu_peso->PlaceHolder ?>" value="<?php echo $ciialternativa->nu_peso->EditValue ?>"<?php echo $ciialternativa->nu_peso->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ciialternativa->nu_peso->ViewAttributes() ?>>
<?php echo $ciialternativa->nu_peso->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_peso" name="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" id="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" value="<?php echo ew_HtmlEncode($ciialternativa->nu_peso->FormValue) ?>">
<input type="hidden" data-field="x_nu_peso" name="o<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" id="o<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" value="<?php echo ew_HtmlEncode($ciialternativa->nu_peso->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ciialternativa_grid->PageObjName . "_row_" . $ciialternativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ciialternativa->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $ciialternativa->ic_ativo->CellAttributes() ?>>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ciialternativa_grid->RowCnt ?>_ciialternativa_ic_ativo" class="control-group ciialternativa_ic_ativo">
<div id="tp_x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $ciialternativa->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ciialternativa->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ciialternativa->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ciialternativa->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ciialternativa->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="o<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ciialternativa->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ciialternativa_grid->RowCnt ?>_ciialternativa_ic_ativo" class="control-group ciialternativa_ic_ativo">
<div id="tp_x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $ciialternativa->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ciialternativa->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ciialternativa->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ciialternativa->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ciialternativa->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ciialternativa->ic_ativo->ViewAttributes() ?>>
<?php echo $ciialternativa->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ciialternativa->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="o<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ciialternativa->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ciialternativa_grid->PageObjName . "_row_" . $ciialternativa_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$ciialternativa_grid->ListOptions->Render("body", "right", $ciialternativa_grid->RowCnt);
?>
	</tr>
<?php if ($ciialternativa->RowType == EW_ROWTYPE_ADD || $ciialternativa->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fciialternativagrid.UpdateOpts(<?php echo $ciialternativa_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($ciialternativa->CurrentAction <> "gridadd" || $ciialternativa->CurrentMode == "copy")
		if (!$ciialternativa_grid->Recordset->EOF) $ciialternativa_grid->Recordset->MoveNext();
}
?>
<?php
	if ($ciialternativa->CurrentMode == "add" || $ciialternativa->CurrentMode == "copy" || $ciialternativa->CurrentMode == "edit") {
		$ciialternativa_grid->RowIndex = '$rowindex$';
		$ciialternativa_grid->LoadDefaultValues();

		// Set row properties
		$ciialternativa->ResetAttrs();
		$ciialternativa->RowAttrs = array_merge($ciialternativa->RowAttrs, array('data-rowindex'=>$ciialternativa_grid->RowIndex, 'id'=>'r0_ciialternativa', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($ciialternativa->RowAttrs["class"], "ewTemplate");
		$ciialternativa->RowType = EW_ROWTYPE_ADD;

		// Render row
		$ciialternativa_grid->RenderRow();

		// Render list options
		$ciialternativa_grid->RenderListOptions();
		$ciialternativa_grid->StartRowCnt = 0;
?>
	<tr<?php echo $ciialternativa->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ciialternativa_grid->ListOptions->Render("body", "left", $ciialternativa_grid->RowIndex);
?>
	<?php if ($ciialternativa->no_alternativa->Visible) { // no_alternativa ?>
		<td>
<?php if ($ciialternativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" size="30" maxlength="100" placeholder="<?php echo $ciialternativa->no_alternativa->PlaceHolder ?>" value="<?php echo $ciialternativa->no_alternativa->EditValue ?>"<?php echo $ciialternativa->no_alternativa->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ciialternativa->no_alternativa->ViewAttributes() ?>>
<?php echo $ciialternativa->no_alternativa->ViewValue ?></span>
<input type="hidden" data-field="x_no_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->no_alternativa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_alternativa" name="o<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" id="o<?php echo $ciialternativa_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->no_alternativa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ciialternativa->vr_alternativa->Visible) { // vr_alternativa ?>
		<td>
<?php if ($ciialternativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" size="30" placeholder="<?php echo $ciialternativa->vr_alternativa->PlaceHolder ?>" value="<?php echo $ciialternativa->vr_alternativa->EditValue ?>"<?php echo $ciialternativa->vr_alternativa->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ciialternativa->vr_alternativa->ViewAttributes() ?>>
<?php echo $ciialternativa->vr_alternativa->ViewValue ?></span>
<input type="hidden" data-field="x_vr_alternativa" name="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->vr_alternativa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_alternativa" name="o<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" id="o<?php echo $ciialternativa_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($ciialternativa->vr_alternativa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ciialternativa->nu_peso->Visible) { // nu_peso ?>
		<td>
<?php if ($ciialternativa->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_peso" name="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" id="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" size="30" placeholder="<?php echo $ciialternativa->nu_peso->PlaceHolder ?>" value="<?php echo $ciialternativa->nu_peso->EditValue ?>"<?php echo $ciialternativa->nu_peso->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ciialternativa->nu_peso->ViewAttributes() ?>>
<?php echo $ciialternativa->nu_peso->ViewValue ?></span>
<input type="hidden" data-field="x_nu_peso" name="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" id="x<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" value="<?php echo ew_HtmlEncode($ciialternativa->nu_peso->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_peso" name="o<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" id="o<?php echo $ciialternativa_grid->RowIndex ?>_nu_peso" value="<?php echo ew_HtmlEncode($ciialternativa->nu_peso->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ciialternativa->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($ciialternativa->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $ciialternativa->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $ciialternativa->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ciialternativa->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $ciialternativa->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $ciialternativa->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $ciialternativa->ic_ativo->ViewAttributes() ?>>
<?php echo $ciialternativa->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="x<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ciialternativa->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" id="o<?php echo $ciialternativa_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($ciialternativa->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ciialternativa_grid->ListOptions->Render("body", "right", $ciialternativa_grid->RowCnt);
?>
<script type="text/javascript">
fciialternativagrid.UpdateOpts(<?php echo $ciialternativa_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($ciialternativa->CurrentMode == "add" || $ciialternativa->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $ciialternativa_grid->FormKeyCountName ?>" id="<?php echo $ciialternativa_grid->FormKeyCountName ?>" value="<?php echo $ciialternativa_grid->KeyCount ?>">
<?php echo $ciialternativa_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ciialternativa->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $ciialternativa_grid->FormKeyCountName ?>" id="<?php echo $ciialternativa_grid->FormKeyCountName ?>" value="<?php echo $ciialternativa_grid->KeyCount ?>">
<?php echo $ciialternativa_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ciialternativa->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fciialternativagrid">
</div>
<?php

// Close recordset
if ($ciialternativa_grid->Recordset)
	$ciialternativa_grid->Recordset->Close();
?>
<?php if ($ciialternativa_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($ciialternativa_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($ciialternativa->Export == "") { ?>
<script type="text/javascript">
fciialternativagrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$ciialternativa_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$ciialternativa_grid->Page_Terminate();
?>
