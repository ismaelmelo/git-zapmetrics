<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "contratoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$contrato_delete = NULL; // Initialize page object first

class ccontrato_delete extends ccontrato {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contrato';

	// Page object name
	var $PageObjName = 'contrato_delete';

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

		// Table object (contrato)
		if (!isset($GLOBALS["contrato"])) {
			$GLOBALS["contrato"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contrato"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contrato', TRUE);

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
			$this->Page_Terminate("contratolist.php");
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
			$this->Page_Terminate("contratolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in contrato class, contratoinfo.php

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
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->nu_fornecedor->setDbValue($rs->fields('nu_fornecedor'));
		$this->no_contrato->setDbValue($rs->fields('no_contrato'));
		$this->ds_contrato->setDbValue($rs->fields('ds_contrato'));
		$this->dt_vencimento->setDbValue($rs->fields('dt_vencimento'));
		$this->im_contrato->Upload->DbValue = $rs->fields('im_contrato');
		$this->nu_stContrato->setDbValue($rs->fields('nu_stContrato'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->nu_fornecedor->DbValue = $row['nu_fornecedor'];
		$this->no_contrato->DbValue = $row['no_contrato'];
		$this->ds_contrato->DbValue = $row['ds_contrato'];
		$this->dt_vencimento->DbValue = $row['dt_vencimento'];
		$this->im_contrato->Upload->DbValue = $row['im_contrato'];
		$this->nu_stContrato->DbValue = $row['nu_stContrato'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_contrato

		$this->nu_contrato->CellCssStyle = "white-space: nowrap;";

		// co_alternativo
		// nu_fornecedor
		// no_contrato
		// ds_contrato
		// dt_vencimento
		// im_contrato
		// nu_stContrato
		// ds_observacoes

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_contrato
			$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
			$this->nu_contrato->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// nu_fornecedor
			if (strval($this->nu_fornecedor->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedor->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornecedor, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornecedor->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornecedor->ViewValue = $this->nu_fornecedor->CurrentValue;
				}
			} else {
				$this->nu_fornecedor->ViewValue = NULL;
			}
			$this->nu_fornecedor->ViewCustomAttributes = "";

			// no_contrato
			$this->no_contrato->ViewValue = $this->no_contrato->CurrentValue;
			$this->no_contrato->ViewCustomAttributes = "";

			// dt_vencimento
			$this->dt_vencimento->ViewValue = $this->dt_vencimento->CurrentValue;
			$this->dt_vencimento->ViewValue = ew_FormatDateTime($this->dt_vencimento->ViewValue, 7);
			$this->dt_vencimento->ViewCustomAttributes = "";

			// nu_stContrato
			if (strval($this->nu_stContrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stContrato]" . ew_SearchString("=", $this->nu_stContrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stContrato], [no_stContrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stcontrato]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stContrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stContrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stContrato->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stContrato->ViewValue = $this->nu_stContrato->CurrentValue;
				}
			} else {
				$this->nu_stContrato->ViewValue = NULL;
			}
			$this->nu_stContrato->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// nu_fornecedor
			$this->nu_fornecedor->LinkCustomAttributes = "";
			$this->nu_fornecedor->HrefValue = "";
			$this->nu_fornecedor->TooltipValue = "";

			// no_contrato
			$this->no_contrato->LinkCustomAttributes = "";
			$this->no_contrato->HrefValue = "";
			$this->no_contrato->TooltipValue = "";

			// dt_vencimento
			$this->dt_vencimento->LinkCustomAttributes = "";
			$this->dt_vencimento->HrefValue = "";
			$this->dt_vencimento->TooltipValue = "";

			// nu_stContrato
			$this->nu_stContrato->LinkCustomAttributes = "";
			$this->nu_stContrato->HrefValue = "";
			$this->nu_stContrato->TooltipValue = "";
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
				$sThisKey .= $row['nu_contrato'];
				$this->LoadDbValues($row);
				$this->im_contrato->OldUploadPath = "arquivos/contratos";
				@unlink(ew_UploadPathEx(TRUE, $this->im_contrato->OldUploadPath) . $row['im_contrato']);
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "contratolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'contrato';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		if (!$this->AuditTrailOnDelete) return;
		$table = 'contrato';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_contrato'];

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
if (!isset($contrato_delete)) $contrato_delete = new ccontrato_delete();

// Page init
$contrato_delete->Page_Init();

// Page main
$contrato_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contrato_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var contrato_delete = new ew_Page("contrato_delete");
contrato_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = contrato_delete.PageID; // For backward compatibility

// Form object
var fcontratodelete = new ew_Form("fcontratodelete");

// Form_CustomValidate event
fcontratodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontratodelete.ValidateRequired = true;
<?php } else { ?>
fcontratodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontratodelete.Lists["x_nu_fornecedor"] = {"LinkField":"x_nu_fornecedor","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_fornecedor","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontratodelete.Lists["x_nu_stContrato"] = {"LinkField":"x_nu_stContrato","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stContrato","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($contrato_delete->Recordset = $contrato_delete->LoadRecordset())
	$contrato_deleteTotalRecs = $contrato_delete->Recordset->RecordCount(); // Get record count
if ($contrato_deleteTotalRecs <= 0) { // No record found, exit
	if ($contrato_delete->Recordset)
		$contrato_delete->Recordset->Close();
	$contrato_delete->Page_Terminate("contratolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $contrato_delete->ShowPageHeader(); ?>
<?php
$contrato_delete->ShowMessage();
?>
<form name="fcontratodelete" id="fcontratodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contrato">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($contrato_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_contratodelete" class="ewTable ewTableSeparate">
<?php echo $contrato->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_contrato_co_alternativo" class="contrato_co_alternativo"><?php echo $contrato->co_alternativo->FldCaption() ?></span></td>
		<td><span id="elh_contrato_nu_fornecedor" class="contrato_nu_fornecedor"><?php echo $contrato->nu_fornecedor->FldCaption() ?></span></td>
		<td><span id="elh_contrato_no_contrato" class="contrato_no_contrato"><?php echo $contrato->no_contrato->FldCaption() ?></span></td>
		<td><span id="elh_contrato_dt_vencimento" class="contrato_dt_vencimento"><?php echo $contrato->dt_vencimento->FldCaption() ?></span></td>
		<td><span id="elh_contrato_nu_stContrato" class="contrato_nu_stContrato"><?php echo $contrato->nu_stContrato->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$contrato_delete->RecCnt = 0;
$i = 0;
while (!$contrato_delete->Recordset->EOF) {
	$contrato_delete->RecCnt++;
	$contrato_delete->RowCnt++;

	// Set row properties
	$contrato->ResetAttrs();
	$contrato->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$contrato_delete->LoadRowValues($contrato_delete->Recordset);

	// Render row
	$contrato_delete->RenderRow();
?>
	<tr<?php echo $contrato->RowAttributes() ?>>
		<td<?php echo $contrato->co_alternativo->CellAttributes() ?>>
<span id="el<?php echo $contrato_delete->RowCnt ?>_contrato_co_alternativo" class="control-group contrato_co_alternativo">
<span<?php echo $contrato->co_alternativo->ViewAttributes() ?>>
<?php echo $contrato->co_alternativo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contrato->nu_fornecedor->CellAttributes() ?>>
<span id="el<?php echo $contrato_delete->RowCnt ?>_contrato_nu_fornecedor" class="control-group contrato_nu_fornecedor">
<span<?php echo $contrato->nu_fornecedor->ViewAttributes() ?>>
<?php echo $contrato->nu_fornecedor->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contrato->no_contrato->CellAttributes() ?>>
<span id="el<?php echo $contrato_delete->RowCnt ?>_contrato_no_contrato" class="control-group contrato_no_contrato">
<span<?php echo $contrato->no_contrato->ViewAttributes() ?>>
<?php echo $contrato->no_contrato->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contrato->dt_vencimento->CellAttributes() ?>>
<span id="el<?php echo $contrato_delete->RowCnt ?>_contrato_dt_vencimento" class="control-group contrato_dt_vencimento">
<span<?php echo $contrato->dt_vencimento->ViewAttributes() ?>>
<?php echo $contrato->dt_vencimento->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $contrato->nu_stContrato->CellAttributes() ?>>
<span id="el<?php echo $contrato_delete->RowCnt ?>_contrato_nu_stContrato" class="control-group contrato_nu_stContrato">
<span<?php echo $contrato->nu_stContrato->ViewAttributes() ?>>
<?php echo $contrato->nu_stContrato->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$contrato_delete->Recordset->MoveNext();
}
$contrato_delete->Recordset->Close();
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
fcontratodelete.Init();
</script>
<?php
$contrato_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$contrato_delete->Page_Terminate();
?>
