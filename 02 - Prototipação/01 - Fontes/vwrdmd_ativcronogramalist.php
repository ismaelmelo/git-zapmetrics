<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "vwrdmd_ativcronogramainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$vwrdmd_ativCronograma_list = NULL; // Initialize page object first

class cvwrdmd_ativCronograma_list extends cvwrdmd_ativCronograma {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0602B820-DE72-4661-BB21-3716ACE9CB5F}";

	// Table name
	var $TableName = 'vwrdmd_ativCronograma';

	// Page object name
	var $PageObjName = 'vwrdmd_ativCronograma_list';

	// Grid form hidden field names
	var $FormName = 'fvwrdmd_ativCronogramalist';
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

		// Table object (vwrdmd_ativCronograma)
		if (!isset($GLOBALS["vwrdmd_ativCronograma"])) {
			$GLOBALS["vwrdmd_ativCronograma"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vwrdmd_ativCronograma"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vwrdmd_ativcronogramaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vwrdmd_ativcronogramadelete.php";
		$this->MultiUpdateUrl = "vwrdmd_ativcronogramaupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vwrdmd_ativCronograma', TRUE);

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
		$this->qt_horasReal->Visible = !$this->IsAddOrEdit();
		$this->qt_horasEstimada->Visible = !$this->IsAddOrEdit();

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
	var $DisplayRecs = 20;
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
			$this->DisplayRecs = 20; // Load default
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
		$this->BuildSearchSql($sWhere, $this->nu_projeto, FALSE); // nu_projeto
		$this->BuildSearchSql($sWhere, $this->nu_versao, FALSE); // nu_versao
		$this->BuildSearchSql($sWhere, $this->nu_tarefaPai, FALSE); // nu_tarefaPai
		$this->BuildSearchSql($sWhere, $this->nu_tarefa, FALSE); // nu_tarefa
		$this->BuildSearchSql($sWhere, $this->no_tarefa, FALSE); // no_tarefa
		$this->BuildSearchSql($sWhere, $this->nu_catAtividade, FALSE); // nu_catAtividade
		$this->BuildSearchSql($sWhere, $this->nu_situacao, FALSE); // nu_situacao
		$this->BuildSearchSql($sWhere, $this->qt_horasReal, FALSE); // qt_horasReal
		$this->BuildSearchSql($sWhere, $this->qt_horasEstimada, FALSE); // qt_horasEstimada
		$this->BuildSearchSql($sWhere, $this->nu_autor, FALSE); // nu_autor
		$this->BuildSearchSql($sWhere, $this->no_autor, FALSE); // no_autor
		$this->BuildSearchSql($sWhere, $this->nu_responsavel, FALSE); // nu_responsavel
		$this->BuildSearchSql($sWhere, $this->no_responsavel, FALSE); // no_responsavel

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_projeto->AdvancedSearch->Save(); // nu_projeto
			$this->nu_versao->AdvancedSearch->Save(); // nu_versao
			$this->nu_tarefaPai->AdvancedSearch->Save(); // nu_tarefaPai
			$this->nu_tarefa->AdvancedSearch->Save(); // nu_tarefa
			$this->no_tarefa->AdvancedSearch->Save(); // no_tarefa
			$this->nu_catAtividade->AdvancedSearch->Save(); // nu_catAtividade
			$this->nu_situacao->AdvancedSearch->Save(); // nu_situacao
			$this->qt_horasReal->AdvancedSearch->Save(); // qt_horasReal
			$this->qt_horasEstimada->AdvancedSearch->Save(); // qt_horasEstimada
			$this->nu_autor->AdvancedSearch->Save(); // nu_autor
			$this->no_autor->AdvancedSearch->Save(); // no_autor
			$this->nu_responsavel->AdvancedSearch->Save(); // nu_responsavel
			$this->no_responsavel->AdvancedSearch->Save(); // no_responsavel
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
		$this->BuildBasicSearchSQL($sWhere, $this->no_tarefa, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->no_autor, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->no_responsavel, $Keyword);
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
		if ($this->nu_projeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_versao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_tarefaPai->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_tarefa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_tarefa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_catAtividade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_situacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qt_horasReal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qt_horasEstimada->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_autor->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_autor->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_responsavel->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_responsavel->AdvancedSearch->IssetSession())
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
		$this->nu_projeto->AdvancedSearch->UnsetSession();
		$this->nu_versao->AdvancedSearch->UnsetSession();
		$this->nu_tarefaPai->AdvancedSearch->UnsetSession();
		$this->nu_tarefa->AdvancedSearch->UnsetSession();
		$this->no_tarefa->AdvancedSearch->UnsetSession();
		$this->nu_catAtividade->AdvancedSearch->UnsetSession();
		$this->nu_situacao->AdvancedSearch->UnsetSession();
		$this->qt_horasReal->AdvancedSearch->UnsetSession();
		$this->qt_horasEstimada->AdvancedSearch->UnsetSession();
		$this->nu_autor->AdvancedSearch->UnsetSession();
		$this->no_autor->AdvancedSearch->UnsetSession();
		$this->nu_responsavel->AdvancedSearch->UnsetSession();
		$this->no_responsavel->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->nu_projeto->AdvancedSearch->Load();
		$this->nu_versao->AdvancedSearch->Load();
		$this->nu_tarefaPai->AdvancedSearch->Load();
		$this->nu_tarefa->AdvancedSearch->Load();
		$this->no_tarefa->AdvancedSearch->Load();
		$this->nu_catAtividade->AdvancedSearch->Load();
		$this->nu_situacao->AdvancedSearch->Load();
		$this->qt_horasReal->AdvancedSearch->Load();
		$this->qt_horasEstimada->AdvancedSearch->Load();
		$this->nu_autor->AdvancedSearch->Load();
		$this->no_autor->AdvancedSearch->Load();
		$this->nu_responsavel->AdvancedSearch->Load();
		$this->no_responsavel->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_projeto, $bCtrl); // nu_projeto
			$this->UpdateSort($this->nu_versao, $bCtrl); // nu_versao
			$this->UpdateSort($this->nu_tarefaPai, $bCtrl); // nu_tarefaPai
			$this->UpdateSort($this->nu_tarefa, $bCtrl); // nu_tarefa
			$this->UpdateSort($this->no_tarefa, $bCtrl); // no_tarefa
			$this->UpdateSort($this->nu_catAtividade, $bCtrl); // nu_catAtividade
			$this->UpdateSort($this->nu_situacao, $bCtrl); // nu_situacao
			$this->UpdateSort($this->qt_horasReal, $bCtrl); // qt_horasReal
			$this->UpdateSort($this->qt_horasEstimada, $bCtrl); // qt_horasEstimada
			$this->UpdateSort($this->nu_autor, $bCtrl); // nu_autor
			$this->UpdateSort($this->no_autor, $bCtrl); // no_autor
			$this->UpdateSort($this->nu_responsavel, $bCtrl); // nu_responsavel
			$this->UpdateSort($this->no_responsavel, $bCtrl); // no_responsavel
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
				$this->nu_projeto->setSort("");
				$this->nu_versao->setSort("");
				$this->nu_tarefaPai->setSort("");
				$this->nu_tarefa->setSort("");
				$this->no_tarefa->setSort("");
				$this->nu_catAtividade->setSort("");
				$this->nu_situacao->setSort("");
				$this->qt_horasReal->setSort("");
				$this->qt_horasEstimada->setSort("");
				$this->nu_autor->setSort("");
				$this->no_autor->setSort("");
				$this->nu_responsavel->setSort("");
				$this->no_responsavel->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fvwrdmd_ativCronogramalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_projeto

		$this->nu_projeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_projeto"]);
		if ($this->nu_projeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_projeto->AdvancedSearch->SearchOperator = @$_GET["z_nu_projeto"];

		// nu_versao
		$this->nu_versao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_versao"]);
		if ($this->nu_versao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_versao->AdvancedSearch->SearchOperator = @$_GET["z_nu_versao"];

		// nu_tarefaPai
		$this->nu_tarefaPai->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_tarefaPai"]);
		if ($this->nu_tarefaPai->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_tarefaPai->AdvancedSearch->SearchOperator = @$_GET["z_nu_tarefaPai"];

		// nu_tarefa
		$this->nu_tarefa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_tarefa"]);
		if ($this->nu_tarefa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_tarefa->AdvancedSearch->SearchOperator = @$_GET["z_nu_tarefa"];

		// no_tarefa
		$this->no_tarefa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_tarefa"]);
		if ($this->no_tarefa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_tarefa->AdvancedSearch->SearchOperator = @$_GET["z_no_tarefa"];

		// nu_catAtividade
		$this->nu_catAtividade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_catAtividade"]);
		if ($this->nu_catAtividade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_catAtividade->AdvancedSearch->SearchOperator = @$_GET["z_nu_catAtividade"];

		// nu_situacao
		$this->nu_situacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_situacao"]);
		if ($this->nu_situacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_situacao->AdvancedSearch->SearchOperator = @$_GET["z_nu_situacao"];

		// qt_horasReal
		$this->qt_horasReal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_qt_horasReal"]);
		if ($this->qt_horasReal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->qt_horasReal->AdvancedSearch->SearchOperator = @$_GET["z_qt_horasReal"];

		// qt_horasEstimada
		$this->qt_horasEstimada->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_qt_horasEstimada"]);
		if ($this->qt_horasEstimada->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->qt_horasEstimada->AdvancedSearch->SearchOperator = @$_GET["z_qt_horasEstimada"];

		// nu_autor
		$this->nu_autor->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_autor"]);
		if ($this->nu_autor->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_autor->AdvancedSearch->SearchOperator = @$_GET["z_nu_autor"];

		// no_autor
		$this->no_autor->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_autor"]);
		if ($this->no_autor->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_autor->AdvancedSearch->SearchOperator = @$_GET["z_no_autor"];

		// nu_responsavel
		$this->nu_responsavel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_responsavel"]);
		if ($this->nu_responsavel->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_responsavel->AdvancedSearch->SearchOperator = @$_GET["z_nu_responsavel"];

		// no_responsavel
		$this->no_responsavel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_responsavel"]);
		if ($this->no_responsavel->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_responsavel->AdvancedSearch->SearchOperator = @$_GET["z_no_responsavel"];
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
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->nu_tarefaPai->setDbValue($rs->fields('nu_tarefaPai'));
		$this->nu_tarefa->setDbValue($rs->fields('nu_tarefa'));
		$this->no_tarefa->setDbValue($rs->fields('no_tarefa'));
		$this->nu_catAtividade->setDbValue($rs->fields('nu_catAtividade'));
		$this->nu_situacao->setDbValue($rs->fields('nu_situacao'));
		$this->qt_horasReal->setDbValue($rs->fields('qt_horasReal'));
		$this->qt_horasEstimada->setDbValue($rs->fields('qt_horasEstimada'));
		$this->nu_autor->setDbValue($rs->fields('nu_autor'));
		$this->no_autor->setDbValue($rs->fields('no_autor'));
		$this->nu_responsavel->setDbValue($rs->fields('nu_responsavel'));
		$this->no_responsavel->setDbValue($rs->fields('no_responsavel'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->nu_tarefaPai->DbValue = $row['nu_tarefaPai'];
		$this->nu_tarefa->DbValue = $row['nu_tarefa'];
		$this->no_tarefa->DbValue = $row['no_tarefa'];
		$this->nu_catAtividade->DbValue = $row['nu_catAtividade'];
		$this->nu_situacao->DbValue = $row['nu_situacao'];
		$this->qt_horasReal->DbValue = $row['qt_horasReal'];
		$this->qt_horasEstimada->DbValue = $row['qt_horasEstimada'];
		$this->nu_autor->DbValue = $row['nu_autor'];
		$this->no_autor->DbValue = $row['no_autor'];
		$this->nu_responsavel->DbValue = $row['nu_responsavel'];
		$this->no_responsavel->DbValue = $row['no_responsavel'];
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
		if ($this->qt_horasReal->FormValue == $this->qt_horasReal->CurrentValue && is_numeric(ew_StrToFloat($this->qt_horasReal->CurrentValue)))
			$this->qt_horasReal->CurrentValue = ew_StrToFloat($this->qt_horasReal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_horasEstimada->FormValue == $this->qt_horasEstimada->CurrentValue && is_numeric(ew_StrToFloat($this->qt_horasEstimada->CurrentValue)))
			$this->qt_horasEstimada->CurrentValue = ew_StrToFloat($this->qt_horasEstimada->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_projeto
		// nu_versao
		// nu_tarefaPai
		// nu_tarefa
		// no_tarefa
		// nu_catAtividade
		// nu_situacao
		// qt_horasReal
		// qt_horasEstimada
		// nu_autor
		// no_autor
		// nu_responsavel
		// no_responsavel

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projeto
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [db_owner].[vwrdm_projects]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// nu_tarefaPai
			$this->nu_tarefaPai->ViewCustomAttributes = "";

			// nu_tarefa
			$this->nu_tarefa->ViewCustomAttributes = "";

			// no_tarefa
			$this->no_tarefa->ViewValue = $this->no_tarefa->CurrentValue;
			$this->no_tarefa->ViewCustomAttributes = "";

			// nu_catAtividade
			if (strval($this->nu_catAtividade->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_catAtividade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [db_owner].[vwrdm_issue_categories]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catAtividade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_catAtividade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_catAtividade->ViewValue = $this->nu_catAtividade->CurrentValue;
				}
			} else {
				$this->nu_catAtividade->ViewValue = NULL;
			}
			$this->nu_catAtividade->ViewCustomAttributes = "";

			// nu_situacao
			if (strval($this->nu_situacao->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_situacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [db_owner].[vwrdm_issue_statuses]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_situacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_situacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_situacao->ViewValue = $this->nu_situacao->CurrentValue;
				}
			} else {
				$this->nu_situacao->ViewValue = NULL;
			}
			$this->nu_situacao->ViewCustomAttributes = "";

			// qt_horasReal
			$this->qt_horasReal->ViewValue = $this->qt_horasReal->CurrentValue;
			$this->qt_horasReal->ViewCustomAttributes = "";

			// qt_horasEstimada
			$this->qt_horasEstimada->ViewValue = $this->qt_horasEstimada->CurrentValue;
			$this->qt_horasEstimada->ViewCustomAttributes = "";

			// nu_autor
			$this->nu_autor->ViewValue = $this->nu_autor->CurrentValue;
			$this->nu_autor->ViewCustomAttributes = "";

			// no_autor
			$this->no_autor->ViewValue = $this->no_autor->CurrentValue;
			$this->no_autor->ViewCustomAttributes = "";

			// nu_responsavel
			$this->nu_responsavel->ViewValue = $this->nu_responsavel->CurrentValue;
			$this->nu_responsavel->ViewCustomAttributes = "";

			// no_responsavel
			$this->no_responsavel->ViewValue = $this->no_responsavel->CurrentValue;
			$this->no_responsavel->ViewCustomAttributes = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// nu_tarefaPai
			$this->nu_tarefaPai->LinkCustomAttributes = "";
			$this->nu_tarefaPai->HrefValue = "";
			$this->nu_tarefaPai->TooltipValue = "";

			// nu_tarefa
			$this->nu_tarefa->LinkCustomAttributes = "";
			$this->nu_tarefa->HrefValue = "";
			$this->nu_tarefa->TooltipValue = "";

			// no_tarefa
			$this->no_tarefa->LinkCustomAttributes = "";
			$this->no_tarefa->HrefValue = "";
			$this->no_tarefa->TooltipValue = "";

			// nu_catAtividade
			$this->nu_catAtividade->LinkCustomAttributes = "";
			$this->nu_catAtividade->HrefValue = "";
			$this->nu_catAtividade->TooltipValue = "";

			// nu_situacao
			$this->nu_situacao->LinkCustomAttributes = "";
			$this->nu_situacao->HrefValue = "";
			$this->nu_situacao->TooltipValue = "";

			// qt_horasReal
			$this->qt_horasReal->LinkCustomAttributes = "";
			$this->qt_horasReal->HrefValue = "";
			$this->qt_horasReal->TooltipValue = "";

			// qt_horasEstimada
			$this->qt_horasEstimada->LinkCustomAttributes = "";
			$this->qt_horasEstimada->HrefValue = "";
			$this->qt_horasEstimada->TooltipValue = "";

			// nu_autor
			$this->nu_autor->LinkCustomAttributes = "";
			$this->nu_autor->HrefValue = "";
			$this->nu_autor->TooltipValue = "";

			// no_autor
			$this->no_autor->LinkCustomAttributes = "";
			$this->no_autor->HrefValue = "";
			$this->no_autor->TooltipValue = "";

			// nu_responsavel
			$this->nu_responsavel->LinkCustomAttributes = "";
			$this->nu_responsavel->HrefValue = "";
			$this->nu_responsavel->TooltipValue = "";

			// no_responsavel
			$this->no_responsavel->LinkCustomAttributes = "";
			$this->no_responsavel->HrefValue = "";
			$this->no_responsavel->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [db_owner].[vwrdm_projects]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_projeto->EditValue = $arwrk;

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "";
			$this->nu_versao->EditValue = ew_HtmlEncode($this->nu_versao->AdvancedSearch->SearchValue);
			$this->nu_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versao->FldCaption()));

			// nu_tarefaPai
			$this->nu_tarefaPai->EditCustomAttributes = "";

			// nu_tarefa
			$this->nu_tarefa->EditCustomAttributes = "";

			// no_tarefa
			$this->no_tarefa->EditCustomAttributes = "";
			$this->no_tarefa->EditValue = ew_HtmlEncode($this->no_tarefa->AdvancedSearch->SearchValue);
			$this->no_tarefa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_tarefa->FldCaption()));

			// nu_catAtividade
			$this->nu_catAtividade->EditCustomAttributes = "";

			// nu_situacao
			$this->nu_situacao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [db_owner].[vwrdm_issue_statuses]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_situacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_situacao->EditValue = $arwrk;

			// qt_horasReal
			$this->qt_horasReal->EditCustomAttributes = "";
			$this->qt_horasReal->EditValue = ew_HtmlEncode($this->qt_horasReal->AdvancedSearch->SearchValue);
			$this->qt_horasReal->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_horasReal->FldCaption()));

			// qt_horasEstimada
			$this->qt_horasEstimada->EditCustomAttributes = "";
			$this->qt_horasEstimada->EditValue = ew_HtmlEncode($this->qt_horasEstimada->AdvancedSearch->SearchValue);
			$this->qt_horasEstimada->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_horasEstimada->FldCaption()));

			// nu_autor
			$this->nu_autor->EditCustomAttributes = "";
			$this->nu_autor->EditValue = ew_HtmlEncode($this->nu_autor->AdvancedSearch->SearchValue);
			$this->nu_autor->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_autor->FldCaption()));

			// no_autor
			$this->no_autor->EditCustomAttributes = "";
			$this->no_autor->EditValue = ew_HtmlEncode($this->no_autor->AdvancedSearch->SearchValue);
			$this->no_autor->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_autor->FldCaption()));

			// nu_responsavel
			$this->nu_responsavel->EditCustomAttributes = "";
			$this->nu_responsavel->EditValue = ew_HtmlEncode($this->nu_responsavel->AdvancedSearch->SearchValue);
			$this->nu_responsavel->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_responsavel->FldCaption()));

			// no_responsavel
			$this->no_responsavel->EditCustomAttributes = "";
			$this->no_responsavel->EditValue = ew_HtmlEncode($this->no_responsavel->AdvancedSearch->SearchValue);
			$this->no_responsavel->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_responsavel->FldCaption()));
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
		$this->nu_projeto->AdvancedSearch->Load();
		$this->nu_versao->AdvancedSearch->Load();
		$this->nu_tarefaPai->AdvancedSearch->Load();
		$this->nu_tarefa->AdvancedSearch->Load();
		$this->no_tarefa->AdvancedSearch->Load();
		$this->nu_catAtividade->AdvancedSearch->Load();
		$this->nu_situacao->AdvancedSearch->Load();
		$this->qt_horasReal->AdvancedSearch->Load();
		$this->qt_horasEstimada->AdvancedSearch->Load();
		$this->nu_autor->AdvancedSearch->Load();
		$this->no_autor->AdvancedSearch->Load();
		$this->nu_responsavel->AdvancedSearch->Load();
		$this->no_responsavel->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_vwrdmd_ativCronograma\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_vwrdmd_ativCronograma',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fvwrdmd_ativCronogramalist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_projeto); // nu_projeto
		$this->AddSearchQueryString($sQry, $this->nu_versao); // nu_versao
		$this->AddSearchQueryString($sQry, $this->nu_tarefaPai); // nu_tarefaPai
		$this->AddSearchQueryString($sQry, $this->nu_tarefa); // nu_tarefa
		$this->AddSearchQueryString($sQry, $this->no_tarefa); // no_tarefa
		$this->AddSearchQueryString($sQry, $this->nu_catAtividade); // nu_catAtividade
		$this->AddSearchQueryString($sQry, $this->nu_situacao); // nu_situacao
		$this->AddSearchQueryString($sQry, $this->qt_horasReal); // qt_horasReal
		$this->AddSearchQueryString($sQry, $this->qt_horasEstimada); // qt_horasEstimada
		$this->AddSearchQueryString($sQry, $this->nu_autor); // nu_autor
		$this->AddSearchQueryString($sQry, $this->no_autor); // no_autor
		$this->AddSearchQueryString($sQry, $this->nu_responsavel); // nu_responsavel
		$this->AddSearchQueryString($sQry, $this->no_responsavel); // no_responsavel

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
if (!isset($vwrdmd_ativCronograma_list)) $vwrdmd_ativCronograma_list = new cvwrdmd_ativCronograma_list();

// Page init
$vwrdmd_ativCronograma_list->Page_Init();

// Page main
$vwrdmd_ativCronograma_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vwrdmd_ativCronograma_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($vwrdmd_ativCronograma->Export == "") { ?>
<script type="text/javascript">

// Page object
var vwrdmd_ativCronograma_list = new ew_Page("vwrdmd_ativCronograma_list");
vwrdmd_ativCronograma_list.PageID = "list"; // Page ID
var EW_PAGE_ID = vwrdmd_ativCronograma_list.PageID; // For backward compatibility

// Form object
var fvwrdmd_ativCronogramalist = new ew_Form("fvwrdmd_ativCronogramalist");
fvwrdmd_ativCronogramalist.FormKeyCountName = '<?php echo $vwrdmd_ativCronograma_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvwrdmd_ativCronogramalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvwrdmd_ativCronogramalist.ValidateRequired = true;
<?php } else { ?>
fvwrdmd_ativCronogramalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fvwrdmd_ativCronogramalist.Lists["x_nu_projeto"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fvwrdmd_ativCronogramalist.Lists["x_nu_catAtividade"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fvwrdmd_ativCronogramalist.Lists["x_nu_situacao"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fvwrdmd_ativCronogramalistsrch = new ew_Form("fvwrdmd_ativCronogramalistsrch");

// Validate function for search
fvwrdmd_ativCronogramalistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fvwrdmd_ativCronogramalistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvwrdmd_ativCronogramalistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fvwrdmd_ativCronogramalistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fvwrdmd_ativCronogramalistsrch.Lists["x_nu_projeto"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fvwrdmd_ativCronogramalistsrch.Lists["x_nu_situacao"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fvwrdmd_ativCronogramalistsrch) fvwrdmd_ativCronogramalistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($vwrdmd_ativCronograma->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($vwrdmd_ativCronograma_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $vwrdmd_ativCronograma_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$vwrdmd_ativCronograma_list->TotalRecs = $vwrdmd_ativCronograma->SelectRecordCount();
	} else {
		if ($vwrdmd_ativCronograma_list->Recordset = $vwrdmd_ativCronograma_list->LoadRecordset())
			$vwrdmd_ativCronograma_list->TotalRecs = $vwrdmd_ativCronograma_list->Recordset->RecordCount();
	}
	$vwrdmd_ativCronograma_list->StartRec = 1;
	if ($vwrdmd_ativCronograma_list->DisplayRecs <= 0 || ($vwrdmd_ativCronograma->Export <> "" && $vwrdmd_ativCronograma->ExportAll)) // Display all records
		$vwrdmd_ativCronograma_list->DisplayRecs = $vwrdmd_ativCronograma_list->TotalRecs;
	if (!($vwrdmd_ativCronograma->Export <> "" && $vwrdmd_ativCronograma->ExportAll))
		$vwrdmd_ativCronograma_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vwrdmd_ativCronograma_list->Recordset = $vwrdmd_ativCronograma_list->LoadRecordset($vwrdmd_ativCronograma_list->StartRec-1, $vwrdmd_ativCronograma_list->DisplayRecs);
$vwrdmd_ativCronograma_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($vwrdmd_ativCronograma->Export == "" && $vwrdmd_ativCronograma->CurrentAction == "") { ?>
<form name="fvwrdmd_ativCronogramalistsrch" id="fvwrdmd_ativCronogramalistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fvwrdmd_ativCronogramalistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fvwrdmd_ativCronogramalistsrch_SearchGroup" href="#fvwrdmd_ativCronogramalistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fvwrdmd_ativCronogramalistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fvwrdmd_ativCronogramalistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="vwrdmd_ativCronograma">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$vwrdmd_ativCronograma_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$vwrdmd_ativCronograma->RowType = EW_ROWTYPE_SEARCH;

// Render row
$vwrdmd_ativCronograma->ResetAttrs();
$vwrdmd_ativCronograma_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($vwrdmd_ativCronograma->nu_projeto->Visible) { // nu_projeto ?>
	<span id="xsc_nu_projeto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $vwrdmd_ativCronograma->nu_projeto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_projeto" id="z_nu_projeto" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_projeto" id="x_nu_projeto" name="x_nu_projeto"<?php echo $vwrdmd_ativCronograma->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($vwrdmd_ativCronograma->nu_projeto->EditValue)) {
	$arwrk = $vwrdmd_ativCronograma->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($vwrdmd_ativCronograma->nu_projeto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fvwrdmd_ativCronogramalistsrch.Lists["x_nu_projeto"].Options = <?php echo (is_array($vwrdmd_ativCronograma->nu_projeto->EditValue)) ? ew_ArrayToJson($vwrdmd_ativCronograma->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($vwrdmd_ativCronograma->nu_situacao->Visible) { // nu_situacao ?>
	<span id="xsc_nu_situacao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $vwrdmd_ativCronograma->nu_situacao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_situacao" id="z_nu_situacao" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_situacao" id="x_nu_situacao" name="x_nu_situacao"<?php echo $vwrdmd_ativCronograma->nu_situacao->EditAttributes() ?>>
<?php
if (is_array($vwrdmd_ativCronograma->nu_situacao->EditValue)) {
	$arwrk = $vwrdmd_ativCronograma->nu_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($vwrdmd_ativCronograma->nu_situacao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fvwrdmd_ativCronogramalistsrch.Lists["x_nu_situacao"].Options = <?php echo (is_array($vwrdmd_ativCronograma->nu_situacao->EditValue)) ? ew_ArrayToJson($vwrdmd_ativCronograma->nu_situacao->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($vwrdmd_ativCronograma_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $vwrdmd_ativCronograma_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_4" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($vwrdmd_ativCronograma_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($vwrdmd_ativCronograma_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($vwrdmd_ativCronograma_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $vwrdmd_ativCronograma_list->ShowPageHeader(); ?>
<?php
$vwrdmd_ativCronograma_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fvwrdmd_ativCronogramalist" id="fvwrdmd_ativCronogramalist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="vwrdmd_ativCronograma">
<div id="gmp_vwrdmd_ativCronograma" class="ewGridMiddlePanel">
<?php if ($vwrdmd_ativCronograma_list->TotalRecs > 0) { ?>
<table id="tbl_vwrdmd_ativCronogramalist" class="ewTable ewTableSeparate">
<?php echo $vwrdmd_ativCronograma->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$vwrdmd_ativCronograma_list->RenderListOptions();

// Render list options (header, left)
$vwrdmd_ativCronograma_list->ListOptions->Render("header", "left");
?>
<?php if ($vwrdmd_ativCronograma->nu_projeto->Visible) { // nu_projeto ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_projeto) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_nu_projeto" class="vwrdmd_ativCronograma_nu_projeto"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_projeto) ?>',2);"><div id="elh_vwrdmd_ativCronograma_nu_projeto" class="vwrdmd_ativCronograma_nu_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->nu_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->nu_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->nu_versao->Visible) { // nu_versao ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_versao) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_nu_versao" class="vwrdmd_ativCronograma_nu_versao"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_versao) ?>',2);"><div id="elh_vwrdmd_ativCronograma_nu_versao" class="vwrdmd_ativCronograma_nu_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->nu_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->nu_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->nu_tarefaPai->Visible) { // nu_tarefaPai ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_tarefaPai) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_nu_tarefaPai" class="vwrdmd_ativCronograma_nu_tarefaPai"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_tarefaPai->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_tarefaPai) ?>',2);"><div id="elh_vwrdmd_ativCronograma_nu_tarefaPai" class="vwrdmd_ativCronograma_nu_tarefaPai">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_tarefaPai->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->nu_tarefaPai->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->nu_tarefaPai->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->nu_tarefa->Visible) { // nu_tarefa ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_tarefa) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_nu_tarefa" class="vwrdmd_ativCronograma_nu_tarefa"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_tarefa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_tarefa) ?>',2);"><div id="elh_vwrdmd_ativCronograma_nu_tarefa" class="vwrdmd_ativCronograma_nu_tarefa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_tarefa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->nu_tarefa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->nu_tarefa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->no_tarefa->Visible) { // no_tarefa ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->no_tarefa) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_no_tarefa" class="vwrdmd_ativCronograma_no_tarefa"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->no_tarefa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->no_tarefa) ?>',2);"><div id="elh_vwrdmd_ativCronograma_no_tarefa" class="vwrdmd_ativCronograma_no_tarefa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->no_tarefa->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->no_tarefa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->no_tarefa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->nu_catAtividade->Visible) { // nu_catAtividade ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_catAtividade) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_nu_catAtividade" class="vwrdmd_ativCronograma_nu_catAtividade"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_catAtividade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_catAtividade) ?>',2);"><div id="elh_vwrdmd_ativCronograma_nu_catAtividade" class="vwrdmd_ativCronograma_nu_catAtividade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_catAtividade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->nu_catAtividade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->nu_catAtividade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->nu_situacao->Visible) { // nu_situacao ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_situacao) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_nu_situacao" class="vwrdmd_ativCronograma_nu_situacao"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_situacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_situacao) ?>',2);"><div id="elh_vwrdmd_ativCronograma_nu_situacao" class="vwrdmd_ativCronograma_nu_situacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_situacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->nu_situacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->nu_situacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->qt_horasReal->Visible) { // qt_horasReal ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->qt_horasReal) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_qt_horasReal" class="vwrdmd_ativCronograma_qt_horasReal"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->qt_horasReal->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->qt_horasReal) ?>',2);"><div id="elh_vwrdmd_ativCronograma_qt_horasReal" class="vwrdmd_ativCronograma_qt_horasReal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->qt_horasReal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->qt_horasReal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->qt_horasReal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->qt_horasEstimada->Visible) { // qt_horasEstimada ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->qt_horasEstimada) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_qt_horasEstimada" class="vwrdmd_ativCronograma_qt_horasEstimada"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->qt_horasEstimada->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->qt_horasEstimada) ?>',2);"><div id="elh_vwrdmd_ativCronograma_qt_horasEstimada" class="vwrdmd_ativCronograma_qt_horasEstimada">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->qt_horasEstimada->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->qt_horasEstimada->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->qt_horasEstimada->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->nu_autor->Visible) { // nu_autor ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_autor) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_nu_autor" class="vwrdmd_ativCronograma_nu_autor"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_autor->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_autor) ?>',2);"><div id="elh_vwrdmd_ativCronograma_nu_autor" class="vwrdmd_ativCronograma_nu_autor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_autor->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->nu_autor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->nu_autor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->no_autor->Visible) { // no_autor ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->no_autor) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_no_autor" class="vwrdmd_ativCronograma_no_autor"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->no_autor->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->no_autor) ?>',2);"><div id="elh_vwrdmd_ativCronograma_no_autor" class="vwrdmd_ativCronograma_no_autor">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->no_autor->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->no_autor->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->no_autor->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->nu_responsavel->Visible) { // nu_responsavel ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_responsavel) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_nu_responsavel" class="vwrdmd_ativCronograma_nu_responsavel"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_responsavel->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->nu_responsavel) ?>',2);"><div id="elh_vwrdmd_ativCronograma_nu_responsavel" class="vwrdmd_ativCronograma_nu_responsavel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->nu_responsavel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->nu_responsavel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->nu_responsavel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($vwrdmd_ativCronograma->no_responsavel->Visible) { // no_responsavel ?>
	<?php if ($vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->no_responsavel) == "") { ?>
		<td><div id="elh_vwrdmd_ativCronograma_no_responsavel" class="vwrdmd_ativCronograma_no_responsavel"><div class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->no_responsavel->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vwrdmd_ativCronograma->SortUrl($vwrdmd_ativCronograma->no_responsavel) ?>',2);"><div id="elh_vwrdmd_ativCronograma_no_responsavel" class="vwrdmd_ativCronograma_no_responsavel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vwrdmd_ativCronograma->no_responsavel->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($vwrdmd_ativCronograma->no_responsavel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vwrdmd_ativCronograma->no_responsavel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vwrdmd_ativCronograma_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vwrdmd_ativCronograma->ExportAll && $vwrdmd_ativCronograma->Export <> "") {
	$vwrdmd_ativCronograma_list->StopRec = $vwrdmd_ativCronograma_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vwrdmd_ativCronograma_list->TotalRecs > $vwrdmd_ativCronograma_list->StartRec + $vwrdmd_ativCronograma_list->DisplayRecs - 1)
		$vwrdmd_ativCronograma_list->StopRec = $vwrdmd_ativCronograma_list->StartRec + $vwrdmd_ativCronograma_list->DisplayRecs - 1;
	else
		$vwrdmd_ativCronograma_list->StopRec = $vwrdmd_ativCronograma_list->TotalRecs;
}
$vwrdmd_ativCronograma_list->RecCnt = $vwrdmd_ativCronograma_list->StartRec - 1;
if ($vwrdmd_ativCronograma_list->Recordset && !$vwrdmd_ativCronograma_list->Recordset->EOF) {
	$vwrdmd_ativCronograma_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $vwrdmd_ativCronograma_list->StartRec > 1)
		$vwrdmd_ativCronograma_list->Recordset->Move($vwrdmd_ativCronograma_list->StartRec - 1);
} elseif (!$vwrdmd_ativCronograma->AllowAddDeleteRow && $vwrdmd_ativCronograma_list->StopRec == 0) {
	$vwrdmd_ativCronograma_list->StopRec = $vwrdmd_ativCronograma->GridAddRowCount;
}

// Initialize aggregate
$vwrdmd_ativCronograma->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vwrdmd_ativCronograma->ResetAttrs();
$vwrdmd_ativCronograma_list->RenderRow();
while ($vwrdmd_ativCronograma_list->RecCnt < $vwrdmd_ativCronograma_list->StopRec) {
	$vwrdmd_ativCronograma_list->RecCnt++;
	if (intval($vwrdmd_ativCronograma_list->RecCnt) >= intval($vwrdmd_ativCronograma_list->StartRec)) {
		$vwrdmd_ativCronograma_list->RowCnt++;

		// Set up key count
		$vwrdmd_ativCronograma_list->KeyCount = $vwrdmd_ativCronograma_list->RowIndex;

		// Init row class and style
		$vwrdmd_ativCronograma->ResetAttrs();
		$vwrdmd_ativCronograma->CssClass = "";
		if ($vwrdmd_ativCronograma->CurrentAction == "gridadd") {
		} else {
			$vwrdmd_ativCronograma_list->LoadRowValues($vwrdmd_ativCronograma_list->Recordset); // Load row values
		}
		$vwrdmd_ativCronograma->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vwrdmd_ativCronograma->RowAttrs = array_merge($vwrdmd_ativCronograma->RowAttrs, array('data-rowindex'=>$vwrdmd_ativCronograma_list->RowCnt, 'id'=>'r' . $vwrdmd_ativCronograma_list->RowCnt . '_vwrdmd_ativCronograma', 'data-rowtype'=>$vwrdmd_ativCronograma->RowType));

		// Render row
		$vwrdmd_ativCronograma_list->RenderRow();

		// Render list options
		$vwrdmd_ativCronograma_list->RenderListOptions();
?>
	<tr<?php echo $vwrdmd_ativCronograma->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vwrdmd_ativCronograma_list->ListOptions->Render("body", "left", $vwrdmd_ativCronograma_list->RowCnt);
?>
	<?php if ($vwrdmd_ativCronograma->nu_projeto->Visible) { // nu_projeto ?>
		<td<?php echo $vwrdmd_ativCronograma->nu_projeto->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->nu_projeto->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->nu_projeto->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->nu_versao->Visible) { // nu_versao ?>
		<td<?php echo $vwrdmd_ativCronograma->nu_versao->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->nu_versao->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->nu_versao->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->nu_tarefaPai->Visible) { // nu_tarefaPai ?>
		<td<?php echo $vwrdmd_ativCronograma->nu_tarefaPai->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->nu_tarefaPai->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->nu_tarefaPai->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->nu_tarefa->Visible) { // nu_tarefa ?>
		<td<?php echo $vwrdmd_ativCronograma->nu_tarefa->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->nu_tarefa->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->nu_tarefa->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->no_tarefa->Visible) { // no_tarefa ?>
		<td<?php echo $vwrdmd_ativCronograma->no_tarefa->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->no_tarefa->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->no_tarefa->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->nu_catAtividade->Visible) { // nu_catAtividade ?>
		<td<?php echo $vwrdmd_ativCronograma->nu_catAtividade->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->nu_catAtividade->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->nu_catAtividade->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->nu_situacao->Visible) { // nu_situacao ?>
		<td<?php echo $vwrdmd_ativCronograma->nu_situacao->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->nu_situacao->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->nu_situacao->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->qt_horasReal->Visible) { // qt_horasReal ?>
		<td<?php echo $vwrdmd_ativCronograma->qt_horasReal->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->qt_horasReal->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->qt_horasReal->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->qt_horasEstimada->Visible) { // qt_horasEstimada ?>
		<td<?php echo $vwrdmd_ativCronograma->qt_horasEstimada->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->qt_horasEstimada->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->qt_horasEstimada->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->nu_autor->Visible) { // nu_autor ?>
		<td<?php echo $vwrdmd_ativCronograma->nu_autor->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->nu_autor->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->nu_autor->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->no_autor->Visible) { // no_autor ?>
		<td<?php echo $vwrdmd_ativCronograma->no_autor->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->no_autor->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->no_autor->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->nu_responsavel->Visible) { // nu_responsavel ?>
		<td<?php echo $vwrdmd_ativCronograma->nu_responsavel->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->nu_responsavel->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->nu_responsavel->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma->no_responsavel->Visible) { // no_responsavel ?>
		<td<?php echo $vwrdmd_ativCronograma->no_responsavel->CellAttributes() ?>>
<span<?php echo $vwrdmd_ativCronograma->no_responsavel->ViewAttributes() ?>>
<?php echo $vwrdmd_ativCronograma->no_responsavel->ListViewValue() ?></span>
<a id="<?php echo $vwrdmd_ativCronograma_list->PageObjName . "_row_" . $vwrdmd_ativCronograma_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$vwrdmd_ativCronograma_list->ListOptions->Render("body", "right", $vwrdmd_ativCronograma_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vwrdmd_ativCronograma->CurrentAction <> "gridadd")
		$vwrdmd_ativCronograma_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vwrdmd_ativCronograma->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vwrdmd_ativCronograma_list->Recordset)
	$vwrdmd_ativCronograma_list->Recordset->Close();
?>
<?php if ($vwrdmd_ativCronograma->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($vwrdmd_ativCronograma->CurrentAction <> "gridadd" && $vwrdmd_ativCronograma->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($vwrdmd_ativCronograma_list->Pager)) $vwrdmd_ativCronograma_list->Pager = new cNumericPager($vwrdmd_ativCronograma_list->StartRec, $vwrdmd_ativCronograma_list->DisplayRecs, $vwrdmd_ativCronograma_list->TotalRecs, $vwrdmd_ativCronograma_list->RecRange) ?>
<?php if ($vwrdmd_ativCronograma_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($vwrdmd_ativCronograma_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdmd_ativCronograma_list->PageUrl() ?>start=<?php echo $vwrdmd_ativCronograma_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdmd_ativCronograma_list->PageUrl() ?>start=<?php echo $vwrdmd_ativCronograma_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($vwrdmd_ativCronograma_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $vwrdmd_ativCronograma_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdmd_ativCronograma_list->PageUrl() ?>start=<?php echo $vwrdmd_ativCronograma_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($vwrdmd_ativCronograma_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $vwrdmd_ativCronograma_list->PageUrl() ?>start=<?php echo $vwrdmd_ativCronograma_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($vwrdmd_ativCronograma_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vwrdmd_ativCronograma_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vwrdmd_ativCronograma_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vwrdmd_ativCronograma_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($vwrdmd_ativCronograma_list->SearchWhere == "0=101") { ?>
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
	foreach ($vwrdmd_ativCronograma_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($vwrdmd_ativCronograma->Export == "") { ?>
<script type="text/javascript">
fvwrdmd_ativCronogramalistsrch.Init();
fvwrdmd_ativCronogramalist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$vwrdmd_ativCronograma_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($vwrdmd_ativCronograma->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$vwrdmd_ativCronograma_list->Page_Terminate();
?>
