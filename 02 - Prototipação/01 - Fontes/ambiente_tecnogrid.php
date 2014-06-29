<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($ambiente_tecno_grid)) $ambiente_tecno_grid = new cambiente_tecno_grid();

// Page init
$ambiente_tecno_grid->Page_Init();

// Page main
$ambiente_tecno_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_tecno_grid->Page_Render();
?>
<?php if ($ambiente_tecno->Export == "") { ?>
<script type="text/javascript">

// Page object
var ambiente_tecno_grid = new ew_Page("ambiente_tecno_grid");
ambiente_tecno_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = ambiente_tecno_grid.PageID; // For backward compatibility

// Form object
var fambiente_tecnogrid = new ew_Form("fambiente_tecnogrid");
fambiente_tecnogrid.FormKeyCountName = '<?php echo $ambiente_tecno_grid->FormKeyCountName ?>';

// Validate form
fambiente_tecnogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_tecnoDesenv");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_tecno->nu_tecnoDesenv->FldCaption()) ?>");

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
fambiente_tecnogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_tecnoDesenv", false)) return false;
	return true;
}

// Form_CustomValidate event
fambiente_tecnogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_tecnogrid.ValidateRequired = true;
<?php } else { ?>
fambiente_tecnogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fambiente_tecnogrid.Lists["x_nu_tecnoDesenv"] = {"LinkField":"x_nu_tecnoDesenv","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tecDesenv","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($ambiente_tecno->getCurrentMasterTable() == "" && $ambiente_tecno_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $ambiente_tecno_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($ambiente_tecno->CurrentAction == "gridadd") {
	if ($ambiente_tecno->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$ambiente_tecno_grid->TotalRecs = $ambiente_tecno->SelectRecordCount();
			$ambiente_tecno_grid->Recordset = $ambiente_tecno_grid->LoadRecordset($ambiente_tecno_grid->StartRec-1, $ambiente_tecno_grid->DisplayRecs);
		} else {
			if ($ambiente_tecno_grid->Recordset = $ambiente_tecno_grid->LoadRecordset())
				$ambiente_tecno_grid->TotalRecs = $ambiente_tecno_grid->Recordset->RecordCount();
		}
		$ambiente_tecno_grid->StartRec = 1;
		$ambiente_tecno_grid->DisplayRecs = $ambiente_tecno_grid->TotalRecs;
	} else {
		$ambiente_tecno->CurrentFilter = "0=1";
		$ambiente_tecno_grid->StartRec = 1;
		$ambiente_tecno_grid->DisplayRecs = $ambiente_tecno->GridAddRowCount;
	}
	$ambiente_tecno_grid->TotalRecs = $ambiente_tecno_grid->DisplayRecs;
	$ambiente_tecno_grid->StopRec = $ambiente_tecno_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$ambiente_tecno_grid->TotalRecs = $ambiente_tecno->SelectRecordCount();
	} else {
		if ($ambiente_tecno_grid->Recordset = $ambiente_tecno_grid->LoadRecordset())
			$ambiente_tecno_grid->TotalRecs = $ambiente_tecno_grid->Recordset->RecordCount();
	}
	$ambiente_tecno_grid->StartRec = 1;
	$ambiente_tecno_grid->DisplayRecs = $ambiente_tecno_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$ambiente_tecno_grid->Recordset = $ambiente_tecno_grid->LoadRecordset($ambiente_tecno_grid->StartRec-1, $ambiente_tecno_grid->DisplayRecs);
}
$ambiente_tecno_grid->RenderOtherOptions();
?>
<?php $ambiente_tecno_grid->ShowPageHeader(); ?>
<?php
$ambiente_tecno_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fambiente_tecnogrid" class="ewForm form-horizontal">
<div id="gmp_ambiente_tecno" class="ewGridMiddlePanel">
<table id="tbl_ambiente_tecnogrid" class="ewTable ewTableSeparate">
<?php echo $ambiente_tecno->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$ambiente_tecno_grid->RenderListOptions();

// Render list options (header, left)
$ambiente_tecno_grid->ListOptions->Render("header", "left");
?>
<?php if ($ambiente_tecno->nu_tecnoDesenv->Visible) { // nu_tecnoDesenv ?>
	<?php if ($ambiente_tecno->SortUrl($ambiente_tecno->nu_tecnoDesenv) == "") { ?>
		<td><div id="elh_ambiente_tecno_nu_tecnoDesenv" class="ambiente_tecno_nu_tecnoDesenv"><div class="ewTableHeaderCaption"><?php echo $ambiente_tecno->nu_tecnoDesenv->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_tecno_nu_tecnoDesenv" class="ambiente_tecno_nu_tecnoDesenv">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_tecno->nu_tecnoDesenv->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_tecno->nu_tecnoDesenv->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_tecno->nu_tecnoDesenv->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ambiente_tecno_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$ambiente_tecno_grid->StartRec = 1;
$ambiente_tecno_grid->StopRec = $ambiente_tecno_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($ambiente_tecno_grid->FormKeyCountName) && ($ambiente_tecno->CurrentAction == "gridadd" || $ambiente_tecno->CurrentAction == "gridedit" || $ambiente_tecno->CurrentAction == "F")) {
		$ambiente_tecno_grid->KeyCount = $objForm->GetValue($ambiente_tecno_grid->FormKeyCountName);
		$ambiente_tecno_grid->StopRec = $ambiente_tecno_grid->StartRec + $ambiente_tecno_grid->KeyCount - 1;
	}
}
$ambiente_tecno_grid->RecCnt = $ambiente_tecno_grid->StartRec - 1;
if ($ambiente_tecno_grid->Recordset && !$ambiente_tecno_grid->Recordset->EOF) {
	$ambiente_tecno_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $ambiente_tecno_grid->StartRec > 1)
		$ambiente_tecno_grid->Recordset->Move($ambiente_tecno_grid->StartRec - 1);
} elseif (!$ambiente_tecno->AllowAddDeleteRow && $ambiente_tecno_grid->StopRec == 0) {
	$ambiente_tecno_grid->StopRec = $ambiente_tecno->GridAddRowCount;
}

// Initialize aggregate
$ambiente_tecno->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ambiente_tecno->ResetAttrs();
$ambiente_tecno_grid->RenderRow();
if ($ambiente_tecno->CurrentAction == "gridadd")
	$ambiente_tecno_grid->RowIndex = 0;
if ($ambiente_tecno->CurrentAction == "gridedit")
	$ambiente_tecno_grid->RowIndex = 0;
while ($ambiente_tecno_grid->RecCnt < $ambiente_tecno_grid->StopRec) {
	$ambiente_tecno_grid->RecCnt++;
	if (intval($ambiente_tecno_grid->RecCnt) >= intval($ambiente_tecno_grid->StartRec)) {
		$ambiente_tecno_grid->RowCnt++;
		if ($ambiente_tecno->CurrentAction == "gridadd" || $ambiente_tecno->CurrentAction == "gridedit" || $ambiente_tecno->CurrentAction == "F") {
			$ambiente_tecno_grid->RowIndex++;
			$objForm->Index = $ambiente_tecno_grid->RowIndex;
			if ($objForm->HasValue($ambiente_tecno_grid->FormActionName))
				$ambiente_tecno_grid->RowAction = strval($objForm->GetValue($ambiente_tecno_grid->FormActionName));
			elseif ($ambiente_tecno->CurrentAction == "gridadd")
				$ambiente_tecno_grid->RowAction = "insert";
			else
				$ambiente_tecno_grid->RowAction = "";
		}

		// Set up key count
		$ambiente_tecno_grid->KeyCount = $ambiente_tecno_grid->RowIndex;

		// Init row class and style
		$ambiente_tecno->ResetAttrs();
		$ambiente_tecno->CssClass = "";
		if ($ambiente_tecno->CurrentAction == "gridadd") {
			if ($ambiente_tecno->CurrentMode == "copy") {
				$ambiente_tecno_grid->LoadRowValues($ambiente_tecno_grid->Recordset); // Load row values
				$ambiente_tecno_grid->SetRecordKey($ambiente_tecno_grid->RowOldKey, $ambiente_tecno_grid->Recordset); // Set old record key
			} else {
				$ambiente_tecno_grid->LoadDefaultValues(); // Load default values
				$ambiente_tecno_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$ambiente_tecno_grid->LoadRowValues($ambiente_tecno_grid->Recordset); // Load row values
		}
		$ambiente_tecno->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($ambiente_tecno->CurrentAction == "gridadd") // Grid add
			$ambiente_tecno->RowType = EW_ROWTYPE_ADD; // Render add
		if ($ambiente_tecno->CurrentAction == "gridadd" && $ambiente_tecno->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$ambiente_tecno_grid->RestoreCurrentRowFormValues($ambiente_tecno_grid->RowIndex); // Restore form values
		if ($ambiente_tecno->CurrentAction == "gridedit") { // Grid edit
			if ($ambiente_tecno->EventCancelled) {
				$ambiente_tecno_grid->RestoreCurrentRowFormValues($ambiente_tecno_grid->RowIndex); // Restore form values
			}
			if ($ambiente_tecno_grid->RowAction == "insert")
				$ambiente_tecno->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$ambiente_tecno->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($ambiente_tecno->CurrentAction == "gridedit" && ($ambiente_tecno->RowType == EW_ROWTYPE_EDIT || $ambiente_tecno->RowType == EW_ROWTYPE_ADD) && $ambiente_tecno->EventCancelled) // Update failed
			$ambiente_tecno_grid->RestoreCurrentRowFormValues($ambiente_tecno_grid->RowIndex); // Restore form values
		if ($ambiente_tecno->RowType == EW_ROWTYPE_EDIT) // Edit row
			$ambiente_tecno_grid->EditRowCnt++;
		if ($ambiente_tecno->CurrentAction == "F") // Confirm row
			$ambiente_tecno_grid->RestoreCurrentRowFormValues($ambiente_tecno_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$ambiente_tecno->RowAttrs = array_merge($ambiente_tecno->RowAttrs, array('data-rowindex'=>$ambiente_tecno_grid->RowCnt, 'id'=>'r' . $ambiente_tecno_grid->RowCnt . '_ambiente_tecno', 'data-rowtype'=>$ambiente_tecno->RowType));

		// Render row
		$ambiente_tecno_grid->RenderRow();

		// Render list options
		$ambiente_tecno_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($ambiente_tecno_grid->RowAction <> "delete" && $ambiente_tecno_grid->RowAction <> "insertdelete" && !($ambiente_tecno_grid->RowAction == "insert" && $ambiente_tecno->CurrentAction == "F" && $ambiente_tecno_grid->EmptyRow())) {
?>
	<tr<?php echo $ambiente_tecno->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ambiente_tecno_grid->ListOptions->Render("body", "left", $ambiente_tecno_grid->RowCnt);
?>
	<?php if ($ambiente_tecno->nu_tecnoDesenv->Visible) { // nu_tecnoDesenv ?>
		<td<?php echo $ambiente_tecno->nu_tecnoDesenv->CellAttributes() ?>>
<?php if ($ambiente_tecno->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_tecno_grid->RowCnt ?>_ambiente_tecno_nu_tecnoDesenv" class="control-group ambiente_tecno_nu_tecnoDesenv">
<select data-field="x_nu_tecnoDesenv" id="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" name="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv"<?php echo $ambiente_tecno->nu_tecnoDesenv->EditAttributes() ?>>
<?php
if (is_array($ambiente_tecno->nu_tecnoDesenv->EditValue)) {
	$arwrk = $ambiente_tecno->nu_tecnoDesenv->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_tecno->nu_tecnoDesenv->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $ambiente_tecno->nu_tecnoDesenv->OldValue = "";
?>
</select>
<script type="text/javascript">
fambiente_tecnogrid.Lists["x_nu_tecnoDesenv"].Options = <?php echo (is_array($ambiente_tecno->nu_tecnoDesenv->EditValue)) ? ew_ArrayToJson($ambiente_tecno->nu_tecnoDesenv->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_tecnoDesenv" name="o<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" id="o<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" value="<?php echo ew_HtmlEncode($ambiente_tecno->nu_tecnoDesenv->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_tecno->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_tecno_grid->RowCnt ?>_ambiente_tecno_nu_tecnoDesenv" class="control-group ambiente_tecno_nu_tecnoDesenv">
<span<?php echo $ambiente_tecno->nu_tecnoDesenv->ViewAttributes() ?>>
<?php echo $ambiente_tecno->nu_tecnoDesenv->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_tecnoDesenv" name="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" id="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" value="<?php echo ew_HtmlEncode($ambiente_tecno->nu_tecnoDesenv->CurrentValue) ?>">
<?php } ?>
<?php if ($ambiente_tecno->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_tecno->nu_tecnoDesenv->ViewAttributes() ?>>
<?php echo $ambiente_tecno->nu_tecnoDesenv->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_tecnoDesenv" name="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" id="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" value="<?php echo ew_HtmlEncode($ambiente_tecno->nu_tecnoDesenv->FormValue) ?>">
<input type="hidden" data-field="x_nu_tecnoDesenv" name="o<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" id="o<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" value="<?php echo ew_HtmlEncode($ambiente_tecno->nu_tecnoDesenv->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_tecno_grid->PageObjName . "_row_" . $ambiente_tecno_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($ambiente_tecno->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_ambiente" name="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_ambiente" id="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_ambiente" value="<?php echo ew_HtmlEncode($ambiente_tecno->nu_ambiente->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_ambiente" name="o<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_ambiente" id="o<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_ambiente" value="<?php echo ew_HtmlEncode($ambiente_tecno->nu_ambiente->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_tecno->RowType == EW_ROWTYPE_EDIT || $ambiente_tecno->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_ambiente" name="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_ambiente" id="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_ambiente" value="<?php echo ew_HtmlEncode($ambiente_tecno->nu_ambiente->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$ambiente_tecno_grid->ListOptions->Render("body", "right", $ambiente_tecno_grid->RowCnt);
?>
	</tr>
<?php if ($ambiente_tecno->RowType == EW_ROWTYPE_ADD || $ambiente_tecno->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fambiente_tecnogrid.UpdateOpts(<?php echo $ambiente_tecno_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($ambiente_tecno->CurrentAction <> "gridadd" || $ambiente_tecno->CurrentMode == "copy")
		if (!$ambiente_tecno_grid->Recordset->EOF) $ambiente_tecno_grid->Recordset->MoveNext();
}
?>
<?php
	if ($ambiente_tecno->CurrentMode == "add" || $ambiente_tecno->CurrentMode == "copy" || $ambiente_tecno->CurrentMode == "edit") {
		$ambiente_tecno_grid->RowIndex = '$rowindex$';
		$ambiente_tecno_grid->LoadDefaultValues();

		// Set row properties
		$ambiente_tecno->ResetAttrs();
		$ambiente_tecno->RowAttrs = array_merge($ambiente_tecno->RowAttrs, array('data-rowindex'=>$ambiente_tecno_grid->RowIndex, 'id'=>'r0_ambiente_tecno', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($ambiente_tecno->RowAttrs["class"], "ewTemplate");
		$ambiente_tecno->RowType = EW_ROWTYPE_ADD;

		// Render row
		$ambiente_tecno_grid->RenderRow();

		// Render list options
		$ambiente_tecno_grid->RenderListOptions();
		$ambiente_tecno_grid->StartRowCnt = 0;
?>
	<tr<?php echo $ambiente_tecno->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ambiente_tecno_grid->ListOptions->Render("body", "left", $ambiente_tecno_grid->RowIndex);
?>
	<?php if ($ambiente_tecno->nu_tecnoDesenv->Visible) { // nu_tecnoDesenv ?>
		<td>
<?php if ($ambiente_tecno->CurrentAction <> "F") { ?>
<select data-field="x_nu_tecnoDesenv" id="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" name="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv"<?php echo $ambiente_tecno->nu_tecnoDesenv->EditAttributes() ?>>
<?php
if (is_array($ambiente_tecno->nu_tecnoDesenv->EditValue)) {
	$arwrk = $ambiente_tecno->nu_tecnoDesenv->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_tecno->nu_tecnoDesenv->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $ambiente_tecno->nu_tecnoDesenv->OldValue = "";
?>
</select>
<script type="text/javascript">
fambiente_tecnogrid.Lists["x_nu_tecnoDesenv"].Options = <?php echo (is_array($ambiente_tecno->nu_tecnoDesenv->EditValue)) ? ew_ArrayToJson($ambiente_tecno->nu_tecnoDesenv->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $ambiente_tecno->nu_tecnoDesenv->ViewAttributes() ?>>
<?php echo $ambiente_tecno->nu_tecnoDesenv->ViewValue ?></span>
<input type="hidden" data-field="x_nu_tecnoDesenv" name="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" id="x<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" value="<?php echo ew_HtmlEncode($ambiente_tecno->nu_tecnoDesenv->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_tecnoDesenv" name="o<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" id="o<?php echo $ambiente_tecno_grid->RowIndex ?>_nu_tecnoDesenv" value="<?php echo ew_HtmlEncode($ambiente_tecno->nu_tecnoDesenv->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ambiente_tecno_grid->ListOptions->Render("body", "right", $ambiente_tecno_grid->RowCnt);
?>
<script type="text/javascript">
fambiente_tecnogrid.UpdateOpts(<?php echo $ambiente_tecno_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($ambiente_tecno->CurrentMode == "add" || $ambiente_tecno->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $ambiente_tecno_grid->FormKeyCountName ?>" id="<?php echo $ambiente_tecno_grid->FormKeyCountName ?>" value="<?php echo $ambiente_tecno_grid->KeyCount ?>">
<?php echo $ambiente_tecno_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ambiente_tecno->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $ambiente_tecno_grid->FormKeyCountName ?>" id="<?php echo $ambiente_tecno_grid->FormKeyCountName ?>" value="<?php echo $ambiente_tecno_grid->KeyCount ?>">
<?php echo $ambiente_tecno_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ambiente_tecno->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fambiente_tecnogrid">
</div>
<?php

// Close recordset
if ($ambiente_tecno_grid->Recordset)
	$ambiente_tecno_grid->Recordset->Close();
?>
<?php if ($ambiente_tecno_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($ambiente_tecno_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($ambiente_tecno->Export == "") { ?>
<script type="text/javascript">
fambiente_tecnogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$ambiente_tecno_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$ambiente_tecno_grid->Page_Terminate();
?>
