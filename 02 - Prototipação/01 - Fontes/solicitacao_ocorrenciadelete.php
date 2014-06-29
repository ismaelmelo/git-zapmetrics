<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "solicitacao_ocorrenciainfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$solicitacao_ocorrencia_delete = NULL; // Initialize page object first

class csolicitacao_ocorrencia_delete extends csolicitacao_ocorrencia {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'solicitacao_ocorrencia';

	// Page object name
	var $PageObjName = 'solicitacao_ocorrencia_delete';

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

		// Table object (solicitacao_ocorrencia)
		if (!isset($GLOBALS["solicitacao_ocorrencia"])) {
			$GLOBALS["solicitacao_ocorrencia"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["solicitacao_ocorrencia"];
		}

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS['solicitacaoMetricas'])) $GLOBALS['solicitacaoMetricas'] = new csolicitacaoMetricas();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'solicitacao_ocorrencia', TRUE);

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
			$this->Page_Terminate("solicitacao_ocorrencialist.php");
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
			$this->Page_Terminate("solicitacao_ocorrencialist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in solicitacao_ocorrencia class, solicitacao_ocorrenciainfo.php

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
		$this->nu_solicitacao->setDbValue($rs->fields('nu_solicitacao'));
		$this->nu_ocorrencia->setDbValue($rs->fields('nu_ocorrencia'));
		$this->ic_tpOcorrencia->setDbValue($rs->fields('ic_tpOcorrencia'));
		$this->ic_exibirNoLaudo->setDbValue($rs->fields('ic_exibirNoLaudo'));
		$this->ds_observacao->setDbValue($rs->fields('ds_observacao'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solicitacao->DbValue = $row['nu_solicitacao'];
		$this->nu_ocorrencia->DbValue = $row['nu_ocorrencia'];
		$this->ic_tpOcorrencia->DbValue = $row['ic_tpOcorrencia'];
		$this->ic_exibirNoLaudo->DbValue = $row['ic_exibirNoLaudo'];
		$this->ds_observacao->DbValue = $row['ds_observacao'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solicitacao

		$this->nu_solicitacao->CellCssStyle = "white-space: nowrap;";

		// nu_ocorrencia
		$this->nu_ocorrencia->CellCssStyle = "white-space: nowrap;";

		// ic_tpOcorrencia
		// ic_exibirNoLaudo
		// ds_observacao
		// nu_usuarioInc
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solicitacao
			$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
			$this->nu_solicitacao->ViewCustomAttributes = "";

			// nu_ocorrencia
			$this->nu_ocorrencia->ViewValue = $this->nu_ocorrencia->CurrentValue;
			$this->nu_ocorrencia->ViewCustomAttributes = "";

			// ic_tpOcorrencia
			if (strval($this->ic_tpOcorrencia->CurrentValue) <> "") {
				switch ($this->ic_tpOcorrencia->CurrentValue) {
					case $this->ic_tpOcorrencia->FldTagValue(1):
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(1) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(1) : $this->ic_tpOcorrencia->CurrentValue;
						break;
					case $this->ic_tpOcorrencia->FldTagValue(2):
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(2) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(2) : $this->ic_tpOcorrencia->CurrentValue;
						break;
					case $this->ic_tpOcorrencia->FldTagValue(3):
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(3) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(3) : $this->ic_tpOcorrencia->CurrentValue;
						break;
					case $this->ic_tpOcorrencia->FldTagValue(4):
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->FldTagCaption(4) <> "" ? $this->ic_tpOcorrencia->FldTagCaption(4) : $this->ic_tpOcorrencia->CurrentValue;
						break;
					default:
						$this->ic_tpOcorrencia->ViewValue = $this->ic_tpOcorrencia->CurrentValue;
				}
			} else {
				$this->ic_tpOcorrencia->ViewValue = NULL;
			}
			$this->ic_tpOcorrencia->ViewCustomAttributes = "";

			// ic_exibirNoLaudo
			if (strval($this->ic_exibirNoLaudo->CurrentValue) <> "") {
				switch ($this->ic_exibirNoLaudo->CurrentValue) {
					case $this->ic_exibirNoLaudo->FldTagValue(1):
						$this->ic_exibirNoLaudo->ViewValue = $this->ic_exibirNoLaudo->FldTagCaption(1) <> "" ? $this->ic_exibirNoLaudo->FldTagCaption(1) : $this->ic_exibirNoLaudo->CurrentValue;
						break;
					case $this->ic_exibirNoLaudo->FldTagValue(2):
						$this->ic_exibirNoLaudo->ViewValue = $this->ic_exibirNoLaudo->FldTagCaption(2) <> "" ? $this->ic_exibirNoLaudo->FldTagCaption(2) : $this->ic_exibirNoLaudo->CurrentValue;
						break;
					default:
						$this->ic_exibirNoLaudo->ViewValue = $this->ic_exibirNoLaudo->CurrentValue;
				}
			} else {
				$this->ic_exibirNoLaudo->ViewValue = NULL;
			}
			$this->ic_exibirNoLaudo->ViewCustomAttributes = "";

			// nu_usuarioInc
			if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
				}
			} else {
				$this->nu_usuarioInc->ViewValue = NULL;
			}
			$this->nu_usuarioInc->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 11);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// ic_tpOcorrencia
			$this->ic_tpOcorrencia->LinkCustomAttributes = "";
			$this->ic_tpOcorrencia->HrefValue = "";
			$this->ic_tpOcorrencia->TooltipValue = "";

			// ic_exibirNoLaudo
			$this->ic_exibirNoLaudo->LinkCustomAttributes = "";
			$this->ic_exibirNoLaudo->HrefValue = "";
			$this->ic_exibirNoLaudo->TooltipValue = "";

			// nu_usuarioInc
			$this->nu_usuarioInc->LinkCustomAttributes = "";
			$this->nu_usuarioInc->HrefValue = "";
			$this->nu_usuarioInc->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";
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
				$sThisKey .= $row['nu_solicitacao'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_ocorrencia'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "solicitacao_ocorrencialist.php", $this->TableVar);
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
if (!isset($solicitacao_ocorrencia_delete)) $solicitacao_ocorrencia_delete = new csolicitacao_ocorrencia_delete();

// Page init
$solicitacao_ocorrencia_delete->Page_Init();

// Page main
$solicitacao_ocorrencia_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$solicitacao_ocorrencia_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var solicitacao_ocorrencia_delete = new ew_Page("solicitacao_ocorrencia_delete");
solicitacao_ocorrencia_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = solicitacao_ocorrencia_delete.PageID; // For backward compatibility

// Form object
var fsolicitacao_ocorrenciadelete = new ew_Form("fsolicitacao_ocorrenciadelete");

// Form_CustomValidate event
fsolicitacao_ocorrenciadelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsolicitacao_ocorrenciadelete.ValidateRequired = true;
<?php } else { ?>
fsolicitacao_ocorrenciadelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsolicitacao_ocorrenciadelete.Lists["x_nu_usuarioInc"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($solicitacao_ocorrencia_delete->Recordset = $solicitacao_ocorrencia_delete->LoadRecordset())
	$solicitacao_ocorrencia_deleteTotalRecs = $solicitacao_ocorrencia_delete->Recordset->RecordCount(); // Get record count
if ($solicitacao_ocorrencia_deleteTotalRecs <= 0) { // No record found, exit
	if ($solicitacao_ocorrencia_delete->Recordset)
		$solicitacao_ocorrencia_delete->Recordset->Close();
	$solicitacao_ocorrencia_delete->Page_Terminate("solicitacao_ocorrencialist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $solicitacao_ocorrencia_delete->ShowPageHeader(); ?>
<?php
$solicitacao_ocorrencia_delete->ShowMessage();
?>
<form name="fsolicitacao_ocorrenciadelete" id="fsolicitacao_ocorrenciadelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="solicitacao_ocorrencia">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($solicitacao_ocorrencia_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_solicitacao_ocorrenciadelete" class="ewTable ewTableSeparate">
<?php echo $solicitacao_ocorrencia->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_solicitacao_ocorrencia_ic_tpOcorrencia" class="solicitacao_ocorrencia_ic_tpOcorrencia"><?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->FldCaption() ?></span></td>
		<td><span id="elh_solicitacao_ocorrencia_ic_exibirNoLaudo" class="solicitacao_ocorrencia_ic_exibirNoLaudo"><?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->FldCaption() ?></span></td>
		<td><span id="elh_solicitacao_ocorrencia_nu_usuarioInc" class="solicitacao_ocorrencia_nu_usuarioInc"><?php echo $solicitacao_ocorrencia->nu_usuarioInc->FldCaption() ?></span></td>
		<td><span id="elh_solicitacao_ocorrencia_dh_inclusao" class="solicitacao_ocorrencia_dh_inclusao"><?php echo $solicitacao_ocorrencia->dh_inclusao->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$solicitacao_ocorrencia_delete->RecCnt = 0;
$i = 0;
while (!$solicitacao_ocorrencia_delete->Recordset->EOF) {
	$solicitacao_ocorrencia_delete->RecCnt++;
	$solicitacao_ocorrencia_delete->RowCnt++;

	// Set row properties
	$solicitacao_ocorrencia->ResetAttrs();
	$solicitacao_ocorrencia->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$solicitacao_ocorrencia_delete->LoadRowValues($solicitacao_ocorrencia_delete->Recordset);

	// Render row
	$solicitacao_ocorrencia_delete->RenderRow();
?>
	<tr<?php echo $solicitacao_ocorrencia->RowAttributes() ?>>
		<td<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->CellAttributes() ?>>
<span id="el<?php echo $solicitacao_ocorrencia_delete->RowCnt ?>_solicitacao_ocorrencia_ic_tpOcorrencia" class="control-group solicitacao_ocorrencia_ic_tpOcorrencia">
<span<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->ic_tpOcorrencia->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->CellAttributes() ?>>
<span id="el<?php echo $solicitacao_ocorrencia_delete->RowCnt ?>_solicitacao_ocorrencia_ic_exibirNoLaudo" class="control-group solicitacao_ocorrencia_ic_exibirNoLaudo">
<span<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->ic_exibirNoLaudo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacao_ocorrencia->nu_usuarioInc->CellAttributes() ?>>
<span id="el<?php echo $solicitacao_ocorrencia_delete->RowCnt ?>_solicitacao_ocorrencia_nu_usuarioInc" class="control-group solicitacao_ocorrencia_nu_usuarioInc">
<span<?php echo $solicitacao_ocorrencia->nu_usuarioInc->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->nu_usuarioInc->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $solicitacao_ocorrencia->dh_inclusao->CellAttributes() ?>>
<span id="el<?php echo $solicitacao_ocorrencia_delete->RowCnt ?>_solicitacao_ocorrencia_dh_inclusao" class="control-group solicitacao_ocorrencia_dh_inclusao">
<span<?php echo $solicitacao_ocorrencia->dh_inclusao->ViewAttributes() ?>>
<?php echo $solicitacao_ocorrencia->dh_inclusao->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$solicitacao_ocorrencia_delete->Recordset->MoveNext();
}
$solicitacao_ocorrencia_delete->Recordset->Close();
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
fsolicitacao_ocorrenciadelete.Init();
</script>
<?php
$solicitacao_ocorrencia_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$solicitacao_ocorrencia_delete->Page_Terminate();
?>
