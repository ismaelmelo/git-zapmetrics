<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "rnversoesinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$RnVersoes_list = NULL; // Initialize page object first

class cRnVersoes_list extends cRnVersoes {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'RnVersoes';

	// Page object name
	var $PageObjName = 'RnVersoes_list';

	// Grid form hidden field names
	var $FormName = 'fRnVersoeslist';
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

		// Table object (RnVersoes)
		if (!isset($GLOBALS["RnVersoes"])) {
			$GLOBALS["RnVersoes"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["RnVersoes"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "rnversoesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "rnversoesdelete.php";
		$this->MultiUpdateUrl = "rnversoesupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'RnVersoes', TRUE);

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
		$this->dt_versao->Visible = !$this->IsAddOrEdit();

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
		$this->BuildSearchSql($sWhere, $this->co_alternativo1, FALSE); // co_alternativo1
		$this->BuildSearchSql($sWhere, $this->no_sistema, FALSE); // no_sistema
		$this->BuildSearchSql($sWhere, $this->no_uc, FALSE); // no_uc
		$this->BuildSearchSql($sWhere, $this->co_alternativo, FALSE); // co_alternativo
		$this->BuildSearchSql($sWhere, $this->ic_ativo, FALSE); // ic_ativo
		$this->BuildSearchSql($sWhere, $this->co_alternativo2, FALSE); // co_alternativo2
		$this->BuildSearchSql($sWhere, $this->no_regraNegocio, FALSE); // no_regraNegocio
		$this->BuildSearchSql($sWhere, $this->ds_regraNegocio, FALSE); // ds_regraNegocio
		$this->BuildSearchSql($sWhere, $this->nu_versao, FALSE); // nu_versao
		$this->BuildSearchSql($sWhere, $this->nu_area, FALSE); // nu_area
		$this->BuildSearchSql($sWhere, $this->ds_origemRegra, FALSE); // ds_origemRegra
		$this->BuildSearchSql($sWhere, $this->nu_projeto, FALSE); // nu_projeto
		$this->BuildSearchSql($sWhere, $this->nu_fornecedor, FALSE); // nu_fornecedor
		$this->BuildSearchSql($sWhere, $this->nu_stRegraNegocio, FALSE); // nu_stRegraNegocio
		$this->BuildSearchSql($sWhere, $this->dt_versao, FALSE); // dt_versao

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->co_alternativo1->AdvancedSearch->Save(); // co_alternativo1
			$this->no_sistema->AdvancedSearch->Save(); // no_sistema
			$this->no_uc->AdvancedSearch->Save(); // no_uc
			$this->co_alternativo->AdvancedSearch->Save(); // co_alternativo
			$this->ic_ativo->AdvancedSearch->Save(); // ic_ativo
			$this->co_alternativo2->AdvancedSearch->Save(); // co_alternativo2
			$this->no_regraNegocio->AdvancedSearch->Save(); // no_regraNegocio
			$this->ds_regraNegocio->AdvancedSearch->Save(); // ds_regraNegocio
			$this->nu_versao->AdvancedSearch->Save(); // nu_versao
			$this->nu_area->AdvancedSearch->Save(); // nu_area
			$this->ds_origemRegra->AdvancedSearch->Save(); // ds_origemRegra
			$this->nu_projeto->AdvancedSearch->Save(); // nu_projeto
			$this->nu_fornecedor->AdvancedSearch->Save(); // nu_fornecedor
			$this->nu_stRegraNegocio->AdvancedSearch->Save(); // nu_stRegraNegocio
			$this->dt_versao->AdvancedSearch->Save(); // dt_versao
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
		$this->BuildBasicSearchSQL($sWhere, $this->co_alternativo1, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->no_sistema, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->no_uc, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->co_alternativo, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->ic_ativo, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->co_alternativo2, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->no_regraNegocio, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->ds_regraNegocio, $Keyword);
		if (is_numeric($Keyword)) $this->BuildBasicSearchSQL($sWhere, $this->nu_projeto, $Keyword);
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
		if ($this->co_alternativo1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_sistema->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_uc->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->co_alternativo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_ativo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->co_alternativo2->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_regraNegocio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_regraNegocio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_versao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_area->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_origemRegra->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_projeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_fornecedor->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_stRegraNegocio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_versao->AdvancedSearch->IssetSession())
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
		$this->co_alternativo1->AdvancedSearch->UnsetSession();
		$this->no_sistema->AdvancedSearch->UnsetSession();
		$this->no_uc->AdvancedSearch->UnsetSession();
		$this->co_alternativo->AdvancedSearch->UnsetSession();
		$this->ic_ativo->AdvancedSearch->UnsetSession();
		$this->co_alternativo2->AdvancedSearch->UnsetSession();
		$this->no_regraNegocio->AdvancedSearch->UnsetSession();
		$this->ds_regraNegocio->AdvancedSearch->UnsetSession();
		$this->nu_versao->AdvancedSearch->UnsetSession();
		$this->nu_area->AdvancedSearch->UnsetSession();
		$this->ds_origemRegra->AdvancedSearch->UnsetSession();
		$this->nu_projeto->AdvancedSearch->UnsetSession();
		$this->nu_fornecedor->AdvancedSearch->UnsetSession();
		$this->nu_stRegraNegocio->AdvancedSearch->UnsetSession();
		$this->dt_versao->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->co_alternativo1->AdvancedSearch->Load();
		$this->no_sistema->AdvancedSearch->Load();
		$this->no_uc->AdvancedSearch->Load();
		$this->co_alternativo->AdvancedSearch->Load();
		$this->ic_ativo->AdvancedSearch->Load();
		$this->co_alternativo2->AdvancedSearch->Load();
		$this->no_regraNegocio->AdvancedSearch->Load();
		$this->ds_regraNegocio->AdvancedSearch->Load();
		$this->nu_versao->AdvancedSearch->Load();
		$this->nu_area->AdvancedSearch->Load();
		$this->ds_origemRegra->AdvancedSearch->Load();
		$this->nu_projeto->AdvancedSearch->Load();
		$this->nu_fornecedor->AdvancedSearch->Load();
		$this->nu_stRegraNegocio->AdvancedSearch->Load();
		$this->dt_versao->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->co_alternativo1, $bCtrl); // co_alternativo1
			$this->UpdateSort($this->no_sistema, $bCtrl); // no_sistema
			$this->UpdateSort($this->no_uc, $bCtrl); // no_uc
			$this->UpdateSort($this->co_alternativo, $bCtrl); // co_alternativo
			$this->UpdateSort($this->ic_ativo, $bCtrl); // ic_ativo
			$this->UpdateSort($this->co_alternativo2, $bCtrl); // co_alternativo2
			$this->UpdateSort($this->no_regraNegocio, $bCtrl); // no_regraNegocio
			$this->UpdateSort($this->nu_versao, $bCtrl); // nu_versao
			$this->UpdateSort($this->nu_area, $bCtrl); // nu_area
			$this->UpdateSort($this->nu_projeto, $bCtrl); // nu_projeto
			$this->UpdateSort($this->nu_fornecedor, $bCtrl); // nu_fornecedor
			$this->UpdateSort($this->nu_stRegraNegocio, $bCtrl); // nu_stRegraNegocio
			$this->UpdateSort($this->dt_versao, $bCtrl); // dt_versao
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
				$this->dt_versao->setSort("DESC");
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
				$this->co_alternativo1->setSort("");
				$this->no_sistema->setSort("");
				$this->no_uc->setSort("");
				$this->co_alternativo->setSort("");
				$this->ic_ativo->setSort("");
				$this->co_alternativo2->setSort("");
				$this->no_regraNegocio->setSort("");
				$this->nu_versao->setSort("");
				$this->nu_area->setSort("");
				$this->nu_projeto->setSort("");
				$this->nu_fornecedor->setSort("");
				$this->nu_stRegraNegocio->setSort("");
				$this->dt_versao->setSort("");
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
			$option->UseDropDownButton = TRUE;
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fRnVersoeslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// co_alternativo1

		$this->co_alternativo1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_co_alternativo1"]);
		if ($this->co_alternativo1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->co_alternativo1->AdvancedSearch->SearchOperator = @$_GET["z_co_alternativo1"];

		// no_sistema
		$this->no_sistema->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_sistema"]);
		if ($this->no_sistema->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_sistema->AdvancedSearch->SearchOperator = @$_GET["z_no_sistema"];

		// no_uc
		$this->no_uc->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_uc"]);
		if ($this->no_uc->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_uc->AdvancedSearch->SearchOperator = @$_GET["z_no_uc"];

		// co_alternativo
		$this->co_alternativo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_co_alternativo"]);
		if ($this->co_alternativo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->co_alternativo->AdvancedSearch->SearchOperator = @$_GET["z_co_alternativo"];

		// ic_ativo
		$this->ic_ativo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_ativo"]);
		if ($this->ic_ativo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_ativo->AdvancedSearch->SearchOperator = @$_GET["z_ic_ativo"];

		// co_alternativo2
		$this->co_alternativo2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_co_alternativo2"]);
		if ($this->co_alternativo2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->co_alternativo2->AdvancedSearch->SearchOperator = @$_GET["z_co_alternativo2"];

		// no_regraNegocio
		$this->no_regraNegocio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_regraNegocio"]);
		if ($this->no_regraNegocio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_regraNegocio->AdvancedSearch->SearchOperator = @$_GET["z_no_regraNegocio"];

		// ds_regraNegocio
		$this->ds_regraNegocio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_regraNegocio"]);
		if ($this->ds_regraNegocio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_regraNegocio->AdvancedSearch->SearchOperator = @$_GET["z_ds_regraNegocio"];

		// nu_versao
		$this->nu_versao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_versao"]);
		if ($this->nu_versao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_versao->AdvancedSearch->SearchOperator = @$_GET["z_nu_versao"];

		// nu_area
		$this->nu_area->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_area"]);
		if ($this->nu_area->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_area->AdvancedSearch->SearchOperator = @$_GET["z_nu_area"];

		// ds_origemRegra
		$this->ds_origemRegra->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_origemRegra"]);
		if ($this->ds_origemRegra->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_origemRegra->AdvancedSearch->SearchOperator = @$_GET["z_ds_origemRegra"];

		// nu_projeto
		$this->nu_projeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_projeto"]);
		if ($this->nu_projeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_projeto->AdvancedSearch->SearchOperator = @$_GET["z_nu_projeto"];

		// nu_fornecedor
		$this->nu_fornecedor->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_fornecedor"]);
		if ($this->nu_fornecedor->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_fornecedor->AdvancedSearch->SearchOperator = @$_GET["z_nu_fornecedor"];

		// nu_stRegraNegocio
		$this->nu_stRegraNegocio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_stRegraNegocio"]);
		if ($this->nu_stRegraNegocio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_stRegraNegocio->AdvancedSearch->SearchOperator = @$_GET["z_nu_stRegraNegocio"];

		// dt_versao
		$this->dt_versao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_versao"]);
		if ($this->dt_versao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_versao->AdvancedSearch->SearchOperator = @$_GET["z_dt_versao"];
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
		$this->co_alternativo1->setDbValue($rs->fields('co_alternativo1'));
		$this->no_sistema->setDbValue($rs->fields('no_sistema'));
		$this->no_uc->setDbValue($rs->fields('no_uc'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->co_alternativo2->setDbValue($rs->fields('co_alternativo2'));
		$this->no_regraNegocio->setDbValue($rs->fields('no_regraNegocio'));
		$this->ds_regraNegocio->setDbValue($rs->fields('ds_regraNegocio'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ds_origemRegra->setDbValue($rs->fields('ds_origemRegra'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_fornecedor->setDbValue($rs->fields('nu_fornecedor'));
		$this->nu_stRegraNegocio->setDbValue($rs->fields('nu_stRegraNegocio'));
		$this->dt_versao->setDbValue($rs->fields('dt_versao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->co_alternativo1->DbValue = $row['co_alternativo1'];
		$this->no_sistema->DbValue = $row['no_sistema'];
		$this->no_uc->DbValue = $row['no_uc'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->co_alternativo2->DbValue = $row['co_alternativo2'];
		$this->no_regraNegocio->DbValue = $row['no_regraNegocio'];
		$this->ds_regraNegocio->DbValue = $row['ds_regraNegocio'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->ds_origemRegra->DbValue = $row['ds_origemRegra'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_fornecedor->DbValue = $row['nu_fornecedor'];
		$this->nu_stRegraNegocio->DbValue = $row['nu_stRegraNegocio'];
		$this->dt_versao->DbValue = $row['dt_versao'];
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
		// co_alternativo1
		// no_sistema
		// no_uc
		// co_alternativo
		// ic_ativo
		// co_alternativo2
		// no_regraNegocio
		// ds_regraNegocio
		// nu_versao
		// nu_area
		// ds_origemRegra
		// nu_projeto
		// nu_fornecedor
		// nu_stRegraNegocio
		// dt_versao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// co_alternativo1
			$this->co_alternativo1->ViewValue = $this->co_alternativo1->CurrentValue;
			$this->co_alternativo1->ViewCustomAttributes = "";

			// no_sistema
			$this->no_sistema->ViewValue = $this->no_sistema->CurrentValue;
			$this->no_sistema->ViewCustomAttributes = "";

			// no_uc
			$this->no_uc->ViewValue = $this->no_uc->CurrentValue;
			$this->no_uc->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// ic_ativo
			if (strval($this->ic_ativo->CurrentValue) <> "") {
				switch ($this->ic_ativo->CurrentValue) {
					case $this->ic_ativo->FldTagValue(1):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->CurrentValue;
						break;
					case $this->ic_ativo->FldTagValue(2):
						$this->ic_ativo->ViewValue = $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->CurrentValue;
						break;
					default:
						$this->ic_ativo->ViewValue = $this->ic_ativo->CurrentValue;
				}
			} else {
				$this->ic_ativo->ViewValue = NULL;
			}
			$this->ic_ativo->ViewCustomAttributes = "";

			// co_alternativo2
			$this->co_alternativo2->ViewValue = $this->co_alternativo2->CurrentValue;
			$this->co_alternativo2->ViewCustomAttributes = "";

			// no_regraNegocio
			$this->no_regraNegocio->ViewValue = $this->no_regraNegocio->CurrentValue;
			$this->no_regraNegocio->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// nu_area
			if (strval($this->nu_area->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_area, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_area->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_area->ViewValue = $this->nu_area->CurrentValue;
				}
			} else {
				$this->nu_area->ViewValue = NULL;
			}
			$this->nu_area->ViewCustomAttributes = "";

			// nu_projeto
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
				}
			} else {
				$this->nu_projeto->ViewValue = NULL;
			}
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_fornecedor
			if (strval($this->nu_fornecedor->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedor->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornecedor, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornecedor->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornecedor->ViewValue = $this->nu_fornecedor->CurrentValue;
				}
			} else {
				$this->nu_fornecedor->ViewValue = NULL;
			}
			$this->nu_fornecedor->ViewCustomAttributes = "";

			// nu_stRegraNegocio
			if (strval($this->nu_stRegraNegocio->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stRegraNegocio]" . ew_SearchString("=", $this->nu_stRegraNegocio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stRegraNegocio], [no_stRegraNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stregranegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stRegraNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stRegraNegocio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stRegraNegocio->ViewValue = $this->nu_stRegraNegocio->CurrentValue;
				}
			} else {
				$this->nu_stRegraNegocio->ViewValue = NULL;
			}
			$this->nu_stRegraNegocio->ViewCustomAttributes = "";

			// dt_versao
			$this->dt_versao->ViewValue = $this->dt_versao->CurrentValue;
			$this->dt_versao->ViewValue = ew_FormatDateTime($this->dt_versao->ViewValue, 7);
			$this->dt_versao->ViewCustomAttributes = "";

			// co_alternativo1
			$this->co_alternativo1->LinkCustomAttributes = "";
			$this->co_alternativo1->HrefValue = "";
			$this->co_alternativo1->TooltipValue = "";

			// no_sistema
			$this->no_sistema->LinkCustomAttributes = "";
			$this->no_sistema->HrefValue = "";
			$this->no_sistema->TooltipValue = "";

			// no_uc
			$this->no_uc->LinkCustomAttributes = "";
			$this->no_uc->HrefValue = "";
			$this->no_uc->TooltipValue = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// co_alternativo2
			$this->co_alternativo2->LinkCustomAttributes = "";
			$this->co_alternativo2->HrefValue = "";
			$this->co_alternativo2->TooltipValue = "";

			// no_regraNegocio
			$this->no_regraNegocio->LinkCustomAttributes = "";
			$this->no_regraNegocio->HrefValue = "";
			$this->no_regraNegocio->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_fornecedor
			$this->nu_fornecedor->LinkCustomAttributes = "";
			$this->nu_fornecedor->HrefValue = "";
			$this->nu_fornecedor->TooltipValue = "";

			// nu_stRegraNegocio
			$this->nu_stRegraNegocio->LinkCustomAttributes = "";
			$this->nu_stRegraNegocio->HrefValue = "";
			$this->nu_stRegraNegocio->TooltipValue = "";

			// dt_versao
			$this->dt_versao->LinkCustomAttributes = "";
			$this->dt_versao->HrefValue = "";
			$this->dt_versao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// co_alternativo1
			$this->co_alternativo1->EditCustomAttributes = "";
			$this->co_alternativo1->EditValue = ew_HtmlEncode($this->co_alternativo1->AdvancedSearch->SearchValue);
			$this->co_alternativo1->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo1->FldCaption()));

			// no_sistema
			$this->no_sistema->EditCustomAttributes = "";
			$this->no_sistema->EditValue = ew_HtmlEncode($this->no_sistema->AdvancedSearch->SearchValue);
			$this->no_sistema->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_sistema->FldCaption()));

			// no_uc
			$this->no_uc->EditCustomAttributes = "";
			$this->no_uc->EditValue = ew_HtmlEncode($this->no_uc->AdvancedSearch->SearchValue);
			$this->no_uc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_uc->FldCaption()));

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "";
			$this->co_alternativo->EditValue = ew_HtmlEncode($this->co_alternativo->AdvancedSearch->SearchValue);
			$this->co_alternativo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;

			// co_alternativo2
			$this->co_alternativo2->EditCustomAttributes = "";
			$this->co_alternativo2->EditValue = ew_HtmlEncode($this->co_alternativo2->AdvancedSearch->SearchValue);
			$this->co_alternativo2->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_alternativo2->FldCaption()));

			// no_regraNegocio
			$this->no_regraNegocio->EditCustomAttributes = "";
			$this->no_regraNegocio->EditValue = ew_HtmlEncode($this->no_regraNegocio->AdvancedSearch->SearchValue);
			$this->no_regraNegocio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_regraNegocio->FldCaption()));

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "readonly";
			$this->nu_versao->EditValue = ew_HtmlEncode($this->nu_versao->AdvancedSearch->SearchValue);
			$this->nu_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versao->FldCaption()));

			// nu_area
			$this->nu_area->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_area, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_area->EditValue = $arwrk;

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_projeto->EditValue = $arwrk;

			// nu_fornecedor
			$this->nu_fornecedor->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornecedor, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_fornecedor->EditValue = $arwrk;

			// nu_stRegraNegocio
			$this->nu_stRegraNegocio->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stRegraNegocio], [no_stRegraNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stregranegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stRegraNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stRegraNegocio->EditValue = $arwrk;

			// dt_versao
			$this->dt_versao->EditCustomAttributes = "";
			$this->dt_versao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_versao->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_versao->FldCaption()));
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
		if (!ew_CheckInteger($this->nu_versao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_versao->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dt_versao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->dt_versao->FldErrMsg());
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
		$this->co_alternativo1->AdvancedSearch->Load();
		$this->no_sistema->AdvancedSearch->Load();
		$this->no_uc->AdvancedSearch->Load();
		$this->co_alternativo->AdvancedSearch->Load();
		$this->ic_ativo->AdvancedSearch->Load();
		$this->co_alternativo2->AdvancedSearch->Load();
		$this->no_regraNegocio->AdvancedSearch->Load();
		$this->ds_regraNegocio->AdvancedSearch->Load();
		$this->nu_versao->AdvancedSearch->Load();
		$this->nu_area->AdvancedSearch->Load();
		$this->ds_origemRegra->AdvancedSearch->Load();
		$this->nu_projeto->AdvancedSearch->Load();
		$this->nu_fornecedor->AdvancedSearch->Load();
		$this->nu_stRegraNegocio->AdvancedSearch->Load();
		$this->dt_versao->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_RnVersoes\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_RnVersoes',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fRnVersoeslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->co_alternativo1); // co_alternativo1
		$this->AddSearchQueryString($sQry, $this->no_sistema); // no_sistema
		$this->AddSearchQueryString($sQry, $this->no_uc); // no_uc
		$this->AddSearchQueryString($sQry, $this->co_alternativo); // co_alternativo
		$this->AddSearchQueryString($sQry, $this->ic_ativo); // ic_ativo
		$this->AddSearchQueryString($sQry, $this->co_alternativo2); // co_alternativo2
		$this->AddSearchQueryString($sQry, $this->no_regraNegocio); // no_regraNegocio
		$this->AddSearchQueryString($sQry, $this->ds_regraNegocio); // ds_regraNegocio
		$this->AddSearchQueryString($sQry, $this->nu_versao); // nu_versao
		$this->AddSearchQueryString($sQry, $this->nu_area); // nu_area
		$this->AddSearchQueryString($sQry, $this->ds_origemRegra); // ds_origemRegra
		$this->AddSearchQueryString($sQry, $this->nu_projeto); // nu_projeto
		$this->AddSearchQueryString($sQry, $this->nu_fornecedor); // nu_fornecedor
		$this->AddSearchQueryString($sQry, $this->nu_stRegraNegocio); // nu_stRegraNegocio
		$this->AddSearchQueryString($sQry, $this->dt_versao); // dt_versao

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
if (!isset($RnVersoes_list)) $RnVersoes_list = new cRnVersoes_list();

// Page init
$RnVersoes_list->Page_Init();

// Page main
$RnVersoes_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$RnVersoes_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($RnVersoes->Export == "") { ?>
<script type="text/javascript">

// Page object
var RnVersoes_list = new ew_Page("RnVersoes_list");
RnVersoes_list.PageID = "list"; // Page ID
var EW_PAGE_ID = RnVersoes_list.PageID; // For backward compatibility

// Form object
var fRnVersoeslist = new ew_Form("fRnVersoeslist");
fRnVersoeslist.FormKeyCountName = '<?php echo $RnVersoes_list->FormKeyCountName ?>';

// Form_CustomValidate event
fRnVersoeslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fRnVersoeslist.ValidateRequired = true;
<?php } else { ?>
fRnVersoeslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fRnVersoeslist.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fRnVersoeslist.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fRnVersoeslist.Lists["x_nu_fornecedor"] = {"LinkField":"x_nu_fornecedor","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_fornecedor","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fRnVersoeslist.Lists["x_nu_stRegraNegocio"] = {"LinkField":"x_nu_stRegraNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stRegraNegocio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fRnVersoeslistsrch = new ew_Form("fRnVersoeslistsrch");

// Validate function for search
fRnVersoeslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_versao");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($RnVersoes->nu_versao->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_dt_versao");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($RnVersoes->dt_versao->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fRnVersoeslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fRnVersoeslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fRnVersoeslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fRnVersoeslistsrch.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fRnVersoeslistsrch.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fRnVersoeslistsrch.Lists["x_nu_fornecedor"] = {"LinkField":"x_nu_fornecedor","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_fornecedor","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fRnVersoeslistsrch.Lists["x_nu_stRegraNegocio"] = {"LinkField":"x_nu_stRegraNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stRegraNegocio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fRnVersoeslistsrch) fRnVersoeslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($RnVersoes->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($RnVersoes_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $RnVersoes_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$RnVersoes_list->TotalRecs = $RnVersoes->SelectRecordCount();
	} else {
		if ($RnVersoes_list->Recordset = $RnVersoes_list->LoadRecordset())
			$RnVersoes_list->TotalRecs = $RnVersoes_list->Recordset->RecordCount();
	}
	$RnVersoes_list->StartRec = 1;
	if ($RnVersoes_list->DisplayRecs <= 0 || ($RnVersoes->Export <> "" && $RnVersoes->ExportAll)) // Display all records
		$RnVersoes_list->DisplayRecs = $RnVersoes_list->TotalRecs;
	if (!($RnVersoes->Export <> "" && $RnVersoes->ExportAll))
		$RnVersoes_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$RnVersoes_list->Recordset = $RnVersoes_list->LoadRecordset($RnVersoes_list->StartRec-1, $RnVersoes_list->DisplayRecs);
$RnVersoes_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($RnVersoes->Export == "" && $RnVersoes->CurrentAction == "") { ?>
<form name="fRnVersoeslistsrch" id="fRnVersoeslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fRnVersoeslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fRnVersoeslistsrch_SearchGroup" href="#fRnVersoeslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fRnVersoeslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fRnVersoeslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="RnVersoes">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$RnVersoes_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$RnVersoes->RowType = EW_ROWTYPE_SEARCH;

// Render row
$RnVersoes->ResetAttrs();
$RnVersoes_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($RnVersoes->no_uc->Visible) { // no_uc ?>
	<span id="xsc_no_uc" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->no_uc->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_uc" id="z_no_uc" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_uc" name="x_no_uc" id="x_no_uc" size="30" maxlength="120" placeholder="<?php echo $RnVersoes->no_uc->PlaceHolder ?>" value="<?php echo $RnVersoes->no_uc->EditValue ?>"<?php echo $RnVersoes->no_uc->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($RnVersoes->co_alternativo->Visible) { // co_alternativo ?>
	<span id="xsc_co_alternativo" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->co_alternativo->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_co_alternativo" id="z_co_alternativo" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_co_alternativo" name="x_co_alternativo" id="x_co_alternativo" size="30" maxlength="20" placeholder="<?php echo $RnVersoes->co_alternativo->PlaceHolder ?>" value="<?php echo $RnVersoes->co_alternativo->EditValue ?>"<?php echo $RnVersoes->co_alternativo->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($RnVersoes->ic_ativo->Visible) { // ic_ativo ?>
	<span id="xsc_ic_ativo" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->ic_ativo->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_ativo" id="z_ic_ativo" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $RnVersoes->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $RnVersoes->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($RnVersoes->ic_ativo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $RnVersoes->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($RnVersoes->no_regraNegocio->Visible) { // no_regraNegocio ?>
	<span id="xsc_no_regraNegocio" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->no_regraNegocio->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_regraNegocio" id="z_no_regraNegocio" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_regraNegocio" name="x_no_regraNegocio" id="x_no_regraNegocio" size="30" maxlength="150" placeholder="<?php echo $RnVersoes->no_regraNegocio->PlaceHolder ?>" value="<?php echo $RnVersoes->no_regraNegocio->EditValue ?>"<?php echo $RnVersoes->no_regraNegocio->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($RnVersoes->nu_versao->Visible) { // nu_versao ?>
	<span id="xsc_nu_versao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->nu_versao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_versao" id="z_nu_versao" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_versao" name="x_nu_versao" id="x_nu_versao" size="30" placeholder="<?php echo $RnVersoes->nu_versao->PlaceHolder ?>" value="<?php echo $RnVersoes->nu_versao->EditValue ?>"<?php echo $RnVersoes->nu_versao->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($RnVersoes->nu_area->Visible) { // nu_area ?>
	<span id="xsc_nu_area" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->nu_area->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_area" id="z_nu_area" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_area" id="x_nu_area" name="x_nu_area"<?php echo $RnVersoes->nu_area->EditAttributes() ?>>
<?php
if (is_array($RnVersoes->nu_area->EditValue)) {
	$arwrk = $RnVersoes->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($RnVersoes->nu_area->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fRnVersoeslistsrch.Lists["x_nu_area"].Options = <?php echo (is_array($RnVersoes->nu_area->EditValue)) ? ew_ArrayToJson($RnVersoes->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($RnVersoes->nu_projeto->Visible) { // nu_projeto ?>
	<span id="xsc_nu_projeto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->nu_projeto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_projeto" id="z_nu_projeto" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_projeto" id="x_nu_projeto" name="x_nu_projeto"<?php echo $RnVersoes->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($RnVersoes->nu_projeto->EditValue)) {
	$arwrk = $RnVersoes->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($RnVersoes->nu_projeto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fRnVersoeslistsrch.Lists["x_nu_projeto"].Options = <?php echo (is_array($RnVersoes->nu_projeto->EditValue)) ? ew_ArrayToJson($RnVersoes->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
<?php if ($RnVersoes->nu_fornecedor->Visible) { // nu_fornecedor ?>
	<span id="xsc_nu_fornecedor" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->nu_fornecedor->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_fornecedor" id="z_nu_fornecedor" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_fornecedor" id="x_nu_fornecedor" name="x_nu_fornecedor"<?php echo $RnVersoes->nu_fornecedor->EditAttributes() ?>>
<?php
if (is_array($RnVersoes->nu_fornecedor->EditValue)) {
	$arwrk = $RnVersoes->nu_fornecedor->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($RnVersoes->nu_fornecedor->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fRnVersoeslistsrch.Lists["x_nu_fornecedor"].Options = <?php echo (is_array($RnVersoes->nu_fornecedor->EditValue)) ? ew_ArrayToJson($RnVersoes->nu_fornecedor->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_9" class="ewRow">
<?php if ($RnVersoes->nu_stRegraNegocio->Visible) { // nu_stRegraNegocio ?>
	<span id="xsc_nu_stRegraNegocio" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->nu_stRegraNegocio->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_stRegraNegocio" id="z_nu_stRegraNegocio" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_stRegraNegocio" id="x_nu_stRegraNegocio" name="x_nu_stRegraNegocio"<?php echo $RnVersoes->nu_stRegraNegocio->EditAttributes() ?>>
<?php
if (is_array($RnVersoes->nu_stRegraNegocio->EditValue)) {
	$arwrk = $RnVersoes->nu_stRegraNegocio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($RnVersoes->nu_stRegraNegocio->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fRnVersoeslistsrch.Lists["x_nu_stRegraNegocio"].Options = <?php echo (is_array($RnVersoes->nu_stRegraNegocio->EditValue)) ? ew_ArrayToJson($RnVersoes->nu_stRegraNegocio->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_10" class="ewRow">
<?php if ($RnVersoes->dt_versao->Visible) { // dt_versao ?>
	<span id="xsc_dt_versao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $RnVersoes->dt_versao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_dt_versao" id="z_dt_versao" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dt_versao" name="x_dt_versao" id="x_dt_versao" placeholder="<?php echo $RnVersoes->dt_versao->PlaceHolder ?>" value="<?php echo $RnVersoes->dt_versao->EditValue ?>"<?php echo $RnVersoes->dt_versao->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_11" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($RnVersoes_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $RnVersoes_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_12" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($RnVersoes_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($RnVersoes_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($RnVersoes_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $RnVersoes_list->ShowPageHeader(); ?>
<?php
$RnVersoes_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fRnVersoeslist" id="fRnVersoeslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="RnVersoes">
<div id="gmp_RnVersoes" class="ewGridMiddlePanel">
<?php if ($RnVersoes_list->TotalRecs > 0) { ?>
<table id="tbl_RnVersoeslist" class="ewTable ewTableSeparate">
<?php echo $RnVersoes->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$RnVersoes_list->RenderListOptions();

// Render list options (header, left)
$RnVersoes_list->ListOptions->Render("header", "left");
?>
<?php if ($RnVersoes->co_alternativo1->Visible) { // co_alternativo1 ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->co_alternativo1) == "") { ?>
		<td><div id="elh_RnVersoes_co_alternativo1" class="RnVersoes_co_alternativo1"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->co_alternativo1->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->co_alternativo1) ?>',2);"><div id="elh_RnVersoes_co_alternativo1" class="RnVersoes_co_alternativo1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->co_alternativo1->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->co_alternativo1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->co_alternativo1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->no_sistema->Visible) { // no_sistema ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->no_sistema) == "") { ?>
		<td><div id="elh_RnVersoes_no_sistema" class="RnVersoes_no_sistema"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->no_sistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->no_sistema) ?>',2);"><div id="elh_RnVersoes_no_sistema" class="RnVersoes_no_sistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->no_sistema->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->no_sistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->no_sistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->no_uc->Visible) { // no_uc ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->no_uc) == "") { ?>
		<td><div id="elh_RnVersoes_no_uc" class="RnVersoes_no_uc"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->no_uc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->no_uc) ?>',2);"><div id="elh_RnVersoes_no_uc" class="RnVersoes_no_uc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->no_uc->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->no_uc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->no_uc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->co_alternativo->Visible) { // co_alternativo ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->co_alternativo) == "") { ?>
		<td><div id="elh_RnVersoes_co_alternativo" class="RnVersoes_co_alternativo"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->co_alternativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->co_alternativo) ?>',2);"><div id="elh_RnVersoes_co_alternativo" class="RnVersoes_co_alternativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->co_alternativo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->co_alternativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->co_alternativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->ic_ativo) == "") { ?>
		<td><div id="elh_RnVersoes_ic_ativo" class="RnVersoes_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->ic_ativo) ?>',2);"><div id="elh_RnVersoes_ic_ativo" class="RnVersoes_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->co_alternativo2->Visible) { // co_alternativo2 ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->co_alternativo2) == "") { ?>
		<td><div id="elh_RnVersoes_co_alternativo2" class="RnVersoes_co_alternativo2"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->co_alternativo2->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->co_alternativo2) ?>',2);"><div id="elh_RnVersoes_co_alternativo2" class="RnVersoes_co_alternativo2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->co_alternativo2->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->co_alternativo2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->co_alternativo2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->no_regraNegocio->Visible) { // no_regraNegocio ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->no_regraNegocio) == "") { ?>
		<td><div id="elh_RnVersoes_no_regraNegocio" class="RnVersoes_no_regraNegocio"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->no_regraNegocio->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->no_regraNegocio) ?>',2);"><div id="elh_RnVersoes_no_regraNegocio" class="RnVersoes_no_regraNegocio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->no_regraNegocio->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->no_regraNegocio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->no_regraNegocio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->nu_versao->Visible) { // nu_versao ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->nu_versao) == "") { ?>
		<td><div id="elh_RnVersoes_nu_versao" class="RnVersoes_nu_versao"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->nu_versao) ?>',2);"><div id="elh_RnVersoes_nu_versao" class="RnVersoes_nu_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->nu_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->nu_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->nu_area->Visible) { // nu_area ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->nu_area) == "") { ?>
		<td><div id="elh_RnVersoes_nu_area" class="RnVersoes_nu_area"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_area->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->nu_area) ?>',2);"><div id="elh_RnVersoes_nu_area" class="RnVersoes_nu_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_area->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->nu_area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->nu_area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->nu_projeto->Visible) { // nu_projeto ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->nu_projeto) == "") { ?>
		<td><div id="elh_RnVersoes_nu_projeto" class="RnVersoes_nu_projeto"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->nu_projeto) ?>',2);"><div id="elh_RnVersoes_nu_projeto" class="RnVersoes_nu_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->nu_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->nu_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->nu_fornecedor->Visible) { // nu_fornecedor ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->nu_fornecedor) == "") { ?>
		<td><div id="elh_RnVersoes_nu_fornecedor" class="RnVersoes_nu_fornecedor"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_fornecedor->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->nu_fornecedor) ?>',2);"><div id="elh_RnVersoes_nu_fornecedor" class="RnVersoes_nu_fornecedor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_fornecedor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->nu_fornecedor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->nu_fornecedor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->nu_stRegraNegocio->Visible) { // nu_stRegraNegocio ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->nu_stRegraNegocio) == "") { ?>
		<td><div id="elh_RnVersoes_nu_stRegraNegocio" class="RnVersoes_nu_stRegraNegocio"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_stRegraNegocio->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->nu_stRegraNegocio) ?>',2);"><div id="elh_RnVersoes_nu_stRegraNegocio" class="RnVersoes_nu_stRegraNegocio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->nu_stRegraNegocio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->nu_stRegraNegocio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->nu_stRegraNegocio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($RnVersoes->dt_versao->Visible) { // dt_versao ?>
	<?php if ($RnVersoes->SortUrl($RnVersoes->dt_versao) == "") { ?>
		<td><div id="elh_RnVersoes_dt_versao" class="RnVersoes_dt_versao"><div class="ewTableHeaderCaption"><?php echo $RnVersoes->dt_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $RnVersoes->SortUrl($RnVersoes->dt_versao) ?>',2);"><div id="elh_RnVersoes_dt_versao" class="RnVersoes_dt_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $RnVersoes->dt_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($RnVersoes->dt_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($RnVersoes->dt_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$RnVersoes_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($RnVersoes->ExportAll && $RnVersoes->Export <> "") {
	$RnVersoes_list->StopRec = $RnVersoes_list->TotalRecs;
} else {

	// Set the last record to display
	if ($RnVersoes_list->TotalRecs > $RnVersoes_list->StartRec + $RnVersoes_list->DisplayRecs - 1)
		$RnVersoes_list->StopRec = $RnVersoes_list->StartRec + $RnVersoes_list->DisplayRecs - 1;
	else
		$RnVersoes_list->StopRec = $RnVersoes_list->TotalRecs;
}
$RnVersoes_list->RecCnt = $RnVersoes_list->StartRec - 1;
if ($RnVersoes_list->Recordset && !$RnVersoes_list->Recordset->EOF) {
	$RnVersoes_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $RnVersoes_list->StartRec > 1)
		$RnVersoes_list->Recordset->Move($RnVersoes_list->StartRec - 1);
} elseif (!$RnVersoes->AllowAddDeleteRow && $RnVersoes_list->StopRec == 0) {
	$RnVersoes_list->StopRec = $RnVersoes->GridAddRowCount;
}

// Initialize aggregate
$RnVersoes->RowType = EW_ROWTYPE_AGGREGATEINIT;
$RnVersoes->ResetAttrs();
$RnVersoes_list->RenderRow();
while ($RnVersoes_list->RecCnt < $RnVersoes_list->StopRec) {
	$RnVersoes_list->RecCnt++;
	if (intval($RnVersoes_list->RecCnt) >= intval($RnVersoes_list->StartRec)) {
		$RnVersoes_list->RowCnt++;

		// Set up key count
		$RnVersoes_list->KeyCount = $RnVersoes_list->RowIndex;

		// Init row class and style
		$RnVersoes->ResetAttrs();
		$RnVersoes->CssClass = "";
		if ($RnVersoes->CurrentAction == "gridadd") {
		} else {
			$RnVersoes_list->LoadRowValues($RnVersoes_list->Recordset); // Load row values
		}
		$RnVersoes->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$RnVersoes->RowAttrs = array_merge($RnVersoes->RowAttrs, array('data-rowindex'=>$RnVersoes_list->RowCnt, 'id'=>'r' . $RnVersoes_list->RowCnt . '_RnVersoes', 'data-rowtype'=>$RnVersoes->RowType));

		// Render row
		$RnVersoes_list->RenderRow();

		// Render list options
		$RnVersoes_list->RenderListOptions();
?>
	<tr<?php echo $RnVersoes->RowAttributes() ?>>
<?php

// Render list options (body, left)
$RnVersoes_list->ListOptions->Render("body", "left", $RnVersoes_list->RowCnt);
?>
	<?php if ($RnVersoes->co_alternativo1->Visible) { // co_alternativo1 ?>
		<td<?php echo $RnVersoes->co_alternativo1->CellAttributes() ?>>
<span<?php echo $RnVersoes->co_alternativo1->ViewAttributes() ?>>
<?php echo $RnVersoes->co_alternativo1->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->no_sistema->Visible) { // no_sistema ?>
		<td<?php echo $RnVersoes->no_sistema->CellAttributes() ?>>
<span<?php echo $RnVersoes->no_sistema->ViewAttributes() ?>>
<?php echo $RnVersoes->no_sistema->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->no_uc->Visible) { // no_uc ?>
		<td<?php echo $RnVersoes->no_uc->CellAttributes() ?>>
<span<?php echo $RnVersoes->no_uc->ViewAttributes() ?>>
<?php echo $RnVersoes->no_uc->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->co_alternativo->Visible) { // co_alternativo ?>
		<td<?php echo $RnVersoes->co_alternativo->CellAttributes() ?>>
<span<?php echo $RnVersoes->co_alternativo->ViewAttributes() ?>>
<?php echo $RnVersoes->co_alternativo->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $RnVersoes->ic_ativo->CellAttributes() ?>>
<span<?php echo $RnVersoes->ic_ativo->ViewAttributes() ?>>
<?php echo $RnVersoes->ic_ativo->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->co_alternativo2->Visible) { // co_alternativo2 ?>
		<td<?php echo $RnVersoes->co_alternativo2->CellAttributes() ?>>
<span<?php echo $RnVersoes->co_alternativo2->ViewAttributes() ?>>
<?php echo $RnVersoes->co_alternativo2->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->no_regraNegocio->Visible) { // no_regraNegocio ?>
		<td<?php echo $RnVersoes->no_regraNegocio->CellAttributes() ?>>
<span<?php echo $RnVersoes->no_regraNegocio->ViewAttributes() ?>>
<?php echo $RnVersoes->no_regraNegocio->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->nu_versao->Visible) { // nu_versao ?>
		<td<?php echo $RnVersoes->nu_versao->CellAttributes() ?>>
<span<?php echo $RnVersoes->nu_versao->ViewAttributes() ?>>
<?php echo $RnVersoes->nu_versao->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->nu_area->Visible) { // nu_area ?>
		<td<?php echo $RnVersoes->nu_area->CellAttributes() ?>>
<span<?php echo $RnVersoes->nu_area->ViewAttributes() ?>>
<?php echo $RnVersoes->nu_area->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->nu_projeto->Visible) { // nu_projeto ?>
		<td<?php echo $RnVersoes->nu_projeto->CellAttributes() ?>>
<span<?php echo $RnVersoes->nu_projeto->ViewAttributes() ?>>
<?php echo $RnVersoes->nu_projeto->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->nu_fornecedor->Visible) { // nu_fornecedor ?>
		<td<?php echo $RnVersoes->nu_fornecedor->CellAttributes() ?>>
<span<?php echo $RnVersoes->nu_fornecedor->ViewAttributes() ?>>
<?php echo $RnVersoes->nu_fornecedor->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->nu_stRegraNegocio->Visible) { // nu_stRegraNegocio ?>
		<td<?php echo $RnVersoes->nu_stRegraNegocio->CellAttributes() ?>>
<span<?php echo $RnVersoes->nu_stRegraNegocio->ViewAttributes() ?>>
<?php echo $RnVersoes->nu_stRegraNegocio->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($RnVersoes->dt_versao->Visible) { // dt_versao ?>
		<td<?php echo $RnVersoes->dt_versao->CellAttributes() ?>>
<span<?php echo $RnVersoes->dt_versao->ViewAttributes() ?>>
<?php echo $RnVersoes->dt_versao->ListViewValue() ?></span>
<a id="<?php echo $RnVersoes_list->PageObjName . "_row_" . $RnVersoes_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$RnVersoes_list->ListOptions->Render("body", "right", $RnVersoes_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($RnVersoes->CurrentAction <> "gridadd")
		$RnVersoes_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($RnVersoes->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($RnVersoes_list->Recordset)
	$RnVersoes_list->Recordset->Close();
?>
<?php if ($RnVersoes->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($RnVersoes->CurrentAction <> "gridadd" && $RnVersoes->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($RnVersoes_list->Pager)) $RnVersoes_list->Pager = new cNumericPager($RnVersoes_list->StartRec, $RnVersoes_list->DisplayRecs, $RnVersoes_list->TotalRecs, $RnVersoes_list->RecRange) ?>
<?php if ($RnVersoes_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($RnVersoes_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $RnVersoes_list->PageUrl() ?>start=<?php echo $RnVersoes_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($RnVersoes_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $RnVersoes_list->PageUrl() ?>start=<?php echo $RnVersoes_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($RnVersoes_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $RnVersoes_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($RnVersoes_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $RnVersoes_list->PageUrl() ?>start=<?php echo $RnVersoes_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($RnVersoes_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $RnVersoes_list->PageUrl() ?>start=<?php echo $RnVersoes_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($RnVersoes_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $RnVersoes_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $RnVersoes_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $RnVersoes_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($RnVersoes_list->SearchWhere == "0=101") { ?>
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
	foreach ($RnVersoes_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($RnVersoes->Export == "") { ?>
<script type="text/javascript">
fRnVersoeslistsrch.Init();
fRnVersoeslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$RnVersoes_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($RnVersoes->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$RnVersoes_list->Page_Terminate();
?>
