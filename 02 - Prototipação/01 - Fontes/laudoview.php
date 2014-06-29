<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "laudoinfo.php" ?>
<?php include_once "solicitacaometricasinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$laudo_view = NULL; // Initialize page object first

class claudo_view extends claudo {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'laudo';

	// Page object name
	var $PageObjName = 'laudo_view';

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

		// Table object (laudo)
		if (!isset($GLOBALS["laudo"])) {
			$GLOBALS["laudo"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["laudo"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_solicitacao"] <> "") {
			$this->RecKey["nu_solicitacao"] = $_GET["nu_solicitacao"];
			$KeyUrl .= "&nu_solicitacao=" . urlencode($this->RecKey["nu_solicitacao"]);
		}
		if (@$_GET["nu_versao"] <> "") {
			$this->RecKey["nu_versao"] = $_GET["nu_versao"];
			$KeyUrl .= "&nu_versao=" . urlencode($this->RecKey["nu_versao"]);
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
			define("EW_TABLE_NAME", 'laudo', TRUE);

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
			$this->Page_Terminate("laudolist.php");
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
		if (@$_GET["nu_solicitacao"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_solicitacao"]);
		}
		if (@$_GET["nu_versao"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_versao"]);
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
			if (@$_GET["nu_solicitacao"] <> "") {
				$this->nu_solicitacao->setQueryStringValue($_GET["nu_solicitacao"]);
				$this->RecKey["nu_solicitacao"] = $this->nu_solicitacao->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}
			if (@$_GET["nu_versao"] <> "") {
				$this->nu_versao->setQueryStringValue($_GET["nu_versao"]);
				$this->RecKey["nu_versao"] = $this->nu_versao->QueryStringValue;
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
						$this->Page_Terminate("laudolist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_solicitacao->CurrentValue) == strval($this->Recordset->fields('nu_solicitacao')) && strval($this->nu_versao->CurrentValue) == strval($this->Recordset->fields('nu_versao'))) {
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
						$sReturnUrl = "laudolist.php"; // No matching record, return to list
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
			$sReturnUrl = "laudolist.php"; // Not page request, return to list
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
		$this->nu_solicitacao->setDbValue($rs->fields('nu_solicitacao'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->ds_sobreDocumentacao->setDbValue($rs->fields('ds_sobreDocumentacao'));
		$this->ds_sobreMetrificacao->setDbValue($rs->fields('ds_sobreMetrificacao'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_horas->setDbValue($rs->fields('qt_horas'));
		$this->qt_prazoMeses->setDbValue($rs->fields('qt_prazoMeses'));
		$this->qt_prazoDias->setDbValue($rs->fields('qt_prazoDias'));
		$this->vr_contratacao->setDbValue($rs->fields('vr_contratacao'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->dt_inicioSolicitacao->setDbValue($rs->fields('dt_inicioSolicitacao'));
		$this->dt_inicioContagem->setDbValue($rs->fields('dt_inicioContagem'));
		$this->dt_emissao->setDbValue($rs->fields('dt_emissao'));
		$this->hh_emissao->setDbValue($rs->fields('hh_emissao'));
		$this->ic_tamanho->setDbValue($rs->fields('ic_tamanho'));
		$this->ic_esforco->setDbValue($rs->fields('ic_esforco'));
		$this->ic_prazo->setDbValue($rs->fields('ic_prazo'));
		$this->ic_custo->setDbValue($rs->fields('ic_custo'));
		$this->ic_bloqueio->setDbValue($rs->fields('ic_bloqueio'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_solicitacao->DbValue = $row['nu_solicitacao'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->ds_sobreDocumentacao->DbValue = $row['ds_sobreDocumentacao'];
		$this->ds_sobreMetrificacao->DbValue = $row['ds_sobreMetrificacao'];
		$this->qt_pf->DbValue = $row['qt_pf'];
		$this->qt_horas->DbValue = $row['qt_horas'];
		$this->qt_prazoMeses->DbValue = $row['qt_prazoMeses'];
		$this->qt_prazoDias->DbValue = $row['qt_prazoDias'];
		$this->vr_contratacao->DbValue = $row['vr_contratacao'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->dt_inicioSolicitacao->DbValue = $row['dt_inicioSolicitacao'];
		$this->dt_inicioContagem->DbValue = $row['dt_inicioContagem'];
		$this->dt_emissao->DbValue = $row['dt_emissao'];
		$this->hh_emissao->DbValue = $row['hh_emissao'];
		$this->ic_tamanho->DbValue = $row['ic_tamanho'];
		$this->ic_esforco->DbValue = $row['ic_esforco'];
		$this->ic_prazo->DbValue = $row['ic_prazo'];
		$this->ic_custo->DbValue = $row['ic_custo'];
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
		if ($this->qt_pf->FormValue == $this->qt_pf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_pf->CurrentValue)))
			$this->qt_pf->CurrentValue = ew_StrToFloat($this->qt_pf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_horas->FormValue == $this->qt_horas->CurrentValue && is_numeric(ew_StrToFloat($this->qt_horas->CurrentValue)))
			$this->qt_horas->CurrentValue = ew_StrToFloat($this->qt_horas->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_prazoMeses->FormValue == $this->qt_prazoMeses->CurrentValue && is_numeric(ew_StrToFloat($this->qt_prazoMeses->CurrentValue)))
			$this->qt_prazoMeses->CurrentValue = ew_StrToFloat($this->qt_prazoMeses->CurrentValue);

		// Convert decimal values if posted back
		if ($this->vr_contratacao->FormValue == $this->vr_contratacao->CurrentValue && is_numeric(ew_StrToFloat($this->vr_contratacao->CurrentValue)))
			$this->vr_contratacao->CurrentValue = ew_StrToFloat($this->vr_contratacao->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_solicitacao
		// nu_versao
		// ds_sobreDocumentacao
		// ds_sobreMetrificacao
		// qt_pf
		// qt_horas
		// qt_prazoMeses
		// qt_prazoDias
		// vr_contratacao
		// nu_usuarioResp
		// dt_inicioSolicitacao
		// dt_inicioContagem
		// dt_emissao
		// hh_emissao
		// ic_tamanho
		// ic_esforco
		// ic_prazo
		// ic_custo
		// ic_bloqueio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_solicitacao
			if (strval($this->nu_solicitacao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_solMetricas]" . ew_SearchString("=", $this->nu_solicitacao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_solMetricas], [nu_solMetricas] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[solicitacaoMetricas]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_solicitacao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_solMetricas] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_solicitacao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_solicitacao->ViewValue = $this->nu_solicitacao->CurrentValue;
				}
			} else {
				$this->nu_solicitacao->ViewValue = NULL;
			}
			$this->nu_solicitacao->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// ds_sobreDocumentacao
			$this->ds_sobreDocumentacao->ViewValue = $this->ds_sobreDocumentacao->CurrentValue;
			$this->ds_sobreDocumentacao->ViewCustomAttributes = "";

			// ds_sobreMetrificacao
			$this->ds_sobreMetrificacao->ViewValue = $this->ds_sobreMetrificacao->CurrentValue;
			$this->ds_sobreMetrificacao->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
			$this->qt_pf->ViewCustomAttributes = "";

			// qt_horas
			$this->qt_horas->ViewValue = $this->qt_horas->CurrentValue;
			$this->qt_horas->ViewCustomAttributes = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->ViewValue = $this->qt_prazoMeses->CurrentValue;
			$this->qt_prazoMeses->ViewCustomAttributes = "";

			// qt_prazoDias
			$this->qt_prazoDias->ViewValue = $this->qt_prazoDias->CurrentValue;
			$this->qt_prazoDias->ViewCustomAttributes = "";

			// vr_contratacao
			$this->vr_contratacao->ViewValue = $this->vr_contratacao->CurrentValue;
			$this->vr_contratacao->ViewValue = ew_FormatCurrency($this->vr_contratacao->ViewValue, 2, -2, -2, -2);
			$this->vr_contratacao->ViewCustomAttributes = "";

			// nu_usuarioResp
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
			$sSqlWrk .= " ORDER BY [no_usuario] ASC";
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

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->ViewValue = $this->dt_inicioSolicitacao->CurrentValue;
			$this->dt_inicioSolicitacao->ViewValue = ew_FormatDateTime($this->dt_inicioSolicitacao->ViewValue, 7);
			$this->dt_inicioSolicitacao->ViewCustomAttributes = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->ViewValue = $this->dt_inicioContagem->CurrentValue;
			$this->dt_inicioContagem->ViewValue = ew_FormatDateTime($this->dt_inicioContagem->ViewValue, 7);
			$this->dt_inicioContagem->ViewCustomAttributes = "";

			// dt_emissao
			$this->dt_emissao->ViewValue = $this->dt_emissao->CurrentValue;
			$this->dt_emissao->ViewValue = ew_FormatDateTime($this->dt_emissao->ViewValue, 7);
			$this->dt_emissao->ViewCustomAttributes = "";

			// hh_emissao
			$this->hh_emissao->ViewValue = $this->hh_emissao->CurrentValue;
			$this->hh_emissao->ViewValue = ew_FormatDateTime($this->hh_emissao->ViewValue, 4);
			$this->hh_emissao->ViewCustomAttributes = "";

			// ic_tamanho
			if (strval($this->ic_tamanho->CurrentValue) <> "") {
				switch ($this->ic_tamanho->CurrentValue) {
					case $this->ic_tamanho->FldTagValue(1):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(1) <> "" ? $this->ic_tamanho->FldTagCaption(1) : $this->ic_tamanho->CurrentValue;
						break;
					case $this->ic_tamanho->FldTagValue(2):
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->FldTagCaption(2) <> "" ? $this->ic_tamanho->FldTagCaption(2) : $this->ic_tamanho->CurrentValue;
						break;
					default:
						$this->ic_tamanho->ViewValue = $this->ic_tamanho->CurrentValue;
				}
			} else {
				$this->ic_tamanho->ViewValue = NULL;
			}
			$this->ic_tamanho->ViewCustomAttributes = "";

			// ic_esforco
			if (strval($this->ic_esforco->CurrentValue) <> "") {
				switch ($this->ic_esforco->CurrentValue) {
					case $this->ic_esforco->FldTagValue(1):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(1) <> "" ? $this->ic_esforco->FldTagCaption(1) : $this->ic_esforco->CurrentValue;
						break;
					case $this->ic_esforco->FldTagValue(2):
						$this->ic_esforco->ViewValue = $this->ic_esforco->FldTagCaption(2) <> "" ? $this->ic_esforco->FldTagCaption(2) : $this->ic_esforco->CurrentValue;
						break;
					default:
						$this->ic_esforco->ViewValue = $this->ic_esforco->CurrentValue;
				}
			} else {
				$this->ic_esforco->ViewValue = NULL;
			}
			$this->ic_esforco->ViewCustomAttributes = "";

			// ic_prazo
			if (strval($this->ic_prazo->CurrentValue) <> "") {
				switch ($this->ic_prazo->CurrentValue) {
					case $this->ic_prazo->FldTagValue(1):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(1) <> "" ? $this->ic_prazo->FldTagCaption(1) : $this->ic_prazo->CurrentValue;
						break;
					case $this->ic_prazo->FldTagValue(2):
						$this->ic_prazo->ViewValue = $this->ic_prazo->FldTagCaption(2) <> "" ? $this->ic_prazo->FldTagCaption(2) : $this->ic_prazo->CurrentValue;
						break;
					default:
						$this->ic_prazo->ViewValue = $this->ic_prazo->CurrentValue;
				}
			} else {
				$this->ic_prazo->ViewValue = NULL;
			}
			$this->ic_prazo->ViewCustomAttributes = "";

			// ic_custo
			if (strval($this->ic_custo->CurrentValue) <> "") {
				switch ($this->ic_custo->CurrentValue) {
					case $this->ic_custo->FldTagValue(1):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(1) <> "" ? $this->ic_custo->FldTagCaption(1) : $this->ic_custo->CurrentValue;
						break;
					case $this->ic_custo->FldTagValue(2):
						$this->ic_custo->ViewValue = $this->ic_custo->FldTagCaption(2) <> "" ? $this->ic_custo->FldTagCaption(2) : $this->ic_custo->CurrentValue;
						break;
					default:
						$this->ic_custo->ViewValue = $this->ic_custo->CurrentValue;
				}
			} else {
				$this->ic_custo->ViewValue = NULL;
			}
			$this->ic_custo->ViewCustomAttributes = "";

			// ic_bloqueio
			$this->ic_bloqueio->ViewValue = $this->ic_bloqueio->CurrentValue;
			$this->ic_bloqueio->ViewCustomAttributes = "";

			// nu_solicitacao
			$this->nu_solicitacao->LinkCustomAttributes = "";
			$this->nu_solicitacao->HrefValue = "";
			$this->nu_solicitacao->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// ds_sobreDocumentacao
			$this->ds_sobreDocumentacao->LinkCustomAttributes = "";
			$this->ds_sobreDocumentacao->HrefValue = "";
			$this->ds_sobreDocumentacao->TooltipValue = "";

			// ds_sobreMetrificacao
			$this->ds_sobreMetrificacao->LinkCustomAttributes = "";
			$this->ds_sobreMetrificacao->HrefValue = "";
			$this->ds_sobreMetrificacao->TooltipValue = "";

			// qt_pf
			$this->qt_pf->LinkCustomAttributes = "";
			$this->qt_pf->HrefValue = "";
			$this->qt_pf->TooltipValue = "";

			// qt_horas
			$this->qt_horas->LinkCustomAttributes = "";
			$this->qt_horas->HrefValue = "";
			$this->qt_horas->TooltipValue = "";

			// qt_prazoMeses
			$this->qt_prazoMeses->LinkCustomAttributes = "";
			$this->qt_prazoMeses->HrefValue = "";
			$this->qt_prazoMeses->TooltipValue = "";

			// qt_prazoDias
			$this->qt_prazoDias->LinkCustomAttributes = "";
			$this->qt_prazoDias->HrefValue = "";
			$this->qt_prazoDias->TooltipValue = "";

			// vr_contratacao
			$this->vr_contratacao->LinkCustomAttributes = "";
			$this->vr_contratacao->HrefValue = "";
			$this->vr_contratacao->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// dt_inicioSolicitacao
			$this->dt_inicioSolicitacao->LinkCustomAttributes = "";
			$this->dt_inicioSolicitacao->HrefValue = "";
			$this->dt_inicioSolicitacao->TooltipValue = "";

			// dt_inicioContagem
			$this->dt_inicioContagem->LinkCustomAttributes = "";
			$this->dt_inicioContagem->HrefValue = "";
			$this->dt_inicioContagem->TooltipValue = "";

			// dt_emissao
			$this->dt_emissao->LinkCustomAttributes = "";
			$this->dt_emissao->HrefValue = "";
			$this->dt_emissao->TooltipValue = "";

			// hh_emissao
			$this->hh_emissao->LinkCustomAttributes = "";
			$this->hh_emissao->HrefValue = "";
			$this->hh_emissao->TooltipValue = "";

			// ic_tamanho
			$this->ic_tamanho->LinkCustomAttributes = "";
			$this->ic_tamanho->HrefValue = "";
			$this->ic_tamanho->TooltipValue = "";

			// ic_esforco
			$this->ic_esforco->LinkCustomAttributes = "";
			$this->ic_esforco->HrefValue = "";
			$this->ic_esforco->TooltipValue = "";

			// ic_prazo
			$this->ic_prazo->LinkCustomAttributes = "";
			$this->ic_prazo->HrefValue = "";
			$this->ic_prazo->TooltipValue = "";

			// ic_custo
			$this->ic_custo->LinkCustomAttributes = "";
			$this->ic_custo->HrefValue = "";
			$this->ic_custo->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_laudo\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_laudo',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.flaudoview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "laudolist.php", $this->TableVar);
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
if (!isset($laudo_view)) $laudo_view = new claudo_view();

// Page init
$laudo_view->Page_Init();

// Page main
$laudo_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$laudo_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($laudo->Export == "") { ?>
<script type="text/javascript">

// Page object
var laudo_view = new ew_Page("laudo_view");
laudo_view.PageID = "view"; // Page ID
var EW_PAGE_ID = laudo_view.PageID; // For backward compatibility

// Form object
var flaudoview = new ew_Form("flaudoview");

// Form_CustomValidate event
flaudoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flaudoview.ValidateRequired = true;
<?php } else { ?>
flaudoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
flaudoview.Lists["x_nu_solicitacao"] = {"LinkField":"x_nu_solMetricas","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_solMetricas","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
flaudoview.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($laudo->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($laudo->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $laudo_view->ExportOptions->Render("body") ?>
<?php if (!$laudo_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($laudo_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $laudo_view->ShowPageHeader(); ?>
<?php
$laudo_view->ShowMessage();
?>
<form name="flaudoview" id="flaudoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="laudo">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_laudoview" class="table table-bordered table-striped">
<?php if ($laudo->nu_solicitacao->Visible) { // nu_solicitacao ?>
	<tr id="r_nu_solicitacao">
		<td><span id="elh_laudo_nu_solicitacao"><?php echo $laudo->nu_solicitacao->FldCaption() ?></span></td>
		<td<?php echo $laudo->nu_solicitacao->CellAttributes() ?>>
<span id="el_laudo_nu_solicitacao" class="control-group">
<span<?php echo $laudo->nu_solicitacao->ViewAttributes() ?>>
<?php echo $laudo->nu_solicitacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->nu_versao->Visible) { // nu_versao ?>
	<tr id="r_nu_versao">
		<td><span id="elh_laudo_nu_versao"><?php echo $laudo->nu_versao->FldCaption() ?></span></td>
		<td<?php echo $laudo->nu_versao->CellAttributes() ?>>
<span id="el_laudo_nu_versao" class="control-group">
<span<?php echo $laudo->nu_versao->ViewAttributes() ?>>
<?php echo $laudo->nu_versao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->ds_sobreDocumentacao->Visible) { // ds_sobreDocumentacao ?>
	<tr id="r_ds_sobreDocumentacao">
		<td><span id="elh_laudo_ds_sobreDocumentacao"><?php echo $laudo->ds_sobreDocumentacao->FldCaption() ?></span></td>
		<td<?php echo $laudo->ds_sobreDocumentacao->CellAttributes() ?>>
<span id="el_laudo_ds_sobreDocumentacao" class="control-group">
<span<?php echo $laudo->ds_sobreDocumentacao->ViewAttributes() ?>>
<?php echo $laudo->ds_sobreDocumentacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->ds_sobreMetrificacao->Visible) { // ds_sobreMetrificacao ?>
	<tr id="r_ds_sobreMetrificacao">
		<td><span id="elh_laudo_ds_sobreMetrificacao"><?php echo $laudo->ds_sobreMetrificacao->FldCaption() ?></span></td>
		<td<?php echo $laudo->ds_sobreMetrificacao->CellAttributes() ?>>
<span id="el_laudo_ds_sobreMetrificacao" class="control-group">
<span<?php echo $laudo->ds_sobreMetrificacao->ViewAttributes() ?>>
<?php echo $laudo->ds_sobreMetrificacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->qt_pf->Visible) { // qt_pf ?>
	<tr id="r_qt_pf">
		<td><span id="elh_laudo_qt_pf"><?php echo $laudo->qt_pf->FldCaption() ?></span></td>
		<td<?php echo $laudo->qt_pf->CellAttributes() ?>>
<span id="el_laudo_qt_pf" class="control-group">
<span<?php echo $laudo->qt_pf->ViewAttributes() ?>>
<?php echo $laudo->qt_pf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->qt_horas->Visible) { // qt_horas ?>
	<tr id="r_qt_horas">
		<td><span id="elh_laudo_qt_horas"><?php echo $laudo->qt_horas->FldCaption() ?></span></td>
		<td<?php echo $laudo->qt_horas->CellAttributes() ?>>
<span id="el_laudo_qt_horas" class="control-group">
<span<?php echo $laudo->qt_horas->ViewAttributes() ?>>
<?php echo $laudo->qt_horas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->qt_prazoMeses->Visible) { // qt_prazoMeses ?>
	<tr id="r_qt_prazoMeses">
		<td><span id="elh_laudo_qt_prazoMeses"><?php echo $laudo->qt_prazoMeses->FldCaption() ?></span></td>
		<td<?php echo $laudo->qt_prazoMeses->CellAttributes() ?>>
<span id="el_laudo_qt_prazoMeses" class="control-group">
<span<?php echo $laudo->qt_prazoMeses->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoMeses->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->qt_prazoDias->Visible) { // qt_prazoDias ?>
	<tr id="r_qt_prazoDias">
		<td><span id="elh_laudo_qt_prazoDias"><?php echo $laudo->qt_prazoDias->FldCaption() ?></span></td>
		<td<?php echo $laudo->qt_prazoDias->CellAttributes() ?>>
<span id="el_laudo_qt_prazoDias" class="control-group">
<span<?php echo $laudo->qt_prazoDias->ViewAttributes() ?>>
<?php echo $laudo->qt_prazoDias->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->vr_contratacao->Visible) { // vr_contratacao ?>
	<tr id="r_vr_contratacao">
		<td><span id="elh_laudo_vr_contratacao"><?php echo $laudo->vr_contratacao->FldCaption() ?></span></td>
		<td<?php echo $laudo->vr_contratacao->CellAttributes() ?>>
<span id="el_laudo_vr_contratacao" class="control-group">
<span<?php echo $laudo->vr_contratacao->ViewAttributes() ?>>
<?php echo $laudo->vr_contratacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<tr id="r_nu_usuarioResp">
		<td><span id="elh_laudo_nu_usuarioResp"><?php echo $laudo->nu_usuarioResp->FldCaption() ?></span></td>
		<td<?php echo $laudo->nu_usuarioResp->CellAttributes() ?>>
<span id="el_laudo_nu_usuarioResp" class="control-group">
<span<?php echo $laudo->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $laudo->nu_usuarioResp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->dt_inicioSolicitacao->Visible) { // dt_inicioSolicitacao ?>
	<tr id="r_dt_inicioSolicitacao">
		<td><span id="elh_laudo_dt_inicioSolicitacao"><?php echo $laudo->dt_inicioSolicitacao->FldCaption() ?></span></td>
		<td<?php echo $laudo->dt_inicioSolicitacao->CellAttributes() ?>>
<span id="el_laudo_dt_inicioSolicitacao" class="control-group">
<span<?php echo $laudo->dt_inicioSolicitacao->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioSolicitacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->dt_inicioContagem->Visible) { // dt_inicioContagem ?>
	<tr id="r_dt_inicioContagem">
		<td><span id="elh_laudo_dt_inicioContagem"><?php echo $laudo->dt_inicioContagem->FldCaption() ?></span></td>
		<td<?php echo $laudo->dt_inicioContagem->CellAttributes() ?>>
<span id="el_laudo_dt_inicioContagem" class="control-group">
<span<?php echo $laudo->dt_inicioContagem->ViewAttributes() ?>>
<?php echo $laudo->dt_inicioContagem->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->dt_emissao->Visible) { // dt_emissao ?>
	<tr id="r_dt_emissao">
		<td><span id="elh_laudo_dt_emissao"><?php echo $laudo->dt_emissao->FldCaption() ?></span></td>
		<td<?php echo $laudo->dt_emissao->CellAttributes() ?>>
<span id="el_laudo_dt_emissao" class="control-group">
<span<?php echo $laudo->dt_emissao->ViewAttributes() ?>>
<?php echo $laudo->dt_emissao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->hh_emissao->Visible) { // hh_emissao ?>
	<tr id="r_hh_emissao">
		<td><span id="elh_laudo_hh_emissao"><?php echo $laudo->hh_emissao->FldCaption() ?></span></td>
		<td<?php echo $laudo->hh_emissao->CellAttributes() ?>>
<span id="el_laudo_hh_emissao" class="control-group">
<span<?php echo $laudo->hh_emissao->ViewAttributes() ?>>
<?php echo $laudo->hh_emissao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->ic_tamanho->Visible) { // ic_tamanho ?>
	<tr id="r_ic_tamanho">
		<td><span id="elh_laudo_ic_tamanho"><?php echo $laudo->ic_tamanho->FldCaption() ?></span></td>
		<td<?php echo $laudo->ic_tamanho->CellAttributes() ?>>
<span id="el_laudo_ic_tamanho" class="control-group">
<span<?php echo $laudo->ic_tamanho->ViewAttributes() ?>>
<?php echo $laudo->ic_tamanho->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->ic_esforco->Visible) { // ic_esforco ?>
	<tr id="r_ic_esforco">
		<td><span id="elh_laudo_ic_esforco"><?php echo $laudo->ic_esforco->FldCaption() ?></span></td>
		<td<?php echo $laudo->ic_esforco->CellAttributes() ?>>
<span id="el_laudo_ic_esforco" class="control-group">
<span<?php echo $laudo->ic_esforco->ViewAttributes() ?>>
<?php echo $laudo->ic_esforco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->ic_prazo->Visible) { // ic_prazo ?>
	<tr id="r_ic_prazo">
		<td><span id="elh_laudo_ic_prazo"><?php echo $laudo->ic_prazo->FldCaption() ?></span></td>
		<td<?php echo $laudo->ic_prazo->CellAttributes() ?>>
<span id="el_laudo_ic_prazo" class="control-group">
<span<?php echo $laudo->ic_prazo->ViewAttributes() ?>>
<?php echo $laudo->ic_prazo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($laudo->ic_custo->Visible) { // ic_custo ?>
	<tr id="r_ic_custo">
		<td><span id="elh_laudo_ic_custo"><?php echo $laudo->ic_custo->FldCaption() ?></span></td>
		<td<?php echo $laudo->ic_custo->CellAttributes() ?>>
<span id="el_laudo_ic_custo" class="control-group">
<span<?php echo $laudo->ic_custo->ViewAttributes() ?>>
<?php echo $laudo->ic_custo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($laudo->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($laudo_view->Pager)) $laudo_view->Pager = new cNumericPager($laudo_view->StartRec, $laudo_view->DisplayRecs, $laudo_view->TotalRecs, $laudo_view->RecRange) ?>
<?php if ($laudo_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($laudo_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $laudo_view->PageUrl() ?>start=<?php echo $laudo_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($laudo_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $laudo_view->PageUrl() ?>start=<?php echo $laudo_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($laudo_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $laudo_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($laudo_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $laudo_view->PageUrl() ?>start=<?php echo $laudo_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($laudo_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $laudo_view->PageUrl() ?>start=<?php echo $laudo_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
flaudoview.Init();
</script>
<?php
$laudo_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($laudo->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$laudo_view->Page_Terminate();
?>
