<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "termoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$termo_list = NULL; // Initialize page object first

class ctermo_list extends ctermo {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'termo';

	// Page object name
	var $PageObjName = 'termo_list';

	// Grid form hidden field names
	var $FormName = 'ftermolist';
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

		// Table object (termo)
		if (!isset($GLOBALS["termo"])) {
			$GLOBALS["termo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["termo"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "termoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "termodelete.php";
		$this->MultiUpdateUrl = "termoupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'termo', TRUE);

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
		$this->nu_termo->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->nu_termo->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_termo->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_termo, FALSE); // nu_termo
		$this->BuildSearchSql($sWhere, $this->no_termo, FALSE); // no_termo
		$this->BuildSearchSql($sWhere, $this->ds_termo, FALSE); // ds_termo
		$this->BuildSearchSql($sWhere, $this->ic_tpTermo, FALSE); // ic_tpTermo
		$this->BuildSearchSql($sWhere, $this->dt_emissao, FALSE); // dt_emissao
		$this->BuildSearchSql($sWhere, $this->im_anexo, FALSE); // im_anexo
		$this->BuildSearchSql($sWhere, $this->ic_situacao, FALSE); // ic_situacao
		$this->BuildSearchSql($sWhere, $this->nu_usuario, FALSE); // nu_usuario
		$this->BuildSearchSql($sWhere, $this->ts_datahora, FALSE); // ts_datahora

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_termo->AdvancedSearch->Save(); // nu_termo
			$this->no_termo->AdvancedSearch->Save(); // no_termo
			$this->ds_termo->AdvancedSearch->Save(); // ds_termo
			$this->ic_tpTermo->AdvancedSearch->Save(); // ic_tpTermo
			$this->dt_emissao->AdvancedSearch->Save(); // dt_emissao
			$this->im_anexo->AdvancedSearch->Save(); // im_anexo
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
		if ($this->nu_termo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_termo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_termo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_tpTermo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_emissao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_anexo->AdvancedSearch->IssetSession())
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
		$this->nu_termo->AdvancedSearch->UnsetSession();
		$this->no_termo->AdvancedSearch->UnsetSession();
		$this->ds_termo->AdvancedSearch->UnsetSession();
		$this->ic_tpTermo->AdvancedSearch->UnsetSession();
		$this->dt_emissao->AdvancedSearch->UnsetSession();
		$this->im_anexo->AdvancedSearch->UnsetSession();
		$this->ic_situacao->AdvancedSearch->UnsetSession();
		$this->nu_usuario->AdvancedSearch->UnsetSession();
		$this->ts_datahora->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_termo->AdvancedSearch->Load();
		$this->no_termo->AdvancedSearch->Load();
		$this->ds_termo->AdvancedSearch->Load();
		$this->ic_tpTermo->AdvancedSearch->Load();
		$this->dt_emissao->AdvancedSearch->Load();
		$this->im_anexo->AdvancedSearch->Load();
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
			$this->UpdateSort($this->nu_termo, $bCtrl); // nu_termo
			$this->UpdateSort($this->no_termo, $bCtrl); // no_termo
			$this->UpdateSort($this->ic_tpTermo, $bCtrl); // ic_tpTermo
			$this->UpdateSort($this->dt_emissao, $bCtrl); // dt_emissao
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
				$this->nu_termo->setSort("");
				$this->no_termo->setSort("");
				$this->ic_tpTermo->setSort("");
				$this->dt_emissao->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.ftermolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_termo

		$this->nu_termo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_termo"]);
		if ($this->nu_termo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_termo->AdvancedSearch->SearchOperator = @$_GET["z_nu_termo"];

		// no_termo
		$this->no_termo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_termo"]);
		if ($this->no_termo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_termo->AdvancedSearch->SearchOperator = @$_GET["z_no_termo"];

		// ds_termo
		$this->ds_termo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_termo"]);
		if ($this->ds_termo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_termo->AdvancedSearch->SearchOperator = @$_GET["z_ds_termo"];

		// ic_tpTermo
		$this->ic_tpTermo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_tpTermo"]);
		if ($this->ic_tpTermo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_tpTermo->AdvancedSearch->SearchOperator = @$_GET["z_ic_tpTermo"];

		// dt_emissao
		$this->dt_emissao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_emissao"]);
		if ($this->dt_emissao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_emissao->AdvancedSearch->SearchOperator = @$_GET["z_dt_emissao"];

		// im_anexo
		$this->im_anexo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_anexo"]);
		if ($this->im_anexo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_anexo->AdvancedSearch->SearchOperator = @$_GET["z_im_anexo"];

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
		$this->nu_termo->setDbValue($rs->fields('nu_termo'));
		$this->no_termo->setDbValue($rs->fields('no_termo'));
		$this->ds_termo->setDbValue($rs->fields('ds_termo'));
		$this->ic_tpTermo->setDbValue($rs->fields('ic_tpTermo'));
		$this->dt_emissao->setDbValue($rs->fields('dt_emissao'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_termo->DbValue = $row['nu_termo'];
		$this->no_termo->DbValue = $row['no_termo'];
		$this->ds_termo->DbValue = $row['ds_termo'];
		$this->ic_tpTermo->DbValue = $row['ic_tpTermo'];
		$this->dt_emissao->DbValue = $row['dt_emissao'];
		$this->im_anexo->Upload->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_termo")) <> "")
			$this->nu_termo->CurrentValue = $this->getKey("nu_termo"); // nu_termo
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
		// nu_termo
		// no_termo
		// ds_termo
		// ic_tpTermo
		// dt_emissao
		// im_anexo
		// ic_situacao
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_termo
			$this->nu_termo->ViewValue = $this->nu_termo->CurrentValue;
			$this->nu_termo->ViewCustomAttributes = "";

			// no_termo
			$this->no_termo->ViewValue = $this->no_termo->CurrentValue;
			$this->no_termo->ViewCustomAttributes = "";

			// ic_tpTermo
			if (strval($this->ic_tpTermo->CurrentValue) <> "") {
				switch ($this->ic_tpTermo->CurrentValue) {
					case $this->ic_tpTermo->FldTagValue(1):
						$this->ic_tpTermo->ViewValue = $this->ic_tpTermo->FldTagCaption(1) <> "" ? $this->ic_tpTermo->FldTagCaption(1) : $this->ic_tpTermo->CurrentValue;
						break;
					default:
						$this->ic_tpTermo->ViewValue = $this->ic_tpTermo->CurrentValue;
				}
			} else {
				$this->ic_tpTermo->ViewValue = NULL;
			}
			$this->ic_tpTermo->ViewCustomAttributes = "";

			// dt_emissao
			$this->dt_emissao->ViewValue = $this->dt_emissao->CurrentValue;
			$this->dt_emissao->ViewValue = ew_FormatDateTime($this->dt_emissao->ViewValue, 7);
			$this->dt_emissao->ViewCustomAttributes = "";

			// im_anexo
			$this->im_anexo->UploadPath = "arquivos/termos";
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->ViewValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->ViewValue = "";
			}
			$this->im_anexo->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
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

			// nu_termo
			$this->nu_termo->LinkCustomAttributes = "";
			$this->nu_termo->HrefValue = "";
			$this->nu_termo->TooltipValue = "";

			// no_termo
			$this->no_termo->LinkCustomAttributes = "";
			$this->no_termo->HrefValue = "";
			$this->no_termo->TooltipValue = "";

			// ic_tpTermo
			$this->ic_tpTermo->LinkCustomAttributes = "";
			$this->ic_tpTermo->HrefValue = "";
			$this->ic_tpTermo->TooltipValue = "";

			// dt_emissao
			$this->dt_emissao->LinkCustomAttributes = "";
			$this->dt_emissao->HrefValue = "";
			$this->dt_emissao->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_termo
			$this->nu_termo->EditCustomAttributes = "";
			$this->nu_termo->EditValue = ew_HtmlEncode($this->nu_termo->AdvancedSearch->SearchValue);
			$this->nu_termo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_termo->FldCaption()));

			// no_termo
			$this->no_termo->EditCustomAttributes = "";
			$this->no_termo->EditValue = ew_HtmlEncode($this->no_termo->AdvancedSearch->SearchValue);
			$this->no_termo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_termo->FldCaption()));

			// ic_tpTermo
			$this->ic_tpTermo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpTermo->FldTagValue(1), $this->ic_tpTermo->FldTagCaption(1) <> "" ? $this->ic_tpTermo->FldTagCaption(1) : $this->ic_tpTermo->FldTagValue(1));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpTermo->EditValue = $arwrk;

			// dt_emissao
			$this->dt_emissao->EditCustomAttributes = "";
			$this->dt_emissao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_emissao->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_emissao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_emissao->FldCaption()));

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
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
		if (!ew_CheckEuroDate($this->dt_emissao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->dt_emissao->FldErrMsg());
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
		$this->nu_termo->AdvancedSearch->Load();
		$this->no_termo->AdvancedSearch->Load();
		$this->ds_termo->AdvancedSearch->Load();
		$this->ic_tpTermo->AdvancedSearch->Load();
		$this->dt_emissao->AdvancedSearch->Load();
		$this->im_anexo->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_termo\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_termo',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ftermolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_termo); // nu_termo
		$this->AddSearchQueryString($sQry, $this->no_termo); // no_termo
		$this->AddSearchQueryString($sQry, $this->ds_termo); // ds_termo
		$this->AddSearchQueryString($sQry, $this->ic_tpTermo); // ic_tpTermo
		$this->AddSearchQueryString($sQry, $this->dt_emissao); // dt_emissao
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
if (!isset($termo_list)) $termo_list = new ctermo_list();

// Page init
$termo_list->Page_Init();

// Page main
$termo_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$termo_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($termo->Export == "") { ?>
<script type="text/javascript">

// Page object
var termo_list = new ew_Page("termo_list");
termo_list.PageID = "list"; // Page ID
var EW_PAGE_ID = termo_list.PageID; // For backward compatibility

// Form object
var ftermolist = new ew_Form("ftermolist");
ftermolist.FormKeyCountName = '<?php echo $termo_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftermolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftermolist.ValidateRequired = true;
<?php } else { ?>
ftermolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var ftermolistsrch = new ew_Form("ftermolistsrch");

// Validate function for search
ftermolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_dt_emissao");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($termo->dt_emissao->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
ftermolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftermolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ftermolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (ftermolistsrch) ftermolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($termo->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($termo_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $termo_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$termo_list->TotalRecs = $termo->SelectRecordCount();
	} else {
		if ($termo_list->Recordset = $termo_list->LoadRecordset())
			$termo_list->TotalRecs = $termo_list->Recordset->RecordCount();
	}
	$termo_list->StartRec = 1;
	if ($termo_list->DisplayRecs <= 0 || ($termo->Export <> "" && $termo->ExportAll)) // Display all records
		$termo_list->DisplayRecs = $termo_list->TotalRecs;
	if (!($termo->Export <> "" && $termo->ExportAll))
		$termo_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$termo_list->Recordset = $termo_list->LoadRecordset($termo_list->StartRec-1, $termo_list->DisplayRecs);
$termo_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($termo->Export == "" && $termo->CurrentAction == "") { ?>
<form name="ftermolistsrch" id="ftermolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="ftermolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#ftermolistsrch_SearchGroup" href="#ftermolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="ftermolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="ftermolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="termo">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$termo_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$termo->RowType = EW_ROWTYPE_SEARCH;

// Render row
$termo->ResetAttrs();
$termo_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($termo->no_termo->Visible) { // no_termo ?>
	<span id="xsc_no_termo" class="ewCell">
		<span class="ewSearchCaption"><?php echo $termo->no_termo->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_termo" id="z_no_termo" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_termo" name="x_no_termo" id="x_no_termo" size="30" maxlength="150" placeholder="<?php echo $termo->no_termo->PlaceHolder ?>" value="<?php echo $termo->no_termo->EditValue ?>"<?php echo $termo->no_termo->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($termo->dt_emissao->Visible) { // dt_emissao ?>
	<span id="xsc_dt_emissao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $termo->dt_emissao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_dt_emissao" id="z_dt_emissao" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dt_emissao" name="x_dt_emissao" id="x_dt_emissao" placeholder="<?php echo $termo->dt_emissao->PlaceHolder ?>" value="<?php echo $termo->dt_emissao->EditValue ?>"<?php echo $termo->dt_emissao->EditAttributes() ?>>
<?php if (!$termo->dt_emissao->ReadOnly && !$termo->dt_emissao->Disabled && @$termo->dt_emissao->EditAttrs["readonly"] == "" && @$termo->dt_emissao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_emissao" name="cal_x_dt_emissao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_emissao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("ftermolistsrch", "x_dt_emissao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $termo_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $termo_list->ShowPageHeader(); ?>
<?php
$termo_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="ftermolist" id="ftermolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="termo">
<div id="gmp_termo" class="ewGridMiddlePanel">
<?php if ($termo_list->TotalRecs > 0) { ?>
<table id="tbl_termolist" class="ewTable ewTableSeparate">
<?php echo $termo->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$termo_list->RenderListOptions();

// Render list options (header, left)
$termo_list->ListOptions->Render("header", "left");
?>
<?php if ($termo->nu_termo->Visible) { // nu_termo ?>
	<?php if ($termo->SortUrl($termo->nu_termo) == "") { ?>
		<td><div id="elh_termo_nu_termo" class="termo_nu_termo"><div class="ewTableHeaderCaption"><?php echo $termo->nu_termo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $termo->SortUrl($termo->nu_termo) ?>',2);"><div id="elh_termo_nu_termo" class="termo_nu_termo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $termo->nu_termo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($termo->nu_termo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($termo->nu_termo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($termo->no_termo->Visible) { // no_termo ?>
	<?php if ($termo->SortUrl($termo->no_termo) == "") { ?>
		<td><div id="elh_termo_no_termo" class="termo_no_termo"><div class="ewTableHeaderCaption"><?php echo $termo->no_termo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $termo->SortUrl($termo->no_termo) ?>',2);"><div id="elh_termo_no_termo" class="termo_no_termo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $termo->no_termo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($termo->no_termo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($termo->no_termo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($termo->ic_tpTermo->Visible) { // ic_tpTermo ?>
	<?php if ($termo->SortUrl($termo->ic_tpTermo) == "") { ?>
		<td><div id="elh_termo_ic_tpTermo" class="termo_ic_tpTermo"><div class="ewTableHeaderCaption"><?php echo $termo->ic_tpTermo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $termo->SortUrl($termo->ic_tpTermo) ?>',2);"><div id="elh_termo_ic_tpTermo" class="termo_ic_tpTermo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $termo->ic_tpTermo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($termo->ic_tpTermo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($termo->ic_tpTermo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($termo->dt_emissao->Visible) { // dt_emissao ?>
	<?php if ($termo->SortUrl($termo->dt_emissao) == "") { ?>
		<td><div id="elh_termo_dt_emissao" class="termo_dt_emissao"><div class="ewTableHeaderCaption"><?php echo $termo->dt_emissao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $termo->SortUrl($termo->dt_emissao) ?>',2);"><div id="elh_termo_dt_emissao" class="termo_dt_emissao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $termo->dt_emissao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($termo->dt_emissao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($termo->dt_emissao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($termo->ic_situacao->Visible) { // ic_situacao ?>
	<?php if ($termo->SortUrl($termo->ic_situacao) == "") { ?>
		<td><div id="elh_termo_ic_situacao" class="termo_ic_situacao"><div class="ewTableHeaderCaption"><?php echo $termo->ic_situacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $termo->SortUrl($termo->ic_situacao) ?>',2);"><div id="elh_termo_ic_situacao" class="termo_ic_situacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $termo->ic_situacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($termo->ic_situacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($termo->ic_situacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$termo_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($termo->ExportAll && $termo->Export <> "") {
	$termo_list->StopRec = $termo_list->TotalRecs;
} else {

	// Set the last record to display
	if ($termo_list->TotalRecs > $termo_list->StartRec + $termo_list->DisplayRecs - 1)
		$termo_list->StopRec = $termo_list->StartRec + $termo_list->DisplayRecs - 1;
	else
		$termo_list->StopRec = $termo_list->TotalRecs;
}
$termo_list->RecCnt = $termo_list->StartRec - 1;
if ($termo_list->Recordset && !$termo_list->Recordset->EOF) {
	$termo_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $termo_list->StartRec > 1)
		$termo_list->Recordset->Move($termo_list->StartRec - 1);
} elseif (!$termo->AllowAddDeleteRow && $termo_list->StopRec == 0) {
	$termo_list->StopRec = $termo->GridAddRowCount;
}

// Initialize aggregate
$termo->RowType = EW_ROWTYPE_AGGREGATEINIT;
$termo->ResetAttrs();
$termo_list->RenderRow();
while ($termo_list->RecCnt < $termo_list->StopRec) {
	$termo_list->RecCnt++;
	if (intval($termo_list->RecCnt) >= intval($termo_list->StartRec)) {
		$termo_list->RowCnt++;

		// Set up key count
		$termo_list->KeyCount = $termo_list->RowIndex;

		// Init row class and style
		$termo->ResetAttrs();
		$termo->CssClass = "";
		if ($termo->CurrentAction == "gridadd") {
		} else {
			$termo_list->LoadRowValues($termo_list->Recordset); // Load row values
		}
		$termo->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$termo->RowAttrs = array_merge($termo->RowAttrs, array('data-rowindex'=>$termo_list->RowCnt, 'id'=>'r' . $termo_list->RowCnt . '_termo', 'data-rowtype'=>$termo->RowType));

		// Render row
		$termo_list->RenderRow();

		// Render list options
		$termo_list->RenderListOptions();
?>
	<tr<?php echo $termo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$termo_list->ListOptions->Render("body", "left", $termo_list->RowCnt);
?>
	<?php if ($termo->nu_termo->Visible) { // nu_termo ?>
		<td<?php echo $termo->nu_termo->CellAttributes() ?>>
<span<?php echo $termo->nu_termo->ViewAttributes() ?>>
<?php echo $termo->nu_termo->ListViewValue() ?></span>
<a id="<?php echo $termo_list->PageObjName . "_row_" . $termo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($termo->no_termo->Visible) { // no_termo ?>
		<td<?php echo $termo->no_termo->CellAttributes() ?>>
<span<?php echo $termo->no_termo->ViewAttributes() ?>>
<?php echo $termo->no_termo->ListViewValue() ?></span>
<a id="<?php echo $termo_list->PageObjName . "_row_" . $termo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($termo->ic_tpTermo->Visible) { // ic_tpTermo ?>
		<td<?php echo $termo->ic_tpTermo->CellAttributes() ?>>
<span<?php echo $termo->ic_tpTermo->ViewAttributes() ?>>
<?php echo $termo->ic_tpTermo->ListViewValue() ?></span>
<a id="<?php echo $termo_list->PageObjName . "_row_" . $termo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($termo->dt_emissao->Visible) { // dt_emissao ?>
		<td<?php echo $termo->dt_emissao->CellAttributes() ?>>
<span<?php echo $termo->dt_emissao->ViewAttributes() ?>>
<?php echo $termo->dt_emissao->ListViewValue() ?></span>
<a id="<?php echo $termo_list->PageObjName . "_row_" . $termo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($termo->ic_situacao->Visible) { // ic_situacao ?>
		<td<?php echo $termo->ic_situacao->CellAttributes() ?>>
<span<?php echo $termo->ic_situacao->ViewAttributes() ?>>
<?php echo $termo->ic_situacao->ListViewValue() ?></span>
<a id="<?php echo $termo_list->PageObjName . "_row_" . $termo_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$termo_list->ListOptions->Render("body", "right", $termo_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($termo->CurrentAction <> "gridadd")
		$termo_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($termo->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($termo_list->Recordset)
	$termo_list->Recordset->Close();
?>
<?php if ($termo->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($termo->CurrentAction <> "gridadd" && $termo->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($termo_list->Pager)) $termo_list->Pager = new cNumericPager($termo_list->StartRec, $termo_list->DisplayRecs, $termo_list->TotalRecs, $termo_list->RecRange) ?>
<?php if ($termo_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($termo_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $termo_list->PageUrl() ?>start=<?php echo $termo_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($termo_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $termo_list->PageUrl() ?>start=<?php echo $termo_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($termo_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $termo_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($termo_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $termo_list->PageUrl() ?>start=<?php echo $termo_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($termo_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $termo_list->PageUrl() ?>start=<?php echo $termo_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($termo_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $termo_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $termo_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $termo_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($termo_list->SearchWhere == "0=101") { ?>
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
	foreach ($termo_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($termo->Export == "") { ?>
<script type="text/javascript">
ftermolistsrch.Init();
ftermolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$termo_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($termo->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$termo_list->Page_Terminate();
?>
