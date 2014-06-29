<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($prospecto_itempdti_grid)) $prospecto_itempdti_grid = new cprospecto_itempdti_grid();

// Page init
$prospecto_itempdti_grid->Page_Init();

// Page main
$prospecto_itempdti_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$prospecto_itempdti_grid->Page_Render();
?>
<?php if ($prospecto_itempdti->Export == "") { ?>
<script type="text/javascript">

// Page object
var prospecto_itempdti_grid = new ew_Page("prospecto_itempdti_grid");
prospecto_itempdti_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = prospecto_itempdti_grid.PageID; // For backward compatibility

// Form object
var fprospecto_itempdtigrid = new ew_Form("fprospecto_itempdtigrid");
fprospecto_itempdtigrid.FormKeyCountName = '<?php echo $prospecto_itempdti_grid->FormKeyCountName ?>';

// Validate form
fprospecto_itempdtigrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_itemPdti");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospecto_itempdti->nu_itemPdti->FldCaption()) ?>");

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
fprospecto_itempdtigrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_itemPdti", false)) return false;
	return true;
}

// Form_CustomValidate event
fprospecto_itempdtigrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprospecto_itempdtigrid.ValidateRequired = true;
<?php } else { ?>
fprospecto_itempdtigrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($prospecto_itempdti->getCurrentMasterTable() == "" && $prospecto_itempdti_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $prospecto_itempdti_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($prospecto_itempdti->CurrentAction == "gridadd") {
	if ($prospecto_itempdti->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$prospecto_itempdti_grid->TotalRecs = $prospecto_itempdti->SelectRecordCount();
			$prospecto_itempdti_grid->Recordset = $prospecto_itempdti_grid->LoadRecordset($prospecto_itempdti_grid->StartRec-1, $prospecto_itempdti_grid->DisplayRecs);
		} else {
			if ($prospecto_itempdti_grid->Recordset = $prospecto_itempdti_grid->LoadRecordset())
				$prospecto_itempdti_grid->TotalRecs = $prospecto_itempdti_grid->Recordset->RecordCount();
		}
		$prospecto_itempdti_grid->StartRec = 1;
		$prospecto_itempdti_grid->DisplayRecs = $prospecto_itempdti_grid->TotalRecs;
	} else {
		$prospecto_itempdti->CurrentFilter = "0=1";
		$prospecto_itempdti_grid->StartRec = 1;
		$prospecto_itempdti_grid->DisplayRecs = $prospecto_itempdti->GridAddRowCount;
	}
	$prospecto_itempdti_grid->TotalRecs = $prospecto_itempdti_grid->DisplayRecs;
	$prospecto_itempdti_grid->StopRec = $prospecto_itempdti_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$prospecto_itempdti_grid->TotalRecs = $prospecto_itempdti->SelectRecordCount();
	} else {
		if ($prospecto_itempdti_grid->Recordset = $prospecto_itempdti_grid->LoadRecordset())
			$prospecto_itempdti_grid->TotalRecs = $prospecto_itempdti_grid->Recordset->RecordCount();
	}
	$prospecto_itempdti_grid->StartRec = 1;
	$prospecto_itempdti_grid->DisplayRecs = $prospecto_itempdti_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$prospecto_itempdti_grid->Recordset = $prospecto_itempdti_grid->LoadRecordset($prospecto_itempdti_grid->StartRec-1, $prospecto_itempdti_grid->DisplayRecs);
}
$prospecto_itempdti_grid->RenderOtherOptions();
?>
<?php $prospecto_itempdti_grid->ShowPageHeader(); ?>
<?php
$prospecto_itempdti_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fprospecto_itempdtigrid" class="ewForm form-horizontal">
<div id="gmp_prospecto_itempdti" class="ewGridMiddlePanel">
<table id="tbl_prospecto_itempdtigrid" class="ewTable ewTableSeparate">
<?php echo $prospecto_itempdti->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$prospecto_itempdti_grid->RenderListOptions();

// Render list options (header, left)
$prospecto_itempdti_grid->ListOptions->Render("header", "left");
?>
<?php if ($prospecto_itempdti->nu_itemPdti->Visible) { // nu_itemPdti ?>
	<?php if ($prospecto_itempdti->SortUrl($prospecto_itempdti->nu_itemPdti) == "") { ?>
		<td><div id="elh_prospecto_itempdti_nu_itemPdti" class="prospecto_itempdti_nu_itemPdti"><div class="ewTableHeaderCaption"><?php echo $prospecto_itempdti->nu_itemPdti->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospecto_itempdti_nu_itemPdti" class="prospecto_itempdti_nu_itemPdti">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto_itempdti->nu_itemPdti->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto_itempdti->nu_itemPdti->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto_itempdti->nu_itemPdti->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$prospecto_itempdti_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$prospecto_itempdti_grid->StartRec = 1;
$prospecto_itempdti_grid->StopRec = $prospecto_itempdti_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($prospecto_itempdti_grid->FormKeyCountName) && ($prospecto_itempdti->CurrentAction == "gridadd" || $prospecto_itempdti->CurrentAction == "gridedit" || $prospecto_itempdti->CurrentAction == "F")) {
		$prospecto_itempdti_grid->KeyCount = $objForm->GetValue($prospecto_itempdti_grid->FormKeyCountName);
		$prospecto_itempdti_grid->StopRec = $prospecto_itempdti_grid->StartRec + $prospecto_itempdti_grid->KeyCount - 1;
	}
}
$prospecto_itempdti_grid->RecCnt = $prospecto_itempdti_grid->StartRec - 1;
if ($prospecto_itempdti_grid->Recordset && !$prospecto_itempdti_grid->Recordset->EOF) {
	$prospecto_itempdti_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $prospecto_itempdti_grid->StartRec > 1)
		$prospecto_itempdti_grid->Recordset->Move($prospecto_itempdti_grid->StartRec - 1);
} elseif (!$prospecto_itempdti->AllowAddDeleteRow && $prospecto_itempdti_grid->StopRec == 0) {
	$prospecto_itempdti_grid->StopRec = $prospecto_itempdti->GridAddRowCount;
}

// Initialize aggregate
$prospecto_itempdti->RowType = EW_ROWTYPE_AGGREGATEINIT;
$prospecto_itempdti->ResetAttrs();
$prospecto_itempdti_grid->RenderRow();
if ($prospecto_itempdti->CurrentAction == "gridadd")
	$prospecto_itempdti_grid->RowIndex = 0;
if ($prospecto_itempdti->CurrentAction == "gridedit")
	$prospecto_itempdti_grid->RowIndex = 0;
while ($prospecto_itempdti_grid->RecCnt < $prospecto_itempdti_grid->StopRec) {
	$prospecto_itempdti_grid->RecCnt++;
	if (intval($prospecto_itempdti_grid->RecCnt) >= intval($prospecto_itempdti_grid->StartRec)) {
		$prospecto_itempdti_grid->RowCnt++;
		if ($prospecto_itempdti->CurrentAction == "gridadd" || $prospecto_itempdti->CurrentAction == "gridedit" || $prospecto_itempdti->CurrentAction == "F") {
			$prospecto_itempdti_grid->RowIndex++;
			$objForm->Index = $prospecto_itempdti_grid->RowIndex;
			if ($objForm->HasValue($prospecto_itempdti_grid->FormActionName))
				$prospecto_itempdti_grid->RowAction = strval($objForm->GetValue($prospecto_itempdti_grid->FormActionName));
			elseif ($prospecto_itempdti->CurrentAction == "gridadd")
				$prospecto_itempdti_grid->RowAction = "insert";
			else
				$prospecto_itempdti_grid->RowAction = "";
		}

		// Set up key count
		$prospecto_itempdti_grid->KeyCount = $prospecto_itempdti_grid->RowIndex;

		// Init row class and style
		$prospecto_itempdti->ResetAttrs();
		$prospecto_itempdti->CssClass = "";
		if ($prospecto_itempdti->CurrentAction == "gridadd") {
			if ($prospecto_itempdti->CurrentMode == "copy") {
				$prospecto_itempdti_grid->LoadRowValues($prospecto_itempdti_grid->Recordset); // Load row values
				$prospecto_itempdti_grid->SetRecordKey($prospecto_itempdti_grid->RowOldKey, $prospecto_itempdti_grid->Recordset); // Set old record key
			} else {
				$prospecto_itempdti_grid->LoadDefaultValues(); // Load default values
				$prospecto_itempdti_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$prospecto_itempdti_grid->LoadRowValues($prospecto_itempdti_grid->Recordset); // Load row values
		}
		$prospecto_itempdti->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($prospecto_itempdti->CurrentAction == "gridadd") // Grid add
			$prospecto_itempdti->RowType = EW_ROWTYPE_ADD; // Render add
		if ($prospecto_itempdti->CurrentAction == "gridadd" && $prospecto_itempdti->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$prospecto_itempdti_grid->RestoreCurrentRowFormValues($prospecto_itempdti_grid->RowIndex); // Restore form values
		if ($prospecto_itempdti->CurrentAction == "gridedit") { // Grid edit
			if ($prospecto_itempdti->EventCancelled) {
				$prospecto_itempdti_grid->RestoreCurrentRowFormValues($prospecto_itempdti_grid->RowIndex); // Restore form values
			}
			if ($prospecto_itempdti_grid->RowAction == "insert")
				$prospecto_itempdti->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$prospecto_itempdti->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($prospecto_itempdti->CurrentAction == "gridedit" && ($prospecto_itempdti->RowType == EW_ROWTYPE_EDIT || $prospecto_itempdti->RowType == EW_ROWTYPE_ADD) && $prospecto_itempdti->EventCancelled) // Update failed
			$prospecto_itempdti_grid->RestoreCurrentRowFormValues($prospecto_itempdti_grid->RowIndex); // Restore form values
		if ($prospecto_itempdti->RowType == EW_ROWTYPE_EDIT) // Edit row
			$prospecto_itempdti_grid->EditRowCnt++;
		if ($prospecto_itempdti->CurrentAction == "F") // Confirm row
			$prospecto_itempdti_grid->RestoreCurrentRowFormValues($prospecto_itempdti_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$prospecto_itempdti->RowAttrs = array_merge($prospecto_itempdti->RowAttrs, array('data-rowindex'=>$prospecto_itempdti_grid->RowCnt, 'id'=>'r' . $prospecto_itempdti_grid->RowCnt . '_prospecto_itempdti', 'data-rowtype'=>$prospecto_itempdti->RowType));

		// Render row
		$prospecto_itempdti_grid->RenderRow();

		// Render list options
		$prospecto_itempdti_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($prospecto_itempdti_grid->RowAction <> "delete" && $prospecto_itempdti_grid->RowAction <> "insertdelete" && !($prospecto_itempdti_grid->RowAction == "insert" && $prospecto_itempdti->CurrentAction == "F" && $prospecto_itempdti_grid->EmptyRow())) {
?>
	<tr<?php echo $prospecto_itempdti->RowAttributes() ?>>
<?php

// Render list options (body, left)
$prospecto_itempdti_grid->ListOptions->Render("body", "left", $prospecto_itempdti_grid->RowCnt);
?>
	<?php if ($prospecto_itempdti->nu_itemPdti->Visible) { // nu_itemPdti ?>
		<td<?php echo $prospecto_itempdti->nu_itemPdti->CellAttributes() ?>>
<?php if ($prospecto_itempdti->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $prospecto_itempdti_grid->RowCnt ?>_prospecto_itempdti_nu_itemPdti" class="control-group prospecto_itempdti_nu_itemPdti">
<select data-field="x_nu_itemPdti" id="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" name="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti"<?php echo $prospecto_itempdti->nu_itemPdti->EditAttributes() ?>>
<?php
if (is_array($prospecto_itempdti->nu_itemPdti->EditValue)) {
	$arwrk = $prospecto_itempdti->nu_itemPdti->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto_itempdti->nu_itemPdti->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $prospecto_itempdti->nu_itemPdti->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_nu_itemPdti" name="o<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" id="o<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" value="<?php echo ew_HtmlEncode($prospecto_itempdti->nu_itemPdti->OldValue) ?>">
<?php } ?>
<?php if ($prospecto_itempdti->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospecto_itempdti_grid->RowCnt ?>_prospecto_itempdti_nu_itemPdti" class="control-group prospecto_itempdti_nu_itemPdti">
<span<?php echo $prospecto_itempdti->nu_itemPdti->ViewAttributes() ?>>
<?php echo $prospecto_itempdti->nu_itemPdti->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_itemPdti" name="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" id="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" value="<?php echo ew_HtmlEncode($prospecto_itempdti->nu_itemPdti->CurrentValue) ?>">
<?php } ?>
<?php if ($prospecto_itempdti->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospecto_itempdti->nu_itemPdti->ViewAttributes() ?>>
<?php echo $prospecto_itempdti->nu_itemPdti->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_itemPdti" name="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" id="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" value="<?php echo ew_HtmlEncode($prospecto_itempdti->nu_itemPdti->FormValue) ?>">
<input type="hidden" data-field="x_nu_itemPdti" name="o<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" id="o<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" value="<?php echo ew_HtmlEncode($prospecto_itempdti->nu_itemPdti->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospecto_itempdti_grid->PageObjName . "_row_" . $prospecto_itempdti_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($prospecto_itempdti->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_prospecto" name="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_prospecto" id="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto_itempdti->nu_prospecto->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_prospecto" name="o<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_prospecto" id="o<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto_itempdti->nu_prospecto->OldValue) ?>">
<?php } ?>
<?php if ($prospecto_itempdti->RowType == EW_ROWTYPE_EDIT || $prospecto_itempdti->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_prospecto" name="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_prospecto" id="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_prospecto" value="<?php echo ew_HtmlEncode($prospecto_itempdti->nu_prospecto->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$prospecto_itempdti_grid->ListOptions->Render("body", "right", $prospecto_itempdti_grid->RowCnt);
?>
	</tr>
<?php if ($prospecto_itempdti->RowType == EW_ROWTYPE_ADD || $prospecto_itempdti->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fprospecto_itempdtigrid.UpdateOpts(<?php echo $prospecto_itempdti_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($prospecto_itempdti->CurrentAction <> "gridadd" || $prospecto_itempdti->CurrentMode == "copy")
		if (!$prospecto_itempdti_grid->Recordset->EOF) $prospecto_itempdti_grid->Recordset->MoveNext();
}
?>
<?php
	if ($prospecto_itempdti->CurrentMode == "add" || $prospecto_itempdti->CurrentMode == "copy" || $prospecto_itempdti->CurrentMode == "edit") {
		$prospecto_itempdti_grid->RowIndex = '$rowindex$';
		$prospecto_itempdti_grid->LoadDefaultValues();

		// Set row properties
		$prospecto_itempdti->ResetAttrs();
		$prospecto_itempdti->RowAttrs = array_merge($prospecto_itempdti->RowAttrs, array('data-rowindex'=>$prospecto_itempdti_grid->RowIndex, 'id'=>'r0_prospecto_itempdti', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($prospecto_itempdti->RowAttrs["class"], "ewTemplate");
		$prospecto_itempdti->RowType = EW_ROWTYPE_ADD;

		// Render row
		$prospecto_itempdti_grid->RenderRow();

		// Render list options
		$prospecto_itempdti_grid->RenderListOptions();
		$prospecto_itempdti_grid->StartRowCnt = 0;
?>
	<tr<?php echo $prospecto_itempdti->RowAttributes() ?>>
<?php

// Render list options (body, left)
$prospecto_itempdti_grid->ListOptions->Render("body", "left", $prospecto_itempdti_grid->RowIndex);
?>
	<?php if ($prospecto_itempdti->nu_itemPdti->Visible) { // nu_itemPdti ?>
		<td>
<?php if ($prospecto_itempdti->CurrentAction <> "F") { ?>
<select data-field="x_nu_itemPdti" id="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" name="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti"<?php echo $prospecto_itempdti->nu_itemPdti->EditAttributes() ?>>
<?php
if (is_array($prospecto_itempdti->nu_itemPdti->EditValue)) {
	$arwrk = $prospecto_itempdti->nu_itemPdti->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto_itempdti->nu_itemPdti->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $prospecto_itempdti->nu_itemPdti->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $prospecto_itempdti->nu_itemPdti->ViewAttributes() ?>>
<?php echo $prospecto_itempdti->nu_itemPdti->ViewValue ?></span>
<input type="hidden" data-field="x_nu_itemPdti" name="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" id="x<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" value="<?php echo ew_HtmlEncode($prospecto_itempdti->nu_itemPdti->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_itemPdti" name="o<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" id="o<?php echo $prospecto_itempdti_grid->RowIndex ?>_nu_itemPdti" value="<?php echo ew_HtmlEncode($prospecto_itempdti->nu_itemPdti->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$prospecto_itempdti_grid->ListOptions->Render("body", "right", $prospecto_itempdti_grid->RowCnt);
?>
<script type="text/javascript">
fprospecto_itempdtigrid.UpdateOpts(<?php echo $prospecto_itempdti_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($prospecto_itempdti->CurrentMode == "add" || $prospecto_itempdti->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $prospecto_itempdti_grid->FormKeyCountName ?>" id="<?php echo $prospecto_itempdti_grid->FormKeyCountName ?>" value="<?php echo $prospecto_itempdti_grid->KeyCount ?>">
<?php echo $prospecto_itempdti_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($prospecto_itempdti->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $prospecto_itempdti_grid->FormKeyCountName ?>" id="<?php echo $prospecto_itempdti_grid->FormKeyCountName ?>" value="<?php echo $prospecto_itempdti_grid->KeyCount ?>">
<?php echo $prospecto_itempdti_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($prospecto_itempdti->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fprospecto_itempdtigrid">
</div>
<?php

// Close recordset
if ($prospecto_itempdti_grid->Recordset)
	$prospecto_itempdti_grid->Recordset->Close();
?>
<?php if ($prospecto_itempdti_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($prospecto_itempdti_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($prospecto_itempdti->Export == "") { ?>
<script type="text/javascript">
fprospecto_itempdtigrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$prospecto_itempdti_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$prospecto_itempdti_grid->Page_Terminate();
?>
