<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "vwrdmd_lanhorasusuariomesinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$vwrdmd_lanHorasUsuarioMes_list = NULL; // Initialize page object first

class cvwrdmd_lanHorasUsuarioMes_list extends cvwrdmd_lanHorasUsuarioMes {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0602B820-DE72-4661-BB21-3716ACE9CB5F}";

	// Table name
	var $TableName = 'vwrdmd_lanHorasUsuarioMes';

	// Page object name
	var $PageObjName = 'vwrdmd_lanHorasUsuarioMes_list';

	// Grid form hidden field names
	var $FormName = 'fvwrdmd_lanHorasUsuarioMeslist';
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

		// Table object (vwrdmd_lanHorasUsuarioMes)
		if (!isset($GLOBALS["vwrdmd_lanHorasUsuarioMes"])) {
			$GLOBALS["vwrdmd_lanHorasUsuarioMes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vwrdmd_lanHorasUsuarioMes"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vwrdmd_lanhorasusuariomesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vwrdmd_lanhorasusuariomesdelete.php";
		$this->MultiUpdateUrl = "vwrdmd_lanhorasusuariomesupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vwrdmd_lanHorasUsuarioMes', TRUE);

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
		$this->BuildBasicSearchSQL($sWhere, $this->no_usuario, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->no_periodo, $Keyword);
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
			$this->UpdateSort($this->no_usuario, $bCtrl); // no_usuario
			$this->UpdateSort($this->nu_mes, $bCtrl); // nu_mes
			$this->UpdateSort($this->nu_ano, $bCtrl); // nu_ano
			$this->UpdateSort($this->qt_horas, $bCtrl); // qt_horas
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
				$this->no_usuario->setSort("");
				$this->nu_mes->setSort("");
				$this->nu_ano->setSort("");
				$this->qt_horas->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fvwrdmd_lanHorasUsuarioMeslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->no_usuario->setDbValue($rs->fields('no_usuario'));
		$this->no_periodo->setDbValue($rs->fields('no_periodo'));
		$this->nu_mes->setDbValue($rs->fields('nu_mes'));
		$this->nu_ano->setDbValue($rs->fields('nu_ano'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->no_usuario->DbValue = $row['no_usuario'];
		$this->no_periodo->DbValue = $row['no_periodo'];
		$this->nu_mes->DbValue = $row['nu_mes'];
		$this->nu_ano->DbValue = $row['nu_ano'];
		$this->qt_horas->DbValue = $row['qt_horas'];
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
		if ($this->qt_horas->FormValue == $this->qt_horas->CurrentValue && is_numeric(ew_StrToFloat($this->qt_horas->CurrentValue)))
			$this->qt_horas->CurrentValue = ew_StrToFloat($this->qt_horas->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_usuario
		// no_usuario
		// no_periodo
		// nu_mes
		// nu_ano
		// qt_horas
		// Accumulate aggregate value

		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			if (is_numeric($this->qt_horas->CurrentValue))
				$this->qt_horas->Total += $this->qt_horas->CurrentValue; // Accumulate total
		}
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// no_usuario
			$this->no_usuario->ViewValue = $this->no_usuario->CurrentValue;
			$this->no_usuario->ViewCustomAttributes = "";

			// no_periodo
			$this->no_periodo->ViewValue = $this->no_periodo->CurrentValue;
			$this->no_periodo->ViewCustomAttributes = "";

			// nu_mes
			$this->nu_mes->ViewValue = $this->nu_mes->CurrentValue;
			$this->nu_mes->ViewCustomAttributes = "";

			// nu_ano
			if (strval($this->nu_ano->CurrentValue) <> "") {
				switch ($this->nu_ano->CurrentValue) {
					case $this->nu_ano->FldTagValue(1):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(1) <> "" ? $this->nu_ano->FldTagCaption(1) : $this->nu_ano->CurrentValue;
						break;
					case $this->nu_ano->FldTagValue(2):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(2) <> "" ? $this->nu_ano->FldTagCaption(2) : $this->nu_ano->CurrentValue;
						break;
					case $this->nu_ano->FldTagValue(3):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(3) <> "" ? $this->nu_ano->FldTagCaption(3) : $this->nu_ano->CurrentValue;
						break;
					case $this->nu_ano->FldTagValue(4):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(4) <> "" ? $this->nu_ano->FldTagCaption(4) : $this->nu_ano->CurrentValue;
						break;
					case $this->nu_ano->FldTagValue(5):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(5) <> "" ? $this->nu_ano->FldTagCaption(5) : $this->nu_ano->CurrentValue;
						break;
					case $this->nu_ano->FldTagValue(6):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(6) <> "" ? $this->nu_ano->FldTagCaption(6) : $this->nu_ano->CurrentValue;
						break;
					case $this->nu_ano->FldTagValue(7):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(7) <> "" ? $this->nu_ano->FldTagCaption(7) : $this->nu_ano->CurrentValue;
						break;
					case $this->nu_ano->FldTagValue(8):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(8) <> "" ? $this->nu_ano->FldTagCaption(8) : $this->nu_ano->CurrentValue;
						break;
					case $this->nu_ano->FldTagValue(9):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(9) <> "" ? $this->nu_ano->FldTagCaption(9) : $this->nu_ano->CurrentValue;
						break;
					case $this->nu_ano->FldTagValue(10):
						$this->nu_ano->ViewValue = $this->nu_ano->FldTagCaption(10) <> "" ? $this->nu_ano->FldTagCaption(10) : $this->nu_ano->CurrentValue;
						break;
					default:
						$this->nu_ano->ViewValue = $this->nu_ano->CurrentValue;
				}
			} else {
				$this->nu_ano->ViewValue = NULL;
			}
			$this->nu_ano->ViewCustomAttributes = "";

			// qt_horas
			$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
			$this->qt_horas->ViewValue = ew_FormatNumber($this->qt_horas->ViewValue, 2, -2, -2, -2);
			$this->qt_horas->ViewCustomAttributes = "";

			// no_usuario
			$this->no_usuario->LinkCustomAttributes = "";
			$this->no_usuario->HrefValue = "";
			$this->no_usuario->TooltipValue = "";

			// nu_mes
			$this->nu_mes->LinkCustomAttributes = "";
			$this->nu_mes->HrefValue = "";
			$this->nu_mes->TooltipValue = "";

			// nu_ano
			$this->nu_ano->LinkCustomAttributes = "";
			$this->nu_ano->HrefValue = "";
			$this->nu_ano->TooltipValue = "";

			// qt_horas
			$this->qt_horas->LinkCustomAttributes = "";
			$this->qt_horas->HrefValue = "";
			$this->qt_horas->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->qt_horas->Total = 0; // Initialize total
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->qt_horas->CurrentValue = $this->qt_horas->Total;
			$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
			$this->qt_horas->ViewValue = ew_FormatNumber($this->qt_horas->ViewValue, 2, -2, -2, -2);
			$this->qt_horas->ViewCustomAttributes = "";
			$this->qt_horas->HrefValue = ""; // Clear href value
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
		$item->Body = "<a id=\"emf_vwrdmd_lanHorasUsuarioMes\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_vwrdmd_lanHorasUsuarioMes',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fvwrdmd_lanHorasUsuarioMeslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($vwrdmd_lanHorasUsuarioMes_list)) $vwrdmd_lanHorasUsuarioMes_list = new cvwrdmd_lanHorasUsuarioMes_list();

// Page init
$vwrdmd_lanHorasUsuarioMes_list->Page_Init();

// Page main
$vwrdmd_lanHorasUsuarioMes_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vwrdmd_lanHorasUsuarioMes_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($vwrdmd_lanHorasUsuarioMes->Export == "") { ?>
<script type="text/javascript">

// Page object
var vwrdmd_lanHorasUsuarioMes_list = new ew_Page("vwrdmd_lanHorasUsuarioMes_list");
vwrdmd_lanHorasUsuarioMes_list.PageID = "list"; // Page ID
var EW_PAGE_ID = vwrdmd_lanHorasUsuarioMes_list.PageID; // For backward compatibility

// Form object
var fvwrdmd_lanHorasUsuarioMeslist = new ew_Form("fvwrdmd_lanHorasUsuarioMeslist");
fvwrdmd_lanHorasUsuarioMeslist.FormKeyCountName = '<?php echo $vwrdmd_lanHorasUsuarioMes_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvwrdmd_lanHorasUsuarioMeslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvwrdmd_lanHorasUsuarioMeslist.ValidateRequired = true;
<?php } else { ?>
fvwrdmd_lanHorasUsuarioMeslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fvwrdmd_lanHorasUsuarioMeslistsrch = new ew_Form("fvwrdmd_lanHorasUsuarioMeslistsrch");

// Init search panel as collapsed
if (fvwrdmd_lanHorasUsuarioMeslistsrch) fvwrdmd_lanHorasUsuarioMeslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($vwrdmd_lanHorasUsuarioMes->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($vwrdmd_lanHorasUsuarioMes_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $vwrdmd_lanHorasUsuarioMes_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$vwrdmd_lanHorasUsuarioMes_list->TotalRecs = $vwrdmd_lanHorasUsuarioMes->SelectRecordCount();
	} else {
		if ($vwrdmd_lanHorasUsuarioMes_list->Recordset = $vwrdmd_lanHorasUsuarioMes_list->LoadRecordset())
			$vwrdmd_lanHorasUsuarioMes_list->TotalRecs = $vwrdmd_lanHorasUsuarioMes_list->Recordset->RecordCount();
	}
	$vwrdmd_lanHorasUsuarioMes_list->StartRec = 1;
	if ($vwrdmd_lanHorasUsuarioMes_list->DisplayRecs <= 0 || ($vwrdmd_lanHorasUsuarioMes->Export <> "" && $vwrdmd_lanHorasUsuarioMes->ExportAll)) // Display all records
		$vwrdmd_lanHorasUsuarioMes_list->DisplayRecs = $vwrdmd_lanHorasUsuarioMes_list->TotalRecs;
	if (!($vwrdmd_lanHorasUsuarioMes->Export <> "" && $vwrdmd_lanHorasUsuarioMes->ExportAll))
		$vwrdmd_lanHorasUsuarioMes_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vwrdmd_lanHorasUsuarioMes_list->Recordset = $vwrdmd_lanHorasUsuarioMes_list->LoadRecordset($vwrdmd_lanHorasUsuarioMes_list->StartRec-1, $vwrdmd_lanHorasUsuarioMes_list->DisplayRecs);
$vwrdmd_lanHorasUsuarioMes_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($vwrdmd_lanHorasUsuarioMes->Export == "" && $vwrdmd_lanHorasUsuarioMes->CurrentAction == "") { ?>
<form name="fvwrdmd_lanHorasUsuarioMeslistsrch" id="fvwrdmd_lanHorasUsuarioMeslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fvwrdmd_lanHorasUsuarioMeslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fvwrdmd_lanHorasUsuarioMeslistsrch_SearchGroup" href="#fvwrdmd_lanHorasUsuarioMeslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fvwrdmd_lanHorasUsuarioMeslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fvwrdmd_lanHorasUsuarioMeslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="vwrdmd_lanHorasUsuarioMes">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($vwrdmd_lanHorasUsuarioMes_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $vwrdmd_lanHorasUsuarioMes_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($vwrdmd_lanHorasUsuarioMes_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($vwrdmd_lanHorasUsuarioMes_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($vwrdmd_lanHorasUsuarioMes_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $vwrdmd_lanHorasUsuarioMes_list->ShowPageHeader(); ?>
<?php
$vwrdmd_lanHorasUsuarioMes_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fvwrdmd_lanHorasUsuarioMeslist" id="fvwrdmd_lanHorasUsuarioMeslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="vwrdmd_lanHorasUsuarioMes">
<div id="gmp_vwrdmd_lanHorasUsuarioMes" class="ewGridMiddlePanel">
<?php if ($vwrdmd_lanHorasUsuarioMes_list->TotalRecs > 0) { ?>
<table id="tbl_vwrdmd_lanHorasUsuarioMeslist" class="ewTable ewTableSeparate">
<?php echo $vwrdmd_lanHorasUsuarioMes->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$vwrdmd_lanHorasUsuarioMes_list->RenderListOptions();

// Render list options (header, left)
$vwrdmd_lanHorasUsuarioMes_list->ListOptions->Render("header", "left");
?>
<?php if ($vwrdmd_lanHorasUsuarioMes->no_usuario->Visible) { // no_usuario ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->SortUrl($vwrdmd_lanHorasUsuarioMes->no_usuario) == "") { ?>
		<td><div id="elh_vwrdmd_lanHorasUsuarioMes_no_usuario" class="vwrdmd_lanHorasUsuarioMes_no_usuario"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_lanHorasUsuarioMes->no_usuario->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_lanHorasUsuarioMes->SortUrl($vwrdmd_lanHorasUsuarioMes->no_usuario) ?>',2);"><div id="elh_vwrdmd_lanHorasUsuarioMes_no_usuario" class="vwrdmd_lanHorasUsuarioMes_no_usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_lanHorasUsuarioMes->no_usuario->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_lanHorasUsuarioMes->no_usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_lanHorasUsuarioMes->no_usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_lanHorasUsuarioMes->nu_mes->Visible) { // nu_mes ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->SortUrl($vwrdmd_lanHorasUsuarioMes->nu_mes) == "") { ?>
		<td><div id="elh_vwrdmd_lanHorasUsuarioMes_nu_mes" class="vwrdmd_lanHorasUsuarioMes_nu_mes"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_lanHorasUsuarioMes->nu_mes->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_lanHorasUsuarioMes->SortUrl($vwrdmd_lanHorasUsuarioMes->nu_mes) ?>',2);"><div id="elh_vwrdmd_lanHorasUsuarioMes_nu_mes" class="vwrdmd_lanHorasUsuarioMes_nu_mes">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_lanHorasUsuarioMes->nu_mes->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_lanHorasUsuarioMes->nu_mes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_lanHorasUsuarioMes->nu_mes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_lanHorasUsuarioMes->nu_ano->Visible) { // nu_ano ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->SortUrl($vwrdmd_lanHorasUsuarioMes->nu_ano) == "") { ?>
		<td><div id="elh_vwrdmd_lanHorasUsuarioMes_nu_ano" class="vwrdmd_lanHorasUsuarioMes_nu_ano"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_lanHorasUsuarioMes->nu_ano->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_lanHorasUsuarioMes->SortUrl($vwrdmd_lanHorasUsuarioMes->nu_ano) ?>',2);"><div id="elh_vwrdmd_lanHorasUsuarioMes_nu_ano" class="vwrdmd_lanHorasUsuarioMes_nu_ano">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_lanHorasUsuarioMes->nu_ano->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_lanHorasUsuarioMes->nu_ano->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_lanHorasUsuarioMes->nu_ano->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_lanHorasUsuarioMes->qt_horas->Visible) { // qt_horas ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->SortUrl($vwrdmd_lanHorasUsuarioMes->qt_horas) == "") { ?>
		<td><div id="elh_vwrdmd_lanHorasUsuarioMes_qt_horas" class="vwrdmd_lanHorasUsuarioMes_qt_horas"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_lanHorasUsuarioMes->qt_horas->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_lanHorasUsuarioMes->SortUrl($vwrdmd_lanHorasUsuarioMes->qt_horas) ?>',2);"><div id="elh_vwrdmd_lanHorasUsuarioMes_qt_horas" class="vwrdmd_lanHorasUsuarioMes_qt_horas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_lanHorasUsuarioMes->qt_horas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_lanHorasUsuarioMes->qt_horas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_lanHorasUsuarioMes->qt_horas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vwrdmd_lanHorasUsuarioMes_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vwrdmd_lanHorasUsuarioMes->ExportAll && $vwrdmd_lanHorasUsuarioMes->Export <> "") {
	$vwrdmd_lanHorasUsuarioMes_list->StopRec = $vwrdmd_lanHorasUsuarioMes_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vwrdmd_lanHorasUsuarioMes_list->TotalRecs > $vwrdmd_lanHorasUsuarioMes_list->StartRec + $vwrdmd_lanHorasUsuarioMes_list->DisplayRecs - 1)
		$vwrdmd_lanHorasUsuarioMes_list->StopRec = $vwrdmd_lanHorasUsuarioMes_list->StartRec + $vwrdmd_lanHorasUsuarioMes_list->DisplayRecs - 1;
	else
		$vwrdmd_lanHorasUsuarioMes_list->StopRec = $vwrdmd_lanHorasUsuarioMes_list->TotalRecs;
}
$vwrdmd_lanHorasUsuarioMes_list->RecCnt = $vwrdmd_lanHorasUsuarioMes_list->StartRec - 1;
if ($vwrdmd_lanHorasUsuarioMes_list->Recordset && !$vwrdmd_lanHorasUsuarioMes_list->Recordset->EOF) {
	$vwrdmd_lanHorasUsuarioMes_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $vwrdmd_lanHorasUsuarioMes_list->StartRec > 1)
		$vwrdmd_lanHorasUsuarioMes_list->Recordset->Move($vwrdmd_lanHorasUsuarioMes_list->StartRec - 1);
} elseif (!$vwrdmd_lanHorasUsuarioMes->AllowAddDeleteRow && $vwrdmd_lanHorasUsuarioMes_list->StopRec == 0) {
	$vwrdmd_lanHorasUsuarioMes_list->StopRec = $vwrdmd_lanHorasUsuarioMes->GridAddRowCount;
}

// Initialize aggregate
$vwrdmd_lanHorasUsuarioMes->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vwrdmd_lanHorasUsuarioMes->ResetAttrs();
$vwrdmd_lanHorasUsuarioMes_list->RenderRow();
while ($vwrdmd_lanHorasUsuarioMes_list->RecCnt < $vwrdmd_lanHorasUsuarioMes_list->StopRec) {
	$vwrdmd_lanHorasUsuarioMes_list->RecCnt++;
	if (intval($vwrdmd_lanHorasUsuarioMes_list->RecCnt) >= intval($vwrdmd_lanHorasUsuarioMes_list->StartRec)) {
		$vwrdmd_lanHorasUsuarioMes_list->RowCnt++;

		// Set up key count
		$vwrdmd_lanHorasUsuarioMes_list->KeyCount = $vwrdmd_lanHorasUsuarioMes_list->RowIndex;

		// Init row class and style
		$vwrdmd_lanHorasUsuarioMes->ResetAttrs();
		$vwrdmd_lanHorasUsuarioMes->CssClass = "";
		if ($vwrdmd_lanHorasUsuarioMes->CurrentAction == "gridadd") {
		} else {
			$vwrdmd_lanHorasUsuarioMes_list->LoadRowValues($vwrdmd_lanHorasUsuarioMes_list->Recordset); // Load row values
		}
		$vwrdmd_lanHorasUsuarioMes->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vwrdmd_lanHorasUsuarioMes->RowAttrs = array_merge($vwrdmd_lanHorasUsuarioMes->RowAttrs, array('data-rowindex'=>$vwrdmd_lanHorasUsuarioMes_list->RowCnt, 'id'=>'r' . $vwrdmd_lanHorasUsuarioMes_list->RowCnt . '_vwrdmd_lanHorasUsuarioMes', 'data-rowtype'=>$vwrdmd_lanHorasUsuarioMes->RowType));

		// Render row
		$vwrdmd_lanHorasUsuarioMes_list->RenderRow();

		// Render list options
		$vwrdmd_lanHorasUsuarioMes_list->RenderListOptions();
?>
	<tr<?php echo $vwrdmd_lanHorasUsuarioMes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vwrdmd_lanHorasUsuarioMes_list->ListOptions->Render("body", "left", $vwrdmd_lanHorasUsuarioMes_list->RowCnt);
?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->no_usuario->Visible) { // no_usuario ?>
		<td<?php echo $vwrdmd_lanHorasUsuarioMes->no_usuario->CellAttributes() ?>>
<span<?php echo $vwrdmd_lanHorasUsuarioMes->no_usuario->ViewAttributes() ?>>
<?php echo $vwrdmd_lanHorasUsuarioMes->no_usuario->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_lanHorasUsuarioMes_list->PageObjName . "_row_" . $vwrdmd_lanHorasUsuarioMes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->nu_mes->Visible) { // nu_mes ?>
		<td<?php echo $vwrdmd_lanHorasUsuarioMes->nu_mes->CellAttributes() ?>>
<span<?php echo $vwrdmd_lanHorasUsuarioMes->nu_mes->ViewAttributes() ?>>
<?php echo $vwrdmd_lanHorasUsuarioMes->nu_mes->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_lanHorasUsuarioMes_list->PageObjName . "_row_" . $vwrdmd_lanHorasUsuarioMes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->nu_ano->Visible) { // nu_ano ?>
		<td<?php echo $vwrdmd_lanHorasUsuarioMes->nu_ano->CellAttributes() ?>>
<span<?php echo $vwrdmd_lanHorasUsuarioMes->nu_ano->ViewAttributes() ?>>
<?php echo $vwrdmd_lanHorasUsuarioMes->nu_ano->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_lanHorasUsuarioMes_list->PageObjName . "_row_" . $vwrdmd_lanHorasUsuarioMes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->qt_horas->Visible) { // qt_horas ?>
		<td<?php echo $vwrdmd_lanHorasUsuarioMes->qt_horas->CellAttributes() ?>>
<span<?php echo $vwrdmd_lanHorasUsuarioMes->qt_horas->ViewAttributes() ?>>
<?php echo $vwrdmd_lanHorasUsuarioMes->qt_horas->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_lanHorasUsuarioMes_list->PageObjName . "_row_" . $vwrdmd_lanHorasUsuarioMes_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$vwrdmd_lanHorasUsuarioMes_list->ListOptions->Render("body", "right", $vwrdmd_lanHorasUsuarioMes_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vwrdmd_lanHorasUsuarioMes->CurrentAction <> "gridadd")
		$vwrdmd_lanHorasUsuarioMes_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$vwrdmd_lanHorasUsuarioMes->RowType = EW_ROWTYPE_AGGREGATE;
$vwrdmd_lanHorasUsuarioMes->ResetAttrs();
$vwrdmd_lanHorasUsuarioMes_list->RenderRow();
?>
<?php if ($vwrdmd_lanHorasUsuarioMes_list->TotalRecs > 0 && ($vwrdmd_lanHorasUsuarioMes->CurrentAction <> "gridadd" && $vwrdmd_lanHorasUsuarioMes->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$vwrdmd_lanHorasUsuarioMes_list->RenderListOptions();

// Render list options (footer, left)
$vwrdmd_lanHorasUsuarioMes_list->ListOptions->Render("footer", "left");
?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->no_usuario->Visible) { // no_usuario ?>
		<td><span id="elf_vwrdmd_lanHorasUsuarioMes_no_usuario" class="vwrdmd_lanHorasUsuarioMes_no_usuario">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->nu_mes->Visible) { // nu_mes ?>
		<td><span id="elf_vwrdmd_lanHorasUsuarioMes_nu_mes" class="vwrdmd_lanHorasUsuarioMes_nu_mes">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->nu_ano->Visible) { // nu_ano ?>
		<td><span id="elf_vwrdmd_lanHorasUsuarioMes_nu_ano" class="vwrdmd_lanHorasUsuarioMes_nu_ano">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes->qt_horas->Visible) { // qt_horas ?>
		<td><span id="elf_vwrdmd_lanHorasUsuarioMes_qt_horas" class="vwrdmd_lanHorasUsuarioMes_qt_horas">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?>: </span>
<?php echo $vwrdmd_lanHorasUsuarioMes->qt_horas->ViewValue ?>
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$vwrdmd_lanHorasUsuarioMes_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($vwrdmd_lanHorasUsuarioMes->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vwrdmd_lanHorasUsuarioMes_list->Recordset)
	$vwrdmd_lanHorasUsuarioMes_list->Recordset->Close();
?>
<?php if ($vwrdmd_lanHorasUsuarioMes->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($vwrdmd_lanHorasUsuarioMes->CurrentAction <> "gridadd" && $vwrdmd_lanHorasUsuarioMes->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($vwrdmd_lanHorasUsuarioMes_list->Pager)) $vwrdmd_lanHorasUsuarioMes_list->Pager = new cNumericPager($vwrdmd_lanHorasUsuarioMes_list->StartRec, $vwrdmd_lanHorasUsuarioMes_list->DisplayRecs, $vwrdmd_lanHorasUsuarioMes_list->TotalRecs, $vwrdmd_lanHorasUsuarioMes_list->RecRange) ?>
<?php if ($vwrdmd_lanHorasUsuarioMes_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($vwrdmd_lanHorasUsuarioMes_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdmd_lanHorasUsuarioMes_list->PageUrl() ?>start=<?php echo $vwrdmd_lanHorasUsuarioMes_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdmd_lanHorasUsuarioMes_list->PageUrl() ?>start=<?php echo $vwrdmd_lanHorasUsuarioMes_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($vwrdmd_lanHorasUsuarioMes_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $vwrdmd_lanHorasUsuarioMes_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdmd_lanHorasUsuarioMes_list->PageUrl() ?>start=<?php echo $vwrdmd_lanHorasUsuarioMes_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdmd_lanHorasUsuarioMes_list->PageUrl() ?>start=<?php echo $vwrdmd_lanHorasUsuarioMes_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($vwrdmd_lanHorasUsuarioMes_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vwrdmd_lanHorasUsuarioMes_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vwrdmd_lanHorasUsuarioMes_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vwrdmd_lanHorasUsuarioMes_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($vwrdmd_lanHorasUsuarioMes_list->SearchWhere == "0=101") { ?>
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
	foreach ($vwrdmd_lanHorasUsuarioMes_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($vwrdmd_lanHorasUsuarioMes->Export == "") { ?>
<script type="text/javascript">
fvwrdmd_lanHorasUsuarioMeslistsrch.Init();
fvwrdmd_lanHorasUsuarioMeslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$vwrdmd_lanHorasUsuarioMes_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($vwrdmd_lanHorasUsuarioMes->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$vwrdmd_lanHorasUsuarioMes_list->Page_Terminate();
?>
