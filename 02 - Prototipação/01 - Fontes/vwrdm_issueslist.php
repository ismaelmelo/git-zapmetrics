<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "vwrdm_issuesinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$vwrdm_issues_list = NULL; // Initialize page object first

class cvwrdm_issues_list extends cvwrdm_issues {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{3DD49CE0-D729-4A83-8076-D56A551C92BF}";

	// Table name
	var $TableName = 'vwrdm_issues';

	// Page object name
	var $PageObjName = 'vwrdm_issues_list';

	// Grid form hidden field names
	var $FormName = 'fvwrdm_issueslist';
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

		// Table object (vwrdm_issues)
		if (!isset($GLOBALS["vwrdm_issues"])) {
			$GLOBALS["vwrdm_issues"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vwrdm_issues"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vwrdm_issuesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vwrdm_issuesdelete.php";
		$this->MultiUpdateUrl = "vwrdm_issuesupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vwrdm_issues', TRUE);

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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
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

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
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

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->subject, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->description, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
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
			$this->UpdateSort($this->tracker_id, $bCtrl); // tracker_id
			$this->UpdateSort($this->project_id, $bCtrl); // project_id
			$this->UpdateSort($this->subject, $bCtrl); // subject
			$this->UpdateSort($this->due_date, $bCtrl); // due_date
			$this->UpdateSort($this->category_id, $bCtrl); // category_id
			$this->UpdateSort($this->status_id, $bCtrl); // status_id
			$this->UpdateSort($this->assigned_to_id, $bCtrl); // assigned_to_id
			$this->UpdateSort($this->priority_id, $bCtrl); // priority_id
			$this->UpdateSort($this->fixed_version_id, $bCtrl); // fixed_version_id
			$this->UpdateSort($this->author_id, $bCtrl); // author_id
			$this->UpdateSort($this->lock_version, $bCtrl); // lock_version
			$this->UpdateSort($this->created_on, $bCtrl); // created_on
			$this->UpdateSort($this->updated_on, $bCtrl); // updated_on
			$this->UpdateSort($this->start_date, $bCtrl); // start_date
			$this->UpdateSort($this->done_ratio, $bCtrl); // done_ratio
			$this->UpdateSort($this->estimated_hours, $bCtrl); // estimated_hours
			$this->UpdateSort($this->parent_id, $bCtrl); // parent_id
			$this->UpdateSort($this->root_id, $bCtrl); // root_id
			$this->UpdateSort($this->lft, $bCtrl); // lft
			$this->UpdateSort($this->rgt, $bCtrl); // rgt
			$this->UpdateSort($this->is_private, $bCtrl); // is_private
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
				$this->tracker_id->setSort("");
				$this->project_id->setSort("");
				$this->subject->setSort("");
				$this->due_date->setSort("");
				$this->category_id->setSort("");
				$this->status_id->setSort("");
				$this->assigned_to_id->setSort("");
				$this->priority_id->setSort("");
				$this->fixed_version_id->setSort("");
				$this->author_id->setSort("");
				$this->lock_version->setSort("");
				$this->created_on->setSort("");
				$this->updated_on->setSort("");
				$this->start_date->setSort("");
				$this->done_ratio->setSort("");
				$this->estimated_hours->setSort("");
				$this->parent_id->setSort("");
				$this->root_id->setSort("");
				$this->lft->setSort("");
				$this->rgt->setSort("");
				$this->is_private->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fvwrdm_issueslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->tracker_id->setDbValue($rs->fields('tracker_id'));
		$this->project_id->setDbValue($rs->fields('project_id'));
		$this->subject->setDbValue($rs->fields('subject'));
		$this->description->setDbValue($rs->fields('description'));
		$this->due_date->setDbValue($rs->fields('due_date'));
		$this->category_id->setDbValue($rs->fields('category_id'));
		$this->status_id->setDbValue($rs->fields('status_id'));
		$this->assigned_to_id->setDbValue($rs->fields('assigned_to_id'));
		$this->priority_id->setDbValue($rs->fields('priority_id'));
		$this->fixed_version_id->setDbValue($rs->fields('fixed_version_id'));
		$this->author_id->setDbValue($rs->fields('author_id'));
		$this->lock_version->setDbValue($rs->fields('lock_version'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->done_ratio->setDbValue($rs->fields('done_ratio'));
		$this->estimated_hours->setDbValue($rs->fields('estimated_hours'));
		$this->parent_id->setDbValue($rs->fields('parent_id'));
		$this->root_id->setDbValue($rs->fields('root_id'));
		$this->lft->setDbValue($rs->fields('lft'));
		$this->rgt->setDbValue($rs->fields('rgt'));
		$this->is_private->setDbValue($rs->fields('is_private'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->tracker_id->DbValue = $row['tracker_id'];
		$this->project_id->DbValue = $row['project_id'];
		$this->subject->DbValue = $row['subject'];
		$this->description->DbValue = $row['description'];
		$this->due_date->DbValue = $row['due_date'];
		$this->category_id->DbValue = $row['category_id'];
		$this->status_id->DbValue = $row['status_id'];
		$this->assigned_to_id->DbValue = $row['assigned_to_id'];
		$this->priority_id->DbValue = $row['priority_id'];
		$this->fixed_version_id->DbValue = $row['fixed_version_id'];
		$this->author_id->DbValue = $row['author_id'];
		$this->lock_version->DbValue = $row['lock_version'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
		$this->start_date->DbValue = $row['start_date'];
		$this->done_ratio->DbValue = $row['done_ratio'];
		$this->estimated_hours->DbValue = $row['estimated_hours'];
		$this->parent_id->DbValue = $row['parent_id'];
		$this->root_id->DbValue = $row['root_id'];
		$this->lft->DbValue = $row['lft'];
		$this->rgt->DbValue = $row['rgt'];
		$this->is_private->DbValue = $row['is_private'];
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

		// Convert decimal values if posted back
		if ($this->estimated_hours->FormValue == $this->estimated_hours->CurrentValue && is_numeric(ew_StrToFloat($this->estimated_hours->CurrentValue)))
			$this->estimated_hours->CurrentValue = ew_StrToFloat($this->estimated_hours->CurrentValue);

		// Convert decimal values if posted back
		if ($this->is_private->FormValue == $this->is_private->CurrentValue && is_numeric(ew_StrToFloat($this->is_private->CurrentValue)))
			$this->is_private->CurrentValue = ew_StrToFloat($this->is_private->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// tracker_id
		// project_id
		// subject
		// description
		// due_date
		// category_id
		// status_id
		// assigned_to_id
		// priority_id
		// fixed_version_id
		// author_id
		// lock_version
		// created_on
		// updated_on
		// start_date
		// done_ratio
		// estimated_hours
		// parent_id
		// root_id
		// lft
		// rgt
		// is_private

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// tracker_id
			$this->tracker_id->ViewValue = $this->tracker_id->CurrentValue;
			$this->tracker_id->ViewCustomAttributes = "";

			// project_id
			$this->project_id->ViewValue = $this->project_id->CurrentValue;
			$this->project_id->ViewCustomAttributes = "";

			// subject
			$this->subject->ViewValue = $this->subject->CurrentValue;
			$this->subject->ViewCustomAttributes = "";

			// due_date
			$this->due_date->ViewValue = $this->due_date->CurrentValue;
			$this->due_date->ViewValue = ew_FormatDateTime($this->due_date->ViewValue, 7);
			$this->due_date->ViewCustomAttributes = "";

			// category_id
			$this->category_id->ViewValue = $this->category_id->CurrentValue;
			$this->category_id->ViewCustomAttributes = "";

			// status_id
			$this->status_id->ViewValue = $this->status_id->CurrentValue;
			$this->status_id->ViewCustomAttributes = "";

			// assigned_to_id
			$this->assigned_to_id->ViewValue = $this->assigned_to_id->CurrentValue;
			$this->assigned_to_id->ViewCustomAttributes = "";

			// priority_id
			$this->priority_id->ViewValue = $this->priority_id->CurrentValue;
			$this->priority_id->ViewCustomAttributes = "";

			// fixed_version_id
			$this->fixed_version_id->ViewValue = $this->fixed_version_id->CurrentValue;
			$this->fixed_version_id->ViewCustomAttributes = "";

			// author_id
			$this->author_id->ViewValue = $this->author_id->CurrentValue;
			$this->author_id->ViewCustomAttributes = "";

			// lock_version
			$this->lock_version->ViewValue = $this->lock_version->CurrentValue;
			$this->lock_version->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
			$this->updated_on->ViewCustomAttributes = "";

			// start_date
			$this->start_date->ViewValue = $this->start_date->CurrentValue;
			$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 7);
			$this->start_date->ViewCustomAttributes = "";

			// done_ratio
			$this->done_ratio->ViewValue = $this->done_ratio->CurrentValue;
			$this->done_ratio->ViewCustomAttributes = "";

			// estimated_hours
			$this->estimated_hours->ViewValue = $this->estimated_hours->CurrentValue;
			$this->estimated_hours->ViewCustomAttributes = "";

			// parent_id
			$this->parent_id->ViewValue = $this->parent_id->CurrentValue;
			$this->parent_id->ViewCustomAttributes = "";

			// root_id
			$this->root_id->ViewValue = $this->root_id->CurrentValue;
			$this->root_id->ViewCustomAttributes = "";

			// lft
			$this->lft->ViewValue = $this->lft->CurrentValue;
			$this->lft->ViewCustomAttributes = "";

			// rgt
			$this->rgt->ViewValue = $this->rgt->CurrentValue;
			$this->rgt->ViewCustomAttributes = "";

			// is_private
			$this->is_private->ViewValue = $this->is_private->CurrentValue;
			$this->is_private->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// tracker_id
			$this->tracker_id->LinkCustomAttributes = "";
			$this->tracker_id->HrefValue = "";
			$this->tracker_id->TooltipValue = "";

			// project_id
			$this->project_id->LinkCustomAttributes = "";
			$this->project_id->HrefValue = "";
			$this->project_id->TooltipValue = "";

			// subject
			$this->subject->LinkCustomAttributes = "";
			$this->subject->HrefValue = "";
			$this->subject->TooltipValue = "";

			// due_date
			$this->due_date->LinkCustomAttributes = "";
			$this->due_date->HrefValue = "";
			$this->due_date->TooltipValue = "";

			// category_id
			$this->category_id->LinkCustomAttributes = "";
			$this->category_id->HrefValue = "";
			$this->category_id->TooltipValue = "";

			// status_id
			$this->status_id->LinkCustomAttributes = "";
			$this->status_id->HrefValue = "";
			$this->status_id->TooltipValue = "";

			// assigned_to_id
			$this->assigned_to_id->LinkCustomAttributes = "";
			$this->assigned_to_id->HrefValue = "";
			$this->assigned_to_id->TooltipValue = "";

			// priority_id
			$this->priority_id->LinkCustomAttributes = "";
			$this->priority_id->HrefValue = "";
			$this->priority_id->TooltipValue = "";

			// fixed_version_id
			$this->fixed_version_id->LinkCustomAttributes = "";
			$this->fixed_version_id->HrefValue = "";
			$this->fixed_version_id->TooltipValue = "";

			// author_id
			$this->author_id->LinkCustomAttributes = "";
			$this->author_id->HrefValue = "";
			$this->author_id->TooltipValue = "";

			// lock_version
			$this->lock_version->LinkCustomAttributes = "";
			$this->lock_version->HrefValue = "";
			$this->lock_version->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// done_ratio
			$this->done_ratio->LinkCustomAttributes = "";
			$this->done_ratio->HrefValue = "";
			$this->done_ratio->TooltipValue = "";

			// estimated_hours
			$this->estimated_hours->LinkCustomAttributes = "";
			$this->estimated_hours->HrefValue = "";
			$this->estimated_hours->TooltipValue = "";

			// parent_id
			$this->parent_id->LinkCustomAttributes = "";
			$this->parent_id->HrefValue = "";
			$this->parent_id->TooltipValue = "";

			// root_id
			$this->root_id->LinkCustomAttributes = "";
			$this->root_id->HrefValue = "";
			$this->root_id->TooltipValue = "";

			// lft
			$this->lft->LinkCustomAttributes = "";
			$this->lft->HrefValue = "";
			$this->lft->TooltipValue = "";

			// rgt
			$this->rgt->LinkCustomAttributes = "";
			$this->rgt->HrefValue = "";
			$this->rgt->TooltipValue = "";

			// is_private
			$this->is_private->LinkCustomAttributes = "";
			$this->is_private->HrefValue = "";
			$this->is_private->TooltipValue = "";
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
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_vwrdm_issues\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_vwrdm_issues',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fvwrdm_issueslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}

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
if (!isset($vwrdm_issues_list)) $vwrdm_issues_list = new cvwrdm_issues_list();

// Page init
$vwrdm_issues_list->Page_Init();

// Page main
$vwrdm_issues_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vwrdm_issues_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($vwrdm_issues->Export == "") { ?>
<script type="text/javascript">

// Page object
var vwrdm_issues_list = new ew_Page("vwrdm_issues_list");
vwrdm_issues_list.PageID = "list"; // Page ID
var EW_PAGE_ID = vwrdm_issues_list.PageID; // For backward compatibility

// Form object
var fvwrdm_issueslist = new ew_Form("fvwrdm_issueslist");
fvwrdm_issueslist.FormKeyCountName = '<?php echo $vwrdm_issues_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvwrdm_issueslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvwrdm_issueslist.ValidateRequired = true;
<?php } else { ?>
fvwrdm_issueslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fvwrdm_issueslistsrch = new ew_Form("fvwrdm_issueslistsrch");

// Init search panel as collapsed
if (fvwrdm_issueslistsrch) fvwrdm_issueslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($vwrdm_issues->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($vwrdm_issues_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $vwrdm_issues_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$vwrdm_issues_list->TotalRecs = $vwrdm_issues->SelectRecordCount();
	} else {
		if ($vwrdm_issues_list->Recordset = $vwrdm_issues_list->LoadRecordset())
			$vwrdm_issues_list->TotalRecs = $vwrdm_issues_list->Recordset->RecordCount();
	}
	$vwrdm_issues_list->StartRec = 1;
	if ($vwrdm_issues_list->DisplayRecs <= 0 || ($vwrdm_issues->Export <> "" && $vwrdm_issues->ExportAll)) // Display all records
		$vwrdm_issues_list->DisplayRecs = $vwrdm_issues_list->TotalRecs;
	if (!($vwrdm_issues->Export <> "" && $vwrdm_issues->ExportAll))
		$vwrdm_issues_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vwrdm_issues_list->Recordset = $vwrdm_issues_list->LoadRecordset($vwrdm_issues_list->StartRec-1, $vwrdm_issues_list->DisplayRecs);
$vwrdm_issues_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($vwrdm_issues->Export == "" && $vwrdm_issues->CurrentAction == "") { ?>
<form name="fvwrdm_issueslistsrch" id="fvwrdm_issueslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fvwrdm_issueslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fvwrdm_issueslistsrch_SearchGroup" href="#fvwrdm_issueslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fvwrdm_issueslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fvwrdm_issueslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="vwrdm_issues">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($vwrdm_issues_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $vwrdm_issues_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($vwrdm_issues_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($vwrdm_issues_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($vwrdm_issues_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $vwrdm_issues_list->ShowPageHeader(); ?>
<?php
$vwrdm_issues_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fvwrdm_issueslist" id="fvwrdm_issueslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="vwrdm_issues">
<div id="gmp_vwrdm_issues" class="ewGridMiddlePanel">
<?php if ($vwrdm_issues_list->TotalRecs > 0) { ?>
<table id="tbl_vwrdm_issueslist" class="ewTable ewTableSeparate">
<?php echo $vwrdm_issues->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$vwrdm_issues_list->RenderListOptions();

// Render list options (header, left)
$vwrdm_issues_list->ListOptions->Render("header", "left");
?>
<?php if ($vwrdm_issues->id->Visible) { // id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->id) == "") { ?>
		<td><div id="elh_vwrdm_issues_id" class="vwrdm_issues_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->id) ?>',2);"><div id="elh_vwrdm_issues_id" class="vwrdm_issues_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->tracker_id->Visible) { // tracker_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->tracker_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_tracker_id" class="vwrdm_issues_tracker_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->tracker_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->tracker_id) ?>',2);"><div id="elh_vwrdm_issues_tracker_id" class="vwrdm_issues_tracker_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->tracker_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->tracker_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->tracker_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->project_id->Visible) { // project_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->project_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_project_id" class="vwrdm_issues_project_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->project_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->project_id) ?>',2);"><div id="elh_vwrdm_issues_project_id" class="vwrdm_issues_project_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->project_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->project_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->project_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->subject->Visible) { // subject ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->subject) == "") { ?>
		<td><div id="elh_vwrdm_issues_subject" class="vwrdm_issues_subject"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->subject->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->subject) ?>',2);"><div id="elh_vwrdm_issues_subject" class="vwrdm_issues_subject">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->subject->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->subject->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->subject->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->due_date->Visible) { // due_date ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->due_date) == "") { ?>
		<td><div id="elh_vwrdm_issues_due_date" class="vwrdm_issues_due_date"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->due_date->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->due_date) ?>',2);"><div id="elh_vwrdm_issues_due_date" class="vwrdm_issues_due_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->due_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->due_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->due_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->category_id->Visible) { // category_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->category_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_category_id" class="vwrdm_issues_category_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->category_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->category_id) ?>',2);"><div id="elh_vwrdm_issues_category_id" class="vwrdm_issues_category_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->category_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->category_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->category_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->status_id->Visible) { // status_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->status_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_status_id" class="vwrdm_issues_status_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->status_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->status_id) ?>',2);"><div id="elh_vwrdm_issues_status_id" class="vwrdm_issues_status_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->status_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->status_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->status_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->assigned_to_id->Visible) { // assigned_to_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->assigned_to_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_assigned_to_id" class="vwrdm_issues_assigned_to_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->assigned_to_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->assigned_to_id) ?>',2);"><div id="elh_vwrdm_issues_assigned_to_id" class="vwrdm_issues_assigned_to_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->assigned_to_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->assigned_to_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->assigned_to_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->priority_id->Visible) { // priority_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->priority_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_priority_id" class="vwrdm_issues_priority_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->priority_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->priority_id) ?>',2);"><div id="elh_vwrdm_issues_priority_id" class="vwrdm_issues_priority_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->priority_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->priority_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->priority_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->fixed_version_id->Visible) { // fixed_version_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->fixed_version_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_fixed_version_id" class="vwrdm_issues_fixed_version_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->fixed_version_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->fixed_version_id) ?>',2);"><div id="elh_vwrdm_issues_fixed_version_id" class="vwrdm_issues_fixed_version_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->fixed_version_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->fixed_version_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->fixed_version_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->author_id->Visible) { // author_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->author_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_author_id" class="vwrdm_issues_author_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->author_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->author_id) ?>',2);"><div id="elh_vwrdm_issues_author_id" class="vwrdm_issues_author_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->author_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->author_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->author_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->lock_version->Visible) { // lock_version ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->lock_version) == "") { ?>
		<td><div id="elh_vwrdm_issues_lock_version" class="vwrdm_issues_lock_version"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->lock_version->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->lock_version) ?>',2);"><div id="elh_vwrdm_issues_lock_version" class="vwrdm_issues_lock_version">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->lock_version->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->lock_version->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->lock_version->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->created_on->Visible) { // created_on ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->created_on) == "") { ?>
		<td><div id="elh_vwrdm_issues_created_on" class="vwrdm_issues_created_on"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->created_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->created_on) ?>',2);"><div id="elh_vwrdm_issues_created_on" class="vwrdm_issues_created_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->created_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->created_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->created_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->updated_on->Visible) { // updated_on ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->updated_on) == "") { ?>
		<td><div id="elh_vwrdm_issues_updated_on" class="vwrdm_issues_updated_on"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->updated_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->updated_on) ?>',2);"><div id="elh_vwrdm_issues_updated_on" class="vwrdm_issues_updated_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->updated_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->updated_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->updated_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->start_date->Visible) { // start_date ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->start_date) == "") { ?>
		<td><div id="elh_vwrdm_issues_start_date" class="vwrdm_issues_start_date"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->start_date->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->start_date) ?>',2);"><div id="elh_vwrdm_issues_start_date" class="vwrdm_issues_start_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->start_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->start_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->start_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->done_ratio->Visible) { // done_ratio ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->done_ratio) == "") { ?>
		<td><div id="elh_vwrdm_issues_done_ratio" class="vwrdm_issues_done_ratio"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->done_ratio->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->done_ratio) ?>',2);"><div id="elh_vwrdm_issues_done_ratio" class="vwrdm_issues_done_ratio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->done_ratio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->done_ratio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->done_ratio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->estimated_hours->Visible) { // estimated_hours ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->estimated_hours) == "") { ?>
		<td><div id="elh_vwrdm_issues_estimated_hours" class="vwrdm_issues_estimated_hours"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->estimated_hours->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->estimated_hours) ?>',2);"><div id="elh_vwrdm_issues_estimated_hours" class="vwrdm_issues_estimated_hours">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->estimated_hours->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->estimated_hours->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->estimated_hours->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->parent_id->Visible) { // parent_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->parent_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_parent_id" class="vwrdm_issues_parent_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->parent_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->parent_id) ?>',2);"><div id="elh_vwrdm_issues_parent_id" class="vwrdm_issues_parent_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->parent_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->parent_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->parent_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->root_id->Visible) { // root_id ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->root_id) == "") { ?>
		<td><div id="elh_vwrdm_issues_root_id" class="vwrdm_issues_root_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->root_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->root_id) ?>',2);"><div id="elh_vwrdm_issues_root_id" class="vwrdm_issues_root_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->root_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->root_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->root_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->lft->Visible) { // lft ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->lft) == "") { ?>
		<td><div id="elh_vwrdm_issues_lft" class="vwrdm_issues_lft"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->lft->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->lft) ?>',2);"><div id="elh_vwrdm_issues_lft" class="vwrdm_issues_lft">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->lft->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->lft->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->lft->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->rgt->Visible) { // rgt ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->rgt) == "") { ?>
		<td><div id="elh_vwrdm_issues_rgt" class="vwrdm_issues_rgt"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->rgt->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->rgt) ?>',2);"><div id="elh_vwrdm_issues_rgt" class="vwrdm_issues_rgt">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->rgt->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->rgt->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->rgt->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_issues->is_private->Visible) { // is_private ?>
	<?php if ($vwrdm_issues->SortUrl($vwrdm_issues->is_private) == "") { ?>
		<td><div id="elh_vwrdm_issues_is_private" class="vwrdm_issues_is_private"><div class="ewTableHeaderCaption"><?php echo $vwrdm_issues->is_private->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_issues->SortUrl($vwrdm_issues->is_private) ?>',2);"><div id="elh_vwrdm_issues_is_private" class="vwrdm_issues_is_private">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_issues->is_private->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_issues->is_private->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_issues->is_private->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vwrdm_issues_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vwrdm_issues->ExportAll && $vwrdm_issues->Export <> "") {
	$vwrdm_issues_list->StopRec = $vwrdm_issues_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vwrdm_issues_list->TotalRecs > $vwrdm_issues_list->StartRec + $vwrdm_issues_list->DisplayRecs - 1)
		$vwrdm_issues_list->StopRec = $vwrdm_issues_list->StartRec + $vwrdm_issues_list->DisplayRecs - 1;
	else
		$vwrdm_issues_list->StopRec = $vwrdm_issues_list->TotalRecs;
}
$vwrdm_issues_list->RecCnt = $vwrdm_issues_list->StartRec - 1;
if ($vwrdm_issues_list->Recordset && !$vwrdm_issues_list->Recordset->EOF) {
	$vwrdm_issues_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $vwrdm_issues_list->StartRec > 1)
		$vwrdm_issues_list->Recordset->Move($vwrdm_issues_list->StartRec - 1);
} elseif (!$vwrdm_issues->AllowAddDeleteRow && $vwrdm_issues_list->StopRec == 0) {
	$vwrdm_issues_list->StopRec = $vwrdm_issues->GridAddRowCount;
}

// Initialize aggregate
$vwrdm_issues->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vwrdm_issues->ResetAttrs();
$vwrdm_issues_list->RenderRow();
while ($vwrdm_issues_list->RecCnt < $vwrdm_issues_list->StopRec) {
	$vwrdm_issues_list->RecCnt++;
	if (intval($vwrdm_issues_list->RecCnt) >= intval($vwrdm_issues_list->StartRec)) {
		$vwrdm_issues_list->RowCnt++;

		// Set up key count
		$vwrdm_issues_list->KeyCount = $vwrdm_issues_list->RowIndex;

		// Init row class and style
		$vwrdm_issues->ResetAttrs();
		$vwrdm_issues->CssClass = "";
		if ($vwrdm_issues->CurrentAction == "gridadd") {
		} else {
			$vwrdm_issues_list->LoadRowValues($vwrdm_issues_list->Recordset); // Load row values
		}
		$vwrdm_issues->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vwrdm_issues->RowAttrs = array_merge($vwrdm_issues->RowAttrs, array('data-rowindex'=>$vwrdm_issues_list->RowCnt, 'id'=>'r' . $vwrdm_issues_list->RowCnt . '_vwrdm_issues', 'data-rowtype'=>$vwrdm_issues->RowType));

		// Render row
		$vwrdm_issues_list->RenderRow();

		// Render list options
		$vwrdm_issues_list->RenderListOptions();
?>
	<tr<?php echo $vwrdm_issues->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vwrdm_issues_list->ListOptions->Render("body", "left", $vwrdm_issues_list->RowCnt);
?>
	<?php if ($vwrdm_issues->id->Visible) { // id ?>
		<td<?php echo $vwrdm_issues->id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->tracker_id->Visible) { // tracker_id ?>
		<td<?php echo $vwrdm_issues->tracker_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->tracker_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->tracker_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->project_id->Visible) { // project_id ?>
		<td<?php echo $vwrdm_issues->project_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->project_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->project_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->subject->Visible) { // subject ?>
		<td<?php echo $vwrdm_issues->subject->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->subject->ViewAttributes() ?>>
<?php echo $vwrdm_issues->subject->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->due_date->Visible) { // due_date ?>
		<td<?php echo $vwrdm_issues->due_date->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->due_date->ViewAttributes() ?>>
<?php echo $vwrdm_issues->due_date->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->category_id->Visible) { // category_id ?>
		<td<?php echo $vwrdm_issues->category_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->category_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->category_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->status_id->Visible) { // status_id ?>
		<td<?php echo $vwrdm_issues->status_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->status_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->status_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->assigned_to_id->Visible) { // assigned_to_id ?>
		<td<?php echo $vwrdm_issues->assigned_to_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->assigned_to_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->assigned_to_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->priority_id->Visible) { // priority_id ?>
		<td<?php echo $vwrdm_issues->priority_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->priority_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->priority_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->fixed_version_id->Visible) { // fixed_version_id ?>
		<td<?php echo $vwrdm_issues->fixed_version_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->fixed_version_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->fixed_version_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->author_id->Visible) { // author_id ?>
		<td<?php echo $vwrdm_issues->author_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->author_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->author_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->lock_version->Visible) { // lock_version ?>
		<td<?php echo $vwrdm_issues->lock_version->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->lock_version->ViewAttributes() ?>>
<?php echo $vwrdm_issues->lock_version->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->created_on->Visible) { // created_on ?>
		<td<?php echo $vwrdm_issues->created_on->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->created_on->ViewAttributes() ?>>
<?php echo $vwrdm_issues->created_on->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->updated_on->Visible) { // updated_on ?>
		<td<?php echo $vwrdm_issues->updated_on->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->updated_on->ViewAttributes() ?>>
<?php echo $vwrdm_issues->updated_on->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->start_date->Visible) { // start_date ?>
		<td<?php echo $vwrdm_issues->start_date->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->start_date->ViewAttributes() ?>>
<?php echo $vwrdm_issues->start_date->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->done_ratio->Visible) { // done_ratio ?>
		<td<?php echo $vwrdm_issues->done_ratio->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->done_ratio->ViewAttributes() ?>>
<?php echo $vwrdm_issues->done_ratio->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->estimated_hours->Visible) { // estimated_hours ?>
		<td<?php echo $vwrdm_issues->estimated_hours->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->estimated_hours->ViewAttributes() ?>>
<?php echo $vwrdm_issues->estimated_hours->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->parent_id->Visible) { // parent_id ?>
		<td<?php echo $vwrdm_issues->parent_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->parent_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->parent_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->root_id->Visible) { // root_id ?>
		<td<?php echo $vwrdm_issues->root_id->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->root_id->ViewAttributes() ?>>
<?php echo $vwrdm_issues->root_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->lft->Visible) { // lft ?>
		<td<?php echo $vwrdm_issues->lft->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->lft->ViewAttributes() ?>>
<?php echo $vwrdm_issues->lft->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->rgt->Visible) { // rgt ?>
		<td<?php echo $vwrdm_issues->rgt->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->rgt->ViewAttributes() ?>>
<?php echo $vwrdm_issues->rgt->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_issues->is_private->Visible) { // is_private ?>
		<td<?php echo $vwrdm_issues->is_private->CellAttributes() ?>>
<span<?php echo $vwrdm_issues->is_private->ViewAttributes() ?>>
<?php echo $vwrdm_issues->is_private->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_issues_list->PageObjName . "_row_" . $vwrdm_issues_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$vwrdm_issues_list->ListOptions->Render("body", "right", $vwrdm_issues_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vwrdm_issues->CurrentAction <> "gridadd")
		$vwrdm_issues_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vwrdm_issues->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vwrdm_issues_list->Recordset)
	$vwrdm_issues_list->Recordset->Close();
?>
<?php if ($vwrdm_issues->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($vwrdm_issues->CurrentAction <> "gridadd" && $vwrdm_issues->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($vwrdm_issues_list->Pager)) $vwrdm_issues_list->Pager = new cNumericPager($vwrdm_issues_list->StartRec, $vwrdm_issues_list->DisplayRecs, $vwrdm_issues_list->TotalRecs, $vwrdm_issues_list->RecRange) ?>
<?php if ($vwrdm_issues_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($vwrdm_issues_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_issues_list->PageUrl() ?>start=<?php echo $vwrdm_issues_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_issues_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_issues_list->PageUrl() ?>start=<?php echo $vwrdm_issues_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($vwrdm_issues_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $vwrdm_issues_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_issues_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_issues_list->PageUrl() ?>start=<?php echo $vwrdm_issues_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_issues_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_issues_list->PageUrl() ?>start=<?php echo $vwrdm_issues_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($vwrdm_issues_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vwrdm_issues_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vwrdm_issues_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vwrdm_issues_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($vwrdm_issues_list->SearchWhere == "0=101") { ?>
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
	foreach ($vwrdm_issues_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($vwrdm_issues->Export == "") { ?>
<script type="text/javascript">
fvwrdm_issueslistsrch.Init();
fvwrdm_issueslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$vwrdm_issues_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($vwrdm_issues->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$vwrdm_issues_list->Page_Terminate();
?>
