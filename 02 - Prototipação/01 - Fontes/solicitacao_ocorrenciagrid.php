<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($solicitacao_ocorrencia_grid)) $solicitacao_ocorrencia_grid = new csolicitacao_ocorrencia_grid();

// Page init
$solicitacao_ocorrencia_grid->Page_Init();

// Page main
$solicitacao_ocorrencia_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$solicitacao_ocorrencia_grid->Page_Render();
?>
<?php if ($solicitacao_ocorrencia->Export == "") { ?>
<script type="text/javascript">

// Page object
var solicitacao_ocorrencia_grid = new ew_Page("solicitacao_ocorrencia_grid");
solicitacao_ocorrencia_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = solicitacao_ocorrencia_grid.PageID; // For backward compatibility

// Form object
var fsolicitacao_ocorrenciagrid = new ew_Form("fsolicitacao_ocorrenciagrid");
fsolicitacao_ocorrenciagrid.FormKeyCountName = '<?php echo $solicitacao_ocorrencia_grid->FormKeyCountName ?>';

// Validate form
fsolicitacao_ocorrenciagrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ic_tpOcorrencia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacao_ocorrencia->ic_tpOcorrencia->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_exibirNoLaudo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($solicitacao_ocorrencia->ic_exibirNoLaudo->FldCaption()) ?>");

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
fsolicitacao_ocorrenciagrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "ic_tpOcorrencia", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_exibirNoLaudo", false)) return false;
	return true;
}

// Form_CustomValidate event
fsolicitacao_ocorrenciagrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsolicitacao_ocorrenciagrid.ValidateRequired = true;
<?php } else { ?>
fsolicitacao_ocorrenciagrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsolicitacao_ocorrenciagrid.Lists["x_nu_usuarioInc"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($solicitacao_ocorrencia->getCurrentMasterTable() == "" && $solicitacao_ocorrencia_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $solicitacao_ocorrencia_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($solicitacao_ocorrencia->CurrentAction == "gridadd") {
	if ($solicitacao_ocorrencia->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$solicitacao_ocorrencia_grid->TotalRecs = $solicitacao_ocorrencia->SelectRecordCount();
			$solicitacao_ocorrencia_grid->Recordset = $solicitacao_ocorrencia_grid->LoadRecordset($solicitacao_ocorrencia_grid->StartRec-1, $solicitacao_ocorrencia_grid->DisplayRecs);
		} else {
			if ($solicitacao_ocorrencia_grid->Recordset = $solicitacao_ocorrencia_grid->LoadRecordset())
				$solicitacao_ocorrencia_grid->TotalRecs = $solicitacao_ocorrencia_grid->Recordset->RecordCount();
		}
		$solicitacao_ocorrencia_grid->StartRec = 1;
		$solicitacao_ocorrencia_grid->DisplayRecs = $solicitacao_ocorrencia_grid->TotalRecs;
	} else {
		$solicitacao_ocorrencia->CurrentFilter = "0=1";
		$solicitacao_ocorrencia_grid->StartRec = 1;
		$solicitacao_ocorrencia_grid->DisplayRecs = $solicitacao_ocorrencia->GridAddRowCount;
	}
	$solicitacao_ocorrencia_grid->TotalRecs = $solicitacao_ocorrencia_grid->DisplayRecs;
	$solicitacao_ocorrencia_grid->StopRec = $solicitacao_ocorrencia_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$solicitacao_ocorrencia_grid->TotalRecs = $solicitacao_ocorrencia->SelectRecordCount();
	} else {
		if ($solicitacao_ocorrencia_grid->Recordset = $solicitacao_ocorrencia_grid->LoadRecordset())
			$solicitacao_ocorrencia_grid->TotalRecs = $solicitacao_ocorrencia_grid->Recordset->RecordCount();
	}
	$solicitacao_ocorrencia_grid->StartRec = 1;
	$solicitacao_ocorrencia_grid->DisplayRecs = $solicitacao_ocorrencia_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$solicitacao_ocorrencia_grid->Recordset = $solicitacao_ocorrencia_grid->LoadRecordset($solicitacao_ocorrencia_grid->StartRec-1, $solicitacao_ocorrencia_grid->DisplayRecs);
}
$solicitacao_ocorrencia_grid->RenderOtherOptions();
?>
<?php $solicitacao_ocorrencia_grid->ShowPageHeader(); ?>
<?php
$solicitacao_ocorrencia_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fsolicitacao_ocorrenciagrid" class="ewForm form-horizontal">
<div id="gmp_solicitacao_ocorrencia" class="ewGridMiddlePanel">
<table id="tbl_solicitacao_ocorrenciagrid" class="ewTable ewTableSeparate">
<?php echo $solicitacao_ocorrencia->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$solicitacao_ocorrencia_grid->RenderListOptions();

// Render list options (header, left)
$solicitacao_ocorrencia_grid->ListOptions->Render("header", "left");
?>
<?php if ($solicitacao_ocorrencia->ic_tpOcorrencia->Visible) { // ic_tpOcorrencia ?>
	<?php if ($solicitacao_ocorrencia->SortUrl($solicitacao_ocorrencia->ic_tpOcorrencia) == "") { ?>
		<td><div id="elh_solicitacao_ocorrencia_ic_tpOcorrencia" class="solicitacao_ocorrencia_ic_tpOcorrencia"><div class="ewTableHeaderCaption"><?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_solicitacao_ocorrencia_ic_tpOcorrencia" class="solicitacao_ocorrencia_ic_tpOcorrencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacao_ocorrencia->ic_tpOcorrencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacao_ocorrencia->ic_tpOcorrencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacao_ocorrencia->ic_exibirNoLaudo->Visible) { // ic_exibirNoLaudo ?>
	<?php if ($solicitacao_ocorrencia->SortUrl($solicitacao_ocorrencia->ic_exibirNoLaudo) == "") { ?>
		<td><div id="elh_solicitacao_ocorrencia_ic_exibirNoLaudo" class="solicitacao_ocorrencia_ic_exibirNoLaudo"><div class="ewTableHeaderCaption"><?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_solicitacao_ocorrencia_ic_exibirNoLaudo" class="solicitacao_ocorrencia_ic_exibirNoLaudo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacao_ocorrencia->ic_exibirNoLaudo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacao_ocorrencia->ic_exibirNoLaudo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacao_ocorrencia->nu_usuarioInc->Visible) { // nu_usuarioInc ?>
	<?php if ($solicitacao_ocorrencia->SortUrl($solicitacao_ocorrencia->nu_usuarioInc) == "") { ?>
		<td><div id="elh_solicitacao_ocorrencia_nu_usuarioInc" class="solicitacao_ocorrencia_nu_usuarioInc"><div class="ewTableHeaderCaption"><?php echo $solicitacao_ocorrencia->nu_usuarioInc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_solicitacao_ocorrencia_nu_usuarioInc" class="solicitacao_ocorrencia_nu_usuarioInc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacao_ocorrencia->nu_usuarioInc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacao_ocorrencia->nu_usuarioInc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacao_ocorrencia->nu_usuarioInc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacao_ocorrencia->dh_inclusao->Visible) { // dh_inclusao ?>
	<?php if ($solicitacao_ocorrencia->SortUrl($solicitacao_ocorrencia->dh_inclusao) == "") { ?>
		<td><div id="elh_solicitacao_ocorrencia_dh_inclusao" class="solicitacao_ocorrencia_dh_inclusao"><div class="ewTableHeaderCaption"><?php echo $solicitacao_ocorrencia->dh_inclusao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_solicitacao_ocorrencia_dh_inclusao" class="solicitacao_ocorrencia_dh_inclusao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacao_ocorrencia->dh_inclusao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacao_ocorrencia->dh_inclusao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacao_ocorrencia->dh_inclusao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$solicitacao_ocorrencia_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$solicitacao_ocorrencia_grid->StartRec = 1;
$solicitacao_ocorrencia_grid->StopRec = $solicitacao_ocorrencia_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($solicitacao_ocorrencia_grid->FormKeyCountName) && ($solicitacao_ocorrencia->CurrentAction == "gridadd" || $solicitacao_ocorrencia->CurrentAction == "gridedit" || $solicitacao_ocorrencia->CurrentAction == "F")) {
		$solicitacao_ocorrencia_grid->KeyCount = $objForm->GetValue($solicitacao_ocorrencia_grid->FormKeyCountName);
		$solicitacao_ocorrencia_grid->StopRec = $solicitacao_ocorrencia_grid->StartRec + $solicitacao_ocorrencia_grid->KeyCount - 1;
	}
}
$solicitacao_ocorrencia_grid->RecCnt = $solicitacao_ocorrencia_grid->StartRec - 1;
if ($solicitacao_ocorrencia_grid->Recordset && !$solicitacao_ocorrencia_grid->Recordset->EOF) {
	$solicitacao_ocorrencia_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $solicitacao_ocorrencia_grid->StartRec > 1)
		$solicitacao_ocorrencia_grid->Recordset->Move($solicitacao_ocorrencia_grid->StartRec - 1);
} elseif (!$solicitacao_ocorrencia->AllowAddDeleteRow && $solicitacao_ocorrencia_grid->StopRec == 0) {
	$solicitacao_ocorrencia_grid->StopRec = $solicitacao_ocorrencia->GridAddRowCount;
}

// Initialize aggregate
$solicitacao_ocorrencia->RowType = EW_ROWTYPE_AGGREGATEINIT;
$solicitacao_ocorrencia->ResetAttrs();
$solicitacao_ocorrencia_grid->RenderRow();
if ($solicitacao_ocorrencia->CurrentAction == "gridadd")
	$solicitacao_ocorrencia_grid->RowIndex = 0;
if ($solicitacao_ocorrencia->CurrentAction == "gridedit")
	$solicitacao_ocorrencia_grid->RowIndex = 0;
while ($solicitacao_ocorrencia_grid->RecCnt < $solicitacao_ocorrencia_grid->StopRec) {
	$solicitacao_ocorrencia_grid->RecCnt++;
	if (intval($solicitacao_ocorrencia_grid->RecCnt) >= intval($solicitacao_ocorrencia_grid->StartRec)) {
		$solicitacao_ocorrencia_grid->RowCnt++;
		if ($solicitacao_ocorrencia->CurrentAction == "gridadd" || $solicitacao_ocorrencia->CurrentAction == "gridedit" || $solicitacao_ocorrencia->CurrentAction == "F") {
			$solicitacao_ocorrencia_grid->RowIndex++;
			$objForm->Index = $solicitacao_ocorrencia_grid->RowIndex;
			if ($objForm->HasValue($solicitacao_ocorrencia_grid->FormActionName))
				$solicitacao_ocorrencia_grid->RowAction = strval($objForm->GetValue($solicitacao_ocorrencia_grid->FormActionName));
			elseif ($solicitacao_ocorrencia->CurrentAction == "gridadd")
				$solicitacao_ocorrencia_grid->RowAction = "insert";
			else
				$solicitacao_ocorrencia_grid->RowAction = "";
		}

		// Set up key count
		$solicitacao_ocorrencia_grid->KeyCount = $solicitacao_ocorrencia_grid->RowIndex;

		// Init row class and style
		$solicitacao_ocorrencia->ResetAttrs();
		$solicitacao_ocorrencia->CssClass = "";
		if ($solicitacao_ocorrencia->CurrentAction == "gridadd") {
			if ($solicitacao_ocorrencia->CurrentMode == "copy") {
				$solicitacao_ocorrencia_grid->LoadRowValues($solicitacao_ocorrencia_grid->Recordset); // Load row values
				$solicitacao_ocorrencia_grid->SetRecordKey($solicitacao_ocorrencia_grid->RowOldKey, $solicitacao_ocorrencia_grid->Recordset); // Set old record key
			} else {
				$solicitacao_ocorrencia_grid->LoadDefaultValues(); // Load default values
				$solicitacao_ocorrencia_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$solicitacao_ocorrencia_grid->LoadRowValues($solicitacao_ocorrencia_grid->Recordset); // Load row values
		}
		$solicitacao_ocorrencia->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($solicitacao_ocorrencia->CurrentAction == "gridadd") // Grid add
			$solicitacao_ocorrencia->RowType = EW_ROWTYPE_ADD; // Render add
		if ($solicitacao_ocorrencia->CurrentAction == "gridadd" && $solicitacao_ocorrencia->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$solicitacao_ocorrencia_grid->RestoreCurrentRowFormValues($solicitacao_ocorrencia_grid->RowIndex); // Restore form values
		if ($solicitacao_ocorrencia->CurrentAction == "gridedit") { // Grid edit
			if ($solicitacao_ocorrencia->EventCancelled) {
				$solicitacao_ocorrencia_grid->RestoreCurrentRowFormValues($solicitacao_ocorrencia_grid->RowIndex); // Restore form values
			}
			if ($solicitacao_ocorrencia_grid->RowAction == "insert")
				$solicitacao_ocorrencia->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$solicitacao_ocorrencia->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($solicitacao_ocorrencia->CurrentAction == "gridedit" && ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_EDIT || $solicitacao_ocorrencia->RowType == EW_ROWTYPE_ADD) && $solicitacao_ocorrencia->EventCancelled) // Update failed
			$solicitacao_ocorrencia_grid->RestoreCurrentRowFormValues($solicitacao_ocorrencia_grid->RowIndex); // Restore form values
		if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_EDIT) // Edit row
			$solicitacao_ocorrencia_grid->EditRowCnt++;
		if ($solicitacao_ocorrencia->CurrentAction == "F") // Confirm row
			$solicitacao_ocorrencia_grid->RestoreCurrentRowFormValues($solicitacao_ocorrencia_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$solicitacao_ocorrencia->RowAttrs = array_merge($solicitacao_ocorrencia->RowAttrs, array('data-rowindex'=>$solicitacao_ocorrencia_grid->RowCnt, 'id'=>'r' . $solicitacao_ocorrencia_grid->RowCnt . '_solicitacao_ocorrencia', 'data-rowtype'=>$solicitacao_ocorrencia->RowType));

		// Render row
		$solicitacao_ocorrencia_grid->RenderRow();

		// Render list options
		$solicitacao_ocorrencia_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($solicitacao_ocorrencia_grid->RowAction <> "delete" && $solicitacao_ocorrencia_grid->RowAction <> "insertdelete" && !($solicitacao_ocorrencia_grid->RowAction == "insert" && $solicitacao_ocorrencia->CurrentAction == "F" && $solicitacao_ocorrencia_grid->EmptyRow())) {
?>
	<tr<?php echo $solicitacao_ocorrencia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$solicitacao_ocorrencia_grid->ListOptions->Render("body", "left", $solicitacao_ocorrencia_grid->RowCnt);
?>
	<?php if ($solicitacao_ocorrencia->ic_tpOcorrencia->Visible) { // ic_tpOcorrencia ?>
		<td<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->CellAttributes() ?>>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $solicitacao_ocorrencia_grid->RowCnt ?>_solicitacao_ocorrencia_ic_tpOcorrencia" class="control-group solicitacao_ocorrencia_ic_tpOcorrencia">
<select data-field="x_ic_tpOcorrencia" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia"<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->EditAttributes() ?>>
<?php
if (is_array($solicitacao_ocorrencia->ic_tpOcorrencia->EditValue)) {
	$arwrk = $solicitacao_ocorrencia->ic_tpOcorrencia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_ocorrencia->ic_tpOcorrencia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $solicitacao_ocorrencia->ic_tpOcorrencia->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_ic_tpOcorrencia" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_tpOcorrencia->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $solicitacao_ocorrencia_grid->RowCnt ?>_solicitacao_ocorrencia_ic_tpOcorrencia" class="control-group solicitacao_ocorrencia_ic_tpOcorrencia">
<select data-field="x_ic_tpOcorrencia" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia"<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->EditAttributes() ?>>
<?php
if (is_array($solicitacao_ocorrencia->ic_tpOcorrencia->EditValue)) {
	$arwrk = $solicitacao_ocorrencia->ic_tpOcorrencia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_ocorrencia->ic_tpOcorrencia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $solicitacao_ocorrencia->ic_tpOcorrencia->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_tpOcorrencia" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_tpOcorrencia->FormValue) ?>">
<input type="hidden" data-field="x_ic_tpOcorrencia" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_tpOcorrencia->OldValue) ?>">
<?php } ?>
<a id="<?php echo $solicitacao_ocorrencia_grid->PageObjName . "_row_" . $solicitacao_ocorrencia_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_solicitacao" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_solicitacao" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_solicitacao->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_solicitacao" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_solicitacao" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_solicitacao->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_EDIT || $solicitacao_ocorrencia->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_solicitacao" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_solicitacao" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_solicitacao" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_solicitacao->CurrentValue) ?>">
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_ocorrencia" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_ocorrencia" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_ocorrencia" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_ocorrencia->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_ocorrencia" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_ocorrencia" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_ocorrencia" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_ocorrencia->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_EDIT || $solicitacao_ocorrencia->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_ocorrencia" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_ocorrencia" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_ocorrencia" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_ocorrencia->CurrentValue) ?>">
<?php } ?>
	<?php if ($solicitacao_ocorrencia->ic_exibirNoLaudo->Visible) { // ic_exibirNoLaudo ?>
		<td<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->CellAttributes() ?>>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $solicitacao_ocorrencia_grid->RowCnt ?>_solicitacao_ocorrencia_ic_exibirNoLaudo" class="control-group solicitacao_ocorrencia_ic_exibirNoLaudo">
<div id="tp_x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" value="{value}"<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $solicitacao_ocorrencia->ic_exibirNoLaudo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_ocorrencia->ic_exibirNoLaudo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_exibirNoLaudo" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $solicitacao_ocorrencia->ic_exibirNoLaudo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_exibirNoLaudo" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_exibirNoLaudo->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $solicitacao_ocorrencia_grid->RowCnt ?>_solicitacao_ocorrencia_ic_exibirNoLaudo" class="control-group solicitacao_ocorrencia_ic_exibirNoLaudo">
<div id="tp_x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" value="{value}"<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $solicitacao_ocorrencia->ic_exibirNoLaudo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_ocorrencia->ic_exibirNoLaudo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_exibirNoLaudo" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $solicitacao_ocorrencia->ic_exibirNoLaudo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_exibirNoLaudo" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_exibirNoLaudo->FormValue) ?>">
<input type="hidden" data-field="x_ic_exibirNoLaudo" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_exibirNoLaudo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $solicitacao_ocorrencia_grid->PageObjName . "_row_" . $solicitacao_ocorrencia_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacao_ocorrencia->nu_usuarioInc->Visible) { // nu_usuarioInc ?>
		<td<?php echo $solicitacao_ocorrencia->nu_usuarioInc->CellAttributes() ?>>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_usuarioInc" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_usuarioInc->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $solicitacao_ocorrencia->nu_usuarioInc->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->nu_usuarioInc->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_usuarioInc" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_usuarioInc->FormValue) ?>">
<input type="hidden" data-field="x_nu_usuarioInc" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_usuarioInc->OldValue) ?>">
<?php } ?>
<a id="<?php echo $solicitacao_ocorrencia_grid->PageObjName . "_row_" . $solicitacao_ocorrencia_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacao_ocorrencia->dh_inclusao->Visible) { // dh_inclusao ?>
		<td<?php echo $solicitacao_ocorrencia->dh_inclusao->CellAttributes() ?>>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->dh_inclusao->OldValue) ?>">
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $solicitacao_ocorrencia->dh_inclusao->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->dh_inclusao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dh_inclusao" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->dh_inclusao->FormValue) ?>">
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->dh_inclusao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $solicitacao_ocorrencia_grid->PageObjName . "_row_" . $solicitacao_ocorrencia_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$solicitacao_ocorrencia_grid->ListOptions->Render("body", "right", $solicitacao_ocorrencia_grid->RowCnt);
?>
	</tr>
<?php if ($solicitacao_ocorrencia->RowType == EW_ROWTYPE_ADD || $solicitacao_ocorrencia->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsolicitacao_ocorrenciagrid.UpdateOpts(<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($solicitacao_ocorrencia->CurrentAction <> "gridadd" || $solicitacao_ocorrencia->CurrentMode == "copy")
		if (!$solicitacao_ocorrencia_grid->Recordset->EOF) $solicitacao_ocorrencia_grid->Recordset->MoveNext();
}
?>
<?php
	if ($solicitacao_ocorrencia->CurrentMode == "add" || $solicitacao_ocorrencia->CurrentMode == "copy" || $solicitacao_ocorrencia->CurrentMode == "edit") {
		$solicitacao_ocorrencia_grid->RowIndex = '$rowindex$';
		$solicitacao_ocorrencia_grid->LoadDefaultValues();

		// Set row properties
		$solicitacao_ocorrencia->ResetAttrs();
		$solicitacao_ocorrencia->RowAttrs = array_merge($solicitacao_ocorrencia->RowAttrs, array('data-rowindex'=>$solicitacao_ocorrencia_grid->RowIndex, 'id'=>'r0_solicitacao_ocorrencia', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($solicitacao_ocorrencia->RowAttrs["class"], "ewTemplate");
		$solicitacao_ocorrencia->RowType = EW_ROWTYPE_ADD;

		// Render row
		$solicitacao_ocorrencia_grid->RenderRow();

		// Render list options
		$solicitacao_ocorrencia_grid->RenderListOptions();
		$solicitacao_ocorrencia_grid->StartRowCnt = 0;
?>
	<tr<?php echo $solicitacao_ocorrencia->RowAttributes() ?>>
<?php

// Render list options (body, left)
$solicitacao_ocorrencia_grid->ListOptions->Render("body", "left", $solicitacao_ocorrencia_grid->RowIndex);
?>
	<?php if ($solicitacao_ocorrencia->ic_tpOcorrencia->Visible) { // ic_tpOcorrencia ?>
		<td>
<?php if ($solicitacao_ocorrencia->CurrentAction <> "F") { ?>
<select data-field="x_ic_tpOcorrencia" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia"<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->EditAttributes() ?>>
<?php
if (is_array($solicitacao_ocorrencia->ic_tpOcorrencia->EditValue)) {
	$arwrk = $solicitacao_ocorrencia->ic_tpOcorrencia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_ocorrencia->ic_tpOcorrencia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $solicitacao_ocorrencia->ic_tpOcorrencia->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->ViewValue ?></span>
<input type="hidden" data-field="x_ic_tpOcorrencia" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_tpOcorrencia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_tpOcorrencia" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_tpOcorrencia" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_tpOcorrencia->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($solicitacao_ocorrencia->ic_exibirNoLaudo->Visible) { // ic_exibirNoLaudo ?>
		<td>
<?php if ($solicitacao_ocorrencia->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" value="{value}"<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $solicitacao_ocorrencia->ic_exibirNoLaudo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacao_ocorrencia->ic_exibirNoLaudo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_exibirNoLaudo" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $solicitacao_ocorrencia->ic_exibirNoLaudo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_exibirNoLaudo" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_exibirNoLaudo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_exibirNoLaudo" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_ic_exibirNoLaudo" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->ic_exibirNoLaudo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($solicitacao_ocorrencia->nu_usuarioInc->Visible) { // nu_usuarioInc ?>
		<td>
<?php if ($solicitacao_ocorrencia->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $solicitacao_ocorrencia->nu_usuarioInc->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->nu_usuarioInc->ViewValue ?></span>
<input type="hidden" data-field="x_nu_usuarioInc" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_usuarioInc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_usuarioInc" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_nu_usuarioInc" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->nu_usuarioInc->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($solicitacao_ocorrencia->dh_inclusao->Visible) { // dh_inclusao ?>
		<td>
<?php if ($solicitacao_ocorrencia->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $solicitacao_ocorrencia->dh_inclusao->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->dh_inclusao->ViewValue ?></span>
<input type="hidden" data-field="x_dh_inclusao" name="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->dh_inclusao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($solicitacao_ocorrencia->dh_inclusao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$solicitacao_ocorrencia_grid->ListOptions->Render("body", "right", $solicitacao_ocorrencia_grid->RowCnt);
?>
<script type="text/javascript">
fsolicitacao_ocorrenciagrid.UpdateOpts(<?php echo $solicitacao_ocorrencia_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($solicitacao_ocorrencia->CurrentMode == "add" || $solicitacao_ocorrencia->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $solicitacao_ocorrencia_grid->FormKeyCountName ?>" id="<?php echo $solicitacao_ocorrencia_grid->FormKeyCountName ?>" value="<?php echo $solicitacao_ocorrencia_grid->KeyCount ?>">
<?php echo $solicitacao_ocorrencia_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($solicitacao_ocorrencia->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $solicitacao_ocorrencia_grid->FormKeyCountName ?>" id="<?php echo $solicitacao_ocorrencia_grid->FormKeyCountName ?>" value="<?php echo $solicitacao_ocorrencia_grid->KeyCount ?>">
<?php echo $solicitacao_ocorrencia_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($solicitacao_ocorrencia->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsolicitacao_ocorrenciagrid">
</div>
<?php

// Close recordset
if ($solicitacao_ocorrencia_grid->Recordset)
	$solicitacao_ocorrencia_grid->Recordset->Close();
?>
<?php if ($solicitacao_ocorrencia_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($solicitacao_ocorrencia_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($solicitacao_ocorrencia->Export == "") { ?>
<script type="text/javascript">
fsolicitacao_ocorrenciagrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$solicitacao_ocorrencia_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$solicitacao_ocorrencia_grid->Page_Terminate();
?>
