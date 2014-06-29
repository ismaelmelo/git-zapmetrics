<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "parsisp_versaoinfo.php" ?>
<?php include_once "parsispinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$parsisp_versao_list = NULL; // Initialize page object first

class cparsisp_versao_list extends cparsisp_versao {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'parsisp_versao';

	// Page object name
	var $PageObjName = 'parsisp_versao_list';

	// Grid form hidden field names
	var $FormName = 'fparsisp_versaolist';
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

		// Table object (parsisp_versao)
		if (!isset($GLOBALS["parsisp_versao"])) {
			$GLOBALS["parsisp_versao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["parsisp_versao"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "parsisp_versaoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "parsisp_versaodelete.php";
		$this->MultiUpdateUrl = "parsisp_versaoupdate.php";

		// Table object (parSisp)
		if (!isset($GLOBALS['parSisp'])) $GLOBALS['parSisp'] = new cparSisp();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'parsisp_versao', TRUE);

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
		$this->dh_inclusao->Visible = !$this->IsAddOrEdit();

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "parSisp") {
			global $parSisp;
			$rsmaster = $parSisp->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("parsisplist.php"); // Return to master page
			} else {
				$parSisp->LoadListRowValues($rsmaster);
				$parSisp->RowType = EW_ROWTYPE_MASTER; // Master row
				$parSisp->RenderListRow();
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
			$this->nu_parSisp->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_parSisp->FormValue))
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
		$this->BuildSearchSql($sWhere, $this->nu_parSisp, FALSE); // nu_parSisp
		$this->BuildSearchSql($sWhere, $this->nu_versao, FALSE); // nu_versao
		$this->BuildSearchSql($sWhere, $this->vr_parSisp, FALSE); // vr_parSisp
		$this->BuildSearchSql($sWhere, $this->ds_codigoSql, FALSE); // ds_codigoSql
		$this->BuildSearchSql($sWhere, $this->nu_usuarioResp, FALSE); // nu_usuarioResp
		$this->BuildSearchSql($sWhere, $this->ds_versao, FALSE); // ds_versao
		$this->BuildSearchSql($sWhere, $this->dh_inclusao, FALSE); // dh_inclusao

		// Set up search parm
		if ($sWhere <> "") {
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->nu_parSisp->AdvancedSearch->Save(); // nu_parSisp
			$this->nu_versao->AdvancedSearch->Save(); // nu_versao
			$this->vr_parSisp->AdvancedSearch->Save(); // vr_parSisp
			$this->ds_codigoSql->AdvancedSearch->Save(); // ds_codigoSql
			$this->nu_usuarioResp->AdvancedSearch->Save(); // nu_usuarioResp
			$this->ds_versao->AdvancedSearch->Save(); // ds_versao
			$this->dh_inclusao->AdvancedSearch->Save(); // dh_inclusao
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
		if ($this->nu_parSisp->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_versao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->vr_parSisp->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_codigoSql->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nu_usuarioResp->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ds_versao->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->dh_inclusao->AdvancedSearch->IssetSession())
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
		$this->nu_parSisp->AdvancedSearch->UnsetSession();
		$this->nu_versao->AdvancedSearch->UnsetSession();
		$this->vr_parSisp->AdvancedSearch->UnsetSession();
		$this->ds_codigoSql->AdvancedSearch->UnsetSession();
		$this->nu_usuarioResp->AdvancedSearch->UnsetSession();
		$this->ds_versao->AdvancedSearch->UnsetSession();
		$this->dh_inclusao->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore advanced search values
		$this->nu_parSisp->AdvancedSearch->Load();
		$this->nu_versao->AdvancedSearch->Load();
		$this->vr_parSisp->AdvancedSearch->Load();
		$this->ds_codigoSql->AdvancedSearch->Load();
		$this->nu_usuarioResp->AdvancedSearch->Load();
		$this->ds_versao->AdvancedSearch->Load();
		$this->dh_inclusao->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_parSisp, $bCtrl); // nu_parSisp
			$this->UpdateSort($this->nu_versao, $bCtrl); // nu_versao
			$this->UpdateSort($this->vr_parSisp, $bCtrl); // vr_parSisp
			$this->UpdateSort($this->nu_usuarioResp, $bCtrl); // nu_usuarioResp
			$this->UpdateSort($this->dh_inclusao, $bCtrl); // dh_inclusao
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
				$this->nu_parSisp->setSort("ASC");
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
				$this->nu_parSisp->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->nu_parSisp->setSort("");
				$this->nu_versao->setSort("");
				$this->vr_parSisp->setSort("");
				$this->nu_usuarioResp->setSort("");
				$this->dh_inclusao->setSort("");
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

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
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

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fparsisp_versaolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		// nu_parSisp

		$this->nu_parSisp->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_parSisp"]);
		if ($this->nu_parSisp->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_parSisp->AdvancedSearch->SearchOperator = @$_GET["z_nu_parSisp"];

		// nu_versao
		$this->nu_versao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_versao"]);
		if ($this->nu_versao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_versao->AdvancedSearch->SearchOperator = @$_GET["z_nu_versao"];

		// vr_parSisp
		$this->vr_parSisp->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_vr_parSisp"]);
		if ($this->vr_parSisp->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->vr_parSisp->AdvancedSearch->SearchOperator = @$_GET["z_vr_parSisp"];

		// ds_codigoSql
		$this->ds_codigoSql->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_codigoSql"]);
		if ($this->ds_codigoSql->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_codigoSql->AdvancedSearch->SearchOperator = @$_GET["z_ds_codigoSql"];

		// nu_usuarioResp
		$this->nu_usuarioResp->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_nu_usuarioResp"]);
		if ($this->nu_usuarioResp->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->nu_usuarioResp->AdvancedSearch->SearchOperator = @$_GET["z_nu_usuarioResp"];

		// ds_versao
		$this->ds_versao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ds_versao"]);
		if ($this->ds_versao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ds_versao->AdvancedSearch->SearchOperator = @$_GET["z_ds_versao"];

		// dh_inclusao
		$this->dh_inclusao->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_dh_inclusao"]);
		if ($this->dh_inclusao->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->dh_inclusao->AdvancedSearch->SearchOperator = @$_GET["z_dh_inclusao"];
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
		$this->nu_parSisp->setDbValue($rs->fields('nu_parSisp'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->vr_parSisp->setDbValue($rs->fields('vr_parSisp'));
		$this->ds_codigoSql->setDbValue($rs->fields('ds_codigoSql'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ds_versao->setDbValue($rs->fields('ds_versao'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_parSisp->DbValue = $row['nu_parSisp'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->vr_parSisp->DbValue = $row['vr_parSisp'];
		$this->ds_codigoSql->DbValue = $row['ds_codigoSql'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ds_versao->DbValue = $row['ds_versao'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_parSisp")) <> "")
			$this->nu_parSisp->CurrentValue = $this->getKey("nu_parSisp"); // nu_parSisp
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
		if ($this->vr_parSisp->FormValue == $this->vr_parSisp->CurrentValue && is_numeric(ew_StrToFloat($this->vr_parSisp->CurrentValue)))
			$this->vr_parSisp->CurrentValue = ew_StrToFloat($this->vr_parSisp->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_parSisp
		// nu_versao
		// vr_parSisp
		// ds_codigoSql
		// nu_usuarioResp
		// ds_versao
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_parSisp
			if (strval($this->nu_parSisp->CurrentValue) <> "") {
				$sFilterWrk = "[nu_parSisp]" . ew_SearchString("=", $this->nu_parSisp->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_parSisp], [no_parSisp] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[parSisp]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_parSisp, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_parSisp] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_parSisp->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_parSisp->ViewValue = $this->nu_parSisp->CurrentValue;
				}
			} else {
				$this->nu_parSisp->ViewValue = NULL;
			}
			$this->nu_parSisp->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// vr_parSisp
			$this->vr_parSisp->ViewValue = $this->vr_parSisp->CurrentValue;
			$this->vr_parSisp->ViewCustomAttributes = "";

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

			// ds_versao
			$this->ds_versao->ViewValue = $this->ds_versao->CurrentValue;
			$this->ds_versao->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 10);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_parSisp
			$this->nu_parSisp->LinkCustomAttributes = "";
			$this->nu_parSisp->HrefValue = "";
			$this->nu_parSisp->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// vr_parSisp
			$this->vr_parSisp->LinkCustomAttributes = "";
			$this->vr_parSisp->HrefValue = "";
			$this->vr_parSisp->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// nu_parSisp
			$this->nu_parSisp->EditCustomAttributes = "";

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "readonly";
			$this->nu_versao->EditValue = ew_HtmlEncode($this->nu_versao->AdvancedSearch->SearchValue);
			$this->nu_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versao->FldCaption()));

			// vr_parSisp
			$this->vr_parSisp->EditCustomAttributes = "";
			$this->vr_parSisp->EditValue = ew_HtmlEncode($this->vr_parSisp->AdvancedSearch->SearchValue);
			$this->vr_parSisp->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_parSisp->FldCaption()));

			// nu_usuarioResp
			$this->nu_usuarioResp->EditCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->EditCustomAttributes = "";
			$this->dh_inclusao->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->dh_inclusao->AdvancedSearch->SearchValue, 10), 10));
			$this->dh_inclusao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dh_inclusao->FldCaption()));
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
		$this->nu_parSisp->AdvancedSearch->Load();
		$this->nu_versao->AdvancedSearch->Load();
		$this->vr_parSisp->AdvancedSearch->Load();
		$this->ds_codigoSql->AdvancedSearch->Load();
		$this->nu_usuarioResp->AdvancedSearch->Load();
		$this->ds_versao->AdvancedSearch->Load();
		$this->dh_inclusao->AdvancedSearch->Load();
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
		$item->Body = "<a id=\"emf_parsisp_versao\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_parsisp_versao',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fparsisp_versaolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "parSisp") {
			global $parSisp;
			$rsmaster = $parSisp->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$parSisp->ExportDocument($ExportDoc, $rsmaster, 1, 1);
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
		$this->AddSearchQueryString($sQry, $this->nu_parSisp); // nu_parSisp
		$this->AddSearchQueryString($sQry, $this->nu_versao); // nu_versao
		$this->AddSearchQueryString($sQry, $this->vr_parSisp); // vr_parSisp
		$this->AddSearchQueryString($sQry, $this->ds_codigoSql); // ds_codigoSql
		$this->AddSearchQueryString($sQry, $this->nu_usuarioResp); // nu_usuarioResp
		$this->AddSearchQueryString($sQry, $this->ds_versao); // ds_versao
		$this->AddSearchQueryString($sQry, $this->dh_inclusao); // dh_inclusao

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
			if ($sMasterTblVar == "parSisp") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_parSisp"] <> "") {
					$GLOBALS["parSisp"]->nu_parSisp->setQueryStringValue($_GET["nu_parSisp"]);
					$this->nu_parSisp->setQueryStringValue($GLOBALS["parSisp"]->nu_parSisp->QueryStringValue);
					$this->nu_parSisp->setSessionValue($this->nu_parSisp->QueryStringValue);
					if (!is_numeric($GLOBALS["parSisp"]->nu_parSisp->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "parSisp") {
				if ($this->nu_parSisp->QueryStringValue == "") $this->nu_parSisp->setSessionValue("");
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
		$table = 'parsisp_versao';
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
if (!isset($parsisp_versao_list)) $parsisp_versao_list = new cparsisp_versao_list();

// Page init
$parsisp_versao_list->Page_Init();

// Page main
$parsisp_versao_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$parsisp_versao_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($parsisp_versao->Export == "") { ?>
<script type="text/javascript">

// Page object
var parsisp_versao_list = new ew_Page("parsisp_versao_list");
parsisp_versao_list.PageID = "list"; // Page ID
var EW_PAGE_ID = parsisp_versao_list.PageID; // For backward compatibility

// Form object
var fparsisp_versaolist = new ew_Form("fparsisp_versaolist");
fparsisp_versaolist.FormKeyCountName = '<?php echo $parsisp_versao_list->FormKeyCountName ?>';

// Form_CustomValidate event
fparsisp_versaolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparsisp_versaolist.ValidateRequired = true;
<?php } else { ?>
fparsisp_versaolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fparsisp_versaolist.Lists["x_nu_parSisp"] = {"LinkField":"x_nu_parSisp","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_parSisp","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fparsisp_versaolist.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fparsisp_versaolistsrch = new ew_Form("fparsisp_versaolistsrch");

// Validate function for search
fparsisp_versaolistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_nu_versao");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($parsisp_versao->nu_versao->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fparsisp_versaolistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fparsisp_versaolistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fparsisp_versaolistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (fparsisp_versaolistsrch) fparsisp_versaolistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($parsisp_versao->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($parsisp_versao->getCurrentMasterTable() == "" && $parsisp_versao_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $parsisp_versao_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($parsisp_versao->Export == "") || (EW_EXPORT_MASTER_RECORD && $parsisp_versao->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "parsisplist.php";
if ($parsisp_versao_list->DbMasterFilter <> "" && $parsisp_versao->getCurrentMasterTable() == "parSisp") {
	if ($parsisp_versao_list->MasterRecordExists) {
		if ($parsisp_versao->getCurrentMasterTable() == $parsisp_versao->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($parsisp_versao_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $parsisp_versao_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "parsispmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$parsisp_versao_list->TotalRecs = $parsisp_versao->SelectRecordCount();
	} else {
		if ($parsisp_versao_list->Recordset = $parsisp_versao_list->LoadRecordset())
			$parsisp_versao_list->TotalRecs = $parsisp_versao_list->Recordset->RecordCount();
	}
	$parsisp_versao_list->StartRec = 1;
	if ($parsisp_versao_list->DisplayRecs <= 0 || ($parsisp_versao->Export <> "" && $parsisp_versao->ExportAll)) // Display all records
		$parsisp_versao_list->DisplayRecs = $parsisp_versao_list->TotalRecs;
	if (!($parsisp_versao->Export <> "" && $parsisp_versao->ExportAll))
		$parsisp_versao_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$parsisp_versao_list->Recordset = $parsisp_versao_list->LoadRecordset($parsisp_versao_list->StartRec-1, $parsisp_versao_list->DisplayRecs);
$parsisp_versao_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($parsisp_versao->Export == "" && $parsisp_versao->CurrentAction == "") { ?>
<form name="fparsisp_versaolistsrch" id="fparsisp_versaolistsrch" class="ewForm form-inline" action="<?php echo ew_CurrentPage() ?>">
<table class="ewSearchTable"><tr><td>
<div class="accordion" id="fparsisp_versaolistsrch_SearchGroup">
	<div class="accordion-group">
		<div class="accordion-heading">
<a class="accordion-toggle" data-toggle="collapse" data-parent="#fparsisp_versaolistsrch_SearchGroup" href="#fparsisp_versaolistsrch_SearchBody"><?php echo $Language->Phrase("Search") ?></a>
		</div>
		<div id="fparsisp_versaolistsrch_SearchBody" class="accordion-body collapse in">
			<div class="accordion-inner">
<div id="fparsisp_versaolistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="parsisp_versao">
<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$parsisp_versao_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$parsisp_versao->RowType = EW_ROWTYPE_SEARCH;

// Render row
$parsisp_versao->ResetAttrs();
$parsisp_versao_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($parsisp_versao->nu_versao->Visible) { // nu_versao ?>
	<span id="xsc_nu_versao" class="ewCell">
		<span class="ewSearchCaption"><?php echo $parsisp_versao->nu_versao->FldCaption() ?></span>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nu_versao" id="z_nu_versao" value="="></span>
		<span class="control-group ewSearchField">
<input type="text" data-field="x_nu_versao" name="x_nu_versao" id="x_nu_versao" size="30" placeholder="<?php echo $parsisp_versao->nu_versao->PlaceHolder ?>" value="<?php echo $parsisp_versao->nu_versao->EditValue ?>"<?php echo $parsisp_versao->nu_versao->EditAttributes() ?>>
</span>
	</span>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="btn-group ewButtonGroup">
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	<div class="btn-group ewButtonGroup">
	<a class="btn ewShowAll" href="<?php echo $parsisp_versao_list->PageUrl() ?>cmd=reset"><?php echo $Language->Phrase("ShowAll") ?></a>
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
<?php $parsisp_versao_list->ShowPageHeader(); ?>
<?php
$parsisp_versao_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fparsisp_versaolist" id="fparsisp_versaolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="parsisp_versao">
<div id="gmp_parsisp_versao" class="ewGridMiddlePanel">
<?php if ($parsisp_versao_list->TotalRecs > 0) { ?>
<table id="tbl_parsisp_versaolist" class="ewTable ewTableSeparate">
<?php echo $parsisp_versao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$parsisp_versao_list->RenderListOptions();

// Render list options (header, left)
$parsisp_versao_list->ListOptions->Render("header", "left");
?>
<?php if ($parsisp_versao->nu_parSisp->Visible) { // nu_parSisp ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->nu_parSisp) == "") { ?>
		<td><div id="elh_parsisp_versao_nu_parSisp" class="parsisp_versao_nu_parSisp"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_parSisp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parsisp_versao->SortUrl($parsisp_versao->nu_parSisp) ?>',2);"><div id="elh_parsisp_versao_nu_parSisp" class="parsisp_versao_nu_parSisp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_parSisp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->nu_parSisp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->nu_parSisp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($parsisp_versao->nu_versao->Visible) { // nu_versao ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->nu_versao) == "") { ?>
		<td><div id="elh_parsisp_versao_nu_versao" class="parsisp_versao_nu_versao"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_versao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parsisp_versao->SortUrl($parsisp_versao->nu_versao) ?>',2);"><div id="elh_parsisp_versao_nu_versao" class="parsisp_versao_nu_versao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_versao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->nu_versao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->nu_versao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($parsisp_versao->vr_parSisp->Visible) { // vr_parSisp ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->vr_parSisp) == "") { ?>
		<td><div id="elh_parsisp_versao_vr_parSisp" class="parsisp_versao_vr_parSisp"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->vr_parSisp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parsisp_versao->SortUrl($parsisp_versao->vr_parSisp) ?>',2);"><div id="elh_parsisp_versao_vr_parSisp" class="parsisp_versao_vr_parSisp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->vr_parSisp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->vr_parSisp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->vr_parSisp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($parsisp_versao->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->nu_usuarioResp) == "") { ?>
		<td><div id="elh_parsisp_versao_nu_usuarioResp" class="parsisp_versao_nu_usuarioResp"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_usuarioResp->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parsisp_versao->SortUrl($parsisp_versao->nu_usuarioResp) ?>',2);"><div id="elh_parsisp_versao_nu_usuarioResp" class="parsisp_versao_nu_usuarioResp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->nu_usuarioResp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->nu_usuarioResp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->nu_usuarioResp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($parsisp_versao->dh_inclusao->Visible) { // dh_inclusao ?>
	<?php if ($parsisp_versao->SortUrl($parsisp_versao->dh_inclusao) == "") { ?>
		<td><div id="elh_parsisp_versao_dh_inclusao" class="parsisp_versao_dh_inclusao"><div class="ewTableHeaderCaption"><?php echo $parsisp_versao->dh_inclusao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $parsisp_versao->SortUrl($parsisp_versao->dh_inclusao) ?>',2);"><div id="elh_parsisp_versao_dh_inclusao" class="parsisp_versao_dh_inclusao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $parsisp_versao->dh_inclusao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($parsisp_versao->dh_inclusao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($parsisp_versao->dh_inclusao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$parsisp_versao_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($parsisp_versao->ExportAll && $parsisp_versao->Export <> "") {
	$parsisp_versao_list->StopRec = $parsisp_versao_list->TotalRecs;
} else {

	// Set the last record to display
	if ($parsisp_versao_list->TotalRecs > $parsisp_versao_list->StartRec + $parsisp_versao_list->DisplayRecs - 1)
		$parsisp_versao_list->StopRec = $parsisp_versao_list->StartRec + $parsisp_versao_list->DisplayRecs - 1;
	else
		$parsisp_versao_list->StopRec = $parsisp_versao_list->TotalRecs;
}
$parsisp_versao_list->RecCnt = $parsisp_versao_list->StartRec - 1;
if ($parsisp_versao_list->Recordset && !$parsisp_versao_list->Recordset->EOF) {
	$parsisp_versao_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $parsisp_versao_list->StartRec > 1)
		$parsisp_versao_list->Recordset->Move($parsisp_versao_list->StartRec - 1);
} elseif (!$parsisp_versao->AllowAddDeleteRow && $parsisp_versao_list->StopRec == 0) {
	$parsisp_versao_list->StopRec = $parsisp_versao->GridAddRowCount;
}

// Initialize aggregate
$parsisp_versao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$parsisp_versao->ResetAttrs();
$parsisp_versao_list->RenderRow();
while ($parsisp_versao_list->RecCnt < $parsisp_versao_list->StopRec) {
	$parsisp_versao_list->RecCnt++;
	if (intval($parsisp_versao_list->RecCnt) >= intval($parsisp_versao_list->StartRec)) {
		$parsisp_versao_list->RowCnt++;

		// Set up key count
		$parsisp_versao_list->KeyCount = $parsisp_versao_list->RowIndex;

		// Init row class and style
		$parsisp_versao->ResetAttrs();
		$parsisp_versao->CssClass = "";
		if ($parsisp_versao->CurrentAction == "gridadd") {
		} else {
			$parsisp_versao_list->LoadRowValues($parsisp_versao_list->Recordset); // Load row values
		}
		$parsisp_versao->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$parsisp_versao->RowAttrs = array_merge($parsisp_versao->RowAttrs, array('data-rowindex'=>$parsisp_versao_list->RowCnt, 'id'=>'r' . $parsisp_versao_list->RowCnt . '_parsisp_versao', 'data-rowtype'=>$parsisp_versao->RowType));

		// Render row
		$parsisp_versao_list->RenderRow();

		// Render list options
		$parsisp_versao_list->RenderListOptions();
?>
	<tr<?php echo $parsisp_versao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$parsisp_versao_list->ListOptions->Render("body", "left", $parsisp_versao_list->RowCnt);
?>
	<?php if ($parsisp_versao->nu_parSisp->Visible) { // nu_parSisp ?>
		<td<?php echo $parsisp_versao->nu_parSisp->CellAttributes() ?>>
<span<?php echo $parsisp_versao->nu_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_parSisp->ListViewValue() ?></span>
<a id="<?php echo $parsisp_versao_list->PageObjName . "_row_" . $parsisp_versao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($parsisp_versao->nu_versao->Visible) { // nu_versao ?>
		<td<?php echo $parsisp_versao->nu_versao->CellAttributes() ?>>
<span<?php echo $parsisp_versao->nu_versao->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_versao->ListViewValue() ?></span>
<a id="<?php echo $parsisp_versao_list->PageObjName . "_row_" . $parsisp_versao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($parsisp_versao->vr_parSisp->Visible) { // vr_parSisp ?>
		<td<?php echo $parsisp_versao->vr_parSisp->CellAttributes() ?>>
<span<?php echo $parsisp_versao->vr_parSisp->ViewAttributes() ?>>
<?php echo $parsisp_versao->vr_parSisp->ListViewValue() ?></span>
<a id="<?php echo $parsisp_versao_list->PageObjName . "_row_" . $parsisp_versao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($parsisp_versao->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
		<td<?php echo $parsisp_versao->nu_usuarioResp->CellAttributes() ?>>
<span<?php echo $parsisp_versao->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $parsisp_versao->nu_usuarioResp->ListViewValue() ?></span>
<a id="<?php echo $parsisp_versao_list->PageObjName . "_row_" . $parsisp_versao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($parsisp_versao->dh_inclusao->Visible) { // dh_inclusao ?>
		<td<?php echo $parsisp_versao->dh_inclusao->CellAttributes() ?>>
<span<?php echo $parsisp_versao->dh_inclusao->ViewAttributes() ?>>
<?php echo $parsisp_versao->dh_inclusao->ListViewValue() ?></span>
<a id="<?php echo $parsisp_versao_list->PageObjName . "_row_" . $parsisp_versao_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$parsisp_versao_list->ListOptions->Render("body", "right", $parsisp_versao_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($parsisp_versao->CurrentAction <> "gridadd")
		$parsisp_versao_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($parsisp_versao->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($parsisp_versao_list->Recordset)
	$parsisp_versao_list->Recordset->Close();
?>
<?php if ($parsisp_versao->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($parsisp_versao->CurrentAction <> "gridadd" && $parsisp_versao->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($parsisp_versao_list->Pager)) $parsisp_versao_list->Pager = new cNumericPager($parsisp_versao_list->StartRec, $parsisp_versao_list->DisplayRecs, $parsisp_versao_list->TotalRecs, $parsisp_versao_list->RecRange) ?>
<?php if ($parsisp_versao_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($parsisp_versao_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $parsisp_versao_list->PageUrl() ?>start=<?php echo $parsisp_versao_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($parsisp_versao_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $parsisp_versao_list->PageUrl() ?>start=<?php echo $parsisp_versao_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($parsisp_versao_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $parsisp_versao_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($parsisp_versao_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $parsisp_versao_list->PageUrl() ?>start=<?php echo $parsisp_versao_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($parsisp_versao_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $parsisp_versao_list->PageUrl() ?>start=<?php echo $parsisp_versao_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($parsisp_versao_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $parsisp_versao_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $parsisp_versao_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $parsisp_versao_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($parsisp_versao_list->SearchWhere == "0=101") { ?>
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
	foreach ($parsisp_versao_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($parsisp_versao->Export == "") { ?>
<script type="text/javascript">
fparsisp_versaolistsrch.Init();
fparsisp_versaolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$parsisp_versao_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($parsisp_versao->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$parsisp_versao_list->Page_Terminate();
?>
