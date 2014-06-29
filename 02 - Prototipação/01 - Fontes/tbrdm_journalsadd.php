<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_journalsinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_journals_add = NULL; // Initialize page object first

class ctbrdm_journals_add extends ctbrdm_journals {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{EC1DE12C-8807-4BF7-B5F7-28BA138CD7FC}";

	// Table name
	var $TableName = 'tbrdm_journals';

	// Page object name
	var $PageObjName = 'tbrdm_journals_add';

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

		// Table object (tbrdm_journals)
		if (!isset($GLOBALS["tbrdm_journals"])) {
			$GLOBALS["tbrdm_journals"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_journals"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_journals', TRUE);

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
			$this->Page_Terminate("tbrdm_journalslist.php");
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
					$this->Page_Terminate("tbrdm_journalslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "tbrdm_journalsview.php")
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
		$this->journalized_id->CurrentValue = NULL;
		$this->journalized_id->OldValue = $this->journalized_id->CurrentValue;
		$this->journalized_type->CurrentValue = NULL;
		$this->journalized_type->OldValue = $this->journalized_type->CurrentValue;
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->notes->CurrentValue = NULL;
		$this->notes->OldValue = $this->notes->CurrentValue;
		$this->created_on->CurrentValue = NULL;
		$this->created_on->OldValue = $this->created_on->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->journalized_id->FldIsDetailKey) {
			$this->journalized_id->setFormValue($objForm->GetValue("x_journalized_id"));
		}
		if (!$this->journalized_type->FldIsDetailKey) {
			$this->journalized_type->setFormValue($objForm->GetValue("x_journalized_type"));
		}
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->notes->FldIsDetailKey) {
			$this->notes->setFormValue($objForm->GetValue("x_notes"));
		}
		if (!$this->created_on->FldIsDetailKey) {
			$this->created_on->setFormValue($objForm->GetValue("x_created_on"));
			$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->journalized_id->CurrentValue = $this->journalized_id->FormValue;
		$this->journalized_type->CurrentValue = $this->journalized_type->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->notes->CurrentValue = $this->notes->FormValue;
		$this->created_on->CurrentValue = $this->created_on->FormValue;
		$this->created_on->CurrentValue = ew_UnFormatDateTime($this->created_on->CurrentValue, 7);
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
		$this->journalized_id->setDbValue($rs->fields('journalized_id'));
		$this->journalized_type->setDbValue($rs->fields('journalized_type'));
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->notes->setDbValue($rs->fields('notes'));
		$this->created_on->setDbValue($rs->fields('created_on'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->journalized_id->DbValue = $row['journalized_id'];
		$this->journalized_type->DbValue = $row['journalized_type'];
		$this->user_id->DbValue = $row['user_id'];
		$this->notes->DbValue = $row['notes'];
		$this->created_on->DbValue = $row['created_on'];
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
		// journalized_id
		// journalized_type
		// user_id
		// notes
		// created_on

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// journalized_id
			$this->journalized_id->ViewValue = $this->journalized_id->CurrentValue;
			$this->journalized_id->ViewCustomAttributes = "";

			// journalized_type
			$this->journalized_type->ViewValue = $this->journalized_type->CurrentValue;
			$this->journalized_type->ViewCustomAttributes = "";

			// user_id
			$this->user_id->ViewValue = $this->user_id->CurrentValue;
			$this->user_id->ViewCustomAttributes = "";

			// notes
			$this->notes->ViewValue = $this->notes->CurrentValue;
			$this->notes->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
			$this->created_on->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// journalized_id
			$this->journalized_id->LinkCustomAttributes = "";
			$this->journalized_id->HrefValue = "";
			$this->journalized_id->TooltipValue = "";

			// journalized_type
			$this->journalized_type->LinkCustomAttributes = "";
			$this->journalized_type->HrefValue = "";
			$this->journalized_type->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// notes
			$this->notes->LinkCustomAttributes = "";
			$this->notes->HrefValue = "";
			$this->notes->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// journalized_id
			$this->journalized_id->EditCustomAttributes = "";
			$this->journalized_id->EditValue = ew_HtmlEncode($this->journalized_id->CurrentValue);
			$this->journalized_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->journalized_id->FldCaption()));

			// journalized_type
			$this->journalized_type->EditCustomAttributes = "";
			$this->journalized_type->EditValue = ew_HtmlEncode($this->journalized_type->CurrentValue);
			$this->journalized_type->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->journalized_type->FldCaption()));

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);
			$this->user_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->user_id->FldCaption()));

			// notes
			$this->notes->EditCustomAttributes = "";
			$this->notes->EditValue = $this->notes->CurrentValue;
			$this->notes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->notes->FldCaption()));

			// created_on
			$this->created_on->EditCustomAttributes = "";
			$this->created_on->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created_on->CurrentValue, 7));
			$this->created_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->created_on->FldCaption()));

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// journalized_id
			$this->journalized_id->HrefValue = "";

			// journalized_type
			$this->journalized_type->HrefValue = "";

			// user_id
			$this->user_id->HrefValue = "";

			// notes
			$this->notes->HrefValue = "";

			// created_on
			$this->created_on->HrefValue = "";
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
		if (!$this->journalized_id->FldIsDetailKey && !is_null($this->journalized_id->FormValue) && $this->journalized_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->journalized_id->FldCaption());
		}
		if (!ew_CheckInteger($this->journalized_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->journalized_id->FldErrMsg());
		}
		if (!$this->journalized_type->FldIsDetailKey && !is_null($this->journalized_type->FormValue) && $this->journalized_type->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->journalized_type->FldCaption());
		}
		if (!$this->user_id->FldIsDetailKey && !is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->user_id->FldCaption());
		}
		if (!ew_CheckInteger($this->user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->user_id->FldErrMsg());
		}
		if (!$this->created_on->FldIsDetailKey && !is_null($this->created_on->FormValue) && $this->created_on->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->created_on->FldCaption());
		}
		if (!ew_CheckEuroDate($this->created_on->FormValue)) {
			ew_AddMessage($gsFormError, $this->created_on->FldErrMsg());
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

		// journalized_id
		$this->journalized_id->SetDbValueDef($rsnew, $this->journalized_id->CurrentValue, 0, FALSE);

		// journalized_type
		$this->journalized_type->SetDbValueDef($rsnew, $this->journalized_type->CurrentValue, "", FALSE);

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, 0, FALSE);

		// notes
		$this->notes->SetDbValueDef($rsnew, $this->notes->CurrentValue, NULL, FALSE);

		// created_on
		$this->created_on->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created_on->CurrentValue, 7), ew_CurrentDate(), FALSE);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tbrdm_journalslist.php", $this->TableVar);
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
if (!isset($tbrdm_journals_add)) $tbrdm_journals_add = new ctbrdm_journals_add();

// Page init
$tbrdm_journals_add->Page_Init();

// Page main
$tbrdm_journals_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_journals_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tbrdm_journals_add = new ew_Page("tbrdm_journals_add");
tbrdm_journals_add.PageID = "add"; // Page ID
var EW_PAGE_ID = tbrdm_journals_add.PageID; // For backward compatibility

// Form object
var ftbrdm_journalsadd = new ew_Form("ftbrdm_journalsadd");

// Validate form
ftbrdm_journalsadd.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_journals->id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_journals->id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_journalized_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_journals->journalized_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_journalized_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_journals->journalized_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_journalized_type");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_journals->journalized_type->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_journals->user_id->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_journals->user_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_created_on");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($tbrdm_journals->created_on->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_created_on");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($tbrdm_journals->created_on->FldErrMsg()) ?>");

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
ftbrdm_journalsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_journalsadd.ValidateRequired = true;
<?php } else { ?>
ftbrdm_journalsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $tbrdm_journals_add->ShowPageHeader(); ?>
<?php
$tbrdm_journals_add->ShowMessage();
?>
<form name="ftbrdm_journalsadd" id="ftbrdm_journalsadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_journals">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_tbrdm_journalsadd" class="table table-bordered table-striped">
<?php if ($tbrdm_journals->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_tbrdm_journals_id"><?php echo $tbrdm_journals->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_journals->id->CellAttributes() ?>>
<span id="el_tbrdm_journals_id" class="control-group">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $tbrdm_journals->id->PlaceHolder ?>" value="<?php echo $tbrdm_journals->id->EditValue ?>"<?php echo $tbrdm_journals->id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_journals->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_journals->journalized_id->Visible) { // journalized_id ?>
	<tr id="r_journalized_id">
		<td><span id="elh_tbrdm_journals_journalized_id"><?php echo $tbrdm_journals->journalized_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_journals->journalized_id->CellAttributes() ?>>
<span id="el_tbrdm_journals_journalized_id" class="control-group">
<input type="text" data-field="x_journalized_id" name="x_journalized_id" id="x_journalized_id" size="30" placeholder="<?php echo $tbrdm_journals->journalized_id->PlaceHolder ?>" value="<?php echo $tbrdm_journals->journalized_id->EditValue ?>"<?php echo $tbrdm_journals->journalized_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_journals->journalized_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_journals->journalized_type->Visible) { // journalized_type ?>
	<tr id="r_journalized_type">
		<td><span id="elh_tbrdm_journals_journalized_type"><?php echo $tbrdm_journals->journalized_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_journals->journalized_type->CellAttributes() ?>>
<span id="el_tbrdm_journals_journalized_type" class="control-group">
<input type="text" data-field="x_journalized_type" name="x_journalized_type" id="x_journalized_type" size="30" maxlength="30" placeholder="<?php echo $tbrdm_journals->journalized_type->PlaceHolder ?>" value="<?php echo $tbrdm_journals->journalized_type->EditValue ?>"<?php echo $tbrdm_journals->journalized_type->EditAttributes() ?>>
</span>
<?php echo $tbrdm_journals->journalized_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_journals->user_id->Visible) { // user_id ?>
	<tr id="r_user_id">
		<td><span id="elh_tbrdm_journals_user_id"><?php echo $tbrdm_journals->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_journals->user_id->CellAttributes() ?>>
<span id="el_tbrdm_journals_user_id" class="control-group">
<input type="text" data-field="x_user_id" name="x_user_id" id="x_user_id" size="30" placeholder="<?php echo $tbrdm_journals->user_id->PlaceHolder ?>" value="<?php echo $tbrdm_journals->user_id->EditValue ?>"<?php echo $tbrdm_journals->user_id->EditAttributes() ?>>
</span>
<?php echo $tbrdm_journals->user_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_journals->notes->Visible) { // notes ?>
	<tr id="r_notes">
		<td><span id="elh_tbrdm_journals_notes"><?php echo $tbrdm_journals->notes->FldCaption() ?></span></td>
		<td<?php echo $tbrdm_journals->notes->CellAttributes() ?>>
<span id="el_tbrdm_journals_notes" class="control-group">
<textarea data-field="x_notes" name="x_notes" id="x_notes" cols="35" rows="4" placeholder="<?php echo $tbrdm_journals->notes->PlaceHolder ?>"<?php echo $tbrdm_journals->notes->EditAttributes() ?>><?php echo $tbrdm_journals->notes->EditValue ?></textarea>
</span>
<?php echo $tbrdm_journals->notes->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tbrdm_journals->created_on->Visible) { // created_on ?>
	<tr id="r_created_on">
		<td><span id="elh_tbrdm_journals_created_on"><?php echo $tbrdm_journals->created_on->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $tbrdm_journals->created_on->CellAttributes() ?>>
<span id="el_tbrdm_journals_created_on" class="control-group">
<input type="text" data-field="x_created_on" name="x_created_on" id="x_created_on" placeholder="<?php echo $tbrdm_journals->created_on->PlaceHolder ?>" value="<?php echo $tbrdm_journals->created_on->EditValue ?>"<?php echo $tbrdm_journals->created_on->EditAttributes() ?>>
</span>
<?php echo $tbrdm_journals->created_on->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
ftbrdm_journalsadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$tbrdm_journals_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tbrdm_journals_add->Page_Terminate();
?>
