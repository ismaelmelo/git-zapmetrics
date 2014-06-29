<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($regranegocio_grid)) $regranegocio_grid = new cregranegocio_grid();

// Page init
$regranegocio_grid->Page_Init();

// Page main
$regranegocio_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$regranegocio_grid->Page_Render();
?>
<?php if ($regranegocio->Export == "") { ?>
<script type="text/javascript">

// Page object
var regranegocio_grid = new ew_Page("regranegocio_grid");
regranegocio_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = regranegocio_grid.PageID; // For backward compatibility

// Form object
var fregranegociogrid = new ew_Form("fregranegociogrid");
fregranegociogrid.FormKeyCountName = '<?php echo $regranegocio_grid->FormKeyCountName ?>';

// Validate form
fregranegociogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->nu_versao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_regraNegocio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->no_regraNegocio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_projeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->nu_projeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stRegraNegocio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->nu_stRegraNegocio->FldCaption()) ?>");

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
fregranegociogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_versao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "no_regraNegocio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_projeto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_stRegraNegocio", false)) return false;
	return true;
}

// Form_CustomValidate event
fregranegociogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregranegociogrid.ValidateRequired = true;
<?php } else { ?>
fregranegociogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fregranegociogrid.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fregranegociogrid.Lists["x_nu_stRegraNegocio"] = {"LinkField":"x_nu_stRegraNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stRegraNegocio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fregranegociogrid.Lists["x_nu_usuario"] = {"LinkField":"x_nu_usuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($regranegocio->getCurrentMasterTable() == "" && $regranegocio_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $regranegocio_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($regranegocio->CurrentAction == "gridadd") {
	if ($regranegocio->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$regranegocio_grid->TotalRecs = $regranegocio->SelectRecordCount();
			$regranegocio_grid->Recordset = $regranegocio_grid->LoadRecordset($regranegocio_grid->StartRec-1, $regranegocio_grid->DisplayRecs);
		} else {
			if ($regranegocio_grid->Recordset = $regranegocio_grid->LoadRecordset())
				$regranegocio_grid->TotalRecs = $regranegocio_grid->Recordset->RecordCount();
		}
		$regranegocio_grid->StartRec = 1;
		$regranegocio_grid->DisplayRecs = $regranegocio_grid->TotalRecs;
	} else {
		$regranegocio->CurrentFilter = "0=1";
		$regranegocio_grid->StartRec = 1;
		$regranegocio_grid->DisplayRecs = $regranegocio->GridAddRowCount;
	}
	$regranegocio_grid->TotalRecs = $regranegocio_grid->DisplayRecs;
	$regranegocio_grid->StopRec = $regranegocio_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$regranegocio_grid->TotalRecs = $regranegocio->SelectRecordCount();
	} else {
		if ($regranegocio_grid->Recordset = $regranegocio_grid->LoadRecordset())
			$regranegocio_grid->TotalRecs = $regranegocio_grid->Recordset->RecordCount();
	}
	$regranegocio_grid->StartRec = 1;
	$regranegocio_grid->DisplayRecs = $regranegocio_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$regranegocio_grid->Recordset = $regranegocio_grid->LoadRecordset($regranegocio_grid->StartRec-1, $regranegocio_grid->DisplayRecs);
}
$regranegocio_grid->RenderOtherOptions();
?>
<?php $regranegocio_grid->ShowPageHeader(); ?>
<?php
$regranegocio_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="fregranegociogrid" class="ewForm form-horizontal">
<div id="gmp_regranegocio" class="ewGridMiddlePanel">
<table id="tbl_regranegociogrid" class="ewTable ewTableSeparate">
<?php echo $regranegocio->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$regranegocio_grid->RenderListOptions();

// Render list options (header, left)
$regranegocio_grid->ListOptions->Render("header", "left");
?>
<?php if ($regranegocio->nu_versao->Visible) { // nu_versao ?>
	<?php if ($regranegocio->SortUrl($regranegocio->nu_versao) == "") { ?>
		<td><div id="elh_regranegocio_nu_versao" class="regranegocio_nu_versao"><div class="ewTableHeaderCaption"><?php echo $regranegocio->nu_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_regranegocio_nu_versao" class="regranegocio_nu_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $regranegocio->nu_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($regranegocio->nu_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($regranegocio->nu_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($regranegocio->no_regraNegocio->Visible) { // no_regraNegocio ?>
	<?php if ($regranegocio->SortUrl($regranegocio->no_regraNegocio) == "") { ?>
		<td><div id="elh_regranegocio_no_regraNegocio" class="regranegocio_no_regraNegocio"><div class="ewTableHeaderCaption"><?php echo $regranegocio->no_regraNegocio->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_regranegocio_no_regraNegocio" class="regranegocio_no_regraNegocio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $regranegocio->no_regraNegocio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($regranegocio->no_regraNegocio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($regranegocio->no_regraNegocio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($regranegocio->nu_projeto->Visible) { // nu_projeto ?>
	<?php if ($regranegocio->SortUrl($regranegocio->nu_projeto) == "") { ?>
		<td><div id="elh_regranegocio_nu_projeto" class="regranegocio_nu_projeto"><div class="ewTableHeaderCaption"><?php echo $regranegocio->nu_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_regranegocio_nu_projeto" class="regranegocio_nu_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $regranegocio->nu_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($regranegocio->nu_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($regranegocio->nu_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($regranegocio->nu_stRegraNegocio->Visible) { // nu_stRegraNegocio ?>
	<?php if ($regranegocio->SortUrl($regranegocio->nu_stRegraNegocio) == "") { ?>
		<td><div id="elh_regranegocio_nu_stRegraNegocio" class="regranegocio_nu_stRegraNegocio"><div class="ewTableHeaderCaption"><?php echo $regranegocio->nu_stRegraNegocio->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_regranegocio_nu_stRegraNegocio" class="regranegocio_nu_stRegraNegocio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $regranegocio->nu_stRegraNegocio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($regranegocio->nu_stRegraNegocio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($regranegocio->nu_stRegraNegocio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($regranegocio->nu_usuario->Visible) { // nu_usuario ?>
	<?php if ($regranegocio->SortUrl($regranegocio->nu_usuario) == "") { ?>
		<td><div id="elh_regranegocio_nu_usuario" class="regranegocio_nu_usuario"><div class="ewTableHeaderCaption"><?php echo $regranegocio->nu_usuario->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_regranegocio_nu_usuario" class="regranegocio_nu_usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $regranegocio->nu_usuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($regranegocio->nu_usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($regranegocio->nu_usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($regranegocio->dt_versao->Visible) { // dt_versao ?>
	<?php if ($regranegocio->SortUrl($regranegocio->dt_versao) == "") { ?>
		<td><div id="elh_regranegocio_dt_versao" class="regranegocio_dt_versao"><div class="ewTableHeaderCaption"><?php echo $regranegocio->dt_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_regranegocio_dt_versao" class="regranegocio_dt_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $regranegocio->dt_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($regranegocio->dt_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($regranegocio->dt_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$regranegocio_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$regranegocio_grid->StartRec = 1;
$regranegocio_grid->StopRec = $regranegocio_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($regranegocio_grid->FormKeyCountName) && ($regranegocio->CurrentAction == "gridadd" || $regranegocio->CurrentAction == "gridedit" || $regranegocio->CurrentAction == "F")) {
		$regranegocio_grid->KeyCount = $objForm->GetValue($regranegocio_grid->FormKeyCountName);
		$regranegocio_grid->StopRec = $regranegocio_grid->StartRec + $regranegocio_grid->KeyCount - 1;
	}
}
$regranegocio_grid->RecCnt = $regranegocio_grid->StartRec - 1;
if ($regranegocio_grid->Recordset && !$regranegocio_grid->Recordset->EOF) {
	$regranegocio_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $regranegocio_grid->StartRec > 1)
		$regranegocio_grid->Recordset->Move($regranegocio_grid->StartRec - 1);
} elseif (!$regranegocio->AllowAddDeleteRow && $regranegocio_grid->StopRec == 0) {
	$regranegocio_grid->StopRec = $regranegocio->GridAddRowCount;
}

// Initialize aggregate
$regranegocio->RowType = EW_ROWTYPE_AGGREGATEINIT;
$regranegocio->ResetAttrs();
$regranegocio_grid->RenderRow();
if ($regranegocio->CurrentAction == "gridadd")
	$regranegocio_grid->RowIndex = 0;
if ($regranegocio->CurrentAction == "gridedit")
	$regranegocio_grid->RowIndex = 0;
while ($regranegocio_grid->RecCnt < $regranegocio_grid->StopRec) {
	$regranegocio_grid->RecCnt++;
	if (intval($regranegocio_grid->RecCnt) >= intval($regranegocio_grid->StartRec)) {
		$regranegocio_grid->RowCnt++;
		if ($regranegocio->CurrentAction == "gridadd" || $regranegocio->CurrentAction == "gridedit" || $regranegocio->CurrentAction == "F") {
			$regranegocio_grid->RowIndex++;
			$objForm->Index = $regranegocio_grid->RowIndex;
			if ($objForm->HasValue($regranegocio_grid->FormActionName))
				$regranegocio_grid->RowAction = strval($objForm->GetValue($regranegocio_grid->FormActionName));
			elseif ($regranegocio->CurrentAction == "gridadd")
				$regranegocio_grid->RowAction = "insert";
			else
				$regranegocio_grid->RowAction = "";
		}

		// Set up key count
		$regranegocio_grid->KeyCount = $regranegocio_grid->RowIndex;

		// Init row class and style
		$regranegocio->ResetAttrs();
		$regranegocio->CssClass = "";
		if ($regranegocio->CurrentAction == "gridadd") {
			if ($regranegocio->CurrentMode == "copy") {
				$regranegocio_grid->LoadRowValues($regranegocio_grid->Recordset); // Load row values
				$regranegocio_grid->SetRecordKey($regranegocio_grid->RowOldKey, $regranegocio_grid->Recordset); // Set old record key
			} else {
				$regranegocio_grid->LoadDefaultValues(); // Load default values
				$regranegocio_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$regranegocio_grid->LoadRowValues($regranegocio_grid->Recordset); // Load row values
		}
		$regranegocio->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($regranegocio->CurrentAction == "gridadd") // Grid add
			$regranegocio->RowType = EW_ROWTYPE_ADD; // Render add
		if ($regranegocio->CurrentAction == "gridadd" && $regranegocio->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$regranegocio_grid->RestoreCurrentRowFormValues($regranegocio_grid->RowIndex); // Restore form values
		if ($regranegocio->CurrentAction == "gridedit") { // Grid edit
			if ($regranegocio->EventCancelled) {
				$regranegocio_grid->RestoreCurrentRowFormValues($regranegocio_grid->RowIndex); // Restore form values
			}
			if ($regranegocio_grid->RowAction == "insert")
				$regranegocio->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$regranegocio->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($regranegocio->CurrentAction == "gridedit" && ($regranegocio->RowType == EW_ROWTYPE_EDIT || $regranegocio->RowType == EW_ROWTYPE_ADD) && $regranegocio->EventCancelled) // Update failed
			$regranegocio_grid->RestoreCurrentRowFormValues($regranegocio_grid->RowIndex); // Restore form values
		if ($regranegocio->RowType == EW_ROWTYPE_EDIT) // Edit row
			$regranegocio_grid->EditRowCnt++;
		if ($regranegocio->CurrentAction == "F") // Confirm row
			$regranegocio_grid->RestoreCurrentRowFormValues($regranegocio_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$regranegocio->RowAttrs = array_merge($regranegocio->RowAttrs, array('data-rowindex'=>$regranegocio_grid->RowCnt, 'id'=>'r' . $regranegocio_grid->RowCnt . '_regranegocio', 'data-rowtype'=>$regranegocio->RowType));

		// Render row
		$regranegocio_grid->RenderRow();

		// Render list options
		$regranegocio_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($regranegocio_grid->RowAction <> "delete" && $regranegocio_grid->RowAction <> "insertdelete" && !($regranegocio_grid->RowAction == "insert" && $regranegocio->CurrentAction == "F" && $regranegocio_grid->EmptyRow())) {
?>
	<tr<?php echo $regranegocio->RowAttributes() ?>>
<?php

// Render list options (body, left)
$regranegocio_grid->ListOptions->Render("body", "left", $regranegocio_grid->RowCnt);
?>
	<?php if ($regranegocio->nu_versao->Visible) { // nu_versao ?>
		<td<?php echo $regranegocio->nu_versao->CellAttributes() ?>>
<?php if ($regranegocio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $regranegocio_grid->RowCnt ?>_regranegocio_nu_versao" class="control-group regranegocio_nu_versao">
<input type="text" data-field="x_nu_versao" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" size="30" placeholder="<?php echo $regranegocio->nu_versao->PlaceHolder ?>" value="<?php echo $regranegocio->nu_versao->EditValue ?>"<?php echo $regranegocio->nu_versao->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($regranegocio->nu_versao->OldValue) ?>">
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $regranegocio_grid->RowCnt ?>_regranegocio_nu_versao" class="control-group regranegocio_nu_versao">
<span<?php echo $regranegocio->nu_versao->ViewAttributes() ?>>
<?php echo $regranegocio->nu_versao->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($regranegocio->nu_versao->CurrentValue) ?>">
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $regranegocio->nu_versao->ViewAttributes() ?>>
<?php echo $regranegocio->nu_versao->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($regranegocio->nu_versao->FormValue) ?>">
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($regranegocio->nu_versao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $regranegocio_grid->PageObjName . "_row_" . $regranegocio_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_co_alternativo" name="x<?php echo $regranegocio_grid->RowIndex ?>_co_alternativo" id="x<?php echo $regranegocio_grid->RowIndex ?>_co_alternativo" value="<?php echo ew_HtmlEncode($regranegocio->co_alternativo->CurrentValue) ?>">
<input type="hidden" data-field="x_co_alternativo" name="o<?php echo $regranegocio_grid->RowIndex ?>_co_alternativo" id="o<?php echo $regranegocio_grid->RowIndex ?>_co_alternativo" value="<?php echo ew_HtmlEncode($regranegocio->co_alternativo->OldValue) ?>">
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_EDIT || $regranegocio->CurrentMode == "edit") { ?>
<input type="hidden" data-field="x_co_alternativo" name="x<?php echo $regranegocio_grid->RowIndex ?>_co_alternativo" id="x<?php echo $regranegocio_grid->RowIndex ?>_co_alternativo" value="<?php echo ew_HtmlEncode($regranegocio->co_alternativo->CurrentValue) ?>">
<?php } ?>
	<?php if ($regranegocio->no_regraNegocio->Visible) { // no_regraNegocio ?>
		<td<?php echo $regranegocio->no_regraNegocio->CellAttributes() ?>>
<?php if ($regranegocio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $regranegocio_grid->RowCnt ?>_regranegocio_no_regraNegocio" class="control-group regranegocio_no_regraNegocio">
<input type="text" data-field="x_no_regraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" size="30" maxlength="150" placeholder="<?php echo $regranegocio->no_regraNegocio->PlaceHolder ?>" value="<?php echo $regranegocio->no_regraNegocio->EditValue ?>"<?php echo $regranegocio->no_regraNegocio->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_no_regraNegocio" name="o<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" id="o<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->no_regraNegocio->OldValue) ?>">
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $regranegocio_grid->RowCnt ?>_regranegocio_no_regraNegocio" class="control-group regranegocio_no_regraNegocio">
<input type="text" data-field="x_no_regraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" size="30" maxlength="150" placeholder="<?php echo $regranegocio->no_regraNegocio->PlaceHolder ?>" value="<?php echo $regranegocio->no_regraNegocio->EditValue ?>"<?php echo $regranegocio->no_regraNegocio->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $regranegocio->no_regraNegocio->ViewAttributes() ?>>
<?php echo $regranegocio->no_regraNegocio->ListViewValue() ?></span>
<input type="hidden" data-field="x_no_regraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->no_regraNegocio->FormValue) ?>">
<input type="hidden" data-field="x_no_regraNegocio" name="o<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" id="o<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->no_regraNegocio->OldValue) ?>">
<?php } ?>
<a id="<?php echo $regranegocio_grid->PageObjName . "_row_" . $regranegocio_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($regranegocio->nu_projeto->Visible) { // nu_projeto ?>
		<td<?php echo $regranegocio->nu_projeto->CellAttributes() ?>>
<?php if ($regranegocio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $regranegocio_grid->RowCnt ?>_regranegocio_nu_projeto" class="control-group regranegocio_nu_projeto">
<select data-field="x_nu_projeto" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto"<?php echo $regranegocio->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($regranegocio->nu_projeto->EditValue)) {
	$arwrk = $regranegocio->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $regranegocio->nu_projeto->OldValue = "";
?>
</select>
<script type="text/javascript">
fregranegociogrid.Lists["x_nu_projeto"].Options = <?php echo (is_array($regranegocio->nu_projeto->EditValue)) ? ew_ArrayToJson($regranegocio->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_projeto" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($regranegocio->nu_projeto->OldValue) ?>">
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $regranegocio_grid->RowCnt ?>_regranegocio_nu_projeto" class="control-group regranegocio_nu_projeto">
<select data-field="x_nu_projeto" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto"<?php echo $regranegocio->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($regranegocio->nu_projeto->EditValue)) {
	$arwrk = $regranegocio->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $regranegocio->nu_projeto->OldValue = "";
?>
</select>
<script type="text/javascript">
fregranegociogrid.Lists["x_nu_projeto"].Options = <?php echo (is_array($regranegocio->nu_projeto->EditValue)) ? ew_ArrayToJson($regranegocio->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $regranegocio->nu_projeto->ViewAttributes() ?>>
<?php echo $regranegocio->nu_projeto->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_projeto" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($regranegocio->nu_projeto->FormValue) ?>">
<input type="hidden" data-field="x_nu_projeto" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($regranegocio->nu_projeto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $regranegocio_grid->PageObjName . "_row_" . $regranegocio_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($regranegocio->nu_stRegraNegocio->Visible) { // nu_stRegraNegocio ?>
		<td<?php echo $regranegocio->nu_stRegraNegocio->CellAttributes() ?>>
<?php if ($regranegocio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $regranegocio_grid->RowCnt ?>_regranegocio_nu_stRegraNegocio" class="control-group regranegocio_nu_stRegraNegocio">
<select data-field="x_nu_stRegraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio"<?php echo $regranegocio->nu_stRegraNegocio->EditAttributes() ?>>
<?php
if (is_array($regranegocio->nu_stRegraNegocio->EditValue)) {
	$arwrk = $regranegocio->nu_stRegraNegocio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->nu_stRegraNegocio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $regranegocio->nu_stRegraNegocio->OldValue = "";
?>
</select>
<script type="text/javascript">
fregranegociogrid.Lists["x_nu_stRegraNegocio"].Options = <?php echo (is_array($regranegocio->nu_stRegraNegocio->EditValue)) ? ew_ArrayToJson($regranegocio->nu_stRegraNegocio->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_stRegraNegocio" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->nu_stRegraNegocio->OldValue) ?>">
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $regranegocio_grid->RowCnt ?>_regranegocio_nu_stRegraNegocio" class="control-group regranegocio_nu_stRegraNegocio">
<select data-field="x_nu_stRegraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio"<?php echo $regranegocio->nu_stRegraNegocio->EditAttributes() ?>>
<?php
if (is_array($regranegocio->nu_stRegraNegocio->EditValue)) {
	$arwrk = $regranegocio->nu_stRegraNegocio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->nu_stRegraNegocio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $regranegocio->nu_stRegraNegocio->OldValue = "";
?>
</select>
<script type="text/javascript">
fregranegociogrid.Lists["x_nu_stRegraNegocio"].Options = <?php echo (is_array($regranegocio->nu_stRegraNegocio->EditValue)) ? ew_ArrayToJson($regranegocio->nu_stRegraNegocio->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $regranegocio->nu_stRegraNegocio->ViewAttributes() ?>>
<?php echo $regranegocio->nu_stRegraNegocio->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_stRegraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->nu_stRegraNegocio->FormValue) ?>">
<input type="hidden" data-field="x_nu_stRegraNegocio" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->nu_stRegraNegocio->OldValue) ?>">
<?php } ?>
<a id="<?php echo $regranegocio_grid->PageObjName . "_row_" . $regranegocio_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($regranegocio->nu_usuario->Visible) { // nu_usuario ?>
		<td<?php echo $regranegocio->nu_usuario->CellAttributes() ?>>
<?php if ($regranegocio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_usuario" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" value="<?php echo ew_HtmlEncode($regranegocio->nu_usuario->OldValue) ?>">
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $regranegocio->nu_usuario->ViewAttributes() ?>>
<?php echo $regranegocio->nu_usuario->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_usuario" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" value="<?php echo ew_HtmlEncode($regranegocio->nu_usuario->FormValue) ?>">
<input type="hidden" data-field="x_nu_usuario" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" value="<?php echo ew_HtmlEncode($regranegocio->nu_usuario->OldValue) ?>">
<?php } ?>
<a id="<?php echo $regranegocio_grid->PageObjName . "_row_" . $regranegocio_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($regranegocio->dt_versao->Visible) { // dt_versao ?>
		<td<?php echo $regranegocio->dt_versao->CellAttributes() ?>>
<?php if ($regranegocio->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_dt_versao" name="o<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" id="o<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" value="<?php echo ew_HtmlEncode($regranegocio->dt_versao->OldValue) ?>">
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($regranegocio->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $regranegocio->dt_versao->ViewAttributes() ?>>
<?php echo $regranegocio->dt_versao->ListViewValue() ?></span>
<input type="hidden" data-field="x_dt_versao" name="x<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" id="x<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" value="<?php echo ew_HtmlEncode($regranegocio->dt_versao->FormValue) ?>">
<input type="hidden" data-field="x_dt_versao" name="o<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" id="o<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" value="<?php echo ew_HtmlEncode($regranegocio->dt_versao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $regranegocio_grid->PageObjName . "_row_" . $regranegocio_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$regranegocio_grid->ListOptions->Render("body", "right", $regranegocio_grid->RowCnt);
?>
	</tr>
<?php if ($regranegocio->RowType == EW_ROWTYPE_ADD || $regranegocio->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fregranegociogrid.UpdateOpts(<?php echo $regranegocio_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($regranegocio->CurrentAction <> "gridadd" || $regranegocio->CurrentMode == "copy")
		if (!$regranegocio_grid->Recordset->EOF) $regranegocio_grid->Recordset->MoveNext();
}
?>
<?php
	if ($regranegocio->CurrentMode == "add" || $regranegocio->CurrentMode == "copy" || $regranegocio->CurrentMode == "edit") {
		$regranegocio_grid->RowIndex = '$rowindex$';
		$regranegocio_grid->LoadDefaultValues();

		// Set row properties
		$regranegocio->ResetAttrs();
		$regranegocio->RowAttrs = array_merge($regranegocio->RowAttrs, array('data-rowindex'=>$regranegocio_grid->RowIndex, 'id'=>'r0_regranegocio', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($regranegocio->RowAttrs["class"], "ewTemplate");
		$regranegocio->RowType = EW_ROWTYPE_ADD;

		// Render row
		$regranegocio_grid->RenderRow();

		// Render list options
		$regranegocio_grid->RenderListOptions();
		$regranegocio_grid->StartRowCnt = 0;
?>
	<tr<?php echo $regranegocio->RowAttributes() ?>>
<?php

// Render list options (body, left)
$regranegocio_grid->ListOptions->Render("body", "left", $regranegocio_grid->RowIndex);
?>
	<?php if ($regranegocio->nu_versao->Visible) { // nu_versao ?>
		<td>
<?php if ($regranegocio->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_versao" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" size="30" placeholder="<?php echo $regranegocio->nu_versao->PlaceHolder ?>" value="<?php echo $regranegocio->nu_versao->EditValue ?>"<?php echo $regranegocio->nu_versao->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $regranegocio->nu_versao->ViewAttributes() ?>>
<?php echo $regranegocio->nu_versao->ViewValue ?></span>
<input type="hidden" data-field="x_nu_versao" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($regranegocio->nu_versao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_versao" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_versao" value="<?php echo ew_HtmlEncode($regranegocio->nu_versao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($regranegocio->no_regraNegocio->Visible) { // no_regraNegocio ?>
		<td>
<?php if ($regranegocio->CurrentAction <> "F") { ?>
<input type="text" data-field="x_no_regraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" size="30" maxlength="150" placeholder="<?php echo $regranegocio->no_regraNegocio->PlaceHolder ?>" value="<?php echo $regranegocio->no_regraNegocio->EditValue ?>"<?php echo $regranegocio->no_regraNegocio->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $regranegocio->no_regraNegocio->ViewAttributes() ?>>
<?php echo $regranegocio->no_regraNegocio->ViewValue ?></span>
<input type="hidden" data-field="x_no_regraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->no_regraNegocio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_no_regraNegocio" name="o<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" id="o<?php echo $regranegocio_grid->RowIndex ?>_no_regraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->no_regraNegocio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($regranegocio->nu_projeto->Visible) { // nu_projeto ?>
		<td>
<?php if ($regranegocio->CurrentAction <> "F") { ?>
<select data-field="x_nu_projeto" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto"<?php echo $regranegocio->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($regranegocio->nu_projeto->EditValue)) {
	$arwrk = $regranegocio->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $regranegocio->nu_projeto->OldValue = "";
?>
</select>
<script type="text/javascript">
fregranegociogrid.Lists["x_nu_projeto"].Options = <?php echo (is_array($regranegocio->nu_projeto->EditValue)) ? ew_ArrayToJson($regranegocio->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $regranegocio->nu_projeto->ViewAttributes() ?>>
<?php echo $regranegocio->nu_projeto->ViewValue ?></span>
<input type="hidden" data-field="x_nu_projeto" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($regranegocio->nu_projeto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_projeto" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($regranegocio->nu_projeto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($regranegocio->nu_stRegraNegocio->Visible) { // nu_stRegraNegocio ?>
		<td>
<?php if ($regranegocio->CurrentAction <> "F") { ?>
<select data-field="x_nu_stRegraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio"<?php echo $regranegocio->nu_stRegraNegocio->EditAttributes() ?>>
<?php
if (is_array($regranegocio->nu_stRegraNegocio->EditValue)) {
	$arwrk = $regranegocio->nu_stRegraNegocio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->nu_stRegraNegocio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $regranegocio->nu_stRegraNegocio->OldValue = "";
?>
</select>
<script type="text/javascript">
fregranegociogrid.Lists["x_nu_stRegraNegocio"].Options = <?php echo (is_array($regranegocio->nu_stRegraNegocio->EditValue)) ? ew_ArrayToJson($regranegocio->nu_stRegraNegocio->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $regranegocio->nu_stRegraNegocio->ViewAttributes() ?>>
<?php echo $regranegocio->nu_stRegraNegocio->ViewValue ?></span>
<input type="hidden" data-field="x_nu_stRegraNegocio" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->nu_stRegraNegocio->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_stRegraNegocio" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_stRegraNegocio" value="<?php echo ew_HtmlEncode($regranegocio->nu_stRegraNegocio->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($regranegocio->nu_usuario->Visible) { // nu_usuario ?>
		<td>
<?php if ($regranegocio->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $regranegocio->nu_usuario->ViewAttributes() ?>>
<?php echo $regranegocio->nu_usuario->ViewValue ?></span>
<input type="hidden" data-field="x_nu_usuario" name="x<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" id="x<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" value="<?php echo ew_HtmlEncode($regranegocio->nu_usuario->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_usuario" name="o<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" id="o<?php echo $regranegocio_grid->RowIndex ?>_nu_usuario" value="<?php echo ew_HtmlEncode($regranegocio->nu_usuario->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($regranegocio->dt_versao->Visible) { // dt_versao ?>
		<td>
<?php if ($regranegocio->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $regranegocio->dt_versao->ViewAttributes() ?>>
<?php echo $regranegocio->dt_versao->ViewValue ?></span>
<input type="hidden" data-field="x_dt_versao" name="x<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" id="x<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" value="<?php echo ew_HtmlEncode($regranegocio->dt_versao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_dt_versao" name="o<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" id="o<?php echo $regranegocio_grid->RowIndex ?>_dt_versao" value="<?php echo ew_HtmlEncode($regranegocio->dt_versao->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$regranegocio_grid->ListOptions->Render("body", "right", $regranegocio_grid->RowCnt);
?>
<script type="text/javascript">
fregranegociogrid.UpdateOpts(<?php echo $regranegocio_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($regranegocio->CurrentMode == "add" || $regranegocio->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $regranegocio_grid->FormKeyCountName ?>" id="<?php echo $regranegocio_grid->FormKeyCountName ?>" value="<?php echo $regranegocio_grid->KeyCount ?>">
<?php echo $regranegocio_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($regranegocio->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $regranegocio_grid->FormKeyCountName ?>" id="<?php echo $regranegocio_grid->FormKeyCountName ?>" value="<?php echo $regranegocio_grid->KeyCount ?>">
<?php echo $regranegocio_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($regranegocio->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fregranegociogrid">
</div>
<?php

// Close recordset
if ($regranegocio_grid->Recordset)
	$regranegocio_grid->Recordset->Close();
?>
<?php if ($regranegocio_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($regranegocio_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($regranegocio->Export == "") { ?>
<script type="text/javascript">
fregranegociogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$regranegocio_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$regranegocio_grid->Page_Terminate();
?>
