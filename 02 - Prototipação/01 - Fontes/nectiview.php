<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "nectiinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$necti_view = NULL; // Initialize page object first

class cnecti_view extends cnecti {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'necti';

	// Page object name
	var $PageObjName = 'necti_view';

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

		// Table object (necti)
		if (!isset($GLOBALS["necti"])) {
			$GLOBALS["necti"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["necti"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_necTi"] <> "") {
			$this->RecKey["nu_necTi"] = $_GET["nu_necTi"];
			$KeyUrl .= "&nu_necTi=" . urlencode($this->RecKey["nu_necTi"]);
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
			define("EW_TABLE_NAME", 'necti', TRUE);

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
			$this->Page_Terminate("nectilist.php");
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
		if (@$_GET["nu_necTi"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_necTi"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_necTi->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_necTi"] <> "") {
				$this->nu_necTi->setQueryStringValue($_GET["nu_necTi"]);
				$this->RecKey["nu_necTi"] = $this->nu_necTi->QueryStringValue;
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
						$this->Page_Terminate("nectilist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_necTi->CurrentValue) == strval($this->Recordset->fields('nu_necTi'))) {
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
						$sReturnUrl = "nectilist.php"; // No matching record, return to list
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
			$sReturnUrl = "nectilist.php"; // Not page request, return to list
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
		$this->nu_necTi->setDbValue($rs->fields('nu_necTi'));
		$this->nu_periodoPei->setDbValue($rs->fields('nu_periodoPei'));
		if (array_key_exists('EV__nu_periodoPei', $rs->fields)) {
			$this->nu_periodoPei->VirtualValue = $rs->fields('EV__nu_periodoPei'); // Set up virtual field value
		} else {
			$this->nu_periodoPei->VirtualValue = ""; // Clear value
		}
		$this->nu_periodoPdti->setDbValue($rs->fields('nu_periodoPdti'));
		if (array_key_exists('EV__nu_periodoPdti', $rs->fields)) {
			$this->nu_periodoPdti->VirtualValue = $rs->fields('EV__nu_periodoPdti'); // Set up virtual field value
		} else {
			$this->nu_periodoPdti->VirtualValue = ""; // Clear value
		}
		$this->nu_tpNecTi->setDbValue($rs->fields('nu_tpNecTi'));
		$this->ic_tpNec->setDbValue($rs->fields('ic_tpNec'));
		$this->nu_metaneg->setDbValue($rs->fields('nu_metaneg'));
		$this->nu_origem->setDbValue($rs->fields('nu_origem'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ic_gravidade->setDbValue($rs->fields('ic_gravidade'));
		$this->ic_urgencia->setDbValue($rs->fields('ic_urgencia'));
		$this->ic_tendencia->setDbValue($rs->fields('ic_tendencia'));
		$this->ic_prioridade->setDbValue($rs->fields('ic_prioridade'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_necTi->DbValue = $row['nu_necTi'];
		$this->nu_periodoPei->DbValue = $row['nu_periodoPei'];
		$this->nu_periodoPdti->DbValue = $row['nu_periodoPdti'];
		$this->nu_tpNecTi->DbValue = $row['nu_tpNecTi'];
		$this->ic_tpNec->DbValue = $row['ic_tpNec'];
		$this->nu_metaneg->DbValue = $row['nu_metaneg'];
		$this->nu_origem->DbValue = $row['nu_origem'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->ic_gravidade->DbValue = $row['ic_gravidade'];
		$this->ic_urgencia->DbValue = $row['ic_urgencia'];
		$this->ic_tendencia->DbValue = $row['ic_tendencia'];
		$this->ic_prioridade->DbValue = $row['ic_prioridade'];
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
		// nu_necTi
		// nu_periodoPei
		// nu_periodoPdti
		// nu_tpNecTi
		// ic_tpNec
		// nu_metaneg
		// nu_origem
		// nu_area
		// ic_gravidade
		// ic_urgencia
		// ic_tendencia
		// ic_prioridade

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_necTi
			$this->nu_necTi->ViewValue = $this->nu_necTi->CurrentValue;
			$this->nu_necTi->ViewCustomAttributes = "";

			// nu_periodoPei
			if ($this->nu_periodoPei->VirtualValue <> "") {
				$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->VirtualValue;
			} else {
			if (strval($this->nu_periodoPei->CurrentValue) <> "") {
				$sFilterWrk = "[nu_periodoPei]" . ew_SearchString("=", $this->nu_periodoPei->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_periodoPei], [nu_anoInicio] AS [DispFld], [nu_anoFim] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[periodopei]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPei, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_periodoPei->ViewValue = $rswrk->fields('DispFld');
					$this->nu_periodoPei->ViewValue .= ew_ValueSeparator(1,$this->nu_periodoPei) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_periodoPei->ViewValue = $this->nu_periodoPei->CurrentValue;
				}
			} else {
				$this->nu_periodoPei->ViewValue = NULL;
			}
			}
			$this->nu_periodoPei->ViewCustomAttributes = "";

			// nu_periodoPdti
			if ($this->nu_periodoPdti->VirtualValue <> "") {
				$this->nu_periodoPdti->ViewValue = $this->nu_periodoPdti->VirtualValue;
			} else {
			if (strval($this->nu_periodoPdti->CurrentValue) <> "") {
				$sFilterWrk = "[nu_periodo]" . ew_SearchString("=", $this->nu_periodoPdti->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_periodo], [no_periodo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[perplanejamento]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPdti, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_periodoPdti->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_periodoPdti->ViewValue = $this->nu_periodoPdti->CurrentValue;
				}
			} else {
				$this->nu_periodoPdti->ViewValue = NULL;
			}
			}
			$this->nu_periodoPdti->ViewCustomAttributes = "";

			// nu_tpNecTi
			if (strval($this->nu_tpNecTi->CurrentValue) <> "") {
				$sFilterWrk = "[nu_tpNecTi]" . ew_SearchString("=", $this->nu_tpNecTi->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_tpNecTi], [no_tpNecTi] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[tpnecti]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_tpNecTi, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_tpNecTi] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_tpNecTi->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_tpNecTi->ViewValue = $this->nu_tpNecTi->CurrentValue;
				}
			} else {
				$this->nu_tpNecTi->ViewValue = NULL;
			}
			$this->nu_tpNecTi->ViewCustomAttributes = "";

			// ic_tpNec
			if (strval($this->ic_tpNec->CurrentValue) <> "") {
				switch ($this->ic_tpNec->CurrentValue) {
					case $this->ic_tpNec->FldTagValue(1):
						$this->ic_tpNec->ViewValue = $this->ic_tpNec->FldTagCaption(1) <> "" ? $this->ic_tpNec->FldTagCaption(1) : $this->ic_tpNec->CurrentValue;
						break;
					case $this->ic_tpNec->FldTagValue(2):
						$this->ic_tpNec->ViewValue = $this->ic_tpNec->FldTagCaption(2) <> "" ? $this->ic_tpNec->FldTagCaption(2) : $this->ic_tpNec->CurrentValue;
						break;
					default:
						$this->ic_tpNec->ViewValue = $this->ic_tpNec->CurrentValue;
				}
			} else {
				$this->ic_tpNec->ViewValue = NULL;
			}
			$this->ic_tpNec->ViewCustomAttributes = "";

			// nu_metaneg
			if (strval($this->nu_metaneg->CurrentValue) <> "") {
				$sFilterWrk = "[nu_metaneg]" . ew_SearchString("=", $this->nu_metaneg->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_metaneg], [no_metaneg] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[metaneg]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metaneg, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_metaneg->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_metaneg->ViewValue = $this->nu_metaneg->CurrentValue;
				}
			} else {
				$this->nu_metaneg->ViewValue = NULL;
			}
			$this->nu_metaneg->ViewCustomAttributes = "";

			// nu_origem
			if (strval($this->nu_origem->CurrentValue) <> "") {
				$sFilterWrk = "[nu_origem]" . ew_SearchString("=", $this->nu_origem->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT [nu_origem], [no_origem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[origemnecti]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_origem, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_origem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_origem->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_origem->ViewValue = $this->nu_origem->CurrentValue;
				}
			} else {
				$this->nu_origem->ViewValue = NULL;
			}
			$this->nu_origem->ViewCustomAttributes = "";

			// nu_area
			$this->nu_area->ViewValue = $this->nu_area->CurrentValue;
			if (strval($this->nu_area->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_area->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]=S && [nu_organizacao] = (SELECT nu_orgBase from organizacao)";
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

			// ic_gravidade
			if (strval($this->ic_gravidade->CurrentValue) <> "") {
				switch ($this->ic_gravidade->CurrentValue) {
					case $this->ic_gravidade->FldTagValue(1):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(1) <> "" ? $this->ic_gravidade->FldTagCaption(1) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(2):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(2) <> "" ? $this->ic_gravidade->FldTagCaption(2) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(3):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(3) <> "" ? $this->ic_gravidade->FldTagCaption(3) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(4):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(4) <> "" ? $this->ic_gravidade->FldTagCaption(4) : $this->ic_gravidade->CurrentValue;
						break;
					case $this->ic_gravidade->FldTagValue(5):
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->FldTagCaption(5) <> "" ? $this->ic_gravidade->FldTagCaption(5) : $this->ic_gravidade->CurrentValue;
						break;
					default:
						$this->ic_gravidade->ViewValue = $this->ic_gravidade->CurrentValue;
				}
			} else {
				$this->ic_gravidade->ViewValue = NULL;
			}
			$this->ic_gravidade->ViewCustomAttributes = "";

			// ic_urgencia
			if (strval($this->ic_urgencia->CurrentValue) <> "") {
				switch ($this->ic_urgencia->CurrentValue) {
					case $this->ic_urgencia->FldTagValue(1):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(1) <> "" ? $this->ic_urgencia->FldTagCaption(1) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(2):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(2) <> "" ? $this->ic_urgencia->FldTagCaption(2) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(3):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(3) <> "" ? $this->ic_urgencia->FldTagCaption(3) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(4):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(4) <> "" ? $this->ic_urgencia->FldTagCaption(4) : $this->ic_urgencia->CurrentValue;
						break;
					case $this->ic_urgencia->FldTagValue(5):
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->FldTagCaption(5) <> "" ? $this->ic_urgencia->FldTagCaption(5) : $this->ic_urgencia->CurrentValue;
						break;
					default:
						$this->ic_urgencia->ViewValue = $this->ic_urgencia->CurrentValue;
				}
			} else {
				$this->ic_urgencia->ViewValue = NULL;
			}
			$this->ic_urgencia->ViewCustomAttributes = "";

			// ic_tendencia
			if (strval($this->ic_tendencia->CurrentValue) <> "") {
				switch ($this->ic_tendencia->CurrentValue) {
					case $this->ic_tendencia->FldTagValue(1):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(1) <> "" ? $this->ic_tendencia->FldTagCaption(1) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(2):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(2) <> "" ? $this->ic_tendencia->FldTagCaption(2) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(3):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(3) <> "" ? $this->ic_tendencia->FldTagCaption(3) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(4):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(4) <> "" ? $this->ic_tendencia->FldTagCaption(4) : $this->ic_tendencia->CurrentValue;
						break;
					case $this->ic_tendencia->FldTagValue(5):
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->FldTagCaption(5) <> "" ? $this->ic_tendencia->FldTagCaption(5) : $this->ic_tendencia->CurrentValue;
						break;
					default:
						$this->ic_tendencia->ViewValue = $this->ic_tendencia->CurrentValue;
				}
			} else {
				$this->ic_tendencia->ViewValue = NULL;
			}
			$this->ic_tendencia->ViewCustomAttributes = "";

			// ic_prioridade
			if (strval($this->ic_prioridade->CurrentValue) <> "") {
				switch ($this->ic_prioridade->CurrentValue) {
					case $this->ic_prioridade->FldTagValue(1):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(1) <> "" ? $this->ic_prioridade->FldTagCaption(1) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(2):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(2) <> "" ? $this->ic_prioridade->FldTagCaption(2) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(3):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(3) <> "" ? $this->ic_prioridade->FldTagCaption(3) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(4):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(4) <> "" ? $this->ic_prioridade->FldTagCaption(4) : $this->ic_prioridade->CurrentValue;
						break;
					case $this->ic_prioridade->FldTagValue(5):
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->FldTagCaption(5) <> "" ? $this->ic_prioridade->FldTagCaption(5) : $this->ic_prioridade->CurrentValue;
						break;
					default:
						$this->ic_prioridade->ViewValue = $this->ic_prioridade->CurrentValue;
				}
			} else {
				$this->ic_prioridade->ViewValue = NULL;
			}
			$this->ic_prioridade->ViewCustomAttributes = "";

			// nu_necTi
			$this->nu_necTi->LinkCustomAttributes = "";
			$this->nu_necTi->HrefValue = "";
			$this->nu_necTi->TooltipValue = "";

			// nu_periodoPei
			$this->nu_periodoPei->LinkCustomAttributes = "";
			$this->nu_periodoPei->HrefValue = "";
			$this->nu_periodoPei->TooltipValue = "";

			// nu_periodoPdti
			$this->nu_periodoPdti->LinkCustomAttributes = "";
			$this->nu_periodoPdti->HrefValue = "";
			$this->nu_periodoPdti->TooltipValue = "";

			// nu_tpNecTi
			$this->nu_tpNecTi->LinkCustomAttributes = "";
			$this->nu_tpNecTi->HrefValue = "";
			$this->nu_tpNecTi->TooltipValue = "";

			// ic_tpNec
			$this->ic_tpNec->LinkCustomAttributes = "";
			$this->ic_tpNec->HrefValue = "";
			$this->ic_tpNec->TooltipValue = "";

			// nu_metaneg
			$this->nu_metaneg->LinkCustomAttributes = "";
			$this->nu_metaneg->HrefValue = "";
			$this->nu_metaneg->TooltipValue = "";

			// nu_origem
			$this->nu_origem->LinkCustomAttributes = "";
			$this->nu_origem->HrefValue = "";
			$this->nu_origem->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// ic_gravidade
			$this->ic_gravidade->LinkCustomAttributes = "";
			$this->ic_gravidade->HrefValue = "";
			$this->ic_gravidade->TooltipValue = "";

			// ic_urgencia
			$this->ic_urgencia->LinkCustomAttributes = "";
			$this->ic_urgencia->HrefValue = "";
			$this->ic_urgencia->TooltipValue = "";

			// ic_tendencia
			$this->ic_tendencia->LinkCustomAttributes = "";
			$this->ic_tendencia->HrefValue = "";
			$this->ic_tendencia->TooltipValue = "";

			// ic_prioridade
			$this->ic_prioridade->LinkCustomAttributes = "";
			$this->ic_prioridade->HrefValue = "";
			$this->ic_prioridade->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_necti\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_necti',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fnectiview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "nectilist.php", $this->TableVar);
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
if (!isset($necti_view)) $necti_view = new cnecti_view();

// Page init
$necti_view->Page_Init();

// Page main
$necti_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$necti_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($necti->Export == "") { ?>
<script type="text/javascript">

// Page object
var necti_view = new ew_Page("necti_view");
necti_view.PageID = "view"; // Page ID
var EW_PAGE_ID = necti_view.PageID; // For backward compatibility

// Form object
var fnectiview = new ew_Form("fnectiview");

// Form_CustomValidate event
fnectiview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnectiview.ValidateRequired = true;
<?php } else { ?>
fnectiview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnectiview.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_anoInicio","x_nu_anoFim","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectiview.Lists["x_nu_periodoPdti"] = {"LinkField":"x_nu_periodo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_periodo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectiview.Lists["x_nu_tpNecTi"] = {"LinkField":"x_nu_tpNecTi","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpNecTi","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectiview.Lists["x_nu_metaneg"] = {"LinkField":"x_nu_metaneg","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metaneg","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectiview.Lists["x_nu_origem"] = {"LinkField":"x_nu_origem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_origem","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectiview.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($necti->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($necti->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $necti_view->ExportOptions->Render("body") ?>
<?php if (!$necti_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($necti_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $necti_view->ShowPageHeader(); ?>
<?php
$necti_view->ShowMessage();
?>
<form name="fnectiview" id="fnectiview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="necti">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_nectiview" class="table table-bordered table-striped">
<?php if ($necti->nu_necTi->Visible) { // nu_necTi ?>
	<tr id="r_nu_necTi">
		<td><span id="elh_necti_nu_necTi"><?php echo $necti->nu_necTi->FldCaption() ?></span></td>
		<td<?php echo $necti->nu_necTi->CellAttributes() ?>>
<span id="el_necti_nu_necTi" class="control-group">
<span<?php echo $necti->nu_necTi->ViewAttributes() ?>>
<?php echo $necti->nu_necTi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->nu_periodoPei->Visible) { // nu_periodoPei ?>
	<tr id="r_nu_periodoPei">
		<td><span id="elh_necti_nu_periodoPei"><?php echo $necti->nu_periodoPei->FldCaption() ?></span></td>
		<td<?php echo $necti->nu_periodoPei->CellAttributes() ?>>
<span id="el_necti_nu_periodoPei" class="control-group">
<span<?php echo $necti->nu_periodoPei->ViewAttributes() ?>>
<?php echo $necti->nu_periodoPei->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->nu_periodoPdti->Visible) { // nu_periodoPdti ?>
	<tr id="r_nu_periodoPdti">
		<td><span id="elh_necti_nu_periodoPdti"><?php echo $necti->nu_periodoPdti->FldCaption() ?></span></td>
		<td<?php echo $necti->nu_periodoPdti->CellAttributes() ?>>
<span id="el_necti_nu_periodoPdti" class="control-group">
<span<?php echo $necti->nu_periodoPdti->ViewAttributes() ?>>
<?php echo $necti->nu_periodoPdti->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->nu_tpNecTi->Visible) { // nu_tpNecTi ?>
	<tr id="r_nu_tpNecTi">
		<td><span id="elh_necti_nu_tpNecTi"><?php echo $necti->nu_tpNecTi->FldCaption() ?></span></td>
		<td<?php echo $necti->nu_tpNecTi->CellAttributes() ?>>
<span id="el_necti_nu_tpNecTi" class="control-group">
<span<?php echo $necti->nu_tpNecTi->ViewAttributes() ?>>
<?php echo $necti->nu_tpNecTi->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->ic_tpNec->Visible) { // ic_tpNec ?>
	<tr id="r_ic_tpNec">
		<td><span id="elh_necti_ic_tpNec"><?php echo $necti->ic_tpNec->FldCaption() ?></span></td>
		<td<?php echo $necti->ic_tpNec->CellAttributes() ?>>
<span id="el_necti_ic_tpNec" class="control-group">
<span<?php echo $necti->ic_tpNec->ViewAttributes() ?>>
<?php echo $necti->ic_tpNec->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->nu_metaneg->Visible) { // nu_metaneg ?>
	<tr id="r_nu_metaneg">
		<td><span id="elh_necti_nu_metaneg"><?php echo $necti->nu_metaneg->FldCaption() ?></span></td>
		<td<?php echo $necti->nu_metaneg->CellAttributes() ?>>
<span id="el_necti_nu_metaneg" class="control-group">
<span<?php echo $necti->nu_metaneg->ViewAttributes() ?>>
<?php echo $necti->nu_metaneg->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->nu_origem->Visible) { // nu_origem ?>
	<tr id="r_nu_origem">
		<td><span id="elh_necti_nu_origem"><?php echo $necti->nu_origem->FldCaption() ?></span></td>
		<td<?php echo $necti->nu_origem->CellAttributes() ?>>
<span id="el_necti_nu_origem" class="control-group">
<span<?php echo $necti->nu_origem->ViewAttributes() ?>>
<?php echo $necti->nu_origem->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->nu_area->Visible) { // nu_area ?>
	<tr id="r_nu_area">
		<td><span id="elh_necti_nu_area"><?php echo $necti->nu_area->FldCaption() ?></span></td>
		<td<?php echo $necti->nu_area->CellAttributes() ?>>
<span id="el_necti_nu_area" class="control-group">
<span<?php echo $necti->nu_area->ViewAttributes() ?>>
<?php echo $necti->nu_area->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->ic_gravidade->Visible) { // ic_gravidade ?>
	<tr id="r_ic_gravidade">
		<td><span id="elh_necti_ic_gravidade"><?php echo $necti->ic_gravidade->FldCaption() ?></span></td>
		<td<?php echo $necti->ic_gravidade->CellAttributes() ?>>
<span id="el_necti_ic_gravidade" class="control-group">
<span<?php echo $necti->ic_gravidade->ViewAttributes() ?>>
<?php echo $necti->ic_gravidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->ic_urgencia->Visible) { // ic_urgencia ?>
	<tr id="r_ic_urgencia">
		<td><span id="elh_necti_ic_urgencia"><?php echo $necti->ic_urgencia->FldCaption() ?></span></td>
		<td<?php echo $necti->ic_urgencia->CellAttributes() ?>>
<span id="el_necti_ic_urgencia" class="control-group">
<span<?php echo $necti->ic_urgencia->ViewAttributes() ?>>
<?php echo $necti->ic_urgencia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->ic_tendencia->Visible) { // ic_tendencia ?>
	<tr id="r_ic_tendencia">
		<td><span id="elh_necti_ic_tendencia"><?php echo $necti->ic_tendencia->FldCaption() ?></span></td>
		<td<?php echo $necti->ic_tendencia->CellAttributes() ?>>
<span id="el_necti_ic_tendencia" class="control-group">
<span<?php echo $necti->ic_tendencia->ViewAttributes() ?>>
<?php echo $necti->ic_tendencia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($necti->ic_prioridade->Visible) { // ic_prioridade ?>
	<tr id="r_ic_prioridade">
		<td><span id="elh_necti_ic_prioridade"><?php echo $necti->ic_prioridade->FldCaption() ?></span></td>
		<td<?php echo $necti->ic_prioridade->CellAttributes() ?>>
<span id="el_necti_ic_prioridade" class="control-group">
<span<?php echo $necti->ic_prioridade->ViewAttributes() ?>>
<?php echo $necti->ic_prioridade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($necti->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($necti_view->Pager)) $necti_view->Pager = new cNumericPager($necti_view->StartRec, $necti_view->DisplayRecs, $necti_view->TotalRecs, $necti_view->RecRange) ?>
<?php if ($necti_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($necti_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $necti_view->PageUrl() ?>start=<?php echo $necti_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($necti_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $necti_view->PageUrl() ?>start=<?php echo $necti_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($necti_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $necti_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($necti_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $necti_view->PageUrl() ?>start=<?php echo $necti_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($necti_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $necti_view->PageUrl() ?>start=<?php echo $necti_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
fnectiview.Init();
</script>
<?php
$necti_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($necti->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$necti_view->Page_Terminate();
?>
