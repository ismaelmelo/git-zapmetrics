<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_custom_fieldsinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_custom_fields_add = NULL; // Initialize page object first

class ctbrdm_custom_fields_add extends ctbrdm_custom_fields {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{EC1DE12C-8807-4BF7-B5F7-28BA138CD7FC}";

	// Table name
	var $TableName = 'tbrdm_custom_fields';

	// Page object name
	var $PageObjName = 'tbrdm_custom_fields_add';

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

		// Table object (tbrdm_custom_fields)
		if (!isset($GLOBALS["tbrdm_custom_fields"])) {
			$GLOBALS["tbrdm_custom_fields"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_custom_fields"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_custom_fields', TRUE);

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
			$this->Page_Terminate("tbrdm_custom_fieldslist.php");
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
					$this->Page_Terminate("tbrdm_custom_fieldslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbrdm_custom_fieldsview.php")
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
		$this->type->CurrentValue = NULL;
		$this->type->OldValue = $this->type->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
		$this->field_format->CurrentValue = NULL;
		$this->field_format->OldValue = $this->field_format->CurrentValue;
		$this->possible_values->CurrentValue = NULL;
		$this->possible_values->OldValue = $this->possible_values->CurrentValue;
		$this->min_length->CurrentValue = NULL;
		$this->min_length->OldValue = $this->min_length->CurrentValue;
		$this->max_length->CurrentValue = NULL;
		$this->max_length->OldValue = $this->max_length->CurrentValue;
		$this->default_value->CurrentValue = NULL;
		$this->default_value->OldValue = $this->default_value->CurrentValue;
		$this->editable->CurrentValue = NULL;
		$this->editable->OldValue = $this->editable->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->field_format->FldIsDetailKey) {
			$this->field_format->setFormValue($objForm->GetValue("x_field_format"));
		}
		if (!$this->possible_values->FldIsDetailKey) {
			$this->possible_values->setFormValue($objForm->GetValue("x_possible_values"));
		}
		if (!$this->min_length->FldIsDetailKey) {
			$this->min_length->setFormValue($objForm->GetValue("x_min_length"));
		}
		if (!$this->max_length->FldIsDetailKey) {
			$this->max_length->setFormValue($objForm->GetValue("x_max_length"));
		}
		if (!$this->default_value->FldIsDetailKey) {
			$this->default_value->setFormValue($objForm->GetValue("x_default_value"));
		}
		if (!$this->editable->FldIsDetailKey) {
			$this->editable->setFormValue($objForm->GetValue("x_editable"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->field_format->CurrentValue = $this->field_format->FormValue;
		$this->possible_values->CurrentValue = $this->possible_values->FormValue;
		$this->min_length->CurrentValue = $this->min_length->FormValue;
		$this->max_length->CurrentValue = $this->max_length->FormValue;
		$this->default_value->CurrentValue = $this->default_value->FormValue;
		$this->editable->CurrentValue = $this->editable->FormValue;
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
		$this->type->setDbValue($rs->fields('type'));
		$this->name->setDbValue($rs->fields('name'));
		$this->field_format->setDbValue($rs->fields('field_format'));
		$this->possible_values->setDbValue($rs->fields('possible_values'));
		$this->min_length->setDbValue($rs->fields('min_length'));
		$this->max_length->setDbValue($rs->fields('max_length'));
		$this->default_value->setDbValue($rs->fields('default_value'));
		$this->editable->setDbValue($rs->fields('editable'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->type->DbValue = $row['type'];
		$this->name->DbValue = $row['name'];
		$this->field_format->DbValue = $row['field_format'];
		$this->possible_values->DbValue = $row['possible_values'];
		$this->min_length->DbValue = $row['min_length'];
		$this->max_length->DbValue = $row['max_length'];
		$this->default_value->DbValue = $row['default_value'];
		$this->editable->DbValue = $row['editable'];
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
		// type
		// name
		// field_format
		// possible_values
		// min_length
		// max_length
		// default_value
		// editable

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// type
			$this->type->ViewValue = $this->type->CurrentValue;
			$this->type->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// field_format
			$this->field_format->ViewValue = $this->field_format->CurrentValue;
			$this->field_format->ViewCustomAttributes = "";

			// possible_values
			$this->possible_values->ViewValue = $this->possible_values->CurrentValue;
			$this->possible_values->ViewCustomAttributes = "";

			// min_length
			$this->min_length->ViewValue = $this->min_length->CurrentValue;
			$this->min_length->ViewCustomAttributes = "";

			// max_length
			$this->max_length->ViewValue = $this->max_length->CurrentValue;
			$this->max_length->ViewCustomAttributes = "";

			// default_value
			$this->default_value->ViewValue = $this->default_value->CurrentValue;
			$this->default_value->ViewCustomAttributes = "";

			// editable
			$this->editable->ViewValue = $this->editable->CurrentValue;
			$this->editable->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// field_format
			$this->field_format->LinkCustomAttributes = "";
			$this->field_format->HrefValue = "";
			$this->field_format->TooltipValue = "";

			// possible_values
			$this->possible_values->LinkCustomAttributes = "";
			$this->possible_values->HrefValue = "";
			$this->possible_values->TooltipValue = "";

			// min_length
			$this->min_length->LinkCustomAttributes = "";
			$this->min_length->HrefValue = "";
			$this->min_length->TooltipValue = "";

			// max_length
			$this->max_length->LinkCustomAttributes = "";
			$this->max_length->HrefValue = "";
			$this->max_length->TooltipValue = "";

			// default_value
			$this->default_value->LinkCustomAttributes = "";
			$this->default_value->HrefValue = "";
			$this->default_value->TooltipValue = "";

			// editable
			$this->editable->LinkCustomAttributes = "";
			$this->editable->HrefValue = "";
			$this->editable->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// type
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);
			$this->type->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->type->FldCaption()));

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// field_format
			$this->field_format->EditCustomAttributes = "";
			$this->field_format->EditValue = ew_HtmlEncode($this->field_format->CurrentValue);
			$this->field_format->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->field_format->FldCaption()));

			// possible_values
			$this->possible_values->EditCustomAttributes = "";
			$this->possible_values->EditValue = $this->possible_values->CurrentValue;
			$this->possible_values->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->possible_values->FldCaption()));

			// min_length
			$this->min_length->EditCustomAttributes = "";
			$this->min_length->EditValue = ew_HtmlEncode($this->min_length->CurrentValue);
			$this->min_length->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->min_length->FldCaption()));

			// max_length
			$this->max_length->EditCustomAttributes = "";
			$this->max_length->EditValue = ew_HtmlEncode($this->max_length->CurrentValue);
			$this->max_length->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->max_length->FldCaption()));

			// default_value
			$this->default_value->EditCustomAttributes = "";
			$this->default_value->EditValue = $this->default_value->CurrentValue;
			$this->default_value->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->default_value->FldCaption()));

			// editable
			$this->editable->EditCustomAttributes = "";
			$this->editable->EditValue = ew_HtmlEncode($this->editable->CurrentValue);
			$this->editable->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->editable->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// field_format
			$this->field_format->HrefValue = "";

			// possible_values
			$this->possible_values->HrefValue = "";

			// min_length
			$this->min_length->HrefValue = "";

			// max_length
			$this->max_length->HrefValue = "";

			// default_value
			$this->default_value->HrefValue = "";

			// editable
			$this->editable->HrefValue = "";
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
		if (!$this->type->FldIsDetailKey && !is_null($this->type->FormValue) && $this->type->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->type->FldCaption());
		}
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->name->FldCaption());
		}
		if (!$this->field_format->FldIsDetailKey && !is_null($this->field_format->FormValue) && $this->field_format->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->field_format->FldCaption());
		}
		if (!$this->min_length->FldIsDetailKey && !is_null($this->min_length->FormValue) && $this->min_length->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->min_length->FldCaption());
		}
		if (!ew_CheckInteger($this->min_length->FormValue)) {
			ew_AddMessage($gsFormError, $this->min_length->FldErrMsg());
		}
		if (!$this->max_length->FldIsDetailKey && !is_null($this->max_length->FormValue) && $this->max_length->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->max_length->FldCaption());
		}
		if (!ew_CheckInteger($this->max_length->FormValue)) {
			ew_AddMessage($gsFormError, $this->max_length->FldErrMsg());
		}
		if (!ew_CheckInteger($this->editable->FormValue)) {
			ew_AddMessage($gsFormError, $this->editable->FldErrMsg());
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

		// type
		$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, "", FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

		// field_format
		$this->field_format->SetDbValueDef($rsnew, $this->field_format->CurrentValue, "", FALSE);

		// possible_values
		$this->possible_values->SetDbValueDef($rsnew, $this->possible_values->CurrentValue, NULL, FALSE);

		// min_length
		$this->min_length->SetDbValueDef($rsnew, $this->min_length->CurrentValue, 0, FALSE);

		// max_length
		$this->max_length->SetDbValueDef($rsnew, $this->max_length->CurrentValue, 0, FALSE);

		// default_value
		$this->default_value->SetDbValueDef($rsnew, $this->default_value->CurrentValue, NULL, FALSE);

		// editable
		$this->editable->SetDbValueDef($rsnew, $this->editable->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_custom_fieldslist.php", $this->TableVar);
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
if (!isset($tbrdm_custom_fields_add)) $tbrdm_custom_fields_add = new ctbrdm_custom_fields_add();

// Page init
$tbrdm_custom_fields_add->Page_Init();

// Page main
$tbrdm_custom_fields_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_custom_fields_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_custom_fields_add = new ew_Page("tbrdm_custom_fields_add");
tbrdm_custom_fields_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbrdm_custom_fields_add.PageID; // For backward compatibility

// Form object
var ftbrdm_custom_fieldsadd = new ew_Form("ftbrdm_custom_fieldsadd");

// Validate form
ftbrdm_custom_fieldsadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_custom_fields->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_custom_fields->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_type");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_custom_fields->type->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_custom_fields->name->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_field_format");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_custom_fields->field_format->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_min_length");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_custom_fields->min_length->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_min_length");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_custom_fields->min_length->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_max_length");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_custom_fields->max_length->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_max_length");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_custom_fields->max_length->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_editable");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_custom_fields->editable->FldErrMsg()) ?>");

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
ftbrdm_custom_fieldsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_custom_fieldsadd.ValidateRequired = true;
<?php } else { ?>
ftbrdm_custom_fieldsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_custom_fields_add->ShowPageHeader(); ?>
<?php
$tbrdm_custom_fields_add->ShowMessage();
?>
<form name="ftbrdm_custom_fieldsadd" id="ftbrdm_custom_fieldsadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_custom_fields">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tbrdm_custom_fieldsadd" class="table table-bordered table-striped">
<?php if ($tbrdm_custom_fields->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbrdm_custom_fields_id"><?php echo $tbrdm_custom_fields->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_custom_fields->id->CellAttributes() ?>>
<span id="el_tbrdm_custom_fields_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $tbrdm_custom_fields->id->PlaceHolder ?>" value="<?php echo $tbrdm_custom_fields->id->EditValue ?>"<?php echo $tbrdm_custom_fields->id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_custom_fields->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_custom_fields->type->Visible) { // type ?>
	<tr id="r_type">
		<td><span id="elh_tbrdm_custom_fields_type"><?php echo $tbrdm_custom_fields->type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_custom_fields->type->CellAttributes() ?>>
<span id="el_tbrdm_custom_fields_type" class="control-group">
<input type="text" data-field="x_type" name="x_type" id="x_type" size="30" maxlength="30" placeholder="<?php echo $tbrdm_custom_fields->type->PlaceHolder ?>" value="<?php echo $tbrdm_custom_fields->type->EditValue ?>"<?php echo $tbrdm_custom_fields->type->EditAttributes() ?>>
</span>
<?php echo $tbrdm_custom_fields->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_custom_fields->name->Visible) { // name ?>
	<tr id="r_name">
		<td><span id="elh_tbrdm_custom_fields_name"><?php echo $tbrdm_custom_fields->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_custom_fields->name->CellAttributes() ?>>
<span id="el_tbrdm_custom_fields_name" class="control-group">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="30" placeholder="<?php echo $tbrdm_custom_fields->name->PlaceHolder ?>" value="<?php echo $tbrdm_custom_fields->name->EditValue ?>"<?php echo $tbrdm_custom_fields->name->EditAttributes() ?>>
</span>
<?php echo $tbrdm_custom_fields->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_custom_fields->field_format->Visible) { // field_format ?>
	<tr id="r_field_format">
		<td><span id="elh_tbrdm_custom_fields_field_format"><?php echo $tbrdm_custom_fields->field_format->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_custom_fields->field_format->CellAttributes() ?>>
<span id="el_tbrdm_custom_fields_field_format" class="control-group">
<input type="text" data-field="x_field_format" name="x_field_format" id="x_field_format" size="30" maxlength="30" placeholder="<?php echo $tbrdm_custom_fields->field_format->PlaceHolder ?>" value="<?php echo $tbrdm_custom_fields->field_format->EditValue ?>"<?php echo $tbrdm_custom_fields->field_format->EditAttributes() ?>>
</span>
<?php echo $tbrdm_custom_fields->field_format->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_custom_fields->possible_values->Visible) { // possible_values ?>
	<tr id="r_possible_values">
		<td><span id="elh_tbrdm_custom_fields_possible_values"><?php echo $tbrdm_custom_fields->possible_values->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_custom_fields->possible_values->CellAttributes() ?>>
<span id="el_tbrdm_custom_fields_possible_values" class="control-group">
<textarea data-field="x_possible_values" name="x_possible_values" id="x_possible_values" cols="35" rows="4" placeholder="<?php echo $tbrdm_custom_fields->possible_values->PlaceHolder ?>"<?php echo $tbrdm_custom_fields->possible_values->EditAttributes() ?>><?php echo $tbrdm_custom_fields->possible_values->EditValue ?></textarea>
</span>
<?php echo $tbrdm_custom_fields->possible_values->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_custom_fields->min_length->Visible) { // min_length ?>
	<tr id="r_min_length">
		<td><span id="elh_tbrdm_custom_fields_min_length"><?php echo $tbrdm_custom_fields->min_length->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_custom_fields->min_length->CellAttributes() ?>>
<span id="el_tbrdm_custom_fields_min_length" class="control-group">
<input type="text" data-field="x_min_length" name="x_min_length" id="x_min_length" size="30" placeholder="<?php echo $tbrdm_custom_fields->min_length->PlaceHolder ?>" value="<?php echo $tbrdm_custom_fields->min_length->EditValue ?>"<?php echo $tbrdm_custom_fields->min_length->EditAttributes() ?>>
</span>
<?php echo $tbrdm_custom_fields->min_length->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_custom_fields->max_length->Visible) { // max_length ?>
	<tr id="r_max_length">
		<td><span id="elh_tbrdm_custom_fields_max_length"><?php echo $tbrdm_custom_fields->max_length->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_custom_fields->max_length->CellAttributes() ?>>
<span id="el_tbrdm_custom_fields_max_length" class="control-group">
<input type="text" data-field="x_max_length" name="x_max_length" id="x_max_length" size="30" placeholder="<?php echo $tbrdm_custom_fields->max_length->PlaceHolder ?>" value="<?php echo $tbrdm_custom_fields->max_length->EditValue ?>"<?php echo $tbrdm_custom_fields->max_length->EditAttributes() ?>>
</span>
<?php echo $tbrdm_custom_fields->max_length->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_custom_fields->default_value->Visible) { // default_value ?>
	<tr id="r_default_value">
		<td><span id="elh_tbrdm_custom_fields_default_value"><?php echo $tbrdm_custom_fields->default_value->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_custom_fields->default_value->CellAttributes() ?>>
<span id="el_tbrdm_custom_fields_default_value" class="control-group">
<textarea data-field="x_default_value" name="x_default_value" id="x_default_value" cols="35" rows="4" placeholder="<?php echo $tbrdm_custom_fields->default_value->PlaceHolder ?>"<?php echo $tbrdm_custom_fields->default_value->EditAttributes() ?>><?php echo $tbrdm_custom_fields->default_value->EditValue ?></textarea>
</span>
<?php echo $tbrdm_custom_fields->default_value->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_custom_fields->editable->Visible) { // editable ?>
	<tr id="r_editable">
		<td><span id="elh_tbrdm_custom_fields_editable"><?php echo $tbrdm_custom_fields->editable->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_custom_fields->editable->CellAttributes() ?>>
<span id="el_tbrdm_custom_fields_editable" class="control-group">
<input type="text" data-field="x_editable" name="x_editable" id="x_editable" size="30" placeholder="<?php echo $tbrdm_custom_fields->editable->PlaceHolder ?>" value="<?php echo $tbrdm_custom_fields->editable->EditValue ?>"<?php echo $tbrdm_custom_fields->editable->EditAttributes() ?>>
</span>
<?php echo $tbrdm_custom_fields->editable->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftbrdm_custom_fieldsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tbrdm_custom_fields_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_custom_fields_add->Page_Terminate();
?>
