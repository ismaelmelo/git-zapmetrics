<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg10.php" ?>
<?php include_once "adodb5/adodb.inc.php" ?>
<?php include_once "phpfn10.php" ?>
<?php include_once "osinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn10.php" ?>
<?php

//
// Page class
//

$os_add = NULL; // Initialize page object first

class cos_add extends cos {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F59C7BF3-F287-4BE0-9F86-FCB94F808AF8}";

	// Table name
	var $TableName = 'os';

	// Page object name
	var $PageObjName = 'os_add';

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

		// Table object (os)
		if (!isset($GLOBALS["os"])) {
			$GLOBALS["os"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["os"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'os', TRUE);

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
			$this->Page_Terminate("oslist.php");
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
			if (@$_GET["nu_os"] != "") {
				$this->nu_os->setQueryStringValue($_GET["nu_os"]);
				$this->setKey("nu_os", $this->nu_os->CurrentValue); // Set up key
			} else {
				$this->setKey("nu_os", ""); // Clear key
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
					$this->Page_Terminate("oslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "osview.php")
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
		$this->co_os->CurrentValue = "1";
		$this->no_titulo->CurrentValue = NULL;
		$this->no_titulo->OldValue = $this->no_titulo->CurrentValue;
		$this->nu_contrato->CurrentValue = NULL;
		$this->nu_contrato->OldValue = $this->nu_contrato->CurrentValue;
		$this->nu_itemContratado->CurrentValue = NULL;
		$this->nu_itemContratado->OldValue = $this->nu_itemContratado->CurrentValue;
		$this->nu_areaSolicitante->CurrentValue = NULL;
		$this->nu_areaSolicitante->OldValue = $this->nu_areaSolicitante->CurrentValue;
		$this->nu_projeto->CurrentValue = NULL;
		$this->nu_projeto->OldValue = $this->nu_projeto->CurrentValue;
		$this->dt_criacaoOs->CurrentValue = NULL;
		$this->dt_criacaoOs->OldValue = $this->dt_criacaoOs->CurrentValue;
		$this->dt_entrega->CurrentValue = NULL;
		$this->dt_entrega->OldValue = $this->dt_entrega->CurrentValue;
		$this->nu_stOs->CurrentValue = NULL;
		$this->nu_stOs->OldValue = $this->nu_stOs->CurrentValue;
		$this->dt_stOs->CurrentValue = NULL;
		$this->dt_stOs->OldValue = $this->dt_stOs->CurrentValue;
		$this->nu_usuarioAnalista->CurrentValue = NULL;
		$this->nu_usuarioAnalista->OldValue = $this->nu_usuarioAnalista->CurrentValue;
		$this->ds_observacoes->CurrentValue = NULL;
		$this->ds_observacoes->OldValue = $this->ds_observacoes->CurrentValue;
		$this->vr_os->CurrentValue = NULL;
		$this->vr_os->OldValue = $this->vr_os->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->co_os->FldIsDetailKey) {
			$this->co_os->setFormValue($objForm->GetValue("x_co_os"));
		}
		if (!$this->no_titulo->FldIsDetailKey) {
			$this->no_titulo->setFormValue($objForm->GetValue("x_no_titulo"));
		}
		if (!$this->nu_contrato->FldIsDetailKey) {
			$this->nu_contrato->setFormValue($objForm->GetValue("x_nu_contrato"));
		}
		if (!$this->nu_itemContratado->FldIsDetailKey) {
			$this->nu_itemContratado->setFormValue($objForm->GetValue("x_nu_itemContratado"));
		}
		if (!$this->nu_areaSolicitante->FldIsDetailKey) {
			$this->nu_areaSolicitante->setFormValue($objForm->GetValue("x_nu_areaSolicitante"));
		}
		if (!$this->nu_projeto->FldIsDetailKey) {
			$this->nu_projeto->setFormValue($objForm->GetValue("x_nu_projeto"));
		}
		if (!$this->dt_criacaoOs->FldIsDetailKey) {
			$this->dt_criacaoOs->setFormValue($objForm->GetValue("x_dt_criacaoOs"));
			$this->dt_criacaoOs->CurrentValue = ew_UnFormatDateTime($this->dt_criacaoOs->CurrentValue, 7);
		}
		if (!$this->dt_entrega->FldIsDetailKey) {
			$this->dt_entrega->setFormValue($objForm->GetValue("x_dt_entrega"));
			$this->dt_entrega->CurrentValue = ew_UnFormatDateTime($this->dt_entrega->CurrentValue, 7);
		}
		if (!$this->nu_stOs->FldIsDetailKey) {
			$this->nu_stOs->setFormValue($objForm->GetValue("x_nu_stOs"));
		}
		if (!$this->dt_stOs->FldIsDetailKey) {
			$this->dt_stOs->setFormValue($objForm->GetValue("x_dt_stOs"));
			$this->dt_stOs->CurrentValue = ew_UnFormatDateTime($this->dt_stOs->CurrentValue, 7);
		}
		if (!$this->nu_usuarioAnalista->FldIsDetailKey) {
			$this->nu_usuarioAnalista->setFormValue($objForm->GetValue("x_nu_usuarioAnalista"));
		}
		if (!$this->ds_observacoes->FldIsDetailKey) {
			$this->ds_observacoes->setFormValue($objForm->GetValue("x_ds_observacoes"));
		}
		if (!$this->vr_os->FldIsDetailKey) {
			$this->vr_os->setFormValue($objForm->GetValue("x_vr_os"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->co_os->CurrentValue = $this->co_os->FormValue;
		$this->no_titulo->CurrentValue = $this->no_titulo->FormValue;
		$this->nu_contrato->CurrentValue = $this->nu_contrato->FormValue;
		$this->nu_itemContratado->CurrentValue = $this->nu_itemContratado->FormValue;
		$this->nu_areaSolicitante->CurrentValue = $this->nu_areaSolicitante->FormValue;
		$this->nu_projeto->CurrentValue = $this->nu_projeto->FormValue;
		$this->dt_criacaoOs->CurrentValue = $this->dt_criacaoOs->FormValue;
		$this->dt_criacaoOs->CurrentValue = ew_UnFormatDateTime($this->dt_criacaoOs->CurrentValue, 7);
		$this->dt_entrega->CurrentValue = $this->dt_entrega->FormValue;
		$this->dt_entrega->CurrentValue = ew_UnFormatDateTime($this->dt_entrega->CurrentValue, 7);
		$this->nu_stOs->CurrentValue = $this->nu_stOs->FormValue;
		$this->dt_stOs->CurrentValue = $this->dt_stOs->FormValue;
		$this->dt_stOs->CurrentValue = ew_UnFormatDateTime($this->dt_stOs->CurrentValue, 7);
		$this->nu_usuarioAnalista->CurrentValue = $this->nu_usuarioAnalista->FormValue;
		$this->ds_observacoes->CurrentValue = $this->ds_observacoes->FormValue;
		$this->vr_os->CurrentValue = $this->vr_os->FormValue;
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
		$this->nu_os->setDbValue($rs->fields('nu_os'));
		$this->co_os->setDbValue($rs->fields('co_os'));
		$this->no_titulo->setDbValue($rs->fields('no_titulo'));
		$this->nu_contrato->setDbValue($rs->fields('nu_contrato'));
		$this->nu_itemContratado->setDbValue($rs->fields('nu_itemContratado'));
		$this->nu_areaSolicitante->setDbValue($rs->fields('nu_areaSolicitante'));
		$this->nu_projeto->setDbValue($rs->fields('nu_projeto'));
		if (array_key_exists('EV__nu_projeto', $rs->fields)) {
			$this->nu_projeto->VirtualValue = $rs->fields('EV__nu_projeto'); // Set up virtual field value
		} else {
			$this->nu_projeto->VirtualValue = ""; // Clear value
		}
		$this->dt_criacaoOs->setDbValue($rs->fields('dt_criacaoOs'));
		$this->dt_entrega->setDbValue($rs->fields('dt_entrega'));
		$this->nu_stOs->setDbValue($rs->fields('nu_stOs'));
		$this->dt_stOs->setDbValue($rs->fields('dt_stOs'));
		$this->nu_usuarioAnalista->setDbValue($rs->fields('nu_usuarioAnalista'));
		$this->ds_observacoes->setDbValue($rs->fields('ds_observacoes'));
		$this->vr_os->setDbValue($rs->fields('vr_os'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->nu_os->DbValue = $row['nu_os'];
		$this->co_os->DbValue = $row['co_os'];
		$this->no_titulo->DbValue = $row['no_titulo'];
		$this->nu_contrato->DbValue = $row['nu_contrato'];
		$this->nu_itemContratado->DbValue = $row['nu_itemContratado'];
		$this->nu_areaSolicitante->DbValue = $row['nu_areaSolicitante'];
		$this->nu_projeto->DbValue = $row['nu_projeto'];
		$this->dt_criacaoOs->DbValue = $row['dt_criacaoOs'];
		$this->dt_entrega->DbValue = $row['dt_entrega'];
		$this->nu_stOs->DbValue = $row['nu_stOs'];
		$this->dt_stOs->DbValue = $row['dt_stOs'];
		$this->nu_usuarioAnalista->DbValue = $row['nu_usuarioAnalista'];
		$this->ds_observacoes->DbValue = $row['ds_observacoes'];
		$this->vr_os->DbValue = $row['vr_os'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("nu_os")) <> "")
			$this->nu_os->CurrentValue = $this->getKey("nu_os"); // nu_os
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
		// Convert decimal values if posted back

		if ($this->vr_os->FormValue == $this->vr_os->CurrentValue && is_numeric(ew_StrToFloat($this->vr_os->CurrentValue)))
			$this->vr_os->CurrentValue = ew_StrToFloat($this->vr_os->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// nu_os
		// co_os
		// no_titulo
		// nu_contrato
		// nu_itemContratado
		// nu_areaSolicitante
		// nu_projeto
		// dt_criacaoOs
		// dt_entrega
		// nu_stOs
		// dt_stOs
		// nu_usuarioAnalista
		// ds_observacoes
		// vr_os

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// nu_os
			$this->nu_os->ViewValue = $this->nu_os->CurrentValue;
			$this->nu_os->ViewCustomAttributes = "";

			// co_os
			$this->co_os->ViewValue = $this->co_os->CurrentValue;
			$this->co_os->ViewValue = ew_FormatNumber($this->co_os->ViewValue, 0, 0, 0, 0);
			$this->co_os->ViewCustomAttributes = "";

			// no_titulo
			$this->no_titulo->ViewValue = $this->no_titulo->CurrentValue;
			$this->no_titulo->ViewCustomAttributes = "";

			// nu_contrato
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
			$sSqlWrk .= " ORDER BY [nu_contrato] ASC";
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
			$this->nu_contrato->ViewCustomAttributes = "";

			// nu_itemContratado
			if (strval($this->nu_itemContratado->CurrentValue) <> "") {
				$sFilterWrk = "[nu_itemContratado]" . ew_SearchString("=", $this->nu_itemContratado->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContratado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_itemContratado->ViewValue = $rswrk->fields('DispFld');
					$this->nu_itemContratado->ViewValue .= ew_ValueSeparator(1,$this->nu_itemContratado) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->nu_itemContratado->ViewValue = $this->nu_itemContratado->CurrentValue;
				}
			} else {
				$this->nu_itemContratado->ViewValue = NULL;
			}
			$this->nu_itemContratado->ViewCustomAttributes = "";

			// nu_areaSolicitante
			if (strval($this->nu_areaSolicitante->CurrentValue) <> "") {
				$sFilterWrk = "[nu_area]" . ew_SearchString("=", $this->nu_areaSolicitante->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[area]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaSolicitante, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_areaSolicitante->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_areaSolicitante->ViewValue = $this->nu_areaSolicitante->CurrentValue;
				}
			} else {
				$this->nu_areaSolicitante->ViewValue = NULL;
			}
			$this->nu_areaSolicitante->ViewCustomAttributes = "";

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
			}
			$this->nu_projeto->ViewCustomAttributes = "";

			// dt_criacaoOs
			$this->dt_criacaoOs->ViewValue = $this->dt_criacaoOs->CurrentValue;
			$this->dt_criacaoOs->ViewValue = ew_FormatDateTime($this->dt_criacaoOs->ViewValue, 7);
			$this->dt_criacaoOs->ViewCustomAttributes = "";

			// dt_entrega
			$this->dt_entrega->ViewValue = $this->dt_entrega->CurrentValue;
			$this->dt_entrega->ViewValue = ew_FormatDateTime($this->dt_entrega->ViewValue, 7);
			$this->dt_entrega->ViewCustomAttributes = "";

			// nu_stOs
			if (strval($this->nu_stOs->CurrentValue) <> "") {
				$sFilterWrk = "[nu_stOs]" . ew_SearchString("=", $this->nu_stOs->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_stOs], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[stos]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stOs, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stUc] ASC";
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_stOs->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_stOs->ViewValue = $this->nu_stOs->CurrentValue;
				}
			} else {
				$this->nu_stOs->ViewValue = NULL;
			}
			$this->nu_stOs->ViewCustomAttributes = "";

			// dt_stOs
			$this->dt_stOs->ViewValue = $this->dt_stOs->CurrentValue;
			$this->dt_stOs->ViewValue = ew_FormatDateTime($this->dt_stOs->ViewValue, 7);
			$this->dt_stOs->ViewCustomAttributes = "";

			// nu_usuarioAnalista
			if (strval($this->nu_usuarioAnalista->CurrentValue) <> "") {
				$sFilterWrk = "[nu_usuario]" . ew_SearchString("=", $this->nu_usuarioAnalista->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAnalista, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->nu_usuarioAnalista->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->nu_usuarioAnalista->ViewValue = $this->nu_usuarioAnalista->CurrentValue;
				}
			} else {
				$this->nu_usuarioAnalista->ViewValue = NULL;
			}
			$this->nu_usuarioAnalista->ViewCustomAttributes = "";

			// ds_observacoes
			$this->ds_observacoes->ViewValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->ViewCustomAttributes = "";

			// vr_os
			$this->vr_os->ViewValue = $this->vr_os->CurrentValue;
			$this->vr_os->ViewValue = ew_FormatCurrency($this->vr_os->ViewValue, 2, -2, -2, -2);
			$this->vr_os->ViewCustomAttributes = "";

			// co_os
			$this->co_os->LinkCustomAttributes = "";
			$this->co_os->HrefValue = "";
			$this->co_os->TooltipValue = "";

			// no_titulo
			$this->no_titulo->LinkCustomAttributes = "";
			$this->no_titulo->HrefValue = "";
			$this->no_titulo->TooltipValue = "";

			// nu_contrato
			$this->nu_contrato->LinkCustomAttributes = "";
			$this->nu_contrato->HrefValue = "";
			$this->nu_contrato->TooltipValue = "";

			// nu_itemContratado
			$this->nu_itemContratado->LinkCustomAttributes = "";
			$this->nu_itemContratado->HrefValue = "";
			$this->nu_itemContratado->TooltipValue = "";

			// nu_areaSolicitante
			$this->nu_areaSolicitante->LinkCustomAttributes = "";
			$this->nu_areaSolicitante->HrefValue = "";
			$this->nu_areaSolicitante->TooltipValue = "";

			// nu_projeto
			$this->nu_projeto->LinkCustomAttributes = "";
			$this->nu_projeto->HrefValue = "";
			$this->nu_projeto->TooltipValue = "";

			// dt_criacaoOs
			$this->dt_criacaoOs->LinkCustomAttributes = "";
			$this->dt_criacaoOs->HrefValue = "";
			$this->dt_criacaoOs->TooltipValue = "";

			// dt_entrega
			$this->dt_entrega->LinkCustomAttributes = "";
			$this->dt_entrega->HrefValue = "";
			$this->dt_entrega->TooltipValue = "";

			// nu_stOs
			$this->nu_stOs->LinkCustomAttributes = "";
			$this->nu_stOs->HrefValue = "";
			$this->nu_stOs->TooltipValue = "";

			// dt_stOs
			$this->dt_stOs->LinkCustomAttributes = "";
			$this->dt_stOs->HrefValue = "";
			$this->dt_stOs->TooltipValue = "";

			// nu_usuarioAnalista
			$this->nu_usuarioAnalista->LinkCustomAttributes = "";
			$this->nu_usuarioAnalista->HrefValue = "";
			$this->nu_usuarioAnalista->TooltipValue = "";

			// ds_observacoes
			$this->ds_observacoes->LinkCustomAttributes = "";
			$this->ds_observacoes->HrefValue = "";
			$this->ds_observacoes->TooltipValue = "";

			// vr_os
			$this->vr_os->LinkCustomAttributes = "";
			$this->vr_os->HrefValue = "";
			$this->vr_os->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// co_os
			$this->co_os->EditCustomAttributes = "";
			$this->co_os->EditValue = ew_HtmlEncode($this->co_os->CurrentValue);
			$this->co_os->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->co_os->FldCaption()));

			// no_titulo
			$this->no_titulo->EditCustomAttributes = "";
			$this->no_titulo->EditValue = ew_HtmlEncode($this->no_titulo->CurrentValue);
			$this->no_titulo->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->no_titulo->FldCaption()));

			// nu_contrato
			$this->nu_contrato->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_contrato], [nu_contrato] AS [DispFld], [no_contrato] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[contrato]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_contrato, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [nu_contrato] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_contrato->EditValue = $arwrk;

			// nu_itemContratado
			$this->nu_itemContratado->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_itemContratado], [nu_itemOc] AS [DispFld], [no_itemContratado] AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], [nu_contrato] AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[item_contratado]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_itemContratado, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_itemContratado->EditValue = $arwrk;

			// nu_areaSolicitante
			$this->nu_areaSolicitante->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_area], [no_area] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[area]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_areaSolicitante, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_area] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_areaSolicitante->EditValue = $arwrk;

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

			// dt_criacaoOs
			// dt_entrega

			$this->dt_entrega->EditCustomAttributes = "";
			$this->dt_entrega->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->dt_entrega->CurrentValue, 7));
			$this->dt_entrega->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->dt_entrega->FldCaption()));

			// nu_stOs
			$this->nu_stOs->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_stOs], [no_stUc] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[stos]";
			$sWhereWrk = "";
			$lookuptblfilter = "[ic_ativo]='S'";
			if (strval($lookuptblfilter) <> "") {
				ew_AddFilter($sWhereWrk, $lookuptblfilter);
			}
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_stOs, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " ORDER BY [no_stUc] ASC";
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_stOs->EditValue = $arwrk;

			// dt_stOs
			// nu_usuarioAnalista

			$this->nu_usuarioAnalista->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT [nu_usuario], [no_usuario] AS [DispFld], '' AS [Disp2Fld], '' AS [Disp3Fld], '' AS [Disp4Fld], '' AS [SelectFilterFld], '' AS [SelectFilterFld2], '' AS [SelectFilterFld3], '' AS [SelectFilterFld4] FROM [dbo].[usuario]";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if (!$GLOBALS["os"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["usuario"]->AddUserIDFilter($sWhereWrk);

			// Call Lookup selecting
			$this->Lookup_Selecting($this->nu_usuarioAnalista, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->nu_usuarioAnalista->EditValue = $arwrk;

			// ds_observacoes
			$this->ds_observacoes->EditCustomAttributes = "";
			$this->ds_observacoes->EditValue = $this->ds_observacoes->CurrentValue;
			$this->ds_observacoes->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->ds_observacoes->FldCaption()));

			// vr_os
			$this->vr_os->EditCustomAttributes = "";
			$this->vr_os->EditValue = ew_HtmlEncode($this->vr_os->CurrentValue);
			$this->vr_os->PlaceHolder = ew_HtmlEncode(ew_RemoveHtml($this->vr_os->FldCaption()));
			if (strval($this->vr_os->EditValue) <> "" && is_numeric($this->vr_os->EditValue)) $this->vr_os->EditValue = ew_FormatNumber($this->vr_os->EditValue, -2, -2, -2, -2);

			// Edit refer script
			// co_os

			$this->co_os->HrefValue = "";

			// no_titulo
			$this->no_titulo->HrefValue = "";

			// nu_contrato
			$this->nu_contrato->HrefValue = "";

			// nu_itemContratado
			$this->nu_itemContratado->HrefValue = "";

			// nu_areaSolicitante
			$this->nu_areaSolicitante->HrefValue = "";

			// nu_projeto
			$this->nu_projeto->HrefValue = "";

			// dt_criacaoOs
			$this->dt_criacaoOs->HrefValue = "";

			// dt_entrega
			$this->dt_entrega->HrefValue = "";

			// nu_stOs
			$this->nu_stOs->HrefValue = "";

			// dt_stOs
			$this->dt_stOs->HrefValue = "";

			// nu_usuarioAnalista
			$this->nu_usuarioAnalista->HrefValue = "";

			// ds_observacoes
			$this->ds_observacoes->HrefValue = "";

			// vr_os
			$this->vr_os->HrefValue = "";
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
		if (!$this->co_os->FldIsDetailKey && !is_null($this->co_os->FormValue) && $this->co_os->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->co_os->FldCaption());
		}
		if (!ew_CheckInteger($this->co_os->FormValue)) {
			ew_AddMessage($gsFormError, $this->co_os->FldErrMsg());
		}
		if (!$this->no_titulo->FldIsDetailKey && !is_null($this->no_titulo->FormValue) && $this->no_titulo->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->no_titulo->FldCaption());
		}
		if (!$this->nu_areaSolicitante->FldIsDetailKey && !is_null($this->nu_areaSolicitante->FormValue) && $this->nu_areaSolicitante->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_areaSolicitante->FldCaption());
		}
		if (!ew_CheckEuroDate($this->dt_entrega->FormValue)) {
			ew_AddMessage($gsFormError, $this->dt_entrega->FldErrMsg());
		}
		if (!$this->nu_stOs->FldIsDetailKey && !is_null($this->nu_stOs->FormValue) && $this->nu_stOs->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_stOs->FldCaption());
		}
		if (!$this->nu_usuarioAnalista->FldIsDetailKey && !is_null($this->nu_usuarioAnalista->FormValue) && $this->nu_usuarioAnalista->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->nu_usuarioAnalista->FldCaption());
		}
		if (!$this->vr_os->FldIsDetailKey && !is_null($this->vr_os->FormValue) && $this->vr_os->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->vr_os->FldCaption());
		}
		if (!ew_CheckNumber($this->vr_os->FormValue)) {
			ew_AddMessage($gsFormError, $this->vr_os->FldErrMsg());
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

		// co_os
		$this->co_os->SetDbValueDef($rsnew, $this->co_os->CurrentValue, NULL, FALSE);

		// no_titulo
		$this->no_titulo->SetDbValueDef($rsnew, $this->no_titulo->CurrentValue, "", FALSE);

		// nu_contrato
		$this->nu_contrato->SetDbValueDef($rsnew, $this->nu_contrato->CurrentValue, NULL, FALSE);

		// nu_itemContratado
		$this->nu_itemContratado->SetDbValueDef($rsnew, $this->nu_itemContratado->CurrentValue, NULL, FALSE);

		// nu_areaSolicitante
		$this->nu_areaSolicitante->SetDbValueDef($rsnew, $this->nu_areaSolicitante->CurrentValue, 0, FALSE);

		// nu_projeto
		$this->nu_projeto->SetDbValueDef($rsnew, $this->nu_projeto->CurrentValue, NULL, FALSE);

		// dt_criacaoOs
		$this->dt_criacaoOs->SetDbValueDef($rsnew, ew_CurrentDate(), ew_CurrentDate());
		$rsnew['dt_criacaoOs'] = &$this->dt_criacaoOs->DbValue;

		// dt_entrega
		$this->dt_entrega->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->dt_entrega->CurrentValue, 7), NULL, FALSE);

		// nu_stOs
		$this->nu_stOs->SetDbValueDef($rsnew, $this->nu_stOs->CurrentValue, NULL, FALSE);

		// dt_stOs
		$this->dt_stOs->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
		$rsnew['dt_stOs'] = &$this->dt_stOs->DbValue;

		// nu_usuarioAnalista
		$this->nu_usuarioAnalista->SetDbValueDef($rsnew, $this->nu_usuarioAnalista->CurrentValue, NULL, FALSE);

		// ds_observacoes
		$this->ds_observacoes->SetDbValueDef($rsnew, $this->ds_observacoes->CurrentValue, NULL, FALSE);

		// vr_os
		$this->vr_os->SetDbValueDef($rsnew, $this->vr_os->CurrentValue, NULL, FALSE);

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
			$this->nu_os->setDbValue($conn->Insert_ID());
			$rsnew['nu_os'] = $this->nu_os->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
			$this->WriteAuditTrailOnAdd($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$PageCaption = $this->TableCaption();
		$Breadcrumb->Add("list", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", "oslist.php", $this->TableVar);
		$PageCaption = ($this->CurrentAction == "C") ? $Language->Phrase("Copy") : $Language->Phrase("Add");
		$Breadcrumb->Add("add", "<span id=\"ewPageCaption\">" . $PageCaption . "</span>", ew_CurrentUrl(), $this->TableVar);
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'os';
	  $usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		if (!$this->AuditTrailOnAdd) return;
		$table = 'os';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['nu_os'];

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

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($os_add)) $os_add = new cos_add();

// Page init
$os_add->Page_Init();

// Page main
$os_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$os_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var os_add = new ew_Page("os_add");
os_add.PageID = "add"; // Page ID
var EW_PAGE_ID = os_add.PageID; // For backward compatibility

// Form object
var fosadd = new ew_Form("fosadd");

// Validate form
fosadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_co_os");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($os->co_os->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_co_os");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($os->co_os->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_titulo");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($os->no_titulo->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_areaSolicitante");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($os->nu_areaSolicitante->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_dt_entrega");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($os->dt_entrega->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nu_stOs");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($os->nu_stOs->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_nu_usuarioAnalista");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($os->nu_usuarioAnalista->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_os");
			if (elm && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($os->vr_os->FldCaption()) ?>");
			elm = this.GetElements("x" + infix + "_vr_os");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($os->vr_os->FldErrMsg()) ?>");

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
fosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fosadd.ValidateRequired = true;
<?php } else { ?>
fosadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fosadd.Lists["x_nu_contrato"] = {"LinkField":"x_nu_contrato","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_contrato","x_no_contrato","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fosadd.Lists["x_nu_itemContratado"] = {"LinkField":"x_nu_itemContratado","Ajax":null,"AutoFill":false,"DisplayFields":["x_nu_itemOc","x_no_itemContratado","",""],"ParentFields":["x_nu_contrato"],"FilterFields":["x_nu_contrato"],"Options":[]};
fosadd.Lists["x_nu_areaSolicitante"] = {"LinkField":"x_nu_area","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_area","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fosadd.Lists["x_nu_projeto"] = {"LinkField":"x_nu_projeto","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_projeto","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fosadd.Lists["x_nu_stOs"] = {"LinkField":"x_nu_stOs","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_stUc","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fosadd.Lists["x_nu_usuarioAnalista"] = {"LinkField":"x_nu_usuario","Ajax":null,"AutoFill":false,"DisplayFields":["x_no_usuario","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $Breadcrumb->Render(); ?>
<?php $os_add->ShowPageHeader(); ?>
<?php
$os_add->ShowMessage();
?>
<form name="fosadd" id="fosadd" class="ewForm form-horizontal" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="os">
<input type="hidden" name="a_add" id="a_add" value="A">
<table cellspacing="0" class="ewGrid"><tr><td>
<table id="tbl_osadd" class="table table-bordered table-striped">
<?php if ($os->co_os->Visible) { // co_os ?>
	<tr id="r_co_os">
		<td><span id="elh_os_co_os"><?php echo $os->co_os->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $os->co_os->CellAttributes() ?>>
<span id="el_os_co_os" class="control-group">
<input type="text" data-field="x_co_os" name="x_co_os" id="x_co_os" size="30" placeholder="<?php echo $os->co_os->PlaceHolder ?>" value="<?php echo $os->co_os->EditValue ?>"<?php echo $os->co_os->EditAttributes() ?>>
</span>
<?php echo $os->co_os->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->no_titulo->Visible) { // no_titulo ?>
	<tr id="r_no_titulo">
		<td><span id="elh_os_no_titulo"><?php echo $os->no_titulo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $os->no_titulo->CellAttributes() ?>>
<span id="el_os_no_titulo" class="control-group">
<input type="text" data-field="x_no_titulo" name="x_no_titulo" id="x_no_titulo" size="30" maxlength="100" placeholder="<?php echo $os->no_titulo->PlaceHolder ?>" value="<?php echo $os->no_titulo->EditValue ?>"<?php echo $os->no_titulo->EditAttributes() ?>>
</span>
<?php echo $os->no_titulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->nu_contrato->Visible) { // nu_contrato ?>
	<tr id="r_nu_contrato">
		<td><span id="elh_os_nu_contrato"><?php echo $os->nu_contrato->FldCaption() ?></span></td>
		<td<?php echo $os->nu_contrato->CellAttributes() ?>>
<span id="el_os_nu_contrato" class="control-group">
<?php $os->nu_contrato->EditAttrs["onchange"] = "ew_UpdateOpt.call(this, ['x_nu_itemContratado']); " . @$os->nu_contrato->EditAttrs["onchange"]; ?>
<select data-field="x_nu_contrato" id="x_nu_contrato" name="x_nu_contrato"<?php echo $os->nu_contrato->EditAttributes() ?>>
<?php
if (is_array($os->nu_contrato->EditValue)) {
	$arwrk = $os->nu_contrato->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($os->nu_contrato->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$os->nu_contrato) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fosadd.Lists["x_nu_contrato"].Options = <?php echo (is_array($os->nu_contrato->EditValue)) ? ew_ArrayToJson($os->nu_contrato->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $os->nu_contrato->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->nu_itemContratado->Visible) { // nu_itemContratado ?>
	<tr id="r_nu_itemContratado">
		<td><span id="elh_os_nu_itemContratado"><?php echo $os->nu_itemContratado->FldCaption() ?></span></td>
		<td<?php echo $os->nu_itemContratado->CellAttributes() ?>>
<span id="el_os_nu_itemContratado" class="control-group">
<select data-field="x_nu_itemContratado" id="x_nu_itemContratado" name="x_nu_itemContratado"<?php echo $os->nu_itemContratado->EditAttributes() ?>>
<?php
if (is_array($os->nu_itemContratado->EditValue)) {
	$arwrk = $os->nu_itemContratado->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($os->nu_itemContratado->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$os->nu_itemContratado) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fosadd.Lists["x_nu_itemContratado"].Options = <?php echo (is_array($os->nu_itemContratado->EditValue)) ? ew_ArrayToJson($os->nu_itemContratado->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $os->nu_itemContratado->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->nu_areaSolicitante->Visible) { // nu_areaSolicitante ?>
	<tr id="r_nu_areaSolicitante">
		<td><span id="elh_os_nu_areaSolicitante"><?php echo $os->nu_areaSolicitante->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $os->nu_areaSolicitante->CellAttributes() ?>>
<span id="el_os_nu_areaSolicitante" class="control-group">
<select data-field="x_nu_areaSolicitante" id="x_nu_areaSolicitante" name="x_nu_areaSolicitante"<?php echo $os->nu_areaSolicitante->EditAttributes() ?>>
<?php
if (is_array($os->nu_areaSolicitante->EditValue)) {
	$arwrk = $os->nu_areaSolicitante->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($os->nu_areaSolicitante->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fosadd.Lists["x_nu_areaSolicitante"].Options = <?php echo (is_array($os->nu_areaSolicitante->EditValue)) ? ew_ArrayToJson($os->nu_areaSolicitante->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $os->nu_areaSolicitante->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->nu_projeto->Visible) { // nu_projeto ?>
	<tr id="r_nu_projeto">
		<td><span id="elh_os_nu_projeto"><?php echo $os->nu_projeto->FldCaption() ?></span></td>
		<td<?php echo $os->nu_projeto->CellAttributes() ?>>
<span id="el_os_nu_projeto" class="control-group">
<select data-field="x_nu_projeto" id="x_nu_projeto" name="x_nu_projeto"<?php echo $os->nu_projeto->EditAttributes() ?>>
<?php
if (is_array($os->nu_projeto->EditValue)) {
	$arwrk = $os->nu_projeto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($os->nu_projeto->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php if (AllowAdd(CurrentProjectID() . "projeto")) { ?>
&nbsp;<a id="aol_x_nu_projeto" class="ewAddOptLink" href="javascript:void(0);" onclick="ew_AddOptDialogShow({lnk:this,el:'x_nu_projeto',url:'projetoaddopt.php'});"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $os->nu_projeto->FldCaption() ?></a>
<?php } ?>
<script type="text/javascript">
fosadd.Lists["x_nu_projeto"].Options = <?php echo (is_array($os->nu_projeto->EditValue)) ? ew_ArrayToJson($os->nu_projeto->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $os->nu_projeto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->dt_entrega->Visible) { // dt_entrega ?>
	<tr id="r_dt_entrega">
		<td><span id="elh_os_dt_entrega"><?php echo $os->dt_entrega->FldCaption() ?></span></td>
		<td<?php echo $os->dt_entrega->CellAttributes() ?>>
<span id="el_os_dt_entrega" class="control-group">
<input type="text" data-field="x_dt_entrega" name="x_dt_entrega" id="x_dt_entrega" placeholder="<?php echo $os->dt_entrega->PlaceHolder ?>" value="<?php echo $os->dt_entrega->EditValue ?>"<?php echo $os->dt_entrega->EditAttributes() ?>>
<?php if (!$os->dt_entrega->ReadOnly && !$os->dt_entrega->Disabled && @$os->dt_entrega->EditAttrs["readonly"] == "" && @$os->dt_entrega->EditAttrs["disabled"] == "") { ?>
<button id="cal_x_dt_entrega" name="cal_x_dt_entrega" class="btn" type="button"><img src="phpimages/calendar.png" id="cal_x_dt_entrega" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" style="border: 0;"></button><script type="text/javascript">
ew_CreateCalendar("fosadd", "x_dt_entrega", "%d/%m/%Y");
</script>
<?php } ?>
</span>
<?php echo $os->dt_entrega->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->nu_stOs->Visible) { // nu_stOs ?>
	<tr id="r_nu_stOs">
		<td><span id="elh_os_nu_stOs"><?php echo $os->nu_stOs->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $os->nu_stOs->CellAttributes() ?>>
<span id="el_os_nu_stOs" class="control-group">
<select data-field="x_nu_stOs" id="x_nu_stOs" name="x_nu_stOs"<?php echo $os->nu_stOs->EditAttributes() ?>>
<?php
if (is_array($os->nu_stOs->EditValue)) {
	$arwrk = $os->nu_stOs->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($os->nu_stOs->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fosadd.Lists["x_nu_stOs"].Options = <?php echo (is_array($os->nu_stOs->EditValue)) ? ew_ArrayToJson($os->nu_stOs->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $os->nu_stOs->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->nu_usuarioAnalista->Visible) { // nu_usuarioAnalista ?>
	<tr id="r_nu_usuarioAnalista">
		<td><span id="elh_os_nu_usuarioAnalista"><?php echo $os->nu_usuarioAnalista->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $os->nu_usuarioAnalista->CellAttributes() ?>>
<span id="el_os_nu_usuarioAnalista" class="control-group">
<select data-field="x_nu_usuarioAnalista" id="x_nu_usuarioAnalista" name="x_nu_usuarioAnalista"<?php echo $os->nu_usuarioAnalista->EditAttributes() ?>>
<?php
if (is_array($os->nu_usuarioAnalista->EditValue)) {
	$arwrk = $os->nu_usuarioAnalista->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($os->nu_usuarioAnalista->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
fosadd.Lists["x_nu_usuarioAnalista"].Options = <?php echo (is_array($os->nu_usuarioAnalista->EditValue)) ? ew_ArrayToJson($os->nu_usuarioAnalista->EditValue, 1) : "[]" ?>;
</script>
</span>
<?php echo $os->nu_usuarioAnalista->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->ds_observacoes->Visible) { // ds_observacoes ?>
	<tr id="r_ds_observacoes">
		<td><span id="elh_os_ds_observacoes"><?php echo $os->ds_observacoes->FldCaption() ?></span></td>
		<td<?php echo $os->ds_observacoes->CellAttributes() ?>>
<span id="el_os_ds_observacoes" class="control-group">
<textarea data-field="x_ds_observacoes" name="x_ds_observacoes" id="x_ds_observacoes" cols="35" rows="4" placeholder="<?php echo $os->ds_observacoes->PlaceHolder ?>"<?php echo $os->ds_observacoes->EditAttributes() ?>><?php echo $os->ds_observacoes->EditValue ?></textarea>
</span>
<?php echo $os->ds_observacoes->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($os->vr_os->Visible) { // vr_os ?>
	<tr id="r_vr_os">
		<td><span id="elh_os_vr_os"><?php echo $os->vr_os->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $os->vr_os->CellAttributes() ?>>
<span id="el_os_vr_os" class="control-group">
<input type="text" data-field="x_vr_os" name="x_vr_os" id="x_vr_os" size="30" placeholder="<?php echo $os->vr_os->PlaceHolder ?>" value="<?php echo $os->vr_os->EditValue ?>"<?php echo $os->vr_os->EditAttributes() ?>>
</span>
<?php echo $os->vr_os->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</td></tr></table>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
</form>
<script type="text/javascript">
fosadd.Init();
<?php if (EW_MOBILE_REFLOW && ew_IsMobile()) { ?>
ew_Reflow();
<?php } ?>
</script>
<?php
$os_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$os_add->Page_Terminate();
?>
