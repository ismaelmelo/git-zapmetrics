<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "rprospresumoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "prospectogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$rprospresumo_list = NULL; // Initialize page object first

class crprospresumo_list extends crprospresumo {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'rprospresumo';

	// Page object name
	var $PageObjName = 'rprospresumo_list';

	// Grid form hidden field names
	var $FormName = 'frprospresumolist';
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

		// Table object (rprospresumo)
		if (!isset($GLOBALS["rprospresumo"])) {
			$GLOBALS["rprospresumo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["rprospresumo"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "rprospresumoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "rprospresumodelete.php";
		$this->MultiUpdateUrl = "rprospresumoupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rprospresumo', TRUE);

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
		$this->vr_prioridade->Visible = !$this->IsAddOrEdit();
		$this->vr_impacto->Visible = !$this->IsAddOrEdit();
		$this->vr_alinhamento->Visible = !$this->IsAddOrEdit();
		$this->vr_abrangencia->Visible = !$this->IsAddOrEdit();
		$this->vr_urgencia->Visible = !$this->IsAddOrEdit();
		$this->vr_duracao->Visible = !$this->IsAddOrEdit();
		$this->vr_tmpFila->Visible = !$this->IsAddOrEdit();

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

			// Get basic search values
			$this->LoadBasicSearchValues();

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

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

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

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_prospecto, FALSE); // nu_prospecto
		$this->BuildSearchSql($sWhere, $this->no_prospecto, FALSE); // no_prospecto
		$this->BuildSearchSql($sWhere, $this->nu_categoriaProspecto, FALSE); // nu_categoriaProspecto
		$this->BuildSearchSql($sWhere, $this->vr_prioridade, FALSE); // vr_prioridade
		$this->BuildSearchSql($sWhere, $this->no_solPatr, FALSE); // no_solPatr
		$this->BuildSearchSql($sWhere, $this->ar_entidade, TRUE); // ar_entidade
		$this->BuildSearchSql($sWhere, $this->nu_area, FALSE); // nu_area
		$this->BuildSearchSql($sWhere, $this->ar_nivel, TRUE); // ar_nivel
		$this->BuildSearchSql($sWhere, $this->ds_sistemas, FALSE); // ds_sistemas
		$this->BuildSearchSql($sWhere, $this->ic_implicacaoLegal, FALSE); // ic_implicacaoLegal
		$this->BuildSearchSql($sWhere, $this->ic_risco, FALSE); // ic_risco
		$this->BuildSearchSql($sWhere, $this->vr_impacto, FALSE); // vr_impacto
		$this->BuildSearchSql($sWhere, $this->vr_alinhamento, FALSE); // vr_alinhamento
		$this->BuildSearchSql($sWhere, $this->vr_abrangencia, FALSE); // vr_abrangencia
		$this->BuildSearchSql($sWhere, $this->vr_urgencia, FALSE); // vr_urgencia
		$this->BuildSearchSql($sWhere, $this->vr_duracao, FALSE); // vr_duracao
		$this->BuildSearchSql($sWhere, $this->vr_tmpFila, FALSE); // vr_tmpFila
		$this->BuildSearchSql($sWhere, $this->ic_stProspecto, FALSE); // ic_stProspecto
		$this->BuildSearchSql($sWhere, $this->ic_ativo, FALSE); // ic_ativo

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_prospecto->AdvancedSearch->Save(); // nu_prospecto
			$this->no_prospecto->AdvancedSearch->Save(); // no_prospecto
			$this->nu_categoriaProspecto->AdvancedSearch->Save(); // nu_categoriaProspecto
			$this->vr_prioridade->AdvancedSearch->Save(); // vr_prioridade
			$this->no_solPatr->AdvancedSearch->Save(); // no_solPatr
			$this->ar_entidade->AdvancedSearch->Save(); // ar_entidade
			$this->nu_area->AdvancedSearch->Save(); // nu_area
			$this->ar_nivel->AdvancedSearch->Save(); // ar_nivel
			$this->ds_sistemas->AdvancedSearch->Save(); // ds_sistemas
			$this->ic_implicacaoLegal->AdvancedSearch->Save(); // ic_implicacaoLegal
			$this->ic_risco->AdvancedSearch->Save(); // ic_risco
			$this->vr_impacto->AdvancedSearch->Save(); // vr_impacto
			$this->vr_alinhamento->AdvancedSearch->Save(); // vr_alinhamento
			$this->vr_abrangencia->AdvancedSearch->Save(); // vr_abrangencia
			$this->vr_urgencia->AdvancedSearch->Save(); // vr_urgencia
			$this->vr_duracao->AdvancedSearch->Save(); // vr_duracao
			$this->vr_tmpFila->AdvancedSearch->Save(); // vr_tmpFila
			$this->ic_stProspecto->AdvancedSearch->Save(); // ic_stProspecto
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

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->no_prospecto, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->no_solPatr, $Keyword);
		if (is_numeric($Keyword)) $this->BuildBasicSearchSQL($sWhere, $this->nu_area, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->ar_nivel, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->ds_sistemas, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->nu_prospecto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_prospecto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_categoriaProspecto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_prioridade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_solPatr->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ar_entidade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_area->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ar_nivel->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_sistemas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_implicacaoLegal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_risco->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_impacto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_alinhamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_abrangencia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_urgencia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_duracao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_tmpFila->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_stProspecto->AdvancedSearch->IssetSession())
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

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->nu_prospecto->AdvancedSearch->UnsetSession();
		$this->no_prospecto->AdvancedSearch->UnsetSession();
		$this->nu_categoriaProspecto->AdvancedSearch->UnsetSession();
		$this->vr_prioridade->AdvancedSearch->UnsetSession();
		$this->no_solPatr->AdvancedSearch->UnsetSession();
		$this->ar_entidade->AdvancedSearch->UnsetSession();
		$this->nu_area->AdvancedSearch->UnsetSession();
		$this->ar_nivel->AdvancedSearch->UnsetSession();
		$this->ds_sistemas->AdvancedSearch->UnsetSession();
		$this->ic_implicacaoLegal->AdvancedSearch->UnsetSession();
		$this->ic_risco->AdvancedSearch->UnsetSession();
		$this->vr_impacto->AdvancedSearch->UnsetSession();
		$this->vr_alinhamento->AdvancedSearch->UnsetSession();
		$this->vr_abrangencia->AdvancedSearch->UnsetSession();
		$this->vr_urgencia->AdvancedSearch->UnsetSession();
		$this->vr_duracao->AdvancedSearch->UnsetSession();
		$this->vr_tmpFila->AdvancedSearch->UnsetSession();
		$this->ic_stProspecto->AdvancedSearch->UnsetSession();
		$this->ic_ativo->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->nu_prospecto->AdvancedSearch->Load();
		$this->no_prospecto->AdvancedSearch->Load();
		$this->nu_categoriaProspecto->AdvancedSearch->Load();
		$this->vr_prioridade->AdvancedSearch->Load();
		$this->no_solPatr->AdvancedSearch->Load();
		$this->ar_entidade->AdvancedSearch->Load();
		$this->nu_area->AdvancedSearch->Load();
		$this->ar_nivel->AdvancedSearch->Load();
		$this->ds_sistemas->AdvancedSearch->Load();
		$this->ic_implicacaoLegal->AdvancedSearch->Load();
		$this->ic_risco->AdvancedSearch->Load();
		$this->vr_impacto->AdvancedSearch->Load();
		$this->vr_alinhamento->AdvancedSearch->Load();
		$this->vr_abrangencia->AdvancedSearch->Load();
		$this->vr_urgencia->AdvancedSearch->Load();
		$this->vr_duracao->AdvancedSearch->Load();
		$this->vr_tmpFila->AdvancedSearch->Load();
		$this->ic_stProspecto->AdvancedSearch->Load();
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
			$this->UpdateSort($this->no_prospecto, $bCtrl); // no_prospecto
			$this->UpdateSort($this->nu_categoriaProspecto, $bCtrl); // nu_categoriaProspecto
			$this->UpdateSort($this->vr_prioridade, $bCtrl); // vr_prioridade
			$this->UpdateSort($this->ar_entidade, $bCtrl); // ar_entidade
			$this->UpdateSort($this->nu_area, $bCtrl); // nu_area
			$this->UpdateSort($this->ar_nivel, $bCtrl); // ar_nivel
			$this->UpdateSort($this->ds_sistemas, $bCtrl); // ds_sistemas
			$this->UpdateSort($this->ic_implicacaoLegal, $bCtrl); // ic_implicacaoLegal
			$this->UpdateSort($this->ic_risco, $bCtrl); // ic_risco
			$this->UpdateSort($this->vr_impacto, $bCtrl); // vr_impacto
			$this->UpdateSort($this->vr_alinhamento, $bCtrl); // vr_alinhamento
			$this->UpdateSort($this->vr_abrangencia, $bCtrl); // vr_abrangencia
			$this->UpdateSort($this->vr_urgencia, $bCtrl); // vr_urgencia
			$this->UpdateSort($this->vr_duracao, $bCtrl); // vr_duracao
			$this->UpdateSort($this->vr_tmpFila, $bCtrl); // vr_tmpFila
			$this->UpdateSort($this->ic_stProspecto, $bCtrl); // ic_stProspecto
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
				$this->vr_prioridade->setSort("DESC");
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
				$this->no_prospecto->setSort("");
				$this->nu_categoriaProspecto->setSort("");
				$this->vr_prioridade->setSort("");
				$this->ar_entidade->setSort("");
				$this->nu_area->setSort("");
				$this->ar_nivel->setSort("");
				$this->ds_sistemas->setSort("");
				$this->ic_implicacaoLegal->setSort("");
				$this->ic_risco->setSort("");
				$this->vr_impacto->setSort("");
				$this->vr_alinhamento->setSort("");
				$this->vr_abrangencia->setSort("");
				$this->vr_urgencia->setSort("");
				$this->vr_duracao->setSort("");
				$this->vr_tmpFila->setSort("");
				$this->ic_stProspecto->setSort("");
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

		// "detail_prospecto"
		$item = &$this->ListOptions->Add("detail_prospecto");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'prospecto') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["prospecto_grid"])) $GLOBALS["prospecto_grid"] = new cprospecto_grid;

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
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_prospecto"
		$oListOpt = &$this->ListOptions->Items["detail_prospecto"];
		if ($Security->AllowList(CurrentProjectID() . 'prospecto')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("prospecto", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("prospectolist.php?" . EW_TABLE_SHOW_MASTER . "=rprospresumo&nu_prospecto=" . strval($this->nu_prospecto->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.frprospresumolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// nu_prospecto

		$this->nu_prospecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_prospecto"]);
		if ($this->nu_prospecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_prospecto->AdvancedSearch->SearchOperator = @$_GET["z_nu_prospecto"];

		// no_prospecto
		$this->no_prospecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_prospecto"]);
		if ($this->no_prospecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_prospecto->AdvancedSearch->SearchOperator = @$_GET["z_no_prospecto"];

		// nu_categoriaProspecto
		$this->nu_categoriaProspecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_categoriaProspecto"]);
		if ($this->nu_categoriaProspecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_categoriaProspecto->AdvancedSearch->SearchOperator = @$_GET["z_nu_categoriaProspecto"];

		// vr_prioridade
		$this->vr_prioridade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_prioridade"]);
		if ($this->vr_prioridade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_prioridade->AdvancedSearch->SearchOperator = @$_GET["z_vr_prioridade"];

		// no_solPatr
		$this->no_solPatr->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_solPatr"]);
		if ($this->no_solPatr->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_solPatr->AdvancedSearch->SearchOperator = @$_GET["z_no_solPatr"];

		// ar_entidade
		$this->ar_entidade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ar_entidade"]);
		if ($this->ar_entidade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ar_entidade->AdvancedSearch->SearchOperator = @$_GET["z_ar_entidade"];
		if (is_array($this->ar_entidade->AdvancedSearch->SearchValue)) $this->ar_entidade->AdvancedSearch->SearchValue = implode(",", $this->ar_entidade->AdvancedSearch->SearchValue);
		if (is_array($this->ar_entidade->AdvancedSearch->SearchValue2)) $this->ar_entidade->AdvancedSearch->SearchValue2 = implode(",", $this->ar_entidade->AdvancedSearch->SearchValue2);

		// nu_area
		$this->nu_area->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_area"]);
		if ($this->nu_area->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_area->AdvancedSearch->SearchOperator = @$_GET["z_nu_area"];

		// ar_nivel
		$this->ar_nivel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ar_nivel"]);
		if ($this->ar_nivel->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ar_nivel->AdvancedSearch->SearchOperator = @$_GET["z_ar_nivel"];
		if (is_array($this->ar_nivel->AdvancedSearch->SearchValue)) $this->ar_nivel->AdvancedSearch->SearchValue = implode(",", $this->ar_nivel->AdvancedSearch->SearchValue);
		if (is_array($this->ar_nivel->AdvancedSearch->SearchValue2)) $this->ar_nivel->AdvancedSearch->SearchValue2 = implode(",", $this->ar_nivel->AdvancedSearch->SearchValue2);

		// ds_sistemas
		$this->ds_sistemas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_sistemas"]);
		if ($this->ds_sistemas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_sistemas->AdvancedSearch->SearchOperator = @$_GET["z_ds_sistemas"];

		// ic_implicacaoLegal
		$this->ic_implicacaoLegal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_implicacaoLegal"]);
		if ($this->ic_implicacaoLegal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_implicacaoLegal->AdvancedSearch->SearchOperator = @$_GET["z_ic_implicacaoLegal"];

		// ic_risco
		$this->ic_risco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_risco"]);
		if ($this->ic_risco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_risco->AdvancedSearch->SearchOperator = @$_GET["z_ic_risco"];

		// vr_impacto
		$this->vr_impacto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_impacto"]);
		if ($this->vr_impacto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_impacto->AdvancedSearch->SearchOperator = @$_GET["z_vr_impacto"];

		// vr_alinhamento
		$this->vr_alinhamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_alinhamento"]);
		if ($this->vr_alinhamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_alinhamento->AdvancedSearch->SearchOperator = @$_GET["z_vr_alinhamento"];

		// vr_abrangencia
		$this->vr_abrangencia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_abrangencia"]);
		if ($this->vr_abrangencia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_abrangencia->AdvancedSearch->SearchOperator = @$_GET["z_vr_abrangencia"];

		// vr_urgencia
		$this->vr_urgencia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_urgencia"]);
		if ($this->vr_urgencia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_urgencia->AdvancedSearch->SearchOperator = @$_GET["z_vr_urgencia"];

		// vr_duracao
		$this->vr_duracao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_duracao"]);
		if ($this->vr_duracao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_duracao->AdvancedSearch->SearchOperator = @$_GET["z_vr_duracao"];

		// vr_tmpFila
		$this->vr_tmpFila->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_tmpFila"]);
		if ($this->vr_tmpFila->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_tmpFila->AdvancedSearch->SearchOperator = @$_GET["z_vr_tmpFila"];

		// ic_stProspecto
		$this->ic_stProspecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_stProspecto"]);
		if ($this->ic_stProspecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_stProspecto->AdvancedSearch->SearchOperator = @$_GET["z_ic_stProspecto"];

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
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->no_prospecto->setDbValue($rs->fields('no_prospecto'));
		$this->nu_categoriaProspecto->setDbValue($rs->fields('nu_categoriaProspecto'));
		$this->vr_prioridade->setDbValue($rs->fields('vr_prioridade'));
		$this->no_solPatr->setDbValue($rs->fields('no_solPatr'));
		$this->ar_entidade->setDbValue($rs->fields('ar_entidade'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ar_nivel->setDbValue($rs->fields('ar_nivel'));
		$this->ds_sistemas->setDbValue($rs->fields('ds_sistemas'));
		$this->ic_implicacaoLegal->setDbValue($rs->fields('ic_implicacaoLegal'));
		$this->ic_risco->setDbValue($rs->fields('ic_risco'));
		$this->vr_impacto->setDbValue($rs->fields('vr_impacto'));
		$this->vr_alinhamento->setDbValue($rs->fields('vr_alinhamento'));
		$this->vr_abrangencia->setDbValue($rs->fields('vr_abrangencia'));
		$this->vr_urgencia->setDbValue($rs->fields('vr_urgencia'));
		$this->vr_duracao->setDbValue($rs->fields('vr_duracao'));
		$this->vr_tmpFila->setDbValue($rs->fields('vr_tmpFila'));
		$this->ic_stProspecto->setDbValue($rs->fields('ic_stProspecto'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->no_prospecto->DbValue = $row['no_prospecto'];
		$this->nu_categoriaProspecto->DbValue = $row['nu_categoriaProspecto'];
		$this->vr_prioridade->DbValue = $row['vr_prioridade'];
		$this->no_solPatr->DbValue = $row['no_solPatr'];
		$this->ar_entidade->DbValue = $row['ar_entidade'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->ar_nivel->DbValue = $row['ar_nivel'];
		$this->ds_sistemas->DbValue = $row['ds_sistemas'];
		$this->ic_implicacaoLegal->DbValue = $row['ic_implicacaoLegal'];
		$this->ic_risco->DbValue = $row['ic_risco'];
		$this->vr_impacto->DbValue = $row['vr_impacto'];
		$this->vr_alinhamento->DbValue = $row['vr_alinhamento'];
		$this->vr_abrangencia->DbValue = $row['vr_abrangencia'];
		$this->vr_urgencia->DbValue = $row['vr_urgencia'];
		$this->vr_duracao->DbValue = $row['vr_duracao'];
		$this->vr_tmpFila->DbValue = $row['vr_tmpFila'];
		$this->ic_stProspecto->DbValue = $row['ic_stProspecto'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

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
		if ($this->vr_prioridade->FormValue == $this->vr_prioridade->CurrentValue && is_numeric(ew_StrToFloat($this->vr_prioridade->CurrentValue)))
			$this->vr_prioridade->CurrentValue = ew_StrToFloat($this->vr_prioridade->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_impacto->FormValue == $this->vr_impacto->CurrentValue && is_numeric(ew_StrToFloat($this->vr_impacto->CurrentValue)))
			$this->vr_impacto->CurrentValue = ew_StrToFloat($this->vr_impacto->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_alinhamento->FormValue == $this->vr_alinhamento->CurrentValue && is_numeric(ew_StrToFloat($this->vr_alinhamento->CurrentValue)))
			$this->vr_alinhamento->CurrentValue = ew_StrToFloat($this->vr_alinhamento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_abrangencia->FormValue == $this->vr_abrangencia->CurrentValue && is_numeric(ew_StrToFloat($this->vr_abrangencia->CurrentValue)))
			$this->vr_abrangencia->CurrentValue = ew_StrToFloat($this->vr_abrangencia->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_urgencia->FormValue == $this->vr_urgencia->CurrentValue && is_numeric(ew_StrToFloat($this->vr_urgencia->CurrentValue)))
			$this->vr_urgencia->CurrentValue = ew_StrToFloat($this->vr_urgencia->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_duracao->FormValue == $this->vr_duracao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_duracao->CurrentValue)))
			$this->vr_duracao->CurrentValue = ew_StrToFloat($this->vr_duracao->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_tmpFila->FormValue == $this->vr_tmpFila->CurrentValue && is_numeric(ew_StrToFloat($this->vr_tmpFila->CurrentValue)))
			$this->vr_tmpFila->CurrentValue = ew_StrToFloat($this->vr_tmpFila->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_prospecto
		// no_prospecto
		// nu_categoriaProspecto
		// vr_prioridade
		// no_solPatr
		// ar_entidade
		// nu_area
		// ar_nivel
		// ds_sistemas
		// ic_implicacaoLegal
		// ic_risco
		// vr_impacto
		// vr_alinhamento
		// vr_abrangencia
		// vr_urgencia
		// vr_duracao
		// vr_tmpFila
		// ic_stProspecto
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_prospecto
			$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
			$this->nu_prospecto->ViewCustomAttributes = "";

			// no_prospecto
			$this->no_prospecto->ViewValue = $this->no_prospecto->CurrentValue;
			$this->no_prospecto->CssStyle = "font-weight: bold;";
			$this->no_prospecto->ViewCustomAttributes = "";

			// nu_categoriaProspecto
			if (strval($this->nu_categoriaProspecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_categoria]" . ew_SearchString("=", $this->nu_categoriaProspecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_categoria], [no_categoria] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catprospecto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_categoriaProspecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_categoria] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_categoriaProspecto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_categoriaProspecto->ViewValue = $this->nu_categoriaProspecto->CurrentValue;
				}
			} else {
				$this->nu_categoriaProspecto->ViewValue = NULL;
			}
			$this->nu_categoriaProspecto->ViewCustomAttributes = "";

			// vr_prioridade
			$this->vr_prioridade->ViewValue = $this->vr_prioridade->CurrentValue;
			$this->vr_prioridade->ViewValue = ew_FormatNumber($this->vr_prioridade->ViewValue, 0, -2, -2, -2);
			$this->vr_prioridade->ViewCustomAttributes = "";

			// no_solPatr
			$this->no_solPatr->ViewValue = $this->no_solPatr->CurrentValue;
			$this->no_solPatr->ViewCustomAttributes = "";

			// ar_entidade
			if (strval($this->ar_entidade->CurrentValue) <> "") {
				$arwrk = explode(",", $this->ar_entidade->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "[nu_organizacao]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
				}	
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->ar_entidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->ar_entidade->ViewValue = "";
					$ari = 0;
					while (!$rswrk->EOF) {
						$this->ar_entidade->ViewValue .= $rswrk->fields('DispFld');
						$rswrk->MoveNext();
						if (!$rswrk->EOF) $this->ar_entidade->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
						$ari++;
					}
					$rswrk->Close();
				} else {
					$this->ar_entidade->ViewValue = $this->ar_entidade->CurrentValue;
				}
			} else {
				$this->ar_entidade->ViewValue = NULL;
			}
			$this->ar_entidade->ViewCustomAttributes = "";

			// nu_area
			if (strval($this->nu_area->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_area, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_area->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_area->ViewValue = $this->nu_area->CurrentValue;
				}
			} else {
				$this->nu_area->ViewValue = NULL;
			}
			$this->nu_area->ViewCustomAttributes = "";

			// ar_nivel
			if (strval($this->ar_nivel->CurrentValue) <> "") {
				$this->ar_nivel->ViewValue = "";
				$arwrk = explode(",", strval($this->ar_nivel->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->ar_nivel->FldTagValue(1):
							$this->ar_nivel->ViewValue .= $this->ar_nivel->FldTagCaption(1) <> "" ? $this->ar_nivel->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						case $this->ar_nivel->FldTagValue(2):
							$this->ar_nivel->ViewValue .= $this->ar_nivel->FldTagCaption(2) <> "" ? $this->ar_nivel->FldTagCaption(2) : trim($arwrk[$ari]);
							break;
						default:
							$this->ar_nivel->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->ar_nivel->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->ar_nivel->ViewValue = NULL;
			}
			$this->ar_nivel->ViewCustomAttributes = "";

			// ds_sistemas
			$this->ds_sistemas->ViewValue = $this->ds_sistemas->CurrentValue;
			$this->ds_sistemas->ViewCustomAttributes = "";

			// ic_implicacaoLegal
			if (strval($this->ic_implicacaoLegal->CurrentValue) <> "") {
				switch ($this->ic_implicacaoLegal->CurrentValue) {
					case $this->ic_implicacaoLegal->FldTagValue(1):
						$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->FldTagCaption(1) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(1) : $this->ic_implicacaoLegal->CurrentValue;
						break;
					case $this->ic_implicacaoLegal->FldTagValue(2):
						$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->FldTagCaption(2) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(2) : $this->ic_implicacaoLegal->CurrentValue;
						break;
					default:
						$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->CurrentValue;
				}
			} else {
				$this->ic_implicacaoLegal->ViewValue = NULL;
			}
			$this->ic_implicacaoLegal->ViewCustomAttributes = "";

			// ic_risco
			if (strval($this->ic_risco->CurrentValue) <> "") {
				switch ($this->ic_risco->CurrentValue) {
					case $this->ic_risco->FldTagValue(1):
						$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(1) <> "" ? $this->ic_risco->FldTagCaption(1) : $this->ic_risco->CurrentValue;
						break;
					case $this->ic_risco->FldTagValue(2):
						$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(2) <> "" ? $this->ic_risco->FldTagCaption(2) : $this->ic_risco->CurrentValue;
						break;
					case $this->ic_risco->FldTagValue(3):
						$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(3) <> "" ? $this->ic_risco->FldTagCaption(3) : $this->ic_risco->CurrentValue;
						break;
					default:
						$this->ic_risco->ViewValue = $this->ic_risco->CurrentValue;
				}
			} else {
				$this->ic_risco->ViewValue = NULL;
			}
			$this->ic_risco->ViewCustomAttributes = "";

			// vr_impacto
			$this->vr_impacto->ViewValue = $this->vr_impacto->CurrentValue;
			$this->vr_impacto->ViewValue = ew_FormatNumber($this->vr_impacto->ViewValue, 0, -2, -2, -2);
			$this->vr_impacto->ViewCustomAttributes = "";

			// vr_alinhamento
			$this->vr_alinhamento->ViewValue = $this->vr_alinhamento->CurrentValue;
			$this->vr_alinhamento->ViewValue = ew_FormatNumber($this->vr_alinhamento->ViewValue, 0, -2, -2, -2);
			$this->vr_alinhamento->ViewCustomAttributes = "";

			// vr_abrangencia
			$this->vr_abrangencia->ViewValue = $this->vr_abrangencia->CurrentValue;
			$this->vr_abrangencia->ViewValue = ew_FormatNumber($this->vr_abrangencia->ViewValue, 0, -2, -2, -2);
			$this->vr_abrangencia->ViewCustomAttributes = "";

			// vr_urgencia
			$this->vr_urgencia->ViewValue = $this->vr_urgencia->CurrentValue;
			$this->vr_urgencia->ViewValue = ew_FormatNumber($this->vr_urgencia->ViewValue, 0, -2, -2, -2);
			$this->vr_urgencia->ViewCustomAttributes = "";

			// vr_duracao
			$this->vr_duracao->ViewValue = $this->vr_duracao->CurrentValue;
			$this->vr_duracao->ViewValue = ew_FormatNumber($this->vr_duracao->ViewValue, 0, -2, -2, -2);
			$this->vr_duracao->ViewCustomAttributes = "";

			// vr_tmpFila
			$this->vr_tmpFila->ViewValue = $this->vr_tmpFila->CurrentValue;
			$this->vr_tmpFila->ViewValue = ew_FormatNumber($this->vr_tmpFila->ViewValue, 0, -2, -2, -2);
			$this->vr_tmpFila->ViewCustomAttributes = "";

			// ic_stProspecto
			if (strval($this->ic_stProspecto->CurrentValue) <> "") {
				switch ($this->ic_stProspecto->CurrentValue) {
					case $this->ic_stProspecto->FldTagValue(1):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(1) <> "" ? $this->ic_stProspecto->FldTagCaption(1) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(2):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(2) <> "" ? $this->ic_stProspecto->FldTagCaption(2) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(3):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(3) <> "" ? $this->ic_stProspecto->FldTagCaption(3) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(4):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(4) <> "" ? $this->ic_stProspecto->FldTagCaption(4) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(5):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(5) <> "" ? $this->ic_stProspecto->FldTagCaption(5) : $this->ic_stProspecto->CurrentValue;
						break;
					default:
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->CurrentValue;
				}
			} else {
				$this->ic_stProspecto->ViewValue = NULL;
			}
			$this->ic_stProspecto->ViewCustomAttributes = "";

			// no_prospecto
			$this->no_prospecto->LinkCustomAttributes = "";
			if (!ew_Empty($this->nu_prospecto->CurrentValue)) {
				$this->no_prospecto->HrefValue = "prospectoview.php?showdetail=&nu_prospecto=" . $this->nu_prospecto->CurrentValue; // Add prefix/suffix
				$this->no_prospecto->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->no_prospecto->HrefValue = ew_ConvertFullUrl($this->no_prospecto->HrefValue);
			} else {
				$this->no_prospecto->HrefValue = "";
			}
			$this->no_prospecto->TooltipValue = "";

			// nu_categoriaProspecto
			$this->nu_categoriaProspecto->LinkCustomAttributes = "";
			$this->nu_categoriaProspecto->HrefValue = "";
			$this->nu_categoriaProspecto->TooltipValue = "";

			// vr_prioridade
			$this->vr_prioridade->LinkCustomAttributes = "";
			$this->vr_prioridade->HrefValue = "";
			$this->vr_prioridade->TooltipValue = "";

			// ar_entidade
			$this->ar_entidade->LinkCustomAttributes = "";
			$this->ar_entidade->HrefValue = "";
			$this->ar_entidade->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// ar_nivel
			$this->ar_nivel->LinkCustomAttributes = "";
			$this->ar_nivel->HrefValue = "";
			$this->ar_nivel->TooltipValue = "";

			// ds_sistemas
			$this->ds_sistemas->LinkCustomAttributes = "";
			$this->ds_sistemas->HrefValue = "";
			$this->ds_sistemas->TooltipValue = "";

			// ic_implicacaoLegal
			$this->ic_implicacaoLegal->LinkCustomAttributes = "";
			$this->ic_implicacaoLegal->HrefValue = "";
			$this->ic_implicacaoLegal->TooltipValue = "";

			// ic_risco
			$this->ic_risco->LinkCustomAttributes = "";
			$this->ic_risco->HrefValue = "";
			$this->ic_risco->TooltipValue = "";

			// vr_impacto
			$this->vr_impacto->LinkCustomAttributes = "";
			$this->vr_impacto->HrefValue = "";
			$this->vr_impacto->TooltipValue = "";

			// vr_alinhamento
			$this->vr_alinhamento->LinkCustomAttributes = "";
			$this->vr_alinhamento->HrefValue = "";
			$this->vr_alinhamento->TooltipValue = "";

			// vr_abrangencia
			$this->vr_abrangencia->LinkCustomAttributes = "";
			$this->vr_abrangencia->HrefValue = "";
			$this->vr_abrangencia->TooltipValue = "";

			// vr_urgencia
			$this->vr_urgencia->LinkCustomAttributes = "";
			$this->vr_urgencia->HrefValue = "";
			$this->vr_urgencia->TooltipValue = "";

			// vr_duracao
			$this->vr_duracao->LinkCustomAttributes = "";
			$this->vr_duracao->HrefValue = "";
			$this->vr_duracao->TooltipValue = "";

			// vr_tmpFila
			$this->vr_tmpFila->LinkCustomAttributes = "";
			$this->vr_tmpFila->HrefValue = "";
			$this->vr_tmpFila->TooltipValue = "";

			// ic_stProspecto
			$this->ic_stProspecto->LinkCustomAttributes = "";
			$this->ic_stProspecto->HrefValue = "";
			$this->ic_stProspecto->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// no_prospecto
			$this->no_prospecto->EditCustomAttributes = "";
			$this->no_prospecto->EditValue = ew_HtmlEncode($this->no_prospecto->AdvancedSearch->SearchValue);
			$this->no_prospecto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_prospecto->FldCaption()));

			// nu_categoriaProspecto
			$this->nu_categoriaProspecto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_categoria], [no_categoria] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[catprospecto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_categoriaProspecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_categoria] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_categoriaProspecto->EditValue = $arwrk;

			// vr_prioridade
			$this->vr_prioridade->EditCustomAttributes = "";
			$this->vr_prioridade->EditValue = ew_HtmlEncode($this->vr_prioridade->AdvancedSearch->SearchValue);
			$this->vr_prioridade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_prioridade->FldCaption()));

			// ar_entidade
			$this->ar_entidade->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_organizacao], [no_organizacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[organizacao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->ar_entidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->ar_entidade->EditValue = $arwrk;

			// nu_area
			$this->nu_area->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_area, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_area->EditValue = $arwrk;

			// ar_nivel
			$this->ar_nivel->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ar_nivel->FldTagValue(1), $this->ar_nivel->FldTagCaption(1) <> "" ? $this->ar_nivel->FldTagCaption(1) : $this->ar_nivel->FldTagValue(1));
			$arwrk[] = array($this->ar_nivel->FldTagValue(2), $this->ar_nivel->FldTagCaption(2) <> "" ? $this->ar_nivel->FldTagCaption(2) : $this->ar_nivel->FldTagValue(2));
			$this->ar_nivel->EditValue = $arwrk;

			// ds_sistemas
			$this->ds_sistemas->EditCustomAttributes = "";
			$this->ds_sistemas->EditValue = $this->ds_sistemas->AdvancedSearch->SearchValue;
			$this->ds_sistemas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_sistemas->FldCaption()));

			// ic_implicacaoLegal
			$this->ic_implicacaoLegal->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_implicacaoLegal->FldTagValue(1), $this->ic_implicacaoLegal->FldTagCaption(1) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(1) : $this->ic_implicacaoLegal->FldTagValue(1));
			$arwrk[] = array($this->ic_implicacaoLegal->FldTagValue(2), $this->ic_implicacaoLegal->FldTagCaption(2) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(2) : $this->ic_implicacaoLegal->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_implicacaoLegal->EditValue = $arwrk;

			// ic_risco
			$this->ic_risco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_risco->FldTagValue(1), $this->ic_risco->FldTagCaption(1) <> "" ? $this->ic_risco->FldTagCaption(1) : $this->ic_risco->FldTagValue(1));
			$arwrk[] = array($this->ic_risco->FldTagValue(2), $this->ic_risco->FldTagCaption(2) <> "" ? $this->ic_risco->FldTagCaption(2) : $this->ic_risco->FldTagValue(2));
			$arwrk[] = array($this->ic_risco->FldTagValue(3), $this->ic_risco->FldTagCaption(3) <> "" ? $this->ic_risco->FldTagCaption(3) : $this->ic_risco->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_risco->EditValue = $arwrk;

			// vr_impacto
			$this->vr_impacto->EditCustomAttributes = "";
			$this->vr_impacto->EditValue = ew_HtmlEncode($this->vr_impacto->AdvancedSearch->SearchValue);
			$this->vr_impacto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_impacto->FldCaption()));

			// vr_alinhamento
			$this->vr_alinhamento->EditCustomAttributes = "";
			$this->vr_alinhamento->EditValue = ew_HtmlEncode($this->vr_alinhamento->AdvancedSearch->SearchValue);
			$this->vr_alinhamento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_alinhamento->FldCaption()));

			// vr_abrangencia
			$this->vr_abrangencia->EditCustomAttributes = "";
			$this->vr_abrangencia->EditValue = ew_HtmlEncode($this->vr_abrangencia->AdvancedSearch->SearchValue);
			$this->vr_abrangencia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_abrangencia->FldCaption()));

			// vr_urgencia
			$this->vr_urgencia->EditCustomAttributes = "";
			$this->vr_urgencia->EditValue = ew_HtmlEncode($this->vr_urgencia->AdvancedSearch->SearchValue);
			$this->vr_urgencia->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_urgencia->FldCaption()));

			// vr_duracao
			$this->vr_duracao->EditCustomAttributes = "";
			$this->vr_duracao->EditValue = ew_HtmlEncode($this->vr_duracao->AdvancedSearch->SearchValue);
			$this->vr_duracao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_duracao->FldCaption()));

			// vr_tmpFila
			$this->vr_tmpFila->EditCustomAttributes = "";
			$this->vr_tmpFila->EditValue = ew_HtmlEncode($this->vr_tmpFila->AdvancedSearch->SearchValue);
			$this->vr_tmpFila->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_tmpFila->FldCaption()));

			// ic_stProspecto
			$this->ic_stProspecto->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(1), $this->ic_stProspecto->FldTagCaption(1) <> "" ? $this->ic_stProspecto->FldTagCaption(1) : $this->ic_stProspecto->FldTagValue(1));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(2), $this->ic_stProspecto->FldTagCaption(2) <> "" ? $this->ic_stProspecto->FldTagCaption(2) : $this->ic_stProspecto->FldTagValue(2));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(3), $this->ic_stProspecto->FldTagCaption(3) <> "" ? $this->ic_stProspecto->FldTagCaption(3) : $this->ic_stProspecto->FldTagValue(3));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(4), $this->ic_stProspecto->FldTagCaption(4) <> "" ? $this->ic_stProspecto->FldTagCaption(4) : $this->ic_stProspecto->FldTagValue(4));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(5), $this->ic_stProspecto->FldTagCaption(5) <> "" ? $this->ic_stProspecto->FldTagCaption(5) : $this->ic_stProspecto->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_stProspecto->EditValue = $arwrk;
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
		$this->nu_prospecto->AdvancedSearch->Load();
		$this->no_prospecto->AdvancedSearch->Load();
		$this->nu_categoriaProspecto->AdvancedSearch->Load();
		$this->vr_prioridade->AdvancedSearch->Load();
		$this->no_solPatr->AdvancedSearch->Load();
		$this->ar_entidade->AdvancedSearch->Load();
		$this->nu_area->AdvancedSearch->Load();
		$this->ar_nivel->AdvancedSearch->Load();
		$this->ds_sistemas->AdvancedSearch->Load();
		$this->ic_implicacaoLegal->AdvancedSearch->Load();
		$this->ic_risco->AdvancedSearch->Load();
		$this->vr_impacto->AdvancedSearch->Load();
		$this->vr_alinhamento->AdvancedSearch->Load();
		$this->vr_abrangencia->AdvancedSearch->Load();
		$this->vr_urgencia->AdvancedSearch->Load();
		$this->vr_duracao->AdvancedSearch->Load();
		$this->vr_tmpFila->AdvancedSearch->Load();
		$this->ic_stProspecto->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_rprospresumo\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_rprospresumo',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.frprospresumolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}
		$this->AddSearchQueryString($sQry, $this->nu_prospecto); // nu_prospecto
		$this->AddSearchQueryString($sQry, $this->no_prospecto); // no_prospecto
		$this->AddSearchQueryString($sQry, $this->nu_categoriaProspecto); // nu_categoriaProspecto
		$this->AddSearchQueryString($sQry, $this->vr_prioridade); // vr_prioridade
		$this->AddSearchQueryString($sQry, $this->no_solPatr); // no_solPatr
		$this->AddSearchQueryString($sQry, $this->ar_entidade); // ar_entidade
		$this->AddSearchQueryString($sQry, $this->nu_area); // nu_area
		$this->AddSearchQueryString($sQry, $this->ar_nivel); // ar_nivel
		$this->AddSearchQueryString($sQry, $this->ds_sistemas); // ds_sistemas
		$this->AddSearchQueryString($sQry, $this->ic_implicacaoLegal); // ic_implicacaoLegal
		$this->AddSearchQueryString($sQry, $this->ic_risco); // ic_risco
		$this->AddSearchQueryString($sQry, $this->vr_impacto); // vr_impacto
		$this->AddSearchQueryString($sQry, $this->vr_alinhamento); // vr_alinhamento
		$this->AddSearchQueryString($sQry, $this->vr_abrangencia); // vr_abrangencia
		$this->AddSearchQueryString($sQry, $this->vr_urgencia); // vr_urgencia
		$this->AddSearchQueryString($sQry, $this->vr_duracao); // vr_duracao
		$this->AddSearchQueryString($sQry, $this->vr_tmpFila); // vr_tmpFila
		$this->AddSearchQueryString($sQry, $this->ic_stProspecto); // ic_stProspecto
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
if (!isset($rprospresumo_list)) $rprospresumo_list = new crprospresumo_list();

// Page init
$rprospresumo_list->Page_Init();

// Page main
$rprospresumo_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$rprospresumo_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($rprospresumo->Export == "") { ?>
<script type="text/javascript">

// Page object
var rprospresumo_list = new ew_Page("rprospresumo_list");
rprospresumo_list.PageID = "list"; // Page ID
var EW_PAGE_ID = rprospresumo_list.PageID; // For backward compatibility

// Form object
var frprospresumolist = new ew_Form("frprospresumolist");
frprospresumolist.FormKeyCountName = '<?php echo $rprospresumo_list->FormKeyCountName ?>';

// Form_CustomValidate event
frprospresumolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frprospresumolist.ValidateRequired = true;
<?php } else { ?>
frprospresumolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frprospresumolist.Lists["x_nu_categoriaProspecto"] = {"LinkField":"x_nu_categoria","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_categoria","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprospresumolist.Lists["x_ar_entidade[]"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprospresumolist.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var frprospresumolistsrch = new ew_Form("frprospresumolistsrch");

// Validate function for search
frprospresumolistsrch.Validate = function(fobj) {
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
frprospresumolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frprospresumolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
frprospresumolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
frprospresumolistsrch.Lists["x_nu_categoriaProspecto"] = {"LinkField":"x_nu_categoria","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_categoria","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprospresumolistsrch.Lists["x_ar_entidade[]"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprospresumolistsrch.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (frprospresumolistsrch) frprospresumolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($rprospresumo->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($rprospresumo_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $rprospresumo_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$rprospresumo_list->TotalRecs = $rprospresumo->SelectRecordCount();
	} else {
		if ($rprospresumo_list->Recordset = $rprospresumo_list->LoadRecordset())
			$rprospresumo_list->TotalRecs = $rprospresumo_list->Recordset->RecordCount();
	}
	$rprospresumo_list->StartRec = 1;
	if ($rprospresumo_list->DisplayRecs <= 0 || ($rprospresumo->Export <> "" && $rprospresumo->ExportAll)) // Display all records
		$rprospresumo_list->DisplayRecs = $rprospresumo_list->TotalRecs;
	if (!($rprospresumo->Export <> "" && $rprospresumo->ExportAll))
		$rprospresumo_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$rprospresumo_list->Recordset = $rprospresumo_list->LoadRecordset($rprospresumo_list->StartRec-1, $rprospresumo_list->DisplayRecs);
$rprospresumo_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($rprospresumo->Export == "" && $rprospresumo->CurrentAction == "") { ?>
<form name="frprospresumolistsrch" id="frprospresumolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="frprospresumolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#frprospresumolistsrch_SearchGroup" href="#frprospresumolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="frprospresumolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="frprospresumolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="rprospresumo">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$rprospresumo_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$rprospresumo->RowType = EW_ROWTYPE_SEARCH;

// Render row
$rprospresumo->ResetAttrs();
$rprospresumo_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($rprospresumo->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
	<span id="xsc_nu_categoriaProspecto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rprospresumo->nu_categoriaProspecto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_categoriaProspecto" id="z_nu_categoriaProspecto" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_categoriaProspecto" id="x_nu_categoriaProspecto" name="x_nu_categoriaProspecto"<?php echo $rprospresumo->nu_categoriaProspecto->EditAttributes() ?>>
<?php
if (is_array($rprospresumo->nu_categoriaProspecto->EditValue)) {
	$arwrk = $rprospresumo->nu_categoriaProspecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rprospresumo->nu_categoriaProspecto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
frprospresumolistsrch.Lists["x_nu_categoriaProspecto"].Options = <?php echo (is_array($rprospresumo->nu_categoriaProspecto->EditValue)) ? ew_ArrayToJson($rprospresumo->nu_categoriaProspecto->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($rprospresumo->ar_entidade->Visible) { // ar_entidade ?>
	<span id="xsc_ar_entidade" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rprospresumo->ar_entidade->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ar_entidade" id="z_ar_entidade" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ar_entidade" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_ar_entidade[]" id="x_ar_entidade[]" value="{value}"<?php echo $rprospresumo->ar_entidade->EditAttributes() ?>></div>
<div id="dsl_x_ar_entidade" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $rprospresumo->ar_entidade->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($rprospresumo->ar_entidade->AdvancedSearch->SearchValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_ar_entidade" name="x_ar_entidade[]" id="x_ar_entidade_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $rprospresumo->ar_entidade->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
<script type="text/javascript">
frprospresumolistsrch.Lists["x_ar_entidade[]"].Options = <?php echo (is_array($rprospresumo->ar_entidade->EditValue)) ? ew_ArrayToJson($rprospresumo->ar_entidade->EditValue, 0) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($rprospresumo->nu_area->Visible) { // nu_area ?>
	<span id="xsc_nu_area" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rprospresumo->nu_area->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_area" id="z_nu_area" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_area" id="x_nu_area" name="x_nu_area"<?php echo $rprospresumo->nu_area->EditAttributes() ?>>
<?php
if (is_array($rprospresumo->nu_area->EditValue)) {
	$arwrk = $rprospresumo->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rprospresumo->nu_area->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
frprospresumolistsrch.Lists["x_nu_area"].Options = <?php echo (is_array($rprospresumo->nu_area->EditValue)) ? ew_ArrayToJson($rprospresumo->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($rprospresumo->ar_nivel->Visible) { // ar_nivel ?>
	<span id="xsc_ar_nivel" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rprospresumo->ar_nivel->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ar_nivel" id="z_ar_nivel" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ar_nivel" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x_ar_nivel[]" id="x_ar_nivel[]" value="{value}"<?php echo $rprospresumo->ar_nivel->EditAttributes() ?>></div>
<div id="dsl_x_ar_nivel" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $rprospresumo->ar_nivel->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($rprospresumo->ar_nivel->AdvancedSearch->SearchValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="checkbox"><input type="checkbox" data-field="x_ar_nivel" name="x_ar_nivel[]" id="x_ar_nivel_<?php echo $rowcntwrk ?>[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $rprospresumo->ar_nivel->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
<?php if ($rprospresumo->ic_implicacaoLegal->Visible) { // ic_implicacaoLegal ?>
	<span id="xsc_ic_implicacaoLegal" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rprospresumo->ic_implicacaoLegal->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_implicacaoLegal" id="z_ic_implicacaoLegal" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_implicacaoLegal" id="x_ic_implicacaoLegal" name="x_ic_implicacaoLegal"<?php echo $rprospresumo->ic_implicacaoLegal->EditAttributes() ?>>
<?php
if (is_array($rprospresumo->ic_implicacaoLegal->EditValue)) {
	$arwrk = $rprospresumo->ic_implicacaoLegal->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rprospresumo->ic_implicacaoLegal->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if ($rprospresumo->ic_risco->Visible) { // ic_risco ?>
	<span id="xsc_ic_risco" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rprospresumo->ic_risco->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_risco" id="z_ic_risco" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_risco" id="x_ic_risco" name="x_ic_risco"<?php echo $rprospresumo->ic_risco->EditAttributes() ?>>
<?php
if (is_array($rprospresumo->ic_risco->EditValue)) {
	$arwrk = $rprospresumo->ic_risco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rprospresumo->ic_risco->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if ($rprospresumo->ic_stProspecto->Visible) { // ic_stProspecto ?>
	<span id="xsc_ic_stProspecto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $rprospresumo->ic_stProspecto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_stProspecto" id="z_ic_stProspecto" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_stProspecto" id="x_ic_stProspecto" name="x_ic_stProspecto"<?php echo $rprospresumo->ic_stProspecto->EditAttributes() ?>>
<?php
if (is_array($rprospresumo->ic_stProspecto->EditValue)) {
	$arwrk = $rprospresumo->ic_stProspecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($rprospresumo->ic_stProspecto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
	<div class="input-append">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="input-large" value="<?php echo ew_HtmlEncode($rprospresumo_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo $Language->Phrase("Search") ?>">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $rprospresumo_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
</div>
<div id="xsr_4" class="ewRow">
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($rprospresumo_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("ExactPhrase") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($rprospresumo_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AllWord") ?></label>
	<label class="inline radio ewRadio" style="white-space: nowrap;"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($rprospresumo_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?>><?php echo $Language->Phrase("AnyWord") ?></label>
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
<?php $rprospresumo_list->ShowPageHeader(); ?>
<?php
$rprospresumo_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="frprospresumolist" id="frprospresumolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="rprospresumo">
<div id="gmp_rprospresumo" class="ewGridMiddlePanel">
<?php if ($rprospresumo_list->TotalRecs > 0) { ?>
<table id="tbl_rprospresumolist" class="ewTable ewTableSeparate">
<?php echo $rprospresumo->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$rprospresumo_list->RenderListOptions();

// Render list options (header, left)
$rprospresumo_list->ListOptions->Render("header", "left");
?>
<?php if ($rprospresumo->no_prospecto->Visible) { // no_prospecto ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->no_prospecto) == "") { ?>
		<td><div id="elh_rprospresumo_no_prospecto" class="rprospresumo_no_prospecto"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->no_prospecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->no_prospecto) ?>',2);"><div id="elh_rprospresumo_no_prospecto" class="rprospresumo_no_prospecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->no_prospecto->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->no_prospecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->no_prospecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->nu_categoriaProspecto) == "") { ?>
		<td><div id="elh_rprospresumo_nu_categoriaProspecto" class="rprospresumo_nu_categoriaProspecto"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->nu_categoriaProspecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->nu_categoriaProspecto) ?>',2);"><div id="elh_rprospresumo_nu_categoriaProspecto" class="rprospresumo_nu_categoriaProspecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->nu_categoriaProspecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->nu_categoriaProspecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->nu_categoriaProspecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->vr_prioridade->Visible) { // vr_prioridade ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->vr_prioridade) == "") { ?>
		<td><div id="elh_rprospresumo_vr_prioridade" class="rprospresumo_vr_prioridade"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_prioridade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->vr_prioridade) ?>',2);"><div id="elh_rprospresumo_vr_prioridade" class="rprospresumo_vr_prioridade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_prioridade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->vr_prioridade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->vr_prioridade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->ar_entidade->Visible) { // ar_entidade ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->ar_entidade) == "") { ?>
		<td><div id="elh_rprospresumo_ar_entidade" class="rprospresumo_ar_entidade"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->ar_entidade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->ar_entidade) ?>',2);"><div id="elh_rprospresumo_ar_entidade" class="rprospresumo_ar_entidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->ar_entidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->ar_entidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->ar_entidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->nu_area->Visible) { // nu_area ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->nu_area) == "") { ?>
		<td><div id="elh_rprospresumo_nu_area" class="rprospresumo_nu_area"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->nu_area->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->nu_area) ?>',2);"><div id="elh_rprospresumo_nu_area" class="rprospresumo_nu_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->nu_area->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->nu_area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->nu_area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->ar_nivel->Visible) { // ar_nivel ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->ar_nivel) == "") { ?>
		<td><div id="elh_rprospresumo_ar_nivel" class="rprospresumo_ar_nivel"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->ar_nivel->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->ar_nivel) ?>',2);"><div id="elh_rprospresumo_ar_nivel" class="rprospresumo_ar_nivel">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->ar_nivel->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->ar_nivel->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->ar_nivel->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->ds_sistemas->Visible) { // ds_sistemas ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->ds_sistemas) == "") { ?>
		<td><div id="elh_rprospresumo_ds_sistemas" class="rprospresumo_ds_sistemas"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->ds_sistemas->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->ds_sistemas) ?>',2);"><div id="elh_rprospresumo_ds_sistemas" class="rprospresumo_ds_sistemas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->ds_sistemas->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->ds_sistemas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->ds_sistemas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->ic_implicacaoLegal->Visible) { // ic_implicacaoLegal ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->ic_implicacaoLegal) == "") { ?>
		<td><div id="elh_rprospresumo_ic_implicacaoLegal" class="rprospresumo_ic_implicacaoLegal"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->ic_implicacaoLegal->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->ic_implicacaoLegal) ?>',2);"><div id="elh_rprospresumo_ic_implicacaoLegal" class="rprospresumo_ic_implicacaoLegal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->ic_implicacaoLegal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->ic_implicacaoLegal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->ic_implicacaoLegal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->ic_risco->Visible) { // ic_risco ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->ic_risco) == "") { ?>
		<td><div id="elh_rprospresumo_ic_risco" class="rprospresumo_ic_risco"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->ic_risco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->ic_risco) ?>',2);"><div id="elh_rprospresumo_ic_risco" class="rprospresumo_ic_risco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->ic_risco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->ic_risco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->ic_risco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->vr_impacto->Visible) { // vr_impacto ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->vr_impacto) == "") { ?>
		<td><div id="elh_rprospresumo_vr_impacto" class="rprospresumo_vr_impacto"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_impacto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->vr_impacto) ?>',2);"><div id="elh_rprospresumo_vr_impacto" class="rprospresumo_vr_impacto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_impacto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->vr_impacto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->vr_impacto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->vr_alinhamento->Visible) { // vr_alinhamento ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->vr_alinhamento) == "") { ?>
		<td><div id="elh_rprospresumo_vr_alinhamento" class="rprospresumo_vr_alinhamento"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_alinhamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->vr_alinhamento) ?>',2);"><div id="elh_rprospresumo_vr_alinhamento" class="rprospresumo_vr_alinhamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_alinhamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->vr_alinhamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->vr_alinhamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->vr_abrangencia->Visible) { // vr_abrangencia ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->vr_abrangencia) == "") { ?>
		<td><div id="elh_rprospresumo_vr_abrangencia" class="rprospresumo_vr_abrangencia"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_abrangencia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->vr_abrangencia) ?>',2);"><div id="elh_rprospresumo_vr_abrangencia" class="rprospresumo_vr_abrangencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_abrangencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->vr_abrangencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->vr_abrangencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->vr_urgencia->Visible) { // vr_urgencia ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->vr_urgencia) == "") { ?>
		<td><div id="elh_rprospresumo_vr_urgencia" class="rprospresumo_vr_urgencia"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_urgencia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->vr_urgencia) ?>',2);"><div id="elh_rprospresumo_vr_urgencia" class="rprospresumo_vr_urgencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_urgencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->vr_urgencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->vr_urgencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->vr_duracao->Visible) { // vr_duracao ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->vr_duracao) == "") { ?>
		<td><div id="elh_rprospresumo_vr_duracao" class="rprospresumo_vr_duracao"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_duracao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->vr_duracao) ?>',2);"><div id="elh_rprospresumo_vr_duracao" class="rprospresumo_vr_duracao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_duracao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->vr_duracao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->vr_duracao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->vr_tmpFila->Visible) { // vr_tmpFila ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->vr_tmpFila) == "") { ?>
		<td><div id="elh_rprospresumo_vr_tmpFila" class="rprospresumo_vr_tmpFila"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_tmpFila->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->vr_tmpFila) ?>',2);"><div id="elh_rprospresumo_vr_tmpFila" class="rprospresumo_vr_tmpFila">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->vr_tmpFila->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->vr_tmpFila->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->vr_tmpFila->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprospresumo->ic_stProspecto->Visible) { // ic_stProspecto ?>
	<?php if ($rprospresumo->SortUrl($rprospresumo->ic_stProspecto) == "") { ?>
		<td><div id="elh_rprospresumo_ic_stProspecto" class="rprospresumo_ic_stProspecto"><div class="ewTableHeaderCaption"><?php echo $rprospresumo->ic_stProspecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprospresumo->SortUrl($rprospresumo->ic_stProspecto) ?>',2);"><div id="elh_rprospresumo_ic_stProspecto" class="rprospresumo_ic_stProspecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprospresumo->ic_stProspecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprospresumo->ic_stProspecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprospresumo->ic_stProspecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$rprospresumo_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($rprospresumo->ExportAll && $rprospresumo->Export <> "") {
	$rprospresumo_list->StopRec = $rprospresumo_list->TotalRecs;
} else {

	// Set the last record to display
	if ($rprospresumo_list->TotalRecs > $rprospresumo_list->StartRec + $rprospresumo_list->DisplayRecs - 1)
		$rprospresumo_list->StopRec = $rprospresumo_list->StartRec + $rprospresumo_list->DisplayRecs - 1;
	else
		$rprospresumo_list->StopRec = $rprospresumo_list->TotalRecs;
}
$rprospresumo_list->RecCnt = $rprospresumo_list->StartRec - 1;
if ($rprospresumo_list->Recordset && !$rprospresumo_list->Recordset->EOF) {
	$rprospresumo_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $rprospresumo_list->StartRec > 1)
		$rprospresumo_list->Recordset->Move($rprospresumo_list->StartRec - 1);
} elseif (!$rprospresumo->AllowAddDeleteRow && $rprospresumo_list->StopRec == 0) {
	$rprospresumo_list->StopRec = $rprospresumo->GridAddRowCount;
}

// Initialize aggregate
$rprospresumo->RowType = EW_ROWTYPE_AGGREGATEINIT;
$rprospresumo->ResetAttrs();
$rprospresumo_list->RenderRow();
while ($rprospresumo_list->RecCnt < $rprospresumo_list->StopRec) {
	$rprospresumo_list->RecCnt++;
	if (intval($rprospresumo_list->RecCnt) >= intval($rprospresumo_list->StartRec)) {
		$rprospresumo_list->RowCnt++;

		// Set up key count
		$rprospresumo_list->KeyCount = $rprospresumo_list->RowIndex;

		// Init row class and style
		$rprospresumo->ResetAttrs();
		$rprospresumo->CssClass = "";
		if ($rprospresumo->CurrentAction == "gridadd") {
		} else {
			$rprospresumo_list->LoadRowValues($rprospresumo_list->Recordset); // Load row values
		}
		$rprospresumo->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$rprospresumo->RowAttrs = array_merge($rprospresumo->RowAttrs, array('data-rowindex'=>$rprospresumo_list->RowCnt, 'id'=>'r' . $rprospresumo_list->RowCnt . '_rprospresumo', 'data-rowtype'=>$rprospresumo->RowType));

		// Render row
		$rprospresumo_list->RenderRow();

		// Render list options
		$rprospresumo_list->RenderListOptions();
?>
	<tr<?php echo $rprospresumo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$rprospresumo_list->ListOptions->Render("body", "left", $rprospresumo_list->RowCnt);
?>
	<?php if ($rprospresumo->no_prospecto->Visible) { // no_prospecto ?>
		<td<?php echo $rprospresumo->no_prospecto->CellAttributes() ?>>
<span<?php echo $rprospresumo->no_prospecto->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($rprospresumo->no_prospecto->ListViewValue()) && $rprospresumo->no_prospecto->LinkAttributes() <> "") { ?>
<a<?php echo $rprospresumo->no_prospecto->LinkAttributes() ?>><?php echo $rprospresumo->no_prospecto->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $rprospresumo->no_prospecto->ListViewValue() ?>
<?php } ?>
</span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
		<td<?php echo $rprospresumo->nu_categoriaProspecto->CellAttributes() ?>>
<span<?php echo $rprospresumo->nu_categoriaProspecto->ViewAttributes() ?>>
<?php echo $rprospresumo->nu_categoriaProspecto->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->vr_prioridade->Visible) { // vr_prioridade ?>
		<td<?php echo $rprospresumo->vr_prioridade->CellAttributes() ?>>
<span<?php echo $rprospresumo->vr_prioridade->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_prioridade->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->ar_entidade->Visible) { // ar_entidade ?>
		<td<?php echo $rprospresumo->ar_entidade->CellAttributes() ?>>
<span<?php echo $rprospresumo->ar_entidade->ViewAttributes() ?>>
<?php echo $rprospresumo->ar_entidade->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->nu_area->Visible) { // nu_area ?>
		<td<?php echo $rprospresumo->nu_area->CellAttributes() ?>>
<span<?php echo $rprospresumo->nu_area->ViewAttributes() ?>>
<?php echo $rprospresumo->nu_area->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->ar_nivel->Visible) { // ar_nivel ?>
		<td<?php echo $rprospresumo->ar_nivel->CellAttributes() ?>>
<span<?php echo $rprospresumo->ar_nivel->ViewAttributes() ?>>
<?php echo $rprospresumo->ar_nivel->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->ds_sistemas->Visible) { // ds_sistemas ?>
		<td<?php echo $rprospresumo->ds_sistemas->CellAttributes() ?>>
<span<?php echo $rprospresumo->ds_sistemas->ViewAttributes() ?>>
<?php echo $rprospresumo->ds_sistemas->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->ic_implicacaoLegal->Visible) { // ic_implicacaoLegal ?>
		<td<?php echo $rprospresumo->ic_implicacaoLegal->CellAttributes() ?>>
<span<?php echo $rprospresumo->ic_implicacaoLegal->ViewAttributes() ?>>
<?php echo $rprospresumo->ic_implicacaoLegal->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->ic_risco->Visible) { // ic_risco ?>
		<td<?php echo $rprospresumo->ic_risco->CellAttributes() ?>>
<span<?php echo $rprospresumo->ic_risco->ViewAttributes() ?>>
<?php echo $rprospresumo->ic_risco->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->vr_impacto->Visible) { // vr_impacto ?>
		<td<?php echo $rprospresumo->vr_impacto->CellAttributes() ?>>
<span<?php echo $rprospresumo->vr_impacto->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_impacto->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->vr_alinhamento->Visible) { // vr_alinhamento ?>
		<td<?php echo $rprospresumo->vr_alinhamento->CellAttributes() ?>>
<span<?php echo $rprospresumo->vr_alinhamento->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_alinhamento->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->vr_abrangencia->Visible) { // vr_abrangencia ?>
		<td<?php echo $rprospresumo->vr_abrangencia->CellAttributes() ?>>
<span<?php echo $rprospresumo->vr_abrangencia->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_abrangencia->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->vr_urgencia->Visible) { // vr_urgencia ?>
		<td<?php echo $rprospresumo->vr_urgencia->CellAttributes() ?>>
<span<?php echo $rprospresumo->vr_urgencia->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_urgencia->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->vr_duracao->Visible) { // vr_duracao ?>
		<td<?php echo $rprospresumo->vr_duracao->CellAttributes() ?>>
<span<?php echo $rprospresumo->vr_duracao->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_duracao->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->vr_tmpFila->Visible) { // vr_tmpFila ?>
		<td<?php echo $rprospresumo->vr_tmpFila->CellAttributes() ?>>
<span<?php echo $rprospresumo->vr_tmpFila->ViewAttributes() ?>>
<?php echo $rprospresumo->vr_tmpFila->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprospresumo->ic_stProspecto->Visible) { // ic_stProspecto ?>
		<td<?php echo $rprospresumo->ic_stProspecto->CellAttributes() ?>>
<span<?php echo $rprospresumo->ic_stProspecto->ViewAttributes() ?>>
<?php echo $rprospresumo->ic_stProspecto->ListViewValue() ?></span>
<a id="<?php echo $rprospresumo_list->PageObjName . "_row_" . $rprospresumo_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$rprospresumo_list->ListOptions->Render("body", "right", $rprospresumo_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($rprospresumo->CurrentAction <> "gridadd")
		$rprospresumo_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($rprospresumo->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($rprospresumo_list->Recordset)
	$rprospresumo_list->Recordset->Close();
?>
<?php if ($rprospresumo->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($rprospresumo->CurrentAction <> "gridadd" && $rprospresumo->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($rprospresumo_list->Pager)) $rprospresumo_list->Pager = new cNumericPager($rprospresumo_list->StartRec, $rprospresumo_list->DisplayRecs, $rprospresumo_list->TotalRecs, $rprospresumo_list->RecRange) ?>
<?php if ($rprospresumo_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($rprospresumo_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $rprospresumo_list->PageUrl() ?>start=<?php echo $rprospresumo_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($rprospresumo_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $rprospresumo_list->PageUrl() ?>start=<?php echo $rprospresumo_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($rprospresumo_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $rprospresumo_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($rprospresumo_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $rprospresumo_list->PageUrl() ?>start=<?php echo $rprospresumo_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($rprospresumo_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $rprospresumo_list->PageUrl() ?>start=<?php echo $rprospresumo_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($rprospresumo_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $rprospresumo_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $rprospresumo_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $rprospresumo_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($rprospresumo_list->SearchWhere == "0=101") { ?>
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
	foreach ($rprospresumo_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($rprospresumo->Export == "") { ?>
<script type="text/javascript">
frprospresumolistsrch.Init();
frprospresumolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$rprospresumo_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($rprospresumo->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$rprospresumo_list->Page_Terminate();
?>
