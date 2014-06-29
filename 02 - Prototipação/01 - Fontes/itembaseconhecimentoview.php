<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "itembaseconhecimentoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$itembaseconhecimento_view = NULL; // Initialize page object first

class citembaseconhecimento_view extends citembaseconhecimento {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'itembaseconhecimento';

	// Page object name
	var $PageObjName = 'itembaseconhecimento_view';

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

		// Table object (itembaseconhecimento)
		if (!isset($GLOBALS["itembaseconhecimento"])) {
			$GLOBALS["itembaseconhecimento"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["itembaseconhecimento"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_item"] <> "") {
			$this->RecKey["nu_item"] = $_GET["nu_item"];
			$KeyUrl .= "&nu_item=" . urlencode($this->RecKey["nu_item"]);
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
			define("EW_TABLE_NAME", 'itembaseconhecimento', TRUE);

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
			$this->Page_Terminate("itembaseconhecimentolist.php");
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
		if (@$_GET["nu_item"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_item"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_item->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_item"] <> "") {
				$this->nu_item->setQueryStringValue($_GET["nu_item"]);
				$this->RecKey["nu_item"] = $this->nu_item->QueryStringValue;
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
						$this->Page_Terminate("itembaseconhecimentolist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_item->CurrentValue) == strval($this->Recordset->fields('nu_item'))) {
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
						$sReturnUrl = "itembaseconhecimentolist.php"; // No matching record, return to list
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
			$sReturnUrl = "itembaseconhecimentolist.php"; // Not page request, return to list
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
		$this->nu_item->setDbValue($rs->fields('nu_item'));
		$this->no_tituloItem->setDbValue($rs->fields('no_tituloItem'));
		$this->ic_tpItem->setDbValue($rs->fields('ic_tpItem'));
		$this->ds_item->setDbValue($rs->fields('ds_item'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->ds_acoes->setDbValue($rs->fields('ds_acoes'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		if (array_key_exists('EV__nu_sistema', $rs->fields)) {
			$this->nu_sistema->VirtualValue = $rs->fields('EV__nu_sistema'); // Set up virtual field value
		} else {
			$this->nu_sistema->VirtualValue = ""; // Clear value
		}
		$this->nu_modulo->setDbValue($rs->fields('nu_modulo'));
		if (array_key_exists('EV__nu_modulo', $rs->fields)) {
			$this->nu_modulo->VirtualValue = $rs->fields('EV__nu_modulo'); // Set up virtual field value
		} else {
			$this->nu_modulo->VirtualValue = ""; // Clear value
		}
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		if (array_key_exists('EV__nu_uc', $rs->fields)) {
			$this->nu_uc->VirtualValue = $rs->fields('EV__nu_uc'); // Set up virtual field value
		} else {
			$this->nu_uc->VirtualValue = ""; // Clear value
		}
		$this->nu_processoCobit->setDbValue($rs->fields('nu_processoCobit'));
		$this->nu_prospecto->setDbValue($rs->fields('nu_prospecto'));
		if (array_key_exists('EV__nu_prospecto', $rs->fields)) {
			$this->nu_prospecto->VirtualValue = $rs->fields('EV__nu_prospecto'); // Set up virtual field value
		} else {
			$this->nu_prospecto->VirtualValue = ""; // Clear value
		}
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		if (array_key_exists('EV__nu_projeto', $rs->fields)) {
			$this->nu_projeto->VirtualValue = $rs->fields('EV__nu_projeto'); // Set up virtual field value
		} else {
			$this->nu_projeto->VirtualValue = ""; // Clear value
		}
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_item->DbValue = $row['nu_item'];
		$this->no_tituloItem->DbValue = $row['no_tituloItem'];
		$this->ic_tpItem->DbValue = $row['ic_tpItem'];
		$this->ds_item->DbValue = $row['ds_item'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->ds_acoes->DbValue = $row['ds_acoes'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioAlt->DbValue = $row['nu_usuarioAlt'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->nu_modulo->DbValue = $row['nu_modulo'];
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->nu_processoCobit->DbValue = $row['nu_processoCobit'];
		$this->nu_prospecto->DbValue = $row['nu_prospecto'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
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
		// nu_item
		// no_tituloItem
		// ic_tpItem
		// ds_item
		// ic_situacao
		// ds_acoes
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao
		// nu_sistema
		// nu_modulo
		// nu_uc
		// nu_processoCobit
		// nu_prospecto
		// nu_projeto

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_item
			$this->nu_item->ViewValue = $this->nu_item->CurrentValue;
			$this->nu_item->ViewCustomAttributes = "";

			// no_tituloItem
			$this->no_tituloItem->ViewValue = $this->no_tituloItem->CurrentValue;
			$this->no_tituloItem->ViewCustomAttributes = "";

			// ic_tpItem
			if (strval($this->ic_tpItem->CurrentValue) <> "") {
				switch ($this->ic_tpItem->CurrentValue) {
					case $this->ic_tpItem->FldTagValue(1):
						$this->ic_tpItem->ViewValue = $this->ic_tpItem->FldTagCaption(1) <> "" ? $this->ic_tpItem->FldTagCaption(1) : $this->ic_tpItem->CurrentValue;
						break;
					case $this->ic_tpItem->FldTagValue(2):
						$this->ic_tpItem->ViewValue = $this->ic_tpItem->FldTagCaption(2) <> "" ? $this->ic_tpItem->FldTagCaption(2) : $this->ic_tpItem->CurrentValue;
						break;
					case $this->ic_tpItem->FldTagValue(3):
						$this->ic_tpItem->ViewValue = $this->ic_tpItem->FldTagCaption(3) <> "" ? $this->ic_tpItem->FldTagCaption(3) : $this->ic_tpItem->CurrentValue;
						break;
					default:
						$this->ic_tpItem->ViewValue = $this->ic_tpItem->CurrentValue;
				}
			} else {
				$this->ic_tpItem->ViewValue = NULL;
			}
			$this->ic_tpItem->ViewCustomAttributes = "";

			// ds_item
			$this->ds_item->ViewValue = $this->ds_item->CurrentValue;
			$this->ds_item->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(3):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(3) <> "" ? $this->ic_situacao->FldTagCaption(3) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(4):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(4) <> "" ? $this->ic_situacao->FldTagCaption(4) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(5):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(5) <> "" ? $this->ic_situacao->FldTagCaption(5) : $this->ic_situacao->CurrentValue;
						break;
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// ds_acoes
			$this->ds_acoes->ViewValue = $this->ds_acoes->CurrentValue;
			$this->ds_acoes->ViewCustomAttributes = "";

			// nu_usuarioInc
			if (strval($this->nu_usuarioInc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioInc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioInc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioInc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioInc->ViewValue = $this->nu_usuarioInc->CurrentValue;
				}
			} else {
				$this->nu_usuarioInc->ViewValue = NULL;
			}
			$this->nu_usuarioInc->ViewCustomAttributes = "";

			// dh_inclusao
			$this->dh_inclusao->ViewValue = $this->dh_inclusao->CurrentValue;
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 9);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioAlt
			if (strval($this->nu_usuarioAlt->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAlt->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAlt, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAlt->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
				}
			} else {
				$this->nu_usuarioAlt->ViewValue = NULL;
			}
			$this->nu_usuarioAlt->ViewCustomAttributes = "";

			// dh_alteracao
			$this->dh_alteracao->ViewValue = $this->dh_alteracao->CurrentValue;
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 9);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_sistema
			if ($this->nu_sistema->VirtualValue <> "") {
				$this->nu_sistema->ViewValue = $this->nu_sistema->VirtualValue;
			} else {
			if (strval($this->nu_sistema->CurrentValue) <> "") {
				$sFilterWrk = "[nu_sistema]" . ew_SearchString("=", $this->nu_sistema->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_sistema], [co_alternativo] AS [DispFld], [no_sistema] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[sistema]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_sistema, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_alternativo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_sistema->ViewValue = $rswrk->fields('DispFld');
					$this->nu_sistema->ViewValue .= ew_ValueSeparator(1,$this->nu_sistema) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_sistema->ViewValue = $this->nu_sistema->CurrentValue;
				}
			} else {
				$this->nu_sistema->ViewValue = NULL;
			}
			}
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_modulo
			if ($this->nu_modulo->VirtualValue <> "") {
				$this->nu_modulo->ViewValue = $this->nu_modulo->VirtualValue;
			} else {
			if (strval($this->nu_modulo->CurrentValue) <> "") {
				$sFilterWrk = "[nu_modulo]" . ew_SearchString("=", $this->nu_modulo->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_modulo], [no_modulo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[modulo]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_modulo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_modulo] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_modulo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_modulo->ViewValue = $this->nu_modulo->CurrentValue;
				}
			} else {
				$this->nu_modulo->ViewValue = NULL;
			}
			}
			$this->nu_modulo->ViewCustomAttributes = "";

			// nu_uc
			if ($this->nu_uc->VirtualValue <> "") {
				$this->nu_uc->ViewValue = $this->nu_uc->VirtualValue;
			} else {
			if (strval($this->nu_uc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_uc]" . ew_SearchString("=", $this->nu_uc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_uc], [co_alternativo] AS [DispFld], [no_uc] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[uc]";
			$sWhereWrk = "";
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
			}
			$this->nu_uc->ViewCustomAttributes = "";

			// nu_processoCobit
			if (strval($this->nu_processoCobit->CurrentValue) <> "") {
				$sFilterWrk = "[nu_processo]" . ew_SearchString("=", $this->nu_processoCobit->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_processo], [co_alternativo] AS [DispFld], [no_processo] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[processocobit5]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_processoCobit, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_processoCobit->ViewValue = $rswrk->fields('DispFld');
					$this->nu_processoCobit->ViewValue .= ew_ValueSeparator(1,$this->nu_processoCobit) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_processoCobit->ViewValue = $this->nu_processoCobit->CurrentValue;
				}
			} else {
				$this->nu_processoCobit->ViewValue = NULL;
			}
			$this->nu_processoCobit->ViewCustomAttributes = "";

			// nu_prospecto
			if ($this->nu_prospecto->VirtualValue <> "") {
				$this->nu_prospecto->ViewValue = $this->nu_prospecto->VirtualValue;
			} else {
			if (strval($this->nu_prospecto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_prospecto]" . ew_SearchString("=", $this->nu_prospecto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_prospecto], [no_prospecto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[prospecto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_prospecto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_prospecto] ASC";
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
			}
			$this->nu_prospecto->ViewCustomAttributes = "";

			// nu_projeto
			if ($this->nu_projeto->VirtualValue <> "") {
				$this->nu_projeto->ViewValue = $this->nu_projeto->VirtualValue;
			} else {
			if (strval($this->nu_projeto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_projeto]" . ew_SearchString("=", $this->nu_projeto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_projeto] ASC";
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

			// nu_item
			$this->nu_item->LinkCustomAttributes = "";
			$this->nu_item->HrefValue = "";
			$this->nu_item->TooltipValue = "";

			// no_tituloItem
			$this->no_tituloItem->LinkCustomAttributes = "";
			$this->no_tituloItem->HrefValue = "";
			$this->no_tituloItem->TooltipValue = "";

			// ic_tpItem
			$this->ic_tpItem->LinkCustomAttributes = "";
			$this->ic_tpItem->HrefValue = "";
			$this->ic_tpItem->TooltipValue = "";

			// ds_item
			$this->ds_item->LinkCustomAttributes = "";
			$this->ds_item->HrefValue = "";
			$this->ds_item->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

			// ds_acoes
			$this->ds_acoes->LinkCustomAttributes = "";
			$this->ds_acoes->HrefValue = "";
			$this->ds_acoes->TooltipValue = "";

			// nu_usuarioInc
			$this->nu_usuarioInc->LinkCustomAttributes = "";
			$this->nu_usuarioInc->HrefValue = "";
			$this->nu_usuarioInc->TooltipValue = "";

			// dh_inclusao
			$this->dh_inclusao->LinkCustomAttributes = "";
			$this->dh_inclusao->HrefValue = "";
			$this->dh_inclusao->TooltipValue = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->LinkCustomAttributes = "";
			$this->nu_usuarioAlt->HrefValue = "";
			$this->nu_usuarioAlt->TooltipValue = "";

			// dh_alteracao
			$this->dh_alteracao->LinkCustomAttributes = "";
			$this->dh_alteracao->HrefValue = "";
			$this->dh_alteracao->TooltipValue = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_modulo
			$this->nu_modulo->LinkCustomAttributes = "";
			$this->nu_modulo->HrefValue = "";
			$this->nu_modulo->TooltipValue = "";

			// nu_uc
			$this->nu_uc->LinkCustomAttributes = "";
			$this->nu_uc->HrefValue = "";
			$this->nu_uc->TooltipValue = "";

			// nu_processoCobit
			$this->nu_processoCobit->LinkCustomAttributes = "";
			$this->nu_processoCobit->HrefValue = "";
			$this->nu_processoCobit->TooltipValue = "";

			// nu_prospecto
			$this->nu_prospecto->LinkCustomAttributes = "";
			$this->nu_prospecto->HrefValue = "";
			$this->nu_prospecto->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_itembaseconhecimento\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_itembaseconhecimento',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fitembaseconhecimentoview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "itembaseconhecimentolist.php", $this->TableVar);
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
if (!isset($itembaseconhecimento_view)) $itembaseconhecimento_view = new citembaseconhecimento_view();

// Page init
$itembaseconhecimento_view->Page_Init();

// Page main
$itembaseconhecimento_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$itembaseconhecimento_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($itembaseconhecimento->Export == "") { ?>
<script type="text/javascript">

// Page object
var itembaseconhecimento_view = new ew_Page("itembaseconhecimento_view");
itembaseconhecimento_view.PageID = "view"; // Page ID
var EW_PAGE_ID = itembaseconhecimento_view.PageID; // For backward compatibility

// Form object
var fitembaseconhecimentoview = new ew_Form("fitembaseconhecimentoview");

// Form_CustomValidate event
fitembaseconhecimentoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fitembaseconhecimentoview.ValidateRequired = true;
<?php } else { ?>
fitembaseconhecimentoview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fitembaseconhecimentoview.MultiPage = new ew_MultiPage("fitembaseconhecimentoview",
	[["x_nu_item",1],["x_no_tituloItem",1],["x_ic_tpItem",1],["x_ds_item",1],["x_ic_situacao",1],["x_ds_acoes",1],["x_nu_sistema",4],["x_nu_modulo",4],["x_nu_uc",4],["x_nu_processoCobit",2],["x_nu_prospecto",3],["x_nu_projeto",3]]
);

// Dynamic selection lists
fitembaseconhecimentoview.Lists["x_nu_usuarioInc"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoview.Lists["x_nu_usuarioAlt"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoview.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoview.Lists["x_nu_modulo"] = {"LinkField":"x_nu_modulo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_modulo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoview.Lists["x_nu_uc"] = {"LinkField":"x_nu_uc","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_uc","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoview.Lists["x_nu_processoCobit"] = {"LinkField":"x_nu_processo","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_processo","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoview.Lists["x_nu_prospecto"] = {"LinkField":"x_nu_prospecto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_prospecto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fitembaseconhecimentoview.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($itembaseconhecimento->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($itembaseconhecimento->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $itembaseconhecimento_view->ExportOptions->Render("body") ?>
<?php if (!$itembaseconhecimento_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($itembaseconhecimento_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $itembaseconhecimento_view->ShowPageHeader(); ?>
<?php
$itembaseconhecimento_view->ShowMessage();
?>
<form name="fitembaseconhecimentoview" id="fitembaseconhecimentoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="itembaseconhecimento">
<?php if ($itembaseconhecimento->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="itembaseconhecimento_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_itembaseconhecimento1" data-toggle="tab"><?php echo $itembaseconhecimento->PageCaption(1) ?></a></li>
		<li><a href="#tab_itembaseconhecimento2" data-toggle="tab"><?php echo $itembaseconhecimento->PageCaption(2) ?></a></li>
		<li><a href="#tab_itembaseconhecimento3" data-toggle="tab"><?php echo $itembaseconhecimento->PageCaption(3) ?></a></li>
		<li><a href="#tab_itembaseconhecimento4" data-toggle="tab"><?php echo $itembaseconhecimento->PageCaption(4) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
		<div class="tab-pane active" id="tab_itembaseconhecimento1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_itembaseconhecimentoview1" class="table table-bordered table-striped">
<?php if ($itembaseconhecimento->nu_item->Visible) { // nu_item ?>
	<tr id="r_nu_item">
		<td><span id="elh_itembaseconhecimento_nu_item"><?php echo $itembaseconhecimento->nu_item->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_item->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_item" class="control-group">
<span<?php echo $itembaseconhecimento->nu_item->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->nu_item->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->no_tituloItem->Visible) { // no_tituloItem ?>
	<tr id="r_no_tituloItem">
		<td><span id="elh_itembaseconhecimento_no_tituloItem"><?php echo $itembaseconhecimento->no_tituloItem->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->no_tituloItem->CellAttributes() ?>>
<span id="el_itembaseconhecimento_no_tituloItem" class="control-group">
<span<?php echo $itembaseconhecimento->no_tituloItem->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->no_tituloItem->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->ic_tpItem->Visible) { // ic_tpItem ?>
	<tr id="r_ic_tpItem">
		<td><span id="elh_itembaseconhecimento_ic_tpItem"><?php echo $itembaseconhecimento->ic_tpItem->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->ic_tpItem->CellAttributes() ?>>
<span id="el_itembaseconhecimento_ic_tpItem" class="control-group">
<span<?php echo $itembaseconhecimento->ic_tpItem->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->ic_tpItem->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->ds_item->Visible) { // ds_item ?>
	<tr id="r_ds_item">
		<td><span id="elh_itembaseconhecimento_ds_item"><?php echo $itembaseconhecimento->ds_item->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->ds_item->CellAttributes() ?>>
<span id="el_itembaseconhecimento_ds_item" class="control-group">
<span<?php echo $itembaseconhecimento->ds_item->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->ds_item->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_itembaseconhecimento_ic_situacao"><?php echo $itembaseconhecimento->ic_situacao->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->ic_situacao->CellAttributes() ?>>
<span id="el_itembaseconhecimento_ic_situacao" class="control-group">
<span<?php echo $itembaseconhecimento->ic_situacao->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->ic_situacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->ds_acoes->Visible) { // ds_acoes ?>
	<tr id="r_ds_acoes">
		<td><span id="elh_itembaseconhecimento_ds_acoes"><?php echo $itembaseconhecimento->ds_acoes->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->ds_acoes->CellAttributes() ?>>
<span id="el_itembaseconhecimento_ds_acoes" class="control-group">
<span<?php echo $itembaseconhecimento->ds_acoes->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->ds_acoes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->nu_usuarioInc->Visible) { // nu_usuarioInc ?>
	<tr id="r_nu_usuarioInc">
		<td><span id="elh_itembaseconhecimento_nu_usuarioInc"><?php echo $itembaseconhecimento->nu_usuarioInc->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_usuarioInc->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_usuarioInc" class="control-group">
<span<?php echo $itembaseconhecimento->nu_usuarioInc->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->nu_usuarioInc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->dh_inclusao->Visible) { // dh_inclusao ?>
	<tr id="r_dh_inclusao">
		<td><span id="elh_itembaseconhecimento_dh_inclusao"><?php echo $itembaseconhecimento->dh_inclusao->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->dh_inclusao->CellAttributes() ?>>
<span id="el_itembaseconhecimento_dh_inclusao" class="control-group">
<span<?php echo $itembaseconhecimento->dh_inclusao->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->dh_inclusao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->nu_usuarioAlt->Visible) { // nu_usuarioAlt ?>
	<tr id="r_nu_usuarioAlt">
		<td><span id="elh_itembaseconhecimento_nu_usuarioAlt"><?php echo $itembaseconhecimento->nu_usuarioAlt->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_usuarioAlt->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_usuarioAlt" class="control-group">
<span<?php echo $itembaseconhecimento->nu_usuarioAlt->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->nu_usuarioAlt->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->dh_alteracao->Visible) { // dh_alteracao ?>
	<tr id="r_dh_alteracao">
		<td><span id="elh_itembaseconhecimento_dh_alteracao"><?php echo $itembaseconhecimento->dh_alteracao->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->dh_alteracao->CellAttributes() ?>>
<span id="el_itembaseconhecimento_dh_alteracao" class="control-group">
<span<?php echo $itembaseconhecimento->dh_alteracao->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->dh_alteracao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_itembaseconhecimento2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_itembaseconhecimentoview2" class="table table-bordered table-striped">
<?php if ($itembaseconhecimento->nu_processoCobit->Visible) { // nu_processoCobit ?>
	<tr id="r_nu_processoCobit">
		<td><span id="elh_itembaseconhecimento_nu_processoCobit"><?php echo $itembaseconhecimento->nu_processoCobit->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_processoCobit->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_processoCobit" class="control-group">
<span<?php echo $itembaseconhecimento->nu_processoCobit->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->nu_processoCobit->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_itembaseconhecimento3">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_itembaseconhecimentoview3" class="table table-bordered table-striped">
<?php if ($itembaseconhecimento->nu_prospecto->Visible) { // nu_prospecto ?>
	<tr id="r_nu_prospecto">
		<td><span id="elh_itembaseconhecimento_nu_prospecto"><?php echo $itembaseconhecimento->nu_prospecto->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_prospecto->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_prospecto" class="control-group">
<span<?php echo $itembaseconhecimento->nu_prospecto->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->nu_prospecto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_itembaseconhecimento_nu_projeto"><?php echo $itembaseconhecimento->nu_projeto->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_projeto->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_projeto" class="control-group">
<span<?php echo $itembaseconhecimento->nu_projeto->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->nu_projeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_itembaseconhecimento4">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_itembaseconhecimentoview4" class="table table-bordered table-striped">
<?php if ($itembaseconhecimento->nu_sistema->Visible) { // nu_sistema ?>
	<tr id="r_nu_sistema">
		<td><span id="elh_itembaseconhecimento_nu_sistema"><?php echo $itembaseconhecimento->nu_sistema->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_sistema->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_sistema" class="control-group">
<span<?php echo $itembaseconhecimento->nu_sistema->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->nu_sistema->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->nu_modulo->Visible) { // nu_modulo ?>
	<tr id="r_nu_modulo">
		<td><span id="elh_itembaseconhecimento_nu_modulo"><?php echo $itembaseconhecimento->nu_modulo->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_modulo->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_modulo" class="control-group">
<span<?php echo $itembaseconhecimento->nu_modulo->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->nu_modulo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($itembaseconhecimento->nu_uc->Visible) { // nu_uc ?>
	<tr id="r_nu_uc">
		<td><span id="elh_itembaseconhecimento_nu_uc"><?php echo $itembaseconhecimento->nu_uc->FldCaption() ?></span></td>
		<td<?php echo $itembaseconhecimento->nu_uc->CellAttributes() ?>>
<span id="el_itembaseconhecimento_nu_uc" class="control-group">
<span<?php echo $itembaseconhecimento->nu_uc->ViewAttributes() ?>>
<?php echo $itembaseconhecimento->nu_uc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
<?php if ($itembaseconhecimento->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
<?php if ($itembaseconhecimento->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($itembaseconhecimento_view->Pager)) $itembaseconhecimento_view->Pager = new cNumericPager($itembaseconhecimento_view->StartRec, $itembaseconhecimento_view->DisplayRecs, $itembaseconhecimento_view->TotalRecs, $itembaseconhecimento_view->RecRange) ?>
<?php if ($itembaseconhecimento_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($itembaseconhecimento_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $itembaseconhecimento_view->PageUrl() ?>start=<?php echo $itembaseconhecimento_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($itembaseconhecimento_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $itembaseconhecimento_view->PageUrl() ?>start=<?php echo $itembaseconhecimento_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($itembaseconhecimento_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $itembaseconhecimento_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($itembaseconhecimento_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $itembaseconhecimento_view->PageUrl() ?>start=<?php echo $itembaseconhecimento_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($itembaseconhecimento_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $itembaseconhecimento_view->PageUrl() ?>start=<?php echo $itembaseconhecimento_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
fitembaseconhecimentoview.Init();
</script>
<?php
$itembaseconhecimento_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($itembaseconhecimento->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$itembaseconhecimento_view->Page_Terminate();
?>
