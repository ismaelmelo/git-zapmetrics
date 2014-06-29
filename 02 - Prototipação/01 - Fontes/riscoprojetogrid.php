<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($riscoprojeto_grid)) $riscoprojeto_grid = new criscoprojeto_grid();

// Page init
$riscoprojeto_grid->Page_Init();

// Page main
$riscoprojeto_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$riscoprojeto_grid->Page_Render();
?>
<?php if ($riscoprojeto->Export == "") { ?>
<script type="text/javascript">

// Page object
var riscoprojeto_grid = new ew_Page("riscoprojeto_grid");
riscoprojeto_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = riscoprojeto_grid.PageID; // For backward compatibility

// Form object
var friscoprojetogrid = new ew_Form("friscoprojetogrid");
friscoprojetogrid.FormKeyCountName = '<?php echo $riscoprojeto_grid->FormKeyCountName ?>';

// Validate form
friscoprojetogrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_projeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->nu_projeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_catRisco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->nu_catRisco->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpRisco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->ic_tpRisco->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_probabilidade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->nu_probabilidade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_severidade");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($riscoprojeto->nu_severidade->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_acao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->nu_acao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_stRisco");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($riscoprojeto->ic_stRisco->FldCaption()) ?>");

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
friscoprojetogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "nu_projeto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_catRisco", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_tpRisco", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_probabilidade", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_impacto", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_severidade", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nu_acao", false)) return false;
	if (ew_ValueChanged(fobj, infix, "ic_stRisco", false)) return false;
	return true;
}

// Form_CustomValidate event
friscoprojetogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
friscoprojetogrid.ValidateRequired = true;
<?php } else { ?>
friscoprojetogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
friscoprojetogrid.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetogrid.Lists["x_nu_catRisco"] = {"LinkField":"x_nu_catRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_catRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetogrid.Lists["x_nu_probabilidade"] = {"LinkField":"x_nu_probOcoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_probOcoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetogrid.Lists["x_nu_impacto"] = {"LinkField":"x_nu_impactoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_impactoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetogrid.Lists["x_nu_acao"] = {"LinkField":"x_nu_acaoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_acaoRisco","","",""],"ParentFields":["x_ic_tpRisco"],"FilterFields":["x_ic_tpRisco"],"Options":[]};
friscoprojetogrid.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php if ($riscoprojeto->getCurrentMasterTable() == "" && $riscoprojeto_grid->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $riscoprojeto_grid->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
if ($riscoprojeto->CurrentAction == "gridadd") {
	if ($riscoprojeto->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$riscoprojeto_grid->TotalRecs = $riscoprojeto->SelectRecordCount();
			$riscoprojeto_grid->Recordset = $riscoprojeto_grid->LoadRecordset($riscoprojeto_grid->StartRec-1, $riscoprojeto_grid->DisplayRecs);
		} else {
			if ($riscoprojeto_grid->Recordset = $riscoprojeto_grid->LoadRecordset())
				$riscoprojeto_grid->TotalRecs = $riscoprojeto_grid->Recordset->RecordCount();
		}
		$riscoprojeto_grid->StartRec = 1;
		$riscoprojeto_grid->DisplayRecs = $riscoprojeto_grid->TotalRecs;
	} else {
		$riscoprojeto->CurrentFilter = "0=1";
		$riscoprojeto_grid->StartRec = 1;
		$riscoprojeto_grid->DisplayRecs = $riscoprojeto->GridAddRowCount;
	}
	$riscoprojeto_grid->TotalRecs = $riscoprojeto_grid->DisplayRecs;
	$riscoprojeto_grid->StopRec = $riscoprojeto_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$riscoprojeto_grid->TotalRecs = $riscoprojeto->SelectRecordCount();
	} else {
		if ($riscoprojeto_grid->Recordset = $riscoprojeto_grid->LoadRecordset())
			$riscoprojeto_grid->TotalRecs = $riscoprojeto_grid->Recordset->RecordCount();
	}
	$riscoprojeto_grid->StartRec = 1;
	$riscoprojeto_grid->DisplayRecs = $riscoprojeto_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$riscoprojeto_grid->Recordset = $riscoprojeto_grid->LoadRecordset($riscoprojeto_grid->StartRec-1, $riscoprojeto_grid->DisplayRecs);
}
$riscoprojeto_grid->RenderOtherOptions();
?>
<?php $riscoprojeto_grid->ShowPageHeader(); ?>
<?php
$riscoprojeto_grid->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div id="friscoprojetogrid" class="ewForm form-horizontal">
<div id="gmp_riscoprojeto" class="ewGridMiddlePanel">
<table id="tbl_riscoprojetogrid" class="ewTable ewTableSeparate">
<?php echo $riscoprojeto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$riscoprojeto_grid->RenderListOptions();

// Render list options (header, left)
$riscoprojeto_grid->ListOptions->Render("header", "left");
?>
<?php if ($riscoprojeto->nu_riscoProjeto->Visible) { // nu_riscoProjeto ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_riscoProjeto) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_riscoProjeto" class="riscoprojeto_nu_riscoProjeto"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_riscoProjeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_nu_riscoProjeto" class="riscoprojeto_nu_riscoProjeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_riscoProjeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_riscoProjeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_riscoProjeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_projeto->Visible) { // nu_projeto ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_projeto) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_projeto" class="riscoprojeto_nu_projeto"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_nu_projeto" class="riscoprojeto_nu_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_catRisco->Visible) { // nu_catRisco ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_catRisco) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_catRisco" class="riscoprojeto_nu_catRisco"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_catRisco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_nu_catRisco" class="riscoprojeto_nu_catRisco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_catRisco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_catRisco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_catRisco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->ic_tpRisco->Visible) { // ic_tpRisco ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->ic_tpRisco) == "") { ?>
		<td><div id="elh_riscoprojeto_ic_tpRisco" class="riscoprojeto_ic_tpRisco"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->ic_tpRisco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_ic_tpRisco" class="riscoprojeto_ic_tpRisco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->ic_tpRisco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->ic_tpRisco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->ic_tpRisco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_probabilidade->Visible) { // nu_probabilidade ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_probabilidade) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_probabilidade" class="riscoprojeto_nu_probabilidade"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_probabilidade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_nu_probabilidade" class="riscoprojeto_nu_probabilidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_probabilidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_probabilidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_probabilidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_impacto->Visible) { // nu_impacto ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_impacto) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_impacto" class="riscoprojeto_nu_impacto"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_impacto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_nu_impacto" class="riscoprojeto_nu_impacto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_impacto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_impacto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_impacto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_severidade->Visible) { // nu_severidade ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_severidade) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_severidade" class="riscoprojeto_nu_severidade"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_severidade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_nu_severidade" class="riscoprojeto_nu_severidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_severidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_severidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_severidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_acao->Visible) { // nu_acao ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_acao) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_acao" class="riscoprojeto_nu_acao"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_acao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_nu_acao" class="riscoprojeto_nu_acao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_acao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_acao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_acao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_usuarioResp) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_usuarioResp" class="riscoprojeto_nu_usuarioResp"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_usuarioResp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_nu_usuarioResp" class="riscoprojeto_nu_usuarioResp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_usuarioResp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_usuarioResp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_usuarioResp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->ic_stRisco->Visible) { // ic_stRisco ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->ic_stRisco) == "") { ?>
		<td><div id="elh_riscoprojeto_ic_stRisco" class="riscoprojeto_ic_stRisco"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->ic_stRisco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div><div id="elh_riscoprojeto_ic_stRisco" class="riscoprojeto_ic_stRisco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->ic_stRisco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->ic_stRisco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->ic_stRisco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$riscoprojeto_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$riscoprojeto_grid->StartRec = 1;
$riscoprojeto_grid->StopRec = $riscoprojeto_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($riscoprojeto_grid->FormKeyCountName) && ($riscoprojeto->CurrentAction == "gridadd" || $riscoprojeto->CurrentAction == "gridedit" || $riscoprojeto->CurrentAction == "F")) {
		$riscoprojeto_grid->KeyCount = $objForm->GetValue($riscoprojeto_grid->FormKeyCountName);
		$riscoprojeto_grid->StopRec = $riscoprojeto_grid->StartRec + $riscoprojeto_grid->KeyCount - 1;
	}
}
$riscoprojeto_grid->RecCnt = $riscoprojeto_grid->StartRec - 1;
if ($riscoprojeto_grid->Recordset && !$riscoprojeto_grid->Recordset->EOF) {
	$riscoprojeto_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $riscoprojeto_grid->StartRec > 1)
		$riscoprojeto_grid->Recordset->Move($riscoprojeto_grid->StartRec - 1);
} elseif (!$riscoprojeto->AllowAddDeleteRow && $riscoprojeto_grid->StopRec == 0) {
	$riscoprojeto_grid->StopRec = $riscoprojeto->GridAddRowCount;
}

// Initialize aggregate
$riscoprojeto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$riscoprojeto->ResetAttrs();
$riscoprojeto_grid->RenderRow();
if ($riscoprojeto->CurrentAction == "gridadd")
	$riscoprojeto_grid->RowIndex = 0;
if ($riscoprojeto->CurrentAction == "gridedit")
	$riscoprojeto_grid->RowIndex = 0;
while ($riscoprojeto_grid->RecCnt < $riscoprojeto_grid->StopRec) {
	$riscoprojeto_grid->RecCnt++;
	if (intval($riscoprojeto_grid->RecCnt) >= intval($riscoprojeto_grid->StartRec)) {
		$riscoprojeto_grid->RowCnt++;
		if ($riscoprojeto->CurrentAction == "gridadd" || $riscoprojeto->CurrentAction == "gridedit" || $riscoprojeto->CurrentAction == "F") {
			$riscoprojeto_grid->RowIndex++;
			$objForm->Index = $riscoprojeto_grid->RowIndex;
			if ($objForm->HasValue($riscoprojeto_grid->FormActionName))
				$riscoprojeto_grid->RowAction = strval($objForm->GetValue($riscoprojeto_grid->FormActionName));
			elseif ($riscoprojeto->CurrentAction == "gridadd")
				$riscoprojeto_grid->RowAction = "insert";
			else
				$riscoprojeto_grid->RowAction = "";
		}

		// Set up key count
		$riscoprojeto_grid->KeyCount = $riscoprojeto_grid->RowIndex;

		// Init row class and style
		$riscoprojeto->ResetAttrs();
		$riscoprojeto->CssClass = "";
		if ($riscoprojeto->CurrentAction == "gridadd") {
			if ($riscoprojeto->CurrentMode == "copy") {
				$riscoprojeto_grid->LoadRowValues($riscoprojeto_grid->Recordset); // Load row values
				$riscoprojeto_grid->SetRecordKey($riscoprojeto_grid->RowOldKey, $riscoprojeto_grid->Recordset); // Set old record key
			} else {
				$riscoprojeto_grid->LoadDefaultValues(); // Load default values
				$riscoprojeto_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$riscoprojeto_grid->LoadRowValues($riscoprojeto_grid->Recordset); // Load row values
		}
		$riscoprojeto->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($riscoprojeto->CurrentAction == "gridadd") // Grid add
			$riscoprojeto->RowType = EW_ROWTYPE_ADD; // Render add
		if ($riscoprojeto->CurrentAction == "gridadd" && $riscoprojeto->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$riscoprojeto_grid->RestoreCurrentRowFormValues($riscoprojeto_grid->RowIndex); // Restore form values
		if ($riscoprojeto->CurrentAction == "gridedit") { // Grid edit
			if ($riscoprojeto->EventCancelled) {
				$riscoprojeto_grid->RestoreCurrentRowFormValues($riscoprojeto_grid->RowIndex); // Restore form values
			}
			if ($riscoprojeto_grid->RowAction == "insert")
				$riscoprojeto->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$riscoprojeto->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($riscoprojeto->CurrentAction == "gridedit" && ($riscoprojeto->RowType == EW_ROWTYPE_EDIT || $riscoprojeto->RowType == EW_ROWTYPE_ADD) && $riscoprojeto->EventCancelled) // Update failed
			$riscoprojeto_grid->RestoreCurrentRowFormValues($riscoprojeto_grid->RowIndex); // Restore form values
		if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) // Edit row
			$riscoprojeto_grid->EditRowCnt++;
		if ($riscoprojeto->CurrentAction == "F") // Confirm row
			$riscoprojeto_grid->RestoreCurrentRowFormValues($riscoprojeto_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$riscoprojeto->RowAttrs = array_merge($riscoprojeto->RowAttrs, array('data-rowindex'=>$riscoprojeto_grid->RowCnt, 'id'=>'r' . $riscoprojeto_grid->RowCnt . '_riscoprojeto', 'data-rowtype'=>$riscoprojeto->RowType));

		// Render row
		$riscoprojeto_grid->RenderRow();

		// Render list options
		$riscoprojeto_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($riscoprojeto_grid->RowAction <> "delete" && $riscoprojeto_grid->RowAction <> "insertdelete" && !($riscoprojeto_grid->RowAction == "insert" && $riscoprojeto->CurrentAction == "F" && $riscoprojeto_grid->EmptyRow())) {
?>
	<tr<?php echo $riscoprojeto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$riscoprojeto_grid->ListOptions->Render("body", "left", $riscoprojeto_grid->RowCnt);
?>
	<?php if ($riscoprojeto->nu_riscoProjeto->Visible) { // nu_riscoProjeto ?>
		<td<?php echo $riscoprojeto->nu_riscoProjeto->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_riscoProjeto" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_riscoProjeto->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_riscoProjeto" class="control-group riscoprojeto_nu_riscoProjeto">
<span<?php echo $riscoprojeto->nu_riscoProjeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_riscoProjeto->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_riscoProjeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_riscoProjeto->CurrentValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->nu_riscoProjeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_riscoProjeto->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_riscoProjeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_riscoProjeto->FormValue) ?>">
<input type="hidden" data-field="x_nu_riscoProjeto" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_riscoProjeto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_projeto->Visible) { // nu_projeto ?>
		<td<?php echo $riscoprojeto->nu_projeto->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($riscoprojeto->nu_projeto->getSessionValue() <> "") { ?>
<span<?php echo $riscoprojeto->nu_projeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_projeto->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_projeto->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_projeto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto"<?php echo $riscoprojeto->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_projeto->EditValue)) {
	$arwrk = $riscoprojeto->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_projeto->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_projeto"].Options = <?php echo (is_array($riscoprojeto->nu_projeto->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" data-field="x_nu_projeto" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_projeto->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($riscoprojeto->nu_projeto->getSessionValue() <> "") { ?>
<span<?php echo $riscoprojeto->nu_projeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_projeto->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_projeto->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_projeto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto"<?php echo $riscoprojeto->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_projeto->EditValue)) {
	$arwrk = $riscoprojeto->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_projeto->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_projeto"].Options = <?php echo (is_array($riscoprojeto->nu_projeto->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->nu_projeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_projeto->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_projeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_projeto->FormValue) ?>">
<input type="hidden" data-field="x_nu_projeto" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_projeto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_catRisco->Visible) { // nu_catRisco ?>
		<td<?php echo $riscoprojeto->nu_catRisco->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_catRisco" class="control-group riscoprojeto_nu_catRisco">
<select data-field="x_nu_catRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco"<?php echo $riscoprojeto->nu_catRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_catRisco->EditValue)) {
	$arwrk = $riscoprojeto->nu_catRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_catRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_catRisco->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_catRisco"].Options = <?php echo (is_array($riscoprojeto->nu_catRisco->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_catRisco->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_catRisco" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_catRisco->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_catRisco" class="control-group riscoprojeto_nu_catRisco">
<select data-field="x_nu_catRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco"<?php echo $riscoprojeto->nu_catRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_catRisco->EditValue)) {
	$arwrk = $riscoprojeto->nu_catRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_catRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_catRisco->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_catRisco"].Options = <?php echo (is_array($riscoprojeto->nu_catRisco->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_catRisco->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->nu_catRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_catRisco->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_catRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_catRisco->FormValue) ?>">
<input type="hidden" data-field="x_nu_catRisco" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_catRisco->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->ic_tpRisco->Visible) { // ic_tpRisco ?>
		<td<?php echo $riscoprojeto->ic_tpRisco->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_ic_tpRisco" class="control-group riscoprojeto_ic_tpRisco">
<?php $riscoprojeto->ic_tpRisco->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $riscoprojeto_grid->RowIndex . "_nu_acao']); " . @$riscoprojeto->ic_tpRisco->EditAttrs["onchange"]; ?>
<select data-field="x_ic_tpRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco"<?php echo $riscoprojeto->ic_tpRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->ic_tpRisco->EditValue)) {
	$arwrk = $riscoprojeto->ic_tpRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_tpRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->ic_tpRisco->OldValue = "";
?>
</select>
</span>
<input type="hidden" data-field="x_ic_tpRisco" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_tpRisco->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_ic_tpRisco" class="control-group riscoprojeto_ic_tpRisco">
<?php $riscoprojeto->ic_tpRisco->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $riscoprojeto_grid->RowIndex . "_nu_acao']); " . @$riscoprojeto->ic_tpRisco->EditAttrs["onchange"]; ?>
<select data-field="x_ic_tpRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco"<?php echo $riscoprojeto->ic_tpRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->ic_tpRisco->EditValue)) {
	$arwrk = $riscoprojeto->ic_tpRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_tpRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->ic_tpRisco->OldValue = "";
?>
</select>
</span>
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->ic_tpRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_tpRisco->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_tpRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_tpRisco->FormValue) ?>">
<input type="hidden" data-field="x_ic_tpRisco" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_tpRisco->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_probabilidade->Visible) { // nu_probabilidade ?>
		<td<?php echo $riscoprojeto->nu_probabilidade->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_probabilidade" class="control-group riscoprojeto_nu_probabilidade">
<select data-field="x_nu_probabilidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade"<?php echo $riscoprojeto->nu_probabilidade->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_probabilidade->EditValue)) {
	$arwrk = $riscoprojeto->nu_probabilidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_probabilidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_probabilidade->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_probabilidade"].Options = <?php echo (is_array($riscoprojeto->nu_probabilidade->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_probabilidade->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_probabilidade" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_probabilidade->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_probabilidade" class="control-group riscoprojeto_nu_probabilidade">
<select data-field="x_nu_probabilidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade"<?php echo $riscoprojeto->nu_probabilidade->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_probabilidade->EditValue)) {
	$arwrk = $riscoprojeto->nu_probabilidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_probabilidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_probabilidade->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_probabilidade"].Options = <?php echo (is_array($riscoprojeto->nu_probabilidade->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_probabilidade->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->nu_probabilidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_probabilidade->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_probabilidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_probabilidade->FormValue) ?>">
<input type="hidden" data-field="x_nu_probabilidade" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_probabilidade->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_impacto->Visible) { // nu_impacto ?>
		<td<?php echo $riscoprojeto->nu_impacto->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_impacto" class="control-group riscoprojeto_nu_impacto">
<select data-field="x_nu_impacto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto"<?php echo $riscoprojeto->nu_impacto->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_impacto->EditValue)) {
	$arwrk = $riscoprojeto->nu_impacto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_impacto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_impacto->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_impacto"].Options = <?php echo (is_array($riscoprojeto->nu_impacto->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_impacto->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_impacto" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_impacto->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_impacto" class="control-group riscoprojeto_nu_impacto">
<select data-field="x_nu_impacto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto"<?php echo $riscoprojeto->nu_impacto->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_impacto->EditValue)) {
	$arwrk = $riscoprojeto->nu_impacto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_impacto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_impacto->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_impacto"].Options = <?php echo (is_array($riscoprojeto->nu_impacto->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_impacto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->nu_impacto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_impacto->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_impacto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_impacto->FormValue) ?>">
<input type="hidden" data-field="x_nu_impacto" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_impacto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_severidade->Visible) { // nu_severidade ?>
		<td<?php echo $riscoprojeto->nu_severidade->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_severidade" class="control-group riscoprojeto_nu_severidade">
<input type="text" data-field="x_nu_severidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" size="30" placeholder="<?php echo $riscoprojeto->nu_severidade->PlaceHolder ?>" value="<?php echo $riscoprojeto->nu_severidade->EditValue ?>"<?php echo $riscoprojeto->nu_severidade->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_nu_severidade" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_severidade->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_severidade" class="control-group riscoprojeto_nu_severidade">
<input type="text" data-field="x_nu_severidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" size="30" placeholder="<?php echo $riscoprojeto->nu_severidade->PlaceHolder ?>" value="<?php echo $riscoprojeto->nu_severidade->EditValue ?>"<?php echo $riscoprojeto->nu_severidade->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->nu_severidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_severidade->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_severidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_severidade->FormValue) ?>">
<input type="hidden" data-field="x_nu_severidade" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_severidade->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_acao->Visible) { // nu_acao ?>
		<td<?php echo $riscoprojeto->nu_acao->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_acao" class="control-group riscoprojeto_nu_acao">
<select data-field="x_nu_acao" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao"<?php echo $riscoprojeto->nu_acao->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_acao->EditValue)) {
	$arwrk = $riscoprojeto->nu_acao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_acao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_acao->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_acao"].Options = <?php echo (is_array($riscoprojeto->nu_acao->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_acao->EditValue, 1) : "[]" ?>;
</script>
</span>
<input type="hidden" data-field="x_nu_acao" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_acao->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_nu_acao" class="control-group riscoprojeto_nu_acao">
<select data-field="x_nu_acao" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao"<?php echo $riscoprojeto->nu_acao->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_acao->EditValue)) {
	$arwrk = $riscoprojeto->nu_acao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_acao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_acao->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_acao"].Options = <?php echo (is_array($riscoprojeto->nu_acao->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_acao->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->nu_acao->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_acao->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_acao" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_acao->FormValue) ?>">
<input type="hidden" data-field="x_nu_acao" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_acao->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
		<td<?php echo $riscoprojeto->nu_usuarioResp->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_nu_usuarioResp" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_usuarioResp->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_usuarioResp->ListViewValue() ?></span>
<input type="hidden" data-field="x_nu_usuarioResp" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_usuarioResp->FormValue) ?>">
<input type="hidden" data-field="x_nu_usuarioResp" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_usuarioResp->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->ic_stRisco->Visible) { // ic_stRisco ?>
		<td<?php echo $riscoprojeto->ic_stRisco->CellAttributes() ?>>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_ic_stRisco" class="control-group riscoprojeto_ic_stRisco">
<div id="tp_x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" value="{value}"<?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $riscoprojeto->ic_stRisco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_stRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->ic_stRisco->OldValue = "";
?>
</div>
</span>
<input type="hidden" data-field="x_ic_stRisco" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_stRisco->OldValue) ?>">
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $riscoprojeto_grid->RowCnt ?>_riscoprojeto_ic_stRisco" class="control-group riscoprojeto_ic_stRisco">
<div id="tp_x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" value="{value}"<?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $riscoprojeto->ic_stRisco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_stRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->ic_stRisco->OldValue = "";
?>
</div>
</span>
<?php } ?>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $riscoprojeto->ic_stRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_stRisco->ListViewValue() ?></span>
<input type="hidden" data-field="x_ic_stRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_stRisco->FormValue) ?>">
<input type="hidden" data-field="x_ic_stRisco" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_stRisco->OldValue) ?>">
<?php } ?>
<a id="<?php echo $riscoprojeto_grid->PageObjName . "_row_" . $riscoprojeto_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$riscoprojeto_grid->ListOptions->Render("body", "right", $riscoprojeto_grid->RowCnt);
?>
	</tr>
<?php if ($riscoprojeto->RowType == EW_ROWTYPE_ADD || $riscoprojeto->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
friscoprojetogrid.UpdateOpts(<?php echo $riscoprojeto_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($riscoprojeto->CurrentAction <> "gridadd" || $riscoprojeto->CurrentMode == "copy")
		if (!$riscoprojeto_grid->Recordset->EOF) $riscoprojeto_grid->Recordset->MoveNext();
}
?>
<?php
	if ($riscoprojeto->CurrentMode == "add" || $riscoprojeto->CurrentMode == "copy" || $riscoprojeto->CurrentMode == "edit") {
		$riscoprojeto_grid->RowIndex = '$rowindex$';
		$riscoprojeto_grid->LoadDefaultValues();

		// Set row properties
		$riscoprojeto->ResetAttrs();
		$riscoprojeto->RowAttrs = array_merge($riscoprojeto->RowAttrs, array('data-rowindex'=>$riscoprojeto_grid->RowIndex, 'id'=>'r0_riscoprojeto', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($riscoprojeto->RowAttrs["class"], "ewTemplate");
		$riscoprojeto->RowType = EW_ROWTYPE_ADD;

		// Render row
		$riscoprojeto_grid->RenderRow();

		// Render list options
		$riscoprojeto_grid->RenderListOptions();
		$riscoprojeto_grid->StartRowCnt = 0;
?>
	<tr<?php echo $riscoprojeto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$riscoprojeto_grid->ListOptions->Render("body", "left", $riscoprojeto_grid->RowIndex);
?>
	<?php if ($riscoprojeto->nu_riscoProjeto->Visible) { // nu_riscoProjeto ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $riscoprojeto->nu_riscoProjeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_riscoProjeto->ViewValue ?></span>
<input type="hidden" data-field="x_nu_riscoProjeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_riscoProjeto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_riscoProjeto" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_riscoProjeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_riscoProjeto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_projeto->Visible) { // nu_projeto ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<?php if ($riscoprojeto->nu_projeto->getSessionValue() <> "") { ?>
<span<?php echo $riscoprojeto->nu_projeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_projeto->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_projeto->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_nu_projeto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto"<?php echo $riscoprojeto->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_projeto->EditValue)) {
	$arwrk = $riscoprojeto->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_projeto->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_projeto"].Options = <?php echo (is_array($riscoprojeto->nu_projeto->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $riscoprojeto->nu_projeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_projeto->ViewValue ?></span>
<input type="hidden" data-field="x_nu_projeto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_projeto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_projeto" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_projeto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_projeto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_catRisco->Visible) { // nu_catRisco ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<select data-field="x_nu_catRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco"<?php echo $riscoprojeto->nu_catRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_catRisco->EditValue)) {
	$arwrk = $riscoprojeto->nu_catRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_catRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_catRisco->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_catRisco"].Options = <?php echo (is_array($riscoprojeto->nu_catRisco->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_catRisco->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $riscoprojeto->nu_catRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_catRisco->ViewValue ?></span>
<input type="hidden" data-field="x_nu_catRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_catRisco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_catRisco" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_catRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_catRisco->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($riscoprojeto->ic_tpRisco->Visible) { // ic_tpRisco ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<?php $riscoprojeto->ic_tpRisco->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x" . $riscoprojeto_grid->RowIndex . "_nu_acao']); " . @$riscoprojeto->ic_tpRisco->EditAttrs["onchange"]; ?>
<select data-field="x_ic_tpRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco"<?php echo $riscoprojeto->ic_tpRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->ic_tpRisco->EditValue)) {
	$arwrk = $riscoprojeto->ic_tpRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_tpRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->ic_tpRisco->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $riscoprojeto->ic_tpRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_tpRisco->ViewValue ?></span>
<input type="hidden" data-field="x_ic_tpRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_tpRisco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_tpRisco" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_tpRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_tpRisco->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_probabilidade->Visible) { // nu_probabilidade ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<select data-field="x_nu_probabilidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade"<?php echo $riscoprojeto->nu_probabilidade->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_probabilidade->EditValue)) {
	$arwrk = $riscoprojeto->nu_probabilidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_probabilidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_probabilidade->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_probabilidade"].Options = <?php echo (is_array($riscoprojeto->nu_probabilidade->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_probabilidade->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $riscoprojeto->nu_probabilidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_probabilidade->ViewValue ?></span>
<input type="hidden" data-field="x_nu_probabilidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_probabilidade->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_probabilidade" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_probabilidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_probabilidade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_impacto->Visible) { // nu_impacto ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<select data-field="x_nu_impacto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto"<?php echo $riscoprojeto->nu_impacto->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_impacto->EditValue)) {
	$arwrk = $riscoprojeto->nu_impacto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_impacto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_impacto->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_impacto"].Options = <?php echo (is_array($riscoprojeto->nu_impacto->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_impacto->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $riscoprojeto->nu_impacto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_impacto->ViewValue ?></span>
<input type="hidden" data-field="x_nu_impacto" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_impacto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_impacto" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_impacto" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_impacto->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_severidade->Visible) { // nu_severidade ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<input type="text" data-field="x_nu_severidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" size="30" placeholder="<?php echo $riscoprojeto->nu_severidade->PlaceHolder ?>" value="<?php echo $riscoprojeto->nu_severidade->EditValue ?>"<?php echo $riscoprojeto->nu_severidade->EditAttributes() ?>>
<?php } else { ?>
<span<?php echo $riscoprojeto->nu_severidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_severidade->ViewValue ?></span>
<input type="hidden" data-field="x_nu_severidade" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_severidade->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_severidade" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_severidade" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_severidade->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_acao->Visible) { // nu_acao ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<select data-field="x_nu_acao" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao"<?php echo $riscoprojeto->nu_acao->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_acao->EditValue)) {
	$arwrk = $riscoprojeto->nu_acao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_acao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->nu_acao->OldValue = "";
?>
</select>
<script type="text/javascript">
friscoprojetogrid.Lists["x_nu_acao"].Options = <?php echo (is_array($riscoprojeto->nu_acao->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_acao->EditValue, 1) : "[]" ?>;
</script>
<?php } else { ?>
<span<?php echo $riscoprojeto->nu_acao->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_acao->ViewValue ?></span>
<input type="hidden" data-field="x_nu_acao" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_acao->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_acao" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_acao" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_acao->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $riscoprojeto->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_usuarioResp->ViewValue ?></span>
<input type="hidden" data-field="x_nu_usuarioResp" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_usuarioResp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_nu_usuarioResp" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_nu_usuarioResp" value="<?php echo ew_HtmlEncode($riscoprojeto->nu_usuarioResp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($riscoprojeto->ic_stRisco->Visible) { // ic_stRisco ?>
		<td>
<?php if ($riscoprojeto->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" value="{value}"<?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $riscoprojeto->ic_stRisco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_stRisco->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $riscoprojeto->ic_stRisco->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $riscoprojeto->ic_stRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_stRisco->ViewValue ?></span>
<input type="hidden" data-field="x_ic_stRisco" name="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="x<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_stRisco->FormValue) ?>">
<?php } ?>
<input type="hidden" data-field="x_ic_stRisco" name="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" id="o<?php echo $riscoprojeto_grid->RowIndex ?>_ic_stRisco" value="<?php echo ew_HtmlEncode($riscoprojeto->ic_stRisco->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$riscoprojeto_grid->ListOptions->Render("body", "right", $riscoprojeto_grid->RowCnt);
?>
<script type="text/javascript">
friscoprojetogrid.UpdateOpts(<?php echo $riscoprojeto_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($riscoprojeto->CurrentMode == "add" || $riscoprojeto->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $riscoprojeto_grid->FormKeyCountName ?>" id="<?php echo $riscoprojeto_grid->FormKeyCountName ?>" value="<?php echo $riscoprojeto_grid->KeyCount ?>">
<?php echo $riscoprojeto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($riscoprojeto->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $riscoprojeto_grid->FormKeyCountName ?>" id="<?php echo $riscoprojeto_grid->FormKeyCountName ?>" value="<?php echo $riscoprojeto_grid->KeyCount ?>">
<?php echo $riscoprojeto_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($riscoprojeto->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="friscoprojetogrid">
</div>
<?php

// Close recordset
if ($riscoprojeto_grid->Recordset)
	$riscoprojeto_grid->Recordset->Close();
?>
<?php if ($riscoprojeto_grid->ShowOtherOptions) { ?>
<div class="ewGridLowerPanel ewListOtherOptions">
<?php
	foreach ($riscoprojeto_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<?php } ?>
</div>
</td></tr></table>
<?php if ($riscoprojeto->Export == "") { ?>
<script type="text/javascript">
friscoprojetogrid.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$riscoprojeto_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$riscoprojeto_grid->Page_Terminate();
?>
