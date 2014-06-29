<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "indicadorversaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "indicadorvalorinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$indicadorversao_delete = NULL; // Initialize page object first

class cindicadorversao_delete extends cindicadorversao {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'indicadorversao';

	// Page object name
	var $PageObjName = 'indicadorversao_delete';

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

		// Table object (indicadorversao)
		if (!isset($GLOBALS["indicadorversao"])) {
			$GLOBALS["indicadorversao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["indicadorversao"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Table object (indicadorvalor)
		if (!isset($GLOBALS['indicadorvalor'])) $GLOBALS['indicadorvalor'] = new cindicadorvalor();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'indicadorversao', TRUE);

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
			$this->Page_Terminate("indicadorversaolist.php");
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
			$this->Page_Terminate("indicadorversaolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in indicadorversao class, indicadorversaoinfo.php

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
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->ic_periodicidadeGeracao->setDbValue($rs->fields('ic_periodicidadeGeracao'));
		$this->ds_origemIndicador->setDbValue($rs->fields('ds_origemIndicador'));
		$this->ic_reponsavelColetaCtrl->setDbValue($rs->fields('ic_reponsavelColetaCtrl'));
		$this->ds_codigoSql->setDbValue($rs->fields('ds_codigoSql'));
		$this->dh_versao->setDbValue($rs->fields('dh_versao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_indicador->DbValue = $row['nu_indicador'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->ic_periodicidadeGeracao->DbValue = $row['ic_periodicidadeGeracao'];
		$this->ds_origemIndicador->DbValue = $row['ds_origemIndicador'];
		$this->ic_reponsavelColetaCtrl->DbValue = $row['ic_reponsavelColetaCtrl'];
		$this->ds_codigoSql->DbValue = $row['ds_codigoSql'];
		$this->dh_versao->DbValue = $row['dh_versao'];
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
		// nu_versao
		// ic_periodicidadeGeracao
		// ds_origemIndicador
		// ic_reponsavelColetaCtrl
		// ds_codigoSql
		// dh_versao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_indicador
			if (strval($this->nu_indicador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_indicador]" . ew_SearchString("=", $this->nu_indicador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_indicador], [no_indicador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[indicador]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_indicador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_indicador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_indicador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_indicador->ViewValue = $this->nu_indicador->CurrentValue;
				}
			} else {
				$this->nu_indicador->ViewValue = NULL;
			}
			$this->nu_indicador->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// ic_periodicidadeGeracao
			if (strval($this->ic_periodicidadeGeracao->CurrentValue) <> "") {
				switch ($this->ic_periodicidadeGeracao->CurrentValue) {
					case $this->ic_periodicidadeGeracao->FldTagValue(1):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(1) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(1) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					case $this->ic_periodicidadeGeracao->FldTagValue(2):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(2) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(2) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					case $this->ic_periodicidadeGeracao->FldTagValue(3):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(3) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(3) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					case $this->ic_periodicidadeGeracao->FldTagValue(4):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(4) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(4) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					case $this->ic_periodicidadeGeracao->FldTagValue(5):
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->FldTagCaption(5) <> "" ? $this->ic_periodicidadeGeracao->FldTagCaption(5) : $this->ic_periodicidadeGeracao->CurrentValue;
						break;
					default:
						$this->ic_periodicidadeGeracao->ViewValue = $this->ic_periodicidadeGeracao->CurrentValue;
				}
			} else {
				$this->ic_periodicidadeGeracao->ViewValue = NULL;
			}
			$this->ic_periodicidadeGeracao->ViewCustomAttributes = "";

			// ic_reponsavelColetaCtrl
			if (strval($this->ic_reponsavelColetaCtrl->CurrentValue) <> "") {
				switch ($this->ic_reponsavelColetaCtrl->CurrentValue) {
					case $this->ic_reponsavelColetaCtrl->FldTagValue(1):
						$this->ic_reponsavelColetaCtrl->ViewValue = $this->ic_reponsavelColetaCtrl->FldTagCaption(1) <> "" ? $this->ic_reponsavelColetaCtrl->FldTagCaption(1) : $this->ic_reponsavelColetaCtrl->CurrentValue;
						break;
					case $this->ic_reponsavelColetaCtrl->FldTagValue(2):
						$this->ic_reponsavelColetaCtrl->ViewValue = $this->ic_reponsavelColetaCtrl->FldTagCaption(2) <> "" ? $this->ic_reponsavelColetaCtrl->FldTagCaption(2) : $this->ic_reponsavelColetaCtrl->CurrentValue;
						break;
					default:
						$this->ic_reponsavelColetaCtrl->ViewValue = $this->ic_reponsavelColetaCtrl->CurrentValue;
				}
			} else {
				$this->ic_reponsavelColetaCtrl->ViewValue = NULL;
			}
			$this->ic_reponsavelColetaCtrl->ViewCustomAttributes = "";

			// dh_versao
			$this->dh_versao->ViewValue = $this->dh_versao->CurrentValue;
			$this->dh_versao->ViewValue = ew_FormatDateTime($this->dh_versao->ViewValue, 11);
			$this->dh_versao->ViewCustomAttributes = "";

			// nu_indicador
			$this->nu_indicador->LinkCustomAttributes = "";
			$this->nu_indicador->HrefValue = "";
			$this->nu_indicador->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// ic_periodicidadeGeracao
			$this->ic_periodicidadeGeracao->LinkCustomAttributes = "";
			$this->ic_periodicidadeGeracao->HrefValue = "";
			$this->ic_periodicidadeGeracao->TooltipValue = "";

			// ic_reponsavelColetaCtrl
			$this->ic_reponsavelColetaCtrl->LinkCustomAttributes = "";
			$this->ic_reponsavelColetaCtrl->HrefValue = "";
			$this->ic_reponsavelColetaCtrl->TooltipValue = "";

			// dh_versao
			$this->dh_versao->LinkCustomAttributes = "";
			$this->dh_versao->HrefValue = "";
			$this->dh_versao->TooltipValue = "";
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
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_versao'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "indicadorversaolist.php", $this->TableVar);
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
if (!isset($indicadorversao_delete)) $indicadorversao_delete = new cindicadorversao_delete();

// Page init
$indicadorversao_delete->Page_Init();

// Page main
$indicadorversao_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$indicadorversao_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var indicadorversao_delete = new ew_Page("indicadorversao_delete");
indicadorversao_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = indicadorversao_delete.PageID; // For backward compatibility

// Form object
var findicadorversaodelete = new ew_Form("findicadorversaodelete");

// Form_CustomValidate event
findicadorversaodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
findicadorversaodelete.ValidateRequired = true;
<?php } else { ?>
findicadorversaodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
findicadorversaodelete.Lists["x_nu_indicador"] = {"LinkField":"x_nu_indicador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_indicador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($indicadorversao_delete->Recordset = $indicadorversao_delete->LoadRecordset())
	$indicadorversao_deleteTotalRecs = $indicadorversao_delete->Recordset->RecordCount(); // Get record count
if ($indicadorversao_deleteTotalRecs <= 0) { // No record found, exit
	if ($indicadorversao_delete->Recordset)
		$indicadorversao_delete->Recordset->Close();
	$indicadorversao_delete->Page_Terminate("indicadorversaolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $indicadorversao_delete->ShowPageHeader(); ?>
<?php
$indicadorversao_delete->ShowMessage();
?>
<form name="findicadorversaodelete" id="findicadorversaodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="indicadorversao">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($indicadorversao_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_indicadorversaodelete" class="ewTable ewTableSeparate">
<?php echo $indicadorversao->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_indicadorversao_nu_indicador" class="indicadorversao_nu_indicador"><?php echo $indicadorversao->nu_indicador->FldCaption() ?></span></td>
		<td><span id="elh_indicadorversao_nu_versao" class="indicadorversao_nu_versao"><?php echo $indicadorversao->nu_versao->FldCaption() ?></span></td>
		<td><span id="elh_indicadorversao_ic_periodicidadeGeracao" class="indicadorversao_ic_periodicidadeGeracao"><?php echo $indicadorversao->ic_periodicidadeGeracao->FldCaption() ?></span></td>
		<td><span id="elh_indicadorversao_ic_reponsavelColetaCtrl" class="indicadorversao_ic_reponsavelColetaCtrl"><?php echo $indicadorversao->ic_reponsavelColetaCtrl->FldCaption() ?></span></td>
		<td><span id="elh_indicadorversao_dh_versao" class="indicadorversao_dh_versao"><?php echo $indicadorversao->dh_versao->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$indicadorversao_delete->RecCnt = 0;
$i = 0;
while (!$indicadorversao_delete->Recordset->EOF) {
	$indicadorversao_delete->RecCnt++;
	$indicadorversao_delete->RowCnt++;

	// Set row properties
	$indicadorversao->ResetAttrs();
	$indicadorversao->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$indicadorversao_delete->LoadRowValues($indicadorversao_delete->Recordset);

	// Render row
	$indicadorversao_delete->RenderRow();
?>
	<tr<?php echo $indicadorversao->RowAttributes() ?>>
		<td<?php echo $indicadorversao->nu_indicador->CellAttributes() ?>>
<span id="el<?php echo $indicadorversao_delete->RowCnt ?>_indicadorversao_nu_indicador" class="control-group indicadorversao_nu_indicador">
<span<?php echo $indicadorversao->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_indicador->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $indicadorversao->nu_versao->CellAttributes() ?>>
<span id="el<?php echo $indicadorversao_delete->RowCnt ?>_indicadorversao_nu_versao" class="control-group indicadorversao_nu_versao">
<span<?php echo $indicadorversao->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->nu_versao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $indicadorversao->ic_periodicidadeGeracao->CellAttributes() ?>>
<span id="el<?php echo $indicadorversao_delete->RowCnt ?>_indicadorversao_ic_periodicidadeGeracao" class="control-group indicadorversao_ic_periodicidadeGeracao">
<span<?php echo $indicadorversao->ic_periodicidadeGeracao->ViewAttributes() ?>>
<?php echo $indicadorversao->ic_periodicidadeGeracao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $indicadorversao->ic_reponsavelColetaCtrl->CellAttributes() ?>>
<span id="el<?php echo $indicadorversao_delete->RowCnt ?>_indicadorversao_ic_reponsavelColetaCtrl" class="control-group indicadorversao_ic_reponsavelColetaCtrl">
<span<?php echo $indicadorversao->ic_reponsavelColetaCtrl->ViewAttributes() ?>>
<?php echo $indicadorversao->ic_reponsavelColetaCtrl->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $indicadorversao->dh_versao->CellAttributes() ?>>
<span id="el<?php echo $indicadorversao_delete->RowCnt ?>_indicadorversao_dh_versao" class="control-group indicadorversao_dh_versao">
<span<?php echo $indicadorversao->dh_versao->ViewAttributes() ?>>
<?php echo $indicadorversao->dh_versao->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$indicadorversao_delete->Recordset->MoveNext();
}
$indicadorversao_delete->Recordset->Close();
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
findicadorversaodelete.Init();
</script>
<?php
$indicadorversao_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$indicadorversao_delete->Page_Terminate();
?>
