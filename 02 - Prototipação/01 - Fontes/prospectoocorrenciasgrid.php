<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($prospectoocorrencias_grid)) $prospectoocorrencias_grid = new cprospectoocorrencias_grid();

// Page init
$prospectoocorrencias_grid->Page_Init();

// Page main
$prospectoocorrencias_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$prospectoocorrencias_grid->Page_Render();
?>
<?php if ($prospectoocorrencias->Export == "") { ?>
<script type="text/javascript">

// Page object
var prospectoocorrencias_grid = new ew_Page("prospectoocorrencias_grid");
prospectoocorrencias_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = prospectoocorrencias_grid.PageID; // For backward compatibility

// Form object
var fprospectoocorrenciasgrid = new ew_Form("fprospectoocorrenciasgrid");
fprospectoocorrenciasgrid.FormKeyCountName = '<?php echo $prospectoocorrencias_grid->FormKeyCountName ?>';

// Validate form
fprospectoocorrenciasgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_assuntoOcor");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospectoocorrencias->no_assuntoOcor->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dh_inclusao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($prospectoocorrencias->dh_inclusao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dh_inclusao");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($prospectoocorrencias->dh_inclusao->FldErrMsg()) ?>");

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
fprospectoocorrenciasgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_assuntoOcor", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dh_inclusao", false)) return false;
	return true;
}

// Form_CustomValidate event
fprospectoocorrenciasgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprospectoocorrenciasgrid.ValidateRequired = true;
<?php } else { ?>
fprospectoocorrenciasgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($prospectoocorrencias->getCurrentMasterTable() == "" && $prospectoocorrencias_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $prospectoocorrencias_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($prospectoocorrencias->CurrentAction == "gridadd") {
	if ($prospectoocorrencias->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$prospectoocorrencias_grid->TotalRecs = $prospectoocorrencias->SelectRecordCount();
			$prospectoocorrencias_grid->Recordset = $prospectoocorrencias_grid->LoadRecordset($prospectoocorrencias_grid->StartRec-1, $prospectoocorrencias_grid->DisplayRecs);
		} else {
			if ($prospectoocorrencias_grid->Recordset = $prospectoocorrencias_grid->LoadRecordset())
				$prospectoocorrencias_grid->TotalRecs = $prospectoocorrencias_grid->Recordset->RecordCount();
		}
		$prospectoocorrencias_grid->StartRec = 1;
		$prospectoocorrencias_grid->DisplayRecs = $prospectoocorrencias_grid->TotalRecs;
	} else {
		$prospectoocorrencias->CurrentFilter = "0=1";
		$prospectoocorrencias_grid->StartRec = 1;
		$prospectoocorrencias_grid->DisplayRecs = $prospectoocorrencias->GridAddRowCount;
	}
	$prospectoocorrencias_grid->TotalRecs = $prospectoocorrencias_grid->DisplayRecs;
	$prospectoocorrencias_grid->StopRec = $prospectoocorrencias_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$prospectoocorrencias_grid->TotalRecs = $prospectoocorrencias->SelectRecordCount();
	} else {
		if ($prospectoocorrencias_grid->Recordset = $prospectoocorrencias_grid->LoadRecordset())
			$prospectoocorrencias_grid->TotalRecs = $prospectoocorrencias_grid->Recordset->RecordCount();
	}
	$prospectoocorrencias_grid->StartRec = 1;
	$prospectoocorrencias_grid->DisplayRecs = $prospectoocorrencias_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$prospectoocorrencias_grid->Recordset = $prospectoocorrencias_grid->LoadRecordset($prospectoocorrencias_grid->StartRec-1, $prospectoocorrencias_grid->DisplayRecs);
}
$prospectoocorrencias_grid->RenderOtherOptions();
?>
<?php $prospectoocorrencias_grid->ShowPageHeader(); ?>
<?php
$prospectoocorrencias_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fprospectoocorrenciasgrid" class="ewForm form-horizontal">
<div id="gmp_prospectoocorrencias" class="ewGridMiddlePanel">
<table id="tbl_prospectoocorrenciasgrid" class="ewTable ewTableSeparate">
<?php echo $prospectoocorrencias->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$prospectoocorrencias_grid->RenderListOptions();

// Render list options (header, left)
$prospectoocorrencias_grid->ListOptions->Render("header", "left");
?>
<?php if ($prospectoocorrencias->nu_ocorrencia->Visible) { // nu_ocorrencia ?>
	<?php if ($prospectoocorrencias->SortUrl($prospectoocorrencias->nu_ocorrencia) == "") { ?>
		<td><div id="elh_prospectoocorrencias_nu_ocorrencia" class="prospectoocorrencias_nu_ocorrencia"><div class="ewTableHeaderCaption"><?php echo $prospectoocorrencias->nu_ocorrencia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospectoocorrencias_nu_ocorrencia" class="prospectoocorrencias_nu_ocorrencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospectoocorrencias->nu_ocorrencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospectoocorrencias->nu_ocorrencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospectoocorrencias->nu_ocorrencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospectoocorrencias->no_assuntoOcor->Visible) { // no_assuntoOcor ?>
	<?php if ($prospectoocorrencias->SortUrl($prospectoocorrencias->no_assuntoOcor) == "") { ?>
		<td><div id="elh_prospectoocorrencias_no_assuntoOcor" class="prospectoocorrencias_no_assuntoOcor"><div class="ewTableHeaderCaption"><?php echo $prospectoocorrencias->no_assuntoOcor->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospectoocorrencias_no_assuntoOcor" class="prospectoocorrencias_no_assuntoOcor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospectoocorrencias->no_assuntoOcor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospectoocorrencias->no_assuntoOcor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospectoocorrencias->no_assuntoOcor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospectoocorrencias->dh_inclusao->Visible) { // dh_inclusao ?>
	<?php if ($prospectoocorrencias->SortUrl($prospectoocorrencias->dh_inclusao) == "") { ?>
		<td><div id="elh_prospectoocorrencias_dh_inclusao" class="prospectoocorrencias_dh_inclusao"><div class="ewTableHeaderCaption"><?php echo $prospectoocorrencias->dh_inclusao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_prospectoocorrencias_dh_inclusao" class="prospectoocorrencias_dh_inclusao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospectoocorrencias->dh_inclusao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospectoocorrencias->dh_inclusao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospectoocorrencias->dh_inclusao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$prospectoocorrencias_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$prospectoocorrencias_grid->StartRec = 1;
$prospectoocorrencias_grid->StopRec = $prospectoocorrencias_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($prospectoocorrencias_grid->FormKeyCountName) && ($prospectoocorrencias->CurrentAction == "gridadd" || $prospectoocorrencias->CurrentAction == "gridedit" || $prospectoocorrencias->CurrentAction == "F")) {
		$prospectoocorrencias_grid->KeyCount = $objForm->GetValue($prospectoocorrencias_grid->FormKeyCountName);
		$prospectoocorrencias_grid->StopRec = $prospectoocorrencias_grid->StartRec + $prospectoocorrencias_grid->KeyCount - 1;
	}
}
$prospectoocorrencias_grid->RecCnt = $prospectoocorrencias_grid->StartRec - 1;
if ($prospectoocorrencias_grid->Recordset && !$prospectoocorrencias_grid->Recordset->EOF) {
	$prospectoocorrencias_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $prospectoocorrencias_grid->StartRec > 1)
		$prospectoocorrencias_grid->Recordset->Move($prospectoocorrencias_grid->StartRec - 1);
} elseif (!$prospectoocorrencias->AllowAddDeleteRow && $prospectoocorrencias_grid->StopRec == 0) {
	$prospectoocorrencias_grid->StopRec = $prospectoocorrencias->GridAddRowCount;
}

// Initialize aggregate
$prospectoocorrencias->RowType = EW_ROWTYPE_AGGREGATEINIT;
$prospectoocorrencias->ResetAttrs();
$prospectoocorrencias_grid->RenderRow();
if ($prospectoocorrencias->CurrentAction == "gridadd")
	$prospectoocorrencias_grid->RowIndex = 0;
if ($prospectoocorrencias->CurrentAction == "gridedit")
	$prospectoocorrencias_grid->RowIndex = 0;
while ($prospectoocorrencias_grid->RecCnt < $prospectoocorrencias_grid->StopRec) {
	$prospectoocorrencias_grid->RecCnt++;
	if (intval($prospectoocorrencias_grid->RecCnt) >= intval($prospectoocorrencias_grid->StartRec)) {
		$prospectoocorrencias_grid->RowCnt++;
		if ($prospectoocorrencias->CurrentAction == "gridadd" || $prospectoocorrencias->CurrentAction == "gridedit" || $prospectoocorrencias->CurrentAction == "F") {
			$prospectoocorrencias_grid->RowIndex++;
			$objForm->Index = $prospectoocorrencias_grid->RowIndex;
			if ($objForm->HasValue($prospectoocorrencias_grid->FormActionName))
				$prospectoocorrencias_grid->RowAction = strval($objForm->GetValue($prospectoocorrencias_grid->FormActionName));
			elseif ($prospectoocorrencias->CurrentAction == "gridadd")
				$prospectoocorrencias_grid->RowAction = "insert";
			else
				$prospectoocorrencias_grid->RowAction = "";
		}

		// Set up key count
		$prospectoocorrencias_grid->KeyCount = $prospectoocorrencias_grid->RowIndex;

		// Init row class and style
		$prospectoocorrencias->ResetAttrs();
		$prospectoocorrencias->CssClass = "";
		if ($prospectoocorrencias->CurrentAction == "gridadd") {
			if ($prospectoocorrencias->CurrentMode == "copy") {
				$prospectoocorrencias_grid->LoadRowValues($prospectoocorrencias_grid->Recordset); // Load row values
				$prospectoocorrencias_grid->SetRecordKey($prospectoocorrencias_grid->RowOldKey, $prospectoocorrencias_grid->Recordset); // Set old record key
			} else {
				$prospectoocorrencias_grid->LoadDefaultValues(); // Load default values
				$prospectoocorrencias_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$prospectoocorrencias_grid->LoadRowValues($prospectoocorrencias_grid->Recordset); // Load row values
		}
		$prospectoocorrencias->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($prospectoocorrencias->CurrentAction == "gridadd") // Grid add
			$prospectoocorrencias->RowType = EW_ROWTYPE_ADD; // Render add
		if ($prospectoocorrencias->CurrentAction == "gridadd" && $prospectoocorrencias->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$prospectoocorrencias_grid->RestoreCurrentRowFormValues($prospectoocorrencias_grid->RowIndex); // Restore form values
		if ($prospectoocorrencias->CurrentAction == "gridedit") { // Grid edit
			if ($prospectoocorrencias->EventCancelled) {
				$prospectoocorrencias_grid->RestoreCurrentRowFormValues($prospectoocorrencias_grid->RowIndex); // Restore form values
			}
			if ($prospectoocorrencias_grid->RowAction == "insert")
				$prospectoocorrencias->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$prospectoocorrencias->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($prospectoocorrencias->CurrentAction == "gridedit" && ($prospectoocorrencias->RowType == EW_ROWTYPE_EDIT || $prospectoocorrencias->RowType == EW_ROWTYPE_ADD) && $prospectoocorrencias->EventCancelled) // Update failed
			$prospectoocorrencias_grid->RestoreCurrentRowFormValues($prospectoocorrencias_grid->RowIndex); // Restore form values
		if ($prospectoocorrencias->RowType == EW_ROWTYPE_EDIT) // Edit row
			$prospectoocorrencias_grid->EditRowCnt++;
		if ($prospectoocorrencias->CurrentAction == "F") // Confirm row
			$prospectoocorrencias_grid->RestoreCurrentRowFormValues($prospectoocorrencias_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$prospectoocorrencias->RowAttrs = array_merge($prospectoocorrencias->RowAttrs, array('data-rowindex'=>$prospectoocorrencias_grid->RowCnt, 'id'=>'r' . $prospectoocorrencias_grid->RowCnt . '_prospectoocorrencias', 'data-rowtype'=>$prospectoocorrencias->RowType));

		// Render row
		$prospectoocorrencias_grid->RenderRow();

		// Render list options
		$prospectoocorrencias_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($prospectoocorrencias_grid->RowAction <> "delete" && $prospectoocorrencias_grid->RowAction <> "insertdelete" && !($prospectoocorrencias_grid->RowAction == "insert" && $prospectoocorrencias->CurrentAction == "F" && $prospectoocorrencias_grid->EmptyRow())) {
?>
	<tr<?php echo $prospectoocorrencias->RowAttributes() ?>>
<?php

// Render list options (body, left)
$prospectoocorrencias_grid->ListOptions->Render("body", "left", $prospectoocorrencias_grid->RowCnt);
?>
	<?php if ($prospectoocorrencias->nu_ocorrencia->Visible) { // nu_ocorrencia ?>
		<td<?php echo $prospectoocorrencias->nu_ocorrencia->CellAttributes() ?>>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_ocorrencia" name="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" id="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" value="<?php echo ew_HtmlEncode($prospectoocorrencias->nu_ocorrencia->OldValue) ?>">
<?php } ?>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospectoocorrencias_grid->RowCnt ?>_prospectoocorrencias_nu_ocorrencia" class="control-group prospectoocorrencias_nu_ocorrencia">
<span<?php echo $prospectoocorrencias->nu_ocorrencia->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->nu_ocorrencia->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_ocorrencia" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" value="<?php echo ew_HtmlEncode($prospectoocorrencias->nu_ocorrencia->CurrentValue) ?>">
<?php } ?>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospectoocorrencias->nu_ocorrencia->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->nu_ocorrencia->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_ocorrencia" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" value="<?php echo ew_HtmlEncode($prospectoocorrencias->nu_ocorrencia->FormValue) ?>">
<input type="hidden" data-field="x_nu_ocorrencia" name="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" id="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" value="<?php echo ew_HtmlEncode($prospectoocorrencias->nu_ocorrencia->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospectoocorrencias_grid->PageObjName . "_row_" . $prospectoocorrencias_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospectoocorrencias->no_assuntoOcor->Visible) { // no_assuntoOcor ?>
		<td<?php echo $prospectoocorrencias->no_assuntoOcor->CellAttributes() ?>>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $prospectoocorrencias_grid->RowCnt ?>_prospectoocorrencias_no_assuntoOcor" class="control-group prospectoocorrencias_no_assuntoOcor">
<input type="text" data-field="x_no_assuntoOcor" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" size="30" maxlength="75" placeholder="<?php echo $prospectoocorrencias->no_assuntoOcor->PlaceHolder ?>" value="<?php echo $prospectoocorrencias->no_assuntoOcor->EditValue ?>"<?php echo $prospectoocorrencias->no_assuntoOcor->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_assuntoOcor" name="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" id="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" value="<?php echo ew_HtmlEncode($prospectoocorrencias->no_assuntoOcor->OldValue) ?>">
<?php } ?>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospectoocorrencias_grid->RowCnt ?>_prospectoocorrencias_no_assuntoOcor" class="control-group prospectoocorrencias_no_assuntoOcor">
<input type="text" data-field="x_no_assuntoOcor" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" size="30" maxlength="75" placeholder="<?php echo $prospectoocorrencias->no_assuntoOcor->PlaceHolder ?>" value="<?php echo $prospectoocorrencias->no_assuntoOcor->EditValue ?>"<?php echo $prospectoocorrencias->no_assuntoOcor->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospectoocorrencias->no_assuntoOcor->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->no_assuntoOcor->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_assuntoOcor" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" value="<?php echo ew_HtmlEncode($prospectoocorrencias->no_assuntoOcor->FormValue) ?>">
<input type="hidden" data-field="x_no_assuntoOcor" name="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" id="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" value="<?php echo ew_HtmlEncode($prospectoocorrencias->no_assuntoOcor->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospectoocorrencias_grid->PageObjName . "_row_" . $prospectoocorrencias_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospectoocorrencias->dh_inclusao->Visible) { // dh_inclusao ?>
		<td<?php echo $prospectoocorrencias->dh_inclusao->CellAttributes() ?>>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $prospectoocorrencias_grid->RowCnt ?>_prospectoocorrencias_dh_inclusao" class="control-group prospectoocorrencias_dh_inclusao">
<input type="text" data-field="x_dh_inclusao" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" placeholder="<?php echo $prospectoocorrencias->dh_inclusao->PlaceHolder ?>" value="<?php echo $prospectoocorrencias->dh_inclusao->EditValue ?>"<?php echo $prospectoocorrencias->dh_inclusao->EditAttributes() ?>>
<?php if (!$prospectoocorrencias->dh_inclusao->ReadOnly && !$prospectoocorrencias->dh_inclusao->Disabled && @$prospectoocorrencias->dh_inclusao->EditAttrs["readonly"] == "" && @$prospectoocorrencias->dh_inclusao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" name="cal_x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fprospectoocorrenciasgrid", "x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao", "%d/%m/%Y %H:%M:%S");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($prospectoocorrencias->dh_inclusao->OldValue) ?>">
<?php } ?>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $prospectoocorrencias_grid->RowCnt ?>_prospectoocorrencias_dh_inclusao" class="control-group prospectoocorrencias_dh_inclusao">
<input type="text" data-field="x_dh_inclusao" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" placeholder="<?php echo $prospectoocorrencias->dh_inclusao->PlaceHolder ?>" value="<?php echo $prospectoocorrencias->dh_inclusao->EditValue ?>"<?php echo $prospectoocorrencias->dh_inclusao->EditAttributes() ?>>
<?php if (!$prospectoocorrencias->dh_inclusao->ReadOnly && !$prospectoocorrencias->dh_inclusao->Disabled && @$prospectoocorrencias->dh_inclusao->EditAttrs["readonly"] == "" && @$prospectoocorrencias->dh_inclusao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" name="cal_x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fprospectoocorrenciasgrid", "x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao", "%d/%m/%Y %H:%M:%S");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $prospectoocorrencias->dh_inclusao->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->dh_inclusao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dh_inclusao" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($prospectoocorrencias->dh_inclusao->FormValue) ?>">
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($prospectoocorrencias->dh_inclusao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $prospectoocorrencias_grid->PageObjName . "_row_" . $prospectoocorrencias_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$prospectoocorrencias_grid->ListOptions->Render("body", "right", $prospectoocorrencias_grid->RowCnt);
?>
	</tr>
<?php if ($prospectoocorrencias->RowType == EW_ROWTYPE_ADD || $prospectoocorrencias->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fprospectoocorrenciasgrid.UpdateOpts(<?php echo $prospectoocorrencias_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($prospectoocorrencias->CurrentAction <> "gridadd" || $prospectoocorrencias->CurrentMode == "copy")
		if (!$prospectoocorrencias_grid->Recordset->EOF) $prospectoocorrencias_grid->Recordset->MoveNext();
}
?>
<?php
	if ($prospectoocorrencias->CurrentMode == "add" || $prospectoocorrencias->CurrentMode == "copy" || $prospectoocorrencias->CurrentMode == "edit") {
		$prospectoocorrencias_grid->RowIndex = '$rowindex$';
		$prospectoocorrencias_grid->LoadDefaultValues();

		// Set row properties
		$prospectoocorrencias->ResetAttrs();
		$prospectoocorrencias->RowAttrs = array_merge($prospectoocorrencias->RowAttrs, array('data-rowindex'=>$prospectoocorrencias_grid->RowIndex, 'id'=>'r0_prospectoocorrencias', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($prospectoocorrencias->RowAttrs["class"], "ewTemplate");
		$prospectoocorrencias->RowType = EW_ROWTYPE_ADD;

		// Render row
		$prospectoocorrencias_grid->RenderRow();

		// Render list options
		$prospectoocorrencias_grid->RenderListOptions();
		$prospectoocorrencias_grid->StartRowCnt = 0;
?>
	<tr<?php echo $prospectoocorrencias->RowAttributes() ?>>
<?php

// Render list options (body, left)
$prospectoocorrencias_grid->ListOptions->Render("body", "left", $prospectoocorrencias_grid->RowIndex);
?>
	<?php if ($prospectoocorrencias->nu_ocorrencia->Visible) { // nu_ocorrencia ?>
		<td>
<?php if ($prospectoocorrencias->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $prospectoocorrencias->nu_ocorrencia->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->nu_ocorrencia->ViewValue ?></span>
<input type="hidden" data-field="x_nu_ocorrencia" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" value="<?php echo ew_HtmlEncode($prospectoocorrencias->nu_ocorrencia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_ocorrencia" name="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" id="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_nu_ocorrencia" value="<?php echo ew_HtmlEncode($prospectoocorrencias->nu_ocorrencia->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($prospectoocorrencias->no_assuntoOcor->Visible) { // no_assuntoOcor ?>
		<td>
<?php if ($prospectoocorrencias->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_assuntoOcor" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" size="30" maxlength="75" placeholder="<?php echo $prospectoocorrencias->no_assuntoOcor->PlaceHolder ?>" value="<?php echo $prospectoocorrencias->no_assuntoOcor->EditValue ?>"<?php echo $prospectoocorrencias->no_assuntoOcor->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $prospectoocorrencias->no_assuntoOcor->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->no_assuntoOcor->ViewValue ?></span>
<input type="hidden" data-field="x_no_assuntoOcor" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" value="<?php echo ew_HtmlEncode($prospectoocorrencias->no_assuntoOcor->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_assuntoOcor" name="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" id="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_no_assuntoOcor" value="<?php echo ew_HtmlEncode($prospectoocorrencias->no_assuntoOcor->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($prospectoocorrencias->dh_inclusao->Visible) { // dh_inclusao ?>
		<td>
<?php if ($prospectoocorrencias->CurrentAction <> "F") { ?>
<input type="text" data-field="x_dh_inclusao" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" placeholder="<?php echo $prospectoocorrencias->dh_inclusao->PlaceHolder ?>" value="<?php echo $prospectoocorrencias->dh_inclusao->EditValue ?>"<?php echo $prospectoocorrencias->dh_inclusao->EditAttributes() ?>>
<?php if (!$prospectoocorrencias->dh_inclusao->ReadOnly && !$prospectoocorrencias->dh_inclusao->Disabled && @$prospectoocorrencias->dh_inclusao->EditAttrs["readonly"] == "" && @$prospectoocorrencias->dh_inclusao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" name="cal_x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fprospectoocorrenciasgrid", "x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao", "%d/%m/%Y %H:%M:%S");
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $prospectoocorrencias->dh_inclusao->ViewAttributes() ?>>
<?php echo $prospectoocorrencias->dh_inclusao->ViewValue ?></span>
<input type="hidden" data-field="x_dh_inclusao" name="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($prospectoocorrencias->dh_inclusao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $prospectoocorrencias_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($prospectoocorrencias->dh_inclusao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$prospectoocorrencias_grid->ListOptions->Render("body", "right", $prospectoocorrencias_grid->RowCnt);
?>
<script type="text/javascript">
fprospectoocorrenciasgrid.UpdateOpts(<?php echo $prospectoocorrencias_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($prospectoocorrencias->CurrentMode == "add" || $prospectoocorrencias->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $prospectoocorrencias_grid->FormKeyCountName ?>" id="<?php echo $prospectoocorrencias_grid->FormKeyCountName ?>" value="<?php echo $prospectoocorrencias_grid->KeyCount ?>">
<?php echo $prospectoocorrencias_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($prospectoocorrencias->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $prospectoocorrencias_grid->FormKeyCountName ?>" id="<?php echo $prospectoocorrencias_grid->FormKeyCountName ?>" value="<?php echo $prospectoocorrencias_grid->KeyCount ?>">
<?php echo $prospectoocorrencias_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($prospectoocorrencias->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fprospectoocorrenciasgrid">
</div>
<?php

// Close recordset
if ($prospectoocorrencias_grid->Recordset)
	$prospectoocorrencias_grid->Recordset->Close();
?>
<?php if ($prospectoocorrencias_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($prospectoocorrencias_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($prospectoocorrencias->Export == "") { ?>
<script type="text/javascript">
fprospectoocorrenciasgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$prospectoocorrencias_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$prospectoocorrencias_grid->Page_Terminate();
?>
