<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gc_reuniaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gc_reuniao_list = NULL; // Initialize page object first

class cgc_reuniao_list extends cgc_reuniao {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'gc_reuniao';

	// Page object name
	var $PageObjName = 'gc_reuniao_list';

	// Grid form hidden field names
	var $FormName = 'fgc_reuniaolist';
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

		// Table object (gc_reuniao)
		if (!isset($GLOBALS["gc_reuniao"])) {
			$GLOBALS["gc_reuniao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gc_reuniao"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "gc_reuniaoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "gc_reuniaodelete.php";
		$this->MultiUpdateUrl = "gc_reuniaoupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gc_reuniao', TRUE);

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
		$this->nu_reuniao->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->nu_reuniao->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_reuniao->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_reuniao, FALSE); // nu_reuniao
		$this->BuildSearchSql($sWhere, $this->nu_grupoOuComite, FALSE); // nu_grupoOuComite
		$this->BuildSearchSql($sWhere, $this->ds_pauta, FALSE); // ds_pauta
		$this->BuildSearchSql($sWhere, $this->no_local, FALSE); // no_local
		$this->BuildSearchSql($sWhere, $this->dt_reuniao, FALSE); // dt_reuniao
		$this->BuildSearchSql($sWhere, $this->hh_inicio, FALSE); // hh_inicio
		$this->BuildSearchSql($sWhere, $this->hh_fim, FALSE); // hh_fim
		$this->BuildSearchSql($sWhere, $this->ic_situacao, FALSE); // ic_situacao
		$this->BuildSearchSql($sWhere, $this->nu_usuario, FALSE); // nu_usuario
		$this->BuildSearchSql($sWhere, $this->ts_datahora, FALSE); // ts_datahora

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_reuniao->AdvancedSearch->Save(); // nu_reuniao
			$this->nu_grupoOuComite->AdvancedSearch->Save(); // nu_grupoOuComite
			$this->ds_pauta->AdvancedSearch->Save(); // ds_pauta
			$this->no_local->AdvancedSearch->Save(); // no_local
			$this->dt_reuniao->AdvancedSearch->Save(); // dt_reuniao
			$this->hh_inicio->AdvancedSearch->Save(); // hh_inicio
			$this->hh_fim->AdvancedSearch->Save(); // hh_fim
			$this->ic_situacao->AdvancedSearch->Save(); // ic_situacao
			$this->nu_usuario->AdvancedSearch->Save(); // nu_usuario
			$this->ts_datahora->AdvancedSearch->Save(); // ts_datahora
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
		if ($this->nu_reuniao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_grupoOuComite->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_pauta->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_local->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_reuniao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->hh_inicio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->hh_fim->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_situacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_usuario->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ts_datahora->AdvancedSearch->IssetSession())
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
		$this->nu_reuniao->AdvancedSearch->UnsetSession();
		$this->nu_grupoOuComite->AdvancedSearch->UnsetSession();
		$this->ds_pauta->AdvancedSearch->UnsetSession();
		$this->no_local->AdvancedSearch->UnsetSession();
		$this->dt_reuniao->AdvancedSearch->UnsetSession();
		$this->hh_inicio->AdvancedSearch->UnsetSession();
		$this->hh_fim->AdvancedSearch->UnsetSession();
		$this->ic_situacao->AdvancedSearch->UnsetSession();
		$this->nu_usuario->AdvancedSearch->UnsetSession();
		$this->ts_datahora->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_reuniao->AdvancedSearch->Load();
		$this->nu_grupoOuComite->AdvancedSearch->Load();
		$this->ds_pauta->AdvancedSearch->Load();
		$this->no_local->AdvancedSearch->Load();
		$this->dt_reuniao->AdvancedSearch->Load();
		$this->hh_inicio->AdvancedSearch->Load();
		$this->hh_fim->AdvancedSearch->Load();
		$this->ic_situacao->AdvancedSearch->Load();
		$this->nu_usuario->AdvancedSearch->Load();
		$this->ts_datahora->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_reuniao, $bCtrl); // nu_reuniao
			$this->UpdateSort($this->nu_grupoOuComite, $bCtrl); // nu_grupoOuComite
			$this->UpdateSort($this->no_local, $bCtrl); // no_local
			$this->UpdateSort($this->dt_reuniao, $bCtrl); // dt_reuniao
			$this->UpdateSort($this->hh_inicio, $bCtrl); // hh_inicio
			$this->UpdateSort($this->hh_fim, $bCtrl); // hh_fim
			$this->UpdateSort($this->ic_situacao, $bCtrl); // ic_situacao
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
				$this->nu_reuniao->setSort("");
				$this->nu_grupoOuComite->setSort("");
				$this->no_local->setSort("");
				$this->dt_reuniao->setSort("");
				$this->hh_inicio->setSort("");
				$this->hh_fim->setSort("");
				$this->ic_situacao->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgc_reuniaolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_reuniao

		$this->nu_reuniao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_reuniao"]);
		if ($this->nu_reuniao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_reuniao->AdvancedSearch->SearchOperator = @$_GET["z_nu_reuniao"];

		// nu_grupoOuComite
		$this->nu_grupoOuComite->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_grupoOuComite"]);
		if ($this->nu_grupoOuComite->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_grupoOuComite->AdvancedSearch->SearchOperator = @$_GET["z_nu_grupoOuComite"];

		// ds_pauta
		$this->ds_pauta->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_pauta"]);
		if ($this->ds_pauta->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_pauta->AdvancedSearch->SearchOperator = @$_GET["z_ds_pauta"];

		// no_local
		$this->no_local->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_local"]);
		if ($this->no_local->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_local->AdvancedSearch->SearchOperator = @$_GET["z_no_local"];

		// dt_reuniao
		$this->dt_reuniao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_reuniao"]);
		if ($this->dt_reuniao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_reuniao->AdvancedSearch->SearchOperator = @$_GET["z_dt_reuniao"];

		// hh_inicio
		$this->hh_inicio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_hh_inicio"]);
		if ($this->hh_inicio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->hh_inicio->AdvancedSearch->SearchOperator = @$_GET["z_hh_inicio"];

		// hh_fim
		$this->hh_fim->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_hh_fim"]);
		if ($this->hh_fim->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->hh_fim->AdvancedSearch->SearchOperator = @$_GET["z_hh_fim"];

		// ic_situacao
		$this->ic_situacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_situacao"]);
		if ($this->ic_situacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_situacao->AdvancedSearch->SearchOperator = @$_GET["z_ic_situacao"];

		// nu_usuario
		$this->nu_usuario->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_usuario"]);
		if ($this->nu_usuario->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_usuario->AdvancedSearch->SearchOperator = @$_GET["z_nu_usuario"];

		// ts_datahora
		$this->ts_datahora->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ts_datahora"]);
		if ($this->ts_datahora->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ts_datahora->AdvancedSearch->SearchOperator = @$_GET["z_ts_datahora"];
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
		$this->nu_reuniao->setDbValue($rs->fields('nu_reuniao'));
		$this->nu_grupoOuComite->setDbValue($rs->fields('nu_grupoOuComite'));
		$this->ds_pauta->setDbValue($rs->fields('ds_pauta'));
		$this->no_local->setDbValue($rs->fields('no_local'));
		$this->dt_reuniao->setDbValue($rs->fields('dt_reuniao'));
		$this->hh_inicio->setDbValue($rs->fields('hh_inicio'));
		$this->hh_fim->setDbValue($rs->fields('hh_fim'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_reuniao->DbValue = $row['nu_reuniao'];
		$this->nu_grupoOuComite->DbValue = $row['nu_grupoOuComite'];
		$this->ds_pauta->DbValue = $row['ds_pauta'];
		$this->no_local->DbValue = $row['no_local'];
		$this->dt_reuniao->DbValue = $row['dt_reuniao'];
		$this->hh_inicio->DbValue = $row['hh_inicio'];
		$this->hh_fim->DbValue = $row['hh_fim'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_reuniao")) <> "")
			$this->nu_reuniao->CurrentValue = $this->getKey("nu_reuniao"); // nu_reuniao
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
		// nu_reuniao
		// nu_grupoOuComite
		// ds_pauta
		// no_local
		// dt_reuniao
		// hh_inicio
		// hh_fim
		// ic_situacao
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_reuniao
			$this->nu_reuniao->ViewValue = $this->nu_reuniao->CurrentValue;
			$this->nu_reuniao->ViewCustomAttributes = "";

			// nu_grupoOuComite
			if (strval($this->nu_grupoOuComite->CurrentValue) <> "") {
				$sFilterWrk = "[nu_gpComite]" . ew_SearchString("=", $this->nu_grupoOuComite->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[gpcomite]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_grupoOuComite->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_grupoOuComite->ViewValue = $this->nu_grupoOuComite->CurrentValue;
				}
			} else {
				$this->nu_grupoOuComite->ViewValue = NULL;
			}
			$this->nu_grupoOuComite->ViewCustomAttributes = "";

			// no_local
			$this->no_local->ViewValue = $this->no_local->CurrentValue;
			$this->no_local->ViewCustomAttributes = "";

			// dt_reuniao
			$this->dt_reuniao->ViewValue = $this->dt_reuniao->CurrentValue;
			$this->dt_reuniao->ViewValue = ew_FormatDateTime($this->dt_reuniao->ViewValue, 7);
			$this->dt_reuniao->ViewCustomAttributes = "";

			// hh_inicio
			$this->hh_inicio->ViewValue = $this->hh_inicio->CurrentValue;
			$this->hh_inicio->ViewValue = ew_FormatDateTime($this->hh_inicio->ViewValue, 4);
			$this->hh_inicio->ViewCustomAttributes = "";

			// hh_fim
			$this->hh_fim->ViewValue = $this->hh_fim->CurrentValue;
			$this->hh_fim->ViewValue = ew_FormatDateTime($this->hh_fim->ViewValue, 4);
			$this->hh_fim->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(3):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_reuniao
			$this->nu_reuniao->LinkCustomAttributes = "";
			$this->nu_reuniao->HrefValue = "";
			$this->nu_reuniao->TooltipValue = "";

			// nu_grupoOuComite
			$this->nu_grupoOuComite->LinkCustomAttributes = "";
			$this->nu_grupoOuComite->HrefValue = "";
			$this->nu_grupoOuComite->TooltipValue = "";

			// no_local
			$this->no_local->LinkCustomAttributes = "";
			$this->no_local->HrefValue = "";
			$this->no_local->TooltipValue = "";

			// dt_reuniao
			$this->dt_reuniao->LinkCustomAttributes = "";
			$this->dt_reuniao->HrefValue = "";
			$this->dt_reuniao->TooltipValue = "";

			// hh_inicio
			$this->hh_inicio->LinkCustomAttributes = "";
			$this->hh_inicio->HrefValue = "";
			$this->hh_inicio->TooltipValue = "";

			// hh_fim
			$this->hh_fim->LinkCustomAttributes = "";
			$this->hh_fim->HrefValue = "";
			$this->hh_fim->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_reuniao
			$this->nu_reuniao->EditCustomAttributes = "";
			$this->nu_reuniao->EditValue = ew_HtmlEncode($this->nu_reuniao->AdvancedSearch->SearchValue);
			$this->nu_reuniao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_reuniao->FldCaption()));

			// nu_grupoOuComite
			$this->nu_grupoOuComite->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_gpComite], [no_gpComite] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[gpcomite]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_grupoOuComite, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_grupoOuComite->EditValue = $arwrk;

			// no_local
			$this->no_local->EditCustomAttributes = "";
			$this->no_local->EditValue = ew_HtmlEncode($this->no_local->AdvancedSearch->SearchValue);
			$this->no_local->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_local->FldCaption()));

			// dt_reuniao
			$this->dt_reuniao->EditCustomAttributes = "";
			$this->dt_reuniao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_reuniao->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_reuniao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_reuniao->FldCaption()));

			// hh_inicio
			$this->hh_inicio->EditCustomAttributes = "";
			$this->hh_inicio->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->hh_inicio->AdvancedSearch->SearchValue, 4), 4));
			$this->hh_inicio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->hh_inicio->FldCaption()));

			// hh_fim
			$this->hh_fim->EditCustomAttributes = "";
			$this->hh_fim->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->hh_fim->AdvancedSearch->SearchValue, 4), 4));
			$this->hh_fim->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->hh_fim->FldCaption()));

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			$arwrk[] = array($this->ic_situacao->FldTagValue(3), $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;
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
		if (!ew_CheckEuroDate($this->dt_reuniao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->dt_reuniao->FldErrMsg());
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
		$this->nu_reuniao->AdvancedSearch->Load();
		$this->nu_grupoOuComite->AdvancedSearch->Load();
		$this->ds_pauta->AdvancedSearch->Load();
		$this->no_local->AdvancedSearch->Load();
		$this->dt_reuniao->AdvancedSearch->Load();
		$this->hh_inicio->AdvancedSearch->Load();
		$this->hh_fim->AdvancedSearch->Load();
		$this->ic_situacao->AdvancedSearch->Load();
		$this->nu_usuario->AdvancedSearch->Load();
		$this->ts_datahora->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_gc_reuniao\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_gc_reuniao',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fgc_reuniaolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_reuniao); // nu_reuniao
		$this->AddSearchQueryString($sQry, $this->nu_grupoOuComite); // nu_grupoOuComite
		$this->AddSearchQueryString($sQry, $this->ds_pauta); // ds_pauta
		$this->AddSearchQueryString($sQry, $this->no_local); // no_local
		$this->AddSearchQueryString($sQry, $this->dt_reuniao); // dt_reuniao
		$this->AddSearchQueryString($sQry, $this->hh_inicio); // hh_inicio
		$this->AddSearchQueryString($sQry, $this->hh_fim); // hh_fim
		$this->AddSearchQueryString($sQry, $this->ic_situacao); // ic_situacao
		$this->AddSearchQueryString($sQry, $this->nu_usuario); // nu_usuario
		$this->AddSearchQueryString($sQry, $this->ts_datahora); // ts_datahora

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
if (!isset($gc_reuniao_list)) $gc_reuniao_list = new cgc_reuniao_list();

// Page init
$gc_reuniao_list->Page_Init();

// Page main
$gc_reuniao_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gc_reuniao_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($gc_reuniao->Export == "") { ?>
<script type="text/javascript">

// Page object
var gc_reuniao_list = new ew_Page("gc_reuniao_list");
gc_reuniao_list.PageID = "list"; // Page ID
var EW_PAGE_ID = gc_reuniao_list.PageID; // For backward compatibility

// Form object
var fgc_reuniaolist = new ew_Form("fgc_reuniaolist");
fgc_reuniaolist.FormKeyCountName = '<?php echo $gc_reuniao_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgc_reuniaolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgc_reuniaolist.ValidateRequired = true;
<?php } else { ?>
fgc_reuniaolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fgc_reuniaolist.Lists["x_nu_grupoOuComite"] = {"LinkField":"x_nu_gpComite","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_gpComite","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fgc_reuniaolistsrch = new ew_Form("fgc_reuniaolistsrch");

// Validate function for search
fgc_reuniaolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_dt_reuniao");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($gc_reuniao->dt_reuniao->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fgc_reuniaolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgc_reuniaolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fgc_reuniaolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fgc_reuniaolistsrch.Lists["x_nu_grupoOuComite"] = {"LinkField":"x_nu_gpComite","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_gpComite","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fgc_reuniaolistsrch) fgc_reuniaolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($gc_reuniao->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($gc_reuniao_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $gc_reuniao_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$gc_reuniao_list->TotalRecs = $gc_reuniao->SelectRecordCount();
	} else {
		if ($gc_reuniao_list->Recordset = $gc_reuniao_list->LoadRecordset())
			$gc_reuniao_list->TotalRecs = $gc_reuniao_list->Recordset->RecordCount();
	}
	$gc_reuniao_list->StartRec = 1;
	if ($gc_reuniao_list->DisplayRecs <= 0 || ($gc_reuniao->Export <> "" && $gc_reuniao->ExportAll)) // Display all records
		$gc_reuniao_list->DisplayRecs = $gc_reuniao_list->TotalRecs;
	if (!($gc_reuniao->Export <> "" && $gc_reuniao->ExportAll))
		$gc_reuniao_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$gc_reuniao_list->Recordset = $gc_reuniao_list->LoadRecordset($gc_reuniao_list->StartRec-1, $gc_reuniao_list->DisplayRecs);
$gc_reuniao_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($gc_reuniao->Export == "" && $gc_reuniao->CurrentAction == "") { ?>
<form name="fgc_reuniaolistsrch" id="fgc_reuniaolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fgc_reuniaolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fgc_reuniaolistsrch_SearchGroup" href="#fgc_reuniaolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fgc_reuniaolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fgc_reuniaolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="gc_reuniao">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$gc_reuniao_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$gc_reuniao->RowType = EW_ROWTYPE_SEARCH;

// Render row
$gc_reuniao->ResetAttrs();
$gc_reuniao_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($gc_reuniao->nu_grupoOuComite->Visible) { // nu_grupoOuComite ?>
	<span id="xsc_nu_grupoOuComite" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gc_reuniao->nu_grupoOuComite->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_grupoOuComite" id="z_nu_grupoOuComite" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_grupoOuComite" id="x_nu_grupoOuComite" name="x_nu_grupoOuComite"<?php echo $gc_reuniao->nu_grupoOuComite->EditAttributes() ?>>
<?php
if (is_array($gc_reuniao->nu_grupoOuComite->EditValue)) {
	$arwrk = $gc_reuniao->nu_grupoOuComite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gc_reuniao->nu_grupoOuComite->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fgc_reuniaolistsrch.Lists["x_nu_grupoOuComite"].Options = <?php echo (is_array($gc_reuniao->nu_grupoOuComite->EditValue)) ? ew_ArrayToJson($gc_reuniao->nu_grupoOuComite->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($gc_reuniao->dt_reuniao->Visible) { // dt_reuniao ?>
	<span id="xsc_dt_reuniao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gc_reuniao->dt_reuniao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_dt_reuniao" id="z_dt_reuniao" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dt_reuniao" name="x_dt_reuniao" id="x_dt_reuniao" placeholder="<?php echo $gc_reuniao->dt_reuniao->PlaceHolder ?>" value="<?php echo $gc_reuniao->dt_reuniao->EditValue ?>"<?php echo $gc_reuniao->dt_reuniao->EditAttributes() ?>>
<?php if (!$gc_reuniao->dt_reuniao->ReadOnly && !$gc_reuniao->dt_reuniao->Disabled && @$gc_reuniao->dt_reuniao->EditAttrs["readonly"] == "" && @$gc_reuniao->dt_reuniao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_reuniao" name="cal_x_dt_reuniao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_reuniao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fgc_reuniaolistsrch", "x_dt_reuniao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($gc_reuniao->ic_situacao->Visible) { // ic_situacao ?>
	<span id="xsc_ic_situacao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gc_reuniao->ic_situacao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_situacao" id="z_ic_situacao" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $gc_reuniao->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($gc_reuniao->ic_situacao->EditValue)) {
	$arwrk = $gc_reuniao->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gc_reuniao->ic_situacao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $gc_reuniao_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $gc_reuniao_list->ShowPageHeader(); ?>
<?php
$gc_reuniao_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fgc_reuniaolist" id="fgc_reuniaolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gc_reuniao">
<div id="gmp_gc_reuniao" class="ewGridMiddlePanel">
<?php if ($gc_reuniao_list->TotalRecs > 0) { ?>
<table id="tbl_gc_reuniaolist" class="ewTable ewTableSeparate">
<?php echo $gc_reuniao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$gc_reuniao_list->RenderListOptions();

// Render list options (header, left)
$gc_reuniao_list->ListOptions->Render("header", "left");
?>
<?php if ($gc_reuniao->nu_reuniao->Visible) { // nu_reuniao ?>
	<?php if ($gc_reuniao->SortUrl($gc_reuniao->nu_reuniao) == "") { ?>
		<td><div id="elh_gc_reuniao_nu_reuniao" class="gc_reuniao_nu_reuniao"><div class="ewTableHeaderCaption"><?php echo $gc_reuniao->nu_reuniao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gc_reuniao->SortUrl($gc_reuniao->nu_reuniao) ?>',2);"><div id="elh_gc_reuniao_nu_reuniao" class="gc_reuniao_nu_reuniao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gc_reuniao->nu_reuniao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gc_reuniao->nu_reuniao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gc_reuniao->nu_reuniao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gc_reuniao->nu_grupoOuComite->Visible) { // nu_grupoOuComite ?>
	<?php if ($gc_reuniao->SortUrl($gc_reuniao->nu_grupoOuComite) == "") { ?>
		<td><div id="elh_gc_reuniao_nu_grupoOuComite" class="gc_reuniao_nu_grupoOuComite"><div class="ewTableHeaderCaption"><?php echo $gc_reuniao->nu_grupoOuComite->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gc_reuniao->SortUrl($gc_reuniao->nu_grupoOuComite) ?>',2);"><div id="elh_gc_reuniao_nu_grupoOuComite" class="gc_reuniao_nu_grupoOuComite">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gc_reuniao->nu_grupoOuComite->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gc_reuniao->nu_grupoOuComite->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gc_reuniao->nu_grupoOuComite->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gc_reuniao->no_local->Visible) { // no_local ?>
	<?php if ($gc_reuniao->SortUrl($gc_reuniao->no_local) == "") { ?>
		<td><div id="elh_gc_reuniao_no_local" class="gc_reuniao_no_local"><div class="ewTableHeaderCaption"><?php echo $gc_reuniao->no_local->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gc_reuniao->SortUrl($gc_reuniao->no_local) ?>',2);"><div id="elh_gc_reuniao_no_local" class="gc_reuniao_no_local">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gc_reuniao->no_local->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gc_reuniao->no_local->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gc_reuniao->no_local->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gc_reuniao->dt_reuniao->Visible) { // dt_reuniao ?>
	<?php if ($gc_reuniao->SortUrl($gc_reuniao->dt_reuniao) == "") { ?>
		<td><div id="elh_gc_reuniao_dt_reuniao" class="gc_reuniao_dt_reuniao"><div class="ewTableHeaderCaption"><?php echo $gc_reuniao->dt_reuniao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gc_reuniao->SortUrl($gc_reuniao->dt_reuniao) ?>',2);"><div id="elh_gc_reuniao_dt_reuniao" class="gc_reuniao_dt_reuniao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gc_reuniao->dt_reuniao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gc_reuniao->dt_reuniao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gc_reuniao->dt_reuniao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gc_reuniao->hh_inicio->Visible) { // hh_inicio ?>
	<?php if ($gc_reuniao->SortUrl($gc_reuniao->hh_inicio) == "") { ?>
		<td><div id="elh_gc_reuniao_hh_inicio" class="gc_reuniao_hh_inicio"><div class="ewTableHeaderCaption"><?php echo $gc_reuniao->hh_inicio->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gc_reuniao->SortUrl($gc_reuniao->hh_inicio) ?>',2);"><div id="elh_gc_reuniao_hh_inicio" class="gc_reuniao_hh_inicio">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gc_reuniao->hh_inicio->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gc_reuniao->hh_inicio->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gc_reuniao->hh_inicio->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gc_reuniao->hh_fim->Visible) { // hh_fim ?>
	<?php if ($gc_reuniao->SortUrl($gc_reuniao->hh_fim) == "") { ?>
		<td><div id="elh_gc_reuniao_hh_fim" class="gc_reuniao_hh_fim"><div class="ewTableHeaderCaption"><?php echo $gc_reuniao->hh_fim->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gc_reuniao->SortUrl($gc_reuniao->hh_fim) ?>',2);"><div id="elh_gc_reuniao_hh_fim" class="gc_reuniao_hh_fim">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gc_reuniao->hh_fim->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gc_reuniao->hh_fim->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gc_reuniao->hh_fim->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gc_reuniao->ic_situacao->Visible) { // ic_situacao ?>
	<?php if ($gc_reuniao->SortUrl($gc_reuniao->ic_situacao) == "") { ?>
		<td><div id="elh_gc_reuniao_ic_situacao" class="gc_reuniao_ic_situacao"><div class="ewTableHeaderCaption"><?php echo $gc_reuniao->ic_situacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gc_reuniao->SortUrl($gc_reuniao->ic_situacao) ?>',2);"><div id="elh_gc_reuniao_ic_situacao" class="gc_reuniao_ic_situacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gc_reuniao->ic_situacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gc_reuniao->ic_situacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gc_reuniao->ic_situacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$gc_reuniao_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($gc_reuniao->ExportAll && $gc_reuniao->Export <> "") {
	$gc_reuniao_list->StopRec = $gc_reuniao_list->TotalRecs;
} else {

	// Set the last record to display
	if ($gc_reuniao_list->TotalRecs > $gc_reuniao_list->StartRec + $gc_reuniao_list->DisplayRecs - 1)
		$gc_reuniao_list->StopRec = $gc_reuniao_list->StartRec + $gc_reuniao_list->DisplayRecs - 1;
	else
		$gc_reuniao_list->StopRec = $gc_reuniao_list->TotalRecs;
}
$gc_reuniao_list->RecCnt = $gc_reuniao_list->StartRec - 1;
if ($gc_reuniao_list->Recordset && !$gc_reuniao_list->Recordset->EOF) {
	$gc_reuniao_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $gc_reuniao_list->StartRec > 1)
		$gc_reuniao_list->Recordset->Move($gc_reuniao_list->StartRec - 1);
} elseif (!$gc_reuniao->AllowAddDeleteRow && $gc_reuniao_list->StopRec == 0) {
	$gc_reuniao_list->StopRec = $gc_reuniao->GridAddRowCount;
}

// Initialize aggregate
$gc_reuniao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$gc_reuniao->ResetAttrs();
$gc_reuniao_list->RenderRow();
while ($gc_reuniao_list->RecCnt < $gc_reuniao_list->StopRec) {
	$gc_reuniao_list->RecCnt++;
	if (intval($gc_reuniao_list->RecCnt) >= intval($gc_reuniao_list->StartRec)) {
		$gc_reuniao_list->RowCnt++;

		// Set up key count
		$gc_reuniao_list->KeyCount = $gc_reuniao_list->RowIndex;

		// Init row class and style
		$gc_reuniao->ResetAttrs();
		$gc_reuniao->CssClass = "";
		if ($gc_reuniao->CurrentAction == "gridadd") {
		} else {
			$gc_reuniao_list->LoadRowValues($gc_reuniao_list->Recordset); // Load row values
		}
		$gc_reuniao->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$gc_reuniao->RowAttrs = array_merge($gc_reuniao->RowAttrs, array('data-rowindex'=>$gc_reuniao_list->RowCnt, 'id'=>'r' . $gc_reuniao_list->RowCnt . '_gc_reuniao', 'data-rowtype'=>$gc_reuniao->RowType));

		// Render row
		$gc_reuniao_list->RenderRow();

		// Render list options
		$gc_reuniao_list->RenderListOptions();
?>
	<tr<?php echo $gc_reuniao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gc_reuniao_list->ListOptions->Render("body", "left", $gc_reuniao_list->RowCnt);
?>
	<?php if ($gc_reuniao->nu_reuniao->Visible) { // nu_reuniao ?>
		<td<?php echo $gc_reuniao->nu_reuniao->CellAttributes() ?>>
<span<?php echo $gc_reuniao->nu_reuniao->ViewAttributes() ?>>
<?php echo $gc_reuniao->nu_reuniao->ListViewValue() ?></span>
<a id="<?php echo $gc_reuniao_list->PageObjName . "_row_" . $gc_reuniao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gc_reuniao->nu_grupoOuComite->Visible) { // nu_grupoOuComite ?>
		<td<?php echo $gc_reuniao->nu_grupoOuComite->CellAttributes() ?>>
<span<?php echo $gc_reuniao->nu_grupoOuComite->ViewAttributes() ?>>
<?php echo $gc_reuniao->nu_grupoOuComite->ListViewValue() ?></span>
<a id="<?php echo $gc_reuniao_list->PageObjName . "_row_" . $gc_reuniao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gc_reuniao->no_local->Visible) { // no_local ?>
		<td<?php echo $gc_reuniao->no_local->CellAttributes() ?>>
<span<?php echo $gc_reuniao->no_local->ViewAttributes() ?>>
<?php echo $gc_reuniao->no_local->ListViewValue() ?></span>
<a id="<?php echo $gc_reuniao_list->PageObjName . "_row_" . $gc_reuniao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gc_reuniao->dt_reuniao->Visible) { // dt_reuniao ?>
		<td<?php echo $gc_reuniao->dt_reuniao->CellAttributes() ?>>
<span<?php echo $gc_reuniao->dt_reuniao->ViewAttributes() ?>>
<?php echo $gc_reuniao->dt_reuniao->ListViewValue() ?></span>
<a id="<?php echo $gc_reuniao_list->PageObjName . "_row_" . $gc_reuniao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gc_reuniao->hh_inicio->Visible) { // hh_inicio ?>
		<td<?php echo $gc_reuniao->hh_inicio->CellAttributes() ?>>
<span<?php echo $gc_reuniao->hh_inicio->ViewAttributes() ?>>
<?php echo $gc_reuniao->hh_inicio->ListViewValue() ?></span>
<a id="<?php echo $gc_reuniao_list->PageObjName . "_row_" . $gc_reuniao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gc_reuniao->hh_fim->Visible) { // hh_fim ?>
		<td<?php echo $gc_reuniao->hh_fim->CellAttributes() ?>>
<span<?php echo $gc_reuniao->hh_fim->ViewAttributes() ?>>
<?php echo $gc_reuniao->hh_fim->ListViewValue() ?></span>
<a id="<?php echo $gc_reuniao_list->PageObjName . "_row_" . $gc_reuniao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gc_reuniao->ic_situacao->Visible) { // ic_situacao ?>
		<td<?php echo $gc_reuniao->ic_situacao->CellAttributes() ?>>
<span<?php echo $gc_reuniao->ic_situacao->ViewAttributes() ?>>
<?php echo $gc_reuniao->ic_situacao->ListViewValue() ?></span>
<a id="<?php echo $gc_reuniao_list->PageObjName . "_row_" . $gc_reuniao_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$gc_reuniao_list->ListOptions->Render("body", "right", $gc_reuniao_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($gc_reuniao->CurrentAction <> "gridadd")
		$gc_reuniao_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($gc_reuniao->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($gc_reuniao_list->Recordset)
	$gc_reuniao_list->Recordset->Close();
?>
<?php if ($gc_reuniao->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($gc_reuniao->CurrentAction <> "gridadd" && $gc_reuniao->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($gc_reuniao_list->Pager)) $gc_reuniao_list->Pager = new cNumericPager($gc_reuniao_list->StartRec, $gc_reuniao_list->DisplayRecs, $gc_reuniao_list->TotalRecs, $gc_reuniao_list->RecRange) ?>
<?php if ($gc_reuniao_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($gc_reuniao_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $gc_reuniao_list->PageUrl() ?>start=<?php echo $gc_reuniao_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($gc_reuniao_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $gc_reuniao_list->PageUrl() ?>start=<?php echo $gc_reuniao_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($gc_reuniao_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $gc_reuniao_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($gc_reuniao_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $gc_reuniao_list->PageUrl() ?>start=<?php echo $gc_reuniao_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($gc_reuniao_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $gc_reuniao_list->PageUrl() ?>start=<?php echo $gc_reuniao_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($gc_reuniao_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $gc_reuniao_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $gc_reuniao_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $gc_reuniao_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($gc_reuniao_list->SearchWhere == "0=101") { ?>
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
	foreach ($gc_reuniao_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($gc_reuniao->Export == "") { ?>
<script type="text/javascript">
fgc_reuniaolistsrch.Init();
fgc_reuniaolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$gc_reuniao_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($gc_reuniao->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$gc_reuniao_list->Page_Terminate();
?>
