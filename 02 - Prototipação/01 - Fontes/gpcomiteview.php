<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "gpcomiteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$gpcomite_view = NULL; // Initialize page object first

class cgpcomite_view extends cgpcomite {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'gpcomite';

	// Page object name
	var $PageObjName = 'gpcomite_view';

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

		// Table object (gpcomite)
		if (!isset($GLOBALS["gpcomite"])) {
			$GLOBALS["gpcomite"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["gpcomite"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_gpComite"] <> "") {
			$this->RecKey["nu_gpComite"] = $_GET["nu_gpComite"];
			$KeyUrl .= "&nu_gpComite=" . urlencode($this->RecKey["nu_gpComite"]);
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
			define("EW_TABLE_NAME", 'gpcomite', TRUE);

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
			$this->Page_Terminate("gpcomitelist.php");
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
		if (@$_GET["nu_gpComite"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_gpComite"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_gpComite->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_gpComite"] <> "") {
				$this->nu_gpComite->setQueryStringValue($_GET["nu_gpComite"]);
				$this->RecKey["nu_gpComite"] = $this->nu_gpComite->QueryStringValue;
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
						$this->Page_Terminate("gpcomitelist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_gpComite->CurrentValue) == strval($this->Recordset->fields('nu_gpComite'))) {
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
						$sReturnUrl = "gpcomitelist.php"; // No matching record, return to list
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
			$sReturnUrl = "gpcomitelist.php"; // Not page request, return to list
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
		$this->nu_gpComite->setDbValue($rs->fields('nu_gpComite'));
		$this->no_gpComite->setDbValue($rs->fields('no_gpComite'));
		$this->ic_tpGpOuComite->setDbValue($rs->fields('ic_tpGpOuComite'));
		$this->ds_descricao->setDbValue($rs->fields('ds_descricao'));
		$this->ds_finalidade->setDbValue($rs->fields('ds_finalidade'));
		$this->ic_natureza->setDbValue($rs->fields('ic_natureza'));
		$this->ds_competencias->setDbValue($rs->fields('ds_competencias'));
		$this->ic_periodicidadeReunioes->setDbValue($rs->fields('ic_periodicidadeReunioes'));
		$this->dt_basePeriodicidade->setDbValue($rs->fields('dt_basePeriodicidade'));
		$this->no_localDocDiretrizes->setDbValue($rs->fields('no_localDocDiretrizes'));
		$this->im_anexoDiretrizes->Upload->DbValue = $rs->fields('im_anexoDiretrizes');
		$this->no_localDocComunicacao->setDbValue($rs->fields('no_localDocComunicacao'));
		$this->im_anexoComunicacao->Upload->DbValue = $rs->fields('im_anexoComunicacao');
		$this->no_localParecerJuridico->setDbValue($rs->fields('no_localParecerJuridico'));
		$this->im_anexoParecerJuridico->Upload->DbValue = $rs->fields('im_anexoParecerJuridico');
		$this->no_localDocDesignacao->setDbValue($rs->fields('no_localDocDesignacao'));
		$this->im_anexoDesignacao->Upload->DbValue = $rs->fields('im_anexoDesignacao');
		$this->ds_partesInteressadas->setDbValue($rs->fields('ds_partesInteressadas'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_gpComite->DbValue = $row['nu_gpComite'];
		$this->no_gpComite->DbValue = $row['no_gpComite'];
		$this->ic_tpGpOuComite->DbValue = $row['ic_tpGpOuComite'];
		$this->ds_descricao->DbValue = $row['ds_descricao'];
		$this->ds_finalidade->DbValue = $row['ds_finalidade'];
		$this->ic_natureza->DbValue = $row['ic_natureza'];
		$this->ds_competencias->DbValue = $row['ds_competencias'];
		$this->ic_periodicidadeReunioes->DbValue = $row['ic_periodicidadeReunioes'];
		$this->dt_basePeriodicidade->DbValue = $row['dt_basePeriodicidade'];
		$this->no_localDocDiretrizes->DbValue = $row['no_localDocDiretrizes'];
		$this->im_anexoDiretrizes->Upload->DbValue = $row['im_anexoDiretrizes'];
		$this->no_localDocComunicacao->DbValue = $row['no_localDocComunicacao'];
		$this->im_anexoComunicacao->Upload->DbValue = $row['im_anexoComunicacao'];
		$this->no_localParecerJuridico->DbValue = $row['no_localParecerJuridico'];
		$this->im_anexoParecerJuridico->Upload->DbValue = $row['im_anexoParecerJuridico'];
		$this->no_localDocDesignacao->DbValue = $row['no_localDocDesignacao'];
		$this->im_anexoDesignacao->Upload->DbValue = $row['im_anexoDesignacao'];
		$this->ds_partesInteressadas->DbValue = $row['ds_partesInteressadas'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->ts_datahora->DbValue = $row['ts_datahora'];
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
		// nu_gpComite
		// no_gpComite
		// ic_tpGpOuComite
		// ds_descricao
		// ds_finalidade
		// ic_natureza
		// ds_competencias
		// ic_periodicidadeReunioes
		// dt_basePeriodicidade
		// no_localDocDiretrizes
		// im_anexoDiretrizes
		// no_localDocComunicacao
		// im_anexoComunicacao
		// no_localParecerJuridico
		// im_anexoParecerJuridico
		// no_localDocDesignacao
		// im_anexoDesignacao
		// ds_partesInteressadas
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_gpComite
			$this->nu_gpComite->ViewValue = $this->nu_gpComite->CurrentValue;
			$this->nu_gpComite->ViewCustomAttributes = "";

			// no_gpComite
			$this->no_gpComite->ViewValue = $this->no_gpComite->CurrentValue;
			$this->no_gpComite->ViewCustomAttributes = "";

			// ic_tpGpOuComite
			if (strval($this->ic_tpGpOuComite->CurrentValue) <> "") {
				switch ($this->ic_tpGpOuComite->CurrentValue) {
					case $this->ic_tpGpOuComite->FldTagValue(1):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(1) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(1) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					case $this->ic_tpGpOuComite->FldTagValue(2):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(2) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(2) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					case $this->ic_tpGpOuComite->FldTagValue(3):
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->FldTagCaption(3) <> "" ? $this->ic_tpGpOuComite->FldTagCaption(3) : $this->ic_tpGpOuComite->CurrentValue;
						break;
					default:
						$this->ic_tpGpOuComite->ViewValue = $this->ic_tpGpOuComite->CurrentValue;
				}
			} else {
				$this->ic_tpGpOuComite->ViewValue = NULL;
			}
			$this->ic_tpGpOuComite->ViewCustomAttributes = "";

			// ds_descricao
			$this->ds_descricao->ViewValue = $this->ds_descricao->CurrentValue;
			$this->ds_descricao->ViewCustomAttributes = "";

			// ds_finalidade
			$this->ds_finalidade->ViewValue = $this->ds_finalidade->CurrentValue;
			$this->ds_finalidade->ViewCustomAttributes = "";

			// ic_natureza
			if (strval($this->ic_natureza->CurrentValue) <> "") {
				switch ($this->ic_natureza->CurrentValue) {
					case $this->ic_natureza->FldTagValue(1):
						$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(1) <> "" ? $this->ic_natureza->FldTagCaption(1) : $this->ic_natureza->CurrentValue;
						break;
					case $this->ic_natureza->FldTagValue(2):
						$this->ic_natureza->ViewValue = $this->ic_natureza->FldTagCaption(2) <> "" ? $this->ic_natureza->FldTagCaption(2) : $this->ic_natureza->CurrentValue;
						break;
					default:
						$this->ic_natureza->ViewValue = $this->ic_natureza->CurrentValue;
				}
			} else {
				$this->ic_natureza->ViewValue = NULL;
			}
			$this->ic_natureza->ViewCustomAttributes = "";

			// ds_competencias
			$this->ds_competencias->ViewValue = $this->ds_competencias->CurrentValue;
			$this->ds_competencias->ViewCustomAttributes = "";

			// ic_periodicidadeReunioes
			if (strval($this->ic_periodicidadeReunioes->CurrentValue) <> "") {
				switch ($this->ic_periodicidadeReunioes->CurrentValue) {
					case $this->ic_periodicidadeReunioes->FldTagValue(1):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(1) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(1) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(2):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(2) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(2) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(3):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(3) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(3) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(4):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(4) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(4) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(5):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(5) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(5) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					case $this->ic_periodicidadeReunioes->FldTagValue(6):
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->FldTagCaption(6) <> "" ? $this->ic_periodicidadeReunioes->FldTagCaption(6) : $this->ic_periodicidadeReunioes->CurrentValue;
						break;
					default:
						$this->ic_periodicidadeReunioes->ViewValue = $this->ic_periodicidadeReunioes->CurrentValue;
				}
			} else {
				$this->ic_periodicidadeReunioes->ViewValue = NULL;
			}
			$this->ic_periodicidadeReunioes->ViewCustomAttributes = "";

			// dt_basePeriodicidade
			$this->dt_basePeriodicidade->ViewValue = $this->dt_basePeriodicidade->CurrentValue;
			$this->dt_basePeriodicidade->ViewValue = ew_FormatDateTime($this->dt_basePeriodicidade->ViewValue, 7);
			$this->dt_basePeriodicidade->ViewCustomAttributes = "";

			// no_localDocDiretrizes
			$this->no_localDocDiretrizes->ViewValue = $this->no_localDocDiretrizes->CurrentValue;
			$this->no_localDocDiretrizes->ViewCustomAttributes = "";

			// im_anexoDiretrizes
			$this->im_anexoDiretrizes->UploadPath = "arquivos/grupocti_diretrizes";
			if (!ew_Empty($this->im_anexoDiretrizes->Upload->DbValue)) {
				$this->im_anexoDiretrizes->ViewValue = $this->im_anexoDiretrizes->Upload->DbValue;
			} else {
				$this->im_anexoDiretrizes->ViewValue = "";
			}
			$this->im_anexoDiretrizes->ViewCustomAttributes = "";

			// no_localDocComunicacao
			$this->no_localDocComunicacao->ViewValue = $this->no_localDocComunicacao->CurrentValue;
			$this->no_localDocComunicacao->ViewCustomAttributes = "";

			// im_anexoComunicacao
			$this->im_anexoComunicacao->UploadPath = "arquivos/grupocti_comunicacao";
			if (!ew_Empty($this->im_anexoComunicacao->Upload->DbValue)) {
				$this->im_anexoComunicacao->ViewValue = $this->im_anexoComunicacao->Upload->DbValue;
			} else {
				$this->im_anexoComunicacao->ViewValue = "";
			}
			$this->im_anexoComunicacao->ViewCustomAttributes = "";

			// no_localParecerJuridico
			$this->no_localParecerJuridico->ViewValue = $this->no_localParecerJuridico->CurrentValue;
			$this->no_localParecerJuridico->ViewCustomAttributes = "";

			// im_anexoParecerJuridico
			$this->im_anexoParecerJuridico->UploadPath = "arquivos/grupocti_parjuridico";
			if (!ew_Empty($this->im_anexoParecerJuridico->Upload->DbValue)) {
				$this->im_anexoParecerJuridico->ViewValue = $this->im_anexoParecerJuridico->Upload->DbValue;
			} else {
				$this->im_anexoParecerJuridico->ViewValue = "";
			}
			$this->im_anexoParecerJuridico->ViewCustomAttributes = "";

			// no_localDocDesignacao
			$this->no_localDocDesignacao->ViewValue = $this->no_localDocDesignacao->CurrentValue;
			$this->no_localDocDesignacao->ViewCustomAttributes = "";

			// im_anexoDesignacao
			$this->im_anexoDesignacao->UploadPath = "arquivos/grupocti_designacao";
			if (!ew_Empty($this->im_anexoDesignacao->Upload->DbValue)) {
				$this->im_anexoDesignacao->ViewValue = $this->im_anexoDesignacao->Upload->DbValue;
			} else {
				$this->im_anexoDesignacao->ViewValue = "";
			}
			$this->im_anexoDesignacao->ViewCustomAttributes = "";

			// ds_partesInteressadas
			$this->ds_partesInteressadas->ViewValue = $this->ds_partesInteressadas->CurrentValue;
			$this->ds_partesInteressadas->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_gpComite
			$this->nu_gpComite->LinkCustomAttributes = "";
			$this->nu_gpComite->HrefValue = "";
			$this->nu_gpComite->TooltipValue = "";

			// no_gpComite
			$this->no_gpComite->LinkCustomAttributes = "";
			$this->no_gpComite->HrefValue = "";
			$this->no_gpComite->TooltipValue = "";

			// ic_tpGpOuComite
			$this->ic_tpGpOuComite->LinkCustomAttributes = "";
			$this->ic_tpGpOuComite->HrefValue = "";
			$this->ic_tpGpOuComite->TooltipValue = "";

			// ds_descricao
			$this->ds_descricao->LinkCustomAttributes = "";
			$this->ds_descricao->HrefValue = "";
			$this->ds_descricao->TooltipValue = "";

			// ds_finalidade
			$this->ds_finalidade->LinkCustomAttributes = "";
			$this->ds_finalidade->HrefValue = "";
			$this->ds_finalidade->TooltipValue = "";

			// ic_natureza
			$this->ic_natureza->LinkCustomAttributes = "";
			$this->ic_natureza->HrefValue = "";
			$this->ic_natureza->TooltipValue = "";

			// ds_competencias
			$this->ds_competencias->LinkCustomAttributes = "";
			$this->ds_competencias->HrefValue = "";
			$this->ds_competencias->TooltipValue = "";

			// ic_periodicidadeReunioes
			$this->ic_periodicidadeReunioes->LinkCustomAttributes = "";
			$this->ic_periodicidadeReunioes->HrefValue = "";
			$this->ic_periodicidadeReunioes->TooltipValue = "";

			// dt_basePeriodicidade
			$this->dt_basePeriodicidade->LinkCustomAttributes = "";
			$this->dt_basePeriodicidade->HrefValue = "";
			$this->dt_basePeriodicidade->TooltipValue = "";

			// no_localDocDiretrizes
			$this->no_localDocDiretrizes->LinkCustomAttributes = "";
			$this->no_localDocDiretrizes->HrefValue = "";
			$this->no_localDocDiretrizes->TooltipValue = "";

			// im_anexoDiretrizes
			$this->im_anexoDiretrizes->LinkCustomAttributes = "";
			$this->im_anexoDiretrizes->HrefValue = "";
			$this->im_anexoDiretrizes->HrefValue2 = $this->im_anexoDiretrizes->UploadPath . $this->im_anexoDiretrizes->Upload->DbValue;
			$this->im_anexoDiretrizes->TooltipValue = "";

			// no_localDocComunicacao
			$this->no_localDocComunicacao->LinkCustomAttributes = "";
			$this->no_localDocComunicacao->HrefValue = "";
			$this->no_localDocComunicacao->TooltipValue = "";

			// im_anexoComunicacao
			$this->im_anexoComunicacao->LinkCustomAttributes = "";
			$this->im_anexoComunicacao->HrefValue = "";
			$this->im_anexoComunicacao->HrefValue2 = $this->im_anexoComunicacao->UploadPath . $this->im_anexoComunicacao->Upload->DbValue;
			$this->im_anexoComunicacao->TooltipValue = "";

			// no_localParecerJuridico
			$this->no_localParecerJuridico->LinkCustomAttributes = "";
			$this->no_localParecerJuridico->HrefValue = "";
			$this->no_localParecerJuridico->TooltipValue = "";

			// im_anexoParecerJuridico
			$this->im_anexoParecerJuridico->LinkCustomAttributes = "";
			$this->im_anexoParecerJuridico->HrefValue = "";
			$this->im_anexoParecerJuridico->HrefValue2 = $this->im_anexoParecerJuridico->UploadPath . $this->im_anexoParecerJuridico->Upload->DbValue;
			$this->im_anexoParecerJuridico->TooltipValue = "";

			// no_localDocDesignacao
			$this->no_localDocDesignacao->LinkCustomAttributes = "";
			$this->no_localDocDesignacao->HrefValue = "";
			$this->no_localDocDesignacao->TooltipValue = "";

			// im_anexoDesignacao
			$this->im_anexoDesignacao->LinkCustomAttributes = "";
			$this->im_anexoDesignacao->HrefValue = "";
			$this->im_anexoDesignacao->HrefValue2 = $this->im_anexoDesignacao->UploadPath . $this->im_anexoDesignacao->Upload->DbValue;
			$this->im_anexoDesignacao->TooltipValue = "";

			// ds_partesInteressadas
			$this->ds_partesInteressadas->LinkCustomAttributes = "";
			$this->ds_partesInteressadas->HrefValue = "";
			$this->ds_partesInteressadas->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// ts_datahora
			$this->ts_datahora->LinkCustomAttributes = "";
			$this->ts_datahora->HrefValue = "";
			$this->ts_datahora->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_gpcomite\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_gpcomite',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fgpcomiteview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "gpcomitelist.php", $this->TableVar);
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
if (!isset($gpcomite_view)) $gpcomite_view = new cgpcomite_view();

// Page init
$gpcomite_view->Page_Init();

// Page main
$gpcomite_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$gpcomite_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($gpcomite->Export == "") { ?>
<script type="text/javascript">

// Page object
var gpcomite_view = new ew_Page("gpcomite_view");
gpcomite_view.PageID = "view"; // Page ID
var EW_PAGE_ID = gpcomite_view.PageID; // For backward compatibility

// Form object
var fgpcomiteview = new ew_Form("fgpcomiteview");

// Form_CustomValidate event
fgpcomiteview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgpcomiteview.ValidateRequired = true;
<?php } else { ?>
fgpcomiteview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fgpcomiteview.MultiPage = new ew_MultiPage("fgpcomiteview",
	[["x_nu_gpComite",1],["x_no_gpComite",1],["x_ic_tpGpOuComite",1],["x_ds_descricao",1],["x_ds_finalidade",1],["x_ic_natureza",1],["x_ds_competencias",1],["x_ic_periodicidadeReunioes",2],["x_dt_basePeriodicidade",2],["x_no_localDocDiretrizes",3],["x_im_anexoDiretrizes",3],["x_no_localDocComunicacao",3],["x_im_anexoComunicacao",3],["x_no_localParecerJuridico",3],["x_im_anexoParecerJuridico",3],["x_no_localDocDesignacao",3],["x_im_anexoDesignacao",3],["x_ds_partesInteressadas",1]]
);

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($gpcomite->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($gpcomite->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $gpcomite_view->ExportOptions->Render("body") ?>
<?php if (!$gpcomite_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($gpcomite_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $gpcomite_view->ShowPageHeader(); ?>
<?php
$gpcomite_view->ShowMessage();
?>
<form name="fgpcomiteview" id="fgpcomiteview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="gpcomite">
<?php if ($gpcomite->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="gpcomite_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_gpcomite1" data-toggle="tab"><?php echo $gpcomite->PageCaption(1) ?></a></li>
		<li><a href="#tab_gpcomite2" data-toggle="tab"><?php echo $gpcomite->PageCaption(2) ?></a></li>
		<li><a href="#tab_gpcomite3" data-toggle="tab"><?php echo $gpcomite->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
		<div class="tab-pane active" id="tab_gpcomite1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_gpcomiteview1" class="table table-bordered table-striped">
<?php if ($gpcomite->nu_gpComite->Visible) { // nu_gpComite ?>
	<tr id="r_nu_gpComite">
		<td><span id="elh_gpcomite_nu_gpComite"><?php echo $gpcomite->nu_gpComite->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->nu_gpComite->CellAttributes() ?>>
<span id="el_gpcomite_nu_gpComite" class="control-group">
<span<?php echo $gpcomite->nu_gpComite->ViewAttributes() ?>>
<?php echo $gpcomite->nu_gpComite->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->no_gpComite->Visible) { // no_gpComite ?>
	<tr id="r_no_gpComite">
		<td><span id="elh_gpcomite_no_gpComite"><?php echo $gpcomite->no_gpComite->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->no_gpComite->CellAttributes() ?>>
<span id="el_gpcomite_no_gpComite" class="control-group">
<span<?php echo $gpcomite->no_gpComite->ViewAttributes() ?>>
<?php echo $gpcomite->no_gpComite->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ic_tpGpOuComite->Visible) { // ic_tpGpOuComite ?>
	<tr id="r_ic_tpGpOuComite">
		<td><span id="elh_gpcomite_ic_tpGpOuComite"><?php echo $gpcomite->ic_tpGpOuComite->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ic_tpGpOuComite->CellAttributes() ?>>
<span id="el_gpcomite_ic_tpGpOuComite" class="control-group">
<span<?php echo $gpcomite->ic_tpGpOuComite->ViewAttributes() ?>>
<?php echo $gpcomite->ic_tpGpOuComite->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ds_descricao->Visible) { // ds_descricao ?>
	<tr id="r_ds_descricao">
		<td><span id="elh_gpcomite_ds_descricao"><?php echo $gpcomite->ds_descricao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ds_descricao->CellAttributes() ?>>
<span id="el_gpcomite_ds_descricao" class="control-group">
<span<?php echo $gpcomite->ds_descricao->ViewAttributes() ?>>
<?php echo $gpcomite->ds_descricao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ds_finalidade->Visible) { // ds_finalidade ?>
	<tr id="r_ds_finalidade">
		<td><span id="elh_gpcomite_ds_finalidade"><?php echo $gpcomite->ds_finalidade->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ds_finalidade->CellAttributes() ?>>
<span id="el_gpcomite_ds_finalidade" class="control-group">
<span<?php echo $gpcomite->ds_finalidade->ViewAttributes() ?>>
<?php echo $gpcomite->ds_finalidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ic_natureza->Visible) { // ic_natureza ?>
	<tr id="r_ic_natureza">
		<td><span id="elh_gpcomite_ic_natureza"><?php echo $gpcomite->ic_natureza->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ic_natureza->CellAttributes() ?>>
<span id="el_gpcomite_ic_natureza" class="control-group">
<span<?php echo $gpcomite->ic_natureza->ViewAttributes() ?>>
<?php echo $gpcomite->ic_natureza->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ds_competencias->Visible) { // ds_competencias ?>
	<tr id="r_ds_competencias">
		<td><span id="elh_gpcomite_ds_competencias"><?php echo $gpcomite->ds_competencias->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ds_competencias->CellAttributes() ?>>
<span id="el_gpcomite_ds_competencias" class="control-group">
<span<?php echo $gpcomite->ds_competencias->ViewAttributes() ?>>
<?php echo $gpcomite->ds_competencias->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ds_partesInteressadas->Visible) { // ds_partesInteressadas ?>
	<tr id="r_ds_partesInteressadas">
		<td><span id="elh_gpcomite_ds_partesInteressadas"><?php echo $gpcomite->ds_partesInteressadas->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ds_partesInteressadas->CellAttributes() ?>>
<span id="el_gpcomite_ds_partesInteressadas" class="control-group">
<span<?php echo $gpcomite->ds_partesInteressadas->ViewAttributes() ?>>
<?php echo $gpcomite->ds_partesInteressadas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->nu_usuario->Visible) { // nu_usuario ?>
	<tr id="r_nu_usuario">
		<td><span id="elh_gpcomite_nu_usuario"><?php echo $gpcomite->nu_usuario->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->nu_usuario->CellAttributes() ?>>
<span id="el_gpcomite_nu_usuario" class="control-group">
<span<?php echo $gpcomite->nu_usuario->ViewAttributes() ?>>
<?php echo $gpcomite->nu_usuario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->ts_datahora->Visible) { // ts_datahora ?>
	<tr id="r_ts_datahora">
		<td><span id="elh_gpcomite_ts_datahora"><?php echo $gpcomite->ts_datahora->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ts_datahora->CellAttributes() ?>>
<span id="el_gpcomite_ts_datahora" class="control-group">
<span<?php echo $gpcomite->ts_datahora->ViewAttributes() ?>>
<?php echo $gpcomite->ts_datahora->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_gpcomite2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_gpcomiteview2" class="table table-bordered table-striped">
<?php if ($gpcomite->ic_periodicidadeReunioes->Visible) { // ic_periodicidadeReunioes ?>
	<tr id="r_ic_periodicidadeReunioes">
		<td><span id="elh_gpcomite_ic_periodicidadeReunioes"><?php echo $gpcomite->ic_periodicidadeReunioes->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->ic_periodicidadeReunioes->CellAttributes() ?>>
<span id="el_gpcomite_ic_periodicidadeReunioes" class="control-group">
<span<?php echo $gpcomite->ic_periodicidadeReunioes->ViewAttributes() ?>>
<?php echo $gpcomite->ic_periodicidadeReunioes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->dt_basePeriodicidade->Visible) { // dt_basePeriodicidade ?>
	<tr id="r_dt_basePeriodicidade">
		<td><span id="elh_gpcomite_dt_basePeriodicidade"><?php echo $gpcomite->dt_basePeriodicidade->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->dt_basePeriodicidade->CellAttributes() ?>>
<span id="el_gpcomite_dt_basePeriodicidade" class="control-group">
<span<?php echo $gpcomite->dt_basePeriodicidade->ViewAttributes() ?>>
<?php echo $gpcomite->dt_basePeriodicidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_gpcomite3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_gpcomiteview3" class="table table-bordered table-striped">
<?php if ($gpcomite->no_localDocDiretrizes->Visible) { // no_localDocDiretrizes ?>
	<tr id="r_no_localDocDiretrizes">
		<td><span id="elh_gpcomite_no_localDocDiretrizes"><?php echo $gpcomite->no_localDocDiretrizes->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->no_localDocDiretrizes->CellAttributes() ?>>
<span id="el_gpcomite_no_localDocDiretrizes" class="control-group">
<span<?php echo $gpcomite->no_localDocDiretrizes->ViewAttributes() ?>>
<?php echo $gpcomite->no_localDocDiretrizes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->im_anexoDiretrizes->Visible) { // im_anexoDiretrizes ?>
	<tr id="r_im_anexoDiretrizes">
		<td><span id="elh_gpcomite_im_anexoDiretrizes"><?php echo $gpcomite->im_anexoDiretrizes->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->im_anexoDiretrizes->CellAttributes() ?>>
<span id="el_gpcomite_im_anexoDiretrizes" class="control-group">
<span<?php echo $gpcomite->im_anexoDiretrizes->ViewAttributes() ?>>
<?php
$Files = explode(",", $gpcomite->im_anexoDiretrizes->Upload->DbValue);
$HrefValue = $gpcomite->im_anexoDiretrizes->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$gpcomite->im_anexoDiretrizes->ViewValue = $Files[$i];
$gpcomite->im_anexoDiretrizes->HrefValue = str_replace("%u", ew_HtmlEncode($gpcomite->im_anexoDiretrizes->UploadPath . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode($gpcomite->im_anexoDiretrizes->UploadPath . $Files[$i]), $gpcomite->im_anexoDiretrizes->ViewValue);
?>
<?php if ($gpcomite->im_anexoDiretrizes->LinkAttributes() <> "") { ?>
<?php if (!empty($gpcomite->im_anexoDiretrizes->Upload->DbValue)) { ?>
<?php echo $gpcomite->im_anexoDiretrizes->ViewValue ?>
<?php } elseif (!in_array($gpcomite->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($gpcomite->im_anexoDiretrizes->Upload->DbValue)) { ?>
<?php echo $gpcomite->im_anexoDiretrizes->ViewValue ?>
<?php } elseif (!in_array($gpcomite->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
<?php
}
}
?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->no_localDocComunicacao->Visible) { // no_localDocComunicacao ?>
	<tr id="r_no_localDocComunicacao">
		<td><span id="elh_gpcomite_no_localDocComunicacao"><?php echo $gpcomite->no_localDocComunicacao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->no_localDocComunicacao->CellAttributes() ?>>
<span id="el_gpcomite_no_localDocComunicacao" class="control-group">
<span<?php echo $gpcomite->no_localDocComunicacao->ViewAttributes() ?>>
<?php echo $gpcomite->no_localDocComunicacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->im_anexoComunicacao->Visible) { // im_anexoComunicacao ?>
	<tr id="r_im_anexoComunicacao">
		<td><span id="elh_gpcomite_im_anexoComunicacao"><?php echo $gpcomite->im_anexoComunicacao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->im_anexoComunicacao->CellAttributes() ?>>
<span id="el_gpcomite_im_anexoComunicacao" class="control-group">
<span<?php echo $gpcomite->im_anexoComunicacao->ViewAttributes() ?>>
<?php
$Files = explode(",", $gpcomite->im_anexoComunicacao->Upload->DbValue);
$HrefValue = $gpcomite->im_anexoComunicacao->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$gpcomite->im_anexoComunicacao->ViewValue = $Files[$i];
$gpcomite->im_anexoComunicacao->HrefValue = str_replace("%u", ew_HtmlEncode($gpcomite->im_anexoComunicacao->UploadPath . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode($gpcomite->im_anexoComunicacao->UploadPath . $Files[$i]), $gpcomite->im_anexoComunicacao->ViewValue);
?>
<?php if ($gpcomite->im_anexoComunicacao->LinkAttributes() <> "") { ?>
<?php if (!empty($gpcomite->im_anexoComunicacao->Upload->DbValue)) { ?>
<?php echo $gpcomite->im_anexoComunicacao->ViewValue ?>
<?php } elseif (!in_array($gpcomite->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($gpcomite->im_anexoComunicacao->Upload->DbValue)) { ?>
<?php echo $gpcomite->im_anexoComunicacao->ViewValue ?>
<?php } elseif (!in_array($gpcomite->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
<?php
}
}
?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->no_localParecerJuridico->Visible) { // no_localParecerJuridico ?>
	<tr id="r_no_localParecerJuridico">
		<td><span id="elh_gpcomite_no_localParecerJuridico"><?php echo $gpcomite->no_localParecerJuridico->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->no_localParecerJuridico->CellAttributes() ?>>
<span id="el_gpcomite_no_localParecerJuridico" class="control-group">
<span<?php echo $gpcomite->no_localParecerJuridico->ViewAttributes() ?>>
<?php echo $gpcomite->no_localParecerJuridico->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->im_anexoParecerJuridico->Visible) { // im_anexoParecerJuridico ?>
	<tr id="r_im_anexoParecerJuridico">
		<td><span id="elh_gpcomite_im_anexoParecerJuridico"><?php echo $gpcomite->im_anexoParecerJuridico->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->im_anexoParecerJuridico->CellAttributes() ?>>
<span id="el_gpcomite_im_anexoParecerJuridico" class="control-group">
<span<?php echo $gpcomite->im_anexoParecerJuridico->ViewAttributes() ?>>
<?php
$Files = explode(",", $gpcomite->im_anexoParecerJuridico->Upload->DbValue);
$HrefValue = $gpcomite->im_anexoParecerJuridico->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$gpcomite->im_anexoParecerJuridico->ViewValue = $Files[$i];
$gpcomite->im_anexoParecerJuridico->HrefValue = str_replace("%u", ew_HtmlEncode($gpcomite->im_anexoParecerJuridico->UploadPath . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode($gpcomite->im_anexoParecerJuridico->UploadPath . $Files[$i]), $gpcomite->im_anexoParecerJuridico->ViewValue);
?>
<?php if ($gpcomite->im_anexoParecerJuridico->LinkAttributes() <> "") { ?>
<?php if (!empty($gpcomite->im_anexoParecerJuridico->Upload->DbValue)) { ?>
<?php echo $gpcomite->im_anexoParecerJuridico->ViewValue ?>
<?php } elseif (!in_array($gpcomite->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($gpcomite->im_anexoParecerJuridico->Upload->DbValue)) { ?>
<?php echo $gpcomite->im_anexoParecerJuridico->ViewValue ?>
<?php } elseif (!in_array($gpcomite->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
<?php
}
}
?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->no_localDocDesignacao->Visible) { // no_localDocDesignacao ?>
	<tr id="r_no_localDocDesignacao">
		<td><span id="elh_gpcomite_no_localDocDesignacao"><?php echo $gpcomite->no_localDocDesignacao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->no_localDocDesignacao->CellAttributes() ?>>
<span id="el_gpcomite_no_localDocDesignacao" class="control-group">
<span<?php echo $gpcomite->no_localDocDesignacao->ViewAttributes() ?>>
<?php echo $gpcomite->no_localDocDesignacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($gpcomite->im_anexoDesignacao->Visible) { // im_anexoDesignacao ?>
	<tr id="r_im_anexoDesignacao">
		<td><span id="elh_gpcomite_im_anexoDesignacao"><?php echo $gpcomite->im_anexoDesignacao->FldCaption() ?></span></td>
		<td<?php echo $gpcomite->im_anexoDesignacao->CellAttributes() ?>>
<span id="el_gpcomite_im_anexoDesignacao" class="control-group">
<span<?php echo $gpcomite->im_anexoDesignacao->ViewAttributes() ?>>
<?php
$Files = explode(",", $gpcomite->im_anexoDesignacao->Upload->DbValue);
$HrefValue = $gpcomite->im_anexoDesignacao->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$gpcomite->im_anexoDesignacao->ViewValue = $Files[$i];
$gpcomite->im_anexoDesignacao->HrefValue = str_replace("%u", ew_HtmlEncode($gpcomite->im_anexoDesignacao->UploadPath . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode($gpcomite->im_anexoDesignacao->UploadPath . $Files[$i]), $gpcomite->im_anexoDesignacao->ViewValue);
?>
<?php if ($gpcomite->im_anexoDesignacao->LinkAttributes() <> "") { ?>
<?php if (!empty($gpcomite->im_anexoDesignacao->Upload->DbValue)) { ?>
<?php echo $gpcomite->im_anexoDesignacao->ViewValue ?>
<?php } elseif (!in_array($gpcomite->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($gpcomite->im_anexoDesignacao->Upload->DbValue)) { ?>
<?php echo $gpcomite->im_anexoDesignacao->ViewValue ?>
<?php } elseif (!in_array($gpcomite->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
<?php
}
}
?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
<?php if ($gpcomite->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
<?php if ($gpcomite->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($gpcomite_view->Pager)) $gpcomite_view->Pager = new cNumericPager($gpcomite_view->StartRec, $gpcomite_view->DisplayRecs, $gpcomite_view->TotalRecs, $gpcomite_view->RecRange) ?>
<?php if ($gpcomite_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($gpcomite_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $gpcomite_view->PageUrl() ?>start=<?php echo $gpcomite_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($gpcomite_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $gpcomite_view->PageUrl() ?>start=<?php echo $gpcomite_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($gpcomite_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $gpcomite_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($gpcomite_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $gpcomite_view->PageUrl() ?>start=<?php echo $gpcomite_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($gpcomite_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $gpcomite_view->PageUrl() ?>start=<?php echo $gpcomite_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
fgpcomiteview.Init();
</script>
<?php
$gpcomite_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($gpcomite->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$gpcomite_view->Page_Terminate();
?>
