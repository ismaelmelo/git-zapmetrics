<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($prospecto_grid)) $prospecto_grid = new cprospecto_grid();

// Page init
$prospecto_grid->Page_Init();

// Page main
$prospecto_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$prospecto_grid->Page_Render();
?>
<?php if ($prospecto->Export == "") { ?>
<script type="text/javascript">

// Page object
var prospecto_grid = new ew_Page("prospecto_grid");
prospecto_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = prospecto_grid.PageID; // For backward compatibility

// Form object
var fprospectogrid = new ew_Form("fprospectogrid");
fprospectogrid.FormKeyCountName = '<?php echo $prospecto_grid->FormKeyCountName ?>';

// Validate form
fprospectogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_prospecto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->no_prospecto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_area");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_area->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_categoriaProspecto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->nu_categoriaProspecto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_stProspecto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->ic_stProspecto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto->ic_ativo->FldCaption()) ?>");

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
fprospectogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_prospecto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_area", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_categoriaProspecto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_stProspecto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
fprospectogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprospectogrid.ValidateRequired = true;
<?php } else { ?>
fprospectogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprospectogrid.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectogrid.Lists["x_nu_categoriaProspecto"] = {"LinkField":"x_nu_categoria","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_categoria","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($prospecto->getCurrentMasterTable() == "" && $prospecto_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $prospecto_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($prospecto->CurrentAction == "gridadd") {
	if ($prospecto->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$prospecto_grid->TotalRecs = $prospecto->SelectRecordCount();
			$prospecto_grid->Recordset = $prospecto_grid->LoadRecordset($prospecto_grid->StartRec-1, $prospecto_grid->DisplayRecs);
		} else {
			if ($prospecto_grid->Recordset = $prospecto_grid->LoadRecordset())
				$prospecto_grid->TotalRecs = $prospecto_grid->Recordset->RecordCount();
		}
		$prospecto_grid->StartRec = 1;
		$prospecto_grid->DisplayRecs = $prospecto_grid->TotalRecs;
	} else {
		$prospecto->CurrentFilter = "0=1";
		$prospecto_grid->StartRec = 1;
		$prospecto_grid->DisplayRecs = $prospecto->GridAddRowCount;
	}
	$prospecto_grid->TotalRecs = $prospecto_grid->DisplayRecs;
	$prospecto_grid->StopRec = $prospecto_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$prospecto_grid->TotalRecs = $prospecto->SelectRecordCount();
	} else {
		if ($prospecto_grid->Recordset = $prospecto_grid->LoadRecordset())
			$prospecto_grid->TotalRecs = $prospecto_grid->Recordset->RecordCount();
	}
	$prospecto_grid->StartRec = 1;
	$prospecto_grid->DisplayRecs = $prospecto_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$prospecto_grid->Recordset = $prospecto_grid->LoadRecordset($prospecto_grid->StartRec-1, $prospecto_grid->DisplayRecs);
}
$prospecto_grid->RenderOtherOptions();
?>
<?php $prospecto_grid->ShowPageHeader(); ?>
<?php
$prospecto_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fprospectogrid" class="ewForm form-horizontal">
<div id="gmp_prospecto" class="ewGridMiddlePanel">
<table id="tbl_prospectogrid" class="ewTable ewTableSeparate">
<?php echo $prospecto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$prospecto_grid->RenderListOptions();

// Render list options (header, left)
$prospecto_grid->ListOptions->Render("header", "left");
?>
<?php if ($prospecto->nu_prospecto->Visible) { // nu_prospecto ?>
	<?php if ($prospecto->SortUrl($prospecto->nu_prospecto) == "") { ?>
		<td><div id="elh_prospecto_nu_prospecto" class="prospecto_nu_prospecto"><div class="ewTableHeaderCaption"><?php echo $prospecto->nu_prospecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospecto_nu_prospecto" class="prospecto_nu_prospecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->nu_prospecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->nu_prospecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->nu_prospecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->no_prospecto->Visible) { // no_prospecto ?>
	<?php if ($prospecto->SortUrl($prospecto->no_prospecto) == "") { ?>
		<td><div id="elh_prospecto_no_prospecto" class="prospecto_no_prospecto"><div class="ewTableHeaderCaption"><?php echo $prospecto->no_prospecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospecto_no_prospecto" class="prospecto_no_prospecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->no_prospecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->no_prospecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->no_prospecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->nu_area->Visible) { // nu_area ?>
	<?php if ($prospecto->SortUrl($prospecto->nu_area) == "") { ?>
		<td><div id="elh_prospecto_nu_area" class="prospecto_nu_area"><div class="ewTableHeaderCaption"><?php echo $prospecto->nu_area->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospecto_nu_area" class="prospecto_nu_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->nu_area->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->nu_area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->nu_area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
	<?php if ($prospecto->SortUrl($prospecto->nu_categoriaProspecto) == "") { ?>
		<td><div id="elh_prospecto_nu_categoriaProspecto" class="prospecto_nu_categoriaProspecto"><div class="ewTableHeaderCaption"><?php echo $prospecto->nu_categoriaProspecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospecto_nu_categoriaProspecto" class="prospecto_nu_categoriaProspecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->nu_categoriaProspecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->nu_categoriaProspecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->nu_categoriaProspecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->ic_stProspecto->Visible) { // ic_stProspecto ?>
	<?php if ($prospecto->SortUrl($prospecto->ic_stProspecto) == "") { ?>
		<td><div id="elh_prospecto_ic_stProspecto" class="prospecto_ic_stProspecto"><div class="ewTableHeaderCaption"><?php echo $prospecto->ic_stProspecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospecto_ic_stProspecto" class="prospecto_ic_stProspecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->ic_stProspecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->ic_stProspecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->ic_stProspecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($prospecto->SortUrl($prospecto->ic_ativo) == "") { ?>
		<td><div id="elh_prospecto_ic_ativo" class="prospecto_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $prospecto->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospecto_ic_ativo" class="prospecto_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$prospecto_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$prospecto_grid->StartRec = 1;
$prospecto_grid->StopRec = $prospecto_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($prospecto_grid->FormKeyCountName) && ($prospecto->CurrentAction == "gridadd" || $prospecto->CurrentAction == "gridedit" || $prospecto->CurrentAction == "F")) {
		$prospecto_grid->KeyCount = $objForm->GetValue($prospecto_grid->FormKeyCountName);
		$prospecto_grid->StopRec = $prospecto_grid->StartRec + $prospecto_grid->KeyCount - 1;
	}
}
$prospecto_grid->RecCnt = $prospecto_grid->StartRec - 1;
if ($prospecto_grid->Recordset && !$prospecto_grid->Recordset->EOF) {
	$prospecto_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $prospecto_grid->StartRec > 1)
		$prospecto_grid->Recordset->Move($prospecto_grid->StartRec - 1);
} elseif (!$prospecto->AllowAddDeleteRow && $prospecto_grid->StopRec == 0) {
	$prospecto_grid->StopRec = $prospecto->GridAddRowCount;
}

// Initialize aggregate
$prospecto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$prospecto->ResetAttrs();
$prospecto_grid->RenderRow();
if ($prospecto->CurrentAction == "gridadd")
	$prospecto_grid->RowIndex = 0;
if ($prospecto->CurrentAction == "gridedit")
	$prospecto_grid->RowIndex = 0;
while ($prospecto_grid->RecCnt < $prospecto_grid->StopRec) {
	$prospecto_grid->RecCnt++;
	if (intval($prospecto_grid->RecCnt) >= intval($prospecto_grid->StartRec)) {
		$prospecto_grid->RowCnt++;
		if ($prospecto->CurrentAction == "gridadd" || $prospecto->CurrentAction == "gridedit" || $prospecto->CurrentAction == "F") {
			$prospecto_grid->RowIndex++;
			$objForm->Index = $prospecto_grid->RowIndex;
			if ($objForm->HasValue($prospecto_grid->FormActionName))
				$prospecto_grid->RowAction = strval($objForm->GetValue($prospecto_grid->FormActionName));
			elseif ($prospecto->CurrentAction == "gridadd")
				$prospecto_grid->RowAction = "insert";
			else
				$prospecto_grid->RowAction = "";
		}

		// Set up key count
		$prospecto_grid->KeyCount = $prospecto_grid->RowIndex;

		// Init row class and style
		$prospecto->ResetAttrs();
		$prospecto->CssClass = "";
		if ($prospecto->CurrentAction == "gridadd") {
			if ($prospecto->CurrentMode == "copy") {
				$prospecto_grid->LoadRowValues($prospecto_grid->Recordset); // Load row values
				$prospecto_grid->SetRecordKey($prospecto_grid->RowOldKey, $prospecto_grid->Recordset); // Set old record key
			} else {
				$prospecto_grid->LoadDefaultValues(); // Load default values
				$prospecto_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$prospecto_grid->LoadRowValues($prospecto_grid->Recordset); // Load row values
		}
		$prospecto->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($prospecto->CurrentAction == "gridadd") // Grid add
			$prospecto->RowType = EW_ROWTYPE_ADD; // Render add
		if ($prospecto->CurrentAction == "gridadd" && $prospecto->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$prospecto_grid->RestoreCurrentRowFormValues($prospecto_grid->RowIndex); // Restore form values
		if ($prospecto->CurrentAction == "gridedit") { // Grid edit
			if ($prospecto->EventCancelled) {
				$prospecto_grid->RestoreCurrentRowFormValues($prospecto_grid->RowIndex); // Restore form values
			}
			if ($prospecto_grid->RowAction == "insert")
				$prospecto->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$prospecto->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($prospecto->CurrentAction == "gridedit" && ($prospecto->RowType == EW_ROWTYPE_EDIT || $prospecto->RowType == EW_ROWTYPE_ADD) && $prospecto->EventCancelled) // Update failed
			$prospecto_grid->RestoreCurrentRowFormValues($prospecto_grid->RowIndex); // Restore form values
		if ($prospecto->RowType == EW_ROWTYPE_EDIT) // Edit row
			$prospecto_grid->EditRowCnt++;
		if ($prospecto->CurrentAction == "F") // Confirm row
			$prospecto_grid->RestoreCurrentRowFormValues($prospecto_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$prospecto->RowAttrs = array_merge($prospecto->RowAttrs, array('data-rowindex'=>$prospecto_grid->RowCnt, 'id'=>'r' . $prospecto_grid->RowCnt . '_prospecto', 'data-rowtype'=>$prospecto->RowType));

		// Render row
		$prospecto_grid->RenderRow();

		// Render list options
		$prospecto_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($prospecto_grid->RowAction <> "delete" && $prospecto_grid->RowAction <> "insertdelete" && !($prospecto_grid->RowAction == "insert" && $prospecto->CurrentAction == "F" && $prospecto_grid->EmptyRow())) {
?>
	<tr<?php echo $prospecto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$prospecto_grid->ListOptions->Render("body", "left", $prospecto_grid->RowCnt);
?>
	<?php if ($prospecto->nu_prospecto->Visible) { // nu_prospecto ?>
		<td<?php echo $prospecto->nu_prospecto->CellAttributes() ?>>
<?php if ($prospecto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_prospecto" name="o<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" id="o<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto->nu_prospecto->OldValue) ?>">
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_nu_prospecto" class="control-group prospecto_nu_prospecto">
<span<?php echo $prospecto->nu_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_prospecto->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_prospecto" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto->nu_prospecto->CurrentValue) ?>">
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospecto->nu_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_prospecto->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_prospecto" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto->nu_prospecto->FormValue) ?>">
<input type="hidden" data-field="x_nu_prospecto" name="o<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" id="o<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto->nu_prospecto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospecto_grid->PageObjName . "_row_" . $prospecto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->no_prospecto->Visible) { // no_prospecto ?>
		<td<?php echo $prospecto->no_prospecto->CellAttributes() ?>>
<?php if ($prospecto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_no_prospecto" class="control-group prospecto_no_prospecto">
<input type="text" data-field="x_no_prospecto" name="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" id="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" size="75" maxlength="120" placeholder="<?php echo $prospecto->no_prospecto->PlaceHolder ?>" value="<?php echo $prospecto->no_prospecto->EditValue ?>"<?php echo $prospecto->no_prospecto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_prospecto" name="o<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" id="o<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" value="<?php echo ew_HtmlEncode($prospecto->no_prospecto->OldValue) ?>">
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_no_prospecto" class="control-group prospecto_no_prospecto">
<input type="text" data-field="x_no_prospecto" name="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" id="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" size="75" maxlength="120" placeholder="<?php echo $prospecto->no_prospecto->PlaceHolder ?>" value="<?php echo $prospecto->no_prospecto->EditValue ?>"<?php echo $prospecto->no_prospecto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospecto->no_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->no_prospecto->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_prospecto" name="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" id="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" value="<?php echo ew_HtmlEncode($prospecto->no_prospecto->FormValue) ?>">
<input type="hidden" data-field="x_no_prospecto" name="o<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" id="o<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" value="<?php echo ew_HtmlEncode($prospecto->no_prospecto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospecto_grid->PageObjName . "_row_" . $prospecto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->nu_area->Visible) { // nu_area ?>
		<td<?php echo $prospecto->nu_area->CellAttributes() ?>>
<?php if ($prospecto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_nu_area" class="control-group prospecto_nu_area">
<select data-field="x_nu_area" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_area" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_area"<?php echo $prospecto->nu_area->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_area->EditValue)) {
	$arwrk = $prospecto->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_area->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $prospecto->nu_area->OldValue = "";
?>
</select>
<script type="text/javascript">
fprospectogrid.Lists["x_nu_area"].Options = <?php echo (is_array($prospecto->nu_area->EditValue)) ? ew_ArrayToJson($prospecto->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_area" name="o<?php echo $prospecto_grid->RowIndex ?>_nu_area" id="o<?php echo $prospecto_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($prospecto->nu_area->OldValue) ?>">
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_nu_area" class="control-group prospecto_nu_area">
<select data-field="x_nu_area" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_area" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_area"<?php echo $prospecto->nu_area->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_area->EditValue)) {
	$arwrk = $prospecto->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_area->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $prospecto->nu_area->OldValue = "";
?>
</select>
<script type="text/javascript">
fprospectogrid.Lists["x_nu_area"].Options = <?php echo (is_array($prospecto->nu_area->EditValue)) ? ew_ArrayToJson($prospecto->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospecto->nu_area->ViewAttributes() ?>>
<?php echo $prospecto->nu_area->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_area" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_area" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($prospecto->nu_area->FormValue) ?>">
<input type="hidden" data-field="x_nu_area" name="o<?php echo $prospecto_grid->RowIndex ?>_nu_area" id="o<?php echo $prospecto_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($prospecto->nu_area->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospecto_grid->PageObjName . "_row_" . $prospecto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
		<td<?php echo $prospecto->nu_categoriaProspecto->CellAttributes() ?>>
<?php if ($prospecto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_nu_categoriaProspecto" class="control-group prospecto_nu_categoriaProspecto">
<select data-field="x_nu_categoriaProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto"<?php echo $prospecto->nu_categoriaProspecto->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_categoriaProspecto->EditValue)) {
	$arwrk = $prospecto->nu_categoriaProspecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_categoriaProspecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $prospecto->nu_categoriaProspecto->OldValue = "";
?>
</select>
<script type="text/javascript">
fprospectogrid.Lists["x_nu_categoriaProspecto"].Options = <?php echo (is_array($prospecto->nu_categoriaProspecto->EditValue)) ? ew_ArrayToJson($prospecto->nu_categoriaProspecto->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_categoriaProspecto" name="o<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" id="o<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" value="<?php echo ew_HtmlEncode($prospecto->nu_categoriaProspecto->OldValue) ?>">
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_nu_categoriaProspecto" class="control-group prospecto_nu_categoriaProspecto">
<select data-field="x_nu_categoriaProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto"<?php echo $prospecto->nu_categoriaProspecto->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_categoriaProspecto->EditValue)) {
	$arwrk = $prospecto->nu_categoriaProspecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_categoriaProspecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $prospecto->nu_categoriaProspecto->OldValue = "";
?>
</select>
<script type="text/javascript">
fprospectogrid.Lists["x_nu_categoriaProspecto"].Options = <?php echo (is_array($prospecto->nu_categoriaProspecto->EditValue)) ? ew_ArrayToJson($prospecto->nu_categoriaProspecto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospecto->nu_categoriaProspecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_categoriaProspecto->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_categoriaProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" value="<?php echo ew_HtmlEncode($prospecto->nu_categoriaProspecto->FormValue) ?>">
<input type="hidden" data-field="x_nu_categoriaProspecto" name="o<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" id="o<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" value="<?php echo ew_HtmlEncode($prospecto->nu_categoriaProspecto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospecto_grid->PageObjName . "_row_" . $prospecto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->ic_stProspecto->Visible) { // ic_stProspecto ?>
		<td<?php echo $prospecto->ic_stProspecto->CellAttributes() ?>>
<?php if ($prospecto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_ic_stProspecto" class="control-group prospecto_ic_stProspecto">
<div id="tp_x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" value="{value}"<?php echo $prospecto->ic_stProspecto->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_stProspecto->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_stProspecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_stProspecto->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $prospecto->ic_stProspecto->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_stProspecto" name="o<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="o<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" value="<?php echo ew_HtmlEncode($prospecto->ic_stProspecto->OldValue) ?>">
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_ic_stProspecto" class="control-group prospecto_ic_stProspecto">
<div id="tp_x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" value="{value}"<?php echo $prospecto->ic_stProspecto->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_stProspecto->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_stProspecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_stProspecto->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $prospecto->ic_stProspecto->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospecto->ic_stProspecto->ViewAttributes() ?>>
<?php echo $prospecto->ic_stProspecto->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_stProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" value="<?php echo ew_HtmlEncode($prospecto->ic_stProspecto->FormValue) ?>">
<input type="hidden" data-field="x_ic_stProspecto" name="o<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="o<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" value="<?php echo ew_HtmlEncode($prospecto->ic_stProspecto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospecto_grid->PageObjName . "_row_" . $prospecto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $prospecto->ic_ativo->CellAttributes() ?>>
<?php if ($prospecto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_ic_ativo" class="control-group prospecto_ic_ativo">
<div id="tp_x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $prospecto->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $prospecto->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="o<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($prospecto->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospecto_grid->RowCnt ?>_prospecto_ic_ativo" class="control-group prospecto_ic_ativo">
<div id="tp_x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $prospecto->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $prospecto->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($prospecto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospecto->ic_ativo->ViewAttributes() ?>>
<?php echo $prospecto->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($prospecto->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="o<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($prospecto->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospecto_grid->PageObjName . "_row_" . $prospecto_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$prospecto_grid->ListOptions->Render("body", "right", $prospecto_grid->RowCnt);
?>
	</tr>
<?php if ($prospecto->RowType == EW_ROWTYPE_ADD || $prospecto->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fprospectogrid.UpdateOpts(<?php echo $prospecto_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($prospecto->CurrentAction <> "gridadd" || $prospecto->CurrentMode == "copy")
		if (!$prospecto_grid->Recordset->EOF) $prospecto_grid->Recordset->MoveNext();
}
?>
<?php
	if ($prospecto->CurrentMode == "add" || $prospecto->CurrentMode == "copy" || $prospecto->CurrentMode == "edit") {
		$prospecto_grid->RowIndex = '$rowindex$';
		$prospecto_grid->LoadDefaultValues();

		// Set row properties
		$prospecto->ResetAttrs();
		$prospecto->RowAttrs = array_merge($prospecto->RowAttrs, array('data-rowindex'=>$prospecto_grid->RowIndex, 'id'=>'r0_prospecto', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($prospecto->RowAttrs["class"], "ewTemplate");
		$prospecto->RowType = EW_ROWTYPE_ADD;

		// Render row
		$prospecto_grid->RenderRow();

		// Render list options
		$prospecto_grid->RenderListOptions();
		$prospecto_grid->StartRowCnt = 0;
?>
	<tr<?php echo $prospecto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$prospecto_grid->ListOptions->Render("body", "left", $prospecto_grid->RowIndex);
?>
	<?php if ($prospecto->nu_prospecto->Visible) { // nu_prospecto ?>
		<td>
<?php if ($prospecto->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $prospecto->nu_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_prospecto->ViewValue ?></span>
<input type="hidden" data-field="x_nu_prospecto" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto->nu_prospecto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_prospecto" name="o<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" id="o<?php echo $prospecto_grid->RowIndex ?>_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto->nu_prospecto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($prospecto->no_prospecto->Visible) { // no_prospecto ?>
		<td>
<?php if ($prospecto->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_prospecto" name="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" id="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" size="75" maxlength="120" placeholder="<?php echo $prospecto->no_prospecto->PlaceHolder ?>" value="<?php echo $prospecto->no_prospecto->EditValue ?>"<?php echo $prospecto->no_prospecto->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $prospecto->no_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->no_prospecto->ViewValue ?></span>
<input type="hidden" data-field="x_no_prospecto" name="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" id="x<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" value="<?php echo ew_HtmlEncode($prospecto->no_prospecto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_prospecto" name="o<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" id="o<?php echo $prospecto_grid->RowIndex ?>_no_prospecto" value="<?php echo ew_HtmlEncode($prospecto->no_prospecto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($prospecto->nu_area->Visible) { // nu_area ?>
		<td>
<?php if ($prospecto->CurrentAction <> "F") { ?>
<select data-field="x_nu_area" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_area" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_area"<?php echo $prospecto->nu_area->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_area->EditValue)) {
	$arwrk = $prospecto->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_area->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $prospecto->nu_area->OldValue = "";
?>
</select>
<script type="text/javascript">
fprospectogrid.Lists["x_nu_area"].Options = <?php echo (is_array($prospecto->nu_area->EditValue)) ? ew_ArrayToJson($prospecto->nu_area->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $prospecto->nu_area->ViewAttributes() ?>>
<?php echo $prospecto->nu_area->ViewValue ?></span>
<input type="hidden" data-field="x_nu_area" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_area" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($prospecto->nu_area->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_area" name="o<?php echo $prospecto_grid->RowIndex ?>_nu_area" id="o<?php echo $prospecto_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($prospecto->nu_area->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($prospecto->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
		<td>
<?php if ($prospecto->CurrentAction <> "F") { ?>
<select data-field="x_nu_categoriaProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto"<?php echo $prospecto->nu_categoriaProspecto->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_categoriaProspecto->EditValue)) {
	$arwrk = $prospecto->nu_categoriaProspecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_categoriaProspecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $prospecto->nu_categoriaProspecto->OldValue = "";
?>
</select>
<script type="text/javascript">
fprospectogrid.Lists["x_nu_categoriaProspecto"].Options = <?php echo (is_array($prospecto->nu_categoriaProspecto->EditValue)) ? ew_ArrayToJson($prospecto->nu_categoriaProspecto->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $prospecto->nu_categoriaProspecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_categoriaProspecto->ViewValue ?></span>
<input type="hidden" data-field="x_nu_categoriaProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" value="<?php echo ew_HtmlEncode($prospecto->nu_categoriaProspecto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_categoriaProspecto" name="o<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" id="o<?php echo $prospecto_grid->RowIndex ?>_nu_categoriaProspecto" value="<?php echo ew_HtmlEncode($prospecto->nu_categoriaProspecto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($prospecto->ic_stProspecto->Visible) { // ic_stProspecto ?>
		<td>
<?php if ($prospecto->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" value="{value}"<?php echo $prospecto->ic_stProspecto->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_stProspecto->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_stProspecto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_stProspecto->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $prospecto->ic_stProspecto->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $prospecto->ic_stProspecto->ViewAttributes() ?>>
<?php echo $prospecto->ic_stProspecto->ViewValue ?></span>
<input type="hidden" data-field="x_ic_stProspecto" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" value="<?php echo ew_HtmlEncode($prospecto->ic_stProspecto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_stProspecto" name="o<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" id="o<?php echo $prospecto_grid->RowIndex ?>_ic_stProspecto" value="<?php echo ew_HtmlEncode($prospecto->ic_stProspecto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($prospecto->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($prospecto->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $prospecto->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $prospecto->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $prospecto->ic_ativo->ViewAttributes() ?>>
<?php echo $prospecto->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="x<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($prospecto->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" id="o<?php echo $prospecto_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($prospecto->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$prospecto_grid->ListOptions->Render("body", "right", $prospecto_grid->RowCnt);
?>
<script type="text/javascript">
fprospectogrid.UpdateOpts(<?php echo $prospecto_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($prospecto->CurrentMode == "add" || $prospecto->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $prospecto_grid->FormKeyCountName ?>" id="<?php echo $prospecto_grid->FormKeyCountName ?>" value="<?php echo $prospecto_grid->KeyCount ?>">
<?php echo $prospecto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($prospecto->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $prospecto_grid->FormKeyCountName ?>" id="<?php echo $prospecto_grid->FormKeyCountName ?>" value="<?php echo $prospecto_grid->KeyCount ?>">
<?php echo $prospecto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($prospecto->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fprospectogrid">
</div>
<?php

// Close recordset
if ($prospecto_grid->Recordset)
	$prospecto_grid->Recordset->Close();
?>
<?php if ($prospecto_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($prospecto_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($prospecto->Export == "") { ?>
<script type="text/javascript">
fprospectogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$prospecto_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$prospecto_grid->Page_Terminate();
?>
