<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($ambiente_phistorico_grid)) $ambiente_phistorico_grid = new cambiente_phistorico_grid();

// Page init
$ambiente_phistorico_grid->Page_Init();

// Page main
$ambiente_phistorico_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_phistorico_grid->Page_Render();
?>
<?php if ($ambiente_phistorico->Export == "") { ?>
<script type="text/javascript">

// Page object
var ambiente_phistorico_grid = new ew_Page("ambiente_phistorico_grid");
ambiente_phistorico_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = ambiente_phistorico_grid.PageID; // For backward compatibility

// Form object
var fambiente_phistoricogrid = new ew_Form("fambiente_phistoricogrid");
fambiente_phistoricogrid.FormKeyCountName = '<?php echo $ambiente_phistorico_grid->FormKeyCountName ?>';

// Validate form
fambiente_phistoricogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_qt_pf");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_pf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_sloc");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_sloc->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_slocPf");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_slocPf->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_esforcoReal");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_esforcoReal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_esforcoRealPm");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_esforcoRealPm->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qt_prazoRealM");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ambiente_phistorico->qt_prazoRealM->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($ambiente_phistorico->ic_situacao->FldCaption()) ?>");

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
fambiente_phistoricogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "no_projeto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_pf", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_sloc", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_slocPf", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_esforcoReal", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_esforcoRealPm", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qt_prazoRealM", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_situacao", false)) return false;
	return true;
}

// Form_CustomValidate event
fambiente_phistoricogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_phistoricogrid.ValidateRequired = true;
<?php } else { ?>
fambiente_phistoricogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php if ($ambiente_phistorico->getCurrentMasterTable() == "" && $ambiente_phistorico_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $ambiente_phistorico_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($ambiente_phistorico->CurrentAction == "gridadd") {
	if ($ambiente_phistorico->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$ambiente_phistorico_grid->TotalRecs = $ambiente_phistorico->SelectRecordCount();
			$ambiente_phistorico_grid->Recordset = $ambiente_phistorico_grid->LoadRecordset($ambiente_phistorico_grid->StartRec-1, $ambiente_phistorico_grid->DisplayRecs);
		} else {
			if ($ambiente_phistorico_grid->Recordset = $ambiente_phistorico_grid->LoadRecordset())
				$ambiente_phistorico_grid->TotalRecs = $ambiente_phistorico_grid->Recordset->RecordCount();
		}
		$ambiente_phistorico_grid->StartRec = 1;
		$ambiente_phistorico_grid->DisplayRecs = $ambiente_phistorico_grid->TotalRecs;
	} else {
		$ambiente_phistorico->CurrentFilter = "0=1";
		$ambiente_phistorico_grid->StartRec = 1;
		$ambiente_phistorico_grid->DisplayRecs = $ambiente_phistorico->GridAddRowCount;
	}
	$ambiente_phistorico_grid->TotalRecs = $ambiente_phistorico_grid->DisplayRecs;
	$ambiente_phistorico_grid->StopRec = $ambiente_phistorico_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$ambiente_phistorico_grid->TotalRecs = $ambiente_phistorico->SelectRecordCount();
	} else {
		if ($ambiente_phistorico_grid->Recordset = $ambiente_phistorico_grid->LoadRecordset())
			$ambiente_phistorico_grid->TotalRecs = $ambiente_phistorico_grid->Recordset->RecordCount();
	}
	$ambiente_phistorico_grid->StartRec = 1;
	$ambiente_phistorico_grid->DisplayRecs = $ambiente_phistorico_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$ambiente_phistorico_grid->Recordset = $ambiente_phistorico_grid->LoadRecordset($ambiente_phistorico_grid->StartRec-1, $ambiente_phistorico_grid->DisplayRecs);
}
$ambiente_phistorico_grid->RenderOtherOptions();
?>
<?php $ambiente_phistorico_grid->ShowPageHeader(); ?>
<?php
$ambiente_phistorico_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fambiente_phistoricogrid" class="ewForm form-horizontal">
<div id="gmp_ambiente_phistorico" class="ewGridMiddlePanel">
<table id="tbl_ambiente_phistoricogrid" class="ewTable ewTableSeparate">
<?php echo $ambiente_phistorico->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$ambiente_phistorico_grid->RenderListOptions();

// Render list options (header, left)
$ambiente_phistorico_grid->ListOptions->Render("header", "left");
?>
<?php if ($ambiente_phistorico->nu_projhist->Visible) { // nu_projhist ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->nu_projhist) == "") { ?>
		<td><div id="elh_ambiente_phistorico_nu_projhist" class="ambiente_phistorico_nu_projhist"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->nu_projhist->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_phistorico_nu_projhist" class="ambiente_phistorico_nu_projhist">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->nu_projhist->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->nu_projhist->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->nu_projhist->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->no_projeto->Visible) { // no_projeto ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->no_projeto) == "") { ?>
		<td><div id="elh_ambiente_phistorico_no_projeto" class="ambiente_phistorico_no_projeto"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->no_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_phistorico_no_projeto" class="ambiente_phistorico_no_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->no_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->no_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->no_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_pf->Visible) { // qt_pf ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_pf) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_pf" class="ambiente_phistorico_qt_pf"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_pf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_phistorico_qt_pf" class="ambiente_phistorico_qt_pf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_pf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_pf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_pf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_sloc->Visible) { // qt_sloc ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_sloc) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_sloc" class="ambiente_phistorico_qt_sloc"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_sloc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_phistorico_qt_sloc" class="ambiente_phistorico_qt_sloc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_sloc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_sloc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_sloc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_slocPf->Visible) { // qt_slocPf ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_slocPf) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_slocPf" class="ambiente_phistorico_qt_slocPf"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_slocPf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_phistorico_qt_slocPf" class="ambiente_phistorico_qt_slocPf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_slocPf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_slocPf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_slocPf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_esforcoReal->Visible) { // qt_esforcoReal ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_esforcoReal) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_esforcoReal" class="ambiente_phistorico_qt_esforcoReal"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_esforcoReal->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_phistorico_qt_esforcoReal" class="ambiente_phistorico_qt_esforcoReal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_esforcoReal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_esforcoReal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_esforcoReal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_esforcoRealPm->Visible) { // qt_esforcoRealPm ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_esforcoRealPm) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_esforcoRealPm" class="ambiente_phistorico_qt_esforcoRealPm"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_esforcoRealPm->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_phistorico_qt_esforcoRealPm" class="ambiente_phistorico_qt_esforcoRealPm">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_esforcoRealPm->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_esforcoRealPm->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_esforcoRealPm->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_prazoRealM->Visible) { // qt_prazoRealM ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_prazoRealM) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_prazoRealM" class="ambiente_phistorico_qt_prazoRealM"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_prazoRealM->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_phistorico_qt_prazoRealM" class="ambiente_phistorico_qt_prazoRealM">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_prazoRealM->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_prazoRealM->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_prazoRealM->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->ic_situacao->Visible) { // ic_situacao ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->ic_situacao) == "") { ?>
		<td><div id="elh_ambiente_phistorico_ic_situacao" class="ambiente_phistorico_ic_situacao"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->ic_situacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_ambiente_phistorico_ic_situacao" class="ambiente_phistorico_ic_situacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->ic_situacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->ic_situacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->ic_situacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ambiente_phistorico_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$ambiente_phistorico_grid->StartRec = 1;
$ambiente_phistorico_grid->StopRec = $ambiente_phistorico_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($ambiente_phistorico_grid->FormKeyCountName) && ($ambiente_phistorico->CurrentAction == "gridadd" || $ambiente_phistorico->CurrentAction == "gridedit" || $ambiente_phistorico->CurrentAction == "F")) {
		$ambiente_phistorico_grid->KeyCount = $objForm->GetValue($ambiente_phistorico_grid->FormKeyCountName);
		$ambiente_phistorico_grid->StopRec = $ambiente_phistorico_grid->StartRec + $ambiente_phistorico_grid->KeyCount - 1;
	}
}
$ambiente_phistorico_grid->RecCnt = $ambiente_phistorico_grid->StartRec - 1;
if ($ambiente_phistorico_grid->Recordset && !$ambiente_phistorico_grid->Recordset->EOF) {
	$ambiente_phistorico_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $ambiente_phistorico_grid->StartRec > 1)
		$ambiente_phistorico_grid->Recordset->Move($ambiente_phistorico_grid->StartRec - 1);
} elseif (!$ambiente_phistorico->AllowAddDeleteRow && $ambiente_phistorico_grid->StopRec == 0) {
	$ambiente_phistorico_grid->StopRec = $ambiente_phistorico->GridAddRowCount;
}

// Initialize aggregate
$ambiente_phistorico->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ambiente_phistorico->ResetAttrs();
$ambiente_phistorico_grid->RenderRow();
if ($ambiente_phistorico->CurrentAction == "gridadd")
	$ambiente_phistorico_grid->RowIndex = 0;
if ($ambiente_phistorico->CurrentAction == "gridedit")
	$ambiente_phistorico_grid->RowIndex = 0;
while ($ambiente_phistorico_grid->RecCnt < $ambiente_phistorico_grid->StopRec) {
	$ambiente_phistorico_grid->RecCnt++;
	if (intval($ambiente_phistorico_grid->RecCnt) >= intval($ambiente_phistorico_grid->StartRec)) {
		$ambiente_phistorico_grid->RowCnt++;
		if ($ambiente_phistorico->CurrentAction == "gridadd" || $ambiente_phistorico->CurrentAction == "gridedit" || $ambiente_phistorico->CurrentAction == "F") {
			$ambiente_phistorico_grid->RowIndex++;
			$objForm->Index = $ambiente_phistorico_grid->RowIndex;
			if ($objForm->HasValue($ambiente_phistorico_grid->FormActionName))
				$ambiente_phistorico_grid->RowAction = strval($objForm->GetValue($ambiente_phistorico_grid->FormActionName));
			elseif ($ambiente_phistorico->CurrentAction == "gridadd")
				$ambiente_phistorico_grid->RowAction = "insert";
			else
				$ambiente_phistorico_grid->RowAction = "";
		}

		// Set up key count
		$ambiente_phistorico_grid->KeyCount = $ambiente_phistorico_grid->RowIndex;

		// Init row class and style
		$ambiente_phistorico->ResetAttrs();
		$ambiente_phistorico->CssClass = "";
		if ($ambiente_phistorico->CurrentAction == "gridadd") {
			if ($ambiente_phistorico->CurrentMode == "copy") {
				$ambiente_phistorico_grid->LoadRowValues($ambiente_phistorico_grid->Recordset); // Load row values
				$ambiente_phistorico_grid->SetRecordKey($ambiente_phistorico_grid->RowOldKey, $ambiente_phistorico_grid->Recordset); // Set old record key
			} else {
				$ambiente_phistorico_grid->LoadDefaultValues(); // Load default values
				$ambiente_phistorico_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$ambiente_phistorico_grid->LoadRowValues($ambiente_phistorico_grid->Recordset); // Load row values
		}
		$ambiente_phistorico->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($ambiente_phistorico->CurrentAction == "gridadd") // Grid add
			$ambiente_phistorico->RowType = EW_ROWTYPE_ADD; // Render add
		if ($ambiente_phistorico->CurrentAction == "gridadd" && $ambiente_phistorico->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$ambiente_phistorico_grid->RestoreCurrentRowFormValues($ambiente_phistorico_grid->RowIndex); // Restore form values
		if ($ambiente_phistorico->CurrentAction == "gridedit") { // Grid edit
			if ($ambiente_phistorico->EventCancelled) {
				$ambiente_phistorico_grid->RestoreCurrentRowFormValues($ambiente_phistorico_grid->RowIndex); // Restore form values
			}
			if ($ambiente_phistorico_grid->RowAction == "insert")
				$ambiente_phistorico->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$ambiente_phistorico->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($ambiente_phistorico->CurrentAction == "gridedit" && ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT || $ambiente_phistorico->RowType == EW_ROWTYPE_ADD) && $ambiente_phistorico->EventCancelled) // Update failed
			$ambiente_phistorico_grid->RestoreCurrentRowFormValues($ambiente_phistorico_grid->RowIndex); // Restore form values
		if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) // Edit row
			$ambiente_phistorico_grid->EditRowCnt++;
		if ($ambiente_phistorico->CurrentAction == "F") // Confirm row
			$ambiente_phistorico_grid->RestoreCurrentRowFormValues($ambiente_phistorico_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$ambiente_phistorico->RowAttrs = array_merge($ambiente_phistorico->RowAttrs, array('data-rowindex'=>$ambiente_phistorico_grid->RowCnt, 'id'=>'r' . $ambiente_phistorico_grid->RowCnt . '_ambiente_phistorico', 'data-rowtype'=>$ambiente_phistorico->RowType));

		// Render row
		$ambiente_phistorico_grid->RenderRow();

		// Render list options
		$ambiente_phistorico_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($ambiente_phistorico_grid->RowAction <> "delete" && $ambiente_phistorico_grid->RowAction <> "insertdelete" && !($ambiente_phistorico_grid->RowAction == "insert" && $ambiente_phistorico->CurrentAction == "F" && $ambiente_phistorico_grid->EmptyRow())) {
?>
	<tr<?php echo $ambiente_phistorico->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ambiente_phistorico_grid->ListOptions->Render("body", "left", $ambiente_phistorico_grid->RowCnt);
?>
	<?php if ($ambiente_phistorico->nu_projhist->Visible) { // nu_projhist ?>
		<td<?php echo $ambiente_phistorico->nu_projhist->CellAttributes() ?>>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_projhist" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" value="<?php echo ew_HtmlEncode($ambiente_phistorico->nu_projhist->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_nu_projhist" class="control-group ambiente_phistorico_nu_projhist">
<span<?php echo $ambiente_phistorico->nu_projhist->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->nu_projhist->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_projhist" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" value="<?php echo ew_HtmlEncode($ambiente_phistorico->nu_projhist->CurrentValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_phistorico->nu_projhist->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->nu_projhist->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_projhist" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" value="<?php echo ew_HtmlEncode($ambiente_phistorico->nu_projhist->FormValue) ?>">
<input type="hidden" data-field="x_nu_projhist" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" value="<?php echo ew_HtmlEncode($ambiente_phistorico->nu_projhist->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_phistorico_grid->PageObjName . "_row_" . $ambiente_phistorico_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->no_projeto->Visible) { // no_projeto ?>
		<td<?php echo $ambiente_phistorico->no_projeto->CellAttributes() ?>>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_no_projeto" class="control-group ambiente_phistorico_no_projeto">
<input type="text" data-field="x_no_projeto" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" size="30" maxlength="150" placeholder="<?php echo $ambiente_phistorico->no_projeto->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->no_projeto->EditValue ?>"<?php echo $ambiente_phistorico->no_projeto->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_projeto" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" value="<?php echo ew_HtmlEncode($ambiente_phistorico->no_projeto->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_no_projeto" class="control-group ambiente_phistorico_no_projeto">
<input type="text" data-field="x_no_projeto" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" size="30" maxlength="150" placeholder="<?php echo $ambiente_phistorico->no_projeto->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->no_projeto->EditValue ?>"<?php echo $ambiente_phistorico->no_projeto->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_phistorico->no_projeto->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->no_projeto->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_projeto" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" value="<?php echo ew_HtmlEncode($ambiente_phistorico->no_projeto->FormValue) ?>">
<input type="hidden" data-field="x_no_projeto" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" value="<?php echo ew_HtmlEncode($ambiente_phistorico->no_projeto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_phistorico_grid->PageObjName . "_row_" . $ambiente_phistorico_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_pf->Visible) { // qt_pf ?>
		<td<?php echo $ambiente_phistorico->qt_pf->CellAttributes() ?>>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_pf" class="control-group ambiente_phistorico_qt_pf">
<input type="text" data-field="x_qt_pf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" size="30" placeholder="<?php echo $ambiente_phistorico->qt_pf->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_pf->EditValue ?>"<?php echo $ambiente_phistorico->qt_pf->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_pf" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_pf->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_pf" class="control-group ambiente_phistorico_qt_pf">
<input type="text" data-field="x_qt_pf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" size="30" placeholder="<?php echo $ambiente_phistorico->qt_pf->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_pf->EditValue ?>"<?php echo $ambiente_phistorico->qt_pf->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_phistorico->qt_pf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_pf->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_pf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_pf->FormValue) ?>">
<input type="hidden" data-field="x_qt_pf" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_pf->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_phistorico_grid->PageObjName . "_row_" . $ambiente_phistorico_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_sloc->Visible) { // qt_sloc ?>
		<td<?php echo $ambiente_phistorico->qt_sloc->CellAttributes() ?>>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_sloc" class="control-group ambiente_phistorico_qt_sloc">
<input type="text" data-field="x_qt_sloc" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" size="30" placeholder="<?php echo $ambiente_phistorico->qt_sloc->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_sloc->EditValue ?>"<?php echo $ambiente_phistorico->qt_sloc->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_sloc" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_sloc->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_sloc" class="control-group ambiente_phistorico_qt_sloc">
<input type="text" data-field="x_qt_sloc" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" size="30" placeholder="<?php echo $ambiente_phistorico->qt_sloc->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_sloc->EditValue ?>"<?php echo $ambiente_phistorico->qt_sloc->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_phistorico->qt_sloc->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_sloc->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_sloc" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_sloc->FormValue) ?>">
<input type="hidden" data-field="x_qt_sloc" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_sloc->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_phistorico_grid->PageObjName . "_row_" . $ambiente_phistorico_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_slocPf->Visible) { // qt_slocPf ?>
		<td<?php echo $ambiente_phistorico->qt_slocPf->CellAttributes() ?>>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_slocPf" class="control-group ambiente_phistorico_qt_slocPf">
<input type="text" data-field="x_qt_slocPf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" size="30" placeholder="<?php echo $ambiente_phistorico->qt_slocPf->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_slocPf->EditValue ?>"<?php echo $ambiente_phistorico->qt_slocPf->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_slocPf" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_slocPf->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_slocPf" class="control-group ambiente_phistorico_qt_slocPf">
<input type="text" data-field="x_qt_slocPf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" size="30" placeholder="<?php echo $ambiente_phistorico->qt_slocPf->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_slocPf->EditValue ?>"<?php echo $ambiente_phistorico->qt_slocPf->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_phistorico->qt_slocPf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_slocPf->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_slocPf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_slocPf->FormValue) ?>">
<input type="hidden" data-field="x_qt_slocPf" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_slocPf->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_phistorico_grid->PageObjName . "_row_" . $ambiente_phistorico_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_esforcoReal->Visible) { // qt_esforcoReal ?>
		<td<?php echo $ambiente_phistorico->qt_esforcoReal->CellAttributes() ?>>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_esforcoReal" class="control-group ambiente_phistorico_qt_esforcoReal">
<input type="text" data-field="x_qt_esforcoReal" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" size="30" placeholder="<?php echo $ambiente_phistorico->qt_esforcoReal->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_esforcoReal->EditValue ?>"<?php echo $ambiente_phistorico->qt_esforcoReal->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_esforcoReal" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoReal->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_esforcoReal" class="control-group ambiente_phistorico_qt_esforcoReal">
<input type="text" data-field="x_qt_esforcoReal" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" size="30" placeholder="<?php echo $ambiente_phistorico->qt_esforcoReal->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_esforcoReal->EditValue ?>"<?php echo $ambiente_phistorico->qt_esforcoReal->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_phistorico->qt_esforcoReal->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoReal->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_esforcoReal" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoReal->FormValue) ?>">
<input type="hidden" data-field="x_qt_esforcoReal" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoReal->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_phistorico_grid->PageObjName . "_row_" . $ambiente_phistorico_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_esforcoRealPm->Visible) { // qt_esforcoRealPm ?>
		<td<?php echo $ambiente_phistorico->qt_esforcoRealPm->CellAttributes() ?>>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_esforcoRealPm" class="control-group ambiente_phistorico_qt_esforcoRealPm">
<input type="text" data-field="x_qt_esforcoRealPm" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" size="30" placeholder="<?php echo $ambiente_phistorico->qt_esforcoRealPm->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_esforcoRealPm->EditValue ?>"<?php echo $ambiente_phistorico->qt_esforcoRealPm->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_esforcoRealPm" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoRealPm->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_esforcoRealPm" class="control-group ambiente_phistorico_qt_esforcoRealPm">
<input type="text" data-field="x_qt_esforcoRealPm" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" size="30" placeholder="<?php echo $ambiente_phistorico->qt_esforcoRealPm->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_esforcoRealPm->EditValue ?>"<?php echo $ambiente_phistorico->qt_esforcoRealPm->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_phistorico->qt_esforcoRealPm->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoRealPm->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_esforcoRealPm" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoRealPm->FormValue) ?>">
<input type="hidden" data-field="x_qt_esforcoRealPm" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoRealPm->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_phistorico_grid->PageObjName . "_row_" . $ambiente_phistorico_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_prazoRealM->Visible) { // qt_prazoRealM ?>
		<td<?php echo $ambiente_phistorico->qt_prazoRealM->CellAttributes() ?>>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_prazoRealM" class="control-group ambiente_phistorico_qt_prazoRealM">
<input type="text" data-field="x_qt_prazoRealM" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" size="30" placeholder="<?php echo $ambiente_phistorico->qt_prazoRealM->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_prazoRealM->EditValue ?>"<?php echo $ambiente_phistorico->qt_prazoRealM->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_qt_prazoRealM" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_prazoRealM->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_qt_prazoRealM" class="control-group ambiente_phistorico_qt_prazoRealM">
<input type="text" data-field="x_qt_prazoRealM" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" size="30" placeholder="<?php echo $ambiente_phistorico->qt_prazoRealM->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_prazoRealM->EditValue ?>"<?php echo $ambiente_phistorico->qt_prazoRealM->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_phistorico->qt_prazoRealM->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_prazoRealM->ListViewValue() ?></span>
<input type="hidden" data-field="x_qt_prazoRealM" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_prazoRealM->FormValue) ?>">
<input type="hidden" data-field="x_qt_prazoRealM" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_prazoRealM->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_phistorico_grid->PageObjName . "_row_" . $ambiente_phistorico_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->ic_situacao->Visible) { // ic_situacao ?>
		<td<?php echo $ambiente_phistorico->ic_situacao->CellAttributes() ?>>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_ic_situacao" class="control-group ambiente_phistorico_ic_situacao">
<select data-field="x_ic_situacao" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao"<?php echo $ambiente_phistorico->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($ambiente_phistorico->ic_situacao->EditValue)) {
	$arwrk = $ambiente_phistorico->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_phistorico->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $ambiente_phistorico->ic_situacao->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_ic_situacao" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($ambiente_phistorico->ic_situacao->OldValue) ?>">
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $ambiente_phistorico_grid->RowCnt ?>_ambiente_phistorico_ic_situacao" class="control-group ambiente_phistorico_ic_situacao">
<select data-field="x_ic_situacao" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao"<?php echo $ambiente_phistorico->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($ambiente_phistorico->ic_situacao->EditValue)) {
	$arwrk = $ambiente_phistorico->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_phistorico->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $ambiente_phistorico->ic_situacao->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $ambiente_phistorico->ic_situacao->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->ic_situacao->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_situacao" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($ambiente_phistorico->ic_situacao->FormValue) ?>">
<input type="hidden" data-field="x_ic_situacao" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($ambiente_phistorico->ic_situacao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $ambiente_phistorico_grid->PageObjName . "_row_" . $ambiente_phistorico_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$ambiente_phistorico_grid->ListOptions->Render("body", "right", $ambiente_phistorico_grid->RowCnt);
?>
	</tr>
<?php if ($ambiente_phistorico->RowType == EW_ROWTYPE_ADD || $ambiente_phistorico->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fambiente_phistoricogrid.UpdateOpts(<?php echo $ambiente_phistorico_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($ambiente_phistorico->CurrentAction <> "gridadd" || $ambiente_phistorico->CurrentMode == "copy")
		if (!$ambiente_phistorico_grid->Recordset->EOF) $ambiente_phistorico_grid->Recordset->MoveNext();
}
?>
<?php
	if ($ambiente_phistorico->CurrentMode == "add" || $ambiente_phistorico->CurrentMode == "copy" || $ambiente_phistorico->CurrentMode == "edit") {
		$ambiente_phistorico_grid->RowIndex = '$rowindex$';
		$ambiente_phistorico_grid->LoadDefaultValues();

		// Set row properties
		$ambiente_phistorico->ResetAttrs();
		$ambiente_phistorico->RowAttrs = array_merge($ambiente_phistorico->RowAttrs, array('data-rowindex'=>$ambiente_phistorico_grid->RowIndex, 'id'=>'r0_ambiente_phistorico', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($ambiente_phistorico->RowAttrs["class"], "ewTemplate");
		$ambiente_phistorico->RowType = EW_ROWTYPE_ADD;

		// Render row
		$ambiente_phistorico_grid->RenderRow();

		// Render list options
		$ambiente_phistorico_grid->RenderListOptions();
		$ambiente_phistorico_grid->StartRowCnt = 0;
?>
	<tr<?php echo $ambiente_phistorico->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ambiente_phistorico_grid->ListOptions->Render("body", "left", $ambiente_phistorico_grid->RowIndex);
?>
	<?php if ($ambiente_phistorico->nu_projhist->Visible) { // nu_projhist ?>
		<td>
<?php if ($ambiente_phistorico->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $ambiente_phistorico->nu_projhist->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->nu_projhist->ViewValue ?></span>
<input type="hidden" data-field="x_nu_projhist" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" value="<?php echo ew_HtmlEncode($ambiente_phistorico->nu_projhist->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_projhist" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_nu_projhist" value="<?php echo ew_HtmlEncode($ambiente_phistorico->nu_projhist->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_phistorico->no_projeto->Visible) { // no_projeto ?>
		<td>
<?php if ($ambiente_phistorico->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_projeto" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" size="30" maxlength="150" placeholder="<?php echo $ambiente_phistorico->no_projeto->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->no_projeto->EditValue ?>"<?php echo $ambiente_phistorico->no_projeto->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_phistorico->no_projeto->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->no_projeto->ViewValue ?></span>
<input type="hidden" data-field="x_no_projeto" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" value="<?php echo ew_HtmlEncode($ambiente_phistorico->no_projeto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_projeto" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_no_projeto" value="<?php echo ew_HtmlEncode($ambiente_phistorico->no_projeto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_pf->Visible) { // qt_pf ?>
		<td>
<?php if ($ambiente_phistorico->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_pf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" size="30" placeholder="<?php echo $ambiente_phistorico->qt_pf->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_pf->EditValue ?>"<?php echo $ambiente_phistorico->qt_pf->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_phistorico->qt_pf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_pf->ViewValue ?></span>
<input type="hidden" data-field="x_qt_pf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_pf->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_pf" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_pf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_pf->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_sloc->Visible) { // qt_sloc ?>
		<td>
<?php if ($ambiente_phistorico->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_sloc" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" size="30" placeholder="<?php echo $ambiente_phistorico->qt_sloc->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_sloc->EditValue ?>"<?php echo $ambiente_phistorico->qt_sloc->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_phistorico->qt_sloc->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_sloc->ViewValue ?></span>
<input type="hidden" data-field="x_qt_sloc" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_sloc->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_sloc" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_sloc" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_sloc->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_slocPf->Visible) { // qt_slocPf ?>
		<td>
<?php if ($ambiente_phistorico->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_slocPf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" size="30" placeholder="<?php echo $ambiente_phistorico->qt_slocPf->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_slocPf->EditValue ?>"<?php echo $ambiente_phistorico->qt_slocPf->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_phistorico->qt_slocPf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_slocPf->ViewValue ?></span>
<input type="hidden" data-field="x_qt_slocPf" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_slocPf->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_slocPf" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_slocPf" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_slocPf->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_esforcoReal->Visible) { // qt_esforcoReal ?>
		<td>
<?php if ($ambiente_phistorico->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_esforcoReal" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" size="30" placeholder="<?php echo $ambiente_phistorico->qt_esforcoReal->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_esforcoReal->EditValue ?>"<?php echo $ambiente_phistorico->qt_esforcoReal->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_phistorico->qt_esforcoReal->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoReal->ViewValue ?></span>
<input type="hidden" data-field="x_qt_esforcoReal" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoReal->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_esforcoReal" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoReal" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoReal->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_esforcoRealPm->Visible) { // qt_esforcoRealPm ?>
		<td>
<?php if ($ambiente_phistorico->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_esforcoRealPm" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" size="30" placeholder="<?php echo $ambiente_phistorico->qt_esforcoRealPm->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_esforcoRealPm->EditValue ?>"<?php echo $ambiente_phistorico->qt_esforcoRealPm->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_phistorico->qt_esforcoRealPm->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoRealPm->ViewValue ?></span>
<input type="hidden" data-field="x_qt_esforcoRealPm" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoRealPm->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_esforcoRealPm" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_esforcoRealPm" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_esforcoRealPm->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_prazoRealM->Visible) { // qt_prazoRealM ?>
		<td>
<?php if ($ambiente_phistorico->CurrentAction <> "F") { ?>
<input type="text" data-field="x_qt_prazoRealM" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" size="30" placeholder="<?php echo $ambiente_phistorico->qt_prazoRealM->PlaceHolder ?>" value="<?php echo $ambiente_phistorico->qt_prazoRealM->EditValue ?>"<?php echo $ambiente_phistorico->qt_prazoRealM->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $ambiente_phistorico->qt_prazoRealM->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_prazoRealM->ViewValue ?></span>
<input type="hidden" data-field="x_qt_prazoRealM" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_prazoRealM->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_qt_prazoRealM" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_qt_prazoRealM" value="<?php echo ew_HtmlEncode($ambiente_phistorico->qt_prazoRealM->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($ambiente_phistorico->ic_situacao->Visible) { // ic_situacao ?>
		<td>
<?php if ($ambiente_phistorico->CurrentAction <> "F") { ?>
<select data-field="x_ic_situacao" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao"<?php echo $ambiente_phistorico->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($ambiente_phistorico->ic_situacao->EditValue)) {
	$arwrk = $ambiente_phistorico->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($ambiente_phistorico->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $ambiente_phistorico->ic_situacao->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $ambiente_phistorico->ic_situacao->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->ic_situacao->ViewValue ?></span>
<input type="hidden" data-field="x_ic_situacao" name="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" id="x<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($ambiente_phistorico->ic_situacao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_situacao" name="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" id="o<?php echo $ambiente_phistorico_grid->RowIndex ?>_ic_situacao" value="<?php echo ew_HtmlEncode($ambiente_phistorico->ic_situacao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$ambiente_phistorico_grid->ListOptions->Render("body", "right", $ambiente_phistorico_grid->RowCnt);
?>
<script type="text/javascript">
fambiente_phistoricogrid.UpdateOpts(<?php echo $ambiente_phistorico_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($ambiente_phistorico->CurrentMode == "add" || $ambiente_phistorico->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $ambiente_phistorico_grid->FormKeyCountName ?>" id="<?php echo $ambiente_phistorico_grid->FormKeyCountName ?>" value="<?php echo $ambiente_phistorico_grid->KeyCount ?>">
<?php echo $ambiente_phistorico_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ambiente_phistorico->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $ambiente_phistorico_grid->FormKeyCountName ?>" id="<?php echo $ambiente_phistorico_grid->FormKeyCountName ?>" value="<?php echo $ambiente_phistorico_grid->KeyCount ?>">
<?php echo $ambiente_phistorico_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($ambiente_phistorico->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fambiente_phistoricogrid">
</div>
<?php

// Close recordset
if ($ambiente_phistorico_grid->Recordset)
	$ambiente_phistorico_grid->Recordset->Close();
?>
<?php if ($ambiente_phistorico_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($ambiente_phistorico_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($ambiente_phistorico->Export == "") { ?>
<script type="text/javascript">
fambiente_phistoricogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$ambiente_phistorico_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$ambiente_phistorico_grid->Page_Terminate();
?>
