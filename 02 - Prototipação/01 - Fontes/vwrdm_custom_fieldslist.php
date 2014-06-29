<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "vwrdm_custom_fieldsinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$vwrdm_custom_fields_list = NULL; // Initialize page object first

class cvwrdm_custom_fields_list extends cvwrdm_custom_fields {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{3DD49CE0-D729-4A83-8076-D56A551C92BF}";

	// Table name
	var $TableName = 'vwrdm_custom_fields';

	// Page object name
	var $PageObjName = 'vwrdm_custom_fields_list';

	// Grid form hidden field names
	var $FormName = 'fvwrdm_custom_fieldslist';
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

		// Table object (vwrdm_custom_fields)
		if (!isset($GLOBALS["vwrdm_custom_fields"])) {
			$GLOBALS["vwrdm_custom_fields"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vwrdm_custom_fields"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vwrdm_custom_fieldsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vwrdm_custom_fieldsdelete.php";
		$this->MultiUpdateUrl = "vwrdm_custom_fieldsupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vwrdm_custom_fields', TRUE);

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
		$this->BuildBasicSearchSQL($sWhere, $this->field_format, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->possible_values, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->regexp, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->default_value, $Keyword);
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
			$this->UpdateSort($this->field_format, $bCtrl); // field_format
			$this->UpdateSort($this->regexp, $bCtrl); // regexp
			$this->UpdateSort($this->min_length, $bCtrl); // min_length
			$this->UpdateSort($this->max_length, $bCtrl); // max_length
			$this->UpdateSort($this->is_required, $bCtrl); // is_required
			$this->UpdateSort($this->is_for_all, $bCtrl); // is_for_all
			$this->UpdateSort($this->is_filter, $bCtrl); // is_filter
			$this->UpdateSort($this->position, $bCtrl); // position
			$this->UpdateSort($this->searchable, $bCtrl); // searchable
			$this->UpdateSort($this->editable, $bCtrl); // editable
			$this->UpdateSort($this->visible, $bCtrl); // visible
			$this->UpdateSort($this->multiple, $bCtrl); // multiple
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
				$this->field_format->setSort("");
				$this->regexp->setSort("");
				$this->min_length->setSort("");
				$this->max_length->setSort("");
				$this->is_required->setSort("");
				$this->is_for_all->setSort("");
				$this->is_filter->setSort("");
				$this->position->setSort("");
				$this->searchable->setSort("");
				$this->editable->setSort("");
				$this->visible->setSort("");
				$this->multiple->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fvwrdm_custom_fieldslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->field_format->setDbValue($rs->fields('field_format'));
		$this->possible_values->setDbValue($rs->fields('possible_values'));
		$this->regexp->setDbValue($rs->fields('regexp'));
		$this->min_length->setDbValue($rs->fields('min_length'));
		$this->max_length->setDbValue($rs->fields('max_length'));
		$this->is_required->setDbValue($rs->fields('is_required'));
		$this->is_for_all->setDbValue($rs->fields('is_for_all'));
		$this->is_filter->setDbValue($rs->fields('is_filter'));
		$this->position->setDbValue($rs->fields('position'));
		$this->searchable->setDbValue($rs->fields('searchable'));
		$this->default_value->setDbValue($rs->fields('default_value'));
		$this->editable->setDbValue($rs->fields('editable'));
		$this->visible->setDbValue($rs->fields('visible'));
		$this->multiple->setDbValue($rs->fields('multiple'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->type->DbValue = $row['type'];
		$this->name->DbValue = $row['name'];
		$this->field_format->DbValue = $row['field_format'];
		$this->possible_values->DbValue = $row['possible_values'];
		$this->regexp->DbValue = $row['regexp'];
		$this->min_length->DbValue = $row['min_length'];
		$this->max_length->DbValue = $row['max_length'];
		$this->is_required->DbValue = $row['is_required'];
		$this->is_for_all->DbValue = $row['is_for_all'];
		$this->is_filter->DbValue = $row['is_filter'];
		$this->position->DbValue = $row['position'];
		$this->searchable->DbValue = $row['searchable'];
		$this->default_value->DbValue = $row['default_value'];
		$this->editable->DbValue = $row['editable'];
		$this->visible->DbValue = $row['visible'];
		$this->multiple->DbValue = $row['multiple'];
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
		if ($this->is_required->FormValue == $this->is_required->CurrentValue && is_numeric(ew_StrToFloat($this->is_required->CurrentValue)))
			$this->is_required->CurrentValue = ew_StrToFloat($this->is_required->CurrentValue);

		// Convert decimal values if posted back
		if ($this->is_for_all->FormValue == $this->is_for_all->CurrentValue && is_numeric(ew_StrToFloat($this->is_for_all->CurrentValue)))
			$this->is_for_all->CurrentValue = ew_StrToFloat($this->is_for_all->CurrentValue);

		// Convert decimal values if posted back
		if ($this->is_filter->FormValue == $this->is_filter->CurrentValue && is_numeric(ew_StrToFloat($this->is_filter->CurrentValue)))
			$this->is_filter->CurrentValue = ew_StrToFloat($this->is_filter->CurrentValue);

		// Convert decimal values if posted back
		if ($this->searchable->FormValue == $this->searchable->CurrentValue && is_numeric(ew_StrToFloat($this->searchable->CurrentValue)))
			$this->searchable->CurrentValue = ew_StrToFloat($this->searchable->CurrentValue);

		// Convert decimal values if posted back
		if ($this->editable->FormValue == $this->editable->CurrentValue && is_numeric(ew_StrToFloat($this->editable->CurrentValue)))
			$this->editable->CurrentValue = ew_StrToFloat($this->editable->CurrentValue);

		// Convert decimal values if posted back
		if ($this->visible->FormValue == $this->visible->CurrentValue && is_numeric(ew_StrToFloat($this->visible->CurrentValue)))
			$this->visible->CurrentValue = ew_StrToFloat($this->visible->CurrentValue);

		// Convert decimal values if posted back
		if ($this->multiple->FormValue == $this->multiple->CurrentValue && is_numeric(ew_StrToFloat($this->multiple->CurrentValue)))
			$this->multiple->CurrentValue = ew_StrToFloat($this->multiple->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// type
		// name
		// field_format
		// possible_values
		// regexp
		// min_length
		// max_length
		// is_required
		// is_for_all
		// is_filter
		// position
		// searchable
		// default_value
		// editable
		// visible
		// multiple

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

			// field_format
			$this->field_format->ViewValue = $this->field_format->CurrentValue;
			$this->field_format->ViewCustomAttributes = "";

			// regexp
			$this->regexp->ViewValue = $this->regexp->CurrentValue;
			$this->regexp->ViewCustomAttributes = "";

			// min_length
			$this->min_length->ViewValue = $this->min_length->CurrentValue;
			$this->min_length->ViewCustomAttributes = "";

			// max_length
			$this->max_length->ViewValue = $this->max_length->CurrentValue;
			$this->max_length->ViewCustomAttributes = "";

			// is_required
			$this->is_required->ViewValue = $this->is_required->CurrentValue;
			$this->is_required->ViewCustomAttributes = "";

			// is_for_all
			$this->is_for_all->ViewValue = $this->is_for_all->CurrentValue;
			$this->is_for_all->ViewCustomAttributes = "";

			// is_filter
			$this->is_filter->ViewValue = $this->is_filter->CurrentValue;
			$this->is_filter->ViewCustomAttributes = "";

			// position
			$this->position->ViewValue = $this->position->CurrentValue;
			$this->position->ViewCustomAttributes = "";

			// searchable
			$this->searchable->ViewValue = $this->searchable->CurrentValue;
			$this->searchable->ViewCustomAttributes = "";

			// editable
			$this->editable->ViewValue = $this->editable->CurrentValue;
			$this->editable->ViewCustomAttributes = "";

			// visible
			$this->visible->ViewValue = $this->visible->CurrentValue;
			$this->visible->ViewCustomAttributes = "";

			// multiple
			$this->multiple->ViewValue = $this->multiple->CurrentValue;
			$this->multiple->ViewCustomAttributes = "";

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

			// field_format
			$this->field_format->LinkCustomAttributes = "";
			$this->field_format->HrefValue = "";
			$this->field_format->TooltipValue = "";

			// regexp
			$this->regexp->LinkCustomAttributes = "";
			$this->regexp->HrefValue = "";
			$this->regexp->TooltipValue = "";

			// min_length
			$this->min_length->LinkCustomAttributes = "";
			$this->min_length->HrefValue = "";
			$this->min_length->TooltipValue = "";

			// max_length
			$this->max_length->LinkCustomAttributes = "";
			$this->max_length->HrefValue = "";
			$this->max_length->TooltipValue = "";

			// is_required
			$this->is_required->LinkCustomAttributes = "";
			$this->is_required->HrefValue = "";
			$this->is_required->TooltipValue = "";

			// is_for_all
			$this->is_for_all->LinkCustomAttributes = "";
			$this->is_for_all->HrefValue = "";
			$this->is_for_all->TooltipValue = "";

			// is_filter
			$this->is_filter->LinkCustomAttributes = "";
			$this->is_filter->HrefValue = "";
			$this->is_filter->TooltipValue = "";

			// position
			$this->position->LinkCustomAttributes = "";
			$this->position->HrefValue = "";
			$this->position->TooltipValue = "";

			// searchable
			$this->searchable->LinkCustomAttributes = "";
			$this->searchable->HrefValue = "";
			$this->searchable->TooltipValue = "";

			// editable
			$this->editable->LinkCustomAttributes = "";
			$this->editable->HrefValue = "";
			$this->editable->TooltipValue = "";

			// visible
			$this->visible->LinkCustomAttributes = "";
			$this->visible->HrefValue = "";
			$this->visible->TooltipValue = "";

			// multiple
			$this->multiple->LinkCustomAttributes = "";
			$this->multiple->HrefValue = "";
			$this->multiple->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_vwrdm_custom_fields\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_vwrdm_custom_fields',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fvwrdm_custom_fieldslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($vwrdm_custom_fields_list)) $vwrdm_custom_fields_list = new cvwrdm_custom_fields_list();

// Page init
$vwrdm_custom_fields_list->Page_Init();

// Page main
$vwrdm_custom_fields_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vwrdm_custom_fields_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($vwrdm_custom_fields->Export == "") { ?>
<script type="text/javascript">

// Page object
var vwrdm_custom_fields_list = new ew_Page("vwrdm_custom_fields_list");
vwrdm_custom_fields_list.PageID = "list"; // Page ID
var EW_PAGE_ID = vwrdm_custom_fields_list.PageID; // For backward compatibility

// Form object
var fvwrdm_custom_fieldslist = new ew_Form("fvwrdm_custom_fieldslist");
fvwrdm_custom_fieldslist.FormKeyCountName = '<?php echo $vwrdm_custom_fields_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvwrdm_custom_fieldslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvwrdm_custom_fieldslist.ValidateRequired = true;
<?php } else { ?>
fvwrdm_custom_fieldslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fvwrdm_custom_fieldslistsrch = new ew_Form("fvwrdm_custom_fieldslistsrch");

// Init search panel as collapsed
if (fvwrdm_custom_fieldslistsrch) fvwrdm_custom_fieldslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($vwrdm_custom_fields->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($vwrdm_custom_fields_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $vwrdm_custom_fields_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$vwrdm_custom_fields_list->TotalRecs = $vwrdm_custom_fields->SelectRecordCount();
	} else {
		if ($vwrdm_custom_fields_list->Recordset = $vwrdm_custom_fields_list->LoadRecordset())
			$vwrdm_custom_fields_list->TotalRecs = $vwrdm_custom_fields_list->Recordset->RecordCount();
	}
	$vwrdm_custom_fields_list->StartRec = 1;
	if ($vwrdm_custom_fields_list->DisplayRecs <= 0 || ($vwrdm_custom_fields->Export <> "" && $vwrdm_custom_fields->ExportAll)) // Display all records
		$vwrdm_custom_fields_list->DisplayRecs = $vwrdm_custom_fields_list->TotalRecs;
	if (!($vwrdm_custom_fields->Export <> "" && $vwrdm_custom_fields->ExportAll))
		$vwrdm_custom_fields_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vwrdm_custom_fields_list->Recordset = $vwrdm_custom_fields_list->LoadRecordset($vwrdm_custom_fields_list->StartRec-1, $vwrdm_custom_fields_list->DisplayRecs);
$vwrdm_custom_fields_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($vwrdm_custom_fields->Export == "" && $vwrdm_custom_fields->CurrentAction == "") { ?>
<form name="fvwrdm_custom_fieldslistsrch" id="fvwrdm_custom_fieldslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fvwrdm_custom_fieldslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fvwrdm_custom_fieldslistsrch_SearchGroup" href="#fvwrdm_custom_fieldslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fvwrdm_custom_fieldslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fvwrdm_custom_fieldslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="vwrdm_custom_fields">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($vwrdm_custom_fields_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $vwrdm_custom_fields_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($vwrdm_custom_fields_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($vwrdm_custom_fields_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($vwrdm_custom_fields_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $vwrdm_custom_fields_list->ShowPageHeader(); ?>
<?php
$vwrdm_custom_fields_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fvwrdm_custom_fieldslist" id="fvwrdm_custom_fieldslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="vwrdm_custom_fields">
<div id="gmp_vwrdm_custom_fields" class="ewGridMiddlePanel">
<?php if ($vwrdm_custom_fields_list->TotalRecs > 0) { ?>
<table id="tbl_vwrdm_custom_fieldslist" class="ewTable ewTableSeparate">
<?php echo $vwrdm_custom_fields->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$vwrdm_custom_fields_list->RenderListOptions();

// Render list options (header, left)
$vwrdm_custom_fields_list->ListOptions->Render("header", "left");
?>
<?php if ($vwrdm_custom_fields->id->Visible) { // id ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->id) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_id" class="vwrdm_custom_fields_id"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->id) ?>',2);"><div id="elh_vwrdm_custom_fields_id" class="vwrdm_custom_fields_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->type->Visible) { // type ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->type) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_type" class="vwrdm_custom_fields_type"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->type->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->type) ?>',2);"><div id="elh_vwrdm_custom_fields_type" class="vwrdm_custom_fields_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->type->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->name->Visible) { // name ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->name) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_name" class="vwrdm_custom_fields_name"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->name) ?>',2);"><div id="elh_vwrdm_custom_fields_name" class="vwrdm_custom_fields_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->field_format->Visible) { // field_format ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->field_format) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_field_format" class="vwrdm_custom_fields_field_format"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->field_format->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->field_format) ?>',2);"><div id="elh_vwrdm_custom_fields_field_format" class="vwrdm_custom_fields_field_format">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->field_format->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->field_format->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->field_format->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->regexp->Visible) { // regexp ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->regexp) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_regexp" class="vwrdm_custom_fields_regexp"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->regexp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->regexp) ?>',2);"><div id="elh_vwrdm_custom_fields_regexp" class="vwrdm_custom_fields_regexp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->regexp->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->regexp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->regexp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->min_length->Visible) { // min_length ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->min_length) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_min_length" class="vwrdm_custom_fields_min_length"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->min_length->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->min_length) ?>',2);"><div id="elh_vwrdm_custom_fields_min_length" class="vwrdm_custom_fields_min_length">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->min_length->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->min_length->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->min_length->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->max_length->Visible) { // max_length ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->max_length) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_max_length" class="vwrdm_custom_fields_max_length"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->max_length->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->max_length) ?>',2);"><div id="elh_vwrdm_custom_fields_max_length" class="vwrdm_custom_fields_max_length">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->max_length->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->max_length->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->max_length->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->is_required->Visible) { // is_required ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->is_required) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_is_required" class="vwrdm_custom_fields_is_required"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->is_required->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->is_required) ?>',2);"><div id="elh_vwrdm_custom_fields_is_required" class="vwrdm_custom_fields_is_required">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->is_required->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->is_required->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->is_required->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->is_for_all->Visible) { // is_for_all ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->is_for_all) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_is_for_all" class="vwrdm_custom_fields_is_for_all"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->is_for_all->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->is_for_all) ?>',2);"><div id="elh_vwrdm_custom_fields_is_for_all" class="vwrdm_custom_fields_is_for_all">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->is_for_all->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->is_for_all->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->is_for_all->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->is_filter->Visible) { // is_filter ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->is_filter) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_is_filter" class="vwrdm_custom_fields_is_filter"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->is_filter->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->is_filter) ?>',2);"><div id="elh_vwrdm_custom_fields_is_filter" class="vwrdm_custom_fields_is_filter">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->is_filter->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->is_filter->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->is_filter->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->position->Visible) { // position ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->position) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_position" class="vwrdm_custom_fields_position"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->position->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->position) ?>',2);"><div id="elh_vwrdm_custom_fields_position" class="vwrdm_custom_fields_position">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->position->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->position->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->position->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->searchable->Visible) { // searchable ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->searchable) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_searchable" class="vwrdm_custom_fields_searchable"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->searchable->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->searchable) ?>',2);"><div id="elh_vwrdm_custom_fields_searchable" class="vwrdm_custom_fields_searchable">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->searchable->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->searchable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->searchable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->editable->Visible) { // editable ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->editable) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_editable" class="vwrdm_custom_fields_editable"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->editable->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->editable) ?>',2);"><div id="elh_vwrdm_custom_fields_editable" class="vwrdm_custom_fields_editable">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->editable->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->editable->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->editable->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->visible->Visible) { // visible ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->visible) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_visible" class="vwrdm_custom_fields_visible"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->visible->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->visible) ?>',2);"><div id="elh_vwrdm_custom_fields_visible" class="vwrdm_custom_fields_visible">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->visible->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->visible->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->visible->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdm_custom_fields->multiple->Visible) { // multiple ?>
	<?php if ($vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->multiple) == "") { ?>
		<td><div id="elh_vwrdm_custom_fields_multiple" class="vwrdm_custom_fields_multiple"><div class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->multiple->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdm_custom_fields->SortUrl($vwrdm_custom_fields->multiple) ?>',2);"><div id="elh_vwrdm_custom_fields_multiple" class="vwrdm_custom_fields_multiple">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdm_custom_fields->multiple->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdm_custom_fields->multiple->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdm_custom_fields->multiple->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vwrdm_custom_fields_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vwrdm_custom_fields->ExportAll && $vwrdm_custom_fields->Export <> "") {
	$vwrdm_custom_fields_list->StopRec = $vwrdm_custom_fields_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vwrdm_custom_fields_list->TotalRecs > $vwrdm_custom_fields_list->StartRec + $vwrdm_custom_fields_list->DisplayRecs - 1)
		$vwrdm_custom_fields_list->StopRec = $vwrdm_custom_fields_list->StartRec + $vwrdm_custom_fields_list->DisplayRecs - 1;
	else
		$vwrdm_custom_fields_list->StopRec = $vwrdm_custom_fields_list->TotalRecs;
}
$vwrdm_custom_fields_list->RecCnt = $vwrdm_custom_fields_list->StartRec - 1;
if ($vwrdm_custom_fields_list->Recordset && !$vwrdm_custom_fields_list->Recordset->EOF) {
	$vwrdm_custom_fields_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $vwrdm_custom_fields_list->StartRec > 1)
		$vwrdm_custom_fields_list->Recordset->Move($vwrdm_custom_fields_list->StartRec - 1);
} elseif (!$vwrdm_custom_fields->AllowAddDeleteRow && $vwrdm_custom_fields_list->StopRec == 0) {
	$vwrdm_custom_fields_list->StopRec = $vwrdm_custom_fields->GridAddRowCount;
}

// Initialize aggregate
$vwrdm_custom_fields->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vwrdm_custom_fields->ResetAttrs();
$vwrdm_custom_fields_list->RenderRow();
while ($vwrdm_custom_fields_list->RecCnt < $vwrdm_custom_fields_list->StopRec) {
	$vwrdm_custom_fields_list->RecCnt++;
	if (intval($vwrdm_custom_fields_list->RecCnt) >= intval($vwrdm_custom_fields_list->StartRec)) {
		$vwrdm_custom_fields_list->RowCnt++;

		// Set up key count
		$vwrdm_custom_fields_list->KeyCount = $vwrdm_custom_fields_list->RowIndex;

		// Init row class and style
		$vwrdm_custom_fields->ResetAttrs();
		$vwrdm_custom_fields->CssClass = "";
		if ($vwrdm_custom_fields->CurrentAction == "gridadd") {
		} else {
			$vwrdm_custom_fields_list->LoadRowValues($vwrdm_custom_fields_list->Recordset); // Load row values
		}
		$vwrdm_custom_fields->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vwrdm_custom_fields->RowAttrs = array_merge($vwrdm_custom_fields->RowAttrs, array('data-rowindex'=>$vwrdm_custom_fields_list->RowCnt, 'id'=>'r' . $vwrdm_custom_fields_list->RowCnt . '_vwrdm_custom_fields', 'data-rowtype'=>$vwrdm_custom_fields->RowType));

		// Render row
		$vwrdm_custom_fields_list->RenderRow();

		// Render list options
		$vwrdm_custom_fields_list->RenderListOptions();
?>
	<tr<?php echo $vwrdm_custom_fields->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vwrdm_custom_fields_list->ListOptions->Render("body", "left", $vwrdm_custom_fields_list->RowCnt);
?>
	<?php if ($vwrdm_custom_fields->id->Visible) { // id ?>
		<td<?php echo $vwrdm_custom_fields->id->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->id->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->id->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->type->Visible) { // type ?>
		<td<?php echo $vwrdm_custom_fields->type->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->type->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->type->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->name->Visible) { // name ?>
		<td<?php echo $vwrdm_custom_fields->name->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->name->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->name->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->field_format->Visible) { // field_format ?>
		<td<?php echo $vwrdm_custom_fields->field_format->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->field_format->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->field_format->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->regexp->Visible) { // regexp ?>
		<td<?php echo $vwrdm_custom_fields->regexp->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->regexp->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->regexp->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->min_length->Visible) { // min_length ?>
		<td<?php echo $vwrdm_custom_fields->min_length->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->min_length->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->min_length->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->max_length->Visible) { // max_length ?>
		<td<?php echo $vwrdm_custom_fields->max_length->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->max_length->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->max_length->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->is_required->Visible) { // is_required ?>
		<td<?php echo $vwrdm_custom_fields->is_required->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->is_required->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->is_required->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->is_for_all->Visible) { // is_for_all ?>
		<td<?php echo $vwrdm_custom_fields->is_for_all->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->is_for_all->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->is_for_all->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->is_filter->Visible) { // is_filter ?>
		<td<?php echo $vwrdm_custom_fields->is_filter->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->is_filter->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->is_filter->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->position->Visible) { // position ?>
		<td<?php echo $vwrdm_custom_fields->position->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->position->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->position->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->searchable->Visible) { // searchable ?>
		<td<?php echo $vwrdm_custom_fields->searchable->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->searchable->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->searchable->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->editable->Visible) { // editable ?>
		<td<?php echo $vwrdm_custom_fields->editable->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->editable->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->editable->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->visible->Visible) { // visible ?>
		<td<?php echo $vwrdm_custom_fields->visible->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->visible->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->visible->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdm_custom_fields->multiple->Visible) { // multiple ?>
		<td<?php echo $vwrdm_custom_fields->multiple->CellAttributes() ?>>
<span<?php echo $vwrdm_custom_fields->multiple->ViewAttributes() ?>>
<?php echo $vwrdm_custom_fields->multiple->ListViewValue() ?></span>
<a id="<?php echo $vwrdm_custom_fields_list->PageObjName . "_row_" . $vwrdm_custom_fields_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$vwrdm_custom_fields_list->ListOptions->Render("body", "right", $vwrdm_custom_fields_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vwrdm_custom_fields->CurrentAction <> "gridadd")
		$vwrdm_custom_fields_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vwrdm_custom_fields->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vwrdm_custom_fields_list->Recordset)
	$vwrdm_custom_fields_list->Recordset->Close();
?>
<?php if ($vwrdm_custom_fields->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($vwrdm_custom_fields->CurrentAction <> "gridadd" && $vwrdm_custom_fields->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($vwrdm_custom_fields_list->Pager)) $vwrdm_custom_fields_list->Pager = new cNumericPager($vwrdm_custom_fields_list->StartRec, $vwrdm_custom_fields_list->DisplayRecs, $vwrdm_custom_fields_list->TotalRecs, $vwrdm_custom_fields_list->RecRange) ?>
<?php if ($vwrdm_custom_fields_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($vwrdm_custom_fields_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_custom_fields_list->PageUrl() ?>start=<?php echo $vwrdm_custom_fields_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_custom_fields_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_custom_fields_list->PageUrl() ?>start=<?php echo $vwrdm_custom_fields_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($vwrdm_custom_fields_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $vwrdm_custom_fields_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_custom_fields_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_custom_fields_list->PageUrl() ?>start=<?php echo $vwrdm_custom_fields_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($vwrdm_custom_fields_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdm_custom_fields_list->PageUrl() ?>start=<?php echo $vwrdm_custom_fields_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($vwrdm_custom_fields_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vwrdm_custom_fields_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vwrdm_custom_fields_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vwrdm_custom_fields_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($vwrdm_custom_fields_list->SearchWhere == "0=101") { ?>
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
	foreach ($vwrdm_custom_fields_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($vwrdm_custom_fields->Export == "") { ?>
<script type="text/javascript">
fvwrdm_custom_fieldslistsrch.Init();
fvwrdm_custom_fieldslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$vwrdm_custom_fields_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($vwrdm_custom_fields->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$vwrdm_custom_fields_list->Page_Terminate();
?>
