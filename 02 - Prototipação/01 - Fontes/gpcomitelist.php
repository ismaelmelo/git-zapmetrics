<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gpcomiteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gpcomite_list = NULL; // Initialize page object first

class cgpcomite_list extends cgpcomite {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'gpcomite';

	// Page object name
	var $PageObjName = 'gpcomite_list';

	// Grid form hidden field names
	var $FormName = 'fgpcomitelist';
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

		// Table object (gpcomite)
		if (!isset($GLOBALS["gpcomite"])) {
			$GLOBALS["gpcomite"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gpcomite"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "gpcomiteadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "gpcomitedelete.php";
		$this->MultiUpdateUrl = "gpcomiteupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'gpcomite', TRUE);

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
		$this->nu_gpComite->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->nu_gpComite->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_gpComite->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_gpComite, FALSE); // nu_gpComite
		$this->BuildSearchSql($sWhere, $this->no_gpComite, FALSE); // no_gpComite
		$this->BuildSearchSql($sWhere, $this->ic_tpGpOuComite, FALSE); // ic_tpGpOuComite
		$this->BuildSearchSql($sWhere, $this->ds_descricao, FALSE); // ds_descricao
		$this->BuildSearchSql($sWhere, $this->ds_finalidade, FALSE); // ds_finalidade
		$this->BuildSearchSql($sWhere, $this->ic_natureza, FALSE); // ic_natureza
		$this->BuildSearchSql($sWhere, $this->ds_competencias, FALSE); // ds_competencias
		$this->BuildSearchSql($sWhere, $this->ic_periodicidadeReunioes, FALSE); // ic_periodicidadeReunioes
		$this->BuildSearchSql($sWhere, $this->dt_basePeriodicidade, FALSE); // dt_basePeriodicidade
		$this->BuildSearchSql($sWhere, $this->no_localDocDiretrizes, FALSE); // no_localDocDiretrizes
		$this->BuildSearchSql($sWhere, $this->im_anexoDiretrizes, FALSE); // im_anexoDiretrizes
		$this->BuildSearchSql($sWhere, $this->no_localDocComunicacao, FALSE); // no_localDocComunicacao
		$this->BuildSearchSql($sWhere, $this->im_anexoComunicacao, FALSE); // im_anexoComunicacao
		$this->BuildSearchSql($sWhere, $this->no_localParecerJuridico, FALSE); // no_localParecerJuridico
		$this->BuildSearchSql($sWhere, $this->im_anexoParecerJuridico, FALSE); // im_anexoParecerJuridico
		$this->BuildSearchSql($sWhere, $this->no_localDocDesignacao, FALSE); // no_localDocDesignacao
		$this->BuildSearchSql($sWhere, $this->im_anexoDesignacao, FALSE); // im_anexoDesignacao
		$this->BuildSearchSql($sWhere, $this->ds_partesInteressadas, FALSE); // ds_partesInteressadas
		$this->BuildSearchSql($sWhere, $this->nu_usuario, FALSE); // nu_usuario
		$this->BuildSearchSql($sWhere, $this->ts_datahora, FALSE); // ts_datahora

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_gpComite->AdvancedSearch->Save(); // nu_gpComite
			$this->no_gpComite->AdvancedSearch->Save(); // no_gpComite
			$this->ic_tpGpOuComite->AdvancedSearch->Save(); // ic_tpGpOuComite
			$this->ds_descricao->AdvancedSearch->Save(); // ds_descricao
			$this->ds_finalidade->AdvancedSearch->Save(); // ds_finalidade
			$this->ic_natureza->AdvancedSearch->Save(); // ic_natureza
			$this->ds_competencias->AdvancedSearch->Save(); // ds_competencias
			$this->ic_periodicidadeReunioes->AdvancedSearch->Save(); // ic_periodicidadeReunioes
			$this->dt_basePeriodicidade->AdvancedSearch->Save(); // dt_basePeriodicidade
			$this->no_localDocDiretrizes->AdvancedSearch->Save(); // no_localDocDiretrizes
			$this->im_anexoDiretrizes->AdvancedSearch->Save(); // im_anexoDiretrizes
			$this->no_localDocComunicacao->AdvancedSearch->Save(); // no_localDocComunicacao
			$this->im_anexoComunicacao->AdvancedSearch->Save(); // im_anexoComunicacao
			$this->no_localParecerJuridico->AdvancedSearch->Save(); // no_localParecerJuridico
			$this->im_anexoParecerJuridico->AdvancedSearch->Save(); // im_anexoParecerJuridico
			$this->no_localDocDesignacao->AdvancedSearch->Save(); // no_localDocDesignacao
			$this->im_anexoDesignacao->AdvancedSearch->Save(); // im_anexoDesignacao
			$this->ds_partesInteressadas->AdvancedSearch->Save(); // ds_partesInteressadas
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
		if ($this->nu_gpComite->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_gpComite->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_tpGpOuComite->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_descricao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_finalidade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_natureza->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_competencias->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_periodicidadeReunioes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_basePeriodicidade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_localDocDiretrizes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_anexoDiretrizes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_localDocComunicacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_anexoComunicacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_localParecerJuridico->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_anexoParecerJuridico->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_localDocDesignacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_anexoDesignacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_partesInteressadas->AdvancedSearch->IssetSession())
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
		$this->nu_gpComite->AdvancedSearch->UnsetSession();
		$this->no_gpComite->AdvancedSearch->UnsetSession();
		$this->ic_tpGpOuComite->AdvancedSearch->UnsetSession();
		$this->ds_descricao->AdvancedSearch->UnsetSession();
		$this->ds_finalidade->AdvancedSearch->UnsetSession();
		$this->ic_natureza->AdvancedSearch->UnsetSession();
		$this->ds_competencias->AdvancedSearch->UnsetSession();
		$this->ic_periodicidadeReunioes->AdvancedSearch->UnsetSession();
		$this->dt_basePeriodicidade->AdvancedSearch->UnsetSession();
		$this->no_localDocDiretrizes->AdvancedSearch->UnsetSession();
		$this->im_anexoDiretrizes->AdvancedSearch->UnsetSession();
		$this->no_localDocComunicacao->AdvancedSearch->UnsetSession();
		$this->im_anexoComunicacao->AdvancedSearch->UnsetSession();
		$this->no_localParecerJuridico->AdvancedSearch->UnsetSession();
		$this->im_anexoParecerJuridico->AdvancedSearch->UnsetSession();
		$this->no_localDocDesignacao->AdvancedSearch->UnsetSession();
		$this->im_anexoDesignacao->AdvancedSearch->UnsetSession();
		$this->ds_partesInteressadas->AdvancedSearch->UnsetSession();
		$this->nu_usuario->AdvancedSearch->UnsetSession();
		$this->ts_datahora->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_gpComite->AdvancedSearch->Load();
		$this->no_gpComite->AdvancedSearch->Load();
		$this->ic_tpGpOuComite->AdvancedSearch->Load();
		$this->ds_descricao->AdvancedSearch->Load();
		$this->ds_finalidade->AdvancedSearch->Load();
		$this->ic_natureza->AdvancedSearch->Load();
		$this->ds_competencias->AdvancedSearch->Load();
		$this->ic_periodicidadeReunioes->AdvancedSearch->Load();
		$this->dt_basePeriodicidade->AdvancedSearch->Load();
		$this->no_localDocDiretrizes->AdvancedSearch->Load();
		$this->im_anexoDiretrizes->AdvancedSearch->Load();
		$this->no_localDocComunicacao->AdvancedSearch->Load();
		$this->im_anexoComunicacao->AdvancedSearch->Load();
		$this->no_localParecerJuridico->AdvancedSearch->Load();
		$this->im_anexoParecerJuridico->AdvancedSearch->Load();
		$this->no_localDocDesignacao->AdvancedSearch->Load();
		$this->im_anexoDesignacao->AdvancedSearch->Load();
		$this->ds_partesInteressadas->AdvancedSearch->Load();
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
			$this->UpdateSort($this->nu_gpComite, $bCtrl); // nu_gpComite
			$this->UpdateSort($this->no_gpComite, $bCtrl); // no_gpComite
			$this->UpdateSort($this->ic_tpGpOuComite, $bCtrl); // ic_tpGpOuComite
			$this->UpdateSort($this->ic_natureza, $bCtrl); // ic_natureza
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
				$this->nu_gpComite->setSort("");
				$this->no_gpComite->setSort("");
				$this->ic_tpGpOuComite->setSort("");
				$this->ic_natureza->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fgpcomitelist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_gpComite

		$this->nu_gpComite->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_gpComite"]);
		if ($this->nu_gpComite->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_gpComite->AdvancedSearch->SearchOperator = @$_GET["z_nu_gpComite"];

		// no_gpComite
		$this->no_gpComite->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_gpComite"]);
		if ($this->no_gpComite->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_gpComite->AdvancedSearch->SearchOperator = @$_GET["z_no_gpComite"];

		// ic_tpGpOuComite
		$this->ic_tpGpOuComite->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_tpGpOuComite"]);
		if ($this->ic_tpGpOuComite->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_tpGpOuComite->AdvancedSearch->SearchOperator = @$_GET["z_ic_tpGpOuComite"];

		// ds_descricao
		$this->ds_descricao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_descricao"]);
		if ($this->ds_descricao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_descricao->AdvancedSearch->SearchOperator = @$_GET["z_ds_descricao"];

		// ds_finalidade
		$this->ds_finalidade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_finalidade"]);
		if ($this->ds_finalidade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_finalidade->AdvancedSearch->SearchOperator = @$_GET["z_ds_finalidade"];

		// ic_natureza
		$this->ic_natureza->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_natureza"]);
		if ($this->ic_natureza->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_natureza->AdvancedSearch->SearchOperator = @$_GET["z_ic_natureza"];

		// ds_competencias
		$this->ds_competencias->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_competencias"]);
		if ($this->ds_competencias->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_competencias->AdvancedSearch->SearchOperator = @$_GET["z_ds_competencias"];

		// ic_periodicidadeReunioes
		$this->ic_periodicidadeReunioes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_periodicidadeReunioes"]);
		if ($this->ic_periodicidadeReunioes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_periodicidadeReunioes->AdvancedSearch->SearchOperator = @$_GET["z_ic_periodicidadeReunioes"];

		// dt_basePeriodicidade
		$this->dt_basePeriodicidade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_basePeriodicidade"]);
		if ($this->dt_basePeriodicidade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_basePeriodicidade->AdvancedSearch->SearchOperator = @$_GET["z_dt_basePeriodicidade"];

		// no_localDocDiretrizes
		$this->no_localDocDiretrizes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_localDocDiretrizes"]);
		if ($this->no_localDocDiretrizes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_localDocDiretrizes->AdvancedSearch->SearchOperator = @$_GET["z_no_localDocDiretrizes"];

		// im_anexoDiretrizes
		$this->im_anexoDiretrizes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_anexoDiretrizes"]);
		if ($this->im_anexoDiretrizes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_anexoDiretrizes->AdvancedSearch->SearchOperator = @$_GET["z_im_anexoDiretrizes"];

		// no_localDocComunicacao
		$this->no_localDocComunicacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_localDocComunicacao"]);
		if ($this->no_localDocComunicacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_localDocComunicacao->AdvancedSearch->SearchOperator = @$_GET["z_no_localDocComunicacao"];

		// im_anexoComunicacao
		$this->im_anexoComunicacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_anexoComunicacao"]);
		if ($this->im_anexoComunicacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_anexoComunicacao->AdvancedSearch->SearchOperator = @$_GET["z_im_anexoComunicacao"];

		// no_localParecerJuridico
		$this->no_localParecerJuridico->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_localParecerJuridico"]);
		if ($this->no_localParecerJuridico->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_localParecerJuridico->AdvancedSearch->SearchOperator = @$_GET["z_no_localParecerJuridico"];

		// im_anexoParecerJuridico
		$this->im_anexoParecerJuridico->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_anexoParecerJuridico"]);
		if ($this->im_anexoParecerJuridico->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_anexoParecerJuridico->AdvancedSearch->SearchOperator = @$_GET["z_im_anexoParecerJuridico"];

		// no_localDocDesignacao
		$this->no_localDocDesignacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_localDocDesignacao"]);
		if ($this->no_localDocDesignacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_localDocDesignacao->AdvancedSearch->SearchOperator = @$_GET["z_no_localDocDesignacao"];

		// im_anexoDesignacao
		$this->im_anexoDesignacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_anexoDesignacao"]);
		if ($this->im_anexoDesignacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_anexoDesignacao->AdvancedSearch->SearchOperator = @$_GET["z_im_anexoDesignacao"];

		// ds_partesInteressadas
		$this->ds_partesInteressadas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_partesInteressadas"]);
		if ($this->ds_partesInteressadas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_partesInteressadas->AdvancedSearch->SearchOperator = @$_GET["z_ds_partesInteressadas"];

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
		$this->nu_gpComite->setDbValue($rs->fields('nu_gpComite'));
		$this->no_gpComite->setDbValue($rs->fields('no_gpComite'));
		$this->ic_tpGpOuComite->setDbValue($rs->fields('ic_tpGpOuComite'));
		$this->ds_descricao->setDbValue($rs->fields('ds_descricao'));
		$this->ds_finalidade->setDbValue($rs->fields('ds_finalidade'));
		$this->ic_natureza->setDbValue($rs->fields('ic_natureza'));
		$this->ds_competencias->setDbValue($rs->fields('ds_competencias'));
		$this->ic_periodicidadeReunioes->setDbValue($rs->fields('ic_periodicidadeReunioes'));
		$this->dt_basePeriodicidade->setDbValue($rs->fields('dt_basePeriodicidade'));
		$this->no_localDocDiretrizes->setDbValue($rs->fields('no_localDocDiretrizes'));
		$this->im_anexoDiretrizes->Upload->DbValue = $rs->fields('im_anexoDiretrizes');
		$this->no_localDocComunicacao->setDbValue($rs->fields('no_localDocComunicacao'));
		$this->im_anexoComunicacao->Upload->DbValue = $rs->fields('im_anexoComunicacao');
		$this->no_localParecerJuridico->setDbValue($rs->fields('no_localParecerJuridico'));
		$this->im_anexoParecerJuridico->Upload->DbValue = $rs->fields('im_anexoParecerJuridico');
		$this->no_localDocDesignacao->setDbValue($rs->fields('no_localDocDesignacao'));
		$this->im_anexoDesignacao->Upload->DbValue = $rs->fields('im_anexoDesignacao');
		$this->ds_partesInteressadas->setDbValue($rs->fields('ds_partesInteressadas'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_gpComite->DbValue = $row['nu_gpComite'];
		$this->no_gpComite->DbValue = $row['no_gpComite'];
		$this->ic_tpGpOuComite->DbValue = $row['ic_tpGpOuComite'];
		$this->ds_descricao->DbValue = $row['ds_descricao'];
		$this->ds_finalidade->DbValue = $row['ds_finalidade'];
		$this->ic_natureza->DbValue = $row['ic_natureza'];
		$this->ds_competencias->DbValue = $row['ds_competencias'];
		$this->ic_periodicidadeReunioes->DbValue = $row['ic_periodicidadeReunioes'];
		$this->dt_basePeriodicidade->DbValue = $row['dt_basePeriodicidade'];
		$this->no_localDocDiretrizes->DbValue = $row['no_localDocDiretrizes'];
		$this->im_anexoDiretrizes->Upload->DbValue = $row['im_anexoDiretrizes'];
		$this->no_localDocComunicacao->DbValue = $row['no_localDocComunicacao'];
		$this->im_anexoComunicacao->Upload->DbValue = $row['im_anexoComunicacao'];
		$this->no_localParecerJuridico->DbValue = $row['no_localParecerJuridico'];
		$this->im_anexoParecerJuridico->Upload->DbValue = $row['im_anexoParecerJuridico'];
		$this->no_localDocDesignacao->DbValue = $row['no_localDocDesignacao'];
		$this->im_anexoDesignacao->Upload->DbValue = $row['im_anexoDesignacao'];
		$this->ds_partesInteressadas->DbValue = $row['ds_partesInteressadas'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_gpComite")) <> "")
			$this->nu_gpComite->CurrentValue = $this->getKey("nu_gpComite"); // nu_gpComite
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
		// nu_gpComite
		// no_gpComite
		// ic_tpGpOuComite
		// ds_descricao
		// ds_finalidade
		// ic_natureza
		// ds_competencias
		// ic_periodicidadeReunioes
		// dt_basePeriodicidade
		// no_localDocDiretrizes
		// im_anexoDiretrizes
		// no_localDocComunicacao
		// im_anexoComunicacao
		// no_localParecerJuridico
		// im_anexoParecerJuridico
		// no_localDocDesignacao
		// im_anexoDesignacao
		// ds_partesInteressadas
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_gpComite
			$this->nu_gpComite->ViewValue = $this->nu_gpComite->CurrentValue;
			$this->nu_gpComite->ViewCustomAttributes = "";

			// no_gpComite
			$this->no_gpComite->ViewValue = $this->no_gpComite->CurrentValue;
			$this->no_gpComite->ViewCustomAttributes = "";

			// ic_tpGpOuComite
			if (strval($this->ic_tpGpOuComite->CurrentValue) <> "") {
				switch ($this->ic_tpGpOuComite->CurrentValue) {
					case $this->ic_tpGpOuComite->FldTagValue(1):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(1) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(1) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					case $this->ic_tpGpOuComite->FldTagValue(2):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(2) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(2) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					case $this->ic_tpGpOuComite->FldTagValue(3):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(3) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(3) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					default:
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->CurrentValue;
				}
			} else {
				$this->ic_tpGpOuComite->ViewValue = NULL;
			}
			$this->ic_tpGpOuComite->ViewCustomAttributes = "";

			// ic_natureza
			if (strval($this->ic_natureza->CurrentValue) <> "") {
				switch ($this->ic_natureza->CurrentValue) {
					case $this->ic_natureza->FldTagValue(1):
						$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(1) <> "" ? $this->ic_natureza->FldTagCaption(1) : $this->ic_natureza->CurrentValue;
						break;
					case $this->ic_natureza->FldTagValue(2):
						$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(2) <> "" ? $this->ic_natureza->FldTagCaption(2) : $this->ic_natureza->CurrentValue;
						break;
					default:
						$this->ic_natureza->ViewValue = $this->ic_natureza->CurrentValue;
				}
			} else {
				$this->ic_natureza->ViewValue = NULL;
			}
			$this->ic_natureza->ViewCustomAttributes = "";

			// ic_periodicidadeReunioes
			if (strval($this->ic_periodicidadeReunioes->CurrentValue) <> "") {
				switch ($this->ic_periodicidadeReunioes->CurrentValue) {
					case $this->ic_periodicidadeReunioes->FldTagValue(1):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(1) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(1) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(2):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(2) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(2) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(3):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(3) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(3) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(4):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(4) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(4) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(5):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(5) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(5) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(6):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(6) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(6) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					default:
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->CurrentValue;
				}
			} else {
				$this->ic_periodicidadeReunioes->ViewValue = NULL;
			}
			$this->ic_periodicidadeReunioes->ViewCustomAttributes = "";

			// dt_basePeriodicidade
			$this->dt_basePeriodicidade->ViewValue = $this->dt_basePeriodicidade->CurrentValue;
			$this->dt_basePeriodicidade->ViewValue = ew_FormatDateTime($this->dt_basePeriodicidade->ViewValue, 7);
			$this->dt_basePeriodicidade->ViewCustomAttributes = "";

			// no_localDocDiretrizes
			$this->no_localDocDiretrizes->ViewValue = $this->no_localDocDiretrizes->CurrentValue;
			$this->no_localDocDiretrizes->ViewCustomAttributes = "";

			// im_anexoDiretrizes
			$this->im_anexoDiretrizes->UploadPath = "arquivos/grupocti_diretrizes";
			if (!ew_Empty($this->im_anexoDiretrizes->Upload->DbValue)) {
				$this->im_anexoDiretrizes->ViewValue = $this->im_anexoDiretrizes->Upload->DbValue;
			} else {
				$this->im_anexoDiretrizes->ViewValue = "";
			}
			$this->im_anexoDiretrizes->ViewCustomAttributes = "";

			// no_localDocComunicacao
			$this->no_localDocComunicacao->ViewValue = $this->no_localDocComunicacao->CurrentValue;
			$this->no_localDocComunicacao->ViewCustomAttributes = "";

			// im_anexoComunicacao
			$this->im_anexoComunicacao->UploadPath = "arquivos/grupocti_comunicacao";
			if (!ew_Empty($this->im_anexoComunicacao->Upload->DbValue)) {
				$this->im_anexoComunicacao->ViewValue = $this->im_anexoComunicacao->Upload->DbValue;
			} else {
				$this->im_anexoComunicacao->ViewValue = "";
			}
			$this->im_anexoComunicacao->ViewCustomAttributes = "";

			// no_localParecerJuridico
			$this->no_localParecerJuridico->ViewValue = $this->no_localParecerJuridico->CurrentValue;
			$this->no_localParecerJuridico->ViewCustomAttributes = "";

			// im_anexoParecerJuridico
			$this->im_anexoParecerJuridico->UploadPath = "arquivos/grupocti_parjuridico";
			if (!ew_Empty($this->im_anexoParecerJuridico->Upload->DbValue)) {
				$this->im_anexoParecerJuridico->ViewValue = $this->im_anexoParecerJuridico->Upload->DbValue;
			} else {
				$this->im_anexoParecerJuridico->ViewValue = "";
			}
			$this->im_anexoParecerJuridico->ViewCustomAttributes = "";

			// no_localDocDesignacao
			$this->no_localDocDesignacao->ViewValue = $this->no_localDocDesignacao->CurrentValue;
			$this->no_localDocDesignacao->ViewCustomAttributes = "";

			// im_anexoDesignacao
			$this->im_anexoDesignacao->UploadPath = "arquivos/grupocti_designacao";
			if (!ew_Empty($this->im_anexoDesignacao->Upload->DbValue)) {
				$this->im_anexoDesignacao->ViewValue = $this->im_anexoDesignacao->Upload->DbValue;
			} else {
				$this->im_anexoDesignacao->ViewValue = "";
			}
			$this->im_anexoDesignacao->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_gpComite
			$this->nu_gpComite->LinkCustomAttributes = "";
			$this->nu_gpComite->HrefValue = "";
			$this->nu_gpComite->TooltipValue = "";

			// no_gpComite
			$this->no_gpComite->LinkCustomAttributes = "";
			$this->no_gpComite->HrefValue = "";
			$this->no_gpComite->TooltipValue = "";

			// ic_tpGpOuComite
			$this->ic_tpGpOuComite->LinkCustomAttributes = "";
			$this->ic_tpGpOuComite->HrefValue = "";
			$this->ic_tpGpOuComite->TooltipValue = "";

			// ic_natureza
			$this->ic_natureza->LinkCustomAttributes = "";
			$this->ic_natureza->HrefValue = "";
			$this->ic_natureza->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_gpComite
			$this->nu_gpComite->EditCustomAttributes = "";
			$this->nu_gpComite->EditValue = ew_HtmlEncode($this->nu_gpComite->AdvancedSearch->SearchValue);
			$this->nu_gpComite->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_gpComite->FldCaption()));

			// no_gpComite
			$this->no_gpComite->EditCustomAttributes = "";
			$this->no_gpComite->EditValue = ew_HtmlEncode($this->no_gpComite->AdvancedSearch->SearchValue);
			$this->no_gpComite->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_gpComite->FldCaption()));

			// ic_tpGpOuComite
			$this->ic_tpGpOuComite->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpGpOuComite->FldTagValue(1), $this->ic_tpGpOuComite->FldTagCaption(1) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(1) : $this->ic_tpGpOuComite->FldTagValue(1));
			$arwrk[] = array($this->ic_tpGpOuComite->FldTagValue(2), $this->ic_tpGpOuComite->FldTagCaption(2) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(2) : $this->ic_tpGpOuComite->FldTagValue(2));
			$arwrk[] = array($this->ic_tpGpOuComite->FldTagValue(3), $this->ic_tpGpOuComite->FldTagCaption(3) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(3) : $this->ic_tpGpOuComite->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpGpOuComite->EditValue = $arwrk;

			// ic_natureza
			$this->ic_natureza->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_natureza->FldTagValue(1), $this->ic_natureza->FldTagCaption(1) <> "" ? $this->ic_natureza->FldTagCaption(1) : $this->ic_natureza->FldTagValue(1));
			$arwrk[] = array($this->ic_natureza->FldTagValue(2), $this->ic_natureza->FldTagCaption(2) <> "" ? $this->ic_natureza->FldTagCaption(2) : $this->ic_natureza->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_natureza->EditValue = $arwrk;
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
		$this->nu_gpComite->AdvancedSearch->Load();
		$this->no_gpComite->AdvancedSearch->Load();
		$this->ic_tpGpOuComite->AdvancedSearch->Load();
		$this->ds_descricao->AdvancedSearch->Load();
		$this->ds_finalidade->AdvancedSearch->Load();
		$this->ic_natureza->AdvancedSearch->Load();
		$this->ds_competencias->AdvancedSearch->Load();
		$this->ic_periodicidadeReunioes->AdvancedSearch->Load();
		$this->dt_basePeriodicidade->AdvancedSearch->Load();
		$this->no_localDocDiretrizes->AdvancedSearch->Load();
		$this->im_anexoDiretrizes->AdvancedSearch->Load();
		$this->no_localDocComunicacao->AdvancedSearch->Load();
		$this->im_anexoComunicacao->AdvancedSearch->Load();
		$this->no_localParecerJuridico->AdvancedSearch->Load();
		$this->im_anexoParecerJuridico->AdvancedSearch->Load();
		$this->no_localDocDesignacao->AdvancedSearch->Load();
		$this->im_anexoDesignacao->AdvancedSearch->Load();
		$this->ds_partesInteressadas->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_gpcomite\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_gpcomite',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fgpcomitelist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_gpComite); // nu_gpComite
		$this->AddSearchQueryString($sQry, $this->no_gpComite); // no_gpComite
		$this->AddSearchQueryString($sQry, $this->ic_tpGpOuComite); // ic_tpGpOuComite
		$this->AddSearchQueryString($sQry, $this->ds_descricao); // ds_descricao
		$this->AddSearchQueryString($sQry, $this->ds_finalidade); // ds_finalidade
		$this->AddSearchQueryString($sQry, $this->ic_natureza); // ic_natureza
		$this->AddSearchQueryString($sQry, $this->ds_competencias); // ds_competencias
		$this->AddSearchQueryString($sQry, $this->ic_periodicidadeReunioes); // ic_periodicidadeReunioes
		$this->AddSearchQueryString($sQry, $this->dt_basePeriodicidade); // dt_basePeriodicidade
		$this->AddSearchQueryString($sQry, $this->no_localDocDiretrizes); // no_localDocDiretrizes
		$this->AddSearchQueryString($sQry, $this->no_localDocComunicacao); // no_localDocComunicacao
		$this->AddSearchQueryString($sQry, $this->no_localParecerJuridico); // no_localParecerJuridico
		$this->AddSearchQueryString($sQry, $this->no_localDocDesignacao); // no_localDocDesignacao
		$this->AddSearchQueryString($sQry, $this->ds_partesInteressadas); // ds_partesInteressadas
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
if (!isset($gpcomite_list)) $gpcomite_list = new cgpcomite_list();

// Page init
$gpcomite_list->Page_Init();

// Page main
$gpcomite_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gpcomite_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($gpcomite->Export == "") { ?>
<script type="text/javascript">

// Page object
var gpcomite_list = new ew_Page("gpcomite_list");
gpcomite_list.PageID = "list"; // Page ID
var EW_PAGE_ID = gpcomite_list.PageID; // For backward compatibility

// Form object
var fgpcomitelist = new ew_Form("fgpcomitelist");
fgpcomitelist.FormKeyCountName = '<?php echo $gpcomite_list->FormKeyCountName ?>';

// Form_CustomValidate event
fgpcomitelist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpcomitelist.ValidateRequired = true;
<?php } else { ?>
fgpcomitelist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fgpcomitelistsrch = new ew_Form("fgpcomitelistsrch");

// Validate function for search
fgpcomitelistsrch.Validate = function(fobj) {
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
fgpcomitelistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpcomitelistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fgpcomitelistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (fgpcomitelistsrch) fgpcomitelistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($gpcomite->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($gpcomite_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $gpcomite_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$gpcomite_list->TotalRecs = $gpcomite->SelectRecordCount();
	} else {
		if ($gpcomite_list->Recordset = $gpcomite_list->LoadRecordset())
			$gpcomite_list->TotalRecs = $gpcomite_list->Recordset->RecordCount();
	}
	$gpcomite_list->StartRec = 1;
	if ($gpcomite_list->DisplayRecs <= 0 || ($gpcomite->Export <> "" && $gpcomite->ExportAll)) // Display all records
		$gpcomite_list->DisplayRecs = $gpcomite_list->TotalRecs;
	if (!($gpcomite->Export <> "" && $gpcomite->ExportAll))
		$gpcomite_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$gpcomite_list->Recordset = $gpcomite_list->LoadRecordset($gpcomite_list->StartRec-1, $gpcomite_list->DisplayRecs);
$gpcomite_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($gpcomite->Export == "" && $gpcomite->CurrentAction == "") { ?>
<form name="fgpcomitelistsrch" id="fgpcomitelistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fgpcomitelistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fgpcomitelistsrch_SearchGroup" href="#fgpcomitelistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fgpcomitelistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fgpcomitelistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="gpcomite">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$gpcomite_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$gpcomite->RowType = EW_ROWTYPE_SEARCH;

// Render row
$gpcomite->ResetAttrs();
$gpcomite_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($gpcomite->no_gpComite->Visible) { // no_gpComite ?>
	<span id="xsc_no_gpComite" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpcomite->no_gpComite->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_gpComite" id="z_no_gpComite" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_gpComite" name="x_no_gpComite" id="x_no_gpComite" size="30" maxlength="50" placeholder="<?php echo $gpcomite->no_gpComite->PlaceHolder ?>" value="<?php echo $gpcomite->no_gpComite->EditValue ?>"<?php echo $gpcomite->no_gpComite->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($gpcomite->ic_tpGpOuComite->Visible) { // ic_tpGpOuComite ?>
	<span id="xsc_ic_tpGpOuComite" class="ewCell">
		<span class="ewSearchCaption"><?php echo $gpcomite->ic_tpGpOuComite->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_tpGpOuComite" id="z_ic_tpGpOuComite" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_tpGpOuComite" id="x_ic_tpGpOuComite" name="x_ic_tpGpOuComite"<?php echo $gpcomite->ic_tpGpOuComite->EditAttributes() ?>>
<?php
if (is_array($gpcomite->ic_tpGpOuComite->EditValue)) {
	$arwrk = $gpcomite->ic_tpGpOuComite->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($gpcomite->ic_tpGpOuComite->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
	<a class="btn ewShowAll" href="<?php echo $gpcomite_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $gpcomite_list->ShowPageHeader(); ?>
<?php
$gpcomite_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fgpcomitelist" id="fgpcomitelist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gpcomite">
<div id="gmp_gpcomite" class="ewGridMiddlePanel">
<?php if ($gpcomite_list->TotalRecs > 0) { ?>
<table id="tbl_gpcomitelist" class="ewTable ewTableSeparate">
<?php echo $gpcomite->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$gpcomite_list->RenderListOptions();

// Render list options (header, left)
$gpcomite_list->ListOptions->Render("header", "left");
?>
<?php if ($gpcomite->nu_gpComite->Visible) { // nu_gpComite ?>
	<?php if ($gpcomite->SortUrl($gpcomite->nu_gpComite) == "") { ?>
		<td><div id="elh_gpcomite_nu_gpComite" class="gpcomite_nu_gpComite"><div class="ewTableHeaderCaption"><?php echo $gpcomite->nu_gpComite->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpcomite->SortUrl($gpcomite->nu_gpComite) ?>',2);"><div id="elh_gpcomite_nu_gpComite" class="gpcomite_nu_gpComite">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpcomite->nu_gpComite->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpcomite->nu_gpComite->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpcomite->nu_gpComite->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpcomite->no_gpComite->Visible) { // no_gpComite ?>
	<?php if ($gpcomite->SortUrl($gpcomite->no_gpComite) == "") { ?>
		<td><div id="elh_gpcomite_no_gpComite" class="gpcomite_no_gpComite"><div class="ewTableHeaderCaption"><?php echo $gpcomite->no_gpComite->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpcomite->SortUrl($gpcomite->no_gpComite) ?>',2);"><div id="elh_gpcomite_no_gpComite" class="gpcomite_no_gpComite">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpcomite->no_gpComite->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpcomite->no_gpComite->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpcomite->no_gpComite->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpcomite->ic_tpGpOuComite->Visible) { // ic_tpGpOuComite ?>
	<?php if ($gpcomite->SortUrl($gpcomite->ic_tpGpOuComite) == "") { ?>
		<td><div id="elh_gpcomite_ic_tpGpOuComite" class="gpcomite_ic_tpGpOuComite"><div class="ewTableHeaderCaption"><?php echo $gpcomite->ic_tpGpOuComite->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpcomite->SortUrl($gpcomite->ic_tpGpOuComite) ?>',2);"><div id="elh_gpcomite_ic_tpGpOuComite" class="gpcomite_ic_tpGpOuComite">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpcomite->ic_tpGpOuComite->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpcomite->ic_tpGpOuComite->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpcomite->ic_tpGpOuComite->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($gpcomite->ic_natureza->Visible) { // ic_natureza ?>
	<?php if ($gpcomite->SortUrl($gpcomite->ic_natureza) == "") { ?>
		<td><div id="elh_gpcomite_ic_natureza" class="gpcomite_ic_natureza"><div class="ewTableHeaderCaption"><?php echo $gpcomite->ic_natureza->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $gpcomite->SortUrl($gpcomite->ic_natureza) ?>',2);"><div id="elh_gpcomite_ic_natureza" class="gpcomite_ic_natureza">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $gpcomite->ic_natureza->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($gpcomite->ic_natureza->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($gpcomite->ic_natureza->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$gpcomite_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($gpcomite->ExportAll && $gpcomite->Export <> "") {
	$gpcomite_list->StopRec = $gpcomite_list->TotalRecs;
} else {

	// Set the last record to display
	if ($gpcomite_list->TotalRecs > $gpcomite_list->StartRec + $gpcomite_list->DisplayRecs - 1)
		$gpcomite_list->StopRec = $gpcomite_list->StartRec + $gpcomite_list->DisplayRecs - 1;
	else
		$gpcomite_list->StopRec = $gpcomite_list->TotalRecs;
}
$gpcomite_list->RecCnt = $gpcomite_list->StartRec - 1;
if ($gpcomite_list->Recordset && !$gpcomite_list->Recordset->EOF) {
	$gpcomite_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $gpcomite_list->StartRec > 1)
		$gpcomite_list->Recordset->Move($gpcomite_list->StartRec - 1);
} elseif (!$gpcomite->AllowAddDeleteRow && $gpcomite_list->StopRec == 0) {
	$gpcomite_list->StopRec = $gpcomite->GridAddRowCount;
}

// Initialize aggregate
$gpcomite->RowType = EW_ROWTYPE_AGGREGATEINIT;
$gpcomite->ResetAttrs();
$gpcomite_list->RenderRow();
while ($gpcomite_list->RecCnt < $gpcomite_list->StopRec) {
	$gpcomite_list->RecCnt++;
	if (intval($gpcomite_list->RecCnt) >= intval($gpcomite_list->StartRec)) {
		$gpcomite_list->RowCnt++;

		// Set up key count
		$gpcomite_list->KeyCount = $gpcomite_list->RowIndex;

		// Init row class and style
		$gpcomite->ResetAttrs();
		$gpcomite->CssClass = "";
		if ($gpcomite->CurrentAction == "gridadd") {
		} else {
			$gpcomite_list->LoadRowValues($gpcomite_list->Recordset); // Load row values
		}
		$gpcomite->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$gpcomite->RowAttrs = array_merge($gpcomite->RowAttrs, array('data-rowindex'=>$gpcomite_list->RowCnt, 'id'=>'r' . $gpcomite_list->RowCnt . '_gpcomite', 'data-rowtype'=>$gpcomite->RowType));

		// Render row
		$gpcomite_list->RenderRow();

		// Render list options
		$gpcomite_list->RenderListOptions();
?>
	<tr<?php echo $gpcomite->RowAttributes() ?>>
<?php

// Render list options (body, left)
$gpcomite_list->ListOptions->Render("body", "left", $gpcomite_list->RowCnt);
?>
	<?php if ($gpcomite->nu_gpComite->Visible) { // nu_gpComite ?>
		<td<?php echo $gpcomite->nu_gpComite->CellAttributes() ?>>
<span<?php echo $gpcomite->nu_gpComite->ViewAttributes() ?>>
<?php echo $gpcomite->nu_gpComite->ListViewValue() ?></span>
<a id="<?php echo $gpcomite_list->PageObjName . "_row_" . $gpcomite_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gpcomite->no_gpComite->Visible) { // no_gpComite ?>
		<td<?php echo $gpcomite->no_gpComite->CellAttributes() ?>>
<span<?php echo $gpcomite->no_gpComite->ViewAttributes() ?>>
<?php echo $gpcomite->no_gpComite->ListViewValue() ?></span>
<a id="<?php echo $gpcomite_list->PageObjName . "_row_" . $gpcomite_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gpcomite->ic_tpGpOuComite->Visible) { // ic_tpGpOuComite ?>
		<td<?php echo $gpcomite->ic_tpGpOuComite->CellAttributes() ?>>
<span<?php echo $gpcomite->ic_tpGpOuComite->ViewAttributes() ?>>
<?php echo $gpcomite->ic_tpGpOuComite->ListViewValue() ?></span>
<a id="<?php echo $gpcomite_list->PageObjName . "_row_" . $gpcomite_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($gpcomite->ic_natureza->Visible) { // ic_natureza ?>
		<td<?php echo $gpcomite->ic_natureza->CellAttributes() ?>>
<span<?php echo $gpcomite->ic_natureza->ViewAttributes() ?>>
<?php echo $gpcomite->ic_natureza->ListViewValue() ?></span>
<a id="<?php echo $gpcomite_list->PageObjName . "_row_" . $gpcomite_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$gpcomite_list->ListOptions->Render("body", "right", $gpcomite_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($gpcomite->CurrentAction <> "gridadd")
		$gpcomite_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($gpcomite->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($gpcomite_list->Recordset)
	$gpcomite_list->Recordset->Close();
?>
<?php if ($gpcomite->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($gpcomite->CurrentAction <> "gridadd" && $gpcomite->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($gpcomite_list->Pager)) $gpcomite_list->Pager = new cNumericPager($gpcomite_list->StartRec, $gpcomite_list->DisplayRecs, $gpcomite_list->TotalRecs, $gpcomite_list->RecRange) ?>
<?php if ($gpcomite_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($gpcomite_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $gpcomite_list->PageUrl() ?>start=<?php echo $gpcomite_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($gpcomite_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $gpcomite_list->PageUrl() ?>start=<?php echo $gpcomite_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($gpcomite_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $gpcomite_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($gpcomite_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $gpcomite_list->PageUrl() ?>start=<?php echo $gpcomite_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($gpcomite_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $gpcomite_list->PageUrl() ?>start=<?php echo $gpcomite_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($gpcomite_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $gpcomite_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $gpcomite_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $gpcomite_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($gpcomite_list->SearchWhere == "0=101") { ?>
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
	foreach ($gpcomite_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($gpcomite->Export == "") { ?>
<script type="text/javascript">
fgpcomitelistsrch.Init();
fgpcomitelist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$gpcomite_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($gpcomite->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$gpcomite_list->Page_Terminate();
?>
