<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "contagempfinfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "contagempf_agrupadorgridcls.php" ?>
<?php include_once "contagempf_funcaogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$contagempf_list = NULL; // Initialize page object first

class ccontagempf_list extends ccontagempf {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contagempf';

	// Page object name
	var $PageObjName = 'contagempf_list';

	// Grid form hidden field names
	var $FormName = 'fcontagempflist';
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

		// Table object (contagempf)
		if (!isset($GLOBALS["contagempf"])) {
			$GLOBALS["contagempf"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contagempf"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "contagempfadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "contagempfdelete.php";
		$this->MultiUpdateUrl = "contagempfupdate.php";

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS['solicitacaoMetricas'])) $GLOBALS['solicitacaoMetricas'] = new csolicitacaoMetricas();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contagempf', TRUE);

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
		$this->nu_contagem->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->nu_usuarioLogado->Visible = !$this->IsAddOrEdit();

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

			// Set up master detail parameters
			$this->SetUpMasterParms();

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "solicitacaoMetricas") {
			global $solicitacaoMetricas;
			$rsmaster = $solicitacaoMetricas->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("solicitacaometricaslist.php"); // Return to master page
			} else {
				$solicitacaoMetricas->LoadListRowValues($rsmaster);
				$solicitacaoMetricas->RowType = EW_ROWTYPE_MASTER; // Master row
				$solicitacaoMetricas->RenderListRow();
				$rsmaster->Close();
			}
		}

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
			$this->nu_contagem->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_contagem->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_contagem, FALSE); // nu_contagem
		$this->BuildSearchSql($sWhere, $this->nu_solMetricas, FALSE); // nu_solMetricas
		$this->BuildSearchSql($sWhere, $this->nu_tpMetrica, FALSE); // nu_tpMetrica
		$this->BuildSearchSql($sWhere, $this->nu_tpContagem, FALSE); // nu_tpContagem
		$this->BuildSearchSql($sWhere, $this->nu_proposito, FALSE); // nu_proposito
		$this->BuildSearchSql($sWhere, $this->nu_sistema, FALSE); // nu_sistema
		$this->BuildSearchSql($sWhere, $this->nu_ambiente, FALSE); // nu_ambiente
		$this->BuildSearchSql($sWhere, $this->nu_metodologia, FALSE); // nu_metodologia
		$this->BuildSearchSql($sWhere, $this->nu_roteiro, FALSE); // nu_roteiro
		$this->BuildSearchSql($sWhere, $this->nu_faseMedida, FALSE); // nu_faseMedida
		$this->BuildSearchSql($sWhere, $this->nu_usuarioLogado, FALSE); // nu_usuarioLogado
		$this->BuildSearchSql($sWhere, $this->dh_inicio, FALSE); // dh_inicio
		$this->BuildSearchSql($sWhere, $this->ic_stContagem, FALSE); // ic_stContagem
		$this->BuildSearchSql($sWhere, $this->ar_fasesRoteiro, TRUE); // ar_fasesRoteiro
		$this->BuildSearchSql($sWhere, $this->pc_varFasesRoteiro, FALSE); // pc_varFasesRoteiro
		$this->BuildSearchSql($sWhere, $this->vr_pfFaturamento, FALSE); // vr_pfFaturamento

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_contagem->AdvancedSearch->Save(); // nu_contagem
			$this->nu_solMetricas->AdvancedSearch->Save(); // nu_solMetricas
			$this->nu_tpMetrica->AdvancedSearch->Save(); // nu_tpMetrica
			$this->nu_tpContagem->AdvancedSearch->Save(); // nu_tpContagem
			$this->nu_proposito->AdvancedSearch->Save(); // nu_proposito
			$this->nu_sistema->AdvancedSearch->Save(); // nu_sistema
			$this->nu_ambiente->AdvancedSearch->Save(); // nu_ambiente
			$this->nu_metodologia->AdvancedSearch->Save(); // nu_metodologia
			$this->nu_roteiro->AdvancedSearch->Save(); // nu_roteiro
			$this->nu_faseMedida->AdvancedSearch->Save(); // nu_faseMedida
			$this->nu_usuarioLogado->AdvancedSearch->Save(); // nu_usuarioLogado
			$this->dh_inicio->AdvancedSearch->Save(); // dh_inicio
			$this->ic_stContagem->AdvancedSearch->Save(); // ic_stContagem
			$this->ar_fasesRoteiro->AdvancedSearch->Save(); // ar_fasesRoteiro
			$this->pc_varFasesRoteiro->AdvancedSearch->Save(); // pc_varFasesRoteiro
			$this->vr_pfFaturamento->AdvancedSearch->Save(); // vr_pfFaturamento
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
		if ($this->nu_contagem->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_solMetricas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_tpMetrica->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_tpContagem->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_proposito->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_sistema->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_ambiente->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_metodologia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_roteiro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_faseMedida->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_usuarioLogado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dh_inicio->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_stContagem->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ar_fasesRoteiro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pc_varFasesRoteiro->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_pfFaturamento->AdvancedSearch->IssetSession())
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
		$this->nu_contagem->AdvancedSearch->UnsetSession();
		$this->nu_solMetricas->AdvancedSearch->UnsetSession();
		$this->nu_tpMetrica->AdvancedSearch->UnsetSession();
		$this->nu_tpContagem->AdvancedSearch->UnsetSession();
		$this->nu_proposito->AdvancedSearch->UnsetSession();
		$this->nu_sistema->AdvancedSearch->UnsetSession();
		$this->nu_ambiente->AdvancedSearch->UnsetSession();
		$this->nu_metodologia->AdvancedSearch->UnsetSession();
		$this->nu_roteiro->AdvancedSearch->UnsetSession();
		$this->nu_faseMedida->AdvancedSearch->UnsetSession();
		$this->nu_usuarioLogado->AdvancedSearch->UnsetSession();
		$this->dh_inicio->AdvancedSearch->UnsetSession();
		$this->ic_stContagem->AdvancedSearch->UnsetSession();
		$this->ar_fasesRoteiro->AdvancedSearch->UnsetSession();
		$this->pc_varFasesRoteiro->AdvancedSearch->UnsetSession();
		$this->vr_pfFaturamento->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_contagem->AdvancedSearch->Load();
		$this->nu_solMetricas->AdvancedSearch->Load();
		$this->nu_tpMetrica->AdvancedSearch->Load();
		$this->nu_tpContagem->AdvancedSearch->Load();
		$this->nu_proposito->AdvancedSearch->Load();
		$this->nu_sistema->AdvancedSearch->Load();
		$this->nu_ambiente->AdvancedSearch->Load();
		$this->nu_metodologia->AdvancedSearch->Load();
		$this->nu_roteiro->AdvancedSearch->Load();
		$this->nu_faseMedida->AdvancedSearch->Load();
		$this->nu_usuarioLogado->AdvancedSearch->Load();
		$this->dh_inicio->AdvancedSearch->Load();
		$this->ic_stContagem->AdvancedSearch->Load();
		$this->ar_fasesRoteiro->AdvancedSearch->Load();
		$this->pc_varFasesRoteiro->AdvancedSearch->Load();
		$this->vr_pfFaturamento->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_contagem, $bCtrl); // nu_contagem
			$this->UpdateSort($this->nu_tpMetrica, $bCtrl); // nu_tpMetrica
			$this->UpdateSort($this->nu_tpContagem, $bCtrl); // nu_tpContagem
			$this->UpdateSort($this->nu_sistema, $bCtrl); // nu_sistema
			$this->UpdateSort($this->nu_faseMedida, $bCtrl); // nu_faseMedida
			$this->UpdateSort($this->nu_usuarioLogado, $bCtrl); // nu_usuarioLogado
			$this->UpdateSort($this->ic_stContagem, $bCtrl); // ic_stContagem
			$this->UpdateSort($this->pc_varFasesRoteiro, $bCtrl); // pc_varFasesRoteiro
			$this->UpdateSort($this->vr_pfFaturamento, $bCtrl); // vr_pfFaturamento
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->nu_solMetricas->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->nu_contagem->setSort("");
				$this->nu_tpMetrica->setSort("");
				$this->nu_tpContagem->setSort("");
				$this->nu_sistema->setSort("");
				$this->nu_faseMedida->setSort("");
				$this->nu_usuarioLogado->setSort("");
				$this->ic_stContagem->setSort("");
				$this->pc_varFasesRoteiro->setSort("");
				$this->vr_pfFaturamento->setSort("");
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

		// "detail_contagempf_agrupador"
		$item = &$this->ListOptions->Add("detail_contagempf_agrupador");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'contagempf_agrupador') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["contagempf_agrupador_grid"])) $GLOBALS["contagempf_agrupador_grid"] = new ccontagempf_agrupador_grid;

		// "detail_contagempf_funcao"
		$item = &$this->ListOptions->Add("detail_contagempf_funcao");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'contagempf_funcao') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["contagempf_funcao_grid"])) $GLOBALS["contagempf_funcao_grid"] = new ccontagempf_funcao_grid;

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

		// "detail_contagempf_agrupador"
		$oListOpt = &$this->ListOptions->Items["detail_contagempf_agrupador"];
		if ($Security->AllowList(CurrentProjectID() . 'contagempf_agrupador')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("contagempf_agrupador", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("contagempf_agrupadorlist.php?" . EW_TABLE_SHOW_MASTER . "=contagempf&nu_contagem=" . strval($this->nu_contagem->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["contagempf_agrupador_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'contagempf_agrupador')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=contagempf_agrupador")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "contagempf_agrupador";
			}
			if ($GLOBALS["contagempf_agrupador_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'contagempf_agrupador')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=contagempf_agrupador")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "contagempf_agrupador";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_contagempf_funcao"
		$oListOpt = &$this->ListOptions->Items["detail_contagempf_funcao"];
		if ($Security->AllowList(CurrentProjectID() . 'contagempf_funcao')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("contagempf_funcao", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("contagempf_funcaolist.php?" . EW_TABLE_SHOW_MASTER . "=contagempf&nu_contagem=" . strval($this->nu_contagem->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["contagempf_funcao_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'contagempf_funcao')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=contagempf_funcao")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "contagempf_funcao";
			}
			if ($GLOBALS["contagempf_funcao_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'contagempf_funcao')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=contagempf_funcao")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "contagempf_funcao";
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
		$item = &$option->Add("detailadd_contagempf_agrupador");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=contagempf_agrupador") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["contagempf_agrupador"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["contagempf_agrupador"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'contagempf_agrupador') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "contagempf_agrupador";
		}
		$item = &$option->Add("detailadd_contagempf_funcao");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=contagempf_funcao") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["contagempf_funcao"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["contagempf_funcao"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'contagempf_funcao') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "contagempf_funcao";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fcontagempflist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_contagem

		$this->nu_contagem->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_contagem"]);
		if ($this->nu_contagem->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_contagem->AdvancedSearch->SearchOperator = @$_GET["z_nu_contagem"];

		// nu_solMetricas
		$this->nu_solMetricas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_solMetricas"]);
		if ($this->nu_solMetricas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_solMetricas->AdvancedSearch->SearchOperator = @$_GET["z_nu_solMetricas"];

		// nu_tpMetrica
		$this->nu_tpMetrica->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_tpMetrica"]);
		if ($this->nu_tpMetrica->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_tpMetrica->AdvancedSearch->SearchOperator = @$_GET["z_nu_tpMetrica"];

		// nu_tpContagem
		$this->nu_tpContagem->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_tpContagem"]);
		if ($this->nu_tpContagem->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_tpContagem->AdvancedSearch->SearchOperator = @$_GET["z_nu_tpContagem"];

		// nu_proposito
		$this->nu_proposito->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_proposito"]);
		if ($this->nu_proposito->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_proposito->AdvancedSearch->SearchOperator = @$_GET["z_nu_proposito"];

		// nu_sistema
		$this->nu_sistema->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_sistema"]);
		if ($this->nu_sistema->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_sistema->AdvancedSearch->SearchOperator = @$_GET["z_nu_sistema"];

		// nu_ambiente
		$this->nu_ambiente->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_ambiente"]);
		if ($this->nu_ambiente->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_ambiente->AdvancedSearch->SearchOperator = @$_GET["z_nu_ambiente"];

		// nu_metodologia
		$this->nu_metodologia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_metodologia"]);
		if ($this->nu_metodologia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_metodologia->AdvancedSearch->SearchOperator = @$_GET["z_nu_metodologia"];

		// nu_roteiro
		$this->nu_roteiro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_roteiro"]);
		if ($this->nu_roteiro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_roteiro->AdvancedSearch->SearchOperator = @$_GET["z_nu_roteiro"];

		// nu_faseMedida
		$this->nu_faseMedida->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_faseMedida"]);
		if ($this->nu_faseMedida->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_faseMedida->AdvancedSearch->SearchOperator = @$_GET["z_nu_faseMedida"];

		// nu_usuarioLogado
		$this->nu_usuarioLogado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_usuarioLogado"]);
		if ($this->nu_usuarioLogado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_usuarioLogado->AdvancedSearch->SearchOperator = @$_GET["z_nu_usuarioLogado"];

		// dh_inicio
		$this->dh_inicio->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dh_inicio"]);
		if ($this->dh_inicio->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dh_inicio->AdvancedSearch->SearchOperator = @$_GET["z_dh_inicio"];

		// ic_stContagem
		$this->ic_stContagem->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_stContagem"]);
		if ($this->ic_stContagem->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_stContagem->AdvancedSearch->SearchOperator = @$_GET["z_ic_stContagem"];

		// ar_fasesRoteiro
		$this->ar_fasesRoteiro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ar_fasesRoteiro"]);
		if ($this->ar_fasesRoteiro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ar_fasesRoteiro->AdvancedSearch->SearchOperator = @$_GET["z_ar_fasesRoteiro"];
		if (is_array($this->ar_fasesRoteiro->AdvancedSearch->SearchValue)) $this->ar_fasesRoteiro->AdvancedSearch->SearchValue = implode(",", $this->ar_fasesRoteiro->AdvancedSearch->SearchValue);
		if (is_array($this->ar_fasesRoteiro->AdvancedSearch->SearchValue2)) $this->ar_fasesRoteiro->AdvancedSearch->SearchValue2 = implode(",", $this->ar_fasesRoteiro->AdvancedSearch->SearchValue2);

		// pc_varFasesRoteiro
		$this->pc_varFasesRoteiro->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pc_varFasesRoteiro"]);
		if ($this->pc_varFasesRoteiro->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pc_varFasesRoteiro->AdvancedSearch->SearchOperator = @$_GET["z_pc_varFasesRoteiro"];

		// vr_pfFaturamento
		$this->vr_pfFaturamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_pfFaturamento"]);
		if ($this->vr_pfFaturamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_pfFaturamento->AdvancedSearch->SearchOperator = @$_GET["z_vr_pfFaturamento"];
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
		$this->nu_contagem->setDbValue($rs->fields('nu_contagem'));
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->nu_tpContagem->setDbValue($rs->fields('nu_tpContagem'));
		$this->nu_proposito->setDbValue($rs->fields('nu_proposito'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->nu_metodologia->setDbValue($rs->fields('nu_metodologia'));
		$this->nu_roteiro->setDbValue($rs->fields('nu_roteiro'));
		$this->nu_faseMedida->setDbValue($rs->fields('nu_faseMedida'));
		$this->nu_usuarioLogado->setDbValue($rs->fields('nu_usuarioLogado'));
		$this->dh_inicio->setDbValue($rs->fields('dh_inicio'));
		$this->ic_stContagem->setDbValue($rs->fields('ic_stContagem'));
		$this->ar_fasesRoteiro->setDbValue($rs->fields('ar_fasesRoteiro'));
		$this->pc_varFasesRoteiro->setDbValue($rs->fields('pc_varFasesRoteiro'));
		$this->vr_pfFaturamento->setDbValue($rs->fields('vr_pfFaturamento'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contagem->DbValue = $row['nu_contagem'];
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_tpMetrica->DbValue = $row['nu_tpMetrica'];
		$this->nu_tpContagem->DbValue = $row['nu_tpContagem'];
		$this->nu_proposito->DbValue = $row['nu_proposito'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->nu_metodologia->DbValue = $row['nu_metodologia'];
		$this->nu_roteiro->DbValue = $row['nu_roteiro'];
		$this->nu_faseMedida->DbValue = $row['nu_faseMedida'];
		$this->nu_usuarioLogado->DbValue = $row['nu_usuarioLogado'];
		$this->dh_inicio->DbValue = $row['dh_inicio'];
		$this->ic_stContagem->DbValue = $row['ic_stContagem'];
		$this->ar_fasesRoteiro->DbValue = $row['ar_fasesRoteiro'];
		$this->pc_varFasesRoteiro->DbValue = $row['pc_varFasesRoteiro'];
		$this->vr_pfFaturamento->DbValue = $row['vr_pfFaturamento'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_contagem")) <> "")
			$this->nu_contagem->CurrentValue = $this->getKey("nu_contagem"); // nu_contagem
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
		if ($this->vr_pfFaturamento->FormValue == $this->vr_pfFaturamento->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pfFaturamento->CurrentValue)))
			$this->vr_pfFaturamento->CurrentValue = ew_StrToFloat($this->vr_pfFaturamento->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_contagem
		// nu_solMetricas
		// nu_tpMetrica
		// nu_tpContagem
		// nu_proposito
		// nu_sistema
		// nu_ambiente
		// nu_metodologia
		// nu_roteiro
		// nu_faseMedida
		// nu_usuarioLogado
		// dh_inicio
		// ic_stContagem
		// ar_fasesRoteiro
		// pc_varFasesRoteiro
		// vr_pfFaturamento
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_contagem
			$this->nu_contagem->ViewValue = $this->nu_contagem->CurrentValue;
			$this->nu_contagem->ViewCustomAttributes = "";

			// nu_solMetricas
			if (strval($this->nu_solMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solMetricas->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
				}
			} else {
				$this->nu_solMetricas->ViewValue = NULL;
			}
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_tpMetrica
			if (strval($this->nu_tpMetrica->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_tpMetrica->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo] = 'S'";
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

			// nu_tpContagem
			if (strval($this->nu_tpContagem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpContagem]" . ew_SearchString("=", $this->nu_tpContagem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpContagem], [no_tpContagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpcontagem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpContagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpContagem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpContagem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpContagem->ViewValue = $this->nu_tpContagem->CurrentValue;
				}
			} else {
				$this->nu_tpContagem->ViewValue = NULL;
			}
			$this->nu_tpContagem->ViewCustomAttributes = "";

			// nu_proposito
			if (strval($this->nu_proposito->CurrentValue) <> "") {
				$sFilterWrk = "[nu_proposito]" . ew_SearchString("=", $this->nu_proposito->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_proposito], [no_proposito] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[proposito]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_proposito, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_proposito] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_proposito->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_proposito->ViewValue = $this->nu_proposito->CurrentValue;
				}
			} else {
				$this->nu_proposito->ViewValue = NULL;
			}
			$this->nu_proposito->ViewCustomAttributes = "";

			// nu_sistema
			if (strval($this->nu_sistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_sistema->ViewValue = $rswrk->fields('DispFld');
					$this->nu_sistema->ViewValue .= ew_ValueSeparator(1,$this->nu_sistema) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_sistema->ViewValue = $this->nu_sistema->CurrentValue;
				}
			} else {
				$this->nu_sistema->ViewValue = NULL;
			}
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_ambiente
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambiente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
				}
			} else {
				$this->nu_ambiente->ViewValue = NULL;
			}
			$this->nu_ambiente->ViewCustomAttributes = "";

			// nu_metodologia
			if (strval($this->nu_metodologia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_metodologia]" . ew_SearchString("=", $this->nu_metodologia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_metodologia], [no_metodologia] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metodologia]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_metodologia] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_metodologia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metodologia->ViewValue = $this->nu_metodologia->CurrentValue;
				}
			} else {
				$this->nu_metodologia->ViewValue = NULL;
			}
			$this->nu_metodologia->ViewCustomAttributes = "";

			// nu_roteiro
			if (strval($this->nu_roteiro->CurrentValue) <> "") {
				$sFilterWrk = "[nu_roteiro]" . ew_SearchString("=", $this->nu_roteiro->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_roteiro], [no_roteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[roteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_roteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_roteiro] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_roteiro->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_roteiro->ViewValue = $this->nu_roteiro->CurrentValue;
				}
			} else {
				$this->nu_roteiro->ViewValue = NULL;
			}
			$this->nu_roteiro->ViewCustomAttributes = "";

			// nu_faseMedida
			if (strval($this->nu_faseMedida->CurrentValue) <> "") {
				$sFilterWrk = "[nu_faseRoteiro]" . ew_SearchString("=", $this->nu_faseMedida->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_faseMedida, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_faseMedida->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_faseMedida->ViewValue = $this->nu_faseMedida->CurrentValue;
				}
			} else {
				$this->nu_faseMedida->ViewValue = NULL;
			}
			$this->nu_faseMedida->ViewCustomAttributes = "";

			// nu_usuarioLogado
			if (strval($this->nu_usuarioLogado->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioLogado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioLogado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioLogado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioLogado->ViewValue = $this->nu_usuarioLogado->CurrentValue;
				}
			} else {
				$this->nu_usuarioLogado->ViewValue = NULL;
			}
			$this->nu_usuarioLogado->ViewCustomAttributes = "";

			// dh_inicio
			$this->dh_inicio->ViewValue = $this->dh_inicio->CurrentValue;
			$this->dh_inicio->ViewValue = ew_FormatDateTime($this->dh_inicio->ViewValue, 7);
			$this->dh_inicio->ViewCustomAttributes = "";

			// ic_stContagem
			if (strval($this->ic_stContagem->CurrentValue) <> "") {
				switch ($this->ic_stContagem->CurrentValue) {
					case $this->ic_stContagem->FldTagValue(1):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(1) <> "" ? $this->ic_stContagem->FldTagCaption(1) : $this->ic_stContagem->CurrentValue;
						break;
					case $this->ic_stContagem->FldTagValue(2):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(2) <> "" ? $this->ic_stContagem->FldTagCaption(2) : $this->ic_stContagem->CurrentValue;
						break;
					case $this->ic_stContagem->FldTagValue(3):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(3) <> "" ? $this->ic_stContagem->FldTagCaption(3) : $this->ic_stContagem->CurrentValue;
						break;
					case $this->ic_stContagem->FldTagValue(4):
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->FldTagCaption(4) <> "" ? $this->ic_stContagem->FldTagCaption(4) : $this->ic_stContagem->CurrentValue;
						break;
					default:
						$this->ic_stContagem->ViewValue = $this->ic_stContagem->CurrentValue;
				}
			} else {
				$this->ic_stContagem->ViewValue = NULL;
			}
			$this->ic_stContagem->ViewCustomAttributes = "";

			// ar_fasesRoteiro
			if (strval($this->ar_fasesRoteiro->CurrentValue) <> "") {
				$arwrk = explode(",", $this->ar_fasesRoteiro->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "[nu_faseRoteiro]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
				}	
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->ar_fasesRoteiro, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->ar_fasesRoteiro->ViewValue = "";
					$ari = 0;
					while (!$rswrk->EOF) {
						$this->ar_fasesRoteiro->ViewValue .= $rswrk->fields('DispFld');
						$rswrk->MoveNext();
						if (!$rswrk->EOF) $this->ar_fasesRoteiro->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
						$ari++;
					}
					$rswrk->Close();
				} else {
					$this->ar_fasesRoteiro->ViewValue = $this->ar_fasesRoteiro->CurrentValue;
				}
			} else {
				$this->ar_fasesRoteiro->ViewValue = NULL;
			}
			$this->ar_fasesRoteiro->ViewCustomAttributes = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
			$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->ViewValue = $this->vr_pfFaturamento->CurrentValue;
			$this->vr_pfFaturamento->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_contagem
			$this->nu_contagem->LinkCustomAttributes = "";
			$this->nu_contagem->HrefValue = "";
			$this->nu_contagem->TooltipValue = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->LinkCustomAttributes = "";
			$this->nu_tpMetrica->HrefValue = "";
			$this->nu_tpMetrica->TooltipValue = "";

			// nu_tpContagem
			$this->nu_tpContagem->LinkCustomAttributes = "";
			$this->nu_tpContagem->HrefValue = "";
			$this->nu_tpContagem->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_faseMedida
			$this->nu_faseMedida->LinkCustomAttributes = "";
			$this->nu_faseMedida->HrefValue = "";
			$this->nu_faseMedida->TooltipValue = "";

			// nu_usuarioLogado
			$this->nu_usuarioLogado->LinkCustomAttributes = "";
			$this->nu_usuarioLogado->HrefValue = "";
			$this->nu_usuarioLogado->TooltipValue = "";

			// ic_stContagem
			$this->ic_stContagem->LinkCustomAttributes = "";
			$this->ic_stContagem->HrefValue = "";
			$this->ic_stContagem->TooltipValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
			$this->pc_varFasesRoteiro->HrefValue = "";
			$this->pc_varFasesRoteiro->TooltipValue = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->LinkCustomAttributes = "";
			$this->vr_pfFaturamento->HrefValue = "";
			$this->vr_pfFaturamento->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_contagem
			$this->nu_contagem->EditCustomAttributes = "";
			$this->nu_contagem->EditValue = ew_HtmlEncode($this->nu_contagem->AdvancedSearch->SearchValue);
			$this->nu_contagem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_contagem->FldCaption()));

			// nu_tpMetrica
			$this->nu_tpMetrica->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpMetrica], [no_tpMetrica] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpmetrica]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo] = 'S'";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpMetrica->EditValue = $arwrk;

			// nu_tpContagem
			$this->nu_tpContagem->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_tpContagem], [no_tpContagem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_tpMetrica] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpcontagem]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpContagem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpContagem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpContagem->EditValue = $arwrk;

			// nu_sistema
			$this->nu_sistema->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_sistema->EditValue = $arwrk;

			// nu_faseMedida
			$this->nu_faseMedida->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_faseRoteiro], [no_faseRoteiro] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_roteiro] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[faseroteiro]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_faseMedida, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_faseMedida->EditValue = $arwrk;

			// nu_usuarioLogado
			$this->nu_usuarioLogado->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["contagempf"]->UserIDAllow($GLOBALS["contagempf"]->CurrentAction)) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioLogado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioLogado->EditValue = $arwrk;

			// ic_stContagem
			$this->ic_stContagem->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stContagem->FldTagValue(1), $this->ic_stContagem->FldTagCaption(1) <> "" ? $this->ic_stContagem->FldTagCaption(1) : $this->ic_stContagem->FldTagValue(1));
			$arwrk[] = array($this->ic_stContagem->FldTagValue(2), $this->ic_stContagem->FldTagCaption(2) <> "" ? $this->ic_stContagem->FldTagCaption(2) : $this->ic_stContagem->FldTagValue(2));
			$arwrk[] = array($this->ic_stContagem->FldTagValue(3), $this->ic_stContagem->FldTagCaption(3) <> "" ? $this->ic_stContagem->FldTagCaption(3) : $this->ic_stContagem->FldTagValue(3));
			$arwrk[] = array($this->ic_stContagem->FldTagValue(4), $this->ic_stContagem->FldTagCaption(4) <> "" ? $this->ic_stContagem->FldTagCaption(4) : $this->ic_stContagem->FldTagValue(4));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_stContagem->EditValue = $arwrk;

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->EditCustomAttributes = "";
			$this->pc_varFasesRoteiro->EditValue = ew_HtmlEncode($this->pc_varFasesRoteiro->AdvancedSearch->SearchValue);
			$this->pc_varFasesRoteiro->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->pc_varFasesRoteiro->FldCaption()));

			// vr_pfFaturamento
			$this->vr_pfFaturamento->EditCustomAttributes = "";
			$this->vr_pfFaturamento->EditValue = ew_HtmlEncode($this->vr_pfFaturamento->AdvancedSearch->SearchValue);
			$this->vr_pfFaturamento->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_pfFaturamento->FldCaption()));
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
		if (!ew_CheckInteger($this->nu_contagem->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_contagem->FldErrMsg());
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
		$this->nu_contagem->AdvancedSearch->Load();
		$this->nu_solMetricas->AdvancedSearch->Load();
		$this->nu_tpMetrica->AdvancedSearch->Load();
		$this->nu_tpContagem->AdvancedSearch->Load();
		$this->nu_proposito->AdvancedSearch->Load();
		$this->nu_sistema->AdvancedSearch->Load();
		$this->nu_ambiente->AdvancedSearch->Load();
		$this->nu_metodologia->AdvancedSearch->Load();
		$this->nu_roteiro->AdvancedSearch->Load();
		$this->nu_faseMedida->AdvancedSearch->Load();
		$this->nu_usuarioLogado->AdvancedSearch->Load();
		$this->dh_inicio->AdvancedSearch->Load();
		$this->ic_stContagem->AdvancedSearch->Load();
		$this->ar_fasesRoteiro->AdvancedSearch->Load();
		$this->pc_varFasesRoteiro->AdvancedSearch->Load();
		$this->vr_pfFaturamento->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_contagempf\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_contagempf',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fcontagempflist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "solicitacaoMetricas") {
			global $solicitacaoMetricas;
			$rsmaster = $solicitacaoMetricas->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$solicitacaoMetricas->ExportDocument($ExportDoc, $rsmaster, 1, 1);
					$ExportDoc->ExportEmptyRow();
				}
				$ExportDoc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
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
		$this->AddSearchQueryString($sQry, $this->nu_contagem); // nu_contagem
		$this->AddSearchQueryString($sQry, $this->nu_solMetricas); // nu_solMetricas
		$this->AddSearchQueryString($sQry, $this->nu_tpMetrica); // nu_tpMetrica
		$this->AddSearchQueryString($sQry, $this->nu_tpContagem); // nu_tpContagem
		$this->AddSearchQueryString($sQry, $this->nu_proposito); // nu_proposito
		$this->AddSearchQueryString($sQry, $this->nu_sistema); // nu_sistema
		$this->AddSearchQueryString($sQry, $this->nu_ambiente); // nu_ambiente
		$this->AddSearchQueryString($sQry, $this->nu_metodologia); // nu_metodologia
		$this->AddSearchQueryString($sQry, $this->nu_roteiro); // nu_roteiro
		$this->AddSearchQueryString($sQry, $this->nu_faseMedida); // nu_faseMedida
		$this->AddSearchQueryString($sQry, $this->nu_usuarioLogado); // nu_usuarioLogado
		$this->AddSearchQueryString($sQry, $this->dh_inicio); // dh_inicio
		$this->AddSearchQueryString($sQry, $this->ic_stContagem); // ic_stContagem
		$this->AddSearchQueryString($sQry, $this->ar_fasesRoteiro); // ar_fasesRoteiro
		$this->AddSearchQueryString($sQry, $this->pc_varFasesRoteiro); // pc_varFasesRoteiro
		$this->AddSearchQueryString($sQry, $this->vr_pfFaturamento); // vr_pfFaturamento

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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "solicitacaoMetricas") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_solMetricas"] <> "") {
					$GLOBALS["solicitacaoMetricas"]->nu_solMetricas->setQueryStringValue($_GET["nu_solMetricas"]);
					$this->nu_solMetricas->setQueryStringValue($GLOBALS["solicitacaoMetricas"]->nu_solMetricas->QueryStringValue);
					$this->nu_solMetricas->setSessionValue($this->nu_solMetricas->QueryStringValue);
					if (!is_numeric($GLOBALS["solicitacaoMetricas"]->nu_solMetricas->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "solicitacaoMetricas") {
				if ($this->nu_solMetricas->QueryStringValue == "") $this->nu_solMetricas->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
		$table = 'contagempf';
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
if (!isset($contagempf_list)) $contagempf_list = new ccontagempf_list();

// Page init
$contagempf_list->Page_Init();

// Page main
$contagempf_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($contagempf->Export == "") { ?>
<script type="text/javascript">

// Page object
var contagempf_list = new ew_Page("contagempf_list");
contagempf_list.PageID = "list"; // Page ID
var EW_PAGE_ID = contagempf_list.PageID; // For backward compatibility

// Form object
var fcontagempflist = new ew_Form("fcontagempflist");
fcontagempflist.FormKeyCountName = '<?php echo $contagempf_list->FormKeyCountName ?>';

// Form_CustomValidate event
fcontagempflist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempflist.ValidateRequired = true;
<?php } else { ?>
fcontagempflist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontagempflist.Lists["x_nu_tpMetrica"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempflist.Lists["x_nu_tpContagem"] = {"LinkField":"x_nu_tpContagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpContagem","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempflist.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempflist.Lists["x_nu_faseMedida"] = {"LinkField":"x_nu_faseRoteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempflist.Lists["x_nu_usuarioLogado"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fcontagempflistsrch = new ew_Form("fcontagempflistsrch");

// Validate function for search
fcontagempflistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_contagem");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($contagempf->nu_contagem->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fcontagempflistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempflistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fcontagempflistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fcontagempflistsrch.Lists["x_nu_tpMetrica"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempflistsrch.Lists["x_nu_tpContagem"] = {"LinkField":"x_nu_tpContagem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpContagem","","",""],"ParentFields":["x_nu_tpMetrica"],"FilterFields":["x_nu_tpMetrica"],"Options":[]};
fcontagempflistsrch.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempflistsrch.Lists["x_nu_faseMedida"] = {"LinkField":"x_nu_faseRoteiro","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_faseRoteiro","","",""],"ParentFields":["x_nu_roteiro"],"FilterFields":["x_nu_roteiro"],"Options":[]};
fcontagempflistsrch.Lists["x_nu_usuarioLogado"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fcontagempflistsrch) fcontagempflistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($contagempf->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($contagempf->getCurrentMasterTable() == "" && $contagempf_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $contagempf_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($contagempf->Export == "") || (EW_EXPORT_MASTER_RECORD && $contagempf->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "solicitacaometricaslist.php";
if ($contagempf_list->DbMasterFilter <> "" && $contagempf->getCurrentMasterTable() == "solicitacaoMetricas") {
	if ($contagempf_list->MasterRecordExists) {
		if ($contagempf->getCurrentMasterTable() == $contagempf->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($contagempf_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $contagempf_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "solicitacaometricasmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$contagempf_list->TotalRecs = $contagempf->SelectRecordCount();
	} else {
		if ($contagempf_list->Recordset = $contagempf_list->LoadRecordset())
			$contagempf_list->TotalRecs = $contagempf_list->Recordset->RecordCount();
	}
	$contagempf_list->StartRec = 1;
	if ($contagempf_list->DisplayRecs <= 0 || ($contagempf->Export <> "" && $contagempf->ExportAll)) // Display all records
		$contagempf_list->DisplayRecs = $contagempf_list->TotalRecs;
	if (!($contagempf->Export <> "" && $contagempf->ExportAll))
		$contagempf_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$contagempf_list->Recordset = $contagempf_list->LoadRecordset($contagempf_list->StartRec-1, $contagempf_list->DisplayRecs);
$contagempf_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($contagempf->Export == "" && $contagempf->CurrentAction == "") { ?>
<form name="fcontagempflistsrch" id="fcontagempflistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fcontagempflistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fcontagempflistsrch_SearchGroup" href="#fcontagempflistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fcontagempflistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fcontagempflistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="contagempf">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$contagempf_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$contagempf->RowType = EW_ROWTYPE_SEARCH;

// Render row
$contagempf->ResetAttrs();
$contagempf_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($contagempf->nu_contagem->Visible) { // nu_contagem ?>
	<span id="xsc_nu_contagem" class="ewCell">
		<span class="ewSearchCaption"><?php echo $contagempf->nu_contagem->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_contagem" id="z_nu_contagem" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_contagem" name="x_nu_contagem" id="x_nu_contagem" placeholder="<?php echo $contagempf->nu_contagem->PlaceHolder ?>" value="<?php echo $contagempf->nu_contagem->EditValue ?>"<?php echo $contagempf->nu_contagem->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
	<span id="xsc_nu_tpMetrica" class="ewCell">
		<span class="ewSearchCaption"><?php echo $contagempf->nu_tpMetrica->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_tpMetrica" id="z_nu_tpMetrica" value="="></span>
		<span class="control-group ewSearchField">
<?php $contagempf->nu_tpMetrica->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_tpContagem']); " . @$contagempf->nu_tpMetrica->EditAttrs["onchange"]; ?>
<select data-field="x_nu_tpMetrica" id="x_nu_tpMetrica" name="x_nu_tpMetrica"<?php echo $contagempf->nu_tpMetrica->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpMetrica->EditValue)) {
	$arwrk = $contagempf->nu_tpMetrica->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpMetrica->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempflistsrch.Lists["x_nu_tpMetrica"].Options = <?php echo (is_array($contagempf->nu_tpMetrica->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpMetrica->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
	<span id="xsc_nu_tpContagem" class="ewCell">
		<span class="ewSearchCaption"><?php echo $contagempf->nu_tpContagem->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_tpContagem" id="z_nu_tpContagem" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_tpContagem" id="x_nu_tpContagem" name="x_nu_tpContagem"<?php echo $contagempf->nu_tpContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_tpContagem->EditValue)) {
	$arwrk = $contagempf->nu_tpContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_tpContagem->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempflistsrch.Lists["x_nu_tpContagem"].Options = <?php echo (is_array($contagempf->nu_tpContagem->EditValue)) ? ew_ArrayToJson($contagempf->nu_tpContagem->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($contagempf->nu_sistema->Visible) { // nu_sistema ?>
	<span id="xsc_nu_sistema" class="ewCell">
		<span class="ewSearchCaption"><?php echo $contagempf->nu_sistema->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_sistema" id="z_nu_sistema" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_sistema" id="x_nu_sistema" name="x_nu_sistema"<?php echo $contagempf->nu_sistema->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_sistema->EditValue)) {
	$arwrk = $contagempf->nu_sistema->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_sistema->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$contagempf->nu_sistema) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fcontagempflistsrch.Lists["x_nu_sistema"].Options = <?php echo (is_array($contagempf->nu_sistema->EditValue)) ? ew_ArrayToJson($contagempf->nu_sistema->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($contagempf->nu_faseMedida->Visible) { // nu_faseMedida ?>
	<span id="xsc_nu_faseMedida" class="ewCell">
		<span class="ewSearchCaption"><?php echo $contagempf->nu_faseMedida->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_faseMedida" id="z_nu_faseMedida" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_faseMedida" id="x_nu_faseMedida" name="x_nu_faseMedida"<?php echo $contagempf->nu_faseMedida->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_faseMedida->EditValue)) {
	$arwrk = $contagempf->nu_faseMedida->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_faseMedida->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempflistsrch.Lists["x_nu_faseMedida"].Options = <?php echo (is_array($contagempf->nu_faseMedida->EditValue)) ? ew_ArrayToJson($contagempf->nu_faseMedida->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($contagempf->nu_usuarioLogado->Visible) { // nu_usuarioLogado ?>
	<span id="xsc_nu_usuarioLogado" class="ewCell">
		<span class="ewSearchCaption"><?php echo $contagempf->nu_usuarioLogado->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_usuarioLogado" id="z_nu_usuarioLogado" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_usuarioLogado" id="x_nu_usuarioLogado" name="x_nu_usuarioLogado"<?php echo $contagempf->nu_usuarioLogado->EditAttributes() ?>>
<?php
if (is_array($contagempf->nu_usuarioLogado->EditValue)) {
	$arwrk = $contagempf->nu_usuarioLogado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->nu_usuarioLogado->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fcontagempflistsrch.Lists["x_nu_usuarioLogado"].Options = <?php echo (is_array($contagempf->nu_usuarioLogado->EditValue)) ? ew_ArrayToJson($contagempf->nu_usuarioLogado->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($contagempf->ic_stContagem->Visible) { // ic_stContagem ?>
	<span id="xsc_ic_stContagem" class="ewCell">
		<span class="ewSearchCaption"><?php echo $contagempf->ic_stContagem->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_ic_stContagem" id="z_ic_stContagem" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_stContagem" id="x_ic_stContagem" name="x_ic_stContagem"<?php echo $contagempf->ic_stContagem->EditAttributes() ?>>
<?php
if (is_array($contagempf->ic_stContagem->EditValue)) {
	$arwrk = $contagempf->ic_stContagem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($contagempf->ic_stContagem->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
	<a class="btn ewShowAll" href="<?php echo $contagempf_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
	<a class="btn ewAdvancedSearch" href="contagempfsrch.php"><?php echo $Language->Phrase("AdvancedSearch") ?></a>
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
<?php $contagempf_list->ShowPageHeader(); ?>
<?php
$contagempf_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fcontagempflist" id="fcontagempflist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contagempf">
<div id="gmp_contagempf" class="ewGridMiddlePanel">
<?php if ($contagempf_list->TotalRecs > 0) { ?>
<table id="tbl_contagempflist" class="ewTable ewTableSeparate">
<?php echo $contagempf->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$contagempf_list->RenderListOptions();

// Render list options (header, left)
$contagempf_list->ListOptions->Render("header", "left");
?>
<?php if ($contagempf->nu_contagem->Visible) { // nu_contagem ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_contagem) == "") { ?>
		<td><div id="elh_contagempf_nu_contagem" class="contagempf_nu_contagem"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_contagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf->SortUrl($contagempf->nu_contagem) ?>',2);"><div id="elh_contagempf_nu_contagem" class="contagempf_nu_contagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_contagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_contagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_contagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_tpMetrica) == "") { ?>
		<td><div id="elh_contagempf_nu_tpMetrica" class="contagempf_nu_tpMetrica"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_tpMetrica->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf->SortUrl($contagempf->nu_tpMetrica) ?>',2);"><div id="elh_contagempf_nu_tpMetrica" class="contagempf_nu_tpMetrica">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_tpMetrica->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_tpMetrica->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_tpMetrica->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_tpContagem) == "") { ?>
		<td><div id="elh_contagempf_nu_tpContagem" class="contagempf_nu_tpContagem"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_tpContagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf->SortUrl($contagempf->nu_tpContagem) ?>',2);"><div id="elh_contagempf_nu_tpContagem" class="contagempf_nu_tpContagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_tpContagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_tpContagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_tpContagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_sistema->Visible) { // nu_sistema ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_sistema) == "") { ?>
		<td><div id="elh_contagempf_nu_sistema" class="contagempf_nu_sistema"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_sistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf->SortUrl($contagempf->nu_sistema) ?>',2);"><div id="elh_contagempf_nu_sistema" class="contagempf_nu_sistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_sistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_sistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_sistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_faseMedida->Visible) { // nu_faseMedida ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_faseMedida) == "") { ?>
		<td><div id="elh_contagempf_nu_faseMedida" class="contagempf_nu_faseMedida"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_faseMedida->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf->SortUrl($contagempf->nu_faseMedida) ?>',2);"><div id="elh_contagempf_nu_faseMedida" class="contagempf_nu_faseMedida">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_faseMedida->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_faseMedida->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_faseMedida->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->nu_usuarioLogado->Visible) { // nu_usuarioLogado ?>
	<?php if ($contagempf->SortUrl($contagempf->nu_usuarioLogado) == "") { ?>
		<td><div id="elh_contagempf_nu_usuarioLogado" class="contagempf_nu_usuarioLogado"><div class="ewTableHeaderCaption"><?php echo $contagempf->nu_usuarioLogado->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf->SortUrl($contagempf->nu_usuarioLogado) ?>',2);"><div id="elh_contagempf_nu_usuarioLogado" class="contagempf_nu_usuarioLogado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->nu_usuarioLogado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->nu_usuarioLogado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->nu_usuarioLogado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->ic_stContagem->Visible) { // ic_stContagem ?>
	<?php if ($contagempf->SortUrl($contagempf->ic_stContagem) == "") { ?>
		<td><div id="elh_contagempf_ic_stContagem" class="contagempf_ic_stContagem"><div class="ewTableHeaderCaption"><?php echo $contagempf->ic_stContagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf->SortUrl($contagempf->ic_stContagem) ?>',2);"><div id="elh_contagempf_ic_stContagem" class="contagempf_ic_stContagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->ic_stContagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->ic_stContagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->ic_stContagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
	<?php if ($contagempf->SortUrl($contagempf->pc_varFasesRoteiro) == "") { ?>
		<td><div id="elh_contagempf_pc_varFasesRoteiro" class="contagempf_pc_varFasesRoteiro"><div class="ewTableHeaderCaption"><?php echo $contagempf->pc_varFasesRoteiro->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf->SortUrl($contagempf->pc_varFasesRoteiro) ?>',2);"><div id="elh_contagempf_pc_varFasesRoteiro" class="contagempf_pc_varFasesRoteiro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->pc_varFasesRoteiro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->pc_varFasesRoteiro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->pc_varFasesRoteiro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
	<?php if ($contagempf->SortUrl($contagempf->vr_pfFaturamento) == "") { ?>
		<td><div id="elh_contagempf_vr_pfFaturamento" class="contagempf_vr_pfFaturamento"><div class="ewTableHeaderCaption"><?php echo $contagempf->vr_pfFaturamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf->SortUrl($contagempf->vr_pfFaturamento) ?>',2);"><div id="elh_contagempf_vr_pfFaturamento" class="contagempf_vr_pfFaturamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf->vr_pfFaturamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf->vr_pfFaturamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf->vr_pfFaturamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$contagempf_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($contagempf->ExportAll && $contagempf->Export <> "") {
	$contagempf_list->StopRec = $contagempf_list->TotalRecs;
} else {

	// Set the last record to display
	if ($contagempf_list->TotalRecs > $contagempf_list->StartRec + $contagempf_list->DisplayRecs - 1)
		$contagempf_list->StopRec = $contagempf_list->StartRec + $contagempf_list->DisplayRecs - 1;
	else
		$contagempf_list->StopRec = $contagempf_list->TotalRecs;
}
$contagempf_list->RecCnt = $contagempf_list->StartRec - 1;
if ($contagempf_list->Recordset && !$contagempf_list->Recordset->EOF) {
	$contagempf_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $contagempf_list->StartRec > 1)
		$contagempf_list->Recordset->Move($contagempf_list->StartRec - 1);
} elseif (!$contagempf->AllowAddDeleteRow && $contagempf_list->StopRec == 0) {
	$contagempf_list->StopRec = $contagempf->GridAddRowCount;
}

// Initialize aggregate
$contagempf->RowType = EW_ROWTYPE_AGGREGATEINIT;
$contagempf->ResetAttrs();
$contagempf_list->RenderRow();
while ($contagempf_list->RecCnt < $contagempf_list->StopRec) {
	$contagempf_list->RecCnt++;
	if (intval($contagempf_list->RecCnt) >= intval($contagempf_list->StartRec)) {
		$contagempf_list->RowCnt++;

		// Set up key count
		$contagempf_list->KeyCount = $contagempf_list->RowIndex;

		// Init row class and style
		$contagempf->ResetAttrs();
		$contagempf->CssClass = "";
		if ($contagempf->CurrentAction == "gridadd") {
		} else {
			$contagempf_list->LoadRowValues($contagempf_list->Recordset); // Load row values
		}
		$contagempf->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$contagempf->RowAttrs = array_merge($contagempf->RowAttrs, array('data-rowindex'=>$contagempf_list->RowCnt, 'id'=>'r' . $contagempf_list->RowCnt . '_contagempf', 'data-rowtype'=>$contagempf->RowType));

		// Render row
		$contagempf_list->RenderRow();

		// Render list options
		$contagempf_list->RenderListOptions();
?>
	<tr<?php echo $contagempf->RowAttributes() ?>>
<?php

// Render list options (body, left)
$contagempf_list->ListOptions->Render("body", "left", $contagempf_list->RowCnt);
?>
	<?php if ($contagempf->nu_contagem->Visible) { // nu_contagem ?>
		<td<?php echo $contagempf->nu_contagem->CellAttributes() ?>>
<span<?php echo $contagempf->nu_contagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_contagem->ListViewValue() ?></span>
<a id="<?php echo $contagempf_list->PageObjName . "_row_" . $contagempf_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
		<td<?php echo $contagempf->nu_tpMetrica->CellAttributes() ?>>
<span<?php echo $contagempf->nu_tpMetrica->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpMetrica->ListViewValue() ?></span>
<a id="<?php echo $contagempf_list->PageObjName . "_row_" . $contagempf_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_tpContagem->Visible) { // nu_tpContagem ?>
		<td<?php echo $contagempf->nu_tpContagem->CellAttributes() ?>>
<span<?php echo $contagempf->nu_tpContagem->ViewAttributes() ?>>
<?php echo $contagempf->nu_tpContagem->ListViewValue() ?></span>
<a id="<?php echo $contagempf_list->PageObjName . "_row_" . $contagempf_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_sistema->Visible) { // nu_sistema ?>
		<td<?php echo $contagempf->nu_sistema->CellAttributes() ?>>
<span<?php echo $contagempf->nu_sistema->ViewAttributes() ?>>
<?php echo $contagempf->nu_sistema->ListViewValue() ?></span>
<a id="<?php echo $contagempf_list->PageObjName . "_row_" . $contagempf_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_faseMedida->Visible) { // nu_faseMedida ?>
		<td<?php echo $contagempf->nu_faseMedida->CellAttributes() ?>>
<span<?php echo $contagempf->nu_faseMedida->ViewAttributes() ?>>
<?php echo $contagempf->nu_faseMedida->ListViewValue() ?></span>
<a id="<?php echo $contagempf_list->PageObjName . "_row_" . $contagempf_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->nu_usuarioLogado->Visible) { // nu_usuarioLogado ?>
		<td<?php echo $contagempf->nu_usuarioLogado->CellAttributes() ?>>
<span<?php echo $contagempf->nu_usuarioLogado->ViewAttributes() ?>>
<?php echo $contagempf->nu_usuarioLogado->ListViewValue() ?></span>
<a id="<?php echo $contagempf_list->PageObjName . "_row_" . $contagempf_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->ic_stContagem->Visible) { // ic_stContagem ?>
		<td<?php echo $contagempf->ic_stContagem->CellAttributes() ?>>
<span<?php echo $contagempf->ic_stContagem->ViewAttributes() ?>>
<?php echo $contagempf->ic_stContagem->ListViewValue() ?></span>
<a id="<?php echo $contagempf_list->PageObjName . "_row_" . $contagempf_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
		<td<?php echo $contagempf->pc_varFasesRoteiro->CellAttributes() ?>>
<span<?php echo $contagempf->pc_varFasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf->pc_varFasesRoteiro->ListViewValue() ?></span>
<a id="<?php echo $contagempf_list->PageObjName . "_row_" . $contagempf_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
		<td<?php echo $contagempf->vr_pfFaturamento->CellAttributes() ?>>
<span<?php echo $contagempf->vr_pfFaturamento->ViewAttributes() ?>>
<?php echo $contagempf->vr_pfFaturamento->ListViewValue() ?></span>
<a id="<?php echo $contagempf_list->PageObjName . "_row_" . $contagempf_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$contagempf_list->ListOptions->Render("body", "right", $contagempf_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($contagempf->CurrentAction <> "gridadd")
		$contagempf_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($contagempf->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($contagempf_list->Recordset)
	$contagempf_list->Recordset->Close();
?>
<?php if ($contagempf->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($contagempf->CurrentAction <> "gridadd" && $contagempf->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($contagempf_list->Pager)) $contagempf_list->Pager = new cNumericPager($contagempf_list->StartRec, $contagempf_list->DisplayRecs, $contagempf_list->TotalRecs, $contagempf_list->RecRange) ?>
<?php if ($contagempf_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($contagempf_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_list->PageUrl() ?>start=<?php echo $contagempf_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($contagempf_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_list->PageUrl() ?>start=<?php echo $contagempf_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($contagempf_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $contagempf_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($contagempf_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_list->PageUrl() ?>start=<?php echo $contagempf_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($contagempf_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_list->PageUrl() ?>start=<?php echo $contagempf_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($contagempf_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $contagempf_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $contagempf_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $contagempf_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($contagempf_list->SearchWhere == "0=101") { ?>
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
	foreach ($contagempf_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($contagempf->Export == "") { ?>
<script type="text/javascript">
fcontagempflistsrch.Init();
fcontagempflist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$contagempf_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($contagempf->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$contagempf_list->Page_Terminate();
?>
