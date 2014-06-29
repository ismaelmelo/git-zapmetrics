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

$processocobit5_edit = NULL; // Initialize page object first

class cprocessocobit5_edit extends cprocessocobit5 {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{DF922394-1B9A-486D-BA72-55BE4EF0B782}";

	// Table name
	var $TableName = 'processocobit5';

	// Page object name
	var $PageObjName = 'processocobit5_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["nu_processo"] <> "") {
			$this->nu_processo->setQueryStringValue($_GET["nu_processo"]);
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->nu_processo->CurrentValue == "")
			$this->Page_Terminate("processocobit5list.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("processocobit5list.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
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
		if (!$this->nu_processo->FldIsDetailKey)
			$this->nu_processo->setFormValue($objForm->GetValue("x_nu_processo"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_processo->CurrentValue = $this->nu_processo->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// ic_dominio
			$this->ic_dominio->EditCustomAttributes = "";
			if (strval($this->ic_dominio->CurrentValue) <> "") {
				switch ($this->ic_dominio->CurrentValue) {
					case $this->ic_dominio->FldTagValue(1):
						$this->ic_dominio->EditValue = $this->ic_dominio->FldTagCaption(1) <> "" ? $this->ic_dominio->FldTagCaption(1) : $this->ic_dominio->CurrentValue;
						break;
					case $this->ic_dominio->FldTagValue(2):
						$this->ic_dominio->EditValue = $this->ic_dominio->FldTagCaption(2) <> "" ? $this->ic_dominio->FldTagCaption(2) : $this->ic_dominio->CurrentValue;
						break;
					case $this->ic_dominio->FldTagValue(3):
						$this->ic_dominio->EditValue = $this->ic_dominio->FldTagCaption(3) <> "" ? $this->ic_dominio->FldTagCaption(3) : $this->ic_dominio->CurrentValue;
						break;
					case $this->ic_dominio->FldTagValue(4):
						$this->ic_dominio->EditValue = $this->ic_dominio->FldTagCaption(4) <> "" ? $this->ic_dominio->FldTagCaption(4) : $this->ic_dominio->CurrentValue;
						break;
					case $this->ic_dominio->FldTagValue(5):
						$this->ic_dominio->EditValue = $this->ic_dominio->FldTagCaption(5) <> "" ? $this->ic_dominio->FldTagCaption(5) : $this->ic_dominio->CurrentValue;
						break;
					default:
						$this->ic_dominio->EditValue = $this->ic_dominio->CurrentValue;
				}
			} else {
				$this->ic_dominio->EditValue = NULL;
			}
			$this->ic_dominio->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "";
			$this->co_alternativo->EditValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// no_processo
			$this->no_processo->SetDbValueDef($rsnew, $this->no_processo->CurrentValue, "", $this->no_processo->ReadOnly);

			// ds_dominio
			$this->ds_dominio->SetDbValueDef($rsnew, $this->ds_dominio->CurrentValue, NULL, $this->ds_dominio->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "processocobit5list.php", $this->TableVar);
		$PageCaption = $Language->Phrase("edit");
		$Breadcrumb->Add("edit", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($processocobit5_edit)) $processocobit5_edit = new cprocessocobit5_edit();

// Page init
$processocobit5_edit->Page_Init();

// Page main
$processocobit5_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$processocobit5_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var processocobit5_edit = new ew_Page("processocobit5_edit");
processocobit5_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = processocobit5_edit.PageID; // For backward compatibility

// Form object
var fprocessocobit5edit = new ew_Form("fprocessocobit5edit");

// Validate form
fprocessocobit5edit.Validate = function() {
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
fprocessocobit5edit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprocessocobit5edit.ValidateRequired = true;
<?php } else { ?>
fprocessocobit5edit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $processocobit5_edit->ShowPageHeader(); ?>
<?php
$processocobit5_edit->ShowMessage();
?>
<form name="fprocessocobit5edit" id="fprocessocobit5edit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="processocobit5">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_processocobit5edit" class="table table-bordered table-striped">
<?php if ($processocobit5->ic_dominio->Visible) { // ic_dominio ?>
	<tr id="r_ic_dominio">
		<td><span id="elh_processocobit5_ic_dominio"><?php echo $processocobit5->ic_dominio->FldCaption() ?></span></td>
		<td<?php echo $processocobit5->ic_dominio->CellAttributes() ?>>
<span id="el_processocobit5_ic_dominio" class="control-group">
<span<?php echo $processocobit5->ic_dominio->ViewAttributes() ?>>
<?php echo $processocobit5->ic_dominio->EditValue ?></span>
</span>
<input type="hidden" data-field="x_ic_dominio" name="x_ic_dominio" id="x_ic_dominio" value="<?php echo ew_HtmlEncode($processocobit5->ic_dominio->CurrentValue) ?>">
<?php echo $processocobit5->ic_dominio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($processocobit5->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_processocobit5_co_alternativo"><?php echo $processocobit5->co_alternativo->FldCaption() ?></span></td>
		<td<?php echo $processocobit5->co_alternativo->CellAttributes() ?>>
<span id="el_processocobit5_co_alternativo" class="control-group">
<span<?php echo $processocobit5->co_alternativo->ViewAttributes() ?>>
<?php echo $processocobit5->co_alternativo->EditValue ?></span>
</span>
<input type="hidden" data-field="x_co_alternativo" name="x_co_alternativo" id="x_co_alternativo" value="<?php echo ew_HtmlEncode($processocobit5->co_alternativo->CurrentValue) ?>">
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
<input type="hidden" data-field="x_nu_processo" name="x_nu_processo" id="x_nu_processo" value="<?php echo ew_HtmlEncode($processocobit5->nu_processo->CurrentValue) ?>">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fprocessocobit5edit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$processocobit5_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$processocobit5_edit->Page_Terminate();
?>
