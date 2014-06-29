<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "atividadeinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "atividade_papelgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$atividade_list = NULL; // Initialize page object first

class catividade_list extends catividade {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'atividade';

	// Page object name
	var $PageObjName = 'atividade_list';

	// Grid form hidden field names
	var $FormName = 'fatividadelist';
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

		// Table object (atividade)
		if (!isset($GLOBALS["atividade"])) {
			$GLOBALS["atividade"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["atividade"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "atividadeadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "atividadedelete.php";
		$this->MultiUpdateUrl = "atividadeupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'atividade', TRUE);

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
			$this->nu_atividade->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_atividade->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_processo, FALSE); // nu_processo
		$this->BuildSearchSql($sWhere, $this->nu_atividadePred, FALSE); // nu_atividadePred
		$this->BuildSearchSql($sWhere, $this->no_atividade, FALSE); // no_atividade
		$this->BuildSearchSql($sWhere, $this->ds_atividade, FALSE); // ds_atividade
		$this->BuildSearchSql($sWhere, $this->vr_duracao, FALSE); // vr_duracao
		$this->BuildSearchSql($sWhere, $this->ic_ativo, FALSE); // ic_ativo

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_processo->AdvancedSearch->Save(); // nu_processo
			$this->nu_atividadePred->AdvancedSearch->Save(); // nu_atividadePred
			$this->no_atividade->AdvancedSearch->Save(); // no_atividade
			$this->ds_atividade->AdvancedSearch->Save(); // ds_atividade
			$this->vr_duracao->AdvancedSearch->Save(); // vr_duracao
			$this->ic_ativo->AdvancedSearch->Save(); // ic_ativo
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
		if ($this->nu_processo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_atividadePred->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_atividade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_atividade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_duracao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_ativo->AdvancedSearch->IssetSession())
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
		$this->nu_processo->AdvancedSearch->UnsetSession();
		$this->nu_atividadePred->AdvancedSearch->UnsetSession();
		$this->no_atividade->AdvancedSearch->UnsetSession();
		$this->ds_atividade->AdvancedSearch->UnsetSession();
		$this->vr_duracao->AdvancedSearch->UnsetSession();
		$this->ic_ativo->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_processo->AdvancedSearch->Load();
		$this->nu_atividadePred->AdvancedSearch->Load();
		$this->no_atividade->AdvancedSearch->Load();
		$this->ds_atividade->AdvancedSearch->Load();
		$this->vr_duracao->AdvancedSearch->Load();
		$this->ic_ativo->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->no_atividade, $bCtrl); // no_atividade
			$this->UpdateSort($this->vr_duracao, $bCtrl); // vr_duracao
			$this->UpdateSort($this->ic_ativo, $bCtrl); // ic_ativo
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
				$this->no_atividade->setSort("ASC");
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
				$this->no_atividade->setSort("");
				$this->vr_duracao->setSort("");
				$this->ic_ativo->setSort("");
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

		// "detail_atividade_papel"
		$item = &$this->ListOptions->Add("detail_atividade_papel");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'atividade_papel') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["atividade_papel_grid"])) $GLOBALS["atividade_papel_grid"] = new catividade_papel_grid;

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

		// "detail_atividade_papel"
		$oListOpt = &$this->ListOptions->Items["detail_atividade_papel"];
		if ($Security->AllowList(CurrentProjectID() . 'atividade_papel')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("atividade_papel", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("atividade_papellist.php?" . EW_TABLE_SHOW_MASTER . "=atividade&nu_atividade=" . strval($this->nu_atividade->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["atividade_papel_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'atividade_papel')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=atividade_papel")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "atividade_papel";
			}
			if ($GLOBALS["atividade_papel_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'atividade_papel')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=atividade_papel")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "atividade_papel";
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
		$item = &$option->Add("detailadd_atividade_papel");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=atividade_papel") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["atividade_papel"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["atividade_papel"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'atividade_papel') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "atividade_papel";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fatividadelist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_processo

		$this->nu_processo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_processo"]);
		if ($this->nu_processo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_processo->AdvancedSearch->SearchOperator = @$_GET["z_nu_processo"];

		// nu_atividadePred
		$this->nu_atividadePred->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_atividadePred"]);
		if ($this->nu_atividadePred->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_atividadePred->AdvancedSearch->SearchOperator = @$_GET["z_nu_atividadePred"];

		// no_atividade
		$this->no_atividade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_atividade"]);
		if ($this->no_atividade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_atividade->AdvancedSearch->SearchOperator = @$_GET["z_no_atividade"];

		// ds_atividade
		$this->ds_atividade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_atividade"]);
		if ($this->ds_atividade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_atividade->AdvancedSearch->SearchOperator = @$_GET["z_ds_atividade"];

		// vr_duracao
		$this->vr_duracao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_duracao"]);
		if ($this->vr_duracao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_duracao->AdvancedSearch->SearchOperator = @$_GET["z_vr_duracao"];
		$this->vr_duracao->AdvancedSearch->SearchCondition = @$_GET["v_vr_duracao"];
		$this->vr_duracao->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_vr_duracao"]);
		if ($this->vr_duracao->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->vr_duracao->AdvancedSearch->SearchOperator2 = @$_GET["w_vr_duracao"];

		// ic_ativo
		$this->ic_ativo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_ativo"]);
		if ($this->ic_ativo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_ativo->AdvancedSearch->SearchOperator = @$_GET["z_ic_ativo"];
		$this->ic_ativo->AdvancedSearch->SearchCondition = @$_GET["v_ic_ativo"];
		$this->ic_ativo->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_ic_ativo"]);
		if ($this->ic_ativo->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->ic_ativo->AdvancedSearch->SearchOperator2 = @$_GET["w_ic_ativo"];
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
		$this->nu_atividade->setDbValue($rs->fields('nu_atividade'));
		$this->nu_processo->setDbValue($rs->fields('nu_processo'));
		$this->nu_atividadePred->setDbValue($rs->fields('nu_atividadePred'));
		$this->no_atividade->setDbValue($rs->fields('no_atividade'));
		$this->ds_atividade->setDbValue($rs->fields('ds_atividade'));
		$this->vr_duracao->setDbValue($rs->fields('vr_duracao'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_atividade->DbValue = $row['nu_atividade'];
		$this->nu_processo->DbValue = $row['nu_processo'];
		$this->nu_atividadePred->DbValue = $row['nu_atividadePred'];
		$this->no_atividade->DbValue = $row['no_atividade'];
		$this->ds_atividade->DbValue = $row['ds_atividade'];
		$this->vr_duracao->DbValue = $row['vr_duracao'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_atividade")) <> "")
			$this->nu_atividade->CurrentValue = $this->getKey("nu_atividade"); // nu_atividade
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
		if ($this->vr_duracao->FormValue == $this->vr_duracao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_duracao->CurrentValue)))
			$this->vr_duracao->CurrentValue = ew_StrToFloat($this->vr_duracao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_atividade

		$this->nu_atividade->CellCssStyle = "white-space: nowrap;";

		// nu_processo
		// nu_atividadePred
		// no_atividade
		// ds_atividade
		// vr_duracao
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_processo
			$this->nu_processo->ViewValue = $this->nu_processo->CurrentValue;
			if (strval($this->nu_processo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_processo], [no_processo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processo]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_processo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_processo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_processo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_processo->ViewValue = $this->nu_processo->CurrentValue;
				}
			} else {
				$this->nu_processo->ViewValue = NULL;
			}
			$this->nu_processo->ViewCustomAttributes = "";

			// nu_atividadePred
			if (strval($this->nu_atividadePred->CurrentValue) <> "") {
				$sFilterWrk = "[nu_atividade]" . ew_SearchString("=", $this->nu_atividadePred->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_atividade], [no_atividade] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[atividade]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_atividadePred, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_atividade] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_atividadePred->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_atividadePred->ViewValue = $this->nu_atividadePred->CurrentValue;
				}
			} else {
				$this->nu_atividadePred->ViewValue = NULL;
			}
			$this->nu_atividadePred->ViewCustomAttributes = "";

			// no_atividade
			$this->no_atividade->ViewValue = $this->no_atividade->CurrentValue;
			$this->no_atividade->ViewCustomAttributes = "";

			// vr_duracao
			$this->vr_duracao->ViewValue = $this->vr_duracao->CurrentValue;
			$this->vr_duracao->ViewValue = ew_FormatNumber($this->vr_duracao->ViewValue, 2, -2, -2, -2);
			$this->vr_duracao->ViewCustomAttributes = "";

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

			// no_atividade
			$this->no_atividade->LinkCustomAttributes = "";
			$this->no_atividade->HrefValue = "";
			$this->no_atividade->TooltipValue = "";

			// vr_duracao
			$this->vr_duracao->LinkCustomAttributes = "";
			$this->vr_duracao->HrefValue = "";
			$this->vr_duracao->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// no_atividade
			$this->no_atividade->EditCustomAttributes = "";
			$this->no_atividade->EditValue = ew_HtmlEncode($this->no_atividade->AdvancedSearch->SearchValue);
			$this->no_atividade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_atividade->FldCaption()));

			// vr_duracao
			$this->vr_duracao->EditCustomAttributes = "";
			$this->vr_duracao->EditValue = ew_HtmlEncode($this->vr_duracao->AdvancedSearch->SearchValue);
			$this->vr_duracao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_duracao->FldCaption()));
			$this->vr_duracao->EditCustomAttributes = "";
			$this->vr_duracao->EditValue2 = ew_HtmlEncode($this->vr_duracao->AdvancedSearch->SearchValue2);
			$this->vr_duracao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_duracao->FldCaption()));

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue2 = $arwrk;
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
		if (!ew_CheckNumber($this->vr_duracao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->vr_duracao->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_duracao->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->vr_duracao->FldErrMsg());
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
		$this->nu_processo->AdvancedSearch->Load();
		$this->nu_atividadePred->AdvancedSearch->Load();
		$this->no_atividade->AdvancedSearch->Load();
		$this->ds_atividade->AdvancedSearch->Load();
		$this->vr_duracao->AdvancedSearch->Load();
		$this->ic_ativo->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_atividade\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_atividade',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fatividadelist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_processo); // nu_processo
		$this->AddSearchQueryString($sQry, $this->nu_atividadePred); // nu_atividadePred
		$this->AddSearchQueryString($sQry, $this->no_atividade); // no_atividade
		$this->AddSearchQueryString($sQry, $this->ds_atividade); // ds_atividade
		$this->AddSearchQueryString($sQry, $this->vr_duracao); // vr_duracao
		$this->AddSearchQueryString($sQry, $this->ic_ativo); // ic_ativo

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

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'atividade';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
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
if (!isset($atividade_list)) $atividade_list = new catividade_list();

// Page init
$atividade_list->Page_Init();

// Page main
$atividade_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$atividade_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($atividade->Export == "") { ?>
<script type="text/javascript">

// Page object
var atividade_list = new ew_Page("atividade_list");
atividade_list.PageID = "list"; // Page ID
var EW_PAGE_ID = atividade_list.PageID; // For backward compatibility

// Form object
var fatividadelist = new ew_Form("fatividadelist");
fatividadelist.FormKeyCountName = '<?php echo $atividade_list->FormKeyCountName ?>';

// Form_CustomValidate event
fatividadelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fatividadelist.ValidateRequired = true;
<?php } else { ?>
fatividadelist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fatividadelistsrch = new ew_Form("fatividadelistsrch");

// Validate function for search
fatividadelistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_vr_duracao");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($atividade->vr_duracao->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fatividadelistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fatividadelistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fatividadelistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (fatividadelistsrch) fatividadelistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($atividade->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($atividade_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $atividade_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$atividade_list->TotalRecs = $atividade->SelectRecordCount();
	} else {
		if ($atividade_list->Recordset = $atividade_list->LoadRecordset())
			$atividade_list->TotalRecs = $atividade_list->Recordset->RecordCount();
	}
	$atividade_list->StartRec = 1;
	if ($atividade_list->DisplayRecs <= 0 || ($atividade->Export <> "" && $atividade->ExportAll)) // Display all records
		$atividade_list->DisplayRecs = $atividade_list->TotalRecs;
	if (!($atividade->Export <> "" && $atividade->ExportAll))
		$atividade_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$atividade_list->Recordset = $atividade_list->LoadRecordset($atividade_list->StartRec-1, $atividade_list->DisplayRecs);
$atividade_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($atividade->Export == "" && $atividade->CurrentAction == "") { ?>
<form name="fatividadelistsrch" id="fatividadelistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fatividadelistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fatividadelistsrch_SearchGroup" href="#fatividadelistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fatividadelistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fatividadelistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="atividade">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$atividade_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$atividade->RowType = EW_ROWTYPE_SEARCH;

// Render row
$atividade->ResetAttrs();
$atividade_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($atividade->no_atividade->Visible) { // no_atividade ?>
	<span id="xsc_no_atividade" class="ewCell">
		<span class="ewSearchCaption"><?php echo $atividade->no_atividade->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_atividade" id="z_no_atividade" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_atividade" name="x_no_atividade" id="x_no_atividade" size="30" maxlength="100" placeholder="<?php echo $atividade->no_atividade->PlaceHolder ?>" value="<?php echo $atividade->no_atividade->EditValue ?>"<?php echo $atividade->no_atividade->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($atividade->vr_duracao->Visible) { // vr_duracao ?>
	<span id="xsc_vr_duracao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $atividade->vr_duracao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_vr_duracao" id="z_vr_duracao" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_vr_duracao" name="x_vr_duracao" id="x_vr_duracao" size="30" placeholder="<?php echo $atividade->vr_duracao->PlaceHolder ?>" value="<?php echo $atividade->vr_duracao->EditValue ?>"<?php echo $atividade->vr_duracao->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_vr_duracao">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_vr_duracao">
<input type="text" data-field="x_vr_duracao" name="y_vr_duracao" id="y_vr_duracao" size="30" placeholder="<?php echo $atividade->vr_duracao->PlaceHolder ?>" value="<?php echo $atividade->vr_duracao->EditValue2 ?>"<?php echo $atividade->vr_duracao->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($atividade->ic_ativo->Visible) { // ic_ativo ?>
	<span id="xsc_ic_ativo" class="ewCell">
		<span class="ewSearchCaption"><?php echo $atividade->ic_ativo->FldCaption() ?></span>
		<span class="ewSearchOperator"><select name="z_ic_ativo" id="z_ic_ativo" class="input-medium" onchange="ewForms(this).SrchOprChanged(this);"><option value="="<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="=") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("=") ?></option><option value="<>"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="<>") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("<>") ?></option><option value="<"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="<") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("<") ?></option><option value="<="<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="<=") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("<=") ?></option><option value=">"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator==">") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase(">") ?></option><option value=">="<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator==">=") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase(">=") ?></option><option value="LIKE"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="LIKE") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("LIKE") ?></option><option value="NOT LIKE"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="NOT LIKE") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("NOT LIKE") ?></option><option value="STARTS WITH"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="STARTS WITH") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("STARTS WITH") ?></option><option value="ENDS WITH"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="ENDS WITH") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("ENDS WITH") ?></option><option value="IS NULL"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="IS NULL") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("IS NULL") ?></option><option value="IS NOT NULL"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="IS NOT NULL") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("IS NOT NULL") ?></option><option value="BETWEEN"<?php echo ($atividade->ic_ativo->AdvancedSearch->SearchOperator=="BETWEEN") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("BETWEEN") ?></option></select></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $atividade->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $atividade->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade->ic_ativo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $atividade->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
		<span class="ewSearchCond btw1_ic_ativo" style="display: none">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_ic_ativo" style="display: none">
<div id="tp_y_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="y_ic_ativo" id="y_ic_ativo" value="{value}"<?php echo $atividade->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_y_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $atividade->ic_ativo->EditValue2;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($atividade->ic_ativo->AdvancedSearch->SearchValue2) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="y_ic_ativo" id="y_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $atividade->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $atividade_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $atividade_list->ShowPageHeader(); ?>
<?php
$atividade_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fatividadelist" id="fatividadelist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="atividade">
<div id="gmp_atividade" class="ewGridMiddlePanel">
<?php if ($atividade_list->TotalRecs > 0) { ?>
<table id="tbl_atividadelist" class="ewTable ewTableSeparate">
<?php echo $atividade->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$atividade_list->RenderListOptions();

// Render list options (header, left)
$atividade_list->ListOptions->Render("header", "left");
?>
<?php if ($atividade->no_atividade->Visible) { // no_atividade ?>
	<?php if ($atividade->SortUrl($atividade->no_atividade) == "") { ?>
		<td><div id="elh_atividade_no_atividade" class="atividade_no_atividade"><div class="ewTableHeaderCaption"><?php echo $atividade->no_atividade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $atividade->SortUrl($atividade->no_atividade) ?>',2);"><div id="elh_atividade_no_atividade" class="atividade_no_atividade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atividade->no_atividade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atividade->no_atividade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atividade->no_atividade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($atividade->vr_duracao->Visible) { // vr_duracao ?>
	<?php if ($atividade->SortUrl($atividade->vr_duracao) == "") { ?>
		<td><div id="elh_atividade_vr_duracao" class="atividade_vr_duracao"><div class="ewTableHeaderCaption"><?php echo $atividade->vr_duracao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $atividade->SortUrl($atividade->vr_duracao) ?>',2);"><div id="elh_atividade_vr_duracao" class="atividade_vr_duracao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atividade->vr_duracao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atividade->vr_duracao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atividade->vr_duracao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($atividade->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($atividade->SortUrl($atividade->ic_ativo) == "") { ?>
		<td><div id="elh_atividade_ic_ativo" class="atividade_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $atividade->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $atividade->SortUrl($atividade->ic_ativo) ?>',2);"><div id="elh_atividade_ic_ativo" class="atividade_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $atividade->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($atividade->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($atividade->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$atividade_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($atividade->ExportAll && $atividade->Export <> "") {
	$atividade_list->StopRec = $atividade_list->TotalRecs;
} else {

	// Set the last record to display
	if ($atividade_list->TotalRecs > $atividade_list->StartRec + $atividade_list->DisplayRecs - 1)
		$atividade_list->StopRec = $atividade_list->StartRec + $atividade_list->DisplayRecs - 1;
	else
		$atividade_list->StopRec = $atividade_list->TotalRecs;
}
$atividade_list->RecCnt = $atividade_list->StartRec - 1;
if ($atividade_list->Recordset && !$atividade_list->Recordset->EOF) {
	$atividade_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $atividade_list->StartRec > 1)
		$atividade_list->Recordset->Move($atividade_list->StartRec - 1);
} elseif (!$atividade->AllowAddDeleteRow && $atividade_list->StopRec == 0) {
	$atividade_list->StopRec = $atividade->GridAddRowCount;
}

// Initialize aggregate
$atividade->RowType = EW_ROWTYPE_AGGREGATEINIT;
$atividade->ResetAttrs();
$atividade_list->RenderRow();
while ($atividade_list->RecCnt < $atividade_list->StopRec) {
	$atividade_list->RecCnt++;
	if (intval($atividade_list->RecCnt) >= intval($atividade_list->StartRec)) {
		$atividade_list->RowCnt++;

		// Set up key count
		$atividade_list->KeyCount = $atividade_list->RowIndex;

		// Init row class and style
		$atividade->ResetAttrs();
		$atividade->CssClass = "";
		if ($atividade->CurrentAction == "gridadd") {
		} else {
			$atividade_list->LoadRowValues($atividade_list->Recordset); // Load row values
		}
		$atividade->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$atividade->RowAttrs = array_merge($atividade->RowAttrs, array('data-rowindex'=>$atividade_list->RowCnt, 'id'=>'r' . $atividade_list->RowCnt . '_atividade', 'data-rowtype'=>$atividade->RowType));

		// Render row
		$atividade_list->RenderRow();

		// Render list options
		$atividade_list->RenderListOptions();
?>
	<tr<?php echo $atividade->RowAttributes() ?>>
<?php

// Render list options (body, left)
$atividade_list->ListOptions->Render("body", "left", $atividade_list->RowCnt);
?>
	<?php if ($atividade->no_atividade->Visible) { // no_atividade ?>
		<td<?php echo $atividade->no_atividade->CellAttributes() ?>>
<span<?php echo $atividade->no_atividade->ViewAttributes() ?>>
<?php echo $atividade->no_atividade->ListViewValue() ?></span>
<a id="<?php echo $atividade_list->PageObjName . "_row_" . $atividade_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($atividade->vr_duracao->Visible) { // vr_duracao ?>
		<td<?php echo $atividade->vr_duracao->CellAttributes() ?>>
<span<?php echo $atividade->vr_duracao->ViewAttributes() ?>>
<?php echo $atividade->vr_duracao->ListViewValue() ?></span>
<a id="<?php echo $atividade_list->PageObjName . "_row_" . $atividade_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($atividade->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $atividade->ic_ativo->CellAttributes() ?>>
<span<?php echo $atividade->ic_ativo->ViewAttributes() ?>>
<?php echo $atividade->ic_ativo->ListViewValue() ?></span>
<a id="<?php echo $atividade_list->PageObjName . "_row_" . $atividade_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$atividade_list->ListOptions->Render("body", "right", $atividade_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($atividade->CurrentAction <> "gridadd")
		$atividade_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($atividade->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($atividade_list->Recordset)
	$atividade_list->Recordset->Close();
?>
<?php if ($atividade->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($atividade->CurrentAction <> "gridadd" && $atividade->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($atividade_list->Pager)) $atividade_list->Pager = new cNumericPager($atividade_list->StartRec, $atividade_list->DisplayRecs, $atividade_list->TotalRecs, $atividade_list->RecRange) ?>
<?php if ($atividade_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($atividade_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $atividade_list->PageUrl() ?>start=<?php echo $atividade_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($atividade_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $atividade_list->PageUrl() ?>start=<?php echo $atividade_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($atividade_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $atividade_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($atividade_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $atividade_list->PageUrl() ?>start=<?php echo $atividade_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($atividade_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $atividade_list->PageUrl() ?>start=<?php echo $atividade_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($atividade_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $atividade_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $atividade_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $atividade_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($atividade_list->SearchWhere == "0=101") { ?>
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
	foreach ($atividade_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($atividade->Export == "") { ?>
<script type="text/javascript">
fatividadelistsrch.Init();
fatividadelist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$atividade_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($atividade->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$atividade_list->Page_Terminate();
?>
