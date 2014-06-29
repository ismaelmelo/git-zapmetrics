<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "estimativainfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$estimativa_view = NULL; // Initialize page object first

class cestimativa_view extends cestimativa {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'estimativa';

	// Page object name
	var $PageObjName = 'estimativa_view';

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

		// Table object (estimativa)
		if (!isset($GLOBALS["estimativa"])) {
			$GLOBALS["estimativa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["estimativa"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_estimativa"] <> "") {
			$this->RecKey["nu_estimativa"] = $_GET["nu_estimativa"];
			$KeyUrl .= "&nu_estimativa=" . urlencode($this->RecKey["nu_estimativa"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS['solicitacaoMetricas'])) $GLOBALS['solicitacaoMetricas'] = new csolicitacaoMetricas();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'estimativa', TRUE);

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
			$this->Page_Terminate("estimativalist.php");
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
		if (@$_GET["nu_estimativa"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_estimativa"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_estimativa->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_estimativa"] <> "") {
				$this->nu_estimativa->setQueryStringValue($_GET["nu_estimativa"]);
				$this->RecKey["nu_estimativa"] = $this->nu_estimativa->QueryStringValue;
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
						$this->Page_Terminate("estimativalist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_estimativa->CurrentValue) == strval($this->Recordset->fields('nu_estimativa'))) {
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
						$sReturnUrl = "estimativalist.php"; // No matching record, return to list
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
			$sReturnUrl = "estimativalist.php"; // Not page request, return to list
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
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_estimativa->setDbValue($rs->fields('nu_estimativa'));
		$this->ic_solicitacaoCritica->setDbValue($rs->fields('ic_solicitacaoCritica'));
		$this->nu_ambienteMaisRepresentativo->setDbValue($rs->fields('nu_ambienteMaisRepresentativo'));
		$this->qt_tamBase->setDbValue($rs->fields('qt_tamBase'));
		$this->ic_modeloCocomo->setDbValue($rs->fields('ic_modeloCocomo'));
		$this->nu_metPrazo->setDbValue($rs->fields('nu_metPrazo'));
		$this->vr_doPf->setDbValue($rs->fields('vr_doPf'));
		$this->pz_estimadoMeses->setDbValue($rs->fields('pz_estimadoMeses'));
		$this->pz_estimadoDias->setDbValue($rs->fields('pz_estimadoDias'));
		$this->vr_ipMaximo->setDbValue($rs->fields('vr_ipMaximo'));
		$this->vr_ipMedio->setDbValue($rs->fields('vr_ipMedio'));
		$this->vr_ipMinimo->setDbValue($rs->fields('vr_ipMinimo'));
		$this->vr_ipInformado->setDbValue($rs->fields('vr_ipInformado'));
		$this->qt_esforco->setDbValue($rs->fields('qt_esforco'));
		$this->vr_custoDesenv->setDbValue($rs->fields('vr_custoDesenv'));
		$this->vr_outrosCustos->setDbValue($rs->fields('vr_outrosCustos'));
		$this->vr_custoTotal->setDbValue($rs->fields('vr_custoTotal'));
		$this->qt_tamBaseFaturamento->setDbValue($rs->fields('qt_tamBaseFaturamento'));
		$this->qt_recursosEquipe->setDbValue($rs->fields('qt_recursosEquipe'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
		$this->nu_altRELY->setDbValue($rs->fields('nu_altRELY'));
		$this->nu_altDATA->setDbValue($rs->fields('nu_altDATA'));
		$this->nu_altCPLX1->setDbValue($rs->fields('nu_altCPLX1'));
		$this->nu_altCPLX2->setDbValue($rs->fields('nu_altCPLX2'));
		$this->nu_altCPLX3->setDbValue($rs->fields('nu_altCPLX3'));
		$this->nu_altCPLX4->setDbValue($rs->fields('nu_altCPLX4'));
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
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_estimativa->DbValue = $row['nu_estimativa'];
		$this->ic_solicitacaoCritica->DbValue = $row['ic_solicitacaoCritica'];
		$this->nu_ambienteMaisRepresentativo->DbValue = $row['nu_ambienteMaisRepresentativo'];
		$this->qt_tamBase->DbValue = $row['qt_tamBase'];
		$this->ic_modeloCocomo->DbValue = $row['ic_modeloCocomo'];
		$this->nu_metPrazo->DbValue = $row['nu_metPrazo'];
		$this->vr_doPf->DbValue = $row['vr_doPf'];
		$this->pz_estimadoMeses->DbValue = $row['pz_estimadoMeses'];
		$this->pz_estimadoDias->DbValue = $row['pz_estimadoDias'];
		$this->vr_ipMaximo->DbValue = $row['vr_ipMaximo'];
		$this->vr_ipMedio->DbValue = $row['vr_ipMedio'];
		$this->vr_ipMinimo->DbValue = $row['vr_ipMinimo'];
		$this->vr_ipInformado->DbValue = $row['vr_ipInformado'];
		$this->qt_esforco->DbValue = $row['qt_esforco'];
		$this->vr_custoDesenv->DbValue = $row['vr_custoDesenv'];
		$this->vr_outrosCustos->DbValue = $row['vr_outrosCustos'];
		$this->vr_custoTotal->DbValue = $row['vr_custoTotal'];
		$this->qt_tamBaseFaturamento->DbValue = $row['qt_tamBaseFaturamento'];
		$this->qt_recursosEquipe->DbValue = $row['qt_recursosEquipe'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
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
		if ($this->qt_tamBase->FormValue == $this->qt_tamBase->CurrentValue && is_numeric(ew_StrToFloat($this->qt_tamBase->CurrentValue)))
			$this->qt_tamBase->CurrentValue = ew_StrToFloat($this->qt_tamBase->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pz_estimadoMeses->FormValue == $this->pz_estimadoMeses->CurrentValue && is_numeric(ew_StrToFloat($this->pz_estimadoMeses->CurrentValue)))
			$this->pz_estimadoMeses->CurrentValue = ew_StrToFloat($this->pz_estimadoMeses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pz_estimadoDias->FormValue == $this->pz_estimadoDias->CurrentValue && is_numeric(ew_StrToFloat($this->pz_estimadoDias->CurrentValue)))
			$this->pz_estimadoDias->CurrentValue = ew_StrToFloat($this->pz_estimadoDias->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMaximo->FormValue == $this->vr_ipMaximo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMaximo->CurrentValue)))
			$this->vr_ipMaximo->CurrentValue = ew_StrToFloat($this->vr_ipMaximo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMedio->FormValue == $this->vr_ipMedio->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMedio->CurrentValue)))
			$this->vr_ipMedio->CurrentValue = ew_StrToFloat($this->vr_ipMedio->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_ipMinimo->FormValue == $this->vr_ipMinimo->CurrentValue && is_numeric(ew_StrToFloat($this->vr_ipMinimo->CurrentValue)))
			$this->vr_ipMinimo->CurrentValue = ew_StrToFloat($this->vr_ipMinimo->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforco->FormValue == $this->qt_esforco->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforco->CurrentValue)))
			$this->qt_esforco->CurrentValue = ew_StrToFloat($this->qt_esforco->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_custoDesenv->FormValue == $this->vr_custoDesenv->CurrentValue && is_numeric(ew_StrToFloat($this->vr_custoDesenv->CurrentValue)))
			$this->vr_custoDesenv->CurrentValue = ew_StrToFloat($this->vr_custoDesenv->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_outrosCustos->FormValue == $this->vr_outrosCustos->CurrentValue && is_numeric(ew_StrToFloat($this->vr_outrosCustos->CurrentValue)))
			$this->vr_outrosCustos->CurrentValue = ew_StrToFloat($this->vr_outrosCustos->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_custoTotal->FormValue == $this->vr_custoTotal->CurrentValue && is_numeric(ew_StrToFloat($this->vr_custoTotal->CurrentValue)))
			$this->vr_custoTotal->CurrentValue = ew_StrToFloat($this->vr_custoTotal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_tamBaseFaturamento->FormValue == $this->qt_tamBaseFaturamento->CurrentValue && is_numeric(ew_StrToFloat($this->qt_tamBaseFaturamento->CurrentValue)))
			$this->qt_tamBaseFaturamento->CurrentValue = ew_StrToFloat($this->qt_tamBaseFaturamento->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_recursosEquipe->FormValue == $this->qt_recursosEquipe->CurrentValue && is_numeric(ew_StrToFloat($this->qt_recursosEquipe->CurrentValue)))
			$this->qt_recursosEquipe->CurrentValue = ew_StrToFloat($this->qt_recursosEquipe->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solMetricas
		// nu_estimativa
		// ic_solicitacaoCritica
		// nu_ambienteMaisRepresentativo
		// qt_tamBase
		// ic_modeloCocomo
		// nu_metPrazo
		// vr_doPf
		// pz_estimadoMeses
		// pz_estimadoDias
		// vr_ipMaximo
		// vr_ipMedio
		// vr_ipMinimo
		// vr_ipInformado
		// qt_esforco
		// vr_custoDesenv
		// vr_outrosCustos
		// vr_custoTotal
		// qt_tamBaseFaturamento
		// qt_recursosEquipe
		// ds_observacoes
		// ic_bloqueio
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solMetricas
			if (strval($this->nu_solMetricas->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solMetricas->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solMetricas, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solMetricas->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
				}
			} else {
				$this->nu_solMetricas->ViewValue = NULL;
			}
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_estimativa
			$this->nu_estimativa->ViewValue = $this->nu_estimativa->CurrentValue;
			$this->nu_estimativa->ViewCustomAttributes = "";

			// ic_solicitacaoCritica
			if (strval($this->ic_solicitacaoCritica->CurrentValue) <> "") {
				switch ($this->ic_solicitacaoCritica->CurrentValue) {
					case $this->ic_solicitacaoCritica->FldTagValue(1):
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(1) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(1) : $this->ic_solicitacaoCritica->CurrentValue;
						break;
					case $this->ic_solicitacaoCritica->FldTagValue(2):
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->FldTagCaption(2) <> "" ? $this->ic_solicitacaoCritica->FldTagCaption(2) : $this->ic_solicitacaoCritica->CurrentValue;
						break;
					default:
						$this->ic_solicitacaoCritica->ViewValue = $this->ic_solicitacaoCritica->CurrentValue;
				}
			} else {
				$this->ic_solicitacaoCritica->ViewValue = NULL;
			}
			$this->ic_solicitacaoCritica->ViewCustomAttributes = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
			if (strval($this->nu_ambienteMaisRepresentativo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambienteMaisRepresentativo->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_ambienteMaisRepresentativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambienteMaisRepresentativo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambienteMaisRepresentativo->ViewValue = $this->nu_ambienteMaisRepresentativo->CurrentValue;
				}
			} else {
				$this->nu_ambienteMaisRepresentativo->ViewValue = NULL;
			}
			$this->nu_ambienteMaisRepresentativo->ViewCustomAttributes = "";

			// qt_tamBase
			$this->qt_tamBase->ViewValue = $this->qt_tamBase->CurrentValue;
			$this->qt_tamBase->ViewCustomAttributes = "";

			// ic_modeloCocomo
			if (strval($this->ic_modeloCocomo->CurrentValue) <> "") {
				switch ($this->ic_modeloCocomo->CurrentValue) {
					case $this->ic_modeloCocomo->FldTagValue(1):
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(1) <> "" ? $this->ic_modeloCocomo->FldTagCaption(1) : $this->ic_modeloCocomo->CurrentValue;
						break;
					case $this->ic_modeloCocomo->FldTagValue(2):
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->FldTagCaption(2) <> "" ? $this->ic_modeloCocomo->FldTagCaption(2) : $this->ic_modeloCocomo->CurrentValue;
						break;
					default:
						$this->ic_modeloCocomo->ViewValue = $this->ic_modeloCocomo->CurrentValue;
				}
			} else {
				$this->ic_modeloCocomo->ViewValue = NULL;
			}
			$this->ic_modeloCocomo->ViewCustomAttributes = "";

			// nu_metPrazo
			if (strval($this->nu_metPrazo->CurrentValue) <> "") {
				switch ($this->nu_metPrazo->CurrentValue) {
					case $this->nu_metPrazo->FldTagValue(1):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(1) <> "" ? $this->nu_metPrazo->FldTagCaption(1) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(2):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(2) <> "" ? $this->nu_metPrazo->FldTagCaption(2) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(3):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(3) <> "" ? $this->nu_metPrazo->FldTagCaption(3) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(4):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(4) <> "" ? $this->nu_metPrazo->FldTagCaption(4) : $this->nu_metPrazo->CurrentValue;
						break;
					case $this->nu_metPrazo->FldTagValue(5):
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->FldTagCaption(5) <> "" ? $this->nu_metPrazo->FldTagCaption(5) : $this->nu_metPrazo->CurrentValue;
						break;
					default:
						$this->nu_metPrazo->ViewValue = $this->nu_metPrazo->CurrentValue;
				}
			} else {
				$this->nu_metPrazo->ViewValue = NULL;
			}
			$this->nu_metPrazo->ViewCustomAttributes = "";

			// vr_doPf
			$this->vr_doPf->ViewValue = $this->vr_doPf->CurrentValue;
			$this->vr_doPf->ViewCustomAttributes = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->ViewValue = $this->pz_estimadoMeses->CurrentValue;
			$this->pz_estimadoMeses->ViewCustomAttributes = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->ViewValue = $this->pz_estimadoDias->CurrentValue;
			$this->pz_estimadoDias->ViewCustomAttributes = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->ViewValue = $this->vr_ipMaximo->CurrentValue;
			$this->vr_ipMaximo->ViewCustomAttributes = "";

			// vr_ipMedio
			$this->vr_ipMedio->ViewValue = $this->vr_ipMedio->CurrentValue;
			$this->vr_ipMedio->ViewCustomAttributes = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->ViewValue = $this->vr_ipMinimo->CurrentValue;
			$this->vr_ipMinimo->ViewCustomAttributes = "";

			// vr_ipInformado
			$this->vr_ipInformado->ViewValue = $this->vr_ipInformado->CurrentValue;
			$this->vr_ipInformado->ViewCustomAttributes = "";

			// qt_esforco
			$this->qt_esforco->ViewValue = $this->qt_esforco->CurrentValue;
			$this->qt_esforco->ViewCustomAttributes = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->ViewValue = $this->vr_custoDesenv->CurrentValue;
			$this->vr_custoDesenv->ViewCustomAttributes = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->ViewValue = $this->vr_outrosCustos->CurrentValue;
			$this->vr_outrosCustos->ViewCustomAttributes = "";

			// vr_custoTotal
			$this->vr_custoTotal->ViewValue = $this->vr_custoTotal->CurrentValue;
			$this->vr_custoTotal->ViewCustomAttributes = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->ViewValue = $this->qt_tamBaseFaturamento->CurrentValue;
			$this->qt_tamBaseFaturamento->ViewCustomAttributes = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->ViewValue = $this->qt_recursosEquipe->CurrentValue;
			$this->qt_recursosEquipe->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_altRELY
			if (strval($this->nu_altRELY->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altRELY->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_quePREC FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			$lookuptblfilter = "[co_questao]=(select co_queDATA FROM ambiente_valoracao where nu_ambiente = '2' and nu_versaoValoracao = '1') AND [ic_ativo]='S'";
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX2, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX2->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX2->ViewValue = $this->nu_altCPLX2->CurrentValue;
				}
			} else {
				$this->nu_altCPLX2->ViewValue = NULL;
			}
			$this->nu_altCPLX2->ViewCustomAttributes = "";

			// nu_altCPLX3
			if (strval($this->nu_altCPLX3->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX3->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX3, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX3->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX3->ViewValue = $this->nu_altCPLX3->CurrentValue;
				}
			} else {
				$this->nu_altCPLX3->ViewValue = NULL;
			}
			$this->nu_altCPLX3->ViewCustomAttributes = "";

			// nu_altCPLX4
			if (strval($this->nu_altCPLX4->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX4->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX4, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX4->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altCPLX4->ViewValue = $this->nu_altCPLX4->CurrentValue;
				}
			} else {
				$this->nu_altCPLX4->ViewValue = NULL;
			}
			$this->nu_altCPLX4->ViewCustomAttributes = "";

			// nu_altCPLX5
			if (strval($this->nu_altCPLX5->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativa]" . ew_SearchString("=", $this->nu_altCPLX5->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altCPLX5, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altCPLX5->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altDOCU, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altDOCU->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altRUSE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altRUSE->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTIME, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTIME->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSTOR, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSTOR->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPVOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPVOL->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altACAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altACAP->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCAP, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCAP->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPCON, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPCON->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altAPEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altAPEX->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altPLEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altPLEX->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altLTEX, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altLTEX->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altTOOL, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altTOOL->ViewValue = $rswrk->fields('DispFld');
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
			$sSqlWrk = "SELECT [nu_alternativa], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ciialternativa]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_altSITE, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_altSITE->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_altSITE->ViewValue = $this->nu_altSITE->CurrentValue;
				}
			} else {
				$this->nu_altSITE->ViewValue = NULL;
			}
			$this->nu_altSITE->ViewCustomAttributes = "";

			// nu_solMetricas
			$this->nu_solMetricas->LinkCustomAttributes = "";
			$this->nu_solMetricas->HrefValue = "";
			$this->nu_solMetricas->TooltipValue = "";

			// nu_estimativa
			$this->nu_estimativa->LinkCustomAttributes = "";
			$this->nu_estimativa->HrefValue = "";
			$this->nu_estimativa->TooltipValue = "";

			// ic_solicitacaoCritica
			$this->ic_solicitacaoCritica->LinkCustomAttributes = "";
			$this->ic_solicitacaoCritica->HrefValue = "";
			$this->ic_solicitacaoCritica->TooltipValue = "";

			// nu_ambienteMaisRepresentativo
			$this->nu_ambienteMaisRepresentativo->LinkCustomAttributes = "";
			$this->nu_ambienteMaisRepresentativo->HrefValue = "";
			$this->nu_ambienteMaisRepresentativo->TooltipValue = "";

			// qt_tamBase
			$this->qt_tamBase->LinkCustomAttributes = "";
			$this->qt_tamBase->HrefValue = "";
			$this->qt_tamBase->TooltipValue = "";

			// ic_modeloCocomo
			$this->ic_modeloCocomo->LinkCustomAttributes = "";
			$this->ic_modeloCocomo->HrefValue = "";
			$this->ic_modeloCocomo->TooltipValue = "";

			// nu_metPrazo
			$this->nu_metPrazo->LinkCustomAttributes = "";
			$this->nu_metPrazo->HrefValue = "";
			$this->nu_metPrazo->TooltipValue = "";

			// vr_doPf
			$this->vr_doPf->LinkCustomAttributes = "";
			$this->vr_doPf->HrefValue = "";
			$this->vr_doPf->TooltipValue = "";

			// pz_estimadoMeses
			$this->pz_estimadoMeses->LinkCustomAttributes = "";
			$this->pz_estimadoMeses->HrefValue = "";
			$this->pz_estimadoMeses->TooltipValue = "";

			// pz_estimadoDias
			$this->pz_estimadoDias->LinkCustomAttributes = "";
			$this->pz_estimadoDias->HrefValue = "";
			$this->pz_estimadoDias->TooltipValue = "";

			// vr_ipMaximo
			$this->vr_ipMaximo->LinkCustomAttributes = "";
			$this->vr_ipMaximo->HrefValue = "";
			$this->vr_ipMaximo->TooltipValue = "";

			// vr_ipMedio
			$this->vr_ipMedio->LinkCustomAttributes = "";
			$this->vr_ipMedio->HrefValue = "";
			$this->vr_ipMedio->TooltipValue = "";

			// vr_ipMinimo
			$this->vr_ipMinimo->LinkCustomAttributes = "";
			$this->vr_ipMinimo->HrefValue = "";
			$this->vr_ipMinimo->TooltipValue = "";

			// vr_ipInformado
			$this->vr_ipInformado->LinkCustomAttributes = "";
			$this->vr_ipInformado->HrefValue = "";
			$this->vr_ipInformado->TooltipValue = "";

			// qt_esforco
			$this->qt_esforco->LinkCustomAttributes = "";
			$this->qt_esforco->HrefValue = "";
			$this->qt_esforco->TooltipValue = "";

			// vr_custoDesenv
			$this->vr_custoDesenv->LinkCustomAttributes = "";
			$this->vr_custoDesenv->HrefValue = "";
			$this->vr_custoDesenv->TooltipValue = "";

			// vr_outrosCustos
			$this->vr_outrosCustos->LinkCustomAttributes = "";
			$this->vr_outrosCustos->HrefValue = "";
			$this->vr_outrosCustos->TooltipValue = "";

			// vr_custoTotal
			$this->vr_custoTotal->LinkCustomAttributes = "";
			$this->vr_custoTotal->HrefValue = "";
			$this->vr_custoTotal->TooltipValue = "";

			// qt_tamBaseFaturamento
			$this->qt_tamBaseFaturamento->LinkCustomAttributes = "";
			$this->qt_tamBaseFaturamento->HrefValue = "";
			$this->qt_tamBaseFaturamento->TooltipValue = "";

			// qt_recursosEquipe
			$this->qt_recursosEquipe->LinkCustomAttributes = "";
			$this->qt_recursosEquipe->HrefValue = "";
			$this->qt_recursosEquipe->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";

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
		$item->Body = "<a id=\"emf_estimativa\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_estimativa',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.festimativaview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "estimativalist.php", $this->TableVar);
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
if (!isset($estimativa_view)) $estimativa_view = new cestimativa_view();

// Page init
$estimativa_view->Page_Init();

// Page main
$estimativa_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$estimativa_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($estimativa->Export == "") { ?>
<script type="text/javascript">

// Page object
var estimativa_view = new ew_Page("estimativa_view");
estimativa_view.PageID = "view"; // Page ID
var EW_PAGE_ID = estimativa_view.PageID; // For backward compatibility

// Form object
var festimativaview = new ew_Form("festimativaview");

// Form_CustomValidate event
festimativaview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
festimativaview.ValidateRequired = true;
<?php } else { ?>
festimativaview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
festimativaview.MultiPage = new ew_MultiPage("festimativaview",
	[["x_nu_solMetricas",1],["x_nu_estimativa",1],["x_ic_solicitacaoCritica",1],["x_nu_ambienteMaisRepresentativo",1],["x_qt_tamBase",1],["x_ic_modeloCocomo",1],["x_nu_metPrazo",1],["x_vr_doPf",1],["x_pz_estimadoMeses",3],["x_pz_estimadoDias",3],["x_vr_ipMaximo",3],["x_vr_ipMedio",3],["x_vr_ipMinimo",3],["x_vr_ipInformado",3],["x_qt_esforco",3],["x_vr_custoDesenv",3],["x_vr_outrosCustos",3],["x_vr_custoTotal",3],["x_qt_tamBaseFaturamento",3],["x_qt_recursosEquipe",3],["x_ds_observacoes",3],["x_nu_altRELY",2],["x_nu_altDATA",2],["x_nu_altCPLX1",2],["x_nu_altCPLX2",2],["x_nu_altCPLX3",2],["x_nu_altCPLX4",2],["x_nu_altCPLX5",2],["x_nu_altDOCU",2],["x_nu_altRUSE",2],["x_nu_altTIME",2],["x_nu_altSTOR",2],["x_nu_altPVOL",2],["x_nu_altACAP",2],["x_nu_altPCAP",2],["x_nu_altPCON",2],["x_nu_altAPEX",2],["x_nu_altPLEX",2],["x_nu_altLTEX",2],["x_nu_altTOOL",2],["x_nu_altSITE",2]]
);

// Dynamic selection lists
festimativaview.Lists["x_nu_solMetricas"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_ambienteMaisRepresentativo"] = {"LinkField":"x_nu_ambiente","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altRELY"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altDATA"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altCPLX1"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altCPLX2"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altCPLX3"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altCPLX4"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altCPLX5"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altDOCU"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altRUSE"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altTIME"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altSTOR"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altPVOL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altACAP"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altPCAP"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altPCON"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altAPEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altPLEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altLTEX"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altTOOL"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
festimativaview.Lists["x_nu_altSITE"] = {"LinkField":"x_nu_alternativa","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($estimativa->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($estimativa->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $estimativa_view->ExportOptions->Render("body") ?>
<?php if (!$estimativa_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($estimativa_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $estimativa_view->ShowPageHeader(); ?>
<?php
$estimativa_view->ShowMessage();
?>
<form name="festimativaview" id="festimativaview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="estimativa">
<?php if ($estimativa->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="estimativa_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_estimativa1" data-toggle="tab"><?php echo $estimativa->PageCaption(1) ?></a></li>
		<li><a href="#tab_estimativa2" data-toggle="tab"><?php echo $estimativa->PageCaption(2) ?></a></li>
		<li><a href="#tab_estimativa3" data-toggle="tab"><?php echo $estimativa->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
		<div class="tab-pane active" id="tab_estimativa1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_estimativaview1" class="table table-bordered table-striped">
<?php if ($estimativa->nu_solMetricas->Visible) { // nu_solMetricas ?>
	<tr id="r_nu_solMetricas">
		<td><span id="elh_estimativa_nu_solMetricas"><?php echo $estimativa->nu_solMetricas->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_solMetricas->CellAttributes() ?>>
<span id="el_estimativa_nu_solMetricas" class="control-group">
<span<?php echo $estimativa->nu_solMetricas->ViewAttributes() ?>>
<?php echo $estimativa->nu_solMetricas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_estimativa->Visible) { // nu_estimativa ?>
	<tr id="r_nu_estimativa">
		<td><span id="elh_estimativa_nu_estimativa"><?php echo $estimativa->nu_estimativa->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_estimativa->CellAttributes() ?>>
<span id="el_estimativa_nu_estimativa" class="control-group">
<span<?php echo $estimativa->nu_estimativa->ViewAttributes() ?>>
<?php echo $estimativa->nu_estimativa->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->ic_solicitacaoCritica->Visible) { // ic_solicitacaoCritica ?>
	<tr id="r_ic_solicitacaoCritica">
		<td><span id="elh_estimativa_ic_solicitacaoCritica"><?php echo $estimativa->ic_solicitacaoCritica->FldCaption() ?></span></td>
		<td<?php echo $estimativa->ic_solicitacaoCritica->CellAttributes() ?>>
<span id="el_estimativa_ic_solicitacaoCritica" class="control-group">
<span<?php echo $estimativa->ic_solicitacaoCritica->ViewAttributes() ?>>
<?php echo $estimativa->ic_solicitacaoCritica->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_ambienteMaisRepresentativo->Visible) { // nu_ambienteMaisRepresentativo ?>
	<tr id="r_nu_ambienteMaisRepresentativo">
		<td><span id="elh_estimativa_nu_ambienteMaisRepresentativo"><?php echo $estimativa->nu_ambienteMaisRepresentativo->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_ambienteMaisRepresentativo->CellAttributes() ?>>
<span id="el_estimativa_nu_ambienteMaisRepresentativo" class="control-group">
<span<?php echo $estimativa->nu_ambienteMaisRepresentativo->ViewAttributes() ?>>
<?php echo $estimativa->nu_ambienteMaisRepresentativo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->qt_tamBase->Visible) { // qt_tamBase ?>
	<tr id="r_qt_tamBase">
		<td><span id="elh_estimativa_qt_tamBase"><?php echo $estimativa->qt_tamBase->FldCaption() ?></span></td>
		<td<?php echo $estimativa->qt_tamBase->CellAttributes() ?>>
<span id="el_estimativa_qt_tamBase" class="control-group">
<span<?php echo $estimativa->qt_tamBase->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBase->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->ic_modeloCocomo->Visible) { // ic_modeloCocomo ?>
	<tr id="r_ic_modeloCocomo">
		<td><span id="elh_estimativa_ic_modeloCocomo"><?php echo $estimativa->ic_modeloCocomo->FldCaption() ?></span></td>
		<td<?php echo $estimativa->ic_modeloCocomo->CellAttributes() ?>>
<span id="el_estimativa_ic_modeloCocomo" class="control-group">
<span<?php echo $estimativa->ic_modeloCocomo->ViewAttributes() ?>>
<?php echo $estimativa->ic_modeloCocomo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_metPrazo->Visible) { // nu_metPrazo ?>
	<tr id="r_nu_metPrazo">
		<td><span id="elh_estimativa_nu_metPrazo"><?php echo $estimativa->nu_metPrazo->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_metPrazo->CellAttributes() ?>>
<span id="el_estimativa_nu_metPrazo" class="control-group">
<span<?php echo $estimativa->nu_metPrazo->ViewAttributes() ?>>
<?php echo $estimativa->nu_metPrazo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_doPf->Visible) { // vr_doPf ?>
	<tr id="r_vr_doPf">
		<td><span id="elh_estimativa_vr_doPf"><?php echo $estimativa->vr_doPf->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_doPf->CellAttributes() ?>>
<span id="el_estimativa_vr_doPf" class="control-group">
<span<?php echo $estimativa->vr_doPf->ViewAttributes() ?>>
<?php echo $estimativa->vr_doPf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_estimativa2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_estimativaview2" class="table table-bordered table-striped">
<?php if ($estimativa->nu_altRELY->Visible) { // nu_altRELY ?>
	<tr id="r_nu_altRELY">
		<td><span id="elh_estimativa_nu_altRELY"><?php echo $estimativa->nu_altRELY->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altRELY->CellAttributes() ?>>
<span id="el_estimativa_nu_altRELY" class="control-group">
<span<?php echo $estimativa->nu_altRELY->ViewAttributes() ?>>
<?php echo $estimativa->nu_altRELY->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altDATA->Visible) { // nu_altDATA ?>
	<tr id="r_nu_altDATA">
		<td><span id="elh_estimativa_nu_altDATA"><?php echo $estimativa->nu_altDATA->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altDATA->CellAttributes() ?>>
<span id="el_estimativa_nu_altDATA" class="control-group">
<span<?php echo $estimativa->nu_altDATA->ViewAttributes() ?>>
<?php echo $estimativa->nu_altDATA->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX1->Visible) { // nu_altCPLX1 ?>
	<tr id="r_nu_altCPLX1">
		<td><span id="elh_estimativa_nu_altCPLX1"><?php echo $estimativa->nu_altCPLX1->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX1->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX1" class="control-group">
<span<?php echo $estimativa->nu_altCPLX1->ViewAttributes() ?>>
<?php echo $estimativa->nu_altCPLX1->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX2->Visible) { // nu_altCPLX2 ?>
	<tr id="r_nu_altCPLX2">
		<td><span id="elh_estimativa_nu_altCPLX2"><?php echo $estimativa->nu_altCPLX2->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX2->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX2" class="control-group">
<span<?php echo $estimativa->nu_altCPLX2->ViewAttributes() ?>>
<?php echo $estimativa->nu_altCPLX2->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX3->Visible) { // nu_altCPLX3 ?>
	<tr id="r_nu_altCPLX3">
		<td><span id="elh_estimativa_nu_altCPLX3"><?php echo $estimativa->nu_altCPLX3->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX3->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX3" class="control-group">
<span<?php echo $estimativa->nu_altCPLX3->ViewAttributes() ?>>
<?php echo $estimativa->nu_altCPLX3->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX4->Visible) { // nu_altCPLX4 ?>
	<tr id="r_nu_altCPLX4">
		<td><span id="elh_estimativa_nu_altCPLX4"><?php echo $estimativa->nu_altCPLX4->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX4->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX4" class="control-group">
<span<?php echo $estimativa->nu_altCPLX4->ViewAttributes() ?>>
<?php echo $estimativa->nu_altCPLX4->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altCPLX5->Visible) { // nu_altCPLX5 ?>
	<tr id="r_nu_altCPLX5">
		<td><span id="elh_estimativa_nu_altCPLX5"><?php echo $estimativa->nu_altCPLX5->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altCPLX5->CellAttributes() ?>>
<span id="el_estimativa_nu_altCPLX5" class="control-group">
<span<?php echo $estimativa->nu_altCPLX5->ViewAttributes() ?>>
<?php echo $estimativa->nu_altCPLX5->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altDOCU->Visible) { // nu_altDOCU ?>
	<tr id="r_nu_altDOCU">
		<td><span id="elh_estimativa_nu_altDOCU"><?php echo $estimativa->nu_altDOCU->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altDOCU->CellAttributes() ?>>
<span id="el_estimativa_nu_altDOCU" class="control-group">
<span<?php echo $estimativa->nu_altDOCU->ViewAttributes() ?>>
<?php echo $estimativa->nu_altDOCU->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altRUSE->Visible) { // nu_altRUSE ?>
	<tr id="r_nu_altRUSE">
		<td><span id="elh_estimativa_nu_altRUSE"><?php echo $estimativa->nu_altRUSE->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altRUSE->CellAttributes() ?>>
<span id="el_estimativa_nu_altRUSE" class="control-group">
<span<?php echo $estimativa->nu_altRUSE->ViewAttributes() ?>>
<?php echo $estimativa->nu_altRUSE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altTIME->Visible) { // nu_altTIME ?>
	<tr id="r_nu_altTIME">
		<td><span id="elh_estimativa_nu_altTIME"><?php echo $estimativa->nu_altTIME->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altTIME->CellAttributes() ?>>
<span id="el_estimativa_nu_altTIME" class="control-group">
<span<?php echo $estimativa->nu_altTIME->ViewAttributes() ?>>
<?php echo $estimativa->nu_altTIME->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altSTOR->Visible) { // nu_altSTOR ?>
	<tr id="r_nu_altSTOR">
		<td><span id="elh_estimativa_nu_altSTOR"><?php echo $estimativa->nu_altSTOR->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altSTOR->CellAttributes() ?>>
<span id="el_estimativa_nu_altSTOR" class="control-group">
<span<?php echo $estimativa->nu_altSTOR->ViewAttributes() ?>>
<?php echo $estimativa->nu_altSTOR->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altPVOL->Visible) { // nu_altPVOL ?>
	<tr id="r_nu_altPVOL">
		<td><span id="elh_estimativa_nu_altPVOL"><?php echo $estimativa->nu_altPVOL->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altPVOL->CellAttributes() ?>>
<span id="el_estimativa_nu_altPVOL" class="control-group">
<span<?php echo $estimativa->nu_altPVOL->ViewAttributes() ?>>
<?php echo $estimativa->nu_altPVOL->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altACAP->Visible) { // nu_altACAP ?>
	<tr id="r_nu_altACAP">
		<td><span id="elh_estimativa_nu_altACAP"><?php echo $estimativa->nu_altACAP->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altACAP->CellAttributes() ?>>
<span id="el_estimativa_nu_altACAP" class="control-group">
<span<?php echo $estimativa->nu_altACAP->ViewAttributes() ?>>
<?php echo $estimativa->nu_altACAP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altPCAP->Visible) { // nu_altPCAP ?>
	<tr id="r_nu_altPCAP">
		<td><span id="elh_estimativa_nu_altPCAP"><?php echo $estimativa->nu_altPCAP->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altPCAP->CellAttributes() ?>>
<span id="el_estimativa_nu_altPCAP" class="control-group">
<span<?php echo $estimativa->nu_altPCAP->ViewAttributes() ?>>
<?php echo $estimativa->nu_altPCAP->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altPCON->Visible) { // nu_altPCON ?>
	<tr id="r_nu_altPCON">
		<td><span id="elh_estimativa_nu_altPCON"><?php echo $estimativa->nu_altPCON->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altPCON->CellAttributes() ?>>
<span id="el_estimativa_nu_altPCON" class="control-group">
<span<?php echo $estimativa->nu_altPCON->ViewAttributes() ?>>
<?php echo $estimativa->nu_altPCON->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altAPEX->Visible) { // nu_altAPEX ?>
	<tr id="r_nu_altAPEX">
		<td><span id="elh_estimativa_nu_altAPEX"><?php echo $estimativa->nu_altAPEX->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altAPEX->CellAttributes() ?>>
<span id="el_estimativa_nu_altAPEX" class="control-group">
<span<?php echo $estimativa->nu_altAPEX->ViewAttributes() ?>>
<?php echo $estimativa->nu_altAPEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altPLEX->Visible) { // nu_altPLEX ?>
	<tr id="r_nu_altPLEX">
		<td><span id="elh_estimativa_nu_altPLEX"><?php echo $estimativa->nu_altPLEX->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altPLEX->CellAttributes() ?>>
<span id="el_estimativa_nu_altPLEX" class="control-group">
<span<?php echo $estimativa->nu_altPLEX->ViewAttributes() ?>>
<?php echo $estimativa->nu_altPLEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altLTEX->Visible) { // nu_altLTEX ?>
	<tr id="r_nu_altLTEX">
		<td><span id="elh_estimativa_nu_altLTEX"><?php echo $estimativa->nu_altLTEX->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altLTEX->CellAttributes() ?>>
<span id="el_estimativa_nu_altLTEX" class="control-group">
<span<?php echo $estimativa->nu_altLTEX->ViewAttributes() ?>>
<?php echo $estimativa->nu_altLTEX->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altTOOL->Visible) { // nu_altTOOL ?>
	<tr id="r_nu_altTOOL">
		<td><span id="elh_estimativa_nu_altTOOL"><?php echo $estimativa->nu_altTOOL->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altTOOL->CellAttributes() ?>>
<span id="el_estimativa_nu_altTOOL" class="control-group">
<span<?php echo $estimativa->nu_altTOOL->ViewAttributes() ?>>
<?php echo $estimativa->nu_altTOOL->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->nu_altSITE->Visible) { // nu_altSITE ?>
	<tr id="r_nu_altSITE">
		<td><span id="elh_estimativa_nu_altSITE"><?php echo $estimativa->nu_altSITE->FldCaption() ?></span></td>
		<td<?php echo $estimativa->nu_altSITE->CellAttributes() ?>>
<span id="el_estimativa_nu_altSITE" class="control-group">
<span<?php echo $estimativa->nu_altSITE->ViewAttributes() ?>>
<?php echo $estimativa->nu_altSITE->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_estimativa3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_estimativaview3" class="table table-bordered table-striped">
<?php if ($estimativa->pz_estimadoMeses->Visible) { // pz_estimadoMeses ?>
	<tr id="r_pz_estimadoMeses">
		<td><span id="elh_estimativa_pz_estimadoMeses"><?php echo $estimativa->pz_estimadoMeses->FldCaption() ?></span></td>
		<td<?php echo $estimativa->pz_estimadoMeses->CellAttributes() ?>>
<span id="el_estimativa_pz_estimadoMeses" class="control-group">
<span<?php echo $estimativa->pz_estimadoMeses->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoMeses->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->pz_estimadoDias->Visible) { // pz_estimadoDias ?>
	<tr id="r_pz_estimadoDias">
		<td><span id="elh_estimativa_pz_estimadoDias"><?php echo $estimativa->pz_estimadoDias->FldCaption() ?></span></td>
		<td<?php echo $estimativa->pz_estimadoDias->CellAttributes() ?>>
<span id="el_estimativa_pz_estimadoDias" class="control-group">
<span<?php echo $estimativa->pz_estimadoDias->ViewAttributes() ?>>
<?php echo $estimativa->pz_estimadoDias->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_ipMaximo->Visible) { // vr_ipMaximo ?>
	<tr id="r_vr_ipMaximo">
		<td><span id="elh_estimativa_vr_ipMaximo"><?php echo $estimativa->vr_ipMaximo->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_ipMaximo->CellAttributes() ?>>
<span id="el_estimativa_vr_ipMaximo" class="control-group">
<span<?php echo $estimativa->vr_ipMaximo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMaximo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_ipMedio->Visible) { // vr_ipMedio ?>
	<tr id="r_vr_ipMedio">
		<td><span id="elh_estimativa_vr_ipMedio"><?php echo $estimativa->vr_ipMedio->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_ipMedio->CellAttributes() ?>>
<span id="el_estimativa_vr_ipMedio" class="control-group">
<span<?php echo $estimativa->vr_ipMedio->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMedio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_ipMinimo->Visible) { // vr_ipMinimo ?>
	<tr id="r_vr_ipMinimo">
		<td><span id="elh_estimativa_vr_ipMinimo"><?php echo $estimativa->vr_ipMinimo->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_ipMinimo->CellAttributes() ?>>
<span id="el_estimativa_vr_ipMinimo" class="control-group">
<span<?php echo $estimativa->vr_ipMinimo->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipMinimo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_ipInformado->Visible) { // vr_ipInformado ?>
	<tr id="r_vr_ipInformado">
		<td><span id="elh_estimativa_vr_ipInformado"><?php echo $estimativa->vr_ipInformado->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_ipInformado->CellAttributes() ?>>
<span id="el_estimativa_vr_ipInformado" class="control-group">
<span<?php echo $estimativa->vr_ipInformado->ViewAttributes() ?>>
<?php echo $estimativa->vr_ipInformado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->qt_esforco->Visible) { // qt_esforco ?>
	<tr id="r_qt_esforco">
		<td><span id="elh_estimativa_qt_esforco"><?php echo $estimativa->qt_esforco->FldCaption() ?></span></td>
		<td<?php echo $estimativa->qt_esforco->CellAttributes() ?>>
<span id="el_estimativa_qt_esforco" class="control-group">
<span<?php echo $estimativa->qt_esforco->ViewAttributes() ?>>
<?php echo $estimativa->qt_esforco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_custoDesenv->Visible) { // vr_custoDesenv ?>
	<tr id="r_vr_custoDesenv">
		<td><span id="elh_estimativa_vr_custoDesenv"><?php echo $estimativa->vr_custoDesenv->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_custoDesenv->CellAttributes() ?>>
<span id="el_estimativa_vr_custoDesenv" class="control-group">
<span<?php echo $estimativa->vr_custoDesenv->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoDesenv->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_outrosCustos->Visible) { // vr_outrosCustos ?>
	<tr id="r_vr_outrosCustos">
		<td><span id="elh_estimativa_vr_outrosCustos"><?php echo $estimativa->vr_outrosCustos->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_outrosCustos->CellAttributes() ?>>
<span id="el_estimativa_vr_outrosCustos" class="control-group">
<span<?php echo $estimativa->vr_outrosCustos->ViewAttributes() ?>>
<?php echo $estimativa->vr_outrosCustos->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->vr_custoTotal->Visible) { // vr_custoTotal ?>
	<tr id="r_vr_custoTotal">
		<td><span id="elh_estimativa_vr_custoTotal"><?php echo $estimativa->vr_custoTotal->FldCaption() ?></span></td>
		<td<?php echo $estimativa->vr_custoTotal->CellAttributes() ?>>
<span id="el_estimativa_vr_custoTotal" class="control-group">
<span<?php echo $estimativa->vr_custoTotal->ViewAttributes() ?>>
<?php echo $estimativa->vr_custoTotal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->qt_tamBaseFaturamento->Visible) { // qt_tamBaseFaturamento ?>
	<tr id="r_qt_tamBaseFaturamento">
		<td><span id="elh_estimativa_qt_tamBaseFaturamento"><?php echo $estimativa->qt_tamBaseFaturamento->FldCaption() ?></span></td>
		<td<?php echo $estimativa->qt_tamBaseFaturamento->CellAttributes() ?>>
<span id="el_estimativa_qt_tamBaseFaturamento" class="control-group">
<span<?php echo $estimativa->qt_tamBaseFaturamento->ViewAttributes() ?>>
<?php echo $estimativa->qt_tamBaseFaturamento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->qt_recursosEquipe->Visible) { // qt_recursosEquipe ?>
	<tr id="r_qt_recursosEquipe">
		<td><span id="elh_estimativa_qt_recursosEquipe"><?php echo $estimativa->qt_recursosEquipe->FldCaption() ?></span></td>
		<td<?php echo $estimativa->qt_recursosEquipe->CellAttributes() ?>>
<span id="el_estimativa_qt_recursosEquipe" class="control-group">
<span<?php echo $estimativa->qt_recursosEquipe->ViewAttributes() ?>>
<?php echo $estimativa->qt_recursosEquipe->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($estimativa->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_estimativa_ds_observacoes"><?php echo $estimativa->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $estimativa->ds_observacoes->CellAttributes() ?>>
<span id="el_estimativa_ds_observacoes" class="control-group">
<span<?php echo $estimativa->ds_observacoes->ViewAttributes() ?>>
<?php echo $estimativa->ds_observacoes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
<?php if ($estimativa->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
<?php if ($estimativa->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($estimativa_view->Pager)) $estimativa_view->Pager = new cNumericPager($estimativa_view->StartRec, $estimativa_view->DisplayRecs, $estimativa_view->TotalRecs, $estimativa_view->RecRange) ?>
<?php if ($estimativa_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($estimativa_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $estimativa_view->PageUrl() ?>start=<?php echo $estimativa_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($estimativa_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $estimativa_view->PageUrl() ?>start=<?php echo $estimativa_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($estimativa_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $estimativa_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($estimativa_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $estimativa_view->PageUrl() ?>start=<?php echo $estimativa_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($estimativa_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $estimativa_view->PageUrl() ?>start=<?php echo $estimativa_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
festimativaview.Init();
</script>
<?php
$estimativa_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($estimativa->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$estimativa_view->Page_Terminate();
?>
