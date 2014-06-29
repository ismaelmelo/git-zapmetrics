<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "contagempf_funcaoinfo.php" ?>
<?php include_once "contagempfinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$contagempf_funcao_list = NULL; // Initialize page object first

class ccontagempf_funcao_list extends ccontagempf_funcao {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contagempf_funcao';

	// Page object name
	var $PageObjName = 'contagempf_funcao_list';

	// Grid form hidden field names
	var $FormName = 'fcontagempf_funcaolist';
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

		// Table object (contagempf_funcao)
		if (!isset($GLOBALS["contagempf_funcao"])) {
			$GLOBALS["contagempf_funcao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contagempf_funcao"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "contagempf_funcaoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "contagempf_funcaodelete.php";
		$this->MultiUpdateUrl = "contagempf_funcaoupdate.php";

		// Table object (contagempf)
		if (!isset($GLOBALS['contagempf'])) $GLOBALS['contagempf'] = new ccontagempf();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contagempf_funcao', TRUE);

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

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 100; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "contagempf") {
			global $contagempf;
			$rsmaster = $contagempf->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("contagempflist.php"); // Return to master page
			} else {
				$contagempf->LoadListRowValues($rsmaster);
				$contagempf->RowType = EW_ROWTYPE_MASTER; // Master row
				$contagempf->RenderListRow();
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
			$this->nu_funcao->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_funcao->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_agrupador, $bCtrl); // nu_agrupador
			$this->UpdateSort($this->nu_uc, $bCtrl); // nu_uc
			$this->UpdateSort($this->no_funcao, $bCtrl); // no_funcao
			$this->UpdateSort($this->nu_tpManutencao, $bCtrl); // nu_tpManutencao
			$this->UpdateSort($this->nu_tpElemento, $bCtrl); // nu_tpElemento
			$this->UpdateSort($this->qt_alr, $bCtrl); // qt_alr
			$this->UpdateSort($this->qt_der, $bCtrl); // qt_der
			$this->UpdateSort($this->ic_complexApf, $bCtrl); // ic_complexApf
			$this->UpdateSort($this->vr_contribuicao, $bCtrl); // vr_contribuicao
			$this->UpdateSort($this->vr_fatorReducao, $bCtrl); // vr_fatorReducao
			$this->UpdateSort($this->pc_varFasesRoteiro, $bCtrl); // pc_varFasesRoteiro
			$this->UpdateSort($this->vr_qtPf, $bCtrl); // vr_qtPf
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
				$this->nu_agrupador->setSort("ASC");
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->nu_contagem->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->nu_agrupador->setSort("");
				$this->nu_uc->setSort("");
				$this->no_funcao->setSort("");
				$this->nu_tpManutencao->setSort("");
				$this->nu_tpElemento->setSort("");
				$this->qt_alr->setSort("");
				$this->qt_der->setSort("");
				$this->ic_complexApf->setSort("");
				$this->vr_contribuicao->setSort("");
				$this->vr_fatorReducao->setSort("");
				$this->pc_varFasesRoteiro->setSort("");
				$this->vr_qtPf->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fcontagempf_funcaolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->nu_funcao->setDbValue($rs->fields('nu_funcao'));
		$this->nu_agrupador->setDbValue($rs->fields('nu_agrupador'));
		if (array_key_exists('EV__nu_agrupador', $rs->fields)) {
			$this->nu_agrupador->VirtualValue = $rs->fields('EV__nu_agrupador'); // Set up virtual field value
		} else {
			$this->nu_agrupador->VirtualValue = ""; // Clear value
		}
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->no_funcao->setDbValue($rs->fields('no_funcao'));
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->nu_tpElemento->setDbValue($rs->fields('nu_tpElemento'));
		$this->qt_alr->setDbValue($rs->fields('qt_alr'));
		$this->ds_alr->setDbValue($rs->fields('ds_alr'));
		$this->qt_der->setDbValue($rs->fields('qt_der'));
		$this->ds_der->setDbValue($rs->fields('ds_der'));
		$this->ic_complexApf->setDbValue($rs->fields('ic_complexApf'));
		$this->vr_contribuicao->setDbValue($rs->fields('vr_contribuicao'));
		$this->vr_fatorReducao->setDbValue($rs->fields('vr_fatorReducao'));
		$this->pc_varFasesRoteiro->setDbValue($rs->fields('pc_varFasesRoteiro'));
		$this->vr_qtPf->setDbValue($rs->fields('vr_qtPf'));
		$this->ic_analalogia->setDbValue($rs->fields('ic_analalogia'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->nu_usuarioLogado->setDbValue($rs->fields('nu_usuarioLogado'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contagem->DbValue = $row['nu_contagem'];
		$this->nu_funcao->DbValue = $row['nu_funcao'];
		$this->nu_agrupador->DbValue = $row['nu_agrupador'];
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->no_funcao->DbValue = $row['no_funcao'];
		$this->nu_tpManutencao->DbValue = $row['nu_tpManutencao'];
		$this->nu_tpElemento->DbValue = $row['nu_tpElemento'];
		$this->qt_alr->DbValue = $row['qt_alr'];
		$this->ds_alr->DbValue = $row['ds_alr'];
		$this->qt_der->DbValue = $row['qt_der'];
		$this->ds_der->DbValue = $row['ds_der'];
		$this->ic_complexApf->DbValue = $row['ic_complexApf'];
		$this->vr_contribuicao->DbValue = $row['vr_contribuicao'];
		$this->vr_fatorReducao->DbValue = $row['vr_fatorReducao'];
		$this->pc_varFasesRoteiro->DbValue = $row['pc_varFasesRoteiro'];
		$this->vr_qtPf->DbValue = $row['vr_qtPf'];
		$this->ic_analalogia->DbValue = $row['ic_analalogia'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->nu_usuarioLogado->DbValue = $row['nu_usuarioLogado'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_funcao")) <> "")
			$this->nu_funcao->CurrentValue = $this->getKey("nu_funcao"); // nu_funcao
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
		if ($this->vr_fatorReducao->FormValue == $this->vr_fatorReducao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_fatorReducao->CurrentValue)))
			$this->vr_fatorReducao->CurrentValue = ew_StrToFloat($this->vr_fatorReducao->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pc_varFasesRoteiro->FormValue == $this->pc_varFasesRoteiro->CurrentValue && is_numeric(ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue)))
			$this->pc_varFasesRoteiro->CurrentValue = ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_qtPf->FormValue == $this->vr_qtPf->CurrentValue && is_numeric(ew_StrToFloat($this->vr_qtPf->CurrentValue)))
			$this->vr_qtPf->CurrentValue = ew_StrToFloat($this->vr_qtPf->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_contagem

		$this->nu_contagem->CellCssStyle = "white-space: nowrap;";

		// nu_funcao
		// nu_agrupador
		// nu_uc
		// no_funcao
		// nu_tpManutencao
		// nu_tpElemento
		// qt_alr
		// ds_alr
		// qt_der
		// ds_der
		// ic_complexApf
		// vr_contribuicao
		// vr_fatorReducao
		// pc_varFasesRoteiro
		// vr_qtPf
		// ic_analalogia
		// ds_observacoes
		// nu_usuarioLogado
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_agrupador
			if ($this->nu_agrupador->VirtualValue <> "") {
				$this->nu_agrupador->ViewValue = $this->nu_agrupador->VirtualValue;
			} else {
			if (strval($this->nu_agrupador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_agrupador]" . ew_SearchString("=", $this->nu_agrupador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_agrupador], [no_agrupador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf_agrupador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_agrupador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_agrupador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_agrupador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_agrupador->ViewValue = $this->nu_agrupador->CurrentValue;
				}
			} else {
				$this->nu_agrupador->ViewValue = NULL;
			}
			}
			$this->nu_agrupador->ViewCustomAttributes = "";

			// nu_uc
			if (strval($this->nu_uc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_uc->ViewValue = $rswrk->fields('DispFld');
					$this->nu_uc->ViewValue .= ew_ValueSeparator(1,$this->nu_uc) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
				}
			} else {
				$this->nu_uc->ViewValue = NULL;
			}
			$this->nu_uc->ViewCustomAttributes = "";

			// no_funcao
			$this->no_funcao->ViewValue = $this->no_funcao->CurrentValue;
			$this->no_funcao->ViewCustomAttributes = "";

			// nu_tpManutencao
			if (strval($this->nu_tpManutencao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpManutencao]" . ew_SearchString("=", $this->nu_tpManutencao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_tpContagem]=(SELECT nu_tpContagem FROM contagempf WHERE nu_contagem = " . strval($this->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpManutencao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
				}
			} else {
				$this->nu_tpManutencao->ViewValue = NULL;
			}
			$this->nu_tpManutencao->ViewCustomAttributes = "";

			// nu_tpElemento
			if (strval($this->nu_tpElemento->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpElemento]" . ew_SearchString("=", $this->nu_tpElemento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpElemento], [no_tpElemento] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpElemento]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpElemento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_tpElemento] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpElemento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpElemento->ViewValue = $this->nu_tpElemento->CurrentValue;
				}
			} else {
				$this->nu_tpElemento->ViewValue = NULL;
			}
			$this->nu_tpElemento->ViewCustomAttributes = "";

			// qt_alr
			$this->qt_alr->ViewValue = $this->qt_alr->CurrentValue;
			$this->qt_alr->ViewValue = ew_FormatNumber($this->qt_alr->ViewValue, 0, 0, 0, 0);
			$this->qt_alr->ViewCustomAttributes = "";

			// qt_der
			$this->qt_der->ViewValue = $this->qt_der->CurrentValue;
			$this->qt_der->ViewValue = ew_FormatNumber($this->qt_der->ViewValue, 0, 0, 0, 0);
			$this->qt_der->ViewCustomAttributes = "";

			// ic_complexApf
			$this->ic_complexApf->ViewValue = $this->ic_complexApf->CurrentValue;
			$this->ic_complexApf->ViewCustomAttributes = "";

			// vr_contribuicao
			$this->vr_contribuicao->ViewValue = $this->vr_contribuicao->CurrentValue;
			$this->vr_contribuicao->ViewValue = ew_FormatNumber($this->vr_contribuicao->ViewValue, 0, 0, 0, 0);
			$this->vr_contribuicao->ViewCustomAttributes = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->ViewValue = $this->vr_fatorReducao->CurrentValue;
			$this->vr_fatorReducao->ViewCustomAttributes = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
			$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

			// vr_qtPf
			$this->vr_qtPf->ViewValue = $this->vr_qtPf->CurrentValue;
			$this->vr_qtPf->ViewCustomAttributes = "";

			// ic_analalogia
			if (strval($this->ic_analalogia->CurrentValue) <> "") {
				switch ($this->ic_analalogia->CurrentValue) {
					case $this->ic_analalogia->FldTagValue(1):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(1) <> "" ? $this->ic_analalogia->FldTagCaption(1) : $this->ic_analalogia->CurrentValue;
						break;
					case $this->ic_analalogia->FldTagValue(2):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(2) <> "" ? $this->ic_analalogia->FldTagCaption(2) : $this->ic_analalogia->CurrentValue;
						break;
					default:
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->CurrentValue;
				}
			} else {
				$this->ic_analalogia->ViewValue = NULL;
			}
			$this->ic_analalogia->ViewCustomAttributes = "";

			// nu_agrupador
			$this->nu_agrupador->LinkCustomAttributes = "";
			$this->nu_agrupador->HrefValue = "";
			$this->nu_agrupador->TooltipValue = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// no_funcao
			$this->no_funcao->LinkCustomAttributes = "";
			$this->no_funcao->HrefValue = "";
			$this->no_funcao->TooltipValue = "";

			// nu_tpManutencao
			$this->nu_tpManutencao->LinkCustomAttributes = "";
			$this->nu_tpManutencao->HrefValue = "";
			$this->nu_tpManutencao->TooltipValue = "";

			// nu_tpElemento
			$this->nu_tpElemento->LinkCustomAttributes = "";
			$this->nu_tpElemento->HrefValue = "";
			$this->nu_tpElemento->TooltipValue = "";

			// qt_alr
			$this->qt_alr->LinkCustomAttributes = "";
			$this->qt_alr->HrefValue = "";
			$this->qt_alr->TooltipValue = "";

			// qt_der
			$this->qt_der->LinkCustomAttributes = "";
			$this->qt_der->HrefValue = "";
			$this->qt_der->TooltipValue = "";

			// ic_complexApf
			$this->ic_complexApf->LinkCustomAttributes = "";
			$this->ic_complexApf->HrefValue = "";
			$this->ic_complexApf->TooltipValue = "";

			// vr_contribuicao
			$this->vr_contribuicao->LinkCustomAttributes = "";
			$this->vr_contribuicao->HrefValue = "";
			$this->vr_contribuicao->TooltipValue = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->LinkCustomAttributes = "";
			$this->vr_fatorReducao->HrefValue = "";
			$this->vr_fatorReducao->TooltipValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
			$this->pc_varFasesRoteiro->HrefValue = "";
			$this->pc_varFasesRoteiro->TooltipValue = "";

			// vr_qtPf
			$this->vr_qtPf->LinkCustomAttributes = "";
			$this->vr_qtPf->HrefValue = "";
			$this->vr_qtPf->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
		$item->Body = "<a id=\"emf_contagempf_funcao\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_contagempf_funcao',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fcontagempf_funcaolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "contagempf") {
			global $contagempf;
			$rsmaster = $contagempf->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$contagempf->ExportDocument($ExportDoc, $rsmaster, 1, 1);
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
			if ($sMasterTblVar == "contagempf") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_contagem"] <> "") {
					$GLOBALS["contagempf"]->nu_contagem->setQueryStringValue($_GET["nu_contagem"]);
					$this->nu_contagem->setQueryStringValue($GLOBALS["contagempf"]->nu_contagem->QueryStringValue);
					$this->nu_contagem->setSessionValue($this->nu_contagem->QueryStringValue);
					if (!is_numeric($GLOBALS["contagempf"]->nu_contagem->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "contagempf") {
				if ($this->nu_contagem->QueryStringValue == "") $this->nu_contagem->setSessionValue("");
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
if (!isset($contagempf_funcao_list)) $contagempf_funcao_list = new ccontagempf_funcao_list();

// Page init
$contagempf_funcao_list->Page_Init();

// Page main
$contagempf_funcao_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_funcao_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($contagempf_funcao->Export == "") { ?>
<script type="text/javascript">

// Page object
var contagempf_funcao_list = new ew_Page("contagempf_funcao_list");
contagempf_funcao_list.PageID = "list"; // Page ID
var EW_PAGE_ID = contagempf_funcao_list.PageID; // For backward compatibility

// Form object
var fcontagempf_funcaolist = new ew_Form("fcontagempf_funcaolist");
fcontagempf_funcaolist.FormKeyCountName = '<?php echo $contagempf_funcao_list->FormKeyCountName ?>';

// Form_CustomValidate event
fcontagempf_funcaolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempf_funcaolist.ValidateRequired = true;
<?php } else { ?>
fcontagempf_funcaolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontagempf_funcaolist.Lists["x_nu_agrupador"] = {"LinkField":"x_nu_agrupador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_agrupador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaolist.Lists["x_nu_uc"] = {"LinkField":"x_nu_uc","Ajax":true,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_uc","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaolist.Lists["x_nu_tpManutencao"] = {"LinkField":"x_nu_tpManutencao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpManutencao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaolist.Lists["x_nu_tpElemento"] = {"LinkField":"x_nu_tpElemento","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpElemento","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($contagempf_funcao->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($contagempf_funcao->getCurrentMasterTable() == "" && $contagempf_funcao_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $contagempf_funcao_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($contagempf_funcao->Export == "") || (EW_EXPORT_MASTER_RECORD && $contagempf_funcao->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "contagempflist.php";
if ($contagempf_funcao_list->DbMasterFilter <> "" && $contagempf_funcao->getCurrentMasterTable() == "contagempf") {
	if ($contagempf_funcao_list->MasterRecordExists) {
		if ($contagempf_funcao->getCurrentMasterTable() == $contagempf_funcao->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($contagempf_funcao_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $contagempf_funcao_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "contagempfmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$contagempf_funcao_list->TotalRecs = $contagempf_funcao->SelectRecordCount();
	} else {
		if ($contagempf_funcao_list->Recordset = $contagempf_funcao_list->LoadRecordset())
			$contagempf_funcao_list->TotalRecs = $contagempf_funcao_list->Recordset->RecordCount();
	}
	$contagempf_funcao_list->StartRec = 1;
	if ($contagempf_funcao_list->DisplayRecs <= 0 || ($contagempf_funcao->Export <> "" && $contagempf_funcao->ExportAll)) // Display all records
		$contagempf_funcao_list->DisplayRecs = $contagempf_funcao_list->TotalRecs;
	if (!($contagempf_funcao->Export <> "" && $contagempf_funcao->ExportAll))
		$contagempf_funcao_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$contagempf_funcao_list->Recordset = $contagempf_funcao_list->LoadRecordset($contagempf_funcao_list->StartRec-1, $contagempf_funcao_list->DisplayRecs);
$contagempf_funcao_list->RenderOtherOptions();
?>
<?php $contagempf_funcao_list->ShowPageHeader(); ?>
<?php
$contagempf_funcao_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fcontagempf_funcaolist" id="fcontagempf_funcaolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contagempf_funcao">
<div id="gmp_contagempf_funcao" class="ewGridMiddlePanel">
<?php if ($contagempf_funcao_list->TotalRecs > 0) { ?>
<table id="tbl_contagempf_funcaolist" class="ewTable ewTableSeparate">
<?php echo $contagempf_funcao->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$contagempf_funcao_list->RenderListOptions();

// Render list options (header, left)
$contagempf_funcao_list->ListOptions->Render("header", "left");
?>
<?php if ($contagempf_funcao->nu_agrupador->Visible) { // nu_agrupador ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->nu_agrupador) == "") { ?>
		<td><div id="elh_contagempf_funcao_nu_agrupador" class="contagempf_funcao_nu_agrupador"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_agrupador->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->nu_agrupador) ?>',2);"><div id="elh_contagempf_funcao_nu_agrupador" class="contagempf_funcao_nu_agrupador">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_agrupador->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->nu_agrupador->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->nu_agrupador->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->nu_uc->Visible) { // nu_uc ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->nu_uc) == "") { ?>
		<td><div id="elh_contagempf_funcao_nu_uc" class="contagempf_funcao_nu_uc"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_uc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->nu_uc) ?>',2);"><div id="elh_contagempf_funcao_nu_uc" class="contagempf_funcao_nu_uc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_uc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->nu_uc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->nu_uc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->no_funcao->Visible) { // no_funcao ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->no_funcao) == "") { ?>
		<td><div id="elh_contagempf_funcao_no_funcao" class="contagempf_funcao_no_funcao"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->no_funcao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->no_funcao) ?>',2);"><div id="elh_contagempf_funcao_no_funcao" class="contagempf_funcao_no_funcao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->no_funcao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->no_funcao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->no_funcao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->nu_tpManutencao->Visible) { // nu_tpManutencao ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->nu_tpManutencao) == "") { ?>
		<td><div id="elh_contagempf_funcao_nu_tpManutencao" class="contagempf_funcao_nu_tpManutencao"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_tpManutencao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->nu_tpManutencao) ?>',2);"><div id="elh_contagempf_funcao_nu_tpManutencao" class="contagempf_funcao_nu_tpManutencao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_tpManutencao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->nu_tpManutencao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->nu_tpManutencao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->nu_tpElemento->Visible) { // nu_tpElemento ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->nu_tpElemento) == "") { ?>
		<td><div id="elh_contagempf_funcao_nu_tpElemento" class="contagempf_funcao_nu_tpElemento"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_tpElemento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->nu_tpElemento) ?>',2);"><div id="elh_contagempf_funcao_nu_tpElemento" class="contagempf_funcao_nu_tpElemento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->nu_tpElemento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->nu_tpElemento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->nu_tpElemento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->qt_alr->Visible) { // qt_alr ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->qt_alr) == "") { ?>
		<td><div id="elh_contagempf_funcao_qt_alr" class="contagempf_funcao_qt_alr"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->qt_alr->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->qt_alr) ?>',2);"><div id="elh_contagempf_funcao_qt_alr" class="contagempf_funcao_qt_alr">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->qt_alr->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->qt_alr->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->qt_alr->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->qt_der->Visible) { // qt_der ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->qt_der) == "") { ?>
		<td><div id="elh_contagempf_funcao_qt_der" class="contagempf_funcao_qt_der"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->qt_der->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->qt_der) ?>',2);"><div id="elh_contagempf_funcao_qt_der" class="contagempf_funcao_qt_der">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->qt_der->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->qt_der->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->qt_der->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->ic_complexApf->Visible) { // ic_complexApf ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->ic_complexApf) == "") { ?>
		<td><div id="elh_contagempf_funcao_ic_complexApf" class="contagempf_funcao_ic_complexApf"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->ic_complexApf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->ic_complexApf) ?>',2);"><div id="elh_contagempf_funcao_ic_complexApf" class="contagempf_funcao_ic_complexApf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->ic_complexApf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->ic_complexApf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->ic_complexApf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->vr_contribuicao->Visible) { // vr_contribuicao ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->vr_contribuicao) == "") { ?>
		<td><div id="elh_contagempf_funcao_vr_contribuicao" class="contagempf_funcao_vr_contribuicao"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_contribuicao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->vr_contribuicao) ?>',2);"><div id="elh_contagempf_funcao_vr_contribuicao" class="contagempf_funcao_vr_contribuicao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_contribuicao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->vr_contribuicao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->vr_contribuicao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->vr_fatorReducao->Visible) { // vr_fatorReducao ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->vr_fatorReducao) == "") { ?>
		<td><div id="elh_contagempf_funcao_vr_fatorReducao" class="contagempf_funcao_vr_fatorReducao"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_fatorReducao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->vr_fatorReducao) ?>',2);"><div id="elh_contagempf_funcao_vr_fatorReducao" class="contagempf_funcao_vr_fatorReducao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_fatorReducao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->vr_fatorReducao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->vr_fatorReducao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->pc_varFasesRoteiro) == "") { ?>
		<td><div id="elh_contagempf_funcao_pc_varFasesRoteiro" class="contagempf_funcao_pc_varFasesRoteiro"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->pc_varFasesRoteiro->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->pc_varFasesRoteiro) ?>',2);"><div id="elh_contagempf_funcao_pc_varFasesRoteiro" class="contagempf_funcao_pc_varFasesRoteiro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->pc_varFasesRoteiro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->pc_varFasesRoteiro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->pc_varFasesRoteiro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($contagempf_funcao->vr_qtPf->Visible) { // vr_qtPf ?>
	<?php if ($contagempf_funcao->SortUrl($contagempf_funcao->vr_qtPf) == "") { ?>
		<td><div id="elh_contagempf_funcao_vr_qtPf" class="contagempf_funcao_vr_qtPf"><div class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_qtPf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $contagempf_funcao->SortUrl($contagempf_funcao->vr_qtPf) ?>',2);"><div id="elh_contagempf_funcao_vr_qtPf" class="contagempf_funcao_vr_qtPf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $contagempf_funcao->vr_qtPf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($contagempf_funcao->vr_qtPf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($contagempf_funcao->vr_qtPf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$contagempf_funcao_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($contagempf_funcao->ExportAll && $contagempf_funcao->Export <> "") {
	$contagempf_funcao_list->StopRec = $contagempf_funcao_list->TotalRecs;
} else {

	// Set the last record to display
	if ($contagempf_funcao_list->TotalRecs > $contagempf_funcao_list->StartRec + $contagempf_funcao_list->DisplayRecs - 1)
		$contagempf_funcao_list->StopRec = $contagempf_funcao_list->StartRec + $contagempf_funcao_list->DisplayRecs - 1;
	else
		$contagempf_funcao_list->StopRec = $contagempf_funcao_list->TotalRecs;
}
$contagempf_funcao_list->RecCnt = $contagempf_funcao_list->StartRec - 1;
if ($contagempf_funcao_list->Recordset && !$contagempf_funcao_list->Recordset->EOF) {
	$contagempf_funcao_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $contagempf_funcao_list->StartRec > 1)
		$contagempf_funcao_list->Recordset->Move($contagempf_funcao_list->StartRec - 1);
} elseif (!$contagempf_funcao->AllowAddDeleteRow && $contagempf_funcao_list->StopRec == 0) {
	$contagempf_funcao_list->StopRec = $contagempf_funcao->GridAddRowCount;
}

// Initialize aggregate
$contagempf_funcao->RowType = EW_ROWTYPE_AGGREGATEINIT;
$contagempf_funcao->ResetAttrs();
$contagempf_funcao_list->RenderRow();
while ($contagempf_funcao_list->RecCnt < $contagempf_funcao_list->StopRec) {
	$contagempf_funcao_list->RecCnt++;
	if (intval($contagempf_funcao_list->RecCnt) >= intval($contagempf_funcao_list->StartRec)) {
		$contagempf_funcao_list->RowCnt++;

		// Set up key count
		$contagempf_funcao_list->KeyCount = $contagempf_funcao_list->RowIndex;

		// Init row class and style
		$contagempf_funcao->ResetAttrs();
		$contagempf_funcao->CssClass = "";
		if ($contagempf_funcao->CurrentAction == "gridadd") {
		} else {
			$contagempf_funcao_list->LoadRowValues($contagempf_funcao_list->Recordset); // Load row values
		}
		$contagempf_funcao->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$contagempf_funcao->RowAttrs = array_merge($contagempf_funcao->RowAttrs, array('data-rowindex'=>$contagempf_funcao_list->RowCnt, 'id'=>'r' . $contagempf_funcao_list->RowCnt . '_contagempf_funcao', 'data-rowtype'=>$contagempf_funcao->RowType));

		// Render row
		$contagempf_funcao_list->RenderRow();

		// Render list options
		$contagempf_funcao_list->RenderListOptions();
?>
	<tr<?php echo $contagempf_funcao->RowAttributes() ?>>
<?php

// Render list options (body, left)
$contagempf_funcao_list->ListOptions->Render("body", "left", $contagempf_funcao_list->RowCnt);
?>
	<?php if ($contagempf_funcao->nu_agrupador->Visible) { // nu_agrupador ?>
		<td<?php echo $contagempf_funcao->nu_agrupador->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->nu_agrupador->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_agrupador->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->nu_uc->Visible) { // nu_uc ?>
		<td<?php echo $contagempf_funcao->nu_uc->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->nu_uc->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_uc->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->no_funcao->Visible) { // no_funcao ?>
		<td<?php echo $contagempf_funcao->no_funcao->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->no_funcao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->no_funcao->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->nu_tpManutencao->Visible) { // nu_tpManutencao ?>
		<td<?php echo $contagempf_funcao->nu_tpManutencao->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->nu_tpManutencao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpManutencao->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->nu_tpElemento->Visible) { // nu_tpElemento ?>
		<td<?php echo $contagempf_funcao->nu_tpElemento->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->nu_tpElemento->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpElemento->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->qt_alr->Visible) { // qt_alr ?>
		<td<?php echo $contagempf_funcao->qt_alr->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->qt_alr->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_alr->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->qt_der->Visible) { // qt_der ?>
		<td<?php echo $contagempf_funcao->qt_der->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->qt_der->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_der->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->ic_complexApf->Visible) { // ic_complexApf ?>
		<td<?php echo $contagempf_funcao->ic_complexApf->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->ic_complexApf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->ic_complexApf->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->vr_contribuicao->Visible) { // vr_contribuicao ?>
		<td<?php echo $contagempf_funcao->vr_contribuicao->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->vr_contribuicao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_contribuicao->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->vr_fatorReducao->Visible) { // vr_fatorReducao ?>
		<td<?php echo $contagempf_funcao->vr_fatorReducao->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->vr_fatorReducao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_fatorReducao->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
		<td<?php echo $contagempf_funcao->pc_varFasesRoteiro->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->pc_varFasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf_funcao->pc_varFasesRoteiro->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($contagempf_funcao->vr_qtPf->Visible) { // vr_qtPf ?>
		<td<?php echo $contagempf_funcao->vr_qtPf->CellAttributes() ?>>
<span<?php echo $contagempf_funcao->vr_qtPf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_qtPf->ListViewValue() ?></span>
<a id="<?php echo $contagempf_funcao_list->PageObjName . "_row_" . $contagempf_funcao_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$contagempf_funcao_list->ListOptions->Render("body", "right", $contagempf_funcao_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($contagempf_funcao->CurrentAction <> "gridadd")
		$contagempf_funcao_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($contagempf_funcao->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($contagempf_funcao_list->Recordset)
	$contagempf_funcao_list->Recordset->Close();
?>
<?php if ($contagempf_funcao->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($contagempf_funcao->CurrentAction <> "gridadd" && $contagempf_funcao->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($contagempf_funcao_list->Pager)) $contagempf_funcao_list->Pager = new cNumericPager($contagempf_funcao_list->StartRec, $contagempf_funcao_list->DisplayRecs, $contagempf_funcao_list->TotalRecs, $contagempf_funcao_list->RecRange) ?>
<?php if ($contagempf_funcao_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($contagempf_funcao_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_funcao_list->PageUrl() ?>start=<?php echo $contagempf_funcao_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($contagempf_funcao_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_funcao_list->PageUrl() ?>start=<?php echo $contagempf_funcao_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($contagempf_funcao_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $contagempf_funcao_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($contagempf_funcao_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_funcao_list->PageUrl() ?>start=<?php echo $contagempf_funcao_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($contagempf_funcao_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_funcao_list->PageUrl() ?>start=<?php echo $contagempf_funcao_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($contagempf_funcao_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $contagempf_funcao_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $contagempf_funcao_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $contagempf_funcao_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($contagempf_funcao_list->SearchWhere == "0=101") { ?>
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
	foreach ($contagempf_funcao_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($contagempf_funcao->Export == "") { ?>
<script type="text/javascript">
fcontagempf_funcaolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$contagempf_funcao_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($contagempf_funcao->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$contagempf_funcao_list->Page_Terminate();
?>
