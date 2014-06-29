<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "tpmetricainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "tpcontagemgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$tpmetrica_view = NULL; // Initialize page object first

class ctpmetrica_view extends ctpmetrica {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'tpmetrica';

	// Page object name
	var $PageObjName = 'tpmetrica_view';

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

		// Table object (tpmetrica)
		if (!isset($GLOBALS["tpmetrica"])) {
			$GLOBALS["tpmetrica"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tpmetrica"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_tpMetrica"] <> "") {
			$this->RecKey["nu_tpMetrica"] = $_GET["nu_tpMetrica"];
			$KeyUrl .= "&nu_tpMetrica=" . urlencode($this->RecKey["nu_tpMetrica"]);
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
			define("EW_TABLE_NAME", 'tpmetrica', TRUE);

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
			$this->Page_Terminate("tpmetricalist.php");
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
		if (@$_GET["nu_tpMetrica"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_tpMetrica"]);
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
	var $Pager;
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
			if (@$_GET["nu_tpMetrica"] <> "") {
				$this->nu_tpMetrica->setQueryStringValue($_GET["nu_tpMetrica"]);
				$this->RecKey["nu_tpMetrica"] = $this->nu_tpMetrica->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("tpmetricalist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_tpMetrica->CurrentValue) == strval($this->Recordset->fields('nu_tpMetrica'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tpmetricalist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "tpmetricalist.php"; // Not page request, return to list
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

		// Detail table 'tpcontagem'
		$body = $Language->TablePhrase("tpcontagem", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("tpcontagemlist.php?" . EW_TABLE_SHOW_MASTER . "=tpmetrica&nu_tpMetrica=" . strval($this->nu_tpMetrica->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_tpcontagem");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'tpcontagem');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "tpcontagem";
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
			$option->UseDropDownButton = TRUE;
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
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->no_tpMetrica->setDbValue($rs->fields('no_tpMetrica'));
		$this->ic_tpMetrica->setDbValue($rs->fields('ic_tpMetrica'));
		$this->ic_tpAplicacao->setDbValue($rs->fields('ic_tpAplicacao'));
		$this->ds_helpTela->setDbValue($rs->fields('ds_helpTela'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
		$this->ic_metodoEsforco->setDbValue($rs->fields('ic_metodoEsforco'));
		$this->ic_metodoPrazo->setDbValue($rs->fields('ic_metodoPrazo'));
		$this->ic_metodoCusto->setDbValue($rs->fields('ic_metodoCusto'));
		$this->ic_metodoRecursos->setDbValue($rs->fields('ic_metodoRecursos'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_tpMetrica->DbValue = $row['nu_tpMetrica'];
		$this->no_tpMetrica->DbValue = $row['no_tpMetrica'];
		$this->ic_tpMetrica->DbValue = $row['ic_tpMetrica'];
		$this->ic_tpAplicacao->DbValue = $row['ic_tpAplicacao'];
		$this->ds_helpTela->DbValue = $row['ds_helpTela'];
		$this->ic_ativo->DbValue = $row['ic_ativo'];
		$this->ic_metodoEsforco->DbValue = $row['ic_metodoEsforco'];
		$this->ic_metodoPrazo->DbValue = $row['ic_metodoPrazo'];
		$this->ic_metodoCusto->DbValue = $row['ic_metodoCusto'];
		$this->ic_metodoRecursos->DbValue = $row['ic_metodoRecursos'];
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
		// nu_tpMetrica
		// no_tpMetrica
		// ic_tpMetrica
		// ic_tpAplicacao
		// ds_helpTela
		// ic_ativo
		// ic_metodoEsforco
		// ic_metodoPrazo
		// ic_metodoCusto
		// ic_metodoRecursos

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_tpMetrica
			$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
			$this->nu_tpMetrica->ViewCustomAttributes = "";

			// no_tpMetrica
			$this->no_tpMetrica->ViewValue = $this->no_tpMetrica->CurrentValue;
			$this->no_tpMetrica->ViewCustomAttributes = "";

			// ic_tpMetrica
			if (strval($this->ic_tpMetrica->CurrentValue) <> "") {
				switch ($this->ic_tpMetrica->CurrentValue) {
					case $this->ic_tpMetrica->FldTagValue(1):
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(1) <> "" ? $this->ic_tpMetrica->FldTagCaption(1) : $this->ic_tpMetrica->CurrentValue;
						break;
					case $this->ic_tpMetrica->FldTagValue(2):
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(2) <> "" ? $this->ic_tpMetrica->FldTagCaption(2) : $this->ic_tpMetrica->CurrentValue;
						break;
					case $this->ic_tpMetrica->FldTagValue(3):
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->FldTagCaption(3) <> "" ? $this->ic_tpMetrica->FldTagCaption(3) : $this->ic_tpMetrica->CurrentValue;
						break;
					default:
						$this->ic_tpMetrica->ViewValue = $this->ic_tpMetrica->CurrentValue;
				}
			} else {
				$this->ic_tpMetrica->ViewValue = NULL;
			}
			$this->ic_tpMetrica->ViewCustomAttributes = "";

			// ic_tpAplicacao
			if (strval($this->ic_tpAplicacao->CurrentValue) <> "") {
				$this->ic_tpAplicacao->ViewValue = "";
				$arwrk = explode(",", strval($this->ic_tpAplicacao->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->ic_tpAplicacao->FldTagValue(1):
							$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(1) <> "" ? $this->ic_tpAplicacao->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						case $this->ic_tpAplicacao->FldTagValue(2):
							$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(2) <> "" ? $this->ic_tpAplicacao->FldTagCaption(2) : trim($arwrk[$ari]);
							break;
						case $this->ic_tpAplicacao->FldTagValue(3):
							$this->ic_tpAplicacao->ViewValue .= $this->ic_tpAplicacao->FldTagCaption(3) <> "" ? $this->ic_tpAplicacao->FldTagCaption(3) : trim($arwrk[$ari]);
							break;
						default:
							$this->ic_tpAplicacao->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->ic_tpAplicacao->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->ic_tpAplicacao->ViewValue = NULL;
			}
			$this->ic_tpAplicacao->ViewCustomAttributes = "";

			// ds_helpTela
			$this->ds_helpTela->ViewValue = $this->ds_helpTela->CurrentValue;
			$this->ds_helpTela->ViewCustomAttributes = "";

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

			// ic_metodoEsforco
			if (strval($this->ic_metodoEsforco->CurrentValue) <> "") {
				switch ($this->ic_metodoEsforco->CurrentValue) {
					case $this->ic_metodoEsforco->FldTagValue(1):
						$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->FldTagCaption(1) <> "" ? $this->ic_metodoEsforco->FldTagCaption(1) : $this->ic_metodoEsforco->CurrentValue;
						break;
					case $this->ic_metodoEsforco->FldTagValue(2):
						$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->FldTagCaption(2) <> "" ? $this->ic_metodoEsforco->FldTagCaption(2) : $this->ic_metodoEsforco->CurrentValue;
						break;
					default:
						$this->ic_metodoEsforco->ViewValue = $this->ic_metodoEsforco->CurrentValue;
				}
			} else {
				$this->ic_metodoEsforco->ViewValue = NULL;
			}
			$this->ic_metodoEsforco->ViewCustomAttributes = "";

			// ic_metodoPrazo
			if (strval($this->ic_metodoPrazo->CurrentValue) <> "") {
				switch ($this->ic_metodoPrazo->CurrentValue) {
					case $this->ic_metodoPrazo->FldTagValue(1):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(1) <> "" ? $this->ic_metodoPrazo->FldTagCaption(1) : $this->ic_metodoPrazo->CurrentValue;
						break;
					case $this->ic_metodoPrazo->FldTagValue(2):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(2) <> "" ? $this->ic_metodoPrazo->FldTagCaption(2) : $this->ic_metodoPrazo->CurrentValue;
						break;
					case $this->ic_metodoPrazo->FldTagValue(3):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(3) <> "" ? $this->ic_metodoPrazo->FldTagCaption(3) : $this->ic_metodoPrazo->CurrentValue;
						break;
					case $this->ic_metodoPrazo->FldTagValue(4):
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->FldTagCaption(4) <> "" ? $this->ic_metodoPrazo->FldTagCaption(4) : $this->ic_metodoPrazo->CurrentValue;
						break;
					default:
						$this->ic_metodoPrazo->ViewValue = $this->ic_metodoPrazo->CurrentValue;
				}
			} else {
				$this->ic_metodoPrazo->ViewValue = NULL;
			}
			$this->ic_metodoPrazo->ViewCustomAttributes = "";

			// ic_metodoCusto
			if (strval($this->ic_metodoCusto->CurrentValue) <> "") {
				switch ($this->ic_metodoCusto->CurrentValue) {
					case $this->ic_metodoCusto->FldTagValue(1):
						$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->FldTagCaption(1) <> "" ? $this->ic_metodoCusto->FldTagCaption(1) : $this->ic_metodoCusto->CurrentValue;
						break;
					case $this->ic_metodoCusto->FldTagValue(2):
						$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->FldTagCaption(2) <> "" ? $this->ic_metodoCusto->FldTagCaption(2) : $this->ic_metodoCusto->CurrentValue;
						break;
					default:
						$this->ic_metodoCusto->ViewValue = $this->ic_metodoCusto->CurrentValue;
				}
			} else {
				$this->ic_metodoCusto->ViewValue = NULL;
			}
			$this->ic_metodoCusto->ViewCustomAttributes = "";

			// ic_metodoRecursos
			if (strval($this->ic_metodoRecursos->CurrentValue) <> "") {
				switch ($this->ic_metodoRecursos->CurrentValue) {
					case $this->ic_metodoRecursos->FldTagValue(1):
						$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->FldTagCaption(1) <> "" ? $this->ic_metodoRecursos->FldTagCaption(1) : $this->ic_metodoRecursos->CurrentValue;
						break;
					case $this->ic_metodoRecursos->FldTagValue(2):
						$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->FldTagCaption(2) <> "" ? $this->ic_metodoRecursos->FldTagCaption(2) : $this->ic_metodoRecursos->CurrentValue;
						break;
					default:
						$this->ic_metodoRecursos->ViewValue = $this->ic_metodoRecursos->CurrentValue;
				}
			} else {
				$this->ic_metodoRecursos->ViewValue = NULL;
			}
			$this->ic_metodoRecursos->ViewCustomAttributes = "";

			// no_tpMetrica
			$this->no_tpMetrica->LinkCustomAttributes = "";
			$this->no_tpMetrica->HrefValue = "";
			$this->no_tpMetrica->TooltipValue = "";

			// ic_tpMetrica
			$this->ic_tpMetrica->LinkCustomAttributes = "";
			$this->ic_tpMetrica->HrefValue = "";
			$this->ic_tpMetrica->TooltipValue = "";

			// ic_tpAplicacao
			$this->ic_tpAplicacao->LinkCustomAttributes = "";
			$this->ic_tpAplicacao->HrefValue = "";
			$this->ic_tpAplicacao->TooltipValue = "";

			// ds_helpTela
			$this->ds_helpTela->LinkCustomAttributes = "";
			$this->ds_helpTela->HrefValue = "";
			$this->ds_helpTela->TooltipValue = "";

			// ic_ativo
			$this->ic_ativo->LinkCustomAttributes = "";
			$this->ic_ativo->HrefValue = "";
			$this->ic_ativo->TooltipValue = "";

			// ic_metodoEsforco
			$this->ic_metodoEsforco->LinkCustomAttributes = "";
			$this->ic_metodoEsforco->HrefValue = "";
			$this->ic_metodoEsforco->TooltipValue = "";

			// ic_metodoPrazo
			$this->ic_metodoPrazo->LinkCustomAttributes = "";
			$this->ic_metodoPrazo->HrefValue = "";
			$this->ic_metodoPrazo->TooltipValue = "";

			// ic_metodoCusto
			$this->ic_metodoCusto->LinkCustomAttributes = "";
			$this->ic_metodoCusto->HrefValue = "";
			$this->ic_metodoCusto->TooltipValue = "";

			// ic_metodoRecursos
			$this->ic_metodoRecursos->LinkCustomAttributes = "";
			$this->ic_metodoRecursos->HrefValue = "";
			$this->ic_metodoRecursos->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_tpmetrica\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_tpmetrica',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.ftpmetricaview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
			if (in_array("tpcontagem", $DetailTblVar)) {
				if (!isset($GLOBALS["tpcontagem_grid"]))
					$GLOBALS["tpcontagem_grid"] = new ctpcontagem_grid;
				if ($GLOBALS["tpcontagem_grid"]->DetailView) {
					$GLOBALS["tpcontagem_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["tpcontagem_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["tpcontagem_grid"]->setStartRecordNumber(1);
					$GLOBALS["tpcontagem_grid"]->nu_tpMetrica->FldIsDetailKey = TRUE;
					$GLOBALS["tpcontagem_grid"]->nu_tpMetrica->CurrentValue = $this->nu_tpMetrica->CurrentValue;
					$GLOBALS["tpcontagem_grid"]->nu_tpMetrica->setSessionValue($GLOBALS["tpcontagem_grid"]->nu_tpMetrica->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "tpmetricalist.php", $this->TableVar);
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
if (!isset($tpmetrica_view)) $tpmetrica_view = new ctpmetrica_view();

// Page init
$tpmetrica_view->Page_Init();

// Page main
$tpmetrica_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$tpmetrica_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($tpmetrica->Export == "") { ?>
<script type="text/javascript">

// Page object
var tpmetrica_view = new ew_Page("tpmetrica_view");
tpmetrica_view.PageID = "view"; // Page ID
var EW_PAGE_ID = tpmetrica_view.PageID; // For backward compatibility

// Form object
var ftpmetricaview = new ew_Form("ftpmetricaview");

// Form_CustomValidate event
ftpmetricaview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftpmetricaview.ValidateRequired = true;
<?php } else { ?>
ftpmetricaview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
ftpmetricaview.MultiPage = new ew_MultiPage("ftpmetricaview",
	[["x_no_tpMetrica",1],["x_ic_tpMetrica",1],["x_ic_tpAplicacao",1],["x_ds_helpTela",1],["x_ic_ativo",1],["x_ic_metodoEsforco",2],["x_ic_metodoPrazo",2],["x_ic_metodoCusto",2],["x_ic_metodoRecursos",2]]
);

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($tpmetrica->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($tpmetrica->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $tpmetrica_view->ExportOptions->Render("body") ?>
<?php if (!$tpmetrica_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($tpmetrica_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $tpmetrica_view->ShowPageHeader(); ?>
<?php
$tpmetrica_view->ShowMessage();
?>
<form name="ftpmetricaview" id="ftpmetricaview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="tpmetrica">
<?php if ($tpmetrica->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="tpmetrica_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_tpmetrica1" data-toggle="tab"><?php echo $tpmetrica->PageCaption(1) ?></a></li>
		<li><a href="#tab_tpmetrica2" data-toggle="tab"><?php echo $tpmetrica->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
		<div class="tab-pane active" id="tab_tpmetrica1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_tpmetricaview1" class="table table-bordered table-striped">
<?php if ($tpmetrica->no_tpMetrica->Visible) { // no_tpMetrica ?>
	<tr id="r_no_tpMetrica">
		<td><span id="elh_tpmetrica_no_tpMetrica"><?php echo $tpmetrica->no_tpMetrica->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->no_tpMetrica->CellAttributes() ?>>
<span id="el_tpmetrica_no_tpMetrica" class="control-group">
<span<?php echo $tpmetrica->no_tpMetrica->ViewAttributes() ?>>
<?php echo $tpmetrica->no_tpMetrica->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_tpMetrica->Visible) { // ic_tpMetrica ?>
	<tr id="r_ic_tpMetrica">
		<td><span id="elh_tpmetrica_ic_tpMetrica"><?php echo $tpmetrica->ic_tpMetrica->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->ic_tpMetrica->CellAttributes() ?>>
<span id="el_tpmetrica_ic_tpMetrica" class="control-group">
<span<?php echo $tpmetrica->ic_tpMetrica->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_tpMetrica->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_tpAplicacao->Visible) { // ic_tpAplicacao ?>
	<tr id="r_ic_tpAplicacao">
		<td><span id="elh_tpmetrica_ic_tpAplicacao"><?php echo $tpmetrica->ic_tpAplicacao->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->ic_tpAplicacao->CellAttributes() ?>>
<span id="el_tpmetrica_ic_tpAplicacao" class="control-group">
<span<?php echo $tpmetrica->ic_tpAplicacao->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_tpAplicacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ds_helpTela->Visible) { // ds_helpTela ?>
	<tr id="r_ds_helpTela">
		<td><span id="elh_tpmetrica_ds_helpTela"><?php echo $tpmetrica->ds_helpTela->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->ds_helpTela->CellAttributes() ?>>
<span id="el_tpmetrica_ds_helpTela" class="control-group">
<span<?php echo $tpmetrica->ds_helpTela->ViewAttributes() ?>>
<?php echo $tpmetrica->ds_helpTela->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_tpmetrica_ic_ativo"><?php echo $tpmetrica->ic_ativo->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->ic_ativo->CellAttributes() ?>>
<span id="el_tpmetrica_ic_ativo" class="control-group">
<span<?php echo $tpmetrica->ic_ativo->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_ativo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_tpmetrica2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_tpmetricaview2" class="table table-bordered table-striped">
<?php if ($tpmetrica->ic_metodoEsforco->Visible) { // ic_metodoEsforco ?>
	<tr id="r_ic_metodoEsforco">
		<td><span id="elh_tpmetrica_ic_metodoEsforco"><?php echo $tpmetrica->ic_metodoEsforco->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->ic_metodoEsforco->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoEsforco" class="control-group">
<span<?php echo $tpmetrica->ic_metodoEsforco->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoEsforco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoPrazo->Visible) { // ic_metodoPrazo ?>
	<tr id="r_ic_metodoPrazo">
		<td><span id="elh_tpmetrica_ic_metodoPrazo"><?php echo $tpmetrica->ic_metodoPrazo->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->ic_metodoPrazo->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoPrazo" class="control-group">
<span<?php echo $tpmetrica->ic_metodoPrazo->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoPrazo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoCusto->Visible) { // ic_metodoCusto ?>
	<tr id="r_ic_metodoCusto">
		<td><span id="elh_tpmetrica_ic_metodoCusto"><?php echo $tpmetrica->ic_metodoCusto->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->ic_metodoCusto->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoCusto" class="control-group">
<span<?php echo $tpmetrica->ic_metodoCusto->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoCusto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($tpmetrica->ic_metodoRecursos->Visible) { // ic_metodoRecursos ?>
	<tr id="r_ic_metodoRecursos">
		<td><span id="elh_tpmetrica_ic_metodoRecursos"><?php echo $tpmetrica->ic_metodoRecursos->FldCaption() ?></span></td>
		<td<?php echo $tpmetrica->ic_metodoRecursos->CellAttributes() ?>>
<span id="el_tpmetrica_ic_metodoRecursos" class="control-group">
<span<?php echo $tpmetrica->ic_metodoRecursos->ViewAttributes() ?>>
<?php echo $tpmetrica->ic_metodoRecursos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
<?php if ($tpmetrica->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
<?php if ($tpmetrica->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($tpmetrica_view->Pager)) $tpmetrica_view->Pager = new cNumericPager($tpmetrica_view->StartRec, $tpmetrica_view->DisplayRecs, $tpmetrica_view->TotalRecs, $tpmetrica_view->RecRange) ?>
<?php if ($tpmetrica_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($tpmetrica_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $tpmetrica_view->PageUrl() ?>start=<?php echo $tpmetrica_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($tpmetrica_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $tpmetrica_view->PageUrl() ?>start=<?php echo $tpmetrica_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($tpmetrica_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $tpmetrica_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($tpmetrica_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $tpmetrica_view->PageUrl() ?>start=<?php echo $tpmetrica_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($tpmetrica_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $tpmetrica_view->PageUrl() ?>start=<?php echo $tpmetrica_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</td>
</tr></tbody></table>
<?php } else { ?>
	<p><?php echo $Language->Phrase("NoRecord") ?></p>
<?php } ?>
</td>
</tr></table>
<?php } ?>
<?php
	if (in_array("tpcontagem", explode(",", $tpmetrica->getCurrentDetailTable())) && $tpcontagem->DetailView) {
?>
<?php include_once "tpcontagemgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
ftpmetricaview.Init();
</script>
<?php
$tpmetrica_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($tpmetrica->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$tpmetrica_view->Page_Terminate();
?>
