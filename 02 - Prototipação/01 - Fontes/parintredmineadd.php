<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "parintredmineinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$parintredmine_add = NULL; // Initialize page object first

class cparintredmine_add extends cparintredmine {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'parintredmine';

	// Page object name
	var $PageObjName = 'parintredmine_add';

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

		// Table object (parintredmine)
		if (!isset($GLOBALS["parintredmine"])) {
			$GLOBALS["parintredmine"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["parintredmine"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'parintredmine', TRUE);

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
			$this->Page_Terminate("parintredminelist.php");
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
			if (@$_GET["nu_parIntRedmine"] != "") {
				$this->nu_parIntRedmine->setQueryStringValue($_GET["nu_parIntRedmine"]);
				$this->setKey("nu_parIntRedmine", $this->nu_parIntRedmine->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_parIntRedmine", ""); // Clear key
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
					$this->Page_Terminate("parintredminelist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "parintredmineview.php")
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
		$this->no_parIntRedmine->CurrentValue = NULL;
		$this->no_parIntRedmine->OldValue = $this->no_parIntRedmine->CurrentValue;
		$this->ic_grupoParIntRedmine->CurrentValue = NULL;
		$this->ic_grupoParIntRedmine->OldValue = $this->ic_grupoParIntRedmine->CurrentValue;
		$this->vr_variavel->CurrentValue = NULL;
		$this->vr_variavel->OldValue = $this->vr_variavel->CurrentValue;
		$this->no_variavel->CurrentValue = NULL;
		$this->no_variavel->OldValue = $this->no_variavel->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->no_parIntRedmine->FldIsDetailKey) {
			$this->no_parIntRedmine->setFormValue($objForm->GetValue("x_no_parIntRedmine"));
		}
		if (!$this->ic_grupoParIntRedmine->FldIsDetailKey) {
			$this->ic_grupoParIntRedmine->setFormValue($objForm->GetValue("x_ic_grupoParIntRedmine"));
		}
		if (!$this->vr_variavel->FldIsDetailKey) {
			$this->vr_variavel->setFormValue($objForm->GetValue("x_vr_variavel"));
		}
		if (!$this->no_variavel->FldIsDetailKey) {
			$this->no_variavel->setFormValue($objForm->GetValue("x_no_variavel"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->no_parIntRedmine->CurrentValue = $this->no_parIntRedmine->FormValue;
		$this->ic_grupoParIntRedmine->CurrentValue = $this->ic_grupoParIntRedmine->FormValue;
		$this->vr_variavel->CurrentValue = $this->vr_variavel->FormValue;
		$this->no_variavel->CurrentValue = $this->no_variavel->FormValue;
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
		$this->nu_parIntRedmine->setDbValue($rs->fields('nu_parIntRedmine'));
		$this->no_parIntRedmine->setDbValue($rs->fields('no_parIntRedmine'));
		$this->ic_grupoParIntRedmine->setDbValue($rs->fields('ic_grupoParIntRedmine'));
		$this->vr_variavel->setDbValue($rs->fields('vr_variavel'));
		$this->no_variavel->setDbValue($rs->fields('no_variavel'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_parIntRedmine->DbValue = $row['nu_parIntRedmine'];
		$this->no_parIntRedmine->DbValue = $row['no_parIntRedmine'];
		$this->ic_grupoParIntRedmine->DbValue = $row['ic_grupoParIntRedmine'];
		$this->vr_variavel->DbValue = $row['vr_variavel'];
		$this->no_variavel->DbValue = $row['no_variavel'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_parIntRedmine")) <> "")
			$this->nu_parIntRedmine->CurrentValue = $this->getKey("nu_parIntRedmine"); // nu_parIntRedmine
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

		if ($this->vr_variavel->FormValue == $this->vr_variavel->CurrentValue && is_numeric(ew_StrToFloat($this->vr_variavel->CurrentValue)))
			$this->vr_variavel->CurrentValue = ew_StrToFloat($this->vr_variavel->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_parIntRedmine
		// no_parIntRedmine
		// ic_grupoParIntRedmine
		// vr_variavel
		// no_variavel

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_parIntRedmine
			$this->nu_parIntRedmine->ViewValue = $this->nu_parIntRedmine->CurrentValue;
			$this->nu_parIntRedmine->ViewCustomAttributes = "";

			// no_parIntRedmine
			$this->no_parIntRedmine->ViewValue = $this->no_parIntRedmine->CurrentValue;
			$this->no_parIntRedmine->ViewCustomAttributes = "";

			// ic_grupoParIntRedmine
			if (strval($this->ic_grupoParIntRedmine->CurrentValue) <> "") {
				switch ($this->ic_grupoParIntRedmine->CurrentValue) {
					case $this->ic_grupoParIntRedmine->FldTagValue(1):
						$this->ic_grupoParIntRedmine->ViewValue = $this->ic_grupoParIntRedmine->FldTagCaption(1) <> "" ? $this->ic_grupoParIntRedmine->FldTagCaption(1) : $this->ic_grupoParIntRedmine->CurrentValue;
						break;
					default:
						$this->ic_grupoParIntRedmine->ViewValue = $this->ic_grupoParIntRedmine->CurrentValue;
				}
			} else {
				$this->ic_grupoParIntRedmine->ViewValue = NULL;
			}
			$this->ic_grupoParIntRedmine->ViewCustomAttributes = "";

			// vr_variavel
			$this->vr_variavel->ViewValue = $this->vr_variavel->CurrentValue;
			$this->vr_variavel->ViewCustomAttributes = "";

			// no_variavel
			$this->no_variavel->ViewValue = $this->no_variavel->CurrentValue;
			$this->no_variavel->ViewCustomAttributes = "";

			// no_parIntRedmine
			$this->no_parIntRedmine->LinkCustomAttributes = "";
			$this->no_parIntRedmine->HrefValue = "";
			$this->no_parIntRedmine->TooltipValue = "";

			// ic_grupoParIntRedmine
			$this->ic_grupoParIntRedmine->LinkCustomAttributes = "";
			$this->ic_grupoParIntRedmine->HrefValue = "";
			$this->ic_grupoParIntRedmine->TooltipValue = "";

			// vr_variavel
			$this->vr_variavel->LinkCustomAttributes = "";
			$this->vr_variavel->HrefValue = "";
			$this->vr_variavel->TooltipValue = "";

			// no_variavel
			$this->no_variavel->LinkCustomAttributes = "";
			$this->no_variavel->HrefValue = "";
			$this->no_variavel->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// no_parIntRedmine
			$this->no_parIntRedmine->EditCustomAttributes = "";
			$this->no_parIntRedmine->EditValue = ew_HtmlEncode($this->no_parIntRedmine->CurrentValue);
			$this->no_parIntRedmine->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_parIntRedmine->FldCaption()));

			// ic_grupoParIntRedmine
			$this->ic_grupoParIntRedmine->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_grupoParIntRedmine->FldTagValue(1), $this->ic_grupoParIntRedmine->FldTagCaption(1) <> "" ? $this->ic_grupoParIntRedmine->FldTagCaption(1) : $this->ic_grupoParIntRedmine->FldTagValue(1));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_grupoParIntRedmine->EditValue = $arwrk;

			// vr_variavel
			$this->vr_variavel->EditCustomAttributes = "";
			$this->vr_variavel->EditValue = ew_HtmlEncode($this->vr_variavel->CurrentValue);
			$this->vr_variavel->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_variavel->FldCaption()));
			if (strval($this->vr_variavel->EditValue) <> "" && is_numeric($this->vr_variavel->EditValue)) $this->vr_variavel->EditValue = ew_FormatNumber($this->vr_variavel->EditValue, -2, -1, -2, 0);

			// no_variavel
			$this->no_variavel->EditCustomAttributes = "";
			$this->no_variavel->EditValue = ew_HtmlEncode($this->no_variavel->CurrentValue);
			$this->no_variavel->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_variavel->FldCaption()));

			// Edit refer script
			// no_parIntRedmine

			$this->no_parIntRedmine->HrefValue = "";

			// ic_grupoParIntRedmine
			$this->ic_grupoParIntRedmine->HrefValue = "";

			// vr_variavel
			$this->vr_variavel->HrefValue = "";

			// no_variavel
			$this->no_variavel->HrefValue = "";
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
		if (!$this->no_parIntRedmine->FldIsDetailKey && !is_null($this->no_parIntRedmine->FormValue) && $this->no_parIntRedmine->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_parIntRedmine->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_variavel->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_variavel->FldErrMsg());
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

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// no_parIntRedmine
		$this->no_parIntRedmine->SetDbValueDef($rsnew, $this->no_parIntRedmine->CurrentValue, "", FALSE);

		// ic_grupoParIntRedmine
		$this->ic_grupoParIntRedmine->SetDbValueDef($rsnew, $this->ic_grupoParIntRedmine->CurrentValue, NULL, FALSE);

		// vr_variavel
		$this->vr_variavel->SetDbValueDef($rsnew, $this->vr_variavel->CurrentValue, NULL, FALSE);

		// no_variavel
		$this->no_variavel->SetDbValueDef($rsnew, $this->no_variavel->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
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
			$this->nu_parIntRedmine->setDbValue($conn->Insert_ID());
			$rsnew['nu_parIntRedmine'] = $this->nu_parIntRedmine->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "parintredminelist.php", $this->TableVar);
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
if (!isset($parintredmine_add)) $parintredmine_add = new cparintredmine_add();

// Page init
$parintredmine_add->Page_Init();

// Page main
$parintredmine_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parintredmine_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var parintredmine_add = new ew_Page("parintredmine_add");
parintredmine_add.PageID = "add"; // Page ID
var EW_PAGE_ID = parintredmine_add.PageID; // For backward compatibility

// Form object
var fparintredmineadd = new ew_Form("fparintredmineadd");

// Validate form
fparintredmineadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_no_parIntRedmine");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($parintredmine->no_parIntRedmine->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_variavel");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($parintredmine->vr_variavel->FldErrMsg()) ?>");

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
fparintredmineadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparintredmineadd.ValidateRequired = true;
<?php } else { ?>
fparintredmineadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $parintredmine_add->ShowPageHeader(); ?>
<?php
$parintredmine_add->ShowMessage();
?>
<form name="fparintredmineadd" id="fparintredmineadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="parintredmine">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_parintredmineadd" class="table table-bordered table-striped">
<?php if ($parintredmine->no_parIntRedmine->Visible) { // no_parIntRedmine ?>
	<tr id="r_no_parIntRedmine">
		<td><span id="elh_parintredmine_no_parIntRedmine"><?php echo $parintredmine->no_parIntRedmine->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $parintredmine->no_parIntRedmine->CellAttributes() ?>>
<span id="el_parintredmine_no_parIntRedmine" class="control-group">
<input type="text" data-field="x_no_parIntRedmine" name="x_no_parIntRedmine" id="x_no_parIntRedmine" size="30" maxlength="50" placeholder="<?php echo $parintredmine->no_parIntRedmine->PlaceHolder ?>" value="<?php echo $parintredmine->no_parIntRedmine->EditValue ?>"<?php echo $parintredmine->no_parIntRedmine->EditAttributes() ?>>
</span>
<?php echo $parintredmine->no_parIntRedmine->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($parintredmine->ic_grupoParIntRedmine->Visible) { // ic_grupoParIntRedmine ?>
	<tr id="r_ic_grupoParIntRedmine">
		<td><span id="elh_parintredmine_ic_grupoParIntRedmine"><?php echo $parintredmine->ic_grupoParIntRedmine->FldCaption() ?></span></td>
		<td<?php echo $parintredmine->ic_grupoParIntRedmine->CellAttributes() ?>>
<span id="el_parintredmine_ic_grupoParIntRedmine" class="control-group">
<select data-field="x_ic_grupoParIntRedmine" id="x_ic_grupoParIntRedmine" name="x_ic_grupoParIntRedmine"<?php echo $parintredmine->ic_grupoParIntRedmine->EditAttributes() ?>>
<?php
if (is_array($parintredmine->ic_grupoParIntRedmine->EditValue)) {
	$arwrk = $parintredmine->ic_grupoParIntRedmine->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($parintredmine->ic_grupoParIntRedmine->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $parintredmine->ic_grupoParIntRedmine->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($parintredmine->vr_variavel->Visible) { // vr_variavel ?>
	<tr id="r_vr_variavel">
		<td><span id="elh_parintredmine_vr_variavel"><?php echo $parintredmine->vr_variavel->FldCaption() ?></span></td>
		<td<?php echo $parintredmine->vr_variavel->CellAttributes() ?>>
<span id="el_parintredmine_vr_variavel" class="control-group">
<input type="text" data-field="x_vr_variavel" name="x_vr_variavel" id="x_vr_variavel" size="30" placeholder="<?php echo $parintredmine->vr_variavel->PlaceHolder ?>" value="<?php echo $parintredmine->vr_variavel->EditValue ?>"<?php echo $parintredmine->vr_variavel->EditAttributes() ?>>
</span>
<?php echo $parintredmine->vr_variavel->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($parintredmine->no_variavel->Visible) { // no_variavel ?>
	<tr id="r_no_variavel">
		<td><span id="elh_parintredmine_no_variavel"><?php echo $parintredmine->no_variavel->FldCaption() ?></span></td>
		<td<?php echo $parintredmine->no_variavel->CellAttributes() ?>>
<span id="el_parintredmine_no_variavel" class="control-group">
<input type="text" data-field="x_no_variavel" name="x_no_variavel" id="x_no_variavel" size="30" maxlength="100" placeholder="<?php echo $parintredmine->no_variavel->PlaceHolder ?>" value="<?php echo $parintredmine->no_variavel->EditValue ?>"<?php echo $parintredmine->no_variavel->EditAttributes() ?>>
</span>
<?php echo $parintredmine->no_variavel->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fparintredmineadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$parintredmine_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$parintredmine_add->Page_Terminate();
?>
