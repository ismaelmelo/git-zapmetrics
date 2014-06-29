<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($item_contratado_grid)) $item_contratado_grid = new citem_contratado_grid();

// Page init
$item_contratado_grid->Page_Init();

// Page main
$item_contratado_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$item_contratado_grid->Page_Render();
?>
<?php if ($item_contratado->Export == "") { ?>
<script type="text/javascript">

// Page object
var item_contratado_grid = new ew_Page("item_contratado_grid");
item_contratado_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = item_contratado_grid.PageID; // For backward compatibility

// Form object
var fitem_contratadogrid = new ew_Form("fitem_contratadogrid");
fitem_contratadogrid.FormKeyCountName = '<?php echo $item_contratado_grid->FormKeyCountName ?>';

// Validate form
fitem_contratadogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_itemOc");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->nu_itemOc->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_itemContratado");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->no_itemContratado->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_unidade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->nu_unidade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_maximo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->qt_maximo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_qt_maximo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($item_contratado->qt_maximo->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vr_maximo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($item_contratado->vr_maximo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_maximo");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($item_contratado->vr_maximo->FldErrMsg()) ?>");

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
fitem_contratadogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_itemOc", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_itemContratado", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_unidade", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_maximo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_maximo", false)) return false;
	return true;
}

// Form_CustomValidate event
fitem_contratadogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fitem_contratadogrid.ValidateRequired = true;
<?php } else { ?>
fitem_contratadogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fitem_contratadogrid.Lists["x_nu_itemOc"] = {"LinkField":"x_nu_itemOc","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_itemOc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitem_contratadogrid.Lists["x_nu_unidade"] = {"LinkField":"x_nu_unidade","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_unidade","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($item_contratado->getCurrentMasterTable() == "" && $item_contratado_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $item_contratado_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($item_contratado->CurrentAction == "gridadd") {
	if ($item_contratado->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$item_contratado_grid->TotalRecs = $item_contratado->SelectRecordCount();
			$item_contratado_grid->Recordset = $item_contratado_grid->LoadRecordset($item_contratado_grid->StartRec-1, $item_contratado_grid->DisplayRecs);
		} else {
			if ($item_contratado_grid->Recordset = $item_contratado_grid->LoadRecordset())
				$item_contratado_grid->TotalRecs = $item_contratado_grid->Recordset->RecordCount();
		}
		$item_contratado_grid->StartRec = 1;
		$item_contratado_grid->DisplayRecs = $item_contratado_grid->TotalRecs;
	} else {
		$item_contratado->CurrentFilter = "0=1";
		$item_contratado_grid->StartRec = 1;
		$item_contratado_grid->DisplayRecs = $item_contratado->GridAddRowCount;
	}
	$item_contratado_grid->TotalRecs = $item_contratado_grid->DisplayRecs;
	$item_contratado_grid->StopRec = $item_contratado_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$item_contratado_grid->TotalRecs = $item_contratado->SelectRecordCount();
	} else {
		if ($item_contratado_grid->Recordset = $item_contratado_grid->LoadRecordset())
			$item_contratado_grid->TotalRecs = $item_contratado_grid->Recordset->RecordCount();
	}
	$item_contratado_grid->StartRec = 1;
	$item_contratado_grid->DisplayRecs = $item_contratado_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$item_contratado_grid->Recordset = $item_contratado_grid->LoadRecordset($item_contratado_grid->StartRec-1, $item_contratado_grid->DisplayRecs);
}
$item_contratado_grid->RenderOtherOptions();
?>
<?php $item_contratado_grid->ShowPageHeader(); ?>
<?php
$item_contratado_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fitem_contratadogrid" class="ewForm form-horizontal">
<div id="gmp_item_contratado" class="ewGridMiddlePanel">
<table id="tbl_item_contratadogrid" class="ewTable ewTableSeparate">
<?php echo $item_contratado->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$item_contratado_grid->RenderListOptions();

// Render list options (header, left)
$item_contratado_grid->ListOptions->Render("header", "left");
?>
<?php if ($item_contratado->nu_itemOc->Visible) { // nu_itemOc ?>
	<?php if ($item_contratado->SortUrl($item_contratado->nu_itemOc) == "") { ?>
		<td><div id="elh_item_contratado_nu_itemOc" class="item_contratado_nu_itemOc"><div class="ewTableHeaderCaption"><?php echo $item_contratado->nu_itemOc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_item_contratado_nu_itemOc" class="item_contratado_nu_itemOc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $item_contratado->nu_itemOc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($item_contratado->nu_itemOc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($item_contratado->nu_itemOc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($item_contratado->no_itemContratado->Visible) { // no_itemContratado ?>
	<?php if ($item_contratado->SortUrl($item_contratado->no_itemContratado) == "") { ?>
		<td><div id="elh_item_contratado_no_itemContratado" class="item_contratado_no_itemContratado"><div class="ewTableHeaderCaption"><?php echo $item_contratado->no_itemContratado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_item_contratado_no_itemContratado" class="item_contratado_no_itemContratado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $item_contratado->no_itemContratado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($item_contratado->no_itemContratado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($item_contratado->no_itemContratado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($item_contratado->nu_unidade->Visible) { // nu_unidade ?>
	<?php if ($item_contratado->SortUrl($item_contratado->nu_unidade) == "") { ?>
		<td><div id="elh_item_contratado_nu_unidade" class="item_contratado_nu_unidade"><div class="ewTableHeaderCaption"><?php echo $item_contratado->nu_unidade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_item_contratado_nu_unidade" class="item_contratado_nu_unidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $item_contratado->nu_unidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($item_contratado->nu_unidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($item_contratado->nu_unidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($item_contratado->qt_maximo->Visible) { // qt_maximo ?>
	<?php if ($item_contratado->SortUrl($item_contratado->qt_maximo) == "") { ?>
		<td><div id="elh_item_contratado_qt_maximo" class="item_contratado_qt_maximo"><div class="ewTableHeaderCaption"><?php echo $item_contratado->qt_maximo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_item_contratado_qt_maximo" class="item_contratado_qt_maximo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $item_contratado->qt_maximo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($item_contratado->qt_maximo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($item_contratado->qt_maximo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($item_contratado->vr_maximo->Visible) { // vr_maximo ?>
	<?php if ($item_contratado->SortUrl($item_contratado->vr_maximo) == "") { ?>
		<td><div id="elh_item_contratado_vr_maximo" class="item_contratado_vr_maximo"><div class="ewTableHeaderCaption"><?php echo $item_contratado->vr_maximo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_item_contratado_vr_maximo" class="item_contratado_vr_maximo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $item_contratado->vr_maximo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($item_contratado->vr_maximo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($item_contratado->vr_maximo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($item_contratado->dt_inclusao->Visible) { // dt_inclusao ?>
	<?php if ($item_contratado->SortUrl($item_contratado->dt_inclusao) == "") { ?>
		<td><div id="elh_item_contratado_dt_inclusao" class="item_contratado_dt_inclusao"><div class="ewTableHeaderCaption"><?php echo $item_contratado->dt_inclusao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_item_contratado_dt_inclusao" class="item_contratado_dt_inclusao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $item_contratado->dt_inclusao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($item_contratado->dt_inclusao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($item_contratado->dt_inclusao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$item_contratado_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$item_contratado_grid->StartRec = 1;
$item_contratado_grid->StopRec = $item_contratado_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($item_contratado_grid->FormKeyCountName) && ($item_contratado->CurrentAction == "gridadd" || $item_contratado->CurrentAction == "gridedit" || $item_contratado->CurrentAction == "F")) {
		$item_contratado_grid->KeyCount = $objForm->GetValue($item_contratado_grid->FormKeyCountName);
		$item_contratado_grid->StopRec = $item_contratado_grid->StartRec + $item_contratado_grid->KeyCount - 1;
	}
}
$item_contratado_grid->RecCnt = $item_contratado_grid->StartRec - 1;
if ($item_contratado_grid->Recordset && !$item_contratado_grid->Recordset->EOF) {
	$item_contratado_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $item_contratado_grid->StartRec > 1)
		$item_contratado_grid->Recordset->Move($item_contratado_grid->StartRec - 1);
} elseif (!$item_contratado->AllowAddDeleteRow && $item_contratado_grid->StopRec == 0) {
	$item_contratado_grid->StopRec = $item_contratado->GridAddRowCount;
}

// Initialize aggregate
$item_contratado->RowType = EW_ROWTYPE_AGGREGATEINIT;
$item_contratado->ResetAttrs();
$item_contratado_grid->RenderRow();
if ($item_contratado->CurrentAction == "gridadd")
	$item_contratado_grid->RowIndex = 0;
if ($item_contratado->CurrentAction == "gridedit")
	$item_contratado_grid->RowIndex = 0;
while ($item_contratado_grid->RecCnt < $item_contratado_grid->StopRec) {
	$item_contratado_grid->RecCnt++;
	if (intval($item_contratado_grid->RecCnt) >= intval($item_contratado_grid->StartRec)) {
		$item_contratado_grid->RowCnt++;
		if ($item_contratado->CurrentAction == "gridadd" || $item_contratado->CurrentAction == "gridedit" || $item_contratado->CurrentAction == "F") {
			$item_contratado_grid->RowIndex++;
			$objForm->Index = $item_contratado_grid->RowIndex;
			if ($objForm->HasValue($item_contratado_grid->FormActionName))
				$item_contratado_grid->RowAction = strval($objForm->GetValue($item_contratado_grid->FormActionName));
			elseif ($item_contratado->CurrentAction == "gridadd")
				$item_contratado_grid->RowAction = "insert";
			else
				$item_contratado_grid->RowAction = "";
		}

		// Set up key count
		$item_contratado_grid->KeyCount = $item_contratado_grid->RowIndex;

		// Init row class and style
		$item_contratado->ResetAttrs();
		$item_contratado->CssClass = "";
		if ($item_contratado->CurrentAction == "gridadd") {
			if ($item_contratado->CurrentMode == "copy") {
				$item_contratado_grid->LoadRowValues($item_contratado_grid->Recordset); // Load row values
				$item_contratado_grid->SetRecordKey($item_contratado_grid->RowOldKey, $item_contratado_grid->Recordset); // Set old record key
			} else {
				$item_contratado_grid->LoadDefaultValues(); // Load default values
				$item_contratado_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$item_contratado_grid->LoadRowValues($item_contratado_grid->Recordset); // Load row values
		}
		$item_contratado->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($item_contratado->CurrentAction == "gridadd") // Grid add
			$item_contratado->RowType = EW_ROWTYPE_ADD; // Render add
		if ($item_contratado->CurrentAction == "gridadd" && $item_contratado->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$item_contratado_grid->RestoreCurrentRowFormValues($item_contratado_grid->RowIndex); // Restore form values
		if ($item_contratado->CurrentAction == "gridedit") { // Grid edit
			if ($item_contratado->EventCancelled) {
				$item_contratado_grid->RestoreCurrentRowFormValues($item_contratado_grid->RowIndex); // Restore form values
			}
			if ($item_contratado_grid->RowAction == "insert")
				$item_contratado->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$item_contratado->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($item_contratado->CurrentAction == "gridedit" && ($item_contratado->RowType == EW_ROWTYPE_EDIT || $item_contratado->RowType == EW_ROWTYPE_ADD) && $item_contratado->EventCancelled) // Update failed
			$item_contratado_grid->RestoreCurrentRowFormValues($item_contratado_grid->RowIndex); // Restore form values
		if ($item_contratado->RowType == EW_ROWTYPE_EDIT) // Edit row
			$item_contratado_grid->EditRowCnt++;
		if ($item_contratado->CurrentAction == "F") // Confirm row
			$item_contratado_grid->RestoreCurrentRowFormValues($item_contratado_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$item_contratado->RowAttrs = array_merge($item_contratado->RowAttrs, array('data-rowindex'=>$item_contratado_grid->RowCnt, 'id'=>'r' . $item_contratado_grid->RowCnt . '_item_contratado', 'data-rowtype'=>$item_contratado->RowType));

		// Render row
		$item_contratado_grid->RenderRow();

		// Render list options
		$item_contratado_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($item_contratado_grid->RowAction <> "delete" && $item_contratado_grid->RowAction <> "insertdelete" && !($item_contratado_grid->RowAction == "insert" && $item_contratado->CurrentAction == "F" && $item_contratado_grid->EmptyRow())) {
?>
	<tr<?php echo $item_contratado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$item_contratado_grid->ListOptions->Render("body", "left", $item_contratado_grid->RowCnt);
?>
	<?php if ($item_contratado->nu_itemOc->Visible) { // nu_itemOc ?>
		<td<?php echo $item_contratado->nu_itemOc->CellAttributes() ?>>
<?php if ($item_contratado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_nu_itemOc" class="control-group item_contratado_nu_itemOc">
<select data-field="x_nu_itemOc" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc"<?php echo $item_contratado->nu_itemOc->EditAttributes() ?>>
<?php
if (is_array($item_contratado->nu_itemOc->EditValue)) {
	$arwrk = $item_contratado->nu_itemOc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($item_contratado->nu_itemOc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $item_contratado->nu_itemOc->OldValue = "";
?>
</select>
<script type="text/javascript">
fitem_contratadogrid.Lists["x_nu_itemOc"].Options = <?php echo (is_array($item_contratado->nu_itemOc->EditValue)) ? ew_ArrayToJson($item_contratado->nu_itemOc->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_itemOc" name="o<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" id="o<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" value="<?php echo ew_HtmlEncode($item_contratado->nu_itemOc->OldValue) ?>">
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_nu_itemOc" class="control-group item_contratado_nu_itemOc">
<select data-field="x_nu_itemOc" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc"<?php echo $item_contratado->nu_itemOc->EditAttributes() ?>>
<?php
if (is_array($item_contratado->nu_itemOc->EditValue)) {
	$arwrk = $item_contratado->nu_itemOc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($item_contratado->nu_itemOc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $item_contratado->nu_itemOc->OldValue = "";
?>
</select>
<script type="text/javascript">
fitem_contratadogrid.Lists["x_nu_itemOc"].Options = <?php echo (is_array($item_contratado->nu_itemOc->EditValue)) ? ew_ArrayToJson($item_contratado->nu_itemOc->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $item_contratado->nu_itemOc->ViewAttributes() ?>>
<?php echo $item_contratado->nu_itemOc->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_itemOc" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" value="<?php echo ew_HtmlEncode($item_contratado->nu_itemOc->FormValue) ?>">
<input type="hidden" data-field="x_nu_itemOc" name="o<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" id="o<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" value="<?php echo ew_HtmlEncode($item_contratado->nu_itemOc->OldValue) ?>">
<?php } ?>
<a id="<?php echo $item_contratado_grid->PageObjName . "_row_" . $item_contratado_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_itemContratado" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemContratado" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemContratado" value="<?php echo ew_HtmlEncode($item_contratado->nu_itemContratado->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_itemContratado" name="o<?php echo $item_contratado_grid->RowIndex ?>_nu_itemContratado" id="o<?php echo $item_contratado_grid->RowIndex ?>_nu_itemContratado" value="<?php echo ew_HtmlEncode($item_contratado->nu_itemContratado->OldValue) ?>">
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_EDIT || $item_contratado->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_itemContratado" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemContratado" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemContratado" value="<?php echo ew_HtmlEncode($item_contratado->nu_itemContratado->CurrentValue) ?>">
<?php } ?>
	<?php if ($item_contratado->no_itemContratado->Visible) { // no_itemContratado ?>
		<td<?php echo $item_contratado->no_itemContratado->CellAttributes() ?>>
<?php if ($item_contratado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_no_itemContratado" class="control-group item_contratado_no_itemContratado">
<input type="text" data-field="x_no_itemContratado" name="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" id="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" size="30" maxlength="100" placeholder="<?php echo $item_contratado->no_itemContratado->PlaceHolder ?>" value="<?php echo $item_contratado->no_itemContratado->EditValue ?>"<?php echo $item_contratado->no_itemContratado->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_itemContratado" name="o<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" id="o<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" value="<?php echo ew_HtmlEncode($item_contratado->no_itemContratado->OldValue) ?>">
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_no_itemContratado" class="control-group item_contratado_no_itemContratado">
<input type="text" data-field="x_no_itemContratado" name="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" id="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" size="30" maxlength="100" placeholder="<?php echo $item_contratado->no_itemContratado->PlaceHolder ?>" value="<?php echo $item_contratado->no_itemContratado->EditValue ?>"<?php echo $item_contratado->no_itemContratado->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $item_contratado->no_itemContratado->ViewAttributes() ?>>
<?php echo $item_contratado->no_itemContratado->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_itemContratado" name="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" id="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" value="<?php echo ew_HtmlEncode($item_contratado->no_itemContratado->FormValue) ?>">
<input type="hidden" data-field="x_no_itemContratado" name="o<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" id="o<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" value="<?php echo ew_HtmlEncode($item_contratado->no_itemContratado->OldValue) ?>">
<?php } ?>
<a id="<?php echo $item_contratado_grid->PageObjName . "_row_" . $item_contratado_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($item_contratado->nu_unidade->Visible) { // nu_unidade ?>
		<td<?php echo $item_contratado->nu_unidade->CellAttributes() ?>>
<?php if ($item_contratado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_nu_unidade" class="control-group item_contratado_nu_unidade">
<select data-field="x_nu_unidade" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade"<?php echo $item_contratado->nu_unidade->EditAttributes() ?>>
<?php
if (is_array($item_contratado->nu_unidade->EditValue)) {
	$arwrk = $item_contratado->nu_unidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($item_contratado->nu_unidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $item_contratado->nu_unidade->OldValue = "";
?>
</select>
<script type="text/javascript">
fitem_contratadogrid.Lists["x_nu_unidade"].Options = <?php echo (is_array($item_contratado->nu_unidade->EditValue)) ? ew_ArrayToJson($item_contratado->nu_unidade->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_unidade" name="o<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" id="o<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" value="<?php echo ew_HtmlEncode($item_contratado->nu_unidade->OldValue) ?>">
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_nu_unidade" class="control-group item_contratado_nu_unidade">
<select data-field="x_nu_unidade" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade"<?php echo $item_contratado->nu_unidade->EditAttributes() ?>>
<?php
if (is_array($item_contratado->nu_unidade->EditValue)) {
	$arwrk = $item_contratado->nu_unidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($item_contratado->nu_unidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $item_contratado->nu_unidade->OldValue = "";
?>
</select>
<script type="text/javascript">
fitem_contratadogrid.Lists["x_nu_unidade"].Options = <?php echo (is_array($item_contratado->nu_unidade->EditValue)) ? ew_ArrayToJson($item_contratado->nu_unidade->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $item_contratado->nu_unidade->ViewAttributes() ?>>
<?php echo $item_contratado->nu_unidade->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_unidade" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" value="<?php echo ew_HtmlEncode($item_contratado->nu_unidade->FormValue) ?>">
<input type="hidden" data-field="x_nu_unidade" name="o<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" id="o<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" value="<?php echo ew_HtmlEncode($item_contratado->nu_unidade->OldValue) ?>">
<?php } ?>
<a id="<?php echo $item_contratado_grid->PageObjName . "_row_" . $item_contratado_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($item_contratado->qt_maximo->Visible) { // qt_maximo ?>
		<td<?php echo $item_contratado->qt_maximo->CellAttributes() ?>>
<?php if ($item_contratado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_qt_maximo" class="control-group item_contratado_qt_maximo">
<input type="text" data-field="x_qt_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" size="30" placeholder="<?php echo $item_contratado->qt_maximo->PlaceHolder ?>" value="<?php echo $item_contratado->qt_maximo->EditValue ?>"<?php echo $item_contratado->qt_maximo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_maximo" name="o<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" id="o<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" value="<?php echo ew_HtmlEncode($item_contratado->qt_maximo->OldValue) ?>">
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_qt_maximo" class="control-group item_contratado_qt_maximo">
<input type="text" data-field="x_qt_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" size="30" placeholder="<?php echo $item_contratado->qt_maximo->PlaceHolder ?>" value="<?php echo $item_contratado->qt_maximo->EditValue ?>"<?php echo $item_contratado->qt_maximo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $item_contratado->qt_maximo->ViewAttributes() ?>>
<?php echo $item_contratado->qt_maximo->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" value="<?php echo ew_HtmlEncode($item_contratado->qt_maximo->FormValue) ?>">
<input type="hidden" data-field="x_qt_maximo" name="o<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" id="o<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" value="<?php echo ew_HtmlEncode($item_contratado->qt_maximo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $item_contratado_grid->PageObjName . "_row_" . $item_contratado_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($item_contratado->vr_maximo->Visible) { // vr_maximo ?>
		<td<?php echo $item_contratado->vr_maximo->CellAttributes() ?>>
<?php if ($item_contratado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_vr_maximo" class="control-group item_contratado_vr_maximo">
<input type="text" data-field="x_vr_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" size="30" placeholder="<?php echo $item_contratado->vr_maximo->PlaceHolder ?>" value="<?php echo $item_contratado->vr_maximo->EditValue ?>"<?php echo $item_contratado->vr_maximo->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_maximo" name="o<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" id="o<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" value="<?php echo ew_HtmlEncode($item_contratado->vr_maximo->OldValue) ?>">
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $item_contratado_grid->RowCnt ?>_item_contratado_vr_maximo" class="control-group item_contratado_vr_maximo">
<input type="text" data-field="x_vr_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" size="30" placeholder="<?php echo $item_contratado->vr_maximo->PlaceHolder ?>" value="<?php echo $item_contratado->vr_maximo->EditValue ?>"<?php echo $item_contratado->vr_maximo->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $item_contratado->vr_maximo->ViewAttributes() ?>>
<?php echo $item_contratado->vr_maximo->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" value="<?php echo ew_HtmlEncode($item_contratado->vr_maximo->FormValue) ?>">
<input type="hidden" data-field="x_vr_maximo" name="o<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" id="o<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" value="<?php echo ew_HtmlEncode($item_contratado->vr_maximo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $item_contratado_grid->PageObjName . "_row_" . $item_contratado_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($item_contratado->dt_inclusao->Visible) { // dt_inclusao ?>
		<td<?php echo $item_contratado->dt_inclusao->CellAttributes() ?>>
<?php if ($item_contratado->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_dt_inclusao" name="o<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" id="o<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" value="<?php echo ew_HtmlEncode($item_contratado->dt_inclusao->OldValue) ?>">
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($item_contratado->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $item_contratado->dt_inclusao->ViewAttributes() ?>>
<?php echo $item_contratado->dt_inclusao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dt_inclusao" name="x<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" id="x<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" value="<?php echo ew_HtmlEncode($item_contratado->dt_inclusao->FormValue) ?>">
<input type="hidden" data-field="x_dt_inclusao" name="o<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" id="o<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" value="<?php echo ew_HtmlEncode($item_contratado->dt_inclusao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $item_contratado_grid->PageObjName . "_row_" . $item_contratado_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$item_contratado_grid->ListOptions->Render("body", "right", $item_contratado_grid->RowCnt);
?>
	</tr>
<?php if ($item_contratado->RowType == EW_ROWTYPE_ADD || $item_contratado->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fitem_contratadogrid.UpdateOpts(<?php echo $item_contratado_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($item_contratado->CurrentAction <> "gridadd" || $item_contratado->CurrentMode == "copy")
		if (!$item_contratado_grid->Recordset->EOF) $item_contratado_grid->Recordset->MoveNext();
}
?>
<?php
	if ($item_contratado->CurrentMode == "add" || $item_contratado->CurrentMode == "copy" || $item_contratado->CurrentMode == "edit") {
		$item_contratado_grid->RowIndex = '$rowindex$';
		$item_contratado_grid->LoadDefaultValues();

		// Set row properties
		$item_contratado->ResetAttrs();
		$item_contratado->RowAttrs = array_merge($item_contratado->RowAttrs, array('data-rowindex'=>$item_contratado_grid->RowIndex, 'id'=>'r0_item_contratado', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($item_contratado->RowAttrs["class"], "ewTemplate");
		$item_contratado->RowType = EW_ROWTYPE_ADD;

		// Render row
		$item_contratado_grid->RenderRow();

		// Render list options
		$item_contratado_grid->RenderListOptions();
		$item_contratado_grid->StartRowCnt = 0;
?>
	<tr<?php echo $item_contratado->RowAttributes() ?>>
<?php

// Render list options (body, left)
$item_contratado_grid->ListOptions->Render("body", "left", $item_contratado_grid->RowIndex);
?>
	<?php if ($item_contratado->nu_itemOc->Visible) { // nu_itemOc ?>
		<td>
<?php if ($item_contratado->CurrentAction <> "F") { ?>
<select data-field="x_nu_itemOc" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc"<?php echo $item_contratado->nu_itemOc->EditAttributes() ?>>
<?php
if (is_array($item_contratado->nu_itemOc->EditValue)) {
	$arwrk = $item_contratado->nu_itemOc->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($item_contratado->nu_itemOc->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $item_contratado->nu_itemOc->OldValue = "";
?>
</select>
<script type="text/javascript">
fitem_contratadogrid.Lists["x_nu_itemOc"].Options = <?php echo (is_array($item_contratado->nu_itemOc->EditValue)) ? ew_ArrayToJson($item_contratado->nu_itemOc->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $item_contratado->nu_itemOc->ViewAttributes() ?>>
<?php echo $item_contratado->nu_itemOc->ViewValue ?></span>
<input type="hidden" data-field="x_nu_itemOc" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" value="<?php echo ew_HtmlEncode($item_contratado->nu_itemOc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_itemOc" name="o<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" id="o<?php echo $item_contratado_grid->RowIndex ?>_nu_itemOc" value="<?php echo ew_HtmlEncode($item_contratado->nu_itemOc->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($item_contratado->no_itemContratado->Visible) { // no_itemContratado ?>
		<td>
<?php if ($item_contratado->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_itemContratado" name="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" id="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" size="30" maxlength="100" placeholder="<?php echo $item_contratado->no_itemContratado->PlaceHolder ?>" value="<?php echo $item_contratado->no_itemContratado->EditValue ?>"<?php echo $item_contratado->no_itemContratado->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $item_contratado->no_itemContratado->ViewAttributes() ?>>
<?php echo $item_contratado->no_itemContratado->ViewValue ?></span>
<input type="hidden" data-field="x_no_itemContratado" name="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" id="x<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" value="<?php echo ew_HtmlEncode($item_contratado->no_itemContratado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_itemContratado" name="o<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" id="o<?php echo $item_contratado_grid->RowIndex ?>_no_itemContratado" value="<?php echo ew_HtmlEncode($item_contratado->no_itemContratado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($item_contratado->nu_unidade->Visible) { // nu_unidade ?>
		<td>
<?php if ($item_contratado->CurrentAction <> "F") { ?>
<select data-field="x_nu_unidade" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade"<?php echo $item_contratado->nu_unidade->EditAttributes() ?>>
<?php
if (is_array($item_contratado->nu_unidade->EditValue)) {
	$arwrk = $item_contratado->nu_unidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($item_contratado->nu_unidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $item_contratado->nu_unidade->OldValue = "";
?>
</select>
<script type="text/javascript">
fitem_contratadogrid.Lists["x_nu_unidade"].Options = <?php echo (is_array($item_contratado->nu_unidade->EditValue)) ? ew_ArrayToJson($item_contratado->nu_unidade->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $item_contratado->nu_unidade->ViewAttributes() ?>>
<?php echo $item_contratado->nu_unidade->ViewValue ?></span>
<input type="hidden" data-field="x_nu_unidade" name="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" id="x<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" value="<?php echo ew_HtmlEncode($item_contratado->nu_unidade->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_unidade" name="o<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" id="o<?php echo $item_contratado_grid->RowIndex ?>_nu_unidade" value="<?php echo ew_HtmlEncode($item_contratado->nu_unidade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($item_contratado->qt_maximo->Visible) { // qt_maximo ?>
		<td>
<?php if ($item_contratado->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" size="30" placeholder="<?php echo $item_contratado->qt_maximo->PlaceHolder ?>" value="<?php echo $item_contratado->qt_maximo->EditValue ?>"<?php echo $item_contratado->qt_maximo->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $item_contratado->qt_maximo->ViewAttributes() ?>>
<?php echo $item_contratado->qt_maximo->ViewValue ?></span>
<input type="hidden" data-field="x_qt_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" value="<?php echo ew_HtmlEncode($item_contratado->qt_maximo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_maximo" name="o<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" id="o<?php echo $item_contratado_grid->RowIndex ?>_qt_maximo" value="<?php echo ew_HtmlEncode($item_contratado->qt_maximo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($item_contratado->vr_maximo->Visible) { // vr_maximo ?>
		<td>
<?php if ($item_contratado->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" size="30" placeholder="<?php echo $item_contratado->vr_maximo->PlaceHolder ?>" value="<?php echo $item_contratado->vr_maximo->EditValue ?>"<?php echo $item_contratado->vr_maximo->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $item_contratado->vr_maximo->ViewAttributes() ?>>
<?php echo $item_contratado->vr_maximo->ViewValue ?></span>
<input type="hidden" data-field="x_vr_maximo" name="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" id="x<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" value="<?php echo ew_HtmlEncode($item_contratado->vr_maximo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_maximo" name="o<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" id="o<?php echo $item_contratado_grid->RowIndex ?>_vr_maximo" value="<?php echo ew_HtmlEncode($item_contratado->vr_maximo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($item_contratado->dt_inclusao->Visible) { // dt_inclusao ?>
		<td>
<?php if ($item_contratado->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $item_contratado->dt_inclusao->ViewAttributes() ?>>
<?php echo $item_contratado->dt_inclusao->ViewValue ?></span>
<input type="hidden" data-field="x_dt_inclusao" name="x<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" id="x<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" value="<?php echo ew_HtmlEncode($item_contratado->dt_inclusao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dt_inclusao" name="o<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" id="o<?php echo $item_contratado_grid->RowIndex ?>_dt_inclusao" value="<?php echo ew_HtmlEncode($item_contratado->dt_inclusao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$item_contratado_grid->ListOptions->Render("body", "right", $item_contratado_grid->RowCnt);
?>
<script type="text/javascript">
fitem_contratadogrid.UpdateOpts(<?php echo $item_contratado_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($item_contratado->CurrentMode == "add" || $item_contratado->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $item_contratado_grid->FormKeyCountName ?>" id="<?php echo $item_contratado_grid->FormKeyCountName ?>" value="<?php echo $item_contratado_grid->KeyCount ?>">
<?php echo $item_contratado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($item_contratado->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $item_contratado_grid->FormKeyCountName ?>" id="<?php echo $item_contratado_grid->FormKeyCountName ?>" value="<?php echo $item_contratado_grid->KeyCount ?>">
<?php echo $item_contratado_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($item_contratado->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fitem_contratadogrid">
</div>
<?php

// Close recordset
if ($item_contratado_grid->Recordset)
	$item_contratado_grid->Recordset->Close();
?>
<?php if ($item_contratado_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($item_contratado_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($item_contratado->Export == "") { ?>
<script type="text/javascript">
fitem_contratadogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$item_contratado_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$item_contratado_grid->Page_Terminate();
?>
