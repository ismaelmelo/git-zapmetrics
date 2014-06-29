<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ambiente_valoracaoinfo.php" ?>
<?php include_once "ambienteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ambiente_valoracao_view = NULL; // Initialize page object first

class cambiente_valoracao_view extends cambiente_valoracao {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente_valoracao';

	// Page object name
	var $PageObjName = 'ambiente_valoracao_view';

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

		// Table object (ambiente_valoracao)
		if (!isset($GLOBALS["ambiente_valoracao"])) {
			$GLOBALS["ambiente_valoracao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ambiente_valoracao"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_ambiente"] <> "") {
			$this->RecKey["nu_ambiente"] = $_GET["nu_ambiente"];
			$KeyUrl .= "&nu_ambiente=" . urlencode($this->RecKey["nu_ambiente"]);
		}
		if (@$_GET["nu_versaoValoracao"] <> "") {
			$this->RecKey["nu_versaoValoracao"] = $_GET["nu_versaoValoracao"];
			$KeyUrl .= "&nu_versaoValoracao=" . urlencode($this->RecKey["nu_versaoValoracao"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (ambiente)
		if (!isset($GLOBALS['ambiente'])) $GLOBALS['ambiente'] = new cambiente();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente_valoracao', TRUE);

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
			$this->Page_Terminate("ambiente_valoracaolist.php");
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
		if (@$_GET["nu_ambiente"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_ambiente"]);
		}
		if (@$_GET["nu_versaoValoracao"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_versaoValoracao"]);
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
			if (@$_GET["nu_ambiente"] <> "") {
				$this->nu_ambiente->setQueryStringValue($_GET["nu_ambiente"]);
				$this->RecKey["nu_ambiente"] = $this->nu_ambiente->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}
			if (@$_GET["nu_versaoValoracao"] <> "") {
				$this->nu_versaoValoracao->setQueryStringValue($_GET["nu_versaoValoracao"]);
				$this->RecKey["nu_versaoValoracao"] = $this->nu_versaoValoracao->QueryStringValue;
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
						$this->Page_Terminate("ambiente_valoracaolist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_ambiente->CurrentValue) == strval($this->Recordset->fields('nu_ambiente')) && strval($this->nu_versaoValoracao->CurrentValue) == strval($this->Recordset->fields('nu_versaoValoracao'))) {
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
						$sReturnUrl = "ambiente_valoracaolist.php"; // No matching record, return to list
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
			$sReturnUrl = "ambiente_valoracaolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
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

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

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
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->nu_versaoValoracao->setDbValue($rs->fields('nu_versaoValoracao'));
		$this->ic_metCalibracao->setDbValue($rs->fields('ic_metCalibracao'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_tpAtualizacao->setDbValue($rs->fields('ic_tpAtualizacao'));
		$this->qt_linhasCodLingPf->setDbValue($rs->fields('qt_linhasCodLingPf'));
		$this->vr_ipMin->setDbValue($rs->fields('vr_ipMin'));
		$this->vr_ipMed->setDbValue($rs->fields('vr_ipMed'));
		$this->vr_ipMax->setDbValue($rs->fields('vr_ipMax'));
		$this->vr_constanteA->setDbValue($rs->fields('vr_constanteA'));
		$this->vr_constanteB->setDbValue($rs->fields('vr_constanteB'));
		$this->vr_constanteC->setDbValue($rs->fields('vr_constanteC'));
		$this->vr_constanteD->setDbValue($rs->fields('vr_constanteD'));
		$this->nu_altPREC->setDbValue($rs->fields('nu_altPREC'));
		if (array_key_exists('EV__nu_altPREC', $rs->fields)) {
			$this->nu_altPREC->VirtualValue = $rs->fields('EV__nu_altPREC'); // Set up virtual field value
		} else {
			$this->nu_altPREC->VirtualValue = ""; // Clear value
		}
		$this->nu_altFLEX->setDbValue($rs->fields('nu_altFLEX'));
		$this->nu_altRESL->setDbValue($rs->fields('nu_altRESL'));
		$this->nu_altTEAM->setDbValue($rs->fields('nu_altTEAM'));
		if (array_key_exists('EV__nu_altTEAM', $rs->fields)) {
			$this->nu_altTEAM->VirtualValue = $rs->fields('EV__nu_altTEAM'); // Set up virtual field value
		} else {
			$this->nu_altTEAM->VirtualValue = ""; // Clear value
		}
		$this->nu_altPMAT->setDbValue($rs->fields('nu_altPMAT'));
		$this->nu_altRELY->setDbValue($rs->fields('nu_altRELY'));
		$this->nu_altDATA->setDbValue($rs->fields('nu_altDATA'));
		$this->nu_altCPLX1->setDbValue($rs->fields('nu_altCPLX1'));
		$this->nu_altCPLX2->setDbValue($rs->fields('nu_altCPLX2'));
		$this->nu_altCPLX3->setDbValue($rs->fields('nu_altCPLX3'));
		if (array_key_exists('EV__nu_altCPLX3', $rs->fields)) {
			$this->nu_altCPLX3->VirtualValue = $rs->fields('EV__nu_altCPLX3'); // Set up virtual field value
		} else {
			$this->nu_altCPLX3->VirtualValue = ""; // Clear value
		}
		$this->nu_altCPLX4->setDbValue($rs->fields('nu_altCPLX4'));
		if (array_key_exists('EV__nu_altCPLX4', $rs->fields)) {
			$this->nu_altCPLX4->VirtualValue = $rs->fields('EV__nu_altCPLX4'); // Set up virtual field value
		} else {
			$this->nu_altCPLX4->VirtualValue = ""; // Clear value
		}
		$this->nu_altCPLX5->setDbValue($rs->fields('nu_altCPLX5'));
		$this->nu_altDOCU->setDbValue($rs->fields('nu_altDOCU'));
		$this->nu_altRUSE->setDbValue($rs->fields('nu_altRUSE'));
		$this->nu_altTIME->setDbValue($rs->fields('nu_altTIME'));
		$this->nu_altSTOR->setDbValue($rs->fields('nu_altSTOR'));
		$this->nu_altPVOL->setDbValue($rs->fields('nu_altPVOL'));
		$this->nu_altACAP->setDbValue($rs->fields('nu_altACAP'));
		$this->nu_altPCAP->setDbValue($rs->fields('nu_altPCAP'));
		$this->nu_altPCON->setDbValue($rs->fields('nu_altPCON'));
		$this->nu_altAPEX->setDbValue($rs->fields('nu_altAPEX'));
		$this->nu_altPLEX->setDbValue($rs->fields('nu_altPLEX'));
		$this->nu_altLTEX->setDbValue($rs->fields('nu_altLTEX'));
		$this->nu_altTOOL->setDbValue($rs->fields('nu_altTOOL'));
		$this->nu_altSITE->setDbValue($rs->fields('nu_altSITE'));
		$this->co_quePREC->setDbValue($rs->fields('co_quePREC'));
		$this->co_queFLEX->setDbValue($rs->fields('co_queFLEX'));
		$this->co_queRESL->setDbValue($rs->fields('co_queRESL'));
		$this->co_queTEAM->setDbValue($rs->fields('co_queTEAM'));
		$this->co_quePMAT->setDbValue($rs->fields('co_quePMAT'));
		$this->co_queRELY->setDbValue($rs->fields('co_queRELY'));
		$this->co_queDATA->setDbValue($rs->fields('co_queDATA'));
		$this->co_queCPLX1->setDbValue($rs->fields('co_queCPLX1'));
		$this->co_queCPLX2->setDbValue($rs->fields('co_queCPLX2'));
		$this->co_queCPLX3->setDbValue($rs->fields('co_queCPLX3'));
		$this->co_queCPLX4->setDbValue($rs->fields('co_queCPLX4'));
		$this->co_queCPLX5->setDbValue($rs->fields('co_queCPLX5'));
		$this->co_queDOCU->setDbValue($rs->fields('co_queDOCU'));
		$this->co_queRUSE->setDbValue($rs->fields('co_queRUSE'));
		$this->co_queTIME->setDbValue($rs->fields('co_queTIME'));
		$this->co_queSTOR->setDbValue($rs->fields('co_queSTOR'));
		$this->co_quePVOL->setDbValue($rs->fields('co_quePVOL'));
		$this->co_queACAP->setDbValue($rs->fields('co_queACAP'));
		$this->co_quePCAP->setDbValue($rs->fields('co_quePCAP'));
		$this->co_quePCON->setDbValue($rs->fields('co_quePCON'));
		$this->co_queAPEX->setDbValue($rs->fields('co_queAPEX'));
		$this->co_quePLEX->setDbValue($rs->fields('co_quePLEX'));
		$this->co_queLTEX->setDbValue($rs->fields('co_queLTEX'));
		$this->co_queTOOL->setDbValue($rs->fields('co_queTOOL'));
		$this->co_queSITE->setDbValue($rs->fields('co_queSITE'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->nu_versaoValoracao->DbValue = $row['nu_versaoValoracao'];
		$this->ic_metCalibracao->DbValue = $row['ic_metCalibracao'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ic_tpAtualizacao->DbValue = $row['ic_tpAtualizacao'];
		$this->qt_linhasCodLingPf->DbValue = $row['qt_linhasCodLingPf'];
		$this->vr_ipMin->DbValue = $row['vr_ipMin'];
		$this->vr_ipMed->DbValue = $row['vr_ipMed'];
		$this->vr_ipMax->DbValue = $row['vr_ipMax'];
		$this->vr_constanteA->DbValue = $row['vr_constanteA'];
		$this->vr_constanteB->DbValue = $row['vr_constanteB'];
		$this->vr_constanteC->DbValue = $row['vr_constanteC'];
		$this->vr_constanteD->DbValue = $row['vr_constanteD'];
		$this->nu_altPREC->DbValue = $row['nu_altPREC'];
		$this->nu_altFLEX->DbValue = $row['nu_altFLEX'];
		$this->nu_altRESL->DbValue = $row['nu_altRESL'];
		$this->nu_altTEAM->DbValue = $row['nu_altTEAM'];
		$this->nu_altPMAT->DbValue = $row['nu_altPMAT'];
		$this->nu_altRELY->DbValue = $row['nu_altRELY'];
		$this->nu_altDATA->DbValue = $row['nu_altDATA'];
		$this->nu_altCPLX1->DbValue = $row['nu_altCPLX1'];
		$this->nu_altCPLX2->DbValue = $row['nu_altCPLX2'];
		$this->nu_altCPLX3->DbValue = $row['nu_altCPLX3'];
		$this->nu_altCPLX4->DbValue = $row['nu_altCPLX4'];
		$this->nu_altCPLX5->DbValue = $row['nu_altCPLX5'];
		$this->nu_altDOCU->DbValue = $row['nu_altDOCU'];
		$this->nu_altRUSE->DbValue = $row['nu_altRUSE'];
		$this->nu_altTIME->DbValue = $row['nu_altTIME'];
		$this->nu_altSTOR->DbValue = $row['nu_altSTOR'];
		$this->nu_altPVOL->DbValue = $row['nu_altPVOL'];
		$this->nu_altACAP->DbValue = $row['nu_altACAP'];
		$this->nu_altPCAP->DbValue = $row['nu_altPCAP'];
		$this->nu_altPCON->DbValue = $row['nu_altPCON'];
		$this->nu_altAPEX->DbValue = $row['nu_altAPEX'];
		$this->nu_altPLEX->DbValue = $row['nu_altPLEX'];
		$this->nu_altLTEX->DbValue = $row['nu_altLTEX'];
		$this->nu_altTOOL->DbValue = $row['nu_altTOOL'];
		$this->nu_altSITE->DbValue = $row['nu_altSITE'];
		$this->co_quePREC->DbValue = $row['co_quePREC'];
		$this->co_queFLEX->DbValue = $row['co_queFLEX'];
		$this->co_queRESL->DbValue = $row['co_queRESL'];
		$this->co_queTEAM->DbValue = $row['co_queTEAM'];
		$this->co_quePMAT->DbValue = $row['co_quePMAT'];
		$this->co_queRELY->DbValue = $row['co_queRELY'];
		$this->co_queDATA->DbValue = $row['co_queDATA'];
		$this->co_queCPLX1->DbValue = $row['co_queCPLX1'];
		$this->co_queCPLX2->DbValue = $row['co_queCPLX2'];
		$this->co_queCPLX3->DbValue = $row['co_queCPLX3'];
		$this->co_queCPLX4->DbValue = $row['co_queCPLX4'];
		$this->co_queCPLX5->DbValue = $row['co_queCPLX5'];
		$this->co_queDOCU->DbValue = $row['co_queDOCU'];
		$this->co_queRUSE->DbValue = $row['co_queRUSE'];
		$this->co_queTIME->DbValue = $row['co_queTIME'];
		$this->co_queSTOR->DbValue = $row['co_queSTOR'];
		$this->co_quePVOL->DbValue = $row['co_quePVOL'];
		$this->co_queACAP->DbValue = $row['co_queACAP'];
		$this->co_quePCAP->DbValue = $row['co_quePCAP'];
		$this->co_quePCON->DbValue = $row['co_quePCON'];
		$this->co_queAPEX->DbValue = $row['co_queAPEX'];
		$this->co_quePLEX->DbValue = $row['co_quePLEX'];
		$this->co_queLTEX->DbValue = $row['co_queLTEX'];
		$this->co_queTOOL->DbValue = $row['co_queTOOL'];
		$this->co_queSITE->DbValue = $row['co_queSITE'];
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

		// Convert decimal values if posted back
		if ($this->vr_ipMin->FormValue == $this->vr_ipMin->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMin->CurrentValue)))
			$this->vr_ipMin->CurrentValue = ew_StrToFloat($this->vr_ipMin->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMed->FormValue == $this->vr_ipMed->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMed->CurrentValue)))
			$this->vr_ipMed->CurrentValue = ew_StrToFloat($this->vr_ipMed->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMax->FormValue == $this->vr_ipMax->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMax->CurrentValue)))
			$this->vr_ipMax->CurrentValue = ew_StrToFloat($this->vr_ipMax->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_constanteA->FormValue == $this->vr_constanteA->CurrentValue && is_numeric(ew_StrToFloat($this->vr_constanteA->CurrentValue)))
			$this->vr_constanteA->CurrentValue = ew_StrToFloat($this->vr_constanteA->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_constanteB->FormValue == $this->vr_constanteB->CurrentValue && is_numeric(ew_StrToFloat($this->vr_constanteB->CurrentValue)))
			$this->vr_constanteB->CurrentValue = ew_StrToFloat($this->vr_constanteB->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_constanteC->FormValue == $this->vr_constanteC->CurrentValue && is_numeric(ew_StrToFloat($this->vr_constanteC->CurrentValue)))
			$this->vr_constanteC->CurrentValue = ew_StrToFloat($this->vr_constanteC->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_constanteD->FormValue == $this->vr_constanteD->CurrentValue && is_numeric(ew_StrToFloat($this->vr_constanteD->CurrentValue)))
			$this->vr_constanteD->CurrentValue = ew_StrToFloat($this->vr_constanteD->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_ambiente
		// nu_versaoValoracao
		// ic_metCalibracao
		// dh_inclusao
		// nu_usuarioResp
		// ic_tpAtualizacao
		// qt_linhasCodLingPf
		// vr_ipMin
		// vr_ipMed
		// vr_ipMax
		// vr_constanteA
		// vr_constanteB
		// vr_constanteC
		// vr_constanteD
		// nu_altPREC
		// nu_altFLEX
		// nu_altRESL
		// nu_altTEAM
		// nu_altPMAT
		// nu_altRELY
		// nu_altDATA
		// nu_altCPLX1
		// nu_altCPLX2
		// nu_altCPLX3
		// nu_altCPLX4
		// nu_altCPLX5
		// nu_altDOCU
		// nu_altRUSE
		// nu_altTIME
		// nu_altSTOR
		// nu_altPVOL
		// nu_altACAP
		// nu_altPCAP
		// nu_altPCON
		// nu_altAPEX
		// nu_altPLEX
		// nu_altLTEX
		// nu_altTOOL
		// nu_altSITE
		// co_quePREC
		// co_queFLEX
		// co_queRESL
		// co_queTEAM
		// co_quePMAT
		// co_queRELY
		// co_queDATA
		// co_queCPLX1
		// co_queCPLX2
		// co_queCPLX3
		// co_queCPLX4
		// co_queCPLX5
		// co_queDOCU
		// co_queRUSE
		// co_queTIME
		// co_queSTOR
		// co_quePVOL
		// co_queACAP
		// co_quePCAP
		// co_quePCON
		// co_queAPEX
		// co_quePLEX
		// co_queLTEX
		// co_queTOOL
		// co_queSITE

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_ambiente
			$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ambiente], [nu_ambiente] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf]";
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

			// nu_versaoValoracao
			$this->nu_versaoValoracao->ViewValue = $this->nu_versaoValoracao->CurrentValue;
			$this->nu_versaoValoracao->ViewCustomAttributes = "";

			// ic_metCalibracao
			if (strval($this->ic_metCalibracao->CurrentValue) <> "") {
				switch ($this->ic_metCalibracao->CurrentValue) {
					case $this->ic_metCalibracao->FldTagValue(1):
						$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->FldTagCaption(1) <> "" ? $this->ic_metCalibracao->FldTagCaption(1) : $this->ic_metCalibracao->CurrentValue;
						break;
					case $this->ic_metCalibracao->FldTagValue(2):
						$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->FldTagCaption(2) <> "" ? $this->ic_metCalibracao->FldTagCaption(2) : $this->ic_metCalibracao->CurrentValue;
						break;
					default:
						$this->ic_metCalibracao->ViewValue = $this->ic_metCalibracao->CurrentValue;
				}
			} else {
				$this->ic_metCalibracao->ViewValue = NULL;
			}
			$this->ic_metCalibracao->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->ViewValue = $this->nu_usuarioResp->CurrentValue;
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

			// ic_tpAtualizacao
			if (strval($this->ic_tpAtualizacao->CurrentValue) <> "") {
				switch ($this->ic_tpAtualizacao->CurrentValue) {
					case $this->ic_tpAtualizacao->FldTagValue(1):
						$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->FldTagCaption(1) <> "" ? $this->ic_tpAtualizacao->FldTagCaption(1) : $this->ic_tpAtualizacao->CurrentValue;
						break;
					case $this->ic_tpAtualizacao->FldTagValue(2):
						$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->FldTagCaption(2) <> "" ? $this->ic_tpAtualizacao->FldTagCaption(2) : $this->ic_tpAtualizacao->CurrentValue;
						break;
					default:
						$this->ic_tpAtualizacao->ViewValue = $this->ic_tpAtualizacao->CurrentValue;
				}
			} else {
				$this->ic_tpAtualizacao->ViewValue = NULL;
			}
			$this->ic_tpAtualizacao->ViewCustomAttributes = "";

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->ViewValue = $this->qt_linhasCodLingPf->CurrentValue;
			$this->qt_linhasCodLingPf->ViewCustomAttributes = "";

			// vr_ipMin
			$this->vr_ipMin->ViewValue = $this->vr_ipMin->CurrentValue;
			$this->vr_ipMin->ViewCustomAttributes = "";

			// vr_ipMed
			$this->vr_ipMed->ViewValue = $this->vr_ipMed->CurrentValue;
			$this->vr_ipMed->ViewCustomAttributes = "";

			// vr_ipMax
			$this->vr_ipMax->ViewValue = $this->vr_ipMax->CurrentValue;
			$this->vr_ipMax->ViewCustomAttributes = "";

			// vr_constanteA
			$this->vr_constanteA->ViewValue = $this->vr_constanteA->CurrentValue;
			$this->vr_constanteA->ViewCustomAttributes = "";

			// vr_constanteB
			$this->vr_constanteB->ViewValue = $this->vr_constanteB->CurrentValue;
			$this->vr_constanteB->ViewCustomAttributes = "";

			// vr_constanteC
			$this->vr_constanteC->ViewValue = $this->vr_constanteC->CurrentValue;
			$this->vr_constanteC->ViewCustomAttributes = "";

			// vr_constanteD
			$this->vr_constanteD->ViewValue = $this->vr_constanteD->CurrentValue;
			$this->vr_constanteD->ViewCustomAttributes = "";

			// nu_altPREC
			if ($this->nu_altPREC->VirtualValue <> "") {
				$this->nu_altPREC->ViewValue = $this->nu_altPREC->VirtualValue;
			} else {
			if (strval($this->nu_altPREC->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPREC->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPREC, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPREC->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPREC->ViewValue .= ew_ValueSeparator(1,$this->nu_altPREC) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPREC->ViewValue = $this->nu_altPREC->CurrentValue;
				}
			} else {
				$this->nu_altPREC->ViewValue = NULL;
			}
			}
			$this->nu_altPREC->ViewCustomAttributes = "";

			// nu_altFLEX
			if (strval($this->nu_altFLEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altFLEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altFLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altFLEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altFLEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altFLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altFLEX->ViewValue = $this->nu_altFLEX->CurrentValue;
				}
			} else {
				$this->nu_altFLEX->ViewValue = NULL;
			}
			$this->nu_altFLEX->ViewCustomAttributes = "";

			// nu_altRESL
			if (strval($this->nu_altRESL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRESL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRESL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRESL->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altRESL->ViewValue .= ew_ValueSeparator(1,$this->nu_altRESL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altRESL->ViewValue = $this->nu_altRESL->CurrentValue;
				}
			} else {
				$this->nu_altRESL->ViewValue = NULL;
			}
			$this->nu_altRESL->ViewCustomAttributes = "";

			// nu_altTEAM
			if ($this->nu_altTEAM->VirtualValue <> "") {
				$this->nu_altTEAM->ViewValue = $this->nu_altTEAM->VirtualValue;
			} else {
			if (strval($this->nu_altTEAM->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTEAM->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTEAM, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTEAM->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altTEAM->ViewValue .= ew_ValueSeparator(1,$this->nu_altTEAM) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altTEAM->ViewValue = $this->nu_altTEAM->CurrentValue;
				}
			} else {
				$this->nu_altTEAM->ViewValue = NULL;
			}
			}
			$this->nu_altTEAM->ViewCustomAttributes = "";

			// nu_altPMAT
			if (strval($this->nu_altPMAT->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPMAT->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPMAT, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPMAT->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPMAT->ViewValue .= ew_ValueSeparator(1,$this->nu_altPMAT) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPMAT->ViewValue = $this->nu_altPMAT->CurrentValue;
				}
			} else {
				$this->nu_altPMAT->ViewValue = NULL;
			}
			$this->nu_altPMAT->ViewCustomAttributes = "";

			// nu_altRELY
			if (strval($this->nu_altRELY->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRELY->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRELY->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altRELY->ViewValue .= ew_ValueSeparator(1,$this->nu_altRELY) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altRELY->ViewValue = $this->nu_altRELY->CurrentValue;
				}
			} else {
				$this->nu_altRELY->ViewValue = NULL;
			}
			$this->nu_altRELY->ViewCustomAttributes = "";

			// nu_altDATA
			if (strval($this->nu_altDATA->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDATA->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDATA->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altDATA->ViewValue .= ew_ValueSeparator(1,$this->nu_altDATA) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altDATA->ViewValue = $this->nu_altDATA->CurrentValue;
				}
			} else {
				$this->nu_altDATA->ViewValue = NULL;
			}
			$this->nu_altDATA->ViewCustomAttributes = "";

			// nu_altCPLX1
			if (strval($this->nu_altCPLX1->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX1->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX1->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX1->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX1) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX1->ViewValue = $this->nu_altCPLX1->CurrentValue;
				}
			} else {
				$this->nu_altCPLX1->ViewValue = NULL;
			}
			$this->nu_altCPLX1->ViewCustomAttributes = "";

			// nu_altCPLX2
			if (strval($this->nu_altCPLX2->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX2->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX2->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX2->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX2) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX2->ViewValue = $this->nu_altCPLX2->CurrentValue;
				}
			} else {
				$this->nu_altCPLX2->ViewValue = NULL;
			}
			$this->nu_altCPLX2->ViewCustomAttributes = "";

			// nu_altCPLX3
			if ($this->nu_altCPLX3->VirtualValue <> "") {
				$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->VirtualValue;
			} else {
			if (strval($this->nu_altCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX3->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX3->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX3->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX3) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->CurrentValue;
				}
			} else {
				$this->nu_altCPLX3->ViewValue = NULL;
			}
			}
			$this->nu_altCPLX3->ViewCustomAttributes = "";

			// nu_altCPLX4
			if ($this->nu_altCPLX4->VirtualValue <> "") {
				$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->VirtualValue;
			} else {
			if (strval($this->nu_altCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX4->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX4->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX4->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX4) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->CurrentValue;
				}
			} else {
				$this->nu_altCPLX4->ViewValue = NULL;
			}
			}
			$this->nu_altCPLX4->ViewCustomAttributes = "";

			// nu_altCPLX5
			if (strval($this->nu_altCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX5->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX5->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altCPLX5->ViewValue .= ew_ValueSeparator(1,$this->nu_altCPLX5) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX5->ViewValue = $this->nu_altCPLX5->CurrentValue;
				}
			} else {
				$this->nu_altCPLX5->ViewValue = NULL;
			}
			$this->nu_altCPLX5->ViewCustomAttributes = "";

			// nu_altDOCU
			if (strval($this->nu_altDOCU->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altDOCU->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDOCU->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altDOCU->ViewValue .= ew_ValueSeparator(1,$this->nu_altDOCU) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altDOCU->ViewValue = $this->nu_altDOCU->CurrentValue;
				}
			} else {
				$this->nu_altDOCU->ViewValue = NULL;
			}
			$this->nu_altDOCU->ViewCustomAttributes = "";

			// nu_altRUSE
			if (strval($this->nu_altRUSE->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRUSE->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRUSE->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altRUSE->ViewValue .= ew_ValueSeparator(1,$this->nu_altRUSE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altRUSE->ViewValue = $this->nu_altRUSE->CurrentValue;
				}
			} else {
				$this->nu_altRUSE->ViewValue = NULL;
			}
			$this->nu_altRUSE->ViewCustomAttributes = "";

			// nu_altTIME
			if (strval($this->nu_altTIME->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTIME->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTIME->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altTIME->ViewValue .= ew_ValueSeparator(1,$this->nu_altTIME) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altTIME->ViewValue = $this->nu_altTIME->CurrentValue;
				}
			} else {
				$this->nu_altTIME->ViewValue = NULL;
			}
			$this->nu_altTIME->ViewCustomAttributes = "";

			// nu_altSTOR
			if (strval($this->nu_altSTOR->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSTOR->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSTOR->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altSTOR->ViewValue .= ew_ValueSeparator(1,$this->nu_altSTOR) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altSTOR->ViewValue = $this->nu_altSTOR->CurrentValue;
				}
			} else {
				$this->nu_altSTOR->ViewValue = NULL;
			}
			$this->nu_altSTOR->ViewCustomAttributes = "";

			// nu_altPVOL
			if (strval($this->nu_altPVOL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPVOL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPVOL->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPVOL->ViewValue .= ew_ValueSeparator(1,$this->nu_altPVOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPVOL->ViewValue = $this->nu_altPVOL->CurrentValue;
				}
			} else {
				$this->nu_altPVOL->ViewValue = NULL;
			}
			$this->nu_altPVOL->ViewCustomAttributes = "";

			// nu_altACAP
			if (strval($this->nu_altACAP->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altACAP->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altACAP->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altACAP->ViewValue .= ew_ValueSeparator(1,$this->nu_altACAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altACAP->ViewValue = $this->nu_altACAP->CurrentValue;
				}
			} else {
				$this->nu_altACAP->ViewValue = NULL;
			}
			$this->nu_altACAP->ViewCustomAttributes = "";

			// nu_altPCAP
			if (strval($this->nu_altPCAP->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCAP->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCAP->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPCAP->ViewValue .= ew_ValueSeparator(1,$this->nu_altPCAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPCAP->ViewValue = $this->nu_altPCAP->CurrentValue;
				}
			} else {
				$this->nu_altPCAP->ViewValue = NULL;
			}
			$this->nu_altPCAP->ViewCustomAttributes = "";

			// nu_altPCON
			if (strval($this->nu_altPCON->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPCON->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [ic_ativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCON->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPCON->ViewValue .= ew_ValueSeparator(1,$this->nu_altPCON) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPCON->ViewValue = $this->nu_altPCON->CurrentValue;
				}
			} else {
				$this->nu_altPCON->ViewValue = NULL;
			}
			$this->nu_altPCON->ViewCustomAttributes = "";

			// nu_altAPEX
			if (strval($this->nu_altAPEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altAPEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altAPEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altAPEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altAPEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altAPEX->ViewValue = $this->nu_altAPEX->CurrentValue;
				}
			} else {
				$this->nu_altAPEX->ViewValue = NULL;
			}
			$this->nu_altAPEX->ViewCustomAttributes = "";

			// nu_altPLEX
			if (strval($this->nu_altPLEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altPLEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPLEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altPLEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altPLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altPLEX->ViewValue = $this->nu_altPLEX->CurrentValue;
				}
			} else {
				$this->nu_altPLEX->ViewValue = NULL;
			}
			$this->nu_altPLEX->ViewCustomAttributes = "";

			// nu_altLTEX
			if (strval($this->nu_altLTEX->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altLTEX->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altLTEX->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altLTEX->ViewValue .= ew_ValueSeparator(1,$this->nu_altLTEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altLTEX->ViewValue = $this->nu_altLTEX->CurrentValue;
				}
			} else {
				$this->nu_altLTEX->ViewValue = NULL;
			}
			$this->nu_altLTEX->ViewCustomAttributes = "";

			// nu_altTOOL
			if (strval($this->nu_altTOOL->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altTOOL->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTOOL->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altTOOL->ViewValue .= ew_ValueSeparator(1,$this->nu_altTOOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altTOOL->ViewValue = $this->nu_altTOOL->CurrentValue;
				}
			} else {
				$this->nu_altTOOL->ViewValue = NULL;
			}
			$this->nu_altTOOL->ViewCustomAttributes = "";

			// nu_altSITE
			if (strval($this->nu_altSITE->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altSITE->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], [ds_alternativa] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_peso] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSITE->ViewValue = $rswrk->fields('DispFld');
					$this->nu_altSITE->ViewValue .= ew_ValueSeparator(1,$this->nu_altSITE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_altSITE->ViewValue = $this->nu_altSITE->CurrentValue;
				}
			} else {
				$this->nu_altSITE->ViewValue = NULL;
			}
			$this->nu_altSITE->ViewCustomAttributes = "";

			// co_quePREC
			if (strval($this->co_quePREC->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePREC->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePREC, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePREC->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePREC->ViewValue .= ew_ValueSeparator(1,$this->co_quePREC) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePREC->ViewValue = $this->co_quePREC->CurrentValue;
				}
			} else {
				$this->co_quePREC->ViewValue = NULL;
			}
			$this->co_quePREC->ViewCustomAttributes = "";

			// co_queFLEX
			if (strval($this->co_queFLEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queFLEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queFLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queFLEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_queFLEX->ViewValue .= ew_ValueSeparator(1,$this->co_queFLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queFLEX->ViewValue = $this->co_queFLEX->CurrentValue;
				}
			} else {
				$this->co_queFLEX->ViewValue = NULL;
			}
			$this->co_queFLEX->ViewCustomAttributes = "";

			// co_queRESL
			if (strval($this->co_queRESL->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRESL->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRESL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queRESL->ViewValue = $rswrk->fields('DispFld');
					$this->co_queRESL->ViewValue .= ew_ValueSeparator(1,$this->co_queRESL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queRESL->ViewValue = $this->co_queRESL->CurrentValue;
				}
			} else {
				$this->co_queRESL->ViewValue = NULL;
			}
			$this->co_queRESL->ViewCustomAttributes = "";

			// co_queTEAM
			if (strval($this->co_queTEAM->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTEAM->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTEAM, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queTEAM->ViewValue = $rswrk->fields('DispFld');
					$this->co_queTEAM->ViewValue .= ew_ValueSeparator(1,$this->co_queTEAM) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queTEAM->ViewValue = $this->co_queTEAM->CurrentValue;
				}
			} else {
				$this->co_queTEAM->ViewValue = NULL;
			}
			$this->co_queTEAM->ViewCustomAttributes = "";

			// co_quePMAT
			if (strval($this->co_quePMAT->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePMAT->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePMAT, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePMAT->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePMAT->ViewValue .= ew_ValueSeparator(1,$this->co_quePMAT) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePMAT->ViewValue = $this->co_quePMAT->CurrentValue;
				}
			} else {
				$this->co_quePMAT->ViewValue = NULL;
			}
			$this->co_quePMAT->ViewCustomAttributes = "";

			// co_queRELY
			if (strval($this->co_queRELY->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRELY->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRELY, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queRELY->ViewValue = $rswrk->fields('DispFld');
					$this->co_queRELY->ViewValue .= ew_ValueSeparator(1,$this->co_queRELY) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queRELY->ViewValue = $this->co_queRELY->CurrentValue;
				}
			} else {
				$this->co_queRELY->ViewValue = NULL;
			}
			$this->co_queRELY->ViewCustomAttributes = "";

			// co_queDATA
			if (strval($this->co_queDATA->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queDATA->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queDATA, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queDATA->ViewValue = $rswrk->fields('DispFld');
					$this->co_queDATA->ViewValue .= ew_ValueSeparator(1,$this->co_queDATA) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queDATA->ViewValue = $this->co_queDATA->CurrentValue;
				}
			} else {
				$this->co_queDATA->ViewValue = NULL;
			}
			$this->co_queDATA->ViewCustomAttributes = "";

			// co_queCPLX1
			if (strval($this->co_queCPLX1->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX1->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX1, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX1->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX1->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX1) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX1->ViewValue = $this->co_queCPLX1->CurrentValue;
				}
			} else {
				$this->co_queCPLX1->ViewValue = NULL;
			}
			$this->co_queCPLX1->ViewCustomAttributes = "";

			// co_queCPLX2
			if (strval($this->co_queCPLX2->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX2->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX2->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX2->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX2) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX2->ViewValue = $this->co_queCPLX2->CurrentValue;
				}
			} else {
				$this->co_queCPLX2->ViewValue = NULL;
			}
			$this->co_queCPLX2->ViewCustomAttributes = "";

			// co_queCPLX3
			if (strval($this->co_queCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX3->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX3->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX3->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX3) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX3->ViewValue = $this->co_queCPLX3->CurrentValue;
				}
			} else {
				$this->co_queCPLX3->ViewValue = NULL;
			}
			$this->co_queCPLX3->ViewCustomAttributes = "";

			// co_queCPLX4
			if (strval($this->co_queCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX4->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX4->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX4->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX4) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX4->ViewValue = $this->co_queCPLX4->CurrentValue;
				}
			} else {
				$this->co_queCPLX4->ViewValue = NULL;
			}
			$this->co_queCPLX4->ViewCustomAttributes = "";

			// co_queCPLX5
			if (strval($this->co_queCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queCPLX5->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queCPLX5->ViewValue = $rswrk->fields('DispFld');
					$this->co_queCPLX5->ViewValue .= ew_ValueSeparator(1,$this->co_queCPLX5) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queCPLX5->ViewValue = $this->co_queCPLX5->CurrentValue;
				}
			} else {
				$this->co_queCPLX5->ViewValue = NULL;
			}
			$this->co_queCPLX5->ViewCustomAttributes = "";

			// co_queDOCU
			if (strval($this->co_queDOCU->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queDOCU->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queDOCU->ViewValue = $rswrk->fields('DispFld');
					$this->co_queDOCU->ViewValue .= ew_ValueSeparator(1,$this->co_queDOCU) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queDOCU->ViewValue = $this->co_queDOCU->CurrentValue;
				}
			} else {
				$this->co_queDOCU->ViewValue = NULL;
			}
			$this->co_queDOCU->ViewCustomAttributes = "";

			// co_queRUSE
			if (strval($this->co_queRUSE->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queRUSE->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queRUSE->ViewValue = $rswrk->fields('DispFld');
					$this->co_queRUSE->ViewValue .= ew_ValueSeparator(1,$this->co_queRUSE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queRUSE->ViewValue = $this->co_queRUSE->CurrentValue;
				}
			} else {
				$this->co_queRUSE->ViewValue = NULL;
			}
			$this->co_queRUSE->ViewCustomAttributes = "";

			// co_queTIME
			if (strval($this->co_queTIME->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTIME->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queTIME->ViewValue = $rswrk->fields('DispFld');
					$this->co_queTIME->ViewValue .= ew_ValueSeparator(1,$this->co_queTIME) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queTIME->ViewValue = $this->co_queTIME->CurrentValue;
				}
			} else {
				$this->co_queTIME->ViewValue = NULL;
			}
			$this->co_queTIME->ViewCustomAttributes = "";

			// co_queSTOR
			if (strval($this->co_queSTOR->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queSTOR->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queSTOR->ViewValue = $rswrk->fields('DispFld');
					$this->co_queSTOR->ViewValue .= ew_ValueSeparator(1,$this->co_queSTOR) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queSTOR->ViewValue = $this->co_queSTOR->CurrentValue;
				}
			} else {
				$this->co_queSTOR->ViewValue = NULL;
			}
			$this->co_queSTOR->ViewCustomAttributes = "";

			// co_quePVOL
			if (strval($this->co_quePVOL->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePVOL->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePVOL->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePVOL->ViewValue .= ew_ValueSeparator(1,$this->co_quePVOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePVOL->ViewValue = $this->co_quePVOL->CurrentValue;
				}
			} else {
				$this->co_quePVOL->ViewValue = NULL;
			}
			$this->co_quePVOL->ViewCustomAttributes = "";

			// co_queACAP
			if (strval($this->co_queACAP->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queACAP->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queACAP->ViewValue = $rswrk->fields('DispFld');
					$this->co_queACAP->ViewValue .= ew_ValueSeparator(1,$this->co_queACAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queACAP->ViewValue = $this->co_queACAP->CurrentValue;
				}
			} else {
				$this->co_queACAP->ViewValue = NULL;
			}
			$this->co_queACAP->ViewCustomAttributes = "";

			// co_quePCAP
			if (strval($this->co_quePCAP->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePCAP->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePCAP->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePCAP->ViewValue .= ew_ValueSeparator(1,$this->co_quePCAP) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePCAP->ViewValue = $this->co_quePCAP->CurrentValue;
				}
			} else {
				$this->co_quePCAP->ViewValue = NULL;
			}
			$this->co_quePCAP->ViewCustomAttributes = "";

			// co_quePCON
			if (strval($this->co_quePCON->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePCON->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePCON->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePCON->ViewValue .= ew_ValueSeparator(1,$this->co_quePCON) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePCON->ViewValue = $this->co_quePCON->CurrentValue;
				}
			} else {
				$this->co_quePCON->ViewValue = NULL;
			}
			$this->co_quePCON->ViewCustomAttributes = "";

			// co_queAPEX
			if (strval($this->co_queAPEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queAPEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queAPEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_queAPEX->ViewValue .= ew_ValueSeparator(1,$this->co_queAPEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queAPEX->ViewValue = $this->co_queAPEX->CurrentValue;
				}
			} else {
				$this->co_queAPEX->ViewValue = NULL;
			}
			$this->co_queAPEX->ViewCustomAttributes = "";

			// co_quePLEX
			if (strval($this->co_quePLEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_quePLEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_quePLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_quePLEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_quePLEX->ViewValue .= ew_ValueSeparator(1,$this->co_quePLEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_quePLEX->ViewValue = $this->co_quePLEX->CurrentValue;
				}
			} else {
				$this->co_quePLEX->ViewValue = NULL;
			}
			$this->co_quePLEX->ViewCustomAttributes = "";

			// co_queLTEX
			if (strval($this->co_queLTEX->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queLTEX->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queLTEX->ViewValue = $rswrk->fields('DispFld');
					$this->co_queLTEX->ViewValue .= ew_ValueSeparator(1,$this->co_queLTEX) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queLTEX->ViewValue = $this->co_queLTEX->CurrentValue;
				}
			} else {
				$this->co_queLTEX->ViewValue = NULL;
			}
			$this->co_queLTEX->ViewCustomAttributes = "";

			// co_queTOOL
			if (strval($this->co_queTOOL->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queTOOL->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queTOOL->ViewValue = $rswrk->fields('DispFld');
					$this->co_queTOOL->ViewValue .= ew_ValueSeparator(1,$this->co_queTOOL) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queTOOL->ViewValue = $this->co_queTOOL->CurrentValue;
				}
			} else {
				$this->co_queTOOL->ViewValue = NULL;
			}
			$this->co_queTOOL->ViewCustomAttributes = "";

			// co_queSITE
			if (strval($this->co_queSITE->CurrentValue) <> "") {
				$sFilterWrk = "[co_questao]" . ew_SearchString("=", $this->co_queSITE->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_questao], [co_questao] AS [DispFld], [no_questao] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciiquestao]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_queSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_queSITE->ViewValue = $rswrk->fields('DispFld');
					$this->co_queSITE->ViewValue .= ew_ValueSeparator(1,$this->co_queSITE) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->co_queSITE->ViewValue = $this->co_queSITE->CurrentValue;
				}
			} else {
				$this->co_queSITE->ViewValue = NULL;
			}
			$this->co_queSITE->ViewCustomAttributes = "";

			// nu_versaoValoracao
			$this->nu_versaoValoracao->LinkCustomAttributes = "";
			$this->nu_versaoValoracao->HrefValue = "";
			$this->nu_versaoValoracao->TooltipValue = "";

			// ic_metCalibracao
			$this->ic_metCalibracao->LinkCustomAttributes = "";
			$this->ic_metCalibracao->HrefValue = "";
			$this->ic_metCalibracao->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// ic_tpAtualizacao
			$this->ic_tpAtualizacao->LinkCustomAttributes = "";
			$this->ic_tpAtualizacao->HrefValue = "";
			$this->ic_tpAtualizacao->TooltipValue = "";

			// qt_linhasCodLingPf
			$this->qt_linhasCodLingPf->LinkCustomAttributes = "";
			$this->qt_linhasCodLingPf->HrefValue = "";
			$this->qt_linhasCodLingPf->TooltipValue = "";

			// vr_ipMin
			$this->vr_ipMin->LinkCustomAttributes = "";
			$this->vr_ipMin->HrefValue = "";
			$this->vr_ipMin->TooltipValue = "";

			// vr_ipMed
			$this->vr_ipMed->LinkCustomAttributes = "";
			$this->vr_ipMed->HrefValue = "";
			$this->vr_ipMed->TooltipValue = "";

			// vr_ipMax
			$this->vr_ipMax->LinkCustomAttributes = "";
			$this->vr_ipMax->HrefValue = "";
			$this->vr_ipMax->TooltipValue = "";

			// vr_constanteA
			$this->vr_constanteA->LinkCustomAttributes = "";
			$this->vr_constanteA->HrefValue = "";
			$this->vr_constanteA->TooltipValue = "";

			// vr_constanteB
			$this->vr_constanteB->LinkCustomAttributes = "";
			$this->vr_constanteB->HrefValue = "";
			$this->vr_constanteB->TooltipValue = "";

			// vr_constanteC
			$this->vr_constanteC->LinkCustomAttributes = "";
			$this->vr_constanteC->HrefValue = "";
			$this->vr_constanteC->TooltipValue = "";

			// vr_constanteD
			$this->vr_constanteD->LinkCustomAttributes = "";
			$this->vr_constanteD->HrefValue = "";
			$this->vr_constanteD->TooltipValue = "";

			// nu_altPREC
			$this->nu_altPREC->LinkCustomAttributes = "";
			$this->nu_altPREC->HrefValue = "";
			$this->nu_altPREC->TooltipValue = "";

			// nu_altFLEX
			$this->nu_altFLEX->LinkCustomAttributes = "";
			$this->nu_altFLEX->HrefValue = "";
			$this->nu_altFLEX->TooltipValue = "";

			// nu_altRESL
			$this->nu_altRESL->LinkCustomAttributes = "";
			$this->nu_altRESL->HrefValue = "";
			$this->nu_altRESL->TooltipValue = "";

			// nu_altTEAM
			$this->nu_altTEAM->LinkCustomAttributes = "";
			$this->nu_altTEAM->HrefValue = "";
			$this->nu_altTEAM->TooltipValue = "";

			// nu_altPMAT
			$this->nu_altPMAT->LinkCustomAttributes = "";
			$this->nu_altPMAT->HrefValue = "";
			$this->nu_altPMAT->TooltipValue = "";

			// nu_altRELY
			$this->nu_altRELY->LinkCustomAttributes = "";
			$this->nu_altRELY->HrefValue = "";
			$this->nu_altRELY->TooltipValue = "";

			// nu_altDATA
			$this->nu_altDATA->LinkCustomAttributes = "";
			$this->nu_altDATA->HrefValue = "";
			$this->nu_altDATA->TooltipValue = "";

			// nu_altCPLX1
			$this->nu_altCPLX1->LinkCustomAttributes = "";
			$this->nu_altCPLX1->HrefValue = "";
			$this->nu_altCPLX1->TooltipValue = "";

			// nu_altCPLX2
			$this->nu_altCPLX2->LinkCustomAttributes = "";
			$this->nu_altCPLX2->HrefValue = "";
			$this->nu_altCPLX2->TooltipValue = "";

			// nu_altCPLX3
			$this->nu_altCPLX3->LinkCustomAttributes = "";
			$this->nu_altCPLX3->HrefValue = "";
			$this->nu_altCPLX3->TooltipValue = "";

			// nu_altCPLX4
			$this->nu_altCPLX4->LinkCustomAttributes = "";
			$this->nu_altCPLX4->HrefValue = "";
			$this->nu_altCPLX4->TooltipValue = "";

			// nu_altCPLX5
			$this->nu_altCPLX5->LinkCustomAttributes = "";
			$this->nu_altCPLX5->HrefValue = "";
			$this->nu_altCPLX5->TooltipValue = "";

			// nu_altDOCU
			$this->nu_altDOCU->LinkCustomAttributes = "";
			$this->nu_altDOCU->HrefValue = "";
			$this->nu_altDOCU->TooltipValue = "";

			// nu_altRUSE
			$this->nu_altRUSE->LinkCustomAttributes = "";
			$this->nu_altRUSE->HrefValue = "";
			$this->nu_altRUSE->TooltipValue = "";

			// nu_altTIME
			$this->nu_altTIME->LinkCustomAttributes = "";
			$this->nu_altTIME->HrefValue = "";
			$this->nu_altTIME->TooltipValue = "";

			// nu_altSTOR
			$this->nu_altSTOR->LinkCustomAttributes = "";
			$this->nu_altSTOR->HrefValue = "";
			$this->nu_altSTOR->TooltipValue = "";

			// nu_altPVOL
			$this->nu_altPVOL->LinkCustomAttributes = "";
			$this->nu_altPVOL->HrefValue = "";
			$this->nu_altPVOL->TooltipValue = "";

			// nu_altACAP
			$this->nu_altACAP->LinkCustomAttributes = "";
			$this->nu_altACAP->HrefValue = "";
			$this->nu_altACAP->TooltipValue = "";

			// nu_altPCAP
			$this->nu_altPCAP->LinkCustomAttributes = "";
			$this->nu_altPCAP->HrefValue = "";
			$this->nu_altPCAP->TooltipValue = "";

			// nu_altPCON
			$this->nu_altPCON->LinkCustomAttributes = "";
			$this->nu_altPCON->HrefValue = "";
			$this->nu_altPCON->TooltipValue = "";

			// nu_altAPEX
			$this->nu_altAPEX->LinkCustomAttributes = "";
			$this->nu_altAPEX->HrefValue = "";
			$this->nu_altAPEX->TooltipValue = "";

			// nu_altPLEX
			$this->nu_altPLEX->LinkCustomAttributes = "";
			$this->nu_altPLEX->HrefValue = "";
			$this->nu_altPLEX->TooltipValue = "";

			// nu_altLTEX
			$this->nu_altLTEX->LinkCustomAttributes = "";
			$this->nu_altLTEX->HrefValue = "";
			$this->nu_altLTEX->TooltipValue = "";

			// nu_altTOOL
			$this->nu_altTOOL->LinkCustomAttributes = "";
			$this->nu_altTOOL->HrefValue = "";
			$this->nu_altTOOL->TooltipValue = "";

			// nu_altSITE
			$this->nu_altSITE->LinkCustomAttributes = "";
			$this->nu_altSITE->HrefValue = "";
			$this->nu_altSITE->TooltipValue = "";

			// co_quePREC
			$this->co_quePREC->LinkCustomAttributes = "";
			$this->co_quePREC->HrefValue = "";
			$this->co_quePREC->TooltipValue = "";

			// co_queFLEX
			$this->co_queFLEX->LinkCustomAttributes = "";
			$this->co_queFLEX->HrefValue = "";
			$this->co_queFLEX->TooltipValue = "";

			// co_queRESL
			$this->co_queRESL->LinkCustomAttributes = "";
			$this->co_queRESL->HrefValue = "";
			$this->co_queRESL->TooltipValue = "";

			// co_queTEAM
			$this->co_queTEAM->LinkCustomAttributes = "";
			$this->co_queTEAM->HrefValue = "";
			$this->co_queTEAM->TooltipValue = "";

			// co_quePMAT
			$this->co_quePMAT->LinkCustomAttributes = "";
			$this->co_quePMAT->HrefValue = "";
			$this->co_quePMAT->TooltipValue = "";

			// co_queRELY
			$this->co_queRELY->LinkCustomAttributes = "";
			$this->co_queRELY->HrefValue = "";
			$this->co_queRELY->TooltipValue = "";

			// co_queDATA
			$this->co_queDATA->LinkCustomAttributes = "";
			$this->co_queDATA->HrefValue = "";
			$this->co_queDATA->TooltipValue = "";

			// co_queCPLX1
			$this->co_queCPLX1->LinkCustomAttributes = "";
			$this->co_queCPLX1->HrefValue = "";
			$this->co_queCPLX1->TooltipValue = "";

			// co_queCPLX2
			$this->co_queCPLX2->LinkCustomAttributes = "";
			$this->co_queCPLX2->HrefValue = "";
			$this->co_queCPLX2->TooltipValue = "";

			// co_queCPLX3
			$this->co_queCPLX3->LinkCustomAttributes = "";
			$this->co_queCPLX3->HrefValue = "";
			$this->co_queCPLX3->TooltipValue = "";

			// co_queCPLX4
			$this->co_queCPLX4->LinkCustomAttributes = "";
			$this->co_queCPLX4->HrefValue = "";
			$this->co_queCPLX4->TooltipValue = "";

			// co_queCPLX5
			$this->co_queCPLX5->LinkCustomAttributes = "";
			$this->co_queCPLX5->HrefValue = "";
			$this->co_queCPLX5->TooltipValue = "";

			// co_queDOCU
			$this->co_queDOCU->LinkCustomAttributes = "";
			$this->co_queDOCU->HrefValue = "";
			$this->co_queDOCU->TooltipValue = "";

			// co_queRUSE
			$this->co_queRUSE->LinkCustomAttributes = "";
			$this->co_queRUSE->HrefValue = "";
			$this->co_queRUSE->TooltipValue = "";

			// co_queTIME
			$this->co_queTIME->LinkCustomAttributes = "";
			$this->co_queTIME->HrefValue = "";
			$this->co_queTIME->TooltipValue = "";

			// co_queSTOR
			$this->co_queSTOR->LinkCustomAttributes = "";
			$this->co_queSTOR->HrefValue = "";
			$this->co_queSTOR->TooltipValue = "";

			// co_quePVOL
			$this->co_quePVOL->LinkCustomAttributes = "";
			$this->co_quePVOL->HrefValue = "";
			$this->co_quePVOL->TooltipValue = "";

			// co_queACAP
			$this->co_queACAP->LinkCustomAttributes = "";
			$this->co_queACAP->HrefValue = "";
			$this->co_queACAP->TooltipValue = "";

			// co_quePCAP
			$this->co_quePCAP->LinkCustomAttributes = "";
			$this->co_quePCAP->HrefValue = "";
			$this->co_quePCAP->TooltipValue = "";

			// co_quePCON
			$this->co_quePCON->LinkCustomAttributes = "";
			$this->co_quePCON->HrefValue = "";
			$this->co_quePCON->TooltipValue = "";

			// co_queAPEX
			$this->co_queAPEX->LinkCustomAttributes = "";
			$this->co_queAPEX->HrefValue = "";
			$this->co_queAPEX->TooltipValue = "";

			// co_quePLEX
			$this->co_quePLEX->LinkCustomAttributes = "";
			$this->co_quePLEX->HrefValue = "";
			$this->co_quePLEX->TooltipValue = "";

			// co_queLTEX
			$this->co_queLTEX->LinkCustomAttributes = "";
			$this->co_queLTEX->HrefValue = "";
			$this->co_queLTEX->TooltipValue = "";

			// co_queTOOL
			$this->co_queTOOL->LinkCustomAttributes = "";
			$this->co_queTOOL->HrefValue = "";
			$this->co_queTOOL->TooltipValue = "";

			// co_queSITE
			$this->co_queSITE->LinkCustomAttributes = "";
			$this->co_queSITE->HrefValue = "";
			$this->co_queSITE->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_ambiente_valoracao\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_ambiente_valoracao',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fambiente_valoracaoview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ambiente_valoracaolist.php", $this->TableVar);
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
if (!isset($ambiente_valoracao_view)) $ambiente_valoracao_view = new cambiente_valoracao_view();

// Page init
$ambiente_valoracao_view->Page_Init();

// Page main
$ambiente_valoracao_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_valoracao_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($ambiente_valoracao->Export == "") { ?>
<script type="text/javascript">

// Page object
var ambiente_valoracao_view = new ew_Page("ambiente_valoracao_view");
ambiente_valoracao_view.PageID = "view"; // Page ID
var EW_PAGE_ID = ambiente_valoracao_view.PageID; // For backward compatibility

// Form object
var fambiente_valoracaoview = new ew_Form("fambiente_valoracaoview");

// Form_CustomValidate event
fambiente_valoracaoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_valoracaoview.ValidateRequired = true;
<?php } else { ?>
fambiente_valoracaoview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fambiente_valoracaoview.MultiPage = new ew_MultiPage("fambiente_valoracaoview",
	[["x_nu_versaoValoracao",1],["x_ic_metCalibracao",1],["x_ic_tpAtualizacao",1],["x_qt_linhasCodLingPf",1],["x_vr_ipMin",5],["x_vr_ipMed",5],["x_vr_ipMax",5],["x_vr_constanteA",2],["x_vr_constanteB",2],["x_vr_constanteC",2],["x_vr_constanteD",2],["x_nu_altPREC",4],["x_nu_altFLEX",4],["x_nu_altRESL",4],["x_nu_altTEAM",4],["x_nu_altPMAT",4],["x_nu_altRELY",4],["x_nu_altDATA",4],["x_nu_altCPLX1",4],["x_nu_altCPLX2",4],["x_nu_altCPLX3",4],["x_nu_altCPLX4",4],["x_nu_altCPLX5",4],["x_nu_altDOCU",4],["x_nu_altRUSE",4],["x_nu_altTIME",4],["x_nu_altSTOR",4],["x_nu_altPVOL",4],["x_nu_altACAP",4],["x_nu_altPCAP",4],["x_nu_altPCON",4],["x_nu_altAPEX",4],["x_nu_altPLEX",4],["x_nu_altLTEX",4],["x_nu_altTOOL",4],["x_nu_altSITE",4],["x_co_quePREC",3],["x_co_queFLEX",3],["x_co_queRESL",3],["x_co_queTEAM",3],["x_co_quePMAT",3],["x_co_queRELY",3],["x_co_queDATA",3],["x_co_queCPLX1",3],["x_co_queCPLX2",3],["x_co_queCPLX3",3],["x_co_queCPLX4",3],["x_co_queCPLX5",3],["x_co_queDOCU",3],["x_co_queRUSE",3],["x_co_queTIME",3],["x_co_queSTOR",3],["x_co_quePVOL",3],["x_co_queACAP",3],["x_co_quePCAP",3],["x_co_quePCON",3],["x_co_queAPEX",3],["x_co_quePLEX",3],["x_co_queLTEX",3],["x_co_queTOOL",3],["x_co_queSITE",3]]
);

// Dynamic selection lists
fambiente_valoracaoview.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altPREC"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altFLEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altRESL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altTEAM"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altPMAT"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altRELY"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altDATA"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altCPLX1"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altCPLX2"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altCPLX3"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altCPLX4"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altCPLX5"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altDOCU"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altRUSE"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altTIME"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altSTOR"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altPVOL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altACAP"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altPCAP"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altPCON"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altAPEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altPLEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altLTEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altTOOL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_nu_altSITE"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","x_ds_alternativa","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_quePREC"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queFLEX"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queRESL"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queTEAM"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_quePMAT"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queRELY"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queDATA"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queCPLX1"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queCPLX2"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queCPLX3"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queCPLX4"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queCPLX5"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queDOCU"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queRUSE"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queTIME"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queSTOR"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_quePVOL"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queACAP"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_quePCAP"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_quePCON"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queAPEX"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_quePLEX"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queLTEX"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queTOOL"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_valoracaoview.Lists["x_co_queSITE"] = {"LinkField":"x_co_questao","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_questao","x_no_questao","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($ambiente_valoracao->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($ambiente_valoracao->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $ambiente_valoracao_view->ExportOptions->Render("body") ?>
<?php if (!$ambiente_valoracao_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($ambiente_valoracao_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $ambiente_valoracao_view->ShowPageHeader(); ?>
<?php
$ambiente_valoracao_view->ShowMessage();
?>
<form name="fambiente_valoracaoview" id="fambiente_valoracaoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ambiente_valoracao">
<?php if ($ambiente_valoracao->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="ambiente_valoracao_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_ambiente_valoracao1" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(1) ?></a></li>
		<li><a href="#tab_ambiente_valoracao2" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(2) ?></a></li>
		<li><a href="#tab_ambiente_valoracao3" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(3) ?></a></li>
		<li><a href="#tab_ambiente_valoracao4" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(4) ?></a></li>
		<li><a href="#tab_ambiente_valoracao5" data-toggle="tab"><?php echo $ambiente_valoracao->PageCaption(5) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
		<div class="tab-pane active" id="tab_ambiente_valoracao1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoview1" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->nu_versaoValoracao->Visible) { // nu_versaoValoracao ?>
	<tr id="r_nu_versaoValoracao">
		<td><span id="elh_ambiente_valoracao_nu_versaoValoracao"><?php echo $ambiente_valoracao->nu_versaoValoracao->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_versaoValoracao->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_versaoValoracao" class="control-group">
<span<?php echo $ambiente_valoracao->nu_versaoValoracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_versaoValoracao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->ic_metCalibracao->Visible) { // ic_metCalibracao ?>
	<tr id="r_ic_metCalibracao">
		<td><span id="elh_ambiente_valoracao_ic_metCalibracao"><?php echo $ambiente_valoracao->ic_metCalibracao->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->ic_metCalibracao->CellAttributes() ?>>
<span id="el_ambiente_valoracao_ic_metCalibracao" class="control-group">
<span<?php echo $ambiente_valoracao->ic_metCalibracao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->ic_metCalibracao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->dh_inclusao->Visible) { // dh_inclusao ?>
	<tr id="r_dh_inclusao">
		<td><span id="elh_ambiente_valoracao_dh_inclusao"><?php echo $ambiente_valoracao->dh_inclusao->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->dh_inclusao->CellAttributes() ?>>
<span id="el_ambiente_valoracao_dh_inclusao" class="control-group">
<span<?php echo $ambiente_valoracao->dh_inclusao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->dh_inclusao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<tr id="r_nu_usuarioResp">
		<td><span id="elh_ambiente_valoracao_nu_usuarioResp"><?php echo $ambiente_valoracao->nu_usuarioResp->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_usuarioResp->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_usuarioResp" class="control-group">
<span<?php echo $ambiente_valoracao->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_usuarioResp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->ic_tpAtualizacao->Visible) { // ic_tpAtualizacao ?>
	<tr id="r_ic_tpAtualizacao">
		<td><span id="elh_ambiente_valoracao_ic_tpAtualizacao"><?php echo $ambiente_valoracao->ic_tpAtualizacao->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->ic_tpAtualizacao->CellAttributes() ?>>
<span id="el_ambiente_valoracao_ic_tpAtualizacao" class="control-group">
<span<?php echo $ambiente_valoracao->ic_tpAtualizacao->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->ic_tpAtualizacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->qt_linhasCodLingPf->Visible) { // qt_linhasCodLingPf ?>
	<tr id="r_qt_linhasCodLingPf">
		<td><span id="elh_ambiente_valoracao_qt_linhasCodLingPf"><?php echo $ambiente_valoracao->qt_linhasCodLingPf->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->qt_linhasCodLingPf->CellAttributes() ?>>
<span id="el_ambiente_valoracao_qt_linhasCodLingPf" class="control-group">
<span<?php echo $ambiente_valoracao->qt_linhasCodLingPf->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->qt_linhasCodLingPf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_ambiente_valoracao2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoview2" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->vr_constanteA->Visible) { // vr_constanteA ?>
	<tr id="r_vr_constanteA">
		<td><span id="elh_ambiente_valoracao_vr_constanteA"><?php echo $ambiente_valoracao->vr_constanteA->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_constanteA->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_constanteA" class="control-group">
<span<?php echo $ambiente_valoracao->vr_constanteA->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_constanteA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_constanteB->Visible) { // vr_constanteB ?>
	<tr id="r_vr_constanteB">
		<td><span id="elh_ambiente_valoracao_vr_constanteB"><?php echo $ambiente_valoracao->vr_constanteB->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_constanteB->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_constanteB" class="control-group">
<span<?php echo $ambiente_valoracao->vr_constanteB->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_constanteB->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_constanteC->Visible) { // vr_constanteC ?>
	<tr id="r_vr_constanteC">
		<td><span id="elh_ambiente_valoracao_vr_constanteC"><?php echo $ambiente_valoracao->vr_constanteC->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_constanteC->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_constanteC" class="control-group">
<span<?php echo $ambiente_valoracao->vr_constanteC->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_constanteC->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_constanteD->Visible) { // vr_constanteD ?>
	<tr id="r_vr_constanteD">
		<td><span id="elh_ambiente_valoracao_vr_constanteD"><?php echo $ambiente_valoracao->vr_constanteD->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_constanteD->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_constanteD" class="control-group">
<span<?php echo $ambiente_valoracao->vr_constanteD->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_constanteD->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_ambiente_valoracao3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoview3" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->co_quePREC->Visible) { // co_quePREC ?>
	<tr id="r_co_quePREC">
		<td><span id="elh_ambiente_valoracao_co_quePREC"><?php echo $ambiente_valoracao->co_quePREC->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePREC->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePREC" class="control-group">
<span<?php echo $ambiente_valoracao->co_quePREC->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_quePREC->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queFLEX->Visible) { // co_queFLEX ?>
	<tr id="r_co_queFLEX">
		<td><span id="elh_ambiente_valoracao_co_queFLEX"><?php echo $ambiente_valoracao->co_queFLEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queFLEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queFLEX" class="control-group">
<span<?php echo $ambiente_valoracao->co_queFLEX->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queFLEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queRESL->Visible) { // co_queRESL ?>
	<tr id="r_co_queRESL">
		<td><span id="elh_ambiente_valoracao_co_queRESL"><?php echo $ambiente_valoracao->co_queRESL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queRESL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queRESL" class="control-group">
<span<?php echo $ambiente_valoracao->co_queRESL->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queRESL->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queTEAM->Visible) { // co_queTEAM ?>
	<tr id="r_co_queTEAM">
		<td><span id="elh_ambiente_valoracao_co_queTEAM"><?php echo $ambiente_valoracao->co_queTEAM->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queTEAM->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queTEAM" class="control-group">
<span<?php echo $ambiente_valoracao->co_queTEAM->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queTEAM->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePMAT->Visible) { // co_quePMAT ?>
	<tr id="r_co_quePMAT">
		<td><span id="elh_ambiente_valoracao_co_quePMAT"><?php echo $ambiente_valoracao->co_quePMAT->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePMAT->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePMAT" class="control-group">
<span<?php echo $ambiente_valoracao->co_quePMAT->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_quePMAT->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queRELY->Visible) { // co_queRELY ?>
	<tr id="r_co_queRELY">
		<td><span id="elh_ambiente_valoracao_co_queRELY"><?php echo $ambiente_valoracao->co_queRELY->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queRELY->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queRELY" class="control-group">
<span<?php echo $ambiente_valoracao->co_queRELY->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queRELY->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queDATA->Visible) { // co_queDATA ?>
	<tr id="r_co_queDATA">
		<td><span id="elh_ambiente_valoracao_co_queDATA"><?php echo $ambiente_valoracao->co_queDATA->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queDATA->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queDATA" class="control-group">
<span<?php echo $ambiente_valoracao->co_queDATA->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queDATA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX1->Visible) { // co_queCPLX1 ?>
	<tr id="r_co_queCPLX1">
		<td><span id="elh_ambiente_valoracao_co_queCPLX1"><?php echo $ambiente_valoracao->co_queCPLX1->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX1->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX1" class="control-group">
<span<?php echo $ambiente_valoracao->co_queCPLX1->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queCPLX1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX2->Visible) { // co_queCPLX2 ?>
	<tr id="r_co_queCPLX2">
		<td><span id="elh_ambiente_valoracao_co_queCPLX2"><?php echo $ambiente_valoracao->co_queCPLX2->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX2->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX2" class="control-group">
<span<?php echo $ambiente_valoracao->co_queCPLX2->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queCPLX2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX3->Visible) { // co_queCPLX3 ?>
	<tr id="r_co_queCPLX3">
		<td><span id="elh_ambiente_valoracao_co_queCPLX3"><?php echo $ambiente_valoracao->co_queCPLX3->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX3->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX3" class="control-group">
<span<?php echo $ambiente_valoracao->co_queCPLX3->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queCPLX3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX4->Visible) { // co_queCPLX4 ?>
	<tr id="r_co_queCPLX4">
		<td><span id="elh_ambiente_valoracao_co_queCPLX4"><?php echo $ambiente_valoracao->co_queCPLX4->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX4->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX4" class="control-group">
<span<?php echo $ambiente_valoracao->co_queCPLX4->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queCPLX4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queCPLX5->Visible) { // co_queCPLX5 ?>
	<tr id="r_co_queCPLX5">
		<td><span id="elh_ambiente_valoracao_co_queCPLX5"><?php echo $ambiente_valoracao->co_queCPLX5->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queCPLX5->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queCPLX5" class="control-group">
<span<?php echo $ambiente_valoracao->co_queCPLX5->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queCPLX5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queDOCU->Visible) { // co_queDOCU ?>
	<tr id="r_co_queDOCU">
		<td><span id="elh_ambiente_valoracao_co_queDOCU"><?php echo $ambiente_valoracao->co_queDOCU->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queDOCU->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queDOCU" class="control-group">
<span<?php echo $ambiente_valoracao->co_queDOCU->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queDOCU->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queRUSE->Visible) { // co_queRUSE ?>
	<tr id="r_co_queRUSE">
		<td><span id="elh_ambiente_valoracao_co_queRUSE"><?php echo $ambiente_valoracao->co_queRUSE->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queRUSE->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queRUSE" class="control-group">
<span<?php echo $ambiente_valoracao->co_queRUSE->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queRUSE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queTIME->Visible) { // co_queTIME ?>
	<tr id="r_co_queTIME">
		<td><span id="elh_ambiente_valoracao_co_queTIME"><?php echo $ambiente_valoracao->co_queTIME->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queTIME->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queTIME" class="control-group">
<span<?php echo $ambiente_valoracao->co_queTIME->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queTIME->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queSTOR->Visible) { // co_queSTOR ?>
	<tr id="r_co_queSTOR">
		<td><span id="elh_ambiente_valoracao_co_queSTOR"><?php echo $ambiente_valoracao->co_queSTOR->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queSTOR->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queSTOR" class="control-group">
<span<?php echo $ambiente_valoracao->co_queSTOR->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queSTOR->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePVOL->Visible) { // co_quePVOL ?>
	<tr id="r_co_quePVOL">
		<td><span id="elh_ambiente_valoracao_co_quePVOL"><?php echo $ambiente_valoracao->co_quePVOL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePVOL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePVOL" class="control-group">
<span<?php echo $ambiente_valoracao->co_quePVOL->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_quePVOL->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queACAP->Visible) { // co_queACAP ?>
	<tr id="r_co_queACAP">
		<td><span id="elh_ambiente_valoracao_co_queACAP"><?php echo $ambiente_valoracao->co_queACAP->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queACAP->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queACAP" class="control-group">
<span<?php echo $ambiente_valoracao->co_queACAP->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queACAP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePCAP->Visible) { // co_quePCAP ?>
	<tr id="r_co_quePCAP">
		<td><span id="elh_ambiente_valoracao_co_quePCAP"><?php echo $ambiente_valoracao->co_quePCAP->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePCAP->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePCAP" class="control-group">
<span<?php echo $ambiente_valoracao->co_quePCAP->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_quePCAP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePCON->Visible) { // co_quePCON ?>
	<tr id="r_co_quePCON">
		<td><span id="elh_ambiente_valoracao_co_quePCON"><?php echo $ambiente_valoracao->co_quePCON->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePCON->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePCON" class="control-group">
<span<?php echo $ambiente_valoracao->co_quePCON->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_quePCON->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queAPEX->Visible) { // co_queAPEX ?>
	<tr id="r_co_queAPEX">
		<td><span id="elh_ambiente_valoracao_co_queAPEX"><?php echo $ambiente_valoracao->co_queAPEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queAPEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queAPEX" class="control-group">
<span<?php echo $ambiente_valoracao->co_queAPEX->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queAPEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_quePLEX->Visible) { // co_quePLEX ?>
	<tr id="r_co_quePLEX">
		<td><span id="elh_ambiente_valoracao_co_quePLEX"><?php echo $ambiente_valoracao->co_quePLEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_quePLEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_quePLEX" class="control-group">
<span<?php echo $ambiente_valoracao->co_quePLEX->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_quePLEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queLTEX->Visible) { // co_queLTEX ?>
	<tr id="r_co_queLTEX">
		<td><span id="elh_ambiente_valoracao_co_queLTEX"><?php echo $ambiente_valoracao->co_queLTEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queLTEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queLTEX" class="control-group">
<span<?php echo $ambiente_valoracao->co_queLTEX->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queLTEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queTOOL->Visible) { // co_queTOOL ?>
	<tr id="r_co_queTOOL">
		<td><span id="elh_ambiente_valoracao_co_queTOOL"><?php echo $ambiente_valoracao->co_queTOOL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queTOOL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queTOOL" class="control-group">
<span<?php echo $ambiente_valoracao->co_queTOOL->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queTOOL->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->co_queSITE->Visible) { // co_queSITE ?>
	<tr id="r_co_queSITE">
		<td><span id="elh_ambiente_valoracao_co_queSITE"><?php echo $ambiente_valoracao->co_queSITE->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->co_queSITE->CellAttributes() ?>>
<span id="el_ambiente_valoracao_co_queSITE" class="control-group">
<span<?php echo $ambiente_valoracao->co_queSITE->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->co_queSITE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_ambiente_valoracao4">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoview4" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->nu_altPREC->Visible) { // nu_altPREC ?>
	<tr id="r_nu_altPREC">
		<td><span id="elh_ambiente_valoracao_nu_altPREC"><?php echo $ambiente_valoracao->nu_altPREC->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPREC->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPREC" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altPREC->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altPREC->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altFLEX->Visible) { // nu_altFLEX ?>
	<tr id="r_nu_altFLEX">
		<td><span id="elh_ambiente_valoracao_nu_altFLEX"><?php echo $ambiente_valoracao->nu_altFLEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altFLEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altFLEX" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altFLEX->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altFLEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altRESL->Visible) { // nu_altRESL ?>
	<tr id="r_nu_altRESL">
		<td><span id="elh_ambiente_valoracao_nu_altRESL"><?php echo $ambiente_valoracao->nu_altRESL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altRESL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altRESL" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altRESL->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altRESL->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altTEAM->Visible) { // nu_altTEAM ?>
	<tr id="r_nu_altTEAM">
		<td><span id="elh_ambiente_valoracao_nu_altTEAM"><?php echo $ambiente_valoracao->nu_altTEAM->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altTEAM->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altTEAM" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altTEAM->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altTEAM->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPMAT->Visible) { // nu_altPMAT ?>
	<tr id="r_nu_altPMAT">
		<td><span id="elh_ambiente_valoracao_nu_altPMAT"><?php echo $ambiente_valoracao->nu_altPMAT->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPMAT->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPMAT" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altPMAT->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altPMAT->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altRELY->Visible) { // nu_altRELY ?>
	<tr id="r_nu_altRELY">
		<td><span id="elh_ambiente_valoracao_nu_altRELY"><?php echo $ambiente_valoracao->nu_altRELY->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altRELY->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altRELY" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altRELY->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altRELY->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altDATA->Visible) { // nu_altDATA ?>
	<tr id="r_nu_altDATA">
		<td><span id="elh_ambiente_valoracao_nu_altDATA"><?php echo $ambiente_valoracao->nu_altDATA->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altDATA->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altDATA" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altDATA->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altDATA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX1->Visible) { // nu_altCPLX1 ?>
	<tr id="r_nu_altCPLX1">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX1"><?php echo $ambiente_valoracao->nu_altCPLX1->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX1->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX1" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altCPLX1->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altCPLX1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX2->Visible) { // nu_altCPLX2 ?>
	<tr id="r_nu_altCPLX2">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX2"><?php echo $ambiente_valoracao->nu_altCPLX2->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX2->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX2" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altCPLX2->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altCPLX2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX3->Visible) { // nu_altCPLX3 ?>
	<tr id="r_nu_altCPLX3">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX3"><?php echo $ambiente_valoracao->nu_altCPLX3->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX3->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX3" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altCPLX3->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altCPLX3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX4->Visible) { // nu_altCPLX4 ?>
	<tr id="r_nu_altCPLX4">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX4"><?php echo $ambiente_valoracao->nu_altCPLX4->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX4->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX4" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altCPLX4->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altCPLX4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altCPLX5->Visible) { // nu_altCPLX5 ?>
	<tr id="r_nu_altCPLX5">
		<td><span id="elh_ambiente_valoracao_nu_altCPLX5"><?php echo $ambiente_valoracao->nu_altCPLX5->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altCPLX5->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altCPLX5" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altCPLX5->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altCPLX5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altDOCU->Visible) { // nu_altDOCU ?>
	<tr id="r_nu_altDOCU">
		<td><span id="elh_ambiente_valoracao_nu_altDOCU"><?php echo $ambiente_valoracao->nu_altDOCU->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altDOCU->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altDOCU" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altDOCU->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altDOCU->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altRUSE->Visible) { // nu_altRUSE ?>
	<tr id="r_nu_altRUSE">
		<td><span id="elh_ambiente_valoracao_nu_altRUSE"><?php echo $ambiente_valoracao->nu_altRUSE->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altRUSE->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altRUSE" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altRUSE->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altRUSE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altTIME->Visible) { // nu_altTIME ?>
	<tr id="r_nu_altTIME">
		<td><span id="elh_ambiente_valoracao_nu_altTIME"><?php echo $ambiente_valoracao->nu_altTIME->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altTIME->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altTIME" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altTIME->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altTIME->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altSTOR->Visible) { // nu_altSTOR ?>
	<tr id="r_nu_altSTOR">
		<td><span id="elh_ambiente_valoracao_nu_altSTOR"><?php echo $ambiente_valoracao->nu_altSTOR->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altSTOR->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altSTOR" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altSTOR->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altSTOR->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPVOL->Visible) { // nu_altPVOL ?>
	<tr id="r_nu_altPVOL">
		<td><span id="elh_ambiente_valoracao_nu_altPVOL"><?php echo $ambiente_valoracao->nu_altPVOL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPVOL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPVOL" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altPVOL->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altPVOL->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altACAP->Visible) { // nu_altACAP ?>
	<tr id="r_nu_altACAP">
		<td><span id="elh_ambiente_valoracao_nu_altACAP"><?php echo $ambiente_valoracao->nu_altACAP->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altACAP->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altACAP" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altACAP->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altACAP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPCAP->Visible) { // nu_altPCAP ?>
	<tr id="r_nu_altPCAP">
		<td><span id="elh_ambiente_valoracao_nu_altPCAP"><?php echo $ambiente_valoracao->nu_altPCAP->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPCAP->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPCAP" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altPCAP->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altPCAP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPCON->Visible) { // nu_altPCON ?>
	<tr id="r_nu_altPCON">
		<td><span id="elh_ambiente_valoracao_nu_altPCON"><?php echo $ambiente_valoracao->nu_altPCON->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPCON->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPCON" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altPCON->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altPCON->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altAPEX->Visible) { // nu_altAPEX ?>
	<tr id="r_nu_altAPEX">
		<td><span id="elh_ambiente_valoracao_nu_altAPEX"><?php echo $ambiente_valoracao->nu_altAPEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altAPEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altAPEX" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altAPEX->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altAPEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altPLEX->Visible) { // nu_altPLEX ?>
	<tr id="r_nu_altPLEX">
		<td><span id="elh_ambiente_valoracao_nu_altPLEX"><?php echo $ambiente_valoracao->nu_altPLEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altPLEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altPLEX" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altPLEX->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altPLEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altLTEX->Visible) { // nu_altLTEX ?>
	<tr id="r_nu_altLTEX">
		<td><span id="elh_ambiente_valoracao_nu_altLTEX"><?php echo $ambiente_valoracao->nu_altLTEX->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altLTEX->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altLTEX" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altLTEX->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altLTEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altTOOL->Visible) { // nu_altTOOL ?>
	<tr id="r_nu_altTOOL">
		<td><span id="elh_ambiente_valoracao_nu_altTOOL"><?php echo $ambiente_valoracao->nu_altTOOL->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altTOOL->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altTOOL" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altTOOL->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altTOOL->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->nu_altSITE->Visible) { // nu_altSITE ?>
	<tr id="r_nu_altSITE">
		<td><span id="elh_ambiente_valoracao_nu_altSITE"><?php echo $ambiente_valoracao->nu_altSITE->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->nu_altSITE->CellAttributes() ?>>
<span id="el_ambiente_valoracao_nu_altSITE" class="control-group">
<span<?php echo $ambiente_valoracao->nu_altSITE->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->nu_altSITE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_ambiente_valoracao5">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_ambiente_valoracaoview5" class="table table-bordered table-striped">
<?php if ($ambiente_valoracao->vr_ipMin->Visible) { // vr_ipMin ?>
	<tr id="r_vr_ipMin">
		<td><span id="elh_ambiente_valoracao_vr_ipMin"><?php echo $ambiente_valoracao->vr_ipMin->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_ipMin->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_ipMin" class="control-group">
<span<?php echo $ambiente_valoracao->vr_ipMin->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_ipMed->Visible) { // vr_ipMed ?>
	<tr id="r_vr_ipMed">
		<td><span id="elh_ambiente_valoracao_vr_ipMed"><?php echo $ambiente_valoracao->vr_ipMed->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_ipMed->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_ipMed" class="control-group">
<span<?php echo $ambiente_valoracao->vr_ipMed->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMed->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_valoracao->vr_ipMax->Visible) { // vr_ipMax ?>
	<tr id="r_vr_ipMax">
		<td><span id="elh_ambiente_valoracao_vr_ipMax"><?php echo $ambiente_valoracao->vr_ipMax->FldCaption() ?></span></td>
		<td<?php echo $ambiente_valoracao->vr_ipMax->CellAttributes() ?>>
<span id="el_ambiente_valoracao_vr_ipMax" class="control-group">
<span<?php echo $ambiente_valoracao->vr_ipMax->ViewAttributes() ?>>
<?php echo $ambiente_valoracao->vr_ipMax->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
<?php if ($ambiente_valoracao->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
<?php if ($ambiente_valoracao->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($ambiente_valoracao_view->Pager)) $ambiente_valoracao_view->Pager = new cNumericPager($ambiente_valoracao_view->StartRec, $ambiente_valoracao_view->DisplayRecs, $ambiente_valoracao_view->TotalRecs, $ambiente_valoracao_view->RecRange) ?>
<?php if ($ambiente_valoracao_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($ambiente_valoracao_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_valoracao_view->PageUrl() ?>start=<?php echo $ambiente_valoracao_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($ambiente_valoracao_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_valoracao_view->PageUrl() ?>start=<?php echo $ambiente_valoracao_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($ambiente_valoracao_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $ambiente_valoracao_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($ambiente_valoracao_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_valoracao_view->PageUrl() ?>start=<?php echo $ambiente_valoracao_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($ambiente_valoracao_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_valoracao_view->PageUrl() ?>start=<?php echo $ambiente_valoracao_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
</form>
<script type="text/javascript">
fambiente_valoracaoview.Init();
</script>
<?php
$ambiente_valoracao_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($ambiente_valoracao->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$ambiente_valoracao_view->Page_Terminate();
?>
