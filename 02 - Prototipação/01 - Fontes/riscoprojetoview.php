<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "riscoprojetoinfo.php" ?>
<?php include_once "projetoinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$riscoprojeto_view = NULL; // Initialize page object first

class criscoprojeto_view extends criscoprojeto {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'riscoprojeto';

	// Page object name
	var $PageObjName = 'riscoprojeto_view';

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

		// Table object (riscoprojeto)
		if (!isset($GLOBALS["riscoprojeto"])) {
			$GLOBALS["riscoprojeto"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["riscoprojeto"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_riscoProjeto"] <> "") {
			$this->RecKey["nu_riscoProjeto"] = $_GET["nu_riscoProjeto"];
			$KeyUrl .= "&nu_riscoProjeto=" . urlencode($this->RecKey["nu_riscoProjeto"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (projeto)
		if (!isset($GLOBALS['projeto'])) $GLOBALS['projeto'] = new cprojeto();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'riscoprojeto', TRUE);

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
			$this->Page_Terminate("riscoprojetolist.php");
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
		if (@$_GET["nu_riscoProjeto"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_riscoProjeto"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_riscoProjeto->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_riscoProjeto"] <> "") {
				$this->nu_riscoProjeto->setQueryStringValue($_GET["nu_riscoProjeto"]);
				$this->RecKey["nu_riscoProjeto"] = $this->nu_riscoProjeto->QueryStringValue;
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
						$this->Page_Terminate("riscoprojetolist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_riscoProjeto->CurrentValue) == strval($this->Recordset->fields('nu_riscoProjeto'))) {
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
						$sReturnUrl = "riscoprojetolist.php"; // No matching record, return to list
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
			$sReturnUrl = "riscoprojetolist.php"; // Not page request, return to list
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
		$this->nu_riscoProjeto->setDbValue($rs->fields('nu_riscoProjeto'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->nu_catRisco->setDbValue($rs->fields('nu_catRisco'));
		$this->ic_tpRisco->setDbValue($rs->fields('ic_tpRisco'));
		$this->ds_risco->setDbValue($rs->fields('ds_risco'));
		$this->ds_consequencia->setDbValue($rs->fields('ds_consequencia'));
		$this->nu_probabilidade->setDbValue($rs->fields('nu_probabilidade'));
		$this->nu_impacto->setDbValue($rs->fields('nu_impacto'));
		$this->nu_severidade->setDbValue($rs->fields('nu_severidade'));
		$this->nu_acao->setDbValue($rs->fields('nu_acao'));
		$this->ds_gatilho->setDbValue($rs->fields('ds_gatilho'));
		$this->ds_respRisco->setDbValue($rs->fields('ds_respRisco'));
		$this->nu_usuarioResp->setDbValue($rs->fields('nu_usuarioResp'));
		$this->ic_stRisco->setDbValue($rs->fields('ic_stRisco'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_riscoProjeto->DbValue = $row['nu_riscoProjeto'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->nu_catRisco->DbValue = $row['nu_catRisco'];
		$this->ic_tpRisco->DbValue = $row['ic_tpRisco'];
		$this->ds_risco->DbValue = $row['ds_risco'];
		$this->ds_consequencia->DbValue = $row['ds_consequencia'];
		$this->nu_probabilidade->DbValue = $row['nu_probabilidade'];
		$this->nu_impacto->DbValue = $row['nu_impacto'];
		$this->nu_severidade->DbValue = $row['nu_severidade'];
		$this->nu_acao->DbValue = $row['nu_acao'];
		$this->ds_gatilho->DbValue = $row['ds_gatilho'];
		$this->ds_respRisco->DbValue = $row['ds_respRisco'];
		$this->nu_usuarioResp->DbValue = $row['nu_usuarioResp'];
		$this->ic_stRisco->DbValue = $row['ic_stRisco'];
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
		// nu_riscoProjeto
		// nu_projeto
		// nu_catRisco
		// ic_tpRisco
		// ds_risco
		// ds_consequencia
		// nu_probabilidade
		// nu_impacto
		// nu_severidade
		// nu_acao
		// ds_gatilho
		// ds_respRisco
		// nu_usuarioResp
		// ic_stRisco

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_riscoProjeto
			$this->nu_riscoProjeto->ViewValue = $this->nu_riscoProjeto->CurrentValue;
			$this->nu_riscoProjeto->ViewCustomAttributes = "";

			// nu_projeto
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
			$this->nu_projeto->ViewCustomAttributes = "";

			// nu_catRisco
			if (strval($this->nu_catRisco->CurrentValue) <> "") {
				$sFilterWrk = "[nu_catRisco]" . ew_SearchString("=", $this->nu_catRisco->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_catRisco], [no_catRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[catriscoproj]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_catRisco, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_catRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_catRisco->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_catRisco->ViewValue = $this->nu_catRisco->CurrentValue;
				}
			} else {
				$this->nu_catRisco->ViewValue = NULL;
			}
			$this->nu_catRisco->ViewCustomAttributes = "";

			// ic_tpRisco
			if (strval($this->ic_tpRisco->CurrentValue) <> "") {
				switch ($this->ic_tpRisco->CurrentValue) {
					case $this->ic_tpRisco->FldTagValue(1):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(1) <> "" ? $this->ic_tpRisco->FldTagCaption(1) : $this->ic_tpRisco->CurrentValue;
						break;
					case $this->ic_tpRisco->FldTagValue(2):
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->FldTagCaption(2) <> "" ? $this->ic_tpRisco->FldTagCaption(2) : $this->ic_tpRisco->CurrentValue;
						break;
					default:
						$this->ic_tpRisco->ViewValue = $this->ic_tpRisco->CurrentValue;
				}
			} else {
				$this->ic_tpRisco->ViewValue = NULL;
			}
			$this->ic_tpRisco->ViewCustomAttributes = "";

			// ds_risco
			$this->ds_risco->ViewValue = $this->ds_risco->CurrentValue;
			$this->ds_risco->ViewCustomAttributes = "";

			// ds_consequencia
			$this->ds_consequencia->ViewValue = $this->ds_consequencia->CurrentValue;
			$this->ds_consequencia->ViewCustomAttributes = "";

			// nu_probabilidade
			if (strval($this->nu_probabilidade->CurrentValue) <> "") {
				$sFilterWrk = "[nu_probOcoRisco]" . ew_SearchString("=", $this->nu_probabilidade->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_probOcoRisco], [no_probOcoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[probocorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_probabilidade, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_probabilidade->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_probabilidade->ViewValue = $this->nu_probabilidade->CurrentValue;
				}
			} else {
				$this->nu_probabilidade->ViewValue = NULL;
			}
			$this->nu_probabilidade->ViewCustomAttributes = "";

			// nu_impacto
			if (strval($this->nu_impacto->CurrentValue) <> "") {
				$sFilterWrk = "[nu_impactoRisco]" . ew_SearchString("=", $this->nu_impacto->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_impactoRisco], [no_impactoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[impactorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_impacto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_valor] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_impacto->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_impacto->ViewValue = $this->nu_impacto->CurrentValue;
				}
			} else {
				$this->nu_impacto->ViewValue = NULL;
			}
			$this->nu_impacto->ViewCustomAttributes = "";

			// nu_severidade
			$this->nu_severidade->ViewValue = $this->nu_severidade->CurrentValue;
			$this->nu_severidade->ViewCustomAttributes = "";

			// nu_acao
			if (strval($this->nu_acao->CurrentValue) <> "") {
				$sFilterWrk = "[nu_acaoRisco]" . ew_SearchString("=", $this->nu_acao->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_acaoRisco], [no_acaoRisco] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[acaorisco]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_acao, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_acaoRisco] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_acao->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_acao->ViewValue = $this->nu_acao->CurrentValue;
				}
			} else {
				$this->nu_acao->ViewValue = NULL;
			}
			$this->nu_acao->ViewCustomAttributes = "";

			// ds_gatilho
			$this->ds_gatilho->ViewValue = $this->ds_gatilho->CurrentValue;
			$this->ds_gatilho->ViewCustomAttributes = "";

			// ds_respRisco
			$this->ds_respRisco->ViewValue = $this->ds_respRisco->CurrentValue;
			$this->ds_respRisco->ViewCustomAttributes = "";

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

			// ic_stRisco
			if (strval($this->ic_stRisco->CurrentValue) <> "") {
				switch ($this->ic_stRisco->CurrentValue) {
					case $this->ic_stRisco->FldTagValue(1):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(1) <> "" ? $this->ic_stRisco->FldTagCaption(1) : $this->ic_stRisco->CurrentValue;
						break;
					case $this->ic_stRisco->FldTagValue(2):
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->FldTagCaption(2) <> "" ? $this->ic_stRisco->FldTagCaption(2) : $this->ic_stRisco->CurrentValue;
						break;
					default:
						$this->ic_stRisco->ViewValue = $this->ic_stRisco->CurrentValue;
				}
			} else {
				$this->ic_stRisco->ViewValue = NULL;
			}
			$this->ic_stRisco->ViewCustomAttributes = "";

			// nu_riscoProjeto
			$this->nu_riscoProjeto->LinkCustomAttributes = "";
			$this->nu_riscoProjeto->HrefValue = "";
			$this->nu_riscoProjeto->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// nu_catRisco
			$this->nu_catRisco->LinkCustomAttributes = "";
			$this->nu_catRisco->HrefValue = "";
			$this->nu_catRisco->TooltipValue = "";

			// ic_tpRisco
			$this->ic_tpRisco->LinkCustomAttributes = "";
			$this->ic_tpRisco->HrefValue = "";
			$this->ic_tpRisco->TooltipValue = "";

			// ds_risco
			$this->ds_risco->LinkCustomAttributes = "";
			$this->ds_risco->HrefValue = "";
			$this->ds_risco->TooltipValue = "";

			// ds_consequencia
			$this->ds_consequencia->LinkCustomAttributes = "";
			$this->ds_consequencia->HrefValue = "";
			$this->ds_consequencia->TooltipValue = "";

			// nu_probabilidade
			$this->nu_probabilidade->LinkCustomAttributes = "";
			$this->nu_probabilidade->HrefValue = "";
			$this->nu_probabilidade->TooltipValue = "";

			// nu_impacto
			$this->nu_impacto->LinkCustomAttributes = "";
			$this->nu_impacto->HrefValue = "";
			$this->nu_impacto->TooltipValue = "";

			// nu_severidade
			$this->nu_severidade->LinkCustomAttributes = "";
			$this->nu_severidade->HrefValue = "";
			$this->nu_severidade->TooltipValue = "";

			// nu_acao
			$this->nu_acao->LinkCustomAttributes = "";
			$this->nu_acao->HrefValue = "";
			$this->nu_acao->TooltipValue = "";

			// ds_gatilho
			$this->ds_gatilho->LinkCustomAttributes = "";
			$this->ds_gatilho->HrefValue = "";
			$this->ds_gatilho->TooltipValue = "";

			// ds_respRisco
			$this->ds_respRisco->LinkCustomAttributes = "";
			$this->ds_respRisco->HrefValue = "";
			$this->ds_respRisco->TooltipValue = "";

			// nu_usuarioResp
			$this->nu_usuarioResp->LinkCustomAttributes = "";
			$this->nu_usuarioResp->HrefValue = "";
			$this->nu_usuarioResp->TooltipValue = "";

			// ic_stRisco
			$this->ic_stRisco->LinkCustomAttributes = "";
			$this->ic_stRisco->HrefValue = "";
			$this->ic_stRisco->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_riscoprojeto\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_riscoprojeto',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.friscoprojetoview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "riscoprojetolist.php", $this->TableVar);
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
if (!isset($riscoprojeto_view)) $riscoprojeto_view = new criscoprojeto_view();

// Page init
$riscoprojeto_view->Page_Init();

// Page main
$riscoprojeto_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$riscoprojeto_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($riscoprojeto->Export == "") { ?>
<script type="text/javascript">

// Page object
var riscoprojeto_view = new ew_Page("riscoprojeto_view");
riscoprojeto_view.PageID = "view"; // Page ID
var EW_PAGE_ID = riscoprojeto_view.PageID; // For backward compatibility

// Form object
var friscoprojetoview = new ew_Form("friscoprojetoview");

// Form_CustomValidate event
friscoprojetoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
friscoprojetoview.ValidateRequired = true;
<?php } else { ?>
friscoprojetoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
friscoprojetoview.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetoview.Lists["x_nu_catRisco"] = {"LinkField":"x_nu_catRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_catRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetoview.Lists["x_nu_probabilidade"] = {"LinkField":"x_nu_probOcoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_probOcoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetoview.Lists["x_nu_impacto"] = {"LinkField":"x_nu_impactoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_impactoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetoview.Lists["x_nu_acao"] = {"LinkField":"x_nu_acaoRisco","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_acaoRisco","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
friscoprojetoview.Lists["x_nu_usuarioResp"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($riscoprojeto->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($riscoprojeto->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $riscoprojeto_view->ExportOptions->Render("body") ?>
<?php if (!$riscoprojeto_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($riscoprojeto_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $riscoprojeto_view->ShowPageHeader(); ?>
<?php
$riscoprojeto_view->ShowMessage();
?>
<form name="friscoprojetoview" id="friscoprojetoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="riscoprojeto">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_riscoprojetoview" class="table table-bordered table-striped">
<?php if ($riscoprojeto->nu_riscoProjeto->Visible) { // nu_riscoProjeto ?>
	<tr id="r_nu_riscoProjeto">
		<td><span id="elh_riscoprojeto_nu_riscoProjeto"><?php echo $riscoprojeto->nu_riscoProjeto->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_riscoProjeto->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_riscoProjeto" class="control-group">
<span<?php echo $riscoprojeto->nu_riscoProjeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_riscoProjeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_riscoprojeto_nu_projeto"><?php echo $riscoprojeto->nu_projeto->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_projeto->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_projeto" class="control-group">
<span<?php echo $riscoprojeto->nu_projeto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_projeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_catRisco->Visible) { // nu_catRisco ?>
	<tr id="r_nu_catRisco">
		<td><span id="elh_riscoprojeto_nu_catRisco"><?php echo $riscoprojeto->nu_catRisco->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_catRisco->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_catRisco" class="control-group">
<span<?php echo $riscoprojeto->nu_catRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_catRisco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ic_tpRisco->Visible) { // ic_tpRisco ?>
	<tr id="r_ic_tpRisco">
		<td><span id="elh_riscoprojeto_ic_tpRisco"><?php echo $riscoprojeto->ic_tpRisco->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ic_tpRisco->CellAttributes() ?>>
<span id="el_riscoprojeto_ic_tpRisco" class="control-group">
<span<?php echo $riscoprojeto->ic_tpRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_tpRisco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ds_risco->Visible) { // ds_risco ?>
	<tr id="r_ds_risco">
		<td><span id="elh_riscoprojeto_ds_risco"><?php echo $riscoprojeto->ds_risco->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ds_risco->CellAttributes() ?>>
<span id="el_riscoprojeto_ds_risco" class="control-group">
<span<?php echo $riscoprojeto->ds_risco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ds_risco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ds_consequencia->Visible) { // ds_consequencia ?>
	<tr id="r_ds_consequencia">
		<td><span id="elh_riscoprojeto_ds_consequencia"><?php echo $riscoprojeto->ds_consequencia->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ds_consequencia->CellAttributes() ?>>
<span id="el_riscoprojeto_ds_consequencia" class="control-group">
<span<?php echo $riscoprojeto->ds_consequencia->ViewAttributes() ?>>
<?php echo $riscoprojeto->ds_consequencia->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_probabilidade->Visible) { // nu_probabilidade ?>
	<tr id="r_nu_probabilidade">
		<td><span id="elh_riscoprojeto_nu_probabilidade"><?php echo $riscoprojeto->nu_probabilidade->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_probabilidade->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_probabilidade" class="control-group">
<span<?php echo $riscoprojeto->nu_probabilidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_probabilidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_impacto->Visible) { // nu_impacto ?>
	<tr id="r_nu_impacto">
		<td><span id="elh_riscoprojeto_nu_impacto"><?php echo $riscoprojeto->nu_impacto->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_impacto->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_impacto" class="control-group">
<span<?php echo $riscoprojeto->nu_impacto->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_impacto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_severidade->Visible) { // nu_severidade ?>
	<tr id="r_nu_severidade">
		<td><span id="elh_riscoprojeto_nu_severidade"><?php echo $riscoprojeto->nu_severidade->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_severidade->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_severidade" class="control-group">
<span<?php echo $riscoprojeto->nu_severidade->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_severidade->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_acao->Visible) { // nu_acao ?>
	<tr id="r_nu_acao">
		<td><span id="elh_riscoprojeto_nu_acao"><?php echo $riscoprojeto->nu_acao->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_acao->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_acao" class="control-group">
<span<?php echo $riscoprojeto->nu_acao->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_acao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ds_gatilho->Visible) { // ds_gatilho ?>
	<tr id="r_ds_gatilho">
		<td><span id="elh_riscoprojeto_ds_gatilho"><?php echo $riscoprojeto->ds_gatilho->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ds_gatilho->CellAttributes() ?>>
<span id="el_riscoprojeto_ds_gatilho" class="control-group">
<span<?php echo $riscoprojeto->ds_gatilho->ViewAttributes() ?>>
<?php echo $riscoprojeto->ds_gatilho->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ds_respRisco->Visible) { // ds_respRisco ?>
	<tr id="r_ds_respRisco">
		<td><span id="elh_riscoprojeto_ds_respRisco"><?php echo $riscoprojeto->ds_respRisco->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ds_respRisco->CellAttributes() ?>>
<span id="el_riscoprojeto_ds_respRisco" class="control-group">
<span<?php echo $riscoprojeto->ds_respRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ds_respRisco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->nu_usuarioResp->Visible) { // nu_usuarioResp ?>
	<tr id="r_nu_usuarioResp">
		<td><span id="elh_riscoprojeto_nu_usuarioResp"><?php echo $riscoprojeto->nu_usuarioResp->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->nu_usuarioResp->CellAttributes() ?>>
<span id="el_riscoprojeto_nu_usuarioResp" class="control-group">
<span<?php echo $riscoprojeto->nu_usuarioResp->ViewAttributes() ?>>
<?php echo $riscoprojeto->nu_usuarioResp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($riscoprojeto->ic_stRisco->Visible) { // ic_stRisco ?>
	<tr id="r_ic_stRisco">
		<td><span id="elh_riscoprojeto_ic_stRisco"><?php echo $riscoprojeto->ic_stRisco->FldCaption() ?></span></td>
		<td<?php echo $riscoprojeto->ic_stRisco->CellAttributes() ?>>
<span id="el_riscoprojeto_ic_stRisco" class="control-group">
<span<?php echo $riscoprojeto->ic_stRisco->ViewAttributes() ?>>
<?php echo $riscoprojeto->ic_stRisco->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($riscoprojeto->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($riscoprojeto_view->Pager)) $riscoprojeto_view->Pager = new cNumericPager($riscoprojeto_view->StartRec, $riscoprojeto_view->DisplayRecs, $riscoprojeto_view->TotalRecs, $riscoprojeto_view->RecRange) ?>
<?php if ($riscoprojeto_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($riscoprojeto_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $riscoprojeto_view->PageUrl() ?>start=<?php echo $riscoprojeto_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($riscoprojeto_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $riscoprojeto_view->PageUrl() ?>start=<?php echo $riscoprojeto_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($riscoprojeto_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $riscoprojeto_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($riscoprojeto_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $riscoprojeto_view->PageUrl() ?>start=<?php echo $riscoprojeto_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($riscoprojeto_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $riscoprojeto_view->PageUrl() ?>start=<?php echo $riscoprojeto_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
friscoprojetoview.Init();
</script>
<?php
$riscoprojeto_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($riscoprojeto->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$riscoprojeto_view->Page_Terminate();
?>
