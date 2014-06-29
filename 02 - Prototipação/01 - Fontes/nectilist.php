<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "nectiinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$necti_list = NULL; // Initialize page object first

class cnecti_list extends cnecti {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'necti';

	// Page object name
	var $PageObjName = 'necti_list';

	// Grid form hidden field names
	var $FormName = 'fnectilist';
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

		// Table object (necti)
		if (!isset($GLOBALS["necti"])) {
			$GLOBALS["necti"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["necti"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "nectiadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "nectidelete.php";
		$this->MultiUpdateUrl = "nectiupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'necti', TRUE);

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
		$this->nu_necTi->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (count($arrKeyFlds) >= 1) {
			$this->nu_necTi->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_necTi->FormValue))
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
			$this->UpdateSort($this->nu_necTi, $bCtrl); // nu_necTi
			$this->UpdateSort($this->nu_periodoPei, $bCtrl); // nu_periodoPei
			$this->UpdateSort($this->nu_periodoPdti, $bCtrl); // nu_periodoPdti
			$this->UpdateSort($this->nu_tpNecTi, $bCtrl); // nu_tpNecTi
			$this->UpdateSort($this->ic_tpNec, $bCtrl); // ic_tpNec
			$this->UpdateSort($this->nu_metaneg, $bCtrl); // nu_metaneg
			$this->UpdateSort($this->nu_origem, $bCtrl); // nu_origem
			$this->UpdateSort($this->nu_area, $bCtrl); // nu_area
			$this->UpdateSort($this->ic_gravidade, $bCtrl); // ic_gravidade
			$this->UpdateSort($this->ic_urgencia, $bCtrl); // ic_urgencia
			$this->UpdateSort($this->ic_tendencia, $bCtrl); // ic_tendencia
			$this->UpdateSort($this->ic_prioridade, $bCtrl); // ic_prioridade
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
				$this->setSessionOrderByList($sOrderBy);
				$this->nu_necTi->setSort("");
				$this->nu_periodoPei->setSort("");
				$this->nu_periodoPdti->setSort("");
				$this->nu_tpNecTi->setSort("");
				$this->ic_tpNec->setSort("");
				$this->nu_metaneg->setSort("");
				$this->nu_origem->setSort("");
				$this->nu_area->setSort("");
				$this->ic_gravidade->setSort("");
				$this->ic_urgencia->setSort("");
				$this->ic_tendencia->setSort("");
				$this->ic_prioridade->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fnectilist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->nu_necTi->setDbValue($rs->fields('nu_necTi'));
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		if (array_key_exists('EV__nu_periodoPei', $rs->fields)) {
			$this->nu_periodoPei->VirtualValue = $rs->fields('EV__nu_periodoPei'); // Set up virtual field value
		} else {
			$this->nu_periodoPei->VirtualValue = ""; // Clear value
		}
		$this->nu_periodoPdti->setDbValue($rs->fields('nu_periodoPdti'));
		if (array_key_exists('EV__nu_periodoPdti', $rs->fields)) {
			$this->nu_periodoPdti->VirtualValue = $rs->fields('EV__nu_periodoPdti'); // Set up virtual field value
		} else {
			$this->nu_periodoPdti->VirtualValue = ""; // Clear value
		}
		$this->nu_tpNecTi->setDbValue($rs->fields('nu_tpNecTi'));
		$this->ic_tpNec->setDbValue($rs->fields('ic_tpNec'));
		$this->nu_metaneg->setDbValue($rs->fields('nu_metaneg'));
		$this->nu_origem->setDbValue($rs->fields('nu_origem'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ic_gravidade->setDbValue($rs->fields('ic_gravidade'));
		$this->ic_urgencia->setDbValue($rs->fields('ic_urgencia'));
		$this->ic_tendencia->setDbValue($rs->fields('ic_tendencia'));
		$this->ic_prioridade->setDbValue($rs->fields('ic_prioridade'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_necTi->DbValue = $row['nu_necTi'];
		$this->nu_periodoPei->DbValue = $row['nu_periodoPei'];
		$this->nu_periodoPdti->DbValue = $row['nu_periodoPdti'];
		$this->nu_tpNecTi->DbValue = $row['nu_tpNecTi'];
		$this->ic_tpNec->DbValue = $row['ic_tpNec'];
		$this->nu_metaneg->DbValue = $row['nu_metaneg'];
		$this->nu_origem->DbValue = $row['nu_origem'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->ic_gravidade->DbValue = $row['ic_gravidade'];
		$this->ic_urgencia->DbValue = $row['ic_urgencia'];
		$this->ic_tendencia->DbValue = $row['ic_tendencia'];
		$this->ic_prioridade->DbValue = $row['ic_prioridade'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_necTi")) <> "")
			$this->nu_necTi->CurrentValue = $this->getKey("nu_necTi"); // nu_necTi
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
		// nu_necTi
		// nu_periodoPei
		// nu_periodoPdti
		// nu_tpNecTi
		// ic_tpNec
		// nu_metaneg
		// nu_origem
		// nu_area
		// ic_gravidade
		// ic_urgencia
		// ic_tendencia
		// ic_prioridade

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_necTi
			$this->nu_necTi->ViewValue = $this->nu_necTi->CurrentValue;
			$this->nu_necTi->ViewCustomAttributes = "";

			// nu_periodoPei
			if ($this->nu_periodoPei->VirtualValue <> "") {
				$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->VirtualValue;
			} else {
			if (strval($this->nu_periodoPei->CurrentValue) <> "") {
				$sFilterWrk = "[nu_periodoPei]" . ew_SearchString("=", $this->nu_periodoPei->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_periodoPei], [nu_anoInicio] AS [DispFld], [nu_anoFim] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[periodopei]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPei, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_periodoPei->ViewValue = $rswrk->fields('DispFld');
					$this->nu_periodoPei->ViewValue .= ew_ValueSeparator(1,$this->nu_periodoPei) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->CurrentValue;
				}
			} else {
				$this->nu_periodoPei->ViewValue = NULL;
			}
			}
			$this->nu_periodoPei->ViewCustomAttributes = "";

			// nu_periodoPdti
			if ($this->nu_periodoPdti->VirtualValue <> "") {
				$this->nu_periodoPdti->ViewValue = $this->nu_periodoPdti->VirtualValue;
			} else {
			if (strval($this->nu_periodoPdti->CurrentValue) <> "") {
				$sFilterWrk = "[nu_periodo]" . ew_SearchString("=", $this->nu_periodoPdti->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_periodo], [no_periodo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[perplanejamento]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPdti, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_periodoPdti->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_periodoPdti->ViewValue = $this->nu_periodoPdti->CurrentValue;
				}
			} else {
				$this->nu_periodoPdti->ViewValue = NULL;
			}
			}
			$this->nu_periodoPdti->ViewCustomAttributes = "";

			// nu_tpNecTi
			if (strval($this->nu_tpNecTi->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpNecTi]" . ew_SearchString("=", $this->nu_tpNecTi->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_tpNecTi], [no_tpNecTi] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpnecti]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpNecTi, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpNecTi] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpNecTi->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpNecTi->ViewValue = $this->nu_tpNecTi->CurrentValue;
				}
			} else {
				$this->nu_tpNecTi->ViewValue = NULL;
			}
			$this->nu_tpNecTi->ViewCustomAttributes = "";

			// ic_tpNec
			if (strval($this->ic_tpNec->CurrentValue) <> "") {
				switch ($this->ic_tpNec->CurrentValue) {
					case $this->ic_tpNec->FldTagValue(1):
						$this->ic_tpNec->ViewValue = $this->ic_tpNec->FldTagCaption(1) <> "" ? $this->ic_tpNec->FldTagCaption(1) : $this->ic_tpNec->CurrentValue;
						break;
					case $this->ic_tpNec->FldTagValue(2):
						$this->ic_tpNec->ViewValue = $this->ic_tpNec->FldTagCaption(2) <> "" ? $this->ic_tpNec->FldTagCaption(2) : $this->ic_tpNec->CurrentValue;
						break;
					default:
						$this->ic_tpNec->ViewValue = $this->ic_tpNec->CurrentValue;
				}
			} else {
				$this->ic_tpNec->ViewValue = NULL;
			}
			$this->ic_tpNec->ViewCustomAttributes = "";

			// nu_metaneg
			if (strval($this->nu_metaneg->CurrentValue) <> "") {
				$sFilterWrk = "[nu_metaneg]" . ew_SearchString("=", $this->nu_metaneg->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_metaneg], [no_metaneg] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metaneg]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metaneg, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_metaneg->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metaneg->ViewValue = $this->nu_metaneg->CurrentValue;
				}
			} else {
				$this->nu_metaneg->ViewValue = NULL;
			}
			$this->nu_metaneg->ViewCustomAttributes = "";

			// nu_origem
			if (strval($this->nu_origem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_origem]" . ew_SearchString("=", $this->nu_origem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_origem], [no_origem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[origemnecti]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_origem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_origem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_origem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_origem->ViewValue = $this->nu_origem->CurrentValue;
				}
			} else {
				$this->nu_origem->ViewValue = NULL;
			}
			$this->nu_origem->ViewCustomAttributes = "";

			// nu_area
			$this->nu_area->ViewValue = $this->nu_area->CurrentValue;
			if (strval($this->nu_area->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]=S && [nu_organizacao] = (SELECT nu_orgBase from organizacao)";
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

			// ic_gravidade
			if (strval($this->ic_gravidade->CurrentValue) <> "") {
				switch ($this->ic_gravidade->CurrentValue) {
					case $this->ic_gravidade->FldTagValue(1):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(1) <> "" ? $this->ic_gravidade->FldTagCaption(1) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(2):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(2) <> "" ? $this->ic_gravidade->FldTagCaption(2) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(3):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(3) <> "" ? $this->ic_gravidade->FldTagCaption(3) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(4):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(4) <> "" ? $this->ic_gravidade->FldTagCaption(4) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(5):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(5) <> "" ? $this->ic_gravidade->FldTagCaption(5) : $this->ic_gravidade->CurrentValue;
						break;
					default:
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->CurrentValue;
				}
			} else {
				$this->ic_gravidade->ViewValue = NULL;
			}
			$this->ic_gravidade->ViewCustomAttributes = "";

			// ic_urgencia
			if (strval($this->ic_urgencia->CurrentValue) <> "") {
				switch ($this->ic_urgencia->CurrentValue) {
					case $this->ic_urgencia->FldTagValue(1):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(1) <> "" ? $this->ic_urgencia->FldTagCaption(1) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(2):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(2) <> "" ? $this->ic_urgencia->FldTagCaption(2) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(3):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(3) <> "" ? $this->ic_urgencia->FldTagCaption(3) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(4):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(4) <> "" ? $this->ic_urgencia->FldTagCaption(4) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(5):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(5) <> "" ? $this->ic_urgencia->FldTagCaption(5) : $this->ic_urgencia->CurrentValue;
						break;
					default:
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->CurrentValue;
				}
			} else {
				$this->ic_urgencia->ViewValue = NULL;
			}
			$this->ic_urgencia->ViewCustomAttributes = "";

			// ic_tendencia
			if (strval($this->ic_tendencia->CurrentValue) <> "") {
				switch ($this->ic_tendencia->CurrentValue) {
					case $this->ic_tendencia->FldTagValue(1):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(1) <> "" ? $this->ic_tendencia->FldTagCaption(1) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(2):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(2) <> "" ? $this->ic_tendencia->FldTagCaption(2) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(3):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(3) <> "" ? $this->ic_tendencia->FldTagCaption(3) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(4):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(4) <> "" ? $this->ic_tendencia->FldTagCaption(4) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(5):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(5) <> "" ? $this->ic_tendencia->FldTagCaption(5) : $this->ic_tendencia->CurrentValue;
						break;
					default:
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->CurrentValue;
				}
			} else {
				$this->ic_tendencia->ViewValue = NULL;
			}
			$this->ic_tendencia->ViewCustomAttributes = "";

			// ic_prioridade
			if (strval($this->ic_prioridade->CurrentValue) <> "") {
				switch ($this->ic_prioridade->CurrentValue) {
					case $this->ic_prioridade->FldTagValue(1):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(1) <> "" ? $this->ic_prioridade->FldTagCaption(1) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(2):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(2) <> "" ? $this->ic_prioridade->FldTagCaption(2) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(3):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(3) <> "" ? $this->ic_prioridade->FldTagCaption(3) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(4):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(4) <> "" ? $this->ic_prioridade->FldTagCaption(4) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(5):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(5) <> "" ? $this->ic_prioridade->FldTagCaption(5) : $this->ic_prioridade->CurrentValue;
						break;
					default:
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->CurrentValue;
				}
			} else {
				$this->ic_prioridade->ViewValue = NULL;
			}
			$this->ic_prioridade->ViewCustomAttributes = "";

			// nu_necTi
			$this->nu_necTi->LinkCustomAttributes = "";
			$this->nu_necTi->HrefValue = "";
			$this->nu_necTi->TooltipValue = "";

			// nu_periodoPei
			$this->nu_periodoPei->LinkCustomAttributes = "";
			$this->nu_periodoPei->HrefValue = "";
			$this->nu_periodoPei->TooltipValue = "";

			// nu_periodoPdti
			$this->nu_periodoPdti->LinkCustomAttributes = "";
			$this->nu_periodoPdti->HrefValue = "";
			$this->nu_periodoPdti->TooltipValue = "";

			// nu_tpNecTi
			$this->nu_tpNecTi->LinkCustomAttributes = "";
			$this->nu_tpNecTi->HrefValue = "";
			$this->nu_tpNecTi->TooltipValue = "";

			// ic_tpNec
			$this->ic_tpNec->LinkCustomAttributes = "";
			$this->ic_tpNec->HrefValue = "";
			$this->ic_tpNec->TooltipValue = "";

			// nu_metaneg
			$this->nu_metaneg->LinkCustomAttributes = "";
			$this->nu_metaneg->HrefValue = "";
			$this->nu_metaneg->TooltipValue = "";

			// nu_origem
			$this->nu_origem->LinkCustomAttributes = "";
			$this->nu_origem->HrefValue = "";
			$this->nu_origem->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// ic_gravidade
			$this->ic_gravidade->LinkCustomAttributes = "";
			$this->ic_gravidade->HrefValue = "";
			$this->ic_gravidade->TooltipValue = "";

			// ic_urgencia
			$this->ic_urgencia->LinkCustomAttributes = "";
			$this->ic_urgencia->HrefValue = "";
			$this->ic_urgencia->TooltipValue = "";

			// ic_tendencia
			$this->ic_tendencia->LinkCustomAttributes = "";
			$this->ic_tendencia->HrefValue = "";
			$this->ic_tendencia->TooltipValue = "";

			// ic_prioridade
			$this->ic_prioridade->LinkCustomAttributes = "";
			$this->ic_prioridade->HrefValue = "";
			$this->ic_prioridade->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_necti\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_necti',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fnectilist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($necti_list)) $necti_list = new cnecti_list();

// Page init
$necti_list->Page_Init();

// Page main
$necti_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$necti_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($necti->Export == "") { ?>
<script type="text/javascript">

// Page object
var necti_list = new ew_Page("necti_list");
necti_list.PageID = "list"; // Page ID
var EW_PAGE_ID = necti_list.PageID; // For backward compatibility

// Form object
var fnectilist = new ew_Form("fnectilist");
fnectilist.FormKeyCountName = '<?php echo $necti_list->FormKeyCountName ?>';

// Form_CustomValidate event
fnectilist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnectilist.ValidateRequired = true;
<?php } else { ?>
fnectilist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnectilist.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_anoInicio","x_nu_anoFim","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectilist.Lists["x_nu_periodoPdti"] = {"LinkField":"x_nu_periodo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_periodo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectilist.Lists["x_nu_tpNecTi"] = {"LinkField":"x_nu_tpNecTi","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpNecTi","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectilist.Lists["x_nu_metaneg"] = {"LinkField":"x_nu_metaneg","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metaneg","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectilist.Lists["x_nu_origem"] = {"LinkField":"x_nu_origem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_origem","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectilist.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($necti->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($necti_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $necti_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$necti_list->TotalRecs = $necti->SelectRecordCount();
	} else {
		if ($necti_list->Recordset = $necti_list->LoadRecordset())
			$necti_list->TotalRecs = $necti_list->Recordset->RecordCount();
	}
	$necti_list->StartRec = 1;
	if ($necti_list->DisplayRecs <= 0 || ($necti->Export <> "" && $necti->ExportAll)) // Display all records
		$necti_list->DisplayRecs = $necti_list->TotalRecs;
	if (!($necti->Export <> "" && $necti->ExportAll))
		$necti_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$necti_list->Recordset = $necti_list->LoadRecordset($necti_list->StartRec-1, $necti_list->DisplayRecs);
$necti_list->RenderOtherOptions();
?>
<?php $necti_list->ShowPageHeader(); ?>
<?php
$necti_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fnectilist" id="fnectilist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="necti">
<div id="gmp_necti" class="ewGridMiddlePanel">
<?php if ($necti_list->TotalRecs > 0) { ?>
<table id="tbl_nectilist" class="ewTable ewTableSeparate">
<?php echo $necti->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$necti_list->RenderListOptions();

// Render list options (header, left)
$necti_list->ListOptions->Render("header", "left");
?>
<?php if ($necti->nu_necTi->Visible) { // nu_necTi ?>
	<?php if ($necti->SortUrl($necti->nu_necTi) == "") { ?>
		<td><div id="elh_necti_nu_necTi" class="necti_nu_necTi"><div class="ewTableHeaderCaption"><?php echo $necti->nu_necTi->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->nu_necTi) ?>',2);"><div id="elh_necti_nu_necTi" class="necti_nu_necTi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->nu_necTi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->nu_necTi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->nu_necTi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->nu_periodoPei->Visible) { // nu_periodoPei ?>
	<?php if ($necti->SortUrl($necti->nu_periodoPei) == "") { ?>
		<td><div id="elh_necti_nu_periodoPei" class="necti_nu_periodoPei"><div class="ewTableHeaderCaption"><?php echo $necti->nu_periodoPei->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->nu_periodoPei) ?>',2);"><div id="elh_necti_nu_periodoPei" class="necti_nu_periodoPei">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->nu_periodoPei->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->nu_periodoPei->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->nu_periodoPei->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->nu_periodoPdti->Visible) { // nu_periodoPdti ?>
	<?php if ($necti->SortUrl($necti->nu_periodoPdti) == "") { ?>
		<td><div id="elh_necti_nu_periodoPdti" class="necti_nu_periodoPdti"><div class="ewTableHeaderCaption"><?php echo $necti->nu_periodoPdti->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->nu_periodoPdti) ?>',2);"><div id="elh_necti_nu_periodoPdti" class="necti_nu_periodoPdti">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->nu_periodoPdti->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->nu_periodoPdti->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->nu_periodoPdti->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->nu_tpNecTi->Visible) { // nu_tpNecTi ?>
	<?php if ($necti->SortUrl($necti->nu_tpNecTi) == "") { ?>
		<td><div id="elh_necti_nu_tpNecTi" class="necti_nu_tpNecTi"><div class="ewTableHeaderCaption"><?php echo $necti->nu_tpNecTi->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->nu_tpNecTi) ?>',2);"><div id="elh_necti_nu_tpNecTi" class="necti_nu_tpNecTi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->nu_tpNecTi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->nu_tpNecTi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->nu_tpNecTi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->ic_tpNec->Visible) { // ic_tpNec ?>
	<?php if ($necti->SortUrl($necti->ic_tpNec) == "") { ?>
		<td><div id="elh_necti_ic_tpNec" class="necti_ic_tpNec"><div class="ewTableHeaderCaption"><?php echo $necti->ic_tpNec->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->ic_tpNec) ?>',2);"><div id="elh_necti_ic_tpNec" class="necti_ic_tpNec">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->ic_tpNec->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->ic_tpNec->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->ic_tpNec->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->nu_metaneg->Visible) { // nu_metaneg ?>
	<?php if ($necti->SortUrl($necti->nu_metaneg) == "") { ?>
		<td><div id="elh_necti_nu_metaneg" class="necti_nu_metaneg"><div class="ewTableHeaderCaption"><?php echo $necti->nu_metaneg->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->nu_metaneg) ?>',2);"><div id="elh_necti_nu_metaneg" class="necti_nu_metaneg">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->nu_metaneg->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->nu_metaneg->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->nu_metaneg->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->nu_origem->Visible) { // nu_origem ?>
	<?php if ($necti->SortUrl($necti->nu_origem) == "") { ?>
		<td><div id="elh_necti_nu_origem" class="necti_nu_origem"><div class="ewTableHeaderCaption"><?php echo $necti->nu_origem->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->nu_origem) ?>',2);"><div id="elh_necti_nu_origem" class="necti_nu_origem">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->nu_origem->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->nu_origem->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->nu_origem->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->nu_area->Visible) { // nu_area ?>
	<?php if ($necti->SortUrl($necti->nu_area) == "") { ?>
		<td><div id="elh_necti_nu_area" class="necti_nu_area"><div class="ewTableHeaderCaption"><?php echo $necti->nu_area->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->nu_area) ?>',2);"><div id="elh_necti_nu_area" class="necti_nu_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->nu_area->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->nu_area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->nu_area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->ic_gravidade->Visible) { // ic_gravidade ?>
	<?php if ($necti->SortUrl($necti->ic_gravidade) == "") { ?>
		<td><div id="elh_necti_ic_gravidade" class="necti_ic_gravidade"><div class="ewTableHeaderCaption"><?php echo $necti->ic_gravidade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->ic_gravidade) ?>',2);"><div id="elh_necti_ic_gravidade" class="necti_ic_gravidade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->ic_gravidade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->ic_gravidade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->ic_gravidade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->ic_urgencia->Visible) { // ic_urgencia ?>
	<?php if ($necti->SortUrl($necti->ic_urgencia) == "") { ?>
		<td><div id="elh_necti_ic_urgencia" class="necti_ic_urgencia"><div class="ewTableHeaderCaption"><?php echo $necti->ic_urgencia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->ic_urgencia) ?>',2);"><div id="elh_necti_ic_urgencia" class="necti_ic_urgencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->ic_urgencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->ic_urgencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->ic_urgencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->ic_tendencia->Visible) { // ic_tendencia ?>
	<?php if ($necti->SortUrl($necti->ic_tendencia) == "") { ?>
		<td><div id="elh_necti_ic_tendencia" class="necti_ic_tendencia"><div class="ewTableHeaderCaption"><?php echo $necti->ic_tendencia->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->ic_tendencia) ?>',2);"><div id="elh_necti_ic_tendencia" class="necti_ic_tendencia">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->ic_tendencia->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->ic_tendencia->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->ic_tendencia->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($necti->ic_prioridade->Visible) { // ic_prioridade ?>
	<?php if ($necti->SortUrl($necti->ic_prioridade) == "") { ?>
		<td><div id="elh_necti_ic_prioridade" class="necti_ic_prioridade"><div class="ewTableHeaderCaption"><?php echo $necti->ic_prioridade->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $necti->SortUrl($necti->ic_prioridade) ?>',2);"><div id="elh_necti_ic_prioridade" class="necti_ic_prioridade">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $necti->ic_prioridade->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($necti->ic_prioridade->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($necti->ic_prioridade->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$necti_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($necti->ExportAll && $necti->Export <> "") {
	$necti_list->StopRec = $necti_list->TotalRecs;
} else {

	// Set the last record to display
	if ($necti_list->TotalRecs > $necti_list->StartRec + $necti_list->DisplayRecs - 1)
		$necti_list->StopRec = $necti_list->StartRec + $necti_list->DisplayRecs - 1;
	else
		$necti_list->StopRec = $necti_list->TotalRecs;
}
$necti_list->RecCnt = $necti_list->StartRec - 1;
if ($necti_list->Recordset && !$necti_list->Recordset->EOF) {
	$necti_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $necti_list->StartRec > 1)
		$necti_list->Recordset->Move($necti_list->StartRec - 1);
} elseif (!$necti->AllowAddDeleteRow && $necti_list->StopRec == 0) {
	$necti_list->StopRec = $necti->GridAddRowCount;
}

// Initialize aggregate
$necti->RowType = EW_ROWTYPE_AGGREGATEINIT;
$necti->ResetAttrs();
$necti_list->RenderRow();
while ($necti_list->RecCnt < $necti_list->StopRec) {
	$necti_list->RecCnt++;
	if (intval($necti_list->RecCnt) >= intval($necti_list->StartRec)) {
		$necti_list->RowCnt++;

		// Set up key count
		$necti_list->KeyCount = $necti_list->RowIndex;

		// Init row class and style
		$necti->ResetAttrs();
		$necti->CssClass = "";
		if ($necti->CurrentAction == "gridadd") {
		} else {
			$necti_list->LoadRowValues($necti_list->Recordset); // Load row values
		}
		$necti->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$necti->RowAttrs = array_merge($necti->RowAttrs, array('data-rowindex'=>$necti_list->RowCnt, 'id'=>'r' . $necti_list->RowCnt . '_necti', 'data-rowtype'=>$necti->RowType));

		// Render row
		$necti_list->RenderRow();

		// Render list options
		$necti_list->RenderListOptions();
?>
	<tr<?php echo $necti->RowAttributes() ?>>
<?php

// Render list options (body, left)
$necti_list->ListOptions->Render("body", "left", $necti_list->RowCnt);
?>
	<?php if ($necti->nu_necTi->Visible) { // nu_necTi ?>
		<td<?php echo $necti->nu_necTi->CellAttributes() ?>>
<span<?php echo $necti->nu_necTi->ViewAttributes() ?>>
<?php echo $necti->nu_necTi->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->nu_periodoPei->Visible) { // nu_periodoPei ?>
		<td<?php echo $necti->nu_periodoPei->CellAttributes() ?>>
<span<?php echo $necti->nu_periodoPei->ViewAttributes() ?>>
<?php echo $necti->nu_periodoPei->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->nu_periodoPdti->Visible) { // nu_periodoPdti ?>
		<td<?php echo $necti->nu_periodoPdti->CellAttributes() ?>>
<span<?php echo $necti->nu_periodoPdti->ViewAttributes() ?>>
<?php echo $necti->nu_periodoPdti->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->nu_tpNecTi->Visible) { // nu_tpNecTi ?>
		<td<?php echo $necti->nu_tpNecTi->CellAttributes() ?>>
<span<?php echo $necti->nu_tpNecTi->ViewAttributes() ?>>
<?php echo $necti->nu_tpNecTi->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->ic_tpNec->Visible) { // ic_tpNec ?>
		<td<?php echo $necti->ic_tpNec->CellAttributes() ?>>
<span<?php echo $necti->ic_tpNec->ViewAttributes() ?>>
<?php echo $necti->ic_tpNec->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->nu_metaneg->Visible) { // nu_metaneg ?>
		<td<?php echo $necti->nu_metaneg->CellAttributes() ?>>
<span<?php echo $necti->nu_metaneg->ViewAttributes() ?>>
<?php echo $necti->nu_metaneg->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->nu_origem->Visible) { // nu_origem ?>
		<td<?php echo $necti->nu_origem->CellAttributes() ?>>
<span<?php echo $necti->nu_origem->ViewAttributes() ?>>
<?php echo $necti->nu_origem->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->nu_area->Visible) { // nu_area ?>
		<td<?php echo $necti->nu_area->CellAttributes() ?>>
<span<?php echo $necti->nu_area->ViewAttributes() ?>>
<?php echo $necti->nu_area->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->ic_gravidade->Visible) { // ic_gravidade ?>
		<td<?php echo $necti->ic_gravidade->CellAttributes() ?>>
<span<?php echo $necti->ic_gravidade->ViewAttributes() ?>>
<?php echo $necti->ic_gravidade->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->ic_urgencia->Visible) { // ic_urgencia ?>
		<td<?php echo $necti->ic_urgencia->CellAttributes() ?>>
<span<?php echo $necti->ic_urgencia->ViewAttributes() ?>>
<?php echo $necti->ic_urgencia->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->ic_tendencia->Visible) { // ic_tendencia ?>
		<td<?php echo $necti->ic_tendencia->CellAttributes() ?>>
<span<?php echo $necti->ic_tendencia->ViewAttributes() ?>>
<?php echo $necti->ic_tendencia->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($necti->ic_prioridade->Visible) { // ic_prioridade ?>
		<td<?php echo $necti->ic_prioridade->CellAttributes() ?>>
<span<?php echo $necti->ic_prioridade->ViewAttributes() ?>>
<?php echo $necti->ic_prioridade->ListViewValue() ?></span>
<a id="<?php echo $necti_list->PageObjName . "_row_" . $necti_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$necti_list->ListOptions->Render("body", "right", $necti_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($necti->CurrentAction <> "gridadd")
		$necti_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($necti->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($necti_list->Recordset)
	$necti_list->Recordset->Close();
?>
<?php if ($necti->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($necti->CurrentAction <> "gridadd" && $necti->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($necti_list->Pager)) $necti_list->Pager = new cNumericPager($necti_list->StartRec, $necti_list->DisplayRecs, $necti_list->TotalRecs, $necti_list->RecRange) ?>
<?php if ($necti_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($necti_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $necti_list->PageUrl() ?>start=<?php echo $necti_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($necti_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $necti_list->PageUrl() ?>start=<?php echo $necti_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($necti_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $necti_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($necti_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $necti_list->PageUrl() ?>start=<?php echo $necti_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($necti_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $necti_list->PageUrl() ?>start=<?php echo $necti_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($necti_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $necti_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $necti_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $necti_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($necti_list->SearchWhere == "0=101") { ?>
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
	foreach ($necti_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($necti->Export == "") { ?>
<script type="text/javascript">
fnectilist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$necti_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($necti->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$necti_list->Page_Terminate();
?>
