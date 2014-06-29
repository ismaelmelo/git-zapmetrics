<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($solicitacao_uc_grid)) $solicitacao_uc_grid = new csolicitacao_uc_grid();

// Page init
$solicitacao_uc_grid->Page_Init();

// Page main
$solicitacao_uc_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$solicitacao_uc_grid->Page_Render();
?>
<?php if ($solicitacao_uc->Export == "") { ?>
<script type="text/javascript">

// Page object
var solicitacao_uc_grid = new ew_Page("solicitacao_uc_grid");
solicitacao_uc_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = solicitacao_uc_grid.PageID; // For backward compatibility

// Form object
var fsolicitacao_ucgrid = new ew_Form("fsolicitacao_ucgrid");
fsolicitacao_ucgrid.FormKeyCountName = '<?php echo $solicitacao_uc_grid->FormKeyCountName ?>';

// Validate form
fsolicitacao_ucgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_sistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacao_uc->nu_sistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_uc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacao_uc->nu_uc->FldCaption()) ?>");

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
fsolicitacao_ucgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_sistema", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_uc", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_impacto", false)) return false;
	return true;
}

// Form_CustomValidate event
fsolicitacao_ucgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsolicitacao_ucgrid.ValidateRequired = true;
<?php } else { ?>
fsolicitacao_ucgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsolicitacao_ucgrid.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacao_ucgrid.Lists["x_nu_uc"] = {"LinkField":"x_nu_uc","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_uc","",""],"ParentFields":["x_nu_sistema"],"FilterFields":["x_nu_sistema"],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($solicitacao_uc->getCurrentMasterTable() == "" && $solicitacao_uc_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $solicitacao_uc_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($solicitacao_uc->CurrentAction == "gridadd") {
	if ($solicitacao_uc->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$solicitacao_uc_grid->TotalRecs = $solicitacao_uc->SelectRecordCount();
			$solicitacao_uc_grid->Recordset = $solicitacao_uc_grid->LoadRecordset($solicitacao_uc_grid->StartRec-1, $solicitacao_uc_grid->DisplayRecs);
		} else {
			if ($solicitacao_uc_grid->Recordset = $solicitacao_uc_grid->LoadRecordset())
				$solicitacao_uc_grid->TotalRecs = $solicitacao_uc_grid->Recordset->RecordCount();
		}
		$solicitacao_uc_grid->StartRec = 1;
		$solicitacao_uc_grid->DisplayRecs = $solicitacao_uc_grid->TotalRecs;
	} else {
		$solicitacao_uc->CurrentFilter = "0=1";
		$solicitacao_uc_grid->StartRec = 1;
		$solicitacao_uc_grid->DisplayRecs = $solicitacao_uc->GridAddRowCount;
	}
	$solicitacao_uc_grid->TotalRecs = $solicitacao_uc_grid->DisplayRecs;
	$solicitacao_uc_grid->StopRec = $solicitacao_uc_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$solicitacao_uc_grid->TotalRecs = $solicitacao_uc->SelectRecordCount();
	} else {
		if ($solicitacao_uc_grid->Recordset = $solicitacao_uc_grid->LoadRecordset())
			$solicitacao_uc_grid->TotalRecs = $solicitacao_uc_grid->Recordset->RecordCount();
	}
	$solicitacao_uc_grid->StartRec = 1;
	$solicitacao_uc_grid->DisplayRecs = $solicitacao_uc_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$solicitacao_uc_grid->Recordset = $solicitacao_uc_grid->LoadRecordset($solicitacao_uc_grid->StartRec-1, $solicitacao_uc_grid->DisplayRecs);
}
$solicitacao_uc_grid->RenderOtherOptions();
?>
<?php $solicitacao_uc_grid->ShowPageHeader(); ?>
<?php
$solicitacao_uc_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fsolicitacao_ucgrid" class="ewForm form-horizontal">
<div id="gmp_solicitacao_uc" class="ewGridMiddlePanel">
<table id="tbl_solicitacao_ucgrid" class="ewTable ewTableSeparate">
<?php echo $solicitacao_uc->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$solicitacao_uc_grid->RenderListOptions();

// Render list options (header, left)
$solicitacao_uc_grid->ListOptions->Render("header", "left");
?>
<?php if ($solicitacao_uc->nu_sistema->Visible) { // nu_sistema ?>
	<?php if ($solicitacao_uc->SortUrl($solicitacao_uc->nu_sistema) == "") { ?>
		<td><div id="elh_solicitacao_uc_nu_sistema" class="solicitacao_uc_nu_sistema"><div class="ewTableHeaderCaption"><?php echo $solicitacao_uc->nu_sistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_solicitacao_uc_nu_sistema" class="solicitacao_uc_nu_sistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacao_uc->nu_sistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacao_uc->nu_sistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacao_uc->nu_sistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacao_uc->nu_uc->Visible) { // nu_uc ?>
	<?php if ($solicitacao_uc->SortUrl($solicitacao_uc->nu_uc) == "") { ?>
		<td><div id="elh_solicitacao_uc_nu_uc" class="solicitacao_uc_nu_uc"><div class="ewTableHeaderCaption"><?php echo $solicitacao_uc->nu_uc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_solicitacao_uc_nu_uc" class="solicitacao_uc_nu_uc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacao_uc->nu_uc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacao_uc->nu_uc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacao_uc->nu_uc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacao_uc->ic_impacto->Visible) { // ic_impacto ?>
	<?php if ($solicitacao_uc->SortUrl($solicitacao_uc->ic_impacto) == "") { ?>
		<td><div id="elh_solicitacao_uc_ic_impacto" class="solicitacao_uc_ic_impacto"><div class="ewTableHeaderCaption"><?php echo $solicitacao_uc->ic_impacto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_solicitacao_uc_ic_impacto" class="solicitacao_uc_ic_impacto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacao_uc->ic_impacto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacao_uc->ic_impacto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacao_uc->ic_impacto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$solicitacao_uc_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$solicitacao_uc_grid->StartRec = 1;
$solicitacao_uc_grid->StopRec = $solicitacao_uc_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($solicitacao_uc_grid->FormKeyCountName) && ($solicitacao_uc->CurrentAction == "gridadd" || $solicitacao_uc->CurrentAction == "gridedit" || $solicitacao_uc->CurrentAction == "F")) {
		$solicitacao_uc_grid->KeyCount = $objForm->GetValue($solicitacao_uc_grid->FormKeyCountName);
		$solicitacao_uc_grid->StopRec = $solicitacao_uc_grid->StartRec + $solicitacao_uc_grid->KeyCount - 1;
	}
}
$solicitacao_uc_grid->RecCnt = $solicitacao_uc_grid->StartRec - 1;
if ($solicitacao_uc_grid->Recordset && !$solicitacao_uc_grid->Recordset->EOF) {
	$solicitacao_uc_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $solicitacao_uc_grid->StartRec > 1)
		$solicitacao_uc_grid->Recordset->Move($solicitacao_uc_grid->StartRec - 1);
} elseif (!$solicitacao_uc->AllowAddDeleteRow && $solicitacao_uc_grid->StopRec == 0) {
	$solicitacao_uc_grid->StopRec = $solicitacao_uc->GridAddRowCount;
}

// Initialize aggregate
$solicitacao_uc->RowType = EW_ROWTYPE_AGGREGATEINIT;
$solicitacao_uc->ResetAttrs();
$solicitacao_uc_grid->RenderRow();
if ($solicitacao_uc->CurrentAction == "gridadd")
	$solicitacao_uc_grid->RowIndex = 0;
if ($solicitacao_uc->CurrentAction == "gridedit")
	$solicitacao_uc_grid->RowIndex = 0;
while ($solicitacao_uc_grid->RecCnt < $solicitacao_uc_grid->StopRec) {
	$solicitacao_uc_grid->RecCnt++;
	if (intval($solicitacao_uc_grid->RecCnt) >= intval($solicitacao_uc_grid->StartRec)) {
		$solicitacao_uc_grid->RowCnt++;
		if ($solicitacao_uc->CurrentAction == "gridadd" || $solicitacao_uc->CurrentAction == "gridedit" || $solicitacao_uc->CurrentAction == "F") {
			$solicitacao_uc_grid->RowIndex++;
			$objForm->Index = $solicitacao_uc_grid->RowIndex;
			if ($objForm->HasValue($solicitacao_uc_grid->FormActionName))
				$solicitacao_uc_grid->RowAction = strval($objForm->GetValue($solicitacao_uc_grid->FormActionName));
			elseif ($solicitacao_uc->CurrentAction == "gridadd")
				$solicitacao_uc_grid->RowAction = "insert";
			else
				$solicitacao_uc_grid->RowAction = "";
		}

		// Set up key count
		$solicitacao_uc_grid->KeyCount = $solicitacao_uc_grid->RowIndex;

		// Init row class and style
		$solicitacao_uc->ResetAttrs();
		$solicitacao_uc->CssClass = "";
		if ($solicitacao_uc->CurrentAction == "gridadd") {
			if ($solicitacao_uc->CurrentMode == "copy") {
				$solicitacao_uc_grid->LoadRowValues($solicitacao_uc_grid->Recordset); // Load row values
				$solicitacao_uc_grid->SetRecordKey($solicitacao_uc_grid->RowOldKey, $solicitacao_uc_grid->Recordset); // Set old record key
			} else {
				$solicitacao_uc_grid->LoadDefaultValues(); // Load default values
				$solicitacao_uc_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$solicitacao_uc_grid->LoadRowValues($solicitacao_uc_grid->Recordset); // Load row values
		}
		$solicitacao_uc->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($solicitacao_uc->CurrentAction == "gridadd") // Grid add
			$solicitacao_uc->RowType = EW_ROWTYPE_ADD; // Render add
		if ($solicitacao_uc->CurrentAction == "gridadd" && $solicitacao_uc->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$solicitacao_uc_grid->RestoreCurrentRowFormValues($solicitacao_uc_grid->RowIndex); // Restore form values
		if ($solicitacao_uc->CurrentAction == "gridedit") { // Grid edit
			if ($solicitacao_uc->EventCancelled) {
				$solicitacao_uc_grid->RestoreCurrentRowFormValues($solicitacao_uc_grid->RowIndex); // Restore form values
			}
			if ($solicitacao_uc_grid->RowAction == "insert")
				$solicitacao_uc->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$solicitacao_uc->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($solicitacao_uc->CurrentAction == "gridedit" && ($solicitacao_uc->RowType == EW_ROWTYPE_EDIT || $solicitacao_uc->RowType == EW_ROWTYPE_ADD) && $solicitacao_uc->EventCancelled) // Update failed
			$solicitacao_uc_grid->RestoreCurrentRowFormValues($solicitacao_uc_grid->RowIndex); // Restore form values
		if ($solicitacao_uc->RowType == EW_ROWTYPE_EDIT) // Edit row
			$solicitacao_uc_grid->EditRowCnt++;
		if ($solicitacao_uc->CurrentAction == "F") // Confirm row
			$solicitacao_uc_grid->RestoreCurrentRowFormValues($solicitacao_uc_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$solicitacao_uc->RowAttrs = array_merge($solicitacao_uc->RowAttrs, array('data-rowindex'=>$solicitacao_uc_grid->RowCnt, 'id'=>'r' . $solicitacao_uc_grid->RowCnt . '_solicitacao_uc', 'data-rowtype'=>$solicitacao_uc->RowType));

		// Render row
		$solicitacao_uc_grid->RenderRow();

		// Render list options
		$solicitacao_uc_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($solicitacao_uc_grid->RowAction <> "delete" && $solicitacao_uc_grid->RowAction <> "insertdelete" && !($solicitacao_uc_grid->RowAction == "insert" && $solicitacao_uc->CurrentAction == "F" && $solicitacao_uc_grid->EmptyRow())) {
?>
	<tr<?php echo $solicitacao_uc->RowAttributes() ?>>
<?php

// Render list options (body, left)
$solicitacao_uc_grid->ListOptions->Render("body", "left", $solicitacao_uc_grid->RowCnt);
?>
	<?php if ($solicitacao_uc->nu_sistema->Visible) { // nu_sistema ?>
		<td<?php echo $solicitacao_uc->nu_sistema->CellAttributes() ?>>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $solicitacao_uc_grid->RowCnt ?>_solicitacao_uc_nu_sistema" class="control-group solicitacao_uc_nu_sistema">
<?php $solicitacao_uc->nu_sistema->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $solicitacao_uc_grid->RowIndex . "_nu_uc']); " . @$solicitacao_uc->nu_sistema->EditAttrs["onchange"]; ?>
<select data-field="x_nu_sistema" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema"<?php echo $solicitacao_uc->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($solicitacao_uc->nu_sistema->EditValue)) {
	$arwrk = $solicitacao_uc->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_uc->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$solicitacao_uc->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $solicitacao_uc->nu_sistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fsolicitacao_ucgrid.Lists["x_nu_sistema"].Options = <?php echo (is_array($solicitacao_uc->nu_sistema->EditValue)) ? ew_ArrayToJson($solicitacao_uc->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_sistema" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_sistema->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $solicitacao_uc_grid->RowCnt ?>_solicitacao_uc_nu_sistema" class="control-group solicitacao_uc_nu_sistema">
<select data-field="x_nu_sistema" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema"<?php echo $solicitacao_uc->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($solicitacao_uc->nu_sistema->EditValue)) {
	$arwrk = $solicitacao_uc->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_uc->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$solicitacao_uc->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $solicitacao_uc->nu_sistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fsolicitacao_ucgrid.Lists["x_nu_sistema"].Options = <?php echo (is_array($solicitacao_uc->nu_sistema->EditValue)) ? ew_ArrayToJson($solicitacao_uc->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $solicitacao_uc->nu_sistema->ViewAttributes() ?>>
<?php echo $solicitacao_uc->nu_sistema->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_sistema" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_sistema->FormValue) ?>">
<input type="hidden" data-field="x_nu_sistema" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_sistema->OldValue) ?>">
<?php } ?>
<a id="<?php echo $solicitacao_uc_grid->PageObjName . "_row_" . $solicitacao_uc_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_solicitacao" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_solicitacao" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_solicitacao->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_solicitacao" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_solicitacao" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_solicitacao->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_EDIT || $solicitacao_uc->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_solicitacao" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_solicitacao" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_solicitacao->CurrentValue) ?>">
<?php } ?>
	<?php if ($solicitacao_uc->nu_uc->Visible) { // nu_uc ?>
		<td<?php echo $solicitacao_uc->nu_uc->CellAttributes() ?>>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $solicitacao_uc_grid->RowCnt ?>_solicitacao_uc_nu_uc" class="control-group solicitacao_uc_nu_uc">
<select data-field="x_nu_uc" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc"<?php echo $solicitacao_uc->nu_uc->EditAttributes() ?>>
<?php
if (is_array($solicitacao_uc->nu_uc->EditValue)) {
	$arwrk = $solicitacao_uc->nu_uc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_uc->nu_uc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$solicitacao_uc->nu_uc) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $solicitacao_uc->nu_uc->OldValue = "";
?>
</select>
<script type="text/javascript">
fsolicitacao_ucgrid.Lists["x_nu_uc"].Options = <?php echo (is_array($solicitacao_uc->nu_uc->EditValue)) ? ew_ArrayToJson($solicitacao_uc->nu_uc->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_uc->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $solicitacao_uc_grid->RowCnt ?>_solicitacao_uc_nu_uc" class="control-group solicitacao_uc_nu_uc">
<span<?php echo $solicitacao_uc->nu_uc->ViewAttributes() ?>>
<?php echo $solicitacao_uc->nu_uc->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_uc->CurrentValue) ?>">
<?php } ?>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $solicitacao_uc->nu_uc->ViewAttributes() ?>>
<?php echo $solicitacao_uc->nu_uc->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_uc->FormValue) ?>">
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_uc->OldValue) ?>">
<?php } ?>
<a id="<?php echo $solicitacao_uc_grid->PageObjName . "_row_" . $solicitacao_uc_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacao_uc->ic_impacto->Visible) { // ic_impacto ?>
		<td<?php echo $solicitacao_uc->ic_impacto->CellAttributes() ?>>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $solicitacao_uc_grid->RowCnt ?>_solicitacao_uc_ic_impacto" class="control-group solicitacao_uc_ic_impacto">
<div id="tp_x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" value="{value}"<?php echo $solicitacao_uc->ic_impacto->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $solicitacao_uc->ic_impacto->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_uc->ic_impacto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_impacto" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $solicitacao_uc->ic_impacto->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $solicitacao_uc->ic_impacto->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_impacto" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" value="<?php echo ew_HtmlEncode($solicitacao_uc->ic_impacto->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $solicitacao_uc_grid->RowCnt ?>_solicitacao_uc_ic_impacto" class="control-group solicitacao_uc_ic_impacto">
<div id="tp_x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" value="{value}"<?php echo $solicitacao_uc->ic_impacto->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $solicitacao_uc->ic_impacto->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_uc->ic_impacto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_impacto" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $solicitacao_uc->ic_impacto->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $solicitacao_uc->ic_impacto->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $solicitacao_uc->ic_impacto->ViewAttributes() ?>>
<?php echo $solicitacao_uc->ic_impacto->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_impacto" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" value="<?php echo ew_HtmlEncode($solicitacao_uc->ic_impacto->FormValue) ?>">
<input type="hidden" data-field="x_ic_impacto" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" value="<?php echo ew_HtmlEncode($solicitacao_uc->ic_impacto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $solicitacao_uc_grid->PageObjName . "_row_" . $solicitacao_uc_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$solicitacao_uc_grid->ListOptions->Render("body", "right", $solicitacao_uc_grid->RowCnt);
?>
	</tr>
<?php if ($solicitacao_uc->RowType == EW_ROWTYPE_ADD || $solicitacao_uc->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsolicitacao_ucgrid.UpdateOpts(<?php echo $solicitacao_uc_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($solicitacao_uc->CurrentAction <> "gridadd" || $solicitacao_uc->CurrentMode == "copy")
		if (!$solicitacao_uc_grid->Recordset->EOF) $solicitacao_uc_grid->Recordset->MoveNext();
}
?>
<?php
	if ($solicitacao_uc->CurrentMode == "add" || $solicitacao_uc->CurrentMode == "copy" || $solicitacao_uc->CurrentMode == "edit") {
		$solicitacao_uc_grid->RowIndex = '$rowindex$';
		$solicitacao_uc_grid->LoadDefaultValues();

		// Set row properties
		$solicitacao_uc->ResetAttrs();
		$solicitacao_uc->RowAttrs = array_merge($solicitacao_uc->RowAttrs, array('data-rowindex'=>$solicitacao_uc_grid->RowIndex, 'id'=>'r0_solicitacao_uc', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($solicitacao_uc->RowAttrs["class"], "ewTemplate");
		$solicitacao_uc->RowType = EW_ROWTYPE_ADD;

		// Render row
		$solicitacao_uc_grid->RenderRow();

		// Render list options
		$solicitacao_uc_grid->RenderListOptions();
		$solicitacao_uc_grid->StartRowCnt = 0;
?>
	<tr<?php echo $solicitacao_uc->RowAttributes() ?>>
<?php

// Render list options (body, left)
$solicitacao_uc_grid->ListOptions->Render("body", "left", $solicitacao_uc_grid->RowIndex);
?>
	<?php if ($solicitacao_uc->nu_sistema->Visible) { // nu_sistema ?>
		<td>
<?php if ($solicitacao_uc->CurrentAction <> "F") { ?>
<?php $solicitacao_uc->nu_sistema->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $solicitacao_uc_grid->RowIndex . "_nu_uc']); " . @$solicitacao_uc->nu_sistema->EditAttrs["onchange"]; ?>
<select data-field="x_nu_sistema" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema"<?php echo $solicitacao_uc->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($solicitacao_uc->nu_sistema->EditValue)) {
	$arwrk = $solicitacao_uc->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_uc->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$solicitacao_uc->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $solicitacao_uc->nu_sistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fsolicitacao_ucgrid.Lists["x_nu_sistema"].Options = <?php echo (is_array($solicitacao_uc->nu_sistema->EditValue)) ? ew_ArrayToJson($solicitacao_uc->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $solicitacao_uc->nu_sistema->ViewAttributes() ?>>
<?php echo $solicitacao_uc->nu_sistema->ViewValue ?></span>
<input type="hidden" data-field="x_nu_sistema" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_sistema->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_sistema" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_sistema->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($solicitacao_uc->nu_uc->Visible) { // nu_uc ?>
		<td>
<?php if ($solicitacao_uc->CurrentAction <> "F") { ?>
<select data-field="x_nu_uc" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc"<?php echo $solicitacao_uc->nu_uc->EditAttributes() ?>>
<?php
if (is_array($solicitacao_uc->nu_uc->EditValue)) {
	$arwrk = $solicitacao_uc->nu_uc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_uc->nu_uc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$solicitacao_uc->nu_uc) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $solicitacao_uc->nu_uc->OldValue = "";
?>
</select>
<script type="text/javascript">
fsolicitacao_ucgrid.Lists["x_nu_uc"].Options = <?php echo (is_array($solicitacao_uc->nu_uc->EditValue)) ? ew_ArrayToJson($solicitacao_uc->nu_uc->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $solicitacao_uc->nu_uc->ViewAttributes() ?>>
<?php echo $solicitacao_uc->nu_uc->ViewValue ?></span>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_uc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($solicitacao_uc->nu_uc->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($solicitacao_uc->ic_impacto->Visible) { // ic_impacto ?>
		<td>
<?php if ($solicitacao_uc->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" value="{value}"<?php echo $solicitacao_uc->ic_impacto->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $solicitacao_uc->ic_impacto->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_uc->ic_impacto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_impacto" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $solicitacao_uc->ic_impacto->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $solicitacao_uc->ic_impacto->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $solicitacao_uc->ic_impacto->ViewAttributes() ?>>
<?php echo $solicitacao_uc->ic_impacto->ViewValue ?></span>
<input type="hidden" data-field="x_ic_impacto" name="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="x<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" value="<?php echo ew_HtmlEncode($solicitacao_uc->ic_impacto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_impacto" name="o<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" id="o<?php echo $solicitacao_uc_grid->RowIndex ?>_ic_impacto" value="<?php echo ew_HtmlEncode($solicitacao_uc->ic_impacto->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$solicitacao_uc_grid->ListOptions->Render("body", "right", $solicitacao_uc_grid->RowCnt);
?>
<script type="text/javascript">
fsolicitacao_ucgrid.UpdateOpts(<?php echo $solicitacao_uc_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($solicitacao_uc->CurrentMode == "add" || $solicitacao_uc->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $solicitacao_uc_grid->FormKeyCountName ?>" id="<?php echo $solicitacao_uc_grid->FormKeyCountName ?>" value="<?php echo $solicitacao_uc_grid->KeyCount ?>">
<?php echo $solicitacao_uc_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($solicitacao_uc->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $solicitacao_uc_grid->FormKeyCountName ?>" id="<?php echo $solicitacao_uc_grid->FormKeyCountName ?>" value="<?php echo $solicitacao_uc_grid->KeyCount ?>">
<?php echo $solicitacao_uc_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($solicitacao_uc->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsolicitacao_ucgrid">
</div>
<?php

// Close recordset
if ($solicitacao_uc_grid->Recordset)
	$solicitacao_uc_grid->Recordset->Close();
?>
<?php if ($solicitacao_uc_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($solicitacao_uc_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($solicitacao_uc->Export == "") { ?>
<script type="text/javascript">
fsolicitacao_ucgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$solicitacao_uc_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$solicitacao_uc_grid->Page_Terminate();
?>
