<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "regranegocioinfo.php" ?>
<?php include_once "corninfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$regranegocio_add = NULL; // Initialize page object first

class cregranegocio_add extends cregranegocio {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'regranegocio';

	// Page object name
	var $PageObjName = 'regranegocio_add';

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
	var $AuditTrailOnAdd = TRUE;

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

		// Table object (regranegocio)
		if (!isset($GLOBALS["regranegocio"])) {
			$GLOBALS["regranegocio"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["regranegocio"];
		}

		// Table object (corn)
		if (!isset($GLOBALS['corn'])) $GLOBALS['corn'] = new ccorn();

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'regranegocio', TRUE);

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
			$this->Page_Terminate("regranegociolist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("regranegociolist.php");
		}

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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["co_alternativo"] != "") {
				$this->co_alternativo->setQueryStringValue($_GET["co_alternativo"]);
				$this->setKey("co_alternativo", $this->co_alternativo->CurrentValue); // Set up key
			} else {
				$this->setKey("co_alternativo", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["nu_versao"] != "") {
				$this->nu_versao->setQueryStringValue($_GET["nu_versao"]);
				$this->setKey("nu_versao", $this->nu_versao->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_versao", ""); // Clear key
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
					$this->Page_Terminate("regranegociolist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "regranegocioview.php")
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
		$this->co_alternativo->CurrentValue = NULL;
		$this->co_alternativo->OldValue = $this->co_alternativo->CurrentValue;
		$this->nu_versao->CurrentValue = 1;
		$this->no_regraNegocio->CurrentValue = NULL;
		$this->no_regraNegocio->OldValue = $this->no_regraNegocio->CurrentValue;
		$this->ds_regraNegocio->CurrentValue = NULL;
		$this->ds_regraNegocio->OldValue = $this->ds_regraNegocio->CurrentValue;
		$this->nu_area->CurrentValue = NULL;
		$this->nu_area->OldValue = $this->nu_area->CurrentValue;
		$this->ds_origemRegra->CurrentValue = NULL;
		$this->ds_origemRegra->OldValue = $this->ds_origemRegra->CurrentValue;
		$this->nu_projeto->CurrentValue = NULL;
		$this->nu_projeto->OldValue = $this->nu_projeto->CurrentValue;
		$this->no_tags->CurrentValue = NULL;
		$this->no_tags->OldValue = $this->no_tags->CurrentValue;
		$this->nu_stRegraNegocio->CurrentValue = NULL;
		$this->nu_stRegraNegocio->OldValue = $this->nu_stRegraNegocio->CurrentValue;
		$this->nu_usuario->CurrentValue = CurrentUserID();
		$this->dt_versao->CurrentValue = NULL;
		$this->dt_versao->OldValue = $this->dt_versao->CurrentValue;
		$this->hh_versao->CurrentValue = NULL;
		$this->hh_versao->OldValue = $this->hh_versao->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->co_alternativo->FldIsDetailKey) {
			$this->co_alternativo->setFormValue($objForm->GetValue("x_co_alternativo"));
		}
		if (!$this->nu_versao->FldIsDetailKey) {
			$this->nu_versao->setFormValue($objForm->GetValue("x_nu_versao"));
		}
		if (!$this->no_regraNegocio->FldIsDetailKey) {
			$this->no_regraNegocio->setFormValue($objForm->GetValue("x_no_regraNegocio"));
		}
		if (!$this->ds_regraNegocio->FldIsDetailKey) {
			$this->ds_regraNegocio->setFormValue($objForm->GetValue("x_ds_regraNegocio"));
		}
		if (!$this->nu_area->FldIsDetailKey) {
			$this->nu_area->setFormValue($objForm->GetValue("x_nu_area"));
		}
		if (!$this->ds_origemRegra->FldIsDetailKey) {
			$this->ds_origemRegra->setFormValue($objForm->GetValue("x_ds_origemRegra"));
		}
		if (!$this->nu_projeto->FldIsDetailKey) {
			$this->nu_projeto->setFormValue($objForm->GetValue("x_nu_projeto"));
		}
		if (!$this->no_tags->FldIsDetailKey) {
			$this->no_tags->setFormValue($objForm->GetValue("x_no_tags"));
		}
		if (!$this->nu_stRegraNegocio->FldIsDetailKey) {
			$this->nu_stRegraNegocio->setFormValue($objForm->GetValue("x_nu_stRegraNegocio"));
		}
		if (!$this->nu_usuario->FldIsDetailKey) {
			$this->nu_usuario->setFormValue($objForm->GetValue("x_nu_usuario"));
		}
		if (!$this->dt_versao->FldIsDetailKey) {
			$this->dt_versao->setFormValue($objForm->GetValue("x_dt_versao"));
			$this->dt_versao->CurrentValue = ew_UnFormatDateTime($this->dt_versao->CurrentValue, 7);
		}
		if (!$this->hh_versao->FldIsDetailKey) {
			$this->hh_versao->setFormValue($objForm->GetValue("x_hh_versao"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->co_alternativo->CurrentValue = $this->co_alternativo->FormValue;
		$this->nu_versao->CurrentValue = $this->nu_versao->FormValue;
		$this->no_regraNegocio->CurrentValue = $this->no_regraNegocio->FormValue;
		$this->ds_regraNegocio->CurrentValue = $this->ds_regraNegocio->FormValue;
		$this->nu_area->CurrentValue = $this->nu_area->FormValue;
		$this->ds_origemRegra->CurrentValue = $this->ds_origemRegra->FormValue;
		$this->nu_projeto->CurrentValue = $this->nu_projeto->FormValue;
		$this->no_tags->CurrentValue = $this->no_tags->FormValue;
		$this->nu_stRegraNegocio->CurrentValue = $this->nu_stRegraNegocio->FormValue;
		$this->nu_usuario->CurrentValue = $this->nu_usuario->FormValue;
		$this->dt_versao->CurrentValue = $this->dt_versao->FormValue;
		$this->dt_versao->CurrentValue = ew_UnFormatDateTime($this->dt_versao->CurrentValue, 7);
		$this->hh_versao->CurrentValue = $this->hh_versao->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('add');
			if (!$res) {
				$sUserIdMsg = $Language->Phrase("NoPermission");
				$this->setFailureMessage($sUserIdMsg);
			}
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
		$this->co_alternativo->setDbValue($rs->fields('co_alternativo'));
		$this->nu_versao->setDbValue($rs->fields('nu_versao'));
		$this->no_regraNegocio->setDbValue($rs->fields('no_regraNegocio'));
		$this->ds_regraNegocio->setDbValue($rs->fields('ds_regraNegocio'));
		$this->nu_area->setDbValue($rs->fields('nu_area'));
		$this->ds_origemRegra->setDbValue($rs->fields('ds_origemRegra'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		$this->no_tags->setDbValue($rs->fields('no_tags'));
		$this->nu_stRegraNegocio->setDbValue($rs->fields('nu_stRegraNegocio'));
		$this->nu_usuario->setDbValue($rs->fields('nu_usuario'));
		$this->dt_versao->setDbValue($rs->fields('dt_versao'));
		$this->hh_versao->setDbValue($rs->fields('hh_versao'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->co_alternativo->DbValue = $row['co_alternativo'];
		$this->nu_versao->DbValue = $row['nu_versao'];
		$this->no_regraNegocio->DbValue = $row['no_regraNegocio'];
		$this->ds_regraNegocio->DbValue = $row['ds_regraNegocio'];
		$this->nu_area->DbValue = $row['nu_area'];
		$this->ds_origemRegra->DbValue = $row['ds_origemRegra'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->no_tags->DbValue = $row['no_tags'];
		$this->nu_stRegraNegocio->DbValue = $row['nu_stRegraNegocio'];
		$this->nu_usuario->DbValue = $row['nu_usuario'];
		$this->dt_versao->DbValue = $row['dt_versao'];
		$this->hh_versao->DbValue = $row['hh_versao'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("co_alternativo")) <> "")
			$this->co_alternativo->CurrentValue = $this->getKey("co_alternativo"); // co_alternativo
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("nu_versao")) <> "")
			$this->nu_versao->CurrentValue = $this->getKey("nu_versao"); // nu_versao
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
		// co_alternativo
		// nu_versao
		// no_regraNegocio
		// ds_regraNegocio
		// nu_area
		// ds_origemRegra
		// nu_projeto
		// no_tags
		// nu_stRegraNegocio
		// nu_usuario
		// dt_versao
		// hh_versao

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// co_alternativo
			if (strval($this->co_alternativo->CurrentValue) <> "") {
				$sFilterWrk = "[co_rn]" . ew_SearchString("=", $this->co_alternativo->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_rn], [co_rn] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[corn]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_alternativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_rn] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_alternativo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
				}
			} else {
				$this->co_alternativo->ViewValue = NULL;
			}
			$this->co_alternativo->ViewCustomAttributes = "";

			// nu_versao
			$this->nu_versao->ViewValue = $this->nu_versao->CurrentValue;
			$this->nu_versao->ViewCustomAttributes = "";

			// no_regraNegocio
			$this->no_regraNegocio->ViewValue = $this->no_regraNegocio->CurrentValue;
			$this->no_regraNegocio->ViewCustomAttributes = "";

			// ds_regraNegocio
			$this->ds_regraNegocio->ViewValue = $this->ds_regraNegocio->CurrentValue;
			$this->ds_regraNegocio->ViewCustomAttributes = "";

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

			// ds_origemRegra
			$this->ds_origemRegra->ViewValue = $this->ds_origemRegra->CurrentValue;
			$this->ds_origemRegra->ViewCustomAttributes = "";

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

			// no_tags
			$this->no_tags->ViewValue = $this->no_tags->CurrentValue;
			$this->no_tags->ViewCustomAttributes = "";

			// nu_stRegraNegocio
			if (strval($this->nu_stRegraNegocio->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stRegraNegocio]" . ew_SearchString("=", $this->nu_stRegraNegocio->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stRegraNegocio], [no_stRegraNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stregranegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stRegraNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stRegraNegocio->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stRegraNegocio->ViewValue = $this->nu_stRegraNegocio->CurrentValue;
				}
			} else {
				$this->nu_stRegraNegocio->ViewValue = NULL;
			}
			$this->nu_stRegraNegocio->ViewCustomAttributes = "";

			// nu_usuario
			$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
			if (strval($this->nu_usuario->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuario->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuario, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuario->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuario->ViewValue = $this->nu_usuario->CurrentValue;
				}
			} else {
				$this->nu_usuario->ViewValue = NULL;
			}
			$this->nu_usuario->ViewCustomAttributes = "";

			// dt_versao
			$this->dt_versao->ViewValue = $this->dt_versao->CurrentValue;
			$this->dt_versao->ViewValue = ew_FormatDateTime($this->dt_versao->ViewValue, 7);
			$this->dt_versao->ViewCustomAttributes = "";

			// hh_versao
			$this->hh_versao->ViewValue = $this->hh_versao->CurrentValue;
			$this->hh_versao->ViewValue = ew_FormatDateTime($this->hh_versao->ViewValue, 4);
			$this->hh_versao->ViewCustomAttributes = "";

			// co_alternativo
			$this->co_alternativo->LinkCustomAttributes = "";
			$this->co_alternativo->HrefValue = "";
			$this->co_alternativo->TooltipValue = "";

			// nu_versao
			$this->nu_versao->LinkCustomAttributes = "";
			$this->nu_versao->HrefValue = "";
			$this->nu_versao->TooltipValue = "";

			// no_regraNegocio
			$this->no_regraNegocio->LinkCustomAttributes = "";
			$this->no_regraNegocio->HrefValue = "";
			$this->no_regraNegocio->TooltipValue = "";

			// ds_regraNegocio
			$this->ds_regraNegocio->LinkCustomAttributes = "";
			$this->ds_regraNegocio->HrefValue = "";
			$this->ds_regraNegocio->TooltipValue = "";

			// nu_area
			$this->nu_area->LinkCustomAttributes = "";
			$this->nu_area->HrefValue = "";
			$this->nu_area->TooltipValue = "";

			// ds_origemRegra
			$this->ds_origemRegra->LinkCustomAttributes = "";
			$this->ds_origemRegra->HrefValue = "";
			$this->ds_origemRegra->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// no_tags
			$this->no_tags->LinkCustomAttributes = "";
			$this->no_tags->HrefValue = "";
			$this->no_tags->TooltipValue = "";

			// nu_stRegraNegocio
			$this->nu_stRegraNegocio->LinkCustomAttributes = "";
			$this->nu_stRegraNegocio->HrefValue = "";
			$this->nu_stRegraNegocio->TooltipValue = "";

			// nu_usuario
			$this->nu_usuario->LinkCustomAttributes = "";
			$this->nu_usuario->HrefValue = "";
			$this->nu_usuario->TooltipValue = "";

			// dt_versao
			$this->dt_versao->LinkCustomAttributes = "";
			$this->dt_versao->HrefValue = "";
			$this->dt_versao->TooltipValue = "";

			// hh_versao
			$this->hh_versao->LinkCustomAttributes = "";
			$this->hh_versao->HrefValue = "";
			$this->hh_versao->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// co_alternativo
			$this->co_alternativo->EditCustomAttributes = "";
			if ($this->co_alternativo->getSessionValue() <> "") {
				$this->co_alternativo->CurrentValue = $this->co_alternativo->getSessionValue();
			if (strval($this->co_alternativo->CurrentValue) <> "") {
				$sFilterWrk = "[co_rn]" . ew_SearchString("=", $this->co_alternativo->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT [co_rn], [co_rn] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[corn]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_alternativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_rn] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->co_alternativo->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->co_alternativo->ViewValue = $this->co_alternativo->CurrentValue;
				}
			} else {
				$this->co_alternativo->ViewValue = NULL;
			}
			$this->co_alternativo->ViewCustomAttributes = "";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [co_rn], [co_rn] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[corn]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->co_alternativo, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [co_rn] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->co_alternativo->EditValue = $arwrk;
			}

			// nu_versao
			$this->nu_versao->EditCustomAttributes = "readonly";
			$this->nu_versao->EditValue = ew_HtmlEncode($this->nu_versao->CurrentValue);
			$this->nu_versao->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->nu_versao->FldCaption()));

			// no_regraNegocio
			$this->no_regraNegocio->EditCustomAttributes = "";
			$this->no_regraNegocio->EditValue = ew_HtmlEncode($this->no_regraNegocio->CurrentValue);
			$this->no_regraNegocio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_regraNegocio->FldCaption()));

			// ds_regraNegocio
			$this->ds_regraNegocio->EditCustomAttributes = "";
			$this->ds_regraNegocio->EditValue = $this->ds_regraNegocio->CurrentValue;
			$this->ds_regraNegocio->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_regraNegocio->FldCaption()));

			// nu_area
			$this->nu_area->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
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
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_area->EditValue = $arwrk;

			// ds_origemRegra
			$this->ds_origemRegra->EditCustomAttributes = "";
			$this->ds_origemRegra->EditValue = $this->ds_origemRegra->CurrentValue;
			$this->ds_origemRegra->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_origemRegra->FldCaption()));

			// nu_projeto
			$this->nu_projeto->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_projeto], [no_projeto] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[projeto]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_projeto, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_projeto] DESC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_projeto->EditValue = $arwrk;

			// no_tags
			$this->no_tags->EditCustomAttributes = "";
			$this->no_tags->EditValue = ew_HtmlEncode($this->no_tags->CurrentValue);
			$this->no_tags->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_tags->FldCaption()));

			// nu_stRegraNegocio
			$this->nu_stRegraNegocio->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stRegraNegocio], [no_stRegraNegocio] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stregranegocio]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stRegraNegocio, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_ordem] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stRegraNegocio->EditValue = $arwrk;

			// nu_usuario
			// dt_versao
			// hh_versao
			// Edit refer script
			// co_alternativo

			$this->co_alternativo->HrefValue = "";

			// nu_versao
			$this->nu_versao->HrefValue = "";

			// no_regraNegocio
			$this->no_regraNegocio->HrefValue = "";

			// ds_regraNegocio
			$this->ds_regraNegocio->HrefValue = "";

			// nu_area
			$this->nu_area->HrefValue = "";

			// ds_origemRegra
			$this->ds_origemRegra->HrefValue = "";

			// nu_projeto
			$this->nu_projeto->HrefValue = "";

			// no_tags
			$this->no_tags->HrefValue = "";

			// nu_stRegraNegocio
			$this->nu_stRegraNegocio->HrefValue = "";

			// nu_usuario
			$this->nu_usuario->HrefValue = "";

			// dt_versao
			$this->dt_versao->HrefValue = "";

			// hh_versao
			$this->hh_versao->HrefValue = "";
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
		if (!$this->co_alternativo->FldIsDetailKey && !is_null($this->co_alternativo->FormValue) && $this->co_alternativo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_alternativo->FldCaption());
		}
		if (!$this->nu_versao->FldIsDetailKey && !is_null($this->nu_versao->FormValue) && $this->nu_versao->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_versao->FldCaption());
		}
		if (!ew_CheckInteger($this->nu_versao->FormValue)) {
			ew_AddMessage($gsFormError, $this->nu_versao->FldErrMsg());
		}
		if (!$this->no_regraNegocio->FldIsDetailKey && !is_null($this->no_regraNegocio->FormValue) && $this->no_regraNegocio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_regraNegocio->FldCaption());
		}
		if (!$this->nu_area->FldIsDetailKey && !is_null($this->nu_area->FormValue) && $this->nu_area->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_area->FldCaption());
		}
		if (!$this->nu_projeto->FldIsDetailKey && !is_null($this->nu_projeto->FormValue) && $this->nu_projeto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_projeto->FldCaption());
		}
		if (!$this->nu_stRegraNegocio->FldIsDetailKey && !is_null($this->nu_stRegraNegocio->FormValue) && $this->nu_stRegraNegocio->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_stRegraNegocio->FldCaption());
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

		// co_alternativo
		$this->co_alternativo->SetDbValueDef($rsnew, $this->co_alternativo->CurrentValue, "", FALSE);

		// nu_versao
		$this->nu_versao->SetDbValueDef($rsnew, $this->nu_versao->CurrentValue, 0, FALSE);

		// no_regraNegocio
		$this->no_regraNegocio->SetDbValueDef($rsnew, $this->no_regraNegocio->CurrentValue, NULL, FALSE);

		// ds_regraNegocio
		$this->ds_regraNegocio->SetDbValueDef($rsnew, $this->ds_regraNegocio->CurrentValue, NULL, FALSE);

		// nu_area
		$this->nu_area->SetDbValueDef($rsnew, $this->nu_area->CurrentValue, NULL, FALSE);

		// ds_origemRegra
		$this->ds_origemRegra->SetDbValueDef($rsnew, $this->ds_origemRegra->CurrentValue, NULL, FALSE);

		// nu_projeto
		$this->nu_projeto->SetDbValueDef($rsnew, $this->nu_projeto->CurrentValue, NULL, FALSE);

		// no_tags
		$this->no_tags->SetDbValueDef($rsnew, $this->no_tags->CurrentValue, NULL, FALSE);

		// nu_stRegraNegocio
		$this->nu_stRegraNegocio->SetDbValueDef($rsnew, $this->nu_stRegraNegocio->CurrentValue, NULL, FALSE);

		// nu_usuario
		$this->nu_usuario->SetDbValueDef($rsnew, CurrentUserID(), NULL);
		$rsnew['nu_usuario'] = &$this->nu_usuario->DbValue;

		// dt_versao
		$this->dt_versao->SetDbValueDef($rsnew, ew_CurrentDate(), NULL);
		$rsnew['dt_versao'] = &$this->dt_versao->DbValue;

		// hh_versao
		$this->hh_versao->SetDbValueDef($rsnew, ew_CurrentTime(), ew_CurrentTime());
		$rsnew['hh_versao'] = &$this->hh_versao->DbValue;

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->co_alternativo->CurrentValue == "" && $this->co_alternativo->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->nu_versao->CurrentValue == "" && $this->nu_versao->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
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
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->nu_usuario->CurrentValue);
		return TRUE;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "corn") {
				$bValidMaster = TRUE;
				if (@$_GET["co_rn"] <> "") {
					$GLOBALS["corn"]->co_rn->setQueryStringValue($_GET["co_rn"]);
					$this->co_alternativo->setQueryStringValue($GLOBALS["corn"]->co_rn->QueryStringValue);
					$this->co_alternativo->setSessionValue($this->co_alternativo->QueryStringValue);
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "corn") {
				if ($this->co_alternativo->QueryStringValue == "") $this->co_alternativo->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "regranegociolist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'regranegocio';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'regranegocio';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['co_alternativo'];
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_versao'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
	  $usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
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
		return TRUE;  
	}      
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($regranegocio_add)) $regranegocio_add = new cregranegocio_add();

// Page init
$regranegocio_add->Page_Init();

// Page main
$regranegocio_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$regranegocio_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var regranegocio_add = new ew_Page("regranegocio_add");
regranegocio_add.PageID = "add"; // Page ID
var EW_PAGE_ID = regranegocio_add.PageID; // For backward compatibility

// Form object
var fregranegocioadd = new ew_Form("fregranegocioadd");

// Validate form
fregranegocioadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_co_alternativo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->co_alternativo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->nu_versao->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_versao");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($regranegocio->nu_versao->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_regraNegocio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->no_regraNegocio->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_area");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->nu_area->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_projeto");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->nu_projeto->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stRegraNegocio");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($regranegocio->nu_stRegraNegocio->FldCaption()) ?>");

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
fregranegocioadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregranegocioadd.ValidateRequired = true;
<?php } else { ?>
fregranegocioadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fregranegocioadd.Lists["x_co_alternativo"] = {"LinkField":"x_co_rn","Ajax":null,"AutoFill":false,"DisplayFields":["x_co_rn","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fregranegocioadd.Lists["x_nu_area"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fregranegocioadd.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fregranegocioadd.Lists["x_nu_stRegraNegocio"] = {"LinkField":"x_nu_stRegraNegocio","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stRegraNegocio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fregranegocioadd.Lists["x_nu_usuario"] = {"LinkField":"x_nu_usuario","Ajax":true,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $regranegocio_add->ShowPageHeader(); ?>
<?php
$regranegocio_add->ShowMessage();
?>
<form name="fregranegocioadd" id="fregranegocioadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="regranegocio">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_regranegocioadd" class="table table-bordered table-striped">
<?php if ($regranegocio->co_alternativo->Visible) { // co_alternativo ?>
	<tr id="r_co_alternativo">
		<td><span id="elh_regranegocio_co_alternativo"><?php echo $regranegocio->co_alternativo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $regranegocio->co_alternativo->CellAttributes() ?>>
<?php if ($regranegocio->co_alternativo->getSessionValue() <> "") { ?>
<span<?php echo $regranegocio->co_alternativo->ViewAttributes() ?>>
<?php echo $regranegocio->co_alternativo->ViewValue ?></span>
<input type="hidden" id="x_co_alternativo" name="x_co_alternativo" value="<?php echo ew_HtmlEncode($regranegocio->co_alternativo->CurrentValue) ?>">
<?php } else { ?>
<select data-field="x_co_alternativo" id="x_co_alternativo" name="x_co_alternativo"<?php echo $regranegocio->co_alternativo->EditAttributes() ?>>
<?php
if (is_array($regranegocio->co_alternativo->EditValue)) {
	$arwrk = $regranegocio->co_alternativo->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->co_alternativo->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fregranegocioadd.Lists["x_co_alternativo"].Options = <?php echo (is_array($regranegocio->co_alternativo->EditValue)) ? ew_ArrayToJson($regranegocio->co_alternativo->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php echo $regranegocio->co_alternativo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($regranegocio->nu_versao->Visible) { // nu_versao ?>
	<tr id="r_nu_versao">
		<td><span id="elh_regranegocio_nu_versao"><?php echo $regranegocio->nu_versao->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $regranegocio->nu_versao->CellAttributes() ?>>
<span id="el_regranegocio_nu_versao" class="control-group">
<input type="text" data-field="x_nu_versao" name="x_nu_versao" id="x_nu_versao" size="30" placeholder="<?php echo $regranegocio->nu_versao->PlaceHolder ?>" value="<?php echo $regranegocio->nu_versao->EditValue ?>"<?php echo $regranegocio->nu_versao->EditAttributes() ?>>
</span>
<?php echo $regranegocio->nu_versao->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($regranegocio->no_regraNegocio->Visible) { // no_regraNegocio ?>
	<tr id="r_no_regraNegocio">
		<td><span id="elh_regranegocio_no_regraNegocio"><?php echo $regranegocio->no_regraNegocio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $regranegocio->no_regraNegocio->CellAttributes() ?>>
<span id="el_regranegocio_no_regraNegocio" class="control-group">
<input type="text" data-field="x_no_regraNegocio" name="x_no_regraNegocio" id="x_no_regraNegocio" size="30" maxlength="150" placeholder="<?php echo $regranegocio->no_regraNegocio->PlaceHolder ?>" value="<?php echo $regranegocio->no_regraNegocio->EditValue ?>"<?php echo $regranegocio->no_regraNegocio->EditAttributes() ?>>
</span>
<?php echo $regranegocio->no_regraNegocio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($regranegocio->ds_regraNegocio->Visible) { // ds_regraNegocio ?>
	<tr id="r_ds_regraNegocio">
		<td><span id="elh_regranegocio_ds_regraNegocio"><?php echo $regranegocio->ds_regraNegocio->FldCaption() ?></span></td>
		<td<?php echo $regranegocio->ds_regraNegocio->CellAttributes() ?>>
<span id="el_regranegocio_ds_regraNegocio" class="control-group">
<textarea data-field="x_ds_regraNegocio" name="x_ds_regraNegocio" id="x_ds_regraNegocio" cols="35" rows="4" placeholder="<?php echo $regranegocio->ds_regraNegocio->PlaceHolder ?>"<?php echo $regranegocio->ds_regraNegocio->EditAttributes() ?>><?php echo $regranegocio->ds_regraNegocio->EditValue ?></textarea>
</span>
<?php echo $regranegocio->ds_regraNegocio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($regranegocio->nu_area->Visible) { // nu_area ?>
	<tr id="r_nu_area">
		<td><span id="elh_regranegocio_nu_area"><?php echo $regranegocio->nu_area->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $regranegocio->nu_area->CellAttributes() ?>>
<span id="el_regranegocio_nu_area" class="control-group">
<select data-field="x_nu_area" id="x_nu_area" name="x_nu_area"<?php echo $regranegocio->nu_area->EditAttributes() ?>>
<?php
if (is_array($regranegocio->nu_area->EditValue)) {
	$arwrk = $regranegocio->nu_area->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->nu_area->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fregranegocioadd.Lists["x_nu_area"].Options = <?php echo (is_array($regranegocio->nu_area->EditValue)) ? ew_ArrayToJson($regranegocio->nu_area->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $regranegocio->nu_area->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($regranegocio->ds_origemRegra->Visible) { // ds_origemRegra ?>
	<tr id="r_ds_origemRegra">
		<td><span id="elh_regranegocio_ds_origemRegra"><?php echo $regranegocio->ds_origemRegra->FldCaption() ?></span></td>
		<td<?php echo $regranegocio->ds_origemRegra->CellAttributes() ?>>
<span id="el_regranegocio_ds_origemRegra" class="control-group">
<textarea data-field="x_ds_origemRegra" name="x_ds_origemRegra" id="x_ds_origemRegra" cols="35" rows="4" placeholder="<?php echo $regranegocio->ds_origemRegra->PlaceHolder ?>"<?php echo $regranegocio->ds_origemRegra->EditAttributes() ?>><?php echo $regranegocio->ds_origemRegra->EditValue ?></textarea>
</span>
<?php echo $regranegocio->ds_origemRegra->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($regranegocio->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_regranegocio_nu_projeto"><?php echo $regranegocio->nu_projeto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $regranegocio->nu_projeto->CellAttributes() ?>>
<span id="el_regranegocio_nu_projeto" class="control-group">
<select data-field="x_nu_projeto" id="x_nu_projeto" name="x_nu_projeto"<?php echo $regranegocio->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($regranegocio->nu_projeto->EditValue)) {
	$arwrk = $regranegocio->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fregranegocioadd.Lists["x_nu_projeto"].Options = <?php echo (is_array($regranegocio->nu_projeto->EditValue)) ? ew_ArrayToJson($regranegocio->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $regranegocio->nu_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($regranegocio->no_tags->Visible) { // no_tags ?>
	<tr id="r_no_tags">
		<td><span id="elh_regranegocio_no_tags"><?php echo $regranegocio->no_tags->FldCaption() ?></span></td>
		<td<?php echo $regranegocio->no_tags->CellAttributes() ?>>
<span id="el_regranegocio_no_tags" class="control-group">
<input type="text" data-field="x_no_tags" name="x_no_tags" id="x_no_tags" size="30" maxlength="120" placeholder="<?php echo $regranegocio->no_tags->PlaceHolder ?>" value="<?php echo $regranegocio->no_tags->EditValue ?>"<?php echo $regranegocio->no_tags->EditAttributes() ?>>
</span>
<?php echo $regranegocio->no_tags->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($regranegocio->nu_stRegraNegocio->Visible) { // nu_stRegraNegocio ?>
	<tr id="r_nu_stRegraNegocio">
		<td><span id="elh_regranegocio_nu_stRegraNegocio"><?php echo $regranegocio->nu_stRegraNegocio->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $regranegocio->nu_stRegraNegocio->CellAttributes() ?>>
<span id="el_regranegocio_nu_stRegraNegocio" class="control-group">
<select data-field="x_nu_stRegraNegocio" id="x_nu_stRegraNegocio" name="x_nu_stRegraNegocio"<?php echo $regranegocio->nu_stRegraNegocio->EditAttributes() ?>>
<?php
if (is_array($regranegocio->nu_stRegraNegocio->EditValue)) {
	$arwrk = $regranegocio->nu_stRegraNegocio->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($regranegocio->nu_stRegraNegocio->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fregranegocioadd.Lists["x_nu_stRegraNegocio"].Options = <?php echo (is_array($regranegocio->nu_stRegraNegocio->EditValue)) ? ew_ArrayToJson($regranegocio->nu_stRegraNegocio->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $regranegocio->nu_stRegraNegocio->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fregranegocioadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$regranegocio_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");        

$(document).ajaxSend(function(event, jqxhr, settings) {                                                  
	var data = settings.data;      

	//alert(data); // Uncomment to view data
	//if (ew_Get("type", data) == "updateopt") && ew_Get("name", data) == "x_co_rn")) // Ajax selection list

		settings.data = data.replace("RN00001", "regra"); // Replace data with custom data
});
</script>
<?php include_once "footer.php" ?>
<?php
$regranegocio_add->Page_Terminate();
?>
