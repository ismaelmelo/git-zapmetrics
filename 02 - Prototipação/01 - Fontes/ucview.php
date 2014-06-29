<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "ucinfo.php" ?>
<?php include_once "sistemainfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "uc_atorgridcls.php" ?>
<?php include_once "uc_mensagemgridcls.php" ?>
<?php include_once "uc_regranegociogridcls.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$uc_view = NULL; // Initialize page object first

class cuc_view extends cuc {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'uc';

	// Page object name
	var $PageObjName = 'uc_view';

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

		// Table object (uc)
		if (!isset($GLOBALS["uc"])) {
			$GLOBALS["uc"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["uc"];
		}
		$KeyUrl = "";
		if (@$_GET["nu_uc"] <> "") {
			$this->RecKey["nu_uc"] = $_GET["nu_uc"];
			$KeyUrl .= "&nu_uc=" . urlencode($this->RecKey["nu_uc"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (sistema)
		if (!isset($GLOBALS['sistema'])) $GLOBALS['sistema'] = new csistema();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'uc', TRUE);

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
			$this->Page_Terminate("uclist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up curent action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

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
			if (@$_GET["nu_uc"] <> "") {
				$this->nu_uc->setQueryStringValue($_GET["nu_uc"]);
				$this->RecKey["nu_uc"] = $this->nu_uc->QueryStringValue;
			} else {
				$sReturnUrl = "uclist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "uclist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "uclist.php"; // Not page request, return to list
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

		// Detail table 'uc_ator'
		$body = $Language->TablePhrase("uc_ator", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("uc_atorlist.php?" . EW_TABLE_SHOW_MASTER . "=uc&nu_uc=" . strval($this->nu_uc->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_uc_ator");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'uc_ator');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "uc_ator";
		}

		// Detail table 'uc_mensagem'
		$body = $Language->TablePhrase("uc_mensagem", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("uc_mensagemlist.php?" . EW_TABLE_SHOW_MASTER . "=uc&nu_uc=" . strval($this->nu_uc->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_uc_mensagem");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'uc_mensagem');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "uc_mensagem";
		}

		// Detail table 'uc_regranegocio'
		$body = $Language->TablePhrase("uc_regranegocio", "TblCaption");
		$body = "<a class=\"ewAction ewDetailList\" href=\"" . ew_HtmlEncode("uc_regranegociolist.php?" . EW_TABLE_SHOW_MASTER . "=uc&nu_uc=" . strval($this->nu_uc->CurrentValue) . "") . "\">" . $body . "</a>";
		$item = &$option->Add("detail_uc_regranegocio");
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'uc_regranegocio');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "uc_regranegocio";
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
		$this->nu_uc->setDbValue($rs->fields('nu_uc'));
		$this->nu_sistema->setDbValue($rs->fields('nu_sistema'));
		$this->nu_modulo->setDbValue($rs->fields('nu_modulo'));
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->no_uc->setDbValue($rs->fields('no_uc'));
		$this->ds_uc->setDbValue($rs->fields('ds_uc'));
		$this->nu_stUc->setDbValue($rs->fields('nu_stUc'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_uc->DbValue = $row['nu_uc'];
		$this->nu_sistema->DbValue = $row['nu_sistema'];
		$this->nu_modulo->DbValue = $row['nu_modulo'];
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->no_uc->DbValue = $row['no_uc'];
		$this->ds_uc->DbValue = $row['ds_uc'];
		$this->nu_stUc->DbValue = $row['nu_stUc'];
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
		// nu_uc
		// nu_sistema
		// nu_modulo
		// co_alternativo
		// no_uc
		// ds_uc
		// nu_stUc

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_sistema
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
			$this->nu_sistema->ViewCustomAttributes = "";

			// nu_modulo
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
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
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
			$this->nu_modulo->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
			$this->co_alternativo->ViewCustomAttributes = "";

			// no_uc
			$this->no_uc->ViewValue = $this->no_uc->CurrentValue;
			$this->no_uc->ViewCustomAttributes = "";

			// ds_uc
			$this->ds_uc->ViewValue = $this->ds_uc->CurrentValue;
			$this->ds_uc->ViewCustomAttributes = "";

			// nu_stUc
			if (strval($this->nu_stUc->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stUc]" . ew_SearchString("=", $this->nu_stUc->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stUc], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stuc]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stUc, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stUc->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stUc->ViewValue = $this->nu_stUc->CurrentValue;
				}
			} else {
				$this->nu_stUc->ViewValue = NULL;
			}
			$this->nu_stUc->ViewCustomAttributes = "";

			// nu_sistema
			$this->nu_sistema->LinkCustomAttributes = "";
			$this->nu_sistema->HrefValue = "";
			$this->nu_sistema->TooltipValue = "";

			// nu_modulo
			$this->nu_modulo->LinkCustomAttributes = "";
			$this->nu_modulo->HrefValue = "";
			$this->nu_modulo->TooltipValue = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// no_uc
			$this->no_uc->LinkCustomAttributes = "";
			$this->no_uc->HrefValue = "";
			$this->no_uc->TooltipValue = "";

			// ds_uc
			$this->ds_uc->LinkCustomAttributes = "";
			$this->ds_uc->HrefValue = "";
			$this->ds_uc->TooltipValue = "";

			// nu_stUc
			$this->nu_stUc->LinkCustomAttributes = "";
			$this->nu_stUc->HrefValue = "";
			$this->nu_stUc->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
			if (in_array("uc_ator", $DetailTblVar)) {
				if (!isset($GLOBALS["uc_ator_grid"]))
					$GLOBALS["uc_ator_grid"] = new cuc_ator_grid;
				if ($GLOBALS["uc_ator_grid"]->DetailView) {
					$GLOBALS["uc_ator_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["uc_ator_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["uc_ator_grid"]->setStartRecordNumber(1);
					$GLOBALS["uc_ator_grid"]->nu_uc->FldIsDetailKey = TRUE;
					$GLOBALS["uc_ator_grid"]->nu_uc->CurrentValue = $this->nu_uc->CurrentValue;
					$GLOBALS["uc_ator_grid"]->nu_uc->setSessionValue($GLOBALS["uc_ator_grid"]->nu_uc->CurrentValue);
				}
			}
			if (in_array("uc_mensagem", $DetailTblVar)) {
				if (!isset($GLOBALS["uc_mensagem_grid"]))
					$GLOBALS["uc_mensagem_grid"] = new cuc_mensagem_grid;
				if ($GLOBALS["uc_mensagem_grid"]->DetailView) {
					$GLOBALS["uc_mensagem_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["uc_mensagem_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["uc_mensagem_grid"]->setStartRecordNumber(1);
					$GLOBALS["uc_mensagem_grid"]->nu_uc->FldIsDetailKey = TRUE;
					$GLOBALS["uc_mensagem_grid"]->nu_uc->CurrentValue = $this->nu_uc->CurrentValue;
					$GLOBALS["uc_mensagem_grid"]->nu_uc->setSessionValue($GLOBALS["uc_mensagem_grid"]->nu_uc->CurrentValue);
				}
			}
			if (in_array("uc_regranegocio", $DetailTblVar)) {
				if (!isset($GLOBALS["uc_regranegocio_grid"]))
					$GLOBALS["uc_regranegocio_grid"] = new cuc_regranegocio_grid;
				if ($GLOBALS["uc_regranegocio_grid"]->DetailView) {
					$GLOBALS["uc_regranegocio_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["uc_regranegocio_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["uc_regranegocio_grid"]->setStartRecordNumber(1);
					$GLOBALS["uc_regranegocio_grid"]->nu_uc->FldIsDetailKey = TRUE;
					$GLOBALS["uc_regranegocio_grid"]->nu_uc->CurrentValue = $this->nu_uc->CurrentValue;
					$GLOBALS["uc_regranegocio_grid"]->nu_uc->setSessionValue($GLOBALS["uc_regranegocio_grid"]->nu_uc->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "uclist.php", $this->TableVar);
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
if (!isset($uc_view)) $uc_view = new cuc_view();

// Page init
$uc_view->Page_Init();

// Page main
$uc_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$uc_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var uc_view = new ew_Page("uc_view");
uc_view.PageID = "view"; // Page ID
var EW_PAGE_ID = uc_view.PageID; // For backward compatibility

// Form object
var fucview = new ew_Form("fucview");

// Form_CustomValidate event
fucview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fucview.ValidateRequired = true;
<?php } else { ?>
fucview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fucview.Lists["x_nu_sistema"] = {"LinkField":"x_nu_sistema","Ajax":true,"AutoFill":false,"DisplayFields":["x_co_alternativo","x_no_sistema","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fucview.Lists["x_nu_modulo"] = {"LinkField":"x_nu_modulo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_modulo","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fucview.Lists["x_nu_stUc"] = {"LinkField":"x_nu_stUc","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<div class="ewViewExportOptions">
<?php $uc_view->ExportOptions->Render("body") ?>
<?php if (!$uc_view->ExportOptions->UseDropDownButton) { ?>
</div>
<div class="ewViewOtherOptions">
<?php } ?>
<?php
	foreach ($uc_view->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<?php $uc_view->ShowPageHeader(); ?>
<?php
$uc_view->ShowMessage();
?>
<form name="fucview" id="fucview" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="uc">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_ucview" class="table table-bordered table-striped">
<?php if ($uc->nu_sistema->Visible) { // nu_sistema ?>
	<tr id="r_nu_sistema">
		<td><span id="elh_uc_nu_sistema"><?php echo $uc->nu_sistema->FldCaption() ?></span></td>
		<td<?php echo $uc->nu_sistema->CellAttributes() ?>>
<span id="el_uc_nu_sistema" class="control-group">
<span<?php echo $uc->nu_sistema->ViewAttributes() ?>>
<?php echo $uc->nu_sistema->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($uc->nu_modulo->Visible) { // nu_modulo ?>
	<tr id="r_nu_modulo">
		<td><span id="elh_uc_nu_modulo"><?php echo $uc->nu_modulo->FldCaption() ?></span></td>
		<td<?php echo $uc->nu_modulo->CellAttributes() ?>>
<span id="el_uc_nu_modulo" class="control-group">
<span<?php echo $uc->nu_modulo->ViewAttributes() ?>>
<?php echo $uc->nu_modulo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($uc->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_uc_co_alternativo"><?php echo $uc->co_alternativo->FldCaption() ?></span></td>
		<td<?php echo $uc->co_alternativo->CellAttributes() ?>>
<span id="el_uc_co_alternativo" class="control-group">
<span<?php echo $uc->co_alternativo->ViewAttributes() ?>>
<?php echo $uc->co_alternativo->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($uc->no_uc->Visible) { // no_uc ?>
	<tr id="r_no_uc">
		<td><span id="elh_uc_no_uc"><?php echo $uc->no_uc->FldCaption() ?></span></td>
		<td<?php echo $uc->no_uc->CellAttributes() ?>>
<span id="el_uc_no_uc" class="control-group">
<span<?php echo $uc->no_uc->ViewAttributes() ?>>
<?php echo $uc->no_uc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($uc->ds_uc->Visible) { // ds_uc ?>
	<tr id="r_ds_uc">
		<td><span id="elh_uc_ds_uc"><?php echo $uc->ds_uc->FldCaption() ?></span></td>
		<td<?php echo $uc->ds_uc->CellAttributes() ?>>
<span id="el_uc_ds_uc" class="control-group">
<span<?php echo $uc->ds_uc->ViewAttributes() ?>>
<?php echo $uc->ds_uc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($uc->nu_stUc->Visible) { // nu_stUc ?>
	<tr id="r_nu_stUc">
		<td><span id="elh_uc_nu_stUc"><?php echo $uc->nu_stUc->FldCaption() ?></span></td>
		<td<?php echo $uc->nu_stUc->CellAttributes() ?>>
<span id="el_uc_nu_stUc" class="control-group">
<span<?php echo $uc->nu_stUc->ViewAttributes() ?>>
<?php echo $uc->nu_stUc->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<?php
	if (in_array("uc_ator", explode(",", $uc->getCurrentDetailTable())) && $uc_ator->DetailView) {
?>
<?php include_once "uc_atorgrid.php" ?>
<?php } ?>
<?php
	if (in_array("uc_mensagem", explode(",", $uc->getCurrentDetailTable())) && $uc_mensagem->DetailView) {
?>
<?php include_once "uc_mensagemgrid.php" ?>
<?php } ?>
<?php
	if (in_array("uc_regranegocio", explode(",", $uc->getCurrentDetailTable())) && $uc_regranegocio->DetailView) {
?>
<?php include_once "uc_regranegociogrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fucview.Init();
</script>
<?php
$uc_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$uc_view->Page_Terminate();
?>
