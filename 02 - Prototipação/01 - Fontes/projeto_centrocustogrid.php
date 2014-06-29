<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($projeto_centrocusto_grid)) $projeto_centrocusto_grid = new cprojeto_centrocusto_grid();

// Page init
$projeto_centrocusto_grid->Page_Init();

// Page main
$projeto_centrocusto_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$projeto_centrocusto_grid->Page_Render();
?>
<?php if ($projeto_centrocusto->Export == "") { ?>
<script type="text/javascript">

// Page object
var projeto_centrocusto_grid = new ew_Page("projeto_centrocusto_grid");
projeto_centrocusto_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = projeto_centrocusto_grid.PageID; // For backward compatibility

// Form object
var fprojeto_centrocustogrid = new ew_Form("fprojeto_centrocustogrid");
fprojeto_centrocustogrid.FormKeyCountName = '<?php echo $projeto_centrocusto_grid->FormKeyCountName ?>';

// Validate form
fprojeto_centrocustogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_centroCusto_");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto_centrocusto->nu_centroCusto_->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_participacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($projeto_centrocusto->pc_participacao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_pc_participacao");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($projeto_centrocusto->pc_participacao->FldErrMsg()) ?>");

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
fprojeto_centrocustogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_centroCusto_", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pc_participacao", false)) return false;
	return true;
}

// Form_CustomValidate event
fprojeto_centrocustogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojeto_centrocustogrid.ValidateRequired = true;
<?php } else { ?>
fprojeto_centrocustogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprojeto_centrocustogrid.Lists["x_nu_centroCusto_"] = {"LinkField":"x_nu_centroCusto","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_centroCusto","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($projeto_centrocusto->getCurrentMasterTable() == "" && $projeto_centrocusto_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $projeto_centrocusto_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($projeto_centrocusto->CurrentAction == "gridadd") {
	if ($projeto_centrocusto->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$projeto_centrocusto_grid->TotalRecs = $projeto_centrocusto->SelectRecordCount();
			$projeto_centrocusto_grid->Recordset = $projeto_centrocusto_grid->LoadRecordset($projeto_centrocusto_grid->StartRec-1, $projeto_centrocusto_grid->DisplayRecs);
		} else {
			if ($projeto_centrocusto_grid->Recordset = $projeto_centrocusto_grid->LoadRecordset())
				$projeto_centrocusto_grid->TotalRecs = $projeto_centrocusto_grid->Recordset->RecordCount();
		}
		$projeto_centrocusto_grid->StartRec = 1;
		$projeto_centrocusto_grid->DisplayRecs = $projeto_centrocusto_grid->TotalRecs;
	} else {
		$projeto_centrocusto->CurrentFilter = "0=1";
		$projeto_centrocusto_grid->StartRec = 1;
		$projeto_centrocusto_grid->DisplayRecs = $projeto_centrocusto->GridAddRowCount;
	}
	$projeto_centrocusto_grid->TotalRecs = $projeto_centrocusto_grid->DisplayRecs;
	$projeto_centrocusto_grid->StopRec = $projeto_centrocusto_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$projeto_centrocusto_grid->TotalRecs = $projeto_centrocusto->SelectRecordCount();
	} else {
		if ($projeto_centrocusto_grid->Recordset = $projeto_centrocusto_grid->LoadRecordset())
			$projeto_centrocusto_grid->TotalRecs = $projeto_centrocusto_grid->Recordset->RecordCount();
	}
	$projeto_centrocusto_grid->StartRec = 1;
	$projeto_centrocusto_grid->DisplayRecs = $projeto_centrocusto_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$projeto_centrocusto_grid->Recordset = $projeto_centrocusto_grid->LoadRecordset($projeto_centrocusto_grid->StartRec-1, $projeto_centrocusto_grid->DisplayRecs);
}
$projeto_centrocusto_grid->RenderOtherOptions();
?>
<?php $projeto_centrocusto_grid->ShowPageHeader(); ?>
<?php
$projeto_centrocusto_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fprojeto_centrocustogrid" class="ewForm form-horizontal">
<div id="gmp_projeto_centrocusto" class="ewGridMiddlePanel">
<table id="tbl_projeto_centrocustogrid" class="ewTable ewTableSeparate">
<?php echo $projeto_centrocusto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$projeto_centrocusto_grid->RenderListOptions();

// Render list options (header, left)
$projeto_centrocusto_grid->ListOptions->Render("header", "left");
?>
<?php if ($projeto_centrocusto->nu_centroCusto_->Visible) { // nu_centroCusto  ?>
	<?php if ($projeto_centrocusto->SortUrl($projeto_centrocusto->nu_centroCusto_) == "") { ?>
		<td><div id="elh_projeto_centrocusto_nu_centroCusto_" class="projeto_centrocusto_nu_centroCusto_"><div class="ewTableHeaderCaption"><?php echo $projeto_centrocusto->nu_centroCusto_->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_projeto_centrocusto_nu_centroCusto_" class="projeto_centrocusto_nu_centroCusto_">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $projeto_centrocusto->nu_centroCusto_->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($projeto_centrocusto->nu_centroCusto_->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($projeto_centrocusto->nu_centroCusto_->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($projeto_centrocusto->pc_participacao->Visible) { // pc_participacao ?>
	<?php if ($projeto_centrocusto->SortUrl($projeto_centrocusto->pc_participacao) == "") { ?>
		<td><div id="elh_projeto_centrocusto_pc_participacao" class="projeto_centrocusto_pc_participacao"><div class="ewTableHeaderCaption"><?php echo $projeto_centrocusto->pc_participacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_projeto_centrocusto_pc_participacao" class="projeto_centrocusto_pc_participacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $projeto_centrocusto->pc_participacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($projeto_centrocusto->pc_participacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($projeto_centrocusto->pc_participacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$projeto_centrocusto_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$projeto_centrocusto_grid->StartRec = 1;
$projeto_centrocusto_grid->StopRec = $projeto_centrocusto_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($projeto_centrocusto_grid->FormKeyCountName) && ($projeto_centrocusto->CurrentAction == "gridadd" || $projeto_centrocusto->CurrentAction == "gridedit" || $projeto_centrocusto->CurrentAction == "F")) {
		$projeto_centrocusto_grid->KeyCount = $objForm->GetValue($projeto_centrocusto_grid->FormKeyCountName);
		$projeto_centrocusto_grid->StopRec = $projeto_centrocusto_grid->StartRec + $projeto_centrocusto_grid->KeyCount - 1;
	}
}
$projeto_centrocusto_grid->RecCnt = $projeto_centrocusto_grid->StartRec - 1;
if ($projeto_centrocusto_grid->Recordset && !$projeto_centrocusto_grid->Recordset->EOF) {
	$projeto_centrocusto_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $projeto_centrocusto_grid->StartRec > 1)
		$projeto_centrocusto_grid->Recordset->Move($projeto_centrocusto_grid->StartRec - 1);
} elseif (!$projeto_centrocusto->AllowAddDeleteRow && $projeto_centrocusto_grid->StopRec == 0) {
	$projeto_centrocusto_grid->StopRec = $projeto_centrocusto->GridAddRowCount;
}

// Initialize aggregate
$projeto_centrocusto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$projeto_centrocusto->ResetAttrs();
$projeto_centrocusto_grid->RenderRow();
if ($projeto_centrocusto->CurrentAction == "gridadd")
	$projeto_centrocusto_grid->RowIndex = 0;
if ($projeto_centrocusto->CurrentAction == "gridedit")
	$projeto_centrocusto_grid->RowIndex = 0;
while ($projeto_centrocusto_grid->RecCnt < $projeto_centrocusto_grid->StopRec) {
	$projeto_centrocusto_grid->RecCnt++;
	if (intval($projeto_centrocusto_grid->RecCnt) >= intval($projeto_centrocusto_grid->StartRec)) {
		$projeto_centrocusto_grid->RowCnt++;
		if ($projeto_centrocusto->CurrentAction == "gridadd" || $projeto_centrocusto->CurrentAction == "gridedit" || $projeto_centrocusto->CurrentAction == "F") {
			$projeto_centrocusto_grid->RowIndex++;
			$objForm->Index = $projeto_centrocusto_grid->RowIndex;
			if ($objForm->HasValue($projeto_centrocusto_grid->FormActionName))
				$projeto_centrocusto_grid->RowAction = strval($objForm->GetValue($projeto_centrocusto_grid->FormActionName));
			elseif ($projeto_centrocusto->CurrentAction == "gridadd")
				$projeto_centrocusto_grid->RowAction = "insert";
			else
				$projeto_centrocusto_grid->RowAction = "";
		}

		// Set up key count
		$projeto_centrocusto_grid->KeyCount = $projeto_centrocusto_grid->RowIndex;

		// Init row class and style
		$projeto_centrocusto->ResetAttrs();
		$projeto_centrocusto->CssClass = "";
		if ($projeto_centrocusto->CurrentAction == "gridadd") {
			if ($projeto_centrocusto->CurrentMode == "copy") {
				$projeto_centrocusto_grid->LoadRowValues($projeto_centrocusto_grid->Recordset); // Load row values
				$projeto_centrocusto_grid->SetRecordKey($projeto_centrocusto_grid->RowOldKey, $projeto_centrocusto_grid->Recordset); // Set old record key
			} else {
				$projeto_centrocusto_grid->LoadDefaultValues(); // Load default values
				$projeto_centrocusto_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$projeto_centrocusto_grid->LoadRowValues($projeto_centrocusto_grid->Recordset); // Load row values
		}
		$projeto_centrocusto->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($projeto_centrocusto->CurrentAction == "gridadd") // Grid add
			$projeto_centrocusto->RowType = EW_ROWTYPE_ADD; // Render add
		if ($projeto_centrocusto->CurrentAction == "gridadd" && $projeto_centrocusto->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$projeto_centrocusto_grid->RestoreCurrentRowFormValues($projeto_centrocusto_grid->RowIndex); // Restore form values
		if ($projeto_centrocusto->CurrentAction == "gridedit") { // Grid edit
			if ($projeto_centrocusto->EventCancelled) {
				$projeto_centrocusto_grid->RestoreCurrentRowFormValues($projeto_centrocusto_grid->RowIndex); // Restore form values
			}
			if ($projeto_centrocusto_grid->RowAction == "insert")
				$projeto_centrocusto->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$projeto_centrocusto->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($projeto_centrocusto->CurrentAction == "gridedit" && ($projeto_centrocusto->RowType == EW_ROWTYPE_EDIT || $projeto_centrocusto->RowType == EW_ROWTYPE_ADD) && $projeto_centrocusto->EventCancelled) // Update failed
			$projeto_centrocusto_grid->RestoreCurrentRowFormValues($projeto_centrocusto_grid->RowIndex); // Restore form values
		if ($projeto_centrocusto->RowType == EW_ROWTYPE_EDIT) // Edit row
			$projeto_centrocusto_grid->EditRowCnt++;
		if ($projeto_centrocusto->CurrentAction == "F") // Confirm row
			$projeto_centrocusto_grid->RestoreCurrentRowFormValues($projeto_centrocusto_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$projeto_centrocusto->RowAttrs = array_merge($projeto_centrocusto->RowAttrs, array('data-rowindex'=>$projeto_centrocusto_grid->RowCnt, 'id'=>'r' . $projeto_centrocusto_grid->RowCnt . '_projeto_centrocusto', 'data-rowtype'=>$projeto_centrocusto->RowType));

		// Render row
		$projeto_centrocusto_grid->RenderRow();

		// Render list options
		$projeto_centrocusto_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($projeto_centrocusto_grid->RowAction <> "delete" && $projeto_centrocusto_grid->RowAction <> "insertdelete" && !($projeto_centrocusto_grid->RowAction == "insert" && $projeto_centrocusto->CurrentAction == "F" && $projeto_centrocusto_grid->EmptyRow())) {
?>
	<tr<?php echo $projeto_centrocusto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$projeto_centrocusto_grid->ListOptions->Render("body", "left", $projeto_centrocusto_grid->RowCnt);
?>
	<?php if ($projeto_centrocusto->nu_centroCusto_->Visible) { // nu_centroCusto  ?>
		<td<?php echo $projeto_centrocusto->nu_centroCusto_->CellAttributes() ?>>
<?php if ($projeto_centrocusto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($projeto_centrocusto->nu_centroCusto_->getSessionValue() <> "") { ?>
<span<?php echo $projeto_centrocusto->nu_centroCusto_->ViewAttributes() ?>>
<?php echo $projeto_centrocusto->nu_centroCusto_->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_centroCusto_->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_centroCusto_" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_"<?php echo $projeto_centrocusto->nu_centroCusto_->EditAttributes() ?>>
<?php
if (is_array($projeto_centrocusto->nu_centroCusto_->EditValue)) {
	$arwrk = $projeto_centrocusto->nu_centroCusto_->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto_centrocusto->nu_centroCusto_->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$projeto_centrocusto->nu_centroCusto_) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $projeto_centrocusto->nu_centroCusto_->OldValue = "";
?>
</select>
<script type="text/javascript">
fprojeto_centrocustogrid.Lists["x_nu_centroCusto_"].Options = <?php echo (is_array($projeto_centrocusto->nu_centroCusto_->EditValue)) ? ew_ArrayToJson($projeto_centrocusto->nu_centroCusto_->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_nu_centroCusto_" name="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" id="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_centroCusto_->OldValue) ?>">
<?php } ?>
<?php if ($projeto_centrocusto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $projeto_centrocusto->nu_centroCusto_->ViewAttributes() ?>>
<?php echo $projeto_centrocusto->nu_centroCusto_->EditValue ?></span>
<input type="hidden" data-field="x_nu_centroCusto_" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_centroCusto_->CurrentValue) ?>">
<?php } ?>
<?php if ($projeto_centrocusto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $projeto_centrocusto->nu_centroCusto_->ViewAttributes() ?>>
<?php echo $projeto_centrocusto->nu_centroCusto_->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_centroCusto_" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_centroCusto_->FormValue) ?>">
<input type="hidden" data-field="x_nu_centroCusto_" name="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" id="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_centroCusto_->OldValue) ?>">
<?php } ?>
<a id="<?php echo $projeto_centrocusto_grid->PageObjName . "_row_" . $projeto_centrocusto_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($projeto_centrocusto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_projeto" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_projeto" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_projeto->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_projeto" name="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_projeto" id="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_projeto->OldValue) ?>">
<?php } ?>
<?php if ($projeto_centrocusto->RowType == EW_ROWTYPE_EDIT || $projeto_centrocusto->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_projeto" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_projeto" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_projeto->CurrentValue) ?>">
<?php } ?>
	<?php if ($projeto_centrocusto->pc_participacao->Visible) { // pc_participacao ?>
		<td<?php echo $projeto_centrocusto->pc_participacao->CellAttributes() ?>>
<?php if ($projeto_centrocusto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $projeto_centrocusto_grid->RowCnt ?>_projeto_centrocusto_pc_participacao" class="control-group projeto_centrocusto_pc_participacao">
<input type="text" data-field="x_pc_participacao" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" size="30" placeholder="<?php echo $projeto_centrocusto->pc_participacao->PlaceHolder ?>" value="<?php echo $projeto_centrocusto->pc_participacao->EditValue ?>"<?php echo $projeto_centrocusto->pc_participacao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_pc_participacao" name="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" id="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" value="<?php echo ew_HtmlEncode($projeto_centrocusto->pc_participacao->OldValue) ?>">
<?php } ?>
<?php if ($projeto_centrocusto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $projeto_centrocusto_grid->RowCnt ?>_projeto_centrocusto_pc_participacao" class="control-group projeto_centrocusto_pc_participacao">
<input type="text" data-field="x_pc_participacao" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" size="30" placeholder="<?php echo $projeto_centrocusto->pc_participacao->PlaceHolder ?>" value="<?php echo $projeto_centrocusto->pc_participacao->EditValue ?>"<?php echo $projeto_centrocusto->pc_participacao->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($projeto_centrocusto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $projeto_centrocusto->pc_participacao->ViewAttributes() ?>>
<?php echo $projeto_centrocusto->pc_participacao->ListViewValue() ?></span>
<input type="hidden" data-field="x_pc_participacao" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" value="<?php echo ew_HtmlEncode($projeto_centrocusto->pc_participacao->FormValue) ?>">
<input type="hidden" data-field="x_pc_participacao" name="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" id="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" value="<?php echo ew_HtmlEncode($projeto_centrocusto->pc_participacao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $projeto_centrocusto_grid->PageObjName . "_row_" . $projeto_centrocusto_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$projeto_centrocusto_grid->ListOptions->Render("body", "right", $projeto_centrocusto_grid->RowCnt);
?>
	</tr>
<?php if ($projeto_centrocusto->RowType == EW_ROWTYPE_ADD || $projeto_centrocusto->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fprojeto_centrocustogrid.UpdateOpts(<?php echo $projeto_centrocusto_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($projeto_centrocusto->CurrentAction <> "gridadd" || $projeto_centrocusto->CurrentMode == "copy")
		if (!$projeto_centrocusto_grid->Recordset->EOF) $projeto_centrocusto_grid->Recordset->MoveNext();
}
?>
<?php
	if ($projeto_centrocusto->CurrentMode == "add" || $projeto_centrocusto->CurrentMode == "copy" || $projeto_centrocusto->CurrentMode == "edit") {
		$projeto_centrocusto_grid->RowIndex = '$rowindex$';
		$projeto_centrocusto_grid->LoadDefaultValues();

		// Set row properties
		$projeto_centrocusto->ResetAttrs();
		$projeto_centrocusto->RowAttrs = array_merge($projeto_centrocusto->RowAttrs, array('data-rowindex'=>$projeto_centrocusto_grid->RowIndex, 'id'=>'r0_projeto_centrocusto', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($projeto_centrocusto->RowAttrs["class"], "ewTemplate");
		$projeto_centrocusto->RowType = EW_ROWTYPE_ADD;

		// Render row
		$projeto_centrocusto_grid->RenderRow();

		// Render list options
		$projeto_centrocusto_grid->RenderListOptions();
		$projeto_centrocusto_grid->StartRowCnt = 0;
?>
	<tr<?php echo $projeto_centrocusto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$projeto_centrocusto_grid->ListOptions->Render("body", "left", $projeto_centrocusto_grid->RowIndex);
?>
	<?php if ($projeto_centrocusto->nu_centroCusto_->Visible) { // nu_centroCusto  ?>
		<td>
<?php if ($projeto_centrocusto->CurrentAction <> "F") { ?>
<?php if ($projeto_centrocusto->nu_centroCusto_->getSessionValue() <> "") { ?>
<span<?php echo $projeto_centrocusto->nu_centroCusto_->ViewAttributes() ?>>
<?php echo $projeto_centrocusto->nu_centroCusto_->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_centroCusto_->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_centroCusto_" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_"<?php echo $projeto_centrocusto->nu_centroCusto_->EditAttributes() ?>>
<?php
if (is_array($projeto_centrocusto->nu_centroCusto_->EditValue)) {
	$arwrk = $projeto_centrocusto->nu_centroCusto_->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto_centrocusto->nu_centroCusto_->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$projeto_centrocusto->nu_centroCusto_) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $projeto_centrocusto->nu_centroCusto_->OldValue = "";
?>
</select>
<script type="text/javascript">
fprojeto_centrocustogrid.Lists["x_nu_centroCusto_"].Options = <?php echo (is_array($projeto_centrocusto->nu_centroCusto_->EditValue)) ? ew_ArrayToJson($projeto_centrocusto->nu_centroCusto_->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $projeto_centrocusto->nu_centroCusto_->ViewAttributes() ?>>
<?php echo $projeto_centrocusto->nu_centroCusto_->ViewValue ?></span>
<input type="hidden" data-field="x_nu_centroCusto_" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_centroCusto_->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_centroCusto_" name="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" id="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_nu_centroCusto_" value="<?php echo ew_HtmlEncode($projeto_centrocusto->nu_centroCusto_->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($projeto_centrocusto->pc_participacao->Visible) { // pc_participacao ?>
		<td>
<?php if ($projeto_centrocusto->CurrentAction <> "F") { ?>
<input type="text" data-field="x_pc_participacao" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" size="30" placeholder="<?php echo $projeto_centrocusto->pc_participacao->PlaceHolder ?>" value="<?php echo $projeto_centrocusto->pc_participacao->EditValue ?>"<?php echo $projeto_centrocusto->pc_participacao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $projeto_centrocusto->pc_participacao->ViewAttributes() ?>>
<?php echo $projeto_centrocusto->pc_participacao->ViewValue ?></span>
<input type="hidden" data-field="x_pc_participacao" name="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" id="x<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" value="<?php echo ew_HtmlEncode($projeto_centrocusto->pc_participacao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_pc_participacao" name="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" id="o<?php echo $projeto_centrocusto_grid->RowIndex ?>_pc_participacao" value="<?php echo ew_HtmlEncode($projeto_centrocusto->pc_participacao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$projeto_centrocusto_grid->ListOptions->Render("body", "right", $projeto_centrocusto_grid->RowCnt);
?>
<script type="text/javascript">
fprojeto_centrocustogrid.UpdateOpts(<?php echo $projeto_centrocusto_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($projeto_centrocusto->CurrentMode == "add" || $projeto_centrocusto->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $projeto_centrocusto_grid->FormKeyCountName ?>" id="<?php echo $projeto_centrocusto_grid->FormKeyCountName ?>" value="<?php echo $projeto_centrocusto_grid->KeyCount ?>">
<?php echo $projeto_centrocusto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($projeto_centrocusto->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $projeto_centrocusto_grid->FormKeyCountName ?>" id="<?php echo $projeto_centrocusto_grid->FormKeyCountName ?>" value="<?php echo $projeto_centrocusto_grid->KeyCount ?>">
<?php echo $projeto_centrocusto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($projeto_centrocusto->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fprojeto_centrocustogrid">
</div>
<?php

// Close recordset
if ($projeto_centrocusto_grid->Recordset)
	$projeto_centrocusto_grid->Recordset->Close();
?>
<?php if ($projeto_centrocusto_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($projeto_centrocusto_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($projeto_centrocusto->Export == "") { ?>
<script type="text/javascript">
fprojeto_centrocustogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$projeto_centrocusto_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$projeto_centrocusto_grid->Page_Terminate();
?>
