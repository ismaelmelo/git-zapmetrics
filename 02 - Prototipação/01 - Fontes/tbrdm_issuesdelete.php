<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_issuesinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_issues_delete = NULL; // Initialize page object first

class ctbrdm_issues_delete extends ctbrdm_issues {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0602B820-DE72-4661-BB21-3716ACE9CB5F}";

	// Table name
	var $TableName = 'tbrdm_issues';

	// Page object name
	var $PageObjName = 'tbrdm_issues_delete';

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

		// Table object (tbrdm_issues)
		if (!isset($GLOBALS["tbrdm_issues"])) {
			$GLOBALS["tbrdm_issues"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_issues"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_issues', TRUE);

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
			$this->Page_Terminate("tbrdm_issueslist.php");
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
			$this->Page_Terminate("tbrdm_issueslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tbrdm_issues class, tbrdm_issuesinfo.php

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
		$this->id->setDbValue($rs->fields('id'));
		$this->tracker_id->setDbValue($rs->fields('tracker_id'));
		$this->project_id->setDbValue($rs->fields('project_id'));
		$this->subject->setDbValue($rs->fields('subject'));
		$this->description->setDbValue($rs->fields('description'));
		$this->due_date->setDbValue($rs->fields('due_date'));
		$this->category_id->setDbValue($rs->fields('category_id'));
		$this->status_id->setDbValue($rs->fields('status_id'));
		$this->assigned_to_id->setDbValue($rs->fields('assigned_to_id'));
		$this->priority_id->setDbValue($rs->fields('priority_id'));
		$this->fixed_version_id->setDbValue($rs->fields('fixed_version_id'));
		$this->author_id->setDbValue($rs->fields('author_id'));
		$this->lock_version->setDbValue($rs->fields('lock_version'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->done_ratio->setDbValue($rs->fields('done_ratio'));
		$this->estimated_hours->setDbValue($rs->fields('estimated_hours'));
		$this->parent_id->setDbValue($rs->fields('parent_id'));
		$this->root_id->setDbValue($rs->fields('root_id'));
		$this->lft->setDbValue($rs->fields('lft'));
		$this->rgt->setDbValue($rs->fields('rgt'));
		$this->is_private->setDbValue($rs->fields('is_private'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->tracker_id->DbValue = $row['tracker_id'];
		$this->project_id->DbValue = $row['project_id'];
		$this->subject->DbValue = $row['subject'];
		$this->description->DbValue = $row['description'];
		$this->due_date->DbValue = $row['due_date'];
		$this->category_id->DbValue = $row['category_id'];
		$this->status_id->DbValue = $row['status_id'];
		$this->assigned_to_id->DbValue = $row['assigned_to_id'];
		$this->priority_id->DbValue = $row['priority_id'];
		$this->fixed_version_id->DbValue = $row['fixed_version_id'];
		$this->author_id->DbValue = $row['author_id'];
		$this->lock_version->DbValue = $row['lock_version'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
		$this->start_date->DbValue = $row['start_date'];
		$this->done_ratio->DbValue = $row['done_ratio'];
		$this->estimated_hours->DbValue = $row['estimated_hours'];
		$this->parent_id->DbValue = $row['parent_id'];
		$this->root_id->DbValue = $row['root_id'];
		$this->lft->DbValue = $row['lft'];
		$this->rgt->DbValue = $row['rgt'];
		$this->is_private->DbValue = $row['is_private'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->is_private->FormValue == $this->is_private->CurrentValue && is_numeric(ew_StrToFloat($this->is_private->CurrentValue)))
			$this->is_private->CurrentValue = ew_StrToFloat($this->is_private->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// tracker_id
		// project_id
		// subject
		// description
		// due_date
		// category_id
		// status_id
		// assigned_to_id
		// priority_id
		// fixed_version_id
		// author_id
		// lock_version
		// created_on
		// updated_on
		// start_date
		// done_ratio
		// estimated_hours
		// parent_id
		// root_id
		// lft
		// rgt
		// is_private

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// tracker_id
			$this->tracker_id->ViewValue = $this->tracker_id->CurrentValue;
			$this->tracker_id->ViewCustomAttributes = "";

			// project_id
			$this->project_id->ViewValue = $this->project_id->CurrentValue;
			$this->project_id->ViewCustomAttributes = "";

			// subject
			$this->subject->ViewValue = $this->subject->CurrentValue;
			$this->subject->ViewCustomAttributes = "";

			// due_date
			$this->due_date->ViewValue = $this->due_date->CurrentValue;
			$this->due_date->ViewValue = ew_FormatDateTime($this->due_date->ViewValue, 7);
			$this->due_date->ViewCustomAttributes = "";

			// category_id
			$this->category_id->ViewValue = $this->category_id->CurrentValue;
			$this->category_id->ViewCustomAttributes = "";

			// status_id
			$this->status_id->ViewValue = $this->status_id->CurrentValue;
			$this->status_id->ViewCustomAttributes = "";

			// assigned_to_id
			$this->assigned_to_id->ViewValue = $this->assigned_to_id->CurrentValue;
			$this->assigned_to_id->ViewCustomAttributes = "";

			// priority_id
			$this->priority_id->ViewValue = $this->priority_id->CurrentValue;
			$this->priority_id->ViewCustomAttributes = "";

			// fixed_version_id
			$this->fixed_version_id->ViewValue = $this->fixed_version_id->CurrentValue;
			$this->fixed_version_id->ViewCustomAttributes = "";

			// author_id
			$this->author_id->ViewValue = $this->author_id->CurrentValue;
			$this->author_id->ViewCustomAttributes = "";

			// lock_version
			$this->lock_version->ViewValue = $this->lock_version->CurrentValue;
			$this->lock_version->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
			$this->updated_on->ViewCustomAttributes = "";

			// start_date
			$this->start_date->ViewValue = $this->start_date->CurrentValue;
			$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 7);
			$this->start_date->ViewCustomAttributes = "";

			// done_ratio
			$this->done_ratio->ViewValue = $this->done_ratio->CurrentValue;
			$this->done_ratio->ViewCustomAttributes = "";

			// estimated_hours
			$this->estimated_hours->ViewValue = $this->estimated_hours->CurrentValue;
			$this->estimated_hours->ViewCustomAttributes = "";

			// parent_id
			$this->parent_id->ViewValue = $this->parent_id->CurrentValue;
			$this->parent_id->ViewCustomAttributes = "";

			// root_id
			$this->root_id->ViewValue = $this->root_id->CurrentValue;
			$this->root_id->ViewCustomAttributes = "";

			// lft
			$this->lft->ViewValue = $this->lft->CurrentValue;
			$this->lft->ViewCustomAttributes = "";

			// rgt
			$this->rgt->ViewValue = $this->rgt->CurrentValue;
			$this->rgt->ViewCustomAttributes = "";

			// is_private
			$this->is_private->ViewValue = $this->is_private->CurrentValue;
			$this->is_private->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// tracker_id
			$this->tracker_id->LinkCustomAttributes = "";
			$this->tracker_id->HrefValue = "";
			$this->tracker_id->TooltipValue = "";

			// project_id
			$this->project_id->LinkCustomAttributes = "";
			$this->project_id->HrefValue = "";
			$this->project_id->TooltipValue = "";

			// subject
			$this->subject->LinkCustomAttributes = "";
			$this->subject->HrefValue = "";
			$this->subject->TooltipValue = "";

			// due_date
			$this->due_date->LinkCustomAttributes = "";
			$this->due_date->HrefValue = "";
			$this->due_date->TooltipValue = "";

			// category_id
			$this->category_id->LinkCustomAttributes = "";
			$this->category_id->HrefValue = "";
			$this->category_id->TooltipValue = "";

			// status_id
			$this->status_id->LinkCustomAttributes = "";
			$this->status_id->HrefValue = "";
			$this->status_id->TooltipValue = "";

			// assigned_to_id
			$this->assigned_to_id->LinkCustomAttributes = "";
			$this->assigned_to_id->HrefValue = "";
			$this->assigned_to_id->TooltipValue = "";

			// priority_id
			$this->priority_id->LinkCustomAttributes = "";
			$this->priority_id->HrefValue = "";
			$this->priority_id->TooltipValue = "";

			// fixed_version_id
			$this->fixed_version_id->LinkCustomAttributes = "";
			$this->fixed_version_id->HrefValue = "";
			$this->fixed_version_id->TooltipValue = "";

			// author_id
			$this->author_id->LinkCustomAttributes = "";
			$this->author_id->HrefValue = "";
			$this->author_id->TooltipValue = "";

			// lock_version
			$this->lock_version->LinkCustomAttributes = "";
			$this->lock_version->HrefValue = "";
			$this->lock_version->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// done_ratio
			$this->done_ratio->LinkCustomAttributes = "";
			$this->done_ratio->HrefValue = "";
			$this->done_ratio->TooltipValue = "";

			// estimated_hours
			$this->estimated_hours->LinkCustomAttributes = "";
			$this->estimated_hours->HrefValue = "";
			$this->estimated_hours->TooltipValue = "";

			// parent_id
			$this->parent_id->LinkCustomAttributes = "";
			$this->parent_id->HrefValue = "";
			$this->parent_id->TooltipValue = "";

			// root_id
			$this->root_id->LinkCustomAttributes = "";
			$this->root_id->HrefValue = "";
			$this->root_id->TooltipValue = "";

			// lft
			$this->lft->LinkCustomAttributes = "";
			$this->lft->HrefValue = "";
			$this->lft->TooltipValue = "";

			// rgt
			$this->rgt->LinkCustomAttributes = "";
			$this->rgt->HrefValue = "";
			$this->rgt->TooltipValue = "";

			// is_private
			$this->is_private->LinkCustomAttributes = "";
			$this->is_private->HrefValue = "";
			$this->is_private->TooltipValue = "";
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
				$sThisKey .= $row['id'];
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_issueslist.php", $this->TableVar);
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
if (!isset($tbrdm_issues_delete)) $tbrdm_issues_delete = new ctbrdm_issues_delete();

// Page init
$tbrdm_issues_delete->Page_Init();

// Page main
$tbrdm_issues_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_issues_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_issues_delete = new ew_Page("tbrdm_issues_delete");
tbrdm_issues_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = tbrdm_issues_delete.PageID; // For backward compatibility

// Form object
var ftbrdm_issuesdelete = new ew_Form("ftbrdm_issuesdelete");

// Form_CustomValidate event
ftbrdm_issuesdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_issuesdelete.ValidateRequired = true;
<?php } else { ?>
ftbrdm_issuesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($tbrdm_issues_delete->Recordset = $tbrdm_issues_delete->LoadRecordset())
	$tbrdm_issues_deleteTotalRecs = $tbrdm_issues_delete->Recordset->RecordCount(); // Get record count
if ($tbrdm_issues_deleteTotalRecs <= 0) { // No record found, exit
	if ($tbrdm_issues_delete->Recordset)
		$tbrdm_issues_delete->Recordset->Close();
	$tbrdm_issues_delete->Page_Terminate("tbrdm_issueslist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_issues_delete->ShowPageHeader(); ?>
<?php
$tbrdm_issues_delete->ShowMessage();
?>
<form name="ftbrdm_issuesdelete" id="ftbrdm_issuesdelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_issues">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tbrdm_issues_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tbrdm_issuesdelete" class="ewTable ewTableSeparate">
<?php echo $tbrdm_issues->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_tbrdm_issues_id" class="tbrdm_issues_id"><?php echo $tbrdm_issues->id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_tracker_id" class="tbrdm_issues_tracker_id"><?php echo $tbrdm_issues->tracker_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_project_id" class="tbrdm_issues_project_id"><?php echo $tbrdm_issues->project_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_subject" class="tbrdm_issues_subject"><?php echo $tbrdm_issues->subject->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_due_date" class="tbrdm_issues_due_date"><?php echo $tbrdm_issues->due_date->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_category_id" class="tbrdm_issues_category_id"><?php echo $tbrdm_issues->category_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_status_id" class="tbrdm_issues_status_id"><?php echo $tbrdm_issues->status_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_assigned_to_id" class="tbrdm_issues_assigned_to_id"><?php echo $tbrdm_issues->assigned_to_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_priority_id" class="tbrdm_issues_priority_id"><?php echo $tbrdm_issues->priority_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_fixed_version_id" class="tbrdm_issues_fixed_version_id"><?php echo $tbrdm_issues->fixed_version_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_author_id" class="tbrdm_issues_author_id"><?php echo $tbrdm_issues->author_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_lock_version" class="tbrdm_issues_lock_version"><?php echo $tbrdm_issues->lock_version->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_created_on" class="tbrdm_issues_created_on"><?php echo $tbrdm_issues->created_on->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_updated_on" class="tbrdm_issues_updated_on"><?php echo $tbrdm_issues->updated_on->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_start_date" class="tbrdm_issues_start_date"><?php echo $tbrdm_issues->start_date->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_done_ratio" class="tbrdm_issues_done_ratio"><?php echo $tbrdm_issues->done_ratio->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_estimated_hours" class="tbrdm_issues_estimated_hours"><?php echo $tbrdm_issues->estimated_hours->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_parent_id" class="tbrdm_issues_parent_id"><?php echo $tbrdm_issues->parent_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_root_id" class="tbrdm_issues_root_id"><?php echo $tbrdm_issues->root_id->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_lft" class="tbrdm_issues_lft"><?php echo $tbrdm_issues->lft->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_rgt" class="tbrdm_issues_rgt"><?php echo $tbrdm_issues->rgt->FldCaption() ?></span></td>
		<td><span id="elh_tbrdm_issues_is_private" class="tbrdm_issues_is_private"><?php echo $tbrdm_issues->is_private->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$tbrdm_issues_delete->RecCnt = 0;
$i = 0;
while (!$tbrdm_issues_delete->Recordset->EOF) {
	$tbrdm_issues_delete->RecCnt++;
	$tbrdm_issues_delete->RowCnt++;

	// Set row properties
	$tbrdm_issues->ResetAttrs();
	$tbrdm_issues->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tbrdm_issues_delete->LoadRowValues($tbrdm_issues_delete->Recordset);

	// Render row
	$tbrdm_issues_delete->RenderRow();
?>
	<tr<?php echo $tbrdm_issues->RowAttributes() ?>>
		<td<?php echo $tbrdm_issues->id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_id" class="control-group tbrdm_issues_id">
<span<?php echo $tbrdm_issues->id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->tracker_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_tracker_id" class="control-group tbrdm_issues_tracker_id">
<span<?php echo $tbrdm_issues->tracker_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->tracker_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->project_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_project_id" class="control-group tbrdm_issues_project_id">
<span<?php echo $tbrdm_issues->project_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->project_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->subject->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_subject" class="control-group tbrdm_issues_subject">
<span<?php echo $tbrdm_issues->subject->ViewAttributes() ?>>
<?php echo $tbrdm_issues->subject->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->due_date->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_due_date" class="control-group tbrdm_issues_due_date">
<span<?php echo $tbrdm_issues->due_date->ViewAttributes() ?>>
<?php echo $tbrdm_issues->due_date->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->category_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_category_id" class="control-group tbrdm_issues_category_id">
<span<?php echo $tbrdm_issues->category_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->category_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->status_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_status_id" class="control-group tbrdm_issues_status_id">
<span<?php echo $tbrdm_issues->status_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->status_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->assigned_to_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_assigned_to_id" class="control-group tbrdm_issues_assigned_to_id">
<span<?php echo $tbrdm_issues->assigned_to_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->assigned_to_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->priority_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_priority_id" class="control-group tbrdm_issues_priority_id">
<span<?php echo $tbrdm_issues->priority_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->priority_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->fixed_version_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_fixed_version_id" class="control-group tbrdm_issues_fixed_version_id">
<span<?php echo $tbrdm_issues->fixed_version_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->fixed_version_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->author_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_author_id" class="control-group tbrdm_issues_author_id">
<span<?php echo $tbrdm_issues->author_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->author_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->lock_version->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_lock_version" class="control-group tbrdm_issues_lock_version">
<span<?php echo $tbrdm_issues->lock_version->ViewAttributes() ?>>
<?php echo $tbrdm_issues->lock_version->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->created_on->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_created_on" class="control-group tbrdm_issues_created_on">
<span<?php echo $tbrdm_issues->created_on->ViewAttributes() ?>>
<?php echo $tbrdm_issues->created_on->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->updated_on->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_updated_on" class="control-group tbrdm_issues_updated_on">
<span<?php echo $tbrdm_issues->updated_on->ViewAttributes() ?>>
<?php echo $tbrdm_issues->updated_on->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->start_date->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_start_date" class="control-group tbrdm_issues_start_date">
<span<?php echo $tbrdm_issues->start_date->ViewAttributes() ?>>
<?php echo $tbrdm_issues->start_date->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->done_ratio->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_done_ratio" class="control-group tbrdm_issues_done_ratio">
<span<?php echo $tbrdm_issues->done_ratio->ViewAttributes() ?>>
<?php echo $tbrdm_issues->done_ratio->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->estimated_hours->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_estimated_hours" class="control-group tbrdm_issues_estimated_hours">
<span<?php echo $tbrdm_issues->estimated_hours->ViewAttributes() ?>>
<?php echo $tbrdm_issues->estimated_hours->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->parent_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_parent_id" class="control-group tbrdm_issues_parent_id">
<span<?php echo $tbrdm_issues->parent_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->parent_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->root_id->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_root_id" class="control-group tbrdm_issues_root_id">
<span<?php echo $tbrdm_issues->root_id->ViewAttributes() ?>>
<?php echo $tbrdm_issues->root_id->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->lft->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_lft" class="control-group tbrdm_issues_lft">
<span<?php echo $tbrdm_issues->lft->ViewAttributes() ?>>
<?php echo $tbrdm_issues->lft->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->rgt->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_rgt" class="control-group tbrdm_issues_rgt">
<span<?php echo $tbrdm_issues->rgt->ViewAttributes() ?>>
<?php echo $tbrdm_issues->rgt->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $tbrdm_issues->is_private->CellAttributes() ?>>
<span id="el<?php echo $tbrdm_issues_delete->RowCnt ?>_tbrdm_issues_is_private" class="control-group tbrdm_issues_is_private">
<span<?php echo $tbrdm_issues->is_private->ViewAttributes() ?>>
<?php echo $tbrdm_issues->is_private->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$tbrdm_issues_delete->Recordset->MoveNext();
}
$tbrdm_issues_delete->Recordset->Close();
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
ftbrdm_issuesdelete.Init();
</script>
<?php
$tbrdm_issues_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_issues_delete->Page_Terminate();
?>
