<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($roteiro_grid)) $roteiro_grid = new croteiro_grid();

// Page init
$roteiro_grid->Page_Init();

// Page main
$roteiro_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$roteiro_grid->Page_Render();
?>
<?php if ($roteiro->Export == "") { ?>
<script type="text/javascript">

// Page object
var roteiro_grid = new ew_Page("roteiro_grid");
roteiro_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = roteiro_grid.PageID; // For backward compatibility

// Form object
var froteirogrid = new ew_Form("froteirogrid");
froteirogrid.FormKeyCountName = '<?php echo $roteiro_grid->FormKeyCountName ?>';

// Validate form
froteirogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_metodologia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($roteiro->nu_metodologia->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_roteiro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($roteiro->no_roteiro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($roteiro->ic_ativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ordem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($roteiro->nu_ordem->FldErrMsg()) ?>");

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
froteirogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_metodologia", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_roteiro", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_ordem", false)) return false;
	return true;
}

// Form_CustomValidate event
froteirogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
froteirogrid.ValidateRequired = true;
<?php } else { ?>
froteirogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
froteirogrid.Lists["x_nu_metodologia"] = {"LinkField":"x_nu_metodologia","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metodologia","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($roteiro->getCurrentMasterTable() == "" && $roteiro_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $roteiro_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($roteiro->CurrentAction == "gridadd") {
	if ($roteiro->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$roteiro_grid->TotalRecs = $roteiro->SelectRecordCount();
			$roteiro_grid->Recordset = $roteiro_grid->LoadRecordset($roteiro_grid->StartRec-1, $roteiro_grid->DisplayRecs);
		} else {
			if ($roteiro_grid->Recordset = $roteiro_grid->LoadRecordset())
				$roteiro_grid->TotalRecs = $roteiro_grid->Recordset->RecordCount();
		}
		$roteiro_grid->StartRec = 1;
		$roteiro_grid->DisplayRecs = $roteiro_grid->TotalRecs;
	} else {
		$roteiro->CurrentFilter = "0=1";
		$roteiro_grid->StartRec = 1;
		$roteiro_grid->DisplayRecs = $roteiro->GridAddRowCount;
	}
	$roteiro_grid->TotalRecs = $roteiro_grid->DisplayRecs;
	$roteiro_grid->StopRec = $roteiro_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$roteiro_grid->TotalRecs = $roteiro->SelectRecordCount();
	} else {
		if ($roteiro_grid->Recordset = $roteiro_grid->LoadRecordset())
			$roteiro_grid->TotalRecs = $roteiro_grid->Recordset->RecordCount();
	}
	$roteiro_grid->StartRec = 1;
	$roteiro_grid->DisplayRecs = $roteiro_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$roteiro_grid->Recordset = $roteiro_grid->LoadRecordset($roteiro_grid->StartRec-1, $roteiro_grid->DisplayRecs);
}
$roteiro_grid->RenderOtherOptions();
?>
<?php $roteiro_grid->ShowPageHeader(); ?>
<?php
$roteiro_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="froteirogrid" class="ewForm form-horizontal">
<div id="gmp_roteiro" class="ewGridMiddlePanel">
<table id="tbl_roteirogrid" class="ewTable ewTableSeparate">
<?php echo $roteiro->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$roteiro_grid->RenderListOptions();

// Render list options (header, left)
$roteiro_grid->ListOptions->Render("header", "left");
?>
<?php if ($roteiro->nu_metodologia->Visible) { // nu_metodologia ?>
	<?php if ($roteiro->SortUrl($roteiro->nu_metodologia) == "") { ?>
		<td><div id="elh_roteiro_nu_metodologia" class="roteiro_nu_metodologia"><div class="ewTableHeaderCaption"><?php echo $roteiro->nu_metodologia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_roteiro_nu_metodologia" class="roteiro_nu_metodologia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $roteiro->nu_metodologia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($roteiro->nu_metodologia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($roteiro->nu_metodologia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($roteiro->no_roteiro->Visible) { // no_roteiro ?>
	<?php if ($roteiro->SortUrl($roteiro->no_roteiro) == "") { ?>
		<td><div id="elh_roteiro_no_roteiro" class="roteiro_no_roteiro"><div class="ewTableHeaderCaption"><?php echo $roteiro->no_roteiro->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_roteiro_no_roteiro" class="roteiro_no_roteiro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $roteiro->no_roteiro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($roteiro->no_roteiro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($roteiro->no_roteiro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($roteiro->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($roteiro->SortUrl($roteiro->ic_ativo) == "") { ?>
		<td><div id="elh_roteiro_ic_ativo" class="roteiro_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $roteiro->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_roteiro_ic_ativo" class="roteiro_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $roteiro->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($roteiro->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($roteiro->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($roteiro->nu_ordem->Visible) { // nu_ordem ?>
	<?php if ($roteiro->SortUrl($roteiro->nu_ordem) == "") { ?>
		<td><div id="elh_roteiro_nu_ordem" class="roteiro_nu_ordem"><div class="ewTableHeaderCaption"><?php echo $roteiro->nu_ordem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_roteiro_nu_ordem" class="roteiro_nu_ordem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $roteiro->nu_ordem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($roteiro->nu_ordem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($roteiro->nu_ordem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$roteiro_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$roteiro_grid->StartRec = 1;
$roteiro_grid->StopRec = $roteiro_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($roteiro_grid->FormKeyCountName) && ($roteiro->CurrentAction == "gridadd" || $roteiro->CurrentAction == "gridedit" || $roteiro->CurrentAction == "F")) {
		$roteiro_grid->KeyCount = $objForm->GetValue($roteiro_grid->FormKeyCountName);
		$roteiro_grid->StopRec = $roteiro_grid->StartRec + $roteiro_grid->KeyCount - 1;
	}
}
$roteiro_grid->RecCnt = $roteiro_grid->StartRec - 1;
if ($roteiro_grid->Recordset && !$roteiro_grid->Recordset->EOF) {
	$roteiro_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $roteiro_grid->StartRec > 1)
		$roteiro_grid->Recordset->Move($roteiro_grid->StartRec - 1);
} elseif (!$roteiro->AllowAddDeleteRow && $roteiro_grid->StopRec == 0) {
	$roteiro_grid->StopRec = $roteiro->GridAddRowCount;
}

// Initialize aggregate
$roteiro->RowType = EW_ROWTYPE_AGGREGATEINIT;
$roteiro->ResetAttrs();
$roteiro_grid->RenderRow();
if ($roteiro->CurrentAction == "gridadd")
	$roteiro_grid->RowIndex = 0;
if ($roteiro->CurrentAction == "gridedit")
	$roteiro_grid->RowIndex = 0;
while ($roteiro_grid->RecCnt < $roteiro_grid->StopRec) {
	$roteiro_grid->RecCnt++;
	if (intval($roteiro_grid->RecCnt) >= intval($roteiro_grid->StartRec)) {
		$roteiro_grid->RowCnt++;
		if ($roteiro->CurrentAction == "gridadd" || $roteiro->CurrentAction == "gridedit" || $roteiro->CurrentAction == "F") {
			$roteiro_grid->RowIndex++;
			$objForm->Index = $roteiro_grid->RowIndex;
			if ($objForm->HasValue($roteiro_grid->FormActionName))
				$roteiro_grid->RowAction = strval($objForm->GetValue($roteiro_grid->FormActionName));
			elseif ($roteiro->CurrentAction == "gridadd")
				$roteiro_grid->RowAction = "insert";
			else
				$roteiro_grid->RowAction = "";
		}

		// Set up key count
		$roteiro_grid->KeyCount = $roteiro_grid->RowIndex;

		// Init row class and style
		$roteiro->ResetAttrs();
		$roteiro->CssClass = "";
		if ($roteiro->CurrentAction == "gridadd") {
			if ($roteiro->CurrentMode == "copy") {
				$roteiro_grid->LoadRowValues($roteiro_grid->Recordset); // Load row values
				$roteiro_grid->SetRecordKey($roteiro_grid->RowOldKey, $roteiro_grid->Recordset); // Set old record key
			} else {
				$roteiro_grid->LoadDefaultValues(); // Load default values
				$roteiro_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$roteiro_grid->LoadRowValues($roteiro_grid->Recordset); // Load row values
		}
		$roteiro->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($roteiro->CurrentAction == "gridadd") // Grid add
			$roteiro->RowType = EW_ROWTYPE_ADD; // Render add
		if ($roteiro->CurrentAction == "gridadd" && $roteiro->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$roteiro_grid->RestoreCurrentRowFormValues($roteiro_grid->RowIndex); // Restore form values
		if ($roteiro->CurrentAction == "gridedit") { // Grid edit
			if ($roteiro->EventCancelled) {
				$roteiro_grid->RestoreCurrentRowFormValues($roteiro_grid->RowIndex); // Restore form values
			}
			if ($roteiro_grid->RowAction == "insert")
				$roteiro->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$roteiro->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($roteiro->CurrentAction == "gridedit" && ($roteiro->RowType == EW_ROWTYPE_EDIT || $roteiro->RowType == EW_ROWTYPE_ADD) && $roteiro->EventCancelled) // Update failed
			$roteiro_grid->RestoreCurrentRowFormValues($roteiro_grid->RowIndex); // Restore form values
		if ($roteiro->RowType == EW_ROWTYPE_EDIT) // Edit row
			$roteiro_grid->EditRowCnt++;
		if ($roteiro->CurrentAction == "F") // Confirm row
			$roteiro_grid->RestoreCurrentRowFormValues($roteiro_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$roteiro->RowAttrs = array_merge($roteiro->RowAttrs, array('data-rowindex'=>$roteiro_grid->RowCnt, 'id'=>'r' . $roteiro_grid->RowCnt . '_roteiro', 'data-rowtype'=>$roteiro->RowType));

		// Render row
		$roteiro_grid->RenderRow();

		// Render list options
		$roteiro_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($roteiro_grid->RowAction <> "delete" && $roteiro_grid->RowAction <> "insertdelete" && !($roteiro_grid->RowAction == "insert" && $roteiro->CurrentAction == "F" && $roteiro_grid->EmptyRow())) {
?>
	<tr<?php echo $roteiro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$roteiro_grid->ListOptions->Render("body", "left", $roteiro_grid->RowCnt);
?>
	<?php if ($roteiro->nu_metodologia->Visible) { // nu_metodologia ?>
		<td<?php echo $roteiro->nu_metodologia->CellAttributes() ?>>
<?php if ($roteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($roteiro->nu_metodologia->getSessionValue() <> "") { ?>
<span<?php echo $roteiro->nu_metodologia->ViewAttributes() ?>>
<?php echo $roteiro->nu_metodologia->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" value="<?php echo ew_HtmlEncode($roteiro->nu_metodologia->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_metodologia" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia"<?php echo $roteiro->nu_metodologia->EditAttributes() ?>>
<?php
if (is_array($roteiro->nu_metodologia->EditValue)) {
	$arwrk = $roteiro->nu_metodologia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($roteiro->nu_metodologia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $roteiro->nu_metodologia->OldValue = "";
?>
</select>
<script type="text/javascript">
froteirogrid.Lists["x_nu_metodologia"].Options = <?php echo (is_array($roteiro->nu_metodologia->EditValue)) ? ew_ArrayToJson($roteiro->nu_metodologia->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_nu_metodologia" name="o<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" id="o<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" value="<?php echo ew_HtmlEncode($roteiro->nu_metodologia->OldValue) ?>">
<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $roteiro->nu_metodologia->ViewAttributes() ?>>
<?php echo $roteiro->nu_metodologia->EditValue ?></span>
<input type="hidden" data-field="x_nu_metodologia" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" value="<?php echo ew_HtmlEncode($roteiro->nu_metodologia->CurrentValue) ?>">
<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $roteiro->nu_metodologia->ViewAttributes() ?>>
<?php echo $roteiro->nu_metodologia->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_metodologia" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" value="<?php echo ew_HtmlEncode($roteiro->nu_metodologia->FormValue) ?>">
<input type="hidden" data-field="x_nu_metodologia" name="o<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" id="o<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" value="<?php echo ew_HtmlEncode($roteiro->nu_metodologia->OldValue) ?>">
<?php } ?>
<a id="<?php echo $roteiro_grid->PageObjName . "_row_" . $roteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_roteiro" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_roteiro" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_roteiro" value="<?php echo ew_HtmlEncode($roteiro->nu_roteiro->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_roteiro" name="o<?php echo $roteiro_grid->RowIndex ?>_nu_roteiro" id="o<?php echo $roteiro_grid->RowIndex ?>_nu_roteiro" value="<?php echo ew_HtmlEncode($roteiro->nu_roteiro->OldValue) ?>">
<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_EDIT || $roteiro->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_roteiro" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_roteiro" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_roteiro" value="<?php echo ew_HtmlEncode($roteiro->nu_roteiro->CurrentValue) ?>">
<?php } ?>
	<?php if ($roteiro->no_roteiro->Visible) { // no_roteiro ?>
		<td<?php echo $roteiro->no_roteiro->CellAttributes() ?>>
<?php if ($roteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $roteiro_grid->RowCnt ?>_roteiro_no_roteiro" class="control-group roteiro_no_roteiro">
<input type="text" data-field="x_no_roteiro" name="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" id="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" size="30" maxlength="75" placeholder="<?php echo $roteiro->no_roteiro->PlaceHolder ?>" value="<?php echo $roteiro->no_roteiro->EditValue ?>"<?php echo $roteiro->no_roteiro->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_roteiro" name="o<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" id="o<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" value="<?php echo ew_HtmlEncode($roteiro->no_roteiro->OldValue) ?>">
<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $roteiro_grid->RowCnt ?>_roteiro_no_roteiro" class="control-group roteiro_no_roteiro">
<input type="text" data-field="x_no_roteiro" name="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" id="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" size="30" maxlength="75" placeholder="<?php echo $roteiro->no_roteiro->PlaceHolder ?>" value="<?php echo $roteiro->no_roteiro->EditValue ?>"<?php echo $roteiro->no_roteiro->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $roteiro->no_roteiro->ViewAttributes() ?>>
<?php echo $roteiro->no_roteiro->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_roteiro" name="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" id="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" value="<?php echo ew_HtmlEncode($roteiro->no_roteiro->FormValue) ?>">
<input type="hidden" data-field="x_no_roteiro" name="o<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" id="o<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" value="<?php echo ew_HtmlEncode($roteiro->no_roteiro->OldValue) ?>">
<?php } ?>
<a id="<?php echo $roteiro_grid->PageObjName . "_row_" . $roteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($roteiro->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $roteiro->ic_ativo->CellAttributes() ?>>
<?php if ($roteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $roteiro_grid->RowCnt ?>_roteiro_ic_ativo" class="control-group roteiro_ic_ativo">
<div id="tp_x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $roteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $roteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($roteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $roteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $roteiro->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="o<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($roteiro->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $roteiro_grid->RowCnt ?>_roteiro_ic_ativo" class="control-group roteiro_ic_ativo">
<div id="tp_x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $roteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $roteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($roteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $roteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $roteiro->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $roteiro->ic_ativo->ViewAttributes() ?>>
<?php echo $roteiro->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($roteiro->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="o<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($roteiro->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $roteiro_grid->PageObjName . "_row_" . $roteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($roteiro->nu_ordem->Visible) { // nu_ordem ?>
		<td<?php echo $roteiro->nu_ordem->CellAttributes() ?>>
<?php if ($roteiro->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $roteiro_grid->RowCnt ?>_roteiro_nu_ordem" class="control-group roteiro_nu_ordem">
<input type="text" data-field="x_nu_ordem" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $roteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $roteiro->nu_ordem->EditValue ?>"<?php echo $roteiro->nu_ordem->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" id="o<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($roteiro->nu_ordem->OldValue) ?>">
<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $roteiro_grid->RowCnt ?>_roteiro_nu_ordem" class="control-group roteiro_nu_ordem">
<input type="text" data-field="x_nu_ordem" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $roteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $roteiro->nu_ordem->EditValue ?>"<?php echo $roteiro->nu_ordem->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($roteiro->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $roteiro->nu_ordem->ViewAttributes() ?>>
<?php echo $roteiro->nu_ordem->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_ordem" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($roteiro->nu_ordem->FormValue) ?>">
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" id="o<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($roteiro->nu_ordem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $roteiro_grid->PageObjName . "_row_" . $roteiro_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$roteiro_grid->ListOptions->Render("body", "right", $roteiro_grid->RowCnt);
?>
	</tr>
<?php if ($roteiro->RowType == EW_ROWTYPE_ADD || $roteiro->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
froteirogrid.UpdateOpts(<?php echo $roteiro_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($roteiro->CurrentAction <> "gridadd" || $roteiro->CurrentMode == "copy")
		if (!$roteiro_grid->Recordset->EOF) $roteiro_grid->Recordset->MoveNext();
}
?>
<?php
	if ($roteiro->CurrentMode == "add" || $roteiro->CurrentMode == "copy" || $roteiro->CurrentMode == "edit") {
		$roteiro_grid->RowIndex = '$rowindex$';
		$roteiro_grid->LoadDefaultValues();

		// Set row properties
		$roteiro->ResetAttrs();
		$roteiro->RowAttrs = array_merge($roteiro->RowAttrs, array('data-rowindex'=>$roteiro_grid->RowIndex, 'id'=>'r0_roteiro', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($roteiro->RowAttrs["class"], "ewTemplate");
		$roteiro->RowType = EW_ROWTYPE_ADD;

		// Render row
		$roteiro_grid->RenderRow();

		// Render list options
		$roteiro_grid->RenderListOptions();
		$roteiro_grid->StartRowCnt = 0;
?>
	<tr<?php echo $roteiro->RowAttributes() ?>>
<?php

// Render list options (body, left)
$roteiro_grid->ListOptions->Render("body", "left", $roteiro_grid->RowIndex);
?>
	<?php if ($roteiro->nu_metodologia->Visible) { // nu_metodologia ?>
		<td>
<?php if ($roteiro->CurrentAction <> "F") { ?>
<?php if ($roteiro->nu_metodologia->getSessionValue() <> "") { ?>
<span<?php echo $roteiro->nu_metodologia->ViewAttributes() ?>>
<?php echo $roteiro->nu_metodologia->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" value="<?php echo ew_HtmlEncode($roteiro->nu_metodologia->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_metodologia" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia"<?php echo $roteiro->nu_metodologia->EditAttributes() ?>>
<?php
if (is_array($roteiro->nu_metodologia->EditValue)) {
	$arwrk = $roteiro->nu_metodologia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($roteiro->nu_metodologia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $roteiro->nu_metodologia->OldValue = "";
?>
</select>
<script type="text/javascript">
froteirogrid.Lists["x_nu_metodologia"].Options = <?php echo (is_array($roteiro->nu_metodologia->EditValue)) ? ew_ArrayToJson($roteiro->nu_metodologia->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $roteiro->nu_metodologia->ViewAttributes() ?>>
<?php echo $roteiro->nu_metodologia->ViewValue ?></span>
<input type="hidden" data-field="x_nu_metodologia" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" value="<?php echo ew_HtmlEncode($roteiro->nu_metodologia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_metodologia" name="o<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" id="o<?php echo $roteiro_grid->RowIndex ?>_nu_metodologia" value="<?php echo ew_HtmlEncode($roteiro->nu_metodologia->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($roteiro->no_roteiro->Visible) { // no_roteiro ?>
		<td>
<?php if ($roteiro->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_roteiro" name="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" id="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" size="30" maxlength="75" placeholder="<?php echo $roteiro->no_roteiro->PlaceHolder ?>" value="<?php echo $roteiro->no_roteiro->EditValue ?>"<?php echo $roteiro->no_roteiro->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $roteiro->no_roteiro->ViewAttributes() ?>>
<?php echo $roteiro->no_roteiro->ViewValue ?></span>
<input type="hidden" data-field="x_no_roteiro" name="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" id="x<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" value="<?php echo ew_HtmlEncode($roteiro->no_roteiro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_roteiro" name="o<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" id="o<?php echo $roteiro_grid->RowIndex ?>_no_roteiro" value="<?php echo ew_HtmlEncode($roteiro->no_roteiro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($roteiro->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($roteiro->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $roteiro->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $roteiro->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($roteiro->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $roteiro->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $roteiro->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $roteiro->ic_ativo->ViewAttributes() ?>>
<?php echo $roteiro->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="x<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($roteiro->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" id="o<?php echo $roteiro_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($roteiro->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($roteiro->nu_ordem->Visible) { // nu_ordem ?>
		<td>
<?php if ($roteiro->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_ordem" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $roteiro->nu_ordem->PlaceHolder ?>" value="<?php echo $roteiro->nu_ordem->EditValue ?>"<?php echo $roteiro->nu_ordem->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $roteiro->nu_ordem->ViewAttributes() ?>>
<?php echo $roteiro->nu_ordem->ViewValue ?></span>
<input type="hidden" data-field="x_nu_ordem" name="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" id="x<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($roteiro->nu_ordem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" id="o<?php echo $roteiro_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($roteiro->nu_ordem->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$roteiro_grid->ListOptions->Render("body", "right", $roteiro_grid->RowCnt);
?>
<script type="text/javascript">
froteirogrid.UpdateOpts(<?php echo $roteiro_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($roteiro->CurrentMode == "add" || $roteiro->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $roteiro_grid->FormKeyCountName ?>" id="<?php echo $roteiro_grid->FormKeyCountName ?>" value="<?php echo $roteiro_grid->KeyCount ?>">
<?php echo $roteiro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($roteiro->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $roteiro_grid->FormKeyCountName ?>" id="<?php echo $roteiro_grid->FormKeyCountName ?>" value="<?php echo $roteiro_grid->KeyCount ?>">
<?php echo $roteiro_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($roteiro->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="froteirogrid">
</div>
<?php

// Close recordset
if ($roteiro_grid->Recordset)
	$roteiro_grid->Recordset->Close();
?>
<?php if ($roteiro_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($roteiro_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($roteiro->Export == "") { ?>
<script type="text/javascript">
froteirogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$roteiro_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$roteiro_grid->Page_Terminate();
?>
