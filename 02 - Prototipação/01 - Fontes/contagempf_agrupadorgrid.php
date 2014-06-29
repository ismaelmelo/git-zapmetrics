<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($contagempf_agrupador_grid)) $contagempf_agrupador_grid = new ccontagempf_agrupador_grid();

// Page init
$contagempf_agrupador_grid->Page_Init();

// Page main
$contagempf_agrupador_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_agrupador_grid->Page_Render();
?>
<?php if ($contagempf_agrupador->Export == "") { ?>
<script type="text/javascript">

// Page object
var contagempf_agrupador_grid = new ew_Page("contagempf_agrupador_grid");
contagempf_agrupador_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = contagempf_agrupador_grid.PageID; // For backward compatibility

// Form object
var fcontagempf_agrupadorgrid = new ew_Form("fcontagempf_agrupadorgrid");
fcontagempf_agrupadorgrid.FormKeyCountName = '<?php echo $contagempf_agrupador_grid->FormKeyCountName ?>';

// Validate form
fcontagempf_agrupadorgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_agrupador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf_agrupador->no_agrupador->FldCaption()) ?>");

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
fcontagempf_agrupadorgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_agrupador", false)) return false;
	return true;
}

// Form_CustomValidate event
fcontagempf_agrupadorgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempf_agrupadorgrid.ValidateRequired = true;
<?php } else { ?>
fcontagempf_agrupadorgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($contagempf_agrupador->getCurrentMasterTable() == "" && $contagempf_agrupador_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $contagempf_agrupador_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($contagempf_agrupador->CurrentAction == "gridadd") {
	if ($contagempf_agrupador->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$contagempf_agrupador_grid->TotalRecs = $contagempf_agrupador->SelectRecordCount();
			$contagempf_agrupador_grid->Recordset = $contagempf_agrupador_grid->LoadRecordset($contagempf_agrupador_grid->StartRec-1, $contagempf_agrupador_grid->DisplayRecs);
		} else {
			if ($contagempf_agrupador_grid->Recordset = $contagempf_agrupador_grid->LoadRecordset())
				$contagempf_agrupador_grid->TotalRecs = $contagempf_agrupador_grid->Recordset->RecordCount();
		}
		$contagempf_agrupador_grid->StartRec = 1;
		$contagempf_agrupador_grid->DisplayRecs = $contagempf_agrupador_grid->TotalRecs;
	} else {
		$contagempf_agrupador->CurrentFilter = "0=1";
		$contagempf_agrupador_grid->StartRec = 1;
		$contagempf_agrupador_grid->DisplayRecs = $contagempf_agrupador->GridAddRowCount;
	}
	$contagempf_agrupador_grid->TotalRecs = $contagempf_agrupador_grid->DisplayRecs;
	$contagempf_agrupador_grid->StopRec = $contagempf_agrupador_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$contagempf_agrupador_grid->TotalRecs = $contagempf_agrupador->SelectRecordCount();
	} else {
		if ($contagempf_agrupador_grid->Recordset = $contagempf_agrupador_grid->LoadRecordset())
			$contagempf_agrupador_grid->TotalRecs = $contagempf_agrupador_grid->Recordset->RecordCount();
	}
	$contagempf_agrupador_grid->StartRec = 1;
	$contagempf_agrupador_grid->DisplayRecs = $contagempf_agrupador_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$contagempf_agrupador_grid->Recordset = $contagempf_agrupador_grid->LoadRecordset($contagempf_agrupador_grid->StartRec-1, $contagempf_agrupador_grid->DisplayRecs);
}
$contagempf_agrupador_grid->RenderOtherOptions();
?>
<?php $contagempf_agrupador_grid->ShowPageHeader(); ?>
<?php
$contagempf_agrupador_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fcontagempf_agrupadorgrid" class="ewForm form-horizontal">
<div id="gmp_contagempf_agrupador" class="ewGridMiddlePanel">
<table id="tbl_contagempf_agrupadorgrid" class="ewTable ewTableSeparate">
<?php echo $contagempf_agrupador->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$contagempf_agrupador_grid->RenderListOptions();

// Render list options (header, left)
$contagempf_agrupador_grid->ListOptions->Render("header", "left");
?>
<?php if ($contagempf_agrupador->no_agrupador->Visible) { // no_agrupador ?>
	<?php if ($contagempf_agrupador->SortUrl($contagempf_agrupador->no_agrupador) == "") { ?>
		<td><div id="elh_contagempf_agrupador_no_agrupador" class="contagempf_agrupador_no_agrupador"><div class="ewTableHeaderCaption"><?php echo $contagempf_agrupador->no_agrupador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_agrupador_no_agrupador" class="contagempf_agrupador_no_agrupador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_agrupador->no_agrupador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_agrupador->no_agrupador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_agrupador->no_agrupador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$contagempf_agrupador_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$contagempf_agrupador_grid->StartRec = 1;
$contagempf_agrupador_grid->StopRec = $contagempf_agrupador_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($contagempf_agrupador_grid->FormKeyCountName) && ($contagempf_agrupador->CurrentAction == "gridadd" || $contagempf_agrupador->CurrentAction == "gridedit" || $contagempf_agrupador->CurrentAction == "F")) {
		$contagempf_agrupador_grid->KeyCount = $objForm->GetValue($contagempf_agrupador_grid->FormKeyCountName);
		$contagempf_agrupador_grid->StopRec = $contagempf_agrupador_grid->StartRec + $contagempf_agrupador_grid->KeyCount - 1;
	}
}
$contagempf_agrupador_grid->RecCnt = $contagempf_agrupador_grid->StartRec - 1;
if ($contagempf_agrupador_grid->Recordset && !$contagempf_agrupador_grid->Recordset->EOF) {
	$contagempf_agrupador_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $contagempf_agrupador_grid->StartRec > 1)
		$contagempf_agrupador_grid->Recordset->Move($contagempf_agrupador_grid->StartRec - 1);
} elseif (!$contagempf_agrupador->AllowAddDeleteRow && $contagempf_agrupador_grid->StopRec == 0) {
	$contagempf_agrupador_grid->StopRec = $contagempf_agrupador->GridAddRowCount;
}

// Initialize aggregate
$contagempf_agrupador->RowType = EW_ROWTYPE_AGGREGATEINIT;
$contagempf_agrupador->ResetAttrs();
$contagempf_agrupador_grid->RenderRow();
if ($contagempf_agrupador->CurrentAction == "gridadd")
	$contagempf_agrupador_grid->RowIndex = 0;
if ($contagempf_agrupador->CurrentAction == "gridedit")
	$contagempf_agrupador_grid->RowIndex = 0;
while ($contagempf_agrupador_grid->RecCnt < $contagempf_agrupador_grid->StopRec) {
	$contagempf_agrupador_grid->RecCnt++;
	if (intval($contagempf_agrupador_grid->RecCnt) >= intval($contagempf_agrupador_grid->StartRec)) {
		$contagempf_agrupador_grid->RowCnt++;
		if ($contagempf_agrupador->CurrentAction == "gridadd" || $contagempf_agrupador->CurrentAction == "gridedit" || $contagempf_agrupador->CurrentAction == "F") {
			$contagempf_agrupador_grid->RowIndex++;
			$objForm->Index = $contagempf_agrupador_grid->RowIndex;
			if ($objForm->HasValue($contagempf_agrupador_grid->FormActionName))
				$contagempf_agrupador_grid->RowAction = strval($objForm->GetValue($contagempf_agrupador_grid->FormActionName));
			elseif ($contagempf_agrupador->CurrentAction == "gridadd")
				$contagempf_agrupador_grid->RowAction = "insert";
			else
				$contagempf_agrupador_grid->RowAction = "";
		}

		// Set up key count
		$contagempf_agrupador_grid->KeyCount = $contagempf_agrupador_grid->RowIndex;

		// Init row class and style
		$contagempf_agrupador->ResetAttrs();
		$contagempf_agrupador->CssClass = "";
		if ($contagempf_agrupador->CurrentAction == "gridadd") {
			if ($contagempf_agrupador->CurrentMode == "copy") {
				$contagempf_agrupador_grid->LoadRowValues($contagempf_agrupador_grid->Recordset); // Load row values
				$contagempf_agrupador_grid->SetRecordKey($contagempf_agrupador_grid->RowOldKey, $contagempf_agrupador_grid->Recordset); // Set old record key
			} else {
				$contagempf_agrupador_grid->LoadDefaultValues(); // Load default values
				$contagempf_agrupador_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$contagempf_agrupador_grid->LoadRowValues($contagempf_agrupador_grid->Recordset); // Load row values
		}
		$contagempf_agrupador->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($contagempf_agrupador->CurrentAction == "gridadd") // Grid add
			$contagempf_agrupador->RowType = EW_ROWTYPE_ADD; // Render add
		if ($contagempf_agrupador->CurrentAction == "gridadd" && $contagempf_agrupador->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$contagempf_agrupador_grid->RestoreCurrentRowFormValues($contagempf_agrupador_grid->RowIndex); // Restore form values
		if ($contagempf_agrupador->CurrentAction == "gridedit") { // Grid edit
			if ($contagempf_agrupador->EventCancelled) {
				$contagempf_agrupador_grid->RestoreCurrentRowFormValues($contagempf_agrupador_grid->RowIndex); // Restore form values
			}
			if ($contagempf_agrupador_grid->RowAction == "insert")
				$contagempf_agrupador->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$contagempf_agrupador->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($contagempf_agrupador->CurrentAction == "gridedit" && ($contagempf_agrupador->RowType == EW_ROWTYPE_EDIT || $contagempf_agrupador->RowType == EW_ROWTYPE_ADD) && $contagempf_agrupador->EventCancelled) // Update failed
			$contagempf_agrupador_grid->RestoreCurrentRowFormValues($contagempf_agrupador_grid->RowIndex); // Restore form values
		if ($contagempf_agrupador->RowType == EW_ROWTYPE_EDIT) // Edit row
			$contagempf_agrupador_grid->EditRowCnt++;
		if ($contagempf_agrupador->CurrentAction == "F") // Confirm row
			$contagempf_agrupador_grid->RestoreCurrentRowFormValues($contagempf_agrupador_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$contagempf_agrupador->RowAttrs = array_merge($contagempf_agrupador->RowAttrs, array('data-rowindex'=>$contagempf_agrupador_grid->RowCnt, 'id'=>'r' . $contagempf_agrupador_grid->RowCnt . '_contagempf_agrupador', 'data-rowtype'=>$contagempf_agrupador->RowType));

		// Render row
		$contagempf_agrupador_grid->RenderRow();

		// Render list options
		$contagempf_agrupador_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($contagempf_agrupador_grid->RowAction <> "delete" && $contagempf_agrupador_grid->RowAction <> "insertdelete" && !($contagempf_agrupador_grid->RowAction == "insert" && $contagempf_agrupador->CurrentAction == "F" && $contagempf_agrupador_grid->EmptyRow())) {
?>
	<tr<?php echo $contagempf_agrupador->RowAttributes() ?>>
<?php

// Render list options (body, left)
$contagempf_agrupador_grid->ListOptions->Render("body", "left", $contagempf_agrupador_grid->RowCnt);
?>
	<?php if ($contagempf_agrupador->no_agrupador->Visible) { // no_agrupador ?>
		<td<?php echo $contagempf_agrupador->no_agrupador->CellAttributes() ?>>
<?php if ($contagempf_agrupador->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_agrupador_grid->RowCnt ?>_contagempf_agrupador_no_agrupador" class="control-group contagempf_agrupador_no_agrupador">
<input type="text" data-field="x_no_agrupador" name="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" id="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" size="30" maxlength="100" placeholder="<?php echo $contagempf_agrupador->no_agrupador->PlaceHolder ?>" value="<?php echo $contagempf_agrupador->no_agrupador->EditValue ?>"<?php echo $contagempf_agrupador->no_agrupador->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_agrupador" name="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" id="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" value="<?php echo ew_HtmlEncode($contagempf_agrupador->no_agrupador->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_agrupador->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_agrupador_grid->RowCnt ?>_contagempf_agrupador_no_agrupador" class="control-group contagempf_agrupador_no_agrupador">
<input type="text" data-field="x_no_agrupador" name="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" id="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" size="30" maxlength="100" placeholder="<?php echo $contagempf_agrupador->no_agrupador->PlaceHolder ?>" value="<?php echo $contagempf_agrupador->no_agrupador->EditValue ?>"<?php echo $contagempf_agrupador->no_agrupador->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($contagempf_agrupador->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf_agrupador->no_agrupador->ViewAttributes() ?>>
<?php echo $contagempf_agrupador->no_agrupador->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_agrupador" name="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" id="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" value="<?php echo ew_HtmlEncode($contagempf_agrupador->no_agrupador->FormValue) ?>">
<input type="hidden" data-field="x_no_agrupador" name="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" id="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" value="<?php echo ew_HtmlEncode($contagempf_agrupador->no_agrupador->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_agrupador_grid->PageObjName . "_row_" . $contagempf_agrupador_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($contagempf_agrupador->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_contagem" name="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_contagem" id="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf_agrupador->nu_contagem->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_contagem" name="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_contagem" id="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf_agrupador->nu_contagem->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_agrupador->RowType == EW_ROWTYPE_EDIT || $contagempf_agrupador->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_contagem" name="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_contagem" id="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf_agrupador->nu_contagem->CurrentValue) ?>">
<?php } ?>
<?php if ($contagempf_agrupador->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_agrupador" name="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_agrupador" id="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_agrupador" value="<?php echo ew_HtmlEncode($contagempf_agrupador->nu_agrupador->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_agrupador" name="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_agrupador" id="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_agrupador" value="<?php echo ew_HtmlEncode($contagempf_agrupador->nu_agrupador->OldValue) ?>">
<?php } ?>
<?php if ($contagempf_agrupador->RowType == EW_ROWTYPE_EDIT || $contagempf_agrupador->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_agrupador" name="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_agrupador" id="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_nu_agrupador" value="<?php echo ew_HtmlEncode($contagempf_agrupador->nu_agrupador->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$contagempf_agrupador_grid->ListOptions->Render("body", "right", $contagempf_agrupador_grid->RowCnt);
?>
	</tr>
<?php if ($contagempf_agrupador->RowType == EW_ROWTYPE_ADD || $contagempf_agrupador->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcontagempf_agrupadorgrid.UpdateOpts(<?php echo $contagempf_agrupador_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($contagempf_agrupador->CurrentAction <> "gridadd" || $contagempf_agrupador->CurrentMode == "copy")
		if (!$contagempf_agrupador_grid->Recordset->EOF) $contagempf_agrupador_grid->Recordset->MoveNext();
}
?>
<?php
	if ($contagempf_agrupador->CurrentMode == "add" || $contagempf_agrupador->CurrentMode == "copy" || $contagempf_agrupador->CurrentMode == "edit") {
		$contagempf_agrupador_grid->RowIndex = '$rowindex$';
		$contagempf_agrupador_grid->LoadDefaultValues();

		// Set row properties
		$contagempf_agrupador->ResetAttrs();
		$contagempf_agrupador->RowAttrs = array_merge($contagempf_agrupador->RowAttrs, array('data-rowindex'=>$contagempf_agrupador_grid->RowIndex, 'id'=>'r0_contagempf_agrupador', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($contagempf_agrupador->RowAttrs["class"], "ewTemplate");
		$contagempf_agrupador->RowType = EW_ROWTYPE_ADD;

		// Render row
		$contagempf_agrupador_grid->RenderRow();

		// Render list options
		$contagempf_agrupador_grid->RenderListOptions();
		$contagempf_agrupador_grid->StartRowCnt = 0;
?>
	<tr<?php echo $contagempf_agrupador->RowAttributes() ?>>
<?php

// Render list options (body, left)
$contagempf_agrupador_grid->ListOptions->Render("body", "left", $contagempf_agrupador_grid->RowIndex);
?>
	<?php if ($contagempf_agrupador->no_agrupador->Visible) { // no_agrupador ?>
		<td>
<?php if ($contagempf_agrupador->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_agrupador" name="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" id="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" size="30" maxlength="100" placeholder="<?php echo $contagempf_agrupador->no_agrupador->PlaceHolder ?>" value="<?php echo $contagempf_agrupador->no_agrupador->EditValue ?>"<?php echo $contagempf_agrupador->no_agrupador->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf_agrupador->no_agrupador->ViewAttributes() ?>>
<?php echo $contagempf_agrupador->no_agrupador->ViewValue ?></span>
<input type="hidden" data-field="x_no_agrupador" name="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" id="x<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" value="<?php echo ew_HtmlEncode($contagempf_agrupador->no_agrupador->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_agrupador" name="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" id="o<?php echo $contagempf_agrupador_grid->RowIndex ?>_no_agrupador" value="<?php echo ew_HtmlEncode($contagempf_agrupador->no_agrupador->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$contagempf_agrupador_grid->ListOptions->Render("body", "right", $contagempf_agrupador_grid->RowCnt);
?>
<script type="text/javascript">
fcontagempf_agrupadorgrid.UpdateOpts(<?php echo $contagempf_agrupador_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($contagempf_agrupador->CurrentMode == "add" || $contagempf_agrupador->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $contagempf_agrupador_grid->FormKeyCountName ?>" id="<?php echo $contagempf_agrupador_grid->FormKeyCountName ?>" value="<?php echo $contagempf_agrupador_grid->KeyCount ?>">
<?php echo $contagempf_agrupador_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($contagempf_agrupador->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $contagempf_agrupador_grid->FormKeyCountName ?>" id="<?php echo $contagempf_agrupador_grid->FormKeyCountName ?>" value="<?php echo $contagempf_agrupador_grid->KeyCount ?>">
<?php echo $contagempf_agrupador_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($contagempf_agrupador->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcontagempf_agrupadorgrid">
</div>
<?php

// Close recordset
if ($contagempf_agrupador_grid->Recordset)
	$contagempf_agrupador_grid->Recordset->Close();
?>
<?php if ($contagempf_agrupador_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($contagempf_agrupador_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($contagempf_agrupador->Export == "") { ?>
<script type="text/javascript">
fcontagempf_agrupadorgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$contagempf_agrupador_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$contagempf_agrupador_grid->Page_Terminate();
?>
