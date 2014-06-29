<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "riscoprojetoinfo.php" ?>
<?php include_once "projetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$riscoprojeto_list = NULL; // Initialize page object first

class criscoprojeto_list extends criscoprojeto {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'riscoprojeto';

	// Page object name
	var $PageObjName = 'riscoprojeto_list';

	// Grid form hidden field names
	var $FormName = 'friscoprojetolist';
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

		// Table object (riscoprojeto)
		if (!isset($GLOBALS["riscoprojeto"])) {
			$GLOBALS["riscoprojeto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["riscoprojeto"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "riscoprojetoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "riscoprojetodelete.php";
		$this->MultiUpdateUrl = "riscoprojetoupdate.php";

		// Table object (projeto)
		if (!isset($GLOBALS['projeto'])) $GLOBALS['projeto'] = new cprojeto();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'riscoprojeto', TRUE);

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
		$this->nu_riscoProjeto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->nu_usuarioResp->Visible = !$this->IsAddOrEdit();

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "projeto") {
			global $projeto;
			$rsmaster = $projeto->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("projetolist.php"); // Return to master page
			} else {
				$projeto->LoadListRowValues($rsmaster);
				$projeto->RowType = EW_ROWTYPE_MASTER; // Master row
				$projeto->RenderListRow();
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
			$this->nu_riscoProjeto->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_riscoProjeto->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere() {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->nu_riscoProjeto, FALSE); // nu_riscoProjeto
		$this->BuildSearchSql($sWhere, $this->nu_projeto, FALSE); // nu_projeto
		$this->BuildSearchSql($sWhere, $this->nu_catRisco, FALSE); // nu_catRisco
		$this->BuildSearchSql($sWhere, $this->ic_tpRisco, FALSE); // ic_tpRisco
		$this->BuildSearchSql($sWhere, $this->ds_risco, FALSE); // ds_risco
		$this->BuildSearchSql($sWhere, $this->ds_consequencia, FALSE); // ds_consequencia
		$this->BuildSearchSql($sWhere, $this->nu_probabilidade, FALSE); // nu_probabilidade
		$this->BuildSearchSql($sWhere, $this->nu_impacto, FALSE); // nu_impacto
		$this->BuildSearchSql($sWhere, $this->nu_severidade, FALSE); // nu_severidade
		$this->BuildSearchSql($sWhere, $this->nu_acao, FALSE); // nu_acao
		$this->BuildSearchSql($sWhere, $this->ds_gatilho, FALSE); // ds_gatilho
		$this->BuildSearchSql($sWhere, $this->ds_respRisco, FALSE); // ds_respRisco
		$this->BuildSearchSql($sWhere, $this->nu_usuarioResp, FALSE); // nu_usuarioResp
		$this->BuildSearchSql($sWhere, $this->ic_stRisco, FALSE); // ic_stRisco

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_riscoProjeto->AdvancedSearch->Save(); // nu_riscoProjeto
			$this->nu_projeto->AdvancedSearch->Save(); // nu_projeto
			$this->nu_catRisco->AdvancedSearch->Save(); // nu_catRisco
			$this->ic_tpRisco->AdvancedSearch->Save(); // ic_tpRisco
			$this->ds_risco->AdvancedSearch->Save(); // ds_risco
			$this->ds_consequencia->AdvancedSearch->Save(); // ds_consequencia
			$this->nu_probabilidade->AdvancedSearch->Save(); // nu_probabilidade
			$this->nu_impacto->AdvancedSearch->Save(); // nu_impacto
			$this->nu_severidade->AdvancedSearch->Save(); // nu_severidade
			$this->nu_acao->AdvancedSearch->Save(); // nu_acao
			$this->ds_gatilho->AdvancedSearch->Save(); // ds_gatilho
			$this->ds_respRisco->AdvancedSearch->Save(); // ds_respRisco
			$this->nu_usuarioResp->AdvancedSearch->Save(); // nu_usuarioResp
			$this->ic_stRisco->AdvancedSearch->Save(); // ic_stRisco
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
		if ($this->nu_riscoProjeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_projeto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_catRisco->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_tpRisco->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_risco->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_consequencia->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_probabilidade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_impacto->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_severidade->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_acao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_gatilho->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_respRisco->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_usuarioResp->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ic_stRisco->AdvancedSearch->IssetSession())
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
		$this->nu_riscoProjeto->AdvancedSearch->UnsetSession();
		$this->nu_projeto->AdvancedSearch->UnsetSession();
		$this->nu_catRisco->AdvancedSearch->UnsetSession();
		$this->ic_tpRisco->AdvancedSearch->UnsetSession();
		$this->ds_risco->AdvancedSearch->UnsetSession();
		$this->ds_consequencia->AdvancedSearch->UnsetSession();
		$this->nu_probabilidade->AdvancedSearch->UnsetSession();
		$this->nu_impacto->AdvancedSearch->UnsetSession();
		$this->nu_severidade->AdvancedSearch->UnsetSession();
		$this->nu_acao->AdvancedSearch->UnsetSession();
		$this->ds_gatilho->AdvancedSearch->UnsetSession();
		$this->ds_respRisco->AdvancedSearch->UnsetSession();
		$this->nu_usuarioResp->AdvancedSearch->UnsetSession();
		$this->ic_stRisco->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_riscoProjeto->AdvancedSearch->Load();
		$this->nu_projeto->AdvancedSearch->Load();
		$this->nu_catRisco->AdvancedSearch->Load();
		$this->ic_tpRisco->AdvancedSearch->Load();
		$this->ds_risco->AdvancedSearch->Load();
		$this->ds_consequencia->AdvancedSearch->Load();
		$this->nu_probabilidade->AdvancedSearch->Load();
		$this->nu_impacto->AdvancedSearch->Load();
		$this->nu_severidade->AdvancedSearch->Load();
		$this->nu_acao->AdvancedSearch->Load();
		$this->ds_gatilho->AdvancedSearch->Load();
		$this->ds_respRisco->AdvancedSearch->Load();
		$this->nu_usuarioResp->AdvancedSearch->Load();
		$this->ic_stRisco->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_riscoProjeto, $bCtrl); // nu_riscoProjeto
			$this->UpdateSort($this->nu_projeto, $bCtrl); // nu_projeto
			$this->UpdateSort($this->nu_catRisco, $bCtrl); // nu_catRisco
			$this->UpdateSort($this->ic_tpRisco, $bCtrl); // ic_tpRisco
			$this->UpdateSort($this->nu_probabilidade, $bCtrl); // nu_probabilidade
			$this->UpdateSort($this->nu_impacto, $bCtrl); // nu_impacto
			$this->UpdateSort($this->nu_severidade, $bCtrl); // nu_severidade
			$this->UpdateSort($this->nu_acao, $bCtrl); // nu_acao
			$this->UpdateSort($this->nu_usuarioResp, $bCtrl); // nu_usuarioResp
			$this->UpdateSort($this->ic_stRisco, $bCtrl); // ic_stRisco
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
				$this->nu_projeto->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->nu_riscoProjeto->setSort("");
				$this->nu_projeto->setSort("");
				$this->nu_catRisco->setSort("");
				$this->ic_tpRisco->setSort("");
				$this->nu_probabilidade->setSort("");
				$this->nu_impacto->setSort("");
				$this->nu_severidade->setSort("");
				$this->nu_acao->setSort("");
				$this->nu_usuarioResp->setSort("");
				$this->ic_stRisco->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.friscoprojetolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_riscoProjeto

		$this->nu_riscoProjeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_riscoProjeto"]);
		if ($this->nu_riscoProjeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_riscoProjeto->AdvancedSearch->SearchOperator = @$_GET["z_nu_riscoProjeto"];

		// nu_projeto
		$this->nu_projeto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_projeto"]);
		if ($this->nu_projeto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_projeto->AdvancedSearch->SearchOperator = @$_GET["z_nu_projeto"];

		// nu_catRisco
		$this->nu_catRisco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_catRisco"]);
		if ($this->nu_catRisco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_catRisco->AdvancedSearch->SearchOperator = @$_GET["z_nu_catRisco"];

		// ic_tpRisco
		$this->ic_tpRisco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_tpRisco"]);
		if ($this->ic_tpRisco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_tpRisco->AdvancedSearch->SearchOperator = @$_GET["z_ic_tpRisco"];

		// ds_risco
		$this->ds_risco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_risco"]);
		if ($this->ds_risco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_risco->AdvancedSearch->SearchOperator = @$_GET["z_ds_risco"];

		// ds_consequencia
		$this->ds_consequencia->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_consequencia"]);
		if ($this->ds_consequencia->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_consequencia->AdvancedSearch->SearchOperator = @$_GET["z_ds_consequencia"];

		// nu_probabilidade
		$this->nu_probabilidade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_probabilidade"]);
		if ($this->nu_probabilidade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_probabilidade->AdvancedSearch->SearchOperator = @$_GET["z_nu_probabilidade"];

		// nu_impacto
		$this->nu_impacto->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_impacto"]);
		if ($this->nu_impacto->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_impacto->AdvancedSearch->SearchOperator = @$_GET["z_nu_impacto"];

		// nu_severidade
		$this->nu_severidade->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_severidade"]);
		if ($this->nu_severidade->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_severidade->AdvancedSearch->SearchOperator = @$_GET["z_nu_severidade"];

		// nu_acao
		$this->nu_acao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_acao"]);
		if ($this->nu_acao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_acao->AdvancedSearch->SearchOperator = @$_GET["z_nu_acao"];

		// ds_gatilho
		$this->ds_gatilho->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_gatilho"]);
		if ($this->ds_gatilho->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_gatilho->AdvancedSearch->SearchOperator = @$_GET["z_ds_gatilho"];

		// ds_respRisco
		$this->ds_respRisco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_respRisco"]);
		if ($this->ds_respRisco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_respRisco->AdvancedSearch->SearchOperator = @$_GET["z_ds_respRisco"];

		// nu_usuarioResp
		$this->nu_usuarioResp->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_usuarioResp"]);
		if ($this->nu_usuarioResp->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_usuarioResp->AdvancedSearch->SearchOperator = @$_GET["z_nu_usuarioResp"];

		// ic_stRisco
		$this->ic_stRisco->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ic_stRisco"]);
		if ($this->ic_stRisco->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ic_stRisco->AdvancedSearch->SearchOperator = @$_GET["z_ic_stRisco"];
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
		$this->nu_riscoProjeto->setDbValue($rs->fields('nu_riscoProjeto'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_catRisco->setDbValue($rs->fields('nu_catRisco'));
		$this->ic_tpRisco->setDbValue($rs->fields('ic_tpRisco'));
		$this->ds_risco->setDbValue($rs->fields('ds_risco'));
		$this->ds_consequencia->setDbValue($rs->fields('ds_consequencia'));
		$this->nu_probabilidade->setDbValue($rs->fields('nu_probabilidade'));
		$this->nu_impacto->setDbValue($rs->fields('nu_impacto'));
		$this->nu_severidade->setDbValue($rs->fields('nu_severidade'));
		$this->nu_acao->setDbValue($rs->fields('nu_acao'));
		$this->ds_gatilho->setDbValue($rs->fields('ds_gatilho'));
		$this->ds_respRisco->setDbValue($rs->fields('ds_respRisco'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_stRisco->setDbValue($rs->fields('ic_stRisco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_riscoProjeto->DbValue = $row['nu_riscoProjeto'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_catRisco->DbValue = $row['nu_catRisco'];
		$this->ic_tpRisco->DbValue = $row['ic_tpRisco'];
		$this->ds_risco->DbValue = $row['ds_risco'];
		$this->ds_consequencia->DbValue = $row['ds_consequencia'];
		$this->nu_probabilidade->DbValue = $row['nu_probabilidade'];
		$this->nu_impacto->DbValue = $row['nu_impacto'];
		$this->nu_severidade->DbValue = $row['nu_severidade'];
		$this->nu_acao->DbValue = $row['nu_acao'];
		$this->ds_gatilho->DbValue = $row['ds_gatilho'];
		$this->ds_respRisco->DbValue = $row['ds_respRisco'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ic_stRisco->DbValue = $row['ic_stRisco'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_riscoProjeto")) <> "")
			$this->nu_riscoProjeto->CurrentValue = $this->getKey("nu_riscoProjeto"); // nu_riscoProjeto
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
		// nu_riscoProjeto
		// nu_projeto
		// nu_catRisco
		// ic_tpRisco
		// ds_risco
		// ds_consequencia
		// nu_probabilidade
		// nu_impacto
		// nu_severidade
		// nu_acao
		// ds_gatilho
		// ds_respRisco
		// nu_usuarioResp
		// ic_stRisco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_riscoProjeto
			$this->nu_riscoProjeto->ViewValue = $this->nu_riscoProjeto->CurrentValue;
			$this->nu_riscoProjeto->ViewCustomAttributes = "";

			// nu_projeto
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
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
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_catRisco
			if (strval($this->nu_catRisco->CurrentValue) <> "") {
				$sFilterWrk = "[nu_catRisco]" . ew_SearchString("=", $this->nu_catRisco->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catriscoproj]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_catRisco->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_catRisco->ViewValue = $this->nu_catRisco->CurrentValue;
				}
			} else {
				$this->nu_catRisco->ViewValue = NULL;
			}
			$this->nu_catRisco->ViewCustomAttributes = "";

			// ic_tpRisco
			if (strval($this->ic_tpRisco->CurrentValue) <> "") {
				switch ($this->ic_tpRisco->CurrentValue) {
					case $this->ic_tpRisco->FldTagValue(1):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->CurrentValue;
						break;
					case $this->ic_tpRisco->FldTagValue(2):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->CurrentValue;
						break;
					default:
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->CurrentValue;
				}
			} else {
				$this->ic_tpRisco->ViewValue = NULL;
			}
			$this->ic_tpRisco->ViewCustomAttributes = "";

			// nu_probabilidade
			if (strval($this->nu_probabilidade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_probOcoRisco]" . ew_SearchString("=", $this->nu_probabilidade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_probOcoRisco], [no_probOcoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[probocorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_probabilidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_probabilidade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_probabilidade->ViewValue = $this->nu_probabilidade->CurrentValue;
				}
			} else {
				$this->nu_probabilidade->ViewValue = NULL;
			}
			$this->nu_probabilidade->ViewCustomAttributes = "";

			// nu_impacto
			if (strval($this->nu_impacto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_impactoRisco]" . ew_SearchString("=", $this->nu_impacto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_impactoRisco], [no_impactoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[impactorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_impacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_impacto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_impacto->ViewValue = $this->nu_impacto->CurrentValue;
				}
			} else {
				$this->nu_impacto->ViewValue = NULL;
			}
			$this->nu_impacto->ViewCustomAttributes = "";

			// nu_severidade
			$this->nu_severidade->ViewValue = $this->nu_severidade->CurrentValue;
			$this->nu_severidade->ViewCustomAttributes = "";

			// nu_acao
			if (strval($this->nu_acao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_acaoRisco]" . ew_SearchString("=", $this->nu_acao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_acaoRisco], [no_acaoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[acaorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_acao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_acaoRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_acao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_acao->ViewValue = $this->nu_acao->CurrentValue;
				}
			} else {
				$this->nu_acao->ViewValue = NULL;
			}
			$this->nu_acao->ViewCustomAttributes = "";

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

			// ic_stRisco
			if (strval($this->ic_stRisco->CurrentValue) <> "") {
				switch ($this->ic_stRisco->CurrentValue) {
					case $this->ic_stRisco->FldTagValue(1):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->CurrentValue;
						break;
					case $this->ic_stRisco->FldTagValue(2):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->CurrentValue;
						break;
					default:
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->CurrentValue;
				}
			} else {
				$this->ic_stRisco->ViewValue = NULL;
			}
			$this->ic_stRisco->ViewCustomAttributes = "";

			// nu_riscoProjeto
			$this->nu_riscoProjeto->LinkCustomAttributes = "";
			$this->nu_riscoProjeto->HrefValue = "";
			$this->nu_riscoProjeto->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_catRisco
			$this->nu_catRisco->LinkCustomAttributes = "";
			$this->nu_catRisco->HrefValue = "";
			$this->nu_catRisco->TooltipValue = "";

			// ic_tpRisco
			$this->ic_tpRisco->LinkCustomAttributes = "";
			$this->ic_tpRisco->HrefValue = "";
			$this->ic_tpRisco->TooltipValue = "";

			// nu_probabilidade
			$this->nu_probabilidade->LinkCustomAttributes = "";
			$this->nu_probabilidade->HrefValue = "";
			$this->nu_probabilidade->TooltipValue = "";

			// nu_impacto
			$this->nu_impacto->LinkCustomAttributes = "";
			$this->nu_impacto->HrefValue = "";
			$this->nu_impacto->TooltipValue = "";

			// nu_severidade
			$this->nu_severidade->LinkCustomAttributes = "";
			$this->nu_severidade->HrefValue = "";
			$this->nu_severidade->TooltipValue = "";

			// nu_acao
			$this->nu_acao->LinkCustomAttributes = "";
			$this->nu_acao->HrefValue = "";
			$this->nu_acao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// ic_stRisco
			$this->ic_stRisco->LinkCustomAttributes = "";
			$this->ic_stRisco->HrefValue = "";
			$this->ic_stRisco->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_riscoProjeto
			$this->nu_riscoProjeto->EditCustomAttributes = "";
			$this->nu_riscoProjeto->EditValue = ew_HtmlEncode($this->nu_riscoProjeto->AdvancedSearch->SearchValue);
			$this->nu_riscoProjeto->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_riscoProjeto->FldCaption()));

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";

			// nu_catRisco
			$this->nu_catRisco->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[catriscoproj]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_catRisco->EditValue = $arwrk;

			// ic_tpRisco
			$this->ic_tpRisco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpRisco->FldTagValue(1), $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->FldTagValue(1));
			$arwrk[] = array($this->ic_tpRisco->FldTagValue(2), $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->FldTagValue(2));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tpRisco->EditValue = $arwrk;

			// nu_probabilidade
			$this->nu_probabilidade->EditCustomAttributes = "";

			// nu_impacto
			$this->nu_impacto->EditCustomAttributes = "";

			// nu_severidade
			$this->nu_severidade->EditCustomAttributes = "";
			$this->nu_severidade->EditValue = ew_HtmlEncode($this->nu_severidade->AdvancedSearch->SearchValue);
			$this->nu_severidade->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_severidade->FldCaption()));

			// nu_acao
			$this->nu_acao->EditCustomAttributes = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["riscoprojeto"]->UserIDAllow($GLOBALS["riscoprojeto"]->CurrentAction)) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioResp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioResp->EditValue = $arwrk;

			// ic_stRisco
			$this->ic_stRisco->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_stRisco->FldTagValue(1), $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->FldTagValue(1));
			$arwrk[] = array($this->ic_stRisco->FldTagValue(2), $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->FldTagValue(2));
			$this->ic_stRisco->EditValue = $arwrk;
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
		$this->nu_riscoProjeto->AdvancedSearch->Load();
		$this->nu_projeto->AdvancedSearch->Load();
		$this->nu_catRisco->AdvancedSearch->Load();
		$this->ic_tpRisco->AdvancedSearch->Load();
		$this->ds_risco->AdvancedSearch->Load();
		$this->ds_consequencia->AdvancedSearch->Load();
		$this->nu_probabilidade->AdvancedSearch->Load();
		$this->nu_impacto->AdvancedSearch->Load();
		$this->nu_severidade->AdvancedSearch->Load();
		$this->nu_acao->AdvancedSearch->Load();
		$this->ds_gatilho->AdvancedSearch->Load();
		$this->ds_respRisco->AdvancedSearch->Load();
		$this->nu_usuarioResp->AdvancedSearch->Load();
		$this->ic_stRisco->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_riscoprojeto\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_riscoprojeto',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.friscoprojetolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "projeto") {
			global $projeto;
			$rsmaster = $projeto->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$projeto->ExportDocument($ExportDoc, $rsmaster, 1, 1);
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
		$this->AddSearchQueryString($sQry, $this->nu_riscoProjeto); // nu_riscoProjeto
		$this->AddSearchQueryString($sQry, $this->nu_projeto); // nu_projeto
		$this->AddSearchQueryString($sQry, $this->nu_catRisco); // nu_catRisco
		$this->AddSearchQueryString($sQry, $this->ic_tpRisco); // ic_tpRisco
		$this->AddSearchQueryString($sQry, $this->ds_risco); // ds_risco
		$this->AddSearchQueryString($sQry, $this->ds_consequencia); // ds_consequencia
		$this->AddSearchQueryString($sQry, $this->nu_probabilidade); // nu_probabilidade
		$this->AddSearchQueryString($sQry, $this->nu_impacto); // nu_impacto
		$this->AddSearchQueryString($sQry, $this->nu_severidade); // nu_severidade
		$this->AddSearchQueryString($sQry, $this->nu_acao); // nu_acao
		$this->AddSearchQueryString($sQry, $this->ds_gatilho); // ds_gatilho
		$this->AddSearchQueryString($sQry, $this->ds_respRisco); // ds_respRisco
		$this->AddSearchQueryString($sQry, $this->nu_usuarioResp); // nu_usuarioResp
		$this->AddSearchQueryString($sQry, $this->ic_stRisco); // ic_stRisco

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
			if ($sMasterTblVar == "projeto") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_projeto"] <> "") {
					$GLOBALS["projeto"]->nu_projeto->setQueryStringValue($_GET["nu_projeto"]);
					$this->nu_projeto->setQueryStringValue($GLOBALS["projeto"]->nu_projeto->QueryStringValue);
					$this->nu_projeto->setSessionValue($this->nu_projeto->QueryStringValue);
					if (!is_numeric($GLOBALS["projeto"]->nu_projeto->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "projeto") {
				if ($this->nu_projeto->QueryStringValue == "") $this->nu_projeto->setSessionValue("");
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
if (!isset($riscoprojeto_list)) $riscoprojeto_list = new criscoprojeto_list();

// Page init
$riscoprojeto_list->Page_Init();

// Page main
$riscoprojeto_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$riscoprojeto_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($riscoprojeto->Export == "") { ?>
<script type="text/javascript">

// Page object
var riscoprojeto_list = new ew_Page("riscoprojeto_list");
riscoprojeto_list.PageID = "list"; // Page ID
var EW_PAGE_ID = riscoprojeto_list.PageID; // For backward compatibility

// Form object
var friscoprojetolist = new ew_Form("friscoprojetolist");
friscoprojetolist.FormKeyCountName = '<?php echo $riscoprojeto_list->FormKeyCountName ?>';

// Form_CustomValidate event
friscoprojetolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
friscoprojetolist.ValidateRequired = true;
<?php } else { ?>
friscoprojetolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
friscoprojetolist.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetolist.Lists["x_nu_catRisco"] = {"LinkField":"x_nu_catRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_catRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetolist.Lists["x_nu_probabilidade"] = {"LinkField":"x_nu_probOcoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_probOcoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetolist.Lists["x_nu_impacto"] = {"LinkField":"x_nu_impactoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_impactoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetolist.Lists["x_nu_acao"] = {"LinkField":"x_nu_acaoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_acaoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetolist.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var friscoprojetolistsrch = new ew_Form("friscoprojetolistsrch");

// Validate function for search
friscoprojetolistsrch.Validate = function(fobj) {
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
friscoprojetolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
friscoprojetolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
friscoprojetolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
friscoprojetolistsrch.Lists["x_nu_catRisco"] = {"LinkField":"x_nu_catRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_catRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetolistsrch.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (friscoprojetolistsrch) friscoprojetolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($riscoprojeto->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($riscoprojeto->getCurrentMasterTable() == "" && $riscoprojeto_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $riscoprojeto_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($riscoprojeto->Export == "") || (EW_EXPORT_MASTER_RECORD && $riscoprojeto->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "projetolist.php";
if ($riscoprojeto_list->DbMasterFilter <> "" && $riscoprojeto->getCurrentMasterTable() == "projeto") {
	if ($riscoprojeto_list->MasterRecordExists) {
		if ($riscoprojeto->getCurrentMasterTable() == $riscoprojeto->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($riscoprojeto_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $riscoprojeto_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "projetomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$riscoprojeto_list->TotalRecs = $riscoprojeto->SelectRecordCount();
	} else {
		if ($riscoprojeto_list->Recordset = $riscoprojeto_list->LoadRecordset())
			$riscoprojeto_list->TotalRecs = $riscoprojeto_list->Recordset->RecordCount();
	}
	$riscoprojeto_list->StartRec = 1;
	if ($riscoprojeto_list->DisplayRecs <= 0 || ($riscoprojeto->Export <> "" && $riscoprojeto->ExportAll)) // Display all records
		$riscoprojeto_list->DisplayRecs = $riscoprojeto_list->TotalRecs;
	if (!($riscoprojeto->Export <> "" && $riscoprojeto->ExportAll))
		$riscoprojeto_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$riscoprojeto_list->Recordset = $riscoprojeto_list->LoadRecordset($riscoprojeto_list->StartRec-1, $riscoprojeto_list->DisplayRecs);
$riscoprojeto_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($riscoprojeto->Export == "" && $riscoprojeto->CurrentAction == "") { ?>
<form name="friscoprojetolistsrch" id="friscoprojetolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="friscoprojetolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#friscoprojetolistsrch_SearchGroup" href="#friscoprojetolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="friscoprojetolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="friscoprojetolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="riscoprojeto">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$riscoprojeto_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$riscoprojeto->RowType = EW_ROWTYPE_SEARCH;

// Render row
$riscoprojeto->ResetAttrs();
$riscoprojeto_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($riscoprojeto->nu_catRisco->Visible) { // nu_catRisco ?>
	<span id="xsc_nu_catRisco" class="ewCell">
		<span class="ewSearchCaption"><?php echo $riscoprojeto->nu_catRisco->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_catRisco" id="z_nu_catRisco" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_catRisco" id="x_nu_catRisco" name="x_nu_catRisco"<?php echo $riscoprojeto->nu_catRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_catRisco->EditValue)) {
	$arwrk = $riscoprojeto->nu_catRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_catRisco->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
friscoprojetolistsrch.Lists["x_nu_catRisco"].Options = <?php echo (is_array($riscoprojeto->nu_catRisco->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_catRisco->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($riscoprojeto->ic_tpRisco->Visible) { // ic_tpRisco ?>
	<span id="xsc_ic_tpRisco" class="ewCell">
		<span class="ewSearchCaption"><?php echo $riscoprojeto->ic_tpRisco->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_tpRisco" id="z_ic_tpRisco" value="LIKE"></span>
		<span class="control-group ewSearchField">
<select data-field="x_ic_tpRisco" id="x_ic_tpRisco" name="x_ic_tpRisco"<?php echo $riscoprojeto->ic_tpRisco->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->ic_tpRisco->EditValue)) {
	$arwrk = $riscoprojeto->ic_tpRisco->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_tpRisco->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if ($riscoprojeto->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<span id="xsc_nu_usuarioResp" class="ewCell">
		<span class="ewSearchCaption"><?php echo $riscoprojeto->nu_usuarioResp->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_usuarioResp" id="z_nu_usuarioResp" value="="></span>
		<span class="control-group ewSearchField">
<select data-field="x_nu_usuarioResp" id="x_nu_usuarioResp" name="x_nu_usuarioResp"<?php echo $riscoprojeto->nu_usuarioResp->EditAttributes() ?>>
<?php
if (is_array($riscoprojeto->nu_usuarioResp->EditValue)) {
	$arwrk = $riscoprojeto->nu_usuarioResp->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->nu_usuarioResp->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
friscoprojetolistsrch.Lists["x_nu_usuarioResp"].Options = <?php echo (is_array($riscoprojeto->nu_usuarioResp->EditValue)) ? ew_ArrayToJson($riscoprojeto->nu_usuarioResp->EditValue, 1) : "[]" ?>;
</script>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($riscoprojeto->ic_stRisco->Visible) { // ic_stRisco ?>
	<span id="xsc_ic_stRisco" class="ewCell">
		<span class="ewSearchCaption"><?php echo $riscoprojeto->ic_stRisco->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_ic_stRisco" id="z_ic_stRisco" value="LIKE"></span>
		<span class="control-group ewSearchField">
<div id="tp_x_ic_stRisco" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_stRisco" id="x_ic_stRisco" value="{value}"<?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>></div>
<div id="dsl_x_ic_stRisco" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $riscoprojeto->ic_stRisco->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($riscoprojeto->ic_stRisco->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_stRisco" name="x_ic_stRisco" id="x_ic_stRisco_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $riscoprojeto->ic_stRisco->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
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
<div id="xsr_5" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $riscoprojeto_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $riscoprojeto_list->ShowPageHeader(); ?>
<?php
$riscoprojeto_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="friscoprojetolist" id="friscoprojetolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="riscoprojeto">
<div id="gmp_riscoprojeto" class="ewGridMiddlePanel">
<?php if ($riscoprojeto_list->TotalRecs > 0) { ?>
<table id="tbl_riscoprojetolist" class="ewTable ewTableSeparate">
<?php echo $riscoprojeto->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$riscoprojeto_list->RenderListOptions();

// Render list options (header, left)
$riscoprojeto_list->ListOptions->Render("header", "left");
?>
<?php if ($riscoprojeto->nu_riscoProjeto->Visible) { // nu_riscoProjeto ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_riscoProjeto) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_riscoProjeto" class="riscoprojeto_nu_riscoProjeto"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_riscoProjeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->nu_riscoProjeto) ?>',2);"><div id="elh_riscoprojeto_nu_riscoProjeto" class="riscoprojeto_nu_riscoProjeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_riscoProjeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_riscoProjeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_riscoProjeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_projeto->Visible) { // nu_projeto ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_projeto) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_projeto" class="riscoprojeto_nu_projeto"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->nu_projeto) ?>',2);"><div id="elh_riscoprojeto_nu_projeto" class="riscoprojeto_nu_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_catRisco->Visible) { // nu_catRisco ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_catRisco) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_catRisco" class="riscoprojeto_nu_catRisco"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_catRisco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->nu_catRisco) ?>',2);"><div id="elh_riscoprojeto_nu_catRisco" class="riscoprojeto_nu_catRisco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_catRisco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_catRisco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_catRisco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->ic_tpRisco->Visible) { // ic_tpRisco ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->ic_tpRisco) == "") { ?>
		<td><div id="elh_riscoprojeto_ic_tpRisco" class="riscoprojeto_ic_tpRisco"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->ic_tpRisco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->ic_tpRisco) ?>',2);"><div id="elh_riscoprojeto_ic_tpRisco" class="riscoprojeto_ic_tpRisco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->ic_tpRisco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->ic_tpRisco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->ic_tpRisco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_probabilidade->Visible) { // nu_probabilidade ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_probabilidade) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_probabilidade" class="riscoprojeto_nu_probabilidade"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_probabilidade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->nu_probabilidade) ?>',2);"><div id="elh_riscoprojeto_nu_probabilidade" class="riscoprojeto_nu_probabilidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_probabilidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_probabilidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_probabilidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_impacto->Visible) { // nu_impacto ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_impacto) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_impacto" class="riscoprojeto_nu_impacto"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_impacto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->nu_impacto) ?>',2);"><div id="elh_riscoprojeto_nu_impacto" class="riscoprojeto_nu_impacto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_impacto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_impacto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_impacto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_severidade->Visible) { // nu_severidade ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_severidade) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_severidade" class="riscoprojeto_nu_severidade"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_severidade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->nu_severidade) ?>',2);"><div id="elh_riscoprojeto_nu_severidade" class="riscoprojeto_nu_severidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_severidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_severidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_severidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_acao->Visible) { // nu_acao ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_acao) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_acao" class="riscoprojeto_nu_acao"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_acao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->nu_acao) ?>',2);"><div id="elh_riscoprojeto_nu_acao" class="riscoprojeto_nu_acao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_acao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_acao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_acao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->nu_usuarioResp) == "") { ?>
		<td><div id="elh_riscoprojeto_nu_usuarioResp" class="riscoprojeto_nu_usuarioResp"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_usuarioResp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->nu_usuarioResp) ?>',2);"><div id="elh_riscoprojeto_nu_usuarioResp" class="riscoprojeto_nu_usuarioResp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->nu_usuarioResp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->nu_usuarioResp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->nu_usuarioResp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($riscoprojeto->ic_stRisco->Visible) { // ic_stRisco ?>
	<?php if ($riscoprojeto->SortUrl($riscoprojeto->ic_stRisco) == "") { ?>
		<td><div id="elh_riscoprojeto_ic_stRisco" class="riscoprojeto_ic_stRisco"><div class="ewTableHeaderCaption"><?php echo $riscoprojeto->ic_stRisco->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $riscoprojeto->SortUrl($riscoprojeto->ic_stRisco) ?>',2);"><div id="elh_riscoprojeto_ic_stRisco" class="riscoprojeto_ic_stRisco">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $riscoprojeto->ic_stRisco->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($riscoprojeto->ic_stRisco->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($riscoprojeto->ic_stRisco->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$riscoprojeto_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($riscoprojeto->ExportAll && $riscoprojeto->Export <> "") {
	$riscoprojeto_list->StopRec = $riscoprojeto_list->TotalRecs;
} else {

	// Set the last record to display
	if ($riscoprojeto_list->TotalRecs > $riscoprojeto_list->StartRec + $riscoprojeto_list->DisplayRecs - 1)
		$riscoprojeto_list->StopRec = $riscoprojeto_list->StartRec + $riscoprojeto_list->DisplayRecs - 1;
	else
		$riscoprojeto_list->StopRec = $riscoprojeto_list->TotalRecs;
}
$riscoprojeto_list->RecCnt = $riscoprojeto_list->StartRec - 1;
if ($riscoprojeto_list->Recordset && !$riscoprojeto_list->Recordset->EOF) {
	$riscoprojeto_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $riscoprojeto_list->StartRec > 1)
		$riscoprojeto_list->Recordset->Move($riscoprojeto_list->StartRec - 1);
} elseif (!$riscoprojeto->AllowAddDeleteRow && $riscoprojeto_list->StopRec == 0) {
	$riscoprojeto_list->StopRec = $riscoprojeto->GridAddRowCount;
}

// Initialize aggregate
$riscoprojeto->RowType = EW_ROWTYPE_AGGREGATEINIT;
$riscoprojeto->ResetAttrs();
$riscoprojeto_list->RenderRow();
while ($riscoprojeto_list->RecCnt < $riscoprojeto_list->StopRec) {
	$riscoprojeto_list->RecCnt++;
	if (intval($riscoprojeto_list->RecCnt) >= intval($riscoprojeto_list->StartRec)) {
		$riscoprojeto_list->RowCnt++;

		// Set up key count
		$riscoprojeto_list->KeyCount = $riscoprojeto_list->RowIndex;

		// Init row class and style
		$riscoprojeto->ResetAttrs();
		$riscoprojeto->CssClass = "";
		if ($riscoprojeto->CurrentAction == "gridadd") {
		} else {
			$riscoprojeto_list->LoadRowValues($riscoprojeto_list->Recordset); // Load row values
		}
		$riscoprojeto->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$riscoprojeto->RowAttrs = array_merge($riscoprojeto->RowAttrs, array('data-rowindex'=>$riscoprojeto_list->RowCnt, 'id'=>'r' . $riscoprojeto_list->RowCnt . '_riscoprojeto', 'data-rowtype'=>$riscoprojeto->RowType));

		// Render row
		$riscoprojeto_list->RenderRow();

		// Render list options
		$riscoprojeto_list->RenderListOptions();
?>
	<tr<?php echo $riscoprojeto->RowAttributes() ?>>
<?php

// Render list options (body, left)
$riscoprojeto_list->ListOptions->Render("body", "left", $riscoprojeto_list->RowCnt);
?>
	<?php if ($riscoprojeto->nu_riscoProjeto->Visible) { // nu_riscoProjeto ?>
		<td<?php echo $riscoprojeto->nu_riscoProjeto->CellAttributes() ?>>
<span<?php echo $riscoprojeto->nu_riscoProjeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_riscoProjeto->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_projeto->Visible) { // nu_projeto ?>
		<td<?php echo $riscoprojeto->nu_projeto->CellAttributes() ?>>
<span<?php echo $riscoprojeto->nu_projeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_projeto->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_catRisco->Visible) { // nu_catRisco ?>
		<td<?php echo $riscoprojeto->nu_catRisco->CellAttributes() ?>>
<span<?php echo $riscoprojeto->nu_catRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_catRisco->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->ic_tpRisco->Visible) { // ic_tpRisco ?>
		<td<?php echo $riscoprojeto->ic_tpRisco->CellAttributes() ?>>
<span<?php echo $riscoprojeto->ic_tpRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_tpRisco->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_probabilidade->Visible) { // nu_probabilidade ?>
		<td<?php echo $riscoprojeto->nu_probabilidade->CellAttributes() ?>>
<span<?php echo $riscoprojeto->nu_probabilidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_probabilidade->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_impacto->Visible) { // nu_impacto ?>
		<td<?php echo $riscoprojeto->nu_impacto->CellAttributes() ?>>
<span<?php echo $riscoprojeto->nu_impacto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_impacto->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_severidade->Visible) { // nu_severidade ?>
		<td<?php echo $riscoprojeto->nu_severidade->CellAttributes() ?>>
<span<?php echo $riscoprojeto->nu_severidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_severidade->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_acao->Visible) { // nu_acao ?>
		<td<?php echo $riscoprojeto->nu_acao->CellAttributes() ?>>
<span<?php echo $riscoprojeto->nu_acao->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_acao->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
		<td<?php echo $riscoprojeto->nu_usuarioResp->CellAttributes() ?>>
<span<?php echo $riscoprojeto->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_usuarioResp->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($riscoprojeto->ic_stRisco->Visible) { // ic_stRisco ?>
		<td<?php echo $riscoprojeto->ic_stRisco->CellAttributes() ?>>
<span<?php echo $riscoprojeto->ic_stRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_stRisco->ListViewValue() ?></span>
<a id="<?php echo $riscoprojeto_list->PageObjName . "_row_" . $riscoprojeto_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$riscoprojeto_list->ListOptions->Render("body", "right", $riscoprojeto_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($riscoprojeto->CurrentAction <> "gridadd")
		$riscoprojeto_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($riscoprojeto->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($riscoprojeto_list->Recordset)
	$riscoprojeto_list->Recordset->Close();
?>
<?php if ($riscoprojeto->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($riscoprojeto->CurrentAction <> "gridadd" && $riscoprojeto->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($riscoprojeto_list->Pager)) $riscoprojeto_list->Pager = new cNumericPager($riscoprojeto_list->StartRec, $riscoprojeto_list->DisplayRecs, $riscoprojeto_list->TotalRecs, $riscoprojeto_list->RecRange) ?>
<?php if ($riscoprojeto_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($riscoprojeto_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $riscoprojeto_list->PageUrl() ?>start=<?php echo $riscoprojeto_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($riscoprojeto_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $riscoprojeto_list->PageUrl() ?>start=<?php echo $riscoprojeto_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($riscoprojeto_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $riscoprojeto_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($riscoprojeto_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $riscoprojeto_list->PageUrl() ?>start=<?php echo $riscoprojeto_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($riscoprojeto_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $riscoprojeto_list->PageUrl() ?>start=<?php echo $riscoprojeto_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($riscoprojeto_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $riscoprojeto_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $riscoprojeto_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $riscoprojeto_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($riscoprojeto_list->SearchWhere == "0=101") { ?>
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
	foreach ($riscoprojeto_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($riscoprojeto->Export == "") { ?>
<script type="text/javascript">
friscoprojetolistsrch.Init();
friscoprojetolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$riscoprojeto_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($riscoprojeto->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$riscoprojeto_list->Page_Terminate();
?>
