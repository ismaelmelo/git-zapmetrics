<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($uc_grid)) $uc_grid = new cuc_grid();

// Page init
$uc_grid->Page_Init();

// Page main
$uc_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$uc_grid->Page_Render();
?>
<?php if ($uc->Export == "") { ?>
<script type="text/javascript">

// Page object
var uc_grid = new ew_Page("uc_grid");
uc_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = uc_grid.PageID; // For backward compatibility

// Form object
var fucgrid = new ew_Form("fucgrid");
fucgrid.FormKeyCountName = '<?php echo $uc_grid->FormKeyCountName ?>';

// Validate form
fucgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_co_alternativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc->co_alternativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_uc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc->no_uc->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stUc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc->nu_stUc->FldCaption()) ?>");

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
fucgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "co_alternativo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_uc", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_stUc", false)) return false;
	return true;
}

// Form_CustomValidate event
fucgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fucgrid.ValidateRequired = true;
<?php } else { ?>
fucgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fucgrid.Lists["x_nu_stUc"] = {"LinkField":"x_nu_stUc","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($uc->getCurrentMasterTable() == "" && $uc_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $uc_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($uc->CurrentAction == "gridadd") {
	if ($uc->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$uc_grid->TotalRecs = $uc->SelectRecordCount();
			$uc_grid->Recordset = $uc_grid->LoadRecordset($uc_grid->StartRec-1, $uc_grid->DisplayRecs);
		} else {
			if ($uc_grid->Recordset = $uc_grid->LoadRecordset())
				$uc_grid->TotalRecs = $uc_grid->Recordset->RecordCount();
		}
		$uc_grid->StartRec = 1;
		$uc_grid->DisplayRecs = $uc_grid->TotalRecs;
	} else {
		$uc->CurrentFilter = "0=1";
		$uc_grid->StartRec = 1;
		$uc_grid->DisplayRecs = $uc->GridAddRowCount;
	}
	$uc_grid->TotalRecs = $uc_grid->DisplayRecs;
	$uc_grid->StopRec = $uc_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$uc_grid->TotalRecs = $uc->SelectRecordCount();
	} else {
		if ($uc_grid->Recordset = $uc_grid->LoadRecordset())
			$uc_grid->TotalRecs = $uc_grid->Recordset->RecordCount();
	}
	$uc_grid->StartRec = 1;
	$uc_grid->DisplayRecs = $uc_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$uc_grid->Recordset = $uc_grid->LoadRecordset($uc_grid->StartRec-1, $uc_grid->DisplayRecs);
}
$uc_grid->RenderOtherOptions();
?>
<?php $uc_grid->ShowPageHeader(); ?>
<?php
$uc_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fucgrid" class="ewForm form-horizontal">
<div id="gmp_uc" class="ewGridMiddlePanel">
<table id="tbl_ucgrid" class="ewTable ewTableSeparate">
<?php echo $uc->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$uc_grid->RenderListOptions();

// Render list options (header, left)
$uc_grid->ListOptions->Render("header", "left");
?>
<?php if ($uc->co_alternativo->Visible) { // co_alternativo ?>
	<?php if ($uc->SortUrl($uc->co_alternativo) == "") { ?>
		<td><div id="elh_uc_co_alternativo" class="uc_co_alternativo"><div class="ewTableHeaderCaption"><?php echo $uc->co_alternativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_uc_co_alternativo" class="uc_co_alternativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $uc->co_alternativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($uc->co_alternativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($uc->co_alternativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($uc->no_uc->Visible) { // no_uc ?>
	<?php if ($uc->SortUrl($uc->no_uc) == "") { ?>
		<td><div id="elh_uc_no_uc" class="uc_no_uc"><div class="ewTableHeaderCaption"><?php echo $uc->no_uc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_uc_no_uc" class="uc_no_uc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $uc->no_uc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($uc->no_uc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($uc->no_uc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($uc->nu_stUc->Visible) { // nu_stUc ?>
	<?php if ($uc->SortUrl($uc->nu_stUc) == "") { ?>
		<td><div id="elh_uc_nu_stUc" class="uc_nu_stUc"><div class="ewTableHeaderCaption"><?php echo $uc->nu_stUc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_uc_nu_stUc" class="uc_nu_stUc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $uc->nu_stUc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($uc->nu_stUc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($uc->nu_stUc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$uc_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$uc_grid->StartRec = 1;
$uc_grid->StopRec = $uc_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($uc_grid->FormKeyCountName) && ($uc->CurrentAction == "gridadd" || $uc->CurrentAction == "gridedit" || $uc->CurrentAction == "F")) {
		$uc_grid->KeyCount = $objForm->GetValue($uc_grid->FormKeyCountName);
		$uc_grid->StopRec = $uc_grid->StartRec + $uc_grid->KeyCount - 1;
	}
}
$uc_grid->RecCnt = $uc_grid->StartRec - 1;
if ($uc_grid->Recordset && !$uc_grid->Recordset->EOF) {
	$uc_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $uc_grid->StartRec > 1)
		$uc_grid->Recordset->Move($uc_grid->StartRec - 1);
} elseif (!$uc->AllowAddDeleteRow && $uc_grid->StopRec == 0) {
	$uc_grid->StopRec = $uc->GridAddRowCount;
}

// Initialize aggregate
$uc->RowType = EW_ROWTYPE_AGGREGATEINIT;
$uc->ResetAttrs();
$uc_grid->RenderRow();
if ($uc->CurrentAction == "gridadd")
	$uc_grid->RowIndex = 0;
if ($uc->CurrentAction == "gridedit")
	$uc_grid->RowIndex = 0;
while ($uc_grid->RecCnt < $uc_grid->StopRec) {
	$uc_grid->RecCnt++;
	if (intval($uc_grid->RecCnt) >= intval($uc_grid->StartRec)) {
		$uc_grid->RowCnt++;
		if ($uc->CurrentAction == "gridadd" || $uc->CurrentAction == "gridedit" || $uc->CurrentAction == "F") {
			$uc_grid->RowIndex++;
			$objForm->Index = $uc_grid->RowIndex;
			if ($objForm->HasValue($uc_grid->FormActionName))
				$uc_grid->RowAction = strval($objForm->GetValue($uc_grid->FormActionName));
			elseif ($uc->CurrentAction == "gridadd")
				$uc_grid->RowAction = "insert";
			else
				$uc_grid->RowAction = "";
		}

		// Set up key count
		$uc_grid->KeyCount = $uc_grid->RowIndex;

		// Init row class and style
		$uc->ResetAttrs();
		$uc->CssClass = "";
		if ($uc->CurrentAction == "gridadd") {
			if ($uc->CurrentMode == "copy") {
				$uc_grid->LoadRowValues($uc_grid->Recordset); // Load row values
				$uc_grid->SetRecordKey($uc_grid->RowOldKey, $uc_grid->Recordset); // Set old record key
			} else {
				$uc_grid->LoadDefaultValues(); // Load default values
				$uc_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$uc_grid->LoadRowValues($uc_grid->Recordset); // Load row values
		}
		$uc->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($uc->CurrentAction == "gridadd") // Grid add
			$uc->RowType = EW_ROWTYPE_ADD; // Render add
		if ($uc->CurrentAction == "gridadd" && $uc->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$uc_grid->RestoreCurrentRowFormValues($uc_grid->RowIndex); // Restore form values
		if ($uc->CurrentAction == "gridedit") { // Grid edit
			if ($uc->EventCancelled) {
				$uc_grid->RestoreCurrentRowFormValues($uc_grid->RowIndex); // Restore form values
			}
			if ($uc_grid->RowAction == "insert")
				$uc->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$uc->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($uc->CurrentAction == "gridedit" && ($uc->RowType == EW_ROWTYPE_EDIT || $uc->RowType == EW_ROWTYPE_ADD) && $uc->EventCancelled) // Update failed
			$uc_grid->RestoreCurrentRowFormValues($uc_grid->RowIndex); // Restore form values
		if ($uc->RowType == EW_ROWTYPE_EDIT) // Edit row
			$uc_grid->EditRowCnt++;
		if ($uc->CurrentAction == "F") // Confirm row
			$uc_grid->RestoreCurrentRowFormValues($uc_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$uc->RowAttrs = array_merge($uc->RowAttrs, array('data-rowindex'=>$uc_grid->RowCnt, 'id'=>'r' . $uc_grid->RowCnt . '_uc', 'data-rowtype'=>$uc->RowType));

		// Render row
		$uc_grid->RenderRow();

		// Render list options
		$uc_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($uc_grid->RowAction <> "delete" && $uc_grid->RowAction <> "insertdelete" && !($uc_grid->RowAction == "insert" && $uc->CurrentAction == "F" && $uc_grid->EmptyRow())) {
?>
	<tr<?php echo $uc->RowAttributes() ?>>
<?php

// Render list options (body, left)
$uc_grid->ListOptions->Render("body", "left", $uc_grid->RowCnt);
?>
	<?php if ($uc->co_alternativo->Visible) { // co_alternativo ?>
		<td<?php echo $uc->co_alternativo->CellAttributes() ?>>
<?php if ($uc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $uc_grid->RowCnt ?>_uc_co_alternativo" class="control-group uc_co_alternativo">
<input type="text" data-field="x_co_alternativo" name="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" id="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" size="30" maxlength="20" placeholder="<?php echo $uc->co_alternativo->PlaceHolder ?>" value="<?php echo $uc->co_alternativo->EditValue ?>"<?php echo $uc->co_alternativo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_co_alternativo" name="o<?php echo $uc_grid->RowIndex ?>_co_alternativo" id="o<?php echo $uc_grid->RowIndex ?>_co_alternativo" value="<?php echo ew_HtmlEncode($uc->co_alternativo->OldValue) ?>">
<?php } ?>
<?php if ($uc->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $uc_grid->RowCnt ?>_uc_co_alternativo" class="control-group uc_co_alternativo">
<input type="text" data-field="x_co_alternativo" name="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" id="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" size="30" maxlength="20" placeholder="<?php echo $uc->co_alternativo->PlaceHolder ?>" value="<?php echo $uc->co_alternativo->EditValue ?>"<?php echo $uc->co_alternativo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($uc->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $uc->co_alternativo->ViewAttributes() ?>>
<?php echo $uc->co_alternativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_co_alternativo" name="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" id="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" value="<?php echo ew_HtmlEncode($uc->co_alternativo->FormValue) ?>">
<input type="hidden" data-field="x_co_alternativo" name="o<?php echo $uc_grid->RowIndex ?>_co_alternativo" id="o<?php echo $uc_grid->RowIndex ?>_co_alternativo" value="<?php echo ew_HtmlEncode($uc->co_alternativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $uc_grid->PageObjName . "_row_" . $uc_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($uc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $uc_grid->RowIndex ?>_nu_uc" id="x<?php echo $uc_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc->nu_uc->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $uc_grid->RowIndex ?>_nu_uc" id="o<?php echo $uc_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc->nu_uc->OldValue) ?>">
<?php } ?>
<?php if ($uc->RowType == EW_ROWTYPE_EDIT || $uc->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $uc_grid->RowIndex ?>_nu_uc" id="x<?php echo $uc_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc->nu_uc->CurrentValue) ?>">
<?php } ?>
	<?php if ($uc->no_uc->Visible) { // no_uc ?>
		<td<?php echo $uc->no_uc->CellAttributes() ?>>
<?php if ($uc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $uc_grid->RowCnt ?>_uc_no_uc" class="control-group uc_no_uc">
<input type="text" data-field="x_no_uc" name="x<?php echo $uc_grid->RowIndex ?>_no_uc" id="x<?php echo $uc_grid->RowIndex ?>_no_uc" size="30" maxlength="120" placeholder="<?php echo $uc->no_uc->PlaceHolder ?>" value="<?php echo $uc->no_uc->EditValue ?>"<?php echo $uc->no_uc->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_uc" name="o<?php echo $uc_grid->RowIndex ?>_no_uc" id="o<?php echo $uc_grid->RowIndex ?>_no_uc" value="<?php echo ew_HtmlEncode($uc->no_uc->OldValue) ?>">
<?php } ?>
<?php if ($uc->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $uc_grid->RowCnt ?>_uc_no_uc" class="control-group uc_no_uc">
<input type="text" data-field="x_no_uc" name="x<?php echo $uc_grid->RowIndex ?>_no_uc" id="x<?php echo $uc_grid->RowIndex ?>_no_uc" size="30" maxlength="120" placeholder="<?php echo $uc->no_uc->PlaceHolder ?>" value="<?php echo $uc->no_uc->EditValue ?>"<?php echo $uc->no_uc->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($uc->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $uc->no_uc->ViewAttributes() ?>>
<?php echo $uc->no_uc->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_uc" name="x<?php echo $uc_grid->RowIndex ?>_no_uc" id="x<?php echo $uc_grid->RowIndex ?>_no_uc" value="<?php echo ew_HtmlEncode($uc->no_uc->FormValue) ?>">
<input type="hidden" data-field="x_no_uc" name="o<?php echo $uc_grid->RowIndex ?>_no_uc" id="o<?php echo $uc_grid->RowIndex ?>_no_uc" value="<?php echo ew_HtmlEncode($uc->no_uc->OldValue) ?>">
<?php } ?>
<a id="<?php echo $uc_grid->PageObjName . "_row_" . $uc_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($uc->nu_stUc->Visible) { // nu_stUc ?>
		<td<?php echo $uc->nu_stUc->CellAttributes() ?>>
<?php if ($uc->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $uc_grid->RowCnt ?>_uc_nu_stUc" class="control-group uc_nu_stUc">
<select data-field="x_nu_stUc" id="x<?php echo $uc_grid->RowIndex ?>_nu_stUc" name="x<?php echo $uc_grid->RowIndex ?>_nu_stUc"<?php echo $uc->nu_stUc->EditAttributes() ?>>
<?php
if (is_array($uc->nu_stUc->EditValue)) {
	$arwrk = $uc->nu_stUc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc->nu_stUc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $uc->nu_stUc->OldValue = "";
?>
</select>
<script type="text/javascript">
fucgrid.Lists["x_nu_stUc"].Options = <?php echo (is_array($uc->nu_stUc->EditValue)) ? ew_ArrayToJson($uc->nu_stUc->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_stUc" name="o<?php echo $uc_grid->RowIndex ?>_nu_stUc" id="o<?php echo $uc_grid->RowIndex ?>_nu_stUc" value="<?php echo ew_HtmlEncode($uc->nu_stUc->OldValue) ?>">
<?php } ?>
<?php if ($uc->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $uc_grid->RowCnt ?>_uc_nu_stUc" class="control-group uc_nu_stUc">
<select data-field="x_nu_stUc" id="x<?php echo $uc_grid->RowIndex ?>_nu_stUc" name="x<?php echo $uc_grid->RowIndex ?>_nu_stUc"<?php echo $uc->nu_stUc->EditAttributes() ?>>
<?php
if (is_array($uc->nu_stUc->EditValue)) {
	$arwrk = $uc->nu_stUc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc->nu_stUc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $uc->nu_stUc->OldValue = "";
?>
</select>
<script type="text/javascript">
fucgrid.Lists["x_nu_stUc"].Options = <?php echo (is_array($uc->nu_stUc->EditValue)) ? ew_ArrayToJson($uc->nu_stUc->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($uc->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $uc->nu_stUc->ViewAttributes() ?>>
<?php echo $uc->nu_stUc->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_stUc" name="x<?php echo $uc_grid->RowIndex ?>_nu_stUc" id="x<?php echo $uc_grid->RowIndex ?>_nu_stUc" value="<?php echo ew_HtmlEncode($uc->nu_stUc->FormValue) ?>">
<input type="hidden" data-field="x_nu_stUc" name="o<?php echo $uc_grid->RowIndex ?>_nu_stUc" id="o<?php echo $uc_grid->RowIndex ?>_nu_stUc" value="<?php echo ew_HtmlEncode($uc->nu_stUc->OldValue) ?>">
<?php } ?>
<a id="<?php echo $uc_grid->PageObjName . "_row_" . $uc_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$uc_grid->ListOptions->Render("body", "right", $uc_grid->RowCnt);
?>
	</tr>
<?php if ($uc->RowType == EW_ROWTYPE_ADD || $uc->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fucgrid.UpdateOpts(<?php echo $uc_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($uc->CurrentAction <> "gridadd" || $uc->CurrentMode == "copy")
		if (!$uc_grid->Recordset->EOF) $uc_grid->Recordset->MoveNext();
}
?>
<?php
	if ($uc->CurrentMode == "add" || $uc->CurrentMode == "copy" || $uc->CurrentMode == "edit") {
		$uc_grid->RowIndex = '$rowindex$';
		$uc_grid->LoadDefaultValues();

		// Set row properties
		$uc->ResetAttrs();
		$uc->RowAttrs = array_merge($uc->RowAttrs, array('data-rowindex'=>$uc_grid->RowIndex, 'id'=>'r0_uc', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($uc->RowAttrs["class"], "ewTemplate");
		$uc->RowType = EW_ROWTYPE_ADD;

		// Render row
		$uc_grid->RenderRow();

		// Render list options
		$uc_grid->RenderListOptions();
		$uc_grid->StartRowCnt = 0;
?>
	<tr<?php echo $uc->RowAttributes() ?>>
<?php

// Render list options (body, left)
$uc_grid->ListOptions->Render("body", "left", $uc_grid->RowIndex);
?>
	<?php if ($uc->co_alternativo->Visible) { // co_alternativo ?>
		<td>
<?php if ($uc->CurrentAction <> "F") { ?>
<input type="text" data-field="x_co_alternativo" name="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" id="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" size="30" maxlength="20" placeholder="<?php echo $uc->co_alternativo->PlaceHolder ?>" value="<?php echo $uc->co_alternativo->EditValue ?>"<?php echo $uc->co_alternativo->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $uc->co_alternativo->ViewAttributes() ?>>
<?php echo $uc->co_alternativo->ViewValue ?></span>
<input type="hidden" data-field="x_co_alternativo" name="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" id="x<?php echo $uc_grid->RowIndex ?>_co_alternativo" value="<?php echo ew_HtmlEncode($uc->co_alternativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_co_alternativo" name="o<?php echo $uc_grid->RowIndex ?>_co_alternativo" id="o<?php echo $uc_grid->RowIndex ?>_co_alternativo" value="<?php echo ew_HtmlEncode($uc->co_alternativo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($uc->no_uc->Visible) { // no_uc ?>
		<td>
<?php if ($uc->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_uc" name="x<?php echo $uc_grid->RowIndex ?>_no_uc" id="x<?php echo $uc_grid->RowIndex ?>_no_uc" size="30" maxlength="120" placeholder="<?php echo $uc->no_uc->PlaceHolder ?>" value="<?php echo $uc->no_uc->EditValue ?>"<?php echo $uc->no_uc->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $uc->no_uc->ViewAttributes() ?>>
<?php echo $uc->no_uc->ViewValue ?></span>
<input type="hidden" data-field="x_no_uc" name="x<?php echo $uc_grid->RowIndex ?>_no_uc" id="x<?php echo $uc_grid->RowIndex ?>_no_uc" value="<?php echo ew_HtmlEncode($uc->no_uc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_uc" name="o<?php echo $uc_grid->RowIndex ?>_no_uc" id="o<?php echo $uc_grid->RowIndex ?>_no_uc" value="<?php echo ew_HtmlEncode($uc->no_uc->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($uc->nu_stUc->Visible) { // nu_stUc ?>
		<td>
<?php if ($uc->CurrentAction <> "F") { ?>
<select data-field="x_nu_stUc" id="x<?php echo $uc_grid->RowIndex ?>_nu_stUc" name="x<?php echo $uc_grid->RowIndex ?>_nu_stUc"<?php echo $uc->nu_stUc->EditAttributes() ?>>
<?php
if (is_array($uc->nu_stUc->EditValue)) {
	$arwrk = $uc->nu_stUc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc->nu_stUc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $uc->nu_stUc->OldValue = "";
?>
</select>
<script type="text/javascript">
fucgrid.Lists["x_nu_stUc"].Options = <?php echo (is_array($uc->nu_stUc->EditValue)) ? ew_ArrayToJson($uc->nu_stUc->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $uc->nu_stUc->ViewAttributes() ?>>
<?php echo $uc->nu_stUc->ViewValue ?></span>
<input type="hidden" data-field="x_nu_stUc" name="x<?php echo $uc_grid->RowIndex ?>_nu_stUc" id="x<?php echo $uc_grid->RowIndex ?>_nu_stUc" value="<?php echo ew_HtmlEncode($uc->nu_stUc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_stUc" name="o<?php echo $uc_grid->RowIndex ?>_nu_stUc" id="o<?php echo $uc_grid->RowIndex ?>_nu_stUc" value="<?php echo ew_HtmlEncode($uc->nu_stUc->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$uc_grid->ListOptions->Render("body", "right", $uc_grid->RowCnt);
?>
<script type="text/javascript">
fucgrid.UpdateOpts(<?php echo $uc_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($uc->CurrentMode == "add" || $uc->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $uc_grid->FormKeyCountName ?>" id="<?php echo $uc_grid->FormKeyCountName ?>" value="<?php echo $uc_grid->KeyCount ?>">
<?php echo $uc_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($uc->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $uc_grid->FormKeyCountName ?>" id="<?php echo $uc_grid->FormKeyCountName ?>" value="<?php echo $uc_grid->KeyCount ?>">
<?php echo $uc_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($uc->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fucgrid">
</div>
<?php

// Close recordset
if ($uc_grid->Recordset)
	$uc_grid->Recordset->Close();
?>
<?php if ($uc_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($uc_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($uc->Export == "") { ?>
<script type="text/javascript">
fucgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$uc_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$uc_grid->Page_Terminate();
?>
