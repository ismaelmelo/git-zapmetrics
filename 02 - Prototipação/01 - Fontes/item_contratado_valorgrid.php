<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($Item_contratado_valor_grid)) $Item_contratado_valor_grid = new cItem_contratado_valor_grid();

// Page init
$Item_contratado_valor_grid->Page_Init();

// Page main
$Item_contratado_valor_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$Item_contratado_valor_grid->Page_Render();
?>
<?php if ($Item_contratado_valor->Export == "") { ?>
<script type="text/javascript">

// Page object
var Item_contratado_valor_grid = new ew_Page("Item_contratado_valor_grid");
Item_contratado_valor_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = Item_contratado_valor_grid.PageID; // For backward compatibility

// Form object
var fItem_contratado_valorgrid = new ew_Form("fItem_contratado_valorgrid");
fItem_contratado_valorgrid.FormKeyCountName = '<?php echo $Item_contratado_valor_grid->FormKeyCountName ?>';

// Validate form
fItem_contratado_valorgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_vr_item");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($Item_contratado_valor->vr_item->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_item");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($Item_contratado_valor->vr_item->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_dt_valor");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($Item_contratado_valor->dt_valor->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_valor");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($Item_contratado_valor->dt_valor->FldErrMsg()) ?>");

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
fItem_contratado_valorgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "vr_item", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dt_valor", false)) return false;
	return true;
}

// Form_CustomValidate event
fItem_contratado_valorgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fItem_contratado_valorgrid.ValidateRequired = true;
<?php } else { ?>
fItem_contratado_valorgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($Item_contratado_valor->getCurrentMasterTable() == "" && $Item_contratado_valor_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $Item_contratado_valor_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($Item_contratado_valor->CurrentAction == "gridadd") {
	if ($Item_contratado_valor->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$Item_contratado_valor_grid->TotalRecs = $Item_contratado_valor->SelectRecordCount();
			$Item_contratado_valor_grid->Recordset = $Item_contratado_valor_grid->LoadRecordset($Item_contratado_valor_grid->StartRec-1, $Item_contratado_valor_grid->DisplayRecs);
		} else {
			if ($Item_contratado_valor_grid->Recordset = $Item_contratado_valor_grid->LoadRecordset())
				$Item_contratado_valor_grid->TotalRecs = $Item_contratado_valor_grid->Recordset->RecordCount();
		}
		$Item_contratado_valor_grid->StartRec = 1;
		$Item_contratado_valor_grid->DisplayRecs = $Item_contratado_valor_grid->TotalRecs;
	} else {
		$Item_contratado_valor->CurrentFilter = "0=1";
		$Item_contratado_valor_grid->StartRec = 1;
		$Item_contratado_valor_grid->DisplayRecs = $Item_contratado_valor->GridAddRowCount;
	}
	$Item_contratado_valor_grid->TotalRecs = $Item_contratado_valor_grid->DisplayRecs;
	$Item_contratado_valor_grid->StopRec = $Item_contratado_valor_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$Item_contratado_valor_grid->TotalRecs = $Item_contratado_valor->SelectRecordCount();
	} else {
		if ($Item_contratado_valor_grid->Recordset = $Item_contratado_valor_grid->LoadRecordset())
			$Item_contratado_valor_grid->TotalRecs = $Item_contratado_valor_grid->Recordset->RecordCount();
	}
	$Item_contratado_valor_grid->StartRec = 1;
	$Item_contratado_valor_grid->DisplayRecs = $Item_contratado_valor_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$Item_contratado_valor_grid->Recordset = $Item_contratado_valor_grid->LoadRecordset($Item_contratado_valor_grid->StartRec-1, $Item_contratado_valor_grid->DisplayRecs);
}
$Item_contratado_valor_grid->RenderOtherOptions();
?>
<?php $Item_contratado_valor_grid->ShowPageHeader(); ?>
<?php
$Item_contratado_valor_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fItem_contratado_valorgrid" class="ewForm form-horizontal">
<div id="gmp_Item_contratado_valor" class="ewGridMiddlePanel">
<table id="tbl_Item_contratado_valorgrid" class="ewTable ewTableSeparate">
<?php echo $Item_contratado_valor->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$Item_contratado_valor_grid->RenderListOptions();

// Render list options (header, left)
$Item_contratado_valor_grid->ListOptions->Render("header", "left");
?>
<?php if ($Item_contratado_valor->vr_item->Visible) { // vr_item ?>
	<?php if ($Item_contratado_valor->SortUrl($Item_contratado_valor->vr_item) == "") { ?>
		<td><div id="elh_Item_contratado_valor_vr_item" class="Item_contratado_valor_vr_item"><div class="ewTableHeaderCaption"><?php echo $Item_contratado_valor->vr_item->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_Item_contratado_valor_vr_item" class="Item_contratado_valor_vr_item">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Item_contratado_valor->vr_item->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($Item_contratado_valor->vr_item->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Item_contratado_valor->vr_item->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($Item_contratado_valor->dt_valor->Visible) { // dt_valor ?>
	<?php if ($Item_contratado_valor->SortUrl($Item_contratado_valor->dt_valor) == "") { ?>
		<td><div id="elh_Item_contratado_valor_dt_valor" class="Item_contratado_valor_dt_valor"><div class="ewTableHeaderCaption"><?php echo $Item_contratado_valor->dt_valor->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_Item_contratado_valor_dt_valor" class="Item_contratado_valor_dt_valor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $Item_contratado_valor->dt_valor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($Item_contratado_valor->dt_valor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Item_contratado_valor->dt_valor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$Item_contratado_valor_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$Item_contratado_valor_grid->StartRec = 1;
$Item_contratado_valor_grid->StopRec = $Item_contratado_valor_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($Item_contratado_valor_grid->FormKeyCountName) && ($Item_contratado_valor->CurrentAction == "gridadd" || $Item_contratado_valor->CurrentAction == "gridedit" || $Item_contratado_valor->CurrentAction == "F")) {
		$Item_contratado_valor_grid->KeyCount = $objForm->GetValue($Item_contratado_valor_grid->FormKeyCountName);
		$Item_contratado_valor_grid->StopRec = $Item_contratado_valor_grid->StartRec + $Item_contratado_valor_grid->KeyCount - 1;
	}
}
$Item_contratado_valor_grid->RecCnt = $Item_contratado_valor_grid->StartRec - 1;
if ($Item_contratado_valor_grid->Recordset && !$Item_contratado_valor_grid->Recordset->EOF) {
	$Item_contratado_valor_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $Item_contratado_valor_grid->StartRec > 1)
		$Item_contratado_valor_grid->Recordset->Move($Item_contratado_valor_grid->StartRec - 1);
} elseif (!$Item_contratado_valor->AllowAddDeleteRow && $Item_contratado_valor_grid->StopRec == 0) {
	$Item_contratado_valor_grid->StopRec = $Item_contratado_valor->GridAddRowCount;
}

// Initialize aggregate
$Item_contratado_valor->RowType = EW_ROWTYPE_AGGREGATEINIT;
$Item_contratado_valor->ResetAttrs();
$Item_contratado_valor_grid->RenderRow();
if ($Item_contratado_valor->CurrentAction == "gridadd")
	$Item_contratado_valor_grid->RowIndex = 0;
if ($Item_contratado_valor->CurrentAction == "gridedit")
	$Item_contratado_valor_grid->RowIndex = 0;
while ($Item_contratado_valor_grid->RecCnt < $Item_contratado_valor_grid->StopRec) {
	$Item_contratado_valor_grid->RecCnt++;
	if (intval($Item_contratado_valor_grid->RecCnt) >= intval($Item_contratado_valor_grid->StartRec)) {
		$Item_contratado_valor_grid->RowCnt++;
		if ($Item_contratado_valor->CurrentAction == "gridadd" || $Item_contratado_valor->CurrentAction == "gridedit" || $Item_contratado_valor->CurrentAction == "F") {
			$Item_contratado_valor_grid->RowIndex++;
			$objForm->Index = $Item_contratado_valor_grid->RowIndex;
			if ($objForm->HasValue($Item_contratado_valor_grid->FormActionName))
				$Item_contratado_valor_grid->RowAction = strval($objForm->GetValue($Item_contratado_valor_grid->FormActionName));
			elseif ($Item_contratado_valor->CurrentAction == "gridadd")
				$Item_contratado_valor_grid->RowAction = "insert";
			else
				$Item_contratado_valor_grid->RowAction = "";
		}

		// Set up key count
		$Item_contratado_valor_grid->KeyCount = $Item_contratado_valor_grid->RowIndex;

		// Init row class and style
		$Item_contratado_valor->ResetAttrs();
		$Item_contratado_valor->CssClass = "";
		if ($Item_contratado_valor->CurrentAction == "gridadd") {
			if ($Item_contratado_valor->CurrentMode == "copy") {
				$Item_contratado_valor_grid->LoadRowValues($Item_contratado_valor_grid->Recordset); // Load row values
				$Item_contratado_valor_grid->SetRecordKey($Item_contratado_valor_grid->RowOldKey, $Item_contratado_valor_grid->Recordset); // Set old record key
			} else {
				$Item_contratado_valor_grid->LoadDefaultValues(); // Load default values
				$Item_contratado_valor_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$Item_contratado_valor_grid->LoadRowValues($Item_contratado_valor_grid->Recordset); // Load row values
		}
		$Item_contratado_valor->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($Item_contratado_valor->CurrentAction == "gridadd") // Grid add
			$Item_contratado_valor->RowType = EW_ROWTYPE_ADD; // Render add
		if ($Item_contratado_valor->CurrentAction == "gridadd" && $Item_contratado_valor->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$Item_contratado_valor_grid->RestoreCurrentRowFormValues($Item_contratado_valor_grid->RowIndex); // Restore form values
		if ($Item_contratado_valor->CurrentAction == "gridedit") { // Grid edit
			if ($Item_contratado_valor->EventCancelled) {
				$Item_contratado_valor_grid->RestoreCurrentRowFormValues($Item_contratado_valor_grid->RowIndex); // Restore form values
			}
			if ($Item_contratado_valor_grid->RowAction == "insert")
				$Item_contratado_valor->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$Item_contratado_valor->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($Item_contratado_valor->CurrentAction == "gridedit" && ($Item_contratado_valor->RowType == EW_ROWTYPE_EDIT || $Item_contratado_valor->RowType == EW_ROWTYPE_ADD) && $Item_contratado_valor->EventCancelled) // Update failed
			$Item_contratado_valor_grid->RestoreCurrentRowFormValues($Item_contratado_valor_grid->RowIndex); // Restore form values
		if ($Item_contratado_valor->RowType == EW_ROWTYPE_EDIT) // Edit row
			$Item_contratado_valor_grid->EditRowCnt++;
		if ($Item_contratado_valor->CurrentAction == "F") // Confirm row
			$Item_contratado_valor_grid->RestoreCurrentRowFormValues($Item_contratado_valor_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$Item_contratado_valor->RowAttrs = array_merge($Item_contratado_valor->RowAttrs, array('data-rowindex'=>$Item_contratado_valor_grid->RowCnt, 'id'=>'r' . $Item_contratado_valor_grid->RowCnt . '_Item_contratado_valor', 'data-rowtype'=>$Item_contratado_valor->RowType));

		// Render row
		$Item_contratado_valor_grid->RenderRow();

		// Render list options
		$Item_contratado_valor_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($Item_contratado_valor_grid->RowAction <> "delete" && $Item_contratado_valor_grid->RowAction <> "insertdelete" && !($Item_contratado_valor_grid->RowAction == "insert" && $Item_contratado_valor->CurrentAction == "F" && $Item_contratado_valor_grid->EmptyRow())) {
?>
	<tr<?php echo $Item_contratado_valor->RowAttributes() ?>>
<?php

// Render list options (body, left)
$Item_contratado_valor_grid->ListOptions->Render("body", "left", $Item_contratado_valor_grid->RowCnt);
?>
	<?php if ($Item_contratado_valor->vr_item->Visible) { // vr_item ?>
		<td<?php echo $Item_contratado_valor->vr_item->CellAttributes() ?>>
<?php if ($Item_contratado_valor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $Item_contratado_valor_grid->RowCnt ?>_Item_contratado_valor_vr_item" class="control-group Item_contratado_valor_vr_item">
<input type="text" data-field="x_vr_item" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" size="30" placeholder="<?php echo $Item_contratado_valor->vr_item->PlaceHolder ?>" value="<?php echo $Item_contratado_valor->vr_item->EditValue ?>"<?php echo $Item_contratado_valor->vr_item->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_item" name="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" id="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" value="<?php echo ew_HtmlEncode($Item_contratado_valor->vr_item->OldValue) ?>">
<?php } ?>
<?php if ($Item_contratado_valor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $Item_contratado_valor_grid->RowCnt ?>_Item_contratado_valor_vr_item" class="control-group Item_contratado_valor_vr_item">
<input type="text" data-field="x_vr_item" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" size="30" placeholder="<?php echo $Item_contratado_valor->vr_item->PlaceHolder ?>" value="<?php echo $Item_contratado_valor->vr_item->EditValue ?>"<?php echo $Item_contratado_valor->vr_item->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($Item_contratado_valor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $Item_contratado_valor->vr_item->ViewAttributes() ?>>
<?php echo $Item_contratado_valor->vr_item->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_item" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" value="<?php echo ew_HtmlEncode($Item_contratado_valor->vr_item->FormValue) ?>">
<input type="hidden" data-field="x_vr_item" name="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" id="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" value="<?php echo ew_HtmlEncode($Item_contratado_valor->vr_item->OldValue) ?>">
<?php } ?>
<a id="<?php echo $Item_contratado_valor_grid->PageObjName . "_row_" . $Item_contratado_valor_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($Item_contratado_valor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_valor" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_nu_valor" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_nu_valor" value="<?php echo ew_HtmlEncode($Item_contratado_valor->nu_valor->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_valor" name="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_nu_valor" id="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_nu_valor" value="<?php echo ew_HtmlEncode($Item_contratado_valor->nu_valor->OldValue) ?>">
<?php } ?>
<?php if ($Item_contratado_valor->RowType == EW_ROWTYPE_EDIT || $Item_contratado_valor->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_valor" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_nu_valor" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_nu_valor" value="<?php echo ew_HtmlEncode($Item_contratado_valor->nu_valor->CurrentValue) ?>">
<?php } ?>
	<?php if ($Item_contratado_valor->dt_valor->Visible) { // dt_valor ?>
		<td<?php echo $Item_contratado_valor->dt_valor->CellAttributes() ?>>
<?php if ($Item_contratado_valor->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $Item_contratado_valor_grid->RowCnt ?>_Item_contratado_valor_dt_valor" class="control-group Item_contratado_valor_dt_valor">
<input type="text" data-field="x_dt_valor" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" placeholder="<?php echo $Item_contratado_valor->dt_valor->PlaceHolder ?>" value="<?php echo $Item_contratado_valor->dt_valor->EditValue ?>"<?php echo $Item_contratado_valor->dt_valor->EditAttributes() ?>>
<?php if (!$Item_contratado_valor->dt_valor->ReadOnly && !$Item_contratado_valor->dt_valor->Disabled && @$Item_contratado_valor->dt_valor->EditAttrs["readonly"] == "" && @$Item_contratado_valor->dt_valor->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" name="cal_x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fItem_contratado_valorgrid", "x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_dt_valor" name="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" id="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" value="<?php echo ew_HtmlEncode($Item_contratado_valor->dt_valor->OldValue) ?>">
<?php } ?>
<?php if ($Item_contratado_valor->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $Item_contratado_valor_grid->RowCnt ?>_Item_contratado_valor_dt_valor" class="control-group Item_contratado_valor_dt_valor">
<input type="text" data-field="x_dt_valor" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" placeholder="<?php echo $Item_contratado_valor->dt_valor->PlaceHolder ?>" value="<?php echo $Item_contratado_valor->dt_valor->EditValue ?>"<?php echo $Item_contratado_valor->dt_valor->EditAttributes() ?>>
<?php if (!$Item_contratado_valor->dt_valor->ReadOnly && !$Item_contratado_valor->dt_valor->Disabled && @$Item_contratado_valor->dt_valor->EditAttrs["readonly"] == "" && @$Item_contratado_valor->dt_valor->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" name="cal_x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fItem_contratado_valorgrid", "x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Item_contratado_valor->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $Item_contratado_valor->dt_valor->ViewAttributes() ?>>
<?php echo $Item_contratado_valor->dt_valor->ListViewValue() ?></span>
<input type="hidden" data-field="x_dt_valor" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" value="<?php echo ew_HtmlEncode($Item_contratado_valor->dt_valor->FormValue) ?>">
<input type="hidden" data-field="x_dt_valor" name="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" id="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" value="<?php echo ew_HtmlEncode($Item_contratado_valor->dt_valor->OldValue) ?>">
<?php } ?>
<a id="<?php echo $Item_contratado_valor_grid->PageObjName . "_row_" . $Item_contratado_valor_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$Item_contratado_valor_grid->ListOptions->Render("body", "right", $Item_contratado_valor_grid->RowCnt);
?>
	</tr>
<?php if ($Item_contratado_valor->RowType == EW_ROWTYPE_ADD || $Item_contratado_valor->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fItem_contratado_valorgrid.UpdateOpts(<?php echo $Item_contratado_valor_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($Item_contratado_valor->CurrentAction <> "gridadd" || $Item_contratado_valor->CurrentMode == "copy")
		if (!$Item_contratado_valor_grid->Recordset->EOF) $Item_contratado_valor_grid->Recordset->MoveNext();
}
?>
<?php
	if ($Item_contratado_valor->CurrentMode == "add" || $Item_contratado_valor->CurrentMode == "copy" || $Item_contratado_valor->CurrentMode == "edit") {
		$Item_contratado_valor_grid->RowIndex = '$rowindex$';
		$Item_contratado_valor_grid->LoadDefaultValues();

		// Set row properties
		$Item_contratado_valor->ResetAttrs();
		$Item_contratado_valor->RowAttrs = array_merge($Item_contratado_valor->RowAttrs, array('data-rowindex'=>$Item_contratado_valor_grid->RowIndex, 'id'=>'r0_Item_contratado_valor', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($Item_contratado_valor->RowAttrs["class"], "ewTemplate");
		$Item_contratado_valor->RowType = EW_ROWTYPE_ADD;

		// Render row
		$Item_contratado_valor_grid->RenderRow();

		// Render list options
		$Item_contratado_valor_grid->RenderListOptions();
		$Item_contratado_valor_grid->StartRowCnt = 0;
?>
	<tr<?php echo $Item_contratado_valor->RowAttributes() ?>>
<?php

// Render list options (body, left)
$Item_contratado_valor_grid->ListOptions->Render("body", "left", $Item_contratado_valor_grid->RowIndex);
?>
	<?php if ($Item_contratado_valor->vr_item->Visible) { // vr_item ?>
		<td>
<?php if ($Item_contratado_valor->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_item" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" size="30" placeholder="<?php echo $Item_contratado_valor->vr_item->PlaceHolder ?>" value="<?php echo $Item_contratado_valor->vr_item->EditValue ?>"<?php echo $Item_contratado_valor->vr_item->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $Item_contratado_valor->vr_item->ViewAttributes() ?>>
<?php echo $Item_contratado_valor->vr_item->ViewValue ?></span>
<input type="hidden" data-field="x_vr_item" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" value="<?php echo ew_HtmlEncode($Item_contratado_valor->vr_item->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_item" name="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" id="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_vr_item" value="<?php echo ew_HtmlEncode($Item_contratado_valor->vr_item->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($Item_contratado_valor->dt_valor->Visible) { // dt_valor ?>
		<td>
<?php if ($Item_contratado_valor->CurrentAction <> "F") { ?>
<input type="text" data-field="x_dt_valor" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" placeholder="<?php echo $Item_contratado_valor->dt_valor->PlaceHolder ?>" value="<?php echo $Item_contratado_valor->dt_valor->EditValue ?>"<?php echo $Item_contratado_valor->dt_valor->EditAttributes() ?>>
<?php if (!$Item_contratado_valor->dt_valor->ReadOnly && !$Item_contratado_valor->dt_valor->Disabled && @$Item_contratado_valor->dt_valor->EditAttrs["readonly"] == "" && @$Item_contratado_valor->dt_valor->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" name="cal_x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fItem_contratado_valorgrid", "x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor", "%d/%m/%Y");
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $Item_contratado_valor->dt_valor->ViewAttributes() ?>>
<?php echo $Item_contratado_valor->dt_valor->ViewValue ?></span>
<input type="hidden" data-field="x_dt_valor" name="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" id="x<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" value="<?php echo ew_HtmlEncode($Item_contratado_valor->dt_valor->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dt_valor" name="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" id="o<?php echo $Item_contratado_valor_grid->RowIndex ?>_dt_valor" value="<?php echo ew_HtmlEncode($Item_contratado_valor->dt_valor->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$Item_contratado_valor_grid->ListOptions->Render("body", "right", $Item_contratado_valor_grid->RowCnt);
?>
<script type="text/javascript">
fItem_contratado_valorgrid.UpdateOpts(<?php echo $Item_contratado_valor_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($Item_contratado_valor->CurrentMode == "add" || $Item_contratado_valor->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $Item_contratado_valor_grid->FormKeyCountName ?>" id="<?php echo $Item_contratado_valor_grid->FormKeyCountName ?>" value="<?php echo $Item_contratado_valor_grid->KeyCount ?>">
<?php echo $Item_contratado_valor_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Item_contratado_valor->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $Item_contratado_valor_grid->FormKeyCountName ?>" id="<?php echo $Item_contratado_valor_grid->FormKeyCountName ?>" value="<?php echo $Item_contratado_valor_grid->KeyCount ?>">
<?php echo $Item_contratado_valor_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Item_contratado_valor->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fItem_contratado_valorgrid">
</div>
<?php

// Close recordset
if ($Item_contratado_valor_grid->Recordset)
	$Item_contratado_valor_grid->Recordset->Close();
?>
<?php if ($Item_contratado_valor_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($Item_contratado_valor_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($Item_contratado_valor->Export == "") { ?>
<script type="text/javascript">
fItem_contratado_valorgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$Item_contratado_valor_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$Item_contratado_valor_grid->Page_Terminate();
?>
