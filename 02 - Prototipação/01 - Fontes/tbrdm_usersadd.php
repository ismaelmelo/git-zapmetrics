<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_usersinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_users_add = NULL; // Initialize page object first

class ctbrdm_users_add extends ctbrdm_users {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{EC1DE12C-8807-4BF7-B5F7-28BA138CD7FC}";

	// Table name
	var $TableName = 'tbrdm_users';

	// Page object name
	var $PageObjName = 'tbrdm_users_add';

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

		// Table object (tbrdm_users)
		if (!isset($GLOBALS["tbrdm_users"])) {
			$GLOBALS["tbrdm_users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_users"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_users', TRUE);

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
			$this->Page_Terminate("tbrdm_userslist.php");
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
					$this->Page_Terminate("tbrdm_userslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbrdm_usersview.php")
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
		$this->_login->CurrentValue = NULL;
		$this->_login->OldValue = $this->_login->CurrentValue;
		$this->mail->CurrentValue = NULL;
		$this->mail->OldValue = $this->mail->CurrentValue;
		$this->admin->CurrentValue = NULL;
		$this->admin->OldValue = $this->admin->CurrentValue;
		$this->status->CurrentValue = NULL;
		$this->status->OldValue = $this->status->CurrentValue;
		$this->last_login_on->CurrentValue = NULL;
		$this->last_login_on->OldValue = $this->last_login_on->CurrentValue;
		$this->_language->CurrentValue = NULL;
		$this->_language->OldValue = $this->_language->CurrentValue;
		$this->created_on->CurrentValue = NULL;
		$this->created_on->OldValue = $this->created_on->CurrentValue;
		$this->updated_on->CurrentValue = NULL;
		$this->updated_on->OldValue = $this->updated_on->CurrentValue;
		$this->type->CurrentValue = NULL;
		$this->type->OldValue = $this->type->CurrentValue;
		$this->identity_url->CurrentValue = NULL;
		$this->identity_url->OldValue = $this->identity_url->CurrentValue;
		$this->mail_notification->CurrentValue = NULL;
		$this->mail_notification->OldValue = $this->mail_notification->CurrentValue;
		$this->name->CurrentValue = NULL;
		$this->name->OldValue = $this->name->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->_login->FldIsDetailKey) {
			$this->_login->setFormValue($objForm->GetValue("x__login"));
		}
		if (!$this->mail->FldIsDetailKey) {
			$this->mail->setFormValue($objForm->GetValue("x_mail"));
		}
		if (!$this->admin->FldIsDetailKey) {
			$this->admin->setFormValue($objForm->GetValue("x_admin"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->last_login_on->FldIsDetailKey) {
			$this->last_login_on->setFormValue($objForm->GetValue("x_last_login_on"));
			$this->last_login_on->CurrentValue = ew_UnFormatDateTime($this->last_login_on->CurrentValue, 7);
		}
		if (!$this->_language->FldIsDetailKey) {
			$this->_language->setFormValue($objForm->GetValue("x__language"));
		}
		if (!$this->created_on->FldIsDetailKey) {
			$this->created_on->setFormValue($objForm->GetValue("x_created_on"));
			$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		}
		if (!$this->updated_on->FldIsDetailKey) {
			$this->updated_on->setFormValue($objForm->GetValue("x_updated_on"));
			$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->identity_url->FldIsDetailKey) {
			$this->identity_url->setFormValue($objForm->GetValue("x_identity_url"));
		}
		if (!$this->mail_notification->FldIsDetailKey) {
			$this->mail_notification->setFormValue($objForm->GetValue("x_mail_notification"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->_login->CurrentValue = $this->_login->FormValue;
		$this->mail->CurrentValue = $this->mail->FormValue;
		$this->admin->CurrentValue = $this->admin->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->last_login_on->CurrentValue = $this->last_login_on->FormValue;
		$this->last_login_on->CurrentValue = ew_UnFormatDateTime($this->last_login_on->CurrentValue, 7);
		$this->_language->CurrentValue = $this->_language->FormValue;
		$this->created_on->CurrentValue = $this->created_on->FormValue;
		$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		$this->updated_on->CurrentValue = $this->updated_on->FormValue;
		$this->updated_on->CurrentValue = ew_UnFormatDateTime($this->updated_on->CurrentValue, 7);
		$this->type->CurrentValue = $this->type->FormValue;
		$this->identity_url->CurrentValue = $this->identity_url->FormValue;
		$this->mail_notification->CurrentValue = $this->mail_notification->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
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
		$this->_login->setDbValue($rs->fields('login'));
		$this->mail->setDbValue($rs->fields('mail'));
		$this->admin->setDbValue($rs->fields('admin'));
		$this->status->setDbValue($rs->fields('status'));
		$this->last_login_on->setDbValue($rs->fields('last_login_on'));
		$this->_language->setDbValue($rs->fields('language'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->type->setDbValue($rs->fields('type'));
		$this->identity_url->setDbValue($rs->fields('identity_url'));
		$this->mail_notification->setDbValue($rs->fields('mail_notification'));
		$this->name->setDbValue($rs->fields('name'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->_login->DbValue = $row['login'];
		$this->mail->DbValue = $row['mail'];
		$this->admin->DbValue = $row['admin'];
		$this->status->DbValue = $row['status'];
		$this->last_login_on->DbValue = $row['last_login_on'];
		$this->_language->DbValue = $row['language'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
		$this->type->DbValue = $row['type'];
		$this->identity_url->DbValue = $row['identity_url'];
		$this->mail_notification->DbValue = $row['mail_notification'];
		$this->name->DbValue = $row['name'];
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
		// login
		// mail
		// admin
		// status
		// last_login_on
		// language
		// created_on
		// updated_on
		// type
		// identity_url
		// mail_notification
		// name

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// login
			$this->_login->ViewValue = $this->_login->CurrentValue;
			$this->_login->ViewCustomAttributes = "";

			// mail
			$this->mail->ViewValue = $this->mail->CurrentValue;
			$this->mail->ViewCustomAttributes = "";

			// admin
			$this->admin->ViewValue = $this->admin->CurrentValue;
			$this->admin->ViewCustomAttributes = "";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->ViewCustomAttributes = "";

			// last_login_on
			$this->last_login_on->ViewValue = $this->last_login_on->CurrentValue;
			$this->last_login_on->ViewValue = ew_FormatDateTime($this->last_login_on->ViewValue, 7);
			$this->last_login_on->ViewCustomAttributes = "";

			// language
			$this->_language->ViewValue = $this->_language->CurrentValue;
			$this->_language->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
			$this->updated_on->ViewCustomAttributes = "";

			// type
			$this->type->ViewValue = $this->type->CurrentValue;
			$this->type->ViewCustomAttributes = "";

			// identity_url
			$this->identity_url->ViewValue = $this->identity_url->CurrentValue;
			$this->identity_url->ViewCustomAttributes = "";

			// mail_notification
			$this->mail_notification->ViewValue = $this->mail_notification->CurrentValue;
			$this->mail_notification->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// login
			$this->_login->LinkCustomAttributes = "";
			$this->_login->HrefValue = "";
			$this->_login->TooltipValue = "";

			// mail
			$this->mail->LinkCustomAttributes = "";
			$this->mail->HrefValue = "";
			$this->mail->TooltipValue = "";

			// admin
			$this->admin->LinkCustomAttributes = "";
			$this->admin->HrefValue = "";
			$this->admin->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// last_login_on
			$this->last_login_on->LinkCustomAttributes = "";
			$this->last_login_on->HrefValue = "";
			$this->last_login_on->TooltipValue = "";

			// language
			$this->_language->LinkCustomAttributes = "";
			$this->_language->HrefValue = "";
			$this->_language->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// identity_url
			$this->identity_url->LinkCustomAttributes = "";
			$this->identity_url->HrefValue = "";
			$this->identity_url->TooltipValue = "";

			// mail_notification
			$this->mail_notification->LinkCustomAttributes = "";
			$this->mail_notification->HrefValue = "";
			$this->mail_notification->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// login
			$this->_login->EditCustomAttributes = "";
			$this->_login->EditValue = ew_HtmlEncode($this->_login->CurrentValue);
			$this->_login->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->_login->FldCaption()));

			// mail
			$this->mail->EditCustomAttributes = "";
			$this->mail->EditValue = ew_HtmlEncode($this->mail->CurrentValue);
			$this->mail->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->mail->FldCaption()));

			// admin
			$this->admin->EditCustomAttributes = "";
			$this->admin->EditValue = ew_HtmlEncode($this->admin->CurrentValue);
			$this->admin->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->admin->FldCaption()));

			// status
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->status->FldCaption()));

			// last_login_on
			$this->last_login_on->EditCustomAttributes = "";
			$this->last_login_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_login_on->CurrentValue, 7));
			$this->last_login_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->last_login_on->FldCaption()));

			// language
			$this->_language->EditCustomAttributes = "";
			$this->_language->EditValue = ew_HtmlEncode($this->_language->CurrentValue);
			$this->_language->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->_language->FldCaption()));

			// created_on
			$this->created_on->EditCustomAttributes = "";
			$this->created_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created_on->CurrentValue, 7));
			$this->created_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->created_on->FldCaption()));

			// updated_on
			$this->updated_on->EditCustomAttributes = "";
			$this->updated_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->updated_on->CurrentValue, 7));
			$this->updated_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->updated_on->FldCaption()));

			// type
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);
			$this->type->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->type->FldCaption()));

			// identity_url
			$this->identity_url->EditCustomAttributes = "";
			$this->identity_url->EditValue = ew_HtmlEncode($this->identity_url->CurrentValue);
			$this->identity_url->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->identity_url->FldCaption()));

			// mail_notification
			$this->mail_notification->EditCustomAttributes = "";
			$this->mail_notification->EditValue = ew_HtmlEncode($this->mail_notification->CurrentValue);
			$this->mail_notification->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->mail_notification->FldCaption()));

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// login
			$this->_login->HrefValue = "";

			// mail
			$this->mail->HrefValue = "";

			// admin
			$this->admin->HrefValue = "";

			// status
			$this->status->HrefValue = "";

			// last_login_on
			$this->last_login_on->HrefValue = "";

			// language
			$this->_language->HrefValue = "";

			// created_on
			$this->created_on->HrefValue = "";

			// updated_on
			$this->updated_on->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// identity_url
			$this->identity_url->HrefValue = "";

			// mail_notification
			$this->mail_notification->HrefValue = "";

			// name
			$this->name->HrefValue = "";
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
		if (!$this->_login->FldIsDetailKey && !is_null($this->_login->FormValue) && $this->_login->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->_login->FldCaption());
		}
		if (!$this->mail->FldIsDetailKey && !is_null($this->mail->FormValue) && $this->mail->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->mail->FldCaption());
		}
		if (!$this->admin->FldIsDetailKey && !is_null($this->admin->FormValue) && $this->admin->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->admin->FldCaption());
		}
		if (!ew_CheckInteger($this->admin->FormValue)) {
			ew_AddMessage($gsFormError, $this->admin->FldErrMsg());
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->status->FldCaption());
		}
		if (!ew_CheckInteger($this->status->FormValue)) {
			ew_AddMessage($gsFormError, $this->status->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->last_login_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->last_login_on->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->created_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->created_on->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->updated_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->updated_on->FldErrMsg());
		}
		if (!$this->mail_notification->FldIsDetailKey && !is_null($this->mail_notification->FormValue) && $this->mail_notification->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->mail_notification->FldCaption());
		}
		if (!$this->name->FldIsDetailKey && !is_null($this->name->FormValue) && $this->name->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->name->FldCaption());
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

		// login
		$this->_login->SetDbValueDef($rsnew, $this->_login->CurrentValue, "", FALSE);

		// mail
		$this->mail->SetDbValueDef($rsnew, $this->mail->CurrentValue, "", FALSE);

		// admin
		$this->admin->SetDbValueDef($rsnew, $this->admin->CurrentValue, 0, FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, 0, FALSE);

		// last_login_on
		$this->last_login_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->last_login_on->CurrentValue, 7), NULL, FALSE);

		// language
		$this->_language->SetDbValueDef($rsnew, $this->_language->CurrentValue, NULL, FALSE);

		// created_on
		$this->created_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created_on->CurrentValue, 7), NULL, FALSE);

		// updated_on
		$this->updated_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->updated_on->CurrentValue, 7), NULL, FALSE);

		// type
		$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, NULL, FALSE);

		// identity_url
		$this->identity_url->SetDbValueDef($rsnew, $this->identity_url->CurrentValue, NULL, FALSE);

		// mail_notification
		$this->mail_notification->SetDbValueDef($rsnew, $this->mail_notification->CurrentValue, "", FALSE);

		// name
		$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_userslist.php", $this->TableVar);
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
if (!isset($tbrdm_users_add)) $tbrdm_users_add = new ctbrdm_users_add();

// Page init
$tbrdm_users_add->Page_Init();

// Page main
$tbrdm_users_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_users_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_users_add = new ew_Page("tbrdm_users_add");
tbrdm_users_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbrdm_users_add.PageID; // For backward compatibility

// Form object
var ftbrdm_usersadd = new ew_Form("ftbrdm_usersadd");

// Validate form
ftbrdm_usersadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_users->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_users->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "__login");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_users->_login->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_mail");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_users->mail->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_admin");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_users->admin->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_admin");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_users->admin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_users->status->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_users->status->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_last_login_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_users->last_login_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_created_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_users->created_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_updated_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_users->updated_on->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_mail_notification");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_users->mail_notification->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_users->name->FldCaption()) ?>");

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
ftbrdm_usersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_usersadd.ValidateRequired = true;
<?php } else { ?>
ftbrdm_usersadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_users_add->ShowPageHeader(); ?>
<?php
$tbrdm_users_add->ShowMessage();
?>
<form name="ftbrdm_usersadd" id="ftbrdm_usersadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_users">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tbrdm_usersadd" class="table table-bordered table-striped">
<?php if ($tbrdm_users->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbrdm_users_id"><?php echo $tbrdm_users->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_users->id->CellAttributes() ?>>
<span id="el_tbrdm_users_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $tbrdm_users->id->PlaceHolder ?>" value="<?php echo $tbrdm_users->id->EditValue ?>"<?php echo $tbrdm_users->id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->_login->Visible) { // login ?>
	<tr id="r__login">
		<td><span id="elh_tbrdm_users__login"><?php echo $tbrdm_users->_login->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_users->_login->CellAttributes() ?>>
<span id="el_tbrdm_users__login" class="control-group">
<input type="text" data-field="x__login" name="x__login" id="x__login" size="30" maxlength="255" placeholder="<?php echo $tbrdm_users->_login->PlaceHolder ?>" value="<?php echo $tbrdm_users->_login->EditValue ?>"<?php echo $tbrdm_users->_login->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->_login->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->mail->Visible) { // mail ?>
	<tr id="r_mail">
		<td><span id="elh_tbrdm_users_mail"><?php echo $tbrdm_users->mail->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_users->mail->CellAttributes() ?>>
<span id="el_tbrdm_users_mail" class="control-group">
<input type="text" data-field="x_mail" name="x_mail" id="x_mail" size="30" maxlength="60" placeholder="<?php echo $tbrdm_users->mail->PlaceHolder ?>" value="<?php echo $tbrdm_users->mail->EditValue ?>"<?php echo $tbrdm_users->mail->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->mail->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->admin->Visible) { // admin ?>
	<tr id="r_admin">
		<td><span id="elh_tbrdm_users_admin"><?php echo $tbrdm_users->admin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_users->admin->CellAttributes() ?>>
<span id="el_tbrdm_users_admin" class="control-group">
<input type="text" data-field="x_admin" name="x_admin" id="x_admin" size="30" placeholder="<?php echo $tbrdm_users->admin->PlaceHolder ?>" value="<?php echo $tbrdm_users->admin->EditValue ?>"<?php echo $tbrdm_users->admin->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->admin->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_tbrdm_users_status"><?php echo $tbrdm_users->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_users->status->CellAttributes() ?>>
<span id="el_tbrdm_users_status" class="control-group">
<input type="text" data-field="x_status" name="x_status" id="x_status" size="30" placeholder="<?php echo $tbrdm_users->status->PlaceHolder ?>" value="<?php echo $tbrdm_users->status->EditValue ?>"<?php echo $tbrdm_users->status->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->last_login_on->Visible) { // last_login_on ?>
	<tr id="r_last_login_on">
		<td><span id="elh_tbrdm_users_last_login_on"><?php echo $tbrdm_users->last_login_on->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_users->last_login_on->CellAttributes() ?>>
<span id="el_tbrdm_users_last_login_on" class="control-group">
<input type="text" data-field="x_last_login_on" name="x_last_login_on" id="x_last_login_on" placeholder="<?php echo $tbrdm_users->last_login_on->PlaceHolder ?>" value="<?php echo $tbrdm_users->last_login_on->EditValue ?>"<?php echo $tbrdm_users->last_login_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->last_login_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->_language->Visible) { // language ?>
	<tr id="r__language">
		<td><span id="elh_tbrdm_users__language"><?php echo $tbrdm_users->_language->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_users->_language->CellAttributes() ?>>
<span id="el_tbrdm_users__language" class="control-group">
<input type="text" data-field="x__language" name="x__language" id="x__language" size="30" maxlength="5" placeholder="<?php echo $tbrdm_users->_language->PlaceHolder ?>" value="<?php echo $tbrdm_users->_language->EditValue ?>"<?php echo $tbrdm_users->_language->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->_language->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->created_on->Visible) { // created_on ?>
	<tr id="r_created_on">
		<td><span id="elh_tbrdm_users_created_on"><?php echo $tbrdm_users->created_on->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_users->created_on->CellAttributes() ?>>
<span id="el_tbrdm_users_created_on" class="control-group">
<input type="text" data-field="x_created_on" name="x_created_on" id="x_created_on" placeholder="<?php echo $tbrdm_users->created_on->PlaceHolder ?>" value="<?php echo $tbrdm_users->created_on->EditValue ?>"<?php echo $tbrdm_users->created_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->created_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->updated_on->Visible) { // updated_on ?>
	<tr id="r_updated_on">
		<td><span id="elh_tbrdm_users_updated_on"><?php echo $tbrdm_users->updated_on->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_users->updated_on->CellAttributes() ?>>
<span id="el_tbrdm_users_updated_on" class="control-group">
<input type="text" data-field="x_updated_on" name="x_updated_on" id="x_updated_on" placeholder="<?php echo $tbrdm_users->updated_on->PlaceHolder ?>" value="<?php echo $tbrdm_users->updated_on->EditValue ?>"<?php echo $tbrdm_users->updated_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->updated_on->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->type->Visible) { // type ?>
	<tr id="r_type">
		<td><span id="elh_tbrdm_users_type"><?php echo $tbrdm_users->type->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_users->type->CellAttributes() ?>>
<span id="el_tbrdm_users_type" class="control-group">
<input type="text" data-field="x_type" name="x_type" id="x_type" size="30" maxlength="255" placeholder="<?php echo $tbrdm_users->type->PlaceHolder ?>" value="<?php echo $tbrdm_users->type->EditValue ?>"<?php echo $tbrdm_users->type->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->identity_url->Visible) { // identity_url ?>
	<tr id="r_identity_url">
		<td><span id="elh_tbrdm_users_identity_url"><?php echo $tbrdm_users->identity_url->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_users->identity_url->CellAttributes() ?>>
<span id="el_tbrdm_users_identity_url" class="control-group">
<input type="text" data-field="x_identity_url" name="x_identity_url" id="x_identity_url" size="30" maxlength="255" placeholder="<?php echo $tbrdm_users->identity_url->PlaceHolder ?>" value="<?php echo $tbrdm_users->identity_url->EditValue ?>"<?php echo $tbrdm_users->identity_url->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->identity_url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->mail_notification->Visible) { // mail_notification ?>
	<tr id="r_mail_notification">
		<td><span id="elh_tbrdm_users_mail_notification"><?php echo $tbrdm_users->mail_notification->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_users->mail_notification->CellAttributes() ?>>
<span id="el_tbrdm_users_mail_notification" class="control-group">
<input type="text" data-field="x_mail_notification" name="x_mail_notification" id="x_mail_notification" size="30" maxlength="255" placeholder="<?php echo $tbrdm_users->mail_notification->PlaceHolder ?>" value="<?php echo $tbrdm_users->mail_notification->EditValue ?>"<?php echo $tbrdm_users->mail_notification->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->mail_notification->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_users->name->Visible) { // name ?>
	<tr id="r_name">
		<td><span id="elh_tbrdm_users_name"><?php echo $tbrdm_users->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_users->name->CellAttributes() ?>>
<span id="el_tbrdm_users_name" class="control-group">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="75" placeholder="<?php echo $tbrdm_users->name->PlaceHolder ?>" value="<?php echo $tbrdm_users->name->EditValue ?>"<?php echo $tbrdm_users->name->EditAttributes() ?>>
</span>
<?php echo $tbrdm_users->name->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftbrdm_usersadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tbrdm_users_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_users_add->Page_Terminate();
?>
