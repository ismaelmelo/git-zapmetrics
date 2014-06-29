<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($tpmanutencao_grid)) $tpmanutencao_grid = new ctpmanutencao_grid();

// Page init
$tpmanutencao_grid->Page_Init();

// Page main
$tpmanutencao_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpmanutencao_grid->Page_Render();
?>
<?php if ($tpmanutencao->Export == "") { ?>
<script type="text/javascript">

// Page object
var tpmanutencao_grid = new ew_Page("tpmanutencao_grid");
tpmanutencao_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = tpmanutencao_grid.PageID; // For backward compatibility

// Form object
var ftpmanutencaogrid = new ew_Form("ftpmanutencaogrid");
ftpmanutencaogrid.FormKeyCountName = '<?php echo $tpmanutencao_grid->FormKeyCountName ?>';

// Validate form
ftpmanutencaogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_tpManutencao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmanutencao->no_tpManutencao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_modeloCalculo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmanutencao->ic_modeloCalculo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_utilizaFaseRoteiroCalculo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmanutencao->ic_utilizaFaseRoteiroCalculo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_parametro");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmanutencao->nu_parametro->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_ativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tpmanutencao->ic_ativo->FldCaption()) ?>");

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
ftpmanutencaogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_tpManutencao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_modeloCalculo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_utilizaFaseRoteiroCalculo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_parametro", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_ativo", false)) return false;
	return true;
}

// Form_CustomValidate event
ftpmanutencaogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpmanutencaogrid.ValidateRequired = true;
<?php } else { ?>
ftpmanutencaogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftpmanutencaogrid.Lists["x_nu_parametro"] = {"LinkField":"x_nu_parSisp","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_parSisp","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($tpmanutencao->getCurrentMasterTable() == "" && $tpmanutencao_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $tpmanutencao_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($tpmanutencao->CurrentAction == "gridadd") {
	if ($tpmanutencao->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$tpmanutencao_grid->TotalRecs = $tpmanutencao->SelectRecordCount();
			$tpmanutencao_grid->Recordset = $tpmanutencao_grid->LoadRecordset($tpmanutencao_grid->StartRec-1, $tpmanutencao_grid->DisplayRecs);
		} else {
			if ($tpmanutencao_grid->Recordset = $tpmanutencao_grid->LoadRecordset())
				$tpmanutencao_grid->TotalRecs = $tpmanutencao_grid->Recordset->RecordCount();
		}
		$tpmanutencao_grid->StartRec = 1;
		$tpmanutencao_grid->DisplayRecs = $tpmanutencao_grid->TotalRecs;
	} else {
		$tpmanutencao->CurrentFilter = "0=1";
		$tpmanutencao_grid->StartRec = 1;
		$tpmanutencao_grid->DisplayRecs = $tpmanutencao->GridAddRowCount;
	}
	$tpmanutencao_grid->TotalRecs = $tpmanutencao_grid->DisplayRecs;
	$tpmanutencao_grid->StopRec = $tpmanutencao_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tpmanutencao_grid->TotalRecs = $tpmanutencao->SelectRecordCount();
	} else {
		if ($tpmanutencao_grid->Recordset = $tpmanutencao_grid->LoadRecordset())
			$tpmanutencao_grid->TotalRecs = $tpmanutencao_grid->Recordset->RecordCount();
	}
	$tpmanutencao_grid->StartRec = 1;
	$tpmanutencao_grid->DisplayRecs = $tpmanutencao_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$tpmanutencao_grid->Recordset = $tpmanutencao_grid->LoadRecordset($tpmanutencao_grid->StartRec-1, $tpmanutencao_grid->DisplayRecs);
}
$tpmanutencao_grid->RenderOtherOptions();
?>
<?php $tpmanutencao_grid->ShowPageHeader(); ?>
<?php
$tpmanutencao_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="ftpmanutencaogrid" class="ewForm form-horizontal">
<div id="gmp_tpmanutencao" class="ewGridMiddlePanel">
<table id="tbl_tpmanutencaogrid" class="ewTable ewTableSeparate">
<?php echo $tpmanutencao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tpmanutencao_grid->RenderListOptions();

// Render list options (header, left)
$tpmanutencao_grid->ListOptions->Render("header", "left");
?>
<?php if ($tpmanutencao->no_tpManutencao->Visible) { // no_tpManutencao ?>
	<?php if ($tpmanutencao->SortUrl($tpmanutencao->no_tpManutencao) == "") { ?>
		<td><div id="elh_tpmanutencao_no_tpManutencao" class="tpmanutencao_no_tpManutencao"><div class="ewTableHeaderCaption"><?php echo $tpmanutencao->no_tpManutencao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpmanutencao_no_tpManutencao" class="tpmanutencao_no_tpManutencao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpmanutencao->no_tpManutencao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpmanutencao->no_tpManutencao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpmanutencao->no_tpManutencao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tpmanutencao->ic_modeloCalculo->Visible) { // ic_modeloCalculo ?>
	<?php if ($tpmanutencao->SortUrl($tpmanutencao->ic_modeloCalculo) == "") { ?>
		<td><div id="elh_tpmanutencao_ic_modeloCalculo" class="tpmanutencao_ic_modeloCalculo"><div class="ewTableHeaderCaption"><?php echo $tpmanutencao->ic_modeloCalculo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpmanutencao_ic_modeloCalculo" class="tpmanutencao_ic_modeloCalculo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpmanutencao->ic_modeloCalculo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpmanutencao->ic_modeloCalculo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpmanutencao->ic_modeloCalculo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tpmanutencao->ic_utilizaFaseRoteiroCalculo->Visible) { // ic_utilizaFaseRoteiroCalculo ?>
	<?php if ($tpmanutencao->SortUrl($tpmanutencao->ic_utilizaFaseRoteiroCalculo) == "") { ?>
		<td><div id="elh_tpmanutencao_ic_utilizaFaseRoteiroCalculo" class="tpmanutencao_ic_utilizaFaseRoteiroCalculo"><div class="ewTableHeaderCaption"><?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpmanutencao_ic_utilizaFaseRoteiroCalculo" class="tpmanutencao_ic_utilizaFaseRoteiroCalculo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpmanutencao->ic_utilizaFaseRoteiroCalculo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpmanutencao->ic_utilizaFaseRoteiroCalculo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tpmanutencao->nu_parametro->Visible) { // nu_parametro ?>
	<?php if ($tpmanutencao->SortUrl($tpmanutencao->nu_parametro) == "") { ?>
		<td><div id="elh_tpmanutencao_nu_parametro" class="tpmanutencao_nu_parametro"><div class="ewTableHeaderCaption"><?php echo $tpmanutencao->nu_parametro->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpmanutencao_nu_parametro" class="tpmanutencao_nu_parametro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpmanutencao->nu_parametro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpmanutencao->nu_parametro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpmanutencao->nu_parametro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tpmanutencao->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($tpmanutencao->SortUrl($tpmanutencao->ic_ativo) == "") { ?>
		<td><div id="elh_tpmanutencao_ic_ativo" class="tpmanutencao_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $tpmanutencao->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_tpmanutencao_ic_ativo" class="tpmanutencao_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tpmanutencao->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tpmanutencao->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tpmanutencao->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tpmanutencao_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$tpmanutencao_grid->StartRec = 1;
$tpmanutencao_grid->StopRec = $tpmanutencao_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($tpmanutencao_grid->FormKeyCountName) && ($tpmanutencao->CurrentAction == "gridadd" || $tpmanutencao->CurrentAction == "gridedit" || $tpmanutencao->CurrentAction == "F")) {
		$tpmanutencao_grid->KeyCount = $objForm->GetValue($tpmanutencao_grid->FormKeyCountName);
		$tpmanutencao_grid->StopRec = $tpmanutencao_grid->StartRec + $tpmanutencao_grid->KeyCount - 1;
	}
}
$tpmanutencao_grid->RecCnt = $tpmanutencao_grid->StartRec - 1;
if ($tpmanutencao_grid->Recordset && !$tpmanutencao_grid->Recordset->EOF) {
	$tpmanutencao_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $tpmanutencao_grid->StartRec > 1)
		$tpmanutencao_grid->Recordset->Move($tpmanutencao_grid->StartRec - 1);
} elseif (!$tpmanutencao->AllowAddDeleteRow && $tpmanutencao_grid->StopRec == 0) {
	$tpmanutencao_grid->StopRec = $tpmanutencao->GridAddRowCount;
}

// Initialize aggregate
$tpmanutencao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tpmanutencao->ResetAttrs();
$tpmanutencao_grid->RenderRow();
if ($tpmanutencao->CurrentAction == "gridadd")
	$tpmanutencao_grid->RowIndex = 0;
if ($tpmanutencao->CurrentAction == "gridedit")
	$tpmanutencao_grid->RowIndex = 0;
while ($tpmanutencao_grid->RecCnt < $tpmanutencao_grid->StopRec) {
	$tpmanutencao_grid->RecCnt++;
	if (intval($tpmanutencao_grid->RecCnt) >= intval($tpmanutencao_grid->StartRec)) {
		$tpmanutencao_grid->RowCnt++;
		if ($tpmanutencao->CurrentAction == "gridadd" || $tpmanutencao->CurrentAction == "gridedit" || $tpmanutencao->CurrentAction == "F") {
			$tpmanutencao_grid->RowIndex++;
			$objForm->Index = $tpmanutencao_grid->RowIndex;
			if ($objForm->HasValue($tpmanutencao_grid->FormActionName))
				$tpmanutencao_grid->RowAction = strval($objForm->GetValue($tpmanutencao_grid->FormActionName));
			elseif ($tpmanutencao->CurrentAction == "gridadd")
				$tpmanutencao_grid->RowAction = "insert";
			else
				$tpmanutencao_grid->RowAction = "";
		}

		// Set up key count
		$tpmanutencao_grid->KeyCount = $tpmanutencao_grid->RowIndex;

		// Init row class and style
		$tpmanutencao->ResetAttrs();
		$tpmanutencao->CssClass = "";
		if ($tpmanutencao->CurrentAction == "gridadd") {
			if ($tpmanutencao->CurrentMode == "copy") {
				$tpmanutencao_grid->LoadRowValues($tpmanutencao_grid->Recordset); // Load row values
				$tpmanutencao_grid->SetRecordKey($tpmanutencao_grid->RowOldKey, $tpmanutencao_grid->Recordset); // Set old record key
			} else {
				$tpmanutencao_grid->LoadDefaultValues(); // Load default values
				$tpmanutencao_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$tpmanutencao_grid->LoadRowValues($tpmanutencao_grid->Recordset); // Load row values
		}
		$tpmanutencao->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($tpmanutencao->CurrentAction == "gridadd") // Grid add
			$tpmanutencao->RowType = EW_ROWTYPE_ADD; // Render add
		if ($tpmanutencao->CurrentAction == "gridadd" && $tpmanutencao->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$tpmanutencao_grid->RestoreCurrentRowFormValues($tpmanutencao_grid->RowIndex); // Restore form values
		if ($tpmanutencao->CurrentAction == "gridedit") { // Grid edit
			if ($tpmanutencao->EventCancelled) {
				$tpmanutencao_grid->RestoreCurrentRowFormValues($tpmanutencao_grid->RowIndex); // Restore form values
			}
			if ($tpmanutencao_grid->RowAction == "insert")
				$tpmanutencao->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$tpmanutencao->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($tpmanutencao->CurrentAction == "gridedit" && ($tpmanutencao->RowType == EW_ROWTYPE_EDIT || $tpmanutencao->RowType == EW_ROWTYPE_ADD) && $tpmanutencao->EventCancelled) // Update failed
			$tpmanutencao_grid->RestoreCurrentRowFormValues($tpmanutencao_grid->RowIndex); // Restore form values
		if ($tpmanutencao->RowType == EW_ROWTYPE_EDIT) // Edit row
			$tpmanutencao_grid->EditRowCnt++;
		if ($tpmanutencao->CurrentAction == "F") // Confirm row
			$tpmanutencao_grid->RestoreCurrentRowFormValues($tpmanutencao_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$tpmanutencao->RowAttrs = array_merge($tpmanutencao->RowAttrs, array('data-rowindex'=>$tpmanutencao_grid->RowCnt, 'id'=>'r' . $tpmanutencao_grid->RowCnt . '_tpmanutencao', 'data-rowtype'=>$tpmanutencao->RowType));

		// Render row
		$tpmanutencao_grid->RenderRow();

		// Render list options
		$tpmanutencao_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($tpmanutencao_grid->RowAction <> "delete" && $tpmanutencao_grid->RowAction <> "insertdelete" && !($tpmanutencao_grid->RowAction == "insert" && $tpmanutencao->CurrentAction == "F" && $tpmanutencao_grid->EmptyRow())) {
?>
	<tr<?php echo $tpmanutencao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tpmanutencao_grid->ListOptions->Render("body", "left", $tpmanutencao_grid->RowCnt);
?>
	<?php if ($tpmanutencao->no_tpManutencao->Visible) { // no_tpManutencao ?>
		<td<?php echo $tpmanutencao->no_tpManutencao->CellAttributes() ?>>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_no_tpManutencao" class="control-group tpmanutencao_no_tpManutencao">
<input type="text" data-field="x_no_tpManutencao" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" size="30" maxlength="75" placeholder="<?php echo $tpmanutencao->no_tpManutencao->PlaceHolder ?>" value="<?php echo $tpmanutencao->no_tpManutencao->EditValue ?>"<?php echo $tpmanutencao->no_tpManutencao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_tpManutencao" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->no_tpManutencao->OldValue) ?>">
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_no_tpManutencao" class="control-group tpmanutencao_no_tpManutencao">
<span<?php echo $tpmanutencao->no_tpManutencao->ViewAttributes() ?>>
<?php echo $tpmanutencao->no_tpManutencao->EditValue ?></span>
</span>
<input type="hidden" data-field="x_no_tpManutencao" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->no_tpManutencao->CurrentValue) ?>">
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpmanutencao->no_tpManutencao->ViewAttributes() ?>>
<?php echo $tpmanutencao->no_tpManutencao->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_tpManutencao" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->no_tpManutencao->FormValue) ?>">
<input type="hidden" data-field="x_no_tpManutencao" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->no_tpManutencao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpmanutencao_grid->PageObjName . "_row_" . $tpmanutencao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_tpManutencao" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_tpManutencao" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_tpManutencao->CurrentValue) ?>">
<input type="hidden" data-field="x_nu_tpManutencao" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_nu_tpManutencao" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_nu_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_tpManutencao->OldValue) ?>">
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_EDIT || $tpmanutencao->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_nu_tpManutencao" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_tpManutencao" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_tpManutencao->CurrentValue) ?>">
<?php } ?>
	<?php if ($tpmanutencao->ic_modeloCalculo->Visible) { // ic_modeloCalculo ?>
		<td<?php echo $tpmanutencao->ic_modeloCalculo->CellAttributes() ?>>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_ic_modeloCalculo" class="control-group tpmanutencao_ic_modeloCalculo">
<div id="tp_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" value="{value}"<?php echo $tpmanutencao->ic_modeloCalculo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_modeloCalculo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_modeloCalculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_modeloCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_modeloCalculo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->ic_modeloCalculo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_modeloCalculo" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_modeloCalculo->OldValue) ?>">
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_ic_modeloCalculo" class="control-group tpmanutencao_ic_modeloCalculo">
<div id="tp_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" value="{value}"<?php echo $tpmanutencao->ic_modeloCalculo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_modeloCalculo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_modeloCalculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_modeloCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_modeloCalculo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->ic_modeloCalculo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpmanutencao->ic_modeloCalculo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_modeloCalculo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_modeloCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_modeloCalculo->FormValue) ?>">
<input type="hidden" data-field="x_ic_modeloCalculo" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_modeloCalculo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpmanutencao_grid->PageObjName . "_row_" . $tpmanutencao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tpmanutencao->ic_utilizaFaseRoteiroCalculo->Visible) { // ic_utilizaFaseRoteiroCalculo ?>
		<td<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->CellAttributes() ?>>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_ic_utilizaFaseRoteiroCalculo" class="control-group tpmanutencao_ic_utilizaFaseRoteiroCalculo">
<div id="tp_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" value="{value}"<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_utilizaFaseRoteiroCalculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_utilizaFaseRoteiroCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->ic_utilizaFaseRoteiroCalculo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_utilizaFaseRoteiroCalculo" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_utilizaFaseRoteiroCalculo->OldValue) ?>">
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_ic_utilizaFaseRoteiroCalculo" class="control-group tpmanutencao_ic_utilizaFaseRoteiroCalculo">
<div id="tp_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" value="{value}"<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_utilizaFaseRoteiroCalculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_utilizaFaseRoteiroCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->ic_utilizaFaseRoteiroCalculo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_utilizaFaseRoteiroCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_utilizaFaseRoteiroCalculo->FormValue) ?>">
<input type="hidden" data-field="x_ic_utilizaFaseRoteiroCalculo" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_utilizaFaseRoteiroCalculo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpmanutencao_grid->PageObjName . "_row_" . $tpmanutencao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tpmanutencao->nu_parametro->Visible) { // nu_parametro ?>
		<td<?php echo $tpmanutencao->nu_parametro->CellAttributes() ?>>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_nu_parametro" class="control-group tpmanutencao_nu_parametro">
<select data-field="x_nu_parametro" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro"<?php echo $tpmanutencao->nu_parametro->EditAttributes() ?>>
<?php
if (is_array($tpmanutencao->nu_parametro->EditValue)) {
	$arwrk = $tpmanutencao->nu_parametro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->nu_parametro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->nu_parametro->OldValue = "";
?>
</select>
<script type="text/javascript">
ftpmanutencaogrid.Lists["x_nu_parametro"].Options = <?php echo (is_array($tpmanutencao->nu_parametro->EditValue)) ? ew_ArrayToJson($tpmanutencao->nu_parametro->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_parametro" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_parametro->OldValue) ?>">
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_nu_parametro" class="control-group tpmanutencao_nu_parametro">
<select data-field="x_nu_parametro" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro"<?php echo $tpmanutencao->nu_parametro->EditAttributes() ?>>
<?php
if (is_array($tpmanutencao->nu_parametro->EditValue)) {
	$arwrk = $tpmanutencao->nu_parametro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->nu_parametro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->nu_parametro->OldValue = "";
?>
</select>
<script type="text/javascript">
ftpmanutencaogrid.Lists["x_nu_parametro"].Options = <?php echo (is_array($tpmanutencao->nu_parametro->EditValue)) ? ew_ArrayToJson($tpmanutencao->nu_parametro->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpmanutencao->nu_parametro->ViewAttributes() ?>>
<?php echo $tpmanutencao->nu_parametro->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_parametro" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_parametro->FormValue) ?>">
<input type="hidden" data-field="x_nu_parametro" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_parametro->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpmanutencao_grid->PageObjName . "_row_" . $tpmanutencao_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tpmanutencao->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $tpmanutencao->ic_ativo->CellAttributes() ?>>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_ic_ativo" class="control-group tpmanutencao_ic_ativo">
<div id="tp_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $tpmanutencao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->ic_ativo->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_ativo->OldValue) ?>">
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $tpmanutencao_grid->RowCnt ?>_tpmanutencao_ic_ativo" class="control-group tpmanutencao_ic_ativo">
<div id="tp_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $tpmanutencao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->ic_ativo->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tpmanutencao->ic_ativo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_ativo->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_ativo->FormValue) ?>">
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_ativo->OldValue) ?>">
<?php } ?>
<a id="<?php echo $tpmanutencao_grid->PageObjName . "_row_" . $tpmanutencao_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tpmanutencao_grid->ListOptions->Render("body", "right", $tpmanutencao_grid->RowCnt);
?>
	</tr>
<?php if ($tpmanutencao->RowType == EW_ROWTYPE_ADD || $tpmanutencao->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftpmanutencaogrid.UpdateOpts(<?php echo $tpmanutencao_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($tpmanutencao->CurrentAction <> "gridadd" || $tpmanutencao->CurrentMode == "copy")
		if (!$tpmanutencao_grid->Recordset->EOF) $tpmanutencao_grid->Recordset->MoveNext();
}
?>
<?php
	if ($tpmanutencao->CurrentMode == "add" || $tpmanutencao->CurrentMode == "copy" || $tpmanutencao->CurrentMode == "edit") {
		$tpmanutencao_grid->RowIndex = '$rowindex$';
		$tpmanutencao_grid->LoadDefaultValues();

		// Set row properties
		$tpmanutencao->ResetAttrs();
		$tpmanutencao->RowAttrs = array_merge($tpmanutencao->RowAttrs, array('data-rowindex'=>$tpmanutencao_grid->RowIndex, 'id'=>'r0_tpmanutencao', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($tpmanutencao->RowAttrs["class"], "ewTemplate");
		$tpmanutencao->RowType = EW_ROWTYPE_ADD;

		// Render row
		$tpmanutencao_grid->RenderRow();

		// Render list options
		$tpmanutencao_grid->RenderListOptions();
		$tpmanutencao_grid->StartRowCnt = 0;
?>
	<tr<?php echo $tpmanutencao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tpmanutencao_grid->ListOptions->Render("body", "left", $tpmanutencao_grid->RowIndex);
?>
	<?php if ($tpmanutencao->no_tpManutencao->Visible) { // no_tpManutencao ?>
		<td>
<?php if ($tpmanutencao->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_tpManutencao" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" size="30" maxlength="75" placeholder="<?php echo $tpmanutencao->no_tpManutencao->PlaceHolder ?>" value="<?php echo $tpmanutencao->no_tpManutencao->EditValue ?>"<?php echo $tpmanutencao->no_tpManutencao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $tpmanutencao->no_tpManutencao->ViewAttributes() ?>>
<?php echo $tpmanutencao->no_tpManutencao->ViewValue ?></span>
<input type="hidden" data-field="x_no_tpManutencao" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->no_tpManutencao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_tpManutencao" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_no_tpManutencao" value="<?php echo ew_HtmlEncode($tpmanutencao->no_tpManutencao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($tpmanutencao->ic_modeloCalculo->Visible) { // ic_modeloCalculo ?>
		<td>
<?php if ($tpmanutencao->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" value="{value}"<?php echo $tpmanutencao->ic_modeloCalculo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_modeloCalculo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_modeloCalculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_modeloCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_modeloCalculo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->ic_modeloCalculo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $tpmanutencao->ic_modeloCalculo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_modeloCalculo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_modeloCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_modeloCalculo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_modeloCalculo" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_modeloCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_modeloCalculo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($tpmanutencao->ic_utilizaFaseRoteiroCalculo->Visible) { // ic_utilizaFaseRoteiroCalculo ?>
		<td>
<?php if ($tpmanutencao->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" value="{value}"<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_utilizaFaseRoteiroCalculo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_utilizaFaseRoteiroCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->ic_utilizaFaseRoteiroCalculo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_utilizaFaseRoteiroCalculo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_utilizaFaseRoteiroCalculo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_utilizaFaseRoteiroCalculo" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_utilizaFaseRoteiroCalculo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_utilizaFaseRoteiroCalculo->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($tpmanutencao->nu_parametro->Visible) { // nu_parametro ?>
		<td>
<?php if ($tpmanutencao->CurrentAction <> "F") { ?>
<select data-field="x_nu_parametro" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro"<?php echo $tpmanutencao->nu_parametro->EditAttributes() ?>>
<?php
if (is_array($tpmanutencao->nu_parametro->EditValue)) {
	$arwrk = $tpmanutencao->nu_parametro->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->nu_parametro->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->nu_parametro->OldValue = "";
?>
</select>
<script type="text/javascript">
ftpmanutencaogrid.Lists["x_nu_parametro"].Options = <?php echo (is_array($tpmanutencao->nu_parametro->EditValue)) ? ew_ArrayToJson($tpmanutencao->nu_parametro->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $tpmanutencao->nu_parametro->ViewAttributes() ?>>
<?php echo $tpmanutencao->nu_parametro->ViewValue ?></span>
<input type="hidden" data-field="x_nu_parametro" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_parametro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_parametro" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_nu_parametro" value="<?php echo ew_HtmlEncode($tpmanutencao->nu_parametro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($tpmanutencao->ic_ativo->Visible) { // ic_ativo ?>
		<td>
<?php if ($tpmanutencao->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" value="{value}"<?php echo $tpmanutencao->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tpmanutencao->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tpmanutencao->ic_ativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tpmanutencao->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tpmanutencao->ic_ativo->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $tpmanutencao->ic_ativo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_ativo->ViewValue ?></span>
<input type="hidden" data-field="x_ic_ativo" name="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="x<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_ativo->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_ativo" name="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" id="o<?php echo $tpmanutencao_grid->RowIndex ?>_ic_ativo" value="<?php echo ew_HtmlEncode($tpmanutencao->ic_ativo->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$tpmanutencao_grid->ListOptions->Render("body", "right", $tpmanutencao_grid->RowCnt);
?>
<script type="text/javascript">
ftpmanutencaogrid.UpdateOpts(<?php echo $tpmanutencao_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($tpmanutencao->CurrentMode == "add" || $tpmanutencao->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $tpmanutencao_grid->FormKeyCountName ?>" id="<?php echo $tpmanutencao_grid->FormKeyCountName ?>" value="<?php echo $tpmanutencao_grid->KeyCount ?>">
<?php echo $tpmanutencao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tpmanutencao->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $tpmanutencao_grid->FormKeyCountName ?>" id="<?php echo $tpmanutencao_grid->FormKeyCountName ?>" value="<?php echo $tpmanutencao_grid->KeyCount ?>">
<?php echo $tpmanutencao_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tpmanutencao->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ftpmanutencaogrid">
</div>
<?php

// Close recordset
if ($tpmanutencao_grid->Recordset)
	$tpmanutencao_grid->Recordset->Close();
?>
<?php if ($tpmanutencao_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($tpmanutencao_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($tpmanutencao->Export == "") { ?>
<script type="text/javascript">
ftpmanutencaogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$tpmanutencao_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$tpmanutencao_grid->Page_Terminate();
?>
