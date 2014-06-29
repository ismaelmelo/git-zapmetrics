<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "solicitacao_ocorrenciagridcls.php" ?>
<?php include_once "contagempfgridcls.php" ?>
<?php include_once "estimativagridcls.php" ?>
<?php include_once "laudogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$solicitacaoMetricas_view = NULL; // Initialize page object first

class csolicitacaoMetricas_view extends csolicitacaoMetricas {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'solicitacaoMetricas';

	// Page object name
	var $PageObjName = 'solicitacaoMetricas_view';

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

		// Table object (solicitacaoMetricas)
		if (!isset($GLOBALS["solicitacaoMetricas"])) {
			$GLOBALS["solicitacaoMetricas"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["solicitacaoMetricas"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_solMetricas"] <> "") {
			$this->RecKey["nu_solMetricas"] = $_GET["nu_solMetricas"];
			$KeyUrl .= "&nu_solMetricas=" . urlencode($this->RecKey["nu_solMetricas"]);
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
			define("EW_TABLE_NAME", 'solicitacaoMetricas', TRUE);

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
			$this->Page_Terminate("solicitacaometricaslist.php");
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
		if (@$_GET["nu_solMetricas"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_solMetricas"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_solMetricas->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_solMetricas"] <> "") {
				$this->nu_solMetricas->setQueryStringValue($_GET["nu_solMetricas"]);
				$this->RecKey["nu_solMetricas"] = $this->nu_solMetricas->QueryStringValue;
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
						$this->Page_Terminate("solicitacaometricaslist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_solMetricas->CurrentValue) == strval($this->Recordset->fields('nu_solMetricas'))) {
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
						$sReturnUrl = "solicitacaometricaslist.php"; // No matching record, return to list
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
			$sReturnUrl = "solicitacaometricaslist.php"; // Not page request, return to list
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

		// Detail table 'solicitacao_ocorrencia'
		$body = $Language->TablePhrase("solicitacao_ocorrencia", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("solicitacao_ocorrencialist.php?" . EW_TABLE_SHOW_MASTER . "=solicitacaoMetricas&nu_solMetricas=" . strval($this->nu_solMetricas->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_solicitacao_ocorrencia");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'solicitacao_ocorrencia');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "solicitacao_ocorrencia";
		}

		// Detail table 'contagempf'
		$body = $Language->TablePhrase("contagempf", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("contagempflist.php?" . EW_TABLE_SHOW_MASTER . "=solicitacaoMetricas&nu_solMetricas=" . strval($this->nu_solMetricas->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_contagempf");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'contagempf');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "contagempf";
		}

		// Detail table 'estimativa'
		$body = $Language->TablePhrase("estimativa", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("estimativalist.php?" . EW_TABLE_SHOW_MASTER . "=solicitacaoMetricas&nu_solMetricas=" . strval($this->nu_solMetricas->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_estimativa");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'estimativa');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "estimativa";
		}

		// Detail table 'laudo'
		$body = $Language->TablePhrase("laudo", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("laudolist.php?" . EW_TABLE_SHOW_MASTER . "=solicitacaoMetricas&nu_solMetricas=" . strval($this->nu_solMetricas->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_laudo");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'laudo');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "laudo";
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
		$this->nu_solMetricas->setDbValue($rs->fields('nu_solMetricas'));
		$this->nu_tpSolicitacao->setDbValue($rs->fields('nu_tpSolicitacao'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		if (array_key_exists('EV__nu_projeto', $rs->fields)) {
			$this->nu_projeto->VirtualValue = $rs->fields('EV__nu_projeto'); // Set up virtual field value
		} else {
			$this->nu_projeto->VirtualValue = ""; // Clear value
		}
		$this->no_atividadeMaeRedmine->setDbValue($rs->fields('no_atividadeMaeRedmine'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ds_documentacaoAux->setDbValue($rs->fields('ds_documentacaoAux'));
		$this->ds_imapactoDb->setDbValue($rs->fields('ds_imapactoDb'));
		$this->ic_stSolicitacao->setDbValue($rs->fields('ic_stSolicitacao'));
		$this->nu_usuarioAlterou->setDbValue($rs->fields('nu_usuarioAlterou'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
		$this->nu_usuarioIncluiu->setDbValue($rs->fields('nu_usuarioIncluiu'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->dt_stSolicitacao->setDbValue($rs->fields('dt_stSolicitacao'));
		$this->qt_pfTotal->setDbValue($rs->fields('qt_pfTotal'));
		$this->vr_pfContForn->setDbValue($rs->fields('vr_pfContForn'));
		$this->nu_tpMetrica->setDbValue($rs->fields('nu_tpMetrica'));
		$this->ds_observacoesContForn->setDbValue($rs->fields('ds_observacoesContForn'));
		$this->im_anexosContForn->Upload->DbValue = $rs->fields('im_anexosContForn');
		$this->nu_contagemAnt->setDbValue($rs->fields('nu_contagemAnt'));
		if (array_key_exists('EV__nu_contagemAnt', $rs->fields)) {
			$this->nu_contagemAnt->VirtualValue = $rs->fields('EV__nu_contagemAnt'); // Set up virtual field value
		} else {
			$this->nu_contagemAnt->VirtualValue = ""; // Clear value
		}
		$this->ds_observaocoesContAnt->setDbValue($rs->fields('ds_observaocoesContAnt'));
		$this->im_anexosContAnt->Upload->DbValue = $rs->fields('im_anexosContAnt');
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solMetricas->DbValue = $row['nu_solMetricas'];
		$this->nu_tpSolicitacao->DbValue = $row['nu_tpSolicitacao'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->no_atividadeMaeRedmine->DbValue = $row['no_atividadeMaeRedmine'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->ds_documentacaoAux->DbValue = $row['ds_documentacaoAux'];
		$this->ds_imapactoDb->DbValue = $row['ds_imapactoDb'];
		$this->ic_stSolicitacao->DbValue = $row['ic_stSolicitacao'];
		$this->nu_usuarioAlterou->DbValue = $row['nu_usuarioAlterou'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
		$this->nu_usuarioIncluiu->DbValue = $row['nu_usuarioIncluiu'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->dt_stSolicitacao->DbValue = $row['dt_stSolicitacao'];
		$this->qt_pfTotal->DbValue = $row['qt_pfTotal'];
		$this->vr_pfContForn->DbValue = $row['vr_pfContForn'];
		$this->nu_tpMetrica->DbValue = $row['nu_tpMetrica'];
		$this->ds_observacoesContForn->DbValue = $row['ds_observacoesContForn'];
		$this->im_anexosContForn->Upload->DbValue = $row['im_anexosContForn'];
		$this->nu_contagemAnt->DbValue = $row['nu_contagemAnt'];
		$this->ds_observaocoesContAnt->DbValue = $row['ds_observaocoesContAnt'];
		$this->im_anexosContAnt->Upload->DbValue = $row['im_anexosContAnt'];
		$this->ic_bloqueio->DbValue = $row['ic_bloqueio'];
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
		if ($this->vr_pfContForn->FormValue == $this->vr_pfContForn->CurrentValue && is_numeric(ew_StrToFloat($this->vr_pfContForn->CurrentValue)))
			$this->vr_pfContForn->CurrentValue = ew_StrToFloat($this->vr_pfContForn->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solMetricas
		// nu_tpSolicitacao
		// nu_projeto
		// no_atividadeMaeRedmine
		// ds_observacoes
		// ds_documentacaoAux
		// ds_imapactoDb
		// ic_stSolicitacao
		// nu_usuarioAlterou
		// dh_alteracao
		// nu_usuarioIncluiu
		// dh_inclusao
		// dt_stSolicitacao
		// qt_pfTotal
		// vr_pfContForn
		// nu_tpMetrica
		// ds_observacoesContForn
		// im_anexosContForn
		// nu_contagemAnt
		// ds_observaocoesContAnt
		// im_anexosContAnt
		// ic_bloqueio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solMetricas
			$this->nu_solMetricas->ViewValue = $this->nu_solMetricas->CurrentValue;
			$this->nu_solMetricas->ViewCustomAttributes = "";

			// nu_tpSolicitacao
			if (strval($this->nu_tpSolicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpSolicitacao]" . ew_SearchString("=", $this->nu_tpSolicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpSolicitacao], [no_tpSolicitacao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpsolicitacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpSolicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpSolicitacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpSolicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpSolicitacao->ViewValue = $this->nu_tpSolicitacao->CurrentValue;
				}
			} else {
				$this->nu_tpSolicitacao->ViewValue = NULL;
			}
			$this->nu_tpSolicitacao->ViewCustomAttributes = "";

			// nu_projeto
			if ($this->nu_projeto->VirtualValue <> "") {
				$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
			} else {
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_passivelContPf]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
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
			}
			$this->nu_projeto->ViewCustomAttributes = "";

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->ViewValue = $this->no_atividadeMaeRedmine->CurrentValue;
			$this->no_atividadeMaeRedmine->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// ds_documentacaoAux
			$this->ds_documentacaoAux->ViewValue = $this->ds_documentacaoAux->CurrentValue;
			$this->ds_documentacaoAux->ViewCustomAttributes = "";

			// ds_imapactoDb
			$this->ds_imapactoDb->ViewValue = $this->ds_imapactoDb->CurrentValue;
			$this->ds_imapactoDb->ViewCustomAttributes = "";

			// ic_stSolicitacao
			if (strval($this->ic_stSolicitacao->CurrentValue) <> "") {
				switch ($this->ic_stSolicitacao->CurrentValue) {
					case $this->ic_stSolicitacao->FldTagValue(1):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(1) <> "" ? $this->ic_stSolicitacao->FldTagCaption(1) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(2):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(2) <> "" ? $this->ic_stSolicitacao->FldTagCaption(2) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(3):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(3) <> "" ? $this->ic_stSolicitacao->FldTagCaption(3) : $this->ic_stSolicitacao->CurrentValue;
						break;
					case $this->ic_stSolicitacao->FldTagValue(4):
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->FldTagCaption(4) <> "" ? $this->ic_stSolicitacao->FldTagCaption(4) : $this->ic_stSolicitacao->CurrentValue;
						break;
					default:
						$this->ic_stSolicitacao->ViewValue = $this->ic_stSolicitacao->CurrentValue;
				}
			} else {
				$this->ic_stSolicitacao->ViewValue = NULL;
			}
			$this->ic_stSolicitacao->ViewCustomAttributes = "";

			// nu_usuarioAlterou
			if (strval($this->nu_usuarioAlterou->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlterou->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlterou, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlterou->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlterou->ViewValue = $this->nu_usuarioAlterou->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlterou->ViewValue = NULL;
			}
			$this->nu_usuarioAlterou->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 10);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_usuarioIncluiu
			if (strval($this->nu_usuarioIncluiu->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioIncluiu->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioIncluiu, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioIncluiu->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioIncluiu->ViewValue = $this->nu_usuarioIncluiu->CurrentValue;
				}
			} else {
				$this->nu_usuarioIncluiu->ViewValue = NULL;
			}
			$this->nu_usuarioIncluiu->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->ViewValue = $this->dt_stSolicitacao->CurrentValue;
			$this->dt_stSolicitacao->ViewValue = ew_FormatDateTime($this->dt_stSolicitacao->ViewValue, 7);
			$this->dt_stSolicitacao->ViewCustomAttributes = "";

			// qt_pfTotal
			$this->qt_pfTotal->ViewValue = $this->qt_pfTotal->CurrentValue;
			$this->qt_pfTotal->ViewCustomAttributes = "";

			// vr_pfContForn
			$this->vr_pfContForn->ViewValue = $this->vr_pfContForn->CurrentValue;
			$this->vr_pfContForn->ViewCustomAttributes = "";

			// nu_tpMetrica
			if (strval($this->nu_tpMetrica->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpMetrica]" . ew_SearchString("=", $this->nu_tpMetrica->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_tpMetrica, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpMetrica] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpMetrica->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpMetrica->ViewValue = $this->nu_tpMetrica->CurrentValue;
				}
			} else {
				$this->nu_tpMetrica->ViewValue = NULL;
			}
			$this->nu_tpMetrica->ViewCustomAttributes = "";

			// ds_observacoesContForn
			$this->ds_observacoesContForn->ViewValue = $this->ds_observacoesContForn->CurrentValue;
			$this->ds_observacoesContForn->ViewCustomAttributes = "";

			// im_anexosContForn
			$this->im_anexosContForn->UploadPath = "contagem_fornecedor";
			if (!ew_Empty($this->im_anexosContForn->Upload->DbValue)) {
				$this->im_anexosContForn->ViewValue = $this->im_anexosContForn->Upload->DbValue;
			} else {
				$this->im_anexosContForn->ViewValue = "";
			}
			$this->im_anexosContForn->ViewCustomAttributes = "";

			// nu_contagemAnt
			if ($this->nu_contagemAnt->VirtualValue <> "") {
				$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->VirtualValue;
			} else {
			if (strval($this->nu_contagemAnt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_contagemAnt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contagemAnt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contagemAnt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_contagemAnt->ViewValue = $this->nu_contagemAnt->CurrentValue;
				}
			} else {
				$this->nu_contagemAnt->ViewValue = NULL;
			}
			}
			$this->nu_contagemAnt->ViewCustomAttributes = "";

			// ds_observaocoesContAnt
			$this->ds_observaocoesContAnt->ViewValue = $this->ds_observaocoesContAnt->CurrentValue;
			$this->ds_observaocoesContAnt->ViewCustomAttributes = "";

			// im_anexosContAnt
			$this->im_anexosContAnt->UploadPath = "contagem_anterior";
			if (!ew_Empty($this->im_anexosContAnt->Upload->DbValue)) {
				$this->im_anexosContAnt->ViewValue = $this->im_anexosContAnt->Upload->DbValue;
			} else {
				$this->im_anexosContAnt->ViewValue = "";
			}
			$this->im_anexosContAnt->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_solMetricas
			$this->nu_solMetricas->LinkCustomAttributes = "";
			$this->nu_solMetricas->HrefValue = "";
			$this->nu_solMetricas->TooltipValue = "";

			// nu_tpSolicitacao
			$this->nu_tpSolicitacao->LinkCustomAttributes = "";
			$this->nu_tpSolicitacao->HrefValue = "";
			$this->nu_tpSolicitacao->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// no_atividadeMaeRedmine
			$this->no_atividadeMaeRedmine->LinkCustomAttributes = "";
			$this->no_atividadeMaeRedmine->HrefValue = "";
			$this->no_atividadeMaeRedmine->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";

			// ds_documentacaoAux
			$this->ds_documentacaoAux->LinkCustomAttributes = "";
			$this->ds_documentacaoAux->HrefValue = "";
			$this->ds_documentacaoAux->TooltipValue = "";

			// ds_imapactoDb
			$this->ds_imapactoDb->LinkCustomAttributes = "";
			$this->ds_imapactoDb->HrefValue = "";
			$this->ds_imapactoDb->TooltipValue = "";

			// ic_stSolicitacao
			$this->ic_stSolicitacao->LinkCustomAttributes = "";
			$this->ic_stSolicitacao->HrefValue = "";
			$this->ic_stSolicitacao->TooltipValue = "";

			// nu_usuarioAlterou
			$this->nu_usuarioAlterou->LinkCustomAttributes = "";
			$this->nu_usuarioAlterou->HrefValue = "";
			$this->nu_usuarioAlterou->TooltipValue = "";

			// dh_alteracao
			$this->dh_alteracao->LinkCustomAttributes = "";
			$this->dh_alteracao->HrefValue = "";
			$this->dh_alteracao->TooltipValue = "";

			// nu_usuarioIncluiu
			$this->nu_usuarioIncluiu->LinkCustomAttributes = "";
			$this->nu_usuarioIncluiu->HrefValue = "";
			$this->nu_usuarioIncluiu->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";

			// dt_stSolicitacao
			$this->dt_stSolicitacao->LinkCustomAttributes = "";
			$this->dt_stSolicitacao->HrefValue = "";
			$this->dt_stSolicitacao->TooltipValue = "";

			// vr_pfContForn
			$this->vr_pfContForn->LinkCustomAttributes = "";
			$this->vr_pfContForn->HrefValue = "";
			$this->vr_pfContForn->TooltipValue = "";

			// nu_tpMetrica
			$this->nu_tpMetrica->LinkCustomAttributes = "";
			$this->nu_tpMetrica->HrefValue = "";
			$this->nu_tpMetrica->TooltipValue = "";

			// ds_observacoesContForn
			$this->ds_observacoesContForn->LinkCustomAttributes = "";
			$this->ds_observacoesContForn->HrefValue = "";
			$this->ds_observacoesContForn->TooltipValue = "";

			// im_anexosContForn
			$this->im_anexosContForn->LinkCustomAttributes = "";
			$this->im_anexosContForn->HrefValue = "";
			$this->im_anexosContForn->HrefValue2 = $this->im_anexosContForn->UploadPath . $this->im_anexosContForn->Upload->DbValue;
			$this->im_anexosContForn->TooltipValue = "";

			// nu_contagemAnt
			$this->nu_contagemAnt->LinkCustomAttributes = "";
			$this->nu_contagemAnt->HrefValue = "";
			$this->nu_contagemAnt->TooltipValue = "";

			// ds_observaocoesContAnt
			$this->ds_observaocoesContAnt->LinkCustomAttributes = "";
			$this->ds_observaocoesContAnt->HrefValue = "";
			$this->ds_observaocoesContAnt->TooltipValue = "";

			// im_anexosContAnt
			$this->im_anexosContAnt->LinkCustomAttributes = "";
			$this->im_anexosContAnt->HrefValue = "";
			$this->im_anexosContAnt->HrefValue2 = $this->im_anexosContAnt->UploadPath . $this->im_anexosContAnt->Upload->DbValue;
			$this->im_anexosContAnt->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_solicitacaoMetricas\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_solicitacaoMetricas',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fsolicitacaoMetricasview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
			if (in_array("solicitacao_ocorrencia", $DetailTblVar)) {
				if (!isset($GLOBALS["solicitacao_ocorrencia_grid"]))
					$GLOBALS["solicitacao_ocorrencia_grid"] = new csolicitacao_ocorrencia_grid;
				if ($GLOBALS["solicitacao_ocorrencia_grid"]->DetailView) {
					$GLOBALS["solicitacao_ocorrencia_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["solicitacao_ocorrencia_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["solicitacao_ocorrencia_grid"]->setStartRecordNumber(1);
					$GLOBALS["solicitacao_ocorrencia_grid"]->nu_solicitacao->FldIsDetailKey = TRUE;
					$GLOBALS["solicitacao_ocorrencia_grid"]->nu_solicitacao->CurrentValue = $this->nu_solMetricas->CurrentValue;
					$GLOBALS["solicitacao_ocorrencia_grid"]->nu_solicitacao->setSessionValue($GLOBALS["solicitacao_ocorrencia_grid"]->nu_solicitacao->CurrentValue);
				}
			}
			if (in_array("contagempf", $DetailTblVar)) {
				if (!isset($GLOBALS["contagempf_grid"]))
					$GLOBALS["contagempf_grid"] = new ccontagempf_grid;
				if ($GLOBALS["contagempf_grid"]->DetailView) {
					$GLOBALS["contagempf_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["contagempf_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["contagempf_grid"]->setStartRecordNumber(1);
					$GLOBALS["contagempf_grid"]->nu_solMetricas->FldIsDetailKey = TRUE;
					$GLOBALS["contagempf_grid"]->nu_solMetricas->CurrentValue = $this->nu_solMetricas->CurrentValue;
					$GLOBALS["contagempf_grid"]->nu_solMetricas->setSessionValue($GLOBALS["contagempf_grid"]->nu_solMetricas->CurrentValue);
				}
			}
			if (in_array("estimativa", $DetailTblVar)) {
				if (!isset($GLOBALS["estimativa_grid"]))
					$GLOBALS["estimativa_grid"] = new cestimativa_grid;
				if ($GLOBALS["estimativa_grid"]->DetailView) {
					$GLOBALS["estimativa_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["estimativa_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["estimativa_grid"]->setStartRecordNumber(1);
					$GLOBALS["estimativa_grid"]->nu_solMetricas->FldIsDetailKey = TRUE;
					$GLOBALS["estimativa_grid"]->nu_solMetricas->CurrentValue = $this->nu_solMetricas->CurrentValue;
					$GLOBALS["estimativa_grid"]->nu_solMetricas->setSessionValue($GLOBALS["estimativa_grid"]->nu_solMetricas->CurrentValue);
				}
			}
			if (in_array("laudo", $DetailTblVar)) {
				if (!isset($GLOBALS["laudo_grid"]))
					$GLOBALS["laudo_grid"] = new claudo_grid;
				if ($GLOBALS["laudo_grid"]->DetailView) {
					$GLOBALS["laudo_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["laudo_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["laudo_grid"]->setStartRecordNumber(1);
					$GLOBALS["laudo_grid"]->nu_solicitacao->FldIsDetailKey = TRUE;
					$GLOBALS["laudo_grid"]->nu_solicitacao->CurrentValue = $this->nu_solMetricas->CurrentValue;
					$GLOBALS["laudo_grid"]->nu_solicitacao->setSessionValue($GLOBALS["laudo_grid"]->nu_solicitacao->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "solicitacaometricaslist.php", $this->TableVar);
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
if (!isset($solicitacaoMetricas_view)) $solicitacaoMetricas_view = new csolicitacaoMetricas_view();

// Page init
$solicitacaoMetricas_view->Page_Init();

// Page main
$solicitacaoMetricas_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$solicitacaoMetricas_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<script type="text/javascript">

// Page object
var solicitacaoMetricas_view = new ew_Page("solicitacaoMetricas_view");
solicitacaoMetricas_view.PageID = "view"; // Page ID
var EW_PAGE_ID = solicitacaoMetricas_view.PageID; // For backward compatibility

// Form object
var fsolicitacaoMetricasview = new ew_Form("fsolicitacaoMetricasview");

// Form_CustomValidate event
fsolicitacaoMetricasview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsolicitacaoMetricasview.ValidateRequired = true;
<?php } else { ?>
fsolicitacaoMetricasview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fsolicitacaoMetricasview.MultiPage = new ew_MultiPage("fsolicitacaoMetricasview",
	[["x_nu_solMetricas",1],["x_nu_tpSolicitacao",1],["x_nu_projeto",1],["x_no_atividadeMaeRedmine",1],["x_ds_observacoes",1],["x_ds_documentacaoAux",1],["x_ds_imapactoDb",1],["x_ic_stSolicitacao",1],["x_vr_pfContForn",2],["x_nu_tpMetrica",2],["x_ds_observacoesContForn",2],["x_im_anexosContForn",2],["x_nu_contagemAnt",3],["x_ds_observaocoesContAnt",3],["x_im_anexosContAnt",3]]
);

// Dynamic selection lists
fsolicitacaoMetricasview.Lists["x_nu_tpSolicitacao"] = {"LinkField":"x_nu_tpSolicitacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpSolicitacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasview.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasview.Lists["x_nu_usuarioAlterou"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasview.Lists["x_nu_usuarioIncluiu"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasview.Lists["x_nu_tpMetrica"] = {"LinkField":"x_nu_tpMetrica","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpMetrica","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fsolicitacaoMetricasview.Lists["x_nu_contagemAnt"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $solicitacaoMetricas_view->ExportOptions->Render("body") ?>
<?php if (!$solicitacaoMetricas_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($solicitacaoMetricas_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $solicitacaoMetricas_view->ShowPageHeader(); ?>
<?php
$solicitacaoMetricas_view->ShowMessage();
?>
<form name="fsolicitacaoMetricasview" id="fsolicitacaoMetricasview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="solicitacaoMetricas">
<?php if ($solicitacaoMetricas->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="solicitacaoMetricas_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_solicitacaoMetricas1" data-toggle="tab"><?php echo $solicitacaoMetricas->PageCaption(1) ?></a></li>
		<li><a href="#tab_solicitacaoMetricas2" data-toggle="tab"><?php echo $solicitacaoMetricas->PageCaption(2) ?></a></li>
		<li><a href="#tab_solicitacaoMetricas3" data-toggle="tab"><?php echo $solicitacaoMetricas->PageCaption(3) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
		<div class="tab-pane active" id="tab_solicitacaoMetricas1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_solicitacaoMetricasview1" class="table table-bordered table-striped">
<?php if ($solicitacaoMetricas->nu_solMetricas->Visible) { // nu_solMetricas ?>
	<tr id="r_nu_solMetricas">
		<td><span id="elh_solicitacaoMetricas_nu_solMetricas"><?php echo $solicitacaoMetricas->nu_solMetricas->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_solMetricas->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_solMetricas" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_solMetricas->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_solMetricas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_tpSolicitacao->Visible) { // nu_tpSolicitacao ?>
	<tr id="r_nu_tpSolicitacao">
		<td><span id="elh_solicitacaoMetricas_nu_tpSolicitacao"><?php echo $solicitacaoMetricas->nu_tpSolicitacao->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_tpSolicitacao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_tpSolicitacao" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_tpSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_tpSolicitacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_solicitacaoMetricas_nu_projeto"><?php echo $solicitacaoMetricas->nu_projeto->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_projeto->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_projeto" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_projeto->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_projeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->no_atividadeMaeRedmine->Visible) { // no_atividadeMaeRedmine ?>
	<tr id="r_no_atividadeMaeRedmine">
		<td><span id="elh_solicitacaoMetricas_no_atividadeMaeRedmine"><?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_no_atividadeMaeRedmine" class="control-group">
<span<?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->no_atividadeMaeRedmine->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_solicitacaoMetricas_ds_observacoes"><?php echo $solicitacaoMetricas->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_observacoes->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_observacoes" class="control-group">
<span<?php echo $solicitacaoMetricas->ds_observacoes->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->ds_observacoes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_documentacaoAux->Visible) { // ds_documentacaoAux ?>
	<tr id="r_ds_documentacaoAux">
		<td><span id="elh_solicitacaoMetricas_ds_documentacaoAux"><?php echo $solicitacaoMetricas->ds_documentacaoAux->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_documentacaoAux->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_documentacaoAux" class="control-group">
<span<?php echo $solicitacaoMetricas->ds_documentacaoAux->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->ds_documentacaoAux->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_imapactoDb->Visible) { // ds_imapactoDb ?>
	<tr id="r_ds_imapactoDb">
		<td><span id="elh_solicitacaoMetricas_ds_imapactoDb"><?php echo $solicitacaoMetricas->ds_imapactoDb->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_imapactoDb->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_imapactoDb" class="control-group">
<span<?php echo $solicitacaoMetricas->ds_imapactoDb->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->ds_imapactoDb->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ic_stSolicitacao->Visible) { // ic_stSolicitacao ?>
	<tr id="r_ic_stSolicitacao">
		<td><span id="elh_solicitacaoMetricas_ic_stSolicitacao"><?php echo $solicitacaoMetricas->ic_stSolicitacao->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ic_stSolicitacao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ic_stSolicitacao" class="control-group">
<span<?php echo $solicitacaoMetricas->ic_stSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->ic_stSolicitacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_usuarioAlterou->Visible) { // nu_usuarioAlterou ?>
	<tr id="r_nu_usuarioAlterou">
		<td><span id="elh_solicitacaoMetricas_nu_usuarioAlterou"><?php echo $solicitacaoMetricas->nu_usuarioAlterou->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_usuarioAlterou->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_usuarioAlterou" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_usuarioAlterou->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_usuarioAlterou->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->dh_alteracao->Visible) { // dh_alteracao ?>
	<tr id="r_dh_alteracao">
		<td><span id="elh_solicitacaoMetricas_dh_alteracao"><?php echo $solicitacaoMetricas->dh_alteracao->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->dh_alteracao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_dh_alteracao" class="control-group">
<span<?php echo $solicitacaoMetricas->dh_alteracao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->dh_alteracao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_usuarioIncluiu->Visible) { // nu_usuarioIncluiu ?>
	<tr id="r_nu_usuarioIncluiu">
		<td><span id="elh_solicitacaoMetricas_nu_usuarioIncluiu"><?php echo $solicitacaoMetricas->nu_usuarioIncluiu->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_usuarioIncluiu->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_usuarioIncluiu" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_usuarioIncluiu->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_usuarioIncluiu->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->dh_inclusao->Visible) { // dh_inclusao ?>
	<tr id="r_dh_inclusao">
		<td><span id="elh_solicitacaoMetricas_dh_inclusao"><?php echo $solicitacaoMetricas->dh_inclusao->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->dh_inclusao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_dh_inclusao" class="control-group">
<span<?php echo $solicitacaoMetricas->dh_inclusao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->dh_inclusao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->dt_stSolicitacao->Visible) { // dt_stSolicitacao ?>
	<tr id="r_dt_stSolicitacao">
		<td><span id="elh_solicitacaoMetricas_dt_stSolicitacao"><?php echo $solicitacaoMetricas->dt_stSolicitacao->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->dt_stSolicitacao->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_dt_stSolicitacao" class="control-group">
<span<?php echo $solicitacaoMetricas->dt_stSolicitacao->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->dt_stSolicitacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_solicitacaoMetricas2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_solicitacaoMetricasview2" class="table table-bordered table-striped">
<?php if ($solicitacaoMetricas->vr_pfContForn->Visible) { // vr_pfContForn ?>
	<tr id="r_vr_pfContForn">
		<td><span id="elh_solicitacaoMetricas_vr_pfContForn"><?php echo $solicitacaoMetricas->vr_pfContForn->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->vr_pfContForn->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_vr_pfContForn" class="control-group">
<span<?php echo $solicitacaoMetricas->vr_pfContForn->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->vr_pfContForn->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->nu_tpMetrica->Visible) { // nu_tpMetrica ?>
	<tr id="r_nu_tpMetrica">
		<td><span id="elh_solicitacaoMetricas_nu_tpMetrica"><?php echo $solicitacaoMetricas->nu_tpMetrica->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_tpMetrica->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_tpMetrica" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_tpMetrica->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_tpMetrica->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_observacoesContForn->Visible) { // ds_observacoesContForn ?>
	<tr id="r_ds_observacoesContForn">
		<td><span id="elh_solicitacaoMetricas_ds_observacoesContForn"><?php echo $solicitacaoMetricas->ds_observacoesContForn->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_observacoesContForn->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_observacoesContForn" class="control-group">
<span<?php echo $solicitacaoMetricas->ds_observacoesContForn->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->ds_observacoesContForn->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->im_anexosContForn->Visible) { // im_anexosContForn ?>
	<tr id="r_im_anexosContForn">
		<td><span id="elh_solicitacaoMetricas_im_anexosContForn"><?php echo $solicitacaoMetricas->im_anexosContForn->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->im_anexosContForn->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_im_anexosContForn" class="control-group">
<span<?php echo $solicitacaoMetricas->im_anexosContForn->ViewAttributes() ?>>
<?php
$Files = explode(",", $solicitacaoMetricas->im_anexosContForn->Upload->DbValue);
$HrefValue = $solicitacaoMetricas->im_anexosContForn->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$solicitacaoMetricas->im_anexosContForn->ViewValue = $Files[$i];
$solicitacaoMetricas->im_anexosContForn->HrefValue = str_replace("%u", ew_HtmlEncode($solicitacaoMetricas->im_anexosContForn->UploadPath . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode($solicitacaoMetricas->im_anexosContForn->UploadPath . $Files[$i]), $solicitacaoMetricas->im_anexosContForn->ViewValue);
?>
<?php if ($solicitacaoMetricas->im_anexosContForn->LinkAttributes() <> "") { ?>
<?php if (!empty($solicitacaoMetricas->im_anexosContForn->Upload->DbValue)) { ?>
<?php echo $solicitacaoMetricas->im_anexosContForn->ViewValue ?>
<?php } elseif (!in_array($solicitacaoMetricas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($solicitacaoMetricas->im_anexosContForn->Upload->DbValue)) { ?>
<?php echo $solicitacaoMetricas->im_anexosContForn->ViewValue ?>
<?php } elseif (!in_array($solicitacaoMetricas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
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
		<div class="tab-pane" id="tab_solicitacaoMetricas3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_solicitacaoMetricasview3" class="table table-bordered table-striped">
<?php if ($solicitacaoMetricas->nu_contagemAnt->Visible) { // nu_contagemAnt ?>
	<tr id="r_nu_contagemAnt">
		<td><span id="elh_solicitacaoMetricas_nu_contagemAnt"><?php echo $solicitacaoMetricas->nu_contagemAnt->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->nu_contagemAnt->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_nu_contagemAnt" class="control-group">
<span<?php echo $solicitacaoMetricas->nu_contagemAnt->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->nu_contagemAnt->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->ds_observaocoesContAnt->Visible) { // ds_observaocoesContAnt ?>
	<tr id="r_ds_observaocoesContAnt">
		<td><span id="elh_solicitacaoMetricas_ds_observaocoesContAnt"><?php echo $solicitacaoMetricas->ds_observaocoesContAnt->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->ds_observaocoesContAnt->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_ds_observaocoesContAnt" class="control-group">
<span<?php echo $solicitacaoMetricas->ds_observaocoesContAnt->ViewAttributes() ?>>
<?php echo $solicitacaoMetricas->ds_observaocoesContAnt->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($solicitacaoMetricas->im_anexosContAnt->Visible) { // im_anexosContAnt ?>
	<tr id="r_im_anexosContAnt">
		<td><span id="elh_solicitacaoMetricas_im_anexosContAnt"><?php echo $solicitacaoMetricas->im_anexosContAnt->FldCaption() ?></span></td>
		<td<?php echo $solicitacaoMetricas->im_anexosContAnt->CellAttributes() ?>>
<span id="el_solicitacaoMetricas_im_anexosContAnt" class="control-group">
<span<?php echo $solicitacaoMetricas->im_anexosContAnt->ViewAttributes() ?>>
<?php
$Files = explode(",", $solicitacaoMetricas->im_anexosContAnt->Upload->DbValue);
$HrefValue = $solicitacaoMetricas->im_anexosContAnt->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$solicitacaoMetricas->im_anexosContAnt->ViewValue = $Files[$i];
$solicitacaoMetricas->im_anexosContAnt->HrefValue = str_replace("%u", ew_HtmlEncode($solicitacaoMetricas->im_anexosContAnt->UploadPath . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode($solicitacaoMetricas->im_anexosContAnt->UploadPath . $Files[$i]), $solicitacaoMetricas->im_anexosContAnt->ViewValue);
?>
<?php if ($solicitacaoMetricas->im_anexosContAnt->LinkAttributes() <> "") { ?>
<?php if (!empty($solicitacaoMetricas->im_anexosContAnt->Upload->DbValue)) { ?>
<?php echo $solicitacaoMetricas->im_anexosContAnt->ViewValue ?>
<?php } elseif (!in_array($solicitacaoMetricas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($solicitacaoMetricas->im_anexosContAnt->Upload->DbValue)) { ?>
<?php echo $solicitacaoMetricas->im_anexosContAnt->ViewValue ?>
<?php } elseif (!in_array($solicitacaoMetricas->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
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
<?php if ($solicitacaoMetricas->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($solicitacaoMetricas_view->Pager)) $solicitacaoMetricas_view->Pager = new cNumericPager($solicitacaoMetricas_view->StartRec, $solicitacaoMetricas_view->DisplayRecs, $solicitacaoMetricas_view->TotalRecs, $solicitacaoMetricas_view->RecRange) ?>
<?php if ($solicitacaoMetricas_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($solicitacaoMetricas_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $solicitacaoMetricas_view->PageUrl() ?>start=<?php echo $solicitacaoMetricas_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($solicitacaoMetricas_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $solicitacaoMetricas_view->PageUrl() ?>start=<?php echo $solicitacaoMetricas_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($solicitacaoMetricas_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $solicitacaoMetricas_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($solicitacaoMetricas_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $solicitacaoMetricas_view->PageUrl() ?>start=<?php echo $solicitacaoMetricas_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($solicitacaoMetricas_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $solicitacaoMetricas_view->PageUrl() ?>start=<?php echo $solicitacaoMetricas_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
	if (in_array("solicitacao_ocorrencia", explode(",", $solicitacaoMetricas->getCurrentDetailTable())) && $solicitacao_ocorrencia->DetailView) {
?>
<?php include_once "solicitacao_ocorrenciagrid.php" ?>
<?php } ?>
<?php
	if (in_array("contagempf", explode(",", $solicitacaoMetricas->getCurrentDetailTable())) && $contagempf->DetailView) {
?>
<?php include_once "contagempfgrid.php" ?>
<?php } ?>
<?php
	if (in_array("estimativa", explode(",", $solicitacaoMetricas->getCurrentDetailTable())) && $estimativa->DetailView) {
?>
<?php include_once "estimativagrid.php" ?>
<?php } ?>
<?php
	if (in_array("laudo", explode(",", $solicitacaoMetricas->getCurrentDetailTable())) && $laudo->DetailView) {
?>
<?php include_once "laudogrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fsolicitacaoMetricasview.Init();
</script>
<?php
$solicitacaoMetricas_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($solicitacaoMetricas->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$solicitacaoMetricas_view->Page_Terminate();
?>
