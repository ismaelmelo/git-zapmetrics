<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "demanda_artefatoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$demanda_artefato_delete = NULL; // Initialize page object first

class cdemanda_artefato_delete extends cdemanda_artefato {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'demanda_artefato';

	// Page object name
	var $PageObjName = 'demanda_artefato_delete';

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

		// Table object (demanda_artefato)
		if (!isset($GLOBALS["demanda_artefato"])) {
			$GLOBALS["demanda_artefato"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["demanda_artefato"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'demanda_artefato', TRUE);

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
			$this->Page_Terminate("demanda_artefatolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_artefato->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("demanda_artefatolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in demanda_artefato class, demanda_artefatoinfo.php

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
		$this->nu_artefato->setDbValue($rs->fields('nu_artefato'));
		$this->nu_demanda->setDbValue($rs->fields('nu_demanda'));
		$this->ic_tpArtefato->setDbValue($rs->fields('ic_tpArtefato'));
		$this->no_local->setDbValue($rs->fields('no_local'));
		$this->im_anexo->setDbValue($rs->fields('im_anexo'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_pessoaResp->setDbValue($rs->fields('nu_pessoaResp'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_artefato->DbValue = $row['nu_artefato'];
		$this->nu_demanda->DbValue = $row['nu_demanda'];
		$this->ic_tpArtefato->DbValue = $row['ic_tpArtefato'];
		$this->no_local->DbValue = $row['no_local'];
		$this->im_anexo->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->nu_pessoaResp->DbValue = $row['nu_pessoaResp'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_artefato
		// nu_demanda
		// ic_tpArtefato
		// no_local
		// im_anexo
		// ic_situacao
		// nu_pessoaResp
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_artefato
			$this->nu_artefato->ViewValue = $this->nu_artefato->CurrentValue;
			$this->nu_artefato->ViewCustomAttributes = "";

			// nu_demanda
			if (strval($this->nu_demanda->CurrentValue) <> "") {
				$sFilterWrk = "[nu_demanda]" . ew_SearchString("=", $this->nu_demanda->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_demanda], [nu_demanda] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[demanda]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_demanda, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_demanda->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_demanda->ViewValue = $this->nu_demanda->CurrentValue;
				}
			} else {
				$this->nu_demanda->ViewValue = NULL;
			}
			$this->nu_demanda->ViewCustomAttributes = "";

			// ic_tpArtefato
			if (strval($this->ic_tpArtefato->CurrentValue) <> "") {
				switch ($this->ic_tpArtefato->CurrentValue) {
					case $this->ic_tpArtefato->FldTagValue(1):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(1) <> "" ? $this->ic_tpArtefato->FldTagCaption(1) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(2):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(2) <> "" ? $this->ic_tpArtefato->FldTagCaption(2) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(3):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(3) <> "" ? $this->ic_tpArtefato->FldTagCaption(3) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(4):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(4) <> "" ? $this->ic_tpArtefato->FldTagCaption(4) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(5):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(5) <> "" ? $this->ic_tpArtefato->FldTagCaption(5) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(6):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(6) <> "" ? $this->ic_tpArtefato->FldTagCaption(6) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(7):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(7) <> "" ? $this->ic_tpArtefato->FldTagCaption(7) : $this->ic_tpArtefato->CurrentValue;
						break;
					case $this->ic_tpArtefato->FldTagValue(8):
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->FldTagCaption(8) <> "" ? $this->ic_tpArtefato->FldTagCaption(8) : $this->ic_tpArtefato->CurrentValue;
						break;
					default:
						$this->ic_tpArtefato->ViewValue = $this->ic_tpArtefato->CurrentValue;
				}
			} else {
				$this->ic_tpArtefato->ViewValue = NULL;
			}
			$this->ic_tpArtefato->ViewCustomAttributes = "";

			// no_local
			$this->no_local->ViewValue = $this->no_local->CurrentValue;
			$this->no_local->ViewCustomAttributes = "";

			// im_anexo
			$this->im_anexo->ViewValue = $this->im_anexo->CurrentValue;
			$this->im_anexo->ViewCustomAttributes = "";

			// ic_situacao
			$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_pessoaResp
			if (strval($this->nu_pessoaResp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_pessoaResp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoaResp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoaResp->ViewValue = $this->nu_pessoaResp->CurrentValue;
				}
			} else {
				$this->nu_pessoaResp->ViewValue = NULL;
			}
			$this->nu_pessoaResp->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_artefato
			$this->nu_artefato->LinkCustomAttributes = "";
			$this->nu_artefato->HrefValue = "";
			$this->nu_artefato->TooltipValue = "";

			// nu_demanda
			$this->nu_demanda->LinkCustomAttributes = "";
			$this->nu_demanda->HrefValue = "";
			$this->nu_demanda->TooltipValue = "";

			// ic_tpArtefato
			$this->ic_tpArtefato->LinkCustomAttributes = "";
			$this->ic_tpArtefato->HrefValue = "";
			$this->ic_tpArtefato->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

			// nu_pessoaResp
			$this->nu_pessoaResp->LinkCustomAttributes = "";
			$this->nu_pessoaResp->HrefValue = "";
			$this->nu_pessoaResp->TooltipValue = "";
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
				$sThisKey .= $row['nu_artefato'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "demanda_artefatolist.php", $this->TableVar);
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
if (!isset($demanda_artefato_delete)) $demanda_artefato_delete = new cdemanda_artefato_delete();

// Page init
$demanda_artefato_delete->Page_Init();

// Page main
$demanda_artefato_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$demanda_artefato_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var demanda_artefato_delete = new ew_Page("demanda_artefato_delete");
demanda_artefato_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = demanda_artefato_delete.PageID; // For backward compatibility

// Form object
var fdemanda_artefatodelete = new ew_Form("fdemanda_artefatodelete");

// Form_CustomValidate event
fdemanda_artefatodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdemanda_artefatodelete.ValidateRequired = true;
<?php } else { ?>
fdemanda_artefatodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdemanda_artefatodelete.Lists["x_nu_demanda"] = {"LinkField":"x_nu_demanda","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_demanda","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdemanda_artefatodelete.Lists["x_nu_pessoaResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($demanda_artefato_delete->Recordset = $demanda_artefato_delete->LoadRecordset())
	$demanda_artefato_deleteTotalRecs = $demanda_artefato_delete->Recordset->RecordCount(); // Get record count
if ($demanda_artefato_deleteTotalRecs <= 0) { // No record found, exit
	if ($demanda_artefato_delete->Recordset)
		$demanda_artefato_delete->Recordset->Close();
	$demanda_artefato_delete->Page_Terminate("demanda_artefatolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $demanda_artefato_delete->ShowPageHeader(); ?>
<?php
$demanda_artefato_delete->ShowMessage();
?>
<form name="fdemanda_artefatodelete" id="fdemanda_artefatodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="demanda_artefato">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($demanda_artefato_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_demanda_artefatodelete" class="ewTable ewTableSeparate">
<?php echo $demanda_artefato->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_demanda_artefato_nu_artefato" class="demanda_artefato_nu_artefato"><?php echo $demanda_artefato->nu_artefato->FldCaption() ?></span></td>
		<td><span id="elh_demanda_artefato_nu_demanda" class="demanda_artefato_nu_demanda"><?php echo $demanda_artefato->nu_demanda->FldCaption() ?></span></td>
		<td><span id="elh_demanda_artefato_ic_tpArtefato" class="demanda_artefato_ic_tpArtefato"><?php echo $demanda_artefato->ic_tpArtefato->FldCaption() ?></span></td>
		<td><span id="elh_demanda_artefato_ic_situacao" class="demanda_artefato_ic_situacao"><?php echo $demanda_artefato->ic_situacao->FldCaption() ?></span></td>
		<td><span id="elh_demanda_artefato_nu_pessoaResp" class="demanda_artefato_nu_pessoaResp"><?php echo $demanda_artefato->nu_pessoaResp->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$demanda_artefato_delete->RecCnt = 0;
$i = 0;
while (!$demanda_artefato_delete->Recordset->EOF) {
	$demanda_artefato_delete->RecCnt++;
	$demanda_artefato_delete->RowCnt++;

	// Set row properties
	$demanda_artefato->ResetAttrs();
	$demanda_artefato->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$demanda_artefato_delete->LoadRowValues($demanda_artefato_delete->Recordset);

	// Render row
	$demanda_artefato_delete->RenderRow();
?>
	<tr<?php echo $demanda_artefato->RowAttributes() ?>>
		<td<?php echo $demanda_artefato->nu_artefato->CellAttributes() ?>>
<span id="el<?php echo $demanda_artefato_delete->RowCnt ?>_demanda_artefato_nu_artefato" class="control-group demanda_artefato_nu_artefato">
<span<?php echo $demanda_artefato->nu_artefato->ViewAttributes() ?>>
<?php echo $demanda_artefato->nu_artefato->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda_artefato->nu_demanda->CellAttributes() ?>>
<span id="el<?php echo $demanda_artefato_delete->RowCnt ?>_demanda_artefato_nu_demanda" class="control-group demanda_artefato_nu_demanda">
<span<?php echo $demanda_artefato->nu_demanda->ViewAttributes() ?>>
<?php echo $demanda_artefato->nu_demanda->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda_artefato->ic_tpArtefato->CellAttributes() ?>>
<span id="el<?php echo $demanda_artefato_delete->RowCnt ?>_demanda_artefato_ic_tpArtefato" class="control-group demanda_artefato_ic_tpArtefato">
<span<?php echo $demanda_artefato->ic_tpArtefato->ViewAttributes() ?>>
<?php echo $demanda_artefato->ic_tpArtefato->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda_artefato->ic_situacao->CellAttributes() ?>>
<span id="el<?php echo $demanda_artefato_delete->RowCnt ?>_demanda_artefato_ic_situacao" class="control-group demanda_artefato_ic_situacao">
<span<?php echo $demanda_artefato->ic_situacao->ViewAttributes() ?>>
<?php echo $demanda_artefato->ic_situacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $demanda_artefato->nu_pessoaResp->CellAttributes() ?>>
<span id="el<?php echo $demanda_artefato_delete->RowCnt ?>_demanda_artefato_nu_pessoaResp" class="control-group demanda_artefato_nu_pessoaResp">
<span<?php echo $demanda_artefato->nu_pessoaResp->ViewAttributes() ?>>
<?php echo $demanda_artefato->nu_pessoaResp->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$demanda_artefato_delete->Recordset->MoveNext();
}
$demanda_artefato_delete->Recordset->Close();
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
fdemanda_artefatodelete.Init();
</script>
<?php
$demanda_artefato_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$demanda_artefato_delete->Page_Terminate();
?>
