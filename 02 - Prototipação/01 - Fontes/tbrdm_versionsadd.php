<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_versionsinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_versions_add = NULL; // Initialize page object first

class ctbrdm_versions_add extends ctbrdm_versions {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{EC1DE12C-8807-4BF7-B5F7-28BA138CD7FC}";

	// Table name
	var $TableName = 'tbrdm_versions';

	// Page object name
	var $PageObjName = 'tbrdm_versions_add';

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

		// Table object (tbrdm_versions)
		if (!isset($GLOBALS["tbrdm_versions"])) {
			$GLOBALS["tbrdm_versions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_versions"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_versions', TRUE);

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
			$this->Page_Terminate("tbrdm_versionslist.php");
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
					$this->Page_Terminate("tbrdm_versionslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbrdm_versionsview.php")
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
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->effective_date->CurrentValue = NULL;
		$this->effective_date->OldValue = $this->effective_date->CurrentValue;
		$this->created_on->CurrentValue = NULL;
		$this->created_on->OldValue = $this->created_on->CurrentValue;
		$this->updated_on->CurrentValue = NULL;
		$this->updated_on->OldValue = $this->updated_on->CurrentValue;
		$this->wiki_page_title->CurrentValue = NULL;
		$this->wiki_page_title->OldValue = $this->wiki_page_title->CurrentValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
		$this->sharing->CurrentValue = NULL;
		$this->sharing->OldValue = $this->sharing->CurrentValue;
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
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->effective_date->FldIsDetailKey) {
			$this->effective_date->setFormValue($objForm->GetValue("x_effective_date"));
			$this->effective_date->CurrentValue = ew_UnFormatDateTime($this->effective_date->CurrentValue, 7);
		}
		if (!$this->created_on->FldIsDetailKey) {
			$this->created_on->setFormValue($objForm->GetValue("x_created_on"));
			$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		}
		if (!$this->updated_on->FldIsDetailKey) {
			$this->updated_on->setFormValue($objForm->GetValue("x_updated_on"));
			$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
		}
		if (!$this->wiki_page_title->FldIsDetailKey) {
			$this->wiki_page_title->setFormValue($objForm->GetValue("x_wiki_page_title"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->sharing->FldIsDetailKey) {
			$this->sharing->setFormValue($objForm->GetValue("x_sharing"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->project_id->CurrentValue = $this->project_id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->effective_date->CurrentValue = $this->effective_date->FormValue;
		$this->effective_date->CurrentValue = ew_UnFormatDateTime($this->effective_date->CurrentValue, 7);
		$this->created_on->CurrentValue = $this->created_on->FormValue;
		$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		$this->updated_on->CurrentValue = $this->updated_on->FormValue;
		$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
		$this->wiki_page_title->CurrentValue = $this->wiki_page_title->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->sharing->CurrentValue = $this->sharing->FormValue;
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
		$this->name->setDbValue($rs->fields('name'));
		$this->description->setDbValue($rs->fields('description'));
		$this->effective_date->setDbValue($rs->fields('effective_date'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->wiki_page_title->setDbValue($rs->fields('wiki_page_title'));
		$this->status->setDbValue($rs->fields('status'));
		$this->sharing->setDbValue($rs->fields('sharing'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->project_id->DbValue = $row['project_id'];
		$this->name->DbValue = $row['name'];
		$this->description->DbValue = $row['description'];
		$this->effective_date->DbValue = $row['effective_date'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
		$this->wiki_page_title->DbValue = $row['wiki_page_title'];
		$this->status->DbValue = $row['status'];
		$this->sharing->DbValue = $row['sharing'];
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
		// project_id
		// name
		// description
		// effective_date
		// created_on
		// updated_on
		// wiki_page_title
		// status
		// sharing

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// project_id
			$this->project_id->ViewValue = $this->project_id->CurrentValue;
			$this->project_id->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

			// effective_date
			$this->effective_date->ViewValue = $this->effective_date->CurrentValue;
			$this->effective_date->ViewValue = ew_FormatDateTime($this->effective_date->ViewValue, 7);
			$this->effective_date->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
			$this->updated_on->ViewCustomAttributes = "";

			// wiki_page_title
			$this->wiki_page_title->ViewValue = $this->wiki_page_title->CurrentValue;
			$this->wiki_page_title->ViewCustomAttributes = "";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->ViewCustomAttributes = "";

			// sharing
			$this->sharing->ViewValue = $this->sharing->CurrentValue;
			$this->sharing->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// project_id
			$this->project_id->LinkCustomAttributes = "";
			$this->project_id->HrefValue = "";
			$this->project_id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// effective_date
			$this->effective_date->LinkCustomAttributes = "";
			$this->effective_date->HrefValue = "";
			$this->effective_date->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";

			// wiki_page_title
			$this->wiki_page_title->LinkCustomAttributes = "";
			$this->wiki_page_title->HrefValue = "";
			$this->wiki_page_title->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// sharing
			$this->sharing->LinkCustomAttributes = "";
			$this->sharing->HrefValue = "";
			$this->sharing->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// project_id
			$this->project_id->EditCustomAttributes = "";
			$this->project_id->EditValue = ew_HtmlEncode($this->project_id->CurrentValue);
			$this->project_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->project_id->FldCaption()));

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// description
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->description->FldCaption()));

			// effective_date
			$this->effective_date->EditCustomAttributes = "";
			$this->effective_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->effective_date->CurrentValue, 7));
			$this->effective_date->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->effective_date->FldCaption()));

			// created_on
			$this->created_on->EditCustomAttributes = "";
			$this->created_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created_on->CurrentValue, 7));
			$this->created_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->created_on->FldCaption()));

			// updated_on
			$this->updated_on->EditCustomAttributes = "";
			$this->updated_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->updated_on->CurrentValue, 7));
			$this->updated_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->updated_on->FldCaption()));

			// wiki_page_title
			$this->wiki_page_title->EditCustomAttributes = "";
			$this->wiki_page_title->EditValue = ew_HtmlEncode($this->wiki_page_title->CurrentValue);
			$this->wiki_page_title->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->wiki_page_title->FldCaption()));

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->status->FldCaption()));

			// sharing
			$this->sharing->EditCustomAttributes = "";
			$this->sharing->EditValue = ew_HtmlEncode($this->sharing->CurrentValue);
			$this->sharing->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->sharing->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// project_id
			$this->project_id->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// description
			$this->description->HrefValue = "";

			// effective_date
			$this->effective_date->HrefValue = "";

			// created_on
			$this->created_on->HrefValue = "";

			// updated_on
			$this->updated_on->HrefValue = "";

			// wiki_page_title
			$this->wiki_page_title->HrefValue = "";

			// status
			$this->status->HrefValue = "";

			// sharing
			$this->sharing->HrefValue = "";
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
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->name->FldCaption());
		}
		if (!ew_CheckEuroDate($this->effective_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->effective_date->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->created_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->created_on->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->updated_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->updated_on->FldErrMsg());
		}
		if (!$this->sharing->FldIsDetailKey && !is_null($this->sharing->FormValue) && $this->sharing->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->sharing->FldCaption());
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

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// effective_date
		$this->effective_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->effective_date->CurrentValue, 7), NULL, FALSE);

		// created_on
		$this->created_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created_on->CurrentValue, 7), NULL, FALSE);

		// updated_on
		$this->updated_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->updated_on->CurrentValue, 7), NULL, FALSE);

		// wiki_page_title
		$this->wiki_page_title->SetDbValueDef($rsnew, $this->wiki_page_title->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, NULL, FALSE);

		// sharing
		$this->sharing->SetDbValueDef($rsnew, $this->sharing->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_versionslist.php", $this->TableVar);
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
if (!isset($tbrdm_versions_add)) $tbrdm_versions_add = new ctbrdm_versions_add();

// Page init
$tbrdm_versions_add->Page_Init();

// Page main
$tbrdm_versions_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_versions_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_versions_add = new ew_Page("tbrdm_versions_add");
tbrdm_versions_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbrdm_versions_add.PageID; // For backward compatibility

// Form object
var ftbrdm_versionsadd = new ew_Form("ftbrdm_versionsadd");

// Validate form
ftbrdm_versionsadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_versions->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_versions->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_project_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_versions->project_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_project_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_versions->project_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_versions->name->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_effective_date");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_versions->effective_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_created_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_versions->created_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_updated_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_versions->updated_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sharing");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_versions->sharing->FldCaption()) ?>");

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
ftbrdm_versionsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_versionsadd.ValidateRequired = true;
<?php } else { ?>
ftbrdm_versionsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_versions_add->ShowPageHeader(); ?>
<?php
$tbrdm_versions_add->ShowMessage();
?>
<form name="ftbrdm_versionsadd" id="ftbrdm_versionsadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_versions">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tbrdm_versionsadd" class="table table-bordered table-striped">
<?php if ($tbrdm_versions->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbrdm_versions_id"><?php echo $tbrdm_versions->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_versions->id->CellAttributes() ?>>
<span id="el_tbrdm_versions_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $tbrdm_versions->id->PlaceHolder ?>" value="<?php echo $tbrdm_versions->id->EditValue ?>"<?php echo $tbrdm_versions->id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_versions->project_id->Visible) { // project_id ?>
	<tr id="r_project_id">
		<td><span id="elh_tbrdm_versions_project_id"><?php echo $tbrdm_versions->project_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_versions->project_id->CellAttributes() ?>>
<span id="el_tbrdm_versions_project_id" class="control-group">
<input type="text" data-field="x_project_id" name="x_project_id" id="x_project_id" size="30" placeholder="<?php echo $tbrdm_versions->project_id->PlaceHolder ?>" value="<?php echo $tbrdm_versions->project_id->EditValue ?>"<?php echo $tbrdm_versions->project_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->project_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_versions->name->Visible) { // name ?>
	<tr id="r_name">
		<td><span id="elh_tbrdm_versions_name"><?php echo $tbrdm_versions->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_versions->name->CellAttributes() ?>>
<span id="el_tbrdm_versions_name" class="control-group">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="255" placeholder="<?php echo $tbrdm_versions->name->PlaceHolder ?>" value="<?php echo $tbrdm_versions->name->EditValue ?>"<?php echo $tbrdm_versions->name->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_versions->description->Visible) { // description ?>
	<tr id="r_description">
		<td><span id="elh_tbrdm_versions_description"><?php echo $tbrdm_versions->description->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_versions->description->CellAttributes() ?>>
<span id="el_tbrdm_versions_description" class="control-group">
<input type="text" data-field="x_description" name="x_description" id="x_description" size="30" maxlength="255" placeholder="<?php echo $tbrdm_versions->description->PlaceHolder ?>" value="<?php echo $tbrdm_versions->description->EditValue ?>"<?php echo $tbrdm_versions->description->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->description->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_versions->effective_date->Visible) { // effective_date ?>
	<tr id="r_effective_date">
		<td><span id="elh_tbrdm_versions_effective_date"><?php echo $tbrdm_versions->effective_date->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_versions->effective_date->CellAttributes() ?>>
<span id="el_tbrdm_versions_effective_date" class="control-group">
<input type="text" data-field="x_effective_date" name="x_effective_date" id="x_effective_date" placeholder="<?php echo $tbrdm_versions->effective_date->PlaceHolder ?>" value="<?php echo $tbrdm_versions->effective_date->EditValue ?>"<?php echo $tbrdm_versions->effective_date->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->effective_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_versions->created_on->Visible) { // created_on ?>
	<tr id="r_created_on">
		<td><span id="elh_tbrdm_versions_created_on"><?php echo $tbrdm_versions->created_on->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_versions->created_on->CellAttributes() ?>>
<span id="el_tbrdm_versions_created_on" class="control-group">
<input type="text" data-field="x_created_on" name="x_created_on" id="x_created_on" placeholder="<?php echo $tbrdm_versions->created_on->PlaceHolder ?>" value="<?php echo $tbrdm_versions->created_on->EditValue ?>"<?php echo $tbrdm_versions->created_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->created_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_versions->updated_on->Visible) { // updated_on ?>
	<tr id="r_updated_on">
		<td><span id="elh_tbrdm_versions_updated_on"><?php echo $tbrdm_versions->updated_on->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_versions->updated_on->CellAttributes() ?>>
<span id="el_tbrdm_versions_updated_on" class="control-group">
<input type="text" data-field="x_updated_on" name="x_updated_on" id="x_updated_on" placeholder="<?php echo $tbrdm_versions->updated_on->PlaceHolder ?>" value="<?php echo $tbrdm_versions->updated_on->EditValue ?>"<?php echo $tbrdm_versions->updated_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->updated_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_versions->wiki_page_title->Visible) { // wiki_page_title ?>
	<tr id="r_wiki_page_title">
		<td><span id="elh_tbrdm_versions_wiki_page_title"><?php echo $tbrdm_versions->wiki_page_title->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_versions->wiki_page_title->CellAttributes() ?>>
<span id="el_tbrdm_versions_wiki_page_title" class="control-group">
<input type="text" data-field="x_wiki_page_title" name="x_wiki_page_title" id="x_wiki_page_title" size="30" maxlength="255" placeholder="<?php echo $tbrdm_versions->wiki_page_title->PlaceHolder ?>" value="<?php echo $tbrdm_versions->wiki_page_title->EditValue ?>"<?php echo $tbrdm_versions->wiki_page_title->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->wiki_page_title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_versions->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tbrdm_versions_status"><?php echo $tbrdm_versions->status->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_versions->status->CellAttributes() ?>>
<span id="el_tbrdm_versions_status" class="control-group">
<input type="text" data-field="x_status" name="x_status" id="x_status" size="30" maxlength="255" placeholder="<?php echo $tbrdm_versions->status->PlaceHolder ?>" value="<?php echo $tbrdm_versions->status->EditValue ?>"<?php echo $tbrdm_versions->status->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_versions->sharing->Visible) { // sharing ?>
	<tr id="r_sharing">
		<td><span id="elh_tbrdm_versions_sharing"><?php echo $tbrdm_versions->sharing->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_versions->sharing->CellAttributes() ?>>
<span id="el_tbrdm_versions_sharing" class="control-group">
<input type="text" data-field="x_sharing" name="x_sharing" id="x_sharing" size="30" maxlength="255" placeholder="<?php echo $tbrdm_versions->sharing->PlaceHolder ?>" value="<?php echo $tbrdm_versions->sharing->EditValue ?>"<?php echo $tbrdm_versions->sharing->EditAttributes() ?>>
</span>
<?php echo $tbrdm_versions->sharing->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftbrdm_versionsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tbrdm_versions_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_versions_add->Page_Terminate();
?>
