<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "projetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "projeto_centrocustogridcls.php" ?>
<?php include_once "riscoprojetogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$projeto_view = NULL; // Initialize page object first

class cprojeto_view extends cprojeto {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'projeto';

	// Page object name
	var $PageObjName = 'projeto_view';

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

		// Table object (projeto)
		if (!isset($GLOBALS["projeto"])) {
			$GLOBALS["projeto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["projeto"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_projeto"] <> "") {
			$this->RecKey["nu_projeto"] = $_GET["nu_projeto"];
			$KeyUrl .= "&nu_projeto=" . urlencode($this->RecKey["nu_projeto"]);
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
			define("EW_TABLE_NAME", 'projeto', TRUE);

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
			$this->Page_Terminate("projetolist.php");
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
		if (@$_GET["nu_projeto"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_projeto"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_projeto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_projeto"] <> "") {
				$this->nu_projeto->setQueryStringValue($_GET["nu_projeto"]);
				$this->RecKey["nu_projeto"] = $this->nu_projeto->QueryStringValue;
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
						$this->Page_Terminate("projetolist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_projeto->CurrentValue) == strval($this->Recordset->fields('nu_projeto'))) {
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
						$sReturnUrl = "projetolist.php"; // No matching record, return to list
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
			$sReturnUrl = "projetolist.php"; // Not page request, return to list
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

		// Detail table 'projeto_centrocusto'
		$body = $Language->TablePhrase("projeto_centrocusto", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("projeto_centrocustolist.php?" . EW_TABLE_SHOW_MASTER . "=projeto&nu_projeto=" . strval($this->nu_projeto->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_projeto_centrocusto");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'projeto_centrocusto');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "projeto_centrocusto";
		}

		// Detail table 'riscoprojeto'
		$body = $Language->TablePhrase("riscoprojeto", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("riscoprojetolist.php?" . EW_TABLE_SHOW_MASTER . "=projeto&nu_projeto=" . strval($this->nu_projeto->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_riscoprojeto");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'riscoprojeto');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "riscoprojeto";
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
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		if (array_key_exists('EV__nu_contrato', $rs->fields)) {
			$this->nu_contrato->VirtualValue = $rs->fields('EV__nu_contrato'); // Set up virtual field value
		} else {
			$this->nu_contrato->VirtualValue = ""; // Clear value
		}
		$this->nu_itemContrato->setDbValue($rs->fields('nu_itemContrato'));
		if (array_key_exists('EV__nu_itemContrato', $rs->fields)) {
			$this->nu_itemContrato->VirtualValue = $rs->fields('EV__nu_itemContrato'); // Set up virtual field value
		} else {
			$this->nu_itemContrato->VirtualValue = ""; // Clear value
		}
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		$this->nu_tpProjeto->setDbValue($rs->fields('nu_tpProjeto'));
		$this->nu_projetoInteg->setDbValue($rs->fields('nu_projetoInteg'));
		$this->no_projeto->setDbValue($rs->fields('no_projeto'));
		$this->id_tarefaTpProj->setDbValue($rs->fields('id_tarefaTpProj'));
		$this->ic_complexProjeto->setDbValue($rs->fields('ic_complexProjeto'));
		$this->ic_passivelContPf->setDbValue($rs->fields('ic_passivelContPf'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->nu_itemContrato->DbValue = $row['nu_itemContrato'];
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->nu_tpProjeto->DbValue = $row['nu_tpProjeto'];
		$this->nu_projetoInteg->DbValue = $row['nu_projetoInteg'];
		$this->no_projeto->DbValue = $row['no_projeto'];
		$this->id_tarefaTpProj->DbValue = $row['id_tarefaTpProj'];
		$this->ic_complexProjeto->DbValue = $row['ic_complexProjeto'];
		$this->ic_passivelContPf->DbValue = $row['ic_passivelContPf'];
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
		// nu_projeto
		// nu_contrato
		// nu_itemContrato
		// nu_prospecto
		// nu_tpProjeto
		// nu_projetoInteg
		// no_projeto
		// id_tarefaTpProj
		// ic_complexProjeto
		// ic_passivelContPf

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projeto
			$this->nu_projeto->ViewValue = $this->nu_projeto->CurrentValue;
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_contrato
			if ($this->nu_contrato->VirtualValue <> "") {
				$this->nu_contrato->ViewValue = $this->nu_contrato->VirtualValue;
			} else {
			if (strval($this->nu_contrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_contrato]" . ew_SearchString("=", $this->nu_contrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_contrato], [nu_contrato] AS [DispFld], [no_contrato] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_contrato] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_contrato->ViewValue = $rswrk->fields('DispFld');
					$this->nu_contrato->ViewValue .= ew_ValueSeparator(1,$this->nu_contrato) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_contrato->ViewValue = $this->nu_contrato->CurrentValue;
				}
			} else {
				$this->nu_contrato->ViewValue = NULL;
			}
			}
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemContrato
			if ($this->nu_itemContrato->VirtualValue <> "") {
				$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->VirtualValue;
			} else {
			if (strval($this->nu_itemContrato->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContrato->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemContrato->ViewValue = $rswrk->fields('DispFld');
					$this->nu_itemContrato->ViewValue .= ew_ValueSeparator(1,$this->nu_itemContrato) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_itemContrato->ViewValue = $this->nu_itemContrato->CurrentValue;
				}
			} else {
				$this->nu_itemContrato->ViewValue = NULL;
			}
			}
			$this->nu_itemContrato->ViewCustomAttributes = "";

			// nu_prospecto
			if (strval($this->nu_prospecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_prospecto]" . ew_SearchString("=", $this->nu_prospecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_prospecto] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_prospecto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_prospecto->ViewValue = $this->nu_prospecto->CurrentValue;
				}
			} else {
				$this->nu_prospecto->ViewValue = NULL;
			}
			$this->nu_prospecto->ViewCustomAttributes = "";

			// nu_tpProjeto
			if (strval($this->nu_tpProjeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpProjeto]" . ew_SearchString("=", $this->nu_tpProjeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_tpProjeto], [no_tpProjeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpprojeto]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S' AND ([ic_tpProjDem]='P' OR [ic_tpProjDem]='D')";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpProjeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpProjeto] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpProjeto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpProjeto->ViewValue = $this->nu_tpProjeto->CurrentValue;
				}
			} else {
				$this->nu_tpProjeto->ViewValue = NULL;
			}
			$this->nu_tpProjeto->ViewCustomAttributes = "";

			// nu_projetoInteg
			if (strval($this->nu_projetoInteg->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->nu_projetoInteg->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_projects]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projetoInteg, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [created_on] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_projetoInteg->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_projetoInteg->ViewValue = $this->nu_projetoInteg->CurrentValue;
				}
			} else {
				$this->nu_projetoInteg->ViewValue = NULL;
			}
			$this->nu_projetoInteg->ViewCustomAttributes = "";

			// no_projeto
			$this->no_projeto->ViewValue = $this->no_projeto->CurrentValue;
			$this->no_projeto->ViewCustomAttributes = "";

			// id_tarefaTpProj
			$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
			if (strval($this->id_tarefaTpProj->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->id_tarefaTpProj->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [subject] AS [DispFld], [id] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tbrdm_issues]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->id_tarefaTpProj, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [id] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->id_tarefaTpProj->ViewValue = $rswrk->fields('DispFld');
					$this->id_tarefaTpProj->ViewValue .= ew_ValueSeparator(1,$this->id_tarefaTpProj) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->id_tarefaTpProj->ViewValue = $this->id_tarefaTpProj->CurrentValue;
				}
			} else {
				$this->id_tarefaTpProj->ViewValue = NULL;
			}
			$this->id_tarefaTpProj->ViewCustomAttributes = "";

			// ic_complexProjeto
			if (strval($this->ic_complexProjeto->CurrentValue) <> "") {
				switch ($this->ic_complexProjeto->CurrentValue) {
					case $this->ic_complexProjeto->FldTagValue(1):
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(1) <> "" ? $this->ic_complexProjeto->FldTagCaption(1) : $this->ic_complexProjeto->CurrentValue;
						break;
					case $this->ic_complexProjeto->FldTagValue(2):
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(2) <> "" ? $this->ic_complexProjeto->FldTagCaption(2) : $this->ic_complexProjeto->CurrentValue;
						break;
					case $this->ic_complexProjeto->FldTagValue(3):
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->FldTagCaption(3) <> "" ? $this->ic_complexProjeto->FldTagCaption(3) : $this->ic_complexProjeto->CurrentValue;
						break;
					default:
						$this->ic_complexProjeto->ViewValue = $this->ic_complexProjeto->CurrentValue;
				}
			} else {
				$this->ic_complexProjeto->ViewValue = NULL;
			}
			$this->ic_complexProjeto->ViewCustomAttributes = "";

			// ic_passivelContPf
			if (strval($this->ic_passivelContPf->CurrentValue) <> "") {
				switch ($this->ic_passivelContPf->CurrentValue) {
					case $this->ic_passivelContPf->FldTagValue(1):
						$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->FldTagCaption(1) <> "" ? $this->ic_passivelContPf->FldTagCaption(1) : $this->ic_passivelContPf->CurrentValue;
						break;
					case $this->ic_passivelContPf->FldTagValue(2):
						$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->FldTagCaption(2) <> "" ? $this->ic_passivelContPf->FldTagCaption(2) : $this->ic_passivelContPf->CurrentValue;
						break;
					default:
						$this->ic_passivelContPf->ViewValue = $this->ic_passivelContPf->CurrentValue;
				}
			} else {
				$this->ic_passivelContPf->ViewValue = NULL;
			}
			$this->ic_passivelContPf->ViewCustomAttributes = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_contrato
			$this->nu_contrato->LinkCustomAttributes = "";
			$this->nu_contrato->HrefValue = "";
			$this->nu_contrato->TooltipValue = "";

			// nu_itemContrato
			$this->nu_itemContrato->LinkCustomAttributes = "";
			$this->nu_itemContrato->HrefValue = "";
			$this->nu_itemContrato->TooltipValue = "";

			// nu_prospecto
			$this->nu_prospecto->LinkCustomAttributes = "";
			$this->nu_prospecto->HrefValue = "";
			$this->nu_prospecto->TooltipValue = "";

			// nu_tpProjeto
			$this->nu_tpProjeto->LinkCustomAttributes = "";
			$this->nu_tpProjeto->HrefValue = "";
			$this->nu_tpProjeto->TooltipValue = "";

			// nu_projetoInteg
			$this->nu_projetoInteg->LinkCustomAttributes = "";
			$this->nu_projetoInteg->HrefValue = "";
			$this->nu_projetoInteg->TooltipValue = "";

			// no_projeto
			$this->no_projeto->LinkCustomAttributes = "";
			$this->no_projeto->HrefValue = "";
			$this->no_projeto->TooltipValue = "";

			// id_tarefaTpProj
			$this->id_tarefaTpProj->LinkCustomAttributes = "";
			$this->id_tarefaTpProj->HrefValue = "";
			$this->id_tarefaTpProj->TooltipValue = "";

			// ic_complexProjeto
			$this->ic_complexProjeto->LinkCustomAttributes = "";
			$this->ic_complexProjeto->HrefValue = "";
			$this->ic_complexProjeto->TooltipValue = "";

			// ic_passivelContPf
			$this->ic_passivelContPf->LinkCustomAttributes = "";
			$this->ic_passivelContPf->HrefValue = "";
			$this->ic_passivelContPf->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_projeto\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_projeto',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fprojetoview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
			if (in_array("projeto_centrocusto", $DetailTblVar)) {
				if (!isset($GLOBALS["projeto_centrocusto_grid"]))
					$GLOBALS["projeto_centrocusto_grid"] = new cprojeto_centrocusto_grid;
				if ($GLOBALS["projeto_centrocusto_grid"]->DetailView) {
					$GLOBALS["projeto_centrocusto_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["projeto_centrocusto_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["projeto_centrocusto_grid"]->setStartRecordNumber(1);
					$GLOBALS["projeto_centrocusto_grid"]->nu_projeto->FldIsDetailKey = TRUE;
					$GLOBALS["projeto_centrocusto_grid"]->nu_projeto->CurrentValue = $this->nu_projeto->CurrentValue;
					$GLOBALS["projeto_centrocusto_grid"]->nu_projeto->setSessionValue($GLOBALS["projeto_centrocusto_grid"]->nu_projeto->CurrentValue);
				}
			}
			if (in_array("riscoprojeto", $DetailTblVar)) {
				if (!isset($GLOBALS["riscoprojeto_grid"]))
					$GLOBALS["riscoprojeto_grid"] = new criscoprojeto_grid;
				if ($GLOBALS["riscoprojeto_grid"]->DetailView) {
					$GLOBALS["riscoprojeto_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["riscoprojeto_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["riscoprojeto_grid"]->setStartRecordNumber(1);
					$GLOBALS["riscoprojeto_grid"]->nu_projeto->FldIsDetailKey = TRUE;
					$GLOBALS["riscoprojeto_grid"]->nu_projeto->CurrentValue = $this->nu_projeto->CurrentValue;
					$GLOBALS["riscoprojeto_grid"]->nu_projeto->setSessionValue($GLOBALS["riscoprojeto_grid"]->nu_projeto->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "projetolist.php", $this->TableVar);
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
if (!isset($projeto_view)) $projeto_view = new cprojeto_view();

// Page init
$projeto_view->Page_Init();

// Page main
$projeto_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$projeto_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($projeto->Export == "") { ?>
<script type="text/javascript">

// Page object
var projeto_view = new ew_Page("projeto_view");
projeto_view.PageID = "view"; // Page ID
var EW_PAGE_ID = projeto_view.PageID; // For backward compatibility

// Form object
var fprojetoview = new ew_Form("fprojetoview");

// Form_CustomValidate event
fprojetoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprojetoview.ValidateRequired = true;
<?php } else { ?>
fprojetoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprojetoview.Lists["x_nu_contrato"] = {"LinkField":"x_nu_contrato","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_contrato","x_no_contrato","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoview.Lists["x_nu_itemContrato"] = {"LinkField":"x_nu_itemContratado","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_itemOc","x_no_itemContratado","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoview.Lists["x_nu_prospecto"] = {"LinkField":"x_nu_prospecto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_prospecto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoview.Lists["x_nu_tpProjeto"] = {"LinkField":"x_nu_tpProjeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpProjeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoview.Lists["x_nu_projetoInteg"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fprojetoview.Lists["x_id_tarefaTpProj"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_subject","x_id","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($projeto->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($projeto->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $projeto_view->ExportOptions->Render("body") ?>
<?php if (!$projeto_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($projeto_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $projeto_view->ShowPageHeader(); ?>
<?php
$projeto_view->ShowMessage();
?>
<form name="fprojetoview" id="fprojetoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="projeto">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_projetoview" class="table table-bordered table-striped">
<?php if ($projeto->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_projeto_nu_projeto"><?php echo $projeto->nu_projeto->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_projeto->CellAttributes() ?>>
<span id="el_projeto_nu_projeto" class="control-group">
<span<?php echo $projeto->nu_projeto->ViewAttributes() ?>>
<?php echo $projeto->nu_projeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_contrato->Visible) { // nu_contrato ?>
	<tr id="r_nu_contrato">
		<td><span id="elh_projeto_nu_contrato"><?php echo $projeto->nu_contrato->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_contrato->CellAttributes() ?>>
<span id="el_projeto_nu_contrato" class="control-group">
<span<?php echo $projeto->nu_contrato->ViewAttributes() ?>>
<?php echo $projeto->nu_contrato->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_itemContrato->Visible) { // nu_itemContrato ?>
	<tr id="r_nu_itemContrato">
		<td><span id="elh_projeto_nu_itemContrato"><?php echo $projeto->nu_itemContrato->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_itemContrato->CellAttributes() ?>>
<span id="el_projeto_nu_itemContrato" class="control-group">
<span<?php echo $projeto->nu_itemContrato->ViewAttributes() ?>>
<?php echo $projeto->nu_itemContrato->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_prospecto->Visible) { // nu_prospecto ?>
	<tr id="r_nu_prospecto">
		<td><span id="elh_projeto_nu_prospecto"><?php echo $projeto->nu_prospecto->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_prospecto->CellAttributes() ?>>
<span id="el_projeto_nu_prospecto" class="control-group">
<span<?php echo $projeto->nu_prospecto->ViewAttributes() ?>>
<?php echo $projeto->nu_prospecto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_tpProjeto->Visible) { // nu_tpProjeto ?>
	<tr id="r_nu_tpProjeto">
		<td><span id="elh_projeto_nu_tpProjeto"><?php echo $projeto->nu_tpProjeto->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_tpProjeto->CellAttributes() ?>>
<span id="el_projeto_nu_tpProjeto" class="control-group">
<span<?php echo $projeto->nu_tpProjeto->ViewAttributes() ?>>
<?php echo $projeto->nu_tpProjeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($projeto->nu_projetoInteg->Visible) { // nu_projetoInteg ?>
	<tr id="r_nu_projetoInteg">
		<td><span id="elh_projeto_nu_projetoInteg"><?php echo $projeto->nu_projetoInteg->FldCaption() ?></span></td>
		<td<?php echo $projeto->nu_projetoInteg->CellAttributes() ?>>
<span id="el_projeto_nu_projetoInteg" class="control-group">
<span<?php echo $projeto->nu_projetoInteg->ViewAttributes() ?>>
<?php echo $projeto->nu_projetoInteg->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($projeto->no_projeto->Visible) { // no_projeto ?>
	<tr id="r_no_projeto">
		<td><span id="elh_projeto_no_projeto"><?php echo $projeto->no_projeto->FldCaption() ?></span></td>
		<td<?php echo $projeto->no_projeto->CellAttributes() ?>>
<span id="el_projeto_no_projeto" class="control-group">
<span<?php echo $projeto->no_projeto->ViewAttributes() ?>>
<?php echo $projeto->no_projeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($projeto->id_tarefaTpProj->Visible) { // id_tarefaTpProj ?>
	<tr id="r_id_tarefaTpProj">
		<td><span id="elh_projeto_id_tarefaTpProj"><?php echo $projeto->id_tarefaTpProj->FldCaption() ?></span></td>
		<td<?php echo $projeto->id_tarefaTpProj->CellAttributes() ?>>
<span id="el_projeto_id_tarefaTpProj" class="control-group">
<span<?php echo $projeto->id_tarefaTpProj->ViewAttributes() ?>>
<?php echo $projeto->id_tarefaTpProj->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($projeto->ic_complexProjeto->Visible) { // ic_complexProjeto ?>
	<tr id="r_ic_complexProjeto">
		<td><span id="elh_projeto_ic_complexProjeto"><?php echo $projeto->ic_complexProjeto->FldCaption() ?></span></td>
		<td<?php echo $projeto->ic_complexProjeto->CellAttributes() ?>>
<span id="el_projeto_ic_complexProjeto" class="control-group">
<span<?php echo $projeto->ic_complexProjeto->ViewAttributes() ?>>
<?php echo $projeto->ic_complexProjeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($projeto->ic_passivelContPf->Visible) { // ic_passivelContPf ?>
	<tr id="r_ic_passivelContPf">
		<td><span id="elh_projeto_ic_passivelContPf"><?php echo $projeto->ic_passivelContPf->FldCaption() ?></span></td>
		<td<?php echo $projeto->ic_passivelContPf->CellAttributes() ?>>
<span id="el_projeto_ic_passivelContPf" class="control-group">
<span<?php echo $projeto->ic_passivelContPf->ViewAttributes() ?>>
<?php echo $projeto->ic_passivelContPf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($projeto->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($projeto_view->Pager)) $projeto_view->Pager = new cNumericPager($projeto_view->StartRec, $projeto_view->DisplayRecs, $projeto_view->TotalRecs, $projeto_view->RecRange) ?>
<?php if ($projeto_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($projeto_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $projeto_view->PageUrl() ?>start=<?php echo $projeto_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($projeto_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $projeto_view->PageUrl() ?>start=<?php echo $projeto_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($projeto_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $projeto_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($projeto_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $projeto_view->PageUrl() ?>start=<?php echo $projeto_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($projeto_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $projeto_view->PageUrl() ?>start=<?php echo $projeto_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
	if (in_array("projeto_centrocusto", explode(",", $projeto->getCurrentDetailTable())) && $projeto_centrocusto->DetailView) {
?>
<?php include_once "projeto_centrocustogrid.php" ?>
<?php } ?>
<?php
	if (in_array("riscoprojeto", explode(",", $projeto->getCurrentDetailTable())) && $riscoprojeto->DetailView) {
?>
<?php include_once "riscoprojetogrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fprojetoview.Init();
</script>
<?php
$projeto_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($projeto->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$projeto_view->Page_Terminate();
?>
