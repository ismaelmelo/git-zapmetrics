<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($necstake_grid)) $necstake_grid = new cnecstake_grid();

// Page init
$necstake_grid->Page_Init();

// Page main
$necstake_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$necstake_grid->Page_Render();
?>
<?php if ($necstake->Export == "") { ?>
<script type="text/javascript">

// Page object
var necstake_grid = new ew_Page("necstake_grid");
necstake_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = necstake_grid.PageID; // For backward compatibility

// Form object
var fnecstakegrid = new ew_Form("fnecstakegrid");
fnecstakegrid.FormKeyCountName = '<?php echo $necstake_grid->FormKeyCountName ?>';

// Validate form
fnecstakegrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_periodoPei");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necstake->nu_periodoPei->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_motivador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necstake->nu_motivador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necstake->ic_situacao->FldCaption()) ?>");

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
fnecstakegrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_periodoPei", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_motivador", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_necessidade", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_situacao", false)) return false;
	return true;
}

// Form_CustomValidate event
fnecstakegrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnecstakegrid.ValidateRequired = true;
<?php } else { ?>
fnecstakegrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnecstakegrid.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_anoInicio","x_nu_anoFim","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnecstakegrid.Lists["x_nu_motivador"] = {"LinkField":"x_nu_motivador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_motivador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($necstake->getCurrentMasterTable() == "" && $necstake_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $necstake_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($necstake->CurrentAction == "gridadd") {
	if ($necstake->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$necstake_grid->TotalRecs = $necstake->SelectRecordCount();
			$necstake_grid->Recordset = $necstake_grid->LoadRecordset($necstake_grid->StartRec-1, $necstake_grid->DisplayRecs);
		} else {
			if ($necstake_grid->Recordset = $necstake_grid->LoadRecordset())
				$necstake_grid->TotalRecs = $necstake_grid->Recordset->RecordCount();
		}
		$necstake_grid->StartRec = 1;
		$necstake_grid->DisplayRecs = $necstake_grid->TotalRecs;
	} else {
		$necstake->CurrentFilter = "0=1";
		$necstake_grid->StartRec = 1;
		$necstake_grid->DisplayRecs = $necstake->GridAddRowCount;
	}
	$necstake_grid->TotalRecs = $necstake_grid->DisplayRecs;
	$necstake_grid->StopRec = $necstake_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$necstake_grid->TotalRecs = $necstake->SelectRecordCount();
	} else {
		if ($necstake_grid->Recordset = $necstake_grid->LoadRecordset())
			$necstake_grid->TotalRecs = $necstake_grid->Recordset->RecordCount();
	}
	$necstake_grid->StartRec = 1;
	$necstake_grid->DisplayRecs = $necstake_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$necstake_grid->Recordset = $necstake_grid->LoadRecordset($necstake_grid->StartRec-1, $necstake_grid->DisplayRecs);
}
$necstake_grid->RenderOtherOptions();
?>
<?php $necstake_grid->ShowPageHeader(); ?>
<?php
$necstake_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fnecstakegrid" class="ewForm form-horizontal">
<div id="gmp_necstake" class="ewGridMiddlePanel">
<table id="tbl_necstakegrid" class="ewTable ewTableSeparate">
<?php echo $necstake->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$necstake_grid->RenderListOptions();

// Render list options (header, left)
$necstake_grid->ListOptions->Render("header", "left");
?>
<?php if ($necstake->nu_periodoPei->Visible) { // nu_periodoPei ?>
	<?php if ($necstake->SortUrl($necstake->nu_periodoPei) == "") { ?>
		<td><div id="elh_necstake_nu_periodoPei" class="necstake_nu_periodoPei"><div class="ewTableHeaderCaption"><?php echo $necstake->nu_periodoPei->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_necstake_nu_periodoPei" class="necstake_nu_periodoPei">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necstake->nu_periodoPei->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necstake->nu_periodoPei->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necstake->nu_periodoPei->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necstake->nu_motivador->Visible) { // nu_motivador ?>
	<?php if ($necstake->SortUrl($necstake->nu_motivador) == "") { ?>
		<td><div id="elh_necstake_nu_motivador" class="necstake_nu_motivador"><div class="ewTableHeaderCaption"><?php echo $necstake->nu_motivador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_necstake_nu_motivador" class="necstake_nu_motivador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necstake->nu_motivador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necstake->nu_motivador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necstake->nu_motivador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necstake->no_necessidade->Visible) { // no_necessidade ?>
	<?php if ($necstake->SortUrl($necstake->no_necessidade) == "") { ?>
		<td><div id="elh_necstake_no_necessidade" class="necstake_no_necessidade"><div class="ewTableHeaderCaption"><?php echo $necstake->no_necessidade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_necstake_no_necessidade" class="necstake_no_necessidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necstake->no_necessidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necstake->no_necessidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necstake->no_necessidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necstake->ic_situacao->Visible) { // ic_situacao ?>
	<?php if ($necstake->SortUrl($necstake->ic_situacao) == "") { ?>
		<td><div id="elh_necstake_ic_situacao" class="necstake_ic_situacao"><div class="ewTableHeaderCaption"><?php echo $necstake->ic_situacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_necstake_ic_situacao" class="necstake_ic_situacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necstake->ic_situacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necstake->ic_situacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necstake->ic_situacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$necstake_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$necstake_grid->StartRec = 1;
$necstake_grid->StopRec = $necstake_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($necstake_grid->FormKeyCountName) && ($necstake->CurrentAction == "gridadd" || $necstake->CurrentAction == "gridedit" || $necstake->CurrentAction == "F")) {
		$necstake_grid->KeyCount = $objForm->GetValue($necstake_grid->FormKeyCountName);
		$necstake_grid->StopRec = $necstake_grid->StartRec + $necstake_grid->KeyCount - 1;
	}
}
$necstake_grid->RecCnt = $necstake_grid->StartRec - 1;
if ($necstake_grid->Recordset && !$necstake_grid->Recordset->EOF) {
	$necstake_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $necstake_grid->StartRec > 1)
		$necstake_grid->Recordset->Move($necstake_grid->StartRec - 1);
} elseif (!$necstake->AllowAddDeleteRow && $necstake_grid->StopRec == 0) {
	$necstake_grid->StopRec = $necstake->GridAddRowCount;
}

// Initialize aggregate
$necstake->RowType = EW_ROWTYPE_AGGREGATEINIT;
$necstake->ResetAttrs();
$necstake_grid->RenderRow();
if ($necstake->CurrentAction == "gridadd")
	$necstake_grid->RowIndex = 0;
if ($necstake->CurrentAction == "gridedit")
	$necstake_grid->RowIndex = 0;
while ($necstake_grid->RecCnt < $necstake_grid->StopRec) {
	$necstake_grid->RecCnt++;
	if (intval($necstake_grid->RecCnt) >= intval($necstake_grid->StartRec)) {
		$necstake_grid->RowCnt++;
		if ($necstake->CurrentAction == "gridadd" || $necstake->CurrentAction == "gridedit" || $necstake->CurrentAction == "F") {
			$necstake_grid->RowIndex++;
			$objForm->Index = $necstake_grid->RowIndex;
			if ($objForm->HasValue($necstake_grid->FormActionName))
				$necstake_grid->RowAction = strval($objForm->GetValue($necstake_grid->FormActionName));
			elseif ($necstake->CurrentAction == "gridadd")
				$necstake_grid->RowAction = "insert";
			else
				$necstake_grid->RowAction = "";
		}

		// Set up key count
		$necstake_grid->KeyCount = $necstake_grid->RowIndex;

		// Init row class and style
		$necstake->ResetAttrs();
		$necstake->CssClass = "";
		if ($necstake->CurrentAction == "gridadd") {
			if ($necstake->CurrentMode == "copy") {
				$necstake_grid->LoadRowValues($necstake_grid->Recordset); // Load row values
				$necstake_grid->SetRecordKey($necstake_grid->RowOldKey, $necstake_grid->Recordset); // Set old record key
			} else {
				$necstake_grid->LoadDefaultValues(); // Load default values
				$necstake_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$necstake_grid->LoadRowValues($necstake_grid->Recordset); // Load row values
		}
		$necstake->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($necstake->CurrentAction == "gridadd") // Grid add
			$necstake->RowType = EW_ROWTYPE_ADD; // Render add
		if ($necstake->CurrentAction == "gridadd" && $necstake->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$necstake_grid->RestoreCurrentRowFormValues($necstake_grid->RowIndex); // Restore form values
		if ($necstake->CurrentAction == "gridedit") { // Grid edit
			if ($necstake->EventCancelled) {
				$necstake_grid->RestoreCurrentRowFormValues($necstake_grid->RowIndex); // Restore form values
			}
			if ($necstake_grid->RowAction == "insert")
				$necstake->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$necstake->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($necstake->CurrentAction == "gridedit" && ($necstake->RowType == EW_ROWTYPE_EDIT || $necstake->RowType == EW_ROWTYPE_ADD) && $necstake->EventCancelled) // Update failed
			$necstake_grid->RestoreCurrentRowFormValues($necstake_grid->RowIndex); // Restore form values
		if ($necstake->RowType == EW_ROWTYPE_EDIT) // Edit row
			$necstake_grid->EditRowCnt++;
		if ($necstake->CurrentAction == "F") // Confirm row
			$necstake_grid->RestoreCurrentRowFormValues($necstake_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$necstake->RowAttrs = array_merge($necstake->RowAttrs, array('data-rowindex'=>$necstake_grid->RowCnt, 'id'=>'r' . $necstake_grid->RowCnt . '_necstake', 'data-rowtype'=>$necstake->RowType));

		// Render row
		$necstake_grid->RenderRow();

		// Render list options
		$necstake_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($necstake_grid->RowAction <> "delete" && $necstake_grid->RowAction <> "insertdelete" && !($necstake_grid->RowAction == "insert" && $necstake->CurrentAction == "F" && $necstake_grid->EmptyRow())) {
?>
	<tr<?php echo $necstake->RowAttributes() ?>>
<?php

// Render list options (body, left)
$necstake_grid->ListOptions->Render("body", "left", $necstake_grid->RowCnt);
?>
	<?php if ($necstake->nu_periodoPei->Visible) { // nu_periodoPei ?>
		<td<?php echo $necstake->nu_periodoPei->CellAttributes() ?>>
<?php if ($necstake->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $necstake_grid->RowCnt ?>_necstake_nu_periodoPei" class="control-group necstake_nu_periodoPei">
<select data-field="x_nu_periodoPei" id="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" name="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei"<?php echo $necstake->nu_periodoPei->EditAttributes() ?>>
<?php
if (is_array($necstake->nu_periodoPei->EditValue)) {
	$arwrk = $necstake->nu_periodoPei->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->nu_periodoPei->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$necstake->nu_periodoPei) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $necstake->nu_periodoPei->OldValue = "";
?>
</select>
<script type="text/javascript">
fnecstakegrid.Lists["x_nu_periodoPei"].Options = <?php echo (is_array($necstake->nu_periodoPei->EditValue)) ? ew_ArrayToJson($necstake->nu_periodoPei->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_periodoPei" name="o<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" id="o<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" value="<?php echo ew_HtmlEncode($necstake->nu_periodoPei->OldValue) ?>">
<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $necstake_grid->RowCnt ?>_necstake_nu_periodoPei" class="control-group necstake_nu_periodoPei">
<select data-field="x_nu_periodoPei" id="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" name="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei"<?php echo $necstake->nu_periodoPei->EditAttributes() ?>>
<?php
if (is_array($necstake->nu_periodoPei->EditValue)) {
	$arwrk = $necstake->nu_periodoPei->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->nu_periodoPei->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$necstake->nu_periodoPei) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $necstake->nu_periodoPei->OldValue = "";
?>
</select>
<script type="text/javascript">
fnecstakegrid.Lists["x_nu_periodoPei"].Options = <?php echo (is_array($necstake->nu_periodoPei->EditValue)) ? ew_ArrayToJson($necstake->nu_periodoPei->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $necstake->nu_periodoPei->ViewAttributes() ?>>
<?php echo $necstake->nu_periodoPei->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_periodoPei" name="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" id="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" value="<?php echo ew_HtmlEncode($necstake->nu_periodoPei->FormValue) ?>">
<input type="hidden" data-field="x_nu_periodoPei" name="o<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" id="o<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" value="<?php echo ew_HtmlEncode($necstake->nu_periodoPei->OldValue) ?>">
<?php } ?>
<a id="<?php echo $necstake_grid->PageObjName . "_row_" . $necstake_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_necessidade" name="x<?php echo $necstake_grid->RowIndex ?>_nu_necessidade" id="x<?php echo $necstake_grid->RowIndex ?>_nu_necessidade" value="<?php echo ew_HtmlEncode($necstake->nu_necessidade->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_necessidade" name="o<?php echo $necstake_grid->RowIndex ?>_nu_necessidade" id="o<?php echo $necstake_grid->RowIndex ?>_nu_necessidade" value="<?php echo ew_HtmlEncode($necstake->nu_necessidade->OldValue) ?>">
<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_EDIT || $necstake->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_necessidade" name="x<?php echo $necstake_grid->RowIndex ?>_nu_necessidade" id="x<?php echo $necstake_grid->RowIndex ?>_nu_necessidade" value="<?php echo ew_HtmlEncode($necstake->nu_necessidade->CurrentValue) ?>">
<?php } ?>
	<?php if ($necstake->nu_motivador->Visible) { // nu_motivador ?>
		<td<?php echo $necstake->nu_motivador->CellAttributes() ?>>
<?php if ($necstake->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $necstake_grid->RowCnt ?>_necstake_nu_motivador" class="control-group necstake_nu_motivador">
<select data-field="x_nu_motivador" id="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador" name="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador"<?php echo $necstake->nu_motivador->EditAttributes() ?>>
<?php
if (is_array($necstake->nu_motivador->EditValue)) {
	$arwrk = $necstake->nu_motivador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->nu_motivador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $necstake->nu_motivador->OldValue = "";
?>
</select>
<script type="text/javascript">
fnecstakegrid.Lists["x_nu_motivador"].Options = <?php echo (is_array($necstake->nu_motivador->EditValue)) ? ew_ArrayToJson($necstake->nu_motivador->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_motivador" name="o<?php echo $necstake_grid->RowIndex ?>_nu_motivador" id="o<?php echo $necstake_grid->RowIndex ?>_nu_motivador" value="<?php echo ew_HtmlEncode($necstake->nu_motivador->OldValue) ?>">
<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $necstake_grid->RowCnt ?>_necstake_nu_motivador" class="control-group necstake_nu_motivador">
<select data-field="x_nu_motivador" id="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador" name="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador"<?php echo $necstake->nu_motivador->EditAttributes() ?>>
<?php
if (is_array($necstake->nu_motivador->EditValue)) {
	$arwrk = $necstake->nu_motivador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->nu_motivador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $necstake->nu_motivador->OldValue = "";
?>
</select>
<script type="text/javascript">
fnecstakegrid.Lists["x_nu_motivador"].Options = <?php echo (is_array($necstake->nu_motivador->EditValue)) ? ew_ArrayToJson($necstake->nu_motivador->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $necstake->nu_motivador->ViewAttributes() ?>>
<?php echo $necstake->nu_motivador->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_motivador" name="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador" id="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador" value="<?php echo ew_HtmlEncode($necstake->nu_motivador->FormValue) ?>">
<input type="hidden" data-field="x_nu_motivador" name="o<?php echo $necstake_grid->RowIndex ?>_nu_motivador" id="o<?php echo $necstake_grid->RowIndex ?>_nu_motivador" value="<?php echo ew_HtmlEncode($necstake->nu_motivador->OldValue) ?>">
<?php } ?>
<a id="<?php echo $necstake_grid->PageObjName . "_row_" . $necstake_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necstake->no_necessidade->Visible) { // no_necessidade ?>
		<td<?php echo $necstake->no_necessidade->CellAttributes() ?>>
<?php if ($necstake->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $necstake_grid->RowCnt ?>_necstake_no_necessidade" class="control-group necstake_no_necessidade">
<input type="text" data-field="x_no_necessidade" name="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" id="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" size="30" maxlength="255" placeholder="<?php echo $necstake->no_necessidade->PlaceHolder ?>" value="<?php echo $necstake->no_necessidade->EditValue ?>"<?php echo $necstake->no_necessidade->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_necessidade" name="o<?php echo $necstake_grid->RowIndex ?>_no_necessidade" id="o<?php echo $necstake_grid->RowIndex ?>_no_necessidade" value="<?php echo ew_HtmlEncode($necstake->no_necessidade->OldValue) ?>">
<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $necstake_grid->RowCnt ?>_necstake_no_necessidade" class="control-group necstake_no_necessidade">
<input type="text" data-field="x_no_necessidade" name="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" id="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" size="30" maxlength="255" placeholder="<?php echo $necstake->no_necessidade->PlaceHolder ?>" value="<?php echo $necstake->no_necessidade->EditValue ?>"<?php echo $necstake->no_necessidade->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $necstake->no_necessidade->ViewAttributes() ?>>
<?php echo $necstake->no_necessidade->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_necessidade" name="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" id="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" value="<?php echo ew_HtmlEncode($necstake->no_necessidade->FormValue) ?>">
<input type="hidden" data-field="x_no_necessidade" name="o<?php echo $necstake_grid->RowIndex ?>_no_necessidade" id="o<?php echo $necstake_grid->RowIndex ?>_no_necessidade" value="<?php echo ew_HtmlEncode($necstake->no_necessidade->OldValue) ?>">
<?php } ?>
<a id="<?php echo $necstake_grid->PageObjName . "_row_" . $necstake_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necstake->ic_situacao->Visible) { // ic_situacao ?>
		<td<?php echo $necstake->ic_situacao->CellAttributes() ?>>
<?php if ($necstake->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $necstake_grid->RowCnt ?>_necstake_ic_situacao" class="control-group necstake_ic_situacao">
<select data-field="x_ic_situacao" id="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao" name="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao"<?php echo $necstake->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($necstake->ic_situacao->EditValue)) {
	$arwrk = $necstake->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $necstake->ic_situacao->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_ic_situacao" name="o<?php echo $necstake_grid->RowIndex ?>_ic_situacao" id="o<?php echo $necstake_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($necstake->ic_situacao->OldValue) ?>">
<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $necstake_grid->RowCnt ?>_necstake_ic_situacao" class="control-group necstake_ic_situacao">
<select data-field="x_ic_situacao" id="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao" name="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao"<?php echo $necstake->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($necstake->ic_situacao->EditValue)) {
	$arwrk = $necstake->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $necstake->ic_situacao->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($necstake->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $necstake->ic_situacao->ViewAttributes() ?>>
<?php echo $necstake->ic_situacao->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_situacao" name="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao" id="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($necstake->ic_situacao->FormValue) ?>">
<input type="hidden" data-field="x_ic_situacao" name="o<?php echo $necstake_grid->RowIndex ?>_ic_situacao" id="o<?php echo $necstake_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($necstake->ic_situacao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $necstake_grid->PageObjName . "_row_" . $necstake_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$necstake_grid->ListOptions->Render("body", "right", $necstake_grid->RowCnt);
?>
	</tr>
<?php if ($necstake->RowType == EW_ROWTYPE_ADD || $necstake->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fnecstakegrid.UpdateOpts(<?php echo $necstake_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($necstake->CurrentAction <> "gridadd" || $necstake->CurrentMode == "copy")
		if (!$necstake_grid->Recordset->EOF) $necstake_grid->Recordset->MoveNext();
}
?>
<?php
	if ($necstake->CurrentMode == "add" || $necstake->CurrentMode == "copy" || $necstake->CurrentMode == "edit") {
		$necstake_grid->RowIndex = '$rowindex$';
		$necstake_grid->LoadDefaultValues();

		// Set row properties
		$necstake->ResetAttrs();
		$necstake->RowAttrs = array_merge($necstake->RowAttrs, array('data-rowindex'=>$necstake_grid->RowIndex, 'id'=>'r0_necstake', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($necstake->RowAttrs["class"], "ewTemplate");
		$necstake->RowType = EW_ROWTYPE_ADD;

		// Render row
		$necstake_grid->RenderRow();

		// Render list options
		$necstake_grid->RenderListOptions();
		$necstake_grid->StartRowCnt = 0;
?>
	<tr<?php echo $necstake->RowAttributes() ?>>
<?php

// Render list options (body, left)
$necstake_grid->ListOptions->Render("body", "left", $necstake_grid->RowIndex);
?>
	<?php if ($necstake->nu_periodoPei->Visible) { // nu_periodoPei ?>
		<td>
<?php if ($necstake->CurrentAction <> "F") { ?>
<select data-field="x_nu_periodoPei" id="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" name="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei"<?php echo $necstake->nu_periodoPei->EditAttributes() ?>>
<?php
if (is_array($necstake->nu_periodoPei->EditValue)) {
	$arwrk = $necstake->nu_periodoPei->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->nu_periodoPei->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$necstake->nu_periodoPei) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $necstake->nu_periodoPei->OldValue = "";
?>
</select>
<script type="text/javascript">
fnecstakegrid.Lists["x_nu_periodoPei"].Options = <?php echo (is_array($necstake->nu_periodoPei->EditValue)) ? ew_ArrayToJson($necstake->nu_periodoPei->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $necstake->nu_periodoPei->ViewAttributes() ?>>
<?php echo $necstake->nu_periodoPei->ViewValue ?></span>
<input type="hidden" data-field="x_nu_periodoPei" name="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" id="x<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" value="<?php echo ew_HtmlEncode($necstake->nu_periodoPei->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_periodoPei" name="o<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" id="o<?php echo $necstake_grid->RowIndex ?>_nu_periodoPei" value="<?php echo ew_HtmlEncode($necstake->nu_periodoPei->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($necstake->nu_motivador->Visible) { // nu_motivador ?>
		<td>
<?php if ($necstake->CurrentAction <> "F") { ?>
<select data-field="x_nu_motivador" id="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador" name="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador"<?php echo $necstake->nu_motivador->EditAttributes() ?>>
<?php
if (is_array($necstake->nu_motivador->EditValue)) {
	$arwrk = $necstake->nu_motivador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->nu_motivador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $necstake->nu_motivador->OldValue = "";
?>
</select>
<script type="text/javascript">
fnecstakegrid.Lists["x_nu_motivador"].Options = <?php echo (is_array($necstake->nu_motivador->EditValue)) ? ew_ArrayToJson($necstake->nu_motivador->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $necstake->nu_motivador->ViewAttributes() ?>>
<?php echo $necstake->nu_motivador->ViewValue ?></span>
<input type="hidden" data-field="x_nu_motivador" name="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador" id="x<?php echo $necstake_grid->RowIndex ?>_nu_motivador" value="<?php echo ew_HtmlEncode($necstake->nu_motivador->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_motivador" name="o<?php echo $necstake_grid->RowIndex ?>_nu_motivador" id="o<?php echo $necstake_grid->RowIndex ?>_nu_motivador" value="<?php echo ew_HtmlEncode($necstake->nu_motivador->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($necstake->no_necessidade->Visible) { // no_necessidade ?>
		<td>
<?php if ($necstake->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_necessidade" name="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" id="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" size="30" maxlength="255" placeholder="<?php echo $necstake->no_necessidade->PlaceHolder ?>" value="<?php echo $necstake->no_necessidade->EditValue ?>"<?php echo $necstake->no_necessidade->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $necstake->no_necessidade->ViewAttributes() ?>>
<?php echo $necstake->no_necessidade->ViewValue ?></span>
<input type="hidden" data-field="x_no_necessidade" name="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" id="x<?php echo $necstake_grid->RowIndex ?>_no_necessidade" value="<?php echo ew_HtmlEncode($necstake->no_necessidade->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_necessidade" name="o<?php echo $necstake_grid->RowIndex ?>_no_necessidade" id="o<?php echo $necstake_grid->RowIndex ?>_no_necessidade" value="<?php echo ew_HtmlEncode($necstake->no_necessidade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($necstake->ic_situacao->Visible) { // ic_situacao ?>
		<td>
<?php if ($necstake->CurrentAction <> "F") { ?>
<select data-field="x_ic_situacao" id="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao" name="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao"<?php echo $necstake->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($necstake->ic_situacao->EditValue)) {
	$arwrk = $necstake->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necstake->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $necstake->ic_situacao->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $necstake->ic_situacao->ViewAttributes() ?>>
<?php echo $necstake->ic_situacao->ViewValue ?></span>
<input type="hidden" data-field="x_ic_situacao" name="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao" id="x<?php echo $necstake_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($necstake->ic_situacao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_situacao" name="o<?php echo $necstake_grid->RowIndex ?>_ic_situacao" id="o<?php echo $necstake_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($necstake->ic_situacao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$necstake_grid->ListOptions->Render("body", "right", $necstake_grid->RowCnt);
?>
<script type="text/javascript">
fnecstakegrid.UpdateOpts(<?php echo $necstake_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($necstake->CurrentMode == "add" || $necstake->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $necstake_grid->FormKeyCountName ?>" id="<?php echo $necstake_grid->FormKeyCountName ?>" value="<?php echo $necstake_grid->KeyCount ?>">
<?php echo $necstake_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($necstake->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $necstake_grid->FormKeyCountName ?>" id="<?php echo $necstake_grid->FormKeyCountName ?>" value="<?php echo $necstake_grid->KeyCount ?>">
<?php echo $necstake_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($necstake->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fnecstakegrid">
</div>
<?php

// Close recordset
if ($necstake_grid->Recordset)
	$necstake_grid->Recordset->Close();
?>
<?php if ($necstake_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($necstake_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($necstake->Export == "") { ?>
<script type="text/javascript">
fnecstakegrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$necstake_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$necstake_grid->Page_Terminate();
?>
