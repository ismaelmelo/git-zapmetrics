<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($modulo_grid)) $modulo_grid = new cmodulo_grid();

// Page init
$modulo_grid->Page_Init();

// Page main
$modulo_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$modulo_grid->Page_Render();
?>
<?php if ($modulo->Export == "") { ?>
<script type="text/javascript">

// Page object
var modulo_grid = new ew_Page("modulo_grid");
modulo_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = modulo_grid.PageID; // For backward compatibility

// Form object
var fmodulogrid = new ew_Form("fmodulogrid");
fmodulogrid.FormKeyCountName = '<?php echo $modulo_grid->FormKeyCountName ?>';

// Validate form
fmodulogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_stSistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($modulo->nu_stSistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_ordem");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($modulo->nu_ordem->FldErrMsg()) ?>");

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
fmodulogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_modulo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_stSistema", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_ordem", false)) return false;
	return true;
}

// Form_CustomValidate event
fmodulogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fmodulogrid.ValidateRequired = true;
<?php } else { ?>
fmodulogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fmodulogrid.Lists["x_nu_stSistema"] = {"LinkField":"x_nu_stSistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stSistema","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($modulo->getCurrentMasterTable() == "" && $modulo_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $modulo_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($modulo->CurrentAction == "gridadd") {
	if ($modulo->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$modulo_grid->TotalRecs = $modulo->SelectRecordCount();
			$modulo_grid->Recordset = $modulo_grid->LoadRecordset($modulo_grid->StartRec-1, $modulo_grid->DisplayRecs);
		} else {
			if ($modulo_grid->Recordset = $modulo_grid->LoadRecordset())
				$modulo_grid->TotalRecs = $modulo_grid->Recordset->RecordCount();
		}
		$modulo_grid->StartRec = 1;
		$modulo_grid->DisplayRecs = $modulo_grid->TotalRecs;
	} else {
		$modulo->CurrentFilter = "0=1";
		$modulo_grid->StartRec = 1;
		$modulo_grid->DisplayRecs = $modulo->GridAddRowCount;
	}
	$modulo_grid->TotalRecs = $modulo_grid->DisplayRecs;
	$modulo_grid->StopRec = $modulo_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$modulo_grid->TotalRecs = $modulo->SelectRecordCount();
	} else {
		if ($modulo_grid->Recordset = $modulo_grid->LoadRecordset())
			$modulo_grid->TotalRecs = $modulo_grid->Recordset->RecordCount();
	}
	$modulo_grid->StartRec = 1;
	$modulo_grid->DisplayRecs = $modulo_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$modulo_grid->Recordset = $modulo_grid->LoadRecordset($modulo_grid->StartRec-1, $modulo_grid->DisplayRecs);
}
$modulo_grid->RenderOtherOptions();
?>
<?php $modulo_grid->ShowPageHeader(); ?>
<?php
$modulo_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fmodulogrid" class="ewForm form-horizontal">
<div id="gmp_modulo" class="ewGridMiddlePanel">
<table id="tbl_modulogrid" class="ewTable ewTableSeparate">
<?php echo $modulo->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$modulo_grid->RenderListOptions();

// Render list options (header, left)
$modulo_grid->ListOptions->Render("header", "left");
?>
<?php if ($modulo->no_modulo->Visible) { // no_modulo ?>
	<?php if ($modulo->SortUrl($modulo->no_modulo) == "") { ?>
		<td><div id="elh_modulo_no_modulo" class="modulo_no_modulo"><div class="ewTableHeaderCaption"><?php echo $modulo->no_modulo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_modulo_no_modulo" class="modulo_no_modulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $modulo->no_modulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($modulo->no_modulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($modulo->no_modulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($modulo->nu_stSistema->Visible) { // nu_stSistema ?>
	<?php if ($modulo->SortUrl($modulo->nu_stSistema) == "") { ?>
		<td><div id="elh_modulo_nu_stSistema" class="modulo_nu_stSistema"><div class="ewTableHeaderCaption"><?php echo $modulo->nu_stSistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_modulo_nu_stSistema" class="modulo_nu_stSistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $modulo->nu_stSistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($modulo->nu_stSistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($modulo->nu_stSistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($modulo->nu_ordem->Visible) { // nu_ordem ?>
	<?php if ($modulo->SortUrl($modulo->nu_ordem) == "") { ?>
		<td><div id="elh_modulo_nu_ordem" class="modulo_nu_ordem"><div class="ewTableHeaderCaption"><?php echo $modulo->nu_ordem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_modulo_nu_ordem" class="modulo_nu_ordem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $modulo->nu_ordem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($modulo->nu_ordem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($modulo->nu_ordem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$modulo_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$modulo_grid->StartRec = 1;
$modulo_grid->StopRec = $modulo_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($modulo_grid->FormKeyCountName) && ($modulo->CurrentAction == "gridadd" || $modulo->CurrentAction == "gridedit" || $modulo->CurrentAction == "F")) {
		$modulo_grid->KeyCount = $objForm->GetValue($modulo_grid->FormKeyCountName);
		$modulo_grid->StopRec = $modulo_grid->StartRec + $modulo_grid->KeyCount - 1;
	}
}
$modulo_grid->RecCnt = $modulo_grid->StartRec - 1;
if ($modulo_grid->Recordset && !$modulo_grid->Recordset->EOF) {
	$modulo_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $modulo_grid->StartRec > 1)
		$modulo_grid->Recordset->Move($modulo_grid->StartRec - 1);
} elseif (!$modulo->AllowAddDeleteRow && $modulo_grid->StopRec == 0) {
	$modulo_grid->StopRec = $modulo->GridAddRowCount;
}

// Initialize aggregate
$modulo->RowType = EW_ROWTYPE_AGGREGATEINIT;
$modulo->ResetAttrs();
$modulo_grid->RenderRow();
if ($modulo->CurrentAction == "gridadd")
	$modulo_grid->RowIndex = 0;
if ($modulo->CurrentAction == "gridedit")
	$modulo_grid->RowIndex = 0;
while ($modulo_grid->RecCnt < $modulo_grid->StopRec) {
	$modulo_grid->RecCnt++;
	if (intval($modulo_grid->RecCnt) >= intval($modulo_grid->StartRec)) {
		$modulo_grid->RowCnt++;
		if ($modulo->CurrentAction == "gridadd" || $modulo->CurrentAction == "gridedit" || $modulo->CurrentAction == "F") {
			$modulo_grid->RowIndex++;
			$objForm->Index = $modulo_grid->RowIndex;
			if ($objForm->HasValue($modulo_grid->FormActionName))
				$modulo_grid->RowAction = strval($objForm->GetValue($modulo_grid->FormActionName));
			elseif ($modulo->CurrentAction == "gridadd")
				$modulo_grid->RowAction = "insert";
			else
				$modulo_grid->RowAction = "";
		}

		// Set up key count
		$modulo_grid->KeyCount = $modulo_grid->RowIndex;

		// Init row class and style
		$modulo->ResetAttrs();
		$modulo->CssClass = "";
		if ($modulo->CurrentAction == "gridadd") {
			if ($modulo->CurrentMode == "copy") {
				$modulo_grid->LoadRowValues($modulo_grid->Recordset); // Load row values
				$modulo_grid->SetRecordKey($modulo_grid->RowOldKey, $modulo_grid->Recordset); // Set old record key
			} else {
				$modulo_grid->LoadDefaultValues(); // Load default values
				$modulo_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$modulo_grid->LoadRowValues($modulo_grid->Recordset); // Load row values
		}
		$modulo->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($modulo->CurrentAction == "gridadd") // Grid add
			$modulo->RowType = EW_ROWTYPE_ADD; // Render add
		if ($modulo->CurrentAction == "gridadd" && $modulo->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$modulo_grid->RestoreCurrentRowFormValues($modulo_grid->RowIndex); // Restore form values
		if ($modulo->CurrentAction == "gridedit") { // Grid edit
			if ($modulo->EventCancelled) {
				$modulo_grid->RestoreCurrentRowFormValues($modulo_grid->RowIndex); // Restore form values
			}
			if ($modulo_grid->RowAction == "insert")
				$modulo->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$modulo->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($modulo->CurrentAction == "gridedit" && ($modulo->RowType == EW_ROWTYPE_EDIT || $modulo->RowType == EW_ROWTYPE_ADD) && $modulo->EventCancelled) // Update failed
			$modulo_grid->RestoreCurrentRowFormValues($modulo_grid->RowIndex); // Restore form values
		if ($modulo->RowType == EW_ROWTYPE_EDIT) // Edit row
			$modulo_grid->EditRowCnt++;
		if ($modulo->CurrentAction == "F") // Confirm row
			$modulo_grid->RestoreCurrentRowFormValues($modulo_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$modulo->RowAttrs = array_merge($modulo->RowAttrs, array('data-rowindex'=>$modulo_grid->RowCnt, 'id'=>'r' . $modulo_grid->RowCnt . '_modulo', 'data-rowtype'=>$modulo->RowType));

		// Render row
		$modulo_grid->RenderRow();

		// Render list options
		$modulo_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($modulo_grid->RowAction <> "delete" && $modulo_grid->RowAction <> "insertdelete" && !($modulo_grid->RowAction == "insert" && $modulo->CurrentAction == "F" && $modulo_grid->EmptyRow())) {
?>
	<tr<?php echo $modulo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$modulo_grid->ListOptions->Render("body", "left", $modulo_grid->RowCnt);
?>
	<?php if ($modulo->no_modulo->Visible) { // no_modulo ?>
		<td<?php echo $modulo->no_modulo->CellAttributes() ?>>
<?php if ($modulo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $modulo_grid->RowCnt ?>_modulo_no_modulo" class="control-group modulo_no_modulo">
<input type="text" data-field="x_no_modulo" name="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" id="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" size="30" maxlength="120" placeholder="<?php echo $modulo->no_modulo->PlaceHolder ?>" value="<?php echo $modulo->no_modulo->EditValue ?>"<?php echo $modulo->no_modulo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_modulo" name="o<?php echo $modulo_grid->RowIndex ?>_no_modulo" id="o<?php echo $modulo_grid->RowIndex ?>_no_modulo" value="<?php echo ew_HtmlEncode($modulo->no_modulo->OldValue) ?>">
<?php } ?>
<?php if ($modulo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $modulo_grid->RowCnt ?>_modulo_no_modulo" class="control-group modulo_no_modulo">
<input type="text" data-field="x_no_modulo" name="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" id="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" size="30" maxlength="120" placeholder="<?php echo $modulo->no_modulo->PlaceHolder ?>" value="<?php echo $modulo->no_modulo->EditValue ?>"<?php echo $modulo->no_modulo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($modulo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $modulo->no_modulo->ViewAttributes() ?>>
<?php echo $modulo->no_modulo->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_modulo" name="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" id="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" value="<?php echo ew_HtmlEncode($modulo->no_modulo->FormValue) ?>">
<input type="hidden" data-field="x_no_modulo" name="o<?php echo $modulo_grid->RowIndex ?>_no_modulo" id="o<?php echo $modulo_grid->RowIndex ?>_no_modulo" value="<?php echo ew_HtmlEncode($modulo->no_modulo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $modulo_grid->PageObjName . "_row_" . $modulo_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($modulo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_modulo" name="x<?php echo $modulo_grid->RowIndex ?>_nu_modulo" id="x<?php echo $modulo_grid->RowIndex ?>_nu_modulo" value="<?php echo ew_HtmlEncode($modulo->nu_modulo->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_modulo" name="o<?php echo $modulo_grid->RowIndex ?>_nu_modulo" id="o<?php echo $modulo_grid->RowIndex ?>_nu_modulo" value="<?php echo ew_HtmlEncode($modulo->nu_modulo->OldValue) ?>">
<?php } ?>
<?php if ($modulo->RowType == EW_ROWTYPE_EDIT || $modulo->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_modulo" name="x<?php echo $modulo_grid->RowIndex ?>_nu_modulo" id="x<?php echo $modulo_grid->RowIndex ?>_nu_modulo" value="<?php echo ew_HtmlEncode($modulo->nu_modulo->CurrentValue) ?>">
<?php } ?>
	<?php if ($modulo->nu_stSistema->Visible) { // nu_stSistema ?>
		<td<?php echo $modulo->nu_stSistema->CellAttributes() ?>>
<?php if ($modulo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $modulo_grid->RowCnt ?>_modulo_nu_stSistema" class="control-group modulo_nu_stSistema">
<select data-field="x_nu_stSistema" id="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" name="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema"<?php echo $modulo->nu_stSistema->EditAttributes() ?>>
<?php
if (is_array($modulo->nu_stSistema->EditValue)) {
	$arwrk = $modulo->nu_stSistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($modulo->nu_stSistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $modulo->nu_stSistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fmodulogrid.Lists["x_nu_stSistema"].Options = <?php echo (is_array($modulo->nu_stSistema->EditValue)) ? ew_ArrayToJson($modulo->nu_stSistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_stSistema" name="o<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" id="o<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" value="<?php echo ew_HtmlEncode($modulo->nu_stSistema->OldValue) ?>">
<?php } ?>
<?php if ($modulo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $modulo_grid->RowCnt ?>_modulo_nu_stSistema" class="control-group modulo_nu_stSistema">
<select data-field="x_nu_stSistema" id="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" name="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema"<?php echo $modulo->nu_stSistema->EditAttributes() ?>>
<?php
if (is_array($modulo->nu_stSistema->EditValue)) {
	$arwrk = $modulo->nu_stSistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($modulo->nu_stSistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $modulo->nu_stSistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fmodulogrid.Lists["x_nu_stSistema"].Options = <?php echo (is_array($modulo->nu_stSistema->EditValue)) ? ew_ArrayToJson($modulo->nu_stSistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($modulo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $modulo->nu_stSistema->ViewAttributes() ?>>
<?php echo $modulo->nu_stSistema->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_stSistema" name="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" id="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" value="<?php echo ew_HtmlEncode($modulo->nu_stSistema->FormValue) ?>">
<input type="hidden" data-field="x_nu_stSistema" name="o<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" id="o<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" value="<?php echo ew_HtmlEncode($modulo->nu_stSistema->OldValue) ?>">
<?php } ?>
<a id="<?php echo $modulo_grid->PageObjName . "_row_" . $modulo_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($modulo->nu_ordem->Visible) { // nu_ordem ?>
		<td<?php echo $modulo->nu_ordem->CellAttributes() ?>>
<?php if ($modulo->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $modulo_grid->RowCnt ?>_modulo_nu_ordem" class="control-group modulo_nu_ordem">
<input type="text" data-field="x_nu_ordem" name="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" id="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $modulo->nu_ordem->PlaceHolder ?>" value="<?php echo $modulo->nu_ordem->EditValue ?>"<?php echo $modulo->nu_ordem->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $modulo_grid->RowIndex ?>_nu_ordem" id="o<?php echo $modulo_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($modulo->nu_ordem->OldValue) ?>">
<?php } ?>
<?php if ($modulo->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $modulo_grid->RowCnt ?>_modulo_nu_ordem" class="control-group modulo_nu_ordem">
<input type="text" data-field="x_nu_ordem" name="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" id="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $modulo->nu_ordem->PlaceHolder ?>" value="<?php echo $modulo->nu_ordem->EditValue ?>"<?php echo $modulo->nu_ordem->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($modulo->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $modulo->nu_ordem->ViewAttributes() ?>>
<?php echo $modulo->nu_ordem->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_ordem" name="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" id="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($modulo->nu_ordem->FormValue) ?>">
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $modulo_grid->RowIndex ?>_nu_ordem" id="o<?php echo $modulo_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($modulo->nu_ordem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $modulo_grid->PageObjName . "_row_" . $modulo_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$modulo_grid->ListOptions->Render("body", "right", $modulo_grid->RowCnt);
?>
	</tr>
<?php if ($modulo->RowType == EW_ROWTYPE_ADD || $modulo->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fmodulogrid.UpdateOpts(<?php echo $modulo_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($modulo->CurrentAction <> "gridadd" || $modulo->CurrentMode == "copy")
		if (!$modulo_grid->Recordset->EOF) $modulo_grid->Recordset->MoveNext();
}
?>
<?php
	if ($modulo->CurrentMode == "add" || $modulo->CurrentMode == "copy" || $modulo->CurrentMode == "edit") {
		$modulo_grid->RowIndex = '$rowindex$';
		$modulo_grid->LoadDefaultValues();

		// Set row properties
		$modulo->ResetAttrs();
		$modulo->RowAttrs = array_merge($modulo->RowAttrs, array('data-rowindex'=>$modulo_grid->RowIndex, 'id'=>'r0_modulo', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($modulo->RowAttrs["class"], "ewTemplate");
		$modulo->RowType = EW_ROWTYPE_ADD;

		// Render row
		$modulo_grid->RenderRow();

		// Render list options
		$modulo_grid->RenderListOptions();
		$modulo_grid->StartRowCnt = 0;
?>
	<tr<?php echo $modulo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$modulo_grid->ListOptions->Render("body", "left", $modulo_grid->RowIndex);
?>
	<?php if ($modulo->no_modulo->Visible) { // no_modulo ?>
		<td>
<?php if ($modulo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_modulo" name="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" id="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" size="30" maxlength="120" placeholder="<?php echo $modulo->no_modulo->PlaceHolder ?>" value="<?php echo $modulo->no_modulo->EditValue ?>"<?php echo $modulo->no_modulo->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $modulo->no_modulo->ViewAttributes() ?>>
<?php echo $modulo->no_modulo->ViewValue ?></span>
<input type="hidden" data-field="x_no_modulo" name="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" id="x<?php echo $modulo_grid->RowIndex ?>_no_modulo" value="<?php echo ew_HtmlEncode($modulo->no_modulo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_modulo" name="o<?php echo $modulo_grid->RowIndex ?>_no_modulo" id="o<?php echo $modulo_grid->RowIndex ?>_no_modulo" value="<?php echo ew_HtmlEncode($modulo->no_modulo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($modulo->nu_stSistema->Visible) { // nu_stSistema ?>
		<td>
<?php if ($modulo->CurrentAction <> "F") { ?>
<select data-field="x_nu_stSistema" id="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" name="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema"<?php echo $modulo->nu_stSistema->EditAttributes() ?>>
<?php
if (is_array($modulo->nu_stSistema->EditValue)) {
	$arwrk = $modulo->nu_stSistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($modulo->nu_stSistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $modulo->nu_stSistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fmodulogrid.Lists["x_nu_stSistema"].Options = <?php echo (is_array($modulo->nu_stSistema->EditValue)) ? ew_ArrayToJson($modulo->nu_stSistema->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $modulo->nu_stSistema->ViewAttributes() ?>>
<?php echo $modulo->nu_stSistema->ViewValue ?></span>
<input type="hidden" data-field="x_nu_stSistema" name="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" id="x<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" value="<?php echo ew_HtmlEncode($modulo->nu_stSistema->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_stSistema" name="o<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" id="o<?php echo $modulo_grid->RowIndex ?>_nu_stSistema" value="<?php echo ew_HtmlEncode($modulo->nu_stSistema->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($modulo->nu_ordem->Visible) { // nu_ordem ?>
		<td>
<?php if ($modulo->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_ordem" name="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" id="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" size="30" placeholder="<?php echo $modulo->nu_ordem->PlaceHolder ?>" value="<?php echo $modulo->nu_ordem->EditValue ?>"<?php echo $modulo->nu_ordem->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $modulo->nu_ordem->ViewAttributes() ?>>
<?php echo $modulo->nu_ordem->ViewValue ?></span>
<input type="hidden" data-field="x_nu_ordem" name="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" id="x<?php echo $modulo_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($modulo->nu_ordem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_ordem" name="o<?php echo $modulo_grid->RowIndex ?>_nu_ordem" id="o<?php echo $modulo_grid->RowIndex ?>_nu_ordem" value="<?php echo ew_HtmlEncode($modulo->nu_ordem->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$modulo_grid->ListOptions->Render("body", "right", $modulo_grid->RowCnt);
?>
<script type="text/javascript">
fmodulogrid.UpdateOpts(<?php echo $modulo_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($modulo->CurrentMode == "add" || $modulo->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $modulo_grid->FormKeyCountName ?>" id="<?php echo $modulo_grid->FormKeyCountName ?>" value="<?php echo $modulo_grid->KeyCount ?>">
<?php echo $modulo_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($modulo->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $modulo_grid->FormKeyCountName ?>" id="<?php echo $modulo_grid->FormKeyCountName ?>" value="<?php echo $modulo_grid->KeyCount ?>">
<?php echo $modulo_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($modulo->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fmodulogrid">
</div>
<?php

// Close recordset
if ($modulo_grid->Recordset)
	$modulo_grid->Recordset->Close();
?>
<?php if ($modulo_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($modulo_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($modulo->Export == "") { ?>
<script type="text/javascript">
fmodulogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$modulo_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$modulo_grid->Page_Terminate();
?>
