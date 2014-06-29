<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "pessoainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$pessoa_list = NULL; // Initialize page object first

class cpessoa_list extends cpessoa {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'pessoa';

	// Page object name
	var $PageObjName = 'pessoa_list';

	// Grid form hidden field names
	var $FormName = 'fpessoalist';
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

		// Table object (pessoa)
		if (!isset($GLOBALS["pessoa"])) {
			$GLOBALS["pessoa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pessoa"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "pessoaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "pessoadelete.php";
		$this->MultiUpdateUrl = "pessoaupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pessoa', TRUE);

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
		$this->nu_pessoa->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->nu_pessoa->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_pessoa->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_pessoa, FALSE); // nu_pessoa
		$this->BuildSearchSql($sWhere, $this->no_pessoa, FALSE); // no_pessoa
		$this->BuildSearchSql($sWhere, $this->ic_tpEnvolvimento, FALSE); // ic_tpEnvolvimento
		$this->BuildSearchSql($sWhere, $this->nu_cargo, FALSE); // nu_cargo
		$this->BuildSearchSql($sWhere, $this->nu_areaLotacao, FALSE); // nu_areaLotacao
		$this->BuildSearchSql($sWhere, $this->no_email, FALSE); // no_email
		$this->BuildSearchSql($sWhere, $this->ds_telefone1, FALSE); // ds_telefone1
		$this->BuildSearchSql($sWhere, $this->ds_telefone2, FALSE); // ds_telefone2
		$this->BuildSearchSql($sWhere, $this->ic_ativo, FALSE); // ic_ativo

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_pessoa->AdvancedSearch->Save(); // nu_pessoa
			$this->no_pessoa->AdvancedSearch->Save(); // no_pessoa
			$this->ic_tpEnvolvimento->AdvancedSearch->Save(); // ic_tpEnvolvimento
			$this->nu_cargo->AdvancedSearch->Save(); // nu_cargo
			$this->nu_areaLotacao->AdvancedSearch->Save(); // nu_areaLotacao
			$this->no_email->AdvancedSearch->Save(); // no_email
			$this->ds_telefone1->AdvancedSearch->Save(); // ds_telefone1
			$this->ds_telefone2->AdvancedSearch->Save(); // ds_telefone2
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
		if ($this->nu_pessoa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_pessoa->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_tpEnvolvimento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_cargo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_areaLotacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_telefone1->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_telefone2->AdvancedSearch->IssetSession())
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
		$this->ic_tpEnvolvimento->AdvancedSearch->LoadDefault();
		$this->ic_tpEnvolvimento->AdvancedSearch->Save();
		$this->ic_ativo->AdvancedSearch->LoadDefault();
		$this->ic_ativo->AdvancedSearch->Save();
		return TRUE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->nu_pessoa->AdvancedSearch->UnsetSession();
		$this->no_pessoa->AdvancedSearch->UnsetSession();
		$this->ic_tpEnvolvimento->AdvancedSearch->UnsetSession();
		$this->nu_cargo->AdvancedSearch->UnsetSession();
		$this->nu_areaLotacao->AdvancedSearch->UnsetSession();
		$this->no_email->AdvancedSearch->UnsetSession();
		$this->ds_telefone1->AdvancedSearch->UnsetSession();
		$this->ds_telefone2->AdvancedSearch->UnsetSession();
		$this->ic_ativo->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_pessoa->AdvancedSearch->Load();
		$this->no_pessoa->AdvancedSearch->Load();
		$this->ic_tpEnvolvimento->AdvancedSearch->Load();
		$this->nu_cargo->AdvancedSearch->Load();
		$this->nu_areaLotacao->AdvancedSearch->Load();
		$this->no_email->AdvancedSearch->Load();
		$this->ds_telefone1->AdvancedSearch->Load();
		$this->ds_telefone2->AdvancedSearch->Load();
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
			$this->UpdateSort($this->nu_pessoa, $bCtrl); // nu_pessoa
			$this->UpdateSort($this->no_pessoa, $bCtrl); // no_pessoa
			$this->UpdateSort($this->ic_tpEnvolvimento, $bCtrl); // ic_tpEnvolvimento
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
				$this->nu_pessoa->setSort("");
				$this->no_pessoa->setSort("");
				$this->ic_tpEnvolvimento->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fpessoalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_pessoa

		$this->nu_pessoa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_pessoa"]);
		if ($this->nu_pessoa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_pessoa->AdvancedSearch->SearchOperator = @$_GET["z_nu_pessoa"];

		// no_pessoa
		$this->no_pessoa->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_pessoa"]);
		if ($this->no_pessoa->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_pessoa->AdvancedSearch->SearchOperator = @$_GET["z_no_pessoa"];

		// ic_tpEnvolvimento
		$this->ic_tpEnvolvimento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_tpEnvolvimento"]);
		if ($this->ic_tpEnvolvimento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_tpEnvolvimento->AdvancedSearch->SearchOperator = @$_GET["z_ic_tpEnvolvimento"];

		// nu_cargo
		$this->nu_cargo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_cargo"]);
		if ($this->nu_cargo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_cargo->AdvancedSearch->SearchOperator = @$_GET["z_nu_cargo"];

		// nu_areaLotacao
		$this->nu_areaLotacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_areaLotacao"]);
		if ($this->nu_areaLotacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_areaLotacao->AdvancedSearch->SearchOperator = @$_GET["z_nu_areaLotacao"];

		// no_email
		$this->no_email->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_email"]);
		if ($this->no_email->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_email->AdvancedSearch->SearchOperator = @$_GET["z_no_email"];

		// ds_telefone1
		$this->ds_telefone1->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_telefone1"]);
		if ($this->ds_telefone1->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_telefone1->AdvancedSearch->SearchOperator = @$_GET["z_ds_telefone1"];

		// ds_telefone2
		$this->ds_telefone2->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_telefone2"]);
		if ($this->ds_telefone2->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_telefone2->AdvancedSearch->SearchOperator = @$_GET["z_ds_telefone2"];

		// ic_ativo
		$this->ic_ativo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_ativo"]);
		if ($this->ic_ativo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_ativo->AdvancedSearch->SearchOperator = @$_GET["z_ic_ativo"];
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
		$this->nu_pessoa->setDbValue($rs->fields('nu_pessoa'));
		$this->no_pessoa->setDbValue($rs->fields('no_pessoa'));
		$this->ic_tpEnvolvimento->setDbValue($rs->fields('ic_tpEnvolvimento'));
		$this->nu_cargo->setDbValue($rs->fields('nu_cargo'));
		$this->nu_areaLotacao->setDbValue($rs->fields('nu_areaLotacao'));
		$this->no_email->setDbValue($rs->fields('no_email'));
		$this->ds_telefone1->setDbValue($rs->fields('ds_telefone1'));
		$this->ds_telefone2->setDbValue($rs->fields('ds_telefone2'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_pessoa->DbValue = $row['nu_pessoa'];
		$this->no_pessoa->DbValue = $row['no_pessoa'];
		$this->ic_tpEnvolvimento->DbValue = $row['ic_tpEnvolvimento'];
		$this->nu_cargo->DbValue = $row['nu_cargo'];
		$this->nu_areaLotacao->DbValue = $row['nu_areaLotacao'];
		$this->no_email->DbValue = $row['no_email'];
		$this->ds_telefone1->DbValue = $row['ds_telefone1'];
		$this->ds_telefone2->DbValue = $row['ds_telefone2'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_pessoa")) <> "")
			$this->nu_pessoa->CurrentValue = $this->getKey("nu_pessoa"); // nu_pessoa
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
		// nu_pessoa
		// no_pessoa
		// ic_tpEnvolvimento
		// nu_cargo
		// nu_areaLotacao
		// no_email
		// ds_telefone1
		// ds_telefone2
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_pessoa
			$this->nu_pessoa->ViewValue = $this->nu_pessoa->CurrentValue;
			$this->nu_pessoa->ViewCustomAttributes = "";

			// no_pessoa
			$this->no_pessoa->ViewValue = $this->no_pessoa->CurrentValue;
			$this->no_pessoa->ViewCustomAttributes = "";

			// ic_tpEnvolvimento
			if (strval($this->ic_tpEnvolvimento->CurrentValue) <> "") {
				switch ($this->ic_tpEnvolvimento->CurrentValue) {
					case $this->ic_tpEnvolvimento->FldTagValue(1):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(1) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(1) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					case $this->ic_tpEnvolvimento->FldTagValue(2):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(2) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(2) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					case $this->ic_tpEnvolvimento->FldTagValue(3):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(3) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(3) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					case $this->ic_tpEnvolvimento->FldTagValue(4):
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->FldTagCaption(4) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(4) : $this->ic_tpEnvolvimento->CurrentValue;
						break;
					default:
						$this->ic_tpEnvolvimento->ViewValue = $this->ic_tpEnvolvimento->CurrentValue;
				}
			} else {
				$this->ic_tpEnvolvimento->ViewValue = NULL;
			}
			$this->ic_tpEnvolvimento->ViewCustomAttributes = "";

			// nu_cargo
			if (strval($this->nu_cargo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_cargo]" . ew_SearchString("=", $this->nu_cargo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_cargo], [no_cargo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[cargo]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_cargo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_cargo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_cargo->ViewValue = $this->nu_cargo->CurrentValue;
				}
			} else {
				$this->nu_cargo->ViewValue = NULL;
			}
			$this->nu_cargo->ViewCustomAttributes = "";

			// nu_areaLotacao
			if (strval($this->nu_areaLotacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaLotacao->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_areaLotacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaLotacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaLotacao->ViewValue = $this->nu_areaLotacao->CurrentValue;
				}
			} else {
				$this->nu_areaLotacao->ViewValue = NULL;
			}
			$this->nu_areaLotacao->ViewCustomAttributes = "";

			// no_email
			$this->no_email->ViewValue = $this->no_email->CurrentValue;
			$this->no_email->ViewCustomAttributes = "";

			// ds_telefone1
			$this->ds_telefone1->ViewValue = $this->ds_telefone1->CurrentValue;
			$this->ds_telefone1->ViewCustomAttributes = "";

			// ds_telefone2
			$this->ds_telefone2->ViewValue = $this->ds_telefone2->CurrentValue;
			$this->ds_telefone2->ViewCustomAttributes = "";

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

			// nu_pessoa
			$this->nu_pessoa->LinkCustomAttributes = "";
			$this->nu_pessoa->HrefValue = "";
			$this->nu_pessoa->TooltipValue = "";

			// no_pessoa
			$this->no_pessoa->LinkCustomAttributes = "";
			$this->no_pessoa->HrefValue = "";
			$this->no_pessoa->TooltipValue = "";

			// ic_tpEnvolvimento
			$this->ic_tpEnvolvimento->LinkCustomAttributes = "";
			$this->ic_tpEnvolvimento->HrefValue = "";
			$this->ic_tpEnvolvimento->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_pessoa
			$this->nu_pessoa->EditCustomAttributes = "";
			$this->nu_pessoa->EditValue = ew_HtmlEncode($this->nu_pessoa->AdvancedSearch->SearchValue);
			$this->nu_pessoa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_pessoa->FldCaption()));

			// no_pessoa
			$this->no_pessoa->EditCustomAttributes = "";
			$this->no_pessoa->EditValue = ew_HtmlEncode($this->no_pessoa->AdvancedSearch->SearchValue);
			$this->no_pessoa->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_pessoa->FldCaption()));

			// ic_tpEnvolvimento
			$this->ic_tpEnvolvimento->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpEnvolvimento->FldTagValue(1), $this->ic_tpEnvolvimento->FldTagCaption(1) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(1) : $this->ic_tpEnvolvimento->FldTagValue(1));
			$arwrk[] = array($this->ic_tpEnvolvimento->FldTagValue(2), $this->ic_tpEnvolvimento->FldTagCaption(2) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(2) : $this->ic_tpEnvolvimento->FldTagValue(2));
			$arwrk[] = array($this->ic_tpEnvolvimento->FldTagValue(3), $this->ic_tpEnvolvimento->FldTagCaption(3) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(3) : $this->ic_tpEnvolvimento->FldTagValue(3));
			$arwrk[] = array($this->ic_tpEnvolvimento->FldTagValue(4), $this->ic_tpEnvolvimento->FldTagCaption(4) <> "" ? $this->ic_tpEnvolvimento->FldTagCaption(4) : $this->ic_tpEnvolvimento->FldTagValue(4));
			$this->ic_tpEnvolvimento->EditValue = $arwrk;

			// ic_ativo
			$this->ic_ativo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_ativo->FldTagValue(1), $this->ic_ativo->FldTagCaption(1) <> "" ? $this->ic_ativo->FldTagCaption(1) : $this->ic_ativo->FldTagValue(1));
			$arwrk[] = array($this->ic_ativo->FldTagValue(2), $this->ic_ativo->FldTagCaption(2) <> "" ? $this->ic_ativo->FldTagCaption(2) : $this->ic_ativo->FldTagValue(2));
			$this->ic_ativo->EditValue = $arwrk;
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
		$this->nu_pessoa->AdvancedSearch->Load();
		$this->no_pessoa->AdvancedSearch->Load();
		$this->ic_tpEnvolvimento->AdvancedSearch->Load();
		$this->nu_cargo->AdvancedSearch->Load();
		$this->nu_areaLotacao->AdvancedSearch->Load();
		$this->no_email->AdvancedSearch->Load();
		$this->ds_telefone1->AdvancedSearch->Load();
		$this->ds_telefone2->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_pessoa\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pessoa',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fpessoalist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_pessoa); // nu_pessoa
		$this->AddSearchQueryString($sQry, $this->no_pessoa); // no_pessoa
		$this->AddSearchQueryString($sQry, $this->ic_tpEnvolvimento); // ic_tpEnvolvimento
		$this->AddSearchQueryString($sQry, $this->nu_cargo); // nu_cargo
		$this->AddSearchQueryString($sQry, $this->nu_areaLotacao); // nu_areaLotacao
		$this->AddSearchQueryString($sQry, $this->no_email); // no_email
		$this->AddSearchQueryString($sQry, $this->ds_telefone1); // ds_telefone1
		$this->AddSearchQueryString($sQry, $this->ds_telefone2); // ds_telefone2
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
if (!isset($pessoa_list)) $pessoa_list = new cpessoa_list();

// Page init
$pessoa_list->Page_Init();

// Page main
$pessoa_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pessoa_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($pessoa->Export == "") { ?>
<script type="text/javascript">

// Page object
var pessoa_list = new ew_Page("pessoa_list");
pessoa_list.PageID = "list"; // Page ID
var EW_PAGE_ID = pessoa_list.PageID; // For backward compatibility

// Form object
var fpessoalist = new ew_Form("fpessoalist");
fpessoalist.FormKeyCountName = '<?php echo $pessoa_list->FormKeyCountName ?>';

// Form_CustomValidate event
fpessoalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpessoalist.ValidateRequired = true;
<?php } else { ?>
fpessoalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fpessoalistsrch = new ew_Form("fpessoalistsrch");

// Validate function for search
fpessoalistsrch.Validate = function(fobj) {
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
fpessoalistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpessoalistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fpessoalistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (fpessoalistsrch) fpessoalistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($pessoa->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($pessoa_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $pessoa_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$pessoa_list->TotalRecs = $pessoa->SelectRecordCount();
	} else {
		if ($pessoa_list->Recordset = $pessoa_list->LoadRecordset())
			$pessoa_list->TotalRecs = $pessoa_list->Recordset->RecordCount();
	}
	$pessoa_list->StartRec = 1;
	if ($pessoa_list->DisplayRecs <= 0 || ($pessoa->Export <> "" && $pessoa->ExportAll)) // Display all records
		$pessoa_list->DisplayRecs = $pessoa_list->TotalRecs;
	if (!($pessoa->Export <> "" && $pessoa->ExportAll))
		$pessoa_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$pessoa_list->Recordset = $pessoa_list->LoadRecordset($pessoa_list->StartRec-1, $pessoa_list->DisplayRecs);
$pessoa_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($pessoa->Export == "" && $pessoa->CurrentAction == "") { ?>
<form name="fpessoalistsrch" id="fpessoalistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fpessoalistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fpessoalistsrch_SearchGroup" href="#fpessoalistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fpessoalistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fpessoalistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="pessoa">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$pessoa_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$pessoa->RowType = EW_ROWTYPE_SEARCH;

// Render row
$pessoa->ResetAttrs();
$pessoa_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($pessoa->no_pessoa->Visible) { // no_pessoa ?>
	<span id="xsc_no_pessoa" class="ewCell">
		<span class="ewSearchCaption"><?php echo $pessoa->no_pessoa->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_pessoa" id="z_no_pessoa" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_pessoa" name="x_no_pessoa" id="x_no_pessoa" size="30" maxlength="100" placeholder="<?php echo $pessoa->no_pessoa->PlaceHolder ?>" value="<?php echo $pessoa->no_pessoa->EditValue ?>"<?php echo $pessoa->no_pessoa->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($pessoa->ic_tpEnvolvimento->Visible) { // ic_tpEnvolvimento ?>
	<span id="xsc_ic_tpEnvolvimento" class="ewCell">
		<span class="ewSearchCaption"><?php echo $pessoa->ic_tpEnvolvimento->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_tpEnvolvimento" id="z_ic_tpEnvolvimento" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ic_tpEnvolvimento" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_tpEnvolvimento" id="x_ic_tpEnvolvimento" value="{value}"<?php echo $pessoa->ic_tpEnvolvimento->EditAttributes() ?>></div>
<div id="dsl_x_ic_tpEnvolvimento" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $pessoa->ic_tpEnvolvimento->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pessoa->ic_tpEnvolvimento->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tpEnvolvimento" name="x_ic_tpEnvolvimento" id="x_ic_tpEnvolvimento_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $pessoa->ic_tpEnvolvimento->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
<div id="xsr_3" class="ewRow">
<?php if ($pessoa->ic_ativo->Visible) { // ic_ativo ?>
	<span id="xsc_ic_ativo" class="ewCell">
		<span class="ewSearchCaption"><?php echo $pessoa->ic_ativo->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_ativo" id="z_ic_ativo" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $pessoa->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $pessoa->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pessoa->ic_ativo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $pessoa->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
	<a class="btn ewShowAll" href="<?php echo $pessoa_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ResetSearch") ?></a>
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
<?php $pessoa_list->ShowPageHeader(); ?>
<?php
$pessoa_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fpessoalist" id="fpessoalist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="pessoa">
<div id="gmp_pessoa" class="ewGridMiddlePanel">
<?php if ($pessoa_list->TotalRecs > 0) { ?>
<table id="tbl_pessoalist" class="ewTable ewTableSeparate">
<?php echo $pessoa->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$pessoa_list->RenderListOptions();

// Render list options (header, left)
$pessoa_list->ListOptions->Render("header", "left");
?>
<?php if ($pessoa->nu_pessoa->Visible) { // nu_pessoa ?>
	<?php if ($pessoa->SortUrl($pessoa->nu_pessoa) == "") { ?>
		<td><div id="elh_pessoa_nu_pessoa" class="pessoa_nu_pessoa"><div class="ewTableHeaderCaption"><?php echo $pessoa->nu_pessoa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pessoa->SortUrl($pessoa->nu_pessoa) ?>',2);"><div id="elh_pessoa_nu_pessoa" class="pessoa_nu_pessoa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa->nu_pessoa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa->nu_pessoa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa->nu_pessoa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pessoa->no_pessoa->Visible) { // no_pessoa ?>
	<?php if ($pessoa->SortUrl($pessoa->no_pessoa) == "") { ?>
		<td><div id="elh_pessoa_no_pessoa" class="pessoa_no_pessoa"><div class="ewTableHeaderCaption"><?php echo $pessoa->no_pessoa->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pessoa->SortUrl($pessoa->no_pessoa) ?>',2);"><div id="elh_pessoa_no_pessoa" class="pessoa_no_pessoa">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa->no_pessoa->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa->no_pessoa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa->no_pessoa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pessoa->ic_tpEnvolvimento->Visible) { // ic_tpEnvolvimento ?>
	<?php if ($pessoa->SortUrl($pessoa->ic_tpEnvolvimento) == "") { ?>
		<td><div id="elh_pessoa_ic_tpEnvolvimento" class="pessoa_ic_tpEnvolvimento"><div class="ewTableHeaderCaption"><?php echo $pessoa->ic_tpEnvolvimento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pessoa->SortUrl($pessoa->ic_tpEnvolvimento) ?>',2);"><div id="elh_pessoa_ic_tpEnvolvimento" class="pessoa_ic_tpEnvolvimento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa->ic_tpEnvolvimento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa->ic_tpEnvolvimento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa->ic_tpEnvolvimento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pessoa->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($pessoa->SortUrl($pessoa->ic_ativo) == "") { ?>
		<td><div id="elh_pessoa_ic_ativo" class="pessoa_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $pessoa->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pessoa->SortUrl($pessoa->ic_ativo) ?>',2);"><div id="elh_pessoa_ic_ativo" class="pessoa_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pessoa->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pessoa->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pessoa->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pessoa_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($pessoa->ExportAll && $pessoa->Export <> "") {
	$pessoa_list->StopRec = $pessoa_list->TotalRecs;
} else {

	// Set the last record to display
	if ($pessoa_list->TotalRecs > $pessoa_list->StartRec + $pessoa_list->DisplayRecs - 1)
		$pessoa_list->StopRec = $pessoa_list->StartRec + $pessoa_list->DisplayRecs - 1;
	else
		$pessoa_list->StopRec = $pessoa_list->TotalRecs;
}
$pessoa_list->RecCnt = $pessoa_list->StartRec - 1;
if ($pessoa_list->Recordset && !$pessoa_list->Recordset->EOF) {
	$pessoa_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $pessoa_list->StartRec > 1)
		$pessoa_list->Recordset->Move($pessoa_list->StartRec - 1);
} elseif (!$pessoa->AllowAddDeleteRow && $pessoa_list->StopRec == 0) {
	$pessoa_list->StopRec = $pessoa->GridAddRowCount;
}

// Initialize aggregate
$pessoa->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pessoa->ResetAttrs();
$pessoa_list->RenderRow();
while ($pessoa_list->RecCnt < $pessoa_list->StopRec) {
	$pessoa_list->RecCnt++;
	if (intval($pessoa_list->RecCnt) >= intval($pessoa_list->StartRec)) {
		$pessoa_list->RowCnt++;

		// Set up key count
		$pessoa_list->KeyCount = $pessoa_list->RowIndex;

		// Init row class and style
		$pessoa->ResetAttrs();
		$pessoa->CssClass = "";
		if ($pessoa->CurrentAction == "gridadd") {
		} else {
			$pessoa_list->LoadRowValues($pessoa_list->Recordset); // Load row values
		}
		$pessoa->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$pessoa->RowAttrs = array_merge($pessoa->RowAttrs, array('data-rowindex'=>$pessoa_list->RowCnt, 'id'=>'r' . $pessoa_list->RowCnt . '_pessoa', 'data-rowtype'=>$pessoa->RowType));

		// Render row
		$pessoa_list->RenderRow();

		// Render list options
		$pessoa_list->RenderListOptions();
?>
	<tr<?php echo $pessoa->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pessoa_list->ListOptions->Render("body", "left", $pessoa_list->RowCnt);
?>
	<?php if ($pessoa->nu_pessoa->Visible) { // nu_pessoa ?>
		<td<?php echo $pessoa->nu_pessoa->CellAttributes() ?>>
<span<?php echo $pessoa->nu_pessoa->ViewAttributes() ?>>
<?php echo $pessoa->nu_pessoa->ListViewValue() ?></span>
<a id="<?php echo $pessoa_list->PageObjName . "_row_" . $pessoa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pessoa->no_pessoa->Visible) { // no_pessoa ?>
		<td<?php echo $pessoa->no_pessoa->CellAttributes() ?>>
<span<?php echo $pessoa->no_pessoa->ViewAttributes() ?>>
<?php echo $pessoa->no_pessoa->ListViewValue() ?></span>
<a id="<?php echo $pessoa_list->PageObjName . "_row_" . $pessoa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pessoa->ic_tpEnvolvimento->Visible) { // ic_tpEnvolvimento ?>
		<td<?php echo $pessoa->ic_tpEnvolvimento->CellAttributes() ?>>
<span<?php echo $pessoa->ic_tpEnvolvimento->ViewAttributes() ?>>
<?php echo $pessoa->ic_tpEnvolvimento->ListViewValue() ?></span>
<a id="<?php echo $pessoa_list->PageObjName . "_row_" . $pessoa_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pessoa->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $pessoa->ic_ativo->CellAttributes() ?>>
<span<?php echo $pessoa->ic_ativo->ViewAttributes() ?>>
<?php echo $pessoa->ic_ativo->ListViewValue() ?></span>
<a id="<?php echo $pessoa_list->PageObjName . "_row_" . $pessoa_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$pessoa_list->ListOptions->Render("body", "right", $pessoa_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($pessoa->CurrentAction <> "gridadd")
		$pessoa_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($pessoa->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($pessoa_list->Recordset)
	$pessoa_list->Recordset->Close();
?>
<?php if ($pessoa->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($pessoa->CurrentAction <> "gridadd" && $pessoa->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($pessoa_list->Pager)) $pessoa_list->Pager = new cNumericPager($pessoa_list->StartRec, $pessoa_list->DisplayRecs, $pessoa_list->TotalRecs, $pessoa_list->RecRange) ?>
<?php if ($pessoa_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($pessoa_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $pessoa_list->PageUrl() ?>start=<?php echo $pessoa_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($pessoa_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $pessoa_list->PageUrl() ?>start=<?php echo $pessoa_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($pessoa_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $pessoa_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($pessoa_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $pessoa_list->PageUrl() ?>start=<?php echo $pessoa_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($pessoa_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $pessoa_list->PageUrl() ?>start=<?php echo $pessoa_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($pessoa_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pessoa_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pessoa_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pessoa_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($pessoa_list->SearchWhere == "0=101") { ?>
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
	foreach ($pessoa_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($pessoa->Export == "") { ?>
<script type="text/javascript">
fpessoalistsrch.Init();
fpessoalist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$pessoa_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pessoa->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$pessoa_list->Page_Terminate();
?>
