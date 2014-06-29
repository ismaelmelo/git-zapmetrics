<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_time_entriesinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_time_entries_add = NULL; // Initialize page object first

class ctbrdm_time_entries_add extends ctbrdm_time_entries {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{EC1DE12C-8807-4BF7-B5F7-28BA138CD7FC}";

	// Table name
	var $TableName = 'tbrdm_time_entries';

	// Page object name
	var $PageObjName = 'tbrdm_time_entries_add';

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

		// Table object (tbrdm_time_entries)
		if (!isset($GLOBALS["tbrdm_time_entries"])) {
			$GLOBALS["tbrdm_time_entries"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_time_entries"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_time_entries', TRUE);

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
			$this->Page_Terminate("tbrdm_time_entrieslist.php");
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
					$this->Page_Terminate("tbrdm_time_entrieslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbrdm_time_entriesview.php")
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
		$this->project_id->CurrentValue = NULL;
		$this->project_id->OldValue = $this->project_id->CurrentValue;
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->issue_id->CurrentValue = NULL;
		$this->issue_id->OldValue = $this->issue_id->CurrentValue;
		$this->hours->CurrentValue = NULL;
		$this->hours->OldValue = $this->hours->CurrentValue;
		$this->comments->CurrentValue = NULL;
		$this->comments->OldValue = $this->comments->CurrentValue;
		$this->activity_id->CurrentValue = NULL;
		$this->activity_id->OldValue = $this->activity_id->CurrentValue;
		$this->spent_on->CurrentValue = NULL;
		$this->spent_on->OldValue = $this->spent_on->CurrentValue;
		$this->tyear->CurrentValue = NULL;
		$this->tyear->OldValue = $this->tyear->CurrentValue;
		$this->tmonth->CurrentValue = NULL;
		$this->tmonth->OldValue = $this->tmonth->CurrentValue;
		$this->tweek->CurrentValue = NULL;
		$this->tweek->OldValue = $this->tweek->CurrentValue;
		$this->created_on->CurrentValue = NULL;
		$this->created_on->OldValue = $this->created_on->CurrentValue;
		$this->updated_on->CurrentValue = NULL;
		$this->updated_on->OldValue = $this->updated_on->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->project_id->FldIsDetailKey) {
			$this->project_id->setFormValue($objForm->GetValue("x_project_id"));
		}
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->issue_id->FldIsDetailKey) {
			$this->issue_id->setFormValue($objForm->GetValue("x_issue_id"));
		}
		if (!$this->hours->FldIsDetailKey) {
			$this->hours->setFormValue($objForm->GetValue("x_hours"));
		}
		if (!$this->comments->FldIsDetailKey) {
			$this->comments->setFormValue($objForm->GetValue("x_comments"));
		}
		if (!$this->activity_id->FldIsDetailKey) {
			$this->activity_id->setFormValue($objForm->GetValue("x_activity_id"));
		}
		if (!$this->spent_on->FldIsDetailKey) {
			$this->spent_on->setFormValue($objForm->GetValue("x_spent_on"));
			$this->spent_on->CurrentValue = ew_UnFormatDateTime($this->spent_on->CurrentValue, 7);
		}
		if (!$this->tyear->FldIsDetailKey) {
			$this->tyear->setFormValue($objForm->GetValue("x_tyear"));
		}
		if (!$this->tmonth->FldIsDetailKey) {
			$this->tmonth->setFormValue($objForm->GetValue("x_tmonth"));
		}
		if (!$this->tweek->FldIsDetailKey) {
			$this->tweek->setFormValue($objForm->GetValue("x_tweek"));
		}
		if (!$this->created_on->FldIsDetailKey) {
			$this->created_on->setFormValue($objForm->GetValue("x_created_on"));
			$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		}
		if (!$this->updated_on->FldIsDetailKey) {
			$this->updated_on->setFormValue($objForm->GetValue("x_updated_on"));
			$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->project_id->CurrentValue = $this->project_id->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->issue_id->CurrentValue = $this->issue_id->FormValue;
		$this->hours->CurrentValue = $this->hours->FormValue;
		$this->comments->CurrentValue = $this->comments->FormValue;
		$this->activity_id->CurrentValue = $this->activity_id->FormValue;
		$this->spent_on->CurrentValue = $this->spent_on->FormValue;
		$this->spent_on->CurrentValue = ew_UnFormatDateTime($this->spent_on->CurrentValue, 7);
		$this->tyear->CurrentValue = $this->tyear->FormValue;
		$this->tmonth->CurrentValue = $this->tmonth->FormValue;
		$this->tweek->CurrentValue = $this->tweek->FormValue;
		$this->created_on->CurrentValue = $this->created_on->FormValue;
		$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		$this->updated_on->CurrentValue = $this->updated_on->FormValue;
		$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
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
		$this->project_id->setDbValue($rs->fields('project_id'));
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->issue_id->setDbValue($rs->fields('issue_id'));
		$this->hours->setDbValue($rs->fields('hours'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->activity_id->setDbValue($rs->fields('activity_id'));
		$this->spent_on->setDbValue($rs->fields('spent_on'));
		$this->tyear->setDbValue($rs->fields('tyear'));
		$this->tmonth->setDbValue($rs->fields('tmonth'));
		$this->tweek->setDbValue($rs->fields('tweek'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->project_id->DbValue = $row['project_id'];
		$this->user_id->DbValue = $row['user_id'];
		$this->issue_id->DbValue = $row['issue_id'];
		$this->hours->DbValue = $row['hours'];
		$this->comments->DbValue = $row['comments'];
		$this->activity_id->DbValue = $row['activity_id'];
		$this->spent_on->DbValue = $row['spent_on'];
		$this->tyear->DbValue = $row['tyear'];
		$this->tmonth->DbValue = $row['tmonth'];
		$this->tweek->DbValue = $row['tweek'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
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

		if ($this->hours->FormValue == $this->hours->CurrentValue && is_numeric(ew_StrToFloat($this->hours->CurrentValue)))
			$this->hours->CurrentValue = ew_StrToFloat($this->hours->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// project_id
		// user_id
		// issue_id
		// hours
		// comments
		// activity_id
		// spent_on
		// tyear
		// tmonth
		// tweek
		// created_on
		// updated_on

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// project_id
			$this->project_id->ViewValue = $this->project_id->CurrentValue;
			$this->project_id->ViewCustomAttributes = "";

			// user_id
			$this->user_id->ViewValue = $this->user_id->CurrentValue;
			$this->user_id->ViewCustomAttributes = "";

			// issue_id
			$this->issue_id->ViewValue = $this->issue_id->CurrentValue;
			$this->issue_id->ViewCustomAttributes = "";

			// hours
			$this->hours->ViewValue = $this->hours->CurrentValue;
			$this->hours->ViewCustomAttributes = "";

			// comments
			$this->comments->ViewValue = $this->comments->CurrentValue;
			$this->comments->ViewCustomAttributes = "";

			// activity_id
			$this->activity_id->ViewValue = $this->activity_id->CurrentValue;
			$this->activity_id->ViewCustomAttributes = "";

			// spent_on
			$this->spent_on->ViewValue = $this->spent_on->CurrentValue;
			$this->spent_on->ViewValue = ew_FormatDateTime($this->spent_on->ViewValue, 7);
			$this->spent_on->ViewCustomAttributes = "";

			// tyear
			$this->tyear->ViewValue = $this->tyear->CurrentValue;
			$this->tyear->ViewCustomAttributes = "";

			// tmonth
			$this->tmonth->ViewValue = $this->tmonth->CurrentValue;
			$this->tmonth->ViewCustomAttributes = "";

			// tweek
			$this->tweek->ViewValue = $this->tweek->CurrentValue;
			$this->tweek->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
			$this->updated_on->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// project_id
			$this->project_id->LinkCustomAttributes = "";
			$this->project_id->HrefValue = "";
			$this->project_id->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// issue_id
			$this->issue_id->LinkCustomAttributes = "";
			$this->issue_id->HrefValue = "";
			$this->issue_id->TooltipValue = "";

			// hours
			$this->hours->LinkCustomAttributes = "";
			$this->hours->HrefValue = "";
			$this->hours->TooltipValue = "";

			// comments
			$this->comments->LinkCustomAttributes = "";
			$this->comments->HrefValue = "";
			$this->comments->TooltipValue = "";

			// activity_id
			$this->activity_id->LinkCustomAttributes = "";
			$this->activity_id->HrefValue = "";
			$this->activity_id->TooltipValue = "";

			// spent_on
			$this->spent_on->LinkCustomAttributes = "";
			$this->spent_on->HrefValue = "";
			$this->spent_on->TooltipValue = "";

			// tyear
			$this->tyear->LinkCustomAttributes = "";
			$this->tyear->HrefValue = "";
			$this->tyear->TooltipValue = "";

			// tmonth
			$this->tmonth->LinkCustomAttributes = "";
			$this->tmonth->HrefValue = "";
			$this->tmonth->TooltipValue = "";

			// tweek
			$this->tweek->LinkCustomAttributes = "";
			$this->tweek->HrefValue = "";
			$this->tweek->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// project_id
			$this->project_id->EditCustomAttributes = "";
			$this->project_id->EditValue = ew_HtmlEncode($this->project_id->CurrentValue);
			$this->project_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->project_id->FldCaption()));

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);
			$this->user_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->user_id->FldCaption()));

			// issue_id
			$this->issue_id->EditCustomAttributes = "";
			$this->issue_id->EditValue = ew_HtmlEncode($this->issue_id->CurrentValue);
			$this->issue_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->issue_id->FldCaption()));

			// hours
			$this->hours->EditCustomAttributes = "";
			$this->hours->EditValue = ew_HtmlEncode($this->hours->CurrentValue);
			$this->hours->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->hours->FldCaption()));
			if (strval($this->hours->EditValue) <> "" && is_numeric($this->hours->EditValue)) $this->hours->EditValue = ew_FormatNumber($this->hours->EditValue, -2, -1, -2, 0);

			// comments
			$this->comments->EditCustomAttributes = "";
			$this->comments->EditValue = ew_HtmlEncode($this->comments->CurrentValue);
			$this->comments->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->comments->FldCaption()));

			// activity_id
			$this->activity_id->EditCustomAttributes = "";
			$this->activity_id->EditValue = ew_HtmlEncode($this->activity_id->CurrentValue);
			$this->activity_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->activity_id->FldCaption()));

			// spent_on
			$this->spent_on->EditCustomAttributes = "";
			$this->spent_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->spent_on->CurrentValue, 7));
			$this->spent_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->spent_on->FldCaption()));

			// tyear
			$this->tyear->EditCustomAttributes = "";
			$this->tyear->EditValue = ew_HtmlEncode($this->tyear->CurrentValue);
			$this->tyear->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tyear->FldCaption()));

			// tmonth
			$this->tmonth->EditCustomAttributes = "";
			$this->tmonth->EditValue = ew_HtmlEncode($this->tmonth->CurrentValue);
			$this->tmonth->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tmonth->FldCaption()));

			// tweek
			$this->tweek->EditCustomAttributes = "";
			$this->tweek->EditValue = ew_HtmlEncode($this->tweek->CurrentValue);
			$this->tweek->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tweek->FldCaption()));

			// created_on
			$this->created_on->EditCustomAttributes = "";
			$this->created_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created_on->CurrentValue, 7));
			$this->created_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->created_on->FldCaption()));

			// updated_on
			$this->updated_on->EditCustomAttributes = "";
			$this->updated_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->updated_on->CurrentValue, 7));
			$this->updated_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->updated_on->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// project_id
			$this->project_id->HrefValue = "";

			// user_id
			$this->user_id->HrefValue = "";

			// issue_id
			$this->issue_id->HrefValue = "";

			// hours
			$this->hours->HrefValue = "";

			// comments
			$this->comments->HrefValue = "";

			// activity_id
			$this->activity_id->HrefValue = "";

			// spent_on
			$this->spent_on->HrefValue = "";

			// tyear
			$this->tyear->HrefValue = "";

			// tmonth
			$this->tmonth->HrefValue = "";

			// tweek
			$this->tweek->HrefValue = "";

			// created_on
			$this->created_on->HrefValue = "";

			// updated_on
			$this->updated_on->HrefValue = "";
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
		if (!$this->project_id->FldIsDetailKey && !is_null($this->project_id->FormValue) && $this->project_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->project_id->FldCaption());
		}
		if (!ew_CheckInteger($this->project_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->project_id->FldErrMsg());
		}
		if (!$this->user_id->FldIsDetailKey && !is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->user_id->FldCaption());
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->issue_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->issue_id->FldErrMsg());
		}
		if (!$this->hours->FldIsDetailKey && !is_null($this->hours->FormValue) && $this->hours->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->hours->FldCaption());
		}
		if (!ew_CheckNumber($this->hours->FormValue)) {
			ew_AddMessage($gsFormError, $this->hours->FldErrMsg());
		}
		if (!$this->activity_id->FldIsDetailKey && !is_null($this->activity_id->FormValue) && $this->activity_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->activity_id->FldCaption());
		}
		if (!ew_CheckInteger($this->activity_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->activity_id->FldErrMsg());
		}
		if (!$this->spent_on->FldIsDetailKey && !is_null($this->spent_on->FormValue) && $this->spent_on->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->spent_on->FldCaption());
		}
		if (!ew_CheckEuroDate($this->spent_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->spent_on->FldErrMsg());
		}
		if (!$this->tyear->FldIsDetailKey && !is_null($this->tyear->FormValue) && $this->tyear->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tyear->FldCaption());
		}
		if (!ew_CheckInteger($this->tyear->FormValue)) {
			ew_AddMessage($gsFormError, $this->tyear->FldErrMsg());
		}
		if (!$this->tmonth->FldIsDetailKey && !is_null($this->tmonth->FormValue) && $this->tmonth->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tmonth->FldCaption());
		}
		if (!ew_CheckInteger($this->tmonth->FormValue)) {
			ew_AddMessage($gsFormError, $this->tmonth->FldErrMsg());
		}
		if (!$this->tweek->FldIsDetailKey && !is_null($this->tweek->FormValue) && $this->tweek->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->tweek->FldCaption());
		}
		if (!ew_CheckInteger($this->tweek->FormValue)) {
			ew_AddMessage($gsFormError, $this->tweek->FldErrMsg());
		}
		if (!$this->created_on->FldIsDetailKey && !is_null($this->created_on->FormValue) && $this->created_on->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->created_on->FldCaption());
		}
		if (!ew_CheckEuroDate($this->created_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->created_on->FldErrMsg());
		}
		if (!$this->updated_on->FldIsDetailKey && !is_null($this->updated_on->FormValue) && $this->updated_on->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->updated_on->FldCaption());
		}
		if (!ew_CheckEuroDate($this->updated_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->updated_on->FldErrMsg());
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

		// project_id
		$this->project_id->SetDbValueDef($rsnew, $this->project_id->CurrentValue, 0, FALSE);

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, FALSE);

		// issue_id
		$this->issue_id->SetDbValueDef($rsnew, $this->issue_id->CurrentValue, NULL, FALSE);

		// hours
		$this->hours->SetDbValueDef($rsnew, $this->hours->CurrentValue, 0, FALSE);

		// comments
		$this->comments->SetDbValueDef($rsnew, $this->comments->CurrentValue, NULL, FALSE);

		// activity_id
		$this->activity_id->SetDbValueDef($rsnew, $this->activity_id->CurrentValue, 0, FALSE);

		// spent_on
		$this->spent_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->spent_on->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// tyear
		$this->tyear->SetDbValueDef($rsnew, $this->tyear->CurrentValue, 0, FALSE);

		// tmonth
		$this->tmonth->SetDbValueDef($rsnew, $this->tmonth->CurrentValue, 0, FALSE);

		// tweek
		$this->tweek->SetDbValueDef($rsnew, $this->tweek->CurrentValue, 0, FALSE);

		// created_on
		$this->created_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created_on->CurrentValue, 7), ew_CurrentDate(), FALSE);

		// updated_on
		$this->updated_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->updated_on->CurrentValue, 7), ew_CurrentDate(), FALSE);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_time_entrieslist.php", $this->TableVar);
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
if (!isset($tbrdm_time_entries_add)) $tbrdm_time_entries_add = new ctbrdm_time_entries_add();

// Page init
$tbrdm_time_entries_add->Page_Init();

// Page main
$tbrdm_time_entries_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_time_entries_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_time_entries_add = new ew_Page("tbrdm_time_entries_add");
tbrdm_time_entries_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbrdm_time_entries_add.PageID; // For backward compatibility

// Form object
var ftbrdm_time_entriesadd = new ew_Form("ftbrdm_time_entriesadd");

// Validate form
ftbrdm_time_entriesadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_project_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->project_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_project_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->project_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->user_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->user_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_issue_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->issue_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hours");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->hours->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_hours");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->hours->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_activity_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->activity_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_activity_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->activity_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_spent_on");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->spent_on->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_spent_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->spent_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tyear");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->tyear->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tyear");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->tyear->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tmonth");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->tmonth->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tmonth");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->tmonth->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tweek");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->tweek->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_tweek");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->tweek->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_created_on");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->created_on->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_created_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->created_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_updated_on");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_time_entries->updated_on->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_updated_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_time_entries->updated_on->FldErrMsg()) ?>");

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
ftbrdm_time_entriesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_time_entriesadd.ValidateRequired = true;
<?php } else { ?>
ftbrdm_time_entriesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_time_entries_add->ShowPageHeader(); ?>
<?php
$tbrdm_time_entries_add->ShowMessage();
?>
<form name="ftbrdm_time_entriesadd" id="ftbrdm_time_entriesadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_time_entries">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tbrdm_time_entriesadd" class="table table-bordered table-striped">
<?php if ($tbrdm_time_entries->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbrdm_time_entries_id"><?php echo $tbrdm_time_entries->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->id->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $tbrdm_time_entries->id->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->id->EditValue ?>"<?php echo $tbrdm_time_entries->id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->project_id->Visible) { // project_id ?>
	<tr id="r_project_id">
		<td><span id="elh_tbrdm_time_entries_project_id"><?php echo $tbrdm_time_entries->project_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->project_id->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_project_id" class="control-group">
<input type="text" data-field="x_project_id" name="x_project_id" id="x_project_id" size="30" placeholder="<?php echo $tbrdm_time_entries->project_id->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->project_id->EditValue ?>"<?php echo $tbrdm_time_entries->project_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->project_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->user_id->Visible) { // user_id ?>
	<tr id="r_user_id">
		<td><span id="elh_tbrdm_time_entries_user_id"><?php echo $tbrdm_time_entries->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->user_id->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_user_id" class="control-group">
<input type="text" data-field="x_user_id" name="x_user_id" id="x_user_id" size="30" placeholder="<?php echo $tbrdm_time_entries->user_id->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->user_id->EditValue ?>"<?php echo $tbrdm_time_entries->user_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->user_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->issue_id->Visible) { // issue_id ?>
	<tr id="r_issue_id">
		<td><span id="elh_tbrdm_time_entries_issue_id"><?php echo $tbrdm_time_entries->issue_id->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_time_entries->issue_id->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_issue_id" class="control-group">
<input type="text" data-field="x_issue_id" name="x_issue_id" id="x_issue_id" size="30" placeholder="<?php echo $tbrdm_time_entries->issue_id->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->issue_id->EditValue ?>"<?php echo $tbrdm_time_entries->issue_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->issue_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->hours->Visible) { // hours ?>
	<tr id="r_hours">
		<td><span id="elh_tbrdm_time_entries_hours"><?php echo $tbrdm_time_entries->hours->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->hours->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_hours" class="control-group">
<input type="text" data-field="x_hours" name="x_hours" id="x_hours" size="30" placeholder="<?php echo $tbrdm_time_entries->hours->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->hours->EditValue ?>"<?php echo $tbrdm_time_entries->hours->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->hours->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->comments->Visible) { // comments ?>
	<tr id="r_comments">
		<td><span id="elh_tbrdm_time_entries_comments"><?php echo $tbrdm_time_entries->comments->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_time_entries->comments->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_comments" class="control-group">
<input type="text" data-field="x_comments" name="x_comments" id="x_comments" size="30" maxlength="255" placeholder="<?php echo $tbrdm_time_entries->comments->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->comments->EditValue ?>"<?php echo $tbrdm_time_entries->comments->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->comments->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->activity_id->Visible) { // activity_id ?>
	<tr id="r_activity_id">
		<td><span id="elh_tbrdm_time_entries_activity_id"><?php echo $tbrdm_time_entries->activity_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->activity_id->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_activity_id" class="control-group">
<input type="text" data-field="x_activity_id" name="x_activity_id" id="x_activity_id" size="30" placeholder="<?php echo $tbrdm_time_entries->activity_id->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->activity_id->EditValue ?>"<?php echo $tbrdm_time_entries->activity_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->activity_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->spent_on->Visible) { // spent_on ?>
	<tr id="r_spent_on">
		<td><span id="elh_tbrdm_time_entries_spent_on"><?php echo $tbrdm_time_entries->spent_on->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->spent_on->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_spent_on" class="control-group">
<input type="text" data-field="x_spent_on" name="x_spent_on" id="x_spent_on" placeholder="<?php echo $tbrdm_time_entries->spent_on->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->spent_on->EditValue ?>"<?php echo $tbrdm_time_entries->spent_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->spent_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->tyear->Visible) { // tyear ?>
	<tr id="r_tyear">
		<td><span id="elh_tbrdm_time_entries_tyear"><?php echo $tbrdm_time_entries->tyear->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->tyear->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_tyear" class="control-group">
<input type="text" data-field="x_tyear" name="x_tyear" id="x_tyear" size="30" placeholder="<?php echo $tbrdm_time_entries->tyear->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->tyear->EditValue ?>"<?php echo $tbrdm_time_entries->tyear->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->tyear->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->tmonth->Visible) { // tmonth ?>
	<tr id="r_tmonth">
		<td><span id="elh_tbrdm_time_entries_tmonth"><?php echo $tbrdm_time_entries->tmonth->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->tmonth->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_tmonth" class="control-group">
<input type="text" data-field="x_tmonth" name="x_tmonth" id="x_tmonth" size="30" placeholder="<?php echo $tbrdm_time_entries->tmonth->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->tmonth->EditValue ?>"<?php echo $tbrdm_time_entries->tmonth->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->tmonth->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->tweek->Visible) { // tweek ?>
	<tr id="r_tweek">
		<td><span id="elh_tbrdm_time_entries_tweek"><?php echo $tbrdm_time_entries->tweek->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->tweek->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_tweek" class="control-group">
<input type="text" data-field="x_tweek" name="x_tweek" id="x_tweek" size="30" placeholder="<?php echo $tbrdm_time_entries->tweek->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->tweek->EditValue ?>"<?php echo $tbrdm_time_entries->tweek->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->tweek->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->created_on->Visible) { // created_on ?>
	<tr id="r_created_on">
		<td><span id="elh_tbrdm_time_entries_created_on"><?php echo $tbrdm_time_entries->created_on->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->created_on->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_created_on" class="control-group">
<input type="text" data-field="x_created_on" name="x_created_on" id="x_created_on" placeholder="<?php echo $tbrdm_time_entries->created_on->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->created_on->EditValue ?>"<?php echo $tbrdm_time_entries->created_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->created_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_time_entries->updated_on->Visible) { // updated_on ?>
	<tr id="r_updated_on">
		<td><span id="elh_tbrdm_time_entries_updated_on"><?php echo $tbrdm_time_entries->updated_on->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_time_entries->updated_on->CellAttributes() ?>>
<span id="el_tbrdm_time_entries_updated_on" class="control-group">
<input type="text" data-field="x_updated_on" name="x_updated_on" id="x_updated_on" placeholder="<?php echo $tbrdm_time_entries->updated_on->PlaceHolder ?>" value="<?php echo $tbrdm_time_entries->updated_on->EditValue ?>"<?php echo $tbrdm_time_entries->updated_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_time_entries->updated_on->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftbrdm_time_entriesadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tbrdm_time_entries_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_time_entries_add->Page_Terminate();
?>
