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

$necti_add = NULL; // Initialize page object first

class cnecti_add extends cnecti {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'necti';

	// Page object name
	var $PageObjName = 'necti_add';

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

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'necti', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("nectilist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["nu_necTi"] != "") {
				$this->nu_necTi->setQueryStringValue($_GET["nu_necTi"]);
				$this->setKey("nu_necTi", $this->nu_necTi->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_necTi", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("nectilist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "nectiview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->nu_periodoPei->CurrentValue = NULL;
		$this->nu_periodoPei->OldValue = $this->nu_periodoPei->CurrentValue;
		$this->nu_periodoPdti->CurrentValue = NULL;
		$this->nu_periodoPdti->OldValue = $this->nu_periodoPdti->CurrentValue;
		$this->nu_tpNecTi->CurrentValue = NULL;
		$this->nu_tpNecTi->OldValue = $this->nu_tpNecTi->CurrentValue;
		$this->ic_tpNec->CurrentValue = NULL;
		$this->ic_tpNec->OldValue = $this->ic_tpNec->CurrentValue;
		$this->nu_metaneg->CurrentValue = NULL;
		$this->nu_metaneg->OldValue = $this->nu_metaneg->CurrentValue;
		$this->nu_origem->CurrentValue = NULL;
		$this->nu_origem->OldValue = $this->nu_origem->CurrentValue;
		$this->nu_area->CurrentValue = NULL;
		$this->nu_area->OldValue = $this->nu_area->CurrentValue;
		$this->ic_gravidade->CurrentValue = NULL;
		$this->ic_gravidade->OldValue = $this->ic_gravidade->CurrentValue;
		$this->ic_urgencia->CurrentValue = NULL;
		$this->ic_urgencia->OldValue = $this->ic_urgencia->CurrentValue;
		$this->ic_tendencia->CurrentValue = NULL;
		$this->ic_tendencia->OldValue = $this->ic_tendencia->CurrentValue;
		$this->ic_prioridade->CurrentValue = NULL;
		$this->ic_prioridade->OldValue = $this->ic_prioridade->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->nu_periodoPei->FldIsDetailKey) {
			$this->nu_periodoPei->setFormValue($objForm->GetValue("x_nu_periodoPei"));
		}
		if (!$this->nu_periodoPdti->FldIsDetailKey) {
			$this->nu_periodoPdti->setFormValue($objForm->GetValue("x_nu_periodoPdti"));
		}
		if (!$this->nu_tpNecTi->FldIsDetailKey) {
			$this->nu_tpNecTi->setFormValue($objForm->GetValue("x_nu_tpNecTi"));
		}
		if (!$this->ic_tpNec->FldIsDetailKey) {
			$this->ic_tpNec->setFormValue($objForm->GetValue("x_ic_tpNec"));
		}
		if (!$this->nu_metaneg->FldIsDetailKey) {
			$this->nu_metaneg->setFormValue($objForm->GetValue("x_nu_metaneg"));
		}
		if (!$this->nu_origem->FldIsDetailKey) {
			$this->nu_origem->setFormValue($objForm->GetValue("x_nu_origem"));
		}
		if (!$this->nu_area->FldIsDetailKey) {
			$this->nu_area->setFormValue($objForm->GetValue("x_nu_area"));
		}
		if (!$this->ic_gravidade->FldIsDetailKey) {
			$this->ic_gravidade->setFormValue($objForm->GetValue("x_ic_gravidade"));
		}
		if (!$this->ic_urgencia->FldIsDetailKey) {
			$this->ic_urgencia->setFormValue($objForm->GetValue("x_ic_urgencia"));
		}
		if (!$this->ic_tendencia->FldIsDetailKey) {
			$this->ic_tendencia->setFormValue($objForm->GetValue("x_ic_tendencia"));
		}
		if (!$this->ic_prioridade->FldIsDetailKey) {
			$this->ic_prioridade->setFormValue($objForm->GetValue("x_ic_prioridade"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->nu_periodoPei->CurrentValue = $this->nu_periodoPei->FormValue;
		$this->nu_periodoPdti->CurrentValue = $this->nu_periodoPdti->FormValue;
		$this->nu_tpNecTi->CurrentValue = $this->nu_tpNecTi->FormValue;
		$this->ic_tpNec->CurrentValue = $this->ic_tpNec->FormValue;
		$this->nu_metaneg->CurrentValue = $this->nu_metaneg->FormValue;
		$this->nu_origem->CurrentValue = $this->nu_origem->FormValue;
		$this->nu_area->CurrentValue = $this->nu_area->FormValue;
		$this->ic_gravidade->CurrentValue = $this->ic_gravidade->FormValue;
		$this->ic_urgencia->CurrentValue = $this->ic_urgencia->FormValue;
		$this->ic_tendencia->CurrentValue = $this->ic_tendencia->FormValue;
		$this->ic_prioridade->CurrentValue = $this->ic_prioridade->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_necTi")) <> "")
			$this->nu_necTi->CurrentValue = $this->getKey("nu_necTi"); // nu_necTi
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// nu_periodoPei
			$this->nu_periodoPei->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_periodoPei], [nu_anoInicio] AS [DispFld], [nu_anoFim] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[periodopei]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPei, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_periodoPei->EditValue = $arwrk;

			// nu_periodoPdti
			$this->nu_periodoPdti->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_periodo], [no_periodo] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_periodoPei] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[perplanejamento]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_periodoPdti, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_anoInicio] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_periodoPdti->EditValue = $arwrk;

			// nu_tpNecTi
			$this->nu_tpNecTi->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT DISTINCT [nu_tpNecTi], [no_tpNecTi] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[tpnecti]";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_tpNecTi->EditValue = $arwrk;

			// ic_tpNec
			$this->ic_tpNec->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tpNec->FldTagValue(1), $this->ic_tpNec->FldTagCaption(1) <> "" ? $this->ic_tpNec->FldTagCaption(1) : $this->ic_tpNec->FldTagValue(1));
			$arwrk[] = array($this->ic_tpNec->FldTagValue(2), $this->ic_tpNec->FldTagCaption(2) <> "" ? $this->ic_tpNec->FldTagCaption(2) : $this->ic_tpNec->FldTagValue(2));
			$this->ic_tpNec->EditValue = $arwrk;

			// nu_metaneg
			$this->nu_metaneg->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_metaneg], [no_metaneg] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_periodoPei] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[metaneg]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_metaneg, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_metaneg->EditValue = $arwrk;

			// nu_origem
			$this->nu_origem->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT DISTINCT [nu_origem], [no_origem] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[origemnecti]";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_origem->EditValue = $arwrk;

			// nu_area
			$this->nu_area->EditCustomAttributes = "";
			$this->nu_area->EditValue = ew_HtmlEncode($this->nu_area->CurrentValue);
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
					$this->nu_area->EditValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_area->EditValue = $this->nu_area->CurrentValue;
				}
			} else {
				$this->nu_area->EditValue = NULL;
			}
			$this->nu_area->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_area->FldCaption()));

			// ic_gravidade
			$this->ic_gravidade->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_gravidade->FldTagValue(1), $this->ic_gravidade->FldTagCaption(1) <> "" ? $this->ic_gravidade->FldTagCaption(1) : $this->ic_gravidade->FldTagValue(1));
			$arwrk[] = array($this->ic_gravidade->FldTagValue(2), $this->ic_gravidade->FldTagCaption(2) <> "" ? $this->ic_gravidade->FldTagCaption(2) : $this->ic_gravidade->FldTagValue(2));
			$arwrk[] = array($this->ic_gravidade->FldTagValue(3), $this->ic_gravidade->FldTagCaption(3) <> "" ? $this->ic_gravidade->FldTagCaption(3) : $this->ic_gravidade->FldTagValue(3));
			$arwrk[] = array($this->ic_gravidade->FldTagValue(4), $this->ic_gravidade->FldTagCaption(4) <> "" ? $this->ic_gravidade->FldTagCaption(4) : $this->ic_gravidade->FldTagValue(4));
			$arwrk[] = array($this->ic_gravidade->FldTagValue(5), $this->ic_gravidade->FldTagCaption(5) <> "" ? $this->ic_gravidade->FldTagCaption(5) : $this->ic_gravidade->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_gravidade->EditValue = $arwrk;

			// ic_urgencia
			$this->ic_urgencia->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_urgencia->FldTagValue(1), $this->ic_urgencia->FldTagCaption(1) <> "" ? $this->ic_urgencia->FldTagCaption(1) : $this->ic_urgencia->FldTagValue(1));
			$arwrk[] = array($this->ic_urgencia->FldTagValue(2), $this->ic_urgencia->FldTagCaption(2) <> "" ? $this->ic_urgencia->FldTagCaption(2) : $this->ic_urgencia->FldTagValue(2));
			$arwrk[] = array($this->ic_urgencia->FldTagValue(3), $this->ic_urgencia->FldTagCaption(3) <> "" ? $this->ic_urgencia->FldTagCaption(3) : $this->ic_urgencia->FldTagValue(3));
			$arwrk[] = array($this->ic_urgencia->FldTagValue(4), $this->ic_urgencia->FldTagCaption(4) <> "" ? $this->ic_urgencia->FldTagCaption(4) : $this->ic_urgencia->FldTagValue(4));
			$arwrk[] = array($this->ic_urgencia->FldTagValue(5), $this->ic_urgencia->FldTagCaption(5) <> "" ? $this->ic_urgencia->FldTagCaption(5) : $this->ic_urgencia->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_urgencia->EditValue = $arwrk;

			// ic_tendencia
			$this->ic_tendencia->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_tendencia->FldTagValue(1), $this->ic_tendencia->FldTagCaption(1) <> "" ? $this->ic_tendencia->FldTagCaption(1) : $this->ic_tendencia->FldTagValue(1));
			$arwrk[] = array($this->ic_tendencia->FldTagValue(2), $this->ic_tendencia->FldTagCaption(2) <> "" ? $this->ic_tendencia->FldTagCaption(2) : $this->ic_tendencia->FldTagValue(2));
			$arwrk[] = array($this->ic_tendencia->FldTagValue(3), $this->ic_tendencia->FldTagCaption(3) <> "" ? $this->ic_tendencia->FldTagCaption(3) : $this->ic_tendencia->FldTagValue(3));
			$arwrk[] = array($this->ic_tendencia->FldTagValue(4), $this->ic_tendencia->FldTagCaption(4) <> "" ? $this->ic_tendencia->FldTagCaption(4) : $this->ic_tendencia->FldTagValue(4));
			$arwrk[] = array($this->ic_tendencia->FldTagValue(5), $this->ic_tendencia->FldTagCaption(5) <> "" ? $this->ic_tendencia->FldTagCaption(5) : $this->ic_tendencia->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_tendencia->EditValue = $arwrk;

			// ic_prioridade
			$this->ic_prioridade->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->ic_prioridade->FldTagValue(1), $this->ic_prioridade->FldTagCaption(1) <> "" ? $this->ic_prioridade->FldTagCaption(1) : $this->ic_prioridade->FldTagValue(1));
			$arwrk[] = array($this->ic_prioridade->FldTagValue(2), $this->ic_prioridade->FldTagCaption(2) <> "" ? $this->ic_prioridade->FldTagCaption(2) : $this->ic_prioridade->FldTagValue(2));
			$arwrk[] = array($this->ic_prioridade->FldTagValue(3), $this->ic_prioridade->FldTagCaption(3) <> "" ? $this->ic_prioridade->FldTagCaption(3) : $this->ic_prioridade->FldTagValue(3));
			$arwrk[] = array($this->ic_prioridade->FldTagValue(4), $this->ic_prioridade->FldTagCaption(4) <> "" ? $this->ic_prioridade->FldTagCaption(4) : $this->ic_prioridade->FldTagValue(4));
			$arwrk[] = array($this->ic_prioridade->FldTagValue(5), $this->ic_prioridade->FldTagCaption(5) <> "" ? $this->ic_prioridade->FldTagCaption(5) : $this->ic_prioridade->FldTagValue(5));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->ic_prioridade->EditValue = $arwrk;

			// Edit refer script
			// nu_periodoPei

			$this->nu_periodoPei->HrefValue = "";

			// nu_periodoPdti
			$this->nu_periodoPdti->HrefValue = "";

			// nu_tpNecTi
			$this->nu_tpNecTi->HrefValue = "";

			// ic_tpNec
			$this->ic_tpNec->HrefValue = "";

			// nu_metaneg
			$this->nu_metaneg->HrefValue = "";

			// nu_origem
			$this->nu_origem->HrefValue = "";

			// nu_area
			$this->nu_area->HrefValue = "";

			// ic_gravidade
			$this->ic_gravidade->HrefValue = "";

			// ic_urgencia
			$this->ic_urgencia->HrefValue = "";

			// ic_tendencia
			$this->ic_tendencia->HrefValue = "";

			// ic_prioridade
			$this->ic_prioridade->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->nu_periodoPei->FldIsDetailKey && !is_null($this->nu_periodoPei->FormValue) && $this->nu_periodoPei->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_periodoPei->FldCaption());
		}
		if (!$this->nu_periodoPdti->FldIsDetailKey && !is_null($this->nu_periodoPdti->FormValue) && $this->nu_periodoPdti->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_periodoPdti->FldCaption());
		}
		if (!$this->nu_tpNecTi->FldIsDetailKey && !is_null($this->nu_tpNecTi->FormValue) && $this->nu_tpNecTi->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_tpNecTi->FldCaption());
		}
		if ($this->ic_tpNec->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tpNec->FldCaption());
		}
		if (!$this->nu_metaneg->FldIsDetailKey && !is_null($this->nu_metaneg->FormValue) && $this->nu_metaneg->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_metaneg->FldCaption());
		}
		if (!$this->nu_origem->FldIsDetailKey && !is_null($this->nu_origem->FormValue) && $this->nu_origem->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_origem->FldCaption());
		}
		if (!$this->nu_area->FldIsDetailKey && !is_null($this->nu_area->FormValue) && $this->nu_area->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_area->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_area->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_area->FldErrMsg());
		}
		if (!$this->ic_gravidade->FldIsDetailKey && !is_null($this->ic_gravidade->FormValue) && $this->ic_gravidade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_gravidade->FldCaption());
		}
		if (!$this->ic_urgencia->FldIsDetailKey && !is_null($this->ic_urgencia->FormValue) && $this->ic_urgencia->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_urgencia->FldCaption());
		}
		if (!$this->ic_tendencia->FldIsDetailKey && !is_null($this->ic_tendencia->FormValue) && $this->ic_tendencia->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_tendencia->FldCaption());
		}
		if (!$this->ic_prioridade->FldIsDetailKey && !is_null($this->ic_prioridade->FormValue) && $this->ic_prioridade->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->ic_prioridade->FldCaption());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// nu_periodoPei
		$this->nu_periodoPei->SetDbValueDef($rsnew, $this->nu_periodoPei->CurrentValue, NULL, FALSE);

		// nu_periodoPdti
		$this->nu_periodoPdti->SetDbValueDef($rsnew, $this->nu_periodoPdti->CurrentValue, NULL, FALSE);

		// nu_tpNecTi
		$this->nu_tpNecTi->SetDbValueDef($rsnew, $this->nu_tpNecTi->CurrentValue, NULL, FALSE);

		// ic_tpNec
		$this->ic_tpNec->SetDbValueDef($rsnew, $this->ic_tpNec->CurrentValue, NULL, FALSE);

		// nu_metaneg
		$this->nu_metaneg->SetDbValueDef($rsnew, $this->nu_metaneg->CurrentValue, NULL, FALSE);

		// nu_origem
		$this->nu_origem->SetDbValueDef($rsnew, $this->nu_origem->CurrentValue, NULL, FALSE);

		// nu_area
		$this->nu_area->SetDbValueDef($rsnew, $this->nu_area->CurrentValue, NULL, FALSE);

		// ic_gravidade
		$this->ic_gravidade->SetDbValueDef($rsnew, $this->ic_gravidade->CurrentValue, NULL, FALSE);

		// ic_urgencia
		$this->ic_urgencia->SetDbValueDef($rsnew, $this->ic_urgencia->CurrentValue, NULL, FALSE);

		// ic_tendencia
		$this->ic_tendencia->SetDbValueDef($rsnew, $this->ic_tendencia->CurrentValue, NULL, FALSE);

		// ic_prioridade
		$this->ic_prioridade->SetDbValueDef($rsnew, $this->ic_prioridade->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->nu_necTi->setDbValue($conn->Insert_ID());
			$rsnew['nu_necTi'] = $this->nu_necTi->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "nectilist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($necti_add)) $necti_add = new cnecti_add();

// Page init
$necti_add->Page_Init();

// Page main
$necti_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$necti_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var necti_add = new ew_Page("necti_add");
necti_add.PageID = "add"; // Page ID
var EW_PAGE_ID = necti_add.PageID; // For backward compatibility

// Form object
var fnectiadd = new ew_Form("fnectiadd");

// Validate form
fnectiadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_nu_periodoPei");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->nu_periodoPei->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_periodoPdti");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->nu_periodoPdti->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_tpNecTi");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->nu_tpNecTi->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tpNec");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->ic_tpNec->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_metaneg");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->nu_metaneg->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_origem");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->nu_origem->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_area");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->nu_area->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_area");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($necti->nu_area->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_ic_gravidade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->ic_gravidade->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_urgencia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->ic_urgencia->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_tendencia");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->ic_tendencia->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_ic_prioridade");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($necti->ic_prioridade->FldCaption()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fnectiadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fnectiadd.ValidateRequired = true;
<?php } else { ?>
fnectiadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fnectiadd.Lists["x_nu_periodoPei"] = {"LinkField":"x_nu_periodoPei","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_anoInicio","x_nu_anoFim","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectiadd.Lists["x_nu_periodoPdti"] = {"LinkField":"x_nu_periodo","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_periodo","","",""],"ParentFields":["x_nu_periodoPei"],"FilterFields":["x_nu_periodoPei"],"Options":[]};
fnectiadd.Lists["x_nu_tpNecTi"] = {"LinkField":"x_nu_tpNecTi","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_tpNecTi","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectiadd.Lists["x_nu_metaneg"] = {"LinkField":"x_nu_metaneg","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_metaneg","","",""],"ParentFields":["x_nu_periodoPei"],"FilterFields":["x_nu_periodoPei"],"Options":[]};
fnectiadd.Lists["x_nu_origem"] = {"LinkField":"x_nu_origem","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_origem","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fnectiadd.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $necti_add->ShowPageHeader(); ?>
<?php
$necti_add->ShowMessage();
?>
<form name="fnectiadd" id="fnectiadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="necti">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_nectiadd" class="table table-bordered table-striped">
<?php if ($necti->nu_periodoPei->Visible) { // nu_periodoPei ?>
	<tr id="r_nu_periodoPei">
		<td><span id="elh_necti_nu_periodoPei"><?php echo $necti->nu_periodoPei->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->nu_periodoPei->CellAttributes() ?>>
<span id="el_necti_nu_periodoPei" class="control-group">
<?php $necti->nu_periodoPei->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_periodoPdti','x_nu_metaneg']); " . @$necti->nu_periodoPei->EditAttrs["onchange"]; ?>
<select data-field="x_nu_periodoPei" id="x_nu_periodoPei" name="x_nu_periodoPei"<?php echo $necti->nu_periodoPei->EditAttributes() ?>>
<?php
if (is_array($necti->nu_periodoPei->EditValue)) {
	$arwrk = $necti->nu_periodoPei->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->nu_periodoPei->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$necti->nu_periodoPei) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fnectiadd.Lists["x_nu_periodoPei"].Options = <?php echo (is_array($necti->nu_periodoPei->EditValue)) ? ew_ArrayToJson($necti->nu_periodoPei->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $necti->nu_periodoPei->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->nu_periodoPdti->Visible) { // nu_periodoPdti ?>
	<tr id="r_nu_periodoPdti">
		<td><span id="elh_necti_nu_periodoPdti"><?php echo $necti->nu_periodoPdti->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->nu_periodoPdti->CellAttributes() ?>>
<span id="el_necti_nu_periodoPdti" class="control-group">
<select data-field="x_nu_periodoPdti" id="x_nu_periodoPdti" name="x_nu_periodoPdti"<?php echo $necti->nu_periodoPdti->EditAttributes() ?>>
<?php
if (is_array($necti->nu_periodoPdti->EditValue)) {
	$arwrk = $necti->nu_periodoPdti->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->nu_periodoPdti->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fnectiadd.Lists["x_nu_periodoPdti"].Options = <?php echo (is_array($necti->nu_periodoPdti->EditValue)) ? ew_ArrayToJson($necti->nu_periodoPdti->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $necti->nu_periodoPdti->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->nu_tpNecTi->Visible) { // nu_tpNecTi ?>
	<tr id="r_nu_tpNecTi">
		<td><span id="elh_necti_nu_tpNecTi"><?php echo $necti->nu_tpNecTi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->nu_tpNecTi->CellAttributes() ?>>
<span id="el_necti_nu_tpNecTi" class="control-group">
<select data-field="x_nu_tpNecTi" id="x_nu_tpNecTi" name="x_nu_tpNecTi"<?php echo $necti->nu_tpNecTi->EditAttributes() ?>>
<?php
if (is_array($necti->nu_tpNecTi->EditValue)) {
	$arwrk = $necti->nu_tpNecTi->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->nu_tpNecTi->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "tpnecti")) { ?>
&nbsp;<a id="aol_x_nu_tpNecTi" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_tpNecTi',url:'tpnectiaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $necti->nu_tpNecTi->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fnectiadd.Lists["x_nu_tpNecTi"].Options = <?php echo (is_array($necti->nu_tpNecTi->EditValue)) ? ew_ArrayToJson($necti->nu_tpNecTi->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $necti->nu_tpNecTi->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->ic_tpNec->Visible) { // ic_tpNec ?>
	<tr id="r_ic_tpNec">
		<td><span id="elh_necti_ic_tpNec"><?php echo $necti->ic_tpNec->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->ic_tpNec->CellAttributes() ?>>
<span id="el_necti_ic_tpNec" class="control-group">
<div id="tp_x_ic_tpNec" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME ?>"><input type="radio" name="x_ic_tpNec" id="x_ic_tpNec" value="{value}"<?php echo $necti->ic_tpNec->EditAttributes() ?>></div>
<div id="dsl_x_ic_tpNec" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $necti->ic_tpNec->EditValue;
if (is_array($arwrk)) {
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->ic_tpNec->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " checked=\"checked\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label class="radio"><input type="radio" data-field="x_ic_tpNec" name="x_ic_tpNec" id="x_ic_tpNec_<?php echo $rowcntwrk ?>" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $necti->ic_tpNec->EditAttributes() ?>><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
?>
</div>
</span>
<?php echo $necti->ic_tpNec->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->nu_metaneg->Visible) { // nu_metaneg ?>
	<tr id="r_nu_metaneg">
		<td><span id="elh_necti_nu_metaneg"><?php echo $necti->nu_metaneg->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->nu_metaneg->CellAttributes() ?>>
<span id="el_necti_nu_metaneg" class="control-group">
<select data-field="x_nu_metaneg" id="x_nu_metaneg" name="x_nu_metaneg"<?php echo $necti->nu_metaneg->EditAttributes() ?>>
<?php
if (is_array($necti->nu_metaneg->EditValue)) {
	$arwrk = $necti->nu_metaneg->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->nu_metaneg->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fnectiadd.Lists["x_nu_metaneg"].Options = <?php echo (is_array($necti->nu_metaneg->EditValue)) ? ew_ArrayToJson($necti->nu_metaneg->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $necti->nu_metaneg->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->nu_origem->Visible) { // nu_origem ?>
	<tr id="r_nu_origem">
		<td><span id="elh_necti_nu_origem"><?php echo $necti->nu_origem->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->nu_origem->CellAttributes() ?>>
<span id="el_necti_nu_origem" class="control-group">
<select data-field="x_nu_origem" id="x_nu_origem" name="x_nu_origem"<?php echo $necti->nu_origem->EditAttributes() ?>>
<?php
if (is_array($necti->nu_origem->EditValue)) {
	$arwrk = $necti->nu_origem->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->nu_origem->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "origemnecti")) { ?>
&nbsp;<a id="aol_x_nu_origem" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_origem',url:'origemnectiaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $necti->nu_origem->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fnectiadd.Lists["x_nu_origem"].Options = <?php echo (is_array($necti->nu_origem->EditValue)) ? ew_ArrayToJson($necti->nu_origem->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $necti->nu_origem->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->nu_area->Visible) { // nu_area ?>
	<tr id="r_nu_area">
		<td><span id="elh_necti_nu_area"><?php echo $necti->nu_area->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->nu_area->CellAttributes() ?>>
<span id="el_necti_nu_area" class="control-group">
<?php
	$wrkonchange = trim(" " . @$necti->nu_area->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$necti->nu_area->EditAttrs["onchange"] = "";
?>
<span id="as_x_nu_area" style="white-space: nowrap; z-index: 8920">
	<input type="text" name="sv_x_nu_area" id="sv_x_nu_area" value="<?php echo $necti->nu_area->EditValue ?>" size="30" placeholder="<?php echo $necti->nu_area->PlaceHolder ?>"<?php echo $necti->nu_area->EditAttributes() ?>>&nbsp;<span id="em_x_nu_area" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x_nu_area" style="display: inline; z-index: 8920"></div>
</span>
<input type="hidden" data-field="x_nu_area" name="x_nu_area" id="x_nu_area" value="<?php echo $necti->nu_area->CurrentValue ?>"<?php echo $wrkonchange ?>>
<?php
$sSqlWrk = "SELECT  TOP " . EW_AUTO_SUGGEST_MAX_ENTRIES . " [nu_area], [no_area] AS [DispFld] FROM [dbo].[area]";
$sWhereWrk = "[no_area] LIKE '%{query_value}%'";
$lookuptblfilter = "[ic_ativo]=S && [nu_organizacao] = (SELECT nu_orgBase from organizacao)";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$necti->Lookup_Selecting($necti->nu_area, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY [no_area] ASC";
?>
<input type="hidden" name="q_x_nu_area" id="q_x_nu_area" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>">
<script type="text/javascript">
var oas = new ew_AutoSuggest("x_nu_area", fnectiadd, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x_nu_area") + ar[i] : "";
	return dv;
}
fnectiadd.AutoSuggests["x_nu_area"] = oas;
</script>
<?php if (AllowAdd(CurrentProjectID() . "area")) { ?>
&nbsp;<a id="aol_x_nu_area" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_area',url:'areaaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $necti->nu_area->FldCaption() ?></a>
<?php } ?>
<?php
$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
$sWhereWrk = "{filter}";
$lookuptblfilter = "[ic_ativo]=S && [nu_organizacao] = (SELECT nu_orgBase from organizacao)";
if (strval($lookuptblfilter) <> "") {
	ew_AddFilter($sWhereWrk, $lookuptblfilter);
}

// Call Lookup selecting
$necti->Lookup_Selecting($necti->nu_area, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
$sSqlWrk .= " ORDER BY [no_area] ASC";
?>
<input type="hidden" name="s_x_nu_area" id="s_x_nu_area" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&f0=<?php echo ew_Encrypt("[nu_area] = {filter_value}"); ?>&t0=3">
</span>
<?php echo $necti->nu_area->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->ic_gravidade->Visible) { // ic_gravidade ?>
	<tr id="r_ic_gravidade">
		<td><span id="elh_necti_ic_gravidade"><?php echo $necti->ic_gravidade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->ic_gravidade->CellAttributes() ?>>
<span id="el_necti_ic_gravidade" class="control-group">
<select data-field="x_ic_gravidade" id="x_ic_gravidade" name="x_ic_gravidade"<?php echo $necti->ic_gravidade->EditAttributes() ?>>
<?php
if (is_array($necti->ic_gravidade->EditValue)) {
	$arwrk = $necti->ic_gravidade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->ic_gravidade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $necti->ic_gravidade->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->ic_urgencia->Visible) { // ic_urgencia ?>
	<tr id="r_ic_urgencia">
		<td><span id="elh_necti_ic_urgencia"><?php echo $necti->ic_urgencia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->ic_urgencia->CellAttributes() ?>>
<span id="el_necti_ic_urgencia" class="control-group">
<select data-field="x_ic_urgencia" id="x_ic_urgencia" name="x_ic_urgencia"<?php echo $necti->ic_urgencia->EditAttributes() ?>>
<?php
if (is_array($necti->ic_urgencia->EditValue)) {
	$arwrk = $necti->ic_urgencia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->ic_urgencia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $necti->ic_urgencia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->ic_tendencia->Visible) { // ic_tendencia ?>
	<tr id="r_ic_tendencia">
		<td><span id="elh_necti_ic_tendencia"><?php echo $necti->ic_tendencia->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->ic_tendencia->CellAttributes() ?>>
<span id="el_necti_ic_tendencia" class="control-group">
<select data-field="x_ic_tendencia" id="x_ic_tendencia" name="x_ic_tendencia"<?php echo $necti->ic_tendencia->EditAttributes() ?>>
<?php
if (is_array($necti->ic_tendencia->EditValue)) {
	$arwrk = $necti->ic_tendencia->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->ic_tendencia->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $necti->ic_tendencia->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($necti->ic_prioridade->Visible) { // ic_prioridade ?>
	<tr id="r_ic_prioridade">
		<td><span id="elh_necti_ic_prioridade"><?php echo $necti->ic_prioridade->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $necti->ic_prioridade->CellAttributes() ?>>
<span id="el_necti_ic_prioridade" class="control-group">
<select data-field="x_ic_prioridade" id="x_ic_prioridade" name="x_ic_prioridade"<?php echo $necti->ic_prioridade->EditAttributes() ?>>
<?php
if (is_array($necti->ic_prioridade->EditValue)) {
	$arwrk = $necti->ic_prioridade->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($necti->ic_prioridade->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
<?php echo $necti->ic_prioridade->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fnectiadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$necti_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$necti_add->Page_Terminate();
?>
