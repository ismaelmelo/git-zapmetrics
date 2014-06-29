<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_rolesinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_roles_add = NULL; // Initialize page object first

class ctbrdm_roles_add extends ctbrdm_roles {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{EC1DE12C-8807-4BF7-B5F7-28BA138CD7FC}";

	// Table name
	var $TableName = 'tbrdm_roles';

	// Page object name
	var $PageObjName = 'tbrdm_roles_add';

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

		// Table object (tbrdm_roles)
		if (!isset($GLOBALS["tbrdm_roles"])) {
			$GLOBALS["tbrdm_roles"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_roles"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_roles', TRUE);

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
			$this->Page_Terminate("tbrdm_roleslist.php");
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
					$this->Page_Terminate("tbrdm_roleslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbrdm_rolesview.php")
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
		$this->position->CurrentValue = NULL;
		$this->position->OldValue = $this->position->CurrentValue;
		$this->assignable->CurrentValue = NULL;
		$this->assignable->OldValue = $this->assignable->CurrentValue;
		$this->builtin->CurrentValue = NULL;
		$this->builtin->OldValue = $this->builtin->CurrentValue;
		$this->permissions->CurrentValue = NULL;
		$this->permissions->OldValue = $this->permissions->CurrentValue;
		$this->issues_visibility->CurrentValue = NULL;
		$this->issues_visibility->OldValue = $this->issues_visibility->CurrentValue;
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
		if (!$this->position->FldIsDetailKey) {
			$this->position->setFormValue($objForm->GetValue("x_position"));
		}
		if (!$this->assignable->FldIsDetailKey) {
			$this->assignable->setFormValue($objForm->GetValue("x_assignable"));
		}
		if (!$this->builtin->FldIsDetailKey) {
			$this->builtin->setFormValue($objForm->GetValue("x_builtin"));
		}
		if (!$this->permissions->FldIsDetailKey) {
			$this->permissions->setFormValue($objForm->GetValue("x_permissions"));
		}
		if (!$this->issues_visibility->FldIsDetailKey) {
			$this->issues_visibility->setFormValue($objForm->GetValue("x_issues_visibility"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->position->CurrentValue = $this->position->FormValue;
		$this->assignable->CurrentValue = $this->assignable->FormValue;
		$this->builtin->CurrentValue = $this->builtin->FormValue;
		$this->permissions->CurrentValue = $this->permissions->FormValue;
		$this->issues_visibility->CurrentValue = $this->issues_visibility->FormValue;
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
		$this->position->setDbValue($rs->fields('position'));
		$this->assignable->setDbValue($rs->fields('assignable'));
		$this->builtin->setDbValue($rs->fields('builtin'));
		$this->permissions->setDbValue($rs->fields('permissions'));
		$this->issues_visibility->setDbValue($rs->fields('issues_visibility'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->name->DbValue = $row['name'];
		$this->position->DbValue = $row['position'];
		$this->assignable->DbValue = $row['assignable'];
		$this->builtin->DbValue = $row['builtin'];
		$this->permissions->DbValue = $row['permissions'];
		$this->issues_visibility->DbValue = $row['issues_visibility'];
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
		// position
		// assignable
		// builtin
		// permissions
		// issues_visibility

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// position
			$this->position->ViewValue = $this->position->CurrentValue;
			$this->position->ViewCustomAttributes = "";

			// assignable
			$this->assignable->ViewValue = $this->assignable->CurrentValue;
			$this->assignable->ViewCustomAttributes = "";

			// builtin
			$this->builtin->ViewValue = $this->builtin->CurrentValue;
			$this->builtin->ViewCustomAttributes = "";

			// permissions
			$this->permissions->ViewValue = $this->permissions->CurrentValue;
			$this->permissions->ViewCustomAttributes = "";

			// issues_visibility
			$this->issues_visibility->ViewValue = $this->issues_visibility->CurrentValue;
			$this->issues_visibility->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// position
			$this->position->LinkCustomAttributes = "";
			$this->position->HrefValue = "";
			$this->position->TooltipValue = "";

			// assignable
			$this->assignable->LinkCustomAttributes = "";
			$this->assignable->HrefValue = "";
			$this->assignable->TooltipValue = "";

			// builtin
			$this->builtin->LinkCustomAttributes = "";
			$this->builtin->HrefValue = "";
			$this->builtin->TooltipValue = "";

			// permissions
			$this->permissions->LinkCustomAttributes = "";
			$this->permissions->HrefValue = "";
			$this->permissions->TooltipValue = "";

			// issues_visibility
			$this->issues_visibility->LinkCustomAttributes = "";
			$this->issues_visibility->HrefValue = "";
			$this->issues_visibility->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);
			$this->name->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->name->FldCaption()));

			// position
			$this->position->EditCustomAttributes = "";
			$this->position->EditValue = ew_HtmlEncode($this->position->CurrentValue);
			$this->position->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->position->FldCaption()));

			// assignable
			$this->assignable->EditCustomAttributes = "";
			$this->assignable->EditValue = ew_HtmlEncode($this->assignable->CurrentValue);
			$this->assignable->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->assignable->FldCaption()));

			// builtin
			$this->builtin->EditCustomAttributes = "";
			$this->builtin->EditValue = ew_HtmlEncode($this->builtin->CurrentValue);
			$this->builtin->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->builtin->FldCaption()));

			// permissions
			$this->permissions->EditCustomAttributes = "";
			$this->permissions->EditValue = $this->permissions->CurrentValue;
			$this->permissions->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->permissions->FldCaption()));

			// issues_visibility
			$this->issues_visibility->EditCustomAttributes = "";
			$this->issues_visibility->EditValue = ew_HtmlEncode($this->issues_visibility->CurrentValue);
			$this->issues_visibility->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->issues_visibility->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// position
			$this->position->HrefValue = "";

			// assignable
			$this->assignable->HrefValue = "";

			// builtin
			$this->builtin->HrefValue = "";

			// permissions
			$this->permissions->HrefValue = "";

			// issues_visibility
			$this->issues_visibility->HrefValue = "";
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
		if (!ew_CheckInteger($this->position->FormValue)) {
			ew_AddMessage($gsFormError, $this->position->FldErrMsg());
		}
		if (!ew_CheckInteger($this->assignable->FormValue)) {
			ew_AddMessage($gsFormError, $this->assignable->FldErrMsg());
		}
		if (!$this->builtin->FldIsDetailKey && !is_null($this->builtin->FormValue) && $this->builtin->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->builtin->FldCaption());
		}
		if (!ew_CheckInteger($this->builtin->FormValue)) {
			ew_AddMessage($gsFormError, $this->builtin->FldErrMsg());
		}
		if (!$this->issues_visibility->FldIsDetailKey && !is_null($this->issues_visibility->FormValue) && $this->issues_visibility->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->issues_visibility->FldCaption());
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

		// position
		$this->position->SetDbValueDef($rsnew, $this->position->CurrentValue, NULL, FALSE);

		// assignable
		$this->assignable->SetDbValueDef($rsnew, $this->assignable->CurrentValue, NULL, FALSE);

		// builtin
		$this->builtin->SetDbValueDef($rsnew, $this->builtin->CurrentValue, 0, FALSE);

		// permissions
		$this->permissions->SetDbValueDef($rsnew, $this->permissions->CurrentValue, NULL, FALSE);

		// issues_visibility
		$this->issues_visibility->SetDbValueDef($rsnew, $this->issues_visibility->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_roleslist.php", $this->TableVar);
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
if (!isset($tbrdm_roles_add)) $tbrdm_roles_add = new ctbrdm_roles_add();

// Page init
$tbrdm_roles_add->Page_Init();

// Page main
$tbrdm_roles_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_roles_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_roles_add = new ew_Page("tbrdm_roles_add");
tbrdm_roles_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbrdm_roles_add.PageID; // For backward compatibility

// Form object
var ftbrdm_rolesadd = new ew_Form("ftbrdm_rolesadd");

// Validate form
ftbrdm_rolesadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_roles->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_roles->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_name");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_roles->name->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_position");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_roles->position->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_assignable");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_roles->assignable->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_builtin");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_roles->builtin->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_builtin");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_roles->builtin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_issues_visibility");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_roles->issues_visibility->FldCaption()) ?>");

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
ftbrdm_rolesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_rolesadd.ValidateRequired = true;
<?php } else { ?>
ftbrdm_rolesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_roles_add->ShowPageHeader(); ?>
<?php
$tbrdm_roles_add->ShowMessage();
?>
<form name="ftbrdm_rolesadd" id="ftbrdm_rolesadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_roles">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tbrdm_rolesadd" class="table table-bordered table-striped">
<?php if ($tbrdm_roles->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbrdm_roles_id"><?php echo $tbrdm_roles->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_roles->id->CellAttributes() ?>>
<span id="el_tbrdm_roles_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $tbrdm_roles->id->PlaceHolder ?>" value="<?php echo $tbrdm_roles->id->EditValue ?>"<?php echo $tbrdm_roles->id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_roles->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_roles->name->Visible) { // name ?>
	<tr id="r_name">
		<td><span id="elh_tbrdm_roles_name"><?php echo $tbrdm_roles->name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_roles->name->CellAttributes() ?>>
<span id="el_tbrdm_roles_name" class="control-group">
<input type="text" data-field="x_name" name="x_name" id="x_name" size="30" maxlength="30" placeholder="<?php echo $tbrdm_roles->name->PlaceHolder ?>" value="<?php echo $tbrdm_roles->name->EditValue ?>"<?php echo $tbrdm_roles->name->EditAttributes() ?>>
</span>
<?php echo $tbrdm_roles->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_roles->position->Visible) { // position ?>
	<tr id="r_position">
		<td><span id="elh_tbrdm_roles_position"><?php echo $tbrdm_roles->position->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_roles->position->CellAttributes() ?>>
<span id="el_tbrdm_roles_position" class="control-group">
<input type="text" data-field="x_position" name="x_position" id="x_position" size="30" placeholder="<?php echo $tbrdm_roles->position->PlaceHolder ?>" value="<?php echo $tbrdm_roles->position->EditValue ?>"<?php echo $tbrdm_roles->position->EditAttributes() ?>>
</span>
<?php echo $tbrdm_roles->position->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_roles->assignable->Visible) { // assignable ?>
	<tr id="r_assignable">
		<td><span id="elh_tbrdm_roles_assignable"><?php echo $tbrdm_roles->assignable->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_roles->assignable->CellAttributes() ?>>
<span id="el_tbrdm_roles_assignable" class="control-group">
<input type="text" data-field="x_assignable" name="x_assignable" id="x_assignable" size="30" placeholder="<?php echo $tbrdm_roles->assignable->PlaceHolder ?>" value="<?php echo $tbrdm_roles->assignable->EditValue ?>"<?php echo $tbrdm_roles->assignable->EditAttributes() ?>>
</span>
<?php echo $tbrdm_roles->assignable->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_roles->builtin->Visible) { // builtin ?>
	<tr id="r_builtin">
		<td><span id="elh_tbrdm_roles_builtin"><?php echo $tbrdm_roles->builtin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_roles->builtin->CellAttributes() ?>>
<span id="el_tbrdm_roles_builtin" class="control-group">
<input type="text" data-field="x_builtin" name="x_builtin" id="x_builtin" size="30" placeholder="<?php echo $tbrdm_roles->builtin->PlaceHolder ?>" value="<?php echo $tbrdm_roles->builtin->EditValue ?>"<?php echo $tbrdm_roles->builtin->EditAttributes() ?>>
</span>
<?php echo $tbrdm_roles->builtin->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_roles->permissions->Visible) { // permissions ?>
	<tr id="r_permissions">
		<td><span id="elh_tbrdm_roles_permissions"><?php echo $tbrdm_roles->permissions->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_roles->permissions->CellAttributes() ?>>
<span id="el_tbrdm_roles_permissions" class="control-group">
<textarea data-field="x_permissions" name="x_permissions" id="x_permissions" cols="35" rows="4" placeholder="<?php echo $tbrdm_roles->permissions->PlaceHolder ?>"<?php echo $tbrdm_roles->permissions->EditAttributes() ?>><?php echo $tbrdm_roles->permissions->EditValue ?></textarea>
</span>
<?php echo $tbrdm_roles->permissions->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_roles->issues_visibility->Visible) { // issues_visibility ?>
	<tr id="r_issues_visibility">
		<td><span id="elh_tbrdm_roles_issues_visibility"><?php echo $tbrdm_roles->issues_visibility->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_roles->issues_visibility->CellAttributes() ?>>
<span id="el_tbrdm_roles_issues_visibility" class="control-group">
<input type="text" data-field="x_issues_visibility" name="x_issues_visibility" id="x_issues_visibility" size="30" maxlength="30" placeholder="<?php echo $tbrdm_roles->issues_visibility->PlaceHolder ?>" value="<?php echo $tbrdm_roles->issues_visibility->EditValue ?>"<?php echo $tbrdm_roles->issues_visibility->EditAttributes() ?>>
</span>
<?php echo $tbrdm_roles->issues_visibility->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftbrdm_rolesadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tbrdm_roles_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_roles_add->Page_Terminate();
?>
