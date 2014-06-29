<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "indicadorinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "indicadorversaoinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$indicador_delete = NULL; // Initialize page object first

class cindicador_delete extends cindicador {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'indicador';

	// Page object name
	var $PageObjName = 'indicador_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-error ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<table class=\"ewStdTable\"><tr><td><div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div></td></tr></table>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (indicador)
		if (!isset($GLOBALS["indicador"])) {
			$GLOBALS["indicador"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["indicador"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Table object (indicadorversao)
		if (!isset($GLOBALS['indicadorversao'])) $GLOBALS['indicadorversao'] = new cindicadorversao();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'indicador', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (IsPasswordExpired())
			$this->Page_Terminate("changepwd.php");
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("indicadorlist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("indicadorlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in indicador class, indicadorinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->nu_indicador->setDbValue($rs->fields('nu_indicador'));
		$this->no_indicador->setDbValue($rs->fields('no_indicador'));
		$this->ds_indicador->setDbValue($rs->fields('ds_indicador'));
		$this->ic_tpIndicador->setDbValue($rs->fields('ic_tpIndicador'));
		$this->nu_processoCobit5->setDbValue($rs->fields('nu_processoCobit5'));
		if (array_key_exists('EV__nu_processoCobit5', $rs->fields)) {
			$this->nu_processoCobit5->VirtualValue = $rs->fields('EV__nu_processoCobit5'); // Set up virtual field value
		} else {
			$this->nu_processoCobit5->VirtualValue = ""; // Clear value
		}
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_indicador->DbValue = $row['nu_indicador'];
		$this->no_indicador->DbValue = $row['no_indicador'];
		$this->ds_indicador->DbValue = $row['ds_indicador'];
		$this->ic_tpIndicador->DbValue = $row['ic_tpIndicador'];
		$this->nu_processoCobit5->DbValue = $row['nu_processoCobit5'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_indicador

		$this->nu_indicador->CellCssStyle = "white-space: nowrap;";

		// no_indicador
		// ds_indicador
		// ic_tpIndicador
		// nu_processoCobit5
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_indicador
			$this->nu_indicador->ViewValue = $this->nu_indicador->CurrentValue;
			$this->nu_indicador->ViewCustomAttributes = "";

			// no_indicador
			$this->no_indicador->ViewValue = $this->no_indicador->CurrentValue;
			$this->no_indicador->ViewCustomAttributes = "";

			// ds_indicador
			$this->ds_indicador->ViewValue = $this->ds_indicador->CurrentValue;
			$this->ds_indicador->ViewCustomAttributes = "";

			// ic_tpIndicador
			if (strval($this->ic_tpIndicador->CurrentValue) <> "") {
				switch ($this->ic_tpIndicador->CurrentValue) {
					case $this->ic_tpIndicador->FldTagValue(1):
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(1) <> "" ? $this->ic_tpIndicador->FldTagCaption(1) : $this->ic_tpIndicador->CurrentValue;
						break;
					case $this->ic_tpIndicador->FldTagValue(2):
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(2) <> "" ? $this->ic_tpIndicador->FldTagCaption(2) : $this->ic_tpIndicador->CurrentValue;
						break;
					case $this->ic_tpIndicador->FldTagValue(3):
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(3) <> "" ? $this->ic_tpIndicador->FldTagCaption(3) : $this->ic_tpIndicador->CurrentValue;
						break;
					case $this->ic_tpIndicador->FldTagValue(4):
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->FldTagCaption(4) <> "" ? $this->ic_tpIndicador->FldTagCaption(4) : $this->ic_tpIndicador->CurrentValue;
						break;
					default:
						$this->ic_tpIndicador->ViewValue = $this->ic_tpIndicador->CurrentValue;
				}
			} else {
				$this->ic_tpIndicador->ViewValue = NULL;
			}
			$this->ic_tpIndicador->ViewCustomAttributes = "";

			// nu_processoCobit5
			if ($this->nu_processoCobit5->VirtualValue <> "") {
				$this->nu_processoCobit5->ViewValue = $this->nu_processoCobit5->VirtualValue;
			} else {
			if (strval($this->nu_processoCobit5->CurrentValue) <> "") {
				$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processoCobit5->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_processo], [co_alternativo] AS [DispFld], [no_processo] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processocobit5]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_processoCobit5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [ic_dominio] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_processoCobit5->ViewValue = $rswrk->fields('DispFld');
					$this->nu_processoCobit5->ViewValue .= ew_ValueSeparator(1,$this->nu_processoCobit5) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_processoCobit5->ViewValue = $this->nu_processoCobit5->CurrentValue;
				}
			} else {
				$this->nu_processoCobit5->ViewValue = NULL;
			}
			}
			$this->nu_processoCobit5->ViewCustomAttributes = "";

			// ic_ativo
			if (strval($this->ic_ativo->CurrentValue) <> "") {
				switch ($this->ic_ativo->CurrentValue) {
					case $this->ic_ativo->FldTagValue(1):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->CurrentValue;
						break;
					case $this->ic_ativo->FldTagValue(2):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->CurrentValue;
						break;
					default:
						$this->ic_ativo->ViewValue = $this->ic_ativo->CurrentValue;
				}
			} else {
				$this->ic_ativo->ViewValue = NULL;
			}
			$this->ic_ativo->ViewCustomAttributes = "";

			// no_indicador
			$this->no_indicador->LinkCustomAttributes = "";
			$this->no_indicador->HrefValue = "";
			$this->no_indicador->TooltipValue = "";

			// ds_indicador
			$this->ds_indicador->LinkCustomAttributes = "";
			$this->ds_indicador->HrefValue = "";
			$this->ds_indicador->TooltipValue = "";

			// ic_tpIndicador
			$this->ic_tpIndicador->LinkCustomAttributes = "";
			$this->ic_tpIndicador->HrefValue = "";
			$this->ic_tpIndicador->TooltipValue = "";

			// nu_processoCobit5
			$this->nu_processoCobit5->LinkCustomAttributes = "";
			$this->nu_processoCobit5->HrefValue = "";
			$this->nu_processoCobit5->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_indicador'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "indicadorlist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($indicador_delete)) $indicador_delete = new cindicador_delete();

// Page init
$indicador_delete->Page_Init();

// Page main
$indicador_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$indicador_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var indicador_delete = new ew_Page("indicador_delete");
indicador_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = indicador_delete.PageID; // For backward compatibility

// Form object
var findicadordelete = new ew_Form("findicadordelete");

// Form_CustomValidate event
findicadordelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
findicadordelete.ValidateRequired = true;
<?php } else { ?>
findicadordelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
findicadordelete.Lists["x_nu_processoCobit5"] = {"LinkField":"x_nu_processo","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_processo","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($indicador_delete->Recordset = $indicador_delete->LoadRecordset())
	$indicador_deleteTotalRecs = $indicador_delete->Recordset->RecordCount(); // Get record count
if ($indicador_deleteTotalRecs <= 0) { // No record found, exit
	if ($indicador_delete->Recordset)
		$indicador_delete->Recordset->Close();
	$indicador_delete->Page_Terminate("indicadorlist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $indicador_delete->ShowPageHeader(); ?>
<?php
$indicador_delete->ShowMessage();
?>
<form name="findicadordelete" id="findicadordelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="indicador">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($indicador_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_indicadordelete" class="ewTable ewTableSeparate">
<?php echo $indicador->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_indicador_no_indicador" class="indicador_no_indicador"><?php echo $indicador->no_indicador->FldCaption() ?></span></td>
		<td><span id="elh_indicador_ds_indicador" class="indicador_ds_indicador"><?php echo $indicador->ds_indicador->FldCaption() ?></span></td>
		<td><span id="elh_indicador_ic_tpIndicador" class="indicador_ic_tpIndicador"><?php echo $indicador->ic_tpIndicador->FldCaption() ?></span></td>
		<td><span id="elh_indicador_nu_processoCobit5" class="indicador_nu_processoCobit5"><?php echo $indicador->nu_processoCobit5->FldCaption() ?></span></td>
		<td><span id="elh_indicador_ic_ativo" class="indicador_ic_ativo"><?php echo $indicador->ic_ativo->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$indicador_delete->RecCnt = 0;
$i = 0;
while (!$indicador_delete->Recordset->EOF) {
	$indicador_delete->RecCnt++;
	$indicador_delete->RowCnt++;

	// Set row properties
	$indicador->ResetAttrs();
	$indicador->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$indicador_delete->LoadRowValues($indicador_delete->Recordset);

	// Render row
	$indicador_delete->RenderRow();
?>
	<tr<?php echo $indicador->RowAttributes() ?>>
		<td<?php echo $indicador->no_indicador->CellAttributes() ?>>
<span id="el<?php echo $indicador_delete->RowCnt ?>_indicador_no_indicador" class="control-group indicador_no_indicador">
<span<?php echo $indicador->no_indicador->ViewAttributes() ?>>
<?php echo $indicador->no_indicador->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $indicador->ds_indicador->CellAttributes() ?>>
<span id="el<?php echo $indicador_delete->RowCnt ?>_indicador_ds_indicador" class="control-group indicador_ds_indicador">
<span<?php echo $indicador->ds_indicador->ViewAttributes() ?>>
<?php echo $indicador->ds_indicador->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $indicador->ic_tpIndicador->CellAttributes() ?>>
<span id="el<?php echo $indicador_delete->RowCnt ?>_indicador_ic_tpIndicador" class="control-group indicador_ic_tpIndicador">
<span<?php echo $indicador->ic_tpIndicador->ViewAttributes() ?>>
<?php echo $indicador->ic_tpIndicador->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $indicador->nu_processoCobit5->CellAttributes() ?>>
<span id="el<?php echo $indicador_delete->RowCnt ?>_indicador_nu_processoCobit5" class="control-group indicador_nu_processoCobit5">
<span<?php echo $indicador->nu_processoCobit5->ViewAttributes() ?>>
<?php echo $indicador->nu_processoCobit5->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $indicador->ic_ativo->CellAttributes() ?>>
<span id="el<?php echo $indicador_delete->RowCnt ?>_indicador_ic_ativo" class="control-group indicador_ic_ativo">
<span<?php echo $indicador->ic_ativo->ViewAttributes() ?>>
<?php echo $indicador->ic_ativo->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$indicador_delete->Recordset->MoveNext();
}
$indicador_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
findicadordelete.Init();
</script>
<?php
$indicador_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$indicador_delete->Page_Terminate();
?>
