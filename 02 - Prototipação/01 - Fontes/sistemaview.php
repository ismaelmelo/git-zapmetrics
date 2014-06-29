<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "sistemainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "atorgridcls.php" ?>
<?php include_once "modulogridcls.php" ?>
<?php include_once "ucgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$sistema_view = NULL; // Initialize page object first

class csistema_view extends csistema {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'sistema';

	// Page object name
	var $PageObjName = 'sistema_view';

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

		// Table object (sistema)
		if (!isset($GLOBALS["sistema"])) {
			$GLOBALS["sistema"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sistema"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_sistema"] <> "") {
			$this->RecKey["nu_sistema"] = $_GET["nu_sistema"];
			$KeyUrl .= "&nu_sistema=" . urlencode($this->RecKey["nu_sistema"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sistema', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "span";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "span";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("sistemalist.php");
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
		if (@$_GET["nu_sistema"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_sistema"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["nu_sistema"] <> "") {
				$this->nu_sistema->setQueryStringValue($_GET["nu_sistema"]);
				$this->RecKey["nu_sistema"] = $this->nu_sistema->QueryStringValue;
			} else {
				$sReturnUrl = "sistemalist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "sistemalist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "sistemalist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());
		$DetailTableLink = "";
		$option = &$options["detail"];

		// Detail table 'ator'
		$body = $Language->TablePhrase("ator", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("atorlist.php?" . EW_TABLE_SHOW_MASTER . "=sistema&nu_sistema=" . strval($this->nu_sistema->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_ator");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'ator');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "ator";
		}

		// Detail table 'modulo'
		$body = $Language->TablePhrase("modulo", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("modulolist.php?" . EW_TABLE_SHOW_MASTER . "=sistema&nu_sistema=" . strval($this->nu_sistema->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_modulo");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'modulo');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "modulo";
		}

		// Detail table 'uc'
		$body = $Language->TablePhrase("uc", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("uclist.php?" . EW_TABLE_SHOW_MASTER . "=sistema&nu_sistema=" . strval($this->nu_sistema->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_uc");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'uc');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "uc";
		}

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<a class=\"ewAction ewDetailView\" data-action=\"view\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink)) . "\">" . $body . "</a>";
			$item = &$option->Add("details");
			$item->Body = $body;
			$item->Visible = ($DetailTableLink <> "");

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detail_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
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
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->no_sistema->setDbValue($rs->fields('no_sistema'));
		$this->ds_sistema->setDbValue($rs->fields('ds_sistema'));
		$this->nu_fornecedor->setDbValue($rs->fields('nu_fornecedor'));
		$this->ds_contatos->setDbValue($rs->fields('ds_contatos'));
		$this->ds_comentarios->setDbValue($rs->fields('ds_comentarios'));
		$this->nu_stSistema->setDbValue($rs->fields('nu_stSistema'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->no_sistema->DbValue = $row['no_sistema'];
		$this->ds_sistema->DbValue = $row['ds_sistema'];
		$this->nu_fornecedor->DbValue = $row['nu_fornecedor'];
		$this->ds_contatos->DbValue = $row['ds_contatos'];
		$this->ds_comentarios->DbValue = $row['ds_comentarios'];
		$this->nu_stSistema->DbValue = $row['nu_stSistema'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_sistema
		// co_alternativo
		// no_sistema
		// ds_sistema
		// nu_fornecedor
		// ds_contatos
		// ds_comentarios
		// nu_stSistema
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// no_sistema
			$this->no_sistema->ViewValue = $this->no_sistema->CurrentValue;
			$this->no_sistema->ViewCustomAttributes = "";

			// ds_sistema
			$this->ds_sistema->ViewValue = $this->ds_sistema->CurrentValue;
			$this->ds_sistema->ViewCustomAttributes = "";

			// nu_fornecedor
			if (strval($this->nu_fornecedor->CurrentValue) <> "") {
				$sFilterWrk = "[nu_fornecedor]" . ew_SearchString("=", $this->nu_fornecedor->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_fornecedor, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_fornecedor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_fornecedor->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_fornecedor->ViewValue = $this->nu_fornecedor->CurrentValue;
				}
			} else {
				$this->nu_fornecedor->ViewValue = NULL;
			}
			$this->nu_fornecedor->ViewCustomAttributes = "";

			// ds_contatos
			$this->ds_contatos->ViewValue = $this->ds_contatos->CurrentValue;
			$this->ds_contatos->ViewCustomAttributes = "";

			// ds_comentarios
			$this->ds_comentarios->ViewValue = $this->ds_comentarios->CurrentValue;
			$this->ds_comentarios->ViewCustomAttributes = "";

			// nu_stSistema
			if (strval($this->nu_stSistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stSistema]" . ew_SearchString("=", $this->nu_stSistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stSistema], [no_stSistema] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stsistema]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
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

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// no_sistema
			$this->no_sistema->LinkCustomAttributes = "";
			$this->no_sistema->HrefValue = "";
			$this->no_sistema->TooltipValue = "";

			// ds_sistema
			$this->ds_sistema->LinkCustomAttributes = "";
			$this->ds_sistema->HrefValue = "";
			$this->ds_sistema->TooltipValue = "";

			// nu_fornecedor
			$this->nu_fornecedor->LinkCustomAttributes = "";
			$this->nu_fornecedor->HrefValue = "";
			$this->nu_fornecedor->TooltipValue = "";

			// ds_contatos
			$this->ds_contatos->LinkCustomAttributes = "";
			$this->ds_contatos->HrefValue = "";
			$this->ds_contatos->TooltipValue = "";

			// ds_comentarios
			$this->ds_comentarios->LinkCustomAttributes = "";
			$this->ds_comentarios->HrefValue = "";
			$this->ds_comentarios->TooltipValue = "";

			// nu_stSistema
			$this->nu_stSistema->LinkCustomAttributes = "";
			$this->nu_stSistema->HrefValue = "";
			$this->nu_stSistema->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";
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
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$item->Body = "<a id=\"emf_sistema\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_sistema',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fsistemaview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = FALSE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$ExportDoc = ew_ExportDocument($this, "v");
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
		$this->ExportDocument($ExportDoc, $rs, $StartRec, $StopRec, "view");
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

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);
		return $sQry;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("ator", $DetailTblVar)) {
				if (!isset($GLOBALS["ator_grid"]))
					$GLOBALS["ator_grid"] = new cator_grid;
				if ($GLOBALS["ator_grid"]->DetailView) {
					$GLOBALS["ator_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["ator_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["ator_grid"]->setStartRecordNumber(1);
					$GLOBALS["ator_grid"]->nu_sistema->FldIsDetailKey = TRUE;
					$GLOBALS["ator_grid"]->nu_sistema->CurrentValue = $this->nu_sistema->CurrentValue;
					$GLOBALS["ator_grid"]->nu_sistema->setSessionValue($GLOBALS["ator_grid"]->nu_sistema->CurrentValue);
				}
			}
			if (in_array("modulo", $DetailTblVar)) {
				if (!isset($GLOBALS["modulo_grid"]))
					$GLOBALS["modulo_grid"] = new cmodulo_grid;
				if ($GLOBALS["modulo_grid"]->DetailView) {
					$GLOBALS["modulo_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["modulo_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["modulo_grid"]->setStartRecordNumber(1);
					$GLOBALS["modulo_grid"]->nu_sistema->FldIsDetailKey = TRUE;
					$GLOBALS["modulo_grid"]->nu_sistema->CurrentValue = $this->nu_sistema->CurrentValue;
					$GLOBALS["modulo_grid"]->nu_sistema->setSessionValue($GLOBALS["modulo_grid"]->nu_sistema->CurrentValue);
				}
			}
			if (in_array("uc", $DetailTblVar)) {
				if (!isset($GLOBALS["uc_grid"]))
					$GLOBALS["uc_grid"] = new cuc_grid;
				if ($GLOBALS["uc_grid"]->DetailView) {
					$GLOBALS["uc_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["uc_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["uc_grid"]->setStartRecordNumber(1);
					$GLOBALS["uc_grid"]->nu_sistema->FldIsDetailKey = TRUE;
					$GLOBALS["uc_grid"]->nu_sistema->CurrentValue = $this->nu_sistema->CurrentValue;
					$GLOBALS["uc_grid"]->nu_sistema->setSessionValue($GLOBALS["uc_grid"]->nu_sistema->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "sistemalist.php", $this->TableVar);
		$PageCaption = $Language->Phrase("view");
		$Breadcrumb->Add("view", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($sistema_view)) $sistema_view = new csistema_view();

// Page init
$sistema_view->Page_Init();

// Page main
$sistema_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sistema_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($sistema->Export == "") { ?>
<script type="text/javascript">

// Page object
var sistema_view = new ew_Page("sistema_view");
sistema_view.PageID = "view"; // Page ID
var EW_PAGE_ID = sistema_view.PageID; // For backward compatibility

// Form object
var fsistemaview = new ew_Form("fsistemaview");

// Form_CustomValidate event
fsistemaview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsistemaview.ValidateRequired = true;
<?php } else { ?>
fsistemaview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsistemaview.Lists["x_nu_fornecedor"] = {"LinkField":"x_nu_fornecedor","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_fornecedor","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsistemaview.Lists["x_nu_stSistema"] = {"LinkField":"x_nu_stSistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stSistema","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($sistema->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($sistema->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $sistema_view->ExportOptions->Render("body") ?>
<?php if (!$sistema_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($sistema_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $sistema_view->ShowPageHeader(); ?>
<?php
$sistema_view->ShowMessage();
?>
<form name="fsistemaview" id="fsistemaview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="sistema">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_sistemaview" class="table table-bordered table-striped">
<?php if ($sistema->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_sistema_co_alternativo"><?php echo $sistema->co_alternativo->FldCaption() ?></span></td>
		<td<?php echo $sistema->co_alternativo->CellAttributes() ?>>
<span id="el_sistema_co_alternativo" class="control-group">
<span<?php echo $sistema->co_alternativo->ViewAttributes() ?>>
<?php echo $sistema->co_alternativo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sistema->no_sistema->Visible) { // no_sistema ?>
	<tr id="r_no_sistema">
		<td><span id="elh_sistema_no_sistema"><?php echo $sistema->no_sistema->FldCaption() ?></span></td>
		<td<?php echo $sistema->no_sistema->CellAttributes() ?>>
<span id="el_sistema_no_sistema" class="control-group">
<span<?php echo $sistema->no_sistema->ViewAttributes() ?>>
<?php echo $sistema->no_sistema->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sistema->ds_sistema->Visible) { // ds_sistema ?>
	<tr id="r_ds_sistema">
		<td><span id="elh_sistema_ds_sistema"><?php echo $sistema->ds_sistema->FldCaption() ?></span></td>
		<td<?php echo $sistema->ds_sistema->CellAttributes() ?>>
<span id="el_sistema_ds_sistema" class="control-group">
<span<?php echo $sistema->ds_sistema->ViewAttributes() ?>>
<?php echo $sistema->ds_sistema->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sistema->nu_fornecedor->Visible) { // nu_fornecedor ?>
	<tr id="r_nu_fornecedor">
		<td><span id="elh_sistema_nu_fornecedor"><?php echo $sistema->nu_fornecedor->FldCaption() ?></span></td>
		<td<?php echo $sistema->nu_fornecedor->CellAttributes() ?>>
<span id="el_sistema_nu_fornecedor" class="control-group">
<span<?php echo $sistema->nu_fornecedor->ViewAttributes() ?>>
<?php echo $sistema->nu_fornecedor->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sistema->ds_contatos->Visible) { // ds_contatos ?>
	<tr id="r_ds_contatos">
		<td><span id="elh_sistema_ds_contatos"><?php echo $sistema->ds_contatos->FldCaption() ?></span></td>
		<td<?php echo $sistema->ds_contatos->CellAttributes() ?>>
<span id="el_sistema_ds_contatos" class="control-group">
<span<?php echo $sistema->ds_contatos->ViewAttributes() ?>>
<?php echo $sistema->ds_contatos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sistema->ds_comentarios->Visible) { // ds_comentarios ?>
	<tr id="r_ds_comentarios">
		<td><span id="elh_sistema_ds_comentarios"><?php echo $sistema->ds_comentarios->FldCaption() ?></span></td>
		<td<?php echo $sistema->ds_comentarios->CellAttributes() ?>>
<span id="el_sistema_ds_comentarios" class="control-group">
<span<?php echo $sistema->ds_comentarios->ViewAttributes() ?>>
<?php echo $sistema->ds_comentarios->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sistema->nu_stSistema->Visible) { // nu_stSistema ?>
	<tr id="r_nu_stSistema">
		<td><span id="elh_sistema_nu_stSistema"><?php echo $sistema->nu_stSistema->FldCaption() ?></span></td>
		<td<?php echo $sistema->nu_stSistema->CellAttributes() ?>>
<span id="el_sistema_nu_stSistema" class="control-group">
<span<?php echo $sistema->nu_stSistema->ViewAttributes() ?>>
<?php echo $sistema->nu_stSistema->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($sistema->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_sistema_ic_ativo"><?php echo $sistema->ic_ativo->FldCaption() ?></span></td>
		<td<?php echo $sistema->ic_ativo->CellAttributes() ?>>
<span id="el_sistema_ic_ativo" class="control-group">
<span<?php echo $sistema->ic_ativo->ViewAttributes() ?>>
<?php echo $sistema->ic_ativo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("ator", explode(",", $sistema->getCurrentDetailTable())) && $ator->DetailView) {
?>
<?php include_once "atorgrid.php" ?>
<?php } ?>
<?php
	if (in_array("modulo", explode(",", $sistema->getCurrentDetailTable())) && $modulo->DetailView) {
?>
<?php include_once "modulogrid.php" ?>
<?php } ?>
<?php
	if (in_array("uc", explode(",", $sistema->getCurrentDetailTable())) && $uc->DetailView) {
?>
<?php include_once "ucgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fsistemaview.Init();
</script>
<?php
$sistema_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($sistema->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$sistema_view->Page_Terminate();
?>
