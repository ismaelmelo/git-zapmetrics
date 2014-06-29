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

$tbrdm_issues_add = NULL; // Initialize page object first

class ctbrdm_issues_add extends ctbrdm_issues {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{EC1DE12C-8807-4BF7-B5F7-28BA138CD7FC}";

	// Table name
	var $TableName = 'tbrdm_issues';

	// Page object name
	var $PageObjName = 'tbrdm_issues_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("tbrdm_issueslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("tbrdm_issueslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbrdm_issuesview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->tracker_id->CurrentValue = NULL;
		$this->tracker_id->OldValue = $this->tracker_id->CurrentValue;
		$this->project_id->CurrentValue = NULL;
		$this->project_id->OldValue = $this->project_id->CurrentValue;
		$this->subject->CurrentValue = NULL;
		$this->subject->OldValue = $this->subject->CurrentValue;
		$this->due_date->CurrentValue = NULL;
		$this->due_date->OldValue = $this->due_date->CurrentValue;
		$this->category_id->CurrentValue = NULL;
		$this->category_id->OldValue = $this->category_id->CurrentValue;
		$this->status_id->CurrentValue = NULL;
		$this->status_id->OldValue = $this->status_id->CurrentValue;
		$this->assigned_to_id->CurrentValue = NULL;
		$this->assigned_to_id->OldValue = $this->assigned_to_id->CurrentValue;
		$this->priority_id->CurrentValue = NULL;
		$this->priority_id->OldValue = $this->priority_id->CurrentValue;
		$this->fixed_version_id->CurrentValue = NULL;
		$this->fixed_version_id->OldValue = $this->fixed_version_id->CurrentValue;
		$this->author_id->CurrentValue = NULL;
		$this->author_id->OldValue = $this->author_id->CurrentValue;
		$this->lock_version->CurrentValue = NULL;
		$this->lock_version->OldValue = $this->lock_version->CurrentValue;
		$this->created_on->CurrentValue = NULL;
		$this->created_on->OldValue = $this->created_on->CurrentValue;
		$this->updated_on->CurrentValue = NULL;
		$this->updated_on->OldValue = $this->updated_on->CurrentValue;
		$this->start_date->CurrentValue = NULL;
		$this->start_date->OldValue = $this->start_date->CurrentValue;
		$this->done_ratio->CurrentValue = NULL;
		$this->done_ratio->OldValue = $this->done_ratio->CurrentValue;
		$this->estimated_hours->CurrentValue = NULL;
		$this->estimated_hours->OldValue = $this->estimated_hours->CurrentValue;
		$this->parent_id->CurrentValue = NULL;
		$this->parent_id->OldValue = $this->parent_id->CurrentValue;
		$this->root_id->CurrentValue = NULL;
		$this->root_id->OldValue = $this->root_id->CurrentValue;
		$this->is_private->CurrentValue = NULL;
		$this->is_private->OldValue = $this->is_private->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->tracker_id->FldIsDetailKey) {
			$this->tracker_id->setFormValue($objForm->GetValue("x_tracker_id"));
		}
		if (!$this->project_id->FldIsDetailKey) {
			$this->project_id->setFormValue($objForm->GetValue("x_project_id"));
		}
		if (!$this->subject->FldIsDetailKey) {
			$this->subject->setFormValue($objForm->GetValue("x_subject"));
		}
		if (!$this->due_date->FldIsDetailKey) {
			$this->due_date->setFormValue($objForm->GetValue("x_due_date"));
			$this->due_date->CurrentValue = ew_UnFormatDateTime($this->due_date->CurrentValue, 7);
		}
		if (!$this->category_id->FldIsDetailKey) {
			$this->category_id->setFormValue($objForm->GetValue("x_category_id"));
		}
		if (!$this->status_id->FldIsDetailKey) {
			$this->status_id->setFormValue($objForm->GetValue("x_status_id"));
		}
		if (!$this->assigned_to_id->FldIsDetailKey) {
			$this->assigned_to_id->setFormValue($objForm->GetValue("x_assigned_to_id"));
		}
		if (!$this->priority_id->FldIsDetailKey) {
			$this->priority_id->setFormValue($objForm->GetValue("x_priority_id"));
		}
		if (!$this->fixed_version_id->FldIsDetailKey) {
			$this->fixed_version_id->setFormValue($objForm->GetValue("x_fixed_version_id"));
		}
		if (!$this->author_id->FldIsDetailKey) {
			$this->author_id->setFormValue($objForm->GetValue("x_author_id"));
		}
		if (!$this->lock_version->FldIsDetailKey) {
			$this->lock_version->setFormValue($objForm->GetValue("x_lock_version"));
		}
		if (!$this->created_on->FldIsDetailKey) {
			$this->created_on->setFormValue($objForm->GetValue("x_created_on"));
			$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		}
		if (!$this->updated_on->FldIsDetailKey) {
			$this->updated_on->setFormValue($objForm->GetValue("x_updated_on"));
			$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
		}
		if (!$this->start_date->FldIsDetailKey) {
			$this->start_date->setFormValue($objForm->GetValue("x_start_date"));
			$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 7);
		}
		if (!$this->done_ratio->FldIsDetailKey) {
			$this->done_ratio->setFormValue($objForm->GetValue("x_done_ratio"));
		}
		if (!$this->estimated_hours->FldIsDetailKey) {
			$this->estimated_hours->setFormValue($objForm->GetValue("x_estimated_hours"));
		}
		if (!$this->parent_id->FldIsDetailKey) {
			$this->parent_id->setFormValue($objForm->GetValue("x_parent_id"));
		}
		if (!$this->root_id->FldIsDetailKey) {
			$this->root_id->setFormValue($objForm->GetValue("x_root_id"));
		}
		if (!$this->is_private->FldIsDetailKey) {
			$this->is_private->setFormValue($objForm->GetValue("x_is_private"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->tracker_id->CurrentValue = $this->tracker_id->FormValue;
		$this->project_id->CurrentValue = $this->project_id->FormValue;
		$this->subject->CurrentValue = $this->subject->FormValue;
		$this->due_date->CurrentValue = $this->due_date->FormValue;
		$this->due_date->CurrentValue = ew_UnFormatDateTime($this->due_date->CurrentValue, 7);
		$this->category_id->CurrentValue = $this->category_id->FormValue;
		$this->status_id->CurrentValue = $this->status_id->FormValue;
		$this->assigned_to_id->CurrentValue = $this->assigned_to_id->FormValue;
		$this->priority_id->CurrentValue = $this->priority_id->FormValue;
		$this->fixed_version_id->CurrentValue = $this->fixed_version_id->FormValue;
		$this->author_id->CurrentValue = $this->author_id->FormValue;
		$this->lock_version->CurrentValue = $this->lock_version->FormValue;
		$this->created_on->CurrentValue = $this->created_on->FormValue;
		$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		$this->updated_on->CurrentValue = $this->updated_on->FormValue;
		$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
		$this->start_date->CurrentValue = $this->start_date->FormValue;
		$this->start_date->CurrentValue = ew_UnFormatDateTime($this->start_date->CurrentValue, 7);
		$this->done_ratio->CurrentValue = $this->done_ratio->FormValue;
		$this->estimated_hours->CurrentValue = $this->estimated_hours->FormValue;
		$this->parent_id->CurrentValue = $this->parent_id->FormValue;
		$this->root_id->CurrentValue = $this->root_id->FormValue;
		$this->is_private->CurrentValue = $this->is_private->FormValue;
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
		$this->is_private->DbValue = $row['is_private'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->estimated_hours->FormValue == $this->estimated_hours->CurrentValue && is_numeric(ew_StrToFloat($this->estimated_hours->CurrentValue)))
			$this->estimated_hours->CurrentValue = ew_StrToFloat($this->estimated_hours->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// tracker_id
		// project_id
		// subject
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

			// is_private
			$this->is_private->LinkCustomAttributes = "";
			$this->is_private->HrefValue = "";
			$this->is_private->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// tracker_id
			$this->tracker_id->EditCustomAttributes = "";
			$this->tracker_id->EditValue = ew_HtmlEncode($this->tracker_id->CurrentValue);
			$this->tracker_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tracker_id->FldCaption()));

			// project_id
			$this->project_id->EditCustomAttributes = "";
			$this->project_id->EditValue = ew_HtmlEncode($this->project_id->CurrentValue);
			$this->project_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->project_id->FldCaption()));

			// subject
			$this->subject->EditCustomAttributes = "";
			$this->subject->EditValue = ew_HtmlEncode($this->subject->CurrentValue);
			$this->subject->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->subject->FldCaption()));

			// due_date
			$this->due_date->EditCustomAttributes = "";
			$this->due_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->due_date->CurrentValue, 7));
			$this->due_date->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->due_date->FldCaption()));

			// category_id
			$this->category_id->EditCustomAttributes = "";
			$this->category_id->EditValue = ew_HtmlEncode($this->category_id->CurrentValue);
			$this->category_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->category_id->FldCaption()));

			// status_id
			$this->status_id->EditCustomAttributes = "";
			$this->status_id->EditValue = ew_HtmlEncode($this->status_id->CurrentValue);
			$this->status_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->status_id->FldCaption()));

			// assigned_to_id
			$this->assigned_to_id->EditCustomAttributes = "";
			$this->assigned_to_id->EditValue = ew_HtmlEncode($this->assigned_to_id->CurrentValue);
			$this->assigned_to_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->assigned_to_id->FldCaption()));

			// priority_id
			$this->priority_id->EditCustomAttributes = "";
			$this->priority_id->EditValue = ew_HtmlEncode($this->priority_id->CurrentValue);
			$this->priority_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->priority_id->FldCaption()));

			// fixed_version_id
			$this->fixed_version_id->EditCustomAttributes = "";
			$this->fixed_version_id->EditValue = ew_HtmlEncode($this->fixed_version_id->CurrentValue);
			$this->fixed_version_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->fixed_version_id->FldCaption()));

			// author_id
			$this->author_id->EditCustomAttributes = "";
			$this->author_id->EditValue = ew_HtmlEncode($this->author_id->CurrentValue);
			$this->author_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->author_id->FldCaption()));

			// lock_version
			$this->lock_version->EditCustomAttributes = "";
			$this->lock_version->EditValue = ew_HtmlEncode($this->lock_version->CurrentValue);
			$this->lock_version->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->lock_version->FldCaption()));

			// created_on
			$this->created_on->EditCustomAttributes = "";
			$this->created_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created_on->CurrentValue, 7));
			$this->created_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->created_on->FldCaption()));

			// updated_on
			$this->updated_on->EditCustomAttributes = "";
			$this->updated_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->updated_on->CurrentValue, 7));
			$this->updated_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->updated_on->FldCaption()));

			// start_date
			$this->start_date->EditCustomAttributes = "";
			$this->start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->start_date->CurrentValue, 7));
			$this->start_date->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->start_date->FldCaption()));

			// done_ratio
			$this->done_ratio->EditCustomAttributes = "";
			$this->done_ratio->EditValue = ew_HtmlEncode($this->done_ratio->CurrentValue);
			$this->done_ratio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->done_ratio->FldCaption()));

			// estimated_hours
			$this->estimated_hours->EditCustomAttributes = "";
			$this->estimated_hours->EditValue = ew_HtmlEncode($this->estimated_hours->CurrentValue);
			$this->estimated_hours->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->estimated_hours->FldCaption()));
			if (strval($this->estimated_hours->EditValue) <> "" && is_numeric($this->estimated_hours->EditValue)) $this->estimated_hours->EditValue = ew_FormatNumber($this->estimated_hours->EditValue, -2, -1, -2, 0);

			// parent_id
			$this->parent_id->EditCustomAttributes = "";
			$this->parent_id->EditValue = ew_HtmlEncode($this->parent_id->CurrentValue);
			$this->parent_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->parent_id->FldCaption()));

			// root_id
			$this->root_id->EditCustomAttributes = "";
			$this->root_id->EditValue = ew_HtmlEncode($this->root_id->CurrentValue);
			$this->root_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->root_id->FldCaption()));

			// is_private
			$this->is_private->EditCustomAttributes = "";
			$this->is_private->EditValue = ew_HtmlEncode($this->is_private->CurrentValue);
			$this->is_private->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->is_private->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// tracker_id
			$this->tracker_id->HrefValue = "";

			// project_id
			$this->project_id->HrefValue = "";

			// subject
			$this->subject->HrefValue = "";

			// due_date
			$this->due_date->HrefValue = "";

			// category_id
			$this->category_id->HrefValue = "";

			// status_id
			$this->status_id->HrefValue = "";

			// assigned_to_id
			$this->assigned_to_id->HrefValue = "";

			// priority_id
			$this->priority_id->HrefValue = "";

			// fixed_version_id
			$this->fixed_version_id->HrefValue = "";

			// author_id
			$this->author_id->HrefValue = "";

			// lock_version
			$this->lock_version->HrefValue = "";

			// created_on
			$this->created_on->HrefValue = "";

			// updated_on
			$this->updated_on->HrefValue = "";

			// start_date
			$this->start_date->HrefValue = "";

			// done_ratio
			$this->done_ratio->HrefValue = "";

			// estimated_hours
			$this->estimated_hours->HrefValue = "";

			// parent_id
			$this->parent_id->HrefValue = "";

			// root_id
			$this->root_id->HrefValue = "";

			// is_private
			$this->is_private->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->id->FldIsDetailKey && !is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id->FldCaption());
		}
		if (!ew_CheckInteger($this->id->FormValue)) {
			ew_AddMessage($gsFormError, $this->id->FldErrMsg());
		}
		if (!$this->tracker_id->FldIsDetailKey && !is_null($this->tracker_id->FormValue) && $this->tracker_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tracker_id->FldCaption());
		}
		if (!ew_CheckInteger($this->tracker_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->tracker_id->FldErrMsg());
		}
		if (!$this->project_id->FldIsDetailKey && !is_null($this->project_id->FormValue) && $this->project_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->project_id->FldCaption());
		}
		if (!ew_CheckInteger($this->project_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->project_id->FldErrMsg());
		}
		if (!$this->subject->FldIsDetailKey && !is_null($this->subject->FormValue) && $this->subject->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->subject->FldCaption());
		}
		if (!ew_CheckEuroDate($this->due_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->due_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->category_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->category_id->FldErrMsg());
		}
		if (!$this->status_id->FldIsDetailKey && !is_null($this->status_id->FormValue) && $this->status_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->status_id->FldCaption());
		}
		if (!ew_CheckInteger($this->status_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->status_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->assigned_to_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->assigned_to_id->FldErrMsg());
		}
		if (!$this->priority_id->FldIsDetailKey && !is_null($this->priority_id->FormValue) && $this->priority_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->priority_id->FldCaption());
		}
		if (!ew_CheckInteger($this->priority_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->priority_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->fixed_version_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->fixed_version_id->FldErrMsg());
		}
		if (!$this->author_id->FldIsDetailKey && !is_null($this->author_id->FormValue) && $this->author_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->author_id->FldCaption());
		}
		if (!ew_CheckInteger($this->author_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->author_id->FldErrMsg());
		}
		if (!$this->lock_version->FldIsDetailKey && !is_null($this->lock_version->FormValue) && $this->lock_version->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->lock_version->FldCaption());
		}
		if (!ew_CheckInteger($this->lock_version->FormValue)) {
			ew_AddMessage($gsFormError, $this->lock_version->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->created_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->created_on->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->updated_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->updated_on->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->start_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->start_date->FldErrMsg());
		}
		if (!$this->done_ratio->FldIsDetailKey && !is_null($this->done_ratio->FormValue) && $this->done_ratio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->done_ratio->FldCaption());
		}
		if (!ew_CheckInteger($this->done_ratio->FormValue)) {
			ew_AddMessage($gsFormError, $this->done_ratio->FldErrMsg());
		}
		if (!ew_CheckNumber($this->estimated_hours->FormValue)) {
			ew_AddMessage($gsFormError, $this->estimated_hours->FldErrMsg());
		}
		if (!ew_CheckInteger($this->parent_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->parent_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->root_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->root_id->FldErrMsg());
		}
		if (!$this->is_private->FldIsDetailKey && !is_null($this->is_private->FormValue) && $this->is_private->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->is_private->FldCaption());
		}
		if (!ew_CheckInteger($this->is_private->FormValue)) {
			ew_AddMessage($gsFormError, $this->is_private->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		if ($this->id->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(id = " . ew_AdjustSql($this->id->CurrentValue) . ")";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->id->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->id->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// id
		$this->id->SetDbValueDef($rsnew, $this->id->CurrentValue, 0, FALSE);

		// tracker_id
		$this->tracker_id->SetDbValueDef($rsnew, $this->tracker_id->CurrentValue, 0, FALSE);

		// project_id
		$this->project_id->SetDbValueDef($rsnew, $this->project_id->CurrentValue, 0, FALSE);

		// subject
		$this->subject->SetDbValueDef($rsnew, $this->subject->CurrentValue, "", FALSE);

		// due_date
		$this->due_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->due_date->CurrentValue, 7), NULL, FALSE);

		// category_id
		$this->category_id->SetDbValueDef($rsnew, $this->category_id->CurrentValue, NULL, FALSE);

		// status_id
		$this->status_id->SetDbValueDef($rsnew, $this->status_id->CurrentValue, 0, FALSE);

		// assigned_to_id
		$this->assigned_to_id->SetDbValueDef($rsnew, $this->assigned_to_id->CurrentValue, NULL, FALSE);

		// priority_id
		$this->priority_id->SetDbValueDef($rsnew, $this->priority_id->CurrentValue, 0, FALSE);

		// fixed_version_id
		$this->fixed_version_id->SetDbValueDef($rsnew, $this->fixed_version_id->CurrentValue, NULL, FALSE);

		// author_id
		$this->author_id->SetDbValueDef($rsnew, $this->author_id->CurrentValue, 0, FALSE);

		// lock_version
		$this->lock_version->SetDbValueDef($rsnew, $this->lock_version->CurrentValue, 0, FALSE);

		// created_on
		$this->created_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created_on->CurrentValue, 7), NULL, FALSE);

		// updated_on
		$this->updated_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->updated_on->CurrentValue, 7), NULL, FALSE);

		// start_date
		$this->start_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->start_date->CurrentValue, 7), NULL, FALSE);

		// done_ratio
		$this->done_ratio->SetDbValueDef($rsnew, $this->done_ratio->CurrentValue, 0, FALSE);

		// estimated_hours
		$this->estimated_hours->SetDbValueDef($rsnew, $this->estimated_hours->CurrentValue, NULL, FALSE);

		// parent_id
		$this->parent_id->SetDbValueDef($rsnew, $this->parent_id->CurrentValue, NULL, FALSE);

		// root_id
		$this->root_id->SetDbValueDef($rsnew, $this->root_id->CurrentValue, NULL, FALSE);

		// is_private
		$this->is_private->SetDbValueDef($rsnew, $this->is_private->CurrentValue, 0, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->id->CurrentValue == "" && $this->id->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_issueslist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tbrdm_issues_add)) $tbrdm_issues_add = new ctbrdm_issues_add();

// Page init
$tbrdm_issues_add->Page_Init();

// Page main
$tbrdm_issues_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_issues_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_issues_add = new ew_Page("tbrdm_issues_add");
tbrdm_issues_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbrdm_issues_add.PageID; // For backward compatibility

// Form object
var ftbrdm_issuesadd = new ew_Form("ftbrdm_issuesadd");

// Validate form
ftbrdm_issuesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tracker_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->tracker_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tracker_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->tracker_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_project_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->project_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_project_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->project_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_subject");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->subject->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_due_date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->due_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_category_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->category_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->status_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_status_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->status_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_assigned_to_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->assigned_to_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_priority_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->priority_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_priority_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->priority_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_fixed_version_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->fixed_version_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_author_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->author_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_author_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->author_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lock_version");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->lock_version->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_lock_version");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->lock_version->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_created_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->created_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_updated_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->updated_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_start_date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->start_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_done_ratio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->done_ratio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_done_ratio");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->done_ratio->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_estimated_hours");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->estimated_hours->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_parent_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->parent_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_root_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->root_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_is_private");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_issues->is_private->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_is_private");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_issues->is_private->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ftbrdm_issuesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_issuesadd.ValidateRequired = true;
<?php } else { ?>
ftbrdm_issuesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_issues_add->ShowPageHeader(); ?>
<?php
$tbrdm_issues_add->ShowMessage();
?>
<form name="ftbrdm_issuesadd" id="ftbrdm_issuesadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_issues">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tbrdm_issuesadd" class="table table-bordered table-striped">
<?php if ($tbrdm_issues->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbrdm_issues_id"><?php echo $tbrdm_issues->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->id->CellAttributes() ?>>
<span id="el_tbrdm_issues_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $tbrdm_issues->id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->id->EditValue ?>"<?php echo $tbrdm_issues->id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->tracker_id->Visible) { // tracker_id ?>
	<tr id="r_tracker_id">
		<td><span id="elh_tbrdm_issues_tracker_id"><?php echo $tbrdm_issues->tracker_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->tracker_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_tracker_id" class="control-group">
<input type="text" data-field="x_tracker_id" name="x_tracker_id" id="x_tracker_id" size="30" placeholder="<?php echo $tbrdm_issues->tracker_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->tracker_id->EditValue ?>"<?php echo $tbrdm_issues->tracker_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->tracker_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->project_id->Visible) { // project_id ?>
	<tr id="r_project_id">
		<td><span id="elh_tbrdm_issues_project_id"><?php echo $tbrdm_issues->project_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->project_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_project_id" class="control-group">
<input type="text" data-field="x_project_id" name="x_project_id" id="x_project_id" size="30" placeholder="<?php echo $tbrdm_issues->project_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->project_id->EditValue ?>"<?php echo $tbrdm_issues->project_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->project_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->subject->Visible) { // subject ?>
	<tr id="r_subject">
		<td><span id="elh_tbrdm_issues_subject"><?php echo $tbrdm_issues->subject->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->subject->CellAttributes() ?>>
<span id="el_tbrdm_issues_subject" class="control-group">
<input type="text" data-field="x_subject" name="x_subject" id="x_subject" size="30" maxlength="255" placeholder="<?php echo $tbrdm_issues->subject->PlaceHolder ?>" value="<?php echo $tbrdm_issues->subject->EditValue ?>"<?php echo $tbrdm_issues->subject->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->subject->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->due_date->Visible) { // due_date ?>
	<tr id="r_due_date">
		<td><span id="elh_tbrdm_issues_due_date"><?php echo $tbrdm_issues->due_date->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->due_date->CellAttributes() ?>>
<span id="el_tbrdm_issues_due_date" class="control-group">
<input type="text" data-field="x_due_date" name="x_due_date" id="x_due_date" placeholder="<?php echo $tbrdm_issues->due_date->PlaceHolder ?>" value="<?php echo $tbrdm_issues->due_date->EditValue ?>"<?php echo $tbrdm_issues->due_date->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->due_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->category_id->Visible) { // category_id ?>
	<tr id="r_category_id">
		<td><span id="elh_tbrdm_issues_category_id"><?php echo $tbrdm_issues->category_id->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->category_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_category_id" class="control-group">
<input type="text" data-field="x_category_id" name="x_category_id" id="x_category_id" size="30" placeholder="<?php echo $tbrdm_issues->category_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->category_id->EditValue ?>"<?php echo $tbrdm_issues->category_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->category_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->status_id->Visible) { // status_id ?>
	<tr id="r_status_id">
		<td><span id="elh_tbrdm_issues_status_id"><?php echo $tbrdm_issues->status_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->status_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_status_id" class="control-group">
<input type="text" data-field="x_status_id" name="x_status_id" id="x_status_id" size="30" placeholder="<?php echo $tbrdm_issues->status_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->status_id->EditValue ?>"<?php echo $tbrdm_issues->status_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->status_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->assigned_to_id->Visible) { // assigned_to_id ?>
	<tr id="r_assigned_to_id">
		<td><span id="elh_tbrdm_issues_assigned_to_id"><?php echo $tbrdm_issues->assigned_to_id->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->assigned_to_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_assigned_to_id" class="control-group">
<input type="text" data-field="x_assigned_to_id" name="x_assigned_to_id" id="x_assigned_to_id" size="30" placeholder="<?php echo $tbrdm_issues->assigned_to_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->assigned_to_id->EditValue ?>"<?php echo $tbrdm_issues->assigned_to_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->assigned_to_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->priority_id->Visible) { // priority_id ?>
	<tr id="r_priority_id">
		<td><span id="elh_tbrdm_issues_priority_id"><?php echo $tbrdm_issues->priority_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->priority_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_priority_id" class="control-group">
<input type="text" data-field="x_priority_id" name="x_priority_id" id="x_priority_id" size="30" placeholder="<?php echo $tbrdm_issues->priority_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->priority_id->EditValue ?>"<?php echo $tbrdm_issues->priority_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->priority_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->fixed_version_id->Visible) { // fixed_version_id ?>
	<tr id="r_fixed_version_id">
		<td><span id="elh_tbrdm_issues_fixed_version_id"><?php echo $tbrdm_issues->fixed_version_id->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->fixed_version_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_fixed_version_id" class="control-group">
<input type="text" data-field="x_fixed_version_id" name="x_fixed_version_id" id="x_fixed_version_id" size="30" placeholder="<?php echo $tbrdm_issues->fixed_version_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->fixed_version_id->EditValue ?>"<?php echo $tbrdm_issues->fixed_version_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->fixed_version_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->author_id->Visible) { // author_id ?>
	<tr id="r_author_id">
		<td><span id="elh_tbrdm_issues_author_id"><?php echo $tbrdm_issues->author_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->author_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_author_id" class="control-group">
<input type="text" data-field="x_author_id" name="x_author_id" id="x_author_id" size="30" placeholder="<?php echo $tbrdm_issues->author_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->author_id->EditValue ?>"<?php echo $tbrdm_issues->author_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->author_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->lock_version->Visible) { // lock_version ?>
	<tr id="r_lock_version">
		<td><span id="elh_tbrdm_issues_lock_version"><?php echo $tbrdm_issues->lock_version->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->lock_version->CellAttributes() ?>>
<span id="el_tbrdm_issues_lock_version" class="control-group">
<input type="text" data-field="x_lock_version" name="x_lock_version" id="x_lock_version" size="30" placeholder="<?php echo $tbrdm_issues->lock_version->PlaceHolder ?>" value="<?php echo $tbrdm_issues->lock_version->EditValue ?>"<?php echo $tbrdm_issues->lock_version->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->lock_version->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->created_on->Visible) { // created_on ?>
	<tr id="r_created_on">
		<td><span id="elh_tbrdm_issues_created_on"><?php echo $tbrdm_issues->created_on->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->created_on->CellAttributes() ?>>
<span id="el_tbrdm_issues_created_on" class="control-group">
<input type="text" data-field="x_created_on" name="x_created_on" id="x_created_on" placeholder="<?php echo $tbrdm_issues->created_on->PlaceHolder ?>" value="<?php echo $tbrdm_issues->created_on->EditValue ?>"<?php echo $tbrdm_issues->created_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->created_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->updated_on->Visible) { // updated_on ?>
	<tr id="r_updated_on">
		<td><span id="elh_tbrdm_issues_updated_on"><?php echo $tbrdm_issues->updated_on->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->updated_on->CellAttributes() ?>>
<span id="el_tbrdm_issues_updated_on" class="control-group">
<input type="text" data-field="x_updated_on" name="x_updated_on" id="x_updated_on" placeholder="<?php echo $tbrdm_issues->updated_on->PlaceHolder ?>" value="<?php echo $tbrdm_issues->updated_on->EditValue ?>"<?php echo $tbrdm_issues->updated_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->updated_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->start_date->Visible) { // start_date ?>
	<tr id="r_start_date">
		<td><span id="elh_tbrdm_issues_start_date"><?php echo $tbrdm_issues->start_date->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->start_date->CellAttributes() ?>>
<span id="el_tbrdm_issues_start_date" class="control-group">
<input type="text" data-field="x_start_date" name="x_start_date" id="x_start_date" placeholder="<?php echo $tbrdm_issues->start_date->PlaceHolder ?>" value="<?php echo $tbrdm_issues->start_date->EditValue ?>"<?php echo $tbrdm_issues->start_date->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->start_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->done_ratio->Visible) { // done_ratio ?>
	<tr id="r_done_ratio">
		<td><span id="elh_tbrdm_issues_done_ratio"><?php echo $tbrdm_issues->done_ratio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->done_ratio->CellAttributes() ?>>
<span id="el_tbrdm_issues_done_ratio" class="control-group">
<input type="text" data-field="x_done_ratio" name="x_done_ratio" id="x_done_ratio" size="30" placeholder="<?php echo $tbrdm_issues->done_ratio->PlaceHolder ?>" value="<?php echo $tbrdm_issues->done_ratio->EditValue ?>"<?php echo $tbrdm_issues->done_ratio->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->done_ratio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->estimated_hours->Visible) { // estimated_hours ?>
	<tr id="r_estimated_hours">
		<td><span id="elh_tbrdm_issues_estimated_hours"><?php echo $tbrdm_issues->estimated_hours->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->estimated_hours->CellAttributes() ?>>
<span id="el_tbrdm_issues_estimated_hours" class="control-group">
<input type="text" data-field="x_estimated_hours" name="x_estimated_hours" id="x_estimated_hours" size="30" placeholder="<?php echo $tbrdm_issues->estimated_hours->PlaceHolder ?>" value="<?php echo $tbrdm_issues->estimated_hours->EditValue ?>"<?php echo $tbrdm_issues->estimated_hours->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->estimated_hours->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->parent_id->Visible) { // parent_id ?>
	<tr id="r_parent_id">
		<td><span id="elh_tbrdm_issues_parent_id"><?php echo $tbrdm_issues->parent_id->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->parent_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_parent_id" class="control-group">
<input type="text" data-field="x_parent_id" name="x_parent_id" id="x_parent_id" size="30" placeholder="<?php echo $tbrdm_issues->parent_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->parent_id->EditValue ?>"<?php echo $tbrdm_issues->parent_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->parent_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->root_id->Visible) { // root_id ?>
	<tr id="r_root_id">
		<td><span id="elh_tbrdm_issues_root_id"><?php echo $tbrdm_issues->root_id->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_issues->root_id->CellAttributes() ?>>
<span id="el_tbrdm_issues_root_id" class="control-group">
<input type="text" data-field="x_root_id" name="x_root_id" id="x_root_id" size="30" placeholder="<?php echo $tbrdm_issues->root_id->PlaceHolder ?>" value="<?php echo $tbrdm_issues->root_id->EditValue ?>"<?php echo $tbrdm_issues->root_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->root_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_issues->is_private->Visible) { // is_private ?>
	<tr id="r_is_private">
		<td><span id="elh_tbrdm_issues_is_private"><?php echo $tbrdm_issues->is_private->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_issues->is_private->CellAttributes() ?>>
<span id="el_tbrdm_issues_is_private" class="control-group">
<input type="text" data-field="x_is_private" name="x_is_private" id="x_is_private" size="30" placeholder="<?php echo $tbrdm_issues->is_private->PlaceHolder ?>" value="<?php echo $tbrdm_issues->is_private->EditValue ?>"<?php echo $tbrdm_issues->is_private->EditAttributes() ?>>
</span>
<?php echo $tbrdm_issues->is_private->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftbrdm_issuesadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tbrdm_issues_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_issues_add->Page_Terminate();
?>
