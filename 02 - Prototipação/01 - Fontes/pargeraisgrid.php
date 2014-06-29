<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($pargerais_grid)) $pargerais_grid = new cpargerais_grid();

// Page init
$pargerais_grid->Page_Init();

// Page main
$pargerais_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pargerais_grid->Page_Render();
?>
<?php if ($pargerais->Export == "") { ?>
<script type="text/javascript">

// Page object
var pargerais_grid = new ew_Page("pargerais_grid");
pargerais_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = pargerais_grid.PageID; // For backward compatibility

// Form object
var fpargeraisgrid = new ew_Form("fpargeraisgrid");
fpargeraisgrid.FormKeyCountName = '<?php echo $pargerais_grid->FormKeyCountName ?>';

// Validate form
fpargeraisgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_orgBase");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_orgBase->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_area");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_area->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_usuarioRespAreaTi");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_usuarioRespAreaTi->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_sistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->nu_sistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioOpSistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($pargerais->dt_inicioOpSistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_inicioOpSistema");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pargerais->dt_inicioOpSistema->FldErrMsg()) ?>");

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
fpargeraisgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_orgBase", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_area", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_usuarioRespAreaTi", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_sistema", false)) return false;
	if (ew_ValueChanged(fobj, infix, "dt_inicioOpSistema", false)) return false;
	return true;
}

// Form_CustomValidate event
fpargeraisgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpargeraisgrid.ValidateRequired = true;
<?php } else { ?>
fpargeraisgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpargeraisgrid.Lists["x_nu_orgBase"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisgrid.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":["x_nu_orgBase"],"FilterFields":["x_nu_organizacao"],"Options":[]};
fpargeraisgrid.Lists["x_nu_usuarioRespAreaTi"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraisgrid.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($pargerais->getCurrentMasterTable() == "" && $pargerais_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $pargerais_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($pargerais->CurrentAction == "gridadd") {
	if ($pargerais->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$pargerais_grid->TotalRecs = $pargerais->SelectRecordCount();
			$pargerais_grid->Recordset = $pargerais_grid->LoadRecordset($pargerais_grid->StartRec-1, $pargerais_grid->DisplayRecs);
		} else {
			if ($pargerais_grid->Recordset = $pargerais_grid->LoadRecordset())
				$pargerais_grid->TotalRecs = $pargerais_grid->Recordset->RecordCount();
		}
		$pargerais_grid->StartRec = 1;
		$pargerais_grid->DisplayRecs = $pargerais_grid->TotalRecs;
	} else {
		$pargerais->CurrentFilter = "0=1";
		$pargerais_grid->StartRec = 1;
		$pargerais_grid->DisplayRecs = $pargerais->GridAddRowCount;
	}
	$pargerais_grid->TotalRecs = $pargerais_grid->DisplayRecs;
	$pargerais_grid->StopRec = $pargerais_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$pargerais_grid->TotalRecs = $pargerais->SelectRecordCount();
	} else {
		if ($pargerais_grid->Recordset = $pargerais_grid->LoadRecordset())
			$pargerais_grid->TotalRecs = $pargerais_grid->Recordset->RecordCount();
	}
	$pargerais_grid->StartRec = 1;
	$pargerais_grid->DisplayRecs = $pargerais_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$pargerais_grid->Recordset = $pargerais_grid->LoadRecordset($pargerais_grid->StartRec-1, $pargerais_grid->DisplayRecs);
}
$pargerais_grid->RenderOtherOptions();
?>
<?php $pargerais_grid->ShowPageHeader(); ?>
<?php
$pargerais_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fpargeraisgrid" class="ewForm form-horizontal">
<div id="gmp_pargerais" class="ewGridMiddlePanel">
<table id="tbl_pargeraisgrid" class="ewTable ewTableSeparate">
<?php echo $pargerais->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$pargerais_grid->RenderListOptions();

// Render list options (header, left)
$pargerais_grid->ListOptions->Render("header", "left");
?>
<?php if ($pargerais->nu_orgBase->Visible) { // nu_orgBase ?>
	<?php if ($pargerais->SortUrl($pargerais->nu_orgBase) == "") { ?>
		<td><div id="elh_pargerais_nu_orgBase" class="pargerais_nu_orgBase"><div class="ewTableHeaderCaption"><?php echo $pargerais->nu_orgBase->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_pargerais_nu_orgBase" class="pargerais_nu_orgBase">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->nu_orgBase->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->nu_orgBase->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->nu_orgBase->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pargerais->nu_area->Visible) { // nu_area ?>
	<?php if ($pargerais->SortUrl($pargerais->nu_area) == "") { ?>
		<td><div id="elh_pargerais_nu_area" class="pargerais_nu_area"><div class="ewTableHeaderCaption"><?php echo $pargerais->nu_area->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_pargerais_nu_area" class="pargerais_nu_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->nu_area->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->nu_area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->nu_area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pargerais->nu_usuarioRespAreaTi->Visible) { // nu_usuarioRespAreaTi ?>
	<?php if ($pargerais->SortUrl($pargerais->nu_usuarioRespAreaTi) == "") { ?>
		<td><div id="elh_pargerais_nu_usuarioRespAreaTi" class="pargerais_nu_usuarioRespAreaTi"><div class="ewTableHeaderCaption"><?php echo $pargerais->nu_usuarioRespAreaTi->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_pargerais_nu_usuarioRespAreaTi" class="pargerais_nu_usuarioRespAreaTi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->nu_usuarioRespAreaTi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->nu_usuarioRespAreaTi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->nu_usuarioRespAreaTi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pargerais->nu_sistema->Visible) { // nu_sistema ?>
	<?php if ($pargerais->SortUrl($pargerais->nu_sistema) == "") { ?>
		<td><div id="elh_pargerais_nu_sistema" class="pargerais_nu_sistema"><div class="ewTableHeaderCaption"><?php echo $pargerais->nu_sistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_pargerais_nu_sistema" class="pargerais_nu_sistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->nu_sistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->nu_sistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->nu_sistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pargerais->dt_inicioOpSistema->Visible) { // dt_inicioOpSistema ?>
	<?php if ($pargerais->SortUrl($pargerais->dt_inicioOpSistema) == "") { ?>
		<td><div id="elh_pargerais_dt_inicioOpSistema" class="pargerais_dt_inicioOpSistema"><div class="ewTableHeaderCaption"><?php echo $pargerais->dt_inicioOpSistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_pargerais_dt_inicioOpSistema" class="pargerais_dt_inicioOpSistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->dt_inicioOpSistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->dt_inicioOpSistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->dt_inicioOpSistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pargerais_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$pargerais_grid->StartRec = 1;
$pargerais_grid->StopRec = $pargerais_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pargerais_grid->FormKeyCountName) && ($pargerais->CurrentAction == "gridadd" || $pargerais->CurrentAction == "gridedit" || $pargerais->CurrentAction == "F")) {
		$pargerais_grid->KeyCount = $objForm->GetValue($pargerais_grid->FormKeyCountName);
		$pargerais_grid->StopRec = $pargerais_grid->StartRec + $pargerais_grid->KeyCount - 1;
	}
}
$pargerais_grid->RecCnt = $pargerais_grid->StartRec - 1;
if ($pargerais_grid->Recordset && !$pargerais_grid->Recordset->EOF) {
	$pargerais_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $pargerais_grid->StartRec > 1)
		$pargerais_grid->Recordset->Move($pargerais_grid->StartRec - 1);
} elseif (!$pargerais->AllowAddDeleteRow && $pargerais_grid->StopRec == 0) {
	$pargerais_grid->StopRec = $pargerais->GridAddRowCount;
}

// Initialize aggregate
$pargerais->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pargerais->ResetAttrs();
$pargerais_grid->RenderRow();
if ($pargerais->CurrentAction == "gridadd")
	$pargerais_grid->RowIndex = 0;
if ($pargerais->CurrentAction == "gridedit")
	$pargerais_grid->RowIndex = 0;
while ($pargerais_grid->RecCnt < $pargerais_grid->StopRec) {
	$pargerais_grid->RecCnt++;
	if (intval($pargerais_grid->RecCnt) >= intval($pargerais_grid->StartRec)) {
		$pargerais_grid->RowCnt++;
		if ($pargerais->CurrentAction == "gridadd" || $pargerais->CurrentAction == "gridedit" || $pargerais->CurrentAction == "F") {
			$pargerais_grid->RowIndex++;
			$objForm->Index = $pargerais_grid->RowIndex;
			if ($objForm->HasValue($pargerais_grid->FormActionName))
				$pargerais_grid->RowAction = strval($objForm->GetValue($pargerais_grid->FormActionName));
			elseif ($pargerais->CurrentAction == "gridadd")
				$pargerais_grid->RowAction = "insert";
			else
				$pargerais_grid->RowAction = "";
		}

		// Set up key count
		$pargerais_grid->KeyCount = $pargerais_grid->RowIndex;

		// Init row class and style
		$pargerais->ResetAttrs();
		$pargerais->CssClass = "";
		if ($pargerais->CurrentAction == "gridadd") {
			if ($pargerais->CurrentMode == "copy") {
				$pargerais_grid->LoadRowValues($pargerais_grid->Recordset); // Load row values
				$pargerais_grid->SetRecordKey($pargerais_grid->RowOldKey, $pargerais_grid->Recordset); // Set old record key
			} else {
				$pargerais_grid->LoadDefaultValues(); // Load default values
				$pargerais_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$pargerais_grid->LoadRowValues($pargerais_grid->Recordset); // Load row values
		}
		$pargerais->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pargerais->CurrentAction == "gridadd") // Grid add
			$pargerais->RowType = EW_ROWTYPE_ADD; // Render add
		if ($pargerais->CurrentAction == "gridadd" && $pargerais->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$pargerais_grid->RestoreCurrentRowFormValues($pargerais_grid->RowIndex); // Restore form values
		if ($pargerais->CurrentAction == "gridedit") { // Grid edit
			if ($pargerais->EventCancelled) {
				$pargerais_grid->RestoreCurrentRowFormValues($pargerais_grid->RowIndex); // Restore form values
			}
			if ($pargerais_grid->RowAction == "insert")
				$pargerais->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$pargerais->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($pargerais->CurrentAction == "gridedit" && ($pargerais->RowType == EW_ROWTYPE_EDIT || $pargerais->RowType == EW_ROWTYPE_ADD) && $pargerais->EventCancelled) // Update failed
			$pargerais_grid->RestoreCurrentRowFormValues($pargerais_grid->RowIndex); // Restore form values
		if ($pargerais->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pargerais_grid->EditRowCnt++;
		if ($pargerais->CurrentAction == "F") // Confirm row
			$pargerais_grid->RestoreCurrentRowFormValues($pargerais_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$pargerais->RowAttrs = array_merge($pargerais->RowAttrs, array('data-rowindex'=>$pargerais_grid->RowCnt, 'id'=>'r' . $pargerais_grid->RowCnt . '_pargerais', 'data-rowtype'=>$pargerais->RowType));

		// Render row
		$pargerais_grid->RenderRow();

		// Render list options
		$pargerais_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($pargerais_grid->RowAction <> "delete" && $pargerais_grid->RowAction <> "insertdelete" && !($pargerais_grid->RowAction == "insert" && $pargerais->CurrentAction == "F" && $pargerais_grid->EmptyRow())) {
?>
	<tr<?php echo $pargerais->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pargerais_grid->ListOptions->Render("body", "left", $pargerais_grid->RowCnt);
?>
	<?php if ($pargerais->nu_orgBase->Visible) { // nu_orgBase ?>
		<td<?php echo $pargerais->nu_orgBase->CellAttributes() ?>>
<?php if ($pargerais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($pargerais->nu_orgBase->getSessionValue() <> "") { ?>
<span<?php echo $pargerais->nu_orgBase->ViewAttributes() ?>>
<?php echo $pargerais->nu_orgBase->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" value="<?php echo ew_HtmlEncode($pargerais->nu_orgBase->CurrentValue) ?>">
<?php } else { ?>
<?php $pargerais->nu_orgBase->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $pargerais_grid->RowIndex . "_nu_area']); " . @$pargerais->nu_orgBase->EditAttrs["onchange"]; ?>
<select data-field="x_nu_orgBase" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase"<?php echo $pargerais->nu_orgBase->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_orgBase->EditValue)) {
	$arwrk = $pargerais->nu_orgBase->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_orgBase->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_orgBase->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_orgBase"].Options = <?php echo (is_array($pargerais->nu_orgBase->EditValue)) ? ew_ArrayToJson($pargerais->nu_orgBase->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_nu_orgBase" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" value="<?php echo ew_HtmlEncode($pargerais->nu_orgBase->OldValue) ?>">
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($pargerais->nu_orgBase->getSessionValue() <> "") { ?>
<span<?php echo $pargerais->nu_orgBase->ViewAttributes() ?>>
<?php echo $pargerais->nu_orgBase->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" value="<?php echo ew_HtmlEncode($pargerais->nu_orgBase->CurrentValue) ?>">
<?php } else { ?>
<?php $pargerais->nu_orgBase->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $pargerais_grid->RowIndex . "_nu_area']); " . @$pargerais->nu_orgBase->EditAttrs["onchange"]; ?>
<select data-field="x_nu_orgBase" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase"<?php echo $pargerais->nu_orgBase->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_orgBase->EditValue)) {
	$arwrk = $pargerais->nu_orgBase->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_orgBase->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_orgBase->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_orgBase"].Options = <?php echo (is_array($pargerais->nu_orgBase->EditValue)) ? ew_ArrayToJson($pargerais->nu_orgBase->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pargerais->nu_orgBase->ViewAttributes() ?>>
<?php echo $pargerais->nu_orgBase->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_orgBase" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" value="<?php echo ew_HtmlEncode($pargerais->nu_orgBase->FormValue) ?>">
<input type="hidden" data-field="x_nu_orgBase" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" value="<?php echo ew_HtmlEncode($pargerais->nu_orgBase->OldValue) ?>">
<?php } ?>
<a id="<?php echo $pargerais_grid->PageObjName . "_row_" . $pargerais_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_parametro" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_parametro" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_parametro" value="<?php echo ew_HtmlEncode($pargerais->nu_parametro->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_parametro" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_parametro" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_parametro" value="<?php echo ew_HtmlEncode($pargerais->nu_parametro->OldValue) ?>">
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_EDIT || $pargerais->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_parametro" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_parametro" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_parametro" value="<?php echo ew_HtmlEncode($pargerais->nu_parametro->CurrentValue) ?>">
<?php } ?>
	<?php if ($pargerais->nu_area->Visible) { // nu_area ?>
		<td<?php echo $pargerais->nu_area->CellAttributes() ?>>
<?php if ($pargerais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pargerais_grid->RowCnt ?>_pargerais_nu_area" class="control-group pargerais_nu_area">
<select data-field="x_nu_area" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_area" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_area"<?php echo $pargerais->nu_area->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_area->EditValue)) {
	$arwrk = $pargerais->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_area->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_area->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_area"].Options = <?php echo (is_array($pargerais->nu_area->EditValue)) ? ew_ArrayToJson($pargerais->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_area" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_area" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($pargerais->nu_area->OldValue) ?>">
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pargerais_grid->RowCnt ?>_pargerais_nu_area" class="control-group pargerais_nu_area">
<select data-field="x_nu_area" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_area" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_area"<?php echo $pargerais->nu_area->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_area->EditValue)) {
	$arwrk = $pargerais->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_area->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_area->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_area"].Options = <?php echo (is_array($pargerais->nu_area->EditValue)) ? ew_ArrayToJson($pargerais->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pargerais->nu_area->ViewAttributes() ?>>
<?php echo $pargerais->nu_area->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_area" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_area" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($pargerais->nu_area->FormValue) ?>">
<input type="hidden" data-field="x_nu_area" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_area" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($pargerais->nu_area->OldValue) ?>">
<?php } ?>
<a id="<?php echo $pargerais_grid->PageObjName . "_row_" . $pargerais_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pargerais->nu_usuarioRespAreaTi->Visible) { // nu_usuarioRespAreaTi ?>
		<td<?php echo $pargerais->nu_usuarioRespAreaTi->CellAttributes() ?>>
<?php if ($pargerais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pargerais_grid->RowCnt ?>_pargerais_nu_usuarioRespAreaTi" class="control-group pargerais_nu_usuarioRespAreaTi">
<select data-field="x_nu_usuarioRespAreaTi" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi"<?php echo $pargerais->nu_usuarioRespAreaTi->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_usuarioRespAreaTi->EditValue)) {
	$arwrk = $pargerais->nu_usuarioRespAreaTi->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_usuarioRespAreaTi->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_usuarioRespAreaTi->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_usuarioRespAreaTi"].Options = <?php echo (is_array($pargerais->nu_usuarioRespAreaTi->EditValue)) ? ew_ArrayToJson($pargerais->nu_usuarioRespAreaTi->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_usuarioRespAreaTi" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" value="<?php echo ew_HtmlEncode($pargerais->nu_usuarioRespAreaTi->OldValue) ?>">
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pargerais_grid->RowCnt ?>_pargerais_nu_usuarioRespAreaTi" class="control-group pargerais_nu_usuarioRespAreaTi">
<select data-field="x_nu_usuarioRespAreaTi" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi"<?php echo $pargerais->nu_usuarioRespAreaTi->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_usuarioRespAreaTi->EditValue)) {
	$arwrk = $pargerais->nu_usuarioRespAreaTi->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_usuarioRespAreaTi->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_usuarioRespAreaTi->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_usuarioRespAreaTi"].Options = <?php echo (is_array($pargerais->nu_usuarioRespAreaTi->EditValue)) ? ew_ArrayToJson($pargerais->nu_usuarioRespAreaTi->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pargerais->nu_usuarioRespAreaTi->ViewAttributes() ?>>
<?php echo $pargerais->nu_usuarioRespAreaTi->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_usuarioRespAreaTi" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" value="<?php echo ew_HtmlEncode($pargerais->nu_usuarioRespAreaTi->FormValue) ?>">
<input type="hidden" data-field="x_nu_usuarioRespAreaTi" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" value="<?php echo ew_HtmlEncode($pargerais->nu_usuarioRespAreaTi->OldValue) ?>">
<?php } ?>
<a id="<?php echo $pargerais_grid->PageObjName . "_row_" . $pargerais_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pargerais->nu_sistema->Visible) { // nu_sistema ?>
		<td<?php echo $pargerais->nu_sistema->CellAttributes() ?>>
<?php if ($pargerais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pargerais_grid->RowCnt ?>_pargerais_nu_sistema" class="control-group pargerais_nu_sistema">
<select data-field="x_nu_sistema" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema"<?php echo $pargerais->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_sistema->EditValue)) {
	$arwrk = $pargerais->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pargerais->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_sistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_sistema"].Options = <?php echo (is_array($pargerais->nu_sistema->EditValue)) ? ew_ArrayToJson($pargerais->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_sistema" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($pargerais->nu_sistema->OldValue) ?>">
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pargerais_grid->RowCnt ?>_pargerais_nu_sistema" class="control-group pargerais_nu_sistema">
<select data-field="x_nu_sistema" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema"<?php echo $pargerais->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_sistema->EditValue)) {
	$arwrk = $pargerais->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pargerais->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_sistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_sistema"].Options = <?php echo (is_array($pargerais->nu_sistema->EditValue)) ? ew_ArrayToJson($pargerais->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pargerais->nu_sistema->ViewAttributes() ?>>
<?php echo $pargerais->nu_sistema->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_sistema" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($pargerais->nu_sistema->FormValue) ?>">
<input type="hidden" data-field="x_nu_sistema" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($pargerais->nu_sistema->OldValue) ?>">
<?php } ?>
<a id="<?php echo $pargerais_grid->PageObjName . "_row_" . $pargerais_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pargerais->dt_inicioOpSistema->Visible) { // dt_inicioOpSistema ?>
		<td<?php echo $pargerais->dt_inicioOpSistema->CellAttributes() ?>>
<?php if ($pargerais->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pargerais_grid->RowCnt ?>_pargerais_dt_inicioOpSistema" class="control-group pargerais_dt_inicioOpSistema">
<input type="text" data-field="x_dt_inicioOpSistema" name="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" id="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" placeholder="<?php echo $pargerais->dt_inicioOpSistema->PlaceHolder ?>" value="<?php echo $pargerais->dt_inicioOpSistema->EditValue ?>"<?php echo $pargerais->dt_inicioOpSistema->EditAttributes() ?>>
<?php if (!$pargerais->dt_inicioOpSistema->ReadOnly && !$pargerais->dt_inicioOpSistema->Disabled && @$pargerais->dt_inicioOpSistema->EditAttrs["readonly"] == "" && @$pargerais->dt_inicioOpSistema->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" name="cal_x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fpargeraisgrid", "x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<input type="hidden" data-field="x_dt_inicioOpSistema" name="o<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" id="o<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" value="<?php echo ew_HtmlEncode($pargerais->dt_inicioOpSistema->OldValue) ?>">
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pargerais_grid->RowCnt ?>_pargerais_dt_inicioOpSistema" class="control-group pargerais_dt_inicioOpSistema">
<input type="text" data-field="x_dt_inicioOpSistema" name="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" id="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" placeholder="<?php echo $pargerais->dt_inicioOpSistema->PlaceHolder ?>" value="<?php echo $pargerais->dt_inicioOpSistema->EditValue ?>"<?php echo $pargerais->dt_inicioOpSistema->EditAttributes() ?>>
<?php if (!$pargerais->dt_inicioOpSistema->ReadOnly && !$pargerais->dt_inicioOpSistema->Disabled && @$pargerais->dt_inicioOpSistema->EditAttrs["readonly"] == "" && @$pargerais->dt_inicioOpSistema->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" name="cal_x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fpargeraisgrid", "x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($pargerais->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $pargerais->dt_inicioOpSistema->ViewAttributes() ?>>
<?php echo $pargerais->dt_inicioOpSistema->ListViewValue() ?></span>
<input type="hidden" data-field="x_dt_inicioOpSistema" name="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" id="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" value="<?php echo ew_HtmlEncode($pargerais->dt_inicioOpSistema->FormValue) ?>">
<input type="hidden" data-field="x_dt_inicioOpSistema" name="o<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" id="o<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" value="<?php echo ew_HtmlEncode($pargerais->dt_inicioOpSistema->OldValue) ?>">
<?php } ?>
<a id="<?php echo $pargerais_grid->PageObjName . "_row_" . $pargerais_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$pargerais_grid->ListOptions->Render("body", "right", $pargerais_grid->RowCnt);
?>
	</tr>
<?php if ($pargerais->RowType == EW_ROWTYPE_ADD || $pargerais->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpargeraisgrid.UpdateOpts(<?php echo $pargerais_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($pargerais->CurrentAction <> "gridadd" || $pargerais->CurrentMode == "copy")
		if (!$pargerais_grid->Recordset->EOF) $pargerais_grid->Recordset->MoveNext();
}
?>
<?php
	if ($pargerais->CurrentMode == "add" || $pargerais->CurrentMode == "copy" || $pargerais->CurrentMode == "edit") {
		$pargerais_grid->RowIndex = '$rowindex$';
		$pargerais_grid->LoadDefaultValues();

		// Set row properties
		$pargerais->ResetAttrs();
		$pargerais->RowAttrs = array_merge($pargerais->RowAttrs, array('data-rowindex'=>$pargerais_grid->RowIndex, 'id'=>'r0_pargerais', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($pargerais->RowAttrs["class"], "ewTemplate");
		$pargerais->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pargerais_grid->RenderRow();

		// Render list options
		$pargerais_grid->RenderListOptions();
		$pargerais_grid->StartRowCnt = 0;
?>
	<tr<?php echo $pargerais->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pargerais_grid->ListOptions->Render("body", "left", $pargerais_grid->RowIndex);
?>
	<?php if ($pargerais->nu_orgBase->Visible) { // nu_orgBase ?>
		<td>
<?php if ($pargerais->CurrentAction <> "F") { ?>
<?php if ($pargerais->nu_orgBase->getSessionValue() <> "") { ?>
<span<?php echo $pargerais->nu_orgBase->ViewAttributes() ?>>
<?php echo $pargerais->nu_orgBase->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" value="<?php echo ew_HtmlEncode($pargerais->nu_orgBase->CurrentValue) ?>">
<?php } else { ?>
<?php $pargerais->nu_orgBase->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $pargerais_grid->RowIndex . "_nu_area']); " . @$pargerais->nu_orgBase->EditAttrs["onchange"]; ?>
<select data-field="x_nu_orgBase" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase"<?php echo $pargerais->nu_orgBase->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_orgBase->EditValue)) {
	$arwrk = $pargerais->nu_orgBase->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_orgBase->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_orgBase->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_orgBase"].Options = <?php echo (is_array($pargerais->nu_orgBase->EditValue)) ? ew_ArrayToJson($pargerais->nu_orgBase->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $pargerais->nu_orgBase->ViewAttributes() ?>>
<?php echo $pargerais->nu_orgBase->ViewValue ?></span>
<input type="hidden" data-field="x_nu_orgBase" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" value="<?php echo ew_HtmlEncode($pargerais->nu_orgBase->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_orgBase" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_orgBase" value="<?php echo ew_HtmlEncode($pargerais->nu_orgBase->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pargerais->nu_area->Visible) { // nu_area ?>
		<td>
<?php if ($pargerais->CurrentAction <> "F") { ?>
<select data-field="x_nu_area" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_area" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_area"<?php echo $pargerais->nu_area->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_area->EditValue)) {
	$arwrk = $pargerais->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_area->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_area->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_area"].Options = <?php echo (is_array($pargerais->nu_area->EditValue)) ? ew_ArrayToJson($pargerais->nu_area->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $pargerais->nu_area->ViewAttributes() ?>>
<?php echo $pargerais->nu_area->ViewValue ?></span>
<input type="hidden" data-field="x_nu_area" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_area" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($pargerais->nu_area->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_area" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_area" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_area" value="<?php echo ew_HtmlEncode($pargerais->nu_area->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pargerais->nu_usuarioRespAreaTi->Visible) { // nu_usuarioRespAreaTi ?>
		<td>
<?php if ($pargerais->CurrentAction <> "F") { ?>
<select data-field="x_nu_usuarioRespAreaTi" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi"<?php echo $pargerais->nu_usuarioRespAreaTi->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_usuarioRespAreaTi->EditValue)) {
	$arwrk = $pargerais->nu_usuarioRespAreaTi->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_usuarioRespAreaTi->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_usuarioRespAreaTi->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_usuarioRespAreaTi"].Options = <?php echo (is_array($pargerais->nu_usuarioRespAreaTi->EditValue)) ? ew_ArrayToJson($pargerais->nu_usuarioRespAreaTi->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $pargerais->nu_usuarioRespAreaTi->ViewAttributes() ?>>
<?php echo $pargerais->nu_usuarioRespAreaTi->ViewValue ?></span>
<input type="hidden" data-field="x_nu_usuarioRespAreaTi" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" value="<?php echo ew_HtmlEncode($pargerais->nu_usuarioRespAreaTi->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_usuarioRespAreaTi" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_usuarioRespAreaTi" value="<?php echo ew_HtmlEncode($pargerais->nu_usuarioRespAreaTi->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pargerais->nu_sistema->Visible) { // nu_sistema ?>
		<td>
<?php if ($pargerais->CurrentAction <> "F") { ?>
<select data-field="x_nu_sistema" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema"<?php echo $pargerais->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($pargerais->nu_sistema->EditValue)) {
	$arwrk = $pargerais->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pargerais->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pargerais->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $pargerais->nu_sistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fpargeraisgrid.Lists["x_nu_sistema"].Options = <?php echo (is_array($pargerais->nu_sistema->EditValue)) ? ew_ArrayToJson($pargerais->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $pargerais->nu_sistema->ViewAttributes() ?>>
<?php echo $pargerais->nu_sistema->ViewValue ?></span>
<input type="hidden" data-field="x_nu_sistema" name="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" id="x<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($pargerais->nu_sistema->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_sistema" name="o<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" id="o<?php echo $pargerais_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($pargerais->nu_sistema->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pargerais->dt_inicioOpSistema->Visible) { // dt_inicioOpSistema ?>
		<td>
<?php if ($pargerais->CurrentAction <> "F") { ?>
<input type="text" data-field="x_dt_inicioOpSistema" name="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" id="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" placeholder="<?php echo $pargerais->dt_inicioOpSistema->PlaceHolder ?>" value="<?php echo $pargerais->dt_inicioOpSistema->EditValue ?>"<?php echo $pargerais->dt_inicioOpSistema->EditAttributes() ?>>
<?php if (!$pargerais->dt_inicioOpSistema->ReadOnly && !$pargerais->dt_inicioOpSistema->Disabled && @$pargerais->dt_inicioOpSistema->EditAttrs["readonly"] == "" && @$pargerais->dt_inicioOpSistema->EditAttrs["disabled"] == "") { ?>
<button id="cal_x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" name="cal_x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fpargeraisgrid", "x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema", "%d/%m/%Y");
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $pargerais->dt_inicioOpSistema->ViewAttributes() ?>>
<?php echo $pargerais->dt_inicioOpSistema->ViewValue ?></span>
<input type="hidden" data-field="x_dt_inicioOpSistema" name="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" id="x<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" value="<?php echo ew_HtmlEncode($pargerais->dt_inicioOpSistema->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dt_inicioOpSistema" name="o<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" id="o<?php echo $pargerais_grid->RowIndex ?>_dt_inicioOpSistema" value="<?php echo ew_HtmlEncode($pargerais->dt_inicioOpSistema->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pargerais_grid->ListOptions->Render("body", "right", $pargerais_grid->RowCnt);
?>
<script type="text/javascript">
fpargeraisgrid.UpdateOpts(<?php echo $pargerais_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($pargerais->CurrentMode == "add" || $pargerais->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $pargerais_grid->FormKeyCountName ?>" id="<?php echo $pargerais_grid->FormKeyCountName ?>" value="<?php echo $pargerais_grid->KeyCount ?>">
<?php echo $pargerais_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pargerais->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $pargerais_grid->FormKeyCountName ?>" id="<?php echo $pargerais_grid->FormKeyCountName ?>" value="<?php echo $pargerais_grid->KeyCount ?>">
<?php echo $pargerais_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pargerais->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpargeraisgrid">
</div>
<?php

// Close recordset
if ($pargerais_grid->Recordset)
	$pargerais_grid->Recordset->Close();
?>
<?php if ($pargerais_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($pargerais_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($pargerais->Export == "") { ?>
<script type="text/javascript">
fpargeraisgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$pargerais_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$pargerais_grid->Page_Terminate();
?>
