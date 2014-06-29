<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "processocobit5info.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$processocobit5_add = NULL; // Initialize page object first

class cprocessocobit5_add extends cprocessocobit5 {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{DF922394-1B9A-486D-BA72-55BE4EF0B782}";

	// Table name
	var $TableName = 'processocobit5';

	// Page object name
	var $PageObjName = 'processocobit5_add';

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

		// Table object (processocobit5)
		if (!isset($GLOBALS["processocobit5"])) {
			$GLOBALS["processocobit5"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["processocobit5"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'processocobit5', TRUE);

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
			$this->Page_Terminate("processocobit5list.php");
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
			if (@$_GET["nu_processo"] != "") {
				$this->nu_processo->setQueryStringValue($_GET["nu_processo"]);
				$this->setKey("nu_processo", $this->nu_processo->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_processo", ""); // Clear key
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
					$this->Page_Terminate("processocobit5list.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "processocobit5view.php")
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
		$this->ic_dominio->CurrentValue = NULL;
		$this->ic_dominio->OldValue = $this->ic_dominio->CurrentValue;
		$this->co_alternativo->CurrentValue = NULL;
		$this->co_alternativo->OldValue = $this->co_alternativo->CurrentValue;
		$this->no_processo->CurrentValue = NULL;
		$this->no_processo->OldValue = $this->no_processo->CurrentValue;
		$this->ds_dominio->CurrentValue = NULL;
		$this->ds_dominio->OldValue = $this->ds_dominio->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ic_dominio->FldIsDetailKey) {
			$this->ic_dominio->setFormValue($objForm->GetValue("x_ic_dominio"));
		}
		if (!$this->co_alternativo->FldIsDetailKey) {
			$this->co_alternativo->setFormValue($objForm->GetValue("x_co_alternativo"));
		}
		if (!$this->no_processo->FldIsDetailKey) {
			$this->no_processo->setFormValue($objForm->GetValue("x_no_processo"));
		}
		if (!$this->ds_dominio->FldIsDetailKey) {
			$this->ds_dominio->setFormValue($objForm->GetValue("x_ds_dominio"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->ic_dominio->CurrentValue = $this->ic_dominio->FormValue;
		$this->co_alternativo->CurrentValue = $this->co_alternativo->FormValue;
		$this->no_processo->CurrentValue = $this->no_processo->FormValue;
		$this->ds_dominio->CurrentValue = $this->ds_dominio->FormValue;
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
		$this->ic_dominio->setDbValue($rs->fields('ic_dominio'));
		$this->nu_processo->setDbValue($rs->fields('nu_processo'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->no_processo->setDbValue($rs->fields('no_processo'));
		$this->ds_dominio->setDbValue($rs->fields('ds_dominio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ic_dominio->DbValue = $row['ic_dominio'];
		$this->nu_processo->DbValue = $row['nu_processo'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->no_processo->DbValue = $row['no_processo'];
		$this->ds_dominio->DbValue = $row['ds_dominio'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_processo")) <> "")
			$this->nu_processo->CurrentValue = $this->getKey("nu_processo"); // nu_processo
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
		// ic_dominio
		// nu_processo
		// co_alternativo
		// no_processo
		// ds_dominio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// ic_dominio
			if (strval($this->ic_dominio->CurrentValue) <> "") {
				switch ($this->ic_dominio->CurrentValue) {
					case $this->ic_dominio->FldTagValue(1):
						$this->ic_dominio->ViewValue = $this->ic_dominio->FldTagCaption(1) <> "" ? $this->ic_dominio->FldTagCaption(1) : $this->ic_dominio->CurrentValue;
						break;
					case $this->ic_dominio->FldTagValue(2):
						$this->ic_dominio->ViewValue = $this->ic_dominio->FldTagCaption(2) <> "" ? $this->ic_dominio->FldTagCaption(2) : $this->ic_dominio->CurrentValue;
						break;
					case $this->ic_dominio->FldTagValue(3):
						$this->ic_dominio->ViewValue = $this->ic_dominio->FldTagCaption(3) <> "" ? $this->ic_dominio->FldTagCaption(3) : $this->ic_dominio->CurrentValue;
						break;
					case $this->ic_dominio->FldTagValue(4):
						$this->ic_dominio->ViewValue = $this->ic_dominio->FldTagCaption(4) <> "" ? $this->ic_dominio->FldTagCaption(4) : $this->ic_dominio->CurrentValue;
						break;
					case $this->ic_dominio->FldTagValue(5):
						$this->ic_dominio->ViewValue = $this->ic_dominio->FldTagCaption(5) <> "" ? $this->ic_dominio->FldTagCaption(5) : $this->ic_dominio->CurrentValue;
						break;
					default:
						$this->ic_dominio->ViewValue = $this->ic_dominio->CurrentValue;
				}
			} else {
				$this->ic_dominio->ViewValue = NULL;
			}
			$this->ic_dominio->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// no_processo
			$this->no_processo->ViewValue = $this->no_processo->CurrentValue;
			$this->no_processo->ViewCustomAttributes = "";

			// ds_dominio
			$this->ds_dominio->ViewValue = $this->ds_dominio->CurrentValue;
			$this->ds_dominio->ViewCustomAttributes = "";

			// ic_dominio
			$this->ic_dominio->LinkCustomAttributes = "";
			$this->ic_dominio->HrefValue = "";
			$this->ic_dominio->TooltipValue = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// no_processo
			$this->no_processo->LinkCustomAttributes = "";
			$this->no_processo->HrefValue = "";
			$this->no_processo->TooltipValue = "";

			// ds_dominio
			$this->ds_dominio->LinkCustomAttributes = "";
			$this->ds_dominio->HrefValue = "";
			$this->ds_dominio->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// ic_dominio
			$this->ic_dominio->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_dominio->FldTagValue(1), $this->ic_dominio->FldTagCaption(1) <> "" ? $this->ic_dominio->FldTagCaption(1) : $this->ic_dominio->FldTagValue(1));
			$arwrk[] = array($this->ic_dominio->FldTagValue(2), $this->ic_dominio->FldTagCaption(2) <> "" ? $this->ic_dominio->FldTagCaption(2) : $this->ic_dominio->FldTagValue(2));
			$arwrk[] = array($this->ic_dominio->FldTagValue(3), $this->ic_dominio->FldTagCaption(3) <> "" ? $this->ic_dominio->FldTagCaption(3) : $this->ic_dominio->FldTagValue(3));
			$arwrk[] = array($this->ic_dominio->FldTagValue(4), $this->ic_dominio->FldTagCaption(4) <> "" ? $this->ic_dominio->FldTagCaption(4) : $this->ic_dominio->FldTagValue(4));
			$arwrk[] = array($this->ic_dominio->FldTagValue(5), $this->ic_dominio->FldTagCaption(5) <> "" ? $this->ic_dominio->FldTagCaption(5) : $this->ic_dominio->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_dominio->EditValue = $arwrk;

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "";
			$this->co_alternativo->EditValue = ew_HtmlEncode($this->co_alternativo->CurrentValue);
			$this->co_alternativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo->FldCaption()));

			// no_processo
			$this->no_processo->EditCustomAttributes = "";
			$this->no_processo->EditValue = ew_HtmlEncode($this->no_processo->CurrentValue);
			$this->no_processo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_processo->FldCaption()));

			// ds_dominio
			$this->ds_dominio->EditCustomAttributes = "";
			$this->ds_dominio->EditValue = $this->ds_dominio->CurrentValue;
			$this->ds_dominio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_dominio->FldCaption()));

			// Edit refer script
			// ic_dominio

			$this->ic_dominio->HrefValue = "";

			// co_alternativo
			$this->co_alternativo->HrefValue = "";

			// no_processo
			$this->no_processo->HrefValue = "";

			// ds_dominio
			$this->ds_dominio->HrefValue = "";
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
		if (!$this->ic_dominio->FldIsDetailKey && !is_null($this->ic_dominio->FormValue) && $this->ic_dominio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_dominio->FldCaption());
		}
		if (!$this->co_alternativo->FldIsDetailKey && !is_null($this->co_alternativo->FormValue) && $this->co_alternativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_alternativo->FldCaption());
		}
		if (!$this->no_processo->FldIsDetailKey && !is_null($this->no_processo->FormValue) && $this->no_processo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_processo->FldCaption());
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

		// ic_dominio
		$this->ic_dominio->SetDbValueDef($rsnew, $this->ic_dominio->CurrentValue, "", FALSE);

		// co_alternativo
		$this->co_alternativo->SetDbValueDef($rsnew, $this->co_alternativo->CurrentValue, "", FALSE);

		// no_processo
		$this->no_processo->SetDbValueDef($rsnew, $this->no_processo->CurrentValue, "", FALSE);

		// ds_dominio
		$this->ds_dominio->SetDbValueDef($rsnew, $this->ds_dominio->CurrentValue, NULL, FALSE);

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
			$this->nu_processo->setDbValue($conn->Insert_ID());
			$rsnew['nu_processo'] = $this->nu_processo->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "processocobit5list.php", $this->TableVar);
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
if (!isset($processocobit5_add)) $processocobit5_add = new cprocessocobit5_add();

// Page init
$processocobit5_add->Page_Init();

// Page main
$processocobit5_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$processocobit5_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var processocobit5_add = new ew_Page("processocobit5_add");
processocobit5_add.PageID = "add"; // Page ID
var EW_PAGE_ID = processocobit5_add.PageID; // For backward compatibility

// Form object
var fprocessocobit5add = new ew_Form("fprocessocobit5add");

// Validate form
fprocessocobit5add.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ic_dominio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($processocobit5->ic_dominio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_alternativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($processocobit5->co_alternativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_processo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($processocobit5->no_processo->FldCaption()) ?>");

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
fprocessocobit5add.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprocessocobit5add.ValidateRequired = true;
<?php } else { ?>
fprocessocobit5add.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $processocobit5_add->ShowPageHeader(); ?>
<?php
$processocobit5_add->ShowMessage();
?>
<form name="fprocessocobit5add" id="fprocessocobit5add" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="processocobit5">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_processocobit5add" class="table table-bordered table-striped">
<?php if ($processocobit5->ic_dominio->Visible) { // ic_dominio ?>
	<tr id="r_ic_dominio">
		<td><span id="elh_processocobit5_ic_dominio"><?php echo $processocobit5->ic_dominio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $processocobit5->ic_dominio->CellAttributes() ?>>
<span id="el_processocobit5_ic_dominio" class="control-group">
<select data-field="x_ic_dominio" id="x_ic_dominio" name="x_ic_dominio"<?php echo $processocobit5->ic_dominio->EditAttributes() ?>>
<?php
if (is_array($processocobit5->ic_dominio->EditValue)) {
	$arwrk = $processocobit5->ic_dominio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($processocobit5->ic_dominio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $processocobit5->ic_dominio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($processocobit5->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_processocobit5_co_alternativo"><?php echo $processocobit5->co_alternativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $processocobit5->co_alternativo->CellAttributes() ?>>
<span id="el_processocobit5_co_alternativo" class="control-group">
<input type="text" data-field="x_co_alternativo" name="x_co_alternativo" id="x_co_alternativo" size="30" maxlength="15" placeholder="<?php echo $processocobit5->co_alternativo->PlaceHolder ?>" value="<?php echo $processocobit5->co_alternativo->EditValue ?>"<?php echo $processocobit5->co_alternativo->EditAttributes() ?>>
</span>
<?php echo $processocobit5->co_alternativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($processocobit5->no_processo->Visible) { // no_processo ?>
	<tr id="r_no_processo">
		<td><span id="elh_processocobit5_no_processo"><?php echo $processocobit5->no_processo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $processocobit5->no_processo->CellAttributes() ?>>
<span id="el_processocobit5_no_processo" class="control-group">
<input type="text" data-field="x_no_processo" name="x_no_processo" id="x_no_processo" size="30" maxlength="120" placeholder="<?php echo $processocobit5->no_processo->PlaceHolder ?>" value="<?php echo $processocobit5->no_processo->EditValue ?>"<?php echo $processocobit5->no_processo->EditAttributes() ?>>
</span>
<?php echo $processocobit5->no_processo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($processocobit5->ds_dominio->Visible) { // ds_dominio ?>
	<tr id="r_ds_dominio">
		<td><span id="elh_processocobit5_ds_dominio"><?php echo $processocobit5->ds_dominio->FldCaption() ?></span></td>
		<td<?php echo $processocobit5->ds_dominio->CellAttributes() ?>>
<span id="el_processocobit5_ds_dominio" class="control-group">
<textarea data-field="x_ds_dominio" name="x_ds_dominio" id="x_ds_dominio" cols="35" rows="4" placeholder="<?php echo $processocobit5->ds_dominio->PlaceHolder ?>"<?php echo $processocobit5->ds_dominio->EditAttributes() ?>><?php echo $processocobit5->ds_dominio->EditValue ?></textarea>
</span>
<?php echo $processocobit5->ds_dominio->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fprocessocobit5add.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$processocobit5_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$processocobit5_add->Page_Terminate();
?>
