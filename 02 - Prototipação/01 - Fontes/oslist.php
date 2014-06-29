<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "osinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$os_list = NULL; // Initialize page object first

class cos_list extends cos {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'os';

	// Page object name
	var $PageObjName = 'os_list';

	// Grid form hidden field names
	var $FormName = 'foslist';
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

		// Table object (os)
		if (!isset($GLOBALS["os"])) {
			$GLOBALS["os"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["os"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "osadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "osdelete.php";
		$this->MultiUpdateUrl = "osupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'os', TRUE);

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
			$this->nu_os->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_os->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->co_os, FALSE); // co_os
		$this->BuildSearchSql($sWhere, $this->no_titulo, FALSE); // no_titulo
		$this->BuildSearchSql($sWhere, $this->nu_contrato, FALSE); // nu_contrato
		$this->BuildSearchSql($sWhere, $this->nu_itemContratado, FALSE); // nu_itemContratado
		$this->BuildSearchSql($sWhere, $this->nu_areaSolicitante, FALSE); // nu_areaSolicitante
		$this->BuildSearchSql($sWhere, $this->nu_projeto, FALSE); // nu_projeto
		$this->BuildSearchSql($sWhere, $this->dt_criacaoOs, FALSE); // dt_criacaoOs
		$this->BuildSearchSql($sWhere, $this->dt_entrega, FALSE); // dt_entrega
		$this->BuildSearchSql($sWhere, $this->nu_stOs, FALSE); // nu_stOs
		$this->BuildSearchSql($sWhere, $this->dt_stOs, FALSE); // dt_stOs
		$this->BuildSearchSql($sWhere, $this->nu_usuarioAnalista, FALSE); // nu_usuarioAnalista
		$this->BuildSearchSql($sWhere, $this->ds_observacoes, FALSE); // ds_observacoes
		$this->BuildSearchSql($sWhere, $this->vr_os, FALSE); // vr_os

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->co_os->AdvancedSearch->Save(); // co_os
			$this->no_titulo->AdvancedSearch->Save(); // no_titulo
			$this->nu_contrato->AdvancedSearch->Save(); // nu_contrato
			$this->nu_itemContratado->AdvancedSearch->Save(); // nu_itemContratado
			$this->nu_areaSolicitante->AdvancedSearch->Save(); // nu_areaSolicitante
			$this->nu_projeto->AdvancedSearch->Save(); // nu_projeto
			$this->dt_criacaoOs->AdvancedSearch->Save(); // dt_criacaoOs
			$this->dt_entrega->AdvancedSearch->Save(); // dt_entrega
			$this->nu_stOs->AdvancedSearch->Save(); // nu_stOs
			$this->dt_stOs->AdvancedSearch->Save(); // dt_stOs
			$this->nu_usuarioAnalista->AdvancedSearch->Save(); // nu_usuarioAnalista
			$this->ds_observacoes->AdvancedSearch->Save(); // ds_observacoes
			$this->vr_os->AdvancedSearch->Save(); // vr_os
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
		if ($this->co_os->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_titulo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_contrato->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_itemContratado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_areaSolicitante->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_projeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_criacaoOs->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_entrega->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_stOs->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_stOs->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_usuarioAnalista->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_observacoes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_os->AdvancedSearch->IssetSession())
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
		$this->co_os->AdvancedSearch->UnsetSession();
		$this->no_titulo->AdvancedSearch->UnsetSession();
		$this->nu_contrato->AdvancedSearch->UnsetSession();
		$this->nu_itemContratado->AdvancedSearch->UnsetSession();
		$this->nu_areaSolicitante->AdvancedSearch->UnsetSession();
		$this->nu_projeto->AdvancedSearch->UnsetSession();
		$this->dt_criacaoOs->AdvancedSearch->UnsetSession();
		$this->dt_entrega->AdvancedSearch->UnsetSession();
		$this->nu_stOs->AdvancedSearch->UnsetSession();
		$this->dt_stOs->AdvancedSearch->UnsetSession();
		$this->nu_usuarioAnalista->AdvancedSearch->UnsetSession();
		$this->ds_observacoes->AdvancedSearch->UnsetSession();
		$this->vr_os->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->co_os->AdvancedSearch->Load();
		$this->no_titulo->AdvancedSearch->Load();
		$this->nu_contrato->AdvancedSearch->Load();
		$this->nu_itemContratado->AdvancedSearch->Load();
		$this->nu_areaSolicitante->AdvancedSearch->Load();
		$this->nu_projeto->AdvancedSearch->Load();
		$this->dt_criacaoOs->AdvancedSearch->Load();
		$this->dt_entrega->AdvancedSearch->Load();
		$this->nu_stOs->AdvancedSearch->Load();
		$this->dt_stOs->AdvancedSearch->Load();
		$this->nu_usuarioAnalista->AdvancedSearch->Load();
		$this->ds_observacoes->AdvancedSearch->Load();
		$this->vr_os->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->co_os, $bCtrl); // co_os
			$this->UpdateSort($this->no_titulo, $bCtrl); // no_titulo
			$this->UpdateSort($this->nu_areaSolicitante, $bCtrl); // nu_areaSolicitante
			$this->UpdateSort($this->nu_stOs, $bCtrl); // nu_stOs
			$this->UpdateSort($this->nu_usuarioAnalista, $bCtrl); // nu_usuarioAnalista
			$this->UpdateSort($this->vr_os, $bCtrl); // vr_os
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
				$this->setSessionOrderByList($sOrderBy);
				$this->co_os->setSort("");
				$this->no_titulo->setSort("");
				$this->nu_areaSolicitante->setSort("");
				$this->nu_stOs->setSort("");
				$this->nu_usuarioAnalista->setSort("");
				$this->vr_os->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.foslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// co_os

		$this->co_os->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_co_os"]);
		if ($this->co_os->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->co_os->AdvancedSearch->SearchOperator = @$_GET["z_co_os"];

		// no_titulo
		$this->no_titulo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_titulo"]);
		if ($this->no_titulo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_titulo->AdvancedSearch->SearchOperator = @$_GET["z_no_titulo"];

		// nu_contrato
		$this->nu_contrato->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_contrato"]);
		if ($this->nu_contrato->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_contrato->AdvancedSearch->SearchOperator = @$_GET["z_nu_contrato"];

		// nu_itemContratado
		$this->nu_itemContratado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_itemContratado"]);
		if ($this->nu_itemContratado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_itemContratado->AdvancedSearch->SearchOperator = @$_GET["z_nu_itemContratado"];

		// nu_areaSolicitante
		$this->nu_areaSolicitante->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_areaSolicitante"]);
		if ($this->nu_areaSolicitante->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_areaSolicitante->AdvancedSearch->SearchOperator = @$_GET["z_nu_areaSolicitante"];

		// nu_projeto
		$this->nu_projeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_projeto"]);
		if ($this->nu_projeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_projeto->AdvancedSearch->SearchOperator = @$_GET["z_nu_projeto"];

		// dt_criacaoOs
		$this->dt_criacaoOs->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_criacaoOs"]);
		if ($this->dt_criacaoOs->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_criacaoOs->AdvancedSearch->SearchOperator = @$_GET["z_dt_criacaoOs"];

		// dt_entrega
		$this->dt_entrega->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_entrega"]);
		if ($this->dt_entrega->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_entrega->AdvancedSearch->SearchOperator = @$_GET["z_dt_entrega"];

		// nu_stOs
		$this->nu_stOs->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_stOs"]);
		if ($this->nu_stOs->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_stOs->AdvancedSearch->SearchOperator = @$_GET["z_nu_stOs"];

		// dt_stOs
		$this->dt_stOs->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_stOs"]);
		if ($this->dt_stOs->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_stOs->AdvancedSearch->SearchOperator = @$_GET["z_dt_stOs"];

		// nu_usuarioAnalista
		$this->nu_usuarioAnalista->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_usuarioAnalista"]);
		if ($this->nu_usuarioAnalista->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_usuarioAnalista->AdvancedSearch->SearchOperator = @$_GET["z_nu_usuarioAnalista"];

		// ds_observacoes
		$this->ds_observacoes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_observacoes"]);
		if ($this->ds_observacoes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_observacoes->AdvancedSearch->SearchOperator = @$_GET["z_ds_observacoes"];

		// vr_os
		$this->vr_os->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_os"]);
		if ($this->vr_os->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_os->AdvancedSearch->SearchOperator = @$_GET["z_vr_os"];
		$this->vr_os->AdvancedSearch->SearchCondition = @$_GET["v_vr_os"];
		$this->vr_os->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_vr_os"]);
		if ($this->vr_os->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->vr_os->AdvancedSearch->SearchOperator2 = @$_GET["w_vr_os"];
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
		$this->nu_os->setDbValue($rs->fields('nu_os'));
		$this->co_os->setDbValue($rs->fields('co_os'));
		$this->no_titulo->setDbValue($rs->fields('no_titulo'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemContratado->setDbValue($rs->fields('nu_itemContratado'));
		$this->nu_areaSolicitante->setDbValue($rs->fields('nu_areaSolicitante'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		if (array_key_exists('EV__nu_projeto', $rs->fields)) {
			$this->nu_projeto->VirtualValue = $rs->fields('EV__nu_projeto'); // Set up virtual field value
		} else {
			$this->nu_projeto->VirtualValue = ""; // Clear value
		}
		$this->dt_criacaoOs->setDbValue($rs->fields('dt_criacaoOs'));
		$this->dt_entrega->setDbValue($rs->fields('dt_entrega'));
		$this->nu_stOs->setDbValue($rs->fields('nu_stOs'));
		$this->dt_stOs->setDbValue($rs->fields('dt_stOs'));
		$this->nu_usuarioAnalista->setDbValue($rs->fields('nu_usuarioAnalista'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->vr_os->setDbValue($rs->fields('vr_os'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_os->DbValue = $row['nu_os'];
		$this->co_os->DbValue = $row['co_os'];
		$this->no_titulo->DbValue = $row['no_titulo'];
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->nu_itemContratado->DbValue = $row['nu_itemContratado'];
		$this->nu_areaSolicitante->DbValue = $row['nu_areaSolicitante'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->dt_criacaoOs->DbValue = $row['dt_criacaoOs'];
		$this->dt_entrega->DbValue = $row['dt_entrega'];
		$this->nu_stOs->DbValue = $row['nu_stOs'];
		$this->dt_stOs->DbValue = $row['dt_stOs'];
		$this->nu_usuarioAnalista->DbValue = $row['nu_usuarioAnalista'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->vr_os->DbValue = $row['vr_os'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_os")) <> "")
			$this->nu_os->CurrentValue = $this->getKey("nu_os"); // nu_os
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
		if ($this->vr_os->FormValue == $this->vr_os->CurrentValue && is_numeric(ew_StrToFloat($this->vr_os->CurrentValue)))
			$this->vr_os->CurrentValue = ew_StrToFloat($this->vr_os->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_os

		$this->nu_os->CellCssStyle = "white-space: nowrap;";

		// co_os
		// no_titulo
		// nu_contrato
		// nu_itemContratado
		// nu_areaSolicitante
		// nu_projeto
		// dt_criacaoOs
		// dt_entrega
		// nu_stOs
		// dt_stOs
		// nu_usuarioAnalista
		// ds_observacoes
		// vr_os
		// Accumulate aggregate value

		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			if (is_numeric($this->vr_os->CurrentValue))
				$this->vr_os->Total += $this->vr_os->CurrentValue; // Accumulate total
		}
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_os
			$this->nu_os->ViewValue = $this->nu_os->CurrentValue;
			$this->nu_os->ViewCustomAttributes = "";

			// co_os
			$this->co_os->ViewValue = $this->co_os->CurrentValue;
			$this->co_os->ViewValue = ew_FormatNumber($this->co_os->ViewValue, 0, 0, 0, 0);
			$this->co_os->ViewCustomAttributes = "";

			// no_titulo
			$this->no_titulo->ViewValue = $this->no_titulo->CurrentValue;
			$this->no_titulo->ViewCustomAttributes = "";

			// nu_contrato
			if (strval($this->nu_contrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contrato], [nu_contrato] AS [DispFld], [no_contrato] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_contrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contrato->ViewValue = $rswrk->fields('DispFld');
					$this->nu_contrato->ViewValue .= ew_ValueSeparator(1,$this->nu_contrato) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
				}
			} else {
				$this->nu_contrato->ViewValue = NULL;
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemContratado
			if (strval($this->nu_itemContratado->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContratado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContratado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemContratado->ViewValue = $rswrk->fields('DispFld');
					$this->nu_itemContratado->ViewValue .= ew_ValueSeparator(1,$this->nu_itemContratado) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_itemContratado->ViewValue = $this->nu_itemContratado->CurrentValue;
				}
			} else {
				$this->nu_itemContratado->ViewValue = NULL;
			}
			$this->nu_itemContratado->ViewCustomAttributes = "";

			// nu_areaSolicitante
			if (strval($this->nu_areaSolicitante->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaSolicitante->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaSolicitante, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaSolicitante->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaSolicitante->ViewValue = $this->nu_areaSolicitante->CurrentValue;
				}
			} else {
				$this->nu_areaSolicitante->ViewValue = NULL;
			}
			$this->nu_areaSolicitante->ViewCustomAttributes = "";

			// nu_projeto
			if ($this->nu_projeto->VirtualValue <> "") {
				$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
			} else {
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
			}
			$this->nu_projeto->ViewCustomAttributes = "";

			// dt_criacaoOs
			$this->dt_criacaoOs->ViewValue = $this->dt_criacaoOs->CurrentValue;
			$this->dt_criacaoOs->ViewValue = ew_FormatDateTime($this->dt_criacaoOs->ViewValue, 7);
			$this->dt_criacaoOs->ViewCustomAttributes = "";

			// dt_entrega
			$this->dt_entrega->ViewValue = $this->dt_entrega->CurrentValue;
			$this->dt_entrega->ViewValue = ew_FormatDateTime($this->dt_entrega->ViewValue, 7);
			$this->dt_entrega->ViewCustomAttributes = "";

			// nu_stOs
			if (strval($this->nu_stOs->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stOs]" . ew_SearchString("=", $this->nu_stOs->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stOs], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stos]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stOs, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stUc] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stOs->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stOs->ViewValue = $this->nu_stOs->CurrentValue;
				}
			} else {
				$this->nu_stOs->ViewValue = NULL;
			}
			$this->nu_stOs->ViewCustomAttributes = "";

			// dt_stOs
			$this->dt_stOs->ViewValue = $this->dt_stOs->CurrentValue;
			$this->dt_stOs->ViewValue = ew_FormatDateTime($this->dt_stOs->ViewValue, 7);
			$this->dt_stOs->ViewCustomAttributes = "";

			// nu_usuarioAnalista
			if (strval($this->nu_usuarioAnalista->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAnalista->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAnalista, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAnalista->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAnalista->ViewValue = $this->nu_usuarioAnalista->CurrentValue;
				}
			} else {
				$this->nu_usuarioAnalista->ViewValue = NULL;
			}
			$this->nu_usuarioAnalista->ViewCustomAttributes = "";

			// vr_os
			$this->vr_os->ViewValue = $this->vr_os->CurrentValue;
			$this->vr_os->ViewValue = ew_FormatCurrency($this->vr_os->ViewValue, 2, -2, -2, -2);
			$this->vr_os->ViewCustomAttributes = "";

			// co_os
			$this->co_os->LinkCustomAttributes = "";
			$this->co_os->HrefValue = "";
			$this->co_os->TooltipValue = "";

			// no_titulo
			$this->no_titulo->LinkCustomAttributes = "";
			$this->no_titulo->HrefValue = "";
			$this->no_titulo->TooltipValue = "";

			// nu_areaSolicitante
			$this->nu_areaSolicitante->LinkCustomAttributes = "";
			$this->nu_areaSolicitante->HrefValue = "";
			$this->nu_areaSolicitante->TooltipValue = "";

			// nu_stOs
			$this->nu_stOs->LinkCustomAttributes = "";
			$this->nu_stOs->HrefValue = "";
			$this->nu_stOs->TooltipValue = "";

			// nu_usuarioAnalista
			$this->nu_usuarioAnalista->LinkCustomAttributes = "";
			$this->nu_usuarioAnalista->HrefValue = "";
			$this->nu_usuarioAnalista->TooltipValue = "";

			// vr_os
			$this->vr_os->LinkCustomAttributes = "";
			$this->vr_os->HrefValue = "";
			$this->vr_os->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// co_os
			$this->co_os->EditCustomAttributes = "";
			$this->co_os->EditValue = ew_HtmlEncode($this->co_os->AdvancedSearch->SearchValue);
			$this->co_os->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_os->FldCaption()));

			// no_titulo
			$this->no_titulo->EditCustomAttributes = "";
			$this->no_titulo->EditValue = ew_HtmlEncode($this->no_titulo->AdvancedSearch->SearchValue);
			$this->no_titulo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_titulo->FldCaption()));

			// nu_areaSolicitante
			$this->nu_areaSolicitante->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaSolicitante, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_areaSolicitante->EditValue = $arwrk;

			// nu_stOs
			$this->nu_stOs->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stOs], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stos]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stOs, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stUc] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stOs->EditValue = $arwrk;

			// nu_usuarioAnalista
			$this->nu_usuarioAnalista->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["os"]->UserIDAllow($GLOBALS["os"]->CurrentAction)) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAnalista, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioAnalista->EditValue = $arwrk;

			// vr_os
			$this->vr_os->EditCustomAttributes = "";
			$this->vr_os->EditValue = ew_HtmlEncode($this->vr_os->AdvancedSearch->SearchValue);
			$this->vr_os->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_os->FldCaption()));
			$this->vr_os->EditCustomAttributes = "";
			$this->vr_os->EditValue2 = ew_HtmlEncode($this->vr_os->AdvancedSearch->SearchValue2);
			$this->vr_os->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_os->FldCaption()));
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->vr_os->Total = 0; // Initialize total
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->vr_os->CurrentValue = $this->vr_os->Total;
			$this->vr_os->ViewValue = $this->vr_os->CurrentValue;
			$this->vr_os->ViewValue = ew_FormatCurrency($this->vr_os->ViewValue, 2, -2, -2, -2);
			$this->vr_os->ViewCustomAttributes = "";
			$this->vr_os->HrefValue = ""; // Clear href value
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
		if (!ew_CheckNumber($this->vr_os->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->vr_os->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_os->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->vr_os->FldErrMsg());
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
		$this->co_os->AdvancedSearch->Load();
		$this->no_titulo->AdvancedSearch->Load();
		$this->nu_contrato->AdvancedSearch->Load();
		$this->nu_itemContratado->AdvancedSearch->Load();
		$this->nu_areaSolicitante->AdvancedSearch->Load();
		$this->nu_projeto->AdvancedSearch->Load();
		$this->dt_criacaoOs->AdvancedSearch->Load();
		$this->dt_entrega->AdvancedSearch->Load();
		$this->nu_stOs->AdvancedSearch->Load();
		$this->dt_stOs->AdvancedSearch->Load();
		$this->nu_usuarioAnalista->AdvancedSearch->Load();
		$this->ds_observacoes->AdvancedSearch->Load();
		$this->vr_os->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_os\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_os',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.foslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->co_os); // co_os
		$this->AddSearchQueryString($sQry, $this->no_titulo); // no_titulo
		$this->AddSearchQueryString($sQry, $this->nu_contrato); // nu_contrato
		$this->AddSearchQueryString($sQry, $this->nu_itemContratado); // nu_itemContratado
		$this->AddSearchQueryString($sQry, $this->nu_areaSolicitante); // nu_areaSolicitante
		$this->AddSearchQueryString($sQry, $this->nu_projeto); // nu_projeto
		$this->AddSearchQueryString($sQry, $this->dt_criacaoOs); // dt_criacaoOs
		$this->AddSearchQueryString($sQry, $this->dt_entrega); // dt_entrega
		$this->AddSearchQueryString($sQry, $this->nu_stOs); // nu_stOs
		$this->AddSearchQueryString($sQry, $this->dt_stOs); // dt_stOs
		$this->AddSearchQueryString($sQry, $this->nu_usuarioAnalista); // nu_usuarioAnalista
		$this->AddSearchQueryString($sQry, $this->ds_observacoes); // ds_observacoes
		$this->AddSearchQueryString($sQry, $this->vr_os); // vr_os

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
		$table = 'os';
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
if (!isset($os_list)) $os_list = new cos_list();

// Page init
$os_list->Page_Init();

// Page main
$os_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$os_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($os->Export == "") { ?>
<script type="text/javascript">

// Page object
var os_list = new ew_Page("os_list");
os_list.PageID = "list"; // Page ID
var EW_PAGE_ID = os_list.PageID; // For backward compatibility

// Form object
var foslist = new ew_Form("foslist");
foslist.FormKeyCountName = '<?php echo $os_list->FormKeyCountName ?>';

// Form_CustomValidate event
foslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
foslist.ValidateRequired = true;
<?php } else { ?>
foslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
foslist.Lists["x_nu_areaSolicitante"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
foslist.Lists["x_nu_stOs"] = {"LinkField":"x_nu_stOs","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
foslist.Lists["x_nu_usuarioAnalista"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var foslistsrch = new ew_Form("foslistsrch");

// Validate function for search
foslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_vr_os");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($os->vr_os->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
foslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
foslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
foslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
foslistsrch.Lists["x_nu_areaSolicitante"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
foslistsrch.Lists["x_nu_stOs"] = {"LinkField":"x_nu_stOs","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
foslistsrch.Lists["x_nu_usuarioAnalista"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (foslistsrch) foslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($os->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($os_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $os_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$os_list->TotalRecs = $os->SelectRecordCount();
	} else {
		if ($os_list->Recordset = $os_list->LoadRecordset())
			$os_list->TotalRecs = $os_list->Recordset->RecordCount();
	}
	$os_list->StartRec = 1;
	if ($os_list->DisplayRecs <= 0 || ($os->Export <> "" && $os->ExportAll)) // Display all records
		$os_list->DisplayRecs = $os_list->TotalRecs;
	if (!($os->Export <> "" && $os->ExportAll))
		$os_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$os_list->Recordset = $os_list->LoadRecordset($os_list->StartRec-1, $os_list->DisplayRecs);
$os_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($os->Export == "" && $os->CurrentAction == "") { ?>
<form name="foslistsrch" id="foslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="foslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#foslistsrch_SearchGroup" href="#foslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="foslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="foslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="os">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$os_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$os->RowType = EW_ROWTYPE_SEARCH;

// Render row
$os->ResetAttrs();
$os_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($os->nu_areaSolicitante->Visible) { // nu_areaSolicitante ?>
	<span id="xsc_nu_areaSolicitante" class="ewCell">
		<span class="ewSearchCaption"><?php echo $os->nu_areaSolicitante->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_areaSolicitante" id="z_nu_areaSolicitante" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_areaSolicitante" id="x_nu_areaSolicitante" name="x_nu_areaSolicitante"<?php echo $os->nu_areaSolicitante->EditAttributes() ?>>
<?php
if (is_array($os->nu_areaSolicitante->EditValue)) {
	$arwrk = $os->nu_areaSolicitante->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($os->nu_areaSolicitante->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
foslistsrch.Lists["x_nu_areaSolicitante"].Options = <?php echo (is_array($os->nu_areaSolicitante->EditValue)) ? ew_ArrayToJson($os->nu_areaSolicitante->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($os->nu_stOs->Visible) { // nu_stOs ?>
	<span id="xsc_nu_stOs" class="ewCell">
		<span class="ewSearchCaption"><?php echo $os->nu_stOs->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_stOs" id="z_nu_stOs" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_stOs" id="x_nu_stOs" name="x_nu_stOs"<?php echo $os->nu_stOs->EditAttributes() ?>>
<?php
if (is_array($os->nu_stOs->EditValue)) {
	$arwrk = $os->nu_stOs->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($os->nu_stOs->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
foslistsrch.Lists["x_nu_stOs"].Options = <?php echo (is_array($os->nu_stOs->EditValue)) ? ew_ArrayToJson($os->nu_stOs->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($os->nu_usuarioAnalista->Visible) { // nu_usuarioAnalista ?>
	<span id="xsc_nu_usuarioAnalista" class="ewCell">
		<span class="ewSearchCaption"><?php echo $os->nu_usuarioAnalista->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_usuarioAnalista" id="z_nu_usuarioAnalista" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_usuarioAnalista" id="x_nu_usuarioAnalista" name="x_nu_usuarioAnalista"<?php echo $os->nu_usuarioAnalista->EditAttributes() ?>>
<?php
if (is_array($os->nu_usuarioAnalista->EditValue)) {
	$arwrk = $os->nu_usuarioAnalista->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($os->nu_usuarioAnalista->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
foslistsrch.Lists["x_nu_usuarioAnalista"].Options = <?php echo (is_array($os->nu_usuarioAnalista->EditValue)) ? ew_ArrayToJson($os->nu_usuarioAnalista->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($os->vr_os->Visible) { // vr_os ?>
	<span id="xsc_vr_os" class="ewCell">
		<span class="ewSearchCaption"><?php echo $os->vr_os->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("<") ?><input type="hidden" name="z_vr_os" id="z_vr_os" value="<"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_vr_os" name="x_vr_os" id="x_vr_os" size="30" placeholder="<?php echo $os->vr_os->PlaceHolder ?>" value="<?php echo $os->vr_os->EditValue ?>"<?php echo $os->vr_os->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw0_vr_os"><label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="v_vr_os" id="v_vr_os" value="AND"<?php if ($os->vr_os->AdvancedSearch->SearchCondition <> "OR") echo " checked=\"checked\"" ?>><?php echo $Language->Phrase("AND") ?></label><label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="v_vr_os" id="v_vr_os" value="OR"<?php if ($os->vr_os->AdvancedSearch->SearchCondition == "OR") echo " checked=\"checked\"" ?>><?php echo $Language->Phrase("OR") ?></label>&nbsp;</span>
		<span class="ewSearchOperator btw0_vr_os"><?php echo $Language->Phrase(">") ?><input type="hidden" name="w_vr_os" id="w_vr_os" value=">"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_vr_os" name="y_vr_os" id="y_vr_os" size="30" placeholder="<?php echo $os->vr_os->PlaceHolder ?>" value="<?php echo $os->vr_os->EditValue2 ?>"<?php echo $os->vr_os->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $os_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $os_list->ShowPageHeader(); ?>
<?php
$os_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="foslist" id="foslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="os">
<div id="gmp_os" class="ewGridMiddlePanel">
<?php if ($os_list->TotalRecs > 0) { ?>
<table id="tbl_oslist" class="ewTable ewTableSeparate">
<?php echo $os->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$os_list->RenderListOptions();

// Render list options (header, left)
$os_list->ListOptions->Render("header", "left");
?>
<?php if ($os->co_os->Visible) { // co_os ?>
	<?php if ($os->SortUrl($os->co_os) == "") { ?>
		<td><div id="elh_os_co_os" class="os_co_os"><div class="ewTableHeaderCaption"><?php echo $os->co_os->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $os->SortUrl($os->co_os) ?>',2);"><div id="elh_os_co_os" class="os_co_os">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $os->co_os->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($os->co_os->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($os->co_os->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($os->no_titulo->Visible) { // no_titulo ?>
	<?php if ($os->SortUrl($os->no_titulo) == "") { ?>
		<td><div id="elh_os_no_titulo" class="os_no_titulo"><div class="ewTableHeaderCaption"><?php echo $os->no_titulo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $os->SortUrl($os->no_titulo) ?>',2);"><div id="elh_os_no_titulo" class="os_no_titulo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $os->no_titulo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($os->no_titulo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($os->no_titulo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($os->nu_areaSolicitante->Visible) { // nu_areaSolicitante ?>
	<?php if ($os->SortUrl($os->nu_areaSolicitante) == "") { ?>
		<td><div id="elh_os_nu_areaSolicitante" class="os_nu_areaSolicitante"><div class="ewTableHeaderCaption"><?php echo $os->nu_areaSolicitante->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $os->SortUrl($os->nu_areaSolicitante) ?>',2);"><div id="elh_os_nu_areaSolicitante" class="os_nu_areaSolicitante">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $os->nu_areaSolicitante->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($os->nu_areaSolicitante->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($os->nu_areaSolicitante->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($os->nu_stOs->Visible) { // nu_stOs ?>
	<?php if ($os->SortUrl($os->nu_stOs) == "") { ?>
		<td><div id="elh_os_nu_stOs" class="os_nu_stOs"><div class="ewTableHeaderCaption"><?php echo $os->nu_stOs->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $os->SortUrl($os->nu_stOs) ?>',2);"><div id="elh_os_nu_stOs" class="os_nu_stOs">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $os->nu_stOs->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($os->nu_stOs->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($os->nu_stOs->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($os->nu_usuarioAnalista->Visible) { // nu_usuarioAnalista ?>
	<?php if ($os->SortUrl($os->nu_usuarioAnalista) == "") { ?>
		<td><div id="elh_os_nu_usuarioAnalista" class="os_nu_usuarioAnalista"><div class="ewTableHeaderCaption"><?php echo $os->nu_usuarioAnalista->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $os->SortUrl($os->nu_usuarioAnalista) ?>',2);"><div id="elh_os_nu_usuarioAnalista" class="os_nu_usuarioAnalista">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $os->nu_usuarioAnalista->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($os->nu_usuarioAnalista->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($os->nu_usuarioAnalista->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($os->vr_os->Visible) { // vr_os ?>
	<?php if ($os->SortUrl($os->vr_os) == "") { ?>
		<td><div id="elh_os_vr_os" class="os_vr_os"><div class="ewTableHeaderCaption"><?php echo $os->vr_os->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $os->SortUrl($os->vr_os) ?>',2);"><div id="elh_os_vr_os" class="os_vr_os">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $os->vr_os->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($os->vr_os->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($os->vr_os->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$os_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($os->ExportAll && $os->Export <> "") {
	$os_list->StopRec = $os_list->TotalRecs;
} else {

	// Set the last record to display
	if ($os_list->TotalRecs > $os_list->StartRec + $os_list->DisplayRecs - 1)
		$os_list->StopRec = $os_list->StartRec + $os_list->DisplayRecs - 1;
	else
		$os_list->StopRec = $os_list->TotalRecs;
}
$os_list->RecCnt = $os_list->StartRec - 1;
if ($os_list->Recordset && !$os_list->Recordset->EOF) {
	$os_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $os_list->StartRec > 1)
		$os_list->Recordset->Move($os_list->StartRec - 1);
} elseif (!$os->AllowAddDeleteRow && $os_list->StopRec == 0) {
	$os_list->StopRec = $os->GridAddRowCount;
}

// Initialize aggregate
$os->RowType = EW_ROWTYPE_AGGREGATEINIT;
$os->ResetAttrs();
$os_list->RenderRow();
while ($os_list->RecCnt < $os_list->StopRec) {
	$os_list->RecCnt++;
	if (intval($os_list->RecCnt) >= intval($os_list->StartRec)) {
		$os_list->RowCnt++;

		// Set up key count
		$os_list->KeyCount = $os_list->RowIndex;

		// Init row class and style
		$os->ResetAttrs();
		$os->CssClass = "";
		if ($os->CurrentAction == "gridadd") {
		} else {
			$os_list->LoadRowValues($os_list->Recordset); // Load row values
		}
		$os->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$os->RowAttrs = array_merge($os->RowAttrs, array('data-rowindex'=>$os_list->RowCnt, 'id'=>'r' . $os_list->RowCnt . '_os', 'data-rowtype'=>$os->RowType));

		// Render row
		$os_list->RenderRow();

		// Render list options
		$os_list->RenderListOptions();
?>
	<tr<?php echo $os->RowAttributes() ?>>
<?php

// Render list options (body, left)
$os_list->ListOptions->Render("body", "left", $os_list->RowCnt);
?>
	<?php if ($os->co_os->Visible) { // co_os ?>
		<td<?php echo $os->co_os->CellAttributes() ?>>
<span<?php echo $os->co_os->ViewAttributes() ?>>
<?php echo $os->co_os->ListViewValue() ?></span>
<a id="<?php echo $os_list->PageObjName . "_row_" . $os_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($os->no_titulo->Visible) { // no_titulo ?>
		<td<?php echo $os->no_titulo->CellAttributes() ?>>
<span<?php echo $os->no_titulo->ViewAttributes() ?>>
<?php echo $os->no_titulo->ListViewValue() ?></span>
<a id="<?php echo $os_list->PageObjName . "_row_" . $os_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($os->nu_areaSolicitante->Visible) { // nu_areaSolicitante ?>
		<td<?php echo $os->nu_areaSolicitante->CellAttributes() ?>>
<span<?php echo $os->nu_areaSolicitante->ViewAttributes() ?>>
<?php echo $os->nu_areaSolicitante->ListViewValue() ?></span>
<a id="<?php echo $os_list->PageObjName . "_row_" . $os_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($os->nu_stOs->Visible) { // nu_stOs ?>
		<td<?php echo $os->nu_stOs->CellAttributes() ?>>
<span<?php echo $os->nu_stOs->ViewAttributes() ?>>
<?php echo $os->nu_stOs->ListViewValue() ?></span>
<a id="<?php echo $os_list->PageObjName . "_row_" . $os_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($os->nu_usuarioAnalista->Visible) { // nu_usuarioAnalista ?>
		<td<?php echo $os->nu_usuarioAnalista->CellAttributes() ?>>
<span<?php echo $os->nu_usuarioAnalista->ViewAttributes() ?>>
<?php echo $os->nu_usuarioAnalista->ListViewValue() ?></span>
<a id="<?php echo $os_list->PageObjName . "_row_" . $os_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($os->vr_os->Visible) { // vr_os ?>
		<td<?php echo $os->vr_os->CellAttributes() ?>>
<span<?php echo $os->vr_os->ViewAttributes() ?>>
<?php echo $os->vr_os->ListViewValue() ?></span>
<a id="<?php echo $os_list->PageObjName . "_row_" . $os_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$os_list->ListOptions->Render("body", "right", $os_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($os->CurrentAction <> "gridadd")
		$os_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$os->RowType = EW_ROWTYPE_AGGREGATE;
$os->ResetAttrs();
$os_list->RenderRow();
?>
<?php if ($os_list->TotalRecs > 0 && ($os->CurrentAction <> "gridadd" && $os->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$os_list->RenderListOptions();

// Render list options (footer, left)
$os_list->ListOptions->Render("footer", "left");
?>
	<?php if ($os->co_os->Visible) { // co_os ?>
		<td><span id="elf_os_co_os" class="os_co_os">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($os->no_titulo->Visible) { // no_titulo ?>
		<td><span id="elf_os_no_titulo" class="os_no_titulo">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($os->nu_areaSolicitante->Visible) { // nu_areaSolicitante ?>
		<td><span id="elf_os_nu_areaSolicitante" class="os_nu_areaSolicitante">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($os->nu_stOs->Visible) { // nu_stOs ?>
		<td><span id="elf_os_nu_stOs" class="os_nu_stOs">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($os->nu_usuarioAnalista->Visible) { // nu_usuarioAnalista ?>
		<td><span id="elf_os_nu_usuarioAnalista" class="os_nu_usuarioAnalista">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($os->vr_os->Visible) { // vr_os ?>
		<td><span id="elf_os_vr_os" class="os_vr_os">
<span class="ewAggregate"><?php echo $Language->Phrase("TOTAL") ?>: </span>
<?php echo $os->vr_os->ViewValue ?>
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$os_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($os->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($os_list->Recordset)
	$os_list->Recordset->Close();
?>
<?php if ($os->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($os->CurrentAction <> "gridadd" && $os->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($os_list->Pager)) $os_list->Pager = new cNumericPager($os_list->StartRec, $os_list->DisplayRecs, $os_list->TotalRecs, $os_list->RecRange) ?>
<?php if ($os_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($os_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $os_list->PageUrl() ?>start=<?php echo $os_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($os_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $os_list->PageUrl() ?>start=<?php echo $os_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($os_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $os_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($os_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $os_list->PageUrl() ?>start=<?php echo $os_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($os_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $os_list->PageUrl() ?>start=<?php echo $os_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($os_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $os_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $os_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $os_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($os_list->SearchWhere == "0=101") { ?>
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
	foreach ($os_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($os->Export == "") { ?>
<script type="text/javascript">
foslistsrch.Init();
foslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$os_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($os->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$os_list->Page_Terminate();
?>
