<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($criterioavaliacao_grid)) $criterioavaliacao_grid = new ccriterioavaliacao_grid();

// Page init
$criterioavaliacao_grid->Page_Init();

// Page main
$criterioavaliacao_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$criterioavaliacao_grid->Page_Render();
?>
<?php if ($criterioavaliacao->Export == "") { ?>
<script type="text/javascript">

// Page object
var criterioavaliacao_grid = new ew_Page("criterioavaliacao_grid");
criterioavaliacao_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = criterioavaliacao_grid.PageID; // For backward compatibility

// Form object
var fcriterioavaliacaogrid = new ew_Form("fcriterioavaliacaogrid");
fcriterioavaliacaogrid.FormKeyCountName = '<?php echo $criterioavaliacao_grid->FormKeyCountName ?>';

// Validate form
fcriterioavaliacaogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_alternativa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($criterioavaliacao->no_alternativa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_alternativa");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($criterioavaliacao->vr_alternativa->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_alternativa");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($criterioavaliacao->vr_alternativa->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($criterioavaliacao->ic_ativo->FldCaption()) ?>");

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
fcriterioavaliacaogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_alternativa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_alternativa", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
fcriterioavaliacaogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcriterioavaliacaogrid.ValidateRequired = true;
<?php } else { ?>
fcriterioavaliacaogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($criterioavaliacao->getCurrentMasterTable() == "" && $criterioavaliacao_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $criterioavaliacao_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($criterioavaliacao->CurrentAction == "gridadd") {
	if ($criterioavaliacao->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$criterioavaliacao_grid->TotalRecs = $criterioavaliacao->SelectRecordCount();
			$criterioavaliacao_grid->Recordset = $criterioavaliacao_grid->LoadRecordset($criterioavaliacao_grid->StartRec-1, $criterioavaliacao_grid->DisplayRecs);
		} else {
			if ($criterioavaliacao_grid->Recordset = $criterioavaliacao_grid->LoadRecordset())
				$criterioavaliacao_grid->TotalRecs = $criterioavaliacao_grid->Recordset->RecordCount();
		}
		$criterioavaliacao_grid->StartRec = 1;
		$criterioavaliacao_grid->DisplayRecs = $criterioavaliacao_grid->TotalRecs;
	} else {
		$criterioavaliacao->CurrentFilter = "0=1";
		$criterioavaliacao_grid->StartRec = 1;
		$criterioavaliacao_grid->DisplayRecs = $criterioavaliacao->GridAddRowCount;
	}
	$criterioavaliacao_grid->TotalRecs = $criterioavaliacao_grid->DisplayRecs;
	$criterioavaliacao_grid->StopRec = $criterioavaliacao_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$criterioavaliacao_grid->TotalRecs = $criterioavaliacao->SelectRecordCount();
	} else {
		if ($criterioavaliacao_grid->Recordset = $criterioavaliacao_grid->LoadRecordset())
			$criterioavaliacao_grid->TotalRecs = $criterioavaliacao_grid->Recordset->RecordCount();
	}
	$criterioavaliacao_grid->StartRec = 1;
	$criterioavaliacao_grid->DisplayRecs = $criterioavaliacao_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$criterioavaliacao_grid->Recordset = $criterioavaliacao_grid->LoadRecordset($criterioavaliacao_grid->StartRec-1, $criterioavaliacao_grid->DisplayRecs);
}
$criterioavaliacao_grid->RenderOtherOptions();
?>
<?php $criterioavaliacao_grid->ShowPageHeader(); ?>
<?php
$criterioavaliacao_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fcriterioavaliacaogrid" class="ewForm form-horizontal">
<div id="gmp_criterioavaliacao" class="ewGridMiddlePanel">
<table id="tbl_criterioavaliacaogrid" class="ewTable ewTableSeparate">
<?php echo $criterioavaliacao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$criterioavaliacao_grid->RenderListOptions();

// Render list options (header, left)
$criterioavaliacao_grid->ListOptions->Render("header", "left");
?>
<?php if ($criterioavaliacao->no_alternativa->Visible) { // no_alternativa ?>
	<?php if ($criterioavaliacao->SortUrl($criterioavaliacao->no_alternativa) == "") { ?>
		<td><div id="elh_criterioavaliacao_no_alternativa" class="criterioavaliacao_no_alternativa"><div class="ewTableHeaderCaption"><?php echo $criterioavaliacao->no_alternativa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_criterioavaliacao_no_alternativa" class="criterioavaliacao_no_alternativa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $criterioavaliacao->no_alternativa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($criterioavaliacao->no_alternativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($criterioavaliacao->no_alternativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($criterioavaliacao->vr_alternativa->Visible) { // vr_alternativa ?>
	<?php if ($criterioavaliacao->SortUrl($criterioavaliacao->vr_alternativa) == "") { ?>
		<td><div id="elh_criterioavaliacao_vr_alternativa" class="criterioavaliacao_vr_alternativa"><div class="ewTableHeaderCaption"><?php echo $criterioavaliacao->vr_alternativa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_criterioavaliacao_vr_alternativa" class="criterioavaliacao_vr_alternativa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $criterioavaliacao->vr_alternativa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($criterioavaliacao->vr_alternativa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($criterioavaliacao->vr_alternativa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($criterioavaliacao->dt_manutencao->Visible) { // dt_manutencao ?>
	<?php if ($criterioavaliacao->SortUrl($criterioavaliacao->dt_manutencao) == "") { ?>
		<td><div id="elh_criterioavaliacao_dt_manutencao" class="criterioavaliacao_dt_manutencao"><div class="ewTableHeaderCaption"><?php echo $criterioavaliacao->dt_manutencao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_criterioavaliacao_dt_manutencao" class="criterioavaliacao_dt_manutencao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $criterioavaliacao->dt_manutencao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($criterioavaliacao->dt_manutencao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($criterioavaliacao->dt_manutencao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($criterioavaliacao->nu_usuarioAlterou->Visible) { // nu_usuarioAlterou ?>
	<?php if ($criterioavaliacao->SortUrl($criterioavaliacao->nu_usuarioAlterou) == "") { ?>
		<td><div id="elh_criterioavaliacao_nu_usuarioAlterou" class="criterioavaliacao_nu_usuarioAlterou"><div class="ewTableHeaderCaption"><?php echo $criterioavaliacao->nu_usuarioAlterou->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_criterioavaliacao_nu_usuarioAlterou" class="criterioavaliacao_nu_usuarioAlterou">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $criterioavaliacao->nu_usuarioAlterou->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($criterioavaliacao->nu_usuarioAlterou->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($criterioavaliacao->nu_usuarioAlterou->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($criterioavaliacao->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($criterioavaliacao->SortUrl($criterioavaliacao->ic_ativo) == "") { ?>
		<td><div id="elh_criterioavaliacao_ic_ativo" class="criterioavaliacao_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $criterioavaliacao->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_criterioavaliacao_ic_ativo" class="criterioavaliacao_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $criterioavaliacao->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($criterioavaliacao->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($criterioavaliacao->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$criterioavaliacao_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$criterioavaliacao_grid->StartRec = 1;
$criterioavaliacao_grid->StopRec = $criterioavaliacao_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($criterioavaliacao_grid->FormKeyCountName) && ($criterioavaliacao->CurrentAction == "gridadd" || $criterioavaliacao->CurrentAction == "gridedit" || $criterioavaliacao->CurrentAction == "F")) {
		$criterioavaliacao_grid->KeyCount = $objForm->GetValue($criterioavaliacao_grid->FormKeyCountName);
		$criterioavaliacao_grid->StopRec = $criterioavaliacao_grid->StartRec + $criterioavaliacao_grid->KeyCount - 1;
	}
}
$criterioavaliacao_grid->RecCnt = $criterioavaliacao_grid->StartRec - 1;
if ($criterioavaliacao_grid->Recordset && !$criterioavaliacao_grid->Recordset->EOF) {
	$criterioavaliacao_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $criterioavaliacao_grid->StartRec > 1)
		$criterioavaliacao_grid->Recordset->Move($criterioavaliacao_grid->StartRec - 1);
} elseif (!$criterioavaliacao->AllowAddDeleteRow && $criterioavaliacao_grid->StopRec == 0) {
	$criterioavaliacao_grid->StopRec = $criterioavaliacao->GridAddRowCount;
}

// Initialize aggregate
$criterioavaliacao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$criterioavaliacao->ResetAttrs();
$criterioavaliacao_grid->RenderRow();
if ($criterioavaliacao->CurrentAction == "gridadd")
	$criterioavaliacao_grid->RowIndex = 0;
if ($criterioavaliacao->CurrentAction == "gridedit")
	$criterioavaliacao_grid->RowIndex = 0;
while ($criterioavaliacao_grid->RecCnt < $criterioavaliacao_grid->StopRec) {
	$criterioavaliacao_grid->RecCnt++;
	if (intval($criterioavaliacao_grid->RecCnt) >= intval($criterioavaliacao_grid->StartRec)) {
		$criterioavaliacao_grid->RowCnt++;
		if ($criterioavaliacao->CurrentAction == "gridadd" || $criterioavaliacao->CurrentAction == "gridedit" || $criterioavaliacao->CurrentAction == "F") {
			$criterioavaliacao_grid->RowIndex++;
			$objForm->Index = $criterioavaliacao_grid->RowIndex;
			if ($objForm->HasValue($criterioavaliacao_grid->FormActionName))
				$criterioavaliacao_grid->RowAction = strval($objForm->GetValue($criterioavaliacao_grid->FormActionName));
			elseif ($criterioavaliacao->CurrentAction == "gridadd")
				$criterioavaliacao_grid->RowAction = "insert";
			else
				$criterioavaliacao_grid->RowAction = "";
		}

		// Set up key count
		$criterioavaliacao_grid->KeyCount = $criterioavaliacao_grid->RowIndex;

		// Init row class and style
		$criterioavaliacao->ResetAttrs();
		$criterioavaliacao->CssClass = "";
		if ($criterioavaliacao->CurrentAction == "gridadd") {
			if ($criterioavaliacao->CurrentMode == "copy") {
				$criterioavaliacao_grid->LoadRowValues($criterioavaliacao_grid->Recordset); // Load row values
				$criterioavaliacao_grid->SetRecordKey($criterioavaliacao_grid->RowOldKey, $criterioavaliacao_grid->Recordset); // Set old record key
			} else {
				$criterioavaliacao_grid->LoadDefaultValues(); // Load default values
				$criterioavaliacao_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$criterioavaliacao_grid->LoadRowValues($criterioavaliacao_grid->Recordset); // Load row values
		}
		$criterioavaliacao->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($criterioavaliacao->CurrentAction == "gridadd") // Grid add
			$criterioavaliacao->RowType = EW_ROWTYPE_ADD; // Render add
		if ($criterioavaliacao->CurrentAction == "gridadd" && $criterioavaliacao->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$criterioavaliacao_grid->RestoreCurrentRowFormValues($criterioavaliacao_grid->RowIndex); // Restore form values
		if ($criterioavaliacao->CurrentAction == "gridedit") { // Grid edit
			if ($criterioavaliacao->EventCancelled) {
				$criterioavaliacao_grid->RestoreCurrentRowFormValues($criterioavaliacao_grid->RowIndex); // Restore form values
			}
			if ($criterioavaliacao_grid->RowAction == "insert")
				$criterioavaliacao->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$criterioavaliacao->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($criterioavaliacao->CurrentAction == "gridedit" && ($criterioavaliacao->RowType == EW_ROWTYPE_EDIT || $criterioavaliacao->RowType == EW_ROWTYPE_ADD) && $criterioavaliacao->EventCancelled) // Update failed
			$criterioavaliacao_grid->RestoreCurrentRowFormValues($criterioavaliacao_grid->RowIndex); // Restore form values
		if ($criterioavaliacao->RowType == EW_ROWTYPE_EDIT) // Edit row
			$criterioavaliacao_grid->EditRowCnt++;
		if ($criterioavaliacao->CurrentAction == "F") // Confirm row
			$criterioavaliacao_grid->RestoreCurrentRowFormValues($criterioavaliacao_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$criterioavaliacao->RowAttrs = array_merge($criterioavaliacao->RowAttrs, array('data-rowindex'=>$criterioavaliacao_grid->RowCnt, 'id'=>'r' . $criterioavaliacao_grid->RowCnt . '_criterioavaliacao', 'data-rowtype'=>$criterioavaliacao->RowType));

		// Render row
		$criterioavaliacao_grid->RenderRow();

		// Render list options
		$criterioavaliacao_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($criterioavaliacao_grid->RowAction <> "delete" && $criterioavaliacao_grid->RowAction <> "insertdelete" && !($criterioavaliacao_grid->RowAction == "insert" && $criterioavaliacao->CurrentAction == "F" && $criterioavaliacao_grid->EmptyRow())) {
?>
	<tr<?php echo $criterioavaliacao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$criterioavaliacao_grid->ListOptions->Render("body", "left", $criterioavaliacao_grid->RowCnt);
?>
	<?php if ($criterioavaliacao->no_alternativa->Visible) { // no_alternativa ?>
		<td<?php echo $criterioavaliacao->no_alternativa->CellAttributes() ?>>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $criterioavaliacao_grid->RowCnt ?>_criterioavaliacao_no_alternativa" class="control-group criterioavaliacao_no_alternativa">
<input type="text" data-field="x_no_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" size="30" maxlength="50" placeholder="<?php echo $criterioavaliacao->no_alternativa->PlaceHolder ?>" value="<?php echo $criterioavaliacao->no_alternativa->EditValue ?>"<?php echo $criterioavaliacao->no_alternativa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_alternativa" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->no_alternativa->OldValue) ?>">
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $criterioavaliacao_grid->RowCnt ?>_criterioavaliacao_no_alternativa" class="control-group criterioavaliacao_no_alternativa">
<input type="text" data-field="x_no_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" size="30" maxlength="50" placeholder="<?php echo $criterioavaliacao->no_alternativa->PlaceHolder ?>" value="<?php echo $criterioavaliacao->no_alternativa->EditValue ?>"<?php echo $criterioavaliacao->no_alternativa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $criterioavaliacao->no_alternativa->ViewAttributes() ?>>
<?php echo $criterioavaliacao->no_alternativa->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->no_alternativa->FormValue) ?>">
<input type="hidden" data-field="x_no_alternativa" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->no_alternativa->OldValue) ?>">
<?php } ?>
<a id="<?php echo $criterioavaliacao_grid->PageObjName . "_row_" . $criterioavaliacao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_alternativaAvaliacao" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_alternativaAvaliacao" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_alternativaAvaliacao" value="<?php echo ew_HtmlEncode($criterioavaliacao->nu_alternativaAvaliacao->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_alternativaAvaliacao" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_alternativaAvaliacao" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_alternativaAvaliacao" value="<?php echo ew_HtmlEncode($criterioavaliacao->nu_alternativaAvaliacao->OldValue) ?>">
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_EDIT || $criterioavaliacao->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_alternativaAvaliacao" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_alternativaAvaliacao" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_alternativaAvaliacao" value="<?php echo ew_HtmlEncode($criterioavaliacao->nu_alternativaAvaliacao->CurrentValue) ?>">
<?php } ?>
	<?php if ($criterioavaliacao->vr_alternativa->Visible) { // vr_alternativa ?>
		<td<?php echo $criterioavaliacao->vr_alternativa->CellAttributes() ?>>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $criterioavaliacao_grid->RowCnt ?>_criterioavaliacao_vr_alternativa" class="control-group criterioavaliacao_vr_alternativa">
<input type="text" data-field="x_vr_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" size="30" placeholder="<?php echo $criterioavaliacao->vr_alternativa->PlaceHolder ?>" value="<?php echo $criterioavaliacao->vr_alternativa->EditValue ?>"<?php echo $criterioavaliacao->vr_alternativa->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_alternativa" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->vr_alternativa->OldValue) ?>">
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $criterioavaliacao_grid->RowCnt ?>_criterioavaliacao_vr_alternativa" class="control-group criterioavaliacao_vr_alternativa">
<input type="text" data-field="x_vr_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" size="30" placeholder="<?php echo $criterioavaliacao->vr_alternativa->PlaceHolder ?>" value="<?php echo $criterioavaliacao->vr_alternativa->EditValue ?>"<?php echo $criterioavaliacao->vr_alternativa->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $criterioavaliacao->vr_alternativa->ViewAttributes() ?>>
<?php echo $criterioavaliacao->vr_alternativa->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->vr_alternativa->FormValue) ?>">
<input type="hidden" data-field="x_vr_alternativa" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->vr_alternativa->OldValue) ?>">
<?php } ?>
<a id="<?php echo $criterioavaliacao_grid->PageObjName . "_row_" . $criterioavaliacao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($criterioavaliacao->dt_manutencao->Visible) { // dt_manutencao ?>
		<td<?php echo $criterioavaliacao->dt_manutencao->CellAttributes() ?>>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_dt_manutencao" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" value="<?php echo ew_HtmlEncode($criterioavaliacao->dt_manutencao->OldValue) ?>">
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $criterioavaliacao->dt_manutencao->ViewAttributes() ?>>
<?php echo $criterioavaliacao->dt_manutencao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dt_manutencao" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" value="<?php echo ew_HtmlEncode($criterioavaliacao->dt_manutencao->FormValue) ?>">
<input type="hidden" data-field="x_dt_manutencao" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" value="<?php echo ew_HtmlEncode($criterioavaliacao->dt_manutencao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $criterioavaliacao_grid->PageObjName . "_row_" . $criterioavaliacao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($criterioavaliacao->nu_usuarioAlterou->Visible) { // nu_usuarioAlterou ?>
		<td<?php echo $criterioavaliacao->nu_usuarioAlterou->CellAttributes() ?>>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_usuarioAlterou" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" value="<?php echo ew_HtmlEncode($criterioavaliacao->nu_usuarioAlterou->OldValue) ?>">
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $criterioavaliacao->nu_usuarioAlterou->ViewAttributes() ?>>
<?php echo $criterioavaliacao->nu_usuarioAlterou->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_usuarioAlterou" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" value="<?php echo ew_HtmlEncode($criterioavaliacao->nu_usuarioAlterou->FormValue) ?>">
<input type="hidden" data-field="x_nu_usuarioAlterou" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" value="<?php echo ew_HtmlEncode($criterioavaliacao->nu_usuarioAlterou->OldValue) ?>">
<?php } ?>
<a id="<?php echo $criterioavaliacao_grid->PageObjName . "_row_" . $criterioavaliacao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($criterioavaliacao->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $criterioavaliacao->ic_ativo->CellAttributes() ?>>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $criterioavaliacao_grid->RowCnt ?>_criterioavaliacao_ic_ativo" class="control-group criterioavaliacao_ic_ativo">
<div id="tp_x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $criterioavaliacao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $criterioavaliacao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($criterioavaliacao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $criterioavaliacao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $criterioavaliacao->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($criterioavaliacao->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $criterioavaliacao_grid->RowCnt ?>_criterioavaliacao_ic_ativo" class="control-group criterioavaliacao_ic_ativo">
<div id="tp_x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $criterioavaliacao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $criterioavaliacao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($criterioavaliacao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $criterioavaliacao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $criterioavaliacao->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $criterioavaliacao->ic_ativo->ViewAttributes() ?>>
<?php echo $criterioavaliacao->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($criterioavaliacao->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($criterioavaliacao->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $criterioavaliacao_grid->PageObjName . "_row_" . $criterioavaliacao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$criterioavaliacao_grid->ListOptions->Render("body", "right", $criterioavaliacao_grid->RowCnt);
?>
	</tr>
<?php if ($criterioavaliacao->RowType == EW_ROWTYPE_ADD || $criterioavaliacao->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcriterioavaliacaogrid.UpdateOpts(<?php echo $criterioavaliacao_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($criterioavaliacao->CurrentAction <> "gridadd" || $criterioavaliacao->CurrentMode == "copy")
		if (!$criterioavaliacao_grid->Recordset->EOF) $criterioavaliacao_grid->Recordset->MoveNext();
}
?>
<?php
	if ($criterioavaliacao->CurrentMode == "add" || $criterioavaliacao->CurrentMode == "copy" || $criterioavaliacao->CurrentMode == "edit") {
		$criterioavaliacao_grid->RowIndex = '$rowindex$';
		$criterioavaliacao_grid->LoadDefaultValues();

		// Set row properties
		$criterioavaliacao->ResetAttrs();
		$criterioavaliacao->RowAttrs = array_merge($criterioavaliacao->RowAttrs, array('data-rowindex'=>$criterioavaliacao_grid->RowIndex, 'id'=>'r0_criterioavaliacao', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($criterioavaliacao->RowAttrs["class"], "ewTemplate");
		$criterioavaliacao->RowType = EW_ROWTYPE_ADD;

		// Render row
		$criterioavaliacao_grid->RenderRow();

		// Render list options
		$criterioavaliacao_grid->RenderListOptions();
		$criterioavaliacao_grid->StartRowCnt = 0;
?>
	<tr<?php echo $criterioavaliacao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$criterioavaliacao_grid->ListOptions->Render("body", "left", $criterioavaliacao_grid->RowIndex);
?>
	<?php if ($criterioavaliacao->no_alternativa->Visible) { // no_alternativa ?>
		<td>
<?php if ($criterioavaliacao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" size="30" maxlength="50" placeholder="<?php echo $criterioavaliacao->no_alternativa->PlaceHolder ?>" value="<?php echo $criterioavaliacao->no_alternativa->EditValue ?>"<?php echo $criterioavaliacao->no_alternativa->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $criterioavaliacao->no_alternativa->ViewAttributes() ?>>
<?php echo $criterioavaliacao->no_alternativa->ViewValue ?></span>
<input type="hidden" data-field="x_no_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->no_alternativa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_alternativa" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_no_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->no_alternativa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($criterioavaliacao->vr_alternativa->Visible) { // vr_alternativa ?>
		<td>
<?php if ($criterioavaliacao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" size="30" placeholder="<?php echo $criterioavaliacao->vr_alternativa->PlaceHolder ?>" value="<?php echo $criterioavaliacao->vr_alternativa->EditValue ?>"<?php echo $criterioavaliacao->vr_alternativa->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $criterioavaliacao->vr_alternativa->ViewAttributes() ?>>
<?php echo $criterioavaliacao->vr_alternativa->ViewValue ?></span>
<input type="hidden" data-field="x_vr_alternativa" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->vr_alternativa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_alternativa" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_vr_alternativa" value="<?php echo ew_HtmlEncode($criterioavaliacao->vr_alternativa->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($criterioavaliacao->dt_manutencao->Visible) { // dt_manutencao ?>
		<td>
<?php if ($criterioavaliacao->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $criterioavaliacao->dt_manutencao->ViewAttributes() ?>>
<?php echo $criterioavaliacao->dt_manutencao->ViewValue ?></span>
<input type="hidden" data-field="x_dt_manutencao" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" value="<?php echo ew_HtmlEncode($criterioavaliacao->dt_manutencao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dt_manutencao" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_dt_manutencao" value="<?php echo ew_HtmlEncode($criterioavaliacao->dt_manutencao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($criterioavaliacao->nu_usuarioAlterou->Visible) { // nu_usuarioAlterou ?>
		<td>
<?php if ($criterioavaliacao->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $criterioavaliacao->nu_usuarioAlterou->ViewAttributes() ?>>
<?php echo $criterioavaliacao->nu_usuarioAlterou->ViewValue ?></span>
<input type="hidden" data-field="x_nu_usuarioAlterou" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" value="<?php echo ew_HtmlEncode($criterioavaliacao->nu_usuarioAlterou->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_usuarioAlterou" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_nu_usuarioAlterou" value="<?php echo ew_HtmlEncode($criterioavaliacao->nu_usuarioAlterou->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($criterioavaliacao->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($criterioavaliacao->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $criterioavaliacao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $criterioavaliacao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($criterioavaliacao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $criterioavaliacao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $criterioavaliacao->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $criterioavaliacao->ic_ativo->ViewAttributes() ?>>
<?php echo $criterioavaliacao->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($criterioavaliacao->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" id="o<?php echo $criterioavaliacao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($criterioavaliacao->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$criterioavaliacao_grid->ListOptions->Render("body", "right", $criterioavaliacao_grid->RowCnt);
?>
<script type="text/javascript">
fcriterioavaliacaogrid.UpdateOpts(<?php echo $criterioavaliacao_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($criterioavaliacao->CurrentMode == "add" || $criterioavaliacao->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $criterioavaliacao_grid->FormKeyCountName ?>" id="<?php echo $criterioavaliacao_grid->FormKeyCountName ?>" value="<?php echo $criterioavaliacao_grid->KeyCount ?>">
<?php echo $criterioavaliacao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($criterioavaliacao->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $criterioavaliacao_grid->FormKeyCountName ?>" id="<?php echo $criterioavaliacao_grid->FormKeyCountName ?>" value="<?php echo $criterioavaliacao_grid->KeyCount ?>">
<?php echo $criterioavaliacao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($criterioavaliacao->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcriterioavaliacaogrid">
</div>
<?php

// Close recordset
if ($criterioavaliacao_grid->Recordset)
	$criterioavaliacao_grid->Recordset->Close();
?>
<?php if ($criterioavaliacao_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($criterioavaliacao_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($criterioavaliacao->Export == "") { ?>
<script type="text/javascript">
fcriterioavaliacaogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$criterioavaliacao_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$criterioavaliacao_grid->Page_Terminate();
?>
