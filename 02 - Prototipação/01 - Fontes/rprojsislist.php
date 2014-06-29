<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "rprojsisinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$rprojsis_list = NULL; // Initialize page object first

class crprojsis_list extends crprojsis {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{0602B820-DE72-4661-BB21-3716ACE9CB5F}";

	// Table name
	var $TableName = 'rprojsis';

	// Page object name
	var $PageObjName = 'rprojsis_list';

	// Grid form hidden field names
	var $FormName = 'frprojsislist';
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

		// Table object (rprojsis)
		if (!isset($GLOBALS["rprojsis"])) {
			$GLOBALS["rprojsis"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["rprojsis"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "rprojsisadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "rprojsisdelete.php";
		$this->MultiUpdateUrl = "rprojsisupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rprojsis', TRUE);

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

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->nu_contrato, $bCtrl); // nu_contrato
			$this->UpdateSort($this->nu_itemContrato, $bCtrl); // nu_itemContrato
			$this->UpdateSort($this->nu_ambiente, $bCtrl); // nu_ambiente
			$this->UpdateSort($this->nu_metodologia, $bCtrl); // nu_metodologia
			$this->UpdateSort($this->nu_sistema, $bCtrl); // nu_sistema
			$this->UpdateSort($this->ic_ativo, $bCtrl); // ic_ativo
			$this->UpdateSort($this->nu_stSistema, $bCtrl); // nu_stSistema
			$this->UpdateSort($this->nu_tpProjeto, $bCtrl); // nu_tpProjeto
			$this->UpdateSort($this->nu_projeto, $bCtrl); // nu_projeto
			$this->UpdateSort($this->nu_projetoInteg, $bCtrl); // nu_projetoInteg
			$this->UpdateSort($this->ic_passivelContPf, $bCtrl); // ic_passivelContPf
			$this->UpdateSort($this->id_tarefaTpProj, $bCtrl); // id_tarefaTpProj
			$this->UpdateSort($this->status_id, $bCtrl); // status_id
			$this->UpdateSort($this->start_date, $bCtrl); // start_date
			$this->UpdateSort($this->due_date, $bCtrl); // due_date
			$this->UpdateSort($this->assigned_to, $bCtrl); // assigned_to
			$this->UpdateSort($this->ic_stContagem, $bCtrl); // ic_stContagem
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->nu_contrato->setSort("");
				$this->nu_itemContrato->setSort("");
				$this->nu_ambiente->setSort("");
				$this->nu_metodologia->setSort("");
				$this->nu_sistema->setSort("");
				$this->ic_ativo->setSort("");
				$this->nu_stSistema->setSort("");
				$this->nu_tpProjeto->setSort("");
				$this->nu_projeto->setSort("");
				$this->nu_projetoInteg->setSort("");
				$this->ic_passivelContPf->setSort("");
				$this->id_tarefaTpProj->setSort("");
				$this->status_id->setSort("");
				$this->start_date->setSort("");
				$this->due_date->setSort("");
				$this->assigned_to->setSort("");
				$this->ic_stContagem->setSort("");
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
			$option->UseDropDownButton = FALSE;
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.frprojsislist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemContrato->setDbValue($rs->fields('nu_itemContrato'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->nu_metodologia->setDbValue($rs->fields('nu_metodologia'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->nu_stSistema->setDbValue($rs->fields('nu_stSistema'));
		$this->nu_tpProjeto->setDbValue($rs->fields('nu_tpProjeto'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_projetoInteg->setDbValue($rs->fields('nu_projetoInteg'));
		$this->ic_passivelContPf->setDbValue($rs->fields('ic_passivelContPf'));
		$this->id_tarefaTpProj->setDbValue($rs->fields('id_tarefaTpProj'));
		$this->status_id->setDbValue($rs->fields('status_id'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->due_date->setDbValue($rs->fields('due_date'));
		$this->assigned_to->setDbValue($rs->fields('assigned_to'));
		$this->ic_stContagem->setDbValue($rs->fields('ic_stContagem'));
		$this->vr_pfFaturamento->setDbValue($rs->fields('vr_pfFaturamento'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->nu_itemContrato->DbValue = $row['nu_itemContrato'];
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->nu_metodologia->DbValue = $row['nu_metodologia'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->nu_stSistema->DbValue = $row['nu_stSistema'];
		$this->nu_tpProjeto->DbValue = $row['nu_tpProjeto'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_projetoInteg->DbValue = $row['nu_projetoInteg'];
		$this->ic_passivelContPf->DbValue = $row['ic_passivelContPf'];
		$this->id_tarefaTpProj->DbValue = $row['id_tarefaTpProj'];
		$this->status_id->DbValue = $row['status_id'];
		$this->start_date->DbValue = $row['start_date'];
		$this->due_date->DbValue = $row['due_date'];
		$this->assigned_to->DbValue = $row['assigned_to'];
		$this->ic_stContagem->DbValue = $row['ic_stContagem'];
		$this->vr_pfFaturamento->DbValue = $row['vr_pfFaturamento'];
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
		if ($this->vr_pfFaturamento->FormValue == $this->vr_pfFaturamento->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pfFaturamento->CurrentValue)))
			$this->vr_pfFaturamento->CurrentValue = ew_StrToFloat($this->vr_pfFaturamento->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_contrato
		// nu_itemContrato
		// nu_ambiente
		// nu_metodologia
		// nu_sistema
		// ic_ativo
		// nu_stSistema
		// nu_tpProjeto
		// nu_projeto
		// nu_projetoInteg
		// ic_passivelContPf
		// id_tarefaTpProj
		// status_id
		// start_date
		// due_date
		// assigned_to
		// ic_stContagem
		// vr_pfFaturamento

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_contrato
			if (strval($this->nu_contrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contrato->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
				}
			} else {
				$this->nu_contrato->ViewValue = NULL;
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemContrato
			if (strval($this->nu_itemContrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemContratado], [no_itemContratado] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemContrato->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->CurrentValue;
				}
			} else {
				$this->nu_itemContrato->ViewValue = NULL;
			}
			$this->nu_itemContrato->ViewCustomAttributes = "";

			// nu_ambiente
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [no_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ambiente]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metodologia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
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

			// nu_stSistema
			if (strval($this->nu_stSistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stSistema]" . ew_SearchString("=", $this->nu_stSistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stSistema], [no_stSistema] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stsistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stSistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stSistema->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stSistema->ViewValue = $this->nu_stSistema->CurrentValue;
				}
			} else {
				$this->nu_stSistema->ViewValue = NULL;
			}
			$this->nu_stSistema->ViewCustomAttributes = "";

			// nu_tpProjeto
			if (strval($this->nu_tpProjeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpProjeto]" . ew_SearchString("=", $this->nu_tpProjeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpProjeto], [no_tpProjeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpprojeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpProjeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpProjeto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpProjeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpProjeto->ViewValue = $this->nu_tpProjeto->CurrentValue;
				}
			} else {
				$this->nu_tpProjeto->ViewValue = NULL;
			}
			$this->nu_tpProjeto->ViewCustomAttributes = "";

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

			// nu_projetoInteg
			if (strval($this->nu_projetoInteg->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_projetoInteg->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projetoInteg, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projetoInteg->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projetoInteg->ViewValue = $this->nu_projetoInteg->CurrentValue;
				}
			} else {
				$this->nu_projetoInteg->ViewValue = NULL;
			}
			$this->nu_projetoInteg->ViewCustomAttributes = "";

			// ic_passivelContPf
			$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->CurrentValue;
			$this->ic_passivelContPf->ViewCustomAttributes = "";

			// id_tarefaTpProj
			if (strval($this->id_tarefaTpProj->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_tarefaTpProj->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [subject] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_tarefa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tarefaTpProj, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tarefaTpProj->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
				}
			} else {
				$this->id_tarefaTpProj->ViewValue = NULL;
			}
			$this->id_tarefaTpProj->ViewCustomAttributes = "";

			// status_id
			if (strval($this->status_id->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->status_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_sttarefa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->status_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->status_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->status_id->ViewValue = $this->status_id->CurrentValue;
				}
			} else {
				$this->status_id->ViewValue = NULL;
			}
			$this->status_id->ViewCustomAttributes = "";

			// start_date
			$this->start_date->ViewValue = $this->start_date->CurrentValue;
			$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 7);
			$this->start_date->ViewCustomAttributes = "";

			// due_date
			$this->due_date->ViewValue = $this->due_date->CurrentValue;
			$this->due_date->ViewValue = ew_FormatDateTime($this->due_date->ViewValue, 7);
			$this->due_date->ViewCustomAttributes = "";

			// assigned_to
			$this->assigned_to->ViewValue = $this->assigned_to->CurrentValue;
			if (strval($this->assigned_to->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->assigned_to->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->assigned_to, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->assigned_to->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->assigned_to->ViewValue = $this->assigned_to->CurrentValue;
				}
			} else {
				$this->assigned_to->ViewValue = NULL;
			}
			$this->assigned_to->ViewCustomAttributes = "";

			// ic_stContagem
			$this->ic_stContagem->ViewValue = $this->ic_stContagem->CurrentValue;
			$this->ic_stContagem->ViewCustomAttributes = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->ViewValue = $this->vr_pfFaturamento->CurrentValue;
			$this->vr_pfFaturamento->ViewCustomAttributes = "";

			// nu_contrato
			$this->nu_contrato->LinkCustomAttributes = "";
			$this->nu_contrato->HrefValue = "";
			$this->nu_contrato->TooltipValue = "";

			// nu_itemContrato
			$this->nu_itemContrato->LinkCustomAttributes = "";
			$this->nu_itemContrato->HrefValue = "";
			$this->nu_itemContrato->TooltipValue = "";

			// nu_ambiente
			$this->nu_ambiente->LinkCustomAttributes = "";
			$this->nu_ambiente->HrefValue = "";
			$this->nu_ambiente->TooltipValue = "";

			// nu_metodologia
			$this->nu_metodologia->LinkCustomAttributes = "";
			$this->nu_metodologia->HrefValue = "";
			$this->nu_metodologia->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// nu_stSistema
			$this->nu_stSistema->LinkCustomAttributes = "";
			$this->nu_stSistema->HrefValue = "";
			$this->nu_stSistema->TooltipValue = "";

			// nu_tpProjeto
			$this->nu_tpProjeto->LinkCustomAttributes = "";
			$this->nu_tpProjeto->HrefValue = "";
			$this->nu_tpProjeto->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_projetoInteg
			$this->nu_projetoInteg->LinkCustomAttributes = "";
			$this->nu_projetoInteg->HrefValue = "";
			$this->nu_projetoInteg->TooltipValue = "";

			// ic_passivelContPf
			$this->ic_passivelContPf->LinkCustomAttributes = "";
			$this->ic_passivelContPf->HrefValue = "";
			$this->ic_passivelContPf->TooltipValue = "";

			// id_tarefaTpProj
			$this->id_tarefaTpProj->LinkCustomAttributes = "";
			$this->id_tarefaTpProj->HrefValue = "";
			$this->id_tarefaTpProj->TooltipValue = "";

			// status_id
			$this->status_id->LinkCustomAttributes = "";
			$this->status_id->HrefValue = "";
			$this->status_id->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// due_date
			$this->due_date->LinkCustomAttributes = "";
			$this->due_date->HrefValue = "";
			$this->due_date->TooltipValue = "";

			// assigned_to
			$this->assigned_to->LinkCustomAttributes = "";
			$this->assigned_to->HrefValue = "";
			$this->assigned_to->TooltipValue = "";

			// ic_stContagem
			$this->ic_stContagem->LinkCustomAttributes = "";
			$this->ic_stContagem->HrefValue = "";
			$this->ic_stContagem->TooltipValue = "";

			// vr_pfFaturamento
			$this->vr_pfFaturamento->LinkCustomAttributes = "";
			$this->vr_pfFaturamento->HrefValue = "";
			$this->vr_pfFaturamento->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_rprojsis\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_rprojsis',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.frprojsislist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($rprojsis_list)) $rprojsis_list = new crprojsis_list();

// Page init
$rprojsis_list->Page_Init();

// Page main
$rprojsis_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$rprojsis_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($rprojsis->Export == "") { ?>
<script type="text/javascript">

// Page object
var rprojsis_list = new ew_Page("rprojsis_list");
rprojsis_list.PageID = "list"; // Page ID
var EW_PAGE_ID = rprojsis_list.PageID; // For backward compatibility

// Form object
var frprojsislist = new ew_Form("frprojsislist");
frprojsislist.FormKeyCountName = '<?php echo $rprojsis_list->FormKeyCountName ?>';

// Form_CustomValidate event
frprojsislist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frprojsislist.ValidateRequired = true;
<?php } else { ?>
frprojsislist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frprojsislist.Lists["x_nu_contrato"] = {"LinkField":"x_nu_contrato","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_contrato","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_nu_itemContrato"] = {"LinkField":"x_nu_itemContratado","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_itemContratado","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_nu_ambiente"] = {"LinkField":"x_nu_ambiente","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_nu_metodologia"] = {"LinkField":"x_nu_metodologia","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metodologia","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_nu_stSistema"] = {"LinkField":"x_nu_stSistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stSistema","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_nu_tpProjeto"] = {"LinkField":"x_nu_tpProjeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpProjeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_nu_projetoInteg"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_id_tarefaTpProj"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_subject","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_status_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frprojsislist.Lists["x_assigned_to"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($rprojsis->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($rprojsis_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $rprojsis_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$rprojsis_list->TotalRecs = $rprojsis->SelectRecordCount();
	} else {
		if ($rprojsis_list->Recordset = $rprojsis_list->LoadRecordset())
			$rprojsis_list->TotalRecs = $rprojsis_list->Recordset->RecordCount();
	}
	$rprojsis_list->StartRec = 1;
	if ($rprojsis_list->DisplayRecs <= 0 || ($rprojsis->Export <> "" && $rprojsis->ExportAll)) // Display all records
		$rprojsis_list->DisplayRecs = $rprojsis_list->TotalRecs;
	if (!($rprojsis->Export <> "" && $rprojsis->ExportAll))
		$rprojsis_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$rprojsis_list->Recordset = $rprojsis_list->LoadRecordset($rprojsis_list->StartRec-1, $rprojsis_list->DisplayRecs);
$rprojsis_list->RenderOtherOptions();
?>
<?php $rprojsis_list->ShowPageHeader(); ?>
<?php
$rprojsis_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="frprojsislist" id="frprojsislist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="rprojsis">
<div id="gmp_rprojsis" class="ewGridMiddlePanel">
<?php if ($rprojsis_list->TotalRecs > 0) { ?>
<table id="tbl_rprojsislist" class="ewTable ewTableSeparate">
<?php echo $rprojsis->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$rprojsis_list->RenderListOptions();

// Render list options (header, left)
$rprojsis_list->ListOptions->Render("header", "left");
?>
<?php if ($rprojsis->nu_contrato->Visible) { // nu_contrato ?>
	<?php if ($rprojsis->SortUrl($rprojsis->nu_contrato) == "") { ?>
		<td><div id="elh_rprojsis_nu_contrato" class="rprojsis_nu_contrato"><div class="ewTableHeaderCaption"><?php echo $rprojsis->nu_contrato->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->nu_contrato) ?>',2);"><div id="elh_rprojsis_nu_contrato" class="rprojsis_nu_contrato">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->nu_contrato->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->nu_contrato->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->nu_contrato->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->nu_itemContrato->Visible) { // nu_itemContrato ?>
	<?php if ($rprojsis->SortUrl($rprojsis->nu_itemContrato) == "") { ?>
		<td><div id="elh_rprojsis_nu_itemContrato" class="rprojsis_nu_itemContrato"><div class="ewTableHeaderCaption"><?php echo $rprojsis->nu_itemContrato->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->nu_itemContrato) ?>',2);"><div id="elh_rprojsis_nu_itemContrato" class="rprojsis_nu_itemContrato">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->nu_itemContrato->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->nu_itemContrato->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->nu_itemContrato->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->nu_ambiente->Visible) { // nu_ambiente ?>
	<?php if ($rprojsis->SortUrl($rprojsis->nu_ambiente) == "") { ?>
		<td><div id="elh_rprojsis_nu_ambiente" class="rprojsis_nu_ambiente"><div class="ewTableHeaderCaption"><?php echo $rprojsis->nu_ambiente->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->nu_ambiente) ?>',2);"><div id="elh_rprojsis_nu_ambiente" class="rprojsis_nu_ambiente">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->nu_ambiente->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->nu_ambiente->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->nu_ambiente->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->nu_metodologia->Visible) { // nu_metodologia ?>
	<?php if ($rprojsis->SortUrl($rprojsis->nu_metodologia) == "") { ?>
		<td><div id="elh_rprojsis_nu_metodologia" class="rprojsis_nu_metodologia"><div class="ewTableHeaderCaption"><?php echo $rprojsis->nu_metodologia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->nu_metodologia) ?>',2);"><div id="elh_rprojsis_nu_metodologia" class="rprojsis_nu_metodologia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->nu_metodologia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->nu_metodologia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->nu_metodologia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->nu_sistema->Visible) { // nu_sistema ?>
	<?php if ($rprojsis->SortUrl($rprojsis->nu_sistema) == "") { ?>
		<td><div id="elh_rprojsis_nu_sistema" class="rprojsis_nu_sistema"><div class="ewTableHeaderCaption"><?php echo $rprojsis->nu_sistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->nu_sistema) ?>',2);"><div id="elh_rprojsis_nu_sistema" class="rprojsis_nu_sistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->nu_sistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->nu_sistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->nu_sistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->ic_ativo->Visible) { // ic_ativo ?>
	<?php if ($rprojsis->SortUrl($rprojsis->ic_ativo) == "") { ?>
		<td><div id="elh_rprojsis_ic_ativo" class="rprojsis_ic_ativo"><div class="ewTableHeaderCaption"><?php echo $rprojsis->ic_ativo->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->ic_ativo) ?>',2);"><div id="elh_rprojsis_ic_ativo" class="rprojsis_ic_ativo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->ic_ativo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->ic_ativo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->ic_ativo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->nu_stSistema->Visible) { // nu_stSistema ?>
	<?php if ($rprojsis->SortUrl($rprojsis->nu_stSistema) == "") { ?>
		<td><div id="elh_rprojsis_nu_stSistema" class="rprojsis_nu_stSistema"><div class="ewTableHeaderCaption"><?php echo $rprojsis->nu_stSistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->nu_stSistema) ?>',2);"><div id="elh_rprojsis_nu_stSistema" class="rprojsis_nu_stSistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->nu_stSistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->nu_stSistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->nu_stSistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->nu_tpProjeto->Visible) { // nu_tpProjeto ?>
	<?php if ($rprojsis->SortUrl($rprojsis->nu_tpProjeto) == "") { ?>
		<td><div id="elh_rprojsis_nu_tpProjeto" class="rprojsis_nu_tpProjeto"><div class="ewTableHeaderCaption"><?php echo $rprojsis->nu_tpProjeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->nu_tpProjeto) ?>',2);"><div id="elh_rprojsis_nu_tpProjeto" class="rprojsis_nu_tpProjeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->nu_tpProjeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->nu_tpProjeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->nu_tpProjeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->nu_projeto->Visible) { // nu_projeto ?>
	<?php if ($rprojsis->SortUrl($rprojsis->nu_projeto) == "") { ?>
		<td><div id="elh_rprojsis_nu_projeto" class="rprojsis_nu_projeto"><div class="ewTableHeaderCaption"><?php echo $rprojsis->nu_projeto->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->nu_projeto) ?>',2);"><div id="elh_rprojsis_nu_projeto" class="rprojsis_nu_projeto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->nu_projeto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->nu_projeto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->nu_projeto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->nu_projetoInteg->Visible) { // nu_projetoInteg ?>
	<?php if ($rprojsis->SortUrl($rprojsis->nu_projetoInteg) == "") { ?>
		<td><div id="elh_rprojsis_nu_projetoInteg" class="rprojsis_nu_projetoInteg"><div class="ewTableHeaderCaption"><?php echo $rprojsis->nu_projetoInteg->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->nu_projetoInteg) ?>',2);"><div id="elh_rprojsis_nu_projetoInteg" class="rprojsis_nu_projetoInteg">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->nu_projetoInteg->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->nu_projetoInteg->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->nu_projetoInteg->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->ic_passivelContPf->Visible) { // ic_passivelContPf ?>
	<?php if ($rprojsis->SortUrl($rprojsis->ic_passivelContPf) == "") { ?>
		<td><div id="elh_rprojsis_ic_passivelContPf" class="rprojsis_ic_passivelContPf"><div class="ewTableHeaderCaption"><?php echo $rprojsis->ic_passivelContPf->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->ic_passivelContPf) ?>',2);"><div id="elh_rprojsis_ic_passivelContPf" class="rprojsis_ic_passivelContPf">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->ic_passivelContPf->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->ic_passivelContPf->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->ic_passivelContPf->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->id_tarefaTpProj->Visible) { // id_tarefaTpProj ?>
	<?php if ($rprojsis->SortUrl($rprojsis->id_tarefaTpProj) == "") { ?>
		<td><div id="elh_rprojsis_id_tarefaTpProj" class="rprojsis_id_tarefaTpProj"><div class="ewTableHeaderCaption"><?php echo $rprojsis->id_tarefaTpProj->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->id_tarefaTpProj) ?>',2);"><div id="elh_rprojsis_id_tarefaTpProj" class="rprojsis_id_tarefaTpProj">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->id_tarefaTpProj->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->id_tarefaTpProj->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->id_tarefaTpProj->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->status_id->Visible) { // status_id ?>
	<?php if ($rprojsis->SortUrl($rprojsis->status_id) == "") { ?>
		<td><div id="elh_rprojsis_status_id" class="rprojsis_status_id"><div class="ewTableHeaderCaption"><?php echo $rprojsis->status_id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->status_id) ?>',2);"><div id="elh_rprojsis_status_id" class="rprojsis_status_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->status_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->status_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->status_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->start_date->Visible) { // start_date ?>
	<?php if ($rprojsis->SortUrl($rprojsis->start_date) == "") { ?>
		<td><div id="elh_rprojsis_start_date" class="rprojsis_start_date"><div class="ewTableHeaderCaption"><?php echo $rprojsis->start_date->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->start_date) ?>',2);"><div id="elh_rprojsis_start_date" class="rprojsis_start_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->start_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->start_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->start_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->due_date->Visible) { // due_date ?>
	<?php if ($rprojsis->SortUrl($rprojsis->due_date) == "") { ?>
		<td><div id="elh_rprojsis_due_date" class="rprojsis_due_date"><div class="ewTableHeaderCaption"><?php echo $rprojsis->due_date->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->due_date) ?>',2);"><div id="elh_rprojsis_due_date" class="rprojsis_due_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->due_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->due_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->due_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->assigned_to->Visible) { // assigned_to ?>
	<?php if ($rprojsis->SortUrl($rprojsis->assigned_to) == "") { ?>
		<td><div id="elh_rprojsis_assigned_to" class="rprojsis_assigned_to"><div class="ewTableHeaderCaption"><?php echo $rprojsis->assigned_to->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->assigned_to) ?>',2);"><div id="elh_rprojsis_assigned_to" class="rprojsis_assigned_to">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->assigned_to->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->assigned_to->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->assigned_to->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->ic_stContagem->Visible) { // ic_stContagem ?>
	<?php if ($rprojsis->SortUrl($rprojsis->ic_stContagem) == "") { ?>
		<td><div id="elh_rprojsis_ic_stContagem" class="rprojsis_ic_stContagem"><div class="ewTableHeaderCaption"><?php echo $rprojsis->ic_stContagem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->ic_stContagem) ?>',2);"><div id="elh_rprojsis_ic_stContagem" class="rprojsis_ic_stContagem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->ic_stContagem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->ic_stContagem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->ic_stContagem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($rprojsis->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
	<?php if ($rprojsis->SortUrl($rprojsis->vr_pfFaturamento) == "") { ?>
		<td><div id="elh_rprojsis_vr_pfFaturamento" class="rprojsis_vr_pfFaturamento"><div class="ewTableHeaderCaption"><?php echo $rprojsis->vr_pfFaturamento->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $rprojsis->SortUrl($rprojsis->vr_pfFaturamento) ?>',2);"><div id="elh_rprojsis_vr_pfFaturamento" class="rprojsis_vr_pfFaturamento">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $rprojsis->vr_pfFaturamento->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($rprojsis->vr_pfFaturamento->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($rprojsis->vr_pfFaturamento->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$rprojsis_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($rprojsis->ExportAll && $rprojsis->Export <> "") {
	$rprojsis_list->StopRec = $rprojsis_list->TotalRecs;
} else {

	// Set the last record to display
	if ($rprojsis_list->TotalRecs > $rprojsis_list->StartRec + $rprojsis_list->DisplayRecs - 1)
		$rprojsis_list->StopRec = $rprojsis_list->StartRec + $rprojsis_list->DisplayRecs - 1;
	else
		$rprojsis_list->StopRec = $rprojsis_list->TotalRecs;
}
$rprojsis_list->RecCnt = $rprojsis_list->StartRec - 1;
if ($rprojsis_list->Recordset && !$rprojsis_list->Recordset->EOF) {
	$rprojsis_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $rprojsis_list->StartRec > 1)
		$rprojsis_list->Recordset->Move($rprojsis_list->StartRec - 1);
} elseif (!$rprojsis->AllowAddDeleteRow && $rprojsis_list->StopRec == 0) {
	$rprojsis_list->StopRec = $rprojsis->GridAddRowCount;
}

// Initialize aggregate
$rprojsis->RowType = EW_ROWTYPE_AGGREGATEINIT;
$rprojsis->ResetAttrs();
$rprojsis_list->RenderRow();
while ($rprojsis_list->RecCnt < $rprojsis_list->StopRec) {
	$rprojsis_list->RecCnt++;
	if (intval($rprojsis_list->RecCnt) >= intval($rprojsis_list->StartRec)) {
		$rprojsis_list->RowCnt++;

		// Set up key count
		$rprojsis_list->KeyCount = $rprojsis_list->RowIndex;

		// Init row class and style
		$rprojsis->ResetAttrs();
		$rprojsis->CssClass = "";
		if ($rprojsis->CurrentAction == "gridadd") {
		} else {
			$rprojsis_list->LoadRowValues($rprojsis_list->Recordset); // Load row values
		}
		$rprojsis->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$rprojsis->RowAttrs = array_merge($rprojsis->RowAttrs, array('data-rowindex'=>$rprojsis_list->RowCnt, 'id'=>'r' . $rprojsis_list->RowCnt . '_rprojsis', 'data-rowtype'=>$rprojsis->RowType));

		// Render row
		$rprojsis_list->RenderRow();

		// Render list options
		$rprojsis_list->RenderListOptions();
?>
	<tr<?php echo $rprojsis->RowAttributes() ?>>
<?php

// Render list options (body, left)
$rprojsis_list->ListOptions->Render("body", "left", $rprojsis_list->RowCnt);
?>
	<?php if ($rprojsis->nu_contrato->Visible) { // nu_contrato ?>
		<td<?php echo $rprojsis->nu_contrato->CellAttributes() ?>>
<span<?php echo $rprojsis->nu_contrato->ViewAttributes() ?>>
<?php echo $rprojsis->nu_contrato->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->nu_itemContrato->Visible) { // nu_itemContrato ?>
		<td<?php echo $rprojsis->nu_itemContrato->CellAttributes() ?>>
<span<?php echo $rprojsis->nu_itemContrato->ViewAttributes() ?>>
<?php echo $rprojsis->nu_itemContrato->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->nu_ambiente->Visible) { // nu_ambiente ?>
		<td<?php echo $rprojsis->nu_ambiente->CellAttributes() ?>>
<span<?php echo $rprojsis->nu_ambiente->ViewAttributes() ?>>
<?php echo $rprojsis->nu_ambiente->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->nu_metodologia->Visible) { // nu_metodologia ?>
		<td<?php echo $rprojsis->nu_metodologia->CellAttributes() ?>>
<span<?php echo $rprojsis->nu_metodologia->ViewAttributes() ?>>
<?php echo $rprojsis->nu_metodologia->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->nu_sistema->Visible) { // nu_sistema ?>
		<td<?php echo $rprojsis->nu_sistema->CellAttributes() ?>>
<span<?php echo $rprojsis->nu_sistema->ViewAttributes() ?>>
<?php echo $rprojsis->nu_sistema->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->ic_ativo->Visible) { // ic_ativo ?>
		<td<?php echo $rprojsis->ic_ativo->CellAttributes() ?>>
<span<?php echo $rprojsis->ic_ativo->ViewAttributes() ?>>
<?php echo $rprojsis->ic_ativo->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->nu_stSistema->Visible) { // nu_stSistema ?>
		<td<?php echo $rprojsis->nu_stSistema->CellAttributes() ?>>
<span<?php echo $rprojsis->nu_stSistema->ViewAttributes() ?>>
<?php echo $rprojsis->nu_stSistema->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->nu_tpProjeto->Visible) { // nu_tpProjeto ?>
		<td<?php echo $rprojsis->nu_tpProjeto->CellAttributes() ?>>
<span<?php echo $rprojsis->nu_tpProjeto->ViewAttributes() ?>>
<?php echo $rprojsis->nu_tpProjeto->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->nu_projeto->Visible) { // nu_projeto ?>
		<td<?php echo $rprojsis->nu_projeto->CellAttributes() ?>>
<span<?php echo $rprojsis->nu_projeto->ViewAttributes() ?>>
<?php echo $rprojsis->nu_projeto->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->nu_projetoInteg->Visible) { // nu_projetoInteg ?>
		<td<?php echo $rprojsis->nu_projetoInteg->CellAttributes() ?>>
<span<?php echo $rprojsis->nu_projetoInteg->ViewAttributes() ?>>
<?php echo $rprojsis->nu_projetoInteg->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->ic_passivelContPf->Visible) { // ic_passivelContPf ?>
		<td<?php echo $rprojsis->ic_passivelContPf->CellAttributes() ?>>
<span<?php echo $rprojsis->ic_passivelContPf->ViewAttributes() ?>>
<?php echo $rprojsis->ic_passivelContPf->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->id_tarefaTpProj->Visible) { // id_tarefaTpProj ?>
		<td<?php echo $rprojsis->id_tarefaTpProj->CellAttributes() ?>>
<span<?php echo $rprojsis->id_tarefaTpProj->ViewAttributes() ?>>
<?php echo $rprojsis->id_tarefaTpProj->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->status_id->Visible) { // status_id ?>
		<td<?php echo $rprojsis->status_id->CellAttributes() ?>>
<span<?php echo $rprojsis->status_id->ViewAttributes() ?>>
<?php echo $rprojsis->status_id->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->start_date->Visible) { // start_date ?>
		<td<?php echo $rprojsis->start_date->CellAttributes() ?>>
<span<?php echo $rprojsis->start_date->ViewAttributes() ?>>
<?php echo $rprojsis->start_date->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->due_date->Visible) { // due_date ?>
		<td<?php echo $rprojsis->due_date->CellAttributes() ?>>
<span<?php echo $rprojsis->due_date->ViewAttributes() ?>>
<?php echo $rprojsis->due_date->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->assigned_to->Visible) { // assigned_to ?>
		<td<?php echo $rprojsis->assigned_to->CellAttributes() ?>>
<span<?php echo $rprojsis->assigned_to->ViewAttributes() ?>>
<?php echo $rprojsis->assigned_to->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->ic_stContagem->Visible) { // ic_stContagem ?>
		<td<?php echo $rprojsis->ic_stContagem->CellAttributes() ?>>
<span<?php echo $rprojsis->ic_stContagem->ViewAttributes() ?>>
<?php echo $rprojsis->ic_stContagem->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($rprojsis->vr_pfFaturamento->Visible) { // vr_pfFaturamento ?>
		<td<?php echo $rprojsis->vr_pfFaturamento->CellAttributes() ?>>
<span<?php echo $rprojsis->vr_pfFaturamento->ViewAttributes() ?>>
<?php echo $rprojsis->vr_pfFaturamento->ListViewValue() ?></span>
<a id="<?php echo $rprojsis_list->PageObjName . "_row_" . $rprojsis_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$rprojsis_list->ListOptions->Render("body", "right", $rprojsis_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($rprojsis->CurrentAction <> "gridadd")
		$rprojsis_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($rprojsis->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($rprojsis_list->Recordset)
	$rprojsis_list->Recordset->Close();
?>
<?php if ($rprojsis->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($rprojsis->CurrentAction <> "gridadd" && $rprojsis->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($rprojsis_list->Pager)) $rprojsis_list->Pager = new cNumericPager($rprojsis_list->StartRec, $rprojsis_list->DisplayRecs, $rprojsis_list->TotalRecs, $rprojsis_list->RecRange) ?>
<?php if ($rprojsis_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($rprojsis_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $rprojsis_list->PageUrl() ?>start=<?php echo $rprojsis_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($rprojsis_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $rprojsis_list->PageUrl() ?>start=<?php echo $rprojsis_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($rprojsis_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $rprojsis_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($rprojsis_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $rprojsis_list->PageUrl() ?>start=<?php echo $rprojsis_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($rprojsis_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $rprojsis_list->PageUrl() ?>start=<?php echo $rprojsis_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($rprojsis_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $rprojsis_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $rprojsis_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $rprojsis_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($rprojsis_list->SearchWhere == "0=101") { ?>
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
	foreach ($rprojsis_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($rprojsis->Export == "") { ?>
<script type="text/javascript">
frprojsislist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$rprojsis_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($rprojsis->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$rprojsis_list->Page_Terminate();
?>
