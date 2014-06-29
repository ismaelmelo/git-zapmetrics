<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tpmanutencaoinfo.php" ?>
<?php include_once "tpcontageminfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tpmanutencao_delete = NULL; // Initialize page object first

class ctpmanutencao_delete extends ctpmanutencao {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'tpmanutencao';

	// Page object name
	var $PageObjName = 'tpmanutencao_delete';

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

		// Table object (tpmanutencao)
		if (!isset($GLOBALS["tpmanutencao"])) {
			$GLOBALS["tpmanutencao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpmanutencao"];
		}

		// Table object (tpcontagem)
		if (!isset($GLOBALS['tpcontagem'])) $GLOBALS['tpcontagem'] = new ctpcontagem();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpmanutencao', TRUE);

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

		// User profile
		$UserProfile = new cUserProfile();
		$UserProfile->LoadProfile(@$_SESSION[EW_SESSION_USER_PROFILE]);

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
			$this->Page_Terminate("tpmanutencaolist.php");
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
			$this->Page_Terminate("tpmanutencaolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tpmanutencao class, tpmanutencaoinfo.php

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
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->nu_tpContagem->setDbValue($rs->fields('nu_tpContagem'));
		$this->no_tpManutencao->setDbValue($rs->fields('no_tpManutencao'));
		$this->ic_modeloCalculo->setDbValue($rs->fields('ic_modeloCalculo'));
		$this->ic_utilizaFaseRoteiroCalculo->setDbValue($rs->fields('ic_utilizaFaseRoteiroCalculo'));
		$this->nu_parametro->setDbValue($rs->fields('nu_parametro'));
		$this->ds_helpTela->setDbValue($rs->fields('ds_helpTela'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_tpManutencao->DbValue = $row['nu_tpManutencao'];
		$this->nu_tpContagem->DbValue = $row['nu_tpContagem'];
		$this->no_tpManutencao->DbValue = $row['no_tpManutencao'];
		$this->ic_modeloCalculo->DbValue = $row['ic_modeloCalculo'];
		$this->ic_utilizaFaseRoteiroCalculo->DbValue = $row['ic_utilizaFaseRoteiroCalculo'];
		$this->nu_parametro->DbValue = $row['nu_parametro'];
		$this->ds_helpTela->DbValue = $row['ds_helpTela'];
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
		// nu_tpManutencao

		$this->nu_tpManutencao->CellCssStyle = "white-space: nowrap;";

		// nu_tpContagem
		// no_tpManutencao
		// ic_modeloCalculo
		// ic_utilizaFaseRoteiroCalculo
		// nu_parametro
		// ds_helpTela
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_tpManutencao
			$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
			$this->nu_tpManutencao->ViewCustomAttributes = "";

			// nu_tpContagem
			$this->nu_tpContagem->ViewValue = $this->nu_tpContagem->CurrentValue;
			$this->nu_tpContagem->ViewCustomAttributes = "";

			// no_tpManutencao
			$this->no_tpManutencao->ViewValue = $this->no_tpManutencao->CurrentValue;
			$this->no_tpManutencao->ViewCustomAttributes = "";

			// ic_modeloCalculo
			if (strval($this->ic_modeloCalculo->CurrentValue) <> "") {
				switch ($this->ic_modeloCalculo->CurrentValue) {
					case $this->ic_modeloCalculo->FldTagValue(1):
						$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->FldTagCaption(1) <> "" ? $this->ic_modeloCalculo->FldTagCaption(1) : $this->ic_modeloCalculo->CurrentValue;
						break;
					case $this->ic_modeloCalculo->FldTagValue(2):
						$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->FldTagCaption(2) <> "" ? $this->ic_modeloCalculo->FldTagCaption(2) : $this->ic_modeloCalculo->CurrentValue;
						break;
					case $this->ic_modeloCalculo->FldTagValue(3):
						$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->FldTagCaption(3) <> "" ? $this->ic_modeloCalculo->FldTagCaption(3) : $this->ic_modeloCalculo->CurrentValue;
						break;
					default:
						$this->ic_modeloCalculo->ViewValue = $this->ic_modeloCalculo->CurrentValue;
				}
			} else {
				$this->ic_modeloCalculo->ViewValue = NULL;
			}
			$this->ic_modeloCalculo->ViewCustomAttributes = "";

			// ic_utilizaFaseRoteiroCalculo
			if (strval($this->ic_utilizaFaseRoteiroCalculo->CurrentValue) <> "") {
				switch ($this->ic_utilizaFaseRoteiroCalculo->CurrentValue) {
					case $this->ic_utilizaFaseRoteiroCalculo->FldTagValue(1):
						$this->ic_utilizaFaseRoteiroCalculo->ViewValue = $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(1) <> "" ? $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(1) : $this->ic_utilizaFaseRoteiroCalculo->CurrentValue;
						break;
					case $this->ic_utilizaFaseRoteiroCalculo->FldTagValue(2):
						$this->ic_utilizaFaseRoteiroCalculo->ViewValue = $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(2) <> "" ? $this->ic_utilizaFaseRoteiroCalculo->FldTagCaption(2) : $this->ic_utilizaFaseRoteiroCalculo->CurrentValue;
						break;
					default:
						$this->ic_utilizaFaseRoteiroCalculo->ViewValue = $this->ic_utilizaFaseRoteiroCalculo->CurrentValue;
				}
			} else {
				$this->ic_utilizaFaseRoteiroCalculo->ViewValue = NULL;
			}
			$this->ic_utilizaFaseRoteiroCalculo->ViewCustomAttributes = "";

			// nu_parametro
			if (strval($this->nu_parametro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_parSisp]" . ew_SearchString("=", $this->nu_parametro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_parSisp], [no_parSisp] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[parSisp]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_parametro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_parSisp] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_parametro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_parametro->ViewValue = $this->nu_parametro->CurrentValue;
				}
			} else {
				$this->nu_parametro->ViewValue = NULL;
			}
			$this->nu_parametro->ViewCustomAttributes = "";

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

			// no_tpManutencao
			$this->no_tpManutencao->LinkCustomAttributes = "";
			$this->no_tpManutencao->HrefValue = "";
			$this->no_tpManutencao->TooltipValue = "";

			// ic_modeloCalculo
			$this->ic_modeloCalculo->LinkCustomAttributes = "";
			$this->ic_modeloCalculo->HrefValue = "";
			$this->ic_modeloCalculo->TooltipValue = "";

			// ic_utilizaFaseRoteiroCalculo
			$this->ic_utilizaFaseRoteiroCalculo->LinkCustomAttributes = "";
			$this->ic_utilizaFaseRoteiroCalculo->HrefValue = "";
			$this->ic_utilizaFaseRoteiroCalculo->TooltipValue = "";

			// nu_parametro
			$this->nu_parametro->LinkCustomAttributes = "";
			$this->nu_parametro->HrefValue = "";
			$this->nu_parametro->TooltipValue = "";

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
				$sThisKey .= $row['nu_tpManutencao'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tpmanutencaolist.php", $this->TableVar);
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
if (!isset($tpmanutencao_delete)) $tpmanutencao_delete = new ctpmanutencao_delete();

// Page init
$tpmanutencao_delete->Page_Init();

// Page main
$tpmanutencao_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpmanutencao_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tpmanutencao_delete = new ew_Page("tpmanutencao_delete");
tpmanutencao_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = tpmanutencao_delete.PageID; // For backward compatibility

// Form object
var ftpmanutencaodelete = new ew_Form("ftpmanutencaodelete");

// Form_CustomValidate event
ftpmanutencaodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpmanutencaodelete.ValidateRequired = true;
<?php } else { ?>
ftpmanutencaodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftpmanutencaodelete.Lists["x_nu_parametro"] = {"LinkField":"x_nu_parSisp","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_parSisp","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($tpmanutencao_delete->Recordset = $tpmanutencao_delete->LoadRecordset())
	$tpmanutencao_deleteTotalRecs = $tpmanutencao_delete->Recordset->RecordCount(); // Get record count
if ($tpmanutencao_deleteTotalRecs <= 0) { // No record found, exit
	if ($tpmanutencao_delete->Recordset)
		$tpmanutencao_delete->Recordset->Close();
	$tpmanutencao_delete->Page_Terminate("tpmanutencaolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $tpmanutencao_delete->ShowPageHeader(); ?>
<?php
$tpmanutencao_delete->ShowMessage();
?>
<form name="ftpmanutencaodelete" id="ftpmanutencaodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tpmanutencao">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tpmanutencao_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tpmanutencaodelete" class="ewTable ewTableSeparate">
<?php echo $tpmanutencao->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_tpmanutencao_no_tpManutencao" class="tpmanutencao_no_tpManutencao"><?php echo $tpmanutencao->no_tpManutencao->FldCaption() ?></span></td>
		<td><span id="elh_tpmanutencao_ic_modeloCalculo" class="tpmanutencao_ic_modeloCalculo"><?php echo $tpmanutencao->ic_modeloCalculo->FldCaption() ?></span></td>
		<td><span id="elh_tpmanutencao_ic_utilizaFaseRoteiroCalculo" class="tpmanutencao_ic_utilizaFaseRoteiroCalculo"><?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->FldCaption() ?></span></td>
		<td><span id="elh_tpmanutencao_nu_parametro" class="tpmanutencao_nu_parametro"><?php echo $tpmanutencao->nu_parametro->FldCaption() ?></span></td>
		<td><span id="elh_tpmanutencao_ic_ativo" class="tpmanutencao_ic_ativo"><?php echo $tpmanutencao->ic_ativo->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$tpmanutencao_delete->RecCnt = 0;
$i = 0;
while (!$tpmanutencao_delete->Recordset->EOF) {
	$tpmanutencao_delete->RecCnt++;
	$tpmanutencao_delete->RowCnt++;

	// Set row properties
	$tpmanutencao->ResetAttrs();
	$tpmanutencao->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tpmanutencao_delete->LoadRowValues($tpmanutencao_delete->Recordset);

	// Render row
	$tpmanutencao_delete->RenderRow();
?>
	<tr<?php echo $tpmanutencao->RowAttributes() ?>>
		<td<?php echo $tpmanutencao->no_tpManutencao->CellAttributes() ?>>
<span id="el<?php echo $tpmanutencao_delete->RowCnt ?>_tpmanutencao_no_tpManutencao" class="control-group tpmanutencao_no_tpManutencao">
<span<?php echo $tpmanutencao->no_tpManutencao->ViewAttributes() ?>>
<?php echo $tpmanutencao->no_tpManutencao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmanutencao->ic_modeloCalculo->CellAttributes() ?>>
<span id="el<?php echo $tpmanutencao_delete->RowCnt ?>_tpmanutencao_ic_modeloCalculo" class="control-group tpmanutencao_ic_modeloCalculo">
<span<?php echo $tpmanutencao->ic_modeloCalculo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_modeloCalculo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->CellAttributes() ?>>
<span id="el<?php echo $tpmanutencao_delete->RowCnt ?>_tpmanutencao_ic_utilizaFaseRoteiroCalculo" class="control-group tpmanutencao_ic_utilizaFaseRoteiroCalculo">
<span<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_utilizaFaseRoteiroCalculo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmanutencao->nu_parametro->CellAttributes() ?>>
<span id="el<?php echo $tpmanutencao_delete->RowCnt ?>_tpmanutencao_nu_parametro" class="control-group tpmanutencao_nu_parametro">
<span<?php echo $tpmanutencao->nu_parametro->ViewAttributes() ?>>
<?php echo $tpmanutencao->nu_parametro->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpmanutencao->ic_ativo->CellAttributes() ?>>
<span id="el<?php echo $tpmanutencao_delete->RowCnt ?>_tpmanutencao_ic_ativo" class="control-group tpmanutencao_ic_ativo">
<span<?php echo $tpmanutencao->ic_ativo->ViewAttributes() ?>>
<?php echo $tpmanutencao->ic_ativo->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$tpmanutencao_delete->Recordset->MoveNext();
}
$tpmanutencao_delete->Recordset->Close();
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
ftpmanutencaodelete.Init();
</script>
<?php
$tpmanutencao_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpmanutencao_delete->Page_Terminate();
?>
