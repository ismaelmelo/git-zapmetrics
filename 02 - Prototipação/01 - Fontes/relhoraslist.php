<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "relhorasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$relhoras_list = NULL; // Initialize page object first

class crelhoras_list extends crelhoras {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0602B820-DE72-4661-BB21-3716ACE9CB5F}";

	// Table name
	var $TableName = 'relhoras';

	// Page object name
	var $PageObjName = 'relhoras_list';

	// Grid form hidden field names
	var $FormName = 'frelhoraslist';
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

		// Table object (relhoras)
		if (!isset($GLOBALS["relhoras"])) {
			$GLOBALS["relhoras"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["relhoras"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "relhorasadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "relhorasdelete.php";
		$this->MultiUpdateUrl = "relhorasupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'relhoras', TRUE);

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

			// Get basic search values
			$this->LoadBasicSearchValues();

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

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

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

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

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
		$this->BuildSearchSql($sWhere, $this->id_lancamento, FALSE); // id_lancamento
		$this->BuildSearchSql($sWhere, $this->id_usuario, FALSE); // id_usuario
		$this->BuildSearchSql($sWhere, $this->ddmmyyyy, FALSE); // ddmmyyyy
		$this->BuildSearchSql($sWhere, $this->ddmm, FALSE); // ddmm
		$this->BuildSearchSql($sWhere, $this->dia, FALSE); // dia
		$this->BuildSearchSql($sWhere, $this->mes, FALSE); // mes
		$this->BuildSearchSql($sWhere, $this->ano, FALSE); // ano
		$this->BuildSearchSql($sWhere, $this->id_projeto, FALSE); // id_projeto
		$this->BuildSearchSql($sWhere, $this->id_tarefa, FALSE); // id_tarefa
		$this->BuildSearchSql($sWhere, $this->titulo, FALSE); // titulo
		$this->BuildSearchSql($sWhere, $this->qt_horas, FALSE); // qt_horas
		$this->BuildSearchSql($sWhere, $this->obs, FALSE); // obs
		$this->BuildSearchSql($sWhere, $this->tp_tarefa, FALSE); // tp_tarefa
		$this->BuildSearchSql($sWhere, $this->situacao, FALSE); // situacao

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->id_lancamento->AdvancedSearch->Save(); // id_lancamento
			$this->id_usuario->AdvancedSearch->Save(); // id_usuario
			$this->ddmmyyyy->AdvancedSearch->Save(); // ddmmyyyy
			$this->ddmm->AdvancedSearch->Save(); // ddmm
			$this->dia->AdvancedSearch->Save(); // dia
			$this->mes->AdvancedSearch->Save(); // mes
			$this->ano->AdvancedSearch->Save(); // ano
			$this->id_projeto->AdvancedSearch->Save(); // id_projeto
			$this->id_tarefa->AdvancedSearch->Save(); // id_tarefa
			$this->titulo->AdvancedSearch->Save(); // titulo
			$this->qt_horas->AdvancedSearch->Save(); // qt_horas
			$this->obs->AdvancedSearch->Save(); // obs
			$this->tp_tarefa->AdvancedSearch->Save(); // tp_tarefa
			$this->situacao->AdvancedSearch->Save(); // situacao
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

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->titulo, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->obs, $Keyword);
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
		if ($this->id_lancamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_usuario->AdvancedSearch->IssetSession())
			return TRUE;
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
		if ($this->id_projeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_tarefa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->titulo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qt_horas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->obs->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tp_tarefa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->situacao->AdvancedSearch->IssetSession())
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

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->id_lancamento->AdvancedSearch->UnsetSession();
		$this->id_usuario->AdvancedSearch->UnsetSession();
		$this->ddmmyyyy->AdvancedSearch->UnsetSession();
		$this->ddmm->AdvancedSearch->UnsetSession();
		$this->dia->AdvancedSearch->UnsetSession();
		$this->mes->AdvancedSearch->UnsetSession();
		$this->ano->AdvancedSearch->UnsetSession();
		$this->id_projeto->AdvancedSearch->UnsetSession();
		$this->id_tarefa->AdvancedSearch->UnsetSession();
		$this->titulo->AdvancedSearch->UnsetSession();
		$this->qt_horas->AdvancedSearch->UnsetSession();
		$this->obs->AdvancedSearch->UnsetSession();
		$this->tp_tarefa->AdvancedSearch->UnsetSession();
		$this->situacao->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id_lancamento->AdvancedSearch->Load();
		$this->id_usuario->AdvancedSearch->Load();
		$this->ddmmyyyy->AdvancedSearch->Load();
		$this->ddmm->AdvancedSearch->Load();
		$this->dia->AdvancedSearch->Load();
		$this->mes->AdvancedSearch->Load();
		$this->ano->AdvancedSearch->Load();
		$this->id_projeto->AdvancedSearch->Load();
		$this->id_tarefa->AdvancedSearch->Load();
		$this->titulo->AdvancedSearch->Load();
		$this->qt_horas->AdvancedSearch->Load();
		$this->obs->AdvancedSearch->Load();
		$this->tp_tarefa->AdvancedSearch->Load();
		$this->situacao->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id_usuario, $bCtrl); // id_usuario
			$this->UpdateSort($this->dia, $bCtrl); // dia
			$this->UpdateSort($this->mes, $bCtrl); // mes
			$this->UpdateSort($this->ano, $bCtrl); // ano
			$this->UpdateSort($this->id_projeto, $bCtrl); // id_projeto
			$this->UpdateSort($this->id_tarefa, $bCtrl); // id_tarefa
			$this->UpdateSort($this->titulo, $bCtrl); // titulo
			$this->UpdateSort($this->qt_horas, $bCtrl); // qt_horas
			$this->UpdateSort($this->tp_tarefa, $bCtrl); // tp_tarefa
			$this->UpdateSort($this->situacao, $bCtrl); // situacao
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
				$this->id_usuario->setSort("");
				$this->dia->setSort("");
				$this->mes->setSort("");
				$this->ano->setSort("");
				$this->id_projeto->setSort("");
				$this->id_tarefa->setSort("");
				$this->titulo->setSort("");
				$this->qt_horas->setSort("");
				$this->tp_tarefa->setSort("");
				$this->situacao->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.frelhoraslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id_lancamento

		$this->id_lancamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_lancamento"]);
		if ($this->id_lancamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_lancamento->AdvancedSearch->SearchOperator = @$_GET["z_id_lancamento"];

		// id_usuario
		$this->id_usuario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_usuario"]);
		if ($this->id_usuario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_usuario->AdvancedSearch->SearchOperator = @$_GET["z_id_usuario"];

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

		// id_projeto
		$this->id_projeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_projeto"]);
		if ($this->id_projeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_projeto->AdvancedSearch->SearchOperator = @$_GET["z_id_projeto"];

		// id_tarefa
		$this->id_tarefa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_tarefa"]);
		if ($this->id_tarefa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_tarefa->AdvancedSearch->SearchOperator = @$_GET["z_id_tarefa"];

		// titulo
		$this->titulo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_titulo"]);
		if ($this->titulo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->titulo->AdvancedSearch->SearchOperator = @$_GET["z_titulo"];

		// qt_horas
		$this->qt_horas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_qt_horas"]);
		if ($this->qt_horas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->qt_horas->AdvancedSearch->SearchOperator = @$_GET["z_qt_horas"];

		// obs
		$this->obs->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_obs"]);
		if ($this->obs->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->obs->AdvancedSearch->SearchOperator = @$_GET["z_obs"];

		// tp_tarefa
		$this->tp_tarefa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_tp_tarefa"]);
		if ($this->tp_tarefa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->tp_tarefa->AdvancedSearch->SearchOperator = @$_GET["z_tp_tarefa"];

		// situacao
		$this->situacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_situacao"]);
		if ($this->situacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->situacao->AdvancedSearch->SearchOperator = @$_GET["z_situacao"];
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
		$this->id_lancamento->setDbValue($rs->fields('id_lancamento'));
		$this->id_usuario->setDbValue($rs->fields('id_usuario'));
		$this->ddmmyyyy->setDbValue($rs->fields('ddmmyyyy'));
		$this->ddmm->setDbValue($rs->fields('ddmm'));
		$this->dia->setDbValue($rs->fields('dia'));
		$this->mes->setDbValue($rs->fields('mes'));
		$this->ano->setDbValue($rs->fields('ano'));
		$this->id_projeto->setDbValue($rs->fields('id_projeto'));
		$this->id_tarefa->setDbValue($rs->fields('id_tarefa'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
		$this->obs->setDbValue($rs->fields('obs'));
		$this->tp_tarefa->setDbValue($rs->fields('tp_tarefa'));
		$this->situacao->setDbValue($rs->fields('situacao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_lancamento->DbValue = $row['id_lancamento'];
		$this->id_usuario->DbValue = $row['id_usuario'];
		$this->ddmmyyyy->DbValue = $row['ddmmyyyy'];
		$this->ddmm->DbValue = $row['ddmm'];
		$this->dia->DbValue = $row['dia'];
		$this->mes->DbValue = $row['mes'];
		$this->ano->DbValue = $row['ano'];
		$this->id_projeto->DbValue = $row['id_projeto'];
		$this->id_tarefa->DbValue = $row['id_tarefa'];
		$this->titulo->DbValue = $row['titulo'];
		$this->qt_horas->DbValue = $row['qt_horas'];
		$this->obs->DbValue = $row['obs'];
		$this->tp_tarefa->DbValue = $row['tp_tarefa'];
		$this->situacao->DbValue = $row['situacao'];
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
		// id_lancamento
		// id_usuario
		// ddmmyyyy
		// ddmm
		// dia
		// mes
		// ano
		// id_projeto
		// id_tarefa
		// titulo
		// qt_horas
		// obs
		// tp_tarefa
		// situacao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_lancamento
			$this->id_lancamento->ViewValue = $this->id_lancamento->CurrentValue;
			$this->id_lancamento->ViewCustomAttributes = "";

			// id_usuario
			if (strval($this->id_usuario->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_usuario->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_usuario, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_usuario->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_usuario->ViewValue = $this->id_usuario->CurrentValue;
				}
			} else {
				$this->id_usuario->ViewValue = NULL;
			}
			$this->id_usuario->ViewCustomAttributes = "";

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
			if (strval($this->mes->CurrentValue) <> "") {
				switch ($this->mes->CurrentValue) {
					case $this->mes->FldTagValue(1):
						$this->mes->ViewValue = $this->mes->FldTagCaption(1) <> "" ? $this->mes->FldTagCaption(1) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(2):
						$this->mes->ViewValue = $this->mes->FldTagCaption(2) <> "" ? $this->mes->FldTagCaption(2) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(3):
						$this->mes->ViewValue = $this->mes->FldTagCaption(3) <> "" ? $this->mes->FldTagCaption(3) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(4):
						$this->mes->ViewValue = $this->mes->FldTagCaption(4) <> "" ? $this->mes->FldTagCaption(4) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(5):
						$this->mes->ViewValue = $this->mes->FldTagCaption(5) <> "" ? $this->mes->FldTagCaption(5) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(6):
						$this->mes->ViewValue = $this->mes->FldTagCaption(6) <> "" ? $this->mes->FldTagCaption(6) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(7):
						$this->mes->ViewValue = $this->mes->FldTagCaption(7) <> "" ? $this->mes->FldTagCaption(7) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(8):
						$this->mes->ViewValue = $this->mes->FldTagCaption(8) <> "" ? $this->mes->FldTagCaption(8) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(9):
						$this->mes->ViewValue = $this->mes->FldTagCaption(9) <> "" ? $this->mes->FldTagCaption(9) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(10):
						$this->mes->ViewValue = $this->mes->FldTagCaption(10) <> "" ? $this->mes->FldTagCaption(10) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(11):
						$this->mes->ViewValue = $this->mes->FldTagCaption(11) <> "" ? $this->mes->FldTagCaption(11) : $this->mes->CurrentValue;
						break;
					case $this->mes->FldTagValue(12):
						$this->mes->ViewValue = $this->mes->FldTagCaption(12) <> "" ? $this->mes->FldTagCaption(12) : $this->mes->CurrentValue;
						break;
					default:
						$this->mes->ViewValue = $this->mes->CurrentValue;
				}
			} else {
				$this->mes->ViewValue = NULL;
			}
			$this->mes->ViewCustomAttributes = "";

			// ano
			if (strval($this->ano->CurrentValue) <> "") {
				switch ($this->ano->CurrentValue) {
					case $this->ano->FldTagValue(1):
						$this->ano->ViewValue = $this->ano->FldTagCaption(1) <> "" ? $this->ano->FldTagCaption(1) : $this->ano->CurrentValue;
						break;
					case $this->ano->FldTagValue(2):
						$this->ano->ViewValue = $this->ano->FldTagCaption(2) <> "" ? $this->ano->FldTagCaption(2) : $this->ano->CurrentValue;
						break;
					case $this->ano->FldTagValue(3):
						$this->ano->ViewValue = $this->ano->FldTagCaption(3) <> "" ? $this->ano->FldTagCaption(3) : $this->ano->CurrentValue;
						break;
					case $this->ano->FldTagValue(4):
						$this->ano->ViewValue = $this->ano->FldTagCaption(4) <> "" ? $this->ano->FldTagCaption(4) : $this->ano->CurrentValue;
						break;
					case $this->ano->FldTagValue(5):
						$this->ano->ViewValue = $this->ano->FldTagCaption(5) <> "" ? $this->ano->FldTagCaption(5) : $this->ano->CurrentValue;
						break;
					case $this->ano->FldTagValue(6):
						$this->ano->ViewValue = $this->ano->FldTagCaption(6) <> "" ? $this->ano->FldTagCaption(6) : $this->ano->CurrentValue;
						break;
					case $this->ano->FldTagValue(7):
						$this->ano->ViewValue = $this->ano->FldTagCaption(7) <> "" ? $this->ano->FldTagCaption(7) : $this->ano->CurrentValue;
						break;
					case $this->ano->FldTagValue(8):
						$this->ano->ViewValue = $this->ano->FldTagCaption(8) <> "" ? $this->ano->FldTagCaption(8) : $this->ano->CurrentValue;
						break;
					case $this->ano->FldTagValue(9):
						$this->ano->ViewValue = $this->ano->FldTagCaption(9) <> "" ? $this->ano->FldTagCaption(9) : $this->ano->CurrentValue;
						break;
					default:
						$this->ano->ViewValue = $this->ano->CurrentValue;
				}
			} else {
				$this->ano->ViewValue = NULL;
			}
			$this->ano->ViewCustomAttributes = "";

			// id_projeto
			if (strval($this->id_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_projeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_projeto->ViewValue = $this->id_projeto->CurrentValue;
				}
			} else {
				$this->id_projeto->ViewValue = NULL;
			}
			$this->id_projeto->ViewCustomAttributes = "";

			// id_tarefa
			$this->id_tarefa->ViewValue = $this->id_tarefa->CurrentValue;
			$this->id_tarefa->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// qt_horas
			$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
			$this->qt_horas->ViewCustomAttributes = "";

			// tp_tarefa
			if (strval($this->tp_tarefa->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->tp_tarefa->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_rastreador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->tp_tarefa, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->tp_tarefa->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->tp_tarefa->ViewValue = $this->tp_tarefa->CurrentValue;
				}
			} else {
				$this->tp_tarefa->ViewValue = NULL;
			}
			$this->tp_tarefa->ViewCustomAttributes = "";

			// situacao
			if (strval($this->situacao->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->situacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_sttarefa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->situacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->situacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->situacao->ViewValue = $this->situacao->CurrentValue;
				}
			} else {
				$this->situacao->ViewValue = NULL;
			}
			$this->situacao->ViewCustomAttributes = "";

			// id_usuario
			$this->id_usuario->LinkCustomAttributes = "";
			$this->id_usuario->HrefValue = "";
			$this->id_usuario->TooltipValue = "";

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

			// id_projeto
			$this->id_projeto->LinkCustomAttributes = "";
			$this->id_projeto->HrefValue = "";
			$this->id_projeto->TooltipValue = "";

			// id_tarefa
			$this->id_tarefa->LinkCustomAttributes = "";
			$this->id_tarefa->HrefValue = "";
			$this->id_tarefa->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// qt_horas
			$this->qt_horas->LinkCustomAttributes = "";
			$this->qt_horas->HrefValue = "";
			$this->qt_horas->TooltipValue = "";

			// tp_tarefa
			$this->tp_tarefa->LinkCustomAttributes = "";
			$this->tp_tarefa->HrefValue = "";
			$this->tp_tarefa->TooltipValue = "";

			// situacao
			$this->situacao->LinkCustomAttributes = "";
			$this->situacao->HrefValue = "";
			$this->situacao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id_usuario
			$this->id_usuario->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[rdm_usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_usuario, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_usuario->EditValue = $arwrk;

			// dia
			$this->dia->EditCustomAttributes = "";
			$this->dia->EditValue = ew_HtmlEncode($this->dia->AdvancedSearch->SearchValue);
			$this->dia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dia->FldCaption()));
			$this->dia->EditCustomAttributes = "";
			$this->dia->EditValue2 = ew_HtmlEncode($this->dia->AdvancedSearch->SearchValue2);
			$this->dia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dia->FldCaption()));

			// mes
			$this->mes->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->mes->FldTagValue(1), $this->mes->FldTagCaption(1) <> "" ? $this->mes->FldTagCaption(1) : $this->mes->FldTagValue(1));
			$arwrk[] = array($this->mes->FldTagValue(2), $this->mes->FldTagCaption(2) <> "" ? $this->mes->FldTagCaption(2) : $this->mes->FldTagValue(2));
			$arwrk[] = array($this->mes->FldTagValue(3), $this->mes->FldTagCaption(3) <> "" ? $this->mes->FldTagCaption(3) : $this->mes->FldTagValue(3));
			$arwrk[] = array($this->mes->FldTagValue(4), $this->mes->FldTagCaption(4) <> "" ? $this->mes->FldTagCaption(4) : $this->mes->FldTagValue(4));
			$arwrk[] = array($this->mes->FldTagValue(5), $this->mes->FldTagCaption(5) <> "" ? $this->mes->FldTagCaption(5) : $this->mes->FldTagValue(5));
			$arwrk[] = array($this->mes->FldTagValue(6), $this->mes->FldTagCaption(6) <> "" ? $this->mes->FldTagCaption(6) : $this->mes->FldTagValue(6));
			$arwrk[] = array($this->mes->FldTagValue(7), $this->mes->FldTagCaption(7) <> "" ? $this->mes->FldTagCaption(7) : $this->mes->FldTagValue(7));
			$arwrk[] = array($this->mes->FldTagValue(8), $this->mes->FldTagCaption(8) <> "" ? $this->mes->FldTagCaption(8) : $this->mes->FldTagValue(8));
			$arwrk[] = array($this->mes->FldTagValue(9), $this->mes->FldTagCaption(9) <> "" ? $this->mes->FldTagCaption(9) : $this->mes->FldTagValue(9));
			$arwrk[] = array($this->mes->FldTagValue(10), $this->mes->FldTagCaption(10) <> "" ? $this->mes->FldTagCaption(10) : $this->mes->FldTagValue(10));
			$arwrk[] = array($this->mes->FldTagValue(11), $this->mes->FldTagCaption(11) <> "" ? $this->mes->FldTagCaption(11) : $this->mes->FldTagValue(11));
			$arwrk[] = array($this->mes->FldTagValue(12), $this->mes->FldTagCaption(12) <> "" ? $this->mes->FldTagCaption(12) : $this->mes->FldTagValue(12));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->mes->EditValue = $arwrk;
			$this->mes->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->mes->FldTagValue(1), $this->mes->FldTagCaption(1) <> "" ? $this->mes->FldTagCaption(1) : $this->mes->FldTagValue(1));
			$arwrk[] = array($this->mes->FldTagValue(2), $this->mes->FldTagCaption(2) <> "" ? $this->mes->FldTagCaption(2) : $this->mes->FldTagValue(2));
			$arwrk[] = array($this->mes->FldTagValue(3), $this->mes->FldTagCaption(3) <> "" ? $this->mes->FldTagCaption(3) : $this->mes->FldTagValue(3));
			$arwrk[] = array($this->mes->FldTagValue(4), $this->mes->FldTagCaption(4) <> "" ? $this->mes->FldTagCaption(4) : $this->mes->FldTagValue(4));
			$arwrk[] = array($this->mes->FldTagValue(5), $this->mes->FldTagCaption(5) <> "" ? $this->mes->FldTagCaption(5) : $this->mes->FldTagValue(5));
			$arwrk[] = array($this->mes->FldTagValue(6), $this->mes->FldTagCaption(6) <> "" ? $this->mes->FldTagCaption(6) : $this->mes->FldTagValue(6));
			$arwrk[] = array($this->mes->FldTagValue(7), $this->mes->FldTagCaption(7) <> "" ? $this->mes->FldTagCaption(7) : $this->mes->FldTagValue(7));
			$arwrk[] = array($this->mes->FldTagValue(8), $this->mes->FldTagCaption(8) <> "" ? $this->mes->FldTagCaption(8) : $this->mes->FldTagValue(8));
			$arwrk[] = array($this->mes->FldTagValue(9), $this->mes->FldTagCaption(9) <> "" ? $this->mes->FldTagCaption(9) : $this->mes->FldTagValue(9));
			$arwrk[] = array($this->mes->FldTagValue(10), $this->mes->FldTagCaption(10) <> "" ? $this->mes->FldTagCaption(10) : $this->mes->FldTagValue(10));
			$arwrk[] = array($this->mes->FldTagValue(11), $this->mes->FldTagCaption(11) <> "" ? $this->mes->FldTagCaption(11) : $this->mes->FldTagValue(11));
			$arwrk[] = array($this->mes->FldTagValue(12), $this->mes->FldTagCaption(12) <> "" ? $this->mes->FldTagCaption(12) : $this->mes->FldTagValue(12));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->mes->EditValue2 = $arwrk;

			// ano
			$this->ano->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ano->FldTagValue(1), $this->ano->FldTagCaption(1) <> "" ? $this->ano->FldTagCaption(1) : $this->ano->FldTagValue(1));
			$arwrk[] = array($this->ano->FldTagValue(2), $this->ano->FldTagCaption(2) <> "" ? $this->ano->FldTagCaption(2) : $this->ano->FldTagValue(2));
			$arwrk[] = array($this->ano->FldTagValue(3), $this->ano->FldTagCaption(3) <> "" ? $this->ano->FldTagCaption(3) : $this->ano->FldTagValue(3));
			$arwrk[] = array($this->ano->FldTagValue(4), $this->ano->FldTagCaption(4) <> "" ? $this->ano->FldTagCaption(4) : $this->ano->FldTagValue(4));
			$arwrk[] = array($this->ano->FldTagValue(5), $this->ano->FldTagCaption(5) <> "" ? $this->ano->FldTagCaption(5) : $this->ano->FldTagValue(5));
			$arwrk[] = array($this->ano->FldTagValue(6), $this->ano->FldTagCaption(6) <> "" ? $this->ano->FldTagCaption(6) : $this->ano->FldTagValue(6));
			$arwrk[] = array($this->ano->FldTagValue(7), $this->ano->FldTagCaption(7) <> "" ? $this->ano->FldTagCaption(7) : $this->ano->FldTagValue(7));
			$arwrk[] = array($this->ano->FldTagValue(8), $this->ano->FldTagCaption(8) <> "" ? $this->ano->FldTagCaption(8) : $this->ano->FldTagValue(8));
			$arwrk[] = array($this->ano->FldTagValue(9), $this->ano->FldTagCaption(9) <> "" ? $this->ano->FldTagCaption(9) : $this->ano->FldTagValue(9));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ano->EditValue = $arwrk;
			$this->ano->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ano->FldTagValue(1), $this->ano->FldTagCaption(1) <> "" ? $this->ano->FldTagCaption(1) : $this->ano->FldTagValue(1));
			$arwrk[] = array($this->ano->FldTagValue(2), $this->ano->FldTagCaption(2) <> "" ? $this->ano->FldTagCaption(2) : $this->ano->FldTagValue(2));
			$arwrk[] = array($this->ano->FldTagValue(3), $this->ano->FldTagCaption(3) <> "" ? $this->ano->FldTagCaption(3) : $this->ano->FldTagValue(3));
			$arwrk[] = array($this->ano->FldTagValue(4), $this->ano->FldTagCaption(4) <> "" ? $this->ano->FldTagCaption(4) : $this->ano->FldTagValue(4));
			$arwrk[] = array($this->ano->FldTagValue(5), $this->ano->FldTagCaption(5) <> "" ? $this->ano->FldTagCaption(5) : $this->ano->FldTagValue(5));
			$arwrk[] = array($this->ano->FldTagValue(6), $this->ano->FldTagCaption(6) <> "" ? $this->ano->FldTagCaption(6) : $this->ano->FldTagValue(6));
			$arwrk[] = array($this->ano->FldTagValue(7), $this->ano->FldTagCaption(7) <> "" ? $this->ano->FldTagCaption(7) : $this->ano->FldTagValue(7));
			$arwrk[] = array($this->ano->FldTagValue(8), $this->ano->FldTagCaption(8) <> "" ? $this->ano->FldTagCaption(8) : $this->ano->FldTagValue(8));
			$arwrk[] = array($this->ano->FldTagValue(9), $this->ano->FldTagCaption(9) <> "" ? $this->ano->FldTagCaption(9) : $this->ano->FldTagValue(9));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ano->EditValue2 = $arwrk;

			// id_projeto
			$this->id_projeto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[rdm_projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->id_projeto->EditValue = $arwrk;

			// id_tarefa
			$this->id_tarefa->EditCustomAttributes = "";
			$this->id_tarefa->EditValue = ew_HtmlEncode($this->id_tarefa->AdvancedSearch->SearchValue);
			$this->id_tarefa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->id_tarefa->FldCaption()));

			// titulo
			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = ew_HtmlEncode($this->titulo->AdvancedSearch->SearchValue);
			$this->titulo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->titulo->FldCaption()));

			// qt_horas
			$this->qt_horas->EditCustomAttributes = "";
			$this->qt_horas->EditValue = ew_HtmlEncode($this->qt_horas->AdvancedSearch->SearchValue);
			$this->qt_horas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_horas->FldCaption()));

			// tp_tarefa
			$this->tp_tarefa->EditCustomAttributes = "";

			// situacao
			$this->situacao->EditCustomAttributes = "";
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
		if (!ew_CheckInteger($this->dia->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->dia->FldErrMsg());
		}
		if (!ew_CheckInteger($this->dia->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->dia->FldErrMsg());
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
		$this->id_lancamento->AdvancedSearch->Load();
		$this->id_usuario->AdvancedSearch->Load();
		$this->ddmmyyyy->AdvancedSearch->Load();
		$this->ddmm->AdvancedSearch->Load();
		$this->dia->AdvancedSearch->Load();
		$this->mes->AdvancedSearch->Load();
		$this->ano->AdvancedSearch->Load();
		$this->id_projeto->AdvancedSearch->Load();
		$this->id_tarefa->AdvancedSearch->Load();
		$this->titulo->AdvancedSearch->Load();
		$this->qt_horas->AdvancedSearch->Load();
		$this->obs->AdvancedSearch->Load();
		$this->tp_tarefa->AdvancedSearch->Load();
		$this->situacao->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_relhoras\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_relhoras',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.frelhoraslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->id_lancamento); // id_lancamento
		$this->AddSearchQueryString($sQry, $this->id_usuario); // id_usuario
		$this->AddSearchQueryString($sQry, $this->ddmmyyyy); // ddmmyyyy
		$this->AddSearchQueryString($sQry, $this->ddmm); // ddmm
		$this->AddSearchQueryString($sQry, $this->dia); // dia
		$this->AddSearchQueryString($sQry, $this->mes); // mes
		$this->AddSearchQueryString($sQry, $this->ano); // ano
		$this->AddSearchQueryString($sQry, $this->id_projeto); // id_projeto
		$this->AddSearchQueryString($sQry, $this->id_tarefa); // id_tarefa
		$this->AddSearchQueryString($sQry, $this->titulo); // titulo
		$this->AddSearchQueryString($sQry, $this->qt_horas); // qt_horas
		$this->AddSearchQueryString($sQry, $this->obs); // obs
		$this->AddSearchQueryString($sQry, $this->tp_tarefa); // tp_tarefa
		$this->AddSearchQueryString($sQry, $this->situacao); // situacao

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
if (!isset($relhoras_list)) $relhoras_list = new crelhoras_list();

// Page init
$relhoras_list->Page_Init();

// Page main
$relhoras_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$relhoras_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($relhoras->Export == "") { ?>
<script type="text/javascript">

// Page object
var relhoras_list = new ew_Page("relhoras_list");
relhoras_list.PageID = "list"; // Page ID
var EW_PAGE_ID = relhoras_list.PageID; // For backward compatibility

// Form object
var frelhoraslist = new ew_Form("frelhoraslist");
frelhoraslist.FormKeyCountName = '<?php echo $relhoras_list->FormKeyCountName ?>';

// Form_CustomValidate event
frelhoraslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frelhoraslist.ValidateRequired = true;
<?php } else { ?>
frelhoraslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frelhoraslist.Lists["x_id_usuario"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frelhoraslist.Lists["x_id_projeto"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frelhoraslist.Lists["x_tp_tarefa"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frelhoraslist.Lists["x_situacao"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var frelhoraslistsrch = new ew_Form("frelhoraslistsrch");

// Validate function for search
frelhoraslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_dia");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($relhoras->dia->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
frelhoraslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frelhoraslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
frelhoraslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
frelhoraslistsrch.Lists["x_id_usuario"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frelhoraslistsrch.Lists["x_id_projeto"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (frelhoraslistsrch) frelhoraslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($relhoras->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($relhoras_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $relhoras_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$relhoras_list->TotalRecs = $relhoras->SelectRecordCount();
	} else {
		if ($relhoras_list->Recordset = $relhoras_list->LoadRecordset())
			$relhoras_list->TotalRecs = $relhoras_list->Recordset->RecordCount();
	}
	$relhoras_list->StartRec = 1;
	if ($relhoras_list->DisplayRecs <= 0 || ($relhoras->Export <> "" && $relhoras->ExportAll)) // Display all records
		$relhoras_list->DisplayRecs = $relhoras_list->TotalRecs;
	if (!($relhoras->Export <> "" && $relhoras->ExportAll))
		$relhoras_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$relhoras_list->Recordset = $relhoras_list->LoadRecordset($relhoras_list->StartRec-1, $relhoras_list->DisplayRecs);
$relhoras_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($relhoras->Export == "" && $relhoras->CurrentAction == "") { ?>
<form name="frelhoraslistsrch" id="frelhoraslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="frelhoraslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#frelhoraslistsrch_SearchGroup" href="#frelhoraslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="frelhoraslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="frelhoraslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="relhoras">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$relhoras_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$relhoras->RowType = EW_ROWTYPE_SEARCH;

// Render row
$relhoras->ResetAttrs();
$relhoras_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($relhoras->id_usuario->Visible) { // id_usuario ?>
	<span id="xsc_id_usuario" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relhoras->id_usuario->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_usuario" id="z_id_usuario" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_id_usuario" id="x_id_usuario" name="x_id_usuario"<?php echo $relhoras->id_usuario->EditAttributes() ?>>
<?php
if (is_array($relhoras->id_usuario->EditValue)) {
	$arwrk = $relhoras->id_usuario->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($relhoras->id_usuario->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
frelhoraslistsrch.Lists["x_id_usuario"].Options = <?php echo (is_array($relhoras->id_usuario->EditValue)) ? ew_ArrayToJson($relhoras->id_usuario->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($relhoras->dia->Visible) { // dia ?>
	<span id="xsc_dia" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relhoras->dia->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_dia" id="z_dia" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dia" name="x_dia" id="x_dia" size="30" placeholder="<?php echo $relhoras->dia->PlaceHolder ?>" value="<?php echo $relhoras->dia->EditValue ?>"<?php echo $relhoras->dia->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_dia">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_dia">
<input type="text" data-field="x_dia" name="y_dia" id="y_dia" size="30" placeholder="<?php echo $relhoras->dia->PlaceHolder ?>" value="<?php echo $relhoras->dia->EditValue2 ?>"<?php echo $relhoras->dia->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($relhoras->mes->Visible) { // mes ?>
	<span id="xsc_mes" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relhoras->mes->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_mes" id="z_mes" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<select data-field="x_mes" id="x_mes" name="x_mes"<?php echo $relhoras->mes->EditAttributes() ?>>
<?php
if (is_array($relhoras->mes->EditValue)) {
	$arwrk = $relhoras->mes->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($relhoras->mes->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
		<span class="ewSearchCond btw1_mes">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_mes">
<select data-field="x_mes" id="y_mes" name="y_mes"<?php echo $relhoras->mes->EditAttributes() ?>>
<?php
if (is_array($relhoras->mes->EditValue2)) {
	$arwrk = $relhoras->mes->EditValue2;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($relhoras->mes->AdvancedSearch->SearchValue2) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($relhoras->ano->Visible) { // ano ?>
	<span id="xsc_ano" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relhoras->ano->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_ano" id="z_ano" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ano" id="x_ano" name="x_ano"<?php echo $relhoras->ano->EditAttributes() ?>>
<?php
if (is_array($relhoras->ano->EditValue)) {
	$arwrk = $relhoras->ano->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($relhoras->ano->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
		<span class="ewSearchCond btw1_ano">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_ano">
<select data-field="x_ano" id="y_ano" name="y_ano"<?php echo $relhoras->ano->EditAttributes() ?>>
<?php
if (is_array($relhoras->ano->EditValue2)) {
	$arwrk = $relhoras->ano->EditValue2;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($relhoras->ano->AdvancedSearch->SearchValue2) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($relhoras->id_projeto->Visible) { // id_projeto ?>
	<span id="xsc_id_projeto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $relhoras->id_projeto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id_projeto" id="z_id_projeto" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_id_projeto" id="x_id_projeto" name="x_id_projeto"<?php echo $relhoras->id_projeto->EditAttributes() ?>>
<?php
if (is_array($relhoras->id_projeto->EditValue)) {
	$arwrk = $relhoras->id_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($relhoras->id_projeto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
frelhoraslistsrch.Lists["x_id_projeto"].Options = <?php echo (is_array($relhoras->id_projeto->EditValue)) ? ew_ArrayToJson($relhoras->id_projeto->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($relhoras_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $relhoras_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_7" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($relhoras_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($relhoras_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($relhoras_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $relhoras_list->ShowPageHeader(); ?>
<?php
$relhoras_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="frelhoraslist" id="frelhoraslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="relhoras">
<div id="gmp_relhoras" class="ewGridMiddlePanel">
<?php if ($relhoras_list->TotalRecs > 0) { ?>
<table id="tbl_relhoraslist" class="ewTable ewTableSeparate">
<?php echo $relhoras->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$relhoras_list->RenderListOptions();

// Render list options (header, left)
$relhoras_list->ListOptions->Render("header", "left");
?>
<?php if ($relhoras->id_usuario->Visible) { // id_usuario ?>
	<?php if ($relhoras->SortUrl($relhoras->id_usuario) == "") { ?>
		<td><div id="elh_relhoras_id_usuario" class="relhoras_id_usuario"><div class="ewTableHeaderCaption"><?php echo $relhoras->id_usuario->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->id_usuario) ?>',2);"><div id="elh_relhoras_id_usuario" class="relhoras_id_usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->id_usuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->id_usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->id_usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relhoras->dia->Visible) { // dia ?>
	<?php if ($relhoras->SortUrl($relhoras->dia) == "") { ?>
		<td><div id="elh_relhoras_dia" class="relhoras_dia"><div class="ewTableHeaderCaption"><?php echo $relhoras->dia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->dia) ?>',2);"><div id="elh_relhoras_dia" class="relhoras_dia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->dia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->dia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->dia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relhoras->mes->Visible) { // mes ?>
	<?php if ($relhoras->SortUrl($relhoras->mes) == "") { ?>
		<td><div id="elh_relhoras_mes" class="relhoras_mes"><div class="ewTableHeaderCaption"><?php echo $relhoras->mes->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->mes) ?>',2);"><div id="elh_relhoras_mes" class="relhoras_mes">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->mes->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->mes->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->mes->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relhoras->ano->Visible) { // ano ?>
	<?php if ($relhoras->SortUrl($relhoras->ano) == "") { ?>
		<td><div id="elh_relhoras_ano" class="relhoras_ano"><div class="ewTableHeaderCaption"><?php echo $relhoras->ano->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->ano) ?>',2);"><div id="elh_relhoras_ano" class="relhoras_ano">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->ano->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->ano->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->ano->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relhoras->id_projeto->Visible) { // id_projeto ?>
	<?php if ($relhoras->SortUrl($relhoras->id_projeto) == "") { ?>
		<td><div id="elh_relhoras_id_projeto" class="relhoras_id_projeto"><div class="ewTableHeaderCaption"><?php echo $relhoras->id_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->id_projeto) ?>',2);"><div id="elh_relhoras_id_projeto" class="relhoras_id_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->id_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->id_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->id_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relhoras->id_tarefa->Visible) { // id_tarefa ?>
	<?php if ($relhoras->SortUrl($relhoras->id_tarefa) == "") { ?>
		<td><div id="elh_relhoras_id_tarefa" class="relhoras_id_tarefa"><div class="ewTableHeaderCaption"><?php echo $relhoras->id_tarefa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->id_tarefa) ?>',2);"><div id="elh_relhoras_id_tarefa" class="relhoras_id_tarefa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->id_tarefa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->id_tarefa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->id_tarefa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relhoras->titulo->Visible) { // titulo ?>
	<?php if ($relhoras->SortUrl($relhoras->titulo) == "") { ?>
		<td><div id="elh_relhoras_titulo" class="relhoras_titulo"><div class="ewTableHeaderCaption"><?php echo $relhoras->titulo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->titulo) ?>',2);"><div id="elh_relhoras_titulo" class="relhoras_titulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->titulo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->titulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->titulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relhoras->qt_horas->Visible) { // qt_horas ?>
	<?php if ($relhoras->SortUrl($relhoras->qt_horas) == "") { ?>
		<td><div id="elh_relhoras_qt_horas" class="relhoras_qt_horas"><div class="ewTableHeaderCaption"><?php echo $relhoras->qt_horas->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->qt_horas) ?>',2);"><div id="elh_relhoras_qt_horas" class="relhoras_qt_horas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->qt_horas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->qt_horas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->qt_horas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relhoras->tp_tarefa->Visible) { // tp_tarefa ?>
	<?php if ($relhoras->SortUrl($relhoras->tp_tarefa) == "") { ?>
		<td><div id="elh_relhoras_tp_tarefa" class="relhoras_tp_tarefa"><div class="ewTableHeaderCaption"><?php echo $relhoras->tp_tarefa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->tp_tarefa) ?>',2);"><div id="elh_relhoras_tp_tarefa" class="relhoras_tp_tarefa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->tp_tarefa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->tp_tarefa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->tp_tarefa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($relhoras->situacao->Visible) { // situacao ?>
	<?php if ($relhoras->SortUrl($relhoras->situacao) == "") { ?>
		<td><div id="elh_relhoras_situacao" class="relhoras_situacao"><div class="ewTableHeaderCaption"><?php echo $relhoras->situacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $relhoras->SortUrl($relhoras->situacao) ?>',2);"><div id="elh_relhoras_situacao" class="relhoras_situacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $relhoras->situacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($relhoras->situacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($relhoras->situacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$relhoras_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($relhoras->ExportAll && $relhoras->Export <> "") {
	$relhoras_list->StopRec = $relhoras_list->TotalRecs;
} else {

	// Set the last record to display
	if ($relhoras_list->TotalRecs > $relhoras_list->StartRec + $relhoras_list->DisplayRecs - 1)
		$relhoras_list->StopRec = $relhoras_list->StartRec + $relhoras_list->DisplayRecs - 1;
	else
		$relhoras_list->StopRec = $relhoras_list->TotalRecs;
}
$relhoras_list->RecCnt = $relhoras_list->StartRec - 1;
if ($relhoras_list->Recordset && !$relhoras_list->Recordset->EOF) {
	$relhoras_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $relhoras_list->StartRec > 1)
		$relhoras_list->Recordset->Move($relhoras_list->StartRec - 1);
} elseif (!$relhoras->AllowAddDeleteRow && $relhoras_list->StopRec == 0) {
	$relhoras_list->StopRec = $relhoras->GridAddRowCount;
}

// Initialize aggregate
$relhoras->RowType = EW_ROWTYPE_AGGREGATEINIT;
$relhoras->ResetAttrs();
$relhoras_list->RenderRow();
while ($relhoras_list->RecCnt < $relhoras_list->StopRec) {
	$relhoras_list->RecCnt++;
	if (intval($relhoras_list->RecCnt) >= intval($relhoras_list->StartRec)) {
		$relhoras_list->RowCnt++;

		// Set up key count
		$relhoras_list->KeyCount = $relhoras_list->RowIndex;

		// Init row class and style
		$relhoras->ResetAttrs();
		$relhoras->CssClass = "";
		if ($relhoras->CurrentAction == "gridadd") {
		} else {
			$relhoras_list->LoadRowValues($relhoras_list->Recordset); // Load row values
		}
		$relhoras->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$relhoras->RowAttrs = array_merge($relhoras->RowAttrs, array('data-rowindex'=>$relhoras_list->RowCnt, 'id'=>'r' . $relhoras_list->RowCnt . '_relhoras', 'data-rowtype'=>$relhoras->RowType));

		// Render row
		$relhoras_list->RenderRow();

		// Render list options
		$relhoras_list->RenderListOptions();
?>
	<tr<?php echo $relhoras->RowAttributes() ?>>
<?php

// Render list options (body, left)
$relhoras_list->ListOptions->Render("body", "left", $relhoras_list->RowCnt);
?>
	<?php if ($relhoras->id_usuario->Visible) { // id_usuario ?>
		<td<?php echo $relhoras->id_usuario->CellAttributes() ?>>
<span<?php echo $relhoras->id_usuario->ViewAttributes() ?>>
<?php echo $relhoras->id_usuario->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relhoras->dia->Visible) { // dia ?>
		<td<?php echo $relhoras->dia->CellAttributes() ?>>
<span<?php echo $relhoras->dia->ViewAttributes() ?>>
<?php echo $relhoras->dia->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relhoras->mes->Visible) { // mes ?>
		<td<?php echo $relhoras->mes->CellAttributes() ?>>
<span<?php echo $relhoras->mes->ViewAttributes() ?>>
<?php echo $relhoras->mes->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relhoras->ano->Visible) { // ano ?>
		<td<?php echo $relhoras->ano->CellAttributes() ?>>
<span<?php echo $relhoras->ano->ViewAttributes() ?>>
<?php echo $relhoras->ano->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relhoras->id_projeto->Visible) { // id_projeto ?>
		<td<?php echo $relhoras->id_projeto->CellAttributes() ?>>
<span<?php echo $relhoras->id_projeto->ViewAttributes() ?>>
<?php echo $relhoras->id_projeto->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relhoras->id_tarefa->Visible) { // id_tarefa ?>
		<td<?php echo $relhoras->id_tarefa->CellAttributes() ?>>
<span<?php echo $relhoras->id_tarefa->ViewAttributes() ?>>
<?php echo $relhoras->id_tarefa->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relhoras->titulo->Visible) { // titulo ?>
		<td<?php echo $relhoras->titulo->CellAttributes() ?>>
<span<?php echo $relhoras->titulo->ViewAttributes() ?>>
<?php echo $relhoras->titulo->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relhoras->qt_horas->Visible) { // qt_horas ?>
		<td<?php echo $relhoras->qt_horas->CellAttributes() ?>>
<span<?php echo $relhoras->qt_horas->ViewAttributes() ?>>
<?php echo $relhoras->qt_horas->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relhoras->tp_tarefa->Visible) { // tp_tarefa ?>
		<td<?php echo $relhoras->tp_tarefa->CellAttributes() ?>>
<span<?php echo $relhoras->tp_tarefa->ViewAttributes() ?>>
<?php echo $relhoras->tp_tarefa->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($relhoras->situacao->Visible) { // situacao ?>
		<td<?php echo $relhoras->situacao->CellAttributes() ?>>
<span<?php echo $relhoras->situacao->ViewAttributes() ?>>
<?php echo $relhoras->situacao->ListViewValue() ?></span>
<a id="<?php echo $relhoras_list->PageObjName . "_row_" . $relhoras_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$relhoras_list->ListOptions->Render("body", "right", $relhoras_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($relhoras->CurrentAction <> "gridadd")
		$relhoras_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($relhoras->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($relhoras_list->Recordset)
	$relhoras_list->Recordset->Close();
?>
<?php if ($relhoras->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($relhoras->CurrentAction <> "gridadd" && $relhoras->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($relhoras_list->Pager)) $relhoras_list->Pager = new cNumericPager($relhoras_list->StartRec, $relhoras_list->DisplayRecs, $relhoras_list->TotalRecs, $relhoras_list->RecRange) ?>
<?php if ($relhoras_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($relhoras_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $relhoras_list->PageUrl() ?>start=<?php echo $relhoras_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($relhoras_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $relhoras_list->PageUrl() ?>start=<?php echo $relhoras_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($relhoras_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $relhoras_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($relhoras_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $relhoras_list->PageUrl() ?>start=<?php echo $relhoras_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($relhoras_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $relhoras_list->PageUrl() ?>start=<?php echo $relhoras_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($relhoras_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $relhoras_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $relhoras_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $relhoras_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($relhoras_list->SearchWhere == "0=101") { ?>
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
	foreach ($relhoras_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($relhoras->Export == "") { ?>
<script type="text/javascript">
frelhoraslistsrch.Init();
frelhoraslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$relhoras_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($relhoras->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$relhoras_list->Page_Terminate();
?>
