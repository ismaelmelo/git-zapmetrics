<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "vwrdm_auth_sourcesinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$vwrdm_auth_sources_list = NULL; // Initialize page object first

class cvwrdm_auth_sources_list extends cvwrdm_auth_sources {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{3DD49CE0-D729-4A83-8076-D56A551C92BF}";

	// Table name
	var $TableName = 'vwrdm_auth_sources';

	// Page object name
	var $PageObjName = 'vwrdm_auth_sources_list';

	// Grid form hidden field names
	var $FormName = 'fvwrdm_auth_sourceslist';
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

		// Table object (vwrdm_auth_sources)
		if (!isset($GLOBALS["vwrdm_auth_sources"])) {
			$GLOBALS["vwrdm_auth_sources"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vwrdm_auth_sources"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vwrdm_auth_sourcesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vwrdm_auth_sourcesdelete.php";
		$this->MultiUpdateUrl = "vwrdm_auth_sourcesupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vwrdm_auth_sources', TRUE);

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
		$this->BuildBasicSearchSQL($sWhere, $this->type, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->host, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->account, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->account_password, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->base_dn, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->attr_login, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->attr_firstname, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->attr_lastname, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->attr_mail, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->filter, $Keyword);
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
			$this->UpdateSort($this->type, $bCtrl); // type
			$this->UpdateSort($this->name, $bCtrl); // name
			$this->UpdateSort($this->host, $bCtrl); // host
			$this->UpdateSort($this->port, $bCtrl); // port
			$this->UpdateSort($this->account, $bCtrl); // account
			$this->UpdateSort($this->account_password, $bCtrl); // account_password
			$this->UpdateSort($this->base_dn, $bCtrl); // base_dn
			$this->UpdateSort($this->attr_login, $bCtrl); // attr_login
			$this->UpdateSort($this->attr_firstname, $bCtrl); // attr_firstname
			$this->UpdateSort($this->attr_lastname, $bCtrl); // attr_lastname
			$this->UpdateSort($this->attr_mail, $bCtrl); // attr_mail
			$this->UpdateSort($this->onthefly_register, $bCtrl); // onthefly_register
			$this->UpdateSort($this->tls, $bCtrl); // tls
			$this->UpdateSort($this->filter, $bCtrl); // filter
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
				$this->type->setSort("");
				$this->name->setSort("");
				$this->host->setSort("");
				$this->port->setSort("");
				$this->account->setSort("");
				$this->account_password->setSort("");
				$this->base_dn->setSort("");
				$this->attr_login->setSort("");
				$this->attr_firstname->setSort("");
				$this->attr_lastname->setSort("");
				$this->attr_mail->setSort("");
				$this->onthefly_register->setSort("");
				$this->tls->setSort("");
				$this->filter->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fvwrdm_auth_sourceslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->type->setDbValue($rs->fields('type'));
		$this->name->setDbValue($rs->fields('name'));
		$this->host->setDbValue($rs->fields('host'));
		$this->port->setDbValue($rs->fields('port'));
		$this->account->setDbValue($rs->fields('account'));
		$this->account_password->setDbValue($rs->fields('account_password'));
		$this->base_dn->setDbValue($rs->fields('base_dn'));
		$this->attr_login->setDbValue($rs->fields('attr_login'));
		$this->attr_firstname->setDbValue($rs->fields('attr_firstname'));
		$this->attr_lastname->setDbValue($rs->fields('attr_lastname'));
		$this->attr_mail->setDbValue($rs->fields('attr_mail'));
		$this->onthefly_register->setDbValue($rs->fields('onthefly_register'));
		$this->tls->setDbValue($rs->fields('tls'));
		$this->filter->setDbValue($rs->fields('filter'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->type->DbValue = $row['type'];
		$this->name->DbValue = $row['name'];
		$this->host->DbValue = $row['host'];
		$this->port->DbValue = $row['port'];
		$this->account->DbValue = $row['account'];
		$this->account_password->DbValue = $row['account_password'];
		$this->base_dn->DbValue = $row['base_dn'];
		$this->attr_login->DbValue = $row['attr_login'];
		$this->attr_firstname->DbValue = $row['attr_firstname'];
		$this->attr_lastname->DbValue = $row['attr_lastname'];
		$this->attr_mail->DbValue = $row['attr_mail'];
		$this->onthefly_register->DbValue = $row['onthefly_register'];
		$this->tls->DbValue = $row['tls'];
		$this->filter->DbValue = $row['filter'];
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
		if ($this->onthefly_register->FormValue == $this->onthefly_register->CurrentValue && is_numeric(ew_StrToFloat($this->onthefly_register->CurrentValue)))
			$this->onthefly_register->CurrentValue = ew_StrToFloat($this->onthefly_register->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tls->FormValue == $this->tls->CurrentValue && is_numeric(ew_StrToFloat($this->tls->CurrentValue)))
			$this->tls->CurrentValue = ew_StrToFloat($this->tls->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// type
		// name
		// host
		// port
		// account
		// account_password
		// base_dn
		// attr_login
		// attr_firstname
		// attr_lastname
		// attr_mail
		// onthefly_register
		// tls
		// filter

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// type
			$this->type->ViewValue = $this->type->CurrentValue;
			$this->type->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// host
			$this->host->ViewValue = $this->host->CurrentValue;
			$this->host->ViewCustomAttributes = "";

			// port
			$this->port->ViewValue = $this->port->CurrentValue;
			$this->port->ViewCustomAttributes = "";

			// account
			$this->account->ViewValue = $this->account->CurrentValue;
			$this->account->ViewCustomAttributes = "";

			// account_password
			$this->account_password->ViewValue = $this->account_password->CurrentValue;
			$this->account_password->ViewCustomAttributes = "";

			// base_dn
			$this->base_dn->ViewValue = $this->base_dn->CurrentValue;
			$this->base_dn->ViewCustomAttributes = "";

			// attr_login
			$this->attr_login->ViewValue = $this->attr_login->CurrentValue;
			$this->attr_login->ViewCustomAttributes = "";

			// attr_firstname
			$this->attr_firstname->ViewValue = $this->attr_firstname->CurrentValue;
			$this->attr_firstname->ViewCustomAttributes = "";

			// attr_lastname
			$this->attr_lastname->ViewValue = $this->attr_lastname->CurrentValue;
			$this->attr_lastname->ViewCustomAttributes = "";

			// attr_mail
			$this->attr_mail->ViewValue = $this->attr_mail->CurrentValue;
			$this->attr_mail->ViewCustomAttributes = "";

			// onthefly_register
			$this->onthefly_register->ViewValue = $this->onthefly_register->CurrentValue;
			$this->onthefly_register->ViewCustomAttributes = "";

			// tls
			$this->tls->ViewValue = $this->tls->CurrentValue;
			$this->tls->ViewCustomAttributes = "";

			// filter
			$this->filter->ViewValue = $this->filter->CurrentValue;
			$this->filter->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// host
			$this->host->LinkCustomAttributes = "";
			$this->host->HrefValue = "";
			$this->host->TooltipValue = "";

			// port
			$this->port->LinkCustomAttributes = "";
			$this->port->HrefValue = "";
			$this->port->TooltipValue = "";

			// account
			$this->account->LinkCustomAttributes = "";
			$this->account->HrefValue = "";
			$this->account->TooltipValue = "";

			// account_password
			$this->account_password->LinkCustomAttributes = "";
			$this->account_password->HrefValue = "";
			$this->account_password->TooltipValue = "";

			// base_dn
			$this->base_dn->LinkCustomAttributes = "";
			$this->base_dn->HrefValue = "";
			$this->base_dn->TooltipValue = "";

			// attr_login
			$this->attr_login->LinkCustomAttributes = "";
			$this->attr_login->HrefValue = "";
			$this->attr_login->TooltipValue = "";

			// attr_firstname
			$this->attr_firstname->LinkCustomAttributes = "";
			$this->attr_firstname->HrefValue = "";
			$this->attr_firstname->TooltipValue = "";

			// attr_lastname
			$this->attr_lastname->LinkCustomAttributes = "";
			$this->attr_lastname->HrefValue = "";
			$this->attr_lastname->TooltipValue = "";

			// attr_mail
			$this->attr_mail->LinkCustomAttributes = "";
			$this->attr_mail->HrefValue = "";
			$this->attr_mail->TooltipValue = "";

			// onthefly_register
			$this->onthefly_register->LinkCustomAttributes = "";
			$this->onthefly_register->HrefValue = "";
			$this->onthefly_register->TooltipValue = "";

			// tls
			$this->tls->LinkCustomAttributes = "";
			$this->tls->HrefValue = "";
			$this->tls->TooltipValue = "";

			// filter
			$this->filter->LinkCustomAttributes = "";
			$this->filter->HrefValue = "";
			$this->filter->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_vwrdm_auth_sources\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_vwrdm_auth_sources',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fvwrdm_auth_sourceslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($vwrdm_auth_sources_list)) $vwrdm_auth_sources_list = new cvwrdm_auth_sources_list();

// Page init
$vwrdm_auth_sources_list->Page_Init();

// Page main
$vwrdm_auth_sources_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vwrdm_auth_sources_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($vwrdm_auth_sources->Export == "") { ?>
<script type="text/javascript">

// Page object
var vwrdm_auth_sources_list = new ew_Page("vwrdm_auth_sources_list");
vwrdm_auth_sources_list.PageID = "list"; // Page ID
var EW_PAGE_ID = vwrdm_auth_sources_list.PageID; // For backward compatibility

// Form object
var fvwrdm_auth_sourceslist = new ew_Form("fvwrdm_auth_sourceslist");
fvwrdm_auth_sourceslist.FormKeyCountName = '<?php echo $vwrdm_auth_sources_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvwrdm_auth_sourceslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvwrdm_auth_sourceslist.ValidateRequired = true;
<?php } else { ?>
fvwrdm_auth_sourceslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fvwrdm_auth_sourceslistsrch = new ew_Form("fvwrdm_auth_sourceslistsrch");

// Init search panel as collapsed
if (fvwrdm_auth_sourceslistsrch) fvwrdm_auth_sourceslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($vwrdm_auth_sources->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($vwrdm_auth_sources_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $vwrdm_auth_sources_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$vwrdm_auth_sources_list->TotalRecs = $vwrdm_auth_sources->SelectRecordCount();
	} else {
		if ($vwrdm_auth_sources_list->Recordset = $vwrdm_auth_sources_list->LoadRecordset())
			$vwrdm_auth_sources_list->TotalRecs = $vwrdm_auth_sources_list->Recordset->RecordCount();
	}
	$vwrdm_auth_sources_list->StartRec = 1;
	if ($vwrdm_auth_sources_list->DisplayRecs <= 0 || ($vwrdm_auth_sources->Export <> "" && $vwrdm_auth_sources->ExportAll)) // Display all records
		$vwrdm_auth_sources_list->DisplayRecs = $vwrdm_auth_sources_list->TotalRecs;
	if (!($vwrdm_auth_sources->Export <> "" && $vwrdm_auth_sources->ExportAll))
		$vwrdm_auth_sources_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vwrdm_auth_sources_list->Recordset = $vwrdm_auth_sources_list->LoadRecordset($vwrdm_auth_sources_list->StartRec-1, $vwrdm_auth_sources_list->DisplayRecs);
$vwrdm_auth_sources_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($vwrdm_auth_sources->Export == "" && $vwrdm_auth_sources->CurrentAction == "") { ?>
<form name="fvwrdm_auth_sourceslistsrch" id="fvwrdm_auth_sourceslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fvwrdm_auth_sourceslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fvwrdm_auth_sourceslistsrch_SearchGroup" href="#fvwrdm_auth_sourceslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fvwrdm_auth_sourceslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fvwrdm_auth_sourceslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="vwrdm_auth_sources">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($vwrdm_auth_sources_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $vwrdm_auth_sources_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($vwrdm_auth_sources_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($vwrdm_auth_sources_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($vwrdm_auth_sources_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $vwrdm_auth_sources_list->ShowPageHeader(); ?>
<?php
$vwrdm_auth_sources_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fvwrdm_auth_sourceslist" id="fvwrdm_auth_sourceslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="vwrdm_auth_sources">
<div id="gmp_vwrdm_auth_sources" class="ewGridMiddlePanel">
<?php if ($vwrdm_auth_sources_list->TotalRecs > 0) { ?>
<table id="tbl_vwrdm_auth_sourceslist" class="ewTable ewTableSeparate">
<?php echo $vwrdm_auth_sources->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$vwrdm_auth_sources_list->RenderListOptions();

// Render list options (header, left)
$vwrdm_auth_sources_list->ListOptions->Render("header", "left");
?>
<?php if ($vwrdm_auth_sources->id->Visible) { // id ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->id) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_id" class="vwrdm_auth_sources_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->id) ?>',2);"><div id="elh_vwrdm_auth_sources_id" class="vwrdm_auth_sources_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->type->Visible) { // type ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->type) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_type" class="vwrdm_auth_sources_type"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->type->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->type) ?>',2);"><div id="elh_vwrdm_auth_sources_type" class="vwrdm_auth_sources_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->type->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->name->Visible) { // name ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->name) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_name" class="vwrdm_auth_sources_name"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->name) ?>',2);"><div id="elh_vwrdm_auth_sources_name" class="vwrdm_auth_sources_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->host->Visible) { // host ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->host) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_host" class="vwrdm_auth_sources_host"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->host->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->host) ?>',2);"><div id="elh_vwrdm_auth_sources_host" class="vwrdm_auth_sources_host">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->host->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->host->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->host->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->port->Visible) { // port ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->port) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_port" class="vwrdm_auth_sources_port"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->port->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->port) ?>',2);"><div id="elh_vwrdm_auth_sources_port" class="vwrdm_auth_sources_port">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->port->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->port->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->port->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->account->Visible) { // account ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->account) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_account" class="vwrdm_auth_sources_account"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->account->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->account) ?>',2);"><div id="elh_vwrdm_auth_sources_account" class="vwrdm_auth_sources_account">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->account->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->account->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->account->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->account_password->Visible) { // account_password ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->account_password) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_account_password" class="vwrdm_auth_sources_account_password"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->account_password->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->account_password) ?>',2);"><div id="elh_vwrdm_auth_sources_account_password" class="vwrdm_auth_sources_account_password">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->account_password->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->account_password->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->account_password->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->base_dn->Visible) { // base_dn ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->base_dn) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_base_dn" class="vwrdm_auth_sources_base_dn"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->base_dn->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->base_dn) ?>',2);"><div id="elh_vwrdm_auth_sources_base_dn" class="vwrdm_auth_sources_base_dn">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->base_dn->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->base_dn->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->base_dn->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->attr_login->Visible) { // attr_login ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->attr_login) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_attr_login" class="vwrdm_auth_sources_attr_login"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->attr_login->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->attr_login) ?>',2);"><div id="elh_vwrdm_auth_sources_attr_login" class="vwrdm_auth_sources_attr_login">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->attr_login->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->attr_login->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->attr_login->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->attr_firstname->Visible) { // attr_firstname ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->attr_firstname) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_attr_firstname" class="vwrdm_auth_sources_attr_firstname"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->attr_firstname->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->attr_firstname) ?>',2);"><div id="elh_vwrdm_auth_sources_attr_firstname" class="vwrdm_auth_sources_attr_firstname">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->attr_firstname->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->attr_firstname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->attr_firstname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->attr_lastname->Visible) { // attr_lastname ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->attr_lastname) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_attr_lastname" class="vwrdm_auth_sources_attr_lastname"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->attr_lastname->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->attr_lastname) ?>',2);"><div id="elh_vwrdm_auth_sources_attr_lastname" class="vwrdm_auth_sources_attr_lastname">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->attr_lastname->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->attr_lastname->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->attr_lastname->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->attr_mail->Visible) { // attr_mail ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->attr_mail) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_attr_mail" class="vwrdm_auth_sources_attr_mail"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->attr_mail->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->attr_mail) ?>',2);"><div id="elh_vwrdm_auth_sources_attr_mail" class="vwrdm_auth_sources_attr_mail">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->attr_mail->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->attr_mail->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->attr_mail->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->onthefly_register->Visible) { // onthefly_register ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->onthefly_register) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_onthefly_register" class="vwrdm_auth_sources_onthefly_register"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->onthefly_register->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->onthefly_register) ?>',2);"><div id="elh_vwrdm_auth_sources_onthefly_register" class="vwrdm_auth_sources_onthefly_register">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->onthefly_register->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->onthefly_register->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->onthefly_register->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->tls->Visible) { // tls ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->tls) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_tls" class="vwrdm_auth_sources_tls"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->tls->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->tls) ?>',2);"><div id="elh_vwrdm_auth_sources_tls" class="vwrdm_auth_sources_tls">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->tls->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->tls->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->tls->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_auth_sources->filter->Visible) { // filter ?>
	<?php if ($vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->filter) == "") { ?>
		<td><div id="elh_vwrdm_auth_sources_filter" class="vwrdm_auth_sources_filter"><div class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->filter->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_auth_sources->SortUrl($vwrdm_auth_sources->filter) ?>',2);"><div id="elh_vwrdm_auth_sources_filter" class="vwrdm_auth_sources_filter">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_auth_sources->filter->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_auth_sources->filter->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_auth_sources->filter->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vwrdm_auth_sources_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vwrdm_auth_sources->ExportAll && $vwrdm_auth_sources->Export <> "") {
	$vwrdm_auth_sources_list->StopRec = $vwrdm_auth_sources_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vwrdm_auth_sources_list->TotalRecs > $vwrdm_auth_sources_list->StartRec + $vwrdm_auth_sources_list->DisplayRecs - 1)
		$vwrdm_auth_sources_list->StopRec = $vwrdm_auth_sources_list->StartRec + $vwrdm_auth_sources_list->DisplayRecs - 1;
	else
		$vwrdm_auth_sources_list->StopRec = $vwrdm_auth_sources_list->TotalRecs;
}
$vwrdm_auth_sources_list->RecCnt = $vwrdm_auth_sources_list->StartRec - 1;
if ($vwrdm_auth_sources_list->Recordset && !$vwrdm_auth_sources_list->Recordset->EOF) {
	$vwrdm_auth_sources_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $vwrdm_auth_sources_list->StartRec > 1)
		$vwrdm_auth_sources_list->Recordset->Move($vwrdm_auth_sources_list->StartRec - 1);
} elseif (!$vwrdm_auth_sources->AllowAddDeleteRow && $vwrdm_auth_sources_list->StopRec == 0) {
	$vwrdm_auth_sources_list->StopRec = $vwrdm_auth_sources->GridAddRowCount;
}

// Initialize aggregate
$vwrdm_auth_sources->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vwrdm_auth_sources->ResetAttrs();
$vwrdm_auth_sources_list->RenderRow();
while ($vwrdm_auth_sources_list->RecCnt < $vwrdm_auth_sources_list->StopRec) {
	$vwrdm_auth_sources_list->RecCnt++;
	if (intval($vwrdm_auth_sources_list->RecCnt) >= intval($vwrdm_auth_sources_list->StartRec)) {
		$vwrdm_auth_sources_list->RowCnt++;

		// Set up key count
		$vwrdm_auth_sources_list->KeyCount = $vwrdm_auth_sources_list->RowIndex;

		// Init row class and style
		$vwrdm_auth_sources->ResetAttrs();
		$vwrdm_auth_sources->CssClass = "";
		if ($vwrdm_auth_sources->CurrentAction == "gridadd") {
		} else {
			$vwrdm_auth_sources_list->LoadRowValues($vwrdm_auth_sources_list->Recordset); // Load row values
		}
		$vwrdm_auth_sources->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vwrdm_auth_sources->RowAttrs = array_merge($vwrdm_auth_sources->RowAttrs, array('data-rowindex'=>$vwrdm_auth_sources_list->RowCnt, 'id'=>'r' . $vwrdm_auth_sources_list->RowCnt . '_vwrdm_auth_sources', 'data-rowtype'=>$vwrdm_auth_sources->RowType));

		// Render row
		$vwrdm_auth_sources_list->RenderRow();

		// Render list options
		$vwrdm_auth_sources_list->RenderListOptions();
?>
	<tr<?php echo $vwrdm_auth_sources->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vwrdm_auth_sources_list->ListOptions->Render("body", "left", $vwrdm_auth_sources_list->RowCnt);
?>
	<?php if ($vwrdm_auth_sources->id->Visible) { // id ?>
		<td<?php echo $vwrdm_auth_sources->id->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->id->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->type->Visible) { // type ?>
		<td<?php echo $vwrdm_auth_sources->type->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->type->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->type->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->name->Visible) { // name ?>
		<td<?php echo $vwrdm_auth_sources->name->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->name->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->name->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->host->Visible) { // host ?>
		<td<?php echo $vwrdm_auth_sources->host->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->host->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->host->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->port->Visible) { // port ?>
		<td<?php echo $vwrdm_auth_sources->port->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->port->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->port->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->account->Visible) { // account ?>
		<td<?php echo $vwrdm_auth_sources->account->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->account->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->account->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->account_password->Visible) { // account_password ?>
		<td<?php echo $vwrdm_auth_sources->account_password->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->account_password->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->account_password->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->base_dn->Visible) { // base_dn ?>
		<td<?php echo $vwrdm_auth_sources->base_dn->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->base_dn->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->base_dn->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->attr_login->Visible) { // attr_login ?>
		<td<?php echo $vwrdm_auth_sources->attr_login->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->attr_login->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->attr_login->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->attr_firstname->Visible) { // attr_firstname ?>
		<td<?php echo $vwrdm_auth_sources->attr_firstname->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->attr_firstname->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->attr_firstname->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->attr_lastname->Visible) { // attr_lastname ?>
		<td<?php echo $vwrdm_auth_sources->attr_lastname->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->attr_lastname->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->attr_lastname->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->attr_mail->Visible) { // attr_mail ?>
		<td<?php echo $vwrdm_auth_sources->attr_mail->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->attr_mail->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->attr_mail->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->onthefly_register->Visible) { // onthefly_register ?>
		<td<?php echo $vwrdm_auth_sources->onthefly_register->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->onthefly_register->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->onthefly_register->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->tls->Visible) { // tls ?>
		<td<?php echo $vwrdm_auth_sources->tls->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->tls->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->tls->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_auth_sources->filter->Visible) { // filter ?>
		<td<?php echo $vwrdm_auth_sources->filter->CellAttributes() ?>>
<span<?php echo $vwrdm_auth_sources->filter->ViewAttributes() ?>>
<?php echo $vwrdm_auth_sources->filter->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_auth_sources_list->PageObjName . "_row_" . $vwrdm_auth_sources_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$vwrdm_auth_sources_list->ListOptions->Render("body", "right", $vwrdm_auth_sources_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vwrdm_auth_sources->CurrentAction <> "gridadd")
		$vwrdm_auth_sources_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vwrdm_auth_sources->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vwrdm_auth_sources_list->Recordset)
	$vwrdm_auth_sources_list->Recordset->Close();
?>
<?php if ($vwrdm_auth_sources->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($vwrdm_auth_sources->CurrentAction <> "gridadd" && $vwrdm_auth_sources->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($vwrdm_auth_sources_list->Pager)) $vwrdm_auth_sources_list->Pager = new cNumericPager($vwrdm_auth_sources_list->StartRec, $vwrdm_auth_sources_list->DisplayRecs, $vwrdm_auth_sources_list->TotalRecs, $vwrdm_auth_sources_list->RecRange) ?>
<?php if ($vwrdm_auth_sources_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($vwrdm_auth_sources_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_auth_sources_list->PageUrl() ?>start=<?php echo $vwrdm_auth_sources_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_auth_sources_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_auth_sources_list->PageUrl() ?>start=<?php echo $vwrdm_auth_sources_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($vwrdm_auth_sources_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $vwrdm_auth_sources_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_auth_sources_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_auth_sources_list->PageUrl() ?>start=<?php echo $vwrdm_auth_sources_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_auth_sources_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_auth_sources_list->PageUrl() ?>start=<?php echo $vwrdm_auth_sources_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($vwrdm_auth_sources_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vwrdm_auth_sources_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vwrdm_auth_sources_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vwrdm_auth_sources_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($vwrdm_auth_sources_list->SearchWhere == "0=101") { ?>
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
	foreach ($vwrdm_auth_sources_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($vwrdm_auth_sources->Export == "") { ?>
<script type="text/javascript">
fvwrdm_auth_sourceslistsrch.Init();
fvwrdm_auth_sourceslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$vwrdm_auth_sources_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($vwrdm_auth_sources->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$vwrdm_auth_sources_list->Page_Terminate();
?>
