<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "solicitacao_ocorrenciagridcls.php" ?>
<?php include_once "contagempfgridcls.php" ?>
<?php include_once "estimativagridcls.php" ?>
<?php include_once "laudogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$solicitacaoMetricas_list = NULL; // Initialize page object first

class csolicitacaoMetricas_list extends csolicitacaoMetricas {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'solicitacaoMetricas';

	// Page object name
	var $PageObjName = 'solicitacaoMetricas_list';

	// Grid form hidden field names
	var $FormName = 'fsolicitacaoMetricaslist';
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

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS["solicitacaoMetricas"])) {
			$GLOBALS["solicitacaoMetricas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["solicitacaoMetricas"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "solicitacaometricasadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "solicitacaometricasdelete.php";
		$this->MultiUpdateUrl = "solicitacaometricasupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'solicitacaoMetricas', TRUE);

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
		$this->nu_solMetricas->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->nu_usuarioAlterou->Visible = !$this->IsAddOrEdit();
		$this->dt_stSolicitacao->Visible = !$this->IsAddOrEdit();

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
			$this->nu_solMetricas->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_solMetricas->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_solMetricas, FALSE); // nu_solMetricas
		$this->BuildSearchSql($sWhere, $this->nu_tpSolicitacao, FALSE); // nu_tpSolicitacao
		$this->BuildSearchSql($sWhere, $this->nu_projeto, FALSE); // nu_projeto
		$this->BuildSearchSql($sWhere, $this->no_atividadeMaeRedmine, FALSE); // no_atividadeMaeRedmine
		$this->BuildSearchSql($sWhere, $this->ds_observacoes, FALSE); // ds_observacoes
		$this->BuildSearchSql($sWhere, $this->ds_documentacaoAux, FALSE); // ds_documentacaoAux
		$this->BuildSearchSql($sWhere, $this->ds_imapactoDb, FALSE); // ds_imapactoDb
		$this->BuildSearchSql($sWhere, $this->ic_stSolicitacao, FALSE); // ic_stSolicitacao
		$this->BuildSearchSql($sWhere, $this->nu_usuarioAlterou, FALSE); // nu_usuarioAlterou
		$this->BuildSearchSql($sWhere, $this->dh_alteracao, FALSE); // dh_alteracao
		$this->BuildSearchSql($sWhere, $this->nu_usuarioIncluiu, FALSE); // nu_usuarioIncluiu
		$this->BuildSearchSql($sWhere, $this->dh_inclusao, FALSE); // dh_inclusao
		$this->BuildSearchSql($sWhere, $this->dt_stSolicitacao, FALSE); // dt_stSolicitacao
		$this->BuildSearchSql($sWhere, $this->qt_pfTotal, FALSE); // qt_pfTotal
		$this->BuildSearchSql($sWhere, $this->vr_pfContForn, FALSE); // vr_pfContForn
		$this->BuildSearchSql($sWhere, $this->nu_tpMetrica, FALSE); // nu_tpMetrica
		$this->BuildSearchSql($sWhere, $this->ds_observacoesContForn, FALSE); // ds_observacoesContForn
		$this->BuildSearchSql($sWhere, $this->im_anexosContForn, FALSE); // im_anexosContForn
		$this->BuildSearchSql($sWhere, $this->nu_contagemAnt, FALSE); // nu_contagemAnt
		$this->BuildSearchSql($sWhere, $this->ds_observaocoesContAnt, FALSE); // ds_observaocoesContAnt
		$this->BuildSearchSql($sWhere, $this->im_anexosContAnt, FALSE); // im_anexosContAnt

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_solMetricas->AdvancedSearch->Save(); // nu_solMetricas
			$this->nu_tpSolicitacao->AdvancedSearch->Save(); // nu_tpSolicitacao
			$this->nu_projeto->AdvancedSearch->Save(); // nu_projeto
			$this->no_atividadeMaeRedmine->AdvancedSearch->Save(); // no_atividadeMaeRedmine
			$this->ds_observacoes->AdvancedSearch->Save(); // ds_observacoes
			$this->ds_documentacaoAux->AdvancedSearch->Save(); // ds_documentacaoAux
			$this->ds_imapactoDb->AdvancedSearch->Save(); // ds_imapactoDb
			$this->ic_stSolicitacao->AdvancedSearch->Save(); // ic_stSolicitacao
			$this->nu_usuarioAlterou->AdvancedSearch->Save(); // nu_usuarioAlterou
			$this->dh_alteracao->AdvancedSearch->Save(); // dh_alteracao
			$this->nu_usuarioIncluiu->AdvancedSearch->Save(); // nu_usuarioIncluiu
			$this->dh_inclusao->AdvancedSearch->Save(); // dh_inclusao
			$this->dt_stSolicitacao->AdvancedSearch->Save(); // dt_stSolicitacao
			$this->qt_pfTotal->AdvancedSearch->Save(); // qt_pfTotal
			$this->vr_pfContForn->AdvancedSearch->Save(); // vr_pfContForn
			$this->nu_tpMetrica->AdvancedSearch->Save(); // nu_tpMetrica
			$this->ds_observacoesContForn->AdvancedSearch->Save(); // ds_observacoesContForn
			$this->im_anexosContForn->AdvancedSearch->Save(); // im_anexosContForn
			$this->nu_contagemAnt->AdvancedSearch->Save(); // nu_contagemAnt
			$this->ds_observaocoesContAnt->AdvancedSearch->Save(); // ds_observaocoesContAnt
			$this->im_anexosContAnt->AdvancedSearch->Save(); // im_anexosContAnt
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
		if ($this->nu_solMetricas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_tpSolicitacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_projeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_atividadeMaeRedmine->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_observacoes->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_documentacaoAux->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_imapactoDb->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_stSolicitacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_usuarioAlterou->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dh_alteracao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_usuarioIncluiu->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dh_inclusao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_stSolicitacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qt_pfTotal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_pfContForn->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_tpMetrica->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_observacoesContForn->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_anexosContForn->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_contagemAnt->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_observaocoesContAnt->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->im_anexosContAnt->AdvancedSearch->IssetSession())
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
		$this->nu_solMetricas->AdvancedSearch->UnsetSession();
		$this->nu_tpSolicitacao->AdvancedSearch->UnsetSession();
		$this->nu_projeto->AdvancedSearch->UnsetSession();
		$this->no_atividadeMaeRedmine->AdvancedSearch->UnsetSession();
		$this->ds_observacoes->AdvancedSearch->UnsetSession();
		$this->ds_documentacaoAux->AdvancedSearch->UnsetSession();
		$this->ds_imapactoDb->AdvancedSearch->UnsetSession();
		$this->ic_stSolicitacao->AdvancedSearch->UnsetSession();
		$this->nu_usuarioAlterou->AdvancedSearch->UnsetSession();
		$this->dh_alteracao->AdvancedSearch->UnsetSession();
		$this->nu_usuarioIncluiu->AdvancedSearch->UnsetSession();
		$this->dh_inclusao->AdvancedSearch->UnsetSession();
		$this->dt_stSolicitacao->AdvancedSearch->UnsetSession();
		$this->qt_pfTotal->AdvancedSearch->UnsetSession();
		$this->vr_pfContForn->AdvancedSearch->UnsetSession();
		$this->nu_tpMetrica->AdvancedSearch->UnsetSession();
		$this->ds_observacoesContForn->AdvancedSearch->UnsetSession();
		$this->im_anexosContForn->AdvancedSearch->UnsetSession();
		$this->nu_contagemAnt->AdvancedSearch->UnsetSession();
		$this->ds_observaocoesContAnt->AdvancedSearch->UnsetSession();
		$this->im_anexosContAnt->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_solMetricas->AdvancedSearch->Load();
		$this->nu_tpSolicitacao->AdvancedSearch->Load();
		$this->nu_projeto->AdvancedSearch->Load();
		$this->no_atividadeMaeRedmine->AdvancedSearch->Load();
		$this->ds_observacoes->AdvancedSearch->Load();
		$this->ds_documentacaoAux->AdvancedSearch->Load();
		$this->ds_imapactoDb->AdvancedSearch->Load();
		$this->ic_stSolicitacao->AdvancedSearch->Load();
		$this->nu_usuarioAlterou->AdvancedSearch->Load();
		$this->dh_alteracao->AdvancedSearch->Load();
		$this->nu_usuarioIncluiu->AdvancedSearch->Load();
		$this->dh_inclusao->AdvancedSearch->Load();
		$this->dt_stSolicitacao->AdvancedSearch->Load();
		$this->qt_pfTotal->AdvancedSearch->Load();
		$this->vr_pfContForn->AdvancedSearch->Load();
		$this->nu_tpMetrica->AdvancedSearch->Load();
		$this->ds_observacoesContForn->AdvancedSearch->Load();
		$this->im_anexosContForn->AdvancedSearch->Load();
		$this->nu_contagemAnt->AdvancedSearch->Load();
		$this->ds_observaocoesContAnt->AdvancedSearch->Load();
		$this->im_anexosContAnt->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_solMetricas, $bCtrl); // nu_solMetricas
			$this->UpdateSort($this->nu_tpSolicitacao, $bCtrl); // nu_tpSolicitacao
			$this->UpdateSort($this->nu_projeto, $bCtrl); // nu_projeto
			$this->UpdateSort($this->ic_stSolicitacao, $bCtrl); // ic_stSolicitacao
			$this->UpdateSort($this->nu_usuarioAlterou, $bCtrl); // nu_usuarioAlterou
			$this->UpdateSort($this->dt_stSolicitacao, $bCtrl); // dt_stSolicitacao
			$this->UpdateSort($this->qt_pfTotal, $bCtrl); // qt_pfTotal
			$this->UpdateSort($this->vr_pfContForn, $bCtrl); // vr_pfContForn
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
				$this->nu_solMetricas->setSort("DESC");
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
				$this->nu_solMetricas->setSort("");
				$this->nu_tpSolicitacao->setSort("");
				$this->nu_projeto->setSort("");
				$this->ic_stSolicitacao->setSort("");
				$this->nu_usuarioAlterou->setSort("");
				$this->dt_stSolicitacao->setSort("");
				$this->qt_pfTotal->setSort("");
				$this->vr_pfContForn->setSort("");
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

		// "detail_solicitacao_ocorrencia"
		$item = &$this->ListOptions->Add("detail_solicitacao_ocorrencia");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'solicitacao_ocorrencia') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["solicitacao_ocorrencia_grid"])) $GLOBALS["solicitacao_ocorrencia_grid"] = new csolicitacao_ocorrencia_grid;

		// "detail_contagempf"
		$item = &$this->ListOptions->Add("detail_contagempf");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'contagempf') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["contagempf_grid"])) $GLOBALS["contagempf_grid"] = new ccontagempf_grid;

		// "detail_estimativa"
		$item = &$this->ListOptions->Add("detail_estimativa");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'estimativa') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["estimativa_grid"])) $GLOBALS["estimativa_grid"] = new cestimativa_grid;

		// "detail_laudo"
		$item = &$this->ListOptions->Add("detail_laudo");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'laudo') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["laudo_grid"])) $GLOBALS["laudo_grid"] = new claudo_grid;

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

		// "detail_solicitacao_ocorrencia"
		$oListOpt = &$this->ListOptions->Items["detail_solicitacao_ocorrencia"];
		if ($Security->AllowList(CurrentProjectID() . 'solicitacao_ocorrencia')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("solicitacao_ocorrencia", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("solicitacao_ocorrencialist.php?" . EW_TABLE_SHOW_MASTER . "=solicitacaoMetricas&nu_solMetricas=" . strval($this->nu_solMetricas->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["solicitacao_ocorrencia_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'solicitacao_ocorrencia')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=solicitacao_ocorrencia")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "solicitacao_ocorrencia";
			}
			if ($GLOBALS["solicitacao_ocorrencia_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'solicitacao_ocorrencia')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=solicitacao_ocorrencia")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "solicitacao_ocorrencia";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_contagempf"
		$oListOpt = &$this->ListOptions->Items["detail_contagempf"];
		if ($Security->AllowList(CurrentProjectID() . 'contagempf')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("contagempf", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("contagempflist.php?" . EW_TABLE_SHOW_MASTER . "=solicitacaoMetricas&nu_solMetricas=" . strval($this->nu_solMetricas->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["contagempf_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'contagempf')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=contagempf")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "contagempf";
			}
			if ($GLOBALS["contagempf_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'contagempf')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=contagempf")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "contagempf";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_estimativa"
		$oListOpt = &$this->ListOptions->Items["detail_estimativa"];
		if ($Security->AllowList(CurrentProjectID() . 'estimativa')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("estimativa", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("estimativalist.php?" . EW_TABLE_SHOW_MASTER . "=solicitacaoMetricas&nu_solMetricas=" . strval($this->nu_solMetricas->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["estimativa_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'estimativa')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=estimativa")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "estimativa";
			}
			if ($GLOBALS["estimativa_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'estimativa')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=estimativa")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "estimativa";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_laudo"
		$oListOpt = &$this->ListOptions->Items["detail_laudo"];
		if ($Security->AllowList(CurrentProjectID() . 'laudo')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("laudo", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("laudolist.php?" . EW_TABLE_SHOW_MASTER . "=solicitacaoMetricas&nu_solMetricas=" . strval($this->nu_solMetricas->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["laudo_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'laudo')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=laudo")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "laudo";
			}
			if ($GLOBALS["laudo_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'laudo')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=laudo")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "laudo";
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
		$item = &$option->Add("detailadd_solicitacao_ocorrencia");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=solicitacao_ocorrencia") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["solicitacao_ocorrencia"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["solicitacao_ocorrencia"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'solicitacao_ocorrencia') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "solicitacao_ocorrencia";
		}
		$item = &$option->Add("detailadd_contagempf");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=contagempf") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["contagempf"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["contagempf"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'contagempf') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "contagempf";
		}
		$item = &$option->Add("detailadd_estimativa");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=estimativa") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["estimativa"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["estimativa"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'estimativa') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "estimativa";
		}
		$item = &$option->Add("detailadd_laudo");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=laudo") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["laudo"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["laudo"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'laudo') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "laudo";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fsolicitacaoMetricaslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_solMetricas

		$this->nu_solMetricas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_solMetricas"]);
		if ($this->nu_solMetricas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_solMetricas->AdvancedSearch->SearchOperator = @$_GET["z_nu_solMetricas"];

		// nu_tpSolicitacao
		$this->nu_tpSolicitacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_tpSolicitacao"]);
		if ($this->nu_tpSolicitacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_tpSolicitacao->AdvancedSearch->SearchOperator = @$_GET["z_nu_tpSolicitacao"];

		// nu_projeto
		$this->nu_projeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_projeto"]);
		if ($this->nu_projeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_projeto->AdvancedSearch->SearchOperator = @$_GET["z_nu_projeto"];

		// no_atividadeMaeRedmine
		$this->no_atividadeMaeRedmine->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_atividadeMaeRedmine"]);
		if ($this->no_atividadeMaeRedmine->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_atividadeMaeRedmine->AdvancedSearch->SearchOperator = @$_GET["z_no_atividadeMaeRedmine"];

		// ds_observacoes
		$this->ds_observacoes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_observacoes"]);
		if ($this->ds_observacoes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_observacoes->AdvancedSearch->SearchOperator = @$_GET["z_ds_observacoes"];

		// ds_documentacaoAux
		$this->ds_documentacaoAux->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_documentacaoAux"]);
		if ($this->ds_documentacaoAux->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_documentacaoAux->AdvancedSearch->SearchOperator = @$_GET["z_ds_documentacaoAux"];

		// ds_imapactoDb
		$this->ds_imapactoDb->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_imapactoDb"]);
		if ($this->ds_imapactoDb->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_imapactoDb->AdvancedSearch->SearchOperator = @$_GET["z_ds_imapactoDb"];

		// ic_stSolicitacao
		$this->ic_stSolicitacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_stSolicitacao"]);
		if ($this->ic_stSolicitacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_stSolicitacao->AdvancedSearch->SearchOperator = @$_GET["z_ic_stSolicitacao"];

		// nu_usuarioAlterou
		$this->nu_usuarioAlterou->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_usuarioAlterou"]);
		if ($this->nu_usuarioAlterou->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_usuarioAlterou->AdvancedSearch->SearchOperator = @$_GET["z_nu_usuarioAlterou"];

		// dh_alteracao
		$this->dh_alteracao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dh_alteracao"]);
		if ($this->dh_alteracao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dh_alteracao->AdvancedSearch->SearchOperator = @$_GET["z_dh_alteracao"];

		// nu_usuarioIncluiu
		$this->nu_usuarioIncluiu->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_usuarioIncluiu"]);
		if ($this->nu_usuarioIncluiu->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_usuarioIncluiu->AdvancedSearch->SearchOperator = @$_GET["z_nu_usuarioIncluiu"];

		// dh_inclusao
		$this->dh_inclusao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dh_inclusao"]);
		if ($this->dh_inclusao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dh_inclusao->AdvancedSearch->SearchOperator = @$_GET["z_dh_inclusao"];

		// dt_stSolicitacao
		$this->dt_stSolicitacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_stSolicitacao"]);
		if ($this->dt_stSolicitacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_stSolicitacao->AdvancedSearch->SearchOperator = @$_GET["z_dt_stSolicitacao"];

		// qt_pfTotal
		$this->qt_pfTotal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_qt_pfTotal"]);
		if ($this->qt_pfTotal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->qt_pfTotal->AdvancedSearch->SearchOperator = @$_GET["z_qt_pfTotal"];

		// vr_pfContForn
		$this->vr_pfContForn->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_pfContForn"]);
		if ($this->vr_pfContForn->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_pfContForn->AdvancedSearch->SearchOperator = @$_GET["z_vr_pfContForn"];

		// nu_tpMetrica
		$this->nu_tpMetrica->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_tpMetrica"]);
		if ($this->nu_tpMetrica->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_tpMetrica->AdvancedSearch->SearchOperator = @$_GET["z_nu_tpMetrica"];

		// ds_observacoesContForn
		$this->ds_observacoesContForn->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_observacoesContForn"]);
		if ($this->ds_observacoesContForn->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_observacoesContForn->AdvancedSearch->SearchOperator = @$_GET["z_ds_observacoesContForn"];

		// im_anexosContForn
		$this->im_anexosContForn->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_anexosContForn"]);
		if ($this->im_anexosContForn->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_anexosContForn->AdvancedSearch->SearchOperator = @$_GET["z_im_anexosContForn"];

		// nu_contagemAnt
		$this->nu_contagemAnt->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_contagemAnt"]);
		if ($this->nu_contagemAnt->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_contagemAnt->AdvancedSearch->SearchOperator = @$_GET["z_nu_contagemAnt"];

		// ds_observaocoesContAnt
		$this->ds_observaocoesContAnt->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_observaocoesContAnt"]);
		if ($this->ds_observaocoesContAnt->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_observaocoesContAnt->AdvancedSearch->SearchOperator = @$_GET["z_ds_observaocoesContAnt"];

		// im_anexosContAnt
		$this->im_anexosContAnt->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_im_anexosContAnt"]);
		if ($this->im_anexosContAnt->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->im_anexosContAnt->AdvancedSearch->SearchOperator = @$_GET["z_im_anexosContAnt"];
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
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_tpSolicitacao->setDbValue($rs->fields('nu_tpSolicitacao'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		if (array_key_exists('EV__nu_projeto', $rs->fields)) {
			$this->nu_projeto->VirtualValue = $rs->fields('EV__nu_projeto'); // Set up virtual field value
		} else {
			$this->nu_projeto->VirtualValue = ""; // Clear value
		}
		$this->no_atividadeMaeRedmine->setDbValue($rs->fields('no_atividadeMaeRedmine'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ds_documentacaoAux->setDbValue($rs->fields('ds_documentacaoAux'));
		$this->ds_imapactoDb->setDbValue($rs->fields('ds_imapactoDb'));
		$this->ic_stSolicitacao->setDbValue($rs->fields('ic_stSolicitacao'));
		$this->nu_usuarioAlterou->setDbValue($rs->fields('nu_usuarioAlterou'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
		$this->nu_usuarioIncluiu->setDbValue($rs->fields('nu_usuarioIncluiu'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->dt_stSolicitacao->setDbValue($rs->fields('dt_stSolicitacao'));
		$this->qt_pfTotal->setDbValue($rs->fields('qt_pfTotal'));
		$this->vr_pfContForn->setDbValue($rs->fields('vr_pfContForn'));
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->ds_observacoesContForn->setDbValue($rs->fields('ds_observacoesContForn'));
		$this->im_anexosContForn->Upload->DbValue = $rs->fields('im_anexosContForn');
		$this->nu_contagemAnt->setDbValue($rs->fields('nu_contagemAnt'));
		if (array_key_exists('EV__nu_contagemAnt', $rs->fields)) {
			$this->nu_contagemAnt->VirtualValue = $rs->fields('EV__nu_contagemAnt'); // Set up virtual field value
		} else {
			$this->nu_contagemAnt->VirtualValue = ""; // Clear value
		}
		$this->ds_observaocoesContAnt->setDbValue($rs->fields('ds_observaocoesContAnt'));
		$this->im_anexosContAnt->Upload->DbValue = $rs->fields('im_anexosContAnt');
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_tpSolicitacao->DbValue = $row['nu_tpSolicitacao'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->no_atividadeMaeRedmine->DbValue = $row['no_atividadeMaeRedmine'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ds_documentacaoAux->DbValue = $row['ds_documentacaoAux'];
		$this->ds_imapactoDb->DbValue = $row['ds_imapactoDb'];
		$this->ic_stSolicitacao->DbValue = $row['ic_stSolicitacao'];
		$this->nu_usuarioAlterou->DbValue = $row['nu_usuarioAlterou'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
		$this->nu_usuarioIncluiu->DbValue = $row['nu_usuarioIncluiu'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->dt_stSolicitacao->DbValue = $row['dt_stSolicitacao'];
		$this->qt_pfTotal->DbValue = $row['qt_pfTotal'];
		$this->vr_pfContForn->DbValue = $row['vr_pfContForn'];
		$this->nu_tpMetrica->DbValue = $row['nu_tpMetrica'];
		$this->ds_observacoesContForn->DbValue = $row['ds_observacoesContForn'];
		$this->im_anexosContForn->Upload->DbValue = $row['im_anexosContForn'];
		$this->nu_contagemAnt->DbValue = $row['nu_contagemAnt'];
		$this->ds_observaocoesContAnt->DbValue = $row['ds_observaocoesContAnt'];
		$this->im_anexosContAnt->Upload->DbValue = $row['im_anexosContAnt'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_solMetricas")) <> "")
			$this->nu_solMetricas->CurrentValue = $this->getKey("nu_solMetricas"); // nu_solMetricas
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
		if ($this->qt_pfTotal->FormValue == $this->qt_pfTotal->CurrentValue && is_numeric(ew_StrToFloat($this->qt_pfTotal->CurrentValue)))
			$this->qt_pfTotal->CurrentValue = ew_StrToFloat($this->qt_pfTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_pfContForn->FormValue == $this->vr_pfContForn->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pfContForn->CurrentValue)))
			$this->vr_pfContForn->CurrentValue = ew_StrToFloat($this->vr_pfContForn->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solMetricas
		// nu_tpSolicitacao
		// nu_projeto
		// no_atividadeMaeRedmine
		// ds_observacoes
		// ds_documentacaoAux
		// ds_imapactoDb
		// ic_stSolicitacao
		// nu_usuarioAlterou
		// dh_alteracao
		// nu_usuarioIncluiu
		// dh_inclusao
		// dt_stSolicitacao
		// qt_pfTotal
		// vr_pfContForn
		// nu_tpMetrica
		// ds_observacoesContForn
		// im_anexosContForn
		// nu_contagemAnt
		// ds_observaocoesContAnt
		// im_anexosContAnt
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solMetricas
			$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_tpSolicitacao
			if (strval($this->nu_tpSolicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpSolicitacao]" . ew_SearchString("=", $this->nu_tpSolicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpSolicitacao], [no_tpSolicitacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpsolicitacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpSolicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpSolicitacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpSolicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpSolicitacao->ViewValue = $this->nu_tpSolicitacao->CurrentValue;
				}
			} else {
				$this->nu_tpSolicitacao->ViewValue = NULL;
			}
			$this->nu_tpSolicitacao->ViewCustomAttributes = "";

			// nu_projeto
			if ($this->nu_projeto->VirtualValue <> "") {
				$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
			} else {
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_passivelContPf]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
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

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->ViewValue = $this->no_atividadeMaeRedmine->CurrentValue;
			$this->no_atividadeMaeRedmine->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// ds_documentacaoAux
			$this->ds_documentacaoAux->ViewValue = $this->ds_documentacaoAux->CurrentValue;
			$this->ds_documentacaoAux->ViewCustomAttributes = "";

			// ds_imapactoDb
			$this->ds_imapactoDb->ViewValue = $this->ds_imapactoDb->CurrentValue;
			$this->ds_imapactoDb->ViewCustomAttributes = "";

			// ic_stSolicitacao
			if (strval($this->ic_stSolicitacao->CurrentValue) <> "") {
				switch ($this->ic_stSolicitacao->CurrentValue) {
					case $this->ic_stSolicitacao->FldTagValue(1):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(1) <> "" ? $this->ic_stSolicitacao->FldTagCaption(1) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(2):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(2) <> "" ? $this->ic_stSolicitacao->FldTagCaption(2) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(3):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(3) <> "" ? $this->ic_stSolicitacao->FldTagCaption(3) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(4):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(4) <> "" ? $this->ic_stSolicitacao->FldTagCaption(4) : $this->ic_stSolicitacao->CurrentValue;
						break;
					default:
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->CurrentValue;
				}
			} else {
				$this->ic_stSolicitacao->ViewValue = NULL;
			}
			$this->ic_stSolicitacao->ViewCustomAttributes = "";

			// nu_usuarioAlterou
			if (strval($this->nu_usuarioAlterou->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlterou->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlterou, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlterou->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlterou->ViewValue = $this->nu_usuarioAlterou->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlterou->ViewValue = NULL;
			}
			$this->nu_usuarioAlterou->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 10);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_usuarioIncluiu
			if (strval($this->nu_usuarioIncluiu->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioIncluiu->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioIncluiu, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioIncluiu->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioIncluiu->ViewValue = $this->nu_usuarioIncluiu->CurrentValue;
				}
			} else {
				$this->nu_usuarioIncluiu->ViewValue = NULL;
			}
			$this->nu_usuarioIncluiu->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->ViewValue = $this->dt_stSolicitacao->CurrentValue;
			$this->dt_stSolicitacao->ViewValue = ew_FormatDateTime($this->dt_stSolicitacao->ViewValue, 7);
			$this->dt_stSolicitacao->ViewCustomAttributes = "";

			// qt_pfTotal
			$this->qt_pfTotal->ViewValue = $this->qt_pfTotal->CurrentValue;
			$this->qt_pfTotal->ViewCustomAttributes = "";

			// vr_pfContForn
			$this->vr_pfContForn->ViewValue = $this->vr_pfContForn->CurrentValue;
			$this->vr_pfContForn->ViewCustomAttributes = "";

			// nu_tpMetrica
			if (strval($this->nu_tpMetrica->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_tpMetrica->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpMetrica, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpMetrica->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
				}
			} else {
				$this->nu_tpMetrica->ViewValue = NULL;
			}
			$this->nu_tpMetrica->ViewCustomAttributes = "";

			// ds_observacoesContForn
			$this->ds_observacoesContForn->ViewValue = $this->ds_observacoesContForn->CurrentValue;
			$this->ds_observacoesContForn->ViewCustomAttributes = "";

			// im_anexosContForn
			$this->im_anexosContForn->UploadPath = "contagem_fornecedor";
			if (!ew_Empty($this->im_anexosContForn->Upload->DbValue)) {
				$this->im_anexosContForn->ViewValue = $this->im_anexosContForn->Upload->DbValue;
			} else {
				$this->im_anexosContForn->ViewValue = "";
			}
			$this->im_anexosContForn->ViewCustomAttributes = "";

			// nu_contagemAnt
			if ($this->nu_contagemAnt->VirtualValue <> "") {
				$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->VirtualValue;
			} else {
			if (strval($this->nu_contagemAnt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_contagemAnt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contagemAnt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contagemAnt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->CurrentValue;
				}
			} else {
				$this->nu_contagemAnt->ViewValue = NULL;
			}
			}
			$this->nu_contagemAnt->ViewCustomAttributes = "";

			// ds_observaocoesContAnt
			$this->ds_observaocoesContAnt->ViewValue = $this->ds_observaocoesContAnt->CurrentValue;
			$this->ds_observaocoesContAnt->ViewCustomAttributes = "";

			// im_anexosContAnt
			$this->im_anexosContAnt->UploadPath = "contagem_anterior";
			if (!ew_Empty($this->im_anexosContAnt->Upload->DbValue)) {
				$this->im_anexosContAnt->ViewValue = $this->im_anexosContAnt->Upload->DbValue;
			} else {
				$this->im_anexosContAnt->ViewValue = "";
			}
			$this->im_anexosContAnt->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_solMetricas
			$this->nu_solMetricas->LinkCustomAttributes = "";
			$this->nu_solMetricas->HrefValue = "";
			$this->nu_solMetricas->TooltipValue = "";

			// nu_tpSolicitacao
			$this->nu_tpSolicitacao->LinkCustomAttributes = "";
			$this->nu_tpSolicitacao->HrefValue = "";
			$this->nu_tpSolicitacao->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// ic_stSolicitacao
			$this->ic_stSolicitacao->LinkCustomAttributes = "";
			$this->ic_stSolicitacao->HrefValue = "";
			$this->ic_stSolicitacao->TooltipValue = "";

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->LinkCustomAttributes = "";
			$this->nu_usuarioAlterou->HrefValue = "";
			$this->nu_usuarioAlterou->TooltipValue = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->LinkCustomAttributes = "";
			$this->dt_stSolicitacao->HrefValue = "";
			$this->dt_stSolicitacao->TooltipValue = "";

			// qt_pfTotal
			$this->qt_pfTotal->LinkCustomAttributes = "";
			$this->qt_pfTotal->HrefValue = "";
			$this->qt_pfTotal->TooltipValue = "";

			// vr_pfContForn
			$this->vr_pfContForn->LinkCustomAttributes = "";
			$this->vr_pfContForn->HrefValue = "";
			$this->vr_pfContForn->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_solMetricas
			$this->nu_solMetricas->EditCustomAttributes = "";
			$this->nu_solMetricas->EditValue = ew_HtmlEncode($this->nu_solMetricas->AdvancedSearch->SearchValue);
			$this->nu_solMetricas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_solMetricas->FldCaption()));

			// nu_tpSolicitacao
			$this->nu_tpSolicitacao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpSolicitacao], [no_tpSolicitacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpsolicitacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpSolicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpSolicitacao] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpSolicitacao->EditValue = $arwrk;

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			$this->nu_projeto->EditValue = ew_HtmlEncode($this->nu_projeto->AdvancedSearch->SearchValue);
			$this->nu_projeto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_projeto->FldCaption()));

			// ic_stSolicitacao
			$this->ic_stSolicitacao->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stSolicitacao->FldTagValue(1), $this->ic_stSolicitacao->FldTagCaption(1) <> "" ? $this->ic_stSolicitacao->FldTagCaption(1) : $this->ic_stSolicitacao->FldTagValue(1));
			$arwrk[] = array($this->ic_stSolicitacao->FldTagValue(2), $this->ic_stSolicitacao->FldTagCaption(2) <> "" ? $this->ic_stSolicitacao->FldTagCaption(2) : $this->ic_stSolicitacao->FldTagValue(2));
			$arwrk[] = array($this->ic_stSolicitacao->FldTagValue(3), $this->ic_stSolicitacao->FldTagCaption(3) <> "" ? $this->ic_stSolicitacao->FldTagCaption(3) : $this->ic_stSolicitacao->FldTagValue(3));
			$arwrk[] = array($this->ic_stSolicitacao->FldTagValue(4), $this->ic_stSolicitacao->FldTagCaption(4) <> "" ? $this->ic_stSolicitacao->FldTagCaption(4) : $this->ic_stSolicitacao->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_stSolicitacao->EditValue = $arwrk;

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["solicitacaoMetricas"]->UserIDAllow($GLOBALS["solicitacaoMetricas"]->CurrentAction)) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlterou, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioAlterou->EditValue = $arwrk;

			// dt_stSolicitacao
			$this->dt_stSolicitacao->EditCustomAttributes = "";
			$this->dt_stSolicitacao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_stSolicitacao->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_stSolicitacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_stSolicitacao->FldCaption()));

			// qt_pfTotal
			$this->qt_pfTotal->EditCustomAttributes = "";
			$this->qt_pfTotal->EditValue = ew_HtmlEncode($this->qt_pfTotal->AdvancedSearch->SearchValue);
			$this->qt_pfTotal->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_pfTotal->FldCaption()));

			// vr_pfContForn
			$this->vr_pfContForn->EditCustomAttributes = "";
			$this->vr_pfContForn->EditValue = ew_HtmlEncode($this->vr_pfContForn->AdvancedSearch->SearchValue);
			$this->vr_pfContForn->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_pfContForn->FldCaption()));
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
		if (!ew_CheckInteger($this->nu_solMetricas->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_solMetricas->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dt_stSolicitacao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->dt_stSolicitacao->FldErrMsg());
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
		$this->nu_solMetricas->AdvancedSearch->Load();
		$this->nu_tpSolicitacao->AdvancedSearch->Load();
		$this->nu_projeto->AdvancedSearch->Load();
		$this->no_atividadeMaeRedmine->AdvancedSearch->Load();
		$this->ds_observacoes->AdvancedSearch->Load();
		$this->ds_documentacaoAux->AdvancedSearch->Load();
		$this->ds_imapactoDb->AdvancedSearch->Load();
		$this->ic_stSolicitacao->AdvancedSearch->Load();
		$this->nu_usuarioAlterou->AdvancedSearch->Load();
		$this->dh_alteracao->AdvancedSearch->Load();
		$this->nu_usuarioIncluiu->AdvancedSearch->Load();
		$this->dh_inclusao->AdvancedSearch->Load();
		$this->dt_stSolicitacao->AdvancedSearch->Load();
		$this->qt_pfTotal->AdvancedSearch->Load();
		$this->vr_pfContForn->AdvancedSearch->Load();
		$this->nu_tpMetrica->AdvancedSearch->Load();
		$this->ds_observacoesContForn->AdvancedSearch->Load();
		$this->im_anexosContForn->AdvancedSearch->Load();
		$this->nu_contagemAnt->AdvancedSearch->Load();
		$this->ds_observaocoesContAnt->AdvancedSearch->Load();
		$this->im_anexosContAnt->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_solicitacaoMetricas\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_solicitacaoMetricas',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fsolicitacaoMetricaslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_solMetricas); // nu_solMetricas
		$this->AddSearchQueryString($sQry, $this->nu_tpSolicitacao); // nu_tpSolicitacao
		$this->AddSearchQueryString($sQry, $this->nu_projeto); // nu_projeto
		$this->AddSearchQueryString($sQry, $this->no_atividadeMaeRedmine); // no_atividadeMaeRedmine
		$this->AddSearchQueryString($sQry, $this->ds_observacoes); // ds_observacoes
		$this->AddSearchQueryString($sQry, $this->ds_documentacaoAux); // ds_documentacaoAux
		$this->AddSearchQueryString($sQry, $this->ds_imapactoDb); // ds_imapactoDb
		$this->AddSearchQueryString($sQry, $this->ic_stSolicitacao); // ic_stSolicitacao
		$this->AddSearchQueryString($sQry, $this->nu_usuarioAlterou); // nu_usuarioAlterou
		$this->AddSearchQueryString($sQry, $this->dh_alteracao); // dh_alteracao
		$this->AddSearchQueryString($sQry, $this->nu_usuarioIncluiu); // nu_usuarioIncluiu
		$this->AddSearchQueryString($sQry, $this->dh_inclusao); // dh_inclusao
		$this->AddSearchQueryString($sQry, $this->dt_stSolicitacao); // dt_stSolicitacao
		$this->AddSearchQueryString($sQry, $this->qt_pfTotal); // qt_pfTotal
		$this->AddSearchQueryString($sQry, $this->vr_pfContForn); // vr_pfContForn
		$this->AddSearchQueryString($sQry, $this->nu_tpMetrica); // nu_tpMetrica
		$this->AddSearchQueryString($sQry, $this->ds_observacoesContForn); // ds_observacoesContForn
		$this->AddSearchQueryString($sQry, $this->nu_contagemAnt); // nu_contagemAnt
		$this->AddSearchQueryString($sQry, $this->ds_observaocoesContAnt); // ds_observaocoesContAnt

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
		$table = 'solicitacaoMetricas';
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
if (!isset($solicitacaoMetricas_list)) $solicitacaoMetricas_list = new csolicitacaoMetricas_list();

// Page init
$solicitacaoMetricas_list->Page_Init();

// Page main
$solicitacaoMetricas_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$solicitacaoMetricas_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<script type="text/javascript">

// Page object
var solicitacaoMetricas_list = new ew_Page("solicitacaoMetricas_list");
solicitacaoMetricas_list.PageID = "list"; // Page ID
var EW_PAGE_ID = solicitacaoMetricas_list.PageID; // For backward compatibility

// Form object
var fsolicitacaoMetricaslist = new ew_Form("fsolicitacaoMetricaslist");
fsolicitacaoMetricaslist.FormKeyCountName = '<?php echo $solicitacaoMetricas_list->FormKeyCountName ?>';

// Form_CustomValidate event
fsolicitacaoMetricaslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsolicitacaoMetricaslist.ValidateRequired = true;
<?php } else { ?>
fsolicitacaoMetricaslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsolicitacaoMetricaslist.Lists["x_nu_tpSolicitacao"] = {"LinkField":"x_nu_tpSolicitacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpSolicitacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricaslist.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricaslist.Lists["x_nu_usuarioAlterou"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fsolicitacaoMetricaslistsrch = new ew_Form("fsolicitacaoMetricaslistsrch");

// Validate function for search
fsolicitacaoMetricaslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_solMetricas");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($solicitacaoMetricas->nu_solMetricas->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_dt_stSolicitacao");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($solicitacaoMetricas->dt_stSolicitacao->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fsolicitacaoMetricaslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsolicitacaoMetricaslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fsolicitacaoMetricaslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fsolicitacaoMetricaslistsrch.Lists["x_nu_tpSolicitacao"] = {"LinkField":"x_nu_tpSolicitacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpSolicitacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricaslistsrch.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricaslistsrch.Lists["x_nu_usuarioAlterou"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fsolicitacaoMetricaslistsrch) fsolicitacaoMetricaslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($solicitacaoMetricas_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $solicitacaoMetricas_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$solicitacaoMetricas_list->TotalRecs = $solicitacaoMetricas->SelectRecordCount();
	} else {
		if ($solicitacaoMetricas_list->Recordset = $solicitacaoMetricas_list->LoadRecordset())
			$solicitacaoMetricas_list->TotalRecs = $solicitacaoMetricas_list->Recordset->RecordCount();
	}
	$solicitacaoMetricas_list->StartRec = 1;
	if ($solicitacaoMetricas_list->DisplayRecs <= 0 || ($solicitacaoMetricas->Export <> "" && $solicitacaoMetricas->ExportAll)) // Display all records
		$solicitacaoMetricas_list->DisplayRecs = $solicitacaoMetricas_list->TotalRecs;
	if (!($solicitacaoMetricas->Export <> "" && $solicitacaoMetricas->ExportAll))
		$solicitacaoMetricas_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$solicitacaoMetricas_list->Recordset = $solicitacaoMetricas_list->LoadRecordset($solicitacaoMetricas_list->StartRec-1, $solicitacaoMetricas_list->DisplayRecs);
$solicitacaoMetricas_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($solicitacaoMetricas->Export == "" && $solicitacaoMetricas->CurrentAction == "") { ?>
<form name="fsolicitacaoMetricaslistsrch" id="fsolicitacaoMetricaslistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fsolicitacaoMetricaslistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fsolicitacaoMetricaslistsrch_SearchGroup" href="#fsolicitacaoMetricaslistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fsolicitacaoMetricaslistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fsolicitacaoMetricaslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="solicitacaoMetricas">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$solicitacaoMetricas_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$solicitacaoMetricas->RowType = EW_ROWTYPE_SEARCH;

// Render row
$solicitacaoMetricas->ResetAttrs();
$solicitacaoMetricas_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($solicitacaoMetricas->nu_solMetricas->Visible) { // nu_solMetricas ?>
	<span id="xsc_nu_solMetricas" class="ewCell">
		<span class="ewSearchCaption"><?php echo $solicitacaoMetricas->nu_solMetricas->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_solMetricas" id="z_nu_solMetricas" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_solMetricas" name="x_nu_solMetricas" id="x_nu_solMetricas" placeholder="<?php echo $solicitacaoMetricas->nu_solMetricas->PlaceHolder ?>" value="<?php echo $solicitacaoMetricas->nu_solMetricas->EditValue ?>"<?php echo $solicitacaoMetricas->nu_solMetricas->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_tpSolicitacao->Visible) { // nu_tpSolicitacao ?>
	<span id="xsc_nu_tpSolicitacao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $solicitacaoMetricas->nu_tpSolicitacao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_tpSolicitacao" id="z_nu_tpSolicitacao" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_tpSolicitacao" id="x_nu_tpSolicitacao" name="x_nu_tpSolicitacao"<?php echo $solicitacaoMetricas->nu_tpSolicitacao->EditAttributes() ?>>
<?php
if (is_array($solicitacaoMetricas->nu_tpSolicitacao->EditValue)) {
	$arwrk = $solicitacaoMetricas->nu_tpSolicitacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacaoMetricas->nu_tpSolicitacao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fsolicitacaoMetricaslistsrch.Lists["x_nu_tpSolicitacao"].Options = <?php echo (is_array($solicitacaoMetricas->nu_tpSolicitacao->EditValue)) ? ew_ArrayToJson($solicitacaoMetricas->nu_tpSolicitacao->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_projeto->Visible) { // nu_projeto ?>
	<span id="xsc_nu_projeto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $solicitacaoMetricas->nu_projeto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_projeto" id="z_nu_projeto" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_projeto" name="x_nu_projeto" id="x_nu_projeto" size="30" placeholder="<?php echo $solicitacaoMetricas->nu_projeto->PlaceHolder ?>" value="<?php echo $solicitacaoMetricas->nu_projeto->EditValue ?>"<?php echo $solicitacaoMetricas->nu_projeto->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($solicitacaoMetricas->ic_stSolicitacao->Visible) { // ic_stSolicitacao ?>
	<span id="xsc_ic_stSolicitacao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $solicitacaoMetricas->ic_stSolicitacao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_stSolicitacao" id="z_ic_stSolicitacao" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_stSolicitacao" id="x_ic_stSolicitacao" name="x_ic_stSolicitacao"<?php echo $solicitacaoMetricas->ic_stSolicitacao->EditAttributes() ?>>
<?php
if (is_array($solicitacaoMetricas->ic_stSolicitacao->EditValue)) {
	$arwrk = $solicitacaoMetricas->ic_stSolicitacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacaoMetricas->ic_stSolicitacao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if ($solicitacaoMetricas->nu_usuarioAlterou->Visible) { // nu_usuarioAlterou ?>
	<span id="xsc_nu_usuarioAlterou" class="ewCell">
		<span class="ewSearchCaption"><?php echo $solicitacaoMetricas->nu_usuarioAlterou->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_usuarioAlterou" id="z_nu_usuarioAlterou" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_usuarioAlterou" id="x_nu_usuarioAlterou" name="x_nu_usuarioAlterou"<?php echo $solicitacaoMetricas->nu_usuarioAlterou->EditAttributes() ?>>
<?php
if (is_array($solicitacaoMetricas->nu_usuarioAlterou->EditValue)) {
	$arwrk = $solicitacaoMetricas->nu_usuarioAlterou->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($solicitacaoMetricas->nu_usuarioAlterou->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fsolicitacaoMetricaslistsrch.Lists["x_nu_usuarioAlterou"].Options = <?php echo (is_array($solicitacaoMetricas->nu_usuarioAlterou->EditValue)) ? ew_ArrayToJson($solicitacaoMetricas->nu_usuarioAlterou->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($solicitacaoMetricas->dt_stSolicitacao->Visible) { // dt_stSolicitacao ?>
	<span id="xsc_dt_stSolicitacao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $solicitacaoMetricas->dt_stSolicitacao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_dt_stSolicitacao" id="z_dt_stSolicitacao" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dt_stSolicitacao" name="x_dt_stSolicitacao" id="x_dt_stSolicitacao" size="30" placeholder="<?php echo $solicitacaoMetricas->dt_stSolicitacao->PlaceHolder ?>" value="<?php echo $solicitacaoMetricas->dt_stSolicitacao->EditValue ?>"<?php echo $solicitacaoMetricas->dt_stSolicitacao->EditAttributes() ?>>
<?php if (!$solicitacaoMetricas->dt_stSolicitacao->ReadOnly && !$solicitacaoMetricas->dt_stSolicitacao->Disabled && @$solicitacaoMetricas->dt_stSolicitacao->EditAttrs["readonly"] == "" && @$solicitacaoMetricas->dt_stSolicitacao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_stSolicitacao" name="cal_x_dt_stSolicitacao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_stSolicitacao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fsolicitacaoMetricaslistsrch", "x_dt_stSolicitacao", "%d/%m/%Y");
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
	<a class="btn ewShowAll" href="<?php echo $solicitacaoMetricas_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $solicitacaoMetricas_list->ShowPageHeader(); ?>
<?php
$solicitacaoMetricas_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fsolicitacaoMetricaslist" id="fsolicitacaoMetricaslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="solicitacaoMetricas">
<div id="gmp_solicitacaoMetricas" class="ewGridMiddlePanel">
<?php if ($solicitacaoMetricas_list->TotalRecs > 0) { ?>
<table id="tbl_solicitacaoMetricaslist" class="ewTable ewTableSeparate">
<?php echo $solicitacaoMetricas->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$solicitacaoMetricas_list->RenderListOptions();

// Render list options (header, left)
$solicitacaoMetricas_list->ListOptions->Render("header", "left");
?>
<?php if ($solicitacaoMetricas->nu_solMetricas->Visible) { // nu_solMetricas ?>
	<?php if ($solicitacaoMetricas->SortUrl($solicitacaoMetricas->nu_solMetricas) == "") { ?>
		<td><div id="elh_solicitacaoMetricas_nu_solMetricas" class="solicitacaoMetricas_nu_solMetricas"><div class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->nu_solMetricas->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $solicitacaoMetricas->SortUrl($solicitacaoMetricas->nu_solMetricas) ?>',2);"><div id="elh_solicitacaoMetricas_nu_solMetricas" class="solicitacaoMetricas_nu_solMetricas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->nu_solMetricas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacaoMetricas->nu_solMetricas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacaoMetricas->nu_solMetricas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacaoMetricas->nu_tpSolicitacao->Visible) { // nu_tpSolicitacao ?>
	<?php if ($solicitacaoMetricas->SortUrl($solicitacaoMetricas->nu_tpSolicitacao) == "") { ?>
		<td><div id="elh_solicitacaoMetricas_nu_tpSolicitacao" class="solicitacaoMetricas_nu_tpSolicitacao"><div class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->nu_tpSolicitacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $solicitacaoMetricas->SortUrl($solicitacaoMetricas->nu_tpSolicitacao) ?>',2);"><div id="elh_solicitacaoMetricas_nu_tpSolicitacao" class="solicitacaoMetricas_nu_tpSolicitacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->nu_tpSolicitacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacaoMetricas->nu_tpSolicitacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacaoMetricas->nu_tpSolicitacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacaoMetricas->nu_projeto->Visible) { // nu_projeto ?>
	<?php if ($solicitacaoMetricas->SortUrl($solicitacaoMetricas->nu_projeto) == "") { ?>
		<td><div id="elh_solicitacaoMetricas_nu_projeto" class="solicitacaoMetricas_nu_projeto"><div class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->nu_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $solicitacaoMetricas->SortUrl($solicitacaoMetricas->nu_projeto) ?>',2);"><div id="elh_solicitacaoMetricas_nu_projeto" class="solicitacaoMetricas_nu_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->nu_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacaoMetricas->nu_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacaoMetricas->nu_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacaoMetricas->ic_stSolicitacao->Visible) { // ic_stSolicitacao ?>
	<?php if ($solicitacaoMetricas->SortUrl($solicitacaoMetricas->ic_stSolicitacao) == "") { ?>
		<td><div id="elh_solicitacaoMetricas_ic_stSolicitacao" class="solicitacaoMetricas_ic_stSolicitacao"><div class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->ic_stSolicitacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $solicitacaoMetricas->SortUrl($solicitacaoMetricas->ic_stSolicitacao) ?>',2);"><div id="elh_solicitacaoMetricas_ic_stSolicitacao" class="solicitacaoMetricas_ic_stSolicitacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->ic_stSolicitacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacaoMetricas->ic_stSolicitacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacaoMetricas->ic_stSolicitacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacaoMetricas->nu_usuarioAlterou->Visible) { // nu_usuarioAlterou ?>
	<?php if ($solicitacaoMetricas->SortUrl($solicitacaoMetricas->nu_usuarioAlterou) == "") { ?>
		<td><div id="elh_solicitacaoMetricas_nu_usuarioAlterou" class="solicitacaoMetricas_nu_usuarioAlterou"><div class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->nu_usuarioAlterou->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $solicitacaoMetricas->SortUrl($solicitacaoMetricas->nu_usuarioAlterou) ?>',2);"><div id="elh_solicitacaoMetricas_nu_usuarioAlterou" class="solicitacaoMetricas_nu_usuarioAlterou">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->nu_usuarioAlterou->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacaoMetricas->nu_usuarioAlterou->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacaoMetricas->nu_usuarioAlterou->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacaoMetricas->dt_stSolicitacao->Visible) { // dt_stSolicitacao ?>
	<?php if ($solicitacaoMetricas->SortUrl($solicitacaoMetricas->dt_stSolicitacao) == "") { ?>
		<td><div id="elh_solicitacaoMetricas_dt_stSolicitacao" class="solicitacaoMetricas_dt_stSolicitacao"><div class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->dt_stSolicitacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $solicitacaoMetricas->SortUrl($solicitacaoMetricas->dt_stSolicitacao) ?>',2);"><div id="elh_solicitacaoMetricas_dt_stSolicitacao" class="solicitacaoMetricas_dt_stSolicitacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->dt_stSolicitacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacaoMetricas->dt_stSolicitacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacaoMetricas->dt_stSolicitacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacaoMetricas->qt_pfTotal->Visible) { // qt_pfTotal ?>
	<?php if ($solicitacaoMetricas->SortUrl($solicitacaoMetricas->qt_pfTotal) == "") { ?>
		<td><div id="elh_solicitacaoMetricas_qt_pfTotal" class="solicitacaoMetricas_qt_pfTotal"><div class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->qt_pfTotal->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $solicitacaoMetricas->SortUrl($solicitacaoMetricas->qt_pfTotal) ?>',2);"><div id="elh_solicitacaoMetricas_qt_pfTotal" class="solicitacaoMetricas_qt_pfTotal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->qt_pfTotal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacaoMetricas->qt_pfTotal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacaoMetricas->qt_pfTotal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($solicitacaoMetricas->vr_pfContForn->Visible) { // vr_pfContForn ?>
	<?php if ($solicitacaoMetricas->SortUrl($solicitacaoMetricas->vr_pfContForn) == "") { ?>
		<td><div id="elh_solicitacaoMetricas_vr_pfContForn" class="solicitacaoMetricas_vr_pfContForn"><div class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->vr_pfContForn->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $solicitacaoMetricas->SortUrl($solicitacaoMetricas->vr_pfContForn) ?>',2);"><div id="elh_solicitacaoMetricas_vr_pfContForn" class="solicitacaoMetricas_vr_pfContForn">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $solicitacaoMetricas->vr_pfContForn->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($solicitacaoMetricas->vr_pfContForn->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($solicitacaoMetricas->vr_pfContForn->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$solicitacaoMetricas_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($solicitacaoMetricas->ExportAll && $solicitacaoMetricas->Export <> "") {
	$solicitacaoMetricas_list->StopRec = $solicitacaoMetricas_list->TotalRecs;
} else {

	// Set the last record to display
	if ($solicitacaoMetricas_list->TotalRecs > $solicitacaoMetricas_list->StartRec + $solicitacaoMetricas_list->DisplayRecs - 1)
		$solicitacaoMetricas_list->StopRec = $solicitacaoMetricas_list->StartRec + $solicitacaoMetricas_list->DisplayRecs - 1;
	else
		$solicitacaoMetricas_list->StopRec = $solicitacaoMetricas_list->TotalRecs;
}
$solicitacaoMetricas_list->RecCnt = $solicitacaoMetricas_list->StartRec - 1;
if ($solicitacaoMetricas_list->Recordset && !$solicitacaoMetricas_list->Recordset->EOF) {
	$solicitacaoMetricas_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $solicitacaoMetricas_list->StartRec > 1)
		$solicitacaoMetricas_list->Recordset->Move($solicitacaoMetricas_list->StartRec - 1);
} elseif (!$solicitacaoMetricas->AllowAddDeleteRow && $solicitacaoMetricas_list->StopRec == 0) {
	$solicitacaoMetricas_list->StopRec = $solicitacaoMetricas->GridAddRowCount;
}

// Initialize aggregate
$solicitacaoMetricas->RowType = EW_ROWTYPE_AGGREGATEINIT;
$solicitacaoMetricas->ResetAttrs();
$solicitacaoMetricas_list->RenderRow();
while ($solicitacaoMetricas_list->RecCnt < $solicitacaoMetricas_list->StopRec) {
	$solicitacaoMetricas_list->RecCnt++;
	if (intval($solicitacaoMetricas_list->RecCnt) >= intval($solicitacaoMetricas_list->StartRec)) {
		$solicitacaoMetricas_list->RowCnt++;

		// Set up key count
		$solicitacaoMetricas_list->KeyCount = $solicitacaoMetricas_list->RowIndex;

		// Init row class and style
		$solicitacaoMetricas->ResetAttrs();
		$solicitacaoMetricas->CssClass = "";
		if ($solicitacaoMetricas->CurrentAction == "gridadd") {
		} else {
			$solicitacaoMetricas_list->LoadRowValues($solicitacaoMetricas_list->Recordset); // Load row values
		}
		$solicitacaoMetricas->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$solicitacaoMetricas->RowAttrs = array_merge($solicitacaoMetricas->RowAttrs, array('data-rowindex'=>$solicitacaoMetricas_list->RowCnt, 'id'=>'r' . $solicitacaoMetricas_list->RowCnt . '_solicitacaoMetricas', 'data-rowtype'=>$solicitacaoMetricas->RowType));

		// Render row
		$solicitacaoMetricas_list->RenderRow();

		// Render list options
		$solicitacaoMetricas_list->RenderListOptions();
?>
	<tr<?php echo $solicitacaoMetricas->RowAttributes() ?>>
<?php

// Render list options (body, left)
$solicitacaoMetricas_list->ListOptions->Render("body", "left", $solicitacaoMetricas_list->RowCnt);
?>
	<?php if ($solicitacaoMetricas->nu_solMetricas->Visible) { // nu_solMetricas ?>
		<td<?php echo $solicitacaoMetricas->nu_solMetricas->CellAttributes() ?>>
<span<?php echo $solicitacaoMetricas->nu_solMetricas->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_solMetricas->ListViewValue() ?></span>
<a id="<?php echo $solicitacaoMetricas_list->PageObjName . "_row_" . $solicitacaoMetricas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacaoMetricas->nu_tpSolicitacao->Visible) { // nu_tpSolicitacao ?>
		<td<?php echo $solicitacaoMetricas->nu_tpSolicitacao->CellAttributes() ?>>
<span<?php echo $solicitacaoMetricas->nu_tpSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_tpSolicitacao->ListViewValue() ?></span>
<a id="<?php echo $solicitacaoMetricas_list->PageObjName . "_row_" . $solicitacaoMetricas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacaoMetricas->nu_projeto->Visible) { // nu_projeto ?>
		<td<?php echo $solicitacaoMetricas->nu_projeto->CellAttributes() ?>>
<span<?php echo $solicitacaoMetricas->nu_projeto->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_projeto->ListViewValue() ?></span>
<a id="<?php echo $solicitacaoMetricas_list->PageObjName . "_row_" . $solicitacaoMetricas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacaoMetricas->ic_stSolicitacao->Visible) { // ic_stSolicitacao ?>
		<td<?php echo $solicitacaoMetricas->ic_stSolicitacao->CellAttributes() ?>>
<span<?php echo $solicitacaoMetricas->ic_stSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->ic_stSolicitacao->ListViewValue() ?></span>
<a id="<?php echo $solicitacaoMetricas_list->PageObjName . "_row_" . $solicitacaoMetricas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacaoMetricas->nu_usuarioAlterou->Visible) { // nu_usuarioAlterou ?>
		<td<?php echo $solicitacaoMetricas->nu_usuarioAlterou->CellAttributes() ?>>
<span<?php echo $solicitacaoMetricas->nu_usuarioAlterou->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_usuarioAlterou->ListViewValue() ?></span>
<a id="<?php echo $solicitacaoMetricas_list->PageObjName . "_row_" . $solicitacaoMetricas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacaoMetricas->dt_stSolicitacao->Visible) { // dt_stSolicitacao ?>
		<td<?php echo $solicitacaoMetricas->dt_stSolicitacao->CellAttributes() ?>>
<span<?php echo $solicitacaoMetricas->dt_stSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->dt_stSolicitacao->ListViewValue() ?></span>
<a id="<?php echo $solicitacaoMetricas_list->PageObjName . "_row_" . $solicitacaoMetricas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacaoMetricas->qt_pfTotal->Visible) { // qt_pfTotal ?>
		<td<?php echo $solicitacaoMetricas->qt_pfTotal->CellAttributes() ?>>
<span<?php echo $solicitacaoMetricas->qt_pfTotal->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->qt_pfTotal->ListViewValue() ?></span>
<a id="<?php echo $solicitacaoMetricas_list->PageObjName . "_row_" . $solicitacaoMetricas_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($solicitacaoMetricas->vr_pfContForn->Visible) { // vr_pfContForn ?>
		<td<?php echo $solicitacaoMetricas->vr_pfContForn->CellAttributes() ?>>
<span<?php echo $solicitacaoMetricas->vr_pfContForn->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->vr_pfContForn->ListViewValue() ?></span>
<a id="<?php echo $solicitacaoMetricas_list->PageObjName . "_row_" . $solicitacaoMetricas_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$solicitacaoMetricas_list->ListOptions->Render("body", "right", $solicitacaoMetricas_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($solicitacaoMetricas->CurrentAction <> "gridadd")
		$solicitacaoMetricas_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($solicitacaoMetricas->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($solicitacaoMetricas_list->Recordset)
	$solicitacaoMetricas_list->Recordset->Close();
?>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($solicitacaoMetricas->CurrentAction <> "gridadd" && $solicitacaoMetricas->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($solicitacaoMetricas_list->Pager)) $solicitacaoMetricas_list->Pager = new cNumericPager($solicitacaoMetricas_list->StartRec, $solicitacaoMetricas_list->DisplayRecs, $solicitacaoMetricas_list->TotalRecs, $solicitacaoMetricas_list->RecRange) ?>
<?php if ($solicitacaoMetricas_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($solicitacaoMetricas_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $solicitacaoMetricas_list->PageUrl() ?>start=<?php echo $solicitacaoMetricas_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($solicitacaoMetricas_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $solicitacaoMetricas_list->PageUrl() ?>start=<?php echo $solicitacaoMetricas_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($solicitacaoMetricas_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $solicitacaoMetricas_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($solicitacaoMetricas_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $solicitacaoMetricas_list->PageUrl() ?>start=<?php echo $solicitacaoMetricas_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($solicitacaoMetricas_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $solicitacaoMetricas_list->PageUrl() ?>start=<?php echo $solicitacaoMetricas_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($solicitacaoMetricas_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $solicitacaoMetricas_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $solicitacaoMetricas_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $solicitacaoMetricas_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($solicitacaoMetricas_list->SearchWhere == "0=101") { ?>
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
	foreach ($solicitacaoMetricas_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<script type="text/javascript">
fsolicitacaoMetricaslistsrch.Init();
fsolicitacaoMetricaslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$solicitacaoMetricas_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$solicitacaoMetricas_list->Page_Terminate();
?>
