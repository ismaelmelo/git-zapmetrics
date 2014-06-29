<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($atividade_papel_grid)) $atividade_papel_grid = new catividade_papel_grid();

// Page init
$atividade_papel_grid->Page_Init();

// Page main
$atividade_papel_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$atividade_papel_grid->Page_Render();
?>
<?php if ($atividade_papel->Export == "") { ?>
<script type="text/javascript">

// Page object
var atividade_papel_grid = new ew_Page("atividade_papel_grid");
atividade_papel_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = atividade_papel_grid.PageID; // For backward compatibility

// Form object
var fatividade_papelgrid = new ew_Form("fatividade_papelgrid");
fatividade_papelgrid.FormKeyCountName = '<?php echo $atividade_papel_grid->FormKeyCountName ?>';

// Validate form
fatividade_papelgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_atividade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade_papel->nu_atividade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_papel");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade_papel->co_papel->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_responsabilidade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($atividade_papel->ic_responsabilidade->FldCaption()) ?>");

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
fatividade_papelgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_atividade", false)) return false;
	if (ew_ValueChanged(fobj, infix, "co_papel", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_responsabilidade", false)) return false;
	return true;
}

// Form_CustomValidate event
fatividade_papelgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fatividade_papelgrid.ValidateRequired = true;
<?php } else { ?>
fatividade_papelgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fatividade_papelgrid.Lists["x_nu_atividade"] = {"LinkField":"x_nu_atividade","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_atividade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fatividade_papelgrid.Lists["x_co_papel"] = {"LinkField":"x_co_papel","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_papel","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($atividade_papel->getCurrentMasterTable() == "" && $atividade_papel_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $atividade_papel_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($atividade_papel->CurrentAction == "gridadd") {
	if ($atividade_papel->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$atividade_papel_grid->TotalRecs = $atividade_papel->SelectRecordCount();
			$atividade_papel_grid->Recordset = $atividade_papel_grid->LoadRecordset($atividade_papel_grid->StartRec-1, $atividade_papel_grid->DisplayRecs);
		} else {
			if ($atividade_papel_grid->Recordset = $atividade_papel_grid->LoadRecordset())
				$atividade_papel_grid->TotalRecs = $atividade_papel_grid->Recordset->RecordCount();
		}
		$atividade_papel_grid->StartRec = 1;
		$atividade_papel_grid->DisplayRecs = $atividade_papel_grid->TotalRecs;
	} else {
		$atividade_papel->CurrentFilter = "0=1";
		$atividade_papel_grid->StartRec = 1;
		$atividade_papel_grid->DisplayRecs = $atividade_papel->GridAddRowCount;
	}
	$atividade_papel_grid->TotalRecs = $atividade_papel_grid->DisplayRecs;
	$atividade_papel_grid->StopRec = $atividade_papel_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$atividade_papel_grid->TotalRecs = $atividade_papel->SelectRecordCount();
	} else {
		if ($atividade_papel_grid->Recordset = $atividade_papel_grid->LoadRecordset())
			$atividade_papel_grid->TotalRecs = $atividade_papel_grid->Recordset->RecordCount();
	}
	$atividade_papel_grid->StartRec = 1;
	$atividade_papel_grid->DisplayRecs = $atividade_papel_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$atividade_papel_grid->Recordset = $atividade_papel_grid->LoadRecordset($atividade_papel_grid->StartRec-1, $atividade_papel_grid->DisplayRecs);
}
$atividade_papel_grid->RenderOtherOptions();
?>
<?php $atividade_papel_grid->ShowPageHeader(); ?>
<?php
$atividade_papel_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fatividade_papelgrid" class="ewForm form-horizontal">
<div id="gmp_atividade_papel" class="ewGridMiddlePanel">
<table id="tbl_atividade_papelgrid" class="ewTable ewTableSeparate">
<?php echo $atividade_papel->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$atividade_papel_grid->RenderListOptions();

// Render list options (header, left)
$atividade_papel_grid->ListOptions->Render("header", "left");
?>
<?php if ($atividade_papel->nu_atividade->Visible) { // nu_atividade ?>
	<?php if ($atividade_papel->SortUrl($atividade_papel->nu_atividade) == "") { ?>
		<td><div id="elh_atividade_papel_nu_atividade" class="atividade_papel_nu_atividade"><div class="ewTableHeaderCaption"><?php echo $atividade_papel->nu_atividade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_atividade_papel_nu_atividade" class="atividade_papel_nu_atividade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atividade_papel->nu_atividade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atividade_papel->nu_atividade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atividade_papel->nu_atividade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($atividade_papel->co_papel->Visible) { // co_papel ?>
	<?php if ($atividade_papel->SortUrl($atividade_papel->co_papel) == "") { ?>
		<td><div id="elh_atividade_papel_co_papel" class="atividade_papel_co_papel"><div class="ewTableHeaderCaption"><?php echo $atividade_papel->co_papel->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_atividade_papel_co_papel" class="atividade_papel_co_papel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atividade_papel->co_papel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atividade_papel->co_papel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atividade_papel->co_papel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($atividade_papel->ic_responsabilidade->Visible) { // ic_responsabilidade ?>
	<?php if ($atividade_papel->SortUrl($atividade_papel->ic_responsabilidade) == "") { ?>
		<td><div id="elh_atividade_papel_ic_responsabilidade" class="atividade_papel_ic_responsabilidade"><div class="ewTableHeaderCaption"><?php echo $atividade_papel->ic_responsabilidade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_atividade_papel_ic_responsabilidade" class="atividade_papel_ic_responsabilidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atividade_papel->ic_responsabilidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atividade_papel->ic_responsabilidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atividade_papel->ic_responsabilidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$atividade_papel_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$atividade_papel_grid->StartRec = 1;
$atividade_papel_grid->StopRec = $atividade_papel_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($atividade_papel_grid->FormKeyCountName) && ($atividade_papel->CurrentAction == "gridadd" || $atividade_papel->CurrentAction == "gridedit" || $atividade_papel->CurrentAction == "F")) {
		$atividade_papel_grid->KeyCount = $objForm->GetValue($atividade_papel_grid->FormKeyCountName);
		$atividade_papel_grid->StopRec = $atividade_papel_grid->StartRec + $atividade_papel_grid->KeyCount - 1;
	}
}
$atividade_papel_grid->RecCnt = $atividade_papel_grid->StartRec - 1;
if ($atividade_papel_grid->Recordset && !$atividade_papel_grid->Recordset->EOF) {
	$atividade_papel_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $atividade_papel_grid->StartRec > 1)
		$atividade_papel_grid->Recordset->Move($atividade_papel_grid->StartRec - 1);
} elseif (!$atividade_papel->AllowAddDeleteRow && $atividade_papel_grid->StopRec == 0) {
	$atividade_papel_grid->StopRec = $atividade_papel->GridAddRowCount;
}

// Initialize aggregate
$atividade_papel->RowType = EW_ROWTYPE_AGGREGATEINIT;
$atividade_papel->ResetAttrs();
$atividade_papel_grid->RenderRow();
if ($atividade_papel->CurrentAction == "gridadd")
	$atividade_papel_grid->RowIndex = 0;
if ($atividade_papel->CurrentAction == "gridedit")
	$atividade_papel_grid->RowIndex = 0;
while ($atividade_papel_grid->RecCnt < $atividade_papel_grid->StopRec) {
	$atividade_papel_grid->RecCnt++;
	if (intval($atividade_papel_grid->RecCnt) >= intval($atividade_papel_grid->StartRec)) {
		$atividade_papel_grid->RowCnt++;
		if ($atividade_papel->CurrentAction == "gridadd" || $atividade_papel->CurrentAction == "gridedit" || $atividade_papel->CurrentAction == "F") {
			$atividade_papel_grid->RowIndex++;
			$objForm->Index = $atividade_papel_grid->RowIndex;
			if ($objForm->HasValue($atividade_papel_grid->FormActionName))
				$atividade_papel_grid->RowAction = strval($objForm->GetValue($atividade_papel_grid->FormActionName));
			elseif ($atividade_papel->CurrentAction == "gridadd")
				$atividade_papel_grid->RowAction = "insert";
			else
				$atividade_papel_grid->RowAction = "";
		}

		// Set up key count
		$atividade_papel_grid->KeyCount = $atividade_papel_grid->RowIndex;

		// Init row class and style
		$atividade_papel->ResetAttrs();
		$atividade_papel->CssClass = "";
		if ($atividade_papel->CurrentAction == "gridadd") {
			if ($atividade_papel->CurrentMode == "copy") {
				$atividade_papel_grid->LoadRowValues($atividade_papel_grid->Recordset); // Load row values
				$atividade_papel_grid->SetRecordKey($atividade_papel_grid->RowOldKey, $atividade_papel_grid->Recordset); // Set old record key
			} else {
				$atividade_papel_grid->LoadDefaultValues(); // Load default values
				$atividade_papel_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$atividade_papel_grid->LoadRowValues($atividade_papel_grid->Recordset); // Load row values
		}
		$atividade_papel->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($atividade_papel->CurrentAction == "gridadd") // Grid add
			$atividade_papel->RowType = EW_ROWTYPE_ADD; // Render add
		if ($atividade_papel->CurrentAction == "gridadd" && $atividade_papel->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$atividade_papel_grid->RestoreCurrentRowFormValues($atividade_papel_grid->RowIndex); // Restore form values
		if ($atividade_papel->CurrentAction == "gridedit") { // Grid edit
			if ($atividade_papel->EventCancelled) {
				$atividade_papel_grid->RestoreCurrentRowFormValues($atividade_papel_grid->RowIndex); // Restore form values
			}
			if ($atividade_papel_grid->RowAction == "insert")
				$atividade_papel->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$atividade_papel->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($atividade_papel->CurrentAction == "gridedit" && ($atividade_papel->RowType == EW_ROWTYPE_EDIT || $atividade_papel->RowType == EW_ROWTYPE_ADD) && $atividade_papel->EventCancelled) // Update failed
			$atividade_papel_grid->RestoreCurrentRowFormValues($atividade_papel_grid->RowIndex); // Restore form values
		if ($atividade_papel->RowType == EW_ROWTYPE_EDIT) // Edit row
			$atividade_papel_grid->EditRowCnt++;
		if ($atividade_papel->CurrentAction == "F") // Confirm row
			$atividade_papel_grid->RestoreCurrentRowFormValues($atividade_papel_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$atividade_papel->RowAttrs = array_merge($atividade_papel->RowAttrs, array('data-rowindex'=>$atividade_papel_grid->RowCnt, 'id'=>'r' . $atividade_papel_grid->RowCnt . '_atividade_papel', 'data-rowtype'=>$atividade_papel->RowType));

		// Render row
		$atividade_papel_grid->RenderRow();

		// Render list options
		$atividade_papel_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($atividade_papel_grid->RowAction <> "delete" && $atividade_papel_grid->RowAction <> "insertdelete" && !($atividade_papel_grid->RowAction == "insert" && $atividade_papel->CurrentAction == "F" && $atividade_papel_grid->EmptyRow())) {
?>
	<tr<?php echo $atividade_papel->RowAttributes() ?>>
<?php

// Render list options (body, left)
$atividade_papel_grid->ListOptions->Render("body", "left", $atividade_papel_grid->RowCnt);
?>
	<?php if ($atividade_papel->nu_atividade->Visible) { // nu_atividade ?>
		<td<?php echo $atividade_papel->nu_atividade->CellAttributes() ?>>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($atividade_papel->nu_atividade->getSessionValue() <> "") { ?>
<span<?php echo $atividade_papel->nu_atividade->ViewAttributes() ?>>
<?php echo $atividade_papel->nu_atividade->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade_papel->nu_atividade->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_atividade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade"<?php echo $atividade_papel->nu_atividade->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->nu_atividade->EditValue)) {
	$arwrk = $atividade_papel->nu_atividade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->nu_atividade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $atividade_papel->nu_atividade->OldValue = "";
?>
</select>
<script type="text/javascript">
fatividade_papelgrid.Lists["x_nu_atividade"].Options = <?php echo (is_array($atividade_papel->nu_atividade->EditValue)) ? ew_ArrayToJson($atividade_papel->nu_atividade->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_nu_atividade" name="o<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" id="o<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade_papel->nu_atividade->OldValue) ?>">
<?php } ?>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $atividade_papel->nu_atividade->ViewAttributes() ?>>
<?php echo $atividade_papel->nu_atividade->EditValue ?></span>
<input type="hidden" data-field="x_nu_atividade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade_papel->nu_atividade->CurrentValue) ?>">
<?php } ?>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $atividade_papel->nu_atividade->ViewAttributes() ?>>
<?php echo $atividade_papel->nu_atividade->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_atividade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade_papel->nu_atividade->FormValue) ?>">
<input type="hidden" data-field="x_nu_atividade" name="o<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" id="o<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade_papel->nu_atividade->OldValue) ?>">
<?php } ?>
<a id="<?php echo $atividade_papel_grid->PageObjName . "_row_" . $atividade_papel_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($atividade_papel->co_papel->Visible) { // co_papel ?>
		<td<?php echo $atividade_papel->co_papel->CellAttributes() ?>>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $atividade_papel_grid->RowCnt ?>_atividade_papel_co_papel" class="control-group atividade_papel_co_papel">
<select data-field="x_co_papel" id="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" name="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel"<?php echo $atividade_papel->co_papel->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->co_papel->EditValue)) {
	$arwrk = $atividade_papel->co_papel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->co_papel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $atividade_papel->co_papel->OldValue = "";
?>
</select>
<script type="text/javascript">
fatividade_papelgrid.Lists["x_co_papel"].Options = <?php echo (is_array($atividade_papel->co_papel->EditValue)) ? ew_ArrayToJson($atividade_papel->co_papel->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_co_papel" name="o<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" id="o<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" value="<?php echo ew_HtmlEncode($atividade_papel->co_papel->OldValue) ?>">
<?php } ?>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $atividade_papel_grid->RowCnt ?>_atividade_papel_co_papel" class="control-group atividade_papel_co_papel">
<span<?php echo $atividade_papel->co_papel->ViewAttributes() ?>>
<?php echo $atividade_papel->co_papel->EditValue ?></span>
</span>
<input type="hidden" data-field="x_co_papel" name="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" id="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" value="<?php echo ew_HtmlEncode($atividade_papel->co_papel->CurrentValue) ?>">
<?php } ?>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $atividade_papel->co_papel->ViewAttributes() ?>>
<?php echo $atividade_papel->co_papel->ListViewValue() ?></span>
<input type="hidden" data-field="x_co_papel" name="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" id="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" value="<?php echo ew_HtmlEncode($atividade_papel->co_papel->FormValue) ?>">
<input type="hidden" data-field="x_co_papel" name="o<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" id="o<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" value="<?php echo ew_HtmlEncode($atividade_papel->co_papel->OldValue) ?>">
<?php } ?>
<a id="<?php echo $atividade_papel_grid->PageObjName . "_row_" . $atividade_papel_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($atividade_papel->ic_responsabilidade->Visible) { // ic_responsabilidade ?>
		<td<?php echo $atividade_papel->ic_responsabilidade->CellAttributes() ?>>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $atividade_papel_grid->RowCnt ?>_atividade_papel_ic_responsabilidade" class="control-group atividade_papel_ic_responsabilidade">
<select data-field="x_ic_responsabilidade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade"<?php echo $atividade_papel->ic_responsabilidade->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->ic_responsabilidade->EditValue)) {
	$arwrk = $atividade_papel->ic_responsabilidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->ic_responsabilidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $atividade_papel->ic_responsabilidade->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_ic_responsabilidade" name="o<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" id="o<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" value="<?php echo ew_HtmlEncode($atividade_papel->ic_responsabilidade->OldValue) ?>">
<?php } ?>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $atividade_papel_grid->RowCnt ?>_atividade_papel_ic_responsabilidade" class="control-group atividade_papel_ic_responsabilidade">
<select data-field="x_ic_responsabilidade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade"<?php echo $atividade_papel->ic_responsabilidade->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->ic_responsabilidade->EditValue)) {
	$arwrk = $atividade_papel->ic_responsabilidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->ic_responsabilidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $atividade_papel->ic_responsabilidade->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $atividade_papel->ic_responsabilidade->ViewAttributes() ?>>
<?php echo $atividade_papel->ic_responsabilidade->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_responsabilidade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" value="<?php echo ew_HtmlEncode($atividade_papel->ic_responsabilidade->FormValue) ?>">
<input type="hidden" data-field="x_ic_responsabilidade" name="o<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" id="o<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" value="<?php echo ew_HtmlEncode($atividade_papel->ic_responsabilidade->OldValue) ?>">
<?php } ?>
<a id="<?php echo $atividade_papel_grid->PageObjName . "_row_" . $atividade_papel_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$atividade_papel_grid->ListOptions->Render("body", "right", $atividade_papel_grid->RowCnt);
?>
	</tr>
<?php if ($atividade_papel->RowType == EW_ROWTYPE_ADD || $atividade_papel->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fatividade_papelgrid.UpdateOpts(<?php echo $atividade_papel_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($atividade_papel->CurrentAction <> "gridadd" || $atividade_papel->CurrentMode == "copy")
		if (!$atividade_papel_grid->Recordset->EOF) $atividade_papel_grid->Recordset->MoveNext();
}
?>
<?php
	if ($atividade_papel->CurrentMode == "add" || $atividade_papel->CurrentMode == "copy" || $atividade_papel->CurrentMode == "edit") {
		$atividade_papel_grid->RowIndex = '$rowindex$';
		$atividade_papel_grid->LoadDefaultValues();

		// Set row properties
		$atividade_papel->ResetAttrs();
		$atividade_papel->RowAttrs = array_merge($atividade_papel->RowAttrs, array('data-rowindex'=>$atividade_papel_grid->RowIndex, 'id'=>'r0_atividade_papel', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($atividade_papel->RowAttrs["class"], "ewTemplate");
		$atividade_papel->RowType = EW_ROWTYPE_ADD;

		// Render row
		$atividade_papel_grid->RenderRow();

		// Render list options
		$atividade_papel_grid->RenderListOptions();
		$atividade_papel_grid->StartRowCnt = 0;
?>
	<tr<?php echo $atividade_papel->RowAttributes() ?>>
<?php

// Render list options (body, left)
$atividade_papel_grid->ListOptions->Render("body", "left", $atividade_papel_grid->RowIndex);
?>
	<?php if ($atividade_papel->nu_atividade->Visible) { // nu_atividade ?>
		<td>
<?php if ($atividade_papel->CurrentAction <> "F") { ?>
<?php if ($atividade_papel->nu_atividade->getSessionValue() <> "") { ?>
<span<?php echo $atividade_papel->nu_atividade->ViewAttributes() ?>>
<?php echo $atividade_papel->nu_atividade->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade_papel->nu_atividade->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_atividade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade"<?php echo $atividade_papel->nu_atividade->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->nu_atividade->EditValue)) {
	$arwrk = $atividade_papel->nu_atividade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->nu_atividade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $atividade_papel->nu_atividade->OldValue = "";
?>
</select>
<script type="text/javascript">
fatividade_papelgrid.Lists["x_nu_atividade"].Options = <?php echo (is_array($atividade_papel->nu_atividade->EditValue)) ? ew_ArrayToJson($atividade_papel->nu_atividade->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $atividade_papel->nu_atividade->ViewAttributes() ?>>
<?php echo $atividade_papel->nu_atividade->ViewValue ?></span>
<input type="hidden" data-field="x_nu_atividade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade_papel->nu_atividade->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_atividade" name="o<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" id="o<?php echo $atividade_papel_grid->RowIndex ?>_nu_atividade" value="<?php echo ew_HtmlEncode($atividade_papel->nu_atividade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($atividade_papel->co_papel->Visible) { // co_papel ?>
		<td>
<?php if ($atividade_papel->CurrentAction <> "F") { ?>
<select data-field="x_co_papel" id="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" name="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel"<?php echo $atividade_papel->co_papel->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->co_papel->EditValue)) {
	$arwrk = $atividade_papel->co_papel->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->co_papel->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $atividade_papel->co_papel->OldValue = "";
?>
</select>
<script type="text/javascript">
fatividade_papelgrid.Lists["x_co_papel"].Options = <?php echo (is_array($atividade_papel->co_papel->EditValue)) ? ew_ArrayToJson($atividade_papel->co_papel->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $atividade_papel->co_papel->ViewAttributes() ?>>
<?php echo $atividade_papel->co_papel->ViewValue ?></span>
<input type="hidden" data-field="x_co_papel" name="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" id="x<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" value="<?php echo ew_HtmlEncode($atividade_papel->co_papel->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_co_papel" name="o<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" id="o<?php echo $atividade_papel_grid->RowIndex ?>_co_papel" value="<?php echo ew_HtmlEncode($atividade_papel->co_papel->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($atividade_papel->ic_responsabilidade->Visible) { // ic_responsabilidade ?>
		<td>
<?php if ($atividade_papel->CurrentAction <> "F") { ?>
<select data-field="x_ic_responsabilidade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade"<?php echo $atividade_papel->ic_responsabilidade->EditAttributes() ?>>
<?php
if (is_array($atividade_papel->ic_responsabilidade->EditValue)) {
	$arwrk = $atividade_papel->ic_responsabilidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade_papel->ic_responsabilidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $atividade_papel->ic_responsabilidade->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $atividade_papel->ic_responsabilidade->ViewAttributes() ?>>
<?php echo $atividade_papel->ic_responsabilidade->ViewValue ?></span>
<input type="hidden" data-field="x_ic_responsabilidade" name="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" id="x<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" value="<?php echo ew_HtmlEncode($atividade_papel->ic_responsabilidade->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_responsabilidade" name="o<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" id="o<?php echo $atividade_papel_grid->RowIndex ?>_ic_responsabilidade" value="<?php echo ew_HtmlEncode($atividade_papel->ic_responsabilidade->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$atividade_papel_grid->ListOptions->Render("body", "right", $atividade_papel_grid->RowCnt);
?>
<script type="text/javascript">
fatividade_papelgrid.UpdateOpts(<?php echo $atividade_papel_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($atividade_papel->CurrentMode == "add" || $atividade_papel->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $atividade_papel_grid->FormKeyCountName ?>" id="<?php echo $atividade_papel_grid->FormKeyCountName ?>" value="<?php echo $atividade_papel_grid->KeyCount ?>">
<?php echo $atividade_papel_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($atividade_papel->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $atividade_papel_grid->FormKeyCountName ?>" id="<?php echo $atividade_papel_grid->FormKeyCountName ?>" value="<?php echo $atividade_papel_grid->KeyCount ?>">
<?php echo $atividade_papel_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($atividade_papel->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fatividade_papelgrid">
</div>
<?php

// Close recordset
if ($atividade_papel_grid->Recordset)
	$atividade_papel_grid->Recordset->Close();
?>
<?php if ($atividade_papel_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($atividade_papel_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($atividade_papel->Export == "") { ?>
<script type="text/javascript">
fatividade_papelgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$atividade_papel_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$atividade_papel_grid->Page_Terminate();
?>
