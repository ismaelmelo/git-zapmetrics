<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($parsisp_versao_grid)) $parsisp_versao_grid = new cparsisp_versao_grid();

// Page init
$parsisp_versao_grid->Page_Init();

// Page main
$parsisp_versao_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parsisp_versao_grid->Page_Render();
?>
<?php if ($parsisp_versao->Export == "") { ?>
<script type="text/javascript">

// Page object
var parsisp_versao_grid = new ew_Page("parsisp_versao_grid");
parsisp_versao_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = parsisp_versao_grid.PageID; // For backward compatibility

// Form object
var fparsisp_versaogrid = new ew_Form("fparsisp_versaogrid");
fparsisp_versaogrid.FormKeyCountName = '<?php echo $parsisp_versao_grid->FormKeyCountName ?>';

// Validate form
fparsisp_versaogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_parSisp");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($parsisp_versao->nu_parSisp->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($parsisp_versao->nu_versao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_parSisp");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($parsisp_versao->vr_parSisp->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_parSisp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parsisp_versao->vr_parSisp->FldErrMsg()) ?>");

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
fparsisp_versaogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_parSisp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_versao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_parSisp", false)) return false;
	return true;
}

// Form_CustomValidate event
fparsisp_versaogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparsisp_versaogrid.ValidateRequired = true;
<?php } else { ?>
fparsisp_versaogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fparsisp_versaogrid.Lists["x_nu_parSisp"] = {"LinkField":"x_nu_parSisp","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_parSisp","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fparsisp_versaogrid.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($parsisp_versao->getCurrentMasterTable() == "" && $parsisp_versao_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $parsisp_versao_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($parsisp_versao->CurrentAction == "gridadd") {
	if ($parsisp_versao->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$parsisp_versao_grid->TotalRecs = $parsisp_versao->SelectRecordCount();
			$parsisp_versao_grid->Recordset = $parsisp_versao_grid->LoadRecordset($parsisp_versao_grid->StartRec-1, $parsisp_versao_grid->DisplayRecs);
		} else {
			if ($parsisp_versao_grid->Recordset = $parsisp_versao_grid->LoadRecordset())
				$parsisp_versao_grid->TotalRecs = $parsisp_versao_grid->Recordset->RecordCount();
		}
		$parsisp_versao_grid->StartRec = 1;
		$parsisp_versao_grid->DisplayRecs = $parsisp_versao_grid->TotalRecs;
	} else {
		$parsisp_versao->CurrentFilter = "0=1";
		$parsisp_versao_grid->StartRec = 1;
		$parsisp_versao_grid->DisplayRecs = $parsisp_versao->GridAddRowCount;
	}
	$parsisp_versao_grid->TotalRecs = $parsisp_versao_grid->DisplayRecs;
	$parsisp_versao_grid->StopRec = $parsisp_versao_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$parsisp_versao_grid->TotalRecs = $parsisp_versao->SelectRecordCount();
	} else {
		if ($parsisp_versao_grid->Recordset = $parsisp_versao_grid->LoadRecordset())
			$parsisp_versao_grid->TotalRecs = $parsisp_versao_grid->Recordset->RecordCount();
	}
	$parsisp_versao_grid->StartRec = 1;
	$parsisp_versao_grid->DisplayRecs = $parsisp_versao_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$parsisp_versao_grid->Recordset = $parsisp_versao_grid->LoadRecordset($parsisp_versao_grid->StartRec-1, $parsisp_versao_grid->DisplayRecs);
}
$parsisp_versao_grid->RenderOtherOptions();
?>
<?php $parsisp_versao_grid->ShowPageHeader(); ?>
<?php
$parsisp_versao_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fparsisp_versaogrid" class="ewForm form-horizontal">
<div id="gmp_parsisp_versao" class="ewGridMiddlePanel">
<table id="tbl_parsisp_versaogrid" class="ewTable ewTableSeparate">
<?php echo $parsisp_versao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$parsisp_versao_grid->RenderListOptions();

// Render list options (header, left)
$parsisp_versao_grid->ListOptions->Render("header", "left");
?>
<?php if ($parsisp_versao->nu_parSisp->Visible) { // nu_parSisp ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->nu_parSisp) == "") { ?>
		<td><div id="elh_parsisp_versao_nu_parSisp" class="parsisp_versao_nu_parSisp"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_parSisp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_parsisp_versao_nu_parSisp" class="parsisp_versao_nu_parSisp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_parSisp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->nu_parSisp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->nu_parSisp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($parsisp_versao->nu_versao->Visible) { // nu_versao ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->nu_versao) == "") { ?>
		<td><div id="elh_parsisp_versao_nu_versao" class="parsisp_versao_nu_versao"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_parsisp_versao_nu_versao" class="parsisp_versao_nu_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->nu_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->nu_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($parsisp_versao->vr_parSisp->Visible) { // vr_parSisp ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->vr_parSisp) == "") { ?>
		<td><div id="elh_parsisp_versao_vr_parSisp" class="parsisp_versao_vr_parSisp"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->vr_parSisp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_parsisp_versao_vr_parSisp" class="parsisp_versao_vr_parSisp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->vr_parSisp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->vr_parSisp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->vr_parSisp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($parsisp_versao->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->nu_usuarioResp) == "") { ?>
		<td><div id="elh_parsisp_versao_nu_usuarioResp" class="parsisp_versao_nu_usuarioResp"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_usuarioResp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_parsisp_versao_nu_usuarioResp" class="parsisp_versao_nu_usuarioResp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_usuarioResp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->nu_usuarioResp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->nu_usuarioResp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($parsisp_versao->dh_inclusao->Visible) { // dh_inclusao ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->dh_inclusao) == "") { ?>
		<td><div id="elh_parsisp_versao_dh_inclusao" class="parsisp_versao_dh_inclusao"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->dh_inclusao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_parsisp_versao_dh_inclusao" class="parsisp_versao_dh_inclusao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->dh_inclusao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->dh_inclusao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->dh_inclusao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$parsisp_versao_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$parsisp_versao_grid->StartRec = 1;
$parsisp_versao_grid->StopRec = $parsisp_versao_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($parsisp_versao_grid->FormKeyCountName) && ($parsisp_versao->CurrentAction == "gridadd" || $parsisp_versao->CurrentAction == "gridedit" || $parsisp_versao->CurrentAction == "F")) {
		$parsisp_versao_grid->KeyCount = $objForm->GetValue($parsisp_versao_grid->FormKeyCountName);
		$parsisp_versao_grid->StopRec = $parsisp_versao_grid->StartRec + $parsisp_versao_grid->KeyCount - 1;
	}
}
$parsisp_versao_grid->RecCnt = $parsisp_versao_grid->StartRec - 1;
if ($parsisp_versao_grid->Recordset && !$parsisp_versao_grid->Recordset->EOF) {
	$parsisp_versao_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $parsisp_versao_grid->StartRec > 1)
		$parsisp_versao_grid->Recordset->Move($parsisp_versao_grid->StartRec - 1);
} elseif (!$parsisp_versao->AllowAddDeleteRow && $parsisp_versao_grid->StopRec == 0) {
	$parsisp_versao_grid->StopRec = $parsisp_versao->GridAddRowCount;
}

// Initialize aggregate
$parsisp_versao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$parsisp_versao->ResetAttrs();
$parsisp_versao_grid->RenderRow();
if ($parsisp_versao->CurrentAction == "gridadd")
	$parsisp_versao_grid->RowIndex = 0;
if ($parsisp_versao->CurrentAction == "gridedit")
	$parsisp_versao_grid->RowIndex = 0;
while ($parsisp_versao_grid->RecCnt < $parsisp_versao_grid->StopRec) {
	$parsisp_versao_grid->RecCnt++;
	if (intval($parsisp_versao_grid->RecCnt) >= intval($parsisp_versao_grid->StartRec)) {
		$parsisp_versao_grid->RowCnt++;
		if ($parsisp_versao->CurrentAction == "gridadd" || $parsisp_versao->CurrentAction == "gridedit" || $parsisp_versao->CurrentAction == "F") {
			$parsisp_versao_grid->RowIndex++;
			$objForm->Index = $parsisp_versao_grid->RowIndex;
			if ($objForm->HasValue($parsisp_versao_grid->FormActionName))
				$parsisp_versao_grid->RowAction = strval($objForm->GetValue($parsisp_versao_grid->FormActionName));
			elseif ($parsisp_versao->CurrentAction == "gridadd")
				$parsisp_versao_grid->RowAction = "insert";
			else
				$parsisp_versao_grid->RowAction = "";
		}

		// Set up key count
		$parsisp_versao_grid->KeyCount = $parsisp_versao_grid->RowIndex;

		// Init row class and style
		$parsisp_versao->ResetAttrs();
		$parsisp_versao->CssClass = "";
		if ($parsisp_versao->CurrentAction == "gridadd") {
			if ($parsisp_versao->CurrentMode == "copy") {
				$parsisp_versao_grid->LoadRowValues($parsisp_versao_grid->Recordset); // Load row values
				$parsisp_versao_grid->SetRecordKey($parsisp_versao_grid->RowOldKey, $parsisp_versao_grid->Recordset); // Set old record key
			} else {
				$parsisp_versao_grid->LoadDefaultValues(); // Load default values
				$parsisp_versao_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$parsisp_versao_grid->LoadRowValues($parsisp_versao_grid->Recordset); // Load row values
		}
		$parsisp_versao->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($parsisp_versao->CurrentAction == "gridadd") // Grid add
			$parsisp_versao->RowType = EW_ROWTYPE_ADD; // Render add
		if ($parsisp_versao->CurrentAction == "gridadd" && $parsisp_versao->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$parsisp_versao_grid->RestoreCurrentRowFormValues($parsisp_versao_grid->RowIndex); // Restore form values
		if ($parsisp_versao->CurrentAction == "gridedit") { // Grid edit
			if ($parsisp_versao->EventCancelled) {
				$parsisp_versao_grid->RestoreCurrentRowFormValues($parsisp_versao_grid->RowIndex); // Restore form values
			}
			if ($parsisp_versao_grid->RowAction == "insert")
				$parsisp_versao->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$parsisp_versao->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($parsisp_versao->CurrentAction == "gridedit" && ($parsisp_versao->RowType == EW_ROWTYPE_EDIT || $parsisp_versao->RowType == EW_ROWTYPE_ADD) && $parsisp_versao->EventCancelled) // Update failed
			$parsisp_versao_grid->RestoreCurrentRowFormValues($parsisp_versao_grid->RowIndex); // Restore form values
		if ($parsisp_versao->RowType == EW_ROWTYPE_EDIT) // Edit row
			$parsisp_versao_grid->EditRowCnt++;
		if ($parsisp_versao->CurrentAction == "F") // Confirm row
			$parsisp_versao_grid->RestoreCurrentRowFormValues($parsisp_versao_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$parsisp_versao->RowAttrs = array_merge($parsisp_versao->RowAttrs, array('data-rowindex'=>$parsisp_versao_grid->RowCnt, 'id'=>'r' . $parsisp_versao_grid->RowCnt . '_parsisp_versao', 'data-rowtype'=>$parsisp_versao->RowType));

		// Render row
		$parsisp_versao_grid->RenderRow();

		// Render list options
		$parsisp_versao_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($parsisp_versao_grid->RowAction <> "delete" && $parsisp_versao_grid->RowAction <> "insertdelete" && !($parsisp_versao_grid->RowAction == "insert" && $parsisp_versao->CurrentAction == "F" && $parsisp_versao_grid->EmptyRow())) {
?>
	<tr<?php echo $parsisp_versao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$parsisp_versao_grid->ListOptions->Render("body", "left", $parsisp_versao_grid->RowCnt);
?>
	<?php if ($parsisp_versao->nu_parSisp->Visible) { // nu_parSisp ?>
		<td<?php echo $parsisp_versao->nu_parSisp->CellAttributes() ?>>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($parsisp_versao->nu_parSisp->getSessionValue() <> "") { ?>
<span<?php echo $parsisp_versao->nu_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_parSisp->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_parSisp->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp"<?php echo $parsisp_versao->nu_parSisp->EditAttributes() ?>>
<?php
if (is_array($parsisp_versao->nu_parSisp->EditValue)) {
	$arwrk = $parsisp_versao->nu_parSisp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($parsisp_versao->nu_parSisp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $parsisp_versao->nu_parSisp->OldValue = "";
?>
</select>
<script type="text/javascript">
fparsisp_versaogrid.Lists["x_nu_parSisp"].Options = <?php echo (is_array($parsisp_versao->nu_parSisp->EditValue)) ? ew_ArrayToJson($parsisp_versao->nu_parSisp->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_nu_parSisp" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_parSisp->OldValue) ?>">
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $parsisp_versao->nu_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_parSisp->EditValue ?></span>
<input type="hidden" data-field="x_nu_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_parSisp->CurrentValue) ?>">
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $parsisp_versao->nu_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_parSisp->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_parSisp->FormValue) ?>">
<input type="hidden" data-field="x_nu_parSisp" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_parSisp->OldValue) ?>">
<?php } ?>
<a id="<?php echo $parsisp_versao_grid->PageObjName . "_row_" . $parsisp_versao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($parsisp_versao->nu_versao->Visible) { // nu_versao ?>
		<td<?php echo $parsisp_versao->nu_versao->CellAttributes() ?>>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parsisp_versao_grid->RowCnt ?>_parsisp_versao_nu_versao" class="control-group parsisp_versao_nu_versao">
<input type="text" data-field="x_nu_versao" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" size="30" placeholder="<?php echo $parsisp_versao->nu_versao->PlaceHolder ?>" value="<?php echo $parsisp_versao->nu_versao->EditValue ?>"<?php echo $parsisp_versao->nu_versao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_versao->OldValue) ?>">
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parsisp_versao_grid->RowCnt ?>_parsisp_versao_nu_versao" class="control-group parsisp_versao_nu_versao">
<span<?php echo $parsisp_versao->nu_versao->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_versao->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_versao->CurrentValue) ?>">
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $parsisp_versao->nu_versao->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_versao->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_versao->FormValue) ?>">
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_versao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $parsisp_versao_grid->PageObjName . "_row_" . $parsisp_versao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($parsisp_versao->vr_parSisp->Visible) { // vr_parSisp ?>
		<td<?php echo $parsisp_versao->vr_parSisp->CellAttributes() ?>>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $parsisp_versao_grid->RowCnt ?>_parsisp_versao_vr_parSisp" class="control-group parsisp_versao_vr_parSisp">
<input type="text" data-field="x_vr_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" size="30" maxlength="50" placeholder="<?php echo $parsisp_versao->vr_parSisp->PlaceHolder ?>" value="<?php echo $parsisp_versao->vr_parSisp->EditValue ?>"<?php echo $parsisp_versao->vr_parSisp->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_parSisp" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->vr_parSisp->OldValue) ?>">
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $parsisp_versao_grid->RowCnt ?>_parsisp_versao_vr_parSisp" class="control-group parsisp_versao_vr_parSisp">
<input type="text" data-field="x_vr_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" size="30" maxlength="50" placeholder="<?php echo $parsisp_versao->vr_parSisp->PlaceHolder ?>" value="<?php echo $parsisp_versao->vr_parSisp->EditValue ?>"<?php echo $parsisp_versao->vr_parSisp->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $parsisp_versao->vr_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->vr_parSisp->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->vr_parSisp->FormValue) ?>">
<input type="hidden" data-field="x_vr_parSisp" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->vr_parSisp->OldValue) ?>">
<?php } ?>
<a id="<?php echo $parsisp_versao_grid->PageObjName . "_row_" . $parsisp_versao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($parsisp_versao->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
		<td<?php echo $parsisp_versao->nu_usuarioResp->CellAttributes() ?>>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_usuarioResp" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_usuarioResp->OldValue) ?>">
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $parsisp_versao->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_usuarioResp->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_usuarioResp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_usuarioResp->FormValue) ?>">
<input type="hidden" data-field="x_nu_usuarioResp" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_usuarioResp->OldValue) ?>">
<?php } ?>
<a id="<?php echo $parsisp_versao_grid->PageObjName . "_row_" . $parsisp_versao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($parsisp_versao->dh_inclusao->Visible) { // dh_inclusao ?>
		<td<?php echo $parsisp_versao->dh_inclusao->CellAttributes() ?>>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($parsisp_versao->dh_inclusao->OldValue) ?>">
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $parsisp_versao->dh_inclusao->ViewAttributes() ?>>
<?php echo $parsisp_versao->dh_inclusao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dh_inclusao" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($parsisp_versao->dh_inclusao->FormValue) ?>">
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($parsisp_versao->dh_inclusao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $parsisp_versao_grid->PageObjName . "_row_" . $parsisp_versao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$parsisp_versao_grid->ListOptions->Render("body", "right", $parsisp_versao_grid->RowCnt);
?>
	</tr>
<?php if ($parsisp_versao->RowType == EW_ROWTYPE_ADD || $parsisp_versao->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fparsisp_versaogrid.UpdateOpts(<?php echo $parsisp_versao_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($parsisp_versao->CurrentAction <> "gridadd" || $parsisp_versao->CurrentMode == "copy")
		if (!$parsisp_versao_grid->Recordset->EOF) $parsisp_versao_grid->Recordset->MoveNext();
}
?>
<?php
	if ($parsisp_versao->CurrentMode == "add" || $parsisp_versao->CurrentMode == "copy" || $parsisp_versao->CurrentMode == "edit") {
		$parsisp_versao_grid->RowIndex = '$rowindex$';
		$parsisp_versao_grid->LoadDefaultValues();

		// Set row properties
		$parsisp_versao->ResetAttrs();
		$parsisp_versao->RowAttrs = array_merge($parsisp_versao->RowAttrs, array('data-rowindex'=>$parsisp_versao_grid->RowIndex, 'id'=>'r0_parsisp_versao', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($parsisp_versao->RowAttrs["class"], "ewTemplate");
		$parsisp_versao->RowType = EW_ROWTYPE_ADD;

		// Render row
		$parsisp_versao_grid->RenderRow();

		// Render list options
		$parsisp_versao_grid->RenderListOptions();
		$parsisp_versao_grid->StartRowCnt = 0;
?>
	<tr<?php echo $parsisp_versao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$parsisp_versao_grid->ListOptions->Render("body", "left", $parsisp_versao_grid->RowIndex);
?>
	<?php if ($parsisp_versao->nu_parSisp->Visible) { // nu_parSisp ?>
		<td>
<?php if ($parsisp_versao->CurrentAction <> "F") { ?>
<?php if ($parsisp_versao->nu_parSisp->getSessionValue() <> "") { ?>
<span<?php echo $parsisp_versao->nu_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_parSisp->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_parSisp->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp"<?php echo $parsisp_versao->nu_parSisp->EditAttributes() ?>>
<?php
if (is_array($parsisp_versao->nu_parSisp->EditValue)) {
	$arwrk = $parsisp_versao->nu_parSisp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($parsisp_versao->nu_parSisp->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $parsisp_versao->nu_parSisp->OldValue = "";
?>
</select>
<script type="text/javascript">
fparsisp_versaogrid.Lists["x_nu_parSisp"].Options = <?php echo (is_array($parsisp_versao->nu_parSisp->EditValue)) ? ew_ArrayToJson($parsisp_versao->nu_parSisp->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $parsisp_versao->nu_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_parSisp->ViewValue ?></span>
<input type="hidden" data-field="x_nu_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_parSisp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_parSisp" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_parSisp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parsisp_versao->nu_versao->Visible) { // nu_versao ?>
		<td>
<?php if ($parsisp_versao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_versao" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" size="30" placeholder="<?php echo $parsisp_versao->nu_versao->PlaceHolder ?>" value="<?php echo $parsisp_versao->nu_versao->EditValue ?>"<?php echo $parsisp_versao->nu_versao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $parsisp_versao->nu_versao->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_versao->ViewValue ?></span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_versao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_versao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parsisp_versao->vr_parSisp->Visible) { // vr_parSisp ?>
		<td>
<?php if ($parsisp_versao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" size="30" maxlength="50" placeholder="<?php echo $parsisp_versao->vr_parSisp->PlaceHolder ?>" value="<?php echo $parsisp_versao->vr_parSisp->EditValue ?>"<?php echo $parsisp_versao->vr_parSisp->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $parsisp_versao->vr_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->vr_parSisp->ViewValue ?></span>
<input type="hidden" data-field="x_vr_parSisp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->vr_parSisp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_parSisp" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_vr_parSisp" value="<?php echo ew_HtmlEncode($parsisp_versao->vr_parSisp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parsisp_versao->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
		<td>
<?php if ($parsisp_versao->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $parsisp_versao->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_usuarioResp->ViewValue ?></span>
<input type="hidden" data-field="x_nu_usuarioResp" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_usuarioResp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_usuarioResp" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($parsisp_versao->nu_usuarioResp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($parsisp_versao->dh_inclusao->Visible) { // dh_inclusao ?>
		<td>
<?php if ($parsisp_versao->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $parsisp_versao->dh_inclusao->ViewAttributes() ?>>
<?php echo $parsisp_versao->dh_inclusao->ViewValue ?></span>
<input type="hidden" data-field="x_dh_inclusao" name="x<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" id="x<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($parsisp_versao->dh_inclusao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dh_inclusao" name="o<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" id="o<?php echo $parsisp_versao_grid->RowIndex ?>_dh_inclusao" value="<?php echo ew_HtmlEncode($parsisp_versao->dh_inclusao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$parsisp_versao_grid->ListOptions->Render("body", "right", $parsisp_versao_grid->RowCnt);
?>
<script type="text/javascript">
fparsisp_versaogrid.UpdateOpts(<?php echo $parsisp_versao_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($parsisp_versao->CurrentMode == "add" || $parsisp_versao->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $parsisp_versao_grid->FormKeyCountName ?>" id="<?php echo $parsisp_versao_grid->FormKeyCountName ?>" value="<?php echo $parsisp_versao_grid->KeyCount ?>">
<?php echo $parsisp_versao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($parsisp_versao->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $parsisp_versao_grid->FormKeyCountName ?>" id="<?php echo $parsisp_versao_grid->FormKeyCountName ?>" value="<?php echo $parsisp_versao_grid->KeyCount ?>">
<?php echo $parsisp_versao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($parsisp_versao->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fparsisp_versaogrid">
</div>
<?php

// Close recordset
if ($parsisp_versao_grid->Recordset)
	$parsisp_versao_grid->Recordset->Close();
?>
<?php if ($parsisp_versao_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($parsisp_versao_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($parsisp_versao->Export == "") { ?>
<script type="text/javascript">
fparsisp_versaogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$parsisp_versao_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$parsisp_versao_grid->Page_Terminate();
?>
