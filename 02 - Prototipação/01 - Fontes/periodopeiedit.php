<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "periodopeiinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$periodopei_edit = NULL; // Initialize page object first

class cperiodopei_edit extends cperiodopei {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'periodopei';

	// Page object name
	var $PageObjName = 'periodopei_edit';

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

		// Table object (periodopei)
		if (!isset($GLOBALS["periodopei"])) {
			$GLOBALS["periodopei"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["periodopei"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'periodopei', TRUE);

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
			$this->Page_Terminate("periodopeilist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action
		$this->nu_periodoPei->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["nu_periodoPei"] <> "") {
			$this->nu_periodoPei->setQueryStringValue($_GET["nu_periodoPei"]);
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
		if ($this->nu_periodoPei->CurrentValue == "")
			$this->Page_Terminate("periodopeilist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("periodopeilist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "periodopeiview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to View page directly
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
		if (!$this->nu_periodoPei->FldIsDetailKey)
			$this->nu_periodoPei->setFormValue($objForm->GetValue("x_nu_periodoPei"));
		if (!$this->nu_anoInicio->FldIsDetailKey) {
			$this->nu_anoInicio->setFormValue($objForm->GetValue("x_nu_anoInicio"));
		}
		if (!$this->nu_anoFim->FldIsDetailKey) {
			$this->nu_anoFim->setFormValue($objForm->GetValue("x_nu_anoFim"));
		}
		if (!$this->no_periodo->FldIsDetailKey) {
			$this->no_periodo->setFormValue($objForm->GetValue("x_no_periodo"));
		}
		if (!$this->ic_situacao->FldIsDetailKey) {
			$this->ic_situacao->setFormValue($objForm->GetValue("x_ic_situacao"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->nu_periodoPei->CurrentValue = $this->nu_periodoPei->FormValue;
		$this->nu_anoInicio->CurrentValue = $this->nu_anoInicio->FormValue;
		$this->nu_anoFim->CurrentValue = $this->nu_anoFim->FormValue;
		$this->no_periodo->CurrentValue = $this->no_periodo->FormValue;
		$this->ic_situacao->CurrentValue = $this->ic_situacao->FormValue;
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
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		$this->nu_anoInicio->setDbValue($rs->fields('nu_anoInicio'));
		$this->nu_anoFim->setDbValue($rs->fields('nu_anoFim'));
		$this->no_periodo->setDbValue($rs->fields('no_periodo'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_periodoPei->DbValue = $row['nu_periodoPei'];
		$this->nu_anoInicio->DbValue = $row['nu_anoInicio'];
		$this->nu_anoFim->DbValue = $row['nu_anoFim'];
		$this->no_periodo->DbValue = $row['no_periodo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_periodoPei
		// nu_anoInicio
		// nu_anoFim
		// no_periodo
		// ic_situacao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_periodoPei
			$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->CurrentValue;
			$this->nu_periodoPei->ViewCustomAttributes = "";

			// nu_anoInicio
			if (strval($this->nu_anoInicio->CurrentValue) <> "") {
				switch ($this->nu_anoInicio->CurrentValue) {
					case $this->nu_anoInicio->FldTagValue(1):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(1) <> "" ? $this->nu_anoInicio->FldTagCaption(1) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(2):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(2) <> "" ? $this->nu_anoInicio->FldTagCaption(2) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(3):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(3) <> "" ? $this->nu_anoInicio->FldTagCaption(3) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(4):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(4) <> "" ? $this->nu_anoInicio->FldTagCaption(4) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(5):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(5) <> "" ? $this->nu_anoInicio->FldTagCaption(5) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(6):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(6) <> "" ? $this->nu_anoInicio->FldTagCaption(6) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(7):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(7) <> "" ? $this->nu_anoInicio->FldTagCaption(7) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(8):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(8) <> "" ? $this->nu_anoInicio->FldTagCaption(8) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(9):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(9) <> "" ? $this->nu_anoInicio->FldTagCaption(9) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(10):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(10) <> "" ? $this->nu_anoInicio->FldTagCaption(10) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(11):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(11) <> "" ? $this->nu_anoInicio->FldTagCaption(11) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(12):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(12) <> "" ? $this->nu_anoInicio->FldTagCaption(12) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(13):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(13) <> "" ? $this->nu_anoInicio->FldTagCaption(13) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(14):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(14) <> "" ? $this->nu_anoInicio->FldTagCaption(14) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(15):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(15) <> "" ? $this->nu_anoInicio->FldTagCaption(15) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(16):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(16) <> "" ? $this->nu_anoInicio->FldTagCaption(16) : $this->nu_anoInicio->CurrentValue;
						break;
					case $this->nu_anoInicio->FldTagValue(17):
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->FldTagCaption(17) <> "" ? $this->nu_anoInicio->FldTagCaption(17) : $this->nu_anoInicio->CurrentValue;
						break;
					default:
						$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->CurrentValue;
				}
			} else {
				$this->nu_anoInicio->ViewValue = NULL;
			}
			$this->nu_anoInicio->ViewCustomAttributes = "";

			// nu_anoFim
			if (strval($this->nu_anoFim->CurrentValue) <> "") {
				switch ($this->nu_anoFim->CurrentValue) {
					case $this->nu_anoFim->FldTagValue(1):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(1) <> "" ? $this->nu_anoFim->FldTagCaption(1) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(2):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(2) <> "" ? $this->nu_anoFim->FldTagCaption(2) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(3):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(3) <> "" ? $this->nu_anoFim->FldTagCaption(3) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(4):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(4) <> "" ? $this->nu_anoFim->FldTagCaption(4) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(5):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(5) <> "" ? $this->nu_anoFim->FldTagCaption(5) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(6):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(6) <> "" ? $this->nu_anoFim->FldTagCaption(6) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(7):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(7) <> "" ? $this->nu_anoFim->FldTagCaption(7) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(8):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(8) <> "" ? $this->nu_anoFim->FldTagCaption(8) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(9):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(9) <> "" ? $this->nu_anoFim->FldTagCaption(9) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(10):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(10) <> "" ? $this->nu_anoFim->FldTagCaption(10) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(11):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(11) <> "" ? $this->nu_anoFim->FldTagCaption(11) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(12):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(12) <> "" ? $this->nu_anoFim->FldTagCaption(12) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(13):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(13) <> "" ? $this->nu_anoFim->FldTagCaption(13) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(14):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(14) <> "" ? $this->nu_anoFim->FldTagCaption(14) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(15):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(15) <> "" ? $this->nu_anoFim->FldTagCaption(15) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(16):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(16) <> "" ? $this->nu_anoFim->FldTagCaption(16) : $this->nu_anoFim->CurrentValue;
						break;
					case $this->nu_anoFim->FldTagValue(17):
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->FldTagCaption(17) <> "" ? $this->nu_anoFim->FldTagCaption(17) : $this->nu_anoFim->CurrentValue;
						break;
					default:
						$this->nu_anoFim->ViewValue = $this->nu_anoFim->CurrentValue;
				}
			} else {
				$this->nu_anoFim->ViewValue = NULL;
			}
			$this->nu_anoFim->ViewCustomAttributes = "";

			// no_periodo
			$this->no_periodo->ViewValue = $this->no_periodo->CurrentValue;
			$this->no_periodo->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(3):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(4):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_periodoPei
			$this->nu_periodoPei->LinkCustomAttributes = "";
			$this->nu_periodoPei->HrefValue = "";
			$this->nu_periodoPei->TooltipValue = "";

			// nu_anoInicio
			$this->nu_anoInicio->LinkCustomAttributes = "";
			$this->nu_anoInicio->HrefValue = "";
			$this->nu_anoInicio->TooltipValue = "";

			// nu_anoFim
			$this->nu_anoFim->LinkCustomAttributes = "";
			$this->nu_anoFim->HrefValue = "";
			$this->nu_anoFim->TooltipValue = "";

			// no_periodo
			$this->no_periodo->LinkCustomAttributes = "";
			$this->no_periodo->HrefValue = "";
			$this->no_periodo->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// nu_periodoPei
			$this->nu_periodoPei->EditCustomAttributes = "";
			$this->nu_periodoPei->EditValue = $this->nu_periodoPei->CurrentValue;
			$this->nu_periodoPei->ViewCustomAttributes = "";

			// nu_anoInicio
			$this->nu_anoInicio->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(1), $this->nu_anoInicio->FldTagCaption(1) <> "" ? $this->nu_anoInicio->FldTagCaption(1) : $this->nu_anoInicio->FldTagValue(1));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(2), $this->nu_anoInicio->FldTagCaption(2) <> "" ? $this->nu_anoInicio->FldTagCaption(2) : $this->nu_anoInicio->FldTagValue(2));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(3), $this->nu_anoInicio->FldTagCaption(3) <> "" ? $this->nu_anoInicio->FldTagCaption(3) : $this->nu_anoInicio->FldTagValue(3));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(4), $this->nu_anoInicio->FldTagCaption(4) <> "" ? $this->nu_anoInicio->FldTagCaption(4) : $this->nu_anoInicio->FldTagValue(4));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(5), $this->nu_anoInicio->FldTagCaption(5) <> "" ? $this->nu_anoInicio->FldTagCaption(5) : $this->nu_anoInicio->FldTagValue(5));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(6), $this->nu_anoInicio->FldTagCaption(6) <> "" ? $this->nu_anoInicio->FldTagCaption(6) : $this->nu_anoInicio->FldTagValue(6));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(7), $this->nu_anoInicio->FldTagCaption(7) <> "" ? $this->nu_anoInicio->FldTagCaption(7) : $this->nu_anoInicio->FldTagValue(7));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(8), $this->nu_anoInicio->FldTagCaption(8) <> "" ? $this->nu_anoInicio->FldTagCaption(8) : $this->nu_anoInicio->FldTagValue(8));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(9), $this->nu_anoInicio->FldTagCaption(9) <> "" ? $this->nu_anoInicio->FldTagCaption(9) : $this->nu_anoInicio->FldTagValue(9));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(10), $this->nu_anoInicio->FldTagCaption(10) <> "" ? $this->nu_anoInicio->FldTagCaption(10) : $this->nu_anoInicio->FldTagValue(10));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(11), $this->nu_anoInicio->FldTagCaption(11) <> "" ? $this->nu_anoInicio->FldTagCaption(11) : $this->nu_anoInicio->FldTagValue(11));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(12), $this->nu_anoInicio->FldTagCaption(12) <> "" ? $this->nu_anoInicio->FldTagCaption(12) : $this->nu_anoInicio->FldTagValue(12));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(13), $this->nu_anoInicio->FldTagCaption(13) <> "" ? $this->nu_anoInicio->FldTagCaption(13) : $this->nu_anoInicio->FldTagValue(13));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(14), $this->nu_anoInicio->FldTagCaption(14) <> "" ? $this->nu_anoInicio->FldTagCaption(14) : $this->nu_anoInicio->FldTagValue(14));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(15), $this->nu_anoInicio->FldTagCaption(15) <> "" ? $this->nu_anoInicio->FldTagCaption(15) : $this->nu_anoInicio->FldTagValue(15));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(16), $this->nu_anoInicio->FldTagCaption(16) <> "" ? $this->nu_anoInicio->FldTagCaption(16) : $this->nu_anoInicio->FldTagValue(16));
			$arwrk[] = array($this->nu_anoInicio->FldTagValue(17), $this->nu_anoInicio->FldTagCaption(17) <> "" ? $this->nu_anoInicio->FldTagCaption(17) : $this->nu_anoInicio->FldTagValue(17));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->nu_anoInicio->EditValue = $arwrk;

			// nu_anoFim
			$this->nu_anoFim->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->nu_anoFim->FldTagValue(1), $this->nu_anoFim->FldTagCaption(1) <> "" ? $this->nu_anoFim->FldTagCaption(1) : $this->nu_anoFim->FldTagValue(1));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(2), $this->nu_anoFim->FldTagCaption(2) <> "" ? $this->nu_anoFim->FldTagCaption(2) : $this->nu_anoFim->FldTagValue(2));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(3), $this->nu_anoFim->FldTagCaption(3) <> "" ? $this->nu_anoFim->FldTagCaption(3) : $this->nu_anoFim->FldTagValue(3));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(4), $this->nu_anoFim->FldTagCaption(4) <> "" ? $this->nu_anoFim->FldTagCaption(4) : $this->nu_anoFim->FldTagValue(4));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(5), $this->nu_anoFim->FldTagCaption(5) <> "" ? $this->nu_anoFim->FldTagCaption(5) : $this->nu_anoFim->FldTagValue(5));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(6), $this->nu_anoFim->FldTagCaption(6) <> "" ? $this->nu_anoFim->FldTagCaption(6) : $this->nu_anoFim->FldTagValue(6));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(7), $this->nu_anoFim->FldTagCaption(7) <> "" ? $this->nu_anoFim->FldTagCaption(7) : $this->nu_anoFim->FldTagValue(7));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(8), $this->nu_anoFim->FldTagCaption(8) <> "" ? $this->nu_anoFim->FldTagCaption(8) : $this->nu_anoFim->FldTagValue(8));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(9), $this->nu_anoFim->FldTagCaption(9) <> "" ? $this->nu_anoFim->FldTagCaption(9) : $this->nu_anoFim->FldTagValue(9));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(10), $this->nu_anoFim->FldTagCaption(10) <> "" ? $this->nu_anoFim->FldTagCaption(10) : $this->nu_anoFim->FldTagValue(10));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(11), $this->nu_anoFim->FldTagCaption(11) <> "" ? $this->nu_anoFim->FldTagCaption(11) : $this->nu_anoFim->FldTagValue(11));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(12), $this->nu_anoFim->FldTagCaption(12) <> "" ? $this->nu_anoFim->FldTagCaption(12) : $this->nu_anoFim->FldTagValue(12));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(13), $this->nu_anoFim->FldTagCaption(13) <> "" ? $this->nu_anoFim->FldTagCaption(13) : $this->nu_anoFim->FldTagValue(13));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(14), $this->nu_anoFim->FldTagCaption(14) <> "" ? $this->nu_anoFim->FldTagCaption(14) : $this->nu_anoFim->FldTagValue(14));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(15), $this->nu_anoFim->FldTagCaption(15) <> "" ? $this->nu_anoFim->FldTagCaption(15) : $this->nu_anoFim->FldTagValue(15));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(16), $this->nu_anoFim->FldTagCaption(16) <> "" ? $this->nu_anoFim->FldTagCaption(16) : $this->nu_anoFim->FldTagValue(16));
			$arwrk[] = array($this->nu_anoFim->FldTagValue(17), $this->nu_anoFim->FldTagCaption(17) <> "" ? $this->nu_anoFim->FldTagCaption(17) : $this->nu_anoFim->FldTagValue(17));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->nu_anoFim->EditValue = $arwrk;

			// no_periodo
			$this->no_periodo->EditCustomAttributes = "";
			$this->no_periodo->EditValue = ew_HtmlEncode($this->no_periodo->CurrentValue);
			$this->no_periodo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_periodo->FldCaption()));

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			$arwrk[] = array($this->ic_situacao->FldTagValue(3), $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->FldTagValue(3));
			$arwrk[] = array($this->ic_situacao->FldTagValue(4), $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;

			// Edit refer script
			// nu_periodoPei

			$this->nu_periodoPei->HrefValue = "";

			// nu_anoInicio
			$this->nu_anoInicio->HrefValue = "";

			// nu_anoFim
			$this->nu_anoFim->HrefValue = "";

			// no_periodo
			$this->no_periodo->HrefValue = "";

			// ic_situacao
			$this->ic_situacao->HrefValue = "";
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
		if (!$this->nu_anoInicio->FldIsDetailKey && !is_null($this->nu_anoInicio->FormValue) && $this->nu_anoInicio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_anoInicio->FldCaption());
		}
		if (!$this->nu_anoFim->FldIsDetailKey && !is_null($this->nu_anoFim->FormValue) && $this->nu_anoFim->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_anoFim->FldCaption());
		}
		if (!$this->ic_situacao->FldIsDetailKey && !is_null($this->ic_situacao->FormValue) && $this->ic_situacao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_situacao->FldCaption());
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

			// nu_anoInicio
			$this->nu_anoInicio->SetDbValueDef($rsnew, $this->nu_anoInicio->CurrentValue, NULL, $this->nu_anoInicio->ReadOnly);

			// nu_anoFim
			$this->nu_anoFim->SetDbValueDef($rsnew, $this->nu_anoFim->CurrentValue, NULL, $this->nu_anoFim->ReadOnly);

			// no_periodo
			$this->no_periodo->SetDbValueDef($rsnew, $this->no_periodo->CurrentValue, NULL, $this->no_periodo->ReadOnly);

			// ic_situacao
			$this->ic_situacao->SetDbValueDef($rsnew, $this->ic_situacao->CurrentValue, NULL, $this->ic_situacao->ReadOnly);

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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "periodopeilist.php", $this->TableVar);
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
if (!isset($periodopei_edit)) $periodopei_edit = new cperiodopei_edit();

// Page init
$periodopei_edit->Page_Init();

// Page main
$periodopei_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$periodopei_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var periodopei_edit = new ew_Page("periodopei_edit");
periodopei_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = periodopei_edit.PageID; // For backward compatibility

// Form object
var fperiodopeiedit = new ew_Form("fperiodopeiedit");

// Validate form
fperiodopeiedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_nu_anoInicio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($periodopei->nu_anoInicio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_anoFim");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($periodopei->nu_anoFim->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_situacao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($periodopei->ic_situacao->FldCaption()) ?>");

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
fperiodopeiedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fperiodopeiedit.ValidateRequired = true;
<?php } else { ?>
fperiodopeiedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $periodopei_edit->ShowPageHeader(); ?>
<?php
$periodopei_edit->ShowMessage();
?>
<form name="fperiodopeiedit" id="fperiodopeiedit" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="periodopei">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_periodopeiedit" class="table table-bordered table-striped">
<?php if ($periodopei->nu_periodoPei->Visible) { // nu_periodoPei ?>
	<tr id="r_nu_periodoPei">
		<td><span id="elh_periodopei_nu_periodoPei"><?php echo $periodopei->nu_periodoPei->FldCaption() ?></span></td>
		<td<?php echo $periodopei->nu_periodoPei->CellAttributes() ?>>
<span id="el_periodopei_nu_periodoPei" class="control-group">
<span<?php echo $periodopei->nu_periodoPei->ViewAttributes() ?>>
<?php echo $periodopei->nu_periodoPei->EditValue ?></span>
</span>
<input type="hidden" data-field="x_nu_periodoPei" name="x_nu_periodoPei" id="x_nu_periodoPei" value="<?php echo ew_HtmlEncode($periodopei->nu_periodoPei->CurrentValue) ?>">
<?php echo $periodopei->nu_periodoPei->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($periodopei->nu_anoInicio->Visible) { // nu_anoInicio ?>
	<tr id="r_nu_anoInicio">
		<td><span id="elh_periodopei_nu_anoInicio"><?php echo $periodopei->nu_anoInicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $periodopei->nu_anoInicio->CellAttributes() ?>>
<span id="el_periodopei_nu_anoInicio" class="control-group">
<select data-field="x_nu_anoInicio" id="x_nu_anoInicio" name="x_nu_anoInicio"<?php echo $periodopei->nu_anoInicio->EditAttributes() ?>>
<?php
if (is_array($periodopei->nu_anoInicio->EditValue)) {
	$arwrk = $periodopei->nu_anoInicio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($periodopei->nu_anoInicio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $periodopei->nu_anoInicio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($periodopei->nu_anoFim->Visible) { // nu_anoFim ?>
	<tr id="r_nu_anoFim">
		<td><span id="elh_periodopei_nu_anoFim"><?php echo $periodopei->nu_anoFim->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $periodopei->nu_anoFim->CellAttributes() ?>>
<span id="el_periodopei_nu_anoFim" class="control-group">
<select data-field="x_nu_anoFim" id="x_nu_anoFim" name="x_nu_anoFim"<?php echo $periodopei->nu_anoFim->EditAttributes() ?>>
<?php
if (is_array($periodopei->nu_anoFim->EditValue)) {
	$arwrk = $periodopei->nu_anoFim->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($periodopei->nu_anoFim->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $periodopei->nu_anoFim->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($periodopei->no_periodo->Visible) { // no_periodo ?>
	<tr id="r_no_periodo">
		<td><span id="elh_periodopei_no_periodo"><?php echo $periodopei->no_periodo->FldCaption() ?></span></td>
		<td<?php echo $periodopei->no_periodo->CellAttributes() ?>>
<span id="el_periodopei_no_periodo" class="control-group">
<input type="text" data-field="x_no_periodo" name="x_no_periodo" id="x_no_periodo" size="30" maxlength="15" placeholder="<?php echo $periodopei->no_periodo->PlaceHolder ?>" value="<?php echo $periodopei->no_periodo->EditValue ?>"<?php echo $periodopei->no_periodo->EditAttributes() ?>>
</span>
<?php echo $periodopei->no_periodo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($periodopei->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_periodopei_ic_situacao"><?php echo $periodopei->ic_situacao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $periodopei->ic_situacao->CellAttributes() ?>>
<span id="el_periodopei_ic_situacao" class="control-group">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $periodopei->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($periodopei->ic_situacao->EditValue)) {
	$arwrk = $periodopei->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($periodopei->ic_situacao->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php echo $periodopei->ic_situacao->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("EditBtn") ?></button>
</form>
<script type="text/javascript">
fperiodopeiedit.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$periodopei_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$periodopei_edit->Page_Terminate();
?>
