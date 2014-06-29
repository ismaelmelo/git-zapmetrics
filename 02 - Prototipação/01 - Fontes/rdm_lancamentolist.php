<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "rdm_lancamentoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$rdm_lancamento_list = NULL; // Initialize page object first

class crdm_lancamento_list extends crdm_lancamento {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0602B820-DE72-4661-BB21-3716ACE9CB5F}";

	// Table name
	var $TableName = 'rdm_lancamento';

	// Page object name
	var $PageObjName = 'rdm_lancamento_list';

	// Grid form hidden field names
	var $FormName = 'frdm_lancamentolist';
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

		// Table object (rdm_lancamento)
		if (!isset($GLOBALS["rdm_lancamento"])) {
			$GLOBALS["rdm_lancamento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["rdm_lancamento"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "rdm_lancamentoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "rdm_lancamentodelete.php";
		$this->MultiUpdateUrl = "rdm_lancamentoupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rdm_lancamento', TRUE);

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
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->project_id, FALSE); // project_id
		$this->BuildSearchSql($sWhere, $this->issue_id, FALSE); // issue_id
		$this->BuildSearchSql($sWhere, $this->user_id, FALSE); // user_id
		$this->BuildSearchSql($sWhere, $this->activity_id, FALSE); // activity_id
		$this->BuildSearchSql($sWhere, $this->hours, FALSE); // hours
		$this->BuildSearchSql($sWhere, $this->comments, FALSE); // comments
		$this->BuildSearchSql($sWhere, $this->spent_on, FALSE); // spent_on
		$this->BuildSearchSql($sWhere, $this->created_on, FALSE); // created_on
		$this->BuildSearchSql($sWhere, $this->updated_on, FALSE); // updated_on

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->project_id->AdvancedSearch->Save(); // project_id
			$this->issue_id->AdvancedSearch->Save(); // issue_id
			$this->user_id->AdvancedSearch->Save(); // user_id
			$this->activity_id->AdvancedSearch->Save(); // activity_id
			$this->hours->AdvancedSearch->Save(); // hours
			$this->comments->AdvancedSearch->Save(); // comments
			$this->spent_on->AdvancedSearch->Save(); // spent_on
			$this->created_on->AdvancedSearch->Save(); // created_on
			$this->updated_on->AdvancedSearch->Save(); // updated_on
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
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->project_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->issue_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->user_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->hours->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->comments->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->spent_on->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->created_on->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->updated_on->AdvancedSearch->IssetSession())
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
		$this->id->AdvancedSearch->UnsetSession();
		$this->project_id->AdvancedSearch->UnsetSession();
		$this->issue_id->AdvancedSearch->UnsetSession();
		$this->user_id->AdvancedSearch->UnsetSession();
		$this->activity_id->AdvancedSearch->UnsetSession();
		$this->hours->AdvancedSearch->UnsetSession();
		$this->comments->AdvancedSearch->UnsetSession();
		$this->spent_on->AdvancedSearch->UnsetSession();
		$this->created_on->AdvancedSearch->UnsetSession();
		$this->updated_on->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->project_id->AdvancedSearch->Load();
		$this->issue_id->AdvancedSearch->Load();
		$this->user_id->AdvancedSearch->Load();
		$this->activity_id->AdvancedSearch->Load();
		$this->hours->AdvancedSearch->Load();
		$this->comments->AdvancedSearch->Load();
		$this->spent_on->AdvancedSearch->Load();
		$this->created_on->AdvancedSearch->Load();
		$this->updated_on->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id, $bCtrl); // id
			$this->UpdateSort($this->project_id, $bCtrl); // project_id
			$this->UpdateSort($this->issue_id, $bCtrl); // issue_id
			$this->UpdateSort($this->user_id, $bCtrl); // user_id
			$this->UpdateSort($this->activity_id, $bCtrl); // activity_id
			$this->UpdateSort($this->hours, $bCtrl); // hours
			$this->UpdateSort($this->spent_on, $bCtrl); // spent_on
			$this->UpdateSort($this->created_on, $bCtrl); // created_on
			$this->UpdateSort($this->updated_on, $bCtrl); // updated_on
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
				$this->id->setSort("");
				$this->project_id->setSort("");
				$this->issue_id->setSort("");
				$this->user_id->setSort("");
				$this->activity_id->setSort("");
				$this->hours->setSort("");
				$this->spent_on->setSort("");
				$this->created_on->setSort("");
				$this->updated_on->setSort("");
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

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

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

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.frdm_lancamentolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id"]);
		if ($this->id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// project_id
		$this->project_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_project_id"]);
		if ($this->project_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->project_id->AdvancedSearch->SearchOperator = @$_GET["z_project_id"];

		// issue_id
		$this->issue_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_issue_id"]);
		if ($this->issue_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->issue_id->AdvancedSearch->SearchOperator = @$_GET["z_issue_id"];

		// user_id
		$this->user_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_user_id"]);
		if ($this->user_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->user_id->AdvancedSearch->SearchOperator = @$_GET["z_user_id"];

		// activity_id
		$this->activity_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_activity_id"]);
		if ($this->activity_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->activity_id->AdvancedSearch->SearchOperator = @$_GET["z_activity_id"];

		// hours
		$this->hours->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_hours"]);
		if ($this->hours->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->hours->AdvancedSearch->SearchOperator = @$_GET["z_hours"];

		// comments
		$this->comments->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_comments"]);
		if ($this->comments->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->comments->AdvancedSearch->SearchOperator = @$_GET["z_comments"];

		// spent_on
		$this->spent_on->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_spent_on"]);
		if ($this->spent_on->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->spent_on->AdvancedSearch->SearchOperator = @$_GET["z_spent_on"];
		$this->spent_on->AdvancedSearch->SearchCondition = @$_GET["v_spent_on"];
		$this->spent_on->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_spent_on"]);
		if ($this->spent_on->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->spent_on->AdvancedSearch->SearchOperator2 = @$_GET["w_spent_on"];

		// created_on
		$this->created_on->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_created_on"]);
		if ($this->created_on->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->created_on->AdvancedSearch->SearchOperator = @$_GET["z_created_on"];
		$this->created_on->AdvancedSearch->SearchCondition = @$_GET["v_created_on"];
		$this->created_on->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_created_on"]);
		if ($this->created_on->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->created_on->AdvancedSearch->SearchOperator2 = @$_GET["w_created_on"];

		// updated_on
		$this->updated_on->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_updated_on"]);
		if ($this->updated_on->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->updated_on->AdvancedSearch->SearchOperator = @$_GET["z_updated_on"];
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
		$this->id->setDbValue($rs->fields('id'));
		$this->project_id->setDbValue($rs->fields('project_id'));
		$this->issue_id->setDbValue($rs->fields('issue_id'));
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->activity_id->setDbValue($rs->fields('activity_id'));
		$this->hours->setDbValue($rs->fields('hours'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->spent_on->setDbValue($rs->fields('spent_on'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->project_id->DbValue = $row['project_id'];
		$this->issue_id->DbValue = $row['issue_id'];
		$this->user_id->DbValue = $row['user_id'];
		$this->activity_id->DbValue = $row['activity_id'];
		$this->hours->DbValue = $row['hours'];
		$this->comments->DbValue = $row['comments'];
		$this->spent_on->DbValue = $row['spent_on'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->hours->FormValue == $this->hours->CurrentValue && is_numeric(ew_StrToFloat($this->hours->CurrentValue)))
			$this->hours->CurrentValue = ew_StrToFloat($this->hours->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// project_id
		// issue_id
		// user_id
		// activity_id
		// hours
		// comments
		// spent_on
		// created_on
		// updated_on

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// project_id
			if (strval($this->project_id->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->project_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->project_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->project_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->project_id->ViewValue = $this->project_id->CurrentValue;
				}
			} else {
				$this->project_id->ViewValue = NULL;
			}
			$this->project_id->ViewCustomAttributes = "";

			// issue_id
			$this->issue_id->ViewValue = $this->issue_id->CurrentValue;
			$this->issue_id->ViewCustomAttributes = "";

			// user_id
			if (strval($this->user_id->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->user_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->user_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->user_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->user_id->ViewValue = $this->user_id->CurrentValue;
				}
			} else {
				$this->user_id->ViewValue = NULL;
			}
			$this->user_id->ViewCustomAttributes = "";

			// activity_id
			$this->activity_id->ViewCustomAttributes = "";

			// hours
			$this->hours->ViewValue = $this->hours->CurrentValue;
			$this->hours->ViewCustomAttributes = "";

			// spent_on
			$this->spent_on->ViewValue = $this->spent_on->CurrentValue;
			$this->spent_on->ViewValue = ew_FormatDateTime($this->spent_on->ViewValue, 14);
			$this->spent_on->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 17);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 17);
			$this->updated_on->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// project_id
			$this->project_id->LinkCustomAttributes = "";
			$this->project_id->HrefValue = "";
			$this->project_id->TooltipValue = "";

			// issue_id
			$this->issue_id->LinkCustomAttributes = "";
			$this->issue_id->HrefValue = "";
			$this->issue_id->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// activity_id
			$this->activity_id->LinkCustomAttributes = "";
			$this->activity_id->HrefValue = "";
			$this->activity_id->TooltipValue = "";

			// hours
			$this->hours->LinkCustomAttributes = "";
			$this->hours->HrefValue = "";
			$this->hours->TooltipValue = "";

			// spent_on
			$this->spent_on->LinkCustomAttributes = "";
			$this->spent_on->HrefValue = "";
			$this->spent_on->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id->FldCaption()));

			// project_id
			$this->project_id->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[rdm_projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->project_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->project_id->EditValue = $arwrk;

			// issue_id
			$this->issue_id->EditCustomAttributes = "";
			$this->issue_id->EditValue = ew_HtmlEncode($this->issue_id->AdvancedSearch->SearchValue);
			$this->issue_id->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->issue_id->FldCaption()));

			// user_id
			$this->user_id->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[rdm_usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->user_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->user_id->EditValue = $arwrk;

			// activity_id
			$this->activity_id->EditCustomAttributes = "";

			// hours
			$this->hours->EditCustomAttributes = "";
			$this->hours->EditValue = ew_HtmlEncode($this->hours->AdvancedSearch->SearchValue);
			$this->hours->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->hours->FldCaption()));

			// spent_on
			$this->spent_on->EditCustomAttributes = "";
			$this->spent_on->EditValue = ew_HtmlEncode($this->spent_on->AdvancedSearch->SearchValue);
			$this->spent_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->spent_on->FldCaption()));
			$this->spent_on->EditCustomAttributes = "";
			$this->spent_on->EditValue2 = ew_HtmlEncode($this->spent_on->AdvancedSearch->SearchValue2);
			$this->spent_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->spent_on->FldCaption()));

			// created_on
			$this->created_on->EditCustomAttributes = "";
			$this->created_on->EditValue = ew_HtmlEncode($this->created_on->AdvancedSearch->SearchValue);
			$this->created_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->created_on->FldCaption()));
			$this->created_on->EditCustomAttributes = "";
			$this->created_on->EditValue2 = ew_HtmlEncode($this->created_on->AdvancedSearch->SearchValue2);
			$this->created_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->created_on->FldCaption()));

			// updated_on
			$this->updated_on->EditCustomAttributes = "";
			$this->updated_on->EditValue = ew_HtmlEncode($this->updated_on->AdvancedSearch->SearchValue);
			$this->updated_on->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->updated_on->FldCaption()));
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
		if (!ew_CheckInteger($this->id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->issue_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->issue_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->spent_on->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->spent_on->FldErrMsg());
		}
		if (!ew_CheckInteger($this->spent_on->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->spent_on->FldErrMsg());
		}
		if (!ew_CheckInteger($this->created_on->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->created_on->FldErrMsg());
		}
		if (!ew_CheckInteger($this->created_on->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->created_on->FldErrMsg());
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
		$this->id->AdvancedSearch->Load();
		$this->project_id->AdvancedSearch->Load();
		$this->issue_id->AdvancedSearch->Load();
		$this->user_id->AdvancedSearch->Load();
		$this->activity_id->AdvancedSearch->Load();
		$this->hours->AdvancedSearch->Load();
		$this->comments->AdvancedSearch->Load();
		$this->spent_on->AdvancedSearch->Load();
		$this->created_on->AdvancedSearch->Load();
		$this->updated_on->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_rdm_lancamento\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_rdm_lancamento',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.frdm_lancamentolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->id); // id
		$this->AddSearchQueryString($sQry, $this->project_id); // project_id
		$this->AddSearchQueryString($sQry, $this->issue_id); // issue_id
		$this->AddSearchQueryString($sQry, $this->user_id); // user_id
		$this->AddSearchQueryString($sQry, $this->activity_id); // activity_id
		$this->AddSearchQueryString($sQry, $this->hours); // hours
		$this->AddSearchQueryString($sQry, $this->comments); // comments
		$this->AddSearchQueryString($sQry, $this->spent_on); // spent_on
		$this->AddSearchQueryString($sQry, $this->created_on); // created_on
		$this->AddSearchQueryString($sQry, $this->updated_on); // updated_on

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
if (!isset($rdm_lancamento_list)) $rdm_lancamento_list = new crdm_lancamento_list();

// Page init
$rdm_lancamento_list->Page_Init();

// Page main
$rdm_lancamento_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$rdm_lancamento_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($rdm_lancamento->Export == "") { ?>
<script type="text/javascript">

// Page object
var rdm_lancamento_list = new ew_Page("rdm_lancamento_list");
rdm_lancamento_list.PageID = "list"; // Page ID
var EW_PAGE_ID = rdm_lancamento_list.PageID; // For backward compatibility

// Form object
var frdm_lancamentolist = new ew_Form("frdm_lancamentolist");
frdm_lancamentolist.FormKeyCountName = '<?php echo $rdm_lancamento_list->FormKeyCountName ?>';

// Form_CustomValidate event
frdm_lancamentolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frdm_lancamentolist.ValidateRequired = true;
<?php } else { ?>
frdm_lancamentolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frdm_lancamentolist.Lists["x_project_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frdm_lancamentolist.Lists["x_user_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var frdm_lancamentolistsrch = new ew_Form("frdm_lancamentolistsrch");

// Validate function for search
frdm_lancamentolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($rdm_lancamento->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_issue_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($rdm_lancamento->issue_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_spent_on");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($rdm_lancamento->spent_on->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_created_on");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($rdm_lancamento->created_on->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
frdm_lancamentolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frdm_lancamentolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
frdm_lancamentolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
frdm_lancamentolistsrch.Lists["x_project_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frdm_lancamentolistsrch.Lists["x_user_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (frdm_lancamentolistsrch) frdm_lancamentolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($rdm_lancamento->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($rdm_lancamento_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $rdm_lancamento_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$rdm_lancamento_list->TotalRecs = $rdm_lancamento->SelectRecordCount();
	} else {
		if ($rdm_lancamento_list->Recordset = $rdm_lancamento_list->LoadRecordset())
			$rdm_lancamento_list->TotalRecs = $rdm_lancamento_list->Recordset->RecordCount();
	}
	$rdm_lancamento_list->StartRec = 1;
	if ($rdm_lancamento_list->DisplayRecs <= 0 || ($rdm_lancamento->Export <> "" && $rdm_lancamento->ExportAll)) // Display all records
		$rdm_lancamento_list->DisplayRecs = $rdm_lancamento_list->TotalRecs;
	if (!($rdm_lancamento->Export <> "" && $rdm_lancamento->ExportAll))
		$rdm_lancamento_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$rdm_lancamento_list->Recordset = $rdm_lancamento_list->LoadRecordset($rdm_lancamento_list->StartRec-1, $rdm_lancamento_list->DisplayRecs);
$rdm_lancamento_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($rdm_lancamento->Export == "" && $rdm_lancamento->CurrentAction == "") { ?>
<form name="frdm_lancamentolistsrch" id="frdm_lancamentolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="frdm_lancamentolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#frdm_lancamentolistsrch_SearchGroup" href="#frdm_lancamentolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="frdm_lancamentolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="frdm_lancamentolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="rdm_lancamento">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$rdm_lancamento_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$rdm_lancamento->RowType = EW_ROWTYPE_SEARCH;

// Render row
$rdm_lancamento->ResetAttrs();
$rdm_lancamento_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($rdm_lancamento->id->Visible) { // id ?>
	<span id="xsc_id" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rdm_lancamento->id->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo $rdm_lancamento->id->PlaceHolder ?>" value="<?php echo $rdm_lancamento->id->EditValue ?>"<?php echo $rdm_lancamento->id->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($rdm_lancamento->project_id->Visible) { // project_id ?>
	<span id="xsc_project_id" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rdm_lancamento->project_id->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_project_id" id="z_project_id" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_project_id" id="x_project_id" name="x_project_id"<?php echo $rdm_lancamento->project_id->EditAttributes() ?>>
<?php
if (is_array($rdm_lancamento->project_id->EditValue)) {
	$arwrk = $rdm_lancamento->project_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rdm_lancamento->project_id->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
frdm_lancamentolistsrch.Lists["x_project_id"].Options = <?php echo (is_array($rdm_lancamento->project_id->EditValue)) ? ew_ArrayToJson($rdm_lancamento->project_id->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($rdm_lancamento->issue_id->Visible) { // issue_id ?>
	<span id="xsc_issue_id" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rdm_lancamento->issue_id->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_issue_id" id="z_issue_id" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_issue_id" name="x_issue_id" id="x_issue_id" size="30" placeholder="<?php echo $rdm_lancamento->issue_id->PlaceHolder ?>" value="<?php echo $rdm_lancamento->issue_id->EditValue ?>"<?php echo $rdm_lancamento->issue_id->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($rdm_lancamento->user_id->Visible) { // user_id ?>
	<span id="xsc_user_id" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rdm_lancamento->user_id->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_user_id" id="z_user_id" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_user_id" id="x_user_id" name="x_user_id"<?php echo $rdm_lancamento->user_id->EditAttributes() ?>>
<?php
if (is_array($rdm_lancamento->user_id->EditValue)) {
	$arwrk = $rdm_lancamento->user_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rdm_lancamento->user_id->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
frdm_lancamentolistsrch.Lists["x_user_id"].Options = <?php echo (is_array($rdm_lancamento->user_id->EditValue)) ? ew_ArrayToJson($rdm_lancamento->user_id->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($rdm_lancamento->spent_on->Visible) { // spent_on ?>
	<span id="xsc_spent_on" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rdm_lancamento->spent_on->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_spent_on" id="z_spent_on" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_spent_on" name="x_spent_on" id="x_spent_on" size="30" placeholder="<?php echo $rdm_lancamento->spent_on->PlaceHolder ?>" value="<?php echo $rdm_lancamento->spent_on->EditValue ?>"<?php echo $rdm_lancamento->spent_on->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_spent_on">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_spent_on">
<input type="text" data-field="x_spent_on" name="y_spent_on" id="y_spent_on" size="30" placeholder="<?php echo $rdm_lancamento->spent_on->PlaceHolder ?>" value="<?php echo $rdm_lancamento->spent_on->EditValue2 ?>"<?php echo $rdm_lancamento->spent_on->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($rdm_lancamento->created_on->Visible) { // created_on ?>
	<span id="xsc_created_on" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rdm_lancamento->created_on->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_created_on" id="z_created_on" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_created_on" name="x_created_on" id="x_created_on" size="30" placeholder="<?php echo $rdm_lancamento->created_on->PlaceHolder ?>" value="<?php echo $rdm_lancamento->created_on->EditValue ?>"<?php echo $rdm_lancamento->created_on->EditAttributes() ?>>
<?php if (!$rdm_lancamento->created_on->ReadOnly && !$rdm_lancamento->created_on->Disabled && @$rdm_lancamento->created_on->EditAttrs["readonly"] == "" && @$rdm_lancamento->created_on->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_created_on" name="cal_x_created_on" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_created_on" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("frdm_lancamentolistsrch", "x_created_on", " %H:%M:%S");
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_created_on">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_created_on">
<input type="text" data-field="x_created_on" name="y_created_on" id="y_created_on" size="30" placeholder="<?php echo $rdm_lancamento->created_on->PlaceHolder ?>" value="<?php echo $rdm_lancamento->created_on->EditValue2 ?>"<?php echo $rdm_lancamento->created_on->EditAttributes() ?>>
<?php if (!$rdm_lancamento->created_on->ReadOnly && !$rdm_lancamento->created_on->Disabled && @$rdm_lancamento->created_on->EditAttrs["readonly"] == "" && @$rdm_lancamento->created_on->EditAttrs["disabled"] == "") { ?>
<button id="cal_y_created_on" name="cal_y_created_on" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_y_created_on" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("frdm_lancamentolistsrch", "y_created_on", " %H:%M:%S");
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $rdm_lancamento_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $rdm_lancamento_list->ShowPageHeader(); ?>
<?php
$rdm_lancamento_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="frdm_lancamentolist" id="frdm_lancamentolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="rdm_lancamento">
<div id="gmp_rdm_lancamento" class="ewGridMiddlePanel">
<?php if ($rdm_lancamento_list->TotalRecs > 0) { ?>
<table id="tbl_rdm_lancamentolist" class="ewTable ewTableSeparate">
<?php echo $rdm_lancamento->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$rdm_lancamento_list->RenderListOptions();

// Render list options (header, left)
$rdm_lancamento_list->ListOptions->Render("header", "left");
?>
<?php if ($rdm_lancamento->id->Visible) { // id ?>
	<?php if ($rdm_lancamento->SortUrl($rdm_lancamento->id) == "") { ?>
		<td><div id="elh_rdm_lancamento_id" class="rdm_lancamento_id"><div class="ewTableHeaderCaption"><?php echo $rdm_lancamento->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rdm_lancamento->SortUrl($rdm_lancamento->id) ?>',2);"><div id="elh_rdm_lancamento_id" class="rdm_lancamento_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rdm_lancamento->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rdm_lancamento->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rdm_lancamento->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rdm_lancamento->project_id->Visible) { // project_id ?>
	<?php if ($rdm_lancamento->SortUrl($rdm_lancamento->project_id) == "") { ?>
		<td><div id="elh_rdm_lancamento_project_id" class="rdm_lancamento_project_id"><div class="ewTableHeaderCaption"><?php echo $rdm_lancamento->project_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rdm_lancamento->SortUrl($rdm_lancamento->project_id) ?>',2);"><div id="elh_rdm_lancamento_project_id" class="rdm_lancamento_project_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rdm_lancamento->project_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rdm_lancamento->project_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rdm_lancamento->project_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rdm_lancamento->issue_id->Visible) { // issue_id ?>
	<?php if ($rdm_lancamento->SortUrl($rdm_lancamento->issue_id) == "") { ?>
		<td><div id="elh_rdm_lancamento_issue_id" class="rdm_lancamento_issue_id"><div class="ewTableHeaderCaption"><?php echo $rdm_lancamento->issue_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rdm_lancamento->SortUrl($rdm_lancamento->issue_id) ?>',2);"><div id="elh_rdm_lancamento_issue_id" class="rdm_lancamento_issue_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rdm_lancamento->issue_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rdm_lancamento->issue_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rdm_lancamento->issue_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rdm_lancamento->user_id->Visible) { // user_id ?>
	<?php if ($rdm_lancamento->SortUrl($rdm_lancamento->user_id) == "") { ?>
		<td><div id="elh_rdm_lancamento_user_id" class="rdm_lancamento_user_id"><div class="ewTableHeaderCaption"><?php echo $rdm_lancamento->user_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rdm_lancamento->SortUrl($rdm_lancamento->user_id) ?>',2);"><div id="elh_rdm_lancamento_user_id" class="rdm_lancamento_user_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rdm_lancamento->user_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rdm_lancamento->user_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rdm_lancamento->user_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rdm_lancamento->activity_id->Visible) { // activity_id ?>
	<?php if ($rdm_lancamento->SortUrl($rdm_lancamento->activity_id) == "") { ?>
		<td><div id="elh_rdm_lancamento_activity_id" class="rdm_lancamento_activity_id"><div class="ewTableHeaderCaption"><?php echo $rdm_lancamento->activity_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rdm_lancamento->SortUrl($rdm_lancamento->activity_id) ?>',2);"><div id="elh_rdm_lancamento_activity_id" class="rdm_lancamento_activity_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rdm_lancamento->activity_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rdm_lancamento->activity_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rdm_lancamento->activity_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rdm_lancamento->hours->Visible) { // hours ?>
	<?php if ($rdm_lancamento->SortUrl($rdm_lancamento->hours) == "") { ?>
		<td><div id="elh_rdm_lancamento_hours" class="rdm_lancamento_hours"><div class="ewTableHeaderCaption"><?php echo $rdm_lancamento->hours->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rdm_lancamento->SortUrl($rdm_lancamento->hours) ?>',2);"><div id="elh_rdm_lancamento_hours" class="rdm_lancamento_hours">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rdm_lancamento->hours->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rdm_lancamento->hours->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rdm_lancamento->hours->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rdm_lancamento->spent_on->Visible) { // spent_on ?>
	<?php if ($rdm_lancamento->SortUrl($rdm_lancamento->spent_on) == "") { ?>
		<td><div id="elh_rdm_lancamento_spent_on" class="rdm_lancamento_spent_on"><div class="ewTableHeaderCaption"><?php echo $rdm_lancamento->spent_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rdm_lancamento->SortUrl($rdm_lancamento->spent_on) ?>',2);"><div id="elh_rdm_lancamento_spent_on" class="rdm_lancamento_spent_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rdm_lancamento->spent_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rdm_lancamento->spent_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rdm_lancamento->spent_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rdm_lancamento->created_on->Visible) { // created_on ?>
	<?php if ($rdm_lancamento->SortUrl($rdm_lancamento->created_on) == "") { ?>
		<td><div id="elh_rdm_lancamento_created_on" class="rdm_lancamento_created_on"><div class="ewTableHeaderCaption"><?php echo $rdm_lancamento->created_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rdm_lancamento->SortUrl($rdm_lancamento->created_on) ?>',2);"><div id="elh_rdm_lancamento_created_on" class="rdm_lancamento_created_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rdm_lancamento->created_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rdm_lancamento->created_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rdm_lancamento->created_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rdm_lancamento->updated_on->Visible) { // updated_on ?>
	<?php if ($rdm_lancamento->SortUrl($rdm_lancamento->updated_on) == "") { ?>
		<td><div id="elh_rdm_lancamento_updated_on" class="rdm_lancamento_updated_on"><div class="ewTableHeaderCaption"><?php echo $rdm_lancamento->updated_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rdm_lancamento->SortUrl($rdm_lancamento->updated_on) ?>',2);"><div id="elh_rdm_lancamento_updated_on" class="rdm_lancamento_updated_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rdm_lancamento->updated_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rdm_lancamento->updated_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rdm_lancamento->updated_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$rdm_lancamento_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($rdm_lancamento->ExportAll && $rdm_lancamento->Export <> "") {
	$rdm_lancamento_list->StopRec = $rdm_lancamento_list->TotalRecs;
} else {

	// Set the last record to display
	if ($rdm_lancamento_list->TotalRecs > $rdm_lancamento_list->StartRec + $rdm_lancamento_list->DisplayRecs - 1)
		$rdm_lancamento_list->StopRec = $rdm_lancamento_list->StartRec + $rdm_lancamento_list->DisplayRecs - 1;
	else
		$rdm_lancamento_list->StopRec = $rdm_lancamento_list->TotalRecs;
}
$rdm_lancamento_list->RecCnt = $rdm_lancamento_list->StartRec - 1;
if ($rdm_lancamento_list->Recordset && !$rdm_lancamento_list->Recordset->EOF) {
	$rdm_lancamento_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $rdm_lancamento_list->StartRec > 1)
		$rdm_lancamento_list->Recordset->Move($rdm_lancamento_list->StartRec - 1);
} elseif (!$rdm_lancamento->AllowAddDeleteRow && $rdm_lancamento_list->StopRec == 0) {
	$rdm_lancamento_list->StopRec = $rdm_lancamento->GridAddRowCount;
}

// Initialize aggregate
$rdm_lancamento->RowType = EW_ROWTYPE_AGGREGATEINIT;
$rdm_lancamento->ResetAttrs();
$rdm_lancamento_list->RenderRow();
while ($rdm_lancamento_list->RecCnt < $rdm_lancamento_list->StopRec) {
	$rdm_lancamento_list->RecCnt++;
	if (intval($rdm_lancamento_list->RecCnt) >= intval($rdm_lancamento_list->StartRec)) {
		$rdm_lancamento_list->RowCnt++;

		// Set up key count
		$rdm_lancamento_list->KeyCount = $rdm_lancamento_list->RowIndex;

		// Init row class and style
		$rdm_lancamento->ResetAttrs();
		$rdm_lancamento->CssClass = "";
		if ($rdm_lancamento->CurrentAction == "gridadd") {
		} else {
			$rdm_lancamento_list->LoadRowValues($rdm_lancamento_list->Recordset); // Load row values
		}
		$rdm_lancamento->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$rdm_lancamento->RowAttrs = array_merge($rdm_lancamento->RowAttrs, array('data-rowindex'=>$rdm_lancamento_list->RowCnt, 'id'=>'r' . $rdm_lancamento_list->RowCnt . '_rdm_lancamento', 'data-rowtype'=>$rdm_lancamento->RowType));

		// Render row
		$rdm_lancamento_list->RenderRow();

		// Render list options
		$rdm_lancamento_list->RenderListOptions();
?>
	<tr<?php echo $rdm_lancamento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$rdm_lancamento_list->ListOptions->Render("body", "left", $rdm_lancamento_list->RowCnt);
?>
	<?php if ($rdm_lancamento->id->Visible) { // id ?>
		<td<?php echo $rdm_lancamento->id->CellAttributes() ?>>
<span<?php echo $rdm_lancamento->id->ViewAttributes() ?>>
<?php echo $rdm_lancamento->id->ListViewValue() ?></span>
<a id="<?php echo $rdm_lancamento_list->PageObjName . "_row_" . $rdm_lancamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rdm_lancamento->project_id->Visible) { // project_id ?>
		<td<?php echo $rdm_lancamento->project_id->CellAttributes() ?>>
<span<?php echo $rdm_lancamento->project_id->ViewAttributes() ?>>
<?php echo $rdm_lancamento->project_id->ListViewValue() ?></span>
<a id="<?php echo $rdm_lancamento_list->PageObjName . "_row_" . $rdm_lancamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rdm_lancamento->issue_id->Visible) { // issue_id ?>
		<td<?php echo $rdm_lancamento->issue_id->CellAttributes() ?>>
<span<?php echo $rdm_lancamento->issue_id->ViewAttributes() ?>>
<?php echo $rdm_lancamento->issue_id->ListViewValue() ?></span>
<a id="<?php echo $rdm_lancamento_list->PageObjName . "_row_" . $rdm_lancamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rdm_lancamento->user_id->Visible) { // user_id ?>
		<td<?php echo $rdm_lancamento->user_id->CellAttributes() ?>>
<span<?php echo $rdm_lancamento->user_id->ViewAttributes() ?>>
<?php echo $rdm_lancamento->user_id->ListViewValue() ?></span>
<a id="<?php echo $rdm_lancamento_list->PageObjName . "_row_" . $rdm_lancamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rdm_lancamento->activity_id->Visible) { // activity_id ?>
		<td<?php echo $rdm_lancamento->activity_id->CellAttributes() ?>>
<span<?php echo $rdm_lancamento->activity_id->ViewAttributes() ?>>
<?php echo $rdm_lancamento->activity_id->ListViewValue() ?></span>
<a id="<?php echo $rdm_lancamento_list->PageObjName . "_row_" . $rdm_lancamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rdm_lancamento->hours->Visible) { // hours ?>
		<td<?php echo $rdm_lancamento->hours->CellAttributes() ?>>
<span<?php echo $rdm_lancamento->hours->ViewAttributes() ?>>
<?php echo $rdm_lancamento->hours->ListViewValue() ?></span>
<a id="<?php echo $rdm_lancamento_list->PageObjName . "_row_" . $rdm_lancamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rdm_lancamento->spent_on->Visible) { // spent_on ?>
		<td<?php echo $rdm_lancamento->spent_on->CellAttributes() ?>>
<span<?php echo $rdm_lancamento->spent_on->ViewAttributes() ?>>
<?php echo $rdm_lancamento->spent_on->ListViewValue() ?></span>
<a id="<?php echo $rdm_lancamento_list->PageObjName . "_row_" . $rdm_lancamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rdm_lancamento->created_on->Visible) { // created_on ?>
		<td<?php echo $rdm_lancamento->created_on->CellAttributes() ?>>
<span<?php echo $rdm_lancamento->created_on->ViewAttributes() ?>>
<?php echo $rdm_lancamento->created_on->ListViewValue() ?></span>
<a id="<?php echo $rdm_lancamento_list->PageObjName . "_row_" . $rdm_lancamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rdm_lancamento->updated_on->Visible) { // updated_on ?>
		<td<?php echo $rdm_lancamento->updated_on->CellAttributes() ?>>
<span<?php echo $rdm_lancamento->updated_on->ViewAttributes() ?>>
<?php echo $rdm_lancamento->updated_on->ListViewValue() ?></span>
<a id="<?php echo $rdm_lancamento_list->PageObjName . "_row_" . $rdm_lancamento_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$rdm_lancamento_list->ListOptions->Render("body", "right", $rdm_lancamento_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($rdm_lancamento->CurrentAction <> "gridadd")
		$rdm_lancamento_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($rdm_lancamento->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($rdm_lancamento_list->Recordset)
	$rdm_lancamento_list->Recordset->Close();
?>
<?php if ($rdm_lancamento->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($rdm_lancamento->CurrentAction <> "gridadd" && $rdm_lancamento->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($rdm_lancamento_list->Pager)) $rdm_lancamento_list->Pager = new cNumericPager($rdm_lancamento_list->StartRec, $rdm_lancamento_list->DisplayRecs, $rdm_lancamento_list->TotalRecs, $rdm_lancamento_list->RecRange) ?>
<?php if ($rdm_lancamento_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($rdm_lancamento_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $rdm_lancamento_list->PageUrl() ?>start=<?php echo $rdm_lancamento_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($rdm_lancamento_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $rdm_lancamento_list->PageUrl() ?>start=<?php echo $rdm_lancamento_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($rdm_lancamento_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $rdm_lancamento_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($rdm_lancamento_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $rdm_lancamento_list->PageUrl() ?>start=<?php echo $rdm_lancamento_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($rdm_lancamento_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $rdm_lancamento_list->PageUrl() ?>start=<?php echo $rdm_lancamento_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($rdm_lancamento_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $rdm_lancamento_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $rdm_lancamento_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $rdm_lancamento_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($rdm_lancamento_list->SearchWhere == "0=101") { ?>
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
	foreach ($rdm_lancamento_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($rdm_lancamento->Export == "") { ?>
<script type="text/javascript">
frdm_lancamentolistsrch.Init();
frdm_lancamentolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$rdm_lancamento_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($rdm_lancamento->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$rdm_lancamento_list->Page_Terminate();
?>
