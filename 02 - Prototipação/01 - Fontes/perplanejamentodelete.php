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

$perplanejamento_delete = NULL; // Initialize page object first

class cperplanejamento_delete extends cperplanejamento {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'perplanejamento';

	// Page object name
	var $PageObjName = 'perplanejamento_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("perplanejamentolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("perplanejamentolist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in perplanejamento class, perplanejamentoinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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

		$this->nu_periodo->CellCssStyle = "white-space: nowrap;";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['nu_periodo'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "perplanejamentolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("delete");
		$Breadcrumb->Add("delete", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($perplanejamento_delete)) $perplanejamento_delete = new cperplanejamento_delete();

// Page init
$perplanejamento_delete->Page_Init();

// Page main
$perplanejamento_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$perplanejamento_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var perplanejamento_delete = new ew_Page("perplanejamento_delete");
perplanejamento_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = perplanejamento_delete.PageID; // For backward compatibility

// Form object
var fperplanejamentodelete = new ew_Form("fperplanejamentodelete");

// Form_CustomValidate event
fperplanejamentodelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fperplanejamentodelete.ValidateRequired = true;
<?php } else { ?>
fperplanejamentodelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fperplanejamentodelete.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_periodo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($perplanejamento_delete->Recordset = $perplanejamento_delete->LoadRecordset())
	$perplanejamento_deleteTotalRecs = $perplanejamento_delete->Recordset->RecordCount(); // Get record count
if ($perplanejamento_deleteTotalRecs <= 0) { // No record found, exit
	if ($perplanejamento_delete->Recordset)
		$perplanejamento_delete->Recordset->Close();
	$perplanejamento_delete->Page_Terminate("perplanejamentolist.php"); // Return to list
}
?>
<?php $Breadcrumb->Render(); ?>
<?php $perplanejamento_delete->ShowPageHeader(); ?>
<?php
$perplanejamento_delete->ShowMessage();
?>
<form name="fperplanejamentodelete" id="fperplanejamentodelete" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="perplanejamento">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($perplanejamento_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_perplanejamentodelete" class="ewTable ewTableSeparate">
<?php echo $perplanejamento->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_perplanejamento_nu_anoInicio" class="perplanejamento_nu_anoInicio"><?php echo $perplanejamento->nu_anoInicio->FldCaption() ?></span></td>
		<td><span id="elh_perplanejamento_nu_anoFim" class="perplanejamento_nu_anoFim"><?php echo $perplanejamento->nu_anoFim->FldCaption() ?></span></td>
		<td><span id="elh_perplanejamento_no_periodo" class="perplanejamento_no_periodo"><?php echo $perplanejamento->no_periodo->FldCaption() ?></span></td>
		<td><span id="elh_perplanejamento_nu_periodoPei" class="perplanejamento_nu_periodoPei"><?php echo $perplanejamento->nu_periodoPei->FldCaption() ?></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$perplanejamento_delete->RecCnt = 0;
$i = 0;
while (!$perplanejamento_delete->Recordset->EOF) {
	$perplanejamento_delete->RecCnt++;
	$perplanejamento_delete->RowCnt++;

	// Set row properties
	$perplanejamento->ResetAttrs();
	$perplanejamento->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$perplanejamento_delete->LoadRowValues($perplanejamento_delete->Recordset);

	// Render row
	$perplanejamento_delete->RenderRow();
?>
	<tr<?php echo $perplanejamento->RowAttributes() ?>>
		<td<?php echo $perplanejamento->nu_anoInicio->CellAttributes() ?>>
<span id="el<?php echo $perplanejamento_delete->RowCnt ?>_perplanejamento_nu_anoInicio" class="control-group perplanejamento_nu_anoInicio">
<span<?php echo $perplanejamento->nu_anoInicio->ViewAttributes() ?>>
<?php echo $perplanejamento->nu_anoInicio->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $perplanejamento->nu_anoFim->CellAttributes() ?>>
<span id="el<?php echo $perplanejamento_delete->RowCnt ?>_perplanejamento_nu_anoFim" class="control-group perplanejamento_nu_anoFim">
<span<?php echo $perplanejamento->nu_anoFim->ViewAttributes() ?>>
<?php echo $perplanejamento->nu_anoFim->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $perplanejamento->no_periodo->CellAttributes() ?>>
<span id="el<?php echo $perplanejamento_delete->RowCnt ?>_perplanejamento_no_periodo" class="control-group perplanejamento_no_periodo">
<span<?php echo $perplanejamento->no_periodo->ViewAttributes() ?>>
<?php echo $perplanejamento->no_periodo->ListViewValue() ?></span>
</span>
</td>
		<td<?php echo $perplanejamento->nu_periodoPei->CellAttributes() ?>>
<span id="el<?php echo $perplanejamento_delete->RowCnt ?>_perplanejamento_nu_periodoPei" class="control-group perplanejamento_nu_periodoPei">
<span<?php echo $perplanejamento->nu_periodoPei->ViewAttributes() ?>>
<?php echo $perplanejamento->nu_periodoPei->ListViewValue() ?></span>
</span>
</td>
	</tr>
<?php
	$perplanejamento_delete->Recordset->MoveNext();
}
$perplanejamento_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fperplanejamentodelete.Init();
</script>
<?php
$perplanejamento_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$perplanejamento_delete->Page_Terminate();
?>
