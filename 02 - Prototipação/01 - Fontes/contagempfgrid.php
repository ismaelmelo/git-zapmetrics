<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($contagempf_grid)) $contagempf_grid = new ccontagempf_grid();

// Page init
$contagempf_grid->Page_Init();

// Page main
$contagempf_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_grid->Page_Render();
?>
<?php if ($contagempf->Export == "") { ?>
<script type="text/javascript">

// Page object
var contagempf_grid = new ew_Page("contagempf_grid");
contagempf_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = contagempf_grid.PageID; // For backward compatibility

// Form object
var fcontagempfgrid = new ew_Form("fcontagempfgrid");
fcontagempfgrid.FormKeyCountName = '<?php echo $contagempf_grid->FormKeyCountName ?>';

// Validate form
fcontagempfgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_tpMetrica");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->nu_tpMetrica->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpContagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->nu_tpContagem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_sistema");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->nu_sistema->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_stContagem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($contagempf->ic_stContagem->FldCaption()) ?>");

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
fcontagempfgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_tpMetrica", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_tpContagem", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_sistema", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_faseMedida", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_stContagem", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pc_varFasesRoteiro", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vr_pfFaturamento", false)) return false;
	return true;
}

// Form_CustomValidate event
fcontagempfgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempfgrid.ValidateRequired = true;
<?php } else { ?>
fcontagempfgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontagempfgrid.Lists["x_nu_tpMetrica"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfgrid.Lists["x_nu_tpContagem"] = {"LinkField":"x_nu_tpContagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpContagem","","",""],"ParentFields":["x_nu_tpMetrica"],"FilterFields":["x_nu_tpMetrica"],"Options":[]};
fcontagempfgrid.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempfgrid.Lists["x_nu_faseMedida"] = {"LinkField":"x_nu_faseRoteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":["x_nu_roteiro"],"FilterFields":["x_nu_roteiro"],"Options":[]};
fcontagempfgrid.Lists["x_nu_usuarioLogado"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($contagempf->getCurrentMasterTable() == "" && $contagempf_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $contagempf_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($contagempf->CurrentAction == "gridadd") {
	if ($contagempf->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$contagempf_grid->TotalRecs = $contagempf->SelectRecordCount();
			$contagempf_grid->Recordset = $contagempf_grid->LoadRecordset($contagempf_grid->StartRec-1, $contagempf_grid->DisplayRecs);
		} else {
			if ($contagempf_grid->Recordset = $contagempf_grid->LoadRecordset())
				$contagempf_grid->TotalRecs = $contagempf_grid->Recordset->RecordCount();
		}
		$contagempf_grid->StartRec = 1;
		$contagempf_grid->DisplayRecs = $contagempf_grid->TotalRecs;
	} else {
		$contagempf->CurrentFilter = "0=1";
		$contagempf_grid->StartRec = 1;
		$contagempf_grid->DisplayRecs = $contagempf->GridAddRowCount;
	}
	$contagempf_grid->TotalRecs = $contagempf_grid->DisplayRecs;
	$contagempf_grid->StopRec = $contagempf_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$contagempf_grid->TotalRecs = $contagempf->SelectRecordCount();
	} else {
		if ($contagempf_grid->Recordset = $contagempf_grid->LoadRecordset())
			$contagempf_grid->TotalRecs = $contagempf_grid->Recordset->RecordCount();
	}
	$contagempf_grid->StartRec = 1;
	$contagempf_grid->DisplayRecs = $contagempf_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$contagempf_grid->Recordset = $contagempf_grid->LoadRecordset($contagempf_grid->StartRec-1, $contagempf_grid->DisplayRecs);
}
$contagempf_grid->RenderOtherOptions();
?>
<?php $contagempf_grid->ShowPageHeader(); ?>
<?php
$contagempf_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fcontagempfgrid" class="ewForm form-horizontal">
<div id="gmp_contagempf" class="ewGridMiddlePanel">
<table id="tbl_contagempfgrid" class="ewTable ewTableSeparate">
<?php echo $contagempf->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$contagempf_grid->RenderListOptions();

// Render list options (header, left)
$contagempf_grid->ListOptions->Render("header", "left");
?>
<?php if ($contagempf->nu_contagem->Visible) { // nu_contagem ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_contagem) == "") { ?>
		<td><div id="elh_contagempf_nu_contagem" class="contagempf_nu_contagem"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_contagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_nu_contagem" class="contagempf_nu_contagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_contagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_contagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_contagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_tpMetrica) == "") { ?>
		<td><div id="elh_contagempf_nu_tpMetrica" class="contagempf_nu_tpMetrica"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_tpMetrica->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_nu_tpMetrica" class="contagempf_nu_tpMetrica">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_tpMetrica->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_tpMetrica->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_tpMetrica->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_tpContagem) == "") { ?>
		<td><div id="elh_contagempf_nu_tpContagem" class="contagempf_nu_tpContagem"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_tpContagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_nu_tpContagem" class="contagempf_nu_tpContagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_tpContagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_tpContagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_tpContagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_sistema->Visible) { // nu_sistema ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_sistema) == "") { ?>
		<td><div id="elh_contagempf_nu_sistema" class="contagempf_nu_sistema"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_sistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_nu_sistema" class="contagempf_nu_sistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_sistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_sistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_sistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_faseMedida->Visible) { // nu_faseMedida ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_faseMedida) == "") { ?>
		<td><div id="elh_contagempf_nu_faseMedida" class="contagempf_nu_faseMedida"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_faseMedida->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_nu_faseMedida" class="contagempf_nu_faseMedida">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_faseMedida->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_faseMedida->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_faseMedida->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_usuarioLogado->Visible) { // nu_usuarioLogado ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_usuarioLogado) == "") { ?>
		<td><div id="elh_contagempf_nu_usuarioLogado" class="contagempf_nu_usuarioLogado"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_usuarioLogado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_nu_usuarioLogado" class="contagempf_nu_usuarioLogado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_usuarioLogado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_usuarioLogado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_usuarioLogado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->ic_stContagem->Visible) { // ic_stContagem ?>
	<?php if ($contagempf->SortUrl($contagempf->ic_stContagem) == "") { ?>
		<td><div id="elh_contagempf_ic_stContagem" class="contagempf_ic_stContagem"><div class="ewTableHeaderCaption"><?php echo $contagempf->ic_stContagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_ic_stContagem" class="contagempf_ic_stContagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->ic_stContagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->ic_stContagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->ic_stContagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
	<?php if ($contagempf->SortUrl($contagempf->pc_varFasesRoteiro) == "") { ?>
		<td><div id="elh_contagempf_pc_varFasesRoteiro" class="contagempf_pc_varFasesRoteiro"><div class="ewTableHeaderCaption"><?php echo $contagempf->pc_varFasesRoteiro->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_pc_varFasesRoteiro" class="contagempf_pc_varFasesRoteiro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->pc_varFasesRoteiro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->pc_varFasesRoteiro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->pc_varFasesRoteiro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
	<?php if ($contagempf->SortUrl($contagempf->vr_pfFaturamento) == "") { ?>
		<td><div id="elh_contagempf_vr_pfFaturamento" class="contagempf_vr_pfFaturamento"><div class="ewTableHeaderCaption"><?php echo $contagempf->vr_pfFaturamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_contagempf_vr_pfFaturamento" class="contagempf_vr_pfFaturamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->vr_pfFaturamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->vr_pfFaturamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->vr_pfFaturamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$contagempf_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$contagempf_grid->StartRec = 1;
$contagempf_grid->StopRec = $contagempf_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($contagempf_grid->FormKeyCountName) && ($contagempf->CurrentAction == "gridadd" || $contagempf->CurrentAction == "gridedit" || $contagempf->CurrentAction == "F")) {
		$contagempf_grid->KeyCount = $objForm->GetValue($contagempf_grid->FormKeyCountName);
		$contagempf_grid->StopRec = $contagempf_grid->StartRec + $contagempf_grid->KeyCount - 1;
	}
}
$contagempf_grid->RecCnt = $contagempf_grid->StartRec - 1;
if ($contagempf_grid->Recordset && !$contagempf_grid->Recordset->EOF) {
	$contagempf_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $contagempf_grid->StartRec > 1)
		$contagempf_grid->Recordset->Move($contagempf_grid->StartRec - 1);
} elseif (!$contagempf->AllowAddDeleteRow && $contagempf_grid->StopRec == 0) {
	$contagempf_grid->StopRec = $contagempf->GridAddRowCount;
}

// Initialize aggregate
$contagempf->RowType = EW_ROWTYPE_AGGREGATEINIT;
$contagempf->ResetAttrs();
$contagempf_grid->RenderRow();
if ($contagempf->CurrentAction == "gridadd")
	$contagempf_grid->RowIndex = 0;
if ($contagempf->CurrentAction == "gridedit")
	$contagempf_grid->RowIndex = 0;
while ($contagempf_grid->RecCnt < $contagempf_grid->StopRec) {
	$contagempf_grid->RecCnt++;
	if (intval($contagempf_grid->RecCnt) >= intval($contagempf_grid->StartRec)) {
		$contagempf_grid->RowCnt++;
		if ($contagempf->CurrentAction == "gridadd" || $contagempf->CurrentAction == "gridedit" || $contagempf->CurrentAction == "F") {
			$contagempf_grid->RowIndex++;
			$objForm->Index = $contagempf_grid->RowIndex;
			if ($objForm->HasValue($contagempf_grid->FormActionName))
				$contagempf_grid->RowAction = strval($objForm->GetValue($contagempf_grid->FormActionName));
			elseif ($contagempf->CurrentAction == "gridadd")
				$contagempf_grid->RowAction = "insert";
			else
				$contagempf_grid->RowAction = "";
		}

		// Set up key count
		$contagempf_grid->KeyCount = $contagempf_grid->RowIndex;

		// Init row class and style
		$contagempf->ResetAttrs();
		$contagempf->CssClass = "";
		if ($contagempf->CurrentAction == "gridadd") {
			if ($contagempf->CurrentMode == "copy") {
				$contagempf_grid->LoadRowValues($contagempf_grid->Recordset); // Load row values
				$contagempf_grid->SetRecordKey($contagempf_grid->RowOldKey, $contagempf_grid->Recordset); // Set old record key
			} else {
				$contagempf_grid->LoadDefaultValues(); // Load default values
				$contagempf_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$contagempf_grid->LoadRowValues($contagempf_grid->Recordset); // Load row values
		}
		$contagempf->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($contagempf->CurrentAction == "gridadd") // Grid add
			$contagempf->RowType = EW_ROWTYPE_ADD; // Render add
		if ($contagempf->CurrentAction == "gridadd" && $contagempf->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$contagempf_grid->RestoreCurrentRowFormValues($contagempf_grid->RowIndex); // Restore form values
		if ($contagempf->CurrentAction == "gridedit") { // Grid edit
			if ($contagempf->EventCancelled) {
				$contagempf_grid->RestoreCurrentRowFormValues($contagempf_grid->RowIndex); // Restore form values
			}
			if ($contagempf_grid->RowAction == "insert")
				$contagempf->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$contagempf->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($contagempf->CurrentAction == "gridedit" && ($contagempf->RowType == EW_ROWTYPE_EDIT || $contagempf->RowType == EW_ROWTYPE_ADD) && $contagempf->EventCancelled) // Update failed
			$contagempf_grid->RestoreCurrentRowFormValues($contagempf_grid->RowIndex); // Restore form values
		if ($contagempf->RowType == EW_ROWTYPE_EDIT) // Edit row
			$contagempf_grid->EditRowCnt++;
		if ($contagempf->CurrentAction == "F") // Confirm row
			$contagempf_grid->RestoreCurrentRowFormValues($contagempf_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$contagempf->RowAttrs = array_merge($contagempf->RowAttrs, array('data-rowindex'=>$contagempf_grid->RowCnt, 'id'=>'r' . $contagempf_grid->RowCnt . '_contagempf', 'data-rowtype'=>$contagempf->RowType));

		// Render row
		$contagempf_grid->RenderRow();

		// Render list options
		$contagempf_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($contagempf_grid->RowAction <> "delete" && $contagempf_grid->RowAction <> "insertdelete" && !($contagempf_grid->RowAction == "insert" && $contagempf->CurrentAction == "F" && $contagempf_grid->EmptyRow())) {
?>
	<tr<?php echo $contagempf->RowAttributes() ?>>
<?php

// Render list options (body, left)
$contagempf_grid->ListOptions->Render("body", "left", $contagempf_grid->RowCnt);
?>
	<?php if ($contagempf->nu_contagem->Visible) { // nu_contagem ?>
		<td<?php echo $contagempf->nu_contagem->CellAttributes() ?>>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_contagem" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf->nu_contagem->OldValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_nu_contagem" class="control-group contagempf_nu_contagem">
<span<?php echo $contagempf->nu_contagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_contagem->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_contagem" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf->nu_contagem->CurrentValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf->nu_contagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_contagem->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_contagem" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf->nu_contagem->FormValue) ?>">
<input type="hidden" data-field="x_nu_contagem" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf->nu_contagem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_grid->PageObjName . "_row_" . $contagempf_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
		<td<?php echo $contagempf->nu_tpMetrica->CellAttributes() ?>>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_nu_tpMetrica" class="control-group contagempf_nu_tpMetrica">
<?php $contagempf->nu_tpMetrica->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $contagempf_grid->RowIndex . "_nu_tpContagem']); " . @$contagempf->nu_tpMetrica->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpMetrica" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica"<?php echo $contagempf->nu_tpMetrica->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpMetrica->EditValue)) {
	$arwrk = $contagempf->nu_tpMetrica->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpMetrica->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_tpMetrica->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_tpMetrica"].Options = <?php echo (is_array($contagempf->nu_tpMetrica->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpMetrica->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_tpMetrica" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" value="<?php echo ew_HtmlEncode($contagempf->nu_tpMetrica->OldValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_nu_tpMetrica" class="control-group contagempf_nu_tpMetrica">
<span<?php echo $contagempf->nu_tpMetrica->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpMetrica->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_tpMetrica" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" value="<?php echo ew_HtmlEncode($contagempf->nu_tpMetrica->CurrentValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf->nu_tpMetrica->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpMetrica->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_tpMetrica" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" value="<?php echo ew_HtmlEncode($contagempf->nu_tpMetrica->FormValue) ?>">
<input type="hidden" data-field="x_nu_tpMetrica" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" value="<?php echo ew_HtmlEncode($contagempf->nu_tpMetrica->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_grid->PageObjName . "_row_" . $contagempf_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
		<td<?php echo $contagempf->nu_tpContagem->CellAttributes() ?>>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_nu_tpContagem" class="control-group contagempf_nu_tpContagem">
<select data-field="x_nu_tpContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem"<?php echo $contagempf->nu_tpContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpContagem->EditValue)) {
	$arwrk = $contagempf->nu_tpContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpContagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_tpContagem->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_tpContagem"].Options = <?php echo (is_array($contagempf->nu_tpContagem->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpContagem->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_tpContagem" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" value="<?php echo ew_HtmlEncode($contagempf->nu_tpContagem->OldValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_nu_tpContagem" class="control-group contagempf_nu_tpContagem">
<span<?php echo $contagempf->nu_tpContagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpContagem->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_tpContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" value="<?php echo ew_HtmlEncode($contagempf->nu_tpContagem->CurrentValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf->nu_tpContagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpContagem->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_tpContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" value="<?php echo ew_HtmlEncode($contagempf->nu_tpContagem->FormValue) ?>">
<input type="hidden" data-field="x_nu_tpContagem" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" value="<?php echo ew_HtmlEncode($contagempf->nu_tpContagem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_grid->PageObjName . "_row_" . $contagempf_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_sistema->Visible) { // nu_sistema ?>
		<td<?php echo $contagempf->nu_sistema->CellAttributes() ?>>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_nu_sistema" class="control-group contagempf_nu_sistema">
<select data-field="x_nu_sistema" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema"<?php echo $contagempf->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_sistema->EditValue)) {
	$arwrk = $contagempf->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_sistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_sistema"].Options = <?php echo (is_array($contagempf->nu_sistema->EditValue)) ? ew_ArrayToJson($contagempf->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_sistema" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($contagempf->nu_sistema->OldValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_nu_sistema" class="control-group contagempf_nu_sistema">
<select data-field="x_nu_sistema" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema"<?php echo $contagempf->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_sistema->EditValue)) {
	$arwrk = $contagempf->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_sistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_sistema"].Options = <?php echo (is_array($contagempf->nu_sistema->EditValue)) ? ew_ArrayToJson($contagempf->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf->nu_sistema->ViewAttributes() ?>>
<?php echo $contagempf->nu_sistema->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_sistema" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($contagempf->nu_sistema->FormValue) ?>">
<input type="hidden" data-field="x_nu_sistema" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($contagempf->nu_sistema->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_grid->PageObjName . "_row_" . $contagempf_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_faseMedida->Visible) { // nu_faseMedida ?>
		<td<?php echo $contagempf->nu_faseMedida->CellAttributes() ?>>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_nu_faseMedida" class="control-group contagempf_nu_faseMedida">
<select data-field="x_nu_faseMedida" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida"<?php echo $contagempf->nu_faseMedida->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_faseMedida->EditValue)) {
	$arwrk = $contagempf->nu_faseMedida->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_faseMedida->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_faseMedida->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_faseMedida"].Options = <?php echo (is_array($contagempf->nu_faseMedida->EditValue)) ? ew_ArrayToJson($contagempf->nu_faseMedida->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_faseMedida" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" value="<?php echo ew_HtmlEncode($contagempf->nu_faseMedida->OldValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_nu_faseMedida" class="control-group contagempf_nu_faseMedida">
<select data-field="x_nu_faseMedida" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida"<?php echo $contagempf->nu_faseMedida->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_faseMedida->EditValue)) {
	$arwrk = $contagempf->nu_faseMedida->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_faseMedida->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_faseMedida->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_faseMedida"].Options = <?php echo (is_array($contagempf->nu_faseMedida->EditValue)) ? ew_ArrayToJson($contagempf->nu_faseMedida->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf->nu_faseMedida->ViewAttributes() ?>>
<?php echo $contagempf->nu_faseMedida->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_faseMedida" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" value="<?php echo ew_HtmlEncode($contagempf->nu_faseMedida->FormValue) ?>">
<input type="hidden" data-field="x_nu_faseMedida" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" value="<?php echo ew_HtmlEncode($contagempf->nu_faseMedida->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_grid->PageObjName . "_row_" . $contagempf_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_usuarioLogado->Visible) { // nu_usuarioLogado ?>
		<td<?php echo $contagempf->nu_usuarioLogado->CellAttributes() ?>>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_usuarioLogado" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" value="<?php echo ew_HtmlEncode($contagempf->nu_usuarioLogado->OldValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf->nu_usuarioLogado->ViewAttributes() ?>>
<?php echo $contagempf->nu_usuarioLogado->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_usuarioLogado" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" value="<?php echo ew_HtmlEncode($contagempf->nu_usuarioLogado->FormValue) ?>">
<input type="hidden" data-field="x_nu_usuarioLogado" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" value="<?php echo ew_HtmlEncode($contagempf->nu_usuarioLogado->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_grid->PageObjName . "_row_" . $contagempf_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->ic_stContagem->Visible) { // ic_stContagem ?>
		<td<?php echo $contagempf->ic_stContagem->CellAttributes() ?>>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_ic_stContagem" class="control-group contagempf_ic_stContagem">
<select data-field="x_ic_stContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem"<?php echo $contagempf->ic_stContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->ic_stContagem->EditValue)) {
	$arwrk = $contagempf->ic_stContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->ic_stContagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->ic_stContagem->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_ic_stContagem" name="o<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" id="o<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" value="<?php echo ew_HtmlEncode($contagempf->ic_stContagem->OldValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_ic_stContagem" class="control-group contagempf_ic_stContagem">
<select data-field="x_ic_stContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem"<?php echo $contagempf->ic_stContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->ic_stContagem->EditValue)) {
	$arwrk = $contagempf->ic_stContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->ic_stContagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->ic_stContagem->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf->ic_stContagem->ViewAttributes() ?>>
<?php echo $contagempf->ic_stContagem->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_stContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" value="<?php echo ew_HtmlEncode($contagempf->ic_stContagem->FormValue) ?>">
<input type="hidden" data-field="x_ic_stContagem" name="o<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" id="o<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" value="<?php echo ew_HtmlEncode($contagempf->ic_stContagem->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_grid->PageObjName . "_row_" . $contagempf_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
		<td<?php echo $contagempf->pc_varFasesRoteiro->CellAttributes() ?>>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_pc_varFasesRoteiro" class="control-group contagempf_pc_varFasesRoteiro">
<input type="text" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" size="30" placeholder="<?php echo $contagempf->pc_varFasesRoteiro->PlaceHolder ?>" value="<?php echo $contagempf->pc_varFasesRoteiro->EditValue ?>"<?php echo $contagempf->pc_varFasesRoteiro->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="o<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" id="o<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf->pc_varFasesRoteiro->OldValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_pc_varFasesRoteiro" class="control-group contagempf_pc_varFasesRoteiro">
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf->pc_varFasesRoteiro->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf->pc_varFasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf->pc_varFasesRoteiro->ListViewValue() ?></span>
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf->pc_varFasesRoteiro->FormValue) ?>">
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="o<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" id="o<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf->pc_varFasesRoteiro->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_grid->PageObjName . "_row_" . $contagempf_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
		<td<?php echo $contagempf->vr_pfFaturamento->CellAttributes() ?>>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_vr_pfFaturamento" class="control-group contagempf_vr_pfFaturamento">
<input type="text" data-field="x_vr_pfFaturamento" name="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" id="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" size="30" placeholder="<?php echo $contagempf->vr_pfFaturamento->PlaceHolder ?>" value="<?php echo $contagempf->vr_pfFaturamento->EditValue ?>"<?php echo $contagempf->vr_pfFaturamento->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_vr_pfFaturamento" name="o<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" id="o<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" value="<?php echo ew_HtmlEncode($contagempf->vr_pfFaturamento->OldValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $contagempf_grid->RowCnt ?>_contagempf_vr_pfFaturamento" class="control-group contagempf_vr_pfFaturamento">
<span<?php echo $contagempf->vr_pfFaturamento->ViewAttributes() ?>>
<?php echo $contagempf->vr_pfFaturamento->EditValue ?></span>
</span>
<input type="hidden" data-field="x_vr_pfFaturamento" name="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" id="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" value="<?php echo ew_HtmlEncode($contagempf->vr_pfFaturamento->CurrentValue) ?>">
<?php } ?>
<?php if ($contagempf->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $contagempf->vr_pfFaturamento->ViewAttributes() ?>>
<?php echo $contagempf->vr_pfFaturamento->ListViewValue() ?></span>
<input type="hidden" data-field="x_vr_pfFaturamento" name="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" id="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" value="<?php echo ew_HtmlEncode($contagempf->vr_pfFaturamento->FormValue) ?>">
<input type="hidden" data-field="x_vr_pfFaturamento" name="o<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" id="o<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" value="<?php echo ew_HtmlEncode($contagempf->vr_pfFaturamento->OldValue) ?>">
<?php } ?>
<a id="<?php echo $contagempf_grid->PageObjName . "_row_" . $contagempf_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$contagempf_grid->ListOptions->Render("body", "right", $contagempf_grid->RowCnt);
?>
	</tr>
<?php if ($contagempf->RowType == EW_ROWTYPE_ADD || $contagempf->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fcontagempfgrid.UpdateOpts(<?php echo $contagempf_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($contagempf->CurrentAction <> "gridadd" || $contagempf->CurrentMode == "copy")
		if (!$contagempf_grid->Recordset->EOF) $contagempf_grid->Recordset->MoveNext();
}
?>
<?php
	if ($contagempf->CurrentMode == "add" || $contagempf->CurrentMode == "copy" || $contagempf->CurrentMode == "edit") {
		$contagempf_grid->RowIndex = '$rowindex$';
		$contagempf_grid->LoadDefaultValues();

		// Set row properties
		$contagempf->ResetAttrs();
		$contagempf->RowAttrs = array_merge($contagempf->RowAttrs, array('data-rowindex'=>$contagempf_grid->RowIndex, 'id'=>'r0_contagempf', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($contagempf->RowAttrs["class"], "ewTemplate");
		$contagempf->RowType = EW_ROWTYPE_ADD;

		// Render row
		$contagempf_grid->RenderRow();

		// Render list options
		$contagempf_grid->RenderListOptions();
		$contagempf_grid->StartRowCnt = 0;
?>
	<tr<?php echo $contagempf->RowAttributes() ?>>
<?php

// Render list options (body, left)
$contagempf_grid->ListOptions->Render("body", "left", $contagempf_grid->RowIndex);
?>
	<?php if ($contagempf->nu_contagem->Visible) { // nu_contagem ?>
		<td>
<?php if ($contagempf->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $contagempf->nu_contagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_contagem->ViewValue ?></span>
<input type="hidden" data-field="x_nu_contagem" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf->nu_contagem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_contagem" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_contagem" value="<?php echo ew_HtmlEncode($contagempf->nu_contagem->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
		<td>
<?php if ($contagempf->CurrentAction <> "F") { ?>
<?php $contagempf->nu_tpMetrica->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $contagempf_grid->RowIndex . "_nu_tpContagem']); " . @$contagempf->nu_tpMetrica->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpMetrica" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica"<?php echo $contagempf->nu_tpMetrica->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpMetrica->EditValue)) {
	$arwrk = $contagempf->nu_tpMetrica->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpMetrica->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_tpMetrica->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_tpMetrica"].Options = <?php echo (is_array($contagempf->nu_tpMetrica->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpMetrica->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $contagempf->nu_tpMetrica->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpMetrica->ViewValue ?></span>
<input type="hidden" data-field="x_nu_tpMetrica" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" value="<?php echo ew_HtmlEncode($contagempf->nu_tpMetrica->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_tpMetrica" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpMetrica" value="<?php echo ew_HtmlEncode($contagempf->nu_tpMetrica->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
		<td>
<?php if ($contagempf->CurrentAction <> "F") { ?>
<select data-field="x_nu_tpContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem"<?php echo $contagempf->nu_tpContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpContagem->EditValue)) {
	$arwrk = $contagempf->nu_tpContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpContagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_tpContagem->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_tpContagem"].Options = <?php echo (is_array($contagempf->nu_tpContagem->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpContagem->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $contagempf->nu_tpContagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpContagem->ViewValue ?></span>
<input type="hidden" data-field="x_nu_tpContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" value="<?php echo ew_HtmlEncode($contagempf->nu_tpContagem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_tpContagem" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_tpContagem" value="<?php echo ew_HtmlEncode($contagempf->nu_tpContagem->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf->nu_sistema->Visible) { // nu_sistema ?>
		<td>
<?php if ($contagempf->CurrentAction <> "F") { ?>
<select data-field="x_nu_sistema" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema"<?php echo $contagempf->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_sistema->EditValue)) {
	$arwrk = $contagempf->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_sistema->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_sistema->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_sistema"].Options = <?php echo (is_array($contagempf->nu_sistema->EditValue)) ? ew_ArrayToJson($contagempf->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $contagempf->nu_sistema->ViewAttributes() ?>>
<?php echo $contagempf->nu_sistema->ViewValue ?></span>
<input type="hidden" data-field="x_nu_sistema" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($contagempf->nu_sistema->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_sistema" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_sistema" value="<?php echo ew_HtmlEncode($contagempf->nu_sistema->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf->nu_faseMedida->Visible) { // nu_faseMedida ?>
		<td>
<?php if ($contagempf->CurrentAction <> "F") { ?>
<select data-field="x_nu_faseMedida" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida"<?php echo $contagempf->nu_faseMedida->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_faseMedida->EditValue)) {
	$arwrk = $contagempf->nu_faseMedida->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_faseMedida->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->nu_faseMedida->OldValue = "";
?>
</select>
<script type="text/javascript">
fcontagempfgrid.Lists["x_nu_faseMedida"].Options = <?php echo (is_array($contagempf->nu_faseMedida->EditValue)) ? ew_ArrayToJson($contagempf->nu_faseMedida->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $contagempf->nu_faseMedida->ViewAttributes() ?>>
<?php echo $contagempf->nu_faseMedida->ViewValue ?></span>
<input type="hidden" data-field="x_nu_faseMedida" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" value="<?php echo ew_HtmlEncode($contagempf->nu_faseMedida->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_faseMedida" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_faseMedida" value="<?php echo ew_HtmlEncode($contagempf->nu_faseMedida->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf->nu_usuarioLogado->Visible) { // nu_usuarioLogado ?>
		<td>
<?php if ($contagempf->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $contagempf->nu_usuarioLogado->ViewAttributes() ?>>
<?php echo $contagempf->nu_usuarioLogado->ViewValue ?></span>
<input type="hidden" data-field="x_nu_usuarioLogado" name="x<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" id="x<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" value="<?php echo ew_HtmlEncode($contagempf->nu_usuarioLogado->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_usuarioLogado" name="o<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" id="o<?php echo $contagempf_grid->RowIndex ?>_nu_usuarioLogado" value="<?php echo ew_HtmlEncode($contagempf->nu_usuarioLogado->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf->ic_stContagem->Visible) { // ic_stContagem ?>
		<td>
<?php if ($contagempf->CurrentAction <> "F") { ?>
<select data-field="x_ic_stContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem"<?php echo $contagempf->ic_stContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->ic_stContagem->EditValue)) {
	$arwrk = $contagempf->ic_stContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->ic_stContagem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $contagempf->ic_stContagem->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $contagempf->ic_stContagem->ViewAttributes() ?>>
<?php echo $contagempf->ic_stContagem->ViewValue ?></span>
<input type="hidden" data-field="x_ic_stContagem" name="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" id="x<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" value="<?php echo ew_HtmlEncode($contagempf->ic_stContagem->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_stContagem" name="o<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" id="o<?php echo $contagempf_grid->RowIndex ?>_ic_stContagem" value="<?php echo ew_HtmlEncode($contagempf->ic_stContagem->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
		<td>
<?php if ($contagempf->CurrentAction <> "F") { ?>
<input type="text" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" size="30" placeholder="<?php echo $contagempf->pc_varFasesRoteiro->PlaceHolder ?>" value="<?php echo $contagempf->pc_varFasesRoteiro->EditValue ?>"<?php echo $contagempf->pc_varFasesRoteiro->EditAttributes() ?>>
<?php } else { ?>
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" id="x<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf->pc_varFasesRoteiro->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_pc_varFasesRoteiro" name="o<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" id="o<?php echo $contagempf_grid->RowIndex ?>_pc_varFasesRoteiro" value="<?php echo ew_HtmlEncode($contagempf->pc_varFasesRoteiro->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($contagempf->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
		<td>
<?php if ($contagempf->CurrentAction <> "F") { ?>
<input type="text" data-field="x_vr_pfFaturamento" name="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" id="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" size="30" placeholder="<?php echo $contagempf->vr_pfFaturamento->PlaceHolder ?>" value="<?php echo $contagempf->vr_pfFaturamento->EditValue ?>"<?php echo $contagempf->vr_pfFaturamento->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $contagempf->vr_pfFaturamento->ViewAttributes() ?>>
<?php echo $contagempf->vr_pfFaturamento->ViewValue ?></span>
<input type="hidden" data-field="x_vr_pfFaturamento" name="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" id="x<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" value="<?php echo ew_HtmlEncode($contagempf->vr_pfFaturamento->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_vr_pfFaturamento" name="o<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" id="o<?php echo $contagempf_grid->RowIndex ?>_vr_pfFaturamento" value="<?php echo ew_HtmlEncode($contagempf->vr_pfFaturamento->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$contagempf_grid->ListOptions->Render("body", "right", $contagempf_grid->RowCnt);
?>
<script type="text/javascript">
fcontagempfgrid.UpdateOpts(<?php echo $contagempf_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($contagempf->CurrentMode == "add" || $contagempf->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $contagempf_grid->FormKeyCountName ?>" id="<?php echo $contagempf_grid->FormKeyCountName ?>" value="<?php echo $contagempf_grid->KeyCount ?>">
<?php echo $contagempf_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($contagempf->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $contagempf_grid->FormKeyCountName ?>" id="<?php echo $contagempf_grid->FormKeyCountName ?>" value="<?php echo $contagempf_grid->KeyCount ?>">
<?php echo $contagempf_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($contagempf->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fcontagempfgrid">
</div>
<?php

// Close recordset
if ($contagempf_grid->Recordset)
	$contagempf_grid->Recordset->Close();
?>
<?php if ($contagempf_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($contagempf_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($contagempf->Export == "") { ?>
<script type="text/javascript">
fcontagempfgrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$contagempf_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$contagempf_grid->Page_Terminate();
?>
