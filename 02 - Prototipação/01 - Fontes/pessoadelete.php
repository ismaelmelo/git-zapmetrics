<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "pessoainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$pessoa_delete = NULL; // Initialize page object first

class cpessoa_delete extends cpessoa {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'pessoa';

	// Page object name
	var $PageObjName = 'pessoa_delete';

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

		// Table object (pessoa)
		if (!isset($GLOBALS["pessoa"])) {
			$GLOBALS["pessoa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pessoa"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pessoa', TRUE);

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
			$this->Page_Terminate("pessoalist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_pessoa->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("pessoalist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in pessoa class, pessoainfo.php

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
		$this->nu_pessoa->setDbValue($rs->fields('nu_pessoa'));
		$this->no_pessoa->setDbValue($rs->fields('no_pessoa'));
		$this->ic_tpEnvolvimento->setDbValue($rs->fields('ic_tpEnvolvimento'));
		$this->nu_cargo->setDbValue($rs->fields('nu_cargo'));
		$this->nu_areaLotacao->setDbValue($rs->fields('nu_areaLotacao'));
		$this->no_email->setDbValue($rs->fields('no_email'));
		$this->ds_telefone1->setDbValue($rs->fields('ds_telefone1'));
		$this->ds_telefone2->setDbValue($rs->fields('ds_telefone2'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_pessoa->DbValue = $row['nu_pessoa'];
		$this->no_pessoa->DbValue = $row['no_pessoa'];
		$this->ic_tpEnvolvimento->DbValue = $row['ic_tpEnvolvimento'];
		$this->nu_cargo->DbValue = $row['nu_cargo'];
		$this->nu_areaLotacao->DbValue = $row['nu_areaLotacao'];
		$this->no_email->DbValue = $row['no_email'];
		$this->ds_telefone1->DbValue = $row['ds_telefone1'];
		$this->ds_telefone2->DbValue = $row['ds_telefone2'];
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
		// nu_pessoa
		// no_pessoa
		// ic_tpEnvolvimento
		// nu_cargo
		// nu_areaLotacao
		// no_email
		// ds_telefone1
		// ds_telefone2
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_pessoa
			$this->nu_pessoa->ViewValue = $this->nu_pessoa->CurrentValue;
			$this->nu_pessoa->ViewCustomAttributes = "";

			// no_pessoa
			$this->no_pessoa->ViewValue = $this->no_pessoa->CurrentValue;
			$this->no_pessoa->ViewCustomAttributes = "";

			// ic_tpEnvolvimento
			if (strval($this->ic_tpEnvolvimento->CurrentValue) <> "") {
				switch ($this->ic_tpEnvolvimento->CurrentValue) {
					case $this->ic_tpEnvolvimento->FldTagValue(1):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(1) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(1) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					case $this->ic_tpEnvolvimento->FldTagValue(2):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(2) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(2) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					case $this->ic_tpEnvolvimento->FldTagValue(3):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(3) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(3) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					case $this->ic_tpEnvolvimento->FldTagValue(4):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(4) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(4) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					default:
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->CurrentValue;
				}
			} else {
				$this->ic_tpEnvolvimento->ViewValue = NULL;
			}
			$this->ic_tpEnvolvimento->ViewCustomAttributes = "";

			// nu_cargo
			if (strval($this->nu_cargo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_cargo]" . ew_SearchString("=", $this->nu_cargo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_cargo], [no_cargo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[cargo]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_cargo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_cargo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_cargo->ViewValue = $this->nu_cargo->CurrentValue;
				}
			} else {
				$this->nu_cargo->ViewValue = NULL;
			}
			$this->nu_cargo->ViewCustomAttributes = "";

			// nu_areaLotacao
			if (strval($this->nu_areaLotacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaLotacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaLotacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaLotacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaLotacao->ViewValue = $this->nu_areaLotacao->CurrentValue;
				}
			} else {
				$this->nu_areaLotacao->ViewValue = NULL;
			}
			$this->nu_areaLotacao->ViewCustomAttributes = "";

			// no_email
			$this->no_email->ViewValue = $this->no_email->CurrentValue;
			$this->no_email->ViewCustomAttributes = "";

			// ds_telefone1
			$this->ds_telefone1->ViewValue = $this->ds_telefone1->CurrentValue;
			$this->ds_telefone1->ViewCustomAttributes = "";

			// ds_telefone2
			$this->ds_telefone2->ViewValue = $this->ds_telefone2->CurrentValue;
			$this->ds_telefone2->ViewCustomAttributes = "";

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

			// nu_pessoa
			$this->nu_pessoa->LinkCustomAttributes = "";
			$this->nu_pessoa->HrefValue = "";
			$this->nu_pessoa->TooltipValue = "";

			// no_pessoa
			$this->no_pessoa->LinkCustomAttributes = "";
			$this->no_pessoa->HrefValue = "";
			$this->no_pessoa->TooltipValue = "";

			// ic_tpEnvolvimento
			$this->ic_tpEnvolvimento->LinkCustomAttributes = "";
			$this->ic_tpEnvolvimento->HrefValue = "";
			$this->ic_tpEnvolvimento->TooltipValue = "";

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
				$sThisKey .= $row['nu_pessoa'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "pessoalist.php", $this->TableVar);
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
if (!isset($pessoa_delete)) $pessoa_delete = new cpessoa_delete();

// Page init
$pessoa_delete->Page_Init();

// Page main
$pessoa_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pessoa_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var pessoa_delete = new ew_Page("pessoa_delete");
pessoa_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = pessoa_delete.PageID; // For backward compatibility

// Form object
var fpessoadelete = new ew_Form("fpessoadelete");

// Form_CustomValidate event
fpessoadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpessoadelete.ValidateRequired = true;
<?php } else { ?>
fpessoadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($pessoa_delete->Recordset = $pessoa_delete->LoadRecordset())
	$pessoa_deleteTotalRecs = $pessoa_delete->Recordset->RecordCount(); // Get record count
if ($pessoa_deleteTotalRecs <= 0) { // No record found, exit
	if ($pessoa_delete->Recordset)
		$pessoa_delete->Recordset->Close();
	$pessoa_delete->Page_Terminate("pessoalist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $pessoa_delete->ShowPageHeader(); ?>
<?php
$pessoa_delete->ShowMessage();
?>
<form name="fpessoadelete" id="fpessoadelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="pessoa">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($pessoa_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_pessoadelete" class="ewTable ewTableSeparate">
<?php echo $pessoa->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_pessoa_nu_pessoa" class="pessoa_nu_pessoa"><?php echo $pessoa->nu_pessoa->FldCaption() ?></span></td>
		<td><span id="elh_pessoa_no_pessoa" class="pessoa_no_pessoa"><?php echo $pessoa->no_pessoa->FldCaption() ?></span></td>
		<td><span id="elh_pessoa_ic_tpEnvolvimento" class="pessoa_ic_tpEnvolvimento"><?php echo $pessoa->ic_tpEnvolvimento->FldCaption() ?></span></td>
		<td><span id="elh_pessoa_ic_ativo" class="pessoa_ic_ativo"><?php echo $pessoa->ic_ativo->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$pessoa_delete->RecCnt = 0;
$i = 0;
while (!$pessoa_delete->Recordset->EOF) {
	$pessoa_delete->RecCnt++;
	$pessoa_delete->RowCnt++;

	// Set row properties
	$pessoa->ResetAttrs();
	$pessoa->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$pessoa_delete->LoadRowValues($pessoa_delete->Recordset);

	// Render row
	$pessoa_delete->RenderRow();
?>
	<tr<?php echo $pessoa->RowAttributes() ?>>
		<td<?php echo $pessoa->nu_pessoa->CellAttributes() ?>>
<span id="el<?php echo $pessoa_delete->RowCnt ?>_pessoa_nu_pessoa" class="control-group pessoa_nu_pessoa">
<span<?php echo $pessoa->nu_pessoa->ViewAttributes() ?>>
<?php echo $pessoa->nu_pessoa->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $pessoa->no_pessoa->CellAttributes() ?>>
<span id="el<?php echo $pessoa_delete->RowCnt ?>_pessoa_no_pessoa" class="control-group pessoa_no_pessoa">
<span<?php echo $pessoa->no_pessoa->ViewAttributes() ?>>
<?php echo $pessoa->no_pessoa->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $pessoa->ic_tpEnvolvimento->CellAttributes() ?>>
<span id="el<?php echo $pessoa_delete->RowCnt ?>_pessoa_ic_tpEnvolvimento" class="control-group pessoa_ic_tpEnvolvimento">
<span<?php echo $pessoa->ic_tpEnvolvimento->ViewAttributes() ?>>
<?php echo $pessoa->ic_tpEnvolvimento->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $pessoa->ic_ativo->CellAttributes() ?>>
<span id="el<?php echo $pessoa_delete->RowCnt ?>_pessoa_ic_ativo" class="control-group pessoa_ic_ativo">
<span<?php echo $pessoa->ic_ativo->ViewAttributes() ?>>
<?php echo $pessoa->ic_ativo->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$pessoa_delete->Recordset->MoveNext();
}
$pessoa_delete->Recordset->Close();
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
fpessoadelete.Init();
</script>
<?php
$pessoa_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pessoa_delete->Page_Terminate();
?>
