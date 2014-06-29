<?php include_once "regranegocioinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($uc_regranegocio_grid)) $uc_regranegocio_grid = new cuc_regranegocio_grid();

// Page init
$uc_regranegocio_grid->Page_Init();

// Page main
$uc_regranegocio_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$uc_regranegocio_grid->Page_Render();
?>
<?php if ($uc_regranegocio->Export == "") { ?>
<script type="text/javascript">

// Page object
var uc_regranegocio_grid = new ew_Page("uc_regranegocio_grid");
uc_regranegocio_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = uc_regranegocio_grid.PageID; // For backward compatibility

// Form object
var fuc_regranegociogrid = new ew_Form("fuc_regranegociogrid");
fuc_regranegociogrid.FormKeyCountName = '<?php echo $uc_regranegocio_grid->FormKeyCountName ?>';

// Validate form
fuc_regranegociogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_co_rn");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc_regranegocio->co_rn->FldCaption()) ?>");

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
fuc_regranegociogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "co_rn", false)) return false;
	return true;
}

// Form_CustomValidate event
fuc_regranegociogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuc_regranegociogrid.ValidateRequired = true;
<?php } else { ?>
fuc_regranegociogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fuc_regranegociogrid.Lists["x_co_rn"] = {"LinkField":"x_co_alternativo","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_regraNegocio","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($uc_regranegocio->getCurrentMasterTable() == "" && $uc_regranegocio_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $uc_regranegocio_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($uc_regranegocio->CurrentAction == "gridadd") {
	if ($uc_regranegocio->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$uc_regranegocio_grid->TotalRecs = $uc_regranegocio->SelectRecordCount();
			$uc_regranegocio_grid->Recordset = $uc_regranegocio_grid->LoadRecordset($uc_regranegocio_grid->StartRec-1, $uc_regranegocio_grid->DisplayRecs);
		} else {
			if ($uc_regranegocio_grid->Recordset = $uc_regranegocio_grid->LoadRecordset())
				$uc_regranegocio_grid->TotalRecs = $uc_regranegocio_grid->Recordset->RecordCount();
		}
		$uc_regranegocio_grid->StartRec = 1;
		$uc_regranegocio_grid->DisplayRecs = $uc_regranegocio_grid->TotalRecs;
	} else {
		$uc_regranegocio->CurrentFilter = "0=1";
		$uc_regranegocio_grid->StartRec = 1;
		$uc_regranegocio_grid->DisplayRecs = $uc_regranegocio->GridAddRowCount;
	}
	$uc_regranegocio_grid->TotalRecs = $uc_regranegocio_grid->DisplayRecs;
	$uc_regranegocio_grid->StopRec = $uc_regranegocio_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$uc_regranegocio_grid->TotalRecs = $uc_regranegocio->SelectRecordCount();
	} else {
		if ($uc_regranegocio_grid->Recordset = $uc_regranegocio_grid->LoadRecordset())
			$uc_regranegocio_grid->TotalRecs = $uc_regranegocio_grid->Recordset->RecordCount();
	}
	$uc_regranegocio_grid->StartRec = 1;
	$uc_regranegocio_grid->DisplayRecs = $uc_regranegocio_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$uc_regranegocio_grid->Recordset = $uc_regranegocio_grid->LoadRecordset($uc_regranegocio_grid->StartRec-1, $uc_regranegocio_grid->DisplayRecs);
}
$uc_regranegocio_grid->RenderOtherOptions();
?>
<?php $uc_regranegocio_grid->ShowPageHeader(); ?>
<?php
$uc_regranegocio_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fuc_regranegociogrid" class="ewForm form-horizontal">
<div id="gmp_uc_regranegocio" class="ewGridMiddlePanel">
<table id="tbl_uc_regranegociogrid" class="ewTable ewTableSeparate">
<?php echo $uc_regranegocio->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$uc_regranegocio_grid->RenderListOptions();

// Render list options (header, left)
$uc_regranegocio_grid->ListOptions->Render("header", "left");
?>
<?php if ($uc_regranegocio->co_rn->Visible) { // co_rn ?>
	<?php if ($uc_regranegocio->SortUrl($uc_regranegocio->co_rn) == "") { ?>
		<td><div id="elh_uc_regranegocio_co_rn" class="uc_regranegocio_co_rn"><div class="ewTableHeaderCaption"><?php echo $uc_regranegocio->co_rn->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_uc_regranegocio_co_rn" class="uc_regranegocio_co_rn">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $uc_regranegocio->co_rn->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($uc_regranegocio->co_rn->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($uc_regranegocio->co_rn->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$uc_regranegocio_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$uc_regranegocio_grid->StartRec = 1;
$uc_regranegocio_grid->StopRec = $uc_regranegocio_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($uc_regranegocio_grid->FormKeyCountName) && ($uc_regranegocio->CurrentAction == "gridadd" || $uc_regranegocio->CurrentAction == "gridedit" || $uc_regranegocio->CurrentAction == "F")) {
		$uc_regranegocio_grid->KeyCount = $objForm->GetValue($uc_regranegocio_grid->FormKeyCountName);
		$uc_regranegocio_grid->StopRec = $uc_regranegocio_grid->StartRec + $uc_regranegocio_grid->KeyCount - 1;
	}
}
$uc_regranegocio_grid->RecCnt = $uc_regranegocio_grid->StartRec - 1;
if ($uc_regranegocio_grid->Recordset && !$uc_regranegocio_grid->Recordset->EOF) {
	$uc_regranegocio_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $uc_regranegocio_grid->StartRec > 1)
		$uc_regranegocio_grid->Recordset->Move($uc_regranegocio_grid->StartRec - 1);
} elseif (!$uc_regranegocio->AllowAddDeleteRow && $uc_regranegocio_grid->StopRec == 0) {
	$uc_regranegocio_grid->StopRec = $uc_regranegocio->GridAddRowCount;
}

// Initialize aggregate
$uc_regranegocio->RowType = EW_ROWTYPE_AGGREGATEINIT;
$uc_regranegocio->ResetAttrs();
$uc_regranegocio_grid->RenderRow();
if ($uc_regranegocio->CurrentAction == "gridadd")
	$uc_regranegocio_grid->RowIndex = 0;
if ($uc_regranegocio->CurrentAction == "gridedit")
	$uc_regranegocio_grid->RowIndex = 0;
while ($uc_regranegocio_grid->RecCnt < $uc_regranegocio_grid->StopRec) {
	$uc_regranegocio_grid->RecCnt++;
	if (intval($uc_regranegocio_grid->RecCnt) >= intval($uc_regranegocio_grid->StartRec)) {
		$uc_regranegocio_grid->RowCnt++;
		if ($uc_regranegocio->CurrentAction == "gridadd" || $uc_regranegocio->CurrentAction == "gridedit" || $uc_regranegocio->CurrentAction == "F") {
			$uc_regranegocio_grid->RowIndex++;
			$objForm->Index = $uc_regranegocio_grid->RowIndex;
			if ($objForm->HasValue($uc_regranegocio_grid->FormActionName))
				$uc_regranegocio_grid->RowAction = strval($objForm->GetValue($uc_regranegocio_grid->FormActionName));
			elseif ($uc_regranegocio->CurrentAction == "gridadd")
				$uc_regranegocio_grid->RowAction = "insert";
			else
				$uc_regranegocio_grid->RowAction = "";
		}

		// Set up key count
		$uc_regranegocio_grid->KeyCount = $uc_regranegocio_grid->RowIndex;

		// Init row class and style
		$uc_regranegocio->ResetAttrs();
		$uc_regranegocio->CssClass = "";
		if ($uc_regranegocio->CurrentAction == "gridadd") {
			if ($uc_regranegocio->CurrentMode == "copy") {
				$uc_regranegocio_grid->LoadRowValues($uc_regranegocio_grid->Recordset); // Load row values
				$uc_regranegocio_grid->SetRecordKey($uc_regranegocio_grid->RowOldKey, $uc_regranegocio_grid->Recordset); // Set old record key
			} else {
				$uc_regranegocio_grid->LoadDefaultValues(); // Load default values
				$uc_regranegocio_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$uc_regranegocio_grid->LoadRowValues($uc_regranegocio_grid->Recordset); // Load row values
		}
		$uc_regranegocio->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($uc_regranegocio->CurrentAction == "gridadd") // Grid add
			$uc_regranegocio->RowType = EW_ROWTYPE_ADD; // Render add
		if ($uc_regranegocio->CurrentAction == "gridadd" && $uc_regranegocio->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$uc_regranegocio_grid->RestoreCurrentRowFormValues($uc_regranegocio_grid->RowIndex); // Restore form values
		if ($uc_regranegocio->CurrentAction == "gridedit") { // Grid edit
			if ($uc_regranegocio->EventCancelled) {
				$uc_regranegocio_grid->RestoreCurrentRowFormValues($uc_regranegocio_grid->RowIndex); // Restore form values
			}
			if ($uc_regranegocio_grid->RowAction == "insert")
				$uc_regranegocio->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$uc_regranegocio->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($uc_regranegocio->CurrentAction == "gridedit" && ($uc_regranegocio->RowType == EW_ROWTYPE_EDIT || $uc_regranegocio->RowType == EW_ROWTYPE_ADD) && $uc_regranegocio->EventCancelled) // Update failed
			$uc_regranegocio_grid->RestoreCurrentRowFormValues($uc_regranegocio_grid->RowIndex); // Restore form values
		if ($uc_regranegocio->RowType == EW_ROWTYPE_EDIT) // Edit row
			$uc_regranegocio_grid->EditRowCnt++;
		if ($uc_regranegocio->CurrentAction == "F") // Confirm row
			$uc_regranegocio_grid->RestoreCurrentRowFormValues($uc_regranegocio_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$uc_regranegocio->RowAttrs = array_merge($uc_regranegocio->RowAttrs, array('data-rowindex'=>$uc_regranegocio_grid->RowCnt, 'id'=>'r' . $uc_regranegocio_grid->RowCnt . '_uc_regranegocio', 'data-rowtype'=>$uc_regranegocio->RowType));

		// Render row
		$uc_regranegocio_grid->RenderRow();

		// Render list options
		$uc_regranegocio_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($uc_regranegocio_grid->RowAction <> "delete" && $uc_regranegocio_grid->RowAction <> "insertdelete" && !($uc_regranegocio_grid->RowAction == "insert" && $uc_regranegocio->CurrentAction == "F" && $uc_regranegocio_grid->EmptyRow())) {
?>
	<tr<?php echo $uc_regranegocio->RowAttributes() ?>>
<?php

// Render list options (body, left)
$uc_regranegocio_grid->ListOptions->Render("body", "left", $uc_regranegocio_grid->RowCnt);
?>
	<?php if ($uc_regranegocio->co_rn->Visible) { // co_rn ?>
		<td<?php echo $uc_regranegocio->co_rn->CellAttributes() ?>>
<?php if ($uc_regranegocio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($uc_regranegocio->co_rn->getSessionValue() <> "") { ?>
<span<?php echo $uc_regranegocio->co_rn->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($uc_regranegocio->co_rn->ListViewValue()) && $uc_regranegocio->co_rn->LinkAttributes() <> "") { ?>
<a<?php echo $uc_regranegocio->co_rn->LinkAttributes() ?>><?php echo $uc_regranegocio->co_rn->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $uc_regranegocio->co_rn->ListViewValue() ?>
<?php } ?>
<div id="tt_uc_regranegocio_x<?php echo $uc_regranegocio_grid->RowCnt ?>_co_rn" style="display: none">
<?php echo $uc_regranegocio->co_rn->TooltipValue ?>
</div></span>
<input type="hidden" id="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" name="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" value="<?php echo ew_HtmlEncode($uc_regranegocio->co_rn->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_co_rn" id="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" name="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn"<?php echo $uc_regranegocio->co_rn->EditAttributes() ?>>
<?php
if (is_array($uc_regranegocio->co_rn->EditValue)) {
	$arwrk = $uc_regranegocio->co_rn->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc_regranegocio->co_rn->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$uc_regranegocio->co_rn) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $uc_regranegocio->co_rn->OldValue = "";
?>
</select>
<script type="text/javascript">
fuc_regranegociogrid.Lists["x_co_rn"].Options = <?php echo (is_array($uc_regranegocio->co_rn->EditValue)) ? ew_ArrayToJson($uc_regranegocio->co_rn->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_co_rn" name="o<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" id="o<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" value="<?php echo ew_HtmlEncode($uc_regranegocio->co_rn->OldValue) ?>">
<?php } ?>
<?php if ($uc_regranegocio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $uc_regranegocio->co_rn->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($uc_regranegocio->co_rn->EditValue) && $uc_regranegocio->co_rn->LinkAttributes() <> "") { ?>
<a<?php echo $uc_regranegocio->co_rn->LinkAttributes() ?>><?php echo $uc_regranegocio->co_rn->EditValue ?></a>
<?php } else { ?>
<?php echo $uc_regranegocio->co_rn->EditValue ?>
<?php } ?>
<div id="tt_uc_regranegocio_x<?php echo $uc_regranegocio_grid->RowCnt ?>_co_rn" style="display: none">
<?php echo $uc_regranegocio->co_rn->TooltipValue ?>
</div></span>
<input type="hidden" data-field="x_co_rn" name="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" id="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" value="<?php echo ew_HtmlEncode($uc_regranegocio->co_rn->CurrentValue) ?>">
<?php } ?>
<?php if ($uc_regranegocio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $uc_regranegocio->co_rn->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($uc_regranegocio->co_rn->ListViewValue()) && $uc_regranegocio->co_rn->LinkAttributes() <> "") { ?>
<a<?php echo $uc_regranegocio->co_rn->LinkAttributes() ?>><?php echo $uc_regranegocio->co_rn->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $uc_regranegocio->co_rn->ListViewValue() ?>
<?php } ?>
<div id="tt_uc_regranegocio_x<?php echo $uc_regranegocio_grid->RowCnt ?>_co_rn" style="display: none">
<?php echo $uc_regranegocio->co_rn->TooltipValue ?>
</div></span>
<input type="hidden" data-field="x_co_rn" name="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" id="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" value="<?php echo ew_HtmlEncode($uc_regranegocio->co_rn->FormValue) ?>">
<input type="hidden" data-field="x_co_rn" name="o<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" id="o<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" value="<?php echo ew_HtmlEncode($uc_regranegocio->co_rn->OldValue) ?>">
<?php } ?>
<a id="<?php echo $uc_regranegocio_grid->PageObjName . "_row_" . $uc_regranegocio_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($uc_regranegocio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $uc_regranegocio_grid->RowIndex ?>_nu_uc" id="x<?php echo $uc_regranegocio_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc_regranegocio->nu_uc->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $uc_regranegocio_grid->RowIndex ?>_nu_uc" id="o<?php echo $uc_regranegocio_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc_regranegocio->nu_uc->OldValue) ?>">
<?php } ?>
<?php if ($uc_regranegocio->RowType == EW_ROWTYPE_EDIT || $uc_regranegocio->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $uc_regranegocio_grid->RowIndex ?>_nu_uc" id="x<?php echo $uc_regranegocio_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc_regranegocio->nu_uc->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$uc_regranegocio_grid->ListOptions->Render("body", "right", $uc_regranegocio_grid->RowCnt);
?>
	</tr>
<?php if ($uc_regranegocio->RowType == EW_ROWTYPE_ADD || $uc_regranegocio->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fuc_regranegociogrid.UpdateOpts(<?php echo $uc_regranegocio_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($uc_regranegocio->CurrentAction <> "gridadd" || $uc_regranegocio->CurrentMode == "copy")
		if (!$uc_regranegocio_grid->Recordset->EOF) $uc_regranegocio_grid->Recordset->MoveNext();
}
?>
<?php
	if ($uc_regranegocio->CurrentMode == "add" || $uc_regranegocio->CurrentMode == "copy" || $uc_regranegocio->CurrentMode == "edit") {
		$uc_regranegocio_grid->RowIndex = '$rowindex$';
		$uc_regranegocio_grid->LoadDefaultValues();

		// Set row properties
		$uc_regranegocio->ResetAttrs();
		$uc_regranegocio->RowAttrs = array_merge($uc_regranegocio->RowAttrs, array('data-rowindex'=>$uc_regranegocio_grid->RowIndex, 'id'=>'r0_uc_regranegocio', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($uc_regranegocio->RowAttrs["class"], "ewTemplate");
		$uc_regranegocio->RowType = EW_ROWTYPE_ADD;

		// Render row
		$uc_regranegocio_grid->RenderRow();

		// Render list options
		$uc_regranegocio_grid->RenderListOptions();
		$uc_regranegocio_grid->StartRowCnt = 0;
?>
	<tr<?php echo $uc_regranegocio->RowAttributes() ?>>
<?php

// Render list options (body, left)
$uc_regranegocio_grid->ListOptions->Render("body", "left", $uc_regranegocio_grid->RowIndex);
?>
	<?php if ($uc_regranegocio->co_rn->Visible) { // co_rn ?>
		<td>
<?php if ($uc_regranegocio->CurrentAction <> "F") { ?>
<?php if ($uc_regranegocio->co_rn->getSessionValue() <> "") { ?>
<span<?php echo $uc_regranegocio->co_rn->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($uc_regranegocio->co_rn->ListViewValue()) && $uc_regranegocio->co_rn->LinkAttributes() <> "") { ?>
<a<?php echo $uc_regranegocio->co_rn->LinkAttributes() ?>><?php echo $uc_regranegocio->co_rn->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $uc_regranegocio->co_rn->ListViewValue() ?>
<?php } ?>
<div id="tt_uc_regranegocio_x<?php echo $uc_regranegocio_grid->RowCnt ?>_co_rn" style="display: none">
<?php echo $uc_regranegocio->co_rn->TooltipValue ?>
</div></span>
<input type="hidden" id="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" name="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" value="<?php echo ew_HtmlEncode($uc_regranegocio->co_rn->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_co_rn" id="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" name="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn"<?php echo $uc_regranegocio->co_rn->EditAttributes() ?>>
<?php
if (is_array($uc_regranegocio->co_rn->EditValue)) {
	$arwrk = $uc_regranegocio->co_rn->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc_regranegocio->co_rn->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$uc_regranegocio->co_rn) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $uc_regranegocio->co_rn->OldValue = "";
?>
</select>
<script type="text/javascript">
fuc_regranegociogrid.Lists["x_co_rn"].Options = <?php echo (is_array($uc_regranegocio->co_rn->EditValue)) ? ew_ArrayToJson($uc_regranegocio->co_rn->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $uc_regranegocio->co_rn->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($uc_regranegocio->co_rn->ViewValue) && $uc_regranegocio->co_rn->LinkAttributes() <> "") { ?>
<a<?php echo $uc_regranegocio->co_rn->LinkAttributes() ?>><?php echo $uc_regranegocio->co_rn->ViewValue ?></a>
<?php } else { ?>
<?php echo $uc_regranegocio->co_rn->ViewValue ?>
<?php } ?>
<div id="tt_uc_regranegocio_x<?php echo $uc_regranegocio_grid->RowCnt ?>_co_rn" style="display: none">
<?php echo $uc_regranegocio->co_rn->TooltipValue ?>
</div></span>
<input type="hidden" data-field="x_co_rn" name="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" id="x<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" value="<?php echo ew_HtmlEncode($uc_regranegocio->co_rn->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_co_rn" name="o<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" id="o<?php echo $uc_regranegocio_grid->RowIndex ?>_co_rn" value="<?php echo ew_HtmlEncode($uc_regranegocio->co_rn->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$uc_regranegocio_grid->ListOptions->Render("body", "right", $uc_regranegocio_grid->RowCnt);
?>
<script type="text/javascript">
fuc_regranegociogrid.UpdateOpts(<?php echo $uc_regranegocio_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($uc_regranegocio->CurrentMode == "add" || $uc_regranegocio->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $uc_regranegocio_grid->FormKeyCountName ?>" id="<?php echo $uc_regranegocio_grid->FormKeyCountName ?>" value="<?php echo $uc_regranegocio_grid->KeyCount ?>">
<?php echo $uc_regranegocio_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($uc_regranegocio->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $uc_regranegocio_grid->FormKeyCountName ?>" id="<?php echo $uc_regranegocio_grid->FormKeyCountName ?>" value="<?php echo $uc_regranegocio_grid->KeyCount ?>">
<?php echo $uc_regranegocio_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($uc_regranegocio->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fuc_regranegociogrid">
</div>
<?php

// Close recordset
if ($uc_regranegocio_grid->Recordset)
	$uc_regranegocio_grid->Recordset->Close();
?>
<?php if ($uc_regranegocio_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($uc_regranegocio_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($uc_regranegocio->Export == "") { ?>
<script type="text/javascript">
fuc_regranegociogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$uc_regranegocio_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$uc_regranegocio_grid->Page_Terminate();
?>
