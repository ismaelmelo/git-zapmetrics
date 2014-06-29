<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "projetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "projeto_centrocustogridcls.php" ?>
<?php include_once "riscoprojetogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$projeto_list = NULL; // Initialize page object first

class cprojeto_list extends cprojeto {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'projeto';

	// Page object name
	var $PageObjName = 'projeto_list';

	// Grid form hidden field names
	var $FormName = 'fprojetolist';
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

		// Table object (projeto)
		if (!isset($GLOBALS["projeto"])) {
			$GLOBALS["projeto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["projeto"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "projetoadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "projetodelete.php";
		$this->MultiUpdateUrl = "projetoupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'projeto', TRUE);

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
			$this->nu_projeto->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_projeto->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_projeto, FALSE); // nu_projeto
		$this->BuildSearchSql($sWhere, $this->nu_contrato, FALSE); // nu_contrato
		$this->BuildSearchSql($sWhere, $this->nu_itemContrato, FALSE); // nu_itemContrato
		$this->BuildSearchSql($sWhere, $this->nu_prospecto, FALSE); // nu_prospecto
		$this->BuildSearchSql($sWhere, $this->nu_tpProjeto, FALSE); // nu_tpProjeto
		$this->BuildSearchSql($sWhere, $this->nu_projetoInteg, FALSE); // nu_projetoInteg
		$this->BuildSearchSql($sWhere, $this->no_projeto, FALSE); // no_projeto
		$this->BuildSearchSql($sWhere, $this->id_tarefaTpProj, FALSE); // id_tarefaTpProj
		$this->BuildSearchSql($sWhere, $this->ic_complexProjeto, FALSE); // ic_complexProjeto
		$this->BuildSearchSql($sWhere, $this->ic_passivelContPf, FALSE); // ic_passivelContPf

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_projeto->AdvancedSearch->Save(); // nu_projeto
			$this->nu_contrato->AdvancedSearch->Save(); // nu_contrato
			$this->nu_itemContrato->AdvancedSearch->Save(); // nu_itemContrato
			$this->nu_prospecto->AdvancedSearch->Save(); // nu_prospecto
			$this->nu_tpProjeto->AdvancedSearch->Save(); // nu_tpProjeto
			$this->nu_projetoInteg->AdvancedSearch->Save(); // nu_projetoInteg
			$this->no_projeto->AdvancedSearch->Save(); // no_projeto
			$this->id_tarefaTpProj->AdvancedSearch->Save(); // id_tarefaTpProj
			$this->ic_complexProjeto->AdvancedSearch->Save(); // ic_complexProjeto
			$this->ic_passivelContPf->AdvancedSearch->Save(); // ic_passivelContPf
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
		if ($this->nu_projeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_contrato->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_itemContrato->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_prospecto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_tpProjeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_projetoInteg->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_projeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->id_tarefaTpProj->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_complexProjeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_passivelContPf->AdvancedSearch->IssetSession())
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
		$this->nu_projeto->AdvancedSearch->UnsetSession();
		$this->nu_contrato->AdvancedSearch->UnsetSession();
		$this->nu_itemContrato->AdvancedSearch->UnsetSession();
		$this->nu_prospecto->AdvancedSearch->UnsetSession();
		$this->nu_tpProjeto->AdvancedSearch->UnsetSession();
		$this->nu_projetoInteg->AdvancedSearch->UnsetSession();
		$this->no_projeto->AdvancedSearch->UnsetSession();
		$this->id_tarefaTpProj->AdvancedSearch->UnsetSession();
		$this->ic_complexProjeto->AdvancedSearch->UnsetSession();
		$this->ic_passivelContPf->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_projeto->AdvancedSearch->Load();
		$this->nu_contrato->AdvancedSearch->Load();
		$this->nu_itemContrato->AdvancedSearch->Load();
		$this->nu_prospecto->AdvancedSearch->Load();
		$this->nu_tpProjeto->AdvancedSearch->Load();
		$this->nu_projetoInteg->AdvancedSearch->Load();
		$this->no_projeto->AdvancedSearch->Load();
		$this->id_tarefaTpProj->AdvancedSearch->Load();
		$this->ic_complexProjeto->AdvancedSearch->Load();
		$this->ic_passivelContPf->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_tpProjeto, $bCtrl); // nu_tpProjeto
			$this->UpdateSort($this->no_projeto, $bCtrl); // no_projeto
			$this->UpdateSort($this->ic_passivelContPf, $bCtrl); // ic_passivelContPf
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
				$this->nu_tpProjeto->setSort("");
				$this->no_projeto->setSort("");
				$this->ic_passivelContPf->setSort("");
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

		// "detail_projeto_centrocusto"
		$item = &$this->ListOptions->Add("detail_projeto_centrocusto");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'projeto_centrocusto') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["projeto_centrocusto_grid"])) $GLOBALS["projeto_centrocusto_grid"] = new cprojeto_centrocusto_grid;

		// "detail_riscoprojeto"
		$item = &$this->ListOptions->Add("detail_riscoprojeto");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'riscoprojeto') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["riscoprojeto_grid"])) $GLOBALS["riscoprojeto_grid"] = new criscoprojeto_grid;

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

		// "detail_projeto_centrocusto"
		$oListOpt = &$this->ListOptions->Items["detail_projeto_centrocusto"];
		if ($Security->AllowList(CurrentProjectID() . 'projeto_centrocusto')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("projeto_centrocusto", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("projeto_centrocustolist.php?" . EW_TABLE_SHOW_MASTER . "=projeto&nu_projeto=" . strval($this->nu_projeto->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["projeto_centrocusto_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'projeto_centrocusto')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=projeto_centrocusto")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "projeto_centrocusto";
			}
			if ($GLOBALS["projeto_centrocusto_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'projeto_centrocusto')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=projeto_centrocusto")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "projeto_centrocusto";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_riscoprojeto"
		$oListOpt = &$this->ListOptions->Items["detail_riscoprojeto"];
		if ($Security->AllowList(CurrentProjectID() . 'riscoprojeto')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("riscoprojeto", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("riscoprojetolist.php?" . EW_TABLE_SHOW_MASTER . "=projeto&nu_projeto=" . strval($this->nu_projeto->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["riscoprojeto_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'riscoprojeto')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=riscoprojeto")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "riscoprojeto";
			}
			if ($GLOBALS["riscoprojeto_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'riscoprojeto')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=riscoprojeto")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "riscoprojeto";
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
		$item = &$option->Add("detailadd_projeto_centrocusto");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=projeto_centrocusto") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["projeto_centrocusto"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["projeto_centrocusto"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'projeto_centrocusto') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "projeto_centrocusto";
		}
		$item = &$option->Add("detailadd_riscoprojeto");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=riscoprojeto") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["riscoprojeto"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["riscoprojeto"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'riscoprojeto') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "riscoprojeto";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fprojetolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_projeto

		$this->nu_projeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_projeto"]);
		if ($this->nu_projeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_projeto->AdvancedSearch->SearchOperator = @$_GET["z_nu_projeto"];

		// nu_contrato
		$this->nu_contrato->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_contrato"]);
		if ($this->nu_contrato->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_contrato->AdvancedSearch->SearchOperator = @$_GET["z_nu_contrato"];

		// nu_itemContrato
		$this->nu_itemContrato->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_itemContrato"]);
		if ($this->nu_itemContrato->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_itemContrato->AdvancedSearch->SearchOperator = @$_GET["z_nu_itemContrato"];

		// nu_prospecto
		$this->nu_prospecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_prospecto"]);
		if ($this->nu_prospecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_prospecto->AdvancedSearch->SearchOperator = @$_GET["z_nu_prospecto"];

		// nu_tpProjeto
		$this->nu_tpProjeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_tpProjeto"]);
		if ($this->nu_tpProjeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_tpProjeto->AdvancedSearch->SearchOperator = @$_GET["z_nu_tpProjeto"];

		// nu_projetoInteg
		$this->nu_projetoInteg->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_projetoInteg"]);
		if ($this->nu_projetoInteg->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_projetoInteg->AdvancedSearch->SearchOperator = @$_GET["z_nu_projetoInteg"];

		// no_projeto
		$this->no_projeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_projeto"]);
		if ($this->no_projeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_projeto->AdvancedSearch->SearchOperator = @$_GET["z_no_projeto"];

		// id_tarefaTpProj
		$this->id_tarefaTpProj->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id_tarefaTpProj"]);
		if ($this->id_tarefaTpProj->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id_tarefaTpProj->AdvancedSearch->SearchOperator = @$_GET["z_id_tarefaTpProj"];

		// ic_complexProjeto
		$this->ic_complexProjeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_complexProjeto"]);
		if ($this->ic_complexProjeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_complexProjeto->AdvancedSearch->SearchOperator = @$_GET["z_ic_complexProjeto"];

		// ic_passivelContPf
		$this->ic_passivelContPf->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_passivelContPf"]);
		if ($this->ic_passivelContPf->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_passivelContPf->AdvancedSearch->SearchOperator = @$_GET["z_ic_passivelContPf"];
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
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		if (array_key_exists('EV__nu_contrato', $rs->fields)) {
			$this->nu_contrato->VirtualValue = $rs->fields('EV__nu_contrato'); // Set up virtual field value
		} else {
			$this->nu_contrato->VirtualValue = ""; // Clear value
		}
		$this->nu_itemContrato->setDbValue($rs->fields('nu_itemContrato'));
		if (array_key_exists('EV__nu_itemContrato', $rs->fields)) {
			$this->nu_itemContrato->VirtualValue = $rs->fields('EV__nu_itemContrato'); // Set up virtual field value
		} else {
			$this->nu_itemContrato->VirtualValue = ""; // Clear value
		}
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->nu_tpProjeto->setDbValue($rs->fields('nu_tpProjeto'));
		$this->nu_projetoInteg->setDbValue($rs->fields('nu_projetoInteg'));
		$this->no_projeto->setDbValue($rs->fields('no_projeto'));
		$this->id_tarefaTpProj->setDbValue($rs->fields('id_tarefaTpProj'));
		$this->ic_complexProjeto->setDbValue($rs->fields('ic_complexProjeto'));
		$this->ic_passivelContPf->setDbValue($rs->fields('ic_passivelContPf'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->nu_itemContrato->DbValue = $row['nu_itemContrato'];
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->nu_tpProjeto->DbValue = $row['nu_tpProjeto'];
		$this->nu_projetoInteg->DbValue = $row['nu_projetoInteg'];
		$this->no_projeto->DbValue = $row['no_projeto'];
		$this->id_tarefaTpProj->DbValue = $row['id_tarefaTpProj'];
		$this->ic_complexProjeto->DbValue = $row['ic_complexProjeto'];
		$this->ic_passivelContPf->DbValue = $row['ic_passivelContPf'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_projeto")) <> "")
			$this->nu_projeto->CurrentValue = $this->getKey("nu_projeto"); // nu_projeto
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
		// nu_projeto
		// nu_contrato
		// nu_itemContrato
		// nu_prospecto
		// nu_tpProjeto
		// nu_projetoInteg
		// no_projeto
		// id_tarefaTpProj
		// ic_complexProjeto
		// ic_passivelContPf

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projeto
			$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_contrato
			if ($this->nu_contrato->VirtualValue <> "") {
				$this->nu_contrato->ViewValue = $this->nu_contrato->VirtualValue;
			} else {
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
			$sSqlWrk .= " ORDER BY [no_contrato] ASC";
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
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemContrato
			if ($this->nu_itemContrato->VirtualValue <> "") {
				$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->VirtualValue;
			} else {
			if (strval($this->nu_itemContrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemContrato->ViewValue = $rswrk->fields('DispFld');
					$this->nu_itemContrato->ViewValue .= ew_ValueSeparator(1,$this->nu_itemContrato) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->CurrentValue;
				}
			} else {
				$this->nu_itemContrato->ViewValue = NULL;
			}
			}
			$this->nu_itemContrato->ViewCustomAttributes = "";

			// nu_prospecto
			if (strval($this->nu_prospecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_prospecto]" . ew_SearchString("=", $this->nu_prospecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_prospecto] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_prospecto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
				}
			} else {
				$this->nu_prospecto->ViewValue = NULL;
			}
			$this->nu_prospecto->ViewCustomAttributes = "";

			// nu_tpProjeto
			if (strval($this->nu_tpProjeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpProjeto]" . ew_SearchString("=", $this->nu_tpProjeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpProjeto], [no_tpProjeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpprojeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND ([ic_tpProjDem]='P' OR [ic_tpProjDem]='D')";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpProjeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpProjeto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpProjeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpProjeto->ViewValue = $this->nu_tpProjeto->CurrentValue;
				}
			} else {
				$this->nu_tpProjeto->ViewValue = NULL;
			}
			$this->nu_tpProjeto->ViewCustomAttributes = "";

			// nu_projetoInteg
			if (strval($this->nu_projetoInteg->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_projetoInteg->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_projects]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projetoInteg, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [created_on] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projetoInteg->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projetoInteg->ViewValue = $this->nu_projetoInteg->CurrentValue;
				}
			} else {
				$this->nu_projetoInteg->ViewValue = NULL;
			}
			$this->nu_projetoInteg->ViewCustomAttributes = "";

			// no_projeto
			$this->no_projeto->ViewValue = $this->no_projeto->CurrentValue;
			$this->no_projeto->ViewCustomAttributes = "";

			// id_tarefaTpProj
			$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
			if (strval($this->id_tarefaTpProj->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_tarefaTpProj->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [subject] AS [DispFld], [id] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_issues]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tarefaTpProj, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [id] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tarefaTpProj->ViewValue = $rswrk->fields('DispFld');
					$this->id_tarefaTpProj->ViewValue .= ew_ValueSeparator(1,$this->id_tarefaTpProj) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
				}
			} else {
				$this->id_tarefaTpProj->ViewValue = NULL;
			}
			$this->id_tarefaTpProj->ViewCustomAttributes = "";

			// ic_complexProjeto
			if (strval($this->ic_complexProjeto->CurrentValue) <> "") {
				switch ($this->ic_complexProjeto->CurrentValue) {
					case $this->ic_complexProjeto->FldTagValue(1):
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(1) <> "" ? $this->ic_complexProjeto->FldTagCaption(1) : $this->ic_complexProjeto->CurrentValue;
						break;
					case $this->ic_complexProjeto->FldTagValue(2):
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(2) <> "" ? $this->ic_complexProjeto->FldTagCaption(2) : $this->ic_complexProjeto->CurrentValue;
						break;
					case $this->ic_complexProjeto->FldTagValue(3):
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(3) <> "" ? $this->ic_complexProjeto->FldTagCaption(3) : $this->ic_complexProjeto->CurrentValue;
						break;
					default:
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->CurrentValue;
				}
			} else {
				$this->ic_complexProjeto->ViewValue = NULL;
			}
			$this->ic_complexProjeto->ViewCustomAttributes = "";

			// ic_passivelContPf
			if (strval($this->ic_passivelContPf->CurrentValue) <> "") {
				switch ($this->ic_passivelContPf->CurrentValue) {
					case $this->ic_passivelContPf->FldTagValue(1):
						$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->FldTagCaption(1) <> "" ? $this->ic_passivelContPf->FldTagCaption(1) : $this->ic_passivelContPf->CurrentValue;
						break;
					case $this->ic_passivelContPf->FldTagValue(2):
						$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->FldTagCaption(2) <> "" ? $this->ic_passivelContPf->FldTagCaption(2) : $this->ic_passivelContPf->CurrentValue;
						break;
					default:
						$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->CurrentValue;
				}
			} else {
				$this->ic_passivelContPf->ViewValue = NULL;
			}
			$this->ic_passivelContPf->ViewCustomAttributes = "";

			// nu_tpProjeto
			$this->nu_tpProjeto->LinkCustomAttributes = "";
			$this->nu_tpProjeto->HrefValue = "";
			$this->nu_tpProjeto->TooltipValue = "";

			// no_projeto
			$this->no_projeto->LinkCustomAttributes = "";
			$this->no_projeto->HrefValue = "";
			$this->no_projeto->TooltipValue = "";

			// ic_passivelContPf
			$this->ic_passivelContPf->LinkCustomAttributes = "";
			$this->ic_passivelContPf->HrefValue = "";
			$this->ic_passivelContPf->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_tpProjeto
			$this->nu_tpProjeto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpProjeto], [no_tpProjeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpprojeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND ([ic_tpProjDem]='P' OR [ic_tpProjDem]='D')";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpProjeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpProjeto] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpProjeto->EditValue = $arwrk;

			// no_projeto
			$this->no_projeto->EditCustomAttributes = "";
			$this->no_projeto->EditValue = ew_HtmlEncode($this->no_projeto->AdvancedSearch->SearchValue);
			$this->no_projeto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_projeto->FldCaption()));

			// ic_passivelContPf
			$this->ic_passivelContPf->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_passivelContPf->FldTagValue(1), $this->ic_passivelContPf->FldTagCaption(1) <> "" ? $this->ic_passivelContPf->FldTagCaption(1) : $this->ic_passivelContPf->FldTagValue(1));
			$arwrk[] = array($this->ic_passivelContPf->FldTagValue(2), $this->ic_passivelContPf->FldTagCaption(2) <> "" ? $this->ic_passivelContPf->FldTagCaption(2) : $this->ic_passivelContPf->FldTagValue(2));
			$this->ic_passivelContPf->EditValue = $arwrk;
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
		$this->nu_contrato->AdvancedSearch->Load();
		$this->nu_itemContrato->AdvancedSearch->Load();
		$this->nu_prospecto->AdvancedSearch->Load();
		$this->nu_tpProjeto->AdvancedSearch->Load();
		$this->nu_projetoInteg->AdvancedSearch->Load();
		$this->no_projeto->AdvancedSearch->Load();
		$this->id_tarefaTpProj->AdvancedSearch->Load();
		$this->ic_complexProjeto->AdvancedSearch->Load();
		$this->ic_passivelContPf->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_projeto\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_projeto',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fprojetolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_projeto); // nu_projeto
		$this->AddSearchQueryString($sQry, $this->nu_contrato); // nu_contrato
		$this->AddSearchQueryString($sQry, $this->nu_itemContrato); // nu_itemContrato
		$this->AddSearchQueryString($sQry, $this->nu_prospecto); // nu_prospecto
		$this->AddSearchQueryString($sQry, $this->nu_tpProjeto); // nu_tpProjeto
		$this->AddSearchQueryString($sQry, $this->nu_projetoInteg); // nu_projetoInteg
		$this->AddSearchQueryString($sQry, $this->no_projeto); // no_projeto
		$this->AddSearchQueryString($sQry, $this->id_tarefaTpProj); // id_tarefaTpProj
		$this->AddSearchQueryString($sQry, $this->ic_complexProjeto); // ic_complexProjeto
		$this->AddSearchQueryString($sQry, $this->ic_passivelContPf); // ic_passivelContPf

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
		$table = 'projeto';
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
if (!isset($projeto_list)) $projeto_list = new cprojeto_list();

// Page init
$projeto_list->Page_Init();

// Page main
$projeto_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$projeto_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($projeto->Export == "") { ?>
<script type="text/javascript">

// Page object
var projeto_list = new ew_Page("projeto_list");
projeto_list.PageID = "list"; // Page ID
var EW_PAGE_ID = projeto_list.PageID; // For backward compatibility

// Form object
var fprojetolist = new ew_Form("fprojetolist");
fprojetolist.FormKeyCountName = '<?php echo $projeto_list->FormKeyCountName ?>';

// Form_CustomValidate event
fprojetolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojetolist.ValidateRequired = true;
<?php } else { ?>
fprojetolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprojetolist.Lists["x_nu_tpProjeto"] = {"LinkField":"x_nu_tpProjeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpProjeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fprojetolistsrch = new ew_Form("fprojetolistsrch");

// Validate function for search
fprojetolistsrch.Validate = function(fobj) {
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
fprojetolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojetolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fprojetolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fprojetolistsrch.Lists["x_nu_tpProjeto"] = {"LinkField":"x_nu_tpProjeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpProjeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fprojetolistsrch) fprojetolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($projeto->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($projeto_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $projeto_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$projeto_list->TotalRecs = $projeto->SelectRecordCount();
	} else {
		if ($projeto_list->Recordset = $projeto_list->LoadRecordset())
			$projeto_list->TotalRecs = $projeto_list->Recordset->RecordCount();
	}
	$projeto_list->StartRec = 1;
	if ($projeto_list->DisplayRecs <= 0 || ($projeto->Export <> "" && $projeto->ExportAll)) // Display all records
		$projeto_list->DisplayRecs = $projeto_list->TotalRecs;
	if (!($projeto->Export <> "" && $projeto->ExportAll))
		$projeto_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$projeto_list->Recordset = $projeto_list->LoadRecordset($projeto_list->StartRec-1, $projeto_list->DisplayRecs);
$projeto_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($projeto->Export == "" && $projeto->CurrentAction == "") { ?>
<form name="fprojetolistsrch" id="fprojetolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fprojetolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fprojetolistsrch_SearchGroup" href="#fprojetolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fprojetolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fprojetolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="projeto">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$projeto_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$projeto->RowType = EW_ROWTYPE_SEARCH;

// Render row
$projeto->ResetAttrs();
$projeto_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($projeto->nu_tpProjeto->Visible) { // nu_tpProjeto ?>
	<span id="xsc_nu_tpProjeto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $projeto->nu_tpProjeto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_tpProjeto" id="z_nu_tpProjeto" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_tpProjeto" id="x_nu_tpProjeto" name="x_nu_tpProjeto"<?php echo $projeto->nu_tpProjeto->EditAttributes() ?>>
<?php
if (is_array($projeto->nu_tpProjeto->EditValue)) {
	$arwrk = $projeto->nu_tpProjeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto->nu_tpProjeto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprojetolistsrch.Lists["x_nu_tpProjeto"].Options = <?php echo (is_array($projeto->nu_tpProjeto->EditValue)) ? ew_ArrayToJson($projeto->nu_tpProjeto->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($projeto->no_projeto->Visible) { // no_projeto ?>
	<span id="xsc_no_projeto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $projeto->no_projeto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_projeto" id="z_no_projeto" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_projeto" name="x_no_projeto" id="x_no_projeto" size="120" maxlength="120" placeholder="<?php echo $projeto->no_projeto->PlaceHolder ?>" value="<?php echo $projeto->no_projeto->EditValue ?>"<?php echo $projeto->no_projeto->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($projeto->ic_passivelContPf->Visible) { // ic_passivelContPf ?>
	<span id="xsc_ic_passivelContPf" class="ewCell">
		<span class="ewSearchCaption"><?php echo $projeto->ic_passivelContPf->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_passivelContPf" id="z_ic_passivelContPf" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ic_passivelContPf" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_passivelContPf" id="x_ic_passivelContPf" value="{value}"<?php echo $projeto->ic_passivelContPf->EditAttributes() ?>></div>
<div id="dsl_x_ic_passivelContPf" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $projeto->ic_passivelContPf->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($projeto->ic_passivelContPf->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_passivelContPf" name="x_ic_passivelContPf" id="x_ic_passivelContPf_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $projeto->ic_passivelContPf->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
	<a class="btn ewShowAll" href="<?php echo $projeto_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $projeto_list->ShowPageHeader(); ?>
<?php
$projeto_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fprojetolist" id="fprojetolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="projeto">
<div id="gmp_projeto" class="ewGridMiddlePanel">
<?php if ($projeto_list->TotalRecs > 0) { ?>
<table id="tbl_projetolist" class="ewTable ewTableSeparate">
<?php echo $projeto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$projeto_list->RenderListOptions();

// Render list options (header, left)
$projeto_list->ListOptions->Render("header", "left");
?>
<?php if ($projeto->nu_tpProjeto->Visible) { // nu_tpProjeto ?>
	<?php if ($projeto->SortUrl($projeto->nu_tpProjeto) == "") { ?>
		<td><div id="elh_projeto_nu_tpProjeto" class="projeto_nu_tpProjeto"><div class="ewTableHeaderCaption"><?php echo $projeto->nu_tpProjeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $projeto->SortUrl($projeto->nu_tpProjeto) ?>',2);"><div id="elh_projeto_nu_tpProjeto" class="projeto_nu_tpProjeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $projeto->nu_tpProjeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($projeto->nu_tpProjeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($projeto->nu_tpProjeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($projeto->no_projeto->Visible) { // no_projeto ?>
	<?php if ($projeto->SortUrl($projeto->no_projeto) == "") { ?>
		<td><div id="elh_projeto_no_projeto" class="projeto_no_projeto"><div class="ewTableHeaderCaption"><?php echo $projeto->no_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $projeto->SortUrl($projeto->no_projeto) ?>',2);"><div id="elh_projeto_no_projeto" class="projeto_no_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $projeto->no_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($projeto->no_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($projeto->no_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($projeto->ic_passivelContPf->Visible) { // ic_passivelContPf ?>
	<?php if ($projeto->SortUrl($projeto->ic_passivelContPf) == "") { ?>
		<td><div id="elh_projeto_ic_passivelContPf" class="projeto_ic_passivelContPf"><div class="ewTableHeaderCaption"><?php echo $projeto->ic_passivelContPf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $projeto->SortUrl($projeto->ic_passivelContPf) ?>',2);"><div id="elh_projeto_ic_passivelContPf" class="projeto_ic_passivelContPf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $projeto->ic_passivelContPf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($projeto->ic_passivelContPf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($projeto->ic_passivelContPf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$projeto_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($projeto->ExportAll && $projeto->Export <> "") {
	$projeto_list->StopRec = $projeto_list->TotalRecs;
} else {

	// Set the last record to display
	if ($projeto_list->TotalRecs > $projeto_list->StartRec + $projeto_list->DisplayRecs - 1)
		$projeto_list->StopRec = $projeto_list->StartRec + $projeto_list->DisplayRecs - 1;
	else
		$projeto_list->StopRec = $projeto_list->TotalRecs;
}
$projeto_list->RecCnt = $projeto_list->StartRec - 1;
if ($projeto_list->Recordset && !$projeto_list->Recordset->EOF) {
	$projeto_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $projeto_list->StartRec > 1)
		$projeto_list->Recordset->Move($projeto_list->StartRec - 1);
} elseif (!$projeto->AllowAddDeleteRow && $projeto_list->StopRec == 0) {
	$projeto_list->StopRec = $projeto->GridAddRowCount;
}

// Initialize aggregate
$projeto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$projeto->ResetAttrs();
$projeto_list->RenderRow();
while ($projeto_list->RecCnt < $projeto_list->StopRec) {
	$projeto_list->RecCnt++;
	if (intval($projeto_list->RecCnt) >= intval($projeto_list->StartRec)) {
		$projeto_list->RowCnt++;

		// Set up key count
		$projeto_list->KeyCount = $projeto_list->RowIndex;

		// Init row class and style
		$projeto->ResetAttrs();
		$projeto->CssClass = "";
		if ($projeto->CurrentAction == "gridadd") {
		} else {
			$projeto_list->LoadRowValues($projeto_list->Recordset); // Load row values
		}
		$projeto->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$projeto->RowAttrs = array_merge($projeto->RowAttrs, array('data-rowindex'=>$projeto_list->RowCnt, 'id'=>'r' . $projeto_list->RowCnt . '_projeto', 'data-rowtype'=>$projeto->RowType));

		// Render row
		$projeto_list->RenderRow();

		// Render list options
		$projeto_list->RenderListOptions();
?>
	<tr<?php echo $projeto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$projeto_list->ListOptions->Render("body", "left", $projeto_list->RowCnt);
?>
	<?php if ($projeto->nu_tpProjeto->Visible) { // nu_tpProjeto ?>
		<td<?php echo $projeto->nu_tpProjeto->CellAttributes() ?>>
<span<?php echo $projeto->nu_tpProjeto->ViewAttributes() ?>>
<?php echo $projeto->nu_tpProjeto->ListViewValue() ?></span>
<a id="<?php echo $projeto_list->PageObjName . "_row_" . $projeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($projeto->no_projeto->Visible) { // no_projeto ?>
		<td<?php echo $projeto->no_projeto->CellAttributes() ?>>
<span<?php echo $projeto->no_projeto->ViewAttributes() ?>>
<?php echo $projeto->no_projeto->ListViewValue() ?></span>
<a id="<?php echo $projeto_list->PageObjName . "_row_" . $projeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($projeto->ic_passivelContPf->Visible) { // ic_passivelContPf ?>
		<td<?php echo $projeto->ic_passivelContPf->CellAttributes() ?>>
<span<?php echo $projeto->ic_passivelContPf->ViewAttributes() ?>>
<?php echo $projeto->ic_passivelContPf->ListViewValue() ?></span>
<a id="<?php echo $projeto_list->PageObjName . "_row_" . $projeto_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$projeto_list->ListOptions->Render("body", "right", $projeto_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($projeto->CurrentAction <> "gridadd")
		$projeto_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($projeto->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($projeto_list->Recordset)
	$projeto_list->Recordset->Close();
?>
<?php if ($projeto->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($projeto->CurrentAction <> "gridadd" && $projeto->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($projeto_list->Pager)) $projeto_list->Pager = new cNumericPager($projeto_list->StartRec, $projeto_list->DisplayRecs, $projeto_list->TotalRecs, $projeto_list->RecRange) ?>
<?php if ($projeto_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($projeto_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $projeto_list->PageUrl() ?>start=<?php echo $projeto_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($projeto_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $projeto_list->PageUrl() ?>start=<?php echo $projeto_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($projeto_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $projeto_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($projeto_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $projeto_list->PageUrl() ?>start=<?php echo $projeto_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($projeto_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $projeto_list->PageUrl() ?>start=<?php echo $projeto_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($projeto_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $projeto_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $projeto_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $projeto_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($projeto_list->SearchWhere == "0=101") { ?>
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
	foreach ($projeto_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($projeto->Export == "") { ?>
<script type="text/javascript">
fprojetolistsrch.Init();
fprojetolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$projeto_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($projeto->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$projeto_list->Page_Terminate();
?>
