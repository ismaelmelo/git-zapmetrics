<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($indicadorversao_grid)) $indicadorversao_grid = new cindicadorversao_grid();

// Page init
$indicadorversao_grid->Page_Init();

// Page main
$indicadorversao_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$indicadorversao_grid->Page_Render();
?>
<?php if ($indicadorversao->Export == "") { ?>
<script type="text/javascript">

// Page object
var indicadorversao_grid = new ew_Page("indicadorversao_grid");
indicadorversao_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = indicadorversao_grid.PageID; // For backward compatibility

// Form object
var findicadorversaogrid = new ew_Form("findicadorversaogrid");
findicadorversaogrid.FormKeyCountName = '<?php echo $indicadorversao_grid->FormKeyCountName ?>';

// Validate form
findicadorversaogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_indicador");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorversao->nu_indicador->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorversao->nu_versao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($indicadorversao->nu_versao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_periodicidadeGeracao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorversao->ic_periodicidadeGeracao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_reponsavelColetaCtrl");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($indicadorversao->ic_reponsavelColetaCtrl->FldCaption()) ?>");

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
findicadorversaogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_indicador", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_versao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_periodicidadeGeracao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_reponsavelColetaCtrl", false)) return false;
	return true;
}

// Form_CustomValidate event
findicadorversaogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
findicadorversaogrid.ValidateRequired = true;
<?php } else { ?>
findicadorversaogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
findicadorversaogrid.Lists["x_nu_indicador"] = {"LinkField":"x_nu_indicador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_indicador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($indicadorversao->getCurrentMasterTable() == "" && $indicadorversao_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $indicadorversao_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($indicadorversao->CurrentAction == "gridadd") {
	if ($indicadorversao->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$indicadorversao_grid->TotalRecs = $indicadorversao->SelectRecordCount();
			$indicadorversao_grid->Recordset = $indicadorversao_grid->LoadRecordset($indicadorversao_grid->StartRec-1, $indicadorversao_grid->DisplayRecs);
		} else {
			if ($indicadorversao_grid->Recordset = $indicadorversao_grid->LoadRecordset())
				$indicadorversao_grid->TotalRecs = $indicadorversao_grid->Recordset->RecordCount();
		}
		$indicadorversao_grid->StartRec = 1;
		$indicadorversao_grid->DisplayRecs = $indicadorversao_grid->TotalRecs;
	} else {
		$indicadorversao->CurrentFilter = "0=1";
		$indicadorversao_grid->StartRec = 1;
		$indicadorversao_grid->DisplayRecs = $indicadorversao->GridAddRowCount;
	}
	$indicadorversao_grid->TotalRecs = $indicadorversao_grid->DisplayRecs;
	$indicadorversao_grid->StopRec = $indicadorversao_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$indicadorversao_grid->TotalRecs = $indicadorversao->SelectRecordCount();
	} else {
		if ($indicadorversao_grid->Recordset = $indicadorversao_grid->LoadRecordset())
			$indicadorversao_grid->TotalRecs = $indicadorversao_grid->Recordset->RecordCount();
	}
	$indicadorversao_grid->StartRec = 1;
	$indicadorversao_grid->DisplayRecs = $indicadorversao_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$indicadorversao_grid->Recordset = $indicadorversao_grid->LoadRecordset($indicadorversao_grid->StartRec-1, $indicadorversao_grid->DisplayRecs);
}
$indicadorversao_grid->RenderOtherOptions();
?>
<?php $indicadorversao_grid->ShowPageHeader(); ?>
<?php
$indicadorversao_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="findicadorversaogrid" class="ewForm form-horizontal">
<div id="gmp_indicadorversao" class="ewGridMiddlePanel">
<table id="tbl_indicadorversaogrid" class="ewTable ewTableSeparate">
<?php echo $indicadorversao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$indicadorversao_grid->RenderListOptions();

// Render list options (header, left)
$indicadorversao_grid->ListOptions->Render("header", "left");
?>
<?php if ($indicadorversao->nu_indicador->Visible) { // nu_indicador ?>
	<?php if ($indicadorversao->SortUrl($indicadorversao->nu_indicador) == "") { ?>
		<td><div id="elh_indicadorversao_nu_indicador" class="indicadorversao_nu_indicador"><div class="ewTableHeaderCaption"><?php echo $indicadorversao->nu_indicador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicadorversao_nu_indicador" class="indicadorversao_nu_indicador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorversao->nu_indicador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicadorversao->nu_indicador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorversao->nu_indicador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicadorversao->nu_versao->Visible) { // nu_versao ?>
	<?php if ($indicadorversao->SortUrl($indicadorversao->nu_versao) == "") { ?>
		<td><div id="elh_indicadorversao_nu_versao" class="indicadorversao_nu_versao"><div class="ewTableHeaderCaption"><?php echo $indicadorversao->nu_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicadorversao_nu_versao" class="indicadorversao_nu_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorversao->nu_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicadorversao->nu_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorversao->nu_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicadorversao->ic_periodicidadeGeracao->Visible) { // ic_periodicidadeGeracao ?>
	<?php if ($indicadorversao->SortUrl($indicadorversao->ic_periodicidadeGeracao) == "") { ?>
		<td><div id="elh_indicadorversao_ic_periodicidadeGeracao" class="indicadorversao_ic_periodicidadeGeracao"><div class="ewTableHeaderCaption"><?php echo $indicadorversao->ic_periodicidadeGeracao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicadorversao_ic_periodicidadeGeracao" class="indicadorversao_ic_periodicidadeGeracao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorversao->ic_periodicidadeGeracao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicadorversao->ic_periodicidadeGeracao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorversao->ic_periodicidadeGeracao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicadorversao->ic_reponsavelColetaCtrl->Visible) { // ic_reponsavelColetaCtrl ?>
	<?php if ($indicadorversao->SortUrl($indicadorversao->ic_reponsavelColetaCtrl) == "") { ?>
		<td><div id="elh_indicadorversao_ic_reponsavelColetaCtrl" class="indicadorversao_ic_reponsavelColetaCtrl"><div class="ewTableHeaderCaption"><?php echo $indicadorversao->ic_reponsavelColetaCtrl->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicadorversao_ic_reponsavelColetaCtrl" class="indicadorversao_ic_reponsavelColetaCtrl">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorversao->ic_reponsavelColetaCtrl->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicadorversao->ic_reponsavelColetaCtrl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorversao->ic_reponsavelColetaCtrl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicadorversao->dh_versao->Visible) { // dh_versao ?>
	<?php if ($indicadorversao->SortUrl($indicadorversao->dh_versao) == "") { ?>
		<td><div id="elh_indicadorversao_dh_versao" class="indicadorversao_dh_versao"><div class="ewTableHeaderCaption"><?php echo $indicadorversao->dh_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_indicadorversao_dh_versao" class="indicadorversao_dh_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorversao->dh_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicadorversao->dh_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorversao->dh_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$indicadorversao_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$indicadorversao_grid->StartRec = 1;
$indicadorversao_grid->StopRec = $indicadorversao_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($indicadorversao_grid->FormKeyCountName) && ($indicadorversao->CurrentAction == "gridadd" || $indicadorversao->CurrentAction == "gridedit" || $indicadorversao->CurrentAction == "F")) {
		$indicadorversao_grid->KeyCount = $objForm->GetValue($indicadorversao_grid->FormKeyCountName);
		$indicadorversao_grid->StopRec = $indicadorversao_grid->StartRec + $indicadorversao_grid->KeyCount - 1;
	}
}
$indicadorversao_grid->RecCnt = $indicadorversao_grid->StartRec - 1;
if ($indicadorversao_grid->Recordset && !$indicadorversao_grid->Recordset->EOF) {
	$indicadorversao_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $indicadorversao_grid->StartRec > 1)
		$indicadorversao_grid->Recordset->Move($indicadorversao_grid->StartRec - 1);
} elseif (!$indicadorversao->AllowAddDeleteRow && $indicadorversao_grid->StopRec == 0) {
	$indicadorversao_grid->StopRec = $indicadorversao->GridAddRowCount;
}

// Initialize aggregate
$indicadorversao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$indicadorversao->ResetAttrs();
$indicadorversao_grid->RenderRow();
if ($indicadorversao->CurrentAction == "gridadd")
	$indicadorversao_grid->RowIndex = 0;
if ($indicadorversao->CurrentAction == "gridedit")
	$indicadorversao_grid->RowIndex = 0;
while ($indicadorversao_grid->RecCnt < $indicadorversao_grid->StopRec) {
	$indicadorversao_grid->RecCnt++;
	if (intval($indicadorversao_grid->RecCnt) >= intval($indicadorversao_grid->StartRec)) {
		$indicadorversao_grid->RowCnt++;
		if ($indicadorversao->CurrentAction == "gridadd" || $indicadorversao->CurrentAction == "gridedit" || $indicadorversao->CurrentAction == "F") {
			$indicadorversao_grid->RowIndex++;
			$objForm->Index = $indicadorversao_grid->RowIndex;
			if ($objForm->HasValue($indicadorversao_grid->FormActionName))
				$indicadorversao_grid->RowAction = strval($objForm->GetValue($indicadorversao_grid->FormActionName));
			elseif ($indicadorversao->CurrentAction == "gridadd")
				$indicadorversao_grid->RowAction = "insert";
			else
				$indicadorversao_grid->RowAction = "";
		}

		// Set up key count
		$indicadorversao_grid->KeyCount = $indicadorversao_grid->RowIndex;

		// Init row class and style
		$indicadorversao->ResetAttrs();
		$indicadorversao->CssClass = "";
		if ($indicadorversao->CurrentAction == "gridadd") {
			if ($indicadorversao->CurrentMode == "copy") {
				$indicadorversao_grid->LoadRowValues($indicadorversao_grid->Recordset); // Load row values
				$indicadorversao_grid->SetRecordKey($indicadorversao_grid->RowOldKey, $indicadorversao_grid->Recordset); // Set old record key
			} else {
				$indicadorversao_grid->LoadDefaultValues(); // Load default values
				$indicadorversao_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$indicadorversao_grid->LoadRowValues($indicadorversao_grid->Recordset); // Load row values
		}
		$indicadorversao->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($indicadorversao->CurrentAction == "gridadd") // Grid add
			$indicadorversao->RowType = EW_ROWTYPE_ADD; // Render add
		if ($indicadorversao->CurrentAction == "gridadd" && $indicadorversao->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$indicadorversao_grid->RestoreCurrentRowFormValues($indicadorversao_grid->RowIndex); // Restore form values
		if ($indicadorversao->CurrentAction == "gridedit") { // Grid edit
			if ($indicadorversao->EventCancelled) {
				$indicadorversao_grid->RestoreCurrentRowFormValues($indicadorversao_grid->RowIndex); // Restore form values
			}
			if ($indicadorversao_grid->RowAction == "insert")
				$indicadorversao->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$indicadorversao->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($indicadorversao->CurrentAction == "gridedit" && ($indicadorversao->RowType == EW_ROWTYPE_EDIT || $indicadorversao->RowType == EW_ROWTYPE_ADD) && $indicadorversao->EventCancelled) // Update failed
			$indicadorversao_grid->RestoreCurrentRowFormValues($indicadorversao_grid->RowIndex); // Restore form values
		if ($indicadorversao->RowType == EW_ROWTYPE_EDIT) // Edit row
			$indicadorversao_grid->EditRowCnt++;
		if ($indicadorversao->CurrentAction == "F") // Confirm row
			$indicadorversao_grid->RestoreCurrentRowFormValues($indicadorversao_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$indicadorversao->RowAttrs = array_merge($indicadorversao->RowAttrs, array('data-rowindex'=>$indicadorversao_grid->RowCnt, 'id'=>'r' . $indicadorversao_grid->RowCnt . '_indicadorversao', 'data-rowtype'=>$indicadorversao->RowType));

		// Render row
		$indicadorversao_grid->RenderRow();

		// Render list options
		$indicadorversao_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($indicadorversao_grid->RowAction <> "delete" && $indicadorversao_grid->RowAction <> "insertdelete" && !($indicadorversao_grid->RowAction == "insert" && $indicadorversao->CurrentAction == "F" && $indicadorversao_grid->EmptyRow())) {
?>
	<tr<?php echo $indicadorversao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$indicadorversao_grid->ListOptions->Render("body", "left", $indicadorversao_grid->RowCnt);
?>
	<?php if ($indicadorversao->nu_indicador->Visible) { // nu_indicador ?>
		<td<?php echo $indicadorversao->nu_indicador->CellAttributes() ?>>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($indicadorversao->nu_indicador->getSessionValue() <> "") { ?>
<span<?php echo $indicadorversao->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_indicador->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicadorversao->nu_indicador->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_indicador" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador"<?php echo $indicadorversao->nu_indicador->EditAttributes() ?>>
<?php
if (is_array($indicadorversao->nu_indicador->EditValue)) {
	$arwrk = $indicadorversao->nu_indicador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->nu_indicador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicadorversao->nu_indicador->OldValue = "";
?>
</select>
<script type="text/javascript">
findicadorversaogrid.Lists["x_nu_indicador"].Options = <?php echo (is_array($indicadorversao->nu_indicador->EditValue)) ? ew_ArrayToJson($indicadorversao->nu_indicador->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_nu_indicador" name="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" id="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicadorversao->nu_indicador->OldValue) ?>">
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $indicadorversao->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_indicador->EditValue ?></span>
<input type="hidden" data-field="x_nu_indicador" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicadorversao->nu_indicador->CurrentValue) ?>">
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicadorversao->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_indicador->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_indicador" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicadorversao->nu_indicador->FormValue) ?>">
<input type="hidden" data-field="x_nu_indicador" name="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" id="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicadorversao->nu_indicador->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicadorversao_grid->PageObjName . "_row_" . $indicadorversao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicadorversao->nu_versao->Visible) { // nu_versao ?>
		<td<?php echo $indicadorversao->nu_versao->CellAttributes() ?>>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($indicadorversao->nu_versao->getSessionValue() <> "") { ?>
<span<?php echo $indicadorversao->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_versao->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($indicadorversao->nu_versao->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_nu_versao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" size="30" placeholder="<?php echo $indicadorversao->nu_versao->PlaceHolder ?>" value="<?php echo $indicadorversao->nu_versao->EditValue ?>"<?php echo $indicadorversao->nu_versao->EditAttributes() ?>>
<?php } ?>
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" id="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($indicadorversao->nu_versao->OldValue) ?>">
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $indicadorversao->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_versao->EditValue ?></span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($indicadorversao->nu_versao->CurrentValue) ?>">
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicadorversao->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_versao->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($indicadorversao->nu_versao->FormValue) ?>">
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" id="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($indicadorversao->nu_versao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicadorversao_grid->PageObjName . "_row_" . $indicadorversao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicadorversao->ic_periodicidadeGeracao->Visible) { // ic_periodicidadeGeracao ?>
		<td<?php echo $indicadorversao->ic_periodicidadeGeracao->CellAttributes() ?>>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $indicadorversao_grid->RowCnt ?>_indicadorversao_ic_periodicidadeGeracao" class="control-group indicadorversao_ic_periodicidadeGeracao">
<select data-field="x_ic_periodicidadeGeracao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao"<?php echo $indicadorversao->ic_periodicidadeGeracao->EditAttributes() ?>>
<?php
if (is_array($indicadorversao->ic_periodicidadeGeracao->EditValue)) {
	$arwrk = $indicadorversao->ic_periodicidadeGeracao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->ic_periodicidadeGeracao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicadorversao->ic_periodicidadeGeracao->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_ic_periodicidadeGeracao" name="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" id="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" value="<?php echo ew_HtmlEncode($indicadorversao->ic_periodicidadeGeracao->OldValue) ?>">
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $indicadorversao_grid->RowCnt ?>_indicadorversao_ic_periodicidadeGeracao" class="control-group indicadorversao_ic_periodicidadeGeracao">
<select data-field="x_ic_periodicidadeGeracao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao"<?php echo $indicadorversao->ic_periodicidadeGeracao->EditAttributes() ?>>
<?php
if (is_array($indicadorversao->ic_periodicidadeGeracao->EditValue)) {
	$arwrk = $indicadorversao->ic_periodicidadeGeracao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->ic_periodicidadeGeracao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicadorversao->ic_periodicidadeGeracao->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicadorversao->ic_periodicidadeGeracao->ViewAttributes() ?>>
<?php echo $indicadorversao->ic_periodicidadeGeracao->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_periodicidadeGeracao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" value="<?php echo ew_HtmlEncode($indicadorversao->ic_periodicidadeGeracao->FormValue) ?>">
<input type="hidden" data-field="x_ic_periodicidadeGeracao" name="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" id="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" value="<?php echo ew_HtmlEncode($indicadorversao->ic_periodicidadeGeracao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicadorversao_grid->PageObjName . "_row_" . $indicadorversao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicadorversao->ic_reponsavelColetaCtrl->Visible) { // ic_reponsavelColetaCtrl ?>
		<td<?php echo $indicadorversao->ic_reponsavelColetaCtrl->CellAttributes() ?>>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $indicadorversao_grid->RowCnt ?>_indicadorversao_ic_reponsavelColetaCtrl" class="control-group indicadorversao_ic_reponsavelColetaCtrl">
<div id="tp_x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" value="{value}"<?php echo $indicadorversao->ic_reponsavelColetaCtrl->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $indicadorversao->ic_reponsavelColetaCtrl->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->ic_reponsavelColetaCtrl->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_reponsavelColetaCtrl" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $indicadorversao->ic_reponsavelColetaCtrl->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $indicadorversao->ic_reponsavelColetaCtrl->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_reponsavelColetaCtrl" name="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" value="<?php echo ew_HtmlEncode($indicadorversao->ic_reponsavelColetaCtrl->OldValue) ?>">
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $indicadorversao_grid->RowCnt ?>_indicadorversao_ic_reponsavelColetaCtrl" class="control-group indicadorversao_ic_reponsavelColetaCtrl">
<div id="tp_x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" value="{value}"<?php echo $indicadorversao->ic_reponsavelColetaCtrl->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $indicadorversao->ic_reponsavelColetaCtrl->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->ic_reponsavelColetaCtrl->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_reponsavelColetaCtrl" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $indicadorversao->ic_reponsavelColetaCtrl->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $indicadorversao->ic_reponsavelColetaCtrl->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicadorversao->ic_reponsavelColetaCtrl->ViewAttributes() ?>>
<?php echo $indicadorversao->ic_reponsavelColetaCtrl->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_reponsavelColetaCtrl" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" value="<?php echo ew_HtmlEncode($indicadorversao->ic_reponsavelColetaCtrl->FormValue) ?>">
<input type="hidden" data-field="x_ic_reponsavelColetaCtrl" name="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" value="<?php echo ew_HtmlEncode($indicadorversao->ic_reponsavelColetaCtrl->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicadorversao_grid->PageObjName . "_row_" . $indicadorversao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicadorversao->dh_versao->Visible) { // dh_versao ?>
		<td<?php echo $indicadorversao->dh_versao->CellAttributes() ?>>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_dh_versao" name="o<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" id="o<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" value="<?php echo ew_HtmlEncode($indicadorversao->dh_versao->OldValue) ?>">
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $indicadorversao->dh_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->dh_versao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dh_versao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" value="<?php echo ew_HtmlEncode($indicadorversao->dh_versao->FormValue) ?>">
<input type="hidden" data-field="x_dh_versao" name="o<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" id="o<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" value="<?php echo ew_HtmlEncode($indicadorversao->dh_versao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $indicadorversao_grid->PageObjName . "_row_" . $indicadorversao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$indicadorversao_grid->ListOptions->Render("body", "right", $indicadorversao_grid->RowCnt);
?>
	</tr>
<?php if ($indicadorversao->RowType == EW_ROWTYPE_ADD || $indicadorversao->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
findicadorversaogrid.UpdateOpts(<?php echo $indicadorversao_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($indicadorversao->CurrentAction <> "gridadd" || $indicadorversao->CurrentMode == "copy")
		if (!$indicadorversao_grid->Recordset->EOF) $indicadorversao_grid->Recordset->MoveNext();
}
?>
<?php
	if ($indicadorversao->CurrentMode == "add" || $indicadorversao->CurrentMode == "copy" || $indicadorversao->CurrentMode == "edit") {
		$indicadorversao_grid->RowIndex = '$rowindex$';
		$indicadorversao_grid->LoadDefaultValues();

		// Set row properties
		$indicadorversao->ResetAttrs();
		$indicadorversao->RowAttrs = array_merge($indicadorversao->RowAttrs, array('data-rowindex'=>$indicadorversao_grid->RowIndex, 'id'=>'r0_indicadorversao', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($indicadorversao->RowAttrs["class"], "ewTemplate");
		$indicadorversao->RowType = EW_ROWTYPE_ADD;

		// Render row
		$indicadorversao_grid->RenderRow();

		// Render list options
		$indicadorversao_grid->RenderListOptions();
		$indicadorversao_grid->StartRowCnt = 0;
?>
	<tr<?php echo $indicadorversao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$indicadorversao_grid->ListOptions->Render("body", "left", $indicadorversao_grid->RowIndex);
?>
	<?php if ($indicadorversao->nu_indicador->Visible) { // nu_indicador ?>
		<td>
<?php if ($indicadorversao->CurrentAction <> "F") { ?>
<?php if ($indicadorversao->nu_indicador->getSessionValue() <> "") { ?>
<span<?php echo $indicadorversao->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_indicador->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicadorversao->nu_indicador->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_indicador" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador"<?php echo $indicadorversao->nu_indicador->EditAttributes() ?>>
<?php
if (is_array($indicadorversao->nu_indicador->EditValue)) {
	$arwrk = $indicadorversao->nu_indicador->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->nu_indicador->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicadorversao->nu_indicador->OldValue = "";
?>
</select>
<script type="text/javascript">
findicadorversaogrid.Lists["x_nu_indicador"].Options = <?php echo (is_array($indicadorversao->nu_indicador->EditValue)) ? ew_ArrayToJson($indicadorversao->nu_indicador->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $indicadorversao->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_indicador->ViewValue ?></span>
<input type="hidden" data-field="x_nu_indicador" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicadorversao->nu_indicador->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_indicador" name="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" id="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_indicador" value="<?php echo ew_HtmlEncode($indicadorversao->nu_indicador->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($indicadorversao->nu_versao->Visible) { // nu_versao ?>
		<td>
<?php if ($indicadorversao->CurrentAction <> "F") { ?>
<?php if ($indicadorversao->nu_versao->getSessionValue() <> "") { ?>
<span<?php echo $indicadorversao->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_versao->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($indicadorversao->nu_versao->CurrentValue) ?>">
<?php } else { ?>
<input type="text" data-field="x_nu_versao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" size="30" placeholder="<?php echo $indicadorversao->nu_versao->PlaceHolder ?>" value="<?php echo $indicadorversao->nu_versao->EditValue ?>"<?php echo $indicadorversao->nu_versao->EditAttributes() ?>>
<?php } ?>
<?php } else { ?>
<span<?php echo $indicadorversao->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_versao->ViewValue ?></span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($indicadorversao->nu_versao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" id="o<?php echo $indicadorversao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($indicadorversao->nu_versao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($indicadorversao->ic_periodicidadeGeracao->Visible) { // ic_periodicidadeGeracao ?>
		<td>
<?php if ($indicadorversao->CurrentAction <> "F") { ?>
<select data-field="x_ic_periodicidadeGeracao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao"<?php echo $indicadorversao->ic_periodicidadeGeracao->EditAttributes() ?>>
<?php
if (is_array($indicadorversao->ic_periodicidadeGeracao->EditValue)) {
	$arwrk = $indicadorversao->ic_periodicidadeGeracao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->ic_periodicidadeGeracao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $indicadorversao->ic_periodicidadeGeracao->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $indicadorversao->ic_periodicidadeGeracao->ViewAttributes() ?>>
<?php echo $indicadorversao->ic_periodicidadeGeracao->ViewValue ?></span>
<input type="hidden" data-field="x_ic_periodicidadeGeracao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" value="<?php echo ew_HtmlEncode($indicadorversao->ic_periodicidadeGeracao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_periodicidadeGeracao" name="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" id="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_periodicidadeGeracao" value="<?php echo ew_HtmlEncode($indicadorversao->ic_periodicidadeGeracao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($indicadorversao->ic_reponsavelColetaCtrl->Visible) { // ic_reponsavelColetaCtrl ?>
		<td>
<?php if ($indicadorversao->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" value="{value}"<?php echo $indicadorversao->ic_reponsavelColetaCtrl->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $indicadorversao->ic_reponsavelColetaCtrl->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($indicadorversao->ic_reponsavelColetaCtrl->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_reponsavelColetaCtrl" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $indicadorversao->ic_reponsavelColetaCtrl->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $indicadorversao->ic_reponsavelColetaCtrl->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $indicadorversao->ic_reponsavelColetaCtrl->ViewAttributes() ?>>
<?php echo $indicadorversao->ic_reponsavelColetaCtrl->ViewValue ?></span>
<input type="hidden" data-field="x_ic_reponsavelColetaCtrl" name="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="x<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" value="<?php echo ew_HtmlEncode($indicadorversao->ic_reponsavelColetaCtrl->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_reponsavelColetaCtrl" name="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" id="o<?php echo $indicadorversao_grid->RowIndex ?>_ic_reponsavelColetaCtrl" value="<?php echo ew_HtmlEncode($indicadorversao->ic_reponsavelColetaCtrl->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($indicadorversao->dh_versao->Visible) { // dh_versao ?>
		<td>
<?php if ($indicadorversao->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $indicadorversao->dh_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->dh_versao->ViewValue ?></span>
<input type="hidden" data-field="x_dh_versao" name="x<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" id="x<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" value="<?php echo ew_HtmlEncode($indicadorversao->dh_versao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dh_versao" name="o<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" id="o<?php echo $indicadorversao_grid->RowIndex ?>_dh_versao" value="<?php echo ew_HtmlEncode($indicadorversao->dh_versao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$indicadorversao_grid->ListOptions->Render("body", "right", $indicadorversao_grid->RowCnt);
?>
<script type="text/javascript">
findicadorversaogrid.UpdateOpts(<?php echo $indicadorversao_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($indicadorversao->CurrentMode == "add" || $indicadorversao->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $indicadorversao_grid->FormKeyCountName ?>" id="<?php echo $indicadorversao_grid->FormKeyCountName ?>" value="<?php echo $indicadorversao_grid->KeyCount ?>">
<?php echo $indicadorversao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($indicadorversao->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $indicadorversao_grid->FormKeyCountName ?>" id="<?php echo $indicadorversao_grid->FormKeyCountName ?>" value="<?php echo $indicadorversao_grid->KeyCount ?>">
<?php echo $indicadorversao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($indicadorversao->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="findicadorversaogrid">
</div>
<?php

// Close recordset
if ($indicadorversao_grid->Recordset)
	$indicadorversao_grid->Recordset->Close();
?>
<?php if ($indicadorversao_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($indicadorversao_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($indicadorversao->Export == "") { ?>
<script type="text/javascript">
findicadorversaogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$indicadorversao_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$indicadorversao_grid->Page_Terminate();
?>
