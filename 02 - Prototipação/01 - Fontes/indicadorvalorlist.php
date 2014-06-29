<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "indicadorvalorinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "indicadorversaogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$indicadorvalor_list = NULL; // Initialize page object first

class cindicadorvalor_list extends cindicadorvalor {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{FE479719-4CC0-498B-BE07-C9817DD0435B}";

	// Table name
	var $TableName = 'indicadorvalor';

	// Page object name
	var $PageObjName = 'indicadorvalor_list';

	// Grid form hidden field names
	var $FormName = 'findicadorvalorlist';
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

		// Table object (indicadorvalor)
		if (!isset($GLOBALS["indicadorvalor"])) {
			$GLOBALS["indicadorvalor"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["indicadorvalor"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "indicadorvaloradd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "indicadorvalordelete.php";
		$this->MultiUpdateUrl = "indicadorvalorupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'indicadorvalor', TRUE);

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
		if (count($arrKeyFlds) >= 3) {
			$this->nu_indicador->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_indicador->FormValue))
				return FALSE;
			$this->nu_versao->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->nu_versao->FormValue))
				return FALSE;
			$this->dh_geracao->setFormValue($arrKeyFlds[2]);
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->vr_indicadorTexto, $Keyword);
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
			$this->UpdateSort($this->nu_indicador, $bCtrl); // nu_indicador
			$this->UpdateSort($this->nu_versao, $bCtrl); // nu_versao
			$this->UpdateSort($this->dh_geracao, $bCtrl); // dh_geracao
			$this->UpdateSort($this->vr_indicadorNumerico, $bCtrl); // vr_indicadorNumerico
			$this->UpdateSort($this->vr_indicadorTexto, $bCtrl); // vr_indicadorTexto
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
				$this->nu_indicador->setSort("");
				$this->nu_versao->setSort("");
				$this->dh_geracao->setSort("");
				$this->vr_indicadorNumerico->setSort("");
				$this->vr_indicadorTexto->setSort("");
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

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// "detail_indicadorversao"
		$item = &$this->ListOptions->Add("detail_indicadorversao");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'indicadorversao') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["indicadorversao_grid"])) $GLOBALS["indicadorversao_grid"] = new cindicadorversao_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = FALSE;
			$item->ShowInButtonGroup = FALSE;
		}

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

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_indicadorversao"
		$oListOpt = &$this->ListOptions->Items["detail_indicadorversao"];
		if ($Security->AllowList(CurrentProjectID() . 'indicadorversao')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("indicadorversao", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("indicadorversaolist.php?" . EW_TABLE_SHOW_MASTER . "=indicadorvalor&nu_indicador=" . strval($this->nu_indicador->CurrentValue) . "&nu_versao=" . strval($this->nu_versao->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["indicadorversao_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'indicadorversao')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=indicadorversao")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "indicadorversao";
			}
			if ($GLOBALS["indicadorversao_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'indicadorversao')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=indicadorversao")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "indicadorversao";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">" .
				"<a class=\"btn btn-small ewRowLink ewDetailView\" data-action=\"list\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . $body . "</a>";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . $Language->Phrase("MasterDetailCopyLink") . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\">&nbsp;<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_indicadorversao");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=indicadorversao") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["indicadorversao"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["indicadorversao"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'indicadorversao') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "indicadorversao";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.findicadorvalorlist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->nu_indicador->setDbValue($rs->fields('nu_indicador'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->dh_geracao->setDbValue($rs->fields('dh_geracao'));
		$this->vr_indicadorNumerico->setDbValue($rs->fields('vr_indicadorNumerico'));
		$this->vr_indicadorTexto->setDbValue($rs->fields('vr_indicadorTexto'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_indicador->DbValue = $row['nu_indicador'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->dh_geracao->DbValue = $row['dh_geracao'];
		$this->vr_indicadorNumerico->DbValue = $row['vr_indicadorNumerico'];
		$this->vr_indicadorTexto->DbValue = $row['vr_indicadorTexto'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_indicador")) <> "")
			$this->nu_indicador->CurrentValue = $this->getKey("nu_indicador"); // nu_indicador
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nu_versao")) <> "")
			$this->nu_versao->CurrentValue = $this->getKey("nu_versao"); // nu_versao
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("dh_geracao")) <> "")
			$this->dh_geracao->CurrentValue = $this->getKey("dh_geracao"); // dh_geracao
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
		if ($this->vr_indicadorNumerico->FormValue == $this->vr_indicadorNumerico->CurrentValue && is_numeric(ew_StrToFloat($this->vr_indicadorNumerico->CurrentValue)))
			$this->vr_indicadorNumerico->CurrentValue = ew_StrToFloat($this->vr_indicadorNumerico->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_indicador
		// nu_versao
		// dh_geracao
		// vr_indicadorNumerico
		// vr_indicadorTexto

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_indicador
			if (strval($this->nu_indicador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_indicador]" . ew_SearchString("=", $this->nu_indicador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_indicador], [no_indicador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[indicador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_indicador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_indicador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_indicador->ViewValue = $this->nu_indicador->CurrentValue;
				}
			} else {
				$this->nu_indicador->ViewValue = NULL;
			}
			$this->nu_indicador->ViewCustomAttributes = "";

			// nu_versao
			if (strval($this->nu_versao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_versao]" . ew_SearchString("=", $this->nu_versao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_versao], [nu_versao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[indicadorversao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_versao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_versao] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_versao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
				}
			} else {
				$this->nu_versao->ViewValue = NULL;
			}
			$this->nu_versao->ViewCustomAttributes = "";

			// dh_geracao
			$this->dh_geracao->ViewValue = $this->dh_geracao->CurrentValue;
			$this->dh_geracao->ViewValue = ew_FormatDateTime($this->dh_geracao->ViewValue, 11);
			$this->dh_geracao->ViewCustomAttributes = "";

			// vr_indicadorNumerico
			$this->vr_indicadorNumerico->ViewValue = $this->vr_indicadorNumerico->CurrentValue;
			$this->vr_indicadorNumerico->ViewCustomAttributes = "";

			// vr_indicadorTexto
			$this->vr_indicadorTexto->ViewValue = $this->vr_indicadorTexto->CurrentValue;
			$this->vr_indicadorTexto->ViewCustomAttributes = "";

			// nu_indicador
			$this->nu_indicador->LinkCustomAttributes = "";
			$this->nu_indicador->HrefValue = "";
			$this->nu_indicador->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// dh_geracao
			$this->dh_geracao->LinkCustomAttributes = "";
			$this->dh_geracao->HrefValue = "";
			$this->dh_geracao->TooltipValue = "";

			// vr_indicadorNumerico
			$this->vr_indicadorNumerico->LinkCustomAttributes = "";
			$this->vr_indicadorNumerico->HrefValue = "";
			$this->vr_indicadorNumerico->TooltipValue = "";

			// vr_indicadorTexto
			$this->vr_indicadorTexto->LinkCustomAttributes = "";
			$this->vr_indicadorTexto->HrefValue = "";
			$this->vr_indicadorTexto->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_indicadorvalor\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_indicadorvalor',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.findicadorvalorlist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($indicadorvalor_list)) $indicadorvalor_list = new cindicadorvalor_list();

// Page init
$indicadorvalor_list->Page_Init();

// Page main
$indicadorvalor_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$indicadorvalor_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($indicadorvalor->Export == "") { ?>
<script type="text/javascript">

// Page object
var indicadorvalor_list = new ew_Page("indicadorvalor_list");
indicadorvalor_list.PageID = "list"; // Page ID
var EW_PAGE_ID = indicadorvalor_list.PageID; // For backward compatibility

// Form object
var findicadorvalorlist = new ew_Form("findicadorvalorlist");
findicadorvalorlist.FormKeyCountName = '<?php echo $indicadorvalor_list->FormKeyCountName ?>';

// Form_CustomValidate event
findicadorvalorlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
findicadorvalorlist.ValidateRequired = true;
<?php } else { ?>
findicadorvalorlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
findicadorvalorlist.Lists["x_nu_indicador"] = {"LinkField":"x_nu_indicador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_indicador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
findicadorvalorlist.Lists["x_nu_versao"] = {"LinkField":"x_nu_versao","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_versao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var findicadorvalorlistsrch = new ew_Form("findicadorvalorlistsrch");

// Init search panel as collapsed
if (findicadorvalorlistsrch) findicadorvalorlistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($indicadorvalor->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($indicadorvalor_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $indicadorvalor_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$indicadorvalor_list->TotalRecs = $indicadorvalor->SelectRecordCount();
	} else {
		if ($indicadorvalor_list->Recordset = $indicadorvalor_list->LoadRecordset())
			$indicadorvalor_list->TotalRecs = $indicadorvalor_list->Recordset->RecordCount();
	}
	$indicadorvalor_list->StartRec = 1;
	if ($indicadorvalor_list->DisplayRecs <= 0 || ($indicadorvalor->Export <> "" && $indicadorvalor->ExportAll)) // Display all records
		$indicadorvalor_list->DisplayRecs = $indicadorvalor_list->TotalRecs;
	if (!($indicadorvalor->Export <> "" && $indicadorvalor->ExportAll))
		$indicadorvalor_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$indicadorvalor_list->Recordset = $indicadorvalor_list->LoadRecordset($indicadorvalor_list->StartRec-1, $indicadorvalor_list->DisplayRecs);
$indicadorvalor_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($indicadorvalor->Export == "" && $indicadorvalor->CurrentAction == "") { ?>
<form name="findicadorvalorlistsrch" id="findicadorvalorlistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="findicadorvalorlistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#findicadorvalorlistsrch_SearchGroup" href="#findicadorvalorlistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="findicadorvalorlistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="findicadorvalorlistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="indicadorvalor">
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($indicadorvalor_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $indicadorvalor_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_2" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($indicadorvalor_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($indicadorvalor_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($indicadorvalor_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $indicadorvalor_list->ShowPageHeader(); ?>
<?php
$indicadorvalor_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="findicadorvalorlist" id="findicadorvalorlist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="indicadorvalor">
<div id="gmp_indicadorvalor" class="ewGridMiddlePanel">
<?php if ($indicadorvalor_list->TotalRecs > 0) { ?>
<table id="tbl_indicadorvalorlist" class="ewTable ewTableSeparate">
<?php echo $indicadorvalor->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$indicadorvalor_list->RenderListOptions();

// Render list options (header, left)
$indicadorvalor_list->ListOptions->Render("header", "left");
?>
<?php if ($indicadorvalor->nu_indicador->Visible) { // nu_indicador ?>
	<?php if ($indicadorvalor->SortUrl($indicadorvalor->nu_indicador) == "") { ?>
		<td><div id="elh_indicadorvalor_nu_indicador" class="indicadorvalor_nu_indicador"><div class="ewTableHeaderCaption"><?php echo $indicadorvalor->nu_indicador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $indicadorvalor->SortUrl($indicadorvalor->nu_indicador) ?>',2);"><div id="elh_indicadorvalor_nu_indicador" class="indicadorvalor_nu_indicador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorvalor->nu_indicador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicadorvalor->nu_indicador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorvalor->nu_indicador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicadorvalor->nu_versao->Visible) { // nu_versao ?>
	<?php if ($indicadorvalor->SortUrl($indicadorvalor->nu_versao) == "") { ?>
		<td><div id="elh_indicadorvalor_nu_versao" class="indicadorvalor_nu_versao"><div class="ewTableHeaderCaption"><?php echo $indicadorvalor->nu_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $indicadorvalor->SortUrl($indicadorvalor->nu_versao) ?>',2);"><div id="elh_indicadorvalor_nu_versao" class="indicadorvalor_nu_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorvalor->nu_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicadorvalor->nu_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorvalor->nu_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicadorvalor->dh_geracao->Visible) { // dh_geracao ?>
	<?php if ($indicadorvalor->SortUrl($indicadorvalor->dh_geracao) == "") { ?>
		<td><div id="elh_indicadorvalor_dh_geracao" class="indicadorvalor_dh_geracao"><div class="ewTableHeaderCaption"><?php echo $indicadorvalor->dh_geracao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $indicadorvalor->SortUrl($indicadorvalor->dh_geracao) ?>',2);"><div id="elh_indicadorvalor_dh_geracao" class="indicadorvalor_dh_geracao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorvalor->dh_geracao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicadorvalor->dh_geracao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorvalor->dh_geracao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicadorvalor->vr_indicadorNumerico->Visible) { // vr_indicadorNumerico ?>
	<?php if ($indicadorvalor->SortUrl($indicadorvalor->vr_indicadorNumerico) == "") { ?>
		<td><div id="elh_indicadorvalor_vr_indicadorNumerico" class="indicadorvalor_vr_indicadorNumerico"><div class="ewTableHeaderCaption"><?php echo $indicadorvalor->vr_indicadorNumerico->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $indicadorvalor->SortUrl($indicadorvalor->vr_indicadorNumerico) ?>',2);"><div id="elh_indicadorvalor_vr_indicadorNumerico" class="indicadorvalor_vr_indicadorNumerico">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorvalor->vr_indicadorNumerico->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($indicadorvalor->vr_indicadorNumerico->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorvalor->vr_indicadorNumerico->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($indicadorvalor->vr_indicadorTexto->Visible) { // vr_indicadorTexto ?>
	<?php if ($indicadorvalor->SortUrl($indicadorvalor->vr_indicadorTexto) == "") { ?>
		<td><div id="elh_indicadorvalor_vr_indicadorTexto" class="indicadorvalor_vr_indicadorTexto"><div class="ewTableHeaderCaption"><?php echo $indicadorvalor->vr_indicadorTexto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $indicadorvalor->SortUrl($indicadorvalor->vr_indicadorTexto) ?>',2);"><div id="elh_indicadorvalor_vr_indicadorTexto" class="indicadorvalor_vr_indicadorTexto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $indicadorvalor->vr_indicadorTexto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($indicadorvalor->vr_indicadorTexto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($indicadorvalor->vr_indicadorTexto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$indicadorvalor_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($indicadorvalor->ExportAll && $indicadorvalor->Export <> "") {
	$indicadorvalor_list->StopRec = $indicadorvalor_list->TotalRecs;
} else {

	// Set the last record to display
	if ($indicadorvalor_list->TotalRecs > $indicadorvalor_list->StartRec + $indicadorvalor_list->DisplayRecs - 1)
		$indicadorvalor_list->StopRec = $indicadorvalor_list->StartRec + $indicadorvalor_list->DisplayRecs - 1;
	else
		$indicadorvalor_list->StopRec = $indicadorvalor_list->TotalRecs;
}
$indicadorvalor_list->RecCnt = $indicadorvalor_list->StartRec - 1;
if ($indicadorvalor_list->Recordset && !$indicadorvalor_list->Recordset->EOF) {
	$indicadorvalor_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $indicadorvalor_list->StartRec > 1)
		$indicadorvalor_list->Recordset->Move($indicadorvalor_list->StartRec - 1);
} elseif (!$indicadorvalor->AllowAddDeleteRow && $indicadorvalor_list->StopRec == 0) {
	$indicadorvalor_list->StopRec = $indicadorvalor->GridAddRowCount;
}

// Initialize aggregate
$indicadorvalor->RowType = EW_ROWTYPE_AGGREGATEINIT;
$indicadorvalor->ResetAttrs();
$indicadorvalor_list->RenderRow();
while ($indicadorvalor_list->RecCnt < $indicadorvalor_list->StopRec) {
	$indicadorvalor_list->RecCnt++;
	if (intval($indicadorvalor_list->RecCnt) >= intval($indicadorvalor_list->StartRec)) {
		$indicadorvalor_list->RowCnt++;

		// Set up key count
		$indicadorvalor_list->KeyCount = $indicadorvalor_list->RowIndex;

		// Init row class and style
		$indicadorvalor->ResetAttrs();
		$indicadorvalor->CssClass = "";
		if ($indicadorvalor->CurrentAction == "gridadd") {
		} else {
			$indicadorvalor_list->LoadRowValues($indicadorvalor_list->Recordset); // Load row values
		}
		$indicadorvalor->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$indicadorvalor->RowAttrs = array_merge($indicadorvalor->RowAttrs, array('data-rowindex'=>$indicadorvalor_list->RowCnt, 'id'=>'r' . $indicadorvalor_list->RowCnt . '_indicadorvalor', 'data-rowtype'=>$indicadorvalor->RowType));

		// Render row
		$indicadorvalor_list->RenderRow();

		// Render list options
		$indicadorvalor_list->RenderListOptions();
?>
	<tr<?php echo $indicadorvalor->RowAttributes() ?>>
<?php

// Render list options (body, left)
$indicadorvalor_list->ListOptions->Render("body", "left", $indicadorvalor_list->RowCnt);
?>
	<?php if ($indicadorvalor->nu_indicador->Visible) { // nu_indicador ?>
		<td<?php echo $indicadorvalor->nu_indicador->CellAttributes() ?>>
<span<?php echo $indicadorvalor->nu_indicador->ViewAttributes() ?>>
<?php echo $indicadorvalor->nu_indicador->ListViewValue() ?></span>
<a id="<?php echo $indicadorvalor_list->PageObjName . "_row_" . $indicadorvalor_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicadorvalor->nu_versao->Visible) { // nu_versao ?>
		<td<?php echo $indicadorvalor->nu_versao->CellAttributes() ?>>
<span<?php echo $indicadorvalor->nu_versao->ViewAttributes() ?>>
<?php echo $indicadorvalor->nu_versao->ListViewValue() ?></span>
<a id="<?php echo $indicadorvalor_list->PageObjName . "_row_" . $indicadorvalor_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicadorvalor->dh_geracao->Visible) { // dh_geracao ?>
		<td<?php echo $indicadorvalor->dh_geracao->CellAttributes() ?>>
<span<?php echo $indicadorvalor->dh_geracao->ViewAttributes() ?>>
<?php echo $indicadorvalor->dh_geracao->ListViewValue() ?></span>
<a id="<?php echo $indicadorvalor_list->PageObjName . "_row_" . $indicadorvalor_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicadorvalor->vr_indicadorNumerico->Visible) { // vr_indicadorNumerico ?>
		<td<?php echo $indicadorvalor->vr_indicadorNumerico->CellAttributes() ?>>
<span<?php echo $indicadorvalor->vr_indicadorNumerico->ViewAttributes() ?>>
<?php echo $indicadorvalor->vr_indicadorNumerico->ListViewValue() ?></span>
<a id="<?php echo $indicadorvalor_list->PageObjName . "_row_" . $indicadorvalor_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($indicadorvalor->vr_indicadorTexto->Visible) { // vr_indicadorTexto ?>
		<td<?php echo $indicadorvalor->vr_indicadorTexto->CellAttributes() ?>>
<span<?php echo $indicadorvalor->vr_indicadorTexto->ViewAttributes() ?>>
<?php echo $indicadorvalor->vr_indicadorTexto->ListViewValue() ?></span>
<a id="<?php echo $indicadorvalor_list->PageObjName . "_row_" . $indicadorvalor_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$indicadorvalor_list->ListOptions->Render("body", "right", $indicadorvalor_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($indicadorvalor->CurrentAction <> "gridadd")
		$indicadorvalor_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($indicadorvalor->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($indicadorvalor_list->Recordset)
	$indicadorvalor_list->Recordset->Close();
?>
<?php if ($indicadorvalor->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($indicadorvalor->CurrentAction <> "gridadd" && $indicadorvalor->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($indicadorvalor_list->Pager)) $indicadorvalor_list->Pager = new cNumericPager($indicadorvalor_list->StartRec, $indicadorvalor_list->DisplayRecs, $indicadorvalor_list->TotalRecs, $indicadorvalor_list->RecRange) ?>
<?php if ($indicadorvalor_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($indicadorvalor_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $indicadorvalor_list->PageUrl() ?>start=<?php echo $indicadorvalor_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($indicadorvalor_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $indicadorvalor_list->PageUrl() ?>start=<?php echo $indicadorvalor_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($indicadorvalor_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $indicadorvalor_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($indicadorvalor_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $indicadorvalor_list->PageUrl() ?>start=<?php echo $indicadorvalor_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($indicadorvalor_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $indicadorvalor_list->PageUrl() ?>start=<?php echo $indicadorvalor_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($indicadorvalor_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $indicadorvalor_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $indicadorvalor_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $indicadorvalor_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($indicadorvalor_list->SearchWhere == "0=101") { ?>
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
	foreach ($indicadorvalor_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($indicadorvalor->Export == "") { ?>
<script type="text/javascript">
findicadorvalorlistsrch.Init();
findicadorvalorlist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$indicadorvalor_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($indicadorvalor->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$indicadorvalor_list->Page_Terminate();
?>
