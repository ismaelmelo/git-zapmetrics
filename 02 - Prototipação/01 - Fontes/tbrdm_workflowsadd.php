<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_workflowsinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_workflows_add = NULL; // Initialize page object first

class ctbrdm_workflows_add extends ctbrdm_workflows {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{EC1DE12C-8807-4BF7-B5F7-28BA138CD7FC}";

	// Table name
	var $TableName = 'tbrdm_workflows';

	// Page object name
	var $PageObjName = 'tbrdm_workflows_add';

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

		// Table object (tbrdm_workflows)
		if (!isset($GLOBALS["tbrdm_workflows"])) {
			$GLOBALS["tbrdm_workflows"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_workflows"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_workflows', TRUE);

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
			$this->Page_Terminate("tbrdm_workflowslist.php");
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
					$this->Page_Terminate("tbrdm_workflowslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbrdm_workflowsview.php")
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
		$this->old_status_id->CurrentValue = NULL;
		$this->old_status_id->OldValue = $this->old_status_id->CurrentValue;
		$this->new_status_id->CurrentValue = NULL;
		$this->new_status_id->OldValue = $this->new_status_id->CurrentValue;
		$this->role_id->CurrentValue = NULL;
		$this->role_id->OldValue = $this->role_id->CurrentValue;
		$this->assignee->CurrentValue = NULL;
		$this->assignee->OldValue = $this->assignee->CurrentValue;
		$this->author->CurrentValue = NULL;
		$this->author->OldValue = $this->author->CurrentValue;
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
		if (!$this->old_status_id->FldIsDetailKey) {
			$this->old_status_id->setFormValue($objForm->GetValue("x_old_status_id"));
		}
		if (!$this->new_status_id->FldIsDetailKey) {
			$this->new_status_id->setFormValue($objForm->GetValue("x_new_status_id"));
		}
		if (!$this->role_id->FldIsDetailKey) {
			$this->role_id->setFormValue($objForm->GetValue("x_role_id"));
		}
		if (!$this->assignee->FldIsDetailKey) {
			$this->assignee->setFormValue($objForm->GetValue("x_assignee"));
		}
		if (!$this->author->FldIsDetailKey) {
			$this->author->setFormValue($objForm->GetValue("x_author"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->tracker_id->CurrentValue = $this->tracker_id->FormValue;
		$this->old_status_id->CurrentValue = $this->old_status_id->FormValue;
		$this->new_status_id->CurrentValue = $this->new_status_id->FormValue;
		$this->role_id->CurrentValue = $this->role_id->FormValue;
		$this->assignee->CurrentValue = $this->assignee->FormValue;
		$this->author->CurrentValue = $this->author->FormValue;
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
		$this->old_status_id->setDbValue($rs->fields('old_status_id'));
		$this->new_status_id->setDbValue($rs->fields('new_status_id'));
		$this->role_id->setDbValue($rs->fields('role_id'));
		$this->assignee->setDbValue($rs->fields('assignee'));
		$this->author->setDbValue($rs->fields('author'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->tracker_id->DbValue = $row['tracker_id'];
		$this->old_status_id->DbValue = $row['old_status_id'];
		$this->new_status_id->DbValue = $row['new_status_id'];
		$this->role_id->DbValue = $row['role_id'];
		$this->assignee->DbValue = $row['assignee'];
		$this->author->DbValue = $row['author'];
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
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// tracker_id
		// old_status_id
		// new_status_id
		// role_id
		// assignee
		// author

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// tracker_id
			$this->tracker_id->ViewValue = $this->tracker_id->CurrentValue;
			$this->tracker_id->ViewCustomAttributes = "";

			// old_status_id
			$this->old_status_id->ViewValue = $this->old_status_id->CurrentValue;
			$this->old_status_id->ViewCustomAttributes = "";

			// new_status_id
			$this->new_status_id->ViewValue = $this->new_status_id->CurrentValue;
			$this->new_status_id->ViewCustomAttributes = "";

			// role_id
			$this->role_id->ViewValue = $this->role_id->CurrentValue;
			$this->role_id->ViewCustomAttributes = "";

			// assignee
			$this->assignee->ViewValue = $this->assignee->CurrentValue;
			$this->assignee->ViewCustomAttributes = "";

			// author
			$this->author->ViewValue = $this->author->CurrentValue;
			$this->author->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// tracker_id
			$this->tracker_id->LinkCustomAttributes = "";
			$this->tracker_id->HrefValue = "";
			$this->tracker_id->TooltipValue = "";

			// old_status_id
			$this->old_status_id->LinkCustomAttributes = "";
			$this->old_status_id->HrefValue = "";
			$this->old_status_id->TooltipValue = "";

			// new_status_id
			$this->new_status_id->LinkCustomAttributes = "";
			$this->new_status_id->HrefValue = "";
			$this->new_status_id->TooltipValue = "";

			// role_id
			$this->role_id->LinkCustomAttributes = "";
			$this->role_id->HrefValue = "";
			$this->role_id->TooltipValue = "";

			// assignee
			$this->assignee->LinkCustomAttributes = "";
			$this->assignee->HrefValue = "";
			$this->assignee->TooltipValue = "";

			// author
			$this->author->LinkCustomAttributes = "";
			$this->author->HrefValue = "";
			$this->author->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// tracker_id
			$this->tracker_id->EditCustomAttributes = "";
			$this->tracker_id->EditValue = ew_HtmlEncode($this->tracker_id->CurrentValue);
			$this->tracker_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tracker_id->FldCaption()));

			// old_status_id
			$this->old_status_id->EditCustomAttributes = "";
			$this->old_status_id->EditValue = ew_HtmlEncode($this->old_status_id->CurrentValue);
			$this->old_status_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->old_status_id->FldCaption()));

			// new_status_id
			$this->new_status_id->EditCustomAttributes = "";
			$this->new_status_id->EditValue = ew_HtmlEncode($this->new_status_id->CurrentValue);
			$this->new_status_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->new_status_id->FldCaption()));

			// role_id
			$this->role_id->EditCustomAttributes = "";
			$this->role_id->EditValue = ew_HtmlEncode($this->role_id->CurrentValue);
			$this->role_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->role_id->FldCaption()));

			// assignee
			$this->assignee->EditCustomAttributes = "";
			$this->assignee->EditValue = ew_HtmlEncode($this->assignee->CurrentValue);
			$this->assignee->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->assignee->FldCaption()));

			// author
			$this->author->EditCustomAttributes = "";
			$this->author->EditValue = ew_HtmlEncode($this->author->CurrentValue);
			$this->author->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->author->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// tracker_id
			$this->tracker_id->HrefValue = "";

			// old_status_id
			$this->old_status_id->HrefValue = "";

			// new_status_id
			$this->new_status_id->HrefValue = "";

			// role_id
			$this->role_id->HrefValue = "";

			// assignee
			$this->assignee->HrefValue = "";

			// author
			$this->author->HrefValue = "";
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
		if (!$this->old_status_id->FldIsDetailKey && !is_null($this->old_status_id->FormValue) && $this->old_status_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->old_status_id->FldCaption());
		}
		if (!ew_CheckInteger($this->old_status_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->old_status_id->FldErrMsg());
		}
		if (!$this->new_status_id->FldIsDetailKey && !is_null($this->new_status_id->FormValue) && $this->new_status_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->new_status_id->FldCaption());
		}
		if (!ew_CheckInteger($this->new_status_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->new_status_id->FldErrMsg());
		}
		if (!$this->role_id->FldIsDetailKey && !is_null($this->role_id->FormValue) && $this->role_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->role_id->FldCaption());
		}
		if (!ew_CheckInteger($this->role_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->role_id->FldErrMsg());
		}
		if (!$this->assignee->FldIsDetailKey && !is_null($this->assignee->FormValue) && $this->assignee->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->assignee->FldCaption());
		}
		if (!ew_CheckInteger($this->assignee->FormValue)) {
			ew_AddMessage($gsFormError, $this->assignee->FldErrMsg());
		}
		if (!$this->author->FldIsDetailKey && !is_null($this->author->FormValue) && $this->author->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->author->FldCaption());
		}
		if (!ew_CheckInteger($this->author->FormValue)) {
			ew_AddMessage($gsFormError, $this->author->FldErrMsg());
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

		// old_status_id
		$this->old_status_id->SetDbValueDef($rsnew, $this->old_status_id->CurrentValue, 0, FALSE);

		// new_status_id
		$this->new_status_id->SetDbValueDef($rsnew, $this->new_status_id->CurrentValue, 0, FALSE);

		// role_id
		$this->role_id->SetDbValueDef($rsnew, $this->role_id->CurrentValue, 0, FALSE);

		// assignee
		$this->assignee->SetDbValueDef($rsnew, $this->assignee->CurrentValue, 0, FALSE);

		// author
		$this->author->SetDbValueDef($rsnew, $this->author->CurrentValue, 0, FALSE);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_workflowslist.php", $this->TableVar);
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
if (!isset($tbrdm_workflows_add)) $tbrdm_workflows_add = new ctbrdm_workflows_add();

// Page init
$tbrdm_workflows_add->Page_Init();

// Page main
$tbrdm_workflows_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_workflows_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_workflows_add = new ew_Page("tbrdm_workflows_add");
tbrdm_workflows_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbrdm_workflows_add.PageID; // For backward compatibility

// Form object
var ftbrdm_workflowsadd = new ew_Form("ftbrdm_workflowsadd");

// Validate form
ftbrdm_workflowsadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_workflows->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_workflows->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tracker_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_workflows->tracker_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tracker_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_workflows->tracker_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_old_status_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_workflows->old_status_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_old_status_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_workflows->old_status_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_new_status_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_workflows->new_status_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_new_status_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_workflows->new_status_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_role_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_workflows->role_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_role_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_workflows->role_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_assignee");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_workflows->assignee->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_assignee");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_workflows->assignee->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_author");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_workflows->author->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_author");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_workflows->author->FldErrMsg()) ?>");

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
ftbrdm_workflowsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_workflowsadd.ValidateRequired = true;
<?php } else { ?>
ftbrdm_workflowsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_workflows_add->ShowPageHeader(); ?>
<?php
$tbrdm_workflows_add->ShowMessage();
?>
<form name="ftbrdm_workflowsadd" id="ftbrdm_workflowsadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_workflows">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tbrdm_workflowsadd" class="table table-bordered table-striped">
<?php if ($tbrdm_workflows->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbrdm_workflows_id"><?php echo $tbrdm_workflows->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_workflows->id->CellAttributes() ?>>
<span id="el_tbrdm_workflows_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $tbrdm_workflows->id->PlaceHolder ?>" value="<?php echo $tbrdm_workflows->id->EditValue ?>"<?php echo $tbrdm_workflows->id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_workflows->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_workflows->tracker_id->Visible) { // tracker_id ?>
	<tr id="r_tracker_id">
		<td><span id="elh_tbrdm_workflows_tracker_id"><?php echo $tbrdm_workflows->tracker_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_workflows->tracker_id->CellAttributes() ?>>
<span id="el_tbrdm_workflows_tracker_id" class="control-group">
<input type="text" data-field="x_tracker_id" name="x_tracker_id" id="x_tracker_id" size="30" placeholder="<?php echo $tbrdm_workflows->tracker_id->PlaceHolder ?>" value="<?php echo $tbrdm_workflows->tracker_id->EditValue ?>"<?php echo $tbrdm_workflows->tracker_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_workflows->tracker_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_workflows->old_status_id->Visible) { // old_status_id ?>
	<tr id="r_old_status_id">
		<td><span id="elh_tbrdm_workflows_old_status_id"><?php echo $tbrdm_workflows->old_status_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_workflows->old_status_id->CellAttributes() ?>>
<span id="el_tbrdm_workflows_old_status_id" class="control-group">
<input type="text" data-field="x_old_status_id" name="x_old_status_id" id="x_old_status_id" size="30" placeholder="<?php echo $tbrdm_workflows->old_status_id->PlaceHolder ?>" value="<?php echo $tbrdm_workflows->old_status_id->EditValue ?>"<?php echo $tbrdm_workflows->old_status_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_workflows->old_status_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_workflows->new_status_id->Visible) { // new_status_id ?>
	<tr id="r_new_status_id">
		<td><span id="elh_tbrdm_workflows_new_status_id"><?php echo $tbrdm_workflows->new_status_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_workflows->new_status_id->CellAttributes() ?>>
<span id="el_tbrdm_workflows_new_status_id" class="control-group">
<input type="text" data-field="x_new_status_id" name="x_new_status_id" id="x_new_status_id" size="30" placeholder="<?php echo $tbrdm_workflows->new_status_id->PlaceHolder ?>" value="<?php echo $tbrdm_workflows->new_status_id->EditValue ?>"<?php echo $tbrdm_workflows->new_status_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_workflows->new_status_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_workflows->role_id->Visible) { // role_id ?>
	<tr id="r_role_id">
		<td><span id="elh_tbrdm_workflows_role_id"><?php echo $tbrdm_workflows->role_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_workflows->role_id->CellAttributes() ?>>
<span id="el_tbrdm_workflows_role_id" class="control-group">
<input type="text" data-field="x_role_id" name="x_role_id" id="x_role_id" size="30" placeholder="<?php echo $tbrdm_workflows->role_id->PlaceHolder ?>" value="<?php echo $tbrdm_workflows->role_id->EditValue ?>"<?php echo $tbrdm_workflows->role_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_workflows->role_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_workflows->assignee->Visible) { // assignee ?>
	<tr id="r_assignee">
		<td><span id="elh_tbrdm_workflows_assignee"><?php echo $tbrdm_workflows->assignee->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_workflows->assignee->CellAttributes() ?>>
<span id="el_tbrdm_workflows_assignee" class="control-group">
<input type="text" data-field="x_assignee" name="x_assignee" id="x_assignee" size="30" placeholder="<?php echo $tbrdm_workflows->assignee->PlaceHolder ?>" value="<?php echo $tbrdm_workflows->assignee->EditValue ?>"<?php echo $tbrdm_workflows->assignee->EditAttributes() ?>>
</span>
<?php echo $tbrdm_workflows->assignee->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_workflows->author->Visible) { // author ?>
	<tr id="r_author">
		<td><span id="elh_tbrdm_workflows_author"><?php echo $tbrdm_workflows->author->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_workflows->author->CellAttributes() ?>>
<span id="el_tbrdm_workflows_author" class="control-group">
<input type="text" data-field="x_author" name="x_author" id="x_author" size="30" placeholder="<?php echo $tbrdm_workflows->author->PlaceHolder ?>" value="<?php echo $tbrdm_workflows->author->EditValue ?>"<?php echo $tbrdm_workflows->author->EditAttributes() ?>>
</span>
<?php echo $tbrdm_workflows->author->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftbrdm_workflowsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tbrdm_workflows_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_workflows_add->Page_Terminate();
?>
