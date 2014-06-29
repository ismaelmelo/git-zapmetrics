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

$perplanejamento_view = NULL; // Initialize page object first

class cperplanejamento_view extends cperplanejamento {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'perplanejamento';

	// Page object name
	var $PageObjName = 'perplanejamento_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["nu_periodo"] <> "") {
			$this->RecKey["nu_periodo"] = $_GET["nu_periodo"];
			$KeyUrl .= "&nu_periodo=" . urlencode($this->RecKey["nu_periodo"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'perplanejamento', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("perplanejamentolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["nu_periodo"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_periodo"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Update url if printer friendly for Pdf
		if ($this->PrinterFriendlyForPdf)
			$this->ExportOptions->Items["pdf"]->Body = str_replace($this->ExportPdfUrl, $this->ExportPrintUrl . "&pdf=1", $this->ExportOptions->Items["pdf"]->Body);
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();
		if ($this->Export == "print" && @$_GET["pdf"] == "1") { // Printer friendly version and with pdf=1 in URL parameters
			$pdf = new cExportPdf($GLOBALS["Table"]);
			$pdf->Text = ob_get_contents(); // Set the content as the HTML of current page (printer friendly version)
			ob_end_clean();
			$pdf->Export();
		}

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["nu_periodo"] <> "") {
				$this->nu_periodo->setQueryStringValue($_GET["nu_periodo"]);
				$this->RecKey["nu_periodo"] = $this->nu_periodo->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("perplanejamentolist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_periodo->CurrentValue) == strval($this->Recordset->fields('nu_periodo'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "perplanejamentolist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "perplanejamentolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_perplanejamento\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_perplanejamento',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fperplanejamentoview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "v");
		$ParentTable = "";
		if ($bSelectLimit) {
			$StartRec = 1;
			$StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {
			$StartRec = $this->StartRec;
			$StopRec = $this->StopRec;
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$ExportDoc->Text .= $sHeader;
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$ExportDoc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$ExportDoc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($ExportDoc->Text);
		} else {
			$ExportDoc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_GET["sender"];
		$sRecipient = @$_GET["recipient"];
		$sCc = @$_GET["cc"];
		$sBcc = @$_GET["bcc"];
		$sContentType = @$_GET["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_GET["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_GET["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-error\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-error\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EW_EMAIL_CHARSET;
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= $EmailContent; // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-error\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);
		return $sQry;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "perplanejamentolist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("view");
		$Breadcrumb->Add("view", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
if (!isset($perplanejamento_view)) $perplanejamento_view = new cperplanejamento_view();

// Page init
$perplanejamento_view->Page_Init();

// Page main
$perplanejamento_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$perplanejamento_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($perplanejamento->Export == "") { ?>
<script type="text/javascript">

// Page object
var perplanejamento_view = new ew_Page("perplanejamento_view");
perplanejamento_view.PageID = "view"; // Page ID
var EW_PAGE_ID = perplanejamento_view.PageID; // For backward compatibility

// Form object
var fperplanejamentoview = new ew_Form("fperplanejamentoview");

// Form_CustomValidate event
fperplanejamentoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fperplanejamentoview.ValidateRequired = true;
<?php } else { ?>
fperplanejamentoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fperplanejamentoview.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_periodo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($perplanejamento->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($perplanejamento->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $perplanejamento_view->ExportOptions->Render("body") ?>
<?php if (!$perplanejamento_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($perplanejamento_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $perplanejamento_view->ShowPageHeader(); ?>
<?php
$perplanejamento_view->ShowMessage();
?>
<form name="fperplanejamentoview" id="fperplanejamentoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="perplanejamento">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_perplanejamentoview" class="table table-bordered table-striped">
<?php if ($perplanejamento->nu_anoInicio->Visible) { // nu_anoInicio ?>
	<tr id="r_nu_anoInicio">
		<td><span id="elh_perplanejamento_nu_anoInicio"><?php echo $perplanejamento->nu_anoInicio->FldCaption() ?></span></td>
		<td<?php echo $perplanejamento->nu_anoInicio->CellAttributes() ?>>
<span id="el_perplanejamento_nu_anoInicio" class="control-group">
<span<?php echo $perplanejamento->nu_anoInicio->ViewAttributes() ?>>
<?php echo $perplanejamento->nu_anoInicio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($perplanejamento->nu_anoFim->Visible) { // nu_anoFim ?>
	<tr id="r_nu_anoFim">
		<td><span id="elh_perplanejamento_nu_anoFim"><?php echo $perplanejamento->nu_anoFim->FldCaption() ?></span></td>
		<td<?php echo $perplanejamento->nu_anoFim->CellAttributes() ?>>
<span id="el_perplanejamento_nu_anoFim" class="control-group">
<span<?php echo $perplanejamento->nu_anoFim->ViewAttributes() ?>>
<?php echo $perplanejamento->nu_anoFim->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($perplanejamento->no_periodo->Visible) { // no_periodo ?>
	<tr id="r_no_periodo">
		<td><span id="elh_perplanejamento_no_periodo"><?php echo $perplanejamento->no_periodo->FldCaption() ?></span></td>
		<td<?php echo $perplanejamento->no_periodo->CellAttributes() ?>>
<span id="el_perplanejamento_no_periodo" class="control-group">
<span<?php echo $perplanejamento->no_periodo->ViewAttributes() ?>>
<?php echo $perplanejamento->no_periodo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($perplanejamento->nu_periodoPei->Visible) { // nu_periodoPei ?>
	<tr id="r_nu_periodoPei">
		<td><span id="elh_perplanejamento_nu_periodoPei"><?php echo $perplanejamento->nu_periodoPei->FldCaption() ?></span></td>
		<td<?php echo $perplanejamento->nu_periodoPei->CellAttributes() ?>>
<span id="el_perplanejamento_nu_periodoPei" class="control-group">
<span<?php echo $perplanejamento->nu_periodoPei->ViewAttributes() ?>>
<?php echo $perplanejamento->nu_periodoPei->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($perplanejamento->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($perplanejamento_view->Pager)) $perplanejamento_view->Pager = new cNumericPager($perplanejamento_view->StartRec, $perplanejamento_view->DisplayRecs, $perplanejamento_view->TotalRecs, $perplanejamento_view->RecRange) ?>
<?php if ($perplanejamento_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($perplanejamento_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $perplanejamento_view->PageUrl() ?>start=<?php echo $perplanejamento_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($perplanejamento_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $perplanejamento_view->PageUrl() ?>start=<?php echo $perplanejamento_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($perplanejamento_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $perplanejamento_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($perplanejamento_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $perplanejamento_view->PageUrl() ?>start=<?php echo $perplanejamento_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($perplanejamento_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $perplanejamento_view->PageUrl() ?>start=<?php echo $perplanejamento_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
</tr></tbody></table>
<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
<?php } ?>
</td>
</tr></table>
<?php } ?>
</form>
<script type="text/javascript">
fperplanejamentoview.Init();
</script>
<?php
$perplanejamento_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($perplanejamento->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$perplanejamento_view->Page_Terminate();
?>
