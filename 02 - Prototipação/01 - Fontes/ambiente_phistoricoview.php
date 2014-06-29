<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ambiente_phistoricoinfo.php" ?>
<?php include_once "ambienteinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$ambiente_phistorico_view = NULL; // Initialize page object first

class cambiente_phistorico_view extends cambiente_phistorico {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'ambiente_phistorico';

	// Page object name
	var $PageObjName = 'ambiente_phistorico_view';

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

		// Table object (ambiente_phistorico)
		if (!isset($GLOBALS["ambiente_phistorico"])) {
			$GLOBALS["ambiente_phistorico"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ambiente_phistorico"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_projhist"] <> "") {
			$this->RecKey["nu_projhist"] = $_GET["nu_projhist"];
			$KeyUrl .= "&nu_projhist=" . urlencode($this->RecKey["nu_projhist"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (ambiente)
		if (!isset($GLOBALS['ambiente'])) $GLOBALS['ambiente'] = new cambiente();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ambiente_phistorico', TRUE);

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
			$this->Page_Terminate("ambiente_phistoricolist.php");
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
		if (@$_GET["nu_projhist"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["nu_projhist"]);
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Setup export options
		$this->SetupExportOptions();
		$this->nu_projhist->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["nu_projhist"] <> "") {
				$this->nu_projhist->setQueryStringValue($_GET["nu_projhist"]);
				$this->RecKey["nu_projhist"] = $this->nu_projhist->QueryStringValue;
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
						$this->Page_Terminate("ambiente_phistoricolist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->nu_projhist->CurrentValue) == strval($this->Recordset->fields('nu_projhist'))) {
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
						$sReturnUrl = "ambiente_phistoricolist.php"; // No matching record, return to list
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
			$sReturnUrl = "ambiente_phistoricolist.php"; // Not page request, return to list
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
		$this->nu_projhist->setDbValue($rs->fields('nu_projhist'));
		$this->nu_ambiente->setDbValue($rs->fields('nu_ambiente'));
		$this->no_projeto->setDbValue($rs->fields('no_projeto'));
		$this->ds_projeto->setDbValue($rs->fields('ds_projeto'));
		$this->qt_pf->setDbValue($rs->fields('qt_pf'));
		$this->qt_sloc->setDbValue($rs->fields('qt_sloc'));
		$this->qt_slocPf->setDbValue($rs->fields('qt_slocPf'));
		$this->qt_esforcoReal->setDbValue($rs->fields('qt_esforcoReal'));
		$this->qt_esforcoRealPm->setDbValue($rs->fields('qt_esforcoRealPm'));
		$this->qt_prazoRealM->setDbValue($rs->fields('qt_prazoRealM'));
		$this->ic_situacao->setDbValue($rs->fields('ic_situacao'));
		$this->ds_acoes->setDbValue($rs->fields('ds_acoes'));
		$this->nu_usuarioInc->setDbValue($rs->fields('nu_usuarioInc'));
		$this->dh_inclusao->setDbValue($rs->fields('dh_inclusao'));
		$this->nu_usuarioAlt->setDbValue($rs->fields('nu_usuarioAlt'));
		$this->dh_alteracao->setDbValue($rs->fields('dh_alteracao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_projhist->DbValue = $row['nu_projhist'];
		$this->nu_ambiente->DbValue = $row['nu_ambiente'];
		$this->no_projeto->DbValue = $row['no_projeto'];
		$this->ds_projeto->DbValue = $row['ds_projeto'];
		$this->qt_pf->DbValue = $row['qt_pf'];
		$this->qt_sloc->DbValue = $row['qt_sloc'];
		$this->qt_slocPf->DbValue = $row['qt_slocPf'];
		$this->qt_esforcoReal->DbValue = $row['qt_esforcoReal'];
		$this->qt_esforcoRealPm->DbValue = $row['qt_esforcoRealPm'];
		$this->qt_prazoRealM->DbValue = $row['qt_prazoRealM'];
		$this->ic_situacao->DbValue = $row['ic_situacao'];
		$this->ds_acoes->DbValue = $row['ds_acoes'];
		$this->nu_usuarioInc->DbValue = $row['nu_usuarioInc'];
		$this->dh_inclusao->DbValue = $row['dh_inclusao'];
		$this->nu_usuarioAlt->DbValue = $row['nu_usuarioAlt'];
		$this->dh_alteracao->DbValue = $row['dh_alteracao'];
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
		if ($this->qt_sloc->FormValue == $this->qt_sloc->CurrentValue && is_numeric(ew_StrToFloat($this->qt_sloc->CurrentValue)))
			$this->qt_sloc->CurrentValue = ew_StrToFloat($this->qt_sloc->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_slocPf->FormValue == $this->qt_slocPf->CurrentValue && is_numeric(ew_StrToFloat($this->qt_slocPf->CurrentValue)))
			$this->qt_slocPf->CurrentValue = ew_StrToFloat($this->qt_slocPf->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforcoReal->FormValue == $this->qt_esforcoReal->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforcoReal->CurrentValue)))
			$this->qt_esforcoReal->CurrentValue = ew_StrToFloat($this->qt_esforcoReal->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_esforcoRealPm->FormValue == $this->qt_esforcoRealPm->CurrentValue && is_numeric(ew_StrToFloat($this->qt_esforcoRealPm->CurrentValue)))
			$this->qt_esforcoRealPm->CurrentValue = ew_StrToFloat($this->qt_esforcoRealPm->CurrentValue);

		// Convert decimal values if posted back
		if ($this->qt_prazoRealM->FormValue == $this->qt_prazoRealM->CurrentValue && is_numeric(ew_StrToFloat($this->qt_prazoRealM->CurrentValue)))
			$this->qt_prazoRealM->CurrentValue = ew_StrToFloat($this->qt_prazoRealM->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_projhist
		// nu_ambiente
		// no_projeto
		// ds_projeto
		// qt_pf
		// qt_sloc
		// qt_slocPf
		// qt_esforcoReal
		// qt_esforcoRealPm
		// qt_prazoRealM
		// ic_situacao
		// ds_acoes
		// nu_usuarioInc
		// dh_inclusao
		// nu_usuarioAlt
		// dh_alteracao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_projhist
			$this->nu_projhist->ViewValue = $this->nu_projhist->CurrentValue;
			$this->nu_projhist->ViewCustomAttributes = "";

			// nu_ambiente
			if (strval($this->nu_ambiente->CurrentValue) <> "") {
				$sFilterWrk = "[nu_ambiente]" . ew_SearchString("=", $this->nu_ambiente->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->Lookup_Selecting($this->nu_ambiente, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_ambiente] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_ambiente->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_ambiente->ViewValue = $this->nu_ambiente->CurrentValue;
				}
			} else {
				$this->nu_ambiente->ViewValue = NULL;
			}
			$this->nu_ambiente->ViewCustomAttributes = "";

			// no_projeto
			$this->no_projeto->ViewValue = $this->no_projeto->CurrentValue;
			$this->no_projeto->ViewCustomAttributes = "";

			// ds_projeto
			$this->ds_projeto->ViewValue = $this->ds_projeto->CurrentValue;
			$this->ds_projeto->ViewCustomAttributes = "";

			// qt_pf
			$this->qt_pf->ViewValue = $this->qt_pf->CurrentValue;
			$this->qt_pf->ViewCustomAttributes = "";

			// qt_sloc
			$this->qt_sloc->ViewValue = $this->qt_sloc->CurrentValue;
			$this->qt_sloc->ViewCustomAttributes = "";

			// qt_slocPf
			$this->qt_slocPf->ViewValue = $this->qt_slocPf->CurrentValue;
			$this->qt_slocPf->ViewCustomAttributes = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->ViewValue = $this->qt_esforcoReal->CurrentValue;
			$this->qt_esforcoReal->ViewCustomAttributes = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->ViewValue = $this->qt_esforcoRealPm->CurrentValue;
			$this->qt_esforcoRealPm->ViewCustomAttributes = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->ViewValue = $this->qt_prazoRealM->CurrentValue;
			$this->qt_prazoRealM->ViewCustomAttributes = "";

			// ic_situacao
			if (strval($this->ic_situacao->CurrentValue) <> "") {
				switch ($this->ic_situacao->CurrentValue) {
					case $this->ic_situacao->FldTagValue(1):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(1) <> "" ? $this->ic_situacao->FldTagCaption(1) : $this->ic_situacao->CurrentValue;
						break;
					case $this->ic_situacao->FldTagValue(2):
						$this->ic_situacao->ViewValue = $this->ic_situacao->FldTagCaption(2) <> "" ? $this->ic_situacao->FldTagCaption(2) : $this->ic_situacao->CurrentValue;
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
			$this->dh_inclusao->ViewValue = ew_FormatDateTime($this->dh_inclusao->ViewValue, 7);
			$this->dh_inclusao->ViewCustomAttributes = "";

			// nu_usuarioAlt
			$this->nu_usuarioAlt->ViewValue = $this->nu_usuarioAlt->CurrentValue;
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
			$this->dh_alteracao->ViewValue = ew_FormatDateTime($this->dh_alteracao->ViewValue, 7);
			$this->dh_alteracao->ViewCustomAttributes = "";

			// nu_projhist
			$this->nu_projhist->LinkCustomAttributes = "";
			$this->nu_projhist->HrefValue = "";
			$this->nu_projhist->TooltipValue = "";

			// nu_ambiente
			$this->nu_ambiente->LinkCustomAttributes = "";
			$this->nu_ambiente->HrefValue = "";
			$this->nu_ambiente->TooltipValue = "";

			// no_projeto
			$this->no_projeto->LinkCustomAttributes = "";
			$this->no_projeto->HrefValue = "";
			$this->no_projeto->TooltipValue = "";

			// ds_projeto
			$this->ds_projeto->LinkCustomAttributes = "";
			$this->ds_projeto->HrefValue = "";
			$this->ds_projeto->TooltipValue = "";

			// qt_pf
			$this->qt_pf->LinkCustomAttributes = "";
			$this->qt_pf->HrefValue = "";
			$this->qt_pf->TooltipValue = "";

			// qt_sloc
			$this->qt_sloc->LinkCustomAttributes = "";
			$this->qt_sloc->HrefValue = "";
			$this->qt_sloc->TooltipValue = "";

			// qt_slocPf
			$this->qt_slocPf->LinkCustomAttributes = "";
			$this->qt_slocPf->HrefValue = "";
			$this->qt_slocPf->TooltipValue = "";

			// qt_esforcoReal
			$this->qt_esforcoReal->LinkCustomAttributes = "";
			$this->qt_esforcoReal->HrefValue = "";
			$this->qt_esforcoReal->TooltipValue = "";

			// qt_esforcoRealPm
			$this->qt_esforcoRealPm->LinkCustomAttributes = "";
			$this->qt_esforcoRealPm->HrefValue = "";
			$this->qt_esforcoRealPm->TooltipValue = "";

			// qt_prazoRealM
			$this->qt_prazoRealM->LinkCustomAttributes = "";
			$this->qt_prazoRealM->HrefValue = "";
			$this->qt_prazoRealM->TooltipValue = "";

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
		$item->Body = "<a id=\"emf_ambiente_phistorico\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_ambiente_phistorico',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.fambiente_phistoricoview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "ambiente_phistoricolist.php", $this->TableVar);
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
if (!isset($ambiente_phistorico_view)) $ambiente_phistorico_view = new cambiente_phistorico_view();

// Page init
$ambiente_phistorico_view->Page_Init();

// Page main
$ambiente_phistorico_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ambiente_phistorico_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($ambiente_phistorico->Export == "") { ?>
<script type="text/javascript">

// Page object
var ambiente_phistorico_view = new ew_Page("ambiente_phistorico_view");
ambiente_phistorico_view.PageID = "view"; // Page ID
var EW_PAGE_ID = ambiente_phistorico_view.PageID; // For backward compatibility

// Form object
var fambiente_phistoricoview = new ew_Form("fambiente_phistoricoview");

// Form_CustomValidate event
fambiente_phistoricoview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fambiente_phistoricoview.ValidateRequired = true;
<?php } else { ?>
fambiente_phistoricoview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fambiente_phistoricoview.Lists["x_nu_ambiente"] = {"LinkField":"x_nu_ambiente","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_ambiente","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_phistoricoview.Lists["x_nu_usuarioInc"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fambiente_phistoricoview.Lists["x_nu_usuarioAlt"] = {"LinkField":"x_nu_usuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($ambiente_phistorico->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($ambiente_phistorico->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $ambiente_phistorico_view->ExportOptions->Render("body") ?>
<?php if (!$ambiente_phistorico_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($ambiente_phistorico_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $ambiente_phistorico_view->ShowPageHeader(); ?>
<?php
$ambiente_phistorico_view->ShowMessage();
?>
<form name="fambiente_phistoricoview" id="fambiente_phistoricoview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="ambiente_phistorico">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_ambiente_phistoricoview" class="table table-bordered table-striped">
<?php if ($ambiente_phistorico->nu_projhist->Visible) { // nu_projhist ?>
	<tr id="r_nu_projhist">
		<td><span id="elh_ambiente_phistorico_nu_projhist"><?php echo $ambiente_phistorico->nu_projhist->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->nu_projhist->CellAttributes() ?>>
<span id="el_ambiente_phistorico_nu_projhist" class="control-group">
<span<?php echo $ambiente_phistorico->nu_projhist->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->nu_projhist->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->nu_ambiente->Visible) { // nu_ambiente ?>
	<tr id="r_nu_ambiente">
		<td><span id="elh_ambiente_phistorico_nu_ambiente"><?php echo $ambiente_phistorico->nu_ambiente->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->nu_ambiente->CellAttributes() ?>>
<span id="el_ambiente_phistorico_nu_ambiente" class="control-group">
<span<?php echo $ambiente_phistorico->nu_ambiente->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->nu_ambiente->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->no_projeto->Visible) { // no_projeto ?>
	<tr id="r_no_projeto">
		<td><span id="elh_ambiente_phistorico_no_projeto"><?php echo $ambiente_phistorico->no_projeto->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->no_projeto->CellAttributes() ?>>
<span id="el_ambiente_phistorico_no_projeto" class="control-group">
<span<?php echo $ambiente_phistorico->no_projeto->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->no_projeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->ds_projeto->Visible) { // ds_projeto ?>
	<tr id="r_ds_projeto">
		<td><span id="elh_ambiente_phistorico_ds_projeto"><?php echo $ambiente_phistorico->ds_projeto->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->ds_projeto->CellAttributes() ?>>
<span id="el_ambiente_phistorico_ds_projeto" class="control-group">
<span<?php echo $ambiente_phistorico->ds_projeto->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->ds_projeto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_pf->Visible) { // qt_pf ?>
	<tr id="r_qt_pf">
		<td><span id="elh_ambiente_phistorico_qt_pf"><?php echo $ambiente_phistorico->qt_pf->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_pf->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_pf" class="control-group">
<span<?php echo $ambiente_phistorico->qt_pf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_pf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_sloc->Visible) { // qt_sloc ?>
	<tr id="r_qt_sloc">
		<td><span id="elh_ambiente_phistorico_qt_sloc"><?php echo $ambiente_phistorico->qt_sloc->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_sloc->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_sloc" class="control-group">
<span<?php echo $ambiente_phistorico->qt_sloc->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_sloc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_slocPf->Visible) { // qt_slocPf ?>
	<tr id="r_qt_slocPf">
		<td><span id="elh_ambiente_phistorico_qt_slocPf"><?php echo $ambiente_phistorico->qt_slocPf->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_slocPf->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_slocPf" class="control-group">
<span<?php echo $ambiente_phistorico->qt_slocPf->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_slocPf->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_esforcoReal->Visible) { // qt_esforcoReal ?>
	<tr id="r_qt_esforcoReal">
		<td><span id="elh_ambiente_phistorico_qt_esforcoReal"><?php echo $ambiente_phistorico->qt_esforcoReal->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_esforcoReal->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_esforcoReal" class="control-group">
<span<?php echo $ambiente_phistorico->qt_esforcoReal->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoReal->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_esforcoRealPm->Visible) { // qt_esforcoRealPm ?>
	<tr id="r_qt_esforcoRealPm">
		<td><span id="elh_ambiente_phistorico_qt_esforcoRealPm"><?php echo $ambiente_phistorico->qt_esforcoRealPm->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_esforcoRealPm->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_esforcoRealPm" class="control-group">
<span<?php echo $ambiente_phistorico->qt_esforcoRealPm->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_esforcoRealPm->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->qt_prazoRealM->Visible) { // qt_prazoRealM ?>
	<tr id="r_qt_prazoRealM">
		<td><span id="elh_ambiente_phistorico_qt_prazoRealM"><?php echo $ambiente_phistorico->qt_prazoRealM->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->qt_prazoRealM->CellAttributes() ?>>
<span id="el_ambiente_phistorico_qt_prazoRealM" class="control-group">
<span<?php echo $ambiente_phistorico->qt_prazoRealM->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->qt_prazoRealM->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->ic_situacao->Visible) { // ic_situacao ?>
	<tr id="r_ic_situacao">
		<td><span id="elh_ambiente_phistorico_ic_situacao"><?php echo $ambiente_phistorico->ic_situacao->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->ic_situacao->CellAttributes() ?>>
<span id="el_ambiente_phistorico_ic_situacao" class="control-group">
<span<?php echo $ambiente_phistorico->ic_situacao->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->ic_situacao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->ds_acoes->Visible) { // ds_acoes ?>
	<tr id="r_ds_acoes">
		<td><span id="elh_ambiente_phistorico_ds_acoes"><?php echo $ambiente_phistorico->ds_acoes->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->ds_acoes->CellAttributes() ?>>
<span id="el_ambiente_phistorico_ds_acoes" class="control-group">
<span<?php echo $ambiente_phistorico->ds_acoes->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->ds_acoes->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->nu_usuarioInc->Visible) { // nu_usuarioInc ?>
	<tr id="r_nu_usuarioInc">
		<td><span id="elh_ambiente_phistorico_nu_usuarioInc"><?php echo $ambiente_phistorico->nu_usuarioInc->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->nu_usuarioInc->CellAttributes() ?>>
<span id="el_ambiente_phistorico_nu_usuarioInc" class="control-group">
<span<?php echo $ambiente_phistorico->nu_usuarioInc->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->nu_usuarioInc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->dh_inclusao->Visible) { // dh_inclusao ?>
	<tr id="r_dh_inclusao">
		<td><span id="elh_ambiente_phistorico_dh_inclusao"><?php echo $ambiente_phistorico->dh_inclusao->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->dh_inclusao->CellAttributes() ?>>
<span id="el_ambiente_phistorico_dh_inclusao" class="control-group">
<span<?php echo $ambiente_phistorico->dh_inclusao->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->dh_inclusao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->nu_usuarioAlt->Visible) { // nu_usuarioAlt ?>
	<tr id="r_nu_usuarioAlt">
		<td><span id="elh_ambiente_phistorico_nu_usuarioAlt"><?php echo $ambiente_phistorico->nu_usuarioAlt->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->nu_usuarioAlt->CellAttributes() ?>>
<span id="el_ambiente_phistorico_nu_usuarioAlt" class="control-group">
<span<?php echo $ambiente_phistorico->nu_usuarioAlt->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->nu_usuarioAlt->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($ambiente_phistorico->dh_alteracao->Visible) { // dh_alteracao ?>
	<tr id="r_dh_alteracao">
		<td><span id="elh_ambiente_phistorico_dh_alteracao"><?php echo $ambiente_phistorico->dh_alteracao->FldCaption() ?></span></td>
		<td<?php echo $ambiente_phistorico->dh_alteracao->CellAttributes() ?>>
<span id="el_ambiente_phistorico_dh_alteracao" class="control-group">
<span<?php echo $ambiente_phistorico->dh_alteracao->ViewAttributes() ?>>
<?php echo $ambiente_phistorico->dh_alteracao->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php if ($ambiente_phistorico->Export == "") { ?>
<table class="ewPager">
<tr><td>
<?php if (!isset($ambiente_phistorico_view->Pager)) $ambiente_phistorico_view->Pager = new cNumericPager($ambiente_phistorico_view->StartRec, $ambiente_phistorico_view->DisplayRecs, $ambiente_phistorico_view->TotalRecs, $ambiente_phistorico_view->RecRange) ?>
<?php if ($ambiente_phistorico_view->Pager->RecordCount > 0) { ?>
<table cellspacing="0" class="ewStdTable"><tbody><tr><td>
<div class="pagination"><ul>
	<?php if ($ambiente_phistorico_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_phistorico_view->PageUrl() ?>start=<?php echo $ambiente_phistorico_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($ambiente_phistorico_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_phistorico_view->PageUrl() ?>start=<?php echo $ambiente_phistorico_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($ambiente_phistorico_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $ambiente_phistorico_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($ambiente_phistorico_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_phistorico_view->PageUrl() ?>start=<?php echo $ambiente_phistorico_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($ambiente_phistorico_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $ambiente_phistorico_view->PageUrl() ?>start=<?php echo $ambiente_phistorico_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
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
fambiente_phistoricoview.Init();
</script>
<?php
$ambiente_phistorico_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($ambiente_phistorico->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$ambiente_phistorico_view->Page_Terminate();
?>
