<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "rdm_tarefainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$rdm_tarefa_view = NULL; // Initialize page object first

class crdm_tarefa_view extends crdm_tarefa {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{0602B820-DE72-4661-BB21-3716ACE9CB5F}";

	// Table name
	var $TableName = 'rdm_tarefa';

	// Page object name
	var $PageObjName = 'rdm_tarefa_view';

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

		// Table object (rdm_tarefa)
		if (!isset($GLOBALS["rdm_tarefa"])) {
			$GLOBALS["rdm_tarefa"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["rdm_tarefa"];
		}
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&id=" . urlencode($this->RecKey["id"]);
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
			define("EW_TABLE_NAME", 'rdm_tarefa', TRUE);

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
			$this->Page_Terminate("rdm_tarefalist.php");
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
		if (@$_GET["id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["id"]);
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
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} else {
				$sReturnUrl = "rdm_tarefalist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "rdm_tarefalist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if (in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "rdm_tarefalist.php"; // Not page request, return to list
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

		// Set up options default
		foreach ($options as &$option) {
			$option->UseDropDownButton = FALSE;
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
		$this->id->setDbValue($rs->fields('id'));
		$this->project_id->setDbValue($rs->fields('project_id'));
		$this->tracker_id->setDbValue($rs->fields('tracker_id'));
		$this->subject->setDbValue($rs->fields('subject'));
		$this->author_id->setDbValue($rs->fields('author_id'));
		$this->status_id->setDbValue($rs->fields('status_id'));
		$this->priority_id->setDbValue($rs->fields('priority_id'));
		$this->assigned_to->setDbValue($rs->fields('assigned_to'));
		$this->start_date->setDbValue($rs->fields('start_date'));
		$this->done_ratio->setDbValue($rs->fields('done_ratio'));
		$this->created_on->setDbValue($rs->fields('created_on'));
		$this->updated_on->setDbValue($rs->fields('updated_on'));
		$this->due_date->setDbValue($rs->fields('due_date'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->project_id->DbValue = $row['project_id'];
		$this->tracker_id->DbValue = $row['tracker_id'];
		$this->subject->DbValue = $row['subject'];
		$this->author_id->DbValue = $row['author_id'];
		$this->status_id->DbValue = $row['status_id'];
		$this->priority_id->DbValue = $row['priority_id'];
		$this->assigned_to->DbValue = $row['assigned_to'];
		$this->start_date->DbValue = $row['start_date'];
		$this->done_ratio->DbValue = $row['done_ratio'];
		$this->created_on->DbValue = $row['created_on'];
		$this->updated_on->DbValue = $row['updated_on'];
		$this->due_date->DbValue = $row['due_date'];
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
		// id
		// project_id
		// tracker_id
		// subject
		// author_id
		// status_id
		// priority_id
		// assigned_to
		// start_date
		// done_ratio
		// created_on
		// updated_on
		// due_date

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// project_id
			if (strval($this->project_id->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->project_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->project_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->project_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->project_id->ViewValue = $this->project_id->CurrentValue;
				}
			} else {
				$this->project_id->ViewValue = NULL;
			}
			$this->project_id->ViewCustomAttributes = "";

			// tracker_id
			if (strval($this->tracker_id->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->tracker_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_rastreador]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->tracker_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->tracker_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->tracker_id->ViewValue = $this->tracker_id->CurrentValue;
				}
			} else {
				$this->tracker_id->ViewValue = NULL;
			}
			$this->tracker_id->ViewCustomAttributes = "";

			// subject
			$this->subject->ViewValue = $this->subject->CurrentValue;
			$this->subject->ViewCustomAttributes = "";

			// author_id
			if (strval($this->author_id->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->author_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->author_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->author_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->author_id->ViewValue = $this->author_id->CurrentValue;
				}
			} else {
				$this->author_id->ViewValue = NULL;
			}
			$this->author_id->ViewCustomAttributes = "";

			// status_id
			$this->status_id->ViewValue = $this->status_id->CurrentValue;
			$this->status_id->ViewCustomAttributes = "";

			// priority_id
			if (strval($this->priority_id->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->priority_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_prioridade]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->priority_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->priority_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->priority_id->ViewValue = $this->priority_id->CurrentValue;
				}
			} else {
				$this->priority_id->ViewValue = NULL;
			}
			$this->priority_id->ViewCustomAttributes = "";

			// assigned_to
			if (strval($this->assigned_to->CurrentValue) <> "") {
				$sFilterWrk = "[id]" . ew_SearchString("=", $this->assigned_to->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [id], [name] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[rdm_usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->assigned_to, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->assigned_to->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->assigned_to->ViewValue = $this->assigned_to->CurrentValue;
				}
			} else {
				$this->assigned_to->ViewValue = NULL;
			}
			$this->assigned_to->ViewCustomAttributes = "";

			// start_date
			$this->start_date->ViewValue = $this->start_date->CurrentValue;
			$this->start_date->ViewValue = ew_FormatDateTime($this->start_date->ViewValue, 14);
			$this->start_date->ViewCustomAttributes = "";

			// done_ratio
			$this->done_ratio->ViewValue = $this->done_ratio->CurrentValue;
			$this->done_ratio->ViewCustomAttributes = "";

			// created_on
			$this->created_on->ViewValue = $this->created_on->CurrentValue;
			$this->created_on->ViewValue = ew_FormatDateTime($this->created_on->ViewValue, 17);
			$this->created_on->ViewCustomAttributes = "";

			// updated_on
			$this->updated_on->ViewValue = $this->updated_on->CurrentValue;
			$this->updated_on->ViewValue = ew_FormatDateTime($this->updated_on->ViewValue, 17);
			$this->updated_on->ViewCustomAttributes = "";

			// due_date
			$this->due_date->ViewValue = $this->due_date->CurrentValue;
			$this->due_date->ViewValue = ew_FormatDateTime($this->due_date->ViewValue, 7);
			$this->due_date->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// project_id
			$this->project_id->LinkCustomAttributes = "";
			$this->project_id->HrefValue = "";
			$this->project_id->TooltipValue = "";

			// tracker_id
			$this->tracker_id->LinkCustomAttributes = "";
			$this->tracker_id->HrefValue = "";
			$this->tracker_id->TooltipValue = "";

			// subject
			$this->subject->LinkCustomAttributes = "";
			$this->subject->HrefValue = "";
			$this->subject->TooltipValue = "";

			// author_id
			$this->author_id->LinkCustomAttributes = "";
			$this->author_id->HrefValue = "";
			$this->author_id->TooltipValue = "";

			// status_id
			$this->status_id->LinkCustomAttributes = "";
			$this->status_id->HrefValue = "";
			$this->status_id->TooltipValue = "";

			// priority_id
			$this->priority_id->LinkCustomAttributes = "";
			$this->priority_id->HrefValue = "";
			$this->priority_id->TooltipValue = "";

			// assigned_to
			$this->assigned_to->LinkCustomAttributes = "";
			$this->assigned_to->HrefValue = "";
			$this->assigned_to->TooltipValue = "";

			// start_date
			$this->start_date->LinkCustomAttributes = "";
			$this->start_date->HrefValue = "";
			$this->start_date->TooltipValue = "";

			// done_ratio
			$this->done_ratio->LinkCustomAttributes = "";
			$this->done_ratio->HrefValue = "";
			$this->done_ratio->TooltipValue = "";

			// created_on
			$this->created_on->LinkCustomAttributes = "";
			$this->created_on->HrefValue = "";
			$this->created_on->TooltipValue = "";

			// updated_on
			$this->updated_on->LinkCustomAttributes = "";
			$this->updated_on->HrefValue = "";
			$this->updated_on->TooltipValue = "";

			// due_date
			$this->due_date->LinkCustomAttributes = "";
			$this->due_date->HrefValue = "";
			$this->due_date->TooltipValue = "";
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
		$item->Body = "<a id=\"emf_rdm_tarefa\" href=\"javascript:void(0);\" class=\"ewExportLink ewEmail\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_rdm_tarefa',hdr:ewLanguage.Phrase('ExportToEmail'),f:document.frdm_tarefaview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false});\">" . $Language->Phrase("ExportToEmail") . "</a>";
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
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "rdm_tarefalist.php", $this->TableVar);
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
if (!isset($rdm_tarefa_view)) $rdm_tarefa_view = new crdm_tarefa_view();

// Page init
$rdm_tarefa_view->Page_Init();

// Page main
$rdm_tarefa_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$rdm_tarefa_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($rdm_tarefa->Export == "") { ?>
<script type="text/javascript">

// Page object
var rdm_tarefa_view = new ew_Page("rdm_tarefa_view");
rdm_tarefa_view.PageID = "view"; // Page ID
var EW_PAGE_ID = rdm_tarefa_view.PageID; // For backward compatibility

// Form object
var frdm_tarefaview = new ew_Form("frdm_tarefaview");

// Form_CustomValidate event
frdm_tarefaview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
frdm_tarefaview.ValidateRequired = true;
<?php } else { ?>
frdm_tarefaview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
frdm_tarefaview.Lists["x_project_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frdm_tarefaview.Lists["x_tracker_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frdm_tarefaview.Lists["x_author_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frdm_tarefaview.Lists["x_priority_id"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
frdm_tarefaview.Lists["x_assigned_to"] = {"LinkField":"x_id","Ajax":null,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($rdm_tarefa->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($rdm_tarefa->Export == "") { ?>
<div class="ewViewExportOptions">
<?php $rdm_tarefa_view->ExportOptions->Render("body") ?>
<?php if (!$rdm_tarefa_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($rdm_tarefa_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php } ?>
<?php $rdm_tarefa_view->ShowPageHeader(); ?>
<?php
$rdm_tarefa_view->ShowMessage();
?>
<form name="frdm_tarefaview" id="frdm_tarefaview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="rdm_tarefa">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_rdm_tarefaview" class="table table-bordered table-striped">
<?php if ($rdm_tarefa->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_rdm_tarefa_id"><?php echo $rdm_tarefa->id->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->id->CellAttributes() ?>>
<span id="el_rdm_tarefa_id" class="control-group">
<span<?php echo $rdm_tarefa->id->ViewAttributes() ?>>
<?php echo $rdm_tarefa->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->project_id->Visible) { // project_id ?>
	<tr id="r_project_id">
		<td><span id="elh_rdm_tarefa_project_id"><?php echo $rdm_tarefa->project_id->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->project_id->CellAttributes() ?>>
<span id="el_rdm_tarefa_project_id" class="control-group">
<span<?php echo $rdm_tarefa->project_id->ViewAttributes() ?>>
<?php echo $rdm_tarefa->project_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->tracker_id->Visible) { // tracker_id ?>
	<tr id="r_tracker_id">
		<td><span id="elh_rdm_tarefa_tracker_id"><?php echo $rdm_tarefa->tracker_id->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->tracker_id->CellAttributes() ?>>
<span id="el_rdm_tarefa_tracker_id" class="control-group">
<span<?php echo $rdm_tarefa->tracker_id->ViewAttributes() ?>>
<?php echo $rdm_tarefa->tracker_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->subject->Visible) { // subject ?>
	<tr id="r_subject">
		<td><span id="elh_rdm_tarefa_subject"><?php echo $rdm_tarefa->subject->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->subject->CellAttributes() ?>>
<span id="el_rdm_tarefa_subject" class="control-group">
<span<?php echo $rdm_tarefa->subject->ViewAttributes() ?>>
<?php echo $rdm_tarefa->subject->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->author_id->Visible) { // author_id ?>
	<tr id="r_author_id">
		<td><span id="elh_rdm_tarefa_author_id"><?php echo $rdm_tarefa->author_id->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->author_id->CellAttributes() ?>>
<span id="el_rdm_tarefa_author_id" class="control-group">
<span<?php echo $rdm_tarefa->author_id->ViewAttributes() ?>>
<?php echo $rdm_tarefa->author_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->status_id->Visible) { // status_id ?>
	<tr id="r_status_id">
		<td><span id="elh_rdm_tarefa_status_id"><?php echo $rdm_tarefa->status_id->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->status_id->CellAttributes() ?>>
<span id="el_rdm_tarefa_status_id" class="control-group">
<span<?php echo $rdm_tarefa->status_id->ViewAttributes() ?>>
<?php echo $rdm_tarefa->status_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->priority_id->Visible) { // priority_id ?>
	<tr id="r_priority_id">
		<td><span id="elh_rdm_tarefa_priority_id"><?php echo $rdm_tarefa->priority_id->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->priority_id->CellAttributes() ?>>
<span id="el_rdm_tarefa_priority_id" class="control-group">
<span<?php echo $rdm_tarefa->priority_id->ViewAttributes() ?>>
<?php echo $rdm_tarefa->priority_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->assigned_to->Visible) { // assigned_to ?>
	<tr id="r_assigned_to">
		<td><span id="elh_rdm_tarefa_assigned_to"><?php echo $rdm_tarefa->assigned_to->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->assigned_to->CellAttributes() ?>>
<span id="el_rdm_tarefa_assigned_to" class="control-group">
<span<?php echo $rdm_tarefa->assigned_to->ViewAttributes() ?>>
<?php echo $rdm_tarefa->assigned_to->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->start_date->Visible) { // start_date ?>
	<tr id="r_start_date">
		<td><span id="elh_rdm_tarefa_start_date"><?php echo $rdm_tarefa->start_date->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->start_date->CellAttributes() ?>>
<span id="el_rdm_tarefa_start_date" class="control-group">
<span<?php echo $rdm_tarefa->start_date->ViewAttributes() ?>>
<?php echo $rdm_tarefa->start_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->done_ratio->Visible) { // done_ratio ?>
	<tr id="r_done_ratio">
		<td><span id="elh_rdm_tarefa_done_ratio"><?php echo $rdm_tarefa->done_ratio->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->done_ratio->CellAttributes() ?>>
<span id="el_rdm_tarefa_done_ratio" class="control-group">
<span<?php echo $rdm_tarefa->done_ratio->ViewAttributes() ?>>
<?php echo $rdm_tarefa->done_ratio->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->created_on->Visible) { // created_on ?>
	<tr id="r_created_on">
		<td><span id="elh_rdm_tarefa_created_on"><?php echo $rdm_tarefa->created_on->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->created_on->CellAttributes() ?>>
<span id="el_rdm_tarefa_created_on" class="control-group">
<span<?php echo $rdm_tarefa->created_on->ViewAttributes() ?>>
<?php echo $rdm_tarefa->created_on->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->updated_on->Visible) { // updated_on ?>
	<tr id="r_updated_on">
		<td><span id="elh_rdm_tarefa_updated_on"><?php echo $rdm_tarefa->updated_on->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->updated_on->CellAttributes() ?>>
<span id="el_rdm_tarefa_updated_on" class="control-group">
<span<?php echo $rdm_tarefa->updated_on->ViewAttributes() ?>>
<?php echo $rdm_tarefa->updated_on->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($rdm_tarefa->due_date->Visible) { // due_date ?>
	<tr id="r_due_date">
		<td><span id="elh_rdm_tarefa_due_date"><?php echo $rdm_tarefa->due_date->FldCaption() ?></span></td>
		<td<?php echo $rdm_tarefa->due_date->CellAttributes() ?>>
<span id="el_rdm_tarefa_due_date" class="control-group">
<span<?php echo $rdm_tarefa->due_date->ViewAttributes() ?>>
<?php echo $rdm_tarefa->due_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
</form>
<script type="text/javascript">
frdm_tarefaview.Init();
</script>
<?php
$rdm_tarefa_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($rdm_tarefa->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$rdm_tarefa_view->Page_Terminate();
?>
