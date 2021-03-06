<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tbrdm_usersinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tbrdm_users_list = NULL; // Initialize page object first

class ctbrdm_users_list extends ctbrdm_users {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'tbrdm_users';

	// Page object name
	var $PageObjName = 'tbrdm_users_list';

	// Grid form hidden field names
	var $FormName = 'ftbrdm_userslist';
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

		// Table object (tbrdm_users)
		if (!isset($GLOBALS["tbrdm_users"])) {
			$GLOBALS["tbrdm_users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tbrdm_users"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "tbrdm_usersadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "tbrdm_usersdelete.php";
		$this->MultiUpdateUrl = "tbrdm_usersupdate.php";

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tbrdm_users', TRUE);

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
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
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
			$this->UpdateSort($this->id, $bCtrl); // id
			$this->UpdateSort($this->_login, $bCtrl); // login
			$this->UpdateSort($this->mail, $bCtrl); // mail
			$this->UpdateSort($this->admin, $bCtrl); // admin
			$this->UpdateSort($this->status, $bCtrl); // status
			$this->UpdateSort($this->last_login_on, $bCtrl); // last_login_on
			$this->UpdateSort($this->_language, $bCtrl); // language
			$this->UpdateSort($this->created_on, $bCtrl); // created_on
			$this->UpdateSort($this->updated_on, $bCtrl); // updated_on
			$this->UpdateSort($this->type, $bCtrl); // type
			$this->UpdateSort($this->identity_url, $bCtrl); // identity_url
			$this->UpdateSort($this->mail_notification, $bCtrl); // mail_notification
			$this->UpdateSort($this->name, $bCtrl); // name
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
				$this->id->setSort("");
				$this->_login->setSort("");
				$this->mail->setSort("");
				$this->admin->setSort("");
				$this->status->setSort("");
				$this->last_login_on->setSort("");
				$this->_language->setSort("");
				$this->created_on->setSort("");
				$this->updated_on->setSort("");
				$this->type->setSort("");
				$this->identity_url->setSort("");
				$this->mail_notification->setSort("");
				$this->name->setSort("");
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.ftbrdm_userslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$this->id->setDbValue($rs->fields('id'));
		$this->_login->setDbValue($rs->fields('login'));
		$this->mail->setDbValue($rs->fields('mail'));
		$this->admin->setDbValue($rs->fields('admin'));
		$this->status->setDbValue($rs->fields('status'));
		$this->last_login_on->setDbValue($rs->fields('last_login_on'));
		$this->_language->setDbValue($rs->fields('language'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->type->setDbValue($rs->fields('type'));
		$this->identity_url->setDbValue($rs->fields('identity_url'));
		$this->mail_notification->setDbValue($rs->fields('mail_notification'));
		$this->name->setDbValue($rs->fields('name'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->_login->DbValue = $row['login'];
		$this->mail->DbValue = $row['mail'];
		$this->admin->DbValue = $row['admin'];
		$this->status->DbValue = $row['status'];
		$this->last_login_on->DbValue = $row['last_login_on'];
		$this->_language->DbValue = $row['language'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
		$this->type->DbValue = $row['type'];
		$this->identity_url->DbValue = $row['identity_url'];
		$this->mail_notification->DbValue = $row['mail_notification'];
		$this->name->DbValue = $row['name'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		// id
		// login
		// mail
		// admin
		// status
		// last_login_on
		// language
		// created_on
		// updated_on
		// type
		// identity_url
		// mail_notification
		// name

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// login
			$this->_login->ViewValue = $this->_login->CurrentValue;
			$this->_login->ViewCustomAttributes = "";

			// mail
			$this->mail->ViewValue = $this->mail->CurrentValue;
			$this->mail->ViewCustomAttributes = "";

			// admin
			$this->admin->ViewValue = $this->admin->CurrentValue;
			$this->admin->ViewCustomAttributes = "";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->ViewCustomAttributes = "";

			// last_login_on
			$this->last_login_on->ViewValue = $this->last_login_on->CurrentValue;
			$this->last_login_on->ViewValue = ew_FormatDateTime($this->last_login_on->ViewValue, 7);
			$this->last_login_on->ViewCustomAttributes = "";

			// language
			$this->_language->ViewValue = $this->_language->CurrentValue;
			$this->_language->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 7);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 7);
			$this->updated_on->ViewCustomAttributes = "";

			// type
			$this->type->ViewValue = $this->type->CurrentValue;
			$this->type->ViewCustomAttributes = "";

			// identity_url
			$this->identity_url->ViewValue = $this->identity_url->CurrentValue;
			$this->identity_url->ViewCustomAttributes = "";

			// mail_notification
			$this->mail_notification->ViewValue = $this->mail_notification->CurrentValue;
			$this->mail_notification->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// login
			$this->_login->LinkCustomAttributes = "";
			$this->_login->HrefValue = "";
			$this->_login->TooltipValue = "";

			// mail
			$this->mail->LinkCustomAttributes = "";
			$this->mail->HrefValue = "";
			$this->mail->TooltipValue = "";

			// admin
			$this->admin->LinkCustomAttributes = "";
			$this->admin->HrefValue = "";
			$this->admin->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// last_login_on
			$this->last_login_on->LinkCustomAttributes = "";
			$this->last_login_on->HrefValue = "";
			$this->last_login_on->TooltipValue = "";

			// language
			$this->_language->LinkCustomAttributes = "";
			$this->_language->HrefValue = "";
			$this->_language->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// identity_url
			$this->identity_url->LinkCustomAttributes = "";
			$this->identity_url->HrefValue = "";
			$this->identity_url->TooltipValue = "";

			// mail_notification
			$this->mail_notification->LinkCustomAttributes = "";
			$this->mail_notification->HrefValue = "";
			$this->mail_notification->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_tbrdm_users\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_tbrdm_users',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ftbrdm_userslist,sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
if (!isset($tbrdm_users_list)) $tbrdm_users_list = new ctbrdm_users_list();

// Page init
$tbrdm_users_list->Page_Init();

// Page main
$tbrdm_users_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tbrdm_users_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($tbrdm_users->Export == "") { ?>
<script type="text/javascript">

// Page object
var tbrdm_users_list = new ew_Page("tbrdm_users_list");
tbrdm_users_list.PageID = "list"; // Page ID
var EW_PAGE_ID = tbrdm_users_list.PageID; // For backward compatibility

// Form object
var ftbrdm_userslist = new ew_Form("ftbrdm_userslist");
ftbrdm_userslist.FormKeyCountName = '<?php echo $tbrdm_users_list->FormKeyCountName ?>';

// Form_CustomValidate event
ftbrdm_userslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftbrdm_userslist.ValidateRequired = true;
<?php } else { ?>
ftbrdm_userslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($tbrdm_users->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($tbrdm_users_list->ExportOptions->Visible()) { ?>
<div class="ewListExportOptions"><?php $tbrdm_users_list->ExportOptions->Render("body") ?></div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tbrdm_users_list->TotalRecs = $tbrdm_users->SelectRecordCount();
	} else {
		if ($tbrdm_users_list->Recordset = $tbrdm_users_list->LoadRecordset())
			$tbrdm_users_list->TotalRecs = $tbrdm_users_list->Recordset->RecordCount();
	}
	$tbrdm_users_list->StartRec = 1;
	if ($tbrdm_users_list->DisplayRecs <= 0 || ($tbrdm_users->Export <> "" && $tbrdm_users->ExportAll)) // Display all records
		$tbrdm_users_list->DisplayRecs = $tbrdm_users_list->TotalRecs;
	if (!($tbrdm_users->Export <> "" && $tbrdm_users->ExportAll))
		$tbrdm_users_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tbrdm_users_list->Recordset = $tbrdm_users_list->LoadRecordset($tbrdm_users_list->StartRec-1, $tbrdm_users_list->DisplayRecs);
$tbrdm_users_list->RenderOtherOptions();
?>
<?php $tbrdm_users_list->ShowPageHeader(); ?>
<?php
$tbrdm_users_list->ShowMessage();
?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<form name="ftbrdm_userslist" id="ftbrdm_userslist" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tbrdm_users">
<div id="gmp_tbrdm_users" class="ewGridMiddlePanel">
<?php if ($tbrdm_users_list->TotalRecs > 0) { ?>
<table id="tbl_tbrdm_userslist" class="ewTable ewTableSeparate">
<?php echo $tbrdm_users->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tbrdm_users_list->RenderListOptions();

// Render list options (header, left)
$tbrdm_users_list->ListOptions->Render("header", "left");
?>
<?php if ($tbrdm_users->id->Visible) { // id ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->id) == "") { ?>
		<td><div id="elh_tbrdm_users_id" class="tbrdm_users_id"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->id->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->id) ?>',2);"><div id="elh_tbrdm_users_id" class="tbrdm_users_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->_login->Visible) { // login ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->_login) == "") { ?>
		<td><div id="elh_tbrdm_users__login" class="tbrdm_users__login"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->_login->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->_login) ?>',2);"><div id="elh_tbrdm_users__login" class="tbrdm_users__login">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->_login->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->_login->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->_login->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->mail->Visible) { // mail ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->mail) == "") { ?>
		<td><div id="elh_tbrdm_users_mail" class="tbrdm_users_mail"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->mail->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->mail) ?>',2);"><div id="elh_tbrdm_users_mail" class="tbrdm_users_mail">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->mail->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->mail->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->mail->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->admin->Visible) { // admin ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->admin) == "") { ?>
		<td><div id="elh_tbrdm_users_admin" class="tbrdm_users_admin"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->admin->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->admin) ?>',2);"><div id="elh_tbrdm_users_admin" class="tbrdm_users_admin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->admin->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->admin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->admin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->status->Visible) { // status ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->status) == "") { ?>
		<td><div id="elh_tbrdm_users_status" class="tbrdm_users_status"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->status->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->status) ?>',2);"><div id="elh_tbrdm_users_status" class="tbrdm_users_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->last_login_on->Visible) { // last_login_on ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->last_login_on) == "") { ?>
		<td><div id="elh_tbrdm_users_last_login_on" class="tbrdm_users_last_login_on"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->last_login_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->last_login_on) ?>',2);"><div id="elh_tbrdm_users_last_login_on" class="tbrdm_users_last_login_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->last_login_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->last_login_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->last_login_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->_language->Visible) { // language ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->_language) == "") { ?>
		<td><div id="elh_tbrdm_users__language" class="tbrdm_users__language"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->_language->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->_language) ?>',2);"><div id="elh_tbrdm_users__language" class="tbrdm_users__language">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->_language->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->_language->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->_language->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->created_on->Visible) { // created_on ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->created_on) == "") { ?>
		<td><div id="elh_tbrdm_users_created_on" class="tbrdm_users_created_on"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->created_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->created_on) ?>',2);"><div id="elh_tbrdm_users_created_on" class="tbrdm_users_created_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->created_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->created_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->created_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->updated_on->Visible) { // updated_on ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->updated_on) == "") { ?>
		<td><div id="elh_tbrdm_users_updated_on" class="tbrdm_users_updated_on"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->updated_on->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->updated_on) ?>',2);"><div id="elh_tbrdm_users_updated_on" class="tbrdm_users_updated_on">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->updated_on->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->updated_on->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->updated_on->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->type->Visible) { // type ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->type) == "") { ?>
		<td><div id="elh_tbrdm_users_type" class="tbrdm_users_type"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->type->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->type) ?>',2);"><div id="elh_tbrdm_users_type" class="tbrdm_users_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->identity_url->Visible) { // identity_url ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->identity_url) == "") { ?>
		<td><div id="elh_tbrdm_users_identity_url" class="tbrdm_users_identity_url"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->identity_url->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->identity_url) ?>',2);"><div id="elh_tbrdm_users_identity_url" class="tbrdm_users_identity_url">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->identity_url->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->identity_url->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->identity_url->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->mail_notification->Visible) { // mail_notification ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->mail_notification) == "") { ?>
		<td><div id="elh_tbrdm_users_mail_notification" class="tbrdm_users_mail_notification"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->mail_notification->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->mail_notification) ?>',2);"><div id="elh_tbrdm_users_mail_notification" class="tbrdm_users_mail_notification">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->mail_notification->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->mail_notification->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->mail_notification->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php if ($tbrdm_users->name->Visible) { // name ?>
	<?php if ($tbrdm_users->SortUrl($tbrdm_users->name) == "") { ?>
		<td><div id="elh_tbrdm_users_name" class="tbrdm_users_name"><div class="ewTableHeaderCaption"><?php echo $tbrdm_users->name->FldCaption() ?></div></div></td>
	<?php } else { ?>
		<td><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $tbrdm_users->SortUrl($tbrdm_users->name) ?>',2);"><div id="elh_tbrdm_users_name" class="tbrdm_users_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $tbrdm_users->name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($tbrdm_users->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($tbrdm_users->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></td>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tbrdm_users_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tbrdm_users->ExportAll && $tbrdm_users->Export <> "") {
	$tbrdm_users_list->StopRec = $tbrdm_users_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tbrdm_users_list->TotalRecs > $tbrdm_users_list->StartRec + $tbrdm_users_list->DisplayRecs - 1)
		$tbrdm_users_list->StopRec = $tbrdm_users_list->StartRec + $tbrdm_users_list->DisplayRecs - 1;
	else
		$tbrdm_users_list->StopRec = $tbrdm_users_list->TotalRecs;
}
$tbrdm_users_list->RecCnt = $tbrdm_users_list->StartRec - 1;
if ($tbrdm_users_list->Recordset && !$tbrdm_users_list->Recordset->EOF) {
	$tbrdm_users_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $tbrdm_users_list->StartRec > 1)
		$tbrdm_users_list->Recordset->Move($tbrdm_users_list->StartRec - 1);
} elseif (!$tbrdm_users->AllowAddDeleteRow && $tbrdm_users_list->StopRec == 0) {
	$tbrdm_users_list->StopRec = $tbrdm_users->GridAddRowCount;
}

// Initialize aggregate
$tbrdm_users->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tbrdm_users->ResetAttrs();
$tbrdm_users_list->RenderRow();
while ($tbrdm_users_list->RecCnt < $tbrdm_users_list->StopRec) {
	$tbrdm_users_list->RecCnt++;
	if (intval($tbrdm_users_list->RecCnt) >= intval($tbrdm_users_list->StartRec)) {
		$tbrdm_users_list->RowCnt++;

		// Set up key count
		$tbrdm_users_list->KeyCount = $tbrdm_users_list->RowIndex;

		// Init row class and style
		$tbrdm_users->ResetAttrs();
		$tbrdm_users->CssClass = "";
		if ($tbrdm_users->CurrentAction == "gridadd") {
		} else {
			$tbrdm_users_list->LoadRowValues($tbrdm_users_list->Recordset); // Load row values
		}
		$tbrdm_users->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tbrdm_users->RowAttrs = array_merge($tbrdm_users->RowAttrs, array('data-rowindex'=>$tbrdm_users_list->RowCnt, 'id'=>'r' . $tbrdm_users_list->RowCnt . '_tbrdm_users', 'data-rowtype'=>$tbrdm_users->RowType));

		// Render row
		$tbrdm_users_list->RenderRow();

		// Render list options
		$tbrdm_users_list->RenderListOptions();
?>
	<tr<?php echo $tbrdm_users->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tbrdm_users_list->ListOptions->Render("body", "left", $tbrdm_users_list->RowCnt);
?>
	<?php if ($tbrdm_users->id->Visible) { // id ?>
		<td<?php echo $tbrdm_users->id->CellAttributes() ?>>
<span<?php echo $tbrdm_users->id->ViewAttributes() ?>>
<?php echo $tbrdm_users->id->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->_login->Visible) { // login ?>
		<td<?php echo $tbrdm_users->_login->CellAttributes() ?>>
<span<?php echo $tbrdm_users->_login->ViewAttributes() ?>>
<?php echo $tbrdm_users->_login->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->mail->Visible) { // mail ?>
		<td<?php echo $tbrdm_users->mail->CellAttributes() ?>>
<span<?php echo $tbrdm_users->mail->ViewAttributes() ?>>
<?php echo $tbrdm_users->mail->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->admin->Visible) { // admin ?>
		<td<?php echo $tbrdm_users->admin->CellAttributes() ?>>
<span<?php echo $tbrdm_users->admin->ViewAttributes() ?>>
<?php echo $tbrdm_users->admin->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->status->Visible) { // status ?>
		<td<?php echo $tbrdm_users->status->CellAttributes() ?>>
<span<?php echo $tbrdm_users->status->ViewAttributes() ?>>
<?php echo $tbrdm_users->status->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->last_login_on->Visible) { // last_login_on ?>
		<td<?php echo $tbrdm_users->last_login_on->CellAttributes() ?>>
<span<?php echo $tbrdm_users->last_login_on->ViewAttributes() ?>>
<?php echo $tbrdm_users->last_login_on->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->_language->Visible) { // language ?>
		<td<?php echo $tbrdm_users->_language->CellAttributes() ?>>
<span<?php echo $tbrdm_users->_language->ViewAttributes() ?>>
<?php echo $tbrdm_users->_language->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->created_on->Visible) { // created_on ?>
		<td<?php echo $tbrdm_users->created_on->CellAttributes() ?>>
<span<?php echo $tbrdm_users->created_on->ViewAttributes() ?>>
<?php echo $tbrdm_users->created_on->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->updated_on->Visible) { // updated_on ?>
		<td<?php echo $tbrdm_users->updated_on->CellAttributes() ?>>
<span<?php echo $tbrdm_users->updated_on->ViewAttributes() ?>>
<?php echo $tbrdm_users->updated_on->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->type->Visible) { // type ?>
		<td<?php echo $tbrdm_users->type->CellAttributes() ?>>
<span<?php echo $tbrdm_users->type->ViewAttributes() ?>>
<?php echo $tbrdm_users->type->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->identity_url->Visible) { // identity_url ?>
		<td<?php echo $tbrdm_users->identity_url->CellAttributes() ?>>
<span<?php echo $tbrdm_users->identity_url->ViewAttributes() ?>>
<?php echo $tbrdm_users->identity_url->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->mail_notification->Visible) { // mail_notification ?>
		<td<?php echo $tbrdm_users->mail_notification->CellAttributes() ?>>
<span<?php echo $tbrdm_users->mail_notification->ViewAttributes() ?>>
<?php echo $tbrdm_users->mail_notification->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($tbrdm_users->name->Visible) { // name ?>
		<td<?php echo $tbrdm_users->name->CellAttributes() ?>>
<span<?php echo $tbrdm_users->name->ViewAttributes() ?>>
<?php echo $tbrdm_users->name->ListViewValue() ?></span>
<a id="<?php echo $tbrdm_users_list->PageObjName . "_row_" . $tbrdm_users_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tbrdm_users_list->ListOptions->Render("body", "right", $tbrdm_users_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tbrdm_users->CurrentAction <> "gridadd")
		$tbrdm_users_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($tbrdm_users->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($tbrdm_users_list->Recordset)
	$tbrdm_users_list->Recordset->Close();
?>
<?php if ($tbrdm_users->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($tbrdm_users->CurrentAction <> "gridadd" && $tbrdm_users->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager">
<tr><td>
<?php if (!isset($tbrdm_users_list->Pager)) $tbrdm_users_list->Pager = new cNumericPager($tbrdm_users_list->StartRec, $tbrdm_users_list->DisplayRecs, $tbrdm_users_list->TotalRecs, $tbrdm_users_list->RecRange) ?>
<?php if ($tbrdm_users_list->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($tbrdm_users_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $tbrdm_users_list->PageUrl() ?>start=<?php echo $tbrdm_users_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($tbrdm_users_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $tbrdm_users_list->PageUrl() ?>start=<?php echo $tbrdm_users_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($tbrdm_users_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $tbrdm_users_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($tbrdm_users_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $tbrdm_users_list->PageUrl() ?>start=<?php echo $tbrdm_users_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($tbrdm_users_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $tbrdm_users_list->PageUrl() ?>start=<?php echo $tbrdm_users_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
<td>
	<?php if ($tbrdm_users_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tbrdm_users_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tbrdm_users_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tbrdm_users_list->Pager->RecordCount ?>
</td>
</tr></tbody></table>
<?php } else { ?>
	<?php if ($Security->CanList()) { ?>
	<?php if ($tbrdm_users_list->SearchWhere == "0=101") { ?>
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
	foreach ($tbrdm_users_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
</div>
<?php } ?>
</td></tr></table>
<?php if ($tbrdm_users->Export == "") { ?>
<script type="text/javascript">
ftbrdm_userslist.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php } ?>
<?php
$tbrdm_users_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($tbrdm_users->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$tbrdm_users_list->Page_Terminate();
?>
