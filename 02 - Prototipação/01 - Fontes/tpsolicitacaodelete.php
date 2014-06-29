<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tpsolicitacaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tpsolicitacao_delete = NULL; // Initialize page object first

class ctpsolicitacao_delete extends ctpsolicitacao {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'tpsolicitacao';

	// Page object name
	var $PageObjName = 'tpsolicitacao_delete';

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
	var $AuditTrailOnDelete = TRUE;

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

		// Table object (tpsolicitacao)
		if (!isset($GLOBALS["tpsolicitacao"])) {
			$GLOBALS["tpsolicitacao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpsolicitacao"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tpsolicitacao', TRUE);

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
			$this->Page_Terminate("tpsolicitacaolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_tpSolicitacao->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate("tpsolicitacaolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tpsolicitacao class, tpsolicitacaoinfo.php

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
		$this->nu_tpSolicitacao->setDbValue($rs->fields('nu_tpSolicitacao'));
		$this->no_tpSolicitacao->setDbValue($rs->fields('no_tpSolicitacao'));
		$this->ic_vincProjeto->setDbValue($rs->fields('ic_vincProjeto'));
		$this->ic_vincAtivRedmine->setDbValue($rs->fields('ic_vincAtivRedmine'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_tpSolicitacao->DbValue = $row['nu_tpSolicitacao'];
		$this->no_tpSolicitacao->DbValue = $row['no_tpSolicitacao'];
		$this->ic_vincProjeto->DbValue = $row['ic_vincProjeto'];
		$this->ic_vincAtivRedmine->DbValue = $row['ic_vincAtivRedmine'];
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
		// nu_tpSolicitacao
		// no_tpSolicitacao
		// ic_vincProjeto
		// ic_vincAtivRedmine
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_tpSolicitacao
			$this->nu_tpSolicitacao->ViewValue = $this->nu_tpSolicitacao->CurrentValue;
			$this->nu_tpSolicitacao->ViewCustomAttributes = "";

			// no_tpSolicitacao
			$this->no_tpSolicitacao->ViewValue = $this->no_tpSolicitacao->CurrentValue;
			$this->no_tpSolicitacao->ViewCustomAttributes = "";

			// ic_vincProjeto
			if (strval($this->ic_vincProjeto->CurrentValue) <> "") {
				switch ($this->ic_vincProjeto->CurrentValue) {
					case $this->ic_vincProjeto->FldTagValue(1):
						$this->ic_vincProjeto->ViewValue = $this->ic_vincProjeto->FldTagCaption(1) <> "" ? $this->ic_vincProjeto->FldTagCaption(1) : $this->ic_vincProjeto->CurrentValue;
						break;
					case $this->ic_vincProjeto->FldTagValue(2):
						$this->ic_vincProjeto->ViewValue = $this->ic_vincProjeto->FldTagCaption(2) <> "" ? $this->ic_vincProjeto->FldTagCaption(2) : $this->ic_vincProjeto->CurrentValue;
						break;
					default:
						$this->ic_vincProjeto->ViewValue = $this->ic_vincProjeto->CurrentValue;
				}
			} else {
				$this->ic_vincProjeto->ViewValue = NULL;
			}
			$this->ic_vincProjeto->ViewCustomAttributes = "";

			// ic_vincAtivRedmine
			if (strval($this->ic_vincAtivRedmine->CurrentValue) <> "") {
				switch ($this->ic_vincAtivRedmine->CurrentValue) {
					case $this->ic_vincAtivRedmine->FldTagValue(1):
						$this->ic_vincAtivRedmine->ViewValue = $this->ic_vincAtivRedmine->FldTagCaption(1) <> "" ? $this->ic_vincAtivRedmine->FldTagCaption(1) : $this->ic_vincAtivRedmine->CurrentValue;
						break;
					case $this->ic_vincAtivRedmine->FldTagValue(2):
						$this->ic_vincAtivRedmine->ViewValue = $this->ic_vincAtivRedmine->FldTagCaption(2) <> "" ? $this->ic_vincAtivRedmine->FldTagCaption(2) : $this->ic_vincAtivRedmine->CurrentValue;
						break;
					default:
						$this->ic_vincAtivRedmine->ViewValue = $this->ic_vincAtivRedmine->CurrentValue;
				}
			} else {
				$this->ic_vincAtivRedmine->ViewValue = NULL;
			}
			$this->ic_vincAtivRedmine->ViewCustomAttributes = "";

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

			// nu_tpSolicitacao
			$this->nu_tpSolicitacao->LinkCustomAttributes = "";
			$this->nu_tpSolicitacao->HrefValue = "";
			$this->nu_tpSolicitacao->TooltipValue = "";

			// no_tpSolicitacao
			$this->no_tpSolicitacao->LinkCustomAttributes = "";
			$this->no_tpSolicitacao->HrefValue = "";
			$this->no_tpSolicitacao->TooltipValue = "";

			// ic_vincProjeto
			$this->ic_vincProjeto->LinkCustomAttributes = "";
			$this->ic_vincProjeto->HrefValue = "";
			$this->ic_vincProjeto->TooltipValue = "";

			// ic_vincAtivRedmine
			$this->ic_vincAtivRedmine->LinkCustomAttributes = "";
			$this->ic_vincAtivRedmine->HrefValue = "";
			$this->ic_vincAtivRedmine->TooltipValue = "";

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
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

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
				$sThisKey .= $row['nu_tpSolicitacao'];
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
			if ($DeleteRows) {
				foreach ($rsold as $row)
					$this->WriteAuditTrailOnDelete($row);
			}
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tpsolicitacaolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'tpsolicitacao';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'tpsolicitacao';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_tpSolicitacao'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
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
if (!isset($tpsolicitacao_delete)) $tpsolicitacao_delete = new ctpsolicitacao_delete();

// Page init
$tpsolicitacao_delete->Page_Init();

// Page main
$tpsolicitacao_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpsolicitacao_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tpsolicitacao_delete = new ew_Page("tpsolicitacao_delete");
tpsolicitacao_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = tpsolicitacao_delete.PageID; // For backward compatibility

// Form object
var ftpsolicitacaodelete = new ew_Form("ftpsolicitacaodelete");

// Form_CustomValidate event
ftpsolicitacaodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpsolicitacaodelete.ValidateRequired = true;
<?php } else { ?>
ftpsolicitacaodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($tpsolicitacao_delete->Recordset = $tpsolicitacao_delete->LoadRecordset())
	$tpsolicitacao_deleteTotalRecs = $tpsolicitacao_delete->Recordset->RecordCount(); // Get record count
if ($tpsolicitacao_deleteTotalRecs <= 0) { // No record found, exit
	if ($tpsolicitacao_delete->Recordset)
		$tpsolicitacao_delete->Recordset->Close();
	$tpsolicitacao_delete->Page_Terminate("tpsolicitacaolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $tpsolicitacao_delete->ShowPageHeader(); ?>
<?php
$tpsolicitacao_delete->ShowMessage();
?>
<form name="ftpsolicitacaodelete" id="ftpsolicitacaodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tpsolicitacao">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tpsolicitacao_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tpsolicitacaodelete" class="ewTable ewTableSeparate">
<?php echo $tpsolicitacao->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_tpsolicitacao_nu_tpSolicitacao" class="tpsolicitacao_nu_tpSolicitacao"><?php echo $tpsolicitacao->nu_tpSolicitacao->FldCaption() ?></span></td>
		<td><span id="elh_tpsolicitacao_no_tpSolicitacao" class="tpsolicitacao_no_tpSolicitacao"><?php echo $tpsolicitacao->no_tpSolicitacao->FldCaption() ?></span></td>
		<td><span id="elh_tpsolicitacao_ic_vincProjeto" class="tpsolicitacao_ic_vincProjeto"><?php echo $tpsolicitacao->ic_vincProjeto->FldCaption() ?></span></td>
		<td><span id="elh_tpsolicitacao_ic_vincAtivRedmine" class="tpsolicitacao_ic_vincAtivRedmine"><?php echo $tpsolicitacao->ic_vincAtivRedmine->FldCaption() ?></span></td>
		<td><span id="elh_tpsolicitacao_ic_ativo" class="tpsolicitacao_ic_ativo"><?php echo $tpsolicitacao->ic_ativo->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$tpsolicitacao_delete->RecCnt = 0;
$i = 0;
while (!$tpsolicitacao_delete->Recordset->EOF) {
	$tpsolicitacao_delete->RecCnt++;
	$tpsolicitacao_delete->RowCnt++;

	// Set row properties
	$tpsolicitacao->ResetAttrs();
	$tpsolicitacao->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tpsolicitacao_delete->LoadRowValues($tpsolicitacao_delete->Recordset);

	// Render row
	$tpsolicitacao_delete->RenderRow();
?>
	<tr<?php echo $tpsolicitacao->RowAttributes() ?>>
		<td<?php echo $tpsolicitacao->nu_tpSolicitacao->CellAttributes() ?>>
<span id="el<?php echo $tpsolicitacao_delete->RowCnt ?>_tpsolicitacao_nu_tpSolicitacao" class="control-group tpsolicitacao_nu_tpSolicitacao">
<span<?php echo $tpsolicitacao->nu_tpSolicitacao->ViewAttributes() ?>>
<?php echo $tpsolicitacao->nu_tpSolicitacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpsolicitacao->no_tpSolicitacao->CellAttributes() ?>>
<span id="el<?php echo $tpsolicitacao_delete->RowCnt ?>_tpsolicitacao_no_tpSolicitacao" class="control-group tpsolicitacao_no_tpSolicitacao">
<span<?php echo $tpsolicitacao->no_tpSolicitacao->ViewAttributes() ?>>
<?php echo $tpsolicitacao->no_tpSolicitacao->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpsolicitacao->ic_vincProjeto->CellAttributes() ?>>
<span id="el<?php echo $tpsolicitacao_delete->RowCnt ?>_tpsolicitacao_ic_vincProjeto" class="control-group tpsolicitacao_ic_vincProjeto">
<span<?php echo $tpsolicitacao->ic_vincProjeto->ViewAttributes() ?>>
<?php echo $tpsolicitacao->ic_vincProjeto->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpsolicitacao->ic_vincAtivRedmine->CellAttributes() ?>>
<span id="el<?php echo $tpsolicitacao_delete->RowCnt ?>_tpsolicitacao_ic_vincAtivRedmine" class="control-group tpsolicitacao_ic_vincAtivRedmine">
<span<?php echo $tpsolicitacao->ic_vincAtivRedmine->ViewAttributes() ?>>
<?php echo $tpsolicitacao->ic_vincAtivRedmine->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tpsolicitacao->ic_ativo->CellAttributes() ?>>
<span id="el<?php echo $tpsolicitacao_delete->RowCnt ?>_tpsolicitacao_ic_ativo" class="control-group tpsolicitacao_ic_ativo">
<span<?php echo $tpsolicitacao->ic_ativo->ViewAttributes() ?>>
<?php echo $tpsolicitacao->ic_ativo->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$tpsolicitacao_delete->Recordset->MoveNext();
}
$tpsolicitacao_delete->Recordset->Close();
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
ftpsolicitacaodelete.Init();
</script>
<?php
$tpsolicitacao_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tpsolicitacao_delete->Page_Terminate();
?>
