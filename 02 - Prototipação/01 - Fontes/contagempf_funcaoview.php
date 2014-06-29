<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "contagempf_funcaoinfo.php" ?>
<?php include_once "contagempfinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$contagempf_funcao_view = NULL; // Initialize page object first

class ccontagempf_funcao_view extends ccontagempf_funcao {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'contagempf_funcao';

	// Page object name
	var $PageObjName = 'contagempf_funcao_view';

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

		// Table object (contagempf_funcao)
		if (!isset($GLOBALS["contagempf_funcao"])) {
			$GLOBALS["contagempf_funcao"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["contagempf_funcao"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_funcao"] <> "") {
			$this->RecKey["nu_funcao"] = $_GET["nu_funcao"];
			$KeyUrl .= "&nu_funcao=" . urlencode($this->RecKey["nu_funcao"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (contagempf)
		if (!isset($GLOBALS['contagempf'])) $GLOBALS['contagempf'] = new ccontagempf();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'contagempf_funcao', TRUE);

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
			$this->Page_Terminate("contagempf_funcaolist.php");
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
		if (@$_GET["nu_funcao"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_funcao"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_funcao->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_funcao"] <> "") {
				$this->nu_funcao->setQueryStringValue($_GET["nu_funcao"]);
				$this->RecKey["nu_funcao"] = $this->nu_funcao->QueryStringValue;
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
						$this->Page_Terminate("contagempf_funcaolist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_funcao->CurrentValue) == strval($this->Recordset->fields('nu_funcao'))) {
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
						$sReturnUrl = "contagempf_funcaolist.php"; // No matching record, return to list
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
			$sReturnUrl = "contagempf_funcaolist.php"; // Not page request, return to list
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
		$this->nu_contagem->setDbValue($rs->fields('nu_contagem'));
		$this->nu_funcao->setDbValue($rs->fields('nu_funcao'));
		$this->nu_agrupador->setDbValue($rs->fields('nu_agrupador'));
		if (array_key_exists('EV__nu_agrupador', $rs->fields)) {
			$this->nu_agrupador->VirtualValue = $rs->fields('EV__nu_agrupador'); // Set up virtual field value
		} else {
			$this->nu_agrupador->VirtualValue = ""; // Clear value
		}
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->no_funcao->setDbValue($rs->fields('no_funcao'));
		$this->nu_tpManutencao->setDbValue($rs->fields('nu_tpManutencao'));
		$this->nu_tpElemento->setDbValue($rs->fields('nu_tpElemento'));
		$this->qt_alr->setDbValue($rs->fields('qt_alr'));
		$this->ds_alr->setDbValue($rs->fields('ds_alr'));
		$this->qt_der->setDbValue($rs->fields('qt_der'));
		$this->ds_der->setDbValue($rs->fields('ds_der'));
		$this->ic_complexApf->setDbValue($rs->fields('ic_complexApf'));
		$this->vr_contribuicao->setDbValue($rs->fields('vr_contribuicao'));
		$this->vr_fatorReducao->setDbValue($rs->fields('vr_fatorReducao'));
		$this->pc_varFasesRoteiro->setDbValue($rs->fields('pc_varFasesRoteiro'));
		$this->vr_qtPf->setDbValue($rs->fields('vr_qtPf'));
		$this->ic_analalogia->setDbValue($rs->fields('ic_analalogia'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->nu_usuarioLogado->setDbValue($rs->fields('nu_usuarioLogado'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_contagem->DbValue = $row['nu_contagem'];
		$this->nu_funcao->DbValue = $row['nu_funcao'];
		$this->nu_agrupador->DbValue = $row['nu_agrupador'];
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->no_funcao->DbValue = $row['no_funcao'];
		$this->nu_tpManutencao->DbValue = $row['nu_tpManutencao'];
		$this->nu_tpElemento->DbValue = $row['nu_tpElemento'];
		$this->qt_alr->DbValue = $row['qt_alr'];
		$this->ds_alr->DbValue = $row['ds_alr'];
		$this->qt_der->DbValue = $row['qt_der'];
		$this->ds_der->DbValue = $row['ds_der'];
		$this->ic_complexApf->DbValue = $row['ic_complexApf'];
		$this->vr_contribuicao->DbValue = $row['vr_contribuicao'];
		$this->vr_fatorReducao->DbValue = $row['vr_fatorReducao'];
		$this->pc_varFasesRoteiro->DbValue = $row['pc_varFasesRoteiro'];
		$this->vr_qtPf->DbValue = $row['vr_qtPf'];
		$this->ic_analalogia->DbValue = $row['ic_analalogia'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->nu_usuarioLogado->DbValue = $row['nu_usuarioLogado'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
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
		if ($this->vr_fatorReducao->FormValue == $this->vr_fatorReducao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_fatorReducao->CurrentValue)))
			$this->vr_fatorReducao->CurrentValue = ew_StrToFloat($this->vr_fatorReducao->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pc_varFasesRoteiro->FormValue == $this->pc_varFasesRoteiro->CurrentValue && is_numeric(ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue)))
			$this->pc_varFasesRoteiro->CurrentValue = ew_StrToFloat($this->pc_varFasesRoteiro->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_qtPf->FormValue == $this->vr_qtPf->CurrentValue && is_numeric(ew_StrToFloat($this->vr_qtPf->CurrentValue)))
			$this->vr_qtPf->CurrentValue = ew_StrToFloat($this->vr_qtPf->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_contagem
		// nu_funcao
		// nu_agrupador
		// nu_uc
		// no_funcao
		// nu_tpManutencao
		// nu_tpElemento
		// qt_alr
		// ds_alr
		// qt_der
		// ds_der
		// ic_complexApf
		// vr_contribuicao
		// vr_fatorReducao
		// pc_varFasesRoteiro
		// vr_qtPf
		// ic_analalogia
		// ds_observacoes
		// nu_usuarioLogado
		// dh_inclusao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_funcao
			$this->nu_funcao->ViewValue = $this->nu_funcao->CurrentValue;
			$this->nu_funcao->ViewCustomAttributes = "";

			// nu_agrupador
			if ($this->nu_agrupador->VirtualValue <> "") {
				$this->nu_agrupador->ViewValue = $this->nu_agrupador->VirtualValue;
			} else {
			if (strval($this->nu_agrupador->CurrentValue) <> "") {
				$sFilterWrk = "[nu_agrupador]" . ew_SearchString("=", $this->nu_agrupador->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_agrupador], [no_agrupador] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contagempf_agrupador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_agrupador, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_agrupador] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_agrupador->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_agrupador->ViewValue = $this->nu_agrupador->CurrentValue;
				}
			} else {
				$this->nu_agrupador->ViewValue = NULL;
			}
			}
			$this->nu_agrupador->ViewCustomAttributes = "";

			// nu_uc
			if (strval($this->nu_uc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_sistema] = (SELECT nu_sistema FROM contagempf WHERE nu_contagem = " . strval(CurrentPage()->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_uc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_uc->ViewValue = $rswrk->fields('DispFld');
					$this->nu_uc->ViewValue .= ew_ValueSeparator(1,$this->nu_uc) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_uc->ViewValue = $this->nu_uc->CurrentValue;
				}
			} else {
				$this->nu_uc->ViewValue = NULL;
			}
			$this->nu_uc->ViewCustomAttributes = "";

			// no_funcao
			$this->no_funcao->ViewValue = $this->no_funcao->CurrentValue;
			$this->no_funcao->ViewCustomAttributes = "";

			// nu_tpManutencao
			if (strval($this->nu_tpManutencao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpManutencao]" . ew_SearchString("=", $this->nu_tpManutencao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpManutencao], [no_tpManutencao] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpmanutencao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[nu_tpContagem]=(SELECT nu_tpContagem FROM contagempf WHERE nu_contagem = " . strval($this->nu_contagem->CurrentValue) . ")";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpManutencao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpManutencao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpManutencao->ViewValue = $this->nu_tpManutencao->CurrentValue;
				}
			} else {
				$this->nu_tpManutencao->ViewValue = NULL;
			}
			$this->nu_tpManutencao->ViewCustomAttributes = "";

			// nu_tpElemento
			if (strval($this->nu_tpElemento->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpElemento]" . ew_SearchString("=", $this->nu_tpElemento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpElemento], [no_tpElemento] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpElemento]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpElemento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_tpElemento] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpElemento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpElemento->ViewValue = $this->nu_tpElemento->CurrentValue;
				}
			} else {
				$this->nu_tpElemento->ViewValue = NULL;
			}
			$this->nu_tpElemento->ViewCustomAttributes = "";

			// qt_alr
			$this->qt_alr->ViewValue = $this->qt_alr->CurrentValue;
			$this->qt_alr->ViewValue = ew_FormatNumber($this->qt_alr->ViewValue, 0, 0, 0, 0);
			$this->qt_alr->ViewCustomAttributes = "";

			// ds_alr
			$this->ds_alr->ViewValue = $this->ds_alr->CurrentValue;
			$this->ds_alr->ViewCustomAttributes = "";

			// qt_der
			$this->qt_der->ViewValue = $this->qt_der->CurrentValue;
			$this->qt_der->ViewValue = ew_FormatNumber($this->qt_der->ViewValue, 0, 0, 0, 0);
			$this->qt_der->ViewCustomAttributes = "";

			// ds_der
			$this->ds_der->ViewValue = $this->ds_der->CurrentValue;
			$this->ds_der->ViewCustomAttributes = "";

			// ic_complexApf
			$this->ic_complexApf->ViewValue = $this->ic_complexApf->CurrentValue;
			$this->ic_complexApf->ViewCustomAttributes = "";

			// vr_contribuicao
			$this->vr_contribuicao->ViewValue = $this->vr_contribuicao->CurrentValue;
			$this->vr_contribuicao->ViewValue = ew_FormatNumber($this->vr_contribuicao->ViewValue, 0, 0, 0, 0);
			$this->vr_contribuicao->ViewCustomAttributes = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->ViewValue = $this->vr_fatorReducao->CurrentValue;
			$this->vr_fatorReducao->ViewCustomAttributes = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->ViewValue = $this->pc_varFasesRoteiro->CurrentValue;
			$this->pc_varFasesRoteiro->ViewCustomAttributes = "";

			// vr_qtPf
			$this->vr_qtPf->ViewValue = $this->vr_qtPf->CurrentValue;
			$this->vr_qtPf->ViewCustomAttributes = "";

			// ic_analalogia
			if (strval($this->ic_analalogia->CurrentValue) <> "") {
				switch ($this->ic_analalogia->CurrentValue) {
					case $this->ic_analalogia->FldTagValue(1):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(1) <> "" ? $this->ic_analalogia->FldTagCaption(1) : $this->ic_analalogia->CurrentValue;
						break;
					case $this->ic_analalogia->FldTagValue(2):
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->FldTagCaption(2) <> "" ? $this->ic_analalogia->FldTagCaption(2) : $this->ic_analalogia->CurrentValue;
						break;
					default:
						$this->ic_analalogia->ViewValue = $this->ic_analalogia->CurrentValue;
				}
			} else {
				$this->ic_analalogia->ViewValue = NULL;
			}
			$this->ic_analalogia->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// nu_usuarioLogado
			$this->nu_usuarioLogado->ViewValue = $this->nu_usuarioLogado->CurrentValue;
			$this->nu_usuarioLogado->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 11);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_funcao
			$this->nu_funcao->LinkCustomAttributes = "";
			$this->nu_funcao->HrefValue = "";
			$this->nu_funcao->TooltipValue = "";

			// nu_agrupador
			$this->nu_agrupador->LinkCustomAttributes = "";
			$this->nu_agrupador->HrefValue = "";
			$this->nu_agrupador->TooltipValue = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// no_funcao
			$this->no_funcao->LinkCustomAttributes = "";
			$this->no_funcao->HrefValue = "";
			$this->no_funcao->TooltipValue = "";

			// nu_tpManutencao
			$this->nu_tpManutencao->LinkCustomAttributes = "";
			$this->nu_tpManutencao->HrefValue = "";
			$this->nu_tpManutencao->TooltipValue = "";

			// nu_tpElemento
			$this->nu_tpElemento->LinkCustomAttributes = "";
			$this->nu_tpElemento->HrefValue = "";
			$this->nu_tpElemento->TooltipValue = "";

			// qt_alr
			$this->qt_alr->LinkCustomAttributes = "";
			$this->qt_alr->HrefValue = "";
			$this->qt_alr->TooltipValue = "";

			// ds_alr
			$this->ds_alr->LinkCustomAttributes = "";
			$this->ds_alr->HrefValue = "";
			$this->ds_alr->TooltipValue = "";

			// qt_der
			$this->qt_der->LinkCustomAttributes = "";
			$this->qt_der->HrefValue = "";
			$this->qt_der->TooltipValue = "";

			// ds_der
			$this->ds_der->LinkCustomAttributes = "";
			$this->ds_der->HrefValue = "";
			$this->ds_der->TooltipValue = "";

			// ic_complexApf
			$this->ic_complexApf->LinkCustomAttributes = "";
			$this->ic_complexApf->HrefValue = "";
			$this->ic_complexApf->TooltipValue = "";

			// vr_contribuicao
			$this->vr_contribuicao->LinkCustomAttributes = "";
			$this->vr_contribuicao->HrefValue = "";
			$this->vr_contribuicao->TooltipValue = "";

			// vr_fatorReducao
			$this->vr_fatorReducao->LinkCustomAttributes = "";
			$this->vr_fatorReducao->HrefValue = "";
			$this->vr_fatorReducao->TooltipValue = "";

			// pc_varFasesRoteiro
			$this->pc_varFasesRoteiro->LinkCustomAttributes = "";
			$this->pc_varFasesRoteiro->HrefValue = "";
			$this->pc_varFasesRoteiro->TooltipValue = "";

			// vr_qtPf
			$this->vr_qtPf->LinkCustomAttributes = "";
			$this->vr_qtPf->HrefValue = "";
			$this->vr_qtPf->TooltipValue = "";

			// ic_analalogia
			$this->ic_analalogia->LinkCustomAttributes = "";
			$this->ic_analalogia->HrefValue = "";
			$this->ic_analalogia->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";

			// nu_usuarioLogado
			$this->nu_usuarioLogado->LinkCustomAttributes = "";
			$this->nu_usuarioLogado->HrefValue = "";
			$this->nu_usuarioLogado->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_contagempf_funcao\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_contagempf_funcao',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fcontagempf_funcaoview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "contagempf_funcaolist.php", $this->TableVar);
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
if (!isset($contagempf_funcao_view)) $contagempf_funcao_view = new ccontagempf_funcao_view();

// Page init
$contagempf_funcao_view->Page_Init();

// Page main
$contagempf_funcao_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$contagempf_funcao_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($contagempf_funcao->Export == "") { ?>
<script type="text/javascript">

// Page object
var contagempf_funcao_view = new ew_Page("contagempf_funcao_view");
contagempf_funcao_view.PageID = "view"; // Page ID
var EW_PAGE_ID = contagempf_funcao_view.PageID; // For backward compatibility

// Form object
var fcontagempf_funcaoview = new ew_Form("fcontagempf_funcaoview");

// Form_CustomValidate event
fcontagempf_funcaoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcontagempf_funcaoview.ValidateRequired = true;
<?php } else { ?>
fcontagempf_funcaoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcontagempf_funcaoview.Lists["x_nu_agrupador"] = {"LinkField":"x_nu_agrupador","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_agrupador","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaoview.Lists["x_nu_uc"] = {"LinkField":"x_nu_uc","Ajax":true,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_uc","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaoview.Lists["x_nu_tpManutencao"] = {"LinkField":"x_nu_tpManutencao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpManutencao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fcontagempf_funcaoview.Lists["x_nu_tpElemento"] = {"LinkField":"x_nu_tpElemento","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpElemento","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($contagempf_funcao->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($contagempf_funcao->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $contagempf_funcao_view->ExportOptions->Render("body") ?>
<?php if (!$contagempf_funcao_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($contagempf_funcao_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $contagempf_funcao_view->ShowPageHeader(); ?>
<?php
$contagempf_funcao_view->ShowMessage();
?>
<form name="fcontagempf_funcaoview" id="fcontagempf_funcaoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="contagempf_funcao">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_contagempf_funcaoview" class="table table-bordered table-striped">
<?php if ($contagempf_funcao->nu_funcao->Visible) { // nu_funcao ?>
	<tr id="r_nu_funcao">
		<td><span id="elh_contagempf_funcao_nu_funcao"><?php echo $contagempf_funcao->nu_funcao->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->nu_funcao->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_funcao" class="control-group">
<span<?php echo $contagempf_funcao->nu_funcao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_funcao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->nu_agrupador->Visible) { // nu_agrupador ?>
	<tr id="r_nu_agrupador">
		<td><span id="elh_contagempf_funcao_nu_agrupador"><?php echo $contagempf_funcao->nu_agrupador->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->nu_agrupador->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_agrupador" class="control-group">
<span<?php echo $contagempf_funcao->nu_agrupador->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_agrupador->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->nu_uc->Visible) { // nu_uc ?>
	<tr id="r_nu_uc">
		<td><span id="elh_contagempf_funcao_nu_uc"><?php echo $contagempf_funcao->nu_uc->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->nu_uc->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_uc" class="control-group">
<span<?php echo $contagempf_funcao->nu_uc->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_uc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->no_funcao->Visible) { // no_funcao ?>
	<tr id="r_no_funcao">
		<td><span id="elh_contagempf_funcao_no_funcao"><?php echo $contagempf_funcao->no_funcao->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->no_funcao->CellAttributes() ?>>
<span id="el_contagempf_funcao_no_funcao" class="control-group">
<span<?php echo $contagempf_funcao->no_funcao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->no_funcao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->nu_tpManutencao->Visible) { // nu_tpManutencao ?>
	<tr id="r_nu_tpManutencao">
		<td><span id="elh_contagempf_funcao_nu_tpManutencao"><?php echo $contagempf_funcao->nu_tpManutencao->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->nu_tpManutencao->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_tpManutencao" class="control-group">
<span<?php echo $contagempf_funcao->nu_tpManutencao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpManutencao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->nu_tpElemento->Visible) { // nu_tpElemento ?>
	<tr id="r_nu_tpElemento">
		<td><span id="elh_contagempf_funcao_nu_tpElemento"><?php echo $contagempf_funcao->nu_tpElemento->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->nu_tpElemento->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_tpElemento" class="control-group">
<span<?php echo $contagempf_funcao->nu_tpElemento->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_tpElemento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->qt_alr->Visible) { // qt_alr ?>
	<tr id="r_qt_alr">
		<td><span id="elh_contagempf_funcao_qt_alr"><?php echo $contagempf_funcao->qt_alr->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->qt_alr->CellAttributes() ?>>
<span id="el_contagempf_funcao_qt_alr" class="control-group">
<span<?php echo $contagempf_funcao->qt_alr->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_alr->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ds_alr->Visible) { // ds_alr ?>
	<tr id="r_ds_alr">
		<td><span id="elh_contagempf_funcao_ds_alr"><?php echo $contagempf_funcao->ds_alr->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->ds_alr->CellAttributes() ?>>
<span id="el_contagempf_funcao_ds_alr" class="control-group">
<span<?php echo $contagempf_funcao->ds_alr->ViewAttributes() ?>>
<?php echo $contagempf_funcao->ds_alr->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->qt_der->Visible) { // qt_der ?>
	<tr id="r_qt_der">
		<td><span id="elh_contagempf_funcao_qt_der"><?php echo $contagempf_funcao->qt_der->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->qt_der->CellAttributes() ?>>
<span id="el_contagempf_funcao_qt_der" class="control-group">
<span<?php echo $contagempf_funcao->qt_der->ViewAttributes() ?>>
<?php echo $contagempf_funcao->qt_der->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ds_der->Visible) { // ds_der ?>
	<tr id="r_ds_der">
		<td><span id="elh_contagempf_funcao_ds_der"><?php echo $contagempf_funcao->ds_der->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->ds_der->CellAttributes() ?>>
<span id="el_contagempf_funcao_ds_der" class="control-group">
<span<?php echo $contagempf_funcao->ds_der->ViewAttributes() ?>>
<?php echo $contagempf_funcao->ds_der->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ic_complexApf->Visible) { // ic_complexApf ?>
	<tr id="r_ic_complexApf">
		<td><span id="elh_contagempf_funcao_ic_complexApf"><?php echo $contagempf_funcao->ic_complexApf->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->ic_complexApf->CellAttributes() ?>>
<span id="el_contagempf_funcao_ic_complexApf" class="control-group">
<span<?php echo $contagempf_funcao->ic_complexApf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->ic_complexApf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->vr_contribuicao->Visible) { // vr_contribuicao ?>
	<tr id="r_vr_contribuicao">
		<td><span id="elh_contagempf_funcao_vr_contribuicao"><?php echo $contagempf_funcao->vr_contribuicao->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->vr_contribuicao->CellAttributes() ?>>
<span id="el_contagempf_funcao_vr_contribuicao" class="control-group">
<span<?php echo $contagempf_funcao->vr_contribuicao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_contribuicao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->vr_fatorReducao->Visible) { // vr_fatorReducao ?>
	<tr id="r_vr_fatorReducao">
		<td><span id="elh_contagempf_funcao_vr_fatorReducao"><?php echo $contagempf_funcao->vr_fatorReducao->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->vr_fatorReducao->CellAttributes() ?>>
<span id="el_contagempf_funcao_vr_fatorReducao" class="control-group">
<span<?php echo $contagempf_funcao->vr_fatorReducao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_fatorReducao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->pc_varFasesRoteiro->Visible) { // pc_varFasesRoteiro ?>
	<tr id="r_pc_varFasesRoteiro">
		<td><span id="elh_contagempf_funcao_pc_varFasesRoteiro"><?php echo $contagempf_funcao->pc_varFasesRoteiro->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->pc_varFasesRoteiro->CellAttributes() ?>>
<span id="el_contagempf_funcao_pc_varFasesRoteiro" class="control-group">
<span<?php echo $contagempf_funcao->pc_varFasesRoteiro->ViewAttributes() ?>>
<?php echo $contagempf_funcao->pc_varFasesRoteiro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->vr_qtPf->Visible) { // vr_qtPf ?>
	<tr id="r_vr_qtPf">
		<td><span id="elh_contagempf_funcao_vr_qtPf"><?php echo $contagempf_funcao->vr_qtPf->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->vr_qtPf->CellAttributes() ?>>
<span id="el_contagempf_funcao_vr_qtPf" class="control-group">
<span<?php echo $contagempf_funcao->vr_qtPf->ViewAttributes() ?>>
<?php echo $contagempf_funcao->vr_qtPf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ic_analalogia->Visible) { // ic_analalogia ?>
	<tr id="r_ic_analalogia">
		<td><span id="elh_contagempf_funcao_ic_analalogia"><?php echo $contagempf_funcao->ic_analalogia->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->ic_analalogia->CellAttributes() ?>>
<span id="el_contagempf_funcao_ic_analalogia" class="control-group">
<span<?php echo $contagempf_funcao->ic_analalogia->ViewAttributes() ?>>
<?php echo $contagempf_funcao->ic_analalogia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_contagempf_funcao_ds_observacoes"><?php echo $contagempf_funcao->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->ds_observacoes->CellAttributes() ?>>
<span id="el_contagempf_funcao_ds_observacoes" class="control-group">
<span<?php echo $contagempf_funcao->ds_observacoes->ViewAttributes() ?>>
<?php echo $contagempf_funcao->ds_observacoes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->nu_usuarioLogado->Visible) { // nu_usuarioLogado ?>
	<tr id="r_nu_usuarioLogado">
		<td><span id="elh_contagempf_funcao_nu_usuarioLogado"><?php echo $contagempf_funcao->nu_usuarioLogado->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->nu_usuarioLogado->CellAttributes() ?>>
<span id="el_contagempf_funcao_nu_usuarioLogado" class="control-group">
<span<?php echo $contagempf_funcao->nu_usuarioLogado->ViewAttributes() ?>>
<?php echo $contagempf_funcao->nu_usuarioLogado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($contagempf_funcao->dh_inclusao->Visible) { // dh_inclusao ?>
	<tr id="r_dh_inclusao">
		<td><span id="elh_contagempf_funcao_dh_inclusao"><?php echo $contagempf_funcao->dh_inclusao->FldCaption() ?></span></td>
		<td<?php echo $contagempf_funcao->dh_inclusao->CellAttributes() ?>>
<span id="el_contagempf_funcao_dh_inclusao" class="control-group">
<span<?php echo $contagempf_funcao->dh_inclusao->ViewAttributes() ?>>
<?php echo $contagempf_funcao->dh_inclusao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($contagempf_funcao->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($contagempf_funcao_view->Pager)) $contagempf_funcao_view->Pager = new cNumericPager($contagempf_funcao_view->StartRec, $contagempf_funcao_view->DisplayRecs, $contagempf_funcao_view->TotalRecs, $contagempf_funcao_view->RecRange) ?>
<?php if ($contagempf_funcao_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($contagempf_funcao_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_funcao_view->PageUrl() ?>start=<?php echo $contagempf_funcao_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($contagempf_funcao_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_funcao_view->PageUrl() ?>start=<?php echo $contagempf_funcao_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($contagempf_funcao_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $contagempf_funcao_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($contagempf_funcao_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_funcao_view->PageUrl() ?>start=<?php echo $contagempf_funcao_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($contagempf_funcao_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $contagempf_funcao_view->PageUrl() ?>start=<?php echo $contagempf_funcao_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
fcontagempf_funcaoview.Init();
</script>
<?php
$contagempf_funcao_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($contagempf_funcao->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$contagempf_funcao_view->Page_Terminate();
?>
