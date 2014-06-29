<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "pargeraisinfo.php" ?>
<?php include_once "organizacaoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$pargerais_list = NULL; // Initialize page object first

class cpargerais_list extends cpargerais {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'pargerais';

	// Page object name
	var $PageObjName = 'pargerais_list';

	// Grid form hidden field names
	var $FormName = 'fpargeraislist';
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

		// Table object (pargerais)
		if (!isset($GLOBALS["pargerais"])) {
			$GLOBALS["pargerais"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pargerais"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "pargeraisadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "pargeraisdelete.php";
		$this->MultiUpdateUrl = "pargeraisupdate.php";

		// Table object (organizacao)
		if (!isset($GLOBALS['organizacao'])) $GLOBALS['organizacao'] = new corganizacao();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pargerais', TRUE);

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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "organizacao") {
			global $organizacao;
			$rsmaster = $organizacao->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("organizacaolist.php"); // Return to master page
			} else {
				$organizacao->LoadListRowValues($rsmaster);
				$organizacao->RowType = EW_ROWTYPE_MASTER; // Master row
				$organizacao->RenderListRow();
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
			$this->nu_parametro->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->nu_parametro->FormValue))
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
			$this->UpdateSort($this->nu_orgBase, $bCtrl); // nu_orgBase
			$this->UpdateSort($this->nu_area, $bCtrl); // nu_area
			$this->UpdateSort($this->nu_usuarioRespAreaTi, $bCtrl); // nu_usuarioRespAreaTi
			$this->UpdateSort($this->nu_sistema, $bCtrl); // nu_sistema
			$this->UpdateSort($this->dt_inicioOpSistema, $bCtrl); // dt_inicioOpSistema
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
				$this->nu_orgBase->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->nu_orgBase->setSort("");
				$this->nu_area->setSort("");
				$this->nu_usuarioRespAreaTi->setSort("");
				$this->nu_sistema->setSort("");
				$this->dt_inicioOpSistema->setSort("");
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

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
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

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fpargeraislist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->nu_parametro->setDbValue($rs->fields('nu_parametro'));
		$this->nu_orgBase->setDbValue($rs->fields('nu_orgBase'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->nu_usuarioRespAreaTi->setDbValue($rs->fields('nu_usuarioRespAreaTi'));
		if (array_key_exists('EV__nu_usuarioRespAreaTi', $rs->fields)) {
			$this->nu_usuarioRespAreaTi->VirtualValue = $rs->fields('EV__nu_usuarioRespAreaTi'); // Set up virtual field value
		} else {
			$this->nu_usuarioRespAreaTi->VirtualValue = ""; // Clear value
		}
		$this->qt_horasMes->setDbValue($rs->fields('qt_horasMes'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->dt_inicioOpSistema->setDbValue($rs->fields('dt_inicioOpSistema'));
		$this->tx_htmlHomeNaoLogado->setDbValue($rs->fields('tx_htmlHomeNaoLogado'));
		$this->nu_orgMetricas->setDbValue($rs->fields('nu_orgMetricas'));
		$this->nu_areaMetricas->setDbValue($rs->fields('nu_areaMetricas'));
		if (array_key_exists('EV__nu_areaMetricas', $rs->fields)) {
			$this->nu_areaMetricas->VirtualValue = $rs->fields('EV__nu_areaMetricas'); // Set up virtual field value
		} else {
			$this->nu_areaMetricas->VirtualValue = ""; // Clear value
		}
		$this->nu_fornMetricas->setDbValue($rs->fields('nu_fornMetricas'));
		$this->no_areaMetricas->setDbValue($rs->fields('no_areaMetricas'));
		$this->nu_modeloMetricasPadrao->setDbValue($rs->fields('nu_modeloMetricasPadrao'));
		if (array_key_exists('EV__nu_modeloMetricasPadrao', $rs->fields)) {
			$this->nu_modeloMetricasPadrao->VirtualValue = $rs->fields('EV__nu_modeloMetricasPadrao'); // Set up virtual field value
		} else {
			$this->nu_modeloMetricasPadrao->VirtualValue = ""; // Clear value
		}
		$this->nu_areaVincEscritProj->setDbValue($rs->fields('nu_areaVincEscritProj'));
		$this->no_areaEscritProj->setDbValue($rs->fields('no_areaEscritProj'));
		$this->nu_fornecedorAuditoria->setDbValue($rs->fields('nu_fornecedorAuditoria'));
		$this->nu_fornPadraoFsw->setDbValue($rs->fields('nu_fornPadraoFsw'));
		if (array_key_exists('EV__nu_fornPadraoFsw', $rs->fields)) {
			$this->nu_fornPadraoFsw->VirtualValue = $rs->fields('EV__nu_fornPadraoFsw'); // Set up virtual field value
		} else {
			$this->nu_fornPadraoFsw->VirtualValue = ""; // Clear value
		}
		$this->nu_contFornPadraoFsw->setDbValue($rs->fields('nu_contFornPadraoFsw'));
		if (array_key_exists('EV__nu_contFornPadraoFsw', $rs->fields)) {
			$this->nu_contFornPadraoFsw->VirtualValue = $rs->fields('EV__nu_contFornPadraoFsw'); // Set up virtual field value
		} else {
			$this->nu_contFornPadraoFsw->VirtualValue = ""; // Clear value
		}
		$this->nu_itemContFornPadraoFsw->setDbValue($rs->fields('nu_itemContFornPadraoFsw'));
		$this->nu_pesoProbRisco->setDbValue($rs->fields('nu_pesoProbRisco'));
		$this->nu_pesoImpacRisco->setDbValue($rs->fields('nu_pesoImpacRisco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_parametro->DbValue = $row['nu_parametro'];
		$this->nu_orgBase->DbValue = $row['nu_orgBase'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->nu_usuarioRespAreaTi->DbValue = $row['nu_usuarioRespAreaTi'];
		$this->qt_horasMes->DbValue = $row['qt_horasMes'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->dt_inicioOpSistema->DbValue = $row['dt_inicioOpSistema'];
		$this->tx_htmlHomeNaoLogado->DbValue = $row['tx_htmlHomeNaoLogado'];
		$this->nu_orgMetricas->DbValue = $row['nu_orgMetricas'];
		$this->nu_areaMetricas->DbValue = $row['nu_areaMetricas'];
		$this->nu_fornMetricas->DbValue = $row['nu_fornMetricas'];
		$this->no_areaMetricas->DbValue = $row['no_areaMetricas'];
		$this->nu_modeloMetricasPadrao->DbValue = $row['nu_modeloMetricasPadrao'];
		$this->nu_areaVincEscritProj->DbValue = $row['nu_areaVincEscritProj'];
		$this->no_areaEscritProj->DbValue = $row['no_areaEscritProj'];
		$this->nu_fornecedorAuditoria->DbValue = $row['nu_fornecedorAuditoria'];
		$this->nu_fornPadraoFsw->DbValue = $row['nu_fornPadraoFsw'];
		$this->nu_contFornPadraoFsw->DbValue = $row['nu_contFornPadraoFsw'];
		$this->nu_itemContFornPadraoFsw->DbValue = $row['nu_itemContFornPadraoFsw'];
		$this->nu_pesoProbRisco->DbValue = $row['nu_pesoProbRisco'];
		$this->nu_pesoImpacRisco->DbValue = $row['nu_pesoImpacRisco'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_parametro")) <> "")
			$this->nu_parametro->CurrentValue = $this->getKey("nu_parametro"); // nu_parametro
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
		// nu_parametro

		$this->nu_parametro->CellCssStyle = "white-space: nowrap;";

		// nu_orgBase
		// nu_area
		// nu_usuarioRespAreaTi
		// qt_horasMes
		// nu_sistema
		// dt_inicioOpSistema
		// tx_htmlHomeNaoLogado
		// nu_orgMetricas
		// nu_areaMetricas
		// nu_fornMetricas
		// no_areaMetricas
		// nu_modeloMetricasPadrao
		// nu_areaVincEscritProj
		// no_areaEscritProj
		// nu_fornecedorAuditoria
		// nu_fornPadraoFsw
		// nu_contFornPadraoFsw
		// nu_itemContFornPadraoFsw
		// nu_pesoProbRisco
		// nu_pesoImpacRisco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_orgBase
			if (strval($this->nu_orgBase->CurrentValue) <> "") {
				$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_orgBase->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_orgBase, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_orgBase->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_orgBase->ViewValue = $this->nu_orgBase->CurrentValue;
				}
			} else {
				$this->nu_orgBase->ViewValue = NULL;
			}
			$this->nu_orgBase->ViewCustomAttributes = "";

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

			// nu_usuarioRespAreaTi
			if ($this->nu_usuarioRespAreaTi->VirtualValue <> "") {
				$this->nu_usuarioRespAreaTi->ViewValue = $this->nu_usuarioRespAreaTi->VirtualValue;
			} else {
			if (strval($this->nu_usuarioRespAreaTi->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioRespAreaTi->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioRespAreaTi, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioRespAreaTi->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioRespAreaTi->ViewValue = $this->nu_usuarioRespAreaTi->CurrentValue;
				}
			} else {
				$this->nu_usuarioRespAreaTi->ViewValue = NULL;
			}
			}
			$this->nu_usuarioRespAreaTi->ViewCustomAttributes = "";

			// qt_horasMes
			$this->qt_horasMes->ViewValue = $this->qt_horasMes->CurrentValue;
			$this->qt_horasMes->ViewCustomAttributes = "";

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

			// dt_inicioOpSistema
			$this->dt_inicioOpSistema->ViewValue = $this->dt_inicioOpSistema->CurrentValue;
			$this->dt_inicioOpSistema->ViewValue = ew_FormatDateTime($this->dt_inicioOpSistema->ViewValue, 7);
			$this->dt_inicioOpSistema->ViewCustomAttributes = "";

			// tx_htmlHomeNaoLogado
			$this->tx_htmlHomeNaoLogado->ViewValue = $this->tx_htmlHomeNaoLogado->CurrentValue;
			$this->tx_htmlHomeNaoLogado->ViewCustomAttributes = "";

			// nu_orgMetricas
			if (strval($this->nu_orgMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_organizacao]" . ew_SearchString("=", $this->nu_orgMetricas->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_orgMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_orgMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_orgMetricas->ViewValue = $this->nu_orgMetricas->CurrentValue;
				}
			} else {
				$this->nu_orgMetricas->ViewValue = NULL;
			}
			$this->nu_orgMetricas->ViewCustomAttributes = "";

			// nu_areaMetricas
			if ($this->nu_areaMetricas->VirtualValue <> "") {
				$this->nu_areaMetricas->ViewValue = $this->nu_areaMetricas->VirtualValue;
			} else {
			if (strval($this->nu_areaMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaMetricas->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_areaMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaMetricas->ViewValue = $this->nu_areaMetricas->CurrentValue;
				}
			} else {
				$this->nu_areaMetricas->ViewValue = NULL;
			}
			}
			$this->nu_areaMetricas->ViewCustomAttributes = "";

			// nu_fornMetricas
			if (strval($this->nu_fornMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornMetricas->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornMetricas->ViewValue = $this->nu_fornMetricas->CurrentValue;
				}
			} else {
				$this->nu_fornMetricas->ViewValue = NULL;
			}
			$this->nu_fornMetricas->ViewCustomAttributes = "";

			// no_areaMetricas
			$this->no_areaMetricas->ViewValue = $this->no_areaMetricas->CurrentValue;
			$this->no_areaMetricas->ViewCustomAttributes = "";

			// nu_modeloMetricasPadrao
			if ($this->nu_modeloMetricasPadrao->VirtualValue <> "") {
				$this->nu_modeloMetricasPadrao->ViewValue = $this->nu_modeloMetricasPadrao->VirtualValue;
			} else {
			if (strval($this->nu_modeloMetricasPadrao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_modeloMetricasPadrao->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_modeloMetricasPadrao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_modeloMetricasPadrao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_modeloMetricasPadrao->ViewValue = $this->nu_modeloMetricasPadrao->CurrentValue;
				}
			} else {
				$this->nu_modeloMetricasPadrao->ViewValue = NULL;
			}
			}
			$this->nu_modeloMetricasPadrao->ViewCustomAttributes = "";

			// nu_areaVincEscritProj
			if (strval($this->nu_areaVincEscritProj->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaVincEscritProj->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_areaVincEscritProj, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaVincEscritProj->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaVincEscritProj->ViewValue = $this->nu_areaVincEscritProj->CurrentValue;
				}
			} else {
				$this->nu_areaVincEscritProj->ViewValue = NULL;
			}
			$this->nu_areaVincEscritProj->ViewCustomAttributes = "";

			// no_areaEscritProj
			$this->no_areaEscritProj->ViewValue = $this->no_areaEscritProj->CurrentValue;
			$this->no_areaEscritProj->ViewCustomAttributes = "";

			// nu_fornecedorAuditoria
			if (strval($this->nu_fornecedorAuditoria->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedorAuditoria->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornecedorAuditoria, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornecedorAuditoria->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornecedorAuditoria->ViewValue = $this->nu_fornecedorAuditoria->CurrentValue;
				}
			} else {
				$this->nu_fornecedorAuditoria->ViewValue = NULL;
			}
			$this->nu_fornecedorAuditoria->ViewCustomAttributes = "";

			// nu_fornPadraoFsw
			if ($this->nu_fornPadraoFsw->VirtualValue <> "") {
				$this->nu_fornPadraoFsw->ViewValue = $this->nu_fornPadraoFsw->VirtualValue;
			} else {
			if (strval($this->nu_fornPadraoFsw->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornPadraoFsw->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_fornecedor], [no_fornecedor] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[fornecedor]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_fornPadraoFsw, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornPadraoFsw->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornPadraoFsw->ViewValue = $this->nu_fornPadraoFsw->CurrentValue;
				}
			} else {
				$this->nu_fornPadraoFsw->ViewValue = NULL;
			}
			}
			$this->nu_fornPadraoFsw->ViewCustomAttributes = "";

			// nu_contFornPadraoFsw
			if ($this->nu_contFornPadraoFsw->VirtualValue <> "") {
				$this->nu_contFornPadraoFsw->ViewValue = $this->nu_contFornPadraoFsw->VirtualValue;
			} else {
			if (strval($this->nu_contFornPadraoFsw->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contFornPadraoFsw->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contrato], [no_contrato] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contFornPadraoFsw, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_contrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contFornPadraoFsw->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contFornPadraoFsw->ViewValue = $this->nu_contFornPadraoFsw->CurrentValue;
				}
			} else {
				$this->nu_contFornPadraoFsw->ViewValue = NULL;
			}
			}
			$this->nu_contFornPadraoFsw->ViewCustomAttributes = "";

			// nu_itemContFornPadraoFsw
			if (strval($this->nu_itemContFornPadraoFsw->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContFornPadraoFsw->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemContratado], [no_itemContratado] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContFornPadraoFsw, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_itemContratado] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemContFornPadraoFsw->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_itemContFornPadraoFsw->ViewValue = $this->nu_itemContFornPadraoFsw->CurrentValue;
				}
			} else {
				$this->nu_itemContFornPadraoFsw->ViewValue = NULL;
			}
			$this->nu_itemContFornPadraoFsw->ViewCustomAttributes = "";

			// nu_pesoProbRisco
			$this->nu_pesoProbRisco->ViewValue = $this->nu_pesoProbRisco->CurrentValue;
			$this->nu_pesoProbRisco->ViewCustomAttributes = "";

			// nu_pesoImpacRisco
			$this->nu_pesoImpacRisco->ViewValue = $this->nu_pesoImpacRisco->CurrentValue;
			$this->nu_pesoImpacRisco->ViewCustomAttributes = "";

			// nu_orgBase
			$this->nu_orgBase->LinkCustomAttributes = "";
			$this->nu_orgBase->HrefValue = "";
			$this->nu_orgBase->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// nu_usuarioRespAreaTi
			$this->nu_usuarioRespAreaTi->LinkCustomAttributes = "";
			$this->nu_usuarioRespAreaTi->HrefValue = "";
			$this->nu_usuarioRespAreaTi->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// dt_inicioOpSistema
			$this->dt_inicioOpSistema->LinkCustomAttributes = "";
			$this->dt_inicioOpSistema->HrefValue = "";
			$this->dt_inicioOpSistema->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_pargerais\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pargerais',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fpargeraislist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "organizacao") {
			global $organizacao;
			$rsmaster = $organizacao->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $ExportDoc->Style;
				$ExportDoc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$organizacao->ExportDocument($ExportDoc, $rsmaster, 1, 1);
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
			if ($sMasterTblVar == "organizacao") {
				$bValidMaster = TRUE;
				if (@$_GET["nu_organizacao"] <> "") {
					$GLOBALS["organizacao"]->nu_organizacao->setQueryStringValue($_GET["nu_organizacao"]);
					$this->nu_orgBase->setQueryStringValue($GLOBALS["organizacao"]->nu_organizacao->QueryStringValue);
					$this->nu_orgBase->setSessionValue($this->nu_orgBase->QueryStringValue);
					if (!is_numeric($GLOBALS["organizacao"]->nu_organizacao->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "organizacao") {
				if ($this->nu_orgBase->QueryStringValue == "") $this->nu_orgBase->setSessionValue("");
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
if (!isset($pargerais_list)) $pargerais_list = new cpargerais_list();

// Page init
$pargerais_list->Page_Init();

// Page main
$pargerais_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pargerais_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($pargerais->Export == "") { ?>
<script type="text/javascript">

// Page object
var pargerais_list = new ew_Page("pargerais_list");
pargerais_list.PageID = "list"; // Page ID
var EW_PAGE_ID = pargerais_list.PageID; // For backward compatibility

// Form object
var fpargeraislist = new ew_Form("fpargeraislist");
fpargeraislist.FormKeyCountName = '<?php echo $pargerais_list->FormKeyCountName ?>';

// Form_CustomValidate event
fpargeraislist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpargeraislist.ValidateRequired = true;
<?php } else { ?>
fpargeraislist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpargeraislist.Lists["x_nu_orgBase"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraislist.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraislist.Lists["x_nu_usuarioRespAreaTi"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpargeraislist.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($pargerais->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($pargerais->getCurrentMasterTable() == "" && $pargerais_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $pargerais_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php if (($pargerais->Export == "") || (EW_EXPORT_MASTER_RECORD && $pargerais->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "organizacaolist.php";
if ($pargerais_list->DbMasterFilter <> "" && $pargerais->getCurrentMasterTable() == "organizacao") {
	if ($pargerais_list->MasterRecordExists) {
		if ($pargerais->getCurrentMasterTable() == $pargerais->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php if ($pargerais_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $pargerais_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php include_once "organizacaomaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$pargerais_list->TotalRecs = $pargerais->SelectRecordCount();
	} else {
		if ($pargerais_list->Recordset = $pargerais_list->LoadRecordset())
			$pargerais_list->TotalRecs = $pargerais_list->Recordset->RecordCount();
	}
	$pargerais_list->StartRec = 1;
	if ($pargerais_list->DisplayRecs <= 0 || ($pargerais->Export <> "" && $pargerais->ExportAll)) // Display all records
		$pargerais_list->DisplayRecs = $pargerais_list->TotalRecs;
	if (!($pargerais->Export <> "" && $pargerais->ExportAll))
		$pargerais_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$pargerais_list->Recordset = $pargerais_list->LoadRecordset($pargerais_list->StartRec-1, $pargerais_list->DisplayRecs);
$pargerais_list->RenderOtherOptions();
?>
<?php $pargerais_list->ShowPageHeader(); ?>
<?php
$pargerais_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="fpargeraislist" id="fpargeraislist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="pargerais">
<div id="gmp_pargerais" class="ewGridMiddlePanel">
<?php if ($pargerais_list->TotalRecs > 0) { ?>
<table id="tbl_pargeraislist" class="ewTable ewTableSeparate">
<?php echo $pargerais->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$pargerais_list->RenderListOptions();

// Render list options (header, left)
$pargerais_list->ListOptions->Render("header", "left");
?>
<?php if ($pargerais->nu_orgBase->Visible) { // nu_orgBase ?>
	<?php if ($pargerais->SortUrl($pargerais->nu_orgBase) == "") { ?>
		<td><div id="elh_pargerais_nu_orgBase" class="pargerais_nu_orgBase"><div class="ewTableHeaderCaption"><?php echo $pargerais->nu_orgBase->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pargerais->SortUrl($pargerais->nu_orgBase) ?>',2);"><div id="elh_pargerais_nu_orgBase" class="pargerais_nu_orgBase">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->nu_orgBase->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->nu_orgBase->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->nu_orgBase->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pargerais->nu_area->Visible) { // nu_area ?>
	<?php if ($pargerais->SortUrl($pargerais->nu_area) == "") { ?>
		<td><div id="elh_pargerais_nu_area" class="pargerais_nu_area"><div class="ewTableHeaderCaption"><?php echo $pargerais->nu_area->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pargerais->SortUrl($pargerais->nu_area) ?>',2);"><div id="elh_pargerais_nu_area" class="pargerais_nu_area">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->nu_area->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->nu_area->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->nu_area->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pargerais->nu_usuarioRespAreaTi->Visible) { // nu_usuarioRespAreaTi ?>
	<?php if ($pargerais->SortUrl($pargerais->nu_usuarioRespAreaTi) == "") { ?>
		<td><div id="elh_pargerais_nu_usuarioRespAreaTi" class="pargerais_nu_usuarioRespAreaTi"><div class="ewTableHeaderCaption"><?php echo $pargerais->nu_usuarioRespAreaTi->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pargerais->SortUrl($pargerais->nu_usuarioRespAreaTi) ?>',2);"><div id="elh_pargerais_nu_usuarioRespAreaTi" class="pargerais_nu_usuarioRespAreaTi">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->nu_usuarioRespAreaTi->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->nu_usuarioRespAreaTi->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->nu_usuarioRespAreaTi->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pargerais->nu_sistema->Visible) { // nu_sistema ?>
	<?php if ($pargerais->SortUrl($pargerais->nu_sistema) == "") { ?>
		<td><div id="elh_pargerais_nu_sistema" class="pargerais_nu_sistema"><div class="ewTableHeaderCaption"><?php echo $pargerais->nu_sistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pargerais->SortUrl($pargerais->nu_sistema) ?>',2);"><div id="elh_pargerais_nu_sistema" class="pargerais_nu_sistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->nu_sistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->nu_sistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->nu_sistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($pargerais->dt_inicioOpSistema->Visible) { // dt_inicioOpSistema ?>
	<?php if ($pargerais->SortUrl($pargerais->dt_inicioOpSistema) == "") { ?>
		<td><div id="elh_pargerais_dt_inicioOpSistema" class="pargerais_dt_inicioOpSistema"><div class="ewTableHeaderCaption"><?php echo $pargerais->dt_inicioOpSistema->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pargerais->SortUrl($pargerais->dt_inicioOpSistema) ?>',2);"><div id="elh_pargerais_dt_inicioOpSistema" class="pargerais_dt_inicioOpSistema">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pargerais->dt_inicioOpSistema->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pargerais->dt_inicioOpSistema->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pargerais->dt_inicioOpSistema->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pargerais_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($pargerais->ExportAll && $pargerais->Export <> "") {
	$pargerais_list->StopRec = $pargerais_list->TotalRecs;
} else {

	// Set the last record to display
	if ($pargerais_list->TotalRecs > $pargerais_list->StartRec + $pargerais_list->DisplayRecs - 1)
		$pargerais_list->StopRec = $pargerais_list->StartRec + $pargerais_list->DisplayRecs - 1;
	else
		$pargerais_list->StopRec = $pargerais_list->TotalRecs;
}
$pargerais_list->RecCnt = $pargerais_list->StartRec - 1;
if ($pargerais_list->Recordset && !$pargerais_list->Recordset->EOF) {
	$pargerais_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $pargerais_list->StartRec > 1)
		$pargerais_list->Recordset->Move($pargerais_list->StartRec - 1);
} elseif (!$pargerais->AllowAddDeleteRow && $pargerais_list->StopRec == 0) {
	$pargerais_list->StopRec = $pargerais->GridAddRowCount;
}

// Initialize aggregate
$pargerais->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pargerais->ResetAttrs();
$pargerais_list->RenderRow();
while ($pargerais_list->RecCnt < $pargerais_list->StopRec) {
	$pargerais_list->RecCnt++;
	if (intval($pargerais_list->RecCnt) >= intval($pargerais_list->StartRec)) {
		$pargerais_list->RowCnt++;

		// Set up key count
		$pargerais_list->KeyCount = $pargerais_list->RowIndex;

		// Init row class and style
		$pargerais->ResetAttrs();
		$pargerais->CssClass = "";
		if ($pargerais->CurrentAction == "gridadd") {
		} else {
			$pargerais_list->LoadRowValues($pargerais_list->Recordset); // Load row values
		}
		$pargerais->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$pargerais->RowAttrs = array_merge($pargerais->RowAttrs, array('data-rowindex'=>$pargerais_list->RowCnt, 'id'=>'r' . $pargerais_list->RowCnt . '_pargerais', 'data-rowtype'=>$pargerais->RowType));

		// Render row
		$pargerais_list->RenderRow();

		// Render list options
		$pargerais_list->RenderListOptions();
?>
	<tr<?php echo $pargerais->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pargerais_list->ListOptions->Render("body", "left", $pargerais_list->RowCnt);
?>
	<?php if ($pargerais->nu_orgBase->Visible) { // nu_orgBase ?>
		<td<?php echo $pargerais->nu_orgBase->CellAttributes() ?>>
<span<?php echo $pargerais->nu_orgBase->ViewAttributes() ?>>
<?php echo $pargerais->nu_orgBase->ListViewValue() ?></span>
<a id="<?php echo $pargerais_list->PageObjName . "_row_" . $pargerais_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pargerais->nu_area->Visible) { // nu_area ?>
		<td<?php echo $pargerais->nu_area->CellAttributes() ?>>
<span<?php echo $pargerais->nu_area->ViewAttributes() ?>>
<?php echo $pargerais->nu_area->ListViewValue() ?></span>
<a id="<?php echo $pargerais_list->PageObjName . "_row_" . $pargerais_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pargerais->nu_usuarioRespAreaTi->Visible) { // nu_usuarioRespAreaTi ?>
		<td<?php echo $pargerais->nu_usuarioRespAreaTi->CellAttributes() ?>>
<span<?php echo $pargerais->nu_usuarioRespAreaTi->ViewAttributes() ?>>
<?php echo $pargerais->nu_usuarioRespAreaTi->ListViewValue() ?></span>
<a id="<?php echo $pargerais_list->PageObjName . "_row_" . $pargerais_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pargerais->nu_sistema->Visible) { // nu_sistema ?>
		<td<?php echo $pargerais->nu_sistema->CellAttributes() ?>>
<span<?php echo $pargerais->nu_sistema->ViewAttributes() ?>>
<?php echo $pargerais->nu_sistema->ListViewValue() ?></span>
<a id="<?php echo $pargerais_list->PageObjName . "_row_" . $pargerais_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pargerais->dt_inicioOpSistema->Visible) { // dt_inicioOpSistema ?>
		<td<?php echo $pargerais->dt_inicioOpSistema->CellAttributes() ?>>
<span<?php echo $pargerais->dt_inicioOpSistema->ViewAttributes() ?>>
<?php echo $pargerais->dt_inicioOpSistema->ListViewValue() ?></span>
<a id="<?php echo $pargerais_list->PageObjName . "_row_" . $pargerais_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$pargerais_list->ListOptions->Render("body", "right", $pargerais_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($pargerais->CurrentAction <> "gridadd")
		$pargerais_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($pargerais->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($pargerais_list->Recordset)
	$pargerais_list->Recordset->Close();
?>
<?php if ($pargerais->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($pargerais->CurrentAction <> "gridadd" && $pargerais->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($pargerais_list->Pager)) $pargerais_list->Pager = new cNumericPager($pargerais_list->StartRec, $pargerais_list->DisplayRecs, $pargerais_list->TotalRecs, $pargerais_list->RecRange) ?>
<?php if ($pargerais_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($pargerais_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $pargerais_list->PageUrl() ?>start=<?php echo $pargerais_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($pargerais_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $pargerais_list->PageUrl() ?>start=<?php echo $pargerais_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($pargerais_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $pargerais_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($pargerais_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $pargerais_list->PageUrl() ?>start=<?php echo $pargerais_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($pargerais_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $pargerais_list->PageUrl() ?>start=<?php echo $pargerais_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($pargerais_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pargerais_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pargerais_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pargerais_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($pargerais_list->SearchWhere == "0=101") { ?>
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
	foreach ($pargerais_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($pargerais->Export == "") { ?>
<script type="text/javascript">
fpargeraislist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$pargerais_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pargerais->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$pargerais_list->Page_Terminate();
?>
