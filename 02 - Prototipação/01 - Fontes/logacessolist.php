<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "logacessoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$logacesso_list = NULL; // Initialize page object first

class clogacesso_list extends clogacesso {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'logacesso';

	// Page object name
	var $PageObjName = 'logacesso_list';

	// Grid form hidden field names
	var $FormName = 'flogacessolist';
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

		// Table object (logacesso)
		if (!isset($GLOBALS["logacesso"])) {
			$GLOBALS["logacesso"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["logacesso"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "logacessoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "logacessodelete.php";
		$this->MultiUpdateUrl = "logacessoupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'logacesso', TRUE);

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
		$this->nu_logAcesso->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->nu_logAcesso->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_logAcesso->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_logAcesso, FALSE); // nu_logAcesso
		$this->BuildSearchSql($sWhere, $this->ds_caminho, FALSE); // ds_caminho
		$this->BuildSearchSql($sWhere, $this->no_arquivo, FALSE); // no_arquivo
		$this->BuildSearchSql($sWhere, $this->nu_sistema, FALSE); // nu_sistema
		$this->BuildSearchSql($sWhere, $this->nu_uc, FALSE); // nu_uc
		$this->BuildSearchSql($sWhere, $this->co_usuarioAd, FALSE); // co_usuarioAd
		$this->BuildSearchSql($sWhere, $this->no_usuarioAd, FALSE); // no_usuarioAd

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_logAcesso->AdvancedSearch->Save(); // nu_logAcesso
			$this->ds_caminho->AdvancedSearch->Save(); // ds_caminho
			$this->no_arquivo->AdvancedSearch->Save(); // no_arquivo
			$this->nu_sistema->AdvancedSearch->Save(); // nu_sistema
			$this->nu_uc->AdvancedSearch->Save(); // nu_uc
			$this->co_usuarioAd->AdvancedSearch->Save(); // co_usuarioAd
			$this->no_usuarioAd->AdvancedSearch->Save(); // no_usuarioAd
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
		if ($this->nu_logAcesso->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_caminho->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_arquivo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_sistema->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_uc->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->co_usuarioAd->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_usuarioAd->AdvancedSearch->IssetSession())
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
		$this->nu_logAcesso->AdvancedSearch->UnsetSession();
		$this->ds_caminho->AdvancedSearch->UnsetSession();
		$this->no_arquivo->AdvancedSearch->UnsetSession();
		$this->nu_sistema->AdvancedSearch->UnsetSession();
		$this->nu_uc->AdvancedSearch->UnsetSession();
		$this->co_usuarioAd->AdvancedSearch->UnsetSession();
		$this->no_usuarioAd->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_logAcesso->AdvancedSearch->Load();
		$this->ds_caminho->AdvancedSearch->Load();
		$this->no_arquivo->AdvancedSearch->Load();
		$this->nu_sistema->AdvancedSearch->Load();
		$this->nu_uc->AdvancedSearch->Load();
		$this->co_usuarioAd->AdvancedSearch->Load();
		$this->no_usuarioAd->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_logAcesso, $bCtrl); // nu_logAcesso
			$this->UpdateSort($this->nu_sistema, $bCtrl); // nu_sistema
			$this->UpdateSort($this->nu_uc, $bCtrl); // nu_uc
			$this->UpdateSort($this->co_usuarioAd, $bCtrl); // co_usuarioAd
			$this->UpdateSort($this->no_usuarioAd, $bCtrl); // no_usuarioAd
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
				$this->nu_logAcesso->setSort("ASC");
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
				$this->nu_logAcesso->setSort("");
				$this->nu_sistema->setSort("");
				$this->nu_uc->setSort("");
				$this->co_usuarioAd->setSort("");
				$this->no_usuarioAd->setSort("");
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

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

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

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.flogacessolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_logAcesso

		$this->nu_logAcesso->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_logAcesso"]);
		if ($this->nu_logAcesso->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_logAcesso->AdvancedSearch->SearchOperator = @$_GET["z_nu_logAcesso"];

		// ds_caminho
		$this->ds_caminho->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_caminho"]);
		if ($this->ds_caminho->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_caminho->AdvancedSearch->SearchOperator = @$_GET["z_ds_caminho"];

		// no_arquivo
		$this->no_arquivo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_arquivo"]);
		if ($this->no_arquivo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_arquivo->AdvancedSearch->SearchOperator = @$_GET["z_no_arquivo"];

		// nu_sistema
		$this->nu_sistema->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_sistema"]);
		if ($this->nu_sistema->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_sistema->AdvancedSearch->SearchOperator = @$_GET["z_nu_sistema"];

		// nu_uc
		$this->nu_uc->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_uc"]);
		if ($this->nu_uc->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_uc->AdvancedSearch->SearchOperator = @$_GET["z_nu_uc"];

		// co_usuarioAd
		$this->co_usuarioAd->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_co_usuarioAd"]);
		if ($this->co_usuarioAd->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->co_usuarioAd->AdvancedSearch->SearchOperator = @$_GET["z_co_usuarioAd"];

		// no_usuarioAd
		$this->no_usuarioAd->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_usuarioAd"]);
		if ($this->no_usuarioAd->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_usuarioAd->AdvancedSearch->SearchOperator = @$_GET["z_no_usuarioAd"];
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
		$this->nu_logAcesso->setDbValue($rs->fields('nu_logAcesso'));
		$this->ds_caminho->setDbValue($rs->fields('ds_caminho'));
		$this->no_arquivo->setDbValue($rs->fields('no_arquivo'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->co_usuarioAd->setDbValue($rs->fields('co_usuarioAd'));
		$this->no_usuarioAd->setDbValue($rs->fields('no_usuarioAd'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_logAcesso->DbValue = $row['nu_logAcesso'];
		$this->ds_caminho->DbValue = $row['ds_caminho'];
		$this->no_arquivo->DbValue = $row['no_arquivo'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->co_usuarioAd->DbValue = $row['co_usuarioAd'];
		$this->no_usuarioAd->DbValue = $row['no_usuarioAd'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_logAcesso")) <> "")
			$this->nu_logAcesso->CurrentValue = $this->getKey("nu_logAcesso"); // nu_logAcesso
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
		// nu_logAcesso
		// ds_caminho
		// no_arquivo
		// nu_sistema
		// nu_uc
		// co_usuarioAd
		// no_usuarioAd

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_logAcesso
			$this->nu_logAcesso->ViewValue = $this->nu_logAcesso->CurrentValue;
			$this->nu_logAcesso->ViewCustomAttributes = "";

			// nu_sistema
			$this->nu_sistema->ViewValue = $this->nu_sistema->CurrentValue;
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_uc
			$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
			$this->nu_uc->ViewCustomAttributes = "";

			// co_usuarioAd
			$this->co_usuarioAd->ViewValue = $this->co_usuarioAd->CurrentValue;
			$this->co_usuarioAd->ViewCustomAttributes = "";

			// no_usuarioAd
			$this->no_usuarioAd->ViewValue = $this->no_usuarioAd->CurrentValue;
			$this->no_usuarioAd->ViewCustomAttributes = "";

			// nu_logAcesso
			$this->nu_logAcesso->LinkCustomAttributes = "";
			$this->nu_logAcesso->HrefValue = "";
			$this->nu_logAcesso->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// co_usuarioAd
			$this->co_usuarioAd->LinkCustomAttributes = "";
			$this->co_usuarioAd->HrefValue = "";
			$this->co_usuarioAd->TooltipValue = "";

			// no_usuarioAd
			$this->no_usuarioAd->LinkCustomAttributes = "";
			$this->no_usuarioAd->HrefValue = "";
			$this->no_usuarioAd->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_logAcesso
			$this->nu_logAcesso->EditCustomAttributes = "";
			$this->nu_logAcesso->EditValue = ew_HtmlEncode($this->nu_logAcesso->AdvancedSearch->SearchValue);
			$this->nu_logAcesso->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_logAcesso->FldCaption()));

			// nu_sistema
			$this->nu_sistema->EditCustomAttributes = "";
			$this->nu_sistema->EditValue = ew_HtmlEncode($this->nu_sistema->AdvancedSearch->SearchValue);
			$this->nu_sistema->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_sistema->FldCaption()));

			// nu_uc
			$this->nu_uc->EditCustomAttributes = "";
			$this->nu_uc->EditValue = ew_HtmlEncode($this->nu_uc->AdvancedSearch->SearchValue);
			$this->nu_uc->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_uc->FldCaption()));

			// co_usuarioAd
			$this->co_usuarioAd->EditCustomAttributes = "";
			$this->co_usuarioAd->EditValue = ew_HtmlEncode($this->co_usuarioAd->AdvancedSearch->SearchValue);
			$this->co_usuarioAd->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_usuarioAd->FldCaption()));

			// no_usuarioAd
			$this->no_usuarioAd->EditCustomAttributes = "";
			$this->no_usuarioAd->EditValue = ew_HtmlEncode($this->no_usuarioAd->AdvancedSearch->SearchValue);
			$this->no_usuarioAd->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_usuarioAd->FldCaption()));
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
		if (!ew_CheckInteger($this->nu_logAcesso->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_logAcesso->FldErrMsg());
		}
		if (!ew_CheckInteger($this->nu_sistema->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_sistema->FldErrMsg());
		}
		if (!ew_CheckInteger($this->nu_uc->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_uc->FldErrMsg());
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
		$this->nu_logAcesso->AdvancedSearch->Load();
		$this->ds_caminho->AdvancedSearch->Load();
		$this->no_arquivo->AdvancedSearch->Load();
		$this->nu_sistema->AdvancedSearch->Load();
		$this->nu_uc->AdvancedSearch->Load();
		$this->co_usuarioAd->AdvancedSearch->Load();
		$this->no_usuarioAd->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_logacesso\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_logacesso',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.flogacessolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_logAcesso); // nu_logAcesso
		$this->AddSearchQueryString($sQry, $this->ds_caminho); // ds_caminho
		$this->AddSearchQueryString($sQry, $this->no_arquivo); // no_arquivo
		$this->AddSearchQueryString($sQry, $this->nu_sistema); // nu_sistema
		$this->AddSearchQueryString($sQry, $this->nu_uc); // nu_uc
		$this->AddSearchQueryString($sQry, $this->co_usuarioAd); // co_usuarioAd
		$this->AddSearchQueryString($sQry, $this->no_usuarioAd); // no_usuarioAd

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
		$table = 'logacesso';
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
if (!isset($logacesso_list)) $logacesso_list = new clogacesso_list();

// Page init
$logacesso_list->Page_Init();

// Page main
$logacesso_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$logacesso_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($logacesso->Export == "") { ?>
<script type="text/javascript">

// Page object
var logacesso_list = new ew_Page("logacesso_list");
logacesso_list.PageID = "list"; // Page ID
var EW_PAGE_ID = logacesso_list.PageID; // For backward compatibility

// Form object
var flogacessolist = new ew_Form("flogacessolist");
flogacessolist.FormKeyCountName = '<?php echo $logacesso_list->FormKeyCountName ?>';

// Form_CustomValidate event
flogacessolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flogacessolist.ValidateRequired = true;
<?php } else { ?>
flogacessolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var flogacessolistsrch = new ew_Form("flogacessolistsrch");

// Validate function for search
flogacessolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_logAcesso");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($logacesso->nu_logAcesso->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_nu_sistema");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($logacesso->nu_sistema->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_nu_uc");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($logacesso->nu_uc->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
flogacessolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flogacessolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
flogacessolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (flogacessolistsrch) flogacessolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($logacesso->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($logacesso_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $logacesso_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$logacesso_list->TotalRecs = $logacesso->SelectRecordCount();
	} else {
		if ($logacesso_list->Recordset = $logacesso_list->LoadRecordset())
			$logacesso_list->TotalRecs = $logacesso_list->Recordset->RecordCount();
	}
	$logacesso_list->StartRec = 1;
	if ($logacesso_list->DisplayRecs <= 0 || ($logacesso->Export <> "" && $logacesso->ExportAll)) // Display all records
		$logacesso_list->DisplayRecs = $logacesso_list->TotalRecs;
	if (!($logacesso->Export <> "" && $logacesso->ExportAll))
		$logacesso_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$logacesso_list->Recordset = $logacesso_list->LoadRecordset($logacesso_list->StartRec-1, $logacesso_list->DisplayRecs);
$logacesso_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($logacesso->Export == "" && $logacesso->CurrentAction == "") { ?>
<form name="flogacessolistsrch" id="flogacessolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="flogacessolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#flogacessolistsrch_SearchGroup" href="#flogacessolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="flogacessolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="flogacessolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="logacesso">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$logacesso_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$logacesso->RowType = EW_ROWTYPE_SEARCH;

// Render row
$logacesso->ResetAttrs();
$logacesso_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($logacesso->nu_logAcesso->Visible) { // nu_logAcesso ?>
	<span id="xsc_nu_logAcesso" class="ewCell">
		<span class="ewSearchCaption"><?php echo $logacesso->nu_logAcesso->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_logAcesso" id="z_nu_logAcesso" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_logAcesso" name="x_nu_logAcesso" id="x_nu_logAcesso" size="30" placeholder="<?php echo $logacesso->nu_logAcesso->PlaceHolder ?>" value="<?php echo $logacesso->nu_logAcesso->EditValue ?>"<?php echo $logacesso->nu_logAcesso->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($logacesso->nu_sistema->Visible) { // nu_sistema ?>
	<span id="xsc_nu_sistema" class="ewCell">
		<span class="ewSearchCaption"><?php echo $logacesso->nu_sistema->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_sistema" id="z_nu_sistema" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_sistema" name="x_nu_sistema" id="x_nu_sistema" size="30" placeholder="<?php echo $logacesso->nu_sistema->PlaceHolder ?>" value="<?php echo $logacesso->nu_sistema->EditValue ?>"<?php echo $logacesso->nu_sistema->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($logacesso->nu_uc->Visible) { // nu_uc ?>
	<span id="xsc_nu_uc" class="ewCell">
		<span class="ewSearchCaption"><?php echo $logacesso->nu_uc->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_uc" id="z_nu_uc" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_uc" name="x_nu_uc" id="x_nu_uc" size="30" placeholder="<?php echo $logacesso->nu_uc->PlaceHolder ?>" value="<?php echo $logacesso->nu_uc->EditValue ?>"<?php echo $logacesso->nu_uc->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($logacesso->co_usuarioAd->Visible) { // co_usuarioAd ?>
	<span id="xsc_co_usuarioAd" class="ewCell">
		<span class="ewSearchCaption"><?php echo $logacesso->co_usuarioAd->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_co_usuarioAd" id="z_co_usuarioAd" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_co_usuarioAd" name="x_co_usuarioAd" id="x_co_usuarioAd" size="30" maxlength="15" placeholder="<?php echo $logacesso->co_usuarioAd->PlaceHolder ?>" value="<?php echo $logacesso->co_usuarioAd->EditValue ?>"<?php echo $logacesso->co_usuarioAd->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $logacesso_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $logacesso_list->ShowPageHeader(); ?>
<?php
$logacesso_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="flogacessolist" id="flogacessolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="logacesso">
<div id="gmp_logacesso" class="ewGridMiddlePanel">
<?php if ($logacesso_list->TotalRecs > 0) { ?>
<table id="tbl_logacessolist" class="ewTable ewTableSeparate">
<?php echo $logacesso->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$logacesso_list->RenderListOptions();

// Render list options (header, left)
$logacesso_list->ListOptions->Render("header", "left");
?>
<?php if ($logacesso->nu_logAcesso->Visible) { // nu_logAcesso ?>
	<?php if ($logacesso->SortUrl($logacesso->nu_logAcesso) == "") { ?>
		<td><div id="elh_logacesso_nu_logAcesso" class="logacesso_nu_logAcesso"><div class="ewTableHeaderCaption"><?php echo $logacesso->nu_logAcesso->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $logacesso->SortUrl($logacesso->nu_logAcesso) ?>',2);"><div id="elh_logacesso_nu_logAcesso" class="logacesso_nu_logAcesso">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $logacesso->nu_logAcesso->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($logacesso->nu_logAcesso->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($logacesso->nu_logAcesso->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($logacesso->nu_sistema->Visible) { // nu_sistema ?>
	<?php if ($logacesso->SortUrl($logacesso->nu_sistema) == "") { ?>
		<td><div id="elh_logacesso_nu_sistema" class="logacesso_nu_sistema"><div class="ewTableHeaderCaption"><?php echo $logacesso->nu_sistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $logacesso->SortUrl($logacesso->nu_sistema) ?>',2);"><div id="elh_logacesso_nu_sistema" class="logacesso_nu_sistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $logacesso->nu_sistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($logacesso->nu_sistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($logacesso->nu_sistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($logacesso->nu_uc->Visible) { // nu_uc ?>
	<?php if ($logacesso->SortUrl($logacesso->nu_uc) == "") { ?>
		<td><div id="elh_logacesso_nu_uc" class="logacesso_nu_uc"><div class="ewTableHeaderCaption"><?php echo $logacesso->nu_uc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $logacesso->SortUrl($logacesso->nu_uc) ?>',2);"><div id="elh_logacesso_nu_uc" class="logacesso_nu_uc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $logacesso->nu_uc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($logacesso->nu_uc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($logacesso->nu_uc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($logacesso->co_usuarioAd->Visible) { // co_usuarioAd ?>
	<?php if ($logacesso->SortUrl($logacesso->co_usuarioAd) == "") { ?>
		<td><div id="elh_logacesso_co_usuarioAd" class="logacesso_co_usuarioAd"><div class="ewTableHeaderCaption"><?php echo $logacesso->co_usuarioAd->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $logacesso->SortUrl($logacesso->co_usuarioAd) ?>',2);"><div id="elh_logacesso_co_usuarioAd" class="logacesso_co_usuarioAd">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $logacesso->co_usuarioAd->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($logacesso->co_usuarioAd->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($logacesso->co_usuarioAd->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($logacesso->no_usuarioAd->Visible) { // no_usuarioAd ?>
	<?php if ($logacesso->SortUrl($logacesso->no_usuarioAd) == "") { ?>
		<td><div id="elh_logacesso_no_usuarioAd" class="logacesso_no_usuarioAd"><div class="ewTableHeaderCaption"><?php echo $logacesso->no_usuarioAd->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $logacesso->SortUrl($logacesso->no_usuarioAd) ?>',2);"><div id="elh_logacesso_no_usuarioAd" class="logacesso_no_usuarioAd">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $logacesso->no_usuarioAd->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($logacesso->no_usuarioAd->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($logacesso->no_usuarioAd->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$logacesso_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($logacesso->ExportAll && $logacesso->Export <> "") {
	$logacesso_list->StopRec = $logacesso_list->TotalRecs;
} else {

	// Set the last record to display
	if ($logacesso_list->TotalRecs > $logacesso_list->StartRec + $logacesso_list->DisplayRecs - 1)
		$logacesso_list->StopRec = $logacesso_list->StartRec + $logacesso_list->DisplayRecs - 1;
	else
		$logacesso_list->StopRec = $logacesso_list->TotalRecs;
}
$logacesso_list->RecCnt = $logacesso_list->StartRec - 1;
if ($logacesso_list->Recordset && !$logacesso_list->Recordset->EOF) {
	$logacesso_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $logacesso_list->StartRec > 1)
		$logacesso_list->Recordset->Move($logacesso_list->StartRec - 1);
} elseif (!$logacesso->AllowAddDeleteRow && $logacesso_list->StopRec == 0) {
	$logacesso_list->StopRec = $logacesso->GridAddRowCount;
}

// Initialize aggregate
$logacesso->RowType = EW_ROWTYPE_AGGREGATEINIT;
$logacesso->ResetAttrs();
$logacesso_list->RenderRow();
while ($logacesso_list->RecCnt < $logacesso_list->StopRec) {
	$logacesso_list->RecCnt++;
	if (intval($logacesso_list->RecCnt) >= intval($logacesso_list->StartRec)) {
		$logacesso_list->RowCnt++;

		// Set up key count
		$logacesso_list->KeyCount = $logacesso_list->RowIndex;

		// Init row class and style
		$logacesso->ResetAttrs();
		$logacesso->CssClass = "";
		if ($logacesso->CurrentAction == "gridadd") {
		} else {
			$logacesso_list->LoadRowValues($logacesso_list->Recordset); // Load row values
		}
		$logacesso->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$logacesso->RowAttrs = array_merge($logacesso->RowAttrs, array('data-rowindex'=>$logacesso_list->RowCnt, 'id'=>'r' . $logacesso_list->RowCnt . '_logacesso', 'data-rowtype'=>$logacesso->RowType));

		// Render row
		$logacesso_list->RenderRow();

		// Render list options
		$logacesso_list->RenderListOptions();
?>
	<tr<?php echo $logacesso->RowAttributes() ?>>
<?php

// Render list options (body, left)
$logacesso_list->ListOptions->Render("body", "left", $logacesso_list->RowCnt);
?>
	<?php if ($logacesso->nu_logAcesso->Visible) { // nu_logAcesso ?>
		<td<?php echo $logacesso->nu_logAcesso->CellAttributes() ?>>
<span<?php echo $logacesso->nu_logAcesso->ViewAttributes() ?>>
<?php echo $logacesso->nu_logAcesso->ListViewValue() ?></span>
<a id="<?php echo $logacesso_list->PageObjName . "_row_" . $logacesso_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($logacesso->nu_sistema->Visible) { // nu_sistema ?>
		<td<?php echo $logacesso->nu_sistema->CellAttributes() ?>>
<span<?php echo $logacesso->nu_sistema->ViewAttributes() ?>>
<?php echo $logacesso->nu_sistema->ListViewValue() ?></span>
<a id="<?php echo $logacesso_list->PageObjName . "_row_" . $logacesso_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($logacesso->nu_uc->Visible) { // nu_uc ?>
		<td<?php echo $logacesso->nu_uc->CellAttributes() ?>>
<span<?php echo $logacesso->nu_uc->ViewAttributes() ?>>
<?php echo $logacesso->nu_uc->ListViewValue() ?></span>
<a id="<?php echo $logacesso_list->PageObjName . "_row_" . $logacesso_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($logacesso->co_usuarioAd->Visible) { // co_usuarioAd ?>
		<td<?php echo $logacesso->co_usuarioAd->CellAttributes() ?>>
<span<?php echo $logacesso->co_usuarioAd->ViewAttributes() ?>>
<?php echo $logacesso->co_usuarioAd->ListViewValue() ?></span>
<a id="<?php echo $logacesso_list->PageObjName . "_row_" . $logacesso_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($logacesso->no_usuarioAd->Visible) { // no_usuarioAd ?>
		<td<?php echo $logacesso->no_usuarioAd->CellAttributes() ?>>
<span<?php echo $logacesso->no_usuarioAd->ViewAttributes() ?>>
<?php echo $logacesso->no_usuarioAd->ListViewValue() ?></span>
<a id="<?php echo $logacesso_list->PageObjName . "_row_" . $logacesso_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$logacesso_list->ListOptions->Render("body", "right", $logacesso_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($logacesso->CurrentAction <> "gridadd")
		$logacesso_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($logacesso->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($logacesso_list->Recordset)
	$logacesso_list->Recordset->Close();
?>
<?php if ($logacesso->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($logacesso->CurrentAction <> "gridadd" && $logacesso->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($logacesso_list->Pager)) $logacesso_list->Pager = new cNumericPager($logacesso_list->StartRec, $logacesso_list->DisplayRecs, $logacesso_list->TotalRecs, $logacesso_list->RecRange) ?>
<?php if ($logacesso_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($logacesso_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $logacesso_list->PageUrl() ?>start=<?php echo $logacesso_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($logacesso_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $logacesso_list->PageUrl() ?>start=<?php echo $logacesso_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($logacesso_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $logacesso_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($logacesso_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $logacesso_list->PageUrl() ?>start=<?php echo $logacesso_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($logacesso_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $logacesso_list->PageUrl() ?>start=<?php echo $logacesso_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($logacesso_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $logacesso_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $logacesso_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $logacesso_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($logacesso_list->SearchWhere == "0=101") { ?>
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
	foreach ($logacesso_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($logacesso->Export == "") { ?>
<script type="text/javascript">
flogacessolistsrch.Init();
flogacessolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$logacesso_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($logacesso->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$logacesso_list->Page_Terminate();
?>
