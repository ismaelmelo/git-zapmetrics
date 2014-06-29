<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "perplanejamentoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$perplanejamento_addopt = NULL; // Initialize page object first

class cperplanejamento_addopt extends cperplanejamento {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'perplanejamento';

	// Page object name
	var $PageObjName = 'perplanejamento_addopt';

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

		// Table object (perplanejamento)
		if (!isset($GLOBALS["perplanejamento"])) {
			$GLOBALS["perplanejamento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["perplanejamento"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'perplanejamento', TRUE);

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
			$this->Page_Terminate("perplanejamentolist.php");
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

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_nu_periodo"] = $this->nu_periodo->DbValue;
					$row["x_nu_anoInicio"] = $this->nu_anoInicio->DbValue;
					$row["x_nu_anoFim"] = $this->nu_anoFim->DbValue;
					$row["x_no_periodo"] = $this->no_periodo->DbValue;
					$row["x_nu_periodoPei"] = $this->nu_periodoPei->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
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
		$this->nu_anoInicio->CurrentValue = NULL;
		$this->nu_anoInicio->OldValue = $this->nu_anoInicio->CurrentValue;
		$this->nu_anoFim->CurrentValue = NULL;
		$this->nu_anoFim->OldValue = $this->nu_anoFim->CurrentValue;
		$this->no_periodo->CurrentValue = NULL;
		$this->no_periodo->OldValue = $this->no_periodo->CurrentValue;
		$this->nu_periodoPei->CurrentValue = NULL;
		$this->nu_periodoPei->OldValue = $this->nu_periodoPei->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_anoInicio->FldIsDetailKey) {
			$this->nu_anoInicio->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_anoInicio")));
		}
		if (!$this->nu_anoFim->FldIsDetailKey) {
			$this->nu_anoFim->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_anoFim")));
		}
		if (!$this->no_periodo->FldIsDetailKey) {
			$this->no_periodo->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_no_periodo")));
		}
		if (!$this->nu_periodoPei->FldIsDetailKey) {
			$this->nu_periodoPei->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_nu_periodoPei")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->nu_anoInicio->CurrentValue = ew_ConvertToUtf8($this->nu_anoInicio->FormValue);
		$this->nu_anoFim->CurrentValue = ew_ConvertToUtf8($this->nu_anoFim->FormValue);
		$this->no_periodo->CurrentValue = ew_ConvertToUtf8($this->no_periodo->FormValue);
		$this->nu_periodoPei->CurrentValue = ew_ConvertToUtf8($this->nu_periodoPei->FormValue);
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
		$this->nu_periodo->setDbValue($rs->fields('nu_periodo'));
		$this->nu_anoInicio->setDbValue($rs->fields('nu_anoInicio'));
		$this->nu_anoFim->setDbValue($rs->fields('nu_anoFim'));
		$this->no_periodo->setDbValue($rs->fields('no_periodo'));
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		if (array_key_exists('EV__nu_periodoPei', $rs->fields)) {
			$this->nu_periodoPei->VirtualValue = $rs->fields('EV__nu_periodoPei'); // Set up virtual field value
		} else {
			$this->nu_periodoPei->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_periodo->DbValue = $row['nu_periodo'];
		$this->nu_anoInicio->DbValue = $row['nu_anoInicio'];
		$this->nu_anoFim->DbValue = $row['nu_anoFim'];
		$this->no_periodo->DbValue = $row['no_periodo'];
		$this->nu_periodoPei->DbValue = $row['nu_periodoPei'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_periodo
		// nu_anoInicio
		// nu_anoFim
		// no_periodo
		// nu_periodoPei

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_periodo
			$this->nu_periodo->ViewValue = $this->nu_periodo->CurrentValue;
			$this->nu_periodo->ViewCustomAttributes = "";

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

			// nu_periodoPei
			if ($this->nu_periodoPei->VirtualValue <> "") {
				$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->VirtualValue;
			} else {
			if (strval($this->nu_periodoPei->CurrentValue) <> "") {
				$sFilterWrk = "[nu_periodoPei]" . ew_SearchString("=", $this->nu_periodoPei->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_periodoPei], [no_periodo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[periodopei]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPei, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_periodoPei->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->CurrentValue;
				}
			} else {
				$this->nu_periodoPei->ViewValue = NULL;
			}
			}
			$this->nu_periodoPei->ViewCustomAttributes = "";

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

			// nu_periodoPei
			$this->nu_periodoPei->LinkCustomAttributes = "";
			$this->nu_periodoPei->HrefValue = "";
			$this->nu_periodoPei->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// nu_periodoPei
			$this->nu_periodoPei->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_periodoPei], [no_periodo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[periodopei]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPei, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_periodoPei->EditValue = $arwrk;

			// Edit refer script
			// nu_anoInicio

			$this->nu_anoInicio->HrefValue = "";

			// nu_anoFim
			$this->nu_anoFim->HrefValue = "";

			// no_periodo
			$this->no_periodo->HrefValue = "";

			// nu_periodoPei
			$this->nu_periodoPei->HrefValue = "";
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
		if (!$this->no_periodo->FldIsDetailKey && !is_null($this->no_periodo->FormValue) && $this->no_periodo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_periodo->FldCaption());
		}
		if (!$this->nu_periodoPei->FldIsDetailKey && !is_null($this->nu_periodoPei->FormValue) && $this->nu_periodoPei->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_periodoPei->FldCaption());
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

		// nu_anoInicio
		$this->nu_anoInicio->SetDbValueDef($rsnew, $this->nu_anoInicio->CurrentValue, NULL, FALSE);

		// nu_anoFim
		$this->nu_anoFim->SetDbValueDef($rsnew, $this->nu_anoFim->CurrentValue, NULL, FALSE);

		// no_periodo
		$this->no_periodo->SetDbValueDef($rsnew, $this->no_periodo->CurrentValue, NULL, FALSE);

		// nu_periodoPei
		$this->nu_periodoPei->SetDbValueDef($rsnew, $this->nu_periodoPei->CurrentValue, NULL, FALSE);

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
			$this->nu_periodo->setDbValue($conn->Insert_ID());
			$rsnew['nu_periodo'] = $this->nu_periodo->DbValue;
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "perplanejamentolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("addopt");
		$Breadcrumb->Add("addopt", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Custom validate event
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
if (!isset($perplanejamento_addopt)) $perplanejamento_addopt = new cperplanejamento_addopt();

// Page init
$perplanejamento_addopt->Page_Init();

// Page main
$perplanejamento_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$perplanejamento_addopt->Page_Render();
?>
<script type="text/javascript">

// Page object
var perplanejamento_addopt = new ew_Page("perplanejamento_addopt");
perplanejamento_addopt.PageID = "addopt"; // Page ID
var EW_PAGE_ID = perplanejamento_addopt.PageID; // For backward compatibility

// Form object
var fperplanejamentoaddopt = new ew_Form("fperplanejamentoaddopt");

// Validate form
fperplanejamentoaddopt.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($perplanejamento->nu_anoInicio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_anoFim");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($perplanejamento->nu_anoFim->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_no_periodo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($perplanejamento->no_periodo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_periodoPei");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($perplanejamento->nu_periodoPei->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fperplanejamentoaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fperplanejamentoaddopt.ValidateRequired = true;
<?php } else { ?>
fperplanejamentoaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fperplanejamentoaddopt.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_periodo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$perplanejamento_addopt->ShowMessage();
?>
<form name="fperplanejamentoaddopt" id="fperplanejamentoaddopt" class="ewForm form-horizontal" action="perplanejamentoaddopt.php" method="post">
<input type="hidden" name="t" value="perplanejamento">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<div id="tbl_perplanejamentoaddopt">
	<div class="control-group">
		<label class="control-label" for="x_nu_anoInicio"><?php echo $perplanejamento->nu_anoInicio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_anoInicio" id="x_nu_anoInicio" name="x_nu_anoInicio"<?php echo $perplanejamento->nu_anoInicio->EditAttributes() ?>>
<?php
if (is_array($perplanejamento->nu_anoInicio->EditValue)) {
	$arwrk = $perplanejamento->nu_anoInicio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($perplanejamento->nu_anoInicio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_anoFim"><?php echo $perplanejamento->nu_anoFim->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_anoFim" id="x_nu_anoFim" name="x_nu_anoFim"<?php echo $perplanejamento->nu_anoFim->EditAttributes() ?>>
<?php
if (is_array($perplanejamento->nu_anoFim->EditValue)) {
	$arwrk = $perplanejamento->nu_anoFim->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($perplanejamento->nu_anoFim->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_no_periodo"><?php echo $perplanejamento->no_periodo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<input type="text" data-field="x_no_periodo" name="x_no_periodo" id="x_no_periodo" size="30" maxlength="15" placeholder="<?php echo $perplanejamento->no_periodo->PlaceHolder ?>" value="<?php echo $perplanejamento->no_periodo->EditValue ?>"<?php echo $perplanejamento->no_periodo->EditAttributes() ?>>
</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="x_nu_periodoPei"><?php echo $perplanejamento->nu_periodoPei->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="controls">
<select data-field="x_nu_periodoPei" id="x_nu_periodoPei" name="x_nu_periodoPei"<?php echo $perplanejamento->nu_periodoPei->EditAttributes() ?>>
<?php
if (is_array($perplanejamento->nu_periodoPei->EditValue)) {
	$arwrk = $perplanejamento->nu_periodoPei->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($perplanejamento->nu_periodoPei->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<script type="text/javascript">
fperplanejamentoaddopt.Lists["x_nu_periodoPei"].Options = <?php echo (is_array($perplanejamento->nu_periodoPei->EditValue)) ? ew_ArrayToJson($perplanejamento->nu_periodoPei->EditValue, 1) : "[]" ?>;
</script>
</div>
	</div>
</div>
</form>
<script type="text/javascript">
fperplanejamentoaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$perplanejamento_addopt->Page_Terminate();
?>
