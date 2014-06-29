<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($uc_mensagem_grid)) $uc_mensagem_grid = new cuc_mensagem_grid();

// Page init
$uc_mensagem_grid->Page_Init();

// Page main
$uc_mensagem_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$uc_mensagem_grid->Page_Render();
?>
<?php if ($uc_mensagem->Export == "") { ?>
<script type="text/javascript">

// Page object
var uc_mensagem_grid = new ew_Page("uc_mensagem_grid");
uc_mensagem_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = uc_mensagem_grid.PageID; // For backward compatibility

// Form object
var fuc_mensagemgrid = new ew_Form("fuc_mensagemgrid");
fuc_mensagemgrid.FormKeyCountName = '<?php echo $uc_mensagem_grid->FormKeyCountName ?>';

// Validate form
fuc_mensagemgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_mensagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($uc_mensagem->nu_mensagem->FldCaption()) ?>");

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
fuc_mensagemgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_mensagem", false)) return false;
	return true;
}

// Form_CustomValidate event
fuc_mensagemgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fuc_mensagemgrid.ValidateRequired = true;
<?php } else { ?>
fuc_mensagemgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fuc_mensagemgrid.Lists["x_nu_mensagem"] = {"LinkField":"x_nu_mensagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_mensagem","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($uc_mensagem->getCurrentMasterTable() == "" && $uc_mensagem_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $uc_mensagem_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($uc_mensagem->CurrentAction == "gridadd") {
	if ($uc_mensagem->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$uc_mensagem_grid->TotalRecs = $uc_mensagem->SelectRecordCount();
			$uc_mensagem_grid->Recordset = $uc_mensagem_grid->LoadRecordset($uc_mensagem_grid->StartRec-1, $uc_mensagem_grid->DisplayRecs);
		} else {
			if ($uc_mensagem_grid->Recordset = $uc_mensagem_grid->LoadRecordset())
				$uc_mensagem_grid->TotalRecs = $uc_mensagem_grid->Recordset->RecordCount();
		}
		$uc_mensagem_grid->StartRec = 1;
		$uc_mensagem_grid->DisplayRecs = $uc_mensagem_grid->TotalRecs;
	} else {
		$uc_mensagem->CurrentFilter = "0=1";
		$uc_mensagem_grid->StartRec = 1;
		$uc_mensagem_grid->DisplayRecs = $uc_mensagem->GridAddRowCount;
	}
	$uc_mensagem_grid->TotalRecs = $uc_mensagem_grid->DisplayRecs;
	$uc_mensagem_grid->StopRec = $uc_mensagem_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$uc_mensagem_grid->TotalRecs = $uc_mensagem->SelectRecordCount();
	} else {
		if ($uc_mensagem_grid->Recordset = $uc_mensagem_grid->LoadRecordset())
			$uc_mensagem_grid->TotalRecs = $uc_mensagem_grid->Recordset->RecordCount();
	}
	$uc_mensagem_grid->StartRec = 1;
	$uc_mensagem_grid->DisplayRecs = $uc_mensagem_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$uc_mensagem_grid->Recordset = $uc_mensagem_grid->LoadRecordset($uc_mensagem_grid->StartRec-1, $uc_mensagem_grid->DisplayRecs);
}
$uc_mensagem_grid->RenderOtherOptions();
?>
<?php $uc_mensagem_grid->ShowPageHeader(); ?>
<?php
$uc_mensagem_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fuc_mensagemgrid" class="ewForm form-horizontal">
<div id="gmp_uc_mensagem" class="ewGridMiddlePanel">
<table id="tbl_uc_mensagemgrid" class="ewTable ewTableSeparate">
<?php echo $uc_mensagem->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$uc_mensagem_grid->RenderListOptions();

// Render list options (header, left)
$uc_mensagem_grid->ListOptions->Render("header", "left");
?>
<?php if ($uc_mensagem->nu_mensagem->Visible) { // nu_mensagem ?>
	<?php if ($uc_mensagem->SortUrl($uc_mensagem->nu_mensagem) == "") { ?>
		<td><div id="elh_uc_mensagem_nu_mensagem" class="uc_mensagem_nu_mensagem"><div class="ewTableHeaderCaption"><?php echo $uc_mensagem->nu_mensagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_uc_mensagem_nu_mensagem" class="uc_mensagem_nu_mensagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $uc_mensagem->nu_mensagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($uc_mensagem->nu_mensagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($uc_mensagem->nu_mensagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$uc_mensagem_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$uc_mensagem_grid->StartRec = 1;
$uc_mensagem_grid->StopRec = $uc_mensagem_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($uc_mensagem_grid->FormKeyCountName) && ($uc_mensagem->CurrentAction == "gridadd" || $uc_mensagem->CurrentAction == "gridedit" || $uc_mensagem->CurrentAction == "F")) {
		$uc_mensagem_grid->KeyCount = $objForm->GetValue($uc_mensagem_grid->FormKeyCountName);
		$uc_mensagem_grid->StopRec = $uc_mensagem_grid->StartRec + $uc_mensagem_grid->KeyCount - 1;
	}
}
$uc_mensagem_grid->RecCnt = $uc_mensagem_grid->StartRec - 1;
if ($uc_mensagem_grid->Recordset && !$uc_mensagem_grid->Recordset->EOF) {
	$uc_mensagem_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $uc_mensagem_grid->StartRec > 1)
		$uc_mensagem_grid->Recordset->Move($uc_mensagem_grid->StartRec - 1);
} elseif (!$uc_mensagem->AllowAddDeleteRow && $uc_mensagem_grid->StopRec == 0) {
	$uc_mensagem_grid->StopRec = $uc_mensagem->GridAddRowCount;
}

// Initialize aggregate
$uc_mensagem->RowType = EW_ROWTYPE_AGGREGATEINIT;
$uc_mensagem->ResetAttrs();
$uc_mensagem_grid->RenderRow();
if ($uc_mensagem->CurrentAction == "gridadd")
	$uc_mensagem_grid->RowIndex = 0;
if ($uc_mensagem->CurrentAction == "gridedit")
	$uc_mensagem_grid->RowIndex = 0;
while ($uc_mensagem_grid->RecCnt < $uc_mensagem_grid->StopRec) {
	$uc_mensagem_grid->RecCnt++;
	if (intval($uc_mensagem_grid->RecCnt) >= intval($uc_mensagem_grid->StartRec)) {
		$uc_mensagem_grid->RowCnt++;
		if ($uc_mensagem->CurrentAction == "gridadd" || $uc_mensagem->CurrentAction == "gridedit" || $uc_mensagem->CurrentAction == "F") {
			$uc_mensagem_grid->RowIndex++;
			$objForm->Index = $uc_mensagem_grid->RowIndex;
			if ($objForm->HasValue($uc_mensagem_grid->FormActionName))
				$uc_mensagem_grid->RowAction = strval($objForm->GetValue($uc_mensagem_grid->FormActionName));
			elseif ($uc_mensagem->CurrentAction == "gridadd")
				$uc_mensagem_grid->RowAction = "insert";
			else
				$uc_mensagem_grid->RowAction = "";
		}

		// Set up key count
		$uc_mensagem_grid->KeyCount = $uc_mensagem_grid->RowIndex;

		// Init row class and style
		$uc_mensagem->ResetAttrs();
		$uc_mensagem->CssClass = "";
		if ($uc_mensagem->CurrentAction == "gridadd") {
			if ($uc_mensagem->CurrentMode == "copy") {
				$uc_mensagem_grid->LoadRowValues($uc_mensagem_grid->Recordset); // Load row values
				$uc_mensagem_grid->SetRecordKey($uc_mensagem_grid->RowOldKey, $uc_mensagem_grid->Recordset); // Set old record key
			} else {
				$uc_mensagem_grid->LoadDefaultValues(); // Load default values
				$uc_mensagem_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$uc_mensagem_grid->LoadRowValues($uc_mensagem_grid->Recordset); // Load row values
		}
		$uc_mensagem->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($uc_mensagem->CurrentAction == "gridadd") // Grid add
			$uc_mensagem->RowType = EW_ROWTYPE_ADD; // Render add
		if ($uc_mensagem->CurrentAction == "gridadd" && $uc_mensagem->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$uc_mensagem_grid->RestoreCurrentRowFormValues($uc_mensagem_grid->RowIndex); // Restore form values
		if ($uc_mensagem->CurrentAction == "gridedit") { // Grid edit
			if ($uc_mensagem->EventCancelled) {
				$uc_mensagem_grid->RestoreCurrentRowFormValues($uc_mensagem_grid->RowIndex); // Restore form values
			}
			if ($uc_mensagem_grid->RowAction == "insert")
				$uc_mensagem->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$uc_mensagem->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($uc_mensagem->CurrentAction == "gridedit" && ($uc_mensagem->RowType == EW_ROWTYPE_EDIT || $uc_mensagem->RowType == EW_ROWTYPE_ADD) && $uc_mensagem->EventCancelled) // Update failed
			$uc_mensagem_grid->RestoreCurrentRowFormValues($uc_mensagem_grid->RowIndex); // Restore form values
		if ($uc_mensagem->RowType == EW_ROWTYPE_EDIT) // Edit row
			$uc_mensagem_grid->EditRowCnt++;
		if ($uc_mensagem->CurrentAction == "F") // Confirm row
			$uc_mensagem_grid->RestoreCurrentRowFormValues($uc_mensagem_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$uc_mensagem->RowAttrs = array_merge($uc_mensagem->RowAttrs, array('data-rowindex'=>$uc_mensagem_grid->RowCnt, 'id'=>'r' . $uc_mensagem_grid->RowCnt . '_uc_mensagem', 'data-rowtype'=>$uc_mensagem->RowType));

		// Render row
		$uc_mensagem_grid->RenderRow();

		// Render list options
		$uc_mensagem_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($uc_mensagem_grid->RowAction <> "delete" && $uc_mensagem_grid->RowAction <> "insertdelete" && !($uc_mensagem_grid->RowAction == "insert" && $uc_mensagem->CurrentAction == "F" && $uc_mensagem_grid->EmptyRow())) {
?>
	<tr<?php echo $uc_mensagem->RowAttributes() ?>>
<?php

// Render list options (body, left)
$uc_mensagem_grid->ListOptions->Render("body", "left", $uc_mensagem_grid->RowCnt);
?>
	<?php if ($uc_mensagem->nu_mensagem->Visible) { // nu_mensagem ?>
		<td<?php echo $uc_mensagem->nu_mensagem->CellAttributes() ?>>
<?php if ($uc_mensagem->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $uc_mensagem_grid->RowCnt ?>_uc_mensagem_nu_mensagem" class="control-group uc_mensagem_nu_mensagem">
<select data-field="x_nu_mensagem" id="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" name="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem"<?php echo $uc_mensagem->nu_mensagem->EditAttributes() ?>>
<?php
if (is_array($uc_mensagem->nu_mensagem->EditValue)) {
	$arwrk = $uc_mensagem->nu_mensagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc_mensagem->nu_mensagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$uc_mensagem->nu_mensagem) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $uc_mensagem->nu_mensagem->OldValue = "";
?>
</select>
<script type="text/javascript">
fuc_mensagemgrid.Lists["x_nu_mensagem"].Options = <?php echo (is_array($uc_mensagem->nu_mensagem->EditValue)) ? ew_ArrayToJson($uc_mensagem->nu_mensagem->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_mensagem" name="o<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" id="o<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_mensagem->OldValue) ?>">
<?php } ?>
<?php if ($uc_mensagem->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $uc_mensagem_grid->RowCnt ?>_uc_mensagem_nu_mensagem" class="control-group uc_mensagem_nu_mensagem">
<span<?php echo $uc_mensagem->nu_mensagem->ViewAttributes() ?>>
<?php echo $uc_mensagem->nu_mensagem->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_mensagem" name="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" id="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_mensagem->CurrentValue) ?>">
<?php } ?>
<?php if ($uc_mensagem->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $uc_mensagem->nu_mensagem->ViewAttributes() ?>>
<?php echo $uc_mensagem->nu_mensagem->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_mensagem" name="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" id="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_mensagem->FormValue) ?>">
<input type="hidden" data-field="x_nu_mensagem" name="o<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" id="o<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_mensagem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $uc_mensagem_grid->PageObjName . "_row_" . $uc_mensagem_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($uc_mensagem->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_uc" id="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_uc->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_uc" name="o<?php echo $uc_mensagem_grid->RowIndex ?>_nu_uc" id="o<?php echo $uc_mensagem_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_uc->OldValue) ?>">
<?php } ?>
<?php if ($uc_mensagem->RowType == EW_ROWTYPE_EDIT || $uc_mensagem->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_uc" name="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_uc" id="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_uc" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_uc->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$uc_mensagem_grid->ListOptions->Render("body", "right", $uc_mensagem_grid->RowCnt);
?>
	</tr>
<?php if ($uc_mensagem->RowType == EW_ROWTYPE_ADD || $uc_mensagem->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fuc_mensagemgrid.UpdateOpts(<?php echo $uc_mensagem_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($uc_mensagem->CurrentAction <> "gridadd" || $uc_mensagem->CurrentMode == "copy")
		if (!$uc_mensagem_grid->Recordset->EOF) $uc_mensagem_grid->Recordset->MoveNext();
}
?>
<?php
	if ($uc_mensagem->CurrentMode == "add" || $uc_mensagem->CurrentMode == "copy" || $uc_mensagem->CurrentMode == "edit") {
		$uc_mensagem_grid->RowIndex = '$rowindex$';
		$uc_mensagem_grid->LoadDefaultValues();

		// Set row properties
		$uc_mensagem->ResetAttrs();
		$uc_mensagem->RowAttrs = array_merge($uc_mensagem->RowAttrs, array('data-rowindex'=>$uc_mensagem_grid->RowIndex, 'id'=>'r0_uc_mensagem', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($uc_mensagem->RowAttrs["class"], "ewTemplate");
		$uc_mensagem->RowType = EW_ROWTYPE_ADD;

		// Render row
		$uc_mensagem_grid->RenderRow();

		// Render list options
		$uc_mensagem_grid->RenderListOptions();
		$uc_mensagem_grid->StartRowCnt = 0;
?>
	<tr<?php echo $uc_mensagem->RowAttributes() ?>>
<?php

// Render list options (body, left)
$uc_mensagem_grid->ListOptions->Render("body", "left", $uc_mensagem_grid->RowIndex);
?>
	<?php if ($uc_mensagem->nu_mensagem->Visible) { // nu_mensagem ?>
		<td>
<?php if ($uc_mensagem->CurrentAction <> "F") { ?>
<select data-field="x_nu_mensagem" id="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" name="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem"<?php echo $uc_mensagem->nu_mensagem->EditAttributes() ?>>
<?php
if (is_array($uc_mensagem->nu_mensagem->EditValue)) {
	$arwrk = $uc_mensagem->nu_mensagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($uc_mensagem->nu_mensagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$uc_mensagem->nu_mensagem) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $uc_mensagem->nu_mensagem->OldValue = "";
?>
</select>
<script type="text/javascript">
fuc_mensagemgrid.Lists["x_nu_mensagem"].Options = <?php echo (is_array($uc_mensagem->nu_mensagem->EditValue)) ? ew_ArrayToJson($uc_mensagem->nu_mensagem->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $uc_mensagem->nu_mensagem->ViewAttributes() ?>>
<?php echo $uc_mensagem->nu_mensagem->ViewValue ?></span>
<input type="hidden" data-field="x_nu_mensagem" name="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" id="x<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_mensagem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_mensagem" name="o<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" id="o<?php echo $uc_mensagem_grid->RowIndex ?>_nu_mensagem" value="<?php echo ew_HtmlEncode($uc_mensagem->nu_mensagem->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$uc_mensagem_grid->ListOptions->Render("body", "right", $uc_mensagem_grid->RowCnt);
?>
<script type="text/javascript">
fuc_mensagemgrid.UpdateOpts(<?php echo $uc_mensagem_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($uc_mensagem->CurrentMode == "add" || $uc_mensagem->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $uc_mensagem_grid->FormKeyCountName ?>" id="<?php echo $uc_mensagem_grid->FormKeyCountName ?>" value="<?php echo $uc_mensagem_grid->KeyCount ?>">
<?php echo $uc_mensagem_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($uc_mensagem->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $uc_mensagem_grid->FormKeyCountName ?>" id="<?php echo $uc_mensagem_grid->FormKeyCountName ?>" value="<?php echo $uc_mensagem_grid->KeyCount ?>">
<?php echo $uc_mensagem_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($uc_mensagem->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fuc_mensagemgrid">
</div>
<?php

// Close recordset
if ($uc_mensagem_grid->Recordset)
	$uc_mensagem_grid->Recordset->Close();
?>
<?php if ($uc_mensagem_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($uc_mensagem_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($uc_mensagem->Export == "") { ?>
<script type="text/javascript">
fuc_mensagemgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$uc_mensagem_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$uc_mensagem_grid->Page_Terminate();
?>
