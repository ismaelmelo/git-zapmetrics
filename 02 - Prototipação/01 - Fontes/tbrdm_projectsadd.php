<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_projectsinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_projects_add = NULL; // Initialize page object first

class ctbrdm_projects_add extends ctbrdm_projects {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{EC1DE12C-8807-4BF7-B5F7-28BA138CD7FC}";

	// Table name
	var $TableName = 'tbrdm_projects';

	// Page object name
	var $PageObjName = 'tbrdm_projects_add';

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

		// Table object (tbrdm_projects)
		if (!isset($GLOBALS["tbrdm_projects"])) {
			$GLOBALS["tbrdm_projects"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_projects"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_projects', TRUE);

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
			$this->Page_Terminate("tbrdm_projectslist.php");
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
					$this->Page_Terminate("tbrdm_projectslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbrdm_projectsview.php")
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
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->description->CurrentValue = NULL;
		$this->description->OldValue = $this->description->CurrentValue;
		$this->homepage->CurrentValue = NULL;
		$this->homepage->OldValue = $this->homepage->CurrentValue;
		$this->is_public->CurrentValue = NULL;
		$this->is_public->OldValue = $this->is_public->CurrentValue;
		$this->parent_id->CurrentValue = NULL;
		$this->parent_id->OldValue = $this->parent_id->CurrentValue;
		$this->created_on->CurrentValue = NULL;
		$this->created_on->OldValue = $this->created_on->CurrentValue;
		$this->updated_on->CurrentValue = NULL;
		$this->updated_on->OldValue = $this->updated_on->CurrentValue;
		$this->identifier->CurrentValue = NULL;
		$this->identifier->OldValue = $this->identifier->CurrentValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->homepage->FldIsDetailKey) {
			$this->homepage->setFormValue($objForm->GetValue("x_homepage"));
		}
		if (!$this->is_public->FldIsDetailKey) {
			$this->is_public->setFormValue($objForm->GetValue("x_is_public"));
		}
		if (!$this->parent_id->FldIsDetailKey) {
			$this->parent_id->setFormValue($objForm->GetValue("x_parent_id"));
		}
		if (!$this->created_on->FldIsDetailKey) {
			$this->created_on->setFormValue($objForm->GetValue("x_created_on"));
			$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		}
		if (!$this->updated_on->FldIsDetailKey) {
			$this->updated_on->setFormValue($objForm->GetValue("x_updated_on"));
			$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
		}
		if (!$this->identifier->FldIsDetailKey) {
			$this->identifier->setFormValue($objForm->GetValue("x_identifier"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->homepage->CurrentValue = $this->homepage->FormValue;
		$this->is_public->CurrentValue = $this->is_public->FormValue;
		$this->parent_id->CurrentValue = $this->parent_id->FormValue;
		$this->created_on->CurrentValue = $this->created_on->FormValue;
		$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		$this->updated_on->CurrentValue = $this->updated_on->FormValue;
		$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
		$this->identifier->CurrentValue = $this->identifier->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
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
		$this->name->setDbValue($rs->fields('name'));
		$this->description->setDbValue($rs->fields('description'));
		$this->homepage->setDbValue($rs->fields('homepage'));
		$this->is_public->setDbValue($rs->fields('is_public'));
		$this->parent_id->setDbValue($rs->fields('parent_id'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->identifier->setDbValue($rs->fields('identifier'));
		$this->status->setDbValue($rs->fields('status'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->name->DbValue = $row['name'];
		$this->description->DbValue = $row['description'];
		$this->homepage->DbValue = $row['homepage'];
		$this->is_public->DbValue = $row['is_public'];
		$this->parent_id->DbValue = $row['parent_id'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
		$this->identifier->DbValue = $row['identifier'];
		$this->status->DbValue = $row['status'];
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
		// name
		// description
		// homepage
		// is_public
		// parent_id
		// created_on
		// updated_on
		// identifier
		// status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

			// homepage
			$this->homepage->ViewValue = $this->homepage->CurrentValue;
			$this->homepage->ViewCustomAttributes = "";

			// is_public
			$this->is_public->ViewValue = $this->is_public->CurrentValue;
			$this->is_public->ViewCustomAttributes = "";

			// parent_id
			$this->parent_id->ViewValue = $this->parent_id->CurrentValue;
			$this->parent_id->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
			$this->updated_on->ViewCustomAttributes = "";

			// identifier
			$this->identifier->ViewValue = $this->identifier->CurrentValue;
			$this->identifier->ViewCustomAttributes = "";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// homepage
			$this->homepage->LinkCustomAttributes = "";
			$this->homepage->HrefValue = "";
			$this->homepage->TooltipValue = "";

			// is_public
			$this->is_public->LinkCustomAttributes = "";
			$this->is_public->HrefValue = "";
			$this->is_public->TooltipValue = "";

			// parent_id
			$this->parent_id->LinkCustomAttributes = "";
			$this->parent_id->HrefValue = "";
			$this->parent_id->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";

			// identifier
			$this->identifier->LinkCustomAttributes = "";
			$this->identifier->HrefValue = "";
			$this->identifier->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// description
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = $this->description->CurrentValue;
			$this->description->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->description->FldCaption()));

			// homepage
			$this->homepage->EditCustomAttributes = "";
			$this->homepage->EditValue = ew_HtmlEncode($this->homepage->CurrentValue);
			$this->homepage->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->homepage->FldCaption()));

			// is_public
			$this->is_public->EditCustomAttributes = "";
			$this->is_public->EditValue = ew_HtmlEncode($this->is_public->CurrentValue);
			$this->is_public->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->is_public->FldCaption()));

			// parent_id
			$this->parent_id->EditCustomAttributes = "";
			$this->parent_id->EditValue = ew_HtmlEncode($this->parent_id->CurrentValue);
			$this->parent_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->parent_id->FldCaption()));

			// created_on
			$this->created_on->EditCustomAttributes = "";
			$this->created_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created_on->CurrentValue, 7));
			$this->created_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->created_on->FldCaption()));

			// updated_on
			$this->updated_on->EditCustomAttributes = "";
			$this->updated_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->updated_on->CurrentValue, 7));
			$this->updated_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->updated_on->FldCaption()));

			// identifier
			$this->identifier->EditCustomAttributes = "";
			$this->identifier->EditValue = ew_HtmlEncode($this->identifier->CurrentValue);
			$this->identifier->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->identifier->FldCaption()));

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->status->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// description
			$this->description->HrefValue = "";

			// homepage
			$this->homepage->HrefValue = "";

			// is_public
			$this->is_public->HrefValue = "";

			// parent_id
			$this->parent_id->HrefValue = "";

			// created_on
			$this->created_on->HrefValue = "";

			// updated_on
			$this->updated_on->HrefValue = "";

			// identifier
			$this->identifier->HrefValue = "";

			// status
			$this->status->HrefValue = "";
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
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->name->FldCaption());
		}
		if (!$this->is_public->FldIsDetailKey && !is_null($this->is_public->FormValue) && $this->is_public->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->is_public->FldCaption());
		}
		if (!ew_CheckInteger($this->is_public->FormValue)) {
			ew_AddMessage($gsFormError, $this->is_public->FldErrMsg());
		}
		if (!ew_CheckInteger($this->parent_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->parent_id->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->created_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->created_on->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->updated_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->updated_on->FldErrMsg());
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->status->FldCaption());
		}
		if (!ew_CheckInteger($this->status->FormValue)) {
			ew_AddMessage($gsFormError, $this->status->FldErrMsg());
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

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// description
		$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, FALSE);

		// homepage
		$this->homepage->SetDbValueDef($rsnew, $this->homepage->CurrentValue, NULL, FALSE);

		// is_public
		$this->is_public->SetDbValueDef($rsnew, $this->is_public->CurrentValue, 0, FALSE);

		// parent_id
		$this->parent_id->SetDbValueDef($rsnew, $this->parent_id->CurrentValue, NULL, FALSE);

		// created_on
		$this->created_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created_on->CurrentValue, 7), NULL, FALSE);

		// updated_on
		$this->updated_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->updated_on->CurrentValue, 7), NULL, FALSE);

		// identifier
		$this->identifier->SetDbValueDef($rsnew, $this->identifier->CurrentValue, NULL, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, 0, FALSE);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_projectslist.php", $this->TableVar);
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
if (!isset($tbrdm_projects_add)) $tbrdm_projects_add = new ctbrdm_projects_add();

// Page init
$tbrdm_projects_add->Page_Init();

// Page main
$tbrdm_projects_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_projects_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_projects_add = new ew_Page("tbrdm_projects_add");
tbrdm_projects_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbrdm_projects_add.PageID; // For backward compatibility

// Form object
var ftbrdm_projectsadd = new ew_Form("ftbrdm_projectsadd");

// Validate form
ftbrdm_projectsadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_projects->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_projects->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_projects->name->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_is_public");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_projects->is_public->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_is_public");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_projects->is_public->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_parent_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_projects->parent_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_created_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_projects->created_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_updated_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_projects->updated_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_projects->status->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_projects->status->FldErrMsg()) ?>");

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
ftbrdm_projectsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_projectsadd.ValidateRequired = true;
<?php } else { ?>
ftbrdm_projectsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_projects_add->ShowPageHeader(); ?>
<?php
$tbrdm_projects_add->ShowMessage();
?>
<form name="ftbrdm_projectsadd" id="ftbrdm_projectsadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_projects">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tbrdm_projectsadd" class="table table-bordered table-striped">
<?php if ($tbrdm_projects->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbrdm_projects_id"><?php echo $tbrdm_projects->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_projects->id->CellAttributes() ?>>
<span id="el_tbrdm_projects_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $tbrdm_projects->id->PlaceHolder ?>" value="<?php echo $tbrdm_projects->id->EditValue ?>"<?php echo $tbrdm_projects->id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_projects->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_projects->name->Visible) { // name ?>
	<tr id="r_name">
		<td><span id="elh_tbrdm_projects_name"><?php echo $tbrdm_projects->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_projects->name->CellAttributes() ?>>
<span id="el_tbrdm_projects_name" class="control-group">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="255" placeholder="<?php echo $tbrdm_projects->name->PlaceHolder ?>" value="<?php echo $tbrdm_projects->name->EditValue ?>"<?php echo $tbrdm_projects->name->EditAttributes() ?>>
</span>
<?php echo $tbrdm_projects->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_projects->description->Visible) { // description ?>
	<tr id="r_description">
		<td><span id="elh_tbrdm_projects_description"><?php echo $tbrdm_projects->description->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_projects->description->CellAttributes() ?>>
<span id="el_tbrdm_projects_description" class="control-group">
<textarea data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo $tbrdm_projects->description->PlaceHolder ?>"<?php echo $tbrdm_projects->description->EditAttributes() ?>><?php echo $tbrdm_projects->description->EditValue ?></textarea>
</span>
<?php echo $tbrdm_projects->description->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_projects->homepage->Visible) { // homepage ?>
	<tr id="r_homepage">
		<td><span id="elh_tbrdm_projects_homepage"><?php echo $tbrdm_projects->homepage->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_projects->homepage->CellAttributes() ?>>
<span id="el_tbrdm_projects_homepage" class="control-group">
<input type="text" data-field="x_homepage" name="x_homepage" id="x_homepage" size="30" maxlength="255" placeholder="<?php echo $tbrdm_projects->homepage->PlaceHolder ?>" value="<?php echo $tbrdm_projects->homepage->EditValue ?>"<?php echo $tbrdm_projects->homepage->EditAttributes() ?>>
</span>
<?php echo $tbrdm_projects->homepage->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_projects->is_public->Visible) { // is_public ?>
	<tr id="r_is_public">
		<td><span id="elh_tbrdm_projects_is_public"><?php echo $tbrdm_projects->is_public->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_projects->is_public->CellAttributes() ?>>
<span id="el_tbrdm_projects_is_public" class="control-group">
<input type="text" data-field="x_is_public" name="x_is_public" id="x_is_public" size="30" placeholder="<?php echo $tbrdm_projects->is_public->PlaceHolder ?>" value="<?php echo $tbrdm_projects->is_public->EditValue ?>"<?php echo $tbrdm_projects->is_public->EditAttributes() ?>>
</span>
<?php echo $tbrdm_projects->is_public->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_projects->parent_id->Visible) { // parent_id ?>
	<tr id="r_parent_id">
		<td><span id="elh_tbrdm_projects_parent_id"><?php echo $tbrdm_projects->parent_id->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_projects->parent_id->CellAttributes() ?>>
<span id="el_tbrdm_projects_parent_id" class="control-group">
<input type="text" data-field="x_parent_id" name="x_parent_id" id="x_parent_id" size="30" placeholder="<?php echo $tbrdm_projects->parent_id->PlaceHolder ?>" value="<?php echo $tbrdm_projects->parent_id->EditValue ?>"<?php echo $tbrdm_projects->parent_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_projects->parent_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_projects->created_on->Visible) { // created_on ?>
	<tr id="r_created_on">
		<td><span id="elh_tbrdm_projects_created_on"><?php echo $tbrdm_projects->created_on->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_projects->created_on->CellAttributes() ?>>
<span id="el_tbrdm_projects_created_on" class="control-group">
<input type="text" data-field="x_created_on" name="x_created_on" id="x_created_on" placeholder="<?php echo $tbrdm_projects->created_on->PlaceHolder ?>" value="<?php echo $tbrdm_projects->created_on->EditValue ?>"<?php echo $tbrdm_projects->created_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_projects->created_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_projects->updated_on->Visible) { // updated_on ?>
	<tr id="r_updated_on">
		<td><span id="elh_tbrdm_projects_updated_on"><?php echo $tbrdm_projects->updated_on->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_projects->updated_on->CellAttributes() ?>>
<span id="el_tbrdm_projects_updated_on" class="control-group">
<input type="text" data-field="x_updated_on" name="x_updated_on" id="x_updated_on" placeholder="<?php echo $tbrdm_projects->updated_on->PlaceHolder ?>" value="<?php echo $tbrdm_projects->updated_on->EditValue ?>"<?php echo $tbrdm_projects->updated_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_projects->updated_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_projects->identifier->Visible) { // identifier ?>
	<tr id="r_identifier">
		<td><span id="elh_tbrdm_projects_identifier"><?php echo $tbrdm_projects->identifier->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_projects->identifier->CellAttributes() ?>>
<span id="el_tbrdm_projects_identifier" class="control-group">
<input type="text" data-field="x_identifier" name="x_identifier" id="x_identifier" size="30" maxlength="255" placeholder="<?php echo $tbrdm_projects->identifier->PlaceHolder ?>" value="<?php echo $tbrdm_projects->identifier->EditValue ?>"<?php echo $tbrdm_projects->identifier->EditAttributes() ?>>
</span>
<?php echo $tbrdm_projects->identifier->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_projects->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tbrdm_projects_status"><?php echo $tbrdm_projects->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_projects->status->CellAttributes() ?>>
<span id="el_tbrdm_projects_status" class="control-group">
<input type="text" data-field="x_status" name="x_status" id="x_status" size="30" placeholder="<?php echo $tbrdm_projects->status->PlaceHolder ?>" value="<?php echo $tbrdm_projects->status->EditValue ?>"<?php echo $tbrdm_projects->status->EditAttributes() ?>>
</span>
<?php echo $tbrdm_projects->status->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftbrdm_projectsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tbrdm_projects_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_projects_add->Page_Terminate();
?>
