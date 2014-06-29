<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "planotiinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$planoti_view = NULL; // Initialize page object first

class cplanoti_view extends cplanoti {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'planoti';

	// Page object name
	var $PageObjName = 'planoti_view';

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

		// Table object (planoti)
		if (!isset($GLOBALS["planoti"])) {
			$GLOBALS["planoti"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["planoti"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_plano"] <> "") {
			$this->RecKey["nu_plano"] = $_GET["nu_plano"];
			$KeyUrl .= "&nu_plano=" . urlencode($this->RecKey["nu_plano"]);
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
			define("EW_TABLE_NAME", 'planoti', TRUE);

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
			$this->Page_Terminate("planotilist.php");
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
		if (@$_GET["nu_plano"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_plano"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_plano->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_plano"] <> "") {
				$this->nu_plano->setQueryStringValue($_GET["nu_plano"]);
				$this->RecKey["nu_plano"] = $this->nu_plano->QueryStringValue;
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
						$this->Page_Terminate("planotilist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_plano->CurrentValue) == strval($this->Recordset->fields('nu_plano'))) {
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
						$sReturnUrl = "planotilist.php"; // No matching record, return to list
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
			$sReturnUrl = "planotilist.php"; // Not page request, return to list
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
		$this->nu_plano->setDbValue($rs->fields('nu_plano'));
		$this->nu_anoInicio->setDbValue($rs->fields('nu_anoInicio'));
		$this->nu_anoFim->setDbValue($rs->fields('nu_anoFim'));
		$this->no_plano->setDbValue($rs->fields('no_plano'));
		$this->ds_plano->setDbValue($rs->fields('ds_plano'));
		$this->nu_planoEstrategico->setDbValue($rs->fields('nu_planoEstrategico'));
		$this->no_localArquivo->setDbValue($rs->fields('no_localArquivo'));
		$this->im_anexo->Upload->DbValue = $rs->fields('im_anexo');
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->ts_datahora->setDbValue($rs->fields('ts_datahora'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_plano->DbValue = $row['nu_plano'];
		$this->nu_anoInicio->DbValue = $row['nu_anoInicio'];
		$this->nu_anoFim->DbValue = $row['nu_anoFim'];
		$this->no_plano->DbValue = $row['no_plano'];
		$this->ds_plano->DbValue = $row['ds_plano'];
		$this->nu_planoEstrategico->DbValue = $row['nu_planoEstrategico'];
		$this->no_localArquivo->DbValue = $row['no_localArquivo'];
		$this->im_anexo->Upload->DbValue = $row['im_anexo'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
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
		// nu_plano
		// nu_anoInicio
		// nu_anoFim
		// no_plano
		// ds_plano
		// nu_planoEstrategico
		// no_localArquivo
		// im_anexo
		// ic_situacao
		// nu_usuario
		// ts_datahora

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_plano
			$this->nu_plano->ViewValue = $this->nu_plano->CurrentValue;
			$this->nu_plano->ViewCustomAttributes = "";

			// nu_anoInicio
			$this->nu_anoInicio->ViewValue = $this->nu_anoInicio->CurrentValue;
			$this->nu_anoInicio->ViewCustomAttributes = "";

			// nu_anoFim
			$this->nu_anoFim->ViewValue = $this->nu_anoFim->CurrentValue;
			if (strval($this->nu_anoFim->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ano]" . ew_SearchString("=", $this->nu_anoFim->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_ano], [nu_ano] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[ano]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_anoFim, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_anoFim->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_anoFim->ViewValue = $this->nu_anoFim->CurrentValue;
				}
			} else {
				$this->nu_anoFim->ViewValue = NULL;
			}
			$this->nu_anoFim->ViewCustomAttributes = "";

			// no_plano
			$this->no_plano->ViewValue = $this->no_plano->CurrentValue;
			$this->no_plano->ViewCustomAttributes = "";

			// ds_plano
			$this->ds_plano->ViewValue = $this->ds_plano->CurrentValue;
			$this->ds_plano->ViewCustomAttributes = "";

			// nu_planoEstrategico
			if (strval($this->nu_planoEstrategico->CurrentValue) <> "") {
				$sFilterWrk = "[nu_plano]" . ew_SearchString("=", $this->nu_planoEstrategico->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_plano], [no_plano] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[planoestrategico]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_planoEstrategico, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_planoEstrategico->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_planoEstrategico->ViewValue = $this->nu_planoEstrategico->CurrentValue;
				}
			} else {
				$this->nu_planoEstrategico->ViewValue = NULL;
			}
			$this->nu_planoEstrategico->ViewCustomAttributes = "";

			// no_localArquivo
			$this->no_localArquivo->ViewValue = $this->no_localArquivo->CurrentValue;
			$this->no_localArquivo->ViewCustomAttributes = "";

			// im_anexo
			$this->im_anexo->UploadPath = "arquivos/plano_ti";
			if (!ew_Empty($this->im_anexo->Upload->DbValue)) {
				$this->im_anexo->ViewValue = $this->im_anexo->Upload->DbValue;
			} else {
				$this->im_anexo->ViewValue = "";
			}
			$this->im_anexo->ViewCustomAttributes = "";

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
					default:
						$this->ic_situacao->ViewValue = $this->ic_situacao->CurrentValue;
				}
			} else {
				$this->ic_situacao->ViewValue = NULL;
			}
			$this->ic_situacao->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			$this->nu_usuario->ViewCustomAttributes = "";

			// ts_datahora
			$this->ts_datahora->ViewValue = $this->ts_datahora->CurrentValue;
			$this->ts_datahora->ViewValue = ew_FormatDateTime($this->ts_datahora->ViewValue, 7);
			$this->ts_datahora->ViewCustomAttributes = "";

			// nu_plano
			$this->nu_plano->LinkCustomAttributes = "";
			$this->nu_plano->HrefValue = "";
			$this->nu_plano->TooltipValue = "";

			// nu_anoInicio
			$this->nu_anoInicio->LinkCustomAttributes = "";
			$this->nu_anoInicio->HrefValue = "";
			$this->nu_anoInicio->TooltipValue = "";

			// nu_anoFim
			$this->nu_anoFim->LinkCustomAttributes = "";
			$this->nu_anoFim->HrefValue = "";
			$this->nu_anoFim->TooltipValue = "";

			// no_plano
			$this->no_plano->LinkCustomAttributes = "";
			$this->no_plano->HrefValue = "";
			$this->no_plano->TooltipValue = "";

			// ds_plano
			$this->ds_plano->LinkCustomAttributes = "";
			$this->ds_plano->HrefValue = "";
			$this->ds_plano->TooltipValue = "";

			// nu_planoEstrategico
			$this->nu_planoEstrategico->LinkCustomAttributes = "";
			$this->nu_planoEstrategico->HrefValue = "";
			$this->nu_planoEstrategico->TooltipValue = "";

			// no_localArquivo
			$this->no_localArquivo->LinkCustomAttributes = "";
			$this->no_localArquivo->HrefValue = "";
			$this->no_localArquivo->TooltipValue = "";

			// im_anexo
			$this->im_anexo->LinkCustomAttributes = "";
			$this->im_anexo->HrefValue = "";
			$this->im_anexo->HrefValue2 = $this->im_anexo->UploadPath . $this->im_anexo->Upload->DbValue;
			$this->im_anexo->TooltipValue = "";

			// ic_situacao
			$this->ic_situacao->LinkCustomAttributes = "";
			$this->ic_situacao->HrefValue = "";
			$this->ic_situacao->TooltipValue = "";

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
		$item->Body = "<a id=\"emf_planoti\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_planoti',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fplanotiview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "planotilist.php", $this->TableVar);
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
if (!isset($planoti_view)) $planoti_view = new cplanoti_view();

// Page init
$planoti_view->Page_Init();

// Page main
$planoti_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$planoti_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($planoti->Export == "") { ?>
<script type="text/javascript">

// Page object
var planoti_view = new ew_Page("planoti_view");
planoti_view.PageID = "view"; // Page ID
var EW_PAGE_ID = planoti_view.PageID; // For backward compatibility

// Form object
var fplanotiview = new ew_Form("fplanotiview");

// Form_CustomValidate event
fplanotiview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fplanotiview.ValidateRequired = true;
<?php } else { ?>
fplanotiview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
fplanotiview.MultiPage = new ew_MultiPage("fplanotiview",
	[["x_nu_plano",1],["x_nu_anoInicio",1],["x_nu_anoFim",1],["x_no_plano",1],["x_ds_plano",1],["x_nu_planoEstrategico",1],["x_no_localArquivo",2],["x_im_anexo",2],["x_ic_situacao",1]]
);

// Dynamic selection lists
fplanotiview.Lists["x_nu_anoFim"] = {"LinkField":"x_nu_ano","Ajax":true,"AutoFill":false,"DisplayFields":["x_nu_ano","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fplanotiview.Lists["x_nu_planoEstrategico"] = {"LinkField":"x_nu_plano","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_plano","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($planoti->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($planoti->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $planoti_view->ExportOptions->Render("body") ?>
<?php if (!$planoti_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($planoti_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $planoti_view->ShowPageHeader(); ?>
<?php
$planoti_view->ShowMessage();
?>
<form name="fplanotiview" id="fplanotiview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="planoti">
<?php if ($planoti->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div class="tabbable" id="planoti_view">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_planoti1" data-toggle="tab"><?php echo $planoti->PageCaption(1) ?></a></li>
		<li><a href="#tab_planoti2" data-toggle="tab"><?php echo $planoti->PageCaption(2) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
		<div class="tab-pane active" id="tab_planoti1">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_planotiview1" class="table table-bordered table-striped">
<?php if ($planoti->nu_plano->Visible) { // nu_plano ?>
	<tr id="r_nu_plano">
		<td><span id="elh_planoti_nu_plano"><?php echo $planoti->nu_plano->FldCaption() ?></span></td>
		<td<?php echo $planoti->nu_plano->CellAttributes() ?>>
<span id="el_planoti_nu_plano" class="control-group">
<span<?php echo $planoti->nu_plano->ViewAttributes() ?>>
<?php echo $planoti->nu_plano->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($planoti->nu_anoInicio->Visible) { // nu_anoInicio ?>
	<tr id="r_nu_anoInicio">
		<td><span id="elh_planoti_nu_anoInicio"><?php echo $planoti->nu_anoInicio->FldCaption() ?></span></td>
		<td<?php echo $planoti->nu_anoInicio->CellAttributes() ?>>
<span id="el_planoti_nu_anoInicio" class="control-group">
<span<?php echo $planoti->nu_anoInicio->ViewAttributes() ?>>
<?php echo $planoti->nu_anoInicio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($planoti->nu_anoFim->Visible) { // nu_anoFim ?>
	<tr id="r_nu_anoFim">
		<td><span id="elh_planoti_nu_anoFim"><?php echo $planoti->nu_anoFim->FldCaption() ?></span></td>
		<td<?php echo $planoti->nu_anoFim->CellAttributes() ?>>
<span id="el_planoti_nu_anoFim" class="control-group">
<span<?php echo $planoti->nu_anoFim->ViewAttributes() ?>>
<?php echo $planoti->nu_anoFim->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($planoti->no_plano->Visible) { // no_plano ?>
	<tr id="r_no_plano">
		<td><span id="elh_planoti_no_plano"><?php echo $planoti->no_plano->FldCaption() ?></span></td>
		<td<?php echo $planoti->no_plano->CellAttributes() ?>>
<span id="el_planoti_no_plano" class="control-group">
<span<?php echo $planoti->no_plano->ViewAttributes() ?>>
<?php echo $planoti->no_plano->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($planoti->ds_plano->Visible) { // ds_plano ?>
	<tr id="r_ds_plano">
		<td><span id="elh_planoti_ds_plano"><?php echo $planoti->ds_plano->FldCaption() ?></span></td>
		<td<?php echo $planoti->ds_plano->CellAttributes() ?>>
<span id="el_planoti_ds_plano" class="control-group">
<span<?php echo $planoti->ds_plano->ViewAttributes() ?>>
<?php echo $planoti->ds_plano->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($planoti->nu_planoEstrategico->Visible) { // nu_planoEstrategico ?>
	<tr id="r_nu_planoEstrategico">
		<td><span id="elh_planoti_nu_planoEstrategico"><?php echo $planoti->nu_planoEstrategico->FldCaption() ?></span></td>
		<td<?php echo $planoti->nu_planoEstrategico->CellAttributes() ?>>
<span id="el_planoti_nu_planoEstrategico" class="control-group">
<span<?php echo $planoti->nu_planoEstrategico->ViewAttributes() ?>>
<?php echo $planoti->nu_planoEstrategico->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($planoti->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_planoti_ic_situacao"><?php echo $planoti->ic_situacao->FldCaption() ?></span></td>
		<td<?php echo $planoti->ic_situacao->CellAttributes() ?>>
<span id="el_planoti_ic_situacao" class="control-group">
<span<?php echo $planoti->ic_situacao->ViewAttributes() ?>>
<?php echo $planoti->ic_situacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($planoti->nu_usuario->Visible) { // nu_usuario ?>
	<tr id="r_nu_usuario">
		<td><span id="elh_planoti_nu_usuario"><?php echo $planoti->nu_usuario->FldCaption() ?></span></td>
		<td<?php echo $planoti->nu_usuario->CellAttributes() ?>>
<span id="el_planoti_nu_usuario" class="control-group">
<span<?php echo $planoti->nu_usuario->ViewAttributes() ?>>
<?php echo $planoti->nu_usuario->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($planoti->ts_datahora->Visible) { // ts_datahora ?>
	<tr id="r_ts_datahora">
		<td><span id="elh_planoti_ts_datahora"><?php echo $planoti->ts_datahora->FldCaption() ?></span></td>
		<td<?php echo $planoti->ts_datahora->CellAttributes() ?>>
<span id="el_planoti_ts_datahora" class="control-group">
<span<?php echo $planoti->ts_datahora->ViewAttributes() ?>>
<?php echo $planoti->ts_datahora->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
		</div>
		<div class="tab-pane" id="tab_planoti2">
<table cellspacing="0" class="ewGrid" style="width: 100%"><tr><td>
<table id="tbl_planotiview2" class="table table-bordered table-striped">
<?php if ($planoti->no_localArquivo->Visible) { // no_localArquivo ?>
	<tr id="r_no_localArquivo">
		<td><span id="elh_planoti_no_localArquivo"><?php echo $planoti->no_localArquivo->FldCaption() ?></span></td>
		<td<?php echo $planoti->no_localArquivo->CellAttributes() ?>>
<span id="el_planoti_no_localArquivo" class="control-group">
<span<?php echo $planoti->no_localArquivo->ViewAttributes() ?>>
<?php echo $planoti->no_localArquivo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($planoti->im_anexo->Visible) { // im_anexo ?>
	<tr id="r_im_anexo">
		<td><span id="elh_planoti_im_anexo"><?php echo $planoti->im_anexo->FldCaption() ?></span></td>
		<td<?php echo $planoti->im_anexo->CellAttributes() ?>>
<span id="el_planoti_im_anexo" class="control-group">
<span<?php echo $planoti->im_anexo->ViewAttributes() ?>>
<?php
$Files = explode(",", $planoti->im_anexo->Upload->DbValue);
$HrefValue = $planoti->im_anexo->HrefValue;
$FileCount = count($Files);
for ($i = 0; $i < $FileCount; $i++) {
if ($Files[$i] <> "") {
$planoti->im_anexo->ViewValue = $Files[$i];
$planoti->im_anexo->HrefValue = str_replace("%u", ew_HtmlEncode($planoti->im_anexo->UploadPath . $Files[$i]), $HrefValue);
$Files[$i] = str_replace("%f", ew_HtmlEncode($planoti->im_anexo->UploadPath . $Files[$i]), $planoti->im_anexo->ViewValue);
?>
<?php if ($planoti->im_anexo->LinkAttributes() <> "") { ?>
<?php if (!empty($planoti->im_anexo->Upload->DbValue)) { ?>
<?php echo $planoti->im_anexo->ViewValue ?>
<?php } elseif (!in_array($planoti->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($planoti->im_anexo->Upload->DbValue)) { ?>
<?php echo $planoti->im_anexo->ViewValue ?>
<?php } elseif (!in_array($planoti->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
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
<?php if ($planoti->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
<?php if ($planoti->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($planoti_view->Pager)) $planoti_view->Pager = new cNumericPager($planoti_view->StartRec, $planoti_view->DisplayRecs, $planoti_view->TotalRecs, $planoti_view->RecRange) ?>
<?php if ($planoti_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($planoti_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $planoti_view->PageUrl() ?>start=<?php echo $planoti_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($planoti_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $planoti_view->PageUrl() ?>start=<?php echo $planoti_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($planoti_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $planoti_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($planoti_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $planoti_view->PageUrl() ?>start=<?php echo $planoti_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($planoti_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $planoti_view->PageUrl() ?>start=<?php echo $planoti_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
fplanotiview.Init();
</script>
<?php
$planoti_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($planoti->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$planoti_view->Page_Terminate();
?>
