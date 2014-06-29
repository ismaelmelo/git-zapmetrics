<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "laudoinfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$laudo_list = NULL; // Initialize page object first

class claudo_list extends claudo {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'laudo';

	// Page object name
	var $PageObjName = 'laudo_list';

	// Grid form hidden field names
	var $FormName = 'flaudolist';
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

		// Table object (laudo)
		if (!isset($GLOBALS["laudo"])) {
			$GLOBALS["laudo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["laudo"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "laudoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "laudodelete.php";
		$this->MultiUpdateUrl = "laudoupdate.php";

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS['solicitacaoMetricas'])) $GLOBALS['solicitacaoMetricas'] = new csolicitacaoMetricas();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'laudo', TRUE);

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
		$this->nu_usuarioResp->Visible = !$this->IsAddOrEdit();
		$this->dt_emissao->Visible = !$this->IsAddOrEdit();
		$this->hh_emissao->Visible = !$this->IsAddOrEdit();

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
		if (count($arrKeyFlds) >= 2) {
			$this->nu_solicitacao->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_solicitacao->FormValue))
				return FALSE;
			$this->nu_versao->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->nu_versao->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_solicitacao, FALSE); // nu_solicitacao
		$this->BuildSearchSql($sWhere, $this->nu_versao, FALSE); // nu_versao
		$this->BuildSearchSql($sWhere, $this->ds_sobreDocumentacao, FALSE); // ds_sobreDocumentacao
		$this->BuildSearchSql($sWhere, $this->ds_sobreMetrificacao, FALSE); // ds_sobreMetrificacao
		$this->BuildSearchSql($sWhere, $this->qt_pf, FALSE); // qt_pf
		$this->BuildSearchSql($sWhere, $this->qt_horas, FALSE); // qt_horas
		$this->BuildSearchSql($sWhere, $this->qt_prazoMeses, FALSE); // qt_prazoMeses
		$this->BuildSearchSql($sWhere, $this->qt_prazoDias, FALSE); // qt_prazoDias
		$this->BuildSearchSql($sWhere, $this->vr_contratacao, FALSE); // vr_contratacao
		$this->BuildSearchSql($sWhere, $this->nu_usuarioResp, FALSE); // nu_usuarioResp
		$this->BuildSearchSql($sWhere, $this->dt_inicioSolicitacao, FALSE); // dt_inicioSolicitacao
		$this->BuildSearchSql($sWhere, $this->dt_inicioContagem, FALSE); // dt_inicioContagem
		$this->BuildSearchSql($sWhere, $this->dt_emissao, FALSE); // dt_emissao
		$this->BuildSearchSql($sWhere, $this->hh_emissao, FALSE); // hh_emissao
		$this->BuildSearchSql($sWhere, $this->ic_tamanho, FALSE); // ic_tamanho
		$this->BuildSearchSql($sWhere, $this->ic_esforco, FALSE); // ic_esforco
		$this->BuildSearchSql($sWhere, $this->ic_prazo, FALSE); // ic_prazo
		$this->BuildSearchSql($sWhere, $this->ic_custo, FALSE); // ic_custo

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_solicitacao->AdvancedSearch->Save(); // nu_solicitacao
			$this->nu_versao->AdvancedSearch->Save(); // nu_versao
			$this->ds_sobreDocumentacao->AdvancedSearch->Save(); // ds_sobreDocumentacao
			$this->ds_sobreMetrificacao->AdvancedSearch->Save(); // ds_sobreMetrificacao
			$this->qt_pf->AdvancedSearch->Save(); // qt_pf
			$this->qt_horas->AdvancedSearch->Save(); // qt_horas
			$this->qt_prazoMeses->AdvancedSearch->Save(); // qt_prazoMeses
			$this->qt_prazoDias->AdvancedSearch->Save(); // qt_prazoDias
			$this->vr_contratacao->AdvancedSearch->Save(); // vr_contratacao
			$this->nu_usuarioResp->AdvancedSearch->Save(); // nu_usuarioResp
			$this->dt_inicioSolicitacao->AdvancedSearch->Save(); // dt_inicioSolicitacao
			$this->dt_inicioContagem->AdvancedSearch->Save(); // dt_inicioContagem
			$this->dt_emissao->AdvancedSearch->Save(); // dt_emissao
			$this->hh_emissao->AdvancedSearch->Save(); // hh_emissao
			$this->ic_tamanho->AdvancedSearch->Save(); // ic_tamanho
			$this->ic_esforco->AdvancedSearch->Save(); // ic_esforco
			$this->ic_prazo->AdvancedSearch->Save(); // ic_prazo
			$this->ic_custo->AdvancedSearch->Save(); // ic_custo
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
		if ($this->nu_solicitacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_versao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_sobreDocumentacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_sobreMetrificacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qt_pf->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qt_horas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qt_prazoMeses->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qt_prazoDias->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_contratacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_usuarioResp->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_inicioSolicitacao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_inicioContagem->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_emissao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->hh_emissao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_tamanho->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_esforco->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_prazo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_custo->AdvancedSearch->IssetSession())
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
		$this->nu_solicitacao->AdvancedSearch->UnsetSession();
		$this->nu_versao->AdvancedSearch->UnsetSession();
		$this->ds_sobreDocumentacao->AdvancedSearch->UnsetSession();
		$this->ds_sobreMetrificacao->AdvancedSearch->UnsetSession();
		$this->qt_pf->AdvancedSearch->UnsetSession();
		$this->qt_horas->AdvancedSearch->UnsetSession();
		$this->qt_prazoMeses->AdvancedSearch->UnsetSession();
		$this->qt_prazoDias->AdvancedSearch->UnsetSession();
		$this->vr_contratacao->AdvancedSearch->UnsetSession();
		$this->nu_usuarioResp->AdvancedSearch->UnsetSession();
		$this->dt_inicioSolicitacao->AdvancedSearch->UnsetSession();
		$this->dt_inicioContagem->AdvancedSearch->UnsetSession();
		$this->dt_emissao->AdvancedSearch->UnsetSession();
		$this->hh_emissao->AdvancedSearch->UnsetSession();
		$this->ic_tamanho->AdvancedSearch->UnsetSession();
		$this->ic_esforco->AdvancedSearch->UnsetSession();
		$this->ic_prazo->AdvancedSearch->UnsetSession();
		$this->ic_custo->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_solicitacao->AdvancedSearch->Load();
		$this->nu_versao->AdvancedSearch->Load();
		$this->ds_sobreDocumentacao->AdvancedSearch->Load();
		$this->ds_sobreMetrificacao->AdvancedSearch->Load();
		$this->qt_pf->AdvancedSearch->Load();
		$this->qt_horas->AdvancedSearch->Load();
		$this->qt_prazoMeses->AdvancedSearch->Load();
		$this->qt_prazoDias->AdvancedSearch->Load();
		$this->vr_contratacao->AdvancedSearch->Load();
		$this->nu_usuarioResp->AdvancedSearch->Load();
		$this->dt_inicioSolicitacao->AdvancedSearch->Load();
		$this->dt_inicioContagem->AdvancedSearch->Load();
		$this->dt_emissao->AdvancedSearch->Load();
		$this->hh_emissao->AdvancedSearch->Load();
		$this->ic_tamanho->AdvancedSearch->Load();
		$this->ic_esforco->AdvancedSearch->Load();
		$this->ic_prazo->AdvancedSearch->Load();
		$this->ic_custo->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_solicitacao, $bCtrl); // nu_solicitacao
			$this->UpdateSort($this->nu_versao, $bCtrl); // nu_versao
			$this->UpdateSort($this->qt_pf, $bCtrl); // qt_pf
			$this->UpdateSort($this->qt_horas, $bCtrl); // qt_horas
			$this->UpdateSort($this->qt_prazoMeses, $bCtrl); // qt_prazoMeses
			$this->UpdateSort($this->qt_prazoDias, $bCtrl); // qt_prazoDias
			$this->UpdateSort($this->vr_contratacao, $bCtrl); // vr_contratacao
			$this->UpdateSort($this->nu_usuarioResp, $bCtrl); // nu_usuarioResp
			$this->UpdateSort($this->dt_inicioSolicitacao, $bCtrl); // dt_inicioSolicitacao
			$this->UpdateSort($this->dt_inicioContagem, $bCtrl); // dt_inicioContagem
			$this->UpdateSort($this->dt_emissao, $bCtrl); // dt_emissao
			$this->UpdateSort($this->hh_emissao, $bCtrl); // hh_emissao
			$this->UpdateSort($this->ic_tamanho, $bCtrl); // ic_tamanho
			$this->UpdateSort($this->ic_esforco, $bCtrl); // ic_esforco
			$this->UpdateSort($this->ic_prazo, $bCtrl); // ic_prazo
			$this->UpdateSort($this->ic_custo, $bCtrl); // ic_custo
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
				$this->nu_solicitacao->setSort("DESC");
				$this->nu_versao->setSort("DESC");
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
				$this->nu_solicitacao->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->nu_solicitacao->setSort("");
				$this->nu_versao->setSort("");
				$this->qt_pf->setSort("");
				$this->qt_horas->setSort("");
				$this->qt_prazoMeses->setSort("");
				$this->qt_prazoDias->setSort("");
				$this->vr_contratacao->setSort("");
				$this->nu_usuarioResp->setSort("");
				$this->dt_inicioSolicitacao->setSort("");
				$this->dt_inicioContagem->setSort("");
				$this->dt_emissao->setSort("");
				$this->hh_emissao->setSort("");
				$this->ic_tamanho->setSort("");
				$this->ic_esforco->setSort("");
				$this->ic_prazo->setSort("");
				$this->ic_custo->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.flaudolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_solicitacao

		$this->nu_solicitacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_solicitacao"]);
		if ($this->nu_solicitacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_solicitacao->AdvancedSearch->SearchOperator = @$_GET["z_nu_solicitacao"];

		// nu_versao
		$this->nu_versao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_versao"]);
		if ($this->nu_versao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_versao->AdvancedSearch->SearchOperator = @$_GET["z_nu_versao"];

		// ds_sobreDocumentacao
		$this->ds_sobreDocumentacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_sobreDocumentacao"]);
		if ($this->ds_sobreDocumentacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_sobreDocumentacao->AdvancedSearch->SearchOperator = @$_GET["z_ds_sobreDocumentacao"];

		// ds_sobreMetrificacao
		$this->ds_sobreMetrificacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_sobreMetrificacao"]);
		if ($this->ds_sobreMetrificacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_sobreMetrificacao->AdvancedSearch->SearchOperator = @$_GET["z_ds_sobreMetrificacao"];

		// qt_pf
		$this->qt_pf->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_qt_pf"]);
		if ($this->qt_pf->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->qt_pf->AdvancedSearch->SearchOperator = @$_GET["z_qt_pf"];
		$this->qt_pf->AdvancedSearch->SearchCondition = @$_GET["v_qt_pf"];
		$this->qt_pf->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_qt_pf"]);
		if ($this->qt_pf->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->qt_pf->AdvancedSearch->SearchOperator2 = @$_GET["w_qt_pf"];

		// qt_horas
		$this->qt_horas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_qt_horas"]);
		if ($this->qt_horas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->qt_horas->AdvancedSearch->SearchOperator = @$_GET["z_qt_horas"];
		$this->qt_horas->AdvancedSearch->SearchCondition = @$_GET["v_qt_horas"];
		$this->qt_horas->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_qt_horas"]);
		if ($this->qt_horas->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->qt_horas->AdvancedSearch->SearchOperator2 = @$_GET["w_qt_horas"];

		// qt_prazoMeses
		$this->qt_prazoMeses->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_qt_prazoMeses"]);
		if ($this->qt_prazoMeses->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->qt_prazoMeses->AdvancedSearch->SearchOperator = @$_GET["z_qt_prazoMeses"];
		$this->qt_prazoMeses->AdvancedSearch->SearchCondition = @$_GET["v_qt_prazoMeses"];
		$this->qt_prazoMeses->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_qt_prazoMeses"]);
		if ($this->qt_prazoMeses->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->qt_prazoMeses->AdvancedSearch->SearchOperator2 = @$_GET["w_qt_prazoMeses"];

		// qt_prazoDias
		$this->qt_prazoDias->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_qt_prazoDias"]);
		if ($this->qt_prazoDias->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->qt_prazoDias->AdvancedSearch->SearchOperator = @$_GET["z_qt_prazoDias"];
		$this->qt_prazoDias->AdvancedSearch->SearchCondition = @$_GET["v_qt_prazoDias"];
		$this->qt_prazoDias->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_qt_prazoDias"]);
		if ($this->qt_prazoDias->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->qt_prazoDias->AdvancedSearch->SearchOperator2 = @$_GET["w_qt_prazoDias"];

		// vr_contratacao
		$this->vr_contratacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_contratacao"]);
		if ($this->vr_contratacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_contratacao->AdvancedSearch->SearchOperator = @$_GET["z_vr_contratacao"];
		$this->vr_contratacao->AdvancedSearch->SearchCondition = @$_GET["v_vr_contratacao"];
		$this->vr_contratacao->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_vr_contratacao"]);
		if ($this->vr_contratacao->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->vr_contratacao->AdvancedSearch->SearchOperator2 = @$_GET["w_vr_contratacao"];

		// nu_usuarioResp
		$this->nu_usuarioResp->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_usuarioResp"]);
		if ($this->nu_usuarioResp->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_usuarioResp->AdvancedSearch->SearchOperator = @$_GET["z_nu_usuarioResp"];
		$this->nu_usuarioResp->AdvancedSearch->SearchCondition = @$_GET["v_nu_usuarioResp"];
		$this->nu_usuarioResp->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_nu_usuarioResp"]);
		if ($this->nu_usuarioResp->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->nu_usuarioResp->AdvancedSearch->SearchOperator2 = @$_GET["w_nu_usuarioResp"];

		// dt_inicioSolicitacao
		$this->dt_inicioSolicitacao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_inicioSolicitacao"]);
		if ($this->dt_inicioSolicitacao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_inicioSolicitacao->AdvancedSearch->SearchOperator = @$_GET["z_dt_inicioSolicitacao"];

		// dt_inicioContagem
		$this->dt_inicioContagem->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_inicioContagem"]);
		if ($this->dt_inicioContagem->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_inicioContagem->AdvancedSearch->SearchOperator = @$_GET["z_dt_inicioContagem"];

		// dt_emissao
		$this->dt_emissao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_emissao"]);
		if ($this->dt_emissao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_emissao->AdvancedSearch->SearchOperator = @$_GET["z_dt_emissao"];
		$this->dt_emissao->AdvancedSearch->SearchCondition = @$_GET["v_dt_emissao"];
		$this->dt_emissao->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_dt_emissao"]);
		if ($this->dt_emissao->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->dt_emissao->AdvancedSearch->SearchOperator2 = @$_GET["w_dt_emissao"];

		// hh_emissao
		$this->hh_emissao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_hh_emissao"]);
		if ($this->hh_emissao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->hh_emissao->AdvancedSearch->SearchOperator = @$_GET["z_hh_emissao"];

		// ic_tamanho
		$this->ic_tamanho->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_tamanho"]);
		if ($this->ic_tamanho->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_tamanho->AdvancedSearch->SearchOperator = @$_GET["z_ic_tamanho"];

		// ic_esforco
		$this->ic_esforco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_esforco"]);
		if ($this->ic_esforco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_esforco->AdvancedSearch->SearchOperator = @$_GET["z_ic_esforco"];

		// ic_prazo
		$this->ic_prazo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_prazo"]);
		if ($this->ic_prazo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_prazo->AdvancedSearch->SearchOperator = @$_GET["z_ic_prazo"];

		// ic_custo
		$this->ic_custo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_custo"]);
		if ($this->ic_custo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_custo->AdvancedSearch->SearchOperator = @$_GET["z_ic_custo"];
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
		$this->nu_solicitacao->setDbValue($rs->fields('nu_solicitacao'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->ds_sobreDocumentacao->setDbValue($rs->fields('ds_sobreDocumentacao'));
		$this->ds_sobreMetrificacao->setDbValue($rs->fields('ds_sobreMetrificacao'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
		$this->qt_prazoMeses->setDbValue($rs->fields('qt_prazoMeses'));
		$this->qt_prazoDias->setDbValue($rs->fields('qt_prazoDias'));
		$this->vr_contratacao->setDbValue($rs->fields('vr_contratacao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->dt_inicioSolicitacao->setDbValue($rs->fields('dt_inicioSolicitacao'));
		$this->dt_inicioContagem->setDbValue($rs->fields('dt_inicioContagem'));
		$this->dt_emissao->setDbValue($rs->fields('dt_emissao'));
		$this->hh_emissao->setDbValue($rs->fields('hh_emissao'));
		$this->ic_tamanho->setDbValue($rs->fields('ic_tamanho'));
		$this->ic_esforco->setDbValue($rs->fields('ic_esforco'));
		$this->ic_prazo->setDbValue($rs->fields('ic_prazo'));
		$this->ic_custo->setDbValue($rs->fields('ic_custo'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solicitacao->DbValue = $row['nu_solicitacao'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->ds_sobreDocumentacao->DbValue = $row['ds_sobreDocumentacao'];
		$this->ds_sobreMetrificacao->DbValue = $row['ds_sobreMetrificacao'];
		$this->qt_pf->DbValue = $row['qt_pf'];
		$this->qt_horas->DbValue = $row['qt_horas'];
		$this->qt_prazoMeses->DbValue = $row['qt_prazoMeses'];
		$this->qt_prazoDias->DbValue = $row['qt_prazoDias'];
		$this->vr_contratacao->DbValue = $row['vr_contratacao'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->dt_inicioSolicitacao->DbValue = $row['dt_inicioSolicitacao'];
		$this->dt_inicioContagem->DbValue = $row['dt_inicioContagem'];
		$this->dt_emissao->DbValue = $row['dt_emissao'];
		$this->hh_emissao->DbValue = $row['hh_emissao'];
		$this->ic_tamanho->DbValue = $row['ic_tamanho'];
		$this->ic_esforco->DbValue = $row['ic_esforco'];
		$this->ic_prazo->DbValue = $row['ic_prazo'];
		$this->ic_custo->DbValue = $row['ic_custo'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_solicitacao")) <> "")
			$this->nu_solicitacao->CurrentValue = $this->getKey("nu_solicitacao"); // nu_solicitacao
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nu_versao")) <> "")
			$this->nu_versao->CurrentValue = $this->getKey("nu_versao"); // nu_versao
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
		if ($this->qt_pf->FormValue == $this->qt_pf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_pf->CurrentValue)))
			$this->qt_pf->CurrentValue = ew_StrToFloat($this->qt_pf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_horas->FormValue == $this->qt_horas->CurrentValue && is_numeric(ew_StrToFloat($this->qt_horas->CurrentValue)))
			$this->qt_horas->CurrentValue = ew_StrToFloat($this->qt_horas->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_prazoMeses->FormValue == $this->qt_prazoMeses->CurrentValue && is_numeric(ew_StrToFloat($this->qt_prazoMeses->CurrentValue)))
			$this->qt_prazoMeses->CurrentValue = ew_StrToFloat($this->qt_prazoMeses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_contratacao->FormValue == $this->vr_contratacao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_contratacao->CurrentValue)))
			$this->vr_contratacao->CurrentValue = ew_StrToFloat($this->vr_contratacao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solicitacao
		// nu_versao
		// ds_sobreDocumentacao
		// ds_sobreMetrificacao
		// qt_pf
		// qt_horas
		// qt_prazoMeses
		// qt_prazoDias
		// vr_contratacao
		// nu_usuarioResp
		// dt_inicioSolicitacao
		// dt_inicioContagem
		// dt_emissao
		// hh_emissao
		// ic_tamanho
		// ic_esforco
		// ic_prazo
		// ic_custo
		// ic_bloqueio

		$this->ic_bloqueio->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solicitacao
			if (strval($this->nu_solicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
				}
			} else {
				$this->nu_solicitacao->ViewValue = NULL;
			}
			$this->nu_solicitacao->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
			$this->qt_pf->ViewCustomAttributes = "";

			// qt_horas
			$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
			$this->qt_horas->ViewCustomAttributes = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->ViewValue = $this->qt_prazoMeses->CurrentValue;
			$this->qt_prazoMeses->ViewCustomAttributes = "";

			// qt_prazoDias
			$this->qt_prazoDias->ViewValue = $this->qt_prazoDias->CurrentValue;
			$this->qt_prazoDias->ViewCustomAttributes = "";

			// vr_contratacao
			$this->vr_contratacao->ViewValue = $this->vr_contratacao->CurrentValue;
			$this->vr_contratacao->ViewValue = ew_FormatCurrency($this->vr_contratacao->ViewValue, 2, -2, -2, -2);
			$this->vr_contratacao->ViewCustomAttributes = "";

			// nu_usuarioResp
			if (strval($this->nu_usuarioResp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioResp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioResp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
				}
			} else {
				$this->nu_usuarioResp->ViewValue = NULL;
			}
			$this->nu_usuarioResp->ViewCustomAttributes = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->ViewValue = $this->dt_inicioSolicitacao->CurrentValue;
			$this->dt_inicioSolicitacao->ViewValue = ew_FormatDateTime($this->dt_inicioSolicitacao->ViewValue, 7);
			$this->dt_inicioSolicitacao->ViewCustomAttributes = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->ViewValue = $this->dt_inicioContagem->CurrentValue;
			$this->dt_inicioContagem->ViewValue = ew_FormatDateTime($this->dt_inicioContagem->ViewValue, 7);
			$this->dt_inicioContagem->ViewCustomAttributes = "";

			// dt_emissao
			$this->dt_emissao->ViewValue = $this->dt_emissao->CurrentValue;
			$this->dt_emissao->ViewValue = ew_FormatDateTime($this->dt_emissao->ViewValue, 7);
			$this->dt_emissao->ViewCustomAttributes = "";

			// hh_emissao
			$this->hh_emissao->ViewValue = $this->hh_emissao->CurrentValue;
			$this->hh_emissao->ViewValue = ew_FormatDateTime($this->hh_emissao->ViewValue, 4);
			$this->hh_emissao->ViewCustomAttributes = "";

			// ic_tamanho
			if (strval($this->ic_tamanho->CurrentValue) <> "") {
				switch ($this->ic_tamanho->CurrentValue) {
					case $this->ic_tamanho->FldTagValue(1):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->CurrentValue;
						break;
					case $this->ic_tamanho->FldTagValue(2):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->CurrentValue;
						break;
					default:
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->CurrentValue;
				}
			} else {
				$this->ic_tamanho->ViewValue = NULL;
			}
			$this->ic_tamanho->ViewCustomAttributes = "";

			// ic_esforco
			if (strval($this->ic_esforco->CurrentValue) <> "") {
				switch ($this->ic_esforco->CurrentValue) {
					case $this->ic_esforco->FldTagValue(1):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->CurrentValue;
						break;
					case $this->ic_esforco->FldTagValue(2):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->CurrentValue;
						break;
					default:
						$this->ic_esforco->ViewValue = $this->ic_esforco->CurrentValue;
				}
			} else {
				$this->ic_esforco->ViewValue = NULL;
			}
			$this->ic_esforco->ViewCustomAttributes = "";

			// ic_prazo
			if (strval($this->ic_prazo->CurrentValue) <> "") {
				switch ($this->ic_prazo->CurrentValue) {
					case $this->ic_prazo->FldTagValue(1):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->CurrentValue;
						break;
					case $this->ic_prazo->FldTagValue(2):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->CurrentValue;
						break;
					default:
						$this->ic_prazo->ViewValue = $this->ic_prazo->CurrentValue;
				}
			} else {
				$this->ic_prazo->ViewValue = NULL;
			}
			$this->ic_prazo->ViewCustomAttributes = "";

			// ic_custo
			if (strval($this->ic_custo->CurrentValue) <> "") {
				switch ($this->ic_custo->CurrentValue) {
					case $this->ic_custo->FldTagValue(1):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->CurrentValue;
						break;
					case $this->ic_custo->FldTagValue(2):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->CurrentValue;
						break;
					default:
						$this->ic_custo->ViewValue = $this->ic_custo->CurrentValue;
				}
			} else {
				$this->ic_custo->ViewValue = NULL;
			}
			$this->ic_custo->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_solicitacao
			$this->nu_solicitacao->LinkCustomAttributes = "";
			$this->nu_solicitacao->HrefValue = "";
			$this->nu_solicitacao->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// qt_pf
			$this->qt_pf->LinkCustomAttributes = "";
			$this->qt_pf->HrefValue = "";
			$this->qt_pf->TooltipValue = "";

			// qt_horas
			$this->qt_horas->LinkCustomAttributes = "";
			$this->qt_horas->HrefValue = "";
			$this->qt_horas->TooltipValue = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->LinkCustomAttributes = "";
			$this->qt_prazoMeses->HrefValue = "";
			$this->qt_prazoMeses->TooltipValue = "";

			// qt_prazoDias
			$this->qt_prazoDias->LinkCustomAttributes = "";
			$this->qt_prazoDias->HrefValue = "";
			$this->qt_prazoDias->TooltipValue = "";

			// vr_contratacao
			$this->vr_contratacao->LinkCustomAttributes = "";
			$this->vr_contratacao->HrefValue = "";
			$this->vr_contratacao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->LinkCustomAttributes = "";
			$this->dt_inicioSolicitacao->HrefValue = "";
			$this->dt_inicioSolicitacao->TooltipValue = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->LinkCustomAttributes = "";
			$this->dt_inicioContagem->HrefValue = "";
			$this->dt_inicioContagem->TooltipValue = "";

			// dt_emissao
			$this->dt_emissao->LinkCustomAttributes = "";
			$this->dt_emissao->HrefValue = "";
			$this->dt_emissao->TooltipValue = "";

			// hh_emissao
			$this->hh_emissao->LinkCustomAttributes = "";
			$this->hh_emissao->HrefValue = "";
			$this->hh_emissao->TooltipValue = "";

			// ic_tamanho
			$this->ic_tamanho->LinkCustomAttributes = "";
			$this->ic_tamanho->HrefValue = "";
			$this->ic_tamanho->TooltipValue = "";

			// ic_esforco
			$this->ic_esforco->LinkCustomAttributes = "";
			$this->ic_esforco->HrefValue = "";
			$this->ic_esforco->TooltipValue = "";

			// ic_prazo
			$this->ic_prazo->LinkCustomAttributes = "";
			$this->ic_prazo->HrefValue = "";
			$this->ic_prazo->TooltipValue = "";

			// ic_custo
			$this->ic_custo->LinkCustomAttributes = "";
			$this->ic_custo->HrefValue = "";
			$this->ic_custo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_solicitacao
			$this->nu_solicitacao->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_solicitacao->EditValue = $arwrk;

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "readonly";
			$this->nu_versao->EditValue = ew_HtmlEncode($this->nu_versao->AdvancedSearch->SearchValue);
			$this->nu_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versao->FldCaption()));

			// qt_pf
			$this->qt_pf->EditCustomAttributes = "readonly";
			$this->qt_pf->EditValue = ew_HtmlEncode($this->qt_pf->AdvancedSearch->SearchValue);
			$this->qt_pf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_pf->FldCaption()));
			$this->qt_pf->EditCustomAttributes = "readonly";
			$this->qt_pf->EditValue2 = ew_HtmlEncode($this->qt_pf->AdvancedSearch->SearchValue2);
			$this->qt_pf->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_pf->FldCaption()));

			// qt_horas
			$this->qt_horas->EditCustomAttributes = "readonly";
			$this->qt_horas->EditValue = ew_HtmlEncode($this->qt_horas->AdvancedSearch->SearchValue);
			$this->qt_horas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_horas->FldCaption()));
			$this->qt_horas->EditCustomAttributes = "readonly";
			$this->qt_horas->EditValue2 = ew_HtmlEncode($this->qt_horas->AdvancedSearch->SearchValue2);
			$this->qt_horas->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_horas->FldCaption()));

			// qt_prazoMeses
			$this->qt_prazoMeses->EditCustomAttributes = "readonly";
			$this->qt_prazoMeses->EditValue = ew_HtmlEncode($this->qt_prazoMeses->AdvancedSearch->SearchValue);
			$this->qt_prazoMeses->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoMeses->FldCaption()));
			$this->qt_prazoMeses->EditCustomAttributes = "readonly";
			$this->qt_prazoMeses->EditValue2 = ew_HtmlEncode($this->qt_prazoMeses->AdvancedSearch->SearchValue2);
			$this->qt_prazoMeses->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoMeses->FldCaption()));

			// qt_prazoDias
			$this->qt_prazoDias->EditCustomAttributes = "readonly";
			$this->qt_prazoDias->EditValue = ew_HtmlEncode($this->qt_prazoDias->AdvancedSearch->SearchValue);
			$this->qt_prazoDias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoDias->FldCaption()));
			$this->qt_prazoDias->EditCustomAttributes = "readonly";
			$this->qt_prazoDias->EditValue2 = ew_HtmlEncode($this->qt_prazoDias->AdvancedSearch->SearchValue2);
			$this->qt_prazoDias->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->qt_prazoDias->FldCaption()));

			// vr_contratacao
			$this->vr_contratacao->EditCustomAttributes = "readonly";
			$this->vr_contratacao->EditValue = ew_HtmlEncode($this->vr_contratacao->AdvancedSearch->SearchValue);
			$this->vr_contratacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_contratacao->FldCaption()));
			$this->vr_contratacao->EditCustomAttributes = "readonly";
			$this->vr_contratacao->EditValue2 = ew_HtmlEncode($this->vr_contratacao->AdvancedSearch->SearchValue2);
			$this->vr_contratacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_contratacao->FldCaption()));

			// nu_usuarioResp
			$this->nu_usuarioResp->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["laudo"]->UserIDAllow($GLOBALS["laudo"]->CurrentAction)) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioResp->EditValue = $arwrk;
			$this->nu_usuarioResp->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["laudo"]->UserIDAllow($GLOBALS["laudo"]->CurrentAction)) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioResp->EditValue2 = $arwrk;

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->EditCustomAttributes = "readonly";
			$this->dt_inicioSolicitacao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_inicioSolicitacao->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_inicioSolicitacao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicioSolicitacao->FldCaption()));

			// dt_inicioContagem
			$this->dt_inicioContagem->EditCustomAttributes = "readonly";
			$this->dt_inicioContagem->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_inicioContagem->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_inicioContagem->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_inicioContagem->FldCaption()));

			// dt_emissao
			$this->dt_emissao->EditCustomAttributes = "";
			$this->dt_emissao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_emissao->AdvancedSearch->SearchValue, 7), 7));
			$this->dt_emissao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_emissao->FldCaption()));
			$this->dt_emissao->EditCustomAttributes = "";
			$this->dt_emissao->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dt_emissao->AdvancedSearch->SearchValue2, 7), 7));
			$this->dt_emissao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_emissao->FldCaption()));

			// hh_emissao
			$this->hh_emissao->EditCustomAttributes = "";
			$this->hh_emissao->EditValue = ew_HtmlEncode($this->hh_emissao->AdvancedSearch->SearchValue);
			$this->hh_emissao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->hh_emissao->FldCaption()));

			// ic_tamanho
			$this->ic_tamanho->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tamanho->FldTagValue(1), $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->FldTagValue(1));
			$arwrk[] = array($this->ic_tamanho->FldTagValue(2), $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->FldTagValue(2));
			$this->ic_tamanho->EditValue = $arwrk;

			// ic_esforco
			$this->ic_esforco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_esforco->FldTagValue(1), $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->FldTagValue(1));
			$arwrk[] = array($this->ic_esforco->FldTagValue(2), $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->FldTagValue(2));
			$this->ic_esforco->EditValue = $arwrk;

			// ic_prazo
			$this->ic_prazo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_prazo->FldTagValue(1), $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->FldTagValue(1));
			$arwrk[] = array($this->ic_prazo->FldTagValue(2), $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->FldTagValue(2));
			$this->ic_prazo->EditValue = $arwrk;

			// ic_custo
			$this->ic_custo->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_custo->FldTagValue(1), $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->FldTagValue(1));
			$arwrk[] = array($this->ic_custo->FldTagValue(2), $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->FldTagValue(2));
			$this->ic_custo->EditValue = $arwrk;
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
		if (!ew_CheckInteger($this->nu_versao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->nu_versao->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_pf->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->qt_pf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_pf->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->qt_pf->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_horas->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->qt_horas->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_horas->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->qt_horas->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_prazoMeses->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->qt_prazoMeses->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qt_prazoMeses->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->qt_prazoMeses->FldErrMsg());
		}
		if (!ew_CheckInteger($this->qt_prazoDias->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->qt_prazoDias->FldErrMsg());
		}
		if (!ew_CheckInteger($this->qt_prazoDias->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->qt_prazoDias->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_contratacao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->vr_contratacao->FldErrMsg());
		}
		if (!ew_CheckNumber($this->vr_contratacao->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->vr_contratacao->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dt_emissao->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->dt_emissao->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->dt_emissao->AdvancedSearch->SearchValue2)) {
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
		$this->nu_solicitacao->AdvancedSearch->Load();
		$this->nu_versao->AdvancedSearch->Load();
		$this->ds_sobreDocumentacao->AdvancedSearch->Load();
		$this->ds_sobreMetrificacao->AdvancedSearch->Load();
		$this->qt_pf->AdvancedSearch->Load();
		$this->qt_horas->AdvancedSearch->Load();
		$this->qt_prazoMeses->AdvancedSearch->Load();
		$this->qt_prazoDias->AdvancedSearch->Load();
		$this->vr_contratacao->AdvancedSearch->Load();
		$this->nu_usuarioResp->AdvancedSearch->Load();
		$this->dt_inicioSolicitacao->AdvancedSearch->Load();
		$this->dt_inicioContagem->AdvancedSearch->Load();
		$this->dt_emissao->AdvancedSearch->Load();
		$this->hh_emissao->AdvancedSearch->Load();
		$this->ic_tamanho->AdvancedSearch->Load();
		$this->ic_esforco->AdvancedSearch->Load();
		$this->ic_prazo->AdvancedSearch->Load();
		$this->ic_custo->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_laudo\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_laudo',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.flaudolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$this->AddSearchQueryString($sQry, $this->nu_solicitacao); // nu_solicitacao
		$this->AddSearchQueryString($sQry, $this->nu_versao); // nu_versao
		$this->AddSearchQueryString($sQry, $this->ds_sobreDocumentacao); // ds_sobreDocumentacao
		$this->AddSearchQueryString($sQry, $this->ds_sobreMetrificacao); // ds_sobreMetrificacao
		$this->AddSearchQueryString($sQry, $this->qt_pf); // qt_pf
		$this->AddSearchQueryString($sQry, $this->qt_horas); // qt_horas
		$this->AddSearchQueryString($sQry, $this->qt_prazoMeses); // qt_prazoMeses
		$this->AddSearchQueryString($sQry, $this->qt_prazoDias); // qt_prazoDias
		$this->AddSearchQueryString($sQry, $this->vr_contratacao); // vr_contratacao
		$this->AddSearchQueryString($sQry, $this->nu_usuarioResp); // nu_usuarioResp
		$this->AddSearchQueryString($sQry, $this->dt_inicioSolicitacao); // dt_inicioSolicitacao
		$this->AddSearchQueryString($sQry, $this->dt_inicioContagem); // dt_inicioContagem
		$this->AddSearchQueryString($sQry, $this->dt_emissao); // dt_emissao
		$this->AddSearchQueryString($sQry, $this->hh_emissao); // hh_emissao
		$this->AddSearchQueryString($sQry, $this->ic_tamanho); // ic_tamanho
		$this->AddSearchQueryString($sQry, $this->ic_esforco); // ic_esforco
		$this->AddSearchQueryString($sQry, $this->ic_prazo); // ic_prazo
		$this->AddSearchQueryString($sQry, $this->ic_custo); // ic_custo

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
					$this->nu_solicitacao->setQueryStringValue($GLOBALS["solicitacaoMetricas"]->nu_solMetricas->QueryStringValue);
					$this->nu_solicitacao->setSessionValue($this->nu_solicitacao->QueryStringValue);
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
				if ($this->nu_solicitacao->QueryStringValue == "") $this->nu_solicitacao->setSessionValue("");
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
		$table = 'laudo';
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
if (!isset($laudo_list)) $laudo_list = new claudo_list();

// Page init
$laudo_list->Page_Init();

// Page main
$laudo_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$laudo_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($laudo->Export == "") { ?>
<script type="text/javascript">

// Page object
var laudo_list = new ew_Page("laudo_list");
laudo_list.PageID = "list"; // Page ID
var EW_PAGE_ID = laudo_list.PageID; // For backward compatibility

// Form object
var flaudolist = new ew_Form("flaudolist");
flaudolist.FormKeyCountName = '<?php echo $laudo_list->FormKeyCountName ?>';

// Form_CustomValidate event
flaudolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flaudolist.ValidateRequired = true;
<?php } else { ?>
flaudolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
flaudolist.Lists["x_nu_solicitacao"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flaudolist.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var flaudolistsrch = new ew_Form("flaudolistsrch");

// Validate function for search
flaudolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_versao");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->nu_versao->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_qt_pf");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_pf->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_qt_horas");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_horas->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_qt_prazoMeses");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_prazoMeses->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_qt_prazoDias");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->qt_prazoDias->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_vr_contratacao");
	if (elm && !ew_CheckNumber(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->vr_contratacao->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_dt_emissao");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($laudo->dt_emissao->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
flaudolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flaudolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
flaudolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
flaudolistsrch.Lists["x_nu_solicitacao"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flaudolistsrch.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (flaudolistsrch) flaudolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($laudo->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($laudo->getCurrentMasterTable() == "" && $laudo_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $laudo_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($laudo->Export == "") || (EW_EXPORT_MASTER_RECORD && $laudo->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "solicitacaometricaslist.php";
if ($laudo_list->DbMasterFilter <> "" && $laudo->getCurrentMasterTable() == "solicitacaoMetricas") {
	if ($laudo_list->MasterRecordExists) {
		if ($laudo->getCurrentMasterTable() == $laudo->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($laudo_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $laudo_list->ExportOptions->Render("body") ?></div>
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
		$laudo_list->TotalRecs = $laudo->SelectRecordCount();
	} else {
		if ($laudo_list->Recordset = $laudo_list->LoadRecordset())
			$laudo_list->TotalRecs = $laudo_list->Recordset->RecordCount();
	}
	$laudo_list->StartRec = 1;
	if ($laudo_list->DisplayRecs <= 0 || ($laudo->Export <> "" && $laudo->ExportAll)) // Display all records
		$laudo_list->DisplayRecs = $laudo_list->TotalRecs;
	if (!($laudo->Export <> "" && $laudo->ExportAll))
		$laudo_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$laudo_list->Recordset = $laudo_list->LoadRecordset($laudo_list->StartRec-1, $laudo_list->DisplayRecs);
$laudo_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($laudo->Export == "" && $laudo->CurrentAction == "") { ?>
<form name="flaudolistsrch" id="flaudolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="flaudolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#flaudolistsrch_SearchGroup" href="#flaudolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="flaudolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="flaudolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="laudo">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$laudo_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$laudo->RowType = EW_ROWTYPE_SEARCH;

// Render row
$laudo->ResetAttrs();
$laudo_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($laudo->nu_solicitacao->Visible) { // nu_solicitacao ?>
	<span id="xsc_nu_solicitacao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $laudo->nu_solicitacao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_solicitacao" id="z_nu_solicitacao" value="="></span>
		<span class="control-group ewSearchField">
<?php if ($laudo->nu_solicitacao->getSessionValue() <> "") { ?>
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->ListViewValue() ?></span>
<input type="hidden" id="x_nu_solicitacao" name="x_nu_solicitacao" value="<?php echo ew_HtmlEncode($laudo->nu_solicitacao->AdvancedSearch->SearchValue) ?>">
<?php } else { ?>
<select data-field="x_nu_solicitacao" id="x_nu_solicitacao" name="x_nu_solicitacao"<?php echo $laudo->nu_solicitacao->EditAttributes() ?>>
<?php
if (is_array($laudo->nu_solicitacao->EditValue)) {
	$arwrk = $laudo->nu_solicitacao->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->nu_solicitacao->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
flaudolistsrch.Lists["x_nu_solicitacao"].Options = <?php echo (is_array($laudo->nu_solicitacao->EditValue)) ? ew_ArrayToJson($laudo->nu_solicitacao->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
<?php if ($laudo->nu_versao->Visible) { // nu_versao ?>
	<span id="xsc_nu_versao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $laudo->nu_versao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_versao" id="z_nu_versao" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_versao" name="x_nu_versao" id="x_nu_versao" size="30" placeholder="<?php echo $laudo->nu_versao->PlaceHolder ?>" value="<?php echo $laudo->nu_versao->EditValue ?>"<?php echo $laudo->nu_versao->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($laudo->qt_pf->Visible) { // qt_pf ?>
	<span id="xsc_qt_pf" class="ewCell">
		<span class="ewSearchCaption"><?php echo $laudo->qt_pf->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_qt_pf" id="z_qt_pf" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_qt_pf" name="x_qt_pf" id="x_qt_pf" size="30" placeholder="<?php echo $laudo->qt_pf->PlaceHolder ?>" value="<?php echo $laudo->qt_pf->EditValue ?>"<?php echo $laudo->qt_pf->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_qt_pf">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_qt_pf">
<input type="text" data-field="x_qt_pf" name="y_qt_pf" id="y_qt_pf" size="30" placeholder="<?php echo $laudo->qt_pf->PlaceHolder ?>" value="<?php echo $laudo->qt_pf->EditValue2 ?>"<?php echo $laudo->qt_pf->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($laudo->qt_horas->Visible) { // qt_horas ?>
	<span id="xsc_qt_horas" class="ewCell">
		<span class="ewSearchCaption"><?php echo $laudo->qt_horas->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_qt_horas" id="z_qt_horas" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_qt_horas" name="x_qt_horas" id="x_qt_horas" size="30" placeholder="<?php echo $laudo->qt_horas->PlaceHolder ?>" value="<?php echo $laudo->qt_horas->EditValue ?>"<?php echo $laudo->qt_horas->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_qt_horas">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_qt_horas">
<input type="text" data-field="x_qt_horas" name="y_qt_horas" id="y_qt_horas" size="30" placeholder="<?php echo $laudo->qt_horas->PlaceHolder ?>" value="<?php echo $laudo->qt_horas->EditValue2 ?>"<?php echo $laudo->qt_horas->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($laudo->qt_prazoMeses->Visible) { // qt_prazoMeses ?>
	<span id="xsc_qt_prazoMeses" class="ewCell">
		<span class="ewSearchCaption"><?php echo $laudo->qt_prazoMeses->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_qt_prazoMeses" id="z_qt_prazoMeses" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_qt_prazoMeses" name="x_qt_prazoMeses" id="x_qt_prazoMeses" size="30" placeholder="<?php echo $laudo->qt_prazoMeses->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoMeses->EditValue ?>"<?php echo $laudo->qt_prazoMeses->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_qt_prazoMeses">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_qt_prazoMeses">
<input type="text" data-field="x_qt_prazoMeses" name="y_qt_prazoMeses" id="y_qt_prazoMeses" size="30" placeholder="<?php echo $laudo->qt_prazoMeses->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoMeses->EditValue2 ?>"<?php echo $laudo->qt_prazoMeses->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($laudo->qt_prazoDias->Visible) { // qt_prazoDias ?>
	<span id="xsc_qt_prazoDias" class="ewCell">
		<span class="ewSearchCaption"><?php echo $laudo->qt_prazoDias->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_qt_prazoDias" id="z_qt_prazoDias" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_qt_prazoDias" name="x_qt_prazoDias" id="x_qt_prazoDias" size="30" placeholder="<?php echo $laudo->qt_prazoDias->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoDias->EditValue ?>"<?php echo $laudo->qt_prazoDias->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_qt_prazoDias">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_qt_prazoDias">
<input type="text" data-field="x_qt_prazoDias" name="y_qt_prazoDias" id="y_qt_prazoDias" size="30" placeholder="<?php echo $laudo->qt_prazoDias->PlaceHolder ?>" value="<?php echo $laudo->qt_prazoDias->EditValue2 ?>"<?php echo $laudo->qt_prazoDias->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($laudo->vr_contratacao->Visible) { // vr_contratacao ?>
	<span id="xsc_vr_contratacao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $laudo->vr_contratacao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_vr_contratacao" id="z_vr_contratacao" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_vr_contratacao" name="x_vr_contratacao" id="x_vr_contratacao" size="30" placeholder="<?php echo $laudo->vr_contratacao->PlaceHolder ?>" value="<?php echo $laudo->vr_contratacao->EditValue ?>"<?php echo $laudo->vr_contratacao->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_vr_contratacao">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_vr_contratacao">
<input type="text" data-field="x_vr_contratacao" name="y_vr_contratacao" id="y_vr_contratacao" size="30" placeholder="<?php echo $laudo->vr_contratacao->PlaceHolder ?>" value="<?php echo $laudo->vr_contratacao->EditValue2 ?>"<?php echo $laudo->vr_contratacao->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($laudo->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<span id="xsc_nu_usuarioResp" class="ewCell">
		<span class="ewSearchCaption"><?php echo $laudo->nu_usuarioResp->FldCaption() ?></span>
		<span class="ewSearchOperator"><select name="z_nu_usuarioResp" id="z_nu_usuarioResp" class="input-medium" onchange="ewForms(this).SrchOprChanged(this);"><option value="="<?php echo ($laudo->nu_usuarioResp->AdvancedSearch->SearchOperator=="=") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("=") ?></option><option value="<>"<?php echo ($laudo->nu_usuarioResp->AdvancedSearch->SearchOperator=="<>") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("<>") ?></option><option value="<"<?php echo ($laudo->nu_usuarioResp->AdvancedSearch->SearchOperator=="<") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("<") ?></option><option value="<="<?php echo ($laudo->nu_usuarioResp->AdvancedSearch->SearchOperator=="<=") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("<=") ?></option><option value=">"<?php echo ($laudo->nu_usuarioResp->AdvancedSearch->SearchOperator==">") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase(">") ?></option><option value=">="<?php echo ($laudo->nu_usuarioResp->AdvancedSearch->SearchOperator==">=") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase(">=") ?></option><option value="IS NULL"<?php echo ($laudo->nu_usuarioResp->AdvancedSearch->SearchOperator=="IS NULL") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("IS NULL") ?></option><option value="IS NOT NULL"<?php echo ($laudo->nu_usuarioResp->AdvancedSearch->SearchOperator=="IS NOT NULL") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("IS NOT NULL") ?></option><option value="BETWEEN"<?php echo ($laudo->nu_usuarioResp->AdvancedSearch->SearchOperator=="BETWEEN") ? " selected=\"selected\"" : "" ?> ><?php echo $Language->Phrase("BETWEEN") ?></option></select></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_usuarioResp" id="x_nu_usuarioResp" name="x_nu_usuarioResp"<?php echo $laudo->nu_usuarioResp->EditAttributes() ?>>
<?php
if (is_array($laudo->nu_usuarioResp->EditValue)) {
	$arwrk = $laudo->nu_usuarioResp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->nu_usuarioResp->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
flaudolistsrch.Lists["x_nu_usuarioResp"].Options = <?php echo (is_array($laudo->nu_usuarioResp->EditValue)) ? ew_ArrayToJson($laudo->nu_usuarioResp->EditValue, 1) : "[]" ?>;
</script>
</span>
		<span class="ewSearchCond btw1_nu_usuarioResp" style="display: none">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_nu_usuarioResp" style="display: none">
<select data-field="x_nu_usuarioResp" id="y_nu_usuarioResp" name="y_nu_usuarioResp"<?php echo $laudo->nu_usuarioResp->EditAttributes() ?>>
<?php
if (is_array($laudo->nu_usuarioResp->EditValue2)) {
	$arwrk = $laudo->nu_usuarioResp->EditValue2;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($laudo->nu_usuarioResp->AdvancedSearch->SearchValue2) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
flaudolistsrch.Lists["x_nu_usuarioResp"].Options = <?php echo (is_array($laudo->nu_usuarioResp->EditValue2)) ? ew_ArrayToJson($laudo->nu_usuarioResp->EditValue2, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($laudo->dt_emissao->Visible) { // dt_emissao ?>
	<span id="xsc_dt_emissao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $laudo->dt_emissao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_dt_emissao" id="z_dt_emissao" value="BETWEEN"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_dt_emissao" name="x_dt_emissao" id="x_dt_emissao" placeholder="<?php echo $laudo->dt_emissao->PlaceHolder ?>" value="<?php echo $laudo->dt_emissao->EditValue ?>"<?php echo $laudo->dt_emissao->EditAttributes() ?>>
<?php if (!$laudo->dt_emissao->ReadOnly && !$laudo->dt_emissao->Disabled && @$laudo->dt_emissao->EditAttrs["readonly"] == "" && @$laudo->dt_emissao->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_emissao" name="cal_x_dt_emissao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_emissao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flaudolistsrch", "x_dt_emissao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
		<span class="ewSearchCond btw1_dt_emissao">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="control-group ewSearchField btw1_dt_emissao">
<input type="text" data-field="x_dt_emissao" name="y_dt_emissao" id="y_dt_emissao" placeholder="<?php echo $laudo->dt_emissao->PlaceHolder ?>" value="<?php echo $laudo->dt_emissao->EditValue2 ?>"<?php echo $laudo->dt_emissao->EditAttributes() ?>>
<?php if (!$laudo->dt_emissao->ReadOnly && !$laudo->dt_emissao->Disabled && @$laudo->dt_emissao->EditAttrs["readonly"] == "" && @$laudo->dt_emissao->EditAttrs["disabled"] == "") { ?>
<button id="cal_y_dt_emissao" name="cal_y_dt_emissao" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_y_dt_emissao" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("flaudolistsrch", "y_dt_emissao", "%d/%m/%Y");
</script>
<?php } ?>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $laudo_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $laudo_list->ShowPageHeader(); ?>
<?php
$laudo_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="flaudolist" id="flaudolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="laudo">
<div id="gmp_laudo" class="ewGridMiddlePanel">
<?php if ($laudo_list->TotalRecs > 0) { ?>
<table id="tbl_laudolist" class="ewTable ewTableSeparate">
<?php echo $laudo->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$laudo_list->RenderListOptions();

// Render list options (header, left)
$laudo_list->ListOptions->Render("header", "left");
?>
<?php if ($laudo->nu_solicitacao->Visible) { // nu_solicitacao ?>
	<?php if ($laudo->SortUrl($laudo->nu_solicitacao) == "") { ?>
		<td><div id="elh_laudo_nu_solicitacao" class="laudo_nu_solicitacao"><div class="ewTableHeaderCaption"><?php echo $laudo->nu_solicitacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->nu_solicitacao) ?>',2);"><div id="elh_laudo_nu_solicitacao" class="laudo_nu_solicitacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->nu_solicitacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->nu_solicitacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->nu_solicitacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->nu_versao->Visible) { // nu_versao ?>
	<?php if ($laudo->SortUrl($laudo->nu_versao) == "") { ?>
		<td><div id="elh_laudo_nu_versao" class="laudo_nu_versao"><div class="ewTableHeaderCaption"><?php echo $laudo->nu_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->nu_versao) ?>',2);"><div id="elh_laudo_nu_versao" class="laudo_nu_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->nu_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->nu_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->nu_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->qt_pf->Visible) { // qt_pf ?>
	<?php if ($laudo->SortUrl($laudo->qt_pf) == "") { ?>
		<td><div id="elh_laudo_qt_pf" class="laudo_qt_pf"><div class="ewTableHeaderCaption"><?php echo $laudo->qt_pf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->qt_pf) ?>',2);"><div id="elh_laudo_qt_pf" class="laudo_qt_pf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->qt_pf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->qt_pf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->qt_pf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->qt_horas->Visible) { // qt_horas ?>
	<?php if ($laudo->SortUrl($laudo->qt_horas) == "") { ?>
		<td><div id="elh_laudo_qt_horas" class="laudo_qt_horas"><div class="ewTableHeaderCaption"><?php echo $laudo->qt_horas->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->qt_horas) ?>',2);"><div id="elh_laudo_qt_horas" class="laudo_qt_horas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->qt_horas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->qt_horas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->qt_horas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->qt_prazoMeses->Visible) { // qt_prazoMeses ?>
	<?php if ($laudo->SortUrl($laudo->qt_prazoMeses) == "") { ?>
		<td><div id="elh_laudo_qt_prazoMeses" class="laudo_qt_prazoMeses"><div class="ewTableHeaderCaption"><?php echo $laudo->qt_prazoMeses->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->qt_prazoMeses) ?>',2);"><div id="elh_laudo_qt_prazoMeses" class="laudo_qt_prazoMeses">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->qt_prazoMeses->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->qt_prazoMeses->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->qt_prazoMeses->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->qt_prazoDias->Visible) { // qt_prazoDias ?>
	<?php if ($laudo->SortUrl($laudo->qt_prazoDias) == "") { ?>
		<td><div id="elh_laudo_qt_prazoDias" class="laudo_qt_prazoDias"><div class="ewTableHeaderCaption"><?php echo $laudo->qt_prazoDias->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->qt_prazoDias) ?>',2);"><div id="elh_laudo_qt_prazoDias" class="laudo_qt_prazoDias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->qt_prazoDias->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->qt_prazoDias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->qt_prazoDias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->vr_contratacao->Visible) { // vr_contratacao ?>
	<?php if ($laudo->SortUrl($laudo->vr_contratacao) == "") { ?>
		<td><div id="elh_laudo_vr_contratacao" class="laudo_vr_contratacao"><div class="ewTableHeaderCaption"><?php echo $laudo->vr_contratacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->vr_contratacao) ?>',2);"><div id="elh_laudo_vr_contratacao" class="laudo_vr_contratacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->vr_contratacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->vr_contratacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->vr_contratacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<?php if ($laudo->SortUrl($laudo->nu_usuarioResp) == "") { ?>
		<td><div id="elh_laudo_nu_usuarioResp" class="laudo_nu_usuarioResp"><div class="ewTableHeaderCaption"><?php echo $laudo->nu_usuarioResp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->nu_usuarioResp) ?>',2);"><div id="elh_laudo_nu_usuarioResp" class="laudo_nu_usuarioResp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->nu_usuarioResp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->nu_usuarioResp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->nu_usuarioResp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->dt_inicioSolicitacao->Visible) { // dt_inicioSolicitacao ?>
	<?php if ($laudo->SortUrl($laudo->dt_inicioSolicitacao) == "") { ?>
		<td><div id="elh_laudo_dt_inicioSolicitacao" class="laudo_dt_inicioSolicitacao"><div class="ewTableHeaderCaption"><?php echo $laudo->dt_inicioSolicitacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->dt_inicioSolicitacao) ?>',2);"><div id="elh_laudo_dt_inicioSolicitacao" class="laudo_dt_inicioSolicitacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->dt_inicioSolicitacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->dt_inicioSolicitacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->dt_inicioSolicitacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->dt_inicioContagem->Visible) { // dt_inicioContagem ?>
	<?php if ($laudo->SortUrl($laudo->dt_inicioContagem) == "") { ?>
		<td><div id="elh_laudo_dt_inicioContagem" class="laudo_dt_inicioContagem"><div class="ewTableHeaderCaption"><?php echo $laudo->dt_inicioContagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->dt_inicioContagem) ?>',2);"><div id="elh_laudo_dt_inicioContagem" class="laudo_dt_inicioContagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->dt_inicioContagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->dt_inicioContagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->dt_inicioContagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->dt_emissao->Visible) { // dt_emissao ?>
	<?php if ($laudo->SortUrl($laudo->dt_emissao) == "") { ?>
		<td><div id="elh_laudo_dt_emissao" class="laudo_dt_emissao"><div class="ewTableHeaderCaption"><?php echo $laudo->dt_emissao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->dt_emissao) ?>',2);"><div id="elh_laudo_dt_emissao" class="laudo_dt_emissao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->dt_emissao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->dt_emissao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->dt_emissao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->hh_emissao->Visible) { // hh_emissao ?>
	<?php if ($laudo->SortUrl($laudo->hh_emissao) == "") { ?>
		<td><div id="elh_laudo_hh_emissao" class="laudo_hh_emissao"><div class="ewTableHeaderCaption"><?php echo $laudo->hh_emissao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->hh_emissao) ?>',2);"><div id="elh_laudo_hh_emissao" class="laudo_hh_emissao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->hh_emissao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->hh_emissao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->hh_emissao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->ic_tamanho->Visible) { // ic_tamanho ?>
	<?php if ($laudo->SortUrl($laudo->ic_tamanho) == "") { ?>
		<td><div id="elh_laudo_ic_tamanho" class="laudo_ic_tamanho"><div class="ewTableHeaderCaption"><?php echo $laudo->ic_tamanho->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->ic_tamanho) ?>',2);"><div id="elh_laudo_ic_tamanho" class="laudo_ic_tamanho">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->ic_tamanho->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->ic_tamanho->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->ic_tamanho->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->ic_esforco->Visible) { // ic_esforco ?>
	<?php if ($laudo->SortUrl($laudo->ic_esforco) == "") { ?>
		<td><div id="elh_laudo_ic_esforco" class="laudo_ic_esforco"><div class="ewTableHeaderCaption"><?php echo $laudo->ic_esforco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->ic_esforco) ?>',2);"><div id="elh_laudo_ic_esforco" class="laudo_ic_esforco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->ic_esforco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->ic_esforco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->ic_esforco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->ic_prazo->Visible) { // ic_prazo ?>
	<?php if ($laudo->SortUrl($laudo->ic_prazo) == "") { ?>
		<td><div id="elh_laudo_ic_prazo" class="laudo_ic_prazo"><div class="ewTableHeaderCaption"><?php echo $laudo->ic_prazo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->ic_prazo) ?>',2);"><div id="elh_laudo_ic_prazo" class="laudo_ic_prazo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->ic_prazo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->ic_prazo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->ic_prazo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($laudo->ic_custo->Visible) { // ic_custo ?>
	<?php if ($laudo->SortUrl($laudo->ic_custo) == "") { ?>
		<td><div id="elh_laudo_ic_custo" class="laudo_ic_custo"><div class="ewTableHeaderCaption"><?php echo $laudo->ic_custo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $laudo->SortUrl($laudo->ic_custo) ?>',2);"><div id="elh_laudo_ic_custo" class="laudo_ic_custo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $laudo->ic_custo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($laudo->ic_custo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($laudo->ic_custo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$laudo_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($laudo->ExportAll && $laudo->Export <> "") {
	$laudo_list->StopRec = $laudo_list->TotalRecs;
} else {

	// Set the last record to display
	if ($laudo_list->TotalRecs > $laudo_list->StartRec + $laudo_list->DisplayRecs - 1)
		$laudo_list->StopRec = $laudo_list->StartRec + $laudo_list->DisplayRecs - 1;
	else
		$laudo_list->StopRec = $laudo_list->TotalRecs;
}
$laudo_list->RecCnt = $laudo_list->StartRec - 1;
if ($laudo_list->Recordset && !$laudo_list->Recordset->EOF) {
	$laudo_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $laudo_list->StartRec > 1)
		$laudo_list->Recordset->Move($laudo_list->StartRec - 1);
} elseif (!$laudo->AllowAddDeleteRow && $laudo_list->StopRec == 0) {
	$laudo_list->StopRec = $laudo->GridAddRowCount;
}

// Initialize aggregate
$laudo->RowType = EW_ROWTYPE_AGGREGATEINIT;
$laudo->ResetAttrs();
$laudo_list->RenderRow();
while ($laudo_list->RecCnt < $laudo_list->StopRec) {
	$laudo_list->RecCnt++;
	if (intval($laudo_list->RecCnt) >= intval($laudo_list->StartRec)) {
		$laudo_list->RowCnt++;

		// Set up key count
		$laudo_list->KeyCount = $laudo_list->RowIndex;

		// Init row class and style
		$laudo->ResetAttrs();
		$laudo->CssClass = "";
		if ($laudo->CurrentAction == "gridadd") {
		} else {
			$laudo_list->LoadRowValues($laudo_list->Recordset); // Load row values
		}
		$laudo->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$laudo->RowAttrs = array_merge($laudo->RowAttrs, array('data-rowindex'=>$laudo_list->RowCnt, 'id'=>'r' . $laudo_list->RowCnt . '_laudo', 'data-rowtype'=>$laudo->RowType));

		// Render row
		$laudo_list->RenderRow();

		// Render list options
		$laudo_list->RenderListOptions();
?>
	<tr<?php echo $laudo->RowAttributes() ?>>
<?php

// Render list options (body, left)
$laudo_list->ListOptions->Render("body", "left", $laudo_list->RowCnt);
?>
	<?php if ($laudo->nu_solicitacao->Visible) { // nu_solicitacao ?>
		<td<?php echo $laudo->nu_solicitacao->CellAttributes() ?>>
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->nu_versao->Visible) { // nu_versao ?>
		<td<?php echo $laudo->nu_versao->CellAttributes() ?>>
<span<?php echo $laudo->nu_versao->ViewAttributes() ?>>
<?php echo $laudo->nu_versao->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->qt_pf->Visible) { // qt_pf ?>
		<td<?php echo $laudo->qt_pf->CellAttributes() ?>>
<span<?php echo $laudo->qt_pf->ViewAttributes() ?>>
<?php echo $laudo->qt_pf->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->qt_horas->Visible) { // qt_horas ?>
		<td<?php echo $laudo->qt_horas->CellAttributes() ?>>
<span<?php echo $laudo->qt_horas->ViewAttributes() ?>>
<?php echo $laudo->qt_horas->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->qt_prazoMeses->Visible) { // qt_prazoMeses ?>
		<td<?php echo $laudo->qt_prazoMeses->CellAttributes() ?>>
<span<?php echo $laudo->qt_prazoMeses->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoMeses->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->qt_prazoDias->Visible) { // qt_prazoDias ?>
		<td<?php echo $laudo->qt_prazoDias->CellAttributes() ?>>
<span<?php echo $laudo->qt_prazoDias->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoDias->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->vr_contratacao->Visible) { // vr_contratacao ?>
		<td<?php echo $laudo->vr_contratacao->CellAttributes() ?>>
<span<?php echo $laudo->vr_contratacao->ViewAttributes() ?>>
<?php echo $laudo->vr_contratacao->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
		<td<?php echo $laudo->nu_usuarioResp->CellAttributes() ?>>
<span<?php echo $laudo->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $laudo->nu_usuarioResp->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->dt_inicioSolicitacao->Visible) { // dt_inicioSolicitacao ?>
		<td<?php echo $laudo->dt_inicioSolicitacao->CellAttributes() ?>>
<span<?php echo $laudo->dt_inicioSolicitacao->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioSolicitacao->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->dt_inicioContagem->Visible) { // dt_inicioContagem ?>
		<td<?php echo $laudo->dt_inicioContagem->CellAttributes() ?>>
<span<?php echo $laudo->dt_inicioContagem->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioContagem->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->dt_emissao->Visible) { // dt_emissao ?>
		<td<?php echo $laudo->dt_emissao->CellAttributes() ?>>
<span<?php echo $laudo->dt_emissao->ViewAttributes() ?>>
<?php echo $laudo->dt_emissao->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->hh_emissao->Visible) { // hh_emissao ?>
		<td<?php echo $laudo->hh_emissao->CellAttributes() ?>>
<span<?php echo $laudo->hh_emissao->ViewAttributes() ?>>
<?php echo $laudo->hh_emissao->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->ic_tamanho->Visible) { // ic_tamanho ?>
		<td<?php echo $laudo->ic_tamanho->CellAttributes() ?>>
<span<?php echo $laudo->ic_tamanho->ViewAttributes() ?>>
<?php echo $laudo->ic_tamanho->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->ic_esforco->Visible) { // ic_esforco ?>
		<td<?php echo $laudo->ic_esforco->CellAttributes() ?>>
<span<?php echo $laudo->ic_esforco->ViewAttributes() ?>>
<?php echo $laudo->ic_esforco->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->ic_prazo->Visible) { // ic_prazo ?>
		<td<?php echo $laudo->ic_prazo->CellAttributes() ?>>
<span<?php echo $laudo->ic_prazo->ViewAttributes() ?>>
<?php echo $laudo->ic_prazo->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($laudo->ic_custo->Visible) { // ic_custo ?>
		<td<?php echo $laudo->ic_custo->CellAttributes() ?>>
<span<?php echo $laudo->ic_custo->ViewAttributes() ?>>
<?php echo $laudo->ic_custo->ListViewValue() ?></span>
<a id="<?php echo $laudo_list->PageObjName . "_row_" . $laudo_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$laudo_list->ListOptions->Render("body", "right", $laudo_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($laudo->CurrentAction <> "gridadd")
		$laudo_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($laudo->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($laudo_list->Recordset)
	$laudo_list->Recordset->Close();
?>
<?php if ($laudo->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($laudo->CurrentAction <> "gridadd" && $laudo->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($laudo_list->Pager)) $laudo_list->Pager = new cNumericPager($laudo_list->StartRec, $laudo_list->DisplayRecs, $laudo_list->TotalRecs, $laudo_list->RecRange) ?>
<?php if ($laudo_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($laudo_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $laudo_list->PageUrl() ?>start=<?php echo $laudo_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($laudo_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $laudo_list->PageUrl() ?>start=<?php echo $laudo_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($laudo_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $laudo_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($laudo_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $laudo_list->PageUrl() ?>start=<?php echo $laudo_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($laudo_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $laudo_list->PageUrl() ?>start=<?php echo $laudo_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($laudo_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $laudo_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $laudo_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $laudo_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($laudo_list->SearchWhere == "0=101") { ?>
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
	foreach ($laudo_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($laudo->Export == "") { ?>
<script type="text/javascript">
flaudolistsrch.Init();
flaudolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$laudo_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($laudo->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$laudo_list->Page_Terminate();
?>
