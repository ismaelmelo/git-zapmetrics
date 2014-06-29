<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "relestativinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$relestativ_list = NULL; // Initialize page object first

class crelestativ_list extends crelestativ {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0602B820-DE72-4661-BB21-3716ACE9CB5F}";

	// Table name
	var $TableName = 'relestativ';

	// Page object name
	var $PageObjName = 'relestativ_list';

	// Grid form hidden field names
	var $FormName = 'frelestativlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (relestativ)
		if (!isset($GLOBALS["relestativ"])) {
			$GLOBALS["relestativ"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["relestativ"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "relestativadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "relestativdelete.php";
		$this->MultiUpdateUrl = "relestativupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'relestativ', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "span";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("login.php");
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
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->ddmmyyyy->Visible = !$this->IsAddOrEdit();
		$this->ddmm->Visible = !$this->IsAddOrEdit();
		$this->dia->Visible = !$this->IsAddOrEdit();
		$this->mes->Visible = !$this->IsAddOrEdit();
		$this->ano->Visible = !$this->IsAddOrEdit();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Setup other options
		$this->SetupOtherOptions();

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 100;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 100; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->ddmmyyyy, FALSE); // ddmmyyyy
		$this->BuildSearchSql($sWhere, $this->ddmm, FALSE); // ddmm
		$this->BuildSearchSql($sWhere, $this->dia, FALSE); // dia
		$this->BuildSearchSql($sWhere, $this->mes, FALSE); // mes
		$this->BuildSearchSql($sWhere, $this->ano, FALSE); // ano
		$this->BuildSearchSql($sWhere, $this->issue_id, FALSE); // issue_id
		$this->BuildSearchSql($sWhere, $this->tracker_id, FALSE); // tracker_id
		$this->BuildSearchSql($sWhere, $this->status_id, FALSE); // status_id
		$this->BuildSearchSql($sWhere, $this->project_id, FALSE); // project_id
		$this->BuildSearchSql($sWhere, $this->is_default, FALSE); // is_default
		$this->BuildSearchSql($sWhere, $this->is_closed, FALSE); // is_closed

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->ddmmyyyy->AdvancedSearch->Save(); // ddmmyyyy
			$this->ddmm->AdvancedSearch->Save(); // ddmm
			$this->dia->AdvancedSearch->Save(); // dia
			$this->mes->AdvancedSearch->Save(); // mes
			$this->ano->AdvancedSearch->Save(); // ano
			$this->issue_id->AdvancedSearch->Save(); // issue_id
			$this->tracker_id->AdvancedSearch->Save(); // tracker_id
			$this->status_id->AdvancedSearch->Save(); // status_id
			$this->project_id->AdvancedSearch->Save(); // project_id
			$this->is_default->AdvancedSearch->Save(); // is_default
			$this->is_closed->AdvancedSearch->Save(); // is_closed
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Check if search parm exists
	function CheckSearchParms() {
		if ($this->ddmmyyyy->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ddmm->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ano->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->issue_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tracker_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->project_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->is_default->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->is_closed->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->ddmmyyyy->AdvancedSearch->UnsetSession();
		$this->ddmm->AdvancedSearch->UnsetSession();
		$this->dia->AdvancedSearch->UnsetSession();
		$this->mes->AdvancedSearch->UnsetSession();
		$this->ano->AdvancedSearch->UnsetSession();
		$this->issue_id->AdvancedSearch->UnsetSession();
		$this->tracker_id->AdvancedSearch->UnsetSession();
		$this->status_id->AdvancedSearch->UnsetSession();
		$this->project_id->AdvancedSearch->UnsetSession();
		$this->is_default->AdvancedSearch->UnsetSession();
		$this->is_closed->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->ddmmyyyy->AdvancedSearch->Load();
		$this->ddmm->AdvancedSearch->Load();
		$this->dia->AdvancedSearch->Load();
		$this->mes->AdvancedSearch->Load();
		$this->ano->AdvancedSearch->Load();
		$this->issue_id->AdvancedSearch->Load();
		$this->tracker_id->AdvancedSearch->Load();
		$this->status_id->AdvancedSearch->Load();
		$this->project_id->AdvancedSearch->Load();
		$this->is_default->AdvancedSearch->Load();
		$this->is_closed->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->ddmmyyyy, $bCtrl); // ddmmyyyy
			$this->UpdateSort($this->ddmm, $bCtrl); // ddmm
			$this->UpdateSort($this->dia, $bCtrl); // dia
			$this->UpdateSort($this->mes, $bCtrl); // mes
			$this->UpdateSort($this->ano, $bCtrl); // ano
			$this->UpdateSort($this->issue_id, $bCtrl); // issue_id
			$this->UpdateSort($this->tracker_id, $bCtrl); // tracker_id
			$this->UpdateSort($this->status_id, $bCtrl); // status_id
			$this->UpdateSort($this->project_id, $bCtrl); // project_id
			$this->UpdateSort($this->is_default, $bCtrl); // is_default
			$this->UpdateSort($this->is_closed, $bCtrl); // is_closed
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->ddmmyyyy->setSort("");
				$this->ddmm->setSort("");
				$this->dia->setSort("");
				$this->mes->setSort("");
				$this->ano->setSort("");
				$this->issue_id->setSort("");
				$this->tracker_id->setSort("");
				$this->status_id->setSort("");
				$this->project_id->setSort("");
				$this->is_default->setSort("");
				$this->is_closed->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<label class=\"checkbox\"><input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\"></label>";
		if (count($this->CustomActions) > 0) $item->Visible = TRUE;
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		$this->ListOptions->ButtonClass = "btn-small"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-small"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.frelestativlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// ddmmyyyy

		$this->ddmmyyyy->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ddmmyyyy"]);
		if ($this->ddmmyyyy->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ddmmyyyy->AdvancedSearch->SearchOperator = @$_GET["z_ddmmyyyy"];

		// ddmm
		$this->ddmm->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ddmm"]);
		if ($this->ddmm->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ddmm->AdvancedSearch->SearchOperator = @$_GET["z_ddmm"];

		// dia
		$this->dia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dia"]);
		if ($this->dia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dia->AdvancedSearch->SearchOperator = @$_GET["z_dia"];
		$this->dia->AdvancedSearch->SearchCondition = @$_GET["v_dia"];
		$this->dia->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_dia"]);
		if ($this->dia->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->dia->AdvancedSearch->SearchOperator2 = @$_GET["w_dia"];

		// mes
		$this->mes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_mes"]);
		if ($this->mes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->mes->AdvancedSearch->SearchOperator = @$_GET["z_mes"];
		$this->mes->AdvancedSearch->SearchCondition = @$_GET["v_mes"];
		$this->mes->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_mes"]);
		if ($this->mes->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->mes->AdvancedSearch->SearchOperator2 = @$_GET["w_mes"];

		// ano
		$this->ano->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ano"]);
		if ($this->ano->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ano->AdvancedSearch->SearchOperator = @$_GET["z_ano"];
		$this->ano->AdvancedSearch->SearchCondition = @$_GET["v_ano"];
		$this->ano->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_ano"]);
		if ($this->ano->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->ano->AdvancedSearch->SearchOperator2 = @$_GET["w_ano"];

		// issue_id
		$this->issue_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_issue_id"]);
		if ($this->issue_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->issue_id->AdvancedSearch->SearchOperator = @$_GET["z_issue_id"];

		// tracker_id
		$this->tracker_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tracker_id"]);
		if ($this->tracker_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tracker_id->AdvancedSearch->SearchOperator = @$_GET["z_tracker_id"];

		// status_id
		$this->status_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status_id"]);
		if ($this->status_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status_id->AdvancedSearch->SearchOperator = @$_GET["z_status_id"];

		// project_id
		$this->project_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_project_id"]);
		if ($this->project_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->project_id->AdvancedSearch->SearchOperator = @$_GET["z_project_id"];

		// is_default
		$this->is_default->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_is_default"]);
		if ($this->is_default->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->is_default->AdvancedSearch->SearchOperator = @$_GET["z_is_default"];

		// is_closed
		$this->is_closed->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_is_closed"]);
		if ($this->is_closed->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->is_closed->AdvancedSearch->SearchOperator = @$_GET["z_is_closed"];
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
		$this->ddmmyyyy->setDbValue($rs->fields('ddmmyyyy'));
		$this->ddmm->setDbValue($rs->fields('ddmm'));
		$this->dia->setDbValue($rs->fields('dia'));
		$this->mes->setDbValue($rs->fields('mes'));
		$this->ano->setDbValue($rs->fields('ano'));
		$this->issue_id->setDbValue($rs->fields('issue_id'));
		$this->tracker_id->setDbValue($rs->fields('tracker_id'));
		$this->status_id->setDbValue($rs->fields('status_id'));
		$this->project_id->setDbValue($rs->fields('project_id'));
		$this->is_default->setDbValue($rs->fields('is_default'));
		$this->is_closed->setDbValue($rs->fields('is_closed'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ddmmyyyy->DbValue = $row['ddmmyyyy'];
		$this->ddmm->DbValue = $row['ddmm'];
		$this->dia->DbValue = $row['dia'];
		$this->mes->DbValue = $row['mes'];
		$this->ano->DbValue = $row['ano'];
		$this->issue_id->DbValue = $row['issue_id'];
		$this->tracker_id->DbValue = $row['tracker_id'];
		$this->status_id->DbValue = $row['status_id'];
		$this->project_id->DbValue = $row['project_id'];
		$this->is_default->DbValue = $row['is_default'];
		$this->is_closed->DbValue = $row['is_closed'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// ddmmyyyy
		// ddmm
		// dia
		// mes
		// ano
		// issue_id
		// tracker_id
		// status_id
		// project_id
		// is_default
		// is_closed

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// ddmmyyyy
			$this->ddmmyyyy->ViewValue = $this->ddmmyyyy->CurrentValue;
			$this->ddmmyyyy->ViewValue = ew_FormatDateTime($this->ddmmyyyy->ViewValue, 7);
			$this->ddmmyyyy->ViewCustomAttributes = "";

			// ddmm
			$this->ddmm->ViewValue = $this->ddmm->CurrentValue;
			$this->ddmm->ViewCustomAttributes = "";

			// dia
			$this->dia->ViewValue = $this->dia->CurrentValue;
			$this->dia->ViewCustomAttributes = "";

			// mes
			$this->mes->ViewValue = $this->mes->CurrentValue;
			$this->mes->ViewCustomAttributes = "";

			// ano
			$this->ano->ViewValue = $this->ano->CurrentValue;
			$this->ano->ViewCustomAttributes = "";

			// issue_id
			$this->issue_id->ViewValue = $this->issue_id->CurrentValue;
			$this->issue_id->ViewCustomAttributes = "";

			// tracker_id
			$this->tracker_id->ViewValue = $this->tracker_id->CurrentValue;
			$this->tracker_id->ViewCustomAttributes = "";

			// status_id
			$this->status_id->ViewValue = $this->status_id->CurrentValue;
			$this->status_id->ViewCustomAttributes = "";

			// project_id
			$this->project_id->ViewValue = $this->project_id->CurrentValue;
			$this->project_id->ViewCustomAttributes = "";

			// is_default
			$this->is_default->ViewValue = $this->is_default->CurrentValue;
			$this->is_default->ViewCustomAttributes = "";

			// is_closed
			$this->is_closed->ViewValue = $this->is_closed->CurrentValue;
			$this->is_closed->ViewCustomAttributes = "";

			// ddmmyyyy
			$this->ddmmyyyy->LinkCustomAttributes = "";
			$this->ddmmyyyy->HrefValue = "";
			$this->ddmmyyyy->TooltipValue = "";

			// ddmm
			$this->ddmm->LinkCustomAttributes = "";
			$this->ddmm->HrefValue = "";
			$this->ddmm->TooltipValue = "";

			// dia
			$this->dia->LinkCustomAttributes = "";
			$this->dia->HrefValue = "";
			$this->dia->TooltipValue = "";

			// mes
			$this->mes->LinkCustomAttributes = "";
			$this->mes->HrefValue = "";
			$this->mes->TooltipValue = "";

			// ano
			$this->ano->LinkCustomAttributes = "";
			$this->ano->HrefValue = "";
			$this->ano->TooltipValue = "";

			// issue_id
			$this->issue_id->LinkCustomAttributes = "";
			$this->issue_id->HrefValue = "";
			$this->issue_id->TooltipValue = "";

			// tracker_id
			$this->tracker_id->LinkCustomAttributes = "";
			$this->tracker_id->HrefValue = "";
			$this->tracker_id->TooltipValue = "";

			// status_id
			$this->status_id->LinkCustomAttributes = "";
			$this->status_id->HrefValue = "";
			$this->status_id->TooltipValue = "";

			// project_id
			$this->project_id->LinkCustomAttributes = "";
			$this->project_id->HrefValue = "";
			$this->project_id->TooltipValue = "";

			// is_default
			$this->is_default->LinkCustomAttributes = "";
			$this->is_default->HrefValue = "";
			$this->is_default->TooltipValue = "";

			// is_closed
			$this->is_closed->LinkCustomAttributes = "";
			$this->is_closed->HrefValue = "";
			$this->is_closed->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// ddmmyyyy
			$this->ddmmyyyy->EditCustomAttributes = "";
			$this->ddmmyyyy->EditValue = ew_HtmlEncode($this->ddmmyyyy->AdvancedSearch->SearchValue);
			$this->ddmmyyyy->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ddmmyyyy->FldCaption()));

			// ddmm
			$this->ddmm->EditCustomAttributes = "";
			$this->ddmm->EditValue = ew_HtmlEncode($this->ddmm->AdvancedSearch->SearchValue);
			$this->ddmm->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ddmm->FldCaption()));

			// dia
			$this->dia->EditCustomAttributes = "";
			$this->dia->EditValue = ew_HtmlEncode($this->dia->AdvancedSearch->SearchValue);
			$this->dia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dia->FldCaption()));
			$this->dia->EditCustomAttributes = "";
			$this->dia->EditValue2 = ew_HtmlEncode($this->dia->AdvancedSearch->SearchValue2);
			$this->dia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dia->FldCaption()));

			// mes
			$this->mes->EditCustomAttributes = "";
			$this->mes->EditValue = ew_HtmlEncode($this->mes->AdvancedSearch->SearchValue);
			$this->mes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->mes->FldCaption()));
			$this->mes->EditCustomAttributes = "";
			$this->mes->EditValue2 = ew_HtmlEncode($this->mes->AdvancedSearch->SearchValue2);
			$this->mes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->mes->FldCaption()));

			// ano
			$this->ano->EditCustomAttributes = "";
			$this->ano->EditValue = ew_HtmlEncode($this->ano->AdvancedSearch->SearchValue);
			$this->ano->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ano->FldCaption()));
			$this->ano->EditCustomAttributes = "";
			$this->ano->EditValue2 = ew_HtmlEncode($this->ano->AdvancedSearch->SearchValue2);
			$this->ano->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ano->FldCaption()));

			// issue_id
			$this->issue_id->EditCustomAttributes = "";
			$this->issue_id->EditValue = ew_HtmlEncode($this->issue_id->AdvancedSearch->SearchValue);
			$this->issue_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->issue_id->FldCaption()));

			// tracker_id
			$this->tracker_id->EditCustomAttributes = "";
			$this->tracker_id->EditValue = ew_HtmlEncode($this->tracker_id->AdvancedSearch->SearchValue);
			$this->tracker_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->tracker_id->FldCaption()));

			// status_id
			$this->status_id->EditCustomAttributes = "";
			$this->status_id->EditValue = ew_HtmlEncode($this->status_id->AdvancedSearch->SearchValue);
			$this->status_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->status_id->FldCaption()));

			// project_id
			$this->project_id->EditCustomAttributes = "";
			$this->project_id->EditValue = ew_HtmlEncode($this->project_id->AdvancedSearch->SearchValue);
			$this->project_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->project_id->FldCaption()));

			// is_default
			$this->is_default->EditCustomAttributes = "";
			$this->is_default->EditValue = ew_HtmlEncode($this->is_default->AdvancedSearch->SearchValue);
			$this->is_default->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->is_default->FldCaption()));

			// is_closed
			$this->is_closed->EditCustomAttributes = "";
			$this->is_closed->EditValue = ew_HtmlEncode($this->is_closed->AdvancedSearch->SearchValue);
			$this->is_closed->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->is_closed->FldCaption()));
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->ddmmyyyy->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->ddmmyyyy->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ddmm->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->ddmm->FldErrMsg());
		}
		if (!ew_CheckInteger($this->dia->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->dia->FldErrMsg());
		}
		if (!ew_CheckInteger($this->dia->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->dia->FldErrMsg());
		}
		if (!ew_CheckInteger($this->mes->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->mes->FldErrMsg());
		}
		if (!ew_CheckInteger($this->mes->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->mes->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ano->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->ano->FldErrMsg());
		}
		if (!ew_CheckInteger($this->ano->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->ano->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->ddmmyyyy->AdvancedSearch->Load();
		$this->ddmm->AdvancedSearch->Load();
		$this->dia->AdvancedSearch->Load();
		$this->mes->AdvancedSearch->Load();
		$this->ano->AdvancedSearch->Load();
		$this->issue_id->AdvancedSearch->Load();
		$this->tracker_id->AdvancedSearch->Load();
		$this->status_id->AdvancedSearch->Load();
		$this->project_id->AdvancedSearch->Load();
		$this->is_default->AdvancedSearch->Load();
		$this->is_closed->AdvancedSearch->Load();
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
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_relestativ\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_relestativ',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.frelestativlist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "");
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

		// Build QueryString for search
		$this->AddSearchQueryString($sQry, $this->ddmmyyyy); // ddmmyyyy
		$this->AddSearchQueryString($sQry, $this->ddmm); // ddmm
		$this->AddSearchQueryString($sQry, $this->dia); // dia
		$this->AddSearchQueryString($sQry, $this->mes); // mes
		$this->AddSearchQueryString($sQry, $this->ano); // ano
		$this->AddSearchQueryString($sQry, $this->issue_id); // issue_id
		$this->AddSearchQueryString($sQry, $this->tracker_id); // tracker_id
		$this->AddSearchQueryString($sQry, $this->status_id); // status_id
		$this->AddSearchQueryString($sQry, $this->project_id); // project_id
		$this->AddSearchQueryString($sQry, $this->is_default); // is_default
		$this->AddSearchQueryString($sQry, $this->is_closed); // is_closed

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", $url, $this->TableVar);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($relestativ_list)) $relestativ_list = new crelestativ_list();

// Page init
$relestativ_list->Page_Init();

// Page main
$relestativ_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$relestativ_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($relestativ->Export == "") { ?>
<script type="text/javascript">

// Page object
var relestativ_list = new ew_Page("relestativ_list");
relestativ_list.PageID = "list"; // Page ID
var EW_PAGE_ID = relestativ_list.PageID; // For backward compatibility

// Form object
var frelestativlist = new ew_Form("frelestativlist");
frelestativlist.FormKeyCountName = '<?php echo $relestativ_list->FormKeyCountName ?>';

// Form_CustomValidate event
frelestativlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frelestativlist.ValidateRequired = true;
<?php } else { ?>
frelestativlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var frelestativlistsrch = new ew_Form("frelestativlistsrch");

// Validate function for search
frelestativlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_ddmmyyyy");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($relestativ->ddmmyyyy->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_ddmm");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($relestativ->ddmm->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_dia");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($relestativ->dia->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_mes");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($relestativ->mes->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_ano");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($relestativ->ano->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
frelestativlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frelestativlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
frelestativlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (frelestativlistsrch) frelestativlistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($relestativ->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($relestativ_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $relestativ_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$relestativ_list->TotalRecs = $relestativ->SelectRecordCount();
	} else {
		if ($relestativ_list->Recordset = $relestativ_list->LoadRecordset())
			$relestativ_list->TotalRecs = $relestativ_list->Recordset->RecordCount();
	}
	$relestativ_list->StartRec = 1;
	if ($relestativ_list->DisplayRecs <= 0 || ($relestativ->Export <> "" && $relestativ->ExportAll)) // Display all records
		$relestativ_list->DisplayRecs = $relestativ_list->TotalRecs;
	if (!($relestativ->Export <> "" && $relestativ->ExportAll))
		$relestativ_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$relestativ_list->Recordset = $relestativ_list->LoadRecordset($relestativ_list->StartRec-1, $relestativ_list->DisplayRecs);
$relestativ_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($relestativ->Export == "" && $relestativ->CurrentAction == "") { ?>
<form name="frelestativlistsrch" id="frelestativlistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="frelestativlistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#frelestativlistsrch_SearchGroup" href="#frelestativlistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="frelestativlistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="frelestativlistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="relestativ">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$relestativ_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$relestativ->RowType = EW_ROWTYPE_SEARCH;

// Render row
$relestativ->ResetAttrs();
$relestativ_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($relestativ->ddmmyyyy->Visible) { // ddmmyyyy ?>
	<span id="xsc_ddmmyyyy" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relestativ->ddmmyyyy->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ddmmyyyy" id="z_ddmmyyyy" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_ddmmyyyy" name="x_ddmmyyyy" id="x_ddmmyyyy" size="30" placeholder="<?php echo $relestativ->ddmmyyyy->PlaceHolder ?>" value="<?php echo $relestativ->ddmmyyyy->EditValue ?>"<?php echo $relestativ->ddmmyyyy->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($relestativ->ddmm->Visible) { // ddmm ?>
	<span id="xsc_ddmm" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relestativ->ddmm->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ddmm" id="z_ddmm" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_ddmm" name="x_ddmm" id="x_ddmm" size="30" placeholder="<?php echo $relestativ->ddmm->PlaceHolder ?>" value="<?php echo $relestativ->ddmm->EditValue ?>"<?php echo $relestativ->ddmm->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($relestativ->dia->Visible) { // dia ?>
	<span id="xsc_dia" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relestativ->dia->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_dia" id="z_dia" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dia" name="x_dia" id="x_dia" size="30" placeholder="<?php echo $relestativ->dia->PlaceHolder ?>" value="<?php echo $relestativ->dia->EditValue ?>"<?php echo $relestativ->dia->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_dia">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_dia">
<input type="text" data-field="x_dia" name="y_dia" id="y_dia" size="30" placeholder="<?php echo $relestativ->dia->PlaceHolder ?>" value="<?php echo $relestativ->dia->EditValue2 ?>"<?php echo $relestativ->dia->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($relestativ->mes->Visible) { // mes ?>
	<span id="xsc_mes" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relestativ->mes->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_mes" id="z_mes" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_mes" name="x_mes" id="x_mes" size="30" placeholder="<?php echo $relestativ->mes->PlaceHolder ?>" value="<?php echo $relestativ->mes->EditValue ?>"<?php echo $relestativ->mes->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_mes">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_mes">
<input type="text" data-field="x_mes" name="y_mes" id="y_mes" size="30" placeholder="<?php echo $relestativ->mes->PlaceHolder ?>" value="<?php echo $relestativ->mes->EditValue2 ?>"<?php echo $relestativ->mes->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($relestativ->ano->Visible) { // ano ?>
	<span id="xsc_ano" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relestativ->ano->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_ano" id="z_ano" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_ano" name="x_ano" id="x_ano" size="30" placeholder="<?php echo $relestativ->ano->PlaceHolder ?>" value="<?php echo $relestativ->ano->EditValue ?>"<?php echo $relestativ->ano->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_ano">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_ano">
<input type="text" data-field="x_ano" name="y_ano" id="y_ano" size="30" placeholder="<?php echo $relestativ->ano->PlaceHolder ?>" value="<?php echo $relestativ->ano->EditValue2 ?>"<?php echo $relestativ->ano->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $relestativ_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
</div>
</div>
			</div>
		</div>
	</div>
</div>
</td></tr></table>
</form>
<?php } ?>
<?php } ?>
<?php $relestativ_list->ShowPageHeader(); ?>
<?php
$relestativ_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="frelestativlist" id="frelestativlist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="relestativ">
<div id="gmp_relestativ" class="ewGridMiddlePanel">
<?php if ($relestativ_list->TotalRecs > 0) { ?>
<table id="tbl_relestativlist" class="ewTable ewTableSeparate">
<?php echo $relestativ->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$relestativ_list->RenderListOptions();

// Render list options (header, left)
$relestativ_list->ListOptions->Render("header", "left");
?>
<?php if ($relestativ->ddmmyyyy->Visible) { // ddmmyyyy ?>
	<?php if ($relestativ->SortUrl($relestativ->ddmmyyyy) == "") { ?>
		<td><div id="elh_relestativ_ddmmyyyy" class="relestativ_ddmmyyyy"><div class="ewTableHeaderCaption"><?php echo $relestativ->ddmmyyyy->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->ddmmyyyy) ?>',2);"><div id="elh_relestativ_ddmmyyyy" class="relestativ_ddmmyyyy">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->ddmmyyyy->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->ddmmyyyy->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->ddmmyyyy->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->ddmm->Visible) { // ddmm ?>
	<?php if ($relestativ->SortUrl($relestativ->ddmm) == "") { ?>
		<td><div id="elh_relestativ_ddmm" class="relestativ_ddmm"><div class="ewTableHeaderCaption"><?php echo $relestativ->ddmm->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->ddmm) ?>',2);"><div id="elh_relestativ_ddmm" class="relestativ_ddmm">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->ddmm->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->ddmm->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->ddmm->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->dia->Visible) { // dia ?>
	<?php if ($relestativ->SortUrl($relestativ->dia) == "") { ?>
		<td><div id="elh_relestativ_dia" class="relestativ_dia"><div class="ewTableHeaderCaption"><?php echo $relestativ->dia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->dia) ?>',2);"><div id="elh_relestativ_dia" class="relestativ_dia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->dia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->dia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->dia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->mes->Visible) { // mes ?>
	<?php if ($relestativ->SortUrl($relestativ->mes) == "") { ?>
		<td><div id="elh_relestativ_mes" class="relestativ_mes"><div class="ewTableHeaderCaption"><?php echo $relestativ->mes->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->mes) ?>',2);"><div id="elh_relestativ_mes" class="relestativ_mes">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->mes->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->mes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->mes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->ano->Visible) { // ano ?>
	<?php if ($relestativ->SortUrl($relestativ->ano) == "") { ?>
		<td><div id="elh_relestativ_ano" class="relestativ_ano"><div class="ewTableHeaderCaption"><?php echo $relestativ->ano->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->ano) ?>',2);"><div id="elh_relestativ_ano" class="relestativ_ano">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->ano->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->ano->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->ano->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->issue_id->Visible) { // issue_id ?>
	<?php if ($relestativ->SortUrl($relestativ->issue_id) == "") { ?>
		<td><div id="elh_relestativ_issue_id" class="relestativ_issue_id"><div class="ewTableHeaderCaption"><?php echo $relestativ->issue_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->issue_id) ?>',2);"><div id="elh_relestativ_issue_id" class="relestativ_issue_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->issue_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->issue_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->issue_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->tracker_id->Visible) { // tracker_id ?>
	<?php if ($relestativ->SortUrl($relestativ->tracker_id) == "") { ?>
		<td><div id="elh_relestativ_tracker_id" class="relestativ_tracker_id"><div class="ewTableHeaderCaption"><?php echo $relestativ->tracker_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->tracker_id) ?>',2);"><div id="elh_relestativ_tracker_id" class="relestativ_tracker_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->tracker_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->tracker_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->tracker_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->status_id->Visible) { // status_id ?>
	<?php if ($relestativ->SortUrl($relestativ->status_id) == "") { ?>
		<td><div id="elh_relestativ_status_id" class="relestativ_status_id"><div class="ewTableHeaderCaption"><?php echo $relestativ->status_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->status_id) ?>',2);"><div id="elh_relestativ_status_id" class="relestativ_status_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->status_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->status_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->status_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->project_id->Visible) { // project_id ?>
	<?php if ($relestativ->SortUrl($relestativ->project_id) == "") { ?>
		<td><div id="elh_relestativ_project_id" class="relestativ_project_id"><div class="ewTableHeaderCaption"><?php echo $relestativ->project_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->project_id) ?>',2);"><div id="elh_relestativ_project_id" class="relestativ_project_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->project_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->project_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->project_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->is_default->Visible) { // is_default ?>
	<?php if ($relestativ->SortUrl($relestativ->is_default) == "") { ?>
		<td><div id="elh_relestativ_is_default" class="relestativ_is_default"><div class="ewTableHeaderCaption"><?php echo $relestativ->is_default->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->is_default) ?>',2);"><div id="elh_relestativ_is_default" class="relestativ_is_default">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->is_default->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->is_default->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->is_default->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relestativ->is_closed->Visible) { // is_closed ?>
	<?php if ($relestativ->SortUrl($relestativ->is_closed) == "") { ?>
		<td><div id="elh_relestativ_is_closed" class="relestativ_is_closed"><div class="ewTableHeaderCaption"><?php echo $relestativ->is_closed->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relestativ->SortUrl($relestativ->is_closed) ?>',2);"><div id="elh_relestativ_is_closed" class="relestativ_is_closed">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relestativ->is_closed->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relestativ->is_closed->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relestativ->is_closed->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$relestativ_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($relestativ->ExportAll && $relestativ->Export <> "") {
	$relestativ_list->StopRec = $relestativ_list->TotalRecs;
} else {

	// Set the last record to display
	if ($relestativ_list->TotalRecs > $relestativ_list->StartRec + $relestativ_list->DisplayRecs - 1)
		$relestativ_list->StopRec = $relestativ_list->StartRec + $relestativ_list->DisplayRecs - 1;
	else
		$relestativ_list->StopRec = $relestativ_list->TotalRecs;
}
$relestativ_list->RecCnt = $relestativ_list->StartRec - 1;
if ($relestativ_list->Recordset && !$relestativ_list->Recordset->EOF) {
	$relestativ_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $relestativ_list->StartRec > 1)
		$relestativ_list->Recordset->Move($relestativ_list->StartRec - 1);
} elseif (!$relestativ->AllowAddDeleteRow && $relestativ_list->StopRec == 0) {
	$relestativ_list->StopRec = $relestativ->GridAddRowCount;
}

// Initialize aggregate
$relestativ->RowType = EW_ROWTYPE_AGGREGATEINIT;
$relestativ->ResetAttrs();
$relestativ_list->RenderRow();
while ($relestativ_list->RecCnt < $relestativ_list->StopRec) {
	$relestativ_list->RecCnt++;
	if (intval($relestativ_list->RecCnt) >= intval($relestativ_list->StartRec)) {
		$relestativ_list->RowCnt++;

		// Set up key count
		$relestativ_list->KeyCount = $relestativ_list->RowIndex;

		// Init row class and style
		$relestativ->ResetAttrs();
		$relestativ->CssClass = "";
		if ($relestativ->CurrentAction == "gridadd") {
		} else {
			$relestativ_list->LoadRowValues($relestativ_list->Recordset); // Load row values
		}
		$relestativ->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$relestativ->RowAttrs = array_merge($relestativ->RowAttrs, array('data-rowindex'=>$relestativ_list->RowCnt, 'id'=>'r' . $relestativ_list->RowCnt . '_relestativ', 'data-rowtype'=>$relestativ->RowType));

		// Render row
		$relestativ_list->RenderRow();

		// Render list options
		$relestativ_list->RenderListOptions();
?>
	<tr<?php echo $relestativ->RowAttributes() ?>>
<?php

// Render list options (body, left)
$relestativ_list->ListOptions->Render("body", "left", $relestativ_list->RowCnt);
?>
	<?php if ($relestativ->ddmmyyyy->Visible) { // ddmmyyyy ?>
		<td<?php echo $relestativ->ddmmyyyy->CellAttributes() ?>>
<span<?php echo $relestativ->ddmmyyyy->ViewAttributes() ?>>
<?php echo $relestativ->ddmmyyyy->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->ddmm->Visible) { // ddmm ?>
		<td<?php echo $relestativ->ddmm->CellAttributes() ?>>
<span<?php echo $relestativ->ddmm->ViewAttributes() ?>>
<?php echo $relestativ->ddmm->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->dia->Visible) { // dia ?>
		<td<?php echo $relestativ->dia->CellAttributes() ?>>
<span<?php echo $relestativ->dia->ViewAttributes() ?>>
<?php echo $relestativ->dia->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->mes->Visible) { // mes ?>
		<td<?php echo $relestativ->mes->CellAttributes() ?>>
<span<?php echo $relestativ->mes->ViewAttributes() ?>>
<?php echo $relestativ->mes->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->ano->Visible) { // ano ?>
		<td<?php echo $relestativ->ano->CellAttributes() ?>>
<span<?php echo $relestativ->ano->ViewAttributes() ?>>
<?php echo $relestativ->ano->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->issue_id->Visible) { // issue_id ?>
		<td<?php echo $relestativ->issue_id->CellAttributes() ?>>
<span<?php echo $relestativ->issue_id->ViewAttributes() ?>>
<?php echo $relestativ->issue_id->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->tracker_id->Visible) { // tracker_id ?>
		<td<?php echo $relestativ->tracker_id->CellAttributes() ?>>
<span<?php echo $relestativ->tracker_id->ViewAttributes() ?>>
<?php echo $relestativ->tracker_id->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->status_id->Visible) { // status_id ?>
		<td<?php echo $relestativ->status_id->CellAttributes() ?>>
<span<?php echo $relestativ->status_id->ViewAttributes() ?>>
<?php echo $relestativ->status_id->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->project_id->Visible) { // project_id ?>
		<td<?php echo $relestativ->project_id->CellAttributes() ?>>
<span<?php echo $relestativ->project_id->ViewAttributes() ?>>
<?php echo $relestativ->project_id->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->is_default->Visible) { // is_default ?>
		<td<?php echo $relestativ->is_default->CellAttributes() ?>>
<span<?php echo $relestativ->is_default->ViewAttributes() ?>>
<?php echo $relestativ->is_default->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relestativ->is_closed->Visible) { // is_closed ?>
		<td<?php echo $relestativ->is_closed->CellAttributes() ?>>
<span<?php echo $relestativ->is_closed->ViewAttributes() ?>>
<?php echo $relestativ->is_closed->ListViewValue() ?></span>
<a id="<?php echo $relestativ_list->PageObjName . "_row_" . $relestativ_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$relestativ_list->ListOptions->Render("body", "right", $relestativ_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($relestativ->CurrentAction <> "gridadd")
		$relestativ_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($relestativ->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($relestativ_list->Recordset)
	$relestativ_list->Recordset->Close();
?>
<?php if ($relestativ->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($relestativ->CurrentAction <> "gridadd" && $relestativ->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($relestativ_list->Pager)) $relestativ_list->Pager = new cNumericPager($relestativ_list->StartRec, $relestativ_list->DisplayRecs, $relestativ_list->TotalRecs, $relestativ_list->RecRange) ?>
<?php if ($relestativ_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($relestativ_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $relestativ_list->PageUrl() ?>start=<?php echo $relestativ_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($relestativ_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $relestativ_list->PageUrl() ?>start=<?php echo $relestativ_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($relestativ_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $relestativ_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($relestativ_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $relestativ_list->PageUrl() ?>start=<?php echo $relestativ_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($relestativ_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $relestativ_list->PageUrl() ?>start=<?php echo $relestativ_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($relestativ_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $relestativ_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $relestativ_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $relestativ_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($relestativ_list->SearchWhere == "0=101") { ?>
	<p><?php echo $Language->Phrase("EnterSearchCriteria") ?></p>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
	<?php } ?>
	<?php } else { ?>
	<p><?php echo $Language->Phrase("NoPermission") ?></p>
	<?php } ?>
<?php } ?>
</td>
</tr></table>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($relestativ_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($relestativ->Export == "") { ?>
<script type="text/javascript">
frelestativlistsrch.Init();
frelestativlist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$relestativ_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($relestativ->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$relestativ_list->Page_Terminate();
?>
