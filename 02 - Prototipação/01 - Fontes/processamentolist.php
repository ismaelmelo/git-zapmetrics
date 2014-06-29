<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "processamentoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$processamento_list = NULL; // Initialize page object first

class cprocessamento_list extends cprocessamento {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'processamento';

	// Page object name
	var $PageObjName = 'processamento_list';

	// Grid form hidden field names
	var $FormName = 'fprocessamentolist';
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

		// Table object (processamento)
		if (!isset($GLOBALS["processamento"])) {
			$GLOBALS["processamento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["processamento"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "processamentoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "processamentodelete.php";
		$this->MultiUpdateUrl = "processamentoupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'processamento', TRUE);

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
		$this->nu_processamento->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->nu_processamento->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_processamento->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_processamento, FALSE); // nu_processamento
		$this->BuildSearchSql($sWhere, $this->ic_origem, FALSE); // ic_origem
		$this->BuildSearchSql($sWhere, $this->ic_tpProcessamento, FALSE); // ic_tpProcessamento
		$this->BuildSearchSql($sWhere, $this->dh_processamento, FALSE); // dh_processamento
		$this->BuildSearchSql($sWhere, $this->ds_msgErro, FALSE); // ds_msgErro
		$this->BuildSearchSql($sWhere, $this->ic_processamento, FALSE); // ic_processamento

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_processamento->AdvancedSearch->Save(); // nu_processamento
			$this->ic_origem->AdvancedSearch->Save(); // ic_origem
			$this->ic_tpProcessamento->AdvancedSearch->Save(); // ic_tpProcessamento
			$this->dh_processamento->AdvancedSearch->Save(); // dh_processamento
			$this->ds_msgErro->AdvancedSearch->Save(); // ds_msgErro
			$this->ic_processamento->AdvancedSearch->Save(); // ic_processamento
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
		if ($this->nu_processamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_origem->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_tpProcessamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dh_processamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_msgErro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_processamento->AdvancedSearch->IssetSession())
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
		$this->nu_processamento->AdvancedSearch->UnsetSession();
		$this->ic_origem->AdvancedSearch->UnsetSession();
		$this->ic_tpProcessamento->AdvancedSearch->UnsetSession();
		$this->dh_processamento->AdvancedSearch->UnsetSession();
		$this->ds_msgErro->AdvancedSearch->UnsetSession();
		$this->ic_processamento->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_processamento->AdvancedSearch->Load();
		$this->ic_origem->AdvancedSearch->Load();
		$this->ic_tpProcessamento->AdvancedSearch->Load();
		$this->dh_processamento->AdvancedSearch->Load();
		$this->ds_msgErro->AdvancedSearch->Load();
		$this->ic_processamento->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_processamento, $bCtrl); // nu_processamento
			$this->UpdateSort($this->ic_origem, $bCtrl); // ic_origem
			$this->UpdateSort($this->ic_tpProcessamento, $bCtrl); // ic_tpProcessamento
			$this->UpdateSort($this->dh_processamento, $bCtrl); // dh_processamento
			$this->UpdateSort($this->ic_processamento, $bCtrl); // ic_processamento
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
				$this->nu_processamento->setSort("");
				$this->ic_origem->setSort("");
				$this->ic_tpProcessamento->setSort("");
				$this->dh_processamento->setSort("");
				$this->ic_processamento->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fprocessamentolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_processamento

		$this->nu_processamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_processamento"]);
		if ($this->nu_processamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_processamento->AdvancedSearch->SearchOperator = @$_GET["z_nu_processamento"];

		// ic_origem
		$this->ic_origem->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_origem"]);
		if ($this->ic_origem->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_origem->AdvancedSearch->SearchOperator = @$_GET["z_ic_origem"];

		// ic_tpProcessamento
		$this->ic_tpProcessamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_tpProcessamento"]);
		if ($this->ic_tpProcessamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_tpProcessamento->AdvancedSearch->SearchOperator = @$_GET["z_ic_tpProcessamento"];

		// dh_processamento
		$this->dh_processamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dh_processamento"]);
		if ($this->dh_processamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dh_processamento->AdvancedSearch->SearchOperator = @$_GET["z_dh_processamento"];

		// ds_msgErro
		$this->ds_msgErro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_msgErro"]);
		if ($this->ds_msgErro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_msgErro->AdvancedSearch->SearchOperator = @$_GET["z_ds_msgErro"];

		// ic_processamento
		$this->ic_processamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_processamento"]);
		if ($this->ic_processamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_processamento->AdvancedSearch->SearchOperator = @$_GET["z_ic_processamento"];
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
		$this->nu_processamento->setDbValue($rs->fields('nu_processamento'));
		$this->ic_origem->setDbValue($rs->fields('ic_origem'));
		$this->ic_tpProcessamento->setDbValue($rs->fields('ic_tpProcessamento'));
		$this->dh_processamento->setDbValue($rs->fields('dh_processamento'));
		$this->ds_msgErro->setDbValue($rs->fields('ds_msgErro'));
		$this->ic_processamento->setDbValue($rs->fields('ic_processamento'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_processamento->DbValue = $row['nu_processamento'];
		$this->ic_origem->DbValue = $row['ic_origem'];
		$this->ic_tpProcessamento->DbValue = $row['ic_tpProcessamento'];
		$this->dh_processamento->DbValue = $row['dh_processamento'];
		$this->ds_msgErro->DbValue = $row['ds_msgErro'];
		$this->ic_processamento->DbValue = $row['ic_processamento'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_processamento")) <> "")
			$this->nu_processamento->CurrentValue = $this->getKey("nu_processamento"); // nu_processamento
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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_processamento
		// ic_origem
		// ic_tpProcessamento
		// dh_processamento
		// ds_msgErro
		// ic_processamento

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_processamento
			$this->nu_processamento->ViewValue = $this->nu_processamento->CurrentValue;
			$this->nu_processamento->ViewCustomAttributes = "";

			// ic_origem
			if (strval($this->ic_origem->CurrentValue) <> "") {
				switch ($this->ic_origem->CurrentValue) {
					case $this->ic_origem->FldTagValue(1):
						$this->ic_origem->ViewValue = $this->ic_origem->FldTagCaption(1) <> "" ? $this->ic_origem->FldTagCaption(1) : $this->ic_origem->CurrentValue;
						break;
					default:
						$this->ic_origem->ViewValue = $this->ic_origem->CurrentValue;
				}
			} else {
				$this->ic_origem->ViewValue = NULL;
			}
			$this->ic_origem->ViewCustomAttributes = "";

			// ic_tpProcessamento
			if (strval($this->ic_tpProcessamento->CurrentValue) <> "") {
				switch ($this->ic_tpProcessamento->CurrentValue) {
					case $this->ic_tpProcessamento->FldTagValue(1):
						$this->ic_tpProcessamento->ViewValue = $this->ic_tpProcessamento->FldTagCaption(1) <> "" ? $this->ic_tpProcessamento->FldTagCaption(1) : $this->ic_tpProcessamento->CurrentValue;
						break;
					case $this->ic_tpProcessamento->FldTagValue(2):
						$this->ic_tpProcessamento->ViewValue = $this->ic_tpProcessamento->FldTagCaption(2) <> "" ? $this->ic_tpProcessamento->FldTagCaption(2) : $this->ic_tpProcessamento->CurrentValue;
						break;
					default:
						$this->ic_tpProcessamento->ViewValue = $this->ic_tpProcessamento->CurrentValue;
				}
			} else {
				$this->ic_tpProcessamento->ViewValue = NULL;
			}
			$this->ic_tpProcessamento->ViewCustomAttributes = "";

			// dh_processamento
			$this->dh_processamento->ViewValue = $this->dh_processamento->CurrentValue;
			$this->dh_processamento->ViewValue = ew_FormatDateTime($this->dh_processamento->ViewValue, 7);
			$this->dh_processamento->ViewCustomAttributes = "";

			// ic_processamento
			if (strval($this->ic_processamento->CurrentValue) <> "") {
				switch ($this->ic_processamento->CurrentValue) {
					case $this->ic_processamento->FldTagValue(1):
						$this->ic_processamento->ViewValue = $this->ic_processamento->FldTagCaption(1) <> "" ? $this->ic_processamento->FldTagCaption(1) : $this->ic_processamento->CurrentValue;
						break;
					case $this->ic_processamento->FldTagValue(2):
						$this->ic_processamento->ViewValue = $this->ic_processamento->FldTagCaption(2) <> "" ? $this->ic_processamento->FldTagCaption(2) : $this->ic_processamento->CurrentValue;
						break;
					default:
						$this->ic_processamento->ViewValue = $this->ic_processamento->CurrentValue;
				}
			} else {
				$this->ic_processamento->ViewValue = NULL;
			}
			$this->ic_processamento->ViewCustomAttributes = "";

			// nu_processamento
			$this->nu_processamento->LinkCustomAttributes = "";
			$this->nu_processamento->HrefValue = "";
			$this->nu_processamento->TooltipValue = "";

			// ic_origem
			$this->ic_origem->LinkCustomAttributes = "";
			$this->ic_origem->HrefValue = "";
			$this->ic_origem->TooltipValue = "";

			// ic_tpProcessamento
			$this->ic_tpProcessamento->LinkCustomAttributes = "";
			$this->ic_tpProcessamento->HrefValue = "";
			$this->ic_tpProcessamento->TooltipValue = "";

			// dh_processamento
			$this->dh_processamento->LinkCustomAttributes = "";
			$this->dh_processamento->HrefValue = "";
			$this->dh_processamento->TooltipValue = "";

			// ic_processamento
			$this->ic_processamento->LinkCustomAttributes = "";
			$this->ic_processamento->HrefValue = "";
			$this->ic_processamento->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_processamento
			$this->nu_processamento->EditCustomAttributes = "";
			$this->nu_processamento->EditValue = ew_HtmlEncode($this->nu_processamento->AdvancedSearch->SearchValue);
			$this->nu_processamento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_processamento->FldCaption()));

			// ic_origem
			$this->ic_origem->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_origem->FldTagValue(1), $this->ic_origem->FldTagCaption(1) <> "" ? $this->ic_origem->FldTagCaption(1) : $this->ic_origem->FldTagValue(1));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_origem->EditValue = $arwrk;

			// ic_tpProcessamento
			$this->ic_tpProcessamento->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpProcessamento->FldTagValue(1), $this->ic_tpProcessamento->FldTagCaption(1) <> "" ? $this->ic_tpProcessamento->FldTagCaption(1) : $this->ic_tpProcessamento->FldTagValue(1));
			$arwrk[] = array($this->ic_tpProcessamento->FldTagValue(2), $this->ic_tpProcessamento->FldTagCaption(2) <> "" ? $this->ic_tpProcessamento->FldTagCaption(2) : $this->ic_tpProcessamento->FldTagValue(2));
			$this->ic_tpProcessamento->EditValue = $arwrk;

			// dh_processamento
			$this->dh_processamento->EditCustomAttributes = "";
			$this->dh_processamento->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dh_processamento->AdvancedSearch->SearchValue, 7), 7));
			$this->dh_processamento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dh_processamento->FldCaption()));

			// ic_processamento
			$this->ic_processamento->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_processamento->FldTagValue(1), $this->ic_processamento->FldTagCaption(1) <> "" ? $this->ic_processamento->FldTagCaption(1) : $this->ic_processamento->FldTagValue(1));
			$arwrk[] = array($this->ic_processamento->FldTagValue(2), $this->ic_processamento->FldTagCaption(2) <> "" ? $this->ic_processamento->FldTagCaption(2) : $this->ic_processamento->FldTagValue(2));
			$this->ic_processamento->EditValue = $arwrk;
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
		$this->nu_processamento->AdvancedSearch->Load();
		$this->ic_origem->AdvancedSearch->Load();
		$this->ic_tpProcessamento->AdvancedSearch->Load();
		$this->dh_processamento->AdvancedSearch->Load();
		$this->ds_msgErro->AdvancedSearch->Load();
		$this->ic_processamento->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_processamento\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_processamento',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fprocessamentolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_processamento); // nu_processamento
		$this->AddSearchQueryString($sQry, $this->ic_origem); // ic_origem
		$this->AddSearchQueryString($sQry, $this->ic_tpProcessamento); // ic_tpProcessamento
		$this->AddSearchQueryString($sQry, $this->dh_processamento); // dh_processamento
		$this->AddSearchQueryString($sQry, $this->ds_msgErro); // ds_msgErro
		$this->AddSearchQueryString($sQry, $this->ic_processamento); // ic_processamento

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
if (!isset($processamento_list)) $processamento_list = new cprocessamento_list();

// Page init
$processamento_list->Page_Init();

// Page main
$processamento_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$processamento_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($processamento->Export == "") { ?>
<script type="text/javascript">

// Page object
var processamento_list = new ew_Page("processamento_list");
processamento_list.PageID = "list"; // Page ID
var EW_PAGE_ID = processamento_list.PageID; // For backward compatibility

// Form object
var fprocessamentolist = new ew_Form("fprocessamentolist");
fprocessamentolist.FormKeyCountName = '<?php echo $processamento_list->FormKeyCountName ?>';

// Form_CustomValidate event
fprocessamentolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprocessamentolist.ValidateRequired = true;
<?php } else { ?>
fprocessamentolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fprocessamentolistsrch = new ew_Form("fprocessamentolistsrch");

// Validate function for search
fprocessamentolistsrch.Validate = function(fobj) {
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
fprocessamentolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprocessamentolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fprocessamentolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (fprocessamentolistsrch) fprocessamentolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($processamento->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($processamento_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $processamento_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$processamento_list->TotalRecs = $processamento->SelectRecordCount();
	} else {
		if ($processamento_list->Recordset = $processamento_list->LoadRecordset())
			$processamento_list->TotalRecs = $processamento_list->Recordset->RecordCount();
	}
	$processamento_list->StartRec = 1;
	if ($processamento_list->DisplayRecs <= 0 || ($processamento->Export <> "" && $processamento->ExportAll)) // Display all records
		$processamento_list->DisplayRecs = $processamento_list->TotalRecs;
	if (!($processamento->Export <> "" && $processamento->ExportAll))
		$processamento_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$processamento_list->Recordset = $processamento_list->LoadRecordset($processamento_list->StartRec-1, $processamento_list->DisplayRecs);
$processamento_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($processamento->Export == "" && $processamento->CurrentAction == "") { ?>
<form name="fprocessamentolistsrch" id="fprocessamentolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fprocessamentolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fprocessamentolistsrch_SearchGroup" href="#fprocessamentolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fprocessamentolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fprocessamentolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="processamento">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$processamento_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$processamento->RowType = EW_ROWTYPE_SEARCH;

// Render row
$processamento->ResetAttrs();
$processamento_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($processamento->ic_origem->Visible) { // ic_origem ?>
	<span id="xsc_ic_origem" class="ewCell">
		<span class="ewSearchCaption"><?php echo $processamento->ic_origem->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_origem" id="z_ic_origem" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_origem" id="x_ic_origem" name="x_ic_origem"<?php echo $processamento->ic_origem->EditAttributes() ?>>
<?php
if (is_array($processamento->ic_origem->EditValue)) {
	$arwrk = $processamento->ic_origem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($processamento->ic_origem->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if ($processamento->ic_tpProcessamento->Visible) { // ic_tpProcessamento ?>
	<span id="xsc_ic_tpProcessamento" class="ewCell">
		<span class="ewSearchCaption"><?php echo $processamento->ic_tpProcessamento->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_tpProcessamento" id="z_ic_tpProcessamento" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ic_tpProcessamento" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_tpProcessamento" id="x_ic_tpProcessamento" value="{value}"<?php echo $processamento->ic_tpProcessamento->EditAttributes() ?>></div>
<div id="dsl_x_ic_tpProcessamento" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $processamento->ic_tpProcessamento->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($processamento->ic_tpProcessamento->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tpProcessamento" name="x_ic_tpProcessamento" id="x_ic_tpProcessamento_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $processamento->ic_tpProcessamento->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
<div id="xsr_2" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $processamento_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $processamento_list->ShowPageHeader(); ?>
<?php
$processamento_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fprocessamentolist" id="fprocessamentolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="processamento">
<div id="gmp_processamento" class="ewGridMiddlePanel">
<?php if ($processamento_list->TotalRecs > 0) { ?>
<table id="tbl_processamentolist" class="ewTable ewTableSeparate">
<?php echo $processamento->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$processamento_list->RenderListOptions();

// Render list options (header, left)
$processamento_list->ListOptions->Render("header", "left");
?>
<?php if ($processamento->nu_processamento->Visible) { // nu_processamento ?>
	<?php if ($processamento->SortUrl($processamento->nu_processamento) == "") { ?>
		<td><div id="elh_processamento_nu_processamento" class="processamento_nu_processamento"><div class="ewTableHeaderCaption"><?php echo $processamento->nu_processamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $processamento->SortUrl($processamento->nu_processamento) ?>',2);"><div id="elh_processamento_nu_processamento" class="processamento_nu_processamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $processamento->nu_processamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($processamento->nu_processamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($processamento->nu_processamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($processamento->ic_origem->Visible) { // ic_origem ?>
	<?php if ($processamento->SortUrl($processamento->ic_origem) == "") { ?>
		<td><div id="elh_processamento_ic_origem" class="processamento_ic_origem"><div class="ewTableHeaderCaption"><?php echo $processamento->ic_origem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $processamento->SortUrl($processamento->ic_origem) ?>',2);"><div id="elh_processamento_ic_origem" class="processamento_ic_origem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $processamento->ic_origem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($processamento->ic_origem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($processamento->ic_origem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($processamento->ic_tpProcessamento->Visible) { // ic_tpProcessamento ?>
	<?php if ($processamento->SortUrl($processamento->ic_tpProcessamento) == "") { ?>
		<td><div id="elh_processamento_ic_tpProcessamento" class="processamento_ic_tpProcessamento"><div class="ewTableHeaderCaption"><?php echo $processamento->ic_tpProcessamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $processamento->SortUrl($processamento->ic_tpProcessamento) ?>',2);"><div id="elh_processamento_ic_tpProcessamento" class="processamento_ic_tpProcessamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $processamento->ic_tpProcessamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($processamento->ic_tpProcessamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($processamento->ic_tpProcessamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($processamento->dh_processamento->Visible) { // dh_processamento ?>
	<?php if ($processamento->SortUrl($processamento->dh_processamento) == "") { ?>
		<td><div id="elh_processamento_dh_processamento" class="processamento_dh_processamento"><div class="ewTableHeaderCaption"><?php echo $processamento->dh_processamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $processamento->SortUrl($processamento->dh_processamento) ?>',2);"><div id="elh_processamento_dh_processamento" class="processamento_dh_processamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $processamento->dh_processamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($processamento->dh_processamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($processamento->dh_processamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($processamento->ic_processamento->Visible) { // ic_processamento ?>
	<?php if ($processamento->SortUrl($processamento->ic_processamento) == "") { ?>
		<td><div id="elh_processamento_ic_processamento" class="processamento_ic_processamento"><div class="ewTableHeaderCaption"><?php echo $processamento->ic_processamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $processamento->SortUrl($processamento->ic_processamento) ?>',2);"><div id="elh_processamento_ic_processamento" class="processamento_ic_processamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $processamento->ic_processamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($processamento->ic_processamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($processamento->ic_processamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$processamento_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($processamento->ExportAll && $processamento->Export <> "") {
	$processamento_list->StopRec = $processamento_list->TotalRecs;
} else {

	// Set the last record to display
	if ($processamento_list->TotalRecs > $processamento_list->StartRec + $processamento_list->DisplayRecs - 1)
		$processamento_list->StopRec = $processamento_list->StartRec + $processamento_list->DisplayRecs - 1;
	else
		$processamento_list->StopRec = $processamento_list->TotalRecs;
}
$processamento_list->RecCnt = $processamento_list->StartRec - 1;
if ($processamento_list->Recordset && !$processamento_list->Recordset->EOF) {
	$processamento_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $processamento_list->StartRec > 1)
		$processamento_list->Recordset->Move($processamento_list->StartRec - 1);
} elseif (!$processamento->AllowAddDeleteRow && $processamento_list->StopRec == 0) {
	$processamento_list->StopRec = $processamento->GridAddRowCount;
}

// Initialize aggregate
$processamento->RowType = EW_ROWTYPE_AGGREGATEINIT;
$processamento->ResetAttrs();
$processamento_list->RenderRow();
while ($processamento_list->RecCnt < $processamento_list->StopRec) {
	$processamento_list->RecCnt++;
	if (intval($processamento_list->RecCnt) >= intval($processamento_list->StartRec)) {
		$processamento_list->RowCnt++;

		// Set up key count
		$processamento_list->KeyCount = $processamento_list->RowIndex;

		// Init row class and style
		$processamento->ResetAttrs();
		$processamento->CssClass = "";
		if ($processamento->CurrentAction == "gridadd") {
		} else {
			$processamento_list->LoadRowValues($processamento_list->Recordset); // Load row values
		}
		$processamento->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$processamento->RowAttrs = array_merge($processamento->RowAttrs, array('data-rowindex'=>$processamento_list->RowCnt, 'id'=>'r' . $processamento_list->RowCnt . '_processamento', 'data-rowtype'=>$processamento->RowType));

		// Render row
		$processamento_list->RenderRow();

		// Render list options
		$processamento_list->RenderListOptions();
?>
	<tr<?php echo $processamento->RowAttributes() ?>>
<?php

// Render list options (body, left)
$processamento_list->ListOptions->Render("body", "left", $processamento_list->RowCnt);
?>
	<?php if ($processamento->nu_processamento->Visible) { // nu_processamento ?>
		<td<?php echo $processamento->nu_processamento->CellAttributes() ?>>
<span<?php echo $processamento->nu_processamento->ViewAttributes() ?>>
<?php echo $processamento->nu_processamento->ListViewValue() ?></span>
<a id="<?php echo $processamento_list->PageObjName . "_row_" . $processamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($processamento->ic_origem->Visible) { // ic_origem ?>
		<td<?php echo $processamento->ic_origem->CellAttributes() ?>>
<span<?php echo $processamento->ic_origem->ViewAttributes() ?>>
<?php echo $processamento->ic_origem->ListViewValue() ?></span>
<a id="<?php echo $processamento_list->PageObjName . "_row_" . $processamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($processamento->ic_tpProcessamento->Visible) { // ic_tpProcessamento ?>
		<td<?php echo $processamento->ic_tpProcessamento->CellAttributes() ?>>
<span<?php echo $processamento->ic_tpProcessamento->ViewAttributes() ?>>
<?php echo $processamento->ic_tpProcessamento->ListViewValue() ?></span>
<a id="<?php echo $processamento_list->PageObjName . "_row_" . $processamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($processamento->dh_processamento->Visible) { // dh_processamento ?>
		<td<?php echo $processamento->dh_processamento->CellAttributes() ?>>
<span<?php echo $processamento->dh_processamento->ViewAttributes() ?>>
<?php echo $processamento->dh_processamento->ListViewValue() ?></span>
<a id="<?php echo $processamento_list->PageObjName . "_row_" . $processamento_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($processamento->ic_processamento->Visible) { // ic_processamento ?>
		<td<?php echo $processamento->ic_processamento->CellAttributes() ?>>
<span<?php echo $processamento->ic_processamento->ViewAttributes() ?>>
<?php echo $processamento->ic_processamento->ListViewValue() ?></span>
<a id="<?php echo $processamento_list->PageObjName . "_row_" . $processamento_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$processamento_list->ListOptions->Render("body", "right", $processamento_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($processamento->CurrentAction <> "gridadd")
		$processamento_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($processamento->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($processamento_list->Recordset)
	$processamento_list->Recordset->Close();
?>
<?php if ($processamento->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($processamento->CurrentAction <> "gridadd" && $processamento->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($processamento_list->Pager)) $processamento_list->Pager = new cNumericPager($processamento_list->StartRec, $processamento_list->DisplayRecs, $processamento_list->TotalRecs, $processamento_list->RecRange) ?>
<?php if ($processamento_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($processamento_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $processamento_list->PageUrl() ?>start=<?php echo $processamento_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($processamento_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $processamento_list->PageUrl() ?>start=<?php echo $processamento_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($processamento_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $processamento_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($processamento_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $processamento_list->PageUrl() ?>start=<?php echo $processamento_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($processamento_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $processamento_list->PageUrl() ?>start=<?php echo $processamento_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($processamento_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $processamento_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $processamento_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $processamento_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($processamento_list->SearchWhere == "0=101") { ?>
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
	foreach ($processamento_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($processamento->Export == "") { ?>
<script type="text/javascript">
fprocessamentolistsrch.Init();
fprocessamentolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$processamento_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($processamento->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$processamento_list->Page_Terminate();
?>
