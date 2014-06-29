<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ambiente_phistoricoinfo.php" ?>
<?php include_once "ambienteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ambiente_phistorico_list = NULL; // Initialize page object first

class cambiente_phistorico_list extends cambiente_phistorico {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente_phistorico';

	// Page object name
	var $PageObjName = 'ambiente_phistorico_list';

	// Grid form hidden field names
	var $FormName = 'fambiente_phistoricolist';
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

		// Table object (ambiente_phistorico)
		if (!isset($GLOBALS["ambiente_phistorico"])) {
			$GLOBALS["ambiente_phistorico"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ambiente_phistorico"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "ambiente_phistoricoadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "ambiente_phistoricodelete.php";
		$this->MultiUpdateUrl = "ambiente_phistoricoupdate.php";

		// Table object (ambiente)
		if (!isset($GLOBALS['ambiente'])) $GLOBALS['ambiente'] = new cambiente();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente_phistorico', TRUE);

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
		$this->nu_projhist->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "ambiente") {
			global $ambiente;
			$rsmaster = $ambiente->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("ambientelist.php"); // Return to master page
			} else {
				$ambiente->LoadListRowValues($rsmaster);
				$ambiente->RowType = EW_ROWTYPE_MASTER; // Master row
				$ambiente->RenderListRow();
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
			$this->nu_projhist->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_projhist->FormValue))
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
			$this->UpdateSort($this->nu_projhist, $bCtrl); // nu_projhist
			$this->UpdateSort($this->no_projeto, $bCtrl); // no_projeto
			$this->UpdateSort($this->qt_pf, $bCtrl); // qt_pf
			$this->UpdateSort($this->qt_sloc, $bCtrl); // qt_sloc
			$this->UpdateSort($this->qt_slocPf, $bCtrl); // qt_slocPf
			$this->UpdateSort($this->qt_esforcoReal, $bCtrl); // qt_esforcoReal
			$this->UpdateSort($this->qt_esforcoRealPm, $bCtrl); // qt_esforcoRealPm
			$this->UpdateSort($this->qt_prazoRealM, $bCtrl); // qt_prazoRealM
			$this->UpdateSort($this->ic_situacao, $bCtrl); // ic_situacao
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->nu_ambiente->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->nu_projhist->setSort("");
				$this->no_projeto->setSort("");
				$this->qt_pf->setSort("");
				$this->qt_sloc->setSort("");
				$this->qt_slocPf->setSort("");
				$this->qt_esforcoReal->setSort("");
				$this->qt_esforcoRealPm->setSort("");
				$this->qt_prazoRealM->setSort("");
				$this->ic_situacao->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fambiente_phistoricolist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->nu_projhist->setDbValue($rs->fields('nu_projhist'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->no_projeto->setDbValue($rs->fields('no_projeto'));
		$this->ds_projeto->setDbValue($rs->fields('ds_projeto'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_sloc->setDbValue($rs->fields('qt_sloc'));
		$this->qt_slocPf->setDbValue($rs->fields('qt_slocPf'));
		$this->qt_esforcoReal->setDbValue($rs->fields('qt_esforcoReal'));
		$this->qt_esforcoRealPm->setDbValue($rs->fields('qt_esforcoRealPm'));
		$this->qt_prazoRealM->setDbValue($rs->fields('qt_prazoRealM'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->ds_acoes->setDbValue($rs->fields('ds_acoes'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projhist->DbValue = $row['nu_projhist'];
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->no_projeto->DbValue = $row['no_projeto'];
		$this->ds_projeto->DbValue = $row['ds_projeto'];
		$this->qt_pf->DbValue = $row['qt_pf'];
		$this->qt_sloc->DbValue = $row['qt_sloc'];
		$this->qt_slocPf->DbValue = $row['qt_slocPf'];
		$this->qt_esforcoReal->DbValue = $row['qt_esforcoReal'];
		$this->qt_esforcoRealPm->DbValue = $row['qt_esforcoRealPm'];
		$this->qt_prazoRealM->DbValue = $row['qt_prazoRealM'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->ds_acoes->DbValue = $row['ds_acoes'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioAlt->DbValue = $row['nu_usuarioAlt'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_projhist")) <> "")
			$this->nu_projhist->CurrentValue = $this->getKey("nu_projhist"); // nu_projhist
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
		if ($this->qt_sloc->FormValue == $this->qt_sloc->CurrentValue && is_numeric(ew_StrToFloat($this->qt_sloc->CurrentValue)))
			$this->qt_sloc->CurrentValue = ew_StrToFloat($this->qt_sloc->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_slocPf->FormValue == $this->qt_slocPf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_slocPf->CurrentValue)))
			$this->qt_slocPf->CurrentValue = ew_StrToFloat($this->qt_slocPf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforcoReal->FormValue == $this->qt_esforcoReal->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforcoReal->CurrentValue)))
			$this->qt_esforcoReal->CurrentValue = ew_StrToFloat($this->qt_esforcoReal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforcoRealPm->FormValue == $this->qt_esforcoRealPm->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforcoRealPm->CurrentValue)))
			$this->qt_esforcoRealPm->CurrentValue = ew_StrToFloat($this->qt_esforcoRealPm->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_prazoRealM->FormValue == $this->qt_prazoRealM->CurrentValue && is_numeric(ew_StrToFloat($this->qt_prazoRealM->CurrentValue)))
			$this->qt_prazoRealM->CurrentValue = ew_StrToFloat($this->qt_prazoRealM->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_projhist
		// nu_ambiente
		// no_projeto
		// ds_projeto
		// qt_pf
		// qt_sloc
		// qt_slocPf
		// qt_esforcoReal
		// qt_esforcoRealPm
		// qt_prazoRealM
		// ic_situacao
		// ds_acoes
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projhist
			$this->nu_projhist->ViewValue = $this->nu_projhist->CurrentValue;
			$this->nu_projhist->ViewCustomAttributes = "";

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

			// no_projeto
			$this->no_projeto->ViewValue = $this->no_projeto->CurrentValue;
			$this->no_projeto->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
			$this->qt_pf->ViewCustomAttributes = "";

			// qt_sloc
			$this->qt_sloc->ViewValue = $this->qt_sloc->CurrentValue;
			$this->qt_sloc->ViewCustomAttributes = "";

			// qt_slocPf
			$this->qt_slocPf->ViewValue = $this->qt_slocPf->CurrentValue;
			$this->qt_slocPf->ViewCustomAttributes = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->ViewValue = $this->qt_esforcoReal->CurrentValue;
			$this->qt_esforcoReal->ViewCustomAttributes = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->ViewValue = $this->qt_esforcoRealPm->CurrentValue;
			$this->qt_esforcoRealPm->ViewCustomAttributes = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->ViewValue = $this->qt_prazoRealM->CurrentValue;
			$this->qt_prazoRealM->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_usuarioInc
			if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
				}
			} else {
				$this->nu_usuarioInc->ViewValue = NULL;
			}
			$this->nu_usuarioInc->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
			if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlt->ViewValue = NULL;
			}
			$this->nu_usuarioAlt->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 7);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_projhist
			$this->nu_projhist->LinkCustomAttributes = "";
			$this->nu_projhist->HrefValue = "";
			$this->nu_projhist->TooltipValue = "";

			// no_projeto
			$this->no_projeto->LinkCustomAttributes = "";
			$this->no_projeto->HrefValue = "";
			$this->no_projeto->TooltipValue = "";

			// qt_pf
			$this->qt_pf->LinkCustomAttributes = "";
			$this->qt_pf->HrefValue = "";
			$this->qt_pf->TooltipValue = "";

			// qt_sloc
			$this->qt_sloc->LinkCustomAttributes = "";
			$this->qt_sloc->HrefValue = "";
			$this->qt_sloc->TooltipValue = "";

			// qt_slocPf
			$this->qt_slocPf->LinkCustomAttributes = "";
			$this->qt_slocPf->HrefValue = "";
			$this->qt_slocPf->TooltipValue = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->LinkCustomAttributes = "";
			$this->qt_esforcoReal->HrefValue = "";
			$this->qt_esforcoReal->TooltipValue = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->LinkCustomAttributes = "";
			$this->qt_esforcoRealPm->HrefValue = "";
			$this->qt_esforcoRealPm->TooltipValue = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->LinkCustomAttributes = "";
			$this->qt_prazoRealM->HrefValue = "";
			$this->qt_prazoRealM->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_ambiente_phistorico\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_ambiente_phistorico',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fambiente_phistoricolist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "ambiente") {
			global $ambiente;
			$rsmaster = $ambiente->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$ambiente->ExportDocument($ExportDoc, $rsmaster, 1, 1);
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
			if ($sMasterTblVar == "ambiente") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_ambiente"] <> "") {
					$GLOBALS["ambiente"]->nu_ambiente->setQueryStringValue($_GET["nu_ambiente"]);
					$this->nu_ambiente->setQueryStringValue($GLOBALS["ambiente"]->nu_ambiente->QueryStringValue);
					$this->nu_ambiente->setSessionValue($this->nu_ambiente->QueryStringValue);
					if (!is_numeric($GLOBALS["ambiente"]->nu_ambiente->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "ambiente") {
				if ($this->nu_ambiente->QueryStringValue == "") $this->nu_ambiente->setSessionValue("");
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
if (!isset($ambiente_phistorico_list)) $ambiente_phistorico_list = new cambiente_phistorico_list();

// Page init
$ambiente_phistorico_list->Page_Init();

// Page main
$ambiente_phistorico_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_phistorico_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($ambiente_phistorico->Export == "") { ?>
<script type="text/javascript">

// Page object
var ambiente_phistorico_list = new ew_Page("ambiente_phistorico_list");
ambiente_phistorico_list.PageID = "list"; // Page ID
var EW_PAGE_ID = ambiente_phistorico_list.PageID; // For backward compatibility

// Form object
var fambiente_phistoricolist = new ew_Form("fambiente_phistoricolist");
fambiente_phistoricolist.FormKeyCountName = '<?php echo $ambiente_phistorico_list->FormKeyCountName ?>';

// Form_CustomValidate event
fambiente_phistoricolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_phistoricolist.ValidateRequired = true;
<?php } else { ?>
fambiente_phistoricolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($ambiente_phistorico->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($ambiente_phistorico->getCurrentMasterTable() == "" && $ambiente_phistorico_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $ambiente_phistorico_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($ambiente_phistorico->Export == "") || (EW_EXPORT_MASTER_RECORD && $ambiente_phistorico->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "ambientelist.php";
if ($ambiente_phistorico_list->DbMasterFilter <> "" && $ambiente_phistorico->getCurrentMasterTable() == "ambiente") {
	if ($ambiente_phistorico_list->MasterRecordExists) {
		if ($ambiente_phistorico->getCurrentMasterTable() == $ambiente_phistorico->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($ambiente_phistorico_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $ambiente_phistorico_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "ambientemaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$ambiente_phistorico_list->TotalRecs = $ambiente_phistorico->SelectRecordCount();
	} else {
		if ($ambiente_phistorico_list->Recordset = $ambiente_phistorico_list->LoadRecordset())
			$ambiente_phistorico_list->TotalRecs = $ambiente_phistorico_list->Recordset->RecordCount();
	}
	$ambiente_phistorico_list->StartRec = 1;
	if ($ambiente_phistorico_list->DisplayRecs <= 0 || ($ambiente_phistorico->Export <> "" && $ambiente_phistorico->ExportAll)) // Display all records
		$ambiente_phistorico_list->DisplayRecs = $ambiente_phistorico_list->TotalRecs;
	if (!($ambiente_phistorico->Export <> "" && $ambiente_phistorico->ExportAll))
		$ambiente_phistorico_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$ambiente_phistorico_list->Recordset = $ambiente_phistorico_list->LoadRecordset($ambiente_phistorico_list->StartRec-1, $ambiente_phistorico_list->DisplayRecs);
$ambiente_phistorico_list->RenderOtherOptions();
?>
<?php $ambiente_phistorico_list->ShowPageHeader(); ?>
<?php
$ambiente_phistorico_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fambiente_phistoricolist" id="fambiente_phistoricolist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ambiente_phistorico">
<div id="gmp_ambiente_phistorico" class="ewGridMiddlePanel">
<?php if ($ambiente_phistorico_list->TotalRecs > 0) { ?>
<table id="tbl_ambiente_phistoricolist" class="ewTable ewTableSeparate">
<?php echo $ambiente_phistorico->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$ambiente_phistorico_list->RenderListOptions();

// Render list options (header, left)
$ambiente_phistorico_list->ListOptions->Render("header", "left");
?>
<?php if ($ambiente_phistorico->nu_projhist->Visible) { // nu_projhist ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->nu_projhist) == "") { ?>
		<td><div id="elh_ambiente_phistorico_nu_projhist" class="ambiente_phistorico_nu_projhist"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->nu_projhist->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ambiente_phistorico->SortUrl($ambiente_phistorico->nu_projhist) ?>',2);"><div id="elh_ambiente_phistorico_nu_projhist" class="ambiente_phistorico_nu_projhist">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->nu_projhist->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->nu_projhist->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->nu_projhist->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->no_projeto->Visible) { // no_projeto ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->no_projeto) == "") { ?>
		<td><div id="elh_ambiente_phistorico_no_projeto" class="ambiente_phistorico_no_projeto"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->no_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ambiente_phistorico->SortUrl($ambiente_phistorico->no_projeto) ?>',2);"><div id="elh_ambiente_phistorico_no_projeto" class="ambiente_phistorico_no_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->no_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->no_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->no_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_pf->Visible) { // qt_pf ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_pf) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_pf" class="ambiente_phistorico_qt_pf"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_pf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ambiente_phistorico->SortUrl($ambiente_phistorico->qt_pf) ?>',2);"><div id="elh_ambiente_phistorico_qt_pf" class="ambiente_phistorico_qt_pf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_pf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_pf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_pf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_sloc->Visible) { // qt_sloc ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_sloc) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_sloc" class="ambiente_phistorico_qt_sloc"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_sloc->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ambiente_phistorico->SortUrl($ambiente_phistorico->qt_sloc) ?>',2);"><div id="elh_ambiente_phistorico_qt_sloc" class="ambiente_phistorico_qt_sloc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_sloc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_sloc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_sloc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_slocPf->Visible) { // qt_slocPf ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_slocPf) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_slocPf" class="ambiente_phistorico_qt_slocPf"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_slocPf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ambiente_phistorico->SortUrl($ambiente_phistorico->qt_slocPf) ?>',2);"><div id="elh_ambiente_phistorico_qt_slocPf" class="ambiente_phistorico_qt_slocPf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_slocPf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_slocPf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_slocPf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_esforcoReal->Visible) { // qt_esforcoReal ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_esforcoReal) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_esforcoReal" class="ambiente_phistorico_qt_esforcoReal"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_esforcoReal->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ambiente_phistorico->SortUrl($ambiente_phistorico->qt_esforcoReal) ?>',2);"><div id="elh_ambiente_phistorico_qt_esforcoReal" class="ambiente_phistorico_qt_esforcoReal">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_esforcoReal->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_esforcoReal->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_esforcoReal->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_esforcoRealPm->Visible) { // qt_esforcoRealPm ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_esforcoRealPm) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_esforcoRealPm" class="ambiente_phistorico_qt_esforcoRealPm"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_esforcoRealPm->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ambiente_phistorico->SortUrl($ambiente_phistorico->qt_esforcoRealPm) ?>',2);"><div id="elh_ambiente_phistorico_qt_esforcoRealPm" class="ambiente_phistorico_qt_esforcoRealPm">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_esforcoRealPm->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_esforcoRealPm->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_esforcoRealPm->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->qt_prazoRealM->Visible) { // qt_prazoRealM ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->qt_prazoRealM) == "") { ?>
		<td><div id="elh_ambiente_phistorico_qt_prazoRealM" class="ambiente_phistorico_qt_prazoRealM"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_prazoRealM->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ambiente_phistorico->SortUrl($ambiente_phistorico->qt_prazoRealM) ?>',2);"><div id="elh_ambiente_phistorico_qt_prazoRealM" class="ambiente_phistorico_qt_prazoRealM">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->qt_prazoRealM->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->qt_prazoRealM->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->qt_prazoRealM->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($ambiente_phistorico->ic_situacao->Visible) { // ic_situacao ?>
	<?php if ($ambiente_phistorico->SortUrl($ambiente_phistorico->ic_situacao) == "") { ?>
		<td><div id="elh_ambiente_phistorico_ic_situacao" class="ambiente_phistorico_ic_situacao"><div class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->ic_situacao->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $ambiente_phistorico->SortUrl($ambiente_phistorico->ic_situacao) ?>',2);"><div id="elh_ambiente_phistorico_ic_situacao" class="ambiente_phistorico_ic_situacao">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $ambiente_phistorico->ic_situacao->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($ambiente_phistorico->ic_situacao->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($ambiente_phistorico->ic_situacao->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$ambiente_phistorico_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($ambiente_phistorico->ExportAll && $ambiente_phistorico->Export <> "") {
	$ambiente_phistorico_list->StopRec = $ambiente_phistorico_list->TotalRecs;
} else {

	// Set the last record to display
	if ($ambiente_phistorico_list->TotalRecs > $ambiente_phistorico_list->StartRec + $ambiente_phistorico_list->DisplayRecs - 1)
		$ambiente_phistorico_list->StopRec = $ambiente_phistorico_list->StartRec + $ambiente_phistorico_list->DisplayRecs - 1;
	else
		$ambiente_phistorico_list->StopRec = $ambiente_phistorico_list->TotalRecs;
}
$ambiente_phistorico_list->RecCnt = $ambiente_phistorico_list->StartRec - 1;
if ($ambiente_phistorico_list->Recordset && !$ambiente_phistorico_list->Recordset->EOF) {
	$ambiente_phistorico_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $ambiente_phistorico_list->StartRec > 1)
		$ambiente_phistorico_list->Recordset->Move($ambiente_phistorico_list->StartRec - 1);
} elseif (!$ambiente_phistorico->AllowAddDeleteRow && $ambiente_phistorico_list->StopRec == 0) {
	$ambiente_phistorico_list->StopRec = $ambiente_phistorico->GridAddRowCount;
}

// Initialize aggregate
$ambiente_phistorico->RowType = EW_ROWTYPE_AGGREGATEINIT;
$ambiente_phistorico->ResetAttrs();
$ambiente_phistorico_list->RenderRow();
while ($ambiente_phistorico_list->RecCnt < $ambiente_phistorico_list->StopRec) {
	$ambiente_phistorico_list->RecCnt++;
	if (intval($ambiente_phistorico_list->RecCnt) >= intval($ambiente_phistorico_list->StartRec)) {
		$ambiente_phistorico_list->RowCnt++;

		// Set up key count
		$ambiente_phistorico_list->KeyCount = $ambiente_phistorico_list->RowIndex;

		// Init row class and style
		$ambiente_phistorico->ResetAttrs();
		$ambiente_phistorico->CssClass = "";
		if ($ambiente_phistorico->CurrentAction == "gridadd") {
		} else {
			$ambiente_phistorico_list->LoadRowValues($ambiente_phistorico_list->Recordset); // Load row values
		}
		$ambiente_phistorico->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$ambiente_phistorico->RowAttrs = array_merge($ambiente_phistorico->RowAttrs, array('data-rowindex'=>$ambiente_phistorico_list->RowCnt, 'id'=>'r' . $ambiente_phistorico_list->RowCnt . '_ambiente_phistorico', 'data-rowtype'=>$ambiente_phistorico->RowType));

		// Render row
		$ambiente_phistorico_list->RenderRow();

		// Render list options
		$ambiente_phistorico_list->RenderListOptions();
?>
	<tr<?php echo $ambiente_phistorico->RowAttributes() ?>>
<?php

// Render list options (body, left)
$ambiente_phistorico_list->ListOptions->Render("body", "left", $ambiente_phistorico_list->RowCnt);
?>
	<?php if ($ambiente_phistorico->nu_projhist->Visible) { // nu_projhist ?>
		<td<?php echo $ambiente_phistorico->nu_projhist->CellAttributes() ?>>
<span<?php echo $ambiente_phistorico->nu_projhist->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->nu_projhist->ListViewValue() ?></span>
<a id="<?php echo $ambiente_phistorico_list->PageObjName . "_row_" . $ambiente_phistorico_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->no_projeto->Visible) { // no_projeto ?>
		<td<?php echo $ambiente_phistorico->no_projeto->CellAttributes() ?>>
<span<?php echo $ambiente_phistorico->no_projeto->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->no_projeto->ListViewValue() ?></span>
<a id="<?php echo $ambiente_phistorico_list->PageObjName . "_row_" . $ambiente_phistorico_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_pf->Visible) { // qt_pf ?>
		<td<?php echo $ambiente_phistorico->qt_pf->CellAttributes() ?>>
<span<?php echo $ambiente_phistorico->qt_pf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_pf->ListViewValue() ?></span>
<a id="<?php echo $ambiente_phistorico_list->PageObjName . "_row_" . $ambiente_phistorico_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_sloc->Visible) { // qt_sloc ?>
		<td<?php echo $ambiente_phistorico->qt_sloc->CellAttributes() ?>>
<span<?php echo $ambiente_phistorico->qt_sloc->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_sloc->ListViewValue() ?></span>
<a id="<?php echo $ambiente_phistorico_list->PageObjName . "_row_" . $ambiente_phistorico_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_slocPf->Visible) { // qt_slocPf ?>
		<td<?php echo $ambiente_phistorico->qt_slocPf->CellAttributes() ?>>
<span<?php echo $ambiente_phistorico->qt_slocPf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_slocPf->ListViewValue() ?></span>
<a id="<?php echo $ambiente_phistorico_list->PageObjName . "_row_" . $ambiente_phistorico_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_esforcoReal->Visible) { // qt_esforcoReal ?>
		<td<?php echo $ambiente_phistorico->qt_esforcoReal->CellAttributes() ?>>
<span<?php echo $ambiente_phistorico->qt_esforcoReal->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoReal->ListViewValue() ?></span>
<a id="<?php echo $ambiente_phistorico_list->PageObjName . "_row_" . $ambiente_phistorico_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_esforcoRealPm->Visible) { // qt_esforcoRealPm ?>
		<td<?php echo $ambiente_phistorico->qt_esforcoRealPm->CellAttributes() ?>>
<span<?php echo $ambiente_phistorico->qt_esforcoRealPm->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoRealPm->ListViewValue() ?></span>
<a id="<?php echo $ambiente_phistorico_list->PageObjName . "_row_" . $ambiente_phistorico_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->qt_prazoRealM->Visible) { // qt_prazoRealM ?>
		<td<?php echo $ambiente_phistorico->qt_prazoRealM->CellAttributes() ?>>
<span<?php echo $ambiente_phistorico->qt_prazoRealM->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_prazoRealM->ListViewValue() ?></span>
<a id="<?php echo $ambiente_phistorico_list->PageObjName . "_row_" . $ambiente_phistorico_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($ambiente_phistorico->ic_situacao->Visible) { // ic_situacao ?>
		<td<?php echo $ambiente_phistorico->ic_situacao->CellAttributes() ?>>
<span<?php echo $ambiente_phistorico->ic_situacao->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->ic_situacao->ListViewValue() ?></span>
<a id="<?php echo $ambiente_phistorico_list->PageObjName . "_row_" . $ambiente_phistorico_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$ambiente_phistorico_list->ListOptions->Render("body", "right", $ambiente_phistorico_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($ambiente_phistorico->CurrentAction <> "gridadd")
		$ambiente_phistorico_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($ambiente_phistorico->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($ambiente_phistorico_list->Recordset)
	$ambiente_phistorico_list->Recordset->Close();
?>
<?php if ($ambiente_phistorico->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($ambiente_phistorico->CurrentAction <> "gridadd" && $ambiente_phistorico->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($ambiente_phistorico_list->Pager)) $ambiente_phistorico_list->Pager = new cNumericPager($ambiente_phistorico_list->StartRec, $ambiente_phistorico_list->DisplayRecs, $ambiente_phistorico_list->TotalRecs, $ambiente_phistorico_list->RecRange) ?>
<?php if ($ambiente_phistorico_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($ambiente_phistorico_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_phistorico_list->PageUrl() ?>start=<?php echo $ambiente_phistorico_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($ambiente_phistorico_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_phistorico_list->PageUrl() ?>start=<?php echo $ambiente_phistorico_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($ambiente_phistorico_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $ambiente_phistorico_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($ambiente_phistorico_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_phistorico_list->PageUrl() ?>start=<?php echo $ambiente_phistorico_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($ambiente_phistorico_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_phistorico_list->PageUrl() ?>start=<?php echo $ambiente_phistorico_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($ambiente_phistorico_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $ambiente_phistorico_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $ambiente_phistorico_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $ambiente_phistorico_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($ambiente_phistorico_list->SearchWhere == "0=101") { ?>
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
	foreach ($ambiente_phistorico_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($ambiente_phistorico->Export == "") { ?>
<script type="text/javascript">
fambiente_phistoricolist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$ambiente_phistorico_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($ambiente_phistorico->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$ambiente_phistorico_list->Page_Terminate();
?>
