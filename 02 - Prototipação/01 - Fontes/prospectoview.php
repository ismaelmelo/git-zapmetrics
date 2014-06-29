<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "prospectoinfo.php" ?>
<?php include_once "rprospresumoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "prospecto_itempdtigridcls.php" ?>
<?php include_once "prospectoocorrenciasgridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$prospecto_view = NULL; // Initialize page object first

class cprospecto_view extends cprospecto {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'prospecto';

	// Page object name
	var $PageObjName = 'prospecto_view';

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

		// Table object (prospecto)
		if (!isset($GLOBALS["prospecto"])) {
			$GLOBALS["prospecto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["prospecto"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_prospecto"] <> "") {
			$this->RecKey["nu_prospecto"] = $_GET["nu_prospecto"];
			$KeyUrl .= "&nu_prospecto=" . urlencode($this->RecKey["nu_prospecto"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (rprospresumo)
		if (!isset($GLOBALS['rprospresumo'])) $GLOBALS['rprospresumo'] = new crprospresumo();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'prospecto', TRUE);

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
			$this->Page_Terminate("prospectolist.php");
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
		if (@$_GET["nu_prospecto"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_prospecto"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_prospecto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_prospecto"] <> "") {
				$this->nu_prospecto->setQueryStringValue($_GET["nu_prospecto"]);
				$this->RecKey["nu_prospecto"] = $this->nu_prospecto->QueryStringValue;
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
						$this->Page_Terminate("prospectolist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_prospecto->CurrentValue) == strval($this->Recordset->fields('nu_prospecto'))) {
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
						$sReturnUrl = "prospectolist.php"; // No matching record, return to list
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
			$sReturnUrl = "prospectolist.php"; // Not page request, return to list
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

		// Detail table 'prospecto_itempdti'
		$body = $Language->TablePhrase("prospecto_itempdti", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("prospecto_itempdtilist.php?" . EW_TABLE_SHOW_MASTER . "=prospecto&nu_prospecto=" . strval($this->nu_prospecto->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_prospecto_itempdti");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'prospecto_itempdti');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "prospecto_itempdti";
		}

		// Detail table 'prospectoocorrencias'
		$body = $Language->TablePhrase("prospectoocorrencias", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("prospectoocorrenciaslist.php?" . EW_TABLE_SHOW_MASTER . "=prospecto&nu_prospecto=" . strval($this->nu_prospecto->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_prospectoocorrencias");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'prospectoocorrencias');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "prospectoocorrencias";
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
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->no_prospecto->setDbValue($rs->fields('no_prospecto'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->no_solicitante->setDbValue($rs->fields('no_solicitante'));
		$this->no_patrocinador->setDbValue($rs->fields('no_patrocinador'));
		$this->ar_entidade->setDbValue($rs->fields('ar_entidade'));
		$this->ar_nivel->setDbValue($rs->fields('ar_nivel'));
		$this->nu_categoriaProspecto->setDbValue($rs->fields('nu_categoriaProspecto'));
		$this->nu_alternativaImpacto->setDbValue($rs->fields('nu_alternativaImpacto'));
		$this->ds_sistemas->setDbValue($rs->fields('ds_sistemas'));
		$this->ds_impactoNaoImplem->setDbValue($rs->fields('ds_impactoNaoImplem'));
		$this->nu_alternativaAlinhamento->setDbValue($rs->fields('nu_alternativaAlinhamento'));
		$this->nu_alternativaAbrangencia->setDbValue($rs->fields('nu_alternativaAbrangencia'));
		$this->nu_alternativaUrgencia->setDbValue($rs->fields('nu_alternativaUrgencia'));
		$this->dt_prazo->setDbValue($rs->fields('dt_prazo'));
		$this->nu_alternativaTmpEstimado->setDbValue($rs->fields('nu_alternativaTmpEstimado'));
		$this->nu_alternativaTmpFila->setDbValue($rs->fields('nu_alternativaTmpFila'));
		$this->ic_implicacaoLegal->setDbValue($rs->fields('ic_implicacaoLegal'));
		$this->ic_risco->setDbValue($rs->fields('ic_risco'));
		$this->ic_stProspecto->setDbValue($rs->fields('ic_stProspecto'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->ic_ativo->setDbValue($rs->fields('ic_ativo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->no_prospecto->DbValue = $row['no_prospecto'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->no_solicitante->DbValue = $row['no_solicitante'];
		$this->no_patrocinador->DbValue = $row['no_patrocinador'];
		$this->ar_entidade->DbValue = $row['ar_entidade'];
		$this->ar_nivel->DbValue = $row['ar_nivel'];
		$this->nu_categoriaProspecto->DbValue = $row['nu_categoriaProspecto'];
		$this->nu_alternativaImpacto->DbValue = $row['nu_alternativaImpacto'];
		$this->ds_sistemas->DbValue = $row['ds_sistemas'];
		$this->ds_impactoNaoImplem->DbValue = $row['ds_impactoNaoImplem'];
		$this->nu_alternativaAlinhamento->DbValue = $row['nu_alternativaAlinhamento'];
		$this->nu_alternativaAbrangencia->DbValue = $row['nu_alternativaAbrangencia'];
		$this->nu_alternativaUrgencia->DbValue = $row['nu_alternativaUrgencia'];
		$this->dt_prazo->DbValue = $row['dt_prazo'];
		$this->nu_alternativaTmpEstimado->DbValue = $row['nu_alternativaTmpEstimado'];
		$this->nu_alternativaTmpFila->DbValue = $row['nu_alternativaTmpFila'];
		$this->ic_implicacaoLegal->DbValue = $row['ic_implicacaoLegal'];
		$this->ic_risco->DbValue = $row['ic_risco'];
		$this->ic_stProspecto->DbValue = $row['ic_stProspecto'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
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
		// nu_prospecto
		// no_prospecto
		// nu_area
		// no_solicitante
		// no_patrocinador
		// ar_entidade
		// ar_nivel
		// nu_categoriaProspecto
		// nu_alternativaImpacto
		// ds_sistemas
		// ds_impactoNaoImplem
		// nu_alternativaAlinhamento
		// nu_alternativaAbrangencia
		// nu_alternativaUrgencia
		// dt_prazo
		// nu_alternativaTmpEstimado
		// nu_alternativaTmpFila
		// ic_implicacaoLegal
		// ic_risco
		// ic_stProspecto
		// ds_observacoes
		// ic_ativo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_prospecto
			$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
			$this->nu_prospecto->ViewCustomAttributes = "";

			// no_prospecto
			$this->no_prospecto->ViewValue = $this->no_prospecto->CurrentValue;
			$this->no_prospecto->ViewCustomAttributes = "";

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

			// no_solicitante
			$this->no_solicitante->ViewValue = $this->no_solicitante->CurrentValue;
			$this->no_solicitante->ViewCustomAttributes = "";

			// no_patrocinador
			$this->no_patrocinador->ViewValue = $this->no_patrocinador->CurrentValue;
			$this->no_patrocinador->ViewCustomAttributes = "";

			// ar_entidade
			if (strval($this->ar_entidade->CurrentValue) <> "") {
				$arwrk = explode(",", $this->ar_entidade->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "[nu_organizacao]" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER);
				}	
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
			$this->Lookup_Selecting($this->ar_entidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_organizacao] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->ar_entidade->ViewValue = "";
					$ari = 0;
					while (!$rswrk->EOF) {
						$this->ar_entidade->ViewValue .= $rswrk->fields('DispFld');
						$rswrk->MoveNext();
						if (!$rswrk->EOF) $this->ar_entidade->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
						$ari++;
					}
					$rswrk->Close();
				} else {
					$this->ar_entidade->ViewValue = $this->ar_entidade->CurrentValue;
				}
			} else {
				$this->ar_entidade->ViewValue = NULL;
			}
			$this->ar_entidade->ViewCustomAttributes = "";

			// ar_nivel
			if (strval($this->ar_nivel->CurrentValue) <> "") {
				$this->ar_nivel->ViewValue = "";
				$arwrk = explode(",", strval($this->ar_nivel->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->ar_nivel->FldTagValue(1):
							$this->ar_nivel->ViewValue .= $this->ar_nivel->FldTagCaption(1) <> "" ? $this->ar_nivel->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						case $this->ar_nivel->FldTagValue(2):
							$this->ar_nivel->ViewValue .= $this->ar_nivel->FldTagCaption(2) <> "" ? $this->ar_nivel->FldTagCaption(2) : trim($arwrk[$ari]);
							break;
						default:
							$this->ar_nivel->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->ar_nivel->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->ar_nivel->ViewValue = NULL;
			}
			$this->ar_nivel->ViewCustomAttributes = "";

			// nu_categoriaProspecto
			if (strval($this->nu_categoriaProspecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_categoria]" . ew_SearchString("=", $this->nu_categoriaProspecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_categoria], [no_categoria] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catprospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_categoriaProspecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_categoria] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_categoriaProspecto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_categoriaProspecto->ViewValue = $this->nu_categoriaProspecto->CurrentValue;
				}
			} else {
				$this->nu_categoriaProspecto->ViewValue = NULL;
			}
			$this->nu_categoriaProspecto->ViewCustomAttributes = "";

			// nu_alternativaImpacto
			if (strval($this->nu_alternativaImpacto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaImpacto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 10";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaImpacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaImpacto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaImpacto->ViewValue = $this->nu_alternativaImpacto->CurrentValue;
				}
			} else {
				$this->nu_alternativaImpacto->ViewValue = NULL;
			}
			$this->nu_alternativaImpacto->ViewCustomAttributes = "";

			// ds_sistemas
			$this->ds_sistemas->ViewValue = $this->ds_sistemas->CurrentValue;
			$this->ds_sistemas->ViewCustomAttributes = "";

			// ds_impactoNaoImplem
			$this->ds_impactoNaoImplem->ViewValue = $this->ds_impactoNaoImplem->CurrentValue;
			$this->ds_impactoNaoImplem->ViewCustomAttributes = "";

			// nu_alternativaAlinhamento
			if (strval($this->nu_alternativaAlinhamento->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaAlinhamento->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = '11'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaAlinhamento, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaAlinhamento->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaAlinhamento->ViewValue = $this->nu_alternativaAlinhamento->CurrentValue;
				}
			} else {
				$this->nu_alternativaAlinhamento->ViewValue = NULL;
			}
			$this->nu_alternativaAlinhamento->ViewCustomAttributes = "";

			// nu_alternativaAbrangencia
			if (strval($this->nu_alternativaAbrangencia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaAbrangencia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 12";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaAbrangencia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaAbrangencia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaAbrangencia->ViewValue = $this->nu_alternativaAbrangencia->CurrentValue;
				}
			} else {
				$this->nu_alternativaAbrangencia->ViewValue = NULL;
			}
			$this->nu_alternativaAbrangencia->ViewCustomAttributes = "";

			// nu_alternativaUrgencia
			if (strval($this->nu_alternativaUrgencia->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaUrgencia->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 13";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaUrgencia, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaUrgencia->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaUrgencia->ViewValue = $this->nu_alternativaUrgencia->CurrentValue;
				}
			} else {
				$this->nu_alternativaUrgencia->ViewValue = NULL;
			}
			$this->nu_alternativaUrgencia->ViewCustomAttributes = "";

			// dt_prazo
			$this->dt_prazo->ViewValue = $this->dt_prazo->CurrentValue;
			$this->dt_prazo->ViewValue = ew_FormatDateTime($this->dt_prazo->ViewValue, 7);
			$this->dt_prazo->ViewCustomAttributes = "";

			// nu_alternativaTmpEstimado
			if (strval($this->nu_alternativaTmpEstimado->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaTmpEstimado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 14";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaTmpEstimado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaTmpEstimado->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaTmpEstimado->ViewValue = $this->nu_alternativaTmpEstimado->CurrentValue;
				}
			} else {
				$this->nu_alternativaTmpEstimado->ViewValue = NULL;
			}
			$this->nu_alternativaTmpEstimado->ViewCustomAttributes = "";

			// nu_alternativaTmpFila
			if (strval($this->nu_alternativaTmpFila->CurrentValue) <> "") {
				$sFilterWrk = "[nu_alternativaAvaliacao]" . ew_SearchString("=", $this->nu_alternativaTmpFila->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_alternativaAvaliacao], [no_alternativa] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[criterioavaliacao]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND [nu_criterioPrioridade] = 15";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_alternativaTmpFila, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [vr_alternativa] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_alternativaTmpFila->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_alternativaTmpFila->ViewValue = $this->nu_alternativaTmpFila->CurrentValue;
				}
			} else {
				$this->nu_alternativaTmpFila->ViewValue = NULL;
			}
			$this->nu_alternativaTmpFila->ViewCustomAttributes = "";

			// ic_implicacaoLegal
			if (strval($this->ic_implicacaoLegal->CurrentValue) <> "") {
				switch ($this->ic_implicacaoLegal->CurrentValue) {
					case $this->ic_implicacaoLegal->FldTagValue(1):
						$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->FldTagCaption(1) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(1) : $this->ic_implicacaoLegal->CurrentValue;
						break;
					case $this->ic_implicacaoLegal->FldTagValue(2):
						$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->FldTagCaption(2) <> "" ? $this->ic_implicacaoLegal->FldTagCaption(2) : $this->ic_implicacaoLegal->CurrentValue;
						break;
					default:
						$this->ic_implicacaoLegal->ViewValue = $this->ic_implicacaoLegal->CurrentValue;
				}
			} else {
				$this->ic_implicacaoLegal->ViewValue = NULL;
			}
			$this->ic_implicacaoLegal->ViewCustomAttributes = "";

			// ic_risco
			if (strval($this->ic_risco->CurrentValue) <> "") {
				switch ($this->ic_risco->CurrentValue) {
					case $this->ic_risco->FldTagValue(1):
						$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(1) <> "" ? $this->ic_risco->FldTagCaption(1) : $this->ic_risco->CurrentValue;
						break;
					case $this->ic_risco->FldTagValue(2):
						$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(2) <> "" ? $this->ic_risco->FldTagCaption(2) : $this->ic_risco->CurrentValue;
						break;
					case $this->ic_risco->FldTagValue(3):
						$this->ic_risco->ViewValue = $this->ic_risco->FldTagCaption(3) <> "" ? $this->ic_risco->FldTagCaption(3) : $this->ic_risco->CurrentValue;
						break;
					default:
						$this->ic_risco->ViewValue = $this->ic_risco->CurrentValue;
				}
			} else {
				$this->ic_risco->ViewValue = NULL;
			}
			$this->ic_risco->ViewCustomAttributes = "";

			// ic_stProspecto
			if (strval($this->ic_stProspecto->CurrentValue) <> "") {
				switch ($this->ic_stProspecto->CurrentValue) {
					case $this->ic_stProspecto->FldTagValue(1):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(1) <> "" ? $this->ic_stProspecto->FldTagCaption(1) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(2):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(2) <> "" ? $this->ic_stProspecto->FldTagCaption(2) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(3):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(3) <> "" ? $this->ic_stProspecto->FldTagCaption(3) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(4):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(4) <> "" ? $this->ic_stProspecto->FldTagCaption(4) : $this->ic_stProspecto->CurrentValue;
						break;
					case $this->ic_stProspecto->FldTagValue(5):
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->FldTagCaption(5) <> "" ? $this->ic_stProspecto->FldTagCaption(5) : $this->ic_stProspecto->CurrentValue;
						break;
					default:
						$this->ic_stProspecto->ViewValue = $this->ic_stProspecto->CurrentValue;
				}
			} else {
				$this->ic_stProspecto->ViewValue = NULL;
			}
			$this->ic_stProspecto->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

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

			// nu_prospecto
			$this->nu_prospecto->LinkCustomAttributes = "";
			$this->nu_prospecto->HrefValue = "";
			$this->nu_prospecto->TooltipValue = "";

			// no_prospecto
			$this->no_prospecto->LinkCustomAttributes = "";
			$this->no_prospecto->HrefValue = "";
			$this->no_prospecto->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// no_solicitante
			$this->no_solicitante->LinkCustomAttributes = "";
			$this->no_solicitante->HrefValue = "";
			$this->no_solicitante->TooltipValue = "";

			// no_patrocinador
			$this->no_patrocinador->LinkCustomAttributes = "";
			$this->no_patrocinador->HrefValue = "";
			$this->no_patrocinador->TooltipValue = "";

			// ar_entidade
			$this->ar_entidade->LinkCustomAttributes = "";
			$this->ar_entidade->HrefValue = "";
			$this->ar_entidade->TooltipValue = "";

			// ar_nivel
			$this->ar_nivel->LinkCustomAttributes = "";
			$this->ar_nivel->HrefValue = "";
			$this->ar_nivel->TooltipValue = "";

			// nu_categoriaProspecto
			$this->nu_categoriaProspecto->LinkCustomAttributes = "";
			$this->nu_categoriaProspecto->HrefValue = "";
			$this->nu_categoriaProspecto->TooltipValue = "";

			// nu_alternativaImpacto
			$this->nu_alternativaImpacto->LinkCustomAttributes = "";
			$this->nu_alternativaImpacto->HrefValue = "";
			$this->nu_alternativaImpacto->TooltipValue = "";

			// ds_sistemas
			$this->ds_sistemas->LinkCustomAttributes = "";
			$this->ds_sistemas->HrefValue = "";
			$this->ds_sistemas->TooltipValue = "";

			// ds_impactoNaoImplem
			$this->ds_impactoNaoImplem->LinkCustomAttributes = "";
			$this->ds_impactoNaoImplem->HrefValue = "";
			$this->ds_impactoNaoImplem->TooltipValue = "";

			// nu_alternativaAlinhamento
			$this->nu_alternativaAlinhamento->LinkCustomAttributes = "";
			$this->nu_alternativaAlinhamento->HrefValue = "";
			$this->nu_alternativaAlinhamento->TooltipValue = "";

			// nu_alternativaAbrangencia
			$this->nu_alternativaAbrangencia->LinkCustomAttributes = "";
			$this->nu_alternativaAbrangencia->HrefValue = "";
			$this->nu_alternativaAbrangencia->TooltipValue = "";

			// nu_alternativaUrgencia
			$this->nu_alternativaUrgencia->LinkCustomAttributes = "";
			$this->nu_alternativaUrgencia->HrefValue = "";
			$this->nu_alternativaUrgencia->TooltipValue = "";

			// dt_prazo
			$this->dt_prazo->LinkCustomAttributes = "";
			$this->dt_prazo->HrefValue = "";
			$this->dt_prazo->TooltipValue = "";

			// nu_alternativaTmpEstimado
			$this->nu_alternativaTmpEstimado->LinkCustomAttributes = "";
			$this->nu_alternativaTmpEstimado->HrefValue = "";
			$this->nu_alternativaTmpEstimado->TooltipValue = "";

			// nu_alternativaTmpFila
			$this->nu_alternativaTmpFila->LinkCustomAttributes = "";
			$this->nu_alternativaTmpFila->HrefValue = "";
			$this->nu_alternativaTmpFila->TooltipValue = "";

			// ic_implicacaoLegal
			$this->ic_implicacaoLegal->LinkCustomAttributes = "";
			$this->ic_implicacaoLegal->HrefValue = "";
			$this->ic_implicacaoLegal->TooltipValue = "";

			// ic_risco
			$this->ic_risco->LinkCustomAttributes = "";
			$this->ic_risco->HrefValue = "";
			$this->ic_risco->TooltipValue = "";

			// ic_stProspecto
			$this->ic_stProspecto->LinkCustomAttributes = "";
			$this->ic_stProspecto->HrefValue = "";
			$this->ic_stProspecto->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";

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
		$item->Body = "<a id=\"emf_prospecto\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_prospecto',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fprospectoview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
			if (in_array("prospecto_itempdti", $DetailTblVar)) {
				if (!isset($GLOBALS["prospecto_itempdti_grid"]))
					$GLOBALS["prospecto_itempdti_grid"] = new cprospecto_itempdti_grid;
				if ($GLOBALS["prospecto_itempdti_grid"]->DetailView) {
					$GLOBALS["prospecto_itempdti_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["prospecto_itempdti_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["prospecto_itempdti_grid"]->setStartRecordNumber(1);
					$GLOBALS["prospecto_itempdti_grid"]->nu_prospecto->FldIsDetailKey = TRUE;
					$GLOBALS["prospecto_itempdti_grid"]->nu_prospecto->CurrentValue = $this->nu_prospecto->CurrentValue;
					$GLOBALS["prospecto_itempdti_grid"]->nu_prospecto->setSessionValue($GLOBALS["prospecto_itempdti_grid"]->nu_prospecto->CurrentValue);
				}
			}
			if (in_array("prospectoocorrencias", $DetailTblVar)) {
				if (!isset($GLOBALS["prospectoocorrencias_grid"]))
					$GLOBALS["prospectoocorrencias_grid"] = new cprospectoocorrencias_grid;
				if ($GLOBALS["prospectoocorrencias_grid"]->DetailView) {
					$GLOBALS["prospectoocorrencias_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["prospectoocorrencias_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["prospectoocorrencias_grid"]->setStartRecordNumber(1);
					$GLOBALS["prospectoocorrencias_grid"]->nu_prospecto->FldIsDetailKey = TRUE;
					$GLOBALS["prospectoocorrencias_grid"]->nu_prospecto->CurrentValue = $this->nu_prospecto->CurrentValue;
					$GLOBALS["prospectoocorrencias_grid"]->nu_prospecto->setSessionValue($GLOBALS["prospectoocorrencias_grid"]->nu_prospecto->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "prospectolist.php", $this->TableVar);
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
if (!isset($prospecto_view)) $prospecto_view = new cprospecto_view();

// Page init
$prospecto_view->Page_Init();

// Page main
$prospecto_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$prospecto_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($prospecto->Export == "") { ?>
<script type="text/javascript">

// Page object
var prospecto_view = new ew_Page("prospecto_view");
prospecto_view.PageID = "view"; // Page ID
var EW_PAGE_ID = prospecto_view.PageID; // For backward compatibility

// Form object
var fprospectoview = new ew_Form("fprospectoview");

// Form_CustomValidate event
fprospectoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprospectoview.ValidateRequired = true;
<?php } else { ?>
fprospectoview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fprospectoview.MultiPage = new ew_MultiPage("fprospectoview",
	[["x_nu_prospecto",1],["x_no_prospecto",1],["x_nu_area",1],["x_no_solicitante",1],["x_no_patrocinador",1],["x_ar_entidade",1],["x_ar_nivel",1],["x_nu_categoriaProspecto",1],["x_nu_alternativaImpacto",1],["x_ds_sistemas",1],["x_ds_impactoNaoImplem",1],["x_nu_alternativaAlinhamento",2],["x_nu_alternativaAbrangencia",2],["x_nu_alternativaUrgencia",2],["x_dt_prazo",1],["x_nu_alternativaTmpEstimado",2],["x_nu_alternativaTmpFila",2],["x_ic_implicacaoLegal",2],["x_ic_risco",1],["x_ic_stProspecto",1],["x_ds_observacoes",1],["x_ic_ativo",1]]
);

// Dynamic selection lists
fprospectoview.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoview.Lists["x_ar_entidade[]"] = {"LinkField":"x_nu_organizacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_organizacao","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoview.Lists["x_nu_categoriaProspecto"] = {"LinkField":"x_nu_categoria","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_categoria","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoview.Lists["x_nu_alternativaImpacto"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoview.Lists["x_nu_alternativaAlinhamento"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoview.Lists["x_nu_alternativaAbrangencia"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoview.Lists["x_nu_alternativaUrgencia"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoview.Lists["x_nu_alternativaTmpEstimado"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprospectoview.Lists["x_nu_alternativaTmpFila"] = {"LinkField":"x_nu_alternativaAvaliacao","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_alternativa","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($prospecto->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($prospecto->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $prospecto_view->ExportOptions->Render("body") ?>
<?php if (!$prospecto_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($prospecto_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $prospecto_view->ShowPageHeader(); ?>
<?php
$prospecto_view->ShowMessage();
?>
<form name="fprospectoview" id="fprospectoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="prospecto">
<?php if ($prospecto->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="prospecto_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_prospecto1" data-toggle="tab"><?php echo $prospecto->PageCaption(1) ?></a></li>
		<li><a href="#tab_prospecto2" data-toggle="tab"><?php echo $prospecto->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
		<div class="tab-pane active" id="tab_prospecto1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_prospectoview1" class="table table-bordered table-striped">
<?php if ($prospecto->nu_prospecto->Visible) { // nu_prospecto ?>
	<tr id="r_nu_prospecto">
		<td><span id="elh_prospecto_nu_prospecto"><?php echo $prospecto->nu_prospecto->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_prospecto->CellAttributes() ?>>
<span id="el_prospecto_nu_prospecto" class="control-group">
<span<?php echo $prospecto->nu_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_prospecto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->no_prospecto->Visible) { // no_prospecto ?>
	<tr id="r_no_prospecto">
		<td><span id="elh_prospecto_no_prospecto"><?php echo $prospecto->no_prospecto->FldCaption() ?></span></td>
		<td<?php echo $prospecto->no_prospecto->CellAttributes() ?>>
<span id="el_prospecto_no_prospecto" class="control-group">
<span<?php echo $prospecto->no_prospecto->ViewAttributes() ?>>
<?php echo $prospecto->no_prospecto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_area->Visible) { // nu_area ?>
	<tr id="r_nu_area">
		<td><span id="elh_prospecto_nu_area"><?php echo $prospecto->nu_area->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_area->CellAttributes() ?>>
<span id="el_prospecto_nu_area" class="control-group">
<span<?php echo $prospecto->nu_area->ViewAttributes() ?>>
<?php echo $prospecto->nu_area->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->no_solicitante->Visible) { // no_solicitante ?>
	<tr id="r_no_solicitante">
		<td><span id="elh_prospecto_no_solicitante"><?php echo $prospecto->no_solicitante->FldCaption() ?></span></td>
		<td<?php echo $prospecto->no_solicitante->CellAttributes() ?>>
<span id="el_prospecto_no_solicitante" class="control-group">
<span<?php echo $prospecto->no_solicitante->ViewAttributes() ?>>
<?php echo $prospecto->no_solicitante->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->no_patrocinador->Visible) { // no_patrocinador ?>
	<tr id="r_no_patrocinador">
		<td><span id="elh_prospecto_no_patrocinador"><?php echo $prospecto->no_patrocinador->FldCaption() ?></span></td>
		<td<?php echo $prospecto->no_patrocinador->CellAttributes() ?>>
<span id="el_prospecto_no_patrocinador" class="control-group">
<span<?php echo $prospecto->no_patrocinador->ViewAttributes() ?>>
<?php echo $prospecto->no_patrocinador->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->ar_entidade->Visible) { // ar_entidade ?>
	<tr id="r_ar_entidade">
		<td><span id="elh_prospecto_ar_entidade"><?php echo $prospecto->ar_entidade->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ar_entidade->CellAttributes() ?>>
<span id="el_prospecto_ar_entidade" class="control-group">
<span<?php echo $prospecto->ar_entidade->ViewAttributes() ?>>
<?php echo $prospecto->ar_entidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->ar_nivel->Visible) { // ar_nivel ?>
	<tr id="r_ar_nivel">
		<td><span id="elh_prospecto_ar_nivel"><?php echo $prospecto->ar_nivel->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ar_nivel->CellAttributes() ?>>
<span id="el_prospecto_ar_nivel" class="control-group">
<span<?php echo $prospecto->ar_nivel->ViewAttributes() ?>>
<?php echo $prospecto->ar_nivel->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_categoriaProspecto->Visible) { // nu_categoriaProspecto ?>
	<tr id="r_nu_categoriaProspecto">
		<td><span id="elh_prospecto_nu_categoriaProspecto"><?php echo $prospecto->nu_categoriaProspecto->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_categoriaProspecto->CellAttributes() ?>>
<span id="el_prospecto_nu_categoriaProspecto" class="control-group">
<span<?php echo $prospecto->nu_categoriaProspecto->ViewAttributes() ?>>
<?php echo $prospecto->nu_categoriaProspecto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaImpacto->Visible) { // nu_alternativaImpacto ?>
	<tr id="r_nu_alternativaImpacto">
		<td><span id="elh_prospecto_nu_alternativaImpacto"><?php echo $prospecto->nu_alternativaImpacto->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_alternativaImpacto->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaImpacto" class="control-group">
<span<?php echo $prospecto->nu_alternativaImpacto->ViewAttributes() ?>>
<?php echo $prospecto->nu_alternativaImpacto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->ds_sistemas->Visible) { // ds_sistemas ?>
	<tr id="r_ds_sistemas">
		<td><span id="elh_prospecto_ds_sistemas"><?php echo $prospecto->ds_sistemas->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ds_sistemas->CellAttributes() ?>>
<span id="el_prospecto_ds_sistemas" class="control-group">
<span<?php echo $prospecto->ds_sistemas->ViewAttributes() ?>>
<?php echo $prospecto->ds_sistemas->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->ds_impactoNaoImplem->Visible) { // ds_impactoNaoImplem ?>
	<tr id="r_ds_impactoNaoImplem">
		<td><span id="elh_prospecto_ds_impactoNaoImplem"><?php echo $prospecto->ds_impactoNaoImplem->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ds_impactoNaoImplem->CellAttributes() ?>>
<span id="el_prospecto_ds_impactoNaoImplem" class="control-group">
<span<?php echo $prospecto->ds_impactoNaoImplem->ViewAttributes() ?>>
<?php echo $prospecto->ds_impactoNaoImplem->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->dt_prazo->Visible) { // dt_prazo ?>
	<tr id="r_dt_prazo">
		<td><span id="elh_prospecto_dt_prazo"><?php echo $prospecto->dt_prazo->FldCaption() ?></span></td>
		<td<?php echo $prospecto->dt_prazo->CellAttributes() ?>>
<span id="el_prospecto_dt_prazo" class="control-group">
<span<?php echo $prospecto->dt_prazo->ViewAttributes() ?>>
<?php echo $prospecto->dt_prazo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->ic_risco->Visible) { // ic_risco ?>
	<tr id="r_ic_risco">
		<td><span id="elh_prospecto_ic_risco"><?php echo $prospecto->ic_risco->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ic_risco->CellAttributes() ?>>
<span id="el_prospecto_ic_risco" class="control-group">
<span<?php echo $prospecto->ic_risco->ViewAttributes() ?>>
<?php echo $prospecto->ic_risco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->ic_stProspecto->Visible) { // ic_stProspecto ?>
	<tr id="r_ic_stProspecto">
		<td><span id="elh_prospecto_ic_stProspecto"><?php echo $prospecto->ic_stProspecto->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ic_stProspecto->CellAttributes() ?>>
<span id="el_prospecto_ic_stProspecto" class="control-group">
<span<?php echo $prospecto->ic_stProspecto->ViewAttributes() ?>>
<?php echo $prospecto->ic_stProspecto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_prospecto_ds_observacoes"><?php echo $prospecto->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ds_observacoes->CellAttributes() ?>>
<span id="el_prospecto_ds_observacoes" class="control-group">
<span<?php echo $prospecto->ds_observacoes->ViewAttributes() ?>>
<?php echo $prospecto->ds_observacoes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->ic_ativo->Visible) { // ic_ativo ?>
	<tr id="r_ic_ativo">
		<td><span id="elh_prospecto_ic_ativo"><?php echo $prospecto->ic_ativo->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ic_ativo->CellAttributes() ?>>
<span id="el_prospecto_ic_ativo" class="control-group">
<span<?php echo $prospecto->ic_ativo->ViewAttributes() ?>>
<?php echo $prospecto->ic_ativo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_prospecto2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_prospectoview2" class="table table-bordered table-striped">
<?php if ($prospecto->nu_alternativaAlinhamento->Visible) { // nu_alternativaAlinhamento ?>
	<tr id="r_nu_alternativaAlinhamento">
		<td><span id="elh_prospecto_nu_alternativaAlinhamento"><?php echo $prospecto->nu_alternativaAlinhamento->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_alternativaAlinhamento->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaAlinhamento" class="control-group">
<span<?php echo $prospecto->nu_alternativaAlinhamento->ViewAttributes() ?>>
<?php echo $prospecto->nu_alternativaAlinhamento->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaAbrangencia->Visible) { // nu_alternativaAbrangencia ?>
	<tr id="r_nu_alternativaAbrangencia">
		<td><span id="elh_prospecto_nu_alternativaAbrangencia"><?php echo $prospecto->nu_alternativaAbrangencia->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_alternativaAbrangencia->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaAbrangencia" class="control-group">
<span<?php echo $prospecto->nu_alternativaAbrangencia->ViewAttributes() ?>>
<?php echo $prospecto->nu_alternativaAbrangencia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaUrgencia->Visible) { // nu_alternativaUrgencia ?>
	<tr id="r_nu_alternativaUrgencia">
		<td><span id="elh_prospecto_nu_alternativaUrgencia"><?php echo $prospecto->nu_alternativaUrgencia->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_alternativaUrgencia->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaUrgencia" class="control-group">
<span<?php echo $prospecto->nu_alternativaUrgencia->ViewAttributes() ?>>
<?php echo $prospecto->nu_alternativaUrgencia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaTmpEstimado->Visible) { // nu_alternativaTmpEstimado ?>
	<tr id="r_nu_alternativaTmpEstimado">
		<td><span id="elh_prospecto_nu_alternativaTmpEstimado"><?php echo $prospecto->nu_alternativaTmpEstimado->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_alternativaTmpEstimado->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaTmpEstimado" class="control-group">
<span<?php echo $prospecto->nu_alternativaTmpEstimado->ViewAttributes() ?>>
<?php echo $prospecto->nu_alternativaTmpEstimado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->nu_alternativaTmpFila->Visible) { // nu_alternativaTmpFila ?>
	<tr id="r_nu_alternativaTmpFila">
		<td><span id="elh_prospecto_nu_alternativaTmpFila"><?php echo $prospecto->nu_alternativaTmpFila->FldCaption() ?></span></td>
		<td<?php echo $prospecto->nu_alternativaTmpFila->CellAttributes() ?>>
<span id="el_prospecto_nu_alternativaTmpFila" class="control-group">
<span<?php echo $prospecto->nu_alternativaTmpFila->ViewAttributes() ?>>
<?php echo $prospecto->nu_alternativaTmpFila->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($prospecto->ic_implicacaoLegal->Visible) { // ic_implicacaoLegal ?>
	<tr id="r_ic_implicacaoLegal">
		<td><span id="elh_prospecto_ic_implicacaoLegal"><?php echo $prospecto->ic_implicacaoLegal->FldCaption() ?></span></td>
		<td<?php echo $prospecto->ic_implicacaoLegal->CellAttributes() ?>>
<span id="el_prospecto_ic_implicacaoLegal" class="control-group">
<span<?php echo $prospecto->ic_implicacaoLegal->ViewAttributes() ?>>
<?php echo $prospecto->ic_implicacaoLegal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
<?php if ($prospecto->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
<?php if ($prospecto->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($prospecto_view->Pager)) $prospecto_view->Pager = new cNumericPager($prospecto_view->StartRec, $prospecto_view->DisplayRecs, $prospecto_view->TotalRecs, $prospecto_view->RecRange) ?>
<?php if ($prospecto_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($prospecto_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $prospecto_view->PageUrl() ?>start=<?php echo $prospecto_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($prospecto_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $prospecto_view->PageUrl() ?>start=<?php echo $prospecto_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($prospecto_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $prospecto_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($prospecto_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $prospecto_view->PageUrl() ?>start=<?php echo $prospecto_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($prospecto_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $prospecto_view->PageUrl() ?>start=<?php echo $prospecto_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
	if (in_array("prospecto_itempdti", explode(",", $prospecto->getCurrentDetailTable())) && $prospecto_itempdti->DetailView) {
?>
<?php include_once "prospecto_itempdtigrid.php" ?>
<?php } ?>
<?php
	if (in_array("prospectoocorrencias", explode(",", $prospecto->getCurrentDetailTable())) && $prospectoocorrencias->DetailView) {
?>
<?php include_once "prospectoocorrenciasgrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fprospectoview.Init();
</script>
<?php
$prospecto_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($prospecto->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$prospecto_view->Page_Terminate();
?>
