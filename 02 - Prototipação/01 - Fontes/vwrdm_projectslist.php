<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "vwrdm_projectsinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$vwrdm_projects_list = NULL; // Initialize page object first

class cvwrdm_projects_list extends cvwrdm_projects {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{3DD49CE0-D729-4A83-8076-D56A551C92BF}";

	// Table name
	var $TableName = 'vwrdm_projects';

	// Page object name
	var $PageObjName = 'vwrdm_projects_list';

	// Grid form hidden field names
	var $FormName = 'fvwrdm_projectslist';
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

		// Table object (vwrdm_projects)
		if (!isset($GLOBALS["vwrdm_projects"])) {
			$GLOBALS["vwrdm_projects"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vwrdm_projects"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vwrdm_projectsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vwrdm_projectsdelete.php";
		$this->MultiUpdateUrl = "vwrdm_projectsupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vwrdm_projects', TRUE);

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
		$this->BuildBasicSearchSQL($sWhere, $this->name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->description, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->homepage, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->identifier, $Keyword);
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
			$this->UpdateSort($this->name, $bCtrl); // name
			$this->UpdateSort($this->homepage, $bCtrl); // homepage
			$this->UpdateSort($this->is_public, $bCtrl); // is_public
			$this->UpdateSort($this->parent_id, $bCtrl); // parent_id
			$this->UpdateSort($this->created_on, $bCtrl); // created_on
			$this->UpdateSort($this->updated_on, $bCtrl); // updated_on
			$this->UpdateSort($this->identifier, $bCtrl); // identifier
			$this->UpdateSort($this->status, $bCtrl); // status
			$this->UpdateSort($this->lft, $bCtrl); // lft
			$this->UpdateSort($this->rgt, $bCtrl); // rgt
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
				$this->name->setSort("");
				$this->homepage->setSort("");
				$this->is_public->setSort("");
				$this->parent_id->setSort("");
				$this->created_on->setSort("");
				$this->updated_on->setSort("");
				$this->identifier->setSort("");
				$this->status->setSort("");
				$this->lft->setSort("");
				$this->rgt->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fvwrdm_projectslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->name->setDbValue($rs->fields('name'));
		$this->description->setDbValue($rs->fields('description'));
		$this->homepage->setDbValue($rs->fields('homepage'));
		$this->is_public->setDbValue($rs->fields('is_public'));
		$this->parent_id->setDbValue($rs->fields('parent_id'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->identifier->setDbValue($rs->fields('identifier'));
		$this->status->setDbValue($rs->fields('status'));
		$this->lft->setDbValue($rs->fields('lft'));
		$this->rgt->setDbValue($rs->fields('rgt'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->name->DbValue = $row['name'];
		$this->description->DbValue = $row['description'];
		$this->homepage->DbValue = $row['homepage'];
		$this->is_public->DbValue = $row['is_public'];
		$this->parent_id->DbValue = $row['parent_id'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
		$this->identifier->DbValue = $row['identifier'];
		$this->status->DbValue = $row['status'];
		$this->lft->DbValue = $row['lft'];
		$this->rgt->DbValue = $row['rgt'];
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
		if ($this->is_public->FormValue == $this->is_public->CurrentValue && is_numeric(ew_StrToFloat($this->is_public->CurrentValue)))
			$this->is_public->CurrentValue = ew_StrToFloat($this->is_public->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// name
		// description
		// homepage
		// is_public
		// parent_id
		// created_on
		// updated_on
		// identifier
		// status
		// lft
		// rgt

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// homepage
			$this->homepage->ViewValue = $this->homepage->CurrentValue;
			$this->homepage->ViewCustomAttributes = "";

			// is_public
			$this->is_public->ViewValue = $this->is_public->CurrentValue;
			$this->is_public->ViewCustomAttributes = "";

			// parent_id
			$this->parent_id->ViewValue = $this->parent_id->CurrentValue;
			$this->parent_id->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
			$this->updated_on->ViewCustomAttributes = "";

			// identifier
			$this->identifier->ViewValue = $this->identifier->CurrentValue;
			$this->identifier->ViewCustomAttributes = "";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->ViewCustomAttributes = "";

			// lft
			$this->lft->ViewValue = $this->lft->CurrentValue;
			$this->lft->ViewCustomAttributes = "";

			// rgt
			$this->rgt->ViewValue = $this->rgt->CurrentValue;
			$this->rgt->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// homepage
			$this->homepage->LinkCustomAttributes = "";
			$this->homepage->HrefValue = "";
			$this->homepage->TooltipValue = "";

			// is_public
			$this->is_public->LinkCustomAttributes = "";
			$this->is_public->HrefValue = "";
			$this->is_public->TooltipValue = "";

			// parent_id
			$this->parent_id->LinkCustomAttributes = "";
			$this->parent_id->HrefValue = "";
			$this->parent_id->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";

			// identifier
			$this->identifier->LinkCustomAttributes = "";
			$this->identifier->HrefValue = "";
			$this->identifier->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// lft
			$this->lft->LinkCustomAttributes = "";
			$this->lft->HrefValue = "";
			$this->lft->TooltipValue = "";

			// rgt
			$this->rgt->LinkCustomAttributes = "";
			$this->rgt->HrefValue = "";
			$this->rgt->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_vwrdm_projects\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_vwrdm_projects',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fvwrdm_projectslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($vwrdm_projects_list)) $vwrdm_projects_list = new cvwrdm_projects_list();

// Page init
$vwrdm_projects_list->Page_Init();

// Page main
$vwrdm_projects_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vwrdm_projects_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($vwrdm_projects->Export == "") { ?>
<script type="text/javascript">

// Page object
var vwrdm_projects_list = new ew_Page("vwrdm_projects_list");
vwrdm_projects_list.PageID = "list"; // Page ID
var EW_PAGE_ID = vwrdm_projects_list.PageID; // For backward compatibility

// Form object
var fvwrdm_projectslist = new ew_Form("fvwrdm_projectslist");
fvwrdm_projectslist.FormKeyCountName = '<?php echo $vwrdm_projects_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvwrdm_projectslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvwrdm_projectslist.ValidateRequired = true;
<?php } else { ?>
fvwrdm_projectslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fvwrdm_projectslistsrch = new ew_Form("fvwrdm_projectslistsrch");

// Init search panel as collapsed
if (fvwrdm_projectslistsrch) fvwrdm_projectslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($vwrdm_projects->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($vwrdm_projects_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $vwrdm_projects_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$vwrdm_projects_list->TotalRecs = $vwrdm_projects->SelectRecordCount();
	} else {
		if ($vwrdm_projects_list->Recordset = $vwrdm_projects_list->LoadRecordset())
			$vwrdm_projects_list->TotalRecs = $vwrdm_projects_list->Recordset->RecordCount();
	}
	$vwrdm_projects_list->StartRec = 1;
	if ($vwrdm_projects_list->DisplayRecs <= 0 || ($vwrdm_projects->Export <> "" && $vwrdm_projects->ExportAll)) // Display all records
		$vwrdm_projects_list->DisplayRecs = $vwrdm_projects_list->TotalRecs;
	if (!($vwrdm_projects->Export <> "" && $vwrdm_projects->ExportAll))
		$vwrdm_projects_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vwrdm_projects_list->Recordset = $vwrdm_projects_list->LoadRecordset($vwrdm_projects_list->StartRec-1, $vwrdm_projects_list->DisplayRecs);
$vwrdm_projects_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($vwrdm_projects->Export == "" && $vwrdm_projects->CurrentAction == "") { ?>
<form name="fvwrdm_projectslistsrch" id="fvwrdm_projectslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fvwrdm_projectslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fvwrdm_projectslistsrch_SearchGroup" href="#fvwrdm_projectslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fvwrdm_projectslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fvwrdm_projectslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="vwrdm_projects">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($vwrdm_projects_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $vwrdm_projects_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($vwrdm_projects_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($vwrdm_projects_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($vwrdm_projects_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $vwrdm_projects_list->ShowPageHeader(); ?>
<?php
$vwrdm_projects_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fvwrdm_projectslist" id="fvwrdm_projectslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="vwrdm_projects">
<div id="gmp_vwrdm_projects" class="ewGridMiddlePanel">
<?php if ($vwrdm_projects_list->TotalRecs > 0) { ?>
<table id="tbl_vwrdm_projectslist" class="ewTable ewTableSeparate">
<?php echo $vwrdm_projects->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$vwrdm_projects_list->RenderListOptions();

// Render list options (header, left)
$vwrdm_projects_list->ListOptions->Render("header", "left");
?>
<?php if ($vwrdm_projects->id->Visible) { // id ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->id) == "") { ?>
		<td><div id="elh_vwrdm_projects_id" class="vwrdm_projects_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->id) ?>',2);"><div id="elh_vwrdm_projects_id" class="vwrdm_projects_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->name->Visible) { // name ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->name) == "") { ?>
		<td><div id="elh_vwrdm_projects_name" class="vwrdm_projects_name"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->name) ?>',2);"><div id="elh_vwrdm_projects_name" class="vwrdm_projects_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->homepage->Visible) { // homepage ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->homepage) == "") { ?>
		<td><div id="elh_vwrdm_projects_homepage" class="vwrdm_projects_homepage"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->homepage->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->homepage) ?>',2);"><div id="elh_vwrdm_projects_homepage" class="vwrdm_projects_homepage">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->homepage->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->homepage->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->homepage->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->is_public->Visible) { // is_public ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->is_public) == "") { ?>
		<td><div id="elh_vwrdm_projects_is_public" class="vwrdm_projects_is_public"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->is_public->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->is_public) ?>',2);"><div id="elh_vwrdm_projects_is_public" class="vwrdm_projects_is_public">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->is_public->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->is_public->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->is_public->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->parent_id->Visible) { // parent_id ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->parent_id) == "") { ?>
		<td><div id="elh_vwrdm_projects_parent_id" class="vwrdm_projects_parent_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->parent_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->parent_id) ?>',2);"><div id="elh_vwrdm_projects_parent_id" class="vwrdm_projects_parent_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->parent_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->parent_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->parent_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->created_on->Visible) { // created_on ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->created_on) == "") { ?>
		<td><div id="elh_vwrdm_projects_created_on" class="vwrdm_projects_created_on"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->created_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->created_on) ?>',2);"><div id="elh_vwrdm_projects_created_on" class="vwrdm_projects_created_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->created_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->created_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->created_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->updated_on->Visible) { // updated_on ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->updated_on) == "") { ?>
		<td><div id="elh_vwrdm_projects_updated_on" class="vwrdm_projects_updated_on"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->updated_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->updated_on) ?>',2);"><div id="elh_vwrdm_projects_updated_on" class="vwrdm_projects_updated_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->updated_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->updated_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->updated_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->identifier->Visible) { // identifier ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->identifier) == "") { ?>
		<td><div id="elh_vwrdm_projects_identifier" class="vwrdm_projects_identifier"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->identifier->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->identifier) ?>',2);"><div id="elh_vwrdm_projects_identifier" class="vwrdm_projects_identifier">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->identifier->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->identifier->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->identifier->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->status->Visible) { // status ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->status) == "") { ?>
		<td><div id="elh_vwrdm_projects_status" class="vwrdm_projects_status"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->status->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->status) ?>',2);"><div id="elh_vwrdm_projects_status" class="vwrdm_projects_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->lft->Visible) { // lft ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->lft) == "") { ?>
		<td><div id="elh_vwrdm_projects_lft" class="vwrdm_projects_lft"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->lft->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->lft) ?>',2);"><div id="elh_vwrdm_projects_lft" class="vwrdm_projects_lft">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->lft->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->lft->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->lft->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_projects->rgt->Visible) { // rgt ?>
	<?php if ($vwrdm_projects->SortUrl($vwrdm_projects->rgt) == "") { ?>
		<td><div id="elh_vwrdm_projects_rgt" class="vwrdm_projects_rgt"><div class="ewTableHeaderCaption"><?php echo $vwrdm_projects->rgt->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_projects->SortUrl($vwrdm_projects->rgt) ?>',2);"><div id="elh_vwrdm_projects_rgt" class="vwrdm_projects_rgt">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_projects->rgt->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_projects->rgt->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_projects->rgt->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vwrdm_projects_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vwrdm_projects->ExportAll && $vwrdm_projects->Export <> "") {
	$vwrdm_projects_list->StopRec = $vwrdm_projects_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vwrdm_projects_list->TotalRecs > $vwrdm_projects_list->StartRec + $vwrdm_projects_list->DisplayRecs - 1)
		$vwrdm_projects_list->StopRec = $vwrdm_projects_list->StartRec + $vwrdm_projects_list->DisplayRecs - 1;
	else
		$vwrdm_projects_list->StopRec = $vwrdm_projects_list->TotalRecs;
}
$vwrdm_projects_list->RecCnt = $vwrdm_projects_list->StartRec - 1;
if ($vwrdm_projects_list->Recordset && !$vwrdm_projects_list->Recordset->EOF) {
	$vwrdm_projects_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $vwrdm_projects_list->StartRec > 1)
		$vwrdm_projects_list->Recordset->Move($vwrdm_projects_list->StartRec - 1);
} elseif (!$vwrdm_projects->AllowAddDeleteRow && $vwrdm_projects_list->StopRec == 0) {
	$vwrdm_projects_list->StopRec = $vwrdm_projects->GridAddRowCount;
}

// Initialize aggregate
$vwrdm_projects->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vwrdm_projects->ResetAttrs();
$vwrdm_projects_list->RenderRow();
while ($vwrdm_projects_list->RecCnt < $vwrdm_projects_list->StopRec) {
	$vwrdm_projects_list->RecCnt++;
	if (intval($vwrdm_projects_list->RecCnt) >= intval($vwrdm_projects_list->StartRec)) {
		$vwrdm_projects_list->RowCnt++;

		// Set up key count
		$vwrdm_projects_list->KeyCount = $vwrdm_projects_list->RowIndex;

		// Init row class and style
		$vwrdm_projects->ResetAttrs();
		$vwrdm_projects->CssClass = "";
		if ($vwrdm_projects->CurrentAction == "gridadd") {
		} else {
			$vwrdm_projects_list->LoadRowValues($vwrdm_projects_list->Recordset); // Load row values
		}
		$vwrdm_projects->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vwrdm_projects->RowAttrs = array_merge($vwrdm_projects->RowAttrs, array('data-rowindex'=>$vwrdm_projects_list->RowCnt, 'id'=>'r' . $vwrdm_projects_list->RowCnt . '_vwrdm_projects', 'data-rowtype'=>$vwrdm_projects->RowType));

		// Render row
		$vwrdm_projects_list->RenderRow();

		// Render list options
		$vwrdm_projects_list->RenderListOptions();
?>
	<tr<?php echo $vwrdm_projects->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vwrdm_projects_list->ListOptions->Render("body", "left", $vwrdm_projects_list->RowCnt);
?>
	<?php if ($vwrdm_projects->id->Visible) { // id ?>
		<td<?php echo $vwrdm_projects->id->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->id->ViewAttributes() ?>>
<?php echo $vwrdm_projects->id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->name->Visible) { // name ?>
		<td<?php echo $vwrdm_projects->name->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->name->ViewAttributes() ?>>
<?php echo $vwrdm_projects->name->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->homepage->Visible) { // homepage ?>
		<td<?php echo $vwrdm_projects->homepage->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->homepage->ViewAttributes() ?>>
<?php echo $vwrdm_projects->homepage->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->is_public->Visible) { // is_public ?>
		<td<?php echo $vwrdm_projects->is_public->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->is_public->ViewAttributes() ?>>
<?php echo $vwrdm_projects->is_public->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->parent_id->Visible) { // parent_id ?>
		<td<?php echo $vwrdm_projects->parent_id->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->parent_id->ViewAttributes() ?>>
<?php echo $vwrdm_projects->parent_id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->created_on->Visible) { // created_on ?>
		<td<?php echo $vwrdm_projects->created_on->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->created_on->ViewAttributes() ?>>
<?php echo $vwrdm_projects->created_on->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->updated_on->Visible) { // updated_on ?>
		<td<?php echo $vwrdm_projects->updated_on->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->updated_on->ViewAttributes() ?>>
<?php echo $vwrdm_projects->updated_on->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->identifier->Visible) { // identifier ?>
		<td<?php echo $vwrdm_projects->identifier->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->identifier->ViewAttributes() ?>>
<?php echo $vwrdm_projects->identifier->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->status->Visible) { // status ?>
		<td<?php echo $vwrdm_projects->status->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->status->ViewAttributes() ?>>
<?php echo $vwrdm_projects->status->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->lft->Visible) { // lft ?>
		<td<?php echo $vwrdm_projects->lft->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->lft->ViewAttributes() ?>>
<?php echo $vwrdm_projects->lft->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_projects->rgt->Visible) { // rgt ?>
		<td<?php echo $vwrdm_projects->rgt->CellAttributes() ?>>
<span<?php echo $vwrdm_projects->rgt->ViewAttributes() ?>>
<?php echo $vwrdm_projects->rgt->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_projects_list->PageObjName . "_row_" . $vwrdm_projects_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$vwrdm_projects_list->ListOptions->Render("body", "right", $vwrdm_projects_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vwrdm_projects->CurrentAction <> "gridadd")
		$vwrdm_projects_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vwrdm_projects->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vwrdm_projects_list->Recordset)
	$vwrdm_projects_list->Recordset->Close();
?>
<?php if ($vwrdm_projects->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($vwrdm_projects->CurrentAction <> "gridadd" && $vwrdm_projects->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($vwrdm_projects_list->Pager)) $vwrdm_projects_list->Pager = new cNumericPager($vwrdm_projects_list->StartRec, $vwrdm_projects_list->DisplayRecs, $vwrdm_projects_list->TotalRecs, $vwrdm_projects_list->RecRange) ?>
<?php if ($vwrdm_projects_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($vwrdm_projects_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_projects_list->PageUrl() ?>start=<?php echo $vwrdm_projects_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_projects_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_projects_list->PageUrl() ?>start=<?php echo $vwrdm_projects_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($vwrdm_projects_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $vwrdm_projects_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_projects_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_projects_list->PageUrl() ?>start=<?php echo $vwrdm_projects_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_projects_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_projects_list->PageUrl() ?>start=<?php echo $vwrdm_projects_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($vwrdm_projects_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vwrdm_projects_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vwrdm_projects_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vwrdm_projects_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($vwrdm_projects_list->SearchWhere == "0=101") { ?>
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
	foreach ($vwrdm_projects_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($vwrdm_projects->Export == "") { ?>
<script type="text/javascript">
fvwrdm_projectslistsrch.Init();
fvwrdm_projectslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$vwrdm_projects_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($vwrdm_projects->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$vwrdm_projects_list->Page_Terminate();
?>
