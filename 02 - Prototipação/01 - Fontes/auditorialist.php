<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "auditoriainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$auditoria_list = NULL; // Initialize page object first

class cauditoria_list extends cauditoria {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'auditoria';

	// Page object name
	var $PageObjName = 'auditoria_list';

	// Grid form hidden field names
	var $FormName = 'fauditorialist';
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

		// Table object (auditoria)
		if (!isset($GLOBALS["auditoria"])) {
			$GLOBALS["auditoria"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["auditoria"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "auditoriaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "auditoriadelete.php";
		$this->MultiUpdateUrl = "auditoriaupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'auditoria', TRUE);

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
		$this->nu_identificador->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->nu_identificador->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_identificador->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_identificador, FALSE); // nu_identificador
		$this->BuildSearchSql($sWhere, $this->dt_data, FALSE); // dt_data
		$this->BuildSearchSql($sWhere, $this->ds_dominioArquivo, FALSE); // ds_dominioArquivo
		$this->BuildSearchSql($sWhere, $this->no_perfil, FALSE); // no_perfil
		$this->BuildSearchSql($sWhere, $this->ic_acao, FALSE); // ic_acao
		$this->BuildSearchSql($sWhere, $this->no_tabela, FALSE); // no_tabela
		$this->BuildSearchSql($sWhere, $this->no_campo, FALSE); // no_campo
		$this->BuildSearchSql($sWhere, $this->nu_chaveCampo, FALSE); // nu_chaveCampo
		$this->BuildSearchSql($sWhere, $this->im_antes, FALSE); // im_antes
		$this->BuildSearchSql($sWhere, $this->im_depois, FALSE); // im_depois

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_identificador->AdvancedSearch->Save(); // nu_identificador
			$this->dt_data->AdvancedSearch->Save(); // dt_data
			$this->ds_dominioArquivo->AdvancedSearch->Save(); // ds_dominioArquivo
			$this->no_perfil->AdvancedSearch->Save(); // no_perfil
			$this->ic_acao->AdvancedSearch->Save(); // ic_acao
			$this->no_tabela->AdvancedSearch->Save(); // no_tabela
			$this->no_campo->AdvancedSearch->Save(); // no_campo
			$this->nu_chaveCampo->AdvancedSearch->Save(); // nu_chaveCampo
			$this->im_antes->AdvancedSearch->Save(); // im_antes
			$this->im_depois->AdvancedSearch->Save(); // im_depois
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
		if ($this->nu_identificador->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_data->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_dominioArquivo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_perfil->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_acao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_tabela->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_campo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_chaveCampo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_antes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_depois->AdvancedSearch->IssetSession())
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
		$this->nu_identificador->AdvancedSearch->UnsetSession();
		$this->dt_data->AdvancedSearch->UnsetSession();
		$this->ds_dominioArquivo->AdvancedSearch->UnsetSession();
		$this->no_perfil->AdvancedSearch->UnsetSession();
		$this->ic_acao->AdvancedSearch->UnsetSession();
		$this->no_tabela->AdvancedSearch->UnsetSession();
		$this->no_campo->AdvancedSearch->UnsetSession();
		$this->nu_chaveCampo->AdvancedSearch->UnsetSession();
		$this->im_antes->AdvancedSearch->UnsetSession();
		$this->im_depois->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_identificador->AdvancedSearch->Load();
		$this->dt_data->AdvancedSearch->Load();
		$this->ds_dominioArquivo->AdvancedSearch->Load();
		$this->no_perfil->AdvancedSearch->Load();
		$this->ic_acao->AdvancedSearch->Load();
		$this->no_tabela->AdvancedSearch->Load();
		$this->no_campo->AdvancedSearch->Load();
		$this->nu_chaveCampo->AdvancedSearch->Load();
		$this->im_antes->AdvancedSearch->Load();
		$this->im_depois->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_identificador, $bCtrl); // nu_identificador
			$this->UpdateSort($this->dt_data, $bCtrl); // dt_data
			$this->UpdateSort($this->ds_dominioArquivo, $bCtrl); // ds_dominioArquivo
			$this->UpdateSort($this->no_perfil, $bCtrl); // no_perfil
			$this->UpdateSort($this->ic_acao, $bCtrl); // ic_acao
			$this->UpdateSort($this->no_tabela, $bCtrl); // no_tabela
			$this->UpdateSort($this->no_campo, $bCtrl); // no_campo
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
				$this->nu_identificador->setSort("ASC");
				$this->dt_data->setSort("ASC");
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
				$this->nu_identificador->setSort("");
				$this->dt_data->setSort("");
				$this->ds_dominioArquivo->setSort("");
				$this->no_perfil->setSort("");
				$this->ic_acao->setSort("");
				$this->no_tabela->setSort("");
				$this->no_campo->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fauditorialist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_identificador

		$this->nu_identificador->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_identificador"]);
		if ($this->nu_identificador->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_identificador->AdvancedSearch->SearchOperator = @$_GET["z_nu_identificador"];

		// dt_data
		$this->dt_data->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_data"]);
		if ($this->dt_data->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_data->AdvancedSearch->SearchOperator = @$_GET["z_dt_data"];

		// ds_dominioArquivo
		$this->ds_dominioArquivo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_dominioArquivo"]);
		if ($this->ds_dominioArquivo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_dominioArquivo->AdvancedSearch->SearchOperator = @$_GET["z_ds_dominioArquivo"];

		// no_perfil
		$this->no_perfil->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_perfil"]);
		if ($this->no_perfil->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_perfil->AdvancedSearch->SearchOperator = @$_GET["z_no_perfil"];

		// ic_acao
		$this->ic_acao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_acao"]);
		if ($this->ic_acao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_acao->AdvancedSearch->SearchOperator = @$_GET["z_ic_acao"];

		// no_tabela
		$this->no_tabela->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_tabela"]);
		if ($this->no_tabela->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_tabela->AdvancedSearch->SearchOperator = @$_GET["z_no_tabela"];

		// no_campo
		$this->no_campo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_campo"]);
		if ($this->no_campo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_campo->AdvancedSearch->SearchOperator = @$_GET["z_no_campo"];

		// nu_chaveCampo
		$this->nu_chaveCampo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_chaveCampo"]);
		if ($this->nu_chaveCampo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_chaveCampo->AdvancedSearch->SearchOperator = @$_GET["z_nu_chaveCampo"];

		// im_antes
		$this->im_antes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_antes"]);
		if ($this->im_antes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_antes->AdvancedSearch->SearchOperator = @$_GET["z_im_antes"];

		// im_depois
		$this->im_depois->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_depois"]);
		if ($this->im_depois->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_depois->AdvancedSearch->SearchOperator = @$_GET["z_im_depois"];
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
		$this->nu_identificador->setDbValue($rs->fields('nu_identificador'));
		$this->dt_data->setDbValue($rs->fields('dt_data'));
		$this->ds_dominioArquivo->setDbValue($rs->fields('ds_dominioArquivo'));
		$this->no_perfil->setDbValue($rs->fields('no_perfil'));
		$this->ic_acao->setDbValue($rs->fields('ic_acao'));
		$this->no_tabela->setDbValue($rs->fields('no_tabela'));
		$this->no_campo->setDbValue($rs->fields('no_campo'));
		$this->nu_chaveCampo->setDbValue($rs->fields('nu_chaveCampo'));
		$this->im_antes->setDbValue($rs->fields('im_antes'));
		$this->im_depois->setDbValue($rs->fields('im_depois'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_identificador->DbValue = $row['nu_identificador'];
		$this->dt_data->DbValue = $row['dt_data'];
		$this->ds_dominioArquivo->DbValue = $row['ds_dominioArquivo'];
		$this->no_perfil->DbValue = $row['no_perfil'];
		$this->ic_acao->DbValue = $row['ic_acao'];
		$this->no_tabela->DbValue = $row['no_tabela'];
		$this->no_campo->DbValue = $row['no_campo'];
		$this->nu_chaveCampo->DbValue = $row['nu_chaveCampo'];
		$this->im_antes->DbValue = $row['im_antes'];
		$this->im_depois->DbValue = $row['im_depois'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_identificador")) <> "")
			$this->nu_identificador->CurrentValue = $this->getKey("nu_identificador"); // nu_identificador
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
		// nu_identificador
		// dt_data
		// ds_dominioArquivo
		// no_perfil
		// ic_acao
		// no_tabela
		// no_campo
		// nu_chaveCampo
		// im_antes
		// im_depois

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_identificador
			$this->nu_identificador->ViewValue = $this->nu_identificador->CurrentValue;
			$this->nu_identificador->ViewCustomAttributes = "";

			// dt_data
			$this->dt_data->ViewValue = $this->dt_data->CurrentValue;
			$this->dt_data->ViewValue = ew_FormatDateTime($this->dt_data->ViewValue, 7);
			$this->dt_data->ViewCustomAttributes = "";

			// ds_dominioArquivo
			$this->ds_dominioArquivo->ViewValue = $this->ds_dominioArquivo->CurrentValue;
			$this->ds_dominioArquivo->ViewCustomAttributes = "";

			// no_perfil
			$this->no_perfil->ViewValue = $this->no_perfil->CurrentValue;
			$this->no_perfil->ViewCustomAttributes = "";

			// ic_acao
			$this->ic_acao->ViewValue = $this->ic_acao->CurrentValue;
			$this->ic_acao->ViewCustomAttributes = "";

			// no_tabela
			$this->no_tabela->ViewValue = $this->no_tabela->CurrentValue;
			$this->no_tabela->ViewCustomAttributes = "";

			// no_campo
			$this->no_campo->ViewValue = $this->no_campo->CurrentValue;
			$this->no_campo->ViewCustomAttributes = "";

			// nu_identificador
			$this->nu_identificador->LinkCustomAttributes = "";
			$this->nu_identificador->HrefValue = "";
			$this->nu_identificador->TooltipValue = "";

			// dt_data
			$this->dt_data->LinkCustomAttributes = "";
			$this->dt_data->HrefValue = "";
			$this->dt_data->TooltipValue = "";

			// ds_dominioArquivo
			$this->ds_dominioArquivo->LinkCustomAttributes = "";
			$this->ds_dominioArquivo->HrefValue = "";
			$this->ds_dominioArquivo->TooltipValue = "";

			// no_perfil
			$this->no_perfil->LinkCustomAttributes = "";
			$this->no_perfil->HrefValue = "";
			$this->no_perfil->TooltipValue = "";

			// ic_acao
			$this->ic_acao->LinkCustomAttributes = "";
			$this->ic_acao->HrefValue = "";
			$this->ic_acao->TooltipValue = "";

			// no_tabela
			$this->no_tabela->LinkCustomAttributes = "";
			$this->no_tabela->HrefValue = "";
			$this->no_tabela->TooltipValue = "";

			// no_campo
			$this->no_campo->LinkCustomAttributes = "";
			$this->no_campo->HrefValue = "";
			$this->no_campo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_identificador
			$this->nu_identificador->EditCustomAttributes = "";
			$this->nu_identificador->EditValue = ew_HtmlEncode($this->nu_identificador->AdvancedSearch->SearchValue);
			$this->nu_identificador->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_identificador->FldCaption()));

			// dt_data
			$this->dt_data->EditCustomAttributes = "";
			$this->dt_data->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_data->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_data->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_data->FldCaption()));

			// ds_dominioArquivo
			$this->ds_dominioArquivo->EditCustomAttributes = "";
			$this->ds_dominioArquivo->EditValue = ew_HtmlEncode($this->ds_dominioArquivo->AdvancedSearch->SearchValue);
			$this->ds_dominioArquivo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_dominioArquivo->FldCaption()));

			// no_perfil
			$this->no_perfil->EditCustomAttributes = "";
			$this->no_perfil->EditValue = ew_HtmlEncode($this->no_perfil->AdvancedSearch->SearchValue);
			$this->no_perfil->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_perfil->FldCaption()));

			// ic_acao
			$this->ic_acao->EditCustomAttributes = "";
			$this->ic_acao->EditValue = ew_HtmlEncode($this->ic_acao->AdvancedSearch->SearchValue);
			$this->ic_acao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ic_acao->FldCaption()));

			// no_tabela
			$this->no_tabela->EditCustomAttributes = "";
			$this->no_tabela->EditValue = ew_HtmlEncode($this->no_tabela->AdvancedSearch->SearchValue);
			$this->no_tabela->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_tabela->FldCaption()));

			// no_campo
			$this->no_campo->EditCustomAttributes = "";
			$this->no_campo->EditValue = ew_HtmlEncode($this->no_campo->AdvancedSearch->SearchValue);
			$this->no_campo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_campo->FldCaption()));
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
		if (!ew_CheckInteger($this->nu_identificador->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_identificador->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dt_data->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->dt_data->FldErrMsg());
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
		$this->nu_identificador->AdvancedSearch->Load();
		$this->dt_data->AdvancedSearch->Load();
		$this->ds_dominioArquivo->AdvancedSearch->Load();
		$this->no_perfil->AdvancedSearch->Load();
		$this->ic_acao->AdvancedSearch->Load();
		$this->no_tabela->AdvancedSearch->Load();
		$this->no_campo->AdvancedSearch->Load();
		$this->nu_chaveCampo->AdvancedSearch->Load();
		$this->im_antes->AdvancedSearch->Load();
		$this->im_depois->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_auditoria\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_auditoria',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fauditorialist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_identificador); // nu_identificador
		$this->AddSearchQueryString($sQry, $this->dt_data); // dt_data
		$this->AddSearchQueryString($sQry, $this->ds_dominioArquivo); // ds_dominioArquivo
		$this->AddSearchQueryString($sQry, $this->no_perfil); // no_perfil
		$this->AddSearchQueryString($sQry, $this->ic_acao); // ic_acao
		$this->AddSearchQueryString($sQry, $this->no_tabela); // no_tabela
		$this->AddSearchQueryString($sQry, $this->no_campo); // no_campo
		$this->AddSearchQueryString($sQry, $this->nu_chaveCampo); // nu_chaveCampo
		$this->AddSearchQueryString($sQry, $this->im_antes); // im_antes
		$this->AddSearchQueryString($sQry, $this->im_depois); // im_depois

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
		$table = 'auditoria';
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
if (!isset($auditoria_list)) $auditoria_list = new cauditoria_list();

// Page init
$auditoria_list->Page_Init();

// Page main
$auditoria_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$auditoria_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($auditoria->Export == "") { ?>
<script type="text/javascript">

// Page object
var auditoria_list = new ew_Page("auditoria_list");
auditoria_list.PageID = "list"; // Page ID
var EW_PAGE_ID = auditoria_list.PageID; // For backward compatibility

// Form object
var fauditorialist = new ew_Form("fauditorialist");
fauditorialist.FormKeyCountName = '<?php echo $auditoria_list->FormKeyCountName ?>';

// Form_CustomValidate event
fauditorialist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fauditorialist.ValidateRequired = true;
<?php } else { ?>
fauditorialist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fauditorialistsrch = new ew_Form("fauditorialistsrch");

// Validate function for search
fauditorialistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_identificador");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($auditoria->nu_identificador->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_dt_data");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($auditoria->dt_data->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fauditorialistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fauditorialistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fauditorialistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (fauditorialistsrch) fauditorialistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($auditoria->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($auditoria_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $auditoria_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$auditoria_list->TotalRecs = $auditoria->SelectRecordCount();
	} else {
		if ($auditoria_list->Recordset = $auditoria_list->LoadRecordset())
			$auditoria_list->TotalRecs = $auditoria_list->Recordset->RecordCount();
	}
	$auditoria_list->StartRec = 1;
	if ($auditoria_list->DisplayRecs <= 0 || ($auditoria->Export <> "" && $auditoria->ExportAll)) // Display all records
		$auditoria_list->DisplayRecs = $auditoria_list->TotalRecs;
	if (!($auditoria->Export <> "" && $auditoria->ExportAll))
		$auditoria_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$auditoria_list->Recordset = $auditoria_list->LoadRecordset($auditoria_list->StartRec-1, $auditoria_list->DisplayRecs);
$auditoria_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($auditoria->Export == "" && $auditoria->CurrentAction == "") { ?>
<form name="fauditorialistsrch" id="fauditorialistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fauditorialistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fauditorialistsrch_SearchGroup" href="#fauditorialistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fauditorialistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fauditorialistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="auditoria">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$auditoria_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$auditoria->RowType = EW_ROWTYPE_SEARCH;

// Render row
$auditoria->ResetAttrs();
$auditoria_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($auditoria->nu_identificador->Visible) { // nu_identificador ?>
	<span id="xsc_nu_identificador" class="ewCell">
		<span class="ewSearchCaption"><?php echo $auditoria->nu_identificador->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_identificador" id="z_nu_identificador" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_identificador" name="x_nu_identificador" id="x_nu_identificador" placeholder="<?php echo $auditoria->nu_identificador->PlaceHolder ?>" value="<?php echo $auditoria->nu_identificador->EditValue ?>"<?php echo $auditoria->nu_identificador->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($auditoria->dt_data->Visible) { // dt_data ?>
	<span id="xsc_dt_data" class="ewCell">
		<span class="ewSearchCaption"><?php echo $auditoria->dt_data->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_dt_data" id="z_dt_data" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dt_data" name="x_dt_data" id="x_dt_data" placeholder="<?php echo $auditoria->dt_data->PlaceHolder ?>" value="<?php echo $auditoria->dt_data->EditValue ?>"<?php echo $auditoria->dt_data->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($auditoria->no_perfil->Visible) { // no_perfil ?>
	<span id="xsc_no_perfil" class="ewCell">
		<span class="ewSearchCaption"><?php echo $auditoria->no_perfil->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_perfil" id="z_no_perfil" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_perfil" name="x_no_perfil" id="x_no_perfil" size="30" maxlength="255" placeholder="<?php echo $auditoria->no_perfil->PlaceHolder ?>" value="<?php echo $auditoria->no_perfil->EditValue ?>"<?php echo $auditoria->no_perfil->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($auditoria->ic_acao->Visible) { // ic_acao ?>
	<span id="xsc_ic_acao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $auditoria->ic_acao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_acao" id="z_ic_acao" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_ic_acao" name="x_ic_acao" id="x_ic_acao" size="30" maxlength="255" placeholder="<?php echo $auditoria->ic_acao->PlaceHolder ?>" value="<?php echo $auditoria->ic_acao->EditValue ?>"<?php echo $auditoria->ic_acao->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($auditoria->no_tabela->Visible) { // no_tabela ?>
	<span id="xsc_no_tabela" class="ewCell">
		<span class="ewSearchCaption"><?php echo $auditoria->no_tabela->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_tabela" id="z_no_tabela" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_tabela" name="x_no_tabela" id="x_no_tabela" size="30" maxlength="255" placeholder="<?php echo $auditoria->no_tabela->PlaceHolder ?>" value="<?php echo $auditoria->no_tabela->EditValue ?>"<?php echo $auditoria->no_tabela->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($auditoria->no_campo->Visible) { // no_campo ?>
	<span id="xsc_no_campo" class="ewCell">
		<span class="ewSearchCaption"><?php echo $auditoria->no_campo->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_campo" id="z_no_campo" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_campo" name="x_no_campo" id="x_no_campo" size="30" maxlength="255" placeholder="<?php echo $auditoria->no_campo->PlaceHolder ?>" value="<?php echo $auditoria->no_campo->EditValue ?>"<?php echo $auditoria->no_campo->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $auditoria_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $auditoria_list->ShowPageHeader(); ?>
<?php
$auditoria_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fauditorialist" id="fauditorialist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="auditoria">
<div id="gmp_auditoria" class="ewGridMiddlePanel">
<?php if ($auditoria_list->TotalRecs > 0) { ?>
<table id="tbl_auditorialist" class="ewTable ewTableSeparate">
<?php echo $auditoria->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$auditoria_list->RenderListOptions();

// Render list options (header, left)
$auditoria_list->ListOptions->Render("header", "left");
?>
<?php if ($auditoria->nu_identificador->Visible) { // nu_identificador ?>
	<?php if ($auditoria->SortUrl($auditoria->nu_identificador) == "") { ?>
		<td><div id="elh_auditoria_nu_identificador" class="auditoria_nu_identificador"><div class="ewTableHeaderCaption"><?php echo $auditoria->nu_identificador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $auditoria->SortUrl($auditoria->nu_identificador) ?>',2);"><div id="elh_auditoria_nu_identificador" class="auditoria_nu_identificador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $auditoria->nu_identificador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($auditoria->nu_identificador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($auditoria->nu_identificador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($auditoria->dt_data->Visible) { // dt_data ?>
	<?php if ($auditoria->SortUrl($auditoria->dt_data) == "") { ?>
		<td><div id="elh_auditoria_dt_data" class="auditoria_dt_data"><div class="ewTableHeaderCaption"><?php echo $auditoria->dt_data->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $auditoria->SortUrl($auditoria->dt_data) ?>',2);"><div id="elh_auditoria_dt_data" class="auditoria_dt_data">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $auditoria->dt_data->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($auditoria->dt_data->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($auditoria->dt_data->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($auditoria->ds_dominioArquivo->Visible) { // ds_dominioArquivo ?>
	<?php if ($auditoria->SortUrl($auditoria->ds_dominioArquivo) == "") { ?>
		<td><div id="elh_auditoria_ds_dominioArquivo" class="auditoria_ds_dominioArquivo"><div class="ewTableHeaderCaption"><?php echo $auditoria->ds_dominioArquivo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $auditoria->SortUrl($auditoria->ds_dominioArquivo) ?>',2);"><div id="elh_auditoria_ds_dominioArquivo" class="auditoria_ds_dominioArquivo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $auditoria->ds_dominioArquivo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($auditoria->ds_dominioArquivo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($auditoria->ds_dominioArquivo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($auditoria->no_perfil->Visible) { // no_perfil ?>
	<?php if ($auditoria->SortUrl($auditoria->no_perfil) == "") { ?>
		<td><div id="elh_auditoria_no_perfil" class="auditoria_no_perfil"><div class="ewTableHeaderCaption"><?php echo $auditoria->no_perfil->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $auditoria->SortUrl($auditoria->no_perfil) ?>',2);"><div id="elh_auditoria_no_perfil" class="auditoria_no_perfil">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $auditoria->no_perfil->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($auditoria->no_perfil->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($auditoria->no_perfil->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($auditoria->ic_acao->Visible) { // ic_acao ?>
	<?php if ($auditoria->SortUrl($auditoria->ic_acao) == "") { ?>
		<td><div id="elh_auditoria_ic_acao" class="auditoria_ic_acao"><div class="ewTableHeaderCaption"><?php echo $auditoria->ic_acao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $auditoria->SortUrl($auditoria->ic_acao) ?>',2);"><div id="elh_auditoria_ic_acao" class="auditoria_ic_acao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $auditoria->ic_acao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($auditoria->ic_acao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($auditoria->ic_acao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($auditoria->no_tabela->Visible) { // no_tabela ?>
	<?php if ($auditoria->SortUrl($auditoria->no_tabela) == "") { ?>
		<td><div id="elh_auditoria_no_tabela" class="auditoria_no_tabela"><div class="ewTableHeaderCaption"><?php echo $auditoria->no_tabela->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $auditoria->SortUrl($auditoria->no_tabela) ?>',2);"><div id="elh_auditoria_no_tabela" class="auditoria_no_tabela">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $auditoria->no_tabela->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($auditoria->no_tabela->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($auditoria->no_tabela->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($auditoria->no_campo->Visible) { // no_campo ?>
	<?php if ($auditoria->SortUrl($auditoria->no_campo) == "") { ?>
		<td><div id="elh_auditoria_no_campo" class="auditoria_no_campo"><div class="ewTableHeaderCaption"><?php echo $auditoria->no_campo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $auditoria->SortUrl($auditoria->no_campo) ?>',2);"><div id="elh_auditoria_no_campo" class="auditoria_no_campo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $auditoria->no_campo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($auditoria->no_campo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($auditoria->no_campo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$auditoria_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($auditoria->ExportAll && $auditoria->Export <> "") {
	$auditoria_list->StopRec = $auditoria_list->TotalRecs;
} else {

	// Set the last record to display
	if ($auditoria_list->TotalRecs > $auditoria_list->StartRec + $auditoria_list->DisplayRecs - 1)
		$auditoria_list->StopRec = $auditoria_list->StartRec + $auditoria_list->DisplayRecs - 1;
	else
		$auditoria_list->StopRec = $auditoria_list->TotalRecs;
}
$auditoria_list->RecCnt = $auditoria_list->StartRec - 1;
if ($auditoria_list->Recordset && !$auditoria_list->Recordset->EOF) {
	$auditoria_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $auditoria_list->StartRec > 1)
		$auditoria_list->Recordset->Move($auditoria_list->StartRec - 1);
} elseif (!$auditoria->AllowAddDeleteRow && $auditoria_list->StopRec == 0) {
	$auditoria_list->StopRec = $auditoria->GridAddRowCount;
}

// Initialize aggregate
$auditoria->RowType = EW_ROWTYPE_AGGREGATEINIT;
$auditoria->ResetAttrs();
$auditoria_list->RenderRow();
while ($auditoria_list->RecCnt < $auditoria_list->StopRec) {
	$auditoria_list->RecCnt++;
	if (intval($auditoria_list->RecCnt) >= intval($auditoria_list->StartRec)) {
		$auditoria_list->RowCnt++;

		// Set up key count
		$auditoria_list->KeyCount = $auditoria_list->RowIndex;

		// Init row class and style
		$auditoria->ResetAttrs();
		$auditoria->CssClass = "";
		if ($auditoria->CurrentAction == "gridadd") {
		} else {
			$auditoria_list->LoadRowValues($auditoria_list->Recordset); // Load row values
		}
		$auditoria->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$auditoria->RowAttrs = array_merge($auditoria->RowAttrs, array('data-rowindex'=>$auditoria_list->RowCnt, 'id'=>'r' . $auditoria_list->RowCnt . '_auditoria', 'data-rowtype'=>$auditoria->RowType));

		// Render row
		$auditoria_list->RenderRow();

		// Render list options
		$auditoria_list->RenderListOptions();
?>
	<tr<?php echo $auditoria->RowAttributes() ?>>
<?php

// Render list options (body, left)
$auditoria_list->ListOptions->Render("body", "left", $auditoria_list->RowCnt);
?>
	<?php if ($auditoria->nu_identificador->Visible) { // nu_identificador ?>
		<td<?php echo $auditoria->nu_identificador->CellAttributes() ?>>
<span<?php echo $auditoria->nu_identificador->ViewAttributes() ?>>
<?php echo $auditoria->nu_identificador->ListViewValue() ?></span>
<a id="<?php echo $auditoria_list->PageObjName . "_row_" . $auditoria_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($auditoria->dt_data->Visible) { // dt_data ?>
		<td<?php echo $auditoria->dt_data->CellAttributes() ?>>
<span<?php echo $auditoria->dt_data->ViewAttributes() ?>>
<?php echo $auditoria->dt_data->ListViewValue() ?></span>
<a id="<?php echo $auditoria_list->PageObjName . "_row_" . $auditoria_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($auditoria->ds_dominioArquivo->Visible) { // ds_dominioArquivo ?>
		<td<?php echo $auditoria->ds_dominioArquivo->CellAttributes() ?>>
<span<?php echo $auditoria->ds_dominioArquivo->ViewAttributes() ?>>
<?php echo $auditoria->ds_dominioArquivo->ListViewValue() ?></span>
<a id="<?php echo $auditoria_list->PageObjName . "_row_" . $auditoria_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($auditoria->no_perfil->Visible) { // no_perfil ?>
		<td<?php echo $auditoria->no_perfil->CellAttributes() ?>>
<span<?php echo $auditoria->no_perfil->ViewAttributes() ?>>
<?php echo $auditoria->no_perfil->ListViewValue() ?></span>
<a id="<?php echo $auditoria_list->PageObjName . "_row_" . $auditoria_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($auditoria->ic_acao->Visible) { // ic_acao ?>
		<td<?php echo $auditoria->ic_acao->CellAttributes() ?>>
<span<?php echo $auditoria->ic_acao->ViewAttributes() ?>>
<?php echo $auditoria->ic_acao->ListViewValue() ?></span>
<a id="<?php echo $auditoria_list->PageObjName . "_row_" . $auditoria_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($auditoria->no_tabela->Visible) { // no_tabela ?>
		<td<?php echo $auditoria->no_tabela->CellAttributes() ?>>
<span<?php echo $auditoria->no_tabela->ViewAttributes() ?>>
<?php echo $auditoria->no_tabela->ListViewValue() ?></span>
<a id="<?php echo $auditoria_list->PageObjName . "_row_" . $auditoria_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($auditoria->no_campo->Visible) { // no_campo ?>
		<td<?php echo $auditoria->no_campo->CellAttributes() ?>>
<span<?php echo $auditoria->no_campo->ViewAttributes() ?>>
<?php echo $auditoria->no_campo->ListViewValue() ?></span>
<a id="<?php echo $auditoria_list->PageObjName . "_row_" . $auditoria_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$auditoria_list->ListOptions->Render("body", "right", $auditoria_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($auditoria->CurrentAction <> "gridadd")
		$auditoria_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($auditoria->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($auditoria_list->Recordset)
	$auditoria_list->Recordset->Close();
?>
<?php if ($auditoria->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($auditoria->CurrentAction <> "gridadd" && $auditoria->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($auditoria_list->Pager)) $auditoria_list->Pager = new cNumericPager($auditoria_list->StartRec, $auditoria_list->DisplayRecs, $auditoria_list->TotalRecs, $auditoria_list->RecRange) ?>
<?php if ($auditoria_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($auditoria_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $auditoria_list->PageUrl() ?>start=<?php echo $auditoria_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($auditoria_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $auditoria_list->PageUrl() ?>start=<?php echo $auditoria_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($auditoria_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $auditoria_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($auditoria_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $auditoria_list->PageUrl() ?>start=<?php echo $auditoria_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($auditoria_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $auditoria_list->PageUrl() ?>start=<?php echo $auditoria_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($auditoria_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $auditoria_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $auditoria_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $auditoria_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($auditoria_list->SearchWhere == "0=101") { ?>
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
	foreach ($auditoria_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($auditoria->Export == "") { ?>
<script type="text/javascript">
fauditorialistsrch.Init();
fauditorialist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$auditoria_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($auditoria->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$auditoria_list->Page_Terminate();
?>
