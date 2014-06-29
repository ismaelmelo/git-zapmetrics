<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($nffatura_grid)) $nffatura_grid = new cnffatura_grid();

// Page init
$nffatura_grid->Page_Init();

// Page main
$nffatura_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$nffatura_grid->Page_Render();
?>
<?php if ($nffatura->Export == "") { ?>
<script type="text/javascript">

// Page object
var nffatura_grid = new ew_Page("nffatura_grid");
nffatura_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = nffatura_grid.PageID; // For backward compatibility

// Form object
var fnffaturagrid = new ew_Form("fnffaturagrid");
fnffaturagrid.FormKeyCountName = '<?php echo $nffatura_grid->FormKeyCountName ?>';

// Validate form
fnffaturagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_notaFiscal");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($nffatura->nu_notaFiscal->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_notaFiscal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($nffatura->nu_notaFiscal->FldErrMsg()) ?>");

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
fnffaturagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_notaFiscal", false)) return false;
	return true;
}

// Form_CustomValidate event
fnffaturagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnffaturagrid.ValidateRequired = true;
<?php } else { ?>
fnffaturagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($nffatura->getCurrentMasterTable() == "" && $nffatura_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $nffatura_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($nffatura->CurrentAction == "gridadd") {
	if ($nffatura->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$nffatura_grid->TotalRecs = $nffatura->SelectRecordCount();
			$nffatura_grid->Recordset = $nffatura_grid->LoadRecordset($nffatura_grid->StartRec-1, $nffatura_grid->DisplayRecs);
		} else {
			if ($nffatura_grid->Recordset = $nffatura_grid->LoadRecordset())
				$nffatura_grid->TotalRecs = $nffatura_grid->Recordset->RecordCount();
		}
		$nffatura_grid->StartRec = 1;
		$nffatura_grid->DisplayRecs = $nffatura_grid->TotalRecs;
	} else {
		$nffatura->CurrentFilter = "0=1";
		$nffatura_grid->StartRec = 1;
		$nffatura_grid->DisplayRecs = $nffatura->GridAddRowCount;
	}
	$nffatura_grid->TotalRecs = $nffatura_grid->DisplayRecs;
	$nffatura_grid->StopRec = $nffatura_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$nffatura_grid->TotalRecs = $nffatura->SelectRecordCount();
	} else {
		if ($nffatura_grid->Recordset = $nffatura_grid->LoadRecordset())
			$nffatura_grid->TotalRecs = $nffatura_grid->Recordset->RecordCount();
	}
	$nffatura_grid->StartRec = 1;
	$nffatura_grid->DisplayRecs = $nffatura_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$nffatura_grid->Recordset = $nffatura_grid->LoadRecordset($nffatura_grid->StartRec-1, $nffatura_grid->DisplayRecs);
}
$nffatura_grid->RenderOtherOptions();
?>
<?php $nffatura_grid->ShowPageHeader(); ?>
<?php
$nffatura_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fnffaturagrid" class="ewForm form-horizontal">
<div id="gmp_nffatura" class="ewGridMiddlePanel">
<table id="tbl_nffaturagrid" class="ewTable ewTableSeparate">
<?php echo $nffatura->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$nffatura_grid->RenderListOptions();

// Render list options (header, left)
$nffatura_grid->ListOptions->Render("header", "left");
?>
<?php if ($nffatura->nu_notaFiscal->Visible) { // nu_notaFiscal ?>
	<?php if ($nffatura->SortUrl($nffatura->nu_notaFiscal) == "") { ?>
		<td><div id="elh_nffatura_nu_notaFiscal" class="nffatura_nu_notaFiscal"><div class="ewTableHeaderCaption"><?php echo $nffatura->nu_notaFiscal->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_nffatura_nu_notaFiscal" class="nffatura_nu_notaFiscal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $nffatura->nu_notaFiscal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($nffatura->nu_notaFiscal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($nffatura->nu_notaFiscal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$nffatura_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$nffatura_grid->StartRec = 1;
$nffatura_grid->StopRec = $nffatura_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($nffatura_grid->FormKeyCountName) && ($nffatura->CurrentAction == "gridadd" || $nffatura->CurrentAction == "gridedit" || $nffatura->CurrentAction == "F")) {
		$nffatura_grid->KeyCount = $objForm->GetValue($nffatura_grid->FormKeyCountName);
		$nffatura_grid->StopRec = $nffatura_grid->StartRec + $nffatura_grid->KeyCount - 1;
	}
}
$nffatura_grid->RecCnt = $nffatura_grid->StartRec - 1;
if ($nffatura_grid->Recordset && !$nffatura_grid->Recordset->EOF) {
	$nffatura_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $nffatura_grid->StartRec > 1)
		$nffatura_grid->Recordset->Move($nffatura_grid->StartRec - 1);
} elseif (!$nffatura->AllowAddDeleteRow && $nffatura_grid->StopRec == 0) {
	$nffatura_grid->StopRec = $nffatura->GridAddRowCount;
}

// Initialize aggregate
$nffatura->RowType = EW_ROWTYPE_AGGREGATEINIT;
$nffatura->ResetAttrs();
$nffatura_grid->RenderRow();
if ($nffatura->CurrentAction == "gridadd")
	$nffatura_grid->RowIndex = 0;
if ($nffatura->CurrentAction == "gridedit")
	$nffatura_grid->RowIndex = 0;
while ($nffatura_grid->RecCnt < $nffatura_grid->StopRec) {
	$nffatura_grid->RecCnt++;
	if (intval($nffatura_grid->RecCnt) >= intval($nffatura_grid->StartRec)) {
		$nffatura_grid->RowCnt++;
		if ($nffatura->CurrentAction == "gridadd" || $nffatura->CurrentAction == "gridedit" || $nffatura->CurrentAction == "F") {
			$nffatura_grid->RowIndex++;
			$objForm->Index = $nffatura_grid->RowIndex;
			if ($objForm->HasValue($nffatura_grid->FormActionName))
				$nffatura_grid->RowAction = strval($objForm->GetValue($nffatura_grid->FormActionName));
			elseif ($nffatura->CurrentAction == "gridadd")
				$nffatura_grid->RowAction = "insert";
			else
				$nffatura_grid->RowAction = "";
		}

		// Set up key count
		$nffatura_grid->KeyCount = $nffatura_grid->RowIndex;

		// Init row class and style
		$nffatura->ResetAttrs();
		$nffatura->CssClass = "";
		if ($nffatura->CurrentAction == "gridadd") {
			if ($nffatura->CurrentMode == "copy") {
				$nffatura_grid->LoadRowValues($nffatura_grid->Recordset); // Load row values
				$nffatura_grid->SetRecordKey($nffatura_grid->RowOldKey, $nffatura_grid->Recordset); // Set old record key
			} else {
				$nffatura_grid->LoadDefaultValues(); // Load default values
				$nffatura_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$nffatura_grid->LoadRowValues($nffatura_grid->Recordset); // Load row values
		}
		$nffatura->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($nffatura->CurrentAction == "gridadd") // Grid add
			$nffatura->RowType = EW_ROWTYPE_ADD; // Render add
		if ($nffatura->CurrentAction == "gridadd" && $nffatura->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$nffatura_grid->RestoreCurrentRowFormValues($nffatura_grid->RowIndex); // Restore form values
		if ($nffatura->CurrentAction == "gridedit") { // Grid edit
			if ($nffatura->EventCancelled) {
				$nffatura_grid->RestoreCurrentRowFormValues($nffatura_grid->RowIndex); // Restore form values
			}
			if ($nffatura_grid->RowAction == "insert")
				$nffatura->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$nffatura->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($nffatura->CurrentAction == "gridedit" && ($nffatura->RowType == EW_ROWTYPE_EDIT || $nffatura->RowType == EW_ROWTYPE_ADD) && $nffatura->EventCancelled) // Update failed
			$nffatura_grid->RestoreCurrentRowFormValues($nffatura_grid->RowIndex); // Restore form values
		if ($nffatura->RowType == EW_ROWTYPE_EDIT) // Edit row
			$nffatura_grid->EditRowCnt++;
		if ($nffatura->CurrentAction == "F") // Confirm row
			$nffatura_grid->RestoreCurrentRowFormValues($nffatura_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$nffatura->RowAttrs = array_merge($nffatura->RowAttrs, array('data-rowindex'=>$nffatura_grid->RowCnt, 'id'=>'r' . $nffatura_grid->RowCnt . '_nffatura', 'data-rowtype'=>$nffatura->RowType));

		// Render row
		$nffatura_grid->RenderRow();

		// Render list options
		$nffatura_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($nffatura_grid->RowAction <> "delete" && $nffatura_grid->RowAction <> "insertdelete" && !($nffatura_grid->RowAction == "insert" && $nffatura->CurrentAction == "F" && $nffatura_grid->EmptyRow())) {
?>
	<tr<?php echo $nffatura->RowAttributes() ?>>
<?php

// Render list options (body, left)
$nffatura_grid->ListOptions->Render("body", "left", $nffatura_grid->RowCnt);
?>
	<?php if ($nffatura->nu_notaFiscal->Visible) { // nu_notaFiscal ?>
		<td<?php echo $nffatura->nu_notaFiscal->CellAttributes() ?>>
<?php if ($nffatura->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $nffatura_grid->RowCnt ?>_nffatura_nu_notaFiscal" class="control-group nffatura_nu_notaFiscal">
<input type="text" data-field="x_nu_notaFiscal" name="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" id="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" size="30" placeholder="<?php echo $nffatura->nu_notaFiscal->PlaceHolder ?>" value="<?php echo $nffatura->nu_notaFiscal->EditValue ?>"<?php echo $nffatura->nu_notaFiscal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_notaFiscal" name="o<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" id="o<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" value="<?php echo ew_HtmlEncode($nffatura->nu_notaFiscal->OldValue) ?>">
<?php } ?>
<?php if ($nffatura->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $nffatura_grid->RowCnt ?>_nffatura_nu_notaFiscal" class="control-group nffatura_nu_notaFiscal">
<input type="text" data-field="x_nu_notaFiscal" name="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" id="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" size="30" placeholder="<?php echo $nffatura->nu_notaFiscal->PlaceHolder ?>" value="<?php echo $nffatura->nu_notaFiscal->EditValue ?>"<?php echo $nffatura->nu_notaFiscal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($nffatura->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $nffatura->nu_notaFiscal->ViewAttributes() ?>>
<?php echo $nffatura->nu_notaFiscal->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_notaFiscal" name="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" id="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" value="<?php echo ew_HtmlEncode($nffatura->nu_notaFiscal->FormValue) ?>">
<input type="hidden" data-field="x_nu_notaFiscal" name="o<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" id="o<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" value="<?php echo ew_HtmlEncode($nffatura->nu_notaFiscal->OldValue) ?>">
<?php } ?>
<a id="<?php echo $nffatura_grid->PageObjName . "_row_" . $nffatura_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($nffatura->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_nfFatura" name="x<?php echo $nffatura_grid->RowIndex ?>_nu_nfFatura" id="x<?php echo $nffatura_grid->RowIndex ?>_nu_nfFatura" value="<?php echo ew_HtmlEncode($nffatura->nu_nfFatura->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_nfFatura" name="o<?php echo $nffatura_grid->RowIndex ?>_nu_nfFatura" id="o<?php echo $nffatura_grid->RowIndex ?>_nu_nfFatura" value="<?php echo ew_HtmlEncode($nffatura->nu_nfFatura->OldValue) ?>">
<?php } ?>
<?php if ($nffatura->RowType == EW_ROWTYPE_EDIT || $nffatura->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_nfFatura" name="x<?php echo $nffatura_grid->RowIndex ?>_nu_nfFatura" id="x<?php echo $nffatura_grid->RowIndex ?>_nu_nfFatura" value="<?php echo ew_HtmlEncode($nffatura->nu_nfFatura->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$nffatura_grid->ListOptions->Render("body", "right", $nffatura_grid->RowCnt);
?>
	</tr>
<?php if ($nffatura->RowType == EW_ROWTYPE_ADD || $nffatura->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fnffaturagrid.UpdateOpts(<?php echo $nffatura_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($nffatura->CurrentAction <> "gridadd" || $nffatura->CurrentMode == "copy")
		if (!$nffatura_grid->Recordset->EOF) $nffatura_grid->Recordset->MoveNext();
}
?>
<?php
	if ($nffatura->CurrentMode == "add" || $nffatura->CurrentMode == "copy" || $nffatura->CurrentMode == "edit") {
		$nffatura_grid->RowIndex = '$rowindex$';
		$nffatura_grid->LoadDefaultValues();

		// Set row properties
		$nffatura->ResetAttrs();
		$nffatura->RowAttrs = array_merge($nffatura->RowAttrs, array('data-rowindex'=>$nffatura_grid->RowIndex, 'id'=>'r0_nffatura', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($nffatura->RowAttrs["class"], "ewTemplate");
		$nffatura->RowType = EW_ROWTYPE_ADD;

		// Render row
		$nffatura_grid->RenderRow();

		// Render list options
		$nffatura_grid->RenderListOptions();
		$nffatura_grid->StartRowCnt = 0;
?>
	<tr<?php echo $nffatura->RowAttributes() ?>>
<?php

// Render list options (body, left)
$nffatura_grid->ListOptions->Render("body", "left", $nffatura_grid->RowIndex);
?>
	<?php if ($nffatura->nu_notaFiscal->Visible) { // nu_notaFiscal ?>
		<td>
<?php if ($nffatura->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_notaFiscal" name="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" id="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" size="30" placeholder="<?php echo $nffatura->nu_notaFiscal->PlaceHolder ?>" value="<?php echo $nffatura->nu_notaFiscal->EditValue ?>"<?php echo $nffatura->nu_notaFiscal->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $nffatura->nu_notaFiscal->ViewAttributes() ?>>
<?php echo $nffatura->nu_notaFiscal->ViewValue ?></span>
<input type="hidden" data-field="x_nu_notaFiscal" name="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" id="x<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" value="<?php echo ew_HtmlEncode($nffatura->nu_notaFiscal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_notaFiscal" name="o<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" id="o<?php echo $nffatura_grid->RowIndex ?>_nu_notaFiscal" value="<?php echo ew_HtmlEncode($nffatura->nu_notaFiscal->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$nffatura_grid->ListOptions->Render("body", "right", $nffatura_grid->RowCnt);
?>
<script type="text/javascript">
fnffaturagrid.UpdateOpts(<?php echo $nffatura_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($nffatura->CurrentMode == "add" || $nffatura->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $nffatura_grid->FormKeyCountName ?>" id="<?php echo $nffatura_grid->FormKeyCountName ?>" value="<?php echo $nffatura_grid->KeyCount ?>">
<?php echo $nffatura_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($nffatura->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $nffatura_grid->FormKeyCountName ?>" id="<?php echo $nffatura_grid->FormKeyCountName ?>" value="<?php echo $nffatura_grid->KeyCount ?>">
<?php echo $nffatura_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($nffatura->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fnffaturagrid">
</div>
<?php

// Close recordset
if ($nffatura_grid->Recordset)
	$nffatura_grid->Recordset->Close();
?>
<?php if ($nffatura_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($nffatura_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($nffatura->Export == "") { ?>
<script type="text/javascript">
fnffaturagrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$nffatura_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$nffatura_grid->Page_Terminate();
?>
