<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "prospectoinfo.php" ?>
<?php include_once "rprospresumoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "prospecto_itempdtigridcls.php" ?>
<?php include_once "prospectoocorrenciasgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$prospecto_list = NULL; // Initialize page object first

class cprospecto_list extends cprospecto {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'prospecto';

	// Page object name
	var $PageObjName = 'prospecto_list';

	// Grid form hidden field names
	var $FormName = 'fprospectolist';
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

		// Table object (prospecto)
		if (!isset($GLOBALS["prospecto"])) {
			$GLOBALS["prospecto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["prospecto"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "prospectoadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "prospectodelete.php";
		$this->MultiUpdateUrl = "prospectoupdate.php";

		// Table object (rprospresumo)
		if (!isset($GLOBALS['rprospresumo'])) $GLOBALS['rprospresumo'] = new crprospresumo();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'prospecto', TRUE);

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
		$this->nu_prospecto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "rprospresumo") {
			global $rprospresumo;
			$rsmaster = $rprospresumo->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("rprospresumolist.php"); // Return to master page
			} else {
				$rprospresumo->LoadListRowValues($rsmaster);
				$rprospresumo->RowType = EW_ROWTYPE_MASTER; // Master row
				$rprospresumo->RenderListRow();
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
			$this->nu_prospecto->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_prospecto->FormValue))
				return FALSE;
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
		$this->BuildSearchSql($sWhere, $this->nu_area, FALSE); // nu_area
		$this->BuildSearchSql($sWhere, $this->no_solicitante, FALSE); // no_solicitante
		$this->BuildSearchSql($sWhere, $this->no_patrocinador, FALSE); // no_patrocinador
		$this->BuildSearchSql($sWhere, $this->ar_entidade, TRUE); // ar_entidade
		$this->BuildSearchSql($sWhere, $this->ar_nivel, TRUE); // ar_nivel
		$this->BuildSearchSql($sWhere, $this->nu_categoriaProspecto, FALSE); // nu_categoriaProspecto
		$this->BuildSearchSql($sWhere, $this->nu_alternativaImpacto, FALSE); // nu_alternativaImpacto
		$this->BuildSearchSql($sWhere, $this->ds_sistemas, FALSE); // ds_sistemas
		$this->BuildSearchSql($sWhere, $this->ds_impactoNaoImplem, FALSE); // ds_impactoNaoImplem
		$this->BuildSearchSql($sWhere, $this->nu_alternativaAlinhamento, FALSE); // nu_alternativaAlinhamento
		$this->BuildSearchSql($sWhere, $this->nu_alternativaAbrangencia, FALSE); // nu_alternativaAbrangencia
		$this->BuildSearchSql($sWhere, $this->nu_alternativaUrgencia, FALSE); // nu_alternativaUrgencia
		$this->BuildSearchSql($sWhere, $this->dt_prazo, FALSE); // dt_prazo
		$this->BuildSearchSql($sWhere, $this->nu_alternativaTmpEstimado, FALSE); // nu_alternativaTmpEstimado
		$this->BuildSearchSql($sWhere, $this->nu_alternativaTmpFila, FALSE); // nu_alternativaTmpFila
		$this->BuildSearchSql($sWhere, $this->ic_implicacaoLegal, FALSE); // ic_implicacaoLegal
		$this->BuildSearchSql($sWhere, $this->ic_risco, FALSE); // ic_risco
		$this->BuildSearchSql($sWhere, $this->ic_stProspecto, FALSE); // ic_stProspecto
		$this->BuildSearchSql($sWhere, $this->ds_observacoes, FALSE); // ds_observacoes
		$this->BuildSearchSql($sWhere, $this->ic_ativo, FALSE); // ic_ativo

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_prospecto->AdvancedSearch->Save(); // nu_prospecto
			$this->no_prospecto->AdvancedSearch->Save(); // no_prospecto
			$this->nu_area->AdvancedSearch->Save(); // nu_area
			$this->no_solicitante->AdvancedSearch->Save(); // no_solicitante
			$this->no_patrocinador->AdvancedSearch->Save(); // no_patrocinador
			$this->ar_entidade->AdvancedSearch->Save(); // ar_entidade
			$this->ar_nivel->AdvancedSearch->Save(); // ar_nivel
			$this->nu_categoriaProspecto->AdvancedSearch->Save(); // nu_categoriaProspecto
			$this->nu_alternativaImpacto->AdvancedSearch->Save(); // nu_alternativaImpacto
			$this->ds_sistemas->AdvancedSearch->Save(); // ds_sistemas
			$this->ds_impactoNaoImplem->AdvancedSearch->Save(); // ds_impactoNaoImplem
			$this->nu_alternativaAlinhamento->AdvancedSearch->Save(); // nu_alternativaAlinhamento
			$this->nu_alternativaAbrangencia->AdvancedSearch->Save(); // nu_alternativaAbrangencia
			$this->nu_alternativaUrgencia->AdvancedSearch->Save(); // nu_alternativaUrgencia
			$this->dt_prazo->AdvancedSearch->Save(); // dt_prazo
			$this->nu_alternativaTmpEstimado->AdvancedSearch->Save(); // nu_alternativaTmpEstimado
			$this->nu_alternativaTmpFila->AdvancedSearch->Save(); // nu_alternativaTmpFila
			$this->ic_implicacaoLegal->AdvancedSearch->Save(); // ic_implicacaoLegal
			$this->ic_risco->AdvancedSearch->Save(); // ic_risco
			$this->ic_stProspecto->AdvancedSearch->Save(); // ic_stProspecto
			$this->ds_observacoes->AdvancedSearch->Save(); // ds_observacoes
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
		if ($this->nu_prospecto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_prospecto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_area->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_solicitante->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_patrocinador->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ar_entidade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ar_nivel->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_categoriaProspecto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_alternativaImpacto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_sistemas->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_impactoNaoImplem->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_alternativaAlinhamento->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_alternativaAbrangencia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_alternativaUrgencia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dt_prazo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_alternativaTmpEstimado->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_alternativaTmpFila->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_implicacaoLegal->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_risco->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_stProspecto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_observacoes->AdvancedSearch->IssetSession())
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
		return FALSE;
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->nu_prospecto->AdvancedSearch->UnsetSession();
		$this->no_prospecto->AdvancedSearch->UnsetSession();
		$this->nu_area->AdvancedSearch->UnsetSession();
		$this->no_solicitante->AdvancedSearch->UnsetSession();
		$this->no_patrocinador->AdvancedSearch->UnsetSession();
		$this->ar_entidade->AdvancedSearch->UnsetSession();
		$this->ar_nivel->AdvancedSearch->UnsetSession();
		$this->nu_categoriaProspecto->AdvancedSearch->UnsetSession();
		$this->nu_alternativaImpacto->AdvancedSearch->UnsetSession();
		$this->ds_sistemas->AdvancedSearch->UnsetSession();
		$this->ds_impactoNaoImplem->AdvancedSearch->UnsetSession();
		$this->nu_alternativaAlinhamento->AdvancedSearch->UnsetSession();
		$this->nu_alternativaAbrangencia->AdvancedSearch->UnsetSession();
		$this->nu_alternativaUrgencia->AdvancedSearch->UnsetSession();
		$this->dt_prazo->AdvancedSearch->UnsetSession();
		$this->nu_alternativaTmpEstimado->AdvancedSearch->UnsetSession();
		$this->nu_alternativaTmpFila->AdvancedSearch->UnsetSession();
		$this->ic_implicacaoLegal->AdvancedSearch->UnsetSession();
		$this->ic_risco->AdvancedSearch->UnsetSession();
		$this->ic_stProspecto->AdvancedSearch->UnsetSession();
		$this->ds_observacoes->AdvancedSearch->UnsetSession();
		$this->ic_ativo->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_prospecto->AdvancedSearch->Load();
		$this->no_prospecto->AdvancedSearch->Load();
		$this->nu_area->AdvancedSearch->Load();
		$this->no_solicitante->AdvancedSearch->Load();
		$this->no_patrocinador->AdvancedSearch->Load();
		$this->ar_entidade->AdvancedSearch->Load();
		$this->ar_nivel->AdvancedSearch->Load();
		$this->nu_categoriaProspecto->AdvancedSearch->Load();
		$this->nu_alternativaImpacto->AdvancedSearch->Load();
		$this->ds_sistemas->AdvancedSearch->Load();
		$this->ds_impactoNaoImplem->AdvancedSearch->Load();
		$this->nu_alternativaAlinhamento->AdvancedSearch->Load();
		$this->nu_alternativaAbrangencia->AdvancedSearch->Load();
		$this->nu_alternativaUrgencia->AdvancedSearch->Load();
		$this->dt_prazo->AdvancedSearch->Load();
		$this->nu_alternativaTmpEstimado->AdvancedSearch->Load();
		$this->nu_alternativaTmpFila->AdvancedSearch->Load();
		$this->ic_implicacaoLegal->AdvancedSearch->Load();
		$this->ic_risco->AdvancedSearch->Load();
		$this->ic_stProspecto->AdvancedSearch->Load();
		$this->ds_observacoes->AdvancedSearch->Load();
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
			$this->UpdateSort($this->nu_prospecto, $bCtrl); // nu_prospecto
			$this->UpdateSort($this->no_prospecto, $bCtrl); // no_prospecto
			$this->UpdateSort($this->nu_area, $bCtrl); // nu_area
			$this->UpdateSort($this->nu_categoriaProspecto, $bCtrl); // nu_categoriaProspecto
			$this->UpdateSort($this->ic_stProspecto, $bCtrl); // ic_stProspecto
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->nu_prospecto->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->nu_prospecto->setSort("");
				$this->no_prospecto->setSort("");
				$this->nu_area->setSort("");
				$this->nu_categoriaProspecto->setSort("");
				$this->ic_stProspecto->setSort("");
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

		// "detail_prospecto_itempdti"
		$item = &$this->ListOptions->Add("detail_prospecto_itempdti");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'prospecto_itempdti') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["prospecto_itempdti_grid"])) $GLOBALS["prospecto_itempdti_grid"] = new cprospecto_itempdti_grid;

		// "detail_prospectoocorrencias"
		$item = &$this->ListOptions->Add("detail_prospectoocorrencias");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'prospectoocorrencias') && !$this->ShowMultipleDetails;
		$item->OnLeft = FALSE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["prospectoocorrencias_grid"])) $GLOBALS["prospectoocorrencias_grid"] = new cprospectoocorrencias_grid;

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

		// "detail_prospecto_itempdti"
		$oListOpt = &$this->ListOptions->Items["detail_prospecto_itempdti"];
		if ($Security->AllowList(CurrentProjectID() . 'prospecto_itempdti')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("prospecto_itempdti", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("prospecto_itempdtilist.php?" . EW_TABLE_SHOW_MASTER . "=prospecto&nu_prospecto=" . strval($this->nu_prospecto->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["prospecto_itempdti_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'prospecto_itempdti')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=prospecto_itempdti")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "prospecto_itempdti";
			}
			if ($GLOBALS["prospecto_itempdti_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'prospecto_itempdti')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=prospecto_itempdti")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "prospecto_itempdti";
			}
			if ($links <> "") {
				$body .= "<button class=\"btn btn-small dropdown-toggle\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}

		// "detail_prospectoocorrencias"
		$oListOpt = &$this->ListOptions->Items["detail_prospectoocorrencias"];
		if ($Security->AllowList(CurrentProjectID() . 'prospectoocorrencias')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("prospectoocorrencias", "TblCaption");
			$body = "<a class=\"btn btn-small ewRowLink ewDetailList\" data-action=\"list\" href=\"" . ew_HtmlEncode("prospectoocorrenciaslist.php?" . EW_TABLE_SHOW_MASTER . "=prospecto&nu_prospecto=" . strval($this->nu_prospecto->CurrentValue) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["prospectoocorrencias_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'prospectoocorrencias')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=prospectoocorrencias")) . "\">" . $Language->Phrase("MasterDetailViewLink") . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "prospectoocorrencias";
			}
			if ($GLOBALS["prospectoocorrencias_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'prospectoocorrencias')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=prospectoocorrencias")) . "\">" . $Language->Phrase("MasterDetailEditLink") . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "prospectoocorrencias";
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
		$item = &$option->Add("detailadd_prospecto_itempdti");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=prospecto_itempdti") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["prospecto_itempdti"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["prospecto_itempdti"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'prospecto_itempdti') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "prospecto_itempdti";
		}
		$item = &$option->Add("detailadd_prospectoocorrencias");
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" href=\"" . ew_HtmlEncode($this->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=prospectoocorrencias") . "\">" . $Language->Phrase("AddLink") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["prospectoocorrencias"]->TableCaption() . "</a>";
		$item->Visible = ($GLOBALS["prospectoocorrencias"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'prospectoocorrencias') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "prospectoocorrencias";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fprospectolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_prospecto

		$this->nu_prospecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_prospecto"]);
		if ($this->nu_prospecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_prospecto->AdvancedSearch->SearchOperator = @$_GET["z_nu_prospecto"];

		// no_prospecto
		$this->no_prospecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_prospecto"]);
		if ($this->no_prospecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_prospecto->AdvancedSearch->SearchOperator = @$_GET["z_no_prospecto"];

		// nu_area
		$this->nu_area->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_area"]);
		if ($this->nu_area->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_area->AdvancedSearch->SearchOperator = @$_GET["z_nu_area"];

		// no_solicitante
		$this->no_solicitante->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_solicitante"]);
		if ($this->no_solicitante->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_solicitante->AdvancedSearch->SearchOperator = @$_GET["z_no_solicitante"];

		// no_patrocinador
		$this->no_patrocinador->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_patrocinador"]);
		if ($this->no_patrocinador->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_patrocinador->AdvancedSearch->SearchOperator = @$_GET["z_no_patrocinador"];

		// ar_entidade
		$this->ar_entidade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ar_entidade"]);
		if ($this->ar_entidade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ar_entidade->AdvancedSearch->SearchOperator = @$_GET["z_ar_entidade"];
		if (is_array($this->ar_entidade->AdvancedSearch->SearchValue)) $this->ar_entidade->AdvancedSearch->SearchValue = implode(",", $this->ar_entidade->AdvancedSearch->SearchValue);
		if (is_array($this->ar_entidade->AdvancedSearch->SearchValue2)) $this->ar_entidade->AdvancedSearch->SearchValue2 = implode(",", $this->ar_entidade->AdvancedSearch->SearchValue2);

		// ar_nivel
		$this->ar_nivel->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ar_nivel"]);
		if ($this->ar_nivel->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ar_nivel->AdvancedSearch->SearchOperator = @$_GET["z_ar_nivel"];
		if (is_array($this->ar_nivel->AdvancedSearch->SearchValue)) $this->ar_nivel->AdvancedSearch->SearchValue = implode(",", $this->ar_nivel->AdvancedSearch->SearchValue);
		if (is_array($this->ar_nivel->AdvancedSearch->SearchValue2)) $this->ar_nivel->AdvancedSearch->SearchValue2 = implode(",", $this->ar_nivel->AdvancedSearch->SearchValue2);

		// nu_categoriaProspecto
		$this->nu_categoriaProspecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_categoriaProspecto"]);
		if ($this->nu_categoriaProspecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_categoriaProspecto->AdvancedSearch->SearchOperator = @$_GET["z_nu_categoriaProspecto"];

		// nu_alternativaImpacto
		$this->nu_alternativaImpacto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_alternativaImpacto"]);
		if ($this->nu_alternativaImpacto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_alternativaImpacto->AdvancedSearch->SearchOperator = @$_GET["z_nu_alternativaImpacto"];

		// ds_sistemas
		$this->ds_sistemas->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_sistemas"]);
		if ($this->ds_sistemas->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_sistemas->AdvancedSearch->SearchOperator = @$_GET["z_ds_sistemas"];

		// ds_impactoNaoImplem
		$this->ds_impactoNaoImplem->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_impactoNaoImplem"]);
		if ($this->ds_impactoNaoImplem->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_impactoNaoImplem->AdvancedSearch->SearchOperator = @$_GET["z_ds_impactoNaoImplem"];

		// nu_alternativaAlinhamento
		$this->nu_alternativaAlinhamento->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_alternativaAlinhamento"]);
		if ($this->nu_alternativaAlinhamento->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_alternativaAlinhamento->AdvancedSearch->SearchOperator = @$_GET["z_nu_alternativaAlinhamento"];

		// nu_alternativaAbrangencia
		$this->nu_alternativaAbrangencia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_alternativaAbrangencia"]);
		if ($this->nu_alternativaAbrangencia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_alternativaAbrangencia->AdvancedSearch->SearchOperator = @$_GET["z_nu_alternativaAbrangencia"];

		// nu_alternativaUrgencia
		$this->nu_alternativaUrgencia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_alternativaUrgencia"]);
		if ($this->nu_alternativaUrgencia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_alternativaUrgencia->AdvancedSearch->SearchOperator = @$_GET["z_nu_alternativaUrgencia"];

		// dt_prazo
		$this->dt_prazo->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dt_prazo"]);
		if ($this->dt_prazo->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dt_prazo->AdvancedSearch->SearchOperator = @$_GET["z_dt_prazo"];

		// nu_alternativaTmpEstimado
		$this->nu_alternativaTmpEstimado->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_alternativaTmpEstimado"]);
		if ($this->nu_alternativaTmpEstimado->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_alternativaTmpEstimado->AdvancedSearch->SearchOperator = @$_GET["z_nu_alternativaTmpEstimado"];

		// nu_alternativaTmpFila
		$this->nu_alternativaTmpFila->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_alternativaTmpFila"]);
		if ($this->nu_alternativaTmpFila->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_alternativaTmpFila->AdvancedSearch->SearchOperator = @$_GET["z_nu_alternativaTmpFila"];

		// ic_implicacaoLegal
		$this->ic_implicacaoLegal->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_implicacaoLegal"]);
		if ($this->ic_implicacaoLegal->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_implicacaoLegal->AdvancedSearch->SearchOperator = @$_GET["z_ic_implicacaoLegal"];

		// ic_risco
		$this->ic_risco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_risco"]);
		if ($this->ic_risco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_risco->AdvancedSearch->SearchOperator = @$_GET["z_ic_risco"];

		// ic_stProspecto
		$this->ic_stProspecto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_stProspecto"]);
		if ($this->ic_stProspecto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_stProspecto->AdvancedSearch->SearchOperator = @$_GET["z_ic_stProspecto"];

		// ds_observacoes
		$this->ds_observacoes->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_observacoes"]);
		if ($this->ds_observacoes->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_observacoes->AdvancedSearch->SearchOperator = @$_GET["z_ds_observacoes"];

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
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->no_solicitante->setDbValue($rs->fields('no_solicitante'));
		$this->no_patrocinador->setDbValue($rs->fields('no_patrocinador'));
		$this->ar_entidade->setDbValue($rs->fields('ar_entidade'));
		$this->ar_nivel->setDbValue($rs->fields('ar_nivel'));
		$this->nu_categoriaProspecto->setDbValue($rs->fields('nu_categoriaProspecto'));
		$this->nu_alternativaImpacto->setDbValue($rs->fields('nu_alternativaImpacto'));
		$this->ds_sistemas->setDbValue($rs->fields('ds_sistemas'));
		$this->ds_impactoNaoImplem->setDbValue($rs->fields('ds_impactoNaoImplem'));
		$this->nu_alternativaAlinhamento->setDbValue($rs->fields('nu_alternativaAlinhamento'));
		$this->nu_alternativaAbrangencia->setDbValue($rs->fields('nu_alternativaAbrangencia'));
		$this->nu_alternativaUrgencia->setDbValue($rs->fields('nu_alternativaUrgencia'));
		$this->dt_prazo->setDbValue($rs->fields('dt_prazo'));
		$this->nu_alternativaTmpEstimado->setDbValue($rs->fields('nu_alternativaTmpEstimado'));
		$this->nu_alternativaTmpFila->setDbValue($rs->fields('nu_alternativaTmpFila'));
		$this->ic_implicacaoLegal->setDbValue($rs->fields('ic_implicacaoLegal'));
		$this->ic_risco->setDbValue($rs->fields('ic_risco'));
		$this->ic_stProspecto->setDbValue($rs->fields('ic_stProspecto'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->no_prospecto->DbValue = $row['no_prospecto'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->no_solicitante->DbValue = $row['no_solicitante'];
		$this->no_patrocinador->DbValue = $row['no_patrocinador'];
		$this->ar_entidade->DbValue = $row['ar_entidade'];
		$this->ar_nivel->DbValue = $row['ar_nivel'];
		$this->nu_categoriaProspecto->DbValue = $row['nu_categoriaProspecto'];
		$this->nu_alternativaImpacto->DbValue = $row['nu_alternativaImpacto'];
		$this->ds_sistemas->DbValue = $row['ds_sistemas'];
		$this->ds_impactoNaoImplem->DbValue = $row['ds_impactoNaoImplem'];
		$this->nu_alternativaAlinhamento->DbValue = $row['nu_alternativaAlinhamento'];
		$this->nu_alternativaAbrangencia->DbValue = $row['nu_alternativaAbrangencia'];
		$this->nu_alternativaUrgencia->DbValue = $row['nu_alternativaUrgencia'];
		$this->dt_prazo->DbValue = $row['dt_prazo'];
		$this->nu_alternativaTmpEstimado->DbValue = $row['nu_alternativaTmpEstimado'];
		$this->nu_alternativaTmpFila->DbValue = $row['nu_alternativaTmpFila'];
		$this->ic_implicacaoLegal->DbValue = $row['ic_implicacaoLegal'];
		$this->ic_risco->DbValue = $row['ic_risco'];
		$this->ic_stProspecto->DbValue = $row['ic_stProspecto'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_prospecto")) <> "")
			$this->nu_prospecto->CurrentValue = $this->getKey("nu_prospecto"); // nu_prospecto
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
		// nu_prospecto
		// no_prospecto
		// nu_area
		// no_solicitante
		// no_patrocinador
		// ar_entidade
		// ar_nivel
		// nu_categoriaProspecto
		// nu_alternativaImpacto
		// ds_sistemas
		// ds_impactoNaoImplem
		// nu_alternativaAlinhamento
		// nu_alternativaAbrangencia
		// nu_alternativaUrgencia
		// dt_prazo
		// nu_alternativaTmpEstimado
		// nu_alternativaTmpFila
		// ic_implicacaoLegal
		// ic_risco
		// ic_stProspecto
		// ds_observacoes
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_prospecto
			$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
			$this->nu_prospecto->ViewCustomAttributes = "";

			// no_prospecto
			$this->no_prospecto->ViewValue = $this->no_prospecto->CurrentValue;
			$this->no_prospecto->ViewCustomAttributes = "";

			// nu_area
			if (strval($this->nu_area->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
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

			// no_solicitante
			$this->no_solicitante->ViewValue = $this->no_solicitante->CurrentValue;
			$this->no_solicitante->ViewCustomAttributes = "";

			// no_patrocinador
			$this->no_patrocinador->ViewValue = $this->no_patrocinador->CurrentValue;
			$this->no_patrocinador->ViewCustomAttributes = "";

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
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
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

			// nu_categoriaProspecto
			if (strval($this->nu_categoriaProspecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_categoria]" . ew_SearchString("=", $this->nu_categoriaProspecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_categoria], [no_categoria] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catprospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
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

			// nu_alternativaImpacto
			if (strval($this->nu_alternativaImpacto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaImpacto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 10";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaImpacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaImpacto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaImpacto->ViewValue = $this->nu_alternativaImpacto->CurrentValue;
				}
			} else {
				$this->nu_alternativaImpacto->ViewValue = NULL;
			}
			$this->nu_alternativaImpacto->ViewCustomAttributes = "";

			// nu_alternativaAlinhamento
			if (strval($this->nu_alternativaAlinhamento->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaAlinhamento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = '11'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaAlinhamento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaAlinhamento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaAlinhamento->ViewValue = $this->nu_alternativaAlinhamento->CurrentValue;
				}
			} else {
				$this->nu_alternativaAlinhamento->ViewValue = NULL;
			}
			$this->nu_alternativaAlinhamento->ViewCustomAttributes = "";

			// nu_alternativaAbrangencia
			if (strval($this->nu_alternativaAbrangencia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaAbrangencia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 12";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaAbrangencia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaAbrangencia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaAbrangencia->ViewValue = $this->nu_alternativaAbrangencia->CurrentValue;
				}
			} else {
				$this->nu_alternativaAbrangencia->ViewValue = NULL;
			}
			$this->nu_alternativaAbrangencia->ViewCustomAttributes = "";

			// nu_alternativaUrgencia
			if (strval($this->nu_alternativaUrgencia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaUrgencia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 13";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaUrgencia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaUrgencia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaUrgencia->ViewValue = $this->nu_alternativaUrgencia->CurrentValue;
				}
			} else {
				$this->nu_alternativaUrgencia->ViewValue = NULL;
			}
			$this->nu_alternativaUrgencia->ViewCustomAttributes = "";

			// dt_prazo
			$this->dt_prazo->ViewValue = $this->dt_prazo->CurrentValue;
			$this->dt_prazo->ViewValue = ew_FormatDateTime($this->dt_prazo->ViewValue, 7);
			$this->dt_prazo->ViewCustomAttributes = "";

			// nu_alternativaTmpEstimado
			if (strval($this->nu_alternativaTmpEstimado->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaTmpEstimado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 14";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaTmpEstimado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaTmpEstimado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaTmpEstimado->ViewValue = $this->nu_alternativaTmpEstimado->CurrentValue;
				}
			} else {
				$this->nu_alternativaTmpEstimado->ViewValue = NULL;
			}
			$this->nu_alternativaTmpEstimado->ViewCustomAttributes = "";

			// nu_alternativaTmpFila
			if (strval($this->nu_alternativaTmpFila->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaTmpFila->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 15";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaTmpFila, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaTmpFila->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaTmpFila->ViewValue = $this->nu_alternativaTmpFila->CurrentValue;
				}
			} else {
				$this->nu_alternativaTmpFila->ViewValue = NULL;
			}
			$this->nu_alternativaTmpFila->ViewCustomAttributes = "";

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

			// nu_prospecto
			$this->nu_prospecto->LinkCustomAttributes = "";
			$this->nu_prospecto->HrefValue = "";
			$this->nu_prospecto->TooltipValue = "";

			// no_prospecto
			$this->no_prospecto->LinkCustomAttributes = "";
			$this->no_prospecto->HrefValue = "";
			$this->no_prospecto->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// nu_categoriaProspecto
			$this->nu_categoriaProspecto->LinkCustomAttributes = "";
			$this->nu_categoriaProspecto->HrefValue = "";
			$this->nu_categoriaProspecto->TooltipValue = "";

			// ic_stProspecto
			$this->ic_stProspecto->LinkCustomAttributes = "";
			$this->ic_stProspecto->HrefValue = "";
			$this->ic_stProspecto->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_prospecto
			$this->nu_prospecto->EditCustomAttributes = "";
			$this->nu_prospecto->EditValue = ew_HtmlEncode($this->nu_prospecto->AdvancedSearch->SearchValue);
			$this->nu_prospecto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_prospecto->FldCaption()));

			// no_prospecto
			$this->no_prospecto->EditCustomAttributes = "";
			$this->no_prospecto->EditValue = ew_HtmlEncode($this->no_prospecto->AdvancedSearch->SearchValue);
			$this->no_prospecto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_prospecto->FldCaption()));

			// nu_area
			$this->nu_area->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
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

			// nu_categoriaProspecto
			$this->nu_categoriaProspecto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_categoria], [no_categoria] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[catprospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
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

			// ic_stProspecto
			$this->ic_stProspecto->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(1), $this->ic_stProspecto->FldTagCaption(1) <> "" ? $this->ic_stProspecto->FldTagCaption(1) : $this->ic_stProspecto->FldTagValue(1));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(2), $this->ic_stProspecto->FldTagCaption(2) <> "" ? $this->ic_stProspecto->FldTagCaption(2) : $this->ic_stProspecto->FldTagValue(2));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(3), $this->ic_stProspecto->FldTagCaption(3) <> "" ? $this->ic_stProspecto->FldTagCaption(3) : $this->ic_stProspecto->FldTagValue(3));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(4), $this->ic_stProspecto->FldTagCaption(4) <> "" ? $this->ic_stProspecto->FldTagCaption(4) : $this->ic_stProspecto->FldTagValue(4));
			$arwrk[] = array($this->ic_stProspecto->FldTagValue(5), $this->ic_stProspecto->FldTagCaption(5) <> "" ? $this->ic_stProspecto->FldTagCaption(5) : $this->ic_stProspecto->FldTagValue(5));
			$this->ic_stProspecto->EditValue = $arwrk;

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
		$this->nu_prospecto->AdvancedSearch->Load();
		$this->no_prospecto->AdvancedSearch->Load();
		$this->nu_area->AdvancedSearch->Load();
		$this->no_solicitante->AdvancedSearch->Load();
		$this->no_patrocinador->AdvancedSearch->Load();
		$this->ar_entidade->AdvancedSearch->Load();
		$this->ar_nivel->AdvancedSearch->Load();
		$this->nu_categoriaProspecto->AdvancedSearch->Load();
		$this->nu_alternativaImpacto->AdvancedSearch->Load();
		$this->ds_sistemas->AdvancedSearch->Load();
		$this->ds_impactoNaoImplem->AdvancedSearch->Load();
		$this->nu_alternativaAlinhamento->AdvancedSearch->Load();
		$this->nu_alternativaAbrangencia->AdvancedSearch->Load();
		$this->nu_alternativaUrgencia->AdvancedSearch->Load();
		$this->dt_prazo->AdvancedSearch->Load();
		$this->nu_alternativaTmpEstimado->AdvancedSearch->Load();
		$this->nu_alternativaTmpFila->AdvancedSearch->Load();
		$this->ic_implicacaoLegal->AdvancedSearch->Load();
		$this->ic_risco->AdvancedSearch->Load();
		$this->ic_stProspecto->AdvancedSearch->Load();
		$this->ds_observacoes->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_prospecto\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_prospecto',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fprospectolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "rprospresumo") {
			global $rprospresumo;
			$rsmaster = $rprospresumo->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$rprospresumo->ExportDocument($ExportDoc, $rsmaster, 1, 1);
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
		$this->AddSearchQueryString($sQry, $this->nu_prospecto); // nu_prospecto
		$this->AddSearchQueryString($sQry, $this->no_prospecto); // no_prospecto
		$this->AddSearchQueryString($sQry, $this->nu_area); // nu_area
		$this->AddSearchQueryString($sQry, $this->no_solicitante); // no_solicitante
		$this->AddSearchQueryString($sQry, $this->no_patrocinador); // no_patrocinador
		$this->AddSearchQueryString($sQry, $this->ar_entidade); // ar_entidade
		$this->AddSearchQueryString($sQry, $this->ar_nivel); // ar_nivel
		$this->AddSearchQueryString($sQry, $this->nu_categoriaProspecto); // nu_categoriaProspecto
		$this->AddSearchQueryString($sQry, $this->nu_alternativaImpacto); // nu_alternativaImpacto
		$this->AddSearchQueryString($sQry, $this->ds_sistemas); // ds_sistemas
		$this->AddSearchQueryString($sQry, $this->ds_impactoNaoImplem); // ds_impactoNaoImplem
		$this->AddSearchQueryString($sQry, $this->nu_alternativaAlinhamento); // nu_alternativaAlinhamento
		$this->AddSearchQueryString($sQry, $this->nu_alternativaAbrangencia); // nu_alternativaAbrangencia
		$this->AddSearchQueryString($sQry, $this->nu_alternativaUrgencia); // nu_alternativaUrgencia
		$this->AddSearchQueryString($sQry, $this->dt_prazo); // dt_prazo
		$this->AddSearchQueryString($sQry, $this->nu_alternativaTmpEstimado); // nu_alternativaTmpEstimado
		$this->AddSearchQueryString($sQry, $this->nu_alternativaTmpFila); // nu_alternativaTmpFila
		$this->AddSearchQueryString($sQry, $this->ic_implicacaoLegal); // ic_implicacaoLegal
		$this->AddSearchQueryString($sQry, $this->ic_risco); // ic_risco
		$this->AddSearchQueryString($sQry, $this->ic_stProspecto); // ic_stProspecto
		$this->AddSearchQueryString($sQry, $this->ds_observacoes); // ds_observacoes
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
			if ($sMasterTblVar == "rprospresumo") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_prospecto"] <> "") {
					$GLOBALS["rprospresumo"]->nu_prospecto->setQueryStringValue($_GET["nu_prospecto"]);
					$this->nu_prospecto->setQueryStringValue($GLOBALS["rprospresumo"]->nu_prospecto->QueryStringValue);
					$this->nu_prospecto->setSessionValue($this->nu_prospecto->QueryStringValue);
					if (!is_numeric($GLOBALS["rprospresumo"]->nu_prospecto->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "rprospresumo") {
				if ($this->nu_prospecto->QueryStringValue == "") $this->nu_prospecto->setSessionValue("");
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
if (!isset($prospecto_list)) $prospecto_list = new cprospecto_list();

// Page init
$prospecto_list->Page_Init();

// Page main
$prospecto_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$prospecto_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($prospecto->Export == "") { ?>
<script type="text/javascript">

// Page object
var prospecto_list = new ew_Page("prospecto_list");
prospecto_list.PageID = "list"; // Page ID
var EW_PAGE_ID = prospecto_list.PageID; // For backward compatibility

// Form object
var fprospectolist = new ew_Form("fprospectolist");
fprospectolist.FormKeyCountName = '<?php echo $prospecto_list->FormKeyCountName ?>';

// Form_CustomValidate event
fprospectolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprospectolist.ValidateRequired = true;
<?php } else { ?>
fprospectolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprospectolist.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectolist.Lists["x_nu_categoriaProspecto"] = {"LinkField":"x_nu_categoria","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_categoria","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fprospectolistsrch = new ew_Form("fprospectolistsrch");

// Validate function for search
fprospectolistsrch.Validate = function(fobj) {
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
fprospectolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprospectolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fprospectolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fprospectolistsrch.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectolistsrch.Lists["x_nu_categoriaProspecto"] = {"LinkField":"x_nu_categoria","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_categoria","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fprospectolistsrch) fprospectolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($prospecto->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($prospecto->getCurrentMasterTable() == "" && $prospecto_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $prospecto_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($prospecto->Export == "") || (EW_EXPORT_MASTER_RECORD && $prospecto->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "rprospresumolist.php";
if ($prospecto_list->DbMasterFilter <> "" && $prospecto->getCurrentMasterTable() == "rprospresumo") {
	if ($prospecto_list->MasterRecordExists) {
		if ($prospecto->getCurrentMasterTable() == $prospecto->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($prospecto_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $prospecto_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "rprospresumomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$prospecto_list->TotalRecs = $prospecto->SelectRecordCount();
	} else {
		if ($prospecto_list->Recordset = $prospecto_list->LoadRecordset())
			$prospecto_list->TotalRecs = $prospecto_list->Recordset->RecordCount();
	}
	$prospecto_list->StartRec = 1;
	if ($prospecto_list->DisplayRecs <= 0 || ($prospecto->Export <> "" && $prospecto->ExportAll)) // Display all records
		$prospecto_list->DisplayRecs = $prospecto_list->TotalRecs;
	if (!($prospecto->Export <> "" && $prospecto->ExportAll))
		$prospecto_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$prospecto_list->Recordset = $prospecto_list->LoadRecordset($prospecto_list->StartRec-1, $prospecto_list->DisplayRecs);
$prospecto_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($prospecto->Export == "" && $prospecto->CurrentAction == "") { ?>
<form name="fprospectolistsrch" id="fprospectolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fprospectolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fprospectolistsrch_SearchGroup" href="#fprospectolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fprospectolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fprospectolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="prospecto">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$prospecto_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$prospecto->RowType = EW_ROWTYPE_SEARCH;

// Render row
$prospecto->ResetAttrs();
$prospecto_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($prospecto->no_prospecto->Visible) { // no_prospecto ?>
	<span id="xsc_no_prospecto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $prospecto->no_prospecto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_prospecto" id="z_no_prospecto" value="LIKE"></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_no_prospecto" name="x_no_prospecto" id="x_no_prospecto" size="75" maxlength="120" placeholder="<?php echo $prospecto->no_prospecto->PlaceHolder ?>" value="<?php echo $prospecto->no_prospecto->EditValue ?>"<?php echo $prospecto->no_prospecto->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
<?php if ($prospecto->nu_area->Visible) { // nu_area ?>
	<span id="xsc_nu_area" class="ewCell">
		<span class="ewSearchCaption"><?php echo $prospecto->nu_area->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_area" id="z_nu_area" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_area" id="x_nu_area" name="x_nu_area"<?php echo $prospecto->nu_area->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_area->EditValue)) {
	$arwrk = $prospecto->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_area->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectolistsrch.Lists["x_nu_area"].Options = <?php echo (is_array($prospecto->nu_area->EditValue)) ? ew_ArrayToJson($prospecto->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($prospecto->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
	<span id="xsc_nu_categoriaProspecto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $prospecto->nu_categoriaProspecto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_categoriaProspecto" id="z_nu_categoriaProspecto" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_categoriaProspecto" id="x_nu_categoriaProspecto" name="x_nu_categoriaProspecto"<?php echo $prospecto->nu_categoriaProspecto->EditAttributes() ?>>
<?php
if (is_array($prospecto->nu_categoriaProspecto->EditValue)) {
	$arwrk = $prospecto->nu_categoriaProspecto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->nu_categoriaProspecto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fprospectolistsrch.Lists["x_nu_categoriaProspecto"].Options = <?php echo (is_array($prospecto->nu_categoriaProspecto->EditValue)) ? ew_ArrayToJson($prospecto->nu_categoriaProspecto->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
<?php if ($prospecto->ic_stProspecto->Visible) { // ic_stProspecto ?>
	<span id="xsc_ic_stProspecto" class="ewCell">
		<span class="ewSearchCaption"><?php echo $prospecto->ic_stProspecto->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_stProspecto" id="z_ic_stProspecto" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ic_stProspecto" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_stProspecto" id="x_ic_stProspecto" value="{value}"<?php echo $prospecto->ic_stProspecto->EditAttributes() ?>></div>
<div id="dsl_x_ic_stProspecto" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_stProspecto->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_stProspecto->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stProspecto" name="x_ic_stProspecto" id="x_ic_stProspecto_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_stProspecto->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
<?php if ($prospecto->ic_ativo->Visible) { // ic_ativo ?>
	<span id="xsc_ic_ativo" class="ewCell">
		<span class="ewSearchCaption"><?php echo $prospecto->ic_ativo->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_ativo" id="z_ic_ativo" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ic_ativo" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_ativo" id="x_ic_ativo" value="{value}"<?php echo $prospecto->ic_ativo->EditAttributes() ?>></div>
<div id="dsl_x_ic_ativo" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $prospecto->ic_ativo->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($prospecto->ic_ativo->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_ativo" name="x_ic_ativo" id="x_ic_ativo_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $prospecto->ic_ativo->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
	<a class="btn ewShowAll" href="<?php echo $prospecto_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $prospecto_list->ShowPageHeader(); ?>
<?php
$prospecto_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fprospectolist" id="fprospectolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="prospecto">
<div id="gmp_prospecto" class="ewGridMiddlePanel">
<?php if ($prospecto_list->TotalRecs > 0) { ?>
<table id="tbl_prospectolist" class="ewTable ewTableSeparate">
<?php echo $prospecto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$prospecto_list->RenderListOptions();

// Render list options (header, left)
$prospecto_list->ListOptions->Render("header", "left");
?>
<?php if ($prospecto->nu_prospecto->Visible) { // nu_prospecto ?>
	<?php if ($prospecto->SortUrl($prospecto->nu_prospecto) == "") { ?>
		<td><div id="elh_prospecto_nu_prospecto" class="prospecto_nu_prospecto"><div class="ewTableHeaderCaption"><?php echo $prospecto->nu_prospecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $prospecto->SortUrl($prospecto->nu_prospecto) ?>',2);"><div id="elh_prospecto_nu_prospecto" class="prospecto_nu_prospecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->nu_prospecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->nu_prospecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->nu_prospecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->no_prospecto->Visible) { // no_prospecto ?>
	<?php if ($prospecto->SortUrl($prospecto->no_prospecto) == "") { ?>
		<td><div id="elh_prospecto_no_prospecto" class="prospecto_no_prospecto"><div class="ewTableHeaderCaption"><?php echo $prospecto->no_prospecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $prospecto->SortUrl($prospecto->no_prospecto) ?>',2);"><div id="elh_prospecto_no_prospecto" class="prospecto_no_prospecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->no_prospecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->no_prospecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->no_prospecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->nu_area->Visible) { // nu_area ?>
	<?php if ($prospecto->SortUrl($prospecto->nu_area) == "") { ?>
		<td><div id="elh_prospecto_nu_area" class="prospecto_nu_area"><div class="ewTableHeaderCaption"><?php echo $prospecto->nu_area->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $prospecto->SortUrl($prospecto->nu_area) ?>',2);"><div id="elh_prospecto_nu_area" class="prospecto_nu_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->nu_area->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->nu_area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->nu_area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
	<?php if ($prospecto->SortUrl($prospecto->nu_categoriaProspecto) == "") { ?>
		<td><div id="elh_prospecto_nu_categoriaProspecto" class="prospecto_nu_categoriaProspecto"><div class="ewTableHeaderCaption"><?php echo $prospecto->nu_categoriaProspecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $prospecto->SortUrl($prospecto->nu_categoriaProspecto) ?>',2);"><div id="elh_prospecto_nu_categoriaProspecto" class="prospecto_nu_categoriaProspecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->nu_categoriaProspecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->nu_categoriaProspecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->nu_categoriaProspecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->ic_stProspecto->Visible) { // ic_stProspecto ?>
	<?php if ($prospecto->SortUrl($prospecto->ic_stProspecto) == "") { ?>
		<td><div id="elh_prospecto_ic_stProspecto" class="prospecto_ic_stProspecto"><div class="ewTableHeaderCaption"><?php echo $prospecto->ic_stProspecto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $prospecto->SortUrl($prospecto->ic_stProspecto) ?>',2);"><div id="elh_prospecto_ic_stProspecto" class="prospecto_ic_stProspecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->ic_stProspecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->ic_stProspecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->ic_stProspecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($prospecto->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($prospecto->SortUrl($prospecto->ic_ativo) == "") { ?>
		<td><div id="elh_prospecto_ic_ativo" class="prospecto_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $prospecto->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $prospecto->SortUrl($prospecto->ic_ativo) ?>',2);"><div id="elh_prospecto_ic_ativo" class="prospecto_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $prospecto->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($prospecto->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($prospecto->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$prospecto_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($prospecto->ExportAll && $prospecto->Export <> "") {
	$prospecto_list->StopRec = $prospecto_list->TotalRecs;
} else {

	// Set the last record to display
	if ($prospecto_list->TotalRecs > $prospecto_list->StartRec + $prospecto_list->DisplayRecs - 1)
		$prospecto_list->StopRec = $prospecto_list->StartRec + $prospecto_list->DisplayRecs - 1;
	else
		$prospecto_list->StopRec = $prospecto_list->TotalRecs;
}
$prospecto_list->RecCnt = $prospecto_list->StartRec - 1;
if ($prospecto_list->Recordset && !$prospecto_list->Recordset->EOF) {
	$prospecto_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $prospecto_list->StartRec > 1)
		$prospecto_list->Recordset->Move($prospecto_list->StartRec - 1);
} elseif (!$prospecto->AllowAddDeleteRow && $prospecto_list->StopRec == 0) {
	$prospecto_list->StopRec = $prospecto->GridAddRowCount;
}

// Initialize aggregate
$prospecto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$prospecto->ResetAttrs();
$prospecto_list->RenderRow();
while ($prospecto_list->RecCnt < $prospecto_list->StopRec) {
	$prospecto_list->RecCnt++;
	if (intval($prospecto_list->RecCnt) >= intval($prospecto_list->StartRec)) {
		$prospecto_list->RowCnt++;

		// Set up key count
		$prospecto_list->KeyCount = $prospecto_list->RowIndex;

		// Init row class and style
		$prospecto->ResetAttrs();
		$prospecto->CssClass = "";
		if ($prospecto->CurrentAction == "gridadd") {
		} else {
			$prospecto_list->LoadRowValues($prospecto_list->Recordset); // Load row values
		}
		$prospecto->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$prospecto->RowAttrs = array_merge($prospecto->RowAttrs, array('data-rowindex'=>$prospecto_list->RowCnt, 'id'=>'r' . $prospecto_list->RowCnt . '_prospecto', 'data-rowtype'=>$prospecto->RowType));

		// Render row
		$prospecto_list->RenderRow();

		// Render list options
		$prospecto_list->RenderListOptions();
?>
	<tr<?php echo $prospecto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$prospecto_list->ListOptions->Render("body", "left", $prospecto_list->RowCnt);
?>
	<?php if ($prospecto->nu_prospecto->Visible) { // nu_prospecto ?>
		<td<?php echo $prospecto->nu_prospecto->CellAttributes() ?>>
<span<?php echo $prospecto->nu_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_prospecto->ListViewValue() ?></span>
<a id="<?php echo $prospecto_list->PageObjName . "_row_" . $prospecto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->no_prospecto->Visible) { // no_prospecto ?>
		<td<?php echo $prospecto->no_prospecto->CellAttributes() ?>>
<span<?php echo $prospecto->no_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->no_prospecto->ListViewValue() ?></span>
<a id="<?php echo $prospecto_list->PageObjName . "_row_" . $prospecto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->nu_area->Visible) { // nu_area ?>
		<td<?php echo $prospecto->nu_area->CellAttributes() ?>>
<span<?php echo $prospecto->nu_area->ViewAttributes() ?>>
<?php echo $prospecto->nu_area->ListViewValue() ?></span>
<a id="<?php echo $prospecto_list->PageObjName . "_row_" . $prospecto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
		<td<?php echo $prospecto->nu_categoriaProspecto->CellAttributes() ?>>
<span<?php echo $prospecto->nu_categoriaProspecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_categoriaProspecto->ListViewValue() ?></span>
<a id="<?php echo $prospecto_list->PageObjName . "_row_" . $prospecto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->ic_stProspecto->Visible) { // ic_stProspecto ?>
		<td<?php echo $prospecto->ic_stProspecto->CellAttributes() ?>>
<span<?php echo $prospecto->ic_stProspecto->ViewAttributes() ?>>
<?php echo $prospecto->ic_stProspecto->ListViewValue() ?></span>
<a id="<?php echo $prospecto_list->PageObjName . "_row_" . $prospecto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($prospecto->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $prospecto->ic_ativo->CellAttributes() ?>>
<span<?php echo $prospecto->ic_ativo->ViewAttributes() ?>>
<?php echo $prospecto->ic_ativo->ListViewValue() ?></span>
<a id="<?php echo $prospecto_list->PageObjName . "_row_" . $prospecto_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$prospecto_list->ListOptions->Render("body", "right", $prospecto_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($prospecto->CurrentAction <> "gridadd")
		$prospecto_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($prospecto->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($prospecto_list->Recordset)
	$prospecto_list->Recordset->Close();
?>
<?php if ($prospecto->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($prospecto->CurrentAction <> "gridadd" && $prospecto->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($prospecto_list->Pager)) $prospecto_list->Pager = new cNumericPager($prospecto_list->StartRec, $prospecto_list->DisplayRecs, $prospecto_list->TotalRecs, $prospecto_list->RecRange) ?>
<?php if ($prospecto_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($prospecto_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $prospecto_list->PageUrl() ?>start=<?php echo $prospecto_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($prospecto_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $prospecto_list->PageUrl() ?>start=<?php echo $prospecto_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($prospecto_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $prospecto_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($prospecto_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $prospecto_list->PageUrl() ?>start=<?php echo $prospecto_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($prospecto_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $prospecto_list->PageUrl() ?>start=<?php echo $prospecto_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($prospecto_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $prospecto_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $prospecto_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $prospecto_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($prospecto_list->SearchWhere == "0=101") { ?>
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
	foreach ($prospecto_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($prospecto->Export == "") { ?>
<script type="text/javascript">
fprospectolistsrch.Init();
fprospectolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$prospecto_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($prospecto->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$prospecto_list->Page_Terminate();
?>
