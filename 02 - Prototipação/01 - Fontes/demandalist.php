<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "demandainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$demanda_list = NULL; // Initialize page object first

class cdemanda_list extends cdemanda {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'demanda';

	// Page object name
	var $PageObjName = 'demanda_list';

	// Grid form hidden field names
	var $FormName = 'fdemandalist';
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

		// Table object (demanda)
		if (!isset($GLOBALS["demanda"])) {
			$GLOBALS["demanda"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["demanda"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "demandaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "demandadelete.php";
		$this->MultiUpdateUrl = "demandaupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'demanda', TRUE);

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
		$this->nu_demanda->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->dt_registro->Visible = !$this->IsAddOrEdit();
		$this->nu_usuario->Visible = !$this->IsAddOrEdit();
		$this->ts_datahora->Visible = !$this->IsAddOrEdit();

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
			$this->nu_demanda->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_demanda->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_demanda, FALSE); // nu_demanda
		$this->BuildSearchSql($sWhere, $this->ds_demanda, FALSE); // ds_demanda
		$this->BuildSearchSql($sWhere, $this->nu_pessoaResponsavel, FALSE); // nu_pessoaResponsavel
		$this->BuildSearchSql($sWhere, $this->nu_itemPDTI, FALSE); // nu_itemPDTI
		$this->BuildSearchSql($sWhere, $this->dt_registro, FALSE); // dt_registro
		$this->BuildSearchSql($sWhere, $this->im_anexo, FALSE); // im_anexo
		$this->BuildSearchSql($sWhere, $this->ic_situacao, FALSE); // ic_situacao
		$this->BuildSearchSql($sWhere, $this->dt_aprovacao, FALSE); // dt_aprovacao
		$this->BuildSearchSql($sWhere, $this->nu_pessoaAprovadora, FALSE); // nu_pessoaAprovadora
		$this->BuildSearchSql($sWhere, $this->nu_usuario, FALSE); // nu_usuario
		$this->BuildSearchSql($sWhere, $this->ts_datahora, FALSE); // ts_datahora

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_demanda->AdvancedSearch->Save(); // nu_demanda
			$this->ds_demanda->AdvancedSearch->Save(); // ds_demanda
			$this->nu_pessoaResponsavel->AdvancedSearch->Save(); // nu_pessoaResponsavel
			$this->nu_itemPDTI->AdvancedSearch->Save(); // nu_itemPDTI
			$this->dt_registro->AdvancedSearch->Save(); // dt_registro
			$this->im_anexo->AdvancedSearch->Save(); // im_anexo
			$this->ic_situacao->AdvancedSearch->Save(); // ic_situacao
			$this->dt_aprovacao->AdvancedSearch->Save(); // dt_aprovacao
			$this->nu_pessoaAprovadora->AdvancedSearch->Save(); // nu_pessoaAprovadora
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
		if ($this->nu_demanda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_demanda->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_pessoaResponsavel->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_itemPDTI->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_registro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_anexo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_situacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_aprovacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_pessoaAprovadora->AdvancedSearch->IssetSession())
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
		$this->nu_demanda->AdvancedSearch->UnsetSession();
		$this->ds_demanda->AdvancedSearch->UnsetSession();
		$this->nu_pessoaResponsavel->AdvancedSearch->UnsetSession();
		$this->nu_itemPDTI->AdvancedSearch->UnsetSession();
		$this->dt_registro->AdvancedSearch->UnsetSession();
		$this->im_anexo->AdvancedSearch->UnsetSession();
		$this->ic_situacao->AdvancedSearch->UnsetSession();
		$this->dt_aprovacao->AdvancedSearch->UnsetSession();
		$this->nu_pessoaAprovadora->AdvancedSearch->UnsetSession();
		$this->nu_usuario->AdvancedSearch->UnsetSession();
		$this->ts_datahora->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_demanda->AdvancedSearch->Load();
		$this->ds_demanda->AdvancedSearch->Load();
		$this->nu_pessoaResponsavel->AdvancedSearch->Load();
		$this->nu_itemPDTI->AdvancedSearch->Load();
		$this->dt_registro->AdvancedSearch->Load();
		$this->im_anexo->AdvancedSearch->Load();
		$this->ic_situacao->AdvancedSearch->Load();
		$this->dt_aprovacao->AdvancedSearch->Load();
		$this->nu_pessoaAprovadora->AdvancedSearch->Load();
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
			$this->UpdateSort($this->nu_demanda, $bCtrl); // nu_demanda
			$this->UpdateSort($this->nu_pessoaResponsavel, $bCtrl); // nu_pessoaResponsavel
			$this->UpdateSort($this->nu_itemPDTI, $bCtrl); // nu_itemPDTI
			$this->UpdateSort($this->dt_registro, $bCtrl); // dt_registro
			$this->UpdateSort($this->im_anexo, $bCtrl); // im_anexo
			$this->UpdateSort($this->ic_situacao, $bCtrl); // ic_situacao
			$this->UpdateSort($this->dt_aprovacao, $bCtrl); // dt_aprovacao
			$this->UpdateSort($this->nu_pessoaAprovadora, $bCtrl); // nu_pessoaAprovadora
			$this->UpdateSort($this->nu_usuario, $bCtrl); // nu_usuario
			$this->UpdateSort($this->ts_datahora, $bCtrl); // ts_datahora
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
				$this->nu_demanda->setSort("");
				$this->nu_pessoaResponsavel->setSort("");
				$this->nu_itemPDTI->setSort("");
				$this->dt_registro->setSort("");
				$this->im_anexo->setSort("");
				$this->ic_situacao->setSort("");
				$this->dt_aprovacao->setSort("");
				$this->nu_pessoaAprovadora->setSort("");
				$this->nu_usuario->setSort("");
				$this->ts_datahora->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fdemandalist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_demanda

		$this->nu_demanda->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_demanda"]);
		if ($this->nu_demanda->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_demanda->AdvancedSearch->SearchOperator = @$_GET["z_nu_demanda"];

		// ds_demanda
		$this->ds_demanda->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_demanda"]);
		if ($this->ds_demanda->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_demanda->AdvancedSearch->SearchOperator = @$_GET["z_ds_demanda"];

		// nu_pessoaResponsavel
		$this->nu_pessoaResponsavel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_pessoaResponsavel"]);
		if ($this->nu_pessoaResponsavel->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_pessoaResponsavel->AdvancedSearch->SearchOperator = @$_GET["z_nu_pessoaResponsavel"];

		// nu_itemPDTI
		$this->nu_itemPDTI->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_itemPDTI"]);
		if ($this->nu_itemPDTI->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_itemPDTI->AdvancedSearch->SearchOperator = @$_GET["z_nu_itemPDTI"];

		// dt_registro
		$this->dt_registro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_registro"]);
		if ($this->dt_registro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_registro->AdvancedSearch->SearchOperator = @$_GET["z_dt_registro"];

		// im_anexo
		$this->im_anexo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_anexo"]);
		if ($this->im_anexo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_anexo->AdvancedSearch->SearchOperator = @$_GET["z_im_anexo"];

		// ic_situacao
		$this->ic_situacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_situacao"]);
		if ($this->ic_situacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_situacao->AdvancedSearch->SearchOperator = @$_GET["z_ic_situacao"];

		// dt_aprovacao
		$this->dt_aprovacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_aprovacao"]);
		if ($this->dt_aprovacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_aprovacao->AdvancedSearch->SearchOperator = @$_GET["z_dt_aprovacao"];

		// nu_pessoaAprovadora
		$this->nu_pessoaAprovadora->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_pessoaAprovadora"]);
		if ($this->nu_pessoaAprovadora->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_pessoaAprovadora->AdvancedSearch->SearchOperator = @$_GET["z_nu_pessoaAprovadora"];

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
		$this->nu_demanda->setDbValue($rs->fields('nu_demanda'));
		$this->ds_demanda->setDbValue($rs->fields('ds_demanda'));
		$this->nu_pessoaResponsavel->setDbValue($rs->fields('nu_pessoaResponsavel'));
		$this->nu_itemPDTI->setDbValue($rs->fields('nu_itemPDTI'));
		$this->dt_registro->setDbValue($rs->fields('dt_registro'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->dt_aprovacao->setDbValue($rs->fields('dt_aprovacao'));
		$this->nu_pessoaAprovadora->setDbValue($rs->fields('nu_pessoaAprovadora'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_demanda->DbValue = $row['nu_demanda'];
		$this->ds_demanda->DbValue = $row['ds_demanda'];
		$this->nu_pessoaResponsavel->DbValue = $row['nu_pessoaResponsavel'];
		$this->nu_itemPDTI->DbValue = $row['nu_itemPDTI'];
		$this->dt_registro->DbValue = $row['dt_registro'];
		$this->im_anexo->Upload->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->dt_aprovacao->DbValue = $row['dt_aprovacao'];
		$this->nu_pessoaAprovadora->DbValue = $row['nu_pessoaAprovadora'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_demanda")) <> "")
			$this->nu_demanda->CurrentValue = $this->getKey("nu_demanda"); // nu_demanda
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
		// nu_demanda
		// ds_demanda
		// nu_pessoaResponsavel
		// nu_itemPDTI
		// dt_registro
		// im_anexo
		// ic_situacao
		// dt_aprovacao
		// nu_pessoaAprovadora
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_demanda
			$this->nu_demanda->ViewValue = $this->nu_demanda->CurrentValue;
			$this->nu_demanda->ViewCustomAttributes = "";

			// nu_pessoaResponsavel
			if (strval($this->nu_pessoaResponsavel->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoaResponsavel->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaResponsavel, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_pessoa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoaResponsavel->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoaResponsavel->ViewValue = $this->nu_pessoaResponsavel->CurrentValue;
				}
			} else {
				$this->nu_pessoaResponsavel->ViewValue = NULL;
			}
			$this->nu_pessoaResponsavel->ViewCustomAttributes = "";

			// nu_itemPDTI
			$this->nu_itemPDTI->ViewValue = $this->nu_itemPDTI->CurrentValue;
			$this->nu_itemPDTI->ViewCustomAttributes = "";

			// dt_registro
			$this->dt_registro->ViewValue = $this->dt_registro->CurrentValue;
			$this->dt_registro->ViewValue = ew_FormatDateTime($this->dt_registro->ViewValue, 7);
			$this->dt_registro->ViewCustomAttributes = "";

			// im_anexo
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
					case $this->ic_situacao->FldTagValue(3):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(4):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// dt_aprovacao
			$this->dt_aprovacao->ViewValue = $this->dt_aprovacao->CurrentValue;
			$this->dt_aprovacao->ViewValue = ew_FormatDateTime($this->dt_aprovacao->ViewValue, 7);
			$this->dt_aprovacao->ViewCustomAttributes = "";

			// nu_pessoaAprovadora
			if (strval($this->nu_pessoaAprovadora->CurrentValue) <> "") {
				$sFilterWrk = "[nu_pessoa]" . ew_SearchString("=", $this->nu_pessoaAprovadora->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_pessoa], [no_pessoa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[pessoa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_pessoaAprovadora, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_pessoaAprovadora->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_pessoaAprovadora->ViewValue = $this->nu_pessoaAprovadora->CurrentValue;
				}
			} else {
				$this->nu_pessoaAprovadora->ViewValue = NULL;
			}
			$this->nu_pessoaAprovadora->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_demanda
			$this->nu_demanda->LinkCustomAttributes = "";
			$this->nu_demanda->HrefValue = "";
			$this->nu_demanda->TooltipValue = "";

			// nu_pessoaResponsavel
			$this->nu_pessoaResponsavel->LinkCustomAttributes = "";
			$this->nu_pessoaResponsavel->HrefValue = "";
			$this->nu_pessoaResponsavel->TooltipValue = "";

			// nu_itemPDTI
			$this->nu_itemPDTI->LinkCustomAttributes = "";
			$this->nu_itemPDTI->HrefValue = "";
			$this->nu_itemPDTI->TooltipValue = "";

			// dt_registro
			$this->dt_registro->LinkCustomAttributes = "";
			$this->dt_registro->HrefValue = "";
			$this->dt_registro->TooltipValue = "";

			// im_anexo
			$this->im_anexo->LinkCustomAttributes = "";
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;
			$this->im_anexo->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

			// dt_aprovacao
			$this->dt_aprovacao->LinkCustomAttributes = "";
			$this->dt_aprovacao->HrefValue = "";
			$this->dt_aprovacao->TooltipValue = "";

			// nu_pessoaAprovadora
			$this->nu_pessoaAprovadora->LinkCustomAttributes = "";
			$this->nu_pessoaAprovadora->HrefValue = "";
			$this->nu_pessoaAprovadora->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// ts_datahora
			$this->ts_datahora->LinkCustomAttributes = "";
			$this->ts_datahora->HrefValue = "";
			$this->ts_datahora->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_demanda
			$this->nu_demanda->EditCustomAttributes = "";
			$this->nu_demanda->EditValue = ew_HtmlEncode($this->nu_demanda->AdvancedSearch->SearchValue);
			$this->nu_demanda->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_demanda->FldCaption()));

			// nu_pessoaResponsavel
			$this->nu_pessoaResponsavel->EditCustomAttributes = "";

			// nu_itemPDTI
			$this->nu_itemPDTI->EditCustomAttributes = "";
			$this->nu_itemPDTI->EditValue = ew_HtmlEncode($this->nu_itemPDTI->AdvancedSearch->SearchValue);
			$this->nu_itemPDTI->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_itemPDTI->FldCaption()));

			// dt_registro
			$this->dt_registro->EditCustomAttributes = "";
			$this->dt_registro->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_registro->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_registro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_registro->FldCaption()));

			// im_anexo
			$this->im_anexo->EditCustomAttributes = "";
			$this->im_anexo->EditValue = ew_HtmlEncode($this->im_anexo->AdvancedSearch->SearchValue);
			$this->im_anexo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->im_anexo->FldCaption()));

			// ic_situacao
			$this->ic_situacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_situacao->FldTagValue(1), $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->FldTagValue(1));
			$arwrk[] = array($this->ic_situacao->FldTagValue(2), $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->FldTagValue(2));
			$arwrk[] = array($this->ic_situacao->FldTagValue(3), $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->FldTagValue(3));
			$arwrk[] = array($this->ic_situacao->FldTagValue(4), $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_situacao->EditValue = $arwrk;

			// dt_aprovacao
			$this->dt_aprovacao->EditCustomAttributes = "";
			$this->dt_aprovacao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_aprovacao->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_aprovacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_aprovacao->FldCaption()));

			// nu_pessoaAprovadora
			$this->nu_pessoaAprovadora->EditCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->EditCustomAttributes = "";
			$this->nu_usuario->EditValue = ew_HtmlEncode($this->nu_usuario->AdvancedSearch->SearchValue);
			$this->nu_usuario->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_usuario->FldCaption()));

			// ts_datahora
			$this->ts_datahora->EditCustomAttributes = "";
			$this->ts_datahora->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->ts_datahora->AdvancedSearch->SearchValue, 7), 7));
			$this->ts_datahora->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ts_datahora->FldCaption()));
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
		if (!ew_CheckInteger($this->nu_demanda->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_demanda->FldErrMsg());
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
		$this->nu_demanda->AdvancedSearch->Load();
		$this->ds_demanda->AdvancedSearch->Load();
		$this->nu_pessoaResponsavel->AdvancedSearch->Load();
		$this->nu_itemPDTI->AdvancedSearch->Load();
		$this->dt_registro->AdvancedSearch->Load();
		$this->im_anexo->AdvancedSearch->Load();
		$this->ic_situacao->AdvancedSearch->Load();
		$this->dt_aprovacao->AdvancedSearch->Load();
		$this->nu_pessoaAprovadora->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_demanda\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_demanda',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fdemandalist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_demanda); // nu_demanda
		$this->AddSearchQueryString($sQry, $this->ds_demanda); // ds_demanda
		$this->AddSearchQueryString($sQry, $this->nu_pessoaResponsavel); // nu_pessoaResponsavel
		$this->AddSearchQueryString($sQry, $this->nu_itemPDTI); // nu_itemPDTI
		$this->AddSearchQueryString($sQry, $this->dt_registro); // dt_registro
		$this->AddSearchQueryString($sQry, $this->ic_situacao); // ic_situacao
		$this->AddSearchQueryString($sQry, $this->dt_aprovacao); // dt_aprovacao
		$this->AddSearchQueryString($sQry, $this->nu_pessoaAprovadora); // nu_pessoaAprovadora
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
if (!isset($demanda_list)) $demanda_list = new cdemanda_list();

// Page init
$demanda_list->Page_Init();

// Page main
$demanda_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$demanda_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($demanda->Export == "") { ?>
<script type="text/javascript">

// Page object
var demanda_list = new ew_Page("demanda_list");
demanda_list.PageID = "list"; // Page ID
var EW_PAGE_ID = demanda_list.PageID; // For backward compatibility

// Form object
var fdemandalist = new ew_Form("fdemandalist");
fdemandalist.FormKeyCountName = '<?php echo $demanda_list->FormKeyCountName ?>';

// Form_CustomValidate event
fdemandalist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdemandalist.ValidateRequired = true;
<?php } else { ?>
fdemandalist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdemandalist.Lists["x_nu_pessoaResponsavel"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fdemandalist.Lists["x_nu_pessoaAprovadora"] = {"LinkField":"x_nu_pessoa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_pessoa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fdemandalistsrch = new ew_Form("fdemandalistsrch");

// Validate function for search
fdemandalistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_demanda");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($demanda->nu_demanda->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fdemandalistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdemandalistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fdemandalistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (fdemandalistsrch) fdemandalistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($demanda->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($demanda_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $demanda_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$demanda_list->TotalRecs = $demanda->SelectRecordCount();
	} else {
		if ($demanda_list->Recordset = $demanda_list->LoadRecordset())
			$demanda_list->TotalRecs = $demanda_list->Recordset->RecordCount();
	}
	$demanda_list->StartRec = 1;
	if ($demanda_list->DisplayRecs <= 0 || ($demanda->Export <> "" && $demanda->ExportAll)) // Display all records
		$demanda_list->DisplayRecs = $demanda_list->TotalRecs;
	if (!($demanda->Export <> "" && $demanda->ExportAll))
		$demanda_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$demanda_list->Recordset = $demanda_list->LoadRecordset($demanda_list->StartRec-1, $demanda_list->DisplayRecs);
$demanda_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($demanda->Export == "" && $demanda->CurrentAction == "") { ?>
<form name="fdemandalistsrch" id="fdemandalistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fdemandalistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fdemandalistsrch_SearchGroup" href="#fdemandalistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fdemandalistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fdemandalistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="demanda">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$demanda_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$demanda->RowType = EW_ROWTYPE_SEARCH;

// Render row
$demanda->ResetAttrs();
$demanda_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($demanda->nu_demanda->Visible) { // nu_demanda ?>
	<span id="xsc_nu_demanda" class="ewCell">
		<span class="ewSearchCaption"><?php echo $demanda->nu_demanda->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_demanda" id="z_nu_demanda" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_demanda" name="x_nu_demanda" id="x_nu_demanda" placeholder="<?php echo $demanda->nu_demanda->PlaceHolder ?>" value="<?php echo $demanda->nu_demanda->EditValue ?>"<?php echo $demanda->nu_demanda->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($demanda->ic_situacao->Visible) { // ic_situacao ?>
	<span id="xsc_ic_situacao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $demanda->ic_situacao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_situacao" id="z_ic_situacao" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_situacao" id="x_ic_situacao" name="x_ic_situacao"<?php echo $demanda->ic_situacao->EditAttributes() ?>>
<?php
if (is_array($demanda->ic_situacao->EditValue)) {
	$arwrk = $demanda->ic_situacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($demanda->ic_situacao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<div id="xsr_3" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $demanda_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $demanda_list->ShowPageHeader(); ?>
<?php
$demanda_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fdemandalist" id="fdemandalist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="demanda">
<div id="gmp_demanda" class="ewGridMiddlePanel">
<?php if ($demanda_list->TotalRecs > 0) { ?>
<table id="tbl_demandalist" class="ewTable ewTableSeparate">
<?php echo $demanda->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$demanda_list->RenderListOptions();

// Render list options (header, left)
$demanda_list->ListOptions->Render("header", "left");
?>
<?php if ($demanda->nu_demanda->Visible) { // nu_demanda ?>
	<?php if ($demanda->SortUrl($demanda->nu_demanda) == "") { ?>
		<td><div id="elh_demanda_nu_demanda" class="demanda_nu_demanda"><div class="ewTableHeaderCaption"><?php echo $demanda->nu_demanda->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->nu_demanda) ?>',2);"><div id="elh_demanda_nu_demanda" class="demanda_nu_demanda">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->nu_demanda->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->nu_demanda->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->nu_demanda->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($demanda->nu_pessoaResponsavel->Visible) { // nu_pessoaResponsavel ?>
	<?php if ($demanda->SortUrl($demanda->nu_pessoaResponsavel) == "") { ?>
		<td><div id="elh_demanda_nu_pessoaResponsavel" class="demanda_nu_pessoaResponsavel"><div class="ewTableHeaderCaption"><?php echo $demanda->nu_pessoaResponsavel->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->nu_pessoaResponsavel) ?>',2);"><div id="elh_demanda_nu_pessoaResponsavel" class="demanda_nu_pessoaResponsavel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->nu_pessoaResponsavel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->nu_pessoaResponsavel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->nu_pessoaResponsavel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($demanda->nu_itemPDTI->Visible) { // nu_itemPDTI ?>
	<?php if ($demanda->SortUrl($demanda->nu_itemPDTI) == "") { ?>
		<td><div id="elh_demanda_nu_itemPDTI" class="demanda_nu_itemPDTI"><div class="ewTableHeaderCaption"><?php echo $demanda->nu_itemPDTI->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->nu_itemPDTI) ?>',2);"><div id="elh_demanda_nu_itemPDTI" class="demanda_nu_itemPDTI">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->nu_itemPDTI->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->nu_itemPDTI->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->nu_itemPDTI->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($demanda->dt_registro->Visible) { // dt_registro ?>
	<?php if ($demanda->SortUrl($demanda->dt_registro) == "") { ?>
		<td><div id="elh_demanda_dt_registro" class="demanda_dt_registro"><div class="ewTableHeaderCaption"><?php echo $demanda->dt_registro->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->dt_registro) ?>',2);"><div id="elh_demanda_dt_registro" class="demanda_dt_registro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->dt_registro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->dt_registro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->dt_registro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($demanda->im_anexo->Visible) { // im_anexo ?>
	<?php if ($demanda->SortUrl($demanda->im_anexo) == "") { ?>
		<td><div id="elh_demanda_im_anexo" class="demanda_im_anexo"><div class="ewTableHeaderCaption"><?php echo $demanda->im_anexo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->im_anexo) ?>',2);"><div id="elh_demanda_im_anexo" class="demanda_im_anexo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->im_anexo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->im_anexo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->im_anexo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($demanda->ic_situacao->Visible) { // ic_situacao ?>
	<?php if ($demanda->SortUrl($demanda->ic_situacao) == "") { ?>
		<td><div id="elh_demanda_ic_situacao" class="demanda_ic_situacao"><div class="ewTableHeaderCaption"><?php echo $demanda->ic_situacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->ic_situacao) ?>',2);"><div id="elh_demanda_ic_situacao" class="demanda_ic_situacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->ic_situacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->ic_situacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->ic_situacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($demanda->dt_aprovacao->Visible) { // dt_aprovacao ?>
	<?php if ($demanda->SortUrl($demanda->dt_aprovacao) == "") { ?>
		<td><div id="elh_demanda_dt_aprovacao" class="demanda_dt_aprovacao"><div class="ewTableHeaderCaption"><?php echo $demanda->dt_aprovacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->dt_aprovacao) ?>',2);"><div id="elh_demanda_dt_aprovacao" class="demanda_dt_aprovacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->dt_aprovacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->dt_aprovacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->dt_aprovacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($demanda->nu_pessoaAprovadora->Visible) { // nu_pessoaAprovadora ?>
	<?php if ($demanda->SortUrl($demanda->nu_pessoaAprovadora) == "") { ?>
		<td><div id="elh_demanda_nu_pessoaAprovadora" class="demanda_nu_pessoaAprovadora"><div class="ewTableHeaderCaption"><?php echo $demanda->nu_pessoaAprovadora->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->nu_pessoaAprovadora) ?>',2);"><div id="elh_demanda_nu_pessoaAprovadora" class="demanda_nu_pessoaAprovadora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->nu_pessoaAprovadora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->nu_pessoaAprovadora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->nu_pessoaAprovadora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($demanda->nu_usuario->Visible) { // nu_usuario ?>
	<?php if ($demanda->SortUrl($demanda->nu_usuario) == "") { ?>
		<td><div id="elh_demanda_nu_usuario" class="demanda_nu_usuario"><div class="ewTableHeaderCaption"><?php echo $demanda->nu_usuario->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->nu_usuario) ?>',2);"><div id="elh_demanda_nu_usuario" class="demanda_nu_usuario">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->nu_usuario->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->nu_usuario->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->nu_usuario->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($demanda->ts_datahora->Visible) { // ts_datahora ?>
	<?php if ($demanda->SortUrl($demanda->ts_datahora) == "") { ?>
		<td><div id="elh_demanda_ts_datahora" class="demanda_ts_datahora"><div class="ewTableHeaderCaption"><?php echo $demanda->ts_datahora->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $demanda->SortUrl($demanda->ts_datahora) ?>',2);"><div id="elh_demanda_ts_datahora" class="demanda_ts_datahora">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $demanda->ts_datahora->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($demanda->ts_datahora->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($demanda->ts_datahora->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$demanda_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($demanda->ExportAll && $demanda->Export <> "") {
	$demanda_list->StopRec = $demanda_list->TotalRecs;
} else {

	// Set the last record to display
	if ($demanda_list->TotalRecs > $demanda_list->StartRec + $demanda_list->DisplayRecs - 1)
		$demanda_list->StopRec = $demanda_list->StartRec + $demanda_list->DisplayRecs - 1;
	else
		$demanda_list->StopRec = $demanda_list->TotalRecs;
}
$demanda_list->RecCnt = $demanda_list->StartRec - 1;
if ($demanda_list->Recordset && !$demanda_list->Recordset->EOF) {
	$demanda_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $demanda_list->StartRec > 1)
		$demanda_list->Recordset->Move($demanda_list->StartRec - 1);
} elseif (!$demanda->AllowAddDeleteRow && $demanda_list->StopRec == 0) {
	$demanda_list->StopRec = $demanda->GridAddRowCount;
}

// Initialize aggregate
$demanda->RowType = EW_ROWTYPE_AGGREGATEINIT;
$demanda->ResetAttrs();
$demanda_list->RenderRow();
while ($demanda_list->RecCnt < $demanda_list->StopRec) {
	$demanda_list->RecCnt++;
	if (intval($demanda_list->RecCnt) >= intval($demanda_list->StartRec)) {
		$demanda_list->RowCnt++;

		// Set up key count
		$demanda_list->KeyCount = $demanda_list->RowIndex;

		// Init row class and style
		$demanda->ResetAttrs();
		$demanda->CssClass = "";
		if ($demanda->CurrentAction == "gridadd") {
		} else {
			$demanda_list->LoadRowValues($demanda_list->Recordset); // Load row values
		}
		$demanda->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$demanda->RowAttrs = array_merge($demanda->RowAttrs, array('data-rowindex'=>$demanda_list->RowCnt, 'id'=>'r' . $demanda_list->RowCnt . '_demanda', 'data-rowtype'=>$demanda->RowType));

		// Render row
		$demanda_list->RenderRow();

		// Render list options
		$demanda_list->RenderListOptions();
?>
	<tr<?php echo $demanda->RowAttributes() ?>>
<?php

// Render list options (body, left)
$demanda_list->ListOptions->Render("body", "left", $demanda_list->RowCnt);
?>
	<?php if ($demanda->nu_demanda->Visible) { // nu_demanda ?>
		<td<?php echo $demanda->nu_demanda->CellAttributes() ?>>
<span<?php echo $demanda->nu_demanda->ViewAttributes() ?>>
<?php echo $demanda->nu_demanda->ListViewValue() ?></span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($demanda->nu_pessoaResponsavel->Visible) { // nu_pessoaResponsavel ?>
		<td<?php echo $demanda->nu_pessoaResponsavel->CellAttributes() ?>>
<span<?php echo $demanda->nu_pessoaResponsavel->ViewAttributes() ?>>
<?php echo $demanda->nu_pessoaResponsavel->ListViewValue() ?></span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($demanda->nu_itemPDTI->Visible) { // nu_itemPDTI ?>
		<td<?php echo $demanda->nu_itemPDTI->CellAttributes() ?>>
<span<?php echo $demanda->nu_itemPDTI->ViewAttributes() ?>>
<?php echo $demanda->nu_itemPDTI->ListViewValue() ?></span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($demanda->dt_registro->Visible) { // dt_registro ?>
		<td<?php echo $demanda->dt_registro->CellAttributes() ?>>
<span<?php echo $demanda->dt_registro->ViewAttributes() ?>>
<?php echo $demanda->dt_registro->ListViewValue() ?></span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($demanda->im_anexo->Visible) { // im_anexo ?>
		<td<?php echo $demanda->im_anexo->CellAttributes() ?>>
<span<?php echo $demanda->im_anexo->ViewAttributes() ?>>
<?php if ($demanda->im_anexo->LinkAttributes() <> "") { ?>
<?php if (!empty($demanda->im_anexo->Upload->DbValue)) { ?>
<?php echo $demanda->im_anexo->ListViewValue() ?>
<?php } elseif (!in_array($demanda->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($demanda->im_anexo->Upload->DbValue)) { ?>
<?php echo $demanda->im_anexo->ListViewValue() ?>
<?php } elseif (!in_array($demanda->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($demanda->ic_situacao->Visible) { // ic_situacao ?>
		<td<?php echo $demanda->ic_situacao->CellAttributes() ?>>
<span<?php echo $demanda->ic_situacao->ViewAttributes() ?>>
<?php echo $demanda->ic_situacao->ListViewValue() ?></span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($demanda->dt_aprovacao->Visible) { // dt_aprovacao ?>
		<td<?php echo $demanda->dt_aprovacao->CellAttributes() ?>>
<span<?php echo $demanda->dt_aprovacao->ViewAttributes() ?>>
<?php echo $demanda->dt_aprovacao->ListViewValue() ?></span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($demanda->nu_pessoaAprovadora->Visible) { // nu_pessoaAprovadora ?>
		<td<?php echo $demanda->nu_pessoaAprovadora->CellAttributes() ?>>
<span<?php echo $demanda->nu_pessoaAprovadora->ViewAttributes() ?>>
<?php echo $demanda->nu_pessoaAprovadora->ListViewValue() ?></span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($demanda->nu_usuario->Visible) { // nu_usuario ?>
		<td<?php echo $demanda->nu_usuario->CellAttributes() ?>>
<span<?php echo $demanda->nu_usuario->ViewAttributes() ?>>
<?php echo $demanda->nu_usuario->ListViewValue() ?></span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($demanda->ts_datahora->Visible) { // ts_datahora ?>
		<td<?php echo $demanda->ts_datahora->CellAttributes() ?>>
<span<?php echo $demanda->ts_datahora->ViewAttributes() ?>>
<?php echo $demanda->ts_datahora->ListViewValue() ?></span>
<a id="<?php echo $demanda_list->PageObjName . "_row_" . $demanda_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$demanda_list->ListOptions->Render("body", "right", $demanda_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($demanda->CurrentAction <> "gridadd")
		$demanda_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($demanda->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($demanda_list->Recordset)
	$demanda_list->Recordset->Close();
?>
<?php if ($demanda->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($demanda->CurrentAction <> "gridadd" && $demanda->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($demanda_list->Pager)) $demanda_list->Pager = new cNumericPager($demanda_list->StartRec, $demanda_list->DisplayRecs, $demanda_list->TotalRecs, $demanda_list->RecRange) ?>
<?php if ($demanda_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($demanda_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $demanda_list->PageUrl() ?>start=<?php echo $demanda_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($demanda_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $demanda_list->PageUrl() ?>start=<?php echo $demanda_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($demanda_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $demanda_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($demanda_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $demanda_list->PageUrl() ?>start=<?php echo $demanda_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($demanda_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $demanda_list->PageUrl() ?>start=<?php echo $demanda_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($demanda_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $demanda_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $demanda_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $demanda_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($demanda_list->SearchWhere == "0=101") { ?>
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
	foreach ($demanda_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($demanda->Export == "") { ?>
<script type="text/javascript">
fdemandalistsrch.Init();
fdemandalist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$demanda_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($demanda->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$demanda_list->Page_Terminate();
?>
