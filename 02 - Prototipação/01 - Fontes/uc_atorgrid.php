<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($uc_ator_grid)) $uc_ator_grid = new cuc_ator_grid();

// Page init
$uc_ator_grid->Page_Init();

// Page main
$uc_ator_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$uc_ator_grid->Page_Render();
?>
<?php if ($uc_ator->Export == "") { ?>
<script type="text/javascript">

// Page object
var uc_ator_grid = new ew_Page("uc_ator_grid");
uc_ator_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = uc_ator_grid.PageID; // For backward compatibility

// Form object
var fuc_atorgrid = new ew_Form("fuc_atorgrid");
fuc_atorgrid.FormKeyCountName = '<?php echo $uc_ator_grid->FormKeyCountName ?>';

// Validate form
fuc_atorgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_ator");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc_ator->nu_ator->FldCaption()) ?>");

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
fuc_atorgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_ator", false)) return false;
	return true;
}

// Form_CustomValidate event
fuc_atorgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuc_atorgrid.ValidateRequired = true;
<?php } else { ?>
fuc_atorgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fuc_atorgrid.Lists["x_nu_ator"] = {"LinkField":"x_nu_ator","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_ator","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($uc_ator->getCurrentMasterTable() == "" && $uc_ator_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $uc_ator_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($uc_ator->CurrentAction == "gridadd") {
	if ($uc_ator->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$uc_ator_grid->TotalRecs = $uc_ator->SelectRecordCount();
			$uc_ator_grid->Recordset = $uc_ator_grid->LoadRecordset($uc_ator_grid->StartRec-1, $uc_ator_grid->DisplayRecs);
		} else {
			if ($uc_ator_grid->Recordset = $uc_ator_grid->LoadRecordset())
				$uc_ator_grid->TotalRecs = $uc_ator_grid->Recordset->RecordCount();
		}
		$uc_ator_grid->StartRec = 1;
		$uc_ator_grid->DisplayRecs = $uc_ator_grid->TotalRecs;
	} else {
		$uc_ator->CurrentFilter = "0=1";
		$uc_ator_grid->StartRec = 1;
		$uc_ator_grid->DisplayRecs = $uc_ator->GridAddRowCount;
	}
	$uc_ator_grid->TotalRecs = $uc_ator_grid->DisplayRecs;
	$uc_ator_grid->StopRec = $uc_ator_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$uc_ator_grid->TotalRecs = $uc_ator->SelectRecordCount();
	} else {
		if ($uc_ator_grid->Recordset = $uc_ator_grid->LoadRecordset())
			$uc_ator_grid->TotalRecs = $uc_ator_grid->Recordset->RecordCount();
	}
	$uc_ator_grid->StartRec = 1;
	$uc_ator_grid->DisplayRecs = $uc_ator_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$uc_ator_grid->Recordset = $uc_ator_grid->LoadRecordset($uc_ator_grid->StartRec-1, $uc_ator_grid->DisplayRecs);
}
$uc_ator_grid->RenderOtherOptions();
?>
<?php $uc_ator_grid->ShowPageHeader(); ?>
<?php
$uc_ator_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fuc_atorgrid" class="ewForm form-horizontal">
<div id="gmp_uc_ator" class="ewGridMiddlePanel">
<table id="tbl_uc_atorgrid" class="ewTable ewTableSeparate">
<?php echo $uc_ator->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$uc_ator_grid->RenderListOptions();

// Render list options (header, left)
$uc_ator_grid->ListOptions->Render("header", "left");
?>
<?php if ($uc_ator->nu_ator->Visible) { // nu_ator ?>
	<?php if ($uc_ator->SortUrl($uc_ator->nu_ator) == "") { ?>
		<td><div id="elh_uc_ator_nu_ator" class="uc_ator_nu_ator"><div class="ewTableHeaderCaption"><?php echo $uc_ator->nu_ator->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_uc_ator_nu_ator" class="uc_ator_nu_ator">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $uc_ator->nu_ator->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($uc_ator->nu_ator->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($uc_ator->nu_ator->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$uc_ator_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$uc_ator_grid->StartRec = 1;
$uc_ator_grid->StopRec = $uc_ator_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($uc_ator_grid->FormKeyCountName) && ($uc_ator->CurrentAction == "gridadd" || $uc_ator->CurrentAction == "gridedit" || $uc_ator->CurrentAction == "F")) {
		$uc_ator_grid->KeyCount = $objForm->GetValue($uc_ator_grid->FormKeyCountName);
		$uc_ator_grid->StopRec = $uc_ator_grid->StartRec + $uc_ator_grid->KeyCount - 1;
	}
}
$uc_ator_grid->RecCnt = $uc_ator_grid->StartRec - 1;
if ($uc_ator_grid->Recordset && !$uc_ator_grid->Recordset->EOF) {
	$uc_ator_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $uc_ator_grid->StartRec > 1)
		$uc_ator_grid->Recordset->Move($uc_ator_grid->StartRec - 1);
} elseif (!$uc_ator->AllowAddDeleteRow && $uc_ator_grid->StopRec == 0) {
	$uc_ator_grid->StopRec = $uc_ator->GridAddRowCount;
}

// Initialize aggregate
$uc_ator->RowType = EW_ROWTYPE_AGGREGATEINIT;
$uc_ator->ResetAttrs();
$uc_ator_grid->RenderRow();
if ($uc_ator->CurrentAction == "gridadd")
	$uc_ator_grid->RowIndex = 0;
if ($uc_ator->CurrentAction == "gridedit")
	$uc_ator_grid->RowIndex = 0;
while ($uc_ator_grid->RecCnt < $uc_ator_grid->StopRec) {
	$uc_ator_grid->RecCnt++;
	if (intval($uc_ator_grid->RecCnt) >= intval($uc_ator_grid->StartRec)) {
		$uc_ator_grid->RowCnt++;
		if ($uc_ator->CurrentAction == "gridadd" || $uc_ator->CurrentAction == "gridedit" || $uc_ator->CurrentAction == "F") {
			$uc_ator_grid->RowIndex++;
			$objForm->Index = $uc_ator_grid->RowIndex;
			if ($objForm->HasValue($uc_ator_grid->FormActionName))
				$uc_ator_grid->RowAction = strval($objForm->GetValue($uc_ator_grid->FormActionName));
			elseif ($uc_ator->CurrentAction == "gridadd")
				$uc_ator_grid->RowAction = "insert";
			else
				$uc_ator_grid->RowAction = "";
		}

		// Set up key count
		$uc_ator_grid->KeyCount = $uc_ator_grid->RowIndex;

		// Init row class and style
		$uc_ator->ResetAttrs();
		$uc_ator->CssClass = "";
		if ($uc_ator->CurrentAction == "gridadd") {
			if ($uc_ator->CurrentMode == "copy") {
				$uc_ator_grid->LoadRowValues($uc_ator_grid->Recordset); // Load row values
				$uc_ator_grid->SetRecordKey($uc_ator_grid->RowOldKey, $uc_ator_grid->Recordset); // Set old record key
			} else {
				$uc_ator_grid->LoadDefaultValues(); // Load default values
				$uc_ator_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$uc_ator_grid->LoadRowValues($uc_ator_grid->Recordset); // Load row values
		}
		$uc_ator->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($uc_ator->CurrentAction == "gridadd") // Grid add
			$uc_ator->RowType = EW_ROWTYPE_ADD; // Render add
		if ($uc_ator->CurrentAction == "gridadd" && $uc_ator->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$uc_ator_grid->RestoreCurrentRowFormValues($uc_ator_grid->RowIndex); // Restore form values
		if ($uc_ator->CurrentAction == "gridedit") { // Grid edit
			if ($uc_ator->EventCancelled) {
				$uc_ator_grid->RestoreCurrentRowFormValues($uc_ator_grid->RowIndex); // Restore form values
			}
			if ($uc_ator_grid->RowAction == "insert")
				$uc_ator->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$uc_ator->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($uc_ator->CurrentAction == "gridedit" && ($uc_ator->RowType == EW_ROWTYPE_EDIT || $uc_ator->RowType == EW_ROWTYPE_ADD) && $uc_ator->EventCancelled) // Update failed
			$uc_ator_grid->RestoreCurrentRowFormValues($uc_ator_grid->RowIndex); // Restore form values
		if ($uc_ator->RowType == EW_ROWTYPE_EDIT) // Edit row
			$uc_ator_grid->EditRowCnt++;
		if ($uc_ator->CurrentAction == "F") // Confirm row
			$uc_ator_grid->RestoreCurrentRowFormValues($uc_ator_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$uc_ator->RowAttrs = array_merge($uc_ator->RowAttrs, array('data-rowindex'=>$uc_ator_grid->RowCnt, 'id'=>'r' . $uc_ator_grid->RowCnt . '_uc_ator', 'data-rowtype'=>$uc_ator->RowType));

		// Render row
		$uc_ator_grid->RenderRow();

		// Render list options
		$uc_ator_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($uc_ator_grid->RowAction <> "delete" && $uc_ator_grid->RowAction <> "insertdelete" && !($uc_ator_grid->RowAction == "insert" && $uc_ator->CurrentAction == "F" && $uc_ator_grid->EmptyRow())) {
?>
	<tr<?php echo $uc_ator->RowAttributes() ?>>
<?php

// Render list options (body, left)
$uc_ator_grid->ListOptions->Render("body", "left", $uc_ator_grid->RowCnt);
?>
	<?php if ($uc_ator->nu_ator->Visible) { // nu_ator ?>
		<td<?php echo $uc_ator->nu_ator->CellAttributes() ?>>
<?php if ($uc_ator->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $uc_ator_grid->RowCnt ?>_uc_ator_nu_ator" class="control-group uc_ator_nu_ator">
<select data-field="x_nu_ator" id="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" name="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator"<?php echo $uc_ator->nu_ator->EditAttributes() ?>>
<?php
if (is_array($uc_ator->nu_ator->EditValue)) {
	$arwrk = $uc_ator->nu_ator->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc_ator->nu_ator->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $uc_ator->nu_ator->OldValue = "";
?>
</select>
<script type="text/javascript">
fuc_atorgrid.Lists["x_nu_ator"].Options = <?php echo (is_array($uc_ator->nu_ator->EditValue)) ? ew_ArrayToJson($uc_ator->nu_ator->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_ator" name="o<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" id="o<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" value="<?php echo ew_HtmlEncode($uc_ator->nu_ator->OldValue) ?>">
<?php } ?>
<?php if ($uc_ator->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $uc_ator_grid->RowCnt ?>_uc_ator_nu_ator" class="control-group uc_ator_nu_ator">
<span<?php echo $uc_ator->nu_ator->ViewAttributes() ?>>
<?php echo $uc_ator->nu_ator->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_ator" name="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" id="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" value="<?php echo ew_HtmlEncode($uc_ator->nu_ator->CurrentValue) ?>">
<?php } ?>
<?php if ($uc_ator->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $uc_ator->nu_ator->ViewAttributes() ?>>
<?php echo $uc_ator->nu_ator->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_ator" name="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" id="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" value="<?php echo ew_HtmlEncode($uc_ator->nu_ator->FormValue) ?>">
<input type="hidden" data-field="x_nu_ator" name="o<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" id="o<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" value="<?php echo ew_HtmlEncode($uc_ator->nu_ator->OldValue) ?>">
<?php } ?>
<a id="<?php echo $uc_ator_grid->PageObjName . "_row_" . $uc_ator_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($uc_ator->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $uc_ator_grid->RowIndex ?>_nu_uc" id="x<?php echo $uc_ator_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc_ator->nu_uc->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $uc_ator_grid->RowIndex ?>_nu_uc" id="o<?php echo $uc_ator_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc_ator->nu_uc->OldValue) ?>">
<?php } ?>
<?php if ($uc_ator->RowType == EW_ROWTYPE_EDIT || $uc_ator->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $uc_ator_grid->RowIndex ?>_nu_uc" id="x<?php echo $uc_ator_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc_ator->nu_uc->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$uc_ator_grid->ListOptions->Render("body", "right", $uc_ator_grid->RowCnt);
?>
	</tr>
<?php if ($uc_ator->RowType == EW_ROWTYPE_ADD || $uc_ator->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fuc_atorgrid.UpdateOpts(<?php echo $uc_ator_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($uc_ator->CurrentAction <> "gridadd" || $uc_ator->CurrentMode == "copy")
		if (!$uc_ator_grid->Recordset->EOF) $uc_ator_grid->Recordset->MoveNext();
}
?>
<?php
	if ($uc_ator->CurrentMode == "add" || $uc_ator->CurrentMode == "copy" || $uc_ator->CurrentMode == "edit") {
		$uc_ator_grid->RowIndex = '$rowindex$';
		$uc_ator_grid->LoadDefaultValues();

		// Set row properties
		$uc_ator->ResetAttrs();
		$uc_ator->RowAttrs = array_merge($uc_ator->RowAttrs, array('data-rowindex'=>$uc_ator_grid->RowIndex, 'id'=>'r0_uc_ator', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($uc_ator->RowAttrs["class"], "ewTemplate");
		$uc_ator->RowType = EW_ROWTYPE_ADD;

		// Render row
		$uc_ator_grid->RenderRow();

		// Render list options
		$uc_ator_grid->RenderListOptions();
		$uc_ator_grid->StartRowCnt = 0;
?>
	<tr<?php echo $uc_ator->RowAttributes() ?>>
<?php

// Render list options (body, left)
$uc_ator_grid->ListOptions->Render("body", "left", $uc_ator_grid->RowIndex);
?>
	<?php if ($uc_ator->nu_ator->Visible) { // nu_ator ?>
		<td>
<?php if ($uc_ator->CurrentAction <> "F") { ?>
<select data-field="x_nu_ator" id="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" name="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator"<?php echo $uc_ator->nu_ator->EditAttributes() ?>>
<?php
if (is_array($uc_ator->nu_ator->EditValue)) {
	$arwrk = $uc_ator->nu_ator->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc_ator->nu_ator->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $uc_ator->nu_ator->OldValue = "";
?>
</select>
<script type="text/javascript">
fuc_atorgrid.Lists["x_nu_ator"].Options = <?php echo (is_array($uc_ator->nu_ator->EditValue)) ? ew_ArrayToJson($uc_ator->nu_ator->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $uc_ator->nu_ator->ViewAttributes() ?>>
<?php echo $uc_ator->nu_ator->ViewValue ?></span>
<input type="hidden" data-field="x_nu_ator" name="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" id="x<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" value="<?php echo ew_HtmlEncode($uc_ator->nu_ator->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_ator" name="o<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" id="o<?php echo $uc_ator_grid->RowIndex ?>_nu_ator" value="<?php echo ew_HtmlEncode($uc_ator->nu_ator->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$uc_ator_grid->ListOptions->Render("body", "right", $uc_ator_grid->RowCnt);
?>
<script type="text/javascript">
fuc_atorgrid.UpdateOpts(<?php echo $uc_ator_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($uc_ator->CurrentMode == "add" || $uc_ator->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $uc_ator_grid->FormKeyCountName ?>" id="<?php echo $uc_ator_grid->FormKeyCountName ?>" value="<?php echo $uc_ator_grid->KeyCount ?>">
<?php echo $uc_ator_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($uc_ator->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $uc_ator_grid->FormKeyCountName ?>" id="<?php echo $uc_ator_grid->FormKeyCountName ?>" value="<?php echo $uc_ator_grid->KeyCount ?>">
<?php echo $uc_ator_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($uc_ator->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fuc_atorgrid">
</div>
<?php

// Close recordset
if ($uc_ator_grid->Recordset)
	$uc_ator_grid->Recordset->Close();
?>
<?php if ($uc_ator_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($uc_ator_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($uc_ator->Export == "") { ?>
<script type="text/javascript">
fuc_atorgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$uc_ator_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$uc_ator_grid->Page_Terminate();
?>
